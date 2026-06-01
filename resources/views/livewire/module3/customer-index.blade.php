<div style="zoom:0.90;">
<style>
    /* Réutiliser les styles des modules précédents */
    .module-toolbar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; }
    .module-toolbar h2 { font-family: 'Syne', sans-serif; font-size: 19px; font-weight: 800; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
    .module-icon { width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; color: white; font-size: 16px; }
    .module-icon.green  { background: linear-gradient(135deg, var(--brand-green), #22a352); box-shadow: 0 4px 10px rgba(26,122,60,0.28); }
    .toolbar-actions { display: flex; align-items: center; gap: 7px; flex-wrap: wrap; }
    .flash-msg { display: flex; align-items: center; gap: 10px; padding: 11px 16px; border-radius: 11px; font-size: 13px; font-weight: 500; margin-bottom: 16px; border: 1px solid; }
    .flash-msg.success { background: rgba(26,122,60,0.08); border-color: rgba(26,122,60,0.2); color: var(--brand-green); }
    .flash-msg.danger { background: rgba(220,38,38,0.07); border-color: rgba(220,38,38,0.18); color: #dc2626; }
    .search-wrap { position: relative; margin-bottom: 16px; }
    .search-wrap .s-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); font-size: 15px; color: var(--text-muted); pointer-events: none; }
    .search-input { width: 100%; padding: 9px 13px 9px 38px; border: 1px solid var(--border-color); border-radius: 11px; background: var(--bg-card); color: var(--text-primary); font-size: 13px; outline: none; transition: 0.18s; }
    .data-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden; }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: var(--bg-page); padding: 11px 16px; font-size: 11px; text-transform: uppercase; color: var(--text-muted); }
    .data-table td { padding: 12px 16px; border-bottom: 1px solid var(--border-color); font-size: 12px; color: var(--text-primary); }
    .data-table .fw-semibold { font-size: 12px; font-weight: 600; }
    .act-btn { width: 30px; height: 30px; border-radius: 7px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--border-color); background: transparent; cursor: pointer; transition: 0.15s; }
    .act-btn.edit:hover { background: var(--brand-orange-xlight); color: var(--brand-orange); }
    .act-btn.del:hover { background: rgba(220,38,38,0.08); color: #dc2626; }
    .empty-state-row td { text-align: center; padding: 44px 20px; color: var(--text-muted); font-size: 12px; }
    .pagination-wrap { padding: 12px 16px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; }
    .modal-jr .modal-content { background: var(--bg-card); border-radius: 18px; }
    .modal-jr .modal-header { background: linear-gradient(135deg, var(--brand-green), #22a352); border-bottom: none; padding: 18px 22px; }
    .modal-jr .modal-title { color: white; font-size: 15px; font-weight: 700; }
    .modal-jr .btn-close { filter: brightness(0) invert(1); }
    .fg { margin-bottom: 14px; }
    .fg label { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.4px; display: block; margin-bottom: 5px; }
    .fc { width: 100%; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 9px; background: var(--bg-card); color: var(--text-primary); font-size: 13px; outline: none; }
    .modal-footer { display: flex; justify-content: flex-end; gap: 8px; margin-top: 20px; }
</style>

@if(session()->has('message'))<div class="flash-msg success"><i class="bi bi-check-circle-fill"></i>{{ session('message') }}</div>@endif
@if(session()->has('error'))<div class="flash-msg danger"><i class="bi bi-exclamation-circle-fill"></i>{{ session('error') }}</div>@endif

<div class="module-toolbar">
    <h2><span class="module-icon green"><i class="bi bi-people"></i></span> Clients</h2>
    <div class="toolbar-actions">
        @can('create customers')
        <button wire:click="create" class="btn-brand"><i class="bi bi-plus-lg"></i> Nouveau client</button>
        @endcan
    </div>
</div>

<div class="search-wrap">
    <i class="bi bi-search s-icon"></i>
    <input type="text" wire:model.live.debounce.300ms="search" class="search-input" placeholder="Rechercher par nom ou email…">
</div>

<div class="data-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $c)
                <tr>
                    <td><span class="fw-semibold">{{ $c->name }}</span></td>
                    <td>{{ $c->email ?? '—' }}</td>
                    <td>{{ $c->phone ?? '—' }}</td>
                    <td>{{ $c->address ?? '—' }}</td>
                    <td>
                        @can('edit customers')
                        <button wire:click="edit({{ $c->id }})" class="act-btn edit"><i class="bi bi-pencil"></i></button>
                        @endcan
                        @can('delete customers')
                        <button wire:click="delete({{ $c->id }})" onclick="return confirm('Supprimer ce client ?')" class="act-btn del"><i class="bi bi-trash3"></i></button>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr class="empty-state-row">
                    <td colspan="5">Aucun client. Ajoutez votre premier client.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($customers->hasPages())
    <div class="pagination-wrap">{{ $customers->links() }}</div>
    @endif
</div>

@if($showForm)
<div class="modal show d-block modal-jr" tabindex="-1" style="background:rgba(0,0,0,0.55);">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-{{ $customerId ? 'pencil-square' : 'plus-circle' }} me-2"></i>
                    {{ $customerId ? 'Modifier' : 'Ajouter' }} un client
                </h5>
                <button type="button" class="btn-close" wire:click="$set('showForm',false)"></button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="save">
                    <div class="fg"><label>Nom *</label><input type="text" wire:model="name" class="fc"></div>
                    <div class="fg"><label>Email</label><input type="email" wire:model="email" class="fc"></div>
                    <div class="fg"><label>Téléphone</label><input type="text" wire:model="phone" class="fc"></div>
                    <div class="fg"><label>Adresse</label><textarea wire:model="address" rows="2" class="fc"></textarea></div>
                    <div class="fg"><label>N° TVA</label><input type="text" wire:model="tax_number" class="fc"></div>
                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn-ghost" wire:click="$set('showForm',false)">Annuler</button>
                        <button type="submit" class="btn-brand">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
</div>