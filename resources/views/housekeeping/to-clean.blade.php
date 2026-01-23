@extends('template.master')

@section('title', 'Chambres à Nettoyer')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark">
                        <i class="fas fa-broom me-2 text-danger"></i>
                        Chambres à Nettoyer
                    </h1>
                    <p class="text-muted mb-0">Liste complète des chambres nécessitant un nettoyage</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('housekeeping.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour
                    </a>
                    <a href="{{ route('housekeeping.mobile') }}" class="btn btn-outline-primary">
                        <i class="fas fa-mobile-alt me-2"></i>
                        Vue Mobile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">À nettoyer</h6>
                            <h3 class="fw-bold text-danger">{{ $stats['total_to_clean'] }}</h3>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Mise à jour: {{ now()->format('H:i') }}
                            </small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-broom fa-3x text-danger opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Sales</h6>
                            <h3 class="fw-bold text-warning">{{ $stats['dirty'] }}</h3>
                            <small class="text-muted">Statut: À nettoyer</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-3x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Départs</h6>
                            <h3 class="fw-bold text-info">{{ $stats['departing_today'] }}</h3>
                            <small class="text-muted">Départs aujourd'hui</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-sign-out-alt fa-3x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des chambres -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-danger text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-list me-2"></i>
                            <strong>Liste des chambres à nettoyer</strong>
                        </div>
                        <span class="badge bg-light text-danger">{{ $rooms->count() }} chambres</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($rooms->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="100">Chambre</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th>Client actuel</th>
                                        <th>Départ</th>
                                        <th>Dernier nettoyage</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rooms as $room)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-danger rounded-circle p-2 me-2">
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
                                            @if($room->room_status_id == \App\Models\Room::STATUS_CLEANING)
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-broom me-1"></i>
                                                    À nettoyer
                                                </span>
                                            @elseif($room->activeTransactions->count() > 0)
                                                <span class="badge bg-info">
                                                    <i class="fas fa-users me-1"></i>
                                                    Occupée
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-question me-1"></i>
                                                    Inconnu
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($room->activeTransactions->count() > 0)
                                                @foreach($room->activeTransactions as $transaction)
                                                    <div class="text-truncate" style="max-width: 150px;">
                                                        <i class="fas fa-user me-1 text-muted"></i>
                                                        {{ $transaction->customer->name ?? 'Client' }}
                                                    </div>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Vacante</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($room->activeTransactions->count() > 0)
                                                @foreach($room->activeTransactions as $transaction)
                                                    @if($transaction->check_out)
                                                        <div>
                                                            <span class="badge bg-info">
                                                                {{ $transaction->check_out->format('H:i') }}
                                                            </span>
                                                            <small class="text-muted d-block">
                                                                {{ $transaction->check_out->format('d/m/Y') }}
                                                            </small>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($room->last_cleaned_at)
                                                <span class="text-muted">
                                                    {{ $room->last_cleaned_at->format('d/m H:i') }}
                                                </span>
                                            @else
                                                <span class="text-danger">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    Jamais
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group" role="group">
                                                <form action="{{ route('housekeeping.start-cleaning', $room->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-broom me-1"></i>
                                                        Démarrer
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                        data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="fas fa-eye me-2"></i>
                                                            Détails
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" 
                                                           href="{{ route('housekeeping.show-maintenance-form', $room->id) }}">
                                                            <i class="fas fa-tools me-2"></i>
                                                            Maintenance
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('housekeeping.mark-inspection', $room->id) }}" 
                                                              method="POST">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-clipboard-check me-2"></i>
                                                                Demander inspection
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-info" href="#">
                                                            <i class="fas fa-qrcode me-2"></i>
                                                            Scanner QR Code
                                                        </a>
                                                    </li>
                                                </ul>
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
                            <h4 class="text-dark mb-2">Excellent travail !</h4>
                            <p class="text-muted mb-4">Toutes les chambres sont propres</p>
                            <a href="{{ route('housekeeping.index') }}" class="btn btn-success">
                                <i class="fas fa-home me-2"></i>
                                Retour au dashboard
                            </a>
                        </div>
                    @endif
                </div>
                @if($rooms->count() > 0)
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Cliquez sur "Démarrer" pour commencer le nettoyage d'une chambre
                                </small>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-print me-1"></i>
                                    Imprimer la liste
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        Procédure de nettoyage
                    </h6>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li class="mb-2">Vérifier le matériel de nettoyage</li>
                        <li class="mb-2">Scanner le QR code de la chambre</li>
                        <li class="mb-2">Suivre le protocole de nettoyage standard</li>
                        <li class="mb-2">Signaler tout problème au superviseur</li>
                        <li>Marquer la chambre comme nettoyée</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                        Chambres prioritaires
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning mb-0">
                        <div class="d-flex">
                            <i class="fas fa-star me-3 fa-lg mt-1"></i>
                            <div>
                                <h6 class="alert-heading">Ordre de priorité :</h6>
                                <ol class="mb-0">
                                    <li>Chambres avec départs matinaux</li>
                                    <li>Chambres sales (statut "À nettoyer")</li>
                                    <li>Chambres d'arrivées prévues</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh toutes les 60 secondes
    setTimeout(function() {
        window.location.reload();
    }, 60000);
    
    // Confirmation avant démarrage
    const startButtons = document.querySelectorAll('form[action*="start-cleaning"]');
    startButtons.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Démarrer le nettoyage de cette chambre ?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush