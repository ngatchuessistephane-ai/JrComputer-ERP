<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Factures – JR Computer Sarl</title>
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

        /* ─── BLOC FACTURE ─── */
        .invoice-block {
            margin: 0 40px 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            overflow: hidden;
            page-break-inside: avoid;
        }
        .invoice-header {
            background: #0d1f14;
            padding: 8px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
        }
        .invoice-header .ref {
            color: #fff;
            font-size: 12px;
            font-weight: 800;
        }
        .invoice-header .date {
            color: #6b8c77;
            font-size: 8px;
        }
        .invoice-header .right { text-align: right; }

        .invoice-meta {
            background: #f4faf6;
            padding: 6px 16px;
            border-bottom: 1px solid #dceae1;
            display: flex;
            flex-wrap: wrap;
            gap: 4px 16px;
            font-size: 9px;
            color: #4a6155;
        }
        .invoice-meta strong { color: #1a7a3c; }

        /* ─── TABLEAU FACTURE ─── */
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

        /* ─── TOTAUX FACTURE (ALIGNÉS À DROITE) ─── */
        .invoice-totals {
            padding: 10px 16px 10px;
            background: #fafcfb;
            border-top: 2px solid #dceae1;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .invoice-totals .total-line {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            width: 100%;
            padding: 3px 0;
            border-bottom: 1px dashed #e0e8e3;
        }

        .invoice-totals .total-line:last-of-type {
            border-bottom: none;
        }

        .invoice-totals .total-line .label {
            color: #4a6155;
            font-weight: 500;
            font-size: 10px;
            margin-right: 20px;
            min-width: 100px;
            text-align: right;
        }

        .invoice-totals .total-line .value {
            font-weight: 600;
            color: #1a1a1a;
            font-size: 10px;
            min-width: 140px;
            text-align: right;
            padding-right: 2px;
        }

        .invoice-totals .total-ttc-line {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            width: 100%;
            padding: 6px 0 2px;
            border-top: 2.5px solid #1a7a3c;
            margin-top: 4px;
        }

        .invoice-totals .total-ttc-line .label {
            font-weight: 800;
            color: #1a7a3c;
            font-size: 11px;
            margin-right: 20px;
            min-width: 100px;
            text-align: right;
        }

        .invoice-totals .total-ttc-line .value {
            font-weight: 800;
            color: #1a7a3c;
            font-size: 13px;
            min-width: 140px;
            text-align: right;
            padding-right: 2px;
        }

        /* ─── MONTANT EN LETTRES ─── */
        .amount-in-words {
            font-size: 8px;
            color: #666;
            padding: 4px 16px 2px;
            text-align: right;
            font-style: italic;
            background: #fafcfb;
        }
        .amount-in-words strong {
            color: #1a1a1a;
            font-style: normal;
        }

        /* ─── PAIEMENTS ─── */
        .payments-section {
            padding: 0 16px 10px;
            border-top: 1px solid #dceae1;
            background: #fafcfb;
        }
        .payments-section .pay-title {
            font-size: 8px;
            font-weight: 700;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 8px 0 4px;
            display: block;
        }
        .payment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 8.5px;
            color: #4a6155;
            padding: 2px 0;
            border-bottom: 1px dashed #e0e8e3;
        }
        .payment-item:last-child { border-bottom: none; }
        .payment-amount {
            font-weight: 700;
            color: #1a7a3c;
        }
        .payment-empty {
            font-size: 8px;
            color: #888;
            padding: 2px 0;
            font-style: italic;
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
        .status-paid      { background: #10b981; }
        .status-partial   { background: #f59e0b; }
        .status-draft     { background: #6b7280; }
        .status-sent      { background: #3b82f6; }
        .status-overdue   { background: #ef4444; }
        .status-cancelled { background: #6b7280; }

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
            .invoice-block { page-break-inside: avoid; }
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
            <span class="meta-title">LISTE DES FACTURES</span>
        </div>
        <div class="meta-period">Période : {{ $period_label ?? 'Toutes les factures' }}</div>
        <div class="meta-date">Généré le {{ $generated_at ?? now()->format('d/m/Y à H:i') }}</div>
    </div>

    {{-- ============================================================
         STATISTIQUES
    ============================================================ --}}
    @if($invoices->count() > 0)
    @php
        $stats = $stats ?? [
            'total' => $invoices->count(),
            'total_amount' => $invoices->sum('total'),
            'paid_count' => $invoices->where('status', 'paid')->count(),
            'overdue_count' => $invoices->where('status', 'overdue')->count(),
            'total_paid' => $invoices->sum('paid_amount'),
        ];
    @endphp
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
            <span class="stat-value warn">{{ $stats['paid_count'] }}</span>
            <span class="stat-label">✅ Payées</span>
        </div>
        <div class="stat">
            <span class="stat-value danger">{{ $stats['overdue_count'] }}</span>
            <span class="stat-label">⏰ En retard</span>
        </div>
        <div class="stat">
            <span class="stat-value">{{ number_format($stats['total_paid'], 0, ',', ' ') }}</span>
            <span class="stat-label">Règlements (FCFA)</span>
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
        @if(!empty($filters['status']))
            @php
                $statusMap = ['draft'=>'Brouillon','sent'=>'Envoyé','paid'=>'Payé','partial'=>'Partiel','overdue'=>'En retard','cancelled'=>'Annulé'];
            @endphp
            <span class="filter-tag orange">📌 {{ $statusMap[$filters['status']] ?? $filters['status'] }}</span>
        @endif
        @if(!empty($filters['customer_id']))
            <span class="filter-tag">👤 {{ \App\Models\Module3\Customer::find($filters['customer_id'])->name ?? 'Client' }}</span>
        @endif
        @if(empty($filters['date_from']) && empty($filters['date_to']) && empty($filters['status']) && empty($filters['customer_id']))
            <span style="color:#888; font-size: 9px;">Toutes les factures</span>
        @endif
        <span style="margin-left:auto; font-size: 9px; color: #888;">
            {{ $invoices->count() }} facture(s)
        </span>
    </div>

    {{-- ============================================================
         LISTE DES FACTURES
    ============================================================ --}}
    @forelse($invoices as $invoice)
    @php
        $statusLabels = [
            'paid' => 'Payée', 'partial' => 'Partielle', 'draft' => 'Brouillon',
            'sent' => 'Envoyée', 'overdue' => 'En retard', 'cancelled' => 'Annulée',
        ];
        $remaining = $invoice->total - $invoice->paid_amount;
    @endphp
    <div class="invoice-block">

        {{-- En-tête Facture --}}
        <div class="invoice-header">
            <div>
                <span class="ref">{{ $invoice->reference }}</span>
                <span class="date">📅 Émise le {{ $invoice->date->format('d/m/Y') }}</span>
            </div>
            <div class="right">
                <span class="status-badge status-{{ $invoice->status }}">
                    {{ $statusLabels[$invoice->status] ?? $invoice->status }}
                </span>
                <br><span style="color: #6b8c77; font-size: 7px;">⏳ Échéance : {{ $invoice->due_date->format('d/m/Y') }}</span>
            </div>
        </div>

        {{-- Meta Client --}}
        <div class="invoice-meta">
            <span><strong>👤 Client :</strong> {{ $invoice->customer->name ?? 'Client inconnu/supprimé' }}</span>
            @if($invoice->customer?->email)
                <span><strong>✉</strong> {{ $invoice->customer->email }}</span>
            @endif
            @if($invoice->customer?->phone)
                <span><strong>📞</strong> {{ $invoice->customer->phone }}</span>
            @endif
            @if($invoice->notes)
                <span><strong>📝</strong> {{ $invoice->notes }}</span>
            @endif
        </div>

        {{-- Articles --}}
        @if($invoice->items->count())
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
                    @foreach($invoice->items as $item)
                    <tr>
                        <td>
                            @if($item->product)
                                <span class="product-name">{{ $item->product->name }}</span>
                                <span class="product-ref">({{ $item->product->reference }})</span>
                            @elseif($item->description)
                                <span class="product-name">{{ $item->description }}</span>
                                <span class="product-ref">[Service]</span>
                            @else
                                <span class="product-name">📦 Produit / Service</span>
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

        {{-- Totaux (alignés à droite) --}}
        <div class="invoice-totals">
            <div class="total-line">
                <span class="label">Sous-total HT</span>
                <span class="value">{{ number_format($invoice->subtotal, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="total-line">
                <span class="label">TVA (19,25%)</span>
                <span class="value">{{ number_format($invoice->tax, 0, ',', ' ') }} FCFA</span>
            </div>
            @if($invoice->paid_amount > 0)
            <div class="total-line">
                <span class="label">Déjà réglé</span>
                <span class="value" style="color:#1a7a3c;">{{ number_format($invoice->paid_amount, 0, ',', ' ') }} FCFA</span>
            </div>
            @endif
            <div class="total-ttc-line">
                <span class="label">Total TTC</span>
                <span class="value">{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>

        {{-- Montant en lettres --}}
        <div class="amount-in-words">
            <strong>Arrêtée à la somme de :</strong>
            {{ number_format($invoice->total, 0, ',', ' ') }} Francs CFA TTC
        </div>

        {{-- Paiements --}}
        <div class="payments-section">
            <span class="pay-title">💳 Historique des paiements</span>
            @if($invoice->payments->count() > 0)
                @foreach($invoice->payments as $payment)
                <div class="payment-item">
                    <span>
                        {{ $payment->payment_date->format('d/m/Y') }} -
                        @switch($payment->method)
                            @case('cash') 💵 Espèces @break
                            @case('mtn_momo') 📱 MTN MoMo @break
                            @case('orange_money') 🟠 Orange Money @break
                            @case('bank_transfer') 🏦 Virement @break
                            @default {{ $payment->method }}
                        @endswitch
                    </span>
                    <span class="payment-amount">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</span>
                </div>
                @endforeach
            @else
                <div class="payment-empty">Aucun paiement enregistré</div>
            @endif
        </div>

        @else
        <div style="padding: 12px 16px; color: #888; text-align: center; font-size: 10px;">
            Aucun article dans cette facture
        </div>
        @endif

    </div>
    @empty
    <div class="empty-state">
        <span class="icon">📄</span>
        <div class="title">Aucune facture trouvée</div>
        <span class="sub">Aucune facture ne correspond aux filtres appliqués.</span>
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