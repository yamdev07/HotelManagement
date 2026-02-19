
<?php $__env->startSection('title', 'Check-in — Dashboard'); ?>
<?php $__env->startSection('content'); ?>

<style>
/* ══════════════════════════════════════════════
   VARIABLES & BASE
══════════════════════════════════════════════ */
:root {
    --green-950: #052e16;
    --green-900: #064e3b;
    --green-800: #065f46;
    --green-600: #059669;
    --green-500: #10b981;
    --green-400: #34d399;
    --green-100: #d1fae5;
    --green-50:  #ecfdf5;

    --amber-500: #f59e0b;
    --amber-100: #fef3c7;
    --amber-50:  #fffbeb;

    --blue-600:  #2563eb;
    --blue-100:  #dbeafe;
    --blue-50:   #eff6ff;

    --slate-900: #0f172a;
    --slate-700: #334155;
    --slate-500: #64748b;
    --slate-400: #94a3b8;
    --slate-200: #e2e8f0;
    --slate-100: #f1f5f9;
    --slate-50:  #f8fafc;

    --red-500:   #ef4444;
    --red-100:   #fee2e2;

    --shadow-sm:  0 1px 3px rgba(0,0,0,.07), 0 1px 2px rgba(0,0,0,.05);
    --shadow-md:  0 4px 12px rgba(0,0,0,.08), 0 2px 6px rgba(0,0,0,.05);
    --shadow-lg:  0 10px 30px rgba(0,0,0,.1),  0 4px 12px rgba(0,0,0,.06);
    --radius-sm:  8px;
    --radius-md:  12px;
    --radius-lg:  16px;

    --transition: all .2s cubic-bezier(.4,0,.2,1);
}

/* ── Page wrapper ───────────────────────────── */
.ci-page {
    padding: 28px 28px 48px;
    background: var(--slate-50);
    min-height: 100vh;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

/* ── Fade-in animation ──────────────────────── */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}
.anim-1 { animation: fadeUp .35s ease both; }
.anim-2 { animation: fadeUp .35s .08s ease both; }
.anim-3 { animation: fadeUp .35s .16s ease both; }
.anim-4 { animation: fadeUp .35s .22s ease both; }
.anim-5 { animation: fadeUp .35s .28s ease both; }
.anim-6 { animation: fadeUp .35s .34s ease both; }

/* ── Breadcrumb ─────────────────────────────── */
.ci-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .78rem;
    color: var(--slate-400);
    margin-bottom: 18px;
    flex-wrap: wrap;
}
.ci-breadcrumb a { color: var(--slate-400); text-decoration: none; transition: var(--transition); }
.ci-breadcrumb a:hover { color: var(--green-600); }
.ci-breadcrumb .sep { color: var(--slate-300); }
.ci-breadcrumb .current { color: var(--slate-600); font-weight: 500; }

/* ── Page header ────────────────────────────── */
.ci-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    margin-bottom: 28px;
}
.ci-header-left {}
.ci-header-title {
    display: flex; align-items: center; gap: 12px;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--slate-900);
    line-height: 1.2;
    margin: 0;
}
.ci-header-icon {
    width: 44px; height: 44px;
    background: linear-gradient(135deg, var(--green-800), var(--green-600));
    border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem;
    box-shadow: 0 4px 12px rgba(5,150,105,.3);
    flex-shrink: 0;
}
.ci-header-subtitle {
    color: var(--slate-500);
    font-size: .875rem;
    margin: 6px 0 0 56px;
}
.ci-header-actions {
    display: flex; gap: 10px; flex-wrap: wrap; align-items: center;
}

