<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Ventes – JR Computer Sarl</title>
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

        .logo-img { height: 50px; width: auto; display: block; }

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

        .two-columns {
            margin: 16px 32px 20px;
            display: flex;
            gap: 20px;
        }

        .card {
            flex: 1;
            background: #ffffff;
            border: 1px solid #dceae1;
            border-radius: 12px;
            overflow: hidden;
        }

        .card-header {
            background: #0d1f14;
            padding: 10px 16px;
        }

        .card-header h3 {
            color: #ffffff;
            font-size: 10px;
            font-weight: 800;
            margin: 0;
        }

        .card-body {
            padding: 14px 16px;
        }

        .top-products-table, .payment-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        .top-products-table th, .payment-table th {
            background: #f4faf6;
            padding: 8px 12px;
            text-align: left;
            font-size: 7.5px;
            font-weight: 700;
            color: #1a7a3c;
            text-transform: uppercase;
            border-bottom: 1px solid #dceae1;
        }

        .top-products-table td, .payment-table td {
            padding: 7px 12px;
            border-bottom: 1px solid #edf5f0;
        }

        .badge-method {
            display: inline-block;
            background: #f1f5f9;
            color: #475569;
            font-size: 7px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 12px;
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

        .footer-left { float: left; color: #7a9185; font-size: 7.5px; }
        .footer-left strong { color: #0d1f14; }
        .footer-right { float: right; color: #f07d00; font-size: 7.5px; font-weight: 700; }

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
                <div class="doc-badge">📊 Analyse</div>
                <div class="doc-title">Rapport des Ventes</div>
                <div class="doc-meta">Généré le <strong>{{ now()->format('d/m/Y à H:i') }}</strong></div>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-value">{{ number_format($totalSales ?? 0, 0, ',', ' ') }}</span>
                <span class="stat-label">Chiffre d’affaires (TTC)</span>
            </div>
            <div class="stat-item">
                <span class="stat-value warn">{{ number_format($totalTax ?? 0, 0, ',', ' ') }}</span>
                <span class="stat-label">TVA collectée</span>
            </div>
            <div class="stat-item">
                <span class="stat-value">{{ number_format($totalPaid ?? 0, 0, ',', ' ') }}</span>
                <span class="stat-label">Encaissements</span>
            </div>
            <div class="stat-item">
                <span class="stat-value">{{ $invoices->count() }}</span>
                <span class="stat-label">Factures</span>
            </div>
        </div>
    </div>

    <div class="two-columns">
        <!-- Top produits -->
        <div class="card">
            <div class="card-header">
                <h3>🏆 Top 10 produits</h3>
            </div>
            <div class="card-body">
                @if($topProducts->count())
                <table class="top-products-table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th class="right">Qté vendue</th>
                            <th class="right">Montant (FCFA)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $tp)
                        <tr>
                            <td>{{ $tp->product->name ?? '—' }}</td>
                            <td class="right">{{ $tp->total_qty }}</td>
                            <td class="right">{{ number_format($tp->total_amount, 0, ',', ' ') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div style="text-align:center; padding:20px;">Aucune donnée</div>
                @endif
            </div>
        </div>

        <!-- Paiements par méthode -->
        <div class="card">
            <div class="card-header">
                <h3>💳 Paiements par méthode</h3>
            </div>
            <div class="card-body">
                @if($paymentsByMethod->count())
                <table class="payment-table">
                    <thead>
                        <tr>
                            <th>Méthode</th>
                            <th class="right">Montant (FCFA)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentsByMethod as $pm)
                        <tr>
                            <td><span class="badge-method">
                                @switch($pm->method)
                                    @case('cash') Espèces @break
                                    @case('mtn_momo') MTN MoMo @break
                                    @case('orange_money') Orange Money @break
                                    @default {{ $pm->method }}
                                @endswitch
                            </span></td>
                            <td class="right">{{ number_format($pm->total, 0, ',', ' ') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div style="text-align:center; padding:20px;">Aucun paiement enregistré</div>
                @endif
            </div>
        </div>
    </div>

    <div class="section-title" style="margin: 0 32px 10px;">Factures détaillées</div>

    @forelse($invoices as $invoice)
    <div class="invoice-card" style="margin: 0 32px 20px; background:#fff; border:1px solid #dceae1; border-radius:12px; overflow:hidden;">
        <div class="card-header clearfix" style="background:#0d1f14; padding:12px 18px;">
            <div style="float:left;">
                <span style="color:#fff; font-weight:800;">{{ $invoice->reference }}</span>
                <span style="color:#6b8c77; font-size:8px; display:block;">{{ $invoice->date->format('d/m/Y') }}</span>
            </div>
            <div style="float:right; text-align:right;">
                <span class="status-badge status-{{ $invoice->status }}">{{ $invoice->status }}</span>
            </div>
        </div>
        <div style="padding:12px 18px; background:#f4faf6; border-bottom:1px solid #dceae1;">
            <strong>Client :</strong> {{ $invoice->customer->name }}
        </div>
        <div style="padding:12px 18px; text-align:right;">
            <div><strong>Total TTC :</strong> {{ number_format($invoice->total, 0, ',', ' ') }} FCFA</div>
            <div><strong>Payé :</strong> {{ number_format($invoice->paid_amount, 0, ',', ' ') }} FCFA</div>
        </div>
    </div>
    @empty
    <div style="text-align:center; padding:40px; color:#7a9185;">Aucune facture pour la période sélectionnée.</div>
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
        <div class="fb-right">Rapport généré automatiquement</div>
    </div>

</body>
</html>