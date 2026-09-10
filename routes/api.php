<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SavApiController;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\BroadcastAuthController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\NotificationPageController;

/*
|--------------------------------------------------------------------------
| API Routes — JR Computer ERP
|--------------------------------------------------------------------------
*/

// Route d'authentification pour broadcasting
 Route::post('/broadcasting/auth', function (Request $request) {
     return Broadcast::auth($request);
        })->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // ============================================================
    // SAV TECHNICIEN (Application Mobile)
    // ============================================================
    Route::prefix('tech')->group(function () {
        Route::get('/tickets', [SavApiController::class, 'getMyTickets']);
        Route::patch('/tickets/{id}', [SavApiController::class, 'updateStatus']);
        Route::post('/tickets/{id}/close', [SavApiController::class, 'closeTicket']);
        Route::get('/parts', [SavApiController::class, 'getSpareParts']);
    });

    // ============================================================
    // ANALYTICS & DASHBOARD (Performance SAV)
    // ============================================================
    Route::get('/analytics/data', function (Request $request) {
        try {
            $filters = $request->only(['date_from', 'date_to']);
            
            // Normalisation des dates
            if (!empty($filters['date_from'])) {
                $filters['date_from'] = Carbon::parse($filters['date_from'])->startOfDay();
            } else {
                $filters['date_from'] = Carbon::now()->startOfMonth();
            }
            
            if (!empty($filters['date_to'])) {
                $filters['date_to'] = Carbon::parse($filters['date_to'])->endOfDay();
            } else {
                $filters['date_to'] = Carbon::now()->endOfDay();
            }
            
            $analytics = app(AnalyticsService::class)->getDashboardData($filters);
            
            // Extraction des données avec fallbacks
            $kpis = $analytics['kpis'] ?? [];
            $ticketsEvolution = $analytics['ticketsEvolution'] ?? collect();
            $ticketsByStatus = $analytics['ticketsByStatus'] ?? collect();
            $topTechnicians = $analytics['topTechnicians'] ?? collect();
            $topPannes = $analytics['topPannes'] ?? collect();
            $alerts = $analytics['alerts'] ?? [];
            
            // Transformation des ticketsEvolution pour le frontend
            $formattedEvolution = [];
            foreach ($ticketsEvolution as $item) {
                $formattedEvolution[] = [
                    'period' => $item['period'] ?? $item->period ?? '',
                    'label' => $item['label'] ?? $item->label ?? '',
                    'created' => (int) ($item['created'] ?? $item->created ?? 0),
                    'closed' => (int) ($item['closed'] ?? $item->closed ?? 0),
                    'backlog_diff' => (int) ($item['backlog_diff'] ?? $item->backlog_diff ?? 0),
                ];
            }
            
            // Transformation des ticketsByStatus
            $formattedStatus = [];
            foreach ($ticketsByStatus as $status => $count) {
                $formattedStatus[] = [
                    'status' => $status,
                    'count' => (int) $count,
                ];
            }
            
            // Transformation des topTechnicians
            $formattedTechnicians = [];
            foreach ($topTechnicians as $tech) {
                $formattedTechnicians[] = [
                    'technician_id' => $tech['technician_id'] ?? $tech->technician_id ?? null,
                    'technician_name' => $tech['technician_name'] ?? $tech->technician_name ?? '—',
                    'total_tickets' => (int) ($tech['total_tickets'] ?? $tech->total_tickets ?? 0),
                    'resolved_tickets' => (int) ($tech['resolved_tickets'] ?? $tech->resolved_tickets ?? 0),
                    'completion_rate' => (int) ($tech['completion_rate'] ?? $tech->completion_rate ?? 0),
                    'avg_resolution_hours' => (float) ($tech['avg_resolution_hours'] ?? $tech->avg_resolution_hours ?? 0),
                ];
            }
            
            // Transformation des topPannes
            $formattedPannes = [];
            foreach ($topPannes as $panne) {
                $formattedPannes[] = [
                    'rank' => (int) ($panne['rank'] ?? $panne->rank ?? 0),
                    'panne' => $panne['panne'] ?? $panne->panne ?? '',
                    'count' => (int) ($panne['count'] ?? $panne->count ?? 0),
                    'percentage' => (int) ($panne['percentage'] ?? $panne->percentage ?? 0),
                ];
            }
            
            return response()->json([
                'success' => true,
                'kpis' => [
                    'resolved_today' => (int) ($kpis['resolved_today'] ?? 0),
                    'pending_tickets' => (int) ($kpis['pending_tickets'] ?? 0),
                    'completion_ratio' => (float) ($kpis['completion_ratio'] ?? 0),
                    'avg_resolution_hours' => (float) ($kpis['avg_resolution_hours'] ?? 0),
                    'assigned_count' => (int) ($kpis['assigned_count'] ?? 0),
                    'completed_count' => (int) ($kpis['completed_count'] ?? 0),
                ],
                'ticketsEvolution' => $formattedEvolution,
                'ticketsByStatus' => $formattedStatus,
                'topTechnicians' => $formattedTechnicians,
                'topPannes' => $formattedPannes,
                'alerts' => [
                    'low_stock_products' => (int) ($alerts['low_stock_products'] ?? 0),
                    'low_stock_parts' => (int) ($alerts['low_stock_parts'] ?? 0),
                    'critical_tickets' => (int) ($alerts['critical_tickets'] ?? 0),
                    'expiring_warranty' => (int) ($alerts['expiring_warranty'] ?? 0),
                ],
                'generated_at' => now()->toIso8601String(),
            ]);
            
        } catch (\Exception $e) {
            \Log::error('API Analytics Error: ' . $e->getMessage());
            
            // Retourner des données par défaut en cas d'erreur
            return response()->json([
                'success' => false,
                'kpis' => [
                    'resolved_today' => 0,
                    'pending_tickets' => 0,
                    'completion_ratio' => 0,
                    'avg_resolution_hours' => 0,
                    'assigned_count' => 0,
                    'completed_count' => 0,
                ],
                'ticketsEvolution' => [],
                'ticketsByStatus' => [],
                'topTechnicians' => [],
                'topPannes' => [],
                'alerts' => [
                    'low_stock_products' => 0,
                    'low_stock_parts' => 0,
                    'critical_tickets' => 0,
                    'expiring_warranty' => 0,
                ],
                'generated_at' => now()->toIso8601String(),
            ]);
        }
    })->name('api.analytics.data');

    // ============================================================
    // COMMERCIAL DASHBOARD (Ventes, Produits, Top produits)
    // ============================================================
    Route::get('/commercial/data', function (Request $request) {
        try {
            // Récupération des filtres
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');
            $category = $request->get('category');
            
            // Normalisation des dates
            $from = !empty($dateFrom) ? Carbon::parse($dateFrom)->startOfDay() : Carbon::now()->startOfMonth();
            $to = !empty($dateTo) ? Carbon::parse($dateTo)->endOfDay() : Carbon::now()->endOfDay();
            
            // 1. KPIs - CA Journalier
            $dailyCa = (float) DB::table('invoices')
                ->whereDate('date', Carbon::today())
                ->whereIn('status', ['sent', 'paid'])
                ->sum('total');
            
            // 2. KPIs - CA Période
            $monthlyCa = (float) DB::table('invoices')
                ->whereBetween('date', [$from, $to])
                ->whereIn('status', ['sent', 'paid'])
                ->sum('total');
            
            // 3. Marge brute (calcul simplifié)
            $cogs = (float) DB::table('invoice_items')
                ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
                ->join('products', 'invoice_items.product_id', '=', 'products.id')
                ->whereBetween('invoices.date', [$from, $to])
                ->whereIn('invoices.status', ['sent', 'paid'])
                ->sum(DB::raw('invoice_items.quantity * products.purchase_price'));
            
            $marginRate = $monthlyCa > 0 ? round((($monthlyCa - $cogs) / $monthlyCa) * 100, 1) : 0;
            
            // 4. Tickets SAV en cours
            $pendingTickets = (int) DB::table('sav_tickets')
                ->whereIn('status', ['pending', 'assigned', 'diagnosing', 'repairing'])
                ->count();
            
            // 5. Évolution du CA (par mois)
            $salesEvolution = DB::table('invoices')
                ->selectRaw('DATE_FORMAT(date, "%Y-%m") as period, SUM(total) as total')
                ->whereBetween('date', [$from, $to])
                ->whereIn('status', ['sent', 'paid'])
                ->groupBy('period')
                ->orderBy('period', 'asc')
                ->get()
                ->map(fn($item) => [
                    'period' => $item->period,
                    'label' => Carbon::parse($item->period . '-01')->isoFormat('MMM YYYY'),
                    'total' => (float) $item->total,
                ]);
            
            if ($salesEvolution->isEmpty()) {
                $salesEvolution = collect([[
                    'period' => Carbon::now()->format('Y-m'),
                    'label' => Carbon::now()->isoFormat('MMM YYYY'),
                    'total' => 0
                ]]);
            }
            
            // 6. Répartition par catégorie
            $categoryQuery = DB::table('invoice_items')
                ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
                ->join('products', 'invoice_items.product_id', '=', 'products.id')
                ->whereBetween('invoices.date', [$from, $to])
                ->whereIn('invoices.status', ['sent', 'paid']);
            
            if (!empty($category)) {
                $categoryQuery->where('products.category', $category);
            }
            
            $categoryDistribution = $categoryQuery
                ->select('products.category', DB::raw('SUM(invoice_items.total) as total'))
                ->groupBy('products.category')
                ->get()
                ->map(fn($item) => [
                    'category' => $item->category ?: 'Non catégorisé',
                    'total' => (float) $item->total,
                ]);
            
            if ($categoryDistribution->isEmpty()) {
                $categoryDistribution = collect([['category' => 'Aucune vente', 'total' => 0]]);
            }
            
            // 7. Top 10 produits
            $productsQuery = DB::table('invoice_items')
                ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
                ->join('products', 'invoice_items.product_id', '=', 'products.id')
                ->whereBetween('invoices.date', [$from, $to])
                ->whereIn('invoices.status', ['sent', 'paid']);
            
            if (!empty($category)) {
                $productsQuery->where('products.category', $category);
            }
            
            $topProducts = $productsQuery
                ->select(
                    'products.name',
                    'products.category',
                    DB::raw('SUM(invoice_items.quantity) as qty'),
                    DB::raw('SUM(invoice_items.total) as total')
                )
                ->groupBy('products.id', 'products.name', 'products.category')
                ->orderByDesc('total')
                ->limit(10)
                ->get()
                ->map(fn($item) => [
                    'name' => $item->name,
                    'category' => $item->category ?: '—',
                    'qty' => (int) $item->qty,
                    'total' => (float) $item->total,
                ]);
            
            if ($topProducts->isEmpty()) {
                $topProducts = collect([['name' => 'Aucune vente', 'category' => '—', 'qty' => 0, 'total' => 0]]);
            }
            
            // 8. Performance SAV (pour la table)
            $savPerformance = [
                'technicians' => DB::table('sav_tickets')
                    ->join('users', 'sav_tickets.assigned_to', '=', 'users.id')
                    ->whereNotNull('sav_tickets.assigned_to')
                    ->select(
                        'users.name as technician_name',
                        DB::raw('COUNT(*) as tickets'),
                        DB::raw('SUM(CASE WHEN sav_tickets.status = "completed" THEN 1 ELSE 0 END) as completed')
                    )
                    ->groupBy('sav_tickets.assigned_to', 'users.name')
                    ->get()
                    ->map(fn($item) => [
                        'technician_name' => $item->technician_name,
                        'tickets' => (int) $item->tickets,
                        'completion_rate' => $item->tickets > 0 ? round(($item->completed / $item->tickets) * 100) : 0,
                    ]),
                'avg_repair_time_minutes' => 0,
            ];
            
            // 9. Alertes
            $alerts = [
                'low_stock_products' => (int) DB::table('products')->whereRaw('quantity <= alert_threshold')->count(),
                'low_stock_parts' => (int) DB::table('spare_parts')->whereRaw('quantity_in_stock <= min_stock_alert')->count(),
                'critical_tickets' => (int) DB::table('sav_tickets')
                    ->where('priority', 'critical')
                    ->whereNotIn('status', ['completed', 'restituted'])
                    ->count(),
                'expiring_warranty' => (int) DB::table('sav_tickets')
                    ->where('is_warranty', true)
                    ->whereDate('warranty_end_date', '>=', Carbon::today())
                    ->whereDate('warranty_end_date', '<=', Carbon::today()->addDays(30))
                    ->count(),
            ];
            
            return response()->json([
                'success' => true,
                'kpis' => [
                    'daily_ca' => $dailyCa,
                    'monthly_ca' => $monthlyCa,
                    'margin_rate' => $marginRate,
                    'pending_tickets' => $pendingTickets,
                ],
                'salesEvolution' => $salesEvolution,
                'categoryDistribution' => $categoryDistribution,
                'topProducts' => $topProducts,
                'savPerformance' => $savPerformance,
                'alerts' => $alerts,
                'generated_at' => Carbon::now()->toIso8601String(),
            ]);
            
        } catch (\Exception $e) {
            Log::error('API Commercial Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'kpis' => [
                    'daily_ca' => 0,
                    'monthly_ca' => 0,
                    'margin_rate' => 0,
                    'pending_tickets' => 0,
                ],
                'salesEvolution' => [],
                'categoryDistribution' => [],
                'topProducts' => [],
                'savPerformance' => ['technicians' => [], 'avg_repair_time_minutes' => 0],
                'alerts' => [
                    'low_stock_products' => 0,
                    'low_stock_parts' => 0,
                    'critical_tickets' => 0,
                    'expiring_warranty' => 0,
                ],
                'generated_at' => Carbon::now()->toIso8601String(),
            ]);
        }
    })->name('api.commercial.data');

    // Ajoutez cette route dans api.php (après la route /commercial/data)

Route::get('/commercial/categories', function (Request $request) {
    try {
        $categories = app(\App\Services\AnalyticsService::class)->getCategoriesCached();
        return response()->json([
            'success' => true,
            'categories' => $categories,
        ]);
    } catch (\Exception $e) {
        \Log::error('API Categories Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'categories' => [],
        ]);
    }
})->name('api.commercial.categories');
    
    // ============================================================
    // ACHATS DATA (pour graphique Ventes vs Achats) - VERSION CORRIGÉE
    // ============================================================
    Route::get('/purchases/data', function (Request $request) {
        try {
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');
            
            // Par défaut : dernier mois si aucun filtre
            $from = !empty($dateFrom) ? Carbon::parse($dateFrom)->startOfDay() : Carbon::now()->subMonth()->startOfDay();
            $to = !empty($dateTo) ? Carbon::parse($dateTo)->endOfDay() : Carbon::now()->endOfDay();
            
            // 🔍 Vérification : combien de commandes reçues ?
            $allReceivedCount = DB::table('purchase_orders')
                ->where('status', 'received')
                ->count();
            
            Log::info('API Purchases - Nombre total de commandes reçues: ' . $allReceivedCount);
            Log::info('API Purchases - Période: ' . $from->toDateString() . ' → ' . $to->toDateString());
            
            $purchasesEvolution = DB::table('purchase_orders')
                ->selectRaw('DATE_FORMAT(order_date, "%Y-%m") as period, SUM(total) as total')
                ->where('status', 'received')
                ->where('order_date', '>=', $from)
                ->where('order_date', '<=', $to)
                ->groupBy('period')
                ->orderBy('period', 'asc')
                ->get()
                ->map(fn($item) => [
                    'period' => $item->period,
                    'label' => Carbon::parse($item->period . '-01')->isoFormat('MMM YYYY'),
                    'total' => (float) $item->total,
                ]);
            
            Log::info('API Purchases - Résultat: ' . json_encode($purchasesEvolution));
            
            return response()->json([
                'success' => true,
                'purchasesEvolution' => $purchasesEvolution,
            ]);
            
        } catch (\Exception $e) {
            Log::error('API Purchases Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'purchasesEvolution' => [],
            ]);
        }
    })->name('api.purchases.data');

   // ============================================================
