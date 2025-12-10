@extends('frontend.layouts.master')

@section('title', 'Hôtel Luxury Palace - Accueil')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 mb-4">Bienvenue au Luxury Palace</h1>
                    <p class="lead mb-4">Découvrez le luxe absolu dans notre hôtel 5 étoiles au cœur de Paris</p>
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
                <h2 class="mb-3" style="color: #2E7D32;">Nos Services Exceptionnels</h2>
                <p class="text-muted">Profitez d'une expérience unique avec nos services haut de gamme</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 text-center h-100 shadow-sm" style="background-color: #F1F8E9; border: 1px solid #C8E6C9;">
                        <div class="card-body">
                            <div class="rounded-circle p-3 d-inline-block mb-3" style="background-color: #4CAF50;">
                                <i class="fas fa-utensils fa-2x text-white"></i>
                            </div>
                            <h4 style="color: #2E7D32;">Restaurant Gastronomique</h4>
                            <p class="text-muted">Cuisine française raffinée préparée par nos chefs étoilés.</p>
                            <a href="{{ route('frontend.restaurant') }}" class="btn btn-link" style="color: #4CAF50;">
                                Voir le menu <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-0 text-center h-100 shadow-sm" style="background-color: #F1F8E9; border: 1px solid #C8E6C9;">
                        <div class="card-body">
                            <div class="rounded-circle p-3 d-inline-block mb-3" style="background-color: #4CAF50;">
                                <i class="fas fa-spa fa-2x text-white"></i>
                            </div>
                            <h4 style="color: #2E7D32;">Spa & Bien-être</h4>
                            <p class="text-muted">Centre de bien-être avec piscine, sauna et massages.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-0 text-center h-100 shadow-sm" style="background-color: #F1F8E9; border: 1px solid #C8E6C9;">
                        <div class="card-body">
                            <div class="rounded-circle p-3 d-inline-block mb-3" style="background-color: #4CAF50;">
                                <i class="fas fa-concierge-bell fa-2x text-white"></i>
                            </div>
                            <h4 style="color: #2E7D32;">Service 24h/24</h4>
                            <p class="text-muted">Notre équipe est disponible à tout moment pour vous servir.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Chambres en vedette -->
    <section class="py-5" style="background-color: #E8F5E9;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="mb-3" style="color: #2E7D32;">Chambres & Suites</h2>
                <p class="text-muted">Découvrez nos chambres luxueuses</p>
            </div>
            
            <div class="row g-4">
                @foreach($featuredRooms as $room)
                <div class="col-md-4">
                    <div class="card room-card h-100 border-0 shadow-sm">
                        <img src="{{ $room->firstImage() }}" 
                             class="card-img-top" 
                             alt="{{ $room->type->name ?? 'Chambre' }}"
                             style="height: 250px; object-fit: cover;">
                        
                        <div class="card-body">
                            <h5 class="card-title" style="color: #2E7D32;">{{ $room->type->name ?? 'Chambre Standard' }}</h5>
                            
                            <div class="mb-2">
                                @if($room->roomStatus)
                                    @php
                                        $statusColor = '#4CAF50'; // Vert par défaut
                                        if($room->roomStatus->code == 'OCC') {
                                            $statusColor = '#F44336'; // Rouge pour occupé
                                        } elseif($room->roomStatus->code == 'MNT') {
                                            $statusColor = '#FF9800'; // Orange pour maintenance
                                        }
                                    @endphp
                                    <span class="badge" style="background-color: {{ $statusColor }}; color: white;">
                                        {{ $room->roomStatus->name }}
                                    </span>
                                @endif
                                <span class="badge ms-1" style="background-color: #81C784; color: white;">
                                    {{ $room->capacity }} personne{{ $room->capacity > 1 ? 's' : '' }}
                                </span>
                            </div>
                            
                            @if($room->description)
                            <p class="card-text text-muted mb-3">
                                {{ Str::limit($room->description, 100) }}
                            </p>
                            @endif
                            
                            @if($room->view)
                            <p class="small text-muted mb-2">
                                <i class="fas fa-binoculars me-1"></i> Vue: {{ $room->view }}
                            </p>
                            @endif
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="h5 mb-0" style="color: #4CAF50;">
                                    {{ number_format($room->price, 0) }} € / nuit
                                </span>
                                <a href="{{ route('frontend.room.details', $room->id) }}" class="btn" style="background-color: #4CAF50; border-color: #4CAF50; color: white;">
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
                <a href="{{ route('frontend.rooms') }}" class="btn btn-lg" style="color: #4CAF50; border-color: #4CAF50; background-color: transparent;">
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
                    <h2 class="mb-4" style="color: #2E7D32;">Notre Restaurant Gastronomique</h2>
                    <p class="lead mb-4" style="color: #424242;">Découvrez une expérience culinaire exceptionnelle dans notre restaurant étoilé.</p>
                    <p style="color: #616161;">Notre chef étoilé vous propose une cuisine française raffinée avec des produits frais et locaux.</p>
                    <a href="{{ route('frontend.restaurant') }}" class="btn mt-3" style="background-color: #4CAF50; border-color: #4CAF50; color: white;">
                        <i class="fas fa-utensils me-2"></i> Découvrir le menu
                    </a>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                         alt="Restaurant" 
                         class="img-fluid rounded shadow" 
                         style="border: 3px solid #C8E6C9;">
                </div>
            </div>
        </div>
    </section>

    <!-- Témoignages -->
    <section class="py-5" style="background-color: #F1F8E9;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="mb-3" style="color: #2E7D32;">Ce que disent nos clients</h2>
                <p class="text-muted">Découvrez les expériences de nos clients satisfaits</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100" style="background-color: white; border-left: 4px solid #4CAF50;">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle overflow-hidden me-3" style="width: 50px; height: 50px;">
                                    <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Client" class="img-fluid">
                                </div>
                                <div>
                                    <h6 class="mb-0" style="color: #2E7D32;">Marie Dubois</h6>
                                    <small class="text-muted">Paris, France</small>
                                </div>
                            </div>
                            <p class="card-text" style="color: #616161;">
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
                    <div class="card border-0 shadow-sm h-100" style="background-color: white; border-left: 4px solid #4CAF50;">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle overflow-hidden me-3" style="width: 50px; height: 50px;">
                                    <img src="https://randomuser.me/api/portraits/men/54.jpg" alt="Client" class="img-fluid">
                                </div>
                                <div>
                                    <h6 class="mb-0" style="color: #2E7D32;">Thomas Martin</h6>
                                    <small class="text-muted">Lyon, France</small>
                                </div>
                            </div>
                            <p class="card-text" style="color: #616161;">
                                "Le restaurant est sublime, une véritable expérience gastronomique. À refaire absolument !"
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
                    <div class="card border-0 shadow-sm h-100" style="background-color: white; border-left: 4px solid #4CAF50;">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle overflow-hidden me-3" style="width: 50px; height: 50px;">
                                    <img src="https://randomuser.me/api/portraits/women/67.jpg" alt="Client" class="img-fluid">
                                </div>
                                <div>
                                    <h6 class="mb-0" style="color: #2E7D32;">Sophie Lambert</h6>
                                    <small class="text-muted">Bordeaux, France</small>
                                </div>
                            </div>
                            <p class="card-text" style="color: #616161;">
                                "Le spa est incroyable, un véritable havre de paix. Personnel très attentionné."
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
@endsection

@push('styles')
<style>
.hero-section {
    background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 150px 0;
}

.room-card {
    transition: transform 0.3s ease;
    border-radius: 10px;
    overflow: hidden;
    background-color: white;
}

.room-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(76, 175, 80, 0.2) !important;
}

.btn-primary-custom {
    background-color: #4CAF50;
    border-color: #4CAF50;
    color: white;
}

.btn-primary-custom:hover {
    background-color: #388E3C;
    border-color: #388E3C;
}

/* Animations */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

/* Badges personnalisés */
.badge {
    padding: 0.35em 0.65em;
    font-weight: 500;
}

/* Boutons avec effet hover */
.btn[style*="background-color: #4CAF50"]:hover {
    background-color: #388E3C !important;
    border-color: #388E3C !important;
}

.btn[style*="color: #4CAF50"]:hover {
    background-color: #4CAF50 !important;
    color: white !important;
}

/* Section backgrounds */
section:nth-child(even) {
    background-color: #F1F8E9;
}

section:nth-child(odd):not(.hero-section) {
    background-color: white;
}
</style>
@endpush