/* ── Buttons ────────────────────────────────── */
.btn-ci {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px;
    border-radius: var(--radius-sm);
    font-size: .85rem;
    font-weight: 500;
    border: none; cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    white-space: nowrap;
    line-height: 1;
}
.btn-ci-primary {
    background: linear-gradient(135deg, var(--green-800), var(--green-600));
    color: white;
    box-shadow: 0 4px 12px rgba(5,150,105,.3);
}
.btn-ci-primary:hover {
    background: linear-gradient(135deg, var(--green-900), var(--green-800));
    box-shadow: 0 6px 16px rgba(5,150,105,.4);
    transform: translateY(-1px);
    color: white;
    text-decoration: none;
}
.btn-ci-outline {
    background: white;
    color: var(--slate-700);
    border: 1.5px solid var(--slate-200);
    box-shadow: var(--shadow-sm);
}
.btn-ci-outline:hover {
    background: var(--slate-50);
    border-color: var(--slate-300);
    color: var(--slate-900);
    transform: translateY(-1px);
    text-decoration: none;
}

/* ── Alerts ─────────────────────────────────── */
.ci-alert {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 14px 18px;
    border-radius: var(--radius-md);
    margin-bottom: 16px;
    border: 1px solid transparent;
    font-size: .875rem;
}
.ci-alert-success {
    background: var(--green-50);
    border-color: var(--green-100);
    color: #065f46;
}
.ci-alert-error {
    background: var(--red-100);
    border-color: #fecaca;
    color: #991b1b;
}
.ci-alert-icon { font-size: 1rem; margin-top: 1px; flex-shrink: 0; }
.ci-alert-close {
    margin-left: auto; background: none; border: none;
    cursor: pointer; color: inherit; opacity: .5;
    padding: 0 4px; font-size: 1rem; line-height: 1;
    transition: var(--transition);
}
.ci-alert-close:hover { opacity: 1; }

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
    background: white;
    border-radius: var(--radius-md);
    padding: 20px 22px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--slate-200);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}
.stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--card-accent, var(--green-500));
    border-radius: 4px 4px 0 0;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}
.stat-card--arrivals   { --card-accent: var(--blue-600); }
.stat-card--staying    { --card-accent: var(--green-500); }
.stat-card--departures { --card-accent: var(--amber-500); }
.stat-card--available  { --card-accent: #8b5cf6; }

.stat-card-top {
    display: flex; justify-content: space-between; align-items: flex-start;
    margin-bottom: 12px;
}
.stat-card-label {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: var(--slate-500);
    margin-bottom: 6px;
}
.stat-card-value {
    font-size: 2rem;
    font-weight: 800;
    color: var(--slate-900);
    line-height: 1;
}
.stat-card-icon {
    width: 46px; height: 46px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
    opacity: .85;
}
.stat-card--arrivals .stat-card-icon   { background: var(--blue-50);  color: var(--blue-600); }
.stat-card--staying .stat-card-icon    { background: var(--green-50); color: var(--green-600); }
.stat-card--departures .stat-card-icon { background: var(--amber-50); color: var(--amber-500); }
.stat-card--available .stat-card-icon  { background: #f5f3ff;         color: #7c3aed; }

.stat-card-meta {
    font-size: .78rem;
    color: var(--slate-400);
    display: flex; align-items: center; gap: 5px;
    border-top: 1px solid var(--slate-100);
    padding-top: 10px;
    margin-top: 10px;
}

/* ══════════════════════════════════════════════
   MAIN LAYOUT
══════════════════════════════════════════════ */
.ci-main-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
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
    background: white;
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--slate-200);
    overflow: hidden;
    margin-bottom: 20px;
}
.ci-card:last-child { margin-bottom: 0; }

.ci-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 22px;
    border-bottom: 1px solid var(--slate-100);
    gap: 12px;
}
.ci-card-title {
    display: flex; align-items: center; gap: 9px;
    font-size: .9rem;
    font-weight: 700;
    color: var(--slate-800);
    margin: 0;
}
.ci-card-title-dot {
    width: 9px; height: 9px;
    border-radius: 50%;
    flex-shrink: 0;
}
.ci-card-badge {
    font-size: .72rem;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 20px;
    white-space: nowrap;
}

/* ── Badge colors ───────────────────────────── */
.badge-green  { background: var(--green-100); color: #065f46; }
.badge-amber  { background: var(--amber-100); color: #92400e; }
.badge-blue   { background: var(--blue-100);  color: #1e40af; }
.badge-purple { background: #f5f3ff;          color: #5b21b6; }
.badge-slate  { background: var(--slate-100); color: var(--slate-700); }

/* ══════════════════════════════════════════════
   DATE GROUP (upcoming reservations)
══════════════════════════════════════════════ */
.date-group {
    padding: 18px 22px;
    border-bottom: 1px solid var(--slate-100);
}
.date-group:last-child { border-bottom: none; }

.date-group-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 14px;
    flex-wrap: wrap; gap: 8px;
}
.date-group-label {
    display: flex; align-items: center; gap: 9px;
    font-size: .8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: var(--slate-600);
}
.date-group-pill {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--blue-50);
    color: var(--blue-600);
    font-size: .7rem;
    font-weight: 700;
    padding: 2px 10px;
    border-radius: 20px;
    border: 1px solid var(--blue-100);
}

/* ── Reservation row ────────────────────────── */
.res-row {
    display: grid;
    grid-template-columns: 1fr auto auto auto auto;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--slate-100);
    margin-bottom: 8px;
    transition: var(--transition);
    background: var(--slate-50);
}
.res-row:last-child { margin-bottom: 0; }
.res-row:hover {
    background: white;
    border-color: var(--green-100);
    box-shadow: var(--shadow-sm);
    transform: translateX(2px);
}

.res-guest { min-width: 0; }
.res-guest-name {
    font-size: .88rem;
    font-weight: 600;
    color: var(--slate-900);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.res-guest-phone {
    font-size: .75rem;
    color: var(--slate-400);
    margin-top: 2px;
}

.res-room-badge {
    background: var(--slate-100);
    color: var(--slate-700);
    font-size: .78rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 6px;
    white-space: nowrap;
}

.res-time {
    text-align: center;
    min-width: 56px;
}
.res-time-val {
    font-size: .9rem; font-weight: 700; color: var(--slate-800);
    display: block;
}
.res-time-label {
    font-size: .68rem; color: var(--slate-400); margin-top: 1px;
}

.res-nights {
    text-align: center; min-width: 52px;
}
.res-nights-val {
    font-size: .88rem; font-weight: 600; color: var(--slate-700);
    display: block;
}
.res-nights-label { font-size: .68rem; color: var(--slate-400); margin-top: 1px; }

.res-actions { display: flex; gap: 6px; flex-shrink: 0; }

/* ── Action buttons ─────────────────────────── */
.btn-res {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 11px;
    border-radius: 6px;
    font-size: .78rem;
    font-weight: 500;
    border: 1px solid transparent;
    cursor: pointer;
    text-decoration: none;
    transition: var(--transition);
    white-space: nowrap;
    line-height: 1;
}
.btn-res:hover { transform: translateY(-1px); text-decoration: none; }
.btn-res-checkin {
    background: linear-gradient(135deg, var(--green-800), var(--green-600));
    color: white; border-color: var(--green-700);
    box-shadow: 0 2px 6px rgba(5,150,105,.25);
}
.btn-res-checkin:hover {
    box-shadow: 0 4px 10px rgba(5,150,105,.35);
    color: white;
}
.btn-res-quick {
    background: white; color: var(--green-700);
    border-color: var(--green-200);
}
.btn-res-quick:hover { background: var(--green-50); color: var(--green-800); border-color: var(--green-300); }
.btn-res-view {
    background: white; color: var(--slate-600);
    border-color: var(--slate-200);
}
.btn-res-view:hover { background: var(--slate-50); color: var(--slate-900); border-color: var(--slate-300); }

/* ── Empty state ────────────────────────────── */
.ci-empty {
    display: flex; flex-direction: column; align-items: center;
    padding: 48px 24px; text-align: center;
}
.ci-empty-icon {
    width: 72px; height: 72px;
    background: var(--slate-100);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.8rem; color: var(--slate-300);
    margin-bottom: 16px;
}
.ci-empty-title { font-size: .95rem; font-weight: 600; color: var(--slate-700); margin-bottom: 6px; }
.ci-empty-text  { font-size: .82rem; color: var(--slate-400); max-width: 260px; }

/* ══════════════════════════════════════════════
   RIGHT COLUMN — Guest cards
══════════════════════════════════════════════ */
.guest-card {
    display: flex; flex-direction: column;
    padding: 14px 16px;
    border-bottom: 1px solid var(--slate-100);
    transition: var(--transition);
    gap: 10px;
}
.guest-card:last-child { border-bottom: none; }
.guest-card:hover { background: var(--slate-50); }

.guest-card-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 8px; }
.guest-card-name {
    font-size: .875rem;
    font-weight: 600;
    color: var(--slate-900);
    line-height: 1.2;
}
.guest-card-meta {
    font-size: .75rem; color: var(--slate-400);
    display: flex; align-items: center; gap: 5px; margin-top: 3px;
    flex-wrap: wrap;
}
.guest-card-meta span { display: flex; align-items: center; gap: 4px; }
.guest-card-room {
    font-size: .78rem; font-weight: 700;
    background: var(--slate-100); color: var(--slate-600);
    padding: 2px 8px; border-radius: 5px; flex-shrink: 0;
}
.guest-card-footer {
    display: flex; justify-content: space-between; align-items: center; gap: 8px;
}
.guest-card-departure {
    font-size: .75rem; color: var(--slate-500);
    display: flex; align-items: center; gap: 5px;
}
.guest-card-actions { display: flex; gap: 6px; }

.btn-ghost-sm {
    display: inline-flex; align-items: center; justify-content: center;
    width: 30px; height: 30px;
    border-radius: 6px;
    border: 1px solid var(--slate-200);
    background: white;
    color: var(--slate-500);
    font-size: .8rem;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}
.btn-ghost-sm:hover { border-color: var(--slate-300); color: var(--slate-900); background: var(--slate-50); transform: translateY(-1px); }
.btn-ghost-sm-green:hover { border-color: var(--green-200); color: var(--green-700); background: var(--green-50); }

/* Departure "urgent" tag */
.tag-urgent {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--amber-50); color: #92400e;
    font-size: .68rem; font-weight: 700;
    padding: 2px 7px; border-radius: 5px;
    border: 1px solid var(--amber-100);
}

/* ── Departure row ──────────────────────────── */
.dep-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 13px 16px;
    border-bottom: 1px solid var(--slate-100);
    gap: 10px;
    transition: var(--transition);
}
.dep-row:last-child { border-bottom: none; }
.dep-row:hover { background: var(--amber-50); }

.dep-row-info {}
.dep-row-name { font-size: .875rem; font-weight: 600; color: var(--slate-900); }
.dep-row-room { font-size: .75rem; color: var(--slate-400); margin-top: 2px; display: flex; align-items: center; gap: 4px; }
.dep-row-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
.dep-time-badge {
    font-size: .8rem; font-weight: 700;
    background: var(--amber-100); color: #92400e;
    padding: 4px 9px; border-radius: 6px;
    border: 1px solid #fde68a;
}
.dep-actions { display: flex; gap: 6px; }

.btn-dep-invoice {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 10px; border-radius: 6px; font-size: .75rem; font-weight: 500;
    border: 1px solid var(--slate-200); background: white; color: var(--slate-600);
    cursor: pointer; text-decoration: none; transition: var(--transition);
}
.btn-dep-invoice:hover { background: var(--slate-50); border-color: var(--slate-300); color: var(--slate-900); text-decoration: none; }
.btn-dep-checkout {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 10px; border-radius: 6px; font-size: .75rem; font-weight: 600;
    border: 1px solid var(--green-200); background: var(--green-50); color: var(--green-700);
    cursor: pointer; text-decoration: none; transition: var(--transition);
}
.btn-dep-checkout:hover {
    background: linear-gradient(135deg, var(--green-800), var(--green-600));
    color: white; border-color: transparent;
    box-shadow: 0 3px 8px rgba(5,150,105,.3);
    transform: translateY(-1px);
}

