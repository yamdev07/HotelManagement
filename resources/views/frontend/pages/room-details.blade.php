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
                            <img src="{{ $room->first_image_url }}" 
                                 alt="Chambre {{ $room->number }}" 
                                 class="img-fluid rounded shadow-sm"
                                 style="width: 100%; height: 400px; object-fit: cover;">
                        </div>
                        
                        @if($room->images && $room->images->count() > 1) {{-- images au pluriel --}}
                        <div class="row g-2">
                            @foreach($room->images as $image)
                            <div class="col-3">
                                <img src="{{ $image->getRoomImage() }}" 
                                     alt="Chambre {{ $room->number }} - Image {{ $loop->iteration }}"
                                     class="img-fluid rounded"
                                     style="height: 100px; width: 100%; object-fit: cover; cursor: pointer;"
                                     onclick="changeMainImage('{{ $image->getRoomImage() }}')">
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
                            
                            @if($room->size)
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 40px; height: 40px; background-color: #E8F5E9;">
                                    <i class="fas fa-expand-arrows-alt" style="color: #2E7D32;"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Surface</small>
                                    <strong style="color: #2E7D32;">{{ $room->size }} m²</strong>
                                </div>
                            </div>
                            @endif
                            
                            @if($room->view)
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 40px; height: 40px; background-color: #E8F5E9;">
                                    <i class="fas fa-eye" style="color: #2E7D32;"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Vue</small>
                                    <strong style="color: #2E7D32;">{{ $room->view }}</strong>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Statut de la chambre -->
                        <div class="mb-4">
                            <span class="badge" style="background-color: {{ $room->room_status_id == 1 ? '#4CAF50' : ($room->room_status_id == 4 ? '#F44336' : '#FF9800') }}; 
                                      color: white; padding: 8px 16px; font-size: 1rem;">
                                <i class="fas fa-circle fa-xs me-2"></i>{{ $room->roomStatus->name ?? 'Statut inconnu' }}
                            </span>
                            
                            @if($room->is_available_today)
                                <span class="badge bg-success ms-2" style="padding: 8px 16px; font-size: 1rem;">
                                    <i class="fas fa-check-circle me-2"></i>Disponible aujourd'hui
                                </span>
                            @elseif($room->next_available_date)
                                <span class="badge bg-warning ms-2" style="padding: 8px 16px; font-size: 1rem;">
                                    <i class="fas fa-calendar-alt me-2"></i>Disponible le {{ $room->next_available_date }}
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
                                                @if($facility->description)
                                                <p class="text-muted mb-0 small">{{ Str::limit($facility->description, 100) }}</p>
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
                                    ['icon' => 'wifi', 'name' => 'Wi-Fi Gratuit', 'description' => 'Connexion internet haute vitesse'],
                                    ['icon' => 'tv', 'name' => 'Télévision HD', 'description' => 'Chaînes internationales'],
                                    ['icon' => 'snowflake', 'name' => 'Climatisation', 'description' => 'Contrôle individuel'],
                                    ['icon' => 'bath', 'name' => 'Salle de bain privée', 'description' => 'Douche avec produits de toilette'],
                                    ['icon' => 'coffee', 'name' => 'Machine à café/thé', 'description' => 'Nespresso avec capsules gratuites'],
                                    ['icon' => 'key', 'name' => 'Coffre-fort', 'description' => 'Sécurité numérique personnelle'],
                                    ['icon' => 'phone', 'name' => 'Téléphone', 'description' => 'Ligne directe avec la réception'],
                                    ['icon' => 'wine-bottle', 'name' => 'Minibar', 'description' => 'Boissons et snacks'],
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
                                                <p class="text-muted mb-0 small">{{ $facility['description'] }}</p>
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
                                    <i class="fas fa-calendar-check me-2"></i>Tarifs & Réservation
                                </h4>
                                
                                <div class="text-center mb-4">
                                    <div class="display-4" style="color: #4CAF50;">
                                        {{ number_format($room->price, 0) }} Fcfa
                                    </div>
                                    <small class="text-muted">par nuit</small>
                                </div>
                                
                                @if($room->is_available_today)
                                <div class="alert alert-success mb-4" role="alert" style="background-color: #E8F5E9; border-color: #4CAF50;">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Cette chambre est disponible pour réservation.
                                </div>
                                @else
                                <div class="alert alert-warning mb-4" role="alert">
                                    <i class="fas fa-clock me-2"></i>
                                    {{ $room->next_available_date ? "Disponible à partir du {$room->next_available_date}" : 'Non disponible pour le moment' }}
                                </div>
                                @endif
                                
                                <div class="mb-3">
                                    <label class="form-label" style="color: #2E7D32; font-weight: 500;">
                                        <i class="fas fa-calendar-alt me-2"></i>Dates de séjour
                                    </label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="date" class="form-control" 
                                                   style="border-color: #C8E6C9;"
                                                   id="checkin-date"
                                                   min="{{ date('Y-m-d') }}">
                                        </div>
                                        <div class="col-6">
                                            <input type="date" class="form-control" 
                                                   style="border-color: #C8E6C9;"
                                                   id="checkout-date">
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
                                
                                @if($room->is_available_today)
                                <button class="btn w-100 mb-3" 
                                        style="background-color: #4CAF50; border-color: #4CAF50; color: white; padding: 12px;"
                                        onclick="checkAvailability()">
                                    <i class="fas fa-check-circle me-2"></i>Vérifier la disponibilité
                                </button>
                                @endif
                                
                                <a href="{{ route('frontend.contact') }}?room_id={{ $room->id }}" 
                                   class="btn w-100 mb-3" 
                                   style="color: #4CAF50; border-color: #4CAF50; background-color: transparent;">
                                    <i class="fas fa-phone-alt me-2"></i>Nous contacter pour réserver
                                </a>
                                
                                <div class="text-center">
                                    <small class="text-muted">
                                        <i class="fas fa-lock me-1"></i>Réservation sécurisée
                                    </small>
                                </div>
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
                                        <i class="fas fa-utensils me-2" style="color: #4CAF50;"></i>
                                        <small>Petit déjeuner inclus</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-ban me-2" style="color: #4CAF50;"></i>
                                        <small>Chambre non-fumeur</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-child me-2" style="color: #4CAF50;"></i>
                                        <small>Enfants bienvenus</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-paw me-2" style="color: #4CAF50;"></i>
                                        <small>Animaux acceptés (sur demande)</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-wifi me-2" style="color: #4CAF50;"></i>
                                        <small>Wi-Fi gratuit illimité</small>
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
                        <img src="{{ $relatedRoom->first_image_url }}" 
                             class="card-img-top" 
                             alt="Chambre {{ $relatedRoom->number }}"
                             style="height: 200px; object-fit: cover; border-radius: 8px 8px 0 0;">
                        
                        <div class="card-body">
                            <h5 style="color: #2E7D32;">{{ $relatedRoom->type->name ?? 'Chambre' }} {{ $relatedRoom->number }}</h5>
                            
                            <div class="mb-3">
                                <span class="badge me-1" style="background-color: #81C784; color: white;">
                                    <i class="fas fa-user-friends me-1"></i>{{ $relatedRoom->capacity }}
                                </span>
                                <span class="badge" style="background-color: {{ $relatedRoom->room_status_id == 1 ? '#4CAF50' : '#FF9800' }}; color: white;">
                                    {{ $relatedRoom->roomStatus->name ?? 'Statut' }}
                                </span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h4 mb-0" style="color: #4CAF50;">
                                    {{ number_format($relatedRoom->price, 0) }} Fcfa
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
// Fonction pour vérifier la disponibilité via AJAX
async function checkAvailability() {
    const checkin = document.getElementById('checkin-date').value;
    const checkout = document.getElementById('checkout-date').value;
    const roomId = {{ $room->id }};
    
    if (!checkin || !checkout) {
        showAlert('Veuillez sélectionner les dates de séjour.', 'warning');
        return;
    }
    
    if (new Date(checkout) <= new Date(checkin)) {
        showAlert('La date de départ doit être après la date d\'arrivée.', 'warning');
        return;
    }
    
    // Afficher un indicateur de chargement
    const button = document.querySelector('button[onclick="checkAvailability()"]');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Vérification...';
    button.disabled = true;
    
    try {
        // Faire une requête AJAX vers votre backend
        const response = await fetch(`/api/rooms/${roomId}/check-availability`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                check_in: checkin,
                check_out: checkout
            })
        });
        
        const data = await response.json();
        
        if (data.available) {
            // Calculer le nombre de nuits et le prix total
            const nights = calculateNights(checkin, checkout);
            const totalPrice = {{ $room->price }} * nights;
            
            showAvailabilityResult(true, nights, totalPrice, checkin, checkout);
        } else {
            showAvailabilityResult(false, 0, 0, checkin, checkout);
        }
        
    } catch (error) {
        console.error('Erreur:', error);
        showAlert('Une erreur est survenue lors de la vérification.', 'danger');
    } finally {
        // Restaurer le bouton
        button.innerHTML = originalText;
        button.disabled = false;
    }
}

