<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>La démo en 2 minutes · {{ config('app.name', 'checkinHub') }}</title>
    <meta name="description" content="Découvrez checkinHub en action : le parcours complet d'une réservation, de la prise en ligne au rapport du jour, en 6 étapes.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg:#0b0a1a; --card:rgba(255,255,255,.045); --border:rgba(255,255,255,.10);
            --ink:#e8ecf6; --muted:#9aa6c2; --brand:#7c83ff; --brand2:#b06bff; --accent:#29e0c8;
        }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--bg); color:var(--ink); font-family:'Inter',system-ui,sans-serif; line-height:1.55;
            background-image:
                radial-gradient(60vw 60vw at 80% -10%, rgba(124,131,255,.22), transparent 60%),
                radial-gradient(50vw 50vw at 0% 20%, rgba(176,107,255,.16), transparent 60%); }
        a { text-decoration:none; color:inherit; }
        .wrap { max-width:960px; margin:0 auto; padding:0 20px; }
        .disp { font-family:'Space Grotesk',sans-serif; letter-spacing:-.02em; }
        .grad { background:linear-gradient(90deg,var(--brand),var(--brand2)); -webkit-background-clip:text; background-clip:text; color:transparent; }
        /* top bar */
        .top { display:flex; align-items:center; justify-content:space-between; padding:20px 0; }
        .brand { font-family:'Space Grotesk',sans-serif; font-weight:700; font-size:1.15rem; }
        .brand i { color:var(--brand); }
        .btn { display:inline-flex; align-items:center; gap:8px; border-radius:11px; padding:11px 18px; font-weight:600; font-size:.92rem; border:1px solid transparent; cursor:pointer; }
        .btn-primary { background:linear-gradient(90deg,var(--brand),var(--brand2)); color:#fff; box-shadow:0 12px 30px -12px var(--brand); }
        .btn-primary:hover { filter:brightness(1.08); }
        .btn-ghost { border-color:var(--border); color:var(--ink); }
        .btn-ghost:hover { background:rgba(255,255,255,.06); }
        /* hero */
        .hero { text-align:center; padding:36px 0 8px; }
        .chip { display:inline-flex; align-items:center; gap:8px; font-size:.78rem; font-weight:600; color:var(--muted);
            border:1px solid var(--border); border-radius:30px; padding:6px 14px; margin-bottom:18px; }
        .hero h1 { font-size:clamp(2rem,5vw,3rem); margin:0 0 14px; line-height:1.05; }
        .hero p { color:var(--muted); font-size:1.08rem; max-width:620px; margin:0 auto; }
        /* steps */
        .steps { position:relative; padding:44px 0 8px; }
        .steps::before { content:''; position:absolute; left:35px; top:60px; bottom:60px; width:2px;
            background:linear-gradient(var(--brand),var(--brand2),transparent); opacity:.35; }
        @media (max-width:640px){ .steps::before{ display:none; } }
        .step { display:flex; gap:20px; align-items:flex-start; margin-bottom:18px; position:relative;
            opacity:0; transform:translateY(14px); animation:rise .5s ease forwards; }
        @keyframes rise { to { opacity:1; transform:none; } }
        .step-num { flex:none; width:54px; height:54px; border-radius:16px; display:grid; place-items:center;
            font-family:'Space Grotesk',sans-serif; font-weight:700; font-size:1.2rem; color:#fff;
            background:linear-gradient(135deg,var(--brand),var(--brand2)); box-shadow:0 10px 26px -10px var(--brand); z-index:1; }
        .step-body { flex:1; background:var(--card); border:1px solid var(--border); border-radius:16px; padding:18px 20px; backdrop-filter:blur(8px); }
        .step-title { font-family:'Space Grotesk',sans-serif; font-weight:600; font-size:1.12rem; margin:0 0 4px; display:flex; align-items:center; gap:10px; }
        .step-title i { color:var(--accent); font-size:1rem; }
        .step-desc { color:var(--muted); font-size:.95rem; margin:0; }
        .step-tag { display:inline-block; margin-top:10px; font-size:.72rem; font-weight:600; color:var(--brand);
            background:rgba(124,131,255,.14); border-radius:20px; padding:3px 10px; }
        /* cta */
        .cta { text-align:center; margin:40px 0 60px; background:var(--card); border:1px solid var(--border);
            border-radius:22px; padding:38px 24px; }
        .cta h2 { font-size:clamp(1.4rem,3.5vw,2rem); margin:0 0 8px; }
        .cta p { color:var(--muted); margin:0 0 22px; }
        .cta-row { display:flex; gap:12px; justify-content:center; flex-wrap:wrap; }
        .foot { text-align:center; color:var(--muted); font-size:.82rem; padding-bottom:34px; }
        @media (prefers-reduced-motion:reduce){ .step{ animation:none; opacity:1; transform:none; } }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="top">
            <a href="{{ route('landing') }}" class="brand"><i class="fas fa-location-dot"></i> check<span class="grad">inHub</span></a>
            <a href="{{ route('hotel.register') }}" class="btn btn-primary"><i class="fas fa-rocket"></i> Essai gratuit</a>
        </div>

        <div class="hero">
            <span class="chip"><i class="fas fa-circle-play" style="color:var(--brand)"></i> La démo en 2 minutes</span>
            <h1 class="disp">Une réservation, <span class="grad">du début à la fin</span></h1>
            <p>Voici tout ce qui se passe dans checkinHub quand un client réserve, arrive et repart. Six étapes, un seul écran.</p>
        </div>

        @php
            $steps = [
                ['fa-calendar-check', 'Réservation', "Le client réserve en ligne depuis votre mini-site, ou vous la saisissez à la réception. La disponibilité se met à jour en temps réel, sans double réservation.", 'En ligne ou réception'],
                ['fa-bed', "Attribution de la chambre", "Vous attribuez une chambre selon le type et la capacité. Le planning se met à jour et bloque automatiquement les dates.", 'Planning centralisé'],
                ['fa-id-card', 'Check-in', "Enregistrez l'arrivée en quelques secondes : identité du client, dates, acompte. Le statut passe à « occupée ».", 'Arrivée en 2 min'],
                ['fa-cash-register', 'Encaissement', "Encaissez à la réception ou via Mobile Money. Chaque paiement est tracé, le solde restant est calculé tout seul.", 'Caisse et Mobile Money'],
                ['fa-broom', 'Housekeeping', "Au départ, la chambre passe en « à nettoyer ». L'équipe ménage voit sa liste en direct et marque le nettoyage fait.", 'Ménage en temps réel'],
                ['fa-chart-line', 'Rapport du jour', "Arrivées, départs, chiffre d'affaires et taux d'occupation, d'un coup d'œil. Le propriétaire suit tout, même à distance.", 'Pilotage à distance'],
            ];
        @endphp
        <div class="steps">
            @foreach ($steps as $i => $s)
                <div class="step" style="animation-delay:{{ $i * 0.08 }}s">
                    <div class="step-num">{{ $i + 1 }}</div>
                    <div class="step-body">
                        <h3 class="step-title"><i class="fas {{ $s[0] }}"></i> {{ $s[1] }}</h3>
                        <p class="step-desc">{{ $s[2] }}</p>
                        <span class="step-tag">{{ $s[3] }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="cta">
            <h2 class="disp">Prêt à gérer votre hôtel comme ça ?</h2>
            <p>Essai gratuit de {{ config('plans.trial_days', 14) }} jours. Sans carte bancaire.</p>
            <div class="cta-row">
                <a href="{{ route('hotel.register') }}" class="btn btn-primary"><i class="fas fa-rocket"></i> Essayer gratuitement</a>
                <a href="{{ route('landing') }}#pricing" class="btn btn-ghost"><i class="fas fa-tags"></i> Voir les tarifs</a>
            </div>
        </div>

        <div class="foot">
            <a href="{{ route('landing') }}" style="color:var(--muted)"><i class="fas fa-arrow-left"></i> Retour à l'accueil</a>
        </div>
    </div>
    @include('partials.whatsapp-fab')
</body>
</html>
