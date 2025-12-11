@extends('frontend.layouts.master')

@section('title', 'Restaurant Gastronomique - cactus Palace')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section" style="background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 mb-4">Le Restaurant Gastronomique</h1>
                    <p class="lead mb-4">Une expérience culinaire unique par notre chef étoilé</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Présentation -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6">
                    <h2 class="mb-4">Cuisine Française Raffinée</h2>
                    <p>Notre restaurant vous invite à un voyage culinaire exceptionnel. Sous la direction de notre chef étoilé, nous proposons une cuisine française contemporaine mettant en valeur les produits de saison.</p>
                    <p>Avec une vue imprenable sur les jardins de l'hôtel, le restaurant offre une ambiance élégante et chaleureuse, parfaite pour un dîner romantique ou un déjeuner d'affaires.</p>
                    
                    <div class="mt-4">
                        <h5>Horaires d'ouverture :</h5>
                        <ul class="list-unstyled">
                            <li><strong>Petit-déjeuner :</strong> 7h - 11h</li>
                            <li><strong>Déjeuner :</strong> 12h - 15h</li>
                            <li><strong>Dîner :</strong> 19h - 23h</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1559339352-11d035aa65de?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                         alt="Notre chef" 
                         class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Menu -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="mb-3">Notre Carte</h2>
                <p class="text-muted">Découvrez notre sélection de plats raffinés</p>
            </div>

            <!-- Filtres catégories -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-center flex-wrap">
                        <button class="btn btn-outline-primary-custom m-1 category-filter active" data-category="all">
                            Tous
                        </button>
                        <button class="btn btn-outline-primary-custom m-1 category-filter" data-category="entree">
                            Entrées
                        </button>
                        <button class="btn btn-outline-primary-custom m-1 category-filter" data-category="plat">
                            Plats principaux
                        </button>
                        <button class="btn btn-outline-primary-custom m-1 category-filter" data-category="dessert">
                            Desserts
                        </button>
                        <button class="btn btn-outline-primary-custom m-1 category-filter" data-category="boisson">
                            Boissons
                        </button>
                    </div>
                </div>
            </div>

            <!-- Liste des menus -->
            <div class="row" id="menuList">
                @forelse($menus as $menu)
                <div class="col-lg-6 mb-4 menu-item" data-category="{{ $menu->category }}">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="row g-0">
                            <div class="col-md-4">
                                @if($menu->image)
                                <img src="{{ asset('storage/' . $menu->image) }}" 
                                     class="img-fluid rounded-start h-100" 
                                     alt="{{ $menu->name }}"
                                     style="object-fit: cover; height: 200px;">
                                @else
                                <div class="bg-secondary d-flex align-items-center justify-content-center h-100">
                                    <i class="fas fa-utensils fa-3x text-white"></i>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h5 class="card-title">{{ $menu->name }}</h5>
                                        <span class="h5 text-primary-custom mb-0">
                                            {{ number_format($menu->price, 2) }} €
                                        </span>
                                    </div>
                                    <p class="card-text text-muted">{{ $menu->description }}</p>
                                    <span class="badge bg-primary-custom">
                                        @if($menu->category == 'plat') Plat principal
                                        @elseif($menu->category == 'entree') Entrée
                                        @elseif($menu->category == 'dessert') Dessert
                                        @elseif($menu->category == 'boisson') Boisson
                                        @else {{ ucfirst($menu->category) }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-utensils fa-4x text-muted mb-3"></i>
                    <h4>Menu en préparation</h4>
                    <p class="text-muted">Notre chef travaille sur de nouvelles créations.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Réservation -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <h2 class="mb-2">Réservation de table</h2>
                                <p class="text-muted">Réservez votre table pour une expérience culinaire inoubliable</p>
                            </div>
                            
                            <form id="reservationForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nom complet *</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Téléphone *</label>
                                        <input type="tel" class="form-control" name="phone" required>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Date *</label>
                                        <input type="date" class="form-control" name="date" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Heure *</label>
                                        <input type="time" class="form-control" name="time" required>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nombre de personnes *</label>
                                        <select class="form-select" name="persons" required>
                                            <option value="1">1 personne</option>
                                            <option value="2" selected>2 personnes</option>
                                            <option value="3">3 personnes</option>
                                            <option value="4">4 personnes</option>
                                            <option value="5">5 personnes</option>
                                            <option value="6">6 personnes</option>
                                            <option value="7">7 personnes</option>
                                            <option value="8">8 personnes</option>
                                            <option value="9">9 personnes</option>
                                            <option value="10">10 personnes</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Type de table</label>
                                        <select class="form-select" name="table_type">
                                            <option value="standard">Standard</option>
                                            <option value="window">Fenêtre</option>
                                            <option value="terrace">Terrasse</option>
                                            <option value="private">Salle privée</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Notes spéciales</label>
                                    <textarea class="form-control" name="notes" rows="3" placeholder="Allergies, préférences alimentaires..."></textarea>
                                </div>
                                
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary-custom btn-lg">
                                        <i class="fas fa-calendar-check me-2"></i> Réserver maintenant
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Filtrage des menus
    $('.category-filter').click(function() {
        $('.category-filter').removeClass('active');
        $(this).addClass('active');
        
        const category = $(this).data('category');
        
        if (category === 'all') {
            $('.menu-item').show();
        } else {
            $('.menu-item').hide();
            $(`.menu-item[data-category="${category}"]`).show();
        }
    });

    // Réservation
    $('#reservationForm').submit(function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: '{{ route("restaurant.reservation.store") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Réservation envoyée !',
                    text: 'Nous vous confirmerons votre réservation par téléphone.',
                    timer: 3000
                }).then(() => {
                    $('#reservationForm')[0].reset();
                });
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue. Veuillez réessayer.'
                });
            }
        });
    });
});
</script>
@endpush