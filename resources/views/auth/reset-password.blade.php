<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jr Computer · Nouveau mot de passe</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brand-green:        #1a7a3c;
            --brand-green-light:  #22a352;
            --brand-green-glow:   rgba(26,122,60,0.18);
            --brand-orange:       #f07d00;
            --bg:           #f4f6f9;
            --bg-card:      #ffffff;
            --bg-input:     #f8faf9;
            --text-primary: #0f1f12;
            --text-sec:     #5a6b61;
            --text-muted:   #94a89e;
            --border:       #e2ece6;
            --password-strength-weak: #ef4444;
            --password-strength-medium: #f59e0b;
            --password-strength-strong: #1a7a3c;
        }
        [data-theme="dark"] {
            --bg:           #0d1810;
            --bg-card:      #131f16;
            --bg-input:     #0f1a12;
            --text-primary: #e8f5ec;
            --text-sec:     #8aab92;
            --text-muted:   #4d6b55;
            --border:       #243328;
            --password-strength-weak: #ef4444;
            --password-strength-medium: #f59e0b;
            --password-strength-strong: #22a352;
        }

        html, body {
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            transition: background 0.3s, color 0.3s;
            transform: scale(0.95);
            transform-origin: center center;
        }

        .rp-card {
            width: 100%; 
            max-width: 440px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 24px;
            box-shadow: 0 24px 60px rgba(26,122,60,0.10);
            overflow: hidden;
            position: relative;
        }

        /* Top strip */
        .rp-strip {
            height: 4px;
            background: linear-gradient(90deg, var(--brand-green), var(--brand-green-light), var(--brand-orange));
        }

        .rp-body { padding: 32px 32px 28px; }

        /* Back link */
        .back-link {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 12px; font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            margin-bottom: 24px;
            transition: all 0.15s;
        }
        .back-link:hover { color: var(--brand-green); transform: translateX(-2px); }

        /* Icon */
        .rp-icon {
            width: 56px; height: 56px; border-radius: 18px;
            background: linear-gradient(135deg, rgba(26,122,60,0.12), rgba(34,163,82,0.08));
            border: 1px solid rgba(26,122,60,0.18);
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; color: var(--brand-green);
            margin-bottom: 16px;
        }

        .rp-title { 
            font-size: 28px; 
            font-weight: 800; 
            color: var(--text-primary); 
            margin-bottom: 8px;
            letter-spacing: -0.3px;
        }
        .rp-desc { 
            font-size: 12.5px; 
            color: var(--text-muted); 
            line-height: 1.65; 
            margin-bottom: 28px; 
        }

        /* Alert success */
        .alert-success {
            display: flex; align-items: flex-start; gap: 10px;
            background: rgba(26,122,60,0.08);
            border: 1px solid rgba(26,122,60,0.2);
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 20px;
        }
        .alert-success i { color: var(--brand-green); font-size: 16px; flex-shrink: 0; margin-top: 1px; }
        .alert-success p { font-size: 12px; color: var(--brand-green); line-height: 1.5; margin: 0; }

        /* Alert error */
        .alert-error {
            display: flex; align-items: flex-start; gap: 10px;
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 20px;
        }
        .alert-error i { color: #ef4444; font-size: 16px; flex-shrink: 0; }
        .alert-error p { font-size: 12px; color: #ef4444; line-height: 1.5; margin: 0; }

        /* Fields */
        .field-group { margin-bottom: 20px; }
        .field-label {
            display: block; font-size: 11px; font-weight: 700;
            letter-spacing: 0.06em; text-transform: uppercase;
            color: var(--text-sec); margin-bottom: 8px;
        }
        .field-wrap { position: relative; }
        .field-icon {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            font-size: 15px; color: var(--text-muted); pointer-events: none;
            z-index: 1;
        }
        .field-input {
            width: 100%;
            padding: 11px 12px 11px 36px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            background: var(--bg-input);
            color: var(--text-primary);
            font-size: 13px; 
            font-family: inherit;
            outline: none;
            transition: all 0.2s ease;
        }
        .field-input:focus {
            border-color: var(--brand-green);
            box-shadow: 0 0 0 3px var(--brand-green-glow);
            background: var(--bg-card);
        }
        .field-input::placeholder { color: var(--text-muted); opacity: 0.7; }
        
        /* Password toggle button */
        .toggle-pw {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: var(--text-muted); font-size: 16px;
            transition: color 0.15s;
            z-index: 1;
        }
        .toggle-pw:hover { color: var(--brand-green); }

        /* Password strength indicator */
        .password-strength {
            margin-top: 8px;
            height: 4px;
            border-radius: 99px;
            background: var(--border);
            overflow: hidden;
            transition: all 0.2s;
        }
        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease, background 0.3s ease;
            border-radius: 99px;
        }
        .password-strength-text {
            font-size: 10px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-muted);
        }
        .strength-weak { background: var(--password-strength-weak); width: 33%; }
        .strength-medium { background: var(--password-strength-medium); width: 66%; }
        .strength-strong { background: var(--password-strength-strong); width: 100%; }
        .text-weak { color: var(--password-strength-weak); }
        .text-medium { color: var(--password-strength-medium); }
        .text-strong { color: var(--password-strength-strong); }

        .field-error { 
            font-size: 11px; 
            color: #ef4444; 
            margin-top: 6px; 
            display: flex; 
            align-items: center; 
            gap: 6px; 
        }

        /* Button */
        .btn-submit {
            width: 100%; 
            padding: 12px;
            background: linear-gradient(135deg, var(--brand-green) 0%, var(--brand-green-light) 100%);
            border: none; 
            border-radius: 12px;
            color: white; 
            font-size: 13.5px; 
            font-weight: 700; 
            font-family: inherit;
            cursor: pointer;
            box-shadow: 0 6px 18px var(--brand-green-glow);
            transition: all 0.2s ease;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 8px;
            margin-top: 8px;
        }
        .btn-submit:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 10px 28px rgba(26,122,60,0.32); 
        }
        .btn-submit:active { transform: translateY(0); }

        /* Footer */
        .rp-footer {
            text-align: center; 
            font-size: 11px; 
            color: var(--text-muted); 
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }
        .rp-footer a { 
            color: var(--brand-green); 
            font-weight: 600; 
            text-decoration: none; 
            transition: color 0.15s;
        }
        .rp-footer a:hover { color: var(--brand-orange); }

        /* Theme btn */
        .theme-btn {
            position: absolute; 
            top: 16px; 
            right: 16px;
            width: 34px; 
            height: 34px; 
            border-radius: 10px;
            border: 1px solid var(--border); 
            background: var(--bg-input);
            color: var(--text-muted); 
            display: flex; 
            align-items: center; 
            justify-content: center;
            cursor: pointer; 
            font-size: 15px; 
            transition: all 0.18s;
            z-index: 10;
        }
        .theme-btn:hover { 
            border-color: var(--brand-orange); 
            color: var(--brand-orange); 
            transform: scale(1.05);
        }

        /* Responsive */
        @media (max-width: 500px) {
            body { transform: scale(0.95); padding: 10px; }
            .rp-body { padding: 24px 20px; }
            .rp-title { font-size: 24px; }
        }

        /* Animation */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .rp-card { animation: fadeInUp 0.4s ease-out; }
    </style>
