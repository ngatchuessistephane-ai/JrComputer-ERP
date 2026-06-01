<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Factures – JR Computer Sarl</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #0d1f14;
            background: #f9fbfa;
        }

        .top-bar {
            height: 5px;
            background: linear-gradient(90deg, #f07d00 0%, #f5a623 30%, #1a7a3c 60%, #0f5229 100%);
        }

        .header {
            background: #ffffff;
            padding: 22px 32px 18px;
            border-bottom: 1px solid #e0ede5;
            position: relative;
            overflow: hidden;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: -40px;
            right: -40px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(240,125,0,0.07) 0%, transparent 70%);
        }

        .header-inner { overflow: hidden; position: relative; z-index: 1; }
        .logo-section { float: left; width: 50%; }
        .meta-section { float: right; width: 46%; text-align: right; padding-top: 6px; }

        .logo-img {
            height: 50px;
            width: auto;
            display: block;
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

        .divider {
            height: 1px;
            background: linear-gradient(90deg, #1a7a3c 0%, #d4e8da 60%, transparent 100%);
            margin: 0 32px;
        }

        .stats-section {
            margin: 16px 32px 0;
        }

        .stats-grid {
            display: table;
            width: 100%;
            border: 1px solid #d4e8da;
            border-radius: 10px;
            background: #ffffff;
            overflow: hidden;
        }

        .stat-item {
            display: table-cell;
            text-align: center;
            padding: 16px 10px;
        }

        .stat-item + .stat-item { border-left: 1px solid #e8f2eb; }

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
            margin-top: 3px;
            display: block;
        }

        .section-title {
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #7a9185;
            margin: 18px 32px 10px;
        }

        .invoice-card {
            margin: 0 32px 20px;
            background: #ffffff;
            border: 1px solid #dceae1;
            border-radius: 12px;
            overflow: hidden;
            page-break-inside: avoid;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .card-header {
            background: #0d1f14;
            padding: 12px 18px;
            overflow: hidden;
        }

        .card-header-left { float: left; }
        .card-header-right { float: right; text-align: right; }

        .invoice-ref {
            color: #ffffff;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.5px;
            display: block;
        }

        .invoice-date {
            color: #6b8c77;
            font-size: 8px;
            margin-top: 2px;
            display: block;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 7.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .status-paid      { background: #d1fae5; color: #065f46; }
        .status-partial   { background: #ffedd5; color: #9a3412; }
        .status-draft     { background: #f1f5f9; color: #475569; }
        .status-sent      { background: #dbeafe; color: #1e40af; }
        .status-overdue   { background: #fee2e2; color: #991b1b; }
        .status-cancelled { background: #f1f5f9; color: #6b7280; }

        .due-date-label {
            color: #6b8c77;
            font-size: 7.5px;
            margin-top: 5px;
            display: block;
        }

        .card-meta {
            background: #f4faf6;
            padding: 10px 18px;
            border-bottom: 1px solid #dceae1;
            overflow: hidden;
        }

        .meta-pill {
            float: left;
            margin-right: 20px;
            font-size: 8.5px;
            color: #4a6155;
        }

        .meta-pill strong { color: #1a7a3c; font-weight: 700; }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table thead tr { background: #f0f8f3; }

        .items-table th {
            padding: 10px 16px;
            text-align: left;
            font-size: 8px;
            font-weight: 700;
            color: #1a7a3c;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-bottom: 1px solid #dceae1;
        }

        .items-table th.right { text-align: right; }
        .items-table th.center { text-align: center; }

        .items-table td {
            padding: 9px 16px;
            border-bottom: 1px solid #edf5f0;
            color: #0d1f14;
            vertical-align: middle;
        }

        .items-table tbody tr:last-child td { border-bottom: none; }
        .items-table tbody tr:nth-child(even) { background: #f9fdfb; }

        .product-ref {
            color: #7a9185;
            font-size: 8px;
            margin-left: 4px;
        }

        .service-item {
            color: #f07d00;
            font-size: 8px;
            font-weight: 600;
            margin-left: 4px;
        }

        .qty-cell {
            text-align: center;
            font-weight: 700;
            color: #1a7a3c;
        }

        .price-cell { text-align: right; color: #4a6155; }

        .total-cell {
            text-align: right;
            font-weight: 700;
            color: #0d1f14;
        }

        .totals-section {
            padding: 16px 20px;
            background: #fafcfb;
            border-top: 2px solid #dceae1;
            text-align: right;
        }

        .totals-row {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 24px;
            font-size: 9px;
            color: #4a6155;
            margin-bottom: 6px;
        }

        .totals-row .t-label {
            font-weight: 500;
        }

        .totals-row .t-value {
            font-weight: 600;
            min-width: 110px;
            text-align: right;
        }

        .totals-row.total-ttc {
            font-size: 13px;
            color: #0d1f14;
            font-weight: 800;
            border-top: 2px solid #dceae1;
            padding-top: 12px;
            margin-top: 8px;
            margin-bottom: 10px;
        }

        .totals-row.total-ttc .t-value {
            color: #1a7a3c;
            font-size: 14px;
        }

        .totals-row.due {
            background: #fff8f0;
            border: 1px solid #fde8c0;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 9.5px;
            font-weight: 700;
            color: #f07d00;
            margin-top: 8px;
            justify-content: space-between;
            gap: 10px;
        }

        .totals-row.paid-full {
            background: #f0fdf4;
            border: 1px solid #d1fae5;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 9px;
            font-weight: 700;
            color: #065f46;
            margin-top: 8px;
            justify-content: space-between;
            gap: 10px;
        }

        .footer {
            margin-top: 10px;
            padding: 10px 32px;
            overflow: hidden;
        }

        .footer-divider {
            height: 1px;
            background: linear-gradient(90deg, #f07d00 0%, #1a7a3c 50%, transparent 100%);
            margin-bottom: 10px;
        }

        .footer-left {
            float: left;
            color: #7a9185;
            font-size: 7.5px;
        }

        .footer-left strong { color: #0d1f14; }

        .footer-right {
            float: right;
            color: #f07d00;
            font-size: 7.5px;
            font-weight: 700;
        }

        .footer-bottom {
            background: #0d1f14;
            padding: 8px 32px;
            overflow: hidden;
        }

        .fb-left { float: left; color: #4a6155; font-size: 7px; }
        .fb-left strong { color: #a0c8ae; }
        .fb-right { float: right; color: #f07d00; font-size: 7px; font-weight: 700; }

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
                    <div style="font-size:8px;color:#f07d00;font-weight:700;letter-spacing:2px;text-transform:uppercase;margin-top:3px;">Sarl · ERP System</div>
                @endif
            </div>
            <div class="meta-section">
                <div class="doc-badge">🧾 Rapport</div>
                <div class="doc-title">Rapport des Factures</div>
                <div class="doc-meta">Généré le <strong>{{ now()->format('d/m/Y à H:i') }}</strong></div>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    @if($invoices->count() > 2)
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-value">{{ $invoices->count() }}</span>
                <span class="stat-label">Factures</span>
            </div>
            <div class="stat-item">
                <span class="stat-value warn">{{ $invoices->where('status', 'sent')->count() }}</span>
                <span class="stat-label">En attente</span>
            </div>
            <div class="stat-item">
                <span class="stat-value">{{ number_format($invoices->sum('total'), 0, ',', ' ') }}</span>
                <span class="stat-label">Total TTC (FCFA)</span>
            </div>
            <div class="stat-item">
                <span class="stat-value">{{ number_format($invoices->sum('paid_amount'), 0, ',', ' ') }}</span>
                <span class="stat-label">Règlements (FCFA)</span>
            </div>
        </div>
    </div>
    @endif

    <div class="section-title">Détail des factures</div>

    @forelse($invoices as $invoice)
    <div class="invoice-card">

        <div class="card-header clearfix">
            <div class="card-header-left">
                <span class="invoice-ref">{{ $invoice->reference }}</span>
                <span class="invoice-date">Émise le {{ $invoice->date->format('d/m/Y') }}</span>
            </div>
            <div class="card-header-right">
                @php
                    $statusLabels = [
                        'paid' => 'Payée', 'partial' => 'Partielle', 'draft' => 'Brouillon',
                        'sent' => 'Envoyée', 'overdue' => 'En retard', 'cancelled' => 'Annulée',
                    ];
                @endphp
                <span class="status-badge status-{{ $invoice->status }}">
                    {{ $statusLabels[$invoice->status] ?? $invoice->status }}
                </span>
                <span class="due-date-label">Échéance : {{ $invoice->due_date->format('d/m/Y') }}</span>
            </div>
        </div>

        <div class="card-meta clearfix">
            <span class="meta-pill"><strong>Client :</strong> {{ $invoice->customer?->name ?? 'Client inconnu/supprimé' }}</span>
            @if($invoice->customer?->phone)
                <span class="meta-pill"><strong>Tél :</strong> {{ $invoice->customer->phone }}</span>
            @endif
        </div>

        @if($invoice->items->count())
        <table class="items-table">
            <thead>
                <tr>
                    <th>Désignation</th>
                    <th class="center" style="width:50px;">Qté</th>
                    <th class="right" style="width:120px;">Prix unitaire</th>
                    <th class="right" style="width:120px;">Total HT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>
                        @if($item->product)
                            <strong>{{ $item->product->name }}</strong>
                            <span class="product-ref">({{ $item->product->reference }})</span>
                        @elseif($item->description)
                            <strong>🛠️ {{ $item->description }}</strong>
                            <span class="service-item">[Service / Prestation]</span>
                        @else
                            <strong>📦 Produit / Service</strong>
                            <span class="service-item">[Information non disponible]</span>
                        @endif
                    </td>
                    <td class="qty-cell">{{ $item->quantity }}</td>
                    <td class="price-cell">{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</td>
                    <td class="total-cell">{{ number_format($item->total, 0, ',', ' ') }} FCFA</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <div class="totals-section">
            <div class="totals-row">
                <span class="t-label">Sous-total HT</span>
                <span class="t-value">{{ number_format($invoice->subtotal, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="totals-row">
                <span class="t-label">TVA (19,25%)</span>
                <span class="t-value">{{ number_format($invoice->tax, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="totals-row">
                <span class="t-label">Déjà réglé</span>
                <span class="t-value">{{ number_format($invoice->paid_amount, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="totals-row total-ttc">
                <span class="t-label">Total TTC</span>
                <span class="t-value">{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</span>
            </div>
            @php $remaining = $invoice->total - $invoice->paid_amount; @endphp
            @if($remaining > 0)
                <div class="totals-row due">
                    <span class="t-label">Reste à payer</span>
                    <span class="t-value">{{ number_format($remaining, 0, ',', ' ') }} FCFA</span>
                </div>
            @endif
        </div>

    </div>
    @empty
    <div style="text-align:center; padding:60px 32px; color:#7a9185;">
        <div style="font-size:30px; margin-bottom:10px;">📄</div>
        <div style="font-size:10px; font-style:italic;">Aucune facture trouvée pour cette période.</div>
    </div>
    @endforelse

    <div class="footer clearfix">
        <div class="footer-divider"></div>
        <div class="footer-left">
            <strong>JR Computer Sarl</strong> &nbsp;·&nbsp; Document confidentiel &nbsp;·&nbsp; Usage interne uniquement
        </div>
        <div class="footer-right">ERP System · {{ now()->format('Y') }}</div>
    </div>

    <div class="footer-bottom clearfix">
        <div class="fb-left"><strong>JR Computer</strong> · Douala, Cameroun</div>
        <div class="fb-right">Rapport généré par JRC-ERP System</div>
    </div>

</body>
</html>