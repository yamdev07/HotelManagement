@extends('frontend.layouts.master')

@section('title', 'Hôtel Luxury Palace - Accueil')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 mb-4">Bienvenue au Cactus Palace</h1>
                    <p class="lead mb-4">Découvrez le luxe absolu dans notre hôtel 5 étoiles au cœur de Cotonou</p>
                    <a href="{{ route('frontend.rooms') }}" class="btn btn-primary-custom btn-lg me-2">
                        <i class="fas fa-bed me-1"></i> Voir nos chambres
                    </a>
                    <a href="{{ route('frontend.contact') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-phone me-1"></i> Réserver
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Services -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="mb-3">Nos Services Exceptionnels</h2>
                <p class="text-muted">Profitez d'une expérience unique avec nos services haut de gamme</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 text-center h-100 shadow-sm">
                        <div class="card-body">
                            <div class="bg-primary-custom rounded-circle p-3 d-inline-block mb-3">
                                <i class="fas fa-utensils fa-2x text-white"></i>
                            </div>
                            <h4 class="text-primary-custom">Restaurant Gastronomique</h4>
                            <p class="text-muted">Cuisine raffinée préparée par nos chefs.</p>
                            <a href="{{ route('frontend.restaurant') }}" class="btn btn-link text-primary-custom">
                                Voir le menu <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-0 text-center h-100 shadow-sm">
                        <div class="card-body">
                            <div class="bg-primary-custom rounded-circle p-3 d-inline-block mb-3">
                                <i class="fas fa-spa fa-2x text-white"></i>
                            </div>
                            <h4 class="text-primary-custom">Spa & Bien-être</h4>
                            <p class="text-muted">Centre de bien-être avec piscine, sauna et massages.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-0 text-center h-100 shadow-sm">
                        <div class="card-body">
                            <div class="bg-primary-custom rounded-circle p-3 d-inline-block mb-3">
                                <i class="fas fa-concierge-bell fa-2x text-white"></i>
                            </div>
                            <h4 class="text-primary-custom">Service 24h/24</h4>
                            <p class="text-muted">Notre équipe est disponible à tout moment pour vous servir.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Chambres en vedette -->
    <section class="py-5 bg-light-green">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="mb-3">Chambres & Suites</h2>
                <p class="text-muted">Découvrez nos chambres luxueuses</p>
            </div>
            
            <div class="row g-4">
                @foreach($featuredRooms as $room)
                <div class="col-md-4">
                    <div class="card room-card h-100 border-0 shadow-sm">
                        <img src="{{ $room->first_image_url }}" 
                             class="card-img-top" 
                             alt="{{ $room->type->name ?? 'Chambre' }}"
                             style="height: 250px; object-fit: cover;">
                        
                        <div class="card-body">
                            <h5 class="card-title text-primary-custom">{{ $room->type->name ?? 'Chambre Standard' }}</h5>
                            
                            <div class="mb-2">
                                @if($room->roomStatus)
                                    <span class="badge bg-{{ $room->room_status_id == 1 ? 'success' : 'warning' }}">
                                        {{ $room->roomStatus->name }}
                                    </span>
                                @endif
                                <span class="badge bg-info ms-1">
                                    {{ $room->capacity }} personne{{ $room->capacity > 1 ? 's' : '' }}
                                </span>
                            </div>
                            
                            @if($room->short_description)
                            <p class="card-text text-muted mb-3">
                                {{ $room->short_description }}
                            </p>
                            @endif
                            
                            @if($room->view)
                            <p class="small text-muted mb-2">
                                <i class="fas fa-binoculars me-1"></i> Vue: {{ $room->view }}
                            </p>
                            @endif
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="h5 mb-0" style="color: #4CAF50;">
                                    {{ number_format($room->price, 0) }} Fcfa / nuit
                                </span>
                                <a href="{{ route('frontend.room.details', $room->id) }}" class="btn btn-primary-custom">
                                    <i class="fas fa-eye me-1"></i> Détails
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($featuredRooms->isNotEmpty())
            <div class="text-center mt-4">
                <a href="{{ route('frontend.rooms') }}" class="btn btn-outline-primary-custom btn-lg">
                    Voir toutes les chambres <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
            @endif
        </div>
    </section>

    <!-- Restaurant -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="mb-4 text-primary-custom">Notre Restaurant Gastronomique</h2>
                    <p class="lead mb-4">Découvrez une expérience culinaire exceptionnelle dans notre restaurant.</p>
                    <p>Notre chef vous propose une cuisine raffinée avec des produits frais et locaux.</p>
                    <a href="{{ route('frontend.restaurant') }}" class="btn btn-primary-custom mt-3">
                        <i class="fas fa-utensils me-2"></i> Découvrir le menu
                    </a>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                         alt="Restaurant" 
                         class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Témoignages -->
    <section class="py-5 bg-light-green">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="mb-3 text-primary-custom">Ce que disent nos clients</h2>
                <p class="text-muted">Découvrez les expériences de nos clients satisfaits</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #2E7D32;">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle overflow-hidden me-3" style="width: 50px; height: 50px;">
                                    <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Client" class="img-fluid">
                                </div>
                                <div>
                                    <h6 class="mb-0" style="color: #2E7D32;">Marie Dubois</h6>
                                    <small class="text-muted">Cotonou, Bénin</small>
                                </div>
                            </div>
                            <p class="card-text text-muted">
                                "Un séjour exceptionnel ! Le service est impeccable et les chambres sont magnifiques."
                            </p>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #2E7D32;">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle overflow-hidden me-3" style="width: 50px; height: 50px;">
                                    <img src="https://randomuser.me/api/portraits/men/54.jpg" alt="Client" class="img-fluid">
                                </div>
                                <div>
                                    <h6 class="mb-0" style="color: #2E7D32;">Thomas Martin</h6>
                                    <small class="text-muted">Cotonou, Bénin</small>
                                </div>
                            </div>
                            <p class="card-text text-muted">
                                "Le restaurant est sublime, une véritable expérience gastronomique."
                            </p>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #2E7D32;">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle overflow-hidden me-3" style="width: 50px; height: 50px;">
                                    <img src="https://randomuser.me/api/portraits/women/67.jpg" alt="Client" class="img-fluid">
                                </div>
                                <div>
                                    <h6 class="mb-0" style="color: #2E7D32;">Sophie Lambert</h6>
                                    <small class="text-muted">Cotonou, Bénin</small>
                                </div>
                            </div>
                            <p class="card-text text-muted">
                                "Le spa est incroyable, un véritable havre de paix."
                            </p>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact rapide -->
    <section class="py-5 bg-primary-custom text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="mb-3">Prêt à réserver votre séjour de rêve ?</h2>
                    <p class="mb-0">Contactez-nous dès maintenant pour réserver votre chambre au Luxury Palace.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('frontend.contact') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-phone me-2"></i> Nous contacter
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
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
    background: rgba(0, 0, 0, 0.3); /* Légère superposition sombre pour meilleur contraste */
    z-index: 1;
}

