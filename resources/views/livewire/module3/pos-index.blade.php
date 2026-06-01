<div style="zoom:0.90;">
<style>
    .module-toolbar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; }
    .module-toolbar h2 { font-family: 'Syne', sans-serif; font-size: 19px; font-weight: 800; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
    .module-icon { width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; color: white; font-size: 16px; }
    .module-icon.green { background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 4px 10px rgba(16,185,129,0.28); }
    .flash-msg { display: flex; align-items: center; gap: 10px; padding: 11px 16px; border-radius: 11px; font-size: 13px; font-weight: 500; margin-bottom: 16px; border: 1px solid; }
    .flash-msg.success { background: rgba(26,122,60,0.08); border-color: rgba(26,122,60,0.2); color: var(--brand-green); }
    .flash-msg.danger  { background: rgba(220,38,38,0.07); border-color: rgba(220,38,38,0.18); color: #dc2626; }
    .search-wrap { position: relative; margin-bottom: 16px; }
    .search-wrap .s-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); font-size: 15px; color: var(--text-muted); pointer-events: none; }
    .search-input { width: 100%; padding: 9px 13px 9px 38px; border: 1px solid var(--border-color); border-radius: 11px; background: var(--bg-card); color: var(--text-primary); font-size: 13px; outline: none; transition: 0.18s; }
    .data-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden; }
    .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 12px; }
    .product-tile { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 14px; padding: 12px 8px; text-align: center; cursor: pointer; transition: 0.18s; user-select: none; }
    .product-tile:hover { border-color: #10b981; box-shadow: 0 4px 14px rgba(16,185,129,0.15); transform: translateY(-2px); }
    .product-tile:active { transform: scale(0.97); }
    .product-tile .pt-icon { font-size: 28px; color: #10b981; margin-bottom: 8px; }
    .product-tile .pt-name { font-size: 12px; font-weight: 600; color: var(--text-primary); margin-bottom: 4px; line-height: 1.3; }
    .product-tile .pt-price { font-size: 12px; font-weight: 700; color: #10b981; }
    .product-tile .pt-stock { font-size: 10px; color: var(--text-muted); margin-top: 4px; }
    .product-tile.out-of-stock { opacity: 0.45; pointer-events: none; }
    .cart-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; }
    .cart-header { padding: 14px 18px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 8px; }
    .cart-header h5 { margin: 0; font-size: 15px; font-weight: 700; color: var(--text-primary); }
    .cart-badge { background: #10b981; color: white; border-radius: 20px; padding: 1px 8px; font-size: 11px; font-weight: 700; }
    .cart-body { overflow-y: auto; max-height: 320px; }
    .cart-table { width: 100%; border-collapse: collapse; }
    .cart-table th { background: var(--bg-page); padding: 8px 12px; font-size: 10px; text-transform: uppercase; color: var(--text-muted); position: sticky; top: 0; }
    .cart-table td { padding: 9px 12px; border-bottom: 1px solid var(--border-color); font-size: 12px; vertical-align: middle; }
    .cart-qty-input { width: 58px; padding: 4px 6px; border: 1px solid var(--border-color); border-radius: 7px; background: var(--bg-page); color: var(--text-primary); font-size: 12px; text-align: center; outline: none; }
    .cart-del-btn { width: 26px; height: 26px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid rgba(220,38,38,0.25); background: rgba(220,38,38,0.06); color: #dc2626; cursor: pointer; transition: 0.15s; }
    .cart-del-btn:hover { background: rgba(220,38,38,0.15); }
    .cart-empty { text-align: center; padding: 32px 16px; color: var(--text-muted); font-size: 13px; }
    .cart-totals { border-top: 1px solid var(--border-color); padding: 12px 18px; }
    .total-row { display: flex; justify-content: space-between; align-items: center; padding: 4px 0; font-size: 13px; color: var(--text-muted); }
    .total-row.ttc { font-size: 16px; font-weight: 800; color: #10b981; border-top: 1px dashed var(--border-color); padding-top: 10px; margin-top: 4px; }
    .cart-footer { padding: 14px 18px; border-top: 1px solid var(--border-color); display: flex; flex-direction: column; gap: 10px; }
    .fc { width: 100%; padding: 8px 11px; border: 1px solid var(--border-color); border-radius: 10px; background: var(--bg-card); color: var(--text-primary); font-size: 13px; outline: none; transition: 0.18s; }
    .fc:focus { border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,0.1); }
    label { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.4px; display: block; margin-bottom: 4px; }
    .checkout-btn { width: 100%; padding: 11px; border-radius: 12px; background: linear-gradient(135deg, #10b981, #059669); color: white; font-weight: 700; font-size: 14px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: 0.18s; }
    .checkout-btn:hover:not(:disabled) { opacity: 0.9; transform: translateY(-1px); }
    .checkout-btn:disabled { opacity: 0.45; cursor: not-allowed; transform: none; }
    .clear-cart-btn { width: 100%; padding: 7px; border-radius: 10px; border: 1px solid var(--border-color); background: transparent; color: var(--text-muted); font-size: 12px; cursor: pointer; transition: 0.18s; }
    .clear-cart-btn:hover { background: rgba(220,38,38,0.08); color: #dc2626; border-color: rgba(220,38,38,0.3); }
</style>

@if(session()->has('message'))<div class="flash-msg success"><i class="bi bi-check-circle-fill"></i>{{ session('message') }}</div>@endif
@if(session()->has('error'))<div class="flash-msg danger"><i class="bi bi-exclamation-circle-fill"></i>{{ session('error') }}</div>@endif

<div class="module-toolbar">
    <h2><span class="module-icon green"><i class="bi bi-cart3"></i></span> Point de vente (POS)</h2>
    <div style="font-size:12px; color:var(--text-muted);">{{ now()->format('d/m/Y H:i') }}</div>
</div>

<div class="row g-3">

    <div class="col-lg-7">
        <div class="search-wrap">
            <!-- <i class="bi bi-upc-scan s-icon"></i> -->
             <i class="bi bi-search s-icon"></i>
            <input type="text" wire:model.live.debounce.300ms="search" class="search-input" placeholder="Rechercher par nom / référence…">
        </div>

        @if($products->isEmpty())
            <div class="data-card" style="padding:40px;text-align:center;color:var(--text-muted);">
                <i class="bi bi-box-seam" style="font-size:32px;opacity:0.4;"></i>
                <p class="mt-2 mb-0">Aucun produit trouvé.</p>
            </div>
        @else
            <div class="products-grid">
                @foreach($products as $p)
                <div class="product-tile {{ $p->quantity <= 0 ? 'out-of-stock' : '' }}"
                     wire:click="addToCart({{ $p->id }})"
                     title="{{ $p->quantity <= 0 ? 'Rupture de stock' : 'Ajouter au panier' }}">
                    <div class="pt-icon"><i class="bi bi-box-seam"></i></div>
                    <div class="pt-name">{{ $p->name }}</div>
                    <div class="pt-price">{{ number_format($p->selling_price, 0, ',', ' ') }} FCFA</div>
                    <div class="pt-stock">
                        @if($p->quantity <= 0) <span style="color:#dc2626;">Rupture</span>
                        @elseif($p->quantity <= 5) <span style="color:#f59e0b;">{{ $p->quantity }} restants</span>
                        @else Stock: {{ $p->quantity }}
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="col-lg-5">
        <div class="cart-card">
            <div class="cart-header">
                <i class="bi bi-cart3" style="color:#10b981;font-size:18px;"></i>
                <h5>Panier</h5>
                @if(count($cart) > 0) <span class="cart-badge">{{ count($cart) }}</span> @endif
            </div>

            <div class="cart-body">
                @if(count($cart) === 0)
                    <div class="cart-empty">
                        <i class="bi bi-cart-x" style="font-size:28px;opacity:0.35;"></i>
                        <p class="mt-2 mb-0">Panier vide. Cliquez sur un produit pour l'ajouter.</p>
                    </div>
                @else
                    <table class="cart-table">
                        <thead><tr><th>Produit</th><th>Qté</th><th>Total</th><th></th></tr></thead>
                        <tbody>
                            @foreach($cart as $index => $item)
                            <tr>
                                <td>
                                    <div style="font-weight:600;font-size:12px;">{{ $item['name'] }}</div>
                                    <div style="font-size:10px;color:var(--text-muted);">{{ number_format($item['unit_price'],0,',',' ') }} FCFA/u</div>
                                </td>
                                <td>
                                    <input type="number" min="1" wire:change="updateQuantity({{ $index }}, $event.target.value)" value="{{ $item['quantity'] }}" class="cart-qty-input">
                                </td>
                                <td style="font-weight:600;">{{ number_format($item['total'],0,',',' ') }} FCFA</td>
                                <td>
                                    <button wire:click="removeFromCart({{ $index }})" class="cart-del-btn"><i class="bi bi-trash3" style="font-size:11px;"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            @php
                $totalHT = collect($cart)->sum('total');
                $tva = $totalHT * 0.1925;
                $totalTTC = $totalHT + $tva;
            @endphp

            <div class="cart-totals">
                <div class="total-row"><span>Total HT</span><span>{{ number_format($totalHT, 0, ',', ' ') }} FCFA</span></div>
                <div class="total-row"><span>TVA (19,25%)</span><span>{{ number_format($tva, 0, ',', ' ') }} FCFA</span></div>
                <div class="total-row ttc"><span>Total TTC</span><span>{{ number_format($totalTTC, 0, ',', ' ') }} FCFA</span></div>
            </div>

            <div class="cart-footer">
                <div>
                    <label>Client (optionnel)</label>
                    <select wire:model="customer_id" class="fc">
                        <option value="">— Client comptant —</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}{{ $c->phone ? ' · '.$c->phone : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Mode de paiement</label>
                    <select wire:model="payment_method" class="fc">
                        <option value="cash">💵 Espèces</option>
                        <option value="mtn_momo">📱 MTN Mobile Money</option>
                        <option value="orange_money">🟠 Orange Money</option>
                        <option value="bank_transfer">🏦 Virement bancaire</option>
                    </select>
                </div>
                <button wire:click="checkout" class="checkout-btn" @if(count($cart) === 0) disabled @endif>
                    <i class="bi bi-check-circle-fill"></i> Valider la vente · {{ number_format($totalTTC, 0, ',', ' ') }} FCFA
                </button>
                @if(count($cart) > 0)
                    <button wire:click="clearCart" onclick="return confirm('Vider le panier ?')" class="clear-cart-btn">
                        <i class="bi bi-x-circle"></i> Vider le panier
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('cartUpdated', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
        Livewire.on('saleCompleted', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    });
</script>
</div>