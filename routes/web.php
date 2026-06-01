<?php

use App\Http\Livewire\Module1\ProductIndex;
use App\Http\Livewire\Module2\SupplierIndex;
use App\Http\Livewire\Module2\PurchaseOrderIndex;
use App\Exports\ProductsPdfExport;
use App\Exports\SuppliersPdfExport;
use App\Exports\PurchaseOrdersPdfExport;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Module 3 – Ventes
use App\Http\Livewire\Module3\CustomerIndex;
use App\Http\Livewire\Module3\QuoteIndex;
use App\Http\Livewire\Module3\QuoteForm;
use App\Http\Livewire\Module3\InvoiceIndex;
use App\Http\Livewire\Module3\PosIndex;
use App\Exports\InvoicesPdfExport;
use App\Http\Controllers\Module3\InvoicePdfController;
use App\Exports\TicketPdfExport;
use App\Exports\AnalyticsPdfExport;
use App\Services\AnalyticsService;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard')->middleware('can:view analytics');
    
    // Profile
    Route::get('/profile', \App\Http\Livewire\Profile::class)->name('profile');

    // Module 1 – Produits & Stock
    Route::get('/produits', ProductIndex::class)->name('module1.products.index');

    // Module 2 – Achats & Fournisseurs
    Route::get('/fournisseurs', SupplierIndex::class)->name('module2.suppliers.index');
    Route::get('/achats/bons-commande', PurchaseOrderIndex::class)->name('module2.purchase-orders.index');

    // Module 3 – Ventes & POS
    Route::get('/clients', CustomerIndex::class)->name('module3.customers.index');
    Route::get('/devis', QuoteIndex::class)->name('module3.quotes.index');
    Route::get('/factures', InvoiceIndex::class)->name('module3.invoices.index');
    Route::get('/factures/creer', \App\Http\Livewire\Module3\InvoiceForm::class)->name('module3.invoices.create');
    Route::get('/factures/{id}/modifier', \App\Http\Livewire\Module3\InvoiceForm::class)->name('module3.invoices.edit');
    Route::get('/factures/{id}', \App\Http\Livewire\Module3\InvoiceShow::class)->name('module3.invoices.show');
    Route::get('/factures/{id}/pdf', [InvoicePdfController::class, 'generate'])->name('module3.invoices.pdf');
    Route::get('/pos', PosIndex::class)->name('module3.pos.index');
    Route::get('/devis/creer', QuoteForm::class)->name('module3.quotes.create');
    Route::get('/devis/{id}/modifier', QuoteForm::class)->name('module3.quotes.edit');

    // Module 5 – SAV
    Route::prefix('sav')->name('module5.')->group(function () {
        Route::get('/tickets', \App\Http\Livewire\Module5\TicketIndex::class)->name('tickets.index');
        Route::get('/tickets/create', \App\Http\Livewire\Module5\TicketForm::class)->name('tickets.create');
        Route::get('/tickets/{id}', \App\Http\Livewire\Module5\TicketShow::class)->name('tickets.show');
        Route::get('/tickets/{id}/edit', \App\Http\Livewire\Module5\TicketForm::class)->name('tickets.edit');
        Route::get('/parts', \App\Http\Livewire\Module5\SparePartIndex::class)->name('parts.index');
    });

    // Gestion des utilisateurs (admin only)
    Route::get('/utilisateurs', \App\Http\Livewire\UserIndex::class)->name('users.index');

    // Exports PDF – Module 1
    Route::get('/export/produits/pdf', function (Request $request) {
        $filters = [
            'date_from' => $request->get('date_from'),
            'date_to'   => $request->get('date_to'),
            'price_min' => $request->get('price_min'),
            'price_max' => $request->get('price_max'),
            'category'  => $request->get('category'),
            'supplier'  => $request->get('supplier'),
            'stock_status' => $request->get('stock_status'),
        ];
        return (new ProductsPdfExport($filters))->generate();
    })->name('export.products.pdf');

    // Exports PDF – Module 2
    Route::get('/export/fournisseurs/pdf', function (Request $request) {
        $filters = [
            'date_from' => $request->get('date_from'),
            'date_to'   => $request->get('date_to'),
            'name'      => $request->get('name'),
            'payment_terms_min' => $request->get('payment_terms_min'),
            'payment_terms_max' => $request->get('payment_terms_max'),
        ];
        return (new SuppliersPdfExport($filters))->generate();
    })->name('export.suppliers.pdf');

    Route::get('/export/bons-commande/pdf', function (Request $request) {
        $filters = [
            'date_from' => $request->get('date_from'),
            'date_to'   => $request->get('date_to'),
            'status'    => $request->get('status'),
            'supplier_id' => $request->get('supplier_id'),
            'total_min' => $request->get('total_min'),
            'total_max' => $request->get('total_max'),
        ];
        return (new PurchaseOrdersPdfExport($filters))->generate();
    })->name('export.purchase-orders.pdf');

    // Exports PDF – Module 3
    Route::get('/export/factures/pdf', function (Request $request) {
        $filters = [
            'date_from' => $request->get('date_from'),
            'date_to'   => $request->get('date_to'),
            'status'    => $request->get('status'),
            'customer_id' => $request->get('customer_id'),
        ];
        return (new InvoicesPdfExport($filters))->generate();
    })->name('export.invoices.pdf');

    // Exports PDF – Devis
    Route::get('/export/devis/pdf', function (Request $request) {
        $filters = [
            'date_from' => $request->get('date_from'),
            'date_to'   => $request->get('date_to'),
            'status'    => $request->get('status'),
            'customer_id' => $request->get('customer_id'),
        ];
        return (new \App\Exports\QuotesPdfExport($filters))->generate();
    })->name('export.quotes.pdf');

    // Export PDF Ticket SAV
    Route::get('/sav/tickets/{id}/pdf', function ($id) {
        $ticket = \App\Models\Module5\SavTicket::findOrFail($id);
        return (new TicketPdfExport($ticket))->generate();
    })->name('module5.tickets.pdf');

    // Module 8 – Analytics
    Route::get('/export/analytics/pdf', function (Request $request) {
        $filters = [
            'date_from' => $request->get('date_from'),
            'date_to'   => $request->get('date_to'),
            'category'  => $request->get('category'),
            'supplier'  => $request->get('supplier'),
        ];
        return (new AnalyticsPdfExport($filters))->generate();
    })->name('export.analytics.pdf');

    // API pour le dashboard (données JSON)
    Route::get('/api/analytics/data', function (Request $request) {
        try {
            $filters = [
                'date_from'   => $request->get('date_from'),
                'date_to'     => $request->get('date_to'),
                'category'    => $request->get('category'),
                'granularity' => $request->get('granularity', 'week'),
            ];
            
            $analyticsService = new AnalyticsService();
            $data = $analyticsService->getDashboardData($filters);
            
            // Vérifier si les données existent
            if (!$data || empty($data)) {
                return response()->json([
                    'kpis' => [
                        'daily_ca' => 0,
                        'monthly_ca' => 0,
                        'margin_rate' => 0,
                        'pending_tickets' => 0,
                    ],
                    'salesEvolution' => [],
                    'categoryDistribution' => [],
                    'topProducts' => [],
                    'savPerformance' => [
                        'technicians' => [],
                        'avg_repair_time_minutes' => 0
                    ],
                    'alerts' => [
                        'low_stock_products' => 0,
                        'low_stock_parts' => 0,
                        'critical_tickets' => 0,
                        'expiring_warranty' => 0
                    ],
                ]);
            }
            
            // Formater les données pour les graphiques
            $salesEvolution = $data['salesEvolution'] ?? collect();
            $formattedEvolution = [];
            
            foreach ($salesEvolution as $item) {
                if (is_array($item)) {
                    $formattedEvolution[] = [
                        'period' => $item['label'] ?? $item['period'] ?? '',
                        'label'  => $item['label'] ?? $item['period'] ?? '',
                        'total'  => (float) ($item['total'] ?? 0)
                    ];
                } else {
                    $formattedEvolution[] = [
                        'period' => $item->label ?? $item->period ?? '',
                        'label'  => $item->label ?? $item->period ?? '',
                        'total'  => (float) ($item->total ?? 0)
                    ];
                }
            }
            
            return response()->json([
                'kpis' => [
                    'daily_ca'        => $data['kpis']['daily_ca'] ?? 0,
                    'monthly_ca'      => $data['kpis']['monthly_ca'] ?? 0,
                    'margin_rate'     => $data['kpis']['margin_rate'] ?? 0,
                    'pending_tickets' => $data['kpis']['pending_tickets'] ?? 0,
                ],
                'salesEvolution'      => $formattedEvolution,
                'categoryDistribution' => $data['categoryDistribution'] ?? [],
                'topProducts'         => $data['topProducts'] ?? [],
                'savPerformance'      => $data['savPerformance'] ?? [
                    'technicians' => [],
                    'avg_repair_time_minutes' => 0
                ],
                'alerts' => $data['alerts'] ?? [
                    'low_stock_products'  => 0,
                    'low_stock_parts'     => 0,
                    'critical_tickets'    => 0,
                    'expiring_warranty'   => 0
                ],
            ]);
        } catch (\Exception $e) {
            // Utilisation de Log sans backslash car déjà importé en haut
            Log::error('API Analytics Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'error'   => $e->getMessage(),
                'kpis' => [
                    'daily_ca' => 0,
                    'monthly_ca' => 0,
                    'margin_rate' => 0,
                    'pending_tickets' => 0,
                ],
                'salesEvolution' => [],
                'categoryDistribution' => [],
                'topProducts' => [],
                'savPerformance' => [
                    'technicians' => [],
                    'avg_repair_time_minutes' => 0
                ],
                'alerts' => [
                    'low_stock_products' => 0,
                    'low_stock_parts' => 0,
                    'critical_tickets' => 0,
                    'expiring_warranty' => 0
                ],
            ]);
        }
    })->name('api.analytics.data');
});

require __DIR__.'/auth.php';