.hero-section .container {
    position: relative;
    z-index: 2;
}

.room-card {
    transition: transform 0.3s ease;
    border-radius: 10px;
    overflow: hidden;
    background-color: white;
}

.room-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(46, 125, 50, 0.15) !important;
}

/* Couleurs vertes */
.bg-primary-custom {
    background-color: #2E7D32 !important; /* Vert foncé élégant */
}

.text-primary-custom {
    color: #2E7D32 !important;
}

.btn-primary-custom {
    background-color: #2E7D32;
    border-color: #2E7D32;
    color: white;
}

.btn-primary-custom:hover {
    background-color: #1B5E20;
    border-color: #1B5E20;
}

.btn-outline-primary-custom {
    color: #2E7D32;
    border-color: #2E7D32;
}

.btn-outline-primary-custom:hover {
    background-color: #2E7D32;
    border-color: #2E7D32;
    color: white;
}

/* Arrière-plans verts clairs */
.bg-light-green {
    background-color: #F1F8E9 !important;
}

/* Bouton contact rapide */
.bg-primary-custom .btn-light {
    background-color: white;
    border-color: white;
    color: #2E7D32;
}

.bg-primary-custom .btn-light:hover {
    background-color: #F1F8E9;
    border-color: #F1F8E9;
}

/* Badges */
.badge.bg-success {
    background-color: #4CAF50 !important;
}

.badge.bg-warning {
    background-color: #FF9800 !important;
}

.badge.bg-info {
    background-color: #2196F3 !important;
}

/* Cards témoignages */
.card[style*="border-left: 4px solid #2E7D32"]:hover {
    border-left: 4px solid #4CAF50 !important;
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
    color: #2E7D32;
}
</style>
@endpush