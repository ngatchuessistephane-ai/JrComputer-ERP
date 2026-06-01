<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Module3\Quote;

class QuotesPdfExport
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function generate()
    {
        $query = Quote::with('customer', 'items.product');

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('date', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('date', '<=', $this->filters['date_to']);
        }
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['customer_id'])) {
            $query->where('customer_id', $this->filters['customer_id']);
        }

        $quotes = $query->orderBy('date', 'desc')->get();

        $pdf = Pdf::loadView('exports.quotes-pdf', ['quotes' => $quotes, 'filters' => $this->filters]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('devis_'.date('Y-m-d_His').'.pdf');
    }
}