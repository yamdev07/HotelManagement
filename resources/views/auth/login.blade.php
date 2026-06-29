<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion · {{ config('app.name', 'MyHotel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --brand:#4f46e5; --brand2:#7c3aed; --ink:#0f172a; }
        * { font-family:'Inter',system-ui,sans-serif; }
        body { margin:0; min-height:100vh; background:linear-gradient(135deg,#eef2ff,#faf5ff); display:flex; align-items:center; justify-content:center; padding:1.5rem; color:var(--ink); }
        .auth { width:100%; max-width:960px; background:#fff; border-radius:24px; overflow:hidden; box-shadow:0 40px 80px -30px rgba(79,70,229,.45); display:grid; grid-template-columns:1fr 1fr; }
        .auth-left { position:relative; padding:3rem; color:#fff; background:linear-gradient(150deg,var(--brand),var(--brand2)); overflow:hidden; }
        .auth-left .blob { position:absolute; border-radius:50%; background:rgba(255,255,255,.12); }
        .brand { font-size:1.7rem; font-weight:800; display:flex; align-items:center; gap:.6rem; }
        .feat { display:flex; gap:1rem; align-items:flex-start; margin-top:1.5rem; }
        .feat-ico { width:42px;height:42px;border-radius:12px;background:rgba(255,255,255,.18);display:grid;place-items:center;flex-shrink:0; }
        .auth-right { padding:3.2rem 3rem; }
        .form-control { border-radius:12px; padding:.8rem 1rem .8rem 2.6rem; border:1px solid #e2e8f0; background:#f8fafc; }
        .form-control:focus { border-color:var(--brand); box-shadow:0 0 0 .2rem rgba(79,70,229,.15); background:#fff; }
        .input-ico { position:absolute; left:.9rem; top:50%; transform:translateY(-50%); color:#94a3b8; }
        .btn-brand { background:var(--brand); border:none; color:#fff; border-radius:12px; padding:.85rem; font-weight:600; width:100%; transition:.25s; }
        .btn-brand:hover { background:#4338ca; transform:translateY(-2px); box-shadow:0 14px 30px -12px var(--brand); color:#fff; }
        .link-brand { color:var(--brand); font-weight:600; text-decoration:none; }
        @media (max-width:768px){ .auth{ grid-template-columns:1fr; } .auth-left{ display:none; } }
    </style>
</head>
<body>
    <div class="auth">
        <!-- Panneau marque -->
        <div class="auth-left d-flex flex-column">
            <div class="blob" style="width:220px;height:220px;top:-60px;right:-50px;"></div>
            <div class="blob" style="width:140px;height:140px;bottom:-30px;left:-30px;"></div>
            <div style="position:relative;z-index:1;">
                <div class="brand"><i class="fas fa-hotel"></i> {{ config('app.name', 'MyHotel') }}</div>
                <p class="mt-2 mb-0" style="opacity:.85;">La plateforme de gestion hôtelière tout-en-un</p>

                <div class="mt-5">
                    <div class="feat"><div class="feat-ico"><i class="fas fa-shield-halved"></i></div>
                        <div><div class="fw-semibold">Sécurité garantie</div><div class="small" style="opacity:.8;">Données isolées par établissement</div></div></div>
                    <div class="feat"><div class="feat-ico"><i class="fas fa-bolt"></i></div>
                        <div><div class="fw-semibold">Tout-en-un</div><div class="small" style="opacity:.8;">Réservations, caisse, restaurant, ménage</div></div></div>
                    <div class="feat"><div class="feat-ico"><i class="fas fa-headset"></i></div>
                        <div><div class="fw-semibold">Support 24/7</div><div class="small" style="opacity:.8;">Une équipe à votre écoute</div></div></div>
                </div>
            </div>
            <div class="mt-auto pt-4 small" style="opacity:.7;position:relative;z-index:1;">© {{ date('Y') }} {{ config('app.name', 'MyHotel') }}</div>
        </div>

        <!-- Formulaire -->
        <div class="auth-right">
            <h3 class="fw-bold mb-1">Bon retour 👋</h3>
            <p class="text-secondary mb-4">Connectez-vous à votre espace.</p>

            @if (session('failed') || session('error'))
                <div class="alert alert-danger py-2">{{ session('failed') ?? session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="alert alert-success py-2">{{ session('success') }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-500">Adresse email</label>
                    <div class="position-relative">
                        <i class="fas fa-envelope input-ico"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="form-control @error('email') is-invalid @enderror" placeholder="vous@exemple.com">
                    </div>
                    @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-500">Mot de passe</label>
                    <div class="position-relative">
                        <i class="fas fa-lock input-ico"></i>
                        <input type="password" name="password" required
                               class="form-control @error('password') is-invalid @enderror" placeholder="••••••••">
                    </div>
                    @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label small" for="remember">Se souvenir de moi</label>
                    </div>
                    <a href="/forgot-password" class="link-brand small">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="btn-brand"><i class="fas fa-arrow-right-to-bracket me-2"></i>Se connecter</button>
            </form>

            <p class="text-center text-secondary small mt-4 mb-0">
                Pas encore de compte ? <a href="{{ route('hotel.register') }}" class="link-brand">Essai gratuit</a>
            </p>
        </div>
    </div>
</body>
</html>
