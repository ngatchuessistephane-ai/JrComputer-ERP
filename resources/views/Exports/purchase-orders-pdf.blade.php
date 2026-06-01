<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bons de Commande – JR Computer Sarl</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #0d1f14;
            background: #f9fbfa;
        }

        /* ── TOP BAR ── */
        .top-bar {
            height: 4px;
            background: linear-gradient(90deg, #f07d00 0%, #f5a623 30%, #1a7a3c 60%, #0f5229 100%);
        }

        /* ── HEADER ── */
        .header {
            background: #ffffff;
            padding: 22px 32px 18px;
            border-bottom: 1px solid #e0ede5;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(26,122,60,0.05) 0%, transparent 70%);
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
            font-size: 17px;
            font-weight: 800;
            color: #0d1f14;
            line-height: 1.15;
            margin-bottom: 4px;
        }

        .doc-meta { font-size: 8px; color: #7a9185; }
        .doc-meta strong { color: #f07d00; font-weight: 700; }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, #1a7a3c 0%, #d4e8da 60%, transparent 100%);
            margin: 0 32px;
        }

        /* ── ORDER CARD ── */
        .order-card {
            margin: 18px 32px 0;
            background: #ffffff;
            border: 1px solid #dceae1;
            border-radius: 10px;
            overflow: hidden;
            page-break-inside: avoid;
        }

        /* Card Header */
        .order-header {
            background: linear-gradient(135deg, #0d1f14 0%, #1a3525 100%);
            padding: 12px 16px;
            overflow: hidden;
        }

        .order-header-left { float: left; }
        .order-header-right { float: right; text-align: right; }

        .order-ref {
            color: #ffffff;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.5px;
            display: block;
        }

        .order-supplier-name {
            color: #7ab88a;
            font-size: 9px;
            margin-top: 3px;
            display: block;
        }

        /* Status */
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 7.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .status-received  { background: #f07d00; color: #fff; }
        .status-pending   { background: #fff3e0; color: #c2410c; }
        .status-partial   { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .status-default   { background: #f1f5f9; color: #475569; }

        .order-date-label {
            color: #6b8c77;
            font-size: 7.5px;
            margin-top: 5px;
            display: block;
        }

        /* Card Meta */
        .order-meta {
            background: #f4faf6;
            padding: 8px 16px;
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

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table thead tr { background: #f0f8f3; }

        .items-table th {
            padding: 8px 14px;
            text-align: left;
            font-size: 7.5px;
            font-weight: 700;
            color: #1a7a3c;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-bottom: 1px solid #dceae1;
        }

        .items-table th.right  { text-align: right; }
        .items-table th.center { text-align: center; }

        .items-table td {
            padding: 7px 14px;
            border-bottom: 1px solid #edf5f0;
            color: #0d1f14;
            vertical-align: middle;
        }

        .items-table tbody tr:last-child td { border-bottom: none; }
        .items-table tbody tr:nth-child(even) { background: #f9fdfb; }

        .product-name { font-weight: 600; }

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

        /* Tfoot */
        .items-table tfoot tr td {
            padding: 5px 14px;
            font-size: 9px;
        }

        .items-table tfoot tr:first-child td {
            border-top: 1px solid #dceae1;
        }

        .tfoot-label {
            text-align: right;
            color: #4a6155;
        }

        .tfoot-value {
            text-align: right;
            color: #0d1f14;
            font-weight: 600;
        }

        .items-table tfoot tr.tfoot-total {
            background: #f0f8f3;
            border-top: 2px solid #1a7a3c;
        }

        .tfoot-total-label {
            text-align: right;
            font-weight: 800;
            color: #1a7a3c;
            font-size: 10px;
        }

        .tfoot-total-value {
            text-align: right;
            font-weight: 800;
            font-size: 10px;
            color: #1a7a3c;
        }

        /* Notes */
        .order-notes {
            background: #fffbf4;
            border-top: 1px solid #fde8c0;
            padding: 9px 16px;
            font-size: 8.5px;
            color: #7a4800;
            border-radius: 0 0 10px 10px;
        }

        .notes-label {
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #f07d00;
            margin-bottom: 3px;
            display: block;
        }

        /* ── SUMMARY BAND ── */
        .summary-band {
            margin: 18px 32px 0;
            background: #0d1f14;
            border-radius: 10px;
            padding: 16px 0;
            overflow: hidden;
        }

        .sum-item {
            float: left;
            width: 33.33%;
            text-align: center;
            padding: 4px 0;
        }

        .sum-item + .sum-item { border-left: 1px solid #1e3826; }

        .sum-value {
            display: block;
            font-size: 16px;
            font-weight: 800;
            color: #7ab88a;
            line-height: 1.2;
        }

        .sum-value.orange { color: #f07d00; }

        .sum-label {
            font-size: 7px;
            color: #4a6155;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            font-weight: 600;
            margin-top: 3px;
            display: block;
        }

        /* ── FOOTER ── */
        .footer {
            margin-top: 18px;
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
                <div class="doc-badge">🛒 Commandes</div>
                <div class="doc-title">Bons de Commande Fournisseurs</div>
                <div class="doc-meta">Généré le <strong>{{ now()->format('d/m/Y') }}</strong> à <strong>{{ now()->format('H:i') }}</strong></div>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    @php
        $grandTotal  = 0;
        $totalItems  = 0;
        $totalOrders = count($orders);

        $statusMap = [
            'received'  => 'status-received',
            'pending'   => 'status-pending',
            'partial'   => 'status-partial',
            'cancelled' => 'status-cancelled',
        ];
        $statusLabel = [
            'received'  => 'Reçu',
            'pending'   => 'En attente',
            'partial'   => 'Partiel',
            'cancelled' => 'Annulé',
        ];
    @endphp

    @foreach($orders as $order)
    @php
        $grandTotal += $order->total;
        $totalItems += $order->items->count();
        $statusClass = $statusMap[$order->status] ?? 'status-default';
        $statusText  = $statusLabel[$order->status] ?? ucfirst($order->status);
    @endphp

    <div class="order-card">

        <div class="order-header clearfix">
            <div class="order-header-left">
                <span class="order-ref">{{ $order->reference }}</span>
                <span class="order-supplier-name">{{ $order->supplier->name }}</span>
            </div>
            <div class="order-header-right">
                <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                <span class="order-date-label">Commande du {{ $order->order_date->format('d/m/Y') }}</span>
            </div>
        </div>

        <div class="order-meta clearfix">
            @if($order->expected_date ?? false)
            <span class="meta-pill">
                <strong>Livraison prévue :</strong> {{ $order->expected_date->format('d/m/Y') }}
            </span>
            @endif
            <span class="meta-pill">
                <strong>Lignes articles :</strong> {{ $order->items->count() }}
            </span>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th class="center" style="width:60px;">Qté</th>
                    <th class="right" style="width:120px;">Prix unitaire</th>
                    <th class="right" style="width:130px;">Total ligne</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td class="product-name">{{ $item->product->name }}</td>
                    <td class="qty-cell">{{ $item->quantity_ordered }}</td>
                    <td class="price-cell">{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</td>
                    <td class="total-cell">{{ number_format($item->total, 0, ',', ' ') }} FCFA</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="tfoot-label">Sous‑total HT</td>
                    <td class="tfoot-value">{{ number_format($order->subtotal, 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr>
                    <td colspan="3" class="tfoot-label">TVA (18%)</td>
                    <td class="tfoot-value">{{ number_format($order->tax, 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr class="tfoot-total">
                    <td colspan="3" class="tfoot-total-label">Total TTC</td>
                    <td class="tfoot-total-value">{{ number_format($order->total, 0, ',', ' ') }} FCFA</td>
                </tr>
            </tfoot>
        </table>

        @if($order->notes)
        <div class="order-notes">
            <span class="notes-label">Notes</span>
            {{ $order->notes }}
        </div>
        @endif

    </div>
    @endforeach

    <!-- Summary Band -->
    <div class="summary-band clearfix">
        <div class="sum-item">
            <span class="sum-value">{{ $totalOrders }}</span>
            <span class="sum-label">Commandes</span>
        </div>
        <div class="sum-item">
            <span class="sum-value">{{ $totalItems }}</span>
            <span class="sum-label">Lignes articles</span>
        </div>
        <div class="sum-item">
            <span class="sum-value orange">{{ number_format($grandTotal, 0, ',', ' ') }}</span>
            <span class="sum-label">Total TTC (FCFA)</span>
        </div>
    </div>

    <div class="footer clearfix">
        <div class="footer-divider"></div>
        <div class="footer-left">
            <strong>JR Computer Sarl</strong> &nbsp;·&nbsp; Document confidentiel &nbsp;·&nbsp; Usage interne uniquement
        </div>
        <div class="footer-right">ERP System · {{ now()->format('Y') }}</div>
    </div>

    <div class="footer-bottom clearfix">
        <div class="fb-left"><strong>JR Computer</strong> · Douala, Cameroun</div>
        <div class="fb-right">Bons de commande générés automatiquement</div>
    </div>

</body>
</html>