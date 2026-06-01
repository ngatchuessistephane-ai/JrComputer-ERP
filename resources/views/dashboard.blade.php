@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">

<style>
/* ============================================================
   DASHBOARD SENIOR EDITION - JR COMPUTER
   Largeur augmentée, bords plus fins
   ============================================================ */

.dashboard-container {
    --ds-g-primary: #1a7a3c;
    --ds-g-primary-light: #22a352;
    --ds-g-primary-dark: #0f5a2e;
    --ds-o-primary: #f07d00;
    --ds-o-primary-light: #ff9c2a;
    --ds-red: #ef4444;
    --ds-red-dark: #dc2626;
    --ds-amber: #f59e0b;
    --ds-blue: #3b82f6;
    
    /* Largeur maximale augmentée pour moins d'espace en bordure */
    zoom: 0.94;
    -moz-transform: scale(0.94);
    -moz-transform-origin: top left;
}

.dashboard-container * {
    font-size: inherit;
}

/* ========== TYPOGRAPHIE ========== */
.dashboard-container {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    color: var(--text-primary);
    max-width: 1800px;  /* Augmenté: 1600px -> 1800px */
    margin: 0 auto;
    padding: 0 0.5rem;  /* Réduction des marges latérales */
}

/* ========== HERO SECTION ========== */
.ds-hero {
    background: linear-gradient(135deg, var(--ds-g-primary) 0%, var(--ds-g-primary-dark) 100%);
    border-radius: 20px;
    padding: 1rem 1.5rem;
    margin-bottom: 1rem;
    position: relative;
    overflow: hidden;
}

.ds-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 60%;
    height: 200%;
    background: radial-gradient(ellipse, rgba(255,255,255,0.08) 0%, transparent 70%);
    pointer-events: none;
}

.ds-hero-content {
    position: relative;
    z-index: 2;
}

.ds-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(4px);
    padding: 0.2rem 0.75rem;
    border-radius: 40px;
    font-size: 0.65rem;
    font-weight: 500;
    color: rgba(255,255,255,0.9);
    margin-bottom: 0.5rem;
}

.ds-hero-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: white;
    margin-bottom: 0.2rem;
    letter-spacing: -0.3px;
}

.ds-hero-title span {
    background: linear-gradient(135deg, #fbbf24, #ff9c2a);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.ds-hero-sub {
    font-size: 0.75rem;
    color: rgba(255,255,255,0.7);
}

.ds-stats-date {
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(4px);
    border-radius: 14px;
    padding: 0.5rem 1rem;
    text-align: center;
    border: 1px solid rgba(255,255,255,0.1);
}

.ds-stat-day {
    font-size: 1.3rem;
    font-weight: 700;
    color: white;
    line-height: 1.2;
}

.ds-stat-month {
    font-size: 0.65rem;
    color: rgba(255,255,255,0.6);
    text-transform: uppercase;
}

/* ========== KPI CARDS ========== */
.ds-kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.875rem;
    margin-bottom: 1rem;
}

@media (max-width: 1000px) {
    .ds-kpi-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 500px) {
    .ds-kpi-grid { grid-template-columns: 1fr; }
}

.ds-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 18px;
    padding: 0.875rem 1rem;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.ds-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}

.ds-card-icon {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    width: 1.75rem;
    height: 1.75rem;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.ds-card-label {
    font-size: 0.65rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    margin-bottom: 0.375rem;
}

.ds-card-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.2;
    margin-bottom: 0.375rem;
}

.ds-card-trend {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.15rem 0.5rem;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
}

.ds-card-trend.up { background: rgba(26,122,60,0.1); color: var(--ds-g-primary); }
.ds-card-trend.down { background: rgba(239,68,68,0.1); color: var(--ds-red); }
.ds-card-trend.neutral { background: rgba(107,114,128,0.1); color: var(--text-muted); }

/* ========== FILTER BAR ========== */
.ds-filter-bar {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    gap: 0.75rem;
}

.ds-filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    flex: 1;
    min-width: 120px;
}

.ds-filter-group label {
    font-size: 0.6rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
}

.ds-filter-group input,
.ds-filter-group select {
    background: var(--bg-page);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 0.4rem 0.6rem;
    font-size: 0.75rem;
    color: var(--text-primary);
    transition: all 0.2s;
}

