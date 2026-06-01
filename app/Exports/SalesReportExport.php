<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Module3\Invoice;
use App\Models\Module3\InvoiceItem;
use Illuminate\Support\Facades\DB;

class SalesReportExport
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function generate()
    {
        // Requête principale : factures
        $query = Invoice::with('customer')
            ->where('status', 'paid')
            ->orWhere('status', 'partial');

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('date', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('date', '<=', $this->filters['date_to']);
        }
        if (!empty($this->filters['customer_id'])) {
            $query->where('customer_id', $this->filters['customer_id']);
        }

        $invoices = $query->orderBy('date', 'desc')->get();

        // Agrégations pour le rapport
        $totalSales = $invoices->sum('total');
        $totalTax = $invoices->sum('tax');
        $totalPaid = $invoices->sum('paid_amount');

        // Top produits (ventes par produit)
        $topProducts = InvoiceItem::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total) as total_amount'))
            ->whereHas('invoice', function($q) use ($invoices) {
                $q->whereIn('id', $invoices->pluck('id'));
            })
            ->groupBy('product_id')
            ->with('product')
            ->orderBy('total_amount', 'desc')
            ->limit(10)
            ->get();

        // Ventes par méthode de paiement (nécessite jointure avec payments)
        $paymentsByMethod = DB::table('payments')
            ->select('method', DB::raw('SUM(amount) as total'))
            ->whereIn('invoice_id', $invoices->pluck('id'))
            ->groupBy('method')
            ->get();

        $data = [
            'invoices' => $invoices,
            'filters' => $this->filters,
            'totalSales' => $totalSales,
            'totalTax' => $totalTax,
            'totalPaid' => $totalPaid,
            'topProducts' => $topProducts,
            'paymentsByMethod' => $paymentsByMethod,
            'generated_at' => now()->format('d/m/Y H:i'),
        ];

        $pdf = Pdf::loadView('exports.sales-report-pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('rapport_ventes_'.date('Y-m-d_His').'.pdf');
    }
}