@extends('template.master')
@section('title', 'Gestion des Réservations')
@section('content')
    <style>
        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
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
        
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: nowrap;
        }
        
        .btn-action {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease;
            text-decoration: none;
            cursor: pointer;
        }
        
        .btn-action:hover:not(.disabled) {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .btn-pay { background-color: #d1e7dd; color: #0f5132; }
        .btn-pay:hover:not(.disabled) { background-color: #c1dfd1; }
        
        .btn-edit { background-color: #cff4fc; color: #055160; }
        .btn-edit:hover:not(.disabled) { background-color: #bee4ec; }
        
        .btn-cancel { background-color: #fff3cd; color: #856404; }
        .btn-cancel:hover:not(.disabled) { background-color: #ffeaa7; }
        
        .table-responsive {
            max-height: 600px;
            overflow-y: auto;
        }
        
        .price-cfa {
            font-weight: 600;
            color: #2d3748;
        }
        
        .disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }
        
        .btn-action.disabled {
            background-color: #e9ecef;
            color: #6c757d;
            border-color: #dee2e6;
        }
        
        .cancelled-row {
            opacity: 0.7;
            background-color: #f8f9fa;
        }
        
        .cancelled-row:hover {
            background-color: #f1f3f4;
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
        <!-- En-tête avec boutons -->
        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="d-flex gap-2">
                    <!-- Bouton Nouvelle Réservation -->
                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Nouvelle Réservation">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#staticBackdrop">
                            <i class="fas fa-plus me-2"></i>Nouvelle Réservation
                        </button>
                    </span>
                    
                    <!-- Historique des Paiements -->
                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Historique des Paiements">
                        <a href="{{ route('payment.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-history me-2"></i>Historique
                        </a>
                    </span>
                    
                    <!-- Mes Réservations (pour les clients) -->
                    @if(auth()->user()->role === 'Customer')
                        <span data-bs-toggle="tooltip" data-bs-placement="top" title="Mes Réservations">
                            <a href="{{ route('transaction.myReservations') }}" class="btn btn-outline-info">
                                <i class="fas fa-bed me-2"></i>Mes Réservations
                            </a>
                        </span>
                    @endif
                </div>
            </div>
            
            <!-- Recherche -->
            <div class="col-lg-6">
                <form class="d-flex" method="GET" action="{{ route('transaction.index') }}">
                    <input class="form-control me-2" type="search" placeholder="Rechercher par ID, nom client ou chambre" 
                           aria-label="Search" id="search" name="search" value="{{ request()->input('search') }}">
                    <button class="btn btn-outline-dark" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request()->has('search'))
                        <a href="{{ route('transaction.index') }}" class="btn btn-outline-danger ms-2">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
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
        
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Réservations Actives -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>Réservations Actives
                        <span class="badge bg-primary">{{ $transactions->count() }}</span>
                    </h5>
                    <div class="d-flex gap-2">
                        <span class="status-badge status-active">Actives</span>
                        <span class="status-badge status-cancelled">Annulées</span>
                        <span class="status-badge status-expired">Expirées</span>
                        <span class="status-badge status-completed">Payées</span>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Client</th>
                                        <th>Chambre</th>
                                        <th>Arrivée</th>
                                        <th>Départ</th>
                                        <th>Nuits</th>
                                        <th>Total (CFA)</th>
                                        <th>Payé (CFA)</th>
                                        <th>Reste (CFA)</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($transactions as $transaction)
                                        @php
                                            // Calcul des montants
                                            $totalPrice = $transaction->getTotalPrice();
                                            $totalPayment = $transaction->getTotalPayment();
                                            $remaining = $totalPrice - $totalPayment;
                                            
                                            // Vérifier le statut
                                            $isCancelled = $transaction->status == 'cancelled';
                                            $isFullyPaid = $remaining <= 0;
                                            $checkOutDate = \Carbon\Carbon::parse($transaction->check_out);
                                            $isExpired = $checkOutDate->isPast();
                                            
                                            // Déterminer le statut
                                            if ($isCancelled) {
                                                $statusClass = 'status-cancelled';
                                                $statusText = 'Annulée';
                                            } elseif ($isFullyPaid) {
                                                $statusClass = 'status-completed';
                                                $statusText = 'Payé';
                                            } elseif ($isExpired) {
                                                $statusClass = 'status-expired';
                                                $statusText = 'Expiré';
                                            } else {
                                                $statusClass = 'status-active';
                                                $statusText = 'Active';
                                            }
                                            
                                            // Vérification des permissions
                                            $isAdmin = in_array(auth()->user()->role, ['Super', 'Admin']);
                                            $isCustomer = auth()->user()->role === 'Customer';
                                            $customerId = auth()->user()->customer->id ?? null;
                                            $isOwnReservation = $isCustomer && $transaction->customer_id == $customerId;
                                            
                                            // URL pour l'édition
                                            $editUrl = $isAdmin ? route('transaction.edit', $transaction) : '#';
                                            
                                            // Vérifier si la réservation peut être annulée
                                            $canCancel = $isAdmin && !$isCancelled && !$isExpired;
                                            
                                            // Vérifier si on peut payer
                                            $canPay = !$isCancelled && !$isFullyPaid && ($isAdmin || $isOwnReservation);
                                            
                                            // Calcul du nombre de nuits
                                            $checkIn = \Carbon\Carbon::parse($transaction->check_in);
                                            $checkOut = \Carbon\Carbon::parse($transaction->check_out);
                                            $nights = $checkIn->diffInDays($checkOut);
                                        @endphp
                                        
                                        <tr class="{{ $isCancelled ? 'cancelled-row' : '' }}">
                                            <td>{{ ($transactions->currentpage() - 1) * $transactions->perpage() + $loop->index + 1 }}</td>
                                            <td><strong>#{{ $transaction->id }}</strong></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $transaction->customer->user->getAvatar() }}" 
                                                         class="rounded-circle me-2" width="30" height="30" 
                                                         alt="{{ $transaction->customer->name }}">
                                                    <div>
                                                        <div>{{ $transaction->customer->name }}</div>
                                                        <small class="text-muted">{{ $transaction->customer->phone ?? '' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $transaction->room->number }}
                                                </span>
                                            </td>
                                            <td>{{ $checkIn->format('d/m/Y') }}</td>
                                            <td>{{ $checkOut->format('d/m/Y') }}</td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $nights }} nuit{{ $nights > 1 ? 's' : '' }}
                                                </span>
                                            </td>
                                            <td class="price-cfa">
                                                {{ number_format($totalPrice, 0, ',', ' ') }} CFA
                                            </td>
                                            <td class="price-cfa">
                                                {{ number_format($totalPayment, 0, ',', ' ') }} CFA
                                            </td>
                                            <td class="price-cfa {{ $isFullyPaid ? 'text-success' : 'text-danger' }}">
                                                @if($isFullyPaid)
                                                    <span class="badge bg-success">Soldé</span>
                                                @else
                                                    {{ number_format($remaining, 0, ',', ' ') }} CFA
                                                @endif
                                            </td>
                                            <td>
                                                <span class="status-badge {{ $statusClass }}">
                                                    {{ $statusText }}
                                                </span>
                                                @if($transaction->cancelled_at && $isCancelled)
                                                    <br>
                                                    <small class="text-muted">
                                                        Annulée le {{ \Carbon\Carbon::parse($transaction->cancelled_at)->format('d/m/Y') }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <!-- Paiement -->
                                                    @if($canPay)
                                                        <a class="btn-action btn-pay"
                                                           href="{{ route('transaction.payment.create', $transaction) }}"
                                                           data-bs-toggle="tooltip" data-bs-placement="top" 
                                                           title="Effectuer un paiement">
                                                            <i class="fas fa-money-bill-wave-alt"></i>
                                                        </a>
                                                    @else
                                                        <span class="btn-action btn-pay disabled"
                                                              data-bs-toggle="tooltip" data-bs-placement="top" 
                                                              title="{{ $isFullyPaid ? 'Déjà payé' : ($isCancelled ? 'Réservation annulée' : ($isExpired ? 'Réservation expirée' : 'Non autorisé')) }}">
                                                            <i class="fas fa-money-bill-wave-alt"></i>
                                                        </span>
                                                    @endif
                                                    
                                                    <!-- Modifier -->
                                                    @if($isAdmin && !$isCancelled && !$isExpired)
                                                        <a class="btn-action btn-edit"
                                                           href="{{ $editUrl }}"
                                                           data-bs-toggle="tooltip" data-bs-placement="top" 
                                                           title="Modifier la réservation">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @else
                                                        <span class="btn-action btn-edit disabled"
                                                              data-bs-toggle="tooltip" data-bs-placement="top" 
                                                              title="{{ $isAdmin ? ($isCancelled ? 'Réservation annulée' : ($isExpired ? 'Réservation expirée' : 'Non autorisé')) : 'Modification réservée aux administrateurs' }}">
                                                            <i class="fas fa-edit"></i>
                                                        </span>
                                                    @endif
                                                    
                                                    <!-- Annuler -->
                                                    @if($canCancel)
                                                        <button type="button" class="btn-action btn-cancel cancel-reservation-btn"
                                                                data-transaction-id="{{ $transaction->id }}"
                                                                data-transaction-number="#{{ $transaction->id }}"
                                                                data-customer-name="{{ $transaction->customer->name }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="top" 
                                                                title="Annuler la réservation">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    @elseif($isAdmin && !$isCancelled && !$isExpired)
                                                        <span class="btn-action btn-cancel disabled"
                                                              data-bs-toggle="tooltip" data-bs-placement="top" 
                                                              title="Non autorisé">
                                                            <i class="fas fa-ban"></i>
                                                        </span>
                                                    @endif
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center py-4">
                                                <i class="fas fa-bed fa-2x text-muted mb-3"></i>
                                                <h5>Aucune Réservation Active</h5>
                                                <p class="text-muted">Aucune réservation active trouvée</p>
                                                @if($isAdmin)
                                                    <a href="#" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                        <i class="fas fa-plus me-2"></i>Créer une réservation
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Pagination -->
                @if($transactions->hasPages())
                    <div class="mt-3">
                        {{ $transactions->onEachSide(2)->links('template.paginationlinks') }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Réservations Expirées/Anciennes -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Anciennes Réservations
                        <span class="badge bg-secondary">{{ $transactionsExpired->count() }}</span>
                    </h5>
                    <span class="status-badge status-expired">Expirées</span>
                </div>
                
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Client</th>
                                        <th>Chambre</th>
                                        <th>Arrivée</th>
                                        <th>Départ</th>
                                        <th>Nuits</th>
                                        <th>Total (CFA)</th>
                                        <th>Payé (CFA)</th>
                                        <th>Reste (CFA)</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($transactionsExpired as $transaction)
                                        @php
                                            $totalPrice = $transaction->getTotalPrice();
                                            $totalPayment = $transaction->getTotalPayment();
                                            $remaining = $totalPrice - $totalPayment;
                                            
                                            $isCancelled = $transaction->status == 'cancelled';
                                            $isFullyPaid = $remaining <= 0;
                                            $isAdmin = in_array(auth()->user()->role, ['Super', 'Admin']);
                                            $isCustomer = auth()->user()->role === 'Customer';
                                            $customerId = auth()->user()->customer->id ?? null;
                                            $isOwnReservation = $isCustomer && $transaction->customer_id == $customerId;
                                            
                                            if ($isCancelled) {
                                                $statusClass = 'status-cancelled';
                                                $statusText = 'Annulée';
                                            } elseif ($isFullyPaid) {
                                                $statusClass = 'status-completed';
                                                $statusText = 'Payé';
                                            } else {
                                                $statusClass = 'status-expired';
                                                $statusText = 'Expiré';
                                            }
                                            
                                            $canPay = !$isCancelled && !$isFullyPaid && ($isAdmin || $isOwnReservation);
                                            
                                            // Calcul du nombre de nuits
                                            $checkIn = \Carbon\Carbon::parse($transaction->check_in);
                                            $checkOut = \Carbon\Carbon::parse($transaction->check_out);
                                            $nights = $checkIn->diffInDays($checkOut);
                                        @endphp
                                        
                                        <tr class="{{ $isCancelled ? 'cancelled-row' : '' }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td><strong>#{{ $transaction->id }}</strong></td>
                                            <td>{{ $transaction->customer->name }}</td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $transaction->room->number }}
                                                </span>
                                            </td>
                                            <td>{{ $checkIn->format('d/m/Y') }}</td>
                                            <td>{{ $checkOut->format('d/m/Y') }}</td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $nights }} nuit{{ $nights > 1 ? 's' : '' }}
                                                </span>
                                            </td>
                                            <td class="price-cfa">
                                                {{ number_format($totalPrice, 0, ',', ' ') }} CFA
                                            </td>
                                            <td class="price-cfa">
                                                {{ number_format($totalPayment, 0, ',', ' ') }} CFA
                                            </td>
                                            <td class="price-cfa {{ $isFullyPaid ? 'text-success' : 'text-danger' }}">
                                                @if($isFullyPaid)
                                                    <span class="badge bg-success">Soldé</span>
                                                @else
                                                    {{ number_format($remaining, 0, ',', ' ') }} CFA
                                                @endif
                                            </td>
                                            <td>
                                                <span class="status-badge {{ $statusClass }}">
                                                    {{ $statusText }}
                                                </span>
                                                @if($transaction->cancelled_at && $isCancelled)
                                                    <br>
                                                    <small class="text-muted">
                                                        Annulée le {{ \Carbon\Carbon::parse($transaction->cancelled_at)->format('d/m/Y') }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <!-- Paiement pour dette -->
                                                    @if($canPay)
                                                        <a class="btn-action btn-pay"
                                                           href="{{ route('transaction.payment.create', $transaction) }}"
                                                           data-bs-toggle="tooltip" data-bs-placement="top" 
                                                           title="Payer la dette restante">
                                                            <i class="fas fa-money-bill-wave-alt"></i>
                                                        </a>
                                                    @elseif(!$isFullyPaid && !$isCancelled)
                                                        <span class="btn-action btn-pay disabled"
                                                              data-bs-toggle="tooltip" data-bs-placement="top" 
                                                              title="{{ $isAdmin ? 'Dette impayée' : 'Non autorisé' }}">
                                                            <i class="fas fa-money-bill-wave-alt"></i>
                                                        </span>
                                                    @endif
                                                    
                                                    <!-- Voir détails -->
                                                    @if($isAdmin || $isOwnReservation)
                                                        <a class="btn-action" style="background-color: #e2e3e5; color: #383d41;"
                                                           href="{{ route('transaction.show', $transaction) }}"
                                                           data-bs-toggle="tooltip" data-bs-placement="top" 
                                                           title="Voir les détails">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center py-4">
                                                <i class="fas fa-history fa-2x text-muted mb-3"></i>
                                                <h5>Aucune Ancienne Réservation</h5>
                                                <p class="text-muted">Aucune réservation expirée dans l'historique</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Pagination pour les anciennes réservations -->
                @if(method_exists($transactionsExpired, 'hasPages') && $transactionsExpired->hasPages())
                    <div class="mt-3">
                        {{ $transactionsExpired->onEachSide(1)->links('template.paginationlinks') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de nouvelle réservation -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Nouvelle Réservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-4">Le client a-t-il déjà un compte ?</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a class="btn btn-primary" href="{{ route('transaction.reservation.createIdentity') }}">
                            <i class="fas fa-user-plus me-2"></i>Nouveau compte
                        </a>
                        <a class="btn btn-success" href="{{ route('transaction.reservation.pickFromCustomer') }}">
                            <i class="fas fa-users me-2"></i>Client existant
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
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
// VERSION CORRIGÉE - SANS BOOTSTRAP JS
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== SYSTÈME D\'ANNULATION INITIALISÉ ===');
    
    // DÉSACTIVÉ: Les tooltips Bootstrap (cause l'erreur)
    // var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    // var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    //     return new bootstrap.Tooltip(tooltipTriggerEl);
    // });
    
    // Gérer l'annulation des réservations - VERSION SIMPLIFIÉE
    function attachCancelEvents() {
        const cancelButtons = document.querySelectorAll('.cancel-reservation-btn');
        console.log(`Trouvé ${cancelButtons.length} bouton(s) d'annulation`);
        
        cancelButtons.forEach(button => {
            // Cloner le bouton pour supprimer les anciens événements
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);
            
            // Attacher le nouvel événement
            newButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const transactionId = this.getAttribute('data-transaction-id');
                const transactionNumber = this.getAttribute('data-transaction-number');
                const customerName = this.getAttribute('data-customer-name');
                
                console.log(`Annulation demandée: ${transactionNumber} (ID: ${transactionId})`);
                
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
                    focusCancel: true,
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
                        
                        // Préparer le formulaire
                        setTimeout(() => {
                            const form = document.getElementById('cancel-form');
                            if (!form) {
                                console.error('Formulaire d\'annulation non trouvé !');
                                Swal.fire('Erreur', 'Formulaire non trouvé', 'error');
                                return;
                            }
                            
                            // Mettre à jour l'action
                            const newAction = `/transaction/${transactionId}/cancel`;
                            form.action = newAction;
                            
                            // Remplir les champs
                            document.getElementById('cancel-transaction-id-input').value = transactionId;
                            document.getElementById('cancel-reason-input').value = reason;
                            
                            console.log('Soumission vers:', newAction);
                            form.submit();
                        }, 500);
                    }
                });
            });
            
            // Visualiser que le bouton est actif
            newButton.style.cursor = 'pointer';
            newButton.style.border = '2px solid #28a745';
            newButton.title = 'Cliquez pour annuler cette réservation';
        });
    }
    
    // Attacher les événements
    attachCancelEvents();
    
    // Si pas de réservations, afficher le modal
    @if($transactions->count() == 0 && in_array(auth()->user()->role, ['Super', 'Admin']))
        setTimeout(() => {
            const modalElement = document.getElementById('staticBackdrop');
            if (modalElement) {
                // Utiliser jQuery ou méthode simple
                $('#staticBackdrop').modal('show');
            }
        }, 1000);
    @endif
    
    // Debug
    @if(config('app.debug'))
        console.log('Réservations actives:', {{ $transactions->count() }});
        console.log('Réservations expirées:', {{ $transactionsExpired->count() }});
    @endif
    
    // Message final
    console.log('✅ Système d\'annulation prêt !');
    console.log('👉 Cliquez sur un bouton jaune (🚫) pour tester');
});
</script>

<!-- Style pour visualiser les boutons actifs -->
<style>
.cancel-reservation-btn {
    transition: all 0.2s ease;
}
.cancel-reservation-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 0 10px rgba(255, 193, 7, 0.5);
}
</style>
@endsection
