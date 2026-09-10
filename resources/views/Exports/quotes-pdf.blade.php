<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Proformas – JR Computer Sarl</title>
    <style>
        /* ─── STYLES IDENTIQUES À PROFORMA-PDF ─── */
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
            font-size: 27px;
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

        /* ─── BLOC PROFORMA ─── */
        .proforma-block {
            margin: 0 40px 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            overflow: hidden;
            page-break-inside: avoid;
        }
        .proforma-header {
            background: #0d1f14;
            padding: 8px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
        }
        .proforma-header .ref {
            color: #fff;
            font-size: 12px;
            font-weight: 800;
        }
        .proforma-header .date {
            color: #6b8c77;
            font-size: 8px;
        }
        .proforma-header .right { text-align: right; }

        .proforma-meta {
            background: #f4faf6;
            padding: 6px 16px;
            border-bottom: 1px solid #dceae1;
            display: flex;
            flex-wrap: wrap;
            gap: 4px 16px;
            font-size: 9px;
            color: #4a6155;
        }
        .proforma-meta strong { color: #1a7a3c; }

        /* ─── TABLEAU PROFORMA ─── */
        .table-wrap {
            margin: 0;
            border: none;
            padding: 0 4px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .items-table thead th {
            border: 1px solid #222;
            border-top: none;
            padding: 8px 14px;
            font-weight: 700;
            font-size: 10px;
            text-align: left;
            background: #f5f5f5;
        }
        .items-table thead th:first-child { border-left: none; }
        .items-table thead th:last-child { border-right: none; }
        .items-table thead th.center { text-align: center; }
        .items-table thead th.right { text-align: right; }

        .items-table tbody td {
            border: 1px solid #222;
            border-top: none;
            border-bottom: none;
            padding: 8px 14px;
            vertical-align: top;
            color: #111;
        }
        .items-table tbody td:first-child { border-left: none; }
        .items-table tbody td:last-child { border-right: none; }
        .items-table tbody tr:last-child td { padding-bottom: 12px; }

        .items-table .product-name { font-weight: 600; }
        .items-table .product-ref { font-size: 9px; color: #666; }
        .items-table .center { text-align: center; }
        .items-table .right { text-align: right; }
        .items-table .empty-row td {
            color: #888;
            text-align: center;
            padding: 20px 12px;
            border-top: 1px solid #222;
        }

        /* ─── TOTAUX PROFORMA (ALIGNÉS À DROITE) ─── */
        .proforma-totals {
            padding: 10px 16px 10px;
            background: #fafcfb;
            border-top: 2px solid #dceae1;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .proforma-totals .total-line {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            width: 100%;
            padding: 3px 0;
            border-bottom: 1px dashed #e0e8e3;
        }

        .proforma-totals .total-line:last-of-type {
            border-bottom: none;
        }

        .proforma-totals .total-line .label {
            color: #4a6155;
            font-weight: 500;
            font-size: 10px;
            margin-right: 20px;
            min-width: 100px;
            text-align: right;
        }

        .proforma-totals .total-line .value {
            font-weight: 600;
            color: #1a1a1a;
            font-size: 10px;
            min-width: 140px;
            text-align: right;
            padding-right: 2px;
        }

        .proforma-totals .total-ttc-line {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            width: 100%;
            padding: 6px 0 2px;
            border-top: 2.5px solid #1a7a3c;
            margin-top: 4px;
        }

        .proforma-totals .total-ttc-line .label {
            font-weight: 800;
            color: #1a7a3c;
            font-size: 11px;
            margin-right: 20px;
            min-width: 100px;
            text-align: right;
        }

        .proforma-totals .total-ttc-line .value {
            font-weight: 800;
            color: #1a7a3c;
            font-size: 13px;
            min-width: 140px;
            text-align: right;
            padding-right: 2px;
        }

        /* ─── STATUS BADGE ─── */
        .status-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #fff;
        }
        .status-draft    { background: #6b7280; }
        .status-sent     { background: #3b82f6; }
        .status-accepted { background: #10b981; }
        .status-rejected { background: #ef4444; }
        .status-converted { background: #8b5cf6; }

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
            .proforma-block { page-break-inside: avoid; }
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
            <span class="meta-title">LISTE DES PROFORMAS</span>
        </div>
        <div class="meta-period">Période : {{ $period_label }}</div>
        <div class="meta-date">Généré le {{ $generated_at }}</div>
    </div>

    {{-- ============================================================
         STATISTIQUES
    ============================================================ --}}
    @if($quotes->count() > 0)
    <div class="stats-row">
        <div class="stat">
            <span class="stat-value">{{ $stats['total'] }}</span>
            <span class="stat-label">Total</span>
        </div>
        <div class="stat">
            <span class="stat-value">{{ number_format($stats['total_amount'], 0, ',', ' ') }}</span>
            <span class="stat-label">Montant Total (FCFA)</span>
        </div>
        <div class="stat">
            <span class="stat-value warn">{{ $stats['sent_count'] }}</span>
            <span class="stat-label">📤 Envoyées</span>
        </div>
        <div class="stat">
            <span class="stat-value">{{ $stats['accepted_count'] }}</span>
            <span class="stat-label">✅ Acceptées</span>
        </div>
        <div class="stat">
            <span class="stat-value danger">{{ $stats['rejected_count'] }}</span>
            <span class="stat-label">❌ Rejetées</span>
        </div>
    </div>
    @endif

    {{-- ============================================================
         FILTRES
    ============================================================ --}}
    <div class="filters-bar">
        <span class="filter-label">📋 Filtres :</span>
        @if(!empty($filters['date_from']) || !empty($filters['date_to']))
            <span class="filter-tag">📅 {{ $period_label }}</span>
        @endif
        @if(!empty($filters['status']))
            @php
                $statusMap = ['draft'=>'Brouillon','sent'=>'Envoyé','accepted'=>'Accepté','rejected'=>'Rejeté','converted'=>'Converti'];
            @endphp
            <span class="filter-tag orange">📌 {{ $statusMap[$filters['status']] ?? $filters['status'] }}</span>
        @endif
        @if(!empty($filters['customer_id']))
            <span class="filter-tag">👤 {{ \App\Models\Module3\Customer::find($filters['customer_id'])->name ?? 'Client' }}</span>
        @endif
        @if(empty($filters['date_from']) && empty($filters['date_to']) && empty($filters['status']) && empty($filters['customer_id']))
            <span style="color:#888; font-size: 9px;">Toutes les Proformas</span>
        @endif
        <span style="margin-left:auto; font-size: 9px; color: #888;">
            {{ $quotes->count() }} proforma(s)
        </span>
    </div>

    {{-- ============================================================
         LISTE DES PROFORMAS
    ============================================================ --}}
    @forelse($quotes as $quote)
    <div class="proforma-block">

        {{-- En-tête Proforma --}}
        <div class="proforma-header">
            <div>
                <span class="ref">{{ $quote->reference }}</span>
                <span class="date">📅 Émise le {{ $quote->date->format('d/m/Y') }}</span>
            </div>
            <div class="right">
                @php
                    $statusLabels = [
                        'draft' => 'Brouillon', 'sent' => 'Envoyé', 'accepted' => 'Accepté',
                        'rejected' => 'Rejeté', 'converted' => 'Converti',
                    ];
                @endphp
                <span class="status-badge status-{{ $quote->status }}">
                    {{ $statusLabels[$quote->status] ?? $quote->status }}
                </span>
                @if($quote->valid_until)
                    <br><span style="color: #6b8c77; font-size: 7px;">⏳ Valable jusqu’au {{ $quote->valid_until->format('d/m/Y') }}</span>
                @endif
            </div>
        </div>

        {{-- Meta Client --}}
        <div class="proforma-meta">
            <span><strong>👤 Client :</strong> {{ $quote->customer->name ?? '—' }}</span>
            @if($quote->customer?->email)
                <span><strong>✉</strong> {{ $quote->customer->email }}</span>
            @endif
            @if($quote->customer?->phone)
                <span><strong>📞</strong> {{ $quote->customer->phone }}</span>
            @endif
            @if($quote->notes)
                <span><strong>📝</strong> {{ $quote->notes }}</span>
            @endif
        </div>

        {{-- Articles --}}
        @if($quote->items->count())
        <div class="table-wrap">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width:45%;">Désignation</th>
                        <th class="center" style="width:12%;">Qté</th>
                        <th class="right" style="width:20%;">P.U HT</th>
                        <th class="right" style="width:23%;">P.T HT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quote->items as $item)
                    <tr>
                        <td>
                            <span class="product-name">{{ $item->product->name ?? $item->description ?? 'Produit' }}</span>
                            @if(isset($item->product->reference))
                                <span class="product-ref">({{ $item->product->reference }})</span>
                            @endif
                            @if($item->description && !$item->product)
                                <span class="product-ref">(Service)</span>
                            @endif
                        </td>
                        <td class="center">{{ $item->quantity }}</td>
                        <td class="right">{{ number_format($item->unit_price, 0, ',', ' ') }}</td>
                        <td class="right">{{ number_format($item->total, 0, ',', ' ') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totaux (alignés à droite avec le PT HT) --}}
        <div class="proforma-totals">
            <div class="total-line">
                <span class="label">Sous-total HT</span>
                <span class="value">{{ number_format($quote->subtotal, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="total-line">
                <span class="label">TVA (19,25%)</span>
                <span class="value">{{ number_format($quote->tax, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="total-ttc-line">
                <span class="label">Total TTC</span>
                <span class="value">{{ number_format($quote->total, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>
        @else
        <div style="padding: 12px 16px; color: #888; text-align: center; font-size: 10px;">
            Aucun article dans cette Proforma
        </div>
        @endif

    </div>
    @empty
    <div class="empty-state">
        <span class="icon">📄</span>
        <div class="title">Aucune Proforma trouvée</div>
        <span class="sub">Aucune Proforma ne correspond aux filtres appliqués.</span>
    </div>
    @endforelse

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