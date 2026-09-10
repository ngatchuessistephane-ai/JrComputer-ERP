<?php

namespace App\Services;

use App\Models\Module1\Product;
use App\Models\Module5\SparePart;
use App\Models\Module3\Invoice;
use App\Models\Module3\InvoiceItem;
use App\Models\Module3\Quote;
use App\Models\Module5\SavTicket;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    protected int $cacheTtl = 3600; // 1 heure (augmenté pour meilleure performance)
    protected int $quickCacheTtl = 600; // 10 minutes pour les données temps réel
    private const INVOICE_ACTIVE_STATUSES = ['sent', 'paid'];
    private const TICKET_IN_PROGRESS_STATUSES = ['pending', 'assigned', 'diagnosing', 'repairing'];

    // ─────────────────────────────────────────────────────────────────────────
    //  ENTRÉE PUBLIQUE AVEC CACHE OPTIMISÉ
    // ─────────────────────────────────────────────────────────────────────────

    public function getDashboardData(array $filters, bool $quick = false): array
    {
        $filters = $this->normalizeFilters($filters);
        $cacheKey = $this->buildCacheKey($filters);
        $ttl = $quick ? $this->quickCacheTtl : $this->cacheTtl;

        return Cache::remember($cacheKey, $ttl, function () use ($filters) {
            $previousFilters = $this->previousPeriodFilters($filters);

            $kpis = $this->buildKpisOptimized($filters);
            
            return [
                'kpis' => $kpis,
                'kpis_previous' => $this->buildKpisOptimized($previousFilters),
                'salesEvolution' => $this->querySalesEvolutionOptimized($filters),
                'categoryDistribution' => $this->queryCategoryDistributionOptimized($filters),
                'topProducts' => $this->queryTopProductsOptimized($filters),
                'savPerformance' => $this->buildSavPerformanceOptimized($filters),
                'ticketsByStatus' => $this->getTicketsByStatusCached(),
                'alerts' => $this->getAlertsCached(),
                'generated_at' => now()->toIso8601String(),
            ];
        });
    }

    // ✅ NOUVELLE MÉTHODE : Récupération rapide des KPIs uniquement
    public function getQuickKPIs(array $filters): array
    {
        $filters = $this->normalizeFilters($filters);
        $cacheKey = 'quick_kpis_' . $this->buildCacheKey($filters);

        return Cache::remember($cacheKey, 120, function () use ($filters) {
            return $this->buildKpisOptimized($filters);
        });
    }

    // ✅ NOUVELLE MÉTHODE : Catégories en cache long
    public function getCategoriesCached(): array
    {
        return Cache::remember('commercial_categories_v2', 86400, function () { // 24 heures
            $categories = DB::table('products')
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->distinct()
                ->pluck('category')
                ->toArray();
            
            return array_values(array_filter($categories));
        });
    }

    public function invalidateCache(array $filters = []): void
    {
        if (!empty($filters)) {
            Cache::forget($this->buildCacheKey($this->normalizeFilters($filters)));
            Cache::forget('quick_kpis_' . $this->buildCacheKey($this->normalizeFilters($filters)));
        }
        // Invalider les caches globaux
        Cache::forget('tickets_by_status_v3');
        Cache::forget('alerts_data_v3');
        Cache::forget('pending_tickets_count');
        Cache::forget('daily_ca_value');
        Cache::forget('commercial_categories_v2');
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  NORMALISATION & UTILITAIRES
    // ─────────────────────────────────────────────────────────────────────────

    private function normalizeFilters(array $filters): array
    {
        return [
            'date_from' => Carbon::parse($filters['date_from'] ?? now()->startOfMonth())->startOfDay(),
            'date_to'   => Carbon::parse($filters['date_to'] ?? now())->endOfDay(),
            'category'  => $filters['category'] ?? null,
            'supplier'  => $filters['supplier'] ?? null,
        ];
    }

    private function previousPeriodFilters(array $filters): array
    {
        $from = Carbon::parse($filters['date_from']);
        $to   = Carbon::parse($filters['date_to']);
        $span = $from->diffInDays($to);

        return array_merge($filters, [
            'date_from' => $from->copy()->subDays($span + 1)->startOfDay(),
            'date_to'   => $from->copy()->subDay()->endOfDay(),
        ]);
    }

    private function buildCacheKey(array $filters): string
    {
        return 'analytics_v6_' . md5(json_encode([
            'from' => $filters['date_from']->toDateString(),
            'to' => $filters['date_to']->toDateString(),
            'category' => $filters['category'],
            'supplier' => $filters['supplier'],
        ]));
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  KPIS OPTIMISÉS (CACHÉS INDIVIDUELLEMENT)
    // ─────────────────────────────────────────────────────────────────────────

    private function buildKpisOptimized(array $filters): array
    {
        ['date_from' => $from, 'date_to' => $to] = $filters;

        // CA journalier (cache 5 minutes)
        $dailyCa = Cache::remember('daily_ca_value', 300, function () {
            return (float) DB::table('invoices')
                ->whereDate('date', now())
                ->whereIn('status', self::INVOICE_ACTIVE_STATUSES)
                ->sum('total');
        });

        // Requête unique pour les agrégats de la période (avec cache)
        $periodCacheKey = 'period_stats_v2_' . $from->toDateString() . '_' . $to->toDateString() . '_' . ($filters['category'] ?? 'all');
        
        $periodStats = Cache::remember($periodCacheKey, $this->cacheTtl, function () use ($from, $to, $filters) {
            $query = DB::table('invoices')
                ->whereBetween('date', [$from, $to])
                ->whereIn('status', self::INVOICE_ACTIVE_STATUSES);
            
            if ($filters['category']) {
                $query->whereIn('id', function ($q) use ($filters) {
                    $q->select('invoice_id')
                        ->from('invoice_items')
                        ->join('products', 'invoice_items.product_id', '=', 'products.id')
                        ->where('products.category', $filters['category'])
                        ->groupBy('invoice_id');
                });
            }
            
            return $query->selectRaw('
                COALESCE(SUM(total), 0) as revenue,
                COUNT(*) as invoice_count,
                COALESCE(SUM(total) / NULLIF(COUNT(*), 0), 0) as avg_basket
            ')->first();
        });

        // Tickets en cours (cache 2 minutes)
        $pendingTickets = Cache::remember('pending_tickets_count', 120, function () {
            return (int) DB::table('sav_tickets')
                ->whereIn('status', self::TICKET_IN_PROGRESS_STATUSES)
                ->count();
        });

        // COGS (avec cache)
        $cogs = $this->computeCogsOptimized($from, $to, $filters['category']);

        // Taux de conversion (avec cache)
        $conversionRate = $this->getConversionRateOptimized($from, $to);

        return [
            'daily_ca' => $dailyCa,
            'monthly_ca' => (float) $periodStats->revenue,
            'margin_rate' => $periodStats->revenue > 0 
                ? round((($periodStats->revenue - $cogs) / $periodStats->revenue) * 100, 1) 
                : 0,
            'conversion_rate' => $conversionRate,
            'pending_tickets' => $pendingTickets,
            'invoice_count' => (int) $periodStats->invoice_count,
            'avg_basket' => (float) $periodStats->avg_basket,
        ];
    }

    private function computeCogsOptimized(Carbon $from, Carbon $to, ?string $category): float
    {
        $cacheKey = 'cogs_' . $from->toDateString() . '_' . $to->toDateString() . '_' . ($category ?? 'all');
        
        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($from, $to, $category) {
            $query = DB::table('invoice_items')
                ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
                ->join('products', 'invoice_items.product_id', '=', 'products.id')
                ->whereBetween('invoices.date', [$from, $to])
                ->whereIn('invoices.status', self::INVOICE_ACTIVE_STATUSES);

            if ($category) {
                $query->where('products.category', $category);
            }

            return (float) $query->sum(DB::raw('invoice_items.quantity * products.purchase_price'));
        });
    }

    private function getConversionRateOptimized(Carbon $from, Carbon $to): float
    {
        $cacheKey = 'conversion_rate_' . $from->toDateString() . '_' . $to->toDateString();
        
        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($from, $to) {
            $stats = DB::table('quotes')
                ->whereBetween('date', [$from, $to])
                ->selectRaw('
                    COALESCE(SUM(CASE WHEN status = "sent" THEN 1 ELSE 0 END), 0) as sent,
                    COALESCE(SUM(CASE WHEN status = "converted" THEN 1 ELSE 0 END), 0) as converted
                ')
                ->first();

            $sent = (int) ($stats->sent ?? 0);
            $converted = (int) ($stats->converted ?? 0);

            return $sent > 0 ? round(($converted / $sent) * 100, 1) : 0;
        });
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  REQUÊTES GRAPHIQUES OPTIMISÉES
    // ─────────────────────────────────────────────────────────────────────────

    private function querySalesEvolutionOptimized(array $filters): Collection
    {
        $cacheKey = 'sales_evolution_' . md5(json_encode([
            'from' => $filters['date_from']->toDateString(),
            'to' => $filters['date_to']->toDateString(),
        ]));

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($filters) {
            // Limiter la période à 12 mois pour éviter les requêtes trop lourdes
            $from = $filters['date_from'];
            $to = $filters['date_to'];
            
            if ($from->diffInMonths($to) > 12) {
                $from = $to->copy()->subMonths(12);
            }
            
            $results = DB::table('invoices')
                ->selectRaw('DATE_FORMAT(date, "%Y-%m") as month, SUM(total) as total')
                ->whereBetween('date', [$from, $to])
                ->whereIn('status', self::INVOICE_ACTIVE_STATUSES)
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            
            if ($results->isEmpty()) {
                return collect([['period' => now()->format('Y-m'), 'label' => now()->format('Y-m'), 'total' => 0]]);
            }
            
            return $results->map(fn($item) => [
                'period' => $item->month,
                'label' => $item->month,
                'total' => (float) $item->total,
            ]);
        });
    }

    private function queryCategoryDistributionOptimized(array $filters): Collection
    {
        $cacheKey = 'category_dist_' . md5(json_encode([
            'from' => $filters['date_from']->toDateString(),
            'to' => $filters['date_to']->toDateString(),
            'category' => $filters['category'],
        ]));

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($filters) {
            $query = DB::table('invoice_items')
                ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
                ->join('products', 'invoice_items.product_id', '=', 'products.id')
                ->whereBetween('invoices.date', [$filters['date_from'], $filters['date_to']])
                ->whereIn('invoices.status', self::INVOICE_ACTIVE_STATUSES)
                ->select('products.category', DB::raw('SUM(invoice_items.total) as total'))
                ->groupBy('products.category');

            if ($filters['category']) {
                $query->where('products.category', $filters['category']);
            }

            $results = $query->get();
            
            if ($results->isEmpty()) {
                return collect([['category' => 'Aucune donnée', 'total' => 0]]);
            }
            
            return $results->map(fn($row) => [
                'category' => $row->category ?: 'Non catégorisé',
                'total' => (float) $row->total,
            ]);
        });
    }

    private function queryTopProductsOptimized(array $filters, int $limit = 10): Collection
    {
        $cacheKey = 'top_products_' . md5(json_encode([
            'from' => $filters['date_from']->toDateString(),
            'to' => $filters['date_to']->toDateString(),
            'category' => $filters['category'],
            'limit' => $limit,
        ]));

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($filters, $limit) {
            $query = DB::table('invoice_items')
                ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
                ->join('products', 'invoice_items.product_id', '=', 'products.id')
                ->whereBetween('invoices.date', [$filters['date_from'], $filters['date_to']])
                ->whereIn('invoices.status', self::INVOICE_ACTIVE_STATUSES)
                ->select(
                    'products.id',
                    'products.name',
                    'products.category',
                    DB::raw('SUM(invoice_items.quantity) as qty'),
                    DB::raw('SUM(invoice_items.total) as total')
                )
                ->groupBy('products.id', 'products.name', 'products.category')
                ->orderByDesc('total')
                ->limit($limit);

            if ($filters['category']) {
                $query->where('products.category', $filters['category']);
            }

            $results = $query->get();
            
            if ($results->isEmpty()) {
                return collect([['name' => 'Aucune vente', 'category' => '-', 'qty' => 0, 'total' => 0]]);
            }
            
            return $results;
        });
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  SAV OPTIMISÉ
    // ─────────────────────────────────────────────────────────────────────────

    private function buildSavPerformanceOptimized(array $filters): array
    {
        ['date_from' => $from, 'date_to' => $to] = $filters;

        $cacheKey = 'sav_performance_' . md5($from->toDateString() . $to->toDateString());

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($from, $to) {
            // Performance des techniciens
            $technicians = DB::table('sav_tickets')
                ->join('users', 'sav_tickets.assigned_to', '=', 'users.id')
                ->whereBetween('sav_tickets.created_at', [$from, $to])
                ->whereNotNull('sav_tickets.assigned_to')
                ->select(
                    'users.name as technician_name',
                    DB::raw('COUNT(*) as tickets'),
                    DB::raw('SUM(CASE WHEN sav_tickets.status = "completed" THEN 1 ELSE 0 END) as completed')
                )
                ->groupBy('sav_tickets.assigned_to', 'users.name')
                ->get()
                ->map(fn($row) => [
                    'technician_name' => $row->technician_name ?? '—',
                    'tickets' => (int) $row->tickets,
                    'completion_rate' => $row->tickets > 0 
                        ? round(($row->completed / $row->tickets) * 100) 
                        : 0,
                ]);

            // Temps moyen de réparation
            $avgRepairTime = (int) round(
                DB::table('interventions')
                    ->whereBetween('created_at', [$from, $to])
                    ->whereNotNull('duration_minutes')
                    ->avg('duration_minutes') ?? 0
            );

            return [
                'technicians' => $technicians,
                'avg_repair_time_minutes' => $avgRepairTime,
                'tickets_by_status' => $this->getTicketsByStatusCached(),
            ];
        });
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  CACHES GLOBAUX (avec fallback)
    // ─────────────────────────────────────────────────────────────────────────

    private function getTicketsByStatusCached(): Collection
    {
        return Cache::remember('tickets_by_status_v3', 300, function () {
            $results = DB::table('sav_tickets')
                ->select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get();
            
            if ($results->isEmpty()) {
                return collect(['pending' => 0, 'completed' => 0]);
            }
            
            return $results->mapWithKeys(fn($row) => [$row->status => (int) $row->count]);
        });
    }

    private function getAlertsCached(): array
    {
        return Cache::remember('alerts_data_v3', 300, function () {
            return [
                'low_stock_products' => Product::whereRaw('quantity <= alert_threshold')->count(),
                'low_stock_parts' => SparePart::whereRaw('quantity_in_stock <= min_stock_alert')->count(),
                'critical_tickets' => SavTicket::where('priority', 'critical')
                    ->whereNotIn('status', ['completed', 'restituted'])
                    ->count(),
                'expiring_warranty' => SavTicket::where('is_warranty', true)
                    ->whereDate('warranty_end_date', '>=', now())
                    ->whereDate('warranty_end_date', '<=', now()->addDays(30))
                    ->count(),
            ];
        });
    }
}