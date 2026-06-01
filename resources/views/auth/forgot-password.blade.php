<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jr Computer · Réinitialisation</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            transition: background 0.3s, color 0.3s;
            /* ===== DÉZOOM DE 5% : zoom global à 0.95 ===== */
            transform: scale(0.95);
            transform-origin: center center;
        }

        /* Ajustement pour éviter que le zoom ne coupe les bords */
        .fp-card {
            width: 100%; 
            max-width: 420px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: 0 24px 60px rgba(26,122,60,0.10);
            overflow: hidden;
            position: relative;
        }

        /* Top strip */
        .fp-strip {
            height: 4px;
            background: linear-gradient(90deg, var(--brand-green), var(--brand-green-light), var(--brand-orange));
        }

        .fp-body { padding: 32px 32px 28px; }

        /* Back link */
        .back-link {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 12px; font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            margin-bottom: 24px;
            transition: color 0.15s;
        }
        .back-link:hover { color: var(--brand-green); }

        /* Icon */
        .fp-icon {
            width: 56px; height: 56px; border-radius: 16px;
            background: linear-gradient(135deg, rgba(26,122,60,0.12), rgba(34,163,82,0.08));
            border: 1px solid rgba(26,122,60,0.18);
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; color: var(--brand-green);
            margin-bottom: 5px;
        }

        .fp-title {  font-size: 30px; font-weight: 800; color: var(--text-primary); margin-bottom: 8px; }
        .fp-desc { font-size: 12.5px; color: var(--text-muted); line-height: 1.65; margin-bottom: 24px; }

        /* Alert */
        .alert-success {
            display: flex; align-items: flex-start; gap: 10px;
            background: rgba(26,122,60,0.08);
            border: 1px solid rgba(26,122,60,0.2);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 20px;
        }
        .alert-success i { color: var(--brand-green); font-size: 16px; flex-shrink: 0; margin-top: 1px; }
        .alert-success p { font-size: 12px; color: var(--brand-green); line-height: 1.5; }

        /* Fields */
        .field-group { margin-bottom: 18px; }
        .field-label {
            display: block; font-size: 11px; font-weight: 700;
            letter-spacing: 0.06em; text-transform: uppercase;
            color: var(--text-sec); margin-bottom: 6px;
        }
        .field-wrap { position: relative; }
        .field-icon {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            font-size: 15px; color: var(--text-muted); pointer-events: none;
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
            border-color: var(--brand-green);
            box-shadow: 0 0 0 3px var(--brand-green-glow);
            background: var(--bg-card);
        }
        .field-input::placeholder { color: var(--text-muted); }
        .field-error { font-size: 11px; color: #e53935; margin-top: 4px; display: flex; align-items: center; gap: 4px; }

        /* Button */
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

        /* Footer */
        .fp-footer {
            text-align: center; font-size: 11px; color: var(--text-muted); margin-top: 20px;
        }
        .fp-footer a { color: var(--brand-green); font-weight: 600; text-decoration: none; }

        /* Theme btn */
        .theme-btn {
            position: absolute; top: 16px; right: 16px;
            width: 32px; height: 32px; border-radius: 8px;
            border: 1px solid var(--border); background: var(--bg-input);
            color: var(--text-muted); display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 14px; transition: all 0.18s;
        }
        .theme-btn:hover { border-color: var(--brand-orange); color: var(--brand-orange); }

        /* Petit ajustement responsif pour éviter que le zoom ne génère du scroll horizontal sur mobile */
        @media (max-width: 500px) {
            body {
                transform: scale(0.95);
                padding: 10px;
            }
        }
    </style>
</head>
<body>
<div class="fp-card">
    <div class="fp-strip"></div>
    <div class="fp-body">
        <button class="theme-btn" id="themeBtn" title="Thème"><i class="bi bi-moon" id="themeIcon"></i></button>

        <a href="{{ route('login') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Retour à la connexion
        </a>

        <div class="fp-icon"><i class="bi bi-shield-lock"></i></div>

        <h1 class="fp-title">Mot de passe oublié ?</h1>
        <p class="fp-desc">Pas de souci. Renseignez votre adresse email et nous vous enverrons un lien pour créer un nouveau mot de passe.</p>

        @if(session('status'))
        <div class="alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <p>{{ session('status') }}</p>
        </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="field-group">
                <label class="field-label">Adresse email</label>
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

            <button type="submit" class="btn-submit">
                <i class="bi bi-send"></i> Envoyer le lien de réinitialisation
            </button>
        </form>

        <div class="fp-footer">
            © {{ date('Y') }} Jr Computer Sarl &nbsp;·&nbsp; <a href="{{ route('login') }}">Se connecter</a>
        </div>
    </div>
</div>

<script>
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
</script>
</body>
</html>