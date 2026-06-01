<div style="zoom:0.90;">
<style>
    /* ─── SHARED MODULE STYLES (identique à la version produit) ─── */
    .module-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px; margin-bottom: 20px;
    }
    .module-toolbar h2 {
        font-family: 'Syne', sans-serif; font-size: 19px; font-weight: 800;
        color: var(--text-primary); margin: 0;
        display: flex; align-items: center; gap: 10px;
    }
    .module-icon {
        width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
        display: inline-flex; align-items: center; justify-content: center;
        color: white; font-size: 16px;
    }
    .module-icon.green  { background: linear-gradient(135deg, var(--brand-green), #22a352); box-shadow: 0 4px 10px rgba(26,122,60,0.28); }

    .toolbar-actions { display: flex; align-items: center; gap: 7px; flex-wrap: wrap; }

    /* Flash messages */
    .flash-msg {
        display: flex; align-items: center; gap: 10px;
        padding: 11px 16px; border-radius: 11px;
        font-size: 13px; font-weight: 500;
        margin-bottom: 16px; border: 1px solid;
    }
    .flash-msg.success { background: rgba(26,122,60,0.08); border-color: rgba(26,122,60,0.2); color: var(--brand-green); }
    .flash-msg.danger  { background: rgba(220,38,38,0.07); border-color: rgba(220,38,38,0.18); color: #dc2626; }
    .flash-msg i { font-size: 16px; flex-shrink: 0; }

    /* Search */
    .search-wrap { position: relative; margin-bottom: 16px; }
    .search-wrap .s-icon {
        position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
        font-size: 15px; color: var(--text-muted); pointer-events: none;
    }
    .search-input {
        width: 100%; padding: 9px 13px 9px 38px;
        border: 1px solid var(--border-color); border-radius: 11px;
        background: var(--bg-card); color: var(--text-primary);
        font-size: 13px; outline: none; transition: 0.18s;
    }
    .search-input:focus { border-color: var(--brand-green); box-shadow: 0 0 0 3px rgba(26,122,60,0.1); }

    /* Data card / Table */
    .data-card {
        background: var(--bg-card); border: 1px solid var(--border-color);
        border-radius: 14px; box-shadow: var(--shadow-card); overflow: hidden;
    }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead tr { background: var(--bg-page); border-bottom: 1px solid var(--border-color); }
    .data-table th {
        font-size: 10.5px; font-weight: 700; letter-spacing: 0.07em;
        text-transform: uppercase; color: var(--text-muted);
        padding: 11px 16px; white-space: nowrap; text-align: left;
    }
    .data-table td {
        padding: 12px 16px; font-size: 13px; color: var(--text-primary);
        border-bottom: 1px solid var(--border-color); vertical-align: middle;
    }
    .data-table tbody tr:last-child td { border-bottom: none; }
    .data-table tbody tr:hover { background: var(--bg-hover); }

    /* Badges */
    .code-badge {
        display: inline-block; background: rgba(100,116,139,0.1);
        color: var(--text-secondary); font-size: 10.5px; font-weight: 700;
        padding: 2px 8px; border-radius: 5px; font-family: monospace;
    }

    /* Action buttons */
    .action-btns { display: flex; gap: 5px; }
    .act-btn {
        width: 30px; height: 30px; border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--border-color); background: transparent;
        font-size: 13px; cursor: pointer; transition: all 0.14s;
        color: var(--text-secondary);
    }
    .act-btn.edit:hover { background: var(--brand-orange-xlight); color: var(--brand-orange); border-color: var(--brand-orange); }
    .act-btn.del:hover { background: rgba(220,38,38,0.08); color: #dc2626; border-color: #dc2626; }

    /* Empty state */
    .empty-state-row td { text-align: center; padding: 44px 20px !important; color: var(--text-muted); font-size: 13px; }
    .empty-icon { font-size: 30px; display: block; margin-bottom: 8px; opacity: .45; color: var(--brand-orange); }

    /* Pagination */
    .pagination-wrap { padding: 12px 16px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; }
    .pagination .page-item .page-link {
        border: 1px solid var(--border-color); background: var(--bg-card);
        color: var(--text-secondary); border-radius: 7px !important;
        margin: 0 2px; font-size: 12.5px; transition: all .14s;
    }
    .pagination .page-item.active .page-link { background: var(--brand-green); border-color: var(--brand-green); color: white; }

    /* Modals */
    .modal-jr .modal-content {
        background: var(--bg-card); border: 1px solid var(--border-color);
        border-radius: 18px; box-shadow: 0 24px 64px rgba(0,0,0,0.16);
        color: var(--text-primary); overflow: hidden;
    }
    .modal-jr .modal-header { border-bottom: none; padding: 18px 22px; }
    .modal-jr .modal-title { font-family: 'Syne', sans-serif; font-size: 15px; font-weight: 800; color: white; }
    .modal-jr .btn-close { filter: brightness(0) invert(1); opacity: .8; }
    .modal-jr .btn-close:hover { opacity: 1; }
    .modal-jr .modal-body { padding: 22px; }
    .modal-jr .modal-footer { padding: 14px 22px; border-top: 1px solid var(--border-color); background: var(--bg-page); }

    /* Form fields */
    .fg { margin-bottom: 14px; }
    .fg label {
        display: block; font-size: 10.5px; font-weight: 700;
        letter-spacing: 0.05em; text-transform: uppercase;
        color: var(--text-muted); margin-bottom: 5px;
    }
    .fc {
        width: 100%; padding: 8px 12px;
        border: 1px solid var(--border-color); border-radius: 9px;
        background: var(--bg-page); color: var(--text-primary);
        font-size: 13px; outline: none; transition: 0.18s;
    }
    .fc:focus { border-color: var(--brand-green); box-shadow: 0 0 0 3px rgba(26,122,60,0.1); }

    /* Import drop zone */
    .import-drop {
        border: 2px dashed var(--border-color); border-radius: 11px;
        padding: 24px; text-align: center; margin-bottom: 14px;
        background: var(--bg-page); transition: border-color .18s;
    }
    .import-drop:hover { border-color: var(--brand-orange); }
    .import-drop-icon { font-size: 32px; color: var(--brand-orange); margin-bottom: 8px; }
</style>

{{-- Flash messages --}}
@if(session()->has('message'))
    <div class="flash-msg success"><i class="bi bi-check-circle-fill"></i>{{ session('message') }}</div>
@endif
@if(session()->has('error'))
    <div class="flash-msg danger"><i class="bi bi-exclamation-circle-fill"></i>{{ session('error') }}</div>
@endif
@if($errors->any())
    <div class="flash-msg danger">
        <i class="bi bi-exclamation-circle-fill"></i>
        <div><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    </div>
@endif

{{-- Toolbar --}}
<div class="module-toolbar">
    <h2><span class="module-icon green"><i class="bi bi-truck"></i></span> Fournisseurs</h2>
    <div class="toolbar-actions">
        @can('create suppliers')
        <button wire:click="create" class="btn-brand"><i class="bi bi-plus-lg"></i> Nouveau fournisseur</button>
        
        <button type="button" class="btn-ghost" data-bs-toggle="modal" data-bs-target="#importModal"><i class="bi bi-upload"></i> Importer Excel</button>
        @endcan
        <button wire:click="openFiltersModal" class="btn-accent" style="background: linear-gradient(135deg, #dc2626, #ef4444);">
            <i class="bi bi-file-pdf"></i> Exporter PDF
        </button>
    </div>
</div>

{{-- Search --}}
<div class="search-wrap">
    <i class="bi bi-search s-icon"></i>
    <input type="text" wire:model.live.debounce.300ms="search" class="search-input" placeholder="Rechercher par nom ou code fournisseur…">
</div>

{{-- Table --}}
<div class="data-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Code</th><th>Nom</th><th>Contact</th>
                    <th>Email</th><th>Téléphone</th><th>Délai paiement</th>
                    @can('edit suppliers')<th>Actions</th>@endcan
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $s)
                <tr>
                    <td><span class="code-badge">{{ $s->code }}</span></td>
                    <td>
                        <div style="font-weight:600;">{{ $s->name }}</div>
                        @if($s->address)<div style="font-size:11px;color:var(--text-muted);">{{ Str::limit($s->address, 40) }}</div>@endif
                    </td>
                    <td style="font-size:12.5px;">{{ $s->contact_person ?? '—' }}</td>
                    <td>
                        @if($s->email)
                            <a href="mailto:{{ $s->email }}" style="font-size:12.5px;color:var(--brand-green);text-decoration:none;">{{ $s->email }}</a>
                        @else —
                        @endif
                    </td>
                    <td style="font-size:12.5px;font-family:monospace;">{{ $s->phone ?? '—' }}</td>
                    <td>
                        <span style="display:inline-flex;align-items:center;gap:4px;">
                            <i class="bi bi-calendar3" style="color:var(--text-muted);font-size:11px;"></i>
                            {{ $s->payment_terms }} jours
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                        @can('edit suppliers')    
                        <button wire:click="edit({{ $s->id }})" class="act-btn edit"><i class="bi bi-pencil"></i></button>
                        @endcan
                        @can('delete suppliers')
                        <button wire:click="delete({{ $s->id }})" onclick="return confirm('Supprimer ce fournisseur ?')" class="act-btn del"><i class="bi bi-trash3"></i></button>
                        @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="empty-state-row">
                    <td colspan="7">
                        <span class="empty-icon"><i class="bi bi-truck"></i></span>
                        Aucun fournisseur enregistré. Ajoutez votre premier fournisseur.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($suppliers->hasPages())
    <div class="pagination-wrap">{{ $suppliers->links() }}</div>
    @endif
</div>

{{-- MODAL — Formulaire fournisseur --}}
@if($showForm)
<div class="modal show d-block modal-jr" tabindex="-1" style="background:rgba(0,0,0,0.55);">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg, var(--brand-orange) 0%, #e07200 100%);">
                <h5 class="modal-title">
                    <i class="bi bi-{{ $supplierId ? 'pencil-square' : 'plus-circle' }} me-2"></i>
                    {{ $supplierId ? 'Modifier le fournisseur' : 'Ajouter un fournisseur' }}
                </h5>
                <button type="button" class="btn-close" wire:click="$set('showForm',false)"></button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="save">
                    <div class="row g-3">
                        <div class="col-md-6"><div class="fg"><label>Nom *</label><input type="text" wire:model="name" class="fc"></div></div>
                        <div class="col-md-6"><div class="fg"><label>Code *</label><input type="text" wire:model="code" class="fc"></div></div>
                        <div class="col-md-6"><div class="fg"><label>Personne de contact</label><input type="text" wire:model="contact_person" class="fc"></div></div>
                        <div class="col-md-6"><div class="fg"><label>Email</label><input type="email" wire:model="email" class="fc"></div></div>
                        <div class="col-md-6"><div class="fg"><label>Téléphone</label><input type="text" wire:model="phone" class="fc"></div></div>
                        <div class="col-md-6"><div class="fg"><label>N° TVA</label><input type="text" wire:model="tax_number" class="fc"></div></div>
                        <div class="col-md-6"><div class="fg"><label>Délai de paiement (jours)</label><input type="number" wire:model="payment_terms" class="fc"></div></div>
                        <div class="col-12"><div class="fg"><label>Adresse</label><textarea wire:model="address" rows="2" class="fc"></textarea></div></div>
                    </div>
                    <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:18px;">
                        <button type="button" class="btn-ghost" wire:click="$set('showForm',false)">Annuler</button>
                        <button type="submit" class="btn-brand"><i class="bi bi-check2"></i> Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

{{-- MODAL — Import Excel --}}
<div class="modal fade modal-jr" id="importModal" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg, var(--brand-orange) 0%, #e07200 100%);">
                <h5 class="modal-title"><i class="bi bi-file-earmark-excel me-2"></i>Importer des fournisseurs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="import-drop">
                    <div class="import-drop-icon"><i class="bi bi-cloud-upload"></i></div>
                    <p>Sélectionnez votre fichier Excel ou CSV</p>
                    <input type="file" wire:model="importFile" class="fc" accept=".xlsx,.xls,.csv" style="max-width:260px;margin:0 auto;">
                    <div wire:loading wire:target="importFile" style="font-size:11.5px;color:var(--brand-orange);margin-top:6px;"><i class="bi bi-arrow-repeat"></i> Chargement…</div>
                </div>
                <div style="background:var(--bg-page);border-radius:9px;padding:10px 13px;border:1px solid var(--border-color);">
                    <p style="font-size:11px;"><strong>Colonnes :</strong> nom · code · contact · email · telephone · adresse · num_tva · delai_paiement</p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-ghost" data-bs-dismiss="modal">Annuler</button>
                <button wire:click="import" class="btn-brand" data-bs-dismiss="modal"><i class="bi bi-upload"></i> Importer</button>
            </div>
        </div>
    </div>
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
                        <div class="fg"><label>Date création (début)</label><input type="date" wire:model="filter_date_from" class="fc"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="fg"><label>Date création (fin)</label><input type="date" wire:model="filter_date_to" class="fc"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="fg"><label>Nom / code</label><input type="text" wire:model="filter_name" class="fc" placeholder="Rechercher..."></div>
                    </div>
                    <div class="col-md-6">
                        <div class="fg"><label>Délai paiement (min)</label><input type="number" wire:model="filter_payment_terms_min" class="fc"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="fg"><label>Délai paiement (max)</label><input type="number" wire:model="filter_payment_terms_max" class="fc"></div>
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
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    });
</script>
</div>