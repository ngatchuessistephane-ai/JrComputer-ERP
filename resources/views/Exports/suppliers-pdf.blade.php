<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Fournisseurs – JR Computer Sarl</title>
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

        .section-title {
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #7a9185;
            margin: 18px 32px 10px;
        }

        .supplier-table {
            margin: 0 32px 20px;
            background: #ffffff;
            border: 1px solid #dceae1;
            border-radius: 12px;
            overflow: hidden;
            width: calc(100% - 64px);
            border-collapse: collapse;
            font-size: 9px;
        }

        .supplier-table th {
            background: #f4faf6;
            padding: 12px 16px;
            text-align: left;
            font-size: 8px;
            font-weight: 700;
            color: #1a7a3c;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-bottom: 1px solid #dceae1;
        }

        .supplier-table td {
            padding: 10px 16px;
            border-bottom: 1px solid #edf5f0;
            color: #0d1f14;
            vertical-align: middle;
        }

        .supplier-table tbody tr:last-child td { border-bottom: none; }
        .supplier-table tbody tr:nth-child(even) { background: #f9fdfb; }

        .code-badge {
            display: inline-block;
            background: #f1f5f9;
            color: #475569;
            font-size: 7.5px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 4px;
            font-family: monospace;
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
                <div class="doc-badge">🚚 Rapport</div>
                <div class="doc-title">Rapport des Fournisseurs</div>
                <div class="doc-meta">Généré le <strong>{{ now()->format('d/m/Y à H:i') }}</strong></div>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    @if($suppliers->count() > 2)
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-value">{{ $suppliers->count() }}</span>
                <span class="stat-label">Fournisseurs</span>
            </div>
            <div class="stat-item">
                <span class="stat-value warn">{{ $suppliers->where('payment_terms', '>', 0)->avg('payment_terms') ?: 0 }}</span>
                <span class="stat-label">Délai moyen (jours)</span>
            </div>
            <div class="stat-item">
                <span class="stat-value">{{ number_format($suppliers->sum('total_purchased'), 0, ',', ' ') }}</span>
                <span class="stat-label">Total acheté (FCFA)</span>
            </div>
            <div class="stat-item">
                <span class="stat-value">{{ $suppliers->whereNotNull('email')->count() }}</span>
                <span class="stat-label">Avec email</span>
            </div>
        </div>
    </div>
    @endif

    <div class="section-title">Liste des fournisseurs</div>

    <table class="supplier-table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Nom</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Délai paiement</th>
                <th>Total acheté (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suppliers as $supplier)
            <tr>
                <td><span class="code-badge">{{ $supplier->code }}</span></td>
                <td style="font-weight:600;">{{ $supplier->name }}<br><span style="font-size:7px;color:#7a9185;">{{ Str::limit($supplier->address ?? '', 30) }}</span></td>
                <td>{{ $supplier->contact_person ?? '—' }}</td>
                <td>{{ $supplier->email ?? '—' }}</td>
                <td>{{ $supplier->phone ?? '—' }}</td>
                <td class="center">{{ $supplier->payment_terms }} jours</td>
                <td class="right">{{ number_format($supplier->total_purchased, 0, ',', ' ') }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center; padding:40px;">Aucun fournisseur trouvé.</td></tr>
            @endforelse
        </tbody>
    </table>

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