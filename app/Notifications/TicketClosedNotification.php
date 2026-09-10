<?php

namespace App\Notifications;

use App\Models\Module5\SavTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketClosedNotification extends Notification
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
            'type' => 'ticket_closed',
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'customer_name' => $this->ticket->customer->name ?? 'Client',
            'message' => "✓ Ticket {$this->ticket->ticket_number} clôturé - Appareil prêt à être récupéré",
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