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
                <h2 class="mb-3">Nos Services Exceptionnels</h2>
                <p class="text-muted">Profitez d'une expérience unique avec nos services haut de gamme</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 text-center h-100">
                        <div class="card-body">
                            <div class="bg-primary-custom rounded-circle p-3 d-inline-block mb-3">
                                <i class="fas fa-utensils fa-2x text-white"></i>
                            </div>
                            <h4>Restaurant Gastronomique</h4>
                            <p class="text-muted">Cuisine française raffinée préparée par nos chefs étoilés.</p>
                            <a href="{{ route('frontend.restaurant') }}" class="btn btn-link text-primary-custom">
                                Voir le menu <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-0 text-center h-100">
                        <div class="card-body">
                            <div class="bg-primary-custom rounded-circle p-3 d-inline-block mb-3">
                                <i class="fas fa-spa fa-2x text-white"></i>
                            </div>
                            <h4>Spa & Bien-être</h4>
                            <p class="text-muted">Centre de bien-être avec piscine, sauna et massages.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-0 text-center h-100">
                        <div class="card-body">
                            <div class="bg-primary-custom rounded-circle p-3 d-inline-block mb-3">
                                <i class="fas fa-concierge-bell fa-2x text-white"></i>
                            </div>
                            <h4>Service 24h/24</h4>
                            <p class="text-muted">Notre équipe est disponible à tout moment pour vous servir.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Chambres en vedette -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="mb-3">Chambres & Suites</h2>
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
                            <h5 class="card-title">{{ $room->type->name ?? 'Chambre Standard' }}</h5>
                            
                            <div class="mb-2">
                                @if($room->roomStatus)
                                    <span class="badge bg-{{ $room->roomStatus->code == 'AVL' ? 'success' : ($room->roomStatus->code == 'OCC' ? 'danger' : 'warning') }}">
                                        {{ $room->roomStatus->name }}
                                    </span>
                                @endif
                                <span class="badge bg-info ms-1">
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
                                <span class="h5 text-primary-custom mb-0">
                                    {{ number_format($room->price, 0) }} € / nuit
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
                    <h2 class="mb-4">Notre Restaurant Gastronomique</h2>
                    <p class="lead mb-4">Découvrez une expérience culinaire exceptionnelle dans notre restaurant étoilé.</p>
                    <p>Notre chef étoilé vous propose une cuisine française raffinée avec des produits frais et locaux.</p>
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
@endsection

@push('styles')
<style>
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
    border-radius: 10px;
    overflow: hidden;
}

.room-card:hover {
    transform: translateY(-5px);
}

.bg-primary-custom {
    background-color: #c29a5c !important;
}

.text-primary-custom {
    color: #c29a5c !important;
}

.btn-primary-custom {
    background-color: #c29a5c;
    border-color: #c29a5c;
    color: white;
}

.btn-primary-custom:hover {
    background-color: #b08a4c;
    border-color: #b08a4c;
}

.btn-outline-primary-custom {
    color: #c29a5c;
    border-color: #c29a5c;
}

.btn-outline-primary-custom:hover {
    background-color: #c29a5c;
    border-color: #c29a5c;
    color: white;
}
</style>
@endpush