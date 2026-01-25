@extends('template.master')

@section('title', 'Calendrier des disponibilités')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark">Calendrier des disponibilités</h1>
                    <p class="text-muted mb-0">Visualisez les réservations et disponibilités des chambres</p>
                </div>
                <div class="d-flex gap-2">
                    <div class="btn-group">
                        <a href="{{ route('availability.calendar', ['month' => $prevMonth->format('m'), 'year' => $prevMonth->format('Y')]) }}" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <button class="btn btn-outline-primary" disabled>
                            {{ $startDate->translatedFormat('F Y') }}
                        </button>
                        <a href="{{ route('availability.calendar', ['month' => $nextMonth->format('m'), 'year' => $nextMonth->format('Y')]) }}" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                    <a href="{{ route('availability.search') }}" class="btn btn-hotel-primary">
                        <i class="fas fa-search me-2"></i>
                        Rechercher disponibilité
                    </a>
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
                            <h6 class="text-muted mb-1">Disponibles aujourd'hui</h6>
                            <h3 class="fw-bold text-success">{{ $stats['available_today'] }}</h3>
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
                            <h6 class="text-muted mb-1">Occupées</h6>
                            <h3 class="fw-bold text-warning">{{ $stats['occupied_today'] }}</h3>
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
                            <h6 class="text-muted mb-1">Indisponibles</h6>
                            <h3 class="fw-bold text-danger">{{ $stats['unavailable_today'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fa-2x text-danger opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" class="row g-3" id="calendarFilterForm">
                        <div class="col-md-3">
                            <label class="form-label">Type de chambre</label>
                            <select name="room_type" class="form-select">
                                <option value="">Tous les types</option>
                                @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('room_type') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Numéro de chambre</label>
                            <input type="text" name="room_number" class="form-control" 
                                   value="{{ request('room_number') }}"
                                   placeholder="Ex: 101, 102...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Mois</label>
                            <input type="month" name="month_year" class="form-control" 
                                   value="{{ $year }}-{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}"
                                   onchange="this.form.submit()">
                        </div>
                        <div class="col-md-3 d-flex align-items-end justify-content-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter me-2"></i>
                                Filtrer
                            </button>
                            <a href="{{ route('availability.calendar') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Légende -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-3 align-items-center">
                <div class="d-flex align-items-center">
                    <div class="legend-square available me-2"></div>
                    <small class="text-muted">Disponible</small>
                </div>
                <div class="d-flex align-items-center">
                    <div class="legend-square occupied me-2"></div>
                    <small class="text-muted">Occupée</small>
                </div>
                <div class="d-flex align-items-center">
                    <div class="legend-square unavailable me-2"></div>
                    <small class="text-muted">Indisponible</small>
                </div>
                <div class="d-flex align-items-center">
                    <div class="legend-square today me-2"></div>
                    <small class="text-muted">Aujourd'hui</small>
                </div>
                <div class="d-flex align-items-center">
                    <div class="badge bg-danger me-2">2+</div>
                    <small class="text-muted">Conflit (multiple réservations)</small>
                </div>
                <div class="ms-auto">
                    <small class="text-muted">
                        <i class="fas fa-mouse-pointer me-1"></i>
                        Cliquez sur une cellule pour les détails
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendrier -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <!-- Container pour le défilement horizontal -->
                    <div class="calendar-container" style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
                        <div class="table-responsive" style="min-width: {{ (count($dates) * 80 + 250) }}px;">
                            <table class="table table-bordered mb-0 availability-calendar" style="min-width: 100%;">
                                <thead>
                                    <tr class="bg-light">
                                        <!-- Première colonne fixe -->
                                        <th class="text-center py-3 room-info-header" style="min-width: 250px; position: sticky; left: 0; z-index: 10; background: #f8f9fa;">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>Chambre / Date</span>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="scrollToToday()" title="Aller à aujourd'hui">
                                                    <i class="fas fa-calendar-day"></i>
                                                </button>
                                            </div>
                                        </th>
                                        <!-- Colonnes de dates -->
                                        @foreach($dates as $dateString => $dateInfo)
                                            <th class="text-center py-3 date-header {{ $dateInfo['is_today'] ? 'today-column' : '' }} {{ $dateInfo['is_weekend'] ? 'weekend-column' : '' }}"
                                                style="min-width: 80px; position: sticky; top: 0; z-index: 5; background: #f8f9fa;"
                                                data-date="{{ $dateString }}"
                                                onclick="scrollToDate('{{ $dateString }}')"
                                                title="Cliquez pour centrer">
                                                <div class="fw-bold">{{ $dateInfo['date']->format('d') }}</div>
                                                <div class="small text-muted">{{ $dateInfo['day_name'] }}</div>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rooms->isEmpty())
                                        <tr>
                                            <td colspan="{{ count($dates) + 1 }}" class="text-center py-5">
                                                <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                                                <h5 class="text-dark">Aucune chambre trouvée</h5>
                                                <p class="text-muted">Aucune chambre ne correspond aux filtres sélectionnés</p>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach($calendar as $roomData)
                                            <tr class="room-row" data-room-number="{{ $roomData['room']->number }}">
                                                <!-- Première colonne fixe -->
                                                <td class="py-3 room-info-cell" style="min-width: 250px; position: sticky; left: 0; z-index: 9; background: #f8f9fa;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <div class="room-number-badge">{{ $roomData['room']->number }}</div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="fw-bold text-dark">{{ $roomData['room']->type->name ?? 'Type inconnu' }}</div>
                                                            <div class="small text-muted">
                                                                <i class="fas fa-users me-1"></i>
                                                                {{ $roomData['room']->capacity }} pers.
                                                            </div>
                                                            <div class="small text-muted">
                                                                <i class="fas fa-money-bill me-1"></i>
                                                                {{ number_format($roomData['room']->price, 0, ',', ' ') }} FCFA/nuit
                                                            </div>
                                                            @if($roomData['room']->room_status_id != 1)
                                                                <div class="small">
                                                                    <span class="badge bg-secondary mt-1">
                                                                        {{ $roomData['room']->roomStatus->name ?? 'Indisponible' }}
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="ms-2">
                                                            <a href="{{ route('availability.room.detail', $roomData['room']->id) }}" 
                                                               class="btn btn-sm btn-outline-primary"
                                                               title="Voir détails">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                               <!-- Colonnes de dates -->
                                                @foreach($dates as $dateString => $dateInfo)
                                                    @php
                                                        $availability = $roomData['availability'][$dateString] ?? [
                                                            'occupied' => false,
                                                            'available' => true,
                                                            'css_class' => 'available',
                                                            'reservation_count' => 0,
                                                            'can_reserve' => false, // false par défaut
                                                            'has_reservations' => false
                                                        ];
                                                        
                                                        // S'assurer que toutes les clés existent
                                                        $canReserve = $availability['can_reserve'] ?? false;
                                                        $cssClass = $availability['css_class'] ?? 'available';
                                                        $reservationCount = $availability['reservation_count'] ?? 0;
                                                        $isOccupied = $availability['occupied'] ?? false;
                                                    @endphp
                                                    
                                                    <td class="text-center py-3 availability-cell
                                                        {{ $cssClass }}
                                                        {{ $dateInfo['is_today'] ? 'today-cell' : '' }}
                                                        {{ $dateInfo['is_weekend'] ? 'weekend-cell' : '' }}
                                                        {{ $canReserve ? 'can-reserve' : 'cannot-reserve' }}"
                                                        style="min-width: 80px;"
                                                        data-bs-toggle="tooltip"
                                                        data-room-id="{{ $roomData['room']->id }}"
                                                        data-room-number="{{ $roomData['room']->number }}"
                                                        data-room-type="{{ $roomData['room']->type->name ?? '' }}"
                                                        data-room-price="{{ $roomData['room']->price }}"
                                                        data-date="{{ $dateString }}"
                                                        data-formatted-date="{{ $dateInfo['date']->format('d/m/Y') }}"
                                                        data-is-occupied="{{ $isOccupied ? 'true' : 'false' }}"
                                                        data-reservation-count="{{ $reservationCount }}"
                                                        data-can-reserve="{{ $canReserve ? 'true' : 'false' }}"
                                                        title="{{ $dateInfo['date']->format('d/m/Y') }} - 
                                                            Chambre {{ $roomData['room']->number }} - 
                                                            {{ $isOccupied ? 'Occupée' : 'Disponible' }}
                                                            @if($reservationCount > 1)
                                                            - ALERTE: {{ $reservationCount }} réservations!
                                                            @endif">
                                                        @if($isOccupied)
                                                            <i class="fas fa-user text-danger"></i>
                                                            @if($reservationCount > 1)
                                                                <span class="small position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                                    {{ $reservationCount }}
                                                                    @if($reservationCount > 2)
                                                                        <i class="fas fa-exclamation ms-1"></i>
                                                                    @endif
                                                                </span>
                                                            @endif
                                                        @else
                                                            <i class="fas fa-check text-success"></i>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary" onclick="selectDateRange()">
                            <i class="fas fa-calendar-range me-2"></i>
                            Sélectionner période
                        </button>
                        <button type="button" class="btn btn-outline-info" onclick="checkAllAvailability()">
                            <i class="fas fa-search me-2"></i>
                            Vérifier disponibilité
                        </button>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>
                        Imprimer
                    </button>
                    <a href="{{ route('availability.export', [
                        'type' => 'excel',
                        'export_type' => 'calendar',
                        'month' => $month,
                        'year' => $year
                    ]) }}" class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>
                        Exporter Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .calendar-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        position: relative;
    }
    
    .availability-calendar {
        font-size: 0.85rem;
        table-layout: fixed;
        margin-bottom: 0;
    }
    
    .room-info-cell {
        background-color: #f8f9fa;
        position: sticky;
        left: 0;
        z-index: 9;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        min-width: 250px;
    }
    
    .room-number-badge {
        width: 40px;
        height: 40px;
        background-color: #0d6efd;
        color: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1rem;
    }
    
    .availability-cell {
        cursor: pointer;
        transition: all 0.2s;
        min-width: 80px;
        height: 80px;
        vertical-align: middle;
        position: relative;
    }
    
    .availability-cell:hover {
        transform: scale(1.02);
        box-shadow: inset 0 0 0 2px #0d6efd;
        z-index: 1;
    }
    
    .availability-cell.available {
        background-color: #d1e7dd;
        border-left: 1px solid #badbcc;
        border-right: 1px solid #badbcc;
    }
    
    .availability-cell.occupied {
        background-color: #f8d7da;
        border-left: 1px solid #f5c2c7;
        border-right: 1px solid #f5c2c7;
    }
    
    .availability-cell.unavailable {
        background-color: #e2e3e5;
        border-left: 1px solid #d3d6d8;
        border-right: 1px solid #d3d6d8;
    }
    
    .availability-cell.today-cell {
        background-color: #fff3cd !important;
        border-left: 2px solid #ffc107 !important;
        border-right: 2px solid #ffc107 !important;
    }
    
    .availability-cell.weekend-cell {
        background-color: rgba(248, 249, 250, 0.7);
    }
    
    .availability-cell.can-reserve:hover {
        background-color: #cfe2ff !important;
    }
    
    .today-column {
        background-color: #fff3cd;
        color: #856404;
        font-weight: bold;
        border-left: 2px solid #ffc107 !important;
        border-right: 2px solid #ffc107 !important;
    }
    
    .weekend-column {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    
    .date-header {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .date-header:hover {
        background-color: #e9ecef !important;
    }
    
    .legend-square {
        width: 20px;
        height: 20px;
        border-radius: 4px;
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
    
    /* Badge pour multiples réservations */
    .availability-cell .badge {
        font-size: 0.6rem;
        padding: 2px 5px;
    }
    
    /* Scrollbar styling */
    .calendar-container::-webkit-scrollbar {
        height: 10px;
    }
    
    .calendar-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 5px;
    }
    
    .calendar-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 5px;
    }
    
    .calendar-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    /* Indicateur de défilement */
    .scroll-indicator {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(13, 110, 253, 0.8);
        color: white;
        padding: 10px;
        border-radius: 50%;
        cursor: pointer;
        z-index: 100;
        transition: opacity 0.3s;
    }
    
    .scroll-indicator:hover {
        background: rgba(13, 110, 253, 1);
    }
    
    /* Sélection de période */
    .selected-period {
        background-color: #cfe2ff !important;
        border: 2px solid #0d6efd !important;
    }
    
    .selected-start {
        background-color: #0d6efd !important;
        color: white !important;
    }
    
    @media print {
        .btn, .legend-square, .availability-cell:hover, .date-header, .scroll-indicator {
            display: none !important;
        }
        
        .calendar-container {
            overflow: visible !important;
            width: 100% !important;
            border: none !important;
        }
        
        .table-responsive {
            min-width: 100% !important;
        }
        
        .availability-calendar {
            font-size: 10px;
        }
        
        .availability-cell {
            min-width: 50px !important;
            height: 50px !important;
        }
        
        .room-info-cell {
            min-width: 180px !important;
        }
        
        .availability-cell .badge {
            font-size: 0.5rem;
            padding: 1px 3px;
        }
    }
    
    @media (max-width: 768px) {
        .availability-cell {
            min-width: 60px;
            height: 60px;
        }
        
        .room-info-cell {
            min-width: 200px;
        }
        
        .room-number-badge {
            width: 30px;
            height: 30px;
            font-size: 0.8rem;
        }
        
        .date-header {
            min-width: 60px !important;
        }
    }
    
    @media (max-width: 576px) {
        .availability-cell {
            min-width: 50px !important;
            height: 50px !important;
            font-size: 0.7rem;
        }
        
        .room-info-cell {
            min-width: 160px !important;
        }
        
        th, td {
            padding: 6px 3px !important;
        }
        
        .room-number-badge {
            width: 25px;
            height: 25px;
            font-size: 0.7rem;
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
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover',
                placement: 'top'
            });
        });
        
        // Variables globales pour la sélection de période
        window.selectedCells = [];
        window.selectionMode = false;
        window.selectionStart = null;
        
        // Gérer les clics sur les cellules du calendrier
        document.querySelectorAll('.availability-cell').forEach(function(cell) {
            cell.addEventListener('click', function(e) {
                if (window.selectionMode) {
                    // Mode sélection de période
                    handlePeriodSelection(this);
                } else {
                    // Mode normal: afficher les détails
                    const roomId = this.getAttribute('data-room-id');
                    const date = this.getAttribute('data-date');
                    const isOccupied = this.getAttribute('data-is-occupied') === 'true';
                    
                    if (!roomId || !date) {
                        console.error('Données manquantes pour la cellule');
                        return;
                    }
                    
                    if (isOccupied) {
                        showOccupancyDetails(roomId, date);
                    } else {
                        showAvailabilityDetails(roomId, date);
                    }
                }
            });
            
            // Ajouter un effet au survol
            cell.addEventListener('mouseenter', function() {
                if (!this.classList.contains('selected-period')) {
                    this.style.transform = 'scale(1.05)';
                    this.style.zIndex = '2';
                    this.style.boxShadow = '0 2px 8px rgba(0,0,0,0.2)';
                }
            });
            
            cell.addEventListener('mouseleave', function() {
                if (!this.classList.contains('selected-period')) {
                    this.style.transform = '';
                    this.style.zIndex = '';
                    this.style.boxShadow = '';
                }
            });
        });
        
        // Gérer le clic sur les en-têtes de date
        document.querySelectorAll('.date-header').forEach(function(header) {
            header.addEventListener('click', function() {
                const date = this.getAttribute('data-date');
                scrollToDate(date);
            });
        });
        
        // Filtrer les chambres par numéro
        const roomNumberInput = document.querySelector('input[name="room_number"]');
        if (roomNumberInput) {
            roomNumberInput.addEventListener('input', function() {
                filterRoomsByNumber(this.value);
            });
        }
        
        // Ajouter un indicateur de défilement
        addScrollIndicator();
        
        // Détecter la largeur d'écran et ajuster
        adjustForScreenSize();
        window.addEventListener('resize', adjustForScreenSize);
    });
    
    // ==================== FONCTIONS UTILITAIRES ====================
    
    function filterRoomsByNumber(roomNumber) {
        const rows = document.querySelectorAll('.room-row');
        const searchText = roomNumber.toLowerCase().trim();
        
        rows.forEach(row => {
            const roomNum = row.getAttribute('data-room-number');
            if (searchText === '' || roomNum.toLowerCase().includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    function scrollToDate(dateString) {
        const targetCell = document.querySelector(`.availability-cell[data-date="${dateString}"]`);
        if (targetCell) {
            targetCell.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'center'
            });
            
            // Surligner temporairement
            targetCell.classList.add('selected-period');
            setTimeout(() => {
                targetCell.classList.remove('selected-period');
            }, 2000);
        }
    }
    
    function scrollToToday() {
        const today = new Date().toISOString().split('T')[0];
        scrollToDate(today);
    }
    
    function addScrollIndicator() {
        const calendarContainer = document.querySelector('.calendar-container');
        if (!calendarContainer) return;
        
        const scrollIndicator = document.createElement('div');
        scrollIndicator.className = 'scroll-indicator d-none d-md-block';
        scrollIndicator.innerHTML = '<i class="fas fa-chevron-right"></i>';
        
        calendarContainer.style.position = 'relative';
        calendarContainer.appendChild(scrollIndicator);
        
        scrollIndicator.addEventListener('click', function() {
            calendarContainer.scrollBy({
                left: 300,
                behavior: 'smooth'
            });
        });
        
        // Masquer l'indicateur quand on est à la fin
        calendarContainer.addEventListener('scroll', function() {
            const isAtEnd = this.scrollLeft + this.clientWidth >= this.scrollWidth - 10;
            scrollIndicator.style.opacity = isAtEnd ? '0' : '1';
            scrollIndicator.style.pointerEvents = isAtEnd ? 'none' : 'auto';
            scrollIndicator.style.display = isAtEnd ? 'none' : 'flex';
        });
        
        // Initialiser l'état
        scrollIndicator.style.opacity = '1';
    }
    
    function adjustForScreenSize() {
        const cells = document.querySelectorAll('.availability-cell');
        const isMobile = window.innerWidth < 768;
        
        cells.forEach(cell => {
            if (isMobile) {
                cell.style.minWidth = '50px';
                cell.style.height = '50px';
                cell.style.fontSize = '0.7rem';
            } else {
                cell.style.minWidth = '80px';
                cell.style.height = '80px';
                cell.style.fontSize = '';
            }
        });
    }
    
    // ==================== GESTION DES DÉTAILS ====================
    
    function showOccupancyDetails(roomId, date) {
        fetch(`/availability/calendar-cell-details?room_id=${roomId}&date=${date}`)
            .then(response => {
                if (!response.ok) throw new Error('Erreur réseau');
                return response.json();
            })
            .then(data => {
                let modalContent = `
                    <div class="p-3">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-calendar-times text-danger me-2"></i>
                            Chambre Occupée
                        </h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-danger border-2">
                                    <div class="card-body">
                                        <h6 class="fw-bold">Informations Chambre</h6>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="room-number-badge-sm me-3">${data.room.number}</span>
                                            <div>
                                                <div class="fw-bold">${data.room.type}</div>
                                                <small class="text-muted">${data.room.capacity} personnes</small>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Prix/nuit:</small>
                                            <div class="fw-bold">${data.room.price} FCFA</div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Date:</small>
                                            <div class="fw-bold">${new Date(date).toLocaleDateString('fr-FR')}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-warning border-2">
                                    <div class="card-body">
                                        <h6 class="fw-bold">Statut</h6>
                                        <div class="mb-3">
                                            <span class="badge bg-danger py-2 px-3">
                                                <i class="fas fa-user me-2"></i>
                                                Occupée
                                            </span>
                                        </div>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Cette chambre n'est pas disponible pour cette date.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                `;
                
                if (data.reservations && data.reservations.length > 0) {
                    modalContent += `
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-list me-2"></i>
                            Réservations (${data.reservations.length})
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Client</th>
                                        <th>Arrivée</th>
                                        <th>Départ</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    
                    data.reservations.forEach(reservation => {
                        modalContent += `
                            <tr>
                                <td>
                                    <div class="fw-bold">${reservation.customer.name || 'Client'}</div>
                                    <small class="text-muted">${reservation.customer.email || ''}</small>
                                </td>
                                <td>${new Date(reservation.check_in).toLocaleDateString('fr-FR')}</td>
                                <td>${new Date(reservation.check_out).toLocaleDateString('fr-FR')}</td>
                                <td>
                                    <span class="badge ${reservation.status === 'active' ? 'bg-success' : 'bg-warning'}">
                                        ${reservation.status === 'active' ? 'En séjour' : 'Réservée'}
                                    </span>
                                </td>
                                <td>
                                    <a href="/transactions/${reservation.id}" class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        `;
                    });
                    
                    modalContent += `
                                </tbody>
                            </table>
                        </div>
                    `;
                    
                    if (data.reservations.length > 1) {
                        modalContent += `
                            <div class="alert alert-danger mt-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>ALERTE:</strong> ${data.reservations.length} réservations trouvées pour cette date!
                                <div class="mt-2">
                                    <a href="/availability/room/${roomId}/conflicts?date=${date}" class="btn btn-sm btn-danger">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Voir les conflits
                                    </a>
                                </div>
                            </div>
                        `;
                    }
                }
                
                modalContent += `
                        <div class="mt-4">
                            <h6 class="fw-bold mb-3">Options</h6>
                            <div class="d-grid gap-2">
                                <a href="/availability/search?room_type_id=${data.room.type_id}" class="btn btn-outline-primary">
                                    <i class="fas fa-search me-2"></i>
                                    Chercher une autre chambre
                                </a>
                                <button class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>
                                    Fermer
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                showModal('Détails d\'occupation', modalContent);
            })
            .catch(error => {
                console.error('Erreur:', error);
                showModal('Erreur', `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Erreur: ${error.message}
                    </div>
                    <div class="text-center mt-3">
                        <button class="btn btn-primary" onclick="showOccupancyDetails('${roomId}', '${date}')">
                            <i class="fas fa-redo me-2"></i>
                            Réessayer
                        </button>
                    </div>
                `);
            });
    }
    
    function showAvailabilityDetails(roomId, date) {
        fetch(`/availability/check-availability?room_id=${roomId}&check_in=${date}&check_out=${date}`)
            .then(response => {
                if (!response.ok) throw new Error('Erreur réseau');
                return response.json();
            })
            .then(data => {
                let modalContent = `
                    <div class="p-3">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-calendar-check text-success me-2"></i>
                            Chambre Disponible
                        </h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-success border-2">
                                    <div class="card-body">
                                        <h6 class="fw-bold">Informations Chambre</h6>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="room-number-badge-sm me-3">${data.room.number}</span>
                                            <div>
                                                <div class="fw-bold">${data.room.type}</div>
                                                <small class="text-muted">${data.room.capacity} personnes</small>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Prix/nuit:</small>
                                            <div class="fw-bold">${data.room.price.toLocaleString()} FCFA</div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Statut:</small>
                                            <div>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>
                                                    Disponible
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-primary border-2">
                                    <div class="card-body">
                                        <h6 class="fw-bold">Réservation</h6>
                                        <div class="mb-3">
                                            <label class="form-label">Date</label>
                                            <div class="fw-bold">${new Date(date).toLocaleDateString('fr-FR')}</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Prix total</label>
                                            <div class="fw-bold text-success fs-4">${data.total_price.toLocaleString()} FCFA</div>
                                        </div>
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle me-2"></i>
                                            Cette chambre est disponible pour cette date.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="/transaction/reservation/createIdentity?room_id=${roomId}&check_in=${date}&check_out=${date}" 
                               class="btn btn-success btn-lg">
                                <i class="fas fa-plus me-2"></i>
                                Réserver cette chambre
                            </a>
                            <button type="button" class="btn btn-outline-primary" onclick="selectDateRangeFromCell('${roomId}', '${date}')">
                                <i class="fas fa-calendar-range me-2"></i>
                                Sélectionner une période
                            </button>
                            <a href="/availability/room/${roomId}" class="btn btn-outline-secondary">
                                <i class="fas fa-info-circle me-2"></i>
                                Voir les détails de la chambre
                            </a>
                        </div>
                    </div>
                `;
                
                showModal('Chambre disponible', modalContent);
            })
            .catch(error => {
                console.error('Erreur:', error);
                showModal('Erreur', `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Erreur: ${error.message}
                    </div>
                `);
            });
    }
    
    // ==================== SÉLECTION DE PÉRIODE ====================
    
    function selectDateRange() {
        window.selectionMode = true;
        window.selectedCells = [];
        
        showModal('Sélection de période', `
            <div class="p-3">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-calendar-range me-2"></i>
                    Sélectionner une période
                </h5>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Mode sélection activé. Cliquez sur la première date, puis sur la dernière date.
                </div>
                <div class="mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Date d'arrivée</label>
                                <input type="date" id="checkInDate" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Date de départ</label>
                                <input type="date" id="checkOutDate" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary w-100" onclick="applyDateSelection()">
                            <i class="fas fa-check me-2"></i>
                            Appliquer la sélection
                        </button>
                    </div>
                </div>
            </div>
        `);
    }
    
    function selectDateRangeFromCell(roomId, startDate) {
        window.selectionMode = true;
        window.selectionStart = { roomId, date: startDate };
        
        showModal('Sélection de période', `
            <div class="p-3">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-calendar-range me-2"></i>
                    Sélectionner la date de départ
                </h5>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Date d'arrivée: <strong>${new Date(startDate).toLocaleDateString('fr-FR')}</strong><br>
                    Cliquez sur la date de départ dans le calendrier.
                </div>
                <div class="mt-3 text-center">
                    <button class="btn btn-secondary" onclick="cancelSelection()">
                        <i class="fas fa-times me-2"></i>
                        Annuler
                    </button>
                </div>
            </div>
        `);
    }
    
    function handlePeriodSelection(cell) {
        if (!window.selectionStart) {
            // Première sélection
            window.selectionStart = {
                roomId: cell.getAttribute('data-room-id'),
                date: cell.getAttribute('data-date'),
                element: cell
            };
            cell.classList.add('selected-start');
            
            showModal('Sélection de période', `
                <div class="p-3">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-calendar-range me-2"></i>
                        Sélectionner la date de départ
                    </h5>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Arrivée: <strong>${new Date(window.selectionStart.date).toLocaleDateString('fr-FR')}</strong><br>
                        Chambre: <strong>${cell.getAttribute('data-room-number')}</strong><br>
                        Cliquez sur la date de départ.
                    </div>
                </div>
            `);
        } else {
            // Deuxième sélection (date de départ)
            const roomId = cell.getAttribute('data-room-id');
            const endDate = cell.getAttribute('data-date');
            
            if (roomId !== window.selectionStart.roomId) {
                alert('Veuillez sélectionner la même chambre');
                return;
            }
            
            const startDate = new Date(window.selectionStart.date);
            const endDateObj = new Date(endDate);
            
            if (endDateObj <= startDate) {
                alert('La date de départ doit être après la date d\'arrivée');
                resetSelection();
                return;
            }
            
            // Calculer le nombre de nuits
            const nights = Math.ceil((endDateObj - startDate) / (1000 * 60 * 60 * 24));
            
            // Afficher la période sélectionnée
            showModal('Période sélectionnée', `
                <div class="p-3">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-calendar-check me-2"></i>
                        Période sélectionnée
                    </h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Détails</h6>
                                    <div class="mb-2">
                                        <small class="text-muted">Chambre:</small>
                                        <div class="fw-bold">${cell.getAttribute('data-room-number')}</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Type:</small>
                                        <div class="fw-bold">${cell.getAttribute('data-room-type')}</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Prix/nuit:</small>
                                        <div class="fw-bold">${parseInt(cell.getAttribute('data-room-price')).toLocaleString()} FCFA</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Période</h6>
                                    <div class="mb-2">
                                        <small class="text-muted">Arrivée:</small>
                                        <div class="fw-bold">${startDate.toLocaleDateString('fr-FR')}</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Départ:</small>
                                        <div class="fw-bold">${endDateObj.toLocaleDateString('fr-FR')}</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Durée:</small>
                                        <div class="fw-bold">${nights} nuit(s)</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Prix total:</small>
                                        <div class="fw-bold text-success">${(parseInt(cell.getAttribute('data-room-price')) * nights).toLocaleString()} FCFA</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="/transaction/reservation/createIdentity?room_id=${roomId}&check_in=${window.selectionStart.date}&check_out=${endDate}" 
                           class="btn btn-success btn-lg">
                            <i class="fas fa-plus me-2"></i>
                            Réserver cette période
                        </a>
                        <button class="btn btn-outline-secondary" onclick="resetSelection()">
                            <i class="fas fa-redo me-2"></i>
                            Sélectionner une autre période
                        </button>
                    </div>
                </div>
            `);
            
            resetSelection();
        }
    }
    
    function applyDateSelection() {
        const checkIn = document.getElementById('checkInDate').value;
        const checkOut = document.getElementById('checkOutDate').value;
        
        if (!checkIn || !checkOut) {
            alert('Veuillez sélectionner les deux dates');
            return;
        }
        
        if (new Date(checkOut) <= new Date(checkIn)) {
            alert('La date de départ doit être après la date d\'arrivée');
            return;
        }
        
        // Rediriger vers la recherche avec les dates
        window.location.href = `/availability/search?check_in=${checkIn}&check_out=${checkOut}`;
    }
    
    function cancelSelection() {
        resetSelection();
        const modal = bootstrap.Modal.getInstance(document.getElementById('detailsModal'));
        if (modal) modal.hide();
    }
    
    function resetSelection() {
        window.selectionMode = false;
        window.selectionStart = null;
        window.selectedCells = [];
        
        // Retirer les styles de sélection
        document.querySelectorAll('.selected-start, .selected-period').forEach(el => {
            el.classList.remove('selected-start', 'selected-period');
        });
    }
    
    function checkAllAvailability() {
        const checkIn = prompt('Date d\'arrivée (YYYY-MM-DD):', new Date().toISOString().split('T')[0]);
        if (!checkIn) return;
        
        const checkOut = prompt('Date de départ (YYYY-MM-DD):', new Date(Date.now() + 86400000).toISOString().split('T')[0]);
        if (!checkOut) return;
        
        if (new Date(checkOut) <= new Date(checkIn)) {
            alert('La date de départ doit être après la date d\'arrivée');
            return;
        }
        
        window.location.href = `/availability/search?check_in=${checkIn}&check_out=${checkOut}`;
    }
    
    // ==================== FONCTIONS MODAL ====================
    
    function showModal(title, content) {
        // Créer ou réutiliser le modal
        let modal = document.getElementById('detailsModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'detailsModal';
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">${title}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="detailsModalBody"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        // Mettre à jour le contenu
        document.getElementById('detailsModalBody').innerHTML = content;
        modal.querySelector('.modal-title').textContent = title;
        
        // Afficher le modal
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
        
        // Réinitialiser les tooltips dans le modal
        const modalTooltips = [].slice.call(modal.querySelectorAll('[data-bs-toggle="tooltip"]'));
        modalTooltips.forEach(function(tooltipEl) {
            new bootstrap.Tooltip(tooltipEl);
        });
    }
</script>

<!-- Style pour le badge de chambre dans le modal -->
<style>
    .room-number-badge-sm {
        width: 30px;
        height: 30px;
        background-color: #0d6efd;
        color: white;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
    }
    
    .modal-xl {
        max-width: 90%;
    }
</style>
@endpush