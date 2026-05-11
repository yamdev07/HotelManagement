@extends('template.master')
@section('title', 'Restaurant - Suivi des Ventes')
@section('content')

@include('restaurant.partials.nav-tabs')

<div class="db-page">
    <div class="db-header anim-1">
        <div>
            <h1 class="db-title-h1">Suivi des Ventes</h1>
            <p class="text-muted small">Analysez les performances et le chiffre d'affaires du restaurant</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary d-flex align-items-center gap-2" onclick="window.print()" style="border-radius: 10px; font-weight: 600;">
                <i class="fas fa-print"></i> Imprimer le rapport
            </button>
        </div>
    </div>

    <!-- KPIs -->
    <div class="kpi-grid anim-2">
        <div class="kpi-card">
            <div class="kpi-icon" style="background: var(--g50); color: var(--g600);"><i class="fas fa-chart-line"></i></div>
            <div class="kpi-data">
                <div class="kpi-label">CHIFFRE D'AFFAIRES TOTAL</div>
                <div class="kpi-value">{{ number_format($totalRevenue, 0, ',', ' ') }} <small style="font-size: .6em; color: var(--s400);">CFA</small></div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon" style="background: #f0fdf4; color: #10b981;"><i class="fas fa-calendar-day"></i></div>
            <div class="kpi-data">
                <div class="kpi-label">REVENUS AUJOURD'HUI</div>
                <div class="kpi-value text-success">{{ number_format($todayRevenue, 0, ',', ' ') }}</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon" style="background: #eff6ff; color: #2563eb;"><i class="fas fa-calendar-alt"></i></div>
            <div class="kpi-data">
                <div class="kpi-label">CHIFFRE DU MOIS</div>
                <div class="kpi-value text-primary">{{ number_format($monthRevenue, 0, ',', ' ') }}</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon" style="background: #fffbeb; color: #d97706;"><i class="fas fa-receipt"></i></div>
            <div class="kpi-data">
                <div class="kpi-label">TOTAL COMMANDES</div>
                <div class="kpi-value text-warning">{{ number_format($totalOrders, 0, ',', ' ') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Revenus 7 derniers jours -->
        <div class="col-lg-8 mb-4">
            <div class="db-card h-100 anim-3">
                <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                    <i class="fas fa-chart-bar text-primary"></i> Revenus — 7 derniers jours
                </h5>
                <div style="height: 300px;">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Répartition par catégorie -->
        <div class="col-lg-4 mb-4">
            <div class="db-card h-100 anim-3" style="animation-delay: 0.3s;">
                <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                    <i class="fas fa-chart-pie text-info"></i> Par catégorie
                </h5>
                <div class="d-flex align-items-center justify-content-center" style="height: 300px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top 10 Plats -->
        <div class="col-lg-7 mb-4">
            <div class="db-card anim-3" style="animation-delay: 0.4s;">
                <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                    <i class="fas fa-trophy text-warning"></i> Top 10 — Plats les plus vendus
                </h5>
                <div class="table-responsive">
                    <table class="db-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Désignation du plat</th>
                                <th>Catégorie</th>
                                <th class="text-center">Ventes</th>
                                <th class="text-end">Recettes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topItems as $i => $item)
                            <tr>
                                <td class="text-center">
                                    @if($i === 0)
                                        <div class="bg-warning bg-opacity-10 text-warning px-2 py-1 rounded fw-bold" style="font-size: .8rem;">1</div>
                                    @else
                                        <span class="text-muted small">#{{ $i + 1 }}</span>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ $item->menu->name ?? 'Article inconnu' }}</td>
                                <td>
                                    @if($item->menu)
                                        <span class="badge bg-light text-dark border px-2 py-1">{{ ucfirst($item->menu->category) }}</span>
                                    @endif
                                </td>
                                <td class="text-center fw-bold">{{ $item->total_qty }}</td>
                                <td class="text-end fw-bold text-primary">{{ number_format($item->total_revenue, 0, ',', ' ') }} CFA</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Revenus mensuels -->
        <div class="col-lg-5 mb-4">
            <div class="db-card anim-3" style="animation-delay: 0.5s;">
                <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                    <i class="fas fa-chart-area text-success"></i> Évolution mensuelle (12 mois)
                </h5>
                <div style="height: 300px;">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const dailyData = @json($dailyRevenue);
    const monthlyData = @json($monthlyRevenue);
    const categoryData = @json($salesByCategory);

    function last7Days() {
        const labels = [], values = [], orderCounts = [];
        for (let i = 6; i >= 0; i--) {
            const d = new Date(); d.setDate(d.getDate() - i);
            const key = d.toISOString().slice(0, 10);
            const found = dailyData.find(r => r.date === key);
            labels.push(d.toLocaleDateString('fr-FR', { weekday: 'short', day: '2-digit' }));
            values.push(found ? parseFloat(found.revenue) : 0);
            orderCounts.push(found ? parseInt(found.nb_orders) : 0);
        }
        return { labels, values, orderCounts };
    }

    const daily = last7Days();
    new Chart(document.getElementById('dailyChart'), {
        type: 'bar',
        data: {
            labels: daily.labels,
            datasets: [{
                label: 'Revenus (CFA)',
                data: daily.values,
                backgroundColor: '#2e8540',
                borderRadius: 5,
            }, {
                label: 'Commandes',
                data: daily.orderCounts,
                type: 'line',
                borderColor: '#2563eb',
                tension: 0.4,
                pointRadius: 4,
                yAxisID: 'y1',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { display: false } },
                y1: { position: 'right', beginAtZero: true, grid: { display: false } }
            },
            plugins: { legend: { position: 'bottom' } }
        }
    });

    const catLabels = Object.keys(categoryData).map(k => k.charAt(0).toUpperCase() + k.slice(1));
    const catValues = Object.values(categoryData).map(v => parseFloat(v.revenue));
    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: catLabels,
            datasets: [{ data: catValues, backgroundColor: ['#2e8540', '#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'], borderWidth: 0 }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } }, cutout: '70%' }
    });

    const frMonths = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
    const mLabels = monthlyData.map(r => `${frMonths[r.month - 1]} ${r.year}`);
    const mValues = monthlyData.map(r => parseFloat(r.revenue));
    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: mLabels,
            datasets: [{
                label: 'CA mensuel',
                data: mValues,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
    });
</script>
@endpush
