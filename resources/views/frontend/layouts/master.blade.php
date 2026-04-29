<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Hôtel de luxe - Réservez votre séjour dans notre établissement 5 étoiles">
    <title>@yield('title', 'Hôtel Cactus Palace')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    

    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Styles personnalisés -->
    <style>
        :root {
            --cactus-green: #1A472A;
            --cactus-dark:  #0F2918;
            --gold-accent:  #C9A961;
            --gold-light:   rgba(201,169,97,.12);
            --primary-color: #1A472A;
            --dark-color:    #1A472A;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Montserrat', sans-serif;
            color: #333;
            background-color: #fff;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--cactus-dark);
        }

        /* ── Navbar ── */
        .cp-navbar {
            background: rgba(255,255,255,.96);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(201,169,97,.2);
            transition: background .3s, box-shadow .3s;
        }
        .cp-navbar.scrolled {
            background: rgba(15,41,24,.97);
            box-shadow: 0 2px 20px rgba(0,0,0,.3);
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--cactus-dark) !important;
            transition: color .3s;
        }
        .cp-navbar.scrolled .navbar-brand { color: #fff !important; }
        .cp-navbar.scrolled .navbar-brand span { color: var(--gold-accent) !important; }

        .nav-link {
            color: #333 !important;
            font-weight: 500;
            font-size: .9rem;
            letter-spacing: .3px;
            position: relative;
            padding: .5rem .9rem !important;
            transition: color .25s;
        }
        .nav-link::after {
            content: '';
            position: absolute; bottom: 0; left: .9rem; right: .9rem;
            height: 2px; background: var(--gold-accent);
            transform: scaleX(0); transition: transform .25s;
        }
        .nav-link:hover::after,
        .nav-link.active::after { transform: scaleX(1); }
        .nav-link:hover { color: var(--cactus-green) !important; }

        .cp-navbar.scrolled .nav-link { color: rgba(255,255,255,.85) !important; }
        .cp-navbar.scrolled .nav-link:hover { color: var(--gold-accent) !important; }

        /* Navbar toggler on dark */
        .cp-navbar.scrolled .navbar-toggler { border-color: rgba(201,169,97,.5); }
        .cp-navbar.scrolled .navbar-toggler-icon {
            filter: invert(1);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--cactus-green), var(--cactus-dark));
            border: none; color: #fff; border-radius: 50px;
            padding: .5rem 1.4rem; font-weight: 600; font-size: .88rem;
            transition: all .25s;
        }
        .btn-primary-custom:hover {
            background: linear-gradient(135deg, var(--gold-accent), #b8924f);
            color: var(--cactus-dark); transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(201,169,97,.35);
        }
        .btn-outline-primary-custom {
            border: 2px solid var(--cactus-green); color: var(--cactus-green);
            border-radius: 50px; padding: .45rem 1.3rem; font-weight: 600; font-size: .88rem;
            background: transparent; transition: all .25s;
        }
        .btn-outline-primary-custom:hover {
            background: var(--cactus-green); color: #fff;
        }
        .cp-navbar.scrolled .btn-outline-primary-custom {
            border-color: var(--gold-accent); color: var(--gold-accent);
        }
        .cp-navbar.scrolled .btn-outline-primary-custom:hover {
            background: var(--gold-accent); color: var(--cactus-dark);
        }

        /* ── Footer ── */
        .footer {
            background: linear-gradient(180deg, var(--cactus-dark) 0%, #07180e 100%);
            color: rgba(255,255,255,.8);
        }
        .footer h4, .footer h5 { color: #fff; }
        .footer h5 { font-size: 1rem; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 1.2rem; }
        .footer h5::after {
            content: ''; display: block; width: 30px; height: 2px;
            background: var(--gold-accent); margin: .4rem auto 0;
        }
        .footer p { color: rgba(255,255,255,.65); font-size: .88rem; line-height: 1.7; }
        .footer a.footer-link {
            color: rgba(255,255,255,.65); text-decoration: none; font-size: .88rem;
            display: block; margin-bottom: .5rem; transition: color .2s, padding-left .2s;
        }
        .footer a.footer-link:hover { color: var(--gold-accent); padding-left: 5px; }
        .footer-contact p { display: flex; align-items: center; justify-content: center; gap: .7rem; }
        .footer-contact .contact-icon {
            color: var(--gold-accent); width: 16px; flex-shrink: 0; margin-top: 3px;
        }

        .social-icons a {
            display: inline-flex; align-items: center; justify-content: center;
            width: 38px; height: 38px; border-radius: 50%;
            border: 1px solid rgba(201,169,97,.35);
            color: rgba(255,255,255,.7); margin-right: .4rem;
            font-size: 1rem; transition: all .25s;
        }
        .social-icons a:hover {
            background: var(--gold-accent); border-color: var(--gold-accent);
            color: var(--cactus-dark); transform: translateY(-2px);
        }

        .footer-divider {
            border-color: rgba(255,255,255,.08);
        }
        .footer-bottom {
            font-size: .8rem; color: rgba(255,255,255,.4);
        }
        .footer-bottom a { color: var(--gold-accent); text-decoration: none; }

        /* ── Global utilities ── */
        .text-primary-custom { color: var(--cactus-green) !important; }
        .bg-primary-custom   { background-color: var(--cactus-green) !important; }
        .badge.bg-primary    { background-color: var(--cactus-green) !important; }

        @media (max-width: 991px) {
            .nav-link::after { display: none; }
            .navbar-collapse { background: #fff; padding: 1rem; border-radius: 8px; margin-top: .5rem; box-shadow: 0 8px 24px rgba(0,0,0,.12); }
            .cp-navbar.scrolled .navbar-collapse { background: var(--cactus-dark); }
        }
        @media (max-width: 576px) {
            .navbar-brand { font-size: 1.2rem; }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg cp-navbar fixed-top" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('frontend.home') }}">
                <img src="{{ asset('img/logo_cactus.png') }}" alt="Hôtel Cactus Palace"
                     style="height:42px;width:auto;">
                <span>Le <span style="color:var(--gold-accent)">Cactus</span> Hotel</span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('frontend.home') ? 'active' : '' }}"
                           href="{{ route('frontend.home') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('frontend.rooms*') ? 'active' : '' }}"
                           href="{{ route('frontend.rooms') }}">Chambres &amp; Suites</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('frontend.restaurant*') ? 'active' : '' }}"
                           href="{{ route('frontend.restaurant') }}">Restaurant</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('frontend.services*') ? 'active' : '' }}"
                           href="{{ route('frontend.services') }}">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('frontend.contact*') ? 'active' : '' }}"
                           href="{{ route('frontend.contact') }}">Contact</a>
                    </li>

                    @auth
                    <li class="nav-item ms-lg-2">
                        <a href="{{ route('dashboard.index') }}" class="btn btn-outline-primary-custom">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>
                    @else
                    <li class="nav-item ms-lg-2">
                        <a href="{{ route('login') }}" class="btn btn-primary-custom">
                            <i class="fas fa-sign-in-alt me-1"></i> Connexion
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main style="padding-top: 72px;">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer pt-5 pb-0 mt-5">
        <div class="container">
            <div class="row gy-4 text-center">

                {{-- Brand column --}}
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                        <img src="{{ asset('img/logo_cactus.png') }}" alt="Cactus Palace" style="height:40px;filter:brightness(10)">
                        <h4 class="mb-0" style="color:#fff;font-size:1.4rem;">Cactus <span style="color:var(--gold-accent)">Palace</span></h4>
                    </div>
                    <p>Un havre de luxe 5 étoiles au cœur de Cotonou, offrant des services d'exception dans un cadre raffiné.</p>
                    <div class="d-flex align-items-center justify-content-center gap-1 mt-3" style="color:var(--gold-accent);font-size:.82rem;letter-spacing:1px;">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        <span class="ms-1">Hôtel 5 étoiles</span>
                    </div>
                    <div class="social-icons mt-3">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                {{-- Quick links --}}
                <div class="col-lg-2 col-md-6 col-6">
                    <h5>Navigation</h5>
                    <a href="{{ route('frontend.home') }}" class="footer-link">Accueil</a>
                    <a href="{{ route('frontend.rooms') }}" class="footer-link">Chambres &amp; Suites</a>
                    <a href="{{ route('frontend.restaurant') }}" class="footer-link">Restaurant</a>
                    <a href="{{ route('frontend.services') }}" class="footer-link">Services</a>
                    <a href="{{ route('frontend.contact') }}" class="footer-link">Contact</a>
                    <a href="{{ route('frontend.reservation') }}" class="footer-link">Réservation</a>
                </div>

                {{-- Services --}}
                <div class="col-lg-2 col-md-6 col-6">
                    <h5>Services</h5>
                    <a href="#" class="footer-link">Spa &amp; Bien-être</a>
                    <a href="#" class="footer-link">Piscine</a>
                    <a href="#" class="footer-link">Salle de sport</a>
                    <a href="#" class="footer-link">Conciergerie</a>
                    <a href="#" class="footer-link">Room Service</a>
                    <a href="#" class="footer-link">Transferts</a>
                </div>

                {{-- Contact --}}
                <div class="col-lg-4 col-md-6 footer-contact">
                    <h5>Contact</h5>
                    <p><i class="fas fa-map-marker-alt contact-icon"></i>Haie Vive, Cotonou, Bénin</p>
                    <p><i class="fas fa-phone contact-icon"></i>+229 01 XX XX XX XX</p>
                    <p><i class="fab fa-whatsapp contact-icon"></i>+229 01 XX XX XX XX</p>
                    <p><i class="fas fa-envelope contact-icon"></i>contact@cactushotel.com</p>
                    <p><i class="fas fa-clock contact-icon"></i>Réception 24h/24 — 7j/7</p>
                </div>

            </div>

            <hr class="footer-divider my-4">

            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center pb-4 footer-bottom">
                <p class="mb-1 mb-sm-0">&copy; {{ date('Y') }} Cactus Palace · Haie Vive, Cotonou. Tous droits réservés.</p>
                <p class="mb-0">Conçu avec <i class="fas fa-heart" style="color:var(--gold-accent)"></i> pour l'excellence</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery pour le frontend -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 pour les alertes utilisateur -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Scripts personnalisés -->
    @stack('scripts')

    <script>
    (function(){
        var nav = document.getElementById('mainNavbar');
        if (!nav) return;
        function onScroll() {
            nav.classList.toggle('scrolled', window.scrollY > 50);
        }
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    })();
    </script>
</body>
</html>