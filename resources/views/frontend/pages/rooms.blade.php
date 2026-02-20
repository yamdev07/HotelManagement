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
                        <span class="badge bg-warning">
                            <i class="fas fa-star me-1"></i>{{ $distinctTypes ?? 0 }} Types
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
                                        {{ $type->name }} ({{ $type->rooms_count ?? 0 }})
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
                                @for($i = 1; $i <= 5; $i++)
                                    @php
                                        $count = $roomsByCapacity[$i] ?? 0;
                                    @endphp
                                    <option value="{{ $i }}" {{ request('capacity') == $i ? 'selected' : '' }}>
                                        {{ $i }} personne{{ $i > 1 ? 's' : '' }} ({{ $count }})
                                    </option>
                                @endfor
                                <option value="6" {{ request('capacity') == 6 ? 'selected' : '' }}>6+ personnes</option>
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
                                <option value="0-50000" {{ request('price_range') == '0-50000' ? 'selected' : '' }}>
                                    < 50 000 FCFA ({{ $priceRanges['0-50000'] ?? 0 }})
                                </option>
                                <option value="50000-100000" {{ request('price_range') == '50000-100000' ? 'selected' : '' }}>
                                    50k - 100k FCFA ({{ $priceRanges['50000-100000'] ?? 0 }})
                                </option>
                                <option value="100000-150000" {{ request('price_range') == '100000-150000' ? 'selected' : '' }}>
                                    100k - 150k FCFA ({{ $priceRanges['100000-150000'] ?? 0 }})
                                </option>
                                <option value="150000-200000" {{ request('price_range') == '150000-200000' ? 'selected' : '' }}>
                                    150k - 200k FCFA ({{ $priceRanges['150000-200000'] ?? 0 }})
                                </option>
                                <option value="200000+" {{ request('price_range') == '200000+' ? 'selected' : '' }}>
                                    > 200k FCFA ({{ $priceRanges['200000+'] ?? 0 }})
                                </option>
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

    <!-- Résultats de recherche -->
    @if(request()->hasAny(['type', 'capacity', 'price_range']))
    <section class="py-3" style="background-color: #F1F8E9;">
        <div class="container">
            <div class="alert alert-success border-0 d-flex align-items-center justify-content-between">
                <div>
                    <i class="fas fa-filter me-2"></i>
                    <strong>{{ $rooms->total() }} chambre(s)</strong> correspondant à vos critères
                    @if(request('type'))
                        • Type: {{ \App\Models\Type::find(request('type'))->name ?? '' }}
                    @endif
                    @if(request('capacity'))
                        • Capacité: {{ request('capacity') }} personne(s)
                    @endif
                    @if(request('price_range'))
                        • Budget: {{ request('price_range') }} FCFA
                    @endif
                </div>
                <div>
                    <a href="{{ route('frontend.rooms') }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-times me-1"></i>Effacer filtres
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Liste des chambres -->
    <section class="py-5">
        <div class="container">
            <!-- Tri et vue -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <span class="me-3 text-muted">
                            <i class="fas fa-sort me-1"></i>Trier par:
                        </span>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-success sort-btn" data-sort="price_asc">
                                Prix ↑
                            </button>
                            <button type="button" class="btn btn-outline-success sort-btn" data-sort="price_desc">
                                Prix ↓
                            </button>
                            <button type="button" class="btn btn-outline-success sort-btn" data-sort="name_asc">
                                Nom A-Z
                            </button>
                            <button type="button" class="btn btn-outline-success sort-btn" data-sort="capacity_desc">
                                Capacité
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-success view-btn active" data-view="grid">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success view-btn" data-view="list">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Grille des chambres -->
            <div id="rooms-grid" class="row g-4">
                @forelse($rooms as $room)
                <div class="col-lg-4 col-md-6 room-item" 
                     data-price="{{ $room->price }}"
                     data-name="{{ strtolower($room->name) }}"
                     data-capacity="{{ $room->capacity }}">
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
                        
                        <!-- Favoris -->
                        <div class="room-favorite" style="position: absolute; top: 15px; left: 15px; z-index: 1;">
                            <button class="btn btn-sm btn-light favorite-btn" data-room-id="{{ $room->id }}">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                        
                        <!-- IMAGE CORRIGÉE - Utilisation du chemin direct -->
                        <div class="room-image-container" style="height: 250px; overflow: hidden;">
                            @php
                                // Image par défaut
                                $defaultImage = asset('img/default/default-room.png');
                                $imageUrl = $defaultImage;
                                
                                // Vérifier si le dossier de la chambre existe avec des images
                                $roomFolder = public_path('img/room/' . $room->number);
                                if(is_dir($roomFolder)) {
                                    $files = scandir($roomFolder);
                                    foreach($files as $file) {
                                        if($file != '.' && $file != '..' && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                                            $imageUrl = asset('img/room/' . $room->number . '/' . $file);
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            
                            <img src="{{ $imageUrl }}" 
                                 class="card-img-top room-image" 
                                 alt="{{ $room->name }}"
                                 onerror="this.onerror=null; this.src='{{ asset('img/room/gamesetting.png') }}';"
                                 style="height: 100%; width: 100%; object-fit: cover; transition: transform 0.5s ease;">
                            
                            <!-- Navigation images -->
                            @if($room->images && $room->images->count() > 1)
                            <div class="image-nav" style="position: absolute; bottom: 10px; right: 10px;">
                                <span class="badge bg-dark opacity-75">
                                    <i class="fas fa-images me-1"></i>{{ $room->images->count() }}
                                </span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="card-body">
                            <!-- Nom et type -->
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="card-title mb-1" style="color: #2E7D32; font-weight: 600;">
                                        {{ $room->name }}
                                    </h5>
                                    <h6 class="text-muted mb-2">
                                        <i class="fas fa-door-closed me-1"></i>{{ $room->type->name ?? 'Chambre Standard' }}
                                    </h6>
                                </div>
                                <div class="text-end">
                                    <span class="badge" style="background-color: #81C784; color: white;">
                                        <i class="fas fa-user-friends me-1"></i>{{ $room->capacity }}
                                    </span>
                                    <div class="mt-1">
                                        <span class="badge" style="background-color: #2196F3; color: white;">
                                            Chambre {{ $room->number }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Thème principal -->
                            @php
                                $description = $room->view;
                                $theme = '';
                                if ($description) {
                                    $parts = explode(' - ', $description);
                                    if (count($parts) > 1) {
                                        $theme = trim($parts[0]);
                                    }
                                }
                            @endphp
                            
                            @if($theme)
                            <div class="room-theme mb-3">
                                <span class="badge" style="background-color: #E8F5E9; color: #2E7D32; font-weight: 500; border: 1px solid #C8E6C9;">
                                    <i class="fas fa-leaf me-1"></i>{{ $theme }}
                                </span>
                            </div>
                            @endif
                            
                            <!-- Description courte -->
                            @if($room->view)
                            <div class="room-description mb-3">
                                <p class="card-text text-muted mb-1" style="font-size: 0.9rem; line-height: 1.4; min-height: 60px;">
                                    <i class="fas fa-quote-left me-1 text-success"></i>
                                    {{ Str::limit($room->view, 80) }}
                                </p>
                                <small class="text-muted">
                                    <a href="{{ route('frontend.room.details', $room->id) }}" class="text-success text-decoration-none">
                                        Lire la suite <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </small>
                            </div>
                            @endif
                            
                            <!-- Équipements -->
                            <div class="room-equipments mb-3">
                                <h6 class="text-muted mb-2" style="font-size: 0.85rem;">
                                    <i class="fas fa-concierge-bell me-1"></i>Équipements
                                </h6>
                                <div class="d-flex flex-wrap gap-1">
                                    @php
                                        $facilities = $room->facilities->take(4);
                                    @endphp
                                    @foreach($facilities as $facility)
                                        <span class="badge bg-light text-dark border" style="font-size: 0.75rem;">
                                            <i class="fas fa-{{ $facility->icon ?? 'check' }} me-1"></i>{{ $facility->name }}
                                        </span>
                                    @endforeach
                                    @if($room->facilities->count() > 4)
                                        <span class="badge bg-light text-dark border" style="font-size: 0.75rem;">
                                            +{{ $room->facilities->count() - 4 }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Détails -->
                            <div class="room-details mb-3">
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-expand-arrows-alt me-1"></i> {{ $room->size ?? '25' }} m²
                                        </small>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-building me-1"></i> Étage {{ $room->floor ?? 'RDC' }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Prix et boutons -->
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                <div>
                                    <span class="h4 mb-0" style="color: #4CAF50;">
                                        {{ number_format($room->price, 0, ',', ' ') }} FCFA
                                    </span>
                                    <small class="text-muted d-block">par nuit</small>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('frontend.room.details', $room->id) }}" 
                                       class="btn btn-sm" 
                                       style="background-color: #4CAF50; border-color: #4CAF50; color: white;">
                                        <i class="fas fa-eye me-1"></i> Détails
                                    </a>
                                    @if($room->is_available_today)
                                        <a href="{{ route('frontend.reservation') }}?room_id={{ $room->id }}" 
                                           class="btn btn-sm" 
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
                                        Disponible à partir du {{ \Carbon\Carbon::parse($room->next_available_date)->format('d/m/Y') }}
                                    </small>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Card footer -->
                        <div class="card-footer bg-transparent border-top-0 pt-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    @if($room->room_status_id == 1)
                                        <span class="text-success">Prête à accueillir</span>
                                    @elseif($room->room_status_id == 3)
                                        <span class="text-warning">En nettoyage</span>
                                    @elseif($room->room_status_id == 4)
                                        <span class="text-danger">En maintenance</span>
                                    @else
                                        <span class="text-secondary">Statut: {{ $room->roomStatus->name ?? 'N/A' }}</span>
                                    @endif
                                </small>
                                <small class="text-muted">
                                    <i class="fas fa-star me-1 text-warning"></i>
                                    {{ $room->average_rating ?? '4.5' }}/5
                                </small>
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
                        <h4 style="color: #2E7D32;">Aucune chambre trouvée</h4>
                        <p class="text-muted mb-4">Aucune chambre ne correspond à vos critères de recherche.</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('frontend.rooms') }}" class="btn" style="background-color: #4CAF50; border-color: #4CAF50; color: white;">
                                <i class="fas fa-redo me-1"></i> Voir toutes les chambres
                            </a>
                            <a href="{{ route('frontend.contact') }}" class="btn btn-outline-success">
                                <i class="fas fa-question-circle me-1"></i> Demander conseil
                            </a>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($rooms->hasPages())
            <div class="mt-5 pt-4">
                <nav aria-label="Navigation des chambres" class="d-flex justify-content-center">
                    {{ $rooms->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                </nav>
            </div>
            @endif
        </div>
    </section>

    <!-- Section comparaison -->
    <section class="py-5" style="background-color: #F8FDF9;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 style="color: #2E7D32;">
                    <i class="fas fa-balance-scale me-2"></i>
                    Comparez nos chambres
                </h2>
                <p class="text-muted">Trouvez celle qui correspond parfaitement à vos besoins</p>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover" style="border-color: #C8E6C9;">
                    <thead style="background-color: #E8F5E9;">
                        <tr>
                            <th style="color: #2E7D32;">Chambre</th>
                            <th style="color: #2E7D32;">Type</th>
                            <th style="color: #2E7D32;">Capacité</th>
                            <th style="color: #2E7D32;">Surface</th>
                            <th style="color: #2E7D32;">Prix/nuit</th>
                            <th style="color: #2E7D32;">Équipements</th>
                            <th style="color: #2E7D32;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rooms->take(3) as $room)
                        <tr>
                            <td>
                                <strong>{{ $room->name }}</strong>
                                <div class="small text-muted">Chambre {{ $room->number }}</div>
                            </td>
                            <td>{{ $room->type->name }}</td>
                            <td class="text-center">
                                <span class="badge" style="background-color: #81C784;">
                                    {{ $room->capacity }}
                                </span>
                            </td>
                            <td>{{ $room->size ?? 'N/A' }} m²</td>
                            <td>
                                <strong style="color: #4CAF50;">
                                    {{ number_format($room->price, 0, ',', ' ') }} FCFA
                                </strong>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($room->facilities->take(3) as $facility)
                                        <span class="badge bg-light text-dark" style="font-size: 0.7rem;">
                                            {{ $facility->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('frontend.room.details', $room->id) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
                            <h3 style="color: #2E7D32;">
                                <i class="fas fa-question-circle me-2"></i>
                                Besoin d'aide pour choisir ?
                            </h3>
                            <p class="text-muted mb-0">Notre équipe est à votre disposition pour vous conseiller et vous aider à trouver la chambre parfaite pour votre séjour.</p>
                            <div class="mt-3">
                                <a href="https://wa.me/229XXXXXXXXX" target="_blank" class="btn btn-success me-2">
                                    <i class="fab fa-whatsapp me-2"></i>WhatsApp
                                </a>
                                <a href="mailto:reservations@lecactushotel.bj" class="btn btn-outline-success me-2">
                                    <i class="fas fa-envelope me-2"></i>Email
                                </a>
                                <a href="tel:+229XXXXXXXXX" class="btn btn-outline-success">
                                    <i class="fas fa-phone-alt me-2"></i>Appeler
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <div class="d-grid">
                                <a href="{{ route('frontend.contact') }}" class="btn btn-lg" style="background-color: #2E7D32; border-color: #2E7D32; color: white;">
                                    <i class="fas fa-calendar-check me-2"></i> Demander un devis
                                </a>
                                <small class="text-muted mt-2">Réponse sous 24h maximum</small>
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
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                url('https://images.unsplash.com/photo-1584132967334-10e028bd69f7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 150px 0;
}

.room-card {
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
}

.room-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(76, 175, 80, 0.2) !important;
}

.room-card:hover .room-image {
    transform: scale(1.05);
}

.room-image-container {
    position: relative;
    overflow: hidden;
}

.favorite-btn:hover {
    background-color: #FF5252 !important;
    color: white !important;
}

.favorite-btn.active {
    background-color: #FF5252 !important;
    color: white !important;
}

.favorite-btn.active i {
    font-family: "Font Awesome 5 Solid" !important;
}

.sort-btn.active {
    background-color: #4CAF50 !important;
    color: white !important;
}

.view-btn.active {
    background-color: #4CAF50 !important;
    color: white !important;
}

.form-select:focus {
    border-color: #4CAF50 !important;
    box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25) !important;
}

@media (max-width: 768px) {
    .hero-section-rooms {
        padding: 100px 0;
    }
    
    .hero-section-rooms h1 {
        font-size: 2.2rem;
    }
    
    .room-card .btn {
        padding: 6px 12px;
        font-size: 0.85rem;
    }
    
    .sort-btn, .view-btn {
        padding: 5px 10px;
        font-size: 0.9rem;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .room-details .col-6 {
        width: 100%;
        margin-bottom: 5px;
    }
    
    .card-title {
        font-size: 1.1rem;
    }
    
    .card-text {
        font-size: 0.85rem;
    }
}

.room-item {
    animation: fadeInUp 0.5s ease-out;
    animation-fill-mode: both;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.room-item:nth-child(1) { animation-delay: 0.1s; }
.room-item:nth-child(2) { animation-delay: 0.2s; }
.room-item:nth-child(3) { animation-delay: 0.3s; }
.room-item:nth-child(4) { animation-delay: 0.4s; }
.room-item:nth-child(5) { animation-delay: 0.5s; }
.room-item:nth-child(6) { animation-delay: 0.6s; }

.view-list .col-lg-4 {
    flex: 0 0 100%;
    max-width: 100%;
}

.view-list .room-card {
    display: flex;
    flex-direction: row;
    height: auto;
}

.view-list .room-image-container {
    flex: 0 0 300px;
    height: 200px;
}

.view-list .card-body {
    flex: 1;
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
    
    // Gestion des favoris
    document.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const roomId = this.dataset.roomId;
            const icon = this.querySelector('i');
            
            this.classList.toggle('active');
            
            if (this.classList.contains('active')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                showToast('Chambre ajoutée aux favoris', 'success');
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                showToast('Chambre retirée des favoris', 'info');
            }
            
            let favorites = JSON.parse(localStorage.getItem('room_favorites') || '[]');
            if (this.classList.contains('active')) {
                if (!favorites.includes(roomId)) {
                    favorites.push(roomId);
                }
            } else {
                favorites = favorites.filter(id => id !== roomId);
            }
            localStorage.setItem('room_favorites', JSON.stringify(favorites));
        });
    });
    
    // Restaurer l'état des favoris
    function restoreFavorites() {
        const favorites = JSON.parse(localStorage.getItem('room_favorites') || '[]');
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            if (favorites.includes(btn.dataset.roomId)) {
                btn.classList.add('active');
                const icon = btn.querySelector('i');
                icon.classList.remove('far');
                icon.classList.add('fas');
            }
        });
    }
    
    // Tri des chambres
    document.querySelectorAll('.sort-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.sort-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            sortRooms(this.dataset.sort);
        });
    });
    
    function sortRooms(sortType) {
        const container = document.getElementById('rooms-grid');
        const items = Array.from(container.querySelectorAll('.room-item'));
        
        items.sort((a, b) => {
            switch(sortType) {
                case 'price_asc':
                    return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                case 'price_desc':
                    return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                case 'name_asc':
                    return a.dataset.name.localeCompare(b.dataset.name);
                case 'capacity_desc':
                    return parseFloat(b.dataset.capacity) - parseFloat(a.dataset.capacity);
                default:
                    return 0;
            }
        });
        
        items.forEach(item => container.appendChild(item));
        items.forEach((item, index) => {
            item.style.animationDelay = `${index * 0.05}s`;
            item.classList.remove('room-item');
            void item.offsetWidth;
            item.classList.add('room-item');
        });
    }
    
    // Changement de vue (grille/liste)
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const viewType = this.dataset.view;
            const grid = document.getElementById('rooms-grid');
            
            if (viewType === 'list') {
                grid.classList.add('view-list');
            } else {
                grid.classList.remove('view-list');
            }
        });
    });
    
    // Fonction pour afficher les toasts
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        const toastContainer = document.getElementById('toastContainer') || createToastContainer();
        toastContainer.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', function() {
            this.remove();
        });
    }
    
    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(container);
        return container;
    }
    
    // Initialisation
    restoreFavorites();
});
</script>
@endpush