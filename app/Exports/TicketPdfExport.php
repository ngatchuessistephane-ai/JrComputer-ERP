<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Module5\SavTicket;

class TicketPdfExport
{
    protected $ticket;

    public function __construct(SavTicket $ticket)
    {
        $this->ticket = $ticket->load('customer', 'product', 'items.sparePart', 'intervention');
    }

    public function generate()
    {
        $data = [
            'ticket' => $this->ticket,
            'generated_at' => now()->format('d/m/Y H:i'),
        ];

        $pdf = Pdf::loadView('exports.ticket-pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('ticket_' . $this->ticket->ticket_number . '.pdf');
    }
}