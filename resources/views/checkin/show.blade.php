@extends('template.master')
@section('title', 'Check-in - Réservation')
@section('content')
    <style>
        .form-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #0d6efd;
        }
        .info-box {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .room-status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }
        .room-available { background-color: #28a745; }
        .room-occupied { background-color: #dc3545; }
        .room-maintenance { background-color: #ffc107; }
        .room-cleaning { background-color: #17a2b8; }
        .alternative-room {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .alternative-room:hover {
            border-color: #0d6efd;
            background-color: #f0f8ff;
        }
        .alternative-room.selected {
            border-color: #0d6efd;
            background-color: #e7f1ff;
        }
        .price-difference {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }
        .price-positive { background-color: #ffe6e6; color: #dc3545; }
        .price-negative { background-color: #e6ffe6; color: #28a745; }
        .price-neutral { background-color: #f8f9fa; color: #6c757d; }
        .form-stepper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .step {
            flex: 1;
            text-align: center;
            position: relative;
            padding: 10px;
        }
        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 20px;
            right: -50%;
            width: 100%;
            height: 2px;
            background-color: #dee2e6;
            z-index: 1;
        }
        .step.active::after {
            background-color: #0d6efd;
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
            position: relative;
            z-index: 2;
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
                        <li class="breadcrumb-item active">Check-in Réservation #{{ $transaction->id }}</li>
                    </ol>
                </nav>
                
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="h4 mb-0">
                        <i class="fas fa-door-open text-primary me-2"></i>
                        Check-in Réservation #{{ $transaction->id }}
                    </h2>
                    <a href="{{ route('checkin.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
                <p class="text-muted">Enregistrez l'arrivée du client et complétez les informations</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {!! session('success') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Stepper -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="form-stepper">
                    <div class="step active" id="step-1">
                        <div class="step-number">1</div>
                        <div class="step-label">Vérification</div>
                    </div>
                    <div class="step" id="step-2">
                        <div class="step-number">2</div>
                        <div class="step-label">Informations</div>
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

        <div class="row">
            <div class="col-lg-8">
                <form method="POST" action="{{ route('checkin.store', $transaction) }}" id="checkin-form">
                    @csrf
                    
                    <!-- Étape 1: Vérification -->
                    <div class="form-tab active" id="tab-1">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Vérification de la Réservation</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Vérifiez les informations de la réservation avant de procéder au check-in.
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-box">
                                            <h6><i class="fas fa-user me-2"></i>Client</h6>
                                            <p class="mb-1"><strong>{{ $transaction->customer->name }}</strong></p>
                                            <p class="mb-1 text-muted small">{{ $transaction->customer->phone }}</p>
                                            <p class="mb-0 text-muted small">{{ $transaction->customer->email ?? 'Email non renseigné' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-box">
                                            <h6><i class="fas fa-bed me-2"></i>Chambre Réservée</h6>
                                            <p class="mb-1"><strong>Chambre {{ $transaction->room->number }}</strong></p>
                                            <p class="mb-1 text-muted small">{{ $transaction->room->type->name ?? 'Type non spécifié' }}</p>
                                            <p class="mb-0 text-muted small">{{ $transaction->room->capacity }} personnes • {{ Helper::formatCFA($transaction->room->price) }}/nuit</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="info-box">
                                            <h6><i class="fas fa-calendar-alt me-2"></i>Dates</h6>
                                            <p class="mb-1"><strong>Arrivée:</strong> {{ $transaction->check_in->format('d/m/Y H:i') }}</p>
                                            <p class="mb-1"><strong>Départ:</strong> {{ $transaction->check_out->format('d/m/Y H:i') }}</p>
                                            <p class="mb-0"><strong>Durée:</strong> {{ $transaction->nights }} nuit(s)</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-box">
                                            <h6><i class="fas fa-money-bill-wave me-2"></i>Paiement</h6>
                                            <p class="mb-1"><strong>Total:</strong> {{ Helper::formatCFA($transaction->getTotalPrice()) }}</p>
                                            <p class="mb-1"><strong>Payé:</strong> {{ Helper::formatCFA($transaction->getTotalPayment()) }}</p>
                                            <p class="mb-0">
                                                <strong>Solde:</strong> 
                                                <span class="{{ $transaction->getRemainingPayment() > 0 ? 'text-warning' : 'text-success' }}">
                                                    {{ Helper::formatCFA($transaction->getRemainingPayment()) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 text-center">
                                    @if($isRoomAvailable)
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle me-2"></i>
                                            La chambre réservée est disponible pour le séjour.
                                        </div>
                                        <button type="button" class="btn btn-primary" onclick="nextStep(2)">
                                            <i class="fas fa-arrow-right me-2"></i>Continuer
                                        </button>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            La chambre réservée n'est pas disponible pour le séjour.
                                            Vous devrez sélectionner une autre chambre.
                                        </div>
                                        <button type="button" class="btn btn-warning" onclick="nextStep(2)">
                                            <i class="fas fa-arrow-right me-2"></i>Sélectionner une autre chambre
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Étape 2: Informations -->
                    <div class="form-tab" id="tab-2">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Informations Complémentaires</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-section">
                                    <h6><i class="fas fa-users me-2"></i>Occupants</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="adults" class="form-label">Adultes *</label>
                                            <input type="number" class="form-control @error('adults') is-invalid @enderror" 
                                                   id="adults" name="adults" 
                                                   value="{{ old('adults', $transaction->person_count ?? 1) }}" 
                                                   min="1" max="10" required>
                                            @error('adults')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="children" class="form-label">Enfants (0-12 ans)</label>
                                            <input type="number" class="form-control @error('children') is-invalid @enderror" 
                                                   id="children" name="children" 
                                                   value="{{ old('children', 0) }}" 
                                                   min="0" max="10">
                                            @error('children')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-section">
                                    <h6><i class="fas fa-id-card me-2"></i>Pièce d'Identité</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="id_type" class="form-label">Type de pièce *</label>
                                            <select class="form-control @error('id_type') is-invalid @enderror" 
                                                    id="id_type" name="id_type" required>
                                                <option value="">Sélectionnez...</option>
                                                @foreach($idTypes as $value => $label)
                                                    <option value="{{ $value }}" 
                                                            {{ old('id_type') == $value ? 'selected' : '' }}>
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
                                                   id="id_number" name="id_number" 
                                                   value="{{ old('id_number') }}" 
                                                   placeholder="Ex: AB123456" required>
                                            @error('id_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nationality" class="form-label">Nationalité *</label>
                                            <input type="text" class="form-control @error('nationality') is-invalid @enderror" 
                                                   id="nationality" name="nationality" 
                                                   value="{{ old('nationality') }}" 
                                                   placeholder="Ex: Française" required>
                                            @error('nationality')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-section">
                                    <h6><i class="fas fa-comment-alt me-2"></i>Autres Informations</h6>
                                    <div class="mb-3">
                                        <label for="special_requests" class="form-label">Demandes Spéciales</label>
                                        <textarea class="form-control @error('special_requests') is-invalid @enderror" 
                                                  id="special_requests" name="special_requests" 
                                                  rows="3" placeholder="Préférences alimentaires, accessibilité, autres...">{{ old('special_requests') }}</textarea>
                                        @error('special_requests')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notes Internes</label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                                  id="notes" name="notes" 
                                                  rows="2" placeholder="Notes pour le personnel...">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-4">
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
                                <h5 class="mb-0">Sélection de la Chambre</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    @if($isRoomAvailable)
                                        La chambre réservée est disponible. Vous pouvez conserver cette chambre ou en sélectionner une autre.
                                    @else
                                        La chambre réservée n'est pas disponible. Veuillez sélectionner une chambre alternative.
                                    @endif
                                </div>
                                
                                <!-- Option 1: Conserver la chambre originale -->
                                @if($isRoomAvailable)
                                    <div class="form-check mb-4">
                                        <input class="form-check-input" type="radio" name="room_option" 
                                               id="keep_original" value="keep" checked 
                                               onchange="toggleRoomOptions('keep')">
                                        <label class="form-check-label" for="keep_original">
                                            <h6 class="mb-1">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                Conserver la chambre originale
                                            </h6>
                                            <div class="ms-4">
                                                <p class="mb-1"><strong>Chambre {{ $transaction->room->number }}</strong></p>
                                                <p class="mb-1 text-muted small">{{ $transaction->room->type->name ?? 'Type non spécifié' }}</p>
                                                <p class="mb-0 text-muted small">{{ $transaction->room->capacity }} personnes • {{ Helper::formatCFA($transaction->room->price) }}/nuit</p>
                                            </div>
                                        </label>
                                    </div>
                                @endif
                                
                                <!-- Option 2: Changer de chambre -->
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="radio" name="room_option" 
                                           id="change_room" value="change" 
                                           {{ !$isRoomAvailable ? 'checked' : '' }}
                                           onchange="toggleRoomOptions('change')">
                                    <label class="form-check-label" for="change_room">
                                        <h6 class="mb-1">
                                            <i class="fas fa-exchange-alt text-primary me-2"></i>
                                            Changer de chambre
                                        </h6>
                                        <div class="ms-4">
                                            <p class="mb-1">Sélectionnez une chambre alternative</p>
                                        </div>
                                    </label>
                                </div>
                                
                                <!-- Liste des chambres alternatives -->
                                <div id="alternative-rooms-container" style="{{ $isRoomAvailable ? 'display: none;' : '' }}">
                                    <h6 class="mb-3">Chambres disponibles pour cette période :</h6>
                                    
                                    @if($alternativeRooms->isEmpty())
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Aucune chambre alternative disponible pour cette période.
                                            Veuillez vérifier les disponibilités ou ajuster les dates.
                                        </div>
                                    @else
                                        <div class="row">
                                            @foreach($alternativeRooms as $room)
                                                <div class="col-md-6">
                                                    <div class="alternative-room" 
                                                         onclick="selectAlternativeRoom({{ $room->id }}, {{ $room->price }})"
                                                         id="room-{{ $room->id }}">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h6 class="mb-1">
                                                                    <span class="room-status-indicator room-available"></span>
                                                                    Chambre {{ $room->number }}
                                                                </h6>
                                                                <p class="mb-1 text-muted small">{{ $room->type->name }}</p>
                                                                <p class="mb-0 text-muted small">{{ $room->capacity }} personnes • {{ Helper::formatCFA($room->price) }}/nuit</p>
                                                            </div>
                                                            <div>
                                                                <i class="fas fa-check-circle text-success" 
                                                                   id="check-{{ $room->id }}" 
                                                                   style="display: none;"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        <!-- Affichage différence de prix -->
                                        <div class="mt-4" id="price-difference-info" style="display: none;">
                                            <div class="alert alert-info">
                                                <h6><i class="fas fa-money-bill-wave me-2"></i>Impact sur le prix</h6>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <p class="mb-1 small">Ancien total:</p>
                                                        <p class="h5">{{ Helper::formatCFA($transaction->getTotalPrice()) }}</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="mb-1 small">Nouveau total:</p>
                                                        <p class="h5" id="new-total-price">0 CFA</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="mb-1 small">Différence:</p>
                                                        <p class="h5" id="price-difference">0 CFA</p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="confirmed_price_change" name="confirmed_price_change">
                                                <label class="form-check-label" for="confirmed_price_change">
                                                    Je confirme le changement de prix
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Champs cachés pour la sélection de chambre -->
                                <input type="hidden" name="change_room" id="change_room_input" value="{{ !$isRoomAvailable ? '1' : '0' }}">
                                <input type="hidden" name="new_room_id" id="new_room_id">
                                
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)">
                                        <i class="fas fa-arrow-left me-2"></i>Retour
                                    </button>
                                    <button type="button" class="btn btn-primary" onclick="nextStep(4)">
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
                                <h5 class="mb-0">Confirmation du Check-in</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-success">
                                    <i class="fas fa-clipboard-check fa-2x mb-3"></i>
                                    <h5>Résumé du Check-in</h5>
                                    <p class="mb-0">Vérifiez les informations avant de finaliser le check-in</p>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-box">
                                            <h6><i class="fas fa-user me-2"></i>Client</h6>
                                            <p class="mb-1" id="summary-client">{{ $transaction->customer->name }}</p>
                                            <p class="mb-0 text-muted small" id="summary-phone">{{ $transaction->customer->phone }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-box">
                                            <h6><i class="fas fa-bed me-2"></i>Chambre</h6>
                                            <p class="mb-1" id="summary-room">Chambre {{ $transaction->room->number }}</p>
                                            <p class="mb-0 text-muted small" id="summary-room-type">{{ $transaction->room->type->name ?? 'Type non spécifié' }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="info-box">
                                            <h6><i class="fas fa-users me-2"></i>Occupants</h6>
                                            <p class="mb-1" id="summary-adults">Adultes: 1</p>
                                            <p class="mb-0" id="summary-children">Enfants: 0</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-box">
                                            <h6><i class="fas fa-id-card me-2"></i>Identité</h6>
                                            <p class="mb-1" id="summary-id-type">Type: -</p>
                                            <p class="mb-0" id="summary-id-number">Numéro: -</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="info-box">
                                            <h6><i class="fas fa-calendar-alt me-2"></i>Séjour</h6>
                                            <p class="mb-1">
                                                <strong>Arrivée:</strong> {{ $transaction->check_in->format('d/m/Y H:i') }}
                                                <span class="text-muted">(check-in maintenant)</span>
                                            </p>
                                            <p class="mb-1"><strong>Départ:</strong> {{ $transaction->check_out->format('d/m/Y H:i') }}</p>
                                            <p class="mb-0"><strong>Durée:</strong> {{ $transaction->nights }} nuit(s)</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert alert-warning mt-4">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Attention:</strong> En confirmant, le statut de la réservation passera à "active" et 
                                    la chambre sera marquée comme occupée. Cette action est irréversible.
                                </div>
                                
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary" onclick="prevStep(3)">
                                        <i class="fas fa-arrow-left me-2"></i>Retour
                                    </button>
                                    <button type="submit" class="btn btn-success" id="confirm-checkin">
                                        <i class="fas fa-check-circle me-2"></i>Confirmer le Check-in
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Sidebar: Informations rapides -->
            <div class="col-lg-4">
                <!-- Statut de la réservation -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Statut Réservation</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="display-6 mb-2">#{{ $transaction->id }}</div>
                            <span class="badge bg-warning fs-6">Réservation</span>
                        </div>
                        
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Date création:</span>
                                <strong>{{ $transaction->created_at->format('d/m/Y') }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Arrivée prévue:</span>
                                <strong>{{ $transaction->check_in->format('H:i') }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Nuits:</span>
                                <strong>{{ $transaction->nights }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Total:</span>
                                <strong>{{ Helper::formatCFA($transaction->getTotalPrice()) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Actions Rapides</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary" onclick="quickCheckIn()">
                                <i class="fas fa-bolt me-2"></i>Check-in Rapide
                            </button>
                            <a href="{{ route('transaction.show', $transaction) }}" 
                               class="btn btn-outline-info">
                                <i class="fas fa-file-invoice me-2"></i>Voir Facture
                            </a>
                            <a href="{{ route('customer.show', $transaction->customer) }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-user me-2"></i>Profil Client
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Aide -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Aide</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info small mb-0">
                            <p class="mb-2"><strong>Procédure de check-in:</strong></p>
                            <ol class="mb-0 ps-3">
                                <li>Vérifiez l'identité du client</li>
                                <li>Complétez les informations requises</li>
                                <li>Attribuez une chambre disponible</li>
                                <li>Confirmez le check-in</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
<script>
let currentStep = 1;
let selectedRoomId = null;
let originalRoomPrice = {{ $transaction->room->price }};
let originalTotal = {{ $transaction->getTotalPrice() }};
let nights = {{ $transaction->nights }};

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
    if (currentStep === 2) {
        // Validation des informations client
        const adults = document.getElementById('adults').value;
        const idType = document.getElementById('id_type').value;
        const idNumber = document.getElementById('id_number').value;
        const nationality = document.getElementById('nationality').value;
        
        if (!adults || !idType || !idNumber || !nationality) {
            alert('Veuillez remplir tous les champs obligatoires de l\'étape 2');
            return;
        }
        
        // Mettre à jour le résumé
        document.getElementById('summary-adults').textContent = `Adultes: ${adults}`;
        document.getElementById('summary-children').textContent = `Enfants: ${document.getElementById('children').value || 0}`;
        document.getElementById('summary-id-type').textContent = `Type: ${document.getElementById('id_type').options[document.getElementById('id_type').selectedIndex].text}`;
        document.getElementById('summary-id-number').textContent = `Numéro: ${idNumber}`;
    }
    
    if (currentStep === 3) {
        // Validation de la sélection de chambre
        const roomOption = document.querySelector('input[name="room_option"]:checked').value;
        
        if (roomOption === 'change') {
            if (!selectedRoomId) {
                alert('Veuillez sélectionner une chambre alternative');
                return;
            }
            
            // Vérifier si changement de prix confirmé
            const priceDifferenceElement = document.getElementById('price-difference');
            const priceDifferenceText = priceDifferenceElement.textContent.replace('CFA', '').replace(/\s/g, '');
            const priceDifference = parseInt(priceDifferenceText);
            
            if (priceDifference !== 0) {
                const confirmed = document.getElementById('confirmed_price_change').checked;
                if (!confirmed) {
                    alert('Veuillez confirmer le changement de prix avant de continuer');
                    return;
                }
            }
        }
        
        // Mettre à jour le résumé de chambre
        if (roomOption === 'keep') {
            document.getElementById('summary-room').textContent = `Chambre {{ $transaction->room->number }}`;
            document.getElementById('summary-room-type').textContent = `{{ $transaction->room->type->name ?? 'Type non spécifié' }}`;
        } else {
            const selectedRoomElement = document.getElementById(`room-${selectedRoomId}`);
            const roomNumber = selectedRoomElement.querySelector('h6').textContent.replace('Chambre ', '');
            const roomType = selectedRoomElement.querySelector('p.text-muted').textContent;
            document.getElementById('summary-room').textContent = `Chambre ${roomNumber}`;
            document.getElementById('summary-room-type').textContent = roomType;
        }
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

function toggleRoomOptions(option) {
    const changeRoomInput = document.getElementById('change_room_input');
    const alternativeRoomsContainer = document.getElementById('alternative-rooms-container');
    
    if (option === 'change') {
        changeRoomInput.value = '1';
        alternativeRoomsContainer.style.display = 'block';
        document.getElementById('new_room_id').value = '';
        document.getElementById('price-difference-info').style.display = 'none';
    } else {
        changeRoomInput.value = '0';
        alternativeRoomsContainer.style.display = 'none';
        document.getElementById('new_room_id').value = '';
        document.getElementById('price-difference-info').style.display = 'none';
        
        // Désélectionner toutes les chambres
        document.querySelectorAll('.alternative-room').forEach(room => {
            room.classList.remove('selected');
            const roomId = room.id.replace('room-', '');
            document.getElementById(`check-${roomId}`).style.display = 'none';
        });
    }
}

function selectAlternativeRoom(roomId, roomPrice) {
    // Désélectionner toutes les chambres
    document.querySelectorAll('.alternative-room').forEach(room => {
        room.classList.remove('selected');
        const id = room.id.replace('room-', '');
        document.getElementById(`check-${id}`).style.display = 'none';
    });
    
    // Sélectionner la chambre choisie
    document.getElementById(`room-${roomId}`).classList.add('selected');
    document.getElementById(`check-${roomId}`).style.display = 'inline-block';
    
    // Mettre à jour les champs cachés
    document.getElementById('new_room_id').value = roomId;
    selectedRoomId = roomId;
    
    // Calculer et afficher la différence de prix
    const newTotal = roomPrice * nights;
    const priceDifference = newTotal - originalTotal;
    
    document.getElementById('new-total-price').textContent = formatCFA(newTotal);
    document.getElementById('price-difference').textContent = formatCFA(priceDifference);
    
    const priceDifferenceElement = document.getElementById('price-difference');
    priceDifferenceElement.className = 'h5';
    
    if (priceDifference > 0) {
        priceDifferenceElement.classList.add('price-positive');
    } else if (priceDifference < 0) {
        priceDifferenceElement.classList.add('price-negative');
    } else {
        priceDifferenceElement.classList.add('price-neutral');
    }
    
    document.getElementById('price-difference-info').style.display = 'block';
    
    // Vérifier la case de confirmation si pas de changement de prix
    if (priceDifference === 0) {
        document.getElementById('confirmed_price_change').checked = true;
    } else {
        document.getElementById('confirmed_price_change').checked = false;
    }
}

function formatCFA(amount) {
    return new Intl.NumberFormat('fr-FR', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount) + ' CFA';
}

function quickCheckIn() {
    if (confirm('Effectuer un check-in rapide sans formulaire détaillé ?\n\nLe client sera enregistré avec les informations de base et la chambre originale.')) {
        fetch(`/checkin/{{ $transaction->id }}/quick`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                alertDiv.innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>
                    ${data.message || 'Check-in rapide effectué avec succès!'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.container-fluid').prepend(alertDiv);
                
                setTimeout(() => {
                    window.location.href = '{{ route("checkin.index") }}';
                }, 2000);
            } else {
                alert('Erreur: ' + (data.error || 'Échec du check-in rapide'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue lors du check-in rapide');
        });
    }
}

// Initialiser le stepper
document.addEventListener('DOMContentLoaded', function() {
    updateStepIndicator(1);
    
    // Initialiser les valeurs par défaut pour le résumé
    document.getElementById('summary-client').textContent = '{{ $transaction->customer->name }}';
    document.getElementById('summary-phone').textContent = '{{ $transaction->customer->phone }}';
    document.getElementById('summary-room').textContent = 'Chambre {{ $transaction->room->number }}';
    document.getElementById('summary-room-type').textContent = '{{ $transaction->room->type->name ?? "Type non spécifié" }}';
    
    // Si la chambre n'est pas disponible, forcer la sélection de chambre alternative
    @if(!$isRoomAvailable)
        document.getElementById('change_room').checked = true;
        toggleRoomOptions('change');
    @endif
    
    // Désactiver la soumission multiple
    const form = document.getElementById('checkin-form');
    const submitButton = document.getElementById('confirm-checkin');
    
    form.addEventListener('submit', function(e) {
        if (form.classList.contains('submitting')) {
            e.preventDefault();
            return false;
        }
        
        // Désactiver le bouton et afficher l'indicateur de chargement
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Traitement...';
        form.classList.add('submitting');
        
        return true;
    });
});
</script>
@endsection