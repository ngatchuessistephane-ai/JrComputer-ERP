<div style="zoom:0.90;">
<style>
    /* ─── SHARED MODULE STYLES (identique au module fournisseur) ─── */
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
    .module-icon.orange { background: linear-gradient(135deg, var(--brand-orange), #ff9c2a); box-shadow: 0 4px 10px rgba(240,125,0,0.28); }
    .module-icon.teal   { background: linear-gradient(135deg, #0891b2, #38bdf8); box-shadow: 0 4px 10px rgba(8,145,178,0.28); }

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

    /* Badges spécifiques produits */
    .ref-badge {
        display: inline-block; background: var(--brand-green-xlight);
        color: var(--brand-green); font-size: 10.5px; font-weight: 700;
        padding: 2px 8px; border-radius: 5px; font-family: monospace; letter-spacing: 0.04em;
    }
    [data-theme="dark"] .ref-badge {
        background: rgba(34,163,82,0.15);
        color: #4dd47c;
    }

    /* Stock indicator */
    .stock-ok    { color: var(--brand-green); font-weight: 700; }
    .stock-low   { color: var(--brand-orange); font-weight: 700; display: inline-flex; align-items: center; gap: 5px; }
    .stock-low::before {
        content:''; width:6px; height:6px; border-radius:50%;
        background: var(--brand-orange); flex-shrink:0; animation: blink 1.4s infinite;
    }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.15} }

    /* Action buttons */
    .action-btns { display: flex; gap: 5px; }
    .act-btn {
        width: 30px; height: 30px; border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--border-color); background: transparent;
        font-size: 13px; cursor: pointer; transition: all 0.14s;
        color: var(--text-secondary);
    }
    .act-btn.edit:hover    { background: var(--brand-orange-xlight); color: var(--brand-orange); border-color: var(--brand-orange); }
    .act-btn.stock-btn:hover { background: rgba(8,145,178,0.08); color: #0891b2; border-color: #0891b2; }
    .act-btn.del:hover     { background: rgba(220,38,38,0.08); color: #dc2626; border-color: #dc2626; }

    /* Empty state */
    .empty-state-row td { text-align: center; padding: 44px 20px !important; color: var(--text-muted); font-size: 13px; }
    .empty-icon { font-size: 30px; display: block; margin-bottom: 8px; opacity: .45; color: var(--brand-green); }

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

    /* Adjust stock tabs */
    .adj-tabs {
        display: flex; gap: 7px; margin-bottom: 14px;
    }
    .adj-tab {
        flex: 1; padding: 9px; border-radius: 9px;
        border: 1px solid var(--border-color); background: var(--bg-page);
        text-align: center; font-size: 12.5px; font-weight: 600;
        cursor: pointer; transition: all .18s; color: var(--text-secondary);
    }
    .adj-tab.entry.selected { background: var(--brand-green-xlight); border-color: var(--brand-green); color: var(--brand-green); }
    .adj-tab.exit.selected  { background: var(--brand-orange-xlight); border-color: var(--brand-orange); color: var(--brand-orange); }

    /* Import drop zone */
    .import-drop {
        border: 2px dashed var(--border-color); border-radius: 11px;
        padding: 24px; text-align: center; margin-bottom: 14px;
        background: var(--bg-page); transition: border-color .18s;
    }
    .import-drop:hover { border-color: var(--brand-green); }
    .import-drop-icon { font-size: 32px; color: var(--brand-green); margin-bottom: 8px; }
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
    <h2><span class="module-icon green"><i class="bi bi-box-seam"></i></span> Gestion des produits</h2>
    <div class="toolbar-actions">
       @can('create products')
        <button wire:click="create" class="btn-brand"><i class="bi bi-plus-lg"></i> Nouveau produit</button>
       
        <button type="button" class="btn-ghost" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-upload"></i> Importer Excel
        </button>
        @endcan
        {{-- Dans la toolbar, remplacez le bouton Exporter PDF --}}
       <button type="button" wire:click="openExportModal" class="btn-accent" style="background: linear-gradient(135deg, #dc2626, #ef4444);">
    <i class="bi bi-file-pdf"></i> Exporter PDF
        </button>
       </div>
</div>

{{-- Search --}}
<div class="search-wrap">
    <i class="bi bi-search s-icon"></i>
    <input type="text" wire:model.live.debounce.300ms="search" class="search-input" placeholder="Rechercher par nom, référence ou numéro de série…">
</div>

{{-- Table --}}
<div class="data-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Référence</th><th>Produit</th><th>N° Série</th>
                    <th>Prix vente</th><th>Stock</th>
                   @can('view suppliers')
                    <th>Fournisseur</th>
                    @endcan
                    @can('manage stock')
                    <th>Actions</th>
                    @endcan
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                <tr>
                    <td><span class="ref-badge">{{ $p->reference }}</span></td>
                    <td>
                        <div style="font-weight:600;">{{ $p->name }}</div>
                        @if($p->category)<div style="font-size:11px;color:var(--text-muted);">{{ $p->category }}</div>@endif
                    </td>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted);">{{ $p->serial_number ?? '—' }}</td>
                    <td style="font-weight:600;">{{ number_format($p->selling_price,0,',',' ') }} FCFA</td>
                    <td>
                        @if($p->quantity <= $p->alert_threshold)
                            <span class="stock-low">{{ $p->quantity }}</span>
                        @else
                            <span class="stock-ok">{{ $p->quantity }}</span>
                        @endif
                    </td>
                    @can('view suppliers')
                    
                    <td style="font-size:12.5px;color:var(--text-secondary);">{{ $p->supplier ?: '—' }}</td>
                     @endcan
                    <td>
                        <div class="action-btns">
                           @can('edit products')
                            <button wire:click="edit({{ $p->id }})" class="act-btn edit" title="Modifier"><i class="bi bi-pencil"></i></button>
                           @endcan
                           @can('manage stock')
                            <button wire:click="openAdjustStock({{ $p->id }})" class="act-btn stock-btn" title="Ajuster stock" data-bs-toggle="modal" data-bs-target="#adjustModal"><i class="bi bi-graph-up-arrow"></i></button>
                           @endcan
                           @can('delete products')
                           <button wire:click="delete({{ $p->id }})" wire:confirm="Êtes-vous sûr de vouloir supprimer ce produit ?" class="act-btn del" title="Supprimer">
    <i class="bi bi-trash3"></i>
</button>
                           @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="empty-state-row">
                    <td colspan="7">
                        <span class="empty-icon"><i class="bi bi-box-seam"></i></span>
                        Aucun produit trouvé. Ajoutez votre premier produit.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div class="pagination-wrap">{{ $products->links() }}</div>
    @endif
</div>

{{-- MODAL — Produit --}}
@if($showForm)
<div class="modal show d-block modal-jr" tabindex="-1" style="background:rgba(0,0,0,0.55);">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg, var(--brand-green) 0%, #2d9e5a 100%);">
                <h5 class="modal-title">
                    <i class="bi bi-{{ $productId ? 'pencil-square' : 'plus-circle' }} me-2"></i>
                    {{ $productId ? 'Modifier le produit' : 'Ajouter un produit' }}
                </h5>
                <button type="button" class="btn-close" wire:click="$set('showForm',false)"></button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="save">
                    <div class="row g-3">
                        <div class="col-md-6"><div class="fg"><label>Nom *</label><input type="text" wire:model="name" class="fc @error('name') is-invalid @enderror">@error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div></div>
                        <div class="col-md-6"><div class="fg"><label>Référence *</label><input type="text" wire:model="reference" class="fc @error('reference') is-invalid @enderror">@error('reference')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div></div>
                        <div class="col-md-6"><div class="fg"><label>N° Série</label><input type="text" wire:model="serial_number" class="fc @error('serial_number') is-invalid @enderror">@error('serial_number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div></div>

                        <div class="col-md-6">
    <div class="fg">
        <label>Catégorie</label>
        <input type="text" list="categoryList" wire:model="category"
               class="fc @error('category') is-invalid @enderror"
               placeholder="Sélectionner ou saisir une nouvelle catégorie">
        <datalist id="categoryList">
            @foreach($categories as $cat)
                <option value="{{ $cat }}"></option>
            @endforeach
        </datalist>
        @error('category')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
</div>

                        

                        <div class="col-md-6"><div class="fg"><label>Prix achat (FCFA)</label><input type="number" step="0.01" wire:model="purchase_price" class="fc @error('purchase_price') is-invalid @enderror">@error('purchase_price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div></div>
                        <div class="col-md-6"><div class="fg"><label>Prix vente * (FCFA)</label><input type="number" step="0.01" wire:model="selling_price" class="fc @error('selling_price') is-invalid @enderror">@error('selling_price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div></div>
                        <div class="col-md-4"><div class="fg"><label>Quantité initiale</label><input type="number" wire:model="quantity" class="fc @error('quantity') is-invalid @enderror">@error('quantity')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div></div>
                        <div class="col-md-4"><div class="fg"><label>Seuil d'alerte</label><input type="number" wire:model="alert_threshold" class="fc @error('alert_threshold') is-invalid @enderror">@error('alert_threshold')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div></div>
                        <div class="col-md-4">
                            <div class="fg"><label>Fournisseur</label>
                            <select wire:model="supplier" class="fc @error('supplier') is-invalid @enderror">
                                <option value="">-- Sélectionner --</option>
                                @foreach($suppliersList as $supp)
                                    <option value="{{ $supp->name }}">{{ $supp->name }} ({{ $supp->code }})</option>
                                @endforeach
                            </select>
                            @error('supplier')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            @if($suppliersList->isEmpty())
                                <div class="mt-1 small text-warning">⚠️ <a href="{{ route('module2.suppliers.index') }}" target="_blank" style="color:var(--brand-green);">Ajoutez un fournisseur</a></div>
                            @endif
                            </div>
                        </div>
                        <div class="col-12"><div class="fg"><label>Description</label><textarea wire:model="description" class="fc @error('description') is-invalid @enderror" rows="2"></textarea>@error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div></div>
                    </div>
                    <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:18px;">
                        <button type="button" class="btn-ghost" wire:click="$set('showForm',false)">Annuler</button>
                        <button type="submit" class="btn-brand"><i class="bi bi-check2"></i> {{ $productId ? 'Enregistrer' : 'Ajouter' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

{{-- MODAL — Ajustement stock --}}
<div class="modal fade modal-jr" id="adjustModal" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg, var(--brand-green) 0%, #2d9e5a 100%);">
                <h5 class="modal-title"><i class="bi bi-graph-up-arrow me-2"></i>Ajuster le stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="adj-tabs">
                    <div class="adj-tab entry {{ isset($adjustType) && $adjustType==='in' ? 'selected' : '' }}" wire:click="$set('adjustType','in')">
                        <i class="bi bi-arrow-down-circle me-1"></i> Entrée
                    </div>
                    <div class="adj-tab exit {{ isset($adjustType) && $adjustType==='out' ? 'selected' : '' }}" wire:click="$set('adjustType','out')">
                        <i class="bi bi-arrow-up-circle me-1"></i> Sortie
                    </div>
                </div>
                <div class="fg"><label>Quantité</label><input type="number" wire:model="adjustQuantityValue" class="fc"></div>
                <div class="fg"><label>Raison</label><input type="text" wire:model="adjustReason" class="fc" placeholder="Achat, vente, casse…"></div>
            </div>
            <div class="modal-footer">
                <button class="btn-ghost" data-bs-dismiss="modal">Annuler</button>
                <button wire:click="adjustStock" class="btn-brand" data-bs-dismiss="modal"><i class="bi bi-check2"></i> Valider</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL — Import Excel --}}
<div class="modal fade modal-jr" id="importModal" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg, var(--brand-green) 0%, #2d9e5a 100%);">
                <h5 class="modal-title"><i class="bi bi-file-earmark-excel me-2"></i>Importer depuis Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="import-drop">
                    <div class="import-drop-icon"><i class="bi bi-cloud-upload"></i></div>
                    <p>Sélectionnez votre fichier Excel ou CSV</p>
                    <input type="file" wire:model="importFile" class="fc" accept=".xlsx,.xls,.csv" style="max-width:260px;margin:0 auto;">
                    <div wire:loading wire:target="importFile" style="font-size:11.5px;color:var(--brand-green);margin-top:6px;"><i class="bi bi-arrow-repeat"></i> Chargement…</div>
                </div>
                <div style="background:var(--bg-page);border-radius:9px;padding:10px 13px;border:1px solid var(--border-color);">
                    <p style="font-size:11px;"><strong>Colonnes :</strong> nom · reference · numero_serie · prix_achat · prix_vente · quantite · seuil_alerte · categorie · fournisseur</p>
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
<div class="modal fade modal-jr" id="exportFiltersModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg, #dc2626, #ef4444);">
                <h5 class="modal-title"><i class="bi bi-funnel me-2"></i>Filtres – Export PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="fg"><label>Date création (début)</label>
                            <input type="date" wire:model.change="filter_date_from" class="fc">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fg"><label>Date création (fin)</label>
                            <input type="date" wire:model.change="filter_date_to" class="fc">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fg"><label>Prix min (FCFA)</label>
                            <input type="number" wire:model.change="filter_price_min" class="fc" step="1000">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fg"><label>Prix max (FCFA)</label>
                            <input type="number" wire:model.change="filter_price_max" class="fc" step="1000">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fg"><label>Catégorie</label>
                            <select wire:model.change="filter_category" class="fc">
                                <option value="">Toutes catégories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fg"><label>Fournisseur</label>
                            <select wire:model.change="filter_supplier" class="fc">
                                <option value="">Tous fournisseurs</option>
                                @foreach($suppliersList as $supp)
                                    <option value="{{ $supp->name }}">{{ $supp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fg"><label>Stock</label>
                            <select wire:model.change="filter_stock_status" class="fc">
                                <option value="">Tous</option>
                                <option value="low">Stock bas (≤ seuil)</option>
                                <option value="ok">Stock normal</option>
                            </select>
                        </div>
                    </div>
                </div>
                @if($filter_date_from || $filter_date_to || $filter_price_min || $filter_price_max || $filter_category || $filter_supplier || $filter_stock_status)
                    <div class="mt-3 p-2" style="background: rgba(26,122,60,0.08); border-radius: 8px;">
                        <small class="text-muted">Filtres actifs</small>
                        <div class="mt-1">
                            <button type="button" wire:click="resetFilters" class="btn-ghost btn-sm" style="padding: 4px 12px; font-size: 11px;">
                                <i class="bi bi-eraser"></i> Tout effacer
                            </button>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-ghost" data-bs-dismiss="modal">Fermer</button>
                <button type="button" wire:click="exportPdf" class="btn-brand" style="background:linear-gradient(135deg, #dc2626, #ef4444);">
                    <i class="bi bi-file-pdf"></i> Générer PDF
                </button>
            </div>
        </div>
    </div>
</div>

{{-- À la fin du fichier, remplacez le script --}}
<script>
    (function() {
        // Attendre que le DOM soit prêt
        function initModals() {
            // Vérifier si Bootstrap est disponible
            if (typeof bootstrap === 'undefined') {
                console.warn('Bootstrap non chargé, réessai dans 500ms...');
                setTimeout(initModals, 500);
                return;
            }
            
            // Modal Export PDF
            const exportModalEl = document.getElementById('exportFiltersModal');
            if (exportModalEl && !exportModalEl._bsModal) {
                exportModalEl._bsModal = new bootstrap.Modal(exportModalEl);
            }
            
            // Modal Ajustement Stock
            const adjustModalEl = document.getElementById('adjustModal');
            if (adjustModalEl && !adjustModalEl._bsModal) {
                adjustModalEl._bsModal = new bootstrap.Modal(adjustModalEl);
            }
        }
        
        // Ouvrir modal export
        window.addEventListener('open-export-modal', () => {
            const modalEl = document.getElementById('exportFiltersModal');
            if (modalEl && modalEl._bsModal) {
                modalEl._bsModal.show();
            } else if (modalEl && typeof bootstrap !== 'undefined') {
                modalEl._bsModal = new bootstrap.Modal(modalEl);
                modalEl._bsModal.show();
            } else {
                console.error('Modal export non disponible');
            }
        });
        
        // ⭐ NOUVEAU : Fermer le modal export
        window.addEventListener('close-export-modal', () => {
            const modalEl = document.getElementById('exportFiltersModal');
            if (modalEl && modalEl._bsModal) {
                modalEl._bsModal.hide();
            }
        });
        
        // ⭐ NOUVEAU : Télécharger le PDF
        window.addEventListener('download-pdf', (event) => {
            if (event.detail && event.detail.url) {
                // Ouvrir dans un nouvel onglet (ne bloque pas l'interface)
                window.open(event.detail.url, '_blank');
            }
        });
        
        // Ouvrir modal ajustement stock
        window.addEventListener('open-adjust-modal', () => {
            const modalEl = document.getElementById('adjustModal');
            if (modalEl && modalEl._bsModal) {
                modalEl._bsModal.show();
            } else if (modalEl && typeof bootstrap !== 'undefined') {
                modalEl._bsModal = new bootstrap.Modal(modalEl);
                modalEl._bsModal.show();
            }
        });
        
        // Initialiser au chargement
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initModals);
        } else {
            initModals();
        }
        
        // Réinitialiser après chaque navigation Livewire
        document.addEventListener('livewire:navigated', initModals);
    })();

</script>
<script>
    document.addEventListener('livewire:init', function () {
        Livewire.on('scroll-to-top', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>
</div>