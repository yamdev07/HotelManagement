@extends('template.master')
@section('title', __('edit.page_title'))
@section('content')

<style>
:root {
    --primary: #2563eb;
    --primary-light: #3b82f6;
    --primary-soft: rgba(37, 99, 235, 0.08);
    --success: var(--g500);
    --success-light: rgb(from var(--g500) r g b / 0.08);
    --warning: #f59e0b;
    --warning-light: rgba(245, 158, 11, 0.08);
    --danger: #ef4444;
    --danger-light: rgba(239, 68, 68, 0.08);
    --info: #3b82f6;
    --info-light: rgba(59, 130, 246, 0.08);
    --dark: #1e293b;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1e293b;
    --radius: 12px;
    --shadow: 0 4px 20px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.05);
    --shadow-hover: 0 10px 30px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
    --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Carte principale */
.transaction-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
    overflow: hidden;
    transition: var(--transition);
    margin-bottom: 24px;
}
.transaction-card:hover {
    box-shadow: var(--shadow-hover);
    border-color: var(--gray-300);
}

/* En-tête de carte */
.transaction-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
    background: white;
    border-bottom: 1px solid var(--gray-200);
    flex-wrap: wrap;
    gap: 16px;
}
.transaction-card-header h5 {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-800);
    letter-spacing: -0.01em;
}
.transaction-card-header h5 i {
    color: var(--primary);
    font-size: 1.1rem;
}

/* Badges statut */
.badge-statut {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    line-height: 1;
    white-space: nowrap;
    gap: 4px;
    border: none;
    cursor: pointer;
    transition: var(--transition);
}
.badge-statut:hover {
    transform: translateY(-1px);
    filter: brightness(0.95);
}
.badge-reservation {
    background: var(--warning-light);
    color: #b45309;
    border: 1px solid rgba(245, 158, 11, 0.15);
}
.badge-active {
    background: var(--success-light);
    color: var(--g700);
    border: 1px solid rgb(from var(--g500) r g b / 0.15);
}
.badge-completed {
    background: var(--info-light);
    color: #1e40af;
    border: 1px solid rgba(37, 99, 235, 0.15);
}
.badge-cancelled {
    background: var(--danger-light);
    color: #b91c1c;
    border: 1px solid rgba(239, 68, 68, 0.15);
}
.badge-no_show {
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1px solid var(--gray-200);
}

/* Badge chambre */
.room-badge {
    background: var(--gray-100);
    color: var(--gray-700);
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 0.8rem;
    display: inline-block;
    border: 1px solid var(--gray-200);
}
.room-badge i {
    margin-right: 4px;
    font-size: 0.7rem;
    color: var(--gray-500);
}

/* Prix */
.price {
    font-weight: 600;
    font-family: 'Inter', monospace;
    font-size: 0.9rem;
}
.price-positive { color: var(--gray-800); }
.price-success { color: var(--success); }
.price-danger { color: var(--danger); font-weight: 700; }
.price-small { font-size: 0.7rem; font-weight: 400; color: var(--gray-500); }

/* Nuits badge */
.nights-badge {
    background: var(--gray-100);
    color: var(--gray-600);
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 0.7rem;
    white-space: nowrap;
    border: 1px solid var(--gray-200);
}

/* Boutons */
.btn-primary-custom {
    background: var(--primary);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.85rem;
    transition: var(--transition);
}
.btn-primary-custom:hover {
    background: var(--primary-light);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.2);
}
.btn-outline-custom {
    background: transparent;
    color: var(--gray-700);
    border: 1px solid var(--gray-300);
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.85rem;
    transition: var(--transition);
}
.btn-outline-custom:hover {
    background: var(--gray-50);
    border-color: var(--gray-400);
    transform: translateY(-2px);
}
.btn-danger-custom {
    background: var(--danger);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.85rem;
    transition: var(--transition);
}
.btn-danger-custom:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(239, 68, 68, 0.2);
}

/* Date picker */
.date-picker-container {
    position: relative;
}
.date-picker-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: var(--gray-500);
}
.datetime-wrapper {
    display: flex;
    gap: 10px;
    align-items: center;
}
.datetime-date {
    flex: 1;
}
.fixed-time-badge {
    background-color: var(--gray-100);
    border-radius: 6px;
    padding: 8px 12px;
    font-size: 0.85rem;
    color: var(--gray-700);
    display: inline-block;
    border: 1px solid var(--gray-200);
    white-space: nowrap;
}

/* Alertes */
.alert-status {
    border-left: 4px solid;
    padding: 12px 16px;
    border-radius: 8px;
    background: white;
    margin-bottom: 20px;
}
.alert-status-reservation { border-left-color: var(--warning); background: var(--warning-light); }
.alert-status-active { border-left-color: var(--success); background: var(--success-light); }
.alert-status-completed { border-left-color: var(--info); background: var(--info-light); }
.alert-status-cancelled { border-left-color: var(--danger); background: var(--danger-light); }
.alert-status-no_show { border-left-color: var(--gray-500); background: var(--gray-100); }

/* Nights counter */
.nights-counter {
    background: var(--gray-50);
    border-radius: 8px;
    padding: 16px;
    margin-top: 16px;
    border: 1px solid var(--gray-200);
}

/* Info bulle */
.info-badge {
    background: var(--info-light);
    color: var(--info);
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
</style>

<div class="container-fluid px-4 py-3">

    <!-- En-tête avec fil d'Ariane -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">{{ __('edit.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('transaction.index') }}">{{ __('edit.reservations') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('edit.breadcrumb_edit') }} #{{ $transaction->id }}</li>
                </ol>
            </nav>
            <h2 class="h5 mb-0" style="color: var(--gray-800); font-weight: 700;">
                <i class="fas fa-edit me-2" style="color: var(--primary);"></i>
                {{ __('edit.edit_reservation_title', ['id' => $transaction->id]) }}
            </h2>
            <p class="text-muted small mb-0">{{ __('edit.client_label', ['name' => $transaction->customer->name]) }}</p>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('transaction.show', $transaction) }}" class="btn btn-outline-custom">
                <i class="fas fa-arrow-left me-2"></i>{{ __('edit.back_button') }}
            </a>
        </div>
    </div>

    <!-- Messages de session -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {!! session('success') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    
    @if(session('error') || session('failed'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') ?? session('failed') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Alertes selon le statut -->
    @php
        $now = \Carbon\Carbon::now();
        $checkInDateTime = \Carbon\Carbon::parse($transaction->check_in)->setTime(12, 0, 0);
        $checkOutDateTime = \Carbon\Carbon::parse($transaction->check_out)->setTime(12, 0, 0);
        $nightsPassed = 0;
        if ($transaction->status == 'active') {
            $nightsPassed = $checkInDateTime->diffInDays($now) > 0 ? $checkInDateTime->diffInDays($now) : 0;
        }
    @endphp

    @if($transaction->status == 'cancelled')
    <div class="alert alert-danger">
        <i class="fas fa-ban me-2"></i>
        <strong>{{ __('edit.cancelled_title') }}</strong> - {{ __('edit.cancelled_text') }}
        @if($transaction->cancelled_at)
        <br><small>{{ __('edit.cancelled_at', ['date' => \Carbon\Carbon::parse($transaction->cancelled_at)->format('d/m/Y H:i')]) }}</small>
        @endif
    </div>
    @endif

    @if($transaction->status == 'no_show')
    <div class="alert alert-secondary">
        <i class="fas fa-user-slash me-2"></i>
        <strong>{{ __('edit.no_show_title') }}</strong> - {{ __('edit.no_show_text') }}
    </div>
    @endif

    @if($transaction->status == 'completed')
    <div class="alert alert-info">
        <i class="fas fa-check-circle me-2"></i>
        <strong>{{ __('edit.completed_title') }}</strong> - {{ __('edit.completed_text') }}
    </div>
    @endif

    <div class="row">
        <!-- Colonne principale - Formulaire -->
        <div class="col-lg-8">
            <div class="transaction-card">
                <div class="transaction-card-header">
                    <h5><i class="fas fa-edit"></i> {{ __('edit.edit_reservation_card') }}</h5>
                    <span class="badge-statut badge-{{ $transaction->status }}">
                        {{-- Icônes Font Awesome au lieu d'emojis, cohérent avec le reste de l'app · issue #185 --}}
                        @if($transaction->status == 'reservation') <i class="fas fa-calendar-alt"></i>
                        @elseif($transaction->status == 'active') <i class="fas fa-hotel"></i>
                        @elseif($transaction->status == 'completed') <i class="fas fa-check-circle"></i>
                        @elseif($transaction->status == 'cancelled') <i class="fas fa-times-circle"></i>
                        @else <i class="fas fa-user-times"></i>
                        @endif
                        {{ $transaction->status_label }}
                    </span>
                </div>
                <div class="p-4">
                    @if(in_array($transaction->status, ['cancelled', 'no_show', 'completed']))
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ __('edit.limited_status_warning', ['status' => $transaction->status_label]) }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('transaction.update', $transaction) }}" id="editForm">
                        @csrf
                        @method('PUT')

                        <!-- Client (non modifiable) -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-user me-2"></i>{{ __('edit.client_section') }}
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small">{{ __('edit.client_name') }}</label>
                                    <input type="text" class="form-control" value="{{ $transaction->customer->name }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small">{{ __('edit.client_phone') }}</label>
                                    <input type="text" class="form-control" value="{{ $transaction->customer->phone ?? __('edit.not_specified') }}" readonly>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label class="form-label text-muted small">{{ __('edit.client_email') }}</label>
                                    <input type="text" class="form-control" value="{{ $transaction->customer->email ?? __('edit.not_specified') }}" readonly>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label class="form-label text-muted small">{{ __('edit.client_actions') }}</label>
                                    <div>
                                        <a href="{{ route('customer.show', $transaction->customer) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye me-1"></i>{{ __('edit.view_profile') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ============ SECTION CHAMBRE MODIFIABLE ============ -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-bed me-2"></i>{{ __('edit.room_section') }}
                                @if($transaction->status == 'reservation')
                                    <span class="info-badge ms-2">
                                        <i class="fas fa-exchange-alt"></i> {{ __('edit.room_changeable') }}
                                    </span>
                                @endif
                            </h6>

                            @if($transaction->status == 'reservation' && isset($availableRooms))
                                <!-- Sélection de chambre pour les réservations -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold">{{ __('edit.select_room') }}</label>
                                        <select name="room_id" id="roomSelect" class="form-control @error('room_id') is-invalid @enderror" required>
                                            <option value="">{{ __('edit.choose_room') }}</option>
                                            
                                            <!-- Chambre actuelle -->
                                            @php $currentRoom = $transaction->room; @endphp
                                            @if($currentRoom)
                                            <optgroup label="{{ __('edit.current_room') }}">
                                                <option value="{{ $currentRoom->id }}" selected>
                                                    🏠 {{ __('edit.room_number_format', ['number' => $currentRoom->number, 'type' => $currentRoom->type->name ?? 'Standard', 'price' => number_format($currentRoom->price, 0, ',', ' ')]) }}
                                                </option>
                                            </optgroup>
                                            @endif

                                            <!-- Autres chambres disponibles -->
                                            @if(isset($availableRooms) && $availableRooms->where('id', '!=', $currentRoom->id)->count() > 0)
                                            <optgroup label="{{ __('edit.available_rooms') }}">
                                                @foreach($availableRooms->where('id', '!=', $currentRoom->id) as $room)
                                                <option value="{{ $room->id }}" data-price="{{ $room->price }}">
                                                    ✅ {{ __('edit.room_number_format', ['number' => $room->number, 'type' => $room->type->name ?? 'Standard', 'price' => number_format($room->price, 0, ',', ' ')]) }}
                                                </option>
                                                @endforeach
                                            </optgroup>
                                            @endif

                                            <!-- Chambres occupées (affichage seulement) -->
                                            @if(isset($occupiedRooms) && $occupiedRooms->count() > 0)
                                            <optgroup label="{{ __('edit.occupied_rooms') }}">
                                                @foreach($occupiedRooms as $room)
                                                <option value="{{ $room->id }}" disabled class="text-muted">
                                                    ❌ {{ __('edit.room_occupied_format', ['number' => $room->number, 'type' => $room->type->name ?? 'Standard']) }}
                                                </option>
                                                @endforeach
                                            </optgroup>
                                            @endif
                                        </select>

                                        <!-- Informations sur les prix si changement -->
                                        <div id="roomPriceInfo" class="mt-2 alert alert-info" style="display: none;">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <span id="priceChangeMessage"></span>
                                        </div>

                                        @error('room_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            {{ __('edit.room_availability_help') }}
                                        </small>
                                    </div>
                                </div>
                            @else
                                <!-- Affichage seule pour les autres statuts -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small">{{ __('edit.room_number') }}</label>
                                        <div class="room-badge">
                                            <i class="fas fa-door-closed"></i> {{ $transaction->room->number }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small">{{ __('edit.room_type') }}</label>
                                        <div>{{ $transaction->room->type->name ?? 'Standard' }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small">{{ __('edit.room_price_per_night') }}</label>
                                        <div class="price price-positive">{{ number_format($transaction->room->price, 0, ',', ' ') }} CFA</div>
                                    </div>
                                </div>
                                <input type="hidden" name="room_id" value="{{ $transaction->room_id }}">
                            @endif
                        </div>

                        <!-- Dates du séjour -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-calendar-alt me-2"></i>{{ __('edit.dates_section') }}
                                <span class="info-badge ms-2">
                                    <i class="fas fa-clock"></i> {{ __('edit.fixed_times_badge') }}
                                </span>
                            </h6>

                            <!-- Affichage des nuits passées (pour séjour en cours) -->
                            @if($transaction->status == 'active' && $nightsPassed > 0)
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>{{ __('edit.nights_passed_info') }}</strong> {{ $nightsPassed }} {{ $nightsPassed > 1 ? __('edit.nights') : __('edit.night') }}
                                ({{ __('edit.since') }} {{ $checkInDateTime->format('d/m/Y') }})
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ __('edit.check_in_date') }}</label>
                                    <div class="datetime-wrapper">
                                        <div class="datetime-date date-picker-container">
                                            <input type="date" 
                                                   class="form-control @error('check_in_date') is-invalid @enderror" 
                                                   id="check_in_date" 
                                                   name="check_in_date" 
                                                   value="{{ old('check_in_date', $checkInDateTime->format('Y-m-d')) }}"
                                                   {{ in_array($transaction->status, ['cancelled', 'no_show', 'completed']) ? 'readonly' : '' }}
                                                   required>
                                            <span class="date-picker-icon"><i class="fas fa-calendar"></i></span>
                                            @error('check_in_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="fixed-time-badge">
                                            <i class="fas fa-clock me-1"></i>12:00
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ __('edit.fixed_time_check_in') }}</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ __('edit.check_out_date') }}</label>
                                    <div class="datetime-wrapper">
                                        <div class="datetime-date date-picker-container">
                                            <input type="date" 
                                                   class="form-control @error('check_out_date') is-invalid @enderror" 
                                                   id="check_out_date" 
                                                   name="check_out_date" 
                                                   value="{{ old('check_out_date', $checkOutDateTime->format('Y-m-d')) }}"
                                                   {{ in_array($transaction->status, ['cancelled', 'no_show', 'completed']) ? 'readonly' : '' }}
                                                   required>
                                            <span class="date-picker-icon"><i class="fas fa-calendar"></i></span>
                                            @error('check_out_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="fixed-time-badge">
                                            <i class="fas fa-clock me-1"></i>12:00
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ __('edit.fixed_time_check_out') }}</small>
                                </div>
                            </div>

                            <!-- Champs cachés pour les heures -->
                            <input type="hidden" name="check_in_time" value="12:00">
                            <input type="hidden" name="check_out_time" value="12:00">
                            <input type="hidden" id="check_in" name="check_in">
                            <input type="hidden" id="check_out" name="check_out">

                            <!-- Calculateur de nuits -->
                            <div class="nights-counter">
                                <div class="row">
                                    <div class="col-md-3">
                                        <span class="text-muted small">{{ __('edit.nights_label') }}</span>
                                        <div id="nights-count" class="h5 mb-0 text-primary">0</div>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="text-muted small">{{ __('edit.total_price') }}</span>
                                        <div id="new-total" class="h5 mb-0 text-success">0 CFA</div>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="text-muted small">{{ __('edit.already_paid') }}</span>
                                        <div class="h5 mb-0">{{ number_format($transaction->getTotalPayment(), 0, ',', ' ') }} CFA</div>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="text-muted small">{{ __('edit.difference') }}</span>
                                        <div id="price-difference" class="h5 mb-0">0 CFA</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bouton vérification disponibilité -->
                            @if(!in_array($transaction->status, ['cancelled', 'no_show', 'completed']))
                            <div class="mt-3">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="checkAvailability">
                                    <i class="fas fa-search me-1"></i> {{ __('edit.check_availability') }}
                                </button>
                                <div id="availabilityResult" class="mt-2"></div>
                            </div>
                            @endif
                        </div>

                        <!-- Statut (avec conditions) -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-exchange-alt me-2"></i>{{ __('edit.status_section') }}
                            </h6>

                            @php
                                $isFullyPaid = $transaction->getRemainingPayment() <= 0;
                                $canChangeStatus = in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']) 
                                                    && !in_array($transaction->status, ['cancelled', 'no_show']);
                            @endphp

                            @if($canChangeStatus)
                            <div class="mb-3">
                                <label class="form-label">{{ __('edit.change_status') }}</label>
                                <select name="status" id="statusSelect" class="form-control">
                                    <option value="reservation" {{ $transaction->status == 'reservation' ? 'selected' : '' }} 
                                        {{ $transaction->status == 'completed' ? 'disabled' : '' }}>
                                        {{ __('edit.status_reservation') }}
                                    </option>
                                    <option value="active" {{ $transaction->status == 'active' ? 'selected' : '' }}
                                        {{ $transaction->status == 'completed' ? 'disabled' : '' }}>
                                        {{ __('edit.status_active') }}
                                    </option>
                                    <option value="completed" {{ $transaction->status == 'completed' ? 'selected' : '' }}
                                        {{ !$isFullyPaid ? 'disabled' : '' }}
                                        data-requires-payment="true">
                                        {{ __('edit.status_completed') }} {{ !$isFullyPaid ? __('edit.status_incomplete_payment') : '' }}
                                    </option>
                                    <option value="cancelled" {{ $transaction->status == 'cancelled' ? 'selected' : '' }}>
                                        {{ __('edit.status_cancelled') }}
                                    </option>
                                    <option value="no_show" {{ $transaction->status == 'no_show' ? 'selected' : '' }}>
                                        {{ __('edit.status_no_show') }}
                                    </option>
                                </select>
                                <small class="text-muted" id="statusHelp">
                                    @if($transaction->status == 'active' && !$isFullyPaid)
                                        {{ __('edit.incomplete_payment_help') }}
                                    @endif
                                </small>
                            </div>

                            <!-- Champ raison d'annulation (caché par défaut) -->
                            <div id="cancelReasonField" style="display: none;" class="mb-3">
                                <label class="form-label">{{ __('edit.cancel_reason_label') }} <span class="text-danger">*</span></label>
                                <textarea name="cancel_reason" class="form-control @error('cancel_reason') is-invalid @enderror" rows="2"
                                          minlength="3" maxlength="500"
                                          placeholder="{{ __('edit.cancel_reason_placeholder') }}">{{ old('cancel_reason', $transaction->cancel_reason) }}</textarea>
                                @error('cancel_reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                {{ __('edit.current_status') }} <strong>{{ $transaction->status_label }}</strong>
                                @if($transaction->status == 'cancelled' && $transaction->cancel_reason)
                                <br><small>{{ __('edit.reason_label') }} {{ $transaction->cancel_reason }}</small>
                                @endif
                            </div>
                            @endif
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label class="form-label">{{ __('edit.notes_label') }}</label>
                            <textarea name="notes" class="form-control" rows="3" 
                                      placeholder="{{ __('edit.notes_placeholder') }}">{{ old('notes', $transaction->notes) }}</textarea>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-custom" id="cancelEditBtn">
                                <i class="fas fa-times me-2"></i>{{ __('edit.cancel_button') }}
                            </button>
                            <button type="submit" class="btn btn-primary-custom" id="saveBtn">
                                <i class="fas fa-save me-2"></i>{{ __('edit.save_button') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Colonne latérale - Infos et actions -->
        <div class="col-lg-4">
            <!-- Résumé -->
            <div class="transaction-card mb-4">
                <div class="transaction-card-header">
                    <h5><i class="fas fa-info-circle"></i> {{ __('edit.summary_title') }}</h5>
                </div>
                <div class="p-4">
                    <table class="table table-sm">
                        <tr>
                            <td class="text-muted">{{ __('edit.reservation_id') }}</td>
                            <td class="text-end fw-bold">#{{ $transaction->id }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">{{ __('edit.summary_client') }}</td>
                            <td class="text-end">{{ $transaction->customer->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">{{ __('edit.summary_room') }}</td>
                            <td class="text-end">{{ $transaction->room->number }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">{{ __('edit.arrival') }}</td>
                            <td class="text-end">{{ $checkInDateTime->format('d/m/Y') }} <small>12:00</small></td>
                        </tr>
                        <tr>
                            <td class="text-muted">{{ __('edit.departure') }}</td>
                            <td class="text-end">{{ $checkOutDateTime->format('d/m/Y') }} <small>12:00</small></td>
                        </tr>
                        <tr>
                            <td class="text-muted">{{ __('edit.summary_nights') }}</td>
                            <td class="text-end">{{ $checkInDateTime->diffInDays($checkOutDateTime) }}</td>
                        </tr>
                        @if($transaction->status == 'active' && $nightsPassed > 0)
                        <tr>
                            <td class="text-muted">{{ __('edit.nights_passed') }}</td>
                            <td class="text-end text-warning fw-bold">{{ $nightsPassed }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-muted">{{ __('edit.total') }}</td>
                            <td class="text-end fw-bold">{{ number_format($transaction->getTotalPrice(), 0, ',', ' ') }} CFA</td>
                        </tr>
                        <tr>
                            <td class="text-muted">{{ __('edit.paid') }}</td>
                            <td class="text-end text-success">{{ number_format($transaction->getTotalPayment(), 0, ',', ' ') }} CFA</td>
                        </tr>
                        <tr>
                            <td class="text-muted">{{ __('edit.remaining') }}</td>
                            <td class="text-end {{ $transaction->getRemainingPayment() > 0 ? 'text-danger fw-bold' : '' }}">
                                {{ number_format($transaction->getRemainingPayment(), 0, ',', ' ') }} CFA
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">{{ __('edit.created_at') }}</td>
                            <td class="text-end">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="transaction-card mb-4">
                <div class="transaction-card-header">
                    <h5><i class="fas fa-bolt"></i> {{ __('edit.quick_actions_title') }}</h5>
                </div>
                <div class="p-4">
                    <div class="d-grid gap-2">
                        @if($transaction->status == 'reservation' && in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']))
                        <form action="{{ route('transaction.mark-arrived', $transaction) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" 
                                    onclick="return confirm('{{ __('edit.confirm_arrival') }}')">
                                <i class="fas fa-sign-in-alt me-2"></i>{{ __('edit.mark_arrived') }}
                            </button>
                        </form>
                        @endif

                        @if($transaction->status == 'active' && in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']))
                            @if($now->gte($checkOutDateTime) && $now->lte($checkOutDateTime->copy()->setTime(14,0,0)))
                            <form action="{{ route('transaction.mark-departed', $transaction) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-info w-100">
                                    <i class="fas fa-sign-out-alt me-2"></i>{{ __('edit.departure_largesse') }}
                                </button>
                            </form>
                            @elseif($now->gt($checkOutDateTime->copy()->setTime(14,0,0)))
                            <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#overrideModal">
                                <i class="fas fa-gavel me-2"></i>{{ __('edit.departure_override') }}
                            </button>
                            @endif
                        @endif

                        @if($transaction->getRemainingPayment() > 0 && !in_array($transaction->status, ['cancelled', 'no_show', 'completed']))
                        <a href="{{ route('transaction.payment.create', $transaction) }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-money-bill-wave me-2"></i>{{ __('edit.add_payment') }}
                        </a>
                        @endif

                        <a href="{{ route('transaction.show', $transaction) }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-eye me-2"></i>{{ __('edit.view_details') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Historique -->
            <div class="transaction-card">
                <div class="transaction-card-header">
                    <h5><i class="fas fa-history"></i> {{ __('edit.history_title') }}</h5>
                </div>
                <div class="p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 pb-2 border-bottom">
                            <small class="text-muted">{{ __('edit.creation') }}</small><br>
                            <strong>{{ $transaction->created_at->format('d/m/Y H:i') }}</strong>
                        </li>
                        <li class="mb-2 pb-2 border-bottom">
                            <small class="text-muted">{{ __('edit.last_modification') }}</small><br>
                            <strong>{{ $transaction->updated_at->format('d/m/Y H:i') }}</strong>
                        </li>
                        @if($transaction->cancelled_at)
                        <li class="mb-2">
                            <small class="text-muted">{{ __('edit.cancellation') }}</small><br>
                            <strong class="text-danger">{{ $transaction->cancelled_at->format('d/m/Y H:i') }}</strong>
                        </li>
                        @endif
                    </ul>
                    <a href="{{ route('transaction.history', $transaction) }}" class="btn btn-sm btn-outline-dark mt-3 w-100">
                        <i class="fas fa-history me-1"></i> {{ __('edit.view_full_history') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal dérogation après 14h -->
<div class="modal fade" id="overrideModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ __('edit.override_title') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('transaction.mark-departed', $transaction) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>{{ __('edit.override_confirm') }}</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-clock me-2"></i>
                        {{ __('edit.departure_planned') }} {{ $checkOutDateTime->translatedFormat('d/m/Y') }} à 12h00<br>
                        {{ __('edit.current_time') }} {{ $now->format('H:i') }}
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('edit.override_reason_label') }}</label>
                        <textarea name="override_reason" class="form-control" rows="2" 
                                  placeholder="{{ __('edit.override_reason_placeholder') }}" required></textarea>
                    </div>
                    <input type="hidden" name="override" value="1">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('edit.cancel_button') }}</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-check me-2"></i>{{ __('edit.authorize_departure') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============ ÉLÉMENTS ============
    const checkInDate = document.getElementById('check_in_date');
    const checkOutDate = document.getElementById('check_out_date');
    const checkInHidden = document.getElementById('check_in');
    const checkOutHidden = document.getElementById('check_out');
    const nightsCount = document.getElementById('nights-count');
    const newTotal = document.getElementById('new-total');
    const priceDiff = document.getElementById('price-difference');
    const statusSelect = document.getElementById('statusSelect');
    const cancelReasonField = document.getElementById('cancelReasonField');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const saveBtn = document.getElementById('saveBtn');
    const checkAvailabilityBtn = document.getElementById('checkAvailability');
    const roomSelect = document.getElementById('roomSelect');
    const roomPriceInfo = document.getElementById('roomPriceInfo');
    const priceChangeMessage = document.getElementById('priceChangeMessage');
    
    const currentRoomPrice = {{ $transaction->room->price }};
    const originalTotal = {{ $transaction->getTotalPrice() }};
    const transactionId = {{ $transaction->id }};

    // ============ FONCTIONS ============
    function combineDateTime(dateInput) {
        return dateInput.value ? `${dateInput.value}T12:00` : null;
    }

    function calculateNights(selectedRoomPrice = null) {
        const checkIn = combineDateTime(checkInDate);
        const checkOut = combineDateTime(checkOutDate);
        const pricePerNight = selectedRoomPrice || currentRoomPrice;
        
        if (checkInHidden) checkInHidden.value = checkIn;
        if (checkOutHidden) checkOutHidden.value = checkOut;
        
        if (checkIn && checkOut) {
            const start = new Date(checkIn);
            const end = new Date(checkOut);
            
            if (end > start) {
                const diffTime = end - start;
                const nights = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const total = nights * pricePerNight;
                const difference = total - originalTotal;
                
                nightsCount.textContent = nights;
                newTotal.textContent = new Intl.NumberFormat('fr-FR').format(total) + ' CFA';
                
                if (difference > 0) {
                    priceDiff.innerHTML = `<span class="text-danger">+${new Intl.NumberFormat('fr-FR').format(difference)} CFA</span>`;
                } else if (difference < 0) {
                    priceDiff.innerHTML = `<span class="text-success">${new Intl.NumberFormat('fr-FR').format(difference)} CFA</span>`;
                } else {
                    priceDiff.innerHTML = '0 CFA';
                }
                
                // Validation départ > arrivée
                if (end <= start) {
                    checkOutDate.setCustomValidity('{{ __('edit.js_departure_after_arrival') }}');
                } else {
                    checkOutDate.setCustomValidity('');
                }
            }
        }
    }

    // ============ GESTION CHANGEMENT DE CHAMBRE ============
    if (roomSelect) {
        // Récupérer les prix des options
        const roomPrices = {};
        document.querySelectorAll('#roomSelect option[data-price]').forEach(option => {
            roomPrices[option.value] = parseFloat(option.dataset.price);
        });
        
        roomSelect.addEventListener('change', function() {
            const selectedRoomId = this.value;
            
            if (selectedRoomId && roomPrices[selectedRoomId]) {
                const newPrice = roomPrices[selectedRoomId];
                const priceDifference = newPrice - currentRoomPrice;
                
                // Recalculer avec le nouveau prix
                calculateNights(newPrice);
                
                // Afficher l'info de prix
                if (priceDifference !== 0) {
                    roomPriceInfo.style.display = 'block';
                    
                    if (priceDifference > 0) {
                        priceChangeMessage.innerHTML = `
                            <strong class="text-danger">⚠️ {{ __('edit.surcharge') }}</strong><br>
                            {{ __('edit.price_per_night') }}: +${new Intl.NumberFormat('fr-FR').format(priceDifference)} CFA<br>
                            {{ __('edit.impact_on_total') }}: +${new Intl.NumberFormat('fr-FR').format(priceDifference * parseInt(nightsCount.textContent || 0))} CFA
                        `;
                    } else {
                        priceChangeMessage.innerHTML = `
                            <strong class="text-success">✅ {{ __('edit.reduction') }}</strong><br>
                            {{ __('edit.price_per_night') }}: ${new Intl.NumberFormat('fr-FR').format(priceDifference)} CFA<br>
                            {{ __('edit.impact_on_total') }}: ${new Intl.NumberFormat('fr-FR').format(priceDifference * parseInt(nightsCount.textContent || 0))} CFA
                        `;
                    }
                } else {
                    roomPriceInfo.style.display = 'none';
                }
            } else {
                roomPriceInfo.style.display = 'none';
                calculateNights(currentRoomPrice);
            }
        });
    }

    // ============ GESTION STATUT ============
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            // Afficher/cacher champ raison d'annulation
            cancelReasonField.style.display = this.value === 'cancelled' ? 'block' : 'none';
            
            // Vérifications avant changement
            if (this.value === 'completed') {
                const isFullyPaid = {{ $transaction->getRemainingPayment() <= 0 ? 'true' : 'false' }};
                if (!isFullyPaid) {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __('edit.js_incomplete_payment_title') }}',
                        text: '{{ __('edit.js_incomplete_payment_text', ['amount' => number_format($transaction->getRemainingPayment(), 0, ',', ' ')]) }}',
                        confirmButtonText: '{{ __('edit.js_understood') }}'
                    });
                    this.value = '{{ $transaction->status }}';
                }
            }
            
            if (this.value === 'active') {
                const today = new Date().toISOString().split('T')[0];
                const checkIn = checkInDate.value;
                if (checkIn > today) {
                    Swal.fire({
                        icon: 'warning',
                        title: '{{ __('edit.js_date_not_reached_title') }}',
                        text: `{{ __('edit.js_arrival_planned_on', ['date' => '']) }} ${new Date(checkIn).toLocaleDateString('fr-FR')}`,
                        confirmButtonText: '{{ __('edit.js_understood') }}'
                    });
                    this.value = '{{ $transaction->status }}';
                }
            }
        });
    }

    // ============ VÉRIFICATION DISPONIBILITÉ ============
    if (checkAvailabilityBtn) {
        checkAvailabilityBtn.addEventListener('click', async function() {
            const checkIn = combineDateTime(checkInDate);
            const checkOut = combineDateTime(checkOutDate);
            
            if (!checkIn || !checkOut) {
                Swal.fire('{{ __('edit.js_error_title') }}', '{{ __('edit.js_select_dates') }}', 'warning');
                return;
            }
            
            try {
                Swal.fire({ title: '{{ __('edit.js_checking_title') }}', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                
                const response = await fetch(`/transaction/${transactionId}/check-availability`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify({ 
                        check_in: checkIn, 
                        check_out: checkOut,
                        room_id: roomSelect ? roomSelect.value : {{ $transaction->room_id }}
                    })
                });
                
                const data = await response.json();
                Swal.close();
                
                const resultDiv = document.getElementById('availabilityResult');
                if (data.available) {
                    resultDiv.innerHTML = '<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>{{ __('edit.js_room_available') }}</div>';
                } else {
                    resultDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>{{ __('edit.js_room_not_available') }}</div>';
                }
            } catch (error) {
                Swal.close();
                console.error(error);
            }
        });
    }

    // ============ BOUTON ANNULER ============
    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', function() {
            Swal.fire({
                title: '{{ __('edit.js_cancel_edit_title') }}',
                text: '{{ __('edit.js_cancel_edit_text') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ __('edit.js_yes_cancel') }}',
                cancelButtonText: '{{ __('edit.js_no_stay') }}'
            }).then(result => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("transaction.show", $transaction) }}';
                }
            });
        });
    }

    // ============ VALIDATION FORMULAIRE ============
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const checkIn = combineDateTime(checkInDate);
        const checkOut = combineDateTime(checkOutDate);
        
        if (!checkIn || !checkOut) {
            Swal.fire('{{ __('edit.js_error_title') }}', '{{ __('edit.js_fill_all_dates') }}', 'error');
            return;
        }
        
        if (new Date(checkOut) <= new Date(checkIn)) {
            Swal.fire('{{ __('edit.js_error_title') }}', '{{ __('edit.js_departure_after_arrival') }}', 'error');
            return;
        }
        
        // Vérification sélection chambre
        if (roomSelect && !roomSelect.value) {
            Swal.fire('{{ __('edit.js_error_title') }}', '{{ __('edit.js_select_room') }}', 'error');
            roomSelect.focus();
            return;
        }
        
        // Vérification statut
        if (statusSelect && statusSelect.value === 'completed') {
            const isFullyPaid = {{ $transaction->getRemainingPayment() <= 0 ? 'true' : 'false' }};
            if (!isFullyPaid) {
                Swal.fire('{{ __('edit.js_error_title') }}', '{{ __('edit.js_incomplete_payment_error') }}', 'error');
                return;
            }
        }
        
        // Confirmation
        Swal.fire({
            title: '{{ __('edit.js_save_title') }}',
            html: `<div class="alert alert-info">
                {{ __('edit.js_save_html_arrival') }} ${checkInDate.value}<br>
                {{ __('edit.js_save_html_departure') }} ${checkOutDate.value}<br>
                {{ __('edit.js_save_html_nights') }} ${nightsCount.textContent}<br>
                ${roomSelect ? '{{ __('edit.js_save_html_room') }} ' + roomSelect.options[roomSelect.selectedIndex]?.text.split(' - ')[0] : ''}
            </div>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '{{ __('edit.js_yes_save') }}',
            cancelButtonText: '{{ __('edit.cancel_button') }}'
        }).then(result => {
            if (result.isConfirmed) {
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __('edit.js_saving') }}';
                e.target.submit();
            }
        });
    });

    // ============ ÉVÉNEMENTS DATES ============
    if (checkInDate && checkOutDate) {
        checkInDate.addEventListener('change', function() {
            const selectedRoomPrice = roomSelect && roomSelect.value ? 
                (roomPrices ? roomPrices[roomSelect.value] : null) : null;
            calculateNights(selectedRoomPrice || currentRoomPrice);
            
            // Mettre à jour le min du départ
            const nextDay = new Date(this.value);
            nextDay.setDate(nextDay.getDate() + 1);
            checkOutDate.min = nextDay.toISOString().split('T')[0];
        });
        
        checkOutDate.addEventListener('change', function() {
            const selectedRoomPrice = roomSelect && roomSelect.value ? 
                (roomPrices ? roomPrices[roomSelect.value] : null) : null;
            calculateNights(selectedRoomPrice || currentRoomPrice);
        });
        
        // Calcul initial
        calculateNights(currentRoomPrice);
        
        // Définir min du départ
        const nextDay = new Date(checkInDate.value);
        nextDay.setDate(nextDay.getDate() + 1);
        checkOutDate.min = nextDay.toISOString().split('T')[0];
    }
});
</script>
@endsection