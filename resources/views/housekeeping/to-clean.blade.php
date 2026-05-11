@extends('template.master')

@section('title', 'Chambres à Nettoyer')

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

.clean-page {
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
.stat-left h3 {
    font-size: 1.6rem;
    font-weight: 700;
    font-family: var(--mono);
    color: var(--gray-900);
    line-height: 1;
    margin-bottom: 2px;
}
.stat-left small {
    font-size: .6rem;
    color: var(--gray-400);
}
.stat-icon {
    font-size: 2rem;
    opacity: .5;
}
.stat-icon.red { color: var(--red-500); }
.stat-icon.orange { color: var(--red-500); }
.stat-icon.blue { color: var(--green-600); }

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
    padding: 14px 20px;
    border-bottom: 1.5px solid var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.card-header.red { background: var(--red-500); color: white; }
.card-header i { color: white; }
.card-header .badge {
    background: rgba(255,255,255,.2);
    color: white;
    border: 1.5px solid rgba(255,255,255,.2);
}
.card-body {
    padding: 0;
}
.card-footer {
    padding: 14px 20px;
    border-top: 1.5px solid var(--gray-200);
    background: var(--gray-50);
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
.badge-red { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }
.badge-green { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.badge-blue { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.badge-gray { background: var(--gray-100); color: var(--gray-600); border: 1.5px solid var(--gray-200); }
.badge-orange { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }

/* ══════════════════════════════════════════════
   ROOM BADGE
══════════════════════════════════════════════ */
.room-icon {
    width: 32px;
    height: 32px;
    border-radius: var(--r);
    background: var(--red-50);
    color: var(--red-500);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
}

/* ══════════════════════════════════════════════
   DROPDOWN
══════════════════════════════════════════════ */
.dropdown-menu {
    border-radius: var(--rl);
    border: 1.5px solid var(--gray-200);
    padding: 6px;
    box-shadow: var(--shadow-sm);
}
.dropdown-item {
    border-radius: var(--r);
    padding: 6px 12px;
    font-size: .75rem;
    transition: var(--transition);
}
.dropdown-item:hover {
    background: var(--green-50);
    color: var(--green-700);
}
.dropdown-item i {
    width: 18px;
    color: var(--green-600);
}
.dropdown-divider {
    border-top: 1.5px solid var(--gray-200);
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
    color: var(--green-600);
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
   INSTRUCTION CARD
══════════════════════════════════════════════ */
.instruction-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 20px;
    height: 100%;
}
.instruction-header {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .9rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 16px;
}
.instruction-header i {
    color: var(--green-600);
}
.instruction-list {
    list-style: none;
    padding: 0;
}
.instruction-list li {
    margin-bottom: 10px;
    padding-left: 20px;
    position: relative;
}
.instruction-list li:before {
    content: "•";
    color: var(--green-600);
    font-weight: bold;
    position: absolute;
    left: 4px;
}
.alert {
    padding: 16px 20px;
    border-radius: var(--rl);
    border: 1.5px solid;
}
.alert-orange {
    background: var(--red-50);
    border-color: var(--red-100);
    color: var(--red-500);
}
.alert-orange i {
    color: var(--red-500);
}
</style>

<div class="clean-page">

    {{-- Breadcrumb --}}
    <div class="breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('housekeeping.index') }}">Housekeeping</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">À nettoyer</span>
    </div>

    {{-- En-tête --}}
    <div class="page-header anim-2">
        <div>
            <div class="header-title">
                <span class="header-icon"><i class="fas fa-broom"></i></span>
                <h1>Chambres à <em>nettoyer</em></h1>
            </div>
            <p class="header-subtitle">Liste complète des chambres nécessitant un nettoyage</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('housekeeping.index') }}" class="btn btn-gray">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <a href="{{ route('housekeeping.mobile') }}" class="btn btn-green">
                <i class="fas fa-mobile-alt"></i> Vue Mobile
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-grid anim-3">
        <div class="stat-card">
            <div class="stat-left">
                <h6>À nettoyer</h6>
                <h3>{{ $stats['total_to_clean'] }}</h3>
                <small><i class="fas fa-clock"></i> {{ now()->format('H:i') }}</small>
            </div>
            <div class="stat-icon red"><i class="fas fa-broom"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-left">
                <h6>Sales</h6>
                <h3>{{ $stats['dirty'] }}</h3>
                <small>statut sale</small>
            </div>
            <div class="stat-icon orange"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-left">
                <h6>Départs</h6>
                <h3>{{ $stats['departing_today'] }}</h3>
                <small>aujourd'hui</small>
            </div>
            <div class="stat-icon blue"><i class="fas fa-sign-out-alt"></i></div>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="card">
        <div class="card-header red">
            <div><i class="fas fa-list"></i> Liste des chambres à nettoyer</div>
            <span class="badge">{{ $rooms->count() }} chambres</span>
        </div>
        <div class="card-body">
            @if($rooms->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Chambre</th>
                                <th>Type</th>
                                <th>Statut</th>
                                <th>Client</th>
                                <th>Départ</th>
                                <th>Dernier nettoyage</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rooms as $room)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="room-icon"><i class="fas fa-door-closed"></i></div>
                                        <div>
                                            <strong>{{ $room->number }}</strong>
                                            <small class="d-block text-muted">Étage {{ substr($room->number,0,1) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-gray">{{ $room->type->name ?? 'Standard' }}</span></td>
                                <td>
                                    @if($room->room_status_id == 3)
                                        <span class="badge badge-red"><i class="fas fa-broom"></i> À nettoyer</span>
                                    @elseif($room->activeTransactions->count() > 0)
                                        <span class="badge badge-blue"><i class="fas fa-users"></i> Occupée</span>
                                    @else
                                        <span class="badge badge-orange"><i class="fas fa-question"></i> Inconnu</span>
                                    @endif
                                </td>
                                <td>
                                    @if($room->activeTransactions->count() > 0)
                                        @foreach($room->activeTransactions as $t)
                                            <i class="fas fa-user text-muted me-1"></i>{{ $t->customer->name ?? 'Client' }}
                                        @endforeach
                                    @else
                                        <span class="text-muted">Vacante</span>
                                    @endif
                                </td>
                                <td>
                                    @if($room->activeTransactions->count() > 0)
                                        @foreach($room->activeTransactions as $t)
                                            @if($t->check_out)
                                                <span class="badge badge-blue">{{ $t->check_out->format('H:i') }}</span>
                                                <small class="d-block text-muted">{{ $t->check_out->format('d/m') }}</small>
                                            @endif
                                        @endforeach
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($room->last_cleaned_at)
                                        <span class="text-muted">{{ $room->last_cleaned_at->format('d/m H:i') }}</span>
                                    @else
                                        <span class="badge badge-red"><i class="fas fa-exclamation-circle"></i> Jamais</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <form action="{{ route('housekeeping.start-cleaning', $room->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-red btn-sm"><i class="fas fa-broom"></i> Démarrer</button>
                                        </form>
                                        <div class="dropdown">
                                            <button class="btn btn-gray btn-sm" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="{{ route('room.show', $room->id) }}"><i class="fas fa-eye"></i> Détails</a></li>
                                                <li><a class="dropdown-item" href="{{ route('housekeeping.maintenance-form', $room->id) }}"><i class="fas fa-tools"></i> Maintenance</a></li>
                                                <li>
                                                    <form action="{{ route('housekeeping.mark-inspection', $room->id) }}" method="POST">
                                                        @csrf
                                                        <button class="dropdown-item"><i class="fas fa-clipboard-check"></i> Inspection</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-check-circle"></i></div>
                    <h4>Excellent travail !</h4>
                    <p>Toutes les chambres sont propres</p>
                    <a href="{{ route('housekeeping.index') }}" class="btn btn-green">Retour</a>
                </div>
            @endif
        </div>
        @if($rooms->count() > 0)
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted"><i class="fas fa-info-circle"></i> Cliquez sur Démarrer pour commencer</small>
                <button class="btn btn-gray btn-sm"><i class="fas fa-print"></i> Imprimer</button>
            </div>
        </div>
        @endif
    </div>

    {{-- Instructions --}}
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div class="instruction-card">
                <div class="instruction-header">
                    <i class="fas fa-info-circle"></i> Procédure de nettoyage
                </div>
                <ul class="instruction-list">
                    <li>Vérifier le matériel de nettoyage</li>
                    <li>Scanner le QR code de la chambre</li>
                    <li>Suivre le protocole standard</li>
                    <li>Signaler tout problème</li>
                    <li>Marquer comme nettoyée</li>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="instruction-card">
                <div class="instruction-header">
                    <i class="fas fa-exclamation-triangle"></i> Chambres prioritaires
                </div>
                <div class="alert alert-orange">
                    <div class="d-flex gap-3">
                        <i class="fas fa-star fa-2x"></i>
                        <div>
                            <h6 class="fw-semibold">Ordre de priorité</h6>
                            <ol class="mb-0">
                                <li>Départs matinaux</li>
                                <li>Chambres sales</li>
                                <li>Arrivées prévues</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.querySelectorAll('form[action*="start-cleaning"]').forEach(f => {
    f.addEventListener('submit', e => {
        e.preventDefault();
        const form = f;
        Swal.fire({
            title: 'Démarrer le nettoyage ?',
            text: 'Confirmer la prise en charge de cette chambre.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-broom me-1"></i> Oui, démarrer',
            cancelButtonText: 'Annuler',
            confirmButtonColor: '#1e6b2e',
            reverseButtons: true
        }).then(r => { if (r.isConfirmed) form.submit(); });
    });
});
setTimeout(() => location.reload(), 60000);
</script>

@endsection