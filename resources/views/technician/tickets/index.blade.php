@extends('layouts.app')

@section('content')
<div style="zoom:0.90;">
<style>
    .tech-page-header {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px; margin-bottom: 20px;
    }
    .tech-page-header h2 {
        font-family: 'Syne', sans-serif; font-size: 20px; font-weight: 800;
        color: var(--text-primary); margin: 0;
        display: flex; align-items: center; gap: 10px;
    }
    .tech-page-header h2 i { color: var(--brand-orange); }

    .tech-stats-mini {
        display: flex; gap: 12px; flex-wrap: wrap;
    }
    .tech-stat-mini {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 8px 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: var(--text-secondary);
    }
    .tech-stat-mini strong {
        font-size: 14px;
        color: var(--text-primary);
    }
    .tech-stat-mini .count { font-weight: 700; color: var(--brand-green); }

    .tech-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
    }

    .tech-table-wrap { overflow-x: auto; }
    .tech-table { width: 100%; border-collapse: collapse; }
    .tech-table thead { background: var(--bg-page); border-bottom: 1px solid var(--border-color); }
    .tech-table th {
        padding: 10px 16px; font-size: 10px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.08em;
        color: var(--text-muted); text-align: left;
    }
    .tech-table td {
        padding: 12px 16px; font-size: 12px;
        color: var(--text-primary); border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }
    .tech-table tbody tr { transition: background 0.15s ease; }
    .tech-table tbody tr:hover { background: var(--bg-hover); }
    .tech-table tbody tr:last-child td { border-bottom: none; }

    /* ✅ BADGES STATUT EN FRANÇAIS */
    .tech-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 20px;
        font-size: 11px; font-weight: 600;
    }
    .tech-badge-en-attente { background: #fef3c7; color: #d97706; }
    .tech-badge-assigne { background: #dbeafe; color: #2563eb; }
    .tech-badge-diagnostic { background: #fff4e6; color: #f07d00; }
    .tech-badge-reparation { background: #e0e7ff; color: #4338ca; }
    .tech-badge-termine { background: #d1fae5; color: #065f46; }
    .tech-badge-restitué { background: #f3f4f6; color: #6b7280; }

    /* ✅ BADGES PRIORITÉ EN FRANÇAIS */
    .tech-badge-basse { background: #f3f4f6; color: #6b7280; }
    .tech-badge-moyenne { background: #dbeafe; color: #2563eb; }
    .tech-badge-haute { background: #fef3c7; color: #d97706; }
    .tech-badge-critique { background: #fee2e2; color: #dc2626; }

    /* Mode sombre */
    [data-theme="dark"] .tech-badge-en-attente { background: rgba(217,119,6,0.2); color: #fcd34d; }
    [data-theme="dark"] .tech-badge-assigne { background: rgba(37,99,235,0.2); color: #93c5fd; }
    [data-theme="dark"] .tech-badge-diagnostic { background: rgba(240,125,0,0.2); color: #fcd34d; }
    [data-theme="dark"] .tech-badge-reparation { background: rgba(67,56,202,0.2); color: #a5b4fc; }
    [data-theme="dark"] .tech-badge-termine { background: rgba(6,95,70,0.2); color: #6ee7b7; }
    [data-theme="dark"] .tech-badge-restitue { background: rgba(107,114,128,0.2); color: #9ca3af; }

    .tech-ref {
        display: inline-block;
        font-family: 'DM Mono', monospace;
        font-size: 11px; font-weight: 700;
        color: var(--brand-green);
        background: var(--brand-green-xlight);
        padding: 2px 10px;
        border-radius: 6px;
        letter-spacing: 0.3px;
    }

    .tech-actions { display: flex; gap: 6px; flex-wrap: wrap; }
    .tech-btn-icon {
        width: 32px; height: 32px; border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid var(--border-color);
        background: transparent;
        color: var(--text-secondary);
        cursor: pointer; transition: all 0.2s ease;
        text-decoration: none;
    }
    .tech-btn-icon:hover {
        border-color: var(--brand-green);
        color: var(--brand-green);
        background: var(--brand-green-xlight);
        transform: translateY(-1px);
    }
    .tech-btn-icon.success:hover {
        background: #10b981;
        color: white;
        border-color: #10b981;
    }

    .tech-empty {
        text-align: center; padding: 40px 20px;
        color: var(--text-muted);
    }
    .tech-empty-icon { font-size: 40px; display: block; margin-bottom: 12px; opacity: 0.3; }
    .tech-empty-title { font-size: 14px; font-weight: 600; color: var(--text-secondary); }
    .tech-empty-sub { font-size: 12px; }

    .pagination-wrap { padding: 12px 16px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; }
    .pagination .page-item .page-link {
        border: 1px solid var(--border-color); background: var(--bg-card);
        color: var(--text-secondary); border-radius: 7px !important;
        margin: 0 2px; font-size: 12.5px;
    }
    .pagination .page-item.active .page-link {
        background: var(--brand-green); border-color: var(--brand-green); color: white;
    }
</style>

<div class="tech-page-header">
    <h2><i class="bi bi-ticket-perforated"></i> Mes tickets SAV</h2>
    <div class="tech-stats-mini">
        <div class="tech-stat-mini">
            <i class="bi bi-clock-history" style="color: var(--brand-orange);"></i>
            <span>En attente <strong class="count">{{ $stats['pending_count'] ?? $tickets->whereIn('status', ['pending','assigned','diagnosing'])->count() }}</strong></span>
        </div>
        <div class="tech-stat-mini">
            <i class="bi bi-tools" style="color: var(--brand-orange);"></i>
            <span>En réparation <strong class="count">{{ $stats['in_progress_count'] ?? $tickets->where('status','repairing')->count() }}</strong></span>
        </div>
        <div class="tech-stat-mini">
            <i class="bi bi-check-circle" style="color: var(--brand-green);"></i>
            <span>Terminés <strong class="count">{{ $stats['completed_count'] ?? $tickets->where('status','completed')->count() }}</strong></span>
        </div>
    </div>
</div>

<div class="tech-card">
    <div class="tech-table-wrap">
        <table class="tech-table">
            <thead>
                <tr>
                    <th>#Ticket</th>
                    <th>Client</th>
                    <th>Appareil</th>
                    <th>Statut</th>
                    <th>Priorité</th>
                    <th>Créé le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr>
                    <td><span class="tech-ref">{{ $ticket->ticket_number }}</span></td>
                    <td>{{ $ticket->customer->name ?? '—' }}</td>
                    <td>{{ $ticket->device_model ?? $ticket->product->name ?? '—' }}</td>
                    <td>
                        @php
                            $statusMap = [
                                'pending' => 'En attente',
                                'assigned' => 'Assigné',
                                'diagnosing' => 'Diagnostic',
                                'repairing' => 'En réparation',
                                'completed' => 'Terminé',
                                'restituted' => 'Restitué'
                            ];
                            $statusClassMap = [
                                'pending' => 'tech-badge-en-attente',
                                'assigned' => 'tech-badge-assigne',
                                'diagnosing' => 'tech-badge-diagnostic',
                                'repairing' => 'tech-badge-reparation',
                                'completed' => 'tech-badge-termine',
                                'restituted' => 'tech-badge-restitue'
                            ];
                            $statusLabel = $statusMap[$ticket->status] ?? $ticket->status;
                            $statusClass = $statusClassMap[$ticket->status] ?? 'tech-badge-en-attente';
                        @endphp
                        <span class="tech-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td>
                        @php
                            $priorityMap = [
                                'low' => 'Basse',
                                'medium' => 'Moyenne',
                                'high' => 'Haute',
                                'critical' => 'Critique'
                            ];
                            $priorityClassMap = [
                                'low' => 'tech-badge-basse',
                                'medium' => 'tech-badge-moyenne',
                                'high' => 'tech-badge-haute',
                                'critical' => 'tech-badge-critique'
                            ];
                            $priorityLabel = $priorityMap[$ticket->priority] ?? $ticket->priority;
                            $priorityClass = $priorityClassMap[$ticket->priority] ?? 'tech-badge-basse';
                        @endphp
                        <span class="tech-badge {{ $priorityClass }}">{{ $priorityLabel }}</span>
                    </td>
                    <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div class="tech-actions">
                            <a href="{{ route('technician.tickets.show', $ticket->id) }}" 
                               class="tech-btn-icon" title="Voir détails">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if(in_array($ticket->status, ['repairing', 'diagnosing']))
                                <a href="{{ route('technician.tickets.close-form', $ticket->id) }}" 
                                   class="tech-btn-icon success" title="Clôturer">
                                    <i class="bi bi-check2-circle"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="tech-empty">
                            <span class="tech-empty-icon"><i class="bi bi-inbox"></i></span>
                            <div class="tech-empty-title">Aucun ticket assigné</div>
                            <div class="tech-empty-sub">Les tickets vous seront assignés par l'administrateur</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tickets->hasPages())
    <div class="pagination-wrap">{{ $tickets->links() }}</div>
    @endif
</div>
</div>
@endsection