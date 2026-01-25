@extends('template.master')

@section('title', 'Recherche de disponibilité')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark">Recherche de disponibilité</h1>
                    <p class="text-muted mb-0">Trouvez des chambres disponibles pour vos dates</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('availability.calendar') }}" class="btn btn-outline-primary">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Calendrier
                    </a>
                    <a href="{{ route('availability.inventory') }}" class="btn btn-outline-primary">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Inventaire
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de recherche -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('availability.search') }}" id="searchForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Arrivée</label>
                                <input type="date" 
                                       name="check_in" 
                                       class="form-control" 
                                       value="{{ $checkIn }}"
                                       min="{{ now()->format('Y-m-d') }}"
                                       required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Départ</label>
                                <input type="date" 
                                       name="check_out" 
                                       class="form-control" 
                                       value="{{ $checkOut }}"
                                       min="{{ now()->addDay()->format('Y-m-d') }}"
                                       required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Adultes</label>
                                <select name="adults" class="form-select">
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ $adults == $i ? 'selected' : '' }}>
                                            {{ $i }} {{ $i == 1 ? 'adulte' : 'adultes' }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Enfants</label>
                                <select name="children" class="form-select">
                                    @for($i = 0; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ $children == $i ? 'selected' : '' }}>
                                            {{ $i }} {{ $i == 1 ? 'enfant' : 'enfants' }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Type de chambre</label>
                                <select name="room_type_id" class="form-select">
                                    <option value="">Tous types</option>
                                    @foreach($roomTypes as $type)
                                        <option value="{{ $type->id }}" {{ $roomTypeId == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>
                                    Rechercher
                                </button>
                                <a href="{{ route('availability.search') }}" class="btn btn-outline-secondary">
                                    Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Information</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-calendar-check me-1"></i>
                                Période: {{ $nights }} nuit(s)
                            </small>
                        </li>
                        <li class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-users me-1"></i>
                                Personnes: {{ $adults + $children }}
                            </small>
                        </li>
                        <li class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Check-in: 14h00
                            </small>
                        </li>
                        <li>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Check-out: 12h00
                            </small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Résultats -->
    @if(request()->has('check_in'))
    <div class="row">
        <div class="col-12">
            <!-- Chambres disponibles -->
            @if(count($availableRooms) > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Chambres disponibles ({{ count($availableRooms) }})</strong>
                        </div>
                        <div class="badge bg-light text-success">
                            {{ $checkIn }} → {{ $checkOut }}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($availableRooms as $roomData)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-success border-2">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="fw-bold text-dark mb-1">
                                                Chambre {{ $roomData['room']->number }}
                                            </h5>
                                            <span class="badge bg-success">
                                                {{ $roomData['room']->type->name ?? 'Standard' }}
                                            </span>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-success fs-4">
                                                {{ number_format($roomData['total_price'], 0, ',', ' ') }} FCFA
                                            </div>
                                            <small class="text-muted">
                                                {{ number_format($roomData['price_per_night'], 0, ',', ' ') }} FCFA/nuit
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-users text-muted me-2"></i>
                                            <small class="text-muted">Capacité: {{ $roomData['room']->capacity }} personnes</small>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-bed text-muted me-2"></i>
                                            <small class="text-muted">Type: {{ $roomData['room']->type->name ?? 'Standard' }}</small>
                                        </div>
                                        @if($roomData['room']->facilities->count() > 0)
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-wifi text-muted me-2"></i>
                                            <small class="text-muted">
                                                Équipements: {{ $roomData['room']->facilities->take(2)->pluck('name')->implode(', ') }}
                                                @if($roomData['room']->facilities->count() > 2)
                                                    +{{ $roomData['room']->facilities->count() - 2 }}
                                                @endif
                                            </small>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('availability.room.detail', $roomData['room']->id) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-eye me-2"></i>
                                            Voir détails
                                        </a>
                                        <a href="{{ route('transaction.reservation.createIdentity', [
                                            'room_id' => $roomData['room']->id,
                                            'check_in' => $checkIn,
                                            'check_out' => $checkOut,
                                            'adults' => $adults,
                                            'children' => $children
                                        ]) }}" 
                                           class="btn btn-success">
                                            <i class="fas fa-book me-2"></i>
                                            Réserver maintenant
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

           <!-- Chambres non disponibles -->
            @if(count($unavailableRooms) > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-danger text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-times-circle me-2"></i>
                            <strong>Chambres non disponibles ({{ count($unavailableRooms) }})</strong>
                        </div>
                        <div class="badge bg-light text-danger">
                            Occupées ou indisponibles
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($unavailableRooms as $room)
                        <div class="col-md-6 mb-3">
                            <div class="card border-danger">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">
                                                Chambre {{ $room->number }}
                                            </h6>
                                            <span class="badge bg-danger">
                                                {{ $room->type->name ?? 'Standard' }}
                                            </span>
                                        </div>
                                        <div>
                                            <!-- LIEN VERS LA PAGE DES CONFLITS -->
                                            <a href="{{ route('availability.room.conflicts', [
                                                'room' => $room->id,
                                                'check_in' => $checkIn,
                                                'check_out' => $checkOut,
                                                'adults' => $adults,
                                                'children' => $children
                                            ]) }}" 
                                            class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                Voir conflits
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <!-- Afficher un aperçu des conflits si disponible -->
                                    @if(isset($roomConflicts[$room->id]) && count($roomConflicts[$room->id]) > 0)
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            {{ count($roomConflicts[$room->id]) }} réservation(s) en conflit
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Aucun résultat -->
            @if(count($availableRooms) == 0 && count($unavailableRooms) == 0)
            <div class="text-center py-5">
                <i class="fas fa-bed fa-4x text-muted mb-4"></i>
                <h4 class="text-dark mb-3">Aucune chambre trouvée</h4>
                <p class="text-muted mb-4">
                    Aucune chambre ne correspond à vos critères de recherche.<br>
                    Essayez de modifier vos dates ou le type de chambre.
                </p>
                <a href="{{ route('availability.search') }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>
                    Modifier la recherche
                </a>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .card-header.bg-success {
        background: linear-gradient(135deg, #198754, #157347) !important;
    }
    
    .card-header.bg-danger {
        background: linear-gradient(135deg, #dc3545, #c82333) !important;
    }
    
    .card.border-success:hover {
        box-shadow: 0 0.5rem 1rem rgba(25, 135, 84, 0.15);
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    
    .card.border-danger:hover {
        box-shadow: 0 0.5rem 1rem rgba(220, 53, 69, 0.15);
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    
    .availability-period {
        font-size: 0.9rem;
        padding: 5px 10px;
        border-radius: 20px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validation des dates
        const checkInInput = document.querySelector('input[name="check_in"]');
        const checkOutInput = document.querySelector('input[name="check_out"]');
        
        checkInInput.addEventListener('change', function() {
            const checkInDate = new Date(this.value);
            const nextDay = new Date(checkInDate);
            nextDay.setDate(nextDay.getDate() + 1);
            
            // Mettre à jour la date min du check-out
            checkOutInput.min = nextDay.toISOString().split('T')[0];
            
            // Si la date de check-out est avant la nouvelle date min
            if (checkOutInput.value && new Date(checkOutInput.value) <= checkInDate) {
                checkOutInput.value = nextDay.toISOString().split('T')[0];
            }
        });
        
        checkOutInput.addEventListener('change', function() {
            const checkOutDate = new Date(this.value);
            const checkInDate = new Date(checkInInput.value);
            
            if (checkOutDate <= checkInDate) {
                alert('La date de départ doit être après la date d\'arrivée');
                const nextDay = new Date(checkInDate);
                nextDay.setDate(nextDay.getDate() + 1);
                this.value = nextDay.toISOString().split('T')[0];
            }
        });
        
        // Initialiser les tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush