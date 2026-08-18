@extends('frontend.layouts.master')

@section('title', __('frontend_services.hero_badge'))

@push('styles')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    :root {
        --cactus-green: #1A472A;
        --cactus-light: #2E5C3F;
        --cactus-dark: #0F2918;
        --gold-accent: #C9A961;
        --gold-light: #E8D5A3;
        --light-bg: #F8FAF9;
        --white: #FFFFFF;
        --text-dark: #1A1A1A;
        --text-gray: #6B7280;
        --border-color: #E5E7EB;
        --transition-smooth: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.06);
        --shadow-md: 0 8px 24px rgba(0,0,0,0.10);
        --shadow-lg: 0 20px 50px rgba(0,0,0,0.14);
    }

    /* ===========================
       HERO SECTION
    =========================== */
    .services-hero {
        position: relative;
        min-height: 70vh;
        display: flex;
        align-items: center;
        background: url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
        background-attachment: fixed;
        overflow: hidden;
    }

    .services-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(15,41,24,0.85) 0%, rgba(26,71,42,0.70) 50%, rgba(201,169,97,0.15) 100%);
        z-index: 1;
    }

    .services-hero .container {
        position: relative;
        z-index: 2;
    }

    .hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 8px 22px;
        background: rgba(201,169,97,0.15);
        border: 1px solid rgba(201,169,97,0.4);
        border-radius: 50px;
        color: var(--gold-accent);
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-bottom: 24px;
    }

    .hero-eyebrow i { font-size: 10px; }

    .services-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.8rem, 6vw, 5rem);
        font-weight: 700;
        color: var(--white);
        line-height: 1.1;
        margin-bottom: 20px;
        letter-spacing: -1px;
    }

    .services-hero h1 em {
        font-style: italic;
        color: var(--gold-accent);
    }

    .services-hero .hero-lead {
        font-size: 1.15rem;
        color: rgba(255,255,255,0.80);
        max-width: 600px;
        line-height: 1.8;
        margin-bottom: 40px;
    }

    .hero-stats-bar {
        display: flex;
        gap: 50px;
        padding-top: 40px;
        border-top: 1px solid rgba(255,255,255,0.15);
        margin-top: 40px;
    }

    .hero-stat { text-align: left; }

    .hero-stat .number {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--gold-accent);
        line-height: 1;
    }

    .hero-stat .label {
        font-size: 12px;
        color: rgba(255,255,255,0.65);
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-top: 4px;
    }


    /* ===========================
       COMMON SECTION STYLES
    =========================== */
    .py-section { padding: 100px 0; }

    .section-tag {
        display: inline-block;
        padding: 6px 20px;
        background: rgba(26,71,42,0.08);
        color: var(--cactus-green);
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        margin-bottom: 16px;
    }

    .section-tag.gold {
        background: rgba(201,169,97,0.12);
        color: #9A7830;
    }

    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 700;
        color: var(--text-dark);
        letter-spacing: -0.5px;
        margin-bottom: 15px;
    }

    .section-subtitle {
        font-size: 1.05rem;
        color: var(--text-gray);
        max-width: 600px;
        line-height: 1.8;
    }

    /* ===========================
       SERVICES OVERVIEW (icon grid)
    =========================== */
    .services-overview {
        background: var(--light-bg);
    }

    .service-icon-card {
        background: var(--white);
        border-radius: 20px;
        padding: 40px 30px;
        text-align: center;
        border: 1px solid var(--border-color);
        transition: var(--transition-smooth);
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .service-icon-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--cactus-green), var(--gold-accent));
        transform: scaleX(0);
        transition: var(--transition-smooth);
    }

    .service-icon-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-lg);
        border-color: transparent;
    }

    .service-icon-card:hover::before { transform: scaleX(1); }

    .service-icon-wrap {
        width: 80px;
        height: 80px;
        margin: 0 auto 24px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        background: linear-gradient(135deg, rgba(26,71,42,0.08), rgba(201,169,97,0.08));
        transition: var(--transition-smooth);
    }

    .service-icon-card:hover .service-icon-wrap {
        background: linear-gradient(135deg, var(--cactus-green), var(--cactus-light));
        color: var(--white);
    }

    .service-icon-card h4 {
        font-family: 'Playfair Display', serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 10px;
    }

    .service-icon-card p {
        font-size: 0.9rem;
        color: var(--text-gray);
        line-height: 1.7;
        margin: 0;
    }

    /* ===========================
       FEATURED SERVICES
    =========================== */
    .featured-service {
        background: var(--white);
    }

    .featured-service.alt { background: var(--light-bg); }

    .service-image-block {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .service-image-block img {
        width: 100%;
        height: 480px;
        object-fit: cover;
        display: block;
        transition: transform 0.8s ease;
    }

    .service-image-block:hover img { transform: scale(1.04); }

    .service-badge-overlay {
        position: absolute;
        top: 24px;
        left: 24px;
        background: var(--white);
        border-radius: 12px;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: var(--shadow-md);
    }

    .service-badge-overlay .icon-circle {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--cactus-green), var(--cactus-light));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        font-size: 1.1rem;
    }

    .service-badge-overlay .badge-label { font-size: 11px; color: var(--text-gray); text-transform: uppercase; letter-spacing: 1px; }
    .service-badge-overlay .badge-value { font-weight: 700; color: var(--text-dark); font-size: 0.95rem; }

    .service-rating-overlay {
        position: absolute;
        bottom: 24px;
        right: 24px;
        background: rgba(15,41,24,0.9);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 12px 18px;
        color: var(--white);
        text-align: center;
    }

    .service-rating-overlay .stars { color: var(--gold-accent); font-size: 0.85rem; letter-spacing: 2px; }
    .service-rating-overlay .rating-text { font-size: 11px; color: rgba(255,255,255,0.7); margin-top: 3px; }

    .service-content-block { padding: 20px 0; }

    .service-content-block .service-number {
        font-family: 'Playfair Display', serif;
        font-size: 5rem;
        font-weight: 700;
        color: rgba(26,71,42,0.06);
        line-height: 1;
        margin-bottom: -20px;
        letter-spacing: -2px;
    }

    .service-content-block h2 {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.8rem, 3vw, 2.5rem);
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 16px;
        letter-spacing: -0.5px;
    }

    .service-content-block .service-desc {
        font-size: 1.05rem;
        color: var(--text-gray);
        line-height: 1.85;
        margin-bottom: 30px;
    }

    .service-features-list {
        list-style: none;
        padding: 0;
        margin: 0 0 36px;
        display: grid;
        gap: 12px;
    }

    .service-features-list li {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.95rem;
        color: var(--text-dark);
    }

    .service-features-list li .check-icon {
        width: 22px;
        height: 22px;
        min-width: 22px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--cactus-green), var(--cactus-light));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        font-size: 0.65rem;
    }

    .btn-service {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 15px 35px;
        background: var(--cactus-green);
        color: var(--white);
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition-smooth);
        border: 2px solid var(--cactus-green);
    }

    .btn-service:hover {
        background: transparent;
        color: var(--cactus-green);
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(26,71,42,0.2);
    }

    .btn-service-gold {
        background: var(--gold-accent);
        border-color: var(--gold-accent);
        color: var(--cactus-dark);
    }

    .btn-service-gold:hover {
        background: transparent;
        color: #9A7830;
        border-color: var(--gold-accent);
    }

    /* ===========================
       ADDITIONAL SERVICES
    =========================== */
    .additional-services { background: var(--cactus-dark); }

    .add-service-card {
        background: var(--white, #fff);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px;
        padding: 30px 24px;
        transition: var(--transition-smooth);
        height: 100%;
    }

    .add-service-card:hover {
        background: var(--white, #fff);
        border-color: rgba(201,169,97,0.3);
        transform: translateY(-6px);
    }

    .add-service-card .icon-ring {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: 2px solid rgba(201,169,97,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: var(--gold-accent);
        margin-bottom: 18px;
        transition: var(--transition-smooth);
    }

    .add-service-card:hover .icon-ring {
        background: var(--gold-accent);
        color: var(--cactus-dark);
        border-color: var(--gold-accent);
    }

    .add-service-card h5 {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        color: var(--white);
        margin-bottom: 10px;
    }

    .add-service-card p {
        font-size: 0.875rem;
        color: rgba(255,255,255,0.55);
        line-height: 1.7;
        margin: 0;
    }

    .add-service-card .availability {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        color: var(--gold-accent);
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-top: 14px;
    }

    .add-service-card .availability .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--gold-accent);
        animation: pulse-dot 2s infinite;
    }

    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(0.8); }
    }

    /* ===========================
       SPA SHOWCASE
    =========================== */
    .spa-section { background: var(--white); }

    .spa-gallery {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 1fr 1fr;
        gap: 16px;
        height: 500px;
    }

    .spa-gallery .main-img {
        grid-row: 1 / 3;
        border-radius: 20px;
        overflow: hidden;
    }

    .spa-gallery .sub-img {
        border-radius: 16px;
        overflow: hidden;
    }

    .spa-gallery img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .spa-gallery .main-img img { height: 500px; }
    .spa-gallery img:hover { transform: scale(1.05); }

    .spa-treatment {
        background: var(--light-bg);
        border-radius: 16px;
        padding: 22px;
        display: flex;
        align-items: center;
        gap: 18px;
        transition: var(--transition-smooth);
        border: 1px solid var(--border-color);
        margin-bottom: 16px;
    }

    .spa-treatment:hover {
        background: var(--white);
        box-shadow: var(--shadow-md);
        transform: translateX(6px);
    }

    .spa-treatment .treatment-icon {
        width: 52px;
        height: 52px;
        min-width: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--cactus-green), var(--cactus-light));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        font-size: 1.2rem;
    }

    .spa-treatment h6 {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 4px;
    }

    .spa-treatment p {
        font-size: 0.85rem;
        color: var(--text-gray);
        margin: 0;
    }

    /* ===========================
       DINING SHOWCASE
    =========================== */
    .dining-card {
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        height: 380px;
        cursor: pointer;
        transition: var(--transition-smooth);
    }

    .dining-card:hover { transform: scale(1.02); box-shadow: var(--shadow-lg); }

    .dining-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .dining-card:hover img { transform: scale(1.08); }

    .dining-card-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(15,41,24,0.92) 0%, transparent 55%);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 28px;
    }

    .dining-card-overlay .tag {
        display: inline-block;
        padding: 4px 14px;
        background: var(--gold-accent);
        color: var(--cactus-dark);
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 10px;
        width: fit-content;
    }

    .dining-card-overlay h4 {
        font-family: 'Playfair Display', serif;
        color: var(--white);
        font-size: 1.5rem;
        margin-bottom: 6px;
    }

    .dining-card-overlay p {
        font-size: 0.875rem;
        color: rgba(255,255,255,0.70);
        margin: 0;
    }

    /* ===========================
       EVENTS SECTION
    =========================== */
    .events-section { background: var(--light-bg); }

    .event-space-card {
        background: var(--white);
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        transition: var(--transition-smooth);
        height: 100%;
    }

    .event-space-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
        border-color: transparent;
    }

    .event-space-card .card-img {
        height: 220px;
        overflow: hidden;
    }

    .event-space-card .card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .event-space-card:hover .card-img img { transform: scale(1.08); }

    .event-space-card .card-body { padding: 28px; }

    .event-space-card h5 {
        font-family: 'Playfair Display', serif;
        font-size: 1.25rem;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .event-space-card p {
        font-size: 0.9rem;
        color: var(--text-gray);
        margin-bottom: 20px;
        line-height: 1.7;
    }

    .event-spec {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.875rem;
        color: var(--text-gray);
        margin-bottom: 6px;
    }

    .event-spec i {
        color: var(--cactus-green);
        width: 16px;
        font-size: 0.85rem;
    }

    /* ===========================
       PROCESS / HOW IT WORKS
    =========================== */
    .process-section { background: var(--white); }

    .process-step {
        position: relative;
        text-align: center;
        padding: 0 20px;
    }

    .process-step::after {
        content: '';
        position: absolute;
        top: 35px;
        right: -50%;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, var(--cactus-green), var(--gold-accent));
        opacity: 0.3;
    }

    .process-step:last-child::after { display: none; }

    .step-number {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--cactus-green), var(--cactus-light));
        color: var(--white);
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 8px 24px rgba(26,71,42,0.25);
    }

    .process-step h5 {
        font-family: 'Playfair Display', serif;
        color: var(--text-dark);
        margin-bottom: 10px;
    }

    .process-step p {
        font-size: 0.9rem;
        color: var(--text-gray);
        line-height: 1.7;
    }

    /* ===========================
       CTA SECTION
    =========================== */
    .cta-services {
        background: linear-gradient(135deg, var(--cactus-dark) 0%, var(--cactus-green) 50%, var(--cactus-light) 100%);
        position: relative;
        overflow: hidden;
    }

    .cta-services::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 600px;
        height: 600px;
        border-radius: 50%;
        background: rgba(201,169,97,0.06);
        z-index: 0;
    }

    .cta-services::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: var(--white, #fff);
        z-index: 0;
    }

    .cta-services .container { position: relative; z-index: 1; }

    .cta-services h2 {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2rem, 4vw, 3.2rem);
        color: var(--white);
        font-weight: 700;
        margin-bottom: 18px;
        letter-spacing: -0.5px;
    }

    .cta-services p {
        font-size: 1.1rem;
        color: rgba(255,255,255,0.75);
        max-width: 550px;
        margin: 0 auto 40px;
        line-height: 1.8;
    }

    .btn-cta-white {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 16px 40px;
        background: var(--white);
        color: var(--cactus-green);
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 700;
        text-decoration: none;
        transition: var(--transition-smooth);
        border: 2px solid var(--white);
    }

    .btn-cta-white:hover {
        background: transparent;
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.2);
    }

    .btn-cta-gold {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 16px 40px;
        background: var(--gold-accent);
        color: var(--cactus-dark);
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 700;
        text-decoration: none;
        transition: var(--transition-smooth);
        border: 2px solid var(--gold-accent);
    }

    .btn-cta-gold:hover {
        background: transparent;
        color: var(--gold-accent);
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(201,169,97,0.3);
    }

    /* ===========================
       RESPONSIVE
    =========================== */
    @media (max-width: 991px) {
        .hero-stats-bar { gap: 30px; flex-wrap: wrap; }
        .spa-gallery { height: 350px; }
        .spa-gallery .main-img img { height: 350px; }
        .service-image-block img { height: 360px; }
        .process-step::after { display: none; }
    }

    @media (max-width: 767px) {
        .py-section { padding: 70px 0; }
        .services-hero { min-height: 85vh; background-attachment: scroll; }
        .spa-gallery { grid-template-columns: 1fr; grid-template-rows: auto; height: auto; }
        .spa-gallery .main-img { grid-row: auto; }
        .spa-gallery .main-img img { height: 280px; }
        .hero-stats-bar { gap: 20px; }
        .hero-stat .number { font-size: 1.6rem; }
        .dining-card { height: 300px; }
    }
