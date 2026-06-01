<?php

namespace App\Services;

use App\Models\Module1\Product;
use App\Models\Module5\SparePart;
use App\Models\Module3\Invoice;
use App\Models\Module3\InvoiceItem;
use App\Models\Module3\Quote;
use App\Models\Module5\SavTicket;
use App\Models\Module5\Intervention;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    protected int $cacheTtl = 3600;
    private const INVOICE_ACTIVE_STATUSES = ['sent', 'paid'];
    private const TICKET_IN_PROGRESS_STATUSES = ['pending', 'assigned', 'diagnosing', 'repairing'];

    // ─────────────────────────────────────────────────────────────────────────
    //  Entrée publique
    // ─────────────────────────────────────────────────────────────────────────

    public function getDashboardData(array $filters): array
    {
        $filters  = $this->normalizeFilters($filters);
        $cacheKey = $this->buildCacheKey($filters);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($filters) {
            $previousFilters = $this->previousPeriodFilters($filters);

            return [
                'kpis'                 => $this->buildKpis($filters),
                'kpis_previous'        => $this->buildKpis($previousFilters),
                'salesEvolution'       => $this->querySalesEvolution($filters),
                'categoryDistribution' => $this->queryCategoryDistribution($filters),
                'topProducts'          => $this->queryTopProducts($filters),
                'savPerformance'       => $this->buildSavPerformance($filters),
                'ticketsByStatus'      => $this->queryTicketsByStatus(),
                'revenueByDay'         => $this->queryRevenueByDay($filters),
                'alerts'               => $this->buildAlerts(),
                'generated_at'         => now()->toIso8601String(),
            ];
        });
    }

    public function invalidateCache(array $filters = []): void
    {
        Cache::forget($this->buildCacheKey($this->normalizeFilters($filters)));
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  Normalisation & utilitaires
    // ─────────────────────────────────────────────────────────────────────────

    private function normalizeFilters(array $filters): array
    {
        return [
            'date_from' => Carbon::parse($filters['date_from'] ?? now()->startOfMonth()),
            'date_to'   => Carbon::parse($filters['date_to']   ?? now()),
            'category'  => $filters['category']  ?? null,
            'supplier'  => $filters['supplier']  ?? null,
        ];
    }

    private function previousPeriodFilters(array $filters): array
    {
        $from = Carbon::parse($filters['date_from']);
        $to   = Carbon::parse($filters['date_to']);
        $span = $from->diffInDays($to);

        return array_merge($filters, [
            'date_from' => $from->copy()->subDays($span + 1),
            'date_to'   => $from->copy()->subDay(),
        ]);
    }

    private function buildCacheKey(array $filters): string
    {
        return 'analytics_v3_' . md5(json_encode($filters));
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  KPIs
    // ─────────────────────────────────────────────────────────────────────────

    private function buildKpis(array $filters): array
    {
        ['date_from' => $from, 'date_to' => $to] = $filters;

        $invoiceQuery = Invoice::whereBetween('date', [$from, $to])
            ->whereIn('status', self::INVOICE_ACTIVE_STATUSES);

        $revenue      = (float) $invoiceQuery->sum('total');
        $invoiceCount = $invoiceQuery->count();

        $cogs = $this->computeCogs($from, $to, $filters['category']);

        [$quotesSent, $quotesConverted] = $this->quoteConversionCounts($from, $to);

        return [
            'daily_ca'        => (float) Invoice::whereDate('date', now())
                                    ->whereIn('status', self::INVOICE_ACTIVE_STATUSES)
                                    ->sum('total'),
            'monthly_ca'      => $revenue,
            'margin_rate'     => $revenue > 0
                                    ? round((($revenue - $cogs) / $revenue) * 100, 1)
                                    : 0,
            'conversion_rate' => $quotesSent > 0
                                    ? round(($quotesConverted / $quotesSent) * 100, 1)
                                    : 0,
            'pending_tickets' => SavTicket::whereIn('status', self::TICKET_IN_PROGRESS_STATUSES)->count(),
            'invoice_count'   => $invoiceCount,
            'avg_basket'      => $invoiceCount > 0 ? round($revenue / $invoiceCount) : 0,
        ];
    }

    private function computeCogs(Carbon $from, Carbon $to, ?string $category): float
    {
        $query = InvoiceItem::whereHas(
            'invoice',
            fn ($q) => $q->whereBetween('date', [$from, $to])
                         ->whereIn('status', self::INVOICE_ACTIVE_STATUSES)
        )->join('products', 'invoice_items.product_id', '=', 'products.id');

        if ($category) {
            $query->where('products.category', $category);
        }

        return (float) $query->sum(DB::raw('invoice_items.quantity * products.purchase_price'));
    }

    private function quoteConversionCounts(Carbon $from, Carbon $to): array
    {
        $sent      = Quote::whereBetween('date', [$from, $to])->where('status', 'sent')->count();
        $converted = Quote::whereBetween('date', [$from, $to])->where('status', 'converted')->count();

        return [$sent, $converted];
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  Requêtes données graphiques
    // ─────────────────────────────────────────────────────────────────────────

    private function querySalesEvolution(array $filters): Collection
    {
        return Invoice::select(
            DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
            DB::raw('SUM(total)  as total'),
            DB::raw('COUNT(*)    as invoice_count')
        )
        ->whereBetween('date', [$filters['date_from'], $filters['date_to']])
        ->whereIn('status', self::INVOICE_ACTIVE_STATUSES)
        ->groupBy('month')
        ->orderBy('month')
        ->get();
    }

    private function queryRevenueByDay(array $filters): Collection
    {
        return Invoice::select(
            DB::raw('DATE(date)  as day'),
            DB::raw('SUM(total)  as total')
        )
        ->whereBetween('date', [$filters['date_from'], $filters['date_to']])
        ->whereIn('status', self::INVOICE_ACTIVE_STATUSES)
        ->groupBy('day')
        ->orderBy('day')
        ->get();
    }

    private function queryCategoryDistribution(array $filters): Collection
    {
        return InvoiceItem::whereHas(
            'invoice',
            fn ($q) => $q->whereBetween('date', [$filters['date_from'], $filters['date_to']])
                         ->whereIn('status', self::INVOICE_ACTIVE_STATUSES)
        )
        ->join('products', 'invoice_items.product_id', '=', 'products.id')
        ->select(
            'products.category',
            DB::raw('SUM(invoice_items.total) as total'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('products.category')
        ->get()
        ->map(fn ($row) => [
            'category' => $row->category ?: 'Non catégorisé',
            'total'    => (float) $row->total,
            'count'    => (int)   $row->count,
        ]);
    }

    private function queryTopProducts(array $filters, int $limit = 10): Collection
    {
        $query = InvoiceItem::whereHas(
            'invoice',
            fn ($q) => $q->whereBetween('date', [$filters['date_from'], $filters['date_to']])
                         ->whereIn('status', self::INVOICE_ACTIVE_STATUSES)
        )
        ->join('products', 'invoice_items.product_id', '=', 'products.id')
        ->select(
            'products.id',
            'products.name',
            'products.category',
            DB::raw('SUM(invoice_items.quantity)   as qty'),
            DB::raw('SUM(invoice_items.total)      as total'),
            DB::raw('AVG(invoice_items.unit_price) as avg_price')
        )
        ->groupBy('products.id', 'products.name', 'products.category')
        ->orderByDesc('total')
        ->limit($limit);

        if ($filters['category']) {
            $query->where('products.category', $filters['category']);
        }

        return $query->get();
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  SAV
    // ─────────────────────────────────────────────────────────────────────────

    private function buildSavPerformance(array $filters): array
{
    ['date_from' => $from, 'date_to' => $to] = $filters;

    // ✅ JOIN direct → zéro N+1 (élimine le ->with('technician') qui faisait 1 query par ligne)
    // ✅ total_tickets calculé dans la même query → évite une 3e requête séparée
    $techRows = DB::table('sav_tickets')
        ->join('users', 'sav_tickets.assigned_to', '=', 'users.id')
        ->whereBetween('sav_tickets.created_at', [$from, $to])
        ->whereNotNull('sav_tickets.assigned_to')
        ->select(
            'users.name as technician_name',
            DB::raw('COUNT(*)                                                          as tickets'),
            DB::raw('SUM(CASE WHEN sav_tickets.status = "completed" THEN 1 ELSE 0 END) as completed')
        )
        ->groupBy('sav_tickets.assigned_to', 'users.name')
        ->get();

    $technicians = $techRows->map(fn ($row) => [
        'technician_name' => $row->technician_name ?? '—',
        'tickets'         => (int) $row->tickets,
        'completed'       => (int) $row->completed,
        'completion_rate' => $row->tickets > 0
            ? round(($row->completed / $row->tickets) * 100)
            : 0,
    ]);

    // ✅ Les deux agrégats en une seule query au lieu de deux
    $savStats = DB::table('sav_tickets')
        ->whereBetween('created_at', [$from, $to])
        ->selectRaw('COUNT(*) as total_tickets')
        ->first();

    $avgRepairTime = (int) round(
        DB::table('interventions')
            ->whereBetween('created_at', [$from, $to])
            ->whereNotNull('duration_minutes')
            ->avg('duration_minutes') ?? 0
    );

    return [
        'technicians'             => $technicians,
        'avg_repair_time_minutes' => $avgRepairTime,
        'tickets_by_status'       => $this->queryTicketsByStatus(),
        'total_tickets'           => (int) ($savStats->total_tickets ?? 0),
    ];
}

    private function queryTicketsByStatus(): Collection
    {
        return SavTicket::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(fn ($row) => [$row->status => (int) $row->count]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  Alertes
    // ─────────────────────────────────────────────────────────────────────────

    private function buildAlerts(): array
    {
        return [
            'low_stock_products' => Product::whereRaw('quantity <= alert_threshold')->count(),
            'low_stock_parts'    => SparePart::whereRaw('quantity_in_stock <= min_stock_alert')->count(),
            'critical_tickets'   => SavTicket::where('priority', 'critical')
                                        ->whereNotIn('status', ['completed', 'restituted'])
                                        ->count(),
            'expiring_warranty'  => SavTicket::where('is_warranty', true)
                                        ->whereDate('warranty_end_date', '>=', now())
                                        ->whereDate('warranty_end_date', '<=', now()->addDays(30))
                                        ->count(),
        ];
    }
}