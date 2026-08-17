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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand: #4f46e5;
            --brand-dark: #4338ca;
            --ink: #0f172a;
        }
        * { font-family: 'Inter', system-ui, sans-serif; }
        body { color: var(--ink); }
        .navbar-brand { font-weight: 800; letter-spacing: -.5px; }
        .text-brand { color: var(--brand) !important; }
        .btn-brand { background: var(--brand); border-color: var(--brand); color: #fff; }
        .btn-brand:hover { background: var(--brand-dark); border-color: var(--brand-dark); color: #fff; }
        .btn-outline-brand { color: var(--brand); border-color: var(--brand); }
        .btn-outline-brand:hover { background: var(--brand); color: #fff; }

        .hero {
            background: radial-gradient(1200px 500px at 70% -10%, #e0e7ff 0%, rgba(224,231,255,0) 60%),
                        linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            padding: 6rem 0 5rem;
        }
        .hero h1 { font-weight: 800; font-size: clamp(2.2rem, 5vw, 3.6rem); line-height: 1.05; letter-spacing: -1.5px; }
        .badge-soft { background: #eef2ff; color: var(--brand); font-weight: 600; padding: .5rem 1rem; border-radius: 999px; }

        .hero-mock {
            border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 30px 60px -20px rgba(79,70,229,.35);
            overflow: hidden; background: var(--white, #fff);
        }
        .hero-mock .bar { height: 36px; background: #f1f5f9; display: flex; align-items: center; gap: 6px; padding: 0 12px; }
        .hero-mock .dot { width: 10px; height: 10px; border-radius: 50%; background: #cbd5e1; }

        .feature-icon {
            width: 52px; height: 52px; border-radius: 12px; display: grid; place-items: center;
            background: #eef2ff; color: var(--brand); font-size: 1.3rem;
        }
        .card-feature { border: 1px solid #eef0f4; border-radius: 16px; transition: .2s; height: 100%; }
        .card-feature:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -24px rgba(15,23,42,.25); }

        .step-num { width: 44px; height: 44px; border-radius: 50%; background: var(--brand); color: #fff; display: grid; place-items: center; font-weight: 700; }

        .price-card { border: 1px solid #e8eaf0; border-radius: 18px; height: 100%; transition: transform .25s, box-shadow .25s, border-color .25s; }
        .price-card:hover { transform: translateY(-8px); box-shadow: 0 30px 55px -30px rgba(15,23,42,.4); border-color: #c7d2fe; }
        .price-card.popular { border: 2px solid var(--brand); box-shadow: 0 24px 50px -28px rgba(79,70,229,.5); transform: scale(1.04); animation: pulseGlow 3s infinite; }
        .price-card.popular:hover { transform: scale(1.04) translateY(-8px); }
        .price-amount { font-size: 2.4rem; font-weight: 800; letter-spacing: -1px; }

        .cta-band { background: linear-gradient(120deg, var(--brand) 0%, #7c3aed 100%); color: #fff; border-radius: 24px; background-size: 200% 200%; animation: gradientShift 8s ease infinite; }
        footer a { color: #cbd5e1; text-decoration: none; }
        footer a:hover { color: #fff; }
        .section { padding: 5rem 0; }

        /* ===== Animations ===== */
        @keyframes gradientShift { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
        @keyframes float { 0%,100%{ transform: translateY(0) } 50%{ transform: translateY(-18px) } }
        @keyframes floatSlow { 0%,100%{ transform: translateY(0) rotate(0) } 50%{ transform: translateY(-26px) rotate(8deg) } }
        @keyframes pulseGlow { 0%,100%{ box-shadow: 0 0 0 0 rgba(79,70,229,.45) } 50%{ box-shadow: 0 0 0 16px rgba(79,70,229,0) } }
        @keyframes fadeUp { from{ opacity:0; transform: translateY(24px) } to{ opacity:1; transform: none } }
        @keyframes shimmer { 0%{ background-position: -400px 0 } 100%{ background-position: 400px 0 } }
        @keyframes marquee { from{ transform: translateX(0) } to{ transform: translateX(-50%) } }
        @keyframes blob { 0%,100%{ border-radius: 42% 58% 63% 37% / 42% 45% 55% 58% } 50%{ border-radius: 58% 42% 38% 62% / 60% 38% 62% 40% } }

        /* Navbar dynamique au scroll */
        .navbar.scrolled { box-shadow: 0 8px 30px -18px rgba(15,23,42,.35); backdrop-filter: blur(8px); background: var(--white, #fff) !important; }
        .navbar { transition: box-shadow .25s, background .25s; }

        /* Blobs décoratifs */
        .blob { position: absolute; filter: blur(14px); opacity: .5; animation: blob 14s ease-in-out infinite, floatSlow 12s ease-in-out infinite; z-index: 0; }

        /* Boutons animés */
        .btn-brand { transition: transform .15s, box-shadow .2s, background .2s; }
        .btn-brand:hover { transform: translateY(-2px); }
        .btn-pulse { animation: pulseGlow 2.4s infinite; }

        /* Cartes feature : hover plus riche */
        .card-feature:hover .feature-icon { transform: scale(1.12) rotate(-6deg); }
        .feature-icon { transition: transform .25s; }

        /* Réduction d'animation si l'utilisateur le demande */
        @media (prefers-reduced-motion: reduce) {
            * { animation: none !important; transition: none !important; }
            [data-aos] { opacity: 1 !important; transform: none !important; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('landing') }}">
            <i class="fas fa-hotel text-brand me-1"></i> {{ config('app.name', 'checkinHub') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav mx-auto gap-lg-3">
                <li class="nav-item"><a class="nav-link" href="#features">{{ __('landing.nav_features') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="#how">{{ __('landing.nav_how') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="#pricing">{{ __('landing.nav_pricing') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/h/cactus-hotel') }}" target="_blank" rel="noopener noreferrer">{{ __('landing.nav_demo') }}</a></li>
            </ul>
            <div class="d-flex gap-2 align-items-center">
                <a href="{{ route('lang.switch', app()->getLocale() === 'fr' ? 'en' : 'fr') }}" class="btn btn-outline-secondary btn-sm fw-semibold">{{ __('landing.nav_switch_lang') }}</a>
                <a href="{{ route('login.index') }}" class="btn btn-outline-brand">{{ __('landing.nav_login') }}</a>
                <a href="{{ route('hotel.register') }}" class="btn btn-brand">{{ __('landing.nav_free_trial') }}</a>
            </div>
        </div>
    </div>
</nav>

<!-- HERO -->
<header class="hero position-relative overflow-hidden">
    <!-- Blobs décoratifs animés -->
    <div class="blob" style="width:280px;height:280px;background:#c7d2fe;top:-60px;left:-40px;"></div>
    <div class="blob" style="width:220px;height:220px;background:#ddd6fe;bottom:-40px;right:8%;animation-delay:-4s;"></div>
    <div class="blob" style="width:120px;height:120px;background:#a5b4fc;top:40%;right:38%;animation-delay:-8s;"></div>

    <div class="container position-relative" style="z-index:1;">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge-soft mb-3 d-inline-block" data-aos="fade-up"><i class="fas fa-bolt me-1"></i> {{ __('landing.hero_badge') }}</span>
                <h1 class="mb-3" data-aos="fade-up" data-aos-delay="80">{{ __('landing.hero_title_1') }}<br><span class="text-brand">{{ __('landing.hero_title_2') }}</span></h1>
                <p class="fs-5 text-secondary mb-4" data-aos="fade-up" data-aos-delay="160">
                    {{ __('landing.hero_description') }}
                </p>
                <div class="d-flex flex-wrap gap-2 mb-4" data-aos="fade-up" data-aos-delay="240">
                    <a href="{{ route('hotel.register') }}" class="btn btn-brand btn-lg px-4 btn-pulse"><i class="fas fa-rocket me-2"></i>{{ __('landing.hero_cta_start') }}</a>
                    <a href="{{ url('/h/cactus-hotel') }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-brand btn-lg px-4"><i class="fas fa-play me-2"></i>{{ __('landing.hero_cta_demo') }}</a>
                </div>
                <div class="d-flex flex-wrap gap-4 text-secondary small" data-aos="fade-up" data-aos-delay="320">
                    <span><i class="fas fa-check text-success me-1"></i> {{ __('landing.hero_check_1') }}</span>
                    <span><i class="fas fa-check text-success me-1"></i> {{ __('landing.hero_check_2') }}</span>
                    <span><i class="fas fa-check text-success me-1"></i> {{ __('landing.hero_check_3') }}</span>
                </div>
            </div>
            <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="200">
                <div class="hero-mock" style="animation: float 6s ease-in-out infinite;">
                    <div class="bar"><span class="dot"></span><span class="dot"></span><span class="dot"></span></div>
                    <div class="p-3">
                        <div class="row g-3">
                            <div class="col-4"><div class="p-3 rounded-3 bg-light text-center"><div class="fs-4 fw-bold text-brand">128</div><div class="small text-secondary">{{ __('landing.mock_rooms') }}</div></div></div>
                            <div class="col-4"><div class="p-3 rounded-3 bg-light text-center"><div class="fs-4 fw-bold text-brand">94%</div><div class="small text-secondary">{{ __('landing.mock_occupancy') }}</div></div></div>
                            <div class="col-4"><div class="p-3 rounded-3 bg-light text-center"><div class="fs-4 fw-bold text-brand">3</div><div class="small text-secondary">{{ __('landing.mock_hotels') }}</div></div></div>
                            <div class="col-12">
                                <div class="p-3 rounded-3 border">
                                    <div class="d-flex justify-content-between mb-2"><span class="fw-semibold">{{ __('landing.mock_daily_revenue') }}</span><span class="text-success fw-bold">+ 1 240 000 CFA</span></div>
                                    <div class="progress" style="height:8px"><div class="progress-bar" style="width:72%;background:var(--brand)"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- STATS animées -->
<section class="py-5 border-bottom">
    <div class="container">
        <div class="row text-center g-4">
            @php
                $stats = [
                    ['value' => 320, 'suffix' => '+', 'label' => __('landing.stat_rooms')],
                    ['value' => 18,  'suffix' => '', 'label' => __('landing.stat_hotels')],
                    ['value' => 99,  'suffix' => '%', 'label' => __('landing.stat_availability')],
                    ['value' => 24,  'suffix' => '/7', 'label' => __('landing.stat_support')],
                ];
            @endphp
            @foreach ($stats as $i => $s)
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                    <div class="display-5 fw-bold text-brand">
                        <span class="counter" data-target="{{ $s['value'] }}">0</span>{{ $s['suffix'] }}
                    </div>
                    <div class="text-secondary">{{ $s['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- BARRE DE CONFIANCE (marquee) -->
<div class="py-4 bg-light overflow-hidden">
    <div style="display:flex; width:max-content; animation: marquee 22s linear infinite; gap:3rem;">
        @for ($k = 0; $k < 2; $k++)
            @foreach (['landing.marquee_reception', 'landing.marquee_cashier', 'landing.marquee_restaurant', 'landing.marquee_housekeeping', 'landing.marquee_reservations', 'landing.marquee_reports', 'landing.marquee_multi_hotels', 'landing.marquee_checkin_express'] as $key)
                <span class="text-secondary fw-semibold text-uppercase" style="letter-spacing:1px;"><i class="fas fa-circle-check text-brand me-2"></i>{{ __($key) }}</span>
            @endforeach
        @endfor
    </div>
</div>

<!-- FEATURES -->
<section class="section" id="features">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge-soft mb-2 d-inline-block">{{ __('landing.features_badge') }}</span>
            <h2 class="fw-bold">{{ __('landing.features_title') }}</h2>
            <p class="text-secondary">{{ __('landing.features_description') }}</p>
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
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 120 }}">
                    <div class="card-feature p-4">
                        <div class="feature-icon mb-3"><i class="fas {{ $icon }}"></i></div>
                        <h5 class="fw-bold">{{ $title }}</h5>
                        <p class="text-secondary mb-0">{{ $desc }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="section bg-light" id="how">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge-soft mb-2 d-inline-block">{{ __('landing.how_badge') }}</span>
            <h2 class="fw-bold">{{ __('landing.how_title') }}</h2>
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
                <div class="col-md-4 position-relative" data-aos="fade-up" data-aos-delay="{{ $i * 150 }}">
                    @if ($i < count($steps) - 1)
                        <div class="d-none d-md-block position-absolute" style="top:22px;left:60%;right:-40%;height:2px;background:repeating-linear-gradient(90deg,var(--brand) 0 8px,transparent 8px 16px);opacity:.4;"></div>
                    @endif
                    <div class="d-flex align-items-start gap-3 position-relative">
                        <div class="step-num flex-shrink-0 btn-pulse">{{ $i + 1 }}</div>
                        <div>
                            <h5 class="fw-bold mb-1">{{ $title }}</h5>
                            <p class="text-secondary mb-0">{{ $desc }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- PRÉSENCE / GLOBE 3D -->
<section class="section" id="presence" style="background:radial-gradient(700px 400px at 75% 40%, #e0e7ff 0%, rgba(224,231,255,0) 65%), linear-gradient(180deg,#f7f8ff 0%,#eef2ff 100%);">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5" data-aos="fade-right">
                <span class="badge-soft mb-2 d-inline-block">Couverture</span>
                <h2 class="fw-bold">Déjà pensé pour <span class="text-brand">votre pays</span></h2>
                <p class="text-secondary">
                    checkinHub est disponible dans <strong>{{ count(config('plans.countries')) }} pays</strong>,
                    avec des tarifs adaptés au coût de la vie et à la devise locale.
                    Faites tourner le globe 🌍
                </p>
                <div class="d-flex flex-wrap gap-2 mt-3" id="country-badges">
                    @foreach (config('plans.countries') as $code => $c)
                        <span class="badge rounded-pill" style="background:#eef2ff;color:var(--brand);font-weight:600;padding:.5rem .9rem;">{{ $c['name'] }}</span>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <div id="globe" style="width:100%;height:560px;max-width:680px;margin:0 auto;"></div>
            </div>
        </div>
    </div>
</section>

<!-- PRICING -->
<section class="section" id="pricing">
    <div class="container">
        <div class="text-center mb-4">
            <span class="badge-soft mb-2 d-inline-block">{{ __('landing.pricing_badge') }}</span>
            <h2 class="fw-bold">{{ __('landing.pricing_title') }}</h2>
            <p class="text-secondary">{{ __('landing.pricing_description') }}</p>
            <div class="d-inline-flex align-items-center gap-2 mt-2">
                <i class="fas fa-earth-africa text-brand"></i>
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
                    $popular = ! empty($tier['popular']);
                    $taglines = ['starter' => __('flash.plan_starter_tagline'), 'pro' => __('flash.plan_pro_tagline'), 'business' => __('flash.plan_business_tagline')];
                    $features = ['starter' => [__('flash.plan_starter_f1'), __('flash.plan_starter_f2'), __('flash.plan_starter_f3'), __('flash.plan_starter_f4')], 'pro' => [__('flash.plan_pro_f1'), __('flash.plan_pro_f2'), __('flash.plan_pro_f3'), __('flash.plan_pro_f4')], 'business' => [__('flash.plan_business_f1'), __('flash.plan_business_f2'), __('flash.plan_business_f3'), __('flash.plan_business_f4')]];
                @endphp
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 130 }}">
                    <div class="price-card p-4 h-100 {{ $popular ? 'popular' : '' }}" data-base="{{ $tier['price'] }}">
                        @if ($popular)
                            <span class="badge text-white mb-2" style="background:var(--brand)">{{ __('flash.plan_popular') }}</span>
                        @endif
                        <h4 class="fw-bold">{{ $tier['name'] }}</h4>
                        <p class="text-secondary small">{{ $taglines[$key] ?? $tier['tagline'] }}</p>
                        <div class="price-amount mb-1">
                            <span class="pr-amount">{{ number_format($tier['price'], 0, ',', ' ') }}</span>
                            <span class="fs-6 text-secondary fw-normal"><span class="pr-cur">XOF</span> {{ __('flash.plan_per_month') }}</span>
                        </div>
                        <hr>
                        <ul class="list-unstyled mb-4">
                            @foreach (($features[$key] ?? $tier['features']) as $item)
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ $item }}</li>
                            @endforeach
                        </ul>
                        <a href="{{ route('hotel.register', ['plan' => $key]) }}" class="btn {{ $popular ? 'btn-brand' : 'btn-outline-brand' }} w-100">
                            {{ __('landing.pricing_choose') }} {{ $tier['name'] }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- TÉMOIGNAGES -->
<section class="section bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="badge-soft mb-2 d-inline-block">{{ __('landing.testimonials_badge') }}</span>
            <h2 class="fw-bold">{{ __('landing.testimonials_title') }}</h2>
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
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="{{ $i * 120 }}">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="text-warning mb-2">@for($s=0;$s<5;$s++)<i class="fas fa-star"></i>@endfor</div>
                            <p class="mb-3">“{{ $quote }}”</p>
                            <div class="d-flex align-items-center gap-2">
                                <div class="feature-icon" style="width:42px;height:42px;">{{ substr($name, 0, 1) }}</div>
                                <div>
                                    <div class="fw-semibold">{{ $name }}</div>
                                    <div class="small text-secondary">{{ $role }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA BAND -->
<section class="pb-5">
    <div class="container">
        <div class="cta-band p-5 text-center" data-aos="zoom-in">
            <h2 class="fw-bold mb-2">{{ __('landing.cta_title') }}</h2>
            <p class="mb-4 opacity-75">{{ __('landing.cta_description') }} {{ config('app.name', 'checkinHub') }}.</p>
            <a href="{{ route('hotel.register') }}" class="btn btn-light btn-lg px-4 text-brand fw-semibold btn-pulse">
                <i class="fas fa-rocket me-2"></i>{{ __('landing.cta_button') }}
            </a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="bg-dark text-light pt-5 pb-4">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-hotel text-brand me-1"></i> {{ config('app.name', 'checkinHub') }}</h5>
                <p class="text-secondary small">{{ __('landing.footer_description') }}</p>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="fw-semibold mb-3">{{ __('landing.footer_product') }}</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="#features">{{ __('landing.footer_features') }}</a></li>
                    <li class="mb-2"><a href="#pricing">{{ __('landing.footer_pricing') }}</a></li>
                    <li class="mb-2"><a href="{{ route('hotel.register') }}">{{ __('landing.footer_free_trial') }}</a></li>
                    <li class="mb-2"><a href="{{ url('/h/cactus-hotel') }}" target="_blank" rel="noopener noreferrer">{{ __('landing.footer_demo') }}</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="fw-semibold mb-3">{{ __('landing.footer_account') }}</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('login.index') }}">{{ __('landing.footer_login') }}</a></li>
                    <li class="mb-2"><a href="#pricing">{{ __('landing.footer_free_trial') }}</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="fw-semibold mb-3">{{ __('landing.footer_contact') }}</h6>
                <p class="text-secondary small mb-1"><i class="fas fa-envelope me-2"></i>contact@checkinhub.com</p>
                <p class="text-secondary small"><i class="fas fa-phone me-2"></i>+229 00 00 00 00</p>
            </div>
        </div>
        <hr class="border-secondary">
        <div class="text-center text-secondary small">
            &copy; {{ date('Y') }} {{ config('app.name', 'checkinHub') }}. {{ __('landing.footer_rights') }}
        </div>
    </div>
</footer>

<!-- Bouton retour en haut -->
<button id="scrollTop" aria-label="{{ __('landing.scroll_to_top') }}"
        style="position:fixed;right:22px;bottom:22px;width:46px;height:46px;border:none;border-radius:50%;background:var(--brand);color:#fff;box-shadow:0 10px 24px -8px rgba(79,70,229,.7);opacity:0;pointer-events:none;transition:opacity .25s, transform .25s;z-index:1000;">
    <i class="fas fa-arrow-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<!-- globe.gl (Three.js) : globe 3D photoréaliste des pays desservis -->
<script src="https://unpkg.com/globe.gl"></script>
<script>
    // Animations au scroll
    AOS.init({ duration: 700, once: true, easing: 'ease-out-cubic', offset: 80 });

    // Navbar dynamique
    const nav = document.querySelector('.navbar');
    const scrollTopBtn = document.getElementById('scrollTop');
    const onScroll = () => {
        nav.classList.toggle('scrolled', window.scrollY > 40);
        const show = window.scrollY > 400;
        scrollTopBtn.style.opacity = show ? '1' : '0';
        scrollTopBtn.style.pointerEvents = show ? 'auto' : 'none';
        scrollTopBtn.style.transform = show ? 'translateY(0)' : 'translateY(10px)';
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
    scrollTopBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    // Compteurs animés (count-up au scroll)
    const counters = document.querySelectorAll('.counter');
    const animateCounter = (el) => {
        const target = +el.dataset.target;
        const duration = 1400;
        const start = performance.now();
        const tick = (now) => {
            const p = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - p, 3);
            el.textContent = Math.round(target * eased);
            if (p < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
    };
    if ('IntersectionObserver' in window) {
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) { animateCounter(e.target); obs.unobserve(e.target); } });
        }, { threshold: .6 });
        counters.forEach(c => obs.observe(c));
    } else {
        counters.forEach(c => c.textContent = c.dataset.target);
    }

    // Prix ajustés selon le pays (coût de la vie)
    const plansCountries = @json(config('plans.countries'));
    const pricingSel = document.getElementById('pricing-country');
    if (pricingSel) {
        const fmt = n => n.toLocaleString('fr-FR');
        const updatePricing = () => {
            const c = plansCountries[pricingSel.value];
            if (!c) return;
            document.querySelectorAll('.price-card[data-base]').forEach(card => {
                const base = +card.dataset.base;
                const price = Math.round(base * c.coef / c.round) * c.round;
                const amt = card.querySelector('.pr-amount');
                const cur = card.querySelector('.pr-cur');
                if (amt) amt.textContent = fmt(price);
                if (cur) cur.textContent = c.currency;
            });
        };
        pricingSel.addEventListener('change', updatePricing);
        updatePricing();
    }
</script>

<!-- Globe 3D photoréaliste · bloc isolé (indépendant du reste du JS) -->
<script>
(function () {
    const servedData = @json(config('plans.countries'));
    // Coordonnées (lat/long) des pays desservis.
    const coords = {
        BJ:[9.3,2.3], TG:[8.6,0.8], CI:[7.5,-5.5], SN:[14.5,-14.5], BF:[12.2,-1.5],
        ML:[17.0,-4.0], NE:[17.6,8.0], CM:[5.7,12.5], GA:[-0.8,11.6], NG:[9.1,8.7],
        GH:[7.9,-1.0], FR:[46.6,2.2]
    };

    function build() {
        const el = document.getElementById('globe');
        if (!el) return;

        if (typeof Globe === 'undefined') {
            // globe.gl non chargé (CDN bloqué) : la liste des pays reste visible.
            el.style.display = 'none';
            console.warn('Globe: globe.gl non disponible (CDN bloqué ?).');
            return;
        }

        try {
            const points = Object.keys(servedData)
                .filter(c => coords[c])
                .map(c => ({ code: c, name: servedData[c].name, lat: coords[c][0], lng: coords[c][1] }));

            const TEX = 'https://unpkg.com/three-globe/example/img/';

            const globe = Globe()(el)
                .backgroundColor('rgba(0,0,0,0)')
                .globeImageUrl(TEX + 'earth-blue-marble.jpg')
                .bumpImageUrl(TEX + 'earth-topology.png')
                .showAtmosphere(true)
                .atmosphereColor('#6366f1')
                .atmosphereAltitude(0.2)
                // Halos pulsants sur chaque pays
                .ringsData(points)
                .ringColor(() => (t) => `rgba(129,140,248,${Math.sqrt(1 - t)})`)
                .ringMaxRadius(4)
                .ringPropagationSpeed(2.2)
                .ringRepeatPeriod(900)
                // Points brillants
                .pointsData(points)
                .pointColor(() => '#c7d2fe')
                .pointAltitude(0.02)
                .pointRadius(0.35)
                // Étiquettes des pays
                .labelsData(points)
                .labelText('name')
                .labelSize(1.1)
                .labelDotRadius(0.4)
                .labelColor(() => '#ffffff')
                .labelResolution(2);

            const resize = () => { globe.width(el.clientWidth).height(el.clientHeight); };
            resize();
            window.addEventListener('resize', resize);

            // Vue centrée sur l'Afrique de l'Ouest + rotation automatique
            globe.pointOfView({ lat: 8, lng: 4, altitude: 1.65 }, 0);
            const controls = globe.controls();
            controls.autoRotate = true;
            controls.autoRotateSpeed = 0.7;
            controls.enableZoom = true;      // molette / pinch pour zoomer
            controls.minDistance = 160;      // zoom avant max (proche)
            controls.maxDistance = 450;      // zoom arrière max (loin)
            controls.zoomSpeed = 0.8;
        } catch (e) {
            console.error('Globe 3D:', e);
            el.style.display = 'none';
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', build);
    } else {
        build();
    }
})();
</script>
</body>
</html>
