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
                <div class="d-flex gap-2">
                    <a href="{{ route('availability.search') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour à la recherche
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-warning">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Détails de la recherche</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <small class="text-muted">Période</small>
                            <div class="fw-bold">{{ $nights }} nuit(s)</div>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Arrivée</small>
                            <div class="fw-bold">{{ \Carbon\Carbon::parse($checkIn)->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Départ</small>
                            <div class="fw-bold">{{ \Carbon\Carbon::parse($checkOut)->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Personnes</small>
                            <div class="fw-bold">{{ $adults + $children }}</div>
                        </div>
                    </div>
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Cette chambre n'est pas disponible pour les dates sélectionnées.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <div class="text-danger mb-3">
                        <i class="fas fa-ban fa-3x"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Indisponible</h5>
                    <p class="text-muted">
                        <strong>{{ $conflicts->count() }}</strong> réservation(s) en conflit
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des conflits -->
    @if($conflicts->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-calendar-times me-2"></i>
                        Réservations en conflit ({{ $conflicts->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Arrivée</th>
                                    <th>Départ</th>
                                    <th>Durée</th>
                                    <th>Statut</th>
                                    <th>N° Réservation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($conflicts as $reservation)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $reservation->customer->name ?? 'Client inconnu' }}</div>
                                        <small class="text-muted">{{ $reservation->customer->email ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        {{ $reservation->check_in->format('d/m/Y') }}
                                        <br>
                                        <small class="text-muted">14h00</small>
                                    </td>
                                    <td>
                                        {{ $reservation->check_out->format('d/m/Y') }}
                                        <br>
                                        <small class="text-muted">12h00</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $reservation->check_in->diffInDays($reservation->check_out) }} nuit(s)
                                        </span>
                                    </td>
                                    <td>
                                        @if($reservation->status == 'reservation')
                                            <span class="badge bg-warning">Réservation</span>
                                        @elseif($reservation->status == 'active')
                                            <span class="badge bg-success">En séjour</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $reservation->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <code>{{ $reservation->reservation_number }}</code>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Suggestions de dates alternatives -->
    @if($suggestedDates && $suggestedDates->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-calendar-check me-2"></i>
                        Dates alternatives suggérées
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        Voici d'autres périodes disponibles pour cette chambre :
                    </p>
                    <div class="row">
                        @foreach($suggestedDates as $suggestion)
                        <div class="col-md-4 mb-3">
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
            </div>
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Que souhaitez-vous faire ?</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-grid">
                                <a href="{{ route('availability.search') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-search me-2"></i>
                                    Nouvelle recherche
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-grid">
                                <a href="{{ route('availability.search', [
                                    'check_in' => $checkIn,
                                    'check_out' => $checkOut,
                                    'adults' => $adults,
                                    'children' => $children
                                ]) }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-bed me-2"></i>
                                    Voir autres chambres
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-grid">
                                <a href="{{ route('availability.calendar') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Voir calendrier
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card.border-warning {
        border-width: 2px;
    }
    .card.border-danger {
        border-width: 2px;
    }
    .card.border-success {
        border-width: 2px;
    }
</style>
@endpush