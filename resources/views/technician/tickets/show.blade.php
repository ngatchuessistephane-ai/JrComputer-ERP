@extends('layouts.app')

@section('content')
<div class="jr-container">
    <style>
        /* ════════════════════════════════════════════════════════════
           FICHE D'INTERVENTION — VERSION DÉZOOMÉE (0.95)
           Alignée sur le design system app.blade.php
        ════════════════════════════════════════════════════════════ */

        .jr-container {
            zoom: 0.95;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            color: var(--text-primary);
            max-width: 1240px;
            margin: 0 auto;
        }

        /* ─── FIL D'ARIANE / BARRE SUPÉRIEURE ─── */
        .jr-top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 22px;
        }
        .jr-top-bar h2 {
            font-size: 19px;
            font-weight: 800;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.3px;
            color: var(--text-primary);
        }
        .jr-top-bar h2 .ticket-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--brand-green-xlight);
            color: var(--brand-green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        /* ─── FICHE UNIQUE CENTRALISÉE ─── */
        .jr-intervention-sheet {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: var(--shadow-card);
            overflow: hidden;
        }

        /* En-tête sobre */
        .jr-sheet-header {
            padding: 22px 28px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            position: relative;
            background: var(--bg-page);
        }
        .jr-sheet-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--brand-green), var(--brand-green-light), var(--brand-orange));
        }
        .jr-sheet-id {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .jr-sheet-title {
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin: 0;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .jr-sheet-title img {
            width: 22px;
            height: 22px;
            border-radius: 6px;
        }

        /* structure en grille */
        .jr-sheet-body {
            padding: 28px;
            display: grid;
            grid-template-columns: 8fr 4fr;
            gap: 32px;
        }
        @media (max-width: 992px) {
            .jr-sheet-body {
                grid-template-columns: 1fr;
                gap: 24px;
                padding: 20px;
            }
        }

        /* ─── SEGMENTS ─── */
        .jr-section {
            margin-bottom: 28px;
        }
        .jr-section:last-child {
            margin-bottom: 0;
        }

        .jr-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--brand-green);
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .jr-section-title i {
            font-size: 13px;
        }

        /* Grilles de données */
        .jr-data-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 18px;
        }
        .jr-data-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .jr-data-label {
            font-size: 10.5px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .jr-data-value {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-primary);
        }
        .jr-data-value.mono {
            font-family: 'DM Mono', Consolas, monospace;
            font-weight: 700;
            color: var(--brand-green);
            background: var(--brand-green-xlight);
            display: inline-block;
            padding: 2px 8px;
            border-radius: 6px;
            width: fit-content;
        }

        /* Zone Rapport & Panne */
        .jr-text-block {
            background: var(--bg-page);
            border-left: 3px solid var(--brand-orange);
            padding: 14px 16px;
            border-radius: 0 10px 10px 0;
            font-size: 13px;
            line-height: 1.65;
            color: var(--text-secondary);
        }
        .jr-text-block.report {
            border-left-color: var(--brand-green);
        }

        /* ─── TABLEAU DES PIÈCES ─── */
        .jr-table-wrapper {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
        }
        .jr-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
        }
        .jr-table th {
            background: var(--bg-page);
            padding: 10px 16px;
            font-weight: 700;
            text-align: left;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-color);
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.06em;
        }
        .jr-table td {
            padding: 11px 16px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
        }
        .jr-table tbody tr:hover {
            background: var(--bg-hover);
        }
        .jr-table tr:last-child td {
            border-bottom: none;
        }
        .jr-table .num {
            text-align: right;
        }

        /* ✅ TOTAL — Couleur adaptée au thème */
        .jr-table .total-row {
            font-weight: 700;
            background: var(--brand-green-xlight);
        }
        .jr-table .total-row:hover {
            background: var(--brand-green-xlight);
        }
        .jr-table .total-row td {
            color: var(--text-primary);
        }
        .jr-table .total-row .total-amount {
            color: var(--brand-green);
            font-weight: 800;
        }
        /* ✅ Mode sombre — total adapté */
        [data-theme="dark"] .jr-table .total-row .total-amount {
            color: #4ade80;
        }
        [data-theme="dark"] .jr-table .total-row {
            background: rgba(34, 197, 94, 0.08);
        }

        /* ─── COLONNE DROITE ─── */
        .jr-sidebar {
            display: flex;
            flex-direction: column;
            gap: 22px;
            border-left: 1px solid var(--border-color);
            padding-left: 32px;
        }
        @media (max-width: 992px) {
            .jr-sidebar {
                border-left: none;
                padding-left: 0;
                padding-top: 22px;
                border-top: 1px solid var(--border-color);
            }
        }

        /* Sélecteurs */
        .jr-select-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .jr-select {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background: var(--bg-card);
            color: var(--text-primary);
            font-size: 13px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.18s;
        }
        .jr-select:focus {
            border-color: var(--brand-green);
        }

        /* Boutons */
        .jr-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 9px 16px;
            border-radius: 10px;
            font-size: 12.5px;
            font-weight: 600;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.18s ease;
            text-decoration: none;
            text-align: center;
            font-family: inherit;
        }
        .jr-btn-primary {
            background: linear-gradient(135deg, var(--brand-green) 0%, var(--brand-green-light) 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(26, 122, 60, 0.28);
        }
        .jr-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(26, 122, 60, 0.38);
        }
        .jr-btn-success {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }
        .jr-btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(16, 185, 129, 0.35);
        }
        .jr-btn-secondary {
            background: transparent;
            border-color: var(--border-color);
            color: var(--text-secondary);
        }
        .jr-btn-secondary:hover {
            background: var(--bg-hover);
            border-color: var(--brand-green);
            color: var(--brand-green);
        }
        .jr-btn-full {
            width: 100%;
        }

        /* Badges de Statut */
        .jr-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 13px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }
        .jr-status-badge .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }
        .st-pending {
            background: #fef3c7;
            color: #d97706;
        }
        .st-pending .dot {
            background: #d97706;
        }
        .st-assigned {
            background: #dbeafe;
            color: #2563eb;
        }
        .st-assigned .dot {
            background: #2563eb;
        }
        .st-diagnosing {
            background: #fff4e6;
            color: #f07d00;
        }
        .st-diagnosing .dot {
            background: #f07d00;
        }
        .st-repairing {
            background: #e0e7ff;
            color: #4338ca;
        }
        .st-repairing .dot {
            background: #4338ca;
        }
        .st-completed {
            background: #d1fae5;
            color: #065f46;
        }
        .st-completed .dot {
            background: #065f46;
        }
        .st-restituted {
            background: #f3f4f6;
            color: #6b7280;
        }
        .st-restituted .dot {
            background: #6b7280;
        }

        [data-theme="dark"] .st-pending {
            background: rgba(217, 119, 6, 0.18);
            color: #fcd34d;
        }
        [data-theme="dark"] .st-assigned {
            background: rgba(29, 78, 216, 0.22);
            color: #93c5fd;
        }
        [data-theme="dark"] .st-diagnosing {
            background: rgba(240, 125, 0, 0.18);
            color: #ffb066;
        }
        [data-theme="dark"] .st-repairing {
            background: rgba(67, 56, 202, 0.22);
            color: #b3b0f5;
        }
        [data-theme="dark"] .st-completed {
            background: rgba(6, 95, 70, 0.22);
            color: #6ee7b7;
        }
        [data-theme="dark"] .st-restituted {
            background: rgba(107, 114, 128, 0.22);
            color: #d1d5db;
        }

        .w-badge {
            font-weight: 700;
            font-size: 11.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 13px;
            border-radius: 20px;
        }
        .w-true {
            color: #065f46;
            background: #d1fae5;
        }
        .w-false {
            color: #dc2626;
            background: #fee2e2;
        }
        [data-theme="dark"] .w-true {
            background: rgba(6, 95, 70, 0.22);
            color: #6ee7b7;
        }
        [data-theme="dark"] .w-false {
            background: rgba(220, 38, 38, 0.18);
            color: #fca5a5;
        }

        /* Messages Flash */
        .jr-alert {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid;
            background: rgba(16, 185, 129, 0.08);
            border-color: rgba(16, 185, 129, 0.2);
            color: #10b981;
            font-weight: 500;
        }

        /* Métrique compacte */
        .jr-metric-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            border-radius: 10px;
            background: var(--bg-page);
        }
        .jr-metric-row .jr-data-label {
            margin-bottom: 0;
        }
        .jr-metric-row .jr-data-value {
            font-size: 12.5px;
        }

        .jr-empty-note {
            font-size: 12.5px;
            color: var(--text-muted);
            margin: 0;
            font-style: italic;
            padding: 14px 16px;
            background: var(--bg-page);
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>

    {{-- Messages Flash --}}
    @if(session()->has('success'))
        <div class="jr-alert"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif

    {{-- Barre supérieure --}}
    <div class="jr-top-bar">
        <h2>
            <span class="ticket-icon"><i class="bi bi-ticket-perforated-fill"></i></span>
            Fiche d'intervention <span style="color: var(--brand-green);">#{{ $ticket->ticket_number }}</span>
        </h2>
        <a href="{{ route('technician.tickets.index') }}" class="jr-btn jr-btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour à la liste
        </a>
    </div>

    {{-- FICHE UNIQUE --}}
    <div class="jr-intervention-sheet">

        {{-- En-tête --}}
        <div class="jr-sheet-header">
            <div class="jr-sheet-id">
                <span class="jr-sheet-title">
                    <img src="{{ asset('images/logo-jr.jpg') }}" alt="JR Computer">
                    JR Computer Atelier
                </span>
                @php
                    $statusClasses = [
                        'pending' => 'st-pending', 'assigned' => 'st-assigned',
                        'diagnosing' => 'st-diagnosing', 'repairing' => 'st-repairing',
                        'completed' => 'st-completed', 'restituted' => 'st-restituted'
                    ];
                    $statusLabels = [
                        'pending' => 'En attente', 'assigned' => 'Assigné',
                        'diagnosing' => 'Diagnostic', 'repairing' => 'En réparation',
                        'completed' => 'Terminé', 'restituted' => 'Restitué'
                    ];
                    $currentStatusClass = $statusClasses[$ticket->status] ?? 'st-pending';
                    $currentStatusLabel = $statusLabels[$ticket->status] ?? $ticket->status;
                @endphp
                <span class="jr-status-badge {{ $currentStatusClass }}">
                    <span class="dot"></span> {{ $currentStatusLabel }}
                </span>
            </div>

            <div class="w-badge {{ $ticket->is_warranty ? 'w-true' : 'w-false' }}">
                <i class="bi {{ $ticket->is_warranty ? 'bi-shield-check' : 'bi-shield-x' }}"></i>
                {{ $ticket->is_warranty ? 'Sous garantie' : 'Hors garantie' }}
            </div>
        </div>

        {{-- Corps --}}
        <div class="jr-sheet-body">

            {{-- GAUCHE : DONNÉES --}}
            <div>
                {{-- Identification --}}
                <div class="jr-section">
                    <div class="jr-section-title"><i class="bi bi-laptop"></i> Identification de l'appareil</div>
                    <div class="jr-data-grid">
                        <div class="jr-data-item">
                            <span class="jr-data-label">Modèle / Type</span>
                            <span class="jr-data-value">{{ $ticket->device_model ?? $ticket->product->name ?? '—' }}</span>
                        </div>
                        <div class="jr-data-item">
                            <span class="jr-data-label">Numéro de série</span>
                            <span class="jr-data-value mono">{{ $ticket->serial_number ?? '—' }}</span>
                        </div>
                        <div class="jr-data-item">
                            <span class="jr-data-label">Date d'ouverture</span>
                            <span class="jr-data-value">{{ $ticket->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Client --}}
                <div class="jr-section">
                    <div class="jr-section-title"><i class="bi bi-person"></i> Informations client</div>
                    <div class="jr-data-grid">
                        <div class="jr-data-item">
                            <span class="jr-data-label">Nom du client</span>
                            <span class="jr-data-value">{{ $ticket->customer->name ?? '—' }}</span>
                        </div>
                        <div class="jr-data-item">
                            <span class="jr-data-label">Contact téléphone</span>
                            <span class="jr-data-value">{{ $ticket->customer->phone ?? '—' }}</span>
                        </div>
                        <div class="jr-data-item">
                            <span class="jr-data-label">Technicien en charge</span>
                            <span class="jr-data-value">{{ $ticket->technician->name ?? 'Non assigné' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Panne --}}
                <div class="jr-section">
                    <div class="jr-section-title"><i class="bi bi-exclamation-circle"></i> Diagnostic initial</div>
                    <div class="jr-text-block">
                        {{ $ticket->description_failure ?? 'Aucune description fournie.' }}
                    </div>
                </div>

                {{-- Pièces --}}
                <div class="jr-section">
                    <div class="jr-section-title"><i class="bi bi-cpu"></i> Composants & pièces</div>
                    @if($ticket->items && $ticket->items->count() > 0)
                        <div class="jr-table-wrapper">
                            <table class="jr-table">
                                <thead>
                                    <tr>
                                        <th>Désignation</th>
                                        <th class="num" style="width: 80px;">Qté</th>
                                        <th class="num" style="width: 120px;">Prix Unit.</th>
                                        <th class="num" style="width: 140px;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $partsTotal = 0; @endphp
                                    @foreach($ticket->items as $item)
                                        @php $partsTotal += $item->quantity * $item->unit_price; @endphp
                                        <tr>
                                            <td>{{ $item->sparePart->name ?? '—' }}</td>
                                            <td class="num">{{ $item->quantity }}</td>
                                            <td class="num">{{ number_format($item->unit_price, 0, ',', ' ') }}</td>
                                            <td class="num">{{ number_format($item->quantity * $item->unit_price, 0, ',', ' ') }}</td>
                                        </tr>
                                    @endforeach
                                    {{-- ✅ TOTAL avec couleur adaptée au thème --}}
                                    <tr class="total-row">
                                        <td colspan="3" class="num"><strong>TOTAL PIÈCES</strong></td>
                                        <td class="num"><strong class="total-amount">{{ number_format($partsTotal, 0, ',', ' ') }} FCFA</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="jr-empty-note"><i class="bi bi-inbox"></i> Aucun composant imputé.</p>
                    @endif
                </div>

                {{-- Rapport technique --}}
                @if($ticket->technical_report)
                    <div class="jr-section">
                        <div class="jr-section-title"><i class="bi bi-file-earmark-text"></i> Rapport d'intervention</div>
                        <div class="jr-text-block report">
                            {{ $ticket->technical_report }}
                        </div>
                    </div>
                @endif
            </div>

            {{-- DROITE : ACTIONS --}}
            <div class="jr-sidebar">

                {{-- Actions --}}
                <div style="width: 100%;">
                    <div class="jr-section-title"><i class="bi bi-lightning"></i> Actions</div>

                    <form action="{{ route('technician.tickets.update-status', $ticket->id) }}" method="POST" style="margin-bottom: 18px;">
                        @csrf
                        @method('PATCH')
                        <div class="jr-select-group">
                            <label class="jr-data-label">Changer le statut</label>
                            <div style="display: flex; gap: 8px;">
                                <select name="status" class="jr-select">
                                    <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="assigned" {{ $ticket->status == 'assigned' ? 'selected' : '' }}>Assigné</option>
                                    <option value="diagnosing" {{ $ticket->status == 'diagnosing' ? 'selected' : '' }}>Diagnostic</option>
                                    <option value="repairing" {{ $ticket->status == 'repairing' ? 'selected' : '' }}>En réparation</option>
                                </select>
                                <button type="submit" class="jr-btn jr-btn-primary" title="Mettre à jour">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    @if(in_array($ticket->status, ['repairing', 'diagnosing']))
                        <a href="{{ route('technician.tickets.close-form', $ticket->id) }}" class="jr-btn jr-btn-success jr-btn-full">
                            <i class="bi bi-check2-circle"></i> Clôturer
                        </a>
                    @endif
                </div>

                {{-- Métriques --}}
                <div style="width: 100%;">
                    <div class="jr-section-title"><i class="bi bi-info-square"></i> Métriques</div>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @if($ticket->warranty_end_date && $ticket->is_warranty)
                            <div class="jr-metric-row">
                                <span class="jr-data-label">Échéance garantie</span>
                                <span class="jr-data-value">{{ \Carbon\Carbon::parse($ticket->warranty_end_date)->format('d/m/Y') }}</span>
                            </div>
                        @endif

                        @if($ticket->duration_minutes)
                            <div class="jr-metric-row">
                                <span class="jr-data-label">Temps passé</span>
                                <span class="jr-data-value"><i class="bi bi-clock"></i> {{ $ticket->duration_minutes }} min</span>
                            </div>
                        @endif

                        @if($ticket->diagnostic_fee)
                            <div class="jr-metric-row">
                                <span class="jr-data-label">Frais diagnostic</span>
                                <span class="jr-data-value">{{ number_format($ticket->diagnostic_fee, 0, ',', ' ') }} FCFA</span>
                            </div>
                        @endif

                        @if($ticket->closed_at)
                            <div class="jr-metric-row">
                                <span class="jr-data-label">Date clôture</span>
                                <span class="jr-data-value">{{ \Carbon\Carbon::parse($ticket->closed_at)->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif

                        @if(!$ticket->warranty_end_date && !$ticket->duration_minutes && !$ticket->diagnostic_fee && !$ticket->closed_at)
                            <p class="jr-empty-note"><i class="bi bi-info-circle"></i> Aucune métrique disponible.</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection