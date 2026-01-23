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
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Type de chambre</label>
                            <select name="room_type" class="form-select" onchange="this.form.submit()">
                                <option value="">Tous les types</option>
                                @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('room_type') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Mois</label>
                            <input type="month" name="month_year" class="form-control" 
                                   value="{{ $year }}-{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}"
                                   onchange="this.form.submit()">
                        </div>
                        <div class="col-md-6 d-flex align-items-end justify-content-end">
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
            <div class="d-flex flex-wrap gap-3">
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
                                            <div>Chambre / Date</div>
                                        </th>
                                        <!-- Colonnes de dates -->
                                        @foreach($dates as $dateString => $dateInfo)
                                            <th class="text-center py-3 {{ $dateInfo['is_today'] ? 'today-column' : '' }} {{ $dateInfo['is_weekend'] ? 'weekend-column' : '' }}"
                                                style="min-width: 80px; position: sticky; top: 0; z-index: 5; background: #f8f9fa;">
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
                                            <tr>
                                                <!-- Première colonne fixe -->
                                                <td class="py-3 room-info-cell" style="min-width: 250px; position: sticky; left: 0; z-index: 9; background: #f8f9fa;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <div class="room-number-badge">{{ $roomData['room']->number }}</div>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark">{{ $roomData['room']->type->name ?? 'Type inconnu' }}</div>
                                                            <div class="small text-muted">
                                                                Capacité: {{ $roomData['room']->capacity }} personne(s)
                                                            </div>
                                                            <div class="small text-muted">
                                                                Prix: {{ number_format($roomData['room']->price, 0, ',', ' ') }} FCFA/nuit
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <!-- Colonnes de dates -->
                                                @foreach($dates as $dateString => $dateInfo)
                                                    @php
                                                        $availability = $roomData['availability'][$dateString] ?? [
                                                            'occupied' => false,
                                                            'available' => true,
                                                            'css_class' => 'available'
                                                        ];
                                                    @endphp
                                                    <td class="text-center py-3 availability-cell 
                                                        {{ $availability['css_class'] }} 
                                                        {{ $dateInfo['is_today'] ? 'today-cell' : '' }}
                                                        {{ $dateInfo['is_weekend'] ? 'weekend-cell' : '' }}"
                                                        style="min-width: 80px;"
                                                        data-bs-toggle="tooltip"
                                                        data-room-id="{{ $roomData['room']->id }}"
                                                        data-date="{{ $dateString }}"
                                                        title="{{ $dateInfo['date']->format('d/m/Y') }} - 
                                                               Chambre {{ $roomData['room']->number }} - 
                                                               {{ $availability['occupied'] ? 'Occupée' : 'Disponible' }}">
                                                        @if($availability['occupied'])
                                                            <i class="fas fa-user text-danger"></i>
                                                            @if($availability['reservation_count'] > 1)
                                                                <span class="small position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                                    {{ $availability['reservation_count'] }}
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
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Cliquez sur une cellule pour voir les détails de réservation
                    </small>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>
                        Imprimer
                    </button>
                    <a href="{{ route('availability.export', [
                        'start_date' => $startDate->format('Y-m-d'),
                        'end_date' => $endDate->format('Y-m-d'),
                        'format' => 'excel'
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
    
    .today-cell {
        background-color: #fff3cd !important;
        border-left: 2px solid #ffc107 !important;
        border-right: 2px solid #ffc107 !important;
    }
    
    .weekend-cell {
        background-color: #f8f9fa;
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
    
    @media print {
        .btn, .legend-square, .availability-cell:hover {
            display: none !important;
        }
        
        .calendar-container {
            overflow: visible !important;
            width: 100% !important;
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
                trigger: 'hover'
            });
        });
        
        // Gérer les clics sur les cellules du calendrier
        document.querySelectorAll('.availability-cell').forEach(function(cell) {
            cell.addEventListener('click', function() {
                const roomId = this.getAttribute('data-room-id');
                const date = this.getAttribute('data-date');
                
                if (!roomId || !date) {
                    console.error('Données manquantes pour la cellule');
                    return;
                }
                
                showAvailabilityDetails(roomId, date);
            });
        });
        
        // Gérer le clic sur les en-têtes de date pour un scroll fluide
        document.querySelectorAll('.date-header').forEach(function(header, index) {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function() {
                const dateCells = document.querySelectorAll(`.availability-cell:nth-child(${index + 2})`);
                if (dateCells.length > 0) {
                    dateCells[0].scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest',
                        inline: 'center'
                    });
                }
            });
        });
        
        // Ajouter un indicateur de défilement
        const calendarContainer = document.querySelector('.calendar-container');
        const scrollIndicator = document.createElement('div');
        scrollIndicator.className = 'scroll-indicator d-none d-md-block';
        scrollIndicator.innerHTML = '<i class="fas fa-chevron-right"></i>';
        scrollIndicator.style.cssText = `
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
        `;
        
        if (calendarContainer) {
            calendarContainer.style.position = 'relative';
            calendarContainer.appendChild(scrollIndicator);
            
            scrollIndicator.addEventListener('click', function() {
                calendarContainer.scrollBy({
                    left: 200,
                    behavior: 'smooth'
                });
            });
            
            // Masquer l'indicateur quand on est à la fin
            calendarContainer.addEventListener('scroll', function() {
                const isAtEnd = this.scrollLeft + this.clientWidth >= this.scrollWidth - 10;
                scrollIndicator.style.opacity = isAtEnd ? '0' : '1';
                scrollIndicator.style.pointerEvents = isAtEnd ? 'none' : 'auto';
            });
        }
    });
    
    function showAvailabilityDetails(roomId, date) {
        // Afficher un loader
        const modalBody = document.getElementById('detailsModalBody');
        modalBody.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-3">Chargement des détails...</p>
            </div>
        `;
        
        // Afficher le modal
        const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
        modal.show();
        
        // Récupérer les données via AJAX
        fetch(`/availability/calendar-cell-details?room_id=${roomId}&date=${date}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.message);
                }
                
                // Créer le contenu du modal
                let modalContent = `
                    <div class="p-3">
                        <h5 class="fw-bold mb-3">Détails de disponibilité</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Chambre</label>
                                    <div class="fw-bold d-flex align-items-center">
                                        <span class="room-number-badge-sm me-2">${data.room.number}</span>
                                        ${data.room.type} (${data.room.capacity} pers.)
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Date</label>
                                    <div class="fw-bold">${data.date.formatted} (${data.date.day_name})</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Statut</label>
                                    <div>
                                        <span class="badge bg-${data.status_class}">
                                            ${data.status}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                `;
                
                if (data.is_occupied && data.reservations && data.reservations.length > 0) {
                    modalContent += `
                        <div class="row">
                            <div class="col-12">
                                <h6 class="fw-bold mt-3 mb-2">
                                    <i class="fas fa-calendar-check me-2"></i>
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
                                    <div class="fw-bold">${reservation.customer}</div>
                                    <small class="text-muted">${reservation.guests} personne(s)</small>
                                </td>
                                <td>${reservation.check_in}</td>
                                <td>${reservation.check_out}</td>
                                <td>
                                    <span class="badge bg-${reservation.status === 'active' ? 'success' : 'warning'}">
                                        ${reservation.status_label}
                                    </span>
                                </td>
                                <td>
                                    <a href="/transactions/${reservation.id}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        `;
                    });
                    
                    modalContent += `
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    `;
                } else if (!data.is_occupied && data.room.room_status_id == 1) {
                    modalContent += `
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Cette chambre est disponible pour cette date.
                                    <div class="mt-2">
                                        <strong>Prix:</strong> ${data.room.formatted_price || (data.room.price + ' CFA/nuit')}
                                    </div>
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('transaction.reservation.createIdentity') }}?room_id=${roomId}&check_in=${date}&check_out=${date}" 
                                       class="btn btn-success">
                                        <i class="fas fa-plus me-2"></i>
                                        Créer une réservation
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                }
                
                modalContent += `</div>`;
                
                modalBody.innerHTML = modalContent;
                
                // Réinitialiser les tooltips dans le modal
                const modalTooltips = [].slice.call(modalBody.querySelectorAll('[data-bs-toggle="tooltip"]'));
                modalTooltips.forEach(function(tooltipEl) {
                    new bootstrap.Tooltip(tooltipEl);
                });
                
            })
            .catch(error => {
                console.error('Erreur:', error);
                modalBody.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Erreur lors de la récupération des détails: ${error.message}
                    </div>
                    <div class="text-center mt-3">
                        <button class="btn btn-primary" onclick="showAvailabilityDetails('${roomId}', '${date}')">
                            <i class="fas fa-redo me-2"></i>
                            Réessayer
                        </button>
                    </div>
                `;
            });
    }
</script>

<!-- Modal pour les détails -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Détails de disponibilité
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailsModalBody">
                <!-- Contenu chargé dynamiquement -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Fermer
                </button>
                <a href="{{ route('availability.search') }}" class="btn btn-outline-primary">
                    <i class="fas fa-search me-2"></i>
                    Rechercher disponibilité
                </a>
            </div>
        </div>
    </div>
</div>

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
</style>
@endpush