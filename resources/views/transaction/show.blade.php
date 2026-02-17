@extends('template.master')
@section('title', 'Détails de la Réservation #' . $transaction->id)
@section('content')

<style>
/* ═══════════════════════════════════════════════════════════════
   STYLES TRANSACTION SHOW - Design moderne cohérent
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
.detail-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
    overflow: hidden;
    transition: var(--transition);
    margin-bottom: 20px;
}
.detail-card:hover {
    box-shadow: var(--shadow-hover);
    border-color: var(--gray-300);
}

/* ────────── EN-TÊTE DE CARTE ────────── */
.detail-card .card-header {
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
    padding: 16px 20px;
}
.detail-card .card-header h5 {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--gray-800);
}
.detail-card .card-header h5 i {
    color: var(--primary);
    font-size: 1rem;
}

.detail-card .card-body {
    padding: 20px;
}

/* ────────── LABELS ET VALEURS ────────── */
.detail-label {
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    color: var(--gray-500);
    margin-bottom: 4px;
}
.detail-value {
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--gray-800);
    margin-bottom: 12px;
}

/* ────────── BADGES STATUT ────────── */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 0.8rem;
    font-weight: 600;
    line-height: 1;
    white-space: nowrap;
    gap: 6px;
    border: none;
}
.status-reservation {
    background: var(--warning-light);
    color: #b45309;
    border: 1px solid rgba(245, 158, 11, 0.15);
}
.status-active {
    background: var(--success-light);
    color: #047857;
    border: 1px solid rgba(16, 185, 129, 0.15);
}
.status-completed {
    background: var(--info-light);
    color: #1e40af;
    border: 1px solid rgba(37, 99, 235, 0.15);
}
.status-cancelled {
    background: var(--danger-light);
    color: #b91c1c;
    border: 1px solid rgba(239, 68, 68, 0.15);
}
.status-no_show {
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1px solid var(--gray-200);
}

/* ────────── BOUTONS ────────── */
.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 500;
    border: 1px solid transparent;
    transition: var(--transition);
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
}
.btn-modern:hover {
    transform: translateY(-2px);
    text-decoration: none;
}
.btn-primary-modern {
    background: var(--primary);
    color: white;
}
.btn-primary-modern:hover {
    background: var(--primary-light);
    color: white;
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.2);
}
.btn-success-modern {
    background: var(--success);
    color: white;
}
.btn-success-modern:hover {
    background: #0d9488;
    color: white;
}
.btn-warning-modern {
    background: var(--warning);
    color: white;
}
.btn-warning-modern:hover {
    background: #d97706;
    color: white;
}
.btn-info-modern {
    background: var(--info);
    color: white;
}
.btn-info-modern:hover {
    background: #2563eb;
    color: white;
}
.btn-outline-modern {
    background: transparent;
    color: var(--gray-700);
    border: 1px solid var(--gray-300);
}
.btn-outline-modern:hover {
    background: var(--gray-50);
    border-color: var(--gray-400);
    color: var(--gray-800);
}
.btn-outline-danger-modern {
    background: transparent;
    color: var(--danger);
    border: 1px solid var(--danger);
}
.btn-outline-danger-modern:hover {
    background: var(--danger);
    color: white;
}

/* ────────── SÉLECTEUR STATUT ────────── */
.status-select {
    padding: 6px 12px;
    border-radius: 6px;
    border: 1px solid var(--gray-200);
    background: white;
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--gray-700);
    transition: var(--transition);
    cursor: pointer;
    min-width: 150px;
}
.status-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
}

/* ────────── ALERTES STATUT ────────── */
.alert-status {
    border-radius: 8px;
    border: none;
    padding: 16px 20px;
    margin-bottom: 24px;
    border-left: 4px solid;
    background: white;
    box-shadow: var(--shadow);
}
.alert-status-reservation {
    border-left-color: var(--warning);
    background: var(--warning-light);
}
.alert-status-active {
    border-left-color: var(--success);
    background: var(--success-light);
}
.alert-status-completed {
    border-left-color: var(--info);
    background: var(--info-light);
}
.alert-status-cancelled {
    border-left-color: var(--danger);
    background: var(--danger-light);
}
.alert-status-no_show {
    border-left-color: var(--gray-500);
    background: var(--gray-100);
}

