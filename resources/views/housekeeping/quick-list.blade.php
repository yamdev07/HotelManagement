@extends('template.master')

@section('title', $statusLabel . ' - Liste rapide')

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

.quick-page {
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
    grid-template-columns: repeat(3, 1fr);
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
}
.stat-left h6 {
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    margin-bottom: 4px;
}
.stat-left h3 {
    font-size: 1.6rem;
    font-weight: 700;
    font-family: var(--mono);
    color: var(--gray-900);
    line-height: 1;
}
.stat-icon {
    font-size: 2rem;
    opacity: .5;
}
.stat-icon.red { color: var(--red-500); }
.stat-icon.green { color: var(--green-600); }

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
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.card-header.red { background: var(--red-500); color: white; }
.card-header.green { background: var(--green-600); color: white; }
.card-header.blue { background: var(--green-600); color: white; }
.card-header i { color: white; }
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

/* ══════════════════════════════════════════════
   ROOM BADGE
══════════════════════════════════════════════ */
.room-badge {
    width: 40px;
    height: 40px;
    border-radius: var(--r);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-family: var(--mono);
    font-size: .9rem;
    margin-right: 12px;
}
.room-badge.red { background: var(--red-500); }
.room-badge.green { background: var(--green-600); }
.room-badge.blue { background: var(--green-600); }
.room-badge.orange { background: var(--green-600); }

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
   ACTION GROUP
══════════════════════════════════════════════ */
.btn-group {
    display: flex;
    gap: 4px;
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
    transition: var(--transition);
}
.btn-icon:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}

