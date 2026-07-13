@extends('platform.layout')

@section('title', 'Hôtels')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-bold mb-0"><i class="fas fa-hotel me-2 text-primary"></i>Hôtels de la plateforme</h3>
            <div class="text-muted small">Vue d'ensemble et abonnements</div>
        </div>
        <a href="{{ route('platform.hotels.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nouvel hôtel
        </a>
    </div>

    {{-- ===== Synthèse ===== --}}
    <div class="row g-3 mb-4">
        @php
            $cards = [
                ['Inscriptions ce mois', $summary['this_month'], 'fa-user-plus', '#6366f1', '#eef2ff'],
                ['Revenus totaux', number_format($summary['revenue'], 0, ',', ' ').' F', 'fa-sack-dollar', '#16a34a', '#dcfce7'],
                ['Revenu mensuel', number_format($summary['mrr'], 0, ',', ' ').' F', 'fa-arrow-trend-up', '#0ea5e9', '#e0f2fe'],
                ['Hôtels actifs', $summary['active'].' / '.$summary['total'], 'fa-circle-check', '#7c3aed', '#f3e8ff'],
                ['Réabonnements', $summary['renewals'], 'fa-rotate', '#f59e0b', '#fef3c7'],
                ['Expirés / suspendus', $summary['expired'], 'fa-triangle-exclamation', '#ef4444', '#fee2e2'],
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
                <h6 class="fw-semibold mb-0"><i class="fas fa-chart-column me-2 text-primary"></i>Inscriptions — 6 derniers mois</h6>
            </div>
            <canvas id="regChart" height="90"></canvas>
        </div>
    </div>

    {{-- ===== Liste ===== --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list me-2"></i>Tous les hôtels</span>
            <span class="badge bg-light text-dark border">{{ $hotels->count() }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Hôtel</th>
                        <th>Contact</th>
                        <th class="text-center">Statut</th>
                        <th>Formule</th>
                        <th>Abonnement</th>
                        <th class="text-center">Chambres</th>
                        <th>Inscrit</th>
                        <th class="text-end">Actions</th>
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
                                <div><i class="fas fa-envelope text-muted me-1"></i>{{ $hotel->contact_email ?? $hotel->owner?->email ?? '—' }}</div>
                                @if ($hotel->contact_phone)<div class="text-muted"><i class="fas fa-phone me-1"></i>{{ $hotel->contact_phone }}</div>@endif
                            </td>
                            <td class="text-center">
                                @if ($hotel->hasActiveAccess())
                                    <span class="badge bg-success-subtle text-success">Actif</span>
                                @elseif (! $hotel->is_active)
                                    <span class="badge bg-danger-subtle text-danger">Suspendu</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning">Expiré</span>
                                @endif
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $hotel->planName() }}</span></td>
                            <td class="small">
                                @if ($hotel->subscription_ends_at)
                                    <span class="{{ $hotel->isSubscriptionExpired() ? 'text-danger fw-semibold' : 'text-muted' }}">{{ $hotel->subscription_ends_at->format('d/m/Y') }}</span>
                                @else
                                    <span class="text-muted">illimité</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $hotel->rooms_count }}</td>
                            <td class="small text-muted">{{ $hotel->created_at?->format('d/m/Y') }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('platform.hotels.show', $hotel) }}" class="btn btn-sm btn-outline-primary" title="Détails"><i class="fas fa-eye"></i></a>
                                <form action="{{ route('platform.hotels.toggle', $hotel) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="reason" value="">
                                    @if ($hotel->is_active)
                                        <button class="btn btn-sm btn-outline-warning" title="Suspendre"
                                                onclick="var r=prompt('Raison de la suspension de « {{ $hotel->name }} » (visible par l\'hôtelier) :','Non-paiement de l\'abonnement'); if(r===null)return false; this.form.reason.value=r; return true;">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-outline-success" title="Réactiver"><i class="fas fa-check"></i></button>
                                    @endif
                                </form>
                                <form action="{{ route('platform.hotels.destroy', $hotel) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('SUPPRIMER définitivement {{ $hotel->name }} et ses {{ $hotel->users_count }} utilisateur(s) ? Irréversible.')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Aucun hôtel pour le moment.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .stat-card { transition: transform .2s, box-shadow .2s; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 18px 40px -24px rgba(15,23,42,.4) !important; }
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
                    backgroundColor: '#6366f1',
                    borderRadius: 8,
                    maxBarThickness: 46,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
@endsection
