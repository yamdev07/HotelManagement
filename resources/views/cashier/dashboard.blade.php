@extends('template.master')

@section('title', 'Dashboard Caissier')

@section('content')
<style>
    .cashier-stats-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }
    
    .cashier-stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    
    .cashier-stats-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-right: 15px;
    }
    
    .active-session-badge {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }
    
    .permission-badge {
        font-size: 0.7rem;
        padding: 2px 6px;
        border-radius: 4px;
    }
    
    .permission-admin {
        background-color: #d1e7dd;
        color: #0f5132;
    }
    
    .permission-receptionist {
        background-color: #cff4fc;
        color: #055160;
    }
    
    .view-only-badge {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #6c757d;
    }
</style>

<div class="container-fluid">
    <!-- En-tête avec permissions -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light p-3 rounded">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard.index') }}">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-cash-register"></i> Caissier
                    </li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-cash-register text-primary me-2"></i>Dashboard Caissier
                    </h1>
                    <p class="text-muted mb-0">
                        Bonjour, <strong>{{ auth()->user()->name }}</strong> 
                        <span class="badge bg-info ms-2">{{ auth()->user()->role }}</span>
                        
                        @if(auth()->user()->role == 'Receptionist')
                        <span class="permission-badge permission-receptionist ms-2">
                            <i class="fas fa-eye me-1"></i>Lecture seule
                        </span>
                        @elseif(in_array(auth()->user()->role, ['Super', 'Admin']))
                        <span class="permission-badge permission-admin ms-2">
                            <i class="fas fa-edit me-1"></i>Permissions complètes
                        </span>
                        @endif
                    </p>
                </div>
                
                <!-- Badge mode admin pour visualisation globale -->
                @if($isAdmin)
                <div class="alert alert-info alert-dismissible fade show py-2" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-users fa-lg me-3"></i>
                        <div>
                            <strong class="d-block">Mode Administrateur</strong>
                            <small>Visualisation de tous les réceptionnistes</small>
                        </div>
                        <a href="{{ route('cashier.dashboard') }}?view=self" class="btn btn-sm btn-outline-info ms-3">
                            <i class="fas fa-user"></i> Voir seulement mes sessions
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Indicateur de permissions -->
    @if(auth()->user()->role == 'Receptionist')
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-warning">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle me-3 fa-lg"></i>
                    <div>
                        <strong class="d-block">Mode Réceptionniste - Lecture Seule</strong>
                        <small class="d-block mb-2">Vous pouvez visualiser les sessions mais pas les modifier. 
                        Seuls les administrateurs peuvent créer, modifier ou clôturer les sessions.</small>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <span class="badge view-only-badge">
                                <i class="fas fa-check-circle text-success me-1"></i>Visualisation ✓
                            </span>
                            <span class="badge view-only-badge">
                                <i class="fas fa-check-circle text-success me-1"></i>Filtrage ✓
                            </span>
                            <span class="badge view-only-badge">
                                <i class="fas fa-times-circle text-danger me-1"></i>Création ✗
                            </span>
                            <span class="badge view-only-badge">
                                <i class="fas fa-times-circle text-danger me-1"></i>Modification ✗
                            </span>
                            <span class="badge view-only-badge">
                                <i class="fas fa-times-circle text-danger me-1"></i>Clôture ✗
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Session active (personnelle ou toutes selon permissions) -->
    @if($activeSession)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-success cashier-stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="cashier-stats-icon bg-success text-white">
                                <i class="fas fa-play-circle"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">
                                    Session Active #{{ $activeSession->id }}
                                    @if($activeSession->user_id != auth()->id())
                                    <small class="text-muted">({{ $activeSession->user->name }})</small>
                                    @endif
                                </h5>
                                <p class="card-text text-muted mb-0">
                                    <i class="fas fa-user me-1"></i> {{ $activeSession->user->name }}
                                    | <i class="fas fa-clock me-1 ms-2"></i> Début: {{ $activeSession->start_time->format('d/m/Y H:i') }} 
                                    | <i class="fas fa-money-bill-wave ms-2 me-1"></i> Solde: 
                                    <strong class="text-success">{{ number_format($activeSession->current_balance, 2) }} FCFA</strong>
                                </p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('cashier.sessions.show', $activeSession) }}" class="btn btn-outline-info">
                                <i class="fas fa-eye me-1"></i> Détails
                            </a>
                            
                            <!-- Seuls les admins peuvent clôturer -->
                            @if($isAdmin && $activeSession->user_id == auth()->id())
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#closeModal">
                                <i class="fas fa-lock me-1"></i> Clôturer
                            </button>
                            @elseif($isAdmin && $activeSession->user_id != auth()->id())
                            <span class="btn btn-outline-secondary disabled" title="Vous ne pouvez clôturer que vos propres sessions">
                                <i class="fas fa-lock me-1"></i> Clôturer (non autorisé)
                            </span>
                            @else
                            <span class="btn btn-outline-secondary disabled" title="Réservé aux administrateurs">
                                <i class="fas fa-lock me-1"></i> Clôturer
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @elseif(count($allActiveSessions) > 0 && $isAdmin)
    <!-- Si admin et visualisation globale, afficher toutes les sessions actives -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>Sessions Actives des Réceptionnistes
                        <span class="badge bg-white text-info ms-2">{{ $allActiveSessions->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Réceptionniste</th>
                                    <th>Session ID</th>
                                    <th>Début</th>
                                    <th>Durée</th>
                                    <th>Solde</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allActiveSessions as $session)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $session->user->getAvatar() }}" 
                                                 class="rounded-circle me-2" width="35" height="35" alt="">
                                            <div>
                                                <div>{{ $session->user->name }}</div>
                                                <small class="text-muted">{{ $session->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><strong>#{{ $session->id }}</strong></td>
                                    <td>{{ $session->start_time->format('d/m H:i') }}</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $session->start_time->diffForHumans(null, true) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ number_format($session->current_balance, 2) }} FCFA
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success active-session-badge">
                                            <i class="fas fa-play-circle me-1"></i>Active
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('cashier.sessions.show', $session) }}" 
                                           class="btn btn-sm btn-info" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($session->user_id == auth()->id())
                                        <a href="#" class="btn btn-sm btn-warning" title="Clôturer (votre session)">
                                            <i class="fas fa-lock"></i>
                                        </a>
                                        @else
                                        <span class="btn btn-sm btn-outline-secondary disabled" 
                                              title="Vous ne pouvez clôturer que vos propres sessions">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Aucune session active -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-warning cashier-stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="cashier-stats-icon bg-warning text-dark">
                                <i class="fas fa-pause-circle"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Aucune session active</h5>
                                <p class="card-text text-muted mb-0">
                                    @if($isAdmin)
                                        @if(request()->get('view') == 'self')
                                        Vous n'avez pas de session active
                                        @else
                                        Aucun réceptionniste n'a de session active
                                        @endif
                                    @else
                                    Vous n'avez pas de session active
                                    @endif
                                </p>
                            </div>
                        </div>
                        @if($isAdmin && $canStartSession)
                            <a href="{{ route('cashier.sessions.create') }}" class="btn btn-primary">
                                <i class="fas fa-play-circle me-1"></i> Démarrer une session
                            </a>
                        @elseif($isAdmin && !$canStartSession)
                            <span class="btn btn-outline-secondary disabled" title="Vous avez déjà une session active">
                                <i class="fas fa-play-circle me-1"></i> Démarrer une session
                            </span>
                        @else
                            <span class="btn btn-outline-secondary disabled" title="Réservé aux administrateurs">
                                <i class="fas fa-play-circle me-1"></i> Démarrer une session
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistiques (différentes selon les permissions) -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card cashier-stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-primary mb-0">📅 Réservations</h6>
                            <h2 class="mt-2 mb-0">{{ $todayStats['totalBookings'] }}</h2>
                            <small class="text-muted">Aujourd'hui</small>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card cashier-stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-success mb-0">💰 Chiffre d'affaires</h6>
                            <h2 class="mt-2 mb-0">{{ number_format($todayStats['revenue'], 0) }} FCFA</h2>
                            <small class="text-muted">
                                @if($isAdmin && request()->get('view') != 'self')
                                Tous les réceptionnistes
                                @else
                                Aujourd'hui
                                @endif
                            </small>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-chart-line fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card cashier-stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-info mb-0">🚪 Check-ins</h6>
                            <h2 class="mt-2 mb-0">{{ $todayStats['checkins'] }}</h2>
                            <small class="text-muted">Aujourd'hui</small>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-sign-in-alt fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card cashier-stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-warning mb-0">⏳ Paiements en attente</h6>
                            <h2 class="mt-2 mb-0">{{ $todayStats['pendingPayments'] }}</h2>
                            <small class="text-muted">
                                @if($isAdmin && request()->get('view') != 'self')
                                Total général
                                @else
                                Mes paiements
                                @endif
                            </small>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu principal avec onglets selon permissions -->
    <div class="row">
        <!-- Onglets de navigation -->
        <div class="col-12 mb-3">
            <ul class="nav nav-tabs" id="cashierTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                        <i class="fas fa-clock me-1"></i> Paiements en attente
                        <span class="badge bg-warning ms-2">{{ $pendingPayments->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="sessions-tab" data-bs-toggle="tab" data-bs-target="#sessions" type="button">
                        <i class="fas fa-history me-1"></i> Mes sessions
                        <span class="badge bg-info ms-2">{{ $recentSessions->count() }}</span>
                    </button>
                </li>
                @if($isAdmin)
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="all-sessions-tab" data-bs-toggle="tab" data-bs-target="#all-sessions" type="button">
                        <i class="fas fa-users me-1"></i> Toutes les sessions
                        <span class="badge bg-dark ms-2">{{ $allSessionsCount ?? 0 }}</span>
                    </button>
                </li>
                @endif
            </ul>
        </div>
        
        <!-- Contenu des onglets -->
        <div class="col-12">
            <div class="tab-content" id="cashierTabsContent">
                <!-- Onglet 1: Paiements en attente -->
                <div class="tab-pane fade show active" id="pending">
                    <div class="card">
                        <div class="card-body">
                            @if($pendingPayments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Référence</th>
                                                <th>Montant</th>
                                                <th>Client</th>
                                                <th>Réceptionniste</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingPayments as $payment)
                                            <tr>
                                                <td><strong>#{{ $payment->reference }}</strong></td>
                                                <td>
                                                    <span class="badge bg-danger">
                                                        {{ number_format($payment->amount, 2) }} FCFA
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($payment->transaction && $payment->transaction->booking && $payment->transaction->booking->customer)
                                                    {{ $payment->transaction->booking->customer->name }}
                                                    @else
                                                    <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($payment->user)
                                                    <small>{{ $payment->user->name }}</small>
                                                    @else
                                                    <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>{{ $payment->created_at->format('d/m H:i') }}</small>
                                                </td>
                                                <td>
                                                    @if($isAdmin)
                                                    <a href="#" class="btn btn-sm btn-success" title="Valider le paiement">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                    @else
                                                    <span class="btn btn-sm btn-outline-secondary disabled" title="Réservé aux administrateurs">
                                                        <i class="fas fa-check"></i>
                                                    </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <h5>Aucun paiement en attente</h5>
                                    <p class="text-muted">Tous les paiements sont à jour</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Onglet 2: Mes sessions -->
                <div class="tab-pane fade" id="sessions">
                    <div class="card">
                        <div class="card-body">
                            @if($recentSessions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Session ID</th>
                                                <th>Début</th>
                                                <th>Fin</th>
                                                <th>Durée</th>
                                                <th>Solde initial</th>
                                                <th>Solde final</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentSessions as $session)
                                            <tr>
                                                <td><strong>#{{ $session->id }}</strong></td>
                                                <td>{{ $session->start_time->format('d/m H:i') }}</td>
                                                <td>
                                                    @if($session->end_time)
                                                    {{ $session->end_time->format('d/m H:i') }}
                                                    @else
                                                    <span class="badge bg-warning">En cours</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($session->end_time)
                                                    <span class="badge bg-secondary">
                                                        {{ $session->start_time->diff($session->end_time)->format('%hh %im') }}
                                                    </span>
                                                    @else
                                                    <span class="badge bg-info">
                                                        {{ $session->start_time->diffForHumans(null, true) }}
                                                    </span>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($session->initial_balance, 2) }} FCFA</td>
                                                <td>{{ number_format($session->final_balance, 2) }} FCFA</td>
                                                <td>
                                                    @if($session->status == 'active')
                                                    <span class="badge bg-success">Active</span>
                                                    @else
                                                    <span class="badge bg-secondary">Terminée</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('cashier.sessions.show', $session) }}" 
                                                       class="btn btn-sm btn-info" title="Voir détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($isAdmin && $session->user_id == auth()->id() && $session->status == 'active')
                                                    <a href="#" class="btn btn-sm btn-warning" title="Clôturer">
                                                        <i class="fas fa-lock"></i>
                                                    </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                    <h5>Aucune session</h5>
                                    <p class="text-muted">
                                        @if($isAdmin)
                                        Commencez votre première session
                                        @else
                                        Contactez un administrateur pour démarrer une session
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Onglet 3: Toutes les sessions (Admin seulement) -->
                @if($isAdmin)
                <div class="tab-pane fade" id="all-sessions">
                    <div class="card">
                        <div class="card-body">
                            <!-- Filtres pour admin -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <select class="form-select" id="filterUser">
                                        <option value="">Tous les utilisateurs</option>
                                        @foreach($allReceptionists as $receptionist)
                                        <option value="{{ $receptionist->id }}">{{ $receptionist->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" id="filterStatus">
                                        <option value="">Tous les statuts</option>
                                        <option value="active">Actives</option>
                                        <option value="closed">Terminées</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="date" class="form-control" id="filterDate" placeholder="Date">
                                </div>
                            </div>
                            
                            @if(isset($allSessions) && $allSessions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Utilisateur</th>
                                                <th>Session ID</th>
                                                <th>Début</th>
                                                <th>Fin</th>
                                                <th>Solde initial</th>
                                                <th>Solde final</th>
                                                <th>Différence</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allSessions as $session)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $session->user->getAvatar() }}" 
                                                             class="rounded-circle me-2" width="30" height="30" alt="">
                                                        <div>
                                                            <div>{{ $session->user->name }}</div>
                                                            <small class="text-muted">{{ $session->user->role }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><strong>#{{ $session->id }}</strong></td>
                                                <td>{{ $session->start_time->format('d/m H:i') }}</td>
                                                <td>
                                                    @if($session->end_time)
                                                    {{ $session->end_time->format('d/m H:i') }}
                                                    @else
                                                    <span class="badge bg-warning">En cours</span>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($session->initial_balance, 2) }} FCFA</td>
                                                <td>{{ number_format($session->final_balance, 2) }} FCFA</td>
                                                <td>
                                                    @php
                                                        $diff = $session->final_balance - $session->initial_balance;
                                                        $diffClass = $diff >= 0 ? 'text-success' : 'text-danger';
                                                    @endphp
                                                    <span class="{{ $diffClass }}">
                                                        {{ $diff >= 0 ? '+' : '' }}{{ number_format($diff, 2) }} FCFA
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($session->status == 'active')
                                                    <span class="badge bg-success">Active</span>
                                                    @else
                                                    <span class="badge bg-secondary">Terminée</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('cashier.sessions.show', $session) }}" 
                                                       class="btn btn-sm btn-info" title="Voir détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($session->user_id == auth()->id() && $session->status == 'active')
                                                    <a href="#" class="btn btn-sm btn-warning" title="Clôturer">
                                                        <i class="fas fa-lock"></i>
                                                    </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Pagination -->
                                @if($allSessions->hasPages())
                                <div class="mt-3">
                                    {{ $allSessions->links('template.paginationlinks') }}
                                </div>
                                @endif
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5>Aucune session trouvée</h5>
                                    <p class="text-muted">Aucun réceptionniste n'a encore créé de session</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de clôture (seulement pour admin et sa propre session) -->
