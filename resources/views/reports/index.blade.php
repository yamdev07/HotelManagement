@extends('template.master')

@section('title', 'Rapports & Analytics')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    /* ── Palette : 3 couleurs uniquement ── */
    /* VERT */
    --g50:  #f0faf0;
    --g100: #d4edda;
    --g200: #a8d5b5;
    --g300: #72bb82;
    --g400: #4a9e5c;
    --g500: #2e8540;
    --g600: #1e6b2e;
    --g700: #155221;
    --g800: #0d3a16;
    --g900: #072210;
    /* BLANC / SURFACE */
    --white:    #ffffff;
    --surface:  #f7f9f7;
    --surface2: #eef3ee;
    /* GRIS */
    --s50:  #f8f9f8;
    --s100: #eff0ef;
    --s200: #dde0dd;
    --s300: #c2c7c2;
    --s400: #9ba09b;
    --s500: #737873;
    --s600: #545954;
    --s700: #3a3e3a;
    --s800: #252825;
    --s900: #131513;

    --shadow-xs: 0 1px 2px rgba(0,0,0,.04);
    --shadow-sm: 0 1px 6px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow-md: 0 4px 16px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.04);
    --shadow-lg: 0 12px 40px rgba(0,0,0,.10), 0 4px 12px rgba(0,0,0,.05);

    --r:   8px;
    --rl:  14px;
    --rxl: 20px;
    --transition: all .2s cubic-bezier(.4,0,.2,1);
    --font: 'DM Sans', system-ui, sans-serif;
    --mono: 'DM Mono', monospace;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.reports-page {
    padding: 28px 32px 64px;
    background: var(--surface);
    min-height: 100vh;
    font-family: var(--font);
    color: var(--s800);
}

/* ── Animations ── */
@keyframes fadeSlide {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes scaleIn {
    from { opacity: 0; transform: scale(.96); }
    to   { opacity: 1; transform: scale(1); }
}
.anim-1 { animation: fadeSlide .4s ease both; }
.anim-2 { animation: fadeSlide .4s .08s ease both; }
.anim-3 { animation: fadeSlide .4s .16s ease both; }
.anim-4 { animation: fadeSlide .4s .24s ease both; }
.anim-5 { animation: fadeSlide .4s .32s ease both; }
.anim-6 { animation: fadeSlide .4s .40s ease both; }

/* ══════════════════════════════════════════════
   BREADCRUMB
══════════════════════════════════════════════ */
.reports-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .8rem; color: var(--s400);
    margin-bottom: 20px;
}
.reports-breadcrumb a {
    color: var(--s400); text-decoration: none;
    transition: var(--transition);
}
.reports-breadcrumb a:hover { color: var(--g600); }
.reports-breadcrumb .sep { color: var(--s300); }
.reports-breadcrumb .current { color: var(--s600); font-weight: 500; }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.reports-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.reports-brand { display: flex; align-items: center; gap: 14px; }
.reports-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.reports-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.reports-header-title em { font-style: normal; color: var(--g600); }
.reports-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.reports-header-sub i { color: var(--g500); }
.reports-header-actions { display: flex; align-items: center; gap: 10px; }

/* ══════════════════════════════════════════════
   BOUTONS
══════════════════════════════════════════════ */
.btn-db {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: var(--r);
    font-size: .8rem; font-weight: 500; border: none;
    cursor: pointer; transition: var(--transition);
    text-decoration: none; white-space: nowrap; line-height: 1;
    font-family: var(--font);
}
.btn-db-primary {
    background: var(--g600); color: white;
    box-shadow: 0 2px 10px rgba(46,133,64,.3);
}
.btn-db-primary:hover {
    background: var(--g700); color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
    text-decoration: none;
}
.btn-db-ghost {
    background: var(--white); color: var(--s600);
    border: 1.5px solid var(--s200);
}
.btn-db-ghost:hover {
    background: var(--s50); border-color: var(--s300);
    color: var(--s900); text-decoration: none;
}
.btn-db-outline-primary {
    background: transparent; color: var(--g600);
    border: 1.5px solid var(--g200);
}
.btn-db-outline-primary:hover {
    background: var(--g50); color: var(--g700);
    border-color: var(--g300); transform: translateY(-1px);
}

/* ══════════════════════════════════════════════
   FILTER CARD
══════════════════════════════════════════════ */
.filter-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 24px; box-shadow: var(--shadow-sm);
}
.filter-card-body {
    padding: 20px 24px;
}
.form-label {
    font-size: .7rem; font-weight: 600; color: var(--s600);
    margin-bottom: 6px; text-transform: uppercase; letter-spacing: .5px;
}
.form-control, .form-select {
    padding: 8px 12px; border-radius: var(--r);
    border: 1.5px solid var(--s200); font-size: .8rem;
    font-family: var(--font); transition: var(--transition);
    background: var(--white); width: 100%;
}
.form-control:focus, .form-select:focus {
    outline: none; border-color: var(--g400);
    box-shadow: 0 0 0 3px var(--g100);
}

/* ══════════════════════════════════════════════
   KPI CARDS
══════════════════════════════════════════════ */
.kpi-grid {
    display: grid; grid-template-columns: repeat(4, 1fr);
    gap: 14px; margin-bottom: 20px;
}
@media(max-width:1100px){ .kpi-grid{ grid-template-columns:repeat(2,1fr); } }
@media(max-width:560px){ .kpi-grid{ grid-template-columns:1fr; } }

.kpi-card {
    background: var(--white); border-radius: var(--rl);
    padding: 20px; border: 1.5px solid var(--s100);
    box-shadow: var(--shadow-xs); transition: var(--transition);
}
.kpi-card:hover {
    transform: translateY(-3px); box-shadow: var(--shadow-md);
    border-color: var(--g200);
}
.kpi-icon {
    width: 48px; height: 48px; border-radius: var(--rl);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; margin-right: 14px;
}
.kpi-icon-primary { background: var(--g50); color: var(--g600); }
.kpi-icon-success { background: var(--g100); color: var(--g700); }
.kpi-icon-info { background: var(--g50); color: var(--g500); }
.kpi-icon-warning { background: #fff3cd; color: #856404; }
.kpi-icon-secondary { background: var(--s100); color: var(--s600); }
.kpi-icon-dark { background: var(--s200); color: var(--s700); }
.kpi-icon-danger { background: #fee2e2; color: #b91c1c; }

.kpi-value {
    font-size: 1.4rem; font-weight: 700; color: var(--s800);
    line-height: 1.2; font-family: var(--mono);
}
.kpi-label {
    font-size: .75rem; color: var(--s400); text-transform: uppercase;
    letter-spacing: .5px; margin-bottom: 2px;
}
.kpi-meta {
    font-size: .7rem; color: var(--s400);
}

/* ══════════════════════════════════════════════
   CARDS
══════════════════════════════════════════════ */
.report-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 20px; box-shadow: var(--shadow-sm);
    transition: var(--transition);
}
.report-card:hover { box-shadow: var(--shadow-md); }

.report-card-header {
    padding: 16px 20px; border-bottom: 1.5px solid var(--s100);
    background: var(--white); display: flex;
    align-items: center; justify-content: space-between;
}
.report-card-title {
    display: flex; align-items: center; gap: 8px;
    font-size: .9rem; font-weight: 600; color: var(--s800); margin: 0;
}
.report-card-title i { color: var(--g500); }
.report-card-badge {
    background: var(--g100); color: var(--g700);
    font-size: .7rem; font-weight: 600; padding: 4px 10px;
    border-radius: 100px;
}
.report-card-body { padding: 20px; }
.report-card-body-p0 { padding: 0; }

/* ══════════════════════════════════════════════
   TABLES
══════════════════════════════════════════════ */
.report-table {
    width: 100%; border-collapse: collapse;
}
.report-table thead th {
    font-size: .65rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .7px; color: var(--s400);
    padding: 12px 16px; background: var(--surface);
    border-bottom: 1.5px solid var(--s100); white-space: nowrap;
}
.report-table tbody td {
    padding: 12px 16px; font-size: .8rem; color: var(--s600);
    border-bottom: 1px solid var(--s100);
}
.report-table tbody tr:hover { background: var(--g50); }
.report-table tfoot td {
    padding: 12px 16px; background: var(--surface);
    border-top: 1.5px solid var(--s100);
    font-weight: 600; color: var(--s800);
}

/* ══════════════════════════════════════════════
   PROGRESS BAR
══════════════════════════════════════════════ */
.progress {
    height: 20px; background: var(--s100);
    border-radius: 10px; overflow: hidden;
}
.progress-bar {
    height: 100%; background: var(--g500);
    border-radius: 10px; font-size: .7rem;
    line-height: 20px; color: white; padding: 0 8px;
}

/* ══════════════════════════════════════════════
   BADGES
══════════════════════════════════════════════ */
.badge-db {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 30px; font-size: .7rem;
    font-weight: 600;
}
.badge-db-primary { background: var(--g100); color: var(--g700); }
.badge-db-success { background: var(--g100); color: var(--g700); }
.badge-db-warning { background: #fff3cd; color: #856404; }
.badge-db-danger { background: #fee2e2; color: #b91c1c; }
.badge-db-info { background: var(--g50); color: var(--g600); }
.badge-db-secondary { background: var(--s100); color: var(--s600); }

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media(max-width:768px){
    .reports-page{ padding: 20px; }
    .reports-header{ flex-direction: column; align-items: flex-start; }
    .filter-card-body .row{ gap: 10px; }
    .btn-db{ width: 100%; justify-content: center; }
}
</style>

<div class="reports-page">
    <!-- Breadcrumb -->
    <div class="reports-breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Rapports & Analytics</span>
    </div>

    <!-- Header -->
    <div class="reports-header anim-2">
        <div class="reports-brand">
            <div class="reports-brand-icon"><i class="fas fa-chart-line"></i></div>
            <div>
                <h1 class="reports-header-title">Rapports & <em>Analytics</em></h1>
                <p class="reports-header-sub">
                    <i class="fas fa-chart-pie me-1"></i> Performance · Finances · Opérations · Tendances
                </p>
            </div>
        </div>
        <div class="reports-header-actions">
            <button class="btn-db btn-db-outline-primary" onclick="window.print()">
                <i class="fas fa-print me-2"></i> Imprimer
            </button>
            <button class="btn-db btn-db-primary" id="exportPdf">
                <i class="fas fa-download me-2"></i> Export PDF
            </button>
        </div>
    </div>

    <!-- Filtres -->
    <div class="filter-card anim-3">
        <div class="filter-card-body">
            <form method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Période</label>
                    <select name="period" class="form-select" id="periodSelect">
                        <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                        <option value="yesterday" {{ request('period') == 'yesterday' ? 'selected' : '' }}>Hier</option>
                        <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                        <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Ce mois</option>
                        <option value="quarter" {{ request('period') == 'quarter' ? 'selected' : '' }}>Ce trimestre</option>
                        <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>Cette année</option>
                        <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Personnalisé</option>
                    </select>
                </div>
                <div class="col-md-3" id="dateRangeStart" style="{{ request('period') == 'custom' ? '' : 'display: none;' }}">
                    <label class="form-label">Date début</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3" id="dateRangeEnd" style="{{ request('period') == 'custom' ? '' : 'display: none;' }}">
                    <label class="form-label">Date fin</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to', now()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn-db btn-db-primary w-100">
                        <i class="fas fa-filter me-2"></i> Appliquer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- KPI Cards - Row 1 (Financial) -->
    <div class="kpi-grid anim-4">
        <!-- Total Revenue -->
        <div class="kpi-card">
            <div class="d-flex align-items-center">
                <div class="kpi-icon kpi-icon-primary me-3">
                    <i class="fas fa-wallet"></i>
                </div>
                <div>
                    <div class="kpi-label">Chiffre d'affaires</div>
                    <div class="kpi-value">{{ number_format($totalRevenue ?? 0, 0, ',', ' ') }} CFA</div>
                </div>
            </div>
        </div>

        <!-- Total Payments -->
        <div class="kpi-card">
            <div class="d-flex align-items-center">
                <div class="kpi-icon kpi-icon-success me-3">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div>
                    <div class="kpi-label">Paiements encaissés</div>
                    <div class="kpi-value">{{ number_format($totalPaymentsAmount ?? 0, 0, ',', ' ') }} CFA</div>
                    <div class="kpi-meta">{{ $paymentsCount ?? 0 }} transactions</div>
                </div>
            </div>
        </div>

        <!-- Average Night Rate -->
        <div class="kpi-card">
            <div class="d-flex align-items-center">
                <div class="kpi-icon kpi-icon-info me-3">
                    <i class="fas fa-moon"></i>
                </div>
                <div>
                    <div class="kpi-label">Prix moyen / nuit</div>
                    <div class="kpi-value">{{ number_format($averageNightRate ?? 0, 0, ',', ' ') }} CFA</div>
                </div>
            </div>
        </div>

        <!-- RevPAR -->
        <div class="kpi-card">
            <div class="d-flex align-items-center">
                <div class="kpi-icon kpi-icon-warning me-3">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <div class="kpi-label">RevPAR</div>
                    <div class="kpi-value">{{ number_format($revPAR ?? 0, 0, ',', ' ') }} CFA</div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards - Row 2 (Operational) -->
    <div class="kpi-grid anim-5">
        <!-- Occupancy Rate -->
        <div class="kpi-card">
            <div class="d-flex align-items-center">
                <div class="kpi-icon kpi-icon-secondary me-3">
                    <i class="fas fa-bed"></i>
                </div>
                <div>
                    <div class="kpi-label">Taux d'occupation</div>
                    <div class="kpi-value">{{ $occupancyRate ?? 0 }}%</div>
                    <div class="kpi-meta">{{ $occupiedRooms ?? 0 }}/{{ $totalRooms ?? 0 }} chambres</div>
                </div>
            </div>
        </div>

        <!-- Check-ins / Check-outs -->
        <div class="kpi-card">
            <div class="d-flex align-items-center">
                <div class="kpi-icon kpi-icon-dark me-3">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div>
                    <div class="kpi-label">Arrivées / Départs</div>
                    <div class="kpi-value">{{ $checkinsCount ?? 0 }} / {{ $checkoutsCount ?? 0 }}</div>
                </div>
            </div>
        </div>

        <!-- Total Nights -->
        <div class="kpi-card">
            <div class="d-flex align-items-center">
                <div class="kpi-icon kpi-icon-primary me-3">
                    <i class="fas fa-sun"></i>
                </div>
                <div>
                    <div class="kpi-label">Nuitées vendues</div>
                    <div class="kpi-value">{{ $totalNights ?? 0 }}</div>
                </div>
            </div>
        </div>

        <!-- Average Stay -->
        <div class="kpi-card">
            <div class="d-flex align-items-center">
                <div class="kpi-icon kpi-icon-danger me-3">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="kpi-label">Séjour moyen</div>
                    <div class="kpi-value">{{ $averageStayLength ?? 0 }} nuits</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section - Row 1 -->
    <div class="row g-4 mb-4 anim-6">
        <!-- Revenue Chart -->
        <div class="col-lg-8">
            <div class="report-card h-100">
                <div class="report-card-header">
                    <h5 class="report-card-title">
                        <i class="fas fa-chart-line"></i>
                        Évolution du chiffre d'affaires
                    </h5>
                    <span class="report-card-badge">{{ $periodLabel ?? 'Période sélectionnée' }}</span>
                </div>
                <div class="report-card-body">
                    <canvas id="revenueChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Payment Methods Chart -->
        <div class="col-lg-4">
            <div class="report-card h-100">
                <div class="report-card-header">
                    <h5 class="report-card-title">
                        <i class="fas fa-chart-pie"></i>
                        Répartition des paiements
                    </h5>
                </div>
                <div class="report-card-body">
                    <canvas id="paymentChart" style="height: 250px;"></canvas>
                    <div class="mt-3">
                        @foreach($paymentSummary ?? [] as $method)
                        <div class="d-flex justify-content-between mb-1" style="font-size:.75rem;">
                            <span style="color:var(--s600);"><i class="{{ $method['icon'] ?? 'fas fa-circle' }} me-1" style="color:var(--g500);"></i>{{ $method['label'] ?? 'N/A' }}</span>
                            <span class="fw-bold" style="color:var(--s800);">{{ number_format($method['percentage'] ?? 0, 1) }}%</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section - Row 2 -->
    <div class="row g-4 mb-4">
        <!-- Occupancy Chart -->
        <div class="col-lg-4">
            <div class="report-card h-100">
                <div class="report-card-header">
                    <h5 class="report-card-title">
                        <i class="fas fa-chart-pie"></i>
                        Occupation des chambres
                    </h5>
                </div>
                <div class="report-card-body">
                    <canvas id="occupancyChart" style="height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Room Status Chart -->
        <div class="col-lg-4">
            <div class="report-card h-100">
                <div class="report-card-header">
                    <h5 class="report-card-title">
                        <i class="fas fa-chart-bar"></i>
                        Statut des chambres
                    </h5>
                </div>
                <div class="report-card-body">
                    <canvas id="roomStatusChart" style="height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Checkout Times Chart -->
        <div class="col-lg-4">
            <div class="report-card h-100">
                <div class="report-card-header">
                    <h5 class="report-card-title">
                        <i class="fas fa-chart-pie"></i>
                        Horaires de départ
                    </h5>
                </div>
                <div class="report-card-body">
                    <canvas id="checkoutTimesChart" style="height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Section - Top Performers -->
    <div class="row g-4 mb-4">
        <!-- Top Rooms -->
        <div class="col-lg-6">
            <div class="report-card">
                <div class="report-card-header">
                    <h5 class="report-card-title">
                        <i class="fas fa-trophy"></i>
                        Top 5 chambres les plus rentables
                    </h5>
                    <a href="{{ route('room.index') }}" class="btn-db btn-db-ghost" style="padding:4px 12px;">Voir tout</a>
                </div>
                <div class="report-card-body-p0">
                    <div style="overflow-x:auto;">
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Chambre</th>
                                    <th>Type</th>
                                    <th>Nuitées</th>
                                    <th class="text-end">Revenu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topRooms ?? [] as $room)
                                <tr>
                                    <td>
                                        <span style="font-weight:600; color:var(--s800);">{{ $room['number'] ?? 'N/A' }}</span>
                                        @if(!empty($room['name']))
                                        <div style="font-size:.7rem; color:var(--s400);">{{ $room['name'] }}</div>
                                        @endif
                                    </td>
                                    <td style="color:var(--s600);">{{ $room['type'] ?? 'N/A' }}</td>
                                    <td style="color:var(--s600);">{{ $room['nights'] ?? 0 }}</td>
                                    <td class="text-end fw-bold" style="color:var(--s800); font-family:var(--mono);">{{ number_format($room['revenue'] ?? 0, 0, ',', ' ') }} CFA</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4" style="color:var(--s400);">
                                        <i class="fas fa-info-circle me-2"></i>Aucune donnée disponible
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Customers -->
        <div class="col-lg-6">
            <div class="report-card">
                <div class="report-card-header">
                    <h5 class="report-card-title">
                        <i class="fas fa-users"></i>
                        Top 5 clients
                    </h5>
                    <a href="{{ route('customer.index') }}" class="btn-db btn-db-ghost" style="padding:4px 12px;">Voir tout</a>
                </div>
                <div class="report-card-body-p0">
                    <div style="overflow-x:auto;">
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Séjours</th>
                                    <th>Nuitées</th>
                                    <th class="text-end">Dépenses totales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCustomers ?? [] as $customer)
                                <tr>
                                    <td>
                                        <span style="font-weight:600; color:var(--s800);">{{ $customer['name'] ?? 'N/A' }}</span>
                                        @if(!empty($customer['email']))
                                        <div style="font-size:.7rem; color:var(--s400);">{{ $customer['email'] }}</div>
                                        @endif
                                    </td>
                                    <td style="color:var(--s600);">{{ $customer['stays'] ?? 0 }}</td>
                                    <td style="color:var(--s600);">{{ $customer['nights'] ?? 0 }}</td>
                                    <td class="text-end fw-bold" style="color:var(--s800); font-family:var(--mono);">{{ number_format($customer['spent'] ?? 0, 0, ',', ' ') }} CFA</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4" style="color:var(--s400);">
                                        <i class="fas fa-info-circle me-2"></i>Aucune donnée disponible
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Receptionist Performance Table -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="report-card">
                <div class="report-card-header">
                    <h5 class="report-card-title">
                        <i class="fas fa-user-tie"></i>
                        Performance des réceptionnistes
                    </h5>
                    <a href="{{ route('receptionist.stats') }}" class="btn-db btn-db-ghost" style="padding:4px 12px;">Détails</a>
                </div>
                <div class="report-card-body-p0">
                    <div style="overflow-x:auto;">
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Réceptionniste</th>
                                    <th>Sessions</th>
                                    <th>Check-ins</th>
                                    <th>Check-outs</th>
                                    <th>Réservations</th>
                                    <th class="text-end">Encaissé</th>
                                    <th class="text-center">Productivité</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($receptionistPerformance ?? [] as $recep)
                                <tr>
                                    <td><span style="font-weight:600; color:var(--s800);">{{ $recep['name'] ?? 'N/A' }}</span></td>
                                    <td>{{ $recep['sessions'] ?? 0 }}</td>
                                    <td>{{ $recep['checkins'] ?? 0 }}</td>
                                    <td>{{ $recep['checkouts'] ?? 0 }}</td>
                                    <td>{{ $recep['reservations'] ?? 0 }}</td>
                                    <td class="text-end fw-bold" style="color:var(--s800); font-family:var(--mono);">{{ number_format($recep['total'] ?? 0, 0, ',', ' ') }} CFA</td>
                                    <td class="text-center">
                                        <div class="progress" style="height:20px; width:100px; margin:0 auto;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $recep['productivity'] ?? 0 }}%;">
                                                {{ $recep['productivity'] ?? 0 }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4" style="color:var(--s400);">
                                        <i class="fas fa-info-circle me-2"></i>Aucune donnée disponible
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Summary Table -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="report-card">
                <div class="report-card-header">
                    <h5 class="report-card-title">
                        <i class="fas fa-money-bill-wave"></i>
                        Synthèse détaillée des paiements
                    </h5>
                    <a href="{{ route('payment.index') }}" class="btn-db btn-db-ghost" style="padding:4px 12px;">Voir tout</a>
                </div>
                <div class="report-card-body-p0">
                    <div style="overflow-x:auto;">
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Méthode</th>
                                    <th class="text-end">Montant</th>
                                    <th class="text-end">%</th>
                                    <th class="text-end">Transactions</th>
                                    <th class="text-end">Moyenne par transaction</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentSummary ?? [] as $method)
                                <tr>
                                    <td>
                                        <i class="{{ $method['icon'] ?? 'fas fa-circle' }} me-2" style="color:var(--g500);"></i>
                                        <span style="color:var(--s800);">{{ $method['label'] ?? 'N/A' }}</span>
                                    </td>
                                    <td class="text-end fw-bold" style="color:var(--s800); font-family:var(--mono);">{{ number_format($method['amount'] ?? 0, 0, ',', ' ') }} CFA</td>
                                    <td class="text-end" style="color:var(--s600);">{{ number_format($method['percentage'] ?? 0, 1) }}%</td>
                                    <td class="text-end" style="color:var(--s600);">{{ $method['count'] ?? 0 }}</td>
                                    <td class="text-end" style="color:var(--s600);">{{ number_format($method['average'] ?? 0, 0, ',', ' ') }} CFA</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td><strong style="color:var(--s800);">TOTAL</strong></td>
                                    <td class="text-end fw-bold" style="color:var(--s800); font-family:var(--mono);">{{ number_format($totalPaymentsAmount ?? 0, 0, ',', ' ') }} CFA</td>
                                    <td class="text-end fw-bold" style="color:var(--s800);">100%</td>
                                    <td class="text-end fw-bold" style="color:var(--s800);">{{ $paymentsCount ?? 0 }}</td>
                                    <td class="text-end fw-bold" style="color:var(--s800);">{{ number_format($averagePayment ?? 0, 0, ',', ' ') }} CFA</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row g-4">
        <div class="col-12">
            <div class="report-card">
                <div class="report-card-header">
                    <h5 class="report-card-title">
                        <i class="fas fa-history"></i>
                        Dernières transactions
                    </h5>
                    <a href="{{ route('transaction.index') }}" class="btn-db btn-db-ghost" style="padding:4px 12px;">Voir tout</a>
                </div>
                <div class="report-card-body-p0">
                    <div style="overflow-x:auto;">
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Chambre</th>
                                    <th>Arrivée</th>
                                    <th>Départ</th>
                                    <th class="text-end">Montant</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions ?? [] as $transaction)
                                <tr>
                                    <td><span style="color:var(--g600); font-family:var(--mono);">#{{ $transaction->id }}</span></td>
                                    <td><span style="color:var(--s800);">{{ $transaction->customer->name ?? 'N/A' }}</span></td>
                                    <td>{{ $transaction->room->number ?? 'N/A' }}</td>
                                    <td style="font-family:var(--mono);">{{ \Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y') }}</td>
                                    <td style="font-family:var(--mono);">{{ \Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y') }}</td>
                                    <td class="text-end fw-bold" style="color:var(--s800); font-family:var(--mono);">{{ number_format($transaction->total_price ?? 0, 0, ',', ' ') }} CFA</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'reservation' => 'badge-db-info',
                                                'active' => 'badge-db-success',
                                                'completed' => 'badge-db-secondary',
                                                'cancelled' => 'badge-db-danger',
                                                'no_show' => 'badge-db-warning'
                                            ];
                                        @endphp
                                        <span class="badge-db {{ $statusColors[$transaction->status] ?? 'badge-db-secondary' }}">
                                            {{ $transaction->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4" style="color:var(--s400);">
                                        <i class="fas fa-info-circle me-2"></i>Aucune transaction récente
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    // ================================================
    // DATE RANGE TOGGLE
    // ================================================
    document.addEventListener('DOMContentLoaded', function() {
        const periodSelect = document.getElementById('periodSelect');
        if (periodSelect) {
            periodSelect.addEventListener('change', function() {
                const isCustom = this.value === 'custom';
                document.getElementById('dateRangeStart').style.display = isCustom ? 'block' : 'none';
                document.getElementById('dateRangeEnd').style.display = isCustom ? 'block' : 'none';
            });
        }
    });

    // ================================================
    // CHART DATA FROM CONTROLLER
    // ================================================
    const revenueLabels = @json($revenueChartLabels ?? []);
    const revenueData = @json($revenueChartData ?? []);
    
    const paymentLabels = @json($paymentChartLabels ?? []);
    const paymentData = @json($paymentChartData ?? []);
    
    const occupied = {{ $occupiedRooms ?? 0 }};
    const available = {{ $availableRooms ?? 0 }};
    
    const roomStatusLabels = @json($roomStatusLabels ?? []);
    const roomStatusData = @json($roomStatusData ?? []);
    
    const checkoutTimesData = @json($checkoutTimesData ?? [0, 0, 0]);

    // ================================================
    // CHART 1: REVENUE LINE CHART
    // ================================================
    if (document.getElementById('revenueChart')) {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        if (!revenueData || revenueData.length === 0 || revenueData.every(v => v === 0)) {
            ctx.font = '14px DM Sans';
            ctx.fillStyle = '#737873';
            ctx.textAlign = 'center';
            ctx.fillText('Aucune donnée disponible pour cette période', ctx.canvas.width/2, ctx.canvas.height/2);
        } else {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: revenueLabels,
                    datasets: [{
                        label: 'Chiffre d\'affaires (CFA)',
                        data: revenueData,
                        borderWidth: 3,
                        borderColor: '#2e8540',
                        backgroundColor: 'rgba(46, 133, 64, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#2e8540',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return (context.raw || 0).toLocaleString() + ' CFA';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString() + ' CFA';
                                }
                            },
                            grid: { color: '#dde0dd' }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    }

    // ================================================
    // CHART 2: PAYMENT METHODS DOUGHNUT
    // ================================================
    if (document.getElementById('paymentChart')) {
        const ctx = document.getElementById('paymentChart').getContext('2d');
        
        if (!paymentData || paymentData.length === 0 || paymentData.every(v => v === 0)) {
            ctx.font = '14px DM Sans';
            ctx.fillStyle = '#737873';
            ctx.textAlign = 'center';
            ctx.fillText('Aucun paiement pour cette période', ctx.canvas.width/2, ctx.canvas.height/2);
        } else {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: paymentLabels,
                    datasets: [{
                        data: paymentData,
                        backgroundColor: [
                            '#2e8540', // cash - green
                            '#dc3545', // card - red
                            '#1e6b2e', // mobile - dark green
                            '#ffc107', // transfer - yellow
                            '#6f42c1', // fedapay - purple
                            '#fd7e14', // check - orange
                            '#737873'  // other - gray
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { color: '#545954', font: { size: 11 } } },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${value.toLocaleString()} CFA (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        }
    }

    // ================================================
    // CHART 3: OCCUPANCY PIE CHART
    // ================================================
    if (document.getElementById('occupancyChart')) {
        const ctx = document.getElementById('occupancyChart').getContext('2d');
        const total = occupied + available;
        
        if (total === 0) {
            ctx.font = '14px DM Sans';
            ctx.fillStyle = '#737873';
            ctx.textAlign = 'center';
            ctx.fillText('Aucune chambre disponible', ctx.canvas.width/2, ctx.canvas.height/2);
        } else {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Occupées', 'Disponibles'],
                    datasets: [{
                        data: [occupied, available],
                        backgroundColor: ['#dc3545', '#2e8540'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { color: '#545954', font: { size: 11 } } },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    let total = occupied + available;
                                    let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${value} chambres (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        }
    }

    // ================================================
    // CHART 4: ROOM STATUS CHART
    // ================================================
    if (document.getElementById('roomStatusChart')) {
        const ctx = document.getElementById('roomStatusChart').getContext('2d');
        
        if (!roomStatusData || roomStatusData.length === 0 || roomStatusData.every(v => v === 0)) {
            ctx.font = '14px DM Sans';
            ctx.fillStyle = '#737873';
            ctx.textAlign = 'center';
            ctx.fillText('Aucune donnée de statut', ctx.canvas.width/2, ctx.canvas.height/2);
        } else {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: roomStatusLabels,
                    datasets: [{
                        label: 'Nombre de chambres',
                        data: roomStatusData,
                        backgroundColor: [
                            '#2e8540', // disponible - green
                            '#dc3545', // occupée - red
                            '#ffc107', // maintenance - yellow
                            '#1e6b2e', // réservée - dark green
                            '#6f42c1', // nettoyage - purple
                            '#737873'  // sale - gray
                        ],
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: '#545954' },
                            grid: { color: '#dde0dd' }
                        },
                        x: { ticks: { color: '#545954' }, grid: { display: false } }
                    }
                }
            });
        }
    }

    // ================================================
    // CHART 5: CHECKOUT TIMES CHART
    // ================================================
    if (document.getElementById('checkoutTimesChart')) {
        const ctx = document.getElementById('checkoutTimesChart').getContext('2d');
        
        if (!checkoutTimesData || checkoutTimesData.every(v => v === 0)) {
            ctx.font = '14px DM Sans';
            ctx.fillStyle = '#737873';
            ctx.textAlign = 'center';
            ctx.fillText('Aucun départ enregistré', ctx.canvas.width/2, ctx.canvas.height/2);
        } else {
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Avant 12h', '12h-14h (largesse)', 'Après 14h (late checkout)'],
                    datasets: [{
                        data: checkoutTimesData,
                        backgroundColor: ['#2e8540', '#ffc107', '#dc3545'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { color: '#545954', font: { size: 11 } } }
                    }
                }
            });
        }
    }

    // ================================================
    // EXPORT PDF FUNCTION
    // ================================================
    document.getElementById('exportPdf')?.addEventListener('click', function() {
        const element = document.querySelector('.reports-page');
        const opt = {
            margin: [0.5, 0.5, 0.5, 0.5],
            filename: 'rapport-hotel-{{ now()->format('Y-m-d') }}.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, letterRendering: true },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save();
    });

</script>
@endsection