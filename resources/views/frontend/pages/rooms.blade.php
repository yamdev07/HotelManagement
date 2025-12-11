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
                </div>
            </div>
        </div>
    </section>

    <!-- Section de filtres (optionnelle) -->
    <section class="py-4" style="background-color: #E8F5E9;">
        <div class="container">
            <div class="row g-3 align-items-center">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="roomType" class="form-label" style="color: #2E7D32; font-weight: 500;">
                            <i class="fas fa-filter me-2"></i>Type de chambre
                        </label>
                        <select class="form-select" id="roomType" style="border: 1px solid #C8E6C9;">
                            <option value="">Tous les types</option>
                            <option value="standard">Chambre Standard</option>
                            <option value="deluxe">Chambre Deluxe</option>
                            <option value="suite">Suite</option>
                            <option value="presidentielle">Suite Présidentielle</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="capacity" class="form-label" style="color: #2E7D32; font-weight: 500;">
                            <i class="fas fa-users me-2"></i>Capacité
                        </label>
                        <select class="form-select" id="capacity" style="border: 1px solid #C8E6C9;">
                            <option value="">Toute capacité</option>
                            <option value="1">1 personne</option>
                            <option value="2">2 personnes</option>
                            <option value="3">3 personnes</option>
                            <option value="4">4+ personnes</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="priceRange" class="form-label" style="color: #2E7D32; font-weight: 500;">
                            <i class="fas fa-euro-sign me-2"></i>Budget/nuit
                        </label>
                        <select class="form-select" id="priceRange" style="border: 1px solid #C8E6C9;">
                            <option value="">Tous les prix</option>
                            <option value="0-100">Moins de 100€</option>
                            <option value="100-200">100€ - 200€</option>
                            <option value="200-300">200€ - 300€</option>
                            <option value="300-500">300€ - 500€</option>
                            <option value="500+">Plus de 500€</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-3 text-lg-end">
                    <button class="btn mt-4" style="background-color: #4CAF50; border-color: #4CAF50; color: white; padding: 10px 30px;">
                        <i class="fas fa-search me-2"></i>Filtrer
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Liste des chambres -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                @forelse($rooms as $room)
                <div class="col-lg-4 col-md-6">
                    <div class="card room-card h-100 border-0 shadow-sm" style="border-top: 4px solid #4CAF50;">
                        <!-- Badge statut -->
                        @if($room->roomStatus)
                            @php
                                $statusBgColor = '#4CAF50'; // Vert pour disponible
                                $statusText = 'Disponible';
                                if($room->roomStatus->code == 'OCC') {
                                    $statusBgColor = '#F44336'; // Rouge pour occupé
                                    $statusText = 'Occupé';
                                } elseif($room->roomStatus->code == 'MNT') {
                                    $statusBgColor = '#FF9800'; // Orange pour maintenance
                                    $statusText = 'Maintenance';
                                }
                            @endphp
                            <div class="room-status-badge" style="position: absolute; top: 15px; right: 15px; z-index: 1;">
                                <span class="badge" style="background-color: {{ $statusBgColor }}; color: white; padding: 6px 12px; font-weight: 600;">
                                    {{ $statusText }}
                                </span>
                            </div>
                        @endif
                        
                        <img src="{{ $room->firstImage() }}" 
                             class="card-img-top" 
                             alt="{{ $room->type->name ?? 'Chambre' }}"
                             style="height: 250px; object-fit: cover; border-radius: 8px 8px 0 0;">
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0" style="color: #2E7D32;">{{ $room->type->name ?? 'Chambre Standard' }}</h5>
                                <span class="badge" style="background-color: #81C784; color: white;">
                                    <i class="fas fa-user-friends me-1"></i>{{ $room->capacity }}
                                </span>
                            </div>
                            
                            <!-- Équipements (exemple) -->
                            <div class="room-equipments mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-wifi me-1" style="color: #4CAF50;"></i>Wi-Fi Gratuit
                                    <i class="fas fa-tv ms-3 me-1" style="color: #4CAF50;"></i>Télévision
                                    <i class="fas fa-snowflake ms-3 me-1" style="color: #4CAF50;"></i>Climatisation
                                </small>
                            </div>
                            
                            @if($room->description)
                            <p class="card-text text-muted mb-3" style="font-size: 0.9rem;">
                                {{ Str::limit($room->description, 120) }}
                            </p>
                            @endif
                            
                            <!-- Avis (exemple fictif) -->
                            <div class="room-rating mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="text-warning me-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= 4)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="fas fa-star-half-alt"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <small class="text-muted">(4.5/5)</small>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div>
                                    <span class="h4 mb-0" style="color: #4CAF50;">
                                        {{ number_format($room->price, 0) }} €
                                    </span>
                                    <small class="text-muted d-block">par nuit</small>
                                </div>
                                <div>
                                    <a href="{{ route('frontend.room.details', $room->id) }}" 
                                       class="btn me-2" 
                                       style="background-color: #4CAF50; border-color: #4CAF50; color: white;">
                                        <i class="fas fa-eye me-1"></i> Voir détails
                                    </a>
                                    <a href="{{ route('frontend.contact') }}" 
                                       class="btn" 
                                       style="color: #4CAF50; border-color: #4CAF50; background-color: transparent;">
                                        <i class="fas fa-calendar-check me-1"></i> Réserver
                                    </a>
                                </div>
                            </div>
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
                        <h4 style="color: #2E7D32;">Aucune chambre disponible pour le moment</h4>
                        <p class="text-muted mb-4">Nos chambres sont en cours de préparation pour vous offrir le meilleur confort.</p>
                        <a href="{{ route('frontend.home') }}" class="btn me-2" style="background-color: #4CAF50; border-color: #4CAF50; color: white;">
                            <i class="fas fa-arrow-left me-1"></i> Retour à l'accueil
                        </a>
                        <a href="{{ route('frontend.contact') }}" class="btn" style="color: #4CAF50; border-color: #4CAF50; background-color: transparent;">
                            <i class="fas fa-envelope me-1"></i> Nous contacter
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($rooms->hasPages())
            <div class="mt-5 pt-4">
                <nav aria-label="Navigation des chambres">
                    {{ $rooms->onEachSide(1)->links('vendor.pagination.custom') }}
                </nav>
            </div>
            @endif
        </div>
    </section>

    <!-- Section avantages -->
    <section class="py-5" style="background-color: #F1F8E9;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 style="color: #2E7D32;">Pourquoi choisir nos chambres ?</h2>
                <p class="text-muted">Tout le confort dont vous avez besoin</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 60px; height: 60px; background-color: #4CAF50;">
                            <i class="fas fa-spa fa-2x text-white"></i>
                        </div>
                        <h5 style="color: #2E7D32;">Confort Optimal</h5>
                        <p class="text-muted">Matelas de haute qualité, literie premium et environnement paisible.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 60px; height: 60px; background-color: #4CAF50;">
                            <i class="fas fa-wifi fa-2x text-white"></i>
                        </div>
                        <h5 style="color: #2E7D32;">Connexion Haut Débit</h5>
                        <p class="text-muted">Wi-Fi gratuit et illimité dans toutes les chambres et parties communes.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 60px; height: 60px; background-color: #4CAF50;">
                            <i class="fas fa-shield-alt fa-2x text-white"></i>
                        </div>
                        <h5 style="color: #2E7D32;">Sécurité Totale</h5>
                        <p class="text-muted">Système de sécurité 24h/24 et coffre-fort individuel dans chaque chambre.</p>
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
                            <h3 style="color: #2E7D32;">Prêt à réserver votre séjour ?</h3>
                            <p class="text-muted mb-0">Contactez-nous dès maintenant pour vérifier la disponibilité et bénéficier de nos meilleurs tarifs.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <a href="{{ route('frontend.contact') }}" class="btn btn-lg" style="background-color: #2E7D32; border-color: #2E7D32; color: white; padding: 12px 40px;">
                                <i class="fas fa-phone-alt me-2"></i> Réserver maintenant
                            </a>
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

/* Effets hover pour les boutons */
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

/* Style de pagination personnalisé (si vous n'avez pas de fichier custom) */
.pagination .page-link {
    color: #4CAF50;
    border-color: #C8E6C9;
}

.pagination .page-link:hover {
    background-color: #E8F5E9;
    border-color: #4CAF50;
}

.pagination .page-item.active .page-link {
    background-color: #4CAF50;
    border-color: #4CAF50;
    color: white;
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
        display: block;
        width: 100%;
        margin-bottom: 10px;
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
    
    // Filtrage basique (exemple)
    const filterBtn = document.querySelector('button[style*="background-color: #4CAF50"]');
    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            const roomType = document.getElementById('roomType').value;
            const capacity = document.getElementById('capacity').value;
            const priceRange = document.getElementById('priceRange').value;
            
            // Ici, vous pourriez implémenter une logique de filtrage
            // Par exemple, rediriger vers une URL avec des paramètres
            // ou filtrer en JavaScript si vous chargez toutes les chambres
            
            alert('Fonctionnalité de filtrage à implémenter avec votre logique backend');
        });
    }
});
</script>
@endpush