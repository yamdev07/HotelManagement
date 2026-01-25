@extends('template.master')

@section('title', 'Inventaire des chambres')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark">Inventaire des chambres</h1>
                    <p class="text-muted mb-0">Statut et occupation des chambres en temps réel</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('availability.calendar') }}" class="btn btn-outline-primary">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Calendrier
                    </a>
                    <a href="{{ route('availability.dashboard') }}" class="btn btn-outline-primary">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>
                        Imprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Chambres totales</h6>
                            <h3 class="fw-bold text-dark">{{ $stats['total_rooms'] }}</h3>
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
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-line fa-2x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Arrivées et départs du jour -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-sign-in-alt me-2"></i>
                            <strong>Arrivées du jour ({{ $todayArrivals->count() }})</strong>
                        </div>
                        <span class="badge bg-light text-primary">{{ now()->format('d/m/Y') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($todayArrivals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Chambre</th>
                                        <th>Client</th>
                                        <th>Arrivée</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todayArrivals as $arrival)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $arrival->room->number }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-title bg-light rounded-circle text-dark">
                                                        {{ substr($arrival->customer->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $arrival->customer->name }}</div>
                                                    <small class="text-muted">{{ $arrival->customer->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $arrival->check_in->format('H:i') }}</td>
                                        <td>
                                            @php
                                                // Vérifier si la route checkin.show existe
                                                try {
                                                    $checkinRoute = route('checkin.show', $arrival->id);
                                                } catch (\Exception $e) {
                                                    $checkinRoute = "/checkin/{$arrival->id}";
                                                }
                                            @endphp
                                            <a href="{{ $checkinRoute }}" 
                                               class="btn btn-sm btn-success">
                                                <i class="fas fa-door-open me-1"></i>
                                                Check-in
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                            <h6 class="text-dark">Aucune arrivée prévue aujourd'hui</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-sign-out-alt me-2"></i>
                            <strong>Départs du jour ({{ $todayDepartures->count() }})</strong>
                        </div>
                        <span class="badge bg-light text-warning">{{ now()->format('d/m/Y') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($todayDepartures->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Chambre</th>
                                        <th>Client</th>
                                        <th>Départ</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todayDepartures as $departure)
                                    <tr>
                                        <td>
                                            <span class="badge bg-warning">{{ $departure->room->number }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-title bg-light rounded-circle text-dark">
                                                        {{ substr($departure->customer->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $departure->customer->name }}</div>
                                                    <small class="text-muted">{{ $departure->customer->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $departure->check_out->format('H:i') }}</td>
                                        <td>
                                            <a href="{{ route('transaction.show', $departure->id) }}" 
                                               class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-receipt me-1"></i>
                                                Facture
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h6 class="text-dark">Aucun départ prévu aujourd'hui</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Inventaire par type de chambre -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-list-alt me-2"></i>
                            <strong>Inventaire détaillé par type de chambre</strong>
                        </div>
                        <div class="text-end">
                            <small class="text-light">Mis à jour: {{ now()->format('H:i:s') }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3">Type de chambre</th>
                                    <th class="py-3 text-center">Total</th>
                                    <th class="py-3 text-center">Disponibles</th>
                                    <th class="py-3 text-center">Occupées</th> <!-- CORRIGÉ: balise <th> fermée -->
                                    <th class="py-3 text-center">Nettoyage</th>
                                    <th class="py-3 text-center">Maintenance</th>
                                    <th class="py-3 text-center">Taux d'occupation</th>
                                    <th class="py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roomTypes as $type)
                                @php
                                    $totalRooms = $type->rooms->count();
                                    $occupiedRooms = $occupancyByType[$type->name]['occupied'] ?? 0;
                                    $percentage = $occupancyByType[$type->name]['percentage'] ?? 0;
                                    
                                    // Compter par statut
                                    $available = $type->rooms->where('room_status_id', 1)->count();
                                    $cleaning = $type->rooms->where('room_status_id', 3)->count(); // cleaning
                                    $maintenance = $type->rooms->where('room_status_id', 2)->count(); // maintenance
                                @endphp
                                <tr>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-bed fa-2x text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $type->name }}</div>
                                                <small class="text-muted">{{ number_format($type->price, 0, ',', ' ') }} FCFA/nuit</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-dark fs-6">{{ $totalRooms }}</span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-success fs-6">{{ $available }}</span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-warning fs-6">{{ $occupiedRooms }}</span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-info fs-6">{{ $cleaning }}</span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-danger fs-6">{{ $maintenance }}</span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                                <div class="progress-bar bg-{{ $percentage > 80 ? 'success' : ($percentage > 50 ? 'warning' : 'info') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $percentage }}%"
                                                     aria-valuenow="{{ $percentage }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100"></div>
                                            </div>
                                            <div class="fw-bold">{{ number_format($percentage, 1) }}%</div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <a href="{{ route('availability.search', ['room_type_id' => $type->id]) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-search me-1"></i>
                                            Voir
                                        </a>
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

    <!-- Chambres par statut -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-clipboard-check me-2"></i>
                    <strong>Chambres par statut</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($roomsByStatus as $statusId => $rooms)
                        @php
                            $status = $rooms->first()->roomStatus ?? null;
                            if (!$status) continue;
                            
                            $color = match($statusId) {
                                1 => 'success', // Available
                                2 => 'danger',  // Maintenance
                                3 => 'info',    // Cleaning
                                4 => 'warning', // Reserved
                                default => 'secondary'
                            };
                        @endphp
                        <div class="col-md-4 mb-3">
                            <div class="card border-{{ $color }}">
                                <div class="card-header bg-{{ $color }} text-white py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong>{{ $status->name }}</strong>
                                        <span class="badge bg-light text-{{ $color }}">{{ $rooms->count() }}</span>
                                    </div>
                                </div>
                                <div class="card-body p-2">
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($rooms->take(12) as $room)
                                        <a href="{{ route('availability.room.detail', $room->id) }}" 
                                           class="badge bg-light text-dark p-2 text-decoration-none"
                                           data-bs-toggle="tooltip" 
                                           title="{{ $room->type->name ?? 'Chambre' }}">
                                            {{ $room->number }}
                                            @if(in_array($statusId, [2, 3]))
                                                <i class="fas fa-{{ $statusId == 2 ? 'tools' : 'broom' }} ms-1"></i>
                                            @endif
                                        </a>
                                        @endforeach
                                        @if($rooms->count() > 12)
                                            <span class="badge bg-secondary p-2">
                                                +{{ $rooms->count() - 12 }}
                                            </span>
                                        @endif
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
</div>
@endsection

@push('styles')
<style>
    .avatar-sm {
        width: 36px;
        height: 36px;
    }
    
    .avatar-title {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1rem;
    }
    
    .card-header.bg-primary {
        background: linear-gradient(135deg, var(--bs-primary), #0a58ca) !important;
    }
    
    .card-header.bg-warning {
        background: linear-gradient(135deg, var(--bs-warning), #e0a800) !important;
    }
    
    .card-header.bg-dark {
        background: linear-gradient(135deg, var(--bs-dark), #1a1e21) !important;
    }
    
    .card-header.bg-info {
        background: linear-gradient(135deg, var(--bs-info), #0aa2c0) !important;
    }
    
    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
    }
    
    .progress-bar {
        border-radius: 10px;
    }
    
    .badge.fs-6 {
        font-size: 1rem !important;
        padding: 0.35em 0.65em;
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
        
        // Rafraîchissement automatique toutes les 60 secondes
        setTimeout(function() {
            window.location.reload();
        }, 60000); 
        
        // Message de mise à jour
        console.log('Inventaire mis à jour: {{ now()->format("H:i:s") }}');
    });
</script>
@endpush