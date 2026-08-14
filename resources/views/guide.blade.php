<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('guide.page_title', ['app' => config('app.name', 'checkinHub')]) }}</title>
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
    <a href="{{ route('lang.switch', app()->getLocale() === 'fr' ? 'en' : 'fr') }}" class="btn-ghost" style="padding:.45rem .8rem;font-size:.85rem;">{{ __('landing.nav_switch_lang') }}</a>
    <a href="{{ route('landing') }}" class="btn btn-ghost"><i class="fas fa-arrow-left me-1"></i> {{ __('guide.nav_site') }}</a>
    @auth
        <a href="{{ url('/home') }}" class="btn btn-glow"><i class="fas fa-gauge-high me-1"></i> {{ __('guide.nav_trial') }}</a>
    @else
        <a href="{{ route('login.index') }}" class="btn btn-ghost">{{ __('guide.nav_login') }}</a>
        <a href="{{ route('hotel.register') }}" class="btn btn-glow">{{ __('guide.nav_trial') }}</a>
    @endauth
</nav>

@php
    $sections = [
        ['start','fa-flag-checkered',__('guide.sec_start'),__('guide.sec_start_sub'),__('guide.sec_start_desc')],
        ['process','fa-diagram-project',__('guide.sec_process'),__('guide.sec_process_sub'),__('guide.sec_process_desc')],
        ['brand','fa-palette',__('guide.sec_brand'),__('guide.sec_brand_sub'),__('guide.sec_brand_desc')],
        ['rooms','fa-bed',__('guide.sec_rooms'),__('guide.sec_rooms_sub'),__('guide.sec_rooms_desc')],
        ['bookings','fa-calendar-check',__('guide.sec_bookings'),__('guide.sec_bookings_sub'),__('guide.sec_bookings_desc')],
        ['cashier','fa-cash-register',__('guide.sec_cashier'),__('guide.sec_cashier_sub'),__('guide.sec_cashier_desc')],
        ['housekeeping','fa-broom',__('guide.sec_housekeeping'),__('guide.sec_housekeeping_sub'),__('guide.sec_housekeeping_desc')],
        ['restaurant','fa-utensils',__('guide.sec_restaurant'),__('guide.sec_restaurant_sub'),__('guide.sec_restaurant_desc')],
        ['site','fa-globe',__('guide.sec_site'),__('guide.sec_site_sub'),__('guide.sec_site_desc')],
        ['reports','fa-chart-line',__('guide.sec_reports'),__('guide.sec_reports_sub'),__('guide.sec_reports_desc')],
        ['staff','fa-user-tie',__('guide.sec_staff'),__('guide.sec_staff_sub'),__('guide.sec_staff_desc')],
        ['billing','fa-credit-card',__('guide.sec_billing'),__('guide.sec_billing_sub'),__('guide.sec_billing_desc')],
    ];
@endphp

<!-- SIDEBAR (gauche) -->
<aside class="gside">
    <div class="t-search">
        <i class="fas fa-magnifying-glass"></i>
        <input type="text" id="guideSearch" placeholder="{{ __('guide.search_placeholder') }}" autocomplete="off">
    </div>
    <div class="t-title">{{ __('guide.toc_title') }}</div>
    @foreach ($sections as $s)
        <a href="#{{ $s[0] }}" class="toc-link" data-text="{{ \Illuminate\Support\Str::lower($s[2].' '.$s[3].' '.$s[4]) }}">
            <i class="fas {{ $s[1] }}"></i> {{ $s[2] }}
        </a>
    @endforeach
    <a href="#support" class="toc-link" data-text="support aide contact whatsapp"><i class="fas fa-headset"></i> {{ __('guide.support_link') }}</a>
    <div class="t-empty" id="tocEmpty">{{ __('guide.toc_empty') }}</div>
</aside>

