@extends('template.master')

@section('title', 'Confirmation de Réservation')

@section('head')
    <link rel="stylesheet" href="{{ asset('style/css/progress-indication.css') }}">
    <style>
        /* Votre CSS reste inchangé */
        .summary-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 4px solid #4a6fa5;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .type-highlight {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        
        .warning-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-left: 4px solid #ffc107;
            border-radius: 8px;
            padding: 15px;
        }
        
        .price-highlight {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2e59d9;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 10px 15px;
            border-radius: 8px;
            border-left: 4px solid #2e59d9;
        }
        
        .customer-avatar {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }
        
        .avatar-placeholder {
            height: 200px;
            background: linear-gradient(135deg, #4a6fa5, #2e59d9);
            border-radius: 8px 8px 0 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .info-icon {
            width: 30px;
            text-align: center;
            color: #4a6fa5;
        }
        
        .payment-options .form-check {
            padding: 15px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }
        
        .payment-options .form-check:hover {
            background-color: #f8f9fa;
            border-color: #4a6fa5;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .payment-options .form-check-input:checked + .form-check-label {
            font-weight: bold;
            color: #4a6fa5;
        }
        
        .payment-options .form-check-input:checked ~ .payment-details {
            display: block;
        }
        
        .payment-details {
            display: none;
            margin-top: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }
        
        .alert-custom {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .table-custom {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .btn-confirm {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 1.1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(40, 167, 69, 0.3);
            background: linear-gradient(135deg, #218838, #1ea085);
        }
        
        .btn-back {
            background: linear-gradient(135deg, #6c757d, #495057);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(108, 117, 125, 0.3);
            background: linear-gradient(135deg, #5a6268, #343a40);
        }
        
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .timeline-item {
            position: relative;
            padding-bottom: 20px;
        }
        
        .timeline-marker {
            position: absolute;
            left: -42px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #4a6fa5;
            border: 3px solid white;
            box-shadow: 0 0 0 3px #4a6fa5;
            top: 5px;
            z-index: 2;
        }
        
        .timeline-content {
            padding-left: 20px;
            border-left: 2px solid #4a6fa5;
            position: relative;
        }
        
        .badge-custom {
            padding: 6px 12px;
            font-weight: 600;
            border-radius: 20px;
        }
        
        .card-header {
            border-radius: 8px 8px 0 0 !important;
            background: linear-gradient(135deg, #4a6fa5, #2e59d9);
            border: none;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .agent-card {
            border-left: 4px solid #20c997;
            background: linear-gradient(135deg, #f8fff9 0%, #e9f7ef 100%);
        }
        
        .room-type-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4a6fa5, #2e59d9);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin: 0 auto 15px;
        }
        
        .type-features {
            list-style: none;
            padding: 0;
            margin: 15px 0;
        }
        
        .type-features li {
            padding: 5px 0;
            color: #666;
        }
        
        .type-features li i {
            color: #4a6fa5;
            margin-right: 10px;
            width: 20px;
        }
        
        .cfa-badge {
            background: #ffd700;
            color: #333;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }
        
        .attribution-info {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            border: 1px solid #90caf9;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
        }
        
        .form-range::-webkit-slider-thumb {
            background: #4a6fa5;
        }
        
        .form-range::-moz-range-thumb {
            background: #4a6fa5;
        }
    </style>
@endsection

@section('content')
    @include('transaction.reservation.progressbar')
    
    <div class="container mt-4">
        <!-- Agent de réservation -->
        @auth
        <div class="alert alert-info alert-custom mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-info text-white rounded-circle p-3 me-3">
                    <i class="fas fa-user-tie fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">
                                <i class="fas fa-user-circle me-2"></i>
                                Agent de réservation
                            </h6>
                            <p class="mb-0 fw-bold">{{ auth()->user()->name }}</p>
                            <small class="text-muted">{{ auth()->user()->email }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-{{ auth()->user()->role == 'Super' ? 'danger' : (auth()->user()->role == 'Admin' ? 'primary' : 'success') }}">
                                {{ auth()->user()->role }}
                            </span>
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Connecté
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endauth
        
        <!-- IMPORTANT : Note sur le système d'attribution -->
        <div class="warning-note mb-4">
            <div class="d-flex align-items-start">
                <i class="fas fa-exclamation-triangle fa-2x text-warning me-3"></i>
                <div>
                    <h5 class="text-warning mb-2">
                        <i class="fas fa-info-circle me-2"></i>
                        Système d'attribution différée
                    </h5>
                    <p class="mb-2">
                        <strong>Vous réservez un TYPE de chambre, pas un numéro spécifique.</strong><br>
                        Le numéro de chambre sera attribué au client lors de son arrivée (check-in).
                    </p>
                    <div class="small text-muted">
                        <i class="fas fa-check-circle me-1"></i> Plus de flexibilité pour la gestion des chambres<br>
                        <i class="fas fa-check-circle me-1"></i> Attribution optimale selon les disponibilités du jour<br>
                        <i class="fas fa-check-circle me-1"></i> Meilleure expérience client
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Alertes -->
                @if(session('success'))
                    <div class="alert alert-success alert-custom fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {!! session('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-custom fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <!-- Résumé de la réservation -->
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0 text-white">
                                    <i class="fas fa-file-invoice-dollar me-2"></i>
                                    Confirmation de Réservation
                                </h4>
                                <small class="text-white opacity-75">Réservation par type - Attribution différée</small>
                            </div>
                            <div class="text-white opacity-75">
                                <small>
                                    <i class="fas fa-clock me-1"></i>
                                    {{ now()->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Type de chambre sélectionné -->
                        <div class="type-highlight mb-4">
                            <div class="room-type-icon">
                                <i class="fas fa-tag"></i>
                            </div>
                            <h3 class="mb-2">{{ $roomType->name }}</h3>
                            <p class="mb-0 opacity-75">{{ $roomType->description ?? 'Chambre confortable' }}</p>
                        </div>
                        
                        <!-- Informations du séjour -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="card summary-card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary mb-3">
                                            <i class="fas fa-tag me-2"></i>
                                            Type de chambre
                                        </h5>
                                        
                                        <div class="mb-3">
                                            <h6 class="fw-bold mb-2">Caractéristiques</h6>
                                            <ul class="type-features">
                                                <li>
                                                    <i class="fas fa-users"></i>
                                                    <strong>Capacité :</strong> {{ $roomType->capacity }} personnes
                                                </li>
                                                @if($roomType->size)
                                                <li>
                                                    <i class="fas fa-ruler-combined"></i>
                                                    <strong>Superficie :</strong> {{ $roomType->size }} m²
                                                </li>
                                                @endif
                                                @if($roomType->bed_type)
                                                <li>
                                                    <i class="fas fa-bed"></i>
                                                    <strong>Type de lit :</strong> {{ $roomType->bed_type }}
                                                </li>
                                                @endif
                                                <li>
                                                    <i class="fas fa-money-bill-wave"></i>
                                                    <strong>Prix/nuit :</strong> 
                                                    <span class="fw-bold text-dark">
                                                        {{ number_format($roomType->base_price, 0, ',', ' ') }} FCFA
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                        
                                        <!-- Disponibilité -->
                                        <div class="mt-3 pt-3 border-top">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <small class="text-muted d-block">Disponibilité</small>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>
                                                        Chambre disponible
                                                    </span>
                                                </div>
                                                <div class="text-end">
                                                    <small class="text-muted d-block">Type #</small>
                                                    <strong>{{ $roomType->id }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card summary-card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary mb-3">
                                            <i class="fas fa-calendar-alt me-2"></i>
                                            Détails du séjour
                                        </h5>
                                        
                                        <div class="timeline">
                                            <div class="timeline-item">
                                                <div class="timeline-marker"></div>
                                                <div class="timeline-content">
                                                    <h6 class="fw-bold mb-1">Arrivée</h6>
                                                    <p class="mb-0 text-dark">
                                                        {{ \Carbon\Carbon::parse($check_in)->format('d/m/Y') }}
                                                        <span class="badge bg-primary ms-2">
                                                            Après 14h
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="timeline-item">
                                                <div class="timeline-marker"></div>
                                                <div class="timeline-content">
                                                    <h6 class="fw-bold mb-1">Départ</h6>
                                                    <p class="mb-0 text-dark">
                                                        {{ \Carbon\Carbon::parse($check_out)->format('d/m/Y') }}
                                                        <span class="badge bg-primary ms-2">
                                                            Avant 12h
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 pt-2 border-top">
                                            <p class="mb-2">
                                                <i class="fas fa-moon me-2 text-muted"></i>
                                                <strong>Durée du séjour :</strong>
                                                <span class="float-end fw-bold">
                                                    {{ $totalNights }} nuit(s)
                                                </span>
                                            </p>
                                            <p class="mb-2">
                                                <i class="fas fa-users me-2 text-muted"></i>
                                                <strong>Personnes :</strong>
                                                <span class="float-end">
                                                    <span class="badge bg-info">
                                                        {{ $adults ?? 1 }} adulte(s)
                                                        @if(isset($children) && $children > 0)
                                                        + {{ $children }} enfant(s)
                                                        @endif
                                                    </span>
                                                </span>
                                            </p>
                                            <p class="mb-0">
                                                <i class="fas fa-user-clock me-2 text-muted"></i>
                                                <strong>Total personnes :</strong>
                                                <span class="float-end fw-bold">
                                                    {{ ($adults ?? 1) + ($children ?? 0) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Calcul du prix -->
                        <div class="card summary-card mb-4">
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-3">
                                    <i class="fas fa-calculator me-2"></i>
                                    Calcul du prix
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td>
                                                    {{ number_format($roomType->base_price, 0, ',', ' ') }} FCFA 
                                                    × {{ $totalNights }} nuit(s)
                                                </td>
                                                <td class="text-end fw-bold">
                                                    {{ number_format($roomType->base_price * $totalNights, 0, ',', ' ') }} FCFA
                                                </td>
                                            </tr>
                                            @if(isset($children) && $children > 0)
                                            <tr>
                                                <td>
                                                    <small class="text-muted">
                                                        <i class="fas fa-child me-1"></i>
                                                        {{ $children }} enfant(s) - Gratuit
                                                    </small>
                                                </td>
                                                <td class="text-end text-muted">
                                                    <small>0 FCFA</small>
                                                </td>
                                            </tr>
                                            @endif
                                            <tr class="table-light">
                                                <td><strong>Total du séjour</strong></td>
                                                <td class="text-end">
                                                    <h5 class="mb-0 text-primary fw-bold">
                                                        {{ number_format($roomType->base_price * $totalNights, 0, ',', ' ') }} FCFA
                                                    </h5>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="price-highlight h-100 d-flex flex-column justify-content-center">
                                            <small class="d-block text-muted">TOTAL À RÉGLER</small>
                                            <div class="fw-bold fs-3">
                                                {{ number_format($roomType->base_price * $totalNights, 0, ',', ' ') }}
                                            </div>
                                            <small class="d-block text-muted">FCFA</small>
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    {{ $totalNights }} nuit(s)
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Note sur l'attribution -->
                                <div class="attribution-info mt-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-door-closed fa-2x text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-1">
                                                <i class="fas fa-clock me-2"></i>
                                                Attribution différée
                                            </h6>
                                            <p class="mb-0">
                                                <strong>Aucun numéro de chambre attribué pour le moment.</strong><br>
                                                Le numéro sera attribué lors du check-in du client.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Formulaire de réservation -->
                        <div class="card border-primary">
                            <div class="card-header bg-light">
                                <h5 class="mb-0 text-dark">
                                    <i class="fas fa-credit-card me-2 text-primary"></i>
                                    Finalisation de la réservation
                                </h5>
                                <small class="text-muted">Choisissez les options de paiement</small>
                            </div>
                            
                            <div class="card-body">
                                <form method="POST" 
                                      action="{{ route('transaction.reservation.store') }}"
                                      id="reservationForm">
                                    @csrf
                                    
                                    <!-- CHAMPS CACHÉS OBLIGATOIRES -->
                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                    <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                                    <input type="hidden" name="check_in" value="{{ $check_in }}">
                                    <input type="hidden" name="check_out" value="{{ $check_out }}">
                                    <input type="hidden" name="adults" value="{{ $adults ?? 1 }}">
                                    <input type="hidden" name="children" value="{{ $children ?? 0 }}">
                                    <input type="hidden" name="total_nights" value="{{ $totalNights }}">
                                    <input type="hidden" name="total_price" value="{{ $roomType->base_price * $totalNights }}">
                                    <input type="hidden" name="status" value="confirmed">
                                    
                                    <!-- CHAMPS PAIEMENT - CORRECTION NOMS -->
                                    <input type="hidden" name="downPayment" id="downPaymentHidden" value="0">
                                    <input type="hidden" name="payment_status" id="paymentStatusHidden" value="pending">
                                    <input type="hidden" name="payment_method" id="paymentMethodHidden" value="cash">
                                    
                                    <!-- Demandes spéciales -->
                                    <div class="mb-4">
                                        <label for="special_requests" class="form-label fw-bold">
                                            <i class="fas fa-comment-alt me-2"></i>
                                            Demandes spéciales (optionnel)
                                        </label>
                                        <textarea class="form-control" 
                                                  id="special_requests" 
                                                  name="special_requests" 
                                                  rows="3" 
                                                  placeholder="Préférences alimentaires, anniversaire, besoins particuliers..."></textarea>
                                        <small class="text-muted">
                                            Ces informations seront transmises à l'équipe pour améliorer le séjour du client.
                                        </small>
                                    </div>
                                    
                                    <!-- Notes internes -->
                                    <div class="mb-4">
                                        <label for="notes" class="form-label fw-bold">
                                            <i class="fas fa-sticky-note me-2"></i>
                                            Notes internes (optionnel)
                                        </label>
                                        <textarea class="form-control" 
                                                  id="notes" 
                                                  name="notes" 
                                                  rows="2" 
                                                  placeholder="Notes pour l'équipe réception..."></textarea>
                                        <small class="text-muted">
                                            Visible uniquement par le personnel de l'hôtel.
                                        </small>
                                    </div>
                                    
                                    <!-- Options de paiement -->
                                    <div class="payment-options mb-4">
                                        <h6 class="mb-3">
                                            <i class="fas fa-money-check-alt me-2"></i>
                                            Options de paiement
                                        </h6>
                                        
                                        <!-- Option 1 : Réservation sans acompte -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_option" 
                                                   id="option_reserve_only" value="reserve_only" checked>
                                            <label class="form-check-label fw-bold d-flex align-items-center" for="option_reserve_only">
                                                <div class="bg-success text-white rounded-circle p-2 me-3">
                                                    <i class="fas fa-calendar-check fa-lg"></i>
                                                </div>
                                                <div>
                                                    <div>Réserver sans acompte</div>
                                                    <small class="text-muted d-block">
                                                        Confirmation immédiate, paiement à l'arrivée
                                                    </small>
                                                </div>
                                            </label>
                                            <div class="payment-details">
                                                <div class="alert alert-light border mt-2">
                                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                                    <small>
                                                        La réservation est confirmée sans paiement immédiat. 
                                                        Le client règle l'intégralité du séjour à son arrivée.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Option 2 : Payer un acompte -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_option" 
                                                   id="option_pay_deposit" value="pay_deposit">
                                            <label class="form-check-label fw-bold d-flex align-items-center" for="option_pay_deposit">
                                                <div class="bg-primary text-white rounded-circle p-2 me-3">
                                                    <i class="fas fa-money-bill-wave fa-lg"></i>
                                                </div>
                                                <div>
                                                    <div>Payer un acompte</div>
                                                    <small class="text-muted d-block">
                                                        Sécurisez la réservation avec un acompte
                                                    </small>
                                                </div>
                                            </label>
                                            <div class="payment-details">
                                                <div class="mt-3">
                                                    <label for="deposit_amount" class="form-label fw-bold">
                                                        <i class="fas fa-money-bill me-2"></i>
                                                        Montant de l'acompte
                                                    </label>
                                                    <div class="input-group input-group-lg">
                                                        <span class="input-group-text bg-primary text-white border-primary">
                                                            <i class="fas fa-currency-sign"></i>
                                                        </span>
                                                        <input type="number" 
                                                               class="form-control border-primary" 
                                                               id="deposit_amount" 
                                                               name="deposit_amount"
                                                               value="{{ $downPayment ?? round($roomType->base_price * $totalNights * 0.3, -3) }}"
                                                               min="0"
                                                               max="{{ $roomType->base_price * $totalNights }}"
                                                               step="500"
                                                               disabled
                                                               placeholder="Montant de l'acompte">
                                                        <span class="input-group-text bg-light border-primary">FCFA</span>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col">
                                                            <input type="range" 
                                                                   class="form-range" 
                                                                   id="deposit_slider"
                                                                   min="0" 
                                                                   max="{{ $roomType->base_price * $totalNights }}" 
                                                                   step="500"
                                                                   value="{{ $downPayment ?? round($roomType->base_price * $totalNights * 0.3, -3) }}"
                                                                   disabled>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <div class="d-flex justify-content-between">
                                                            <small class="text-muted">
                                                                <i class="fas fa-lightbulb me-1"></i>
                                                                Recommandé : {{ number_format($downPayment ?? round($roomType->base_price * $totalNights * 0.3, -3), 0, ',', ' ') }} FCFA
                                                            </small>
                                                            <small class="text-muted">
                                                                Max : {{ number_format($roomType->base_price * $totalNights, 0, ',', ' ') }} FCFA
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Méthode de paiement -->
                                                <div class="mt-3">
                                                    <label for="payment_method" class="form-label fw-bold">
                                                        <i class="fas fa-credit-card me-2"></i>
                                                        Méthode de paiement
                                                    </label>
                                                    <select class="form-select" id="payment_method" name="payment_method_deposit">
                                                        <option value="cash" selected>💵 Espèces</option>
                                                        <option value="card">💳 Carte bancaire</option>
                                                        <option value="mobile_money">📱 Mobile Money</option>
                                                        <option value="bank_transfer">🏦 Virement bancaire</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Option 3 : Payer la totalité -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_option" 
                                                   id="option_pay_full" value="pay_full">
                                            <label class="form-check-label fw-bold d-flex align-items-center" for="option_pay_full">
                                                <div class="bg-success text-white rounded-circle p-2 me-3">
                                                    <i class="fas fa-wallet fa-lg"></i>
                                                </div>
                                                <div>
                                                    <div>Paiement complet</div>
                                                    <small class="text-muted d-block">
                                                        Régler l'intégralité du séjour maintenant
                                                    </small>
                                                </div>
                                            </label>
                                            <div class="payment-details">
                                                <div class="alert alert-success mt-2">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-check-circle fa-2x me-3"></i>
                                                        <div>
                                                            <h6 class="alert-heading mb-1">Paiement complet</h6>
                                                            <p class="mb-0 fw-bold">
                                                                {{ number_format($roomType->base_price * $totalNights, 0, ',', ' ') }} FCFA
                                                            </p>
                                                            <small>La réservation sera entièrement confirmée</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Méthode de paiement -->
                                                <div class="mt-3">
                                                    <label for="payment_method_full" class="form-label fw-bold">
                                                        <i class="fas fa-credit-card me-2"></i>
                                                        Méthode de paiement
                                                    </label>
                                                    <select class="form-select" id="payment_method_full" name="payment_method_full">
                                                        <option value="cash" selected>💵 Espèces</option>
                                                        <option value="card">💳 Carte bancaire</option>
                                                        <option value="mobile_money">📱 Mobile Money</option>
                                                        <option value="bank_transfer">🏦 Virement bancaire</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Conditions générales -->
                                    <div class="card border-0 bg-light mb-4">
                                        <div class="card-body">
                                            <h6 class="card-title text-dark mb-3">
                                                <i class="fas fa-file-contract me-2 text-primary"></i>
                                                Conditions générales
                                            </h6>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="terms" required>
                                                <label class="form-check-label" for="terms">
                                                    J'accepte les conditions générales de réservation :
                                                    <ul class="mt-2 mb-0 small">
                                                        <li>Le numéro de chambre sera attribué au check-in</li>
                                                        <li>Présentation d'une pièce d'identité obligatoire à l'arrivée</li>
                                                        <li>Check-in à partir de 14h, check-out avant 12h</li>
                                                        <li>Annulation gratuite jusqu'à 48h avant l'arrivée</li>
                                                        <li>Non-présentation entraîne l'annulation automatique</li>
                                                    </ul>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Boutons d'action -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ route('transaction.reservation.choose-type', [
                                            'customer' => $customer->id,
                                            'check_in' => $check_in,
                                            'check_out' => $check_out
                                        ]) }}" 
                                           class="btn btn-back px-4">
                                            <i class="fas fa-arrow-left me-2"></i>
                                            Retour
                                        </a>
                                        
                                        <button type="submit" class="btn btn-confirm px-5" id="submitBtn">
                                            <i class="fas fa-calendar-plus me-2"></i>
                                            <span id="submitText">Confirmer la réservation</span>
                                            <small class="d-block fw-normal opacity-75">
                                                Type: {{ $roomType->name }} | Attribution différée
                                            </small>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Colonne latérale - Informations client -->
            <div class="col-lg-4">
                <div class="card shadow-lg sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user-circle me-2"></i>
                            Informations Client
                        </h5>
                    </div>
                    
                    <!-- Avatar -->
                    <div class="avatar-placeholder">
                        <div class="text-center text-white">
                            @if($customer->avatar)
                                <img src="{{ Storage::url($customer->avatar) }}" 
                                     alt="{{ $customer->name }}" 
                                     class="customer-avatar">
                            @else
                                <i class="fas fa-user-circle fa-6x"></i>
                                <h4 class="mt-3">{{ $customer->name }}</h4>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <table class="table table-custom">
                            <tbody>
                                <tr>
                                    <td class="info-icon"><i class="fas fa-id-card"></i></td>
                                    <td><strong>ID Client</strong></td>
                                    <td class="text-end"><span class="badge bg-secondary">#{{ $customer->id }}</span></td>
                                </tr>
                                <tr>
                                    <td class="info-icon"><i class="fas fa-user"></i></td>
                                    <td><strong>Nom complet</strong></td>
                                    <td class="text-end">{{ $customer->name }}</td>
                                </tr>
                                <tr>
                                    <td class="info-icon"><i class="fas fa-{{ $customer->gender == 'Male' ? 'male' : 'female' }}"></i></td>
                                    <td><strong>Genre</strong></td>
                                    <td class="text-end">
                                        <span class="badge bg-{{ $customer->gender == 'Male' ? 'primary' : 'danger' }}">
                                            {{ $customer->gender == 'Male' ? 'Homme' : 'Femme' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="info-icon"><i class="fas fa-phone"></i></td>
                                    <td><strong>Téléphone</strong></td>
                                    <td class="text-end">{{ $customer->phone ?? 'Non renseigné' }}</td>
                                </tr>
                                <tr>
                                    <td class="info-icon"><i class="fas fa-envelope"></i></td>
                                    <td><strong>Email</strong></td>
                                    <td class="text-end">
                                        <small>{{ $customer->email ?? 'Non renseigné' }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="info-icon"><i class="fas fa-briefcase"></i></td>
                                    <td><strong>Profession</strong></td>
                                    <td class="text-end">{{ $customer->job ?? 'Non renseigné' }}</td>
                                </tr>
                                @if($customer->user)
                                <tr class="table-success">
                                    <td class="info-icon"><i class="fas fa-user-tie"></i></td>
                                    <td><strong>Créé par</strong></td>
                                    <td class="text-end">
                                        <small>{{ $customer->user->name ?? 'Système' }}</small>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        
                        <!-- Statut de la réservation -->
                        <div class="mt-4">
                            <h6 class="text-dark mb-2">
                                <i class="fas fa-info-circle me-2 text-primary"></i>
                                Statut de la réservation
                            </h6>
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <p class="mb-1 fw-bold">En attente d'attribution</p>
                                    <small class="text-muted">Numéro de chambre à attribuer</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Agent connecté -->
                        @auth
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="text-dark mb-2">
                                <i class="fas fa-user-shield me-2 text-success"></i>
                                Agent responsable
                            </h6>
                            <div class="d-flex align-items-center">
                                <div class="bg-success text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div>
                                    <p class="mb-1 fw-bold">{{ auth()->user()->name }}</p>
                                    <small class="text-muted d-block">{{ auth()->user()->email }}</small>
                                    <small class="badge bg-info">{{ auth()->user()->role }}</small>
                                </div>
                            </div>
                        </div>
                        @endauth
                    </div>
                    
                    <div class="card-footer bg-light">
                        <div class="text-center">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ now()->format('d/m/Y à H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Script initialisé - Version corrigée');
    
    // Éléments du formulaire
    const reservationForm = document.getElementById('reservationForm');
    const downPaymentHidden = document.getElementById('downPaymentHidden');
    const paymentStatusHidden = document.getElementById('paymentStatusHidden');
    const paymentMethodHidden = document.getElementById('paymentMethodHidden');
    const depositAmountInput = document.getElementById('deposit_amount');
    const depositSlider = document.getElementById('deposit_slider');
    const paymentMethodSelect = document.getElementById('payment_method');
    const paymentMethodFullSelect = document.getElementById('payment_method_full');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const termsCheckbox = document.getElementById('terms');
    
    // Données de la réservation
    const totalPrice = {{ $roomType->base_price * $totalNights }};
    const roomTypeName = "{{ $roomType->name }}";
    
    // Formater la monnaie
    function formatCurrency(amount) {
        return new Intl.NumberFormat('fr-FR').format(amount) + ' FCFA';
    }
    
    // ⚠️ CORRECTION CRITIQUE: Mettre à jour les champs cachés PAIEMENT
    function updateHiddenFields() {
        // ✅ CORRECTION: 'payment_option' (un seul 'i') - correspond au HTML
        const selectedOption = document.querySelector('input[name="payment_option"]:checked');
        if (!selectedOption) return;
        
        let downPayment = 0;
        let paymentStatus = 'pending';
        let paymentMethod = 'cash';
        
        switch(selectedOption.value) {
            case 'reserve_only':
                downPayment = 0;
                paymentStatus = 'pending';
                paymentMethod = 'cash';
                break;
                
            case 'pay_deposit':
                downPayment = parseFloat(depositAmountInput.value) || 0;
                paymentStatus = downPayment > 0 ? 'partial' : 'pending';
                paymentMethod = paymentMethodSelect.value;
                break;
                
            case 'pay_full':
                downPayment = totalPrice;
                paymentStatus = 'paid';
                paymentMethod = paymentMethodFullSelect.value;
                break;
        }
        
        // ✅ Mise à jour des champs cachés
        downPaymentHidden.value = downPayment;
        paymentStatusHidden.value = paymentStatus;
        paymentMethodHidden.value = paymentMethod;
        
        console.log('💰 Paiement mis à jour:', {
            downPayment: downPayment,
            paymentStatus: paymentStatus,
            paymentMethod: paymentMethod
        });
    }
    
    // Activer/désactiver les champs selon l'option
    function toggleDepositInput() {
        const selectedOption = document.querySelector('input[name="payment_option"]:checked');
        if (!selectedOption) return;
        
        depositAmountInput.disabled = selectedOption.value !== 'pay_deposit';
        depositSlider.disabled = selectedOption.value !== 'pay_deposit';
        paymentMethodSelect.disabled = selectedOption.value !== 'pay_deposit';
        paymentMethodFullSelect.disabled = selectedOption.value !== 'pay_full';
        
        switch(selectedOption.value) {
            case 'pay_deposit':
                if (!depositAmountInput.value || depositAmountInput.value == 0) {
                    const recommended = Math.round(totalPrice * 0.3 / 500) * 500;
                    depositAmountInput.value = recommended;
                    depositSlider.value = recommended;
                }
                break;
            case 'pay_full':
                depositAmountInput.value = totalPrice;
                depositSlider.value = totalPrice;
                break;
            case 'reserve_only':
                depositAmountInput.value = 0;
                depositSlider.value = 0;
                break;
        }
        
        updateHiddenFields();
    }
    
    // Mettre à jour le texte du bouton
    function updateButtonText() {
        const selectedOption = document.querySelector('input[name="payment_option"]:checked');
        if (!selectedOption) return;
        
        let paymentAmount = 0;
        
        switch(selectedOption.value) {
            case 'pay_deposit':
                paymentAmount = parseFloat(depositAmountInput.value) || 0;
                break;
            case 'pay_full':
                paymentAmount = totalPrice;
                break;
        }
        
        let buttonText = 'Confirmer la réservation';
        let buttonSubtext = `Type: ${roomTypeName} | Attribution différée`;
        
        if (paymentAmount > 0) {
            buttonText = `Confirmer avec paiement de ${formatCurrency(paymentAmount)}`;
            buttonSubtext = `Type: ${roomTypeName} | Solde: ${formatCurrency(totalPrice - paymentAmount)}`;
        }
        
        submitText.textContent = buttonText;
        const smallElement = document.querySelector('#submitBtn small');
        if (smallElement) {
            smallElement.textContent = buttonSubtext;
        }
    }
    
    // Synchroniser les méthodes de paiement
    function syncPaymentMethods() {
        const selectedOption = document.querySelector('input[name="payment_option"]:checked');
        if (!selectedOption) return;
        
        if (selectedOption.value === 'pay_deposit') {
            paymentMethodFullSelect.value = paymentMethodSelect.value;
        } else if (selectedOption.value === 'pay_full') {
            paymentMethodSelect.value = paymentMethodFullSelect.value;
        }
        
        updateHiddenFields();
    }
    
    // ✅ GESTIONNAIRE DE SUBMIT CORRIGÉ
    reservationForm.addEventListener('submit', function(e) {
        console.log('✅ FORMULAIRE SOUMIS !');
        
        // Vérifier les conditions générales
        if (!termsCheckbox.checked) {
            e.preventDefault();
            alert('Vous devez accepter les conditions générales pour continuer.');
            termsCheckbox.focus();
            return false;
        }
        
        // Vérifier l'acompte si option choisie
        const selectedOption = document.querySelector('input[name="payment_option"]:checked');
        if (selectedOption && selectedOption.value === 'pay_deposit') {
            const depositAmount = parseFloat(depositAmountInput.value) || 0;
            
            if (depositAmount > totalPrice) {
                e.preventDefault();
                alert('L\'acompte ne peut pas dépasser le prix total du séjour.');
                depositAmountInput.focus();
                return false;
            }
            
            if (depositAmount < 0) {
                e.preventDefault();
                alert('Le montant de l\'acompte doit être positif.');
                depositAmountInput.focus();
                return false;
            }
        }
        
        // ✅ METTRE À JOUR LES CHAMPS CACHÉS AVANT SOUMISSION
        updateHiddenFields();
        
        // Afficher l'état de chargement
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement en cours...';
        submitBtn.disabled = true;
        
        // ✅ LAISSE LA SOUMISSION SE FAIRE NORMALEMENT
        return true;
    });
    
    // ✅ CORRECTION: 'payment_option' (un seul 'i')
    document.querySelectorAll('input[name="payment_option"]').forEach(radio => {
        radio.addEventListener('change', function() {
            toggleDepositInput();
            syncPaymentMethods();
            updateButtonText();
        });
    });
    
    // Écouteurs pour l'acompte
    if (depositAmountInput) {
        depositAmountInput.addEventListener('input', function() {
            depositSlider.value = this.value;
            updateHiddenFields();
            updateButtonText();
        });
    }
    
    if (depositSlider) {
        depositSlider.addEventListener('input', function() {
            depositAmountInput.value = this.value;
            updateHiddenFields();
            updateButtonText();
        });
    }
    
    // Écouteurs pour les méthodes de paiement
    if (paymentMethodSelect) {
        paymentMethodSelect.addEventListener('change', function() {
            syncPaymentMethods();
        });
    }
    
    if (paymentMethodFullSelect) {
        paymentMethodFullSelect.addEventListener('change', function() {
            syncPaymentMethods();
        });
    }
    
    // Écouteur pour les conditions générales
    if (termsCheckbox) {
        termsCheckbox.addEventListener('change', function() {
            submitBtn.disabled = !this.checked;
        });
    }
    
    // Initialisation
    toggleDepositInput();
    syncPaymentMethods();
    updateButtonText();
    
    if (termsCheckbox) {
        submitBtn.disabled = !termsCheckbox.checked;
    }
    
    console.log('✅ Script prêt - Total: ' + formatCurrency(totalPrice));
});
</script>
@endsection