</head>
<body>
<div class="rp-card">
    <div class="rp-strip"></div>
    <div class="rp-body">
        <button class="theme-btn" id="themeBtn" title="Thème">
            <i class="bi bi-moon" id="themeIcon"></i>
        </button>

        <a href="{{ route('login') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Retour à la connexion
        </a>

        <div class="rp-icon">
            <i class="bi bi-key-fill"></i>
        </div>

        <h1 class="rp-title">Nouveau mot de passe</h1>
        <p class="rp-desc">Choisissez un mot de passe sécurisé pour votre compte Jr Computer.</p>

        @if(session('status'))
        <div class="alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <p>{{ session('status') }}</p>
        </div>
        @endif

        @if($errors->any())
        <div class="alert-error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <p>Veuillez corriger les erreurs ci-dessous.</p>
        </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') ?? $token ?? '' }}">

            <!-- Email Address -->
            <div class="field-group">
                <label class="field-label">Adresse email</label>
                <div class="field-wrap">
                    <i class="bi bi-envelope field-icon"></i>
                    <input type="email" name="email" 
                           class="field-input" 
                           value="{{ old('email', $request->email ?? '') }}" 
                           placeholder="vous@jrcomputer.cm"
                           required autofocus autocomplete="username"
                           readonly style="background: var(--bg-input); cursor: default;">
                </div>
                @error('email')
                    <div class="field-error">
                        <i class="bi bi-exclamation-circle"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- New Password -->
            <div class="field-group">
                <label class="field-label">Nouveau mot de passe</label>
                <div class="field-wrap">
                    <i class="bi bi-lock field-icon"></i>
                    <input type="password" name="password" id="password"
                           class="field-input" 
                           placeholder="••••••••"
                           required autocomplete="new-password">
                    <button type="button" class="toggle-pw" onclick="togglePassword('password', 'eyeIcon1')">
                        <i class="bi bi-eye" id="eyeIcon1"></i>
                    </button>
                </div>
                <div class="password-strength">
                    <div class="password-strength-bar" id="strengthBar"></div>
                </div>
                <div class="password-strength-text">
                    <i class="bi bi-shield-check"></i>
                    <span id="strengthText">Sécurité du mot de passe</span>
                </div>
                @error('password')
                    <div class="field-error">
                        <i class="bi bi-exclamation-circle"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="field-group">
                <label class="field-label">Confirmer le mot de passe</label>
                <div class="field-wrap">
                    <i class="bi bi-lock-fill field-icon"></i>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="field-input" 
                           placeholder="••••••••"
                           required autocomplete="new-password">
                    <button type="button" class="toggle-pw" onclick="togglePassword('password_confirmation', 'eyeIcon2')">
                        <i class="bi bi-eye" id="eyeIcon2"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <div class="field-error">
                        <i class="bi bi-exclamation-circle"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-check2-circle"></i> Réinitialiser le mot de passe
            </button>
        </form>

        <div class="rp-footer">
            <i class="bi bi-shield-lock me-1"></i> Sécurisé par Jr Computer 
            &nbsp;·&nbsp; <a href="{{ route('login') }}">Se connecter</a>
        </div>
    </div>