<!-- CONTENT -->
<main class="gcontent">
        <div class="hero">
            <span class="chip"><i class="fas fa-book-open" style="color:var(--accent)"></i> {{ __('guide.hero_chip') }}</span>
            <h1>{{ __('guide.hero_title', ['app' => config('app.name', 'checkinHub')]) }}</h1>
            <p>{{ __('guide.hero_desc') }}</p>
        </div>

        <!-- Premiers pas (détaillé avec étapes) -->
        <section class="doc" id="start">
            <h2><span class="sico"><i class="fas fa-flag-checkered"></i></span> {{ __('guide.sec_start') }}</h2>
            <p>{!! __('guide.start_intro') !!}</p>
            <ol class="steps">
                <li>{!! __('guide.start_step1') !!}</li>
                <li>{!! __('guide.start_step2') !!}</li>
                <li>{!! __('guide.start_step3') !!}</li>
                <li>{!! __('guide.start_step4') !!}</li>
            </ol>
            <div class="tip"><i class="fas fa-lightbulb"></i><div>{!! __('guide.start_tip') !!}</div></div>
        </section>

        <!-- Processus métier (détaillé) -->
        <section class="doc" id="process">
            <h2><span class="sico"><i class="fas fa-diagram-project"></i></span> {{ __('guide.sec_process') }}</h2>
            <p>{{ __('guide.sec_process_desc') }}</p>
            <ol class="steps">
                <li>{!! __('guide.process_step1') !!}</li>
                <li>{!! __('guide.process_step2') !!}</li>
                <li>{!! __('guide.process_step3') !!}</li>
                <li>{!! __('guide.process_step4') !!}</li>
                <li>{!! __('guide.process_step5') !!}</li>
                <li>{!! __('guide.process_step6') !!}</li>
                <li>{!! __('guide.process_step7') !!}</li>
            </ol>
            <div class="tip"><i class="fas fa-lightbulb"></i><div>{!! __('guide.process_tip') !!}</div></div>
        </section>

        <!-- Rooms -->
        <section class="doc" id="rooms">
            <h2><span class="sico"><i class="fas fa-bed"></i></span> {{ __('guide.sec_rooms') }}</h2>
            <p>{!! __('guide.rooms_intro') !!}</p>
            <ol class="steps">
                <li>{!! __('guide.rooms_step1') !!}</li>
                <li>{!! __('guide.rooms_step2') !!}</li>
                <li>{!! __('guide.rooms_step3') !!}</li>
            </ol>
            <div class="tip"><i class="fas fa-lightbulb"></i><div>{!! __('guide.rooms_tip') !!}</div></div>
        </section>

        <!-- Bookings -->
        <section class="doc" id="bookings">
            <h2><span class="sico"><i class="fas fa-calendar-check"></i></span> {{ __('guide.sec_bookings') }}</h2>
            <p>{{ __('guide.sec_bookings_desc') }}</p>
            <ol class="steps">
                <li>{!! __('guide.bookings_step1') !!}</li>
                <li>{!! __('guide.bookings_step2') !!}</li>
                <li>{!! __('guide.bookings_step3') !!}</li>
                <li>{!! __('guide.bookings_step4') !!}</li>
                <li>{!! __('guide.bookings_step5') !!}</li>
            </ol>
            <div class="tip"><i class="fas fa-lightbulb"></i><div>{!! __('guide.bookings_tip') !!}</div></div>
        </section>

        <!-- Cashier -->
        <section class="doc" id="cashier">
            <h2><span class="sico"><i class="fas fa-cash-register"></i></span> {{ __('guide.sec_cashier') }}</h2>
            <p>{{ __('guide.sec_cashier_desc') }}</p>
            <ol class="steps">
                <li>{!! __('guide.cashier_step1') !!}</li>
                <li>{!! __('guide.cashier_step2') !!}</li>
                <li>{!! __('guide.cashier_step3') !!}</li>
                <li>{!! __('guide.cashier_step4') !!}</li>
            </ol>
            <div class="tip"><i class="fas fa-lightbulb"></i><div>{!! __('guide.cashier_tip') !!}</div></div>
        </section>

        <!-- Housekeeping -->
        <section class="doc" id="housekeeping">
            <h2><span class="sico"><i class="fas fa-broom"></i></span> {{ __('guide.sec_housekeeping') }}</h2>
            <p>{!! __('guide.sec_housekeeping_desc') !!}</p>
            <ol class="steps">
                <li>{!! __('guide.housekeeping_step1') !!}</li>
                <li>{!! __('guide.housekeeping_step2') !!}</li>
                <li>{!! __('guide.housekeeping_step3') !!}</li>
                <li>{!! __('guide.housekeeping_step4') !!}</li>
            </ol>
            <div class="tip"><i class="fas fa-lightbulb"></i><div>{!! __('guide.housekeeping_tip') !!}</div></div>
        </section>

        <!-- Restaurant -->
        <section class="doc" id="restaurant">
            <h2><span class="sico"><i class="fas fa-utensils"></i></span> {{ __('guide.sec_restaurant') }}</h2>
            <p>{!! __('guide.sec_restaurant_desc') !!}</p>
            <ol class="steps">
                <li>{!! __('guide.restaurant_step1') !!}</li>
                <li>{!! __('guide.restaurant_step2') !!}</li>
                <li>{!! __('guide.restaurant_step3') !!}</li>
                <li>{!! __('guide.restaurant_step4') !!}</li>
            </ol>
            <div class="tip"><i class="fas fa-lightbulb"></i><div>{!! __('guide.restaurant_tip') !!}</div></div>
        </section>

        <!-- Site -->
        <section class="doc" id="site">
            <h2><span class="sico"><i class="fas fa-globe"></i></span> {{ __('guide.sec_site') }}</h2>
            <p>{{ __('guide.sec_site_desc') }}</p>
            <ol class="steps">
                <li>{!! __('guide.site_step1') !!}</li>
                <li>{!! __('guide.site_step2') !!}</li>
                <li>{!! __('guide.site_step3') !!}</li>
                <li>{!! __('guide.site_step4') !!}</li>
            </ol>
            <div class="tip"><i class="fas fa-lightbulb"></i><div>{!! __('guide.site_tip') !!}</div></div>
        </section>

        <!-- Reports -->
        <section class="doc" id="reports">
            <h2><span class="sico"><i class="fas fa-chart-line"></i></span> {{ __('guide.sec_reports') }}</h2>
            <p>{!! __('guide.sec_reports_desc') !!}</p>
            <ol class="steps">
                <li>{!! __('guide.reports_step1') !!}</li>
                <li>{!! __('guide.reports_step2') !!}</li>
                <li>{!! __('guide.reports_step3') !!}</li>
                <li>{!! __('guide.reports_step4') !!}</li>
            </ol>
            <div class="tip"><i class="fas fa-lightbulb"></i><div>{!! __('guide.reports_tip') !!}</div></div>
        </section>

        <!-- Staff -->
        <section class="doc" id="staff">
            <h2><span class="sico"><i class="fas fa-user-tie"></i></span> {{ __('guide.sec_staff') }}</h2>
            <p><strong style="color:var(--head)">{{ __('guide.sec_staff_sub') }}.</strong> {{ __('guide.sec_staff_desc') }}</p>
        </section>

        <!-- Billing -->
        <section class="doc" id="billing">
            <h2><span class="sico"><i class="fas fa-credit-card"></i></span> {{ __('guide.sec_billing') }}</h2>
            <p><strong style="color:var(--head)">{{ __('guide.sec_billing_sub') }}.</strong> {{ __('guide.sec_billing_desc') }}</p>
        </section>

        <!-- Support -->
        <section class="doc" id="support">
            <h2><span class="sico"><i class="fas fa-headset"></i></span> {{ __('guide.support_title') }}</h2>
            <p>{{ __('guide.support_desc') }}</p>
            <div class="note"><i class="fab fa-whatsapp" style="color:#25d366"></i><div>{!! __('guide.support_whatsapp') !!}</div></div>
            <div class="cta-final">
                <h3 style="margin:0 0 8px;">{{ __('guide.cta_title') }}</h3>
                <p style="color:var(--muted);margin:0 0 18px;">{{ __('guide.cta_desc', ['days' => config('plans.trial_days', 14)]) }}</p>
                <a href="{{ route('hotel.register') }}" class="btn btn-glow"><i class="fas fa-rocket me-1"></i> {{ __('guide.cta_button') }}</a>
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
