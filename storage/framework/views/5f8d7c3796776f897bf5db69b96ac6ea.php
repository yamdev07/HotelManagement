
<?php $__env->startSection('title', 'Check-in — Dashboard'); ?>
<?php $__env->startSection('content'); ?>

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

.ci-page {
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
   HEADER
══════════════════════════════════════════════ */
.ci-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.ci-brand { display: flex; align-items: center; gap: 14px; }
.ci-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.ci-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.ci-header-title em { font-style: normal; color: var(--g600); }
.ci-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.ci-header-actions { display: flex; align-items: center; gap: 10px; }

.time-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--white);
    border: 1.5px solid var(--s200);
    border-radius: 100px;
    padding: 4px 12px;
    font-size: .75rem;
    color: var(--s600);
}
.time-badge i {
    color: var(--g600);
}

/* ══════════════════════════════════════════════
   BREADCRUMB
══════════════════════════════════════════════ */
.ci-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .8rem;
    color: var(--s400);
    margin-bottom: 20px;
}
.ci-breadcrumb a {
    color: var(--s400);
    text-decoration: none;
    transition: var(--transition);
}
.ci-breadcrumb a:hover {
    color: var(--g600);
}
.ci-breadcrumb .sep {
    color: var(--s300);
}

/* ══════════════════════════════════════════════
   ALERTS
══════════════════════════════════════════════ */
.ci-alert {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    border-radius: var(--rl);
    margin-bottom: 24px;
    border: 1.5px solid transparent;
    animation: fadeSlide .3s ease;
}
.ci-alert-success {
    background: var(--g50);
    border-color: var(--g200);
    color: var(--g700);
}
.ci-alert-error {
    background: #fee2e2;
    border-color: #fecaca;
    color: #b91c1c;
}
.ci-alert-close {
    margin-left: auto;
    background: none;
    border: none;
    color: currentColor;
    opacity: .6;
    cursor: pointer;
    font-size: 1rem;
    transition: var(--transition);
}
.ci-alert-close:hover {
    opacity: 1;
}

/* ══════════════════════════════════════════════
   STAT CARDS
══════════════════════════════════════════════ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 28px;
}
@media (max-width: 1100px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 600px)  { .stats-grid { grid-template-columns: 1fr; } }

.stat-card {
    background: var(--white);
    border-radius: var(--rl);
    padding: 20px 22px;
    border: 1.5px solid var(--s100);
    box-shadow: var(--shadow-xs);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}
.stat-card:hover {
    transform: translateY(-3px);
    border-color: var(--g200);
    box-shadow: var(--shadow-md);
}
.stat-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    background: var(--accent, var(--g400));
    border-radius: 0 0 var(--rl) var(--rl);
}
.stat-card--arrivals   { --accent: var(--g400); }
.stat-card--staying    { --accent: var(--g600); }
.stat-card--departures { --accent: var(--g300); }
.stat-card--available  { --accent: var(--s400); }

.stat-card-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 14px;
}
.stat-card-label {
    font-size: .7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: var(--s400);
}
.stat-card-value {
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--s900);
    line-height: 1;
    font-family: var(--mono);
    letter-spacing: -1px;
}
.stat-card-icon {
    width: 44px; height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.stat-card--arrivals .stat-card-icon   { background: var(--g50); color: var(--g600); }
.stat-card--staying .stat-card-icon    { background: var(--g100); color: var(--g700); }
.stat-card--departures .stat-card-icon { background: var(--g50); color: var(--g500); }
.stat-card--available .stat-card-icon  { background: var(--s100); color: var(--s500); }

.stat-card-meta {
    font-size: .75rem;
    color: var(--s400);
    display: flex;
    align-items: center;
    gap: 6px;
    border-top: 1px solid var(--s100);
    padding-top: 12px;
    margin-top: 12px;
}

/* ══════════════════════════════════════════════
   MAIN GRID
══════════════════════════════════════════════ */
.ci-main-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 20px;
    align-items: start;
}
@media (max-width: 1100px) {
    .ci-main-grid { grid-template-columns: 1fr; }
}

