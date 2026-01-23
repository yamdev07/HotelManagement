@extends('template.master')
@section('title', 'Check-in - Dashboard')
@section('content')
    <style>
        .stat-card {
            border-radius: 10px;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .list-group-item-hover:hover {
            background-color: #f8f9fa;
        }
        .quick-action-btn {
            padding: 6px 12px;
            font-size: 0.85rem;
        }
        .reservation-date {
            background-color: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .guest-status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-checked-in { background-color: #d1e7dd; color: #0f5132; }
        .status-departing { background-color: #fff3cd; color: #856404; }
        .status-upcoming { background-color: #cfe2ff; color: #084298; }
        .badge-notification {
            font-size: 0.7rem;
            padding: 3px 6px;
            margin-left: 5px;
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
                        <li class="breadcrumb-item active">Check-in</li>
                    </ol>
                </nav>
                
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="h4 mb-0">
                        <i class="fas fa-door-open text-primary me-2"></i>
                        Gestion des Check-in
                    </h2>
                    <div class="btn-group">
                        <a href="{{ route('checkin.search') }}" class="btn btn-outline-primary">
                            <i class="fas fa-search me-2"></i>Rechercher
                        </a>
                        <a href="{{ route('checkin.direct') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Check-in Direct
                        </a>
                    </div>
                </div>
                <p class="text-muted">Gérez les arrivées, départs et séjours en cours</p>
            </div>
        </div>

        <!-- Messages de session -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {!! session('success') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Arrivées aujourd'hui
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $stats['arrivals_today'] }}
                                </div>
                                <div class="mt-2">
                                    <span class="text-xs text-muted">
                                        <i class="fas fa-calendar-day me-1"></i>
                                        {{ $today->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-day fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Séjours en cours
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $stats['currently_checked_in'] }}
                                </div>
                                <div class="mt-2">
                                    <span class="text-xs text-muted">
                                        <i class="fas fa-bed me-1"></i>
                                        Dans l'hôtel
                                    </span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-bed fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Départs aujourd'hui
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $stats['departures_today'] }}
                                </div>
                                <div class="mt-2">
                                    <span class="text-xs text-muted">
                                        <i class="fas fa-sign-out-alt me-1"></i>
                                        À libérer
                                    </span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-sign-out-alt fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Chambres disponibles
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $stats['available_rooms'] }}
                                </div>
                                <div class="mt-2">
                                    <span class="text-xs text-muted">
                                        <i class="fas fa-door-closed me-1"></i>
                                        Prêtes
                                    </span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-door-closed fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Réservations à venir -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-calendar-check me-2"></i>
                            Réservations à venir (Aujourd'hui & Demain)
                        </h6>
                        <span class="badge bg-primary">{{ $upcomingReservations->count() }} jours</span>
                    </div>
                    <div class="card-body">
                        @if($upcomingReservations->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-4x text-gray-300 mb-3"></i>
                                <h5 class="text-muted">Aucune réservation à venir</h5>
                                <p class="text-muted">Pas d'arrivées prévues pour aujourd'hui ou demain</p>
                                <a href="{{ route('checkin.search') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-search me-2"></i>Chercher des réservations
                                </a>
                            </div>
                        @else
                            @foreach($upcomingReservations as $date => $reservations)
                                <div class="reservation-date">
                                    <h6 class="font-weight-bold mb-3 d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-calendar-day me-2"></i>
                                            {{ \Carbon\Carbon::parse($date)->translatedFormat('l d F Y') }}
                                        </span>
                                        <span class="badge bg-secondary">{{ $reservations->count() }} réservation(s)</span>
                                    </h6>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead>
                                                <tr class="bg-light">
                                                    <th width="25%">Client</th>
                                                    <th width="15%">Chambre</th>
                                                    <th width="15%">Arrivée</th>
                                                    <th width="15%">Durée</th>
                                                    <th width="30%" class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($reservations as $transaction)
                                                    <tr class="list-group-item-hover">
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-shrink-0">
                                                                    <i class="fas fa-user-circle text-muted"></i>
                                                                </div>
                                                                <div class="flex-grow-1 ms-2">
                                                                    <strong class="d-block">{{ $transaction->customer->name }}</strong>
                                                                    <small class="text-muted">{{ $transaction->customer->phone }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-light text-dark">{{ $transaction->room->number }}</span>
                                                            <div><small class="text-muted">{{ $transaction->room->type->name ?? 'N/A' }}</small></div>
                                                        </td>
                                                        <td>
                                                            <span class="d-block">{{ $transaction->check_in->format('H:i') }}</span>
                                                            <small class="text-muted">check-in</small>
                                                        </td>
                                                        <td>
                                                            <span class="d-block">{{ $transaction->nights }} nuit(s)</span>
                                                            <small class="text-muted">jusqu'au {{ $transaction->check_out->format('d/m') }}</small>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="btn-group btn-group-sm" role="group">
                                                                <a href="{{ route('checkin.show', $transaction) }}" 
                                                                   class="btn btn-outline-primary quick-action-btn">
                                                                    <i class="fas fa-door-open me-1"></i> Check-in
                                                                </a>
                                                                <button onclick="quickCheckIn({{ $transaction->id }})" 
                                                                        class="btn btn-outline-success quick-action-btn">
                                                                    <i class="fas fa-bolt me-1"></i> Rapide
                                                                </button>
                                                                <a href="{{ route('transaction.show', $transaction) }}" 
                                                                   class="btn btn-outline-info quick-action-btn">
                                                                    <i class="fas fa-eye me-1"></i> Détails
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <hr class="my-4">
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Clients dans l'hôtel et départs -->
            <div class="col-lg-4">
                <!-- Clients dans l'hôtel -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i class="fas fa-bed me-2"></i>
                            Clients dans l'hôtel
                        </h6>
                        <span class="badge bg-success">{{ $activeGuests->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                        @if($activeGuests->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-users-slash fa-3x text-gray-300 mb-3"></i>
                                <p class="text-muted">Aucun client actuellement</p>
                            </div>
                        @else
                            <div class="list-group list-group-flush">
                                @foreach($activeGuests as $transaction)
                                    <div class="list-group-item list-group-item-hover">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">
                                                    <i class="fas fa-user-circle text-success me-1"></i>
                                                    {{ $transaction->customer->name }}
                                                </h6>
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-door-closed me-1"></i>
                                                    Chambre {{ $transaction->room->number }}
                                                </small>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>
                                                    Depuis {{ $transaction->check_in->diffForHumans() }}
                                                </small>
                                            </div>
                                            <span class="badge bg-light text-dark">{{ $transaction->room->type->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="mt-3 d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                Départ: {{ $transaction->check_out->format('d/m H:i') }}
                                            </small>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('transaction.show', $transaction) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button onclick="checkoutGuest({{ $transaction->id }})" 
                                                        class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-sign-out-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @if($activeGuests->isNotEmpty())
                        <div class="card-footer text-center">
                            <a href="{{ route('transaction.index') }}?status=active" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-list me-1"></i> Voir tous les séjours
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Départs du jour -->
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-warning">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Départs aujourd'hui
                        </h6>
                        <span class="badge bg-warning">{{ $todayDepartures->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                        @if($todayDepartures->isEmpty())
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle fa-2x text-gray-300 mb-3"></i>
                                <p class="text-muted">Aucun départ prévu aujourd'hui</p>
                            </div>
                        @else
                            <div class="list-group list-group-flush">
                                @foreach($todayDepartures as $transaction)
                                    <div class="list-group-item list-group-item-hover">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $transaction->customer->name }}</h6>
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-door-closed me-1"></i>
                                                    Chambre {{ $transaction->room->number }}
                                                </small>
                                            </div>
                                            <span class="badge bg-warning">
                                                {{ $transaction->check_out->format('H:i') }}
                                            </span>
                                        </div>
                                        <div class="mt-2 d-flex gap-2">
                                            <a href="{{ route('transaction.show', $transaction) }}" 
                                               class="btn btn-sm btn-outline-primary flex-fill">
                                                <i class="fas fa-file-invoice me-1"></i> Facture
                                            </a>
                                            <button onclick="checkoutGuest({{ $transaction->id }})" 
                                                    class="btn btn-sm btn-outline-success flex-fill">
                                                <i class="fas fa-sign-out-alt me-1"></i> Check-out
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
<script>
function quickCheckIn(transactionId) {
    if (confirm('Effectuer un check-in rapide sans formulaire détaillé ?\n\nLe client sera enregistré avec les informations de base.')) {
        // Afficher un indicateur de chargement
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Traitement...';
        button.disabled = true;
        
        fetch(`/checkin/${transactionId}/quick`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Afficher message de succès
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                alertDiv.innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>
                    ${data.message || 'Check-in effectué avec succès!'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.container-fluid').prepend(alertDiv);
                
                // Recharger la page après 1.5 secondes
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                alert('Erreur: ' + (data.error || 'Échec du check-in'));
                button.innerHTML = originalText;
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue lors du check-in');
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
}

function checkoutGuest(transactionId) {
    if (confirm('Effectuer le check-out de ce client ?\n\nLa chambre sera marquée comme à nettoyer.')) {
        fetch(`/transaction/${transactionId}/check-out`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
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
                    location.reload();
                }, 1500);
            } else {
                alert('Erreur: ' + (data.message || 'Échec du check-out'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue lors du check-out');
        });
    }
}
</script>
@endsection