/* ══════════════════════════════════════════════
   TILE VIEW
══════════════════════════════════════════════ */
.tile-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    padding: 16px;
}
.tile-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    overflow: hidden;
    transition: var(--transition);
}
.tile-card.red:hover { border-color: var(--red-500); box-shadow: 0 4px 12px rgba(185,28,28,.15); }
.tile-card.green:hover { border-color: var(--green-600); box-shadow: 0 4px 12px rgba(46,133,64,.15); }
.tile-body {
    padding: 16px;
}
.tile-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.tile-number {
    font-size: 1.1rem;
    font-weight: 700;
    font-family: var(--mono);
    color: var(--gray-800);
}
.tile-badge {
    padding: 4px 8px;
    border-radius: 100px;
    font-size: .6rem;
    font-weight: 600;
}
.tile-badge.red { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }
.tile-badge.green { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.tile-meta {
    font-size: .7rem;
    color: var(--gray-500);
    margin-bottom: 4px;
}
.tile-meta i {
    width: 16px;
    color: var(--green-600);
}
.tile-actions {
    display: flex;
    gap: 4px;
    margin-top: 12px;
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
   RESPONSIVE
══════════════════════════════════════════════ */
@media (max-width: 1200px) {
    .tile-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 768px) {
    .quick-page { padding: 16px; }
    .stats-grid { grid-template-columns: 1fr; }
    .tile-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) {
    .tile-grid { grid-template-columns: 1fr; }
}
</style>

<div class="quick-page">

    {{-- Breadcrumb --}}
    <div class="breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('housekeeping.index') }}">Housekeeping</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">{{ $statusLabel }}</span>
    </div>

    {{-- En-tête --}}
    <div class="page-header anim-2">
        <div>
            <div class="header-title">
                <span class="header-icon">
                    @switch($status)
                        @case('dirty') <i class="fas fa-broom"></i> @break
                        @case('cleaning') <i class="fas fa-spinner"></i> @break
                        @case('clean') <i class="fas fa-check-circle"></i> @break
                        @case('occupied') <i class="fas fa-users"></i> @break
                        @case('maintenance') <i class="fas fa-tools"></i> @break
                    @endswitch
                </span>
                <h1>{{ $statusLabel }} <em>({{ $rooms->count() }})</em></h1>
            </div>
            <p class="header-subtitle">Liste rapide des chambres</p>
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

    {{-- Stats --}}
    <div class="stats-grid anim-3">
        <div class="stat-card">
            <div class="stat-left">
                <h6>Total chambres</h6>
                <h3>{{ $rooms->count() }}</h3>
            </div>
            <div class="stat-icon {{ $status == 'dirty' ? 'red' : 'green' }}">
                @switch($status)
                    @case('dirty') <i class="fas fa-broom"></i> @break
                    @case('cleaning') <i class="fas fa-spinner"></i> @break
                    @case('clean') <i class="fas fa-check-circle"></i> @break
                    @case('occupied') <i class="fas fa-users"></i> @break
                    @case('maintenance') <i class="fas fa-tools"></i> @break
                @endswitch
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-left">
                <h6>Par type</h6>
                <h3>{{ $rooms->groupBy('type.name')->count() }}</h3>
            </div>
            <div class="stat-icon"><i class="fas fa-layer-group"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-left">
                <h6>Mis à jour</h6>
                <h3>{{ now()->format('H:i') }}</h3>
            </div>
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
        </div>
    </div>

    {{-- Liste table --}}
    <div class="card">
        <div class="card-header {{ $status == 'dirty' ? 'red' : ($status == 'clean' ? 'green' : 'blue') }}">
            <div><i class="fas fa-list"></i> Liste des chambres - {{ $statusLabel }}</div>
            <span class="badge badge-green">{{ now()->format('d/m/Y H:i') }}</span>
        </div>
        <div class="card-body">
            @if($rooms->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Chambre</th>
                                <th>Type</th>
                                <th>Capacité</th>
                                <th>Prix</th>
                                <th>Dernière activité</th>
                                <th>Client actuel</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rooms as $room)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="room-badge {{ $status == 'dirty' ? 'red' : ($status == 'clean' ? 'green' : 'blue') }}">
                                            {{ $room->number }}
                                        </div>
                                        <div>
                                            <span class="fw-semibold">{{ $room->type->name ?? 'Standard' }}</span>
                                            <small class="d-block text-muted">Étage {{ $room->floor ?? '?' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $room->type->name ?? 'Standard' }}</td>
                                <td><i class="fas fa-users me-1" style="color:var(--green-600);"></i> {{ $room->capacity }}</td>
                                <td><span class="fw-semibold" style="color:var(--green-600);">{{ number_format($room->price, 0, ',', ' ') }}</span> FCFA</td>
                                <td><span class="text-muted">{{ $room->updated_at?->diffForHumans() ?? 'N/A' }}</span></td>
                                <td>
                                    @if($room->activeTransactions->count() > 0)
                                        @php $t = $room->activeTransactions->first(); @endphp
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-sm">{{ substr($t->customer->name, 0, 1) }}</div>
                                            <span>{{ $t->customer->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('availability.room.detail', $room->id) }}" class="btn-icon"><i class="fas fa-eye"></i></a>
                                        @switch($status)
                                            @case('dirty')
                                                <form action="{{ route('housekeeping.start-cleaning', $room->id) }}" method="POST">
                                                    @csrf
                                                    <button class="btn-icon" style="color:var(--green-600);"><i class="fas fa-play"></i></button>
                                                </form>
                                                @break
                                            @case('cleaning')
                                                <form action="{{ route('housekeeping.mark-cleaned', $room->id) }}" method="POST">
                                                    @csrf
                                                    <button class="btn-icon" style="color:var(--green-600);"><i class="fas fa-check"></i></button>
                                                </form>
                                                @break
                                            @case('maintenance')
                                                <form action="{{ route('housekeeping.end-maintenance', $room->id) }}" method="POST">
                                                    @csrf
                                                    <button class="btn-icon" style="color:var(--green-600);"><i class="fas fa-check"></i></button>
                                                </form>
                                                @break
                                        @endswitch
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        @switch($status)
                            @case('dirty') <i class="fas fa-check-circle" style="color:var(--green-600);"></i> @break
                            @case('cleaning') <i class="fas fa-clock" style="color:var(--gray-400);"></i> @break
                            @case('clean') <i class="fas fa-bed" style="color:var(--gray-400);"></i> @break
                            @case('occupied') <i class="fas fa-users" style="color:var(--gray-400);"></i> @break
                            @case('maintenance') <i class="fas fa-check-circle" style="color:var(--green-600);"></i> @break
                        @endswitch
                    </div>
                    <h4>
                        @switch($status)
                            @case('dirty') Aucune chambre à nettoyer @break
                            @case('cleaning') Aucune chambre en nettoyage @break
                            @case('clean') Aucune chambre nettoyée @break
                            @case('occupied') Aucune chambre occupée @break
                            @case('maintenance') Aucune chambre en maintenance @break
                        @endswitch
                    </h4>
                    <p>Toutes les chambres sont en ordre</p>
                    <a href="{{ route('housekeeping.index') }}" class="btn btn-green">Retour</a>
                </div>
            @endif
        </div>
    </div>

    {{-- Vue tuiles --}}
    @if($rooms->count() > 0)
    <div class="card mt-4">
        <div class="card-header blue">
            <i class="fas fa-th"></i> Vue par tuiles ({{ $rooms->count() }})
        </div>
        <div class="card-body p-3">
            <div class="tile-grid">
                @foreach($rooms as $room)
                <div class="tile-card {{ $status == 'dirty' ? 'red' : ($status == 'clean' ? 'green' : '') }}">
                    <div class="tile-body">
                        <div class="tile-header">
                            <span class="tile-number">#{{ $room->number }}</span>
                            <span class="tile-badge {{ $status == 'dirty' ? 'red' : 'green' }}">{{ $room->type->name ?? 'Std' }}</span>
                        </div>
                        <div class="tile-meta"><i class="fas fa-users"></i> {{ $room->capacity }} pers.</div>
                        <div class="tile-meta"><i class="fas fa-money-bill"></i> {{ number_format($room->price, 0, ',', ' ') }} FCFA</div>
                        @if($room->last_cleaned_at)<div class="tile-meta"><i class="fas fa-clock"></i> {{ $room->last_cleaned_at->diffForHumans() }}</div>@endif
                        <div class="tile-actions">
                            <a href="{{ route('availability.room.detail', $room->id) }}" class="btn btn-gray btn-sm flex-grow-1">Détails</a>
                            @switch($status)
                                @case('dirty')
                                    <form action="{{ route('housekeeping.start-cleaning', $room->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-green btn-sm"><i class="fas fa-play"></i></button>
                                    </form>
                                    @break
                                @case('cleaning')
                                @case('maintenance')
                                    <form action="{{ route('housekeeping.mark-cleaned', $room->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-green btn-sm"><i class="fas fa-check"></i></button>
                                    </form>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>

<script>
setTimeout(() => location.reload(), 60000);
</script>

@endsection