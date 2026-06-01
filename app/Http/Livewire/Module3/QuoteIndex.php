<?php

namespace App\Http\Livewire\Module3;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Module3\Customer;
use App\Models\Module3\Quote;
use App\Models\Module3\QuoteItem;
use App\Models\Module3\Invoice;
use App\Models\Module3\InvoiceItem;
use Illuminate\Support\Facades\Auth;
use App\Exports\QuotesPdfExport;

#[Layout('layouts.appProd')]
class QuoteIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $sortBy = 'created_at';

    // Filtres pour export PDF
    public $filter_date_from, $filter_date_to, $filter_status, $filter_customer_id;

    protected $queryString = ['search', 'statusFilter', 'sortBy'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $quotes = Quote::with('customer')
            ->when($this->search, function ($query) {
                $query->where('reference', 'like', '%'.$this->search.'%')
                      ->orWhereHas('customer', fn($q) => $q->where('name', 'like', '%'.$this->search.'%'));
            })
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->orderBy($this->sortBy, 'desc')
            ->paginate(10);

        return view('livewire.module3.quote-index', ['quotes' => $quotes]);
    }

    public function openFiltersModal()
    {
        $this->dispatch('openFiltersModal');
    }

    public function exportPdf()
    {
        $query = http_build_query([
            'date_from' => $this->filter_date_from,
            'date_to'   => $this->filter_date_to,
            'status'    => $this->filter_status,
            'customer_id' => $this->filter_customer_id,
        ]);
        return redirect()->route('export.quotes.pdf', '?' . $query);
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
        $quote = Quote::find($id);
        if ($quote) {
            $quote->delete();
            session()->flash('message', 'Devis supprimé.');
        }
    }

    public function convertToInvoice($id)
    {
        $quote = Quote::with('items.product')->findOrFail($id);
        if ($quote->status === 'converted') {
            session()->flash('error', 'Ce devis a déjà été converti.');
            return;
        }

        // Créer la facture (par défaut en brouillon)
        $invoiceData = [
            'reference' => $this->generateInvoiceReference(),
            'customer_id' => $quote->customer_id,
            'quote_id' => $quote->id,
            'date' => now()->toDateString(),
            'due_date' => now()->addDays(30)->toDateString(),
            'status' => 'draft',
            'subtotal' => $quote->subtotal,
            'discount' => $quote->discount,
            'tax' => $quote->tax,
            'total' => $quote->total,
            'paid_amount' => 0,
            'notes' => $quote->notes,
            'created_by' => Auth::id(),
        ];
        $invoice = Invoice::create($invoiceData);

        // Copier les lignes
        foreach ($quote->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total' => $item->total,
            ]);
        }

        $quote->status = 'converted';
        $quote->save();

        session()->flash('message', 'Devis converti en facture #' . $invoice->reference);
        return redirect()->route('module3.invoices.index');
    }

    private function generateInvoiceReference()
    {
        $last = Invoice::orderBy('id', 'desc')->first();
        $number = $last ? intval(substr($last->reference, -5)) + 1 : 1;
        return 'FAC-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}