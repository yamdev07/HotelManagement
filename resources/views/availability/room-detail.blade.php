@extends('template.master')

@section('title', 'Chambre ' . $room->number)

@section('content')
@php
    // Assurez-vous que $roomStats contient toutes les clés nécessaires
    $roomStats = array_merge([
        'total_transactions' => 0,
        'total_revenue' => 0,
        'total_revenue_30d' => 0,
        'avg_stay_duration' => 0,
        'avg_daily_rate' => $room->price ?? 0,
        'occupancy_rate_30d' => 0,
        'next_available' => null,
        'formatted_next_available' => 'Immédiate',
        'last_30_days_revenue' => 0
    ], $roomStats ?? []);
    
    // S'assurer que Carbon utilise le français
    \Carbon\Carbon::setLocale('fr');
    
    // Vérifier le rôle de l'utilisateur pour les permissions
    $user = auth()->user();
    $canCheckOut = in_array($user->role ?? '', ['Super', 'Admin', 'Reception']);
@endphp

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
                    <a href="{{ route('availability.calendar') }}?room_number={{ $room->number }}" class="btn btn-outline-primary">
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
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="fw-bold text-dark fs-4">{{ $roomStats['total_transactions'] }}</div>
                                <div class="text-muted small">Réservations</div>
                            </div>
                        </div>
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
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="fw-bold text-dark fs-4">{{ number_format($roomStats['avg_daily_rate'], 0, ',', ' ') }} FCFA</div>
                                <div class="text-muted small">Prix moyen/jour</div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="text-center">
                                <div class="fw-bold text-success fs-4">
                                    {{ number_format($roomStats['total_revenue_30d'], 0, ',', ' ') }} FCFA
                                </div>
                                <div class="text-muted small">Revenu total (30j)</div>
                            </div>
                        </div>
                        
                        <!-- Prochaine disponibilité -->
                        @if($roomStats['next_available'] && $roomStats['next_available'] instanceof \Carbon\Carbon)
                        <div class="col-12">
                            <div class="alert alert-warning p-2 mb-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-check me-2"></i>
                                    <div>
                                        <strong>Prochaine disponibilité :</strong> 
                                        {{ $roomStats['next_available']->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif(isset($roomStats['formatted_next_available']) && $roomStats['formatted_next_available'] != 'Immédiate')
                        <div class="col-12">
                            <div class="alert alert-success p-2 mb-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <div>
                                        <strong>Disponible :</strong> Immédiatement
                                    </div>
                                </div>
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
                                        {{ substr($currentTransaction->customer->name ?? 'C', 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-1">{{ $currentTransaction->customer->name ?? 'Client inconnu' }}</h5>
                                    <div class="text-muted mb-2">
                                        @if($currentTransaction->customer->email ?? false)
                                        <i class="fas fa-envelope me-1"></i>
                                        {{ $currentTransaction->customer->email }}
                                        @endif
                                        @if($currentTransaction->customer->phone ?? false)
                                        <i class="fas fa-phone ms-3 me-1"></i>
                                        {{ $currentTransaction->customer->phone }}
                                        @endif
                                    </div>
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-info">
                                            {{ $currentTransaction->nights ?? 1 }} nuit(s)
                                        </span>
                                        <span class="badge bg-success">
                                            {{ number_format($currentTransaction->total_price ?? 0, 0, ',', ' ') }} FCFA
                                        </span>
                                        <span class="badge bg-{{ ($currentTransaction->status ?? '') == 'active' ? 'warning' : 'primary' }}">
                                            {{ $currentTransaction->status_label ?? 'Réservation' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-center justify-content-end">
                            <div class="d-flex flex-column gap-2">
                                <!-- Bouton Détails -->
                                <a href="{{ route('transaction.show', ['transaction' => $currentTransaction->id]) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-2"></i>
                                    Détails
                                </a>
                                
                                <!-- Bouton Check-out -->
                                @if(($currentTransaction->status ?? '') == 'active')
                                    @if($canCheckOut)
                                    <a href="{{ route('transaction.check-out', ['transaction' => $currentTransaction->id]) }}" 
                                       class="btn btn-warning"
                                       onclick="return confirm('Êtes-vous sûr de vouloir faire le check-out de ce client ?');">
                                        <i class="fas fa-sign-out-alt me-2"></i>
                                        Check-out
                                    </a>
                                    @else
                                    <button class="btn btn-warning" disabled
                                            title="Seul le personnel de réception peut faire le check-out">
                                        <i class="fas fa-sign-out-alt me-2"></i>
                                        Check-out
                                    </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <!-- Aucun client actuel -->
            <div class="card border-0 shadow-sm mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-door-open me-2"></i>
                            <strong>Chambre disponible</strong>
                        </div>
                        <span class="badge bg-light text-success">
                            {{ now()->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5 class="text-dark">Chambre libre</h5>
                        <p class="text-muted">Cette chambre est actuellement disponible pour une nouvelle réservation.</p>
                        @if($room->room_status_id == 1)
                        <a href="{{ route('transaction.reservation.createIdentity') }}?room_id={{ $room->id }}" 
                           class="btn btn-success">
                            <i class="fas fa-plus-circle me-2"></i>
                            Créer une réservation
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- CALENDRIER DES 30 PROCHAINS JOURS -->
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
                    <!-- Légende améliorée -->
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <div class="d-flex align-items-center">
                            <div class="legend-square available me-2"></div>
                            <small class="text-muted">Disponible (cliquez pour réserver)</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="legend-square occupied me-2"></div>
                            <small class="text-muted">Occupée (cliquez pour voir)</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="legend-square unavailable me-2"></div>
                            <small class="text-muted">Indisponible (maintenance/nettoyage)</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="legend-square today me-2"></div>
                            <small class="text-muted">Aujourd'hui</small>
                        </div>
                    </div>
                    
                    <!-- Calendrier amélioré -->
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 availability-mini-calendar">
                            <thead>
                                <tr class="bg-light">
                                    @for($i = 0; $i < 7; $i++)
                                        @php
                                            $day = now()->addDays($i);
                                        @endphp
                                        <th class="text-center py-2">
                                            <div class="fw-bold">{{ $day->isoFormat('dd') }}</div>
                                            <div class="small text-muted">{{ $day->format('d') }}</div>
                                        </th>
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
                                                
                                                // Vérifier correctement l'occupation
                                                $isOccupied = false;
                                                $reservationInfo = '';
                                                
                                                if ($availability && isset($availability['occupied'])) {
                                                    $isOccupied = $availability['occupied'];
                                                    if (isset($availability['reservation_count']) && $availability['reservation_count'] > 0) {
                                                        $reservationInfo = $availability['reservation_count'] . ' réservation(s)';
                                                    }
                                                }
                                                
                                                // Déterminer la classe CSS
                                                $cssClass = 'available';
                                                $icon = 'fas fa-check text-success';
                                                $tooltipText = 'Disponible - ' . number_format($room->price, 0, ',', ' ') . ' FCFA/nuit';
                                                
                                                if ($isOccupied) {
                                                    $cssClass = 'occupied';
                                                    $icon = 'fas fa-user text-danger';
                                                    $tooltipText = 'Occupée';
                                                    if ($reservationInfo) {
                                                        $tooltipText .= ' - ' . $reservationInfo;
                                                    }
                                                } elseif ($room->room_status_id != 1) {
                                                    $cssClass = 'unavailable';
                                                    $icon = 'fas fa-times text-secondary';
                                                    $tooltipText = 'Indisponible - ' . ($room->roomStatus->name ?? 'Maintenance');
                                                }
                                                
                                                $isToday = $date->isToday();
                                                if ($isToday) {
                                                    $cssClass .= ' today-cell';
                                                    $tooltipText .= ' - Aujourd\'hui';
                                                }
                                            @endphp
                                            <td class="text-center py-3 calendar-day {{ $cssClass }}"
                                                style="cursor: pointer;"
                                                data-date="{{ $dateKey }}"
                                                data-is-occupied="{{ $isOccupied ? 'true' : 'false' }}"
                                                data-room-id="{{ $room->id }}"
                                                data-room-number="{{ $room->number }}"
                                                data-tooltip="{{ $tooltipText }}"
                                                onclick="handleCalendarDayClick(this)"
                                                title="{{ $tooltipText }}">
                                                <div class="fw-bold">{{ $date->format('d') }}</div>
                                                <div class="small text-muted">{{ $date->isoFormat('MMM') }}</div>
                                                <div class="mt-1">
                                                    <i class="{{ $icon }} fa-sm"></i>
                                                </div>
                                                @if($isOccupied && isset($availability['reservation_count']) && $availability['reservation_count'] > 1)
                                                <div class="position-absolute top-0 end-0">
                                                    <span class="badge bg-danger" style="font-size: 0.6rem; padding: 1px 3px;">
                                                        {{ $availability['reservation_count'] }}
                                                    </span>
                                                </div>
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Navigation du calendrier -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Cliquez sur une date pour voir/réserver
                            </small>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="scrollToTodayInCalendar()">
                                <i class="fas fa-calendar-day me-1"></i>
                                Aujourd'hui
                            </button>
                            <a href="{{ route('availability.calendar') }}?room_number={{ $room->number }}" 
                               class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-expand-alt me-1"></i>
                                Calendrier complet
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Réservations à venir -->
            @if($nextReservation)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-calendar-plus me-2"></i>
                            <strong>Prochaine réservation</strong>
                        </div>
                        <span class="badge bg-light text-info">
                            {{ $nextReservation->check_in->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold text-dark mb-1">{{ $nextReservation->customer->name ?? 'Client inconnu' }}</h6>
                            <div class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $nextReservation->check_in->format('d/m/Y') }} → {{ $nextReservation->check_out->format('d/m/Y') }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-moon me-1"></i>
                                {{ $nextReservation->nights }} nuit(s)
                                <span class="mx-2">•</span>
                                <i class="fas fa-users me-1"></i>
                                {{ $nextReservation->person_count ?? 1 }} pers.
                            </div>
                        </div>
                        <div>
                            <span class="badge bg-warning">Réservation confirmée</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

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
                            @php
                                // Vérifier si l'utilisateur peut marquer en maintenance
                                $canMaintenance = in_array($user->role ?? '', ['Super', 'Admin', 'Reception', 'Housekeeping']);
                            @endphp
                            
                            @if($canMaintenance && $room->room_status_id == 1)
                            <a href="{{ route('housekeeping.mark-maintenance', $room->id) }}"
                            class="btn btn-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-tools fa-2x mb-2"></i>
                                <div>Maintenance</div>
                                <small class="text-muted">Marquer en maintenance</small>
                            </a>
                            @else
                            <button class="btn btn-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" 
                                    disabled
                                    title="{{ !$canMaintenance ? 'Non autorisé' : 'Chambre déjà en maintenance' }}">
                                <i class="fas fa-tools fa-2x mb-2"></i>
                                <div>Maintenance</div>
                                <small class="text-muted">{{ !$canMaintenance ? 'Non autorisé' : 'Indisponible' }}</small>
                            </button>
                            @endif
                        </div>
                        <div class="col-md-3">
                            @php
                                // Vérifier si l'utilisateur peut marquer comme nettoyée
                                $canClean = in_array($user->role ?? '', ['Super', 'Admin', 'Housekeeping']);
                                $isDirty = $room->room_status_id == 3; // À nettoyer
                            @endphp
                            
                            @if($canClean && $isDirty)
                            <a href="{{ route('housekeeping.mark-cleaned', $room->id) }}" 
                            class="btn btn-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-broom fa-2x mb-2"></i>
                                <div>Nettoyée</div>
                                <small class="text-muted">Marquer comme nettoyée</small>
                            </a>
                            @else
                            <button class="btn btn-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" 
                                    disabled
                                    title="{{ !$canClean ? 'Non autorisé' : 'Chambre déjà nettoyée' }}">
                                <i class="fas fa-broom fa-2x mb-2"></i>
                                <div>Nettoyée</div>
                                <small class="text-muted">{{ !$canClean ? 'Non autorisé' : 'Indisponible' }}</small>
                            </button>
                            @endif
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
    
    .legend-square.unavailable {
        background-color: #e2e3e5;
        border: 1px solid #d3d6d8;
    }
    
    .legend-square.today {
        background-color: #fff3cd;
        border: 1px solid #ffecb5;
    }
    
    /* Calendrier amélioré */
    .availability-mini-calendar {
        font-size: 0.85rem;
    }
    
    .calendar-day {
        position: relative;
        transition: all 0.2s;
        min-height: 70px;
    }
    
    .calendar-day:hover {
        transform: scale(1.05);
        z-index: 10;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }
    
    .calendar-day.available {
        background-color: #d1e7dd;
        border: 1px solid #badbcc;
    }
    
    .calendar-day.occupied {
        background-color: #f8d7da;
        border: 1px solid #f5c2c7;
    }
    
    .calendar-day.unavailable {
        background-color: #e2e3e5;
        border: 1px solid #d3d6d8;
    }
    
    .calendar-day.today-cell {
        background-color: #fff3cd !important;
        border: 2px solid #ffc107 !important;
        font-weight: bold;
    }
    
    .calendar-day .badge {
        position: absolute;
        top: 2px;
        right: 2px;
        font-size: 0.5rem;
        padding: 1px 3px;
    }
    
    /* En-têtes de cartes */
    .card-header.bg-primary {
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
    
    /* Responsive */
    @media (max-width: 768px) {
        .calendar-day {
            min-height: 60px;
            padding: 8px 4px !important;
        }
        
        .calendar-day .small {
            font-size: 0.7rem;
        }
        
        .availability-mini-calendar {
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les tooltips Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Ajouter des tooltips dynamiques pour les cellules du calendrier
        document.querySelectorAll('.calendar-day').forEach(function(cell) {
            cell.setAttribute('data-bs-toggle', 'tooltip');
            cell.setAttribute('data-bs-placement', 'top');
            cell.setAttribute('title', cell.getAttribute('data-tooltip'));
            new bootstrap.Tooltip(cell);
        });
        
        // Mettre en évidence aujourd'hui
        highlightToday();
    });
    
    function handleCalendarDayClick(cell) {
        const date = cell.getAttribute('data-date');
        const isOccupied = cell.getAttribute('data-is-occupied') === 'true';
        const roomId = cell.getAttribute('data-room-id');
        const roomNumber = cell.getAttribute('data-room-number');
        
        if (isOccupied) {
            // Si occupée, montrer les détails de réservation
            showOccupancyDetailsModal(roomId, date, roomNumber);
        } else {
            // Si disponible, proposer de réserver
            showReservationModal(roomId, roomNumber, date);
        }
    }
    
    function showOccupancyDetailsModal(roomId, date, roomNumber) {
        // Afficher une modal simple avec un message
        const modalContent = `
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-times me-2"></i>
                    Chambre Occupée
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    La chambre ${roomNumber} est occupée le ${new Date(date).toLocaleDateString('fr-FR')}.
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Date :</label>
                            <div class="fw-bold">${new Date(date).toLocaleDateString('fr-FR')}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Statut :</label>
                            <span class="badge bg-danger">Occupée</span>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Pour voir les détails de la réservation, veuillez consulter la liste des transactions.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <a href="/transaction" class="btn btn-primary">
                    <i class="fas fa-list me-2"></i>
                    Voir les transactions
                </a>
            </div>
        `;
        
        showModal('Détails d\'occupation', modalContent);
    }
    
    function showReservationModal(roomId, roomNumber, date) {
        const checkInDate = new Date(date);
        const checkOutDate = new Date(checkInDate);
        checkOutDate.setDate(checkOutDate.getDate() + 1);
        
        const checkInStr = checkInDate.toISOString().split('T')[0];
        const checkOutStr = checkOutDate.toISOString().split('T')[0];
        
        const modalContent = `
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-plus me-2"></i>
                    Réserver la chambre
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    La chambre ${roomNumber} est disponible pour le ${new Date(date).toLocaleDateString('fr-FR')}.
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Chambre :</label>
                            <div class="fw-bold">${roomNumber}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Date de début :</label>
                            <div class="fw-bold">${new Date(date).toLocaleDateString('fr-FR')}</div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Vous serez redirigé vers le formulaire de réservation avec les dates pré-remplies.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="{{ route('transaction.reservation.createIdentity') }}?room_id=${roomId}&check_in=${checkInStr}&check_out=${checkOutStr}" 
                   class="btn btn-success">
                    <i class="fas fa-book me-2"></i>
                    Continuer la réservation
                </a>
            </div>
        `;
        
        showModal('Nouvelle réservation', modalContent);
    }
    
    function showModal(title, content) {
        // Supprimer les modales existantes
        const existingModal = document.getElementById('calendarModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Créer la modale
        const modalHtml = `
            <div class="modal fade" id="calendarModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        ${content}
                    </div>
                </div>
            </div>
        `;
        
        // Ajouter la modale au DOM
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Afficher la modale
        const modalElement = document.getElementById('calendarModal');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        
        // Supprimer la modale du DOM après fermeture
        modalElement.addEventListener('hidden.bs.modal', function () {
            modalElement.remove();
        });
    }
    
    function scrollToTodayInCalendar() {
        const todayCell = document.querySelector('.calendar-day.today-cell');
        if (todayCell) {
            todayCell.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center',
                inline: 'center' 
            });
            
            // Ajouter une animation de surbrillance
            todayCell.style.boxShadow = '0 0 20px rgba(255, 193, 7, 0.7)';
            setTimeout(() => {
                todayCell.style.boxShadow = '';
            }, 1500);
        }
    }
    
    function highlightToday() {
        const todayCells = document.querySelectorAll('.calendar-day.today-cell');
        todayCells.forEach(cell => {
            cell.classList.add('today-highlight');
        });
    }
    
    // Gestion des actions rapides
    document.addEventListener('click', function(e) {
        // Gestion du bouton de maintenance
        if (e.target.closest('.btn-warning') && e.target.closest('.btn-warning').textContent.includes('Maintenance')) {
            const button = e.target.closest('.btn-warning');
            if (button.hasAttribute('disabled')) {
                e.preventDefault();
                const title = button.getAttribute('title');
                if (title) {
                    alert(title);
                }
            }
        }
        
        // Gestion du bouton de nettoyage
        if (e.target.closest('.btn-info') && e.target.closest('.btn-info').textContent.includes('Nettoyée')) {
            const button = e.target.closest('.btn-info');
            if (button.hasAttribute('disabled')) {
                e.preventDefault();
                const title = button.getAttribute('title');
                if (title) {
                    alert(title);
                }
            }
        }
    });
    
    // Fonction pour afficher des notifications
    function showToast(title, message, type = 'info') {
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0 position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999;" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}:</strong> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', toastHtml);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
        toast.show();
        
        toastElement.addEventListener('hidden.bs.toast', function () {
            toastElement.remove();
        });
    }
    
    // Mettre à jour l'heure en temps réel
    function updateCurrentTime() {
        const now = new Date();
        const timeElements = document.querySelectorAll('.current-time');
        timeElements.forEach(element => {
            element.textContent = now.toLocaleTimeString('fr-FR');
        });
    }
    
    // Démarrer la mise à jour de l'heure
    setInterval(updateCurrentTime, 1000);
    updateCurrentTime();
</script>

<style>
    .today-highlight {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.03); }
        100% { transform: scale(1); }
    }
    
    /* Styles pour les modales */
    .modal-content {
        border-radius: 10px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    
    .modal-header {
        border-radius: 10px 10px 0 0 !important;
    }
    
    /* Animation pour les cellules du calendrier */
    .calendar-day {
        animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Toast personnalisé */
    .toast {
        min-width: 300px;
        border-radius: 8px;
    }
    
    /* Responsive pour les modales */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 10px;
        }
        
        .modal-content {
            font-size: 0.9rem;
        }
    }
</style>
@endpush