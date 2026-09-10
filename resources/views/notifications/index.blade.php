@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&family=Syne:wght@500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* ============================================================
       NOTIFICATIONS PAGE — JR COMPUTER STYLE
       Hérite des variables du layout (app.blade.php)
       ============================================================ */

    .notif-page {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        font-size: 13px;
        color: var(--text-primary);
        max-width: 1280px;
        margin: 0 auto;
        padding: 0;
        zoom: 0.95;
        transform: scale(0.95);
        transform-origin: top left;
        width: 105.26%;
    }

    .notif-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .notif-header-left {
        flex: 1;
    }

    .notif-eyebrow {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
    }

    .notif-eyebrow-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--brand-green);
        opacity: 0.8;
    }

    .notif-eyebrow-label {
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--text-muted);
    }

    .notif-title {
        font-family: 'Syne', 'Inter', sans-serif;
        font-size: 26px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 2px;
    }

    .notif-title span {
        background: linear-gradient(135deg, var(--brand-green), var(--brand-green-light));
        background-clip: text;
        -webkit-background-clip: text;
        color: transparent;
    }

    .notif-subtitle {
        font-size: 12px;
        color: var(--text-muted);
    }

    .notif-stats {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 8px 18px;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        flex-wrap: wrap;
    }

    .notif-stat {
        display: flex;
        align-items: baseline;
        gap: 6px;
    }

    .notif-stat-label {
        font-size: 11px;
        font-weight: 500;
        color: var(--text-muted);
    }

    .notif-stat-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.02em;
    }

    .notif-stat-value.unread {
        color: #dc2626;
    }

    .notif-filters {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
        padding: 4px 8px;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
    }

    .notif-filter-group {
        display: flex;
        align-items: center;
        gap: 4px;
        flex-wrap: wrap;
        padding: 4px;
    }

    .notif-filter-btn {
        padding: 6px 14px;
        font-size: 11px;
        font-weight: 500;
        border-radius: 20px;
        border: 1px solid transparent;
        background: transparent;
        cursor: pointer;
        color: var(--text-secondary);
        transition: all 0.15s;
        font-family: 'Inter', sans-serif;
    }

    .notif-filter-btn:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    .notif-filter-btn.active {
        background: var(--brand-green);
        color: #fff;
        border-color: var(--brand-green);
    }

    .notif-actions {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 4px;
    }

    .notif-action-btn {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: var(--bg-card);
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s;
        font-size: 14px;
    }

    .notif-action-btn:hover {
        border-color: var(--brand-green);
        color: var(--brand-green);
        background: var(--brand-green-xlight);
    }

    .notif-action-btn.primary {
        background: var(--brand-green);
        color: #fff;
        border-color: var(--brand-green);
        width: auto;
        padding: 0 16px;
        gap: 6px;
        font-size: 11px;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
    }

    .notif-action-btn.primary:hover {
        background: var(--brand-green-light);
    }

    .notif-action-btn.active {
        background: var(--brand-green-xlight);
        border-color: var(--brand-green);
        color: var(--brand-green);
    }

    .notif-action-btn.danger {
        color: #dc2626;
        border-color: #dc2626;
    }

    .notif-action-btn.danger:hover {
        background: #fee2e2;
    }

    .notif-list-wrap {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: var(--shadow-card);
    }

    .notif-list {
        max-height: 600px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: var(--border-color) transparent;
    }

    .notif-list::-webkit-scrollbar {
        width: 4px;
    }

    .notif-list::-webkit-scrollbar-thumb {
        background: var(--border-color);
        border-radius: 99px;
    }

    .np-item {
        display: flex;
        align-items: stretch;
        padding: 0;
        border-bottom: 1px solid var(--border-color);
        cursor: pointer;
        transition: background 0.12s;
        overflow: hidden;
    }

    .np-item:last-child {
        border-bottom: none;
    }

    .np-item:hover {
        background: var(--bg-hover);
    }

    .np-item-bar {
        width: 4px;
        align-self: stretch;
        flex-shrink: 0;
        background: var(--border-color);
        transition: background 0.15s;
    }

    .np-item.unread .np-item-bar {
        background: var(--brand-green);
    }

    .np-item.acc-red .np-item-bar {
        background: #dc2626;
    }
    .np-item.acc-amber .np-item-bar {
        background: #f07d00;
    }
    .np-item.acc-green .np-item-bar {
        background: #10b981;
    }
    .np-item.acc-blue .np-item-bar {
        background: #3b82f6;
    }

    .np-item-inner {
        flex: 1;
        padding: 14px 18px;
        min-width: 0;
    }

    .np-item-top {
        display: flex;
        align-items: center;
        gap: 10px;
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

    .np-tag-red {
        background: #fee2e2;
        color: #dc2626;
    }
    .np-tag-amber {
        background: #fef3c7;
        color: #b45309;
    }
    .np-tag-green {
        background: #d1fae5;
        color: #065f46;
    }
    .np-tag-blue {
        background: #dbeafe;
        color: #1d4ed8;
    }
    .np-tag-gray {
        background: var(--border-color);
        color: var(--text-muted);
    }

    [data-theme="dark"] .np-tag-red {
        background: rgba(220, 38, 38, 0.18);
        color: #fca5a5;
    }
    [data-theme="dark"] .np-tag-amber {
        background: rgba(217, 119, 6, 0.18);
        color: #fcd34d;
    }
    [data-theme="dark"] .np-tag-blue {
        background: rgba(29, 78, 216, 0.22);
        color: #93c5fd;
    }
    [data-theme="dark"] .np-tag-green {
        background: rgba(6, 95, 70, 0.22);
        color: #6ee7b7;
    }

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

    .np-item.acc-red .np-unread-dot {
        background: #dc2626;
    }
    .np-item.acc-amber .np-unread-dot {
        background: #f07d00;
    }
    .np-item.acc-blue .np-unread-dot {
        background: #3b82f6;
    }

    .np-item-msg {
        font-size: 12px;
        line-height: 1.5;
        color: var(--text-secondary);
        margin: 6px 0 8px;
        padding: 6px 12px;
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

    .np-cta-primary,
    .np-cta-secondary {
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

    .notif-empty {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }

    .notif-empty-icon {
        font-size: 48px;
        color: var(--border-color);
        display: block;
        margin-bottom: 12px;
    }

    .notif-empty-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 4px;
    }

    .notif-empty-sub {
        font-size: 13px;
    }

    .notif-pagination {
        padding: 14px 18px;
        border-top: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg-card);
    }

    .pagination {
        display: flex;
        gap: 4px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .pagination .page-item .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0 10px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: transparent;
        color: var(--text-secondary);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s;
        font-family: 'Inter', sans-serif;
    }

    .pagination .page-item .page-link:hover {
        background: var(--bg-hover);
        border-color: var(--brand-green);
        color: var(--brand-green);
    }

    .pagination .page-item.active .page-link {
        background: var(--brand-green);
        border-color: var(--brand-green);
        color: #fff;
    }

    .pagination .page-item.disabled .page-link {
        opacity: 0.4;
        cursor: not-allowed;
    }

    .notif-loader {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 0;
    }

    .spinner {
        width: 30px;
        height: 30px;
        border: 3px solid var(--border-color);
        border-top-color: var(--brand-green);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .np-select-checkbox {
        accent-color: var(--brand-green);
        cursor: pointer;
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    @media (max-width: 640px) {
        .notif-stats {
            flex-wrap: wrap;
            gap: 8px 16px;
        }
        .notif-stat-value {
            font-size: 16px;
        }
        .notif-filter-btn {
            padding: 4px 10px;
            font-size: 10px;
        }
        .np-item-inner {
            padding: 12px 14px;
        }
        .np-item-title {
            font-size: 12px;
        }
        .np-item-msg {
            font-size: 11px;
        }
        .notif-title {
            font-size: 22px;
        }
        .notif-actions {
            gap: 4px;
        }
        .notif-action-btn {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }
        .notif-action-btn.primary {
            padding: 0 10px;
            font-size: 10px;
        }
    }
</style>

<div class="notif-page" id="notifPage">
    <div class="notif-header">
        <div class="notif-header-left">
            <div class="notif-eyebrow">
                <span class="notif-eyebrow-dot"></span>
                <span class="notif-eyebrow-label">Centre de notifications</span>
            </div>
            <h1 class="notif-title">
                <span>Jr Computer</span> · Notifications
            </h1>
            <p class="notif-subtitle">Historique complet de toutes vos alertes et activités.</p>
        </div>
        <div class="notif-stats">
            <div class="notif-stat">
                <span class="notif-stat-label">Total</span>
                <span class="notif-stat-value" id="totalCount">—</span>
            </div>
            <div class="notif-stat">
                <span class="notif-stat-label">Non lues</span>
                <span class="notif-stat-value unread" id="unreadCount">—</span>
            </div>
        </div>
    </div>

    <div class="notif-filters">
        <div class="notif-filter-group" id="filterGroup">
            <button class="notif-filter-btn active" data-filter="all">Toutes</button>
            <button class="notif-filter-btn" data-filter="unread">Non lues</button>
            <button class="notif-filter-btn" data-filter="tickets">Tickets</button>
            <button class="notif-filter-btn" data-filter="commandes">Commandes</button>
            <button class="notif-filter-btn" data-filter="alertes">Alertes</button>
        </div>
        <div class="notif-actions">
            <button class="notif-action-btn" id="selectPageBtn" title="Sélectionner">
                <i class="bi bi-check2-square"></i>
            </button>
            <button class="notif-action-btn danger" id="deletePageBtn" style="display:none;" title="Supprimer la sélection">
                <i class="bi bi-trash3"></i> <span id="pageSelectedCount">0</span>
            </button>
            <button class="notif-action-btn primary" id="markAllPageBtn">
                <i class="bi bi-check2-all"></i> Tout lire
            </button>
            <button class="notif-action-btn" id="refreshPageBtn" title="Rafraîchir">
                <i class="bi bi-arrow-repeat"></i>
            </button>
        </div>
    </div>

    <div class="notif-list-wrap">
        <div class="notif-list" id="notificationsList">
            <div class="notif-loader">
                <div class="spinner"></div>
            </div>
        </div>
        <div class="notif-pagination" id="paginationContainer"></div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        'use strict';

        // ============================================================
        // 1. ÉTAT
        // ============================================================
        let allItems = [];
        let filteredItems = [];
        let currentFilter = 'all';
        let currentPage = 1;
        const perPage = 20;

        let selectMode = false;
        let selectedIds = new Set();

        // ============================================================
        // 2. RÉFÉRENCES DOM
        // ============================================================
        const listEl = document.getElementById('notificationsList');
        const paginationEl = document.getElementById('paginationContainer');
        const filterBtns = document.querySelectorAll('#filterGroup .notif-filter-btn');
        const totalCountEl = document.getElementById('totalCount');
        const unreadCountEl = document.getElementById('unreadCount');
        const selectPageBtn = document.getElementById('selectPageBtn');
        const deletePageBtn = document.getElementById('deletePageBtn');
        const pageSelectedCount = document.getElementById('pageSelectedCount');
        const markAllPageBtn = document.getElementById('markAllPageBtn');
        const refreshPageBtn = document.getElementById('refreshPageBtn');

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
            return `<i class="bi ${icon}" style="color:${color};font-size:18px;"></i>`;
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

        function renderItem(n) {
            const url = n.action_url || '#';
            const time = n.time_ago || formatTime(n.created_at);
            const isUnread = !n.is_read;
            const accentClass = n.priority === 'critical' ? 'acc-red' :
                n.type === 'low_stock' ? 'acc-amber' :
                n.type === 'ticket_closed' ? 'acc-green' :
                n.type === 'ticket_assigned' ? 'acc-blue' : '';

            const checkboxHtml = selectMode ? `
                <div style="display:flex;align-items:center;padding-left:12px;flex-shrink:0;">
                    <input type="checkbox" class="np-select-checkbox" data-id="${escapeHtml(n.id)}" 
                           style="width:16px;height:16px;accent-color:var(--brand-green);cursor:pointer;">
                </div>
            ` : '';

            return `
                <div class="np-item ${isUnread ? 'unread' : ''} ${accentClass}" 
                     data-id="${escapeHtml(n.id)}" 
                     data-url="${escapeHtml(url)}"
                     data-type="${escapeHtml(n.type)}">
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
        // 4. CHARGEMENT DES DONNÉES
        // ============================================================
        async function loadData() {
            try {
                listEl.innerHTML = `<div class="notif-loader"><div class="spinner"></div></div>`;
                const response = await axios.get('/api/activities');
                if (response.data.success) {
                    allItems = response.data.activities || [];
                    updateStats();
                    applyFilters();
                } else {
                    throw new Error('Réponse invalide');
                }
            } catch (e) {
                console.error('Erreur chargement :', e);
                listEl.innerHTML = `
                    <div class="notif-empty">
                        <span class="notif-empty-icon"><i class="bi bi-exclamation-triangle"></i></span>
                        <div class="notif-empty-title">Erreur de chargement</div>
                        <div class="notif-empty-sub">Impossible de récupérer les notifications. Réessayez.</div>
                    </div>
                `;
                paginationEl.innerHTML = '';
            }
        }

        function updateStats() {
            const total = allItems.length;
            const unread = allItems.filter(n => !n.is_read).length;
            if (totalCountEl) totalCountEl.textContent = total;
            if (unreadCountEl) unreadCountEl.textContent = unread;
            if (window.notifications && window.notifications.updateBadge) {
                window.notifications.updateBadge();
            }
        }

        // ============================================================
        // 5. FILTRAGE
        // ============================================================
        function applyFilters() {
            let list = [...allItems];
            if (currentFilter === 'unread') {
                list = list.filter(n => !n.is_read);
            } else if (currentFilter === 'tickets') {
                list = list.filter(n => getCategory(n.type) === 'tickets');
            } else if (currentFilter === 'commandes') {
                list = list.filter(n => getCategory(n.type) === 'commandes');
            } else if (currentFilter === 'alertes') {
                list = list.filter(n => getCategory(n.type) === 'alertes');
            }
            filteredItems = list;
            currentPage = 1;
            if (selectMode) {
                selectedIds.clear();
                updateSelectionUI();
            }
            renderPage();
        }

        // ============================================================
        // 6. RENDU AVEC PAGINATION
        // ============================================================
        function renderPage() {
            const total = filteredItems.length;
            const totalPages = Math.ceil(total / perPage);
            const start = (currentPage - 1) * perPage;
            const end = Math.min(start + perPage, total);
            const pageItems = filteredItems.slice(start, end);

            if (total === 0) {
                listEl.innerHTML = `
                    <div class="notif-empty">
                        <span class="notif-empty-icon"><i class="bi bi-bell-slash"></i></span>
                        <div class="notif-empty-title">Aucune notification</div>
                        <div class="notif-empty-sub">Aucun élément ne correspond à votre filtre.</div>
                    </div>
                `;
                paginationEl.innerHTML = '';
                return;
            }

            let html = '';
            let lastDate = '';
            pageItems.forEach(n => {
                const date = new Date(n.created_at);
                const dateKey = date.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long',
                    year: 'numeric' });
                if (dateKey !== lastDate) {
                    html += `<div class="np-date-label">${dateKey}</div>`;
                    lastDate = dateKey;
                }
                html += renderItem(n);
            });
            listEl.innerHTML = html;

            // Gestion des checkboxes
            if (selectMode) {
                listEl.querySelectorAll('.np-select-checkbox').forEach(cb => {
                    cb.checked = selectedIds.has(cb.dataset.id);
                    cb.addEventListener('change', function() {
                        const id = this.dataset.id;
                        if (this.checked) {
                            selectedIds.add(id);
                        } else {
                            selectedIds.delete(id);
                        }
                        updateSelectionUI();
                    });
                });
                listEl.querySelectorAll('.np-item').forEach(el => {
                    el.style.cursor = 'default';
                    el.removeEventListener('click', itemClickHandler);
                });
            } else {
                listEl.querySelectorAll('.np-item').forEach(el => {
                    el.style.cursor = 'pointer';
                    el.addEventListener('click', itemClickHandler);
                });
            }

            renderPagination(totalPages);
        }

        function itemClickHandler(e) {
            if (e.target.closest('a[style*="cursor:pointer"]')) return;
            const el = e.currentTarget;
            const id = el.dataset.id;
            const url = el.dataset.url;
            if (el.classList.contains('unread')) {
                markAsRead(id);
            }
            if (url && url !== '#') {
                window.location.href = url;
            }
        }

        function renderPagination(totalPages) {
            if (totalPages <= 1) {
                paginationEl.innerHTML = '';
                return;
            }

            let html = '<ul class="pagination">';
            html += `<li class="page-item ${currentPage <= 1 ? 'disabled' : ''}">
                        <button class="page-link" data-page="${currentPage - 1}" ${currentPage <= 1 ? 'disabled' : ''}>‹</button>
                     </li>`;
            for (let i = 1; i <= totalPages; i++) {
                const active = i === currentPage ? 'active' : '';
                html += `<li class="page-item ${active}">
                            <button class="page-link" data-page="${i}">${i}</button>
                         </li>`;
            }
            html += `<li class="page-item ${currentPage >= totalPages ? 'disabled' : ''}">
                        <button class="page-link" data-page="${currentPage + 1}" ${currentPage >= totalPages ? 'disabled' : ''}>›</button>
                     </li>`;
            html += '</ul>';
            paginationEl.innerHTML = html;

            paginationEl.querySelectorAll('.page-link').forEach(btn => {
                btn.addEventListener('click', function() {
                    const page = parseInt(this.dataset.page);
                    if (!isNaN(page) && page >= 1 && page <= totalPages) {
                        currentPage = page;
                        renderPage();
                        listEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });
        }

        // ============================================================
        // 7. MARQUER COMME LU
        // ============================================================
        async function markAsRead(id) {
            try {
                await axios.post(`/api/activities/${id}/read`);
                const item = allItems.find(n => n.id === id);
                if (item) {
                    item.is_read = true;
                }
                updateStats();
                applyFilters();
            } catch (e) {
                console.error('Erreur marquage lu :', e);
            }
        }

        async function markAllAsRead() {
            try {
                await axios.post('/api/activities/read-all');
                allItems.forEach(n => n.is_read = true);
                updateStats();
                applyFilters();
            } catch (e) {
                console.error('Erreur tout marquer lu :', e);
            }
        }

        // ============================================================
        // 8. GESTION DE LA SÉLECTION
        // ============================================================
        function updateSelectionUI() {
            const count = selectedIds.size;
            if (pageSelectedCount) pageSelectedCount.textContent = count;
            if (deletePageBtn) {
                deletePageBtn.style.display = count > 0 ? 'inline-flex' : 'none';
            }
            document.querySelectorAll('.np-select-checkbox').forEach(cb => {
                cb.checked = selectedIds.has(cb.dataset.id);
            });
        }

        function toggleSelectMode() {
            selectMode = !selectMode;
            if (!selectMode) {
                selectedIds.clear();
                deletePageBtn.style.display = 'none';
                selectPageBtn.classList.remove('active');
            } else {
                selectPageBtn.classList.add('active');
            }
            renderPage();
            updateSelectionUI();
        }

        async function deleteSelected() {
    if (selectedIds.size === 0) return;
    if (!confirm(`Supprimer ${selectedIds.size} notification(s) sélectionnée(s) ?`)) return;

    try {
        const ids = Array.from(selectedIds);
        const response = await axios.delete('/api/activities', {
            data: { ids: ids }
        });
        if (response.data.success) {
            allItems = allItems.filter(n => !ids.includes(n.id));
            selectedIds.clear();
            updateStats();
            applyFilters();
            updateSelectionUI();
            if (selectMode) toggleSelectMode();
        } else {
            alert('Erreur lors de la suppression');
        }
    } catch (error) {
        console.error('Erreur suppression :', error);
        alert('Erreur réseau');
    }
}

        // ============================================================
        // 9. ÉVÉNEMENTS
        // ============================================================
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.dataset.filter || 'all';
                if (selectMode) toggleSelectMode();
                applyFilters();
            });
        });

        markAllPageBtn?.addEventListener('click', markAllAsRead);
        refreshPageBtn?.addEventListener('click', loadData);
        selectPageBtn?.addEventListener('click', toggleSelectMode);
        deletePageBtn?.addEventListener('click', deleteSelected);

        // ============================================================
        // 10. INIT
        // ============================================================
        loadData();
    });
</script>
@endpush