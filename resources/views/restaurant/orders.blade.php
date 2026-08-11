@extends('template.master')
@section('title', __('restaurant.orders.page_title'))
@section('content')

@include('restaurant.partials.nav-tabs')

<div class="db-page">
    <div class="db-header anim-1">
        <div>
            <h1 class="db-title-h1">{{ __('restaurant.orders.header') }}</h1>
            <p class="text-muted small">{{ __('restaurant.orders.header_desc') }}</p>
        </div>
        @if(!auth()->user()->isCuisiner())
        <button class="btn-db-primary" data-bs-toggle="modal" data-bs-target="#newOrderModal">
            <i class="fas fa-plus"></i> {{ __('restaurant.orders.new_order') }}
        </button>
        @endif
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
        background: radial-gradient(circle, rgb(from var(--g500) r g b / 0.1) 0%, transparent 70%);
        z-index: 0;
    }
    .qr-info { position: relative; z-index: 1; flex: 1; }
    .qr-info h3 { font-size: 1.15rem; margin-bottom: 8px; font-weight: 700; color: #fff; }
    .qr-info p { font-size: 0.8rem; color: #94a3b8; margin-bottom: 12px; line-height: 1.4; }
    .qr-url { 
        font-family: monospace; font-size: 0.7rem; background: var(--white, #fff); 
        padding: 6px 12px; border-radius: 6px; color: var(--g500); width: fit-content;
        border: 1px solid rgb(from var(--g500) r g b / 0.2);
    }
    .badge-qr {
        background: var(--g500); color: #fff; font-size: 0.65rem; padding: 2px 8px;
        border-radius: 50px; font-weight: 700; margin-left: 8px; vertical-align: middle;
    }
    .qr-download-container {
        display: flex; flex-direction: column; align-items: center; gap: 15px;
    }
    .qr-image-wrap {
        background: var(--white, #fff); padding: 15px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        position: relative; z-index: 1;
    }
    .qr-image-wrap img { width: 100px; height: 100px; }
    .btn-download-qr {
        background: var(--g500); color: #fff; border: none;
        padding: 12px 24px; border-radius: 8px; font-size: 0.9rem;
        font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;
        transition: background 0.2s, transform 0.1s; white-space: nowrap;
        z-index: 10; position: relative; box-shadow: 0 2px 8px rgb(from var(--g500) r g b / 0.3);
    }
    .btn-download-qr:hover { background: var(--g600); transform: translateY(-1px); }
    .btn-download-qr:active { transform: translateY(0); }
    
    @media (max-width: 600px) {
        .qr-menu-card { flex-direction: column; text-align: center; }
        .qr-url { margin: 0 auto; }
    }
    </style>

    <div class="qr-menu-card anim-1">
        <div class="qr-info">
            <h3>{{ __('restaurant.orders.qr_title') }} <span class="badge-qr">FLASH ORDER</span></h3>
            <p>{{ __('restaurant.orders.qr_desc') }}</p>
            <div class="qr-url">{{ rtrim(config('app.url'), '/') . '/menu' }}</div>
        </div>
        <div class="qr-download-container">
            <div class="qr-image-wrap">
                @php
                    $qrUrl = rtrim(config('app.url'), '/') . '/menu';
                @endphp
                <img id="qrCodeImage" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($qrUrl) }}" alt="QR Code Menu">
            </div>
            <button class="btn-download-qr" onclick="downloadQRCode()">
                <i class="fas fa-download"></i> {{ __('restaurant.orders.download') }}
            </button>
        </div>
    </div>

    <!-- Statistiques du Jour -->
    <div class="kpi-grid anim-2">
        <div class="kpi-card">
            <div class="kpi-icon" style="background: var(--g50); color: var(--g600);"><i class="fas fa-coins"></i></div>
            <div class="kpi-data">
                <div class="kpi-label">{{ __('restaurant.orders.kpi_revenue') }}</div>
                <div class="kpi-value text-success">{{ number_format($todayRevenue ?? 0, 0, ',', ' ') }}</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon" style="background: #eff6ff; color: #2563eb;"><i class="fas fa-receipt"></i></div>
            <div class="kpi-data">
                <div class="kpi-label">{{ __('restaurant.orders.kpi_orders') }}</div>
                <div class="kpi-value text-primary">{{ $todayOrdersTotal ?? 0 }}</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon" style="background: #fff1f2; color: #e11d48;"><i class="fas fa-fire"></i></div>
            <div class="kpi-data">
                <div class="kpi-label">{{ __('restaurant.orders.kpi_pending') }}</div>
                <div class="kpi-value text-danger">{{ ($pendingOrders ?? 0) + ($validatedOrders ?? 0) + ($preparingOrders ?? 0) }}</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon" style="background: #f0fdf4; color: var(--g500);"><i class="fas fa-hat-chef"></i></div>
            <div class="kpi-data">
                <div class="kpi-label">{{ __('restaurant.orders.kpi_ready') }}</div>
                <div class="kpi-value text-success">{{ $readyOrders ?? 0 }}</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon" style="background: #fdf4ff; color: #a855f7;"><i class="fas fa-check-double"></i></div>
            <div class="kpi-data">
                <div class="kpi-label">{{ __('restaurant.orders.kpi_delivered') }}</div>
                <div class="kpi-value text-purple">{{ ($deliveredOrders ?? 0) + ($paidOrders ?? 0) }}</div>
            </div>
        </div>
    </div>

    <div class="db-card anim-3">
        <!-- Filtres -->
        <div class="filter-row mb-4">
            <select class="db-input" id="statusFilter" style="width: 200px;">
                <option value="">{{ __('restaurant.orders.filter_all') }}</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('restaurant.orders.filter_pending') }}</option>
                <option value="validated" {{ request('status') == 'validated' ? 'selected' : '' }}>{{ __('restaurant.orders.filter_validated') }}</option>
                <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>{{ __('restaurant.orders.filter_preparing') }}</option>
                <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>{{ __('restaurant.orders.filter_ready') }}</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>{{ __('restaurant.orders.filter_delivered') }}</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>{{ __('restaurant.orders.filter_paid') }}</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('restaurant.orders.filter_cancelled') }}</option>
            </select>
            <input type="date" class="db-input" id="dateFrom" value="{{ request('from') }}" title="Du">
            <input type="date" class="db-input" id="dateTo" value="{{ request('to') }}" title="Au">
            <button class="btn-db-primary" id="applyFilters" style="height:42px; padding:0 24px;">
                <i class="fas fa-filter"></i> {{ __('restaurant.orders.filter_btn') }}
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
                        <th>{{ __('restaurant.orders.th_client') }}</th>
                        <th>{{ __('restaurant.orders.th_room') }}</th>
                        <th class="text-center">{{ __('restaurant.orders.th_menus') }}</th>
                        <th class="text-end">{{ __('restaurant.orders.th_total') }}</th>
                        <th>{{ __('restaurant.orders.th_status') }}</th>
                        <th>{{ __('restaurant.orders.th_date') }}</th>
                        <th class="text-end">{{ __('restaurant.orders.th_actions') }}</th>
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
                                $statusLabels = [
                                    'pending' => __('restaurant.orders.filter_pending'),
                                    'validated' => __('restaurant.orders.filter_validated'),
                                    'preparing' => __('restaurant.orders.filter_preparing'),
                                    'ready' => __('restaurant.orders.filter_ready'),
                                    'delivered' => __('restaurant.orders.filter_delivered'),
                                    'paid' => __('restaurant.orders.filter_paid'),
                                    'cancelled' => __('restaurant.orders.filter_cancelled')
                                ];
                                $statusColors = [
                                    'pending' => 'bg-warning',
                                    'validated' => 'bg-dark',
                                    'preparing' => 'bg-info',
                                    'ready' => 'bg-success',
                                    'delivered' => 'bg-primary',
                                    'paid' => 'bg-secondary',
                                    'cancelled' => 'bg-danger'
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
                                        title="{{ __('restaurant.orders.view') }}"
                                        data-order-id="{{ $order->id }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#orderDetailsModal">
                                    <i class="fas fa-eye"></i>
                                </button>

                                {{-- Valider (pending → validated) par le Servant ou Admin --}}
                                @if($order->status == 'pending' && (auth()->user()->isSuper() || auth()->user()->role === 'Servant'))
                                <button class="btn btn-sm btn-dark text-white p-2 change-status"
                                        title="{{ __('restaurant.orders.validate') }}"
                                        data-order-id="{{ $order->id }}"
                                        data-status="validated">
                                    <i class="fas fa-check-double"></i>
                                </button>
                                @endif

                                {{-- Préparer (validated → preparing) --}}
                                @if($order->status == 'validated' && (auth()->user()->isSuper() || auth()->user()->isCuisiner()))
                                <button class="btn btn-sm btn-info text-white p-2 change-status"
                                        title="{{ __('restaurant.orders.start_prep') }}"
                                        data-order-id="{{ $order->id }}"
                                        data-status="preparing">
                                    <i class="fas fa-play"></i>
                                </button>
                                @endif

                                {{-- Prêt (preparing → ready) --}}
                                @if($order->status == 'preparing' && (auth()->user()->isSuper() || auth()->user()->isCuisiner()))
                                <button class="btn btn-sm btn-success text-white p-2 change-status"
                                        title="{{ __('restaurant.orders.mark_ready') }}"
                                        data-order-id="{{ $order->id }}"
                                        data-status="ready">
                                    <i class="fas fa-check"></i>
                                </button>
                                @endif

                                {{-- Livrer (ready → delivered ou preparing → delivered pour compatibilité) --}}
                                @if(in_array($order->status, ['preparing', 'ready']) && (auth()->user()->isSuper() || auth()->user()->role === 'Servant'))
                                <button class="btn btn-sm btn-primary text-white p-2 change-status"
                                        title="{{ __('restaurant.orders.mark_delivered') }}"
                                        data-order-id="{{ $order->id }}"
                                        data-status="delivered">
                                    <i class="fas fa-truck"></i>
                                </button>
                                @endif

                                {{-- Payer --}}
                                @if(in_array($order->status, ['delivered', 'pending', 'ready']) && $order->payment_method !== 'room_charge' && (auth()->user()->isSuper() || auth()->user()->role === 'Servant'))
                                <button class="btn btn-sm btn-dark text-white p-2 change-status"
                                        title="{{ __('restaurant.orders.collect') }}"
                                        data-order-id="{{ $order->id }}"
                                        data-status="paid">
                                    <i class="fas fa-cash-register"></i>
                                </button>
                                @endif

                                {{-- Impression Facture --}}
                                <a href="{{ route('restaurant.orders.invoice', $order->id) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-secondary p-2"
                                   title="{{ __('restaurant.orders.print_invoice') }}">
                                    <i class="fas fa-print"></i>
                                </a>

                                @if($order->payment_method === 'room_charge')
                                <span class="badge bg-light text-primary border border-primary border-opacity-25 d-flex align-items-center" title="{{ __('restaurant.orders.room_charge') }}">
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
                            <h5 class="text-muted">{{ __('restaurant.orders.no_orders') }}</h5>
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
                <h5 class="modal-title fw-bold">{!! __('restaurant.orders.details_title') !!} #<span id="orderId"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="orderDetailsContent">
                    <!-- Dynamic -->
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-outline-secondary fw-bold" data-bs-dismiss="modal">{{ __('restaurant.orders.close') }}</button>
                <button type="button" class="btn btn-primary fw-bold" id="printOrder">
                    <i class="fas fa-print me-1"></i> {!! __('restaurant.orders.print_ticket') !!}
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
// ── Télécharger le QR Code avec logo et nom ──
function downloadQRCode() {
    var qrImg = document.getElementById('qrCodeImage');
    if (!qrImg) return;

    var canvas = document.createElement('canvas');
    var ctx = canvas.getContext('2d');

    // Dimensions de l'image finale
    var width = 400;
    var height = 500;
    canvas.width = width;
    canvas.height = height;

    // Fond dégradé vert
    var gradient = ctx.createLinearGradient(0, 0, width, height);
    gradient.addColorStop(0, '#0F2918');
    gradient.addColorStop(1, '#1A472A');
    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, width, height);

    // Bordure dorée
    ctx.strokeStyle = '#C9A961';
    ctx.lineWidth = 4;
    ctx.strokeRect(10, 10, width - 20, height - 20);

    // Charger le logo
    var logoImg = new Image();
    logoImg.crossOrigin = 'anonymous';
    logoImg.src = '{{ asset('img/logo_cactus.png') }}';

    logoImg.onload = function() {
        // Dessiner le logo
        var logoWidth = 80;
        var logoHeight = 80;
        var logoX = (width - logoWidth) / 2;
        var logoY = 30;
        ctx.drawImage(logoImg, logoX, logoY, logoWidth, logoHeight);

        // Nom de l'hôtel
        ctx.fillStyle = '#FFFFFF';
        ctx.font = 'bold 24px Playfair Display, serif';
        ctx.textAlign = 'center';
        ctx.fillText('LE CACTUS HOTEL', width / 2, logoY + logoHeight + 35);

        // Sous-titre
        ctx.fillStyle = '#C9A961';
        ctx.font = 'bold 16px sans-serif';
        ctx.letterSpacing = '2px';
        ctx.fillText('RESTAURANT', width / 2, logoY + logoHeight + 60);

        // Charger et dessiner le QR code
        var qrCodeImg = new Image();
        qrCodeImg.crossOrigin = 'anonymous';
        qrCodeImg.src = qrImg.src;

        qrCodeImg.onload = function() {
            var qrSize = 200;
            var qrX = (width - qrSize) / 2;
            var qrY = logoY + logoHeight + 80;

            // Fond blanc pour le QR code
            ctx.fillStyle = '#FFFFFF';
            ctx.fillRect(qrX - 10, qrY - 10, qrSize + 20, qrSize + 20);

            // Dessiner le QR code
            ctx.drawImage(qrCodeImg, qrX, qrY, qrSize, qrSize);

            // Télécharger l'image
            var link = document.createElement('a');
            link.download = 'qr-menu-restaurant.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        };

        qrCodeImg.onerror = function() {
            // Fallback: ouvrir dans un nouvel onglet
            window.open(qrImg.src, '_blank');
        };
    };

    logoImg.onerror = function() {
        // Fallback: ouvrir dans un nouvel onglet
        window.open(qrImg.src, '_blank');
    };
}

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

    // Rafraîchissement doux (issue #217 : la page se rechargeait toutes les 30 s
    // et interrompait le cuisinier). On ne recharge que si l'onglet est visible ET
    // qu'aucune fenêtre modale n'est ouverte, et moins souvent.
    setInterval(function() {
        if (document.hidden) return;
        if (document.querySelector('.modal.show')) return;
        location.reload();
    }, 60000);
}
initOrdersPage();
</script>
@endpush


