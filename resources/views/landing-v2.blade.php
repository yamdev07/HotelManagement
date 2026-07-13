<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'checkinHub') }} : La gestion hôtelière réinventée</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --bg: #070b16; --bg2: #0c1224; --card: rgba(255,255,255,.04);
            --border: rgba(255,255,255,.09); --txt: #e8ecf6; --muted: #9aa6c2;
            --brand: #7c83ff; --brand2: #b06bff; --accent: #29e0c8;
        }
        * { font-family: 'Inter', system-ui, sans-serif; }
        body { background: var(--bg); color: var(--txt); overflow-x: hidden; }
        h1,h2,h3,h4,.display-font { font-family: 'Space Grotesk', sans-serif; letter-spacing: -.5px; }

        /* Fond cosmique animé */
        .cosmos { position: fixed; inset: 0; z-index: -2; background:
            radial-gradient(900px 500px at 80% -5%, rgba(124,131,255,.28), transparent 60%),
            radial-gradient(800px 500px at 10% 10%, rgba(176,107,255,.20), transparent 55%),
            radial-gradient(700px 600px at 50% 110%, rgba(41,224,200,.14), transparent 60%),
            linear-gradient(180deg, var(--bg) 0%, var(--bg2) 100%); }
        .stars { position: fixed; inset: 0; z-index: -1; opacity:.5;
            background-image: radial-gradient(1px 1px at 20% 30%, #fff, transparent), radial-gradient(1px 1px at 60% 70%, #fff, transparent),
                radial-gradient(1px 1px at 80% 20%, #cbd5ff, transparent), radial-gradient(1px 1px at 40% 80%, #fff, transparent),
                radial-gradient(1px 1px at 90% 60%, #fff, transparent), radial-gradient(1px 1px at 15% 65%, #cbd5ff, transparent);
            background-size: 100% 100%; animation: drift 60s linear infinite; }
        @keyframes drift { from{background-position:0 0;} to{background-position:100px 200px;} }

        .navbar { backdrop-filter: blur(14px); background: rgba(7,11,22,.55); border-bottom: 1px solid var(--border); }
        .brand-logo { font-family:'Space Grotesk'; font-weight:700; font-size:1.4rem; color:#fff; }
        .brand-logo span { background: linear-gradient(90deg,var(--brand),var(--brand2)); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }
        .nav-link { color: var(--muted) !important; font-weight:500; }
        .nav-link:hover { color:#fff !important; }

        .btn-glow { background: linear-gradient(90deg,var(--brand),var(--brand2)); color:#fff; border:none; font-weight:600;
            border-radius: 12px; padding:.7rem 1.4rem; box-shadow: 0 12px 34px -10px rgba(124,131,255,.7); transition:.25s; }
        .btn-glow:hover { color:#fff; transform: translateY(-2px); box-shadow: 0 18px 44px -10px rgba(176,107,255,.8); }
        .btn-ghost { border:1px solid var(--border); color:#fff; border-radius:12px; padding:.7rem 1.3rem; font-weight:600; background:transparent; transition:.25s; }
        .btn-ghost:hover { background: rgba(255,255,255,.06); color:#fff; border-color: rgba(255,255,255,.25); }

        .chip { display:inline-flex; align-items:center; gap:.5rem; padding:.4rem .9rem; border:1px solid var(--border);
            border-radius:999px; background: var(--card); font-size:.85rem; color: var(--muted); }
        .grad-text { background: linear-gradient(100deg,var(--brand) 10%,var(--brand2) 55%, var(--accent) 110%);
            -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }

        .glass { background: var(--card); border:1px solid var(--border); border-radius: 20px; backdrop-filter: blur(8px);
            transition: transform .3s, border-color .3s, box-shadow .3s; }
        .glass:hover { transform: translateY(-5px); border-color: rgba(124,131,255,.5); box-shadow: 0 24px 60px -30px rgba(124,131,255,.6); }
        .ico { width:50px;height:50px;border-radius:14px; display:grid;place-items:center; font-size:1.3rem; color:#fff;
            background: linear-gradient(135deg, rgba(124,131,255,.35), rgba(176,107,255,.25)); border:1px solid var(--border); }

        .section { padding: 6rem 0; }
        .text-muted2 { color: var(--muted) !important; }
        #globe-hero { width:100%; height:520px; }
        .marquee { overflow:hidden; border-block:1px solid var(--border); background: rgba(255,255,255,.02); }
        .marquee .track { display:inline-flex; gap:3rem; white-space:nowrap; padding:1rem 0; animation: scroll 26s linear infinite; }
        .marquee .track span { color: var(--muted); font-weight:600; font-family:'Space Grotesk'; }
        @keyframes scroll { from{transform:translateX(0);} to{transform:translateX(-50%);} }

        .price-card { background: var(--card); border:1px solid var(--border); border-radius:20px; transition:.3s; }
        .price-card:hover { transform: translateY(-6px); border-color: rgba(124,131,255,.5); }
        .price-card.pop { border:1px solid transparent; background:
            linear-gradient(var(--bg2),var(--bg2)) padding-box, linear-gradient(135deg,var(--brand),var(--brand2)) border-box; }
        .price-amount { font-family:'Space Grotesk'; font-weight:700; font-size:2.4rem; }
        .form-select, .form-select:focus { background:var(--bg2); color:#fff; border:1px solid var(--border); }
        .step-dot { width:44px;height:44px;border-radius:50%; display:grid;place-items:center; font-family:'Space Grotesk'; font-weight:700;
            background: linear-gradient(135deg,var(--brand),var(--brand2)); color:#fff; }
        footer { border-top:1px solid var(--border); }
        .accordion-button { background: transparent !important; color:#fff !important; padding:1.1rem 1.25rem; }
        .accordion-button:not(.collapsed) { color:#fff !important; box-shadow:none; }
        .accordion-button:focus { box-shadow:none; border-color: transparent; }
        .accordion-button::after { filter: invert(1) brightness(2); }
        .accordion-body { padding:0 1.25rem 1.2rem; }
        .footer-preview { position:fixed; bottom:14px; left:50%; transform:translateX(-50%); z-index:1000;
            background: rgba(12,18,36,.9); border:1px solid var(--border); border-radius:999px; padding:.5rem 1rem; font-size:.85rem; backdrop-filter:blur(8px); }
        .footer-preview a { color: var(--brand); }
    </style>
</head>
<body>
<div class="cosmos"></div>
<div class="stars"></div>

<!-- NAV -->
<nav class="navbar navbar-expand-lg fixed-top py-3">
    <div class="container">
        <a class="navbar-brand brand-logo" href="#"><i class="fas fa-location-dot me-1" style="color:var(--brand)"></i>check<span>inHub</span></a>
        <button class="navbar-toggler border-0 text-white" data-bs-toggle="collapse" data-bs-target="#nv"><i class="fas fa-bars"></i></button>
        <div class="collapse navbar-collapse" id="nv">
            <ul class="navbar-nav mx-auto gap-lg-3">
                <li class="nav-item"><a class="nav-link" href="#features">Fonctionnalités</a></li>
                <li class="nav-item"><a class="nav-link" href="#how">Comment ça marche</a></li>
                <li class="nav-item"><a class="nav-link" href="#temoignages">Avis</a></li>
                <li class="nav-item"><a class="nav-link" href="#pricing">Tarifs</a></li>
                <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
            </ul>
            <div class="d-flex gap-2">
                <a href="{{ route('login.index') }}" class="btn-ghost">Connexion</a>
                <a href="{{ route('hotel.register') }}" class="btn-glow">Essai gratuit</a>
            </div>
        </div>
    </div>
</nav>

<!-- HERO avec globe en vedette -->
<header class="section" style="padding-top:9rem;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-up">
                <span class="chip mb-3"><span style="width:8px;height:8px;border-radius:50%;background:var(--accent)"></span> Nouveau · Essai {{ config('plans.trial_days', 14) }} jours sans carte</span>
                <h1 class="display-4 fw-bold mb-3">La gestion hôtelière,<br><span class="grad-text">réinventée pour l'Afrique.</span></h1>
                <p class="fs-5 text-muted2 mb-4" style="max-width:520px;">
                    Réservations, check-in, caisse, housekeeping et vitrine web réunis dans une seule plateforme,
                    avec des tarifs adaptés au coût de la vie de <strong class="text-white">{{ count(config('plans.countries')) }} pays</strong>.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="{{ route('hotel.register') }}" class="btn-glow btn-lg"><i class="fas fa-rocket me-2"></i>Démarrer gratuitement</a>
                    <a href="#pricing" class="btn-ghost btn-lg"><i class="fas fa-tag me-2"></i>Voir les tarifs</a>
                </div>
                <div class="d-flex gap-4 text-muted2 small">
                    <span><i class="fas fa-check text-white me-1"></i> Sans engagement</span>
                    <span><i class="fas fa-check text-white me-1"></i> Prêt en 5 min</span>
                    <span><i class="fas fa-check text-white me-1"></i> Support inclus</span>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="150">
                <div id="globe-hero"></div>
            </div>
        </div>
    </div>
</header>

<!-- MARQUEE pays -->
<div class="marquee">
    <div class="track">
        @foreach (array_merge(array_values(config('plans.countries')), array_values(config('plans.countries'))) as $c)
            <span><i class="fas fa-location-dot me-2" style="color:var(--brand)"></i>{{ $c['name'] }}</span>
        @endforeach
    </div>
</div>

<!-- STATS (chiffres animés) -->
<section class="py-5">
    <div class="container">
        <div class="row g-4 text-center">
            @php $stats = [
                ['target'=>count(config('plans.countries')),'suffix'=>'','label'=>'pays desservis','icon'=>'fa-earth-africa'],
                ['target'=>6,'suffix'=>'','label'=>'modules tout-en-un','icon'=>'fa-layer-group'],
                ['target'=>5,'suffix'=>' min','label'=>'pour être opérationnel','icon'=>'fa-bolt'],
                ['target'=>config('plans.trial_days',14),'suffix'=>' j','label'=>"d'essai gratuit",'icon'=>'fa-gift'],
            ]; @endphp
            @foreach ($stats as $i => $s)
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $i*100 }}">
                    <div class="glass p-4 h-100">
                        <div class="ico mx-auto mb-3"><i class="fas {{ $s['icon'] }}"></i></div>
                        <div class="display-5 fw-bold grad-text"><span class="counter" data-target="{{ $s['target'] }}">0</span>{{ $s['suffix'] }}</div>
                        <div class="text-muted2">{{ $s['label'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- FEATURES (bento) -->
<section class="section" id="features">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="chip mb-2">Fonctionnalités</span>
            <h2 class="fw-bold">Tout votre hôtel, <span class="grad-text">au même endroit</span></h2>
        </div>
        <div class="row g-4">
            @php $feats = [
                ['fa-calendar-check','Réservations & check-in','Planning en temps réel, arrivées/départs, check-in direct en un clic.'],
                ['fa-cash-register','Caisse & paiements','Encaissements, ouverture/fermeture de caisse, suivi des transactions.'],
                ['fa-broom','Housekeeping','Statuts des chambres, tâches du personnel, suivi du ménage.'],
                ['fa-utensils','Restaurant','Commandes, service en chambre, gestion des points de vente.'],
                ['fa-chart-line','Rapports','Occupation, revenus et performance sur des tableaux de bord clairs.'],
                ['fa-globe','Vitrine web','Un mini-site à vos couleurs pour vos réservations en ligne.'],
            ]; @endphp
            @foreach ($feats as $i => $f)
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($i%3)*100 }}">
                    <div class="glass p-4 h-100">
                        <div class="ico mb-3"><i class="fas {{ $f[0] }}"></i></div>
                        <h5 class="fw-bold">{{ $f[1] }}</h5>
                        <p class="text-muted2 mb-0">{{ $f[2] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- HOW -->
<section class="section" id="how">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="chip mb-2">Simple</span>
            <h2 class="fw-bold">Opérationnel en <span class="grad-text">3 étapes</span></h2>
        </div>
        <div class="row g-4">
            @php $steps = [['Créez votre compte','Inscription en 2 minutes, essai gratuit immédiat, sans carte.'],
                ['Personnalisez','Vos couleurs, votre logo, vos chambres et votre vitrine web.'],
                ['Accueillez vos clients','Réservations, check-in et caisse dès le premier jour.']]; @endphp
            @foreach ($steps as $i => $s)
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $i*120 }}">
                    <div class="glass p-4 h-100">
                        <div class="step-dot mb-3">{{ $i+1 }}</div>
                        <h5 class="fw-bold">{{ $s[0] }}</h5>
                        <p class="text-muted2 mb-0">{{ $s[1] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- TÉMOIGNAGES -->
<section class="section" id="temoignages">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="chip mb-2">Témoignages</span>
            <h2 class="fw-bold">Ils gèrent leur hôtel avec <span class="grad-text">checkinHub</span></h2>
        </div>
        <div class="row g-4">
            @php $temoins = [
                ['A','Aïcha D.','Directrice, Résidence Les Palmiers','checkinHub a remplacé nos cahiers et nos fichiers Excel. Le check-in prend deux minutes et la caisse est enfin claire.'],
                ['K','Koffi M.','Gérant, Hôtel Baobab','Mise en route en une après-midi. Mes réceptionnistes ont adopté l’outil tout de suite, sans formation compliquée.'],
                ['F','Fatou S.','Propriétaire, Villa Océane','Le prix adapté à mon pays a fait la différence. Et la vitrine web m’apporte des réservations directes.'],
            ]; @endphp
            @foreach ($temoins as $i => $t)
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $i*120 }}">
                    <div class="glass p-4 h-100">
                        <div class="mb-2" style="color:var(--accent)">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="mb-4">“{{ $t[3] }}”</p>
                        <div class="d-flex align-items-center gap-3 mt-auto">
                            <div class="ico" style="width:44px;height:44px;font-family:'Space Grotesk';font-weight:700">{{ $t[0] }}</div>
                            <div>
                                <div class="fw-semibold">{{ $t[1] }}</div>
                                <div class="text-muted2 small">{{ $t[2] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- PRICING -->
<section class="section" id="pricing">
    <div class="container">
        <div class="text-center mb-4" data-aos="fade-up">
            <span class="chip mb-2">Tarifs</span>
            <h2 class="fw-bold">Des prix <span class="grad-text">adaptés à votre pays</span></h2>
            <p class="text-muted2">Coût de la vie & devise locale pris en compte.</p>
            <div class="d-inline-flex align-items-center gap-2 mt-2">
                <i class="fas fa-earth-africa" style="color:var(--brand)"></i>
                <select id="pricing-country" class="form-select" style="width:auto;">
                    @foreach (config('plans.countries') as $code => $c)
                        <option value="{{ $code }}" {{ $code === config('plans.default_country') ? 'selected' : '' }}>{{ $c['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row g-4 justify-content-center">
            @foreach (config('plans.tiers') as $key => $tier)
                @php $pop = !empty($tier['popular']); @endphp
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index*120 }}">
                    <div class="price-card {{ $pop ? 'pop' : '' }} p-4 h-100" data-base="{{ $tier['price'] }}">
                        @if ($pop)<span class="chip mb-2" style="border-color:var(--brand);color:#fff"><i class="fas fa-star" style="color:var(--accent)"></i> Populaire</span>@endif
                        <h4 class="fw-bold">{{ $tier['name'] }}</h4>
                        <p class="text-muted2 small">{{ $tier['tagline'] }}</p>
                        <div class="my-2"><span class="price-amount pr-amount">{{ number_format($tier['price'],0,',',' ') }}</span>
                            <span class="text-muted2"><span class="pr-cur">XOF</span> / mois</span></div>
                        <hr style="border-color:var(--border)">
                        <ul class="list-unstyled mb-4">
                            @foreach ($tier['features'] as $item)
                                <li class="mb-2 text-muted2"><i class="fas fa-check me-2" style="color:var(--accent)"></i>{{ $item }}</li>
                            @endforeach
                        </ul>
                        <a href="{{ route('hotel.register', ['plan'=>$key]) }}" class="{{ $pop ? 'btn-glow' : 'btn-ghost' }} w-100 d-block text-center">Choisir {{ $tier['name'] }}</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="section" id="faq">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="chip mb-2">FAQ</span>
            <h2 class="fw-bold">Les questions <span class="grad-text">fréquentes</span></h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                @php $faqs = [
                    ["L'essai gratuit nécessite-t-il une carte bancaire ?","Non. Vous démarrez votre essai de ".config('plans.trial_days',14)." jours immédiatement, sans aucune carte. Vous ne payez que si vous décidez de continuer."],
                    ["Comment sont fixés les prix ?","Le tarif dépend de votre pays : nous ajustons les prix au coût de la vie local et affichons votre devise. Sélectionnez votre pays dans la section Tarifs pour voir vos prix."],
                    ["Mes données sont-elles isolées des autres hôtels ?","Oui. Chaque établissement dispose de son espace cloisonné : vos réservations, clients et transactions ne sont jamais mélangés avec ceux d'un autre hôtel."],
                    ["Puis-je changer de formule plus tard ?","Bien sûr. Vous pouvez passer à une formule supérieure ou inférieure à tout moment depuis votre espace, selon le nombre de chambres."],
                    ["Comment se passe le paiement de l'abonnement ?","Le paiement se fait en ligne (Mobile Money & carte). Votre accès est prolongé automatiquement dès la confirmation du paiement."],
                ]; @endphp
                <div class="accordion accordion-flush" id="faqAcc">
                    @foreach ($faqs as $i => $q)
                        <div class="glass mb-3" style="overflow:hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-transparent text-white fw-semibold {{ $i===0?'':'' }}" style="box-shadow:none"
                                    type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}">
                                    {{ $q[0] }}
                                </button>
                            </h2>
                            <div id="faq{{ $i }}" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                                <div class="accordion-body text-muted2">{{ $q[1] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section">
    <div class="container">
        <div class="glass p-5 text-center" data-aos="zoom-in" style="background:linear-gradient(135deg, rgba(124,131,255,.18), rgba(176,107,255,.12));">
            <h2 class="fw-bold mb-2">Prêt à moderniser votre hôtel ?</h2>
            <p class="text-muted2 mb-4">Démarrez votre essai gratuit de {{ config('plans.trial_days',14) }} jours, aucune carte requise.</p>
            <a href="{{ route('hotel.register') }}" class="btn-glow btn-lg"><i class="fas fa-rocket me-2"></i>Créer mon établissement</a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="py-5">
    <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">
        <span class="brand-logo">check<span>inHub</span></span>
        <span class="text-muted2 small">© {{ now()->year }} checkinHub. La gestion hôtelière réinventée.</span>
        <a href="{{ route('login.index') }}" class="btn-ghost">Connexion</a>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://unpkg.com/globe.gl"></script>
<script>
    try { AOS.init({ duration: 700, once: true, offset: 60 }); } catch(e){}

    // Compteurs animés (count-up au scroll)
    (function () {
        const counters = document.querySelectorAll('.counter');
        if (!counters.length) return;
        const run = (el) => {
            const target = +el.dataset.target, dur = 1300, t0 = performance.now();
            const tick = (now) => {
                const p = Math.min((now - t0) / dur, 1);
                el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3)));
                if (p < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        };
        if ('IntersectionObserver' in window) {
            const obs = new IntersectionObserver((es) => es.forEach(e => { if (e.isIntersecting) { run(e.target); obs.unobserve(e.target); } }), { threshold: .6 });
            counters.forEach(c => obs.observe(c));
        } else { counters.forEach(c => c.textContent = c.dataset.target); }
    })();

    // Prix par pays
    (function () {
        const countries = @json(config('plans.countries'));
        const sel = document.getElementById('pricing-country');
        if (!sel) return;
        const fmt = n => n.toLocaleString('fr-FR');
        const upd = () => {
            const c = countries[sel.value]; if (!c) return;
            document.querySelectorAll('.price-card[data-base]').forEach(card => {
                const price = Math.round((+card.dataset.base) * c.coef / c.round) * c.round;
                const a = card.querySelector('.pr-amount'), u = card.querySelector('.pr-cur');
                if (a) a.textContent = fmt(price); if (u) u.textContent = c.currency;
            });
        };
        sel.addEventListener('change', upd); upd();
    })();
</script>
<!-- Globe hero (isolé) -->
<script>
(function () {
    const servedData = @json(config('plans.countries'));
    const coords = { BJ:[9.3,2.3],TG:[8.6,0.8],CI:[7.5,-5.5],SN:[14.5,-14.5],BF:[12.2,-1.5],ML:[17.0,-4.0],NE:[17.6,8.0],CM:[5.7,12.5],GA:[-0.8,11.6],NG:[9.1,8.7],GH:[7.9,-1.0],FR:[46.6,2.2] };
    function build() {
        const el = document.getElementById('globe-hero'); if (!el) return;
        if (typeof Globe === 'undefined') { el.style.display='none'; return; }
        try {
            const pts = Object.keys(servedData).filter(c=>coords[c]).map(c=>({name:servedData[c].name,lat:coords[c][0],lng:coords[c][1]}));
            const TEX='https://unpkg.com/three-globe/example/img/';
            const g = Globe()(el)
                .backgroundColor('rgba(0,0,0,0)')
                .globeImageUrl(TEX+'earth-blue-marble.jpg').bumpImageUrl(TEX+'earth-topology.png')
                .showAtmosphere(true).atmosphereColor('#7c83ff').atmosphereAltitude(0.25)
                .ringsData(pts).ringColor(()=>t=>`rgba(41,224,200,${Math.sqrt(1-t)})`).ringMaxRadius(4).ringPropagationSpeed(2.2).ringRepeatPeriod(900)
                .pointsData(pts).pointColor(()=>'#c7d2fe').pointAltitude(0.02).pointRadius(0.35)
                .labelsData(pts).labelText('name').labelSize(1.0).labelDotRadius(0.35).labelColor(()=>'#ffffff').labelResolution(2);
            const resize=()=>g.width(el.clientWidth).height(el.clientHeight); resize(); window.addEventListener('resize',resize);
            g.pointOfView({lat:8,lng:4,altitude:1.7},0);
            const c=g.controls(); c.autoRotate=true; c.autoRotateSpeed=0.8; c.enableZoom=true; c.minDistance=160; c.maxDistance=450;
        } catch(e){ console.error('globe',e); el.style.display='none'; }
    }
    document.readyState==='loading' ? document.addEventListener('DOMContentLoaded',build) : build();
})();
</script>
</body>
</html>
