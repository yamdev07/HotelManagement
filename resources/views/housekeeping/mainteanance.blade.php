@extends('template.master')

@section('title', 'Chambres en Maintenance')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark">Chambres en Maintenance</h1>
                    <p class="text-muted mb-0">Gestion des chambres en réparation et maintenance</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('housekeeping.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour
                    </a>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addMaintenanceModal">
                        <i class="fas fa-plus-circle me-2"></i>
                        Nouvelle Maintenance
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
                            <h6 class="text-muted mb-1">Total Maintenance</h6>
                            <h3 class="fw-bold text-purple">{{ $stats['total_maintenance'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-tools fa-2x text-purple opacity-50"></i>
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
                            <h6 class="text-muted mb-1">Plus ancienne</h6>
                            <h5 class="fw-bold text-dark">
                                @if($stats['longest_maintenance'])
                                    {{ \Carbon\Carbon::parse($stats['longest_maintenance'])->diffForHumans() }}
                                @else
                                    N/A
                                @endif
                            </h5>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-3">Maintenance par raison</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($stats['maintenance_by_reason'] as $reason => $count)
                        <span class="badge bg-light text-dark p-2">
                            {{ $reason }}: <strong class="text-purple">{{ $count }}</strong>
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des chambres en maintenance -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-purple text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-tools me-2"></i>
                            <strong>Chambres en maintenance ({{ $maintenanceRooms->count() }})</strong>
                        </div>
                        <div class="text-light">
                            <small>Mis à jour: {{ now()->format('H:i') }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($maintenanceRooms->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-3">Chambre</th>
                                        <th class="py-3">Type</th>
                                        <th class="py-3">Raison</th>
                                        <th class="py-3">Début</th>
                                        <th class="py-3">Durée</th>
                                        <th class="py-3">Demandé par</th>
                                        <th class="py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($maintenanceRooms as $room)
                                    @php
                                        $startDate = \Carbon\Carbon::parse($room->maintenance_started_at);
                                        $duration = $startDate->diffForHumans(now(), true);
                                        $isLongTerm = $startDate->diffInDays(now()) > 3;
                                    @endphp
                                    <tr class="{{ $isLongTerm ? 'table-warning' : '' }}">
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="room-badge bg-purple me-3">
                                                    {{ $room->number }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $room->type->name ?? 'Standard' }}</div>
                                                    <small class="text-muted">Étage: {{ $room->floor ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            {{ $room->type->name ?? 'Standard' }}
                                        </td>
                                        <td class="py-3">
                                            <div class="text-truncate" style="max-width: 200px;" 
                                                 data-bs-toggle="tooltip" 
                                                 title="{{ $room->maintenance_reason }}">
                                                {{ $room->maintenance_reason }}
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="fw-bold">{{ $startDate->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $startDate->format('H:i') }}</small>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge bg-{{ $isLongTerm ? 'warning' : 'info' }}">
                                                {{ $duration }}
                                            </span>
                                            @if($room->estimated_maintenance_duration)
                                                <small class="d-block text-muted">
                                                    Estimé: {{ $room->estimated_maintenance_duration }}h
                                                </small>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            @php
                                                $requestedBy = \App\Models\User::find($room->maintenance_requested_by);
                                            @endphp
                                            @if($requestedBy)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <div class="avatar-title bg-light rounded-circle text-dark">
                                                            {{ substr($requestedBy->name, 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $requestedBy->name }}</div>
                                                        <small class="text-muted">{{ $requestedBy->role }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Inconnu</span>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#detailsModal{{ $room->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="{{ route('housekeeping.maintenance-form', $room->id) }}"
                                                   class="btn btn-sm btn-outline-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('housekeeping.end-maintenance', $room->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-success"
                                                            onclick="return confirm('Terminer la maintenance de la chambre {{ $room->number }} ?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-4x text-success mb-4"></i>
                            <h4 class="text-dark mb-3">Aucune chambre en maintenance</h4>
                            <p class="text-muted mb-4">Toutes les chambres sont opérationnelles</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMaintenanceModal">
                                <i class="fas fa-plus-circle me-2"></i>
                                Ajouter une maintenance
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Résumé -->
    @if($maintenanceRooms->count() > 0)
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Maintenance longue durée (> 3 jours)</strong>
                </div>
                <div class="card-body">
                    @php
                        $longTerm = $maintenanceRooms->filter(function($room) {
                            return \Carbon\Carbon::parse($room->maintenance_started_at)->diffInDays(now()) > 3;
                        });
                    @endphp
                    @if($longTerm->count() > 0)
                        <div class="list-group">
                            @foreach($longTerm as $room)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 fw-bold">Chambre {{ $room->number }}</h6>
                                        <small class="text-muted">{{ $room->maintenance_reason }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-danger">
                                            {{ \Carbon\Carbon::parse($room->maintenance_started_at)->diffForHumans(now(), true) }}
                                        </span>
                                        <div class="mt-1">
                                            <small class="text-muted">
                                                Début: {{ \Carbon\Carbon::parse($room->maintenance_started_at)->format('d/m/Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-check fa-2x text-success mb-2"></i>
                            <h6 class="text-dark">Aucune maintenance longue durée</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-chart-pie me-2"></i>
                    <strong>Répartition par type de chambre</strong>
                </div>
                <div class="card-body">
                    @php
                        $byType = $maintenanceRooms->groupBy(function($room) {
                            return $room->type->name ?? 'Inconnu';
                        })->map->count();
                    @endphp
                    @if($byType->count() > 0)
                        <div class="row">
                            @foreach($byType as $type => $count)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">{{ $type }}</span>
                                    <span class="badge bg-info">{{ $count }}</span>
                                </div>
                                <div class="progress mt-1" style="height: 8px;">
                                    @php
                                        $percentage = ($count / $maintenanceRooms->count()) * 100;
                                    @endphp
                                    <div class="progress-bar bg-info" 
                                         style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-chart-pie fa-2x text-muted mb-2"></i>
                            <h6 class="text-dark">Aucune donnée</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal Ajouter Maintenance -->
<div class="modal fade" id="addMaintenanceModal" tabindex="-1" aria-labelledby="addMaintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMaintenanceModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>
                    Ajouter une maintenance
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('housekeeping.mark-maintenance', 0) }}" method="POST" id="addMaintenanceForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="room_select" class="form-label">Chambre *</label>
                        <select class="form-select" id="room_select" name="room_id" required>
                            <option value="">Sélectionner une chambre</option>
                            @php
                                $availableRooms = \App\Models\Room::where('room_status_id', '!=', \App\Models\Room::STATUS_MAINTENANCE)
                                    ->orderBy('number')
                                    ->get();
                            @endphp
                            @foreach($availableRooms as $room)
                                <option value="{{ $room->id }}">
                                    Chambre {{ $room->number }} - {{ $room->type->name ?? 'Standard' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="maintenance_reason" class="form-label">Raison de la maintenance *</label>
                        <select class="form-select" id="maintenance_reason" name="maintenance_reason" required>
                            <option value="">Sélectionner une raison</option>
                            <option value="Électricité">Problème électrique</option>
                            <option value="Plomberie">Fuite d'eau / Plomberie</option>
                            <option value="Climatisation">Climatisation défectueuse</option>
                            <option value="Meuble">Meuble cassé / Réparation</option>
                            <option value="Sécurité">Problème de sécurité (serrure, fenêtre)</option>
                            <option value="Peinture">Peinture / Rénovation</option>
                            <option value="Sol">Sol / Tapis à remplacer</option>
                            <option value="Salle de bain">Salle de bain (sanitaires)</option>
                            <option value="Nettoyage profond">Nettoyage profond nécessaire</option>
                            <option value="Autre">Autre raison</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="estimated_duration" class="form-label">Durée estimée (heures) *</label>
                        <input type="number" class="form-control" id="estimated_duration" 
                               name="estimated_duration" min="1" max="168" value="4" required>
                        <small class="text-muted">Entre 1 et 168 heures (1 semaine)</small>
                    </div>
                    <div class="mb-3">
                        <label for="additional_notes" class="form-label">Notes supplémentaires</label>
                        <textarea class="form-control" id="additional_notes" name="additional_notes" 
                                  rows="2" placeholder="Détails supplémentaires..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-tools me-2"></i>
                        Ajouter la maintenance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals pour détails -->
@foreach($maintenanceRooms as $room)
<div class="modal fade" id="detailsModal{{ $room->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>
                    Détails maintenance - Chambre {{ $room->number }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Chambre</label>
                            <div class="fw-bold fs-5">
                                <span class="badge bg-purple">{{ $room->number }}</span>
                                {{ $room->type->name ?? 'Standard' }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Étage</label>
                            <div class="fw-bold">{{ $room->floor ?? 'N/A' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Capacité</label>
                            <div class="fw-bold">
                                <i class="fas fa-users me-1"></i>
                                {{ $room->capacity }} personnes
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Statut</label>
                            <div>
                                <span class="badge bg-purple p-2">
                                    <i class="fas fa-tools me-1"></i>
                                    EN MAINTENANCE
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Début maintenance</label>
                            <div class="fw-bold">
                                {{ \Carbon\Carbon::parse($room->maintenance_started_at)->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Durée</label>
                            <div class="fw-bold">
                                {{ \Carbon\Carbon::parse($room->maintenance_started_at)->diffForHumans(now(), true) }}
                                @if($room->estimated_maintenance_duration)
                                    <small class="d-block text-muted">
                                        Estimé: {{ $room->estimated_maintenance_duration }} heures
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Raison de la maintenance</label>
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ $room->maintenance_reason }}
                    </div>
                </div>
                
                @if($room->additional_notes)
                <div class="mb-3">
                    <label class="form-label text-muted">Notes supplémentaires</label>
                    <div class="card bg-light">
                        <div class="card-body">
                            {{ $room->additional_notes }}
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Demandé par</label>
                            @php
                                $requestedBy = \App\Models\User::find($room->maintenance_requested_by);
                            @endphp
                            @if($requestedBy)
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg me-3">
                                    <div class="avatar-title bg-light rounded-circle text-dark fs-4">
                                        {{ substr($requestedBy->name, 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $requestedBy->name }}</div>
                                    <div class="text-muted">{{ $requestedBy->email }}</div>
                                    <small class="badge bg-secondary">{{ $requestedBy->role }}</small>
                                </div>
                            </div>
                            @else
                            <span class="text-muted">Inconnu</span>
                            @endif
                        </div>
                    </div>
                    @if($room->maintenance_resolved_by)
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Résolu par</label>
                            @php
                                $resolvedBy = \App\Models\User::find($room->maintenance_resolved_by);
                            @endphp
                            @if($resolvedBy)
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg me-3">
                                    <div class="avatar-title bg-success rounded-circle text-white fs-4">
                                        {{ substr($resolvedBy->name, 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $resolvedBy->name }}</div>
                                    <div class="text-muted">{{ $resolvedBy->email }}</div>
                                    <small class="badge bg-success">{{ $resolvedBy->role }}</small>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <a href="{{ route('housekeeping.maintenance-form', $room->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>
                    Modifier
                </a>
                <form action="{{ route('housekeeping.end-maintenance', $room->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success"
                            onclick="return confirm('Terminer la maintenance de la chambre {{ $room->number }} ?')">
                        <i class="fas fa-check me-2"></i>
                        Terminer
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
    
    .bg-purple {
        background-color: #6f42c1 !important;
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
    
    .room-badge {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    .avatar-sm {
        width: 32px;
        height: 32px;
    }
    
    .avatar-lg {
        width: 50px;
        height: 50px;
    }
    
    .avatar-title {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    .card-header.bg-purple,
    .card-header.bg-warning,
    .card-header.bg-info {
        background: linear-gradient(135deg, var(--bs-purple), #6f42c1) !important;
    }
    
    .card-header.bg-warning {
        background: linear-gradient(135deg, var(--bs-warning), #e0a800) !important;
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
    
    .list-group-item {
        border-left: 4px solid transparent;
    }
    
    .list-group-item:hover {
        border-left-color: #6f42c1;
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
    
    // Gestion du formulaire d'ajout de maintenance
    const addMaintenanceForm = document.getElementById('addMaintenanceForm');
    if (addMaintenanceForm) {
        addMaintenanceForm.addEventListener('submit', function(e) {
            const roomId = document.getElementById('room_select').value;
            const reason = document.getElementById('maintenance_reason').value;
            
            if (!roomId || !reason) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires');
                return false;
            }
            
            // Mettre à jour l'action du formulaire avec l'ID de la chambre
            const action = addMaintenanceForm.getAttribute('action').replace('/0', '/' + roomId);
            addMaintenanceForm.setAttribute('action', action);
        });
    }
    
    // Rafraîchissement automatique toutes les 60 secondes
    setTimeout(function() {
        window.location.reload();
    }, 60000);
    
    // Afficher les statistiques
    console.log('Maintenance: {{ $stats["total_maintenance"] }} chambres');
});
</script>
@endpush