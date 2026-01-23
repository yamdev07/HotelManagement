@extends('template.master')
@section('title', 'Check-in Direct')
@section('content')
    <style>
        .direct-checkin-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            padding: 30px;
            color: white;
            margin-bottom: 30px;
        }
        .room-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .room-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s;
            cursor: pointer;
        }
        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            border-color: #0d6efd;
        }
        .room-card.selected {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
        }
        .room-image {
            height: 150px;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }
        .room-info {
            padding: 15px;
        }
        .room-price {
            font-size: 1.25rem;
            font-weight: bold;
            color: #28a745;
        }
        .room-features {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }
        .feature-icon {
            width: 24px;
            height: 24px;
            background-color: #e7f1ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0d6efd;
            font-size: 0.8rem;
        }
        .form-stepper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        .stepper-line {
            position: absolute;
            top: 20px;
            left: 50px;
            right: 50px;
            height: 2px;
            background-color: #dee2e6;
            z-index: 1;
        }
        .step {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 2;
        }
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #dee2e6;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
            border: 4px solid white;
        }
        .step.active .step-number {
            background-color: #0d6efd;
            color: white;
        }
        .step.completed .step-number {
            background-color: #28a745;
            color: white;
        }
        .step-label {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .step.active .step-label {
            color: #0d6efd;
            font-weight: bold;
        }
        .form-tab {
            display: none;
        }
        .form-tab.active {
            display: block;
        }
        .search-customer-results {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-top: 10px;
        }
        .customer-result-item {
            padding: 10px 15px;
            border-bottom: 1px solid #dee2e6;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .customer-result-item:hover {
            background-color: #f8f9fa;
        }
        .customer-result-item:last-child {
            border-bottom: none;
        }
        .date-picker-container {
            position: relative;
        }
        .date-picker-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #6c757d;
        }
        .availability-check {
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }
        .availability-available {
            background-color: #d1e7dd;
            border: 1px solid #badbcc;
        }
        .availability-unavailable {
            background-color: #f8d7da;
            border: 1px solid #f5c2c7;
        }
        .summary-box {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard.index') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('checkin.index') }}">Check-in</a>
                        </li>
                        <li class="breadcrumb-item active">Check-in Direct</li>
                    </ol>
                </nav>
                
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="h4 mb-0">
                        <i class="fas fa-user-plus text-primary me-2"></i>
                        Check-in Direct (Sans Réservation)
                    </h2>
                    <a href="{{ route('checkin.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
                <p class="text-muted">Enregistrez un client directement sans réservation préalable</p>
            </div>
        </div>

        <!-- Stepper -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="form-stepper">
                    <div class="stepper-line"></div>
                    <div class="step active" id="step-1">
                        <div class="step-number">1</div>
                        <div class="step-label">Client</div>
                    </div>
                    <div class="step" id="step-2">
                        <div class="step-number">2</div>
                        <div class="step-label">Dates</div>
                    </div>
                    <div class="step" id="step-3">
                        <div class="step-number">3</div>
                        <div class="step-label">Chambre</div>
                    </div>
                    <div class="step" id="step-4">
                        <div class="step-number">4</div>
                        <div class="step-label">Confirmation</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- État initial -->
        <div class="direct-checkin-container">
            <h4 class="mb-3">
                <i class="fas fa-user-plus me-2"></i>Enregistrement d'un nouveau client
            </h4>
            <p class="mb-0">
                Cette fonctionnalité vous permet d'enregistrer un client qui n'a pas de réservation préalable.
                Le client sera directement enregistré dans une chambre disponible.
            </p>
        </div>

        <form method="POST" action="{{ route('transaction.store') }}" id="direct-checkin-form">
            @csrf
            <input type="hidden" name="checkin_method" value="direct">
            
            <!-- Étape 1: Client -->
            <div class="form-tab active" id="tab-1">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informations Client</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Recherchez d'abord si le client existe déjà dans le système.
                        </div>
                        
                        <!-- Recherche client existant -->
                        <div class="mb-4">
                            <h6>Rechercher un client existant</h6>
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <input type="text" 
                                           class="form-control" 
                                           id="search-customer"
                                           placeholder="Nom, téléphone ou email..."
                                           autocomplete="off">
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-outline-primary w-100" onclick="searchCustomers()">
                                        <i class="fas fa-search me-2"></i>Rechercher
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Résultats de recherche -->
                            <div class="search-customer-results" id="customer-results" style="display: none;">
                                <!-- Les résultats seront injectés ici -->
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Formulaire nouveau client -->
                        <h6 class="mb-3">Nouveau Client</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nom complet *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Téléphone *</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nationality" class="form-label">Nationalité *</label>
                                <input type="text" class="form-control @error('nationality') is-invalid @enderror" 
                                       id="nationality" name="nationality" value="{{ old('nationality') }}" required>
                                @error('nationality')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="id_type" class="form-label">Type de pièce d'identité *</label>
                                <select class="form-control @error('id_type') is-invalid @enderror" 
                                        id="id_type" name="id_type" required>
                                    <option value="">Sélectionnez...</option>
                                    @foreach($idTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('id_type') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="id_number" class="form-label">Numéro de pièce *</label>
                                <input type="text" class="form-control @error('id_number') is-invalid @enderror" 
                                       id="id_number" name="id_number" value="{{ old('id_number') }}" required>
                                @error('id_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" onclick="nextStep(2)">
                                <i class="fas fa-arrow-right me-2"></i>Continuer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Étape 2: Dates -->
            <div class="form-tab" id="tab-2">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Dates du Séjour</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="check_in" class="form-label">Date d'arrivée *</label>
                                    <div class="date-picker-container">
                                        <input type="date" 
                                               class="form-control @error('check_in') is-invalid @enderror" 
                                               id="check_in" 
                                               name="check_in" 
                                               value="{{ old('check_in', date('Y-m-d')) }}"
                                               required>
                                        <span class="date-picker-icon">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                        @error('check_in')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="check_out" class="form-label">Date de départ *</label>
                                    <div class="date-picker-container">
                                        <input type="date" 
                                               class="form-control @error('check_out') is-invalid @enderror" 
                                               id="check_out" 
                                               name="check_out" 
                                               value="{{ old('check_out', date('Y-m-d', strtotime('+1 day'))) }}"
                                               required>
                                        <span class="date-picker-icon">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                        @error('check_out')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="adults" class="form-label">Nombre d'adultes *</label>
                                    <input type="number" class="form-control @error('adults') is-invalid @enderror" 
                                           id="adults" name="adults" 
                                           value="{{ old('adults', 1) }}" min="1" max="10" required>
                                    @error('adults')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="children" class="form-label">Nombre d'enfants</label>
                                    <input type="number" class="form-control @error('children') is-invalid @enderror" 
                                           id="children" name="children" 
                                           value="{{ old('children', 0) }}" min="0" max="10">
                                    @error('children')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Calcul des nuits -->
                        <div class="summary-box">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <p class="mb-1"><strong>Nuits</strong></p>
                                    <h3 id="nights-count">0</h3>
                                </div>
                                <div class="col-md-8">
                                    <p class="mb-2"><strong>Séjour:</strong></p>
                                    <p class="mb-1">
                                        <span id="arrival-date"></span>
                                        <i class="fas fa-arrow-right mx-2"></i>
                                        <span id="departure-date"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" onclick="prevStep(1)">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nextStep(3)">
                                <i class="fas fa-arrow-right me-2"></i>Continuer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Étape 3: Chambre -->
            <div class="form-tab" id="tab-3">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-bed me-2"></i>Sélection de la Chambre</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Sélectionnez une chambre disponible pour la période choisie.
                        </div>
                        
                        <!-- Filtres -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="filter-type" class="form-label">Type de chambre</label>
                                <select class="form-control" id="filter-type" onchange="filterRooms()">
                                    <option value="">Tous les types</option>
                                    @php
                                        $roomTypes = \App\Models\Type::pluck('name', 'id');
                                    @endphp
                                    @foreach($roomTypes as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="filter-capacity" class="form-label">Capacité minimum</label>
                                <select class="form-control" id="filter-capacity" onchange="filterRooms()">
                                    <option value="1">1 personne</option>
                                    <option value="2">2 personnes</option>
                                    <option value="3">3 personnes</option>
                                    <option value="4">4 personnes</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="filter-price" class="form-label">Prix maximum</label>
                                <select class="form-control" id="filter-price" onchange="filterRooms()">
                                    <option value="">Tous les prix</option>
                                    <option value="50000">50,000 CFA</option>
                                    <option value="100000">100,000 CFA</option>
                                    <option value="150000">150,000 CFA</option>
                                    <option value="200000">200,000 CFA</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Liste des chambres -->
                        <div class="room-grid" id="rooms-grid">
                            @foreach($availableRooms as $room)
                                <div class="room-card" 
                                     data-type="{{ $room->type_id }}"
                                     data-capacity="{{ $room->capacity }}"
                                     data-price="{{ $room->price }}"
                                     onclick="selectRoom({{ $room->id }}, {{ $room->price }}, '{{ $room->number }}')"
                                     id="room-card-{{ $room->id }}">
                                    <div class="room-image">
                                        @if($room->first_image_url && $room->first_image_url != asset('img/default/default-room.png'))
                                            <img src="{{ $room->first_image_url }}" alt="Chambre {{ $room->number }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <i class="fas fa-bed fa-3x"></i>
                                        @endif
                                    </div>
                                    <div class="room-info">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="mb-0">Chambre {{ $room->number }}</h5>
                                            <span class="room-price">{{ Helper::formatCFA($room->price) }}/nuit</span>
                                        </div>
                                        <p class="text-muted small mb-2">{{ $room->type->name ?? 'N/A' }}</p>
                                        <div class="room-features">
                                            <span class="feature-icon" title="Capacité">
                                                <i class="fas fa-user"></i> {{ $room->capacity }}
                                            </span>
                                            <span class="feature-icon" title="Salle de bain">
                                                <i class="fas fa-bath"></i>
                                            </span>
                                            @if($room->facilities->where('name', 'Wifi')->first())
                                                <span class="feature-icon" title="Wifi">
                                                    <i class="fas fa-wifi"></i>
                                                </span>
                                            @endif
                                            @if($room->facilities->where('name', 'Climatisation')->first())
                                                <span class="feature-icon" title="Climatisation">
                                                    <i class="fas fa-snowflake"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Aucune chambre disponible -->
                        @if($availableRooms->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-bed fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">Aucune chambre disponible</h5>
                                <p class="text-muted">
                                    Aucune chambre n'est disponible pour les dates sélectionnées.
                                    Veuillez ajuster vos dates ou contacter la réception.
                                </p>
                                <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)">
                                    <i class="fas fa-arrow-left me-2"></i>Modifier les dates
                                </button>
                            </div>
                        @endif
                        
                        <!-- Chambre sélectionnée -->
                        <div class="summary-box mt-4" id="selected-room-info" style="display: none;">
                            <h6><i class="fas fa-check-circle text-success me-2"></i>Chambre sélectionnée</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong id="selected-room-number"></strong></p>
                                    <p class="mb-1 text-muted" id="selected-room-type"></p>
                                    <p class="mb-0 text-muted" id="selected-room-capacity"></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Prix par nuit:</strong> <span id="selected-room-price"></span></p>
                                    <p class="mb-0"><strong>Total séjour:</strong> <span id="selected-room-total"></span></p>
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" name="room_id" id="selected_room_id">
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </button>
                            <button type="button" class="btn btn-primary" id="continue-to-summary" onclick="nextStep(4)" disabled>
                                <i class="fas fa-arrow-right me-2"></i>Continuer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Étape 4: Confirmation -->
            <div class="form-tab" id="tab-4">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Confirmation du Check-in</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success">
                            <i class="fas fa-clipboard-check fa-2x mb-3"></i>
                            <h5>Résumé du Check-in Direct</h5>
                            <p class="mb-0">Vérifiez les informations avant de finaliser l'enregistrement</p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="summary-box">
                                    <h6><i class="fas fa-user me-2"></i>Informations Client</h6>
                                    <p class="mb-1"><strong id="summary-name"></strong></p>
                                    <p class="mb-1"><span id="summary-phone"></span></p>
                                    <p class="mb-1"><span id="summary-email"></span></p>
                                    <p class="mb-0"><span id="summary-nationality"></span></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="summary-box">
                                    <h6><i class="fas fa-bed me-2"></i>Informations Chambre</h6>
                                    <p class="mb-1"><strong id="summary-room"></strong></p>
                                    <p class="mb-1">Type: <span id="summary-room-type"></span></p>
                                    <p class="mb-1">Capacité: <span id="summary-room-capacity"></span> personnes</p>
                                    <p class="mb-0">Prix: <span id="summary-room-price"></span>/nuit</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="summary-box">
                                    <h6><i class="fas fa-calendar-alt me-2"></i>Dates du Séjour</h6>
                                    <p class="mb-1">Arrivée: <strong id="summary-checkin"></strong></p>
                                    <p class="mb-1">Départ: <strong id="summary-checkout"></strong></p>
                                    <p class="mb-0">Durée: <strong id="summary-nights"></strong> nuits</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="summary-box">
                                    <h6><i class="fas fa-money-bill-wave me-2"></i>Détails Financiers</h6>
                                    <p class="mb-1">Prix/nuit: <span id="summary-price-night"></span></p>
                                    <p class="mb-1">Total séjour: <span id="summary-total"></span></p>
                                    <p class="mb-0">Méthode: <strong>Check-in direct</strong></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Paiement d'acompte -->
                        <div class="summary-box mt-4">
                            <h6><i class="fas fa-credit-card me-2"></i>Paiement d'Acompte</h6>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="pay-deposit" name="pay_deposit" value="1">
                                <label class="form-check-label" for="pay-deposit">
                                    Prendre un acompte de 30% à l'arrivée
                                </label>
                            </div>
                            <div id="deposit-amount" style="display: none;">
                                <p class="mb-0 text-success">
                                    <i class="fas fa-money-bill-wave me-2"></i>
                                    Montant de l'acompte: <strong id="deposit-amount-value"></strong>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Notes spéciales -->
                        <div class="mb-3">
                            <label for="special_requests" class="form-label">Demandes Spéciales</label>
                            <textarea class="form-control" id="special_requests" name="special_requests" rows="3" placeholder="Demandes spéciales du client..."></textarea>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Important:</strong> Cette action créera une nouvelle réservation et enregistrera immédiatement 
                            le client dans la chambre sélectionnée. Le statut sera directement "active".
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" onclick="prevStep(3)">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </button>
                            <button type="submit" class="btn btn-success" id="confirm-checkin">
                                <i class="fas fa-check-circle me-2"></i>Confirmer le Check-in Direct
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('footer')
<script>
let currentStep = 1;
let selectedRoomId = null;
let selectedRoomPrice = null;
let selectedRoomNumber = null;
let selectedRoomType = null;
let selectedRoomCapacity = null;
let nightsCount = 0;
let totalPrice = 0;

function updateStepIndicator(step) {
    // Mettre à jour toutes les étapes
    for (let i = 1; i <= 4; i++) {
        const stepElement = document.getElementById(`step-${i}`);
        const stepNumber = stepElement.querySelector('.step-number');
        
        if (i < step) {
            stepElement.classList.remove('active');
            stepElement.classList.add('completed');
            stepNumber.innerHTML = '<i class="fas fa-check"></i>';
        } else if (i === step) {
            stepElement.classList.add('active');
            stepElement.classList.remove('completed');
            stepNumber.textContent = i;
        } else {
            stepElement.classList.remove('active', 'completed');
            stepNumber.textContent = i;
        }
    }
}

function showTab(tabNumber) {
    // Cacher tous les onglets
    for (let i = 1; i <= 4; i++) {
        document.getElementById(`tab-${i}`).classList.remove('active');
    }
    // Afficher l'onglet actif
    document.getElementById(`tab-${tabNumber}`).classList.add('active');
}

function nextStep(next) {
    // Validation de l'étape actuelle
    if (currentStep === 1) {
        // Validation des informations client
        const name = document.getElementById('name').value;
        const phone = document.getElementById('phone').value;
        const idType = document.getElementById('id_type').value;
        const idNumber = document.getElementById('id_number').value;
        const nationality = document.getElementById('nationality').value;
        
        if (!name || !phone || !idType || !idNumber || !nationality) {
            alert('Veuillez remplir tous les champs obligatoires');
            return;
        }
    }
    
    if (currentStep === 2) {
        // Validation des dates
        const checkIn = new Date(document.getElementById('check_in').value);
        const checkOut = new Date(document.getElementById('check_out').value);
        const adults = document.getElementById('adults').value;
        
        if (checkOut <= checkIn) {
            alert('La date de départ doit être après la date d\'arrivée');
            return;
        }
        
        if (!adults || adults < 1) {
            alert('Le nombre d\'adultes doit être d\'au moins 1');
            return;
        }
        
        // Calculer les nuits
        const timeDiff = checkOut.getTime() - checkIn.getTime();
        nightsCount = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
        if (nightsCount < 1) {
            alert('La durée du séjour doit être d\'au moins 1 nuit');
            return;
        }
        
        // Mettre à jour l'affichage
        document.getElementById('nights-count').textContent = nightsCount;
        document.getElementById('arrival-date').textContent = formatDate(checkIn);
        document.getElementById('departure-date').textContent = formatDate(checkOut);
        
        // Vérifier la disponibilité des chambres
        checkRoomAvailability();
    }
    
    if (currentStep === 3) {
        if (!selectedRoomId) {
            alert('Veuillez sélectionner une chambre');
            return;
        }
        
        // Mettre à jour le résumé
        updateSummary();
    }
    
    currentStep = next;
    updateStepIndicator(currentStep);
    showTab(currentStep);
}

function prevStep(prev) {
    currentStep = prev;
    updateStepIndicator(currentStep);
    showTab(currentStep);
}

function searchCustomers() {
    const searchTerm = document.getElementById('search-customer').value;
    if (!searchTerm || searchTerm.length < 2) {
        alert('Veuillez entrer au moins 2 caractères pour la recherche');
        return;
    }
    
    const resultsContainer = document.getElementById('customer-results');
    resultsContainer.innerHTML = '<div class="text-center py-3"><i class="fas fa-spinner fa-spin"></i> Recherche en cours...</div>';
    resultsContainer.style.display = 'block';
    
    fetch(`/api/customers?search=${encodeURIComponent(searchTerm)}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                resultsContainer.innerHTML = '<div class="text-center py-3 text-muted">Aucun client trouvé</div>';
                return;
            }
            
            let html = '';
            data.forEach(customer => {
                html += `
                    <div class="customer-result-item" onclick="useExistingCustomer(${customer.id}, '${customer.name.replace("'", "\\'")}', '${customer.phone}', '${customer.email || ''}')">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${customer.name}</strong>
                                <div class="text-muted small">
                                    ${customer.phone} ${customer.email ? '• ' + customer.email : ''}
                                </div>
                            </div>
                            <span class="badge bg-light text-dark">${customer.reservation_count || 0} réservations</span>
                        </div>
                    </div>
                `;
            });
            
            resultsContainer.innerHTML = html;
        })
        .catch(error => {
            console.error('Erreur:', error);
            resultsContainer.innerHTML = '<div class="text-center py-3 text-danger">Erreur lors de la recherche</div>';
        });
}

function useExistingCustomer(id, name, phone, email) {
    document.getElementById('name').value = name;
    document.getElementById('phone').value = phone;
    document.getElementById('email').value = email;
    
    // Masquer les résultats
    document.getElementById('customer-results').style.display = 'none';
    
    // Focus sur le champ suivant
    document.getElementById('nationality').focus();
}

function formatDate(date) {
    return date.toLocaleDateString('fr-FR', {
        weekday: 'short',
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
}

function selectRoom(roomId, price, number) {
    // Désélectionner toutes les chambres
    document.querySelectorAll('.room-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Sélectionner la chambre choisie
    const roomCard = document.getElementById(`room-card-${roomId}`);
    roomCard.classList.add('selected');
    
    // Mettre à jour les variables globales
    selectedRoomId = roomId;
    selectedRoomPrice = price;
    selectedRoomNumber = number;
    selectedRoomType = roomCard.querySelector('.text-muted.small').textContent;
    selectedRoomCapacity = roomCard.querySelector('[title="Capacité"]').textContent.replace(' ', '');
    
    // Calculer le total
    totalPrice = price * nightsCount;
    
    // Afficher les informations de la chambre sélectionnée
    const selectedRoomInfo = document.getElementById('selected-room-info');
    document.getElementById('selected-room-number').textContent = `Chambre ${number}`;
    document.getElementById('selected-room-type').textContent = selectedRoomType;
    document.getElementById('selected-room-capacity').textContent = `${selectedRoomCapacity} personnes`;
    document.getElementById('selected-room-price').textContent = formatCFA(price);
    document.getElementById('selected-room-total').textContent = formatCFA(totalPrice);
    selectedRoomInfo.style.display = 'block';
    
    // Activer le bouton continuer
    document.getElementById('continue-to-summary').disabled = false;
    
    // Mettre à jour le champ caché
    document.getElementById('selected_room_id').value = roomId;
}

function filterRooms() {
    const typeFilter = document.getElementById('filter-type').value;
    const capacityFilter = parseInt(document.getElementById('filter-capacity').value);
    const priceFilter = document.getElementById('filter-price').value;
    
    document.querySelectorAll('.room-card').forEach(card => {
        const type = card.dataset.type;
        const capacity = parseInt(card.dataset.capacity);
        const price = parseInt(card.dataset.price);
        
        let show = true;
        
        if (typeFilter && type !== typeFilter) {
            show = false;
        }
        
        if (capacityFilter && capacity < capacityFilter) {
            show = false;
        }
        
        if (priceFilter && price > parseInt(priceFilter)) {
            show = false;
        }
        
        card.style.display = show ? 'block' : 'none';
    });
}

function formatCFA(amount) {
    return new Intl.NumberFormat('fr-FR', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount) + ' CFA';
}

function checkRoomAvailability() {
    const checkIn = document.getElementById('check_in').value;
    const checkOut = document.getElementById('check_out').value;
    const adults = parseInt(document.getElementById('adults').value);
    const children = parseInt(document.getElementById('children').value || 0);
    const totalPersons = adults + children;
    
    // Mettre à jour la capacité du filtre
    document.getElementById('filter-capacity').value = totalPersons;
    
    // Filtrer les chambres
    filterRooms();
}

function updateSummary() {
    // Informations client
    document.getElementById('summary-name').textContent = document.getElementById('name').value;
    document.getElementById('summary-phone').textContent = document.getElementById('phone').value;
    document.getElementById('summary-email').textContent = document.getElementById('email').value || 'Non renseigné';
    document.getElementById('summary-nationality').textContent = document.getElementById('nationality').value;
    
    // Informations chambre
    document.getElementById('summary-room').textContent = `Chambre ${selectedRoomNumber}`;
    document.getElementById('summary-room-type').textContent = selectedRoomType;
    document.getElementById('summary-room-capacity').textContent = selectedRoomCapacity;
    document.getElementById('summary-room-price').textContent = formatCFA(selectedRoomPrice);
    
    // Dates
    const checkIn = new Date(document.getElementById('check_in').value);
    const checkOut = new Date(document.getElementById('check_out').value);
    document.getElementById('summary-checkin').textContent = formatDate(checkIn);
    document.getElementById('summary-checkout').textContent = formatDate(checkOut);
    document.getElementById('summary-nights').textContent = nightsCount;
    
    // Détails financiers
    document.getElementById('summary-price-night').textContent = formatCFA(selectedRoomPrice);
    document.getElementById('summary-total').textContent = formatCFA(totalPrice);
    
    // Acompte
    const depositAmount = totalPrice * 0.3;
    document.getElementById('deposit-amount-value').textContent = formatCFA(depositAmount);
    
    // Gérer l'affichage de l'acompte
    const depositCheckbox = document.getElementById('pay-deposit');
    const depositAmountDiv = document.getElementById('deposit-amount');
    
    depositCheckbox.addEventListener('change', function() {
        depositAmountDiv.style.display = this.checked ? 'block' : 'none';
    });
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    updateStepIndicator(1);
    
    // Calculer les nuits au chargement
    const checkIn = new Date(document.getElementById('check_in').value);
    const checkOut = new Date(document.getElementById('check_out').value);
    const timeDiff = checkOut.getTime() - checkIn.getTime();
    nightsCount = Math.ceil(timeDiff / (1000 * 3600 * 24));
    document.getElementById('nights-count').textContent = nightsCount;
    document.getElementById('arrival-date').textContent = formatDate(checkIn);
    document.getElementById('departure-date').textContent = formatDate(checkOut);
    
    // Validation des dates
    document.getElementById('check_in').addEventListener('change', function() {
        const checkInDate = new Date(this.value);
        const nextDay = new Date(checkInDate);
        nextDay.setDate(nextDay.getDate() + 1);
        const minDate = nextDay.toISOString().split('T')[0];
        document.getElementById('check_out').min = minDate;
        
        // Si la date de départ est antérieure, la mettre à jour
        const checkOutInput = document.getElementById('check_out');
        if (checkOutInput.value && new Date(checkOutInput.value) < nextDay) {
            checkOutInput.value = minDate;
        }
        
        checkRoomAvailability();
    });
    
    document.getElementById('check_out').addEventListener('change', checkRoomAvailability);
    document.getElementById('adults').addEventListener('change', checkRoomAvailability);
    document.getElementById('children').addEventListener('change', checkRoomAvailability);
    
    // Empêcher la soumission multiple
    const form = document.getElementById('direct-checkin-form');
    form.addEventListener('submit', function(e) {
        const submitButton = document.getElementById('confirm-checkin');
        if (submitButton.disabled) {
            e.preventDefault();
            return false;
        }
        
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Traitement...';
        
        return true;
    });
});
</script>
@endsection