/* ══════════════════════════════════════════════
   CARDS
══════════════════════════════════════════════ */
.ci-card {
    background: var(--white);
    border-radius: var(--rxl);
    border: 1.5px solid var(--s100);
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}
.ci-card:hover {
    border-color: var(--g200);
    box-shadow: var(--shadow-md);
}
.ci-card:last-child { margin-bottom: 0; }

.ci-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px;
    border-bottom: 1.5px solid var(--s100);
    background: var(--white);
    flex-wrap: wrap;
    gap: 12px;
}
.ci-card-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: .95rem;
    font-weight: 600;
    color: var(--s800);
    margin: 0;
}
.ci-card-title i {
    color: var(--g600);
}
.ci-card-badge {
    background: var(--g100);
    color: var(--g700);
    font-size: .7rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 100px;
}

/* ══════════════════════════════════════════════
   DATE GROUPS (arrivées)
══════════════════════════════════════════════ */
.date-group {
    padding: 18px 22px;
    border-bottom: 1.5px solid var(--s100);
}
.date-group:last-child { border-bottom: none; }

.date-group-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    flex-wrap: wrap;
    gap: 10px;
}
.date-group-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .8rem;
    font-weight: 600;
    color: var(--s600);
    text-transform: uppercase;
    letter-spacing: .5px;
}
.date-group-label i {
    color: var(--g500);
}
.date-group-pill {
    background: var(--g50);
    color: var(--g700);
    border: 1.5px solid var(--g200);
    padding: 4px 12px;
    border-radius: 100px;
    font-size: .7rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* ── Reservation row ────────────────────────── */
.res-row {
    display: grid;
    grid-template-columns: 1fr auto auto auto auto;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    border-radius: var(--rl);
    border: 1.5px solid var(--s100);
    margin-bottom: 8px;
    transition: var(--transition);
    background: var(--surface);
}
.res-row:last-child { margin-bottom: 0; }
.res-row:hover {
    background: var(--white);
    border-color: var(--g200);
    transform: translateX(2px);
    box-shadow: var(--shadow-sm);
}

.res-guest {}
.res-guest-name {
    font-size: .88rem;
    font-weight: 600;
    color: var(--s900);
}
.res-guest-phone {
    font-size: .72rem;
    color: var(--s400);
    margin-top: 3px;
}

.res-room-badge {
    background: var(--s100);
    color: var(--s700);
    font-weight: 600;
    padding: 5px 12px;
    border-radius: var(--r);
    font-size: .8rem;
    display: inline-block;
    border: 1.5px solid var(--s200);
    font-family: var(--mono);
}
.res-room-type {
    font-size: .68rem;
    color: var(--s400);
    text-align: center;
    margin-top: 3px;
}

.res-time {
    text-align: center;
    min-width: 70px;
}
.res-time-val {
    font-size: .9rem;
    font-weight: 700;
    color: var(--s800);
    display: block;
}
.res-time-label {
    font-size: .68rem;
    color: var(--s400);
}

.res-nights {
    text-align: center;
    min-width: 70px;
}
.res-nights-val {
    font-size: .85rem;
    font-weight: 600;
    color: var(--s700);
    display: block;
}
.res-nights-label {
    font-size: .68rem;
    color: var(--s400);
}

.date-indicator {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 100px;
    font-size: .62rem;
    font-weight: 600;
    margin-top: 3px;
}
.di-upcoming { background: var(--g100); color: var(--g700); }
.di-waiting  { background: var(--g50); color: var(--g600); }

/* ── Action buttons ─────────────────────────── */
.btn-res {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: var(--r);
    font-size: .75rem;
    font-weight: 500;
    border: 1.5px solid transparent;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    white-space: nowrap;
}
.btn-res-checkin {
    background: var(--g600);
    color: white;
    border-color: var(--g700);
}
.btn-res-checkin:hover {
    background: var(--g700);
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(46,133,64,.25);
}
.btn-res-quick {
    background: var(--white);
    color: var(--g600);
    border-color: var(--g200);
}
.btn-res-quick:hover {
    background: var(--g50);
    color: var(--g700);
    border-color: var(--g300);
    transform: translateY(-1px);
}
.btn-res-view {
    background: var(--white);
    color: var(--s500);
    border-color: var(--s200);
}
.btn-res-view:hover {
    background: var(--s50);
    color: var(--s700);
    border-color: var(--s300);
    transform: translateY(-1px);
}

/* ══════════════════════════════════════════════
   RIGHT COLUMN — Guest cards
══════════════════════════════════════════════ */
.guest-card {
    padding: 16px 18px;
    border-bottom: 1.5px solid var(--s100);
    transition: var(--transition);
}
.guest-card:last-child { border-bottom: none; }
.guest-card:hover {
    background: var(--g50);
}

.guest-card-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 10px;
    gap: 10px;
}
.guest-card-name {
    font-size: .88rem;
    font-weight: 600;
    color: var(--s900);
}
.guest-card-meta {
    font-size: .72rem;
    color: var(--s400);
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 4px;
    flex-wrap: wrap;
}
.guest-card-room-badge {
    background: var(--s100);
    color: var(--s600);
    padding: 3px 8px;
    border-radius: 5px;
    font-weight: 600;
    font-size: .7rem;
}
.unpaid-alert {
    background: #fee2e2;
    border-left: 3px solid #b91c1c;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: .7rem;
    margin-top: 6px;
    color: #b91c1c;
}

.guest-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}
.guest-card-departure {
    font-size: .72rem;
    color: var(--s500);
    display: flex;
    align-items: center;
    gap: 5px;
}
.guest-card-actions {
    display: flex;
    gap: 5px;
}

.btn-ghost-sm {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1.5px solid var(--s200);
    background: var(--white);
    color: var(--s500);
    font-size: .75rem;
    transition: var(--transition);
    text-decoration: none;
    cursor: pointer;
}
.btn-ghost-sm:hover {
    background: var(--g50);
    border-color: var(--g300);
    color: var(--g700);
    transform: translateY(-1px);
}

.tag-largesse {
    background: var(--g100);
    color: var(--g700);
    padding: 2px 8px;
    border-radius: 100px;
    font-size: .6rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 3px;
}
.tag-urgent {
    background: #fee2e2;
    color: #b91c1c;
    padding: 2px 8px;
    border-radius: 100px;
    font-size: .6rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 3px;
}

/* ══════════════════════════════════════════════
   DEPARTURE ROWS
══════════════════════════════════════════════ */
.dep-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 18px;
    border-bottom: 1.5px solid var(--s100);
    gap: 12px;
    transition: var(--transition);
}
.dep-row:last-child { border-bottom: none; }
.dep-row:hover {
    background: var(--g50);
}
.dep-row-info {}
.dep-row-name {
    font-size: .88rem;
    font-weight: 600;
    color: var(--s900);
}
.dep-row-room {
    font-size: .72rem;
    color: var(--s400);
    margin-top: 3px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.dep-row-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}
.dep-time-badge {
    background: var(--s100);
    color: var(--s700);
    font-weight: 600;
    padding: 4px 10px;
    border-radius: var(--r);
    font-size: .78rem;
    border: 1.5px solid var(--s200);
}
.dep-time-badge-largesse {
    background: var(--g100);
    color: var(--g700);
    border-color: var(--g200);
}
.dep-actions {
    display: flex;
    gap: 5px;
}

.btn-dep-invoice {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 10px;
    border-radius: var(--r);
    font-size: .72rem;
    font-weight: 500;
    border: 1.5px solid var(--s200);
    background: var(--white);
    color: var(--s500);
    text-decoration: none;
    transition: var(--transition);
}
.btn-dep-invoice:hover {
    background: var(--s50);
    border-color: var(--s300);
    color: var(--s700);
    transform: translateY(-1px);
}
.btn-dep-checkout {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 12px;
    border-radius: var(--r);
    font-size: .72rem;
    font-weight: 600;
    border: 1.5px solid var(--g200);
    background: var(--g50);
    color: var(--g700);
    text-decoration: none;
    transition: var(--transition);
}
.btn-dep-checkout:hover {
    background: var(--g600);
    border-color: var(--g600);
    color: white;
    transform: translateY(-1px);
}
.btn-dep-checkout-late {
    background: #fff7ed;
    border-color: #fed7aa;
    color: #c2410c;
}
.btn-dep-checkout-late:hover {
    background: #c2410c;
    border-color: #c2410c;
    color: white;
}

/* ══════════════════════════════════════════════
   CARD FOOTER
══════════════════════════════════════════════ */
.ci-card-footer {
    padding: 14px 22px;
    border-top: 1.5px solid var(--s100);
    background: var(--surface);
    text-align: center;
}
.btn-ci-footer {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    border-radius: var(--r);
    font-size: .78rem;
    font-weight: 500;
    color: var(--g700);
    background: var(--white);
    border: 1.5px solid var(--g200);
    text-decoration: none;
    transition: var(--transition);
}
.btn-ci-footer:hover {
    background: var(--g50);
    border-color: var(--g300);
    color: var(--g800);
    transform: translateY(-1px);
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.ci-empty {
    text-align: center;
    padding: 48px 24px;
}
.ci-empty-icon {
    width: 72px;
    height: 72px;
    background: var(--g50);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: var(--g300);
    margin: 0 auto 16px;
    border: 2px solid var(--g100);
}
.ci-empty-title {
    font-size: .95rem;
    font-weight: 600;
    color: var(--s700);
    margin-bottom: 6px;
}
.ci-empty-text {
    font-size: .8rem;
    color: var(--s400);
    margin-bottom: 18px;
}

/* ══════════════════════════════════════════════
   TOAST
══════════════════════════════════════════════ */
.ci-toast-wrap {
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 8px;
    pointer-events: none;
}
.ci-toast {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 18px;
    background: var(--white);
    border-radius: var(--rl);
    box-shadow: var(--shadow-lg);
    border-left: 4px solid var(--g600);
    font-size: .8rem;
    font-weight: 500;
    color: var(--s800);
    pointer-events: auto;
    animation: slideIn .25s ease;
    min-width: 280px;
    max-width: 380px;
}
.ci-toast-error {
    border-left-color: #b91c1c;
}
@keyframes slideIn {
    from { opacity: 0; transform: translateX(20px); }
    to   { opacity: 1; transform: translateX(0); }
}
</style>

<div class="ci-page">

    
    <div class="ci-toast-wrap" id="toast-container"></div>

    
    <div class="ci-breadcrumb anim-1">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Check-in</span>
    </div>

    
    <div class="ci-header anim-2">
        <div class="ci-brand">
            <div class="ci-brand-icon"><i class="fas fa-door-open"></i></div>
            <div>
                <h1 class="ci-header-title">Gestion des <em>Check-in</em></h1>
                <div class="ci-header-sub">
                    <span>Arrivées, séjours et départs</span>
                    <span class="time-badge">
                        <i class="fas fa-clock"></i> Check-in 12h | Check-out 12h (largesse 14h)
                    </span>
                </div>
            </div>
        </div>
        <div class="ci-header-actions">
            <a href="<?php echo e(route('checkin.search')); ?>" class="btn-db btn-db-ghost">
                <i class="fas fa-search fa-xs"></i> Rechercher
            </a>
            <a href="<?php echo e(route('checkin.direct')); ?>" class="btn-db btn-db-primary">
                <i class="fas fa-user-plus fa-xs"></i> Check-in Direct
            </a>
        </div>
    </div>

    
    <?php if(session('success')): ?>
    <div class="ci-alert ci-alert-success anim-2">
        <i class="fas fa-check-circle"></i>
        <span><?php echo session('success'); ?></span>
        <button class="ci-alert-close" onclick="this.parentElement.remove()">✕</button>
    </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div class="ci-alert ci-alert-error anim-2">
        <i class="fas fa-exclamation-circle"></i>
        <span><?php echo e(session('error')); ?></span>
        <button class="ci-alert-close" onclick="this.parentElement.remove()">✕</button>
    </div>
    <?php endif; ?>

    
    <div class="stats-grid anim-3">
        <div class="stat-card stat-card--arrivals">
            <div class="stat-card-top">
                <div>
                    <div class="stat-card-label">Arrivées aujourd'hui</div>
                    <div class="stat-card-value"><?php echo e($stats['arrivals_today']); ?></div>
                </div>
                <div class="stat-card-icon"><i class="fas fa-calendar-day"></i></div>
            </div>
            <div class="stat-card-meta">
                <i class="fas fa-clock fa-xs"></i>
                Prévues pour <?php echo e($today->format('d/m/Y')); ?>

            </div>
        </div>

        <div class="stat-card stat-card--staying">
            <div class="stat-card-top">
                <div>
                    <div class="stat-card-label">Séjours en cours</div>
                    <div class="stat-card-value"><?php echo e($stats['currently_checked_in']); ?></div>
                </div>
                <div class="stat-card-icon"><i class="fas fa-bed"></i></div>
            </div>
            <div class="stat-card-meta">
                <i class="fas fa-hotel fa-xs"></i>
                Clients dans l'hôtel
            </div>
        </div>

        <div class="stat-card stat-card--departures">
            <div class="stat-card-top">
                <div>
                    <div class="stat-card-label">Départs aujourd'hui</div>
                    <div class="stat-card-value"><?php echo e($stats['departures_today']); ?></div>
                </div>
                <div class="stat-card-icon"><i class="fas fa-sign-out-alt"></i></div>
            </div>
            <div class="stat-card-meta">
                <i class="fas fa-door-open fa-xs"></i>
                Chambres à libérer
            </div>
        </div>

        <div class="stat-card stat-card--available">
            <div class="stat-card-top">
                <div>
                    <div class="stat-card-label">Chambres disponibles</div>
                    <div class="stat-card-value"><?php echo e($stats['available_rooms']); ?></div>
                </div>
                <div class="stat-card-icon"><i class="fas fa-door-closed"></i></div>
            </div>
            <div class="stat-card-meta">
                <i class="fas fa-check-circle fa-xs"></i>
                Prêtes à l'accueil
            </div>
        </div>
    </div>

    
    <div class="ci-main-grid">

        
        <div class="anim-4">
            <div class="ci-card">
                <div class="ci-card-header">
                    <h3 class="ci-card-title">
                        <i class="fas fa-calendar-check"></i>
                        Réservations à venir
                    </h3>
                    <span class="ci-card-badge"><?php echo e($upcomingReservations->count()); ?> groupe(s)</span>
                </div>

                <?php if($upcomingReservations->isEmpty()): ?>
                <div class="ci-empty">
                    <div class="ci-empty-icon"><i class="fas fa-calendar-times"></i></div>
                    <p class="ci-empty-title">Aucune arrivée prévue</p>
                    <p class="ci-empty-text">Pas de réservations pour aujourd'hui ni demain</p>
                    <a href="<?php echo e(route('checkin.search')); ?>" class="btn-db btn-db-primary" style="margin-top:8px;">
                        <i class="fas fa-search fa-xs"></i> Chercher des réservations
                    </a>
                </div>
                <?php else: ?>
                    <?php $__currentLoopData = $upcomingReservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $reservations): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="date-group">
                        <div class="date-group-header">
                            <span class="date-group-label">
                                <i class="fas fa-calendar-day"></i>
                                <?php echo e(\Carbon\Carbon::parse($date)->translatedFormat('l d F Y')); ?>

                            </span>
                            <span class="date-group-pill">
                                <i class="fas fa-ticket-alt fa-xs"></i>
                                <?php echo e($reservations->count()); ?> réservation<?php echo e($reservations->count() > 1 ? 's' : ''); ?>

                            </span>
                        </div>

                        <?php $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $now = \Carbon\Carbon::now();
                            $checkIn = \Carbon\Carbon::parse($transaction->check_in);
                            $checkInDate = $checkIn->copy()->startOfDay();
                            $checkInTime = $checkIn->copy()->setTime(12, 0, 0);
                            
                            $canCheckin = $transaction->status == 'reservation' && 
                                          $now->isSameDay($checkInDate) && 
                                          $now->gte($checkInTime);
                            $checkinTooEarly = $transaction->status == 'reservation' && 
                                              $now->isSameDay($checkInDate) && 
                                              $now->lt($checkInTime);
                            $checkinFuture = $transaction->status == 'reservation' && 
                                           !$now->isSameDay($checkInDate);
                        ?>
                        <div class="res-row">
                            <div class="res-guest">
                                <div class="res-guest-name"><?php echo e($transaction->customer->name); ?></div>
                                <div class="res-guest-phone">
                                    <i class="fas fa-phone fa-xs" style="color:var(--s300);"></i>
                                    <?php echo e($transaction->customer->phone); ?>

                                </div>
                            </div>

                            <div style="text-align:center;">
                                <span class="res-room-badge"><?php echo e($transaction->room->number); ?></span>
                                <div class="res-room-type"><?php echo e($transaction->room->type->name ?? 'N/A'); ?></div>
                            </div>

                            <div class="res-time">
                                <span class="res-time-val">12:00</span>
                                <span class="res-time-label">Arrivée</span>
                                <?php if($now->lt($checkInDate)): ?>
                                    <div class="date-indicator di-upcoming">J-<?php echo e($now->diffInDays($checkInDate)); ?></div>
                                <?php elseif($checkinTooEarly): ?>
                                    <div class="date-indicator di-waiting">Attente 12h</div>
                                <?php endif; ?>
                            </div>

                            <div class="res-nights">
                                <span class="res-nights-val"><?php echo e($transaction->nights); ?>n</span>
                                <span class="res-nights-label">→ <?php echo e($transaction->check_out->format('d/m')); ?></span>
                            </div>

                            <div class="res-actions">
                                <?php if($canCheckin): ?>
                                <form action="<?php echo e(route('transaction.mark-arrived', $transaction)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-res btn-res-checkin" onclick="return confirm('Confirmer l\'arrivée de <?php echo e($transaction->customer->name); ?> ?')">
                                        <i class="fas fa-door-open"></i> Check-in
                                    </button>
                                </form>
                                <?php elseif($checkinTooEarly): ?>
                                <span class="btn-res btn-res-checkin" style="opacity:0.5;cursor:not-allowed;" title="Check-in possible à partir de 12h">
                                    <i class="fas fa-clock"></i> Check-in
                                </span>
                                <?php elseif($checkinFuture): ?>
                                <span class="btn-res btn-res-checkin" style="opacity:0.5;cursor:not-allowed;" title="Arrivée prévue le <?php echo e($checkInDate->format('d/m/Y')); ?>">
                                    <i class="fas fa-calendar"></i> Check-in
                                </span>
                                <?php endif; ?>
                                
                                <button onclick="quickCheckIn(<?php echo e($transaction->id); ?>, this)" class="btn-res btn-res-quick">
                                    <i class="fas fa-bolt"></i>
                                </button>
                                
                                <a href="<?php echo e(route('transaction.show', $transaction)); ?>" class="btn-res btn-res-view">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div class="ci-card-footer">
                        <a href="<?php echo e(route('transaction.index')); ?>?status=reservation" class="btn-ci-footer">
                            <i class="fas fa-list fa-xs"></i> Voir toutes les réservations
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="anim-5">

            
            <div class="ci-card">
                <div class="ci-card-header">
                    <h3 class="ci-card-title">
                        <i class="fas fa-users"></i>
                        Dans l'hôtel
                    </h3>
                    <span class="ci-card-badge"><?php echo e($activeGuests->count()); ?> client<?php echo e($activeGuests->count() > 1 ? 's' : ''); ?></span>
                </div>

                <?php if($activeGuests->isEmpty()): ?>
                <div class="ci-empty" style="padding: 32px 20px;">
                    <div class="ci-empty-icon" style="width:56px;height:56px;font-size:1.4rem;"><i class="fas fa-users-slash"></i></div>
                    <p class="ci-empty-title" style="font-size:.85rem;">Aucun client en ce moment</p>
                </div>
                <?php else: ?>
                    <?php $__currentLoopData = $activeGuests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $totalPrice = $transaction->getTotalPrice();
                        $totalPayment = $transaction->getTotalPayment();
                        $remaining = $totalPrice - $totalPayment;
                        $isFullyPaid = $remaining <= 0;
                        
                        $now = \Carbon\Carbon::now();
                        $checkOutTime = \Carbon\Carbon::parse($transaction->check_out)->setTime(12, 0, 0);
                        $checkOutLargess = $checkOutTime->copy()->setTime(14, 0, 0);
                        $canCheckout = $isFullyPaid && $now->gte($checkOutTime) && $now->lte($checkOutLargess);
                        $isLate = $now->gt($checkOutLargess);
                        $isInLargess = $now->gte($checkOutTime) && $now->lte($checkOutLargess);
                    ?>
                    <div class="guest-card">
                        <div class="guest-card-top">
                            <div>
                                <div class="guest-card-name"><?php echo e($transaction->customer->name); ?></div>
                                <div class="guest-card-meta">
                                    <span><i class="fas fa-door-closed"></i> Ch. <?php echo e($transaction->room->number); ?></span>
                                    <span class="guest-card-room-badge"><?php echo e($transaction->room->type->name ?? 'N/A'); ?></span>
                                </div>
                                <?php if(!$isFullyPaid): ?>
                                <div class="unpaid-alert">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Solde: <?php echo e(number_format($remaining, 0, ',', ' ')); ?> FCFA
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="guest-card-footer">
                            <div class="guest-card-departure">
                                <i class="fas fa-calendar-minus" style="color:var(--g400);"></i>
                                Départ <?php echo e($transaction->check_out->format('d/m')); ?> à 12h00
                                <?php if($isInLargess): ?>
                                    <span class="tag-largesse"><i class="fas fa-gift fa-xs"></i> largesse</span>
                                <?php endif; ?>
                                <?php if($isLate): ?>
                                    <span class="tag-urgent"><i class="fas fa-exclamation-triangle fa-xs"></i> Dépassé</span>
                                <?php endif; ?>
                            </div>
                            <div class="guest-card-actions">
                                <a href="<?php echo e(route('transaction.show', $transaction)); ?>" class="btn-ghost-sm" title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if(!$isFullyPaid): ?>
                                <a href="<?php echo e(route('transaction.payment.create', $transaction)); ?>" class="btn-ghost-sm" title="Paiement">
                                    <i class="fas fa-money-bill-wave-alt"></i>
                                </a>
                                <?php endif; ?>
                                <?php if($canCheckout): ?>
                                <form action="<?php echo e(route('transaction.mark-departed', $transaction)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-ghost-sm" title="Check-out (largesse)" onclick="return confirm('Confirmer le départ de <?php echo e($transaction->customer->name); ?> ?')">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </form>
                                <?php elseif($isLate): ?>
                                <span class="btn-ghost-sm" style="opacity:0.5;cursor:not-allowed;background:var(--g50);" title="Départ après 14h - Prolongation nécessaire">
                                    <i class="fas fa-hourglass-end"></i>
                                </span>
                                <?php else: ?>
                                <span class="btn-ghost-sm" style="opacity:0.5;cursor:not-allowed;" title="Check-out possible à partir de 12h">
                                    <i class="fas fa-clock"></i>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

                <?php if($activeGuests->isNotEmpty()): ?>
                <div class="ci-card-footer">
                    <a href="<?php echo e(route('transaction.index')); ?>?status=active" class="btn-ci-footer">
                        <i class="fas fa-list fa-xs"></i> Voir tous les séjours
                    </a>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="ci-card anim-6">
                <div class="ci-card-header">
                    <h3 class="ci-card-title">
                        <i class="fas fa-sign-out-alt"></i>
                        Départs aujourd'hui
                    </h3>
                    <span class="ci-card-badge"><?php echo e($todayDepartures->count()); ?></span>
                </div>

                <?php if($todayDepartures->isEmpty()): ?>
                <div class="ci-empty" style="padding: 32px 20px;">
                    <div class="ci-empty-icon" style="width:56px;height:56px;font-size:1.4rem;background:var(--g50);color:var(--g400);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <p class="ci-empty-title" style="font-size:.85rem;">Aucun départ prévu</p>
                </div>
                <?php else: ?>
                    <?php $__currentLoopData = $todayDepartures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $totalPrice = $transaction->getTotalPrice();
                        $totalPayment = $transaction->getTotalPayment();
                        $remaining = $totalPrice - $totalPayment;
                        $isFullyPaid = $remaining <= 0;
                        
                        $now = \Carbon\Carbon::now();
                        $checkOutTime = \Carbon\Carbon::parse($transaction->check_out)->setTime(12, 0, 0);
                        $checkOutLargess = $checkOutTime->copy()->setTime(14, 0, 0);
                        $canCheckout = $isFullyPaid && $now->gte($checkOutTime) && $now->lte($checkOutLargess);
                        $isLate = $now->gt($checkOutLargess);
                        $isInLargess = $now->gte($checkOutTime) && $now->lte($checkOutLargess);
                    ?>
                    <div class="dep-row">
                        <div class="dep-row-info">
                            <div class="dep-row-name"><?php echo e($transaction->customer->name); ?></div>
                            <div class="dep-row-room">
                                <i class="fas fa-door-closed"></i>
                                Chambre <?php echo e($transaction->room->number); ?>

                                <?php if(!$isFullyPaid): ?>
                                <span class="badge badge-danger" style="background:#fee2e2;color:#b91c1c;padding:2px 6px;border-radius:4px;font-size:.6rem;margin-left:5px;">Impayé</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="dep-row-right">
                            <span class="dep-time-badge <?php echo e($isInLargess ? 'dep-time-badge-largesse' : ''); ?>">12:00</span>
                            <div class="dep-actions">
                                <a href="<?php echo e(route('transaction.show', $transaction)); ?>" class="btn-dep-invoice">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                                <?php if($canCheckout): ?>
                                <form action="<?php echo e(route('transaction.mark-departed', $transaction)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-dep-checkout" onclick="return confirm('Confirmer le départ ?')">
                                        <i class="fas fa-sign-out-alt"></i> Out
                                    </button>
                                </form>
                                <?php elseif($isLate && $isFullyPaid): ?>
                                <form action="<?php echo e(route('transaction.mark-departed', $transaction)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="override" value="1">
                                    <button type="submit" class="btn-dep-checkout btn-dep-checkout-late" onclick="return confirm('Dérogation après 14h ? Confirmer le départ ?')">
                                        <i class="fas fa-gavel"></i> Dérog.
                                    </button>
                                </form>
                                <?php elseif(!$isFullyPaid): ?>
                                <a href="<?php echo e(route('transaction.payment.create', $transaction)); ?>" class="btn-dep-checkout" style="background:#fee2e2;color:#b91c1c;border-color:#fecaca;">
                                    <i class="fas fa-money-bill-wave-alt"></i> Payer
                                </a>
                                <?php else: ?>
                                <span class="btn-dep-checkout" style="opacity:0.5;cursor:not-allowed;" title="Check-out à partir de 12h">
                                    <i class="fas fa-clock"></i> 12h
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div class="ci-card-footer">
                        <a href="<?php echo e(route('transaction.index')); ?>?check_out=<?php echo e($today->format('Y-m-d')); ?>" class="btn-ci-footer">
                            <i class="fas fa-door-open fa-xs"></i> Gérer tous les départs
                        </a>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script>
/* ── Toast helper ─────────────────────────────── */
function showToast(msg, type = 'success') {
    const wrap = document.getElementById('toast-container');
    const t = document.createElement('div');
    t.className = 'ci-toast' + (type === 'error' ? ' ci-toast-error' : '');
    const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
    t.innerHTML = `<i class="${icon}" style="font-size:1rem;flex-shrink:0"></i><span>${msg}</span>`;
    wrap.appendChild(t);
    setTimeout(() => t.style.opacity = '0', 2800);
    setTimeout(() => t.remove(), 3100);
}

/* ── Quick check-in (rapide) ───────────────────── */
function quickCheckIn(id, btn) {
    if (!confirm('Effectuer un check-in rapide ?')) return;

    const orig = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;

    fetch(`/checkin/${id}/quick`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Check-in effectué avec succès !');
            setTimeout(() => location.reload(), 1600);
        } else {
            showToast(data.error || 'Échec du check-in', 'error');
            btn.innerHTML = orig;
            btn.disabled = false;
        }
    })
    .catch(() => {
        showToast('Une erreur est survenue', 'error');
        btn.innerHTML = orig;
        btn.disabled = false;
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/checkin/index.blade.php ENDPATH**/ ?>