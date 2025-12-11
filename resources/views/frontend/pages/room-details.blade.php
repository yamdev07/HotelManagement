@extends('frontend.layouts.master')

@section('title', 'Détails de la Chambre - Hôtel Cactus Palace')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section-room-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 mb-4">Détails de la Chambre</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}" style="color: white;">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('frontend.rooms') }}" style="color: white;">Chambres</a></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: #C8E6C9;">Chambre {{ $room->number }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Détails de la chambre -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Galerie d'images -->
                    <div class="room-gallery mb-5">
                        <div class="main-image mb-3">
                            <img src="{{ $room->firstImage() }}" 
                                 alt="Chambre {{ $room->number }}" 
                                 class="img-fluid rounded shadow-sm"
                                 style="width: 100%; height: 400px; object-fit: cover;">
                        </div>
                        
                        @if($room->image && $room->image->count() > 1)
                        <div class="row g-2">
                            @foreach($room->image as $image)
                            <div class="col-3">
                                <img src="{{ $image->getRoomImage() ?? $image->url }}" 
                                     alt="Chambre {{ $room->number }} - Image {{ $loop->iteration }}"
                                     class="img-fluid rounded"
                                     style="height: 100px; width: 100%; object-fit: cover; cursor: pointer;"
                                     onclick="changeMainImage('{{ $image->getRoomImage() ?? $image->url }}')">
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <!-- Informations de base -->
                    <div class="room-basic-info mb-5">
                        <h2 style="color: #2E7D32;" class="mb-3">Chambre {{ $room->number }}</h2>
                        
                        <div class="d-flex flex-wrap gap-3 mb-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 40px; height: 40px; background-color: #E8F5E9;">
                                    <i class="fas fa-users" style="color: #2E7D32;"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Capacité</small>
                                    <strong style="color: #2E7D32;">{{ $room->capacity }} personne{{ $room->capacity > 1 ? 's' : '' }}</strong>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 40px; height: 40px; background-color: #E8F5E9;">
                                    <i class="fas fa-bed" style="color: #2E7D32;"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Type</small>
                                    <strong style="color: #2E7D32;">{{ $room->type->name ?? 'Standard' }}</strong>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 40px; height: 40px; background-color: #E8F5E9;">
                                    <i class="fas fa-eye" style="color: #2E7D32;"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Vue</small>
                                    <strong style="color: #2E7D32;">{{ $room->view ?? 'Non spécifiée' }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Statut de la chambre -->
                        <div class="mb-4">
                            @if($room->roomStatus)
                                @php
                                    $statusColor = '#4CAF50'; // Vert pour disponible
                                    $statusText = 'Disponible';
                                    if($room->roomStatus->code == 'OCC') {
                                        $statusColor = '#F44336';
                                        $statusText = 'Occupée';
                                    } elseif($room->roomStatus->code == 'MNT') {
                                        $statusColor = '#FF9800';
                                        $statusText = 'Maintenance';
                                    }
                                @endphp
                                <span class="badge" style="background-color: {{ $statusColor }}; color: white; padding: 8px 16px; font-size: 1rem;">
                                    <i class="fas fa-circle fa-xs me-2"></i>{{ $statusText }}
                                </span>
                            @endif
                        </div>

                        <!-- Description -->
                        @if($room->description)
                        <div class="room-description mb-5">
                            <h4 style="color: #2E7D32;" class="mb-3">
                                <i class="fas fa-align-left me-2"></i>Description
                            </h4>
                            <p class="text-muted" style="line-height: 1.8;">
                                {{ $room->description }}
                            </p>
                        </div>
                        @endif
                    </div>

                    <!-- Équipements de la chambre -->
                    <div class="room-facilities mb-5">
                        <h4 style="color: #2E7D32;" class="mb-4">
                            <i class="fas fa-concierge-bell me-2"></i>Équipements & Services
                        </h4>
                        
                        @if($room->facilities && $room->facilities->count() > 0)
                        <div class="row g-3">
                            @foreach($room->facilities as $facility)
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100" style="background-color: #F1F8E9; border-left: 3px solid #4CAF50;">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 50px; height: 50px; background-color: #4CAF50;">
                                                <i class="fas fa-{{ $facility->icon ?? 'check' }} fa-lg text-white"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1" style="color: #2E7D32;">{{ $facility->name }}</h6>
                                                @if($facility->detail)
                                                <p class="text-muted mb-0 small">{{ $facility->detail }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <!-- Équipements par défaut -->
                        <div class="row g-3">
                            @php
                                $defaultFacilities = [
                                    ['icon' => 'wifi', 'name' => 'Wi-Fi Gratuit', 'detail' => 'Connexion internet haute vitesse'],
                                    ['icon' => 'tv', 'name' => 'Télévision HD', 'detail' => 'Chaînes internationales'],
                                    ['icon' => 'snowflake', 'name' => 'Climatisation', 'detail' => 'Contrôle individuel'],
                                    ['icon' => 'bath', 'name' => 'Salle de bain', 'detail' => 'Douche privée avec produits'],
                                    ['icon' => 'coffee', 'name' => 'Machine à café', 'detail' => 'Nespresso avec capsules'],
                                    ['icon' => 'key', 'name' => 'Coffre-fort', 'detail' => 'Sécurité numérique'],
                                ];
                            @endphp
                            
                            @foreach($defaultFacilities as $facility)
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100" style="background-color: #F1F8E9; border-left: 3px solid #4CAF50;">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 50px; height: 50px; background-color: #4CAF50;">
                                                <i class="fas fa-{{ $facility['icon'] }} fa-lg text-white"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1" style="color: #2E7D32;">{{ $facility['name'] }}</h6>
                                                <p class="text-muted mb-0 small">{{ $facility['detail'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar: Réservation & Prix -->
                <div class="col-lg-4">
                    <div class="sticky-top" style="top: 20px;">
                        <!-- Carte de réservation -->
                        <div class="card border-0 shadow-sm mb-4" style="border-top: 4px solid #4CAF50;">
                            <div class="card-body">
                                <h4 style="color: #2E7D32;" class="mb-4">
                                    <i class="fas fa-calendar-check me-2"></i>Réserver cette chambre
                                </h4>
                                
                                <div class="text-center mb-4">
                                    <div class="display-4" style="color: #4CAF50;">
                                        {{ number_format($room->price, 0) }} €
                                    </div>
                                    <small class="text-muted">par nuit</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label" style="color: #2E7D32; font-weight: 500;">
                                        <i class="fas fa-calendar-alt me-2"></i>Dates de séjour
                                    </label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="date" class="form-control" style="border-color: #C8E6C9;">
                                        </div>
                                        <div class="col-6">
                                            <input type="date" class="form-control" style="border-color: #C8E6C9;">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label" style="color: #2E7D32; font-weight: 500;">
                                        <i class="fas fa-user-friends me-2"></i>Nombre de personnes
                                    </label>
                                    <select class="form-select" style="border-color: #C8E6C9;">
                                        @for($i = 1; $i <= $room->capacity; $i++)
                                            <option value="{{ $i }}">{{ $i }} personne{{ $i > 1 ? 's' : '' }}</option>
                                        @endfor
                                    </select>
                                </div>
                                
                                <button class="btn w-100 mb-3" 
                                        style="background-color: #4CAF50; border-color: #4CAF50; color: white; padding: 12px;">
                                    <i class="fas fa-check-circle me-2"></i>Vérifier la disponibilité
                                </button>
                                
                                <a href="{{ route('frontend.contact') }}" class="btn w-100" 
                                   style="color: #4CAF50; border-color: #4CAF50; background-color: transparent;">
                                    <i class="fas fa-phone-alt me-2"></i>Nous contacter
                                </a>
                            </div>
                        </div>

                        <!-- Informations importantes -->
                        <div class="card border-0 shadow-sm" style="background-color: #F1F8E9; border-left: 3px solid #2E7D32;">
                            <div class="card-body">
                                <h5 style="color: #2E7D32;" class="mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Informations importantes
                                </h5>
                                
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-clock me-2" style="color: #4CAF50;"></i>
                                        <small>Check-in: 15:00 | Check-out: 12:00</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-ban me-2" style="color: #4CAF50;"></i>
                                        <small>Non-fumeur</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-child me-2" style="color: #4CAF50;"></i>
                                        <small>Enfants acceptés</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-paw me-2" style="color: #4CAF50;"></i>
                                        <small>Animaux sur demande</small>
                                    </li>
                                    <li>
                                        <i class="fas fa-credit-card me-2" style="color: #4CAF50;"></i>
                                        <small>Cartes de crédit acceptées</small>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Chambres similaires -->
    @if(isset($relatedRooms) && $relatedRooms->count() > 0)
    <section class="py-5" style="background-color: #E8F5E9;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 style="color: #2E7D32;">Chambres similaires</h2>
                <p class="text-muted">Découvrez d'autres chambres qui pourraient vous plaire</p>
            </div>
            
            <div class="row g-4">
                @foreach($relatedRooms as $relatedRoom)
                <div class="col-md-4">
                    <div class="card room-card h-100 border-0 shadow-sm" style="border-top: 4px solid #4CAF50;">
                        <img src="{{ $relatedRoom->firstImage() }}" 
                             class="card-img-top" 
                             alt="Chambre {{ $relatedRoom->number }}"
                             style="height: 200px; object-fit: cover; border-radius: 8px 8px 0 0;">
                        
                        <div class="card-body">
                            <h5 style="color: #2E7D32;">{{ $relatedRoom->type->name ?? 'Chambre' }} {{ $relatedRoom->number }}</h5>
                            
                            <div class="mb-3">
                                <span class="badge me-1" style="background-color: #81C784; color: white;">
                                    <i class="fas fa-user-friends me-1"></i>{{ $relatedRoom->capacity }}
                                </span>
                                @if($relatedRoom->roomStatus)
                                    @php
                                        $statusColor = '#4CAF50';
                                        if($relatedRoom->roomStatus->code == 'OCC') $statusColor = '#F44336';
                                        elseif($relatedRoom->roomStatus->code == 'MNT') $statusColor = '#FF9800';
                                    @endphp
                                    <span class="badge" style="background-color: {{ $statusColor }}; color: white;">
                                        {{ $relatedRoom->roomStatus->name }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h4 mb-0" style="color: #4CAF50;">
                                    {{ number_format($relatedRoom->price, 0) }} €
                                </span>
                                <a href="{{ route('frontend.room.details', $relatedRoom->id) }}" 
                                   class="btn" 
                                   style="background-color: #4CAF50; border-color: #4CAF50; color: white;">
                                    <i class="fas fa-eye me-1"></i> Voir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Call to Action -->
    <section class="py-5">
        <div class="container">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #E8F5E9, #C8E6C9);">
                <div class="card-body p-5 text-center">
                    <h3 style="color: #2E7D32;" class="mb-3">Vous avez des questions sur cette chambre ?</h3>
                    <p class="text-muted mb-4">Notre équipe est disponible pour vous aider à choisir la chambre parfaite pour votre séjour.</p>
                    <a href="{{ route('frontend.contact') }}" class="btn btn-lg me-3" 
                       style="background-color: #4CAF50; border-color: #4CAF50; color: white; padding: 12px 40px;">
                        <i class="fas fa-envelope me-2"></i> Nous contacter
                    </a>
                    <a href="{{ route('frontend.rooms') }}" class="btn btn-lg" 
                       style="color: #4CAF50; border-color: #4CAF50; background-color: transparent; padding: 12px 40px;">
                        <i class="fas fa-bed me-2"></i> Voir toutes les chambres
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
.hero-section-room-details {
    background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                url('https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 120px 0 80px;
}

.breadcrumb {
    background: transparent;
    padding: 0;
}

.breadcrumb-item.active {
    color: #C8E6C9 !important;
}

.room-card {
    transition: transform 0.3s ease;
    border-radius: 10px;
    overflow: hidden;
}

.room-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(76, 175, 80, 0.15) !important;
}

/* Style pour les formulaires */
.form-control:focus,
.form-select:focus {
    border-color: #4CAF50;
    box-shadow: 0 0 0 0.25rem rgba(76, 175, 80, 0.25);
}

/* Badges */
.badge {
    font-weight: 500;
    letter-spacing: 0.3px;
}

/* Boutons avec effet hover */
.btn[style*="background-color: #4CAF50"]:hover {
    background-color: #388E3C !important;
    border-color: #388E3C !important;
    transform: translateY(-2px);
}

.btn[style*="color: #4CAF50"]:hover {
    background-color: #4CAF50 !important;
    color: white !important;
    border-color: #4CAF50 !important;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section-room-details {
        padding: 80px 0 60px;
    }
    
    .hero-section-room-details h1 {
        font-size: 2.5rem;
    }
    
    .sticky-top {
        position: static !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Fonction pour changer l'image principale
function changeMainImage(src) {
    document.querySelector('.main-image img').src = src;
}

// Date picker - minimum aujourd'hui
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const dateInputs = document.querySelectorAll('input[type="date"]');
    
    dateInputs.forEach(input => {
        input.min = today;
    });
    
    // Animation des cartes
    const cards = document.querySelectorAll('.room-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endpush