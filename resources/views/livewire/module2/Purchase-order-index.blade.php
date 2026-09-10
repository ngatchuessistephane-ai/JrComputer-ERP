<div style="zoom:0.90;">
<style>
    /* ─── SHARED MODULE STYLES ─── */
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
    .search-input:focus { border-color: var(--brand-green); box-shadow: 0 0 0 3px rgba(26,122,60,0.1); }
    .data-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 14px; box-shadow: var(--shadow-card); overflow: hidden; }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead tr { background: var(--bg-page); border-bottom: 1px solid var(--border-color); }
    .data-table th { font-size: 10.5px; font-weight: 700; letter-spacing: 0.07em; text-transform: uppercase; color: var(--text-muted); padding: 11px 16px; white-space: nowrap; text-align: left; }
    .data-table td { padding: 12px 16px; font-size: 13px; color: var(--text-primary); border-bottom: 1px solid var(--border-color); vertical-align: middle; }
    .data-table tbody tr:last-child td { border-bottom: none; }
    .data-table tbody tr:hover { background: var(--bg-hover); }
    .ref-badge { display: inline-block; background: rgba(8,145,178,0.1); color: #0891b2; font-size: 10.5px; font-weight: 700; padding: 2px 8px; border-radius: 5px; font-family: monospace; }
    .status-badge { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 600; padding: 3px 9px; border-radius: 99px; }
    .status-badge.draft { background: rgba(100,116,139,0.1); color: #64748b; }
    .status-badge.sent { background: rgba(8,145,178,0.1); color: #0891b2; }
    .status-badge.partial { background: rgba(240,125,0,0.12); color: var(--brand-orange); }
    .status-badge.received { background: rgba(26,122,60,0.1); color: var(--brand-green); }
    .status-badge.cancelled { background: rgba(220,38,38,0.09); color: #dc2626; }
    .status-badge::before { content:''; width:6px; height:6px; border-radius:50%; background: currentColor; }
    .action-btns { display: flex; gap: 5px; }
    .act-btn { width: 30px; height: 30px; border-radius: 7px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-color); background: transparent; font-size: 13px; cursor: pointer; transition: all 0.14s; color: var(--text-secondary); }
    .act-btn.edit:hover { background: var(--brand-orange-xlight); color: var(--brand-orange); border-color: var(--brand-orange); }
    .act-btn.receive:hover { background: rgba(26,122,60,0.08); color: var(--brand-green); border-color: var(--brand-green); }
    .act-btn.cancel:hover { background: rgba(220,38,38,0.08); color: #dc2626; border-color: #dc2626; }
    .act-btn.del:hover { background: rgba(220,38,38,0.08); color: #dc2626; border-color: #dc2626; }
    .empty-state-row td { text-align: center; padding: 44px 20px !important; color: var(--text-muted); font-size: 13px; }
    .empty-icon { font-size: 30px; display: block; margin-bottom: 8px; opacity: .45; color: #0891b2; }
    .pagination-wrap { padding: 12px 16px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; }
    .pagination .page-item .page-link { border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-secondary); border-radius: 7px !important; margin: 0 2px; font-size: 12.5px; transition: all .14s; }
    .pagination .page-item.active .page-link { background: var(--brand-green); border-color: var(--brand-green); color: white; }
    .modal-jr .modal-content { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 18px; box-shadow: 0 24px 64px rgba(0,0,0,0.16); color: var(--text-primary); overflow: hidden; }
    .modal-jr .modal-header { border-bottom: none; padding: 18px 22px; }
    .modal-jr .modal-title { font-family: 'Syne', sans-serif; font-size: 15px; font-weight: 800; color: white; }
    .modal-jr .btn-close { filter: brightness(0) invert(1); opacity: .8; }
    .modal-jr .btn-close:hover { opacity: 1; }
    .modal-jr .modal-body { padding: 22px; }
    .modal-jr .modal-footer { padding: 14px 22px; border-top: 1px solid var(--border-color); background: var(--bg-page); }
    .fg { margin-bottom: 14px; }
    .fg label { display: block; font-size: 10.5px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 5px; }
    .fc { width: 100%; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 9px; background: var(--bg-page); color: var(--text-primary); font-size: 13px; outline: none; transition: 0.18s; }
    .fc:focus { border-color: var(--brand-green); box-shadow: 0 0 0 3px rgba(26,122,60,0.1); }
    .totals-row td { padding: 6px 12px; font-size: 12.5px; }
    .totals-row.total td { font-weight: 700; font-size: 13.5px; color: var(--text-primary); }
    .totals-sep { border-top: 2px solid var(--border-color); }
    
    /* ⭐ Supprimer le style pour le bouton annuler du modal (car il y a déjà btn-ghost) */
    .btn-ghost { background: transparent; color: var(--text-secondary); border: 1px solid var(--border-color); border-radius: 10px; padding: 9px 18px; font-size: 13.5px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; transition: all 0.18s; text-decoration: none; }
    .btn-ghost:hover { border-color: var(--brand-green); color: var(--brand-green); background: var(--brand-green-xlight); }
    .btn-brand { background: linear-gradient(135deg, var(--brand-green) 0%, var(--brand-green-light) 100%); color: white; border: none; border-radius: 10px; padding: 9px 18px; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; transition: all 0.18s; box-shadow: 0 4px 12px rgba(26,122,60,0.28); text-decoration: none; }
    .btn-brand:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(26,122,60,0.38); }
    .btn-accent { background: linear-gradient(135deg, var(--brand-orange) 0%, var(--brand-orange-light) 100%); color: white; border: none; border-radius: 10px; padding: 9px 18px; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; transition: all 0.18s; box-shadow: 0 4px 12px rgba(240,125,0,0.28); text-decoration: none; }
    .btn-accent:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(240,125,0,0.38); }
</style>

{{-- Flash --}}
@if(session()->has('message'))<div class="flash-msg success"><i class="bi bi-check-circle-fill"></i>{{ session('message') }}</div>@endif
@if(session()->has('error'))<div class="flash-msg danger"><i class="bi bi-exclamation-circle-fill"></i>{{ session('error') }}</div>@endif
@if($errors->any())<div class="flash-msg danger"><i class="bi bi-exclamation-circle-fill"></i><div><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div></div>@endif

{{-- Toolbar --}}
<div class="module-toolbar">
    <h2><span class="module-icon green"><i class="bi bi-cart-check"></i></span> Bons de commande fournisseurs</h2>
    <div class="toolbar-actions">
        @can('create purchase orders')
        <button wire:click="create" class="btn-brand"><i class="bi bi-plus-lg"></i> Nouveau bon de commande</button>
        @endcan
        <button type="button" class="btn-accent" wire:click="openFiltersModal" style="background: linear-gradient(135deg, #dc2626, #ef4444);">
            <i class="bi bi-file-pdf"></i> Exporter PDF
        </button>
    </div>
</div>

{{-- Search --}}
<div class="search-wrap"><i class="bi bi-search s-icon"></i><input type="text" wire:model.live.debounce.300ms="search" class="search-input" placeholder="Rechercher par référence ou fournisseur…"></div>

{{-- Table --}}
<div class="data-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead><tr>
                <th>Référence</th>
                <th>Fournisseur</th>
                <th>Date</th>
                <th>Livraison prévue</th>
                <th>Total</th>
                <th>Statut</th>
                @can('create purchase orders')
                <th>Actions</th>
                @endcan
            </tr></thead>
            <tbody>
                @forelse($purchaseOrders as $po)
                <tr>
                    <td><span class="ref-badge">{{ $po->reference }}</span></td>
                    <td><div style="font-weight:600;">{{ $po->supplier->name }}</div><div style="font-size:11px;color:var(--text-muted);">{{ $po->supplier->code }}</div></td>
                    <td style="font-size:12.5px;">{{ $po->order_date->format('d/m/Y') }}</td>
                    <td style="font-size:12.5px;">{{ $po->expected_delivery_date ? $po->expected_delivery_date->format('d/m/Y') : '—' }}</td>
                    <td style="font-weight:700;">{{ number_format($po->total,0,',',' ') }} FCFA</td>
                    <td>@switch($po->status)@case('draft')<span class="status-badge draft">Brouillon</span>@break @case('sent')<span class="status-badge sent">Envoyé</span>@break @case('partially_received')<span class="status-badge partial">Partiel</span>@break @case('received')<span class="status-badge received">Reçu</span>@break @case('cancelled')<span class="status-badge cancelled">Annulé</span>@break @endswitch</td>
                    <td>
                        <div class="action-btns">
                            @can('edit purchase orders')
                            <button wire:click="edit({{ $po->id }})" class="act-btn edit"><i class="bi bi-pencil"></i></button>
                            @endcan

                            @if($po->status !== 'received' && $po->status !== 'cancelled')
                                @can('receive purchase orders')
                                <button wire:click="receiveOrder({{ $po->id }})" wire:confirm="Confirmer la réception de cette commande ?" class="act-btn receive"><i class="bi bi-arrow-down-circle"></i></button>
                                @endcan
                                @can('edit purchase orders')
                                <button wire:click="cancelOrder({{ $po->id }})" wire:confirm="Annuler cette commande ?" class="act-btn cancel"><i class="bi bi-x-circle"></i></button>
                                @endcan
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="empty-state-row"><td colspan="7"><span class="empty-icon"><i class="bi bi-cart-check"></i></span>Aucun bon de commande. Créez votre première commande fournisseur.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($purchaseOrders->hasPages())<div class="pagination-wrap">{{ $purchaseOrders->links() }}</div>@endif
</div>

{{-- MODAL — Formulaire bon de commande --}}
@if($showForm)
<div class="modal show d-block modal-jr" tabindex="-1" style="background:rgba(0,0,0,0.55); overflow-y:auto;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg, var(--brand-green), #22a352);">
                <h5 class="modal-title"><i class="bi bi-{{ $purchaseOrderId ? 'pencil-square' : 'plus-circle' }} me-2"></i>{{ $purchaseOrderId ? 'Modifier le bon de commande' : 'Nouveau bon de commande' }}</h5>
                <button type="button" class="btn-close" wire:click="$set('showForm',false)"></button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="save">
                    {{-- Infos générales --}}
                    <div style="background:var(--bg-page);border-radius:11px;padding:16px 18px;margin-bottom:18px;border:1px solid var(--border-color);">
                        <div style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:12px;"><i class="bi bi-info-circle me-1"></i> Informations générales</div>
                        <div class="row g-3">
                            <div class="col-md-5"><div class="fg"><label>Fournisseur *</label><select wire:model="supplier_id" class="fc @error('supplier_id') is-invalid @enderror"><option value="">— Sélectionner un fournisseur —</option>@foreach($suppliers as $s)<option value="{{ $s->id }}">{{ $s->name }} ({{ $s->code }})</option>@endforeach</select>@error('supplier_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div></div>
                            <div class="col-md-3"><div class="fg"><label>Date de commande *</label><input type="date" wire:model="order_date" class="fc @error('order_date') is-invalid @enderror">@error('order_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div></div>
                            <div class="col-md-2"><div class="fg"><label>Livraison prévue</label><input type="date" wire:model="expected_delivery_date" class="fc @error('expected_delivery_date') is-invalid @enderror">@error('expected_delivery_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div></div>
                            <div class="col-md-2"><div class="fg"><label>Statut</label><select wire:model="status" class="fc @error('status') is-invalid @enderror"><option value="draft">Brouillon</option><option value="sent">Envoyé</option><option value="partially_received">Réception partielle</option><option value="received">Reçu</option><option value="cancelled">Annulé</option></select>@error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div></div>
                        </div>
                    </div>

                    {{-- Ajout d'articles --}}
                    <div style="background:var(--bg-page);border-radius:11px;padding:16px 18px;margin-bottom:18px;border:1px solid var(--border-color);">
                        <div style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:12px;">
                            <i class="bi bi-box-seam me-1"></i> Ajouter un article
                        </div>
                        <div class="row g-2 align-items-end">
                            <div class="col-md-5">
                                <div class="fg" style="margin-bottom:0;">
                                    <label>Produit *</label>
                                    <select wire:model.live="selectedProduct" class="fc @error('selectedProduct') is-invalid @enderror">
                                        <option value="">— Sélectionner un produit —</option>
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}">
                                                {{ $p->name }} ({{ $p->reference }}) - Prix achat: {{ number_format($p->purchase_price, 0, ',', ' ') }} FCFA
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('selectedProduct')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="fg" style="margin-bottom:0;">
                                    <label>Quantité *</label>
                                    <input type="number" wire:model="quantityOrdered" class="fc @error('quantityOrdered') is-invalid @enderror" min="1" value="1">
                                    @error('quantityOrdered')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fg" style="margin-bottom:0;">
                                    <label>Prix(FCFA) se remplit automatiquement</label>
                                    <br>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" wire:click="addItem" class="btn-brand" style="width:100%;justify-content:center;">
                                    <i class="bi bi-plus-lg"></i> Ajouter
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Tableau des articles --}}
                    @if(count($items))
                    <div class="data-card" style="margin-bottom:18px;">
                        <table class="data-table">
                            <thead><tr><th>Produit</th><th>Qté commandée</th><th>Prix unitaire</th><th>Total ligne</th><th></th></tr></thead>
                            <tbody>
                                @foreach($items as $index => $item)
                                <tr>
                                    <td style="font-weight:600;">{{ $item['product_name'] ?? $products->find($item['product_id'])?->name }}</td>
                                    <td>{{ $item['quantity_ordered'] }}</td>
                                    <td>{{ number_format($item['unit_price'],0,',',' ') }} FCFA</td>
                                    <td style="font-weight:700;">{{ number_format($item['total'],0,',',' ') }} FCFA</td>
                                    <td><button type="button" wire:click="removeItem({{ $index }})" class="act-btn del"><i class="bi bi-trash3"></i></button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div style="padding:14px 18px;border-top:1px solid var(--border-color);display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
                            @php $subtotal = collect($items)->sum('total'); @endphp
                            <div style="display:flex;gap:32px;font-size:12.5px;"><span>Sous-total HT</span><span style="font-weight:600;">{{ number_format($subtotal,0,',',' ') }} FCFA</span></div>
                            <div style="display:flex;gap:32px;font-size:12.5px;"><span>TVA (19.25%)</span><span style="font-weight:600;">{{ number_format($subtotal*0.1925,0,',',' ') }} FCFA</span></div>
                            <div style="display:flex;gap:32px;font-size:14px;font-weight:800;padding-top:6px;border-top:1px solid var(--border-color);"><span>Total TTC</span><span style="color:var(--brand-green);">{{ number_format($subtotal*1.1925,0,',',' ') }} FCFA</span></div>
                        </div>
                    </div>
                    @else
                    <div style="background:rgba(240,125,0,0.07);border:1px solid rgba(240,125,0,0.2);border-radius:10px;padding:12px 16px;font-size:13px;color:var(--brand-orange);margin-bottom:18px;"><i class="bi bi-info-circle me-2"></i>Aucun article ajouté. Ajoutez des produits à cette commande.</div>
                    @endif

                    {{-- Notes --}}
                    <div class="fg"><label>Notes / Remarques</label><textarea wire:model="notes" rows="2" class="fc @error('notes') is-invalid @enderror"></textarea>@error('notes')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>

                    <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:8px;">
                        <button type="button" class="btn-ghost" wire:click="$set('showForm',false)">Annuler</button>
                        <button type="submit" class="btn-brand"><i class="bi bi-check2"></i> {{ $purchaseOrderId ? 'Enregistrer les modifications' : 'Créer le bon de commande' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

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
                    <div class="col-md-6"><div class="fg"><label>Date commande (début)</label><input type="date" wire:model.change="filter_date_from" class="fc"></div></div>
                    <div class="col-md-6"><div class="fg"><label>Date commande (fin)</label><input type="date" wire:model.change="filter_date_to" class="fc"></div></div>
                    <div class="col-md-6"><div class="fg"><label>Statut</label>
                        <select wire:model.change="filter_status" class="fc">
                            <option value="">Tous</option>
                            <option value="draft">Brouillon</option>
                            <option value="sent">Envoyé</option>
                            <option value="partially_received">Réception partielle</option>
                            <option value="received">Reçu</option>
                            <option value="cancelled">Annulé</option>
                        </select>
                    </div></div>
                    <div class="col-md-6"><div class="fg"><label>Fournisseur</label>
                        <select wire:model.change="filter_supplier_id" class="fc">
                            <option value="">-- Tous --</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->code }})</option>
                            @endforeach
                        </select>
                    </div></div>
                    <div class="col-md-6"><div class="fg"><label>Total min (FCFA)</label><input type="number" wire:model.change="filter_total_min" class="fc" placeholder="0"></div></div>
                    <div class="col-md-6"><div class="fg"><label>Total max (FCFA)</label><input type="number" wire:model.change="filter_total_max" class="fc" placeholder="Illimité"></div></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-ghost" wire:click="resetFilters" data-bs-dismiss="modal">Réinitialiser</button>
                <button wire:click="exportPdf" class="btn-brand" data-bs-dismiss="modal" style="background:linear-gradient(135deg, #dc2626, #ef4444);"><i class="bi bi-file-pdf"></i> Générer PDF</button>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        function initModals() {
            if (typeof bootstrap === 'undefined') {
                setTimeout(initModals, 500);
                return;
            }
            
            // Modal Export PDF
            const exportModalEl = document.getElementById('exportFiltersModal');
            if (exportModalEl && !exportModalEl._bsModal) {
                exportModalEl._bsModal = new bootstrap.Modal(exportModalEl);
            }
        }
        
        window.addEventListener('openFiltersModal', () => {
            const modalEl = document.getElementById('exportFiltersModal');
            if (modalEl && modalEl._bsModal) {
                modalEl._bsModal.show();
            } else if (modalEl && typeof bootstrap !== 'undefined') {
                modalEl._bsModal = new bootstrap.Modal(modalEl);
                modalEl._bsModal.show();
            }
        });
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initModals);
        } else {
            initModals();
        }
        
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