@extends('template.master')

@section('title', 'Dashboard Caissier')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
    --primary: #3b82f6;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --info: #06b6d4;
    --dark: #1e293b;
    --light: #f8fafc;
    --border: #e2e8f0;
    --shadow: rgba(0,0,0,0.05);
}

body {
    font-family: 'Inter', sans-serif;
    background: var(--light);
}

/* Header */
.cashier-header {
    background: white;
    border-bottom: 1px solid var(--border);
    padding: 1.5rem 0;
    margin-bottom: 1.5rem;
}

.cashier-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--dark);
    margin: 0;
}

.user-info-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #64748b;
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.role-admin {
    background: rgba(16,185,129,0.1);
    color: #059669;
}

.role-receptionist {
    background: rgba(59,130,246,0.1);
    color: #2563eb;
}

.role-cashier {
    background: rgba(245,158,11,0.1);
    color: #d97706;
}

/* Permission alerts */
.permission-alert {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border: 1px solid #fbbf24;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
}

.permission-alert-icon {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #f59e0b;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.permission-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.75rem;
}

.permission-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
}

.permission-badge i {
    font-size: 0.75rem;
}

/* Active session card */
.active-session {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    border: 2px solid #10b981;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}

.active-session::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(16,185,129,0.1) 0%, transparent 70%);
    border-radius: 50%;
    transform: translate(50%, -50%);
}

.session-icon {
    width: 50px;
    height: 50px;
    background: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: var(--success);
    flex-shrink: 0;
    box-shadow: 0 4px 6px rgba(16,185,129,0.1);
}

.session-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 1rem;
    background: white;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

