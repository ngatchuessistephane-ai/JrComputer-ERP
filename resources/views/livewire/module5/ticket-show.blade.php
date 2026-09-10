<div class="ticket-container">
    <style>
        .ticket-container {
            zoom: 0.81;
        }
        .module-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
        }
        .module-toolbar h2 {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .module-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }
        .module-icon.green {
            background: linear-gradient(135deg, var(--brand-green), #22a352);
            box-shadow: 0 4px 10px rgba(26,122,60,0.28);
        }
        .toolbar-actions {
            display: flex;
            gap: 10px;
        }
        .btn-ghost, .btn-accent {
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
            cursor: pointer;
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
            box-shadow: 0 4px 10px rgba(26,122,60,0.2);
        }
        .btn-accent:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(26,122,60,0.3);
            color: white;
        }
        .card-jr {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        
        /* Grille d'informations */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        .info-item {
            background: var(--bg-page);
            padding: 14px 18px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }
        .info-item label {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            margin-bottom: 6px;
            font-weight: 600;
        }
        .info-item span, .info-item p {
            font-size: 14px;
            color: var(--text-primary);
            margin: 0;
            font-weight: 500;
        }
        .info-item.full-width {
            grid-column: 1 / -1;
        }

        /* ============================================================
           BADGES STATUT — UNIFORMISÉS AVEC ticket-index
           ============================================================ */
        .badge-custom {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        /* Statuts SAV */
        .badge-status.pending    { background: #fef3c7; color: #92400e; }
        .badge-status.assigned   { background: #dbeafe; color: #1e40af; }
        .badge-status.diagnosing { background: #fff4e6; color: #b45309; }
        .badge-status.repairing  { background: #e0e7ff; color: #3730a3; }
        .badge-status.completed  { background: #d1fae5; color: #065f46; }
        .badge-status.restituted { background: #f3f4f6; color: #4b5563; }

        /* Mode sombre - statuts */
        [data-theme="dark"] .badge-status.pending    { background: rgba(251,191,36,0.18); color: #fcd34d; }
        [data-theme="dark"] .badge-status.assigned   { background: rgba(59,130,246,0.18); color: #93c5fd; }
        [data-theme="dark"] .badge-status.diagnosing { background: rgba(251,146,60,0.18); color: #fdba74; }
        [data-theme="dark"] .badge-status.repairing  { background: rgba(99,102,241,0.18); color: #a5b4fc; }
        [data-theme="dark"] .badge-status.completed  { background: rgba(16,185,129,0.18); color: #6ee7b7; }
        [data-theme="dark"] .badge-status.restituted { background: rgba(107,114,128,0.18); color: #9ca3af; }

        /* Priorités */
        .badge-priority.low      { background: #6b7280; color: white; }
        .badge-priority.medium   { background: #3b82f6; color: white; }
        .badge-priority.high     { background: #f59e0b; color: white; }
        .badge-priority.critical { background: #dc2626; color: white; animation: pulse-urgent 1.8s ease-in-out infinite; }

        /* Mode sombre - priorités */
        [data-theme="dark"] .badge-priority.low      { background: #4b5563; color: white; }
        [data-theme="dark"] .badge-priority.medium   { background: #2563eb; color: white; }
        [data-theme="dark"] .badge-priority.high     { background: #d97706; color: white; }
        [data-theme="dark"] .badge-priority.critical { background: #dc2626; color: white; }

        @keyframes pulse-urgent {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.75; transform: scale(1.02); }
        }

        /* Garantie */
        .badge-guarantee.yes { background: #d1fae5; color: #065f46; }
        .badge-guarantee.no  { background: #fee2e2; color: #dc2626; }

        [data-theme="dark"] .badge-guarantee.yes { background: rgba(16,185,129,0.18); color: #6ee7b7; }
        [data-theme="dark"] .badge-guarantee.no  { background: rgba(220,38,38,0.18); color: #fca5a5; }

        /* ============================================================
           SECTION TITRES
           ============================================================ */
        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 24px 0 14px 0;
            position: relative;
            padding-left: 12px;
        }
        .section-title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 3px;
            bottom: 3px;
            width: 3px;
            background: var(--brand-green);
            border-radius: 2px;
        }

        /* ============================================================
           TABLES
           ============================================================ */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th {
            background: var(--bg-page);
            padding: 12px 16px;
            font-size: 11px;
            text-transform: uppercase;
            color: var(--text-muted);
            font-weight: 700;
            text-align: left;
            border-bottom: 2px solid var(--border-color);
        }
        .data-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
            font-size: 13px;
            color: var(--text-primary);
            vertical-align: middle;
        }
        .data-table tr:hover td {
            background-color: var(--bg-page);
        }
        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
        
        hr {
            margin: 30px 0;
            border: 0;
            border-top: 1px solid var(--border-color);
        }

        /* Code / Serial number */
        code {
            background: var(--bg-page);
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            color: var(--brand-green);
            font-family: 'Courier New', monospace;
        }

        /* Signature image */
        .signature-img {
            max-width: 250px;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        [data-theme="dark"] .signature-img {
            background: #1a1a1a;
        }
    </style>

    <div class="module-toolbar">
        <h2>
            <span class="module-icon green"><i class="bi bi-ticket-perforated"></i></span> 
            Ticket #{{ $ticket->ticket_number }}
        </h2>
        <div class="toolbar-actions">
            <a href="{{ route('module5.tickets.index') }}" class="btn-ghost">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
            @can('edit sav tickets')
                <a href="{{ route('module5.tickets.edit', $ticket->id) }}" class="btn-accent">
                    <i class="bi bi-pencil"></i>Clôturer le Ticket
                </a>
            @endcan
            <a href="{{ route('module5.tickets.pdf', $ticket->id) }}" target="_blank" class="btn-accent">
                <i class="bi bi-file-pdf"></i> Exporter PDF
            </a>
        </div>
    </div>

    <div class="card-jr">
        <div class="info-grid">
            <div class="info-item">
                <label>Client</label>
                <span>{{ $ticket->customer->name }}</span>
            </div>
            <div class="info-item">
                <label>Produit</label>
                <span>{{ $ticket->product->name ?? $ticket->device_model ?? '—' }}</span>
            </div>
            <div class="info-item">
                <label>Numéro de série</label>
                <span><code>{{ $ticket->serial_number ?? '—' }}</code></span>
            </div>
            <div class="info-item">
                <label>Technicien</label>
                <span>{{ $ticket->technician->name ?? 'Non assigné' }}</span>
            </div>
            <div class="info-item">
                <label>Statut</label>
                <div>
                    @php
                        $statusLabels = [
                            'pending'    => 'En attente',
                            'assigned'   => 'Assigné',
                            'diagnosing' => 'Diagnostic',
                            'repairing'  => 'En réparation',
                            'completed'  => 'Terminé',
                            'restituted' => 'Restitué'
                        ];
                        $statusClasses = [
                            'pending'    => 'pending',
                            'assigned'   => 'assigned',
                            'diagnosing' => 'diagnosing',
                            'repairing'  => 'repairing',
                            'completed'  => 'completed',
                            'restituted' => 'restituted'
                        ];
                    @endphp
                    <span class="badge-custom badge-status {{ $statusClasses[$ticket->status] ?? 'pending' }}">
                        {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                    </span>
                </div>
            </div>
            <div class="info-item">
                <label>Priorité</label>
                <div>
                    @php
                        $priorityNames = [
                            'low'      => 'Basse',
                            'medium'   => 'Moyenne',
                            'high'     => 'Haute',
                            'critical' => 'Critique'
                        ];
                        $priorityClasses = [
                            'low'      => 'low',
                            'medium'   => 'medium',
                            'high'     => 'high',
                            'critical' => 'critical'
                        ];
                    @endphp
                    <span class="badge-custom badge-priority {{ $priorityClasses[$ticket->priority] ?? 'low' }}">
                        {{ $priorityNames[$ticket->priority] ?? $ticket->priority }}
                    </span>
                </div>
            </div>
            <div class="info-item full-width">
                <label>Garantie</label>
                <div>
                    @if($ticket->is_warranty)
                        <span class="badge-custom badge-guarantee yes">
                            <i class="bi bi-shield-check"></i> Sous garantie jusqu'au {{ \Carbon\Carbon::parse($ticket->warranty_end_date)->format('d/m/Y') }}
                        </span>
                    @else
                        <span class="badge-custom badge-guarantee no">
                            <i class="bi bi-shield-x"></i> Hors garantie
                        </span>
                    @endif
                </div>
            </div>
            <div class="info-item full-width">
                <label>Description panne</label>
                <p>{{ $ticket->description_failure }}</p>
            </div>
        </div>

        <h5 class="section-title">Espace des Pièces utilisées</h5>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Pièce</th>
                        <th class="text-right" style="width: 80px;">Qté</th>
                        <th class="text-right" style="width: 150px;">Prix unitaire</th>
                        <th class="text-right" style="width: 150px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ticket->items as $item)
                    <tr>
                        <td>{{ $item->sparePart->name }}</td>
                        <td class="text-right"><strong>{{ $item->quantity }}</strong></td>
                        <td class="text-right">{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</td>
                        <td class="text-right" style="font-weight: 600; color: var(--brand-green);">{{ number_format($item->quantity * $item->unit_price, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center" style="color: var(--text-muted); padding: 20px;">Aucune pièce utilisée pour le moment.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ticket->intervention)
            <hr>
            <h5 class="section-title">Rapport d'intervention</h5>
            
            <div class="info-grid">
                <div class="info-item">
                    <label>Durée de l'intervention</label>
                    <span><i class="bi bi-clock"></i> {{ $ticket->intervention->duration_minutes }} minutes</span>
                </div>
                <div class="info-item full-width">
                    <label>Rapport technique</label>
                    <p>{{ $ticket->intervention->technical_report }}</p>
                </div>
                @if($ticket->intervention->client_signature)
                    <div class="info-item full-width">
                        <label>Signature Client</label>
                        <div style="margin-top: 8px;">
                            <img src="{{ Storage::url($ticket->intervention->client_signature) }}" alt="Signature" class="signature-img">
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <script>
    document.addEventListener('livewire:init', function () {
        Livewire.on('scroll-to-top', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
    </script>
</div>