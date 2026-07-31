@extends('template.master')
@section('title', 'Notifications')
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

.notifications-page {
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
.notifications-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .8rem; color: var(--s400);
    margin-bottom: 20px;
}
.notifications-breadcrumb a {
    color: var(--s400); text-decoration: none;
    transition: var(--transition);
}
.notifications-breadcrumb a:hover { color: var(--g600); }
.notifications-breadcrumb .sep { color: var(--s300); }
.notifications-breadcrumb .current { color: var(--s600); font-weight: 500; }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.notifications-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.notifications-brand { display: flex; align-items: center; gap: 14px; }
.notifications-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgb(from var(--g500) r g b / .35);
}
.notifications-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.notifications-header-title em { font-style: normal; color: var(--g600); }
.notifications-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.notifications-header-sub i { color: var(--g500); }
.notifications-header-actions { display: flex; align-items: center; gap: 10px; }

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
    box-shadow: 0 2px 10px rgb(from var(--g500) r g b / .3);
}
.btn-db-primary:hover {
    background: var(--g700); color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgb(from var(--g500) r g b / .35);
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
   TIMELINE
══════════════════════════════════════════════ */
.timelines {
    display: flex; flex-direction: column; gap: 40px;
}

.timeline__group {
    position: relative;
}

.timeline__year {
    display: inline-block;
    font-size: .9rem;
    font-weight: 600;
    color: var(--g600);
    background: var(--g100);
    padding: 6px 20px;
    border-radius: 30px;
    border: 1.5px solid var(--g200);
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: .5px;
}

.timeline__cards {
    display: flex; flex-direction: column; gap: 12px;
    position: relative;
    padding-left: 30px;
}

.timeline__cards::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--g200);
    border-radius: 2px;
}

/* ══════════════════════════════════════════════
   NOTIFICATION CARD
══════════════════════════════════════════════ */
.notification-card {
    background: var(--white);
    border: 1.5px solid var(--s100);
    border-radius: var(--rl);
    padding: 20px;
    position: relative;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
}

.notification-card:hover {
    transform: translateX(5px);
    box-shadow: var(--shadow-md);
    border-color: var(--g200);
}

.notification-card--unread {
    border-left: 4px solid var(--g500);
    background: linear-gradient(to right, var(--g50), var(--white));
}

.notification-card--read {
    border-left: 4px solid var(--s300);
    opacity: 0.8;
}

.notification-card__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.notification-card__time {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .75rem;
    color: var(--s400);
    font-family: var(--mono);
}

.notification-card__time i {
    color: var(--g500);
    font-size: .7rem;
}

.notification-card__badge {
    font-size: .65rem;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: .5px;
}

.badge-unread {
    background: var(--g100);
    color: var(--g700);
    border: 1px solid var(--g200);
}

.badge-read {
    background: var(--s100);
    color: var(--s600);
    border: 1px solid var(--s200);
}

.notification-card__content {
    padding-left: 0;
}

.notification-card__message {
    font-size: .9rem;
    color: var(--s800);
    margin-bottom: 16px;
    line-height: 1.5;
}

.notification-card__footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 16px;
    padding-top: 12px;
    border-top: 1px solid var(--s100);
}

.notification-card__link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 16px;
    background: var(--g50);
    color: var(--g600);
    border: 1.5px solid var(--g200);
    border-radius: var(--r);
    font-size: .75rem;
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition);
}

.notification-card__link:hover {
    background: var(--g100);
    color: var(--g700);
    border-color: var(--g300);
    transform: translateY(-2px);
    text-decoration: none;
}

.notification-card__meta {
    font-size: .7rem;
    color: var(--s400);
    display: flex;
    align-items: center;
    gap: 6px;
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    padding: 48px 24px; text-align: center;
    background: var(--white);
    border: 1.5px solid var(--s100);
    border-radius: var(--rl);
}
.empty-icon {
    width: 64px; height: 64px; background: var(--g50);
    border-radius: 50%; display: flex; align-items: center;
    justify-content: center; font-size: 1.5rem; color: var(--g300);
    margin: 0 auto 16px; border: 2px solid var(--g100);
}
.empty-title {
    font-size: .9rem; font-weight: 600; color: var(--s700);
    margin-bottom: 4px;
}
.empty-text {
    font-size: .75rem; color: var(--s400);
}

/* ══════════════════════════════════════════════
   STAT CARD (pour le header)
══════════════════════════════════════════════ */
.stats-mini {
    display: flex; gap: 10px;
}
.stat-mini-item {
    display: flex; align-items: center; gap: 8px;
    padding: 8px 14px;
    background: var(--white);
    border: 1.5px solid var(--s100);
    border-radius: var(--rl);
}
.stat-mini-dot {
    width: 8px; height: 8px; border-radius: 50%;
}
.dot-unread { background: var(--g500); }
.dot-read { background: var(--s400); }
.stat-mini-label {
    font-size: .7rem; color: var(--s600);
}
.stat-mini-value {
    font-size: .9rem; font-weight: 600; color: var(--s800);
    font-family: var(--mono);
    margin-left: 4px;
}

/* ══════════════════════════════════════════════
   BARRE D'OUTILS : RECHERCHE + FILTRES (issue #198)
══════════════════════════════════════════════ */
.notif-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 12px; margin-bottom: 24px;
}
.notif-search {
    position: relative; flex: 1; min-width: 220px; max-width: 420px;
}
.notif-search i {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    color: var(--s400); font-size: .8rem;
}
.notif-search input {
    width: 100%; padding: 10px 14px 10px 38px;
    border: 1.5px solid var(--s200); border-radius: var(--r);
    font-size: .85rem; font-family: var(--font); background: var(--white);
    transition: var(--transition);
}
.notif-search input:focus {
    outline: none; border-color: var(--g400);
    box-shadow: 0 0 0 3px var(--g100);
}
.notif-filters { display: flex; gap: 6px; flex-wrap: wrap; }
.notif-filter {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 14px; border-radius: 20px;
    border: 1.5px solid var(--s200); background: var(--white);
    color: var(--s600); font-size: .78rem; font-weight: 500;
    cursor: pointer; transition: var(--transition); font-family: var(--font);
}
.notif-filter:hover { border-color: var(--g300); color: var(--g700); }
.notif-filter.active {
    background: var(--g600); color: white; border-color: var(--g600);
    box-shadow: 0 2px 8px rgb(from var(--g500) r g b / .3);
}
.notif-filter .count {
    font-family: var(--mono); font-size: .7rem; opacity: .85;
}
.notif-noresult {
    display: none; padding: 40px 24px; text-align: center;
    color: var(--s400); font-size: .8rem;
}

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media(max-width:768px){
    .notifications-page{ padding: 20px; }
    .notifications-header{ flex-direction: column; align-items: flex-start; }
    .stats-mini{ width: 100%; }
    .timeline__cards{ padding-left: 20px; }
    .timeline__cards::before{ left: 5px; }
    .notif-toolbar{ flex-direction: column; align-items: stretch; }
    .notif-search{ max-width: 100%; }
}
</style>

<div class="notifications-page">
    <!-- Breadcrumb -->
    <div class="notifications-breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Notifications</span>
    </div>

    <!-- Header -->
    <div class="notifications-header anim-2">
        <div class="notifications-brand">
            <div class="notifications-brand-icon"><i class="fas fa-bell"></i></div>
            <div>
                <h1 class="notifications-header-title">Centre de <em>notifications</em></h1>
                <p class="notifications-header-sub">
                    <i class="fas fa-bell me-1"></i> Restez informé des activités importantes
                </p>
            </div>
        </div>
        <div class="notifications-header-actions">
            <div class="stats-mini">
                <div class="stat-mini-item">
                    <span class="stat-mini-dot dot-unread"></span>
                    <span class="stat-mini-label">Nouvelles</span>
                    <span class="stat-mini-value">{{ count($newIds) }}</span>
                </div>
                <div class="stat-mini-item">
                    <span class="stat-mini-dot dot-read"></span>
                    <span class="stat-mini-label">Total</span>
                    <span class="stat-mini-value">{{ $notifications->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    @php $newCount = count($newIds); $readCount = $notifications->count() - $newCount; @endphp

    <!-- Barre d'outils : recherche + filtres (issue #198) -->
    <div class="notif-toolbar anim-2">
        <div class="notif-search">
            <i class="fas fa-search"></i>
            <input type="text" id="notifSearch" placeholder="Rechercher dans les notifications…" autocomplete="off">
        </div>
        <div class="notif-filters" id="notifFilters">
            <button type="button" class="notif-filter active" data-filter="all">
                <i class="fas fa-layer-group"></i> Toutes <span class="count">{{ $notifications->count() }}</span>
            </button>
            <button type="button" class="notif-filter" data-filter="new">
                <i class="fas fa-circle" style="font-size:.5rem;"></i> Nouvelles <span class="count">{{ $newCount }}</span>
            </button>
            <button type="button" class="notif-filter" data-filter="read">
                <i class="fas fa-check"></i> Lues <span class="count">{{ $readCount }}</span>
            </button>
        </div>
    </div>

    <!-- Liste des notifications -->
    <div class="timeline__cards anim-3" id="notifList">
        @forelse ($notifications as $notification)
            @php $isNew = in_array($notification->id, $newIds, true); @endphp
            <div class="notification-card {{ $isNew ? 'notification-card--unread' : 'notification-card--read' }}"
                 data-state="{{ $isNew ? 'new' : 'read' }}"
                 data-text="{{ \Illuminate\Support\Str::lower($notification->data['message'] ?? 'notification') }}">
                <div class="notification-card__header">
                    <div class="notification-card__time">
                        <i class="fas fa-clock"></i>
                        {{ Helper::dateFormatTimeNoYear($notification->created_at) }}
                    </div>
                    @if ($isNew)
                        <span class="notification-card__badge badge-unread">
                            <i class="fas fa-circle me-1" style="font-size:.5rem;"></i>
                            Nouveau
                        </span>
                    @else
                        <span class="notification-card__badge badge-read">
                            <i class="fas fa-check me-1"></i>
                            Lu
                        </span>
                    @endif
                </div>
                <div class="notification-card__content">
                    <p class="notification-card__message">
                        {{ $notification->data['message'] ?? 'Notification' }}
                    </p>
                </div>
                <div class="notification-card__footer">
                    <a href="{{ $notification->data['url'] ?? '#' }}" class="notification-card__link">
                        <i class="fas fa-eye"></i>
                        Voir les détails
                    </a>
                    <span class="notification-card__meta">
                        @if ($isNew)
                            <i class="fas fa-info-circle"></i> Reçue récemment
                        @else
                            <i class="fas fa-check-circle" style="color:var(--g500);"></i> Déjà consultée
                        @endif
                    </span>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <p class="empty-title">Aucune notification</p>
                <p class="empty-text">Vous n'avez pas encore de notifications</p>
            </div>
        @endforelse
    </div>

    <!-- Aucun résultat de recherche/filtre -->
    <div class="notif-noresult" id="notifNoResult">
        <i class="fas fa-magnifying-glass me-1"></i> Aucune notification ne correspond à votre recherche.
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    // Recherche + filtres du centre de notifications (issue #198)
    const search   = document.getElementById('notifSearch');
    const filters  = document.getElementById('notifFilters');
    const list     = document.getElementById('notifList');
    const noResult = document.getElementById('notifNoResult');
    if (!list) return;

    const cards = Array.from(list.querySelectorAll('.notification-card'));
    let activeFilter = 'all';

    function apply() {
        const q = (search.value || '').trim().toLowerCase();
        let visible = 0;

        cards.forEach(card => {
            const state = card.dataset.state;           // 'new' | 'read'
            const text  = card.dataset.text || '';
            const matchFilter = activeFilter === 'all' || state === activeFilter;
            const matchSearch = q === '' || text.includes(q);
            const show = matchFilter && matchSearch;
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (noResult) noResult.style.display = (cards.length && visible === 0) ? 'block' : 'none';
    }

    if (search) search.addEventListener('input', apply);

    if (filters) {
        filters.querySelectorAll('.notif-filter').forEach(btn => {
            btn.addEventListener('click', function () {
                filters.querySelectorAll('.notif-filter').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                activeFilter = this.dataset.filter;
                apply();
            });
        });
    }
})();
</script>
@endpush