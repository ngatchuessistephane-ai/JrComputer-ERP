<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Jr Computer ERP</title>
   {{-- Vite CSS sera injecté automatiquement avec le JS en bas --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 205px;
            --sidebar-collapsed-width: 55px;

            /* Brand palette – JR Computer (vert + orange) */
            --brand-green:       #1a7a3c;
            --brand-green-light: #22a352;
            --brand-green-xlight:#e6f5ec;
            --brand-orange:      #f07d00;
            --brand-orange-light:#ff9c2a;
            --brand-orange-xlight:#fff4e6;

            /* Light theme tokens */
            --bg-page:    #f4f6f9;
            --bg-sidebar: #ffffff;
            --bg-card:    #ffffff;
            --bg-hover:   #f0faf4;
            --text-primary:   #0f1f12;
            --text-secondary: #5a6b61;
            --text-muted:     #94a89e;
            --border-color:   #e2ece6;
            --shadow-card:    0 2px 12px rgba(26,122,60,0.07);
            --shadow-sidebar: 4px 0 24px rgba(0,0,0,0.06);
            --nav-active-bg:  var(--brand-green-xlight);
            --nav-active-text:var(--brand-green);
            --topbar-bg:      rgba(244,246,249,0.88);
        }

        [data-theme="dark"] {
            --bg-page:    #0d1810;
            --bg-sidebar: #111d14;
            --bg-card:    #172119;
            --bg-hover:   #1e3023;
            --text-primary:   #e8f5ec;
            --text-secondary: #8aab92;
            --text-muted:     #4d6b55;
            --border-color:   #243328;
            --shadow-card:    0 2px 12px rgba(0,0,0,0.3);
            --shadow-sidebar: 4px 0 32px rgba(0,0,0,0.4);
            --nav-active-bg:  rgba(34,163,82,0.18);
            --nav-active-text:#4dd47c;
            --topbar-bg:      rgba(13,24,16,0.88);
        }

        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            margin: 0; padding: 0; height: 100%;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: var(--bg-page);
            color: var(--text-primary);
            transition: background 0.3s, color 0.3s;
        }

        /* ─── SIDEBAR ─── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            box-shadow: var(--shadow-sidebar);
            display: flex;
            flex-direction: column;
            transition: width 0.28s cubic-bezier(.4,0,.2,1);
            z-index: 1000;
            overflow: hidden;
        }
        .sidebar.collapsed { width: var(--sidebar-collapsed-width); }

        /* Logo zone */
        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border-color);
            overflow: hidden;
            padding: 12px 10px 8px; /* Réduit au lieu de 16px 14px 12px */
            min-height: 64px; /* Aligné sur la topbar pour l'équilibre */
        }
        .logo-mark img{
            width: 29px; height: 29px; /* Au lieu de 36px */
            border-radius: 10px;
            background: linear-gradient(135deg, var(--brand-green) 0%, var(--brand-green-light) 100%);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-family: 'Syne', sans-serif;
            font-size: 11px; font-weight: 800;
            color: white;
            letter-spacing: -0.5px;
            box-shadow: 0 4px 12px rgba(26,122,60,0.35);

        }
        .logo-text {
            overflow: hidden;
            white-space: nowrap;
            opacity: 1;
            transition: opacity 0.2s;
        }
        .sidebar.collapsed .logo-text { opacity: 0; }
       .logo-title {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: var(--brand-green);
            letter-spacing: 0.3px;
            line-height: 1.3;
        }
        .logo-sub {
            font-size: 10px; font-weight: 500;
            color: var(--brand-orange);
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        /* Toggle button */
        .sidebar-toggle {
            position: absolute;
            top: 24px;
            right: -14px;
            width: 28px; height: 28px;
            border-radius: 50%;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: var(--text-secondary);
            font-size: 13px;
            transition: all 0.2s;
            z-index: 10;
        }
        .sidebar-toggle:hover {
            background: var(--brand-green);
            color: white;
            border-color: var(--brand-green);
            transform: scale(1.1);
        }

        /* Navigation */
        .sidebar-nav { flex: 1; overflow-y: auto; padding: 7px 10px; }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 99px; }

        .nav-section-label {
            font-size: 8.5px; font-weight: 700; letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 13px 12px 5px;/* Un peu plus compact */
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.2s;
        }
        .sidebar.collapsed .nav-section-label { opacity: 0; }

        .nav-item {
            display: flex; align-items: center;
            gap: 7px;
            padding: 5px 8px;
            border-radius: 10px;
            margin-bottom: 2px;
            cursor: pointer;
            text-decoration: none;
            color: var(--text-secondary);
            font-size: 11.5px; font-weight: 500;
            transition: all 0.18s;
            position: relative;
            white-space: nowrap;
            overflow: hidden;
        }
        .nav-item:hover {
            background: var(--bg-hover);
            color: var(--brand-green);
        }
        .nav-item.active {
            background: var(--nav-active-bg);
            color: var(--nav-active-text);
            font-weight: 600;
        }
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px;
            border-radius: 0 3px 3px 0;
            background: var(--brand-green);
        }
        .nav-icon {
            font-size: 16px; /* Au lieu de 18px */
            flex-shrink: 0;
            width: 20px;
            text-align: center;
        }
        .nav-label {
            flex: 1;
            opacity: 1;
            transition: opacity 0.15s;
        }
        .sidebar.collapsed .nav-label,
        .sidebar.collapsed .nav-badge { opacity: 0; }

        .nav-badge {
            background: var(--brand-orange);
            color: white;
            font-size: 10px; font-weight: 700;
            padding: 1px 7px;
            border-radius: 99px;
            line-height: 1.6;
        }

        /* Sidebar footer */
        .sidebar-footer {
            margin:1px 0 1px 0;
            padding: 2px 10px;
            border-top: 1px solid var(--border-color);
        }
        .sidebar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 2px 4px;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.18s;
            overflow: hidden;
        }
        .sidebar-user:hover { background: linear-gradient(135deg, rgba(239,68,68,0.15) 0%, rgba(220,38,38,0.1) 100%);
            border-color: rgba(239,68,68,0.4);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239,68,68,0.15);}
        .user-avatar {
            width: 29px; height: 29px; border-radius: 9px;
            background: linear-gradient(135deg, var(--brand-green), #22a352);
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 12px; font-weight: 700;
            flex-shrink: 0;
        }
        .user-info { overflow: hidden; }
        .logout-text { font-size: 12.5px; font-weight: 600; color: var(--text-primary); white-space: nowrap; }
        .user-role { font-size: 11px; color: var(--brand-green-light); white-space: nowrap; }

        /* ─── MAIN CONTENT ─── */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.28s cubic-bezier(.4,0,.2,1);
            display: flex; flex-direction: column;
        }
        .sidebar.collapsed ~ .main-content { margin-left: var(--sidebar-collapsed-width); }

        /* Topbar */
        .topbar {
            position: sticky; top: 0; z-index: 100;
            background: var(--topbar-bg);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border-color);
            padding: 0 10px;
            height: 50px;
            display: flex; align-items: center;
            gap: 16px;
        }
        .topbar-title {
            font-family: 'Syne', sans-serif;
            font-size: 16px; font-weight: 700;
            color: var(--text-primary);
            flex: 1;
        }
        .topbar-actions { display: flex; align-items: center; gap: 8px; }

        /* Icon buttons */
        .icon-btn {
            width: 35px; height: 35px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 17px;
            transition: all 0.18s;
            text-decoration: none;
        }
        .icon-btn:hover { border-color: var(--brand-green); color: var(--brand-green); background: var(--brand-green-xlight); }

        /* Theme toggle */
        .theme-toggle {
            width: 35px; height: 35px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 17px;
            transition: all 0.18s;
        }
        .theme-toggle:hover { border-color: var(--brand-orange); color: var(--brand-orange); background: var(--brand-orange-xlight); }

        /* Notif badge */
        .notif-wrap { position: relative; }
        .notif-dot {
            position: absolute; top: 6px; right: 6px;
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--brand-orange);
            border: 2px solid var(--bg-page);
        }

        /* User chip */
        .user-chip {
            display: flex; align-items: center; gap: 8px;
            padding: 3px 11px 3px 3px; /* Plus compact */
            border-radius: 12px;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            cursor: pointer;
            transition: all 0.18s;
        }
        .user-chip:hover { border-color: var(--brand-green); }
        .chip-avatar {
            width: 26px; height: 26px; border-radius: 8px;
            background: linear-gradient(135deg, var(--brand-orange) 0%, var(--brand-orange-light) 100%);
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 11px; font-weight: 700;
        }
        .chip-name { font-size: 12px; font-weight: 600; color: var(--text-primary); }

        /* Dropdown */
        .dropdown-menu {
            border: 1px solid var(--border-color) !important;
            background: var(--bg-card) !important;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12) !important;
            border-radius: 12px !important;
            padding: 6px !important;
        }
        .dropdown-item {
            border-radius: 8px !important;
            font-size: 13.5px !important;
            color: var(--text-primary) !important;
            padding: 8px 14px !important;
        }
        .dropdown-item:hover { background: var(--bg-hover) !important; color: var(--brand-green) !important; }

        /* Page area */
        .page-area { flex: 1; padding: 28px 28px 40px; }

        /* ─── CARDS ─── */
        .card-jr {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: var(--shadow-card);
            transition: border-color 0.18s;
        }
        .card-jr:hover { border-color: rgba(26,122,60,0.25); }

        /* Breadcrumb strip */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 24px;
        }
        .page-header h1 {
            font-family: 'Syne', sans-serif;
            font-size: 24px; font-weight: 800;
            color: var(--text-primary);
            margin: 0;
        }
        .page-header .subtitle {
            font-size: 13.5px; color: var(--text-muted);
            margin-top: 3px;
        }

        /* Buttons */
        .btn-brand {
            background: linear-gradient(135deg, var(--brand-green) 0%, var(--brand-green-light) 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 9px 18px;
            font-size: 13.5px; font-weight: 600;
            display: inline-flex; align-items: center; gap: 6px;
            cursor: pointer;
            transition: all 0.18s;
            box-shadow: 0 4px 12px rgba(26,122,60,0.28);
        }
        .btn-brand:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(26,122,60,0.38); }

        .btn-accent {
            background: linear-gradient(135deg, var(--brand-orange) 0%, var(--brand-orange-light) 100%);
            color: white; border: none; border-radius: 10px;
            padding: 9px 18px; font-size: 13.5px; font-weight: 600;
            display: inline-flex; align-items: center; gap: 6px;
            cursor: pointer; transition: all 0.18s;
            box-shadow: 0 4px 12px rgba(240,125,0,0.28);
        }
        .btn-accent:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(240,125,0,0.38); }

        .btn-ghost {
            background: transparent; color: var(--text-secondary);
            border: 1px solid var(--border-color); border-radius: 10px;
            padding: 9px 18px; font-size: 13.5px; font-weight: 500;
            display: inline-flex; align-items: center; gap: 6px;
            cursor: pointer; transition: all 0.18s;
        }
        .btn-ghost:hover { border-color: var(--brand-green); color: var(--brand-green); background: var(--brand-green-xlight); }

        /* Mobile overlay */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: hsla(0, 0%, 0%, 0.45);
            z-index: 999;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important;
                transition: transform 0.28s cubic-bezier(.4,0,.2,1);
            }
            .sidebar.mobile-open { transform: translateX(0); }
            .sidebar-overlay { display: block; }
            .main-content { margin-left: 0 !important; }
            .topbar { padding: 0 16px; }
            .page-area { padding: 16px 16px 32px; }
        }

        /* ─── TOOLTIP pour sidebar collapsed ─── */
        .nav-item[data-tip] { position: relative; }
        .sidebar.collapsed .nav-item[data-tip]:hover::after {
            content: attr(data-tip);
            position: absolute;
            left: calc(100% + 14px);
            top: 50%; transform: translateY(-50%);
            background: var(--text-primary);
            color: var(--bg-page);
            font-size: 12px; font-weight: 500;
            padding: 5px 10px;
            border-radius: 8px;
            white-space: nowrap;
            pointer-events: none;
            z-index: 2000;
        }
        .sidebar.collapsed .nav-item[data-tip]:hover::before {
            content: '';
            position: absolute;
            left: calc(100% + 8px);
            top: 50%; transform: translateY(-50%);
            border: 6px solid transparent;
            border-right-color: var(--text-primary);
            z-index: 2000;
        }

      /* Parent item with arrow */
.nav-item-parent {
    display: flex; align-items: center;
    gap: 11px; padding: 9px 12px;
    border-radius: 10px;
    margin-bottom: 2px;
    cursor: pointer;
    text-decoration: none;
    color: var(--text-secondary);
    font-size: 11.5px; font-weight: 500;
    transition: all 0.18s;
    position: relative;
    white-space: nowrap;
    overflow: hidden;
}
.nav-item-parent:hover {
    background: var(--bg-hover);
    color: var(--brand-green);
}
.nav-item-parent.active {
    background: var(--nav-active-bg);
    color: var(--nav-active-text);
    font-weight: 600;
}
.nav-arrow {
    margin-left: auto;
    transition: transform 0.2s ease;
    font-size: 12px;
}
.nav-arrow.rotated {
    transform: rotate(180deg);
}

/* Children container */
.nav-children {
    margin-left: 20px;  /* indentation visuelle */
    border-left: 1px dashed var(--border-color);
    margin-bottom: 6px;
}
.nav-item.child {
    padding-left: 28px !important;
}
/*////////////////////////////////////*/
.user-name-highlight {
    background: linear-gradient(135deg, var(--brand-green) 0%, var(--brand-orange) 100%);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent;
    font-weight: 800;
}
.greeting-wave {
    display: inline-block;
    animation: wave 1.5s infinite;
    transform-origin: 70% 70%;
}
@keyframes wave {
    0% { transform: rotate(0deg); }
    20% { transform: rotate(14deg); }
    40% { transform: rotate(-8deg); }
    60% { transform: rotate(14deg); }
    80% { transform: rotate(-4deg); }
    100% { transform: rotate(0deg); }
}
.dropdown:hover i {
            transform: translateX(2px);
        }

    </style>
