<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catalogue Produits – JR Computer Sarl</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #0d1f14;
            background: #ffffff;
        }

        /* ── TOP ACCENT ── */
        .top-bar {
            height: 4px;
            background: linear-gradient(90deg, #f07d00 0%, #f5a623 30%, #1a7a3c 60%, #0f5229 100%);
        }

        /* ── HEADER ── */
        .header {
            padding: 22px 32px 18px;
            background: #ffffff;
            position: relative;
            overflow: hidden;
        }

        /* Decorative circle BG */
        .header::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(26,122,60,0.06) 0%, transparent 70%);
        }

        .header-inner { overflow: hidden; position: relative; z-index: 1; }

        .logo-section { float: left; width: 50%; }

        .logo-img {
            height: 48px;
            width: auto;
            display: block;
            margin-bottom: 0;
        }

        .meta-section {
            float: right;
            width: 46%;
            text-align: right;
            padding-top: 8px;
        }

        .doc-badge {
            display: inline-block;
            background: #0d1f14;
            color: #ffffff;
            font-size: 7px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 2px;
            margin-bottom: 6px;
        }

        .doc-title {
            font-size: 18px;
            font-weight: 800;
            color: #0d1f14;
            line-height: 1.15;
            margin-bottom: 4px;
        }

        .doc-meta {
            font-size: 8px;
            color: #7a9185;
        }

        .doc-meta strong { color: #f07d00; font-weight: 700; }

        /* ── DIVIDER ── */
        .divider {
            margin: 0 32px;
            height: 1px;
            background: linear-gradient(90deg, #1a7a3c 0%, #d4e8da 60%, transparent 100%);
        }

        /* ── FILTERS ── */
        .filters-block {
            margin: 14px 32px 0;
            background: #fffbf4;
            border: 1px solid #fde8c0;
            border-left: 4px solid #f07d00;
            border-radius: 6px;
            padding: 9px 14px;
            font-size: 8.5px;
            color: #7a4800;
        }

        .filters-label {
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #f07d00;
            margin-bottom: 5px;
        }

        .filter-tag {
            display: inline-block;
            background: #f07d00;
            color: #fff;
            font-size: 7.5px;
            font-weight: 600;
            padding: 2px 9px;
            border-radius: 20px;
            margin: 0 4px 3px 0;
        }

        /* ── STATS ── */
        .stats-bar {
            margin: 14px 32px 0;
            overflow: hidden;
            display: table;
            width: calc(100% - 64px);
            border: 1px solid #d4e8da;
            border-radius: 8px;
            background: #f4faf6;
        }

        .stat-item {
            display: table-cell;
            text-align: center;
            padding: 14px 10px;
            width: 25%;
        }

        .stat-item + .stat-item {
            border-left: 1px solid #d4e8da;
        }

        .stat-icon {
            font-size: 14px;
            display: block;
            margin-bottom: 4px;
            line-height: 1;
        }

        .stat-value {
            font-size: 20px;
            font-weight: 800;
            color: #1a7a3c;
            display: block;
            line-height: 1.1;
        }

        .stat-value.warn { color: #f07d00; }

        .stat-label {
            font-size: 7px;
            color: #7a9185;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            font-weight: 600;
            margin-top: 2px;
            display: block;
        }

        /* ── TABLE ── */
        .table-wrap {
            margin: 16px 32px 0;
        }

        .table-title {
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #7a9185;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #e0ede5;
            border-radius: 8px;
            overflow: hidden;
        }

        thead tr {
            background: #0d1f14;
        }

        thead th {
            color: #ffffff;
            padding: 9px 12px;
            text-align: left;
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        thead th.right { text-align: right; }
        thead th.center { text-align: center; }

        tbody tr:nth-child(even) { background: #f7fbf8; }
        tbody tr:nth-child(odd)  { background: #ffffff; }
        tbody tr { border-bottom: 1px solid #edf5f0; }
        tbody tr:last-child { border-bottom: none; }

        td {
            padding: 7px 12px;
            color: #0d1f14;
            vertical-align: middle;
        }

        .ref-badge {
            display: inline-block;
            background: #1a7a3c;
            color: #fff;
            font-size: 7px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }

        .cat-badge {
            display: inline-block;
            background: #e8f5ee;
            color: #1a7a3c;
            font-size: 7.5px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
            border: 1px solid #c0dfc9;
        }

        td.product-name {
            font-weight: 600;
            color: #0d1f14;
        }

        .price {
            text-align: right;
            font-weight: 700;
            color: #0d1f14;
            font-variant-numeric: tabular-nums;
        }

        .price-currency {
            font-size: 7px;
            font-weight: 500;
            color: #7a9185;
            margin-left: 2px;
        }

        .qty-ok {
            text-align: center;
            color: #1a7a3c;
            font-weight: 700;
        }

        .qty-low {
            text-align: center;
            color: #f07d00;
            font-weight: 700;
        }

        .low-badge {
            display: inline-block;
            background: #fff3e0;
            color: #f07d00;
            font-size: 6.5px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 8px;
            margin-left: 4px;
            border: 1px solid #fde8c0;
        }

        .threshold {
            text-align: center;
            color: #a0b8a8;
            font-size: 9px;
        }

        .supplier-text {
            color: #4a6155;
            font-size: 9px;
        }

        .empty-row td {
            text-align: center;
            color: #a0b8a8;
            padding: 30px;
            font-style: italic;
        }

        /* ── FOOTER ── */
        .footer {
            margin-top: 22px;
            padding: 0 32px 0;
            overflow: hidden;
        }

        .footer-divider {
            height: 1px;
            background: linear-gradient(90deg, #f07d00 0%, #1a7a3c 50%, transparent 100%);
            margin-bottom: 10px;
        }

        .footer-inner {
            overflow: hidden;
            padding-bottom: 10px;
        }

        .footer-left {
            float: left;
            color: #7a9185;
            font-size: 7.5px;
        }

        .footer-left strong { color: #0d1f14; font-weight: 700; }

        .footer-right {
            float: right;
            color: #f07d00;
            font-size: 7.5px;
            font-weight: 700;
        }

        .footer-bottom {
            background: #0d1f14;
            margin-top: 0;
            padding: 8px 32px;
            overflow: hidden;
        }

        .fb-left {
            float: left;
            color: #4a6155;
            font-size: 7px;
        }

        .fb-left strong { color: #a0c8ae; }

        .fb-right {
            float: right;
            color: #f07d00;
            font-size: 7px;
            font-weight: 700;
        }

        .clearfix::after { content: ''; display: table; clear: both; }
    </style>
</head>
<body>

    <div class="top-bar"></div>

    <div class="header">
        <div class="header-inner clearfix">
            <div class="logo-section">
                @php
                    $logoPath = public_path('images/logo-jr.jpg');
                    $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
                @endphp
                @if($logoData)
                    <img src="data:image/jpeg;base64,{{ $logoData }}" class="logo-img" alt="JR Computer">
                @else
                    <div style="font-size:22px;font-weight:800;color:#1a7a3c;">JR Computer</div>
                    <div style="font-size:8px;color:#f07d00;font-weight:700;letter-spacing:2px;text-transform:uppercase;">Sarl · ERP System</div>
                @endif
            </div>
            <div class="meta-section">
                <div class="doc-badge">📦 Catalogue</div>
                <div class="doc-title">Liste des Produits</div>
                <div class="doc-meta">Généré le <strong>{{ $generated_at }}</strong></div>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    @if(count($filters))
    <div class="filters-block">
        <div class="filters-label">Filtres appliqués</div>
        @if(!empty($filters['date_from']))<span class="filter-tag">📅 Depuis {{ $filters['date_from'] }}</span>@endif
        @if(!empty($filters['date_to']))<span class="filter-tag">📅 Jusqu'au {{ $filters['date_to'] }}</span>@endif
        @if(!empty($filters['price_min']))<span class="filter-tag">Prix ≥ {{ number_format($filters['price_min'],0,',',' ') }} FCFA</span>@endif
        @if(!empty($filters['price_max']))<span class="filter-tag">Prix ≤ {{ number_format($filters['price_max'],0,',',' ') }} FCFA</span>@endif
        @if(!empty($filters['category']))<span class="filter-tag">{{ $filters['category'] }}</span>@endif
        @if(!empty($filters['supplier']))<span class="filter-tag">{{ $filters['supplier'] }}</span>@endif
        @if(!empty($filters['stock_status']))<span class="filter-tag">Stock : {{ $filters['stock_status'] === 'low' ? '⚠ Bas' : '✓ Normal' }}</span>@endif
    </div>
    @endif

    <div class="stats-bar">
        <div class="stat-item">
            <span class="stat-value">{{ $products->count() }}</span>
            <span class="stat-label">Produits</span>
        </div>
        <div class="stat-item">
            <span class="stat-value warn">{{ $products->filter(fn($p) => $p->quantity <= $p->alert_threshold)->count() }}</span>
            <span class="stat-label">Stock bas</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">{{ $products->sum('quantity') }}</span>
            <span class="stat-label">Unités totales</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">{{ $products->unique('category')->count() }}</span>
            <span class="stat-label">Catégories</span>
        </div>
    </div>

    <div class="table-wrap">
        <div class="table-title">Inventaire complet</div>
        <table>
            <thead>
                <tr>
                    <th style="width:90px;">Référence</th>
                    <th>Nom du produit</th>
                    <th style="width:90px;">Catégorie</th>
                    <th class="right" style="width:110px;">Prix vente</th>
                    <th class="center" style="width:70px;">Stock</th>
                    <th class="center" style="width:60px;">Seuil</th>
                    <th style="width:100px;">Fournisseur</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                <tr>
                    <td><span class="ref-badge">{{ $p->reference }}</span></td>
                    <td class="product-name">{{ $p->name }}</td>
                    <td>
                        @if($p->category)
                            <span class="cat-badge">{{ $p->category }}</span>
                        @else
                            <span style="color:#c0d4c9;">—</span>
                        @endif
                    </td>
                    <td class="price">
                        {{ number_format($p->selling_price, 0, ',', ' ') }}<span class="price-currency">FCFA</span>
                    </td>
                    <td class="{{ $p->quantity <= $p->alert_threshold ? 'qty-low' : 'qty-ok' }}">
                        {{ $p->quantity }}
                        @if($p->quantity <= $p->alert_threshold)
                            <span class="low-badge">⚠ Bas</span>
                        @endif
                    </td>
                    <td class="threshold">{{ $p->alert_threshold }}</td>
                    <td class="supplier-text">{{ $p->supplier ?? '—' }}</td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="7">Aucun produit correspondant aux filtres appliqués.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <div class="footer-divider"></div>
        <div class="footer-inner clearfix">
            <div class="footer-left">
                <strong>JR Computer Sarl</strong> &nbsp;·&nbsp; Document confidentiel &nbsp;·&nbsp; Usage interne uniquement
            </div>
            <div class="footer-right">ERP System · {{ now()->format('Y') }}</div>
        </div>
    </div>

    <div class="footer-bottom clearfix">
        <div class="fb-left"><strong>JR Computer</strong> · Douala, Cameroun</div>
        <div class="fb-right">Page catalogue · Généré automatiquement</div>
    </div>

</body>
</html>