// SAV TICKETS DATA (pour graphique mixte) - VERSION CORRIGÉE
// ============================================================
Route::get('/sav-tickets/data', function (Request $request) {
    try {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        $from = !empty($dateFrom) ? Carbon::parse($dateFrom)->startOfDay() : Carbon::now()->startOfMonth();
        $to = !empty($dateTo) ? Carbon::parse($dateTo)->endOfDay() : Carbon::now()->endOfDay();
        
        // Tickets créés par mois
        $createdTickets = DB::table('sav_tickets')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as period, COUNT(*) as total')
            ->where('created_at', '>=', $from)
            ->where('created_at', '<=', $to)
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get()
            ->map(fn($item) => [
                'period' => $item->period,
                'label' => Carbon::parse($item->period . '-01')->isoFormat('MMM YYYY'),
                'total' => (int) $item->total,
            ]);
        
        // Tickets clôturés par mois (maintenant la colonne closed_at existe)
        $closedTickets = DB::table('sav_tickets')
            ->selectRaw('DATE_FORMAT(closed_at, "%Y-%m") as period, COUNT(*) as total')
            ->whereNotNull('closed_at')
            ->where('closed_at', '>=', $from)
            ->where('closed_at', '<=', $to)
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get()
            ->map(fn($item) => [
                'period' => $item->period,
                'label' => Carbon::parse($item->period . '-01')->isoFormat('MMM YYYY'),
                'total' => (int) $item->total,
            ]);
        
        // Si aucun ticket clôturé, retourner un tableau vide (pas d'erreur)
        
        return response()->json([
            'success' => true,
            'createdTickets' => $createdTickets,
            'closedTickets' => $closedTickets,
        ]);
        
    } catch (\Exception $e) {
        \Log::error('API SavTickets Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'createdTickets' => [],
            'closedTickets' => [],
        ]);
    }
})->name('api.sav-tickets.data');

    // ============================================================
    // ALERTES DASHBOARD (Endpoint dédié)
    // ============================================================
    Route::get('/alerts', function (Request $request) {
        try {
            $analytics = app(AnalyticsService::class)->getDashboardData([]);
            $alerts = $analytics['alerts'] ?? [];
            
            return response()->json([
                'success' => true,
                'alerts' => [
                    'low_stock_products' => (int) ($alerts['low_stock_products'] ?? 0),
                    'low_stock_parts' => (int) ($alerts['low_stock_parts'] ?? 0),
                    'critical_tickets' => (int) ($alerts['critical_tickets'] ?? 0),
                    'expiring_warranty' => (int) ($alerts['expiring_warranty'] ?? 0),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'alerts' => ['low_stock_products' => 0, 'low_stock_parts' => 0, 'critical_tickets' => 0, 'expiring_warranty' => 0],
            ]);
        }
    })->name('api.alerts');

    // ============================================================
    // NOTIFICATIONS (Système de notifications Laravel)
    // ============================================================
    Route::delete('/activities/{id}', [ActivityController::class, 'destroy']);
    Route::delete('/activities', [ActivityController::class, 'destroyMultiple']);
    
   // Route::get('/notifications/page', [NotificationPageController::class, 'fetch'])->middleware('auth:sanctum');
    Route::get('/notifications', function (Request $request) {
        $user = $request->user();
        $notifications = $user->notifications()->latest()->take(50)->get();
        
        return response()->json([
            'success' => true,
            'notifications' => $notifications->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'type' => $notif->data['type'] ?? 'general',
                    'title' => $notif->data['title'] ?? null,
                    'message' => $notif->data['message'] ?? '',
                    'data' => $notif->data,
                    'read_at' => $notif->read_at,
                    'created_at' => $notif->created_at->toIso8601String(),
                ];
            }),
            'unread_count' => $user->unreadNotifications->count(),
        ]);
    });

    Route::post('/notifications/{id}/read', function ($id, Request $request) {
        $notification = $request->user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }
        return response()->json(['success' => true]);
    });

    Route::post('/notifications/read-all', function (Request $request) {
        $request->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    });

    // ============================================================
    // ACTIVITÉS SYSTÈME (Historique)
    // ============================================================
    Route::get('/activities', [ActivityController::class, 'index']);
    Route::post('/activities/{id}/read', [ActivityController::class, 'markAsRead']);
    Route::post('/activities/read-all', [ActivityController::class, 'markAllAsRead']);

    // ============================================================
    // AUTHENTIFICATION WEBSOCKET (Reverb)
    // ============================================================
    Route::post('/broadcasting/auth', [BroadcastAuthController::class, 'authenticate']);

});