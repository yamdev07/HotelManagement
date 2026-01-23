@extends('template.master')

@section('title', 'Chambre ' . $room->number)

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark">Chambre {{ $room->number }}</h1>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-{{ $room->room_status_id == 1 ? 'success' : ($room->room_status_id == 2 ? 'danger' : 'warning') }}">
                            {{ $room->roomStatus->name ?? 'Statut inconnu' }}
                        </span>
                        <span class="text-muted">
                            <i class="fas fa-bed me-1"></i>
                            {{ $room->type->name ?? 'Type inconnu' }}
                        </span>
                        <span class="text-muted">
                            <i class="fas fa-users me-1"></i>
                            Capacité: {{ $room->capacity }} personnes
                        </span>
                    </div>
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
                    <a href="{{ route('room.edit', $room->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>
                        Modifier
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Colonne gauche: Informations -->
        <div class="col-md-4">
            <!-- Informations générales -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Informations générales</strong>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Numéro</label>
                                <div class="fw-bold fs-5">{{ $room->number }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Étage</label>
                                <div class="fw-bold fs-5">{{ $room->floor ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Type</label>
                        <div class="fw-bold">{{ $room->type->name ?? 'Type inconnu' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Prix par nuit</label>
                        <div class="fw-bold text-success fs-4">
                            {{ number_format($room->price, 0, ',', ' ') }} FCFA
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Capacité</label>
                                <div class="fw-bold">
                                    <i class="fas fa-users me-1"></i>
                                    {{ $room->capacity }} personnes
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Surface</label>
                                <div class="fw-bold">
                                    {{ $room->size ?? 'N/A' }} m²
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Statut</label>
                        <div>
                            <span class="badge bg-{{ $room->room_status_id == 1 ? 'success' : ($room->room_status_id == 2 ? 'danger' : 'warning') }} p-2 fs-6">
                                <i class="fas fa-{{ $room->room_status_id == 1 ? 'check' : ($room->room_status_id == 2 ? 'tools' : 'broom') }} me-1"></i>
                                {{ $room->roomStatus->name ?? 'Statut inconnu' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Dernière mise à jour</label>
                        <div class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            {{ $room->updated_at ? $room->updated_at->format('d/m/Y H:i') : 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Équipements -->
            @if($room->facilities->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-wifi me-2"></i>
                    <strong>Équipements et services</strong>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($room->facilities as $facility)
                        <span class="badge bg-light text-dark p-2">
                            <i class="fas fa-{{ $facility->icon ?? 'check' }} me-1"></i>
                            {{ $facility->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Statistiques -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-chart-bar me-2"></i>
                    <strong>Statistiques (30 jours)</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="fw-bold text-dark fs-4">{{ number_format($roomStats['occupancy_rate_30d'], 1) }}%</div>
                                <div class="text-muted small">Taux d'occupation</div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="fw-bold text-dark fs-4">{{ number_format($roomStats['avg_stay_duration'], 1) }}</div>
                                <div class="text-muted small">Nuits moyennes</div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="text-center">
                                <div class="fw-bold text-success fs-4">
                                    {{ number_format($roomStats['total_revenue_30d'], 0, ',', ' ') }} FCFA
                                </div>
                                <div class="text-muted small">Revenu total</div>
                            </div>
                        </div>
                        @if($roomStats['next_available'])
                        <div class="col-12">
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-calendar-check me-2"></i>
                                <strong>Prochaine disponibilité:</strong>
                                {{ $roomStats['next_available']->format('d/m/Y') }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite: Réservations et calendrier -->
        <div class="col-md-8">
            <!-- Client actuel -->
            @if($currentTransaction)
            <div class="card border-0 shadow-sm mb-4 border-warning">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-user-check me-2"></i>
                            <strong>Client actuel</strong>
                        </div>
                        <span class="badge bg-light text-warning">
                            {{ $currentTransaction->check_in->format('d/m/Y') }} - {{ $currentTransaction->check_out->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg me-3">
                                    <div class="avatar-title bg-light rounded-circle text-dark fs-3">
                                        {{ substr($currentTransaction->customer->name, 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-1">{{ $currentTransaction->customer->name }}</h5>
                                    <div class="text-muted mb-2">
                                        <i class="fas fa-envelope me-1"></i>
                                        {{ $currentTransaction->customer->email }}
                                        <i class="fas fa-phone ms-3 me-1"></i>
                                        {{ $currentTransaction->customer->phone }}
                                    </div>
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-info">
                                            {{ $currentTransaction->nights }} nuit(s)
                                        </span>
                                        <span class="badge bg-success">
                                            {{ number_format($currentTransaction->total_price, 0, ',', ' ') }} FCFA
                                        </span>
                                        <span class="badge bg-{{ $currentTransaction->status == 'active' ? 'warning' : 'primary' }}">
                                            {{ $currentTransaction->status_label }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-center justify-content-end">
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('transaction.show', $currentTransaction->id) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-2"></i>
                                    Détails
                                </a>
                                @if($currentTransaction->status == 'active')
                                <a href="{{ route('transaction.check-out', $currentTransaction->id) }}" 
                                   class="btn btn-warning">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    Check-out
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Calendrier des 30 prochains jours -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-calendar-alt me-2"></i>
                            <strong>Disponibilité (30 prochains jours)</strong>
                        </div>
                        <small class="text-light">{{ now()->format('d/m/Y') }} → {{ now()->addDays(30)->format('d/m/Y') }}</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="legend-square available me-2"></div>
                            <small class="text-muted">Disponible</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="legend-square occupied me-2"></div>
                            <small class="text-muted">Occupée</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="legend-square today me-2"></div>
                            <small class="text-muted">Aujourd'hui</small>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr class="bg-light">
                                    @for($i = 0; $i < 7; $i++)
                                        <th class="text-center py-2">{{ now()->addDays($i)->format('D') }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @for($week = 0; $week < 5; $week++)
                                    <tr>
                                        @for($day = 0; $day < 7; $day++)
                                            @php
                                                $dateIndex = $week * 7 + $day;
                                                if ($dateIndex >= 30) break;
                                                
                                                $date = now()->addDays($dateIndex);
                                                $dateKey = $date->format('Y-m-d');
                                                $availability = $calendar[$dateKey] ?? null;
                                            @endphp
                                            <td class="text-center py-3 
                                                {{ $availability['css_class'] ?? 'available' }} 
                                                {{ $date->isToday() ? 'today-cell' : '' }}"
                                                style="width: 14.28%;">
                                                <div class="fw-bold">{{ $date->format('d') }}</div>
                                                <div class="small text-muted">{{ $date->format('M') }}</div>
                                                @if($availability && $availability['occupied'])
                                                    <i class="fas fa-user text-danger mt-1"></i>
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-bolt me-2"></i>
                    <strong>Actions rapides</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('availability.search', [
                                'room_type_id' => $room->type_id,
                                'check_in' => now()->format('Y-m-d'),
                                'check_out' => now()->addDays(1)->format('Y-m-d')
                            ]) }}" 
                               class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-search fa-2x mb-2"></i>
                                <div>Rechercher</div>
                                <small class="text-muted">Voir disponibilités</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            @if($room->room_status_id == 1)
                                <a href="{{ route('transaction.reservation.createIdentity', ['room_id' => $room->id]) }}" 
                                   class="btn btn-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-book fa-2x mb-2"></i>
                                    <div>Réserver</div>
                                    <small class="text-light">Réserver cette chambre</small>
                                </a>
                            @else
                                <button class="btn btn-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" disabled>
                                    <i class="fas fa-ban fa-2x mb-2"></i>
                                    <div>Indisponible</div>
                                    <small>{{ $room->roomStatus->name ?? 'Non disponible' }}</small>
                                </button>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('housekeeping.mark-maintenance', $room->id) }}" 
                               class="btn btn-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-tools fa-2x mb-2"></i>
                                <div>Maintenance</div>
                                <small class="text-muted">Marquer en maintenance</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('housekeeping.mark-cleaned', $room->id) }}" 
                               class="btn btn-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-broom fa-2x mb-2"></i>
                                <div>Nettoyée</div>
                                <small class="text-muted">Marquer comme nettoyée</small>
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
    .avatar-lg {
        width: 60px;
        height: 60px;
    }
    
    .avatar-title {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    .legend-square {
        width: 15px;
        height: 15px;
        border-radius: 3px;
        display: inline-block;
    }
    
    .legend-square.available {
        background-color: #d1e7dd;
        border: 1px solid #badbcc;
    }
    
    .legend-square.occupied {
        background-color: #f8d7da;
        border: 1px solid #f5c2c7;
    }
    
    .legend-square.today {
        background-color: #fff3cd;
        border: 1px solid #ffecb5;
    }
    
    .today-cell {
        background-color: #fff3cd !important;
        font-weight: bold;
    }
    
    .card-header.bg-primary,
    .card-header.bg-success,
    .card-header.bg-info,
    .card-header.bg-warning,
    .card-header.bg-dark {
        background: linear-gradient(135deg, var(--bs-primary), #0a58ca) !important;
    }
    
    .card-header.bg-success {
        background: linear-gradient(135deg, var(--bs-success), #198754) !important;
    }
    
    .card-header.bg-info {
        background: linear-gradient(135deg, var(--bs-info), #0aa2c0) !important;
    }
    
    .card-header.bg-warning {
        background: linear-gradient(135deg, var(--bs-warning), #e0a800) !important;
    }
    
    .card-header.bg-dark {
        background: linear-gradient(135deg, var(--bs-dark), #1a1e21) !important;
    }
</style>
@endpush