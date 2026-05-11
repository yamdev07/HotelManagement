@extends('template.master')

@section('title', 'Housekeeping - Nettoyage des Chambres')

@push('styles')
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

.hk-page {
    background: var(--surface);
    min-height: 100vh;
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
.anim-4 { animation: fadeSlide .4s .24s ease both; }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.hk-header {
    background: var(--white);
    border-bottom: 1.5px solid var(--gray-200);
    padding: 20px 32px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-xs);
}
.hk-header__inner {
    max-width: 1600px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}
.hk-header__title {
    display: flex;
    align-items: center;
    gap: 16px;
}
.hk-header__icon {
    width: 52px;
    height: 52px;
    background: var(--green-600);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.4rem;
    box-shadow: 0 4px 10px rgba(46,133,64,.3);
}
.hk-header__title h1 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 4px;
}
.hk-header__title em {
    font-style: normal;
    color: var(--green-600);
}
.hk-header__title p {
    font-size: .8rem;
    color: var(--gray-500);
}
.hk-header__actions {
    display: flex;
    align-items: center;
    gap: 12px;
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
.btn-red {
    background: var(--red-500);
    color: white;
}
.btn-red:hover {
    background: var(--red-600);
    transform: translateY(-1px);
}
.btn-sm {
    padding: 6px 12px;
    font-size: .7rem;
}
.btn-lg {
    padding: 12px 24px;
    font-size: .9rem;
}

/* ══════════════════════════════════════════════
   STATS GRID
══════════════════════════════════════════════ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
@media (max-width: 1200px) { .stats-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 768px)  { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px)  { .stats-grid { grid-template-columns: 1fr; } }
.stat-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 16px;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}
.stat-card:hover {
    transform: translateY(-2px);
    border-color: var(--green-300);
}
.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--green-600);
}
.stat-card.red::before { background: var(--red-500); }
.stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    font-family: var(--mono);
    color: var(--gray-900);
    line-height: 1;
    margin-bottom: 4px;
}
.stat-label {
    font-size: .65rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    margin-bottom: 8px;
}
.stat-footer {
    font-size: .65rem;
    color: var(--gray-400);
    display: flex;
    align-items: center;
    gap: 4px;
}

/* ══════════════════════════════════════════════
   ACTION BANNER
══════════════════════════════════════════════ */
.action-banner {
    background: var(--green-600);
    border-radius: var(--rxl);
    padding: 20px 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: white;
}
.action-banner h3 {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 4px;
}
.action-banner p {
    font-size: .8rem;
    opacity: .9;
}
.action-banner .btn {
    background: var(--white);
    color: var(--green-700);
}
.action-banner .btn:hover {
    background: var(--green-50);
}

/* ══════════════════════════════════════════════
   SECTION TITLE
══════════════════════════════════════════════ */
.section-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.section-title i {
    color: var(--green-600);
}
.section-badge {
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1.5px solid var(--gray-200);
    padding: 4px 12px;
    border-radius: 100px;
    font-size: .7rem;
    font-weight: 600;
}

/* ══════════════════════════════════════════════
   ROOMS GRID
══════════════════════════════════════════════ */
.rooms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}
.room-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    overflow: hidden;
    transition: var(--transition);
}
.room-card:hover {
    border-color: var(--green-300);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}
.room-header {
    padding: 16px;
    border-bottom: 1.5px solid var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.room-number {
    font-size: 1.2rem;
    font-weight: 700;
    font-family: var(--mono);
    color: var(--gray-800);
}
.room-badge {
    width: 36px;
    height: 36px;
    border-radius: var(--r);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}
.room-badge.red { background: var(--red-50); color: var(--red-500); }
.room-badge.green { background: var(--green-50); color: var(--green-600); }
.room-body {
    padding: 16px;
}
.room-type {
    font-size: .8rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 4px;
}
.room-meta {
    font-size: .7rem;
    color: var(--gray-500);
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}
.room-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 100px;
    font-size: .7rem;
    font-weight: 600;
    margin-bottom: 12px;
}
.room-status.red { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }
.room-status.green { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }

