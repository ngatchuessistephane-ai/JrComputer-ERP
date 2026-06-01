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

        /* Badges de Statut Professionnels */
        .badge-custom {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success { background-color: #6ff1a5; color: #1a7a3c; }
        .badge-danger { background-color: #f59292; color: #e02424; }
        .badge-info { background-color: #7abafd; color: #1e429f; }
        .badge-warning { background-color: #f7e55a; color: #854d0e; }
        .badge-dark { background-color: rgba(0,0,0,0.08); color: #1f2937; }
        

        /* Section titres */
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

        /* Tables */
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
        
        hr {
            margin: 30px 0;
            border: 0;
            border-top: 1px solid var(--border-color);
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
                    <i class="bi bi-pencil"></i> Modifier
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
                        // Attribution d'une couleur logique suivant la phase du statut
                        $statusStyles = [
                            'pending'    => 'badge-warning',
                            'assigned'   => 'badge-info',
                            'diagnosing' => 'badge-info',
                            'repairing'  => 'badge-warning',
                            'completed'  => 'badge-success',
                            'restituted' => 'badge-dark'
                        ];
                    @endphp
                    <span class="badge-custom {{ $statusStyles[$ticket->status] ?? 'badge-dark' }}">
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
                        $priorityStyles = [
                            'low'      => 'badge-info',
                            'medium'   => 'badge-warning',
                            'high'     => 'badge-danger',
                            'critical' => 'badge-dark'
                        ];
                    @endphp
                    <span class="badge-custom {{ $priorityStyles[$ticket->priority] ?? 'badge-dark' }}">
                        {{ $priorityNames[$ticket->priority] ?? $ticket->priority }}
                    </span>
                </div>
            </div>
            <div class="info-item full-width">
                <label>Garantie</label>
                <div>
                    @if($ticket->is_warranty)
                        <span class="badge-custom badge-success"><i class="bi bi-shield-check"></i> Sous garantie jusqu'au {{ \Carbon\Carbon::parse($ticket->warranty_end_date)->format('d/m/Y') }}</span>
                    @else
                        <span class="badge-custom badge-danger"><i class="bi bi-shield-x"></i> Hors garantie</span>
                    @endif
                </div>
            </div>
            <div class="info-item full-width">
                <label>Description panne</label>
                <p>{{ $ticket->description_failure }}</p>
            </div>
        </div>

        <h5 class="section-title">Pièces utilisées</h5>
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
                        <td colspan="4" class="text-center" style="color: var(--text-muted); padding: 20px;">Aucune pièce utilisée pour ce ticket.</td>
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
                            <img src="{{ Storage::url($ticket->intervention->client_signature) }}" alt="Signature" style="max-width:250px; background:#fff; border:1px solid var(--border-color); border-radius:12px; padding:10px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>