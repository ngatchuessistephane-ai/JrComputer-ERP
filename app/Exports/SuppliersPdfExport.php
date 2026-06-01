<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Module2\Supplier;

class SuppliersPdfExport
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function generate()
    {
        $query = Supplier::query();

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        if (!empty($this->filters['name'])) {
            $query->where('name', 'like', '%'.$this->filters['name'].'%');
        }

        if (!empty($this->filters['payment_terms_min'])) {
            $query->where('payment_terms', '>=', $this->filters['payment_terms_min']);
        }
        if (!empty($this->filters['payment_terms_max'])) {
            $query->where('payment_terms', '<=', $this->filters['payment_terms_max']);
        }

        $suppliers = $query->orderBy('name')->get();

        $pdf = Pdf::loadView('exports.suppliers-pdf', ['suppliers' => $suppliers, 'filters' => $this->filters]);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('fournisseurs_'.date('Y-m-d_His').'.pdf');
    }
}