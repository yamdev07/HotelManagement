<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'checkinHub') }} : {{ __('landing_v2.page_title') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        :root { /* Sombre (par défaut) */
            --bg: #070b16; --bg2: #0c1224; --card: rgba(255,255,255,.04);
            --border: rgba(255,255,255,.09); --txt: #e8ecf6; --muted: #9aa6c2; --head: #ffffff;
            --navbar-bg: rgba(7,11,22,.55); --hover: rgba(255,255,255,.06);
            --brand: #7c83ff; --brand2: #b06bff; --accent: #29e0c8;
        }
        :root[data-theme="light"] { /* Clair */
            --bg: #ffffff; --bg2: #f3f5fc; --card: rgba(15,23,42,.03);
            --border: rgba(15,23,42,.10); --txt: #46536b; --muted: #64748b; --head: #0f172a;
            --navbar-bg: rgba(255,255,255,.82); --hover: rgba(15,23,42,.05);
            --brand: #6366f1; --brand2: #8b5cf6; --accent: #0ea5e9;
        }
        * { font-family: 'Inter', system-ui, sans-serif; }
        body { background: var(--bg); color: var(--txt); overflow-x: hidden; }
        h1,h2,h3,h4,.display-font { font-family: 'Space Grotesk', sans-serif; letter-spacing: -.5px; color: var(--head); }
        /* La maquette du dashboard reste sombre (capture produit) dans les deux thèmes */
        .hx-wrap { --txt:#e8ecf6; --muted:#9aa6c2; --head:#ffffff; --border:rgba(255,255,255,.10); --card:rgba(255,255,255,.05); }
        :root[data-theme="light"] .text-white { color: var(--head) !important; }
        :root[data-theme="light"] .stars { display: none; }
        :root[data-theme="light"] .cosmos { background:
            radial-gradient(900px 500px at 80% -5%, rgba(124,131,255,.12), transparent 60%),
            radial-gradient(800px 500px at 10% 10%, rgba(176,107,255,.10), transparent 55%),
            linear-gradient(180deg, var(--bg), var(--bg2)); }

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

        .navbar { backdrop-filter: blur(14px); background: var(--navbar-bg); border-bottom: 1px solid var(--border); }
        .brand-logo { font-family:'Space Grotesk'; font-weight:700; font-size:1.4rem; color:var(--head); }
        .brand-logo span { background: linear-gradient(90deg,var(--brand),var(--brand2)); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }
        .nav-link { color: var(--muted) !important; font-weight:500; }
        .nav-link:hover { color:var(--head) !important; }
        .theme-btn { width:42px; height:42px; border-radius:12px; border:1px solid var(--border); background:var(--card);
            color:var(--txt); cursor:pointer; display:grid; place-items:center; font-size:1rem; }
        .theme-btn:hover { color:var(--head); }

        .btn-glow { background: linear-gradient(90deg,var(--brand),var(--brand2)); color:#fff; border:none; font-weight:600;
            border-radius: 12px; padding:.7rem 1.4rem; box-shadow: 0 12px 34px -10px rgba(124,131,255,.7); transition:.25s; }
        .btn-glow:hover { color:#fff; transform: translateY(-2px); box-shadow: 0 18px 44px -10px rgba(176,107,255,.8); }
        .btn-ghost { border:1px solid var(--border); color:var(--head); border-radius:12px; padding:.7rem 1.3rem; font-weight:600; background:transparent; transition:.25s; }
        .btn-ghost:hover { background: var(--hover); color:var(--head); border-color: var(--brand); }

        .chip { display:inline-flex; align-items:center; gap:.5rem; padding:.4rem .9rem; border:1px solid var(--border);
            border-radius:999px; background: var(--card); font-size:.85rem; color: var(--muted); }
        .grad-text { background: linear-gradient(100deg,var(--brand) 10%,var(--brand2) 55%, var(--accent) 110%);
            -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }

        .glass { background: var(--card); border:1px solid var(--border); border-radius: 20px; backdrop-filter: blur(8px);
            transition: transform .3s, border-color .3s, box-shadow .3s; }
        .glass:hover { transform: translateY(-5px); border-color: rgba(124,131,255,.5); box-shadow: 0 24px 60px -30px rgba(124,131,255,.6); }
        .ico { width:50px;height:50px;border-radius:14px; display:grid;place-items:center; font-size:1.3rem; color:var(--head);
            background: linear-gradient(135deg, rgba(124,131,255,.35), rgba(176,107,255,.25)); border:1px solid var(--border); }

        .section { padding: 6rem 0; }
        .text-muted2 { color: var(--muted) !important; }
        /* Le contenu occupe (presque) toute la largeur · marges minimes, plus de "boîte" centrée étroite */
        @media (min-width: 992px){
            .container { max-width: none; padding-left: 3.5vw; padding-right: 3.5vw; }
        }
        @media (min-width: 1600px){
            .container { padding-left: 5vw; padding-right: 5vw; }
        }
        #globe-hero { width:100%; height:520px; }
        .marquee { overflow:hidden; border-block:1px solid var(--border); background: var(--white, #fff); }
        .marquee .track { display:inline-flex; gap:3rem; white-space:nowrap; padding:1rem 0; animation: scroll 26s linear infinite; }
        .marquee .track span { color: var(--muted); font-weight:600; font-family:'Space Grotesk'; }
        @keyframes scroll { from{transform:translateX(0);} to{transform:translateX(-50%);} }

        .price-card { background: var(--card); border:1px solid var(--border); border-radius:20px; transition:.3s; }
        .price-card:hover { transform: translateY(-6px); border-color: rgba(124,131,255,.5); }
        .price-card.pop { border:1px solid transparent; background:
            linear-gradient(var(--bg2),var(--bg2)) padding-box, linear-gradient(135deg,var(--brand),var(--brand2)) border-box; }
        .price-amount { font-family:'Space Grotesk'; font-weight:700; font-size:2.4rem; }
        .form-select, .form-select:focus { background:var(--bg2); color:var(--txt); border:1px solid var(--border); }
        .form-select option { background:var(--bg2); color:var(--txt); }
        .step-dot { width:44px;height:44px;border-radius:50%; display:grid;place-items:center; font-family:'Space Grotesk'; font-weight:700;
            background: linear-gradient(135deg,var(--brand),var(--brand2)); color:#fff; }
        footer { border-top:1px solid var(--border); }
        .accordion-button { background: transparent !important; color:var(--head) !important; padding:1.1rem 1.25rem; }
        .accordion-button:not(.collapsed) { color:var(--head) !important; box-shadow:none; }
        .accordion-button:focus { box-shadow:none; border-color: transparent; }
        .accordion-button::after { filter: invert(1) brightness(2); }
        :root[data-theme="light"] .accordion-button::after { filter: none; }
        .accordion-body { padding:0 1.25rem 1.2rem; }
        .footer-preview { position:fixed; bottom:14px; left:50%; transform:translateX(-50%); z-index:1000;
            background: rgba(12,18,36,.9); border:1px solid var(--border); border-radius:999px; padding:.5rem 1rem; font-size:.85rem; backdrop-filter:blur(8px); }
        .footer-preview a { color: var(--brand); }

        /* ===== Maquette dashboard (hero) ===== */
        .hx-wrap { position: relative; }
        .hx-dash { background:#0d1426; border:1px solid var(--border); border-radius:16px; overflow:hidden;
            box-shadow:0 40px 80px -30px rgba(0,0,0,.7); }
        .hx-top { display:flex; align-items:center; gap:12px; padding:11px 14px; border-bottom:1px solid var(--border); }
        .hx-brand { font-family:'Space Grotesk'; font-weight:700; font-size:.85rem; color:#fff; white-space:nowrap; }
        .hx-brand i { color:var(--brand); }
        .hx-search { flex:1; background:var(--white, #fff); border:1px solid var(--border); border-radius:8px;
            padding:6px 11px; font-size:.68rem; color:var(--muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .hx-user { display:flex; align-items:center; gap:8px; color:var(--muted); }
        .hx-ava { width:24px; height:24px; border-radius:50%; background:linear-gradient(135deg,var(--brand),var(--brand2));
            color:#fff; display:grid; place-items:center; font-weight:700; font-size:.66rem; }
        .hx-uname { color:#fff; font-weight:600; line-height:1; font-size:.66rem; }
        .hx-urole { font-size:.58rem; }
        .hx-body { display:flex; }
        .hx-side { width:148px; flex-shrink:0; border-right:1px solid var(--border); padding:9px 7px; }
        .hx-nav { display:flex; align-items:center; gap:8px; padding:6px 9px; border-radius:8px; font-size:.7rem;
            color:var(--muted); margin-bottom:2px; }
        .hx-nav i { width:14px; text-align:center; font-size:.7rem; }
        .hx-nav.active { background:linear-gradient(90deg,rgba(124,131,255,.28),rgba(176,107,255,.12)); color:#fff; }
        .hx-main { flex:1; padding:11px; min-width:0; }
        .hx-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:7px; margin-bottom:9px; }
        .hx-card { background:var(--white, #fff); border:1px solid var(--border); border-radius:10px; padding:8px; }
        .hx-clab { font-size:.55rem; color:var(--muted); margin-bottom:4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .hx-cval { font-family:'Space Grotesk'; font-weight:700; color:#fff; font-size:.95rem; line-height:1; }
        .hx-cval small { font-size:.5rem; color:var(--muted); }
        .hx-cup { font-size:.52rem; color:#29e0c8; margin-top:3px; }
        .hx-charts { display:grid; grid-template-columns:1.5fr 1fr; gap:7px; }
        .hx-chart { background:var(--white, #fff); border:1px solid var(--border); border-radius:10px; padding:9px; }
        .hx-ctitle { font-size:.6rem; color:var(--muted); margin-bottom:6px; }
        .hx-donut-wrap { display:flex; align-items:center; gap:9px; }
        .hx-donut { width:66px; height:66px; border-radius:50%; flex-shrink:0;
            background:conic-gradient(#7c83ff 0 62%, #29e0c8 62% 90%, #b06bff 90% 100%);
            -webkit-mask:radial-gradient(circle 19px at center, transparent 98%, #000 100%);
            mask:radial-gradient(circle 19px at center, transparent 98%, #000 100%); }
        .hx-legend { font-size:.57rem; color:var(--muted); flex:1; }
        .hx-legend div { display:flex; align-items:center; gap:5px; margin-bottom:4px; }
        .hx-legend span { width:8px; height:8px; border-radius:2px; display:inline-block; }
        .hx-legend b { color:#fff; margin-left:auto; }
        .hx-phone { position:absolute; right:-8px; bottom:-26px; width:145px; background:#0d1426;
            border:1px solid var(--border); border-radius:20px; padding:12px; box-shadow:0 30px 60px -18px rgba(0,0,0,.85); }
        .hx-ptop { font-size:.6rem; color:#fff; font-weight:600; margin-bottom:9px; }
        .hx-pfield { background:var(--white, #fff); border:1px solid var(--border); border-radius:8px; padding:5px 8px; margin-bottom:6px; }
        .hx-pfield label { font-size:.48rem; color:var(--muted); display:block; }
        .hx-pfield div { font-size:.6rem; color:#fff; }
        .hx-pbtn { background:linear-gradient(90deg,var(--brand),var(--brand2)); color:#fff; text-align:center;
            font-size:.6rem; font-weight:600; padding:6px; border-radius:8px; margin-top:2px; }
        .hx-phint { font-size:.5rem; color:var(--muted); text-align:center; margin-top:6px; }
        @media (max-width:991px) { .hx-phone { display:none; } .hx-side { width:120px; } }
        @media (max-width:575px) { .hx-side { display:none; } .hx-stats { grid-template-columns:repeat(2,1fr); } .hx-charts { grid-template-columns:1fr; } }
    </style>
    <script>
        // Thème appliqué avant le rendu (évite le flash). Défaut : sombre.
        (function(){ try{ document.documentElement.setAttribute('data-theme', localStorage.getItem('landing-theme') || 'dark'); }catch(e){} })();
    </script>
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
                <li class="nav-item"><a class="nav-link" href="#features">{{ __('landing_v2.nav_features') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="#how">{{ __('landing_v2.nav_how') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('guide') }}">{{ __('landing_v2.nav_guide') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="#pricing">{{ __('landing_v2.nav_pricing') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="#faq">{{ __('landing_v2.nav_faq') }}</a></li>
            </ul>
            <div class="d-flex gap-2 align-items-center">
                <a href="{{ route('lang.switch', app()->getLocale() === 'fr' ? 'en' : 'fr') }}" class="btn-ghost" style="padding:.45rem .8rem;font-size:.85rem;">{{ __('landing.nav_switch_lang') }}</a>
                <button class="theme-btn" id="themeToggle" type="button" aria-label="Changer de thème"><i class="fas fa-moon"></i></button>
                <a href="{{ route('login.index') }}" class="btn-ghost">{{ __('landing_v2.nav_login') }}</a>
                <a href="{{ route('hotel.register') }}" class="btn-glow">{{ __('landing_v2.nav_free_trial') }}</a>
            </div>
        </div>
    </div>
</nav>

<!-- HERO avec maquette du produit -->
<header style="padding:8.5rem 0 4rem;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5" data-aos="fade-up">
                <span class="chip mb-3"><i class="fas fa-gem" style="color:var(--accent);font-size:.7rem"></i> {{ __('landing_v2.hero_chip', ['text' => config('plans.trial_days', 14)]) }}</span>
                <h1 class="fw-bold mb-3" style="font-size:clamp(2.3rem,4.6vw,3.4rem);line-height:1.04;">{{ __('landing_v2.hero_title_1') }}<br><span class="grad-text">{{ __('landing_v2.hero_title_2') }}</span></h1>
                <p class="fs-5 text-muted2 mb-3" style="max-width:480px;">
                    {{ __('landing_v2.hero_description') }}
                </p>
                <div class="d-flex align-items-center gap-2 mb-4">
                    <span style="color:#fbbf24;letter-spacing:2px;">★★★★★</span>
                    <span class="text-muted2 small"><strong class="text-white">4,9/5</strong> {{ __('landing_v2.hero_stars_label') }}</span>
                </div>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="{{ route('hotel.register') }}" class="btn-glow btn-lg" style="line-height:1.15;">
                        <i class="fas fa-rocket me-1"></i> {{ __('landing_v2.hero_cta_start') }}
                        <span style="display:block;font-size:.7rem;font-weight:400;opacity:.85">{{ __('landing_v2.hero_cta_start_sub', ['text' => config('plans.trial_days', 14)]) }}</span>
                    </a>
                    <a href="#features" class="btn-ghost btn-lg d-inline-flex align-items-center gap-2" style="line-height:1.15;">
                        <i class="fas fa-circle-play fs-5"></i>
                        <span style="text-align:left">{{ __('landing_v2.hero_cta_demo') }}<span style="display:block;font-size:.7rem;font-weight:400;opacity:.7">{{ __('landing_v2.hero_cta_demo_sub') }}</span></span>
                    </a>
                </div>
                <div class="d-flex flex-wrap gap-4 text-muted2 small mb-4">
                    <span><i class="fas fa-check text-white me-1"></i> {{ __('landing_v2.hero_check_1') }}</span>
                    <span><i class="fas fa-rotate text-white me-1"></i> {{ __('landing_v2.hero_check_2') }}</span>
                    <span><i class="fab fa-whatsapp me-1" style="color:#25d366"></i> {{ __('landing_v2.hero_check_3') }}</span>
                </div>
                <div class="glass p-3" style="max-width:520px;">
                    <div class="small text-muted2 mb-2">{{ __('landing_v2.hero_countries_label', ['count' => count(config('plans.countries'))]) }}</div>
                    <div class="d-flex flex-wrap gap-2">
                        @php $flags = ['SN'=>'🇸🇳','CI'=>'🇨🇮','BJ'=>'🇧🇯','TG'=>'🇹🇬','BF'=>'🇧🇫','ML'=>'🇲🇱']; @endphp
                        @foreach ($flags as $code => $flag)
                            <span class="chip" style="font-size:.72rem;padding:.3rem .6rem;">{{ $flag }} {{ config('plans.countries.'.$code.'.name') }}</span>
                        @endforeach
                        <span class="chip" style="font-size:.72rem;padding:.3rem .6rem;color:var(--brand)">+ {{ max(0, count(config('plans.countries')) - count($flags)) }} pays</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-7" data-aos="fade-left" data-aos-delay="150">
                <div class="hx-wrap">
                    <div class="hx-dash">
                        <div class="hx-top">
                            <div class="hx-brand"><i class="fas fa-location-dot"></i> check<span>inHub</span></div>
                            <div class="hx-search"><i class="fas fa-magnifying-glass"></i> Rechercher une réservation, un client…</div>
                            <div class="hx-user"><i class="fas fa-bell"></i><span class="hx-ava">M</span><div><div class="hx-uname">Marie K.</div><div class="hx-urole">Réception</div></div></div>
                        </div>
                        <div class="hx-body">
                            <div class="hx-side">
                                @php $nav = [['fa-gauge-high','Dashboard',1],['fa-calendar-check','Réservations',0],['fa-calendar-days','Calendrier',0],['fa-users','Clients',0],['fa-cash-register','Caisse',0],['fa-broom','Housekeeping',0],['fa-chart-line','Rapports',0],['fa-globe','Site Web',0],['fa-gear','Paramètres',0]]; @endphp
                                @foreach ($nav as $n)
                                    <div class="hx-nav {{ $n[2] ? 'active' : '' }}"><i class="fas {{ $n[0] }}"></i> {{ $n[1] }}</div>
                                @endforeach
                            </div>
                            <div class="hx-main">
                                <div class="hx-stats">
                                    <div class="hx-card"><div class="hx-clab">Réservations aujourd'hui</div><div class="hx-cval">24</div><div class="hx-cup">↑ 12% vs hier</div></div>
                                    <div class="hx-card"><div class="hx-clab">Arrivées</div><div class="hx-cval">12</div><div class="hx-cup">↑ 8% vs hier</div></div>
                                    <div class="hx-card"><div class="hx-clab">Départs</div><div class="hx-cval">8</div><div class="hx-cup">↑ 5% vs hier</div></div>
                                    <div class="hx-card"><div class="hx-clab">Chiffre d'affaires</div><div class="hx-cval">1 250 000 <small>FCFA</small></div><div class="hx-cup">↑ 15% vs hier</div></div>
                                </div>
                                <div class="hx-charts">
                                    <div class="hx-chart">
                                        <div class="hx-ctitle">Évolution des réservations</div>
                                        <svg viewBox="0 0 260 110" preserveAspectRatio="none" style="width:100%;height:110px;">
                                            <defs><linearGradient id="hxg" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#7c83ff" stop-opacity=".35"/><stop offset="1" stop-color="#7c83ff" stop-opacity="0"/></linearGradient></defs>
                                            <polyline points="0,80 37,70 74,74 111,55 148,60 185,40 222,44 260,25" fill="none" stroke="#7c83ff" stroke-width="2.5" stroke-linejoin="round"/>
                                            <polygon points="0,80 37,70 74,74 111,55 148,60 185,40 222,44 260,25 260,110 0,110" fill="url(#hxg)"/>
                                            <circle cx="260" cy="25" r="3.5" fill="#7c83ff"/>
                                        </svg>
                                    </div>
                                    <div class="hx-chart">
                                        <div class="hx-ctitle">Répartition des chambres</div>
                                        <div class="hx-donut-wrap">
                                            <div class="hx-donut"></div>
                                            <div class="hx-legend">
                                                <div><span style="background:#7c83ff"></span>Occupées <b>62%</b></div>
                                                <div><span style="background:#29e0c8"></span>Disponibles <b>28%</b></div>
                                                <div><span style="background:#b06bff"></span>Réservées <b>10%</b></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hx-phone">
                        <div class="hx-ptop">‹ Nouvelle réservation</div>
                        <div class="hx-pfield"><label>Arrivée</label><div>18 Mai 2024</div></div>
                        <div class="hx-pfield"><label>Départ</label><div>20 Mai 2024</div></div>
                        <div class="hx-pfield"><label>Chambres</label><div>1 chambre, 2 adultes</div></div>
                        <div class="hx-pbtn">Rechercher</div>
                        <div class="hx-phint">12 chambres disponibles</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- STATS (chiffres animés) -->
<section class="py-5">
    <div class="container">
        <div class="row g-4 text-center">
            @php
                // Vrais chiffres de la plateforme (route publique = aucun tenant → comptage global).
                // Mis en cache 10 min pour ne pas requêter à chaque visite.
                try {
                    $kpi = \Illuminate\Support\Facades\Cache::remember('landing_kpis', 600, function () {
                        return [
                            'hotels'   => \App\Models\Hotel::count(),
                            'rooms'    => \App\Models\Room::count(),
                            'bookings' => \App\Models\Transaction::count(),
                        ];
                    });
                } catch (\Throwable $e) {
                    $kpi = ['hotels' => 0, 'rooms' => 0, 'bookings' => 0];
                }
                $stats = [
                    ['target'=>$kpi['hotels'],                    'suffix'=>'', 'label'=>__('landing_v2.stat_hotels'), 'icon'=>'fa-hotel'],
                    ['target'=>count(config('plans.countries')),  'suffix'=>'', 'label'=>__('landing_v2.stat_countries'),      'icon'=>'fa-earth-africa'],
                    ['target'=>$kpi['rooms'],                     'suffix'=>'', 'label'=>__('landing_v2.stat_rooms'),       'icon'=>'fa-bed'],
                    ['target'=>$kpi['bookings'],                  'suffix'=>'', 'label'=>__('landing_v2.stat_bookings'), 'icon'=>'fa-calendar-check'],
                ];
            @endphp
            @foreach ($stats as $i => $s)
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $i*100 }}">
                    <div class="glass p-4 h-100">
                        <div class="ico mx-auto mb-3"><i class="fas {{ $s['icon'] }}"></i></div>
                        <div class="display-5 fw-bold grad-text"><span class="counter" data-target="{{ $s['target'] }}" data-decimals="{{ $s['decimals'] ?? 0 }}">0</span>{{ $s['suffix'] }}</div>
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
            <span class="chip mb-2">{{ __('landing_v2.features_badge') }}</span>
            <h2 class="fw-bold">{{ __('landing_v2.features_title_1') }} <span class="grad-text">{{ __('landing_v2.features_title_2') }}</span></h2>
        </div>
        <div class="row g-4">
            @php $feats = [
                ['fa-calendar-check',__('landing_v2.feature_1_title'),__('landing_v2.feature_1_desc')],
                ['fa-cash-register',__('landing_v2.feature_2_title'),__('landing_v2.feature_2_desc')],
                ['fa-broom',__('landing_v2.feature_3_title'),__('landing_v2.feature_3_desc')],
                ['fa-utensils',__('landing_v2.feature_4_title'),__('landing_v2.feature_4_desc')],
                ['fa-chart-line',__('landing_v2.feature_5_title'),__('landing_v2.feature_5_desc')],
                ['fa-globe',__('landing_v2.feature_6_title'),__('landing_v2.feature_6_desc')],
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
            <span class="chip mb-2">{{ __('landing_v2.how_badge') }}</span>
            <h2 class="fw-bold">{{ __('landing_v2.how_title_1') }} <span class="grad-text">{{ __('landing_v2.how_title_2') }}</span></h2>
        </div>
        <div class="row g-4">
            @php $steps = [[__('landing_v2.how_step_1_title'),__('landing_v2.how_step_1_desc')],
                [__('landing_v2.how_step_2_title'),__('landing_v2.how_step_2_desc')],
                [__('landing_v2.how_step_3_title'),__('landing_v2.how_step_3_desc')]]; @endphp
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
            <span class="chip mb-2">{{ __('landing_v2.testimonials_badge') }}</span>
            <h2 class="fw-bold">{{ __('landing_v2.testimonials_title_1') }} <span class="grad-text">{{ __('landing_v2.testimonials_title_2') }}</span></h2>
        </div>
        <div class="row g-4">
            @php $temoins = [
                [__('landing_v2.testimonial_1_name'),__('landing_v2.testimonial_1_role'),__('landing_v2.testimonial_1_quote')],
                [__('landing_v2.testimonial_2_name'),__('landing_v2.testimonial_2_role'),__('landing_v2.testimonial_2_quote')],
                [__('landing_v2.testimonial_3_name'),__('landing_v2.testimonial_3_role'),__('landing_v2.testimonial_3_quote')],
            ]; @endphp
            @foreach ($temoins as $i => $t)
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $i*120 }}">
                    <div class="glass p-4 h-100">
                        <div class="mb-2" style="color:var(--accent)">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="mb-4">“{{ $t[2] }}”</p>
                        <div class="d-flex align-items-center gap-3 mt-auto">
                            <div class="ico" style="width:44px;height:44px;font-family:'Space Grotesk';font-weight:700">{{ substr($t[0], 0, 1) }}</div>
                            <div>
                                <div class="fw-semibold">{{ $t[0] }}</div>
                                <div class="text-muted2 small">{{ $t[1] }}</div>
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
            <span class="chip mb-2">{{ __('landing_v2.pricing_badge') }}</span>
            <h2 class="fw-bold">{{ __('landing_v2.pricing_title_1') }} <span class="grad-text">{{ __('landing_v2.pricing_title_2') }}</span></h2>
            <p class="text-muted2">{{ __('landing_v2.pricing_description') }}</p>
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
                @php
                    $pop = !empty($tier['popular']);
                    $min = $tier['room_min']; $max = $tier['room_max'];
                    $rooms = $max === null ? __('flash.plan_business_f1') : ($min <= 0 ? __('flash.plan_starter_f1') : __('flash.plan_pro_f1'));
                    $taglines = ['starter' => __('flash.plan_starter_tagline'), 'pro' => __('flash.plan_pro_tagline'), 'business' => __('flash.plan_business_tagline')];
                    $features = ['starter' => [__('flash.plan_starter_f1'), __('flash.plan_starter_f2'), __('flash.plan_starter_f3'), __('flash.plan_starter_f4')], 'pro' => [__('flash.plan_pro_f1'), __('flash.plan_pro_f2'), __('flash.plan_pro_f3'), __('flash.plan_pro_f4')], 'business' => [__('flash.plan_business_f1'), __('flash.plan_business_f2'), __('flash.plan_business_f3'), __('flash.plan_business_f4')]];
                @endphp
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index*120 }}">
                    <div class="price-card {{ $pop ? 'pop' : '' }} p-4 h-100" data-base="{{ $tier['price'] }}">
                        @if ($pop)<span class="chip mb-2" style="border-color:var(--brand);color:var(--head)"><i class="fas fa-star" style="color:var(--accent)"></i> {{ __('flash.plan_popular') }}</span>@endif
                        <h4 class="fw-bold">{{ $tier['name'] }}</h4>
                        <p class="text-muted2 small">{{ $taglines[$key] ?? $tier['tagline'] }}</p>
                        <div class="chip mb-2" style="color:var(--head)"><i class="fas fa-bed" style="color:var(--brand)"></i> {{ $rooms }}@if ($max === null) · illimité @endif</div>
                        <div class="my-2"><span class="price-amount pr-amount">{{ number_format($tier['price'],0,',',' ') }}</span>
                            <span class="text-muted2"><span class="pr-cur">XOF</span> {{ __('flash.plan_per_month') }}</span></div>
                        <hr style="border-color:var(--border)">
                        <ul class="list-unstyled mb-4">
                            @foreach (($features[$key] ?? $tier['features']) as $item)
                                <li class="mb-2 text-muted2"><i class="fas fa-check me-2" style="color:var(--accent)"></i>{{ $item }}</li>
                            @endforeach
                        </ul>
                        <a href="{{ route('hotel.register', ['plan'=>$key]) }}" class="{{ $pop ? 'btn-glow' : 'btn-ghost' }} w-100 d-block text-center">{{ __('landing_v2.pricing_choose') }} {{ $tier['name'] }}</a>
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
            <span class="chip mb-2">{{ __('landing_v2.faq_badge') }}</span>
            <h2 class="fw-bold">{{ __('landing_v2.faq_title_1') }} <span class="grad-text">{{ __('landing_v2.faq_title_2') }}</span></h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                @php $faqs = [
                    [__('landing_v2.faq_1_q'),__('landing_v2.faq_1_a', ['text' => config('plans.trial_days', 14)])],
                    [__('landing_v2.faq_2_q'),__('landing_v2.faq_2_a')],
                    [__('landing_v2.faq_3_q'),__('landing_v2.faq_3_a')],
                    [__('landing_v2.faq_4_q'),__('landing_v2.faq_4_a')],
                    [__('landing_v2.faq_5_q'),__('landing_v2.faq_5_a')],
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
            <h2 class="fw-bold mb-2">{{ __('landing_v2.cta_title') }}</h2>
            <p class="text-muted2 mb-4">{{ __('landing_v2.cta_description', ['text' => config('plans.trial_days', 14)]) }}</p>
            <a href="{{ route('hotel.register') }}" class="btn-glow btn-lg"><i class="fas fa-rocket me-2"></i>{{ __('landing_v2.cta_button') }}</a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="py-5">
    <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">
        <span class="brand-logo">check<span>inHub</span></span>
        <span class="text-muted2 small">&copy; {{ now()->year }} checkinHub. {{ __('landing_v2.footer_rights') }}</span>
        <a href="{{ route('login.index') }}" class="btn-ghost">{{ __('landing_v2.footer_login') }}</a>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://unpkg.com/globe.gl"></script>
<script>
    try { AOS.init({ duration: 700, once: true, offset: 60 }); } catch(e){}

    // Bascule clair / sombre
    (function () {
        const btn = document.getElementById('themeToggle');
        if (!btn) return;
        const icon = () => { btn.querySelector('i').className =
            document.documentElement.getAttribute('data-theme') === 'light' ? 'fas fa-sun' : 'fas fa-moon'; };
        icon();
        btn.addEventListener('click', () => {
            const next = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', next);
            try { localStorage.setItem('landing-theme', next); } catch(e){}
            icon();
        });
    })();

    // Compteurs animés (count-up au scroll)
    (function () {
        const counters = document.querySelectorAll('.counter');
        if (!counters.length) return;
        const fmt = (v, dec) => dec
            ? v.toFixed(dec).replace('.', ',')
            : Math.round(v).toLocaleString('fr-FR');
        const run = (el) => {
            const target = +el.dataset.target, dec = +(el.dataset.decimals || 0), dur = 1400, t0 = performance.now();
            const tick = (now) => {
                const p = Math.min((now - t0) / dur, 1);
                el.textContent = fmt(target * (1 - Math.pow(1 - p, 3)), dec);
                if (p < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        };
        if ('IntersectionObserver' in window) {
            const obs = new IntersectionObserver((es) => es.forEach(e => { if (e.isIntersecting) { run(e.target); obs.unobserve(e.target); } }), { threshold: .6 });
            counters.forEach(c => obs.observe(c));
        } else { counters.forEach(c => c.textContent = fmt(+c.dataset.target, +(c.dataset.decimals || 0))); }
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
