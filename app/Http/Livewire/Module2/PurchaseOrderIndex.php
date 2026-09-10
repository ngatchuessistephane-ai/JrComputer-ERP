<?php

namespace App\Http\Livewire\Module2;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Module2\Supplier;
use App\Models\Module2\PurchaseOrder;
use App\Models\Module2\PurchaseOrderItem;
use App\Models\Module1\Product;
use Illuminate\Support\Facades\Auth;
use App\Exports\PurchaseOrdersPdfExport;

#[Layout('layouts.appProd')]
class PurchaseOrderIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $showForm = false;
    public $purchaseOrderId;
    public $supplier_id, $order_date, $expected_delivery_date, $status = 'draft', $notes;
    public $items = [];
    public $selectedProduct, $quantityOrdered, $unitPrice;

    // Filtres PDF
    public $filter_date_from, $filter_date_to, $filter_status, $filter_supplier_id, $filter_total_min, $filter_total_max;

    protected $rules = [
        'supplier_id' => 'required|exists:suppliers,id',
        'order_date' => 'required|date',
        'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity_ordered' => 'required|integer|min:1',
        'items.*.unit_price' => 'required|numeric|min:0',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $purchaseOrders = PurchaseOrder::with('supplier')
            ->where('reference', 'like', '%'.$this->search.'%')
            ->orWhereHas('supplier', fn($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->orderBy('id', 'desc')
            ->paginate(10);

        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('livewire.module2.purchase-order-index', [
            'purchaseOrders' => $purchaseOrders,
            'suppliers' => $suppliers,
            'products' => $products,
        ]);
    }

    // ✅ NOUVELLE MÉTHODE : Charger le prix d'achat automatiquement quand on sélectionne un produit
    public function updatedSelectedProduct($value)
    {
        if ($value) {
            $product = Product::find($value);
            if ($product) {
                // Pré-remplir le prix unitaire avec le prix d'achat du produit
                $this->unitPrice = $product->purchase_price;
            }
        } else {
            $this->unitPrice = null;
        }
    }

    public function create()
    {
        $this->reset(['purchaseOrderId', 'supplier_id', 'order_date', 'expected_delivery_date', 'notes', 'items']);
        $this->order_date = now()->toDateString();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $po = PurchaseOrder::with('items')->findOrFail($id);
        $this->purchaseOrderId = $po->id;
        $this->supplier_id = $po->supplier_id;
        $this->order_date = $po->order_date->toDateString();
        $this->expected_delivery_date = $po->expected_delivery_date?->toDateString();
        $this->status = $po->status;
        $this->notes = $po->notes;
        $this->items = $po->items->map(fn($item) => [
            'product_id' => $item->product_id,
            'product_name' => $item->product->name,
            'quantity_ordered' => $item->quantity_ordered,
            'unit_price' => $item->unit_price,
            'total' => $item->total,
        ])->toArray();
        $this->showForm = true;
    }

    public function addItem()
    {
        $this->validate([
            'selectedProduct' => 'required|exists:products,id',
            'quantityOrdered' => 'required|integer|min:1',
            'unitPrice' => 'required|numeric|min:0',
        ]);

        $product = Product::find($this->selectedProduct);
        $total = $this->quantityOrdered * $this->unitPrice;

        $this->items[] = [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity_ordered' => $this->quantityOrdered,
            'unit_price' => $this->unitPrice,
            'total' => $total,
        ];

        $this->reset(['selectedProduct', 'quantityOrdered', 'unitPrice']);
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save()
    {
        $this->validate();

        $subtotal = collect($this->items)->sum('total');
        $tax = $subtotal * 0.1925;
        $total = $subtotal + $tax;

        $data = [
            'reference' => $this->purchaseOrderId ? PurchaseOrder::find($this->purchaseOrderId)->reference : $this->generateReference(),
            'supplier_id' => $this->supplier_id,
            'order_date' => $this->order_date,
            'expected_delivery_date' => $this->expected_delivery_date,
            'status' => $this->status,
            'subtotal' => $subtotal,
            'discount' => 0,
            'tax' => $tax,
            'total' => $total,
            'notes' => $this->notes,
            'created_by' => Auth::id(),
        ];

        if ($this->purchaseOrderId) {
            $po = PurchaseOrder::find($this->purchaseOrderId);
            $po->update($data);
            $po->items()->delete();
        } else {
            $po = PurchaseOrder::create($data);
        }

        foreach ($this->items as $item) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $po->id,
                'product_id' => $item['product_id'],
                'quantity_ordered' => $item['quantity_ordered'],
                'quantity_received' => 0,
                'unit_price' => $item['unit_price'],
                'total' => $item['total'],
            ]);
        }

        $this->resetInputForm();
        session()->flash('message', 'Bon de commande sauvegardé.');
        $this->dispatch('scroll-to-top');
    }

    private function generateReference()
    {
        $last = PurchaseOrder::orderBy('id', 'desc')->first();
        $number = $last ? intval(substr($last->reference, -5)) + 1 : 1;
        return 'PO-'.str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    public function receiveOrder($id)
    {
        $po = PurchaseOrder::with('items')->findOrFail($id);
        if ($po->status === 'received') {
            session()->flash('error', 'Déjà réceptionnée.');
            $this->dispatch('scroll-to-top');
            return;
        }

        foreach ($po->items as $item) {
            $product = $item->product;
            $product->quantity += $item->quantity_ordered;
            $product->save();
            $item->quantity_received = $item->quantity_ordered;
            $item->save();
        }

        $po->status = 'received';
        $po->save();
        session()->flash('message', 'Commande réceptionnée et stock mis à jour.');
        $this->dispatch('scroll-to-top');
    }

    public function cancelOrder($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        if ($po->status === 'received') {
            session()->flash('error', 'Impossible d’annuler une commande déjà reçue.');
            $this->dispatch('scroll-to-top');
            return;
        }
        $po->status = 'cancelled';
        $po->save();
        session()->flash('message', 'Commande annulée.');
        $this->dispatch('scroll-to-top');
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
            'status' => $this->filter_status,
            'supplier_id' => $this->filter_supplier_id,
            'total_min' => $this->filter_total_min,
            'total_max' => $this->filter_total_max,
        ]);
        return redirect()->route('export.purchase-orders.pdf', '?' . $query);
    }

    public function resetFilters()
    {
        $this->filter_date_from = null;
        $this->filter_date_to = null;
        $this->filter_status = null;
        $this->filter_supplier_id = null;
        $this->filter_total_min = null;
        $this->filter_total_max = null;
    }

    private function resetInputForm()
    {
        $this->purchaseOrderId = null;
        $this->supplier_id = null;
        $this->order_date = now()->toDateString();
        $this->expected_delivery_date = null;
        $this->status = 'draft';
        $this->notes = '';
        $this->items = [];
        $this->showForm = false;
    }
}