@extends('layouts.app')

@section('title', 'Dashboard Caissier')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">📊 Dashboard Caissier</h1>
            <p class="text-muted">Bonjour, {{ $user->name }} ({{ $user->role }})</p>
        </div>
    </div>

    <!-- Session active -->
    @if($activeSession)
        <div class="alert alert-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">✅ Session Active #{{ $activeSession->id }}</h5>
                    <p class="mb-0">
                        Débutée le {{ $activeSession->start_time->format('d/m/Y à H:i') }} | 
                        Solde actuel: <strong>{{ number_format($activeSession->current_balance, 2) }} FCFA</strong>
                    </p>
                </div>
                <div>
                    <a href="{{ route('cashier.sessions.show', $activeSession) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i> Détails
                    </a>
                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#closeModal">
                        <i class="fas fa-lock"></i> Clôturer
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">ℹ️ Aucune session active</h5>
                    <p class="mb-0">Vous pouvez démarrer une nouvelle session de caisse</p>
                </div>
                @if($canStartSession)
                    <a href="{{ route('cashier.sessions.create') }}" class="btn btn-primary">
                        <i class="fas fa-play-circle"></i> Démarrer une session
                    </a>
                @endif
            </div>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="card-title text-primary">Réservations</h6>
                    <h2 class="mb-0">{{ $todayStats['totalBookings'] }}</h2>
                    <small class="text-muted">Aujourd'hui</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <h6 class="card-title text-success">Chiffre d'affaires</h6>
                    <h2 class="mb-0">{{ number_format($todayStats['revenue'], 0) }} FCFA</h2>
                    <small class="text-muted">Aujourd'hui</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body">
                    <h6 class="card-title text-info">Check-ins</h6>
                    <h2 class="mb-0">{{ $todayStats['checkins'] }}</h2>
                    <small class="text-muted">Aujourd'hui</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <h6 class="card-title text-warning">Paiements en attente</h6>
                    <h2 class="mb-0">{{ $todayStats['pendingPayments'] }}</h2>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="row">
        <!-- Paiements en attente -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">⏳ Paiements en attente</h5>
                </div>
                <div class="card-body">
                    @if($pendingPayments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Référence</th>
                                        <th>Montant</th>
                                        <th>Client</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->reference }}</td>
                                        <td>{{ number_format($payment->amount, 2) }} FCFA</td>
                                        <td>{{ $payment->transaction->booking->customer->name ?? 'N/A' }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Aucun paiement en attente</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sessions récentes -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">📋 Mes sessions récentes</h5>
                </div>
                <div class="card-body">
                    @if($recentSessions->count() > 0)
                        <div class="list-group">
                            @foreach($recentSessions as $session)
                            <a href="{{ route('cashier.sessions.show', $session) }}" 
                               class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Session #{{ $session->id }}</h6>
                                    <small>{{ $session->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">
                                    Statut: 
                                    <span class="badge bg-{{ $session->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ $session->status }}
                                    </span>
                                    | Solde: {{ number_format($session->current_balance, 2) }} FCFA
                                </p>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Aucune session trouvée</p>
                    @endif
                    <div class="mt-3">
                        <a href="{{ route('cashier.sessions.index') }}" class="btn btn-sm btn-outline-primary">
                            Voir toutes mes sessions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sessions actives (admin seulement) -->
    @if($isAdmin && $allActiveSessions->count() > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">👥 Sessions actives - Tous les utilisateurs</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Utilisateur</th>
                                    <th>Session ID</th>
                                    <th>Début</th>
                                    <th>Solde</th>
                                    <th>Durée</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allActiveSessions as $session)
                                <tr>
                                    <td>{{ $session->user->name }}</td>
                                    <td>#{{ $session->id }}</td>
                                    <td>{{ $session->start_time->format('H:i') }}</td>
                                    <td>{{ number_format($session->current_balance, 2) }} FCFA</td>
                                    <td>{{ $session->start_time->diffForHumans(null, true) }}</td>
                                    <td>
                                        <a href="{{ route('cashier.sessions.show', $session) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
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
    @endif
</div>

<!-- Modal de clôture -->
@if($activeSession)
<div class="modal fade" id="closeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clôturer la session</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('cashier.sessions.destroy', $activeSession) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir clôturer la session #{{ $activeSession->id }} ?</p>
                    <div class="mb-3">
                        <label class="form-label">Solde final (physique)</label>
                        <input type="number" name="final_balance" class="form-control" 
                               step="0.01" value="{{ $activeSession->current_balance }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes de clôture</label>
                        <textarea name="closing_notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Clôturer la session</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
// Auto-refresh des stats toutes les 60 secondes
setInterval(function() {
    fetch('{{ route("cashier.live-stats") }}')
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Mettre à jour les stats ici si besoin
                console.log('Stats mises à jour:', data.stats);
            }
        });
}, 60000);
</script>
@endsection