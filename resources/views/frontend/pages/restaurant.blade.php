@extends('frontend.layouts.master')

@section('title', 'Restaurant Gastronomique — Cactus Palace 5 Étoiles')

@push('styles')
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --cactus-green: #1A472A;
            --cactus-light: #2E5C3F;
            --cactus-dark: #0F2918;
            --gold-accent: #C9A961;
            --light-bg: #F8FAF9;
            --white: #FFFFFF;
            --text-dark: #1A1A1A;
            --text-gray: #6B7280;
            --border-color: #E5E7EB;
            --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.10);
            --shadow-lg: 0 20px 50px rgba(0, 0, 0, 0.14);
        }

        /* ── HERO ── */
        .resto-hero {
            position: relative;
            min-height: 72vh;
            display: flex;
            align-items: center;
            background: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
            background-attachment: fixed;
            overflow: hidden;
        }

        .resto-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(15, 41, 24, 0.90) 0%, rgba(26, 71, 42, 0.75) 50%, rgba(201, 169, 97, 0.10) 100%);
        }

        .resto-hero .container {
            position: relative;
            z-index: 2;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 22px;
            background: rgba(201, 169, 97, 0.15);
            border: 1px solid rgba(201, 169, 97, 0.4);
            border-radius: 50px;
            color: var(--gold-accent);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 24px;
        }

        .resto-hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.8rem, 6vw, 4.8rem);
            font-weight: 700;
            color: var(--white);
            line-height: 1.1;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }

        .resto-hero h1 em {
            font-style: italic;
            color: var(--gold-accent);
        }

        .resto-hero .hero-lead {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.78);
            max-width: 580px;
            line-height: 1.85;
            margin-bottom: 36px;
        }

        .hero-stats-bar {
            display: flex;
            gap: 44px;
            flex-wrap: wrap;
            padding-top: 36px;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            margin-top: 4px;
        }

        .hero-stat-item .num {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--gold-accent);
            line-height: 1;
        }

        .hero-stat-item .lbl {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.6);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 4px;
        }

        /* ── PRESENTATION ── */
        .resto-intro {
            background: var(--light-bg);
            padding: 90px 0;
        }

        .section-tag {
            display: inline-block;
            padding: 5px 18px;
            background: rgba(26, 71, 42, 0.08);
            color: var(--cactus-green);
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: -0.5px;
            margin-bottom: 16px;
        }

        .intro-img-wrap {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .intro-img-wrap img {
            width: 100%;
            height: 480px;
            object-fit: cover;
            display: block;
            transition: transform .7s ease;
        }

        .intro-img-wrap:hover img {
            transform: scale(1.04);
        }

        .intro-badge {
            position: absolute;
            bottom: 24px;
            left: 24px;
            background: var(--white);
            border-radius: 14px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: var(--shadow-md);
        }

        .intro-badge .ib-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            min-width: 44px;
            background: linear-gradient(135deg, var(--cactus-green), var(--cactus-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 1.1rem;
        }

        .intro-badge .ib-lbl {
            font-size: 11px;
            color: var(--text-gray);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .intro-badge .ib-val {
            font-weight: 700;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .rating-badge {
            position: absolute;
            top: 24px;
            right: 24px;
            background: rgba(15, 41, 24, 0.88);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 12px 16px;
            text-align: center;
            color: var(--white);
        }

        .rating-badge .stars {
            color: var(--gold-accent);
            font-size: 0.85rem;
            letter-spacing: 2px;
        }

        .rating-badge .rt {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 3px;
        }

        .horaires-list {
            list-style: none;
            padding: 0;
            margin: 0;
            background: var(--white);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .horaires-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 20px;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
        }

        .horaires-list li:last-child {
            border-bottom: none;
        }

        .horaires-list li .service {
            color: var(--text-gray);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .horaires-list li .service i {
            color: var(--cactus-green);
            width: 16px;
        }

        .horaires-list li .hours {
            font-weight: 700;
            color: var(--cactus-green);
        }

        .btn-see-menu {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 36px;
            background: var(--cactus-green);
            color: var(--white);
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            transition: var(--transition);
            border: 2px solid var(--cactus-green);
        }

        .btn-see-menu:hover {
            background: transparent;
            color: var(--cactus-green);
            box-shadow: 0 10px 28px rgba(26, 71, 42, 0.18);
        }

        /* ── MENU SECTION ── */
        .menu-section {
            background: var(--white);
            padding: 80px 0;
        }

        .category-filter {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 22px;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            border: 1.5px solid var(--border-color);
            background: var(--white);
            color: var(--text-gray);
            transition: var(--transition);
            margin: 4px;
        }

        .category-filter:hover {
            border-color: var(--cactus-green);
            color: var(--cactus-green);
        }

        .category-filter.active {
            background: var(--cactus-green);
            border-color: var(--cactus-green);
            color: var(--white);
            box-shadow: 0 4px 14px rgba(26, 71, 42, 0.2);
        }

        .menu-card {
            background: var(--white);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: var(--transition);
            height: 100%;
            display: flex;
        }

        .menu-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-color: transparent;
        }

        .menu-card-img {
            flex-shrink: 0;
            width: 140px;
            overflow: hidden;
            background: var(--light-bg);
        }

        .menu-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s ease;
        }

        .menu-card:hover .menu-card-img img {
            transform: scale(1.08);
        }

        .menu-card-noimg {
            width: 140px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--light-bg);
            flex-shrink: 0;
        }

        .menu-card-noimg i {
            font-size: 2.5rem;
            color: rgba(26, 71, 42, 0.15);
        }

        .menu-card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .menu-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .menu-card-name {
            font-family: 'Playfair Display', serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .menu-card-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--cactus-green);
            white-space: nowrap;
        }

        .menu-card-desc {
            font-size: 0.85rem;
            color: var(--text-gray);
            line-height: 1.6;
            flex: 1;
        }

        .menu-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 14px;
        }

        .cat-badge {
            display: inline-block;
            padding: 3px 12px;
            background: rgba(26, 71, 42, 0.08);
            color: var(--cactus-green);
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-order {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            background: var(--gold-accent);
            color: var(--cactus-dark);
            border: none;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-order:hover {
            background: #b8962e;
            transform: translateY(-1px);
        }

        /* ── RESERVATION SECTION ── */
        .resa-section {
            background: var(--light-bg);
            padding: 80px 0;
        }

        .resa-card {
            background: var(--white);
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .resa-card-header {
            background: linear-gradient(135deg, var(--cactus-dark), var(--cactus-green));
            padding: 32px 40px;
        }

        .resa-card-header h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            color: var(--white);
            margin-bottom: 6px;
        }

        .resa-card-header p {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.65);
            margin: 0;
        }

        .resa-card-body {
            padding: 32px 40px;
        }

        .resa-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--cactus-green);
            margin-bottom: 8px;
            display: block;
        }

        .resa-input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid var(--border-color);
            border-radius: 10px;
            font-size: 0.9rem;
            color: var(--text-dark);
            background: var(--white);
            transition: var(--transition);
        }

        .resa-input:focus {
            outline: none;
            border-color: var(--cactus-green);
            box-shadow: 0 0 0 3px rgba(26, 71, 42, 0.08);
        }

        .resa-select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%231A472A'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 36px;
        }

        .btn-resa {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 36px;
            background: var(--gold-accent);
            color: var(--cactus-dark);
            border: 2px solid var(--gold-accent);
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-resa:hover {
            background: transparent;
            color: #9A7830;
            box-shadow: 0 10px 28px rgba(201, 169, 97, 0.25);
        }

        /* Responsive */
        @media (max-width: 991px) {
            .resto-hero {
                min-height: 60vh;
                background-attachment: scroll;
            }

            .intro-img-wrap img {
                height: 360px;
            }

            .hero-stats-bar {
                gap: 24px;
            }

            .resa-card-header,
            .resa-card-body {
                padding: 24px 24px;
            }
        }

        @media (max-width: 767px) {
            .menu-card {
                flex-direction: column;
            }

            .menu-card-img,
            .menu-card-noimg {
                width: 100%;
                height: 160px;
            }

            .resa-card-header,
            .resa-card-body {
                padding: 20px 18px;
            }
        }

        /* Contrôle quantité Vitrine */
        .v-qty-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(26, 71, 42, 0.05);
            border: 1.5px solid var(--gold-accent);
            border-radius: 10px;
            padding: 5px 12px;
        }

        .v-qty-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--gold-accent);
            color: var(--cactus-dark);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .v-qty-btn:hover {
            background: #b8962e;
            transform: scale(1.1);
        }

        .v-qval {
            font-weight: 700;
            color: var(--cactus-green);
            min-width: 20px;
            text-align: center;
        }

        /* Panier flottant */
        #floating-cart {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: var(--gold-accent);
            color: var(--cactus-dark);
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(201, 169, 97, 0.4);
            cursor: pointer;
            border: none;
            transition: all 0.3s;
        }

        #floating-cart:hover {
            transform: scale(1.1);
            background: #b8962e;
        }

        #cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e11d48;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        /* Checkout Grid */
        .om-checkout-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 991px) {
            .om-checkout-grid {
                grid-template-columns: 1fr;
            }
        }

        .om-checkout-left {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            height: 100%;
        }

        .om-checkout-right {
            background: rgba(212, 175, 55, 0.03);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid rgba(212, 175, 55, 0.1);
            height: 100%;
        }

        .om-recap-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .om-recap-item:last-child {
            border-bottom: none;
        }

        .om-recap-img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
            border: 1.5px solid var(--gold-accent);
        }

        .om-recap-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .om-recap-name {
            font-size: 0.85rem;
            color: #f5f0e0;
            font-weight: 600;
        }

        .om-recap-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .om-recap-qty-btn {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 1px solid var(--gold-accent);
            background: transparent;
            color: var(--gold-accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .om-recap-qty-btn:hover {
            background: var(--gold-accent);
            color: #000;
        }

        .om-recap-qty-val {
            font-size: 0.85rem;
            color: #fff;
            font-weight: 700;
            min-width: 15px;
            text-align: center;
        }

        .om-recap-price {
            font-size: 0.85rem;
            color: var(--gold-accent);
            font-weight: 700;
            text-align: right;
        }
    </style>
@endpush

@section('content')

    {{-- ── HERO ── --}}
    <section class="resto-hero">
        <div class="container">
            <div class="row align-items-center py-5">
                <div class="col-lg-8" data-aos="fade-right">
                    <div class="hero-eyebrow">
                        <i class="fas fa-utensils" style="font-size:10px;"></i>
                        Restaurant Gastronomique — Cactus Palace
                        <i class="fas fa-utensils" style="font-size:10px;"></i>
                    </div>
                    <h1>
                        L'Art culinaire<br>
                        <em>à son sommet</em>
                    </h1>
                    <p class="hero-lead">
                        Sous la direction de notre chef étoilé, découvrez une cuisine raffinée qui célèbre
                        les saveurs africaines et internationales dans un cadre d'exception.
                    </p>
                    <div class="hero-stats-bar">
                        <div class="hero-stat-item">
                            <div class="num">7h–23h</div>
                            <div class="lbl">Horaires</div>
                        </div>
                        <div class="hero-stat-item">
                            <div class="num">120+</div>
                            <div class="lbl">Couverts</div>
                        </div>
                        <div class="hero-stat-item">
                            <div class="num">5★</div>
                            <div class="lbl">Note moyenne</div>
                        </div>
                        <div class="hero-stat-item">
                            <div class="num">24/7</div>
                            <div class="lbl">Room Service</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── PRÉSENTATION ── --}}
    <section class="resto-intro">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="intro-img-wrap">
                        <img src="https://images.unsplash.com/photo-1559339352-11d035aa65de?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"
                            alt="Chef du Cactus Palace">
                        <div class="intro-badge">
                            <div class="ib-icon"><i class="fas fa-award"></i></div>
                            <div>
                                <div class="ib-lbl">Distinction</div>
                                <div class="ib-val">Chef étoilé</div>
                            </div>
                        </div>
                        <div class="rating-badge">
                            <div class="stars">★★★★★</div>
                            <div class="rt">Note clients</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <span class="section-tag">Art Culinaire</span>
                    <h2 class="section-title">Gastronomie &<br>Saveurs d'Exception</h2>
                    <p style="font-size:1rem;color:var(--text-gray);line-height:1.85;margin-bottom:20px;">
                        Notre restaurant vous invite à un voyage culinaire exceptionnel. Sous la direction de notre chef
                        étoilé,
                        nous proposons une cuisine contemporaine qui met en valeur les produits frais et les saveurs
                        béninoises
                        mêlées aux grandes traditions gastronomiques.
                    </p>
                    <p style="font-size:1rem;color:var(--text-gray);line-height:1.85;margin-bottom:30px;">
                        Avec une vue imprenable sur les jardins de l'hôtel, le restaurant offre une ambiance élégante
                        et chaleureuse, parfaite pour un dîner romantique, un repas d'affaires ou une célébration familiale.
                    </p>

                    <ul class="horaires-list mb-4">
                        <li>
                            <span class="service"><i class="fas fa-coffee"></i>Petit-déjeuner</span>
                            <span class="hours">7h00 – 11h00</span>
                        </li>
                        <li>
                            <span class="service"><i class="fas fa-sun"></i>Déjeuner</span>
                            <span class="hours">12h00 – 15h00</span>
                        </li>
                        <li>
                            <span class="service"><i class="fas fa-moon"></i>Dîner</span>
                            <span class="hours">19h00 – 23h00</span>
                        </li>
                        <li>
                            <span class="service"><i class="fas fa-concierge-bell"></i>Room Service</span>
                            <span class="hours">24h/24</span>
                        </li>
                    </ul>

                    <a href="#menuSection" class="btn-see-menu">
                        <i class="fas fa-book-open"></i> Voir notre carte
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ── MENU SECTION ── --}}
    <section class="menu-section" id="menuSection">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-tag">Notre Carte</span>
                <h2 class="section-title">Découvrez nos créations</h2>
                <p style="font-size:1rem;color:var(--text-gray);max-width:520px;margin:0 auto;">
                    Une sélection de plats raffinés préparés avec des produits frais et locaux.
                </p>
                <div class="mt-4" data-aos="fade-up" data-aos-delay="40">
                    <a href="{{ route('frontend.african') }}"
                        style="display:inline-flex;align-items:center;gap:10px;padding:13px 30px;
                              background:linear-gradient(135deg,#5C3317,#8B5A2B);color:#fff;
                              border-radius:50px;font-size:0.92rem;font-weight:700;text-decoration:none;
                              box-shadow:0 6px 20px rgba(92,51,23,0.3);transition:all .3s ease;"
                        onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 10px 28px rgba(92,51,23,0.4)'"
                        onmouseout="this.style.transform='none';this.style.boxShadow='0 6px 20px rgba(92,51,23,0.3)'">
                        <i class="fas fa-globe-africa"></i>
                        Spécialités Africaines
                        <span
                            style="background:rgba(255,255,255,0.2);border-radius:20px;padding:2px 9px;font-size:11px;">Nouveau</span>
                    </a>
                </div>
            </div>

            {{-- Onglets de catégorie --}}
            <div class="d-flex justify-content-center flex-wrap mb-5" data-aos="fade-up" data-aos-delay="60">
                <button class="category-filter active" data-category="all"><i class="fas fa-th me-1"></i>Tout voir</button>
                <button class="category-filter" data-category="entree"><i class="fas fa-leaf me-1"></i>Entrées</button>
                <button class="category-filter" data-category="plat"><i class="fas fa-utensils me-1"></i>Plats</button>
                <button class="category-filter" data-category="dessert"><i
                        class="fas fa-birthday-cake me-1"></i>Desserts</button>
                <button class="category-filter" data-category="boisson"><i
                        class="fas fa-glass-martini-alt me-1"></i>Boissons</button>
            </div>

            {{-- Grille menu (une catégorie à la fois par défaut) --}}
            <div class="row g-4" id="menuList">
                @forelse($menus as $menu)
                    <div class="col-lg-6 menu-item" data-category="{{ $menu->category }}" data-aos="fade-up"
                        data-aos-delay="{{ ($loop->index % 4) * 60 }}">
                        <div class="menu-card">
                            @if ($menu->image)
                                <div class="menu-card-img">
                                    <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}"
                                        onerror="this.onerror=null; this.src='https://i.pinimg.com/736x/fc/7a/4a/fc7a4ad5e3299c1dac28baa60eef6111.jpg';">
                                </div>
                            @else
                                <div class="menu-card-noimg"><i class="fas fa-utensils"></i></div>
                            @endif
                            <div class="menu-card-body">
                                <div class="menu-card-header">
                                    <div class="menu-card-name">{{ $menu->name }}</div>
                                    <div class="menu-card-price">{{ number_format($menu->price, 0, ',', ' ') }} FCFA</div>
                                </div>
                                <p class="menu-card-desc">{{ $menu->description }}</p>
                                <div class="menu-card-footer">
                                    <span class="cat-badge">
                                        @if ($menu->category == 'plat')
                                            Plat
                                        @elseif($menu->category == 'entree')
                                            Entrée
                                        @elseif($menu->category == 'dessert')
                                            Dessert
                                        @elseif($menu->category == 'boisson')
                                            Boisson
                                        @else
                                            {{ ucfirst($menu->category) }}
                                        @endif
                                    </span>
                                    <div class="v-qty-controls" data-id="{{ $menu->id }}">
                                        <button class="btn-order add-to-order" id="v-addbtn-{{ $menu->id }}"
                                            data-menu-id="{{ $menu->id }}" data-menu-name="{{ $menu->name }}"
                                            data-menu-price="{{ $menu->price }}"
                                            data-menu-image="{{ $menu->image_url }}">
                                            <i class="fas fa-cart-plus"></i> Commander
                                        </button>
                                        <div class="v-qty-wrapper d-none" id="v-qty-wrapper-{{ $menu->id }}">
                                            <button type="button" class="v-qty-btn v-qminus"
                                                data-id="{{ $menu->id }}">−</button>
                                            <span class="v-qval" id="v-qval-{{ $menu->id }}">0</span>
                                            <button type="button" class="v-qty-btn v-qplus"
                                                data-id="{{ $menu->id }}">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div
                            style="width:80px;height:80px;border-radius:50%;background:rgba(26,71,42,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:2rem;color:var(--cactus-green);">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h4 style="font-family:'Playfair Display',serif;color:var(--text-dark);">Menu en préparation</h4>
                        <p style="color:var(--text-gray);">Notre chef travaille sur de nouvelles créations.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════
             MODAL COMMANDE — 5 ÉTOILES
        ════════════════════════════════════════════ -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content om-card">

                {{-- En-tête --}}
                <div class="om-header">
                    <div class="om-header-left">
                        <span class="om-crown"><i class="fas fa-crown"></i></span>
                        <div>
                            <div class="om-title">Votre Commande</div>
                            <div class="om-subtitle">Restaurant Gastronomique — Cactus Palace</div>
                        </div>
                    </div>
                    <button type="button" class="om-close" data-bs-dismiss="modal"><i
                            class="fas fa-times"></i></button>
                </div>

                {{-- Barre de progression --}}
                <div class="om-steps">
                    <div class="om-step active" data-step="1">
                        <div class="om-step-dot">1</div>
                        <div class="om-step-lbl">Vos informations</div>
                    </div>
                    <div class="om-step-line"></div>
                    <div class="om-step" data-step="2">
                        <div class="om-step-dot">2</div>
                        <div class="om-step-lbl">Validation & Paiement</div>
                    </div>
                </div>

                <form id="orderForm" action="{{ route('restaurant.orders.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="items" id="itemsInput">
                    <input type="hidden" name="total" id="totalInput">
                    <input type="hidden" name="customer_name" id="hCustomerName">
                    <input type="hidden" name="phone" id="hPhone">
                    <input type="hidden" name="email" id="hEmail">
                    <input type="hidden" name="room_number" id="hRoom">
                    <input type="hidden" name="notes" id="hNotes">
                    <input type="hidden" name="payment_method" id="hPayment" value="cash">

                    <div class="om-body">

                        {{-- ── ÉTAPE 1 : Informations ── --}}
                        <div class="om-panel active" id="panel-1">
                            <div class="om-panel-title">
                                <i class="fas fa-user-circle me-1 om-panel-icon"></i> Informations personnelles
                            </div>
                            <p class="om-panel-desc">Pour personnaliser votre expérience et vous contacter si besoin.</p>
                            <div class="om-grid-2">
                                <div class="om-field">
                                    <label class="om-label">Prénom <span class="om-req">*</span></label>
                                    <input type="text" class="om-input" id="f-prenom" placeholder="Samuel"
                                        autocomplete="given-name">
                                    <div class="om-err" id="err-prenom"></div>
                                </div>
                                <div class="om-field">
                                    <label class="om-label">Nom de famille <span class="om-req">*</span></label>
                                    <input type="text" class="om-input" id="f-nom" placeholder="ABAS"
                                        autocomplete="family-name">
                                    <div class="om-err" id="err-nom"></div>
                                </div>
                                <div class="om-field">
                                    <label class="om-label">Téléphone <span class="om-req">*</span></label>
                                    <div class="om-input-icon">
                                        <span class="om-icon"><i class="fas fa-mobile-alt"></i></span>
                                        <input type="tel" class="om-input has-icon" id="f-phone"
                                            placeholder="+229 01 00 00 00" autocomplete="tel">
                                    </div>
                                    <div class="om-err" id="err-phone"></div>
                                </div>
                                <div class="om-field">
                                    <label class="om-label">Adresse email</label>
                                    <div class="om-input-icon">
                                        <span class="om-icon"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="om-input has-icon" id="f-email"
                                            placeholder="samuel02@gmail.com" autocomplete="email">
                                    </div>
                                </div>
                                <div class="om-field">
                                    <label class="om-label">Numéro de chambre</label>
                                    <div class="om-input-icon">
                                        <span class="om-icon"><i class="fas fa-key"></i></span>
                                        <input type="text" class="om-input has-icon" id="f-room"
                                            placeholder="Ex : 214">
                                    </div>
                                    <div class="om-hint">Laissez vide si vous n'êtes pas résident</div>
                                </div>

                                <div class="om-field col-span-2" style="grid-column: span 2;">
                                    <label class="om-label">Notes spéciales</label>
                                    <textarea class="om-input" id="f-notes" rows="2" placeholder="Allergies, cuisson..."></textarea>
                                </div>
                            </div>
                        </div>



                        {{-- Étape 2: Mode de règlement --}}
                        <div class="om-panel" id="panel-2">
                            <div class="om-panel-title">
                                <i class="fas fa-credit-card me-1 om-panel-icon"></i> Mode de règlement
                            </div>
                            <p class="text-white-50 mb-4" style="font-size: 0.9rem;">Comment souhaitez-vous régler votre
                                commande ?</p>

                            <div class="om-payment-grid"
                                style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 15px;">
                                <label class="om-payment-card active" id="pay-card-cash">
                                    <input type="radio" name="payment_choice" value="cash" checked
                                        style="display:none">
                                    <div class="om-pc-icon"><i class="fas fa-money-bill-wave"></i></div>
                                    <div class="om-pc-label">Espèces</div>
                                    <div class="om-pc-status"><i class="fas fa-check-circle"></i></div>
                                </label>
                                <label class="om-payment-card" id="pay-card-mobile">
                                    <input type="radio" name="payment_choice" value="mobile_money" style="display:none">
                                    <div class="om-pc-icon"><i class="fas fa-mobile-alt"></i></div>
                                    <div class="om-pc-label">Mobile Money</div>
                                    <div class="om-pc-status"><i class="fas fa-check-circle d-none"></i></div>
                                </label>
                                <label class="om-payment-card" id="pay-card-card">
                                    <input type="radio" name="payment_choice" value="card" style="display:none">
                                    <div class="om-pc-icon"><i class="fas fa-credit-card"></i></div>
                                    <div class="om-pc-label">Carte Bancaire</div>
                                    <div class="om-pc-status"><i class="fas fa-check-circle d-none"></i></div>
                                </label>
                                <label class="om-payment-card" id="pay-card-fedapay">
                                    <input type="radio" name="payment_choice" value="fedapay" style="display:none">
                                    <div class="om-pc-icon"><i class="fas fa-wallet"></i></div>
                                    <div class="om-pc-label">Fedapay</div>
                                    <div class="om-pc-status"><i class="fas fa-check-circle d-none"></i></div>
                                </label>
                                <label class="om-payment-card" id="pay-card-transfer">
                                    <input type="radio" name="payment_choice" value="transfer" style="display:none">
                                    <div class="om-pc-icon"><i class="fas fa-university"></i></div>
                                    <div class="om-pc-label">Virement</div>
                                    <div class="om-pc-status"><i class="fas fa-check-circle d-none"></i></div>
                                </label>
                                <label class="om-payment-card" id="pay-card-check">
                                    <input type="radio" name="payment_choice" value="check" style="display:none">
                                    <div class="om-pc-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                                    <div class="om-pc-label">Chèque</div>
                                    <div class="om-pc-status"><i class="fas fa-check-circle d-none"></i></div>
                                </label>
                                <div id="room-payment-notice" class="col-span-2 d-none" style="grid-column: span 2;">
                                    <div class="om-payment-card active w-100" style="cursor: default;">
                                        <input type="radio" name="payment_choice" value="room_charge" style="display:none">
                                        <div class="om-pc-icon"><i class="fas fa-hotel"></i></div>
                                        <div class="om-pc-label">Facture de la chambre</div>
                                        <div class="om-pc-status"><i class="fas fa-check-circle"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div id="payment-normal-hint" class="mt-4 p-3 rounded"
                                style="background: rgba(212,175,55,0.05); border: 1px dashed var(--gold-accent);">
                                <p class="mb-0" style="color: var(--gold-accent); font-size: 0.85rem;">
                                    <i class="fas fa-info-circle me-2"></i> Le règlement s'effectuera directement lors de
                                    la réception de votre commande.
                                </p>
                            </div>
                            <div id="payment-room-hint" class="mt-4 p-3 rounded d-none"
                                style="background: rgba(46, 125, 50, 0.1); border: 1px dashed #4caf50;">
                                <p class="mb-0" style="color: #81c784; font-size: 0.85rem;">
                                    <i class="fas fa-check-circle me-2"></i> Client de l'hôtel détecté. La commande sera ajoutée directement à votre note de chambre.
                                </p>
                            </div>

                            <style>
                                .om-payment-card {
                                    background: rgba(255, 255, 255, 0.03);
                                    border: 1px solid rgba(255, 255, 255, 0.1);
                                    border-radius: 12px;
                                    padding: 20px;
                                    cursor: pointer;
                                    transition: all 0.3s;
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    position: relative;
                                }

                                .om-payment-card.active {
                                    border-color: var(--gold-accent);
                                    background: rgba(212, 175, 55, 0.08);
                                }

                                .om-pc-icon {
                                    font-size: 1.5rem;
                                    color: var(--gold-accent);
                                    margin-bottom: 10px;
                                }

                                .om-pc-label {
                                    font-size: 0.9rem;
                                    color: #fff;
                                    font-weight: 600;
                                }

                                .om-pc-status {
                                    position: absolute;
                                    top: 10px;
                                    right: 10px;
                                    color: var(--gold-accent);
                                    font-size: 1.1rem;
                                }

                                .om-payment-card.active .om-pc-status i {
                                    display: block !important;
                                }
                            </style>
                        </div>

                        {{-- Étape 3: Confirmation Finale --}}
                        <div class="om-panel" id="panel-3">
                            <div class="om-checkout-grid">
                                {{-- Gauche: Infos & Paiement --}}
                                <div class="om-checkout-left">
                                    <div class="om-panel-title">
                                        <i class="fas fa-id-card me-1 om-panel-icon"></i> Vos Informations
                                    </div>
                                    <div class="om-recap-block mb-4" id="recap-identity">
                                        {{-- Injecté par JS --}}
                                    </div>

                                    <div class="om-panel-title">
                                        <i class="fas fa-wallet me-2 om-panel-icon"></i> Règlement choisi
                                    </div>
                                    <div id="recap-payment-method" class="mt-2"
                                        style="color: var(--gold-accent); font-weight: 600; font-size: 0.9rem;">
                                        {{-- Injecté par JS --}}
                                    </div>
                                </div>

                                {{-- Droite: Panier --}}
                                <div class="om-checkout-right">
                                    <div class="om-panel-title">
                                        <i class="fas fa-shopping-cart me-1 om-panel-icon"></i> Votre sélection
                                    </div>
                                    <div id="recap-items" class="mt-3">
                                        {{-- Injecté par JS --}}
                                    </div>
                                    <div class="om-recap-total mt-4 pt-3" style="border-top:2px solid var(--gold-accent)">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span style="color:#a09060; font-size:0.8rem">Total à régler :</span>
                                            <strong id="recap-total"
                                                style="font-size:1.2rem; color:var(--gold-accent)"></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>{{-- /om-body --}}

                    {{-- Pied de modal --}}
                    <div class="om-footer">
                        <button type="button" class="om-btn om-btn-ghost" id="om-prev" style="display:none">
                            ← Précédent
                        </button>
                        <div class="om-footer-right">
                            <button type="button" class="om-btn om-btn-outline" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" class="om-btn om-btn-gold" id="om-next">
                                Suivant →
                            </button>
                            <button type="submit" class="om-btn om-btn-gold" id="om-submit" style="display:none">
                                <i class="fas fa-check me-1"></i> Confirmer la commande
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- ── RESERVATION SECTION ── --}}
    <section class="resa-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up">
                    <div class="resa-card">
                        <div class="resa-card-header">
                            <h3>Réservation de table</h3>
                            <p>Réservez votre table pour une expérience culinaire inoubliable</p>
                        </div>
                        <div class="resa-card-body">
                            <form id="reservationForm">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="resa-label">Nom complet *</label>
                                        <input type="text" class="resa-input" name="name"
                                            placeholder="Jean Dupont" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="resa-label">Téléphone *</label>
                                        <input type="tel" class="resa-input" name="phone"
                                            placeholder="+229 01 23 45 67" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="resa-label">Date *</label>
                                        <input type="date" class="resa-input" name="date" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="resa-label">Heure *</label>
                                        <input type="time" class="resa-input" name="time" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="resa-label">Nombre de personnes *</label>
                                        <select class="resa-input resa-select" name="persons" required>
                                            @for ($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ $i == 2 ? 'selected' : '' }}>
                                                    {{ $i }} personne{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="resa-label">Type de table</label>
                                        <select class="resa-input resa-select" name="table_type">
                                            <option value="standard">Standard</option>
                                            <option value="window">Fenêtre vue jardin</option>
                                            <option value="terrace">Terrasse extérieure</option>
                                            <option value="private">Salle privée</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="resa-label">Notes spéciales</label>
                                        <textarea class="resa-input" name="notes" rows="3"
                                            placeholder="Allergies, préférences alimentaires, occasion spéciale…" style="resize:vertical;min-height:90px;"></textarea>
                                    </div>
                                    <div class="col-12 text-center pt-2">
                                        <button type="submit" class="btn-resa">
                                            <i class="fas fa-calendar-check"></i> Réserver ma table
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- Panier flottant --}}
    <button id="floating-cart">
        <i class="fas fa-shopping-basket fa-lg"></i>
        <span id="cart-badge">0</span>
    </button>
