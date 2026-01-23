@extends('template.master')
@section('title', 'Check-in - Recherche')
@section('content')
    <style>
        .search-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            padding: 30px;
            color: white;
            margin-bottom: 30px;
        }
        .search-results {
            max-height: 500px;
            overflow-y: auto;
        }
        .reservation-item {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        .reservation-item:hover {
            border-color: #0d6efd;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        .reservation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .reservation-id {
            font-weight: bold;
            color: #0d6efd;
            font-size: 1.1rem;
        }
        .reservation-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .status-reservation { background-color: #fff3cd; color: #856404; }
        .status-active { background-color: #d1e7dd; color: #0f5132; }
        .status-completed { background-color: #cfe2ff; color: #084298; }
        .customer-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #0d6efd;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .room-badge {
            background-color: #e7f1ff;
            color: #0d6efd;
            padding: 4px 10px;
            border-radius: 15px;
            font-weight: 500;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-state-icon {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }
        .quick-filter-badge {
            cursor: pointer;
            transition: all 0.3s;
        }
        .quick-filter-badge:hover {
            transform: scale(1.05);
        }
        .search-loading {
            display: none;
        }
        .search-loading.active {
            display: block;
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard.index') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('checkin.index') }}">Check-in</a>
                        </li>
                        <li class="breadcrumb-item active">Recherche de Réservations</li>
                    </ol>
                </nav>
                
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="h4 mb-0">
                        <i class="fas fa-search text-primary me-2"></i>
                        Recherche de Réservations
                    </h2>
                    <a href="{{ route('checkin.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
                <p class="text-muted">Trouvez rapidement une réservation pour le check-in</p>
            </div>
        </div>

        <!-- Barre de recherche -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="search-container">
                    <h4 class="mb-3">
                        <i class="fas fa-search me-2"></i>Rechercher une réservation
                    </h4>
                    <form method="GET" action="{{ route('checkin.search') }}" id="search-form">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white border-0">
                                        <i class="fas fa-search text-primary"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control border-0" 
                                           name="search" 
                                           id="search-input"
                                           placeholder="Nom client, téléphone, email, numéro de chambre..."
                                           value="{{ $search ?? '' }}"
                                           autocomplete="off"
                                           autofocus>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-light btn-lg w-100">
                                    <i class="fas fa-search me-2"></i>Rechercher
                                </button>
                            </div>
                        </div>
                        
                        <!-- Filtres rapides -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-2">
                                    <small class="text-white me-2">Filtres rapides:</small>
                                    <span class="quick-filter-badge badge bg-light text-dark" 
                                          onclick="setFilter('arrivals-today')">
                                        <i class="fas fa-calendar-day me-1"></i>Arrivées aujourd'hui
                                    </span>
                                    <span class="quick-filter-badge badge bg-light text-dark" 
                                          onclick="setFilter('departures-today')">
                                        <i class="fas fa-sign-out-alt me-1"></i>Départs aujourd'hui
                                    </span>
                                    <span class="quick-filter-badge badge bg-light text-dark" 
                                          onclick="setFilter('reservation')">
                                        <i class="fas fa-calendar-check me-1"></i>Réservations
                                    </span>
                                    <span class="quick-filter-badge badge bg-light text-dark" 
                                          onclick="setFilter('active')">
                                        <i class="fas fa-bed me-1"></i>Dans l'hôtel
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Indicateur de chargement -->
        <div class="row mb-4 search-loading" id="loading-indicator">
            <div class="col-12">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-2 text-muted">Recherche en cours...</p>
                </div>
            </div>
        </div>

        <!-- Résultats -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Résultats de recherche</h5>
                        @if(isset($search) && $search)
                            <div>
                                <span class="badge bg-primary">{{ $reservations->count() }} résultat(s)</span>
                                <a href="{{ route('checkin.search') }}" class="btn btn-sm btn-outline-secondary ms-2">
                                    <i class="fas fa-times me-1"></i>Effacer
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        @if(!isset($search) || !$search)
                            <!-- État initial - Instructions -->
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <h4 class="text-muted">Recherchez une réservation</h4>
                                <p class="text-muted mb-4">
                                    Utilisez la barre de recherche ci-dessus pour trouver des réservations.
                                    Vous pouvez rechercher par nom, téléphone, email ou numéro de chambre.
                                </p>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card text-center h-100">
                                            <div class="card-body">
                                                <i class="fas fa-user fa-2x text-primary mb-3"></i>
                                                <h6>Par nom client</h6>
                                                <p class="text-muted small">Ex: "Dupont"</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card text-center h-100">
                                            <div class="card-body">
                                                <i class="fas fa-phone fa-2x text-success mb-3"></i>
                                                <h6>Par téléphone</h6>
                                                <p class="text-muted small">Ex: "0123456789"</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card text-center h-100">
                                            <div class="card-body">
                                                <i class="fas fa-envelope fa-2x text-warning mb-3"></i>
                                                <h6>Par email</h6>
                                                <p class="text-muted small">Ex: "client@email.com"</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card text-center h-100">
                                            <div class="card-body">
                                                <i class="fas fa-door-closed fa-2x text-info mb-3"></i>
                                                <h6>Par chambre</h6>
                                                <p class="text-muted small">Ex: "101"</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($reservations->isEmpty())
                            <!-- Aucun résultat -->
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-search-minus"></i>
                                </div>
                                <h4 class="text-muted">Aucun résultat trouvé</h4>
                                <p class="text-muted mb-3">
                                    Aucune réservation ne correspond à votre recherche "{{ $search }}".
                                </p>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('checkin.search') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-search me-2"></i>Nouvelle recherche
                                    </a>
                                    <a href="{{ route('checkin.direct') }}" class="btn btn-primary">
                                        <i class="fas fa-user-plus me-2"></i>Check-in direct
                                    </a>
                                </div>
                            </div>
                        @else
                            <!-- Liste des résultats -->
                            <div class="search-results">
                                @foreach($reservations as $transaction)
                                    <div class="reservation-item">
                                        <div class="reservation-header">
                                            <div>
                                                <span class="reservation-id">#{{ $transaction->id }}</span>
                                                <span class="reservation-status status-{{ $transaction->status }}">
                                                    {{ $transaction->status_label }}
                                                </span>
                                            </div>
                                            <div>
                                                <small class="text-muted">
                                                    Créée le {{ $transaction->created_at->format('d/m/Y') }}
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <div class="row align-items-center">
                                            <div class="col-md-2">
                                                <div class="customer-avatar">
                                                    {{ substr($transaction->customer->name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <h6 class="mb-1">{{ $transaction->customer->name }}</h6>
                                                <div class="text-muted small">
                                                    <div><i class="fas fa-phone fa-xs me-1"></i> {{ $transaction->customer->phone }}</div>
                                                    @if($transaction->customer->email)
                                                        <div><i class="fas fa-envelope fa-xs me-1"></i> {{ $transaction->customer->email }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-1">
                                                    <span class="room-badge">
                                                        <i class="fas fa-door-closed me-1"></i>
                                                        Chambre {{ $transaction->room->number }}
                                                    </span>
                                                </div>
                                                <div class="text-muted small">
                                                    <i class="fas fa-bed me-1"></i>
                                                    {{ $transaction->room->type->name ?? 'Type non spécifié' }}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-end">
                                                    <div class="mb-2">
                                                        <small class="text-muted">Arrivée:</small><br>
                                                        <strong>{{ $transaction->check_in->format('d/m/Y H:i') }}</strong>
                                                    </div>
                                                    <div class="btn-group btn-group-sm">
                                                        @if($transaction->status == 'reservation')
                                                            <a href="{{ route('checkin.show', $transaction) }}" 
                                                               class="btn btn-primary">
                                                                <i class="fas fa-door-open me-1"></i> Check-in
                                                            </a>
                                                            <button onclick="quickCheckIn({{ $transaction->id }})" 
                                                                    class="btn btn-outline-success">
                                                                <i class="fas fa-bolt"></i>
                                                            </button>
                                                        @elseif($transaction->status == 'active')
                                                            <a href="{{ route('transaction.show', $transaction) }}" 
                                                               class="btn btn-outline-info">
                                                                <i class="fas fa-eye me-1"></i> Voir
                                                            </a>
                                                            <button onclick="checkoutGuest({{ $transaction->id }})" 
                                                                    class="btn btn-outline-success">
                                                                <i class="fas fa-sign-out-alt me-1"></i> Check-out
                                                            </button>
                                                        @else
                                                            <a href="{{ route('transaction.show', $transaction) }}" 
                                                               class="btn btn-outline-secondary">
                                                                <i class="fas fa-eye me-1"></i> Voir détails
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Informations supplémentaires -->
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    <strong>Durée:</strong> {{ $transaction->nights }} nuit(s)
                                                </small>
                                            </div>
                                            <div class="col-md-4">
                                                <small class="text-muted">
                                                    <i class="fas fa-money-bill-wave me-1"></i>
                                                    <strong>Total:</strong> {{ Helper::formatCFA($transaction->getTotalPrice()) }}
                                                </small>
                                            </div>
                                            <div class="col-md-4">
                                                <small class="text-muted">
                                                    <i class="fas fa-credit-card me-1"></i>
                                                    <strong>Payé:</strong> {{ Helper::formatCFA($transaction->getTotalPayment()) }}
                                                    @if($transaction->getRemainingPayment() > 0)
                                                        <span class="text-warning ms-1">
                                                            (Solde: {{ Helper::formatCFA($transaction->getRemainingPayment()) }})
                                                        </span>
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Pagination -->
                            @if($reservations->hasPages())
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $reservations->appends(['search' => $search])->links() }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Aide -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Conseils de recherche</h6>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>Utilisez les initiales pour une recherche plus large</li>
                            <li>Les numéros de téléphone peuvent être saisis avec ou sans indicatif</li>
                            <li>Recherchez par numéro de chambre pour voir tous les clients d'une chambre</li>
                            <li>Utilisez les filtres rapides pour les besoins courants</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Actions rapides</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('checkin.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-home me-2"></i>Retour au dashboard check-in
                            </a>
                            <a href="{{ route('checkin.direct') }}" class="btn btn-outline-success">
                                <i class="fas fa-user-plus me-2"></i>Check-in direct (sans réservation)
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
<script>
function setFilter(filterType) {
    const searchInput = document.getElementById('search-input');
    
    switch(filterType) {
        case 'arrivals-today':
            searchInput.value = 'arrivée:' + new Date().toLocaleDateString('fr-CA');
            break;
        case 'departures-today':
            searchInput.value = 'départ:' + new Date().toLocaleDateString('fr-CA');
            break;
        case 'reservation':
            searchInput.value = 'statut:reservation';
            break;
        case 'active':
            searchInput.value = 'statut:active';
            break;
    }
    
    // Soumettre automatiquement le formulaire
    document.getElementById('search-form').submit();
}

function quickCheckIn(transactionId) {
    if (confirm('Effectuer un check-in rapide sans formulaire détaillé ?')) {
        const loadingIndicator = document.getElementById('loading-indicator');
        loadingIndicator.classList.add('active');
        
        fetch(`/checkin/${transactionId}/quick`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            loadingIndicator.classList.remove('active');
            
            if (data.success) {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                alertDiv.innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>
                    ${data.message || 'Check-in rapide effectué avec succès!'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.container-fluid').prepend(alertDiv);
                
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                alert('Erreur: ' + (data.error || 'Échec du check-in rapide'));
            }
        })
        .catch(error => {
            loadingIndicator.classList.remove('active');
            console.error('Error:', error);
            alert('Une erreur est survenue lors du check-in rapide');
        });
    }
}

function checkoutGuest(transactionId) {
    if (confirm('Effectuer le check-out de ce client ?')) {
        const loadingIndicator = document.getElementById('loading-indicator');
        loadingIndicator.classList.add('active');
        
        fetch(`/transaction/${transactionId}/check-out`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            loadingIndicator.classList.remove('active');
            
            if (data.success) {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                alertDiv.innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>
                    ${data.message || 'Check-out effectué avec succès!'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.container-fluid').prepend(alertDiv);
                
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                alert('Erreur: ' + (data.message || 'Échec du check-out'));
            }
        })
        .catch(error => {
            loadingIndicator.classList.remove('active');
            console.error('Error:', error);
            alert('Une erreur est survenue lors du check-out');
        });
    }
}

// Recherche en temps réel (optionnel)
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchForm = document.getElementById('search-form');
    const loadingIndicator = document.getElementById('loading-indicator');
    
    // Focus sur le champ de recherche
    if (searchInput && !searchInput.value) {
        searchInput.focus();
    }
    
    // Recherche automatique après 1 seconde d'inactivité (optionnel)
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        if (this.value.length >= 2) {
            searchTimeout = setTimeout(() => {
                loadingIndicator.classList.add('active');
                searchForm.submit();
            }, 1000);
        }
    });
});
</script>
@endsection