/* ── Card footer ────────────────────────────── */
.ci-card-footer {
    padding: 12px 18px;
    border-top: 1px solid var(--slate-100);
    background: var(--slate-50);
    text-align: center;
}
.btn-ci-footer {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 16px; border-radius: var(--radius-sm);
    font-size: .78rem; font-weight: 500;
    color: var(--green-700); background: transparent;
    border: 1px solid var(--green-200);
    text-decoration: none; cursor: pointer; transition: var(--transition);
}
.btn-ci-footer:hover {
    background: var(--green-50);
    border-color: var(--green-300);
    color: var(--green-800);
    text-decoration: none;
}

/* ── Toast / feedback overlay ───────────────── */
.ci-toast-wrap {
    position: fixed; top: 24px; right: 24px; z-index: 9999;
    display: flex; flex-direction: column; gap: 8px;
    pointer-events: none;
}
.ci-toast {
    display: flex; align-items: center; gap: 10px;
    padding: 13px 18px;
    background: white;
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-lg);
    border-left: 4px solid var(--green-500);
    font-size: .875rem; font-weight: 500;
    color: var(--slate-800);
    pointer-events: auto;
    animation: slideIn .25s ease;
    min-width: 280px; max-width: 380px;
}
.ci-toast-error { border-left-color: var(--red-500); }
@keyframes slideIn {
    from { opacity: 0; transform: translateX(20px); }
    to   { opacity: 1; transform: translateX(0); }
}
</style>

