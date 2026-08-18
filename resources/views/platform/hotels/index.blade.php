@extends('platform.layout')

@section('title', __('platform.hotels_title'))

@section('content')
    {{-- ===== Synthèse ===== --}}
    <div class="row g-3 mb-4">
        @php
            $cards = [
                [__('platform.signups_this_month'), $summary['this_month'], 'fa-user-plus', '#a9b0ff', 'rgba(124,131,255,.18)'],
                [__('platform.total_revenue'), number_format($summary['revenue'], 0, ',', ' ').' F', 'fa-sack-dollar', 'var(--g400)', 'rgb(from var(--g400) r g b / .16)'],
                [__('platform.monthly_revenue'), number_format($summary['mrr'], 0, ',', ' ').' F', 'fa-arrow-trend-up', '#38bdf8', 'rgba(56,189,248,.16)'],
                [__('platform.active_hotels'), $summary['active'].' / '.$summary['total'], 'fa-circle-check', '#c4b5fd', 'rgba(176,107,255,.18)'],
                [__('platform.renewals'), $summary['renewals'], 'fa-rotate', '#fbbf24', 'rgba(251,191,36,.16)'],
                [__('platform.expired_suspended'), $summary['expired'], 'fa-triangle-exclamation', '#fb7185', 'rgba(251,113,133,.16)'],
            ];
        @endphp
        @foreach ($cards as [$label, $value, $icon, $c, $bg])
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="stat-ico" style="background:{{ $bg }};color:{{ $c }}"><i class="fas {{ $icon }}"></i></span>
                        </div>
                        <div class="fs-4 fw-bold lh-1">{{ $value }}</div>
                        <div class="text-muted small mt-1">{{ $label }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ===== Graphe des inscriptions ===== --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-semibold mb-0"><i class="fas fa-chart-column me-2 text-primary"></i>{{ __('platform.signups_6_months') }}</h6>
            </div>
            <canvas id="regChart" height="90"></canvas>
        </div>
    </div>

    {{-- ===== Liste ===== --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list me-2 text-primary"></i>{{ __('platform.all_hotels') }}</span>
            <span class="badge bg-light text-dark border">{{ $hotels->count() }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('platform.hotel') }}</th>
                        <th>{{ __('platform.contact') }}</th>
                        <th class="text-center">{{ __('platform.status') }}</th>
                        <th>{{ __('platform.plan_label') }}</th>
                        <th>{{ __('platform.subscription_label') }}</th>
                        <th class="text-center">{{ __('platform.rooms_label') }}</th>
                        <th>{{ __('platform.signed_up') }}</th>
                        <th class="text-end">{{ __('platform.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hotels as $hotel)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="hotel-ava" style="background:{{ $hotel->primaryColor() }}">
                                        @if ($hotel->logoUrl())<img src="{{ $hotel->logoUrl() }}" alt="">@else{{ strtoupper(substr($hotel->name,0,1)) }}@endif
                                    </span>
                                    <div>
                                        <a href="{{ route('platform.hotels.show', $hotel) }}" class="fw-semibold text-decoration-none">{{ $hotel->name }}</a>
                                        <div class="small text-muted">{{ $hotel->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="small">
                                <div><i class="fas fa-envelope text-muted me-1"></i>{{ $hotel->contact_email ?? $hotel->owner?->email ?? '·' }}</div>
                                @if ($hotel->contact_phone)<div class="text-muted"><i class="fas fa-phone me-1"></i>{{ $hotel->contact_phone }}</div>@endif
                            </td>
                            <td class="text-center">
                                @if ($hotel->hasActiveAccess())
                                    <span class="badge bg-success-subtle text-success">{{ __('platform.active') }}</span>
                                @elseif (! $hotel->is_active)
                                    <span class="badge bg-danger-subtle text-danger">{{ __('platform.suspended') }}</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning">{{ __('platform.expired') }}</span>
                                @endif
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $hotel->planName() }}</span></td>
                            <td class="small">
                                @if ($hotel->subscription_ends_at)
                                    <span class="{{ $hotel->isSubscriptionExpired() ? 'text-danger fw-semibold' : 'text-muted' }}">{{ $hotel->subscription_ends_at->format('d/m/Y') }}</span>
                                @else
                                    <span class="text-muted">{{ __('platform.unlimited') }}</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $hotel->rooms_count }}</td>
                            <td class="small text-muted">{{ $hotel->created_at?->format('d/m/Y') }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('platform.hotels.show', $hotel) }}" class="btn btn-sm btn-outline-primary" title="{{ __('platform.details') }}"><i class="fas fa-eye"></i></a>
                                <form action="{{ route('platform.hotels.toggle', $hotel) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="reason" value="">
                                    @if ($hotel->is_active)
                                        <button class="btn btn-sm btn-outline-warning" title="{{ __('platform.suspend') }}"
                                                data-hotel="{{ $hotel->name }}"
                                                onclick="return suspendHotelPrompt(this);">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-outline-success" title="{{ __('platform.reactivate') }}"><i class="fas fa-check"></i></button>
                                    @endif
                                </form>
                                <form action="{{ route('platform.hotels.destroy', $hotel) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="{{ __('platform.delete') }}" onclick="return confirm('{{ __('platform.delete_confirm', ['name' => $hotel->name, 'count' => $hotel->users_count]) }}')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">{{ __('platform.no_hotels') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .stat-card { transition: transform .2s, border-color .2s; }
        .stat-card:hover { transform: translateY(-4px); border-color: rgba(124,131,255,.5) !important; }
        .stat-ico { width: 40px; height: 40px; border-radius: 12px; display: grid; place-items: center; font-size: 1.05rem; }
        .hotel-ava { width: 40px; height: 40px; border-radius: 12px; color: #fff; display: grid; place-items: center; font-weight: 700; overflow: hidden; flex-shrink: 0; }
        .hotel-ava img { width: 100%; height: 100%; object-fit: cover; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const data = @json($chart);
        new Chart(document.getElementById('regChart'), {
            type: 'bar',
            data: {
                labels: data.map(d => d.label),
                datasets: [{
                    data: data.map(d => d.count),
                    backgroundColor: 'rgba(124,131,255,.6)',
                    hoverBackgroundColor: '#b06bff',
                    borderRadius: 8,
                    maxBarThickness: 46,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0, color: '#94a1bd' }, grid: { color: 'rgba(255,255,255,.06)' } },
                    x: { ticks: { color: '#94a1bd' }, grid: { display: false } }
                }
            }
        });

        // Demande le motif de suspension via une jolie pop-up (au lieu de prompt()).
        function suspendHotelPrompt(btn) {
            var form = btn.form;
            var name = btn.getAttribute('data-hotel') || '';
            var promptMsg = @json(__('platform.suspension_reason', ['name' => '« X »']));
            promptMsg = promptMsg.replace('« X »', '« ' + name + ' »');
            window.promptAction(promptMsg, function (r) {
                if (r === null) return;
                form.reason.value = r;
                form.submit();
            }, { default: @json(__('platform.non_payment_default')), required: true });
            return false;
        }
    </script>
@endsection
