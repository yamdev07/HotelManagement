@extends('template.master')
@section('title', 'Gestion des Réservations')
@section('content')

<style>
/* ═══════════════════════════════════════════════════════════════
   STYLES TRANSACTION INDEX - Design moderne cohérent
═══════════════════════════════════════════════════════════════════ */
:root {
    --primary: #2563eb;
    --primary-light: #3b82f6;
    --primary-soft: rgba(37, 99, 235, 0.08);
    --success: #10b981;
    --success-light: rgba(16, 185, 129, 0.08);
    --warning: #f59e0b;
    --warning-light: rgba(245, 158, 11, 0.08);
    --danger: #ef4444;
    --danger-light: rgba(239, 68, 68, 0.08);
    --info: #3b82f6;
    --info-light: rgba(59, 130, 246, 0.08);
    --dark: #1e293b;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1e293b;
    --radius: 12px;
    --shadow: 0 4px 20px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.05);
    --shadow-hover: 0 10px 30px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
    --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ────────── CARTE PRINCIPALE ────────── */
.transaction-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
    overflow: hidden;
    transition: var(--transition);
    margin-bottom: 24px;
}
.transaction-card:hover {
    box-shadow: var(--shadow-hover);
    border-color: var(--gray-300);
}

/* ────────── EN-TÊTE DE CARTE ────────── */
.transaction-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
    background: white;
    border-bottom: 1px solid var(--gray-200);
    flex-wrap: wrap;
    gap: 16px;
}
.transaction-card-header h5 {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-800);
    letter-spacing: -0.01em;
}
.transaction-card-header h5 i {
    color: var(--primary);
    font-size: 1.1rem;
}

/* ────────── BADGES STATUT ────────── */
.badge-statut {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    line-height: 1;
    white-space: nowrap;
    gap: 4px;
    border: none;
    cursor: pointer;
    transition: var(--transition);
}
.badge-statut:hover {
    transform: translateY(-1px);
    filter: brightness(0.95);
}
.badge-reservation {
    background: var(--warning-light);
    color: #b45309;
    border: 1px solid rgba(245, 158, 11, 0.15);
}
.badge-active {
    background: var(--success-light);
    color: #047857;
    border: 1px solid rgba(16, 185, 129, 0.15);
}
.badge-completed {
    background: var(--info-light);
    color: #1e40af;
    border: 1px solid rgba(37, 99, 235, 0.15);
}
.badge-cancelled {
    background: var(--danger-light);
    color: #b91c1c;
    border: 1px solid rgba(239, 68, 68, 0.15);
}
.badge-no_show {
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1px solid var(--gray-200);
}
.badge-unpaid {
    background: var(--danger-light);
    color: #b91c1c;
    border: 1px solid rgba(239, 68, 68, 0.15);
}

/* ────────── LÉGENDE STATUTS ────────── */
.legend-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 0.7rem;
    font-weight: 600;
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    color: var(--gray-700);
    transition: var(--transition);
}
.legend-badge i {
    font-size: 0.65rem;
}
.legend-badge:hover {
    background: white;
    border-color: var(--gray-300);
    transform: translateY(-1px);
}

/* ────────── TABLEAU ────────── */
.transaction-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.transaction-table thead th {
    background: var(--gray-50);
    color: var(--gray-600);
    font-weight: 600;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    padding: 16px 12px;
    border-bottom: 1px solid var(--gray-200);
    white-space: nowrap;
}
.transaction-table tbody td {
    padding: 16px 12px;
    font-size: 0.85rem;
    color: var(--gray-700);
    border-bottom: 1px solid var(--gray-200);
    vertical-align: middle;
    transition: var(--transition);
}
.transaction-table tbody tr {
    transition: var(--transition);
}
.transaction-table tbody tr:hover td {
    background: var(--gray-50);
}
.transaction-table tbody tr.cancelled-row {
    opacity: 0.7;
    background: var(--gray-50);
}
.transaction-table tbody tr:last-child td {
    border-bottom: none;
}

