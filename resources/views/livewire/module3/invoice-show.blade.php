<div style="zoom:0.90;">
    <style>
        /* Styles écran (interaction) */
        .module-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }
        .module-toolbar h2 {
            font-family: 'Syne', sans-serif;
            font-size: 19px;
            font-weight: 800;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .module-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            background: linear-gradient(135deg, var(--brand-green), #22a352);
            box-shadow: 0 4px 10px rgba(26,122,60,0.28);
        }
        .btn-ghost, .btn-accent {
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: 0.2s;
            text-decoration: none;
        }
        .btn-ghost {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
        }
        .btn-ghost:hover {
            border-color: var(--brand-green);
            color: var(--brand-green);
            background: var(--brand-green-xlight);
        }
        .btn-accent {
            background: linear-gradient(135deg, var(--brand-green), #22a352);
            color: white;
            border: none;
        }
        .btn-accent:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(26,122,60,0.3);
        }
        .invoice-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 35px -12px rgba(0,0,0,0.1);
            overflow: hidden;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }
        .invoice-header {
            background: linear-gradient(135deg, #0d1f14 0%, #1a3a28 100%);
            padding: 24px 32px;
            color: white;
        }
        .invoice-header-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .logo-area {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .logo-img {
            height: 55px;
            width: auto;
        }
        .company-details h1 {
            font-size: 20px;
            font-weight: 800;
            margin: 0;
            font-family: 'Syne', sans-serif;
        }
        .company-details p {
            font-size: 10px;
            opacity: 0.8;
            margin: 5px 0 0;
        }
        .invoice-info {
            text-align: right;
        }
        .invoice-info .doc-title {
            font-size: 24px;
            font-weight: 800;
            margin: 0;
        }
        .invoice-info .ref {
            font-size: 14px;
            font-weight: 600;
            margin-top: 5px;
        }
        .invoice-meta {
            background: #f8fafc;
            padding: 20px 32px;
            border-bottom: 1px solid #e2ece6;
        }
        .meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .meta-item {
            font-size: 12px;
        }
        .meta-label {
            font-weight: 600;
            color: #5a6b61;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .meta-value {
            font-weight: 700;
            color: #0f1f12;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
        }
        .status-badge.paid    { background: #d1fae5; color: #065f46; }
        .status-badge.partial { background: #ffedd5; color: #9a3412; }
        .status-badge.sent    { background: #dbeafe; color: #1e40af; }
        .status-badge.draft   { background: #f1f5f9; color: #475569; }
        .status-badge.overdue { background: #fee2e2; color: #991b1b; }
        .status-badge.cancelled { background: #f1f5f9; color: #6b7280; }
        .amount-positive { color: #1a7a3c; font-weight: 800; }
        .amount-negative { color: #dc2626; font-weight: 800; }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .items-table th {
            background: #f4f6f9;
            padding: 12px 16px;
            font-size: 11px;
            text-transform: uppercase;
            color: #5a6b61;
            border-bottom: 1px solid #e2ece6;
        }
        .items-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #e2ece6;
            color: #0f1f12;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        
        .payment-section {
            padding: 0 32px 20px 32px;
        }
        .payment-section h5 {
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--text-primary);
        }
        .payment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--bg-page);
            border-radius: 10px;
            padding: 10px 15px;
            margin-bottom: 8px;
        }
        .payment-amount {
            font-weight: 700;
            font-size: 14px;
            color: #1a7a3c;
        }
        .payment-details {
            font-size: 11px;
            color: var(--text-muted);
        }
        .payment-empty {
            text-align: center;
            padding: 20px;
            background: var(--bg-page);
            border-radius: 10px;
            color: var(--text-muted);
            font-size: 12px;
        }
        
        .totals-box {
            background: #f4f6f9;
            border-radius: 16px;
            padding: 20px 28px;
            margin: 20px 32px;
            text-align: right;
        }
        .totals-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-size: 13px;
        }
        .totals-line .label {
            color: #5a6b61;
            font-weight: 500;
        }
        .totals-line .value {
            font-weight: 600;
            color: #0f1f12;
        }
        .totals-line.total {
            font-size: 16px;
            font-weight: 800;
            color: #1a7a3c;
            border-top: 1px solid #e2ece6;
            padding-top: 12px;
            margin-top: 8px;
        }
        .totals-line.total .value {
            color: #1a7a3c;
            font-size: 18px;
        }
        .totals-line.remaining {
            background: #fff8f0;
            padding: 10px 16px;
            border-radius: 12px;
            margin-top: 12px;
        }
        .totals-line.remaining .label {
            color: #f07d00;
            font-weight: 700;
        }
        .totals-line.remaining .value {
            color: #f07d00;
            font-weight: 800;
        }
        .totals-line.paid-full {
            background: #d1fae5;
            padding: 10px 16px;
            border-radius: 12px;
            margin-top: 12px;
        }
        .totals-line.paid-full .label {
            color: #065f46;
            font-weight: 700;
        }
        .totals-line.paid-full .value {
            color: #065f46;
            font-weight: 800;
        }
        .invoice-footer {
            background: #0d1f14;
            color: #6b8c77;
            padding: 14px 32px;
            font-size: 9px;
            text-align: center;
        }
        .payment-terms {
            background: #f0fdf4;
            border: 1px solid #d1fae5;
            border-radius: 12px;
            padding: 12px 20px;
            margin: 0 32px 20px 32px;
            font-size: 11px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        .payment-terms span:first-child {
            color: #065f46;
            font-weight: 600;
        }
        
        .delete-payment-btn {
            background: transparent;
            border: 1px solid rgba(220,38,38,0.3);
            border-radius: 6px;
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.15s;
            color: #dc2626;
        }
        .delete-payment-btn:hover {
            background: rgba(220,38,38,0.1);
            border-color: #dc2626;
        }
        
        @media print {
            body {
                background: white;
                margin: 0;
                padding: 0;
            }
            .module-toolbar, .btn-ghost, .btn-accent, .topbar, .sidebar, .flash-msg, .pagination-wrap, .filters-row, .search-wrap, .notif-wrap, .theme-toggle, .user-chip, .sidebar-overlay, .main-content .topbar, .main-content .topbar-actions, .delete-payment-btn {
                display: none !important;
            }
            .page-area {
                padding: 0 !important;
                margin: 0 !important;
            }
            .invoice-container {
                box-shadow: none;
                border-radius: 0;
                margin: 0;
            }
            .invoice-header {
                background: #0d1f14;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .status-badge {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .totals-box, .payment-terms, .payment-item {
                background: #f4f6f9;
                print-color-adjust: exact;
            }
        }
    </style>

    <div class="module-toolbar">
        <h2><span class="module-icon"><i class="bi bi-receipt"></i></span> Détail de la facture</h2>
        <div>
            <a href="{{ route('module3.invoices.index') }}" class="btn-ghost"><i class="bi bi-arrow-left"></i> Retour</a>
            <button onclick="window.print()" class="btn-accent"><i class="bi bi-printer"></i> Imprimer</button>
        </div>
    </div>

    <div class="invoice-container">
        <div class="invoice-header">
            <div class="invoice-header-inner">
                <div class="logo-area">
                    @php
                        $logoPath = public_path('images/logo-jr.jpg');
                        $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
                    @endphp
                    @if($logoData)
                        <img src="data:image/jpeg;base64,{{ $logoData }}" class="logo-img" alt="JR Computer">
                    @else
                        <div style="font-size:32px;font-weight:800;">JR</div>
                    @endif
                    <div class="company-details">
                        <h1>JR Computer Sarl</h1>
                        <p>Douala, Cameroun · RCCM: RC/DLA/2025/B123</p>
                        <p>Tél: +237 690 000 000 · contact@jrcomputer.com</p>
                    </div>
                </div>
                <div class="invoice-info">
                    <div class="doc-title">FACTURE</div>
                    <div class="ref">N° {{ $invoice->reference }}</div>
                </div>
            </div>
        </div>

        <div class="invoice-meta">
            <div class="meta-grid">
                <div class="meta-item">
                    <div class="meta-label">Client</div>
                    <div class="meta-value">{{ $invoice->customer?->name ?? 'Client inconnu/supprimé' }}</div>
                    @if($invoice->customer?->phone)
                        <div style="font-size: 10px; color: #6b8c77;">Tél: {{ $invoice->customer->phone }}</div>
                    @endif
                    @if($invoice->customer?->email)
                        <div style="font-size: 10px; color: #6b8c77;">Email: {{ $invoice->customer->email }}</div>
                    @endif
                </div>
                <div class="meta-item">
                    <div class="meta-label">Date d'émission</div>
                    <div class="meta-value">{{ $invoice->date->format('d/m/Y') }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Date d'échéance</div>
                    <div class="meta-value">{{ $invoice->due_date->format('d/m/Y') }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Statut</div>
                    @php
                        $statusMap = [
                            'paid' => ['paid','Payée','bi-check-circle-fill'],
                            'partial' => ['partial','Partielle','bi-clock-history'],
                            'sent' => ['sent','Envoyée','bi-send'],
                            'draft' => ['draft','Brouillon','bi-file-earmark'],
                            'overdue' => ['overdue','En retard','bi-exclamation-circle'],
                            'cancelled' => ['cancelled','Annulée','bi-x-circle'],
                        ];
                        $s = $statusMap[$invoice->status] ?? ['draft','—','bi-circle'];
                    @endphp
                    <span class="status-badge {{ $s[0] }}"><i class="bi {{ $s[2] }}"></i> {{ $s[1] }}</span>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Reçu par</div>
                    <div class="meta-value">
                        @if($invoice->status === 'paid' && $invoice->payments->isNotEmpty())
                            {{ $invoice->payments->last()->receiver->name ?? '—' }}
                        @else
                            —
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive px-3">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Désignation</th>
                        <th class="text-center" style="width:80px;">Qté</th>
                        <th class="text-end" style="width:140px;">Prix unitaire (FCFA)</th>
                        <th class="text-end" style="width:140px;">Total HT (FCFA)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                    <tr>
                        <td>
                            @if($item->product)
                                <strong>{{ $item->product->name }}</strong>
                                <br><span style="font-size: 10px; color: #6c757d;">Réf: {{ $item->product->reference }}</span>
                            @elseif($item->description)
                                <strong>🛠️ {{ $item->description }}</strong>
                                <br><span style="font-size: 10px; color: #f07d00;">Service / Prestation</span>
                            @else
                                <strong>📦 Produit / Service</strong>
                                <br><span style="font-size: 10px; color: #dc2626;">Information non disponible</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</td>
                        <td class="text-end">{{ number_format($item->total, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @php
            $subtotal = $invoice->subtotal;
            $tax = $invoice->tax;
            $total = $invoice->total;
            $paid = $invoice->paid_amount;
            $remaining = $total - $paid;
        @endphp

        <div class="totals-box">
            <div class="totals-line">
                <span class="label">Sous-total HT</span>
                <span class="value">{{ number_format($subtotal, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="totals-line">
                <span class="label">TVA (19,25%)</span>
                <span class="value">{{ number_format($tax, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="totals-line">
                <span class="label">Déjà réglé</span>
                <span class="value">{{ number_format($paid, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="totals-line total">
                <span class="label">Total TTC</span>
                <span class="value">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
            </div>
            @if($remaining > 0)
                <div class="totals-line remaining">
                    <span class="label">Reste à payer</span>
                    <span class="value">{{ number_format($remaining, 0, ',', ' ') }} FCFA</span>
                </div>
            @endif
        </div>

        <div class="payment-section">
            <h5><i class="bi bi-credit-card me-2"></i> Historique des paiements</h5>
            @if($invoice->payments->count() > 0)
                @foreach($invoice->payments as $payment)
                <div class="payment-item">
                    <div>
                        <div class="payment-amount">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</div>
                        <div class="payment-details">
                            {{ $payment->payment_date->format('d/m/Y') }} - 
                            @switch($payment->method)
                                @case('cash') 💵 Espèces @break
                                @case('mtn_momo') 📱 MTN MoMo @break
                                @case('orange_money') 🟠 Orange Money @break
                                @case('bank_transfer') 🏦 Virement bancaire @break
                                @default {{ $payment->method }}
                            @endswitch
                        </div>
                    </div>
                    <button wire:click="deletePayment({{ $payment->id }})" 
                            class="delete-payment-btn" 
                            onclick="return confirm('Supprimer ce paiement ?')"
                            title="Supprimer ce paiement">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
                @endforeach
            @else
                <div class="payment-empty">
                    <i class="bi bi-credit-card-2-front me-2"></i> Aucun paiement enregistré
                </div>
            @endif
        </div>

        @if($invoice->status !== 'paid' && $remaining > 0)
            <div class="payment-terms">
                <span><i class="bi bi-info-circle"></i> Conditions de paiement</span>
                <span>À régler avant le {{ $invoice->due_date->format('d/m/Y') }}</span>
                <span><i class="bi bi-credit-card"></i> Paiement accepté : espèces, MTN MoMo, Orange Money</span>
            </div>
        @endif

        <div class="invoice-footer">
            <div>JR Computer Sarl · BP:5226 · NIU: M123456789B</div>
            <div style="margin-top: 4px;">Document généré par JRC-ERP System· Fait à Douala, le {{ now()->format('d/m/Y') }}</div>
        </div>
    </div>
</div>