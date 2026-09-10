<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Module5\SavTicket;
use App\Models\Module5\SparePart;
use App\Models\Module5\Intervention;
use App\Models\User;
use App\Events\TicketClosed;
use App\Events\CriticalPartAlert;
use App\Notifications\TicketClosedNotification;
use App\Notifications\CriticalPartNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketClosedMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    /**
     * Affiche le tableau de bord du technicien
     */
    public function dashboard()
    {
        $technicianId = Auth::id();
        
        $stats = [
            'pending_count' => SavTicket::where('assigned_to', $technicianId)
                ->whereIn('status', ['pending', 'assigned', 'diagnosing'])
                ->count(),
            'in_progress_count' => SavTicket::where('assigned_to', $technicianId)
                ->where('status', 'repairing')
                ->count(),
            'completed_today_count' => SavTicket::where('assigned_to', $technicianId)
                ->where('status', 'completed')
                ->whereDate('closed_at', today())
                ->count(),
        ];
        
        $recentTickets = SavTicket::where('assigned_to', $technicianId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('technician.dashboard', compact('stats', 'recentTickets'));
    }
    
    /**
     * Liste des tickets assignés au technicien
     */
    public function index()
    {
        $technicianId = Auth::id();
        
        $tickets = SavTicket::where('assigned_to', $technicianId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        $stats = [
            'pending_count' => SavTicket::where('assigned_to', $technicianId)
                ->whereIn('status', ['pending', 'assigned', 'diagnosing'])
                ->count(),
            'in_progress_count' => SavTicket::where('assigned_to', $technicianId)
                ->where('status', 'repairing')
                ->count(),
            'completed_count' => SavTicket::where('assigned_to', $technicianId)
                ->where('status', 'completed')
                ->count(),
        ];
        
        return view('technician.tickets.index', compact('tickets', 'stats'));
    }
    
    /**
     * Affiche les détails d'un ticket
     */
    public function show(int $id)
    {
        $ticket = SavTicket::where('assigned_to', Auth::id())
            ->where('id', $id)
            ->with(['customer', 'product', 'items.sparePart'])
            ->firstOrFail();
        
        $availableParts = SparePart::where('quantity_in_stock', '>', 0)
            ->orderBy('name')
            ->get();
        
        return view('technician.tickets.show', compact('ticket', 'availableParts'));
    }
    
    /**
     * Formulaire de clôture de ticket (atelier)
     */
    public function closeForm(int $id)
    {
        $ticket = SavTicket::where('assigned_to', Auth::id())
            ->where('id', $id)
            ->whereIn('status', ['repairing', 'diagnosing'])
            ->firstOrFail();
        
        $availableParts = SparePart::where('quantity_in_stock', '>', 0)
            ->orderBy('name')
            ->get();
        
        return view('technician.tickets.close', compact('ticket', 'availableParts'));
    }
    
    /**
     * Clôture un ticket (atelier) - AVEC VALIDATION STOCK
     * La facture sera générée par l'Admin via le formulaire de modification
     */
    public function close(Request $request, int $id)
    {
        $ticket = SavTicket::where('assigned_to', Auth::id())
            ->where('id', $id)
            ->whereIn('status', ['repairing', 'diagnosing'])
            ->firstOrFail();

        $request->validate([
            'technical_report' => 'required|string|min:10',
            'duration_minutes' => 'nullable|integer|min:1',
            'parts' => 'nullable|array',
            'parts.*.spare_part_id' => 'required|exists:spare_parts,id',
            'parts.*.quantity' => 'required|integer|min:1',
        ]);

        // ✅ VÉRIFICATION DU STOCK AVANT DE COMMENCER
        $stockErrors = [];
        if ($request->has('parts')) {
            foreach ($request->parts as $index => $partData) {
                $sparePart = SparePart::find($partData['spare_part_id']);
                if ($sparePart && $sparePart->quantity_in_stock < $partData['quantity']) {
                    $stockErrors[] = "Stock insuffisant pour '{$sparePart->name}'. Disponible: {$sparePart->quantity_in_stock}, Demandé: {$partData['quantity']}";
                }
            }
        }

        if (!empty($stockErrors)) {
            return back()->with('error', '❌ Erreur de stock :<br>' . implode('<br>', $stockErrors));
        }

        DB::beginTransaction();

        try {
            // 1. Mettre à jour le ticket
            $ticket->status = 'completed';
            $ticket->technical_report = $request->technical_report;
            $ticket->duration_minutes = $request->duration_minutes;
            $ticket->closed_at = now();
            $ticket->save();

            // 2. Enregistrer les pièces utilisées ET DÉDUIRE LE STOCK
            if ($request->has('parts')) {
                foreach ($request->parts as $partData) {
                    $sparePart = SparePart::find($partData['spare_part_id']);

                    if ($sparePart) {
                        // Enregistrer dans ticket_items
                        $ticket->items()->create([
                            'spare_part_id' => $partData['spare_part_id'],
                            'quantity' => $partData['quantity'],
                            'unit_price' => $sparePart->selling_price,
                        ]);

                        // ✅ DÉDUIRE LE STOCK
                        $sparePart->quantity_in_stock -= $partData['quantity'];
                        $sparePart->save();

                        // ✅ VÉRIFIER SI STOCK CRITIQUE
                        if ($sparePart->quantity_in_stock <= $sparePart->min_stock_alert) {
                            try {
                                event(new CriticalPartAlert($sparePart));
                                $technicians = User::role('technicien_sav')->get();
                                foreach ($technicians as $tech) {
                                    $tech->notify(new CriticalPartNotification($sparePart));
                                }
                            } catch (\Exception $e) {
                                Log::warning('Erreur alerte stock critique: ' . $e->getMessage());
                            }
                        }
                    }
                }
            }

            // 3. Enregistrer l'intervention
            Intervention::create([
                'ticket_id' => $ticket->id,
                'technician_id' => Auth::id(),
                'description' => 'Intervention réalisée en atelier',
                'technical_report' => $request->technical_report,
                'duration_minutes' => $request->duration_minutes,
                'synced' => true,
            ]);

            DB::commit();

            // 4. Déclencher les notifications
            try {
                event(new TicketClosed($ticket, $request->technical_report));
            } catch (\Exception $e) {
                Log::warning('Erreur événement TicketClosed: ' . $e->getMessage());
            }

            // 5. Notifier les admins et managers
            try {
                $admins = User::role('admin')->get();
                $managers = User::role('manager')->get();
                $users = $admins->merge($managers);

                foreach ($users as $user) {
                    try {
                        $user->notify(new TicketClosedNotification($ticket));
                    } catch (\Exception $e) {
                        Log::warning('Erreur notification pour ' . $user->email . ': ' . $e->getMessage());
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Erreur envoi notifications: ' . $e->getMessage());
            }

            // 6. Envoyer email au client (sans facture)
            if ($ticket->customer && $ticket->customer->email) {
                try {
                    Mail::to($ticket->customer->email)
                        ->send(new TicketClosedMail($ticket, $request->technical_report, null, []));
                } catch (\Exception $e) {
                    Log::warning('Erreur envoi email client: ' . $e->getMessage());
                }
            }

            // 7. Message de succès
            return redirect()->route('technician.tickets.index')
                ->with('success', ' Ticket clôturé avec succès. La facture sera générée par l\'administrateur.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur clôture ticket: ' . $e->getMessage());
            return back()->with('error', ' Erreur lors de la clôture : ' . $e->getMessage());
        }
    }
    
    /**
     * Met à jour le statut d'un ticket (sauf vers "completed")
     */
    public function updateStatus(Request $request, int $id)
    {
        $ticket = SavTicket::where('assigned_to', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'status' => 'required|in:pending,assigned,diagnosing,repairing',
        ]);

        $oldStatus = $ticket->status;
        $ticket->status = $request->status;
        $ticket->save();

        return redirect()->route('technician.tickets.show', $ticket->id)
            ->with('success', 'Statut mis à jour : ' . $oldStatus . ' → ' . $request->status);
    }
}