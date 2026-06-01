<?php

namespace App\Http\Livewire\Module5;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Module5\SavTicket;
use App\Models\Module3\Invoice;

#[Layout('layouts.appProd')]
class TicketShow extends Component
{
    public $ticket;

    public function mount($id)
    {
        $this->ticket = SavTicket::with('customer', 'product', 'technician', 'items.sparePart', 'intervention')->findOrFail($id);
    }

    public function generateInvoice()
    {
        if (!$this->ticket->is_warranty && $this->ticket->status === 'completed') {
            // Logique de création facture (sera implémentée dans l'API)
            session()->flash('info', 'Fonctionnalité en cours de développement.');
        } else {
            session()->flash('error', 'Impossible de générer une facture pour un ticket sous garantie.');
        }
    }

    public function render()
    {
        return view('livewire.module5.ticket-show', ['ticket' => $this->ticket]);
    }
}