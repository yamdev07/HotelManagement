@extends('frontend.layouts.master')

@section('title', $room->name . ' - Hôtel Cactus Palace')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section-room-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 mb-3">{{ $room->name }}</h1>
                    <p class="lead mb-4">{{ $room->view ?? 'Découvrez notre chambre luxueuse' }}</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}" style="color: white;">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('frontend.rooms') }}" style="color: white;">Chambres</a></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: #C8E6C9;">{{ $room->number }}</li>
                        </ol>
                    </nav>
                    <div class="mt-4 d-flex justify-content-center flex-wrap gap-3">
                        <span class="badge" style="background-color: #4CAF50; padding: 8px 16px;">
                            <i class="fas fa-user-friends me-1"></i>{{ $room->capacity }} Personne{{ $room->capacity > 1 ? 's' : '' }}
                        </span>
                        <span class="badge" style="background-color: #2196F3; padding: 8px 16px;">
                            <i class="fas fa-expand-arrows-alt me-1"></i>{{ $room->size ?? '--' }} m²
                        </span>
                        <span class="badge" style="background-color: #9C27B0; padding: 8px 16px;">
                            <i class="fas fa-bed me-1"></i>{{ $room->type->name ?? 'Standard' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Galerie principale -->
    <section class="py-4" style="background-color: #F8FDF9;">
        <div class="container">
            <div class="row g-4">
                <!-- Image principale -->
                <div class="col-lg-8">
                    <div class="main-gallery-container position-relative">
                        <img src="{{ $room->first_image_url }}" 
                             alt="{{ $room->name }}"
                             id="mainImage"
                             class="img-fluid rounded-3 shadow"
                             style="width: 100%; height: 450px; object-fit: cover;">
                        
                        <!-- Badges sur l'image -->
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge" style="background-color: {{ $room->is_available_today ? '#4CAF50' : '#F44336' }}; 
                                  padding: 8px 16px; font-size: 0.9rem;">
                                <i class="fas fa-{{ $room->is_available_today ? 'check-circle' : 'times-circle' }} me-1"></i>
                                {{ $room->is_available_today ? 'Disponible' : 'Non disponible' }}
                            </span>
                        </div>
                        
                        @if($room->average_rating)
                        <div class="position-absolute bottom-0 start-0 m-3">
                            <span class="badge" style="background-color: rgba(0,0,0,0.7); padding: 8px 16px;">
                                <i class="fas fa-star text-warning me-1"></i>
                                {{ number_format($room->average_rating, 1) }}/5
                            </span>
                        </div>
                        @endif
                        
                        <!-- Contrôles de galerie -->
                        @if($room->images && $room->images->count() > 1)
                        <div class="position-absolute top-50 start-0 end-0 d-flex justify-content-between px-3">
                            <button class="btn btn-light btn-sm rounded-circle shadow" 
                                    onclick="prevImage()"
                                    style="width: 40px; height: 40px;">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="btn btn-light btn-sm rounded-circle shadow" 
                                    onclick="nextImage()"
                                    style="width: 40px; height: 40px;">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Miniatures -->
                    @if($room->images && $room->images->count() > 1)
                    <div class="row g-2 mt-3">
                        @foreach($room->images as $index => $image)
                        <div class="col-3">
                            <img src="{{ $image->getRoomImage() }}" 
                                 alt="Image {{ $loop->iteration }}"
                                 class="img-fluid rounded thumbnail"
                                 style="height: 100px; width: 100%; object-fit: cover; cursor: pointer; 
                                        {{ $loop->first ? 'border: 3px solid #4CAF50;' : 'opacity: 0.7;' }}"
                                 onclick="changeMainImage('{{ $image->getRoomImage() }}', this)"
                                 data-index="{{ $index }}">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                
                <!-- Réservation rapide -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm sticky-top" style="top: 20px; border-top: 4px solid #4CAF50;">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <h3 style="color: #4CAF50; font-weight: 600;">
                                    {{ number_format($room->price, 0, ',', ' ') }} FCFA
                                </h3>
                                <small class="text-muted">par nuit, petit déjeuner inclus</small>
                                
                                @if($room->promotion_price)
                                <div class="mt-2">
                                    <span class="text-decoration-line-through text-muted me-2">
                                        {{ number_format($room->price, 0, ',', ' ') }} FCFA
                                    </span>
                                    <span class="badge bg-danger">
                                        -{{ $room->promotion_percentage }}%
                                    </span>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Formulaire de réservation -->
                            <form id="quickBookingForm">
                                @csrf
                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                
                                <div class="mb-3">
                                    <label class="form-label fw-medium" style="color: #2E7D32;">
                                        <i class="fas fa-calendar-alt me-2"></i>Dates de séjour
                                    </label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="date" 
                                                   class="form-control"
                                                   name="check_in"
                                                   id="check_in"
                                                   required
                                                   style="border-color: #C8E6C9;">
                                            <small class="text-muted">Arrivée</small>
                                        </div>
                                        <div class="col-6">
                                            <input type="date" 
                                                   class="form-control"
                                                   name="check_out"
                                                   id="check_out"
                                                   required
                                                   style="border-color: #C8E6C9;">
                                            <small class="text-muted">Départ</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-medium" style="color: #2E7D32;">
                                        <i class="fas fa-user-friends me-2"></i>Occupants
                                    </label>
                                    <select class="form-select" 
                                            name="adults"
                                            id="adults"
                                            style="border-color: #C8E6C9;">
                                        @for($i = 1; $i <= min($room->capacity, 6); $i++)
                                            <option value="{{ $i }}" {{ $i == min($room->capacity, 2) ? 'selected' : '' }}>
                                                {{ $i }} Adulte{{ $i > 1 ? 's' : '' }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label fw-medium" style="color: #2E7D32;">
                                        <i class="fas fa-child me-2"></i>Enfants (2-12 ans)
                                    </label>
                                    <select class="form-select" 
                                            name="children"
                                            id="children"
                                            style="border-color: #C8E6C9;">
                                        @for($i = 0; $i <= 4; $i++)
                                            <option value="{{ $i }}">{{ $i }} Enfant{{ $i > 1 ? 's' : '' }}</option>
                                        @endfor
                                    </select>
                                </div>
                                
                                <!-- Calcul du prix -->
                                <div class="price-summary mb-4 p-3 rounded" style="background-color: #E8F5E9; display: none;" id="priceSummary">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Nuits:</span>
                                        <strong id="nightsCount">0</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Prix par nuit:</span>
                                        <strong>{{ number_format($room->price, 0, ',', ' ') }} FCFA</strong>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Total:</span>
                                        <strong id="totalPrice" class="text-success">0 FCFA</strong>
                                    </div>
                                </div>
                                
                                <!-- Boutons d'action -->
                                <div class="d-grid gap-2">
                                    @if($room->is_available_today)
                                    <button type="button" 
                                            onclick="checkAvailability()"
                                            class="btn py-3 fw-medium"
                                            style="background-color: #4CAF50; color: white;">
                                        <i class="fas fa-check-circle me-2"></i>Vérifier la disponibilité
                                    </button>
                                    @endif
                                    
                                    <a href="{{ route('frontend.contact') }}?room_id={{ $room->id }}&subject=Réservation%20Chambre%20{{ $room->number }}"
                                       class="btn py-3 fw-medium"
                                       style="border: 2px solid #4CAF50; color: #4CAF50;">
                                        <i class="fas fa-envelope me-2"></i>Nous contacter
                                    </a>
                                </div>
                                
                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-lock me-1"></i>Réservation sécurisée
                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Détails et description -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Description et équipements -->
                <div class="col-lg-8">
                    <!-- Description détaillée -->
                    <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #2E7D32;">
                        <div class="card-body">
                            <h3 style="color: #2E7D32;" class="mb-4">
                                <i class="fas fa-align-left me-2"></i>Description
                            </h3>
                            
                            @if($room->description)
                            <div class="mb-4">
                                <p class="text-muted" style="line-height: 1.8; font-size: 1.1rem;">
                                    {{ $room->description }}
                                </p>
                            </div>
                            @endif
                            
                            @if($room->view)
                            <div class="mb-3">
                                <h5 style="color: #4CAF50;">
                                    <i class="fas fa-eye me-2"></i>Vue & Ambiance
                                </h5>
                                <p class="text-muted">{{ $room->view }}</p>
                            </div>
                            @endif
                            
                            <!-- Points forts -->
                            <div class="row g-3 mt-4">
                                @foreach(['wifi' => 'Wi-Fi Gratuit', 'tv' => 'TV Écran plat', 'snowflake' => 'Climatisation', 
                                        'bath' => 'Salle de bain privée', 'coffee' => 'Machine à café', 'wine-bottle' => 'Minibar'] as $icon => $text)
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 40px; height: 40px; background-color: #E8F5E9;">
                                            <i class="fas fa-{{ $icon }} text-success"></i>
                                        </div>
                                        <span class="fw-medium" style="color: #2E7D32;">{{ $text }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <!-- Équipements détaillés -->
                    @if($room->facilities && $room->facilities->count() > 0)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h3 style="color: #2E7D32;" class="mb-4">
                                <i class="fas fa-concierge-bell me-2"></i>Équipements & Services
                            </h3>
                            
                            <div class="row g-3">
                                @foreach($room->facilities->chunk(ceil($room->facilities->count() / 2)) as $chunk)
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        @foreach($chunk as $facility)
                                        <li class="mb-3">
                                            <div class="d-flex align-items-start">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 mt-1" 
                                                     style="min-width: 36px; height: 36px; background-color: #4CAF50;">
                                                    <i class="fas fa-{{ $facility->icon ?? 'check' }} text-white"></i>
                                                </div>
                                                <div>
                                                    <strong style="color: #2E7D32;">{{ $facility->name }}</strong>
                                                    @if($facility->description)
                                                    <p class="text-muted small mb-0">{{ $facility->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Informations techniques -->
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h4 style="color: #2E7D32;" class="mb-4">
                                        <i class="fas fa-info-circle me-2"></i>Informations pratiques
                                    </h4>
                                    <ul class="list-unstyled">
                                        <li class="mb-3">
                                            <i class="fas fa-door-closed me-2 text-success"></i>
                                            <strong>Numéro:</strong> {{ $room->number }}
                                        </li>
                                        <li class="mb-3">
                                            <i class="fas fa-layer-group me-2 text-success"></i>
                                            <strong>Étage:</strong> {{ $room->floor ?? 'RDC' }}
                                        </li>
                                        <li class="mb-3">
                                            <i class="fas fa-clock me-2 text-success"></i>
                                            <strong>Check-in:</strong> 15:00 - 22:00
                                        </li>
                                        <li class="mb-3">
                                            <i class="fas fa-clock me-2 text-success"></i>
                                            <strong>Check-out:</strong> Avant 12:00
                                        </li>
                                        <li class="mb-3">
                                            <i class="fas fa-bed me-2 text-success"></i>
                                            <strong>Type de lit:</strong> {{ $room->bed_type ?? 'Queen size' }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h4 style="color: #2E7D32;" class="mb-4">
                                        <i class="fas fa-ban me-2"></i>Règles & Conditions
                                    </h4>
                                    <ul class="list-unstyled">
                                        <li class="mb-3">
                                            <i class="fas fa-smoking-ban me-2 text-danger"></i>
                                            <span>Chambre non-fumeur</span>
                                        </li>
                                        <li class="mb-3">
                                            <i class="fas fa-paw me-2 text-success"></i>
                                            <span>Animaux acceptés (avec supplément)</span>
                                        </li>
                                        <li class="mb-3">
                                            <i class="fas fa-user-plus me-2 text-info"></i>
                                            <span>Occupation max: {{ $room->capacity }} personnes</span>
                                        </li>
                                        <li class="mb-3">
                                            <i class="fas fa-baby me-2 text-warning"></i>
                                            <span>Lit bébé disponible sur demande</span>
                                        </li>
                                        <li class="mb-3">
                                            <i class="fas fa-wheelchair me-2 text-primary"></i>
                                            <span>Accès PMV selon configuration</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar: Stats et Avis -->
                <div class="col-lg-4">
                    <!-- Statistiques -->
                    <div class="card border-0 shadow-sm mb-4" style="background-color: #F1F8E9;">
                        <div class="card-body">
                            <h4 style="color: #2E7D32;" class="mb-4 text-center">
                                <i class="fas fa-chart-bar me-2"></i>Statistiques
                            </h4>
                            
                            <div class="row text-center">
                                <div class="col-6 mb-4">
                                    <div class="display-6 fw-bold" style="color: #4CAF50;">
                                        {{ $room->capacity }}
                                    </div>
                                    <small class="text-muted">Capacité max</small>
                                </div>
                                <div class="col-6 mb-4">
                                    <div class="display-6 fw-bold" style="color: #2196F3;">
                                        {{ $room->size ?? '--' }}
                                    </div>
                                    <small class="text-muted">m² Surface</small>
                                </div>
                                <div class="col-6">
                                    <div class="display-6 fw-bold" style="color: #9C27B0;">
                                        4.8
                                    </div>
                                    <small class="text-muted">Note clients</small>
                                </div>
                                <div class="col-6">
                                    <div class="display-6 fw-bold" style="color: #FF9800;">
                                        98%
                                    </div>
                                    <small class="text-muted">Satisfaction</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Avis récents (simulé) -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h4 style="color: #2E7D32;" class="mb-4">
                                <i class="fas fa-star me-2"></i>Avis clients
                            </h4>
                            
                            <div class="mb-4 text-center">
                                <div class="display-4 fw-bold text-warning mb-2">4.8</div>
                                <div class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star text-warning"></i>
                                    @endfor
                                </div>
                                <small class="text-muted">Basé sur 24 avis</small>
                            </div>
                            
                            <div class="testimonial">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-success d-flex align-items-center justify-content-center me-3"
                                         style="width: 40px; height: 40px;">
                                        <span class="text-white fw-bold">JD</span>
                                    </div>
                                    <div>
                                        <strong>Jean D.</strong>
                                        <div class="small text-warning">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-muted small mb-0">
                                    "Chambre spacieuse et très propre. Le personnel est exceptionnel !"
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- CTA WhatsApp -->
                    <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #25D366, #128C7E);">
                        <div class="card-body text-center text-white">
                            <i class="fab fa-whatsapp fa-3x mb-3"></i>
                            <h5 class="mb-3">Questions rapides ?</h5>
                            <p class="mb-3 small opacity-90">Parlez directement avec notre équipe</p>
                            <a href="https://wa.me/229XXXXXXXXX?text=Bonjour,%20je%20m'intéresse%20à%20la%20chambre%20{{ $room->number }}"
                               target="_blank"
                               class="btn btn-lg text-white fw-bold"
                               style="background-color: rgba(255,255,255,0.2); border: 2px solid white;">
                                <i class="fab fa-whatsapp me-2"></i>Contact WhatsApp
                            </a>
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
                    <div class="card room-card h-100 border-0 shadow-sm" style="border-top: 4px solid #4CAF50; transition: all 0.3s ease;">
                        <div class="position-relative">
                            <img src="{{ $relatedRoom->first_image_url }}" 
                                 class="card-img-top" 
                                 alt="Chambre {{ $relatedRoom->number }}"
                                 style="height: 220px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge" style="background-color: {{ $relatedRoom->is_available_today ? '#4CAF50' : '#FF9800' }};">
                                    {{ $relatedRoom->is_available_today ? 'Disponible' : 'Sur demande' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 style="color: #2E7D32;" class="mb-1">{{ $relatedRoom->name }}</h5>
                                <span class="badge" style="background-color: #81C784;">
                                    <i class="fas fa-user-friends me-1"></i>{{ $relatedRoom->capacity }}
                                </span>
                            </div>
                            
                            <p class="text-muted small mb-3">
                                <i class="fas fa-door-closed me-1"></i>{{ $relatedRoom->type->name ?? 'Standard' }}
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="h4 mb-0" style="color: #4CAF50;">
                                        {{ number_format($relatedRoom->price, 0, ',', ' ') }} FCFA
                                    </span>
                                    <small class="text-muted d-block">par nuit</small>
                                </div>
                                <a href="{{ route('frontend.room.details', $relatedRoom->id) }}" 
                                   class="btn btn-sm"
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

    <!-- Call to Action final -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h3 style="color: #2E7D32;" class="mb-3">
                        <i class="fas fa-gift me-2"></i>
                        Offre spéciale pour cette chambre
                    </h3>
                    <p class="text-muted mb-4">
                        Réservez aujourd'hui et bénéficiez d'un service de transfert aéroport gratuit 
                        pour tout séjour de 3 nuits ou plus !
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('frontend.contact') }}?room_id={{ $room->id }}&promo=transfert_gratuit"
                           class="btn py-3 px-4 fw-bold"
                           style="background-color: #4CAF50; color: white;">
                            <i class="fas fa-calendar-check me-2"></i>Profiter de l'offre
                        </a>
                        <a href="tel:+229XXXXXXXXX"
                           class="btn py-3 px-4"
                           style="border: 2px solid #4CAF50; color: #4CAF50;">
                            <i class="fas fa-phone-alt me-2"></i>Appeler maintenant
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0 text-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width: 120px; height: 120px; background: linear-gradient(135deg, #4CAF50, #2E7D32);">
                        <i class="fas fa-gift fa-3x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
.hero-section-room-details {
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                url('https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 100px 0 60px;
    margin-bottom: 20px;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    font-size: 0.9rem;
}

.breadcrumb-item.active {
    color: #C8E6C9 !important;
}

.main-gallery-container {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.thumbnail {
    transition: all 0.3s ease;
}

.thumbnail:hover {
    opacity: 1 !important;
    transform: scale(1.05);
}

.room-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(76, 175, 80, 0.2) !important;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.card {
    animation: fadeIn 0.5s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section-room-details {
        padding: 80px 0 40px;
    }
    
    .hero-section-room-details h1 {
        font-size: 2.2rem;
    }
    
    .sticky-top {
        position: static !important;
    }
    
    .main-gallery-container img {
        height: 300px !important;
    }
}

@media (max-width: 576px) {
    .display-4 {
        font-size: 2rem;
    }
    
    .btn {
        width: 100% !important;
        margin-bottom: 10px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Variables pour la galerie
let currentImageIndex = 0;
const images = [
    @if($room->images && $room->images->count() > 0)
        @foreach($room->images as $image)
            '{{ $image->getRoomImage() }}',
        @endforeach
    @endif
];

// Fonction pour changer l'image principale
function changeMainImage(src, element) {
    document.getElementById('mainImage').src = src;
    
    // Retirer la bordure de toutes les miniatures
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.style.border = 'none';
        thumb.style.opacity = '0.7';
    });
    
    // Ajouter la bordure à la miniature cliquée
    if (element) {
        element.style.border = '3px solid #4CAF50';
        element.style.opacity = '1';
        currentImageIndex = parseInt(element.dataset.index);
    }
}

// Fonction pour l'image précédente
function prevImage() {
    currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
    changeMainImage(images[currentImageIndex]);
    
    // Mettre à jour la miniature active
    document.querySelectorAll('.thumbnail').forEach((thumb, index) => {
        thumb.style.border = index === currentImageIndex ? '3px solid #4CAF50' : 'none';
        thumb.style.opacity = index === currentImageIndex ? '1' : '0.7';
    });
}

// Fonction pour l'image suivante
function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % images.length;
    changeMainImage(images[currentImageIndex]);
    
    // Mettre à jour la miniature active
    document.querySelectorAll('.thumbnail').forEach((thumb, index) => {
        thumb.style.border = index === currentImageIndex ? '3px solid #4CAF50' : 'none';
        thumb.style.opacity = index === currentImageIndex ? '1' : '0.7';
    });
}

// Gestion des dates
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const checkinInput = document.getElementById('check_in');
    const checkoutInput = document.getElementById('check_out');
    
    // Initialiser les dates
    if (checkinInput) {
        // Date d'arrivée par défaut: demain
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        checkinInput.min = today;
        checkinInput.value = tomorrow.toISOString().split('T')[0];
        
        // Date de départ par défaut: après-demain
        const dayAfterTomorrow = new Date(tomorrow);
        dayAfterTomorrow.setDate(dayAfterTomorrow.getDate() + 1);
        checkoutInput.min = dayAfterTomorrow.toISOString().split('T')[0];
        checkoutInput.value = dayAfterTomorrow.toISOString().split('T')[0];
        
        // Calcul initial du prix
        calculatePrice();
        
        // Écouteurs d'événements
        checkinInput.addEventListener('change', function() {
            if (this.value) {
                const nextDay = new Date(this.value);
                nextDay.setDate(nextDay.getDate() + 1);
                checkoutInput.min = nextDay.toISOString().split('T')[0];
                
                // Si checkout est antérieur, le réinitialiser
                if (new Date(checkoutInput.value) < nextDay) {
                    checkoutInput.value = nextDay.toISOString().split('T')[0];
                }
                calculatePrice();
            }
        });
        
        checkoutInput.addEventListener('change', calculatePrice);
    }
});

// Fonction pour calculer le prix
function calculatePrice() {
    const checkin = document.getElementById('check_in').value;
    const checkout = document.getElementById('check_out').value;
    
    if (!checkin || !checkout) return;
    
    const nights = Math.ceil((new Date(checkout) - new Date(checkin)) / (1000 * 60 * 60 * 24));
    const pricePerNight = {{ $room->price }};
    const totalPrice = nights * pricePerNight;
    
    // Afficher le résumé
    const summaryDiv = document.getElementById('priceSummary');
    summaryDiv.style.display = 'block';
    
    document.getElementById('nightsCount').textContent = nights;
    document.getElementById('totalPrice').textContent = totalPrice.toLocaleString('fr-FR') + ' FCFA';
}

// Fonction pour vérifier la disponibilité
async function checkAvailability() {
    const checkin = document.getElementById('check_in').value;
    const checkout = document.getElementById('check_out').value;
    const adults = document.getElementById('adults').value;
    const children = document.getElementById('children').value;
    
    if (!checkin || !checkout) {
        showAlert('Veuillez sélectionner les dates de séjour.', 'warning');
        return;
    }
    
    if (new Date(checkout) <= new Date(checkin)) {
        showAlert('La date de départ doit être après la date d\'arrivée.', 'warning');
        return;
    }
    
    // Afficher l'indicateur de chargement
    const button = document.querySelector('button[onclick="checkAvailability()"]');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Vérification...';
    button.disabled = true;
    
    try {
        // Simuler une vérification (à remplacer par votre API)
        await new Promise(resolve => setTimeout(resolve, 1500));
        
        const nights = Math.ceil((new Date(checkout) - new Date(checkin)) / (1000 * 60 * 60 * 24));
        const totalPrice = nights * {{ $room->price }};
        
        showAvailabilityModal(checkin, checkout, nights, totalPrice, adults, children);
        
    } catch (error) {
        console.error('Erreur:', error);
        showAlert('Une erreur est survenue lors de la vérification.', 'danger');
    } finally {
        button.innerHTML = originalText;
        button.disabled = false;
    }
}

// Fonction pour afficher la modal de disponibilité
function showAvailabilityModal(checkin, checkout, nights, totalPrice, adults, children) {
    // Créer la modal si elle n'existe pas
    let modal = document.getElementById('availabilityModal');
    
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'availabilityModal';
        modal.className = 'modal fade';
        modal.tabIndex = '-1';
        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #4CAF50; color: white;">
                        <h5 class="modal-title">
                            <i class="fas fa-check-circle me-2"></i>Chambre disponible
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                            <h4>Votre chambre est disponible !</h4>
                        </div>
                        
                        <div class="booking-summary p-3 rounded mb-4" style="background-color: #E8F5E9;">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted d-block">Arrivée</small>
                                    <strong id="modalCheckin"></strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Départ</small>
                                    <strong id="modalCheckout"></strong>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted d-block">Durée</small>
                                    <strong id="modalNights"></strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Occupants</small>
                                    <strong id="modalOccupants"></strong>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <small class="text-muted d-block">Prix total</small>
                                    <h3 class="text-success mb-0" id="modalTotalPrice"></h3>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                Le prix inclut le petit déjeuner et les taxes
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <a href="{{ route('frontend.contact') }}?room_id={{ $room->id }}&checkin=${checkin}&checkout=${checkout}&adults=${adults}&children=${children}"
                           class="btn btn-success">
                            <i class="fas fa-calendar-check me-2"></i>Procéder à la réservation
                        </a>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    
    // Remplir les informations
    document.getElementById('modalCheckin').textContent = formatDate(checkin);
    document.getElementById('modalCheckout').textContent = formatDate(checkout);
    document.getElementById('modalNights').textContent = `${nights} nuit${nights > 1 ? 's' : ''}`;
    document.getElementById('modalOccupants').textContent = `${adults} adulte${adults > 1 ? 's' : ''}${children > 0 ? `, ${children} enfant${children > 1 ? 's' : ''}` : ''}`;
    document.getElementById('modalTotalPrice').textContent = totalPrice.toLocaleString('fr-FR') + ' FCFA';
    
    // Afficher la modal
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}

// Fonctions utilitaires
function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' };
    return date.toLocaleDateString('fr-FR', options);
}

function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
            <div>${message}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
@endpush