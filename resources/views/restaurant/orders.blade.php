@extends('template.master')
@section('title', 'Restaurant - Commandes')
@section('content')

@include('restaurant.partials.nav-tabs')

<div class="db-page">
    <div class="db-header anim-1">
        <div>
            <h1 class="db-title-h1">Gestion des Commandes</h1>
            <p class="text-muted small">Suivez et traitez les commandes des clients en temps réel</p>
        </div>
        <button class="btn-db-primary" data-bs-toggle="modal" data-bs-target="#newOrderModal">
            <i class="fas fa-plus"></i> Nouvelle Commande
        </button>
    </div>

    <!-- Statistiques du Jour -->
    <div class="kpi-grid anim-2">
        <div class="kpi-card">
            <div class="kpi-icon" style="background: var(--g50); color: var(--g600);"><i class="fas fa-coins"></i></div>
            <div class="kpi-data">
                <div class="kpi-label">CA DU JOUR</div>
                <div class="kpi-value text-success">{{ number_format($todayRevenue ?? 0, 0, ',', ' ') }}</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon" style="background: #eff6ff; color: #2563eb;"><i class="fas fa-receipt"></i></div>
            <div class="kpi-data">
                <div class="kpi-label">COMMANDES (AUJ.)</div>
                <div class="kpi-value text-primary">{{ $todayOrdersTotal ?? 0 }}</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon" style="background: #fff1f2; color: #e11d48;"><i class="fas fa-fire"></i></div>
            <div class="kpi-data">
                <div class="kpi-label">EN ATTENTE / PRÉP.</div>
                <div class="kpi-value text-danger">{{ ($pendingOrders ?? 0) + ($preparingOrders ?? 0) }}</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon" style="background: #f0fdf4; color: #10b981;"><i class="fas fa-check-double"></i></div>
            <div class="kpi-data">
                <div class="kpi-label">LIVRÉES / PAYÉES</div>
                <div class="kpi-value text-success">{{ ($deliveredOrders ?? 0) + ($paidOrders ?? 0) }}</div>
            </div>
        </div>
    </div>

    <div class="db-card anim-3">
        <!-- Filtres -->
        <div class="filter-row mb-4">
            <select class="db-input" id="statusFilter" style="width: 200px;">
                <option value="">Tous les statuts</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>En préparation</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Livré</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Payé</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
            </select>
            <input type="date" class="db-input" id="dateFrom" value="{{ request('from') }}" title="Du">
            <input type="date" class="db-input" id="dateTo" value="{{ request('to') }}" title="Au">
            <button class="btn-db-primary" id="applyFilters" style="height:42px; padding:0 24px;">
                <i class="fas fa-filter"></i> Filtrer
            </button>
            @if(request()->hasAny(['status','from','to']))
                <a href="{{ route('restaurant.orders') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center" style="height:42px; width:42px; border-radius:10px;">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </div>

        <!-- Table des commandes -->
        <div class="table-responsive">
            <table class="db-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Chambre</th>
                        <th class="text-center">Menus</th>
                        <th class="text-end">Total</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr data-status="{{ $order->status }}">
                        <td class="fw-bold">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold">{{ $order->customer_name ?? 'Client' }}</span>
                                <small class="text-muted">{{ $order->customer_phone ?? '' }}</small>
                            </div>
                        </td>
                        <td>
                            @if($order->room_number)
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                    <i class="fas fa-door-open me-1"></i> {{ $order->room_number }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-light border px-2 py-1 view-items" 
                                    data-order-id="{{ $order->id }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#orderDetailsModal">
                                <i class="fas fa-shopping-basket me-1"></i> {{ $order->items_count ?? 0 }}
                            </button>
                        </td>
                        <td class="text-end fw-bold text-primary">
                            {{ number_format($order->total, 0, ',', ' ') }} CFA
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-warning',
                                    'preparing' => 'bg-info',
                                    'delivered' => 'bg-success',
                                    'paid' => 'bg-primary',
                                    'cancelled' => 'bg-danger'
                                ];
                                $statusLabels = [
                                    'pending' => 'En attente',
                                    'preparing' => 'Préparation',
                                    'delivered' => 'Livré',
                                    'paid' => 'Payé',
                                    'cancelled' => 'Annulé'
                                ];
                            @endphp
                            <span class="badge {{ $statusColors[$order->status] ?? 'bg-secondary' }} px-2 py-1">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex flex-column" style="font-size: 0.75rem;">
                                <span>{{ $order->created_at->format('d/m/Y') }}</span>
                                <span class="text-muted">{{ $order->created_at->format('H:i') }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-2">
                                {{-- Détails --}}
                                <button class="btn btn-sm btn-light border p-2"
                                        title="Voir"
                                        data-order-id="{{ $order->id }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#orderDetailsModal">
                                    <i class="fas fa-eye"></i>
                                </button>

                                {{-- Préparer (pending → preparing) --}}
                                @if($order->status == 'pending')
                                <button class="btn btn-sm btn-info text-white p-2 change-status"
                                        title="Lancer la préparation"
                                        data-order-id="{{ $order->id }}"
                                        data-status="preparing">
                                    <i class="fas fa-play"></i>
                                </button>
                                @endif

                                {{-- Livrer (preparing → delivered) --}}
                                @if($order->status == 'preparing')
                                <button class="btn btn-sm btn-success text-white p-2 change-status"
                                        title="Marquer comme livré"
                                        data-order-id="{{ $order->id }}"
                                        data-status="delivered">
                                    <i class="fas fa-truck"></i>
                                </button>
                                @endif

                                {{-- Payer (si paiement direct) --}}
                                @if(in_array($order->status, ['delivered', 'pending']) && $order->payment_method !== 'room_charge')
                                <button class="btn btn-sm btn-primary text-white p-2 change-status"
                                        title="Encaisser la commande"
                                        data-order-id="{{ $order->id }}"
                                        data-status="paid">
                                    <i class="fas fa-cash-register"></i>
                                </button>
                                @endif
                                
                                @if($order->payment_method === 'room_charge')
                                <span class="badge bg-light text-primary border border-primary border-opacity-25 d-flex align-items-center" title="Sur facture chambre">
                                    <i class="fas fa-hotel"></i>
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune commande</h5>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Détails -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden; border: none; box-shadow: var(--shadow-lg);">
            <div class="modal-header" style="background: var(--g700); color: white; border: none;">
                <h5 class="modal-title fw-bold">Détails de la commande #<span id="orderId"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="orderDetailsContent">
                    <!-- Dynamic -->
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-outline-secondary fw-bold" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary fw-bold" id="printOrder">
                    <i class="fas fa-print me-1"></i> Imprimer le ticket
                </button>
            </div>
        </div>
    </div>
</div>

@include('restaurant.partials.new-order-modal')

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    $('#applyFilters').click(function() {
        let url = new URL(window.location.href);
        const status = $('#statusFilter').val();
        const from = $('#dateFrom').val();
        const to = $('#dateTo').val();
        if(status) url.searchParams.set('status', status); else url.searchParams.delete('status');
        if(from) url.searchParams.set('from', from); else url.searchParams.delete('from');
        if(to) url.searchParams.set('to', to); else url.searchParams.delete('to');
        window.location.href = url.toString();
    });

    $(document).on('click', '.change-status', function() {
        const orderId = $(this).data('order-id');
        const status = $(this).data('status');
        $.post(`/restaurant/orders/${orderId}/status`, { status }, function() {
            location.reload();
        }).fail(function(xhr) {
            Swal.fire({ icon: 'warning', title: 'Erreur', text: xhr.responseJSON?.message || 'Erreur inconnue' });
        });
    });

    $('#orderDetailsModal').on('show.bs.modal', function(e) {
        const orderId = $(e.relatedTarget).data('order-id');
        $('#orderId').text(orderId);
        $('#orderDetailsContent').html('<div class="text-center py-5"><div class="spinner-border text-success"></div></div>');
        $.get(`/restaurant/orders/${orderId}`, function(res) {
            $('#orderDetailsContent').html(res.html);
        });
    });

    $('#printOrder').click(function() {
        const content = document.getElementById('orderDetailsContent').innerHTML;
        const win = window.open('', '', 'width=800,height=600');
        win.document.write('<html><head><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"></head><body>'+content+'</body></html>');
        win.document.close();
        setTimeout(() => { win.print(); win.close(); }, 500);
    });
});
</script>
@endpush
