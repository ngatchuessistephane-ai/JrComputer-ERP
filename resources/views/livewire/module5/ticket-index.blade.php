<div style="zoom:0.90;">
    <style>
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
        }
        .module-icon.green {
            background: linear-gradient(135deg, var(--brand-green), #22a352);
            box-shadow: 0 4px 10px rgba(26,122,60,0.28);
        }
        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 7px;
            flex-wrap: wrap;
        }
        .flash-msg {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 16px;
            border-radius: 11px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 16px;
            border: 1px solid;
        }
        .flash-msg.success {
            background: rgba(26,122,60,0.08);
            border-color: rgba(26,122,60,0.2);
            color: var(--brand-green);
        }
        .search-wrap {
            position: relative;
            margin-bottom: 16px;
        }
        .search-wrap .s-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 15px;
            color: var(--text-muted);
            pointer-events: none;
        }
        .search-input {
            width: 100%;
            padding: 9px 13px 9px 38px;
            border: 1px solid var(--border-color);
            border-radius: 11px;
            background: var(--bg-card);
            color: var(--text-primary);
            font-size: 13px;
            outline: none;
            transition: 0.18s;
        }
        .search-input:focus {
            border-color: var(--brand-green);
            box-shadow: 0 0 0 3px rgba(26,122,60,0.1);
        }
        .filters-row {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }
        .filter-select {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 9px;
            background: var(--bg-card);
            color: var(--text-primary);
            font-size: 12px;
            outline: none;
        }
        .data-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            overflow: hidden;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th {
            background: var(--bg-page);
            padding: 11px 16px;
            font-size: 11px;
            text-transform: uppercase;
            color: var(--text-muted);
            text-align: left;
        }
        .data-table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            font-size: 12px;
            color: var(--text-primary);
        }

        /* ============================================================
           BADGES STATUT — UNIFORMISÉS
           ============================================================ */
        .badge-status {
            display: inline-block;
            padding: 3px 12px;
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

        /* Mode sombre */
        [data-theme="dark"] .badge-status.pending    { background: rgba(251,191,36,0.18); color: #fcd34d; }
        [data-theme="dark"] .badge-status.assigned   { background: rgba(59,130,246,0.18); color: #93c5fd; }
        [data-theme="dark"] .badge-status.diagnosing { background: rgba(251,146,60,0.18); color: #fdba74; }
        [data-theme="dark"] .badge-status.repairing  { background: rgba(99,102,241,0.18); color: #a5b4fc; }
        [data-theme="dark"] .badge-status.completed  { background: rgba(16,185,129,0.18); color: #6ee7b7; }
        [data-theme="dark"] .badge-status.restituted { background: rgba(107,114,128,0.18); color: #9ca3af; }

        /* ============================================================
           BADGES PRIORITÉ — UNIFORMISÉS
           ============================================================ */
        .badge-priority {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: white;
        }

        .badge-priority.low      { background: #6b7280; }
        .badge-priority.medium   { background: #3b82f6; }
        .badge-priority.high     { background: #f59e0b; }
        .badge-priority.critical { background: #dc2626; animation: pulse-urgent 1.8s ease-in-out infinite; }

        @keyframes pulse-urgent {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.75; transform: scale(1.02); }
        }

        /* Mode sombre - priorités */
        [data-theme="dark"] .badge-priority.low      { background: #4b5563; }
        [data-theme="dark"] .badge-priority.medium   { background: #2563eb; }
        [data-theme="dark"] .badge-priority.high     { background: #d97706; }
        [data-theme="dark"] .badge-priority.critical { background: #dc2626; }

        /* ============================================================
           ACTIONS
           ============================================================ */
        .act-btn {
            width: 30px;
            height: 30px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border-color);
            background: transparent;
            cursor: pointer;
            transition: 0.15s;
            color: var(--text-secondary);
        }
        .act-btn.view:hover {
            background: rgba(59,130,246,0.10);
            color: #3b82f6;
        }
        .act-btn.del:hover {
            background: rgba(220,38,38,0.08);
            color: #dc2626;
        }
        .empty-state-row td {
            text-align: center;
            padding: 44px 20px;
            color: var(--text-muted);
            font-size: 12px;
        }
        .pagination-wrap {
            padding: 12px 16px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
        }
        .pagination .page-item .page-link {
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            color: var(--text-secondary);
            border-radius: 7px !important;
            margin: 0 2px;
            font-size: 12.5px;
        }
        .pagination .page-item.active .page-link {
            background: var(--brand-green);
            border-color: var(--brand-green);
            color: white;
        }
        .fw-semibold {
            font-weight: 600;
        }
    </style>

    @if(session()->has('message'))
        <div class="flash-msg success">{{ session('message') }}</div>
    @endif

    <div class="module-toolbar">
        <h2><span class="module-icon green"><i class="bi bi-tools"></i></span> SAV - Tickets d'intervention</h2>
        <div class="toolbar-actions">
            @can('create sav tickets')
            <a href="{{ route('module5.tickets.create') }}" class="btn-brand"><i class="bi bi-plus-lg"></i> Nouveau ticket</a>
            @endcan
        </div>
    </div>

    <div class="search-wrap">
        <i class="bi bi-search s-icon"></i>
        <input type="text" wire:model.live.debounce.300ms="search" class="search-input" placeholder="Rechercher par n° ticket ou client...">
    </div>

    <div class="filters-row">
        <select wire:model.live="statusFilter" class="filter-select">
            <option value="">Tous statuts</option>
            <option value="pending">En attente</option>
            <option value="assigned">Assigné</option>
            <option value="diagnosing">Diagnostic</option>
            <option value="repairing">En réparation</option>
            <option value="completed">Terminé</option>
            <option value="restituted">Restitué</option>
        </select>
        <select wire:model.live="priorityFilter" class="filter-select">
            <option value="">Toutes priorités</option>
            <option value="low">Basse</option>
            <option value="medium">Moyenne</option>
            <option value="high">Haute</option>
            <option value="critical">Critique</option>
        </select>
    </div>

    <div class="data-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>N° Ticket</th>
                        <th>Client</th>
                        <th>Appareil</th>
                        <th>Statut</th>
                        <th>Priorité</th>
                        <th>Technicien</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td><span class="fw-semibold">{{ $ticket->ticket_number }}</span></td>
                        <td>{{ $ticket->customer->name ?? '—' }}</td>
                        <td>{{ $ticket->product->name ?? $ticket->device_model ?? '—' }}</td>
                        <td>
                            @php
                                $statusLabels = [
                                    'pending'=>'En attente',
                                    'assigned'=>'Assigné',
                                    'diagnosing'=>'Diagnostic',
                                    'repairing'=>'En réparation',
                                    'completed'=>'Terminé',
                                    'restituted'=>'Restitué'
                                ];
                                $statusClasses = [
                                    'pending'=>'pending',
                                    'assigned'=>'assigned',
                                    'diagnosing'=>'diagnosing',
                                    'repairing'=>'repairing',
                                    'completed'=>'completed',
                                    'restituted'=>'restituted'
                                ];
                            @endphp
                            <span class="badge-status {{ $statusClasses[$ticket->status] ?? '' }}">
                                {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                            </span>
                        </td>
                        <td>
                            @php
                                $priorityClasses = [
                                    'low'=>'low',
                                    'medium'=>'medium',
                                    'high'=>'high',
                                    'critical'=>'critical'
                                ];
                                $priorityNames = [
                                    'low'=>'Basse',
                                    'medium'=>'Moyenne',
                                    'high'=>'Haute',
                                    'critical'=>'Critique'
                                ];
                            @endphp
                            <span class="badge-priority {{ $priorityClasses[$ticket->priority] ?? 'low' }}">
                                {{ $priorityNames[$ticket->priority] ?? $ticket->priority }}
                            </span>
                        </td>
                        <td>{{ $ticket->technician->name ?? '—' }}</td>
                        <td>
                            <a href="{{ route('module5.tickets.show', $ticket->id) }}" class="act-btn view" title="Voir"><i class="bi bi-eye"></i></a>
                            @can('delete sav tickets')
                                <button wire:click="delete({{ $ticket->id }})" wire:confirm="Êtes-vous sûr de vouloir supprimer ce ticket SAV ?" class="act-btn del"><i class="bi bi-trash3"></i></button>
                            @endcan
                        </td>
                    </tr>
                    @empty
                        <tr class="empty-state-row"><td colspan="7">Aucun ticket SAV trouvé. Créez votre premier ticket.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tickets->hasPages())
            <div class="pagination-wrap">{{ $tickets->links() }}</div>
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