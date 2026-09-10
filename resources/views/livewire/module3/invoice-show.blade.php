<div style="zoom:0.90; max-width: 1100px; margin: 0 auto;">
    <style>
        /* ─── TOOLBAR (écran uniquement) ─── */
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
            box-shadow: 0 4px 12px rgba(26,122,60,0.3);
            color: white;
            border: none;
        }
        .btn-accent:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(26,122,60,0.38); }
        .btn-accent.orange { background: linear-gradient(135deg, var(--brand-orange), var(--brand-orange-light)); box-shadow: 0 4px 12px rgba(240,125,0,0.3); }
        .btn-accent.orange:hover { box-shadow: 0 6px 18px rgba(240,125,0,0.38); }

        /* ─── DOCUMENT (réplique du papier en-tête JR Computer) ─── */
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@500;700&display=swap');

        .invoice-container {
            background: #ffffff;
            color: #1a1a1a;
            box-shadow: var(--shadow-card);
            border-radius: 6px;
            overflow: hidden;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* ─── EN-TÊTE / LETTERHEAD ─── */
        .invoice-letterhead {
            padding: 28px 60px 14px;
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
        .letterhead-logo .fallback { font-size: 24px; font-weight: 900; color: #2f6b3f; font-family: 'Dancing Script', cursive; }
        .letterhead-text { flex: 1; text-align: center; }
        .letterhead-text .brand-tagline {
            font-family: 'Dancing Script', cursive;
            font-size: 27px;
            font-weight: 700;
            color: #8a8a8a;
            letter-spacing: 0.3px;
        }
        .letterhead-text .brand-meta {
            font-size: 9.5px;
            color: #666;
            margin-top: 2px;
            line-height: 1.5;
        }
        .letterhead-text .brand-meta strong { color: #444; font-weight: 600; }
        .letterhead-sub {
            font-family: 'Dancing Script', cursive;
            font-size: 13px;
            color: #9a9a9a;
            margin-top: -4px;
            margin-left: 4px;
        }

        /* ─── ZONE DATE / DESTINATAIRE / TITRE ─── */
        .invoice-meta-zone { padding: 18px 60px 0; }
        .meta-date { text-align: right; font-size: 13px; color: #222; margin-bottom: 14px; }
        .meta-recipient { text-align: right; font-size: 14px; color: #111; margin-bottom: 22px; }
        .meta-recipient .name { font-weight: 800; text-transform: uppercase; }
        .meta-recipient .line { font-weight: 700; text-transform: uppercase; }
        .meta-recipient .small { font-weight: 500; text-transform: none; font-size: 12px; color: #555; }

        .doc-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 16px;
        }
        .doc-title {
            display: inline-block;
            background: #ececec;
            padding: 6px 14px 6px 4px;
            font-size: 19px;
            font-weight: 800;
            letter-spacing: 0.3px;
            color: #111;
            text-transform: uppercase;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #fff;
            flex-shrink: 0;
        }
        .status-badge.paid      { background: #10b981; }
        .status-badge.partial   { background: #f59e0b; }
        .status-badge.sent      { background: #3b82f6; }
        .status-badge.draft     { background: #6b7280; }
        .status-badge.overdue   { background: #ef4444; }
        .status-badge.cancelled { background: #6b7280; }

        /* ─── TABLEAU (encadré identique au papier) ─── */
        .proforma-box {
            margin: 0 60px 0;
            border: 1.5px solid #222;
        }
        .items-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .items-table thead th {
            border: 1px solid #222;
            border-top: none;
            padding: 8px 10px;
            font-weight: 700;
            font-size: 13px;
            text-align: left;
            background: #fff;
        }
        .items-table thead th:first-child { border-left: none; }
        .items-table thead th:last-child { border-right: none; }
        .items-table thead th.text-center { text-align: center; }
        .items-table thead th.text-end { text-align: right; }

        .items-table tbody td {
            border: 1px solid #222;
            border-top: none;
            border-bottom: none;
            padding: 10px 12px;
            vertical-align: top;
            color: #111;
        }
        .items-table tbody td:first-child { border-left: none; }
        .items-table tbody td:last-child { border-right: none; }
        .items-table tbody tr:last-child td { padding-bottom: 28px; }

        .items-table .product-name { font-weight: 700; text-decoration: underline; text-transform: uppercase; font-size: 13px; }
        .items-table .product-ref { font-size: 12px; text-decoration: underline; margin-top: 4px; }
        .items-table .service-tag { font-size: 10px; font-weight: 600; color: #555; font-style: italic; }
        .items-table .text-center { text-align: center; }
        .items-table .text-end { text-align: right; }
        .items-table .empty-row td { color: #888; text-align: center; padding: 30px 12px; border-top: 1px solid #222; }

        /* ─── TOTAUX (lignes intégrées au même encadré) ─── */
        .totals-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .totals-table td {
            border: 1px solid #222;
            border-bottom: none;
            padding: 6px 12px;
            font-weight: 600;
        }
        .totals-table td:first-child { border-left: none; text-align: center; font-weight: 400; }
        .totals-table td:last-child { border-right: none; text-align: right; width: 22%; }
        .totals-table tr.ttc td { font-weight: 800; font-size: 14px; }
        .totals-table tr.paid td { color: #1a7a3c; font-weight: 600; }

        .amount-words-row {
            border: 1px solid #222;
            padding: 10px 12px;
            font-size: 12px;
            line-height: 1.6;
        }
        .amount-words-row strong { font-weight: 700; }

        .remaining-row {
            border: 1px solid #222;
            border-top: none;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 800;
            text-align: right;
            color: #b45309;
            background: #fff8ec;
        }

        /* ─── MENTIONS / CONDITIONS ─── */
        .conditions-block {
            padding: 14px 60px 0;
            font-size: 12.5px;
            color: #111;
            line-height: 1.85;
        }
        .conditions-block .cond-line { text-decoration: underline; }
        .conditions-block .cond-strong { font-weight: 800; text-decoration: underline; }

        /* ─── HISTORIQUE DES PAIEMENTS (UNIQUEMENT SI PAIEMENTS EXISTENT) ─── */
        @if($invoice->payments->count() > 0)
        .payment-section { padding: 18px 60px 4px; }
        .payment-section h5 {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #444;
            border-bottom: 1px solid #ddd;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .payment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f7f7f7;
            border-radius: 8px;
            padding: 9px 14px;
            margin-bottom: 6px;
            border-left: 3px solid #1a7a3c;
            font-size: 12px;
        }
        .payment-amount { font-weight: 700; font-size: 13px; color: #1a7a3c; }
        .payment-details { font-size: 11px; color: #666; }
        .payment-empty { text-align: center; padding: 14px; background: #f7f7f7; border-radius: 8px; color: #888; font-size: 12px; }
        .delete-payment-btn {
            background: transparent;
            border: 1px solid rgba(220,38,38,0.3);
            border-radius: 6px;
            width: 26px;
            height: 26px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #dc2626;
        }
        .delete-payment-btn:hover { background: rgba(220,38,38,0.1); border-color: #dc2626; }
        @endif

        /* ─── PIED DE PAGE ─── */
        .invoice-footer {
            margin-top: 22px;
            border-top: 1px solid #ccc;
            padding: 16px 60px 18px;
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
        .footer-logo .fallback { font-size: 16px; font-weight: 900; color: #2f6b3f; font-family: 'Dancing Script', cursive; }
        .footer-brand-text { font-size: 10px; line-height: 1.5; color: #333; text-align: center; }
        .footer-brand-text .name { font-weight: 700; font-size: 11px; }
        .footer-tagline {
            text-align: center;
            font-size: 11px;
            font-style: italic;
            font-weight: 600;
            color: var(--brand-orange, #f07d00);
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
            transition: opacity 0.3s ease;
        }
        .partners-row img:hover {
            opacity: 1;
            filter: grayscale(0%);
        }
        .footer-legal { text-align: center; font-size: 8px; color: #999; margin-top: 10px; }

        /* ─── IMPRESSION ─── */
        @media print {
            body { background: white !important; margin: 0 !important; padding: 0 !important; }
            .module-toolbar, .btn-ghost, .btn-accent, .topbar, .sidebar, .flash-msg,
            .pagination-wrap, .filters-row, .search-wrap, .notif-wrap, .theme-toggle,
            .user-chip, .sidebar-overlay, .main-content .topbar, .main-content .topbar-actions,
            .delete-payment-btn { display: none !important; }
            .payment-section { display: block !important; }
            .page-area { padding: 0 !important; margin: 0 !important; }
            .invoice-container { box-shadow: none !important; border-radius: 0 !important; margin: 0 !important; }
            .status-badge, .remaining-row, .payment-item { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .proforma-box, .totals-table td, .items-table thead th, .items-table tbody td { border-color: #222 !important; }
            .letterhead-text .brand-tagline { color: #8a8a8a !important; }
            .partners-row img { opacity: 1 !important; filter: none !important; }
        }
    </style>

    {{-- TOOLBAR --}}
    <div class="module-toolbar">
        <h2><span class="module-icon"><i class="bi bi-receipt"></i></span> Détail de la facture</h2>
        <div>
            <a href="{{ route('module3.invoices.index') }}" class="btn-ghost"><i class="bi bi-arrow-left"></i> Retour</a>
            <button onclick="window.print()" class="btn-accent"><i class="bi bi-printer"></i> Imprimer</button>
        </div>
    </div>

    {{-- DOCUMENT --}}
    <div class="invoice-container">

        {{-- ============================================================
             EN-TÊTE / LETTERHEAD JR COMPUTER
        ============================================================ --}}
        <div class="invoice-letterhead">
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
                {{-- élément vide pour équilibrer --}}
                <div style="width:78px; flex-shrink:0;"></div>
            </div>
        </div>

        {{-- ============================================================
             DATE / DESTINATAIRE / TITRE
        ============================================================ --}}
        <div class="invoice-meta-zone">
            <div class="meta-date">Douala, le {{ $invoice->date->locale('fr')->translatedFormat('d F Y') }}</div>

            <div class="meta-recipient">
                <div class="name">{{ $invoice->customer?->name ?? 'Client inconnu/supprimé' }}</div>
                @if($invoice->customer?->address)
                    <div class="line">{{ $invoice->customer->address }}</div>
                @endif
                @if($invoice->customer?->phone || $invoice->customer?->email)
                    <div class="small">
                        @if($invoice->customer?->phone) Tél : {{ $invoice->customer->phone }} @endif
                        @if($invoice->customer?->phone && $invoice->customer?->email) &nbsp;·&nbsp; @endif
                        @if($invoice->customer?->email) {{ $invoice->customer->email }} @endif
                    </div>
                @endif
            </div>

            <div class="doc-title-row">
                <div class="doc-title">
                    @if($invoice->status === 'draft')
                        PROFORMA N°{{ $invoice->reference }}
                    @else
                        FACTURE N°{{ $invoice->reference }}
                    @endif
                </div>
                <!-- @php
                    $statusLabels = ['paid' => 'Payée', 'partial' => 'Partielle', 'sent' => 'Envoyée', 'draft' => 'Brouillon', 'overdue' => 'En retard', 'cancelled' => 'Annulée'];
                    $s = $statusLabels[$invoice->status] ?? $invoice->status;
                @endphp
                <span class="status-badge {{ $invoice->status }}">{{ $s }}</span> -->
            </div>
        </div>

        {{-- ============================================================
             TABLEAU PROFORMA (encadré, identique au papier)
        ============================================================ --}}
        <div class="proforma-box">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width:45%;">Désignation</th>
                        <th class="text-center" style="width:12%;">Qté</th>
                        <th class="text-end" style="width:20%;">P.U HT</th>
                        <th class="text-end" style="width:23%;">P.T HT</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoice->items as $item)
                    <tr>
                        <td>
                            @if($item->product)
                                <div class="product-name">{{ $item->product->name }}</div>
                                <div class="product-ref">Ref : {{ $item->product->reference }}</div>
                            @elseif($item->description)
                                <div class="product-name">{{ $item->description }}</div>
                                <div class="service-tag">Service</div>
                            @else
                                <div class="product-name">Produit / Service</div>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">{{ number_format($item->unit_price, 0, ',', ' ') }}</td>
                        <td class="text-end">{{ number_format($item->total, 0, ',', ' ') }}</td>
                    </tr>
                    @empty
                    <tr class="empty-row">
                        <td colspan="4">Aucun article dans cette facture</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- TOTAUX --}}
            @php
                $subtotal = $invoice->subtotal;
                $tax = $invoice->tax;
                $total = $invoice->total;
                $paid = $invoice->paid_amount;
                $remaining = $total - $paid;
            @endphp
            <table class="totals-table">
                <tr>
                    <td>Total HT</td>
                    <td>{{ number_format($subtotal, 0, ',', ' ') }}</td>
                </tr>
                <tr>
                    <td>TVA 19,25%</td>
                    <td>{{ number_format($tax, 0, ',', ' ') }}</td>
                </tr>
                @if($paid > 0)
                <tr class="paid">
                    <td>Déjà réglé</td>
                    <td>{{ number_format($paid, 0, ',', ' ') }}</td>
                </tr>
                @endif
                <tr class="ttc">
                    <td>TOTAL TTC</td>
                    <td>{{ number_format($total, 0, ',', ' ') }}</td>
                </tr>
            </table>

            {{-- MONTANT EN LETTRES --}}
            @if($total > 0)
            <div class="amount-words-row">
                La présente facture {{ $invoice->status === 'draft' ? 'proforma' : '' }} est arrêtée à la somme de :
                <strong>{{ \App\Helpers\NumberToWords::convert($total) }} FCFA TTC</strong>.
            </div>
            @endif

            {{-- RESTE À PAYER --}}
            @if($remaining > 0 && $paid > 0)
            <div class="remaining-row">Reste à payer : {{ number_format($remaining, 0, ',', ' ') }} FCFA</div>
            @endif
        </div>

        {{-- ============================================================
             division d'espace
        ============================================================ --}}
        <div class="conditions-block">
            <div class="cond-line"><br></div>
        </div>

        {{-- ============================================================
             HISTORIQUE DES PAIEMENTS (UNIQUEMENT SI DES PAIEMENTS EXISTENT)
        ============================================================ --}}
        @if($invoice->payments->count() > 0)
        <div class="payment-section">
            <h5><i class="bi bi-credit-card me-2"></i> Historique des paiements</h5>
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
        </div>
        @else
        {{-- ============================================================
             division d'espace
        ============================================================ --}}
        <div class="conditions-block">
            <div class="cond-line"><br><br><br><br></div>
        </div>

        @endif

        {{-- ============================================================
             PIED DE PAGE JR COMPUTER
        ============================================================ --}}
        <div class="invoice-footer">
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

            {{-- Logos partenaires --}}
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
    <script>
    document.addEventListener('livewire:init', function () {
        Livewire.on('scroll-to-top', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>
</div>