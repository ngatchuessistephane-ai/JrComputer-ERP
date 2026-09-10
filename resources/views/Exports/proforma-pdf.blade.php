<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>PROFORMA {{ $quote->reference }}</title>
    <style>
        /* ─── STYLES IDENTIQUES À QUOTE-SHOW SANS TOOLBAR ─── */
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@500;700&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #ffffff;
            color: #1a1a1a;
            padding: 20px;
            font-size: 12px;
        }

        .invoice-container {
            max-width: 210mm;
            margin: 0 auto;
            background: #ffffff;
        }

        /* ─── EN-TÊTE / LETTERHEAD ─── */
        .invoice-letterhead {
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
        .invoice-meta-zone { padding: 18px 40px 0; }
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

        /* ─── TABLEAU ─── */
        .proforma-box {
            margin: 0 40px 0;
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
            padding: 14px 40px 0;
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
        .footer-logo .fallback { font-size: 16px; font-weight: 900; color: #2f6b3f; font-family: 'Dancing Script', cursive; }
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
    </style>
</head>
<body>

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

</body>
</html>