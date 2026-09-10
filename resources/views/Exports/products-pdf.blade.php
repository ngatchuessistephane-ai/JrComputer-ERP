<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catalogue Produits – JR Computer Sarl</title>
    <style>
        /* ─── STYLES UNIFORMES AVEC INVOICES-PDF ET QUOTES-PDF ─── */
        /* ✅ SUPPRESSION de l'import Google Fonts (bloquant pour DomPDF) */

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', 'Helvetica', Arial, sans-serif;
            background: #ffffff;
            color: #1a1a1a;
            padding: 20px;
            font-size: 11px;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
            background: #ffffff;
        }

        /* ─── EN-TÊTE / LETTERHEAD ─── */
        .letterhead {
            padding: 28px 40px 14px;
            border-bottom: 1px solid #ccc;
        }
        .letterhead-row {
            display: flex;
            align-items: center;
            gap: 18px;
        }
        .letterhead-logo {
            width: 78px;
            height: 78px;
            border-radius: 50%;
            flex-shrink: 0;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #bbb;
            overflow: hidden;
        }
        .letterhead-logo img { width: 100%; height: 100%; object-fit: cover; }
        .letterhead-logo .fallback { font-size: 24px; font-weight: 900; color: #2f6b3f; font-family: 'DejaVu Sans', sans-serif; }
        .letterhead-text { flex: 1; text-align: center; }
        .letterhead-text .brand-tagline {
            font-family: 'DejaVu Sans', 'Helvetica', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: #8a8a8a;
            letter-spacing: 0.3px;
            font-style: italic;
        }
        .letterhead-text .brand-meta {
            font-size: 9.5px;
            color: #666;
            margin-top: 2px;
            line-height: 1.5;
        }
        .letterhead-text .brand-meta strong { color: #444; font-weight: 600; }

        /* ─── ZONE DATE / TITRE ─── */
        .meta-zone { padding: 18px 40px 0; }
        .meta-title {
            text-align: center;
            font-size: 19px;
            font-weight: 800;
            letter-spacing: 0.3px;
            color: #111;
            text-transform: uppercase;
            margin-bottom: 6px;
            padding: 6px 14px 6px 4px;
            background: #ececec;
            display: inline-block;
        }
        .meta-period {
            text-align: center;
            font-size: 12px;
            color: #555;
            margin-bottom: 12px;
        }
        .meta-date {
            text-align: right;
            font-size: 10px;
            color: #666;
            margin-bottom: 12px;
        }

        /* ─── STATS ROW ─── */
        .stats-row {
            display: table;
            width: calc(100% - 80px);
            margin: 0 40px 16px;
            border: 1px solid #222;
            border-radius: 4px;
            overflow: hidden;
        }
        .stats-row .stat {
            display: table-cell;
            text-align: center;
            padding: 8px 10px;
            width: 20%;
            border-right: 1px solid #222;
        }
        .stats-row .stat:last-child { border-right: none; }
        .stats-row .stat-value {
            font-size: 18px;
            font-weight: 800;
            color: #1a7a3c;
            display: block;
            line-height: 1.2;
        }
        .stats-row .stat-value.warn { color: #f07d00; }
        .stats-row .stat-value.danger { color: #dc2626; }
        .stats-row .stat-label {
            font-size: 7.5px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
            margin-top: 2px;
            display: block;
        }

        /* ─── FILTRES ─── */
        .filters-bar {
            margin: 0 40px 14px;
            border: 1px solid #ccc;
            border-left: 3px solid #f07d00;
            padding: 6px 12px;
            font-size: 10px;
            color: #555;
            display: flex;
            flex-wrap: wrap;
            gap: 4px 14px;
            background: #f9f9f9;
        }
        .filters-bar .filter-tag {
            background: #1a7a3c;
            color: #fff;
            font-size: 8px;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 20px;
            letter-spacing: 0.3px;
        }
        .filters-bar .filter-tag.orange { background: #f07d00; }
        .filters-bar .filter-label { font-weight: 700; color: #1a1a1a; }

        /* ─── TABLE ─── */
        .table-wrap {
            margin: 0 40px 16px;
        }

        .table-title {
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 6px;
        }

        /* ✅ TABLEAU CLAIR AVEC LIGNES DE SÉPARATION VISIBLES */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            border: 1.5px solid #111;
        }

        /* ✅ Lignes d'en-tête épaisses */
        .items-table thead th {
            border: 1.5px solid #111;
            padding: 10px 14px;
            font-weight: 700;
            font-size: 10px;
            text-align: left;
            background: #e8ece8;
            color: #0d1f14;
        }
        .items-table thead th:first-child { border-left: none; }
        .items-table thead th:last-child { border-right: none; }

        .items-table thead th.center { text-align: center; }
        .items-table thead th.right { text-align: right; }

        /* ✅ Lignes de corps nettes et visibles */
        .items-table tbody td {
            border: 1px solid #222;
            padding: 9px 14px;
            vertical-align: middle;
            color: #111;
            background: #ffffff;
        }
        .items-table tbody td:first-child { border-left: none; }
        .items-table tbody td:last-child { border-right: none; }

        /* ✅ Ligne de séparation entre chaque ligne (hors dernière) */
        .items-table tbody tr:not(:last-child) td {
            border-bottom: 1.5px solid #444;
        }

        /* ✅ Alternance légère pour la lisibilité */
        .items-table tbody tr:nth-child(even) td {
            background: #f7f9f7;
        }
        .items-table tbody tr:nth-child(odd) td {
            background: #ffffff;
        }

        .items-table tbody tr:last-child td {
            border-bottom: 1.5px solid #111;
        }

        .items-table .product-name { font-weight: 600; }
        .items-table .product-ref {
            font-size: 9px;
            display: inline-block;
            background: #1a7a3c;
            color: #fff;
            padding: 2px 10px;
            border-radius: 12px;
            font-weight: 600;
        }
        .items-table .center { text-align: center; }
        .items-table .right { text-align: right; }

        .items-table .empty-row td {
            color: #888;
            text-align: center;
            padding: 30px 12px;
            border-top: 1.5px solid #111;
            font-style: italic;
        }

        .cat-badge {
            display: inline-block;
            background: #e8f5ee;
            color: #1a7a3c;
            font-size: 8px;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 20px;
            border: 1px solid #c0dfc9;
        }

        .low-badge {
            display: inline-block;
            background: #fff3e0;
            color: #f07d00;
            font-size: 7px;
            font-weight: 700;
            padding: 1px 8px;
            border-radius: 12px;
            margin-left: 4px;
            border: 1px solid #fde8c0;
        }

        .qty-low { color: #f07d00; font-weight: 700; }
        .qty-ok  { color: #1a7a3c; font-weight: 700; }

        /* ─── PIED DE PAGE ─── */
        .footer {
            margin-top: 22px;
            border-top: 1px solid #ccc;
            padding: 16px 40px 18px;
        }
        .footer-brand-row { display: flex; align-items: center; justify-content: center; gap: 14px; }
        .footer-logo {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            border: 1px solid #bbb;
            overflow: hidden;
            flex-shrink: 0;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .footer-logo img { width: 100%; height: 100%; object-fit: cover; }
        .footer-logo .fallback { font-size: 16px; font-weight: 900; color: #2f6b3f; font-family: 'DejaVu Sans', sans-serif; }
        .footer-brand-text { font-size: 10px; line-height: 1.5; color: #333; text-align: center; }
        .footer-brand-text .name { font-weight: 700; font-size: 11px; }
        .footer-tagline {
            text-align: center;
            font-size: 11px;
            font-style: italic;
            font-weight: 600;
            color: #f07d00;
            margin: 12px 0 12px;
        }
        .partners-row {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 22px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
        .partners-row img {
            height: 24px;
            width: auto;
            object-fit: contain;
            opacity: 0.85;
            filter: grayscale(15%);
        }
        .footer-legal { text-align: center; font-size: 8px; color: #999; margin-top: 10px; }

        /* ─── EMPTY STATE ─── */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #888;
        }
        .empty-state .icon { font-size: 32px; display: block; margin-bottom: 8px; }
        .empty-state .title { font-size: 12px; font-weight: 700; color: #1a1a1a; }
        .empty-state .sub { font-size: 10px; color: #888; }

        /* ─── PRINT ─── */
        @media print {
            body { background: #ffffff; padding: 10px; }
            .items-table { page-break-inside: avoid; }
        }
    </style>
</head>
<body>

<div class="container">

    {{-- ============================================================
         EN-TÊTE / LETTERHEAD
    ============================================================ --}}
    <div class="letterhead">
        <div class="letterhead-row">
            <div class="letterhead-logo">
                @php
                    $logoPath = public_path('images/logo-jr.jpg');
                    $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
                @endphp
                @if($logoData)
                    <img src="data:image/jpeg;base64,{{ $logoData }}" alt="JR Computer">
                @else
                    <span class="fallback">JR</span>
                @endif
            </div>
            <div class="letterhead-text">
                <div class="brand-tagline">Ingénierie Informatique &amp; Télécommunications</div>
                <div class="brand-meta">
                    1390, Boulevard de la République, BP 5226 Douala &nbsp;/&nbsp; infos@jr-computer.net &nbsp;/&nbsp; www.jrcomputersarl.com<br>
                    Régime : Réel &nbsp;-&nbsp; N° Cont : M020900027385T &nbsp;-&nbsp; R.C : 09/B.732 &nbsp;-&nbsp; CNPS : 351-0109022-N<br>
                    Tél. : 2 33 42 21 53 / 6 99 96 96 08 / 6 99 00 38 38
                </div>
            </div>
            <div style="width:78px; flex-shrink:0;"></div>
        </div>
    </div>

    {{-- ============================================================
         TITRE
    ============================================================ --}}
    <div class="meta-zone">
        <div style="text-align: center;">
            <span class="meta-title">CATALOGUE DES PRODUITS</span>
        </div>
        <div class="meta-period">{{ $period_label ?? 'Inventaire complet' }}</div>
        <div class="meta-date">Généré le {{ $generated_at ?? now()->format('d/m/Y à H:i') }}</div>
    </div>

    {{-- ============================================================
         STATISTIQUES
    ============================================================ --}}
    @if($products->count() > 0)
    @php
        $stats = $stats ?? [
            'total' => $products->count(),
            'low_stock' => $products->filter(fn($p) => $p->quantity <= $p->alert_threshold)->count(),
            'total_units' => $products->sum('quantity'),
            'categories' => $products->unique('category')->count(),
        ];
    @endphp
    <div class="stats-row">
        <div class="stat">
            <span class="stat-value">{{ $stats['total'] }}</span>
            <span class="stat-label">Produits</span>
        </div>
        <div class="stat">
            <span class="stat-value warn">{{ $stats['low_stock'] }}</span>
            <span class="stat-label">⚠ Stock bas</span>
        </div>
        <div class="stat">
            <span class="stat-value">{{ $stats['total_units'] }}</span>
            <span class="stat-label">Unités totales</span>
        </div>
        <div class="stat">
            <span class="stat-value">{{ $stats['categories'] }}</span>
            <span class="stat-label">Catégories</span>
        </div>
    </div>
    @endif

    {{-- ============================================================
         FILTRES
    ============================================================ --}}
    <div class="filters-bar">
        <span class="filter-label">📋 Filtres :</span>
        @if(!empty($filters['date_from']) || !empty($filters['date_to']))
            <span class="filter-tag">📅 {{ $period_label ?? 'Période définie' }}</span>
        @endif
        @if(!empty($filters['price_min']))
            <span class="filter-tag orange">≥ {{ number_format($filters['price_min'],0,',',' ') }} FCFA</span>
        @endif
        @if(!empty($filters['price_max']))
            <span class="filter-tag orange">≤ {{ number_format($filters['price_max'],0,',',' ') }} FCFA</span>
        @endif
        @if(!empty($filters['category']))
            <span class="filter-tag orange">{{ $filters['category'] }}</span>
        @endif
        @if(!empty($filters['supplier']))
            <span class="filter-tag orange">{{ $filters['supplier'] }}</span>
        @endif
        @if(!empty($filters['stock_status']))
            <span class="filter-tag orange">Stock : {{ $filters['stock_status'] === 'low' ? '⚠ Bas' : '✓ Normal' }}</span>
        @endif
        @if(empty($filters['date_from']) && empty($filters['date_to']) && empty($filters['price_min']) && empty($filters['price_max']) && empty($filters['category']) && empty($filters['supplier']) && empty($filters['stock_status']))
            <span style="color:#888; font-size: 9px;">Tous les produits</span>
        @endif
        <span style="margin-left:auto; font-size: 9px; color: #888;">
            {{ $products->count() }} produit(s)
        </span>
    </div>

    {{-- ============================================================
         TABLEAU DES PRODUITS
    ============================================================ --}}
    <div class="table-wrap">
        <div class="table-title">📦 Inventaire complet</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:100px;">Référence</th>
                    <th style="width:30%;">Nom du produit</th>
                    <th style="width:100px;">Catégorie</th>
                    <th class="right" style="width:120px;">Prix vente</th>
                    <th class="center" style="width:70px;">Stock</th>
                    <th class="center" style="width:60px;">Seuil</th>
                    <th style="width:110px;">Fournisseur</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                <tr>
                    <td><span class="product-ref">{{ $p->reference }}</span></td>
                    <td class="product-name">{{ $p->name }}</td>
                    <td>
                        @if($p->category)
                            <span class="cat-badge">{{ $p->category }}</span>
                        @else
                            <span style="color:#ccc;">—</span>
                        @endif
                    </td>
                    <td class="right">
                        <strong>{{ number_format($p->selling_price, 0, ',', ' ') }}</strong> FCFA
                    </td>
                    <td class="center {{ $p->quantity <= $p->alert_threshold ? 'qty-low' : 'qty-ok' }}">
                        {{ $p->quantity }}
                        @if($p->quantity <= $p->alert_threshold)
                            <span class="low-badge">⚠ Bas</span>
                        @endif
                    </td>
                    <td class="center">{{ $p->alert_threshold }}</td>
                    <td>{{ $p->supplier ?? '—' }}</td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="7">Aucun produit correspondant aux filtres appliqués.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ============================================================
         PIED DE PAGE
    ============================================================ --}}
    <div class="footer">
        <div class="footer-brand-row">
            <div class="footer-logo">
                @if($logoData)
                    <img src="data:image/jpeg;base64,{{ $logoData }}" alt="JR Computer">
                @else
                    <span class="fallback">JR</span>
                @endif
            </div>
            <div class="footer-brand-text">
                <div class="name">Ingénierie Informatique &amp; Télécommunications</div>
                <div>BP 5226 Douala &nbsp;·&nbsp; Tél : 2 33 42 21 53 / 6 99 96 96 08</div>
                <div>infos@jr-computer.net &nbsp;·&nbsp; www.jrcomputersarl.com</div>
            </div>
        </div>

        <div class="footer-tagline">Une équipe d'ingénieurs expérimentés et qualifiés pour vous servir</div>

        <div class="partners-row">
            <img src="{{ asset('images/ubiquiti.jfif') }}" alt="Ubiquiti">
            <img src="{{ asset('images/kaspersky.jfif') }}" alt="Kaspersky">
            <img src="{{ asset('images/hikvision.png') }}" alt="Hikvision">
            <img src="{{ asset('images/cisco.png') }}" alt="Cisco">
            <img src="{{ asset('images/apc.png') }}" alt="APC">
            <img src="{{ asset('images/idirect.jfif') }}" alt="iDirect">
            <img src="{{ asset('images/dell.png') }}" alt="Dell">
            <img src="{{ asset('images/hp.png') }}" alt="HP">
            <img src="{{ asset('images/alhua.jfif') }}" alt="alhua">
        </div>

        <div class="footer-legal">Document généré par JRC-ERP System · Fait à Douala, le {{ now()->locale('fr')->translatedFormat('d F Y') }}</div>
    </div>

</div>

</body>
</html>