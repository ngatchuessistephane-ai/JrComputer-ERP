<div style="zoom:0.90; max-width: 1100px; margin: 0 auto;">
    <style>
        /* ─── TOOLBAR ─── */
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
        .btn-accent:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(26,122,60,0.38); }
        .btn-accent.orange { background: linear-gradient(135deg, var(--brand-green), #22a352);
            box-shadow: 0 4px 12px rgba(26,122,60,0.3); }
        .btn-accent.orange:hover { box-shadow: 0 6px 18px rgba(26,122,60,0.38); }
        .btn-accent.pdf { background: linear-gradient(135deg, #dc2626, #ef4444);  }
        .btn-accent.pdf:hover { box-shadow: 0 4px 12px rgba(220,38,38,0.3); }

        /* ─── DOCUMENT ─── */
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
        .status-badge.draft { background: #6b7280; }
        .status-badge.sent { background: #3b82f6; }
        .status-badge.approved { background: #10b981; }
        .status-badge.rejected { background: #ef4444; }
        .status-badge.converted { background: #8b5cf6; }

        /* ─── TABLEAU ─── */
        .proforma-box {
            margin: 0 60px 0;
            border: 1.5px solid #222;
        }
        .items-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .items-table thead th {
            border: 1px solid #222;
            border-top: none;
            padding: 8px 12px;
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

        /* ─── TOTAUX ─── */
        .totals-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .totals-table td { border: 1px solid #222; border-bottom: none; padding: 6px 12px; }
        .totals-table td:first-child { border-left: none; text-align: center; }
        .totals-table td:last-child { border-right: none; text-align: right; width: 22%; }
        .totals-table tr.ttc td { font-weight: 800; }

        .amount-words-row {
            border: 1px solid #222;
            padding: 10px 12px;
            font-size: 12px;
            line-height: 1.6;
        }
        .amount-words-row strong { font-weight: 700; }

        /* ─── MENTIONS / CONDITIONS ─── */
        .conditions-block {
            padding: 14px 60px 0;
            font-size: 12.5px;
            color: #111;
            line-height: 1.85;
        }
        .conditions-block .cond-line { text-decoration: underline; }
        .conditions-block .cond-strong { font-weight: 800; text-decoration: underline; }

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
            .user-chip, .sidebar-overlay, .main-content .topbar, .main-content .topbar-actions { display: none !important; }
            .page-area { padding: 0 !important; margin: 0 !important; }
            .invoice-container { box-shadow: none !important; border-radius: 0 !important; margin: 0 !important; }
            .status-badge { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .partners-row img { opacity: 1 !important; filter: none !important; }
        }
    </style>

    {{-- TOOLBAR --}}
    <div class="module-toolbar">
        <h2><span class="module-icon"><i class="bi bi-file-earmark-text"></i></span> Détail de la Proforma</h2>
        <div>
            <a href="{{ route('module3.quotes.index') }}" class="btn-ghost"><i class="bi bi-arrow-left"></i> Retour</a>
            <button onclick="window.print()" class="btn-accent orange"><i class="bi bi-printer"></i> Imprimer</button>
        </div>
    </div>

    {{-- DOCUMENT --}}
    <div class="invoice-container">

        {{-- EN-TÊTE --}}
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
                <div style="width:78px; flex-shrink:0;"></div>
            </div>
        </div>

        {{-- DATE / DESTINATAIRE / TITRE --}}
        <div class="invoice-meta-zone">
            <div class="meta-date">Douala, le {{ $quote->date->locale('fr')->translatedFormat('d F Y') }}</div>

            <div class="meta-recipient">
                <div class="name">{{ $quote->customer?->name ?? 'Client inconnu' }}</div>
                @if($quote->customer?->address)
                    <div class="line">{{ $quote->customer->address }}</div>
                @endif
                @if($quote->customer?->phone || $quote->customer?->email)
                    <div class="small">
                        @if($quote->customer?->phone) Tél : {{ $quote->customer->phone }} @endif
                        @if($quote->customer?->phone && $quote->customer?->email) &nbsp;·&nbsp; @endif
                        @if($quote->customer?->email) {{ $quote->customer->email }} @endif
                    </div>
                @endif
            </div>

            <div class="doc-title-row">
                <div class="doc-title">PROFORMA N°{{ $quote->reference }}</div>
                <!-- @php
                    $statusLabels = ['draft' => 'Brouillon', 'sent' => 'Envoyé', 'approved' => 'Accepté', 'rejected' => 'Rejeté', 'converted' => 'Converti'];
                    $s = $statusLabels[$quote->status] ?? $quote->status;
                @endphp
                <span class="status-badge {{ $quote->status }}">{{ $s }}</span> -->
            </div>
        </div>

        {{-- TABLEAU --}}
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
                    @forelse($quote->items as $item)
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
                        <td colspan="4">Aucun article dans cette Proforma</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- TOTAUX --}}
            @php
                $subtotal = $quote->subtotal;
                $tax = $quote->tax;
                $total = $quote->total;
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
                <tr class="ttc">
                    <td>TOTAL TTC</td>
                    <td>{{ number_format($total, 0, ',', ' ') }}</td>
                </tr>
            </table>

            {{-- MONTANT EN LETTRES --}}
            @if($total > 0)
            <div class="amount-words-row">
                La présente Proforma est arrêtée à la somme de :
                <strong>{{ \App\Helpers\NumberToWords::convert($total) }} FCFA TTC</strong>.
            </div>
            @endif
        </div>

        {{-- MENTIONS / CONDITIONS --}}
        <div class="conditions-block">
            <div class="cond-line">Disponibilité : En stock</div>
            <div class="cond-line">Conditions de paiement : Habituelles</div>
            <div class="cond-strong">Validité de l'offre : 30 Jours</div>
            <div class="cond-line">Equipements d'origine et à la marque !!!</div>
        </div>

        {{-- PIED DE PAGE --}}
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
</div>