</div>

<script>
    // Theme management
    const html = document.documentElement;
    const savedTheme = localStorage.getItem('jrTheme') || 'light';
    html.setAttribute('data-theme', savedTheme);
    const themeIcon = document.getElementById('themeIcon');
    themeIcon.className = savedTheme === 'dark' ? 'bi bi-sun' : 'bi bi-moon';

    document.getElementById('themeBtn').addEventListener('click', () => {
        const current = html.getAttribute('data-theme');
        const next = current === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', next);
        themeIcon.className = next === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
        localStorage.setItem('jrTheme', next);
    });

    // Toggle password visibility
    function togglePassword(fieldId, iconId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(iconId);
        if (field.type === 'password') {
            field.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            field.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    // Password strength checker
    const passwordField = document.getElementById('password');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');

    function checkPasswordStrength(password) {
        let strength = 0;
        
        if (password.length === 0) return { score: 0, text: 'Sécurité du mot de passe', class: '' };
        if (password.length < 6) return { score: 1, text: '❌ Trop court (minimum 6 caractères)', class: 'strength-weak text-weak' };
        
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        if (strength <= 2) return { score: 1, text: '⚠️ Faible - Ajoutez chiffres, majuscules ou symboles', class: 'strength-weak text-weak' };
        if (strength <= 4) return { score: 2, text: '👍 Moyenne - Bonne sécurité', class: 'strength-medium text-medium' };
        return { score: 3, text: '✅ Forte - Excellent mot de passe', class: 'strength-strong text-strong' };
    }

    passwordField.addEventListener('input', function() {
        const result = checkPasswordStrength(this.value);
        
        strengthBar.className = 'password-strength-bar';
        if (result.class) {
            strengthBar.classList.add(result.class.split(' ')[0]);
        }
        
        strengthText.innerHTML = result.text;
        if (result.class.includes('weak')) {
            strengthText.className = 'text-weak';
        } else if (result.class.includes('medium')) {
            strengthText.className = 'text-medium';
        } else if (result.class.includes('strong')) {
            strengthText.className = 'text-strong';
        } else {
            strengthText.className = '';
        }
    });

    // Real-time password confirmation check
    const confirmField = document.getElementById('password_confirmation');
    
    function checkConfirmation() {
        const password = passwordField.value;
        const confirm = confirmField.value;
        
        if (confirm.length > 0 && password !== confirm) {
            confirmField.style.borderColor = '#ef4444';
        } else {
            confirmField.style.borderColor = 'var(--border)';
        }
    }
    
    passwordField.addEventListener('input', checkConfirmation);
    confirmField.addEventListener('input', checkConfirmation);
</script>
</body>
</html>