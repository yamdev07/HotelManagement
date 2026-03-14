@extends('template.master')

@section('title', 'Statistiques Mensuelles - ' . $selectedMonth->format('F Y'))

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    /* ── 4 COULEURS (vert, rouge, gris, blanc) ── */
    --green-50:  #f0faf0;
    --green-100: #d4edda;
    --green-500: #2e8540;
    --green-600: #1e6b2e;
    --green-700: #155221;

    --red-50:    #fee2e2;
    --red-100:   #fecaca;
    --red-500:   #b91c1c;
    --red-600:   #991b1b;

    --gray-50:   #f8f9f8;
    --gray-100:  #eff0ef;
    --gray-200:  #dde0dd;
    --gray-300:  #c2c7c2;
    --gray-400:  #9ba09b;
    --gray-500:  #737873;
    --gray-600:  #545954;
    --gray-700:  #3a3e3a;
    --gray-800:  #252825;
    --gray-900:  #131513;

    --white:     #ffffff;
    --surface:   #f7f9f7;

    --shadow-xs: 0 1px 2px rgba(0,0,0,.04);
    --shadow-sm: 0 1px 6px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow-md: 0 4px 16px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.04);

    --r:   8px;
    --rl:  14px;
    --rxl: 20px;
    --transition: all .2s ease;
    --font: 'DM Sans', system-ui, sans-serif;
    --mono: 'DM Mono', monospace;
}

* { box-sizing: border-box; margin: 0; padding: 0; }

.stats-page {
    background: var(--surface);
    min-height: 100vh;
    padding: 24px 32px;
    font-family: var(--font);
    color: var(--gray-800);
}

/* ── Animations ── */
@keyframes fadeSlide {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
.anim-1 { animation: fadeSlide .4s ease both; }
.anim-2 { animation: fadeSlide .4s .08s ease both; }
.anim-3 { animation: fadeSlide .4s .16s ease both; }

/* ══════════════════════════════════════════════
   BREADCRUMB
══════════════════════════════════════════════ */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .8rem;
    color: var(--gray-400);
    margin-bottom: 20px;
}
.breadcrumb a {
    color: var(--gray-400);
    text-decoration: none;
    transition: var(--transition);
}
.breadcrumb a:hover {
    color: var(--green-600);
}
.breadcrumb .sep {
    color: var(--gray-300);
}
.breadcrumb .current {
    color: var(--gray-600);
    font-weight: 500;
}

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
}
.header-title {
    display: flex;
    align-items: center;
    gap: 12px;
}
.header-icon {
    width: 48px;
    height: 48px;
    background: var(--green-600);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 10px rgba(46,133,64,.3);
}
.header-title h1 {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0;
}
.header-title em {
    font-style: normal;
    color: var(--green-600);
}
.header-subtitle {
    color: var(--gray-500);
    font-size: .8rem;
    margin: 6px 0 0 60px;
}

/* ══════════════════════════════════════════════
   BUTTONS
══════════════════════════════════════════════ */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: var(--r);
    font-size: .8rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}
.btn-green {
    background: var(--green-600);
    color: white;
}
.btn-green:hover {
    background: var(--green-700);
    transform: translateY(-1px);
    color: white;
}
.btn-gray {
    background: var(--white);
    color: var(--gray-600);
    border: 1.5px solid var(--gray-200);
}
.btn-gray:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.btn-outline {
    background: var(--white);
    color: var(--gray-600);
    border: 1.5px solid var(--gray-200);
}
.btn-outline:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.btn-sm {
    padding: 6px 12px;
    font-size: .7rem;
}
.input-group {
    display: flex;
    width: 200px;
}
.input-group .form-control {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}
.input-group .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

/* ══════════════════════════════════════════════
   STATS CARDS
══════════════════════════════════════════════ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.stat-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 20px;
    text-align: center;
    transition: var(--transition);
}
.stat-card:hover {
    border-color: var(--green-300);
    transform: translateY(-2px);
}
.stat-label {
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    margin-bottom: 8px;
}
.stat-value {
    font-size: 2.2rem;
    font-weight: 700;
    font-family: var(--mono);
    color: var(--gray-900);
    line-height: 1;
    margin-bottom: 4px;
}
.stat-value.green { color: var(--green-600); }
.stat-value.blue { color: var(--green-600); }
.stat-value.orange { color: var(--green-600); }
.stat-footer {
    font-size: .7rem;
    color: var(--gray-400);
}

/* ══════════════════════════════════════════════
   CARDS
══════════════════════════════════════════════ */
.card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: var(--shadow-sm);
}
.card-header {
    padding: 14px 20px;
    border-bottom: 1.5px solid var(--gray-200);
    background: var(--green-600);
    color: white;
    display: flex;
    align-items: center;
    gap: 8px;
}
.card-header i {
    color: white;
}
.card-header.green { background: var(--green-600); }
.card-header.blue { background: var(--green-600); }
.card-header.orange { background: var(--green-600); }
.card-header.dark { background: var(--gray-700); color: white; }
.card-body {
    padding: 20px;
}

