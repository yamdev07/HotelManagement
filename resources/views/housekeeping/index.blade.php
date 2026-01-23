@extends('template.master')

@section('title', 'Dashboard Femmes de Chambre')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark">Dashboard Femmes de Chambre</h1>
                    <p class="text-muted mb-0">Gestion du nettoyage et maintenance des chambres</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('housekeeping.mobile') }}" class="btn btn-outline-primary">
                        <i class="fas fa-mobile-alt me-2"></i>
                        Vue Mobile
                    </a>
                    <a href="{{ route('housekeeping.daily-report') }}" class="btn btn-primary">
                        <i class="fas fa-file-alt me-2"></i>
                        Rapport Quotidien
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Total</h6>
                            <h3 class="fw-bold text-dark">{{ $stats['total_rooms'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-bed fa-2x text-secondary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">À nettoyer</h6>
                            <h3 class="fw-bold text-danger">{{ $stats['dirty_rooms'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-broom fa-2x text-danger opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">En nettoyage</h6>
                            <h3 class="fw-bold text-warning">{{ $stats['cleaning_rooms'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-spinner fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Nettoyées</h6>
                            <h3 class="fw-bold text-success">{{ $stats['clean_rooms'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Occupées</h6>
                            <h3 class="fw-bold text-info">{{ $stats['occupied_rooms'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Maintenance</h6>
                            <h3 class="fw-bold text-purple">{{ $stats['maintenance_rooms'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-tools fa-2x text-purple opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Colonne gauche: Chambres par statut -->
        <div class="col-md-8">
            <!-- Chambres à nettoyer -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-broom me-2"></i>
                            <strong>Chambres à nettoyer ({{ $roomsByStatus['dirty']->count() }})</strong>
                        </div>
                        <a href="{{ route('housekeeping.to-clean') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-list me-1"></i>
                            Voir tout
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($roomsByStatus['dirty']->count() > 0)
                        <div class="row">
                            @foreach($roomsByStatus['dirty']->take(12) as $room)
                            <div class="col-md-3 mb-3">
                                <div class="card border-danger h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="fw-bold mb-0 text-danger">
                                                Chambre {{ $room->number }}
                                            </h6>
                                            <span class="badge bg-danger">
                                                <i class="fas fa-broom"></i>
                                            </span>
                                        </div>
                                        <small class="text-muted d-block mb-2">
                                            <i class="fas fa-layer-group me-1"></i>
                                            {{ $room->type->name ?? 'Standard' }}
                                        </small>
                                        <div class="d-grid">
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#startCleaningModal{{ $room->id }}">
                                                Démarrer nettoyage
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if($roomsByStatus['dirty']->count() > 12)
                            <div class="text-center mt-3">
                                <a href="{{ route('housekeeping.to-clean') }}" class="btn btn-sm btn-outline-danger">
                                    Voir les {{ $roomsByStatus['dirty']->count() - 12 }} autres chambres
                                    <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h6 class="text-dark">Aucune chambre à nettoyer</h6>
                            <p class="text-muted small">Toutes les chambres sont propres</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Chambres en nettoyage -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-spinner me-2"></i>
                            <strong>En cours de nettoyage ({{ $roomsByStatus['cleaning']->count() }})</strong>
                        </div>
                        <a href="{{ route('housekeeping.quick-list', 'cleaning') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-list me-1"></i>
                            Voir tout
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($roomsByStatus['cleaning']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Chambre</th>
                                        <th>Type</th>
                                        <th>Démarré à</th>
                                        <th>Durée</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roomsByStatus['cleaning']->take(10) as $room)
                                    <tr>
                                        <td>
                                            <span class="badge bg-warning">{{ $room->number }}</span>
                                        </td>
                                        <td>{{ $room->type->name ?? 'Standard' }}</td>
                                        <td>
                                            @if($room->cleaning_started_at)
                                                {{ $room->cleaning_started_at->format('H:i') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if($room->cleaning_started_at)
                                                {{ now()->diffForHumans($room->cleaning_started_at, true) }}
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('housekeeping.mark-cleaned', $room->id) }}" 
                                                  method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check me-1"></i>
                                                    Terminer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                            <h6 class="text-dark">Aucune chambre en nettoyage</h6>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Chambres nettoyées aujourd'hui -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Chambres nettoyées aujourd'hui ({{ $roomsCleanedToday }})</strong>
                        </div>
                        <span class="badge bg-light text-success">{{ now()->format('d/m/Y') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-success mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-trophy fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Excellent travail !</h6>
                                <p class="mb-0">
                                    Vous avez nettoyé <strong>{{ $roomsCleanedToday }}</strong> chambres aujourd'hui.
                                    @if($roomsCleanedToday > 0)
                                        Continue comme ça !
                                    @else
                                        Commencez par nettoyer les chambres à nettoyer.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite: Arrivées et départs -->
        <div class="col-md-4">
            <!-- Départs du jour -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-sign-out-alt me-2"></i>
                            <strong>Départs aujourd'hui ({{ $todayDepartures->count() }})</strong>
                        </div>
                        <span class="badge bg-light text-info">{{ now()->format('d/m') }}</span>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todayDepartures->take(5) as $departure)
                                    <tr>
                                        <td>
                                            <span class="badge bg-info">{{ $departure->room->number }}</span>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 100px;">
                                                {{ $departure->customer->name }}
                                            </div>
                                        </td>
                                        <td>{{ $departure->check_out->format('H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($todayDepartures->count() > 5)
                            <div class="text-center mt-2">
                                <a href="#" class="btn btn-sm btn-outline-info">
                                    Voir les {{ $todayDepartures->count() - 5 }} autres départs
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-calendar-check fa-2x text-muted mb-2"></i>
                            <h6 class="text-dark">Aucun départ aujourd'hui</h6>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Arrivées du jour -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-sign-in-alt me-2"></i>
                            <strong>Arrivées aujourd'hui ({{ $todayArrivals->count() }})</strong>
                        </div>
                        <span class="badge bg-light text-primary">{{ now()->format('d/m') }}</span>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todayArrivals->take(5) as $arrival)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $arrival->room->number }}</span>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 100px;">
                                                {{ $arrival->customer->name }}
                                            </div>
                                        </td>
                                        <td>{{ $arrival->check_in->format('H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($todayArrivals->count() > 5)
                            <div class="text-center mt-2">
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    Voir les {{ $todayArrivals->count() - 5 }} autres arrivées
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                            <h6 class="text-dark">Aucune arrivée aujourd'hui</h6>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-bolt me-2"></i>
                    <strong>Actions rapides</strong>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('housekeeping.scan') }}" class="btn btn-outline-primary">
                            <i class="fas fa-qrcode me-2"></i>
                            Scanner QR Code
                        </a>
                        <a href="{{ route('housekeeping.mobile') }}" class="btn btn-outline-info">
                            <i class="fas fa-mobile-alt me-2"></i>
                            Vue Mobile
                        </a>
                        <a href="{{ route('housekeeping.reports') }}" class="btn btn-outline-success">
                            <i class="fas fa-chart-bar me-2"></i>
                            Rapports
                        </a>
                        <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#maintenanceModal">
                            <i class="fas fa-tools me-2"></i>
                            Signaler Maintenance
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation rapide -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <i class="fas fa-th me-2"></i>
                    <strong>Navigation rapide</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <a href="{{ route('housekeeping.to-clean') }}" 
                               class="btn btn-danger w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-broom fa-2x mb-2"></i>
                                <div>À nettoyer</div>
                                <small class="text-light">{{ $stats['dirty_rooms'] }} chambres</small>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('housekeeping.quick-list', 'cleaning') }}" 
                               class="btn btn-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-spinner fa-2x mb-2"></i>
                                <div>En nettoyage</div>
                                <small>{{ $stats['cleaning_rooms'] }} chambres</small>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('housekeeping.quick-list', 'clean') }}" 
                               class="btn btn-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <div>Nettoyées</div>
                                <small class="text-light">{{ $stats['clean_rooms'] }} chambres</small>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('housekeeping.quick-list', 'occupied') }}" 
                               class="btn btn-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <div>Occupées</div>
                                <small>{{ $stats['occupied_rooms'] }} chambres</small>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('housekeeping.maintenance') }}" 
                               class="btn btn-purple w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-tools fa-2x mb-2"></i>
                                <div>Maintenance</div>
                                <small class="text-light">{{ $stats['maintenance_rooms'] }} chambres</small>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('housekeeping.daily-report') }}" 
                               class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-file-alt fa-2x mb-2"></i>
                                <div>Rapport</div>
                                <small>Quotidien</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Maintenance -->
<div class="modal fade" id="maintenanceModal" tabindex="-1" aria-labelledby="maintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="maintenanceModalLabel">
                    <i class="fas fa-tools me-2"></i>
                    Signaler une maintenance
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" id="maintenanceForm">
                    @csrf
                    <div class="mb-3">
                        <label for="room_number" class="form-label">Numéro de chambre</label>
                        <input type="text" class="form-control" id="room_number" 
                               placeholder="Ex: 101, 102..." required>
                    </div>
                    <div class="mb-3">
                        <label for="maintenance_reason" class="form-label">Raison de la maintenance</label>
                        <select class="form-select" id="maintenance_reason" name="maintenance_reason" required>
                            <option value="">Sélectionner une raison</option>
                            <option value="Électricité">Problème électrique</option>
                            <option value="Plomberie">Fuite d'eau</option>
                            <option value="Climatisation">Climatisation défectueuse</option>
                            <option value="Meuble">Meuble cassé</option>
                            <option value="Sécurité">Problème de sécurité</option>
                            <option value="Nettoyage profond">Nettoyage profond nécessaire</option>
                            <option value="Autre">Autre raison</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="estimated_duration" class="form-label">Durée estimée (heures)</label>
                        <input type="number" class="form-control" id="estimated_duration" 
                               name="estimated_duration" min="1" max="48" value="2">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-warning" form="maintenanceForm">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Signaler
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modals pour démarrer le nettoyage -->
@foreach($roomsByStatus['dirty'] as $room)
<div class="modal fade" id="startCleaningModal{{ $room->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-broom me-2"></i>
                    Démarrer le nettoyage
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Confirmer le début du nettoyage pour la <strong>chambre {{ $room->number }}</strong> ?</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Cette action changera le statut de la chambre en "En nettoyage".
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('housekeeping.start-cleaning', $room->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-broom me-2"></i>
                        Démarrer nettoyage
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('styles')
<style>
    .text-purple {
        color: #6f42c1 !important;
    }
    
    .btn-purple {
        background-color: #6f42c1;
        border-color: #6f42c1;
        color: white;
    }
    
    .btn-purple:hover {
        background-color: #5a32a3;
        border-color: #5a32a3;
        color: white;
    }
    
    .btn-purple.text-light {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    
    .card-header.bg-danger,
    .card-header.bg-warning,
    .card-header.bg-success,
    .card-header.bg-info,
    .card-header.bg-primary,
    .card-header.bg-dark,
    .card-header.bg-secondary {
        background: linear-gradient(135deg, var(--bs-danger), #dc2626) !important;
    }
    
    .card-header.bg-warning {
        background: linear-gradient(135deg, var(--bs-warning), #e0a800) !important;
    }
    
    .card-header.bg-success {
        background: linear-gradient(135deg, var(--bs-success), #198754) !important;
    }
    
    .card-header.bg-info {
        background: linear-gradient(135deg, var(--bs-info), #0aa2c0) !important;
    }
    
    .card-header.bg-primary {
        background: linear-gradient(135deg, var(--bs-primary), #0a58ca) !important;
    }
    
    .card-header.bg-dark {
        background: linear-gradient(135deg, var(--bs-dark), #1a1e21) !important;
    }
    
    .card-header.bg-secondary {
        background: linear-gradient(135deg, var(--bs-secondary), #64748b) !important;
    }
    
    .card.border-danger:hover {
        box-shadow: 0 0.5rem 1rem rgba(220, 53, 69, 0.15);
        transform: translateY(-2px);
        transition: all 0.3s ease;
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
    
    // Gestion du formulaire de maintenance
    const maintenanceForm = document.getElementById('maintenanceForm');
    if (maintenanceForm) {
        maintenanceForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const roomNumber = document.getElementById('room_number').value;
            const reason = document.getElementById('maintenance_reason').value;
            const duration = document.getElementById('estimated_duration').value;
            
            // Ici, vous devriez faire une requête AJAX pour soumettre le formulaire
            // Pour l'instant, on affiche juste un message
            alert(`Maintenance signalée pour la chambre ${roomNumber}\nRaison: ${reason}\nDurée estimée: ${duration} heures`);
            
            // Fermer le modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('maintenanceModal'));
            modal.hide();
        });
    }
    
    // Rafraîchissement automatique toutes les 30 secondes
    setTimeout(function() {
        window.location.reload();
    }, 30000);
});
</script>
@endpush