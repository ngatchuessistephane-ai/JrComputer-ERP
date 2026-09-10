<div style="zoom:0.90;">
    <style>
        .profile-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 25px;
            box-shadow: var(--shadow-card);
            margin-top:-15px;
        }
        .avatar-container {
            text-align: center;
            margin-bottom: 24px;
            position: relative;
        }
        .avatar-circle {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--brand-green), var(--brand-green-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            font-weight: 700;
            color: white;
            margin: 0 auto;
            box-shadow: 0 8px 20px rgba(26,122,60,0.3);
            overflow: hidden;
        }
        .avatar-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .avatar-upload-btn {
            position: absolute;
            bottom: -5px;
            right: calc(50% - 50px);
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.2s;
        }
        .avatar-upload-btn:hover {
            background: var(--brand-green-xlight);
            border-color: var(--brand-green);
        }
        .section-title {
            font-family: 'Syne', sans-serif;
            font-size: 18px;
            font-weight: 800;
            color: var(--text-primary);
            margin: 24px 0 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--border-color);
        }
        .section-title:first-of-type {
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            display: block;
        }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background: var(--bg-page);
            color: var(--text-primary);
            font-size: 14px;
            outline: none;
            transition: 0.2s;
        }
        .form-control:focus {
            border-color: var(--brand-green);
            box-shadow: 0 0 0 3px rgba(26,122,60,0.1);
        }
        .btn-save {
            background: linear-gradient(135deg, var(--brand-green), var(--brand-green-light));
            border: none;
            padding: 10px 24px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            transition: 0.2s;
        }
        .btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(26,122,60,0.4);
        }
        .flash-msg {
            background: rgba(26,122,60,0.1);
            border-left: 4px solid var(--brand-green);
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            color: var(--brand-green);
        }
        .text-danger {
            font-size: 11px;
            color: #dc2626;
            margin-top: 4px;
            display: block;
        }
        .small-hint {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 4px;
        }
    </style>

    @if(session()->has('message'))
        <div class="flash-msg">{{ session('message') }}</div>
    @endif
    <div class="profile-card">
         <!-- Remplacer la ligne du bouton Retour par : -->
<button onclick="window.history.back()" class="btn-ghost" style="margin-bottom:20px;">
    <i class="bi bi-arrow-left"></i> Retour
</button>
        <div class="avatar-container" style="float:clear">
            <div class="avatar-circle">
                @if(Auth::user()->avatar)
                    <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="Avatar">
                @else
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                @endif
            </div>
            <label for="avatarUpload" class="avatar-upload-btn" title="Changer l'avatar">
                <i class="bi bi-camera" style="font-size: 14px;"></i>
            </label>
            <input type="file" id="avatarUpload" wire:model="avatar" accept="image/*" style="display: none;">
            @error('avatar') <span class="text-danger">{{ $message }}</span> @enderror
            @if($avatar)
                <div class="small-hint text-success mt-2"><i class="bi bi-check-circle"></i> Nouvelle image sélectionnée</div>
            @endif
        </div>

        <div class="section-title">Informations personnelles</div>
        <form wire:submit.prevent="updateProfile">
            <div class="form-group">
                <label>Nom complet</label>
                <input type="text" wire:model="name" class="form-control">
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Adresse email</label>
                <input type="email" wire:model="email" class="form-control">
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn-save">Mettre à jour le profil</button>
        </form>

        <div class="section-title">Changer le mot de passe</div>
        <form wire:submit.prevent="updatePassword">
            <div class="form-group">
                <label>Mot de passe actuel</label>
                <input type="password" wire:model="current_password" class="form-control">
                @error('current_password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Nouveau mot de passe</label>
                <input type="password" wire:model="new_password" class="form-control">
                @error('new_password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Confirmer le nouveau mot de passe</label>
                <input type="password" wire:model="new_password_confirmation" class="form-control">
            </div>
            <button type="submit" class="btn-save">Modifier le mot de passe</button>
        </form>
    </div>
</div>