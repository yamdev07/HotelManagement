@extends('template.master')

@section('title', 'Rapport Quotidien - ' . $today->format('d/m/Y'))

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

.report-page {
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
.stat-value.red { color: var(--red-500); }
.stat-footer {
    font-size: .7rem;
    color: var(--gray-400);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
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
    padding: 16px 20px;
    border-bottom: 1.5px solid var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.card-header.green { background: var(--green-600); color: white; }
.card-header.red { background: var(--red-500); color: white; }
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
}
.table tbody tr:hover td {
    background: var(--green-50);
}
.table tfoot td {
    padding: 12px 16px;
    background: var(--gray-50);
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
.badge-gray { background: var(--gray-100); color: var(--gray-600); border: 1.5px solid var(--gray-200); }

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
    border-bottom: 1.5px solid var(--gray-200);
}
.list-item:last-child {
    border-bottom: none;
}
.user-avatar {
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
.count-badge {
    background: var(--green-50);
    color: var(--green-700);
    border: 1.5px solid var(--green-200);
    padding: 2px 10px;
    border-radius: 100px;
    font-size: .7rem;
    font-weight: 600;
}

/* ══════════════════════════════════════════════
   FORM
══════════════════════════════════════════════ */
.card-footer {
    padding: 20px;
    border-top: 1.5px solid var(--gray-200);
    background: var(--white);
}
.form-label {
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray-600);
    text-transform: uppercase;
    margin-bottom: 6px;
    display: block;
}
.form-control {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .8rem;
    transition: var(--transition);
    font-family: var(--font);
}
.form-control:focus {
    outline: none;
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgba(46,133,64,.1);
}
textarea.form-control {
    resize: vertical;
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    padding: 48px 20px;
    text-align: center;
}
.empty-state i {
    font-size: 2.5rem;
    color: var(--gray-300);
    margin-bottom: 12px;
}
.empty-state h5 {
    font-size: .9rem;
    font-weight: 600;
    color: var(--gray-600);
}
.empty-state p {
    color: var(--gray-400);
    font-size: .75rem;
}

/* ══════════════════════════════════════════════
   PRINT STYLES
══════════════════════════════════════════════ */
@media print {
    .no-print { display: none !important; }
    .card { border: 1.5px solid var(--gray-200) !important; break-inside: avoid; }
    .badge { border: 1.5px solid var(--gray-200); }
}
</style>

<div class="report-page">

    {{-- Breadcrumb --}}
    <div class="breadcrumb no-print anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('housekeeping.index') }}">Housekeeping</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Rapport du {{ $today->format('d/m/Y') }}</span>
    </div>

    {{-- En-tête --}}
    <div class="page-header no-print anim-2">
        <div>
            <div class="header-title">
                <span class="header-icon"><i class="fas fa-file-alt"></i></span>
                <h1>Rapport <em>quotidien</em></h1>
            </div>
            <p class="header-subtitle">Activités de nettoyage du {{ $today->format('d/m/Y') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('housekeeping.index') }}" class="btn btn-gray">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <button class="btn btn-green" onclick="window.print()">
                <i class="fas fa-print"></i> Imprimer
            </button>
        </div>
    </div>

    {{-- Statistiques --}}
    <div class="stats-grid anim-3">
        <div class="stat-card">
            <div class="stat-label">Nettoyées</div>
            <div class="stat-value green">{{ $stats['cleaned_today'] }}</div>
            <div class="stat-footer"><i class="fas fa-check-circle"></i> aujourd'hui</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Restantes</div>
            <div class="stat-value red">{{ $stats['to_clean'] }}</div>
            <div class="stat-footer"><i class="fas fa-broom"></i> en attente</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Taux</div>
            <div class="stat-value">
                @if($stats['cleaned_today'] + $stats['to_clean'] > 0)
                    {{ round(($stats['cleaned_today'] / ($stats['cleaned_today'] + $stats['to_clean'])) * 100) }}%
                @else
                    100%
                @endif
            </div>
            <div class="stat-footer"><i class="fas fa-chart-line"></i> achèvement</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Temps moyen</div>
            <div class="stat-value">25m</div>
            <div class="stat-footer"><i class="fas fa-clock"></i> par chambre</div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Tableau principal --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header green">
                    <div><i class="fas fa-check-circle"></i> Chambres nettoyées aujourd'hui ({{ $cleanedToday->count() }})</div>
                    <span class="badge badge-green">{{ $today->format('d/m/Y') }}</span>
                </div>
                <div class="card-body p-0">
                    @if($cleanedToday->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Chambre</th>
                                    <th>Type</th>
                                    <th>Nettoyée à</th>
                                    <th>Durée</th>
                                    <th>Femme de chambre</th>
                                    <th>Statut</th>
                                    <th class="no-print"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cleanedToday as $room)
                                <tr>
                                    <td><span class="badge badge-green">#{{ $room->number }}</span></td>
                                    <td>{{ $room->type->name ?? 'Standard' }}</td>
                                    <td>{{ $room->last_cleaned_at ? \Carbon\Carbon::parse($room->last_cleaned_at)->format('H:i') : 'N/A' }}</td>
                                    <td>
                                        @if($room->cleaning_started_at && $room->cleaning_completed_at)
                                            {{ \Carbon\Carbon::parse($room->cleaning_started_at)->diffInMinutes($room->cleaning_completed_at) }} min
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($room->cleaned_by)
                                            <span class="badge badge-gray">{{ \App\Models\User::find($room->cleaned_by)->name ?? 'Inconnu' }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $status = $room->room_status_id == 1 ? 'green' : ($room->room_status_id == 2 ? 'red' : 'gray');
                                        @endphp
                                        <span class="badge badge-{{ $status }}">{{ $room->roomStatus->name ?? 'Inconnu' }}</span>
                                    </td>
                                    <td class="no-print">
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-gray btn-sm"><i class="fas fa-eye"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-clipboard-list"></i>
                        <h5>Aucune chambre nettoyée</h5>
                        <p>Commencez par nettoyer les chambres à nettoyer.</p>
                    </div>
                    @endif
                </div>
                @if($cleanedToday->count() > 0)
                <div class="card-footer no-print">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted"><i class="fas fa-info-circle"></i> {{ $cleanedToday->count() }} chambres nettoyées</small>
                        <small class="text-muted">Dernière mise à jour: {{ now()->format('H:i') }}</small>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Colonne droite --}}
        <div class="col-lg-4">

            {{-- Performance par agent --}}
            <div class="card">
                <div class="card-header blue">
                    <i class="fas fa-chart-bar"></i> Performance par agent
                </div>
                <div class="card-body p-0">
                    @if(count($stats['cleaned_by_user']) > 0)
                        <div class="list-group">
                            @foreach($stats['cleaned_by_user'] as $userId => $count)
                                @php $user = \App\Models\User::find($userId); @endphp
                                <div class="list-item">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="user-avatar">{{ substr($user->name ?? '?', 0, 1) }}</div>
                                        <span>{{ $user->name ?? 'Inconnu' }}</span>
                                    </div>
                                    <span class="count-badge">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-user-slash"></i>
                            <p>Aucune donnée disponible</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Chambres restantes --}}
            <div class="card">
                <div class="card-header red">
                    <i class="fas fa-broom"></i> Chambres restantes ({{ $toClean->count() }})
                </div>
                <div class="card-body p-0">
                    @if($toClean->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Chambre</th>
                                        <th>Priorité</th>
                                        <th class="no-print"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($toClean->take(5) as $room)
                                    <tr>
                                        <td><span class="badge badge-red">#{{ $room->number }}</span></td>
                                        <td>
                                            @if($room->activeTransactions->where('check_out', '<=', now())->count() > 0)
                                                <span class="badge badge-red">Haute</span>
                                            @else
                                                <span class="badge badge-gray">Normale</span>
                                            @endif
                                        </td>
                                        <td class="no-print">
                                            <form action="{{ route('housekeeping.start-cleaning', $room->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-red btn-sm"><i class="fas fa-play"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($toClean->count() > 5)
                        <div class="p-3 text-center no-print">
                            <a href="{{ route('housekeeping.to-clean') }}" class="btn btn-outline btn-sm">
                                Voir les {{ $toClean->count() - 5 }} autres
                            </a>
                        </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <i class="fas fa-trophy" style="color:var(--green-600);"></i>
                            <h5>Tout est nettoyé !</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Notes --}}
    <div class="card mt-4 no-print">
        <div class="card-header gray">
            <i class="fas fa-sticky-note"></i> Notes du jour
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Observations générales</label>
                        <textarea class="form-control" rows="3" placeholder="Déroulement de la journée, problèmes..."></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Suggestions</label>
                        <textarea class="form-control" rows="3" placeholder="Idées d'amélioration..."></textarea>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-outline"><i class="fas fa-save"></i> Enregistrer</button>
                <button class="btn btn-green"><i class="fas fa-file-pdf"></i> Générer PDF</button>
            </div>
        </div>
    </div>

</div>

<script>
function updateClock() {
    const clocks = document.querySelectorAll('.clock');
    clocks.forEach(c => c.textContent = new Date().toLocaleTimeString());
}
setInterval(updateClock, 60000);
updateClock();
</script>

@endsection