/* ────────── INFOS CLIENT ────────── */
.client-info {
    display: flex;
    align-items: center;
    gap: 10px;
}
.client-avatar {
    width: 34px;
    height: 34px;
    border-radius: 30px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.75rem;
    flex-shrink: 0;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
}
.client-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 30px;
    object-fit: cover;
}
.client-details {
    display: flex;
    flex-direction: column;
}
.client-name {
    font-weight: 600;
    color: var(--gray-800);
}
.client-phone {
    font-size: 0.7rem;
    color: var(--gray-500);
    margin-top: 2px;
}

/* ────────── CHAMBRE ────────── */
.room-badge {
    background: var(--gray-100);
    color: var(--gray-700);
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 0.8rem;
    display: inline-block;
    border: 1px solid var(--gray-200);
}
.room-badge i {
    margin-right: 4px;
    font-size: 0.7rem;
    color: var(--gray-500);
}

/* ────────── PRIX ────────── */
.price {
    font-weight: 600;
    font-family: 'Inter', monospace;
    font-size: 0.9rem;
}
.price-positive {
    color: var(--gray-800);
}
.price-success {
    color: var(--success);
}
.price-danger {
    color: var(--danger);
    font-weight: 700;
}
.price-small {
    font-size: 0.7rem;
    font-weight: 400;
    color: var(--gray-500);
}

/* ────────── NUITS ────────── */
.nights-badge {
    background: var(--gray-100);
    color: var(--gray-600);
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 0.7rem;
    white-space: nowrap;
    border: 1px solid var(--gray-200);
}

/* ────────── BOUTONS D'ACTION ────────── */
.action-buttons {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
    justify-content: flex-end;
}
.btn-action {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--gray-200);
    background: white;
    color: var(--gray-600);
    font-size: 0.8rem;
    transition: var(--transition);
    text-decoration: none;
    cursor: pointer;
}
.btn-action:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-800);
    transform: translateY(-2px);
}
.btn-pay {
    background: var(--success-light);
    color: var(--success);
    border-color: rgba(16, 185, 129, 0.2);
}
.btn-pay:hover {
    background: var(--success);
    border-color: var(--success);
    color: white;
}
.btn-arrived {
    background: var(--success-light);
    color: var(--success);
    border-color: rgba(16, 185, 129, 0.2);
}
.btn-arrived:hover {
    background: var(--success);
    border-color: var(--success);
    color: white;
}
.btn-departed {
    background: var(--info-light);
    color: var(--info);
    border-color: rgba(59, 130, 246, 0.2);
}
.btn-departed:hover {
    background: var(--info);
    border-color: var(--info);
    color: white;
}
.btn-edit {
    background: var(--gray-100);
    color: var(--gray-600);
}
.btn-edit:hover {
    background: var(--gray-200);
    color: var(--gray-800);
}
.btn-view {
    background: var(--gray-50);
    color: var(--gray-500);
}
.btn-view:hover {
    background: var(--gray-100);
    color: var(--gray-700);
}
.btn-action.disabled {
    opacity: 0.4;
    cursor: not-allowed;
    pointer-events: none;
}

/* ────────── INDICATEURS DATE ────────── */
.date-indicator {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
    margin-top: 3px;
    background: var(--gray-100);
    color: var(--gray-600);
}
.date-indicator.upcoming {
    background: var(--warning-light);
    color: #b45309;
}
.date-indicator.ready {
    background: var(--success-light);
    color: #047857;
}
.date-indicator.overdue {
    background: var(--danger-light);
    color: #b91c1c;
}
.date-indicator.pending {
    background: var(--info-light);
    color: #1e40af;
}

/* ────────── ALERTE IMPAYÉ ────────── */
.unpaid-alert {
    background: var(--danger-light);
    border-left: 3px solid var(--danger);
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.7rem;
    margin-top: 4px;
}
.unpaid-alert a {
    color: var(--danger);
    font-weight: 600;
    text-decoration: none;
}
.unpaid-alert a:hover {
    text-decoration: underline;
}

