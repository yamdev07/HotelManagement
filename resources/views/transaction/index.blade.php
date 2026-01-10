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
        
        .btn-delete { background-color: #f8d7da; color: #842029; }
        .btn-delete:hover:not(.disabled) { background-color: #e8c7ca; }
        
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
        
        .debug-info {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .route-test {
            display: none; /* Cacher en production */
        }
        
        .test-links {
            background-color: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        
        .test-links a {
            font-size: 12px;
            margin-right: 10px;
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
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- TESTS DE DEBUG (uniquement en développement local) -->
        @if(app()->environment('local') && $transactions->isNotEmpty())
            <div class="test-links">
                <strong><i class="fas fa-bug me-2"></i>Debug Mode</strong>
                <div class="mt-2">
                    @php
                        $testTransaction = $transactions->first();
                    @endphp
                    <small class="text-muted me-3">Test Transaction ID: {{ $testTransaction->id }}</small>
                    <a href="{{ route('transaction.edit', $testTransaction->id) }}" 
                       class="badge bg-primary text-decoration-none" target="_blank">
                        Test Route avec ID
                    </a>
                    <a href="{{ route('transaction.edit', $testTransaction) }}" 
                       class="badge bg-success text-decoration-none" target="_blank">
                        Test Route avec objet
                    </a>
                    <a href="{{ route('transaction.edit', ['transaction' => $testTransaction->id]) }}" 
                       class="badge bg-info text-decoration-none" target="_blank">
                        Test Route param nommé
                    </a>
                    <a href="/transaction/{{ $testTransaction->id }}/edit" 
                       class="badge bg-warning text-decoration-none" target="_blank">
                        URL Directe
                    </a>
                </div>
            </div>
        @endif

        <!-- Réservations Actives -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>Clients Actuels
                        <span class="badge bg-primary">{{ $transactions->count() }}</span>
                    </h5>
                    <span class="status-badge status-active">Actives</span>
                </div>
                
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
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
                                            // CORRECTION : Ajout de ?? 0 pour éviter les erreurs null
                                            $totalPrice = $transaction->getTotalPrice() ?? 0;
                                            $totalPayment = $transaction->getTotalPayment() ?? 0;
                                            $remaining = $totalPrice - $totalPayment;
                                            $isFullyPaid = $remaining <= 0;
                                            $checkOutDate = \Carbon\Carbon::parse($transaction->check_out);
                                            $isExpired = $checkOutDate->isPast();
                                            $statusClass = $isFullyPaid ? 'status-completed' : ($isExpired ? 'status-expired' : 'status-active');
                                            $statusText = $isFullyPaid ? 'Payé' : ($isExpired ? 'Expiré' : 'Active');
                                            
                                            // Vérification des permissions
                                            $canEdit = in_array(auth()->user()->role, ['Super', 'Admin']);
                                            
                                            // CORRECTION : URL directe simplifiée
                                            $editUrlDirect = $canEdit ? "/transaction/" . $transaction->id . "/edit" : '#';
                                        @endphp
                                        
                                        <tr>
                                            <td>{{ ($transactions->currentpage() - 1) * $transactions->perpage() + $loop->index + 1 }}</td>
                                            <td><strong>#{{ $transaction->id }}</strong></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $transaction->customer->user->getAvatar() }}" 
                                                         class="rounded-circle me-2" width="30" height="30">
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
                                            <td>{{ Helper::dateFormat($transaction->check_in) }}</td>
                                            <td>{{ Helper::dateFormat($transaction->check_out) }}</td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $transaction->getDateDifferenceWithPlural($transaction->check_in, $transaction->check_out) }}
                                                </span>
                                            </td>
                                            <td class="price-cfa">
                                                {{ Helper::formatCFA($totalPrice) }}
                                            </td>
                                            <td class="price-cfa">
                                                {{ Helper::formatCFA($totalPayment) }}
                                            </td>
                                            <td class="price-cfa {{ $isFullyPaid ? 'text-success' : 'text-danger' }}">
                                                {{ $isFullyPaid ? '-' : Helper::formatCFA($remaining) }}
                                            </td>
                                            <td>
                                                <span class="status-badge {{ $statusClass }}">
                                                    {{ $statusText }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <!-- Paiement -->
                                                    <a class="btn-action btn-pay {{ $isFullyPaid ? 'disabled' : '' }}"
                                                       href="{{ $isFullyPaid ? '#' : route('transaction.payment.create', ['transaction' => $transaction->id]) }}"
                                                       data-bs-toggle="tooltip" data-bs-placement="top" 
                                                       title="{{ $isFullyPaid ? 'Déjà payé' : 'Payer' }}">
                                                        <i class="fas fa-money-bill-wave-alt"></i>
                                                    </a>
                                                    
                                                    <!-- Modifier - CORRECTION : URL directe simplifiée -->
                                                    @if($canEdit)
                                                        <a class="btn-action btn-edit"
                                                           href="{{ $editUrlDirect }}"
                                                           data-bs-toggle="tooltip" data-bs-placement="top" 
                                                           title="Modifier"
                                                           data-transaction-id="{{ $transaction->id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @else
                                                        <span class="btn-action btn-edit disabled"
                                                              data-bs-toggle="tooltip" data-bs-placement="top" 
                                                              title="Modification réservée aux administrateurs">
                                                            <i class="fas fa-edit"></i>
                                                        </span>
                                                    @endif
                                                    
                                                    <!-- Supprimer -->
                                                    <button type="button" class="btn-action btn-delete delete-reservation-btn"
                                                            data-transaction-id="{{ $transaction->id }}"
                                                            data-transaction-number="{{ $transaction->id }}"
                                                            data-customer-name="{{ $transaction->customer->name }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" 
                                                            title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center py-4">
                                                <i class="fas fa-bed fa-2x text-muted mb-3"></i>
                                                <h5>Aucune Réservation Active</h5>
                                                <p class="text-muted">Aucun client ne séjourne actuellement</p>
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
                                <thead class="table-light">
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
                                            // CORRECTION : Ajout de ?? 0 pour éviter les erreurs null
                                            $totalPrice = $transaction->getTotalPrice() ?? 0;
                                            $totalPayment = $transaction->getTotalPayment() ?? 0;
                                            $remaining = $totalPrice - $totalPayment;
                                            $isFullyPaid = $remaining <= 0;
                                            $statusClass = $isFullyPaid ? 'status-completed' : 'status-expired';
                                            $statusText = $isFullyPaid ? 'Payé' : 'Impayé';
                                            
                                            // Vérification des permissions
                                            $canEdit = in_array(auth()->user()->role, ['Super', 'Admin']);
                                            // URL directe pour éviter les erreurs de route
                                            $editUrlDirect = $canEdit ? "/transaction/" . $transaction->id . "/edit" : '#';
                                        @endphp
                                        
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><strong>#{{ $transaction->id }}</strong></td>
                                            <td>{{ $transaction->customer->name }}</td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $transaction->room->number }}
                                                </span>
                                            </td>
                                            <td>{{ Helper::dateFormat($transaction->check_in) }}</td>
                                            <td>{{ Helper::dateFormat($transaction->check_out) }}</td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $transaction->getDateDifferenceWithPlural($transaction->check_in, $transaction->check_out) }}
                                                </span>
                                            </td>
                                            <td class="price-cfa">
                                                {{ Helper::formatCFA($totalPrice) }}
                                            </td>
                                            <td class="price-cfa">
                                                {{ Helper::formatCFA($totalPayment) }}
                                            </td>
                                            <td class="price-cfa {{ $isFullyPaid ? 'text-success' : 'text-danger' }}">
                                                {{ $isFullyPaid ? '-' : Helper::formatCFA($remaining) }}
                                            </td>
                                            <td>
                                                <span class="status-badge {{ $statusClass }}">
                                                    {{ $statusText }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <!-- Paiement (si impayé) -->
                                                    <a class="btn-action btn-pay {{ $isFullyPaid ? 'disabled' : '' }}"
                                                       href="{{ $isFullyPaid ? '#' : route('transaction.payment.create', ['transaction' => $transaction->id]) }}"
                                                       data-bs-toggle="tooltip" data-bs-placement="top" 
                                                       title="{{ $isFullyPaid ? 'Déjà payé' : 'Payer dette' }}">
                                                        <i class="fas fa-money-bill-wave-alt"></i>
                                                    </a>
                                                    
                                                    <!-- Modifier (si autorisé) -->
                                                    @if($canEdit)
                                                        <a class="btn-action btn-edit"
                                                           href="{{ $editUrlDirect }}"
                                                           data-bs-toggle="tooltip" data-bs-placement="top" 
                                                           title="Modifier (réservation expirée)"
                                                           data-transaction-id="{{ $transaction->id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @else
                                                        <span class="btn-action btn-edit disabled"
                                                              data-bs-toggle="tooltip" data-bs-placement="top" 
                                                              title="Modification réservée aux administrateurs">
                                                            <i class="fas fa-edit"></i>
                                                        </span>
                                                    @endif
                                                    
                                                    <!-- Supprimer -->
                                                    <button type="button" class="btn-action btn-delete delete-reservation-btn"
                                                            data-transaction-id="{{ $transaction->id }}"
                                                            data-transaction-number="{{ $transaction->id }}"
                                                            data-customer-name="{{ $transaction->customer->name }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" 
                                                            title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
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

    <!-- Formulaire de suppression masqué -->
    <form id="delete-form" method="POST" class="d-none">
        @csrf
        @method('DELETE')
        <input type="hidden" name="transaction_id" id="transaction-id-input">
    </form>
@endsection

@section('footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Initialiser les tooltips Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Gérer la suppression des réservations
    document.querySelectorAll('.delete-reservation-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const transactionId = this.getAttribute('data-transaction-id');
            const transactionNumber = this.getAttribute('data-transaction-number');
            const customerName = this.getAttribute('data-customer-name');
            
            Swal.fire({
                title: 'Confirmer la suppression',
                html: `
                    <div class="text-left">
                        <p>Êtes-vous sûr de vouloir supprimer cette réservation ?</p>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Attention :</strong> Cette action est irréversible !
                        </div>
                        <p><strong>Réservation #:</strong> ${transactionNumber}</p>
                        <p><strong>Client :</strong> ${customerName}</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Préparer et soumettre le formulaire de suppression
                    const deleteForm = document.getElementById('delete-form');
                    deleteForm.action = '{{ url("transaction") }}/' + transactionId;
                    deleteForm.querySelector('#transaction-id-input').value = transactionId;
                    deleteForm.submit();
                    
                    // Afficher un message de succès
                    Swal.fire({
                        title: 'Suppression en cours...',
                        text: 'La réservation est en cours de suppression',
                        icon: 'info',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
    });
    
    // Debug des liens d'édition (uniquement en développement)
    @if(app()->environment('local'))
        console.log('=== TRANSACTION SYSTEM LOADED ===');
        console.log('User:', '{{ auth()->user()->name ?? "Guest" }}');
        console.log('Role:', '{{ auth()->user()->role ?? "None" }}');
        console.log('Route transaction.edit exists:', {{ Route::has('transaction.edit') ? 'true' : 'false' }});
        
        // Vérifier les liens d'édition
        document.querySelectorAll('.btn-edit').forEach((link, index) => {
            console.log(`Edit Link ${index + 1}:`, link.href);
        });
        
        // Tester toutes les URLs possibles
        @if($transactions->isNotEmpty())
            const testId = {{ $transactions->first()->id }};
            console.log('Test ID:', testId);
            console.log('Direct URL:', '/transaction/' + testId + '/edit');
            
            // Tester la route avec fetch
            fetch('/test-route/' + testId)
                .then(response => response.json())
                .then(data => console.log('Route test result:', data))
                .catch(error => console.error('Route test error:', error));
        @endif
    @endif
    
    // Ajouter un événement pour déboguer les clics sur les liens d'édition
    document.querySelectorAll('.btn-edit').forEach(link => {
        link.addEventListener('click', function(e) {
            @if(app()->environment('local'))
                console.log('Edit clicked:', this.href);
                console.log('Transaction ID:', this.getAttribute('data-transaction-id'));
            @endif
        });
    });
});
</script>
@endsection