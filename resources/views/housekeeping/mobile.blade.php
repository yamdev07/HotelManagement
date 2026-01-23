@extends('template.master')

@section('title', 'Vue Mobile - Femmes de Chambre')

@section('content')
<div class="container-fluid">
    <!-- En-tête mobile -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark">
                        <i class="fas fa-mobile-alt me-2"></i>
                        Vue Mobile
                    </h1>
                    <p class="text-muted mb-0">Interface simplifiée pour le nettoyage</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('housekeeping.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-desktop me-2"></i>
                        Vue Desktop
                    </a>
                    <a href="{{ route('housekeeping.scan') }}" class="btn btn-success">
                        <i class="fas fa-qrcode me-2"></i>
                        Scanner
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-3">
                    <div class="text-danger fw-bold fs-2">{{ $stats['dirty'] }}</div>
                    <small class="text-muted">À nettoyer</small>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-3">
                    <div class="text-warning fw-bold fs-2">{{ $stats['cleaning'] }}</div>
                    <small class="text-muted">En cours</small>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-3">
                    <div class="text-success fw-bold fs-2">{{ $stats['cleaned_today'] }}</div>
                    <small class="text-muted">Nettoyées</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Chambres à nettoyer -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-broom me-2"></i>
                            <strong>À nettoyer ({{ $dirtyRooms->count() }})</strong>
                        </div>
                        <span class="badge bg-light text-danger">{{ now()->format('H:i') }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($dirtyRooms->count() > 0)
                        <div class="list-group">
                            @foreach($dirtyRooms as $room)
                            <div class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 fw-bold text-danger">
                                            <i class="fas fa-bed me-2"></i>
                                            Chambre {{ $room->number }}
                                        </h6>
                                        <small class="text-muted">
                                            {{ $room->type->name ?? 'Standard' }}
                                            @if($room->last_cleaned_at)
                                                · Nettoyée: {{ $room->last_cleaned_at->diffForHumans() }}
                                            @endif
                                        </small>
                                    </div>
                                    <div>
                                        <form action="{{ route('housekeeping.start-cleaning', $room->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h6 class="text-dark">Tout est propre !</h6>
                            <p class="text-muted small">Aucune chambre à nettoyer</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Chambres en nettoyage -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-spinner me-2"></i>
                            <strong>En cours de nettoyage ({{ $cleaningRooms->count() }})</strong>
                        </div>
                        <span class="badge bg-light text-warning">{{ now()->format('H:i') }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($cleaningRooms->count() > 0)
                        <div class="list-group">
                            @foreach($cleaningRooms as $room)
                            @php
                                $startTime = $room->cleaning_started_at ?? now();
                                $duration = $startTime->diffForHumans(now(), true);
                            @endphp
                            <div class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 fw-bold text-warning">
                                            <i class="fas fa-bed me-2"></i>
                                            Chambre {{ $room->number }}
                                        </h6>
                                        <small class="text-muted">
                                            {{ $room->type->name ?? 'Standard' }}
                                            <span class="badge bg-warning ms-2">{{ $duration }}</span>
                                        </small>
                                    </div>
                                    <div>
                                        <form action="{{ route('housekeeping.mark-cleaned', $room->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                            <h6 class="text-dark">Aucune chambre en nettoyage</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-bolt me-2"></i>
                    <strong>Actions rapides</strong>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('housekeeping.scan') }}" 
                               class="btn btn-success w-100 d-flex flex-column align-items-center p-3">
                                <i class="fas fa-qrcode fa-2x mb-2"></i>
                                <div>Scanner</div>
                            </a>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-warning w-100 d-flex flex-column align-items-center p-3"
                                    data-bs-toggle="modal" data-bs-target="#maintenanceModal">
                                <i class="fas fa-tools fa-2x mb-2"></i>
                                <div>Maintenance</div>
                            </button>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('housekeeping.to-clean') }}" 
                               class="btn btn-danger w-100 d-flex flex-column align-items-center p-3">
                                <i class="fas fa-list fa-2x mb-2"></i>
                                <div>Liste complète</div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('housekeeping.reports') }}" 
                               class="btn btn-info w-100 d-flex flex-column align-items-center p-3">
                                <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                <div>Rapports</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Résumé du jour -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-calendar-day me-2"></i>
                    <strong>Résumé du jour</strong>
                </div>
                <div class="card-body">
                    <div class="alert alert-success mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-trophy fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Bon travail aujourd'hui !</h6>
                                <p class="mb-0">
                                    Vous avez nettoyé <strong>{{ $stats['cleaned_today'] }}</strong> chambres.
                                    @if($stats['dirty'] > 0)
                                        Il reste <strong>{{ $stats['dirty'] }}</strong> chambres à nettoyer.
                                    @else
                                        Toutes les chambres sont propres !
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Maintenance -->
<div class="modal fade" id="maintenanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-tools me-2"></i>
                    Signaler une maintenance
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Numéro de chambre</label>
                    <input type="text" class="form-control" id="mobileRoomNumber" 
                           placeholder="Ex: 101, 102..." maxlength="10">
                </div>
                <div class="mb-3">
                    <label class="form-label">Problème</label>
                    <select class="form-select" id="mobileProblem">
                        <option value="">Sélectionner...</option>
                        <option value="Électricité">Problème électrique</option>
                        <option value="Plomberie">Fuite d'eau</option>
                        <option value="Climatisation">Climatisation</option>
                        <option value="Meuble">Meuble cassé</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-warning" onclick="submitMobileMaintenance()">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Signaler
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .list-group-item {
        border-left: 4px solid transparent;
        border-radius: 0 !important;
    }
    
    .list-group-item:hover {
        border-left-color: #0d6efd;
    }
    
    .btn {
        border-radius: 10px;
    }
    
    .card-header.bg-danger,
    .card-header.bg-warning,
    .card-header.bg-primary,
    .card-header.bg-success {
        background: linear-gradient(135deg, var(--bs-danger), #dc2626) !important;
    }
    
    .card-header.bg-warning {
        background: linear-gradient(135deg, var(--bs-warning), #e0a800) !important;
    }
    
    .card-header.bg-primary {
        background: linear-gradient(135deg, var(--bs-primary), #0a58ca) !important;
    }
    
    .card-header.bg-success {
        background: linear-gradient(135deg, var(--bs-success), #198754) !important;
    }
</style>
@endpush

@push('scripts')
<script>
function submitMobileMaintenance() {
    const roomNumber = document.getElementById('mobileRoomNumber').value;
    const problem = document.getElementById('mobileProblem').value;
    
    if (!roomNumber || !problem) {
        alert('Veuillez remplir tous les champs');
        return;
    }
    
    // Rechercher la chambre par son numéro
    fetch(`/api/rooms/find-by-number/${roomNumber}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Soumettre le formulaire de maintenance
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/housekeeping/${data.room.id}/mark-maintenance`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                const reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'maintenance_reason';
                reasonInput.value = problem;
                form.appendChild(reasonInput);
                
                const durationInput = document.createElement('input');
                durationInput.type = 'hidden';
                durationInput.name = 'estimated_duration';
                durationInput.value = 4;
                form.appendChild(durationInput);
                
                document.body.appendChild(form);
                form.submit();
            } else {
                alert('Chambre non trouvée');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la recherche de la chambre');
        });
}

document.addEventListener('DOMContentLoaded', function() {
    // Rafraîchissement automatique toutes les 30 secondes
    setInterval(function() {
        fetch('{{ route("housekeeping.mobile") }}?partial=true')
            .then(response => response.text())
            .then(html => {
                // Mettre à jour seulement certaines parties
                console.log('Vue mobile mise à jour');
            })
            .catch(error => console.error('Erreur rafraîchissement:', error));
    }, 30000);
    
    // Mode sombre/clair automatique
    const hour = new Date().getHours();
    if (hour >= 18 || hour <= 6) {
        document.body.classList.add('bg-dark', 'text-light');
    }
});
</script>
@endpush