@extends('template.master')

@section('title', 'Chambres en Maintenance')

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

.maintenance-page {
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
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    padding: 20px 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
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
.btn-red {
    background: var(--red-500);
    color: white;
}
.btn-red:hover {
    background: var(--red-600);
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
    padding: 18px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    transition: var(--transition);
}
.stat-card:hover {
    border-color: var(--green-300);
    transform: translateY(-2px);
}
.stat-left {
    flex: 1;
}
.stat-label {
    font-size: .65rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    margin-bottom: 4px;
}
.stat-number {
    font-size: 1.8rem;
    font-weight: 700;
    font-family: var(--mono);
    color: var(--gray-900);
    line-height: 1;
    margin-bottom: 2px;
}
.stat-icon {
    font-size: 1.8rem;
    color: var(--green-600);
    opacity: .5;
}

/* ══════════════════════════════════════════════
   CARD
══════════════════════════════════════════════ */
.card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.card-header {
    padding: 16px 20px;
    border-bottom: 1.5px solid var(--gray-200);
    background: var(--green-600);
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.card-header i {
    color: white;
}
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
    padding: 16px 18px;
    border-bottom: 1px solid var(--gray-200);
    color: var(--gray-700);
    font-size: .8rem;
    vertical-align: middle;
}
.table tbody tr:hover td {
    background: var(--green-50);
}
.table tbody tr.warning {
    background: var(--red-50);
}

/* ══════════════════════════════════════════════
   ROOM BADGE
══════════════════════════════════════════════ */
.room-badge {
    width: 48px;
    height: 48px;
    border-radius: var(--r);
    background: var(--green-600);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: .9rem;
    font-family: var(--mono);
    flex-shrink: 0;
}
.room-info {
    margin-left: 12px;
}
.room-number {
    font-weight: 600;
    font-size: .9rem;
    color: var(--gray-800);
}
.room-type {
    font-size: .65rem;
    color: var(--gray-500);
}

/* ══════════════════════════════════════════════
   BADGES
══════════════════════════════════════════════ */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
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
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: var(--green-50);
    color: var(--green-600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .7rem;
    font-weight: 600;
}
.avatar-lg {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: var(--green-50);
    color: var(--green-600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    font-weight: 600;
}

/* ══════════════════════════════════════════════
   ACTION BUTTONS
══════════════════════════════════════════════ */
.action-group {
    display: flex;
    gap: 4px;
    justify-content: flex-end;
}
.btn-icon {
    width: 32px;
    height: 32px;
    border-radius: var(--r);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1.5px solid var(--gray-200);
    background: var(--white);
    color: var(--gray-500);
    cursor: pointer;
    transition: var(--transition);
}
.btn-icon:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}

/* ══════════════════════════════════════════════
   SECONDARY CARD
══════════════════════════════════════════════ */
.secondary-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    overflow: hidden;
    height: 100%;
}
.secondary-header {
    padding: 16px 18px;
    border-bottom: 1.5px solid var(--gray-200);
    background: var(--gray-50);
    display: flex;
    align-items: center;
    gap: 8px;
}
.secondary-header i {
    color: var(--green-600);
}
.secondary-header strong {
    font-size: .85rem;
    color: var(--gray-700);
}
.list-group {
    list-style: none;
    padding: 0;
}
.list-item {
    padding: 14px 18px;
    border-bottom: 1.5px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.list-item:last-child {
    border-bottom: none;
}

/* ══════════════════════════════════════════════
   PROGRESS BAR
══════════════════════════════════════════════ */
.progress {
    height: 6px;
    background: var(--gray-100);
    border-radius: 100px;
    overflow: hidden;
}
.progress-bar {
    height: 100%;
    background: var(--green-600);
    border-radius: 100px;
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    padding: 48px 24px;
    text-align: center;
}
.empty-state i {
    font-size: 3rem;
    color: var(--green-500);
    margin-bottom: 16px;
}
.empty-state h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 8px;
}
.empty-state p {
    color: var(--gray-400);
    margin-bottom: 20px;
}

/* ══════════════════════════════════════════════
   MODAL
══════════════════════════════════════════════ */
.modal-content {
    border-radius: var(--rxl);
    border: 1.5px solid var(--gray-200);
}
.modal-header {
    border-bottom: 1.5px solid var(--gray-200);
    padding: 18px 22px;
}
.modal-title i {
    color: var(--green-600);
}
.modal-body {
    padding: 22px;
}
.modal-footer {
    border-top: 1.5px solid var(--gray-200);
    padding: 16px 22px;
}
.form-label {
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray-600);
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
.alert {
    padding: 14px 18px;
    border-radius: var(--rl);
    border: 1.5px solid;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}