/* ────────── TIMELINE PAIEMENTS ────────── */
.timeline {
    position: relative;
    padding-left: 30px;
    margin-top: 20px;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--gray-200);
}
.timeline-item {
    position: relative;
    margin-bottom: 24px;
    padding-bottom: 8px;
}
.timeline-item::before {
    content: '';
    position: absolute;
    left: -23px;
    top: 6px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--primary);
    border: 2px solid white;
    box-shadow: 0 0 0 2px var(--primary-soft);
}
.timeline-item:last-child {
    margin-bottom: 0;
}

/* ────────── STATISTIQUES ────────── */
.stat-box {
    background: var(--gray-50);
    border-radius: 8px;
    padding: 16px;
    text-align: center;
    border: 1px solid var(--gray-200);
    transition: var(--transition);
}
.stat-box:hover {
    background: white;
    border-color: var(--gray-300);
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}
.stat-label {
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--gray-500);
    margin-bottom: 4px;
}
.stat-value {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--gray-800);
}
.stat-value-success {
    color: var(--success);
}
.stat-value-danger {
    color: var(--danger);
}
.stat-value-primary {
    color: var(--primary);
}

/* ────────── CARTE CLIENT ────────── */
.client-avatar {
    width: 60px;
    height: 60px;
    border-radius: 30px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.2rem;
    flex-shrink: 0;
    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
}
.client-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 30px;
    object-fit: cover;
}

/* ────────── BADGE CHAMBRE ────────── */
.room-badge-large {
    background: var(--primary-soft);
    color: var(--primary);
    font-weight: 700;
    padding: 8px 20px;
    border-radius: 30px;
    font-size: 1.1rem;
    display: inline-block;
    border: 1px solid rgba(37, 99, 235, 0.2);
}

/* ────────── PRIX ────────── */
.price-amount {
    font-weight: 700;
    font-family: 'Inter', monospace;
    font-size: 1.2rem;
}
.price-success {
    color: var(--success);
}
.price-danger {
    color: var(--danger);
}
.price-primary {
    color: var(--primary);
}

/* ────────── BADGE PAIEMENT ────────── */
.payment-status-paid {
    color: var(--success);
    font-weight: 600;
}
.payment-status-pending {
    color: var(--warning);
    font-weight: 600;
}
.payment-status-cancelled {
    color: var(--danger);
    font-weight: 600;
}

/* ────────── ACTIONS RAPIDES ────────── */
.quick-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 20px;
}

/* ────────── SÉPARATEUR ────────── */
.divider {
    height: 1px;
    background: var(--gray-200);
    margin: 20px 0;
}

/* ────────── BREADCRUMB ────────── */
.breadcrumb-modern {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    color: var(--gray-500);
    margin-bottom: 16px;
}
.breadcrumb-modern a {
    color: var(--gray-500);
    text-decoration: none;
}
.breadcrumb-modern a:hover {
    color: var(--primary);
}
.breadcrumb-modern .sep {
    color: var(--gray-300);
    font-size: 0.7rem;
}
</style>

<div class="container-fluid px-4 py-3">
    <!-- Breadcrumb -->
    <div class="breadcrumb-modern">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home fa-xs me-1"></i>Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('transaction.index') }}">Réservations</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span style="color: var(--gray-700); font-weight: 500;">#{{ $transaction->id }}</span>
    </div>

    <!-- En-tête avec titre et actions -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1" style="color: var(--gray-800); font-weight: 700;">
                <i class="fas fa-calendar-check me-2" style="color: var(--primary);"></i>
                Réservation #{{ $transaction->id }}
            </h2>
            <p class="text-muted small mb-0">
                {{ $transaction->customer->name }} · Chambre {{ $transaction->room->number }}
            </p>
        </div>
        
        <div class="d-flex gap-2 flex-wrap">
            <!-- Sélecteur de statut (admin seulement) -->
            @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']))
            <form action="{{ route('transaction.updateStatus', $transaction) }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <select name="status" class="status-select" onchange="this.form.submit()">
                    <option value="reservation" {{ $transaction->status == 'reservation' ? 'selected' : '' }}>📅 Réservation</option>
                    <option value="active" {{ $transaction->status == 'active' ? 'selected' : '' }}>🏨 Dans l'hôtel</option>
                    <option value="completed" {{ $transaction->status == 'completed' ? 'selected' : '' }}>✅ Terminé</option>
                    <option value="cancelled" {{ $transaction->status == 'cancelled' ? 'selected' : '' }}>❌ Annulée</option>
                    <option value="no_show" {{ $transaction->status == 'no_show' ? 'selected' : '' }}>👤 No Show</option>
                </select>
            </form>
            @endif
            
            <a href="{{ route('transaction.index') }}" class="btn-modern btn-outline-modern">
                <i class="fas fa-arrow-left me-1"></i>Retour
            </a>
        </div>
    </div>

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
    
    @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    
    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Alerte statut -->
    @if($transaction->status == 'reservation')
    <div class="alert-status alert-status-reservation d-flex align-items-center gap-3">
        <i class="fas fa-calendar-check fa-2x" style="color: #b45309;"></i>
        <div>
            <strong class="d-block mb-1">📅 RÉSERVATION</strong>
            <p class="mb-0 small">Arrivée prévue : <strong>{{ \Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y à H:i') }}</strong></p>
        </div>
    </div>
    @elseif($transaction->status == 'active')
    <div class="alert-status alert-status-active d-flex align-items-center gap-3">
        <i class="fas fa-bed fa-2x" style="color: #047857;"></i>
        <div>
            <strong class="d-block mb-1">🏨 DANS L'HÔTEL</strong>
            <p class="mb-0 small">Départ prévu : <strong>{{ \Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y à H:i') }}</strong>
            @if(\Carbon\Carbon::parse($transaction->check_out)->isPast())
                <br><span class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>Départ dépassé</span>
            @endif
            </p>
        </div>
    </div>
    @elseif($transaction->status == 'completed')
    <div class="alert-status alert-status-completed d-flex align-items-center gap-3">
        <i class="fas fa-check-circle fa-2x" style="color: #1e40af;"></i>
        <div>
            <strong class="d-block mb-1">✅ SÉJOUR TERMINÉ</strong>
            <p class="mb-0 small">Client parti, séjour terminé</p>
        </div>
    </div>
    @elseif($transaction->status == 'cancelled')
    <div class="alert-status alert-status-cancelled d-flex align-items-center gap-3">
        <i class="fas fa-ban fa-2x" style="color: #b91c1c;"></i>
        <div>
            <strong class="d-block mb-1">❌ ANNULÉE</strong>
            @if($transaction->cancelled_at)
            <p class="mb-0 small">Annulée le <strong>{{ \Carbon\Carbon::parse($transaction->cancelled_at)->format('d/m/Y à H:i') }}</strong>
                @if($transaction->cancel_reason)
                <br>Raison : {{ $transaction->cancel_reason }}
                @endif
            </p>
            @endif
        </div>
    </div>
    @elseif($transaction->status == 'no_show')
    <div class="alert-status alert-status-no_show d-flex align-items-center gap-3">
        <i class="fas fa-user-slash fa-2x" style="color: #64748b;"></i>
        <div>
            <strong class="d-block mb-1">👤 NO SHOW</strong>
            <p class="mb-0 small">Client ne s'est pas présenté</p>
        </div>
    </div>
    @endif

    <!-- Actions rapides (uniquement pour admin) -->
    @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']))
    <div class="quick-actions mb-4">
        @if($transaction->status == 'reservation')
        <form action="{{ route('transaction.mark-arrived', $transaction) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn-modern btn-success-modern">
                <i class="fas fa-sign-in-alt me-1"></i>Arrivée
            </button>
        </form>
        @endif
        
        @if($transaction->status == 'active')
        <form action="{{ route('transaction.mark-departed', $transaction) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn-modern btn-info-modern">
                <i class="fas fa-sign-out-alt me-1"></i>Départ
            </button>
        </form>
        @endif
        
        @if(in_array($transaction->status, ['reservation', 'active']))
        <a href="{{ route('transaction.extend', $transaction) }}" class="btn-modern btn-warning-modern">
            <i class="fas fa-calendar-plus me-1"></i>Prolonger
        </a>
        @endif
        
        @if(!in_array($transaction->status, ['cancelled', 'no_show', 'completed']))
        <a href="{{ route('transaction.edit', $transaction) }}" class="btn-modern btn-outline-modern">
            <i class="fas fa-edit me-1"></i>Modifier
        </a>
        @endif
        
        @if($remaining > 0 && !in_array($transaction->status, ['cancelled', 'no_show']))
        <a href="{{ route('transaction.payment.create', $transaction) }}" class="btn-modern btn-success-modern">
            <i class="fas fa-money-bill-wave me-1"></i>Paiement
        </a>
        @endif
        
        @if(!in_array($transaction->status, ['cancelled', 'no_show', 'completed']))
        <button type="button" class="btn-modern btn-outline-danger-modern" 
                data-bs-toggle="modal" data-bs-target="#cancelModal">
            <i class="fas fa-ban me-1"></i>Annuler
        </button>
        @endif
    </div>
    @endif

    <div class="row">
        <!-- Colonne de gauche (8 colonnes) -->
        <div class="col-lg-8">
            <!-- Client -->
            <div class="detail-card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-user"></i>Informations Client</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="client-avatar">
                            @if($transaction->customer->user && $transaction->customer->user->getAvatar())
                                <img src="{{ $transaction->customer->user->getAvatar() }}" alt="{{ $transaction->customer->name }}">
                            @else
                                {{ strtoupper(substr($transaction->customer->name, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <h4 class="mb-1" style="color: var(--gray-800); font-weight: 600;">{{ $transaction->customer->name }}</h4>
                            <p class="text-muted small mb-0">{{ $transaction->customer->email ?? 'Email non renseigné' }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p class="detail-label">Téléphone</p>
                            <p class="detail-value">{{ $transaction->customer->phone ?? 'Non renseigné' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="detail-label">NIC/ID</p>
                            <p class="detail-value">{{ $transaction->customer->nik ?? 'Non renseigné' }}</p>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-2">
                        <a href="{{ route('customer.show', $transaction->customer) }}" class="btn-modern btn-outline-modern btn-sm">
                            <i class="fas fa-eye me-1"></i>Voir profil
                        </a>
                        <a href="{{ route('transaction.reservation.customerReservations', $transaction->customer) }}" class="btn-modern btn-outline-modern btn-sm">
                            <i class="fas fa-history me-1"></i>Historique
                        </a>
                    </div>
                </div>
            </div>

            <!-- Chambre et dates -->
            <div class="detail-card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-bed"></i>Informations Séjour</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 text-center mb-3 mb-md-0">
                            <p class="detail-label">Chambre</p>
                            <span class="room-badge-large">{{ $transaction->room->number }}</span>
                            <p class="text-muted small mt-2">{{ $transaction->room->type->name ?? 'Type non spécifié' }}</p>
                        </div>
                        <div class="col-md-6 text-center">
                            <p class="detail-label">Durée du séjour</p>
                            <span class="room-badge-large" style="background: var(--gray-100); color: var(--gray-700);">
                                {{ $nights }} nuit{{ $nights > 1 ? 's' : '' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p class="detail-label">Arrivée</p>
                            <p class="detail-value">
                                <i class="fas fa-calendar-check me-1" style="color: var(--success);"></i>
                                {{ \Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y') }}
                                <span class="text-muted ms-2">{{ \Carbon\Carbon::parse($transaction->check_in)->format('H:i') }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="detail-label">Départ</p>
                            <p class="detail-value">
                                <i class="fas fa-calendar-times me-1" style="color: var(--danger);"></i>
                                {{ \Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y') }}
                                <span class="text-muted ms-2">{{ \Carbon\Carbon::parse($transaction->check_out)->format('H:i') }}</span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="divider"></div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p class="detail-label">Statut chambre</p>
                            <p class="detail-value">
                                @if($transaction->room->roomStatus)
                                <span class="badge-statut badge-{{ $transaction->room->roomStatus->name == 'Occupée' ? 'active' : ($transaction->room->roomStatus->name == 'Disponible' ? 'completed' : 'reservation') }}">
                                    {{ $transaction->room->roomStatus->name }}
                                </span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="detail-label">Statut réservation</p>
                            <p class="detail-value">
                                <span class="status-badge status-{{ $transaction->status }}">
                                    @if($transaction->status == 'reservation') 📅
                                    @elseif($transaction->status == 'active') 🏨
                                    @elseif($transaction->status == 'completed') ✅
                                    @elseif($transaction->status == 'cancelled') ❌
                                    @else 👤
                                    @endif
                                    {{ $transaction->status_label }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paiements -->
            <div class="detail-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-money-bill-wave"></i>Paiements</h5>
                    <span class="status-badge {{ $isFullyPaid ? 'badge-active' : ($remaining > 0 ? 'badge-reservation' : 'badge-no_show') }}">
                        {{ $isFullyPaid ? 'Soldé' : ($remaining > 0 ? 'En attente' : 'Aucune dette') }}
                    </span>
                </div>
                <div class="card-body">
                    <!-- Résumé financier -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="stat-box">
                                <p class="stat-label">Total</p>
                                <p class="stat-value stat-value-primary">{{ number_format($totalPrice, 0, ',', ' ') }} CFA</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <p class="stat-label">Payé</p>
                                <p class="stat-value stat-value-success">{{ number_format($totalPayment, 0, ',', ' ') }} CFA</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <p class="stat-label">Reste</p>
                                <p class="stat-value {{ $remaining > 0 ? 'stat-value-danger' : 'stat-value-success' }}">
                                    @if($remaining > 0)
                                        {{ number_format($remaining, 0, ',', ' ') }} CFA
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <p class="stat-label">Taux</p>
                                @php
                                    $paymentRate = $totalPrice > 0 ? ($totalPayment / $totalPrice * 100) : 0;
                                @endphp
                                <p class="stat-value {{ $paymentRate >= 100 ? 'stat-value-success' : ($paymentRate >= 50 ? 'stat-value-primary' : 'stat-value-danger') }}">
                                    {{ number_format($paymentRate, 1) }}%
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des paiements -->
                    @if($payments && $payments->count() > 0)
                        <p class="detail-label mb-3">Historique des paiements</p>
                        <div class="timeline">
                            @foreach($payments as $payment)
                                <div class="timeline-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1" style="font-weight: 600;">
                                                Paiement #{{ $payment->id }}
                                                <span class="badge-statut {{ $payment->status === 'completed' ? 'badge-active' : ($payment->status === 'pending' ? 'badge-reservation' : 'badge-cancelled') }}" style="margin-left: 8px;">
                                                    {{ $payment->status === 'completed' ? 'Complet' : ($payment->status === 'pending' ? 'En attente' : 'Annulé') }}
                                                </span>
                                            </h6>
                                            <p class="text-muted small mb-1">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y à H:i') }}
                                            </p>
                                            @if($payment->payment_method)
                                                <p class="text-muted small mb-1">
                                                    <i class="fas fa-credit-card me-1"></i>
                                                    {{ ucfirst($payment->payment_method) }}
                                                </p>
                                            @endif
                                            @if($payment->notes)
                                                <p class="text-muted small mb-0">Note: {{ $payment->notes }}</p>
                                            @endif
                                        </div>
                                        <div class="text-end">
                                            <p class="price-amount price-success mb-1">
                                                {{ number_format($payment->amount, 0, ',', ' ') }} CFA
                                            </p>
                                            <a href="{{ route('payment.invoice', $payment) }}" class="btn-modern btn-outline-modern btn-sm" target="_blank">
                                                <i class="fas fa-receipt"></i> Reçu
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-money-bill-wave fa-3x mb-3" style="color: var(--gray-300);"></i>
                            <h5 style="color: var(--gray-600);">Aucun paiement</h5>
                            <p class="text-muted small">Aucun paiement n'a été effectué pour cette réservation.</p>
                            @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']) && $remaining > 0 && !in_array($transaction->status, ['cancelled', 'no_show']))
                                <a href="{{ route('transaction.payment.create', $transaction) }}" class="btn-modern btn-primary-modern mt-2">
                                    <i class="fas fa-plus me-1"></i>Ajouter un paiement
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne de droite (4 colonnes) -->
        <div class="col-lg-4">
            <!-- Actions rapides (version compacte) -->
            <div class="detail-card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-bolt"></i>Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('transaction.history', $transaction) }}" class="btn-modern btn-outline-modern w-100">
                            <i class="fas fa-history me-1"></i>Historique
                        </a>
                        
                        @if($payments && $payments->count() > 0)
                        <a href="{{ route('transaction.invoice', $transaction) }}" class="btn-modern btn-outline-modern w-100" target="_blank">
                            <i class="fas fa-file-invoice me-1"></i>Facture
                        </a>
                        @endif
                        
                        @if($transaction->status == 'cancelled' && in_array(auth()->user()->role, ['Super', 'Admin']))
                        <form action="{{ route('transaction.restore', $transaction) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-modern btn-warning-modern w-100" onclick="return confirm('Restaurer cette réservation ?')">
                                <i class="fas fa-undo me-1"></i>Restaurer
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informations supplémentaires -->
            <div class="detail-card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i>Détails</h5>
                </div>
                <div class="card-body">
                    <p class="detail-label">Nombre de personnes</p>
                    <p class="detail-value">{{ $transaction->person_count ?? 1 }} personne{{ ($transaction->person_count ?? 1) > 1 ? 's' : '' }}</p>
                    
                    <p class="detail-label">Prix par nuit</p>
                    <p class="detail-value">{{ number_format($transaction->room->price, 0, ',', ' ') }} CFA</p>
                    
                    <p class="detail-label">Créée le</p>
                    <p class="detail-value">{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y à H:i') }}</p>
                    
                    @if($transaction->user)
                    <p class="detail-label">Créée par</p>
                    <p class="detail-value">{{ $transaction->user->name }}</p>
                    @endif
                    
                    @if($transaction->updated_at != $transaction->created_at)
                    <p class="detail-label">Dernière modification</p>
                    <p class="detail-value">{{ \Carbon\Carbon::parse($transaction->updated_at)->format('d/m/Y à H:i') }}</p>
                    @endif
                    
                    @if($transaction->notes)
                    <div class="divider"></div>
                    <p class="detail-label">Notes</p>
                    <p class="detail-value" style="white-space: pre-line;">{{ $transaction->notes }}</p>
                    @endif
                </div>
            </div>

            <!-- Statistiques -->
            <div class="detail-card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-bar"></i>Statistiques</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="stat-box p-3">
                                <p class="stat-label">Nuits</p>
                                <p class="stat-value">{{ $nights }}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box p-3">
                                <p class="stat-label">Paiements</p>
                                <p class="stat-value">{{ $payments ? $payments->count() : 0 }}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box p-3">
                                <p class="stat-label">Total</p>
                                <p class="stat-value stat-value-primary">{{ number_format($totalPrice, 0, ',', ' ') }} CFA</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box p-3">
                                <p class="stat-label">Payé</p>
                                <p class="stat-value stat-value-success">{{ number_format($totalPayment, 0, ',', ' ') }} CFA</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($remaining > 0)
                    <div class="divider"></div>
                    <div class="text-center">
                        <p class="detail-label mb-1">Reste à payer</p>
                        <p class="stat-value stat-value-danger h4">{{ number_format($remaining, 0, ',', ' ') }} CFA</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'annulation -->
@if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']) && !in_array($transaction->status, ['cancelled', 'no_show', 'completed']))
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none;">
            <div class="modal-header" style="background: var(--gray-50); border-bottom: 1px solid var(--gray-200);">
                <h5 class="modal-title">
                    <i class="fas fa-ban text-danger me-2"></i>
                    Annuler la réservation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('transaction.cancel', $transaction) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p class="mb-3">Êtes-vous sûr de vouloir annuler cette réservation ?</p>
                    <div class="mb-3">
                        <label class="form-label">Raison (optionnelle)</label>
                        <textarea name="cancel_reason" class="form-control" rows="3" placeholder="Pourquoi annuler ?"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0" style="background: var(--gray-50);">
                    <button type="button" class="btn-modern btn-outline-modern" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn-modern btn-outline-danger-modern">
                        <i class="fas fa-ban me-1"></i>Confirmer l'annulation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Formulaire annulation masqué (conservé pour compatibilité) -->
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
    
    // Confirmation pour le changement de statut
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function(e) {
            const newStatus = this.value;
            const oldStatus = this.options[this.selectedIndex].dataset.oldStatus || this.value;
            
            const statusLabels = {
                'reservation': '📅 Réservation',
                'active': '🏨 Dans l\'hôtel',
                'completed': '✅ Terminé',
                'cancelled': '❌ Annulée',
                'no_show': '👤 No Show'
            };
            
            const oldLabel = statusLabels[oldStatus] || oldStatus;
            const newLabel = statusLabels[newStatus] || newStatus;
            
            // Confirmation pour cancelled
            if (newStatus === 'cancelled') {
                if (!confirm(`⚠️ Êtes-vous sûr de vouloir annuler cette réservation ?\n\n${oldLabel} → ${newLabel}`)) {
                    this.value = oldStatus;
                    return false;
                }
            }
            
            // Confirmation pour no_show
            if (newStatus === 'no_show') {
                if (!confirm(`⚠️ Marquer comme "No Show" ?\n\n${oldLabel} → ${newLabel}`)) {
                    this.value = oldStatus;
                    return false;
                }
            }
        });
    });
});
</script>
@endsection