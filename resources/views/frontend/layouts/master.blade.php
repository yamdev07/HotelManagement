<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Hôtel de luxe - Réservez votre séjour dans notre établissement 5 étoiles">
    <title>@yield('title', 'Hôtel Luxury Palace')</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Styles personnalisés -->
    <style>
        :root {
            --primary-color: #c29a5c;
            --secondary-color: #1a3c5e;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--dark-color);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
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
            background-color: #b08a4c;
            border-color: #b08a4c;
        }
        
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 150px 0;
        }
        
        .room-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .room-card:hover {
            transform: translateY(-10px);
        }
        
        .footer {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .social-icons a {
            color: white;
            margin: 0 10px;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }
        
        .social-icons a:hover {
            color: var(--primary-color);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand text-primary-custom" href="{{ route('frontend.home') }}">
                <i class="fas fa-hotel me-2"></i>Luxury Palace
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
                    <h4 class="mb-3">Luxury Palace</h4>
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
                    <h5 class="mb-3">Contact</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i> 123 Avenue des Champs-Élysées, 75008 Paris</p>
                    <p><i class="fas fa-phone me-2"></i> +33 1 23 45 67 89</p>
                    <p><i class="fas fa-envelope me-2"></i> contact@luxurypalace.com</p>
                </div>
            </div>
            
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Luxury Palace. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personnalisés -->
    @stack('scripts')
</body>
</html>