@extends('template.master')
@section('title', __('checkin.checkin_reservation'))
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
            background-color: var(--white, #fff);
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
        .room-dirty { background-color: #ffc107; animation: pulse 2s infinite; }
        
        @keyframes pulse {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.1); }
            100% { opacity: 1; transform: scale(1); }
        }
        
        .alternative-room {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }
        .alternative-room:hover {
            border-color: #0d6efd;
            background-color: #f0f8ff;
        }
        .alternative-room.selected {
            border-color: #0d6efd;
            background-color: #e7f1ff;
        }
        .alternative-room.dirty {
            border-color: #ffc107;
            background-color: #fff3cd;
        }
        .alternative-room.dirty:hover {
            border-color: #fd7e14;
            background-color: #ffe69c;
        }
        .alternative-room.dirty.selected {
            border-color: #fd7e14;
            background-color: #fff3cd;
        }
        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: bold;
        }
        .status-badge.clean {
            background-color: #28a745;
            color: white;
        }
        .status-badge.dirty {
            background-color: #ffc107;
            color: #856404;
        }
        .status-badge.occupied {
            background-color: #dc3545;
            color: white;
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
        .urgent-cleaning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            animation: pulse-bg 2s infinite;
        }
        @keyframes pulse-bg {
            0% { background-color: #fff3cd; }
            50% { background-color: #ffe69c; }
            100% { background-color: #fff3cd; }
        }
        .blocked-checkin {
            text-align: center;
            padding: 40px 20px;
        }
        .blocked-checkin i {
            font-size: 5rem;
            color: #ffc107;
            margin-bottom: 20px;
        }
        .blocked-checkin h3 {
            margin-bottom: 15px;
            color: #856404;
        }
        .blocked-checkin p {
            color: #6c757d;
            max-width: 500px;
            margin: 0 auto 25px;
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard.index') }}">{{ __('checkin.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('checkin.index') }}">{{ __('checkin.checkin') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('checkin.checkin_reservation') }} #{{ $transaction->id }}</li>
                    </ol>
                </nav>
                
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="h4 mb-0">
                        <i class="fas fa-door-open text-primary me-2"></i>
                        {{ __('checkin.checkin_reservation') }} #{{ $transaction->id }}
                    </h2>
                    <a href="{{ route('checkin.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>{{ __('checkin.back') }}
                    </a>
                </div>
                <p class="text-muted">{{ __('checkin.register_arrival_subtitle') }}</p>
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

        <!-- 🔴 ALERTE SI CHECK-IN BLOQUÉ -->
        @if(isset($canCheckIn) && !$canCheckIn)
            <div class="alert alert-warning alert-dismissible fade show urgent-cleaning" role="alert">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-broom fa-2x"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading fw-bold mb-2">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ __('checkin.checkin_temporarily_impossible') }}
                        </h5>
                        <p class="mb-2">{{ $checkInBlockedReason ?? $transaction->room->getCheckInErrorMessage() }}</p>
                        
                        @if(isset($isUrgentCleaning) && $isUrgentCleaning)
                            <div class="mt-2">
                                <button class="btn btn-warning" onclick="notifyHousekeeping({{ $transaction->room->id }})">
                                    <i class="fas fa-bell me-2"></i>
                                    {{ __('checkin.notify_urgent_housekeeping') }}
                                </button>
                            </div>
                            <div class="mt-2 small">
                                <i class="fas fa-clock me-1"></i>
                                {{ __('checkin.guest_expected_at', ['time' => $transaction->check_in->format('H:i')]) }}
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        <!-- 🔴 ALERTE SI CHAMBRE SALE MAIS RÉSERVABLE -->
        @if(isset($isAvailableForBooking) && $isAvailableForBooking && !($canCheckIn ?? true))
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>{{ __('checkin.note_label') }}</strong> {{ __('checkin.room_bookable_needs_cleaning') }}
                @if($isUrgentCleaning ?? false)
                    <span class="badge bg-warning ms-2">{{ __('checkin.urgent_arrival_today') }}</span>
                @endif
            </div>
        @endif

        <!-- 🔴 SI CHECK-IN BLOQUÉ, AFFICHER UNE VERSION SIMPLIFIÉE -->
        @if(isset($canCheckIn) && !$canCheckIn)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h5 class="mb-0 text-dark">
                                <i class="fas fa-clock me-2"></i>
                                {{ __('checkin.waiting_for_cleaning') }}
                            </h5>
                        </div>
                        <div class="card-body blocked-checkin">
                            <i class="fas fa-broom"></i>
                            <h3>{{ __('checkin.room_being_cleaned') }}</h3>
                            <p>{{ $checkInBlockedReason ?? $transaction->room->getCheckInErrorMessage() }}</p>
                            
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="alert alert-light border text-start">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>{{ __('checkin.customer_info') }}</h6>
                                                <p class="mb-1"><strong>{{ $transaction->customer->name }}</strong></p>
                                                <p class="mb-1">{{ $transaction->customer->phone }}</p>
                                                <p class="mb-0">{{ $transaction->customer->email ?? __('checkin.email_not_provided') }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>{{ __('checkin.reservation_details') }}</h6>
                                                <p class="mb-1">{{ __('checkin.room_number', ['number' => $transaction->room->number]) }}</p>
                                                <p class="mb-1">{{ __('checkin.arrival_time') }} {{ $transaction->check_in->format('d/m/Y H:i') }}</p>
                                                <p class="mb-0">{{ __('checkin.departure_time') }} {{ $transaction->check_out->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('checkin.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>{{ __('checkin.back_to_list') }}
                                </a>
                                @if($isUrgentCleaning ?? false)
                                    <button class="btn btn-warning btn-lg ms-2" onclick="notifyHousekeeping({{ $transaction->room->id }})">
                                        <i class="fas fa-bell me-2"></i>{{ __('checkin.notify_housekeeping_btn') }}
                                    </button>
                                @endif
                            </div>
                            
                            @if(!$alternativeRooms->isEmpty())
                                <hr class="my-4">
                                <h5 class="mb-3">{{ __('checkin.available_alternative_rooms') }}</h5>
                                <div class="row">
                                    @foreach($alternativeRooms as $altRoom)
                                        <div class="col-md-4 mb-3">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h6 class="card-title">{{ __('checkin.room_number', ['number' => $altRoom->number]) }}</h6>
                                                    <p class="small mb-1">{{ $altRoom->type->name ?? 'Standard' }}</p>
                                                    <p class="small mb-2">{{ Helper::formatCFA($altRoom->price) }}{{ __('checkin.per_night') }}</p>
                                                    @if($altRoom->room_status_id == 6)
                                                        <span class="badge bg-warning">{{ __('checkin.to_clean') }}</span>
                                                    @else
                                                        <span class="badge bg-success">{{ __('checkin.room_ready') }}</span>
                                                    @endif
                                                    <button class="btn btn-sm btn-outline-primary mt-2 w-100" 
                                                            onclick="selectAlternativeRoomFromBlocked({{ $altRoom->id }})">
                                                        {{ __('checkin.select_button') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- SI CHECK-IN POSSIBLE, AFFICHER LE FORMULAIRE COMPLET -->
            
            <!-- Stepper -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="form-stepper">
                        <div class="step active" id="step-1">
                            <div class="step-number">1</div>
                            <div class="step-label">{{ __('checkin.step_verification') }}</div>
                        </div>
                        <div class="step" id="step-2">
                            <div class="step-number">2</div>
                            <div class="step-label">{{ __('checkin.step_information') }}</div>
                        </div>
                        <div class="step" id="step-3">
                            <div class="step-number">3</div>
                            <div class="step-label">{{ __('checkin.step_room') }}</div>
                        </div>
                        <div class="step" id="step-4">
                            <div class="step-number">4</div>
                            <div class="step-label">{{ __('checkin.step_confirmation') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <form method="POST" action="{{ route('checkin.store', $transaction) }}" id="checkin-form">
                        @csrf
                        
                        <!-- Étape 1: Vérification (MODIFIÉE) -->
                        <div class="form-tab active" id="tab-1">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">{{ __('checkin.verify_reservation') }}</h5>
                                </div>
                                <div class="card-body">
                                    <!-- 🔴 Statut de la chambre -->
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="info-box d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6><i class="fas fa-bed me-2"></i>{{ __('checkin.room_status') }}</h6>
                                                    <p class="mb-0">
                                                        <span class="badge bg-{{ $transaction->room->status_color }} fs-6">
                                                            <i class="fas {{ $transaction->room->status_icon }} me-1"></i>
                                                            {{ $transaction->room->status_label }}
                                                        </span>
                                                    </p>
                                                </div>
                                                <div>
                                                    @if($transaction->room->room_status_id == 1)
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>{{ __('checkin.ready_for_checkin') }}
                                                        </span>
                                                    @elseif($transaction->room->room_status_id == 6)
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-broom me-1"></i>{{ __('checkin.to_clean') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        {{ __('checkin.verify_reservation_info') }}
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <h6><i class="fas fa-user me-2"></i>{{ __('checkin.customer') }}</h6>
                                                <p class="mb-1"><strong>{{ $transaction->customer->name }}</strong></p>
                                                <p class="mb-1 text-muted small">{{ $transaction->customer->phone }}</p>
                                                <p class="mb-0 text-muted small">{{ $transaction->customer->email ?? __('checkin.email_not_provided') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <h6><i class="fas fa-bed me-2"></i>{{ __('checkin.reserved_room') }}</h6>
                                                <p class="mb-1"><strong>{{ __('checkin.room_number', ['number' => $transaction->room->number]) }}</strong></p>
                                                <p class="mb-1 text-muted small">{{ $transaction->room->type->name ?? __('checkin.type_not_specified') }}</p>
                                                <p class="mb-0 text-muted small">{{ __('checkin.persons_per_night', ['count' => $transaction->room->capacity, 'price' => Helper::formatCFA($transaction->room->price)]) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <h6><i class="fas fa-calendar-alt me-2"></i>{{ __('checkin.dates') }}</h6>
                                                <p class="mb-1"><strong>{{ __('checkin.arrival_time') }}</strong> {{ $transaction->check_in->format('d/m/Y H:i') }}</p>
                                                <p class="mb-1"><strong>{{ __('checkin.departure_time') }}</strong> {{ $transaction->check_out->format('d/m/Y H:i') }}</p>
                                                <p class="mb-0"><strong>{{ __('checkin.duration') }}</strong> {{ $transaction->nights }} {{ __('checkin.nights_unit') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <h6><i class="fas fa-money-bill-wave me-2"></i>{{ __('checkin.payment') }}</h6>
                                                <p class="mb-1"><strong>{{ __('checkin.total_label') }}</strong> {{ Helper::formatCFA($transaction->getTotalPrice()) }}</p>
                                                <p class="mb-1"><strong>{{ __('checkin.paid_label') }}</strong> {{ Helper::formatCFA($transaction->getTotalPayment()) }}</p>
                                                <p class="mb-0">
                                                    <strong>{{ __('checkin.balance_label') }}</strong> 
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
                                                {{ __('checkin.reserved_room_available') }}
                                            </div>
                                            <button type="button" class="btn btn-primary" onclick="nextStep(2)">
                                                <i class="fas fa-arrow-right me-2"></i>{{ __('checkin.continue_button') }}
                                            </button>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                {{ __('checkin.reserved_room_not_available') }}
                                                {{ __('checkin.must_select_other_room') }}
                                            </div>
                                            <button type="button" class="btn btn-warning" onclick="nextStep(2)">
                                                <i class="fas fa-arrow-right me-2"></i>{{ __('checkin.select_another_room') }}
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
                                    <h5 class="mb-0">{{ __('checkin.additional_info') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-section">
                                        <h6><i class="fas fa-users me-2"></i>{{ __('checkin.occupants') }}</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="adults" class="form-label">{{ __('checkin.adults_label') }}</label>
                                                <input type="number" class="form-control @error('adults') is-invalid @enderror" 
                                                       id="adults" name="adults" 
                                                       value="{{ old('adults', $transaction->person_count ?? 1) }}" 
                                                       min="1" max="10" required>
                                                @error('adults')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="children" class="form-label">{{ __('checkin.children_label') }}</label>
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
                                        <h6><i class="fas fa-id-card me-2"></i>{{ __('checkin.id_document') }}</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="id_type" class="form-label">{{ __('checkin.id_type_label') }}</label>
                                                <select class="form-control @error('id_type') is-invalid @enderror" 
                                                        id="id_type" name="id_type" required>
                                                    <option value="">{{ __('checkin.select_placeholder') }}</option>
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
                                                <label for="id_number" class="form-label">{{ __('checkin.id_number_label') }}</label>
                                                <input type="text" class="form-control @error('id_number') is-invalid @enderror" 
                                                       id="id_number" name="id_number" 
                                                       value="{{ old('id_number') }}" 
                                                       placeholder="{{ __('checkin.id_number_placeholder') }}" required>
                                                @error('id_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="nationality" class="form-label">{{ __('checkin.nationality_label') }}</label>
                                                <input type="text" class="form-control @error('nationality') is-invalid @enderror" 
                                                       id="nationality" name="nationality" 
                                                       value="{{ old('nationality') }}" 
                                                       placeholder="{{ __('checkin.nationality_placeholder') }}" required>
                                                @error('nationality')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-section">
                                        <h6><i class="fas fa-comment-alt me-2"></i>{{ __('checkin.other_info') }}</h6>
                                        <div class="mb-3">
                                            <label for="special_requests" class="form-label">{{ __('checkin.special_requests') }}</label>
                                            <textarea class="form-control @error('special_requests') is-invalid @enderror" 
                                                      id="special_requests" name="special_requests" 
                                                      rows="3" placeholder="{{ __('checkin.special_requests_placeholder') }}">{{ old('special_requests') }}</textarea>
                                            @error('special_requests')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="notes" class="form-label">{{ __('checkin.internal_notes') }}</label>
                                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                                      id="notes" name="notes" 
                                                      rows="2" placeholder="{{ __('checkin.notes_placeholder') }}">{{ old('notes') }}</textarea>
                                            @error('notes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(1)">
                                            <i class="fas fa-arrow-left me-2"></i>{{ __('checkin.back') }}
                                        </button>
                                        <button type="button" class="btn btn-primary" onclick="nextStep(3)">
                                            <i class="fas fa-arrow-right me-2"></i>{{ __('checkin.continue_button') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Étape 3: Chambre (MODIFIÉE) -->
                        <div class="form-tab" id="tab-3">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">{{ __('checkin.room_selection') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        @if($isRoomAvailable)
                                            {{ __('checkin.reserved_room_available_info') }}
                                        @else
                                            {{ __('checkin.reserved_room_not_available_info') }}
                                        @endif
                                    </div>
                                    
                                    <!-- 🔴 Statut de la chambre originale -->
                                    @if($transaction->room->room_status_id == 6)
                                        <div class="alert alert-warning mb-3">
                                            <i class="fas fa-broom me-2"></i>
                                            <strong>{{ __('checkin.attention_label') }}</strong> {{ __('checkin.reserved_room_dirty', ['number' => $transaction->room->number]) }}
                                            {{ __('checkin.checkin_after_cleaning') }}
                                        </div>
                                    @endif
                                    
                                    <!-- Option 1: Conserver la chambre originale -->
                                    @if($isRoomAvailable)
                                        <div class="form-check mb-4">
                                            <input class="form-check-input" type="radio" name="room_option" 
                                                   id="keep_original" value="keep" checked 
                                                   onchange="toggleRoomOptions('keep')">
                                            <label class="form-check-label" for="keep_original">
                                                <h6 class="mb-1">
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                    {{ __('checkin.keep_original_room') }}
                                                </h6>
                                                <div class="ms-4">
                                                    <p class="mb-1">
                                                        <strong>{{ __('checkin.room_number', ['number' => $transaction->room->number]) }}</strong>
                                                        @if($transaction->room->room_status_id == 6)
                                                            <span class="badge bg-warning ms-2">{{ __('checkin.to_clean') }}</span>
                                                        @endif
                                                    </p>
                                                    <p class="mb-1 text-muted small">{{ $transaction->room->type->name ?? __('checkin.type_not_specified') }}</p>
                                                    <p class="mb-0 text-muted small">{{ __('checkin.persons_per_night', ['count' => $transaction->room->capacity, 'price' => Helper::formatCFA($transaction->room->price)]) }}</p>
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
                                                {{ __('checkin.change_room') }}
                                            </h6>
                                            <div class="ms-4">
                                                <p class="mb-1">{{ __('checkin.select_alternative_room') }}</p>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <!-- Liste des chambres alternatives (MODIFIÉE) -->
                                    <div id="alternative-rooms-container" style="{{ $isRoomAvailable ? 'display: none;' : '' }}">
                                        <h6 class="mb-3">{{ __('checkin.available_rooms_for_period') }}</h6>
                                        
                                        @if($alternativeRooms->isEmpty())
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                {{ __('checkin.no_alternative_room') }}
                                                {{ __('checkin.check_availability_dates') }}
                                            </div>
                                        @else
                                            <div class="row">
                                                @foreach($alternativeRooms as $room)
                                                    @php
                                                        $isDirty = $room->room_status_id == 6;
                                                        $canCheckIn = $room->canCheckIn ? $room->canCheckIn() : ($room->room_status_id == 1);
                                                    @endphp
                                                    <div class="col-md-6">
                                                        <div class="alternative-room {{ $isDirty ? 'dirty' : '' }}" 
                                                             onclick="selectAlternativeRoom({{ $room->id }}, {{ $room->price }}, {{ $isDirty ? 'true' : 'false' }})"
                                                             id="room-{{ $room->id }}">
                                                            <div class="d-flex justify-content-between align-items-start">
                                                                <div>
                                                                    <h6 class="mb-1">
                                                                        <span class="room-status-indicator {{ $isDirty ? 'room-dirty' : 'room-available' }}"></span>
                                                                        {{ __('checkin.room_number', ['number' => $room->number]) }}
                                                                    </h6>
                                                                    <p class="mb-1 text-muted small">{{ $room->type->name }}</p>
                                                                    <p class="mb-0 text-muted small">{{ __('checkin.persons_per_night', ['count' => $room->capacity, 'price' => Helper::formatCFA($room->price)]) }}</p>
                                                                    @if($isDirty)
                                                                        <span class="badge bg-warning mt-1">
                                                                            <i class="fas fa-broom me-1"></i>{{ __('checkin.to_clean') }}
                                                                        </span>
                                                                    @else
                                                                        <span class="badge bg-success mt-1">
                                                                            <i class="fas fa-check-circle me-1"></i>{{ __('checkin.room_ready') }}
                                                                        </span>
                                                                    @endif
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
                                            
                                            <!-- 🔴 Warning pour chambre sale -->
                                            <div class="mt-4" id="dirty-room-warning" style="display: none;">
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                    <strong>{{ __('checkin.attention_label') }}</strong> {{ __('checkin.selected_room_needs_cleaning') }}
                                                    {{ __('checkin.checkin_after_housekeeping') }}
                                                </div>
                                            </div>
                                            
                                            <!-- Affichage différence de prix -->
                                            <div class="mt-4" id="price-difference-info" style="display: none;">
                                                <div class="alert alert-info">
                                                    <h6><i class="fas fa-money-bill-wave me-2"></i>{{ __('checkin.price_impact') }}</h6>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <p class="mb-1 small">{{ __('checkin.old_total') }}</p>
                                                            <p class="h5">{{ Helper::formatCFA($transaction->getTotalPrice()) }}</p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p class="mb-1 small">{{ __('checkin.new_total') }}</p>
                                                            <p class="h5" id="new-total-price">0 CFA</p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p class="mb-1 small">{{ __('checkin.difference') }}</p>
                                                            <p class="h5" id="price-difference">0 CFA</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           id="confirmed_price_change" name="confirmed_price_change">
                                                    <label class="form-check-label" for="confirmed_price_change">
                                                        {{ __('checkin.confirm_price_change') }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Champs cachés pour la sélection de chambre -->
                                    <input type="hidden" name="change_room" id="change_room_input" value="{{ !$isRoomAvailable ? '1' : '0' }}">
                                    <input type="hidden" name="new_room_id" id="new_room_id">
                                    <input type="hidden" name="selected_room_dirty" id="selected_room_dirty" value="0">
                                    
                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)">
                                            <i class="fas fa-arrow-left me-2"></i>{{ __('checkin.back') }}
                                        </button>
                                        <button type="button" class="btn btn-primary" onclick="nextStep(4)">
                                            <i class="fas fa-arrow-right me-2"></i>{{ __('checkin.continue_button') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Étape 4: Confirmation (MODIFIÉE) -->
                        <div class="form-tab" id="tab-4">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">{{ __('checkin.confirmation_checkin') }}</h5>
                                </div>
                                <div class="card-body">
                                    <!-- 🔴 Alerte si chambre sale -->
                                    <div id="confirmation-dirty-warning" style="display: none;" class="alert alert-warning mb-3">
                                        <i class="fas fa-broom me-2"></i>
                                        <strong>{{ __('checkin.attention_label') }}</strong> {{ __('checkin.selected_room_dirty_warning') }}
                                        {{ __('checkin.checkin_final_after_cleaning') }}
                                    </div>
                                    
                                    <div class="alert alert-success">
                                        <i class="fas fa-clipboard-check fa-2x mb-3"></i>
                                        <h5>{{ __('checkin.checkin_summary') }}</h5>
                                        <p class="mb-0">{{ __('checkin.verify_before_finalizing') }}</p>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <h6><i class="fas fa-user me-2"></i>{{ __('checkin.customer') }}</h6>
                                                <p class="mb-1" id="summary-client">{{ $transaction->customer->name }}</p>
                                                <p class="mb-0 text-muted small" id="summary-phone">{{ $transaction->customer->phone }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <h6><i class="fas fa-bed me-2"></i>{{ __('checkin.room_heading') }}</h6>
                                                <p class="mb-1" id="summary-room">{{ __('checkin.room_number', ['number' => $transaction->room->number]) }}</p>
                                                <p class="mb-0 text-muted small" id="summary-room-type">{{ $transaction->room->type->name ?? __('checkin.type_not_specified') }}</p>
                                                <p class="mb-0 text-muted small" id="summary-room-status"></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <h6><i class="fas fa-users me-2"></i>{{ __('checkin.occupants_heading') }}</h6>
                                                <p class="mb-1" id="summary-adults">{{ __('checkin.adults_summary', ['count' => 1]) }}</p>
                                                <p class="mb-0" id="summary-children">{{ __('checkin.children_summary', ['count' => 0]) }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <h6><i class="fas fa-id-card me-2"></i>{{ __('checkin.identity') }}</h6>
                                                <p class="mb-1" id="summary-id-type">{{ __('checkin.type_dash') }}</p>
                                                <p class="mb-0" id="summary-id-number">{{ __('checkin.number_dash') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="info-box">
                                                <h6><i class="fas fa-calendar-alt me-2"></i>{{ __('checkin.stay') }}</h6>
                                                <p class="mb-1">
                                                    <strong>{{ __('checkin.arrival_time') }}</strong> {{ $transaction->check_in->format('d/m/Y H:i') }}
                                                    <span class="text-muted">{{ __('checkin.checkin_now') }}</span>
                                                </p>
                                                <p class="mb-1"><strong>{{ __('checkin.departure_time') }}</strong> {{ $transaction->check_out->format('d/m/Y H:i') }}</p>
                                                <p class="mb-0"><strong>{{ __('checkin.duration') }}</strong> {{ $transaction->nights }} {{ __('checkin.nights_unit') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-warning mt-4">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>{{ __('checkin.attention_label') }}</strong> {{ __('checkin.confirmation_warning') }}
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(3)">
                                            <i class="fas fa-arrow-left me-2"></i>{{ __('checkin.back') }}
                                        </button>
                                        <button type="submit" class="btn btn-success" id="confirm-checkin">
                                            <i class="fas fa-check-circle me-2"></i>{{ __('checkin.confirm_checkin_button') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Sidebar: Informations rapides (MODIFIÉE) -->
                <div class="col-lg-4">
                    <!-- Statut de la réservation -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">{{ __('checkin.reservation_status') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="display-6 mb-2">#{{ $transaction->id }}</div>
                                <span class="badge bg-warning fs-6">{{ __('checkin.reservation_badge') }}</span>
                            </div>
                            
                            <!-- 🔴 Statut chambre -->
                            <div class="mb-3 text-center">
                                <span class="badge bg-{{ $transaction->room->status_color }} fs-6">
                                    <i class="fas {{ $transaction->room->status_icon }} me-1"></i>
                                    {{ __('checkin.room_status_label', ['status' => $transaction->room->status_label]) }}
                                </span>
                                @if($transaction->room->room_status_id == 6)
                                    <span class="badge bg-warning mt-2 d-block">
                                        <i class="fas fa-broom me-1"></i>{{ __('checkin.dirty_checkin_blocked') }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between">
                                    <span>{{ __('checkin.creation_date') }}</span>
                                    <strong>{{ $transaction->created_at->format('d/m/Y') }}</strong>
                                </div>
                                <div class="list-group-item d-flex justify-content-between">
                                    <span>{{ __('checkin.expected_arrival') }}</span>
                                    <strong>{{ $transaction->check_in->format('H:i') }}</strong>
                                </div>
                                <div class="list-group-item d-flex justify-content-between">
                                    <span>{{ __('checkin.nights_label') }}</span>
                                    <strong>{{ $transaction->nights }}</strong>
                                </div>
                                <div class="list-group-item d-flex justify-content-between">
                                    <span>{{ __('checkin.total_short') }}</span>
                                    <strong>{{ Helper::formatCFA($transaction->getTotalPrice()) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions rapides (MODIFIÉES) -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">{{ __('checkin.quick_actions') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if(!isset($canCheckIn) || $canCheckIn)
                                    <button type="button" class="btn btn-outline-primary" onclick="quickCheckIn()">
                                        <i class="fas fa-bolt me-2"></i>{{ __('checkin.quick_checkin_btn') }}
                                    </button>
                                @else
                                    <button type="button" class="btn btn-outline-warning" onclick="notifyHousekeeping({{ $transaction->room->id }})">
                                        <i class="fas fa-bell me-2"></i>{{ __('checkin.notify_housekeeping_title') }}
                                    </button>
                                @endif
                                <a href="{{ route('transaction.show', $transaction) }}" 
                                   class="btn btn-outline-info">
                                    <i class="fas fa-file-invoice me-2"></i>{{ __('checkin.view_invoice') }}
                                </a>
                                <a href="{{ route('customer.show', $transaction->customer) }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-user me-2"></i>{{ __('checkin.customer_profile') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Aide (MODIFIÉE) -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>{{ __('checkin.help') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-{{ (!isset($canCheckIn) || $canCheckIn) ? 'info' : 'warning' }} small mb-0">
                                @if(!isset($canCheckIn) || $canCheckIn)
                                    <p class="mb-2"><strong>{{ __('checkin.checkin_procedure') }}</strong></p>
                                    <ol class="mb-0 ps-3">
                                        <li>{{ __('checkin.verify_customer_identity') }}</li>
                                        <li>{{ __('checkin.complete_required_info') }}</li>
                                        <li>{{ __('checkin.assign_available_room') }}</li>
                                        <li>{{ __('checkin.confirm_checkin_step') }}</li>
                                    </ol>
                                @else
                                    <p class="mb-2"><strong>{{ __('checkin.checkin_blocked') }}</strong></p>
                                    <p class="mb-2">{{ $checkInBlockedReason ?? $transaction->room->getCheckInErrorMessage() }}</p>
                                    @if($isUrgentCleaning ?? false)
                                        <button class="btn btn-warning btn-sm mt-2 w-100" onclick="notifyHousekeeping({{ $transaction->room->id }})">
                                            <i class="fas fa-bell me-2"></i>{{ __('checkin.notify_housekeeping_btn') }}
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- 🔴 Fonction JavaScript pour notifier housekeeping -->
    <script>
    function notifyHousekeeping(roomId) {
        fetch('/checkin/notify-housekeeping/' + roomId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
            } else {
                alert('❌ ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ {{ __("checkin.notification_error") }}');
        });
    }

    function selectAlternativeRoomFromBlocked(roomId) {
        window.confirmAction('{{ __("checkin.change_room_confirm") }}', function () {
            window.location.href = '/checkin/{{ $transaction->id }}/change-room/' + roomId;
        });
    }
    </script>
@endsection

@section('footer')
<script>
let currentStep = 1;
let selectedRoomId = null;
let selectedRoomDirty = false;
let originalRoomPrice = {{ $transaction->room->price }};
let originalTotal = {{ $transaction->getTotalPrice() }};
let nights = {{ $transaction->nights }};

function updateStepIndicator(step) {
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
    for (let i = 1; i <= 4; i++) {
        document.getElementById(`tab-${i}`).classList.remove('active');
    }
    document.getElementById(`tab-${tabNumber}`).classList.add('active');
}

function nextStep(next) {
    if (currentStep === 2) {
        const adults = document.getElementById('adults').value;
        const idType = document.getElementById('id_type').value;
        const idNumber = document.getElementById('id_number').value;
        const nationality = document.getElementById('nationality').value;
        
        if (!adults || !idType || !idNumber || !nationality) {
            alert('{{ __("checkin.fill_step2_required") }}');
            return;
        }
        
        document.getElementById('summary-adults').textContent = `{!! __('checkin.adults_summary', ['count' => '') !!}${adults}`;
        document.getElementById('summary-children').textContent = `{!! __('checkin.children_summary', ['count' => '') !!}${document.getElementById('children').value || 0}`;
        document.getElementById('summary-id-type').textContent = `Type: ${document.getElementById('id_type').options[document.getElementById('id_type').selectedIndex].text}`;
        document.getElementById('summary-id-number').textContent = `{!! __('checkin.number_dash') !== 'Numéro: -' ? 'Number: ' : 'Numéro: ' !!}${idNumber}`;
    }
    
    if (currentStep === 3) {
        const roomOption = document.querySelector('input[name="room_option"]:checked').value;
        
        if (roomOption === 'change') {
            if (!selectedRoomId) {
                alert('{{ __("checkin.select_alternative_alert") }}');
                return;
            }
            
            if (selectedRoomDirty && !nextStep.__dirtyConfirmed) {
                window.confirmAction('{{ __("checkin.dirty_room_checkin_warning") }}', function () {
                    nextStep.__dirtyConfirmed = true;
                    nextStep(next);
                }, { icon: 'warning' });
                return;
            }
            nextStep.__dirtyConfirmed = false;

            const priceDifferenceElement = document.getElementById('price-difference');
            const priceDifferenceText = priceDifferenceElement.textContent.replace('CFA', '').replace(/\s/g, '');
            const priceDifference = parseInt(priceDifferenceText);
            
            if (priceDifference !== 0) {
                const confirmed = document.getElementById('confirmed_price_change').checked;
                if (!confirmed) {
                    alert('{{ __("checkin.confirm_price_alert") }}');
                    return;
                }
            }
        }
        
        if (roomOption === 'keep') {
            document.getElementById('summary-room').textContent = '{!! __("checkin.room_number", ["number" => $transaction->room->number]) !!}';
            document.getElementById('summary-room-type').textContent = '{{ $transaction->room->type->name ?? __("checkin.type_not_specified") }}';
            document.getElementById('summary-room-status').textContent = '';
            document.getElementById('confirmation-dirty-warning').style.display = 'none';
        } else {
            const selectedRoomElement = document.getElementById(`room-${selectedRoomId}`);
            const roomNumber = selectedRoomElement.querySelector('h6').textContent.replace('{!! __("checkin.room_number_short") !!} ', '');
            const roomType = selectedRoomElement.querySelector('p.text-muted').textContent;
            document.getElementById('summary-room').textContent = `{!! __("checkin.room_number_short") !!} ${roomNumber}`;
            document.getElementById('summary-room-type').textContent = roomType;
            
            if (selectedRoomDirty) {
                document.getElementById('summary-room-status').textContent = '{{ __("checkin.room_status_dirty") }}';
                document.getElementById('confirmation-dirty-warning').style.display = 'block';
            } else {
                document.getElementById('summary-room-status').textContent = '';
                document.getElementById('confirmation-dirty-warning').style.display = 'none';
            }
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
        document.getElementById('dirty-room-warning').style.display = 'none';
        document.getElementById('selected_room_dirty').value = '0';
        selectedRoomDirty = false;
    } else {
        changeRoomInput.value = '0';
        alternativeRoomsContainer.style.display = 'none';
        document.getElementById('new_room_id').value = '';
        document.getElementById('price-difference-info').style.display = 'none';
        document.getElementById('dirty-room-warning').style.display = 'none';
        document.getElementById('selected_room_dirty').value = '0';
        selectedRoomDirty = false;
        
        document.querySelectorAll('.alternative-room').forEach(room => {
            room.classList.remove('selected');
            const roomId = room.id.replace('room-', '');
            document.getElementById(`check-${roomId}`).style.display = 'none';
        });
    }
}

function selectAlternativeRoom(roomId, roomPrice, isDirty) {
    document.querySelectorAll('.alternative-room').forEach(room => {
        room.classList.remove('selected');
        const id = room.id.replace('room-', '');
        document.getElementById(`check-${id}`).style.display = 'none';
    });
    
    document.getElementById(`room-${roomId}`).classList.add('selected');
    document.getElementById(`check-${roomId}`).style.display = 'inline-block';
    
    document.getElementById('new_room_id').value = roomId;
    document.getElementById('selected_room_dirty').value = isDirty ? 1 : 0;
    selectedRoomId = roomId;
    selectedRoomDirty = isDirty;
    
    if (isDirty) {
        document.getElementById('dirty-room-warning').style.display = 'block';
    } else {
        document.getElementById('dirty-room-warning').style.display = 'none';
    }
    
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
    window.confirmAction('{{ __("checkin.quick_checkin_long_confirm") }}', function () {
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
                    ${data.message || '{{ __("checkin.quick_checkin_success_msg") }}'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.container-fluid').prepend(alertDiv);
                
                setTimeout(() => {
                    window.location.href = '{{ route("checkin.index") }}';
                }, 2000);
            } else {
                alert('Error: ' + (data.error || '{{ __("checkin.quick_checkin_failed") }}'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("checkin.error_during_quick_checkin") }}');
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    updateStepIndicator(1);
    
    document.getElementById('summary-client').textContent = '{{ $transaction->customer->name }}';
    document.getElementById('summary-phone').textContent = '{{ $transaction->customer->phone }}';
    document.getElementById('summary-room').textContent = '{!! __("checkin.room_number", ["number" => $transaction->room->number]) !!}';
    document.getElementById('summary-room-type').textContent = '{{ $transaction->room->type->name ?? __("checkin.type_not_specified") }}';
    
    @if(!$isRoomAvailable)
        document.getElementById('change_room').checked = true;
        toggleRoomOptions('change');
    @endif
    
    const form = document.getElementById('checkin-form');
    const submitButton = document.getElementById('confirm-checkin');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            if (form.classList.contains('submitting')) {
                e.preventDefault();
                return false;
            }
            
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> {{ __("checkin.processing") }}';
            form.classList.add('submitting');
            
            return true;
        });
    }
});
</script>
@endsection
