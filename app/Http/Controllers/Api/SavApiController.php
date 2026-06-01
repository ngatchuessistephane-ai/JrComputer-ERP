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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SavApiController extends Controller
{
    // Récupérer les tickets assignés au technicien connecté
    public function getMyTickets()
    {
        $tickets = SavTicket::with(['customer', 'product', 'items.sparePart'])
            ->where('assigned_to', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $tickets->each(function ($ticket) {
            $ticket->items->each(function ($item) {
                $item->spare_part_name = $item->sparePart->name ?? null;
            });
        });

        return response()->json([
            'success' => true,
            'tickets' => $tickets
        ]);
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

        $ticket->status = $request->status;
        $ticket->save();

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
                    'synced' => true
                ]
            );

            // 3. Traitement des pièces utilisées et déstockage
            $partsData = [];
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
                    $sparePart->quantity_in_stock -= $part['quantity'];
                    $sparePart->save();

                    // Mouvement de stock
                    StockMovement::create([
                        'spare_part_id' => $sparePart->id,
                        'type' => 'out',
                        'quantity' => $part['quantity'],
                        'reason' => 'SAV ticket #' . $ticket->ticket_number,
                        'user_id' => Auth::id(),
                    ]);

                    // Stockage pour la facture
                    $partsData[] = [
                        'name' => $sparePart->name,
                        'quantity' => $part['quantity'],
                        'unit_price' => $part['unit_price'],
                        'total' => $part['quantity'] * $part['unit_price']
                    ];
                }
            }

            // 4. Si hors garantie, générer une facture DÉTAILLÉE
            if (!$ticket->is_warranty && $ticket->status !== 'completed') {
                $this->generateDetailedInvoice($ticket, $request, $partsData);
            }

            // 5. Mettre à jour le statut du ticket
            $ticket->status = 'completed';
            $ticket->save();
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

        // Tarif horaire main d'œuvre
        $hourlyRate = 5000; // 5000 FCFA/heure
        $laborCost = $request->duration_minutes ? ($hourlyRate * $request->duration_minutes / 60) : 0;

        $subtotal = $partsTotal + $laborCost;
        $tax = $subtotal * 0.1925;
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
            'notes' => 'Facture SAV - Ticket #' . $ticket->ticket_number,
            'created_by' => Auth::id(),
        ]);

        // Ligne pour CHAQUE pièce (détail)
        foreach ($partsData as $part) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => null,
                'description' => 'Pièce: ' . $part['name'],
                'quantity' => $part['quantity'],
                'unit_price' => $part['unit_price'],
                'total' => $part['total'],
            ]);
        }

        // Ligne pour la main d'œuvre
        if ($laborCost > 0) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => null,
                'description' => 'Main d\'œuvre (' . $request->duration_minutes . ' minutes)',
                'quantity' => 1,
                'unit_price' => $laborCost,
                'total' => $laborCost,
            ]);
        }
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
        $parts = SparePart::select('id', 'part_number', 'name', 'compatibility', 'selling_price', 'quantity_in_stock')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'parts' => $parts
        ]);
    }
}