</style>
@endpush

@section('content')

{{-- =================== HERO =================== --}}
<section class="services-hero">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8" data-aos="fade-right">
                <div class="hero-eyebrow">
                    <i class="fas fa-star"></i>
                    {!! __('frontend_services.hero_badge') !!}
                    <i class="fas fa-star"></i>
                </div>
                <h1>
                    {!! __('frontend_services.hero_title') !!}
                </h1>
                <p class="hero-lead">
                    {{ __('frontend_services.hero_lead') }}
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('frontend.reservation') }}" class="btn-cta-gold">
                        <i class="fas fa-calendar-check"></i> {{ __('frontend_services.book_stay') }}
                    </a>
                    <a href="{{ route('frontend.contact') }}" class="btn-cta-white">
                        <i class="fas fa-headset"></i> {{ __('frontend_services.contact_service') }}
                    </a>
                </div>
                <div class="hero-stats-bar">
                    <div class="hero-stat">
                        <div class="number">24/7</div>
                        <div class="label">{{ __('frontend_services.service_client') }}</div>
                    </div>
                    <div class="hero-stat">
                        <div class="number">5+</div>
                        <div class="label">{{ __('frontend_services.premium_services') }}</div>
                    </div>
                    <div class="hero-stat">
                        <div class="number">5★</div>
                        <div class="label">{{ __('frontend_services.official_rating') }}</div>
                    </div>
                    <div class="hero-stat">
                        <div class="number">30+</div>
                        <div class="label">{{ __('frontend_services.years_excellence') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =================== APERÇU SERVICES =================== --}}