/* ══════════════════════════════════════════════
   TABLE
══════════════════════════════════════════════ */
.table-responsive {
    overflow-x: auto;
}
.table {
    width: 100%;
    border-collapse: collapse;
}
.table thead th {
    background: var(--gray-50);
    padding: 12px 16px;
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    border-bottom: 1.5px solid var(--gray-200);
    text-align: left;
}
.table tbody td {
    padding: 12px 16px;
    border-bottom: 1px solid var(--gray-200);
    color: var(--gray-700);
    font-size: .8rem;
    vertical-align: middle;
}
.table tbody tr:hover td {
    background: var(--green-50);
}

/* ══════════════════════════════════════════════
   BADGES
══════════════════════════════════════════════ */
.badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 4px 10px;
    border-radius: 100px;
    font-size: .65rem;
    font-weight: 600;
}
.badge-green { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.badge-red { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }
.badge-gray { background: var(--gray-100); color: var(--gray-600); border: 1.5px solid var(--gray-200); }

/* ══════════════════════════════════════════════
   AVATAR
══════════════════════════════════════════════ */
.avatar-sm {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--green-50);
    color: var(--green-600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .7rem;
    font-weight: 600;
}

/* ══════════════════════════════════════════════
   PROGRESS BAR
══════════════════════════════════════════════ */
.progress {
    height: 20px;
    background: var(--gray-100);
    border-radius: 100px;
    overflow: hidden;
}
.progress-bar {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .65rem;
    font-weight: 600;
    color: white;
}
.progress-bar.green { background: var(--green-600); }
.progress-bar.blue { background: var(--green-600); }
.progress-bar.orange { background: var(--green-600); }

/* ══════════════════════════════════════════════
   LIST GROUP
══════════════════════════════════════════════ */
.list-group {
    list-style: none;
    padding: 0;
}
.list-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid var(--gray-200);
}
.list-item:last-child {
    border-bottom: none;
}
.list-item.active {
    background: var(--green-50);
    margin: 0 -20px;
    padding: 12px 20px;
    color: var(--green-700);
}
.list-item.active i {
    color: var(--green-600);
}
.list-link {
    text-decoration: none;
    color: var(--gray-700);
    display: block;
}
.list-link:hover {
    background: var(--green-50);
}

/* ══════════════════════════════════════════════
   SUMMARY TABLE
══════════════════════════════════════════════ */
.summary-table {
    width: 100%;
}
.summary-table td {
    padding: 8px 0;
    border-bottom: 1px solid var(--gray-200);
}
.summary-table td:last-child {
    text-align: right;
    font-weight: 600;
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    padding: 32px 16px;
    text-align: center;
}
.empty-state i {
    font-size: 2rem;
    color: var(--gray-300);
    margin-bottom: 12px;
}
.empty-state p {
    color: var(--gray-400);
    font-size: .75rem;
}

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media (max-width: 1024px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .stats-page { padding: 16px; }
    .stats-grid { grid-template-columns: 1fr; }
    .page-header { flex-direction: column; align-items: flex-start; }
}
</style>

