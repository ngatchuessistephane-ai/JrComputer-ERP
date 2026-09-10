<?php

namespace App\Notifications;

use App\Models\Module5\SavTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;

    public function __construct(SavTicket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'ticket_assigned',
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'customer_name' => $this->ticket->customer->name ?? 'Client',
            'device_model' => $this->ticket->device_model,
            'priority' => $this->ticket->priority,
            'message' => "Nouveau ticket SAV assigné : {$this->ticket->ticket_number}",
            'action_url' => route('module5.tickets.show', $this->ticket->id),
            'created_at' => now()->toIso8601String(),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'data' => $this->toArray($notifiable),
            'created_at' => now()->toIso8601String(),
        ];
    }
}