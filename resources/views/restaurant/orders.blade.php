@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Gestion des Commandes</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Commandes Restaurant</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">Commandes en attente</p>
                            <h4 class="mt-2">{{ $pendingOrders ?? 0 }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-2">
                                <i class="fas fa-clock"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">Commandes livrées</p>
                            <h4 class="mt-2">{{ $deliveredOrders ?? 0 }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success-subtle text-success rounded-circle fs-2">
                                <i class="fas fa-check-circle"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">Chiffre d'affaires (auj.)</p>
                            <h4 class="mt-2">{{ number_format($todayRevenue ?? 0, 2) }} €</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-2">
                                <i class="fas fa-euro-sign"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">Commandes du mois</p>
                            <h4 class="mt-2">{{ $monthlyOrders ?? 0 }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info-subtle text-info rounded-circle fs-2">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et Actions -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Toutes les Commandes</h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newOrderModal">
                                <i class="fas fa-plus me-1"></i> Nouvelle Commande
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-download me-1"></i> Exporter
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Excel</a></li>
                                    <li><a class="dropdown-item" href="#">PDF</a></li>
                                    <li><a class="dropdown-item" href="#">CSV</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filtres -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Statut</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">Tous les statuts</option>
                                <option value="pending">En attente</option>
                                <option value="preparing">En préparation</option>
                                <option value="delivered">Livré</option>
                                <option value="paid">Payé</option>
                                <option value="cancelled">Annulé</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date de</label>
                            <input type="date" class="form-control" id="dateFrom">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date à</label>
                            <input type="date" class="form-control" id="dateTo">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary w-100" id="applyFilters">
                                <i class="fas fa-filter me-1"></i> Appliquer
                            </button>
                        </div>
                    </div>

                    <!-- Table des commandes -->
                    <div class="table-responsive">
                        <table class="table table-hover table-centered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Client</th>
                                    <th>Chambre</th>
                                    <th>Menus</th>
                                    <th>Total</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr data-status="{{ $order->status }}">
                                    <td><strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="fas fa-user-circle text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                {{ $order->customer_name ?? 'Client non spécifié' }}
                                                @if($order->customer_phone)
                                                <br><small class="text-muted">{{ $order->customer_phone }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($order->room_id)
                                        <span class="badge bg-info">Ch. {{ $order->room_number }}</span>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info view-items" 
                                                data-order-id="{{ $order->id }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#orderDetailsModal">
                                            {{ $order->items_count ?? 0 }} article(s)
                                        </button>
                                    </td>
                                    <td>
                                        <strong class="text-primary">{{ number_format($order->total, 2) }} €</strong>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'preparing' => 'info',
                                                'delivered' => 'success',
                                                'paid' => 'primary',
                                                'cancelled' => 'danger'
                                            ];
                                            $statusLabels = [
                                                'pending' => 'En attente',
                                                'preparing' => 'En préparation',
                                                'delivered' => 'Livré',
                                                'paid' => 'Payé',
                                                'cancelled' => 'Annulé'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                            {{ $statusLabels[$order->status] ?? $order->status }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#orderDetailsModal" data-order-id="{{ $order->id }}">
                                                        <i class="fas fa-eye me-2"></i> Détails
                                                    </a>
                                                </li>
                                                @if($order->status == 'pending')
                                                <li>
                                                    <a class="dropdown-item change-status" href="#" data-order-id="{{ $order->id }}" data-status="preparing">
                                                        <i class="fas fa-play me-2"></i> Préparer
                                                    </a>
                                                </li>
                                                @endif
                                                @if($order->status == 'preparing')
                                                <li>
                                                    <a class="dropdown-item change-status" href="#" data-order-id="{{ $order->id }}" data-status="delivered">
                                                        <i class="fas fa-check me-2"></i> Livrer
                                                    </a>
                                                </li>
                                                @endif
                                                @if(in_array($order->status, ['delivered', 'pending']))
                                                <li>
                                                    <a class="dropdown-item change-status" href="#" data-order-id="{{ $order->id }}" data-status="paid">
                                                        <i class="fas fa-euro-sign me-2"></i> Marquer payé
                                                    </a>
                                                </li>
                                                @endif
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item text-danger cancel-order" href="#" data-order-id="{{ $order->id }}">
                                                        <i class="fas fa-times me-2"></i> Annuler
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                        <h4>Aucune commande trouvée</h4>
                                        <p class="text-muted">Aucune commande n'a été passée pour le moment.</p>
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newOrderModal">
                                            <i class="fas fa-plus me-1"></i> Créer la première commande
                                        </button>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($orders->hasPages())
                    <div class="row mt-4">
                        <div class="col-12">
                            {{ $orders->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Détails de la commande -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails de la commande #<span id="orderId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="orderDetailsContent">
                    <!-- Contenu chargé dynamiquement -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="printOrder">
                    <i class="fas fa-print me-1"></i> Imprimer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nouvelle Commande -->
<div class="modal fade" id="newOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle Commande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('restaurant.orders.store') }}" method="POST" id="newOrderForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Client <span class="text-danger">*</span></label>
                            <select class="form-select" name="customer_id" id="newCustomerSelect" required>
                                <option value="">Sélectionner un client</option>
                                @foreach($customers ?? [] as $customer)
                                <option value="{{ $customer->id }}" data-room="{{ $customer->room_number ?? '' }}">
                                    {{ $customer->name }} - {{ $customer->room_number ? 'Chambre ' . $customer->room_number : 'Externe' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Chambre</label>
                            <input type="text" class="form-control" id="roomNumber" name="room_number" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Menus <span class="text-danger">*</span></label>
                        <div class="border rounded p-3">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <select class="form-select" id="menuSelect">
                                        <option value="">Sélectionner un menu</option>
                                        @foreach($menus ?? [] as $menu)
                                        <option value="{{ $menu->id }}" data-price="{{ $menu->price }}" data-name="{{ $menu->name }}">
                                            {{ $menu->name }} ({{ number_format($menu->price, 2) }} €)
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" id="quantity" value="1" min="1" placeholder="Quantité">
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-primary w-100" id="addItem">
                                        <i class="fas fa-plus me-1"></i> Ajouter
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm" id="itemsTable">
                                    <thead>
                                        <tr>
                                            <th>Menu</th>
                                            <th>Prix unitaire</th>
                                            <th>Quantité</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Items ajoutés dynamiquement -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">Total:</th>
                                            <th id="orderTotal">0.00 €</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <input type="hidden" name="items" id="itemsInput">
                            <input type="hidden" name="total" id="totalInput">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes supplémentaires</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Instructions spéciales..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Méthode de paiement</label>
                        <select class="form-select" name="payment_method">
                            <option value="cash">Espèces</option>
                            <option value="card">Carte bancaire</option>
                            <option value="room_charge">Frais de chambre</option>
                            <option value="online">En ligne</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-1"></i> Enregistrer la commande
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let items = [];
    let total = 0;

    // Filtrer par statut
    $('#statusFilter').change(function() {
        const status = $(this).val();
        if (status) {
            $('tbody tr').hide();
            $(`tbody tr[data-status="${status}"]`).show();
        } else {
            $('tbody tr').show();
        }
    });

    // Appliquer les filtres
    $('#applyFilters').click(function() {
        // Ici, vous pouvez implémenter le filtrage AJAX
        alert('Filtrage à implémenter avec AJAX');
    });

    // Afficher les détails de la commande
    $('.view-items').click(function() {
        const orderId = $(this).data('order-id');
        $('#orderId').text(orderId);
        
        // Charger les détails via AJAX
        $.ajax({
            url: `{{ route('restaurant.orders.show', '') }}/${orderId}`,
            type: 'GET',
            success: function(response) {
                $('#orderDetailsContent').html(response.html);
            },
            error: function() {
                $('#orderDetailsContent').html('<div class="alert alert-danger">Erreur lors du chargement des détails.</div>');
            }
        });
    });

    // Changer le statut
    $('.change-status').click(function(e) {
        e.preventDefault();
        const orderId = $(this).data('order-id');
        const status = $(this).data('status');
        
        if (confirm('Changer le statut de cette commande ?')) {
            $.ajax({
                url: `{{ route('restaurant.orders.update', '') }}/${orderId}`,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status
                },
                success: function(response) {
                    location.reload();
                }
            });
        }
    });

    // Annuler une commande
    $('.cancel-order').click(function(e) {
        e.preventDefault();
        const orderId = $(this).data('order-id');
        
        if (confirm('Êtes-vous sûr de vouloir annuler cette commande ?')) {
            $.ajax({
                url: `{{ route('restaurant.orders.cancel', '') }}/${orderId}`,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    location.reload();
                }
            });
        }
    });

    // Nouvelle commande - Logique
    $('#newCustomerSelect').change(function() {
        const room = $(this).find(':selected').data('room');
        $('#roomNumber').val(room || '');
    });

    // Ajouter un item
    $('#addItem').click(function() {
        const menuSelect = $('#menuSelect');
        const quantity = parseInt($('#quantity').val());
        
        if (!menuSelect.val()) {
            alert('Veuillez sélectionner un menu');
            return;
        }
        
        if (quantity < 1) {
            alert('La quantité doit être au moins 1');
            return;
        }
        
        const menuId = menuSelect.val();
        const menuName = menuSelect.find(':selected').data('name');
        const price = parseFloat(menuSelect.find(':selected').data('price'));
        const itemTotal = price * quantity;
        
        // Vérifier si l'item existe déjà
        const existingItem = items.find(item => item.menu_id == menuId);
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            items.push({
                menu_id: menuId,
                name: menuName,
                price: price,
                quantity: quantity
            });
        }
        
        updateItemsTable();
        menuSelect.val('');
        $('#quantity').val(1);
    });

    function updateItemsTable() {
        const tbody = $('#itemsTable tbody');
        tbody.empty();
        total = 0;
        
        items.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            
            const row = `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.price.toFixed(2)} €</td>
                    <td>
                        <input type="number" class="form-control form-control-sm quantity-input" 
                               data-index="${index}" value="${item.quantity}" min="1" style="width: 70px;">
                    </td>
                    <td>${itemTotal.toFixed(2)} €</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-item" data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
        
        $('#orderTotal').text(total.toFixed(2) + ' €');
        $('#totalInput').val(total);
        $('#itemsInput').val(JSON.stringify(items));
    }

    // Gérer la quantité
    $(document).on('change', '.quantity-input', function() {
        const index = $(this).data('index');
        const quantity = parseInt($(this).val());
        
        if (quantity < 1) {
            $(this).val(1);
            items[index].quantity = 1;
        } else {
            items[index].quantity = quantity;
        }
        
        updateItemsTable();
    });

    // Supprimer un item
    $(document).on('click', '.remove-item', function() {
        const index = $(this).data('index');
        items.splice(index, 1);
        updateItemsTable();
    });

    // Soumettre le formulaire
    $('#newOrderForm').submit(function(e) {
        if (items.length === 0) {
            e.preventDefault();
            alert('Veuillez ajouter au moins un menu à la commande');
            return;
        }
    });

    // Imprimer la commande
    $('#printOrder').click(function() {
        window.print();
    });
});
</script>

<style>
.card-animate {
    transition: transform 0.3s ease;
}

.card-animate:hover {
    transform: translateY(-5px);
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.badge {
    font-size: 0.75em;
    padding: 0.35em 0.65em;
}

#itemsTable input.form-control-sm {
    text-align: center;
}

.quantity-input::-webkit-inner-spin-button,
.quantity-input::-webkit-outer-spin-button {
    opacity: 1;
}
</style>
@endpush