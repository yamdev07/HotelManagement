@extends('template.master')
@section('title', 'Détails de l\'activité #' . $activity->id)
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="fas fa-info-circle me-2 text-info"></i>
                                Détails de l'activité #{{ $activity->id }}
                            </h4>
                            <p class="text-muted mb-0">Informations complètes sur cette action</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('activity.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                            <button onclick="window.print()" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-print me-1"></i> Imprimer
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Informations principales -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Informations générales</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="140">ID :</th>
                                            <td>{{ $activity->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date et heure :</th>
                                            <td>{{ $activity->created_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Description :</th>
                                            <td class="fw-semibold">{{ $activity->description }}</td>
                                        </tr>
                                        <tr>
                                            <th>Événement :</th>
                                            <td>
                                                @php
                                                    $badgeClass = match($activity->event) {
                                                        'created' => 'badge-success',
                                                        'updated' => 'badge-warning',
                                                        'deleted' => 'badge-danger',
                                                        'restored' => 'badge-info',
                                                        default => 'badge-secondary'
                                                    };
                                                    $eventLabel = match($activity->event) {
                                                        'created' => 'Création',
                                                        'updated' => 'Modification',
                                                        'deleted' => 'Suppression',
                                                        'restored' => 'Restauration',
                                                        default => ucfirst($activity->event)
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ $eventLabel }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Log Name :</th>
                                            <td><code>{{ $activity->log_name }}</code></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Utilisateur -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Utilisateur</h5>
                                </div>
                                <div class="card-body">
                                    @if($activity->causer)
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                {{ substr($activity->causer->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h5 class="mb-1">{{ $activity->causer->name }}</h5>
                                                <p class="text-muted mb-0">{{ $activity->causer->email }}</p>
                                            </div>
                                        </div>
                                        <table class="table table-sm">
                                            <tr>
                                                <th width="100">ID :</th>
                                                <td>{{ $activity->causer_id }}</td>
                                            </tr>
                                            <tr>
                                                <th>Type :</th>
                                                <td><code>{{ $activity->causer_type }}</code></td>
                                            </tr>
                                        </table>
                                    @else
                                        <div class="text-center text-muted py-3">
                                            <i class="fas fa-user-slash fa-2x mb-2"></i>
                                            <p>Aucun utilisateur associé (action système)</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Objet concerné -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Objet concerné</h5>
                                </div>
                                <div class="card-body">
                                    @if($activity->subject)
                                        @php
                                            $modelName = class_basename($activity->subject_type);
                                            $modelIcon = match($modelName) {
                                                'User' => 'fa-user text-primary',
                                                'Facility' => 'fa-cogs text-warning',
                                                'Room' => 'fa-door-closed text-success',
                                                'Type' => 'fa-tag text-info',
                                                'Customer' => 'fa-users text-secondary',
                                                'Transaction' => 'fa-receipt text-danger',
                                                'Payment' => 'fa-credit-card text-success',
                                                default => 'fa-cube text-muted'
                                            };
                                        @endphp
                                        
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas {{ $modelIcon }} fa-2x me-3"></i>
                                            <div>
                                                <h5 class="mb-1">{{ $modelName }}</h5>
                                                <p class="text-muted mb-0">ID: {{ $activity->subject_id }}</p>
                                            </div>
                                        </div>
                                        
                                        @if(method_exists($activity->subject, 'getNameAttribute'))
                                            <div class="mb-3">
                                                <strong>Nom :</strong> {{ $activity->subject->name }}
                                            </div>
                                        @endif
                                        
                                        @if($activity->subject->exists)
                                            <a href="{{ $this->getSubjectUrl($activity->subject) ?? '#' }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-external-link-alt me-1"></i> Voir l'objet
                                            </a>
                                        @endif
                                    @else
                                        <div class="text-center text-muted py-3">
                                            <i class="fas fa-trash-alt fa-2x mb-2"></i>
                                            <p>Objet supprimé ou introuvable</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Informations techniques -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Informations techniques</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="120">IP Address :</th>
                                            <td>
                                                <code>{{ $activity->properties['ip_address'] ?? 'N/A' }}</code>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>User Agent :</th>
                                            <td>
                                                <small class="text-muted">{{ $activity->properties['user_agent'] ?? 'N/A' }}</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>URL :</th>
                                            <td>
                                                <small>{{ $activity->properties['url'] ?? 'N/A' }}</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Méthode :</th>
                                            <td>
                                                <span class="badge bg-secondary">{{ $activity->properties['method'] ?? 'N/A' }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Propriétés complètes -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Propriétés complètes</h5>
                                    <small class="text-muted">{{ $activity->properties->count() }} propriétés</small>
                                </div>
                                <div class="card-body">
                                    @if($activity->properties->count() > 0)
                                        <pre class="bg-light p-3 rounded" style="max-height: 500px; overflow: auto; font-size: 0.85em;">{{ json_encode($activity->properties->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    @else
                                        <div class="text-center text-muted py-3">
                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                            <p>Aucune propriété supplémentaire</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.avatar-lg {
    width: 60px;
    height: 60px;
    font-size: 24px;
    font-weight: 600;
}
pre {
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    line-height: 1.4;
}
.card {
    border-radius: 8px;
    border: 1px solid #e9ecef;
}
.table th {
    font-weight: 600;
    color: #495057;
}
</style>
@endpush

@push('scripts')
<script>
// Ajoutez la fonction pour obtenir l'URL de l'objet si nécessaire
function getSubjectUrl(subjectType, subjectId) {
    const routes = {
        'App\\Models\\User': '/user/',
        'App\\Models\\Room': '/room/',
        'App\\Models\\Customer': '/customer/',
        'App\\Models\\Transaction': '/transaction/',
        'App\\Models\\Payment': '/payment/',
        'App\\Models\\Facility': '/facility/',
        'App\\Models\\Type': '/type/'
    };
    
    return routes[subjectType] ? routes[subjectType] + subjectId : '#';
}

// Si vous avez besoin de cette fonctionnalité
document.addEventListener('DOMContentLoaded', function() {
    const subjectLinks = document.querySelectorAll('.subject-link');
    subjectLinks.forEach(link => {
        const subjectType = link.dataset.type;
        const subjectId = link.dataset.id;
        link.href = getSubjectUrl(subjectType, subjectId);
    });
});
</script>
@endpush
@endsection