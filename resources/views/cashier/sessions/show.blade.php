@extends('template.master')

@section('title', 'Détails de la Session #' . $cashierSession->id)

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    /* ── 4 COULEURS (vert, rouge, gris, blanc) ── */
    --green-50:  #f0faf0;
    --green-100: #d4edda;
    --green-500: #2e8540;
    --green-600: #1e6b2e;
    --green-700: #155221;

    --red-50:    #fee2e2;
    --red-100:   #fecaca;
    --red-500:   #b91c1c;
    --red-600:   #991b1b;

    --gray-50:   #f8f9f8;
    --gray-100:  #eff0ef;
    --gray-200:  #dde0dd;
    --gray-300:  #c2c7c2;
    --gray-400:  #9ba09b;
    --gray-500:  #737873;
    --gray-600:  #545954;
    --gray-700:  #3a3e3a;
    --gray-800:  #252825;
    --gray-900:  #131513;

    --white:     #ffffff;
    --surface:   #f7f9f7;

    --shadow-xs: 0 1px 2px rgba(0,0,0,.04);
    --shadow-sm: 0 1px 6px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow-md: 0 4px 16px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.04);

    --r:   8px;
    --rl:  14px;
    --rxl: 20px;
    --transition: all .2s ease;
    --font: 'DM Sans', system-ui, sans-serif;
    --mono: 'DM Mono', monospace;
}

* { box-sizing: border-box; }

.show-page {
    background: var(--surface);
    min-height: 100vh;
    padding: 24px 32px;
    font-family: var(--font);
    color: var(--gray-800);
}

/* ── Animations ── */
@keyframes fadeSlide {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
.anim-1 { animation: fadeSlide .4s ease both; }
.anim-2 { animation: fadeSlide .4s .08s ease both; }
.anim-3 { animation: fadeSlide .4s .16s ease both; }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.show-header {
    background: var(--white);
    border-bottom: 1.5px solid var(--gray-200);
    padding: 20px 0;
    margin-bottom: 24px;
}
.header-title {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0;
}
.header-title i {
    color: var(--green-600);
    margin-right: 10px;
}
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .8rem;
    color: var(--gray-400);
}
.breadcrumb a {
    color: var(--gray-400);
    text-decoration: none;
    transition: var(--transition);
}
.breadcrumb a:hover {
    color: var(--green-600);
}
.breadcrumb .active {
    color: var(--gray-600);
    font-weight: 500;
}

/* ══════════════════════════════════════════════
   BUTTONS
══════════════════════════════════════════════ */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: var(--r);
    font-size: .8rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}
.btn-green {
    background: var(--green-600);
    color: white;
}
.btn-green:hover {
    background: var(--green-700);
    transform: translateY(-1px);
    color: white;
}
.btn-gray {
    background: var(--white);
    color: var(--gray-600);
    border: 1.5px solid var(--gray-200);
}
.btn-gray:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.btn-red {
    background: var(--red-500);
    color: white;
}
.btn-red:hover {
    background: var(--red-600);
    transform: translateY(-1px);
    color: white;
}
.btn-sm {
    padding: 6px 12px;
    font-size: .7rem;
}
.btn-icon {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--r);
    border: 1.5px solid var(--gray-200);
    background: var(--white);
    color: var(--gray-500);
}
.btn-icon:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}

/* ══════════════════════════════════════════════
   SESSION HEADER CARD
══════════════════════════════════════════════ */
.session-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    padding: 22px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}
.session-info {
    display: flex;
    align-items: center;
    gap: 16px;
}
.session-icon {
    width: 56px;
    height: 56px;
    background: var(--green-50);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: var(--green-600);
}
.session-title h2 {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 4px;
}
.session-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 100px;
    font-size: .7rem;
    font-weight: 600;
}
.badge-green { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.badge-gray { background: var(--gray-100); color: var(--gray-700); border: 1.5px solid var(--gray-200); }

/* ══════════════════════════════════════════════
   STATS CARDS
══════════════════════════════════════════════ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}
.stat-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 18px;
}
.stat-label {
    font-size: .65rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    margin-bottom: 6px;
}
.stat-value {
    font-size: 1.6rem;
    font-weight: 700;
    font-family: var(--mono);
    color: var(--gray-900);
    line-height: 1;
    margin-bottom: 4px;
}
.stat-value.green { color: var(--green-600); }
.stat-value.red { color: var(--red-500); }
.stat-sub {
    font-size: .7rem;
    color: var(--gray-400);
}
.stat-diff {
    font-size: .7rem;
    margin-top: 4px;
}
.stat-diff.green { color: var(--green-600); }
.stat-diff.red { color: var(--red-500); }

/* ══════════════════════════════════════════════
   TABLE
══════════════════════════════════════════════ */
.table-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
}
.table-card-header {
    padding: 16px 22px;
    border-bottom: 1.5px solid var(--gray-200);
    display: flex;
    align-items: center;
    gap: 8px;
}
.table-card-header h5 {
    font-size: .95rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
}
.table-card-header i {
    color: var(--green-600);
}
.table-responsive {
    overflow-x: auto;
}
.table {
    width: 100%;
    border-collapse: collapse;
}
.table thead th {
    background: var(--gray-50);
    padding: 14px 18px;
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    border-bottom: 1.5px solid var(--gray-200);
    text-align: left;
}
.table tbody td {
    padding: 16px 18px;
    border-bottom: 1px solid var(--gray-200);
    color: var(--gray-700);
    font-size: .8rem;
    vertical-align: middle;
}
.table tbody tr:hover td {
    background: var(--green-50);
}

/* ── Payment badges ── */
.payment-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 100px;
    font-size: .68rem;
    font-weight: 600;
}
.payment-cash { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.payment-card { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.payment-mobile { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }

/* ── Status badges ── */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 100px;
    font-size: .68rem;
    font-weight: 600;
}
.status-completed { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.status-pending { background: var(--gray-100); color: var(--gray-700); border: 1.5px solid var(--gray-200); }

/* ── Montants ── */
.amount {
    font-weight: 600;
    font-family: var(--mono);
}
.amount.green { color: var(--green-600); }
.amount.red { color: var(--red-500); }

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    text-align: center;
    padding: 48px 24px;
}
.empty-icon {
    width: 72px;
    height: 72px;
    background: var(--gray-100);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: var(--gray-300);
    margin-bottom: 16px;
}
.empty-state h5 {
    font-size: .95rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 4px;
}
.empty-state p {
    color: var(--gray-400);
    font-size: .8rem;
}

/* ══════════════════════════════════════════════
   MODAL
══════════════════════════════════════════════ */
.modal-content {
    border-radius: var(--rxl);
    border: 1.5px solid var(--gray-200);
}
.modal-header {
    border-bottom: 1.5px solid var(--gray-200);
    padding: 18px 24px;
}
.modal-body {
    padding: 24px;
}
.modal-footer {
    border-top: 1.5px solid var(--gray-200);
    padding: 16px 24px;
}
.alert-warning {
    background: var(--red-50);
    border: 1.5px solid var(--red-100);
    color: var(--red-500);
    padding: 14px 18px;
    border-radius: var(--rl);
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 20px;
}
.summary-box {
    background: var(--gray-50);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 16px;
    margin-bottom: 20px;
}
.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px dashed var(--gray-200);
}
.summary-row:last-child {
    border-bottom: none;
}
.form-label {
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    margin-bottom: 6px;
}
.form-control {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .85rem;
}
.form-control:focus {
    outline: none;
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgba(46,133,64,.1);
}
</style>
@endpush

@section('content')
<div class="show-page">

    {{-- Header --}}
    <div class="show-header anim-1">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-cash-register"></i>
                    Détails de la Session #{{ $cashierSession->id }}
                </h1>
            </div>
        </div>
        
        <div class="breadcrumb">
            <a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Accueil</a>
            <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
            <a href="{{ route('cashier.dashboard') }}">Caissier</a>
            <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
            <a href="{{ route('cashier.sessions.index') }}">Sessions</a>
            <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
            <span class="active">#{{ $cashierSession->id }}</span>
        </div>
    </div>

    {{-- En-tête de la session --}}
    <div class="session-card anim-2">
        <div class="session-info">
            <div class="session-icon">
                <i class="fas fa-cash-register"></i>
            </div>
            <div class="session-title">
                <h2>Session #{{ $cashierSession->id }}</h2>
                <div class="d-flex gap-2 mt-1">
                    <span class="session-badge {{ $cashierSession->status == 'active' ? 'badge-green' : 'badge-gray' }}">
                        {{ $cashierSession->status == 'active' ? 'Active' : 'Fermée' }}
                    </span>
                    <small class="text-muted">
                        <i class="fas fa-user me-1" style="color:var(--green-600);"></i>
                        {{ $cashierSession->user->name }}
                    </small>
                </div>
            </div>
        </div>
        <div>
            @if($cashierSession->status == 'active' && auth()->id() == $cashierSession->user_id)
            <button class="btn btn-red" data-bs-toggle="modal" data-bs-target="#closeModal">
                <i class="fas fa-lock"></i> Clôturer
            </button>
            @endif
        </div>
    </div>

    {{-- Calculs --}}
    @php
        $encaissements = $payments->where('status', 'completed')->where('amount', '>', 0)->sum('amount');
        $remboursements = abs($payments->where('status', 'completed')->where('amount', '<', 0)->sum('amount'));
        $netTotal = $encaissements - $remboursements;
        $paymentCount = $payments->where('status', 'completed')->count();
        $difference = $cashierSession->balance_difference ?? ($netTotal - $cashierSession->initial_balance);
    @endphp

    {{-- Cartes résumé --}}
    <div class="stats-grid anim-3">
        <div class="stat-card">
            <div class="stat-label">Période</div>
            <div class="stat-value">{{ $cashierSession->start_time->format('d/m/Y') }}</div>
            <div class="stat-sub">
                {{ $cashierSession->start_time->format('H:i') }} - 
                {{ $cashierSession->end_time ? $cashierSession->end_time->format('H:i') : 'En cours' }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Solde initial</div>
            <div class="stat-value">{{ number_format($cashierSession->initial_balance, 0, ',', ' ') }}</div>
            <div class="stat-sub">FCFA</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Encaissements</div>
            <div class="stat-value green">{{ number_format($encaissements, 0, ',', ' ') }}</div>
            <div class="stat-sub">{{ $paymentCount }} paiement(s)</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Remboursements</div>
            <div class="stat-value red">{{ number_format($remboursements, 0, ',', ' ') }}</div>
            <div class="stat-sub">{{ $payments->where('status', 'completed')->where('amount', '<', 0)->count() }} tx</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Net</div>
            <div class="stat-value {{ $netTotal >= 0 ? 'green' : 'red' }}">{{ number_format($netTotal, 0, ',', ' ') }}</div>
            <div class="stat-sub">FCFA</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Solde final</div>
            <div class="stat-value {{ ($netTotal + $cashierSession->initial_balance) >= 0 ? 'green' : 'red' }}">
                {{ number_format($netTotal + $cashierSession->initial_balance, 0, ',', ' ') }}
            </div>
            @if($difference != 0)
            <div class="stat-diff {{ $difference > 0 ? 'green' : 'red' }}">
                <i class="fas {{ $difference > 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                Écart: {{ number_format(abs($difference), 0, ',', ' ') }}
            </div>
            @endif
        </div>
    </div>

    {{-- Liste des paiements --}}
    <div class="table-card">
        <div class="table-card-header">
            <i class="fas fa-list"></i>
            <h5>Historique des paiements</h5>
            <span class="badge {{ $payments->count() > 0 ? 'badge-green' : 'badge-gray' }}" style="margin-left:auto;">
                {{ $payments->count() }} transaction(s)
            </span>
        </div>
        
        @if($payments->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Méthode</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    @php
                        $isPositive = $payment->amount > 0;
                        $methodIcon = 'fa-money-bill-wave';
                        if($payment->payment_method == 'card' || $payment->payment_method == 'fedapay') {
                            $methodIcon = 'fa-credit-card';
                        } elseif($payment->payment_method == 'mobile_money') {
                            $methodIcon = 'fa-mobile-alt';
                        }
                    @endphp
                    <tr>
                        <td><span style="font-family:var(--mono);">#{{ $payment->reference }}</span></td>
                        <td>
                            <div>{{ $payment->created_at->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            @if($payment->transaction && $payment->transaction->customer)
                                <div class="fw-semibold">{{ $payment->transaction->customer->name }}</div>
                                <small class="text-muted">{{ $payment->transaction->customer->phone ?? '' }}</small>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <span class="amount {{ $isPositive ? 'green' : 'red' }}">
                                {{ $isPositive ? '+' : '-' }} {{ number_format(abs($payment->amount), 0, ',', ' ') }} FCFA
                            </span>
                        </td>
                        <td>
                            <span class="payment-badge payment-cash">
                                <i class="fas {{ $methodIcon }}"></i>
                                {{ $payment->payment_method_label }}
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $payment->status }}">
                                {{ $payment->status == 'completed' ? '✓ Complété' : '⏱ En attente' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('payment.show', $payment) }}" class="btn-icon" title="Voir détails">
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
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <h5>Aucun paiement</h5>
            <p class="text-muted">Aucun paiement n'a été enregistré pendant cette session.</p>
        </div>
        @endif
    </div>

</div>

{{-- Modal de clôture --}}
@if($cashierSession->status == 'active' && auth()->id() == $cashierSession->user_id)
<div class="modal fade" id="closeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-lock text-danger me-2"></i>Clôturer la session</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('cashier.sessions.destroy', $cashierSession) }}" method="POST">
                @csrf @method('DELETE')
                <div class="modal-body">
                    <div class="alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Cette action est irréversible.
                    </div>
                    
                    <div class="summary-box">
                        <div class="summary-row">
                            <span>Solde initial</span>
                            <span class="fw-bold">{{ number_format($cashierSession->initial_balance, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="summary-row">
                            <span>Encaissements</span>
                            <span class="fw-bold green">{{ number_format($encaissements, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="summary-row">
                            <span>Remboursements</span>
                            <span class="fw-bold red">{{ number_format($remboursements, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="summary-row">
                            <span>Solde théorique</span>
                            <span class="fw-bold">{{ number_format($netTotal + $cashierSession->initial_balance, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Solde final réel</label>
                        <input type="number" name="final_balance" class="form-control" 
                               step="0.01" value="{{ $netTotal + $cashierSession->initial_balance }}" required>
                        <small class="text-muted">Montant réel en caisse</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="closing_notes" class="form-control" rows="3" 
                                  placeholder="Observations, anomalies..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-red">
                        <i class="fas fa-lock"></i> Clôturer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection