@extends('template.master')
@section('title', 'Détails de la Réservation #' . $transaction->id)
@section('content')

<style>
    .detail-card {
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    
    .detail-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .detail-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
    }
    
    .detail-value {
        color: #212529;
        font-size: 1rem;
    }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .status-reservation {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .status-active {
        background-color: #d1e7dd;
        color: #0f5132;
    }
    
    .status-completed {
        background-color: #cfe2ff;
        color: #084298;
    }
    
    .status-cancelled {
        background-color: #e9ecef;
        color: #495057;
    }
    
    .status-no_show {
        background-color: #6c757d;
        color: #ffffff;
    }
    
    .price-amount {
        font-weight: 700;
        font-size: 1.1rem;
    }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -23px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #6c757d;
        border: 2px solid white;
    }
    
    .payment-status-paid {
        color: #198754;
        font-weight: 600;
    }
    
    .payment-status-pending {
        color: #fd7e14;
        font-weight: 600;
    }
    
    .payment-status-cancelled {
        color: #dc3545;
        font-weight: 600;
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .btn-outline-cancel {
        color: #dc3545;
        border-color: #dc3545;
    }
    
    .btn-outline-cancel:hover {
        background-color: #dc3545;
        color: white;
    }
    
    .status-select-form {
        max-width: 180px;
        display: inline-block;
    }
    
    .status-select {
        font-size: 0.85rem;
        padding: 4px 8px;
    }
    
    .alert-status {
        border-left: 4px solid;
        padding-left: 15px;
    }
    
    .alert-status-reservation { border-left-color: #ffc107; }
    .alert-status-active { border-left-color: #198754; }
    .alert-status-completed { border-left-color: #0dcaf0; }
    .alert-status-cancelled { border-left-color: #dc3545; }
    .alert-status-no_show { border-left-color: #6c757d; }
    
    .btn-arrived {
        background-color: #28a745;
        color: white;
        border: none;
    }
    
    .btn-departed {
        background-color: #17a2b8;
        color: white;
        border: none;
    }
</style>

<div class="container-fluid">
    <!-- En-tête avec bouton retour -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('transaction.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour aux réservations
                    </a>
                </div>
                <div class="text-center">
                    <h4 class="mb-0">Détails de la Réservation</h4>
                    <small class="text-muted">ID: #{{ $transaction->id }}</small>
                </div>
                <div class="action-buttons">
                    <!-- Gestion du statut (pour admin) -->
                    @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Reception']))
                        <!-- COMBO BOX DE STATUT -->
                        <form action="{{ route('transaction.updateStatus', $transaction) }}" method="POST" class="status-select-form">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-control form-select-sm status-select" onchange="this.form.submit()">
                                <option value="reservation" {{ $transaction->status == 'reservation' ? 'selected' : '' }} 
                                        class="text-warning">📅 Réservation</option>
                                <option value="active" {{ $transaction->status == 'active' ? 'selected' : '' }}
                                        class="text-success">🏨 Dans l'hôtel</option>
                                <option value="completed" {{ $transaction->status == 'completed' ? 'selected' : '' }}
                                        class="text-info">✅ Terminé</option>
                                <option value="cancelled" {{ $transaction->status == 'cancelled' ? 'selected' : '' }}
                                        class="text-danger">❌ Annulée</option>
                                <option value="no_show" {{ $transaction->status == 'no_show' ? 'selected' : '' }}
                                        class="text-secondary">👤 No Show</option>
                            </select>
                        </form>
                    @endif
                    
                    <!-- Actions rapides -->
                    @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Reception']))
                        @if($transaction->status == 'reservation')
                            <form action="{{ route('transaction.mark-arrived', $transaction) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-arrived btn-sm">
                                    <i class="fas fa-sign-in-alt me-1"></i>Arrivée
                                </button>
                            </form>
                        @endif
                        
                        @if($transaction->status == 'active')
                            <form action="{{ route('transaction.mark-departed', $transaction) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-departed btn-sm">
                                    <i class="fas fa-sign-out-alt me-1"></i>Départ
                                </button>
                            </form>
                        @endif
                        
                        @if($transaction->status !== 'cancelled' && !$isExpired)
                            <a href="{{ route('transaction.edit', $transaction) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit me-1"></i>Modifier
                            </a>
                        @endif
                        
                        @if($remaining > 0 && !in_array($transaction->status, ['cancelled', 'no_show']))
                            <a href="{{ route('transaction.payment.create', $transaction) }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-money-bill-wave me-1"></i>Paiement
                            </a>
                        @endif
                    @endif
                    
                    <a href="{{ route('transaction.invoice', $transaction) }}" class="btn btn-outline-info btn-sm" target="_blank">
                        <i class="fas fa-file-invoice me-1"></i>Facture
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages de session -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error') || session('failed'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') ?? session('failed') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Avertissement selon le statut -->
    @if($transaction->status == 'reservation')
        <div class="alert alert-warning alert-status alert-status-reservation mb-4">
            <i class="fas fa-calendar-check me-2"></i>
            <strong>📅 RÉSERVATION</strong> - Le client n'est pas encore arrivé à l'hôtel.
            Arrivée prévue : <strong>{{ \Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y à H:i') }}</strong>
        </div>
    @elseif($transaction->status == 'active')
        <div class="alert alert-success alert-status alert-status-active mb-4">
            <i class="fas fa-bed me-2"></i>
            <strong>🏨 DANS L'HÔTEL</strong> - Le client est actuellement en séjour.
            Départ prévu : <strong>{{ \Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y à H:i') }}</strong>
        </div>
    @elseif($transaction->status == 'completed')
        <div class="alert alert-info alert-status alert-status-completed mb-4">
            <i class="fas fa-check-circle me-2"></i>
            <strong>✅ SÉJOUR TERMINÉ</strong> - Le client est parti, le séjour est terminé.
        </div>
    @elseif($transaction->status == 'cancelled')
        <div class="alert alert-danger alert-status alert-status-cancelled mb-4">
            <i class="fas fa-ban me-2"></i>
            <strong>❌ ANNULÉE</strong> - Cette réservation a été annulée.
            @if($transaction->cancelled_at)
                <br>Annulée le : <strong>{{ \Carbon\Carbon::parse($transaction->cancelled_at)->format('d/m/Y à H:i') }}</strong>
                @if($transaction->cancel_reason)
                    <br>Raison : <strong>{{ $transaction->cancel_reason }}</strong>
                @endif
            @endif
        </div>
    @elseif($transaction->status == 'no_show')
        <div class="alert alert-secondary alert-status alert-status-no_show mb-4">
            <i class="fas fa-user-slash me-2"></i>
            <strong>👤 NO SHOW</strong> - Le client ne s'est pas présenté.
        </div>
    @endif

    <div class="row">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <div class="row">
                <!-- Client -->
                <div class="col-md-6 mb-4">
                    <div class="card detail-card h-100">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informations Client</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ $transaction->customer->user->getAvatar() }}" 
                                     class="rounded-circle me-3" width="60" height="60">
                                <div>
                                    <h5 class="mb-1">{{ $transaction->customer->name }}</h5>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-envelope me-1"></i>{{ $transaction->customer->email }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <p class="detail-label">Téléphone</p>
                                    <p class="detail-value">
                                        <i class="fas fa-phone me-1"></i>
                                        {{ $transaction->customer->phone ?? 'Non renseigné' }}
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="detail-label">NIC/ID</p>
                                    <p class="detail-value">
                                        {{ $transaction->customer->nik ?? 'Non renseigné' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <a href="{{ route('customer.show', $transaction->customer) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Voir profil client
                                </a>
                                <a href="{{ route('transaction.reservation.customerReservations', $transaction->customer) }}" 
                                   class="btn btn-sm btn-outline-info ms-2">
                                    <i class="fas fa-history me-1"></i>Voir ses réservations
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chambre et Dates -->
                <div class="col-md-6 mb-4">
                    <div class="card detail-card h-100">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-bed me-2"></i>Informations Séjour</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <p class="detail-label">Chambre</p>
                                    <p class="detail-value">
                                        <span class="badge bg-primary fs-6">
                                            {{ $transaction->room->number }}
                                        </span>
                                    </p>
                                    <small class="text-muted">
                                        {{ $transaction->room->type->name ?? 'Type non spécifié' }}
                                    </small>
                                </div>
                                <div class="col-6">
                                    <p class="detail-label">Nuits</p>
                                    <p class="detail-value">
                                        <span class="badge bg-secondary fs-6">
                                            {{ $nights }} nuit{{ $nights > 1 ? 's' : '' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <p class="detail-label">Arrivée</p>
                                    <p class="detail-value">
                                        <i class="fas fa-calendar-check me-1 text-success"></i>
                                        {{ \Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y') }}
                                    </p>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($transaction->check_in)->format('H:i') }}
                                    </small>
                                </div>
                                <div class="col-6">
                                    <p class="detail-label">Départ</p>
                                    <p class="detail-value">
                                        <i class="fas fa-calendar-times me-1 text-danger"></i>
                                        {{ \Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y') }}
                                    </p>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($transaction->check_out)->format('H:i') }}
                                    </small>
                                </div>
                            </div>
                            
                            <div class="mt-3 pt-3 border-top">
                                <p class="detail-label">Statut de la chambre</p>
                                <p class="detail-value">
                                    @if($transaction->room->roomStatus)
                                        <span class="badge bg-{{ $transaction->room->roomStatus->name == 'Occupied' ? 'danger' : ($transaction->room->roomStatus->name == 'Available' ? 'success' : 'warning') }}">
                                            {{ $transaction->room->roomStatus->name }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Non défini</span>
                                    @endif
                                </p>
                                
                                <p class="detail-label mb-1">Statut de la réservation</p>
                                <span class="status-badge status-{{ $transaction->status }}">
                                    {{ $transaction->status_label }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paiements -->
                <div class="col-12 mb-4">
                    <div class="card detail-card">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Paiements</h5>
                            <span class="badge bg-{{ $isFullyPaid ? 'success' : ($remaining > 0 ? 'warning' : 'secondary') }}">
                                {{ $isFullyPaid ? 'Soldé' : ($remaining > 0 ? 'En attente' : 'Aucune dette') }}
                            </span>
                        </div>
                        <div class="card-body">
                            <!-- Résumé financier -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <p class="detail-label mb-1">Total à payer</p>
                                        <p class="price-amount text-primary mb-0">
                                            {{ number_format($totalPrice, 0, ',', ' ') }} CFA
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <p class="detail-label mb-1">Déjà payé</p>
                                        <p class="price-amount text-success mb-0">
                                            {{ number_format($totalPayment, 0, ',', ' ') }} CFA
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <p class="detail-label mb-1">Reste à payer</p>
                                        <p class="price-amount text-{{ $remaining > 0 ? 'danger' : 'success' }} mb-0">
                                            @if($remaining > 0)
                                                {{ number_format($remaining, 0, ',', ' ') }} CFA
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <p class="detail-label mb-1">Taux de paiement</p>
                                        <p class="price-amount mb-0">
                                            @php
                                                $paymentRate = $totalPrice > 0 ? ($totalPayment / $totalPrice * 100) : 0;
                                            @endphp
                                            <span class="text-{{ $paymentRate >= 100 ? 'success' : ($paymentRate >= 50 ? 'warning' : 'danger') }}">
                                                {{ number_format($paymentRate, 1) }}%
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Liste des paiements -->
                            @if($payments && $payments->count() > 0)
                                <div class="timeline">
                                    @foreach($payments as $payment)
                                        <div class="timeline-item">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="mb-1">
                                                        Paiement #{{ $payment->id }}
                                                        <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                                            {{ $payment->status === 'completed' ? 'Complet' : ($payment->status === 'pending' ? 'En attente' : 'Annulé') }}
                                                        </span>
                                                    </h6>
                                                    <p class="text-muted mb-1">
                                                        <small>
                                                            <i class="fas fa-calendar me-1"></i>
                                                            {{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y à H:i') }}
                                                        </small>
                                                    </p>
                                                    @if($payment->payment_method)
                                                        <p class="mb-1">
                                                            <small>
                                                                <i class="fas fa-credit-card me-1"></i>
                                                                {{ ucfirst($payment->payment_method) }}
                                                            </small>
                                                        </p>
                                                    @endif
                                                    @if($payment->notes)
                                                        <p class="mb-0">
                                                            <small><strong>Note:</strong> {{ $payment->notes }}</small>
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="text-end">
                                                    <p class="price-amount text-success mb-1">
                                                        {{ number_format($payment->amount, 0, ',', ' ') }} CFA
                                                    </p>
                                                    <a href="{{ route('payment.invoice', $payment) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                        <i class="fas fa-receipt"></i> Reçu
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                    <h5>Aucun paiement enregistré</h5>
                                    <p class="text-muted">Aucun paiement n'a été effectué pour cette réservation.</p>
                                    @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Reception']) && $remaining > 0 && !in_array($transaction->status, ['cancelled', 'no_show']))
                                        <a href="{{ route('transaction.payment.create', $transaction) }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>Ajouter un paiement
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations supplémentaires et actions -->
        <div class="col-lg-4">
            <!-- Actions rapides selon le statut -->
            <div class="card detail-card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Actions Rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <!-- Actions selon le statut -->
                        @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Reception']))
                            @if($transaction->status == 'reservation')
                                <form action="{{ route('transaction.mark-arrived', $transaction) }}" method="POST" class="d-grid">
                                    @csrf
                                    <button type="submit" class="btn btn-arrived mb-2">
                                        <i class="fas fa-sign-in-alt me-2"></i>Marquer comme arrivé
                                    </button>
                                </form>
                            @endif

                            @if($transaction->status == 'active')
                                <form action="{{ route('transaction.mark-departed', $transaction) }}" method="POST" class="d-grid">
                                    @csrf
                                    <button type="submit" class="btn btn-departed mb-2">
                                        <i class="fas fa-sign-out-alt me-2"></i>Marquer comme parti
                                    </button>
                                </form>
                            @endif

                            @if($remaining > 0 && !in_array($transaction->status, ['cancelled', 'no_show']))
                                <a href="{{ route('transaction.payment.create', $transaction) }}" 
                                   class="btn btn-success mb-2">
                                    <i class="fas fa-credit-card me-2"></i>Ajouter un Paiement
                                </a>
                            @endif
                            
                            @if(!in_array($transaction->status, ['cancelled', 'no_show', 'completed']))
                                <a href="{{ route('transaction.edit', $transaction) }}" 
                                   class="btn btn-primary mb-2">
                                    <i class="fas fa-edit me-2"></i>Modifier la réservation
                                </a>
                            @endif
                            
                            @if($transaction->status == 'cancelled' && in_array(auth()->user()->role, ['Super', 'Admin']))
                                <form action="{{ route('transaction.restore', $transaction) }}" method="POST" class="d-grid">
                                    @csrf
                                    <button type="submit" class="btn btn-warning mb-2" 
                                            onclick="return confirm('Restaurer cette réservation ?')">
                                        <i class="fas fa-undo me-2"></i>Restaurer la réservation
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('transaction.history', $transaction) }}" class="btn btn-outline-secondary mb-2">
                                <i class="fas fa-history me-2"></i>Historique des modifications
                            </a>
                            
                            @if($payments && $payments->count() > 0)
                                <a href="{{ route('transaction.invoice', $transaction) }}" class="btn btn-outline-info mb-2" target="_blank">
                                    <i class="fas fa-file-invoice me-2"></i>Générer la facture
                                </a>
                            @endif
                            
                            <!-- Annulation (si pas déjà annulé/no show/completé) -->
                            @if(!in_array($transaction->status, ['cancelled', 'no_show', 'completed']) && in_array(auth()->user()->role, ['Super', 'Admin']))
                                <button type="button" class="btn btn-outline-danger cancel-btn" 
                                        data-transaction-id="{{ $transaction->id }}"
                                        data-transaction-number="#{{ $transaction->id }}"
                                        data-customer-name="{{ $transaction->customer->name }}">
                                    <i class="fas fa-ban me-2"></i>Annuler la réservation
                                </button>
                            @endif
                        @endif
                        
                        @if(auth()->user()->role === 'Customer')
                            @if($remaining > 0 && !in_array($transaction->status, ['cancelled', 'no_show']))
                                <a href="{{ route('transaction.payment.create', $transaction) }}" class="btn btn-success">
                                    <i class="fas fa-money-bill-wave me-2"></i>Effectuer un paiement
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informations supplémentaires -->
            <div class="card detail-card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations Supplémentaires</h5>
                </div>
                <div class="card-body">
                    <p class="detail-label">Nombre de personnes</p>
                    <p class="detail-value">
                        <i class="fas fa-users me-1"></i>
                        {{ $transaction->person_count ?? 1 }} personne{{ ($transaction->person_count ?? 1) > 1 ? 's' : '' }}
                    </p>
                    
                    <p class="detail-label">Prix par nuit</p>
                    <p class="detail-value">
                        <i class="fas fa-money-bill me-1"></i>
                        {{ number_format($transaction->room->price, 0, ',', ' ') }} CFA
                    </p>
                    
                    <p class="detail-label">Créée le</p>
                    <p class="detail-value">
                        <i class="fas fa-calendar-plus me-1"></i>
                        {{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y à H:i') }}
                    </p>
                    
                    @if($transaction->user)
                        <p class="detail-label">Créée par</p>
                        <p class="detail-value">
                            <i class="fas fa-user-check me-1"></i>
                            {{ $transaction->user->name }}
                        </p>
                    @endif
                    
                    @if($transaction->cancelled_by && $transaction->cancelledBy)
                        <p class="detail-label">Annulée par</p>
                        <p class="detail-value">
                            <i class="fas fa-user-times me-1"></i>
                            {{ $transaction->cancelledBy->name }}
                        </p>
                    @endif
                    
                    @if($transaction->updated_at != $transaction->created_at)
                        <p class="detail-label">Dernière modification</p>
                        <p class="detail-value">
                            <i class="fas fa-edit me-1"></i>
                            {{ \Carbon\Carbon::parse($transaction->updated_at)->format('d/m/Y à H:i') }}
                        </p>
                    @endif
                    
                    @if($transaction->notes)
                        <div class="mt-3 pt-3 border-top">
                            <p class="detail-label">Notes</p>
                            <p class="detail-value">{{ $transaction->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistiques -->
            <div class="card detail-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistiques</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <p class="detail-label mb-1">Nuits</p>
                                <p class="detail-value h4">{{ $nights }}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <p class="detail-label mb-1">Paiements</p>
                                <p class="detail-value h4">{{ $payments ? $payments->count() : 0 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <div class="text-center">
                                <p class="detail-label mb-1">Total</p>
                                <p class="detail-value text-primary">{{ number_format($totalPrice, 0, ',', ' ') }} CFA</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <p class="detail-label mb-1">Payé</p>
                                <p class="detail-value text-success">{{ number_format($totalPayment, 0, ',', ' ') }} CFA</p>
                            </div>
                        </div>
                    </div>
                    @if($remaining > 0)
                    <div class="mt-3 pt-3 border-top">
                        <div class="text-center">
                            <p class="detail-label mb-1">Reste à payer</p>
                            <p class="detail-value text-danger h5">{{ number_format($remaining, 0, ',', ' ') }} CFA</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Formulaire d'annulation masqué -->
<form id="cancel-form" method="POST" action="{{ route('transaction.cancel', 0) }}" class="d-none">
    @csrf
    @method('DELETE')
    <input type="hidden" name="transaction_id" id="cancel-transaction-id-input">
    <input type="hidden" name="cancel_reason" id="cancel-reason-input">
</form>

@endsection

@section('footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de l'annulation
    document.querySelectorAll('.cancel-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const transactionId = this.getAttribute('data-transaction-id');
            const transactionNumber = this.getAttribute('data-transaction-number');
            const customerName = this.getAttribute('data-customer-name');
            
            Swal.fire({
                title: 'Annuler la réservation ?',
                html: `
                    <div style="text-align: left;">
                        <p>Confirmez l'annulation de :</p>
                        <div style="background: #fff3cd; padding: 10px; border-radius: 5px; margin: 10px 0;">
                            <strong>${transactionNumber}</strong><br>
                            <small>Client: ${customerName}</small>
                        </div>
                        <div style="margin-top: 15px;">
                            <label>Raison (optionnelle) :</label>
                            <textarea id="cancelReason" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" 
                                      rows="3" placeholder="Pourquoi annuler cette réservation ?"></textarea>
                        </div>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="fas fa-ban me-2"></i> Oui, annuler',
                cancelButtonText: '<i class="fas fa-times me-2"></i> Non, garder',
                reverseButtons: true,
                preConfirm: () => {
                    return {
                        reason: document.getElementById('cancelReason').value
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const reason = result.value.reason || '';
                    
                    // Afficher message de chargement
                    Swal.fire({
                        title: 'Traitement en cours...',
                        text: 'Annulation de la réservation',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Soumettre le formulaire
                    setTimeout(() => {
                        const form = document.getElementById('cancel-form');
                        form.action = `/transaction/${transactionId}/cancel`;
                        document.getElementById('cancel-transaction-id-input').value = transactionId;
                        document.getElementById('cancel-reason-input').value = reason;
                        form.submit();
                    }, 500);
                }
            });
        });
    });
    
    // Confirmation pour les changements de statut via combo box
    const statusSelects = document.querySelectorAll('.status-select');
    statusSelects.forEach(select => {
        select.addEventListener('change', function(e) {
            const newStatus = this.value;
            const oldStatus = this.options[this.selectedIndex].dataset.oldStatus || this.value;
            
            // Mapper les valeurs aux labels
            const statusLabels = {
                'reservation': '📅 Réservation',
                'active': '🏨 Dans l\'hôtel',
                'completed': '✅ Séjour terminé',
                'cancelled': '❌ Annulée',
                'no_show': '👤 No Show'
            };
            
            const oldLabel = statusLabels[oldStatus] || oldStatus;
            const newLabel = statusLabels[newStatus] || newStatus;
            
            // Confirmation pour certains changements
            if (newStatus === 'cancelled') {
                if (!confirm(`⚠️ Êtes-vous sûr de vouloir annuler cette réservation ?\n\nStatut: ${oldLabel} → ${newLabel}`)) {
                    this.value = oldStatus;
                    return false;
                }
            } else if (newStatus === 'no_show') {
                if (!confirm(`⚠️ Marquer comme "No Show" ?\n\nLe client ne s'est pas présenté.\nStatut: ${oldLabel} → ${newLabel}`)) {
                    this.value = oldStatus;
                    return false;
                }
            } else if (oldStatus === 'cancelled' && newStatus !== 'cancelled') {
                if (!confirm(`♻️ Restaurer cette réservation ?\n\nStatut: ${oldLabel} → ${newLabel}`)) {
                    this.value = oldStatus;
                    return false;
                }
            }
            
            // Soumettre automatiquement
            this.form.submit();
        });
    });
});
</script>
@endsection