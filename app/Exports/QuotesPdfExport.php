<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Module3\Quote;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class QuotesPdfExport
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function generate()
    {
        try {
            Log::info('=== DÉBUT GÉNÉRATION PDF PROFORMAS ===');
            Log::info('Filtres reçus:', $this->filters);
            
            $query = Quote::with(['customer', 'items.product']);

            // Filtres
            if (!empty($this->filters['date_from'])) {
                $query->whereDate('date', '>=', $this->filters['date_from']);
                Log::info('Filtre date_from: ' . $this->filters['date_from']);
            }
            if (!empty($this->filters['date_to'])) {
                $query->whereDate('date', '<=', $this->filters['date_to']);
                Log::info('Filtre date_to: ' . $this->filters['date_to']);
            }
            if (!empty($this->filters['status'])) {
                $query->where('status', $this->filters['status']);
                Log::info('Filtre status: ' . $this->filters['status']);
            }
            if (!empty($this->filters['customer_id'])) {
                $query->where('customer_id', $this->filters['customer_id']);
                Log::info('Filtre customer_id: ' . $this->filters['customer_id']);
            }

            $quotes = $query->orderBy('date', 'desc')->get();
            
            Log::info('Nombre de Proformas trouvées: ' . $quotes->count());

            // Calcul des statistiques
            $stats = [
                'total' => $quotes->count(),
                'total_amount' => $quotes->sum('total'),
                'sent_count' => $quotes->where('status', 'sent')->count(),
                'accepted_count' => $quotes->where('status', 'accepted')->count(),
                'converted_count' => $quotes->where('status', 'converted')->count(),
                'rejected_count' => $quotes->where('status', 'rejected')->count(),
            ];
            
            Log::info('Statistiques calculées:', $stats);

            $data = [
                'quotes' => $quotes,
                'filters' => $this->filters,
                'stats' => $stats,
                'generated_at' => now()->format('d/m/Y à H:i'),
                'period_label' => $this->getPeriodLabel(),
            ];

            Log::info('Chargement de la vue: exports.quotes-pdf');
            
            // ✅ Vérifier que la vue existe
            if (!view()->exists('exports.quotes-pdf')) {
                throw new \Exception('La vue exports.quotes-pdf n\'existe pas.');
            }

            $pdf = Pdf::loadView('exports.quotes-pdf', $data);
            $pdf->setPaper('A4', 'landscape');
            $pdf->setOptions([
                'dpi' => 150,
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
                'tempDir' => storage_path('app/temp'),
                'logOutputFile' => storage_path('logs/dompdf.log'),
            ]);

            Log::info('PDF généré avec succès, téléchargement...');
            
            return $pdf->download('PROFORMAS_' . date('Y-m-d_His') . '.pdf');
            
        } catch (\Exception $e) {
            Log::error('ERREUR GENERATION PDF: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    private function getPeriodLabel(): string
    {
        $from = isset($this->filters['date_from']) 
            ? Carbon::parse($this->filters['date_from'])->format('d/m/Y') 
            : 'Début';
        
        $to = isset($this->filters['date_to']) 
            ? Carbon::parse($this->filters['date_to'])->format('d/m/Y') 
            : 'Aujourd\'hui';
        
        if ($from === 'Début' && $to === 'Aujourd\'hui') {
            return 'Toutes les Proformas';
        }
        
        return "Du {$from} au {$to}";
    }
}