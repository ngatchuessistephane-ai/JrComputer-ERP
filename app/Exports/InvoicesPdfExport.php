<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Module3\Invoice;
use Illuminate\Support\Facades\DB;

class InvoicesPdfExport
{
    protected $filters;
    
    public function __construct($filters = []) 
    { 
        $this->filters = $filters; 
    }

    public function generate()
    {
        // Utiliser leftJoin pour éviter les clients nuls
        $query = Invoice::query()
            ->leftJoin('customers', 'invoices.customer_id', '=', 'customers.id')
            ->with(['items.product'])
            ->select('invoices.*', 'customers.name as customer_name');
        
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('invoices.date', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('invoices.date', '<=', $this->filters['date_to']);
        }
        if (!empty($this->filters['status'])) {
            $query->where('invoices.status', $this->filters['status']);
        }
        if (!empty($this->filters['customer_id'])) {
            $query->where('invoices.customer_id', $this->filters['customer_id']);
        }
        
        $invoices = $query->orderBy('invoices.date', 'desc')->get();

        // Vérifier si la vue existe
        if (!view()->exists('Exports.invoices-pdf')) {
            throw new \Exception('La vue Exports.invoices-pdf n\'existe pas. Vérifiez le chemin resources/views/Exports/invoices-pdf.blade.php');
        }

        $pdf = Pdf::loadView('Exports.invoices-pdf', [
            'invoices' => $invoices, 
            'filters' => $this->filters
        ]);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('factures_' . date('Y-m-d_His') . '.pdf');
    }
}