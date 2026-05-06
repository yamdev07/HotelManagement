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
    <style>
    .qr-menu-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 16px;
        padding: 20px 24px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .qr-menu-card::before {
        content: ''; position: absolute; top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(16,185,129,0.1) 0%, transparent 70%);
        z-index: 0;
    }
    .qr-info { position: relative; z-index: 1; flex: 1; }
    .qr-info h3 { font-size: 1.15rem; margin-bottom: 8px; font-weight: 700; color: #fff; }
    .qr-info p { font-size: 0.8rem; color: #94a3b8; margin-bottom: 12px; line-height: 1.4; }
    .qr-url { 
        font-family: monospace; font-size: 0.7rem; background: rgba(255,255,255,0.05); 
        padding: 6px 12px; border-radius: 6px; color: #10b981; width: fit-content;
        border: 1px solid rgba(16,185,129,0.2);
    }
    .badge-qr {
        background: #10b981; color: #fff; font-size: 0.65rem; padding: 2px 8px;
        border-radius: 50px; font-weight: 700; margin-left: 8px; vertical-align: middle;
    }
    .qr-image-wrap {
        background: #fff; padding: 10px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        position: relative; z-index: 1;
    }
    .qr-image-wrap img { width: 100px; height: 100px; }
    
    @media (max-width: 600px) {
        .qr-menu-card { flex-direction: column; text-align: center; }
        .qr-url { margin: 0 auto; }
    }
    </style>

    <div class="qr-menu-card anim-1">
        <div class="qr-info">
            <h3>Menu Digital Restaurant <span class="badge-qr">FLASH ORDER</span></h3>
            <p>Ce QR Code permet aux clients de scanner et commander directement via leur mobile ou une tablette de table.</p>
            <div class="qr-url">{{ rtrim(config('app.url'), '/') . '/menu' }}</div>
        </div>
        <div class="qr-image-wrap">
            @php
                $qrUrl = rtrim(config('app.url'), '/') . '/menu';
            @endphp
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($qrUrl) }}" alt="QR Code Menu">
        </div>
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

                                {{-- Imprimer la facture --}}
                                <a href="{{ route('restaurant.orders.invoice', $order->id) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-secondary p-2"
                                   title="Imprimer la facture">
                                    <i class="fas fa-print"></i>
                                </a>
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

{{-- Zone d'impression facture (masquée à l'écran) --}}
<div id="invoice-print-area" style="display:none;"></div>

@include('restaurant.partials.new-order-modal')

@endsection

@push('scripts')
<script>
// ── Bouton Imprimer en Vanilla JS pur (indépendant de jQuery) ──
document.addEventListener('DOMContentLoaded', function () {
    var printBtn = document.getElementById('printOrder');
    if (printBtn) {
        printBtn.addEventListener('click', function () {
            var orderIdEl = document.getElementById('orderId');
            var orderId = orderIdEl ? orderIdEl.textContent.trim() : '';
            if (orderId) {
                window.open('/restaurant/orders/' + orderId + '/invoice', '_blank', 'width=820,height=950');
            }
        });
    }
});

// ── Reste du script (attend jQuery comme le partial) ──
function initOrdersPage() {
    if (!window.$) { setTimeout(initOrdersPage, 50); return; }

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    $('#applyFilters').click(function() {
        let url = new URL(window.location.href);
        const status = $('#statusFilter').val();
        const from   = $('#dateFrom').val();
        const to     = $('#dateTo').val();
        if (status) url.searchParams.set('status', status); else url.searchParams.delete('status');
        if (from)   url.searchParams.set('from', from);     else url.searchParams.delete('from');
        if (to)     url.searchParams.set('to', to);         else url.searchParams.delete('to');
        window.location.href = url.toString();
    });

    $(document).on('click', '.change-status', function() {
        const orderId = $(this).data('order-id');
        const status  = $(this).data('status');
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
}
initOrdersPage();
</script>
@endpush


