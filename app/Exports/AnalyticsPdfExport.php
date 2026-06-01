<?php

namespace App\Exports;

use App\Services\AnalyticsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AnalyticsPdfExport
{
    public function __construct(protected array $filters = []) {}

    public function generate()
    {
        $data = app(AnalyticsService::class)->getDashboardData($this->filters);

        // Normalise la collection technicians pour Blade
        if (isset($data['savPerformance']['technicians'])) {
            $data['savPerformance']['technicians'] = collect($data['savPerformance']['technicians']);
        }

        $pdf = Pdf::loadView('exports.analytics-pdf', [
            ...$data,
            'filters'      => $this->filters,
            'generated_at' => now()->format('d/m/Y à H:i'),
            'period_label' => $this->periodLabel(),
        ]);

        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'dpi'                  => 150,
            'isRemoteEnabled'      => false,
            'isHtml5ParserEnabled' => true,
        ]);

        return $pdf->download('rapport_analytique_' . now()->format('Y-m-d_His') . '.pdf');
    }

    private function periodLabel(): string
    {
        $from = isset($this->filters['date_from'])
            ? Carbon::parse($this->filters['date_from'])->format('d/m/Y')
            : now()->startOfMonth()->format('d/m/Y');

        $to = isset($this->filters['date_to'])
            ? Carbon::parse($this->filters['date_to'])->format('d/m/Y')
            : now()->format('d/m/Y');

        return "Du {$from} au {$to}";
    }
}