/* ── Clean button ── */
.clean-btn {
    width: 100%;
    padding: 10px;
    border-radius: var(--r);
    border: none;
    font-weight: 600;
    font-size: .75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: pointer;
    transition: var(--transition);
    margin-top: 8px;
}
.clean-btn.red {
    background: var(--red-500);
    color: white;
}
.clean-btn.red:hover {
    background: var(--red-600);
}
.clean-btn.green {
    background: var(--green-50);
    color: var(--green-700);
    border: 1.5px solid var(--green-200);
}
.clean-btn.green:hover {
    background: var(--green-600);
    color: white;
    border-color: var(--green-600);
}

/* ══════════════════════════════════════════════
   SIDE CARDS
══════════════════════════════════════════════ */
.side-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
    margin-bottom: 20px;
}
.side-header {
    padding: 16px 20px;
    border-bottom: 1.5px solid var(--gray-200);
    background: var(--gray-50);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.side-header h3 {
    font-size: .85rem;
    font-weight: 600;
    color: var(--gray-700);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.side-header i {
    color: var(--green-600);
}
.side-badge {
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1.5px solid var(--gray-200);
    padding: 2px 8px;
    border-radius: 100px;
    font-size: .6rem;
    font-weight: 600;
}
.side-body {
    padding: 0;
}
.side-item {
    padding: 16px 20px;
    border-bottom: 1.5px solid var(--gray-200);
    display: flex;
    align-items: center;
    gap: 12px;
}
.side-item:last-child {
    border-bottom: none;
}
.side-item-room {
    font-size: .9rem;
    font-weight: 700;
    font-family: var(--mono);
    color: var(--gray-800);
    min-width: 50px;
}
.side-item-info {
    flex: 1;
}
.side-item-name {
    font-size: .8rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 2px;
}
.side-item-meta {
    font-size: .65rem;
    color: var(--gray-500);
}
.side-item-status {
    font-size: .6rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 100px;
}
.side-item-status.red { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }
.side-item-status.green { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }

/* ══════════════════════════════════════════════
   QUICK ACTIONS
══════════════════════════════════════════════ */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-top: 20px;
}
.quick-btn {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 16px;
    text-align: center;
    text-decoration: none;
    transition: var(--transition);
}
.quick-btn:hover {
    border-color: var(--green-300);
    transform: translateY(-2px);
}
.quick-btn i {
    font-size: 1.4rem;
    color: var(--green-600);
    margin-bottom: 6px;
}
.quick-btn span {
    display: block;
    font-size: .75rem;
    font-weight: 600;
    color: var(--gray-700);
}
.quick-btn small {
    font-size: .6rem;
    color: var(--gray-500);
}

/* ══════════════════════════════════════════════
   ALERT
══════════════════════════════════════════════ */
.alert {
    padding: 14px 18px;
    border-radius: var(--rl);
    margin-bottom: 20px;
    border: 1.5px solid;
    display: flex;
    align-items: center;
    gap: 10px;
}
.alert-green {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    padding: 48px 20px;
    text-align: center;
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
}
.empty-state i {
    font-size: 3rem;
    color: var(--gray-300);
    margin-bottom: 16px;
}
.empty-state h4 {
    font-size: .95rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 4px;
}
.empty-state p {
    color: var(--gray-400);
    font-size: .8rem;
}
</style>
@endpush

@section('content')
<div class="hk-page">

    {{-- HEADER --}}
    <div class="hk-header anim-1">
        <div class="hk-header__inner">
            <div class="hk-header__title">
                <div class="hk-header__icon"><i class="fas fa-broom"></i></div>
                <div>
                    <h1>Housekeeping • <em>Nettoyage</em></h1>
                    <p>Gestion du nettoyage des chambres en un clic</p>
                </div>
            </div>
            <div class="hk-header__actions">
                <a href="{{ route('housekeeping.scan') }}" class="btn btn-gray"><i class="fas fa-qrcode"></i> Scanner</a>
                <a href="{{ route('housekeeping.reports') }}" class="btn btn-green"><i class="fas fa-chart-bar"></i> Rapports</a>
            </div>
        </div>
    </div>

    <div class="hk-container" style="max-width:1600px; margin:0 auto; padding:0 32px 48px;">

        {{-- ALERTES --}}
        @if(session('success'))
        <div class="alert alert-green anim-2">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        {{-- STATS --}}
        <div class="stats-grid anim-3">
            <div class="stat-card red">
                <div class="stat-value">{{ $stats['dirty_rooms'] ?? 0 }}</div>
                <div class="stat-label">À nettoyer</div>
                <div class="stat-footer"><i class="fas fa-broom"></i> Sales</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['clean_rooms'] ?? 0 }}</div>
                <div class="stat-label">Nettoyées</div>
                <div class="stat-footer"><i class="fas fa-check-circle"></i> Prêtes</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['occupied_rooms'] ?? 0 }}</div>
                <div class="stat-label">Occupées</div>
                <div class="stat-footer"><i class="fas fa-user"></i> Clients</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total_rooms'] ?? 0 }}</div>
                <div class="stat-label">Total</div>
                <div class="stat-footer"><i class="fas fa-building"></i> Chambres</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['cleaned_today'] ?? 0 }}</div>
                <div class="stat-label">Aujourd'hui</div>
                <div class="stat-footer"><i class="fas fa-calendar-day"></i> Nettoyées</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['maintenance_rooms'] ?? 0 }}</div>
                <div class="stat-label">Maintenance</div>
                <div class="stat-footer"><i class="fas fa-tools"></i> En réparation</div>
            </div>
        </div>

        {{-- BANNER --}}
        @if(($stats['dirty_rooms'] ?? 0) > 0)
        <div class="action-banner anim-4">
            <div>
                <h3><i class="fas fa-broom"></i> {{ $stats['dirty_rooms'] ?? 0 }} chambre(s) à nettoyer</h3>
                <p>Cliquez sur le bouton vert pour marquer comme nettoyée</p>
            </div>
            <a href="#dirty" class="btn"><i class="fas fa-arrow-down"></i> Voir</a>
        </div>
        @endif

        {{-- GRID --}}
        <div class="row g-4">
            {{-- LEFT COLUMN --}}
            <div class="col-lg-8">

                {{-- SECTION À NETTOYER --}}
                <div class="section-title" id="dirty">
                    <i class="fas fa-broom"></i> À nettoyer
                    <span class="section-badge">{{ $roomsByStatus['dirty']->count() }}</span>
                </div>

                @if($roomsByStatus['dirty']->count() > 0)
                <div class="rooms-grid">
                    @foreach($roomsByStatus['dirty'] as $room)
                    <div class="room-card">
                        <div class="room-header">
                            <div class="room-number">#{{ $room->number }}</div>
                            <div class="room-badge red"><i class="fas fa-broom"></i></div>
                        </div>
                        <div class="room-body">
                            <div class="room-type">{{ $room->type->name ?? 'Standard' }}</div>
                            <div class="room-meta"><i class="fas fa-user"></i> {{ $room->capacity }} pers.</div>
                            <div class="room-status red"><i class="fas fa-exclamation-circle"></i> À nettoyer</div>
                            <form action="{{ route('housekeeping.clean-room', $room->id) }}" method="POST">
                                @csrf
                                <button class="clean-btn red"><i class="fas fa-check-circle"></i> Marquer nettoyée</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state mb-4">
                    <i class="fas fa-check-circle" style="color:var(--green-500);"></i>
                    <h4>Aucune chambre à nettoyer</h4>
                    <p>Toutes les chambres sont propres</p>
                </div>
                @endif

                {{-- SECTION NETTOYÉES AUJOURD'HUI --}}
                <div class="section-title mt-4">
                    <i class="fas fa-check-circle" style="color:var(--green-600);"></i> Nettoyées aujourd'hui
                    <span class="section-badge">{{ $stats['cleaned_today'] ?? 0 }}</span>
                </div>

                @if(isset($roomsCleanedToday) && $roomsCleanedToday->count() > 0)
                <div class="rooms-grid">
                    @foreach($roomsCleanedToday->take(4) as $room)
                    <div class="room-card">
                        <div class="room-header">
                            <div class="room-number">#{{ $room->number }}</div>
                            <div class="room-badge green"><i class="fas fa-check"></i></div>
                        </div>
                        <div class="room-body">
                            <div class="room-type">{{ $room->type->name ?? 'Standard' }}</div>
                            <div class="room-meta"><i class="fas fa-clock"></i> {{ $room->last_cleaned_at ? \Carbon\Carbon::parse($room->last_cleaned_at)->format('H:i') : 'N/A' }}</div>
                            <div class="room-status green"><i class="fas fa-check-circle"></i> Nettoyée</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="col-lg-4">

                {{-- DÉPARTS --}}
                <div class="side-card">
                    <div class="side-header">
                        <h3><i class="fas fa-sign-out-alt"></i> Départs aujourd'hui</h3>
                        <span class="side-badge">{{ $todayDepartures->count() }}</span>
                    </div>
                    <div class="side-body">
                        @if($todayDepartures->count() > 0)
                            @foreach($todayDepartures->take(5) as $d)
                            <div class="side-item">
                                <div class="side-item-room">#{{ $d->room->number }}</div>
                                <div class="side-item-info">
                                    <div class="side-item-name">{{ $d->customer->name ?? 'Client' }}</div>
                                    <div class="side-item-meta"><i class="fas fa-clock"></i> 12h00</div>
                                </div>
                                <div class="side-item-status red">À nettoyer</div>
                            </div>
                            @endforeach
                        @else
                        <div class="p-4 text-center text-muted">Aucun départ</div>
                        @endif
                    </div>
                </div>

                {{-- ARRIVÉES --}}
                <div class="side-card">
                    <div class="side-header">
                        <h3><i class="fas fa-sign-in-alt"></i> Arrivées aujourd'hui</h3>
                        <span class="side-badge">{{ $todayArrivals->count() }}</span>
                    </div>
                    <div class="side-body">
                        @if($todayArrivals->count() > 0)
                            @foreach($todayArrivals->take(5) as $a)
                            <div class="side-item">
                                <div class="side-item-room">#{{ $a->room->number }}</div>
                                <div class="side-item-info">
                                    <div class="side-item-name">{{ $a->customer->name ?? 'Client' }}</div>
                                    <div class="side-item-meta"><i class="fas fa-clock"></i> 14h00</div>
                                </div>
                                <div class="side-item-status green">À préparer</div>
                            </div>
                            @endforeach
                        @else
                        <div class="p-4 text-center text-muted">Aucune arrivée</div>
                        @endif
                    </div>
                </div>

                {{-- ACTIONS RAPIDES --}}
                <div class="quick-actions">
                    <a href="{{ route('housekeeping.to-clean') }}" class="quick-btn">
                        <i class="fas fa-broom"></i>
                        <span>À nettoyer</span>
                        <small>{{ $stats['dirty_rooms'] ?? 0 }}</small>
                    </a>
                    <a href="{{ route('housekeeping.maintenance') }}" class="quick-btn">
                        <i class="fas fa-tools"></i>
                        <span>Maintenance</span>
                        <small>{{ $stats['maintenance_rooms'] ?? 0 }}</small>
                    </a>
                    <a href="{{ route('housekeeping.mobile') }}" class="quick-btn">
                        <i class="fas fa-mobile-alt"></i>
                        <span>Mobile</span>
                        <small>Scanner</small>
                    </a>
                    <a href="{{ route('housekeeping.daily-report') }}" class="quick-btn">
                        <i class="fas fa-file-alt"></i>
                        <span>Rapport</span>
                        <small>Quotidien</small>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.querySelectorAll('.clean-btn.red').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');
        const roomNum = form.closest('.room-card').querySelector('.room-number').textContent.trim();
        Swal.fire({
            title: 'Chambre ' + roomNum,
            text: 'Marquer cette chambre comme nettoyée et prête ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check-circle me-1"></i> Oui, nettoyée !',
            cancelButtonText: 'Annuler',
            confirmButtonColor: '#1e6b2e',
            cancelButtonColor: '#6c757d',
            reverseButtons: true
        }).then(r => { if (r.isConfirmed) form.submit(); });
    });
});
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(a => {
        a.style.transition = 'opacity .5s';
        a.style.opacity = '0';
        setTimeout(() => a.remove(), 500);
    });
}, 4000);
</script>

@endsection