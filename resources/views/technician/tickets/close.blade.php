@extends('layouts.app')

@section('content')
<style>
    /* ============================================================
       STYLES GÉNÉRAUX
       ============================================================ */
    .tech-close-page {
        zoom: 0.90;
        max-width: 1100px;
        margin: 0 auto;
        padding: 20px 0;
    }

    /* === Barre d'actions === */
    .tech-close-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }
    .tech-close-header h2 {
        font-family: 'Syne', sans-serif;
        font-size: 20px;
        font-weight: 800;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .tech-close-header h2 i {
        color: var(--brand-green);
    }

    .tech-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .tech-btn-success {
        background: linear-gradient(135deg, #10b981, #34d399);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
    }
    .tech-btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(16, 185, 129, 0.35);
    }
    .tech-btn-secondary {
        background: var(--bg-page);
        border: 1px solid var(--border-color);
        color: var(--text-secondary);
    }
    .tech-btn-secondary:hover {
        border-color: var(--brand-green);
        color: var(--brand-green);
        background: var(--brand-green-xlight);
    }
    .btn-accent.orange {
        background: linear-gradient(135deg, #f07d00, #ff9c2a);
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(240, 125, 0, 0.25);
    }
    .btn-accent.orange:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(240, 125, 0, 0.35);
    }

    /* === FICHE : réplique du document papier === */
    .fiche-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 24px 28px;
        margin-bottom: 20px;
    }

    .fiche-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
        padding-bottom: 14px;
        margin-bottom: 16px;
        border-bottom: 2px solid var(--text-primary);
    }
    .fiche-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .fiche-logo {
        width: 56px;
        height: 56px;
        object-fit: contain;
        border-radius: 50%;
    }
    .fiche-company strong {
        display: block;
        font-family: 'Syne', sans-serif;
        font-size: 16px;
        color: var(--text-primary);
    }
    .fiche-company span {
        display: block;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
    }
    .fiche-company small {
        display: block;
        font-size: 10px;
        color: var(--text-muted);
        margin-top: 2px;
    }
    .fiche-services {
        list-style: none;
        margin: 0;
        padding: 0;
        text-align: right;
    }
    .fiche-services li {
        font-size: 10px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 2px;
    }
    .fiche-services li::before {
        content: "• ";
        color: var(--brand-green);
    }

    .fiche-title {
        text-align: center;
        font-family: 'Syne', sans-serif;
        font-size: 15px;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: var(--text-primary);
        border: 1px solid var(--text-primary);
        padding: 6px;
        margin-bottom: 16px;
    }
    .fiche-title-sub {
        margin-top: 24px;
    }

    .fiche-info-box {
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 16px;
        display: grid;
        grid-template-columns: 1fr 1.1fr 1.3fr;
        gap: 16px;
    }
    @media (max-width: 768px) {
        .fiche-info-box {
            grid-template-columns: 1fr;
        }
    }
    .fiche-contact {
        font-size: 11px;
        color: var(--text-secondary);
        line-height: 1.7;
    }
    .fiche-device-fields,
    .fiche-client-fields {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .fiche-field {
        display: flex;
        flex-direction: column;
        border-bottom: 1px dotted var(--border-color);
        padding-bottom: 3px;
    }
    .fiche-field-label {
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-muted);
    }
    .fiche-field-value {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .fiche-section {
        margin-bottom: 14px;
    }
    .fiche-section-title {
        display: block;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-primary);
        border-bottom: 1px solid var(--text-primary);
        padding-bottom: 3px;
        margin-bottom: 6px;
    }
    .fiche-section-title .required {
        color: #dc2626;
    }
    .fiche-static-text {
        font-size: 12px;
        color: var(--text-secondary);
        padding: 6px 0;
        border-bottom: 1px dotted var(--border-color);
    }

    .fiche-textarea {
        width: 100%;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background: var(--bg-page);
        color: var(--text-primary);
        font-size: 12px;
        padding: 8px 10px;
        outline: none;
        resize: vertical;
        min-height: 80px;
    }
    .fiche-textarea:focus {
        border-color: var(--brand-green);
        box-shadow: 0 0 0 3px rgba(26, 122, 60, 0.1);
    }
    .fiche-textarea.error {
        border-color: #dc2626;
    }
    .fiche-error {
        font-size: 10px;
        color: #dc2626;
        margin-top: 3px;
        display: block;
    }

    .fiche-warranty {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
    }
    .fiche-warranty .fiche-section-title {
        border-bottom: none;
        padding-bottom: 0;
        margin-bottom: 0;
    }
    .fiche-warranty-choices {
        display: flex;
        gap: 14px;
        align-items: center;
    }
    .fiche-choice {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        padding: 2px 14px;
    }
    .fiche-choice-active {
        border: 2px solid var(--brand-green);
        border-radius: 30px;
        color: var(--brand-green);
    }

    .fiche-nb {
        font-size: 10px;
        color: var(--text-muted);
        background: var(--bg-page);
        border-radius: 8px;
        padding: 8px 12px;
        margin-bottom: 14px;
    }
    .fiche-nb ol {
        margin: 4px 0 0 16px;
        padding: 0;
    }
    .fiche-nb li {
        margin-bottom: 2px;
    }

    .fiche-signatures {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 2px;
    }
    .fiche-signature-block {
        text-align: center;
    }
    .fiche-signature-label {
        font-size: 10px;
        font-weight: 700;
        color: var(--text-secondary);
        display: block;
        margin-bottom: 28px;
    }
    .fiche-signature-line {
        border-top: 1px solid var(--border-color);
    }

    .fiche-parts-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 8px;
    }
    .fiche-parts-table th {
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        text-align: left;
        padding: 4px 6px;
        border-bottom: 1px solid var(--text-primary);
    }
    .fiche-parts-table td {
        padding: 6px;
        border-bottom: 1px dotted var(--border-color);
        vertical-align: middle;
    }
    .fiche-parts-table th.col-qty,
    .fiche-parts-table td.col-qty {
        width: 60px;
    }
    .fiche-parts-table th.col-price,
    .fiche-parts-table td.col-price {
        width: 120px;
        text-align: right;
        font-weight: 700;
        color: var(--text-primary);
    }
    .fiche-parts-table th.col-action,
    .fiche-parts-table td.col-action {
        width: 32px;
    }
    .fiche-select {
        width: 100%;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        background: var(--bg-page);
        color: var(--text-primary);
        font-size: 12px;
        padding: 6px 8px;
        outline: none;
    }
    .qty-input {
        width: 100%;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        background: var(--bg-page);
        color: var(--text-primary);
        font-size: 12px;
        padding: 4px 6px;
        outline: none;
        text-align: center;
    }
    .fiche-add-btn {
        margin-top: 4px;
        padding: 4px 12px;
        font-size: 11px;
        width: auto;
    }
    .remove-part-btn {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        background: var(--bg-page);
        color: #dc2626;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .remove-part-btn:hover {
        border-color: #dc2626;
        background: #fee2e2;
    }

    .fiche-duration-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        border-top: 1px solid var(--border-color);
        padding-top: 12px;
        margin-bottom: 14px;
    }
    .fiche-field-inline {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .fiche-field-inline .fiche-section-title {
        border: none;
        margin: 0;
        padding: 0;
    }
    .fiche-duration-input {
        width: 90px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        background: var(--bg-page);
        color: var(--text-primary);
        padding: 4px 6px;
        outline: none;
        font-size: 12px;
    }
    .fiche-total-amount {
        font-family: 'DM Mono', monospace;
        font-weight: 800;
        color: var(--brand-green);
        font-size: 15px;
    }

    .fiche-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 4px;
    }

    /* ✅ INDICATEURS STOCK */
    .stock-indicator {
        font-size: 10px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 12px;
        display: inline-block;
        margin-left: 6px;
    }
    .stock-ok {
        background: #d1fae5;
        color: #065f46;
    }
    .stock-low {
        background: #fef3c7;
        color: #d97706;
    }
    .stock-out {
        background: #fee2e2;
        color: #dc2626;
    }
    .stock-warning {
        background: #fef3c7;
        color: #d97706;
        animation: pulse-warning 1.5s ease-in-out infinite;
    }
    @keyframes pulse-warning {
        0%,
        100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    /* ============================================================
       STYLES POUR L'IMPRESSION
       ============================================================ */
    @media print {
        /* Cacher tout ce qui n'est pas la fiche */
        .topbar,
        .sidebar,
        .sidebar-overlay,
        #toast-container,
        .notif-wrap,
        .dropdown,
        .tech-close-header,
        .fiche-actions,
        .no-print {
            display: none !important;
        }

        /* Ajuster la page */
        body {
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .page-area {
            padding: 0 !important;
            margin: 0 !important;
        }
        .tech-close-page {
            zoom: 1 !important;
            max-width: 100% !important;
            padding: 10px !important;
        }

        /* Style de la fiche en impression */
        .fiche-card {
            border: 1px solid #000 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            padding: 16px 20px !important;
            margin: 0 !important;
            background: white !important;
        }
        .fiche-header {
            border-bottom: 2px solid #000 !important;
        }
        .fiche-title {
            border: 1px solid #000 !important;
        }
        .fiche-section-title {
            border-bottom: 1px solid #000 !important;
        }
        .fiche-info-box {
            border: 1px solid #000 !important;
        }
        .fiche-parts-table th {
            border-bottom: 1px solid #000 !important;
        }
        .fiche-parts-table td {
            border-bottom: 1px dotted #ccc !important;
        }
        .fiche-duration-total {
            border-top: 1px solid #000 !important;
        }
        .fiche-nb {
            border: 1px solid #ccc !important;
        }
        .fiche-signature-line {
            border-top: 1px solid #000 !important;
        }
        .fiche-textarea {
            border: 1px solid #000 !important;
            background: white !important;
            min-height: 60px !important;
        }
        .fiche-select,
        .qty-input {
            border: 1px solid #000 !important;
            background: white !important;
        }

        /* Cacher les boutons d'action des lignes */
        .remove-part-btn {
            display: none !important;
        }
        .fiche-add-btn {
            display: none !important;
        }

        /* Couleurs en noir et blanc */
        .fiche-choice-active {
            border-color: #000 !important;
            color: #000 !important;
        }
        .fiche-total-amount {
            color: #000 !important;
        }
        .fiche-section-title .required {
            color: #000 !important;
        }

        /* Éviter les coupures de page */
        .fiche-card {
            page-break-inside: avoid;
        }
        .fiche-signatures {
            page-break-inside: avoid;
        }
    }
</style>

<div class="tech-close-page" id="techClosePage">

    {{-- BARRE D'ACTIONS (cachée à l'impression) --}}
    <div class="tech-close-header no-print">
        <h2><i class="bi bi-check2-circle"></i> Clôturer l'intervention</h2>
        <div>
            <a href="{{ route('technician.tickets.show', $ticket->id) }}" class="tech-btn tech-btn-secondary">
                <i class="bi bi-arrow-left"></i> Annuler
            </a>
            <button onclick="window.print()" class="tech-btn" style="background: linear-gradient(135deg, #f07d00, #ff9c2a); color: white; border: none; box-shadow: 0 4px 12px rgba(240,125,0,0.25);">
                <i class="bi bi-printer"></i> Imprimer
            </button>
        </div>
    </div>

    {{-- FORMULAIRE DE CLÔTURE --}}
    <form action="{{ route('technician.tickets.close', $ticket->id) }}" method="POST" id="closeForm" onsubmit="return validateStock()">
        @csrf

        <div class="fiche-card" id="ficheToPrint">

            {{-- En-tête société --}}
            <div class="fiche-header">
                <div class="fiche-header-left">
                    <img src="{{ asset('images/logo-jr.jpg') }}" alt="JR Computer Sarl" class="fiche-logo">
                    <div class="fiche-company">
                        <strong>Ingénierie Informatique</strong>
                        <span>&amp; Télécommunications</span>
                        <small>Computer sarl</small>
                    </div>
                </div>
                <ul class="fiche-services">
                    <li>Infrastructures réseaux</li>
                    <li>Interconnexion des sites</li>
                    <li>Solution de collaboration</li>
                    <li>Stockage des données</li>
                    <li>Infogérance</li>
                    <li>Protection électrique</li>
                    <li>Sécurité électronique</li>
                    <li>Distribution des systèmes informatiques</li>
                </ul>
            </div>

            <div class="fiche-title">Fiche d'intervention / Entrée matériel</div>

            {{-- Bloc coordonnées + infos --}}
            <div class="fiche-info-box">
                <div class="fiche-contact">
                    <div>infos@jr-computer.net</div>
                    <div>www.jrcomputersarl.net</div>
                    <div style="margin-top: 6px;">Tél. : 2 33 42 21 53</div>
                    <div>6 99 96 96 08</div>
                    <div>6 99 00 38 38</div>
                </div>

                <div class="fiche-device-fields">
                    <div class="fiche-field">
                        <span class="fiche-field-label">Type d'appareil</span>
                        <span class="fiche-field-value">{{ $ticket->device_model ?? $ticket->product->name ?? '—' }}</span>
                    </div>
                    <div class="fiche-field">
                        <span class="fiche-field-label">N° série</span>
                        <span class="fiche-field-value" style="font-family: 'DM Mono', monospace;">{{ $ticket->serial_number ?? '—' }}</span>
                    </div>
                </div>

                <div class="fiche-client-fields">
                    <div class="fiche-field">
                        <span class="fiche-field-label">Client</span>
                        <span class="fiche-field-value">{{ $ticket->customer->name ?? '—' }}</span>
                    </div>
                    <div class="fiche-field">
                        <span class="fiche-field-label">Tél.</span>
                        <span class="fiche-field-value">{{ $ticket->customer->phone ?? '—' }}</span>
                    </div>
                    <div class="fiche-field">
                        <span class="fiche-field-label">Date</span>
                        <span class="fiche-field-value">{{ optional($ticket->created_at)->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Pannes signalées --}}
            <div class="fiche-section">
                <span class="fiche-section-title">Pannes signalées</span>
                <div class="fiche-static-text">{{ $ticket->description_failure ?? '—' }}</div>
            </div>

            {{-- Matériel sous garantie --}}
            <div class="fiche-section fiche-warranty">
                <span class="fiche-section-title">Matériel sous garantie</span>
                <div class="fiche-warranty-choices">
                    <span class="fiche-choice {{ $ticket->is_warranty ? 'fiche-choice-active' : '' }}">Oui</span>
                    <span class="fiche-choice {{ !$ticket->is_warranty ? 'fiche-choice-active' : '' }}">Non</span>
                </div>
            </div>

            <div class="fiche-nb">
                <strong>N.B :</strong>
                <ol>
                    <li>1- Le délai de retrait du matériel après diagnostic est de <strong>72h</strong> ;</li>
                    <li>2- Passé ce délai, les frais de magasinage sont facturés à <strong>1.000 F.CFA</strong> par journée ;</li>
                    <li>3- JR Computer Sarl décline toutes responsabilités pour tout dépôt de matériel excédant un <strong>(1) mois</strong> ;</li>
                    <li>4- Les frais de diagnostic s'élèvent à <strong>10.000 F.CFA</strong> pour tout matériel hors contrat.</li>
                </ol>
            </div>

            {{-- RAPPORT D'INTERVENTION --}}
            <div class="fiche-title fiche-title-sub">Rapport d'intervention</div>

            {{-- Diagnostic du technicien --}}
            <div class="fiche-section">
                <span class="fiche-section-title">Diagnostic du technicien <span class="required">*</span></span>
                <textarea name="technical_report"
                          class="fiche-textarea @error('technical_report') error @enderror"
                          rows="4" required
                          placeholder="Décrivez le diagnostic posé, les observations relevées...">{{ old('technical_report') }}</textarea>
                @error('technical_report')
                    <span class="fiche-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Solution apportée / pièces remplacées --}}
            <div class="fiche-section">
                <span class="fiche-section-title">Pièces remplacées</span>
                <table class="fiche-parts-table">
                    <thead>
                        <tr>
                            <th>Pièce / Solution</th>
                            <th class="col-qty">Qté</th>
                            <th class="col-price">Prix unit.</th>
                            <th class="col-action no-print"></th>
                        </tr>
                    </thead>
                    <tbody id="parts-body">
                        <tr class="part-row">
                            <td>
                                <select name="parts[0][spare_part_id]" class="fiche-select part-select" data-row="0">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($availableParts as $part)
                                        <option value="{{ $part->id }}"
                                                data-price="{{ $part->selling_price }}"
                                                data-stock="{{ $part->quantity_in_stock }}"
                                                data-name="{{ $part->name }}">
                                            {{ $part->name }}
                                            (stock: {{ $part->quantity_in_stock }})
                                            @if($part->quantity_in_stock <= 0)
                                                🔴 RUPTURE
                                            @elseif($part->isLowStock())
                                                ⚠️ STOCK BAS
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="col-qty">
                                <input type="number" name="parts[0][quantity]" class="qty-input" value="1" min="1" data-row="0">
                            </td>
                            <td class="col-price"><span class="line-price">0 FCFA</span></td>
                            <td class="col-action no-print">
                                <button type="button" class="remove-part-btn"><i class="bi bi-trash3"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" id="add-part" class="tech-btn tech-btn-secondary fiche-add-btn no-print">
                    <i class="bi bi-plus-lg"></i> Ajouter une pièce
                </button>
                <div id="stock-error-container" style="margin-top: 8px;"></div>
            </div>

            {{-- Durée + total --}}
            <div class="fiche-duration-total">
                <div class="fiche-field-inline">
                    <span class="fiche-section-title">Durée (minutes)</span>
                    <input type="number" name="duration_minutes" class="fiche-duration-input"
                           value="{{ old('duration_minutes') }}" placeholder="90" min="0">
                </div>
                <div class="fiche-field-inline">
                    <span class="fiche-section-title">Total pièces</span>
                    <span id="fiche-total-amount" class="fiche-total-amount">0 FCFA</span>
                </div>
            </div>

            {{-- SIGNATURES --}}
            <div class="fiche-signatures">
                <div class="fiche-signature-block">
                    <span class="fiche-signature-label">Nom et Visa Inspecteur de Maintenance</span>
                    <div class="fiche-signature-line"></div>
                    <span style="font-size: 11px; color: var(--text-muted); margin-top: 4px; display: block;">{{ Auth::user()->name ?? 'Technicien' }}</span>
                </div>
                <div class="fiche-signature-block">
                    <span class="fiche-signature-label">Nom et Visa Client</span>
                    <div class="fiche-signature-line"></div>
                    <span style="font-size: 11px; color: var(--text-muted); margin-top: 4px; display: block;">Signature manuelle</span>
                </div>
            </div>

        </div>

        {{-- Boutons d'action (cachés à l'impression) --}}
        <div class="fiche-actions no-print">
            <a href="{{ route('technician.tickets.show', $ticket->id) }}" class="tech-btn tech-btn-secondary">
                <i class="bi bi-x-lg"></i> Annuler
            </a>
            <button type="submit" class="tech-btn tech-btn-success" id="submitBtn">
                <i class="bi bi-check2-circle"></i> Clôturer le ticket
            </button>
        </div>
    </form>

</div>

{{-- ============================================================
     SCRIPTS
     ============================================================ --}}
<script>
    let partIndex = 1;

    // ✅ Fonction pour vérifier le stock avant soumission
    function validateStock() {
        const errors = [];
        const rows = document.querySelectorAll('.part-row');

        rows.forEach(function(row) {
            const select = row.querySelector('.part-select');
            const qtyInput = row.querySelector('.qty-input');
            const selectedOption = select.selectedOptions[0];

            if (selectedOption && selectedOption.value) {
                const stock = parseInt(selectedOption.dataset.stock) || 0;
                const qty = parseInt(qtyInput.value) || 0;
                const name = selectedOption.dataset.name || 'Pièce';

                if (qty > stock) {
                    errors.push(`❌ Stock insuffisant pour "${name}". Disponible: ${stock}, Demandé: ${qty}`);
                }
                if (stock <= 0 && qty > 0) {
                    errors.push(`🔴 "${name}" est en rupture de stock (${stock} unités).`);
                }
            }
        });

        if (errors.length > 0) {
            const container = document.getElementById('stock-error-container');
            container.innerHTML = `
                <div style="background: #fee2e2; border: 1px solid #dc2626; border-radius: 8px; padding: 10px 14px; color: #dc2626; font-size: 12px;">
                    <strong>⚠️ Erreur de stock :</strong><br>
                    ${errors.join('<br>')}
                </div>
            `;
            container.scrollIntoView({ behavior: 'smooth', block: 'center' });

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            setTimeout(() => { submitBtn.disabled = false; }, 3000);

            return false;
        }
        return true;
    }

    function buildPartRow(index) {
        const tr = document.createElement('tr');
        tr.className = 'part-row';
        tr.innerHTML = `
            <td>
                <select name="parts[${index}][spare_part_id]" class="fiche-select part-select" data-row="${index}">
                    <option value="">-- Sélectionner --</option>
                    @foreach($availableParts as $part)
                        <option value="{{ $part->id }}"
                                data-price="{{ $part->selling_price }}"
                                data-stock="{{ $part->quantity_in_stock }}"
                                data-name="{{ $part->name }}">
                            {{ $part->name }}
                            (stock: {{ $part->quantity_in_stock }})
                            @if($part->quantity_in_stock <= 0)
                                🔴 RUPTURE
                            @elseif($part->isLowStock())
                                ⚠️ STOCK BAS
                            @endif
                        </option>
                    @endforeach
                </select>
            </td>
            <td class="col-qty">
                <input type="number" name="parts[${index}][quantity]" class="qty-input" value="1" min="1" data-row="${index}">
            </td>
            <td class="col-price"><span class="line-price">0 FCFA</span></td>
            <td class="col-action no-print">
                <button type="button" class="remove-part-btn"><i class="bi bi-trash3"></i></button>
            </td>
        `;
        return tr;
    }

    function recalcTotals() {
        let total = 0;
        let hasStockIssue = false;
        const errors = [];

        document.querySelectorAll('.part-row').forEach(function(row) {
            const select = row.querySelector('.part-select');
            const qtyInput = row.querySelector('.qty-input');
            const qty = parseFloat(qtyInput.value) || 0;
            const opt = select.selectedOptions[0];
            const price = (opt && opt.dataset.price) ? parseFloat(opt.dataset.price) || 0 : 0;
            const stock = (opt && opt.dataset.stock) ? parseInt(opt.dataset.stock) || 0 : 0;
            const name = (opt && opt.dataset.name) || 'Pièce';

            const lineTotal = price * qty;
            row.querySelector('.line-price').textContent = lineTotal.toLocaleString('fr-FR') + ' FCFA';
            total += lineTotal;

            if (opt && opt.value) {
                if (qty > stock) {
                    hasStockIssue = true;
                    errors.push(`❌ ${name}: stock insuffisant (${stock} disponible, ${qty} demandé)`);
                    qtyInput.style.borderColor = '#dc2626';
                    qtyInput.style.backgroundColor = '#fee2e2';
                } else if (stock <= 0 && qty > 0) {
                    hasStockIssue = true;
                    errors.push(`🔴 ${name}: rupture de stock`);
                    qtyInput.style.borderColor = '#dc2626';
                    qtyInput.style.backgroundColor = '#fee2e2';
                } else {
                    qtyInput.style.borderColor = '';
                    qtyInput.style.backgroundColor = '';
                }
            }
        });

        document.getElementById('fiche-total-amount').textContent = total.toLocaleString('fr-FR') + ' FCFA';

        const container = document.getElementById('stock-error-container');
        if (hasStockIssue && errors.length > 0) {
            container.innerHTML = `
                <div style="background: #fee2e2; border: 1px solid #dc2626; border-radius: 8px; padding: 10px 14px; color: #dc2626; font-size: 12px;">
                    <strong>⚠️ Problème de stock :</strong><br>
                    ${errors.join('<br>')}
                </div>
            `;
        } else {
            container.innerHTML = '';
        }
    }

    // Événements pour la validation en temps réel
    document.getElementById('parts-body').addEventListener('change', function(e) {
        if (e.target.classList.contains('part-select') || e.target.classList.contains('qty-input')) {
            recalcTotals();
        }
    });

    document.getElementById('parts-body').addEventListener('click', function(e) {
        const btn = e.target.closest('.remove-part-btn');
        if (btn) {
            const rows = document.querySelectorAll('.part-row');
            if (rows.length > 1) {
                btn.closest('tr').remove();
                recalcTotals();
            }
        }
    });

    document.getElementById('add-part').addEventListener('click', function() {
        document.getElementById('parts-body').appendChild(buildPartRow(partIndex));
        partIndex++;
        recalcTotals();
    });

    recalcTotals();
</script>

</div>
@endsection