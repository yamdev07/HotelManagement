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

<!-- Statistiques du Jour -->
<div class="row mb-4 align-items-stretch">
    <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
        <div class="card border-0 shadow-sm border-start border-warning border-4 h-100">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div>
                        <h6 class="text-muted mb-0 small">CA du jour</h6>
                        <h4 class="mb-0 text-warning">{{ number_format($todayRevenue ?? 0, 0, ',', ' ') }} CFA</h4>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-2 rounded">
                        <i class="fas fa-coins text-warning"></i>
                    </div>
                </div>
                <hr class="my-1 opacity-25">
                <div class="row g-0">
                    <div class="col-6 border-end px-1">
                        <small class="text-muted d-block" style="font-size: 0.65rem;">Chambre</small>
                        <span class="fw-bold text-success" style="font-size: 0.8rem;">{{ number_format($todayRoomRevenue ?? 0, 0, ',', ' ') }}</span>
                    </div>
                    <div class="col-6 ps-2">
                        <small class="text-muted d-block" style="font-size: 0.65rem;">Hors-Ch.</small>
                        <span class="fw-bold text-info" style="font-size: 0.8rem;">{{ number_format($todayNoRoomRevenue ?? 0, 0, ',', ' ') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>    <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
        <div class="card border-0 shadow-sm border-start border-primary border-4 h-100">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div>
                        <h6 class="text-muted mb-0 small">Commandes (Auj.)</h6>
                        <h4 class="mb-0 text-primary">{{ $todayOrdersTotal ?? 0 }}</h4>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-2 rounded">
                        <i class="fas fa-receipt text-primary"></i>
                    </div>
                </div>
                <hr class="my-1 opacity-25">
                <div class="row g-0">
                    <div class="col-6 border-end px-1">
                        <small class="text-muted d-block" style="font-size: 0.65rem;">Chambre</small>
                        <span class="fw-bold text-success" style="font-size: 0.8rem;">{{ $todayOrdersRoom ?? 0 }}</span>
                    </div>
                    <div class="col-6 ps-2">
                        <small class="text-muted d-block" style="font-size: 0.65rem;">Hors-Ch.</small>
                        <span class="fw-bold text-info" style="font-size: 0.8rem;">{{ $todayOrdersNoRoom ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
        <div class="card border-0 shadow-sm border-start border-danger border-4 h-100">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div>
                        <h6 class="text-muted mb-0 small">Attente / Préparation (Auj.)</h6>
                        <h4 class="mb-0 text-danger">{{ ($pendingOrders ?? 0) + ($preparingOrders ?? 0) }}</h4>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-2 rounded">
                        <i class="fas fa-fire text-danger"></i>
                    </div>
                </div>
                <hr class="my-1 opacity-25">
                <div class="row g-0">
                    <div class="col-6 border-end px-1">
                        <small class="text-muted d-block" style="font-size: 0.65rem;">En attente</small>
                        <span class="fw-bold text-danger" style="font-size: 0.8rem;">{{ $pendingOrders ?? 0 }}</span>
                    </div>
                    <div class="col-6 ps-2">
                        <small class="text-muted d-block" style="font-size: 0.65rem;">Préparation</small>
                        <span class="fw-bold text-warning" style="font-size: 0.8rem;">{{ $preparingOrders ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
        <div class="card border-0 shadow-sm border-start border-success border-4 h-100">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div>
                        <h6 class="text-muted mb-0 small">Terminées / Autres</h6>
                        <h4 class="mb-0 text-success">{{ ($deliveredOrders ?? 0) + ($paidOrders ?? 0) + ($cancelledOrders ?? 0) }}</h4>
                    </div>
                    <div class="bg-success bg-opacity-10 p-2 rounded">
                        <i class="fas fa-check-double text-success"></i>
                    </div>
                </div>
                <hr class="my-1 opacity-25">
                <div class="row g-0">
                    <div class="col-4 border-end px-1 text-center">
                        <small class="text-muted d-block" style="font-size: 0.65rem;">Livrées</small>
                        <span class="fw-bold text-success" style="font-size: 0.8rem;">{{ $deliveredOrders ?? 0 }}</span>
                    </div>
                    <div class="col-4 border-end px-1 text-center">
                        <small class="text-muted d-block" style="font-size: 0.65rem;">Payées</small>
                        <span class="fw-bold text-primary" style="font-size: 0.8rem;">{{ $paidOrders ?? 0 }}</span>
                    </div>
                    <div class="col-4 px-1 text-center">
                        <small class="text-muted d-block" style="font-size: 0.65rem;">Annulées</small>
                        <span class="fw-bold text-secondary" style="font-size: 0.8rem;">{{ $cancelledOrders ?? 0 }}</span>
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
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>En préparation</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Livré</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Payé</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date de</label>
                    <input type="date" class="form-control" id="dateFrom" value="{{ request('from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date à</label>
                    <input type="date" class="form-control" id="dateTo" value="{{ request('to') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary flex-grow-1" id="applyFilters">
                        <i class="fas fa-filter me-1"></i> Appliquer
                    </button>
                    @if(request()->hasAny(['status','from','to']))
                    <a href="{{ route('restaurant.orders') }}" class="btn btn-outline-secondary" title="Réinitialiser les filtres">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </div>
            @if(request()->hasAny(['status','from','to']))
            <div class="mt-2">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Filtres actifs :
                    @if(request('status'))
                        <span class="badge bg-secondary me-1">Statut: {{ ['pending'=>'En attente','preparing'=>'En préparation','delivered'=>'Livré','paid'=>'Payé','cancelled'=>'Annulé'][request('status')] ?? request('status') }}</span>
                    @endif
                    @if(request('from'))
                        <span class="badge bg-secondary me-1">Du: {{ \Carbon\Carbon::parse(request('from'))->format('d/m/Y') }}</span>
                    @endif
                    @if(request('to'))
                        <span class="badge bg-secondary me-1">Au: {{ \Carbon\Carbon::parse(request('to'))->format('d/m/Y') }}</span>
                    @endif
                </small>
            </div>
            @endif
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
                        <th style="min-width:140px;">Actions</th>
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
                            <div class="d-flex gap-1 flex-nowrap">
                                {{-- Détails --}}
                                <button class="btn btn-sm btn-outline-secondary"
                                        title="Voir les détails"
                                        data-order-id="{{ $order->id }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#orderDetailsModal">
                                    <i class="fas fa-eye"></i>
                                </button>

                                {{-- Préparer (pending → preparing) --}}
                                @if($order->status == 'pending')
                                <button class="btn btn-sm btn-info change-status"
                                        title="Mettre en préparation"
                                        data-order-id="{{ $order->id }}"
                                        data-status="preparing">
                                    <i class="fas fa-play"></i>
                                </button>
                                @endif

                                {{-- Livrer (preparing → delivered) --}}
                                @if($order->status == 'preparing')
                                <button class="btn btn-sm btn-success change-status"
                                        title="Marquer comme livré"
                                        data-order-id="{{ $order->id }}"
                                        data-status="delivered">
                                    <i class="fas fa-check"></i>
                                </button>
                                @endif

                                {{-- Payer (pending ou delivered → paid) --}}
                                @if(in_array($order->status, ['delivered', 'pending']))
                                <button class="btn btn-sm btn-primary change-status"
                                        title="Marquer comme payé"
                                        data-order-id="{{ $order->id }}"
                                        data-status="paid">
                                    <i class="fas fa-money-bill-wave"></i>
                                </button>
                                @endif

                                {{-- Annuler (si pas déjà payé ou annulé) --}}
                                @if(!in_array($order->status, ['paid', 'cancelled']))
                                <button class="btn btn-sm btn-outline-danger cancel-order"
                                        title="Annuler la commande"
                                        data-order-id="{{ $order->id }}">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h4>Aucune commande trouvée</h4>
                            @if(request()->hasAny(['status','from','to']))
                            <p class="text-muted">Aucune commande ne correspond aux filtres sélectionnés.</p>
                            <a href="{{ route('restaurant.orders') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-times me-1"></i> Réinitialiser les filtres
                            </a>
                            @else
                            <p class="text-muted">Aucune commande n'a été passée pour le moment.</p>
                            @endif
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
    <div class="modal-dialog modal-xl">
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

@push('scripts')
<script>
$(document).ready(function() {
    // Configuration Ajax pour envoyer le tag CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // 1. Filtrage au clic sur "Appliquer"
    $('#applyFilters').click(function() {
        const status = $('#statusFilter').val();
        const dateFrom = $('#dateFrom').val();
        const dateTo = $('#dateTo').val();
        
        // Simuler un rechargement en redirigeant ou en filtrant côté client pour l'instant
        // Pour un système complet, il faudrait passer par une requête GET avec ces paramètres
        let url = new URL(window.location.href);
        if(status) url.searchParams.set('status', status);
        else url.searchParams.delete('status');
        
        if(dateFrom) url.searchParams.set('from', dateFrom);
        else url.searchParams.delete('from');
        
        window.location.href = url.toString();
    });

    // Afficher les détails
    $('#orderDetailsModal').on('show.bs.modal', function(e) {
        const button = $(e.relatedTarget);
        const orderId = button.data('order-id');
        
        $('#orderId').text(orderId);
        $('#orderDetailsContent').html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');
        
        $.ajax({
            url: `/restaurant/orders/${orderId}`,
            type: 'GET',
            success: function(response) {
                $('#orderDetailsContent').html(response.html);
            },
            error: function() {
                $('#orderDetailsContent').html('<div class="alert alert-danger">Erreur lors du chargement.</div>');
            }
        });
    });

    // 5. Impression
    $('#printOrder').click(function() {
        const content = document.getElementById('orderDetailsContent').innerHTML;
        const myWindow = window.open('', '', 'width=800,height=600');
        myWindow.document.write('<html><head><title>Impression Commande</title>');
        myWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
        myWindow.document.write('<style>body{padding:20px} .modal-body{padding:0}</style>');
        myWindow.document.write('</head><body>');
        myWindow.document.write(content);
        myWindow.document.write('</body></html>');
        myWindow.document.close();
        
        setTimeout(function() {
            myWindow.print();
        }, 500);
    });
});
</script>
@endpush
