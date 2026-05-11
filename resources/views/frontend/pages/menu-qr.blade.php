@extends('frontend.layouts.master')

@section('title', 'MENU — Le Cactus Hotel')

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
        }

        /* Standalone Experience */
        .cp-navbar, .footer { display: none !important; }
        main { padding-top: 0 !important; }
        body { background: #fff; overflow-x: hidden; }

        /* ── Splash Animation ── */
        #splash-screen {
            position: fixed; inset: 0; background: var(--cactus-dark);
            z-index: 9999; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
        }

        .splash-logo {
            width: 100px; margin-bottom: 25px; opacity: 0;
            animation: fadeIn 0.8s forwards;
        }

        .splash-hotel-name {
            font-family: 'Playfair Display', serif;
            letter-spacing: 4px; font-weight: 600; color: #fff;
            margin-bottom: 40px; opacity: 0; animation: fadeIn 0.8s 0.3s forwards;
        }

        .splash-title {
            font-family: 'Playfair Display', serif;
            font-size: 4rem; font-weight: 700; color: var(--gold-accent);
            letter-spacing: 10px; opacity: 0; transform: translateY(20px);
            animation: titleIn 1s 0.6s forwards ease;
        }

        .category-sequence {
            margin-top: 30px; height: 30px;
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem; font-weight: 500; color: rgba(255,255,255,0.6);
            letter-spacing: 3px; text-transform: uppercase;
        }

        @keyframes titleIn { to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { to { opacity: 1; } }

        /* ── Header Luxury (Fluid Architecture) ── */
        .menu-header {
            position: sticky; top: 0; z-index: 1000;
            padding: 40px 0;
            background: linear-gradient(135deg, var(--cactus-dark) 0%, var(--cactus-green) 100%);
            color: #fff; border-bottom: 1px solid var(--gold-accent);
            border-radius: 0 0 40px 40px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            transition: all 0.7s cubic-bezier(0.165, 0.84, 0.44, 1);
            overflow: hidden;
            will-change: padding, background, box-shadow;
        }

        .menu-header .header-content {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            transition: all 0.7s cubic-bezier(0.165, 0.84, 0.44, 1);
            gap: 15px;
        }

        .menu-header img { 
            height: 70px; width: auto; 
            transition: all 0.7s cubic-bezier(0.165, 0.84, 0.44, 1);
            filter: brightness(10);
        }

        .menu-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.2rem; font-weight: 800; color: var(--white);
            margin: 0; letter-spacing: 6px;
            transition: all 0.7s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .menu-header hr {
            width: 45px; height: 3px; background: var(--gold-accent);
            border: none; margin: 10px auto 0;
            transition: all 0.4s ease;
        }

        /* Scrolled State - Transitions everything precisely */
        .menu-header.scrolled {
            padding: 12px 0;
            border-radius: 0;
            background: rgba(15, 41, 24, 0.98);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .menu-header.scrolled .header-content {
            flex-direction: row; gap: 20px;
        }

        .menu-header.scrolled img { height: 42px; }
        .menu-header.scrolled h1 { font-size: 1.6rem; letter-spacing: 4px; }
        .menu-header.scrolled hr { opacity: 0; width: 0; margin: 0; }

        .menu-qr-wrapper { opacity: 0; transition: opacity 1s; }
        .menu-qr-wrapper.loaded { opacity: 1; }

        /* Entry Screen */
        #entry-screen {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at center, #1A472A 0%, #0F2918 100%);
            display: flex; align-items: center; justify-content: center;
            z-index: 10001; color: #fff;
        }
        .entry-logo { height: 100px; width: auto; margin-bottom: 2rem; filter: brightness(10); }
        .entry-sub { font-size: 1rem; letter-spacing: 5px; color: var(--gold-accent); margin-bottom: 0.5rem; }
        .entry-title { font-family: 'Playfair Display', serif; font-size: clamp(2.5rem, 8vw, 4.5rem); line-height: 1.1; margin-bottom: 3rem; color: #fff; }
        .btn-start {
            background: var(--gold-accent); color: var(--cactus-dark);
            border: none; padding: 20px 50px; border-radius: 50px;
            font-weight: 700; font-size: 1.1rem; letter-spacing: 2px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        }
        .btn-start:hover { transform: scale(1.08) translateY(-5px); background: #fff; box-shadow: 0 20px 45px rgba(201, 169, 97, 0.4); }

        @media (max-width: 1024px) {
            .menu-card { flex-direction: column !important; height: auto !important; }
            .menu-card-img, .menu-card-noimg { width: 100% !important; height: 280px !important; }
            .menu-header h1 { font-size: 2.5rem; }
            .entry-title { font-size: 3rem; }
            .btn-start { padding: 18px 45px; font-size: 1.1rem; }
            .menu-item { margin-bottom: 25px; }
        }

        @media (max-width: 767px) {
            .menu-card-img, .menu-card-noimg { height: 180px !important; }
            .menu-header h1 { font-size: 2.2rem; }
            .splash-title { font-size: 3rem; letter-spacing: 5px; }
            .entry-title { font-size: 2.8rem; }
            .btn-start { padding: 15px 35px; font-size: 1rem; }
            .no-items-empty-state { padding-bottom: 120px !important; }
        }

    </style>
@endpush

@section('content')
    {{-- Ecran d'Entrée --}}
    <div id="entry-screen">
        <div class="entry-content text-center">
            <img src="{{ asset('img/logo_cactus.png') }}" class="entry-logo mb-4">
            <h2 class="entry-sub">BIENVENUE AU</h2>
            <h1 class="entry-title mb-5">RESTAURANT<br><span style="color:var(--gold-accent)">CACTUS</span></h1>
            <button id="start-menu-btn" class="btn-start">
                <span>ALLER AU MENU</span>
                <i class="fas fa-arrow-right ms-2"></i>
            </button>
        </div>
    </div>

    {{-- Splash Screen (caché au début) --}}
    <div id="splash-screen" style="display: none;">
        <img src="{{ asset('img/logo_cactus.png') }}" alt="Logo" class="splash-logo" style="filter: brightness(10)">
        <div class="splash-hotel-name">LE CACTUS HOTEL</div>
        <div class="splash-title">MENU</div>
        <div class="category-sequence" id="cat-seq"></div>
    </div>

    <div class="menu-qr-wrapper" id="menu-wrap">
        <header class="menu-header">
            <div class="header-content">
                <img src="{{ asset('img/logo_cactus.png') }}">
                <div class="title-wrap">
                    <h1>MENU</h1>
                    <hr>
                </div>
            </div>
        </header>

        @include('frontend.pages.partials.restaurant_dishes')
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        $(document).ready(function() {
            const categories = {!! json_encode($categories->pluck('name')) !!};
            let catIdx = 0;
            
            function cycleCategories() {
                if(catIdx < categories.length) {
                    $('#cat-seq').fadeOut(300, function() {
                        $(this).text(categories[catIdx]).fadeIn(300);
                        catIdx++;
                    });
                }
            }

            // Au clic sur le bouton de départ
            $('#start-menu-btn').click(function() {
                // On cache l'entrée et on lance le splash
                $('#entry-screen').fadeOut(800, function() {
                    $('#splash-screen').fadeIn(500);
                    
                    // Lancement de l'animation AOS en avance pour être prêt
                    AOS.init({ duration: 1000, once: true });

                    // Lancement du cycle des catégories
                    setTimeout(() => {
                        setInterval(cycleCategories, 1000);
                    }, 500);

                    // Fin du splash et affichage du menu
                    setTimeout(function() {
                        $('#splash-screen').fadeOut(1000, function() {
                            $('#menu-wrap').addClass('loaded');
                            if (typeof AOS !== 'undefined') {
                                AOS.refresh();
                            }
                        });
                    }, 3500);
                });
            });

            // Header scroll effect with Hysteresis to prevent jitter/flashing
            let lastScrollTop = 0;
            let tick = false;
            let isScrolled = false;

            window.addEventListener('scroll', function() {
                lastScrollTop = window.scrollY;
                if (!tick) {
                    window.requestAnimationFrame(function() {
                        const thresholdDown = 120; // Point where it shrinks
                        const thresholdUp = 40;    // Point where it grows back

                        if (lastScrollTop > thresholdDown && !isScrolled) {
                            $('.menu-header').addClass('scrolled');
                            isScrolled = true;
                        } else if (lastScrollTop < thresholdUp && isScrolled) {
                            $('.menu-header').removeClass('scrolled');
                            isScrolled = false;
                        }
                        tick = false;
                    });
                    tick = true;
                }
            }, { passive: true });

            // UI cleanup
            $('.section-tag, .section-title, .menu-section p:first').hide();
        });
    </script>
@endpush