/* ────────── DROPDOWN STATUT ────────── */
.status-dropdown-menu {
    min-width: 180px;
    padding: 8px;
    border-radius: 10px;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-hover);
}
.status-dropdown-item {
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 500;
    transition: var(--transition);
    cursor: pointer;
    width: 100%;
    text-align: left;
    background: white;
    border: none;
    margin-bottom: 2px;
}
.status-dropdown-item:hover {
    background: var(--gray-50);
    transform: translateX(2px);
}
.status-dropdown-item:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}
.status-dropdown-divider {
    margin: 6px 0;
    border-top: 1px solid var(--gray-200);
}

/* ────────── BOUTONS PRINCIPAUX ────────── */
.btn-primary-custom {
    background: var(--primary);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.85rem;
    transition: var(--transition);
}
.btn-primary-custom:hover {
    background: var(--primary-light);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.2);
}
.btn-outline-custom {
    background: transparent;
    color: var(--gray-700);
    border: 1px solid var(--gray-300);
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.85rem;
    transition: var(--transition);
}
.btn-outline-custom:hover {
    background: var(--gray-50);
    border-color: var(--gray-400);
    transform: translateY(-2px);
}

/* ────────── NOTE RÉCEPTIONNISTE ────────── */
.receptionist-note-modern {
    background: linear-gradient(135deg, #fef3c7, #fffbeb);
    border-left: 4px solid #f59e0b;
    border-radius: 8px;
    padding: 16px 20px;
    margin-bottom: 24px;
    border: 1px solid rgba(245, 158, 11, 0.15);
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.05);
}
.receptionist-note-modern i {
    color: #d97706;
}

