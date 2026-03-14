@extends('template.master')

@section('title', 'Rapports de Nettoyage')

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

.reports-page {
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

/* ══════════════════════════════════════════════
   FILTER CARD
══════════════════════════════════════════════ */
.filter-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    padding: 20px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-sm);
}
.form-label {
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    margin-bottom: 6px;
}
.form-control, .form-select {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .8rem;
}
.form-control:focus, .form-select:focus {
    outline: none;
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgba(46,133,64,.1);
}
.input-group {
    display: flex;
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
    padding: 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: var(--transition);
}
.stat-card:hover {
    border-color: var(--green-300);
    transform: translateY(-2px);
}
.stat-left h6 {
    font-size: .65rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    margin-bottom: 4px;
}
.stat-left h2 {
    font-size: 1.8rem;
    font-weight: 700;
    font-family: var(--mono);
    color: var(--gray-900);
    line-height: 1;
}
.stat-left small {
    font-size: .6rem;
    color: var(--gray-400);
}
.stat-icon {
    font-size: 2rem;
    opacity: .5;
}
.stat-icon.blue { color: var(--green-600); }
.stat-icon.green { color: var(--green-600); }
.stat-icon.orange { color: var(--green-600); }

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
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.card-header.blue { background: var(--green-600); color: white; }
.card-header i { color: white; }
.card-header .badge {
    background: rgba(255,255,255,.2);
    color: white;
    border: 1.5px solid rgba(255,255,255,.2);
}
.card-body {
    padding: 0;
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
    padding: 14px 18px;
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    border-bottom: 1.5px solid var(--gray-200);
    text-align: left;
}
.table tbody td {
    padding: 14px 18px;
    border-bottom: 1px solid var(--gray-200);
    color: var(--gray-700);
    font-size: .8rem;
    vertical-align: middle;
}
.table tbody tr:hover td {
    background: var(--green-50);
}
.table-footer {
    padding: 14px 18px;
    background: var(--gray-50);
    border-top: 1.5px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* ══════════════════════════════════════════════
   BADGES
══════════════════════════════════════════════ */
.badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 100px;
    font-size: .65rem;
    font-weight: 600;
}
.badge-green { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.badge-red { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }
.badge-blue { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.badge-gray { background: var(--gray-100); color: var(--gray-600); border: 1.5px solid var(--gray-200); }
.badge-orange { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }

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
    padding: 12px 16px;
    border-bottom: 1px solid var(--gray-200);
    text-decoration: none;
    color: var(--gray-700);
    transition: var(--transition);
}
.list-item:last-child {
    border-bottom: none;
}
.list-item:hover {
    background: var(--green-50);
}
.list-item.active {
    background: var(--green-600);
    color: white;
}
.list-item.active .badge {
    background: white;
    color: var(--green-600);
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    text-align: center;
    padding: 48px 24px;
}
.empty-icon {
    font-size: 3rem;
    color: var(--gray-300);
    margin-bottom: 16px;
}
.empty-state h5 {
    font-size: .9rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 4px;
}
.empty-state p {
    color: var(--gray-400);
    font-size: .75rem;
}

/* ══════════════════════════════════════════════
   ALERT
══════════════════════════════════════════════ */
.alert {
    padding: 16px 20px;
    border-radius: var(--rl);
    border: 1.5px solid;
    display: flex;
    align-items: center;
    gap: 16px;
}
.alert-green {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.alert-link {
    color: var(--green-700);
    font-weight: 600;
    text-decoration: underline;
}
</style>

<div class="reports-page">

    {{-- Breadcrumb --}}
    <div class="breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('housekeeping.index') }}">Housekeeping</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Rapports</span>
    </div>

    {{-- En-tête --}}
    <div class="page-header anim-2">
        <div>
            <div class="header-title">
                <span class="header-icon"><i class="fas fa-chart-bar"></i></span>
                <h1>Rapports de <em>nettoyage</em></h1>
            </div>
            <p class="header-subtitle">Analyses et statistiques détaillées</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('housekeeping.index') }}" class="btn btn-gray">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <a href="{{ route('housekeeping.daily-report') }}" class="btn btn-green">
                <i class="fas fa-file-alt"></i> Rapport Quotidien
            </a>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="filter-card anim-3">
        <form action="{{ route('housekeeping.reports') }}" method="GET" id="reportFilters">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Date</label>
                    <div class="input-group">
                        <input type="date" class="form-control" name="date" value="{{ $selectedDate->format('Y-m-d') }}">
                        <button class="btn btn-gray" type="button" id="todayBtn">Aujourd'hui</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Agent</label>
                    <select class="form-select" name="employee">
                        <option value="">Tous</option>
                        @php $staff = \App\Models\User::where('role', 'Housekeeping')->orWhere('role','housekeeping')->get(); @endphp
                        @foreach($staff as $e)
                            <option value="{{ $e->id }}" {{ request('employee') == $e->id ? 'selected' : '' }}>{{ $e->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Type chambre</label>
                    <select class="form-select" name="room_type">
                        <option value="">Tous</option>
                        @php
                            $types = class_exists('\App\Models\Type') ? \App\Models\Type::all() : collect();
                        @endphp
                        @foreach($types as $t)
                            <option value="{{ $t->id }}" {{ request('room_type') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 d-flex justify-content-end gap-2">
                    <button class="btn btn-green" type="submit"><i class="fas fa-filter"></i> Filtrer</button>
                    <a href="{{ route('housekeeping.reports') }}" class="btn btn-gray"><i class="fas fa-redo"></i> Réinitialiser</a>
                    <button class="btn btn-green" type="button" onclick="exportToExcel()"><i class="fas fa-file-excel"></i> Exporter</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Statistiques --}}
    <div class="stats-grid anim-3">
        <div class="stat-card">
            <div class="stat-left">
                <h6>Nettoyées</h6>
                <h2>{{ $stats['total_cleaned'] ?? 0 }}</h2>
                <small>{{ $selectedDate->format('d/m/Y') }}</small>
            </div>
            <div class="stat-icon blue"><i class="fas fa-bed"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-left">
                <h6>Temps moyen</h6>
                <h2>{{ $stats['average_cleaning_time'] ?? 30 }} min</h2>
                <small>par chambre</small>
            </div>
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-left">
                <h6>Disponibles</h6>
                <h2>{{ $stats['cleaned_by_status'][1] ?? 0 }}</h2>
                <small>prêtes</small>
            </div>
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-left">
                <h6>Occupées</h6>
                <h2>{{ $stats['cleaned_by_status'][2] ?? 0 }}</h2>
                <small>nettoyées occupées</small>
            </div>
            <div class="stat-icon orange"><i class="fas fa-users"></i></div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Tableau principal --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header blue">
                    <div><i class="fas fa-list"></i> Détail des chambres nettoyées</div>
                    <span class="badge">{{ $cleanedRooms->count() }} chambres</span>
                </div>
                <div class="card-body">
                    @if($cleanedRooms->count() > 0)
                        <div class="table-responsive">
                            <table class="table" id="cleaningReportTable">
                                <thead>
                                    <tr>
                                        <th>Chambre</th>
                                        <th>Type</th>
                                        <th>Début</th>
                                        <th>Fin</th>
                                        <th>Durée</th>
                                        <th>Agent</th>
                                        <th>Statut</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cleanedRooms as $room)
                                    <tr>
                                        <td><span class="badge badge-green">#{{ $room->number }}</span></td>
                                        <td>{{ $room->type->name ?? 'Standard' }}</td>
                                        <td>{{ $room->cleaning_started_at ? \Carbon\Carbon::parse($room->cleaning_started_at)->format('H:i') : 'N/A' }}</td>
                                        <td>{{ $room->cleaning_completed_at ? \Carbon\Carbon::parse($room->cleaning_completed_at)->format('H:i') : 'N/A' }}</td>
                                        <td>
                                            @if($room->cleaning_started_at && $room->cleaning_completed_at)
                                                @php $mins = \Carbon\Carbon::parse($room->cleaning_started_at)->diffInMinutes($room->cleaning_completed_at); @endphp
                                                <span class="badge {{ $mins > 60 ? 'badge-red' : ($mins > 45 ? 'badge-orange' : 'badge-green') }}">{{ $mins }} min</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($room->cleaned_by)
                                                @php $c = \App\Models\User::find($room->cleaned_by); @endphp
                                                <span class="badge badge-blue">{{ $c->name ?? 'Inconnu' }}</span>
                                            @else
                                                <span class="text-muted">Auto</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $status = $room->room_status_id;
                                                $statusClass = $status == 1 ? 'badge-green' : ($status == 2 ? 'badge-blue' : 'badge-gray');
                                            @endphp
                                            <span class="badge {{ $statusClass }}">{{ $room->roomStatus->name ?? '?' }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1 justify-content-center">
                                                <button class="btn-icon" onclick="showRoomDetails({{ $room->id }})"><i class="fas fa-eye"></i></button>
                                                <a href="{{ route('housekeeping.maintenance-form', $room) }}" class="btn-icon"><i class="fas fa-tools"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-chart-bar"></i></div>
                            <h5>Aucune donnée</h5>
                            <p>Aucune chambre nettoyée à cette date</p>
                        </div>
                    @endif
                </div>
                @if($cleanedRooms->count() > 0)
                <div class="table-footer">
                    <small class="text-muted"><i class="fas fa-info-circle"></i> <span class="badge badge-green">≤45 min</span> <span class="badge badge-orange">45-60 min</span> <span class="badge badge-red">≥60 min</span></small>
                    <small class="text-muted">{{ now()->format('d/m/Y H:i') }}</small>
                </div>
                @endif
            </div>
        </div>

        {{-- Colonne droite --}}
        <div class="col-lg-4">
            {{-- Graphique agent --}}
            <div class="card">
                <div class="card-header blue">
                    <i class="fas fa-users"></i> Répartition par agent
                </div>
                <div class="card-body p-3">
                    @if(isset($cleanedByUser) && $cleanedByUser->count() > 0)
                        <canvas id="employeeChart" height="200"></canvas>
                        <div class="mt-3">
                            @foreach($cleanedByUser as $item)
                            <div class="d-flex justify-content-between py-1">
                                <span>{{ $item['name'] }}</span>
                                <span class="fw-bold">{{ $item['count'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state py-3">
                            <i class="fas fa-user-slash"></i>
                            <p class="mb-0">Aucune donnée</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Dates disponibles --}}
            <div class="card mt-3">
                <div class="card-header blue">
                    <i class="fas fa-calendar"></i> Dates disponibles
                </div>
                <div class="card-body p-0">
                    @if(isset($availableDates) && $availableDates->count() > 0)
                        <div class="list-group">
                            @foreach($availableDates as $date)
                            <a href="{{ route('housekeeping.reports', ['date' => $date]) }}" 
                               class="list-item {{ $selectedDate->format('Y-m-d') == $date ? 'active' : '' }}">
                                {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                <span class="badge"><i class="fas fa-arrow-right"></i></span>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state py-3">
                            <i class="fas fa-calendar-times"></i>
                            <p class="mb-0">Aucune date</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Lien mensuel --}}
    <div class="card mt-4">
        <div class="card-body">
            <div class="alert alert-green">
                <i class="fas fa-chart-line fa-2x"></i>
                <div>
                    <h6 class="fw-semibold">Tendances mensuelles</h6>
                    <p class="mb-0">Consultez les <a href="{{ route('housekeeping.monthly-stats') }}" class="alert-link">statistiques mensuelles</a> pour une analyse détaillée.</p>
                </div>
            </div>
            <div class="text-center">
                <a href="{{ route('housekeeping.monthly-stats') }}" class="btn btn-green">
                    <i class="fas fa-chart-bar"></i> Voir mensuel
                </a>
            </div>
        </div>
    </div>

</div>

{{-- Modal détails --}}
<div class="modal fade" id="roomModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-door-closed" style="color:var(--green-600);"></i> Détails chambre</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="roomModalBody"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique
    const emp = document.getElementById('employeeChart');
    if (emp) {
        @if(isset($cleanedByUser) && $cleanedByUser->count() > 0)
            const labels = {!! json_encode($cleanedByUser->pluck('name')) !!};
            const data = {!! json_encode($cleanedByUser->pluck('count')) !!};
            new Chart(emp, {
                type: 'doughnut',
                data: { labels, datasets: [{ data, backgroundColor: ['#1e6b2e','#1e6b2e','#1e6b2e','#1e6b2e','#1e6b2e'] }] },
                options: { plugins: { legend: { position: 'bottom' } } }
            });
        @endif
    }

    // Aujourd'hui
    document.getElementById('todayBtn')?.addEventListener('click', function() {
        document.querySelector('input[name="date"]').value = new Date().toISOString().split('T')[0];
        document.getElementById('reportFilters').submit();
    });
});

function exportToExcel() {
    const table = document.getElementById('cleaningReportTable');
    if (!table) return alert('Aucune donnée');
    const w = window.open('', '_blank');
    w.document.write('<html><head><title>Rapport {{ $selectedDate->format('d/m/Y') }}</title><style>table{border-collapse:collapse;width:100%}th,td{border:1px solid #ddd;padding:8px}th{background:#f2f2f2}</style></head><body>' + table.outerHTML + '</body></html>');
    w.print();
}

function showRoomDetails(id) {
    const modal = new bootstrap.Modal(document.getElementById('roomModal'));
    document.getElementById('roomModalBody').innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Chargement...</p></div>';
    modal.show();
    setTimeout(() => {
        document.getElementById('roomModalBody').innerHTML = '<div class="alert alert-green">Détails à venir pour chambre ' + id + '</div>';
    }, 500);
}
</script>

@endsection