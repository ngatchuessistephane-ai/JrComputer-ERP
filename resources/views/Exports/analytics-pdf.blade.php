<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport BI — JR Computer Sarl</title>
    <style>
        /* DomPDF : pas de flexbox avancé → float + table layout */
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'DejaVu Sans', Helvetica, sans-serif;
            font-size: 9px;
            color: #1a1a2e;
            background: #f8fafc;
            padding: 22px 24px;
        }

        /* ─── HEADER ─── */
        .hdr { border-bottom: 2px solid #1a7a3c; padding-bottom: 14px; margin-bottom: 18px; }
        .hdr-left  { float: left; }
        .hdr-right { float: right; text-align: right; }
        .hdr::after { content:''; display:table; clear:both; }

        .logo-box {
            display: inline-block;
            width: 38px; height: 38px; border-radius: 9px;
            background: #1a7a3c;
            text-align: center; line-height: 38px;
            color: #fff; font-size: 14px; font-weight: 900;
            vertical-align: middle; margin-right: 10px;
        }
        .co-name { font-size: 17px; font-weight: 900; color: #1a7a3c; display: inline-block; vertical-align: middle; }
        .co-sub  { font-size: 8px; color: #6b7280; margin-top: 2px; }
        .rpt-title { font-size: 13px; font-weight: 900; color: #111827; }
        .rpt-meta  { font-size: 8px; color: #6b7280; margin-top: 3px; }

        /* ─── KPI ROW ─── */
        .kpi-row { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin-bottom: 16px; }
        .kpi-cell {
            background: #fff; border-radius: 10px; padding: 11px 12px;
            border-left: 3px solid #1a7a3c;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
            vertical-align: top;
        }
        .kpi-cell.o  { border-left-color: #f07d00; }
        .kpi-cell.b  { border-left-color: #0891b2; }
        .kpi-cell.r  { border-left-color: #ef4444; }
        .kpi-cell.a  { border-left-color: #d97706; }

        .kpi-label { font-size: 7px; text-transform: uppercase; letter-spacing: .8px; color: #9ca3af; font-weight: 700; margin-bottom: 5px; }
        .kpi-value { font-size: 16px; font-weight: 900; color: #111827; line-height: 1; }
        .kpi-unit  { font-size: 7.5px; color: #9ca3af; margin-top: 3px; }

        /* ─── ALERTS ─── */
        .alerts-row { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin-bottom: 16px; }
        .al-cell { border-radius: 9px; padding: 10px 11px; vertical-align: middle; }
        .al-cell.w  { background: #fffbeb; border: 1px solid #fde68a; }
        .al-cell.d  { background: #fef2f2; border: 1px solid #fecaca; }
        .al-cell.i  { background: #eff6ff; border: 1px solid #bfdbfe; }
        .al-cell.ok { background: #f0fdf4; border: 1px solid #bbf7d0; }
        .al-num  { font-size: 20px; font-weight: 900; line-height: 1; }
        .al-cell.w  .al-num { color: #d97706; }
        .al-cell.d  .al-num { color: #ef4444; }
        .al-cell.i  .al-num { color: #3b82f6; }
        .al-cell.ok .al-num { color: #16a34a; }
        .al-title { font-size: 8px; font-weight: 800; color: #374151; }
        .al-sub   { font-size: 7.5px; color: #6b7280; }

        /* ─── SECTION BOXES ─── */
        .section-wrap { margin-bottom: 16px; }
        .section-box {
            display: inline-block; vertical-align: top;
            background: #fff; border-radius: 10px; padding: 13px;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }
        .sec-title {
            font-size: 10px; font-weight: 900; color: #111827;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 7px; margin-bottom: 10px;
        }
        .sec-dot { display: inline-block; width: 7px; height: 7px; border-radius: 50%; background: #1a7a3c; margin-right: 6px; vertical-align: middle; }

        /* ─── TABLES ─── */
        table.data { width: 100%; border-collapse: collapse; font-size: 8.5px; }
        table.data thead tr { background: #f9fafb; }
        table.data th { padding: 6px 8px; text-align: left; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: .5px; font-size: 7.5px; }
        table.data td { padding: 6px 8px; color: #374151; border-bottom: 1px solid #f3f4f6; }
        table.data tbody tr:last-child td { border-bottom: none; }
        table.data tbody tr:nth-child(even) td { background: #fafafa; }

        .rank {
            display: inline-block; width: 16px; height: 16px; border-radius: 4px;
            background: #f3f4f6; color: #6b7280;
            font-size: 7.5px; font-weight: 800;
            text-align: center; line-height: 16px;
        }
        .rank.gold   { background: #fef3c7; color: #d97706; }
        .rank.silver { background: #f1f5f9; color: #64748b; }
        .rank.bronze { background: #fff7ed; color: #c2410c; }

        .pill { display: inline-block; padding: 2px 7px; border-radius: 20px; font-size: 7.5px; font-weight: 700; }
        .pill-g  { background: #ecfdf5; color: #065f46; }
        .pill-o  { background: #fff7ed; color: #c2410c; }
        .pill-b  { background: #eff6ff; color: #1e40af; }

        .t-r  { text-align: right; }
        .t-b  { font-weight: 800; }
        .t-g  { color: #1a7a3c; font-weight: 700; }

        /* ─── PROGRESS ─── */
        .prog-bg   { background: #f3f4f6; border-radius: 99px; height: 4px; margin-top: 3px; }
        .prog-fill { background: #1a7a3c; border-radius: 99px; height: 4px; }
        /* ✅ Classes de largeur statiques pour DomPDF (évite style="width:{{ }}%" qui casse le CSS de VS Code) */
        .prog-w-0   { width: 0%; }
        .prog-w-5   { width: 5%; }
        .prog-w-10  { width: 10%; }
        .prog-w-15  { width: 15%; }
        .prog-w-20  { width: 20%; }
        .prog-w-25  { width: 25%; }
        .prog-w-30  { width: 30%; }
        .prog-w-35  { width: 35%; }
        .prog-w-40  { width: 40%; }
        .prog-w-45  { width: 45%; }
        .prog-w-50  { width: 50%; }
        .prog-w-55  { width: 55%; }
        .prog-w-60  { width: 60%; }
        .prog-w-65  { width: 65%; }
        .prog-w-70  { width: 70%; }
        .prog-w-75  { width: 75%; }
        .prog-w-80  { width: 80%; }
        .prog-w-85  { width: 85%; }
        .prog-w-90  { width: 90%; }
        .prog-w-95  { width: 95%; }
        .prog-w-100 { width: 100%; }

        /* ─── SAV METRIC ─── */
        .sav-metric {
            background: #f9fafb; border-radius: 8px; padding: 10px;
            text-align: center; margin-bottom: 10px;
        }
        .sav-num { font-size: 22px; font-weight: 900; color: #0891b2; }
        .sav-sub { font-size: 7.5px; color: #6b7280; text-transform: uppercase; letter-spacing: .5px; }

        /* ─── CATEGORY TILES ─── */
        .cat-tile {
            display: inline-block; vertical-align: top;
            min-width: 90px; padding: 10px;
            background: #f9fafb; border-radius: 8px;
            margin-right: 8px; margin-bottom: 8px;
        }
        /* ✅ Couleurs statiques pour les tuiles catégories (évite border-top: 3px solid {{ $color }}) */
        .cat-c0 { border-top: 3px solid #1a7a3c; }
        .cat-c1 { border-top: 3px solid #0891b2; }
        .cat-c2 { border-top: 3px solid #f07d00; }
        .cat-c3 { border-top: 3px solid #d97706; }
        .cat-c4 { border-top: 3px solid #6366f1; }
        .cat-c5 { border-top: 3px solid #ec4899; }
        .cat-c6 { border-top: 3px solid #84cc16; }
        .cat-c7 { border-top: 3px solid #14b8a6; }

        .cat-pct   { font-size: 16px; font-weight: 900; color: #111827; }
        .cat-name  { font-size: 8px; font-weight: 700; color: #374151; margin: 2px 0; }
        .cat-value { font-size: 7.5px; color: #9ca3af; }

        /* ─── FOOTER ─── */
        .footer {
            margin-top: 18px; padding-top: 10px;
            border-top: 1px solid #e5e7eb;
        }
        .footer-l { float: left;  font-size: 7.5px; color: #9ca3af; }
        .footer-r { float: right; font-size: 7.5px; color: #9ca3af; }
        .footer::after { content:''; display:table; clear:both; }
    </style>
</head>
<body>

{{-- ─── HEADER ─── --}}
<div class="hdr">
    <div class="hdr-left">
        <span class="logo-box">JR</span>
        <span class="co-name">JR Computer Sarl</span>
        <div class="co-sub" style="padding-left:48px;">Système ERP — Module Analytics &amp; Business Intelligence</div>
    </div>
    <div class="hdr-right">
        <div class="rpt-title">Rapport Business Intelligence</div>
        <div class="rpt-meta">{{ $period_label }}</div>
        <div class="rpt-meta">Généré le {{ $generated_at }}</div>
    </div>
</div>

{{-- ─── KPIs ─── --}}
<table class="kpi-row">
    <tr>
        <td class="kpi-cell">
            <div class="kpi-label">CA Journalier</div>
            <div class="kpi-value">{{ number_format($kpis['daily_ca'], 0, ',', ' ') }}</div>
            <div class="kpi-unit">FCFA aujourd'hui</div>
        </td>
        <td class="kpi-cell">
            <div class="kpi-label">CA Période</div>
            <div class="kpi-value">{{ number_format($kpis['monthly_ca'], 0, ',', ' ') }}</div>
            <div class="kpi-unit">FCFA sur la période</div>
        </td>
        <td class="kpi-cell o">
            <div class="kpi-label">Marge Brute</div>
            <div class="kpi-value">{{ $kpis['margin_rate'] }}%</div>
            <div class="kpi-unit">après coût d'achat</div>
        </td>
        <td class="kpi-cell b">
            <div class="kpi-label">Taux Conversion</div>
            <div class="kpi-value">{{ $kpis['conversion_rate'] }}%</div>
            <div class="kpi-unit">devis → facture</div>
        </td>
        <td class="kpi-cell a">
            <div class="kpi-label">Panier Moyen</div>
            <div class="kpi-value">{{ number_format($kpis['avg_basket'], 0, ',', ' ') }}</div>
            <div class="kpi-unit">FCFA / facture</div>
        </td>
        <td class="kpi-cell r">
            <div class="kpi-label">Tickets SAV actifs</div>
            <div class="kpi-value">{{ $kpis['pending_tickets'] }}</div>
            <div class="kpi-unit">en cours</div>
        </td>
    </tr>
</table>

{{-- ─── ALERTES ─── --}}
<table class="alerts-row">
    <tr>
        <td class="al-cell w">
            <div class="al-num">{{ $alerts['low_stock_products'] }}</div>
            <div class="al-title">Stocks produits bas</div>
            <div class="al-sub">En dessous du seuil d'alerte</div>
        </td>
        <td class="al-cell w">
            <div class="al-num">{{ $alerts['low_stock_parts'] }}</div>
            <div class="al-title">Pièces détachées</div>
            <div class="al-sub">Réapprovisionnement requis</div>
        </td>
        <td class="al-cell d">
            <div class="al-num">{{ $alerts['critical_tickets'] }}</div>
            <div class="al-title">Tickets critiques</div>
            <div class="al-sub">Priorité max, non résolus</div>
        </td>
        <td class="al-cell i">
            <div class="al-num">{{ $alerts['expiring_warranty'] }}</div>
            <div class="al-title">Garanties expirant</div>
            <div class="al-sub">Dans les 30 prochains jours</div>
        </td>
    </tr>
</table>

{{-- ─── TOP PRODUITS + SAV ─── --}}
<div class="section-wrap">

    {{-- Top Produits --}}
    <div class="section-box" style="width:63%; margin-right:1.5%;">
        <div class="sec-title"><span class="sec-dot"></span>Top 10 produits par chiffre d'affaires</div>
        <table class="data">
            <thead>
                <tr>
                    <th style="width:22px;">#</th>
                    <th>Produit</th>
                    <th>Catégorie</th>
                    <th class="t-r">Qté</th>
                    <th class="t-r">CA (FCFA)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProducts as $i => $p)
                @php
                    $name      = is_array($p) ? $p['name']          : $p->name;
                    $cat       = is_array($p) ? ($p['category'] ?? '—') : ($p->category ?? '—');
                    $qty       = is_array($p) ? $p['qty']           : $p->qty;
                    $total     = is_array($p) ? $p['total']         : $p->total;
                    $rankClass = $i === 0 ? 'gold' : ($i === 1 ? 'silver' : ($i === 2 ? 'bronze' : ''));
                @endphp
                <tr>
                    <td><span class="rank {{ $rankClass }}">{{ $i + 1 }}</span></td>
                    <td class="t-b">{{ $name }}</td>
                    <td><span class="pill pill-b">{{ $cat }}</span></td>
                    <td class="t-r">{{ number_format($qty, 0, ',', ' ') }}</td>
                    <td class="t-r t-g">{{ number_format($total, 0, ',', ' ') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- SAV --}}
    <div class="section-box" style="width:34%;">
        <div class="sec-title"><span class="sec-dot" style="background:#0891b2;"></span>Performance SAV</div>

        <div class="sav-metric">
            <div class="sav-num">{{ $savPerformance['avg_repair_time_minutes'] }} min</div>
            <div class="sav-sub">temps moyen de réparation</div>
        </div>

        <table class="data">
            <thead>
                <tr>
                    <th>Technicien</th>
                    <th class="t-r">Tickets</th>
                    <th class="t-r">Taux</th>
                </tr>
            </thead>
            <tbody>
                @foreach($savPerformance['technicians'] as $t)
                @php
                    $tech     = is_array($t) ? $t : $t->toArray();
                    $rate     = (int) min($tech['completion_rate'], 100);
                    {{-- ✅ Arrondi au multiple de 5 → mappe sur classe CSS statique prog-w-XX --}}
                    $progStep = (int) round($rate / 5) * 5;
                    $pillCls  = $rate >= 75 ? 'pill-g' : 'pill-o';
                @endphp
                <tr>
                    <td class="t-b">{{ $tech['technician_name'] }}</td>
                    <td class="t-r">{{ $tech['tickets'] }}</td>
                    <td class="t-r">
                        <span class="pill {{ $pillCls }}">{{ $rate }}%</span>
                        <div class="prog-bg">
                            {{-- ✅ Classe statique au lieu de style="width:{{ }}%" --}}
                            <div class="prog-fill prog-w-{{ $progStep }}"></div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

{{-- ─── CATÉGORIES ─── --}}
<div class="section-box" style="width:100%; display:block;">
    <div class="sec-title">
        <span class="sec-dot" style="background:#f07d00;"></span>Répartition du CA par catégorie de produits
    </div>
    @php
        $totalCat = collect($categoryDistribution)->sum('total');
    @endphp
    <div>
        @foreach($categoryDistribution as $idx => $cat)
        @php
            $catArr  = is_array($cat) ? $cat : $cat->toArray();
            $pct     = $totalCat > 0 ? round(($catArr['total'] / $totalCat) * 100, 1) : 0;
            $colorIdx = $idx % 8;
            {{-- ✅ Classe statique cat-cX au lieu de style="border-top: 3px solid {{ $color }}" --}}
        @endphp
        <div class="cat-tile cat-c{{ $colorIdx }}">
            <div class="cat-pct">{{ $pct }}%</div>
            <div class="cat-name">{{ $catArr['category'] }}</div>
            <div class="cat-value">{{ number_format($catArr['total'], 0, ',', ' ') }} FCFA</div>
        </div>
        @endforeach
    </div>
</div>

{{-- ─── FOOTER ─── --}}
<div class="footer">
    <div class="footer-l">Document confidentiel — JR Computer Sarl ERP System · Module Analytics v3</div>
    <div class="footer-r">Rapport généré automatiquement le {{ $generated_at }} · Diffusion restreinte</div>
</div>

</body>
</html>