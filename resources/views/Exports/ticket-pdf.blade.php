<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche Intervention - {{ $ticket->ticket_number }}</title>
    <style>
        /* ─── RESET & BASE ─── */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', 'Helvetica', Arial, sans-serif;
            font-size: 9.5px;
            background: #ffffff;
            color: #1a1a2e;
            padding: 14px 18px;
            line-height: 1.5;
        }

        /* ─── PAGE ─── */
        .page {
            max-width: 210mm;
            margin: 0 auto;
            background: #ffffff;
        }

        /* ─── EN-TÊTE SOCIÉTÉ ─── */
        .header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding-bottom: 10px;
            margin-bottom: 12px;
            border-bottom: 2.5px solid #1a7a3c;
        }
        .header-left { display: flex; align-items: center; gap: 12px; }
        .header-logo {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: contain;
            border: 1px solid #e2ece6;
        }
        .header-company .brand {
            font-size: 14px;
            font-weight: 800;
            color: #1a7a3c;
            letter-spacing: 0.3px;
        }
        .header-company .sub {
            font-size: 12px;
            font-weight: 700;
            color: #0d1f14;
        }
        .header-company .legal {
            display: block;
            font-size: 7.5px;
            color: #7a9185;
            margin-top: 1px;
            font-weight: 500;
        }
        .header-services {
            list-style: none;
            margin: 0;
            padding: 0;
            text-align: right;
            font-size: 7.5px;
            font-weight: 600;
            color: #0d1f14;
            columns: 2;
            column-gap: 18px;
        }
        .header-services li {
            margin-bottom: 1px;
            padding-left: 0;
        }
        .header-services li::before {
            content: "• ";
            color: #1a7a3c;
            font-weight: 900;
        }

        /* ─── TITRE PRINCIPAL ─── */
        .page-title {
            text-align: center;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            border: 1.5px solid #1a1a2e;
            padding: 5px 10px;
            margin-bottom: 14px;
            color: #1a1a2e;
            background: #f8faf9;
        }

        /* ─── BLOC INFORMATIONS ─── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr 1.3fr;
            gap: 10px;
            border: 1px solid #d4e0d8;
            border-radius: 6px;
            padding: 10px 12px;
            margin-bottom: 12px;
            background: #f8faf9;
        }
        .info-contact {
            font-size: 8.5px;
            color: #5a6b61;
            line-height: 1.7;
        }
        .info-contact .highlight { color: #1a7a3c; font-weight: 700; }
        .info-field {
            display: flex;
            flex-direction: column;
            border-bottom: 1px dotted #d4e0d8;
            padding-bottom: 2px;
            margin-bottom: 3px;
        }
        .info-field:last-child { border-bottom: none; margin-bottom: 0; }
        .info-field .label {
            font-size: 6.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a89e;
        }
        .info-field .value {
            font-size: 10px;
            font-weight: 600;
            color: #0d1f14;
        }
        .info-field .value.code {
            font-family: 'Courier New', monospace;
            letter-spacing: 0.3px;
            font-weight: 700;
            color: #1a7a3c;
        }

        /* ─── SECTIONS ─── */
        .section { margin-bottom: 10px; }
        .section-title {
            font-size: 8.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            border-bottom: 1.5px solid #1a1a2e;
            padding-bottom: 2px;
            margin-bottom: 4px;
            color: #1a1a2e;
        }
        .section-title .badge {
            font-size: 7px;
            font-weight: 700;
            color: #ffffff;
            background: #1a7a3c;
            padding: 1px 6px;
            border-radius: 3px;
            margin-left: 6px;
        }
        .section-text {
            font-size: 9.5px;
            color: #3d5c45;
            padding: 4px 6px;
            background: #ffffff;
            border-left: 2px solid #1a7a3c;
            border-radius: 2px;
            min-height: 20px;
        }

        /* ─── GARANTIE ─── */
        .warranty-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 6px;
        }
        .warranty-row .section-title { border: none; margin: 0; padding: 0; }
        .warranty-choices { display: flex; gap: 12px; align-items: center; }
        .warranty-choice {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 14px;
            border: 2px solid #d4e0d8;
            border-radius: 20px;
            color: #94a89e;
        }
        .warranty-choice.active {
            border-color: #1a7a3c;
            color: #1a7a3c;
            background: #e6f5ec;
        }

        /* ─── N.B ─── */
        .nb-box {
            font-size: 8px;
            color: #5a6b61;
            background: #f8faf9;
            border-radius: 4px;
            padding: 6px 10px;
            margin-bottom: 10px;
            border-left: 3px solid #1a7a3c;
        }
        .nb-box ol {
            margin: 2px 0 0 18px;
            padding: 0;
            list-style: none;
        }
        .nb-box li {
            margin-bottom: 1px;
            padding-left: 4px;
        }
        .nb-box li::before {
            content: "• ";
            color: #1a7a3c;
            font-weight: 700;
        }
        .nb-box strong { color: #0d1f14; }

        /* ─── SIGNATURES ─── */
        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 8px 0 10px;
        }
        .signature-block {
            text-align: center;
            border: 1px dashed #d4e0d8;
            border-radius: 6px;
            padding: 6px 10px 8px;
            background: #f8faf9;
        }
        .signature-label {
            font-size: 7.5px;
            font-weight: 700;
            color: #5a6b61;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            display: block;
            margin-bottom: 14px;
        }
        .signature-line {
            border-top: 1px solid #1a1a2e;
            margin: 0 16px;
        }
        .signature-name {
            font-size: 8px;
            color: #5a6b61;
            margin-top: 3px;
            display: block;
        }

        /* ─── TABLEAU PIÈCES ─── */
        .parts-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
            font-size: 9px;
        }
        .parts-table thead th {
            font-size: 7px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a89e;
            padding: 3px 6px;
            border-bottom: 1.5px solid #1a1a2e;
            text-align: left;
            background: #f8faf9;
        }
        .parts-table tbody td {
            padding: 4px 6px;
            border-bottom: 1px dotted #d4e0d8;
            color: #0d1f14;
            vertical-align: middle;
        }
        .parts-table .col-qty { text-align: center; width: 40px; }
        .parts-table .col-price { text-align: right; width: 80px; font-weight: 600; }
        .parts-table .col-total { text-align: right; width: 90px; font-weight: 700; color: #1a7a3c; }
        .parts-table .total-row td {
            border-top: 2px solid #1a1a2e;
            font-weight: 800;
            font-size: 9px;
            padding-top: 5px;
            background: #f8faf9;
        }
        .parts-table .total-row td:last-child {
            color: #1a7a3c;
            font-size: 10px;
        }
        .parts-table .empty-row td {
            text-align: center;
            color: #94a89e;
            font-style: italic;
            padding: 8px;
        }

        /* ─── RAPPORT ─── */
        .report-box {
            background: #ffffff;
            border-radius: 4px;
            padding: 6px 8px;
            border-left: 3px solid #1a7a3c;
            font-size: 9px;
            color: #3d5c45;
            line-height: 1.6;
            min-height: 28px;
        }
        .duration-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 3px;
            font-size: 8px;
            color: #5a6b61;
        }
        .duration-row strong { color: #0d1f14; }

        /* ─── PIED DE PAGE ─── */
        .footer {
            margin-top: 12px;
            padding-top: 8px;
            border-top: 1px solid #d4e0d8;
            display: flex;
            justify-content: space-between;
            font-size: 6.5px;
            color: #94a89e;
        }
        .footer strong { color: #1a7a3c; }
        .footer .footer-right { text-align: right; }

        /* ─── PRINT OPTIMIZATION ─── */
        @media print {
            body { padding: 8px 10px; }
            .header-services { columns: 2; }
            .info-grid { background: #f8faf9; }
            .nb-box { background: #f8faf9; }
            .report-box { background: #ffffff; }
            .page { min-height: 100%; }
            .signature-block { background: #f8faf9; }
        }

        /* ─── PAGE BREAK ─── */
        .page-break {
            page-break-before: always;
            border-top: 2px dashed #d4e0d8;
            margin-top: 16px;
            padding-top: 16px;
        }
    </style>
</head>
<body>

<div class="page">

    {{-- ============================================================
         EN-TÊTE SOCIÉTÉ
    ============================================================ --}}
    <div class="header">
        <div class="header-left">
            @php
                $logoPath = public_path('images/logo-jr.jpg');
                $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
            @endphp
            @if($logoData)
                <img src="data:image/jpeg;base64,{{ $logoData }}" alt="JR Computer" class="header-logo">
            @else
                <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#1a7a3c,#22a352);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:14px;">JR</div>
            @endif
            <div class="header-company">
                <span class="brand">JR Computer Sarl</span>
                <span class="sub">Ingénierie Informatique &amp; Télécommunications</span>
                <span class="legal">Société à responsabilité limitée · Douala, Cameroun</span>
            </div>
        </div>
        <ul class="header-services">
            <li>Infrastructures réseaux</li>
            <li>Interconnexion des sites</li>
            <li>Solutions de collaboration</li>
            <li>Stockage des données</li>
            <li>Infogérance</li>
            <li>Protection électrique</li>
            <li>Sécurité électronique</li>
            <li>Distribution des systèmes informatiques</li>
        </ul>
    </div>

    {{-- ============================================================
         TITRE
    ============================================================ --}}
    <div class="page-title">Fiche d'intervention / Entrée matériel</div>

    {{-- ============================================================
         BLOC INFORMATIONS
    ============================================================ --}}
    <div class="info-grid">
        {{-- Contact --}}
        <div class="info-contact">
            <div><span class="highlight">✉</span> infos@jr-computer.net</div>
            <div><span class="highlight">🌐</span> www.jrcomputersarl.net</div>
            <div style="margin-top:4px;"><span class="highlight">📞</span> 2 33 42 21 53</div>
            <div><span class="highlight">📱</span> 6 99 96 96 08</div>
            <div><span class="highlight">📱</span> 6 99 00 38 38</div>
        </div>

        {{-- Appareil --}}
        <div class="info-device">
            <div class="info-field">
                <span class="label">Type d'appareil</span>
                <span class="value">{{ $ticket->device_model ?? $ticket->product?->name ?? '—' }}</span>
            </div>
            <div class="info-field">
                <span class="label">Numéro de série</span>
                <span class="value code">{{ $ticket->serial_number ?? '—' }}</span>
            </div>
        </div>

        {{-- Client --}}
        <div class="info-client">
            <div class="info-field">
                <span class="label">Client</span>
                <span class="value">{{ $ticket->customer?->name ?? '—' }}</span>
            </div>
            <div class="info-field">
                <span class="label">Téléphone</span>
                <span class="value">{{ $ticket->customer?->phone ?? '—' }}</span>
            </div>
            <div class="info-field">
                <span class="label">Date d'entrée</span>
                <span class="value">{{ $ticket->created_at ? $ticket->created_at->format('d/m/Y') : '—' }}</span>
            </div>
        </div>
    </div>

    {{-- ============================================================
         PANNES SIGNALÉES
    ============================================================ --}}
    <div class="section">
        <div class="section-title">Pannes signalées</div>
        <div class="section-text">{{ $ticket->description_failure ?? 'Aucune panne signalée' }}</div>
    </div>

    {{-- ============================================================
         GARANTIE
    ============================================================ --}}
    <div class="section warranty-row">
        <div class="section-title">Matériel sous garantie</div>
        <div class="warranty-choices">
            <span class="warranty-choice {{ $ticket->is_warranty ? 'active' : '' }}">✓ Oui</span>
            <span class="warranty-choice {{ !$ticket->is_warranty ? 'active' : '' }}">✓ Non</span>
        </div>
    </div>

    {{-- ============================================================
         NOTA BENE
    ============================================================ --}}
    <div class="nb-box">
        <strong>N.B :</strong>
        <ol>
            <li>Le délai de retrait du matériel après diagnostic est de <strong>72 heures</strong> ;</li>
            <li>Passé ce délai, les frais de magasinage sont facturés à <strong>1.000 F.CFA</strong> par journée ;</li>
            <li>JR Computer Sarl décline toutes responsabilités pour tout dépôt de matériel excédant un <strong>mois</strong> ;</li>
            <li>Les frais de diagnostic s'élèvent à <strong>10.000 F.CFA</strong> pour tout matériel hors contrat.</li>
        </ol>
    </div>

    <!-- {{-- ============================================================
         SIGNATURES (HAUT)
    ============================================================ --}}
    <div class="signatures">
        <div class="signature-block">
            <span class="signature-label">Nom et Visa Inspecteur de Maintenance</span>
            <div class="signature-line"></div>
            <span class="signature-name">{{ $ticket->technician?->name ?? 'Technicien' }}</span>
        </div>
        <div class="signature-block">
            <span class="signature-label">Nom et Visa Client</span>
            <div class="signature-line"></div>
            <span class="signature-name">{{ $ticket->customer?->name ?? 'Client' }}</span>
        </div>
    </div> -->

    {{-- ============================================================
         RAPPORT D'INTERVENTION
    ============================================================ --}}
    <div class="page-title" style="font-size:11px;margin-top:4px;border-color:#1a7a3c;background:#e6f5ec;">
        Rapport d'intervention
    </div>

    {{-- Diagnostic --}}
    <div class="section">
        <div class="section-title">
            Diagnostic du technicien
            <span class="badge">Rapport technique</span>
        </div>
        <div class="report-box">
            {{ $ticket->technical_report ?? $ticket->intervention?->technical_report ?? 'Aucun rapport disponible' }}
        </div>
        @if($ticket->duration_minutes || ($ticket->intervention?->duration_minutes ?? 0) > 0)
            <div class="duration-row">
                <span><strong>⏱ Durée :</strong> {{ $ticket->duration_minutes ?? $ticket->intervention?->duration_minutes ?? 0 }} minutes</span>
            </div>
        @endif
    </div>

    {{-- Pièces remplacées --}}
    <div class="section">
        <div class="section-title">
            Pièces remplacées
            <span class="badge">Détail</span>
        </div>

        @if($ticket->items && $ticket->items->count() > 0)
            <table class="parts-table">
                <thead>
                    <tr>
                        <th style="width:50%;">Désignation</th>
                        <th class="col-qty">Qté</th>
                        <th class="col-price">Prix unit.</th>
                        <th class="col-total">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalParts = 0; @endphp
                    @foreach($ticket->items as $item)
                        @php $lineTotal = $item->quantity * $item->unit_price; $totalParts += $lineTotal; @endphp
                        <tr>
                            <td>{{ $item->sparePart?->name ?? 'Pièce' }}</td>
                            <td class="col-qty">{{ $item->quantity }}</td>
                            <td class="col-price">{{ number_format($item->unit_price, 0, ',', ' ') }}</td>
                            <td class="col-total">{{ number_format($lineTotal, 0, ',', ' ') }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3" style="text-align:right;padding-right:6px;"><strong>TOTAL PIÈCES</strong></td>
                        <td class="col-total">{{ number_format($totalParts, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </tbody>
            </table>
        @else
            <table class="parts-table">
                <tbody>
                    <tr class="empty-row">
                        <td colspan="4">Aucune pièce utilisée pour cette intervention.</td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>

    {{-- ============================================================
         SIGNATURES (BAS)
    ============================================================ --}}
    <div class="signatures" style="margin-top:4px;">
        <div class="signature-block">
            <span class="signature-label">Nom et Visa Inspecteur de Maintenance</span>
            <div class="signature-line"></div>
            <span class="signature-name">{{ $ticket->technician?->name ?? 'Technicien' }}</span>
        </div>
        <div class="signature-block">
            <span class="signature-label">Nom et Visa Client</span>
            <div class="signature-line"></div>
            <span class="signature-name">{{ $ticket->customer?->name ?? 'Client' }}</span>
        </div>
    </div>

    {{-- ============================================================
         PIED DE PAGE
    ============================================================ --}}
    <div class="footer">
        <div class="footer-left">
            <strong>JR Computer Sarl</strong> · Douala, Cameroun
        </div>
        <div class="footer-right">
            Document généré le {{ $generated_at ?? now()->format('d/m/Y à H:i') }}
        </div>
    </div>

</div>

</body>
</html>