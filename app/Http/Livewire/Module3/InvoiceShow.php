<?php

namespace App\Http\Livewire\Module3;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Module3\Invoice;
use App\Models\Module3\Payment;

#[Layout('layouts.appProd')]
class InvoiceShow extends Component
{
    public $invoice;

    public function mount($id)
    {
        $this->invoice = Invoice::with('customer', 'items.product', 'payments.receiver')->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.module3.invoice-show', [
            'invoice' => $this->invoice,
        ]);
    }

    public function deletePayment($paymentId)
{
    $payment = Payment::find($paymentId);
    if ($payment) {
        $invoice = $payment->invoice;
        $payment->delete();
        
        // Recalculer le montant payé de la facture
        $paidAmount = $invoice->payments()->sum('amount');
        $invoice->paid_amount = $paidAmount;
        
        // Mettre à jour le statut si nécessaire
        if ($paidAmount == 0) {
            $invoice->status = 'sent';
        } elseif ($paidAmount < $invoice->total) {
            $invoice->status = 'partial';
        } else {
            $invoice->status = 'paid';
        }
        $invoice->save();
        
        session()->flash('message', 'Paiement supprimé.');
        $this->dispatch('scroll-to-top');
    }
}

}