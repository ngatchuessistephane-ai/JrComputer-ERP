<div class="pos-wrap" style="zoom:0.90; padding: 0 8px; max-width: 100%;">
<style>
/* ═══════════════════════════════════════════════════════════════
   JR COMPUTER POS — DESIGN STARTUP PREMIUM v3
   Variables héritées de app.blade.php
═══════════════════════════════════════════════════════════════ */

/* ─── RESET SCOPED ─── */
.pos-wrap *, .pos-wrap *::before, .pos-wrap *::after { box-sizing: border-box; }
.pos-wrap { 
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif; 
    color: var(--text-primary);
    padding: 0 8px;
    max-width: 100%;
}

/* ─── TOOLBAR ─── */
.pos-topbar {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 10px; margin-bottom: 14px;
}
.pos-topbar-left { display: flex; align-items: center; gap: 10px; }
.pos-topbar h2 {
    margin: 0; font-size: 18px; font-weight: 800;
    color: var(--text-primary); letter-spacing: -0.4px;
    display: flex; align-items: center; gap: 8px;
}
.pos-hicon {
    width: 34px; height: 34px; border-radius: 10px; flex-shrink: 0;
    display: inline-flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, var(--brand-green) 0%, var(--brand-green-light) 100%);
    color: #fff; font-size: 16px;
    box-shadow: 0 4px 12px rgba(26,122,60,.28);
}
.pos-meta {
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
}
.pos-meta-chip {
    display: flex; align-items: center; gap: 4px;
    font-size: 10.5px; font-weight: 600; color: var(--text-muted);
    background: var(--bg-card); border: 1px solid var(--border-color);
    padding: 3px 10px; border-radius: 16px;
}
.pos-meta-chip i { font-size: 11px; }
.pos-live-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: #22c55e; display: inline-block;
    animation: pulse-live 1.8s ease-in-out infinite;
}
@keyframes pulse-live { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(.75)} }

/* ─── FLASH ─── */
.pos-flash {
    display: flex; align-items: center; gap: 8px; padding: 10px 14px;
    border-radius: 10px; font-size: 12px; font-weight: 500;
    margin-bottom: 12px; border: 1px solid;
    animation: fadeSlide .3s ease;
}
.pos-flash.success { background:rgba(26,122,60,.08); border-color:rgba(26,122,60,.2); color:var(--brand-green); }
.pos-flash.danger  { background:rgba(220,38,38,.07); border-color:rgba(220,38,38,.2); color:#dc2626; }
@keyframes fadeSlide { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }

/* ─── LAYOUT PRINCIPAL ─── */
.pos-layout {
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 14px;
    align-items: start;
}
@media (max-width: 1140px) { .pos-layout { grid-template-columns: 1fr; } }

/* ══════════════════════════════════════
   COLONNE GAUCHE — CATALOGUE
══════════════════════════════════════ */
.pos-catalog { display: flex; flex-direction: column; gap: 10px; }

/* Barre de recherche premium */
.pos-searchbar {
    position: relative;
    background: var(--bg-card);
    border: 1.5px solid var(--border-color);
    border-radius: 12px;
    display: flex; align-items: center;
    transition: border-color .18s, box-shadow .18s;
    overflow: hidden;
}
.pos-searchbar:focus-within {
    border-color: var(--brand-green);
    box-shadow: 0 0 0 3px rgba(26,122,60,.1);
}
.pos-searchbar .s-icon {
    padding: 0 4px 0 14px; font-size: 15px;
    color: var(--text-muted); flex-shrink: 0;
    pointer-events: none;
}
.pos-search-input {
    flex: 1; padding: 10px 12px 10px 4px;
    border: none; outline: none; background: transparent;
    color: var(--text-primary); font-size: 13px; font-family: inherit;
}
.pos-search-input::placeholder { color: var(--text-muted); }
.pos-search-kbd {
    margin-right: 10px; padding: 1px 6px;
    background: var(--bg-page); border: 1px solid var(--border-color);
    border-radius: 5px; font-size: 9px; font-weight: 700;
    color: var(--text-muted); letter-spacing: .04em;
}

/* Scroll produits */
.pos-products-scroll {
    max-height: calc(100vh - 190px);
    overflow-y: auto; padding-right: 2px;
    scrollbar-width: thin; scrollbar-color: var(--border-color) transparent;
}
.pos-products-scroll::-webkit-scrollbar { width: 4px; }
.pos-products-scroll::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 99px; }

/* Grille - Plus large */
.pos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 10px;
}

/* Tuile produit — plus compacte */
.pos-tile {
    background: var(--bg-card);
    border: 1.5px solid var(--border-color);
    border-radius: 14px;
    padding: 14px 10px 12px;
    text-align: center;
    cursor: pointer;
    transition: border-color .2s, box-shadow .2s, transform .18s;
    user-select: none;
    position: relative; overflow: hidden;
    display: flex; flex-direction: column; align-items: center; gap: 0;
}
.pos-tile::after {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(160deg, rgba(26,122,60,.04) 0%, transparent 60%);
    opacity: 0; transition: opacity .2s;
}
.pos-tile:hover {
    border-color: var(--brand-green);
    box-shadow: 0 6px 24px rgba(26,122,60,.12);
    transform: translateY(-3px);
}
.pos-tile:hover::after { opacity: 1; }
.pos-tile:active { transform: translateY(-1px) scale(.98); }
.pos-tile.out-of-stock { opacity: .4; pointer-events: none; filter: grayscale(.5); }

/* Bouton "+" flottant - plus petit */
.pos-tile-plus {
    position: absolute; top: 8px; right: 8px;
    width: 20px; height: 20px; border-radius: 6px;
    background: var(--brand-green); color: white;
    font-size: 14px; font-weight: 700; line-height: 1;
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transform: scale(.6) rotate(-15deg);
    transition: opacity .18s, transform .2s cubic-bezier(.34,1.56,.64,1);
    z-index: 1;
}
.pos-tile:hover .pos-tile-plus { opacity: 1; transform: scale(1) rotate(0deg); }

/* Icône produit - plus petite */
.pos-tile-icon {
    width: 44px; height: 44px; border-radius: 12px; margin-bottom: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: var(--brand-green);
    background: var(--brand-green-xlight);
    transition: background .2s, color .2s, transform .2s;
    flex-shrink: 0;
}
.pos-tile:hover .pos-tile-icon {
    background: var(--brand-green);
    color: #fff;
    transform: scale(1.05) rotate(-4deg);
}

.pos-tile-name {
    font-size: 11px; font-weight: 700; color: var(--text-primary);
    line-height: 1.25; margin-bottom: 5px;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    overflow: hidden; min-height: 26px;
}
.pos-tile-price {
    font-size: 13px; font-weight: 800; color: var(--brand-green);
    margin-bottom: 4px; letter-spacing: -.3px;
}
.pos-tile-price small { font-size: 9px; font-weight: 600; letter-spacing: 0; }