</head>
<body>

    <aside class="sidebar" id="sidebar">
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="bi bi-chevron-left" id="toggleIcon"></i>
        </button>

        <div class="sidebar-header">
            <div class="logo-mark"><img src="{{ asset('images/logo-jr.jpg') }}" alt="Logo"></div>
            <div class="logo-text">
                <div class="logo-title">Jr Computer</div>
                <div class="logo-sub">ERP System</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Principal</div>
            @can('view analytics')
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" data-tip="Dashboard">
                    <i class="bi bi-speedometer2 nav-icon"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
                   @endcan
                @can('view products')
                <a href="{{ route('module1.products.index') }}" class="nav-item {{ request()->routeIs('module1.products.*') ? 'active' : '' }}" data-tip="Produits & Stock">
                    <i class="bi bi-box-seam nav-icon"></i>
                    <span class="nav-label">Produits & Stock</span>
                </a>
            @endcan
            {{-- VENTES --}}
            @php
                $hasVentesPermission = auth()->user()->can('view customers') 
                                    || auth()->user()->can('view quotes') 
                                    || auth()->user()->can('view invoices') 
                                    || auth()->user()->can('use pos');
            @endphp
            @if($hasVentesPermission)
                <div x-data="{ openVentes: {{ request()->routeIs('module3.*') || request()->routeIs('module3.customers.*') || request()->routeIs('module3.quotes.*') || request()->routeIs('module3.invoices.*') || request()->routeIs('module3.pos.*') ? 'true' : 'false' }} }">
                    <div class="nav-item-parent" @click="openVentes = !openVentes" :class="{ 'active': openVentes }" data-tip="Ventes">
                        <i class="bi bi-cart3 nav-icon"></i>
                        <span class="nav-label">Ventes</span>
                        <i class="bi bi-chevron-down nav-arrow" :class="{ 'rotated': openVentes }"></i>
                    </div>
                    <div x-show="openVentes" x-transition.duration.200ms class="nav-children">
                        @can('view customers')
                            <a href="{{ route('module3.customers.index') }}" class="nav-item child {{ request()->routeIs('module3.customers.*') ? 'active' : '' }}" data-tip="Clients">
                                <i class="bi bi-people nav-icon"></i><span class="nav-label">Clients</span>
                            </a>
                        @endcan
                        @can('view quotes')
                            <a href="{{ route('module3.quotes.index') }}" class="nav-item child {{ request()->routeIs('module3.quotes.*') ? 'active' : '' }}" data-tip="Devis">
                                <i class="bi bi-file-text nav-icon"></i><span class="nav-label">Devis</span>
                            </a>
                        @endcan
                        @can('view invoices')
                            <a href="{{ route('module3.invoices.index') }}" class="nav-item child {{ request()->routeIs('module3.invoices.*') ? 'active' : '' }}" data-tip="Factures">
                                <i class="bi bi-receipt nav-icon"></i><span class="nav-label">Factures</span>
                            </a>
                        @endcan
                        @can('use pos')
                            <a href="{{ route('module3.pos.index') }}" class="nav-item child {{ request()->routeIs('module3.pos.*') ? 'active' : '' }}" data-tip="Point de vente">
                                <i class="bi bi-cart3 nav-icon"></i><span class="nav-label">POS</span>
                            </a>
                        @endcan
                    </div>
                </div>
            @endif

            {{-- ACHATS --}}
            @php
                $hasAchatsPermission = auth()->user()->can('view suppliers') || auth()->user()->can('view purchase orders');
            @endphp
            @if($hasAchatsPermission)
                <div class="nav-section-label">Achats</div>
                @can('view suppliers')
                    <a href="{{ route('module2.suppliers.index') }}" class="nav-item {{ request()->routeIs('module2.suppliers.*') ? 'active' : '' }}" data-tip="Fournisseurs">
                        <i class="bi bi-truck nav-icon"></i><span class="nav-label">Fournisseurs</span>
                    </a>
                @endcan
                @can('view purchase orders')
                    <a href="{{ route('module2.purchase-orders.index') }}" class="nav-item {{ request()->routeIs('module2.purchase-orders.*') ? 'active' : '' }}" data-tip="Bons de commande">
                        <i class="bi bi-cart-check nav-icon"></i><span class="nav-label">Bons de commande</span>
                    </a>
                @endcan
            @endif

            {{-- SAV --}}
            @php
                $hasSavPermission = auth()->user()->can('view sav tickets') || auth()->user()->can('use sav parts');
            @endphp
            @if($hasSavPermission)
                <div x-data="{ openSav: {{ request()->routeIs('module5.*') ? 'true' : 'false' }} }">
                    <div class="nav-item-parent" @click="openSav = !openSav" :class="{ 'active': openSav }" data-tip="SAV">
                        <i class="bi bi-tools nav-icon"></i>
                        <span class="nav-label">Serv Après-Vente</span>
                        <i class="bi bi-chevron-down nav-arrow" :class="{ 'rotated': openSav }"></i>
                    </div>
                    <div x-show="openSav" x-transition.duration.200ms class="nav-children">
                        @can('view sav tickets')
                            <a href="{{ route('module5.tickets.index') }}" class="nav-item child {{ request()->routeIs('module5.tickets.*') ? 'active' : '' }}" data-tip="Tickets SAV">
                                <i class="bi bi-ticket-perforated nav-icon"></i><span class="nav-label">Tickets SAV</span>
                            </a>
                        @endcan
                        @can('use sav parts')
                            <a href="{{ route('module5.parts.index') }}" class="nav-item child {{ request()->routeIs('module5.parts.*') ? 'active' : '' }}" data-tip="Pièces détachées">
                                <i class="bi bi-puzzle nav-icon"></i><span class="nav-label">Pièces détachées</span>
                            </a>
                        @endcan
                    </div>
                </div>
            @endif

            {{-- CONFIGURATION --}}
            @can('view users')
            <div class="nav-section-label">Configuration</div>
            
                 <a href="{{ route('users.index') }}" class="nav-item" data-tip="Utilisateurs">
                  <i class="bi bi-people nav-icon"></i>
                   <span class="nav-label">Utilisateurs</span>
                 </a>
                @endcan
            <!-- @can('manage settings')
                <a href="#" class="nav-item" data-tip="Paramètres">
                    <i class="bi bi-sliders nav-icon"></i>
                    <span class="nav-label">Paramètres</span>
                </a>
            @endcan -->
        </nav>

        <div class="sidebar-footer">
            <div class="dropdown">
                <div class="sidebar-user dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-box-arrow-right" style="font-size: 25px;
            color: #ef4444;"></i>
                    <div class="user-info">
                        <div class="logout-text">Déconnexion</div>
                        <div class="user-role">
                            @if(auth()->user()->hasRole('admin')) Administrateur
                            @elseif(auth()->user()->hasRole('manager')) Manager
                            @elseif(auth()->user()->hasRole('vendeur')) Vendeur
                            @elseif(auth()->user()->hasRole('technicien_sav')) Technicien SAV
                            @else Utilisateur
                            @endif
                        </div>
                    </div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <!-- <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>Mon profil</a></li>
                    <li><hr class="dropdown-divider" style="border-color: var(--border-color); margin: 4px 8px;"></li> -->
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i>Cliquez Ici
                        </a>
                    </li>
                </ul>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
        </div>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay" style="display:none;"></div>

    <div class="main-content" id="mainContent">
        <header class="topbar">
            <button class="icon-btn d-md-none" id="mobileMenuBtn" aria-label="Menu">
                <i class="bi bi-list"></i>
            </button>
           <div class="topbar-title" id="pageTitle">
    @php
        $hour = now()->hour;
        if ($hour >= 5 && $hour < 12) {
            $greeting = 'Bonjour';
        } elseif ($hour >= 12 && $hour < 18) {
            $greeting = 'Bon après-midi';
        } elseif ($hour >= 18 && $hour < 22) {
            $greeting = 'Bonsoir';
        } else {
            $greeting = 'Bonne nuit';
        }
    @endphp
    <h2 style="font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px;">
        <span>{{ $greeting }} <span class="greeting-wave">👋</span>,&nbsp; &nbsp;Mr. <span class="user-name-highlight">{{ Auth::user()->name ?? 'Invité' }}</span></span>
    </h2>
</div>
            <div class="topbar-actions">
                <button class="theme-toggle" id="themeToggle" aria-label="Changer le thème">
                    <i class="bi bi-moon" id="themeIcon"></i>
                </button>
                <div class="notif-wrap">
                    <button class="icon-btn" aria-label="Notifications">
                        <i class="bi bi-bell"></i>
                    </button>
                    <div class="notif-dot"></div>
                </div>
                <div class="dropdown">
                    <div class="user-chip dropdown-toggle" data-bs-toggle="dropdown">
                        @if(Auth::user()->avatar)
                            <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="Avatar" width="25" height="22" style="border-radius: 50%; object-fit: cover;">
                        @else
                            <div class="chip-avatar">
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                            </div>
                        @endif
                        <span class="chip-name">{{ Auth::user()->name ?? 'Compte' }}</span>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>Mon profil</a></li>
                        <li><hr class="dropdown-divider" style="border-color: var(--border-color); margin: 4px 8px;"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i>Quitter l'appli
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>
        <main class="page-area">
             @yield('content')
        </main>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        /* Scripts identiques à appProd.blade.php (sidebar, theme) */
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        const toggleIcon = document.getElementById('toggleIcon');
        const COLLAPSED = 'collapsed';

        const savedState = localStorage.getItem('sidebarCollapsed');
        if (savedState === 'true') {
            sidebar.classList.add(COLLAPSED);
            toggleIcon.className = 'bi bi-chevron-right';
        }

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle(COLLAPSED);
            const isCollapsed = sidebar.classList.contains(COLLAPSED);
            toggleIcon.className = isCollapsed ? 'bi bi-chevron-right' : 'bi bi-chevron-left';
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        });

        const mobileBtn = document.getElementById('mobileMenuBtn');
        const overlay = document.getElementById('sidebarOverlay');
        const MOBILE_OPEN = 'mobile-open';

        function closeMobileMenu() {
            sidebar.classList.remove(MOBILE_OPEN);
            overlay.style.display = 'none';
        }
        mobileBtn?.addEventListener('click', () => {
            const open = sidebar.classList.toggle(MOBILE_OPEN);
            overlay.style.display = open ? 'block' : 'none';
        });
        overlay.addEventListener('click', closeMobileMenu);

        const html = document.documentElement;
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');

        function applyTheme(theme) {
            html.setAttribute('data-theme', theme);
            themeIcon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
            localStorage.setItem('jrTheme', theme);
        }

        const savedTheme = localStorage.getItem('jrTheme') || 'light';
        applyTheme(savedTheme);

        themeToggle.addEventListener('click', () => {
            const current = html.getAttribute('data-theme');
            applyTheme(current === 'dark' ? 'light' : 'dark');
        });
    </script>
</body>
</html>