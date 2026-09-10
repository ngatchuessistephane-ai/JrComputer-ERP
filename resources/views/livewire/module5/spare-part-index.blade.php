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
        .act-btn.edit:hover {
            background: var(--brand-orange-xlight);
            color: var(--brand-orange);
            border-color: var(--brand-orange);
        }
        .act-btn.del:hover {
            background: rgba(220,38,38,0.08);
            color: #dc2626;
            border-color: #dc2626;
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
        .modal-jr .modal-content {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 18px;
        }
        .modal-jr .modal-header {
            background: linear-gradient(135deg, var(--brand-green), #22a352);
            border-bottom: none;
            padding: 18px 22px;
        }
        .modal-jr .modal-title {
            color: #fff;
            font-size: 15px;
            font-weight: 700;
        }
        .modal-jr .btn-close {
            filter: brightness(0) invert(1);
        }
        .modal-jr .modal-body {
            padding: 22px;
        }
        .modal-jr .modal-footer {
            padding: 14px 22px;
            border-top: 1px solid var(--border-color);
            background: var(--bg-page);
        }
        .fg {
            margin-bottom: 14px;
        }
        .fg label {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            display: block;
            margin-bottom: 5px;
        }
        .fc {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 9px;
            background: var(--bg-page);
            color: var(--text-primary);
            font-size: 13px;
            outline: none;
        }
        .fc:focus {
            border-color: var(--brand-green);
            box-shadow: 0 0 0 3px rgba(26,122,60,0.1);
        }
        .text-danger {
            color: #dc2626 !important;
        }
        .fw-bold {
            font-weight: 700;
        }
        .fw-semibold {
            font-weight: 600;
        }

        /* ✅ STOCK BADGES */
        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 12px;
        }
        .stock-ok {
            background: #d1fae5;
            color: #065f46;
        }
        .stock-low {
            background: #fef3c7;
            color: #d97706;
        }
        .stock-out {
            background: #fee2e2;
            color: #dc2626;
        }
        .stock-ok i { color: #065f46; }
        .stock-low i { color: #d97706; }
        .stock-out i { color: #dc2626; }
    </style>

    @if(session()->has('message'))
        <div class="flash-msg success">{{ session('message') }}</div>
    @endif

    <div class="module-toolbar">
        <h2><span class="module-icon green"><i class="bi bi-puzzle"></i></span> Pièces détachées - SAV</h2>
        <button wire:click="create" class="btn-brand"><i class="bi bi-plus-lg"></i> Nouvelle pièce</button>
    </div>

    <div class="search-wrap">
        <i class="bi bi-search s-icon"></i>
        <input type="text" wire:model.live.debounce.300ms="search" class="search-input" placeholder="Rechercher par nom ou référence...">
    </div>

    <div class="data-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Nom</th>
                        <th>Compatibilité</th>
                        <th>Stock</th>
                        <th>Prix vente</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parts as $p)
                    <tr>
                        <td><span class="fw-semibold">{{ $p->part_number }}</span></td>
                        <td>{{ $p->name }}</td>
                        <td>{{ Str::limit($p->compatibility, 40) }}</td>
                        <td>
                            @php
                                $stockClass = 'stock-ok';
                                $stockIcon = '';
                                if ($p->quantity_in_stock <= 0) {
                                    $stockClass = 'stock-out';
                                    $stockIcon = '<i class="bi bi-exclamation-circle" title="Rupture de stock"></i>';
                                } elseif ($p->quantity_in_stock <= $p->min_stock_alert) {
                                    $stockClass = 'stock-low';
                                    $stockIcon = '<i class="bi bi-exclamation-triangle" title="Stock bas"></i>';
                                }
                            @endphp
                            <span class="stock-badge {{ $stockClass }}">
                                {{ $p->quantity_in_stock }}
                                {!! $stockIcon !!}
                            </span>
                        </td>
                        <td>{{ number_format($p->selling_price, 0, ',', ' ') }} FCFA</td>
                        <td>
                            <button wire:click="edit({{ $p->id }})" class="act-btn edit" title="Modifier"><i class="bi bi-pencil"></i></button>
                            <button wire:click="delete({{ $p->id }})" wire:confirm="Êtes-vous sûr de vouloir supprimer cette pièce ?" class="act-btn del"><i class="bi bi-trash3"></i></button>
                        </td>
                    </tr>
                    @empty
                        <tr class="empty-state-row"><td colspan="6">Aucune pièce détachée. Créez votre première pièce.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($parts->hasPages())
            <div class="pagination-wrap">{{ $parts->links() }}</div>
        @endif
    </div>

    @if($showForm)
        <div class="modal show d-block modal-jr" style="background:rgba(0,0,0,0.55);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-{{ $partId ? 'pencil-square' : 'plus-circle' }} me-2"></i>{{ $partId ? 'Modifier' : 'Ajouter' }} une pièce</h5>
                        <button type="button" class="btn-close" wire:click="$set('showForm',false)"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save">
                            <div class="fg"><label>Référence *</label><input type="text" wire:model="part_number" class="fc"></div>
                            <div class="fg"><label>Nom *</label><input type="text" wire:model="name" class="fc"></div>
                            <div class="fg"><label>Compatibilité</label><textarea wire:model="compatibility" class="fc" rows="2"></textarea></div>
                            <div class="fg"><label>Prix achat (FCFA)</label><input type="number" step="0.01" wire:model="purchase_price" class="fc"></div>
                            <div class="fg"><label>Prix vente * (FCFA)</label><input type="number" step="0.01" wire:model="selling_price" class="fc"></div>
                            <div class="fg"><label>Stock initial</label><input type="number" wire:model="quantity_in_stock" class="fc"></div>
                            <div class="fg"><label>Seuil alerte</label><input type="number" wire:model="min_stock_alert" class="fc"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn-ghost" wire:click="$set('showForm',false)">Annuler</button>
                                <button type="submit" class="btn-brand">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <script>
    document.addEventListener('livewire:init', function () {
        Livewire.on('scroll-to-top', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>
</div>