@endsection

@push('styles')
    <style>
        /* ══════════════════════════════════════════════════════
       MODAL 5 ÉTOILES — ORDER MODAL
    ══════════════════════════════════════════════════════ */
        .om-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background: #0e0e0e;
            box-shadow: 0 40px 80px rgba(0, 0, 0, .6);
            font-family: 'Georgia', 'Times New Roman', serif;
            display: flex;
            flex-direction: column;
            max-height: 90vh;
        }

        #orderForm {
            display: flex;
            flex-direction: column;
            flex: 1;
            overflow: hidden;
        }

        /* En-tête */
        .om-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 22px 30px 18px;
            background: linear-gradient(135deg, #1a1208, #2a1e08);
            border-bottom: 1px solid rgba(212, 175, 55, .25);
        }

        .om-header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .om-crown {
            font-size: 1.6rem;
            color: #d4af37;
            line-height: 1;
        }

        .om-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #f5f0e0;
            letter-spacing: .04em;
        }

        .om-subtitle {
            font-size: .72rem;
            color: #a09060;
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .om-close {
            background: none;
            border: 1px solid rgba(212, 175, 55, .3);
            color: #a09060;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            font-size: .85rem;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .om-close:hover {
            background: rgba(212, 175, 55, .15);
            color: #d4af37;
        }

        /* Barre de progression */
        .om-steps {
            display: flex;
            align-items: center;
            padding: 18px 30px;
            background: #141414;
            border-bottom: 1px solid #222;
        }

        .om-step {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            cursor: default;
        }

        .om-step-dot {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .78rem;
            font-weight: 700;
            font-family: system-ui, sans-serif;
            border: 2px solid #333;
            color: #555;
            background: #1a1a1a;
            transition: all .3s;
            flex-shrink: 0;
        }

        .om-step-lbl {
            font-size: .72rem;
            color: #555;
            font-family: system-ui, sans-serif;
            white-space: nowrap;
        }

        .om-step.active .om-step-dot {
            background: #d4af37;
            border-color: #d4af37;
            color: #0e0e0e;
        }

        .om-step.active .om-step-lbl {
            color: #d4af37;
        }

        .om-step.done .om-step-dot {
            background: #2a6;
            border-color: #2a6;
            color: #fff;
        }

        .om-step.done .om-step-lbl {
            color: #4ade80;
        }

        .om-step-line {
            flex: 1;
            height: 1px;
            background: #2a2a2a;
            margin: 0 8px;
        }

        /* Corps */
        .om-body {
            padding: 28px 30px;
            background: #111;
            min-height: 340px;
            max-height: 70vh;
            overflow-y: auto;
        }

        /* Panneaux */
        .om-panel {
            display: none;
            animation: omFadeIn .3s ease;
        }

        .om-panel.active {
            display: block;
        }

        @keyframes omFadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .om-panel-title {
            font-size: 1rem;
            font-weight: 700;
            color: #e8d8b0;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 6px;
        }

        .om-panel-icon {
            font-size: 1.1rem;
        }

        .om-panel-desc {
            font-size: .8rem;
            color: #707070;
            margin-bottom: 22px;
            font-family: system-ui, sans-serif;
        }

        /* Grille 2 colonnes */
        .om-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media(max-width:600px) {
            .om-grid-2 {
                grid-template-columns: 1fr;
            }
        }

        /* Champs */
        .om-field {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .om-label {
            font-size: .72rem;
            color: #a09060;
            letter-spacing: .06em;
            text-transform: uppercase;
            font-family: system-ui, sans-serif;
        }

        .om-req {
            color: #d4af37;
        }

        .om-input {
            background: #1a1a1a;
            border: 1px solid #2e2e2e;
            border-radius: 9px;
            color: #f0e8d0;
            padding: 11px 14px;
            font-size: .88rem;
            font-family: system-ui, sans-serif;
            width: 100%;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }

        .om-input:focus {
            border-color: #d4af37;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, .12);
        }

        .om-input::placeholder {
            color: #3a3a3a;
        }

        .om-input-icon {
            position: relative;
        }

        .om-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: .85rem;
            pointer-events: none;
        }

        .om-input.has-icon {
            padding-left: 36px;
        }

        .om-select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23a09060'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 36px;
        }

        .om-select option {
            background: #1a1a1a;
            color: #f0e8d0;
        }

        .om-textarea {
            resize: vertical;
            min-height: 80px;
        }

        .om-hint {
            font-size: .68rem;
            color: #444;
            font-family: system-ui, sans-serif;
        }

        .om-err {
            font-size: .72rem;
            color: #f87171;
            min-height: 16px;
            font-family: system-ui, sans-serif;
        }

        /* ── Filtres catégorie ── */
        .om-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 18px;
        }

        .om-filter {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: .76rem;
            font-family: system-ui, sans-serif;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid #2e2e2e;
            background: #1a1a1a;
            color: #666;
            transition: all .18s;
        }

        .om-filter.active {
            background: #d4af37;
            border-color: #d4af37;
            color: #0e0e0e;
        }

        .om-filter:hover:not(.active) {
            border-color: #d4af37;
            color: #d4af37;
        }

        /* ── Grille plats ── */
        .om-menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 14px;
            max-height: 360px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #2e2e2e #111;
            padding-right: 4px;
            margin-bottom: 18px;
        }

        .om-dish {
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            overflow: hidden;
            transition: border-color .2s, transform .2s;
            cursor: pointer;
        }

        .om-dish:hover {
            border-color: #d4af37;
            transform: translateY(-2px);
        }

        .om-dish.selected {
            border-color: #d4af37;
            background: #1e1a0e;
        }

        .om-dish-img {
            height: 100px;
            overflow: hidden;
            background: #222;
            position: relative;
        }

        .om-dish-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .om-dish-noimg {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #333;
        }

        .om-dish-body {
            padding: 10px 12px;
        }

        .om-dish-name {
            font-size: .82rem;
            font-weight: 700;
            color: #e8d8b0;
            margin-bottom: 3px;
        }

        .om-dish-desc {
            font-size: .7rem;
            color: #555;
            line-height: 1.4;
            margin-bottom: 8px;
            font-family: system-ui, sans-serif;
        }

        .om-dish-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
        }

        .om-dish-price {
            font-size: .82rem;
            color: #d4af37;
            font-weight: 700;
            white-space: nowrap;
        }

        .om-add-btn {
            background: #d4af37;
            color: #0e0e0e;
            border: none;
            border-radius: 6px;
            font-size: .7rem;
            font-weight: 700;
            padding: 4px 10px;
            cursor: pointer;
            transition: background .18s;
            font-family: system-ui, sans-serif;
            white-space: nowrap;
        }

        .om-add-btn:hover {
            background: #c09a1a;
        }

        .om-add-btn.added {
            background: #2a6;
            color: #fff;
        }

        /* Contrôle quantité dans la grille */
        .om-qty {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .om-qty-btn {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 1px solid #d4af37;
            background: transparent;
            color: #d4af37;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .15s;
            font-family: system-ui, sans-serif;
        }

        .om-qty-btn:hover {
            background: #d4af37;
            color: #0e0e0e;
        }

        .om-qty-val {
            font-size: .82rem;
            color: #e8d8b0;
            font-weight: 700;
            min-width: 18px;
            text-align: center;
        }

        /* Mini panier */
        .om-basket {
            background: #1a1608;
            border: 1px solid rgba(212, 175, 55, .2);
            border-radius: 12px;
            padding: 14px 16px;
            margin-top: 4px;
        }

        .om-basket-title {
            font-size: .78rem;
            color: #d4af37;
            font-weight: 700;
            margin-bottom: 10px;
            font-family: system-ui, sans-serif;
        }

        .om-basket-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: .78rem;
            color: #c0b080;
            padding: 5px 0;
            border-bottom: 1px solid rgba(212, 175, 55, .08);
            font-family: system-ui, sans-serif;
        }

        .om-basket-item:last-child {
            border-bottom: none;
        }

        .om-basket-total {
            font-size: .82rem;
            color: #d4af37;
            font-weight: 700;
            text-align: right;
            margin-top: 10px;
            font-family: system-ui, sans-serif;
        }

        /* ── Allergènes ── */
        .om-section-lbl {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #a09060;
            font-family: system-ui, sans-serif;
            margin-bottom: 10px;
        }

        .om-allergen-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        @media(max-width:600px) {
            .om-allergen-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .om-allergen {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 9px;
            padding: 9px 12px;
            cursor: pointer;
            transition: all .18s;
            font-family: system-ui, sans-serif;
            font-size: .78rem;
            color: #888;
        }

        .om-allergen:has(input:checked) {
            border-color: #f87171;
            background: #1f1212;
            color: #fca5a5;
        }

        .om-allergen input {
            display: none;
        }

        .om-al-icon {
            font-size: 1rem;
        }

        /* ── Radios cuisson/régime ── */
        .om-radio-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .om-radio {
            display: flex;
            align-items: center;
            gap: 7px;
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 9px;
            padding: 8px 14px;
            cursor: pointer;
            transition: all .18s;
            font-size: .78rem;
            color: #888;
            font-family: system-ui, sans-serif;
        }

        .om-radio:has(input:checked) {
            border-color: #d4af37;
            background: #1e1a0e;
            color: #d4af37;
        }

        .om-radio input {
            display: none;
        }

        /* ── Paiement ── */
        .om-payment-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        @media(max-width:600px) {
            .om-payment-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .om-pay-card {
            cursor: pointer;
            border: 1px solid #2a2a2a;
            border-radius: 10px;
            background: #1a1a1a;
            transition: all .18s;
        }

        .om-pay-card:has(input:checked) {
            border-color: #d4af37;
            background: #1e1a0e;
        }

        .om-pay-card input {
            display: none;
        }

        .om-pay-body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 14px 8px;
            font-size: .72rem;
            color: #888;
            font-family: system-ui, sans-serif;
            text-align: center;
        }

        .om-pay-card:has(input:checked) .om-pay-body {
            color: #d4af37;
        }

        .om-pay-icon {
            font-size: 1.4rem;
        }

        /* ── Récapitulatif ── */
        .om-recap-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        @media(max-width:600px) {
            .om-recap-grid {
                grid-template-columns: 1fr;
            }
        }

        .om-recap-block {
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            padding: 16px 18px;
        }

        .om-recap-block-title {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #a09060;
            margin-bottom: 10px;
            font-family: system-ui, sans-serif;
        }

        .om-recap-line {
            font-size: .82rem;
            color: #c0b080;
            padding: 4px 0;
            font-family: system-ui, sans-serif;
            display: flex;
            justify-content: space-between;
        }

        .om-recap-line span {
            color: #888;
        }

        .om-recap-item {
            font-size: .82rem;
            color: #c0b080;
            padding: 6px 0;
            border-bottom: 1px solid #222;
            font-family: system-ui, sans-serif;
            display: flex;
            justify-content: space-between;
        }

        .om-recap-item:last-child {
            border-bottom: none;
        }

        .om-recap-total {
            text-align: right;
            margin-top: 12px;
            font-size: .9rem;
            color: #d4af37;
            font-weight: 700;
            font-family: system-ui, sans-serif;
        }

        .om-confirm-notice {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(212, 175, 55, .08);
            border: 1px solid rgba(212, 175, 55, .2);
            border-radius: 10px;
            padding: 14px 18px;
            font-size: .8rem;
            color: #a09060;
            font-family: system-ui, sans-serif;
            line-height: 1.6;
        }

        /* ── Pied ── */
        .om-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 15px;
            padding: 16px 30px;
            background: #0e0e0e;
            border-top: 1px solid #1e1e1e;
        }

        #om-prev {
            margin-right: auto;
        }

        .om-footer-right {
            display: flex;
            gap: 10px;
        }

        .om-btn {
            padding: 10px 22px;
            border-radius: 9px;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
            border: none;
            font-family: system-ui, sans-serif;
            transition: all .18s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .om-btn-ghost {
            background: transparent;
            color: #666;
            border: 1px solid #2a2a2a;
        }

        .om-btn-ghost:hover {
            color: #a09060;
            border-color: #3a3a3a;
        }

        .om-btn-outline {
            background: transparent;
            color: #888;
            border: 1px solid #2a2a2a;
        }

        .om-btn-outline:hover {
            color: #ccc;
            border-color: #555;
        }

        .om-btn-gold {
            background: linear-gradient(135deg, #d4af37, #b8962e);
            color: #0e0e0e;
            box-shadow: 0 4px 14px rgba(212, 175, 55, .3);
        }

        .om-btn-gold:hover {
            background: linear-gradient(135deg, #e0c048, #c8a030);
            box-shadow: 0 6px 20px rgba(212, 175, 55, .4);
            transform: translateY(-1px);
        }

        .om-btn-gold:disabled {
            opacity: .5;
            cursor: not-allowed;
            transform: none;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 700,
            once: true,
            offset: 60
        });
        $(document).ready(function() {

            /* ═══════════════════════════════
               FILTRAGE DE LA CARTE (page)
            ═══════════════════════════════ */
            $('.category-filter').click(function() {
                $('.category-filter').removeClass('active');
                $(this).addClass('active');
                const cat = $(this).data('category');
                if (cat === 'all') {
                    $('.menu-item').removeClass('d-none');
                } else {
                    $('.menu-item').addClass('d-none');
                    $(`.menu-item[data-category="${cat}"]`).removeClass('d-none');
                }
            });

            /* ═══════════════════════════════
               LOGIQUE PANIER VITRINE (3 ÉTAPES)
            ═══════════════════════════════ */
            let orderItems = {};
            const TOTAL_STEPS = 3;
            let currentStep = 1;

            function saveCart() {
                const data = {
                    items: orderItems,
                    customer: {
                        prenom: $('#f-prenom').val(),
                        nom: $('#f-nom').val(),
                        phone: $('#f-phone').val(),
                        email: $('#f-email').val(),
                        room: $('#f-room').val()
                    }
                };
                localStorage.setItem('cactus_cart', JSON.stringify(data));
            }

            function loadCart() {
                const saved = localStorage.getItem('cactus_cart');
                if (saved) {
                    try {
                        const data = JSON.parse(saved);
                        // Si les données sont à l'ancien format (juste les items), on les convertit
                        if (data.menu_id || (Object.keys(data).length > 0 && !data.items)) {
                            orderItems = data;
                        } else {
                            orderItems = data.items || {};
                            if (data.customer) {
                                $('#f-prenom').val(data.customer.prenom || '');
                                $('#f-nom').val(data.customer.nom || '');
                                $('#f-phone').val(data.customer.phone || '');
                                $('#f-email').val(data.customer.email || '');
                                $('#f-room').val(data.customer.room || '');
                            }
                        }

                        Object.keys(orderItems).forEach(id => syncUI(id));
                        updateFloatingCart();
                    } catch (e) {
                        orderItems = {};
                    }
                }
            }

            // Sauvegarder auto quand on saisit des infos
            $(document).on('input', '#f-prenom, #f-nom, #f-phone, #f-email, #f-room', saveCart);

            // --- 1. Gestion des clics sur la grille principale ---
            $(document).on('click', '.add-to-order', function() {
                const id = $(this).data('menu-id');
                const name = $(this).data('menu-name');
                const price = parseFloat($(this).data('menu-price'));
                const image = $(this).data('menu-image') ||
                    'https://i.pinimg.com/736x/fc/7a/4a/fc7a4ad5e3299c1dac28baa60eef6111.jpg';

                if (!orderItems[id]) {
                    orderItems[id] = {
                        menu_id: id,
                        name,
                        price,
                        image,
                        quantity: 1
                    };
                }
                syncUI(id);
                updateFloatingCart();
                saveCart();
            });

            $(document).on('click', '.v-qplus', function() {
                const id = $(this).data('id');
                if (orderItems[id]) {
                    orderItems[id].quantity++;
                    syncUI(id);
                    updateFloatingCart();
                    saveCart();
                }
            });

            $(document).on('click', '.v-qminus', function() {
                const id = $(this).data('id');
                if (orderItems[id]) {
                    orderItems[id].quantity--;
                    if (orderItems[id].quantity <= 0) {
                        delete orderItems[id];
                    }
                    syncUI(id);
                    updateFloatingCart();
                    saveCart();
                }
            });

            function syncUI(id) {
                const item = orderItems[id];
                const btn = $(`#v-addbtn-${id}`);
                const wrapper = $(`#v-qty-wrapper-${id}`);
                const val = $(`#v-qval-${id}`);

                if (item && item.quantity > 0) {
                    btn.addClass('d-none');
                    wrapper.removeClass('d-none').addClass('d-flex');
                    val.text(item.quantity);
                } else {
                    btn.removeClass('d-none');
                    wrapper.addClass('d-none').removeClass('d-flex');
                    val.text(0);
                }
            }

            function updateFloatingCart() {
                const count = Object.values(orderItems).reduce((sum, item) => sum + item.quantity, 0);
                if (count > 0) {
                    $('#floating-cart').css('display', 'flex');
                    $('#cart-badge').text(count);
                } else {
                    $('#floating-cart').hide();
                }
            }

            // --- 2. Ouverture du Modal Checkout ---
            $('#floating-cart').click(function() {
                goToStep(1);
                new bootstrap.Modal(document.getElementById('orderModal')).show();
            });

            /* ── Navigation Modal ── */
            $('#om-next').click(function() {
                if (validateStep(currentStep)) goToStep(currentStep + 1);
            });
            $('#om-prev').click(function() {
                goToStep(currentStep - 1);
            });

            function goToStep(n) {
                if (n < 1 || n > TOTAL_STEPS) return;

                const isGuest = $('#f-room').val().trim() !== '';

                // Si c'est un client hôtel et qu'il veut aller à l'étape 2, on saute direct à la 3
                if (isGuest && n === 2 && currentStep === 1) {
                    n = 3; 
                }
                // Si c'est un client hôtel et qu'il veut revenir de la 3, on saute la 2 pour aller à la 1
                if (isGuest && n === 2 && currentStep === 3) {
                    n = 1;
                }

                if (n === TOTAL_STEPS) buildRecap();

                // Gérer l'affichage des modes de paiement selon le statut guest
                if (isGuest) {
                    $('.om-payment-card:not(#room-payment-notice .om-payment-card)').parent().filter(':not(#room-payment-notice)').addClass('d-none');
                    // Plus simple: cacher tous les labels de paiement sauf celui de la chambre
                    $('#panel-2 label.om-payment-card').addClass('d-none');
                    $('#room-payment-notice, #payment-room-hint').removeClass('d-none');
                    $('#payment-normal-hint').addClass('d-none');
                    $('input[name="payment_choice"][value="room_charge"]').prop('checked', true);
                } else {
                    $('#panel-2 label.om-payment-card').removeClass('d-none');
                    $('#room-payment-notice, #payment-room-hint').addClass('d-none');
                    $('#payment-normal-hint').removeClass('d-none');
                    // Remettre cash par défaut si c'était sur room_charge
                    if ($('input[name="payment_choice"]:checked').val() === 'room_charge') {
                        $('input[name="payment_choice"][value="cash"]').prop('checked', true);
                        $('.om-payment-card').removeClass('active');
                        $('#pay-card-cash').addClass('active');
                    }
                }

                $('.om-step').each(function() {
                    const s = parseInt($(this).data('step'));
                    $(this).toggleClass('active', s === n)
                        .toggleClass('done', s < n);
                });
                $('.om-panel').removeClass('active');
                $(`#panel-${n}`).addClass('active');
                currentStep = n;

                $('#om-prev').toggle(n > 1);
                $('#om-next').toggle(n < TOTAL_STEPS);
                $('#om-submit').toggle(n === TOTAL_STEPS);
            }

            function validateStep(step) {
                if (step === 1) {
                    let ok = true;
                    if (!$('#f-prenom').val().trim()) {
                        $('#err-prenom').text('Le prénom est requis.');
                        ok = false;
                    } else {
                        $('#err-prenom').text('');
                    }
                    if (!$('#f-nom').val().trim()) {
                        $('#err-nom').text('Le nom est requis.');
                        ok = false;
                    } else {
                        $('#err-nom').text('');
                    }
                    if (!$('#f-phone').val().trim()) {
                        $('#err-phone').text('Le téléphone est requis.');
                        ok = false;
                    } else {
                        $('#err-phone').text('');
                    }
                    return ok;
                }
                return true;
            }

            function buildRecap() {
                const prenom = $('#f-prenom').val().trim();
                const nom = $('#f-nom').val().trim();
                const phone = $('#f-phone').val().trim();
                const email = $('#f-email').val().trim();
                const room = $('#f-room').val().trim();
                const notes = $('#f-notes').val().trim();
                let idHtml = `<div class="om-recap-line"><span>Client</span>${prenom} ${nom}</div>
                      <div class="om-recap-line"><span>Téléphone</span>${phone}</div>`;
                if (email) idHtml += `<div class="om-recap-line"><span>Email</span>${email}</div>`;
                if (room) idHtml += `<div class="om-recap-line"><span>Chambre</span>${room}</div>`;
                if (notes) idHtml += `<div class="om-recap-line"><span>Notes</span>${notes}</div>`;
                $('#recap-identity').html(idHtml);

                const items = Object.values(orderItems);
                let itemsHtml = '',
                    total = 0;
                items.forEach(it => {
                    const sub = it.price * it.quantity;
                    total += sub;
                    itemsHtml += `
                <div class="om-recap-item">
                    <img src="${it.image}" class="om-recap-img" onerror="this.onerror=null; this.src='https://i.pinimg.com/736x/fc/7a/4a/fc7a4ad5e3299c1dac28baa60eef6111.jpg';">
                    <div class="om-recap-info">
                        <div class="om-recap-name">${it.name}</div>
                        <div class="om-recap-controls">
                            <button type="button" class="om-recap-qty-btn m-qminus" data-id="${it.menu_id}">−</button>
                            <span class="om-recap-qty-val">${it.quantity}</span>
                            <button type="button" class="om-recap-qty-btn m-qplus" data-id="${it.menu_id}">+</button>
                        </div>
                    </div>
                    <div class="om-recap-price">${Math.round(sub).toLocaleString('fr-FR')} FCFA</div>
                </div>`;
                });
                $('#recap-items').html(itemsHtml);
                $('#recap-total').text(Math.round(total).toLocaleString('fr-FR') + ' FCFA');

                // Champs cachés pour l'envoi
                $('#hCustomerName').val(prenom + ' ' + nom);
                $('#hPhone').val(phone);
                $('#hEmail').val(email);
                $('#hRoom').val(room);
                const notesVal = $('#f-notes').val().trim();
                $('#hNotes').val(notesVal);
                // Mode de paiement
                const isGuest = room !== '';
                const paymentMode = isGuest ? 'room_charge' : $('input[name="payment_choice"]:checked').val();
                
                let paymentLabel = '';
                const methods = {
                    'room_charge': '<i class="fas fa-hotel me-2"></i> Sur facture (Chambre ' + room + ')',
                    'cash': '💵 Espèces (à la livraison)',
                    'mobile_money': '📲 Mobile Money',
                    'card': '💳 Carte Bancaire',
                    'fedapay': '💳 Fedapay / En ligne',
                    'transfer': '🏦 Virement Bancaire',
                    'check': '📄 Chèque'
                };
                paymentLabel = methods[paymentMode] || paymentMode;
                
                $('#recap-payment-method').html(paymentLabel);
                $('#hPayment').val(paymentMode);

                $('#itemsInput').val(JSON.stringify(items.map(i => ({
                    menu_id: i.menu_id,
                    quantity: i.quantity
                }))));
                $('#totalInput').val(total.toFixed(2));
            }

            // Gestion sélection paiement
            $(document).on('click', '.om-payment-card', function() {
                $('.om-payment-card').removeClass('active').find('i.fa-check-circle').addClass('d-none');
                $(this).addClass('active').find('i.fa-check-circle').removeClass('d-none');
                $(this).find('input').prop('checked', true);
            });

            // --- 3. Soumission ---
            $('#orderForm').submit(function(e) {
                e.preventDefault();
                const btn = $('#om-submit');
                btn.prop('disabled', true).text('Envoi en cours…');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Commande confirmée',
                            html: '<p style="color:#666;font-size:.9rem">Notre équipe prépare votre commande.<br>Vous serez contacté(e) très prochainement.</p>',
                            confirmButtonColor: '#d4af37',
                            confirmButtonText: 'Parfait !',
                        }).then(() => {
                            bootstrap.Modal.getInstance(document.getElementById(
                                'orderModal'))?.hide();
                            resetModal();
                        });
                    },
                    error: function(xhr) {
                        let msg = 'Une erreur est survenue.';
                        if (xhr.responseJSON?.message) msg = xhr.responseJSON.message;
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: msg
                        });
                        btn.prop('disabled', false).text('✓ Confirmer la commande');
                    }
                });
            });

            function resetModal() {
                orderItems = {};
                currentStep = 1;
                updateFloatingCart();

                // On ne vide plus complètement le storage pour garder les infos client
                // Mais on vide les items
                const saved = JSON.parse(localStorage.getItem('cactus_cart') || '{}');
                saved.items = {};
                localStorage.setItem('cactus_cart', JSON.stringify(saved));

                $('.v-qty-wrapper').addClass('d-none').removeClass('d-flex');
                $('.add-to-order').removeClass('d-none');
                // On ne reset plus tout le formulaire pour garder les infos client
                // $('#orderForm')[0].reset(); 
            }

            document.getElementById('orderModal').addEventListener('hidden.bs.modal', function() {
                if (Object.keys(orderItems).length === 0) resetModal();
            });

            // --- 4. Increment/Decrement dans le Modal ---
            $(document).on('click', '.m-qplus', function() {
                const id = $(this).data('id');
                if (orderItems[id]) {
                    orderItems[id].quantity++;
                    syncUI(id);
                    updateFloatingCart();
                    saveCart();
                    buildRecap(); // Rafraîchir le recap du modal
                }
            });

            $(document).on('click', '.m-qminus', function() {
                const id = $(this).data('id');
                if (orderItems[id]) {
                    orderItems[id].quantity--;
                    if (orderItems[id].quantity <= 0) {
                        delete orderItems[id];
                        // Si plus d'items, on peut fermer le modal ou afficher un message
                        if (Object.keys(orderItems).length === 0) {
                            bootstrap.Modal.getInstance(document.getElementById('orderModal'))?.hide();
                        }
                    }
                    syncUI(id);
                    updateFloatingCart();
                    saveCart();
                    buildRecap(); // Rafraîchir le recap du modal
                }
            });

            /* ── Réservation Table ── */
            $('#reservationForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('restaurant.reservation.store') }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function() {
                        Swal.fire({
                                icon: 'success',
                                title: 'Réservation envoyée !',
                                text: 'Nous vous confirmerons par téléphone.',
                                timer: 3000
                            })
                            .then(() => {
                                $('#reservationForm')[0].reset();
                            });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: 'Une erreur est survenue. Veuillez réessayer.'
                        });
                    }
                });
            });

            loadCart();
        });
    </script>
@endpush
