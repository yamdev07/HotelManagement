@extends('template.master')

@section('title', 'Chambres à Inspecter')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark">
                        <i class="fas fa-clipboard-check me-2 text-warning"></i>
                        Inspections Requises
                    </h1>
                    <p class="text-muted mb-0">Chambres nécessitant une inspection de qualité</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('housekeeping.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour
                    </a>
                    <button class="btn btn-warning" onclick="markAllInspected()">
                        <i class="fas fa-check-double me-2"></i>
                        Tout marquer inspecté
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
                            <h6 class="text-muted mb-1">À inspecter</h6>
                            <h2 class="fw-bold text-warning">{{ $inspectionRooms->count() }}</h2>
                            <small class="text-muted">En attente</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clipboard-list fa-2x text-warning opacity-50"></i>
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
                            <h6 class="text-muted mb-1">En attente >24h</h6>
                            <h2 class="fw-bold text-danger">{{ $inspectionRooms->filter(fn($r) => $r->inspection_requested_at && $r->inspection_requested_at->diffInHours(now()) > 24)->count() }}</h2>
                            <small class="text-muted">Priorité haute</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x text-danger opacity-50"></i>
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
                            <h6 class="text-muted mb-1">Inspectées aujourd'hui</h6>
                            <h2 class="fw-bold text-success">{{ \App\Models\Room::where('needs_inspection', false)->whereDate('inspected_at', today())->count() }}</h2>
                            <small class="text-muted">Déjà traitées</small>
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
                            <h6 class="text-muted mb-1">Moyenne d'attente</h6>
                            <h2 class="fw-bold text-info">
                                @php
                                    $avgHours = $inspectionRooms->avg(fn($r) => $r->inspection_requested_at ? $r->inspection_requested_at->diffInHours(now()) : 0);
                                    echo round($avgHours) . 'h';
                                @endphp
                            </h2>
                            <small class="text-muted">Temps d'attente</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-hourglass-half fa-2x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des inspections -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-list me-2"></i>
                            <strong>Liste des chambres à inspecter</strong>
                        </div>
                        <span class="badge bg-dark">{{ $inspectionRooms->count() }} inspections</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($inspectionRooms->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="50" class="text-center">
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th>Chambre</th>
                                        <th>Type</th>
                                        <th>Demandée le</th>
                                        <th>Demandée par</th>
                                        <th>Durée d'attente</th>
                                        <th>Raison</th>
                                        <th>Priorité</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inspectionRooms as $room)
                                    @php
                                        $waitHours = $room->inspection_requested_at ? $room->inspection_requested_at->diffInHours(now()) : 0;
                                        $priority = $waitHours > 48 ? 'danger' : ($waitHours > 24 ? 'warning' : 'info');
                                        $priorityText = $waitHours > 48 ? 'Haute' : ($waitHours > 24 ? 'Moyenne' : 'Basse');
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input room-checkbox" value="{{ $room->id }}">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-warning rounded-circle p-2 me-2">
                                                    <i class="fas fa-door-closed"></i>
                                                </span>
                                                <div>
                                                    <strong class="d-block">{{ $room->number }}</strong>
                                                    <small class="text-muted">Étage: {{ substr($room->number, 0, 1) ?? '?' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $room->type->name ?? 'Standard' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($room->inspection_requested_at)
                                                <div>
                                                    <span class="d-block">{{ $room->inspection_requested_at->format('d/m/Y') }}</span>
                                                    <small class="text-muted">{{ $room->inspection_requested_at->format('H:i') }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">Inconnu</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($room->inspection_requested_by)
                                                <span class="badge bg-info">
                                                    {{ \App\Models\User::find($room->inspection_requested_by)->name ?? 'Inconnu' }}
                                                </span>
                                            @else
                                                <span class="text-muted">Système</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($room->inspection_requested_at)
                                                <span class="badge bg-{{ $priority }}">
                                                    {{ round($waitHours) }} heures
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="fas fa-comment me-1"></i>
                                                Inspection qualité demandée
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $priority }}">
                                                {{ $priorityText }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group" role="group">
                                                <form action="{{ route('housekeeping.complete-inspection', $room->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check me-1"></i>
                                                        Inspecter
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                        onclick="showInspectionModal({{ $room->id }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h4 class="text-dark mb-2">Toutes les inspections sont terminées !</h4>
                            <p class="text-muted mb-4">Aucune chambre ne nécessite d'inspection pour le moment.</p>
                            <a href="{{ route('housekeeping.index') }}" class="btn btn-success">
                                <i class="fas fa-home me-2"></i>
                                Retour au dashboard
                            </a>
                        </div>
                    @endif
                </div>
                @if($inspectionRooms->count() > 0)
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button class="btn btn-sm btn-warning" onclick="markSelectedInspected()">
                                    <i class="fas fa-check me-2"></i>
                                    Marquer sélectionnées comme inspectées
                                </button>
                            </div>
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Priorité: 
                                    <span class="badge bg-danger">Haute (>48h)</span> |
                                    <span class="badge bg-warning">Moyenne (24-48h)</span> |
                                    <span class="badge bg-info">Basse (<24h)</span>
                                </small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Checklist d'inspection -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-clipboard-list me-2 text-primary"></i>
                        Checklist d'inspection standard
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Salle de bain</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Propreté sanitaires
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Robinetterie fonctionnelle
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Sol propre et sec
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Fournitures complètes
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Chambre</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Literie impeccable
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Sol et surfaces propres
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Équipements fonctionnels
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Aération correcte
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'inspection détaillée -->
<div class="modal fade" id="inspectionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-clipboard-check me-2"></i>
                    Inspection détaillée
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="inspectionModalContent">
                <!-- Contenu chargé dynamiquement -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner toutes les chambres
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.room-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
});

// Marquer toutes les inspections comme terminées
function markAllInspected() {
    if (!confirm('Marquer toutes les chambres comme inspectées ?')) {
        return;
    }
    
    const checkboxes = document.querySelectorAll('.room-checkbox');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    // Envoyer une requête pour toutes les marquer
    fetch('{{ route("housekeeping.bulk-complete-inspections") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ room_ids: ids })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Une erreur est survenue');
    });
}

// Marquer les chambres sélectionnées
function markSelectedInspected() {
    const checkboxes = document.querySelectorAll('.room-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Veuillez sélectionner au moins une chambre');
        return;
    }
    
    if (!confirm(`Marquer ${checkboxes.length} chambre(s) comme inspectée(s) ?`)) {
        return;
    }
    
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    // Envoyer une requête pour les marquer
    fetch('{{ route("housekeeping.bulk-complete-inspections") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ room_ids: ids })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Une erreur est survenue');
    });
}

// Afficher le modal d'inspection
function showInspectionModal(roomId) {
    // Simuler un chargement AJAX
    const content = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-2">Chargement des détails...</p>
        </div>
    `;
    
    document.getElementById('inspectionModalContent').innerHTML = content;
    const modal = new bootstrap.Modal(document.getElementById('inspectionModal'));
    modal.show();
    
    // Simuler un appel AJAX
    setTimeout(() => {
        document.getElementById('inspectionModalContent').innerHTML = `
            <form id="detailedInspectionForm" action="{{ route('housekeeping.complete-detailed-inspection', '') }}/${roomId}" method="POST">
                @csrf
                <div class="mb-4">
                    <h6>Chambre 101 - Inspection de qualité</h6>
                    <p class="text-muted">Veuillez vérifier chaque point de la checklist</p>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-bath me-2"></i>
                            Salle de bain
                        </h6>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="bathroom1" name="bathroom[]" value="clean">
                            <label class="form-check-label" for="bathroom1">
                                Propreté des sanitaires
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="bathroom2" name="bathroom[]" value="functioning">
                            <label class="form-check-label" for="bathroom2">
                                Robinetterie fonctionnelle
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="bathroom3" name="bathroom[]" value="floor">
                            <label class="form-check-label" for="bathroom3">
                                Sol propre et sec
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="bathroom4" name="bathroom[]" value="supplies">
                            <label class="form-check-label" for="bathroom4">
                                Fournitures complètes
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-bed me-2"></i>
                            Chambre
                        </h6>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="room1" name="room[]" value="bedding">
                            <label class="form-check-label" for="room1">
                                Literie impeccable
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="room2" name="room[]" value="surfaces">
                            <label class="form-check-label" for="room2">
                                Sol et surfaces propres
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="room3" name="room[]" value="equipment">
                            <label class="form-check-label" for="room3">
                                Équipements fonctionnels
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="room4" name="room[]" value="ventilation">
                            <label class="form-check-label" for="room4">
                                Aération correcte
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="inspection_notes" class="form-label">
                        <i class="fas fa-comment me-2"></i>
                        Notes d'inspection
                    </label>
                    <textarea class="form-control" id="inspection_notes" name="inspection_notes" rows="3" placeholder="Notes supplémentaires..."></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-star me-2"></i>
                        Note globale
                    </label>
                    <div class="rating">
                        <input type="radio" id="star5" name="rating" value="5">
                        <label for="star5">★</label>
                        <input type="radio" id="star4" name="rating" value="4" checked>
                        <label for="star4">★</label>
                        <input type="radio" id="star3" name="rating" value="3">
                        <label for="star3">★</label>
                        <input type="radio" id="star2" name="rating" value="2">
                        <label for="star2">★</label>
                        <input type="radio" id="star1" name="rating" value="1">
                        <label for="star1">★</label>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>
                        Valider l'inspection
                    </button>
                </div>
            </form>
            
            <style>
                .rating {
                    direction: rtl;
                    unicode-bidi: bidi-override;
                }
                .rating input {
                    display: none;
                }
                .rating label {
                    color: #ddd;
                    font-size: 2rem;
                    padding: 0 5px;
                    cursor: pointer;
                }
                .rating input:checked ~ label,
                .rating label:hover,
                .rating label:hover ~ label {
                    color: #ffc107;
                }
            </style>
        `;
    }, 500);
}
</script>
@endpush