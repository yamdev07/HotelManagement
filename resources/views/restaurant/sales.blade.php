@extends('template.master')
@section('title', 'Restaurant - Suivi des Ventes')
@section('content')

@include('restaurant.partials.nav-tabs')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0">Suivi des Ventes</h3>
        <small class="text-muted">Chiffre d'affaires & analyse des ventes restaurant</small>
    </div>
</div>

<!-- KPIs -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">CA Total</h6>
                        <h3 class="mb-0 text-primary fw-bold">{{ number_format($totalRevenue, 0, ',', ' ') }}</h3>
                        <small class="text-muted">FCFA</small>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="fas fa-chart-line fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">CA Aujourd'hui</h6>
                        <h3 class="mb-0 text-success fw-bold">{{ number_format($todayRevenue, 0, ',', ' ') }}</h3>
                        <small class="text-muted">FCFA</small>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded">
                        <i class="fas fa-calendar-day fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">CA Ce mois</h6>
                        <h3 class="mb-0 text-info fw-bold">{{ number_format($monthRevenue, 0, ',', ' ') }}</h3>
                        <small class="text-muted">FCFA</small>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded">
                        <i class="fas fa-calendar-alt fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Commandes traitées</h6>
                        <h3 class="mb-0 text-warning fw-bold">{{ number_format($totalOrders, 0, ',', ' ') }}</h3>
                        <small class="text-muted">total</small>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                        <i class="fas fa-receipt fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Revenus 7 derniers jours -->
    <div class="col-lg-8 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2 text-primary"></i>Revenus — 7 derniers jours</h5>
            </div>
            <div class="card-body">
                <canvas id="dailyChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <!-- Répartition par catégorie -->
    <div class="col-lg-4 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2 text-info"></i>Par catégorie</h5>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <canvas id="categoryChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Top 10 articles les plus vendus -->
    <div class="col-lg-7 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="mb-0"><i class="fas fa-trophy me-2 text-warning"></i>Top 10 — Plats les plus vendus</h5>
            </div>
            <div class="card-body p-0">
                @if($topItems->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-utensils fa-2x mb-2"></i>
                    <p>Aucune vente enregistrée pour le moment.</p>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Plat</th>
                                <th>Catégorie</th>
                                <th class="text-center">Qté vendue</th>
                                <th class="text-end">CA généré</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topItems as $i => $item)
                            <tr>
                                <td>
                                    @if($i === 0)
                                        <span class="badge bg-warning text-dark"><i class="fas fa-trophy"></i></span>
                                    @elseif($i === 1)
                                        <span class="badge bg-secondary"><i class="fas fa-medal"></i></span>
                                    @elseif($i === 2)
                                        <span class="badge" style="background:#cd7f32"><i class="fas fa-medal"></i></span>
                                    @else
                                        <span class="text-muted">{{ $i + 1 }}</span>
                                    @endif
                                </td>
                                <td><strong>{{ $item->menu->name ?? '—' }}</strong></td>
                                <td>
                                    @if($item->menu)
                                    <span class="badge bg-light text-dark border">{{ ucfirst($item->menu->category) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill">{{ $item->total_qty }}</span>
                                </td>
                                <td class="text-end fw-semibold">
                                    {{ number_format($item->total_revenue, 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Revenus mensuels (12 mois) -->
    <div class="col-lg-5 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent">
                <h5 class="mb-0"><i class="fas fa-chart-area me-2 text-success"></i>Évolution mensuelle (12 mois)</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" height="220"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Données PHP → JS ──────────────────────────────────────────
const dailyData = @json($dailyRevenue);
const monthlyData = @json($monthlyRevenue);
const categoryData = @json($salesByCategory);

// Générer les 7 derniers jours (même si pas de vente)
function last7Days() {
    const labels = [], values = [], orderCounts = [];
    for (let i = 6; i >= 0; i--) {
        const d = new Date();
        d.setDate(d.getDate() - i);
        const key = d.toISOString().slice(0, 10);
        const found = dailyData.find(r => r.date === key);
        labels.push(d.toLocaleDateString('fr-FR', { weekday: 'short', day: '2-digit', month: 'short' }));
        values.push(found ? parseFloat(found.revenue) : 0);
        orderCounts.push(found ? parseInt(found.nb_orders) : 0);
    }
    return { labels, values, orderCounts };
}

// ── Graphique Revenus 7 jours ──────────────────────────────────
const daily = last7Days();
new Chart(document.getElementById('dailyChart'), {
    type: 'bar',
    data: {
        labels: daily.labels,
        datasets: [{
            label: 'Revenus (FCFA)',
            data: daily.values,
            backgroundColor: 'rgba(59,130,246,0.7)',
            borderColor: 'rgba(59,130,246,1)',
            borderWidth: 2,
            borderRadius: 6,
        }, {
            label: 'Nb commandes',
            data: daily.orderCounts,
            type: 'line',
            yAxisID: 'y1',
            borderColor: 'rgba(16,185,129,1)',
            backgroundColor: 'rgba(16,185,129,0.1)',
            tension: 0.4,
            fill: true,
            pointRadius: 5,
        }]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        scales: {
            y:  { beginAtZero: true, title: { display: true, text: 'FCFA' } },
            y1: { beginAtZero: true, position: 'right', title: { display: true, text: 'Commandes' }, grid: { drawOnChartArea: false } },
        },
        plugins: { legend: { position: 'top' } }
    }
});

// ── Graphique par catégorie ────────────────────────────────────
const catLabels = Object.keys(categoryData).map(k => k.charAt(0).toUpperCase() + k.slice(1));
const catValues = Object.values(categoryData).map(v => parseFloat(v.revenue));
const catColors = ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316'];

new Chart(document.getElementById('categoryChart'), {
    type: 'doughnut',
    data: {
        labels: catLabels,
        datasets: [{ data: catValues, backgroundColor: catColors.slice(0, catLabels.length), borderWidth: 2 }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: ctx => ` ${ctx.label}: ${parseInt(ctx.raw).toLocaleString('fr-FR')} FCFA`
                }
            }
        }
    }
});

// ── Graphique mensuel ──────────────────────────────────────────
const frMonths = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
const mLabels = monthlyData.map(r => `${frMonths[r.month - 1]} ${r.year}`);
const mValues = monthlyData.map(r => parseFloat(r.revenue));

new Chart(document.getElementById('monthlyChart'), {
    type: 'line',
    data: {
        labels: mLabels,
        datasets: [{
            label: 'CA mensuel (FCFA)',
            data: mValues,
            borderColor: 'rgba(16,185,129,1)',
            backgroundColor: 'rgba(16,185,129,0.15)',
            tension: 0.4,
            fill: true,
            pointRadius: 5,
            pointBackgroundColor: 'rgba(16,185,129,1)',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, title: { display: true, text: 'FCFA' } }
        }
    }
});
</script>
@endpush
