@extends('template.master')
@section('title', 'Restaurant - Commandes')
@section('content')

@include('restaurant.partials.nav-tabs')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Gestion des Commandes</h3>
    <div class="d-flex gap-2">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newOrderModal">
            <i class="fas fa-plus me-2"></i> Nouvelle Commande
        </button>
    </div>
</div>

<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">En attente</h6>
                        <h3 class="mb-0 text-warning">{{ $pendingOrders ?? 0 }}</h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Livrées</h6>
                        <h3 class="mb-0 text-success">{{ $deliveredOrders ?? 0 }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">CA (auj.)</h6>
                        <h3 class="mb-0 text-primary">{{ number_format($todayRevenue ?? 0, 0, ',', ' ') }} CFA</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="fas fa-coins fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Mois</h6>
                        <h3 class="mb-0 text-info">{{ $monthlyOrders ?? 0 }}</h3>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded">
                        <i class="fas fa-calendar-alt fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <!-- Filtres -->
        <div class="p-3 border-bottom">
            <div class="row">
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
        </div>

        <!-- Table des commandes -->
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Chambre</th>
                        <th>Transaction</th>
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
                            @if($order->transaction_id)
                            <span class="badge bg-success">Trans. #{{ $order->transaction_id }}</span>
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
                            <strong class="text-primary">{{ number_format($order->total, 0, ',', ' ') }} CFA</strong>
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
                                            <i class="fas fa-money-bill-wave me-2"></i> Marquer payé
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
        <div class="p-3 border-top">
            {{ $orders->links() }}
        </div>
        @endif
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

@endsection

@include('restaurant.partials.new-order-modal')

