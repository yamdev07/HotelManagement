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

    @include('frontend.pages.partials.restaurant_dishes')




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


@endsection


@push('scripts')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 700,
            once: true,
            offset: 60
        });
        $(document).ready(function() {





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




        });
    </script>
@endpush
