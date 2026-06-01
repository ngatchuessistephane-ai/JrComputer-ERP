<?php

namespace App\Http\Livewire\Module3;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Module3\Customer;
use App\Models\Module3\Invoice;
use App\Models\Module3\InvoiceItem;
use App\Models\Module3\Payment;
use App\Models\Module1\Product;
use App\Models\Module1\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.appProd')]
class InvoiceForm extends Component
{
    public $invoiceId;
    public $customer_id, $date, $due_date, $status = 'draft', $notes;
    public $items = [];
    public $selectedProduct, $quantity, $unitPrice;

    protected $rules = [
        'customer_id' => 'required|exists:customers,id',
        'date' => 'required|date',
        'due_date' => 'required|date|after_or_equal:date',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.unit_price' => 'required|numeric|min:0',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $invoice = Invoice::with('items')->findOrFail($id);
            $this->invoiceId = $invoice->id;
            $this->customer_id = $invoice->customer_id;
            $this->date = $invoice->date->toDateString();
            $this->due_date = $invoice->due_date->toDateString();
            $this->status = $invoice->status;
            $this->notes = $invoice->notes;
            $this->items = $invoice->items->map(function ($item) {
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
            $this->due_date = now()->addDays(30)->toDateString();
        }
    }

    public function render()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('livewire.module3.invoice-form', [
            'customers' => $customers,
            'products' => $products,
        ]);
    }

    public function addItem()
    {
        $this->validate([
            'selectedProduct' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unitPrice' => 'required|numeric|min:0',
        ]);

        $product = Product::find($this->selectedProduct);
        $total = $this->quantity * $this->unitPrice;

        $this->items[] = [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => $this->quantity,
            'unit_price' => $this->unitPrice,
            'total' => $total,
        ];

        $this->reset(['selectedProduct', 'quantity', 'unitPrice']);
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save()
    {
        $this->validate();

        // Calcul des totaux
        $subtotal = collect($this->items)->sum('total');
        $tax = $subtotal * 0.1925; // TVA 19.25%
        $total = $subtotal + $tax;

        $data = [
            'customer_id' => $this->customer_id,
            'date' => $this->date,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'subtotal' => $subtotal,
            'discount' => 0,
            'tax' => $tax,
            'total' => $total,
            'notes' => $this->notes,
            'created_by' => Auth::id(),
        ];

        DB::transaction(function () use ($data) {
            if ($this->invoiceId) {
                // Modification d'une facture existante
                $invoice = Invoice::find($this->invoiceId);
                $oldStatus = $invoice->status;
                $invoice->update($data);

                // Si le nouveau statut est 'paid', mettre à jour paid_amount
                if ($invoice->status === 'paid' && $invoice->paid_amount < $invoice->total) {
                    $invoice->paid_amount = $invoice->total;
                    $invoice->save();
                }

                // Supprimer les anciens articles
                $invoice->items()->delete();

                // Si la facture passe de "draft" à "sent" ou "paid", on déduit le stock des NOUVEAUX articles
                if ($oldStatus === 'draft' && in_array($invoice->status, ['sent', 'paid'])) {
                    $this->updateStock($this->items);
                }
            } else {
                // Création d'une nouvelle facture
                $invoice = Invoice::create(array_merge($data, ['reference' => $this->generateReference()]));

                // Si la facture est directement en statut "sent" ou "paid", on déduit le stock
                if (in_array($invoice->status, ['sent', 'paid'])) {
                    $this->updateStock($this->items);
                }
                
                // Si le statut est 'paid', mettre à jour paid_amount
                if ($invoice->status === 'paid') {
                    $invoice->paid_amount = $invoice->total;
                    $invoice->save();
                }
            }

            // Création des nouveaux articles (toujours)
            foreach ($this->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['total'],
                ]);
            }
        });

        session()->flash('message', 'Facture sauvegardée.');
        return redirect()->route('module3.invoices.index');
    }

    private function updateStock($items)
    {
        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            if ($product->quantity < $item['quantity']) {
                throw new \Exception('Stock insuffisant pour le produit ' . $product->name);
            }
            $product->quantity -= $item['quantity'];
            $product->save();

            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'out',
                'quantity' => $item['quantity'],
                'reason' => 'Facture #' . ($this->invoiceId ?? 'nouvelle'),
                'user_id' => Auth::id(),
            ]);
        }
    }

    private function generateReference()
    {
        $last = Invoice::orderBy('id', 'desc')->first();
        $number = $last ? intval(substr($last->reference, -5)) + 1 : 1;
        return 'FAC-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}