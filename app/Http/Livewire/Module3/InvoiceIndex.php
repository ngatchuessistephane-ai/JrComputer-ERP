<?php

namespace App\Http\Livewire\Module3;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Module3\Invoice;
use App\Models\Module3\Payment;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.appProd')]
class InvoiceIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $sortBy = 'created_at';

    // Filtres pour l'export PDF
    public $filter_date_from, $filter_date_to, $filter_status, $filter_customer_id;

    protected $queryString = ['search', 'statusFilter', 'sortBy'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $invoices = Invoice::with('customer', 'payments.receiver') 
            ->when($this->search, function ($query) {
                $query->where('reference', 'like', '%'.$this->search.'%')
                      ->orWhereHas('customer', fn($q) => $q->where('name', 'like', '%'.$this->search.'%'));
            })
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->orderBy($this->sortBy, 'desc')
            ->paginate(10);

        return view('livewire.module3.invoice-index', ['invoices' => $invoices]);
    }

    // Ouvre la modal de filtres PDF
    public function openFiltersModal()
    {
        $this->dispatch('openFiltersModal');
    }

   /**
 * Exporte les factures en PDF avec les filtres appliqués
 */
public function exportPdf()
{
    // Construire l'URL avec les filtres
    $params = [];
    if ($this->filter_date_from) $params['date_from'] = $this->filter_date_from;
    if ($this->filter_date_to) $params['date_to'] = $this->filter_date_to;
    if ($this->filter_status) $params['status'] = $this->filter_status;
    if ($this->filter_customer_id) $params['customer_id'] = $this->filter_customer_id;
    
    $query = http_build_query($params);
    $url = route('export.invoices.pdf') . ($query ? '?' . $query : '');
    
    // Redirection directe (fonctionne dans Livewire)
    return redirect($url);
}

    public function resetFilters()
    {
        $this->filter_date_from = null;
        $this->filter_date_to = null;
        $this->filter_status = null;
        $this->filter_customer_id = null;
    }

    public function delete($id)
{
    $invoice = Invoice::find($id);
    if ($invoice) {
        // Supprimer d'abord les paiements associés
        $invoice->payments()->delete();
        // Puis supprimer la facture
        $invoice->delete();
        session()->flash('message', 'Facture supprimée.');
    }
}

    // Marquer une facture comme payée
    public function markAsPaid($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        // Ne peut marquer que les factures en statut "sent", "partial" ou "draft" (après confirmation)
        if (!in_array($invoice->status, ['sent', 'partial', 'draft'])) {
            session()->flash('error', 'Cette facture ne peut pas être marquée comme payée.');
            return;
        }
        
        $invoice->status = 'paid';
        $invoice->paid_amount = $invoice->total;
        $invoice->save();
        
        // Enregistrer le paiement dans la table payments
        Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => $invoice->total,
            'method' => 'cash',
            'payment_date' => now()->toDateString(),
            'reference' => 'PAY-' . $invoice->reference . '-' . now()->format('YmdHis'),
            'received_by' => Auth::id(),
        ]);
        
        session()->flash('message', 'Facture marquée comme payée.');
    }
}