/* No session card */
.no-session {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border: 2px dashed #f59e0b;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

/* Stats cards */
.stat-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.25rem;
    transition: all 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px var(--shadow);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.stat-label {
    font-size: 0.8125rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: var(--dark);
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-subtitle {
    font-size: 0.75rem;
    color: #94a3b8;
}

/* Tabs */
.nav-tabs {
    border: none;
    background: white;
    border-radius: 12px;
    padding: 0.5rem;
    box-shadow: 0 1px 3px var(--shadow);
}

.nav-tabs .nav-link {
    border: none;
    border-radius: 8px;
    color: #64748b;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.75rem 1.25rem;
    transition: all 0.2s;
}

.nav-tabs .nav-link:hover {
    background: var(--light);
    color: var(--dark);
}

.nav-tabs .nav-link.active {
    background: var(--primary);
    color: white;
}

.nav-tabs .badge {
    margin-left: 0.5rem;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.nav-tabs .nav-link.active .badge {
    background: rgba(255,255,255,0.2);
}

/* Table */
.table-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
}

.table {
    margin: 0;
}

.table thead {
    background: var(--light);
}

.table thead th {
    border: none;
    padding: 1rem 1.25rem;
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table tbody td {
    border-color: var(--border);
    padding: 1rem 1.25rem;
    vertical-align: middle;
}

.table tbody tr:hover {
    background: var(--light);
}

/* Badges */
.badge {
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.75rem;
}

.badge-success-soft {
    background: rgba(16,185,129,0.1);
    color: #059669;
}

.badge-warning-soft {
    background: rgba(245,158,11,0.1);
    color: #d97706;
}

.badge-danger-soft {
    background: rgba(239,68,68,0.1);
    color: #dc2626;
}

.badge-info-soft {
    background: rgba(6,182,212,0.1);
    color: #0891b2;
}

.badge-dark-soft {
    background: rgba(30,41,59,0.1);
    color: #1e293b;
}

/* Buttons */
.btn {
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    transition: all 0.2s;
}

.btn-icon {
    width: 36px;
    height: 36px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-primary {
    background: var(--primary);
    border-color: var(--primary);
}

.btn-primary:hover {
    background: #2563eb;
    border-color: #2563eb;
    transform: translateY(-2px);
}

/* Payment method badges */
.payment-method-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}

.method-cash {
    background: rgba(16,185,129,0.1);
    color: #059669;
}

.method-card {
    background: rgba(59,130,246,0.1);
    color: #2563eb;
}

.method-mobile {
    background: rgba(245,158,11,0.1);
    color: #d97706;
}

/* Empty states */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: var(--light);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #cbd5e1;
    margin-bottom: 1.5rem;
}

/* User avatar */
.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
}

/* Filters */
.filters-row {
    background: white;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1rem;
    border: 1px solid var(--border);
}

/* Modal */
.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.modal-header {
    border-bottom: 1px solid var(--border);
    padding: 1.5rem;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    border-top: 1px solid var(--border);
    padding: 1.5rem;
}

/* Summary card */
.summary-card {
    background: var(--light);
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px dashed var(--border);
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-label {
    color: #64748b;
    font-size: 0.875rem;
}

.summary-value {
    font-weight: 700;
    color: var(--dark);
}

/* Responsive */
@media (max-width: 768px) {
    .stat-value {
        font-size: 1.5rem;
    }
    
    .cashier-title {
        font-size: 1.5rem;
    }
    
    .stat-card {
        margin-bottom: 1rem;
    }
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
                    Dashboard Caissier
                </h1>
                <div class="user-info-badge mt-2">
                    Bonjour, <strong class="mx-1">{{ auth()->user()->name }}</strong>
                    <span class="role-badge {{ $isAdmin ? 'role-admin' : ($isCashier ? 'role-cashier' : 'role-receptionist') }}">
                        <i class="fas {{ $isAdmin ? 'fa-crown' : ($isCashier ? 'fa-cash-register' : 'fa-user') }}"></i>
                        {{ auth()->user()->role }}
                    </span>
                </div>
            </div>
            
            @if($isAdmin && $canStartSession && !$activeSession)
            <div>
                <a href="{{ route('cashier.sessions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nouvelle session
                </a>
            </div>
            @endif
        </div>
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard.index') }}">
                        <i class="fas fa-home"></i> Accueil
                    </a>
                </li>
                <li class="breadcrumb-item active">Caissier</li>
            </ol>
        </nav>
    </div>

    <!-- Receptionist/Cashier permission notice -->
    @if(!$isAdmin)
    <div class="permission-alert">
        <div class="d-flex align-items-start gap-3">
            <div class="permission-alert-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="fw-bold mb-1">
                    @if($isCashier)
                    Mode Caissier
                    @else
                    Mode Lecture Seule
                    @endif
                </h6>
                <p class="mb-2 small">
                    @if($isCashier)
                    Vous pouvez gérer votre session et les paiements.
                    @else
                    Vous pouvez consulter les données mais seuls les administrateurs peuvent effectuer des modifications.
                    @endif
                </p>
                <div class="permission-badges">
                    <span class="permission-badge">
                        <i class="fas fa-check text-success"></i> Visualisation
                    </span>
                    @if($isCashier)
                    <span class="permission-badge">
                        <i class="fas fa-check text-success"></i> Paiements
                    </span>
                    <span class="permission-badge">
                        <i class="fas fa-times text-danger"></i> Administration
                    </span>
                    @else
                    <span class="permission-badge">
                        <i class="fas fa-times text-danger"></i> Modification
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Active session -->
    @if($activeSession)
    <div class="active-session">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="session-icon pulse">
                    <i class="fas fa-play-circle"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-2">
                        Session Active #{{ $activeSession->id }}
                        @if($activeSession->user_id != auth()->id())
                        <span class="badge badge-dark-soft">{{ $activeSession->user->name }}</span>
                        @endif
                    </h5>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="session-badge">
                            <i class="fas fa-user"></i>
                            <span>{{ $activeSession->user->name }}</span>
                        </div>
                        <div class="session-badge">
                            <i class="fas fa-clock"></i>
                            <span>{{ $activeSession->start_time->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="session-badge">
                            <i class="fas fa-hourglass-half"></i>
                            <span>{{ $activeSession->start_time->diffForHumans(now(), true) }}</span>
                        </div>
                        <div class="session-badge">
                            <i class="fas fa-wallet" style="color:var(--success)"></i>
                            <strong style="color:var(--success)">{{ number_format($activeSession->current_balance, 0, ',', ' ') }} FCFA</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('cashier.sessions.show', $activeSession) }}" class="btn btn-outline-success">
                    <i class="fas fa-eye"></i> Détails
                </a>
                @if(($isAdmin && $activeSession->user_id == auth()->id()) || $isCashier)
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#closeModal">
                    <i class="fas fa-lock"></i> Clôturer
                </button>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="no-session">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="session-icon" style="background:white;color:var(--warning)">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Aucune session active</h5>
                    <p class="mb-0 small text-muted">
                        @if($isAdmin || $isCashier)
                        Démarrez une nouvelle session pour commencer
                        @else
                        Contactez un administrateur pour démarrer une session
                        @endif
                    </p>
                </div>
            </div>
            @if(($isAdmin || $isCashier) && $canStartSession)
            <a href="{{ route('cashier.sessions.create') }}" class="btn btn-warning">
                <i class="fas fa-play"></i> Démarrer une session
            </a>
            @endif
        </div>
    </div>
    @endif

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Réservations</div>
                        <div class="stat-value">{{ $todayStats['totalBookings'] }}</div>
                        <div class="stat-subtitle">Aujourd'hui</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(59,130,246,0.1);color:var(--primary)">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Chiffre d'affaires</div>
                        <div class="stat-value">{{ number_format($todayStats['revenue'], 0, ',', ' ') }}</div>
                        <div class="stat-subtitle">FCFA aujourd'hui</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(16,185,129,0.1);color:var(--success)">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Check-ins</div>
                        <div class="stat-value">{{ $todayStats['checkins'] }}</div>
                        <div class="stat-subtitle">Aujourd'hui</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(6,182,212,0.1);color:var(--info)">
                        <i class="fas fa-door-open"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">En attente</div>
                        <div class="stat-value">{{ $todayStats['pendingPayments'] }}</div>
                        <div class="stat-subtitle">Paiements</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(245,158,11,0.1);color:var(--warning)">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pending">
                <i class="fas fa-clock me-1"></i> Paiements
                <span class="badge bg-warning">{{ $pendingPayments->count() }}</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sessions">
                <i class="fas fa-history me-1"></i> Mes sessions
                <span class="badge bg-info">{{ $recentSessions->count() }}</span>
            </button>
        </li>
        @if($isAdmin)
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#all-sessions">
                <i class="fas fa-users me-1"></i> Toutes les sessions
                <span class="badge bg-dark">{{ $allSessionsCount ?? 0 }}</span>
            </button>
        </li>
        @endif
    </ul>

    <!-- Tab content -->
    <div class="tab-content">
        
        <!-- Pending payments -->
        <div class="tab-pane fade show active" id="pending">
            <div class="table-card">
                @if($activeSession && $activeSession->payments && $activeSession->payments->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Montant</th>
                                <th>Client</th>
                                <th>Méthode</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeSession->payments as $payment)
                            <tr>
                                <td><strong>#{{ $payment->reference }}</strong></td>
                                <td>
                                    <span class="badge badge-success-soft">
                                        {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                                    </span>
                                </td>
                                <td>
                                    @if($payment->transaction && $payment->transaction->customer)
                                        {{ $payment->transaction->customer->name }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $methodIcon = 'fa-money-bill-wave';
                                        $methodClass = 'method-cash';
                                        if($payment->payment_method == 'card') {
                                            $methodIcon = 'fa-credit-card';
                                            $methodClass = 'method-card';
                                        } elseif($payment->payment_method == 'mobile_money') {
                                            $methodIcon = 'fa-mobile-alt';
                                            $methodClass = 'method-mobile';
                                        }
                                    @endphp
                                    <span class="payment-method-badge {{ $methodClass }}">
                                        <i class="fas {{ $methodIcon }} me-1"></i>
                                        {{ $payment->payment_method_label }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $payment->created_at->format('d/m H:i') }}</small>
                                </td>
                                <td>
                                    <a href="#" 
                                       class="btn btn-sm btn-info btn-icon" 
                                       title="Voir détail"
                                       data-bs-toggle="modal" 
                                       data-bs-target="#paymentModal{{ $payment->id }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="6" class="text-end">
                                    <strong>Total encaissé : 
                                        {{ number_format($activeSession->current_balance, 0, ',', ' ') }} FCFA
                                    </strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Aucun paiement</h5>
                    <p class="text-muted">
                        @if($activeSession)
                            Aucun paiement n'a été effectué pendant cette session.
                        @else
                            Démarrez une session pour commencer à enregistrer des paiements.
                        @endif
                    </p>
                    @if(!$activeSession && ($isAdmin || $isCashier))
                    <a href="{{ route('cashier.sessions.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-play me-2"></i>Démarrer une session
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- My sessions -->
        <div class="tab-pane fade" id="sessions">
            <div class="table-card">
                @if($recentSessions->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Session</th>
                                <th>Début</th>
                                <th>Fin</th>
                                <th>Durée</th>
                                <th>Initial</th>
                                <th>Final</th>
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
                                    <span class="badge badge-warning-soft">En cours</span>
                                    @endif
                                </td>
                                <td>
                                    @if($session->end_time)
                                    <span class="badge badge-dark-soft">
                                        @php
                                            $minutes = $session->start_time->diffInMinutes($session->end_time);
                                            $hours = floor($minutes / 60);
                                            $remainingMinutes = $minutes % 60;
                                        @endphp
                                        @if($hours > 0)
                                            {{ $hours }}h {{ $remainingMinutes }}min
                                        @else
                                            {{ $remainingMinutes }} min
                                        @endif
                                    </span>
                                    @else
                                    <span class="badge badge-info-soft">
                                        {{ $session->start_time->diffForHumans(now(), true) }}
                                    </span>
                                    @endif
                                </td>
                                <td>{{ number_format($session->initial_balance, 0, ',', ' ') }} FCFA</td>
                                <td>{{ number_format($session->final_balance, 0, ',', ' ') }} FCFA</td>
                                <td>
                                    @if($session->status == 'active')
                                    <span class="badge badge-success-soft">Active</span>
                                    @else
                                    <span class="badge badge-dark-soft">Terminée</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('cashier.sessions.show', $session) }}" 
                                       class="btn btn-sm btn-info btn-icon" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Aucune session</h5>
                    <p class="text-muted">Vous n'avez pas encore de sessions</p>
                </div>
                @endif
            </div>
        </div>

        <!-- All sessions (admin) -->
        @if($isAdmin)
        <div class="tab-pane fade" id="all-sessions">
            <div class="filters-row">
                <div class="row g-2">
                    <div class="col-md-4">
                        <select class="form-select" id="userFilter">
                            <option value="">Tous les utilisateurs</option>
                            @foreach($allReceptionists as $receptionist)
                            <option value="{{ $receptionist->id }}">{{ $receptionist->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="statusFilter">
                            <option value="">Tous les statuts</option>
                            <option value="active">Actives</option>
                            <option value="closed">Terminées</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="date" class="form-control" id="dateFilter" value="{{ now()->format('Y-m-d') }}">
                    </div>
                </div>
            </div>
            
            <div class="table-card">
                @if(isset($allSessions) && $allSessions->count() > 0)
                <div class="table-responsive">
                    <table class="table" id="sessionsTable">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Session</th>
                                <th>Début</th>
                                <th>Fin</th>
                                <th>Durée</th>
                                <th>Initial</th>
                                <th>Final</th>
                                <th>Différence</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allSessions as $session)
                            <tr data-user="{{ $session->user_id }}" 
                                data-status="{{ $session->status }}"
                                data-date="{{ $session->start_time->format('Y-m-d') }}">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($session->user && $session->user->avatar)
                                        <img src="{{ $session->user->avatar }}" class="user-avatar" alt="">
                                        @else
                                        <div class="user-avatar bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <div class="fw-medium">{{ $session->user->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $session->user->role ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><strong>#{{ $session->id }}</strong></td>
                                <td>{{ $session->start_time->format('d/m H:i') }}</td>
                                <td>
                                    @if($session->end_time)
                                    {{ $session->end_time->format('d/m H:i') }}
                                    @else
                                    <span class="badge badge-warning-soft">En cours</span>
                                    @endif
                                </td>
                                <td>
                                    @if($session->end_time)
                                    <span class="badge badge-dark-soft">
                                        @php
                                            $minutes = $session->start_time->diffInMinutes($session->end_time);
                                            $hours = floor($minutes / 60);
                                            $remainingMinutes = $minutes % 60;
                                        @endphp
                                        @if($hours > 0)
                                            {{ $hours }}h {{ $remainingMinutes }}min
                                        @else
                                            {{ $remainingMinutes }} min
                                        @endif
                                    </span>
                                    @else
                                    <span class="badge badge-info-soft">
                                        {{ $session->start_time->diffForHumans(now(), true) }}
                                    </span>
                                    @endif
                                </td>
                                <td>{{ number_format($session->initial_balance, 0, ',', ' ') }}</td>
                                <td>{{ number_format($session->final_balance, 0, ',', ' ') }}</td>
                                <td>
                                    @php
                                        $diff = $session->final_balance - $session->initial_balance;
                                    @endphp
                                    <span class="badge {{ $diff >= 0 ? 'badge-success-soft' : 'badge-danger-soft' }}">
                                        {{ $diff >= 0 ? '+' : '' }}{{ number_format($diff, 0, ',', ' ') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $session->status == 'active' ? 'badge-success-soft' : 'badge-dark-soft' }}">
                                        {{ $session->status == 'active' ? 'Active' : 'Terminée' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('cashier.sessions.show', $session) }}" 
                                       class="btn btn-sm btn-info btn-icon" title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="p-3 border-top">
                    {{ $allSessions->links() }}
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Aucune session</h5>
                    <p class="text-muted">Aucune session n'a été créée</p>
                </div>
                @endif
            </div>
        </div>
        @endif
        
    </div>
</div>

<!-- Close modal -->
@if($activeSession && ($isAdmin || $isCashier) && $activeSession->user_id == auth()->id())
<div class="modal fade" id="closeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-lock text-danger me-2"></i>
                    Clôturer la session #{{ $activeSession->id }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('cashier.sessions.destroy', $activeSession) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Cette action est irréversible. Vérifiez le solde avant de clôturer.
                    </div>
                    
                    <!-- Résumé de la session -->
                    <div class="summary-card">
                        <h6 class="fw-bold mb-3">Récapitulatif de la session</h6>
                        <div class="summary-item">
                            <span class="summary-label">Début de session</span>
                            <span class="summary-value">{{ $activeSession->start_time->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Durée</span>
                            <span class="summary-value">{{ $activeSession->start_time->diffForHumans(now(), true) }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Solde théorique</span>
                            <span class="summary-value text-success">{{ number_format($activeSession->current_balance, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Nombre de paiements</span>
                            <span class="summary-value">{{ $activeSession->payments->count() }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium">
                            <i class="fas fa-calculator me-1 text-primary"></i>
                            Solde final réel <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                   name="final_balance" 
                                   id="finalBalance"
                                   class="form-control form-control-lg" 
                                   step="0.01" 
                                   min="0"
                                   value="{{ $activeSession->current_balance }}" 
                                   required>
                            <span class="input-group-text">FCFA</span>
                        </div>
                        <div class="form-text" id="balanceHelp">
                            Montant physique présent dans la caisse
                            @if($activeSession->current_balance > 0)
                            <br>Solde attendu : <strong>{{ number_format($activeSession->current_balance, 0, ',', ' ') }} FCFA</strong>
                            @endif
                        </div>
                        
                        <!-- Différence indicator -->
                        <div class="mt-2" id="differenceAlert" style="display: none;">
                            <div class="alert alert-info py-2">
                                <i class="fas fa-info-circle me-2"></i>
                                Différence : <span id="differenceAmount">0</span> FCFA
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">
                            <i class="fas fa-sticky-note me-1 text-primary"></i>
                            Notes de clôture
                        </label>
                        <textarea name="closing_notes" 
                                  class="form-control" 
                                  rows="3" 
                                  placeholder="Observations, anomalies, remarques..."></textarea>
                    </div>
                    
                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle me-2"></i>
                        Une différence sera automatiquement enregistrée comme ajustement.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-danger" id="closeConfirmBtn">
                        <i class="fas fa-lock me-2"></i>Confirmer la clôture
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calcul de la différence en temps réel
    const finalBalanceInput = document.getElementById('finalBalance');
    const theoreticalBalance = {{ $activeSession->current_balance }};
    const differenceAlert = document.getElementById('differenceAlert');
    const differenceAmount = document.getElementById('differenceAmount');
    
    if (finalBalanceInput) {
        finalBalanceInput.addEventListener('input', function() {
            const finalBalance = parseFloat(this.value) || 0;
            const difference = finalBalance - theoreticalBalance;
            
            if (Math.abs(difference) > 0.01) { // Tolérance de 0.01
                differenceAlert.style.display = 'block';
                differenceAmount.textContent = difference.toLocaleString('fr-FR', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
                
                if (difference > 0) {
                    differenceAlert.className = 'mt-2 alert alert-success py-2';
                    differenceAmount.style.color = '#10b981';
                } else {
                    differenceAlert.className = 'mt-2 alert alert-danger py-2';
                    differenceAmount.style.color = '#ef4444';
                }
            } else {
                differenceAlert.style.display = 'none';
            }
        });
    }
    
    // Filtres pour le tableau des sessions
    const userFilter = document.getElementById('userFilter');
    const statusFilter = document.getElementById('statusFilter');
    const dateFilter = document.getElementById('dateFilter');
    const table = document.getElementById('sessionsTable');
    
    if (userFilter && statusFilter && dateFilter && table) {
        const filterRows = function() {
            const selectedUser = userFilter.value;
            const selectedStatus = statusFilter.value;
            const selectedDate = dateFilter.value;
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                let show = true;
                
                if (selectedUser && row.dataset.user !== selectedUser) {
                    show = false;
                }
                
                if (selectedStatus && row.dataset.status !== selectedStatus) {
                    show = false;
                }
                
                if (selectedDate && row.dataset.date !== selectedDate) {
                    show = false;
                }
                
                row.style.display = show ? '' : 'none';
            });
        };
        
        userFilter.addEventListener('change', filterRows);
        statusFilter.addEventListener('change', filterRows);
        dateFilter.addEventListener('change', filterRows);
    }
});
</script>
@endpush
@endif

@endsection