@if($activeSession && $isAdmin && $activeSession->user_id == auth()->id())
<div class="modal fade" id="closeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-lock text-danger me-2"></i>Clôturer ma session
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('cashier.sessions.destroy', $activeSession) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible.
                    </div>
                    
                    <p>Êtes-vous sûr de vouloir clôturer votre session <strong>#{{ $activeSession->id }}</strong> ?</p>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-money-bill-wave me-1"></i>Solde final (physique)
                        </label>
                        <input type="number" name="final_balance" class="form-control" 
                               step="0.01" value="{{ $activeSession->current_balance }}" required>
                        <small class="text-muted">Veuillez saisir le solde réel en caisse</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-edit me-1"></i>Notes de clôture
                        </label>
                        <textarea name="closing_notes" class="form-control" rows="3" 
                                  placeholder="Observations, anomalies, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-lock me-1"></i>Clôturer la session
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@section('footer')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les onglets
    const tabTriggerList = [].slice.call(document.querySelectorAll('#cashierTabs button'));
    tabTriggerList.forEach(function (tabTriggerEl) {
        tabTriggerEl.addEventListener('click', function (event) {
            event.preventDefault();
            const tab = new bootstrap.Tab(tabTriggerEl);
            tab.show();
        });
    });
    
    // Filtres pour admin
    const filterUser = document.getElementById('filterUser');
    const filterStatus = document.getElementById('filterStatus');
    const filterDate = document.getElementById('filterDate');
    
    if (filterUser) {
        filterUser.addEventListener('change', applyFilters);
    }
    if (filterStatus) {
        filterStatus.addEventListener('change', applyFilters);
    }
    if (filterDate) {
        filterDate.addEventListener('change', applyFilters);
    }
    
    function applyFilters() {
        const userId = filterUser ? filterUser.value : '';
        const status = filterStatus ? filterStatus.value : '';
        const date = filterDate ? filterDate.value : '';
        
        // Ici vous pouvez ajouter la logique de filtrage AJAX
        console.log('Filtres:', { userId, status, date });
    }
    
    // Confirmation avant clôture
    const closeModal = document.getElementById('closeModal');
    if (closeModal) {
        closeModal.addEventListener('show.bs.modal', function() {
            const form = this.querySelector('form');
            form.addEventListener('submit', function(e) {
                const finalBalance = this.querySelector('input[name="final_balance"]').value;
                const currentBalance = {{ $activeSession->current_balance ?? 0 }};
                
                if (Math.abs(finalBalance - currentBalance) > 1000) {
                    if (!confirm('⚠️ Écart important détecté !\n\nSolde système: ' + currentBalance + ' FCFA\n' +
                               'Solde saisi: ' + finalBalance + ' FCFA\n\n' +
                               'Voulez-vous vraiment continuer ?')) {
                        e.preventDefault();
                    }
                }
            });
        });
    }
    
    // Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection