<?php

namespace App\Events;

use App\Models\Module5\SavTicket;
use App\Services\ActivityLoggerService;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TicketClosed implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public SavTicket $ticket;
    public ?string $report;

    public function __construct(SavTicket $ticket, ?string $report = null)
    {
        $this->ticket = $ticket;
        $this->report = $report;
        $this->logActivity();
    }

    protected function logActivity(): void
    {
        try {
            $activityLogger = app(ActivityLoggerService::class);
            
            $customerName = $this->ticket->customer ? $this->ticket->customer->name : 'Client inconnu';
            $customerId = $this->ticket->customer_id;
            
            $activityLogger->log(
                type: 'ticket_closed',
                action: 'close',
                entityType: 'ticket',
                entityId: $this->ticket->getAttribute('id'),
                data: [
                    'ticket_number' => $this->ticket->ticket_number,
                    'customer_name' => $customerName,
                    'customer_id' => $customerId,
                    'device_model' => $this->ticket->device_model,
                    'serial_number' => $this->ticket->serial_number,
                    'duration_minutes' => $this->ticket->duration_minutes,
                    'has_report' => !empty($this->report),
                    'report_preview' => $this->report ? substr($this->report, 0, 100) : null,
                    'closed_by' => auth()->id(),
                    'closed_by_name' => auth()->user()?->name,
                ],
                priority: 'normal',
                actionLinks: [
                    ['label' => 'Voir le ticket', 'url' => route('module5.tickets.show', $this->ticket->getAttribute('id'))],
                    ['label' => 'Voir le client', 'url' => $customerId ? route('module3.customers.show', $customerId) : '#'],
                    ['label' => 'Rapport PDF', 'url' => route('module5.tickets.pdf', $this->ticket->getAttribute('id'))],
                ]
            );
        } catch (\Exception $e) {
            Log::error('Erreur lors du logging activité: ' . $e->getMessage());
        }
    }

    public function broadcastOn(): array
    {
        // ✅ Choix 1 : Canaux publics (si vous voulez éviter l'authentification)
        return [
            new Channel('admin'),
            new Channel('manager'),
        ];
        
        // ✅ Choix 2 : Canaux privés (plus sécurisé)
        // return [
        //     new PrivateChannel('admin'),
        //     new PrivateChannel('manager'),
        // ];
    }

    public function broadcastAs(): string
    {
        return 'ticket.closed';
    }

    public function broadcastWith(): array
    {
        $ticketId = $this->ticket->getAttribute('id');
        $customerId = $this->ticket->customer_id;
        $customerName = $this->ticket->customer ? $this->ticket->customer->name : 'Client';
        
        return [
            'id' => $ticketId,
            'ticket_number' => $this->ticket->ticket_number,
            'customer_name' => $customerName,
            'customer_id' => $customerId,
            'device_model' => $this->ticket->device_model,
            'status' => 'completed',
            'message' => "Ticket {$this->ticket->ticket_number} clôturé - Prêt à être récupéré",
            'closed_at' => now()->toIso8601String(),
            'action_url' => route('module5.tickets.show', $ticketId),
            'action_links' => [
                ['label' => 'Voir le ticket', 'url' => route('module5.tickets.show', $ticketId)],
                ['label' => 'Voir le client', 'url' => $customerId ? route('module3.customers.show', $customerId) : '#'],
            ]
        ];
    }
}