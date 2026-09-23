<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sécurité & données · {{ config('app.name', 'checkinHub') }}</title>
    <meta name="description" content="Comment checkinHub protège vos données : isolation par hôtel, chiffrement, sauvegardes, propriété et export de vos données.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --bg:#0b0a1a; --card:rgba(255,255,255,.045); --border:rgba(255,255,255,.10);
            --ink:#e8ecf6; --muted:#9aa6c2; --brand:#7c83ff; --brand2:#b06bff; --accent:#29e0c8; }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--bg); color:var(--ink); font-family:'Inter',system-ui,sans-serif; line-height:1.6;
            background-image:radial-gradient(60vw 60vw at 80% -10%, rgba(124,131,255,.20), transparent 60%),
                radial-gradient(50vw 50vw at 0% 15%, rgba(176,107,255,.14), transparent 60%); }
        a { text-decoration:none; color:inherit; }
        .wrap { max-width:900px; margin:0 auto; padding:0 20px; }
        .disp { font-family:'Space Grotesk',sans-serif; letter-spacing:-.02em; }
        .grad { background:linear-gradient(90deg,var(--brand),var(--brand2)); -webkit-background-clip:text; background-clip:text; color:transparent; }
        .top { display:flex; align-items:center; justify-content:space-between; padding:20px 0; }
        .brand { font-family:'Space Grotesk',sans-serif; font-weight:700; font-size:1.15rem; }
        .brand i { color:var(--brand); }
        .btn { display:inline-flex; align-items:center; gap:8px; border-radius:11px; padding:10px 16px; font-weight:600; font-size:.9rem; border:1px solid var(--border); }
        .btn:hover { background:rgba(255,255,255,.06); }
        .hero { text-align:center; padding:30px 0 10px; }
        .chip { display:inline-flex; align-items:center; gap:8px; font-size:.78rem; font-weight:600; color:var(--muted);
            border:1px solid var(--border); border-radius:30px; padding:6px 14px; margin-bottom:16px; }
        .hero h1 { font-size:clamp(2rem,5vw,2.8rem); margin:0 0 12px; }
        .hero p { color:var(--muted); max-width:600px; margin:0 auto; }
        .grid { display:grid; grid-template-columns:repeat(2,1fr); gap:16px; margin:34px 0; }
        @media (max-width:680px){ .grid{ grid-template-columns:1fr; } }
        .card { background:var(--card); border:1px solid var(--border); border-radius:16px; padding:22px; backdrop-filter:blur(8px); }
        .card-ico { width:44px; height:44px; border-radius:12px; display:grid; place-items:center; font-size:1.1rem;
            background:linear-gradient(135deg,rgba(124,131,255,.25),rgba(176,107,255,.2)); color:var(--brand); margin-bottom:14px; }
        .card h3 { font-family:'Space Grotesk',sans-serif; font-size:1.08rem; margin:0 0 8px; }
        .card p { color:var(--muted); font-size:.92rem; margin:0; }
        .cta { text-align:center; background:var(--card); border:1px solid var(--border); border-radius:20px; padding:32px 22px; margin:20px 0 50px; }
        .cta h2 { margin:0 0 8px; } .cta p { color:var(--muted); margin:0 0 18px; }
        .btn-primary { background:linear-gradient(90deg,var(--brand),var(--brand2)); color:#fff; border:0; box-shadow:0 12px 30px -12px var(--brand); }
        .foot { text-align:center; color:var(--muted); font-size:.82rem; padding-bottom:34px; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="top">
            <a href="{{ route('landing') }}" class="brand"><i class="fas fa-location-dot"></i> check<span class="grad">inHub</span></a>
            <a href="{{ route('landing') }}" class="btn"><i class="fas fa-arrow-left"></i> Accueil</a>
        </div>

        <div class="hero">
            <span class="chip"><i class="fas fa-shield-halved" style="color:var(--brand)"></i> Sécurité & données</span>
            <h1 class="disp">Vos données sont <span class="grad">protégées</span></h1>
            <p>checkinHub gère les clients, les paiements et le chiffre d'affaires de votre hôtel. Voici comment nous protégeons ces informations.</p>
        </div>

        @php
            $cards = [
                ['fa-database', 'Isolation par établissement', "Chaque hôtel est strictement cloisonné : vos réservations, clients et paiements ne sont jamais visibles par un autre établissement. L'isolation est appliquée automatiquement à chaque requête."],
                ['fa-lock', 'Connexion chiffrée', "Les échanges avec la plateforme passent par une connexion sécurisée (HTTPS/TLS). Les mots de passe ne sont jamais stockés en clair : ils sont hachés."],
                ['fa-users-gear', "Contrôle d'accès par rôle", "Chaque membre de l'équipe a son propre compte et des droits limités (réception, caisse, ménage, service). Vous n'avez plus à partager votre mot de passe."],
                ['fa-cloud-arrow-up', 'Sauvegardes', "Vos données sont sauvegardées régulièrement pour éviter toute perte. En cas d'incident, nous pouvons restaurer une version récente."],
                ['fa-file-export', 'Vos données vous appartiennent', "Vous restez propriétaire de vos données. Vous pouvez demander leur export à tout moment, et leur suppression si vous quittez la plateforme."],
                ['fa-user-shield', 'Confidentialité', "Nous n'utilisons vos données que pour faire fonctionner votre hôtel. Elles ne sont ni revendues ni cédées à des tiers à des fins commerciales."],
            ];
        @endphp
        <div class="grid">
            @foreach ($cards as $c)
                <div class="card">
                    <div class="card-ico"><i class="fas {{ $c[0] }}"></i></div>
                    <h3>{{ $c[1] }}</h3>
                    <p>{{ $c[2] }}</p>
                </div>
            @endforeach
        </div>

        <div class="cta">
            <h2 class="disp">Une question sur la sécurité ?</h2>
            <p>Nous répondons clairement, sans jargon.</p>
            <a href="{{ route('demo') }}" class="btn btn-primary"><i class="fas fa-circle-play"></i> Voir la démo</a>
        </div>

        <div class="foot">
            <a href="{{ route('landing') }}" style="color:var(--muted)"><i class="fas fa-arrow-left"></i> Retour à l'accueil</a>
        </div>
    </div>
    @include('partials.whatsapp-fab')
</body>
</html>
