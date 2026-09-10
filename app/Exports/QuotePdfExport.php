<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Module3\Quote;
use Illuminate\Support\Facades\Log;

class QuotePdfExport
{
    public function generate(Quote $quote)
    {
        try {
            $data = [
                'quote' => $quote,
                'generated_at' => now()->format('d/m/Y à H:i'),
            ];

            // ✅ Vérifier que la vue existe
            if (!view()->exists('exports.proforma-pdf')) {
                throw new \Exception('La vue exports.proforma-pdf n\'existe pas.');
            }

            $pdf = Pdf::loadView('exports.proforma-pdf', $data);
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'dpi' => 150,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
            ]);

            return $pdf->download('PROFORMA_' . $quote->reference . '.pdf');
            
        } catch (\Exception $e) {
            Log::error('Erreur génération PDF Proforma: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e; // ✅ Relancer l'exception pour que la route la capture
        }
    }
}