<section class="services-overview py-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">{{ __('frontend_services.overview_tag') }}</span>
            <h2 class="section-title">{!! __('frontend_services.overview_title') !!}</h2>
            <p class="section-subtitle mx-auto">
                {{ __('frontend_services.overview_desc') }}
            </p>
        </div>

        <div class="row g-4">
            {{-- Card 1 --}}
            <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="0">
                <div class="service-icon-card">
                    <div class="service-icon-wrap">
                        <i class="fas fa-headset text-success"></i>
                    </div>
                    <h4>{{ __('frontend_services.service_24_7') }}</h4>
                    <p>{{ __('frontend_services.service_24_7_desc') }}</p>
                </div>
            </div>
            {{-- Card 2 --}}
            <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="60">
                <div class="service-icon-card">
                    <div class="service-icon-wrap">
                        <i class="fas fa-utensils text-success"></i>
                    </div>
                    <h4>{{ __('frontend_services.restaurant') }}</h4>
                    <p>{{ __('frontend_services.restaurant_desc') }}</p>
                </div>
            </div>
            {{-- Card 3 --}}
            <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="120">
                <div class="service-icon-card">
                    <div class="service-icon-wrap">
                        <i class="fas fa-cocktail text-success"></i>
                    </div>
                    <h4>{{ __('frontend_services.bar_cocktails') }}</h4>
                    <p>{{ __('frontend_services.bar_desc') }}</p>
                </div>
            </div>
            {{-- Card 4 --}}
            <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="180">
                <div class="service-icon-card">
                    <div class="service-icon-wrap">
                        <i class="fas fa-swimming-pool text-success"></i>
                    </div>
                    <h4>{{ __('frontend_services.pool_fitness') }}</h4>
                    <p>{{ __('frontend_services.pool_desc') }}</p>
                </div>
            </div>
            {{-- Card 5 --}}
            <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="0">
                <div class="service-icon-card">
                    <div class="service-icon-wrap">
                        <i class="fas fa-wifi text-success"></i>
                    </div>
                    <h4>{{ __('frontend_services.wifi') }}</h4>
                    <p>{{ __('frontend_services.wifi_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =================== SERVICE CLIENT =================== --}}
<section class="featured-service py-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="service-image-block">
                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"
                         alt="Service Client Cactus Palace">
                    <div class="service-badge-overlay">
                        <div class="icon-circle"><i class="fas fa-headset"></i></div>
                        <div>
                            <div class="badge-label">{{ __('frontend_services.availability') }}</div>
                            <div class="badge-value">24h/24 · 7j/7</div>
                        </div>
                    </div>
                    <div class="service-rating-overlay">
                        <div class="stars">★★★★★</div>
                        <div class="badge-label">{{ __('frontend_services.service_client') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="service-content-block">
                    <div class="service-number">01</div>
                    <span class="section-tag">{{ __('frontend_services.service_client') }}</span>
                    <h2>{!! __('frontend_services.service_client_title') !!}</h2>
                    <p class="service-desc">
                        {{ __('frontend_services.service_client_desc') }}
                    </p>
                    <ul class="service-features-list">
                        <li><span class="check-icon"><i class="fas fa-check"></i></span>{{ __('frontend_services.feature_1') }}</li>
                        <li><span class="check-icon"><i class="fas fa-check"></i></span>{{ __('frontend_services.feature_2') }}</li>
                        <li><span class="check-icon"><i class="fas fa-check"></i></span>{{ __('frontend_services.feature_3') }}</li>
                        <li><span class="check-icon"><i class="fas fa-check"></i></span>{{ __('frontend_services.feature_4') }}</li>
                        <li><span class="check-icon"><i class="fas fa-check"></i></span>{{ __('frontend_services.feature_5') }}</li>
                    </ul>
                    <a href="{{ route('frontend.contact') }}" class="btn-service">
                        {{ __('frontend_services.contact_team') }} <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- =================== GASTRONOMIE =================== --}}
<section class="featured-service py-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="service-number text-center" style="font-family:'Playfair Display',serif;font-size:5rem;font-weight:700;color:rgba(26,71,42,0.06);line-height:1;margin-bottom:-20px;">02</div>
            <span class="section-tag">{{ __('frontend_services.gastro_tag') }}</span>
            <h2 class="section-title">{{ __('frontend_services.gastro_title') }}</h2>
            <p class="section-subtitle mx-auto">
                {{ __('frontend_services.gastro_desc') }}
            </p>
        </div>
        <div class="row g-4">
            <div class="col-lg-5" data-aos="fade-right">
                <div class="dining-card" style="height:480px;">
                    <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"
                         alt="Restaurant Gastronomique">
                    <div class="dining-card-overlay">
                        <span class="tag">{{ __('frontend_services.restaurant_stellar_tag') }}</span>
                        <h4>{{ __('frontend_services.restaurant_stellar_name') }}</h4>
                        <p>{{ __('frontend_services.restaurant_stellar_desc') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <div class="row g-4 h-100">
                    <div class="col-md-6">
                        <div class="dining-card" style="height:220px;">
                            <img src="https://images.unsplash.com/photo-1559329007-40df8a9345d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                                 alt="Bar Lounge">
                            <div class="dining-card-overlay">
                                <span class="tag">{{ __('frontend_services.bar_lounge_tag') }}</span>
                                <h4>{{ __('frontend_services.bar_lounge_name') }}</h4>
                                <p>{{ __('frontend_services.bar_lounge_desc') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dining-card" style="height:220px;">
                            <img src="https://images.unsplash.com/photo-1533777857889-4be7c70b33f7?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                                 alt="Terrasse">
                            <div class="dining-card-overlay">
                                <span class="tag">{{ __('frontend_services.terrace_tag') }}</span>
                                <h4>{{ __('frontend_services.terrace_name') }}</h4>
                                <p>{{ __('frontend_services.terrace_desc') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dining-card" style="height:220px;">
                            <img src="https://images.unsplash.com/photo-1476224203421-9ac39bcb3327?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                                 alt="Room Service">
                            <div class="dining-card-overlay">
                                <span class="tag">{{ __('frontend_services.in_room_dining_tag') }}</span>
                                <h4>{{ __('frontend_services.in_room_dining_name') }}</h4>
                                <p>{{ __('frontend_services.room_service_desc') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dining-card" style="height:220px;">
                            <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                                 alt="Chef privé">
                            <div class="dining-card-overlay">
                                <span class="tag">{{ __('frontend_services.exclusive_tag') }}</span>
                                <h4>{{ __('frontend_services.private_chef_name') }}</h4>
                                <p>{{ __('frontend_services.private_chef_desc') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="{{ route('frontend.restaurant') }}" class="btn-service me-3">
                <i class="fas fa-utensils"></i> {{ __('frontend_services.see_full_menu') }}
            </a>
        </div>
    </div>
</section>


{{-- =================== SERVICES ADDITIONNELS =================== --}}
<section class="additional-services py-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag" style="background:rgba(201,169,97,0.12);color:var(--gold-accent);">{{ __('frontend_services.additional_tag') }}</span>
            <h2 class="section-title" style="color:var(--white);">{!! __('frontend_services.additional_title') !!}</h2>
            <p class="section-subtitle mx-auto" style="color:rgba(255,255,255,0.6);">
                {{ __('frontend_services.additional_desc') }}
            </p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="0">
                <div class="add-service-card">
                    <div class="icon-ring"><i class="fas fa-dumbbell"></i></div>
                    <h5>{{ __('frontend_services.gym') }}</h5>
                    <p>{{ __('frontend_services.gym_desc') }}</p>
                    <div class="availability"><span class="dot"></span>6h · 22h</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="60">
                <div class="add-service-card">
                    <div class="icon-ring"><i class="fas fa-swimming-pool"></i></div>
                    <h5>{{ __('frontend_services.outdoor_pool') }}</h5>
                    <p>{{ __('frontend_services.outdoor_pool_desc') }}</p>
                    <div class="availability"><span class="dot"></span>7h · 21h</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="120">
                <div class="add-service-card">
                    <div class="icon-ring"><i class="fas fa-wifi"></i></div>
                    <h5>{{ __('frontend_services.wifi_high_speed') }}</h5>
                    <p>{{ __('frontend_services.wifi_hd_desc') }}</p>
                    <div class="availability"><span class="dot"></span>24h/24</div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- =================== COMMENT ÇA MARCHE =================== --}}
<section class="process-section py-section" style="background:var(--light-bg);">
    <div class="container">
        <div class="text-center mb-6" data-aos="fade-up">
            <span class="section-tag">{{ __('frontend_services.process_tag') }}</span>
            <h2 class="section-title">{!! __('frontend_services.process_title') !!}</h2>
        </div>
        <div class="row g-4 mt-2">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                <div class="process-step">
                    <div class="step-number">1</div>
                    <h5>{{ __('frontend_services.step_1_title') }}</h5>
                    <p>{{ __('frontend_services.step_1_desc') }}</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="process-step">
                    <div class="step-number">2</div>
                    <h5>{{ __('frontend_services.step_2_title') }}</h5>
                    <p>{{ __('frontend_services.step_2_desc') }}</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="process-step">
                    <div class="step-number">3</div>
                    <h5>{{ __('frontend_services.step_3_title') }}</h5>
                    <p>{{ __('frontend_services.step_3_desc') }}</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="process-step">
                    <div class="step-number">4</div>
                    <h5>{{ __('frontend_services.step_4_title') }}</h5>
                    <p>{{ __('frontend_services.step_4_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =================== CTA =================== --}}
<section class="cta-services py-section">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <span class="section-tag" style="background:rgba(201,169,97,0.15);color:var(--gold-accent);">
                {{ __('frontend_services.cta_tag') }}
            </span>
            <h2>{!! __('frontend_services.cta_title') !!}</h2>
            <p>
                {{ __('frontend_services.cta_desc') }}
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('frontend.reservation') }}" class="btn-cta-gold">
                    <i class="fas fa-calendar-check"></i> {{ __('frontend_services.book_now') }}
                </a>
                <a href="{{ route('frontend.contact') }}" class="btn-cta-white">
                    <i class="fas fa-phone"></i> {{ __('frontend_services.call_us') }}
                </a>
                <a href="{{ route('frontend.rooms') }}" class="btn-cta-white" style="background:transparent;color:rgba(255,255,255,0.8);border-color:rgba(255,255,255,0.3);">
                    <i class="fas fa-bed"></i> {{ __('frontend_services.view_rooms') }}
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true,
        offset: 80
    });
</script>
@endpush
