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
                            {{ $startDate->format('F Y') }}
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
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 availability-calendar">
                            <thead>
                                <tr class="bg-light">
                                    <th class="text-center py-3" style="min-width: 200px;">Chambre / Date</th>
                                    @foreach($dates as $day => $dateInfo)
                                        <th class="text-center py-3 {{ $dateInfo['is_today'] ? 'today-column' : '' }} {{ $dateInfo['is_weekend'] ? 'weekend-column' : '' }}">
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
                                            <td class="py-3 room-info-cell">
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
                                            @foreach($roomData['availability'] as $day => $availability)
                                                <td class="text-center py-3 availability-cell 
                                                    {{ $availability['css_class'] }} 
                                                    {{ $dates[$day]['is_today'] ? 'today-cell' : '' }}
                                                    {{ $dates[$day]['is_weekend'] ? 'weekend-cell' : '' }}"
                                                    data-bs-toggle="tooltip"
                                                    title="{{ $dates[$day]['date']->format('d/m/Y') }} - 
                                                           Chambre {{ $roomData['room']->number }} - 
                                                           {{ $availability['occupied'] ? 'Occupée' : 'Disponible' }}">
                                                    @if($availability['occupied'])
                                                        <i class="fas fa-user text-danger"></i>
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
    .availability-calendar {
        font-size: 0.85rem;
    }
    
    .room-info-cell {
        background-color: #f8f9fa;
        position: sticky;
        left: 0;
        z-index: 1;
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
        min-width: 60px;
        height: 60px;
        vertical-align: middle;
    }
    
    .availability-cell:hover {
        transform: scale(1.05);
        box-shadow: inset 0 0 0 2px #0d6efd;
    }
    
    .availability-cell.available {
        background-color: #d1e7dd;
    }
    
    .availability-cell.occupied {
        background-color: #f8d7da;
    }
    
    .availability-cell.unavailable {
        background-color: #e2e3e5;
    }
    
    .today-cell {
        background-color: #fff3cd !important;
    }
    
    .weekend-cell {
        background-color: #f8f9fa;
    }
    
    .today-column {
        background-color: #fff3cd;
        color: #856404;
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
    
    @media print {
        .btn, .legend-square, .availability-cell:hover {
            display: none !important;
        }
        
        .availability-calendar {
            font-size: 10px;
        }
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
        
        // Gérer les clics sur les cellules
        var cells = document.querySelectorAll('.availability-cell');
        cells.forEach(function(cell) {
            cell.addEventListener('click', function() {
                var roomNumber = this.closest('tr').querySelector('.room-number-badge').textContent;
                var date = this.getAttribute('title').split(' - ')[0];
                
                // Afficher les détails dans un modal
                showAvailabilityDetails(roomNumber, date, this.classList.contains('occupied'));
            });
        });
    });
    
    function showAvailabilityDetails(roomNumber, date, isOccupied) {
        // Récupérer les données via AJAX
        fetch(`/api/availability/details?room=${roomNumber}&date=${date}`)
            .then(response => response.json())
            .then(data => {
                // Créer le contenu du modal
                var modalContent = `
                    <div class="p-3">
                        <h5 class="fw-bold mb-3">Détails de disponibilité</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Chambre</label>
                                    <div class="fw-bold">${data.room.number}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Date</label>
                                    <div class="fw-bold">${data.date}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Statut</label>
                                    <div>
                                        <span class="badge ${isOccupied ? 'bg-danger' : 'bg-success'}">
                                            ${isOccupied ? 'Occupée' : 'Disponible'}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                `;
                
                if (isOccupied && data.reservations && data.reservations.length > 0) {
                    modalContent += `
                        <div class="row">
                            <div class="col-12">
                                <h6 class="fw-bold mt-3 mb-2">Réservations</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Client</th>
                                                <th>Arrivée</th>
                                                <th>Départ</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                    `;
                    
                    data.reservations.forEach(reservation => {
                        modalContent += `
                            <tr>
                                <td>${reservation.customer}</td>
                                <td>${reservation.check_in}</td>
                                <td>${reservation.check_out}</td>
                                <td><span class="badge bg-info">${reservation.status}</span></td>
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
                }
                
                modalContent += `</div>`;
                
                // Afficher le modal
                var modal = new bootstrap.Modal(document.getElementById('detailsModal'));
                document.getElementById('detailsModalBody').innerHTML = modalContent;
                modal.show();
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la récupération des détails');
            });
    }
</script>

<!-- Modal pour les détails -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">Détails de disponibilité</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailsModalBody">
                <!-- Contenu chargé dynamiquement -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <a href="{{ route('transaction.reservation.createIdentity') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Nouvelle réservation
                </a>
            </div>
        </div>
    </div>
</div>
@endpush