/* Badge stock - plus compact */
.pos-stock-badge {
    font-size: 9px; font-weight: 700; padding: 2px 7px;
    border-radius: 16px; display: inline-flex; align-items: center; gap: 3px;
}
.stkOk  { background: rgba(26,122,60,.1); color: var(--brand-green); }
.stkLow { background: rgba(240,125,0,.1); color: var(--brand-orange); }
.stkOut { background: rgba(220,38,38,.1); color: #dc2626; }

/* Empty state */
.pos-empty-state {
    grid-column: 1/-1;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 8px; padding: 40px 16px;
    background: var(--bg-card); border: 1.5px dashed var(--border-color);
    border-radius: 14px;
}
.pos-empty-state .e-icon {
    width: 48px; height: 48px; border-radius: 14px;
    background: var(--bg-page); display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: var(--text-muted);
}
.pos-empty-state p { margin: 0; font-size: 12px; font-weight: 500; color: var(--text-muted); }

/* ══════════════════════════════════════
   COLONNE DROITE — PANIER (plus large)
══════════════════════════════════════ */
.pos-cart {
    background: var(--bg-card);
    border: 1.5px solid var(--border-color);
    border-radius: 16px;
    box-shadow: var(--shadow-card);
    overflow: hidden;
    display: flex; flex-direction: column;
    position: sticky; top: 60px;
}

/* En-tête panier - plus compact */
.pos-cart-head {
    padding: 12px 16px;
    background: var(--bg-page);
    border-bottom: 1px solid var(--border-color);
    display: flex; align-items: center; gap: 8px;
    position: relative;
}
.pos-cart-head::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, var(--brand-green), var(--brand-green-light) 60%, var(--brand-orange));
}
.pos-cart-head-title {
    flex: 1;
}
.pos-cart-head-title h5 {
    margin: 0 0 1px; font-size: 13px; font-weight: 800; color: var(--text-primary);
}
.pos-cart-head-title span {
    font-size: 10px; font-weight: 500; color: var(--text-muted);
}
.pos-cart-icon {
    width: 30px; height: 30px; border-radius: 9px;
    background: var(--brand-green-xlight); color: var(--brand-green);
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; flex-shrink: 0;
}
.pos-cart-count {
    background: var(--brand-green); color: #fff;
    border-radius: 6px; padding: 2px 8px;
    font-size: 10px; font-weight: 800;
    transition: transform .25s cubic-bezier(.34,1.56,.64,1);
}

/* Corps panier - plus grand */
.pos-cart-body {
    overflow-y: auto; max-height: 380px;
    scrollbar-width: thin; scrollbar-color: var(--border-color) transparent;
}
.pos-cart-body::-webkit-scrollbar { width: 4px; }
.pos-cart-body::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 99px; }

/* État vide */
.pos-cart-empty {
    display: flex; flex-direction: column; align-items: center;
    justify-content: center; gap: 6px;
    padding: 30px 16px; text-align: center;
}
.pos-cart-empty .e-ring {
    width: 44px; height: 44px; border-radius: 50%;
    border: 2px dashed var(--border-color);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: var(--text-muted);
}
.pos-cart-empty p { margin: 0; font-size: 12px; color: var(--text-muted); font-weight: 500; }

/* Lignes du panier - plus compactes */
.pos-cart-line {
    display: grid;
    grid-template-columns: 1fr auto auto auto;
    align-items: center; gap: 8px;
    padding: 8px 14px;
    border-bottom: 1px solid var(--border-color);
    transition: background .12s;
    animation: lineIn .22s ease;
}
.pos-cart-line:hover { background: var(--bg-hover); }
.pos-cart-line:last-child { border-bottom: none; }
@keyframes lineIn {
    from { opacity: 0; transform: translateX(-6px); }
    to   { opacity: 1; transform: translateX(0); }
}
.cart-line-name { font-size: 11.5px; font-weight: 700; color: var(--text-primary); margin-bottom: 1px; }
.cart-line-uprice { font-size: 10px; color: var(--text-muted); font-weight: 500; }

