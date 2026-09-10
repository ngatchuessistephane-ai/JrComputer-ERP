@extends('layouts.app')

@section('content')
<div style="zoom:0.90;">
<style>
    /* ─── STYLES TECHNICIEN SAV ─── */
    .tech-dashboard {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Stats Grid - Cards plus compactes */
    .tech-stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }
    @media (max-width: 768px) {
        .tech-stats-grid { grid-template-columns: 1fr; }
    }

    .tech-stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 14px 18px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .tech-stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--brand-green), var(--brand-green-light));
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .tech-stat-card:hover::before { opacity: 1; }
    .tech-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        border-color: var(--brand-green);
    }

    .tech-stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        background: var(--bg-page);
        color: var(--brand-green);
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }
    .tech-stat-card:hover .tech-stat-icon {
        background: var(--brand-green-xlight);
        transform: scale(1.05);
    }
    .tech-stat-label {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin-bottom: 2px;
    }
    .tech-stat-value {
        font-size: 22px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
    }
    .tech-stat-change {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-size: 10px;
        font-weight: 600;
        margin-top: 4px;
        padding: 1px 8px;
        border-radius: 20px;
    }
    .tech-stat-change.up { background: rgba(16, 185, 129, 0.1); color: #10b981; }

    /* Card */
    .tech-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        overflow: hidden;
    }
    .tech-card-header {
        padding: 12px 16px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .tech-card-header h3 {
        font-size: 13px;
        font-weight: 700;
        margin: 0;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Table */
    .tech-table {
        width: 100%;
        border-collapse: collapse;
    }
    .tech-table thead { background: var(--bg-page); border-bottom: 1px solid var(--border-color); }
    .tech-table th {
        padding: 8px 14px;
        font-size: 9.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        text-align: left;
    }
    .tech-table td {
        padding: 10px 14px;
        font-size: 11.5px;
        color: var(--text-primary);
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }
    .tech-table tbody tr { transition: background 0.15s ease; }
    .tech-table tbody tr:hover { background: var(--bg-hover); }
    .tech-table tbody tr:last-child td { border-bottom: none; }

    /* ✅ BADGES STATUT EN FRANÇAIS */
    .tech-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
    }
    .tech-badge-en-attente { background: #fef3c7; color: #d97706; }
    .tech-badge-assigne { background: #dbeafe; color: #2563eb; }
    .tech-badge-diagnostic { background: #fff4e6; color: #f07d00; }
    .tech-badge-reparation { background: #e0e7ff; color: #4338ca; }
    .tech-badge-termine { background: #d1fae5; color: #065f46; }
    .tech-badge-restitue { background: #f3f4f6; color: #6b7280; }

    /* ✅ BADGES PRIORITÉ EN FRANÇAIS */
    .tech-badge-basse { background: #f3f4f6; color: #6b7280; }
    .tech-badge-moyenne { background: #dbeafe; color: #2563eb; }
    .tech-badge-haute { background: #fef3c7; color: #d97706; }
    .tech-badge-critique { background: #fee2e2; color: #dc2626; }

    [data-theme="dark"] .tech-badge-en-attente { background: rgba(217,119,6,0.2); color: #fcd34d; }
    [data-theme="dark"] .tech-badge-assigne { background: rgba(37,99,235,0.2); color: #93c5fd; }
    [data-theme="dark"] .tech-badge-diagnostic { background: rgba(240,125,0,0.2); color: #fcd34d; }
    [data-theme="dark"] .tech-badge-reparation { background: rgba(67,56,202,0.2); color: #a5b4fc; }
    [data-theme="dark"] .tech-badge-termine { background: rgba(6,95,70,0.2); color: #6ee7b7; }

    .tech-ref {
        display: inline-block;
        font-family: 'DM Mono', monospace;
        font-size: 10px;
        font-weight: 700;
        color: var(--brand-green);
        background: var(--brand-green-xlight);
        padding: 1px 8px;
        border-radius: 5px;
        letter-spacing: 0.3px;
    }

    .tech-actions { display: flex; gap: 4px; flex-wrap: wrap; }
    .tech-btn-icon {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border-color);
        background: transparent;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        font-size: 12px;
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
        text-align: center;
        padding: 30px 20px;
        color: var(--text-muted);
    }
    .tech-empty-icon { font-size: 32px; display: block; margin-bottom: 8px; opacity: 0.3; }
    .tech-empty-title { font-size: 13px; font-weight: 600; color: var(--text-secondary); }
    .tech-empty-sub { font-size: 11px; }

    .tech-flash {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 500;
        margin-bottom: 16px;
        border: 1px solid;
        animation: slideDown 0.3s ease;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .tech-flash.success {
        background: rgba(16, 185, 129, 0.08);
        border-color: rgba(16, 185, 129, 0.2);
        color: #10b981;
    }
    .tech-flash.error {
        background: rgba(239, 68, 68, 0.08);
        border-color: rgba(239, 68, 68, 0.2);
        color: #dc2626;
    }

    .btn-ghost-sm {
        background: transparent;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        padding: 4px 12px;
        font-size: 11px;
        font-weight: 500;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.18s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .btn-ghost-sm:hover {
        border-color: var(--brand-green);
        color: var(--brand-green);
        background: var(--brand-green-xlight);
    }
</style>

<div class="tech-dashboard">
    {{-- Flash messages --}}
    @if(session()->has('success'))
        <div class="tech-flash success"><i class="bi bi-check-circle-fill"></i>{{ session('success') }}</div>
    @endif
    @if(session()->has('error'))
        <div class="tech-flash error"><i class="bi bi-exclamation-circle-fill"></i>{{ session('error') }}</div>
    @endif

    {{-- Stats --}}
    <div class="tech-stats-grid">
        <div class="tech-stat-card">
            <div class="tech-stat-icon"><i class="bi bi-clock-history"></i></div>
            <div class="tech-stat-label">En attente / Diagnostic</div>
            <div class="tech-stat-value">{{ $stats['pending_count'] }}</div>
            <div class="tech-stat-change up"><i class="bi bi-arrow-up-short"></i> À traiter</div>
        </div>
        <div class="tech-stat-card">
            <div class="tech-stat-icon"><i class="bi bi-tools"></i></div>
            <div class="tech-stat-label">En cours de réparation</div>
            <div class="tech-stat-value">{{ $stats['in_progress_count'] }}</div>
            <div class="tech-stat-change up"><i class="bi bi-arrow-up-short"></i> Actifs</div>
        </div>
        <div class="tech-stat-card">
            <div class="tech-stat-icon"><i class="bi bi-check-circle"></i></div>
            <div class="tech-stat-label">Terminés aujourd'hui</div>
            <div class="tech-stat-value">{{ $stats['completed_today_count'] }}</div>
            <div class="tech-stat-change up"><i class="bi bi-arrow-up-short"></i> Clôturés</div>
        </div>
    </div>

    {{-- Tickets récents --}}
    <div class="tech-card">
        <div class="tech-card-header">
            <h3><i class="bi bi-clock-history" style="color: var(--brand-orange);"></i> Tickets récents</h3>
            <a href="{{ route('technician.tickets.index') }}" class="btn-ghost-sm">
                Voir tous <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="table-responsive">
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
                    @forelse($recentTickets as $ticket)
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
    </div>
</div>
@endsection