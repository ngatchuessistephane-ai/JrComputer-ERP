<?php

namespace App\Http\Livewire\Module1;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Module1\Product;
use App\Models\Module1\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Models\Module2\Supplier;
use App\Exports\ProductsPdfExport;
use App\Models\Module3\InvoiceItem;
use App\Models\Module2\PurchaseOrderItem;

#[Layout('layouts.appProd')]
class ProductIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $showForm = false;
    public $productId, $name, $reference, $serial_number, $description,
           $purchase_price, $selling_price, $quantity, $alert_threshold,
           $category, $supplier;
    public $adjustQuantityProductId, $adjustQuantityValue, $adjustReason;
    public $importFile;
    public $adjustType = 'in';

    // Filtres pour l'export PDF
    public $filter_date_from, $filter_date_to, $filter_price_min, $filter_price_max,
           $filter_category, $filter_supplier, $filter_stock_status;

    protected $rules = [
        'name' => 'required|string|max:255',
        'reference' => 'required|string|unique:products,reference',
        'serial_number' => 'nullable|string|unique:products,serial_number',
        'purchase_price' => 'required|numeric|min:0',
        'selling_price' => 'required|numeric|min:0',
        'alert_threshold' => 'integer|min:0',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Récupérer les fournisseurs avec cache
        $suppliersList = Cache::remember('suppliers_list', 3600, function () {
            return Supplier::orderBy('name')->get(['id', 'name', 'code']);
        });

        // Catégories en cache
        $categories = Cache::remember('product_categories', 1800, function () {
            return Product::select('category')
                ->whereNotNull('category')
                ->distinct()
                ->pluck('category');
        });

        // Requête optimisée
        $query = Product::query();
        
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('reference', 'like', $searchTerm)
                  ->orWhere('serial_number', 'like', $searchTerm);
            });
        }
        
        $products = $query->orderBy('id', 'desc')
                      ->select('id', 'name', 'reference', 'serial_number', 
                               'selling_price', 'quantity', 'alert_threshold', 'category', 'supplier')
                      ->paginate(10);

        return view('livewire.module1.product-index', [
            'products' => $products,
            'suppliersList' => $suppliersList,
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $this->resetInput();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->reference = $product->reference;
        $this->serial_number = $product->serial_number;
        $this->description = $product->description;
        $this->purchase_price = $product->purchase_price;
        $this->selling_price = $product->selling_price;
        $this->quantity = $product->quantity;
        $this->alert_threshold = $product->alert_threshold;
        $this->category = $product->category;
        $this->supplier = $product->supplier;
        $this->showForm = true;
    }

    public function save()
    {
        if ($this->productId) {
            $this->rules['reference'] = 'required|string|unique:products,reference,'.$this->productId;
            $this->rules['serial_number'] = 'nullable|string|unique:products,serial_number,'.$this->productId;
            $this->validate();

            $product = Product::find($this->productId);
            $product->update([
                'name' => $this->name,
                'reference' => $this->reference,
                'serial_number' => $this->serial_number,
                'description' => $this->description,
                'purchase_price' => $this->purchase_price,
                'selling_price' => $this->selling_price,
                'quantity' => $this->quantity,
                'alert_threshold' => $this->alert_threshold,
                'category' => $this->category,
                'supplier' => $this->supplier,
            ]);

            session()->flash('message', 'Produit modifié avec succès.');
            $this->dispatch('scroll-to-top');
        } else {
            $this->rules['serial_number'] = 'nullable|string|unique:products,serial_number';
            $this->validate();

            Product::create([
                'name' => $this->name,
                'reference' => $this->reference,
                'serial_number' => $this->serial_number,
                'description' => $this->description,
                'purchase_price' => $this->purchase_price,
                'selling_price' => $this->selling_price,
                'quantity' => $this->quantity,
                'alert_threshold' => $this->alert_threshold,
                'category' => $this->category,
                'supplier' => $this->supplier,
            ]);

            session()->flash('message', 'Produit sauvegardé avec succès.');
            $this->dispatch('scroll-to-top');
        }
        
        $this->resetInput();
        $this->showForm = false;
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if (!$product) {
            session()->flash('error', 'Produit introuvable.');
            $this->dispatch('scroll-to-top');
            return;
        }

        $invoiceItemsCount = InvoiceItem::where('product_id', $id)->count();
        if ($invoiceItemsCount > 0) {
            session()->flash('error', "Impossible de supprimer ce produit car il est utilisé dans {$invoiceItemsCount} facture(s).");
            $this->dispatch('scroll-to-top');
            return;
        }

        $purchaseOrderItemsCount = PurchaseOrderItem::where('product_id', $id)->count();
        if ($purchaseOrderItemsCount > 0) {
            session()->flash('error', "Impossible de supprimer ce produit car il est utilisé dans {$purchaseOrderItemsCount} bon(s) de commande.");
            $this->dispatch('scroll-to-top');
            return;
        }

        $stockMovementsCount = StockMovement::where('product_id', $id)->count();
        if ($stockMovementsCount > 0) {
            session()->flash('error', "Impossible de supprimer ce produit car il a {$stockMovementsCount} mouvement(s) de stock.");
            $this->dispatch('scroll-to-top');
            return;
        }

        $product->delete();
        session()->flash('message', 'Produit supprimé avec succès.');
        $this->dispatch('scroll-to-top');
    }

    public function openAdjustStock($id)
    {
        $this->adjustQuantityProductId = $id;
        $this->adjustQuantityValue = 0;
        $this->adjustReason = '';
        $this->adjustType = 'in';
        $this->dispatch('open-adjust-modal');
    }

    public function adjustStock()
    {
        $this->validate([
            'adjustQuantityValue' => 'required|integer|min:1',
            'adjustReason' => 'required|string|min:3',
        ]);

        $product = Product::find($this->adjustQuantityProductId);
        if (!$product) {
            session()->flash('error', 'Produit introuvable.');
            $this->dispatch('scroll-to-top');
            return;
        }

        $quantity = ($this->adjustType === 'in') ? $this->adjustQuantityValue : -$this->adjustQuantityValue;
        $newQty = $product->quantity + $quantity;

        if ($newQty < 0) {
            session()->flash('error', 'Le stock ne peut pas devenir négatif.');
            $this->dispatch('scroll-to-top');
            return;
        }

        $product->quantity = $newQty;
        $product->save();

        StockMovement::create([
            'product_id' => $product->id,
            'type' => ($quantity > 0) ? 'in' : 'out',
            'quantity' => abs($quantity),
            'reason' => $this->adjustReason,
            'user_id' => Auth::id(),
        ]);

        $this->adjustQuantityProductId = null;
        session()->flash('message', 'Stock ajusté avec succès.');
        $this->dispatch('scroll-to-top');
    }

    public function import()
    {
        if (!$this->importFile) {
            session()->flash('error', 'Aucun fichier sélectionné.');
            $this->dispatch('scroll-to-top');
            return;
        }

        $this->validate([
            'importFile' => 'file|mimes:xlsx,xls,csv|max:40000',
        ]);

        try {
            Excel::import(new ProductsImport, $this->importFile);
            session()->flash('message', 'Import terminé avec succès.');
            $this->dispatch('scroll-to-top');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $errors = [];
            foreach ($e->failures() as $failure) {
                $errors[] = "Ligne {$failure->row()} - {$failure->attribute()} : " . implode(', ', $failure->errors());
            }
            session()->flash('error', 'Erreur de validation dans le fichier : ' . implode('; ', $errors));
            $this->dispatch('scroll-to-top');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur technique : ' . $e->getMessage());
            $this->dispatch('scroll-to-top');
        }

        $this->importFile = null;
    }

    public function openExportModal()
    {
        $this->dispatch('open-export-modal');
    }

    public function exportPdf()
    {
        $params = [];
        if ($this->filter_date_from) $params['date_from'] = $this->filter_date_from;
        if ($this->filter_date_to) $params['date_to'] = $this->filter_date_to;
        if ($this->filter_price_min) $params['price_min'] = $this->filter_price_min;
        if ($this->filter_price_max) $params['price_max'] = $this->filter_price_max;
        if ($this->filter_category) $params['category'] = $this->filter_category;
        if ($this->filter_supplier) $params['supplier'] = $this->filter_supplier;
        if ($this->filter_stock_status) $params['stock_status'] = $this->filter_stock_status;
        
        $query = http_build_query($params);
        $url = route('export.products.pdf') . ($query ? '?' . $query : '');
        
        $this->dispatch('close-export-modal');
        $this->dispatch('download-pdf', url: $url);
    }

    public function resetFilters()
    {
        $this->filter_date_from = null;
        $this->filter_date_to = null;
        $this->filter_price_min = null;
        $this->filter_price_max = null;
        $this->filter_category = null;
        $this->filter_supplier = null;
        $this->filter_stock_status = null;
        
        $this->dispatch('filters-reset');
        session()->flash('message', 'Filtres réinitialisés.');
        $this->dispatch('scroll-to-top');
    }

    private function resetInput()
    {
        $this->productId = null;
        $this->name = '';
        $this->reference = '';
        $this->serial_number = '';
        $this->description = '';
        $this->purchase_price = '';
        $this->selling_price = '';
        $this->quantity = 0;
        $this->alert_threshold = 5;
        $this->category = '';
        $this->supplier = '';
    }
}