/* Stepper quantité - plus compact */
.qty-stepper {
    display: flex; align-items: center;
    border: 1px solid var(--border-color); border-radius: 6px;
    overflow: hidden; flex-shrink: 0;
}
.qty-btn {
    width: 22px; height: 22px; border: none; background: var(--bg-page);
    color: var(--text-secondary); font-size: 14px; font-weight: 700;
    cursor: pointer; transition: background .12s, color .12s;
    display: flex; align-items: center; justify-content: center;
    font-family: inherit; line-height: 1;
}
.qty-btn:hover { background: var(--brand-green); color: #fff; }
.qty-val {
    width: 30px; text-align: center; border: none; outline: none;
    font-size: 11.5px; font-weight: 800; color: var(--text-primary);
    background: var(--bg-card); font-family: inherit; padding: 0;
}
.cart-line-total {
    font-size: 12px; font-weight: 800; color: var(--text-primary);
    white-space: nowrap; text-align: right; min-width: 70px;
}
.cart-del-btn {
    width: 22px; height: 22px; border-radius: 6px; border: none;
    background: transparent; color: var(--text-muted);
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    font-size: 12px; transition: background .12s, color .12s; flex-shrink: 0;
}
.cart-del-btn:hover { background: rgba(220,38,38,.1); color: #dc2626; }

/* Récapitulatif - plus compact */
.pos-totals {
    padding: 10px 14px;
    background: var(--bg-page);
    border-top: 1px solid var(--border-color);
}
.pos-total-line {
    display: flex; justify-content: space-between; align-items: center;
    padding: 2px 0; font-size: 12px; color: var(--text-muted);
}
.pos-total-line .val { font-weight: 600; color: var(--text-secondary); }
.pos-total-ttc {
    display: flex; justify-content: space-between; align-items: center;
    padding: 8px 12px; border-radius: 10px; margin-top: 6px;
    background: var(--brand-green-xlight);
}
.pos-total-ttc .lbl { font-size: 12px; font-weight: 700; color: var(--brand-green); }
.pos-total-ttc .amount { font-size: 18px; font-weight: 900; color: var(--brand-green); letter-spacing: -.5px; }
.pos-total-ttc .amount small { font-size: 11px; font-weight: 600; margin-left: 2px; }

/* Pied panier - plus compact */
.pos-cart-footer {
    padding: 12px 14px;
    border-top: 1px solid var(--border-color);
    display: flex; flex-direction: column; gap: 8px;
}
.pos-field { display: flex; flex-direction: column; gap: 4px; }
.pos-field-label {
    font-size: 10px; font-weight: 700; color: var(--text-muted);
    text-transform: uppercase; letter-spacing: .4px;
    display: flex; align-items: center; gap: 4px;
}
.pos-field-label i { font-size: 11px; }
.pos-select {
    width: 100%; padding: 7px 10px;
    border: 1.5px solid var(--border-color); border-radius: 8px;
    background: var(--bg-card); color: var(--text-primary);
    font-size: 12px; font-family: inherit; outline: none;
    transition: border-color .18s, box-shadow .18s;
    -webkit-appearance: none; appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath fill='%2394a89e' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center;
    padding-right: 28px;
}
.pos-select:focus {
    border-color: var(--brand-green);
    box-shadow: 0 0 0 3px rgba(26,122,60,.1);
}

/* Bouton paiement — plus compact */
.pos-pay-btn {
    width: 100%; padding: 11px 14px; border-radius: 12px; border: none;
    background: linear-gradient(135deg, var(--brand-green) 0%, var(--brand-green-light) 100%);
    color: #fff; font-size: 13px; font-weight: 800; font-family: inherit;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    gap: 8px; letter-spacing: -.2px;
    box-shadow: 0 4px 16px rgba(26,122,60,.3), 0 1px 4px rgba(26,122,60,.15);
    transition: transform .18s, box-shadow .18s, opacity .18s;
    position: relative; overflow: hidden;
}
.pos-pay-btn::before {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,.1), transparent 60%);
}
.pos-pay-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(26,122,60,.38), 0 2px 6px rgba(26,122,60,.15);
}
.pos-pay-btn:active:not(:disabled) { transform: translateY(0); }
.pos-pay-btn:disabled { opacity: .4; cursor: not-allowed; box-shadow: none; }
.pos-pay-btn .pay-icon {
    width: 24px; height: 24px; border-radius: 6px;
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; flex-shrink: 0;
}
.pos-pay-btn .pay-amount {
    background: rgba(255,255,255,.15); border-radius: 6px;
    padding: 1px 8px; font-size: 12px; font-weight: 800; margin-left: auto;
}

/* Bouton vider - plus compact */
.pos-clear-btn {
    width: 100%; padding: 6px; border-radius: 8px;
    border: 1px solid var(--border-color); background: transparent;
    color: var(--text-muted); font-size: 11px; font-family: inherit;
    cursor: pointer; transition: all .18s;
    display: flex; align-items: center; justify-content: center; gap: 5px;
}
.pos-clear-btn:hover {
    border-color: rgba(220,38,38,.3);
    background: rgba(220,38,38,.07); color: #dc2626;
}

[data-theme="dark"] .pos-total-ttc { background: rgba(26,122,60,.15); }
</style>

{{-- ─── FLASH ─── --}}
@if(session()->has('message'))
    <div class="pos-flash success"><i class="bi bi-check-circle-fill"></i> {{ session('message') }}</div>
@endif
@if(session()->has('error'))
    <div class="pos-flash danger"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
@endif

{{-- ─── TOPBAR ─── --}}
<div class="pos-topbar">
    <div class="pos-topbar-left">
        <h2>
            <span class="pos-hicon"><i class="bi bi-grid-1x2-fill" style="font-size:15px;"></i></span>
            Point de vente
        </h2>
    </div>
    <div class="pos-meta">
        <div class="pos-meta-chip">
            <span class="pos-live-dot"></span>
            Session active
        </div>
        <div class="pos-meta-chip">
            <i class="bi bi-calendar3"></i>
            {{ now()->format('d/m/Y') }}
        </div>
        <div class="pos-meta-chip">
            <i class="bi bi-clock"></i>
            {{ now()->format('H:i') }}
        </div>
    </div>
</div>

<div class="pos-layout">

    {{-- ══════════════════ CATALOGUE ══════════════════ --}}
    <div class="pos-catalog">

        {{-- Recherche --}}
        <div class="pos-searchbar">
            <i class="bi bi-search s-icon"></i>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                class="pos-search-input"
                placeholder="Nom, référence, catégorie…"
                autocomplete="off"
            >
            <span class="pos-search-kbd">⌘K</span>
        </div>

        {{-- Grille produits --}}
        <div class="pos-products-scroll">
            <div class="pos-grid">
                @if($products->isEmpty())
                    <div class="pos-empty-state">
                        <div class="e-icon"><i class="bi bi-box-seam"></i></div>
                        <p>Aucun produit trouvé pour cette recherche.</p>
                    </div>
                @else
                    @foreach($products as $p)
                        @php
                            $isOut  = $p->quantity <= 0;
                            $isLow  = !$isOut && $p->quantity <= 5;
                            $sClass = $isOut ? 'stkOut' : ($isLow ? 'stkLow' : 'stkOk');
                            $sLabel = $isOut ? 'Rupture'
                                : ($isLow ? $p->quantity.' restant'.($p->quantity > 1 ? 's' : '') : 'Stock : '.$p->quantity);
                        @endphp
                        <div
                            class="pos-tile {{ $isOut ? 'out-of-stock' : '' }}"
                            wire:click="addToCart({{ $p->id }})"
                            title="{{ $isOut ? 'Rupture de stock' : 'Ajouter au panier' }}"
                        >
                            <div class="pos-tile-plus">+</div>
                            <div class="pos-tile-icon"><i class="bi bi-box-seam"></i></div>
                            <div class="pos-tile-name">{{ $p->name }}</div>
                            <div class="pos-tile-price">
                                {{ number_format($p->selling_price, 0, ',', ' ') }}<small> FCFA</small>
                            </div>
                            <span class="pos-stock-badge {{ $sClass }}">
                                @if($isOut) <i class="bi bi-x-circle-fill" style="font-size:8px;"></i>
                                @elseif($isLow) <i class="bi bi-exclamation-circle-fill" style="font-size:8px;"></i>
                                @else <i class="bi bi-check-circle-fill" style="font-size:8px;"></i>
                                @endif
                                {{ $sLabel }}
                            </span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    {{-- ══════════════════ PANIER ══════════════════ --}}
    <div class="pos-cart">

        {{-- En-tête --}}
        <div class="pos-cart-head">
            <div class="pos-cart-icon"><i class="bi bi-bag-check-fill"></i></div>
            <div class="pos-cart-head-title">
                <h5>Panier de vente</h5>
                <span>{{ count($cart) === 0 ? 'Aucun article' : count($cart).' article'.( count($cart) > 1 ? 's' : '' ).' sélectionné'.( count($cart) > 1 ? 's' : '' ) }}</span>
            </div>
            @if(count($cart) > 0)
                <span class="pos-cart-count">{{ count($cart) }}</span>
            @endif
        </div>

        {{-- Articles --}}
        <div class="pos-cart-body">
            @if(count($cart) === 0)
                <div class="pos-cart-empty">
                    <div class="e-ring"><i class="bi bi-cart3"></i></div>
                    <p>Panier vide</p>
                    <p style="font-size:10.5px; margin-top:-3px;">Cliquez sur un produit pour l'ajouter</p>
                </div>
            @else
                @foreach($cart as $index => $item)
                    <div class="pos-cart-line">
                        <div>
                            <div class="cart-line-name">{{ $item['name'] }}</div>
                            <div class="cart-line-uprice">{{ number_format($item['unit_price'], 0, ',', ' ') }} FCFA / u</div>
                        </div>
                        <div class="qty-stepper">
                            <button class="qty-btn" wire:click="updateQuantity({{ $index }}, {{ max(1, $item['quantity'] - 1) }})">−</button>
                            <input
                                type="number" min="1"
                                wire:change="updateQuantity({{ $index }}, $event.target.value)"
                                value="{{ $item['quantity'] }}"
                                class="qty-val"
                            >
                            <button class="qty-btn" wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] + 1 }})">+</button>
                        </div>
                        <div class="cart-line-total">{{ number_format($item['total'], 0, ',', ' ') }}&nbsp;FCFA</div>
                        <button wire:click="removeFromCart({{ $index }})" class="cart-del-btn" title="Supprimer">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Totaux --}}
        @php
            $totalHT  = collect($cart)->sum('total');
            $tva      = $totalHT * 0.1925;
            $totalTTC = $totalHT + $tva;
        @endphp
        <div class="pos-totals">
            <div class="pos-total-line">
                <span>Sous-total HT</span>
                <span class="val">{{ number_format($totalHT, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="pos-total-line">
                <span>TVA <span style="font-size:9px;">(19,25%)</span></span>
                <span class="val">{{ number_format($tva, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="pos-total-ttc">
                <span class="lbl"><i class="bi bi-receipt" style="font-size:12px; margin-right:3px;"></i>Total TTC</span>
                <span class="amount">{{ number_format($totalTTC, 0, ',', ' ') }}<small>FCFA</small></span>
            </div>
        </div>

        {{-- Pied --}}
        <div class="pos-cart-footer">
            <div class="pos-field">
                <label class="pos-field-label"><i class="bi bi-person-fill"></i> Client</label>
                <select wire:model="customer_id" class="pos-select">
                    <option value="">— Client comptant —</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}{{ $c->phone ? ' · '.$c->phone : '' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pos-field">
                <label class="pos-field-label"><i class="bi bi-credit-card-fill"></i> Paiement</label>
                <select wire:model="payment_method" class="pos-select">
                    <option value="cash">💵 Espèces</option>
                    <option value="mtn_momo">📱 MTN Mobile Money</option>
                    <option value="orange_money">🟠 Orange Money</option>
                    <option value="bank_transfer">🏦 Virement bancaire</option>
                </select>
            </div>

            <button
                wire:click="checkout"
                class="pos-pay-btn"
                @if(count($cart) === 0) disabled @endif
            >
                <span class="pay-icon"><i class="bi bi-check2-circle"></i></span>
                Valider la vente
                <span class="pay-amount">{{ number_format($totalTTC, 0, ',', ' ') }} FCFA</span>
            </button>

            @if(count($cart) > 0)
                <button
                    wire:click="clearCart"
                    onclick="return confirm('Vider entièrement le panier ?')"
                    class="pos-clear-btn"
                >
                    <i class="bi bi-x-circle"></i> Vider le panier
                </button>
            @endif
        </div>
    </div>

</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('cartUpdated', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
        Livewire.on('saleCompleted', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    });
    document.addEventListener('livewire:init', () => {
        Livewire.on('scroll-to-top', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    });
</script>
</div>