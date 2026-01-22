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
        
        .btn-arrived { background-color: #28a745; color: white; }
        .btn-arrived:hover:not(.disabled) { background-color: #218838; }
        
        .btn-departed { background-color: #17a2b8; color: white; }
        .btn-departed:hover:not(.disabled) { background-color: #138496; }
        
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
        
        .status-select {
            max-width: 120px;
            font-size: 0.85rem;
            padding: 2px 8px;
            border-radius: 4px;
            border: 1px solid #ced4da;
            background-color: white;
        }
        
        .status-select:focus {
            outline: none;
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .status-form {
            display: inline-block;
            margin: 0;
            padding: 0;
        }
        
        .badge-reservation {
            background-color: #ffc107;
            color: #000;
        }
        
        .badge-active {
            background-color: #198754;
            color: #fff;
        }
        
        .badge-completed {
            background-color: #0dcaf0;
            color: #fff;
        }
        
        .badge-cancelled {
            background-color: #dc3545;
            color: #fff;
        }
        
        .badge-no_show {
            background-color: #6c757d;
            color: #fff;
        }
        
        .form-select-sm {
            font-size: 0.875rem;
            padding: 0.25rem 2rem 0.25rem 0.5rem;
        }
        
        /* NOUVEAU : Styles pour les séjours terminés mais non payés */
        .unpaid-departure-alert {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 8px 12px;
            margin: 4px 0;
            border-radius: 4px;
            font-size: 0.85rem;
        }
        
        .unpaid-departure-alert .alert-link {
            font-weight: 600;
            color: #856404;
        }
        
        /* NOUVEAU : Style pour les options désactivées */
        select option:disabled {
            color: #6c757d;
            background-color: #f8f9fa;
        }
        
        /* NOUVEAU : Style pour les montants impayés */
        .unpaid-amount {
            font-weight: 700;
            color: #dc3545;
            animation: pulse 2s infinite;
        }
        
        @keyframes unpaid-pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
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
                    
                    <!-- Test auto-statuts (DEBUG) -->
                    @if(env('APP_DEBUG', false) && in_array(auth()->user()->role, ['Super', 'Admin']))
                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Tester les mises à jour automatiques">
                        <a href="{{ route('test.auto-status') }}" class="btn btn-outline-warning" target="_blank">
                            <i class="fas fa-cogs me-2"></i>Test Auto
                        </a>
                    </span>
                    @endif
                    
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
                {!! session('success') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error') || session('failed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {!! session('error') ?? session('failed') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                {!! session('info') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Message spécial pour les départs réussis -->
        @if(session('departure_success'))
            @php $departure = session('departure_success'); @endphp
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>{{ $departure['title'] }}</strong><br>
                {{ $departure['message'] }}
                <div class="mt-2 small">
                    Transaction: #{{ $departure['transaction_id'] }} | 
                    Chambre: {{ $departure['room_number'] }} | 
                    Client: {{ $departure['customer_name'] }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Réservations Actives -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>Gestion des Réservations
                        <span class="badge bg-primary">{{ $transactions->count() }}</span>
                    </h5>
                    <div class="d-flex gap-2">
                        <span class="badge badge-reservation">📅 Réservation</span>
                        <span class="badge badge-active">🏨 Dans l'hôtel</span>
                        <span class="badge badge-completed">✅ Terminé (payé)</span>
                        <span class="badge badge-cancelled">❌ Annulée</span>
                        <span class="badge badge-no_show">👤 No Show</span>
                        <span class="badge bg-warning">⚠️ Terminé mais impayé</span>
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
                                            $isFullyPaid = $remaining <= 0;
                                            
                                            // Déterminer le statut depuis la base
                                            $status = $transaction->status;
                                            $statusText = $transaction->status_label;
                                            $statusClass = 'status-' . $status;
                                            $badgeClass = 'badge-' . $status;
                                            
                                            // Vérification des permissions
                                            $isAdmin = in_array(auth()->user()->role, ['Super', 'Admin', 'Reception']);
                                            $isCustomer = auth()->user()->role === 'Customer';
                                            $customerId = auth()->user()->customer->id ?? null;
                                            $isOwnReservation = $isCustomer && $transaction->customer_id == $customerId;
                                            
                                            // URL pour l'édition
                                            $editUrl = $isAdmin ? route('transaction.edit', $transaction) : '#';
                                            
                                            // Vérifier si la réservation peut être annulée
                                            $canCancel = $isAdmin && !in_array($status, ['cancelled', 'no_show', 'completed']);
                                            
                                            // Vérifier si on peut payer
                                            $canPay = !in_array($status, ['cancelled', 'no_show']) && !$isFullyPaid && ($isAdmin || $isOwnReservation);
                                            
                                            // Calcul du nombre de nuits
                                            $checkIn = \Carbon\Carbon::parse($transaction->check_in);
                                            $checkOut = \Carbon\Carbon::parse($transaction->check_out);
                                            $nights = $checkIn->diffInDays($checkOut);
                                            
                                            // Vérifier si on peut marquer comme arrivé/départ
                                            $canMarkArrived = $isAdmin && $status == 'reservation';
                                            $canMarkDeparted = $isAdmin && $status == 'active';
                                            
                                            // NOUVEAU : Vérifier si séjour terminé mais non payé
                                            $isPastDue = $checkOut->isPast() && $status == 'active' && !$isFullyPaid;
                                            $canMarkCompleted = $isAdmin && $status == 'active' && $isFullyPaid;
                                        @endphp
                                        
                                        <tr class="{{ in_array($status, ['cancelled', 'no_show']) ? 'cancelled-row' : '' }}">
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
                                            <td class="price-cfa {{ $isFullyPaid ? 'text-success' : 'text-danger unpaid-amount' }}">
                                                @if($isFullyPaid)
                                                    <span class="badge bg-success">Soldé</span>
                                                @else
                                                    {{ number_format($remaining, 0, ',', ' ') }} CFA
                                                    @if($isPastDue)
                                                        <br><small class="text-danger">⚠️ Départ dépassé</small>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if($isAdmin)
                                                    <!-- COMBO BOX POUR ADMIN AVEC VALIDATION DE PAIEMENT -->
                                                    <form action="{{ route('transaction.updateStatus', $transaction) }}" method="POST" class="status-form" id="status-form-{{ $transaction->id }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <select name="status" class="form-control form-select-sm status-select" 
                                                                id="status-select-{{ $transaction->id }}"
                                                                data-transaction-id="{{ $transaction->id }}"
                                                                data-is-fully-paid="{{ $isFullyPaid ? 'true' : 'false' }}"
                                                                data-remaining="{{ $remaining }}"
                                                                data-old-status="{{ $status }}">
                                                            <option value="reservation" {{ $status == 'reservation' ? 'selected' : '' }} 
                                                                    class="text-warning">📅 Réservation</option>
                                                            <option value="active" {{ $status == 'active' ? 'selected' : '' }}
                                                                    class="text-success">🏨 Dans l'hôtel</option>
                                                            
                                                            <!-- Option "completed" conditionnelle -->
                                                            <option value="completed" 
                                                                    {{ $status == 'completed' ? 'selected' : '' }}
                                                                    class="text-info"
                                                                    {{ !$isFullyPaid ? 'disabled' : '' }}
                                                                    data-can-complete="{{ $isFullyPaid ? 'true' : 'false' }}">
                                                                ✅ Séjour terminé
                                                                @if(!$isFullyPaid)
                                                                    (Solde: {{ number_format($remaining, 0, ',', ' ') }} CFA)
                                                                @endif
                                                            </option>
                                                            
                                                            <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}
                                                                    class="text-danger">❌ Annulée</option>
                                                            <option value="no_show" {{ $status == 'no_show' ? 'selected' : '' }}
                                                                    class="text-secondary">👤 No Show</option>
                                                        </select>
                                                    </form>
                                                @else
                                                    <!-- BADGE POUR LES CLIENTS -->
                                                    <span class="badge {{ $badgeClass }}">
                                                        {{ $statusText }}
                                                        @if($isPastDue)
                                                            <i class="fas fa-exclamation-triangle ms-1"></i>
                                                        @endif
                                                    </span>
                                                @endif
                                                
                                                @if($transaction->cancelled_at && $status == 'cancelled')
                                                    <br>
                                                    <small class="text-muted">
                                                        Annulée le {{ \Carbon\Carbon::parse($transaction->cancelled_at)->format('d/m/Y') }}
                                                    </small>
                                                @endif
                                                
                                                <!-- NOUVEAU : Afficher un message pour les séjours terminés mais non payés -->
                                                @if($isPastDue)
                                                    <div class="unpaid-departure-alert mt-1">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Séjour terminé mais <strong>impayé</strong>
                                                        <a href="{{ route('transaction.payment.create', $transaction) }}" class="alert-link ms-2">
                                                            <i class="fas fa-money-bill-wave me-1"></i>Régler maintenant
                                                        </a>
                                                    </div>
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
                                                              title="{{ $isFullyPaid ? 'Déjà payé' : (in_array($status, ['cancelled', 'no_show']) ? 'Réservation annulée/no show' : 'Non autorisé') }}">
                                                            <i class="fas fa-money-bill-wave-alt"></i>
                                                        </span>
                                                    @endif
                                                    
                                                    <!-- Marquer comme arrivé -->
                                                    @if($canMarkArrived)
                                                        <form action="{{ route('transaction.mark-arrived', $transaction) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn-action btn-arrived"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top" 
                                                                    title="Marquer comme arrivé">
                                                                <i class="fas fa-sign-in-alt"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    <!-- Marquer comme parti (AVEC VÉRIFICATION DE PAIEMENT) -->
                                                    @if($canMarkDeparted)
                                                        <button type="button" class="btn-action btn-departed mark-departed-btn"
                                                                data-transaction-id="{{ $transaction->id }}"
                                                                data-is-fully-paid="{{ $isFullyPaid ? 'true' : 'false' }}"
                                                                data-remaining="{{ $remaining }}"
                                                                data-form-action="{{ route('transaction.mark-departed', $transaction) }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="top" 
                                                                title="{{ $isFullyPaid ? 'Marquer comme parti' : 'Impossible : paiement incomplet' }}"
                                                                {{ !$isFullyPaid ? 'disabled' : '' }}>
                                                            <i class="fas fa-sign-out-alt"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    <!-- Modifier -->
                                                    @if($isAdmin && !in_array($status, ['cancelled', 'no_show', 'completed']))
                                                        <a class="btn-action btn-edit"
                                                           href="{{ $editUrl }}"
                                                           data-bs-toggle="tooltip" data-bs-placement="top" 
                                                           title="Modifier la réservation">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @else
                                                        <span class="btn-action btn-edit disabled"
                                                              data-bs-toggle="tooltip" data-bs-placement="top" 
                                                              title="{{ $isAdmin ? 'Réservation non modifiable' : 'Modification réservée aux administrateurs' }}">
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
                                                    @elseif($isAdmin && !in_array($status, ['cancelled', 'no_show']))
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
                                                <h5>Aucune Réservation Trouvée</h5>
                                                <p class="text-muted">Aucune réservation active trouvée</p>
                                                @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Reception']))
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

        <!-- Réservations Anciennes/Expirées -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Anciennes Réservations
                        <span class="badge bg-secondary">{{ $transactionsExpired->count() }}</span>
                    </h5>
                    <small class="text-muted">Réservations terminées ou expirées</small>
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
                                            $isFullyPaid = $remaining <= 0;
                                            
                                            // Déterminer le statut depuis la base
                                            $status = $transaction->status;
                                            $statusText = $transaction->status_label;
                                            $statusClass = 'status-' . $status;
                                            $badgeClass = 'badge-' . $status;
                                            
                                            $isAdmin = in_array(auth()->user()->role, ['Super', 'Admin', 'Reception']);
                                            $isCustomer = auth()->user()->role === 'Customer';
                                            $customerId = auth()->user()->customer->id ?? null;
                                            $isOwnReservation = $isCustomer && $transaction->customer_id == $customerId;
                                            
                                            $canPay = !in_array($status, ['cancelled', 'no_show']) && !$isFullyPaid && ($isAdmin || $isOwnReservation);
                                            
                                            // Calcul du nombre de nuits
                                            $checkIn = \Carbon\Carbon::parse($transaction->check_in);
                                            $checkOut = \Carbon\Carbon::parse($transaction->check_out);
                                            $nights = $checkIn->diffInDays($checkOut);
                                        @endphp
                                        
                                        <tr class="{{ in_array($status, ['cancelled', 'no_show']) ? 'cancelled-row' : '' }}">
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
                                            <td class="price-cfa {{ $isFullyPaid ? 'text-success' : 'text-danger unpaid-amount' }}">
                                                @if($isFullyPaid)
                                                    <span class="badge bg-success">Soldé</span>
                                                @else
                                                    {{ number_format($remaining, 0, ',', ' ') }} CFA
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $badgeClass }}">
                                                    {{ $statusText }}
                                                    @if(!$isFullyPaid && $status == 'completed')
                                                        <i class="fas fa-exclamation-triangle ms-1" title="Anomalie : marqué comme terminé mais impayé"></i>
                                                    @endif
                                                </span>
                                                @if($transaction->cancelled_at && $status == 'cancelled')
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
                                                    @elseif($remaining > 0 && !in_array($status, ['cancelled', 'no_show']))
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
                                                    
                                                    <!-- Restaurer si annulée -->
                                                    @if($isAdmin && $status == 'cancelled')
                                                        <form action="{{ route('transaction.restore', $transaction) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn-action" 
                                                                    style="background-color: #20c997; color: white;"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top" 
                                                                    title="Restaurer la réservation"
                                                                    onclick="return confirm('Restaurer cette réservation ?')">
                                                                <i class="fas fa-undo"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center py-4">
                                                <i class="fas fa-history fa-2x text-muted mb-3"></i>
                                                <h5>Aucune Ancienne Réservation</h5>
                                                <p class="text-muted">Aucune réservation dans l'historique</p>
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
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== SYSTÈME DE GESTION DES RÉSERVATIONS AVEC VALIDATION PAIEMENT ===');
    
    // Gérer l'annulation des réservations
    function attachCancelEvents() {
        const cancelButtons = document.querySelectorAll('.cancel-reservation-btn');
        console.log(`Trouvé ${cancelButtons.length} bouton(s) d'annulation`);
        
        cancelButtons.forEach(button => {
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);
            
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
        });
    }
    
    // ======================================================
    // GÉRER LES CHANGEMENTS DE STATUT AVEC VÉRIFICATION PAIEMENT
    // ======================================================
    function attachStatusChangeEvents() {
        const statusSelects = document.querySelectorAll('.status-select');
        console.log(`Trouvé ${statusSelects.length} sélecteur(s) de statut`);
        
        statusSelects.forEach(select => {
            // Stocker l'ancienne valeur
            const originalValue = select.value;
            const transactionId = select.getAttribute('data-transaction-id');
            const isFullyPaid = select.getAttribute('data-is-fully-paid') === 'true';
            const remaining = parseFloat(select.getAttribute('data-remaining')) || 0;
            
            select.addEventListener('change', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const newStatus = this.value;
                const selectedOption = this.options[this.selectedIndex];
                const oldStatus = this.getAttribute('data-old-status');
                const canComplete = selectedOption.getAttribute('data-can-complete') === 'true';
                
                console.log(`Changement de statut demandé: Transaction #${transactionId}, ${oldStatus} → ${newStatus}`);
                
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
                
                // ==============================================
                // VÉRIFICATION 1 : Bloquer "completed" si non payé
                // ==============================================
                if (newStatus === 'completed' && !canComplete) {
                    // Bloquer et afficher un message
                    Swal.fire({
                        icon: 'error',
                        title: 'Paiement incomplet',
                        html: `
                            <div class="text-start">
                                <p><strong>Impossible de marquer comme "Séjour terminé"</strong></p>
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Solde restant : ${remaining.toLocaleString('fr-FR')} CFA</strong>
                                </div>
                                <p class="mb-3">Veuillez d'abord compléter le paiement avant de marquer le séjour comme terminé.</p>
                                <div class="d-grid gap-2">
                                    <a href="/transaction/${transactionId}/payment/create" class="btn btn-warning">
                                        <i class="fas fa-money-bill-wave me-2"></i>Régler maintenant
                                    </a>
                                    <button type="button" class="btn btn-secondary" onclick="Swal.close()">
                                        <i class="fas fa-times me-2"></i>Annuler
                                    </button>
                                </div>
                            </div>
                        `,
                        confirmButtonText: false,
                        showCancelButton: false,
                        allowOutsideClick: true
                    });
                    
                    // Revenir à l'ancienne valeur
                    this.value = originalValue;
                    return false;
                }
                
                // ==============================================
                // VÉRIFICATION 2 : Confirmation pour "cancelled"
                // ==============================================
                if (newStatus === 'cancelled') {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: 'Annuler cette réservation ?',
                        html: `
                            <div class="text-start">
                                <p>Confirmez l'annulation :</p>
                                <div class="alert alert-warning">
                                    <strong>Statut : ${oldLabel} → ${newLabel}</strong><br>
                                    <small>Transaction #${transactionId}</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Raison (optionnelle) :</label>
                                    <textarea id="cancelReasonInput" class="form-control" rows="3" 
                                              placeholder="Pourquoi annuler cette réservation ?"></textarea>
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
                                reason: document.getElementById('cancelReasonInput').value
                            };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const reason = result.value.reason || '';
                            
                            // Ajouter le champ de raison au formulaire
                            const form = document.getElementById(`status-form-${transactionId}`);
                            if (form) {
                                // Créer un champ caché pour la raison
                                const reasonInput = document.createElement('input');
                                reasonInput.type = 'hidden';
                                reasonInput.name = 'cancel_reason';
                                reasonInput.value = reason;
                                form.appendChild(reasonInput);
                                
                                // Soumettre le formulaire
                                console.log(`Soumission annulation avec raison: ${reason}`);
                                form.submit();
                            }
                        } else {
                            // Annuler : revenir à l'ancienne valeur
                            this.value = originalValue;
                        }
                    });
                    
                    return false;
                }
                
                // ==============================================
                // VÉRIFICATION 3 : Confirmation pour "no_show"
                // ==============================================
                if (newStatus === 'no_show') {
                    if (!confirm(`⚠️ Marquer comme "No Show" ?\n\nLe client ne s'est pas présenté.\nStatut: ${oldLabel} → ${newLabel}`)) {
                        this.value = originalValue;
                        return false;
                    }
                }
                
                // ==============================================
                // VÉRIFICATION 4 : Confirmation pour "completed" (si payé)
                // ==============================================
                if (newStatus === 'completed' && canComplete) {
                    if (!confirm(`✅ Marquer comme "Séjour terminé" ?\n\nLe paiement est complet.\nStatut: ${oldLabel} → ${newLabel}`)) {
                        this.value = originalValue;
                        return false;
                    }
                }
                
                // ==============================================
                // SOUMETTRE LE FORMULAIRE
                // ==============================================
                console.log(`Soumission du formulaire pour transaction #${transactionId}`);
                const form = document.getElementById(`status-form-${transactionId}`);
                if (form) {
                    form.submit();
                }
            });
        });
    }
    
    // ======================================================
    // GÉRER LES BOUTONS "MARQUER COMME PARTI" (Boutons rapides)
    // ======================================================
    function attachDepartButtonsEvents() {
        const departButtons = document.querySelectorAll('.mark-departed-btn');
        console.log(`Trouvé ${departButtons.length} bouton(s) "Marquer comme parti"`);
        
        departButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const transactionId = this.getAttribute('data-transaction-id');
                const isFullyPaid = this.getAttribute('data-is-fully-paid') === 'true';
                const remaining = parseFloat(this.getAttribute('data-remaining')) || 0;
                const formAction = this.getAttribute('data-form-action');
                
                console.log(`Départ demandé: Transaction #${transactionId}, Payé: ${isFullyPaid}`);
                
                if (!isFullyPaid) {
                    // Bloquer et afficher un message
                    Swal.fire({
                        icon: 'error',
                        title: 'Paiement incomplet',
                        html: `
                            <div class="text-start">
                                <p><strong>Impossible de marquer comme parti</strong></p>
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Solde restant : ${remaining.toLocaleString('fr-FR')} CFA</strong>
                                </div>
                                <p class="mb-3">Le client ne peut pas partir sans avoir réglé l'intégralité du séjour.</p>
                                <div class="d-grid gap-2">
                                    <a href="/transaction/${transactionId}/payment/create" class="btn btn-warning">
                                        <i class="fas fa-money-bill-wave me-2"></i>Régler maintenant
                                    </a>
                                    <button type="button" class="btn btn-secondary" onclick="Swal.close()">
                                        <i class="fas fa-times me-2"></i>Annuler
                                    </button>
                                </div>
                            </div>
                        `,
                        confirmButtonText: false,
                        showCancelButton: false,
                        allowOutsideClick: true
                    });
                    return false;
                }
                
                // Confirmation pour le départ
                Swal.fire({
                    title: 'Confirmer le départ',
                    html: `
                        <div class="text-start">
                            <p>Marquer le client comme parti ?</p>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Paiement complet vérifié ✓</strong><br>
                                <small>Transaction #${transactionId}</small>
                            </div>
                            <p>Cette action :</p>
                            <ul class="text-start">
                                <li>Marquera le séjour comme "terminé"</li>
                                <li>Libérera la chambre</li>
                                <li>Enregistrera l'heure de départ</li>
                            </ul>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#17a2b8',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-sign-out-alt me-2"></i> Oui, marquer comme parti',
                    cancelButtonText: '<i class="fas fa-times me-2"></i> Annuler',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Créer un formulaire dynamique pour soumettre
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = formAction;
                        form.style.display = 'none';
                        
                        // Ajouter le token CSRF
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        form.appendChild(csrfToken);
                        
                        // Ajouter au body et soumettre
                        document.body.appendChild(form);
                        console.log(`Soumission départ pour transaction #${transactionId}`);
                        form.submit();
                    }
                });
            });
        });
    }
    
    // ======================================================
    // INITIALISATION
    // ======================================================
    function initializeSystem() {
        console.log('Initialisation du système de gestion des réservations...');
        
        // Attacher les événements
        attachCancelEvents();
        attachStatusChangeEvents();
        attachDepartButtonsEvents();
        
        // Vérifier s'il y a des séjours terminés mais non payés
        const unpaidAlerts = document.querySelectorAll('.unpaid-departure-alert');
        if (unpaidAlerts.length > 0) {
            console.log(`⚠️ ${unpaidAlerts.length} séjour(s) terminé(s) mais non payé(s) détecté(s)`);
            
            // Optionnel : Afficher une notification globale
            if (unpaidAlerts.length >= 3) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Séjours impayés',
                    html: `
                        <div class="text-start">
                            <p><strong>${unpaidAlerts.length} séjour(s) terminé(s) mais non payé(s)</strong></p>
                            <p class="small">Ces clients sont partis sans avoir réglé l'intégralité de leur séjour.</p>
                            <div class="mt-3">
                                <a href="/payment" class="btn btn-sm btn-warning">
                                    <i class="fas fa-history me-2"></i>Voir l'historique des paiements
                                </a>
                            </div>
                        </div>
                    `,
                    confirmButtonText: 'Compris',
                    confirmButtonColor: '#ffc107',
                    showCancelButton: false,
                    allowOutsideClick: true
                });
            }
        }
        
        console.log('✅ Système de gestion des statuts avec validation de paiement prêt !');
    }
    
    // Démarrer l'initialisation
    initializeSystem();
    
    // Si pas de réservations, afficher le modal
    @if($transactions->count() == 0 && in_array(auth()->user()->role, ['Super', 'Admin', 'Reception']))
        setTimeout(() => {
            const modalElement = document.getElementById('staticBackdrop');
            if (modalElement) {
                // Utiliser jQuery si disponible
                if (typeof jQuery !== 'undefined') {
                    $('#staticBackdrop').modal('show');
                } else {
                    // Fallback vanilla JS
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            }
        }, 1000);
    @endif
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

.btn-arrived, .btn-departed {
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
    100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
}

/* Animation pour les montants impayés */
.unpaid-amount {
    animation: unpaid-pulse 2s infinite;
}
@keyframes unpaid-pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}
</style>
@endsection