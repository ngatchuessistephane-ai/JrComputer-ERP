<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jr Computer · Connexion</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            --border-focus: #1a7a3c;
            --shadow:       0 24px 60px rgba(26,122,60,0.10);
        }

        [data-theme="dark"] {
            --bg:           #0d1810;
            --bg-card:      #131f16;
            --bg-input:     #0f1a12;
            --text-primary: #e8f5ec;
            --text-sec:     #8aab92;
            --text-muted:   #4d6b55;
            --border:       #243328;
            --border-focus: #22a352;
            --shadow:       0 24px 60px rgba(0,0,0,0.4);
        }

        html, body {
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            transition: background 0.3s, color 0.3s;
        }

        /* ── Layout ── */
        .auth-wrap {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
        @media (max-width: 860px) {
            .auth-wrap { grid-template-columns: 1fr; }
            .auth-brand { display: none; }
        }

        /* ── Left brand panel ── */
        .auth-brand {
            background: linear-gradient(145deg, #0f4a24 0%, #1a7a3c 45%, #2d9e5a 100%);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 48px 52px;
            position: relative;
            overflow: hidden;
        }
        .auth-brand::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(circle at 20% 20%, rgba(240,125,0,0.12) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255,255,255,0.05) 0%, transparent 50%);
        }
        /* Decorative diamond grid (like logo) */
        .brand-diamonds {
            position: absolute;
            bottom: 0; right: 0;
            width: 260px; height: 160px;
            display: grid;
            grid-template-columns: repeat(8, 24px);
            gap: 6px;
            padding: 20px;
            transform: rotate(-10deg) translate(20px, 20px);
            opacity: 0.18;
        }
        .brand-diamonds span {
            width: 14px; height: 14px;
            background: #f07d00;
            transform: rotate(45deg);
            border-radius: 2px;
        }
        .brand-content { position: relative; z-index: 1; text-align: center; }
        .brand-tagline {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 26px; font-weight: 800;
            color: white; line-height: 1.2;
            margin-bottom: 12px;
        }
        .brand-sub {
            font-size: 13px; color: rgba(255,255,255,0.65);
            line-height: 1.6; max-width: 280px;
        }
        .brand-modules {
            display: flex; flex-wrap: wrap; gap: 8px;
            margin-top: 36px; justify-content: center;
        }
        .brand-pill {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 99px;
            padding: 5px 13px;
            font-size: 11px; font-weight: 500;
            color: rgba(255,255,255,0.8);
        }

        /* ── Right form panel ── */
        .auth-form-panel {
            display: flex; align-items: center; justify-content: center;
            padding: 32px 24px;
            position: relative;
            /* DÉZOOM DE 5% SUR LA PARTIE DROITE */
            transform: scale(0.95);
        }
        .theme-btn {
            position: absolute; top: 20px; right: 24px;
            width: 34px; height: 34px;
            border-radius: 9px;
            border: 1px solid var(--border);
            background: var(--bg-card);
            color: var(--text-muted);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 15px;
            transition: all 0.18s;
        }
        .theme-btn:hover { border-color: var(--brand-orange); color: var(--brand-orange); }

        .form-card {
            width: 100%; max-width: 400px;
        }

        /* Small logo for mobile / right panel top */
        .form-logo {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 28px;
        }
        .form-logo-text { font-family: 'Syne', sans-serif; font-size: 20px; font-weight: 800; color: var(--text-primary); }
        .form-logo-sub  { font-size: 12px; color: var(--brand-orange); font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; }

        /* Heading */
        .form-heading { font-family: 'Syne', sans-serif; font-size: 30px; font-weight: 800; color: var(--text-primary); margin:0px 0px 4px 90px; }
        .form-subheading { font-size: 12.5px; color: var(--text-muted); margin:0px 0px 28px 60px;; }

        /* Fields */
        .field-group { margin-bottom: 14px; }
        .field-label {
            display: block;
            font-size: 11px; font-weight: 700;
            letter-spacing: 0.06em; text-transform: uppercase;
            color: var(--text-sec);
            margin-bottom: 6px;
        }
        .field-wrap { position: relative; }
        .field-icon {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            font-size: 15px; color: var(--text-muted);
            pointer-events: none;
        }
        .field-input {
            width: 100%;
            padding: 9px 12px 9px 36px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--bg-input);
            color: var(--text-primary);
            font-size: 13px; font-family: inherit;
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .field-input:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px var(--brand-green-glow);
            background: var(--bg-card);
        }
        .field-input::placeholder { color: var(--text-muted); }
        .field-error { font-size: 11px; color: #e53935; margin-top: 4px; display: flex; align-items: center; gap: 4px; }

        /* Toggle password */
        .toggle-pw {
            position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: var(--text-muted); font-size: 15px;
            transition: color 0.15s;
        }
        .toggle-pw:hover { color: var(--brand-green); }

        /* Remember / forgot */
        .form-row {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px; margin-top: 2px;
        }
        .checkbox-wrap { display: flex; align-items: center; gap: 7px; cursor: pointer; }
        .checkbox-wrap input[type="checkbox"] {
            width: 14px; height: 14px;
            accent-color: var(--brand-green);
            border-radius: 4px;
        }
        .checkbox-wrap span { font-size: 12px; color: var(--text-sec); }
        .forgot-link { font-size: 12px; color: var(--brand-green); text-decoration: none; font-weight: 600; }
        .forgot-link:hover { color: var(--brand-green-light); }

        /* Submit */
        .btn-submit {
            width: 100%;
            padding: 11px;
            background: linear-gradient(135deg, var(--brand-green) 0%, var(--brand-green-light) 100%);
            border: none; border-radius: 11px;
            color: white; font-size: 13.5px; font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            box-shadow: 0 6px 18px var(--brand-green-glow);
            transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 7px;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(26,122,60,0.32); }
        .btn-submit:active { transform: translateY(0); }

        /* Divider */
        .divider { display: flex; align-items: center; gap: 10px; margin: 18px 0; }
        .divider hr { flex: 1; border: none; border-top: 1px solid var(--border); }
        .divider span { font-size: 11px; color: var(--text-muted); }

        /* Footer */
        .form-footer { text-align: center; font-size: 11px; color: var(--text-muted); margin-top: 20px; }
        .form-footer a { color: var(--brand-green); font-weight: 600; text-decoration: none; }

        /* Alerts */
        .alert-success {
            background: rgba(26,122,60,0.08); border: 1px solid rgba(26,122,60,0.2);
            border-radius: 10px; padding: 10px 14px;
            font-size: 12px; color: var(--brand-green);
            margin-bottom: 18px; display: flex; align-items: center; gap: 8px;
        }
        
    </style>
</head>
<body>
<div class="auth-wrap">

    <!-- Left brand panel -->
    <div class="auth-brand">
        <div class="brand-diamonds">
            @for($i = 0; $i < 40; $i++)<span></span>@endfor
        </div>
        <div class="brand-content">
            <div class="brand-jr"><img src="{{ asset('images/logo-jr.jpg') }}" width=170px style="border-radius:100px"></div>
            <div class="brand-tagline">Gérez votre<br>business, simplement avec</div>
             <div>
                    <div class="form-logo-text">Jr Computer</div>
                    <div class="form-logo-sub">ERP Platform</div>
                </div>
            <!-- <div class="brand-modules">
                <span class="brand-pill"><i class="bi bi-box-seam me-1"></i>Stock</span>
                <span class="brand-pill"><i class="bi bi-cart3 me-1"></i>Ventes</span>
                <span class="brand-pill"><i class="bi bi-people me-1"></i>Clients</span>
                <span class="brand-pill"><i class="bi bi-tools me-1"></i>SAV</span>
            </div> -->
        </div>
    </div>

    <!-- Right form panel -->
    <div class="auth-form-panel">
        <button class="theme-btn" id="themeBtn" title="Changer le thème">
            <i class="bi bi-moon" id="themeIcon"></i>
        </button>

        <div class="form-card">
            <div class="form-logo">
               
            </div>

            <h1 class="form-heading"> Connexion</h1>
            <p class="form-subheading">Connectez-vous à votre espace de gestion</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field-group">
                    <label class="field-label">Email</label>
                    <div class="field-wrap">
                        <i class="bi bi-envelope field-icon"></i>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="field-input" placeholder="vous@jrcomputer.cm"
                               required autofocus autocomplete="email">
                    </div>
                    @error('email')
                        <div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-group">
                    <label class="field-label">Mot de passe</label>
                    <div class="field-wrap">
                        <i class="bi bi-lock field-icon"></i>
                        <input type="password" name="password" id="pwField"
                               class="field-input" placeholder="••••••••"
                               required autocomplete="current-password">
                        <button type="button" class="toggle-pw" onclick="togglePw()" id="pwToggle">
                            <i class="bi bi-eye" id="pwEyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <label class="checkbox-wrap">
                        <input type="checkbox" name="remember">
                        <span>Se souvenir de moi</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="bi bi-box-arrow-in-right"></i> Se connecter
                </button>
            </form>

            <div class="form-footer">
                Sécurité garantie &nbsp;·&nbsp; © {{ date('Y') }} Jr Computer Sarl
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
        const cur = html.getAttribute('data-theme');
        const next = cur === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', next);
        document.getElementById('themeIcon').className = next === 'dark' ? 'bi bi-moon' : 'bi bi-sun';
        localStorage.setItem('jrTheme', next);
    });

    /* Toggle password */
    function togglePw() {
        const f = document.getElementById('pwField');
        const i = document.getElementById('pwEyeIcon');
        if (f.type === 'password') { f.type = 'text'; i.className = 'bi bi-eye-slash'; }
        else { f.type = 'password'; i.className = 'bi bi-eye'; }
    }
</script>
</body>
</html>