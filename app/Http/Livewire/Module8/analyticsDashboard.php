<?php

namespace App\Http\Livewire\Module8;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Services\AnalyticsService;
use App\Exports\AnalyticsPdfExport;
use Illuminate\Support\Facades\Cache;

#[Layout('layouts.appProd')]
class AnalyticsDashboard extends Component
{
    public $date_from, $date_to, $category_filter, $supplier_filter;
    public $refreshKey = 0;

    protected $queryString = ['date_from', 'date_to', 'category_filter', 'supplier_filter'];

    public function mount()
    {
        $this->date_from = now()->startOfMonth()->toDateString();
        $this->date_to = now()->toDateString();
    }

    public function render()
    {
        $filters = [
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'category' => $this->category_filter,
            'supplier' => $this->supplier_filter,
        ];

        $analytics = app(AnalyticsService::class)->getDashboardData($filters);
        
        return view('livewire.module8.analytics-dashboard', array_merge($analytics, [
            'refreshKey' => $this->refreshKey,
        ]));
    }

   public function refreshData()
{
    // Utilise le service directement pour invalider la bonne clé
    $filters = [
        'date_from' => $this->date_from,
        'date_to'   => $this->date_to,
        'category'  => $this->category_filter,
        'supplier'  => $this->supplier_filter,
    ];

    app(AnalyticsService::class)->invalidateCache($filters);

    $this->refreshKey++;
    $this->dispatch('refreshCharts');
    session()->flash('message', 'Données actualisées avec succès.');
}

    public function exportPdf()
    {
        $filters = [
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'category' => $this->category_filter,
            'supplier' => $this->supplier_filter,
        ];
        return redirect()->route('export.analytics.pdf', '?' . http_build_query($filters));
    }
}