<div class="ci-page">
    <!-- Toast container -->
    <div class="ci-toast-wrap" id="toast-container"></div>

    <!-- Breadcrumb -->
    <nav class="ci-breadcrumb anim-1">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Check-in</span>
    </nav>

    <!-- Header -->
    <div class="ci-header anim-2">
        <div class="ci-header-left">
            <h1 class="ci-header-title">
                <span class="ci-header-icon"><i class="fas fa-door-open"></i></span>
                Gestion des Check-in
            </h1>
            <p class="ci-header-subtitle">Arrivées, séjours en cours et départs du <?php echo e($today->format('d/m/Y')); ?></p>
        </div>
        <div class="ci-header-actions">
            <a href="<?php echo e(route('checkin.search')); ?>" class="btn-ci btn-ci-outline">
                <i class="fas fa-search"></i> Rechercher
            </a>
            <a href="<?php echo e(route('checkin.direct')); ?>" class="btn-ci btn-ci-primary">
                <i class="fas fa-user-plus"></i> Check-in Direct
            </a>
        </div>
    </div>

    <!-- Alerts session -->
    <?php if(session('success')): ?>
    <div class="ci-alert ci-alert-success anim-2">
        <span class="ci-alert-icon"><i class="fas fa-check-circle"></i></span>
        <span><?php echo session('success'); ?></span>
        <button class="ci-alert-close" onclick="this.parentElement.remove()">✕</button>
    </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div class="ci-alert ci-alert-error anim-2">
        <span class="ci-alert-icon"><i class="fas fa-exclamation-circle"></i></span>
        <span><?php echo e(session('error')); ?></span>
        <button class="ci-alert-close" onclick="this.parentElement.remove()">✕</button>
    </div>
    <?php endif; ?>

    <!-- ─── Stat Cards ──────────────────────── -->
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
                <i class="fas fa-clock"></i>
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
                <i class="fas fa-hotel"></i>
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
                <i class="fas fa-door-open"></i>
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
                <i class="fas fa-check-circle"></i>
                Prêtes à l'accueil
            </div>
        </div>
    </div>

    <!-- ─── Main Grid ──────────────────────── -->
    <div class="ci-main-grid">

        <!-- LEFT — Upcoming reservations -->
        <div class="anim-4">
            <div class="ci-card">
                <div class="ci-card-header">
                    <h2 class="ci-card-title">
                        <span class="ci-card-title-dot" style="background:var(--blue-600)"></span>
                        Réservations à venir
                        <span style="font-size:.75rem;font-weight:400;color:var(--slate-400)">Aujourd'hui & Demain</span>
                    </h2>
                    <span class="ci-card-badge badge-blue"><?php echo e($upcomingReservations->count()); ?> groupe(s)</span>
                </div>

                <?php if($upcomingReservations->isEmpty()): ?>
                <div class="ci-empty">
                    <div class="ci-empty-icon"><i class="fas fa-calendar-times"></i></div>
                    <p class="ci-empty-title">Aucune arrivée prévue</p>
                    <p class="ci-empty-text">Pas de réservations pour aujourd'hui ni demain</p>
                    <a href="<?php echo e(route('checkin.search')); ?>" class="btn-ci btn-ci-primary" style="margin-top:16px;font-size:.82rem;">
                        <i class="fas fa-search"></i> Chercher des réservations
                    </a>
                </div>
                <?php else: ?>
                    <?php $__currentLoopData = $upcomingReservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $reservations): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="date-group">
                        <div class="date-group-header">
                            <span class="date-group-label">
                                <i class="fas fa-calendar-day" style="color:var(--blue-600)"></i>
                                <?php echo e(\Carbon\Carbon::parse($date)->translatedFormat('l d F Y')); ?>

                            </span>
                            <span class="date-group-pill">
                                <i class="fas fa-ticket-alt"></i>
                                <?php echo e($reservations->count()); ?> réservation<?php echo e($reservations->count() > 1 ? 's' : ''); ?>

                            </span>
                        </div>

                        <?php $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $today = \Carbon\Carbon::today();
                            $checkIn = \Carbon\Carbon::parse($transaction->check_in);
                            $checkInDate = $checkIn->copy()->startOfDay();
                        ?>
                        <div class="res-row">
                            <!-- Guest -->
                            <div class="res-guest">
                                <div class="res-guest-name"><?php echo e($transaction->customer->name); ?></div>
                                <div class="res-guest-phone">
                                    <i class="fas fa-phone fa-xs" style="color:var(--slate-300)"></i>
                                    <?php echo e($transaction->customer->phone); ?>

                                </div>
                            </div>

                            <!-- Room -->
                            <div style="text-align:center;">
                                <span class="res-room-badge">N° <?php echo e($transaction->room->number); ?></span>
                                <div style="font-size:.68rem;color:var(--slate-400);margin-top:3px;"><?php echo e($transaction->room->type->name ?? 'N/A'); ?></div>
                            </div>

                            <!-- Time -->
                            <div class="res-time">
                                <span class="res-time-val"><?php echo e($transaction->check_in->format('H:i')); ?></span>
                                <span class="res-time-label">Arrivée</span>
                                <?php if($today->lessThan($checkInDate)): ?>
                                    <div class="date-indicator upcoming" style="font-size:.6rem;background:var(--amber-100);color:#92400e;padding:2px 4px;border-radius:4px;margin-top:2px;">J-<?php echo e($today->diffInDays($checkInDate)); ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Nights -->
                            <div class="res-nights">
                                <span class="res-nights-val"><?php echo e($transaction->nights); ?>n</span>
                                <span class="res-nights-label">→ <?php echo e($transaction->check_out->format('d/m')); ?></span>
                            </div>

                            <!-- Actions -->
                            <div class="res-actions">
                                <?php if($transaction->status == 'reservation' && $today->greaterThanOrEqualTo($checkInDate)): ?>
                                <form action="<?php echo e(route('transaction.mark-arrived', $transaction)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-res btn-res-checkin" onclick="return confirm('Confirmer l\'arrivée de <?php echo e($transaction->customer->name); ?> ?')">
                                        <i class="fas fa-door-open"></i> Check-in
                                    </button>
                                </form>
                                <?php elseif($today->lessThan($checkInDate)): ?>
                                <span class="btn-res btn-res-checkin" style="opacity:0.5;cursor:not-allowed;" title="Arrivée prévue le <?php echo e($checkInDate->format('d/m/Y')); ?>">
                                    <i class="fas fa-clock"></i> Check-in
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
                <?php endif; ?>
            </div>
        </div>

        <!-- RIGHT column -->
        <div class="anim-5">

            <!-- Clients dans l'hôtel -->
            <div class="ci-card">
                <div class="ci-card-header">
                    <h2 class="ci-card-title">
                        <span class="ci-card-title-dot" style="background:var(--green-500)"></span>
                        Dans l'hôtel
                    </h2>
                    <span class="ci-card-badge badge-green"><?php echo e($activeGuests->count()); ?> client<?php echo e($activeGuests->count() > 1 ? 's' : ''); ?></span>
                </div>

                <?php if($activeGuests->isEmpty()): ?>
                <div class="ci-empty" style="padding: 32px 20px;">
                    <div class="ci-empty-icon" style="width:52px;height:52px;font-size:1.3rem;"><i class="fas fa-users-slash"></i></div>
                    <p class="ci-empty-title" style="font-size:.85rem;">Aucun client en ce moment</p>
                </div>
                <?php else: ?>
                    <?php $__currentLoopData = $activeGuests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $totalPrice = $transaction->getTotalPrice();
                        $totalPayment = $transaction->getTotalPayment();
                        $remaining = $totalPrice - $totalPayment;
                        $isFullyPaid = $remaining <= 0;
                        $checkOut = \Carbon\Carbon::parse($transaction->check_out);
                        $today = \Carbon\Carbon::today();
                    ?>
                    <div class="guest-card">
                        <div class="guest-card-top">
                            <div>
                                <div class="guest-card-name"><?php echo e($transaction->customer->name); ?></div>
                                <div class="guest-card-meta">
                                    <span><i class="fas fa-door-closed"></i> Ch. <?php echo e($transaction->room->number); ?></span>
                                    <span style="color:var(--slate-300)">·</span>
                                    <span><i class="fas fa-clock"></i> <?php echo e($transaction->check_in->diffForHumans()); ?></span>
                                </div>
                                <?php if(!$isFullyPaid): ?>
                                <div class="unpaid-alert" style="background:var(--red-100);padding:2px 8px;border-radius:4px;margin-top:4px;font-size:.7rem;">
                                    <i class="fas fa-exclamation-triangle text-danger me-1"></i>
                                    <span class="text-danger">Solde: <?php echo e(number_format($remaining, 0, ',', ' ')); ?> CFA</span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <span class="guest-card-room"><?php echo e($transaction->room->type->name ?? 'N/A'); ?></span>
                        </div>
                        <div class="guest-card-footer">
                            <div class="guest-card-departure">
                                <i class="fas fa-calendar-minus" style="color:var(--amber-500)"></i>
                                Départ <?php echo e($transaction->check_out->format('d/m à H:i')); ?>

                                <?php if($today->greaterThanOrEqualTo($checkOut->copy()->startOfDay())): ?>
                                    <span class="tag-urgent" style="margin-left:5px;">Dépassé</span>
                                <?php endif; ?>
                            </div>
                            <div class="guest-card-actions">
                                <a href="<?php echo e(route('transaction.show', $transaction)); ?>" class="btn-ghost-sm" title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if(!$isFullyPaid): ?>
                                <a href="<?php echo e(route('transaction.payment.create', $transaction)); ?>" class="btn-ghost-sm" style="color:var(--success);" title="Paiement">
                                    <i class="fas fa-money-bill-wave-alt"></i>
                                </a>
                                <?php endif; ?>
                                <?php if($isFullyPaid): ?>
                                <form action="<?php echo e(route('transaction.mark-departed', $transaction)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-ghost-sm btn-ghost-sm-green" title="Check-out" onclick="return confirm('Confirmer le départ de <?php echo e($transaction->customer->name); ?> ?')">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </form>
                                <?php else: ?>
                                <span class="btn-ghost-sm" style="opacity:0.5;cursor:not-allowed;" title="Paiement requis avant départ">
                                    <i class="fas fa-sign-out-alt"></i>
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
                        <i class="fas fa-list"></i> Voir tous les séjours
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Départs du jour -->
            <div class="ci-card anim-6">
                <div class="ci-card-header">
                    <h2 class="ci-card-title">
                        <span class="ci-card-title-dot" style="background:var(--amber-500)"></span>
                        Départs aujourd'hui
                    </h2>
                    <span class="ci-card-badge badge-amber"><?php echo e($todayDepartures->count()); ?></span>
                </div>

                <?php if($todayDepartures->isEmpty()): ?>
                <div class="ci-empty" style="padding: 28px 20px;">
                    <div class="ci-empty-icon" style="width:52px;height:52px;font-size:1.3rem;color:var(--green-400);background:var(--green-50);"><i class="fas fa-check-circle"></i></div>
                    <p class="ci-empty-title" style="font-size:.85rem;">Aucun départ prévu</p>
                </div>
                <?php else: ?>
                    <?php $__currentLoopData = $todayDepartures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $totalPrice = $transaction->getTotalPrice();
                        $totalPayment = $transaction->getTotalPayment();
                        $remaining = $totalPrice - $totalPayment;
                        $isFullyPaid = $remaining <= 0;
                    ?>
                    <div class="dep-row">
                        <div class="dep-row-info">
                            <div class="dep-row-name"><?php echo e($transaction->customer->name); ?></div>
                            <div class="dep-row-room">
                                <i class="fas fa-door-closed"></i>
                                Chambre <?php echo e($transaction->room->number); ?>

                                <?php if(!$isFullyPaid): ?>
                                <span class="badge bg-danger ms-2" style="font-size:.6rem;">Impayé</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="dep-row-right">
                            <span class="dep-time-badge"><?php echo e($transaction->check_out->format('H:i')); ?></span>
                            <div class="dep-actions">
                                <a href="<?php echo e(route('transaction.show', $transaction)); ?>" class="btn-dep-invoice">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                                <?php if($isFullyPaid): ?>
                                <form action="<?php echo e(route('transaction.mark-departed', $transaction)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-dep-checkout" onclick="return confirm('Confirmer le départ ?')">
                                        <i class="fas fa-sign-out-alt"></i> Out
                                    </button>
                                </form>
                                <?php else: ?>
                                <a href="<?php echo e(route('transaction.payment.create', $transaction)); ?>" class="btn-dep-checkout" style="background:var(--red-50);color:var(--red-700);border-color:var(--red-200);">
                                    <i class="fas fa-money-bill-wave-alt"></i> Payer
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>

        </div><!-- /right col -->
    </div><!-- /main grid -->
</div><!-- /ci-page -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script>
/* ── Toast helper ─────────────────────────────── */
function showToast(msg, type = 'success') {
    const wrap = document.getElementById('toast-container');
    const t = document.createElement('div');
    t.className = 'ci-toast' + (type === 'error' ? ' ci-toast-error' : '');
    const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
    const color = type === 'success' ? 'var(--green-500)' : 'var(--red-500)';
    t.innerHTML = `<i class="${icon}" style="color:${color};font-size:1rem;flex-shrink:0"></i><span>${msg}</span>`;
    wrap.appendChild(t);
    setTimeout(() => t.style.opacity = '0', 2800);
    setTimeout(() => t.remove(), 3100);
}

/* ── Quick check-in (rapide) ───────────────────── */
function quickCheckIn(id, btn) {
    if (!confirm('Effectuer un check-in rapide sans formulaire détaillé ?\nLe client sera enregistré avec les informations de base.')) return;

    const orig = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;

    fetch(`/checkin/${id}/quick`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(r => { if (!r.ok) throw new Error(); return r.json(); })
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