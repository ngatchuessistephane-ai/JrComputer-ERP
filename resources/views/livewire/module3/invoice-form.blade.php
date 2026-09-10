<div style="zoom:0.90;">
<style>
    .module-toolbar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; }
    .module-toolbar h2 { font-family: 'Syne', sans-serif; font-size: 19px; font-weight: 800; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
    .module-icon { width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; color: white; font-size: 16px; }
    .module-icon.blue { background: linear-gradient(135deg, var(--brand-green), #22a352); box-shadow: 0 4px 10px rgba(26,122,60,0.28);}
    .flash-msg { display: flex; align-items: center; gap: 10px; padding: 11px 16px; border-radius: 11px; font-size: 13px; font-weight: 500; margin-bottom: 16px; border: 1px solid; }
    .flash-msg.success { background: rgba(220, 220, 220, 0.08); border-color: rgba(26,122,60,0.2); color: var(--brand-green); }
    .flash-msg.danger  { background: rgba(220,38,38,0.07); border-color: rgba(220,38,38,0.18); color: #dc2626; }

    /* Sections / blocs */
    .form-section { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden; margin-bottom: 18px; }
    .form-section-header { display: flex; align-items: center; gap: 9px; padding: 14px 20px; border-bottom: 1px solid var(--border-color); }
    .form-section-header .sec-icon { width: 28px; height: 28px; border-radius: 7px; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; color: white; }
    .form-section-header .sec-icon.blue   { background: linear-gradient(135deg, var(--brand-green), #22a352); }
    .form-section-header .sec-icon.green  { background: linear-gradient(135deg, var(--brand-green), #22a352); }
    .form-section-header .sec-icon.gray   { background: linear-gradient(135deg,#6b7280,#4b5563); }
    .form-section-header h6 { margin: 0; font-size: 13.5px; font-weight: 700; color: var(--text-primary); }
    .form-section-body { padding: 18px 20px; }

    /* Champs */
    .fg { margin-bottom: 14px; }
    .fg label { font-size: 11.5px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.4px; display: block; margin-bottom: 5px; }
    .fc { width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 9px; background: var(--bg-card); color: var(--text-primary); font-size: 13px; outline: none; transition: 0.18s; }
    .fc:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
    .fc:disabled { opacity: 0.6; cursor: not-allowed; background: var(--bg-page); }
    .field-error { font-size: 11.5px; color: #dc2626; margin-top: 4px; display: flex; align-items: center; gap: 4px; }

    /* Tableau articles */
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: var(--bg-page); padding: 10px 14px; font-size: 10.5px; text-transform: uppercase; color: var(--text-muted); text-align: left; }
    .data-table td { padding: 11px 14px; border-bottom: 1px solid var(--border-color); font-size: 13px; vertical-align: middle; }
    .act-btn.del { width: 28px; height: 28px; border-radius: 7px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid rgba(220,38,38,0.25); background: rgba(220,38,38,0.06); color: #dc2626; cursor: pointer; transition: 0.15s; }
    .act-btn.del:hover { background: rgba(220,38,38,0.15); }

    /* Ajout article */
    .add-item-bar { display: flex; gap: 8px; flex-wrap: wrap; align-items: flex-end; padding: 14px; background: var(--bg-page); border-radius: 10px; margin-bottom: 16px; }
    .add-item-bar .fg { margin-bottom: 0; flex: 1; min-width: 130px; }

    /* Totaux */
    .totals-box { background: var(--bg-page); border-radius: 10px; padding: 14px 18px; min-width: 260px; }
    .totals-row { display: flex; justify-content: space-between; align-items: center; padding: 5px 0; font-size: 13px; color: var(--text-muted); }
    .totals-row.ttc { font-size: 16px; font-weight: 800; color: #3b82f6; border-top: 1px dashed var(--border-color); padding-top: 10px; margin-top: 6px; }

    /* État vide */
    .empty-items { text-align: center; padding: 30px 20px; color: var(--text-muted); font-size: 13px; }

    /* Footer boutons */
    .form-footer { display: flex; justify-content: flex-end; gap: 8px; padding-top: 8px; }

    /* Badge statut */
    .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 5px; }

    /* ✅ Style pour l'auto-remplissage */
    .auto-fill-badge {
        display: inline-block;
        background: rgba(26,122,60,0.1);
        color: var(--brand-green);
        font-size: 9px;
        font-weight: 700;
        padding: 1px 8px;
        border-radius: 4px;
        margin-left: 4px;
    } 
    .btn-brand:hover{
    cursor: pointer;
    }
</style>

@if(session()->has('message'))<div class="flash-msg success"><i class="bi bi-check-circle-fill"></i>{{ session('message') }}</div>@endif
@if(session()->has('error'))<div class="flash-msg danger"><i class="bi bi-exclamation-circle-fill"></i>{{ session('error') }}</div>@endif

<div class="module-toolbar">
    <h2>
        <span class="module-icon blue"><i class="bi bi-receipt"></i></span>
        {{ $invoiceId ? 'Modifier la facture' : 'Nouvelle facture' }}
    </h2>
    <a href="{{ route('module3.invoices.index') }}" class="btn-ghost" style="font-size:13px;">
        <i class="bi bi-arrow-left me-1"></i> Retour aux factures
    </a>
</div>

<form wire:submit.prevent="save">

    {{-- ===== BLOC 1 : Informations générales ===== --}}
    <div class="form-section">
        <div class="form-section-header">
            <span class="sec-icon blue"><i class="bi bi-info-circle"></i></span>
            <h6>Informations générales</h6>
        </div>
        <div class="form-section-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="fg">
                        <label>Client <span style="color:#dc2626;">*</span></label>
                        <select wire:model="customer_id" class="fc">
                            <option value="">— Sélectionner un client —</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('customer_id')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="fg">
                        <label>Date de facture <span style="color:#dc2626;">*</span></label>
                        <input type="date" wire:model="date" class="fc">
                        @error('date')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="fg">
                        <label>Date d'échéance <span style="color:#dc2626;">*</span></label>
                        <input type="date" wire:model="due_date" class="fc">
                        @error('due_date')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="fg">
                        <label>Statut</label>
                        <select wire:model="status" class="fc">
                            <option value="draft">📄 Brouillon</option>
                            <option value="sent">📤 Envoyée</option>
                            <option value="paid">✅ Payée</option>
                            <option value="partial">🔶 Partielle</option>
                            <option value="overdue">🔴 En retard</option>
                            <option value="cancelled">⛔ Annulée</option>
                        </select>
                        @error('status')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== BLOC 2 : Articles ===== --}}
    <div class="form-section">
        <div class="form-section-header">
            <span class="sec-icon green"><i class="bi bi-box-seam"></i></span>
            <h6>Articles facturés</h6>
            @if(count($items) > 0)
                <span style="margin-left:auto;font-size:11px;color:var(--text-muted);">{{ count($items) }} article(s)</span>
            @endif
        </div>
        <div class="form-section-body">

            {{-- Barre d'ajout avec auto-remplissage --}}
            <div class="add-item-bar">
                <div class="fg" style="flex:2;min-width:180px;">
                    <label>Produit <span style="color:#dc2626;">*</span></label>
                    <select wire:model.live="selectedProduct" class="fc" id="productSelect">
                        <option value="">— Sélectionner un produit —</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" data-price="{{ $p->selling_price }}">
                                {{ $p->name }} · {{ number_format($p->selling_price,0,',',' ') }} FCFA
                            </option>
                        @endforeach
                    </select>
                    @error('selectedProduct')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                </div>
                <div class="fg" style="max-width:150px;">
                    <label>Prix unitaire (FCFA) <span style="color:#dc2626;">*</span></label>
                    <input type="number" step="0.01" wire:model="unitPrice" class="fc" placeholder="0" id="unitPriceInput">
                    @error('unitPrice')<div class="field-error">{{ $message }}</div>@enderror
                    <small class="auto-fill-badge" id="autoFillBadge" style="display:none;">⏺ Auto-rempli</small>
                </div>
                <div class="fg" style="max-width:90px;">
                    <label>Quantité <span style="color:#dc2626;">*</span></label>
                    <input type="number" min="1" wire:model="quantity" class="fc" placeholder="0" value="1">
                    @error('quantity')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div style="padding-bottom:1px;">
                    <button type="button" wire:click="addItem" class="btn-brand" style="white-space:nowrap;">
                        <i class="bi bi-plus-lg me-1"></i> Ajouter
                    </button>
                </div>
            </div>

            {{-- Liste des articles --}}
            @if(count($items))
                <div class="table-responsive" style="border-radius:10px;overflow:hidden;border:1px solid var(--border-color);">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Produit</th>
                                <th style="text-align:right;">Quantité</th>
                                <th style="text-align:right;">Prix unitaire</th>
                                <th style="text-align:right;">Total HT</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $idx => $item)
                            <tr>
                                <td style="color:var(--text-muted);font-size:11px;">{{ $idx + 1 }}</td>
                                <td><span class="fw-semibold">{{ $item['product_name'] }}</span></td>
                                <td style="text-align:right;">{{ $item['quantity'] }}</td>
                                <td style="text-align:right;">{{ number_format($item['unit_price'],0,',',' ') }} FCFA</td>
                                <td style="text-align:right;font-weight:700;">{{ number_format($item['total'],0,',',' ') }} FCFA</td>
                                <td style="text-align:center;">
                                    <button type="button" wire:click="removeItem({{ $idx }})" class="act-btn del" title="Retirer">
                                        <i class="bi bi-trash3" style="font-size:11px;"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @php
                    $ht  = collect($items)->sum('total');
                    $tva = $ht * 0.1925;
                    $ttc = $ht + $tva;
                @endphp
                <div class="d-flex justify-content-end mt-3">
                    <div class="totals-box">
                        <div class="totals-row"><span>Sous-total HT</span><span>{{ number_format($ht,0,',',' ') }} FCFA</span></div>
                        <div class="totals-row"><span>TVA (19,25%)</span><span>{{ number_format($tva,0,',',' ') }} FCFA</span></div>
                        <div class="totals-row ttc"><span>Total TTC</span><span>{{ number_format($ttc,0,',',' ') }} FCFA</span></div>
                    </div>
                </div>
            @else
                <div class="empty-items">
                    <i class="bi bi-inbox" style="font-size:30px;opacity:0.35;display:block;margin-bottom:8px;"></i>
                    Aucun article ajouté. Sélectionnez un produit et cliquez sur <strong>Ajouter</strong>.
                </div>
            @endif
        </div>
    </div>

    {{-- ===== BLOC 3 : Notes ===== --}}
    <div class="form-section">
        <div class="form-section-header">
            <span class="sec-icon gray"><i class="bi bi-chat-left-text"></i></span>
            <h6>Notes & remarques</h6>
        </div>
        <div class="form-section-body">
            <div class="fg" style="margin-bottom:0;">
                <textarea wire:model="notes" rows="3" class="fc" placeholder="Informations complémentaires, conditions de paiement, message au client…"></textarea>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="form-footer">
        <a href="{{ route('module3.invoices.index') }}" class="btn-ghost">Annuler</a>
        <button type="submit" class="btn-brand">
            <i class="bi bi-{{ $invoiceId ? 'floppy' : 'plus-circle' }} me-1"></i>
            {{ $invoiceId ? 'Mettre à jour' : 'Créer la facture' }}
        </button>
    </div>

</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('productSelect');
        const unitPriceInput = document.getElementById('unitPriceInput');
        const autoFillBadge = document.getElementById('autoFillBadge');

        if (productSelect && unitPriceInput) {
            productSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    const price = selectedOption.getAttribute('data-price');
                    if (price) {
                        unitPriceInput.value = parseInt(price);
                        // Déclencher l'événement input pour Livewire
                        unitPriceInput.dispatchEvent(new Event('input'));
                        if (autoFillBadge) {
                            autoFillBadge.style.display = 'inline-block';
                            setTimeout(() => {
                                autoFillBadge.style.display = 'none';
                            }, 3000);
                        }
                    }
                } else {
                    unitPriceInput.value = '';
                    if (autoFillBadge) {
                        autoFillBadge.style.display = 'none';
                    }
                }
            });
        }
    });
</script>

@push('scripts')
<script>
    // Écouter les événements Livewire pour rafraîchir le badge
    document.addEventListener('livewire:update', function() {
        const productSelect = document.getElementById('productSelect');
        const unitPriceInput = document.getElementById('unitPriceInput');
        const autoFillBadge = document.getElementById('autoFillBadge');
        
        if (productSelect && unitPriceInput && productSelect.value) {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const price = selectedOption.getAttribute('data-price');
                if (price && unitPriceInput.value == price) {
                    if (autoFillBadge) {
                        autoFillBadge.style.display = 'inline-block';
                        setTimeout(() => {
                            autoFillBadge.style.display = 'none';
                        }, 2000);
                    }
                }
            }
        }
    });
</script>
<script>
    document.addEventListener('livewire:init', function () {
        Livewire.on('scroll-to-top', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>
@endpush
</div>