.ds-filter-group input:focus,
.ds-filter-group select:focus {
    outline: none;
    border-color: var(--ds-g-primary);
    box-shadow: 0 0 0 3px rgba(26,122,60,0.1);
}

.ds-period-buttons {
    display: flex;
    gap: 0.25rem;
    align-items: flex-end;
}

.ds-period-btn {
    background: var(--bg-page);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 0.4rem 0.8rem;
    font-size: 0.7rem;
    font-weight: 500;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.2s;
}

.ds-period-btn:hover {
    border-color: var(--ds-g-primary);
    color: var(--ds-g-primary);
}

.ds-period-btn.active {
    background: var(--ds-g-primary);
    border-color: var(--ds-g-primary);
    color: white;
}

.ds-btn-primary {
    background: linear-gradient(135deg, var(--ds-g-primary), var(--ds-g-primary-light));
    border: none;
    border-radius: 8px;
    padding: 0.4rem 1rem;
    font-size: 0.7rem;
    font-weight: 600;
    color: white;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}

.ds-btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(26,122,60,0.3);
}

.ds-btn-secondary {
    background: transparent;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 0.4rem 0.8rem;
    font-size: 0.7rem;
    font-weight: 500;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.2s;
}

.ds-btn-secondary:hover {
    border-color: var(--ds-g-primary);
    color: var(--ds-g-primary);
}

/* ========== CHARTS SECTION ========== */
.ds-charts-grid {
    display: grid;
    grid-template-columns: 3fr 2fr;
    gap: 0.875rem;
    margin-bottom: 1rem;
}

@media (max-width: 900px) {
    .ds-charts-grid { grid-template-columns: 1fr; }
}

.ds-chart-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 18px;
    padding: 1rem;
}

.ds-chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.ds-chart-title {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--text-primary);
}

.ds-chart-icon {
    width: 26px;
    height: 26px;
    border-radius: 8px;
    background: rgba(26,122,60,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--ds-g-primary);
    font-size: 0.85rem;
}

.ds-granularity-select {
    background: var(--bg-page);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 0.3rem 0.6rem;
    font-size: 0.65rem;
    font-weight: 500;
    color: var(--text-primary);
    cursor: pointer;
}

/* ========== TABLES SECTION ========== */
.ds-tables-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.875rem;
    margin-bottom: 1rem;
}

@media (max-width: 800px) {
    .ds-tables-grid { grid-template-columns: 1fr; }
}

.ds-table-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 18px;
    overflow: hidden;
}

.ds-table-header {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.ds-table-title {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-weight: 600;
    font-size: 0.8rem;
    color: var(--text-primary);
}

.ds-table {
    width: 100%;
    border-collapse: collapse;
}

.ds-table th {
    padding: 0.5rem 0.875rem;
    font-size: 0.6rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    background: var(--bg-page);
    border-bottom: 1px solid var(--border-color);
    text-align: left;
}

.ds-table td {
    padding: 0.6rem 0.875rem;
    font-size: 0.7rem;
    color: var(--text-primary);
    border-bottom: 1px solid var(--border-color);
}

.ds-table tr:last-child td { border-bottom: none; }

.ds-rank-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    border-radius: 6px;
    font-size: 0.65rem;
    font-weight: 700;
}

.ds-rank-1 { background: #fef3c7; color: #b45309; }
.ds-rank-2 { background: #f1f5f9; color: #64748b; }
.ds-rank-3 { background: #fff7ed; color: #c2410c; }

/* ========== ALERTS SECTION ========== */
.ds-alerts-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.75rem;
    transform: scale(0.85);
    transform-origin: top left;
    margin-bottom: -0.75rem;
    width: 117.65%;
}

@media (max-width: 900px) {
    .ds-alerts-grid { 
        grid-template-columns: repeat(2, 1fr);
        width: 100%;
        transform: scale(0.9);
        margin-bottom: 0;
    }
}
@media (max-width: 500px) {
    .ds-alerts-grid { 
        grid-template-columns: 1fr;
        transform: scale(0.95);
    }
}

.ds-alert-card {
    background: var(--bg-card);
    border-radius: 14px;
    padding: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

.ds-alert-card:hover {
    transform: translateY(-2px);
}

/* Niveaux d'urgence */
.ds-alert-critical {
    border-left: 3px solid #dc2626;
    background: linear-gradient(135deg, var(--bg-card) 0%, rgba(220,38,38,0.05) 100%);
}
.ds-alert-critical .ds-alert-icon { background: #fee2e2; color: #dc2626; }
.ds-alert-critical .ds-alert-value { color: #dc2626; }

.ds-alert-high {
    border-left: 3px solid #f07d00;
    background: linear-gradient(135deg, var(--bg-card) 0%, rgba(240,125,0,0.05) 100%);
}
.ds-alert-high .ds-alert-icon { background: #fff4e6; color: #f07d00; }
.ds-alert-high .ds-alert-value { color: #f07d00; }

.ds-alert-medium {
    border-left: 3px solid #f59e0b;
    background: linear-gradient(135deg, var(--bg-card) 0%, rgba(245,158,11,0.05) 100%);
}
.ds-alert-medium .ds-alert-icon { background: #fef3c7; color: #f59e0b; }
.ds-alert-medium .ds-alert-value { color: #f59e0b; }

.ds-alert-low {
    border-left: 3px solid #3b82f6;
    background: linear-gradient(135deg, var(--bg-card) 0%, rgba(59,130,246,0.05) 100%);
}
.ds-alert-low .ds-alert-icon { background: #dbeafe; color: #3b82f6; }
.ds-alert-low .ds-alert-value { color: #3b82f6; }

.ds-alert-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.ds-alert-content {
    flex: 1;
}

.ds-alert-value {
    font-size: 1.2rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 0.15rem;
}

.ds-alert-label {
    font-size: 0.6rem;
    font-weight: 500;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.ds-alert-sublabel {
    font-size: 0.55rem;
    color: var(--text-muted);
    margin-top: 0.2rem;
}

.ds-alert-badge {
    position: absolute;
    top: 0.35rem;
    right: 0.35rem;
    font-size: 0.55rem;
    font-weight: 700;
    padding: 0.15rem 0.4rem;
    border-radius: 20px;
    text-transform: uppercase;
}

.ds-alert-critical .ds-alert-badge { background: #dc2626; color: white; }
.ds-alert-high .ds-alert-badge { background: #f07d00; color: white; }
.ds-alert-medium .ds-alert-badge { background: #f59e0b; color: white; }
.ds-alert-low .ds-alert-badge { background: #3b82f6; color: white; }

/* ========== LOADER ========== */
.ds-loader {
    display: inline-flex;
    gap: 0.2rem;
    align-items: center;
}

.ds-loader span {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: var(--ds-g-primary);
    animation: ds-bounce 1.2s infinite;
}

.ds-loader span:nth-child(2) { animation-delay: 0.15s; }
.ds-loader span:nth-child(3) { animation-delay: 0.3s; }

@keyframes ds-bounce {
    0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
    40% { transform: scale(1); opacity: 1; }
}

.empty-state {
    text-align: center;
    padding: 1rem;
    color: var(--text-muted);
    font-size: 0.7rem;
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.ds-card, .ds-chart-card, .ds-table-card, .ds-alert-card {
    animation: fadeInUp 0.4s ease forwards;
}

.ds-card:nth-child(1) { animation-delay: 0.05s; }
.ds-card:nth-child(2) { animation-delay: 0.1s; }
.ds-card:nth-child(3) { animation-delay: 0.15s; }
.ds-card:nth-child(4) { animation-delay: 0.2s; }
</style>

<div class="dashboard-container">
    <!-- HERO SECTION -->
    <div class="ds-hero">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="ds-hero-content">
                <div class="ds-hero-badge">
                    <i class="bi bi-graph-up"></i>
                    <span>Tableau de bord en temps réel</span>
                </div>
                <h1 class="ds-hero-title">
                    Tableau de bord <span>JR Computer</span>
                </h1>
                <p class="ds-hero-sub">Analyse complète de vos performances commerciales et opérationnelles</p>
            </div>
            <div class="ds-stats-date">
                <div class="ds-stat-day" id="heroDay">--</div>
                <div class="ds-stat-month" id="heroMonth">---</div>
            </div>
        </div>
    </div>

    <!-- FILTER BAR (sans le bouton Bons de commande) -->
    <div class="ds-filter-bar">
        <div class="ds-filter-group">
            <label><i class="bi bi-calendar3 me-1"></i> Date début</label>
            <input type="date" id="filterDateFrom" value="{{ now()->startOfMonth()->toDateString() }}">
        </div>
        <div class="ds-filter-group">
            <label><i class="bi bi-calendar3 me-1"></i> Date fin</label>
            <input type="date" id="filterDateTo" value="{{ now()->toDateString() }}">
        </div>
        <div class="ds-filter-group">
            <label><i class="bi bi-tag me-1"></i> Catégorie</label>
            <input type="text" id="filterCategory" placeholder="Toutes les catégories">
        </div>
        <div class="ds-filter-group">
            <label><i class="bi bi-clock-history me-1"></i> Période rapide</label>
            <div class="ds-period-buttons">
                <button class="ds-period-btn" data-period="week">7j</button>
                <button class="ds-period-btn active" data-period="month">Mois</button>
                <button class="ds-period-btn" data-period="quarter">Trimestre</button>
                <button class="ds-period-btn" data-period="year">Année</button>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="ds-btn-primary" id="applyFiltersBtn">
                <i class="bi bi-funnel"></i> Appliquer
            </button>
            <button class="ds-btn-secondary" id="resetFiltersBtn">
                <i class="bi bi-arrow-repeat"></i> Réinitialiser
            </button>
        </div>
    </div>

    <!-- KPI CARDS -->
    <div class="ds-kpi-grid">
        <div class="ds-card">
            <div class="ds-card-icon" style="background: rgba(26,122,60,0.1); color: #1a7a3c;">
                <i class="bi bi-wallet2"></i>
            </div>
            <div class="ds-card-label">CA Journalier</div>
            <div class="ds-card-value" id="kpiDailyCa">
                <div class="ds-loader"><span></span><span></span><span></span></div>
            </div>
            <span class="ds-card-trend up"><i class="bi bi-arrow-up-short"></i> vs hier</span>
        </div>
        <div class="ds-card">
            <div class="ds-card-icon" style="background: rgba(240,125,0,0.1); color: #f07d00;">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="ds-card-label">CA Période</div>
            <div class="ds-card-value" id="kpiPeriodCa">
                <div class="ds-loader"><span></span><span></span><span></span></div>
            </div>
            <span class="ds-card-trend neutral"><i class="bi bi-calendar3"></i> cumul</span>
        </div>
        <div class="ds-card">
            <div class="ds-card-icon" style="background: rgba(8,145,178,0.1); color: #0891b2;">
                <i class="bi bi-pie-chart"></i>
            </div>
            <div class="ds-card-label">Marge brute</div>
            <div class="ds-card-value" id="kpiMargin">
                <div class="ds-loader"><span></span><span></span><span></span></div>
            </div>
            <span class="ds-card-trend up"><i class="bi bi-arrow-up-short"></i> rentable</span>
        </div>
        <div class="ds-card">
            <div class="ds-card-icon" style="background: rgba(239,68,68,0.1); color: #ef4444;">
                <i class="bi bi-headset"></i>
            </div>
            <div class="ds-card-label">SAV en cours</div>
            <div class="ds-card-value" id="kpiSavPending">
                <div class="ds-loader"><span></span><span></span><span></span></div>
            </div>
            <span class="ds-card-trend down"><i class="bi bi-clock"></i> à traiter</span>
        </div>
    </div>

    <!-- CHARTS SECTION -->
    <div class="ds-charts-grid">
        <div class="ds-chart-card">
            <div class="ds-chart-header">
                <div class="ds-chart-title">
                    <div class="ds-chart-icon"><i class="bi bi-graph-up"></i></div>
                    <span>Évolution du chiffre d'affaires</span>
                </div>
                <select id="granularitySelect" class="ds-granularity-select">
                    <option value="day">📅 Par jour</option>
                    <option value="week" selected>📊 Par semaine</option>
                    <option value="month">📈 Par mois</option>
                    <option value="quarter">📉 Par trimestre</option>
                </select>
            </div>
            <div style="position: relative; height: 280px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="ds-chart-card">
            <div class="ds-chart-header">
                <div class="ds-chart-title">
                    <div class="ds-chart-icon" style="background: rgba(240,125,0,0.1); color: #f07d00;"><i class="bi bi-pie-chart"></i></div>
                    <span>Répartition par catégorie</span>
                </div>
            </div>
            <div style="position: relative; height: 280px;">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- TABLES SECTION -->
    <div class="ds-tables-grid">
        <div class="ds-table-card">
            <div class="ds-table-header">
                <div class="ds-table-title">
                    <i class="bi bi-trophy-fill" style="color: #f07d00;"></i>
                    <span>Top 10 des produits</span>
                </div>
                <span class="ds-card-trend neutral" style="font-size: 0.55rem;">💰 CA généré</span>
            </div>
            <div style="overflow-x: auto;">
                <table class="ds-table" id="topProductsTable">
                    <thead>
                        <tr><th style="width: 35px;">#</th><th>Produit</th><th>Catégorie</th><th style="text-align: right;">Qté</th><th style="text-align: right;">CA</th></tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="5" class="empty-state"><div class="ds-loader"><span></span><span></span><span></span></div> Chargement...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="ds-table-card">
            <div class="ds-table-header">
                <div class="ds-table-title">
                    <i class="bi bi-tools" style="color: #0891b2;"></i>
                    <span>Performance SAV</span>
                </div>
                <span class="ds-card-trend neutral" id="savAvgTime" style="font-size: 0.55rem;">⏱️ Temps moyen: -- min</span>
            </div>
            <div style="overflow-x: auto;">
                <table class="ds-table" id="savTable">
                    <thead><tr><th>Technicien</th><th style="text-align: right;">Tickets</th><th>Taux complétion</th></tr></thead>
                    <tbody>
                        <tr><td colspan="3" class="empty-state"><div class="ds-loader"><span></span><span></span><span></span></div> Chargement...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ALERTS SECTION - Avec bouton Bons de commande intégré dans la carte "Stocks bas" -->
    <div class="ds-alerts-grid" id="alertsGrid">
        <div class="ds-loader" style="justify-content: center; padding: 0.75rem;"><span></span><span></span><span></span> Chargement des alertes...</div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
    'use strict';

    const $ = id => document.getElementById(id);
    let revenueChart = null;
    let categoryChart = null;
    let currentGranularity = 'week';
    
    let currentFilters = {
        date_from: $('filterDateFrom')?.value || '',
        date_to: $('filterDateTo')?.value || '',
        category: $('filterCategory')?.value || '',
        granularity: currentGranularity
    };

    const formatCurrency = (value) => {
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'XAF',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(value).replace('XAF', 'FCFA');
    };

    const formatNumber = (value) => {
        return new Intl.NumberFormat('fr-FR').format(value);
    };

    const getChartColors = () => {
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        return {
            gridColor: isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)',
            textColor: isDark ? '#9ca3af' : '#6b7280',
            tooltipBg: isDark ? '#1f2937' : '#ffffff',
        };
    };

    const buildRevenueChart = (labels, data) => {
        const ctx = $('revenueChart')?.getContext('2d');
        if (!ctx) return;
        
        const colors = getChartColors();
        const gradient = ctx.createLinearGradient(0, 0, 0, 280);
        gradient.addColorStop(0, 'rgba(26,122,60,0.35)');
        gradient.addColorStop(0.4, 'rgba(26,122,60,0.15)');
        gradient.addColorStop(1, 'rgba(26,122,60,0.02)');

        if (revenueChart) revenueChart.destroy();

        revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Chiffre d\'affaires',
                    data: data,
                    borderColor: '#1a7a3c',
                    backgroundColor: gradient,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3.5,
                    pointBackgroundColor: '#1a7a3c',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 1.5,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#f07d00',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 2,
                    cubicInterpolationMode: 'monotone'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: colors.tooltipBg,
                        titleColor: '#6b7280',
                        bodyColor: '#1a7a3c',
                        bodyFont: { weight: 'bold', size: 11, family: 'Inter' },
                        titleFont: { size: 10, family: 'Inter' },
                        padding: 8,
                        cornerRadius: 6,
                        displayColors: false,
                        callbacks: { label: (ctx) => `💰 ${formatCurrency(ctx.raw)}` }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: colors.textColor, font: { size: 9, family: 'Inter' }, maxRotation: 35, autoSkip: true, maxTicksLimit: 8 },
                        border: { display: false }
                    },
                    y: {
                        grid: { color: colors.gridColor, drawBorder: false },
                        ticks: {
                            color: colors.textColor,
                            font: { size: 9, family: 'Inter' },
                            callback: (value) => {
                                if (value >= 1_000_000) return (value / 1_000_000).toFixed(1) + 'M FCFA';
                                if (value >= 1_000) return (value / 1_000).toFixed(0) + 'k';
                                return value;
                            }
                        },
                        border: { display: false }
                    }
                },
                elements: { line: { borderJoin: 'round' } }
            }
        });
    };

    const buildCategoryChart = (labels, data) => {
        const ctx = $('categoryChart')?.getContext('2d');
        if (!ctx) return;
        const colors = getChartColors();
        
        if (categoryChart) categoryChart.destroy();

        categoryChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: ['#1a7a3c', '#f07d00', '#0891b2', '#d97706', '#6366f1', '#ec4899', '#84cc16', '#14b8a6'],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { position: 'bottom', labels: { color: colors.textColor, font: { size: 9, family: 'Inter' }, padding: 8, boxWidth: 8, borderRadius: 3 } },
                    tooltip: {
                        backgroundColor: colors.tooltipBg,
                        bodyColor: '#374151',
                        bodyFont: { size: 10, family: 'Inter' },
                        callbacks: { label: (ctx) => ` ${formatCurrency(ctx.raw)} (${ctx.parsed}%)` }
                    }
                }
            }
        });
    };

    const renderTopProducts = (products) => {
        const tbody = document.querySelector('#topProductsTable tbody');
        if (!tbody) return;
        
        if (!products || products.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="empty-state">📭 Aucune donnée disponible</td></tr>';
            return;
        }
        
        tbody.innerHTML = products.slice(0, 10).map((p, i) => {
            let rankClass = i === 0 ? 'ds-rank-1' : i === 1 ? 'ds-rank-2' : i === 2 ? 'ds-rank-3' : '';
            return `
                <tr>
                    <td><span class="ds-rank-badge ${rankClass}">${i+1}</span></td>
                    <td><strong style="font-size: 0.7rem;">${escapeHtml(p.name)}</strong></td>
                    <td><span style="font-size: 0.65rem; color: var(--text-muted);">${escapeHtml(p.category || '—')}</span></td>
                    <td style="text-align: right;">${formatNumber(p.qty)}</td>
                    <td style="text-align: right; color: #1a7a3c; font-weight: 600;">${formatCurrency(p.total)}</td>
                </tr>
            `;
        }).join('');
    };

    const renderSavTable = (technicians, avgTime) => {
        const tbody = document.querySelector('#savTable tbody');
        const avgTimeSpan = $('savAvgTime');
        
        if (avgTimeSpan) avgTimeSpan.innerHTML = `⏱️ Temps moyen: ${avgTime || 0} min`;
        if (!tbody) return;
        
        if (!technicians || technicians.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="empty-state">👨‍🔧 Aucun technicien actif</td></tr>';
            return;
        }
        
        tbody.innerHTML = technicians.map(t => {
            const rate = t.completion_rate || 0;
            const rateColor = rate >= 75 ? '#1a7a3c' : rate >= 50 ? '#f07d00' : '#dc2626';
            return `
                <tr>
                    <td><strong style="font-size: 0.7rem;">${escapeHtml(t.technician_name)}</strong></td>
                    <td style="text-align: right;">${formatNumber(t.tickets)}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.4rem;">
                            <div style="flex: 1; height: 4px; background: var(--border-color); border-radius: 99px;">
                                <div style="width: ${rate}%; height: 4px; background: ${rateColor}; border-radius: 99px; transition: width 0.5s ease;"></div>
                            </div>
                            <span style="font-size: 0.65rem; font-weight: 600; color: ${rateColor};">${rate}%</span>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    };

    // Rendu des alertes avec le bouton Bons de commande intégré dans la carte "Stocks bas"
    const renderAlerts = (alerts) => {
        const alertsGrid = $('alertsGrid');
        if (!alertsGrid) return;
        
        const valueLowStock = alerts.low_stock_products || 0;
        const valueLowParts = alerts.low_stock_parts || 0;
        const valueCriticalTickets = alerts.critical_tickets || 0;
        const valueExpiringWarranty = alerts.expiring_warranty || 0;
        
        // Carte 1: Stocks bas - AVEC BOUTON BONS DE COMMANDE
        const lowStockCard = `
            <div class="ds-alert-card ds-alert-high" onclick="window.location.href='{{ route('module2.purchase-orders.index') }}'" style="cursor: pointer;">
                ${valueLowStock > 5 ? '<span class="ds-alert-badge">URGENT</span>' : ''}
                <div class="ds-alert-icon"><i class="bi bi-box-seam"></i></div>
                <div class="ds-alert-content">
                    <div class="ds-alert-value">${formatNumber(valueLowStock)}</div>
                    <div class="ds-alert-label">Stocks bas</div>
                    <div class="ds-alert-sublabel">Cliquez pour commander ➜</div>
                </div>
            </div>
        `;
        
        // Carte 2: Pièces critiques
        const partsCard = `
            <div class="ds-alert-card ds-alert-critical">
                ${valueLowParts > 3 ? '<span class="ds-alert-badge">CRITIQUE</span>' : ''}
                <div class="ds-alert-icon"><i class="bi bi-puzzle"></i></div>
                <div class="ds-alert-content">
                    <div class="ds-alert-value">${formatNumber(valueLowParts)}</div>
                    <div class="ds-alert-label">Pièces critiques</div>
                    <div class="ds-alert-sublabel">Réapprovisionnement urgent</div>
                </div>
            </div>
        `;
        
        // Carte 3: Tickets critiques SAV
        const ticketsCard = `
            <div class="ds-alert-card ds-alert-critical">
                ${valueCriticalTickets > 0 ? '<span class="ds-alert-badge">DÉLAI</span>' : ''}
                <div class="ds-alert-icon"><i class="bi bi-exclamation-triangle"></i></div>
                <div class="ds-alert-content">
                    <div class="ds-alert-value">${formatNumber(valueCriticalTickets)}</div>
                    <div class="ds-alert-label">Tickets critiques</div>
                    <div class="ds-alert-sublabel">Délai dépassé</div>
                </div>
            </div>
        `;
        
        // Carte 4: Garanties expirant
        const warrantyCard = `
            <div class="ds-alert-card ds-alert-medium">
                ${valueExpiringWarranty > 5 ? '<span class="ds-alert-badge">ATTENTION</span>' : ''}
                <div class="ds-alert-icon"><i class="bi bi-shield-shaded"></i></div>
                <div class="ds-alert-content">
                    <div class="ds-alert-value">${formatNumber(valueExpiringWarranty)}</div>
                    <div class="ds-alert-label">Garanties expirant</div>
                    <div class="ds-alert-sublabel">30 jours restants</div>
                </div>
            </div>
        `;
        
        alertsGrid.innerHTML = lowStockCard + partsCard + ticketsCard + warrantyCard;
    };

    const escapeHtml = (str) => {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    };

    const updateHeroDate = () => {
        const today = new Date();
        const heroDay = $('heroDay');
        const heroMonth = $('heroMonth');
        if (heroDay) heroDay.textContent = today.toLocaleDateString('fr-FR', { day: '2-digit' });
        if (heroMonth) heroMonth.textContent = today.toLocaleDateString('fr-FR', { month: 'short', year: 'numeric' }).toUpperCase();
    };

    const loadDashboardData = async () => {
        const loadingElements = ['kpiDailyCa', 'kpiPeriodCa', 'kpiMargin', 'kpiSavPending'];
        loadingElements.forEach(id => {
            const el = $(id);
            if (el) el.innerHTML = '<div class="ds-loader"><span></span><span></span><span></span></div>';
        });
        
        try {
            const params = new URLSearchParams(currentFilters);
           const response = await fetch(`/api/analytics/data?${params.toString()}`);
            
            if (!response.ok) throw new Error('Erreur réseau');
            
            const data = await response.json();
            
            const kpiDailyCa = $('kpiDailyCa');
            const kpiPeriodCa = $('kpiPeriodCa');
            const kpiMargin = $('kpiMargin');
            const kpiSavPending = $('kpiSavPending');
            
            if (kpiDailyCa) kpiDailyCa.textContent = formatCurrency(data.kpis?.daily_ca || 0);
            if (kpiPeriodCa) kpiPeriodCa.textContent = formatCurrency(data.kpis?.monthly_ca || 0);
            if (kpiMargin) kpiMargin.textContent = (data.kpis?.margin_rate || 0) + '%';
            if (kpiSavPending) kpiSavPending.textContent = formatNumber(data.kpis?.pending_tickets || 0);
            
            if (data.salesEvolution && data.salesEvolution.length > 0) {
                buildRevenueChart(
                    data.salesEvolution.map(s => s.label || s.period),
                    data.salesEvolution.map(s => s.total)
                );
            } else {
                buildRevenueChart(['Aucune donnée'], [0]);
            }
            
            if (data.categoryDistribution && data.categoryDistribution.length > 0) {
                buildCategoryChart(
                    data.categoryDistribution.map(c => c.category || 'Général'),
                    data.categoryDistribution.map(c => c.total)
                );
            } else {
                buildCategoryChart(['Aucune donnée'], [0]);
            }
            
            renderTopProducts(data.topProducts || []);
            renderSavTable(data.savPerformance?.technicians || [], data.savPerformance?.avg_repair_time_minutes || 0);
            renderAlerts(data.alerts || {});
            
        } catch (error) {
            console.error('Erreur chargement dashboard:', error);
            const errorElements = ['kpiDailyCa', 'kpiPeriodCa', 'kpiMargin', 'kpiSavPending'];
            errorElements.forEach(id => {
                const el = $(id);
                if (el) el.innerHTML = '<span style="color: #ef4444;">⚠️ Erreur</span>';
            });
        }
    };

    const applyFilters = () => {
        currentFilters = {
            date_from: $('filterDateFrom')?.value || '',
            date_to: $('filterDateTo')?.value || '',
            category: $('filterCategory')?.value || '',
            granularity: currentGranularity
        };
        loadDashboardData();
    };

    const resetFilters = () => {
        const today = new Date();
        const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        
        const filterDateFrom = $('filterDateFrom');
        const filterDateTo = $('filterDateTo');
        const filterCategory = $('filterCategory');
        
        if (filterDateFrom) filterDateFrom.value = firstDayOfMonth.toISOString().slice(0, 10);
        if (filterDateTo) filterDateTo.value = today.toISOString().slice(0, 10);
        if (filterCategory) filterCategory.value = '';
        
        document.querySelectorAll('.ds-period-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.period === 'month') btn.classList.add('active');
        });
        
        currentGranularity = 'week';
        const granularitySelect = $('granularitySelect');
        if (granularitySelect) granularitySelect.value = 'week';
        
        applyFilters();
    };

    const initEventListeners = () => {
        const applyBtn = $('applyFiltersBtn');
        const resetBtn = $('resetFiltersBtn');
        
        if (applyBtn) applyBtn.addEventListener('click', applyFilters);
        if (resetBtn) resetBtn.addEventListener('click', resetFilters);
        
        document.querySelectorAll('.ds-period-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const period = this.dataset.period;
                const today = new Date();
                let from = new Date();
                
                switch(period) {
                    case 'week': from.setDate(today.getDate() - 6); break;
                    case 'quarter': from.setMonth(today.getMonth() - 3); break;
                    case 'year': from = new Date(today.getFullYear(), 0, 1); break;
                    default: from = new Date(today.getFullYear(), today.getMonth(), 1);
                }
                
                const filterDateFrom = $('filterDateFrom');
                const filterDateTo = $('filterDateTo');
                if (filterDateFrom) filterDateFrom.value = from.toISOString().slice(0, 10);
                if (filterDateTo) filterDateTo.value = today.toISOString().slice(0, 10);
                
                document.querySelectorAll('.ds-period-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                applyFilters();
            });
        });
        
        const granularitySelect = $('granularitySelect');
        if (granularitySelect) {
            granularitySelect.addEventListener('change', function() {
                currentGranularity = this.value;
                currentFilters.granularity = currentGranularity;
                loadDashboardData();
            });
        }
    };

    const watchThemeChanges = () => {
        const observer = new MutationObserver(() => {
            if (revenueChart || categoryChart) loadDashboardData();
        });
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
    };

    updateHeroDate();
    initEventListeners();
    watchThemeChanges();
    loadDashboardData();
})();
</script>
@endsection