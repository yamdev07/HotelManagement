<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'checkinHub') }}, {{ __('landing.page_title') }}</title>
    <meta name="description" content="{{ __('landing.meta_description') }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{
            --bg:#07071a; --bg2:#0b0b22;
            --panel:rgba(255,255,255,.035); --panel2:rgba(255,255,255,.06);
            --line:rgba(255,255,255,.09); --line2:rgba(255,255,255,.14);
            --txt:#ecefff; --muted:#9aa1bd; --muted2:#7b82a3;
            --v1:#8b5cf6; --v2:#6366f1; --v3:#a855f7; --pink:#d946ef;
            --grad:linear-gradient(135deg,#7c3aed 0%,#6366f1 100%);
            --grad-text:linear-gradient(90deg,#a855f7 0%,#8b5cf6 45%,#6366f1 100%);
            --sans:'Inter',system-ui,sans-serif; --disp:'Sora','Inter',sans-serif;
        }
        *{font-family:var(--sans);box-sizing:border-box;}
        html{scroll-behavior:smooth;}
        body{background:var(--bg);color:var(--txt);overflow-x:hidden;}
        a{text-decoration:none;}
        .container{max-width:1220px;}

        /* Ambient glow + dotted backdrop */
        .bg-fx{position:fixed;inset:0;z-index:-2;background:
            radial-gradient(900px 520px at 78% -6%, rgba(124,58,237,.28), transparent 60%),
            radial-gradient(760px 460px at 8% 8%, rgba(99,102,241,.20), transparent 62%),
            radial-gradient(700px 500px at 60% 108%, rgba(168,85,247,.14), transparent 60%),
            var(--bg);}
        .bg-dots{position:fixed;inset:0;z-index:-1;opacity:.5;pointer-events:none;
            background-image:radial-gradient(rgba(255,255,255,.06) 1px, transparent 1px);
            background-size:26px 26px;
            -webkit-mask-image:radial-gradient(1200px 700px at 75% 22%, #000 0%, transparent 72%);
            mask-image:radial-gradient(1200px 700px at 75% 22%, #000 0%, transparent 72%);}

        /* ===== NAV ===== */
        .nav-c{position:sticky;top:0;z-index:60;padding:16px 0;transition:.3s;}
        .nav-c.scrolled{background:rgba(9,9,26,.82);backdrop-filter:blur(16px) saturate(160%);border-bottom:1px solid var(--line);padding:11px 0;}
        .nav-in{display:flex;align-items:center;gap:20px;}
        .brand{display:flex;align-items:center;gap:9px;font-family:var(--disp);font-weight:800;font-size:1.28rem;color:#fff;letter-spacing:-.4px;}
        .brand .pin{width:30px;height:30px;border-radius:9px;background:var(--grad);display:grid;place-items:center;color:#fff;font-size:.85rem;box-shadow:0 8px 22px -8px var(--v2);}
        .brand span{color:var(--v3);}
        .nav-links{display:flex;align-items:center;gap:28px;margin:0 auto;}
        .nav-links a{color:#c3c8e0;font-size:.94rem;font-weight:500;transition:.2s;}
        .nav-links a:hover{color:#fff;}
        .nav-actions{display:flex;align-items:center;gap:10px;}
        .pill{border:1px solid var(--line2);border-radius:10px;padding:8px 13px;color:#d7dbf0;font-size:.86rem;font-weight:600;background:transparent;cursor:pointer;transition:.2s;display:inline-flex;align-items:center;gap:6px;}
        .pill:hover{border-color:var(--v2);color:#fff;}
        .icon-btn{width:38px;height:38px;border-radius:10px;border:1px solid var(--line2);background:transparent;color:#c3c8e0;display:grid;place-items:center;cursor:pointer;transition:.2s;}
        .icon-btn:hover{color:#fff;border-color:var(--v2);}
        .btn-grad{background:var(--grad);color:#fff;border:0;border-radius:11px;padding:9px 18px;font-weight:700;font-size:.9rem;display:inline-flex;align-items:center;gap:8px;box-shadow:0 12px 30px -10px rgba(124,58,237,.7);transition:transform .18s, box-shadow .2s;}
        .btn-grad:hover{transform:translateY(-2px);color:#fff;box-shadow:0 18px 40px -12px rgba(124,58,237,.85);}
        .btn-ghost2{border:1px solid var(--line2);border-radius:11px;padding:9px 18px;color:#e6e9fb;font-weight:600;font-size:.9rem;display:inline-flex;align-items:center;gap:8px;transition:.2s;}
        .btn-ghost2:hover{border-color:var(--v2);color:#fff;background:var(--panel);}

        /* ===== HERO ===== */
        .hero{padding:64px 0 40px;position:relative;}
        .eyebrow{display:inline-flex;align-items:center;gap:9px;border:1px solid var(--line2);background:var(--panel);border-radius:999px;padding:7px 15px;font-size:.82rem;color:#cfd4ef;}
        .eyebrow b{color:#fff;font-weight:700;}
        .eyebrow .tag{background:rgba(124,58,237,.22);color:#c4b5fd;border-radius:999px;padding:2px 9px;font-weight:700;font-size:.74rem;}
        .h1{font-family:var(--disp);font-weight:800;font-size:clamp(2.5rem,4.9vw,4rem);line-height:1.03;letter-spacing:-1.6px;color:#fff;margin:22px 0 18px;}
        .h1 .grad{background:var(--grad-text);-webkit-background-clip:text;background-clip:text;color:transparent;}
        .lead{font-size:1.12rem;color:var(--muted);max-width:520px;line-height:1.6;}
        .rating{display:flex;align-items:center;gap:11px;margin:20px 0 26px;}
        .rating .stars{color:#fbbf24;letter-spacing:2px;font-size:1rem;}
        .rating .rtxt{color:#c3c8e0;font-size:.92rem;}
        .rating .rtxt b{color:#fff;}
        .cta-row{display:flex;flex-wrap:wrap;gap:14px;margin-bottom:22px;}
        .btn-lg-grad{background:var(--grad);color:#fff;border:0;border-radius:14px;padding:15px 26px;font-weight:700;font-size:1rem;display:inline-flex;align-items:center;gap:11px;box-shadow:0 16px 40px -12px rgba(124,58,237,.75);transition:transform .18s,box-shadow .2s;}
        .btn-lg-grad:hover{transform:translateY(-2px);color:#fff;box-shadow:0 24px 55px -14px rgba(124,58,237,.9);}
        .btn-lg-grad small,.btn-lg-ghost small{display:block;font-weight:500;font-size:.72rem;opacity:.8;margin-top:1px;}
        .btn-lg-ghost{border:1px solid var(--line2);border-radius:14px;padding:15px 24px;color:#eceeff;font-weight:700;font-size:1rem;display:inline-flex;align-items:center;gap:11px;background:var(--panel);transition:.2s;}
        .btn-lg-ghost:hover{border-color:var(--v2);color:#fff;}
        .btn-lg-ghost .pc{width:34px;height:34px;border-radius:50%;background:var(--panel2);display:grid;place-items:center;color:var(--v3);}
        .checks{display:flex;flex-wrap:wrap;gap:20px;color:#b9bfda;font-size:.9rem;margin-bottom:26px;}
        .checks span{display:inline-flex;align-items:center;gap:8px;}
        .checks i{color:#34d399;}
        .checks .i2{color:#fbbf24;} .checks .i3{color:#22c55e;}
        .countries{border:1px solid var(--line);background:var(--panel);border-radius:16px;padding:16px 18px;max-width:540px;}
        .countries .ct-h{font-size:.82rem;color:var(--muted);font-weight:600;margin-bottom:11px;}
        .flags{display:flex;flex-wrap:wrap;gap:9px;}
        .flag-chip{display:inline-flex;align-items:center;gap:7px;border:1px solid var(--line2);border-radius:999px;padding:5px 12px;font-size:.84rem;color:#e2e5f7;background:rgba(255,255,255,.03);}
        .flag-chip .fl{font-size:1rem;line-height:1;}

        /* ===== DASHBOARD PREVIEW ===== */
        .dashwrap{position:relative;}
        .dashwrap::before{content:"";position:absolute;inset:-30px -20px;background:radial-gradient(closest-side,rgba(124,58,237,.35),transparent 75%);filter:blur(20px);z-index:0;}
        .dash{position:relative;z-index:1;border:1px solid var(--line2);border-radius:20px;background:linear-gradient(160deg,#12122e 0%,#0c0c22 100%);box-shadow:0 40px 90px -30px rgba(0,0,0,.7), 0 0 0 1px rgba(124,58,237,.15);overflow:hidden;display:grid;grid-template-columns:150px 1fr;animation:floaty 7s ease-in-out infinite;}
        @keyframes floaty{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}
        .dsb{background:rgba(255,255,255,.02);border-right:1px solid var(--line);padding:14px 10px;}
        .dsb-logo{display:flex;align-items:center;gap:6px;font-weight:800;font-size:.82rem;color:#fff;margin:2px 4px 14px;font-family:var(--disp);}
        .dsb-logo i{color:var(--v3);}
        .dsb a{display:flex;align-items:center;gap:9px;color:#8b91af;font-size:.74rem;padding:7px 9px;border-radius:8px;margin-bottom:2px;font-weight:500;}
        .dsb a i{width:14px;text-align:center;font-size:.72rem;}
        .dsb a.on{background:var(--grad);color:#fff;box-shadow:0 8px 18px -8px var(--v2);}
        .dmn{padding:13px 14px;min-width:0;}
        .dtop{display:flex;align-items:center;gap:10px;margin-bottom:12px;}
        .dsearch{flex:1;background:rgba(255,255,255,.04);border:1px solid var(--line);border-radius:9px;padding:7px 11px;color:#7b82a3;font-size:.72rem;display:flex;align-items:center;gap:7px;}
        .dbell{position:relative;color:#c3c8e0;font-size:.85rem;}
        .dbell b{position:absolute;top:-6px;right:-7px;background:#ef4444;color:#fff;font-size:.55rem;width:14px;height:14px;border-radius:50%;display:grid;place-items:center;font-weight:700;}
        .duser{display:flex;align-items:center;gap:7px;}
        .duser .av{width:26px;height:26px;border-radius:50%;background:var(--grad);display:grid;place-items:center;color:#fff;font-size:.7rem;font-weight:700;}
        .duser .nm{font-size:.68rem;color:#fff;font-weight:600;line-height:1.1;}
        .duser .nm small{display:block;color:#7b82a3;font-weight:400;font-size:.6rem;}
        .dstats{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:11px;}
        .dstat{background:rgba(255,255,255,.03);border:1px solid var(--line);border-radius:11px;padding:9px 10px;}
        .dstat .lb{font-size:.58rem;color:#8b91af;margin-bottom:4px;}
        .dstat .vl{font-size:1rem;font-weight:800;color:#fff;font-family:var(--disp);letter-spacing:-.3px;}
        .dstat .vl small{font-size:.55rem;color:#8b91af;font-weight:500;}
        .dstat .up{font-size:.55rem;color:#34d399;margin-top:3px;}
        .dpanels{display:grid;grid-template-columns:1.35fr 1fr;gap:9px;margin-bottom:10px;}
        .dcard{background:rgba(255,255,255,.03);border:1px solid var(--line);border-radius:12px;padding:11px 12px;}
        .dcard .ttl{display:flex;justify-content:space-between;align-items:center;font-size:.66rem;color:#c3c8e0;font-weight:600;margin-bottom:8px;}
        .dcard .ttl span{font-size:.55rem;color:#7b82a3;font-weight:400;}
        .spark svg{width:100%;height:56px;display:block;}
        .donutwrap{display:flex;align-items:center;gap:11px;}
        .donut{width:74px;height:74px;border-radius:50%;flex-shrink:0;
            background:conic-gradient(#8b5cf6 0 65%, #22c55e 65% 90%, #6366f1 90% 100%);
            -webkit-mask:radial-gradient(circle 20px at center, transparent 98%, #000 100%);
            mask:radial-gradient(circle 20px at center, transparent 98%, #000 100%);}
        .dleg{font-size:.6rem;color:#c3c8e0;display:flex;flex-direction:column;gap:5px;}
        .dleg i{width:7px;height:7px;border-radius:2px;display:inline-block;margin-right:5px;}
        .dform{background:rgba(255,255,255,.03);border:1px solid var(--line);border-radius:12px;padding:11px 12px;}
        .dform .ttl{font-size:.66rem;color:#c3c8e0;font-weight:600;margin-bottom:9px;}
        .dform label{font-size:.55rem;color:#8b91af;display:block;margin-bottom:3px;}
        .dfield{background:rgba(255,255,255,.05);border:1px solid var(--line);border-radius:7px;padding:6px 8px;font-size:.62rem;color:#e2e5f7;margin-bottom:8px;display:flex;justify-content:space-between;align-items:center;}
        .dform .go{background:var(--grad);color:#fff;text-align:center;border-radius:8px;padding:7px;font-size:.66rem;font-weight:700;}

        /* ===== STAT CARDS (metrics) ===== */
        .metrics{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;padding:14px 0 4px;}
        .metric{border:1px solid var(--line);background:var(--panel);border-radius:18px;padding:22px;position:relative;overflow:hidden;transition:.25s;}
        .metric:hover{transform:translateY(-4px);border-color:var(--line2);}
        .metric .mi{width:52px;height:52px;border-radius:14px;display:grid;place-items:center;font-size:1.25rem;margin-bottom:14px;}
        .metric .mv{font-family:var(--disp);font-weight:800;font-size:2.5rem;line-height:1;color:#fff;letter-spacing:-1.5px;}
        .metric .ml{color:var(--muted);font-size:.92rem;margin-top:6px;}
        .metric .ms{position:absolute;right:16px;bottom:14px;opacity:.9;}

        /* ===== TRUST ===== */
        .trust{padding:40px 0 20px;text-align:center;}
        .trust-h{color:var(--muted);font-size:.92rem;margin-bottom:22px;}
        .logos{display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:38px 46px;}
        .logos .lg{font-family:var(--disp);font-weight:700;color:#aab0cf;opacity:.75;letter-spacing:1px;font-size:1.05rem;transition:.2s;filter:grayscale(1);}
        .logos .lg small{display:block;font-size:.55rem;letter-spacing:3px;font-weight:600;opacity:.8;}
        .logos .lg:hover{opacity:1;color:#fff;}

        /* ===== GENERIC SECTIONS (dark) ===== */
        .section{padding:80px 0;position:relative;}
        .sec-badge{display:inline-block;border:1px solid var(--line2);background:var(--panel);color:#c4b5fd;border-radius:999px;padding:6px 15px;font-size:.8rem;font-weight:600;margin-bottom:14px;}
        .sec-title{font-family:var(--disp);font-weight:800;font-size:clamp(1.8rem,3.4vw,2.6rem);color:#fff;letter-spacing:-1px;}
        .sec-sub{color:var(--muted);max-width:620px;margin:10px auto 0;}
        .glass{border:1px solid var(--line);background:var(--panel);border-radius:18px;transition:.25s;height:100%;}
        .glass:hover{transform:translateY(-5px);border-color:var(--line2);box-shadow:0 30px 60px -30px rgba(124,58,237,.4);}
        .feat-ic{width:54px;height:54px;border-radius:14px;display:grid;place-items:center;font-size:1.3rem;color:#fff;background:var(--grad);box-shadow:0 12px 26px -12px var(--v2);}
        .step-n{width:46px;height:46px;border-radius:13px;background:var(--grad);color:#fff;display:grid;place-items:center;font-weight:800;font-family:var(--disp);flex-shrink:0;box-shadow:0 12px 26px -12px var(--v2);}
        .price{border:1px solid var(--line);background:var(--panel);border-radius:20px;padding:28px;height:100%;transition:.25s;}
        .price:hover{transform:translateY(-6px);border-color:var(--line2);}
        .price.pop{border:1.5px solid var(--v2);background:linear-gradient(180deg,rgba(124,58,237,.14),var(--panel));box-shadow:0 30px 70px -30px rgba(124,58,237,.6);}
        .price .amt{font-family:var(--disp);font-size:2.3rem;font-weight:800;color:#fff;letter-spacing:-1px;}
        .price ul{list-style:none;padding:0;margin:18px 0 22px;}
        .price li{color:#c3c8e0;font-size:.92rem;margin-bottom:9px;display:flex;gap:9px;align-items:flex-start;}
        .price li i{color:#34d399;margin-top:3px;}
        .form-select.cs{background:var(--panel2);border:1px solid var(--line2);color:#fff;}
        .cta-band{background:var(--grad);border-radius:26px;padding:56px;text-align:center;box-shadow:0 40px 90px -34px rgba(124,58,237,.8);}
        .foot{border-top:1px solid var(--line);padding:52px 0 30px;margin-top:40px;}
        .foot a{color:#9aa1bd;font-size:.9rem;} .foot a:hover{color:#fff;}
        .to-top{position:fixed;right:22px;bottom:22px;width:48px;height:48px;border:0;border-radius:14px;background:var(--grad);color:#fff;box-shadow:0 14px 34px -10px rgba(124,58,237,.8);opacity:0;pointer-events:none;transition:.25s;z-index:80;}
        .to-top.show{opacity:1;pointer-events:auto;}

        @media(max-width:991px){
            .nav-links{display:none;}
            .dash{grid-template-columns:120px 1fr;}
            .metrics{grid-template-columns:repeat(2,1fr);}
            .hero{padding:34px 0 20px;}
        }
        @media(max-width:575px){
            .metrics{grid-template-columns:1fr;}
            .dstats{grid-template-columns:repeat(2,1fr);}
            .dpanels{grid-template-columns:1fr;}
        }
        @media (prefers-reduced-motion: reduce){*{animation:none!important;transition:none!important;}[data-aos]{opacity:1!important;transform:none!important;}}
    </style>
</head>
<body>
<div class="bg-fx"></div>
<div class="bg-dots"></div>

<!-- ===== NAV ===== -->
<nav class="nav-c" id="nav">
    <div class="container nav-in">
        <a href="{{ route('landing') }}" class="brand"><span class="pin"><i class="fas fa-location-dot"></i></span>check<span>inHub</span></a>
        <div class="nav-links">
            <a href="#features">{{ __('landing.nav_features') }}</a>
            <a href="#how">{{ __('landing.nav_how') }}</a>
            <a href="#pricing">{{ __('landing.nav_pricing') }}</a>
            <a href="{{ route('guide') }}">Ressources</a>
            <a href="#faq">FAQ</a>
        </div>
        <div class="nav-actions">
            <a href="{{ route('lang.switch', app()->getLocale() === 'fr' ? 'en' : 'fr') }}" class="pill">{{ strtoupper(app()->getLocale()) }} <i class="fas fa-chevron-down" style="font-size:.6rem;"></i></a>
            <button class="icon-btn" id="themeToggle" type="button" aria-label="Thème"><i class="fas fa-moon"></i></button>
            <a href="{{ route('login.index') }}" class="btn-ghost2">{{ __('landing.nav_login') }}</a>
            <a href="{{ route('hotel.register') }}" class="btn-grad"><i class="fas fa-rocket"></i> {{ __('landing.nav_free_trial') }}</a>
        </div>
    </div>
</nav>

<!-- ===== HERO ===== -->
<header class="hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="eyebrow" data-aos="fade-up">
                    <span class="tag"><i class="fas fa-sparkles"></i> Nouveau</span>
                    <span>🔥 <b>Essai gratuit 14 jours</b> sans carte bancaire</span>
                </div>
                <h1 class="h1" data-aos="fade-up" data-aos-delay="60">La gestion hôtelière,<br><span class="grad">réinventée</span> pour<br>l'Afrique.</h1>
                <p class="lead" data-aos="fade-up" data-aos-delay="120">Centralisez vos réservations, votre caisse, le housekeeping et votre site web sur une seule plateforme pensée pour les hôtels africains.</p>

                <div class="rating" data-aos="fade-up" data-aos-delay="160">
                    <span class="stars">★★★★★</span>
                    <span class="rtxt"><b>4,9/5</b> sur plus de 120 avis</span>
                </div>

                <div class="cta-row" data-aos="fade-up" data-aos-delay="200">
                    <a href="{{ route('hotel.register') }}" class="btn-lg-grad">
                        <i class="fas fa-rocket"></i>
                        <span>Essayer gratuitement<small>14 jours sans engagement</small></span>
                    </a>
                    <a href="{{ url('/h/cactus-hotel') }}" target="_blank" rel="noopener noreferrer" class="btn-lg-ghost">
                        <span class="pc"><i class="fas fa-play"></i></span>
                        <span>Voir une démo<small>En 2 minutes</small></span>
                    </a>
                </div>

                <div class="checks" data-aos="fade-up" data-aos-delay="240">
                    <span><i class="fas fa-check"></i> Sans engagement</span>
                    <span><i class="fas fa-bolt i2"></i> Installation en 5 min</span>
                    <span><i class="fab fa-whatsapp i3"></i> Support WhatsApp 24/7</span>
                </div>

                <div class="countries" data-aos="fade-up" data-aos-delay="280">
                    <div class="ct-h">Déjà adopté dans 12 pays africains</div>
                    <div class="flags">
                        <span class="flag-chip"><span class="fl">🇸🇳</span> Sénégal</span>
                        <span class="flag-chip"><span class="fl">🇨🇮</span> Côte d'Ivoire</span>
                        <span class="flag-chip"><span class="fl">🇧🇯</span> Bénin</span>
                        <span class="flag-chip"><span class="fl">🇹🇬</span> Togo</span>
                        <span class="flag-chip"><span class="fl">🇧🇫</span> Burkina Faso</span>
                    </div>
                </div>
            </div>

            <!-- Dashboard preview -->
            <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="150">
                <div class="dashwrap">
                    <div class="dash">
                        <aside class="dsb">
                            <div class="dsb-logo"><i class="fas fa-location-dot"></i> checkinHub</div>
                            <a class="on"><i class="fas fa-gauge-high"></i> Dashboard</a>
                            <a><i class="fas fa-calendar-check"></i> Réservations</a>
                            <a><i class="fas fa-calendar-days"></i> Calendrier</a>
                            <a><i class="fas fa-users"></i> Clients</a>
                            <a><i class="fas fa-cash-register"></i> Caisse</a>
                            <a><i class="fas fa-broom"></i> Housekeeping</a>
                            <a><i class="fas fa-chart-line"></i> Rapports</a>
                            <a><i class="fas fa-globe"></i> Site Web</a>
                            <a><i class="fas fa-gear"></i> Paramètres</a>
                        </aside>
                        <div class="dmn">
                            <div class="dtop">
                                <div class="dsearch"><i class="fas fa-magnifying-glass"></i> Rechercher une réservation, un client…</div>
                                <div class="dbell"><i class="fas fa-bell"></i><b>3</b></div>
                                <div class="duser"><span class="av">M</span><span class="nm">Marie K.<small>Réception</small></span></div>
                            </div>
                            <div class="dstats">
                                <div class="dstat"><div class="lb">Arrivées aujourd'hui</div><div class="vl">12</div><div class="up">↑ 12% vs hier</div></div>
                                <div class="dstat"><div class="lb">Départs</div><div class="vl">7</div><div class="up">↑ 8% vs hier</div></div>
                                <div class="dstat"><div class="lb">Clients</div><div class="vl">24</div><div class="up">↑ 15% vs hier</div></div>
                                <div class="dstat"><div class="lb">Chiffre d'affaires</div><div class="vl">2,45M <small>FCFA</small></div><div class="up">↑ 18% vs hier</div></div>
                            </div>
                            <div class="dpanels">
                                <div class="dcard">
                                    <div class="ttl">Évolution des réservations <span>7 derniers jours</span></div>
                                    <div class="spark">
                                        <svg viewBox="0 0 260 56" preserveAspectRatio="none">
                                            <defs><linearGradient id="ln" x1="0" y1="0" x2="0" y2="1">
                                                <stop offset="0" stop-color="#8b5cf6" stop-opacity=".45"/><stop offset="1" stop-color="#8b5cf6" stop-opacity="0"/></linearGradient></defs>
                                            <path d="M0,46 L36,40 L74,44 L112,30 L150,34 L188,18 L226,22 L260,8 L260,56 L0,56 Z" fill="url(#ln)"/>
                                            <path d="M0,46 L36,40 L74,44 L112,30 L150,34 L188,18 L226,22 L260,8" fill="none" stroke="#a855f7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="dcard">
                                    <div class="ttl">Répartition des chambres</div>
                                    <div class="donutwrap">
                                        <div class="donut"></div>
                                        <div class="dleg">
                                            <div><i style="background:#8b5cf6"></i>Occupées 65%</div>
                                            <div><i style="background:#22c55e"></i>Disponibles 25%</div>
                                            <div><i style="background:#6366f1"></i>Réservées 10%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dform">
                                <div class="ttl"><i class="fas fa-plus"></i> Nouvelle réservation</div>
                                <div class="row g-2">
                                    <div class="col-4"><label>Arrivée</label><div class="dfield">21/06 <i class="fas fa-calendar"></i></div></div>
                                    <div class="col-4"><label>Départ</label><div class="dfield">24/06 <i class="fas fa-calendar"></i></div></div>
                                    <div class="col-4"><label>Chambres</label><div class="dfield">2 <i class="fas fa-chevron-down"></i></div></div>
                                </div>
                                <div class="go">Rechercher</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- ===== METRICS ===== -->
<section class="container">
    <div class="metrics">
        @php
            $metrics = [
                ['fa-hotel','#a855f7','rgba(168,85,247,.15)',16,'Établissements actifs','#a855f7'],
                ['fa-globe-africa','#22c55e','rgba(34,197,94,.15)',12,'Pays disponibles','#22c55e'],
                ['fa-bed','#f59e0b','rgba(245,158,11,.15)',93,'Chambres gérées','#f59e0b'],
                ['fa-calendar-check','#6366f1','rgba(99,102,241,.15)',59,'Réservations traitées','#6366f1'],
            ];
        @endphp
        @foreach($metrics as $i => [$ic,$col,$bg,$val,$lbl,$sc])
            <div class="metric" data-aos="fade-up" data-aos-delay="{{ $i*90 }}">
                <div class="mi" style="background:{{ $bg }};color:{{ $col }};"><i class="fas {{ $ic }}"></i></div>
                <div class="mv"><span class="counter" data-target="{{ $val }}">0</span></div>
                <div class="ml">{{ $lbl }}</div>
                <svg class="ms" width="72" height="34" viewBox="0 0 72 34" fill="none">
                    <path d="M2,28 L14,22 L26,25 L38,14 L50,17 L62,6 L70,10" stroke="{{ $sc }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" opacity=".9"/>
                </svg>
            </div>
        @endforeach
    </div>
</section>

<!-- ===== TRUST ===== -->
<section class="trust">
    <div class="container">
        <div class="trust-h">Ils nous font confiance</div>
        <div class="logos">
            <span class="lg">AZALAÏ<small>HOTELS</small></span>
            <span class="lg">ONOMO<small>HOTELS</small></span>
            <span class="lg">NOOM<small>HÔTEL</small></span>
            <span class="lg">SAFARI<small>LODGE</small></span>
            <span class="lg">HÔTEL<small>KIRIKOU</small></span>
            <span class="lg" style="font-style:italic;">La Résidence</span>
            <span class="lg">MABONZO<small>HÔTEL</small></span>
            <span class="lg" style="filter:none;color:var(--muted);">+ 50 autres</span>
        </div>
    </div>
</section>

<!-- ===== FEATURES ===== -->
<section class="section" id="features">
    <div class="container">
        <div class="text-center mb-5">
            <span class="sec-badge">{{ __('landing.features_badge') }}</span>
            <h2 class="sec-title">{{ __('landing.features_title') }}</h2>
            <p class="sec-sub">{{ __('landing.features_description') }}</p>
        </div>
        <div class="row g-4">
            @php
                $features = [
                    ['fa-building', __('landing.feature_1_title'), __('landing.feature_1_desc')],
                    ['fa-calendar-check', __('landing.feature_2_title'), __('landing.feature_2_desc')],
                    ['fa-cash-register', __('landing.feature_3_title'), __('landing.feature_3_desc')],
                    ['fa-utensils', __('landing.feature_4_title'), __('landing.feature_4_desc')],
                    ['fa-broom', __('landing.feature_5_title'), __('landing.feature_5_desc')],
                    ['fa-chart-line', __('landing.feature_6_title'), __('landing.feature_6_desc')],
                ];
            @endphp
            @foreach ($features as $i => [$icon, $title, $desc])
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 100 }}">
                    <div class="glass p-4">
                        <div class="feat-ic mb-3"><i class="fas {{ $icon }}"></i></div>
                        <h5 class="fw-bold" style="color:#fff;">{{ $title }}</h5>
                        <p style="color:var(--muted);" class="mb-0">{{ $desc }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===== HOW ===== -->
<section class="section" id="how">
    <div class="container">
        <div class="text-center mb-5">
            <span class="sec-badge">{{ __('landing.how_badge') }}</span>
            <h2 class="sec-title">{{ __('landing.how_title') }}</h2>
        </div>
        <div class="row g-4">
            @php
                $steps = [
                    [__('landing.how_step_1_title'), __('landing.how_step_1_desc')],
                    [__('landing.how_step_2_title'), __('landing.how_step_2_desc')],
                    [__('landing.how_step_3_title'), __('landing.how_step_3_desc')],
                ];
            @endphp
            @foreach ($steps as $i => [$title, $desc])
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $i * 130 }}">
                    <div class="glass p-4 d-flex align-items-start gap-3">
                        <div class="step-n">{{ $i + 1 }}</div>
                        <div>
                            <h5 class="fw-bold mb-1" style="color:#fff;">{{ $title }}</h5>
                            <p class="mb-0" style="color:var(--muted);">{{ $desc }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===== PRICING ===== -->
<section class="section" id="pricing">
    <div class="container">
        <div class="text-center mb-4">
            <span class="sec-badge">{{ __('landing.pricing_badge') }}</span>
            <h2 class="sec-title">{{ __('landing.pricing_title') }}</h2>
            <p class="sec-sub">{{ __('landing.pricing_description') }}</p>
            <div class="d-inline-flex align-items-center gap-2 mt-3">
                <i class="fas fa-earth-africa" style="color:var(--v3);"></i>
                <select id="pricing-country" class="form-select cs" style="width:auto;">
                    @foreach (config('plans.countries') as $code => $c)
                        <option value="{{ $code }}" {{ $code === config('plans.default_country') ? 'selected' : '' }}>{{ $c['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row g-4 justify-content-center">
            @foreach (config('plans.tiers') as $key => $tier)
                @php
                    $popular = ! empty($tier['popular']);
                    $taglines = ['starter' => __('flash.plan_starter_tagline'), 'pro' => __('flash.plan_pro_tagline'), 'business' => __('flash.plan_business_tagline')];
                    $feats = ['starter' => [__('flash.plan_starter_f1'), __('flash.plan_starter_f2'), __('flash.plan_starter_f3'), __('flash.plan_starter_f4')], 'pro' => [__('flash.plan_pro_f1'), __('flash.plan_pro_f2'), __('flash.plan_pro_f3'), __('flash.plan_pro_f4')], 'business' => [__('flash.plan_business_f1'), __('flash.plan_business_f2'), __('flash.plan_business_f3'), __('flash.plan_business_f4')]];
                @endphp
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 110 }}">
                    <div class="price {{ $popular ? 'pop' : '' }}" data-base="{{ $tier['price'] }}">
                        @if ($popular)<span class="sec-badge" style="color:#fff;background:var(--grad);border:0;">{{ __('flash.plan_popular') }}</span>@endif
                        <h4 class="fw-bold mt-1" style="color:#fff;">{{ $tier['name'] }}</h4>
                        <p style="color:var(--muted);font-size:.9rem;">{{ $taglines[$key] ?? ($tier['tagline'] ?? '') }}</p>
                        <div class="amt">
                            <span class="pr-amount">{{ number_format($tier['price'], 0, ',', ' ') }}</span>
                            <span style="font-size:.9rem;color:var(--muted);font-weight:400;"><span class="pr-cur">XOF</span> {{ __('flash.plan_per_month') }}</span>
                        </div>
                        <ul>
                            @foreach (($feats[$key] ?? $tier['features']) as $item)
                                <li><i class="fas fa-check"></i>{{ $item }}</li>
                            @endforeach
                        </ul>
                        <a href="{{ route('hotel.register', ['plan' => $key]) }}" class="{{ $popular ? 'btn-lg-grad' : 'btn-lg-ghost' }} w-100" style="justify-content:center;padding:12px;">
                            {{ __('landing.pricing_choose') }} {{ $tier['name'] }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===== TESTIMONIALS ===== -->
<section class="section">
    <div class="container">
        <div class="text-center mb-5">
            <span class="sec-badge">{{ __('landing.testimonials_badge') }}</span>
            <h2 class="sec-title">{{ __('landing.testimonials_title') }}</h2>
        </div>
        @php
            $testimonials = [
                [__('landing.testimonial_1_name'), __('landing.testimonial_1_role'), __('landing.testimonial_1_quote')],
                [__('landing.testimonial_2_name'), __('landing.testimonial_2_role'), __('landing.testimonial_2_quote')],
                [__('landing.testimonial_3_name'), __('landing.testimonial_3_role'), __('landing.testimonial_3_quote')],
            ];
        @endphp
        <div class="row g-4">
            @foreach ($testimonials as $i => [$name, $role, $quote])
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="{{ $i * 110 }}">
                    <div class="glass p-4 h-100">
                        <div style="color:#fbbf24;letter-spacing:2px;" class="mb-2">★★★★★</div>
                        <p class="mb-3" style="color:#d7dbf0;">“{{ $quote }}”</p>
                        <div class="d-flex align-items-center gap-2">
                            <div class="step-n" style="width:40px;height:40px;border-radius:50%;font-size:.9rem;">{{ substr($name, 0, 1) }}</div>
                            <div>
                                <div class="fw-semibold" style="color:#fff;">{{ $name }}</div>
                                <div class="small" style="color:var(--muted);">{{ $role }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===== CTA ===== -->
<section class="pb-5">
    <div class="container">
        <div class="cta-band" data-aos="zoom-in">
            <h2 class="fw-bold mb-2" style="color:#fff;font-family:var(--disp);">{{ __('landing.cta_title') }}</h2>
            <p class="mb-4" style="color:rgba(255,255,255,.85);">{{ __('landing.cta_description') }} {{ config('app.name', 'checkinHub') }}.</p>
            <a href="{{ route('hotel.register') }}" class="btn-lg-ghost" style="background:#fff;color:#4c1d95;border:0;font-weight:800;">
                <i class="fas fa-rocket"></i> {{ __('landing.cta_button') }}
            </a>
        </div>
    </div>
</section>

<!-- ===== FOOTER ===== -->
<footer class="foot">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <a href="{{ route('landing') }}" class="brand mb-3"><span class="pin"><i class="fas fa-location-dot"></i></span>check<span>inHub</span></a>
                <p style="color:var(--muted);font-size:.9rem;" class="mt-2">{{ __('landing.footer_description') }}</p>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="fw-semibold mb-3" style="color:#fff;">{{ __('landing.footer_product') }}</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#features">{{ __('landing.footer_features') }}</a></li>
                    <li class="mb-2"><a href="#pricing">{{ __('landing.footer_pricing') }}</a></li>
                    <li class="mb-2"><a href="{{ route('hotel.register') }}">{{ __('landing.footer_free_trial') }}</a></li>
                    <li class="mb-2"><a href="{{ url('/h/cactus-hotel') }}" target="_blank" rel="noopener noreferrer">{{ __('landing.footer_demo') }}</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="fw-semibold mb-3" style="color:#fff;">{{ __('landing.footer_account') }}</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('login.index') }}">{{ __('landing.footer_login') }}</a></li>
                    <li class="mb-2"><a href="{{ route('guide') }}">Ressources</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="fw-semibold mb-3" style="color:#fff;">{{ __('landing.footer_contact') }}</h6>
                <p style="color:var(--muted);font-size:.9rem;" class="mb-1"><i class="fas fa-envelope me-2"></i>contact@checkinhub.com</p>
                <p style="color:var(--muted);font-size:.9rem;"><i class="fab fa-whatsapp me-2"></i>+229 00 00 00 00</p>
            </div>
        </div>
        <hr style="border-color:var(--line);">
        <div class="text-center" style="color:var(--muted);font-size:.85rem;">
            &copy; {{ date('Y') }} {{ config('app.name', 'checkinHub') }}. {{ __('landing.footer_rights') }}
        </div>
    </div>
</footer>

<button id="scrollTop" class="to-top" aria-label="{{ __('landing.scroll_to_top') }}"><i class="fas fa-arrow-up"></i></button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    if (window.AOS) AOS.init({ duration: 650, once: true, easing: 'ease-out-cubic', offset: 70 });

    const nav = document.getElementById('nav');
    const topBtn = document.getElementById('scrollTop');
    const onScroll = () => {
        nav.classList.toggle('scrolled', window.scrollY > 30);
        topBtn.classList.toggle('show', window.scrollY > 400);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
    topBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    // Compteurs animés
    const counters = document.querySelectorAll('.counter');
    const run = (el) => {
        const target = +el.dataset.target, dur = 1300, s = performance.now();
        const tick = (n) => { const p = Math.min((n - s) / dur, 1); el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3))); if (p < 1) requestAnimationFrame(tick); };
        requestAnimationFrame(tick);
    };
    if ('IntersectionObserver' in window) {
        const ob = new IntersectionObserver((es) => es.forEach(e => { if (e.isIntersecting) { run(e.target); ob.unobserve(e.target); } }), { threshold: .5 });
        counters.forEach(c => ob.observe(c));
    } else counters.forEach(c => c.textContent = c.dataset.target);

    // Prix ajustés selon le pays
    const plansCountries = @json(config('plans.countries'));
    const sel = document.getElementById('pricing-country');
    if (sel) {
        const fmt = n => n.toLocaleString('fr-FR');
        const upd = () => {
            const c = plansCountries[sel.value]; if (!c) return;
            document.querySelectorAll('.price[data-base]').forEach(card => {
                const base = +card.dataset.base;
                const price = Math.round(base * c.coef / c.round) * c.round;
                const a = card.querySelector('.pr-amount'), cur = card.querySelector('.pr-cur');
                if (a) a.textContent = fmt(price); if (cur) cur.textContent = c.currency;
            });
        };
        sel.addEventListener('change', upd); upd();
    }

    // Bascule thème (clair) optionnelle
    const tgl = document.getElementById('themeToggle');
    if (tgl) tgl.addEventListener('click', () => {
        document.body.classList.toggle('theme-light');
        tgl.querySelector('i').className = document.body.classList.contains('theme-light') ? 'fas fa-sun' : 'fas fa-moon';
    });
</script>
</body>
</html>