/* ────────── PAGINATION ────────── */
.pagination-modern {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    margin-top: 20px;
}
.pagination-modern .page-item {
    list-style: none;
}
.pagination-modern .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 6px;
    border: 1px solid var(--gray-200);
    background: white;
    color: var(--gray-600);
    font-size: 0.8rem;
    font-weight: 500;
    transition: var(--transition);
}
.pagination-modern .page-link:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-800);
    transform: translateY(-2px);
}
.pagination-modern .active .page-link {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

/* ────────── BADGE INFO PERMISSIONS ────────── */
.info-badge-modern {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
</style>

<div class="container-fluid px-4 py-3">

    <!-- En-tête avec boutons -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h5 mb-1" style="color: var(--gray-800); font-weight: 700;">
                <i class="fas fa-calendar-check me-2" style="color: var(--primary);"></i>
                Gestion des Réservations
            </h2>
            <p class="text-muted small mb-0">Gérez les arrivées, séjours et départs</p>
        </div>
        
        <div class="d-flex gap-2">
            @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']))
            <span data-bs-toggle="tooltip" title="Nouvelle Réservation">
                <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <i class="fas fa-plus me-2"></i>Nouvelle Réservation
                </button>
            </span>
            @endif
            
            <span data-bs-toggle="tooltip" title="Historique des Paiements">
                <a href="{{ route('payment.index') }}" class="btn btn-outline-custom">
                    <i class="fas fa-history me-2"></i>Historique
                </a>
            </span>
            
            @if(auth()->user()->role == 'Receptionist')
            <span class="info-badge-modern">
                <i class="fas fa-user-check"></i>
                <span>Permissions complètes</span>
            </span>
            @endif
        </div>
    </div>

    <!-- Légende des statuts -->
    <div class="d-flex flex-wrap gap-2 mb-4">
        <span class="legend-badge"><i class="fas fa-circle text-warning"></i> Réservation</span>
        <span class="legend-badge"><i class="fas fa-circle text-success"></i> Dans l'hôtel</span>
        <span class="legend-badge"><i class="fas fa-circle text-info"></i> Terminé (payé)</span>
        <span class="legend-badge"><i class="fas fa-circle text-danger"></i> Annulée</span>
        <span class="legend-badge"><i class="fas fa-circle text-secondary"></i> No Show</span>
        <span class="legend-badge"><i class="fas fa-exclamation-triangle text-warning"></i> Terminé mais impayé</span>
    </div>

    <!-- Formulaire de recherche -->
    <div class="transaction-card mb-4">
        <div class="transaction-card-header">
            <h5><i class="fas fa-search"></i> Rechercher</h5>
        </div>
        <div class="p-3">
            <form method="GET" action="{{ route('transaction.index') }}" class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" 
                       placeholder="ID, nom client ou chambre..." 
                       name="search" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary-custom">
                    <i class="fas fa-search"></i>
                </button>
                @if(request('search'))
                <a href="{{ route('transaction.index') }}" class="btn btn-outline-custom">
                    <i class="fas fa-times"></i>
                </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Note spéciale pour réceptionnistes -->
    @if(auth()->user()->role == 'Receptionist')
    <div class="receptionist-note-modern d-flex align-items-center gap-3">
        <i class="fas fa-info-circle fa-2x"></i>
        <div>
            <strong class="d-block mb-1">💼 Réceptionniste - Permissions Complètes</strong>
            <small class="d-block">Création, modification, paiements, check-in/out, annulation ✓ (sauf suppression)</small>
        </div>
    </div>
    @endif

    <!-- Messages de session -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {!! session('success') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    
    @if(session('error') || session('failed'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') ?? session('failed') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Message spécial départ -->
    @if(session('departure_success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <strong>{{ session('departure_success')['title'] }}</strong><br>
        {{ session('departure_success')['message'] }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Réservations Actives -->
    <div class="transaction-card">
        <div class="transaction-card-header">
            <h5><i class="fas fa-users"></i> Réservations en cours <span class="badge bg-primary ms-2">{{ $transactions->count() }}</span></h5>
            <span class="text-muted small">Arrivées & séjours en cours</span>
        </div>

        <div class="table-responsive">
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Chambre</th>
                        <th>Arrivée</th>
                        <th>Départ</th>
                        <th>Nuits</th>
                        <th>Total</th>
                        <th>Payé</th>
                        <th>Reste</th>
                        <th class="text-center">Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                    @php
                        $totalPrice = $transaction->getTotalPrice();
                        $totalPayment = $transaction->getTotalPayment();
                        $remaining = $totalPrice - $totalPayment;
                        $isFullyPaid = $remaining <= 0;
                        $status = $transaction->status;
                        
                        $checkIn = \Carbon\Carbon::parse($transaction->check_in);
                        $checkOut = \Carbon\Carbon::parse($transaction->check_out);
                        $nights = $checkIn->diffInDays($checkOut);
                        
                        $isAdmin = in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']);
                        $isSuperAdmin = auth()->user()->role == 'Super';
                        $isReceptionist = auth()->user()->role == 'Receptionist';
                        
                        $canPay = !in_array($status, ['cancelled', 'no_show']) && !$isFullyPaid && $isAdmin;
                        $canMarkArrived = $isAdmin && $status == 'reservation';
                        $canMarkDeparted = $isAdmin && $status == 'active';
                        
                        $today = \Carbon\Carbon::today();
                        $checkInDate = $checkIn->copy()->startOfDay();
                        $checkOutDate = $checkOut->copy()->startOfDay();
                        
                        $canMarkArrivedNow = $canMarkArrived && $today->greaterThanOrEqualTo($checkInDate);
                        $arrivalNotReached = $status == 'reservation' && $today->lessThan($checkInDate);
                        $arrivalDelay = $arrivalNotReached ? $today->diffInDays($checkInDate) : 0;
                        
                        $canMarkDepartedNow = $canMarkDeparted && $today->greaterThanOrEqualTo($checkOutDate) && $isFullyPaid;
                        $departureNotReached = $status == 'active' && $today->lessThan($checkOutDate);
                        $departureDelay = $departureNotReached ? $today->diffInDays($checkOutDate) : 0;
                    @endphp
                    <tr class="{{ in_array($status, ['cancelled', 'no_show']) ? 'cancelled-row' : '' }}">
                        <td><span style="color: var(--gray-500); font-weight: 500;">#{{ $transaction->id }}</span></td>
                        
                        <td>
                            <div class="client-info">
                                <div class="client-avatar">
                                    @if($transaction->customer->user && $transaction->customer->user->getAvatar())
                                        <img src="{{ $transaction->customer->user->getAvatar() }}" alt="{{ $transaction->customer->name }}">
                                    @else
                                        {{ strtoupper(substr($transaction->customer->name, 0, 1)) }}{{ strtoupper(substr(strstr($transaction->customer->name, ' ', true) ?: substr($transaction->customer->name, 1, 1), 0, 1)) }}
                                    @endif
                                </div>
                                <div class="client-details">
                                    <span class="client-name">{{ $transaction->customer->name }}</span>
                                    <span class="client-phone">{{ $transaction->customer->phone ?? '' }}</span>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            <span class="room-badge"><i class="fas fa-door-closed"></i> {{ $transaction->room->number }}</span>
                        </td>
                        
                        <td>
                            <div>{{ $checkIn->format('d/m/Y') }}</div>
                            <small style="color: var(--gray-500);">{{ $checkIn->format('H:i') }}</small>
                            @if($status == 'reservation' && $today->lessThan($checkInDate))
                                <div class="date-indicator upcoming"><i class="fas fa-clock me-1"></i> J-{{ $arrivalDelay }}</div>
                            @elseif($status == 'reservation' && $today->greaterThanOrEqualTo($checkInDate))
                                <div class="date-indicator ready"><i class="fas fa-check-circle me-1"></i> Prêt</div>
                            @endif
                        </td>
                        
                        <td>
                            <div>{{ $checkOut->format('d/m/Y') }}</div>
                            <small style="color: var(--gray-500);">{{ $checkOut->format('H:i') }}</small>
                            @if($status == 'active' && $today->lessThan($checkOutDate))
                                <div class="date-indicator pending"><i class="fas fa-hourglass-half me-1"></i> J-{{ $departureDelay }}</div>
                            @elseif($status == 'active' && $today->greaterThanOrEqualTo($checkOutDate))
                                <div class="date-indicator overdue"><i class="fas fa-exclamation-triangle me-1"></i> Dépassé</div>
                            @endif
                        </td>
                        
                        <td>
                            <span class="nights-badge">{{ $nights }} nuit{{ $nights > 1 ? 's' : '' }}</span>
                        </td>
                        
                        <td class="price price-positive">{{ number_format($totalPrice, 0, ',', ' ') }} CFA</td>
                        
                        <td class="price price-success">{{ number_format($totalPayment, 0, ',', ' ') }} CFA</td>
                        
                        <td>
                            @if($isFullyPaid)
                                <span class="badge-statut badge-active"><i class="fas fa-check-circle me-1"></i> Soldé</span>
                            @else
                                <span class="price price-danger">{{ number_format($remaining, 0, ',', ' ') }} CFA</span>
                                @if($checkOut->isPast() && $status == 'active')
                                    <div class="unpaid-alert">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        <a href="{{ route('transaction.payment.create', $transaction) }}">Régler</a>
                                    </div>
                                @endif
                            @endif
                        </td>
                        
                        <td class="text-center">
                            @if($isAdmin)
                            <!-- Badge cliquable avec dropdown pour changer le statut -->
                            <div class="dropdown">
                                <button class="badge-statut badge-{{ $status == 'reservation' ? 'reservation' : ($status == 'active' ? 'active' : ($status == 'completed' ? 'completed' : ($status == 'cancelled' ? 'cancelled' : 'no_show'))) }} dropdown-toggle" 
                                        type="button" data-bs-toggle="dropdown" style="border: none;">
                                    @if($status == 'reservation') 📅
                                    @elseif($status == 'active') 🏨
                                    @elseif($status == 'completed') ✅
                                    @elseif($status == 'cancelled') ❌
                                    @else 👤
                                    @endif
                                    {{ $status == 'reservation' ? 'Réservation' : ($status == 'active' ? 'Dans hôtel' : ($status == 'completed' ? 'Terminé' : ($status == 'cancelled' ? 'Annulée' : 'No Show'))) }}
                                </button>
                                <ul class="dropdown-menu status-dropdown-menu">
                                    <li>
                                        <form action="{{ route('transaction.updateStatus', $transaction) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="reservation">
                                            <button type="submit" class="status-dropdown-item" {{ $status == 'reservation' ? 'disabled' : '' }}>
                                                📅 Réservation
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('transaction.updateStatus', $transaction) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="status-dropdown-item" {{ $status == 'active' ? 'disabled' : '' }}>
                                                🏨 Dans l'hôtel
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('transaction.updateStatus', $transaction) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="status-dropdown-item" {{ !$isFullyPaid ? 'disabled' : '' }} {{ $status == 'completed' ? 'disabled' : '' }}>
                                                ✅ Terminé {{ !$isFullyPaid ? '(impayé)' : '' }}
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="status-dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('transaction.updateStatus', $transaction) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="status-dropdown-item text-danger" {{ $status == 'cancelled' ? 'disabled' : '' }}>
                                                ❌ Annulée
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('transaction.updateStatus', $transaction) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="no_show">
                                            <button type="submit" class="status-dropdown-item text-secondary" {{ $status == 'no_show' ? 'disabled' : '' }}>
                                                👤 No Show
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            @else
                            <span class="badge-statut badge-{{ $status == 'reservation' ? 'reservation' : ($status == 'active' ? 'active' : ($status == 'completed' ? 'completed' : ($status == 'cancelled' ? 'cancelled' : 'no_show'))) }}">
                                @if($status == 'reservation') 📅
                                @elseif($status == 'active') 🏨
                                @elseif($status == 'completed') ✅
                                @elseif($status == 'cancelled') ❌
                                @else 👤
                                @endif
                                {{ $status == 'reservation' ? 'Réservation' : ($status == 'active' ? 'Dans hôtel' : ($status == 'completed' ? 'Terminé' : ($status == 'cancelled' ? 'Annulée' : 'No Show'))) }}
                            </span>
                            @endif
                        </td>
                        
                        <td>
                            <div class="action-buttons">
                                @if($canPay)
                                <a href="{{ route('transaction.payment.create', $transaction) }}" class="btn-action btn-pay" data-bs-toggle="tooltip" title="Paiement">
                                    <i class="fas fa-money-bill-wave-alt"></i>
                                </a>
                                @endif
                                
                                @if($canMarkArrivedNow)
                                <form action="{{ route('transaction.mark-arrived', $transaction) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-action btn-arrived" data-bs-toggle="tooltip" title="Arrivé">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </button>
                                </form>
                                @elseif($arrivalNotReached)
                                <span class="btn-action disabled" data-bs-toggle="tooltip" title="Arrivée le {{ $checkInDate->format('d/m/Y') }}">
                                    <i class="fas fa-clock"></i>
                                </span>
                                @endif
                                
                                @if($canMarkDepartedNow)
                                <button type="button" class="btn-action btn-departed mark-departed-btn"
                                        data-transaction-id="{{ $transaction->id }}"
                                        data-check-out="{{ $checkOutDate->format('d/m/Y') }}"
                                        data-is-fully-paid="{{ $isFullyPaid ? 'true' : 'false' }}"
                                        data-remaining="{{ $remaining }}"
                                        data-form-action="{{ route('transaction.mark-departed', $transaction) }}"
                                        data-bs-toggle="tooltip" title="Départ">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                                @elseif($departureNotReached)
                                <span class="btn-action disabled" data-bs-toggle="tooltip" title="Départ le {{ $checkOutDate->format('d/m/Y') }}">
                                    <i class="fas fa-hourglass-half"></i>
                                </span>
                                @endif
                                
                                @if($isSuperAdmin || ($isReceptionist && !in_array($status, ['cancelled', 'no_show', 'completed'])))
                                <a href="{{ route('transaction.edit', $transaction) }}" class="btn-action btn-edit" data-bs-toggle="tooltip" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                
                                <a href="{{ route('transaction.show', $transaction) }}" class="btn-action btn-view" data-bs-toggle="tooltip" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center py-5">
                            <i class="fas fa-bed fa-3x mb-3" style="color: var(--gray-300);"></i>
                            <h5 style="color: var(--gray-600);">Aucune réservation active</h5>
                            <p class="text-muted small">Commencez par créer une nouvelle réservation</p>
                            @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']))
                            <button class="btn btn-primary-custom mt-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                <i class="fas fa-plus me-2"></i>Nouvelle réservation
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
        <div class="p-3 border-top">
            {{ $transactions->onEachSide(2)->links('template.paginationlinks', ['class' => 'pagination-modern']) }}
        </div>
        @endif
    </div>

    <!-- Anciennes réservations -->
    @if($transactionsExpired->isNotEmpty())
    <div class="transaction-card mt-4">
        <div class="transaction-card-header">
            <h5><i class="fas fa-history"></i> Anciennes réservations <span class="badge bg-secondary ms-2">{{ $transactionsExpired->count() }}</span></h5>
            <span class="text-muted small">Terminées ou expirées</span>
        </div>

        <div class="table-responsive">
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Chambre</th>
                        <th>Arrivée</th>
                        <th>Départ</th>
                        <th>Nuits</th>
                        <th>Total</th>
                        <th>Payé</th>
                        <th>Reste</th>
                        <th class="text-center">Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactionsExpired as $transaction)
                    @php
                        $totalPrice = $transaction->getTotalPrice();
                        $totalPayment = $transaction->getTotalPayment();
                        $remaining = $totalPrice - $totalPayment;
                        $isFullyPaid = $remaining <= 0;
                        $status = $transaction->status;
                        
                        $checkIn = \Carbon\Carbon::parse($transaction->check_in);
                        $checkOut = \Carbon\Carbon::parse($transaction->check_out);
                        $nights = $checkIn->diffInDays($checkOut);
                        
                        $isAdmin = in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']);
                        $canPay = !in_array($status, ['cancelled', 'no_show']) && !$isFullyPaid && $isAdmin;
                    @endphp
                    <tr class="{{ in_array($status, ['cancelled', 'no_show']) ? 'cancelled-row' : '' }}">
                        <td><span style="color: var(--gray-500);">#{{ $transaction->id }}</span></td>
                        <td>{{ $transaction->customer->name }}</td>
                        <td><span class="room-badge">{{ $transaction->room->number }}</span></td>
                        <td>{{ $checkIn->format('d/m/Y') }}</td>
                        <td>{{ $checkOut->format('d/m/Y') }}</td>
                        <td><span class="nights-badge">{{ $nights }} nuit{{ $nights > 1 ? 's' : '' }}</span></td>
                        <td class="price price-positive">{{ number_format($totalPrice, 0, ',', ' ') }} CFA</td>
                        <td class="price price-success">{{ number_format($totalPayment, 0, ',', ' ') }} CFA</td>
                        <td>
                            @if($isFullyPaid)
                                <span class="badge-statut badge-active">Soldé</span>
                            @else
                                <span class="price price-danger">{{ number_format($remaining, 0, ',', ' ') }} CFA</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge-statut badge-{{ $status == 'reservation' ? 'reservation' : ($status == 'active' ? 'active' : ($status == 'completed' ? 'completed' : ($status == 'cancelled' ? 'cancelled' : 'no_show'))) }}">
                                @if($status == 'reservation') 📅
                                @elseif($status == 'active') 🏨
                                @elseif($status == 'completed') ✅
                                @elseif($status == 'cancelled') ❌
                                @else 👤
                                @endif
                                {{ $status == 'reservation' ? 'Réservation' : ($status == 'active' ? 'Dans hôtel' : ($status == 'completed' ? 'Terminé' : ($status == 'cancelled' ? 'Annulée' : 'No Show'))) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                @if($canPay)
                                <a href="{{ route('transaction.payment.create', $transaction) }}" class="btn-action btn-pay" data-bs-toggle="tooltip" title="Payer dette">
                                    <i class="fas fa-money-bill-wave-alt"></i>
                                </a>
                                @endif
                                <a href="{{ route('transaction.show', $transaction) }}" class="btn-action btn-view" data-bs-toggle="tooltip" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->user()->role == 'Super' && $status == 'cancelled')
                                <form action="{{ route('transaction.restore', $transaction) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-action" style="background: #20c997; color: white;" onclick="return confirm('Restaurer ?')">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<!-- Modal nouvelle réservation -->
@if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']))
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none;">
            <div class="modal-header" style="background: var(--gray-50); border-bottom: 1px solid var(--gray-200);">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle text-primary me-2"></i>
                    Nouvelle Réservation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="mb-4">Le client a-t-il déjà un compte ?</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('transaction.reservation.createIdentity') }}" class="btn btn-primary-custom">
                        <i class="fas fa-user-plus me-2"></i>Nouveau compte
                    </a>
                    <a href="{{ route('transaction.reservation.pickFromCustomer') }}" class="btn btn-outline-custom">
                        <i class="fas fa-users me-2"></i>Client existant
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Formulaire annulation masqué -->
<form id="cancel-form" method="POST" action="{{ route('transaction.cancel', 0) }}" class="d-none">
    @csrf @method('DELETE')
    <input type="hidden" name="transaction_id" id="cancel-transaction-id-input">
    <input type="hidden" name="cancel_reason" id="cancel-reason-input">
</form>
@endsection

@section('footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) {
        return new bootstrap.Tooltip(el);
    });

    // Gestion des changements de statut via dropdown
    document.querySelectorAll('.status-dropdown-item').forEach(item => {
        item.addEventListener('click', function(e) {
            const form = this.closest('form');
            const transactionId = form.querySelector('input[name="transaction_id"]')?.value || '{{ $transaction->id }}';
            const newStatus = form.querySelector('input[name="status"]').value;
            
            // Confirmation pour cancelled
            if (newStatus === 'cancelled') {
                e.preventDefault();
                Swal.fire({
                    title: 'Annuler cette réservation ?',
                    html: '<textarea id="reason" class="form-control mt-2" placeholder="Raison (optionnelle)"></textarea>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, annuler',
                    cancelButtonText: 'Non'
                }).then(result => {
                    if (result.isConfirmed) {
                        const reason = document.getElementById('reason')?.value || '';
                        document.getElementById('cancel-reason-input').value = reason;
                        document.getElementById('cancel-transaction-id-input').value = transactionId;
                        document.getElementById('cancel-form').action = `/transaction/${transactionId}/cancel`;
                        document.getElementById('cancel-form').submit();
                    }
                });
                return false;
            }
            
            // Confirmation pour no_show
            if (newStatus === 'no_show') {
                e.preventDefault();
                Swal.fire({
                    title: 'Marquer comme "No Show" ?',
                    text: 'Le client ne s\'est pas présenté',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Oui',
                    cancelButtonText: 'Non'
                }).then(result => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
                return false;
            }
        });
    });

    // Gestion boutons départ
    document.querySelectorAll('.mark-departed-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const formAction = this.dataset.formAction;
            const checkOut = this.dataset.checkOut;
            const isPaid = this.dataset.isFullyPaid === 'true';
            
            // Vérification date
            const today = new Date(); today.setHours(0,0,0,0);
            const [d, m, y] = checkOut.split('/');
            const departure = new Date(y, m-1, d);
            
            if (today < departure) {
                Swal.fire('⏳ Date non atteinte', `Départ prévu le ${checkOut}`, 'warning');
                return;
            }
            
            if (!isPaid) {
                Swal.fire('❌ Paiement incomplet', 'Le client doit avoir soldé son séjour', 'error');
                return;
            }
            
            Swal.fire({
                title: 'Confirmer le départ ?',
                text: 'La chambre sera marquée comme à nettoyer',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui, départ',
                cancelButtonText: 'Annuler'
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = formAction;
                    form.innerHTML = '@csrf';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection