<?php

namespace App\Http\Livewire\Module2;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Module2\Supplier;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SuppliersImport;
use App\Exports\SuppliersPdfExport;

#[Layout('layouts.appProd')]
class SupplierIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $showForm = false;
    public $supplierId, $name, $code, $contact_person, $email, $phone,
           $address, $tax_number, $payment_terms;
    public $importFile;

    // Filtres PDF
    public $showFilters = false;
    public $filter_date_from, $filter_date_to, $filter_name, $filter_payment_terms_min, $filter_payment_terms_max;

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|unique:suppliers,code',
        'email' => 'nullable|email',
        'payment_terms' => 'integer|min:0',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $suppliers = Supplier::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('code', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('livewire.module2.supplier-index', ['suppliers' => $suppliers]);
    }

    public function create()
    {
        $this->resetInput();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        $this->supplierId = $supplier->id;
        $this->name = $supplier->name;
        $this->code = $supplier->code;
        $this->contact_person = $supplier->contact_person;
        $this->email = $supplier->email;
        $this->phone = $supplier->phone;
        $this->address = $supplier->address;
        $this->tax_number = $supplier->tax_number;
        $this->payment_terms = $supplier->payment_terms;
        $this->showForm = true;
    }

    public function save()
    {
        if ($this->supplierId) {
            $this->rules['code'] = 'required|string|unique:suppliers,code,'.$this->supplierId;
            $this->validate();
            Supplier::find($this->supplierId)->update($this->except(['supplierId', 'showForm', 'importFile']));
        } else {
            $this->validate();
            Supplier::create($this->except(['supplierId', 'showForm', 'importFile']));
        }
        $this->resetInput();
        $this->showForm = false;
        session()->flash('message', 'Fournisseur sauvegardé.');
        $this->dispatch('scroll-to-top');
    }

    public function delete($id)
    {
        Supplier::find($id)?->delete();
        session()->flash('message', 'Fournisseur supprimé.');
        $this->dispatch('scroll-to-top');
    }

    public function import()
    {
        $this->validate(['importFile' => 'required|file|mimes:xlsx,csv']);
        Excel::import(new SuppliersImport, $this->importFile);
        session()->flash('message', 'Import fournisseurs terminé.');
        $this->dispatch('scroll-to-top');
        $this->importFile = null;
    }

    // Ouvre la modal de filtres PDF
    public function openFiltersModal()
    {
        $this->dispatch('openFiltersModal');
    }

    // Export PDF avec redirection vers route GET
    public function exportPdf()
    {
        $query = http_build_query([
            'date_from' => $this->filter_date_from,
            'date_to' => $this->filter_date_to,
            'name' => $this->filter_name,
            'payment_terms_min' => $this->filter_payment_terms_min,
            'payment_terms_max' => $this->filter_payment_terms_max,
        ]);
        return redirect()->route('export.suppliers.pdf', '?' . $query);
    }

    public function resetFilters()
    {
        $this->filter_date_from = null;
        $this->filter_date_to = null;
        $this->filter_name = null;
        $this->filter_payment_terms_min = null;
        $this->filter_payment_terms_max = null;
    }

    private function resetInput()
    {
        $this->supplierId = null;
        $this->name = '';
        $this->code = '';
        $this->contact_person = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->tax_number = '';
        $this->payment_terms = 30;
    }
}