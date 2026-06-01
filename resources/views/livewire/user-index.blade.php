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
        .flash-msg.danger {
            background: rgba(220,38,38,0.07);
            border-color: rgba(220,38,38,0.18);
            color: #dc2626;
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
        .data-table tbody tr:last-child td {
            border-bottom: none;
        }
        .data-table tbody tr:hover {
            background: var(--bg-hover);
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
        .empty-icon {
            font-size: 30px;
            display: block;
            margin-bottom: 8px;
            opacity: 0.45;
            color: var(--brand-orange);
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
            transition: 0.15s;
        }
        .pagination .page-item.active .page-link {
            background: var(--brand-green);
            border-color: var(--brand-green);
            color: white;
        }
        .pagination .page-item .page-link:hover:not(.active) {
            background: var(--brand-green-xlight);
            border-color: var(--brand-green);
            color: var(--brand-green);
        }
        .modal-jr .modal-content {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 18px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.16);
            overflow: hidden;
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
            opacity: 0.8;
        }
        .modal-jr .btn-close:hover {
            opacity: 1;
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
            transition: 0.18s;
        }
        .fc:focus {
            border-color: var(--brand-green);
            box-shadow: 0 0 0 3px rgba(26,122,60,0.1);
        }
        .text-danger {
            color: #dc2626 !important;
            font-size: 11px;
            margin-top: 4px;
            display: block;
        }
        .fw-bold {
            font-weight: 700;
        }
        .fw-semibold {
            font-weight: 600;
        }
        .role-badge {
            display: inline-block;
            background: rgba(26,122,60,0.1);
            color: var(--brand-green);
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }
    </style>

    @if(session()->has('message'))
        <div class="flash-msg success"><i class="bi bi-check-circle-fill"></i> {{ session('message') }}</div>
    @endif
    @if(session()->has('error'))
        <div class="flash-msg danger"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
    @endif

    <div class="module-toolbar">
        <h2><span class="module-icon green"><i class="bi bi-people"></i></span> Utilisateurs</h2>
        <button wire:click="create" class="btn-brand"><i class="bi bi-plus-lg"></i> Nouvel utilisateur</button>
    </div>

    <div class="search-wrap">
        <i class="bi bi-search s-icon"></i>
        <input type="text" wire:model.live.debounce.300ms="search" class="search-input" placeholder="Rechercher par nom ou email...">
    </div>

    <div class="data-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td><span class="fw-semibold">{{ $user->name }}</span></td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="role-badge">{{ ucfirst($user->roles->first()?->name ?? '—') }}</span>
                            </td>
                            <td>
                                @can('edit users')
                                    <button wire:click="edit({{ $user->id }})" class="act-btn edit" title="Modifier"><i class="bi bi-pencil"></i></button>
                                @endcan
                                @can('delete users')
                                    @if($user->id !== auth()->id())
                                        <button wire:click="delete({{ $user->id }})" onclick="return confirm('Supprimer cet utilisateur ?')" class="act-btn del" title="Supprimer"><i class="bi bi-trash3"></i></button>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-state-row">
                            <td colspan="4">
                                <span class="empty-icon"><i class="bi bi-people"></i></span>
                                Aucun utilisateur trouvé. Créez votre premier utilisateur.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="pagination-wrap">{{ $users->links() }}</div>
        @endif
    </div>

    @if($showForm)
    <div class="modal show d-block modal-jr" tabindex="-1" style="box-shadow: 0 4px 10px rgba(26,122,60,0.28); ">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-{{ $userId ? 'pencil-square' : 'plus-circle' }} me-2"></i>
                        {{ $userId ? 'Modifier' : 'Ajouter' }} un utilisateur
                    </h5>
                    <button type="button" class="btn-close" wire:click="$set('showForm',false)"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        <div class="fg">
                            <label>Nom complet *</label>
                            <input type="text" wire:model="name" class="fc @error('name') is-invalid @enderror">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="fg">
                            <label>Adresse email *</label>
                            <input type="email" wire:model="email" class="fc @error('email') is-invalid @enderror">
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="fg">
                            <label>Mot de passe</label>
                            <input type="password" wire:model="password" class="fc @error('password') is-invalid @enderror" placeholder="{{ $userId ? 'Laisser vide pour ne pas changer' : 'Obligatoire' }}">
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="fg">
                            <label>Confirmation du mot de passe</label>
                            <input type="password" wire:model="password_confirmation" class="fc">
                        </div>
                        <div class="fg">
                            <label>Rôle *</label>
                            <select wire:model="role" class="fc @error('role') is-invalid @enderror">
                                <option value="">— Sélectionner un rôle —</option>
                                @foreach($roles as $r)
                                    <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                                @endforeach
                            </select>
                            @error('role') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-ghost" wire:click="$set('showForm',false)">Annuler</button>
                            <button type="submit" class="btn-brand"><i class="bi bi-check2"></i> Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>