.alert-green {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
</style>

<div class="maintenance-page">

    {{-- Breadcrumb --}}
    <div class="breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('housekeeping.index') }}">Housekeeping</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Maintenance</span>
    </div>

    {{-- En-tête --}}
    <div class="page-header anim-2">
        <div class="header-title">
            <span class="header-icon"><i class="fas fa-tools"></i></span>
            <div>
                <h1>Chambres en <em>maintenance</em></h1>
                <p class="header-subtitle">Gestion des chambres en réparation</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('housekeeping.index') }}" class="btn btn-gray">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <button class="btn btn-green" data-bs-toggle="modal" data-bs-target="#addMaintenanceModal">
                <i class="fas fa-plus-circle"></i> Nouvelle maintenance
            </button>
        </div>
    </div>

    {{-- Statistiques --}}
    <div class="stats-grid anim-3">
        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-label">Total maintenance</div>
                <div class="stat-number">{{ $stats['total_maintenance'] ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-tools"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-label">En cours</div>
                <div class="stat-number">{{ $stats['in_progress'] ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-spinner"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-label">Planifiées</div>
                <div class="stat-number">{{ $stats['scheduled'] ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-label">Plus ancienne</div>
                <div class="stat-number" style="font-size:1rem;">
                    @if(isset($stats['longest_maintenance']) && $stats['longest_maintenance'])
                        {{ \Carbon\Carbon::parse($stats['longest_maintenance'])->diffForHumans(['parts' => 1]) }}
                    @else
                        N/A
                    @endif
                </div>
            </div>
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
        </div>
    </div>

    {{-- Répartition par raison --}}
    @if(isset($stats['maintenance_by_reason']) && count($stats['maintenance_by_reason']) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="secondary-card">
                <div class="secondary-header">
                    <i class="fas fa-chart-pie"></i>
                    <strong>Répartition par raison</strong>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($stats['maintenance_by_reason'] as $reason => $count)
                        <span class="badge badge-green">
                            <i class="fas fa-tag"></i> {{ $reason }}: <strong>{{ $count }}</strong>
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Liste des chambres --}}
    <div class="card">
        <div class="card-header">
            <div><i class="fas fa-tools"></i> Chambres en maintenance ({{ $maintenanceRooms->count() }})</div>
            <span class="badge badge-green">{{ now()->format('H:i') }}</span>
        </div>
        <div class="card-body p-0">
            @if($maintenanceRooms->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Chambre</th>
                                <th>Type</th>
                                <th>Raison</th>
                                <th>Début</th>
                                <th>Durée</th>
                                <th>Demandé par</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($maintenanceRooms as $room)
                            @php
                                $start = \Carbon\Carbon::parse($room->maintenance_started_at);
                                $isLong = $start->diffInDays(now()) > 3;
                            @endphp
                            <tr class="{{ $isLong ? 'warning' : '' }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="room-badge">{{ $room->number }}</div>
                                        <div class="room-info">
                                            <div class="room-number">{{ $room->type->name ?? 'Standard' }}</div>
                                            <div class="room-type">Étage {{ $room->floor ?? '?' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-gray">{{ $room->type->name ?? 'Standard' }}</span></td>
                                <td>{{ Str::limit($room->maintenance_reason, 30) }}</td>
                                <td>
                                    <div>{{ $start->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $start->format('H:i') }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $isLong ? 'badge-red' : 'badge-green' }}">
                                        <i class="fas fa-clock"></i> {{ $start->diffForHumans(now(), true) }}
                                    </span>
                                </td>
                                <td>
                                    @php $req = \App\Models\User::find($room->maintenance_requested_by); @endphp
                                    @if($req)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-sm">{{ substr($req->name, 0, 1) }}</div>
                                        <span>{{ $req->name }}</span>
                                    </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-group">
                                        <button class="btn-icon" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $room->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('housekeeping.maintenance-form', $room->id) }}" class="btn-icon">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('housekeeping.end-maintenance', $room->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn-icon" style="color:var(--green-600);" onclick="return confirm('Terminer la maintenance ?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-check-circle" style="color:var(--green-600);"></i>
                    <h4>Aucune chambre en maintenance</h4>
                    <p>Toutes les chambres sont opérationnelles</p>
                    <button class="btn btn-green" data-bs-toggle="modal" data-bs-target="#addMaintenanceModal">
                        <i class="fas fa-plus-circle"></i> Ajouter
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Analyses supplémentaires --}}
    @if($maintenanceRooms->count() > 0)
    <div class="row g-4 mt-3">
        <div class="col-md-6">
            <div class="secondary-card">
                <div class="secondary-header">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Maintenance longue durée (>3 jours)</strong>
                </div>
                <div class="list-group">
                    @php $long = $maintenanceRooms->filter(fn($r) => \Carbon\Carbon::parse($r->maintenance_started_at)->diffInDays(now()) > 3); @endphp
                    @forelse($long as $room)
                        <div class="list-item">
                            <span class="fw-semibold">Chambre {{ $room->number }}</span>
                            <span class="badge badge-red">{{ \Carbon\Carbon::parse($room->maintenance_started_at)->diffForHumans(now(), true) }}</span>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted">Aucune</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="secondary-card">
                <div class="secondary-header">
                    <i class="fas fa-chart-pie"></i>
                    <strong>Répartition par type</strong>
                </div>
                <div class="card-body p-3">
                    @php $byType = $maintenanceRooms->groupBy(fn($r) => $r->type->name ?? 'Inconnu')->map->count(); @endphp
                    @foreach($byType as $type => $count)
                        @php $pct = ($count / $maintenanceRooms->count()) * 100; @endphp
                        <div class="mb-2">
                            <div class="d-flex justify-content-between small">
                                <span>{{ $type }}</span>
                                <span class="fw-bold">{{ $count }}</span>
                            </div>
                            <div class="progress"><div class="progress-bar" style="width:{{ $pct }}%"></div></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

{{-- Modal ajout maintenance --}}
<div class="modal fade" id="addMaintenanceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle" style="color:var(--green-600);"></i> Nouvelle maintenance</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" id="addMaintenanceForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Chambre</label>
                        <select class="form-select" name="room_id" required>
                            <option value="">-- Sélectionner --</option>
                            @php $rooms = \App\Models\Room::where('room_status_id', '!=', 2)->orderBy('number')->get(); @endphp
                            @foreach($rooms as $r)
                                <option value="{{ $r->id }}">Chambre {{ $r->number }} - {{ $r->type->name ?? 'Standard' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Raison</label>
                        <select class="form-select" name="maintenance_reason" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="Électricité">Électricité</option>
                            <option value="Plomberie">Plomberie</option>
                            <option value="Climatisation">Climatisation</option>
                            <option value="Meuble">Meuble</option>
                            <option value="Sécurité">Sécurité</option>
                            <option value="Peinture">Peinture</option>
                            <option value="Sol">Sol</option>
                            <option value="Salle de bain">Salle de bain</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Durée estimée (heures)</label>
                        <input type="number" class="form-control" name="estimated_duration" min="1" max="168" value="4">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="additional_notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-gray" data-bs-dismiss="modal">Annuler</button>
                    <button class="btn btn-green" type="submit">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modals détails --}}
@foreach($maintenanceRooms as $room)
<div class="modal fade" id="detailsModal{{ $room->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-info-circle" style="color:var(--green-600);"></i> Détails - Chambre {{ $room->number }}</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="room-badge">{{ $room->number }}</div>
                            <div>
                                <h6>{{ $room->type->name ?? 'Standard' }}</h6>
                                <small class="text-muted">Étage {{ $room->floor ?? '?' }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <span class="badge badge-red"><i class="fas fa-tools"></i> EN MAINTENANCE</span>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <small class="text-muted">Début</small>
                        <div class="fw-bold">{{ \Carbon\Carbon::parse($room->maintenance_started_at)->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted">Durée</small>
                        <div class="fw-bold">{{ \Carbon\Carbon::parse($room->maintenance_started_at)->diffForHumans(now(), true) }}</div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted">Raison</small>
                        <div class="alert alert-green mt-1">{{ $room->maintenance_reason }}</div>
                    </div>
                    @if($room->additional_notes)
                    <div class="col-12">
                        <small class="text-muted">Notes</small>
                        <div class="bg-light p-3 rounded">{{ $room->additional_notes }}</div>
                    </div>
                    @endif
                    <div class="col-sm-6">
                        <small class="text-muted">Demandé par</small>
                        @php $req = \App\Models\User::find($room->maintenance_requested_by); @endphp
                        @if($req)
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <div class="avatar-lg">{{ substr($req->name, 0, 1) }}</div>
                            <div>{{ $req->name }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-gray" data-bs-dismiss="modal">Fermer</button>
                <a href="{{ route('housekeeping.maintenance-form', $room->id) }}" class="btn btn-green">Modifier</a>
                <form action="{{ route('housekeeping.end-maintenance', $room->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-red" onclick="return confirm('Terminer la maintenance ?')">Terminer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
document.getElementById('addMaintenanceForm')?.addEventListener('submit', function(e) {
    const room = this.querySelector('[name="room_id"]').value;
    const reason = this.querySelector('[name="maintenance_reason"]').value;
    if (!room || !reason) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs');
    } else {
        const action = "{{ route('housekeeping.mark-maintenance', ':roomId') }}".replace(':roomId', room);
        this.setAttribute('action', action);
    }
});

setTimeout(() => window.location.reload(), 120000);
</script>

@endsection