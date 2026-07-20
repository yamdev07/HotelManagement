<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Guide d'utilisation · {{ config('app.name', 'checkinHub') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root{ /* Sombre (par défaut) */
            --bg:#070b16;--bg2:#0b1122;--panel:rgba(255,255,255,.035);--border:rgba(255,255,255,.09);
            --txt:#e8ecf6;--muted:#93a0bd;--head:#ffffff;--nav-bg:rgba(7,11,22,.72);--side-bg:rgba(9,13,26,.55);
            --brand:#7c83ff;--brand2:#b06bff;--accent:#29e0c8;--glow:.16;}
        :root[data-theme="light"]{ /* Clair */
            --bg:#f6f7fb;--bg2:#eef1f8;--panel:rgba(15,23,42,.035);--border:rgba(15,23,42,.11);
            --txt:#3b475c;--muted:#64748b;--head:#0f172a;--nav-bg:rgba(255,255,255,.85);--side-bg:rgba(255,255,255,.72);
            --brand:#6366f1;--brand2:#8b5cf6;--accent:#0ea5e9;--glow:.10;}
        *{box-sizing:border-box;font-family:'Inter',system-ui,sans-serif;}
        body{margin:0;background:var(--bg);color:var(--txt);line-height:1.65;}
        h1,h2,h3,.dfont{font-family:'Space Grotesk',sans-serif;letter-spacing:-.4px;color:var(--head);}
        a{color:var(--brand);text-decoration:none;}
        .cosmos{position:fixed;inset:0;z-index:-1;background:
            radial-gradient(800px 400px at 80% -5%,rgba(124,131,255,.16),transparent 60%),
            radial-gradient(700px 400px at 5% 5%,rgba(176,107,255,.12),transparent 55%),
            linear-gradient(180deg,var(--bg),var(--bg2));}
        .nav{position:sticky;top:0;z-index:30;display:flex;align-items:center;gap:16px;padding:14px 26px;
            border-bottom:1px solid var(--border);background:var(--nav-bg);backdrop-filter:blur(14px);}
        .logo{font-family:'Space Grotesk';font-weight:700;font-size:1.2rem;color:var(--head);display:flex;align-items:center;gap:8px;}
        .theme-btn{width:40px;height:40px;border-radius:11px;border:1px solid var(--border);background:var(--panel);
            color:var(--txt);cursor:pointer;display:grid;place-items:center;font-size:.95rem;}
        .theme-btn:hover{color:var(--head);}
        .logo i{color:var(--brand);}
        .logo span{background:linear-gradient(90deg,var(--brand),var(--brand2));-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;}
        .btn{border-radius:11px;padding:9px 16px;font-weight:600;font-size:.85rem;}
        .btn-glow{background:linear-gradient(90deg,var(--brand),var(--brand2));color:#fff;box-shadow:0 12px 30px -12px rgba(124,131,255,.6);}
        .btn-ghost{border:1px solid var(--border);color:var(--txt);}
        /* Sidebar doc (fixe, à gauche) */
        .gside{position:fixed;top:59px;left:0;bottom:0;width:300px;border-right:1px solid var(--border);
            background:var(--side-bg);backdrop-filter:blur(12px);padding:22px 18px;overflow-y:auto;z-index:20;transition:transform .25s;
            scrollbar-width:thin;scrollbar-color:var(--border) transparent;}
        /* Scrollbar fine et discrète, cohérente avec l'app (issue #167) */
        .gside::-webkit-scrollbar{width:6px;}
        .gside::-webkit-scrollbar-track{background:transparent;}
        .gside::-webkit-scrollbar-thumb{background:var(--border);border-radius:99px;}
        .gside::-webkit-scrollbar-thumb:hover{background:var(--muted);}
        body{scrollbar-width:thin;scrollbar-color:var(--border) transparent;}
        body::-webkit-scrollbar{width:9px;}
        body::-webkit-scrollbar-track{background:transparent;}
        body::-webkit-scrollbar-thumb{background:var(--border);border-radius:99px;}
        .t-search{display:flex;align-items:center;gap:10px;background:var(--panel);border:1px solid var(--border);
            border-radius:12px;padding:11px 14px;margin-bottom:20px;color:var(--muted);}
        .t-search i{font-size:.85rem;}
        .t-search input{background:none;border:none;outline:none;color:var(--txt);font-size:.9rem;width:100%;}
        .t-search input::placeholder{color:var(--muted);}
        .t-title{font-size:.7rem;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin:0 0 12px;}
        .gside a.toc-link{display:flex;align-items:center;gap:10px;color:var(--muted);font-size:.92rem;padding:10px 14px;
            border-radius:10px;margin-bottom:3px;border-left:2px solid transparent;}
        .gside a.toc-link i{font-size:.8rem;width:16px;text-align:center;}
        .gside a.toc-link:hover{color:var(--head);background:var(--panel);}
        .gside a.toc-link.active{color:var(--head);border-left-color:var(--brand);background:var(--panel);}
        .t-empty{color:var(--muted);font-size:.82rem;padding:10px 14px;display:none;}
        /* content · centré dans l'espace à droite de la sidebar */
        .gcontent{margin-left:300px;padding:40px 40px 90px;display:flex;flex-direction:column;align-items:center;}
        .gcontent > *{width:100%;max-width:820px;}
        .gburger{display:none;width:40px;height:40px;border-radius:11px;place-items:center;color:var(--head);background:var(--panel);border:1px solid var(--border);}
        .hero{margin-bottom:40px;}
        .hero .chip{display:inline-flex;align-items:center;gap:7px;padding:.4rem .9rem;border:1px solid var(--border);
            border-radius:999px;background:var(--panel);font-size:.78rem;color:var(--muted);margin-bottom:14px;}
        .hero h1{font-size:clamp(1.9rem,3.6vw,2.6rem);margin:0 0 10px;}
        .hero p{color:var(--muted);font-size:1.05rem;max-width:640px;}
        section.doc{scroll-margin-top:90px;margin-bottom:46px;padding-bottom:8px;}
        section.doc h2{font-size:1.35rem;display:flex;align-items:center;gap:12px;margin:0 0 8px;}
        .sico{width:40px;height:40px;border-radius:11px;display:grid;place-items:center;font-size:1.05rem;color:var(--head);
            background:linear-gradient(135deg,rgba(124,131,255,.4),rgba(176,107,255,.25));border:1px solid var(--border);flex-shrink:0;}
        section.doc p{color:var(--muted);}
        .steps{list-style:none;padding:0;margin:16px 0 0;counter-reset:s;}
        .steps li{position:relative;padding:0 0 16px 44px;counter-increment:s;}
        .steps li::before{content:counter(s);position:absolute;left:0;top:-2px;width:28px;height:28px;border-radius:50%;
            background:linear-gradient(135deg,var(--brand),var(--brand2));color:#fff;font-family:'Space Grotesk';font-weight:700;
            font-size:.8rem;display:grid;place-items:center;}
        .steps li:not(:last-child)::after{content:'';position:absolute;left:13px;top:28px;bottom:2px;width:2px;background:var(--border);}
        .steps li b{color:var(--head);}
        .tip{display:flex;gap:12px;background:rgba(41,224,200,.08);border:1px solid rgba(41,224,200,.25);
            border-radius:12px;padding:12px 16px;margin-top:16px;font-size:.9rem;color:var(--txt);}
        .tip i{color:var(--accent);margin-top:3px;}
        .note{display:flex;gap:12px;background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.25);
            border-radius:12px;padding:12px 16px;margin-top:16px;font-size:.9rem;color:var(--txt);}
        .note i{color:#fbbf24;margin-top:3px;}
        .cta-final{background:linear-gradient(135deg,rgba(124,131,255,.18),rgba(176,107,255,.12));
            border:1px solid var(--border);border-radius:18px;padding:28px;text-align:center;margin-top:20px;}
        @media (max-width:900px){
            .gside{transform:translateX(-100%);width:280px;}
            .gside.open{transform:none;}
            .gcontent{margin-left:0;padding:26px 20px 80px;}
            .gburger{display:grid;}
        }
    </style>
    <script>
        // Applique le thème avant le rendu (évite le flash)
        (function(){
            try{
                var t = localStorage.getItem('guide-theme') || (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
                document.documentElement.setAttribute('data-theme', t);
            }catch(e){}
        })();
    </script>
</head>
<body>
<div class="cosmos"></div>

<nav class="nav">
    <div class="gburger" onclick="document.querySelector('.gside').classList.toggle('open')"><i class="fas fa-bars"></i></div>
    <a href="{{ route('landing') }}" class="logo"><i class="fas fa-location-dot"></i> check<span>inHub</span></a>
    <div style="flex:1"></div>
    <button class="theme-btn" id="themeToggle" type="button" aria-label="Changer de thème"><i class="fas fa-moon"></i></button>
    <a href="{{ route('landing') }}" class="btn btn-ghost"><i class="fas fa-arrow-left me-1"></i> Site</a>
    @auth
        {{-- Déjà connecté : on ne propose pas de se reconnecter à un autre compte (issue #183) --}}
        <a href="{{ url('/home') }}" class="btn btn-glow"><i class="fas fa-gauge-high me-1"></i> Mon espace</a>
    @else
        <a href="{{ route('login.index') }}" class="btn btn-ghost">Connexion</a>
        <a href="{{ route('hotel.register') }}" class="btn btn-glow">Essai gratuit</a>
    @endauth
</nav>

@php
    $sections = [
        ['start','fa-flag-checkered','Premiers pas','Après votre inscription',
            "Dès la validation de votre essai (ou de votre paiement), vous recevez vos identifiants par email · pensez à vérifier vos spams. Connectez-vous sur la page /login avec l'email et le mot de passe reçus."],
        ['process','fa-diagram-project','Le parcours d\'un séjour','Le processus métier au quotidien',
            "De la réservation au départ du client, puis à la remise en état de la chambre : voici le déroulé complet géré par l'application."],
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

    // Contenu pas-à-pas détaillé, par section (ajouté progressivement).
    $details = [];

    $details['rooms'] = <<<'HTML'
        <p>L'organisation se fait en deux temps : d'abord les <b>types de chambre</b>, ensuite les <b>chambres</b> elles-mêmes.</p>
        <ol class="steps">
            <li><b>Créez vos types de chambre</b> · Menu <b>Chambres → Types</b> → « Nouveau type ». Donnez un nom (Standard, Suite, Deluxe…), une <b>capacité</b> (nombre de personnes) et un <b>prix de base</b>. Répétez pour chaque catégorie.</li>
            <li><b>Ajoutez vos chambres</b> · Menu <b>Chambres → Nouvelle chambre</b>. Indiquez le <b>numéro</b>, choisissez le <b>type</b>, la capacité et le prix. La chambre est créée avec le statut « disponible ».</li>
            <li><b>Suivez les statuts</b> · chaque chambre affiche son état (disponible, réservée, occupée, à nettoyer, en nettoyage, maintenance). Ces statuts évoluent <b>automatiquement</b> avec les réservations et le housekeeping.</li>
        </ol>
        <div class="tip"><i class="fas fa-lightbulb"></i><div>Le <b>numéro de chambre est unique par établissement</b> : deux hôtels différents peuvent chacun avoir une chambre « 101 » sans conflit.</div></div>
    HTML;

    $details['bookings'] = <<<'HTML'
        <p>C'est le cœur de l'activité : enregistrer un séjour, accueillir puis faire partir le client.</p>
        <ol class="steps">
            <li><b>Nouvelle réservation</b> · cliquez sur <b>Nouvelle réservation</b>. Sélectionnez ou créez le <b>client</b>, choisissez la <b>chambre</b> et les <b>dates</b> (arrivée / départ). La chambre passe en « réservée ».</li>
            <li><b>Le client</b> · recherchez une fiche existante, ou créez-la (nom, téléphone, email facultatif). Un même client peut revenir sans créer de doublon.</li>
            <li><b>Check-in (arrivée)</b> · à l'arrivée, ouvrez la réservation et cliquez sur <b>Check-in</b>. La chambre devient « occupée » et le séjour est actif. Vous pouvez encaisser un acompte ou la totalité.</li>
            <li><b>Pendant le séjour</b> · ajoutez si besoin des <b>extras</b> (services, consommations) qui s'ajoutent à la note du client.</li>
            <li><b>Check-out (départ)</b> · au départ, cliquez sur <b>Check-out</b>. La note finale (chambre + extras) est calculée, puis encaissée en caisse. La chambre bascule en « à nettoyer ».</li>
        </ol>
        <div class="tip"><i class="fas fa-lightbulb"></i><div>Un client se présente <b>sans réservation</b> ? Utilisez le <b>check-in direct</b> : la réservation et l'arrivée se font en une seule étape.</div></div>
    HTML;

    $details['cashier'] = <<<'HTML'
        <p>La caisse suit tout l'argent encaissé pendant un service, avec ouverture et clôture pour le rapprochement.</p>
        <ol class="steps">
            <li><b>Ouvrir la caisse</b> · en début de service, Menu <b>Caisse → Ouvrir la caisse</b>. Saisissez le <b>fond de caisse</b> (montant de départ).</li>
            <li><b>Encaisser</b> · enregistrez chaque paiement (chambre, extras) en indiquant le <b>moyen</b> : espèces, Mobile Money, carte… Un paiement peut être <b>partiel</b> (acompte) puis complété plus tard.</li>
            <li><b>Suivre en temps réel</b> · la caisse affiche le <b>total encaissé</b> de la session au fur et à mesure.</li>
            <li><b>Fermer la caisse</b> · en fin de service, cliquez sur <b>Fermer la caisse</b> : comptez votre tiroir, l'application calcule le <b>rapprochement</b> et signale tout écart.</li>
        </ol>
        <div class="tip"><i class="fas fa-lightbulb"></i><div>Chaque encaissement est <b>tracé dans le journal d'activité</b> de votre établissement, avec l'auteur et l'heure.</div></div>
    HTML;

    $details['housekeeping'] = <<<'HTML'
        <p>Le module suit l'état de chaque chambre et organise le travail de l'équipe de ménage. <i>(Offres Pro & Business)</i></p>
        <ol class="steps">
            <li><b>Repérez les chambres à traiter</b> — Menu <b>Housekeeping</b> : après chaque départ, la chambre apparaît « à nettoyer ». Vous voyez d'un coup d'œil ce qu'il reste à faire.</li>
            <li><b>Démarrez le nettoyage</b> — quand un agent commence, marquez la chambre <b>« en nettoyage »</b>. L'équipe sait ainsi ce qui est en cours.</li>
            <li><b>Marquez « propre »</b> — une fois terminée, passez la chambre en <b>« propre / disponible »</b> : elle redevient immédiatement réservable.</li>
            <li><b>Signalez la maintenance</b> — si une chambre nécessite une réparation, mettez-la en <b>« maintenance »</b> pour la rendre indisponible le temps des travaux.</li>
        </ol>
        <div class="tip"><i class="fas fa-lightbulb"></i><div>Le <b>tableau de bord</b> affiche en temps réel le nombre de chambres à nettoyer, pour prioriser les arrivées du jour.</div></div>
    HTML;

    $details['restaurant'] = <<<'HTML'
        <p>Gérez votre carte, les commandes en salle et le service en chambre. <i>(Offres Pro & Business)</i></p>
        <ol class="steps">
            <li><b>Créez votre carte</b> — Menu <b>Restaurant → Menus</b> : ajoutez vos plats (nom, prix) et organisez-les par <b>catégories</b> (entrées, plats, boissons…).</li>
            <li><b>Prenez une commande</b> — créez une commande et associez-la à une <b>table</b> ou à une <b>chambre</b> (room service).</li>
            <li><b>Suivez le service</b> — la commande évolue par statuts (en attente, en préparation, servie), visibles par l'équipe.</li>
            <li><b>Facturez</b> — le montant s'<b>ajoute à la note du client</b> (room service, réglé au départ) ou s'<b>encaisse directement en caisse</b>.</li>
        </ol>
        <div class="tip"><i class="fas fa-lightbulb"></i><div>Une commande en <b>room service</b> se rattache au séjour en cours du client : tout est réglé en une fois au check-out.</div></div>
    HTML;

    $details['site'] = <<<'HTML'
        <p>Chaque établissement dispose d'un <b>mini-site public</b> à ses couleurs, pour présenter ses chambres et recevoir des réservations en ligne.</p>
        <ol class="steps">
            <li><b>Activez les sections</b> — depuis <b>Mon établissement</b>, activez les pages voulues (chambres, restaurant, services, contact).</li>
            <li><b>Personnalisez</b> — couleurs, logo, image de couverture, textes (à propos, services) et liens vers vos réseaux sociaux.</li>
            <li><b>Partagez le lien</b> — votre vitrine a une <b>adresse publique</b> ; partagez-la à vos clients (réseaux, WhatsApp, carte de visite).</li>
            <li><b>Recevez des réservations</b> — les visiteurs consultent vos chambres et réservent en ligne ; la réservation arrive directement dans votre espace.</li>
        </ol>
        <div class="tip"><i class="fas fa-lightbulb"></i><div>Le bouton <b>« Voir mon site »</b> ouvre la vitrine telle que la voient vos clients, pour vérifier le rendu.</div></div>
    HTML;

    $details['reports'] = <<<'HTML'
        <p>Pilotez la performance de votre établissement avec des chiffres clairs. <i>(Offres Pro & Business)</i></p>
        <ol class="steps">
            <li><b>Ouvrez les Rapports</b> — Menu <b>Rapports</b>.</li>
            <li><b>Choisissez la période</b> — jour, semaine ou mois, selon ce que vous voulez analyser.</li>
            <li><b>Analysez</b> — <b>taux d'occupation</b>, <b>revenus</b>, nombre de réservations et répartition des moyens de paiement.</li>
            <li><b>Décidez</b> — ajustez vos tarifs et votre organisation en fonction des tendances observées.</li>
        </ol>
        <div class="tip"><i class="fas fa-lightbulb"></i><div>Le <b>tableau de bord</b> donne déjà un aperçu quotidien (occupation, chiffre d'affaires, arrivées/départs) sans ouvrir les rapports.</div></div>
    HTML;
@endphp

<!-- SIDEBAR (gauche) -->
<aside class="gside">
    <div class="t-search">
        <i class="fas fa-magnifying-glass"></i>
        <input type="text" id="guideSearch" placeholder="Rechercher dans le guide…" autocomplete="off">
    </div>
    <div class="t-title">Sommaire</div>
    @foreach ($sections as $s)
        <a href="#{{ $s[0] }}" class="toc-link" data-text="{{ \Illuminate\Support\Str::lower($s[2].' '.$s[3].' '.$s[4]) }}">
            <i class="fas {{ $s[1] }}"></i> {{ $s[2] }}
        </a>
    @endforeach
    <a href="#support" class="toc-link" data-text="support aide contact whatsapp"><i class="fas fa-headset"></i> Support &amp; aide</a>
    <div class="t-empty" id="tocEmpty">Aucun résultat pour cette recherche.</div>
</aside>

<!-- CONTENT -->
<main class="gcontent">
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

        <!-- Processus métier (détaillé) -->
        <section class="doc" id="process">
            <h2><span class="sico"><i class="fas fa-diagram-project"></i></span> Le parcours d'un séjour</h2>
            <p>Voici comment l'application accompagne le cycle de vie complet d'un séjour, de la réservation jusqu'à la remise en état de la chambre.</p>
            <ol class="steps">
                <li><b>Réservation</b> · le client réserve, soit <b>en ligne</b> depuis votre site web (vitrine), soit <b>à la réception</b>. Vous enregistrez le client (fiche client) et choisissez la chambre et les dates. La chambre passe en <b>« réservée »</b>.</li>
                <li><b>Arrivée (check-in)</b> · à l'arrivée, vous validez le check-in : la chambre devient <b>« occupée »</b> et le séjour est actif. Vous pouvez encaisser un acompte ou l'intégralité.</li>
                <li><b>Pendant le séjour</b> · vous suivez les clients présents, ajoutez des consommations (restaurant, room service…) et des extras qui s'ajoutent à la note.</li>
                <li><b>Départ (check-out)</b> · au départ, vous clôturez le séjour : la note finale est calculée (chambre + extras), puis <b>encaissée en caisse</b>. La chambre bascule en <b>« à nettoyer »</b>.</li>
                <li><b>Housekeeping</b> · l'équipe de ménage voit les chambres à nettoyer, effectue le travail, et la chambre repasse <b>« propre / disponible »</b>, prête pour un nouveau client.</li>
                <li><b>Caisse & clôture</b> · en fin de service, vous <b>fermez la caisse</b> pour rapprocher les encaissements de la journée.</li>
                <li><b>Pilotage</b> · à tout moment, le tableau de bord et les rapports vous donnent l'occupation, le chiffre d'affaires et l'activité de votre établissement.</li>
            </ol>
            <div class="tip"><i class="fas fa-lightbulb"></i><div>Chaque action (check-in, encaissement, nettoyage…) est <b>tracée dans le journal d'activité</b>, propre à votre établissement.</div></div>
        </section>

        @foreach (array_slice($sections, 2) as $s)
            <section class="doc" id="{{ $s[0] }}">
                <h2><span class="sico"><i class="fas {{ $s[1] }}"></i></span> {{ $s[2] }}</h2>
                @isset($details[$s[0]])
                    {!! $details[$s[0]] !!}
                @else
                    <p><strong style="color:var(--head)">{{ $s[3] }}.</strong> {{ $s[4] }}</p>
                @endisset
            </section>
        @endforeach

        <!-- Support -->
        <section class="doc" id="support">
            <h2><span class="sico"><i class="fas fa-headset"></i></span> Support & aide</h2>
            <p>Une question ? Un blocage ? Notre équipe vous accompagne :</p>
            <div class="note"><i class="fab fa-whatsapp" style="color:#25d366"></i><div>Support <b>WhatsApp 7j/7</b> · réponse rapide pour vous débloquer.</div></div>
            <div class="cta-final">
                <h3 style="margin:0 0 8px;">Prêt à gérer votre hôtel sereinement ?</h3>
                <p style="color:var(--muted);margin:0 0 18px;">Démarrez votre essai gratuit de {{ config('plans.trial_days', 14) }} jours, sans carte.</p>
                <a href="{{ route('hotel.register') }}" class="btn btn-glow"><i class="fas fa-rocket me-1"></i> Créer mon établissement</a>
            </div>
        </section>
    </main>

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

    // Recherche : filtre le sommaire ET les sections
    const search = document.getElementById('guideSearch');
    const empty = document.getElementById('tocEmpty');
    if (search) {
        search.addEventListener('input', () => {
            const q = search.value.trim().toLowerCase();
            let visible = 0;
            links.forEach(l => {
                const hay = (l.dataset.text || l.textContent).toLowerCase();
                const match = !q || hay.includes(q);
                l.style.display = match ? '' : 'none';
                const sec = document.getElementById(l.getAttribute('href').slice(1));
                if (sec) sec.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            if (empty) empty.style.display = visible ? 'none' : 'block';
        });
    }

    // Bascule clair / sombre
    const themeBtn = document.getElementById('themeToggle');
    const setIcon = () => {
        const dark = document.documentElement.getAttribute('data-theme') !== 'light';
        themeBtn.querySelector('i').className = dark ? 'fas fa-moon' : 'fas fa-sun';
    };
    if (themeBtn) {
        setIcon();
        themeBtn.addEventListener('click', () => {
            const next = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', next);
            try { localStorage.setItem('guide-theme', next); } catch(e){}
            setIcon();
        });
    }
</script>
</body>
</html>
