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

    public $ticket;

    public function __construct(SavTicket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function broadcastOn()
    {
        return new Channel('user.' . $this->ticket->assigned_to);
    }

    public function broadcastAs()
    {
        return 'ticket.assigned';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'customer_name' => $this->ticket->customer->name,
            'device_model' => $this->ticket->device_model ?? $this->ticket->product?->name,
            'priority' => $this->ticket->priority,
            'status' => $this->ticket->status,
            'created_at' => $this->ticket->created_at->toISOString(),
        ];
    }
}
