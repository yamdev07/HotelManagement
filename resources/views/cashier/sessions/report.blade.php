@extends('template.master')

@section('title', 'Rapport de Session #' . $session->id)

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

.report-container {
    max-width: 1200px;
    margin: 2rem auto;
}

.report-header {
    background: white;
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    text-align: center;
}

.report-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.report-subtitle {
    color: #64748b;
    font-size: 0.9rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
}

.stat-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--dark);
}

.stat-value-success {
    color: var(--success);
}

.stat-value-warning {
    color: var(--warning);
}

.stat-value-danger {
    color: var(--danger);
}

.payments-table {
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 2rem;
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
    color: #64748b;
    text-transform: uppercase;
}

.payments-table td {
    padding: 1rem;
    border-top: 1px solid var(--border);
}

.method-badge {
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

.summary-box {
    background: var(--light);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px dashed var(--border);
}

.summary-row:last-child {
    border-bottom: none;
}

.btn-print {
    background: var(--primary);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-print:hover {
    background: #2563eb;
    transform: translateY(-2px);
}

.btn-back {
    background: white;
    color: var(--dark);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
}

.btn-back:hover {
    background: var(--light);
}
</style>
@endpush

@section('content')
<div class="report-container">
    
    <!-- En-tête -->
    <div class="report-header">
        <h1 class="report-title">Rapport de Session #{{ $session->id }}</h1>
        <p class="report-subtitle">
            Généré le {{ now()->format('d/m/Y à H:i') }} par {{ auth()->user()->name }}
        </p>
    </div>

    <!-- Informations générales -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Réceptionniste</div>
            <div class="stat-value">{{ $session->user->name }}</div>
            <small class="text-muted">{{ $session->user->role }}</small>
        </div>

        <div class="stat-card">
            <div class="stat-label">Période</div>
            <div class="stat-value">{{ $session->start_time->format('d/m/Y') }}</div>
            <small class="text-muted">
                {{ $session->start_time->format('H:i') }} - 
                {{ $session->end_time ? $session->end_time->format('H:i') : 'En cours' }}
            </small>
        </div>

        <div class="stat-card">
            <div class="stat-label">Durée</div>
            <div class="stat-value">{{ $durationFormatted }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Statut</div>
            <div class="stat-value {{ $session->status == 'active' ? 'stat-value-success' : 'stat-value-warning' }}">
                {{ $session->status == 'active' ? 'Active' : 'Fermée' }}
            </div>
        </div>
    </div>

    <!-- Résumé financier -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Solde initial</div>
            <div class="stat-value">{{ number_format($session->initial_balance, 0, ',', ' ') }}</div>
            <small class="text-muted">FCFA</small>
        </div>

        <div class="stat-card">
            <div class="stat-label">Total encaissé</div>
            <div class="stat-value stat-value-success">{{ number_format($totalCompleted, 0, ',', ' ') }}</div>
            <small class="text-muted">{{ $paymentCount }} paiement(s)</small>
        </div>

        <div class="stat-card">
            <div class="stat-label">Total remboursé</div>
            <div class="stat-value stat-value-danger">{{ number_format($totalRefunded, 0, ',', ' ') }}</div>
            <small class="text-muted">FCFA</small>
        </div>

        <div class="stat-card">
            <div class="stat-label">Solde final</div>
            <div class="stat-value {{ $session->balance_difference > 0 ? 'stat-value-success' : ($session->balance_difference < 0 ? 'stat-value-danger' : '') }}">
                {{ number_format($session->final_balance ?? $session->current_balance, 0, ',', ' ') }}
            </div>
            @if($session->balance_difference != 0)
            <small class="text-{{ $session->balance_difference > 0 ? 'success' : 'danger' }}">
                Écart: {{ number_format(abs($session->balance_difference), 0, ',', ' ') }} FCFA
            </small>
            @endif
        </div>
    </div>

    <!-- Détail par méthode -->
    @if($byMethod->count() > 0)
    <div class="summary-box">
        <h5 class="mb-3">Répartition par mode de paiement</h5>
        <div class="row">
            @foreach($byMethod as $method)
            <div class="col-md-4 mb-3">
                <div class="p-3 bg-white rounded border">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fas {{ $method['icon'] }}" style="color: var(--primary);"></i>
                        <strong>{{ $method['method'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>{{ $method['count'] }} paiement(s)</span>
                        <span class="fw-bold">{{ number_format($method['total'], 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Liste des paiements -->
    @if($payments->count() > 0)
    <div class="payments-table">
        <div class="p-3 bg-white border-bottom">
            <h5 class="mb-0">Détail des paiements</h5>
        </div>
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
                        <td class="fw-bold {{ $payment->amount > 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
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
                            <span class="method-badge {{ $methodClass }}">
                                <i class="fas {{ $icon }}"></i>
                                {{ $payment->payment_method_label }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $payment->status_class }}">
                                {{ $payment->status_text }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Boutons d'action -->
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('cashier.sessions.show', $session) }}" class="btn-back">
            <i class="fas fa-arrow-left me-2"></i>Retour aux détails
        </a>
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print me-2"></i>Imprimer le rapport
        </button>
    </div>
</div>
@endsection