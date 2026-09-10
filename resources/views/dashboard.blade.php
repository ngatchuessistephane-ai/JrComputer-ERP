@extends('layouts.app')

@section('content')
<style>
/* ════════════════════════════════════════════════════════════
   JR COMPUTER · DASHBOARD v11 — FILTRES PREMIUM
   Aligné sur app.blade.php (Plus Jakarta Sans, variables CSS)
════════════════════════════════════════════════════════════ */

.ds {
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
    font-size: 13px;
    font-weight: 400;
    line-height: 1.5;
    color: var(--text-primary);
    max-width: 1600px;
    margin: 0 auto;
    zoom: 0.96;
}

/* ─── HEADER ─── */
.ds-header {
    display: flex; align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 16px; flex-wrap: wrap; gap: 12px;
}
.ds-title {
    font-size: 22px; font-weight: 800;
    color: var(--text-primary); margin: 0 0 2px;
    letter-spacing: -0.4px;
}
.ds-title span {
    background: linear-gradient(135deg, var(--brand-green), var(--brand-green-light));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
}
.ds-subtitle { font-size: 12px; color: var(--text-muted); margin: 0; }

.ds-date-chip {
    display: flex; align-items: center; gap: 8px;
    padding: 6px 14px; background: var(--bg-card);
    border: 1px solid var(--border-color); border-radius: 18px;
    font-size: 11.5px; font-weight: 600; color: var(--text-secondary);
    white-space: nowrap;
}
.ds-live-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: #22c55e; animation: live-pulse 2s ease-in-out infinite;
}
@keyframes live-pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(.8)} }

/* ══════════════════════════════════════════════════════
   BARRE DE FILTRES — REDESIGN STARTUP PREMIUM
══════════════════════════════════════════════════════ */
.ds-filterbar {
    background: var(--bg-card);
    border: 1.5px solid var(--border-color);
    border-radius: 14px;
    padding: 0;
    margin-bottom: 16px;
    overflow: hidden;
    transition: border-color .18s, box-shadow .18s;
}
.ds-filterbar:focus-within {
    border-color: var(--brand-green);
    box-shadow: 0 0 0 3px rgba(26,122,60,.08);
}

/* Ligne 1 : Période + actions */
.ds-fb-row1 {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 8px 14px;
    border-bottom: 1px solid var(--border-color);
    gap: 10px; flex-wrap: wrap;
    background: var(--bg-page);
}

.ds-fb-label {
    font-size: 10px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .5px;
    color: var(--text-muted); white-space: nowrap;
    display: flex; align-items: center; gap: 4px;
}

/* Sélecteur de période */
.ds-period-wrap {
    display: flex; align-items: center;
    background: var(--bg-card); border: 1px solid var(--border-color);
    border-radius: 8px; padding: 2px; gap: 2px;
}
.ds-period-btn {
    padding: 4px 11px; font-size: 11px; font-weight: 600;
    border-radius: 6px; background: transparent; border: none;
    cursor: pointer; color: var(--text-muted); font-family: inherit;
    transition: all .18s; white-space: nowrap;
}
.ds-period-btn:hover { background: var(--bg-hover); color: var(--text-primary); }
.ds-period-btn.active {
    background: linear-gradient(135deg, var(--brand-green), var(--brand-green-light));
    color: #fff;
    box-shadow: 0 2px 6px rgba(26,122,60,.22);
}

/* Sélecteur dates custom */
.ds-date-range {
    display: flex; align-items: center; gap: 6px;
}
.ds-date-sep { font-size: 10px; color: var(--text-muted); font-weight: 600; }
.ds-date-input {
    padding: 4px 8px; font-size: 11px; font-family: inherit;
    border: 1px solid var(--border-color); border-radius: 6px;
    background: var(--bg-card); color: var(--text-primary);
    outline: none; transition: border-color .15s;
    width: 115px;
}
.ds-date-input:focus { border-color: var(--brand-green); }

/* Actions */
.ds-fb-actions { display: flex; align-items: center; gap: 4px; }
.ds-fb-btn {
    height: 28px; padding: 0 12px; border-radius: 7px;
    border: 1px solid var(--border-color); background: var(--bg-card);
    color: var(--text-secondary); font-size: 11px; font-weight: 600;
    font-family: inherit; cursor: pointer;
    display: inline-flex; align-items: center; gap: 5px;
    transition: all .18s; white-space: nowrap;
}
.ds-fb-btn:hover { border-color: var(--brand-green); color: var(--brand-green); background: var(--brand-green-xlight); }
.ds-fb-btn.primary {
    background: linear-gradient(135deg, var(--brand-green), var(--brand-green-light));
    color: #fff; border-color: transparent;
    box-shadow: 0 3px 8px rgba(26,122,60,.25);
}
.ds-fb-btn.primary:hover { transform: translateY(-1px); box-shadow: 0 5px 12px rgba(26,122,60,.32); }
.ds-fb-btn.danger:hover { border-color: #dc2626; color: #dc2626; background: rgba(220,38,38,.07); }

.ds-fb-iconbtn {
    width: 28px; height: 28px; border-radius: 7px;
    border: 1px solid var(--border-color); background: var(--bg-card);
    color: var(--text-secondary); cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 13px; transition: all .18s;
}
.ds-fb-iconbtn:hover { border-color: var(--brand-green); color: var(--brand-green); background: var(--brand-green-xlight); }

/* Ligne 2 : Catégorie + Tags actifs */
.ds-fb-row2 {
    display: flex; align-items: center;
    padding: 8px 14px; gap: 10px; flex-wrap: wrap;
}

/* Chips catégorie */
.ds-cat-chips {
    display: flex; align-items: center; gap: 5px; flex-wrap: wrap; flex: 1;
}
.ds-cat-chip {
    padding: 4px 11px; font-size: 11px; font-weight: 600;
    border-radius: 18px; border: 1.5px solid var(--border-color);
    background: var(--bg-page); cursor: pointer; color: var(--text-muted);
    font-family: inherit; transition: all .15s; white-space: nowrap;
    display: inline-flex; align-items: center; gap: 4px;
}
.ds-cat-chip:hover { border-color: var(--brand-green); color: var(--brand-green); background: var(--brand-green-xlight); }
.ds-cat-chip.active {
    background: var(--brand-green); color: #fff;
    border-color: var(--brand-green);
    box-shadow: 0 2px 6px rgba(26,122,60,.2);
}
.ds-cat-chip .chip-dot {
    width: 5px; height: 5px; border-radius: 50%;
    background: currentColor; flex-shrink: 0;
}

/* Tags filtres actifs */
.ds-active-filters {
    display: flex; align-items: center; gap: 5px; flex-wrap: wrap;
}
.ds-active-tag {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 2px 8px; font-size: 10.5px; font-weight: 600;
    background: var(--brand-green-xlight); color: var(--brand-green);
    border: 1px solid rgba(26,122,60,.2); border-radius: 18px;
}
.ds-active-tag button {
    width: 13px; height: 13px; border-radius: 50%; border: none;
    background: rgba(26,122,60,.2); color: var(--brand-green);
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    font-size: 9px; line-height: 1; padding: 0; font-family: inherit;
    transition: background .12s;
}
.ds-active-tag button:hover { background: var(--brand-green); color: #fff; }

/* Résumé filtre actif */
.ds-filter-summary {
    display: flex; align-items: center; gap: 6px; margin-left: auto;
    font-size: 10.5px; color: var(--text-muted); white-space: nowrap;
}
.ds-filter-summary strong { color: var(--text-primary); font-weight: 700; }

/* Séparateur vertical */
.ds-fb-sep {
    width: 1px; height: 18px; background: var(--border-color); flex-shrink: 0;
}

/* ─── KPI CARDS ─── (Hauteur réduite) */
.ds-kpi-grid {
    display: grid; grid-template-columns: repeat(4, 1fr);
    gap: 10px; margin-bottom: 14px;
}
@media (max-width: 900px) { .ds-kpi-grid { grid-template-columns: repeat(2, 1fr); } }

.ds-kpi-card {
    background: var(--bg-card); border: 1px solid var(--border-color);
    border-radius: 12px; padding: 10px 14px;
    transition: all .2s; position: relative; overflow: hidden;
}
.ds-kpi-card:hover { transform: translateY(-1px); box-shadow: var(--shadow-card); border-color: rgba(26,122,60,.2); }
.ds-kpi-accent {
    position: absolute; top: 0; left: 0; width: 3px; height: 100%;
    background: var(--brand-green);
}
.ds-kpi-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px; }
.ds-kpi-label { font-size: 9.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--text-muted); }
.ds-kpi-icon {
    width: 24px; height: 24px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    background: var(--bg-page); color: var(--text-muted); font-size: 11px;
}
.ds-kpi-value { font-size: 18px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.03em; line-height: 1.1; margin-bottom: 4px; }
.ds-kpi-trend {
    display: inline-flex; align-items: center; gap: 2px;
    font-size: 10px; font-weight: 600; padding: 1px 6px; border-radius: 16px;
}
.ds-kpi-trend.up { background: rgba(16,185,129,.1); color: #10b981; }
.ds-kpi-trend.down { background: rgba(239,68,68,.1); color: #ef4444; }
.ds-kpi-trend.neutral { background: var(--bg-hover); color: var(--text-muted); }

/* ─── CHARTS ─── (Hauteur réduite) */
.ds-charts-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 12px; margin-bottom: 14px;
}
@media (max-width: 900px) { .ds-charts-grid { grid-template-columns: 1fr; } }

.ds-chart-card {
    background: var(--bg-card); border: 1px solid var(--border-color);
    border-radius: 14px; overflow: hidden;
}
.ds-chart-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 14px; border-bottom: 1px solid var(--border-color);
}
.ds-chart-title {
    display: flex; align-items: center; gap: 6px;
    font-weight: 700; font-size: 12px; color: var(--text-primary);
}
.ds-chart-title-icon {
    width: 26px; height: 26px; border-radius: 7px;
    background: var(--bg-page); display: flex; align-items: center; justify-content: center;
    font-size: 12px; color: var(--brand-green);
}
.ds-chart-legend { display: flex; align-items: center; gap: 10px; font-size: 10.5px; color: var(--text-muted); }
.ds-legend-dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; margin-right: 3px; }
.ds-chart-body { padding: 10px 14px 6px; height: 320px; position: relative; }
.ds-chart-footer {
    padding: 6px 14px; border-top: 1px solid var(--border-color);
    display: flex; align-items: center; justify-content: space-between;
    background: var(--bg-page); font-size: 10.5px;
}
.ds-chart-stat { display: flex; align-items: baseline; gap: 4px; }
.ds-chart-stat-label { color: var(--text-muted); }
.ds-chart-stat-value { font-weight: 700; color: var(--text-primary); }

/* ─── CATEGORY PANEL ─── */
.ds-category-panel {
    background: var(--bg-card); border: 1px solid var(--border-color);
    border-radius: 14px; margin-bottom: 14px; overflow: hidden;
}
.ds-category-header {
    padding: 10px 14px; border-bottom: 1px solid var(--border-color);
    display: flex; align-items: center; justify-content: space-between;
}
.ds-category-content {
    display: grid; grid-template-columns: 1fr 280px;
    gap: 16px; padding: 14px; align-items: center;
}
@media (max-width: 700px) { .ds-category-content { grid-template-columns: 1fr; } }
.ds-category-chart { height: 200px; position: relative; }
.ds-category-list { display: flex; flex-direction: column; gap: 8px; }
.ds-category-item { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
.ds-category-info { display: flex; align-items: center; gap: 6px; flex: 1; min-width: 0; }
.ds-category-color { width: 7px; height: 7px; border-radius: 2px; flex-shrink: 0; }
.ds-category-name { font-size: 11.5px; font-weight: 500; color: var(--text-secondary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ds-category-percent { font-size: 10.5px; font-weight: 700; color: var(--text-primary); flex-shrink: 0; }
.ds-category-bar { width: 100%; height: 3px; background: var(--border-color); border-radius: 99px; overflow: hidden; margin-top: 3px; }
.ds-category-bar-fill { height: 100%; border-radius: 99px; transition: width .4s ease; }

/* ─── TABLE TOP PRODUITS ─── */
.ds-table-card {
    background: var(--bg-card); border: 1px solid var(--border-color);
    border-radius: 14px; overflow: hidden; margin-bottom: 14px;
}
.ds-table-header {
    padding: 10px 14px; border-bottom: 1px solid var(--border-color);
    display: flex; align-items: center; justify-content: space-between;
}
.ds-table-title {
    display: flex; align-items: center; gap: 6px;
    font-weight: 700; font-size: 12px; color: var(--text-primary);
}
.ds-table { width: 100%; border-collapse: collapse; }
.ds-table th {
    text-align: left; padding: 8px 14px; font-size: 9.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .05em;
    color: var(--text-muted); background: var(--bg-page);
    border-bottom: 1px solid var(--border-color);
}
.ds-table td { padding: 8px 14px; font-size: 12px; color: var(--text-primary); border-bottom: 1px solid var(--border-color); }
.ds-table tr:last-child td { border-bottom: none; }
.ds-table tr:hover td { background: var(--bg-hover); }
.ds-rank { display: inline-flex; align-items: center; justify-content: center; width: 20px; height: 20px; border-radius: 5px; font-size: 9.5px; font-weight: 700; }
.ds-rank-1 { background: #fef3c7; color: #d97706; }
.ds-rank-2 { background: #f1f5f9; color: #64748b; }
.ds-rank-3 { background: #fff7ed; color: #c2410c; }
.ds-rank-n { background: var(--bg-hover); color: var(--text-muted); }
.ds-progress { width: 60px; height: 4px; background: var(--border-color); border-radius: 99px; overflow: hidden; display: inline-block; vertical-align: middle; }
.ds-progress-fill { height: 100%; border-radius: 99px; background: var(--brand-green); }

/* ─── ÉTATS ─── */
.sk { background: linear-gradient(90deg, var(--border-color) 25%, var(--bg-hover) 50%, var(--border-color) 75%); background-size: 200% 100%; animation: shimmer 1.2s infinite; border-radius: 4px; }
@keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
.sk-val { height: 18px; width: 70%; }
.empty-state { text-align: center; padding: 30px; color: var(--text-muted); }
.empty-state i { font-size: 28px; display: block; margin-bottom: 10px; opacity: .4; }

/* ─── FOOTER ─── */
.ds-footer {
    display: flex; align-items: center; justify-content: flex-end;
    gap: 8px; padding-top: 4px; font-size: 10.5px; color: var(--text-muted);
}
.ds-footer-dot { width: 4px; height: 4px; border-radius: 50%; background: #22c55e; }

/* ─── OVERLAY CHARGEMENT ─── */
.ds-loading-overlay {
    position: absolute; inset: 0; background: rgba(var(--bg-card-rgb, 255,255,255),.7);
    display: flex; align-items: center; justify-content: center;
    backdrop-filter: blur(2px); z-index: 10; border-radius: inherit;
    opacity: 0; pointer-events: none; transition: opacity .2s;
}
.ds-loading-overlay.visible { opacity: 1; pointer-events: all; }
.ds-spinner {
    width: 24px; height: 24px; border-radius: 50%;
    border: 3px solid var(--border-color);
    border-top-color: var(--brand-green);
    animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>

<div class="ds" id="ds">

    {{-- ─── HEADER ─── --}}
    <div class="ds-header">
        <div>
            <h1 class="ds-title">Tableau de bord <span>JR Computer</span></h1>
            <p class="ds-subtitle">Analyse commerciale et opérationnelle en temps réel</p>
        </div>
        <div class="ds-date-chip">
            <div class="ds-live-dot"></div>
            <strong id="heroDay">--</strong>
            <span id="heroDate">---</span>
            <span>·</span>
            <span id="heroTime">--:--</span>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════
         BARRE DE FILTRES REDESIGNÉE
    ══════════════════════════════════════════════ --}}
    <div class="ds-filterbar" id="filterBar">

        {{-- Ligne 1 : Période + Dates + Actions --}}
        <div class="ds-fb-row1">

            {{-- Libellé période --}}
            <span class="ds-fb-label">
                <i class="bi bi-calendar3" style="font-size:11px;"></i>
                Période
            </span>

            {{-- Pilules de période --}}
            <div class="ds-period-wrap" id="periodBtns">
                <button class="ds-period-btn" data-p="week">7 jours</button>
                <button class="ds-period-btn active" data-p="month">Ce mois</button>
                <button class="ds-period-btn" data-p="quarter">Trimestre</button>
                <button class="ds-period-btn" data-p="year">Année</button>
                <button class="ds-period-btn" data-p="custom" id="customToggle">Personnalisé</button>
            </div>

            {{-- Dates custom (masquées par défaut) --}}
            <div class="ds-date-range" id="customDates" style="display:none;">
                <input type="date" id="filterFrom" class="ds-date-input" title="Date de début">
                <span class="ds-date-sep">→</span>
                <input type="date" id="filterTo" class="ds-date-input" title="Date de fin">
            </div>

            <div class="ds-fb-sep"></div>

            {{-- Actions --}}
            <div class="ds-fb-actions">
                <button class="ds-fb-btn primary" id="applyBtn">
                    <i class="bi bi-funnel-fill" style="font-size:10px;"></i> Appliquer
                </button>
                <button class="ds-fb-btn danger" id="resetBtn" title="Réinitialiser tous les filtres">
                    <i class="bi bi-arrow-counterclockwise" style="font-size:11px;"></i>
                </button>
                <button class="ds-fb-iconbtn" id="refreshBtn" title="Rafraîchir les données">
                    <i class="bi bi-arrow-repeat" id="refreshIcon"></i>
                </button>
            </div>
        </div>

        {{-- Ligne 2 : Filtres catégorie + tags actifs --}}
        <div class="ds-fb-row2">

            <span class="ds-fb-label" style="flex-shrink:0;">
                <i class="bi bi-tag" style="font-size:10px;"></i>
                Catégorie
            </span>

            <div class="ds-cat-chips" id="catChips">
                {{-- Chargé dynamiquement depuis l'API --}}
                <button class="ds-cat-chip active" data-cat="">
                    <span class="chip-dot"></span> Toutes
                </button>
                <span style="font-size:10.5px;color:var(--text-muted);font-style:italic;" id="catLoading">Chargement…</span>
            </div>

            <div class="ds-fb-sep" id="activeTagsSep" style="display:none;"></div>

            {{-- Tags filtres actifs --}}
            <div class="ds-active-filters" id="activeFilters"></div>

            <div class="ds-filter-summary" id="filterSummary">
                <i class="bi bi-sliders" style="font-size:10px;"></i>
                <span id="filterSummaryText">Période : Ce mois</span>
            </div>
        </div>
    </div>

    {{-- ─── KPI CARDS ─── --}}
    <div class="ds-kpi-grid">
        <div class="ds-kpi-card">
            <div class="ds-kpi-accent"></div>
            <div class="ds-kpi-header">
                <span class="ds-kpi-label">Ventes du jour</span>
                <span class="ds-kpi-icon"><i class="bi bi-wallet2"></i></span>
            </div>
            <div class="ds-kpi-value" id="kDailyCa">—</div>
            <div class="ds-kpi-trend up" id="kDailyCaTrend"><i class="bi bi-arrow-up-short"></i> vs hier</div>
        </div>
        <div class="ds-kpi-card">
            <div class="ds-kpi-accent" style="background:#f07d00;"></div>
            <div class="ds-kpi-header">
                <span class="ds-kpi-label">Ventes période</span>
                <span class="ds-kpi-icon"><i class="bi bi-cash-stack"></i></span>
            </div>
            <div class="ds-kpi-value" id="kPeriodCa">—</div>
            <div class="ds-kpi-trend neutral"><i class="bi bi-calendar3"></i> cumul</div>
        </div>
        <div class="ds-kpi-card">
            <div class="ds-kpi-accent" style="background:#0891b2;"></div>
            <div class="ds-kpi-header">
                <span class="ds-kpi-label">Marge brute</span>
                <span class="ds-kpi-icon"><i class="bi bi-percent"></i></span>
            </div>
            <div class="ds-kpi-value" id="kMargin">—</div>
            <div class="ds-kpi-trend up"><i class="bi bi-graph-up"></i> rentabilité</div>
        </div>
        <div class="ds-kpi-card">
            <div class="ds-kpi-accent" style="background:#ef4444;"></div>
            <div class="ds-kpi-header">
                <span class="ds-kpi-label">SAV en cours</span>
                <span class="ds-kpi-icon"><i class="bi bi-headset"></i></span>
            </div>
            <div class="ds-kpi-value" id="kSav">—</div>
            <div class="ds-kpi-trend down"><i class="bi bi-clock"></i> à traiter</div>
        </div>
    </div>

    {{-- ─── CHARTS ─── --}}
    <div class="ds-charts-grid">
        <div class="ds-chart-card" style="position:relative;">
            <div class="ds-loading-overlay" id="ov-rev"><div class="ds-spinner"></div></div>
            <div class="ds-chart-header">
                <div class="ds-chart-title">
                    <div class="ds-chart-title-icon"><i class="bi bi-graph-up-arrow"></i></div>
                    Ventes vs Achats
                </div>
                <div class="ds-chart-legend">
                    <span><span class="ds-legend-dot" style="background:#1a7a3c;"></span>Ventes</span>
                    <span><span class="ds-legend-dot" style="background:#f07d00;"></span>Achats</span>
                </div>
            </div>
            <div class="ds-chart-body"><canvas id="chartRevenue"></canvas></div>
            <div class="ds-chart-footer">
                <div class="ds-chart-stat"><span class="ds-chart-stat-label">Pic :</span><span class="ds-chart-stat-value" id="svMax">—</span></div>
                <div class="ds-chart-stat"><span class="ds-chart-stat-label">Moyenne :</span><span class="ds-chart-stat-value" id="svAvg">—</span></div>
                <div class="ds-chart-stat"><span class="ds-chart-stat-label">Marge :</span><span class="ds-chart-stat-value" id="svDiff">—</span></div>
            </div>
        </div>

        <div class="ds-chart-card" style="position:relative;">
            <div class="ds-loading-overlay" id="ov-sav"><div class="ds-spinner"></div></div>
            <div class="ds-chart-header">
                <div class="ds-chart-title">
                    <div class="ds-chart-title-icon"><i class="bi bi-bar-chart-fill"></i></div>
                    Activité SAV
                </div>
                <div class="ds-chart-legend">
                    <span><span class="ds-legend-dot" style="background:#f59e0b;"></span>Créés</span>
                    <span><span class="ds-legend-dot" style="background:#1a7a3c;"></span>Clôturés</span>
                    <span><span class="ds-legend-dot" style="background:#7c3aed;"></span>Taux</span>
                </div>
            </div>
            <div class="ds-chart-body"><canvas id="chartSav"></canvas></div>
            <div class="ds-chart-footer">
                <div class="ds-chart-stat"><span class="ds-chart-stat-label">Créés :</span><span class="ds-chart-stat-value" id="savTotalCreated">—</span></div>
                <div class="ds-chart-stat"><span class="ds-chart-stat-label">Clôturés :</span><span class="ds-chart-stat-value" id="savTotalClosed">—</span></div>
                <div class="ds-chart-stat"><span class="ds-chart-stat-label">Backlog :</span><span class="ds-chart-stat-value" id="savBacklog">—</span></div>
            </div>
        </div>
    </div>

    {{-- ─── DONUT CATÉGORIE ─── --}}
    <div class="ds-category-panel" style="position:relative;">
        <div class="ds-loading-overlay" id="ov-cat"><div class="ds-spinner"></div></div>
        <div class="ds-category-header">
            <div class="ds-chart-title">
                <div class="ds-chart-title-icon"><i class="bi bi-pie-chart-fill"></i></div>
                Répartition par catégorie
                <span id="activeCatBadge" style="display:none;padding:2px 8px;background:var(--brand-green-xlight);color:var(--brand-green);border-radius:18px;font-size:10px;font-weight:700;"></span>
            </div>
            <span id="catTotalLabel" style="font-size:10.5px;color:var(--text-muted);">Chargement...</span>
        </div>
        <div class="ds-category-content">
            <div class="ds-category-chart"><canvas id="chartCategory"></canvas></div>
            <div class="ds-category-list" id="catList"></div>
        </div>
    </div>

    {{-- ─── TOP 10 PRODUITS ─── --}}
    <div class="ds-table-card" style="position:relative;">
        <div class="ds-loading-overlay" id="ov-prod"><div class="ds-spinner"></div></div>
        <div class="ds-table-header">
            <div class="ds-table-title">
                <i class="bi bi-trophy-fill" style="color:#f07d00;"></i>
                Top 10 produits
                <span id="activeCatBadge2" style="display:none;padding:2px 8px;background:var(--brand-green-xlight);color:var(--brand-green);border-radius:18px;font-size:10px;font-weight:700;"></span>
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="ds-table" id="tProducts">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Produit</th>
                        <th style="width:100px;">Catégorie</th>
                        <th style="width:60px;text-align:right;">Qté</th>
                        <th style="width:120px;text-align:right;">CA</th>
                        <th style="width:100px;">Performance</th>
                    </tr>
                </thead>
                <tbody><tr><td colspan="6" class="empty-state"><div class="sk sk-val" style="margin:0 auto;"></div></td></tr></tbody>
            </table>
        </div>
    </div>

    {{-- ─── FOOTER ─── --}}
    <div class="ds-footer" style="margin-bottom:-25px">
        <div class="ds-footer-dot"></div>
        <span id="lastUpdate">Chargement...</span>
    </div>

</div>{{-- .ds --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    'use strict';

    /* ── Utilitaires ── */
    const $ = id => document.getElementById(id);
    const fmt = v => new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XAF', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(v).replace('XAF', 'FCFA');
    const fmtN = v => new Intl.NumberFormat('fr-FR').format(v);
    const fmtC = v => v >= 1e6 ? (v / 1e6).toFixed(1) + 'M' : v >= 1e3 ? (v / 1e3).toFixed(0) + 'k' : String(v);
    const esc = s => { if (!s) return ''; const d = document.createElement('div'); d.textContent = s; return d.innerHTML; };
    const ov = id => $(id);
    const showOv = id => { const el = ov(id); if (el) el.classList.add('visible'); };
    const hideOv = id => { const el = ov(id); if (el) el.classList.remove('visible'); };

    /* ── État global ── */
    let revenueChart = null, savChart = null, categoryChart = null;
    let currentPeriod = 'month';
    let currentCategory = '';
    let currentDateFrom = '', currentDateTo = '';
    let allCategories = [];

    const PALETTE = ['#1a7a3c', '#f07d00', '#0891b2', '#f59e0b', '#6366f1', '#ec4899', '#84cc16', '#14b8a6', '#8b5cf6', '#06b6d4'];

    /* ── Plages de dates ── */
    function getDateRange(period) {
        const today = new Date();
        let from = new Date();
        switch (period) {
            case 'week':    from.setDate(today.getDate() - 6); break;
            case 'month':   from = new Date(today.getFullYear(), today.getMonth(), 1); break;
            case 'quarter': from.setMonth(today.getMonth() - 3); break;
            case 'year':    from = new Date(today.getFullYear(), 0, 1); break;
            case 'custom':
                return {
                    date_from: $('filterFrom')?.value || new Date(today.getFullYear(), today.getMonth(), 1).toISOString().slice(0, 10),
                    date_to: $('filterTo')?.value || today.toISOString().slice(0, 10),
                };
        }
        return {
            date_from: from.toISOString().slice(0, 10),
            date_to: today.toISOString().slice(0, 10),
        };
    }

    /* ── Mise à jour des filtres actifs ── */
    function updateFilters() {
        const range = getDateRange(currentPeriod);
        currentDateFrom = range.date_from;
        currentDateTo   = range.date_to;
    }

    /* ── Résumé des filtres (UI) ── */
    function updateFilterSummary() {
        const periodLabels = { week: '7 derniers jours', month: 'Ce mois', quarter: 'Trimestre', year: 'Année', custom: 'Personnalisé' };
        let txt = `Période : <strong>${periodLabels[currentPeriod] || currentPeriod}</strong>`;
        if (currentCategory) txt += ` · Catégorie : <strong>${currentCategory}</strong>`;
        const el = $('filterSummaryText');
        if (el) el.innerHTML = txt;

        /* Tags actifs */
        const af = $('activeFilters');
        const sep = $('activeTagsSep');
        if (af) {
            af.innerHTML = '';
            if (currentCategory) {
                const tag = document.createElement('div');
                tag.className = 'ds-active-tag';
                tag.innerHTML = `<i class="bi bi-tag" style="font-size:9px;"></i>${esc(currentCategory)}<button onclick="clearCategory()" title="Supprimer">✕</button>`;
                af.appendChild(tag);
            }
        }
        if (sep) sep.style.display = currentCategory ? '' : 'none';

        /* Badge catégorie sur les panneaux */
        ['activeCatBadge', 'activeCatBadge2'].forEach(id => {
            const el = $(id);
            if (!el) return;
            if (currentCategory) { el.textContent = currentCategory; el.style.display = ''; }
            else el.style.display = 'none';
        });
    }

    /* ── Charger les catégories depuis l'API ── */
    async function loadCategories() {
        try {
            const res = await fetch('/api/commercial/categories');
            if (!res.ok) throw new Error();
            const data = await res.json();
            allCategories = data.categories || [];
            renderCategoryChips();
        } catch {
            /* Silently fail — les chips restent avec "Toutes" uniquement */
            const loading = $('catLoading');
            if (loading) loading.remove();
        }
    }

    function renderCategoryChips() {
        const container = $('catChips');
        const loading = $('catLoading');
        if (loading) loading.remove();
        if (!container) return;

        /* Vider sauf "Toutes" */
        container.innerHTML = '';

        /* Chip "Toutes" */
        const all = document.createElement('button');
        all.className = 'ds-cat-chip' + (!currentCategory ? ' active' : '');
        all.dataset.cat = '';
        all.innerHTML = `<span class="chip-dot"></span> Toutes`;
        all.addEventListener('click', () => selectCategory(''));
        container.appendChild(all);

        /* Chips des catégories */
        allCategories.forEach((cat, i) => {
            const btn = document.createElement('button');
            btn.className = 'ds-cat-chip' + (currentCategory === cat ? ' active' : '');
            btn.dataset.cat = cat;
            btn.style.setProperty('--chip-color', PALETTE[i % PALETTE.length]);
            btn.innerHTML = `<span class="chip-dot" style="background:${PALETTE[i % PALETTE.length]};"></span> ${esc(cat)}`;
            btn.addEventListener('click', () => selectCategory(cat));
            container.appendChild(btn);
        });
    }

    function selectCategory(cat) {
        currentCategory = cat;
        renderCategoryChips();
        updateFilterSummary();
        load();
    }

    window.clearCategory = function () {
        selectCategory('');
    };

    /* ── Tokens pour Chart.js (dark mode) ── */
    function chartTokens() {
        const dark = document.documentElement.getAttribute('data-theme') === 'dark';
        return {
            grid:          dark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)',
            tick:          dark ? '#6b8074' : '#94a89e',
            tooltipBg:     dark ? '#172119' : '#ffffff',
            tooltipTitle:  dark ? '#e8f5ec' : '#0f1f12',
            tooltipBody:   dark ? '#8aab92' : '#5a6b61',
        };
    }

    /* ── Charts ── */
    function buildRevenueChart(labels, ventes, achats) {
        const canvas = $('chartRevenue'); if (!canvas) return;
        const ctx = canvas.getContext('2d'); const t = chartTokens();
        if (revenueChart) revenueChart.destroy();
        const gG = ctx.createLinearGradient(0, 0, 0, 240);
        gG.addColorStop(0, 'rgba(26,122,60,.22)'); gG.addColorStop(1, 'rgba(26,122,60,0)');
        const gO = ctx.createLinearGradient(0, 0, 0, 240);
        gO.addColorStop(0, 'rgba(240,125,0,.18)'); gO.addColorStop(1, 'rgba(240,125,0,0)');
        revenueChart = new Chart(ctx, {
            type: 'line',
            data: { labels, datasets: [
                { label: 'Ventes',  data: ventes,  borderColor: '#1a7a3c', backgroundColor: gG, borderWidth: 2.5, fill: true, tension: .35, pointRadius: 4, pointBackgroundColor: '#1a7a3c', pointBorderColor: '#fff', pointBorderWidth: 1.5 },
                { label: 'Achats',  data: achats,  borderColor: '#f07d00', backgroundColor: gO, borderWidth: 2,   fill: true, tension: .35, pointRadius: 4, pointBackgroundColor: '#f07d00', pointBorderColor: '#fff', pointBorderWidth: 1.5, borderDash: [5,4] },
            ]},
            options: {
                responsive: true, maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { display: false }, tooltip: { backgroundColor: t.tooltipBg, titleColor: t.tooltipTitle, bodyColor: t.tooltipBody, padding: 8, cornerRadius: 8, callbacks: { label: ctx => ` ${ctx.dataset.label} : ${fmt(ctx.raw || 0)}` } } },
                scales: { x: { grid: { display: false }, ticks: { color: t.tick, font: { size: 9 } } }, y: { grid: { color: t.grid }, ticks: { color: t.tick, font: { size: 9 }, callback: v => fmtC(v) + ' F' }, beginAtZero: true } }
            }
        });
    }

    function buildSavChart(labels, created, closed) {
        const canvas = $('chartSav'); if (!canvas) return;
        const ctx = canvas.getContext('2d'); const t = chartTokens();
        if (savChart) savChart.destroy();
        const rate = created.map((c, i) => c > 0 ? Math.round((closed[i] / c) * 100) : 0);
        savChart = new Chart(ctx, {
            type: 'bar',
            data: { labels, datasets: [
                { label: 'Créés',    data: created, backgroundColor: 'rgba(245,158,11,.75)', borderRadius: 5, barPercentage: .55, yAxisID: 'y' },
                { label: 'Clôturés', data: closed,  backgroundColor: 'rgba(26,122,60,.75)',  borderRadius: 5, barPercentage: .55, yAxisID: 'y' },
                { label: 'Taux %',   data: rate, type: 'line', borderColor: '#7c3aed', backgroundColor: 'transparent', borderWidth: 2.5, tension: .3, pointRadius: 4, pointBackgroundColor: '#7c3aed', pointBorderColor: '#fff', yAxisID: 'y1' },
            ]},
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { backgroundColor: chartTokens().tooltipBg, titleColor: chartTokens().tooltipTitle, bodyColor: chartTokens().tooltipBody, callbacks: { label: ctx => ctx.dataset.yAxisID === 'y1' ? ` Taux : ${ctx.raw}%` : ` ${ctx.dataset.label} : ${ctx.raw}` } } },
                scales: { x: { grid: { display: false }, ticks: { color: chartTokens().tick, font: { size: 9 } } }, y: { grid: { color: chartTokens().grid }, ticks: { color: chartTokens().tick }, beginAtZero: true }, y1: { position: 'right', grid: { display: false }, ticks: { color: '#7c3aed', callback: v => v + '%' }, min: 0, max: 100 } }
            }
        });
    }

    function buildCategoryChart(labels, data) {
        const canvas = $('chartCategory'); if (!canvas) return;
        const ctx = canvas.getContext('2d'); const t = chartTokens();
        if (categoryChart) categoryChart.destroy();
        const total = data.reduce((a, b) => a + b, 0);
        if ($('catTotalLabel')) $('catTotalLabel').textContent = 'Total : ' + fmt(total);
        if (!total || !data.length) {
            categoryChart = new Chart(ctx, { type: 'doughnut', data: { labels: ['Aucune donnée'], datasets: [{ data: [1], backgroundColor: ['#e5e7eb'] }] }, options: { cutout: '65%', plugins: { legend: { display: false } } } });
            if ($('catList')) $('catList').innerHTML = '<div class="empty-state" style="padding:0">Aucune vente sur cette période</div>';
            return;
        }
        categoryChart = new Chart(ctx, {
            type: 'doughnut',
            data: { labels, datasets: [{ data, backgroundColor: PALETTE, borderWidth: 0, hoverOffset: 8 }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '65%', plugins: { legend: { display: false }, tooltip: { backgroundColor: t.tooltipBg, titleColor: t.tooltipTitle, bodyColor: t.tooltipBody, callbacks: { label: ctx => ` ${fmt(ctx.raw)} (${Math.round((ctx.raw / total) * 100)}%)` } } } }
        });
        if ($('catList')) {
            $('catList').innerHTML = labels.map((l, i) => {
                const pct = Math.round((data[i] / total) * 100);
                return `<div>
                    <div class="ds-category-item">
                        <div class="ds-category-info">
                            <div class="ds-category-color" style="background:${PALETTE[i % PALETTE.length]}"></div>
                            <span class="ds-category-name">${esc(l)}</span>
                        </div>
                        <span class="ds-category-percent">${pct}%</span>
                    </div>
                    <div class="ds-category-bar"><div class="ds-category-bar-fill" style="width:${pct}%;background:${PALETTE[i % PALETTE.length]}"></div></div>
                </div>`;
            }).join('');
        }
    }

    function renderProducts(list) {
        const tbody = document.querySelector('#tProducts tbody');
        if (!tbody) return;
        if (!list?.length) { tbody.innerHTML = '<tr><td colspan="6" class="empty-state">Aucun produit sur cette période</td></tr>'; return; }
        const maxTotal = Math.max(...list.map(p => p.total || 0)) || 1;
        tbody.innerHTML = list.slice(0, 10).map((p, i) => {
            const rankCls = i === 0 ? 'ds-rank-1' : i === 1 ? 'ds-rank-2' : i === 2 ? 'ds-rank-3' : 'ds-rank-n';
            const pct = Math.round((p.total / maxTotal) * 100);
            return `<tr>
                <td><span class="ds-rank ${rankCls}">${i + 1}</span></td>
                <td style="font-weight:700;">${esc(p.name)}</td>
                <td><span style="font-size:10.5px;color:var(--text-muted);">${esc(p.category || '—')}</span></td>
                <td style="text-align:right;">${fmtN(p.qty)}</td>
                <td style="text-align:right;color:#1a7a3c;font-weight:700;">${fmt(p.total)}</td>
                <td>
                    <div class="ds-progress"><div class="ds-progress-fill" style="width:${pct}%"></div></div>
                    <span style="font-size:9.5px;color:var(--text-muted);margin-left:6px;">${pct}%</span>
                </td>
            </tr>`;
        }).join('');
    }

    // Remplacez la fonction load() dans dashboard.blade.php par ceci :

/* ══════════════════════════════════════════════
   CHARGEMENT PRINCIPAL (OPTIMISÉ)
══════════════════════════════════════════════ */
async function load() {
    updateFilters();
    updateFilterSummary();

    /* Skeletons KPI */
    ['kDailyCa', 'kPeriodCa', 'kMargin', 'kSav'].forEach(id => {
        const el = $(id);
        if (el && (!el.textContent.trim() || el.textContent === '—')) {
            el.innerHTML = '<div class="sk sk-val"></div>';
        }
    });

    /* Afficher overlays */
    ['ov-rev', 'ov-sav', 'ov-cat', 'ov-prod'].forEach(showOv);

    const params = new URLSearchParams({
        date_from: currentDateFrom,
        date_to:   currentDateTo,
        category:  currentCategory,
    });

    try {
        // ✅ 1. D'abord charger les KPIs (rapides)
        const kpiRes = await fetch(`/api/commercial/data?${params}&quick=true`);
        const kpiData = await kpiRes.json();
        const kpis = kpiData.kpis || {};
        
        if ($('kDailyCa'))  $('kDailyCa').textContent  = fmt(kpis.daily_ca || 0);
        if ($('kPeriodCa')) $('kPeriodCa').textContent = fmt(kpis.monthly_ca || 0);
        if ($('kMargin'))   $('kMargin').textContent   = (kpis.margin_rate || 0) + '%';
        if ($('kSav'))      $('kSav').textContent      = fmtN(kpis.pending_tickets || 0);

        // ✅ 2. Puis charger les graphiques en parallèle
        const [commRes, purchRes, savRes] = await Promise.all([
            fetch(`/api/commercial/data?${params}`),
            fetch(`/api/purchases/data?${params}`),
            fetch(`/api/sav-tickets/data?${params}`),
        ]);

        const [commData, purchData, savData] = await Promise.all([
            commRes.json(), purchRes.json(), savRes.json(),
        ]);

        hideOv('ov-rev'); hideOv('ov-sav'); hideOv('ov-cat'); hideOv('ov-prod');

        const cats = commData.categoryDistribution || [];
        buildCategoryChart(cats.map(c => c.category), cats.map(c => c.total));
        hideOv('ov-cat');

        renderProducts(commData.topProducts || []);
        hideOv('ov-prod');

        const ventes   = commData.salesEvolution || [];
        const purchases = purchData.purchasesEvolution || [];
        const allP = [...new Set([...ventes.map(v => v.period), ...purchases.map(p => p.period)])].sort();
        const vMap = new Map(ventes.map(v => [v.period, v.total]));
        const aMap = new Map(purchases.map(p => [p.period, p.total]));
        const rlabels = allP.map(p => new Date(p + '-01').toLocaleDateString('fr-FR', { month: 'short', year: '2-digit' }));
        buildRevenueChart(rlabels, allP.map(p => vMap.get(p) || 0), allP.map(p => aMap.get(p) || 0));
        hideOv('ov-rev');

        if (ventes.length) {
            const totals = ventes.map(v => v.total);
            const maxV = Math.max(...totals);
            const avgV = Math.round(totals.reduce((a, b) => a + b, 0) / totals.length);
            const totalV = totals.reduce((a, b) => a + b, 0);
            const totalA = purchases.reduce((a, p) => a + (p.total || 0), 0);
            const diff = totalV > 0 ? Math.round(((totalV - totalA) / totalV) * 100) : 0;
            if ($('svMax'))  $('svMax').textContent = fmtC(maxV) + ' F';
            if ($('svAvg'))  $('svAvg').textContent = fmtC(avgV) + ' F';
            if ($('svDiff')) { $('svDiff').textContent = diff + '%'; $('svDiff').style.color = diff >= 0 ? '#1a7a3c' : '#ef4444'; }
        }

        const created = savData.createdTickets || [];
        const closed  = savData.closedTickets  || [];
        const savP = [...new Set([...created.map(c => c.period), ...closed.map(c => c.period)])].sort();
        const cMap = new Map(created.map(c => [c.period, c.total]));
        const dMap = new Map(closed.map(c => [c.period, c.total]));
        const sLabels = savP.map(p => new Date(p + '-01').toLocaleDateString('fr-FR', { month: 'short', year: '2-digit' }));
        buildSavChart(sLabels, savP.map(p => cMap.get(p) || 0), savP.map(p => dMap.get(p) || 0));
        hideOv('ov-sav');

        const tc = created.reduce((a, c) => a + (c.total || 0), 0);
        const tl = closed.reduce((a, c) => a + (c.total || 0), 0);
        if ($('savTotalCreated')) $('savTotalCreated').textContent = fmtN(tc);
        if ($('savTotalClosed'))  $('savTotalClosed').textContent  = fmtN(tl);
        if ($('savBacklog'))      $('savBacklog').textContent      = fmtN(Math.max(0, tc - tl));

        const lu = $('lastUpdate');
        if (lu) lu.textContent = `Mis à jour à ${new Date().toLocaleTimeString('fr-FR')} · Filtre: ${currentCategory || 'Toutes catégories'}`;

    } catch (e) {
        console.error('Dashboard error:', e);
        ['kDailyCa', 'kPeriodCa', 'kMargin', 'kSav'].forEach(id => { const el = $(id); if (el) el.innerHTML = '<span style="color:#ef4444;">⚠</span>'; });
        ['ov-rev', 'ov-sav', 'ov-cat', 'ov-prod'].forEach(hideOv);
    }
}

    /* ── Boutons période ── */
    document.querySelectorAll('.ds-period-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.ds-period-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentPeriod = this.dataset.p;

            const cd = $('customDates');
            if (cd) cd.style.display = currentPeriod === 'custom' ? 'flex' : 'none';

            if (currentPeriod !== 'custom') load();
        });
    });

    /* ── Bouton Appliquer ── */
    $('applyBtn')?.addEventListener('click', () => load());

    /* ── Touches Entrée sur les dates ── */
    ['filterFrom', 'filterTo'].forEach(id => {
        $(id)?.addEventListener('keypress', e => { if (e.key === 'Enter') load(); });
        $(id)?.addEventListener('change', () => { if (currentPeriod === 'custom') load(); });
    });

    /* ── Réinitialiser ── */
    $('resetBtn')?.addEventListener('click', () => {
        currentPeriod   = 'month';
        currentCategory = '';
        document.querySelectorAll('.ds-period-btn').forEach(b => b.classList.toggle('active', b.dataset.p === 'month'));
        const cd = $('customDates');
        if (cd) cd.style.display = 'none';
        renderCategoryChips();
        load();
    });

    /* ── Rafraîchir ── */
    $('refreshBtn')?.addEventListener('click', () => {
        const ic = $('refreshIcon');
        if (ic) { ic.style.transition = 'transform .5s ease'; ic.style.transform = 'rotate(360deg)'; setTimeout(() => { ic.style.transition = ''; ic.style.transform = ''; }, 520); }
        load();
    });

    /* ── Réactivité mode sombre ── */
    new MutationObserver(() => load())
        .observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });

    /* ── Horloge ── */
    function updateClock() {
        const n = new Date();
        const days   = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
        if ($('heroDay'))  $('heroDay').textContent  = days[n.getDay()];
        if ($('heroDate')) $('heroDate').textContent = `${n.getDate()} ${months[n.getMonth()]} ${n.getFullYear()}`;
        if ($('heroTime')) $('heroTime').textContent = n.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    }
    updateClock();
    setInterval(updateClock, 60000);

    /* ── Pré-remplir les dates custom avec la plage actuelle ── */
    function initDateInputs() {
        const r = getDateRange('month');
        const f = $('filterFrom'); const t = $('filterTo');
        if (f) f.value = r.date_from;
        if (t) t.value = r.date_to;
    }

    /* ── WebSocket Echo ── */
    if (window.Echo) {
        const role = document.body.dataset.userRole;
        if (role === 'admin' || role === 'manager') {
            try {
                window.Echo.private('admin')
                    .listen('.ticket.closed', () => load())
                    .listen('.low.stock',     () => load());
            } catch (e) {}
        }
    }

    /* ── INIT ── */
    initDateInputs();
    loadCategories();
    load();

})();
</script>
@endsection