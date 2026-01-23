@extends('template.master')

@section('title', 'Dashboard disponibilité')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark">Dashboard disponibilité</h1>
                    <p class="text-muted mb-0">Vue d'ensemble des chambres et réservations</p>
                </div>
                <div class="d-flex gap-2">
                    <div class="text-end">
                        <div class="text-muted small">Mis à jour</div>
                        <div class="fw-bold">{{ now()->format('d/m/Y H:i:s') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Chambres totales</h6>
                            <h3 class="fw-bold text-dark">{{ $stats['total_rooms'] }}</h3>
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar bg-primary" style="width: 100%"></div>
                            </div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-bed fa-2x text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Chambres disponibles</h6>
                            <h3 class="fw-bold text-success">{{ $stats['available_rooms'] }}</h3>
                            <div class="progress" style="height: 5px;">
                                @php
                                    $availablePercent = $stats['total_rooms'] > 0 ? ($stats['available_rooms'] / $stats['total_rooms']) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-success" style="width: {{ $availablePercent }}%"></div>
                            </div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Chambres occupées</h6>
                            <h3 class="fw-bold text-warning">{{ $stats['occupied_rooms'] }}</h3>
                            <div class="progress" style="height: 5px;">
                                @php
                                    $occupiedPercent = $stats['total_rooms'] > 0 ? ($stats['occupied_rooms'] / $stats['total_rooms']) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-warning" style="width: {{ $occupiedPercent }}%"></div>
                            </div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Taux d'occupation</h6>
                            <h3 class="fw-bold text-info">{{ number_format($stats['occupancy_rate'], 1) }}%</h3>
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar bg-info" style="width: {{ $stats['occupancy_rate'] }}%"></div>
                            </div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-line fa-2x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chambres disponibles maintenant -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-bed me-2"></i>
                            <strong>Chambres disponibles maintenant ({{ $availableNow->count() }})</strong>
                        </div>
                        <span class="badge bg-light text-success">
                            {{ now()->format('H:i') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @if($availableNow->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Chambre</th>
                                        <th>Type</th>
                                        <th>Prix/nuit</th>
                                        <th>Capacité</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($availableNow as $room)
                                    <tr>
                                        <td>
                                            <span class="badge bg-success">{{ $room->number }}</span>
                                        </td>
                                        <td>{{ $room->type->name ?? 'Standard' }}</td>
                                        <td class="text-success fw-bold">
                                            {{ number_format($room->price, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td>
                                            <i class="fas fa-users text-muted me-1"></i>
                                            {{ $room->capacity }}
                                        </td>
                                        <td>
                                            <a href="{{ route('availability.room.detail', $room->id) }}" 
                                               class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-eye me-1"></i>
                                                Voir
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                            <h6 class="text-dark">Aucune chambre disponible actuellement</h6>
                            <p class="text-muted small">Toutes les chambres sont occupées ou en maintenance</p>
                        </div>
                    @endif
                    
                    @if($availableNow->count() > 0)
                    <div class="mt-3 text-center">
                        <a href="{{ route('availability.search') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-search me-2"></i>
                            Rechercher disponibilités
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Chambres en maintenance/nettoyage -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-tools me-2"></i>
                            <strong>Chambres en maintenance/nettoyage ({{ $unavailableRooms->count() }})</strong>
                        </div>
                        <span class="badge bg-light text-warning">
                            {{ now()->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @if($unavailableRooms->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Chambre</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th>Depuis</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($unavailableRooms as $room)
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $room->room_status_id == 2 ? 'danger' : 'info' }}">
                                                {{ $room->number }}
                                            </span>
                                        </td>
                                        <td>{{ $room->type->name ?? 'Standard' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $room->room_status_id == 2 ? 'danger' : 'info' }}">
                                                <i class="fas fa-{{ $room->room_status_id == 2 ? 'tools' : 'broom' }} me-1"></i>
                                                {{ $room->roomStatus->name }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($room->updated_at)
                                                {{ $room->updated_at->diffForHumans() }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('housekeeping.mark-cleaned', $room->id) }}" 
                                               class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-check me-1"></i>
                                                Nettoyée
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h6 class="text-dark">Aucune chambre en maintenance</h6>
                            <p class="text-muted small">Toutes les chambres sont opérationnelles</p>
                        </div>
                    @endif
                    
                    @if($unavailableRooms->count() > 0)
                    <div class="mt-3 text-center">
                        <a href="{{ route('housekeeping.index') }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-broom me-2"></i>
                            Gestion nettoyage
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Arrivées et départs des 7 prochains jours -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-sign-in-alt me-2"></i>
                            <strong>Arrivées (7 prochains jours)</strong>
                        </div>
                        <span class="badge bg-light text-primary">
                            {{ now()->format('d/m') }} → {{ now()->addDays(7)->format('d/m') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @if($upcomingArrivals->count() > 0)
                        @foreach($upcomingArrivals->groupBy(function($item) {
                            return \Carbon\Carbon::parse($item->check_in)->format('Y-m-d');
                        }) as $date => $arrivals)
                        <div class="mb-3">
                            <h6 class="fw-bold text-dark mb-2">
                                <i class="fas fa-calendar-day me-2"></i>
                                {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                <span class="badge bg-primary">{{ $arrivals->count() }}</span>
                            </h6>
                            @foreach($arrivals as $arrival)
                            <div class="card mb-2 border-start border-primary border-3">
                                <div class="card-body py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold">{{ $arrival->customer->name }}</div>
                                            <small class="text-muted">
                                                Chambre {{ $arrival->room->number }} • 
                                                Arrivée: {{ \Carbon\Carbon::parse($arrival->check_in)->format('H:i') }}
                                            </small>
                                        </div>
                                        <div>
                                            <span class="badge bg-{{ $arrival->status == 'reservation' ? 'info' : 'warning' }}">
                                                {{ $arrival->status_label }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                            <h6 class="text-dark">Aucune arrivée prévue</h6>
                            <p class="text-muted small">Aucun check-in prévu pour les 7 prochains jours</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-danger text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-sign-out-alt me-2"></i>
                            <strong>Départs (7 prochains jours)</strong>
                        </div>
                        <span class="badge bg-light text-danger">
                            {{ now()->format('d/m') }} → {{ now()->addDays(7)->format('d/m') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @if($upcomingDepartures->count() > 0)
                        @foreach($upcomingDepartures->groupBy(function($item) {
                            return \Carbon\Carbon::parse($item->check_out)->format('Y-m-d');
                        }) as $date => $departures)
                        <div class="mb-3">
                            <h6 class="fw-bold text-dark mb-2">
                                <i class="fas fa-calendar-day me-2"></i>
                                {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                <span class="badge bg-danger">{{ $departures->count() }}</span>
                            </h6>
                            @foreach($departures as $departure)
                            <div class="card mb-2 border-start border-danger border-3">
                                <div class="card-body py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold">{{ $departure->customer->name }}</div>
                                            <small class="text-muted">
                                                Chambre {{ $departure->room->number }} • 
                                                Départ: {{ \Carbon\Carbon::parse($departure->check_out)->format('H:i') }}
                                            </small>
                                        </div>
                                        <div>
                                            <span class="badge bg-warning">
                                                {{ $departure->status_label }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h6 class="text-dark">Aucun départ prévu</h6>
                            <p class="text-muted small">Aucun check-out prévu pour les 7 prochains jours</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Occupation par type de chambre -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-chart-pie me-2"></i>
                            <strong>Occupation par type de chambre</strong>
                        </div>
                        <div class="text-light">
                            <small>Taux d'occupation moyen: {{ 
                                number_format(collect($occupancyByType)->avg('percentage'), 1) 
                            }}%</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($occupancyByType as $type)
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-bold text-dark mb-0">{{ $type['type'] }}</h6>
                                        <span class="badge bg-info">{{ number_format($type['percentage'], 1) }}%</span>
                                    </div>
                                    
                                    <div class="progress mb-3" style="height: 20px;">
                                        <div class="progress-bar bg-{{ $type['percentage'] > 80 ? 'success' : ($type['percentage'] > 50 ? 'warning' : 'info') }}" 
                                             role="progressbar" 
                                             style="width: {{ $type['percentage'] }}%"
                                             aria-valuenow="{{ $type['percentage'] }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            {{ number_format($type['percentage'], 1) }}%
                                        </div>
                                    </div>
                                    
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="fw-bold text-dark fs-4">{{ $type['occupied'] }}</div>
                                            <small class="text-muted">Occupées</small>
                                        </div>
                                        <div class="col-6">
                                            <div class="fw-bold text-dark fs-4">{{ $type['total'] }}</div>
                                            <small class="text-muted">Total</small>
                                        </div>
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

    <!-- Quick actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-bolt me-2"></i>
                    <strong>Actions rapides</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <a href="{{ route('availability.calendar') }}" 
                               class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                <div>Calendrier</div>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('availability.search') }}" 
                               class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-search fa-2x mb-2"></i>
                                <div>Rechercher</div>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('availability.inventory') }}" 
                               class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                                <div>Inventaire</div>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('transaction.reservation.createIdentity') }}" 
                               class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                <div>Nouvelle réservation</div>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('checkin.index') }}" 
                               class="btn btn-outline-danger w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-door-open fa-2x mb-2"></i>
                                <div>Check-in</div>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('housekeeping.index') }}" 
                               class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-broom fa-2x mb-2"></i>
                                <div>Nettoyage</div>
                            </a>
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
    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
    }
    
    .progress-bar {
        border-radius: 10px;
    }
    
    .card-header.bg-primary,
    .card-header.bg-success,
    .card-header.bg-warning,
    .card-header.bg-danger,
    .card-header.bg-info,
    .card-header.bg-dark {
        background: linear-gradient(135deg, var(--bs-primary), #0a58ca) !important;
    }
    
    .card-header.bg-success {
        background: linear-gradient(135deg, var(--bs-success), #198754) !important;
    }
    
    .card-header.bg-warning {
        background: linear-gradient(135deg, var(--bs-warning), #e0a800) !important;
    }
    
    .card-header.bg-danger {
        background: linear-gradient(135deg, var(--bs-danger), #c82333) !important;
    }
    
    .card-header.bg-info {
        background: linear-gradient(135deg, var(--bs-info), #0aa2c0) !important;
    }
    
    .card-header.bg-dark {
        background: linear-gradient(135deg, var(--bs-dark), #1a1e21) !important;
    }
    
    .border-start.border-primary {
        border-left-width: 4px !important;
    }
    
    .border-start.border-danger {
        border-left-width: 4px !important;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Rafraîchissement automatique toutes les 30 secondes
        setInterval(function() {
            // Mettre à jour seulement certaines parties
            fetch('{{ route("availability.dashboard") }}?partial=true')
                .then(response => response.text())
                .then(html => {
                    // Mettre à jour le timestamp
                    document.querySelector('.text-end .fw-bold').textContent = 
                        new Date().toLocaleString('fr-FR');
                })
                .catch(error => console.error('Erreur rafraîchissement:', error));
        }, 30000);
        
        // Afficher la dernière mise à jour
        console.log('Dashboard mis à jour: {{ now()->format("H:i:s") }}');
    });
</script>
@endpush