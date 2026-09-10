<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Jr Computer ERP</title>
     <!-- Favicon - Logo JR Computer -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-jr.jpg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/logo-jr.jpg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 205px;
            --sidebar-collapsed-width: 55px;
            --brand-green: #1a7a3c;
            --brand-green-light: #22a352;
            --brand-green-xlight: #e6f5ec;
            --brand-orange: #f07d00;
            --brand-orange-light: #ff9c2a;
            --brand-orange-xlight: #fff4e6;
            --bg-page: #f4f6f9;
            --bg-sidebar: #ffffff;
            --bg-card: #ffffff;
            --bg-hover: #f0faf4;
            --text-primary: #0f1f12;
            --text-secondary: #5a6b61;
            --text-muted: #94a89e;
            --border-color: #e2ece6;
            --shadow-card: 0 2px 12px rgba(26,122,60,0.07);
            --shadow-sidebar: 4px 0 24px rgba(0,0,0,0.06);
            --nav-active-bg: var(--brand-green-xlight);
            --nav-active-text: var(--brand-green);
            --topbar-bg: rgba(244,246,249,0.88);
        }

        [data-theme="dark"] {
            --bg-page: #0d1810;
            --bg-sidebar: #111d14;
            --bg-card: #172119;
            --bg-hover: #1e3023;
            --text-primary: #e8f5ec;
            --text-secondary: #8aab92;
            --text-muted: #4d6b55;
            --border-color: #243328;
            --shadow-card: 0 2px 12px rgba(0,0,0,0.3);
            --shadow-sidebar: 4px 0 32px rgba(0,0,0,0.4);
            --nav-active-bg: rgba(34,163,82,0.18);
            --nav-active-text: #4dd47c;
            --topbar-bg: rgba(13,24,16,0.88);
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
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-width); height: 100vh;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            box-shadow: var(--shadow-sidebar);
            display: flex; flex-direction: column;
            transition: width 0.28s cubic-bezier(.4,0,.2,1);
            z-index: 1000; overflow: hidden;
        }
        .sidebar.collapsed { width: var(--sidebar-collapsed-width); }

        .sidebar-header {
            display: flex; align-items: center; gap: 12px;
            border-bottom: 1px solid var(--border-color);
            overflow: hidden; padding: 12px 10px 8px; min-height: 64px;
        }
        .logo-mark img {
            width: 29px; height: 29px; border-radius: 10px;
            box-shadow: 0 4px 12px rgba(26,122,60,0.35);
        }
        .logo-text { overflow: hidden; white-space: nowrap; opacity: 1; transition: opacity 0.2s; }
        .sidebar.collapsed .logo-text { opacity: 0; }
        .logo-title { font-family: 'Plus Jakarta Sans', system-ui; font-size: 20px; font-weight: 800; color: var(--brand-green); letter-spacing: 0.3px; line-height: 1.3; }
        .logo-sub { font-size: 10px; font-weight: 500; color: var(--brand-orange); letter-spacing: 0.08em; text-transform: uppercase; }

        .sidebar-toggle {
            position: absolute; top: 24px; right: -14px;
            width: 28px; height: 28px; border-radius: 50%;
            background: var(--bg-card); border: 1px solid var(--border-color);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--text-secondary); font-size: 13px;
            transition: all 0.2s; z-index: 10;
        }
        .sidebar-toggle:hover { background: var(--brand-green); color: white; border-color: var(--brand-green); transform: scale(1.1); }

        .sidebar-nav { flex: 1; overflow-y: auto; padding: 7px 10px; }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 99px; }

        .nav-section-label { font-size: 8.5px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--text-muted); padding: 13px 12px 5px; white-space: nowrap; overflow: hidden; transition: opacity 0.2s; }
        .sidebar.collapsed .nav-section-label { opacity: 0; }

        .nav-item {
            display: flex; align-items: center; gap: 7px;
            padding: 5px 8px; border-radius: 10px; margin-bottom: 2px;
            cursor: pointer; text-decoration: none; color: var(--text-secondary);
            font-size: 11.5px; font-weight: 500; transition: all 0.18s;
            position: relative; white-space: nowrap; overflow: hidden;
        }
        .nav-item:hover { background: var(--bg-hover); color: var(--brand-green); }
        .nav-item.active { background: var(--nav-active-bg); color: var(--nav-active-text); font-weight: 600; }
        .nav-item.active::before { content: ''; position: absolute; left: 0; top: 20%; bottom: 20%; width: 3px; border-radius: 0 3px 3px 0; background: var(--brand-green); }
        .nav-icon { font-size: 16px; flex-shrink: 0; width: 20px; text-align: center; }
        .nav-label { flex: 1; opacity: 1; transition: opacity 0.15s; }
        .sidebar.collapsed .nav-label, .sidebar.collapsed .nav-badge { opacity: 0; }
        .nav-badge { background: var(--brand-orange); color: white; font-size: 10px; font-weight: 700; padding: 1px 7px; border-radius: 99px; line-height: 1.6; }

        .sidebar-footer { margin: 1px 0 1px 0; padding: 2px 10px; border-top: 1px solid var(--border-color); }
        .sidebar-user { display: flex; align-items: center; gap: 10px; padding: 2px 4px; border-radius: 10px; cursor: pointer; transition: background 0.18s; overflow: hidden; }
        .sidebar-user:hover { background: linear-gradient(135deg, rgba(239,68,68,0.15) 0%, rgba(220,38,38,0.1) 100%); border-color: rgba(239,68,68,0.4); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(239,68,68,0.15); }
        .user-avatar { width: 29px; height: 29px; border-radius: 9px; background: linear-gradient(135deg, var(--brand-green), #22a352); display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: 700; flex-shrink: 0; }
        .user-info { overflow: hidden; }
        .logout-text { font-size: 12.5px; font-weight: 600; color: var(--text-primary); white-space: nowrap; }
        .user-role { font-size: 11px; color: var(--brand-green-light); white-space: nowrap; }

        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; transition: margin-left 0.28s cubic-bezier(.4,0,.2,1); display: flex; flex-direction: column; }
        .sidebar.collapsed ~ .main-content { margin-left: var(--sidebar-collapsed-width); }

        .topbar {
            position: sticky; top: 0; z-index: 100;
            background: var(--topbar-bg);
            backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border-color);
            padding: 0 10px; height: 50px;
            display: flex; align-items: center; gap: 16px;
        }
        .topbar-title { font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; color: var(--text-primary); flex: 1; }
        .topbar-actions { display: flex; align-items: center; gap: 8px; }

        .icon-btn { width: 35px; height: 35px; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-secondary); cursor: pointer; font-size: 17px; transition: all 0.18s; text-decoration: none; }
        .icon-btn:hover { border-color: var(--brand-green); color: var(--brand-green); background: var(--brand-green-xlight); }
        .theme-toggle { width: 35px; height: 35px; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-secondary); cursor: pointer; font-size: 17px; transition: all 0.18s; }
        .theme-toggle:hover { border-color: var(--brand-orange); color: var(--brand-orange); background: var(--brand-orange-xlight); }

        .user-chip { display: flex; align-items: center; gap: 8px; padding: 3px 11px 3px 3px; border-radius: 12px; border: 1px solid var(--border-color); background: var(--bg-card); cursor: pointer; transition: all 0.18s; }
        .user-chip:hover { border-color: var(--brand-green); }
        .chip-avatar { width: 26px; height: 26px; border-radius: 8px; background: linear-gradient(135deg, var(--brand-orange) 0%, var(--brand-orange-light) 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 11px; font-weight: 700; }
        .chip-name { font-size: 12px; font-weight: 600; color: var(--text-primary); }

        .dropdown-menu { border: 1px solid var(--border-color) !important; background: var(--bg-card) !important; box-shadow: 0 8px 30px rgba(0,0,0,0.12) !important; border-radius: 12px !important; padding: 6px !important; }
        .dropdown-item { border-radius: 8px !important; font-size: 13.5px !important; color: var(--text-primary) !important; padding: 8px 14px !important; }
        .dropdown-item:hover { background: var(--bg-hover) !important; color: var(--brand-green) !important; }

        .page-area { flex: 1; padding: 28px 28px 40px; }

        .card-jr { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; box-shadow: var(--shadow-card); transition: border-color 0.18s; }
        .card-jr:hover { border-color: rgba(26,122,60,0.25); }

        .btn-brand { background: linear-gradient(135deg, var(--brand-green) 0%, var(--brand-green-light) 100%); color: white; border: none; border-radius: 10px; padding: 9px 18px; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; transition: all 0.18s; box-shadow: 0 4px 12px rgba(26,122,60,0.28); }
        .btn-brand:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(26,122,60,0.38); }
        .btn-accent { background: linear-gradient(135deg, var(--brand-orange) 0%, var(--brand-orange-light) 100%); color: white; border: none; border-radius: 10px; padding: 9px 18px; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; transition: all 0.18s; box-shadow: 0 4px 12px rgba(240,125,0,0.28); }
        .btn-accent:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(240,125,0,0.38); }
        .btn-ghost { background: transparent; color: var(--text-secondary); border: 1px solid var(--border-color); border-radius: 10px; padding: 9px 18px; font-size: 13.5px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; transition: all 0.18s; }
        .btn-ghost:hover { border-color: var(--brand-green); color: var(--brand-green); background: var(--brand-green-xlight); }

        .sidebar-overlay { display: none; position: fixed; inset: 0; background: hsla(0, 0%, 0%, 0.45); z-index: 999; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: var(--sidebar-width) !important; transition: transform 0.28s cubic-bezier(.4,0,.2,1); }
            .sidebar.mobile-open { transform: translateX(0); }
            .sidebar-overlay { display: block; }
            .main-content { margin-left: 0 !important; }
            .topbar { padding: 0 16px; }
            .page-area { padding: 16px 16px 32px; }
        }

        .nav-item[data-tip] { position: relative; }
        .sidebar.collapsed .nav-item[data-tip]:hover::after { content: attr(data-tip); position: absolute; left: calc(100% + 14px); top: 50%; transform: translateY(-50%); background: var(--text-primary); color: var(--bg-page); font-size: 12px; font-weight: 500; padding: 5px 10px; border-radius: 8px; white-space: nowrap; pointer-events: none; z-index: 2000; }
        .sidebar.collapsed .nav-item[data-tip]:hover::before { content: ''; position: absolute; left: calc(100% + 8px); top: 50%; transform: translateY(-50%); border: 6px solid transparent; border-right-color: var(--text-primary); z-index: 2000; }

        .nav-item-parent { display: flex; align-items: center; gap: 11px; padding: 9px 12px; border-radius: 10px; margin-bottom: 2px; cursor: pointer; text-decoration: none; color: var(--text-secondary); font-size: 11.5px; font-weight: 500; transition: all 0.18s; position: relative; white-space: nowrap; overflow: hidden; }
        .nav-item-parent:hover { background: var(--bg-hover); color: var(--brand-green); }
        .nav-item-parent.active { background: var(--nav-active-bg); color: var(--nav-active-text); font-weight: 600; }
        .nav-arrow { margin-left: auto; transition: transform 0.2s ease; font-size: 12px; }
        .nav-arrow.rotated { transform: rotate(180deg); }
        .nav-children { margin-left: 20px; border-left: 1px dashed var(--border-color); margin-bottom: 6px; }
        .nav-item.child { padding-left: 28px !important; }

        .greeting-wave { display: inline-block; animation: wave 1.5s infinite; transform-origin: 70% 70%; }
        @keyframes wave { 0% { transform: rotate(0deg); } 20% { transform: rotate(14deg); } 40% { transform: rotate(-8deg); } 60% { transform: rotate(14deg); } 80% { transform: rotate(-4deg); } 100% { transform: rotate(0deg); } }

        /* ══════════════════════════════════════════════════════════
           NOTIFICATION SYSTEM — SENIOR DEV EDITION
        ══════════════════════════════════════════════════════════ */

        .notif-wrap { position: relative; display: inline-flex; align-items: center; }

        .notif-badge {
            position: absolute; top: -4px; right: -5px;
            min-width: 16px; height: 16px;
            background: #ef4444; color: #fff;
            font-size: 8px; font-weight: 700;
            padding: 0 4px; border-radius: 99px;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--bg-page); z-index: 10;
            transition: transform 0.2s cubic-bezier(.34,1.56,.64,1);
        }
        .notif-badge.bump { animation: badge-bump 0.35s cubic-bezier(.34,1.56,.64,1) forwards; }
        @keyframes badge-bump { 0% { transform: scale(1); } 55% { transform: scale(1.55); } 100% { transform: scale(1); } }
        .bell-ring { animation: bell-shake 0.48s cubic-bezier(.36,.07,.19,.97) both; }
        @keyframes bell-shake { 10%,90% { transform: rotate(-4deg); } 30%,70% { transform: rotate(-9deg); } 40%,60% { transform: rotate(9deg); } 100% { transform: rotate(0); } }

        .notif-panel {
            position: absolute; top: calc(100% + 10px); right: -8px;
            width: 400px; background: var(--bg-card);
            border: 1px solid var(--border-color); border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.13), 0 4px 16px rgba(0,0,0,0.06);
            z-index: 1050; display: flex; flex-direction: column;
            max-height: 560px; transform-origin: top right;
            transform: scale(0.96) translateY(-6px); opacity: 0;
            pointer-events: none; transition: transform 0.22s cubic-bezier(.34,1.18,.64,1), opacity 0.18s ease;
            overflow: hidden;
        }
        .notif-panel.open { transform: scale(1) translateY(0); opacity: 1; pointer-events: all; }

        .np-header { padding: 14px 16px 0; background: var(--bg-card); flex-shrink: 0; }
        .np-header-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
        .np-title-wrap { display: flex; align-items: center; gap: 8px; }
        .np-title { font-size: 14px; font-weight: 700; color: var(--text-primary); letter-spacing: -0.3px; }
        .np-live { display: flex; align-items: center; gap: 5px; padding: 2px 8px; border-radius: 20px; background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2); }
        .np-live-dot { width: 5px; height: 5px; border-radius: 50%; background: #22c55e; animation: live-pulse 1.8s ease-in-out infinite; }
        @keyframes live-pulse { 0%,100% { opacity: 1; } 50% { opacity: 0.45; } }
        .np-live-label { font-size: 9px; font-weight: 600; color: #16a34a; letter-spacing: 0.04em; text-transform: uppercase; }
        [data-theme="dark"] .np-live-label { color: #4ade80; }
        .np-header-actions { display: flex; align-items: center; gap: 6px; }
        .np-btn-ghost { height: 28px; padding: 0 10px; border-radius: 8px; border: 1px solid var(--border-color); background: transparent; color: var(--text-muted); font-size: 11px; font-weight: 500; font-family: inherit; display: inline-flex; align-items: center; gap: 5px; cursor: pointer; transition: all 0.15s; white-space: nowrap; }
        .np-btn-ghost:hover { background: var(--bg-hover); border-color: var(--brand-green); color: var(--brand-green); }
        .np-icon-btn { width: 28px; height: 28px; border-radius: 8px; background: var(--bg-hover); border: 1px solid var(--border-color); color: var(--text-muted); font-size: 12px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.15s; }
        .np-icon-btn:hover { background: var(--brand-green-light); border-color: var(--brand-green); color: var(--brand-green); }

        .np-chips-row {
            display: flex; gap: 6px; padding: 10px 16px;
            overflow-x: auto; scrollbar-width: none;
            border-bottom: 1px solid var(--border-color);
            flex-shrink: 0;
        }
        .np-chips-row::-webkit-scrollbar { display: none; }
        .np-chip {
            white-space: nowrap; font-size: 11px; font-weight: 500;
            padding: 4px 12px; border-radius: 20px;
            border: 1px solid var(--border-color);
            background: none; cursor: pointer;
            color: var(--text-secondary); font-family: inherit;
            transition: all 0.13s;
        }
        .np-chip:hover { border-color: var(--brand-green); color: var(--brand-green); }
        .np-chip.active { background: var(--brand-green); color: white; border-color: var(--brand-green); }

        .np-list { flex: 1; overflow-y: auto; scrollbar-width: thin; scrollbar-color: var(--border-color) transparent; }
        .np-list::-webkit-scrollbar { width: 3px; }
        .np-list::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 99px; }

        .np-date-label {
            padding: 8px 16px 6px; font-size: 10px; font-weight: 700;
            letter-spacing: 0.08em; text-transform: uppercase;
            color: var(--brand-green); background: var(--bg-card);
            position: sticky; top: 0; z-index: 5;
            border-bottom: 1px solid var(--border-color);
        }

        .np-item {
            display: flex; align-items: flex-start; gap: 0;
            padding: 0; border-bottom: 1px solid var(--border-color);
            cursor: pointer; position: relative; transition: background 0.1s;
            overflow: hidden;
        }
        .np-item:last-child { border-bottom: none; }
        .np-item:hover { background: var(--bg-hover); }

        .np-item-bar { width: 3px; align-self: stretch; flex-shrink: 0; background: var(--border-color); transition: background 0.15s; }
        .np-item.unread .np-item-bar { background: var(--brand-green); }
        .np-item.unread.acc-red .np-item-bar { background: #dc2626; }
        .np-item.unread.acc-amber .np-item-bar { background: #f07d00; }
        .np-item.unread.acc-green .np-item-bar { background: #10b981; }
        .np-item.unread.acc-blue .np-item-bar { background: #3b82f6; }

        .np-item-inner { flex: 1; padding: 12px 14px; min-width: 0; }

        .np-item-top {
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 4px; flex-wrap: wrap;
        }
        .np-item-title {
            font-size: 13px; font-weight: 700;
            color: var(--text-primary); letter-spacing: -0.2px;
            flex: 1; min-width: 0;
        }

        .np-tag {
            font-size: 9px; font-weight: 700; padding: 2px 8px;
            border-radius: 12px; text-transform: uppercase;
            letter-spacing: 0.3px; flex-shrink: 0;
        }
        .np-tag-red { background: #fee2e2; color: #dc2626; }
        .np-tag-amber { background: #fef3c7; color: #b45309; }
        .np-tag-green { background: #d1fae5; color: #065f46; }
        .np-tag-blue { background: #dbeafe; color: #1d4ed8; }
        .np-tag-gray { background: var(--border-color); color: var(--text-muted); }

        [data-theme="dark"] .np-tag-red { background: rgba(220,38,38,0.18); color: #fca5a5; }
        [data-theme="dark"] .np-tag-amber { background: rgba(217,119,6,0.18); color: #fcd34d; }
        [data-theme="dark"] .np-tag-blue { background: rgba(29,78,216,0.22); color: #93c5fd; }
        [data-theme="dark"] .np-tag-green { background: rgba(6,95,70,0.22); color: #6ee7b7; }

        .np-item-time {
            font-size: 10px; font-weight: 500;
            color: var(--text-muted); font-variant-numeric: tabular-nums;
            flex-shrink: 0;
        }

        .np-unread-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--brand-green); flex-shrink: 0;
        }
        .np-item.acc-red .np-unread-dot { background: #dc2626; }
        .np-item.acc-amber .np-unread-dot { background: #f07d00; }
        .np-item.acc-blue .np-unread-dot { background: #3b82f6; }

        .np-item-msg {
            font-size: 12px; line-height: 1.45;
            color: var(--text-secondary); margin: 6px 0 8px;
        }
        .np-item-msg strong { font-weight: 700; color: var(--text-primary); }
        .np-item-msg .stock-critical { color: #dc2626; font-weight: 700; }
        .np-item-msg .stock-warning { color: #f07d00; font-weight: 700; }

        .np-item-actions { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 4px; }
        .np-cta-primary, .np-cta-secondary {
            font-size: 11px; font-weight: 600;
            padding: 4px 12px; border-radius: 20px;
            cursor: pointer; transition: all 0.2s;
            font-family: inherit; text-decoration: none;
            display: inline-flex; align-items: center; gap: 5px;
            border: none;
        }
        .np-cta-primary { background: var(--brand-green); color: #fff; }
        .np-cta-primary:hover { background: var(--brand-green-light); transform: translateY(-1px); }
        .np-cta-primary.danger { background: #dc2626; }
        .np-cta-primary.danger:hover { background: #b91c1c; }
        .np-cta-secondary { background: transparent; border: 1px solid var(--border-color); color: var(--text-secondary); }
        .np-cta-secondary:hover { border-color: var(--brand-green); color: var(--brand-green); background: var(--brand-green-xlight); }

        .np-empty { padding: 40px 20px; text-align: center; }
        .np-empty-icon { width: 44px; height: 44px; border-radius: 14px; background: var(--bg-hover); display: flex; align-items: center; justify-content: center; font-size: 20px; color: var(--text-muted); margin: 0 auto 10px; }
        .np-empty-title { font-size: 12.5px; font-weight: 600; color: var(--text-secondary); margin-bottom: 3px; }
        .np-empty-sub { font-size: 11px; color: var(--text-muted); }

        .np-footer { padding: 9px 16px; border-top: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: var(--bg-card); }
        .np-footer-btn { font-size: 11px; font-weight: 600; color: var(--text-muted); background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 5px; padding: 5px 14px; border-radius: 8px; transition: all 0.15s; font-family: inherit; }
        .np-footer-btn:hover { background: var(--bg-hover); color: var(--brand-green); }

        #toast-container {
            position: fixed; bottom: 20px; right: 20px;
            z-index: 99999; display: flex; flex-direction: column-reverse;
            gap: 8px; pointer-events: none; max-width: 340px; width: 100%;
        }
        .jr-toast {
            background: var(--bg-card); border: 1px solid var(--border-color);
            border-radius: 12px; overflow: hidden; pointer-events: all;
            transform: translateX(calc(100% + 32px)); opacity: 0;
            transition: transform 0.3s cubic-bezier(.34,1.3,.64,1), opacity 0.22s ease;
            box-shadow: 0 8px 24px rgba(0,0,0,0.10);
        }
        .jr-toast.show { transform: translateX(0); opacity: 1; }
        .jr-toast-stripe { height: 3px; }
        .jr-toast.t-success .jr-toast-stripe { background: linear-gradient(90deg, #10b981, #34d399); }
        .jr-toast.t-warning .jr-toast-stripe { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
        .jr-toast.t-urgent .jr-toast-stripe { background: linear-gradient(90deg, #dc2626, #f87171); }
        .jr-toast.t-info .jr-toast-stripe { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
        .jr-toast.t-default .jr-toast-stripe { background: linear-gradient(90deg, var(--brand-green), var(--brand-green-light)); }
        .jr-toast-body { display: flex; flex-direction: column; padding: 10px 36px 10px 12px; gap: 2px; position: relative; }
        .jr-toast-title { font-size: 12px; font-weight: 700; color: var(--text-primary); }
        .jr-toast-time { font-size: 9px; color: var(--text-muted); font-variant-numeric: tabular-nums; position: absolute; top: 10px; right: 12px; }
        .jr-toast-msg { font-size: 11px; color: var(--text-secondary); line-height: 1.4; }
        .jr-toast-link { font-size: 10.5px; font-weight: 600; color: var(--brand-green); text-decoration: none; margin-top: 4px; display: inline-flex; align-items: center; gap: 3px; }
        .jr-toast-close { position: absolute; top: 8px; right: 8px; background: none; border: none; cursor: pointer; width: 18px; height: 18px; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 11px; opacity: 0; transition: opacity 0.12s; }
        .jr-toast:hover .jr-toast-close { opacity: 1; }
        .jr-toast-prog { height: 2px; background: var(--border-color); }
        .jr-toast-prog-bar { height: 100%; transform-origin: left; animation: toast-prog linear forwards; background: var(--brand-green); }
        @keyframes toast-prog { from { transform: scaleX(1); } to { transform: scaleX(0); } }
        /* ════════════════════════════════════════════════════════════
   TECHNICIEN SAV — STYLES PREMIUM
   ════════════════════════════════════════════════════════════ */

/* Badges de statut améliorés */
.tech-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.tech-badge-pending { background: #fef3c7; color: #d97706; }
.tech-badge-assigned { background: #dbeafe; color: #2563eb; }
.tech-badge-diagnosing { background: #fff4e6; color: #f07d00; }
.tech-badge-repairing { background: #e0e7ff; color: #4338ca; }
.tech-badge-completed { background: #d1fae5; color: #065f46; }
.tech-badge-restituted { background: #f3f4f6; color: #6b7280; }

.tech-badge-priority-low { background: #f3f4f6; color: #6b7280; }
.tech-badge-priority-medium { background: #dbeafe; color: #2563eb; }
.tech-badge-priority-high { background: #fef3c7; color: #d97706; }
.tech-badge-priority-critical { background: #fee2e2; color: #dc2626; }

/* Cartes de statistiques premium */
.tech-stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 20px 24px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.tech-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--brand-green), var(--brand-green-light));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.tech-stat-card:hover::before {
    opacity: 1;
}

.tech-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
    border-color: var(--brand-green);
}

.tech-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    background: var(--bg-page);
    color: var(--brand-green);
    margin-bottom: 12px;
    transition: all 0.3s ease;
}

.tech-stat-card:hover .tech-stat-icon {
    background: var(--brand-green-xlight);
    transform: scale(1.05);
}

.tech-stat-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    margin-bottom: 4px;
}

.tech-stat-value {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.2;
}

.tech-stat-change {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    font-weight: 600;
    margin-top: 8px;
    padding: 2px 10px;
    border-radius: 20px;
}

.tech-stat-change.up { background: rgba(16, 185, 129, 0.1); color: #10b981; }
.tech-stat-change.down { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

/* Table premium */
.tech-table-wrap {
    overflow: hidden;
    border-radius: 16px;
    border: 1px solid var(--border-color);
    background: var(--bg-card);
}

.tech-table {
    width: 100%;
    border-collapse: collapse;
}

.tech-table thead {
    background: var(--bg-page);
    border-bottom: 1px solid var(--border-color);
}

.tech-table th {
    padding: 12px 16px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-muted);
    text-align: left;
}

.tech-table td {
    padding: 12px 16px;
    font-size: 12px;
    color: var(--text-primary);
    border-bottom: 1px solid var(--border-color);
    vertical-align: middle;
}

.tech-table tbody tr {
    transition: background 0.15s ease;
}

.tech-table tbody tr:hover {
    background: var(--bg-hover);
}

.tech-table tbody tr:last-child td {
    border-bottom: none;
}

.tech-ref {
    display: inline-block;
    font-family: 'DM Mono', monospace;
    font-size: 11px;
    font-weight: 700;
    color: var(--brand-green);
    background: var(--brand-green-xlight);
    padding: 2px 10px;
    border-radius: 6px;
    letter-spacing: 0.3px;
}

/* Actions buttons */
.tech-actions {
    display: flex;
    gap: 6px;
}

.tech-btn-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border-color);
    background: transparent;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.tech-btn-icon:hover {
    border-color: var(--brand-green);
    color: var(--brand-green);
    background: var(--brand-green-xlight);
    transform: translateY(-1px);
}

.tech-btn-icon.primary:hover {
    background: var(--brand-green);
    color: white;
    border-color: var(--brand-green);
}

.tech-btn-icon.success:hover {
    background: #10b981;
    color: white;
    border-color: #10b981;
}

/* Empty state */
.tech-empty {
    text-align: center;
    padding: 48px 20px;
    color: var(--text-muted);
}

.tech-empty-icon {
    font-size: 40px;
    color: var(--border-color);
    display: block;
    margin-bottom: 12px;
}

.tech-empty-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 4px;
}

.tech-empty-sub {
    font-size: 12px;
}

/* Flash messages */
.tech-flash {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 20px;
    border: 1px solid;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.tech-flash.success {
    background: rgba(16, 185, 129, 0.08);
    border-color: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.tech-flash.error {
    background: rgba(239, 68, 68, 0.08);
    border-color: rgba(239, 68, 68, 0.2);
    color: #dc2626;
}

/* ============================================================
   NOTIFICATIONS — STYLE PREMIUM UNIFIÉ
   ============================================================ */

/* Conteneur principal */
.notif-wrap {
    position: relative;
    display: inline-flex;
    align-items: center;
}

/* Badge de compteur */
.notif-badge {
    position: absolute;
    top: -4px;
    right: -5px;
    min-width: 18px;
    height: 18px;
    background: #ef4444;
    color: #fff;
    font-size: 9px;
    font-weight: 700;
    padding: 0 5px;
    /* border-radius: 99px; */
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid var(--bg-page);
    z-index: 10;
    transition: transform 0.2s cubic-bezier(.34,1.56,.64,1);
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
}
.notif-badge.bump {
    animation: badge-bump 0.35s cubic-bezier(.34,1.56,.64,1) forwards;
}
@keyframes badge-bump {
    0% { transform: scale(1); }
    55% { transform: scale(1.45); }
    100% { transform: scale(1); }
}

/* Panneau de notification */
.notif-panel {
    position: absolute;
    top: calc(100% + 10px);
    right: -8px;
    width: 420px;
    max-width: calc(100vw - 20px);
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15), 0 4px 16px rgba(0,0,0,0.06);
    z-index: 1050;
    display: flex;
    flex-direction: column;
    max-height: 600px;
    transform-origin: top right;
    transform: scale(0.96) translateY(-6px);
    opacity: 0;
    pointer-events: none;
    transition: transform 0.22s cubic-bezier(.34,1.18,.64,1), opacity 0.18s ease;
    overflow: hidden;
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
}
.notif-panel.open {
    transform: scale(1) translateY(0);
    opacity: 1;
    pointer-events: all;
}

/* En-tête */
.np-header {
    padding: 16px 18px 0;
    background: var(--bg-card);
    flex-shrink: 0;
    border-bottom: 1px solid var(--border-color);
}
.np-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}
.np-title-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}
.np-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--text-primary);
    letter-spacing: -0.3px;
}
.np-live {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 2px 10px;
    border-radius: 20px;
    background: rgba(34,197,94,0.1);
    border: 1px solid rgba(34,197,94,0.2);
}
.np-live-dot {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: #22c55e;
    animation: live-pulse 1.8s ease-in-out infinite;
}
@keyframes live-pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
}
.np-live-label {
    font-size: 9px;
    font-weight: 600;
    color: #16a34a;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}
.np-header-actions {
    display: flex;
    align-items: center;
    gap: 6px;
}
.np-btn-ghost {
    height: 30px;
    padding: 0 12px;
    border-radius: 8px;
    border: 1px solid var(--border-color);
    background: transparent;
    color: var(--text-muted);
    font-size: 11px;
    font-weight: 500;
    font-family: inherit;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    transition: all 0.15s;
    white-space: nowrap;
}
.np-btn-ghost:hover {
    background: var(--bg-hover);
    border-color: var(--brand-green);
    color: var(--brand-green);
}
.np-icon-btn {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: transparent;
    border: 1px solid var(--border-color);
    color: var(--text-muted);
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s;
}
.np-icon-btn:hover {
    background: var(--bg-hover);
    border-color: var(--brand-green);
    color: var(--brand-green);
}

/* Chips de filtre */
.np-chips-row {
    display: flex;
    gap: 6px;
    padding: 10px 18px;
    overflow-x: auto;
    scrollbar-width: none;
    border-bottom: 1px solid var(--border-color);
    flex-shrink: 0;
    background: var(--bg-card);
}
.np-chips-row::-webkit-scrollbar { display: none; }
.np-chip {
    white-space: nowrap;
    font-size: 11px;
    font-weight: 500;
    padding: 5px 14px;
    border-radius: 20px;
    border: 1px solid var(--border-color);
    background: none;
    cursor: pointer;
    color: var(--text-secondary);
    font-family: inherit;
    transition: all 0.13s;
}
.np-chip:hover {
    border-color: var(--brand-green);
    color: var(--brand-green);
}
.np-chip.active {
    background: var(--brand-green);
    color: white;
    border-color: var(--brand-green);
}

/* Liste */
.np-list {
    flex: 1;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: var(--border-color) transparent;
}
.np-list::-webkit-scrollbar { width: 4px; }
.np-list::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 99px; }

/* Séparateur de date */
.np-date-label {
    padding: 8px 18px 6px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--brand-green);
    background: var(--bg-card);
    position: sticky;
    top: 0;
    z-index: 5;
    border-bottom: 1px solid var(--border-color);
}

/* Élément de notification */
.np-item {
    display: flex;
    align-items: stretch;
    gap: 0;
    padding: 0;
    border-bottom: 1px solid var(--border-color);
    cursor: pointer;
    position: relative;
    transition: background 0.12s;
    overflow: hidden;
}
.np-item:last-child { border-bottom: none; }
.np-item:hover { background: var(--bg-hover); }

.np-item-bar {
    width: 4px;
    align-self: stretch;
    flex-shrink: 0;
    background: var(--border-color);
    transition: background 0.15s;
}
.np-item.unread .np-item-bar { background: var(--brand-green); }
.np-item.acc-red .np-item-bar { background: #dc2626; }
.np-item.acc-amber .np-item-bar { background: #f07d00; }
.np-item.acc-green .np-item-bar { background: #10b981; }
.np-item.acc-blue .np-item-bar { background: #3b82f6; }

.np-item-inner {
    flex: 1;
    padding: 12px 16px;
    min-width: 0;
}

.np-item-top {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
    flex-wrap: wrap;
}
.np-item-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-primary);
    letter-spacing: -0.2px;
    flex: 1;
    min-width: 0;
}
.np-item.unread .np-item-title {
    font-weight: 700;
}

.np-tag {
    font-size: 9px;
    font-weight: 700;
    padding: 2px 10px;
    border-radius: 12px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    flex-shrink: 0;
}
.np-tag-red { background: #fee2e2; color: #dc2626; }
.np-tag-amber { background: #fef3c7; color: #b45309; }
.np-tag-green { background: #d1fae5; color: #065f46; }
.np-tag-blue { background: #dbeafe; color: #1d4ed8; }
.np-tag-gray { background: var(--border-color); color: var(--text-muted); }

[data-theme="dark"] .np-tag-red { background: rgba(220,38,38,0.18); color: #fca5a5; }
[data-theme="dark"] .np-tag-amber { background: rgba(217,119,6,0.18); color: #fcd34d; }
[data-theme="dark"] .np-tag-blue { background: rgba(29,78,216,0.22); color: #93c5fd; }
[data-theme="dark"] .np-tag-green { background: rgba(6,95,70,0.22); color: #6ee7b7; }

.np-item-time {
    font-size: 10px;
    font-weight: 500;
    color: var(--text-muted);
    font-variant-numeric: tabular-nums;
    flex-shrink: 0;
}

.np-unread-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--brand-green);
    flex-shrink: 0;
}
.np-item.acc-red .np-unread-dot { background: #dc2626; }
.np-item.acc-amber .np-unread-dot { background: #f07d00; }
.np-item.acc-blue .np-unread-dot { background: #3b82f6; }

.np-item-msg {
    font-size: 12px;
    line-height: 1.5;
    color: var(--text-secondary);
    margin: 6px 0 8px;
    padding: 6px 10px;
    background: var(--bg-page);
    border-radius: 6px;
    border-left: 3px solid var(--border-color);
}
.np-item.unread .np-item-msg {
    border-left-color: var(--brand-green);
}
.np-item-msg strong {
    font-weight: 600;
    color: var(--text-primary);
}

.np-item-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 4px;
}
.np-cta-primary, .np-cta-secondary {
    font-size: 11px;
    font-weight: 600;
    padding: 4px 14px;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.2s;
    font-family: inherit;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border: none;
}
.np-cta-primary {
    background: var(--brand-green);
    color: #fff;
}
.np-cta-primary:hover {
    background: var(--brand-green-light);
    transform: translateY(-1px);
    color: #fff;
}
.np-cta-primary.danger {
    background: #dc2626;
}
.np-cta-primary.danger:hover {
    background: #b91c1c;
}
.np-cta-secondary {
    background: transparent;
    border: 1px solid var(--border-color);
    color: var(--text-secondary);
}
.np-cta-secondary:hover {
    border-color: var(--brand-green);
    color: var(--brand-green);
    background: var(--brand-green-xlight);
}

/* Pied de page */
.np-footer {
    padding: 10px 16px;
    border-top: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: var(--bg-card);
}
.np-footer-btn {
    font-size: 11px;
    font-weight: 600;
    color: var(--text-muted);
    background: none;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 6px 16px;
    border-radius: 8px;
    transition: all 0.15s;
    font-family: inherit;
}
.np-footer-btn:hover {
    background: var(--bg-hover);
    color: var(--brand-green);
}

/* État vide */
.np-empty {
    padding: 40px 20px;
    text-align: center;
}
.np-empty-icon {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    background: var(--bg-hover);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: var(--text-muted);
    margin: 0 auto 10px;
}
.np-empty-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 3px;
}
.np-empty-sub {
    font-size: 11px;
    color: var(--text-muted);
}

/* Animation de la cloche */
.bell-ring {
    animation: bell-shake 0.48s cubic-bezier(.36,.07,.19,.97) both;
}
@keyframes bell-shake {
    10%, 90% { transform: rotate(-4deg); }
    30%, 70% { transform: rotate(-9deg); }
    40%, 60% { transform: rotate(9deg); }
    100% { transform: rotate(0); }
}

/* Responsive */
@media (max-width: 576px) {
    .notif-panel {
        right: -60px;
        width: calc(100vw - 20px);
        max-height: 80vh;
    }
    .np-header-row {
        flex-wrap: wrap;
        gap: 6px;
    }
    .np-title {
        font-size: 13px;
    }
    .np-chip {
        font-size: 10px;
        padding: 4px 10px;
    }
    .np-item-title {
        font-size: 12px;
    }
    .np-item-msg {
        font-size: 11px;
    }
    /* Dans app.blade.php, ajouter ces styles */
.np-tag-urgent {
    background: #dc2626;
    color: white;
    animation: pulse-urgent 1.5s ease-in-out infinite;
}

@keyframes pulse-urgent {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(1.05); }
}

[data-theme="dark"] .np-tag-urgent {
    background: rgba(220, 38, 38, 0.3);
    color: #fca5a5;
}
}
    </style>
</head>
<body data-user-role="{{ auth()->user()->getRoleNames()->first() }}" data-user-id="{{ auth()->id() }}">

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
            @can('view customers')
        <a href="{{ route('module3.customers.index') }}" class="nav-item {{ request()->routeIs('module3.customers.*') ? 'active' : '' }}" data-tip="Clients">
            <i class="bi bi-people nav-icon"></i>
            <span class="nav-label">Clients</span>
        </a>
    @endcan

            @php
                $hasVentesPermission = auth()->user()->can('view customers')
                                    || auth()->user()->can('view quotes')
                                    || auth()->user()->can('view invoices')
                                    || auth()->user()->can('use pos');
            @endphp
            @if($hasVentesPermission)
                <div x-data="{ openVentes: {{ request()->routeIs('module3.*') ? 'true' : 'false' }} }">
                    @can('view invoices')<div class="nav-item-parent" @click="openVentes = !openVentes" :class="{ 'active': openVentes }" data-tip="Ventes">
                        <i class="bi bi-cart3 nav-icon"></i>
                        <span class="nav-label">Ventes</span>
                        <i class="bi bi-chevron-down nav-arrow" :class="{ 'rotated': openVentes }"></i>
                    </div>@endcan
                    <div x-show="openVentes" x-transition.duration.200ms class="nav-children">
                        @can('view quotes')
                            <a href="{{ route('module3.quotes.index') }}" class="nav-item child {{ request()->routeIs('module3.quotes.*') ? 'active' : '' }}" data-tip="Devis">
                                <i class="bi bi-file-earmark-text nav-icon"></i><span class="nav-label">Proforma</span>
                            </a>
                        @endcan
                        @can('view invoices')
                            <a href="{{ route('module3.invoices.index') }}" class="nav-item child {{ request()->routeIs('module3.invoices.*') ? 'active' : '' }}" data-tip="Factures">
                                <i class="bi bi-receipt nav-icon"></i><span class="nav-label">Factures</span>
                            </a>
                        @endcan
                        @can('use pos')
                            <a href="{{ route('module3.pos.index') }}" class="nav-item child {{ request()->routeIs('module3.pos.*') ? 'active' : '' }}" data-tip="Point de vente">
                                <i class="bi bi-cash-coin"></i><span class="nav-label">POS</span>
                            </a>
                        @endcan
                    </div>
                </div>
            @endif

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
                        @can('create sav tickets')
                            <a href="{{ route('module5.tickets.index') }}" class="nav-item child {{ request()->routeIs('module5.tickets.*') ? 'active' : '' }}" data-tip="Tickets SAV">
                                <i class="bi bi-ticket-perforated nav-icon"></i><span class="nav-label">Tickets SAV</span>
                            </a>
                        @endcan
                         @auth
                          @if(auth()->user()->hasRole('technicien_sav'))
                            <a href="{{ route('technician.dashboard') }}" class="nav-item child {{ request()->routeIs('technician.*') ? 'active' : '' }}" data-tip="Mon espace SAV">
                            <i class="bi bi-wrench nav-icon"></i>
                            <span class="nav-label">Mon Atelier SAV</span>
                               </a>
                          @endif
                        @endauth
                        @can('use sav parts')
                            <a href="{{ route('module5.parts.index') }}" class="nav-item child {{ request()->routeIs('module5.parts.*') ? 'active' : '' }}" data-tip="Pièces détachées">
                                <i class="bi bi-puzzle nav-icon"></i><span class="nav-label">Pièces de rechange</span>
                            </a>
                        @endcan
                    </div>
                </div>
            @endif

            @can('view users')
                <div class="nav-section-label">Configuration</div>
                <a href="{{ route('users.index') }}" class="nav-item" data-tip="Utilisateurs">
                    <i class="bi bi-people nav-icon"></i>
                    <span class="nav-label">Utilisateurs</span>
                </a>
            @endcan
        </nav>

        <div class="sidebar-footer">
            <div class="dropdown">
                <div class="sidebar-user dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-box-arrow-right" style="font-size: 25px; color: #ef4444;"></i>
                    <div class="user-info">
                        <div class="logout-text">Déconnexion</div>
                        <div class="user-role">
                            @if(auth()->user()->hasRole('admin')) Administrateur
                            @elseif(auth()->user()->hasRole('manager')) Manager
                            @elseif(auth()->user()->hasRole('vendeur')) Commercial
                            @elseif(auth()->user()->hasRole('technicien_sav')) Technicien SAV
                            @else Utilisateur
                            @endif
                        </div>
                    </div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
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
    <div id="toast-container"></div>

    <div class="main-content" id="mainContent">
        <header class="topbar">
            <button class="icon-btn d-md-none" id="mobileMenuBtn" aria-label="Menu" style="background:green">
                <i class="bi bi-list"></i>
            </button>
            <div class="topbar-title" id="pageTitle">
                @php
                    $hour = now()->hour;
                    $greeting = $hour >= 5 && $hour < 12 ? 'Bonjour' : ($hour >= 12 && $hour < 18 ? 'Bon après-midi' : ($hour >= 18 && $hour < 22 ? 'Bonsoir' : 'Bonne nuit'));
                @endphp
                <h2 style="font-size: 16px; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <span>{{ $greeting }} <span class="greeting-wave">👋</span>, M.{{ Auth::user()->name ?? 'Invité' }}</span>
                </h2>
            </div>

            <div class="topbar-actions">
                <button class="theme-toggle" id="themeToggle" aria-label="Changer le thème">
                    <i class="bi bi-moon" id="themeIcon"></i>
                </button>

                <div class="notif-wrap" id="notifWrap">
                    <button class="icon-btn" id="notifBtn" aria-label="Notifications" aria-expanded="false">
                        <i class="bi bi-bell" id="notifBellIcon"></i>
                        <span class="notif-badge" id="notifBadge" style="display:none;">0</span>
                    </button>

                    <div class="notif-panel" id="notifPanel" role="dialog" aria-label="Centre de notifications">
                        <div class="np-header">
                            <div class="np-header-row">
                                <div class="np-title-wrap">
                                    <span class="np-title">Notifications</span>
                                </div>
                                <div class="np-header-actions">
    <button class="np-btn-ghost" id="selectNotifBtn" title="Sélectionner">
        <i class="bi bi-check2-square"></i> Supprimer
    </button>
    <button class="np-btn-ghost" id="deleteNotifBtn" style="display:none; color: #dc2626; border-color: #dc2626;" title="Supprimer la sélection">
        <i class="bi bi-trash3"></i> <span id="selectedCount">0</span>
    </button>
    <button class="np-btn-ghost" id="markAllReadBtn">
        <i class="bi bi-check2-all"></i> Tout lire
    </button>
    <button class="np-icon-btn" id="refreshNotifBtn" title="Actualiser">
        <i class="bi bi-arrow-repeat" id="npRefreshIcon"></i>
    </button>
</div>
                            </div>
                        </div>

                        <div class="np-chips-row" id="npChips">
                            <button class="np-chip active" data-subfilter="all">Tout</button>
                            <button class="np-chip" data-subfilter="unread">Non lues</button>
                            <button class="np-chip" data-subfilter="tickets">Tickets</button>
                            <button class="np-chip" data-subfilter="alertes">Alertes</button>
                        </div>

                        <div class="np-list" id="notificationsList">
                            <div class="np-empty">
                                <div class="np-empty-icon"><i class="bi bi-bell-slash"></i></div>
                                <div class="np-empty-title">Aucune notification</div>
                                <div class="np-empty-sub">Les notifications apparaîtront ici</div>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="dropdown">
                    <div class="user-chip dropdown-toggle" data-bs-toggle="dropdown">
                        @if(Auth::user()->avatar)
                            <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="Avatar" width="25" height="22" style="border-radius: 50%; object-fit: cover;">
                        @else
                            <div class="chip-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}</div>
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

<script id="notif-js-v6">
(function() {
    'use strict';

    // ============================================================
    // 1. ÉTAT
    // ============================================================
    const state = {
        all: [],
        filtered: [],
        unreadCount: 0,
        currentFilter: 'all',
        isOpen: false,
        isLoading: false,
        lastRendered: null,
        panelProtected: false,
        selectMode: false,
        selectedIds: new Set(),
    };

    // ============================================================
    // 2. RÉFÉRENCES DOM
    // ============================================================
    const dom = {
        btn: document.getElementById('notifBtn'),
        panel: document.getElementById('notifPanel'),
        bell: document.getElementById('notifBellIcon'),
        badge: document.getElementById('notifBadge'),
        list: document.getElementById('notificationsList'),
        chips: document.querySelectorAll('.np-chip'),
        refreshBtn: document.getElementById('refreshNotifBtn'),
        markAllBtn: document.getElementById('markAllReadBtn'),
        viewAllBtn: document.getElementById('viewAllNotifBtn'),
        refreshIcon: document.getElementById('npRefreshIcon'),
        selectBtn: document.getElementById('selectNotifBtn'),
        deleteBtn: document.getElementById('deleteNotifBtn'),
        selectedCount: document.getElementById('selectedCount'),
    };

    // Sauvegarder le contenu initial du panneau (pour restauration)
    let savedContent = dom.list ? dom.list.innerHTML : '';

    // ✅ Vérifier si une notification vient d'être marquée comme lue
    (function checkRecentRead() {
        const justReadId = sessionStorage.getItem('just_read_notification');
        const savedCount = sessionStorage.getItem('unread_count_after_read');
        const timestamp = sessionStorage.getItem('read_timestamp');
        
        // Ne restaurer que si l'opération est récente (< 5 secondes)
        if (justReadId && savedCount !== null && timestamp) {
            const age = Date.now() - parseInt(timestamp);
            if (age < 5000) {
                state.unreadCount = parseInt(savedCount);
                updateBadge();
                
                // Nettoyer après restauration
                sessionStorage.removeItem('just_read_notification');
                sessionStorage.removeItem('unread_count_after_read');
                sessionStorage.removeItem('read_timestamp');
            }
        }
    })();

    // ============================================================
    // 3. FONCTIONS UTILITAIRES
    // ============================================================
    function formatTime(dateStr) {
        if (!dateStr) return '';
        const diff = Date.now() - new Date(dateStr).getTime();
        if (diff < 60000) return 'À l\'instant';
        if (diff < 3600000) return Math.floor(diff / 60000) + ' min';
        if (diff < 86400000) return Math.floor(diff / 3600000) + ' h';
        const days = Math.floor(diff / 86400000);
        return days === 1 ? 'Hier' : days + ' j';
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    function getCategory(type) {
        if (['ticket_assigned', 'ticket_closed', 'urgent', 'ticket_urgent'].includes(type)) return 'tickets';
        if (['invoice_created', 'invoice_paid', 'purchase_order_created'].includes(type)) return 'commandes';
        if (['low_stock', 'critical_part'].includes(type)) return 'alertes';
        return 'other';
    }

    function getBadgeHtml(type) {
        const map = {
            urgent: { label: 'URGENT', class: 'np-tag-red' },
            ticket_urgent: { label: 'URGENT', class: 'np-tag-red' },
            critical_part: { label: 'CRITIQUE', class: 'np-tag-red' },
            low_stock: { label: 'ALERTE', class: 'np-tag-amber' },
            ticket_assigned: { label: 'NOUVEAU', class: 'np-tag-blue' },
            ticket_closed: { label: 'TERMINÉ', class: 'np-tag-green' },
            invoice_paid: { label: 'PAYÉ', class: 'np-tag-green' },
        };
        const t = map[type];
        return t ? `<span class="np-tag ${t.class}">${t.label}</span>` : '';
    }

    function getIconHtml(type) {
        const map = {
            low_stock: 'bi-exclamation-triangle-fill',
            critical_part: 'bi-puzzle-fill',
            urgent: 'bi-alarm-fill',
            ticket_urgent: 'bi-alarm-fill',
            ticket_assigned: 'bi-person-plus-fill',
            ticket_closed: 'bi-check-circle-fill',
            invoice_created: 'bi-file-earmark-text-fill',
            invoice_paid: 'bi-cash-stack',
        };
        const icon = map[type] || 'bi-bell-fill';
        const color = ['urgent', 'ticket_urgent', 'critical_part'].includes(type) ? '#dc2626' :
                      type === 'low_stock' ? '#f07d00' :
                      type === 'ticket_closed' ? '#10b981' :
                      type === 'ticket_assigned' ? '#3b82f6' : '#94a89e';
        return `<i class="bi ${icon}" style="color:${color};font-size:16px;"></i>`;
    }

    function renderActions(n) {
        const links = n.action_links || [];
        if (!links.length) return '';

        let html = '<div class="np-item-actions">';
        links.forEach(link => {
            const isPrimary = link.label.includes('Commander') || link.label.includes('Voir');
            const cls = isPrimary ? 'np-cta-primary' : 'np-cta-secondary';
            const danger = link.label.includes('Urgent') ? ' danger' : '';
            html += `
                <a href="${escapeHtml(link.url)}" class="${cls}${danger}" onclick="event.stopPropagation();">
                    ${link.label} →
                </a>
            `;
        });
        html += '</div>';
        return html;
    }

    // ============================================================
    // 4. GÉNÉRATION D'UN ÉLÉMENT (avec checkbox si selectMode)
    // ============================================================
    function generateItemHtml(n) {
        const url = n.action_url || '#';
        const time = n.time_ago || formatTime(n.created_at);
        const isUnread = !n.is_read;
        const accentClass = n.priority === 'critical' ? 'acc-red' :
                           n.type === 'low_stock' ? 'acc-amber' :
                           n.type === 'ticket_closed' ? 'acc-green' :
                           n.type === 'ticket_assigned' ? 'acc-blue' : '';

        const checkboxHtml = state.selectMode ? `
            <div style="display:flex;align-items:center;padding-left:12px;flex-shrink:0;">
                <input type="checkbox" class="np-select-checkbox" data-id="${n.id}" 
                       style="width:16px;height:16px;accent-color:var(--brand-green);cursor:pointer;">
            </div>
        ` : '';

        return `
            <div class="np-item ${isUnread ? 'unread' : ''} ${accentClass}" 
                 data-id="${n.id}" 
                 data-url="${escapeHtml(url)}"
                 data-type="${n.type}">
                ${checkboxHtml}
                <div class="np-item-bar"></div>
                <div class="np-item-inner">
                    <div class="np-item-top">
                        ${getIconHtml(n.type)}
                        <span class="np-item-title">${escapeHtml(n.title)}</span>
                        ${getBadgeHtml(n.type)}
                        <span class="np-item-time">${time}</span>
                        ${isUnread ? '<span class="np-unread-dot"></span>' : ''}
                    </div>
                    <div class="np-item-msg">${n.message_html || escapeHtml(n.message)}</div>
                    ${renderActions(n)}
                </div>
            </div>
        `;
    }

    // ============================================================
    // 5. FILTRAGE
    // ============================================================
    function applyFilters() {
        let list = [...state.all];
        if (state.currentFilter === 'unread') {
            list = list.filter(n => !n.is_read);
        } else if (state.currentFilter === 'tickets') {
            list = list.filter(n => getCategory(n.type) === 'tickets');
        } else if (state.currentFilter === 'commandes') {
            list = list.filter(n => getCategory(n.type) === 'commandes');
        } else if (state.currentFilter === 'alertes') {
            list = list.filter(n => getCategory(n.type) === 'alertes');
        }
        state.filtered = list;
        renderList();
    }

    // ============================================================
    // 6. RENDU PROTÉGÉ (avec checkboxes)
    // ============================================================
    function renderList() {
        if (!dom.list) return;

        if (state.panelProtected && !state.isOpen) {
            return;
        }

        const currentData = state.filtered.slice(0, 30).map(n => n.id).join(',');
        if (state.lastRendered === currentData) {
            return;
        }
        state.lastRendered = currentData;

        let html = '';
        if (state.filtered.length === 0) {
            html = `
                <div class="np-empty">
                    <div class="np-empty-icon"><i class="bi bi-bell-slash"></i></div>
                    <div class="np-empty-title">Aucune notification</div>
                    <div class="np-empty-sub">Les notifications apparaîtront ici</div>
                </div>
            `;
        } else {
            let lastDate = '';
            state.filtered.slice(0, 30).forEach(n => {
                const date = new Date(n.created_at);
                const dateKey = date.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long' });
                if (dateKey !== lastDate) {
                    html += `<div class="np-date-label">${dateKey}</div>`;
                    lastDate = dateKey;
                }
                html += generateItemHtml(n);
            });
        }

        try {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;
            dom.list.innerHTML = '';
            while (tempDiv.firstChild) {
                dom.list.appendChild(tempDiv.firstChild);
            }
            savedContent = dom.list.innerHTML;

            // Gestion des checkboxes
            if (state.selectMode) {
                dom.list.querySelectorAll('.np-select-checkbox').forEach(cb => {
                    cb.checked = state.selectedIds.has(cb.dataset.id);
                    cb.addEventListener('change', function() {
                        const id = this.dataset.id;
                        if (this.checked) {
                            state.selectedIds.add(id);
                        } else {
                            state.selectedIds.delete(id);
                        }
                        updateSelectionUI();
                    });
                });
            }

            // Clic sur les items (sauf en mode sélection)
            if (!state.selectMode) {
                dom.list.querySelectorAll('.np-item').forEach(el => {
                    el.addEventListener('click', function(e) {
                        if (e.target.closest('a[style*="cursor:pointer"]')) return;
                        const id = this.dataset.id;
                        const url = this.dataset.url;
                        if (this.classList.contains('unread')) {
                            markAsRead(id);
                        } else {
                            // Si déjà lu, rediriger directement
                            if (url && url !== '#') {
                                window.location.href = url;
                            }
                        }
                    });
                });
            }
        } catch (e) {
            console.warn('Erreur lors du rendu des notifications :', e);
            if (savedContent) {
                dom.list.innerHTML = savedContent;
            }
        }
    }

    // ============================================================
    // 7. UI DE SÉLECTION
    // ============================================================
    function updateSelectionUI() {
        const count = state.selectedIds.size;
        if (dom.selectedCount) dom.selectedCount.textContent = count;
        if (dom.deleteBtn) {
            dom.deleteBtn.style.display = count > 0 ? 'inline-flex' : 'none';
        }
        if (state.selectMode) {
            document.querySelectorAll('.np-select-checkbox').forEach(cb => {
                cb.checked = state.selectedIds.has(cb.dataset.id);
            });
        }
    }

    // ============================================================
    // 8. ACTIONS API
    // ============================================================
    async function loadFromApi(force = false) {
        if (state.isOpen && !force) {
            try {
                const response = await fetch('/api/activities');
                if (!response.ok) throw new Error('Erreur réseau');
                const data = await response.json();
                if (data.success) {
                    state.unreadCount = data.unread_count || 0;
                    updateBadge();
                    state.all = data.activities || [];
                }
            } catch (e) { console.error('Erreur chargement badge :', e); }
            return;
        }

        if (!state.isOpen && !force) {
            try {
                const response = await fetch('/api/activities');
                if (!response.ok) throw new Error('Erreur réseau');
                const data = await response.json();
                if (data.success) {
                    state.unreadCount = data.unread_count || 0;
                    updateBadge();
                    state.all = data.activities || [];
                }
            } catch (e) { console.error('Erreur chargement badge :', e); }
            return;
        }

        if (state.isLoading) return;
        state.isLoading = true;

        try {
            const response = await fetch('/api/activities');
            if (!response.ok) throw new Error('Erreur réseau');
            const data = await response.json();
            if (data.success) {
                // ✅ Vérifier si une notification vient d'être marquée comme lue
                const justReadId = sessionStorage.getItem('just_read_notification');
                const savedCount = sessionStorage.getItem('unread_count_after_read');
                
                if (justReadId && savedCount !== null) {
                    // Utiliser le compteur sauvegardé
                    state.unreadCount = parseInt(savedCount);
                    // Nettoyer le sessionStorage
                    sessionStorage.removeItem('just_read_notification');
                    sessionStorage.removeItem('unread_count_after_read');
                    sessionStorage.removeItem('read_timestamp');
                } else {
                    state.unreadCount = data.unread_count || 0;
                }
                
                state.all = data.activities || [];
                updateBadge();
                state.lastRendered = null;
                state.selectedIds.clear();
                state.panelProtected = false;
                applyFilters();
                state.panelProtected = true;
                updateSelectionUI();
            }
        } catch (error) {
            console.error('Erreur chargement notifications :', error);
            dom.list.innerHTML = `
                <div class="np-empty" style="padding:20px;">
                    <div class="np-empty-icon"><i class="bi bi-exclamation-triangle" style="color:#dc2626;"></i></div>
                    <div class="np-empty-title">Erreur de chargement</div>
                    <div class="np-empty-sub">Impossible de récupérer les notifications.</div>
                </div>
            `;
        } finally {
            state.isLoading = false;
        }
    }

    /**
     * ✅ Marque une notification comme lue avec persistance
     */
    async function markAsRead(id) {
        try {
            // ✅ 1. Appel API avec gestion d'erreur
            const response = await fetch(`/api/activities/${id}/read`, { 
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                // ✅ 2. Mise à jour locale
                const item = state.all.find(n => n.id === id);
                if (item) {
                    item.is_read = true;
                    state.unreadCount = Math.max(0, state.unreadCount - 1);
                    updateBadge();
                    state.lastRendered = null;
                    state.panelProtected = false;
                    applyFilters();
                    state.panelProtected = true;
                    
                    // ✅ 3. Stocker l'état pour persistance après navigation
                    sessionStorage.setItem('just_read_notification', id);
                    sessionStorage.setItem('unread_count_after_read', state.unreadCount);
                    sessionStorage.setItem('read_timestamp', Date.now());
                    
                    // ✅ 4. Récupérer l'URL de redirection
                    const itemElement = document.querySelector(`.np-item[data-id="${id}"]`);
                    const url = itemElement?.dataset?.url || '#';
                    
                    // ✅ 5. Redirection différée
                    if (url && url !== '#') {
                        // Animation de feedback
                        if (itemElement) {
                            itemElement.style.transition = 'background 0.3s ease';
                            itemElement.style.background = 'var(--brand-green-xlight)';
                            setTimeout(() => {
                                itemElement.style.background = '';
                            }, 300);
                        }
                        
                        setTimeout(() => {
                            window.location.href = url;
                        }, 200);
                    }
                }
            } else {
                console.error('Erreur API :', result.message);
                // ✅ Fallback : rediriger quand même
                const itemElement = document.querySelector(`.np-item[data-id="${id}"]`);
                const url = itemElement?.dataset?.url || '#';
                if (url && url !== '#') {
                    setTimeout(() => {
                        window.location.href = url;
                    }, 100);
                }
            }
        } catch (error) {
            console.error('Erreur lors du marquage comme lu :', error);
            
            // ✅ Fallback : si l'API échoue, quand même effectuer la navigation
            const itemElement = document.querySelector(`.np-item[data-id="${id}"]`);
            const url = itemElement?.dataset?.url || '#';
            if (url && url !== '#') {
                setTimeout(() => {
                    window.location.href = url;
                }, 100);
            }
        }
    }

    async function markAllAsRead() {
        try {
            await fetch('/api/activities/read-all', { method: 'POST' });
            state.all.forEach(n => n.is_read = true);
            state.unreadCount = 0;
            updateBadge();
            state.lastRendered = null;
            state.panelProtected = false;
            applyFilters();
            state.panelProtected = true;
            
            // ✅ Nettoyer le sessionStorage
            sessionStorage.removeItem('just_read_notification');
            sessionStorage.removeItem('unread_count_after_read');
            sessionStorage.removeItem('read_timestamp');
        } catch (error) {
            console.error('Erreur tout marquer lu :', error);
        }
    }

    async function deleteSelected() {
        if (state.selectedIds.size === 0) return;
        if (!confirm(`Supprimer ${state.selectedIds.size} notification(s) sélectionnée(s) ?`)) return;

        try {
            const ids = Array.from(state.selectedIds);
            const response = await axios.delete('/api/activities', {
                data: { ids: ids }
            });
            if (response.data.success) {
                state.all = state.all.filter(n => !ids.includes(n.id));
                state.selectedIds.clear();
                state.lastRendered = null;
                state.unreadCount = state.all.filter(n => !n.is_read).length;
                updateBadge();
                state.panelProtected = false;
                applyFilters();
                state.panelProtected = true;
                updateSelectionUI();
                if (state.selectMode) toggleSelectMode();
            } else {
                alert('Erreur lors de la suppression');
            }
        } catch (error) {
            console.error('Erreur suppression :', error);
            alert('Erreur réseau');
        }
    }

    function updateBadge() {
        if (!dom.badge) return;
        if (state.unreadCount > 0) {
            dom.badge.style.display = 'flex';
            dom.badge.textContent = state.unreadCount > 99 ? '99+' : state.unreadCount;
            dom.badge.classList.remove('bump');
            void dom.badge.offsetWidth;
            dom.badge.classList.add('bump');
        } else {
            dom.badge.style.display = 'none';
        }
    }

    // ============================================================
    // 9. MODE SÉLECTION
    // ============================================================
    function toggleSelectMode() {
        state.selectMode = !state.selectMode;
        if (!state.selectMode) {
            state.selectedIds.clear();
            if (dom.deleteBtn) dom.deleteBtn.style.display = 'none';
            if (dom.selectBtn) dom.selectBtn.classList.remove('active');
        } else {
            if (dom.selectBtn) dom.selectBtn.classList.add('active');
        }
        state.lastRendered = null;
        applyFilters();
        updateSelectionUI();
    }

    // ============================================================
    // 10. PROTECTION DU DOM (MutationObserver)
    // ============================================================
    let observer = null;

    function protectPanel() {
        if (observer) observer.disconnect();
        if (!dom.list) return;

        savedContent = dom.list.innerHTML;

        observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (state.panelProtected && dom.list.innerHTML !== savedContent) {
                    dom.list.innerHTML = savedContent;
                    console.warn('🔒 Panneau protégé : restauration du contenu.');
                }
            });
        });

        observer.observe(dom.list, {
            childList: true,
            subtree: true,
            characterData: true,
        });
    }

    // ============================================================
    // 11. INTERACTIONS UI
    // ============================================================
    function togglePanel() {
        state.isOpen = !state.isOpen;
        dom.panel.classList.toggle('open', state.isOpen);
        dom.btn.setAttribute('aria-expanded', state.isOpen);

        if (state.isOpen) {
            dom.bell.classList.add('bell-ring');
            dom.bell.addEventListener('animationend', () => dom.bell.classList.remove('bell-ring'), { once: true });
            if (state.selectMode) toggleSelectMode();
            state.panelProtected = false;
            loadFromApi(true);
        } else {
            loadFromApi(false);
            state.panelProtected = true;
        }
    }

    function closePanel() {
        if (state.isOpen) {
            state.isOpen = false;
            dom.panel.classList.remove('open');
            dom.btn.setAttribute('aria-expanded', 'false');
            if (state.selectMode) toggleSelectMode();
            loadFromApi(false);
            state.panelProtected = true;
        }
    }

    // ============================================================
    // 12. ÉVÉNEMENTS
    // ============================================================
    dom.btn.addEventListener('click', function(e) {
        e.stopPropagation();
        togglePanel();
    });

    document.addEventListener('click', function(e) {
        if (state.isOpen && !dom.panel.contains(e.target) && e.target !== dom.btn) {
            closePanel();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && state.isOpen) closePanel();
    });

    dom.chips.forEach(chip => {
        chip.addEventListener('click', function() {
            dom.chips.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            state.currentFilter = this.dataset.subfilter || 'all';
            if (state.selectMode) toggleSelectMode();
            state.lastRendered = null;
            state.panelProtected = false;
            applyFilters();
            state.panelProtected = true;
        });
    });

    dom.refreshBtn?.addEventListener('click', function() {
        dom.refreshIcon.style.transition = 'transform .5s ease';
        dom.refreshIcon.style.transform = 'rotate(360deg)';
        setTimeout(() => {
            dom.refreshIcon.style.transition = '';
            dom.refreshIcon.style.transform = '';
        }, 520);
        if (state.selectMode) toggleSelectMode();
        state.lastRendered = null;
        state.panelProtected = false;
        loadFromApi(true);
        state.panelProtected = true;
    });

    dom.markAllBtn?.addEventListener('click', markAllAsRead);

    dom.viewAllBtn?.addEventListener('click', function() {
        window.location.href = '/notifications';
    });

    dom.selectBtn?.addEventListener('click', function(e) {
        e.stopPropagation();
        toggleSelectMode();
    });

    dom.deleteBtn?.addEventListener('click', function(e) {
        e.stopPropagation();
        deleteSelected();
    });

    // ============================================================
    // 13. INIT
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        state.panelProtected = false;
        loadFromApi(true);
        state.panelProtected = true;
        protectPanel();
    });

    // Exposer l'API
    window.notifications = {
        loadFromApi,
        markAsRead,
        markAllAsRead,
        applyFilters,
        updateBadge,
        toggleSelectMode,
        deleteSelected,
        showToast: function(data) {
            console.log('Toast:', data);
        }
    };

})();

// Exposer l'API V6 pour les appels depuis app.js
window.notificationsV6 = {
    loadFromApi: window.notifications.loadFromApi,
    markAsRead: window.notifications.markAsRead,
    markAllAsRead: window.notifications.markAllAsRead,
    applyFilters: window.notifications.applyFilters,
    updateBadge: window.notifications.updateBadge,
    toggleSelectMode: window.notifications.toggleSelectMode,
    deleteSelected: window.notifications.deleteSelected,
    showToast: window.notifications.showToast
};
</script>

    <script>
        const sidebar    = document.getElementById('sidebar');
        const toggleBtn  = document.getElementById('sidebarToggle');
        const toggleIcon = document.getElementById('toggleIcon');

        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
            toggleIcon.className = 'bi bi-chevron-right';
        }
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            const c = sidebar.classList.contains('collapsed');
            toggleIcon.className = c ? 'bi bi-chevron-right' : 'bi bi-chevron-left';
            localStorage.setItem('sidebarCollapsed', c);
        });

        const mobileBtn = document.getElementById('mobileMenuBtn');
        const overlay   = document.getElementById('sidebarOverlay');
        mobileBtn?.addEventListener('click', () => {
            const open = sidebar.classList.toggle('mobile-open');
            overlay.style.display = open ? 'block' : 'none';
        });
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('mobile-open');
            overlay.style.display = 'none';
        });

        const html        = document.documentElement;
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon   = document.getElementById('themeIcon');

        function applyTheme(t) {
            html.setAttribute('data-theme', t);
            themeIcon.className = t === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
            localStorage.setItem('jrTheme', t);
        }
        applyTheme(localStorage.getItem('jrTheme') || 'light');
        themeToggle.addEventListener('click', () => applyTheme(html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'));
    </script>

</body>
</html>