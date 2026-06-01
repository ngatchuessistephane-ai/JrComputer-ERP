<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jr Computer · Créer un compte</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brand-green:        #1a7a3c;
            --brand-green-light:  #22a352;
            --brand-green-glow:   rgba(26,122,60,0.18);
            --brand-orange:       #f07d00;
            --brand-orange-light: #ff9c2a;
            --bg:           #f4f6f9;
            --bg-card:      #ffffff;
            --bg-input:     #f8faf9;
            --text-primary: #0f1f12;
            --text-sec:     #5a6b61;
            --text-muted:   #94a89e;
            --border:       #e2ece6;
        }
        [data-theme="dark"] {
            --bg:           #0d1810;
            --bg-card:      #131f16;
            --bg-input:     #0f1a12;
            --text-primary: #e8f5ec;
            --text-sec:     #8aab92;
            --text-muted:   #4d6b55;
            --border:       #243328;
        }

        html, body {
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            transition: background 0.3s, color 0.3s;
        }

        /* ── Layout split ── */
        .auth-wrap {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
        @media (max-width: 900px) {
            .auth-wrap { grid-template-columns: 1fr; }
            .auth-brand { display: none; }
        }

        /* ── Brand panel (orange accent) ── */
        .auth-brand {
            background: linear-gradient(150deg, #7c3c00 0%, #c46200 45%, #f07d00 100%);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 48px 52px;
            position: relative; overflow: hidden;
        }
        .auth-brand::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(circle at 80% 20%, rgba(26,122,60,0.15) 0%, transparent 50%),
                radial-gradient(circle at 20% 80%, rgba(255,255,255,0.04) 0%, transparent 50%);
        }
        .brand-diamonds {
            position: absolute; bottom: 0; left: 0;
            width: 220px; height: 140px;
            display: grid;
            grid-template-columns: repeat(7, 22px);
            gap: 6px; padding: 20px;
            transform: rotate(10deg) translate(-20px, 20px);
            opacity: 0.15;
        }
        .brand-diamonds span {
            width: 13px; height: 13px;
            background: white; transform: rotate(45deg); border-radius: 2px;
        }

        .brand-content { position: relative; z-index: 1; text-align: center; }
        .brand-jr {
            width: 88px; height: 88px; border-radius: 26px;
            background: rgba(255,255,255,0.14);
            border: 1.5px solid rgba(255,255,255,0.22);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 26px;
        }
        .brand-jr span { font-family: 'Syne', sans-serif; font-size: 36px; font-weight: 800; color: white; letter-spacing: -2px; }
        .brand-tagline { font-family: 'Syne', sans-serif; font-size: 24px; font-weight: 800; color: white; line-height: 1.25; margin-bottom: 12px; }
        .brand-sub { font-size: 13px; color: rgba(255,255,255,0.65); line-height: 1.65; max-width: 270px; }

        .brand-steps { margin-top: 36px; display: flex; flex-direction: column; gap: 12px; text-align: left; }
        .brand-step {
            display: flex; align-items: center; gap: 12px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.14);
            border-radius: 12px; padding: 10px 16px;
        }
        .step-num {
            width: 24px; height: 24px; border-radius: 8px;
            background: rgba(255,255,255,0.18);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 800; color: white; flex-shrink: 0;
        }
        .brand-step p { font-size: 12px; color: rgba(255,255,255,0.8); font-weight: 500; }

        /* ── Form panel ── */
        .auth-form-panel {
            display: flex; align-items: center; justify-content: center;
            padding: 28px 24px; position: relative;
        }
        .theme-btn {
            position: absolute; top: 20px; right: 24px;
            width: 34px; height: 34px; border-radius: 9px;
            border: 1px solid var(--border); background: var(--bg-card);
            color: var(--text-muted);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 15px; transition: all 0.18s;
        }
        .theme-btn:hover { border-color: var(--brand-orange); color: var(--brand-orange); }

        .form-card { width: 100%; max-width: 420px; }

        .form-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 24px; }
        .form-logo-mark {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, var(--brand-green), var(--brand-green-light));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Syne', sans-serif; font-size: 15px; font-weight: 800; color: white;
            box-shadow: 0 4px 12px var(--brand-green-glow);
        }
        .form-logo-text { font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 800; color: var(--text-primary); }
        .form-logo-sub  { font-size: 10px; color: var(--brand-orange); font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; }

        .form-heading    { font-family: 'Syne', sans-serif; font-size: 21px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px; }
        .form-subheading { font-size: 12.5px; color: var(--text-muted); margin-bottom: 22px; }

        /* Row layout for 2-col fields */
        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        @media (max-width: 500px) { .field-row { grid-template-columns: 1fr; } }

        .field-group { margin-bottom: 13px; }
        .field-label {
            display: block; font-size: 10.5px; font-weight: 700;
            letter-spacing: 0.06em; text-transform: uppercase;
            color: var(--text-sec); margin-bottom: 5px;
        }
        .field-wrap { position: relative; }
        .field-icon {
            position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
            font-size: 14px; color: var(--text-muted); pointer-events: none;
        }
        .field-input {
            width: 100%;
            padding: 8px 11px 8px 33px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--bg-input);
            color: var(--text-primary);
            font-size: 12.5px; font-family: inherit;
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .field-input:focus {
            border-color: var(--brand-green);
            box-shadow: 0 0 0 3px var(--brand-green-glow);
            background: var(--bg-card);
        }
        .field-input::placeholder { color: var(--text-muted); }
        .field-error { font-size: 10.5px; color: #e53935; margin-top: 3px; display: flex; align-items: center; gap: 4px; }

        .toggle-pw {
            position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: var(--text-muted); font-size: 14px; transition: color 0.15s;
        }
        .toggle-pw:hover { color: var(--brand-green); }

        /* Password strength */
        .pw-strength { margin-top: 6px; display: flex; gap: 4px; }
        .pw-bar { flex: 1; height: 3px; border-radius: 99px; background: var(--border); transition: background 0.3s; }
        .pw-bar.weak   { background: #e53935; }
        .pw-bar.medium { background: var(--brand-orange); }
        .pw-bar.strong { background: var(--brand-green); }
        .pw-label { font-size: 10.5px; color: var(--text-muted); margin-top: 3px; }

        /* Terms */
        .terms-wrap { display: flex; align-items: flex-start; gap: 8px; margin: 14px 0; }
        .terms-wrap input[type="checkbox"] { width: 14px; height: 14px; accent-color: var(--brand-green); margin-top: 2px; flex-shrink: 0; }
        .terms-wrap span { font-size: 11.5px; color: var(--text-sec); line-height: 1.5; }
        .terms-wrap a { color: var(--brand-green); font-weight: 600; text-decoration: none; }

        /* Submit */
        .btn-submit {
            width: 100%; padding: 11px;
            background: linear-gradient(135deg, var(--brand-green) 0%, var(--brand-green-light) 100%);
            border: none; border-radius: 11px;
            color: white; font-size: 13px; font-weight: 700; font-family: inherit;
            cursor: pointer;
            box-shadow: 0 6px 18px var(--brand-green-glow);
            transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 7px;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(26,122,60,0.32); }

        .form-footer { text-align: center; font-size: 11.5px; color: var(--text-muted); margin-top: 16px; }
        .form-footer a { color: var(--brand-green); font-weight: 600; text-decoration: none; }

        /* Divider */
        .divider { display: flex; align-items: center; gap: 10px; margin: 14px 0; }
        .divider hr { flex: 1; border: none; border-top: 1px solid var(--border); }
        .divider span { font-size: 10.5px; color: var(--text-muted); }
    </style>
</head>
<body>
<div class="auth-wrap">

    <!-- Brand panel -->
    <div class="auth-brand">
        <div class="brand-diamonds">
            @for($i = 0; $i < 35; $i++)<span></span>@endfor
        </div>
        <div class="brand-content">
            <div class="brand-jr"><span>JR</span></div>
            <div class="brand-tagline">Rejoignez<br>l'équipe JR.</div>
            <p class="brand-sub">Créez votre compte pour accéder à la plateforme de gestion intégrée Jr Computer Sarl.</p>

            <div class="brand-steps">
                <div class="brand-step">
                    <div class="step-num">1</div>
                    <p>Créez votre compte administrateur</p>
                </div>
                <div class="brand-step">
                    <div class="step-num">2</div>
                    <p>Configurez vos modules ERP</p>
                </div>
                <div class="brand-step">
                    <div class="step-num">3</div>
                    <p>Pilotez votre business en temps réel</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form panel -->
    <div class="auth-form-panel">
        <button class="theme-btn" id="themeBtn" title="Thème"><i class="bi bi-moon" id="themeIcon"></i></button>

        <div class="form-card">
            <div class="form-logo">
                <div class="form-logo-mark">JR</div>
                <div>
                    <div class="form-logo-text">Jr Computer</div>
                    <div class="form-logo-sub">ERP Platform</div>
                </div>
            </div>

            <h1 class="form-heading">Créer un compte</h1>
            <p class="form-subheading">Renseignez vos informations pour commencer</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="field-group">
                    <label class="field-label">Nom complet</label>
                    <div class="field-wrap">
                        <i class="bi bi-person field-icon"></i>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="field-input" placeholder="Jean Dupont"
                               required autofocus autocomplete="name">
                    </div>
                    @error('name')
                        <div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="field-group">
                    <label class="field-label">Email professionnel</label>
                    <div class="field-wrap">
                        <i class="bi bi-envelope field-icon"></i>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="field-input" placeholder="vous@jrcomputer.cm"
                               required autocomplete="username">
                    </div>
                    @error('email')
                        <div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password row -->
                <div class="field-row">
                    <div class="field-group">
                        <label class="field-label">Mot de passe</label>
                        <div class="field-wrap">
                            <i class="bi bi-lock field-icon"></i>
                            <input type="password" name="password" id="pwField"
                                   class="field-input" placeholder="••••••••"
                                   required autocomplete="new-password"
                                   oninput="checkStrength(this.value)">
                            <button type="button" class="toggle-pw" onclick="togglePw('pwField','pwEye')">
                                <i class="bi bi-eye" id="pwEye"></i>
                            </button>
                        </div>
                        <div class="pw-strength">
                            <div class="pw-bar" id="bar1"></div>
                            <div class="pw-bar" id="bar2"></div>
                            <div class="pw-bar" id="bar3"></div>
                        </div>
                        <div class="pw-label" id="pwLabel">Min. 8 caractères</div>
                        @error('password')
                            <div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label">Confirmer</label>
                        <div class="field-wrap">
                            <i class="bi bi-lock-fill field-icon"></i>
                            <input type="password" name="password_confirmation" id="pwConfirm"
                                   class="field-input" placeholder="••••••••"
                                   required autocomplete="new-password">
                            <button type="button" class="toggle-pw" onclick="togglePw('pwConfirm','pwEye2')">
                                <i class="bi bi-eye" id="pwEye2"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="bi bi-person-plus"></i> Créer mon compte
                </button>
            </form>

            <div class="form-footer" style="margin-top: 14px;">
                Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a>
                &nbsp;·&nbsp; © {{ date('Y') }} Jr Computer Sarl
            </div>
        </div>
    </div>
</div>

<script>
    /* Theme */
    const html = document.documentElement;
    const saved = localStorage.getItem('jrTheme') || 'light';
    html.setAttribute('data-theme', saved);
    document.getElementById('themeIcon').className = saved === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
    document.getElementById('themeBtn').addEventListener('click', () => {
        const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', next);
        document.getElementById('themeIcon').className = next === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
        localStorage.setItem('jrTheme', next);
    });

    /* Toggle password */
    function togglePw(fieldId, iconId) {
        const f = document.getElementById(fieldId);
        const i = document.getElementById(iconId);
        if (f.type === 'password') { f.type = 'text'; i.className = 'bi bi-eye-slash'; }
        else { f.type = 'password'; i.className = 'bi bi-eye'; }
    }

    /* Password strength */
    function checkStrength(val) {
        const bars  = [document.getElementById('bar1'), document.getElementById('bar2'), document.getElementById('bar3')];
        const label = document.getElementById('pwLabel');
        bars.forEach(b => b.className = 'pw-bar');

        if (val.length === 0) { label.textContent = 'Min. 8 caractères'; return; }

        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { cls: 'weak',   txt: 'Faible' },
            { cls: 'medium', txt: 'Moyen' },
            { cls: 'strong', txt: 'Fort' }
        ];
        for (let i = 0; i < score; i++) bars[i].classList.add(levels[score - 1].cls);
        label.textContent = levels[score - 1]?.txt ?? 'Min. 8 caractères';
        label.style.color = score === 1 ? '#e53935' : score === 2 ? 'var(--brand-orange)' : 'var(--brand-green)';
    }
</script>
</body>
</html>