<div class="stats-page">

    {{-- Breadcrumb --}}
    <div class="breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('housekeeping.index') }}">Housekeeping</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('housekeeping.reports') }}">Rapports</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">{{ $selectedMonth->translatedFormat('F Y') }}</span>
    </div>

    {{-- En-tête --}}
    <div class="page-header anim-2">
        <div>
            <div class="header-title">
                <span class="header-icon"><i class="fas fa-chart-line"></i></span>
                <h1>Statistiques <em>mensuelles</em></h1>
            </div>
            <p class="header-subtitle">{{ $selectedMonth->translatedFormat('F Y') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('housekeeping.reports') }}" class="btn btn-gray">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <div class="input-group">
                <input type="month" class="form-control" id="monthSelector" value="{{ $selectedMonth->format('Y-m') }}">
                <button class="btn btn-green" onclick="changeMonth()"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </div>

    {{-- KPI --}}
    <div class="stats-grid anim-3">
        <div class="stat-card">
            <div class="stat-label">Chambres nettoyées</div>
            <div class="stat-value green">{{ $monthlyStats->sum('cleaned_count') }}</div>
            <div class="stat-footer">total mensuel</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Moyenne journalière</div>
            <div class="stat-value blue">{{ round($monthlyStats->avg('cleaned_count')) }}</div>
            <div class="stat-footer">par jour</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Temps moyen</div>
            <div class="stat-value orange">{{ round($monthlyStats->avg('avg_time')) }} min</div>
            <div class="stat-footer">par chambre</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Jours actifs</div>
            <div class="stat-value">{{ $monthlyStats->count() }}</div>
            <div class="stat-footer">jours avec activité</div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Colonne principale --}}
        <div class="col-lg-8">

            {{-- Graphique évolution --}}
            <div class="card">
                <div class="card-header green">
                    <i class="fas fa-chart-area"></i> Évolution quotidienne
                </div>
                <div class="card-body">
                    <canvas id="dailyEvolutionChart" height="300"></canvas>
                </div>
            </div>

            {{-- Top femmes de chambre --}}
            <div class="card">
                <div class="card-header green">
                    <i class="fas fa-trophy"></i> Top 10 - Femmes de chambre
                </div>
                <div class="card-body p-0">
                    @if($topCleaners->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Agent</th>
                                        <th>Chambres</th>
                                        <th>Moy/jour</th>
                                        <th>Performance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topCleaners as $index => $c)
                                    <tr>
                                        <td>
                                            @if($index == 0)<span class="badge badge-green">1</span>
                                            @elseif($index == 1)<span class="badge badge-green">2</span>
                                            @elseif($index == 2)<span class="badge badge-green">3</span>
                                            @else<span class="text-muted">{{ $index+1 }}</span>@endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-sm">{{ substr($c['name'], 0, 1) }}</div>
                                                <span>{{ $c['name'] }}</span>
                                            </div>
                                        </td>
                                        <td><span class="fw-bold">{{ $c['count'] }}</span></td>
                                        <td><span class="badge badge-green">{{ round($c['count'] / $monthlyStats->count()) }}</span></td>
                                        <td style="width:200px;">
                                            @php $pct = ($c['count'] / $monthlyStats->sum('cleaned_count')) * 100; @endphp
                                            <div class="progress">
                                                <div class="progress-bar green" style="width:{{ min(100, $pct*2) }}%">{{ round($pct,1) }}%</div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state"><i class="fas fa-users-slash"></i><p>Aucune donnée</p></div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Colonne latérale --}}
        <div class="col-lg-4">

            {{-- Chambres les plus actives --}}
            <div class="card">
                <div class="card-header blue">
                    <i class="fas fa-star"></i> Chambres les plus actives
                </div>
                <div class="card-body p-0">
                    @if($mostCleanedRooms->count() > 0)
                        <div class="list-group">
                            @foreach($mostCleanedRooms as $room)
                            <div class="list-item">
                                <div>
                                    <span class="badge badge-green me-2">#{{ $room->number }}</span>
                                    <small>{{ $room->type }}</small>
                                </div>
                                <span class="badge badge-green">{{ $room->cleaned_count }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state"><i class="fas fa-bed"></i><p>Aucune donnée</p></div>
                    @endif
                </div>
            </div>

            {{-- Répartition par jour --}}
            <div class="card">
                <div class="card-header orange">
                    <i class="fas fa-chart-pie"></i> Activité par jour
                </div>
                <div class="card-body">
                    <canvas id="dayOfWeekChart" height="200"></canvas>
                </div>
            </div>

            {{-- Historique des mois --}}
            <div class="card">
                <div class="card-header dark">
                    <i class="fas fa-history"></i> Historique des mois
                </div>
                <div class="card-body p-0">
                    <div class="list-group">
                        @foreach($availableMonths as $month)
                        @php $md = \Carbon\Carbon::createFromFormat('Y-m', $month); @endphp
                        <a href="{{ route('housekeeping.monthly-stats', ['month' => $month]) }}" 
                           class="list-link {{ $selectedMonth->format('Y-m') == $month ? 'active' : '' }}">
                            <div class="list-item">
                                <div><i class="fas fa-calendar me-2"></i> {{ $md->translatedFormat('F Y') }}</div>
                                <span class="badge badge-green"><i class="fas fa-arrow-right"></i></span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Résumé détaillé --}}
    <div class="card mt-4">
        <div class="card-header dark">
            <i class="fas fa-file-alt"></i> Résumé mensuel détaillé
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <h6 class="fw-semibold mb-3"><i class="fas fa-chart-bar me-2" style="color:var(--green-600);"></i> Statistiques clés</h6>
                    <table class="summary-table">
                        @php $bestDay = $monthlyStats->sortByDesc('cleaned_count')->first(); @endphp
                        <tr><td>Total chambres nettoyées</td><td>{{ $monthlyStats->sum('cleaned_count') }}</td></tr>
                        <tr><td>Moyenne par jour</td><td>{{ round($monthlyStats->avg('cleaned_count'), 1) }}</td></tr>
                        <tr><td>Meilleur jour</td><td>@if($bestDay){{ \Carbon\Carbon::parse($bestDay->date)->format('d/m') }} ({{ $bestDay->cleaned_count }})@endif</td></tr>
                        <tr><td>Jours sans activité</td><td>{{ $selectedMonth->daysInMonth - $monthlyStats->count() }}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-semibold mb-3"><i class="fas fa-tachometer-alt me-2" style="color:var(--green-600);"></i> Performances</h6>
                    <table class="summary-table">
                        @php
                            $totalMins = $monthlyStats->sum(fn($d) => $d->cleaned_count * ($d->avg_time ?? 30));
                            $hours = round($totalMins/60);
                            $efficiency = $monthlyStats->count() > 0 ? round(($totalMins / ($monthlyStats->count() * 8 * 60)) * 100) : 0;
                        @endphp
                        <tr><td>Temps moyen</td><td>{{ round($monthlyStats->avg('avg_time')) }} minutes</td></tr>
                        <tr><td>Heures totales</td><td>{{ $hours }} heures</td></tr>
                        <tr><td>Efficacité</td><td>{{ min(100, $efficiency) }}%</td></tr>
                        <tr><td>Productivité</td>
                            <td>
                                @if($monthlyStats->avg('cleaned_count') > 15)
                                    <span class="badge badge-green">Élevée</span>
                                @elseif($monthlyStats->avg('cleaned_count') > 10)
                                    <span class="badge badge-green">Moyenne</span>
                                @else
                                    <span class="badge badge-gray">Faible</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="text-center mt-4">
                <button class="btn btn-green" onclick="exportMonthlyReport()">
                    <i class="fas fa-file-excel"></i> Exporter
                </button>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const data = @json($monthlyStats);
    const labels = data.map(d => new Date(d.date).getDate()+'/'+(new Date(d.date).getMonth()+1));
    const cleaned = data.map(d => d.cleaned_count);
    const times = data.map(d => Math.round(d.avg_time) || 0);

    new Chart(document.getElementById('dailyEvolutionChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [
                { label: 'Chambres nettoyées', data: cleaned, borderColor: '#1e6b2e', backgroundColor: 'rgba(30,107,46,0.1)', tension:0.4, yAxisID:'y' },
                { label: 'Temps moyen (min)', data: times, borderColor: '#b91c1c', backgroundColor: 'rgba(185,28,28,0.1)', tension:0.4, yAxisID:'y1' }
            ]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true, position:'left' }, y1: { beginAtZero: true, position:'right', grid:{drawOnChartArea:false} } }
        }
    });

    const days = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];
    const totals = {Lundi:0,Mardi:0,Mercredi:0,Jeudi:0,Vendredi:0,Samedi:0,Dimanche:0};
    data.forEach(d => {
        const day = new Date(d.date).toLocaleDateString('fr-FR', { weekday:'long' });
        totals[day] += d.cleaned_count;
    });

    new Chart(document.getElementById('dayOfWeekChart'), {
        type: 'bar',
        data: {
            labels: days,
            datasets: [{
                label: 'Chambres nettoyées',
                data: days.map(d => totals[d]),
                backgroundColor: ['#1e6b2e','#1e6b2e','#1e6b2e','#1e6b2e','#1e6b2e','#1e6b2e','#1e6b2e']
            }]
        },
        options: { scales: { y: { beginAtZero:true } }, plugins: { legend: { display:false } } }
    });
});

function changeMonth() {
    const m = document.getElementById('monthSelector').value;
    if(m) window.location.href = `{{ route('housekeeping.monthly-stats') }}?month=${m}`;
}

function exportMonthlyReport() {
    const w = window.open('', '_blank');
    const rows = @json($monthlyStats).map(d => 
        `<tr><td>${new Date(d.date).toLocaleDateString('fr-FR')}</td><td>${d.cleaned_count}</td><td>${Math.round(d.avg_time)||0} min</td></tr>`
    ).join('');
    w.document.write(`
        <html><head><title>Rapport {{ $selectedMonth->translatedFormat('F Y') }}</title>
        <style>body{font-family:sans-serif;margin:20px}h1{color:#1e6b2e}table{border-collapse:collapse;width:100%}th,td{border:1px solid #ddd;padding:8px}th{background:#f2f2f2}</style>
        </head><body>
        <h1>Rapport Mensuel - {{ $selectedMonth->translatedFormat('F Y') }}</h1>
        <p>Généré le ${new Date().toLocaleString()}</p>
        <table><thead><tr><th>Date</th><th>Chambres</th><th>Temps</th></tr></thead><tbody>${rows}</tbody></table>
        </body></html>
    `);
    w.print();
}
</script>

@endsection