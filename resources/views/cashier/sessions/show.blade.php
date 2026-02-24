@extends('template.master')

@section('title', 'Détails de la Session #' . $cashierSession->id)

@push('styles')
<style>
:root {
    --primary: #3b82f6;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --dark: #1e293b;
    --light: #f8fafc;
    --border: #e2e8f0;
}

.session-detail-container {
    max-width: 1200px;
    margin: 2rem auto;
}

.session-header {
    background: white;
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.session-title {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.session-title h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
}

.session-badge-active {
    background: var(--success);
    color: white;
    padding: 0.25rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.session-badge-closed {
    background: var(--dark);
    color: white;
    padding: 0.25rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.summary-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.25rem;
}

.summary-card .label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}

.summary-card .value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
}

.summary-card .value-success {
    color: var(--success);
}

.summary-card .value-warning {
    color: var(--warning);
}

.summary-card .value-danger {
    color: var(--danger);
}

.payments-table {
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
}

.payments-table table {
    width: 100%;
    border-collapse: collapse;
}

.payments-table th {
    background: var(--light);
    padding: 1rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
}

.payments-table td {
    padding: 1rem;
    border-top: 1px solid var(--border);
}

.payment-method-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.method-cash {
    background: rgba(16,185,129,0.1);
    color: var(--success);
}

.method-card {
    background: rgba(59,130,246,0.1);
    color: var(--primary);
}

.method-mobile {
    background: rgba(245,158,11,0.1);
    color: var(--warning);
}

.empty-payments {
    text-align: center;
    padding: 3rem;
    color: var(--gray-400);
}

.empty-payments i {
    font-size: 3rem;
    margin-bottom: 1rem;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="cashier-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="cashier-title">
                    <i class="fas fa-cash-register me-2" style="color:var(--primary)"></i>
                    Détails de la Session #{{ $cashierSession->id }}
                </h1>
            </div>
        </div>
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard.index') }}">
                        <i class="fas fa-home"></i> Accueil
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('cashier.dashboard') }}">Caissier</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('cashier.sessions.index') }}">Sessions</a>
                </li>
                <li class="breadcrumb-item active">Session #{{ $cashierSession->id }}</li>
            </ol>
        </nav>
    </div>

    <!-- En-tête de la session -->
    <div class="session-header">
        <div class="session-title">
            <i class="fas fa-cash-register fa-2x" style="color: var(--primary);"></i>
            <div>
                <h2>Session #{{ $cashierSession->id }}</h2>
                <div class="d-flex gap-2 mt-1">
                    <span class="session-badge-{{ $cashierSession->status }}">
                        {{ $cashierSession->status == 'active' ? 'Active' : 'Fermée' }}
                    </span>
                    <small class="text-muted">
                        <i class="fas fa-user me-1"></i>{{ $cashierSession->user->name }}
                    </small>
                </div>
            </div>
        </div>
        <div>
            @if($cashierSession->status == 'active' && auth()->id() == $cashierSession->user_id)
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#closeModal">
                <i class="fas fa-lock me-2"></i>Clôturer
            </button>
            @endif
        </div>
    </div>

    <!-- Cartes résumé -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="label">Période</div>
            <div class="value">
                {{ $cashierSession->start_time->format('d/m/Y') }}
            </div>
            <div class="text-muted small">
                {{ $cashierSession->start_time->format('H:i') }} - 
                {{ $cashierSession->end_time ? $cashierSession->end_time->format('H:i') : 'En cours' }}
            </div>
        </div>

        <div class="summary-card">
            <div class="label">Solde initial</div>
            <div class="value">{{ number_format($cashierSession->initial_balance, 0, ',', ' ') }} FCFA</div>
        </div>

        <div class="summary-card">
            <div class="label">Total encaissé</div>
            <div class="value value-success">
                {{ number_format($stats['totalAmount'] ?? 0, 0, ',', ' ') }} FCFA
            </div>
            <div class="text-muted small">{{ $stats['totalPayments'] ?? 0 }} paiement(s)</div>
        </div>

        <div class="summary-card">
            <div class="label">Solde final</div>
            <div class="value {{ $cashierSession->balance_difference > 0 ? 'value-success' : ($cashierSession->balance_difference < 0 ? 'value-danger' : '') }}">
                {{ number_format($cashierSession->final_balance ?? $cashierSession->current_balance, 0, ',', ' ') }} FCFA
            </div>
            @if($cashierSession->balance_difference != 0)
            <div class="small {{ $cashierSession->balance_difference > 0 ? 'text-success' : 'text-danger' }}">
                <i class="fas {{ $cashierSession->balance_difference > 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} me-1"></i>
                Écart: {{ number_format(abs($cashierSession->balance_difference), 0, ',', ' ') }} FCFA
            </div>
            @endif
        </div>
    </div>

    <!-- Liste des paiements -->
    <div class="payments-table">
        <div class="card-header bg-white p-3 border-bottom">
            <h5 class="mb-0">
                <i class="fas fa-list me-2" style="color: var(--primary);"></i>
                Historique des paiements
            </h5>
        </div>
        
        @if($payments->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Méthode</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td><strong>{{ $payment->reference }}</strong></td>
                        <td>{{ $payment->created_at->format('d/m H:i') }}</td>
                        <td>
                            @if($payment->transaction && $payment->transaction->customer)
                                {{ $payment->transaction->customer->name }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong>
                        </td>
                        <td>
                            @php
                                $methodClass = 'method-cash';
                                $icon = 'fa-money-bill-wave';
                                if($payment->payment_method == 'card') {
                                    $methodClass = 'method-card';
                                    $icon = 'fa-credit-card';
                                } elseif($payment->payment_method == 'mobile_money') {
                                    $methodClass = 'method-mobile';
                                    $icon = 'fa-mobile-alt';
                                }
                            @endphp
                            <span class="payment-method-badge {{ $methodClass }}">
                                <i class="fas {{ $icon }}"></i>
                                {{ $payment->payment_method_label }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $payment->status_class }}">
                                {{ $payment->status_text }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('payment.show', $payment) }}" class="btn btn-sm btn-info btn-icon">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if(method_exists($payments, 'links'))
        <div class="p-3 border-top">
            {{ $payments->links() }}
        </div>
        @endif
        
        @else
        <div class="empty-payments">
            <i class="fas fa-money-bill-wave"></i>
            <h5>Aucun paiement</h5>
            <p class="text-muted">Aucun paiement n'a été enregistré pendant cette session.</p>
        </div>
        @endif
    </div>
</div>

<!-- Modal de clôture -->
@if($cashierSession->status == 'active' && auth()->id() == $cashierSession->user_id)
<div class="modal fade" id="closeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-lock text-danger me-2"></i>Clôturer la session
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('cashier.sessions.destroy', $cashierSession) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Cette action est irréversible.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Solde final (réel)</label>
                        <input type="number" name="final_balance" class="form-control" 
                               step="0.01" value="{{ $cashierSession->current_balance }}" required>
                        <small class="text-muted">Montant réel en caisse</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Notes de clôture</label>
                        <textarea name="closing_notes" class="form-control" rows="3" 
                                  placeholder="Observations, anomalies..."></textarea>
                    </div>

                    <div class="bg-light p-3 rounded">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total théorique:</span>
                            <strong>{{ number_format($cashierSession->current_balance, 0, ',', ' ') }} FCFA</strong>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-lock me-2"></i>Clôturer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection