<?php

namespace App\Http\Livewire\Module3;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Module3\Customer;
use App\Models\Module3\Quote;
use App\Models\Module3\QuoteItem;
use App\Models\Module1\Product;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.appProd')]
class QuoteForm extends Component
{
    public $quoteId;
    public $customer_id, $date, $valid_until, $status = 'draft', $notes;
    public $items = [];
    public $selectedProduct, $quantityOrdered, $unitPrice;

    protected $rules = [
        'customer_id' => 'required|exists:customers,id',
        'date' => 'required|date',
        'valid_until' => 'nullable|date|after_or_equal:date',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.unit_price' => 'required|numeric|min:0',
        'status' => 'required|in:draft,sent,approved,rejected,converted',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $quote = Quote::with('items')->findOrFail($id);
            $this->quoteId = $quote->id;
            $this->customer_id = $quote->customer_id;
            $this->date = $quote->date->toDateString();
            $this->valid_until = $quote->valid_until?->toDateString();
            $this->status = $quote->status;
            $this->notes = $quote->notes;
            $this->items = $quote->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total' => $item->total,
                ];
            })->toArray();
        } else {
            $this->date = now()->toDateString();
        }
    }

    public function render()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('livewire.module3.quote-form', [
            'customers' => $customers,
            'products' => $products,
        ]);
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
            'quantity' => $this->quantityOrdered,
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
            'customer_id' => $this->customer_id,
            'date' => $this->date,
            'valid_until' => $this->valid_until,
            'status' => $this->status,
            'subtotal' => $subtotal,
            'discount' => 0,
            'tax' => $tax,
            'total' => $total,
            'notes' => $this->notes,
            'created_by' => Auth::id(),
        ];

        if ($this->quoteId) {
            $quote = Quote::find($this->quoteId);
            $quote->update($data);
            $quote->items()->delete();
        } else {
            $quote = Quote::create(array_merge($data, ['reference' => $this->generateReference()]));
        }

        foreach ($this->items as $item) {
            QuoteItem::create([
                'quote_id' => $quote->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['total'],
            ]);
        }

        session()->flash('message', 'Proforma sauvegardé.');
        $this->dispatch('scroll-to-top');
        return redirect()->route('module3.quotes.index');
    }

    private function generateReference()
    {
        $last = Quote::orderBy('id', 'desc')->first();
        $number = $last ? intval(substr($last->reference, -5)) + 1 : 1;
        return 'DEV-'.str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    /**
 *  Auto-remplissage du prix unitaire lors de la sélection d'un produit
 */
public function updatedSelectedProduct($value)
{
    if ($value) {
        $product = \App\Models\Module1\Product::find($value);
        if ($product) {
            $this->unitPrice = $product->selling_price;
        }
    } else {
        $this->unitPrice = null;
    }
}
}