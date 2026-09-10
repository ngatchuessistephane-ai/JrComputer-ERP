<?php

namespace App\Events;

use App\Models\Module5\SavTicket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketAssigned implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public SavTicket $ticket;
    public int $technicianId;

    public function __construct(SavTicket $ticket, int $technicianId)
    {
        $this->ticket = $ticket;
        $this->technicianId = $technicianId;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('private-user.' . $this->technicianId),
            new Channel('private-admin'),
            new Channel('private-manager'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ticket.assigned';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'customer_name' => $this->ticket->customer?->name ?? 'Client',
            'device_model' => $this->ticket->device_model,
            'priority' => $this->ticket->priority,
            'message' => "📋 Nouveau ticket {$this->ticket->ticket_number}",
            'action_url' => route('module5.tickets.show', $this->ticket->id),
            'created_at' => $this->ticket->created_at->toIso8601String(),
        ];
    }
}