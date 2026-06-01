<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Module1\Product;
use Illuminate\Support\Facades\Date;

class ProductsPdfExport
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function generate()
    {
        $query = Product::query();

        // Filtre par date de création (plage)
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        // Filtre par prix de vente min/max
        if (!empty($this->filters['price_min'])) {
            $query->where('selling_price', '>=', $this->filters['price_min']);
        }
        if (!empty($this->filters['price_max'])) {
            $query->where('selling_price', '<=', $this->filters['price_max']);
        }

        // Filtre par catégorie
        if (!empty($this->filters['category'])) {
            $query->where('category', $this->filters['category']);
        }

        // Filtre par fournisseur (nom)
        if (!empty($this->filters['supplier'])) {
            $query->where('supplier', $this->filters['supplier']);
        }

        // Filtre par statut stock (bas ou normal)
        if (!empty($this->filters['stock_status'])) {
            if ($this->filters['stock_status'] === 'low') {
                $query->whereRaw('quantity <= alert_threshold');
            } elseif ($this->filters['stock_status'] === 'ok') {
                $query->whereRaw('quantity > alert_threshold');
            }
        }

        $products = $query->orderBy('id', 'desc')->get();

        $data = [
            'products' => $products,
            'filters' => $this->filters,
            'generated_at' => now()->format('d/m/Y H:i'),
        ];

        $pdf = Pdf::loadView('exports.products-pdf', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('produits_'.date('Y-m-d_His').'.pdf');
    }
}