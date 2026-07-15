<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Guide d'utilisation — {{ config('app.name', 'checkinHub') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root{--bg:#070b16;--bg2:#0b1122;--panel:rgba(255,255,255,.035);--border:rgba(255,255,255,.09);
            --txt:#e8ecf6;--muted:#93a0bd;--brand:#7c83ff;--brand2:#b06bff;--accent:#29e0c8;}
        *{box-sizing:border-box;font-family:'Inter',system-ui,sans-serif;}
        body{margin:0;background:var(--bg);color:var(--txt);line-height:1.65;}
        h1,h2,h3,.dfont{font-family:'Space Grotesk',sans-serif;letter-spacing:-.4px;color:#fff;}
        a{color:var(--brand);text-decoration:none;}
        .cosmos{position:fixed;inset:0;z-index:-1;background:
            radial-gradient(800px 400px at 80% -5%,rgba(124,131,255,.16),transparent 60%),
            radial-gradient(700px 400px at 5% 5%,rgba(176,107,255,.12),transparent 55%),
            linear-gradient(180deg,var(--bg),var(--bg2));}
        .nav{position:sticky;top:0;z-index:30;display:flex;align-items:center;gap:16px;padding:14px 26px;
            border-bottom:1px solid var(--border);background:rgba(7,11,22,.72);backdrop-filter:blur(14px);}
        .logo{font-family:'Space Grotesk';font-weight:700;font-size:1.2rem;color:#fff;display:flex;align-items:center;gap:8px;}
        .logo i{color:var(--brand);}
        .logo span{background:linear-gradient(90deg,var(--brand),var(--brand2));-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;}
        .btn{border-radius:11px;padding:9px 16px;font-weight:600;font-size:.85rem;}
        .btn-glow{background:linear-gradient(90deg,var(--brand),var(--brand2));color:#fff;box-shadow:0 12px 30px -12px rgba(124,131,255,.6);}
        .btn-ghost{border:1px solid var(--border);color:var(--txt);}
        .wrap{max-width:1180px;margin:0 auto;display:grid;grid-template-columns:250px 1fr;gap:40px;padding:36px 26px 80px;}
        /* TOC */
        .toc{position:sticky;top:80px;align-self:start;max-height:calc(100vh - 100px);overflow-y:auto;}
        .toc .t-title{font-size:.7rem;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin:0 0 12px;}
        .toc a{display:block;color:var(--muted);font-size:.85rem;padding:7px 12px;border-radius:9px;margin-bottom:2px;border-left:2px solid transparent;}
        .toc a:hover{color:#fff;background:var(--panel);}
        .toc a.active{color:#fff;border-left-color:var(--brand);background:var(--panel);}
        /* content */
        .content{min-width:0;}
        .hero{margin-bottom:40px;}
        .hero .chip{display:inline-flex;align-items:center;gap:7px;padding:.4rem .9rem;border:1px solid var(--border);
            border-radius:999px;background:var(--panel);font-size:.78rem;color:var(--muted);margin-bottom:14px;}
        .hero h1{font-size:clamp(1.9rem,3.6vw,2.6rem);margin:0 0 10px;}
        .hero p{color:var(--muted);font-size:1.05rem;max-width:640px;}
        section.doc{scroll-margin-top:90px;margin-bottom:46px;padding-bottom:8px;}
        section.doc h2{font-size:1.35rem;display:flex;align-items:center;gap:12px;margin:0 0 8px;}
        .sico{width:40px;height:40px;border-radius:11px;display:grid;place-items:center;font-size:1.05rem;color:#fff;
            background:linear-gradient(135deg,rgba(124,131,255,.4),rgba(176,107,255,.25));border:1px solid var(--border);flex-shrink:0;}
        section.doc p{color:var(--muted);}
        .steps{list-style:none;padding:0;margin:16px 0 0;counter-reset:s;}
        .steps li{position:relative;padding:0 0 16px 44px;counter-increment:s;}
        .steps li::before{content:counter(s);position:absolute;left:0;top:-2px;width:28px;height:28px;border-radius:50%;
            background:linear-gradient(135deg,var(--brand),var(--brand2));color:#fff;font-family:'Space Grotesk';font-weight:700;
            font-size:.8rem;display:grid;place-items:center;}
        .steps li:not(:last-child)::after{content:'';position:absolute;left:13px;top:28px;bottom:2px;width:2px;background:var(--border);}
        .steps li b{color:#fff;}
        .tip{display:flex;gap:12px;background:rgba(41,224,200,.08);border:1px solid rgba(41,224,200,.25);
            border-radius:12px;padding:12px 16px;margin-top:16px;font-size:.9rem;color:#cdeee7;}
        .tip i{color:var(--accent);margin-top:3px;}
        .note{display:flex;gap:12px;background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.25);
            border-radius:12px;padding:12px 16px;margin-top:16px;font-size:.9rem;color:#f3e2b6;}
        .note i{color:#fbbf24;margin-top:3px;}
        .cta-final{background:linear-gradient(135deg,rgba(124,131,255,.18),rgba(176,107,255,.12));
            border:1px solid var(--border);border-radius:18px;padding:28px;text-align:center;margin-top:20px;}
        @media (max-width:900px){.wrap{grid-template-columns:1fr;}.toc{display:none;}}
    </style>
</head>
<body>
<div class="cosmos"></div>

<nav class="nav">
    <a href="{{ route('landing') }}" class="logo"><i class="fas fa-location-dot"></i> check<span>inHub</span></a>
    <div style="flex:1"></div>
    <a href="{{ route('landing') }}" class="btn btn-ghost"><i class="fas fa-arrow-left me-1"></i> Site</a>
    <a href="{{ route('login.index') }}" class="btn btn-ghost">Connexion</a>
    <a href="{{ route('hotel.register') }}" class="btn btn-glow">Essai gratuit</a>
</nav>

@php
    $sections = [
        ['start','fa-flag-checkered','Premiers pas','Après votre inscription',
            "Dès la validation de votre essai (ou de votre paiement), vous recevez vos identifiants par email — pensez à vérifier vos spams. Connectez-vous sur la page /login avec l'email et le mot de passe reçus."],
        ['brand','fa-palette','Personnaliser votre établissement','Couleurs, logo & site',
            "À la première connexion, un assistant vous permet de définir le nom affiché, votre logo et vos couleurs. Vous pouvez y revenir à tout moment depuis « Mon établissement »."],
        ['rooms','fa-bed','Configurer vos chambres','Types & chambres',
            "Créez d'abord vos types de chambre (Standard, Suite…), puis vos chambres avec leur numéro, capacité et prix."],
        ['bookings','fa-calendar-check','Réservations & check-in','Le cœur du métier',
            "Enregistrez une réservation, effectuez le check-in à l'arrivée du client et le check-out au départ, en quelques clics."],
        ['cashier','fa-cash-register','La caisse','Encaissements',
            "Ouvrez votre caisse en début de service, encaissez les paiements, puis fermez la caisse en fin de journée pour le rapprochement."],
        ['housekeeping','fa-broom','Housekeeping','Ménage & statuts',
            "Suivez l'état des chambres (à nettoyer, en nettoyage, propre) et assignez les tâches à votre équipe. (Offres Pro & Business)"],
        ['restaurant','fa-utensils','Restaurant','Commandes & service',
            "Gérez votre carte, les commandes et le service en chambre depuis le module Restaurant. (Offres Pro & Business)"],
        ['site','fa-globe','Votre site web','Vitrine & réservations en ligne',
            "Chaque établissement dispose d'un mini-site à ses couleurs pour présenter ses chambres et recevoir des réservations en ligne."],
        ['reports','fa-chart-line','Rapports','Pilotage',
            "Suivez votre occupation, vos revenus et la performance de votre établissement. (Offres Pro & Business)"],
        ['staff','fa-user-tie','Votre personnel','Comptes & rôles',
            "Créez des comptes pour vos réceptionnistes, votre équipe de ménage, etc., chacun avec ses droits."],
        ['billing','fa-credit-card','Abonnement & paiement','Gérer votre offre',
            "Depuis « Mon abonnement », consultez votre échéance, changez d'offre et renouvelez en ligne (Mobile Money & carte)."],
    ];
@endphp

<div class="wrap">
    <!-- TOC -->
    <aside class="toc">
        <div class="t-title">Sur cette page</div>
        @foreach ($sections as $s)
            <a href="#{{ $s[0] }}" class="toc-link">{{ $s[2] }}</a>
        @endforeach
        <a href="#support" class="toc-link">Support & aide</a>
    </aside>

    <!-- CONTENT -->
    <main class="content">
        <div class="hero">
            <span class="chip"><i class="fas fa-book-open" style="color:var(--accent)"></i> Guide d'utilisation</span>
            <h1>Prenez en main {{ config('app.name', 'checkinHub') }} en quelques minutes</h1>
            <p>Ce guide vous accompagne pas à pas, de votre première connexion à la gestion quotidienne de votre établissement.</p>
        </div>

        <!-- Premiers pas (détaillé avec étapes) -->
        <section class="doc" id="start">
            <h2><span class="sico"><i class="fas fa-flag-checkered"></i></span> Premiers pas</h2>
            <p>Après validation de votre essai gratuit ou de votre paiement :</p>
            <ol class="steps">
                <li><b>Recevez vos identifiants</b> par email (vérifiez le dossier spam et marquez le message « non spam »).</li>
                <li><b>Connectez-vous</b> sur la page de connexion avec votre email et votre mot de passe.</li>
                <li><b>Personnalisez votre site</b> via l'assistant de bienvenue (nom, logo, couleurs).</li>
                <li><b>Ajoutez vos chambres</b> et commencez à enregistrer vos réservations.</li>
            </ol>
            <div class="tip"><i class="fas fa-lightbulb"></i><div>Changez votre mot de passe après la première connexion depuis <b>Profil → Changer le mot de passe</b>.</div></div>
        </section>

        @foreach (array_slice($sections, 1) as $s)
            <section class="doc" id="{{ $s[0] }}">
                <h2><span class="sico"><i class="fas {{ $s[1] }}"></i></span> {{ $s[2] }}</h2>
                <p><strong style="color:#fff">{{ $s[3] }}.</strong> {{ $s[4] }}</p>
            </section>
        @endforeach

        <!-- Support -->
        <section class="doc" id="support">
            <h2><span class="sico"><i class="fas fa-headset"></i></span> Support & aide</h2>
            <p>Une question ? Un blocage ? Notre équipe vous accompagne :</p>
            <div class="note"><i class="fab fa-whatsapp" style="color:#25d366"></i><div>Support <b>WhatsApp 7j/7</b> — réponse rapide pour vous débloquer.</div></div>
            <div class="cta-final">
                <h3 style="margin:0 0 8px;">Prêt à gérer votre hôtel sereinement ?</h3>
                <p style="color:var(--muted);margin:0 0 18px;">Démarrez votre essai gratuit de {{ config('plans.trial_days', 14) }} jours, sans carte.</p>
                <a href="{{ route('hotel.register') }}" class="btn btn-glow"><i class="fas fa-rocket me-1"></i> Créer mon établissement</a>
            </div>
        </section>
    </main>
</div>

<script>
    // Surlignage de la section active dans le sommaire
    const links = [...document.querySelectorAll('.toc-link')];
    const map = new Map(links.map(l => [l.getAttribute('href').slice(1), l]));
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                links.forEach(l => l.classList.remove('active'));
                const a = map.get(e.target.id);
                if (a) a.classList.add('active');
            }
        });
    }, { rootMargin: '-40% 0px -55% 0px' });
    document.querySelectorAll('section.doc').forEach(s => obs.observe(s));
</script>
</body>
</html>
