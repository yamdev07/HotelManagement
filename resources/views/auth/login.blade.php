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
        * { font-family:'Inter',system-ui,sans-serif; box-sizing:border-box; }
        html,body { height:100%; }
        body { margin:0; color:var(--ink); overflow:hidden; }

        .split { display:grid; grid-template-columns:1.05fr 1fr; height:100vh; }

        /* ---------- Panneau marque (plein écran) ---------- */
        .side {
            position:relative; padding:4rem; color:#fff; overflow:hidden;
            background:linear-gradient(135deg,var(--brand),var(--brand2),#6d28d9);
            background-size:200% 200%; animation:grad 12s ease infinite;
            display:flex; flex-direction:column; justify-content:center;
        }
        @keyframes grad { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
        .blob { position:absolute; border-radius:50%; filter:blur(2px); background:rgba(255,255,255,.10); animation:float 9s ease-in-out infinite; }
        @keyframes float { 0%,100%{ transform:translateY(0) } 50%{ transform:translateY(-26px) } }
        .grid-deco { position:absolute; inset:0; background-image:radial-gradient(rgba(255,255,255,.12) 1px,transparent 1px); background-size:26px 26px; opacity:.4; mask-image:linear-gradient(180deg,transparent,#000 40%,transparent); }
        .side-inner { position:relative; z-index:2; max-width:460px; }
        .brand { font-size:2rem; font-weight:800; display:flex; align-items:center; gap:.7rem; }
        .side h1 { font-size:clamp(2rem,3.2vw,3rem); font-weight:800; line-height:1.1; letter-spacing:-.02em; margin:2rem 0 1rem; }
        .feat { display:flex; gap:1rem; align-items:flex-start; margin-top:1.4rem; }
        .feat-ico { width:46px;height:46px;border-radius:14px;background:rgba(255,255,255,.16);display:grid;place-items:center;flex-shrink:0;backdrop-filter:blur(4px); }

        /* ---------- Formulaire (plein écran) ---------- */
        .panel { display:flex; align-items:center; justify-content:center; padding:2rem; background:#fff; }
        .form-wrap { width:100%; max-width:420px; }
        .form-control { border-radius:14px; padding:.9rem 1rem .9rem 2.8rem; border:1px solid #e5e7eb; background:#f8fafc; transition:.25s; }
        .form-control:focus { border-color:var(--brand); box-shadow:0 0 0 .25rem rgba(79,70,229,.15); background:#fff; }
        .input-ico { position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:#94a3b8; transition:.25s; }
        .position-relative:focus-within .input-ico { color:var(--brand); }
        .btn-brand { background:linear-gradient(135deg,var(--brand),var(--brand2)); border:none; color:#fff; border-radius:14px; padding:.95rem; font-weight:600; width:100%; transition:.25s; }
        .btn-brand:hover { transform:translateY(-2px); box-shadow:0 16px 34px -12px var(--brand); color:#fff; filter:brightness(1.05); }
        .link-brand { color:var(--brand); font-weight:600; text-decoration:none; }

        /* ---------- Animations d'entrée ---------- */
        @keyframes up { from{ opacity:0; transform:translateY(22px) } to{ opacity:1; transform:none } }
        @keyframes inLeft { from{ opacity:0; transform:translateX(-26px) } to{ opacity:1; transform:none } }
        .anim { opacity:0; animation:up .7s cubic-bezier(.2,.7,.2,1) forwards; }
        .anim-l { opacity:0; animation:inLeft .8s cubic-bezier(.2,.7,.2,1) forwards; }
        .d1{animation-delay:.05s} .d2{animation-delay:.15s} .d3{animation-delay:.25s} .d4{animation-delay:.35s} .d5{animation-delay:.45s} .d6{animation-delay:.55s}

        @media (max-width:860px){
            body{ overflow:auto; }
            .split{ grid-template-columns:1fr; height:auto; min-height:100vh; }
            .side{ min-height:38vh; padding:2.5rem; }
        }
        @media (prefers-reduced-motion: reduce){ *{ animation:none!important; } .anim,.anim-l{ opacity:1!important; } }
    </style>
</head>
<body>
<div class="split">
    <!-- MARQUE -->
    <aside class="side">
        <div class="grid-deco"></div>
        <span class="blob" style="width:260px;height:260px;top:-40px;right:-60px;"></span>
        <span class="blob" style="width:170px;height:170px;bottom:6%;left:-50px;animation-delay:-3s;"></span>
        <span class="blob" style="width:90px;height:90px;top:30%;right:22%;animation-delay:-6s;"></span>

        <div class="side-inner">
            <div class="brand anim-l d1"><i class="fas fa-hotel"></i> {{ config('app.name', 'MyHotel') }}</div>
            <h1 class="anim-l d2">Gérez votre hôtel,<br>sans la complexité.</h1>
            <p class="anim-l d3" style="opacity:.9;font-size:1.05rem;">Réservations, caisse, restaurant, housekeeping et rapports — réunis sur une seule plateforme.</p>

            <div class="anim-l d4 feat"><div class="feat-ico"><i class="fas fa-shield-halved"></i></div>
                <div><div class="fw-semibold">Sécurité garantie</div><div class="small" style="opacity:.8;">Données isolées par établissement</div></div></div>
            <div class="anim-l d5 feat"><div class="feat-ico"><i class="fas fa-bolt"></i></div>
                <div><div class="fw-semibold">Tout-en-un</div><div class="small" style="opacity:.8;">Tous vos outils au même endroit</div></div></div>
            <div class="anim-l d6 feat"><div class="feat-ico"><i class="fas fa-headset"></i></div>
                <div><div class="fw-semibold">Support 24/7</div><div class="small" style="opacity:.8;">Une équipe à votre écoute</div></div></div>
        </div>
    </aside>

    <!-- FORMULAIRE -->
    <section class="panel">
        <div class="form-wrap">
            <div class="anim d1">
                <h2 class="fw-bold mb-1">Bon retour 👋</h2>
                <p class="text-secondary mb-4">Connectez-vous à votre espace.</p>
            </div>

            @if (session('failed') || session('error'))
                <div class="alert alert-danger py-2 anim">{{ session('failed') ?? session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="alert alert-success py-2 anim">{{ session('success') }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3 anim d2">
                    <label class="form-label fw-semibold">Adresse email</label>
                    <div class="position-relative">
                        <i class="fas fa-envelope input-ico"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="form-control @error('email') is-invalid @enderror" placeholder="vous@exemple.com">
                    </div>
                    @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3 anim d3">
                    <label class="form-label fw-semibold">Mot de passe</label>
                    <div class="position-relative">
                        <i class="fas fa-lock input-ico"></i>
                        <input type="password" name="password" required
                               class="form-control @error('password') is-invalid @enderror" placeholder="••••••••">
                    </div>
                    @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4 anim d4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label small" for="remember">Se souvenir de moi</label>
                    </div>
                    <a href="/forgot-password" class="link-brand small">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="btn-brand anim d5"><i class="fas fa-arrow-right-to-bracket me-2"></i>Se connecter</button>
            </form>

            <p class="text-center text-secondary small mt-4 mb-0 anim d6">
                Pas encore de compte ? <a href="{{ route('hotel.register') }}" class="link-brand">Démarrer l'essai gratuit</a>
            </p>
        </div>
    </section>
</div>
</body>
</html>
