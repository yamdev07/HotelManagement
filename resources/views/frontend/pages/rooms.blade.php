@extends('frontend.layouts.master')

@section('title', 'Nos Chambres - Hôtel Cactus Palace')

@section('content')
    <!-- Hero Section pour les chambres -->
    <section class="hero-section-rooms">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 mb-4">Nos Chambres & Suites</h1>
                    <p class="lead mb-4">Découvrez l'harmonie entre confort luxueux et élégance naturelle</p>
                    <div class="d-flex justify-content-center align-items-center gap-3">
                        <span class="badge bg-success">
                            <i class="fas fa-bed me-1"></i>{{ $rooms->total() }} Chambres
                        </span>
                        <span class="badge bg-info">
                            <i class="fas fa-check-circle me-1"></i>{{ $availableCount ?? 0 }} Disponibles
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section de filtres -->
    <section class="py-4" style="background-color: #E8F5E9;">
        <div class="container">
            <form action="{{ route('frontend.rooms') }}" method="GET" id="filterForm">
                <div class="row g-3 align-items-center">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="type" class="form-label" style="color: #2E7D32; font-weight: 500;">
                                <i class="fas fa-filter me-2"></i>Type de chambre
                            </label>
                            <select class="form-select" id="type" name="type" style="border: 1px solid #C8E6C9;">
                                <option value="">Tous les types</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="capacity" class="form-label" style="color: #2E7D32; font-weight: 500;">
                                <i class="fas fa-users me-2"></i>Capacité
                            </label>
                            <select class="form-select" id="capacity" name="capacity" style="border: 1px solid #C8E6C9;">
                                <option value="">Toute capacité</option>
                                <option value="1" {{ request('capacity') == 1 ? 'selected' : '' }}>1 personne</option>
                                <option value="2" {{ request('capacity') == 2 ? 'selected' : '' }}>2 personnes</option>
                                <option value="3" {{ request('capacity') == 3 ? 'selected' : '' }}>3 personnes</option>
                                <option value="4" {{ request('capacity') == 4 ? 'selected' : '' }}>4 personnes</option>
                                <option value="5" {{ request('capacity') == 5 ? 'selected' : '' }}>5+ personnes</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="price_range" class="form-label" style="color: #2E7D32; font-weight: 500;">
                                <i class="fas fa-money-bill me-2"></i>Budget/nuit (FCFA)
                            </label>
                            <select class="form-select" id="price_range" name="price_range" style="border: 1px solid #C8E6C9;">
                                <option value="">Tous les prix</option>
                                <option value="0-50000" {{ request('price_range') == '0-50000' ? 'selected' : '' }}>Moins de 50 000 FCFA</option>
                                <option value="50000-100000" {{ request('price_range') == '50000-100000' ? 'selected' : '' }}>50 000 - 100 000 FCFA</option>
                                <option value="100000-150000" {{ request('price_range') == '100000-150000' ? 'selected' : '' }}>100 000 - 150 000 FCFA</option>
                                <option value="150000-200000" {{ request('price_range') == '150000-200000' ? 'selected' : '' }}>150 000 - 200 000 FCFA</option>
                                <option value="200000+" {{ request('price_range') == '200000+' ? 'selected' : '' }}>Plus de 200 000 FCFA</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 text-lg-end">
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn" style="background-color: #4CAF50; border-color: #4CAF50; color: white; padding: 10px 20px;">
                                <i class="fas fa-search me-2"></i>Filtrer
                            </button>
                            <a href="{{ route('frontend.rooms') }}" class="btn" style="background-color: #FF9800; border-color: #FF9800; color: white; padding: 10px 20px;">
                                <i class="fas fa-redo me-2"></i>Réinitialiser
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Liste des chambres -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                @forelse($rooms as $room)
                <div class="col-lg-4 col-md-6">
                    <div class="card room-card h-100 border-0 shadow-sm" style="border-top: 4px solid #4CAF50;">
                        <!-- Badge statut dynamique -->
                        <div class="room-status-badge" style="position: absolute; top: 15px; right: 15px; z-index: 1;">
                            @if($room->is_available_today)
                                <span class="badge bg-success" style="padding: 6px 12px; font-weight: 600;">
                                    <i class="fas fa-check-circle me-1"></i>Disponible
                                </span>
                            @else
                                <span class="badge bg-danger" style="padding: 6px 12px; font-weight: 600;">
                                    <i class="fas fa-times-circle me-1"></i>Non disponible
                                </span>
                            @endif
                        </div>
                        
                        <img src="{{ $room->first_image_url }}" 
                             class="card-img-top" 
                             alt="{{ $room->type->name ?? 'Chambre' }}"
                             style="height: 250px; object-fit: cover; border-radius: 8px 8px 0 0;">
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0" style="color: #2E7D32;">{{ $room->type->name ?? 'Chambre Standard' }}</h5>
                                <div>
                                    <span class="badge" style="background-color: #81C784; color: white;">
                                        <i class="fas fa-user-friends me-1"></i>{{ $room->capacity }}
                                    </span>
                                    <span class="badge ms-1" style="background-color: #2196F3; color: white;">
                                        Chambre {{ $room->number }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Équipements réels -->
                            <div class="room-equipments mb-3">
                                <small class="text-muted">
                                    @php
                                        $facilities = $room->facilities->take(3);
                                    @endphp
                                    @foreach($facilities as $facility)
                                        <i class="fas fa-check-circle me-1" style="color: #4CAF50;"></i>{{ $facility->name }}
                                        @if(!$loop->last) • @endif
                                    @endforeach
                                    @if($room->facilities->count() > 3)
                                        <span class="text-primary-custom">+{{ $room->facilities->count() - 3 }} autres</span>
                                    @endif
                                </small>
                            </div>
                            
                            <!-- Description courte -->
                            <p class="card-text text-muted mb-3" style="font-size: 0.9rem;">
                                {{ $room->short_description }}
                            </p>
                            
                            <!-- Détails -->
                            <div class="room-details mb-3">
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-expand-arrows-alt me-1"></i> {{ $room->size ?? 'N/A' }} m²
                                        </small>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-building me-1"></i> Étage {{ $room->floor ?? 'RDC' }}
                                        </small>
                                    </div>
                                    @if($room->view)
                                    <div class="col-12 mt-1">
                                        <small class="text-muted">
                                            <i class="fas fa-binoculars me-1"></i> Vue: {{ $room->view }}
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Prix et boutons -->
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div>
                                    <span class="h4 mb-0" style="color: #4CAF50;">
                                        {{ number_format($room->price, 0, ',', ' ') }} FCFA
                                    </span>
                                    <small class="text-muted d-block">par nuit</small>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('frontend.room.details', $room->id) }}" 
                                       class="btn" 
                                       style="background-color: #4CAF50; border-color: #4CAF50; color: white;">
                                        <i class="fas fa-eye me-1"></i> Détails
                                    </a>
                                    @if($room->is_available_today)
                                        <a href="{{ route('frontend.contact') }}?room_id={{ $room->id }}" 
                                           class="btn" 
                                           style="color: #4CAF50; border-color: #4CAF50; background-color: transparent;">
                                            <i class="fas fa-calendar-check me-1"></i> Réserver
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Prochaine disponibilité si occupée -->
                            @if(!$room->is_available_today && $room->next_available_date)
                                <div class="mt-3">
                                    <small class="text-warning">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        Disponible à partir du {{ $room->next_available_date }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5" style="background-color: #F1F8E9; border-radius: 10px;">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                             style="width: 80px; height: 80px; background-color: #C8E6C9;">
                            <i class="fas fa-bed fa-2x" style="color: #2E7D32;"></i>
                        </div>
                        <h4 style="color: #2E7D32;">Aucune chambre trouvée</h4>
                        <p class="text-muted mb-4">Aucune chambre ne correspond à vos critères de recherche.</p>
                        <a href="{{ route('frontend.rooms') }}" class="btn" style="background-color: #4CAF50; border-color: #4CAF50; color: white;">
                            <i class="fas fa-redo me-1"></i> Voir toutes les chambres
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($rooms->hasPages())
            <div class="mt-5 pt-4">
                <nav aria-label="Navigation des chambres">
                    {{ $rooms->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                </nav>
            </div>
            @endif
        </div>
    </section>

    <!-- Section statistiques -->
    <section class="py-5" style="background-color: #F1F8E9;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 style="color: #2E7D32;">Notre Sélection en Chiffres</h2>
                <p class="text-muted">La qualité au service de votre confort</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px; background-color: #4CAF50;">
                            <i class="fas fa-bed fa-2x text-white"></i>
                        </div>
                        <h3 style="color: #2E7D32;">{{ $totalRooms ?? 0 }}</h3>
                        <h5 style="color: #2E7D32;">Chambres</h5>
                        <p class="text-muted">Types variés pour tous vos besoins</p>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px; background-color: #2196F3;">
                            <i class="fas fa-check-circle fa-2x text-white"></i>
                        </div>
                        <h3 style="color: #2196F3;">{{ $availableCount ?? 0 }}</h3>
                        <h5 style="color: #2196F3;">Disponibles</h5>
                        <p class="text-muted">Chambres prêtes à vous accueillir</p>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px; background-color: #FF9800;">
                            <i class="fas fa-users fa-2x text-white"></i>
                        </div>
                        <h3 style="color: #FF9800;">{{ $averageCapacity ?? 2 }}</h3>
                        <h5 style="color: #FF9800;">Capacité moyenne</h5>
                        <p class="text-muted">Personnes par chambre</p>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px; background-color: #9C27B0;">
                            <i class="fas fa-star fa-2x text-white"></i>
                        </div>
                        <h3 style="color: #9C27B0;">4.8/5</h3>
                        <h5 style="color: #9C27B0;">Satisfaction</h5>
                        <p class="text-muted">Note moyenne de nos clients</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section CTA Réservation -->
    <section class="py-5">
        <div class="container">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #E8F5E9, #C8E6C9); border: 1px solid #A5D6A7;">
                <div class="card-body p-5">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h3 style="color: #2E7D32;">Besoin d'aide pour choisir ?</h3>
                            <p class="text-muted mb-0">Notre équipe est à votre disposition pour vous conseiller et vous aider à trouver la chambre parfaite pour votre séjour.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <div class="d-flex flex-column flex-md-row gap-2">
                                <a href="{{ route('frontend.contact') }}" class="btn btn-lg" style="background-color: #2E7D32; border-color: #2E7D32; color: white;">
                                    <i class="fas fa-comment me-2"></i> Nous contacter
                                </a>
                                <a href="tel:+229XXXXXXXXX" class="btn btn-lg" style="background-color: #4CAF50; border-color: #4CAF50; color: white;">
                                    <i class="fas fa-phone-alt me-2"></i> Appeler
                                </a>
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
.hero-section-rooms {
    background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                url('https://images.unsplash.com/photo-1584132967334-10e028bd69f7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 120px 0;
}

.room-card {
    transition: all 0.3s ease;
    border-radius: 10px;
    overflow: hidden;
}

.room-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(76, 175, 80, 0.15) !important;
}

/* Style des badges */
.badge {
    font-weight: 500;
    letter-spacing: 0.3px;
}

/* Prix en FCFA */
.price-fcfa {
    font-weight: bold;
    color: #2E7D32;
}

.price-fcfa::after {
    content: " FCFA";
    font-size: 0.8em;
    font-weight: normal;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section-rooms {
        padding: 80px 0;
    }
    
    .hero-section-rooms h1 {
        font-size: 2.5rem;
    }
    
    .room-card .btn {
        padding: 8px 15px;
        font-size: 0.9rem;
    }
    
    .statistics h3 {
        font-size: 1.8rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation pour les cartes de chambre
    const roomCards = document.querySelectorAll('.room-card');
    roomCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Auto-submit des filtres
    const filterSelects = document.querySelectorAll('#type, #capacity, #price_range');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
    
    // Rafraîchissement automatique de la disponibilité
    function refreshAvailability() {
        fetch('/api/rooms/availability-summary')
            .then(response => response.json())
            .then(data => {
                // Mettre à jour les badges de disponibilité
                document.querySelectorAll('.room-status-badge').forEach((badge, index) => {
                    if (data.rooms && data.rooms[index]) {
                        const room = data.rooms[index];
                        if (room.is_available) {
                            badge.innerHTML = `
                                <span class="badge bg-success" style="padding: 6px 12px; font-weight: 600;">
                                    <i class="fas fa-check-circle me-1"></i>Disponible
                                </span>
                            `;
                        } else {
                            badge.innerHTML = `
                                <span class="badge bg-danger" style="padding: 6px 12px; font-weight: 600;">
                                    <i class="fas fa-times-circle me-1"></i>Non disponible
                                </span>
                            `;
                        }
                    }
                });
                
                // Mettre à jour le compteur
                const availableCount = document.querySelector('.badge.bg-info');
                if (availableCount && data.available_count !== undefined) {
                    availableCount.innerHTML = `<i class="fas fa-check-circle me-1"></i>${data.available_count} Disponibles`;
                }
            })
            .catch(error => console.error('Erreur de rafraîchissement:', error));
    }
    
    // Rafraîchir toutes les 30 secondes
    setInterval(refreshAvailability, 30000);
});
</script>
@endpush