// Fonction pour calculer le nombre de nuits
function calculateNights(checkin, checkout) {
    const checkinDate = new Date(checkin);
    const checkoutDate = new Date(checkout);
    const diffTime = Math.abs(checkoutDate - checkinDate);
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
}

// Fonction pour afficher le résultat
function showAvailabilityResult(isAvailable, nights, totalPrice, checkin, checkout) {
    const resultDiv = document.getElementById('availability-result');
    
    if (!resultDiv) {
        // Créer la div si elle n'existe pas
        const newDiv = document.createElement('div');
        newDiv.id = 'availability-result';
        newDiv.className = 'mt-4';
        document.querySelector('.card-body').appendChild(newDiv);
    }
    
    const resultDivFinal = document.getElementById('availability-result');
    
    if (isAvailable) {
        const formattedTotal = totalPrice.toLocaleString('fr-FR');
        resultDivFinal.innerHTML = `
            <div class="alert alert-success" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-2">Chambre disponible !</h5>
                        <p class="mb-2">Du ${formatDate(checkin)} au ${formatDate(checkout)}</p>
                        <p class="mb-2"><strong>${nights} nuit${nights > 1 ? 's' : ''}</strong> - Total: <strong>${formattedTotal} FCFA</strong></p>
                        <hr>
                        <div class="d-grid gap-2">
                            <a href="{{ route('frontend.contact') }}?room_id={{ $room->id }}&checkin=${checkin}&checkout=${checkout}" 
                               class="btn btn-success">
                                <i class="fas fa-calendar-check me-2"></i>Réserver maintenant
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;
    } else {
        resultDivFinal.innerHTML = `
            <div class="alert alert-danger" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-times-circle fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-2">Chambre non disponible</h5>
                        <p class="mb-2">Malheureusement, cette chambre n'est pas disponible pour les dates sélectionnées.</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('frontend.rooms') }}" class="btn btn-outline-danger">
                                <i class="fas fa-bed me-2"></i>Voir d'autres chambres
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    // Faire défiler jusqu'au résultat
    resultDivFinal.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Fonction pour formater la date
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// Fonction pour afficher des alertes simples
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Ajouter l'alerte au début de la carte
    const cardBody = document.querySelector('.card-body');
    cardBody.insertBefore(alertDiv, cardBody.firstChild);
    
    // Supprimer l'alerte après 5 secondes
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Date picker - minimum aujourd'hui
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const checkinInput = document.getElementById('checkin-date');
    const checkoutInput = document.getElementById('checkout-date');
    
    // Définir une date par défaut pour demain
    if (checkinInput) {
        checkinInput.min = today;
        
        // Définir une date par défaut pour demain
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        checkinInput.value = tomorrow.toISOString().split('T')[0];
        
        checkinInput.addEventListener('change', function() {
            if (this.value) {
                const nextDay = new Date(this.value);
                nextDay.setDate(nextDay.getDate() + 1);
                checkoutInput.min = nextDay.toISOString().split('T')[0];
                
                // Définir checkout à check-in + 1 jour
                checkoutInput.value = nextDay.toISOString().split('T')[0];
                
                // Si checkout est antérieur à la nouvelle date minimale, on le réinitialise
                if (checkoutInput.value && checkoutInput.value < checkoutInput.min) {
                    checkoutInput.value = '';
                }
            }
        });
        
        // Déclencher l'événement change pour initialiser checkout
        checkinInput.dispatchEvent(new Event('change'));
    }
    
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