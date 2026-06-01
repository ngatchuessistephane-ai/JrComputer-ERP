<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Module2\PurchaseOrder;

class PurchaseOrdersPdfExport
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function generate()
    {
        $query = PurchaseOrder::with('supplier', 'items.product');

        // Filtre par dates de commande
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('order_date', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('order_date', '<=', $this->filters['date_to']);
        }

        // Filtre par statut
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        // Filtre par fournisseur
        if (!empty($this->filters['supplier_id'])) {
            $query->where('supplier_id', $this->filters['supplier_id']);
        }

        // Filtre par montant total min/max
        if (!empty($this->filters['total_min'])) {
            $query->where('total', '>=', $this->filters['total_min']);
        }
        if (!empty($this->filters['total_max'])) {
            $query->where('total', '<=', $this->filters['total_max']);
        }

        $orders = $query->orderBy('order_date', 'desc')->get();

        $pdf = Pdf::loadView('exports.purchase-orders-pdf', ['orders' => $orders, 'filters' => $this->filters]);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('bons_commande_'.date('Y-m-d_His').'.pdf');
    }
}