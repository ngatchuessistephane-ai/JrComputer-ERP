<?php

namespace App\Http\Livewire\Module3;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Module1\Product;
use App\Models\Module3\Customer;
use App\Models\Module3\Invoice;
use App\Models\Module3\InvoiceItem;
use App\Models\Module3\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.appProd')]
class PosIndex extends Component
{
    public $cart = [];
    public $customer_id = null;
    public $search = '';
    public $payment_method = 'cash';

    protected $rules = [
        'cart' => 'required|array|min:1',
        'payment_method' => 'required|in:cash,mtn_momo,orange_money,bank_transfer',
    ];

    // Ajouter un produit au panier
    public function addToCart($productId)
    {
        $product = Product::find($productId);
        if (!$product) {
            session()->flash('error', 'Produit introuvable.');
            $this->dispatch('scroll-to-top');
            return;
        }
        if ($product->quantity <= 0) {
            session()->flash('error', "Le produit {$product->name} n'est plus en stock.");
            $this->dispatch('scroll-to-top');
            return;
        }

        $index = collect($this->cart)->search(fn($item) => $item['product_id'] == $productId);
        if ($index !== false) {
            $this->cart[$index]['quantity']++;
            $this->cart[$index]['total'] = $this->cart[$index]['quantity'] * $this->cart[$index]['unit_price'];
        } else {
            $this->cart[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => 1,
                'unit_price' => $product->selling_price,
                'total' => $product->selling_price,
            ];
        }
        $this->dispatch('cartUpdated');
    }

    // Modifier la quantité d'un article
    public function updateQuantity($index, $newQty)
    {
        if ($newQty <= 0) {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart);
        } else {
            $this->cart[$index]['quantity'] = $newQty;
            $this->cart[$index]['total'] = $newQty * $this->cart[$index]['unit_price'];
        }
        $this->dispatch('cartUpdated');
    }

    // Supprimer un article
    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
        $this->dispatch('cartUpdated');
    }

    // Vider le panier
    public function clearCart()
    {
        $this->cart = [];
        session()->flash('message', 'Panier vidé.');
        $this->dispatch('scroll-to-top');
    }

    // Accesseurs pour les totaux
    public function getTotalHTProperty()
    {
        return array_sum(array_column($this->cart, 'total'));
    }

    public function getTaxProperty()
    {
        return $this->totalHT * 0.1925;
    }

    public function getTotalTTCProperty()
    {
        return $this->totalHT + $this->tax;
    }

    // Validation de la vente
    public function checkout()
    {
        $this->validate();

        if (empty($this->cart)) {
            session()->flash('error', 'Le panier est vide.');
            $this->dispatch('scroll-to-top');
            return;
        }

        // Vérification des stocks
        foreach ($this->cart as $item) {
            $product = Product::find($item['product_id']);
            if (!$product || $product->quantity < $item['quantity']) {
                $available = $product ? $product->quantity : 0;
                session()->flash('error', "Stock insuffisant pour {$item['name']} (demandé: {$item['quantity']}, disponible: {$available})");
                $this->dispatch('scroll-to-top');
                return;
            }
        }

        DB::transaction(function () {
            // Création de la facture
            $invoice = Invoice::create([
                'reference' => $this->generateInvoiceReference(),
                'customer_id' => $this->customer_id,
                'date' => now()->toDateString(),
                'due_date' => now()->toDateString(),
                'status' => 'paid',
                'subtotal' => $this->totalHT,
                'discount' => 0,
                'tax' => $this->tax,
                'total' => $this->totalTTC,
                'paid_amount' => $this->totalTTC,
                'notes' => 'Vente POS',
                'created_by' => Auth::id(),
            ]);

            // Lignes de facture et déstockage
            foreach ($this->cart as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['total'],
                ]);

                $product = Product::find($item['product_id']);
                $product->quantity -= $item['quantity'];
                $product->save();

                \App\Models\Module1\StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'reason' => 'Vente POS Facture #'.$invoice->reference,
                    'user_id' => Auth::id(),
                ]);
            }

            // Enregistrement du paiement (sans received_by)
            Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $this->totalTTC,
                'method' => $this->payment_method,
                'payment_date' => now()->toDateString(),
                'reference' => 'POS-'.now()->format('YmdHis'),
                'received_by' => Auth::id(), 
            ]);
        });

        session()->flash('message', 'Vente enregistrée avec succès.');
        $this->dispatch('scroll-to-top');
        $this->reset(['cart', 'customer_id']);
        $this->payment_method = 'cash';
        $this->dispatch('saleCompleted');
    }

    // Génération de la référence de facture
    private function generateInvoiceReference()
    {
        $last = Invoice::orderBy('id', 'desc')->first();
        $number = $last ? intval(substr($last->reference, -5)) + 1 : 1;
        return 'FA-'.str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        $products = Product::where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('reference', 'like', '%'.$this->search.'%')
                    ->orderBy('name')
                    ->limit(30)
                    ->get();
        $customers = Customer::orderBy('name')->get();
        return view('livewire.module3.pos-index', [
            'products' => $products,
            'customers' => $customers,
        ]);
    }
}