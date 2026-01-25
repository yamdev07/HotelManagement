@extends('template.master')

@section('title', 'Conflits de réservation')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark">Conflits de réservation</h1>
                    <p class="text-muted mb-0">Détails des réservations en conflit pour cette chambre</p>
                </div>
                <div>
                    <a href="{{ route('availability.search') }}?check_in={{ $checkIn }}&check_out={{ $checkOut }}&adults={{ $adults }}&children={{ $children }}" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour à la recherche
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations sur la chambre et la période -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold text-primary mb-3">
                        <i class="fas fa-bed me-2"></i>
                        Informations chambre
                    </h6>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-door-closed fs-4"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="fw-bold mb-0">Chambre {{ $room->number }}</h5>
                            <span class="badge bg-primary">{{ $roomType }}</span>
                        </div>
                    </div>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-users text-muted me-2"></i>
                            <small class="text-muted">Capacité: {{ $roomCapacity }} personnes</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-tag text-muted me-2"></i>
                            <small class="text-muted">Prix/nuit: {{ $formattedRoomPrice }}</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-muted me-2"></i>
                            <small class="text-muted">Statut: {{ $roomStatus }}</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold text-primary mb-3">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Période recherchée
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-sign-in-alt fa-2x me-3 text-info"></i>
                                    <div>
                                        <small class="text-muted d-block">Arrivée</small>
                                        <strong>{{ \Carbon\Carbon::parse($checkIn)->format('d/m/Y') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-warning">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-sign-out-alt fa-2x me-3 text-warning"></i>
                                    <div>
                                        <small class="text-muted d-block">Départ</small>
                                        <strong>{{ \Carbon\Carbon::parse($checkOut)->format('d/m/Y') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <div class="text-muted mb-1">Nuits</div>
                                    <div class="fw-bold fs-4">{{ $nights }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <div class="text-muted mb-1">Prix total</div>
                                    <div class="fw-bold fs-4 text-success">{{ $formattedSearchPrice }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <div class="text-muted mb-1">Personnes</div>
                                    <div class="fw-bold fs-4">{{ $totalGuests }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Résumé des conflits -->
    <div class="row mb-4">
        <div class="col-12">
            @if($conflicts->count() > 0)
            <div class="card border-0 shadow-sm border-danger">
                <div class="card-header bg-danger text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Réservations en conflit ({{ $conflicts->count() }})</strong>
                        </div>
                        <div class="badge bg-white text-danger">
                            {{ $overlapPercentage }}% de chevauchement
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle fa-2x me-3 mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-2">Attention !</h6>
                                <p class="mb-2">
                                    Cette chambre n'est pas disponible pour la période demandée 
                                    car elle est déjà réservée pendant {{ $totalOverlapDays }} 
                                    jour(s) sur les {{ $nights }} nuit(s) recherchées.
                                </p>
                                <p class="mb-0">
                                    <strong>Nuits disponibles:</strong> {{ $availableNights }} / {{ $nights }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des réservations en conflit -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Client</th>
                                    <th>Arrivée</th>
                                    <th>Départ</th>
                                    <th>Nuits</th>
                                    <th>Statut</th>
                                    <th>Chevauchement</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($conflictAnalysis as $conflict)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 35px; height: 35px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="ms-3">
                                                <div class="fw-bold">{{ $conflict['customer_name'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-nowrap">
                                            {{ \Carbon\Carbon::parse($conflict['transaction']->check_in)->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-nowrap">
                                            {{ \Carbon\Carbon::parse($conflict['transaction']->check_out)->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $conflict['transaction']->check_in->diffInDays($conflict['transaction']->check_out) }} nuit(s)
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $conflict['status_color'] }}">
                                            {{ $conflict['status_label'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            @php
                                                $percentage = ($conflict['overlap_days'] / $nights) * 100;
                                            @endphp
                                            <div class="progress-bar bg-danger" 
                                                 role="progressbar" 
                                                 style="width: {{ $percentage }}%"
                                                 aria-valuenow="{{ $percentage }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                {{ $conflict['overlap_days'] }} jour(s)
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $conflict['overlap_period'] }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('transaction.show', $conflict['transaction']->id) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>
                                            Voir réservation
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="card border-0 shadow-sm border-success">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Aucun conflit détecté !</strong>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
                        <h4 class="text-dark mb-3">Chambre disponible</h4>
                        <p class="text-muted mb-4">
                            Cette chambre est disponible pour la période demandée.
                        </p>
                        <a href="{{ route('transaction.reservation.createIdentity', [
                            'room_id' => $room->id,
                            'check_in' => $checkIn,
                            'check_out' => $checkOut,
                            'adults' => $adults,
                            'children' => $children
                        ]) }}" 
                           class="btn btn-success btn-lg">
                            <i class="fas fa-book me-2"></i>
                            Réserver maintenant
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Suggestions et alternatives -->
    @if($conflicts->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Suggestions</strong>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="fas fa-calendar-plus me-2"></i>
                                        Changer les dates
                                    </h6>
                                    <p class="text-muted mb-3">
                                        Essayez de modifier vos dates pour éviter les périodes de chevauchement.
                                    </p>
                                    <a href="{{ route('availability.search') }}?check_in={{ $checkIn }}&check_out={{ $checkOut }}&adults={{ $adults }}&children={{ $children }}" 
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-edit me-2"></i>
                                        Modifier la recherche
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-info">
                                <div class="card-body">
                                    <h6 class="fw-bold text-info mb-3">
                                        <i class="fas fa-exchange-alt me-2"></i>
                                        Changer de chambre
                                    </h6>
                                    <p class="text-muted mb-3">
                                        Consultez les autres chambres disponibles pour les mêmes dates.
                                    </p>
                                    <a href="{{ route('availability.search') }}" 
                                       class="btn btn-outline-info">
                                        <i class="fas fa-search me-2"></i>
                                        Rechercher d'autres chambres
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .card.border-danger .card-header {
        background: linear-gradient(135deg, #dc3545, #c82333) !important;
    }
    
    .card.border-success .card-header {
        background: linear-gradient(135deg, #198754, #157347) !important;
    }
    
    .progress {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .progress-bar {
        border-radius: 10px;
        transition: width 0.6s ease;
    }
    
    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }
    
    .table th {
        border-top: none;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #495057;
    }
    
    .table td {
        vertical-align: middle;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des barres de progression
        const progressBars = document.querySelectorAll('.progress-bar');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 300);
        });
        
        // Confirmation avant de réserver
        const reserveButton = document.querySelector('a[href*="reservation.createIdentity"]');
        if (reserveButton) {
            reserveButton.addEventListener('click', function(e) {
                if (!confirm('Êtes-vous sûr de vouloir réserver cette chambre pour cette période ?')) {
                    e.preventDefault();
                }
            });
        }
    });
</script>
@endpush