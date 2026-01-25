{{-- resources/views/availability/conflicts.blade.php --}}
@extends('template.master')

@section('title', 'Conflits de réservation')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Conflits de réservation
                    </h1>
                    <p class="text-muted mb-0">
                        Chambre {{ $room->number }} - {{ $room->type->name ?? 'Standard' }}
                    </p>
                </div>
                <a href="{{ route('availability.search') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour à la recherche
                </a>
            </div>
        </div>
    </div>

    <!-- Informations de la recherche -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Votre recherche</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <small class="text-muted">Arrivée</small>
                            <div class="fw-bold">{{ \Carbon\Carbon::parse($checkIn)->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Départ</small>
                            <div class="fw-bold">{{ \Carbon\Carbon::parse($checkOut)->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted">Durée</small>
                            <div class="fw-bold">{{ $nights }} nuit(s)</div>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted">Adultes</small>
                            <div class="fw-bold">{{ $adults }}</div>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted">Enfants</small>
                            <div class="fw-bold">{{ $children }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="fas fa-ban fa-2x"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Chambre indisponible</h5>
                    <p class="text-muted mb-0">
                        Cette chambre est réservée pour les dates sélectionnées
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Conflits de réservation -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-calendar-times me-2"></i>
                            <strong>Réservations en conflit ({{ $conflicts->count() }})</strong>
                        </div>
                        <span class="badge bg-danger">
                            {{ $conflicts->count() }} conflit(s)
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @if($conflicts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Arrivée</th>
                                    <th>Départ</th>
                                    <th>Durée</th>
                                    <th>Statut</th>
                                    <th>Numéro de réservation</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($conflicts as $reservation)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $reservation->customer->full_name ?? 'Client inconnu' }}</div>
                                        <small class="text-muted">
                                            {{ $reservation->customer->email ?? 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">
                                            {{ $reservation->check_in->format('d/m/Y') }}
                                        </div>
                                        <small class="text-muted">14h00</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">
                                            {{ $reservation->check_out->format('d/m/Y') }}
                                        </div>
                                        <small class="text-muted">12h00</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $reservation->check_in->diffInDays($reservation->check_out) }} nuit(s)
                                        </span>
                                    </td>
                                    <td>
                                        @if($reservation->status == 'confirmed')
                                            <span class="badge bg-success">Confirmée</span>
                                        @elseif($reservation->status == 'checked_in')
                                            <span class="badge bg-primary">En séjour</span>
                                        @elseif($reservation->status == 'pending')
                                            <span class="badge bg-warning">En attente</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $reservation->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <code>{{ $reservation->reservation_number }}</code>
                                    </td>
                                    <td>
                                        <a href="{{ route('reservations.show', $reservation->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           target="_blank">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Visualisation du calendrier -->
                    <div class="mt-4">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Visualisation des conflits
                        </h5>
                        <div class="timeline-container">
                            <div class="timeline-header">
                                <div class="timeline-dates">
                                    @php
                                        $startDate = min($conflicts->min('check_in'), \Carbon\Carbon::parse($checkIn));
                                        $endDate = max($conflicts->max('check_out'), \Carbon\Carbon::parse($checkOut));
                                        $totalDays = $startDate->diffInDays($endDate);
                                    @endphp
                                    
                                    @for($i = 0; $i <= min($totalDays, 30); $i++)
                                        @php $currentDate = $startDate->copy()->addDays($i); @endphp
                                        <div class="timeline-day {{ $currentDate->isToday() ? 'today' : '' }}">
                                            <small>{{ $currentDate->format('d/m') }}</small>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            
                            <div class="timeline-items">
                                <!-- Votre période recherchée -->
                                <div class="timeline-item search-period">
                                    <div class="timeline-label">Votre recherche</div>
                                    <div class="timeline-bar" 
                                         style="width: {{ $nights * 20 }}px; margin-left: {{ $startDate->diffInDays(\Carbon\Carbon::parse($checkIn)) * 20 }}px;">
                                        <span class="timeline-text">{{ $nights }} nuit(s)</span>
                                    </div>
                                </div>
                                
                                <!-- Périodes de réservation existantes -->
                                @foreach($conflicts as $index => $reservation)
                                <div class="timeline-item">
                                    <div class="timeline-label">
                                        Rés. {{ $reservation->reservation_number }}
                                    </div>
                                    <div class="timeline-bar conflict-period" 
                                         style="width: {{ $reservation->check_in->diffInDays($reservation->check_out) * 20 }}px; margin-left: {{ $startDate->diffInDays($reservation->check_in) * 20 }}px;">
                                        <span class="timeline-text">
                                            {{ $reservation->check_in->diffInDays($reservation->check_out) }} nuit(s)
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h4 class="text-dark mb-3">Aucun conflit détecté</h4>
                        <p class="text-muted mb-4">
                            Aucune réservation en conflit n'a été trouvée pour cette chambre.
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Alternatives et suggestions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-lightbulb me-2 text-primary"></i>
                        Suggestions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Option 1 : Modifier vos dates</h6>
                            <p class="text-muted">
                                Essayez avec des dates différentes pour éviter les conflits.
                            </p>
                            <a href="{{ route('availability.search') }}" class="btn btn-outline-primary">
                                <i class="fas fa-calendar-edit me-2"></i>
                                Modifier la recherche
                            </a>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Option 2 : Choisir une autre chambre</h6>
                            <p class="text-muted">
                                Consultez les autres chambres disponibles pour vos dates.
                            </p>
                            <a href="{{ route('availability.search') }}" class="btn btn-success">
                                <i class="fas fa-bed me-2"></i>
                                Voir les chambres disponibles
                            </a>
                        </div>
                    </div>
                    
                    <!-- Dates alternatives suggérées -->
                    @if($suggestedDates && count($suggestedDates) > 0)
                    <div class="mt-4">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-calendar-check me-2 text-success"></i>
                            Dates alternatives suggérées
                        </h6>
                        <div class="row">
                            @foreach($suggestedDates->take(3) as $suggestion)
                            <div class="col-md-4 mb-2">
                                <div class="card border-success border-2">
                                    <div class="card-body text-center">
                                        <small class="text-muted d-block">Disponible</small>
                                        <div class="fw-bold">
                                            {{ \Carbon\Carbon::parse($suggestion['check_in'])->format('d/m/Y') }}
                                            <i class="fas fa-arrow-right mx-2"></i>
                                            {{ \Carbon\Carbon::parse($suggestion['check_out'])->format('d/m/Y') }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $suggestion['nights'] }} nuit(s)
                                        </small>
                                        <div class="mt-2">
                                            <a href="{{ route('availability.search', [
                                                'check_in' => $suggestion['check_in'],
                                                'check_out' => $suggestion['check_out'],
                                                'adults' => $adults,
                                                'children' => $children,
                                                'room_type_id' => $room->type_id
                                            ]) }}" 
                                               class="btn btn-sm btn-success">
                                                <i class="fas fa-check me-1"></i>
                                                Sélectionner
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline-container {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        border: 1px solid #dee2e6;
    }
    
    .timeline-header {
        margin-bottom: 20px;
    }
    
    .timeline-dates {
        display: flex;
        overflow-x: auto;
        padding-bottom: 10px;
    }
    
    .timeline-day {
        min-width: 20px;
        text-align: center;
        padding: 5px;
        border-right: 1px solid #dee2e6;
        font-size: 11px;
    }
    
    .timeline-day.today {
        background-color: #007bff;
        color: white;
        border-radius: 3px;
    }
    
    .timeline-items {
        position: relative;
        min-height: 100px;
    }
    
    .timeline-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        height: 30px;
    }
    
    .timeline-label {
        width: 150px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .timeline-bar {
        height: 20px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 11px;
        font-weight: bold;
    }
    
    .timeline-bar.search-period {
        background-color: #007bff;
    }
    
    .timeline-bar.conflict-period {
        background-color: #dc3545;
    }
    
    .timeline-text {
        white-space: nowrap;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush