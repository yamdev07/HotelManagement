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
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Styles personnalisés -->
    <style>
        :root {
            --primary-color: #4CAF50; /* Vert principal */
            --secondary-color: #81C784; /* Vert secondaire */
            --light-color: #F1F8E9; /* Vert très clair */
            --dark-color: #2E7D32; /* Vert foncé */
            --accent-color: #C8E6C9; /* Vert accent */
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            color: #333;
            background-color: var(--light-color);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--dark-color);
        }
        
        .bg-primary-custom {
            background-color: var(--primary-color) !important;
        }
        
        .text-primary-custom {
            color: var(--primary-color) !important;
        }
        
        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary-custom:hover {
            background-color: #388E3C;
            border-color: #388E3C;
        }
        
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-color) !important;
        }
        
        .hero-section {
            background: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 180px 0;
            position: relative;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 1;
        }
        
        .hero-section .container {
            position: relative;
            z-index: 2;
        }
        
        .room-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .room-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.2);
        }
        
        .footer {
            background-color: var(--dark-color);
            color: white;
        }
        
        .social-icons a {
            color: white;
            margin: 0 10px;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }
        
        .social-icons a:hover {
            color: var(--secondary-color);
        }
        
        .nav-link {
            color: var(--dark-color) !important;
            font-weight: 500;
        }
        
        .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        .btn-outline-primary-custom {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary-custom:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        /* Styles pour les formulaires */
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(129, 199, 132, 0.25);
        }
        
        /* Cartes et conteneurs */
        .card {
            border: 1px solid rgba(76, 175, 80, 0.1);
            background-color: white;
        }
        
        .card-header {
            background-color: var(--accent-color);
            border-bottom: 1px solid rgba(76, 175, 80, 0.1);
        }
        
        /* Tables */
        .table-hover tbody tr:hover {
            background-color: var(--accent-color);
        }
        
        /* Alertes */
        .alert-success {
            background-color: var(--accent-color);
            border-color: var(--secondary-color);
            color: var(--dark-color);
        }
        
        /* Badges */
        .badge.bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        /* Boutons Hero Section */
        .hero-section .btn-primary-custom {
            background-color: #2E7D32;
            border-color: #2E7D32;
        }
        
        .hero-section .btn-primary-custom:hover {
            background-color: #1B5E20;
            border-color: #1B5E20;
        }
        
        .hero-section .btn-outline-light {
            border-color: white;
            color: white;
        }
        
        .hero-section .btn-outline-light:hover {
            background-color: white;
            color: var(--dark-color);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('frontend.home') }}">
                <img src="{{ asset('img/logo_cactus.png') }}"
                    alt="Hôtel Le Cactus"
                    class="me-2"
                    style="height: 45px; width: auto;">
                <span>Le cactus Hotel</span>
            </a>


            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.home') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.rooms') }}">Chambres & Suites</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.restaurant') }}">Restaurant</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.services') }}">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.contact') }}">Contact</a>
                    </li>
                    
                    <!-- Bouton dashboard pour les utilisateurs connectés -->
                    @auth
                    <li class="nav-item ms-2">
                        <a href="{{ route('dashboard.index') }}" class="btn btn-outline-primary-custom">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>
                    @else
                    <li class="nav-item ms-2">
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
    <main style="padding-top: 76px;">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h4 class="mb-3">Cactus Palace</h4>
                    <p>Un hôtel 5 étoiles offrant des services exceptionnels dans un cadre luxueux et paisible.</p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3">Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('frontend.home') }}" class="text-white text-decoration-none">Accueil</a></li>
                        <li><a href="{{ route('frontend.rooms') }}" class="text-white text-decoration-none">Chambres</a></li>
                        <li><a href="{{ route('frontend.restaurant') }}" class="text-white text-decoration-none">Restaurant</a></li>
                        <li><a href="{{ route('frontend.services') }}" class="text-white text-decoration-none">Services</a></li>
                        <li><a href="{{ route('frontend.contact') }}" class="text-white text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3">Contact & Localisation</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Haie Vive, Cotonou, Bénin</p>
                    <p><i class="fas fa-phone me-2"></i> +229 01 XX XX XX XX</p>
                    <p><i class="fas fa-phone me-2"></i> +229 02 XX XX XX XX</p>
                    <p><i class="fas fa-envelope me-2"></i> contact@cactushotel.com</p>
                    <p><i class="fas fa-envelope me-2"></i> reservation@cactushotel.com</p>
                </div>
            </div>
            
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Cactus Palace - Haie Vive, Cotonou. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personnalisés -->
    @stack('scripts')
</body>
</html>