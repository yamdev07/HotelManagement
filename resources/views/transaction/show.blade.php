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
    
    .status-active {
        background-color: #d1e7dd;
        color: #0f5132;
    }
    
    .status-expired {
        background-color: #f8d7da;
        color: #842029;
    }
    
    .status-completed {
        background-color: #cfe2ff;
        color: #084298;
    }
    
    .status-cancelled {
        background-color: #e9ecef;
        color: #495057;
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
                <div>
                    <h4 class="mb-0">Détails de la Réservation</h4>
                    <small class="text-muted">ID: #{{ $transaction->id }}</small>
                </div>
                <div class="action-buttons">
                    @if(in_array(auth()->user()->role, ['Super', 'Admin']))
                        @if($canCancel && !$isExpired && $status !== 'cancelled')
                            <button class="btn btn-outline-cancel cancel-btn" 
                                    data-transaction-id="{{ $transaction->id }}"
                                    data-transaction-number="#{{ $transaction->id }}"
                                    data-customer-name="{{ $transaction->customer->name }}">
                                <i class="fas fa-ban me-1"></i>Annuler
                            </button>
                        @endif
                        
                        @if($status !== 'cancelled' && !$isExpired)
                            <a href="{{ route('transaction.edit', $transaction) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-1"></i>Modifier
                            </a>
                        @endif
                        
                        <a href="{{ route('transaction.payment.create', $transaction) }}" class="btn btn-outline-success">
                            <i class="fas fa-money-bill-wave me-1"></i>Paiement
                        </a>
                    @endif
                    
                    <a href="{{ route('transaction.invoice', $transaction) }}" class="btn btn-outline-info" target="_blank">
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
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                                <p class="detail-label">Statut</p>
                                @php
                                    $statusClass = '';
                                    $statusText = '';
                                    
                                    if($status === 'cancelled') {
                                        $statusClass = 'status-cancelled';
                                        $statusText = 'Annulée';
                                    } elseif($status === 'paid' || $isFullyPaid) {
                                        $statusClass = 'status-completed';
                                        $statusText = 'Payée';
                                    } elseif($isExpired) {
                                        $statusClass = 'status-expired';
                                        $statusText = 'Expirée';
                                    } else {
                                        $statusClass = 'status-active';
                                        $statusText = 'Active';
                                    }
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                                
                                @if($transaction->cancelled_at && $status === 'cancelled')
                                    <p class="mt-2 mb-0">
                                        <small class="text-muted">
                                            Annulée le {{ \Carbon\Carbon::parse($transaction->cancelled_at)->format('d/m/Y à H:i') }}
                                        </small>
                                    </p>
                                    @if($transaction->cancel_reason)
                                        <p class="mt-1 mb-0">
                                            <small><strong>Raison:</strong> {{ $transaction->cancel_reason }}</small>
                                        </p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paiements -->
                <div class="col-12 mb-4">
                    <div class="card detail-card">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Paiements</h5>
                            <span class="badge bg-{{ $isFullyPaid ? 'success' : 'warning' }}">
                                {{ $isFullyPaid ? 'Soldé' : 'En attente' }}
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
                                    @if(in_array(auth()->user()->role, ['Super', 'Admin']) && !$isExpired && $status !== 'cancelled')
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
            <!-- Informations supplémentaires -->
            <div class="card detail-card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations Supplémentaires</h5>
                </div>
                <div class="card-body">
                    <p class="detail-label">Nombre de personnes</p>
                    <p class="detail-value">
                        <i class="fas fa-users me-1"></i>
                        {{ $transaction->person_count }} personne{{ $transaction->person_count > 1 ? 's' : '' }}
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
                    
                    @if($transaction->createdBy)
                        <p class="detail-label">Créée par</p>
                        <p class="detail-value">
                            <i class="fas fa-user-check me-1"></i>
                            {{ $transaction->createdBy->name }}
                        </p>
                    @endif
                    
                    @if($transaction->updated_at != $transaction->created_at)
                        <p class="detail-label">Dernière modification</p>
                        <p class="detail-value">
                            <i class="fas fa-edit me-1"></i>
                            {{ \Carbon\Carbon::parse($transaction->updated_at)->format('d/m/Y à H:i') }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card detail-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Actions Rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(in_array(auth()->user()->role, ['Super', 'Admin']))
                            @if($status !== 'cancelled' && !$isExpired && !$isFullyPaid)
                                <a href="{{ route('transaction.payment.create', $transaction) }}" class="btn btn-success">
                                    <i class="fas fa-money-bill-wave me-2"></i>Enregistrer un paiement
                                </a>
                            @endif
                            
                            @if($status !== 'cancelled' && !$isExpired)
                                <a href="{{ route('transaction.edit', $transaction) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-2"></i>Modifier la réservation
                                </a>
                            @endif
                            
                            <a href="{{ route('transaction.history', $transaction) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-history me-2"></i>Historique des modifications
                            </a>
                            
                            <a href="{{ route('transaction.invoice', $transaction) }}" class="btn btn-outline-info" target="_blank">
                                <i class="fas fa-file-invoice me-2"></i>Générer la facture
                            </a>
                        @endif
                        
                        @if(auth()->user()->role === 'Customer')
                            @if($status !== 'cancelled' && !$isExpired && !$isFullyPaid)
                                <a href="{{ route('transaction.payment.create', $transaction) }}" class="btn btn-success">
                                    <i class="fas fa-money-bill-wave me-2"></i>Effectuer un paiement
                                </a>
                            @endif
                        @endif
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Statistiques</h6>
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center">
                                    <p class="detail-label mb-1">Nuits</p>
                                    <p class="detail-value">{{ $nights }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <p class="detail-label mb-1">Paiements</p>
                                    <p class="detail-value">{{ $payments ? $payments->count() : 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
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
});
</script>
@endsection