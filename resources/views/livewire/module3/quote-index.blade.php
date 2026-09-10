<div style="zoom:0.90;">
<style>
    .module-toolbar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; }
    .module-toolbar h2 { font-family: 'Syne', sans-serif; font-size: 19px; font-weight: 800; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
    .module-icon { width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; color: white; font-size: 16px; }
    .module-icon.green  { background: linear-gradient(135deg, var(--brand-green), #22a352); box-shadow: 0 4px 10px rgba(26,122,60,0.28); }
    .toolbar-actions { display: flex; align-items: center; gap: 7px; flex-wrap: wrap; }
    .flash-msg { display: flex; align-items: center; gap: 10px; padding: 11px 16px; border-radius: 11px; font-size: 13px; font-weight: 500; margin-bottom: 16px; border: 1px solid; }
    .flash-msg.success { background: rgba(26,122,60,0.08); border-color: rgba(26,122,60,0.2); color: var(--brand-green); }
    .flash-msg.danger  { background: rgba(220,38,38,0.07); border-color: rgba(220,38,38,0.18); color: #dc2626; }
    .search-wrap { position: relative; margin-bottom: 16px; }
    .search-wrap .s-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); font-size: 15px; color: var(--text-muted); pointer-events: none; }
    .search-input { width: 100%; padding: 9px 13px 9px 38px; border: 1px solid var(--border-color); border-radius: 11px; background: var(--bg-card); color: var(--text-primary); font-size: 13px; outline: none; transition: 0.18s; }
    .data-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden; }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: var(--bg-page); padding: 11px 16px; font-size: 10.5px; text-transform: uppercase; color: var(--text-muted); text-align: left; }
    .data-table td { padding: 12px 16px; border-bottom: 1px solid var(--border-color); font-size: 12px; color: var(--text-primary); }
    .act-btn { width: 30px; height: 30px; border-radius: 7px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--border-color); background: transparent; cursor: pointer; transition: 0.15s; }
    .act-btn.edit:hover { background: var(--brand-orange-xlight); color: var(--brand-orange); }
    .act-btn.del:hover { background: rgba(220,38,38,0.08); color: #dc2626; }
    .act-btn.convert:hover { background: rgba(26,122,60,0.08); color: var(--brand-green); }
    .empty-state-row td { text-align: center; padding: 44px 20px; color: var(--text-muted); }
    .pagination-wrap { padding: 12px 16px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; }
    .badge-status { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
    .badge-status.draft { background: rgba(107,114,128,0.10); color: #6b7280; }
    .badge-status.sent { background: rgba(8,145,178,0.10); color: #0891b2; }
    .badge-status.accepted { background: rgba(22,163,74,0.12); color: #16a34a; }
    .badge-status.rejected { background: rgba(220,38,38,0.10); color: #dc2626; }
    .filters-row { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 16px; }
    .filter-select { padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 9px; background: var(--bg-card); color: var(--text-primary); font-size: 12px; outline: none; }
    .modal-jr .modal-content { background: var(--bg-card); border-radius: 18px; }
    .modal-jr .modal-header  {  border-bottom: none; padding: 18px 22px; }
    .modal-jr .modal-title   { color: #fff; font-size: 15px; font-weight: 700; }
    .modal-jr .btn-close     { filter: brightness(0) invert(1); }
    .fg { margin-bottom: 14px; }
    .fg label { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.4px; display: block; margin-bottom: 5px; }
    .fc { width: 100%; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 9px; background: var(--bg-card); color: var(--text-primary); font-size: 13px; outline: none; }

    .act-btn.view:hover { background: rgba(59,130,246,0.10); color: #3b82f6; }
</style>

@if(session()->has('message'))<div class="flash-msg success"><i class="bi bi-check-circle-fill"></i>{{ session('message') }}</div>@endif
@if(session()->has('error'))<div class="flash-msg danger"><i class="bi bi-exclamation-circle-fill"></i>{{ session('error') }}</div>@endif

<div class="module-toolbar">
    <h2><span class="module-icon green"><i class="bi bi-file-earmark-text"></i></span> Factures Proforma</h2>
    <div class="toolbar-actions">
        @can('create quotes')
        <a href="{{ route('module3.quotes.create') }}" class="btn-brand"><i class="bi bi-plus-lg"></i> Nouvelle Proforma</a>
        @endcan
        <button wire:click="openFiltersModal" class="btn-accent" style="background: linear-gradient(135deg, #dc2626, #ef4444);">
            <i class="bi bi-file-pdf"></i> Exporter PDF
        </button>
    </div>
</div>

<div class="search-wrap">
    <i class="bi bi-search s-icon"></i>
    <input type="text" wire:model.live.debounce.300ms="search" class="search-input" placeholder="Rechercher par numéro, client…">
</div>

<div class="filters-row">
    <select wire:model.live="statusFilter" class="filter-select">
        <option value="">Tous les statuts</option>
        <option value="draft">Brouillon</option>
        <option value="sent">Envoyé</option>
        <option value="accepted">Accepté</option>
        <option value="rejected">Rejeté</option>
        <option value="converted">Converti</option>
    </select>
    <select wire:model.live="sortBy" class="filter-select">
        <option value="created_at">Date de création</option>
        <option value="valid_until">Date de validité</option>
        <option value="total">Montant</option>
    </select>
</div>

<div class="data-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>N° Proforma</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Valide jusqu'au</th>
                    <th>Total TTC</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quotes as $quote)
                <tr>
                    <td><span class="fw-semibold">{{ $quote->reference }}</span></td>
                    <td>{{ $quote->customer->name ?? '—' }}</td>
                    <td>{{ $quote->date->format('d/m/Y') }}</td>
                    <td>{{ $quote->valid_until ? $quote->valid_until->format('d/m/Y') : '—' }}</td>
                    <td class="fw-semibold">{{ number_format($quote->total, 0, ',', ' ') }} FCFA</td>
                    <td>
                        @php
                            $statusMap = [
                                'draft' => ['draft','Brouillon','bi-file-earmark'],
                                'sent' => ['sent','Envoyé','bi-send'],
                                'approved' => ['approved','Accepté','bi-check-circle'],
                                'rejected' => ['rejected','Rejeté','bi-x-circle'],
                                'converted' => ['converted','Converti','bi-arrow-repeat'],
                            ];
                            $s = $statusMap[$quote->status] ?? ['draft','—','bi-circle'];
                        @endphp
                        <span class="badge-status {{ $s[0] }}"><i class="bi {{ $s[2] }}"></i> {{ $s[1] }}</span>
                    </td>
                    <td style="display:flex;gap:4px;">
                        <a href="{{ route('module3.quotes.show', $quote->id) }}" class="act-btn view" title="Voir la Proforma">
        <i class="bi bi-eye"></i>
    </a>
                        @can('edit quotes')
                        <a href="{{ route('module3.quotes.edit', $quote->id) }}" class="act-btn edit" title="Modifier"><i class="bi bi-pencil"></i></a>
                        @endcan

                        @if($quote->status !== 'converted')
                        @can('convert quotes')
                            <button wire:click="convertToInvoice({{ $quote->id }})" class="act-btn convert" title="Convertir en facture"><i class="bi bi-arrow-repeat"></i></button>
                            @endcan
                        @endif
                        @can('delete quotes')
                        <button wire:click="delete({{ $quote->id }})" wire:confirm="Êtes-vous sûr de vouloir supprimer cette Proforma ?" class="act-btn del"><i class="bi bi-trash3"></i>
</button>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr class="empty-state-row"><td colspan="7">Aucune Proforma trouvée. Créez votre première Proforma.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($quotes->hasPages())
    <div class="pagination-wrap">{{ $quotes->links() }}</div>
    @endif
</div>

{{-- MODAL FILTRES EXPORT PDF --}}
<div class="modal fade modal-jr" id="exportFiltersModal" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg, #dc2626, #ef4444);">
                <h5 class="modal-title"><i class="bi bi-funnel me-2"></i>Filtres – Export PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="fg"><label>Date Proforma (début)</label><input type="date" wire:model="filter_date_from" class="fc"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="fg"><label>Date Proforma (fin)</label><input type="date" wire:model="filter_date_to" class="fc"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="fg"><label>Statut</label>
                            <select wire:model="filter_status" class="fc">
                                <option value="">Tous</option>
                                <option value="draft">Brouillon</option>
                                <option value="sent">Envoyé</option>
                                <option value="accepted">Accepté</option>
                                <option value="rejected">Rejeté</option>
                                <option value="converted">Converti</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fg"><label>Client</label>
                            <select wire:model="filter_customer_id" class="fc">
                                <option value="">Tous les clients</option>
                                @foreach(\App\Models\Module3\Customer::orderBy('name')->get() as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button wire:click="resetFilters" class="btn-ghost" data-bs-dismiss="modal">Réinitialiser</button>
                <button wire:click="exportPdf" class="btn-brand" data-bs-dismiss="modal" style="background:linear-gradient(135deg, #dc2626, #ef4444);"><i class="bi bi-file-pdf"></i> Générer PDF</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('openFiltersModal', () => {
        const modalEl = document.getElementById('exportFiltersModal');
        if (modalEl) new bootstrap.Modal(modalEl).show();
    });
</script>
<script>
    document.addEventListener('livewire:init', function () {
        Livewire.on('scroll-to-top', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>
</div>