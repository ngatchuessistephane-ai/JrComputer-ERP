<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Module5\SavTicket;
use App\Models\Module5\TicketItem;
use App\Models\Module5\Intervention;
use App\Models\Module5\SparePart;
use App\Models\Module1\StockMovement;
use App\Models\Module3\Invoice;
use App\Models\Module3\InvoiceItem;
use App\Models\User;
use App\Events\TicketClosed;
use App\Events\TicketUrgent;
use App\Events\CriticalPartAlert;
use App\Notifications\TicketClosedNotification;
use App\Notifications\CriticalPartNotification;
use App\Mail\TicketClosedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class SavApiController extends Controller
{
    // Récupérer les tickets assignés au technicien connecté
public function getMyTickets(Request $request)  // ✅ Ajouter Request
{
    try {
        // ✅ Récupérer l'utilisateur via la requête
        $user = $request->user();
        if (!$user) {
            \Log::error('❌ getMyTickets: Utilisateur non authentifié');
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié'
            ], 401);
        }

        $userId = $user->id;
        \Log::info('🔍 getMyTickets pour user: ' . $userId);

        // ✅ Version simplifiée pour tester
        $tickets = SavTicket::where('assigned_to', $userId)
            ->with(['customer', 'product'])
            ->orderBy('created_at', 'desc')
            ->get();

        // ✅ Charger les items séparément pour éviter les problèmes
        $ticketIds = $tickets->pluck('id')->toArray();
        if (!empty($ticketIds)) {
            $items = TicketItem::with('sparePart')
                ->whereIn('ticket_id', $ticketIds)
                ->get()
                ->groupBy('ticket_id');

            $tickets->each(function ($ticket) use ($items) {
                $ticket->items = $items->get($ticket->id, collect())->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'spare_part_id' => $item->spare_part_id,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'spare_part_name' => $item->sparePart ? $item->sparePart->name : null,
                    ];
                });
            });
        } else {
            $tickets->each(function ($ticket) {
                $ticket->items = collect();
            });
        }

        \Log::info('✅ getMyTickets: ' . $tickets->count() . ' tickets trouvés');

        return response()->json([
            'success' => true,
            'tickets' => $tickets
        ]);

    } catch (\Exception $e) {
        \Log::error('❌ Erreur getMyTickets: ' . $e->getMessage());
        \Log::error('📚 Trace: ' . $e->getTraceAsString());

        return response()->json([
            'success' => false,
            'message' => 'Erreur serveur: ' . $e->getMessage()
        ], 500);
    }
}
    // Mettre à jour le statut d'un ticket
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,assigned,diagnosing,repairing,completed,restituted'
        ]);

        $ticket = SavTicket::where('id', $id)
            ->where('assigned_to', Auth::id())
            ->firstOrFail();

        $oldStatus = $ticket->status;
        $ticket->status = $request->status;
         // ✅ CORRECTION : Si le statut passe à 'completed', définir closed_at
    if ($request->status === 'completed' && !$ticket->closed_at) {
        $ticket->closed_at = now();
    }
        $ticket->save();

        // ✅ Si le statut passe à "completed" ou "restituted", déclencher notification
        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            event(new TicketClosed($ticket));
            
            // Notifier admins et managers
            $admins = User::role('admin')->get();
            $managers = User::role('manager')->get();
            $adminManagerUsers = $admins->merge($managers);
            
            foreach ($adminManagerUsers as $user) {
                $user->notify(new TicketClosedNotification($ticket));
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour',
            'ticket' => $ticket
        ]);
    }

    // Clôturer une intervention (rapport + signature + pièces)
    public function closeTicket(Request $request, $id)
    {
        $request->validate([
            'technical_report' => 'required|string|min:10',
            'duration_minutes' => 'nullable|integer|min:1',
            'signature' => 'nullable|string',
            'parts' => 'nullable|array',
            'parts.*.spare_part_id' => 'required|exists:spare_parts,id',
            'parts.*.quantity' => 'required|integer|min:1',
            'parts.*.unit_price' => 'required|numeric|min:0',
        ]);

        $ticket = SavTicket::where('id', $id)
            ->where('assigned_to', Auth::id())
            ->firstOrFail();

        DB::transaction(function () use ($request, $ticket) {
            // 1. Sauvegarde de la signature
            $signaturePath = null;
            if ($request->signature) {
                $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->signature));
                $filename = 'signatures/ticket_' . $ticket->id . '_' . time() . '.png';
                Storage::disk('public')->put($filename, $imageData);
                $signaturePath = $filename;
            }

            // 2. Création ou mise à jour de l'intervention
            Intervention::updateOrCreate(
    ['ticket_id' => $ticket->id],
    [
        'technical_report' => $request->technical_report,
        'duration_minutes' => $request->duration_minutes,
        'client_signature' => $signaturePath,
        'synced' => true,
        'technician_id' => Auth::id(), // ✅ Ajouté
    ]
);

            // 3. Traitement des pièces utilisées et déstockage
            $partsData = [];
            $criticalPartsDetected = [];

            if ($request->parts && count($request->parts) > 0) {
                foreach ($request->parts as $part) {
                    // Enregistrement dans ticket_items
                    TicketItem::create([
                        'ticket_id' => $ticket->id,
                        'spare_part_id' => $part['spare_part_id'],
                        'quantity' => $part['quantity'],
                        'unit_price' => $part['unit_price'],
                    ]);

                    // Déstockage
                    $sparePart = SparePart::find($part['spare_part_id']);
                    $oldStock = $sparePart->quantity_in_stock;
                    $newStock = $oldStock - $part['quantity'];
                    $sparePart->quantity_in_stock = $newStock;
                    $sparePart->save();

                    // Mouvement de stock
                    StockMovement::create([
                        'spare_part_id' => $sparePart->id,
                        'type' => 'out',
                        'quantity' => $part['quantity'],
                        'reason' => 'SAV ticket #' . $ticket->ticket_number,
                        'user_id' => Auth::id(),
                    ]);

                    // ✅ Scénario 4 : Vérifier si stock devient critique (≤ seuil min)
                    if ($newStock <= $sparePart->min_stock_alert) {
                        $criticalPartsDetected[] = $sparePart;
                        
                        // Déclencher l'événement
                        event(new CriticalPartAlert($sparePart));
                        
                        // Notifier tous les techniciens SAV
                        $technicians = User::role('technicien_sav')->get();
                        foreach ($technicians as $tech) {
                            $tech->notify(new CriticalPartNotification($sparePart));
                        }
                    }

                    // Stockage pour la facture
                    $partsData[] = [
                        'id' => $sparePart->id,
                        'name' => $sparePart->name,
                        'reference' => $sparePart->reference,
                        'quantity' => $part['quantity'],
                        'unit_price' => $part['unit_price'],
                        'total' => $part['quantity'] * $part['unit_price']
                    ];
                }
            }

            // 4. Si hors garantie, générer une facture DÉTAILLÉE
            $invoice = null;
            if (!$ticket->is_warranty && $ticket->status !== 'completed') {
                $invoice = $this->generateDetailedInvoice($ticket, $request, $partsData);
            }

            // 5. Mettre à jour le statut du ticket
            $ticket->status = 'completed';
            $ticket->closed_at = now();
            $ticket->technical_report = $request->technical_report;
            $ticket->duration_minutes = $request->duration_minutes;
            $ticket->save();

            // ✅ Scénario 2 : Ticket clôturé - Déclencher événement et notifications
            event(new TicketClosed($ticket, $request->technical_report));
            
            // Notifier les admins et managers
            $admins = User::role('admin')->get();
            $managers = User::role('manager')->get();
            $adminManagerUsers = $admins->merge($managers);
            
            foreach ($adminManagerUsers as $user) {
                $user->notify(new TicketClosedNotification($ticket));
            }
            
            // Envoyer email au client avec le rapport détaillé
            if ($ticket->customer && $ticket->customer->email) {
                Mail::to($ticket->customer->email)->send(new TicketClosedMail($ticket, $request->technical_report, $invoice, $partsData));
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Intervention clôturée avec succès'
        ]);
    }

    // Générer une facture DÉTAILLÉE pour un ticket hors garantie
    private function generateDetailedInvoice($ticket, $request, $partsData)
    {
        // Calcul du total des pièces
        $partsTotal = 0;
        foreach ($partsData as $part) {
            $partsTotal += $part['total'];
        }

        // Tarif horaire main d'œuvre (configurable dans .env)
        $hourlyRate = env('SAV_HOURLY_RATE', 5000); // 5000 FCFA/heure par défaut
        $laborCost = $request->duration_minutes ? ($hourlyRate * $request->duration_minutes / 60) : 0;

        $subtotal = $partsTotal + $laborCost;
        $tax = $subtotal * 0.1925; // TVA 19.25%
        $total = $subtotal + $tax;

        // Création de la facture
        $invoice = Invoice::create([
            'reference' => $this->generateInvoiceReference(),
            'customer_id' => $ticket->customer_id,
            'date' => now()->toDateString(),
            'due_date' => now()->addDays(30)->toDateString(),
            'status' => 'sent',
            'subtotal' => $subtotal,
            'discount' => 0,
            'tax' => $tax,
            'total' => $total,
            'paid_amount' => 0,
            'notes' => 'Facture SAV - Ticket #' . $ticket->ticket_number . "\nAppareil: " . ($ticket->device_model ?? 'Non spécifié'),
            'created_by' => Auth::id(),
        ]);

        // Ligne pour CHAQUE pièce (détail)
        foreach ($partsData as $part) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => null,
                'description' => 'Pièce: ' . $part['name'] . ' (Réf: ' . ($part['reference'] ?? 'N/A') . ')',
                'quantity' => $part['quantity'],
                'unit_price' => $part['unit_price'],
                'total' => $part['total'],
            ]);
        }

        // Ligne pour la main d'œuvre
        if ($laborCost > 0) {
            $minutes = $request->duration_minutes;
            $hours = floor($minutes / 60);
            $remainingMinutes = $minutes % 60;
            $durationText = '';
            if ($hours > 0) $durationText .= $hours . 'h ';
            if ($remainingMinutes > 0) $durationText .= $remainingMinutes . 'min';
            
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => null,
                'description' => 'Main d\'œuvre technique (' . $durationText . ') - ' . number_format($hourlyRate, 0) . ' FCFA/h',
                'quantity' => 1,
                'unit_price' => $laborCost,
                'total' => $laborCost,
            ]);
        }

        return $invoice;
    }

    private function generateInvoiceReference()
    {
        $last = Invoice::orderBy('id', 'desc')->first();
        $number = $last ? intval(substr($last->reference, -5)) + 1 : 1;
        return 'SAV-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    // Liste des pièces détachées
    public function getSpareParts()
    {
        $parts = SparePart::select('id', 'part_number', 'name', 'compatibility', 'selling_price', 'quantity_in_stock', 'min_stock_alert')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'parts' => $parts
        ]);
    }

    // ✅ Nouvelle méthode : Assigner un technicien à un ticket (depuis le web)
    public function assignTicket(Request $request, $id)
    {
        $request->validate([
            'technician_id' => 'required|exists:users,id'
        ]);

        $ticket = SavTicket::findOrFail($id);
        $oldTechnicianId = $ticket->assigned_to;
        
        $ticket->assigned_to = $request->technician_id;
        $ticket->status = 'assigned';
        $ticket->save();

        // ✅ Scénario 1 : Ticket assigné - Déclencher notification
        if ($oldTechnicianId != $request->technician_id) {
            event(new \App\Events\TicketAssigned($ticket, $request->technician_id));
            
            $technician = User::find($request->technician_id);
            if ($technician) {
                $technician->notify(new \App\Notifications\TicketAssignedNotification($ticket));
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Technicien assigné',
            'ticket' => $ticket
        ]);
    }

    // ✅ Nouvelle méthode : Créer un ticket urgent (depuis le web ou mobile)
    public function createUrgentTicket(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'description_failure' => 'required|string',
            'device_model' => 'nullable|string',
            'serial_number' => 'nullable|string',
        ]);

        $ticket = SavTicket::create([
            'ticket_number' => $this->generateTicketNumber(),
            'customer_id' => $request->customer_id,
            'description_failure' => $request->description_failure,
            'device_model' => $request->device_model,
            'serial_number' => $request->serial_number,
            'priority' => 'critical', // ✅ Priorité critique
            'status' => 'pending',
            'is_warranty' => $this->checkWarranty($request->customer_id, $request->device_model),
            'created_by' => Auth::id(),
        ]);

        // ✅ Scénario 5 : Ticket urgent - Déclencher notification broadcast
        event(new TicketUrgent($ticket));
        
        // Notifier tous les techniciens SAV
        $technicians = User::role('technicien_sav')->get();
        foreach ($technicians as $tech) {
            $tech->notify(new \App\Notifications\TicketUrgentNotification($ticket));
        }

        return response()->json([
            'success' => true,
            'message' => 'Ticket urgent créé',
            'ticket' => $ticket
        ]);
    }

    private function generateTicketNumber()
    {
        $last = SavTicket::orderBy('id', 'desc')->first();
        $number = $last ? intval(substr($last->ticket_number, -5)) + 1 : 1;
        return 'SAV-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    private function checkWarranty($customerId, $deviceModel)
    {
        // Vérifier si le produit a été acheté dans les 12 derniers mois
        $warrantyMonths = env('SAV_WARRANTY_MONTHS', 12);
        
        $invoiceItem = InvoiceItem::whereHas('invoice', function ($q) use ($customerId) {
            $q->where('customer_id', $customerId);
        })->whereHas('product', function ($q) use ($deviceModel) {
            $q->where('name', 'like', '%' . $deviceModel . '%');
        })->orderBy('created_at', 'desc')->first();
        
        if ($invoiceItem && $invoiceItem->invoice) {
            $purchaseDate = $invoiceItem->invoice->date;
            if ($purchaseDate && now()->diffInMonths($purchaseDate) <= $warrantyMonths) {
                return true;
            }
        }
        
        return false;
    }
}