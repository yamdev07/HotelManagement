
<?php $__env->startSection('title', 'Gestion des Réservations'); ?>
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

.trx-page {
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
.trx-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.trx-brand { display: flex; align-items: center; gap: 14px; }
.trx-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.trx-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.trx-header-title em { font-style: normal; color: var(--g600); }
.trx-header-sub { font-size: .8rem; color: var(--s400); margin-top: 3px; }
.trx-header-actions { display: flex; align-items: center; gap: 10px; }

/* ══════════════════════════════════════════════
   STAT CARDS (pour la légende)
══════════════════════════════════════════════ */
.legend-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 24px;
}
.legend-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: var(--white);
    border: 1.5px solid var(--s100);
    border-radius: 100px;
    font-size: .75rem;
    font-weight: 500;
    color: var(--s700);
    transition: var(--transition);
}
.legend-item:hover {
    background: var(--g50);
    border-color: var(--g200);
    transform: translateY(-2px);
}
.legend-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
}
.dot-reservation { background: #f59e0b; }
.dot-active { background: var(--g500); }
.dot-completed { background: var(--g400); }
.dot-cancelled { background: var(--s400); }
.dot-no_show { background: var(--s300); }
.dot-late { background: #f59e0b; }

/* ══════════════════════════════════════════════
   SEARCH CARD
══════════════════════════════════════════════ */
.search-card {
    background: var(--white);
    border-radius: var(--rxl);
    border: 1.5px solid var(--s100);
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: var(--shadow-sm);
}
.search-card-header {
    padding: 16px 22px;
    border-bottom: 1.5px solid var(--s100);
    background: var(--white);
}
.search-card-header h5 {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .9rem;
    font-weight: 600;
    color: var(--s800);
    margin: 0;
}
.search-card-header h5 i {
    color: var(--g600);
}
.search-card-body {
    padding: 16px 22px;
}
.search-form {
    display: flex;
    gap: 10px;
}
.search-input {
    flex: 1;
    height: 40px;
    padding: 0 16px;
    border: 1.5px solid var(--s200);
    border-radius: var(--r);
    font-size: .85rem;
    outline: none;
    transition: var(--transition);
    color: var(--s900);
    background: var(--surface);
    font-family: var(--font);
}
.search-input:focus {
    border-color: var(--g400);
    background: var(--white);
    box-shadow: 0 0 0 3px rgba(46,133,64,.08);
}
.search-btn {
    height: 40px;
    padding: 0 20px;
    background: var(--g600);
    color: white;
    border: none;
    border-radius: var(--r);
    font-size: .85rem;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.search-btn:hover {
    background: var(--g700);
    transform: translateY(-1px);
}

/* ══════════════════════════════════════════════
   NOTE RÉCEPTIONNISTE
══════════════════════════════════════════════ */
.recep-note {
    background: linear-gradient(135deg, var(--g50), #f8fdf8);
    border-left: 4px solid var(--g600);
    border-radius: var(--rl);
    padding: 16px 22px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    border: 1.5px solid var(--g200);
}
.recep-note i {
    color: var(--g600);
    font-size: 1.4rem;
}
.recep-note strong {
    display: block;
    margin-bottom: 4px;
    color: var(--s800);
}
.recep-note small {
    color: var(--s500);
}

/* ══════════════════════════════════════════════
   MAIN CARD
══════════════════════════════════════════════ */
.trx-card {
    background: var(--white);
    border-radius: var(--rxl);
    border: 1.5px solid var(--s100);
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}
.trx-card:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--g200);
}
.trx-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px;
    border-bottom: 1.5px solid var(--s100);
    background: var(--white);
    flex-wrap: wrap;
    gap: 12px;
}
.trx-card-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: .95rem;
    font-weight: 600;
    color: var(--s800);
    margin: 0;
}
.trx-card-title i {
    color: var(--g600);
    font-size: 1rem;
}
.trx-card-count {
    background: var(--g100);
    color: var(--g700);
    font-size: .7rem;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 100px;
    margin-left: 6px;
}
.trx-card-subtitle {
    font-size: .75rem;
    color: var(--s400);
}
.trx-card-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

/* ══════════════════════════════════════════════
   BUTTONS
══════════════════════════════════════════════ */
.btn-db {
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
    white-space: nowrap;
    font-family: var(--font);
}
.btn-db-primary {
    background: var(--g600);
    color: white;
    box-shadow: 0 2px 10px rgba(46,133,64,.25);
}
.btn-db-primary:hover {
    background: var(--g700);
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(46,133,64,.3);
    color: white;
    text-decoration: none;
}
.btn-db-ghost {
    background: var(--white);
    color: var(--s600);
    border: 1.5px solid var(--s200);
}
.btn-db-ghost:hover {
    background: var(--g50);
    border-color: var(--g300);
    color: var(--g700);
    text-decoration: none;
}
.btn-db-sm {
    padding: 5px 12px;
    font-size: .75rem;
}

/* ══════════════════════════════════════════════
   TABLE
══════════════════════════════════════════════ */
.trx-table {
    width: 100%;
    border-collapse: collapse;
}
.trx-table thead th {
    background: var(--surface);
    color: var(--s500);
    font-weight: 600;
    font-size: .68rem;
    text-transform: uppercase;
    letter-spacing: .6px;
    padding: 14px 18px;
    border-bottom: 1.5px solid var(--s100);
    white-space: nowrap;
}
.trx-table tbody td {
    padding: 14px 18px;
    font-size: .82rem;
    color: var(--s700);
    border-bottom: 1px solid var(--s100);
    vertical-align: middle;
    transition: var(--transition);
}
.trx-table tbody tr {
    transition: var(--transition);
}
.trx-table tbody tr:hover td {
    background: var(--g50);
}
.trx-table tbody tr.cancelled-row {
    opacity: .7;
    background: var(--surface);
}

/* ── Client info ── */
.client-info {
    display: flex;
    align-items: center;
    gap: 12px;
}
.client-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: var(--g100);
    border: 2px solid var(--g200);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .78rem;
    font-weight: 700;
    color: var(--g700);
    flex-shrink: 0;
    font-family: var(--mono);
}
.client-name {
    font-weight: 600;
    color: var(--s900);
    font-size: .85rem;
}
.client-phone {
    font-size: .7rem;
    color: var(--s400);
    margin-top: 2px;
}

/* ── Room badge ── */
.room-badge {
    background: var(--surface);
    color: var(--s700);
    font-weight: 600;
    padding: 5px 10px;
    border-radius: var(--r);
    font-size: .78rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border: 1.5px solid var(--s200);
    font-family: var(--mono);
}
.room-badge i {
    color: var(--s400);
    font-size: .7rem;
}

/* ── Nights badge ── */
.nights-badge {
    background: var(--surface);
    color: var(--s500);
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 100px;
    font-size: .68rem;
    border: 1.5px solid var(--s200);
    white-space: nowrap;
}

/* ── Prices ── */
.price {
    font-weight: 700;
    font-family: var(--mono);
    font-size: .85rem;
}
.price-positive { color: var(--s800); }
.price-success { color: var(--g600); }
.price-danger { color: var(--s700); }

/* ── Date indicators ── */
.date-indicator {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    border-radius: 100px;
    font-size: .62rem;
    font-weight: 600;
    margin-top: 4px;
}
.di-upcoming { background: #fef3c7; color: #b45309; }
.di-ready { background: var(--g100); color: var(--g700); }
.di-overdue { background: #fee2e2; color: #b91c1c; }
.di-pending { background: #e0f2fe; color: #0369a1; }
.di-late { background: #fff7ed; color: #c2410c; }

/* ── Unpaid alert ── */
.unpaid-alert {
    background: #fee2e2;
    border-left: 3px solid #b91c1c;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: .68rem;
    margin-top: 4px;
}
.unpaid-alert a {
    color: #b91c1c;
    font-weight: 600;
    text-decoration: none;
}

/* ── Badges ── */
.badge-statut {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 100px;
    font-size: .68rem;
    font-weight: 600;
    white-space: nowrap;
    border: none;
    cursor: pointer;
    transition: var(--transition);
}
.badge-reservation { background: #fef3c7; color: #b45309; }
.badge-active { background: var(--g100); color: var(--g700); }
.badge-completed { background: var(--g100); color: var(--g700); }
.badge-cancelled { background: var(--s100); color: var(--s600); }
.badge-no_show { background: var(--s100); color: var(--s500); }
.badge-late { background: #fff7ed; color: #c2410c; }
.badge-paid {
    background: var(--g100);
    color: var(--g700);
    padding: 3px 8px;
    border-radius: 100px;
    font-size: .65rem;
    font-weight: 600;
}

/* ── Action buttons ── */
.action-buttons {
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
    justify-content: flex-end;
}
.btn-action {
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
.btn-action:hover {
    background: var(--g50);
    border-color: var(--g300);
    color: var(--g700);
    transform: translateY(-2px);
    text-decoration: none;
}
.btn-action.disabled {
    opacity: .4;
    cursor: not-allowed;
    pointer-events: none;
}
.btn-pay:hover {
    background: var(--g600);
    border-color: var(--g600);
    color: white;
}
.btn-arrived:hover {
    background: var(--g500);
    border-color: var(--g500);
    color: white;
}
.btn-departed:hover {
    background: var(--g600);
    border-color: var(--g600);
    color: white;
}
.btn-edit:hover {
    background: var(--s200);
    border-color: var(--s300);
    color: var(--s800);
}
.btn-view:hover {
    background: var(--s100);
    border-color: var(--s200);
    color: var(--s700);
}
.btn-late:hover {
    background: #c2410c;
    border-color: #c2410c;
    color: white;
}
.btn-warning-action {
    background: #fff7ed;
    color: #c2410c;
    border: 1.5px solid #fed7aa;
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .75rem;
    transition: var(--transition);
}
.btn-warning-action:hover {
    background: #c2410c;
    color: white;
    border-color: #c2410c;
    transform: translateY(-2px);
}

/* ── Dropdown ── */
.db-dropdown {
    position: relative;
    display: inline-block;
}
.db-dropdown-menu {
    position: absolute;
    right: 0;
    top: calc(100% + 4px);
    background: var(--white);
    border: 1.5px solid var(--s200);
    border-radius: var(--rl);
    box-shadow: var(--shadow-lg);
    min-width: 180px;
    z-index: 1000;
    overflow: hidden;
    display: none;
    animation: scaleIn .15s ease;
    transform-origin: top right;
}
.db-dropdown-menu.open {
    display: block;
}
.db-dropdown-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    font-size: .8rem;
    font-weight: 500;
    color: var(--s700);
    text-decoration: none;
    transition: var(--transition);
    cursor: pointer;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    font-family: var(--font);
}
.db-dropdown-item:hover {
    background: var(--g50);
    color: var(--g700);
}
.db-dropdown-divider {
    height: 1px;
    background: var(--s100);
    margin: 4px 0;
}

/* ── Status dropdown ── */
.status-dropdown-menu {
    min-width: 180px;
    padding: 8px;
    border-radius: var(--rl);
    border: 1.5px solid var(--s200);
    box-shadow: var(--shadow-lg);
}
.status-dropdown-item {
    padding: 8px 12px;
    border-radius: var(--r);
    font-size: .8rem;
    font-weight: 500;
    transition: var(--transition);
    cursor: pointer;
    width: 100%;
    text-align: left;
    background: white;
    border: none;
    margin-bottom: 2px;
}
.status-dropdown-item:hover {
    background: var(--g50);
    color: var(--g700);
    transform: translateX(2px);
}
.status-dropdown-item:disabled {
    opacity: .4;
    cursor: not-allowed;
}
.status-dropdown-divider {
    margin: 6px 0;
    border-top: 1px solid var(--s200);
}

/* ── Pagination ── */
.pagination-modern {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    margin-top: 0;
}
.pagination-modern .page-item {
    list-style: none;
}
.pagination-modern .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: var(--r);
    border: 1.5px solid var(--s200);
    background: var(--white);
    color: var(--s600);
    font-size: .75rem;
    font-weight: 500;
    transition: var(--transition);
    text-decoration: none;
}
.pagination-modern .page-link:hover {
    background: var(--g50);
    border-color: var(--g300);
    color: var(--g700);
    transform: translateY(-2px);
}
.pagination-modern .active .page-link {
    background: var(--g600);
    border-color: var(--g600);
    color: white;
}

/* ── Empty state ── */
.trx-empty {
    text-align: center;
    padding: 60px 24px;
}
.trx-empty-icon {
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

/* ── Modal ── */
.modal-content {
    border-radius: var(--rxl);
    border: none;
    box-shadow: var(--shadow-lg);
}
.modal-header {
    background: var(--white);
    border-bottom: 1.5px solid var(--s100);
    padding: 18px 22px;
}
.modal-body {
    padding: 24px;
}
.modal-footer {
    border-top: 1.5px solid var(--s100);
    padding: 16px 22px;
}
</style>

<div class="trx-page">

    
    <div class="trx-header anim-1">
        <div class="trx-brand">
            <div class="trx-brand-icon"><i class="fas fa-calendar-check"></i></div>
            <div>
                <h1 class="trx-header-title">Gestion des <em>Réservations</em></h1>
                <p class="trx-header-sub"><?php echo e(now()->translatedFormat('l d F Y')); ?> · Gérez les arrivées, séjours et départs</p>
            </div>
        </div>
        <div class="trx-header-actions">
            <?php if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist'])): ?>
            <button type="button" class="btn-db btn-db-primary" data-bs-toggle="modal" data-bs-target="#newReservationModal">
                <i class="fas fa-plus fa-xs"></i> Nouvelle réservation
            </button>
            <?php endif; ?>
            <a href="<?php echo e(route('payment.index')); ?>" class="btn-db btn-db-ghost">
                <i class="fas fa-history fa-xs"></i> Historique
            </a>
            <?php if(auth()->user()->role == 'Receptionist'): ?>
            <span class="btn-db btn-db-ghost" style="background: var(--g50); border-color: var(--g200); color: var(--g700);">
                <i class="fas fa-user-check fa-xs"></i> Permissions complètes
            </span>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="legend-grid anim-2">
        <span class="legend-item"><span class="legend-dot dot-reservation"></span> Réservation</span>
        <span class="legend-item"><span class="legend-dot dot-active"></span> Dans l'hôtel</span>
        <span class="legend-item"><span class="legend-dot dot-completed"></span> Terminé (payé)</span>
        <span class="legend-item"><span class="legend-dot dot-cancelled"></span> Annulée</span>
        <span class="legend-item"><span class="legend-dot dot-no_show"></span> No Show</span>
        <span class="legend-item"><span class="legend-dot dot-late"></span> Late checkout</span>
    </div>

    
    <div class="search-card anim-3">
        <div class="search-card-header">
            <h5><i class="fas fa-search"></i> Rechercher une réservation</h5>
        </div>
        <div class="search-card-body">
            <form method="GET" action="<?php echo e(route('transaction.index')); ?>" class="search-form">
                <input type="text" class="search-input" name="search" value="<?php echo e(request('search')); ?>" 
                       placeholder="ID, nom client ou numéro de chambre...">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search fa-xs"></i> Rechercher
                </button>
                <?php if(request('search')): ?>
                <a href="<?php echo e(route('transaction.index')); ?>" class="btn-db btn-db-ghost">
                    <i class="fas fa-times fa-xs"></i> Effacer
                </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    
    <?php if(auth()->user()->role == 'Receptionist'): ?>
    <div class="recep-note anim-4">
        <i class="fas fa-info-circle"></i>
        <div>
            <strong>💼 Réceptionniste — Permissions Complètes</strong>
            <small>Création, modification, paiements, check-in/out, annulation ✓ (sauf suppression)</small>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="background: var(--g50); border: 1.5px solid var(--g200); color: var(--g700); border-radius: var(--rl); padding: 14px 18px; margin-bottom: 20px;">
        <i class="fas fa-check-circle me-2"></i> <?php echo session('success'); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?php if(session('error') || session('failed')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background: #fee2e2; border: 1.5px solid #fecaca; color: #b91c1c; border-radius: var(--rl); padding: 14px 18px; margin-bottom: 20px;">
        <i class="fas fa-exclamation-circle me-2"></i> <?php echo e(session('error') ?? session('failed')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?php if(session('departure_success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="background: var(--g50); border: 1.5px solid var(--g200); color: var(--g700); border-radius: var(--rl); padding: 14px 18px; margin-bottom: 20px;">
        <i class="fas fa-check-circle me-2"></i>
        <strong><?php echo e(session('departure_success')['title']); ?></strong><br>
        <?php echo e(session('departure_success')['message']); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    
    <div class="trx-card anim-5">
        <div class="trx-card-header">
            <h3 class="trx-card-title">
                <i class="fas fa-users"></i>
                Réservations en cours
                <span class="trx-card-count"><?php echo e($transactions->count()); ?></span>
            </h3>
            <span class="trx-card-subtitle">Arrivées & séjours en cours</span>
        </div>

        <div style="overflow-x: auto;">
            <table class="trx-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Chambre</th>
                        <th>Arrivée</th>
                        <th>Départ</th>
                        <th>Nuits</th>
                        <th>Total</th>
                        <th>Payé</th>
                        <th>Reste</th>
                        <th class="text-center">Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $totalPrice = $transaction->getTotalPrice();
                        $totalPayment = $transaction->getTotalPayment();
                        $remaining = $totalPrice - $totalPayment;
                        $isFullyPaid = $remaining <= 0;
                        $status = $transaction->status;
                        
                        $checkIn = \Carbon\Carbon::parse($transaction->check_in);
                        $checkOut = \Carbon\Carbon::parse($transaction->check_out);
                        $nights = $checkIn->diffInDays($checkOut);
                        
                        $isAdmin = in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']);
                        $isSuperAdmin = auth()->user()->role == 'Super';
                        $isReceptionist = auth()->user()->role == 'Receptionist';
                        
                        $canPay = !in_array($status, ['cancelled', 'no_show']) && !$isFullyPaid && $isAdmin;
                        $canMarkArrived = $isAdmin && $status == 'reservation';
                        
                        $now = \Carbon\Carbon::now();
                        $checkInDateTime = \Carbon\Carbon::parse($transaction->check_in)->setTime(12, 0, 0);
                        $checkOutDateTime = \Carbon\Carbon::parse($transaction->check_out)->setTime(12, 0, 0);
                        
                        $checkInTime = $checkInDateTime->copy()->setTime(12, 0, 0);
                        $checkOutDeadline = $checkOutDateTime->copy()->setTime(12, 0, 0);
                        $checkOutLargess = $checkOutDateTime->copy()->setTime(14, 0, 0);
                        $lateCheckoutEnd = $checkOutDateTime->copy()->setTime(20, 0, 0);
                        
                        $canMarkArrivedNow = $canMarkArrived && $now->isSameDay($checkInDateTime) && $now->gte($checkInTime);
                        $arrivalNotReached = $status == 'reservation' && ($now->lt($checkInDateTime) || !$now->isSameDay($checkInDateTime));
                        $arrivalDelay = $arrivalNotReached ? ceil($now->diffInDays($checkInDateTime, false)) : 0;
                        $arrivalHoursLeft = $arrivalNotReached && $now->isSameDay($checkInDateTime) ? $now->diffInHours($checkInTime) : 0;
                        
                        $isLateCheckout = $transaction->late_checkout ?? false;
                        $expectedCheckoutTime = $transaction->expected_checkout_time ?? '12:00:00';
                        $lateCheckoutFee = $transaction->late_checkout_fee ?? 0;
                        
                        $latePayment = $transaction->payments->first(function($p) {
                            $hasLateRef = $p->reference && str_contains($p->reference, 'LATE-');
                            $hasLateDesc = $p->description && (str_contains(strtolower($p->description), 'late checkout') || str_contains(strtolower($p->description), 'late'));
                            return ($hasLateRef || $hasLateDesc) && $p->status == 'completed';
                        });
                        $isLatePaid = !is_null($latePayment);
                        
                        $canDepart = false;
                        $departureButtonType = '';
                        $departureButtonTitle = '';
                        
                        if ($status == 'active') {
                            if ($now->isSameDay($checkOutDateTime) && $now->gte($checkOutDeadline) && $now->lte($checkOutLargess) && $isFullyPaid) {
                                $canDepart = true;
                                $departureButtonType = 'btn-departed';
                                $departureButtonTitle = 'Départ (largesse jusqu\'à 14h)';
                            } elseif ($isLateCheckout && $isLatePaid && $now->isSameDay($checkOutDateTime) && $now->gte($checkOutLargess) && $now->lt($lateCheckoutEnd)) {
                                $canDepart = true;
                                $departureButtonType = 'btn-departed';
                                $departureButtonTitle = 'Départ (late checkout)';
                            } elseif ($isLateCheckout && !$isLatePaid) {
                                $departureButtonType = 'disabled';
                                $departureButtonTitle = 'Supplément late checkout de ' . number_format($lateCheckoutFee, 0, ',', ' ') . ' FCFA en attente';
                            } elseif ($now->isSameDay($checkOutDateTime) && $now->gt($checkOutLargess) && !$isLateCheckout) {
                                $departureButtonType = 'extend';
                                $departureButtonTitle = 'Départ après 14h - Prolonger';
                            }
                        }
                        
                        $arrivalTooltip = '';
                        if ($arrivalNotReached) {
                            if (!$now->isSameDay($checkInDateTime)) {
                                $arrivalTooltip = "Arrivée prévue le " . $checkInDateTime->format('d/m/Y');
                            } elseif ($now->lt($checkInTime)) {
                                $arrivalTooltip = "Check-in possible à partir de 12h. Encore $arrivalHoursLeft heure(s).";
                            }
                        }
                        
                        $departureTooltip = '';
                        if (!$canDepart && $departureButtonType != 'disabled' && $departureButtonType != 'extend') {
                            if ($status == 'active') {
                                if (!$now->isSameDay($checkOutDateTime)) {
                                    $departureTooltip = "Départ prévu le " . $checkOutDateTime->format('d/m/Y');
                                } elseif ($now->lt($checkOutDeadline)) {
                                    $departureTooltip = "Check-out possible à partir de 12h. Encore " . $now->diffInHours($checkOutDeadline) . " heure(s).";
                                }
                            }
                        }
                    ?>
                    <tr class="<?php echo e(in_array($status, ['cancelled', 'no_show']) ? 'cancelled-row' : ''); ?>">
                        <td><span style="color: var(--s400); font-family: var(--mono);">#<?php echo e($transaction->id); ?></span></td>
                        
                        <td>
                            <div class="client-info">
                                <div class="client-avatar">
                                    <?php if($transaction->customer->user && $transaction->customer->user->getAvatar()): ?>
                                        <img src="<?php echo e($transaction->customer->user->getAvatar()); ?>" alt="<?php echo e($transaction->customer->name); ?>" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                    <?php else: ?>
                                        <?php echo e(strtoupper(substr($transaction->customer->name, 0, 1))); ?><?php echo e(strtoupper(substr(strstr($transaction->customer->name, ' ', true) ?: substr($transaction->customer->name, 1, 1), 0, 1))); ?>

                                    <?php endif; ?>
                                </div>
                                <div>
                                    <div class="client-name"><?php echo e($transaction->customer->name); ?></div>
                                    <div class="client-phone"><?php echo e($transaction->customer->phone ?? ''); ?></div>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            <span class="room-badge">
                                <i class="fas fa-door-closed"></i> <?php echo e($transaction->room->number); ?>

                            </span>
                        </td>
                        
                        <td>
                            <div style="font-weight: 500;"><?php echo e($checkIn->format('d/m/Y')); ?></div>
                            <small style="color: var(--s400);">12:00</small>
                            <?php if($status == 'reservation'): ?>
                                <?php if($now->lt($checkInDateTime)): ?>
                                    <div class="date-indicator di-upcoming">
                                        <i class="fas fa-clock"></i> J-<?php echo e($arrivalDelay); ?>

                                    </div>
                                <?php elseif($now->gte($checkInDateTime)): ?>
                                    <div class="date-indicator di-ready">
                                        <i class="fas fa-check-circle"></i> Prêt
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        
                        <td>
                            <div style="font-weight: 500;"><?php echo e($checkOut->format('d/m/Y')); ?></div>
                            <?php if($isLateCheckout): ?>
                                <small style="color: #c2410c; font-weight: 600;"><?php echo e($expectedCheckoutTime); ?></small>
                                <div class="date-indicator di-late">
                                    <i class="fas fa-clock"></i> Late
                                    <?php if($lateCheckoutFee > 0): ?>
                                        (+<?php echo e(number_format($lateCheckoutFee, 0, ',', ' ')); ?> FCFA)
                                        <?php if(!$isLatePaid): ?>
                                            <span class="ms-1" style="color: #b91c1c;">(non payé)</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <small style="color: var(--s400);">12:00</small>
                                <?php if($status == 'active'): ?>
                                    <?php if($now->lt($checkOutDateTime)): ?>
                                        <div class="date-indicator di-pending">
                                            <i class="fas fa-hourglass-half"></i> J-<?php echo e(ceil($now->diffInDays($checkOutDateTime, false))); ?>

                                        </div>
                                    <?php elseif($now->gte($checkOutDateTime) && $now->lte($checkOutLargess)): ?>
                                        <div class="date-indicator di-ready">
                                            <i class="fas fa-check-circle"></i> Départ possible
                                            <?php if($now->gt($checkOutDeadline) && $now->lte($checkOutLargess)): ?>
                                                <small>(largesse)</small>
                                            <?php endif; ?>
                                        </div>
                                    <?php elseif($now->gt($checkOutLargess)): ?>
                                        <div class="date-indicator di-overdue">
                                            <i class="fas fa-exclamation-triangle"></i> Dépassé
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        
                        <td>
                            <span class="nights-badge"><?php echo e($nights); ?> nuit<?php echo e($nights > 1 ? 's' : ''); ?></span>
                        </td>
                        
                        <td class="price price-positive"><?php echo e(number_format($totalPrice, 0, ',', ' ')); ?> FCFA</td>
                        
                        <td class="price price-success"><?php echo e(number_format($totalPayment, 0, ',', ' ')); ?> FCFA</td>
                        
                        <td>
                            <?php if($isFullyPaid): ?>
                                <span class="badge-paid"><i class="fas fa-check"></i> Soldé</span>
                            <?php else: ?>
                                <span class="price price-danger"><?php echo e(number_format($remaining, 0, ',', ' ')); ?> FCFA</span>
                                <?php if($checkOut->isPast() && $status == 'active'): ?>
                                    <div class="unpaid-alert">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <a href="<?php echo e(route('transaction.payment.create', $transaction)); ?>">Régler</a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        
                        <td class="text-center">
                            <?php if($isAdmin): ?>
                            <div class="db-dropdown">
                                <button class="badge-statut <?php echo e($isLateCheckout ? 'badge-late' : ($status == 'reservation' ? 'badge-reservation' : ($status == 'active' ? 'badge-active' : ($status == 'completed' ? 'badge-completed' : ($status == 'cancelled' ? 'badge-cancelled' : 'badge-no_show'))))); ?>" 
                                        onclick="toggleDropdown('status-dd-<?php echo e($transaction->id); ?>')" style="border: none;">
                                    <?php if($isLateCheckout): ?> <i class="fas fa-clock"></i>
                                    <?php elseif($status == 'reservation'): ?> <i class="fas fa-calendar-alt"></i>
                                    <?php elseif($status == 'active'): ?> <i class="fas fa-hotel"></i>
                                    <?php elseif($status == 'completed'): ?> <i class="fas fa-check-circle"></i>
                                    <?php elseif($status == 'cancelled'): ?> <i class="fas fa-times-circle"></i>
                                    <?php else: ?> <i class="fas fa-user-times"></i>
                                    <?php endif; ?>
                                    <?php echo e($isLateCheckout ? 'Late checkout' : ($status == 'reservation' ? 'Réservation' : ($status == 'active' ? 'Dans hôtel' : ($status == 'completed' ? 'Terminé' : ($status == 'cancelled' ? 'Annulée' : 'No Show'))))); ?>

                                </button>
                                <div class="db-dropdown-menu" id="status-dd-<?php echo e($transaction->id); ?>">
                                    <form action="<?php echo e(route('transaction.updateStatus', $transaction)); ?>" method="POST">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                        <input type="hidden" name="status" value="reservation">
                                        <button type="submit" class="db-dropdown-item" <?php echo e($status == 'reservation' ? 'disabled' : ''); ?>>
                                            <i class="fas fa-calendar-alt me-1"></i> Réservation
                                        </button>
                                    </form>
                                    <form action="<?php echo e(route('transaction.updateStatus', $transaction)); ?>" method="POST">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit" class="db-dropdown-item" <?php echo e($status == 'active' ? 'disabled' : ''); ?>>
                                            <i class="fas fa-hotel me-1"></i> Dans l'hôtel
                                        </button>
                                    </form>
                                    <form action="<?php echo e(route('transaction.updateStatus', $transaction)); ?>" method="POST">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="db-dropdown-item" <?php echo e(!$isFullyPaid ? 'disabled' : ''); ?> <?php echo e($status == 'completed' ? 'disabled' : ''); ?>>
                                            <i class="fas fa-check-circle me-1"></i> Terminé <?php echo e(!$isFullyPaid ? '(impayé)' : ''); ?>

                                        </button>
                                    </form>
                                    <div class="db-dropdown-divider"></div>
                                    <form action="<?php echo e(route('transaction.updateStatus', $transaction)); ?>" method="POST">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="db-dropdown-item" <?php echo e($status == 'cancelled' ? 'disabled' : ''); ?>>
                                            <i class="fas fa-times-circle me-1"></i> Annulée
                                        </button>
                                    </form>
                                    <form action="<?php echo e(route('transaction.updateStatus', $transaction)); ?>" method="POST">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                        <input type="hidden" name="status" value="no_show">
                                        <button type="submit" class="db-dropdown-item" <?php echo e($status == 'no_show' ? 'disabled' : ''); ?>>
                                            <i class="fas fa-user-times me-1"></i> No Show
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <?php else: ?>
                            <span class="badge-statut <?php echo e($isLateCheckout ? 'badge-late' : ($status == 'reservation' ? 'badge-reservation' : ($status == 'active' ? 'badge-active' : ($status == 'completed' ? 'badge-completed' : ($status == 'cancelled' ? 'badge-cancelled' : 'badge-no_show'))))); ?>">
                                <?php if($isLateCheckout): ?> <i class="fas fa-clock"></i>
                                <?php elseif($status == 'reservation'): ?> <i class="fas fa-calendar-alt"></i>
                                <?php elseif($status == 'active'): ?> <i class="fas fa-hotel"></i>
                                <?php elseif($status == 'completed'): ?> <i class="fas fa-check-circle"></i>
                                <?php elseif($status == 'cancelled'): ?> <i class="fas fa-times-circle"></i>
                                <?php else: ?> <i class="fas fa-user-times"></i>
                                <?php endif; ?>
                                <?php echo e($isLateCheckout ? 'Late checkout' : ($status == 'reservation' ? 'Réservation' : ($status == 'active' ? 'Dans hôtel' : ($status == 'completed' ? 'Terminé' : ($status == 'cancelled' ? 'Annulée' : 'No Show'))))); ?>

                            </span>
                            <?php endif; ?>
                        </td>
                        
                        <td>
                            <div class="action-buttons">
                                <?php if($canPay): ?>
                                <a href="<?php echo e(route('transaction.payment.create', $transaction)); ?>" class="btn-action btn-pay" data-bs-toggle="tooltip" title="Paiement">
                                    <i class="fas fa-money-bill-wave-alt"></i>
                                </a>
                                <?php endif; ?>
                                
                                <?php if($canMarkArrivedNow): ?>
                                <form action="<?php echo e(route('transaction.mark-arrived', $transaction)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-action btn-arrived" data-bs-toggle="tooltip" title="Marquer arrivé">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </button>
                                </form>
                                <?php elseif($arrivalNotReached): ?>
                                <span class="btn-action disabled" data-bs-toggle="tooltip" title="<?php echo e($arrivalTooltip); ?>">
                                    <i class="fas fa-clock"></i>
                                </span>
                                <?php endif; ?>
                                
                                <?php if($canDepart): ?>
                                    <button type="button" class="btn-action btn-departed mark-departed-btn"
                                            data-transaction-id="<?php echo e($transaction->id); ?>"
                                            data-check-out="<?php echo e($checkOutDateTime->format('d/m/Y H:i')); ?>"
                                            data-is-fully-paid="<?php echo e($isFullyPaid ? 'true' : 'false'); ?>"
                                            data-remaining="<?php echo e($remaining); ?>"
                                            data-form-action="<?php echo e(route('transaction.mark-departed', $transaction)); ?>"
                                            data-bs-toggle="tooltip" title="<?php echo e($departureButtonTitle); ?>">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                <?php elseif($departureButtonType == 'disabled'): ?>
                                    <span class="btn-action disabled" data-bs-toggle="tooltip" title="<?php echo e($departureButtonTitle); ?>">
                                        <i class="fas fa-hourglass-half"></i>
                                    </span>
                                <?php elseif($departureButtonType == 'extend'): ?>
                                    <a href="<?php echo e(route('transaction.extend', $transaction)); ?>" class="btn-warning-action" data-bs-toggle="tooltip" title="<?php echo e($departureButtonTitle); ?>">
                                        <i class="fas fa-calendar-plus"></i>
                                    </a>
                                <?php elseif($status == 'active' && $departureTooltip): ?>
                                    <span class="btn-action disabled" data-bs-toggle="tooltip" title="<?php echo e($departureTooltip); ?>">
                                        <i class="fas fa-hourglass-half"></i>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if($isLateCheckout && $isAdmin): ?>
                                <a href="<?php echo e(route('transaction.show', $transaction)); ?>" class="btn-action btn-late" data-bs-toggle="tooltip" title="Voir détails late checkout">
                                    <i class="fas fa-clock"></i>
                                </a>
                                <?php endif; ?>
                                
                                <?php if($isSuperAdmin || ($isReceptionist && !in_array($status, ['cancelled', 'no_show', 'completed']))): ?>
                                <a href="<?php echo e(route('transaction.edit', $transaction)); ?>" class="btn-action btn-edit" data-bs-toggle="tooltip" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php endif; ?>
                                
                                <a href="<?php echo e(route('transaction.show', $transaction)); ?>" class="btn-action btn-view" data-bs-toggle="tooltip" title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if(in_array($status, ['active', 'pending_checkout'])): ?>
                                <a href="<?php echo e(route('transaction.compte-sejour', $transaction)); ?>" class="btn-action" style="background:#10b981; color:#fff;" data-bs-toggle="tooltip" title="Compte séjour">
                                    <i class="fas fa-receipt"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="12" class="text-center">
                            <div class="trx-empty">
                                <div class="trx-empty-icon"><i class="fas fa-bed"></i></div>
                                <h5 style="color: var(--s600); margin-bottom: 6px;">Aucune réservation active</h5>
                                <p style="color: var(--s400); margin-bottom: 18px;">Commencez par créer une nouvelle réservation</p>
                                <?php if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist'])): ?>
                                <button class="btn-db btn-db-primary" data-bs-toggle="modal" data-bs-target="#newReservationModal">
                                    <i class="fas fa-plus"></i> Nouvelle réservation
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($transactions->hasPages()): ?>
        <div class="p-3 border-top" style="border-top: 1.5px solid var(--s100) !important;">
            <?php echo e($transactions->onEachSide(2)->links('template.paginationlinks', ['class' => 'pagination-modern'])); ?>

        </div>
        <?php endif; ?>
    </div>

    
    <?php if($transactionsExpired->isNotEmpty()): ?>
    <div class="trx-card anim-6">
        <div class="trx-card-header">
            <h3 class="trx-card-title">
                <i class="fas fa-history"></i>
                Anciennes réservations
                <span class="trx-card-count"><?php echo e($transactionsExpired->count()); ?></span>
            </h3>
            <span class="trx-card-subtitle">Terminées ou expirées</span>
        </div>

        <div style="overflow-x: auto;">
            <table class="trx-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Chambre</th>
                        <th>Arrivée</th>
                        <th>Départ</th>
                        <th>Nuits</th>
                        <th>Total</th>
                        <th>Payé</th>
                        <th>Reste</th>
                        <th class="text-center">Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $transactionsExpired; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $totalPrice = $transaction->getTotalPrice();
                        $totalPayment = $transaction->getTotalPayment();
                        $remaining = $totalPrice - $totalPayment;
                        $isFullyPaid = $remaining <= 0;
                        $status = $transaction->status;
                        $checkIn = \Carbon\Carbon::parse($transaction->check_in);
                        $checkOut = \Carbon\Carbon::parse($transaction->check_out);
                        $nights = $checkIn->diffInDays($checkOut);
                        $isAdmin = in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']);
                        $canPay = !in_array($status, ['cancelled', 'no_show']) && !$isFullyPaid && $isAdmin;
                        $isLateCheckout = $transaction->late_checkout ?? false;
                        $expectedCheckoutTime = $transaction->expected_checkout_time ?? '12:00:00';
                    ?>
                    <tr class="<?php echo e(in_array($status, ['cancelled', 'no_show']) ? 'cancelled-row' : ''); ?>">
                        <td><span style="color: var(--s400); font-family: var(--mono);">#<?php echo e($transaction->id); ?></span></td>
                        <td><?php echo e($transaction->customer->name); ?></td>
                        <td><span class="room-badge"><?php echo e($transaction->room->number); ?></span></td>
                        <td><?php echo e($checkIn->format('d/m/Y')); ?> 12:00</td>
                        <td><?php echo e($checkOut->format('d/m/Y')); ?> 
                            <?php if($isLateCheckout): ?>
                                <span class="badge-late ms-1"><?php echo e($expectedCheckoutTime); ?></span>
                            <?php endif; ?>
                        </td>
                        <td><span class="nights-badge"><?php echo e($nights); ?> nuit<?php echo e($nights > 1 ? 's' : ''); ?></span></td>
                        <td class="price price-positive"><?php echo e(number_format($totalPrice, 0, ',', ' ')); ?> FCFA</td>
                        <td class="price price-success"><?php echo e(number_format($totalPayment, 0, ',', ' ')); ?> FCFA</td>
                        <td>
                            <?php if($isFullyPaid): ?>
                                <span class="badge-paid"><i class="fas fa-check"></i> Soldé</span>
                            <?php else: ?>
                                <span class="price price-danger"><?php echo e(number_format($remaining, 0, ',', ' ')); ?> FCFA</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <span class="badge-statut <?php echo e($isLateCheckout ? 'badge-late' : ($status == 'reservation' ? 'badge-reservation' : ($status == 'active' ? 'badge-active' : ($status == 'completed' ? 'badge-completed' : ($status == 'cancelled' ? 'badge-cancelled' : 'badge-no_show'))))); ?>">
                                <?php if($isLateCheckout): ?> <i class="fas fa-clock"></i>
                                <?php elseif($status == 'reservation'): ?> 📅
                                <?php elseif($status == 'active'): ?> 🏨
                                <?php elseif($status == 'completed'): ?> ✅
                                <?php elseif($status == 'cancelled'): ?> ❌
                                <?php else: ?> 👤
                                <?php endif; ?>
                                <?php echo e($isLateCheckout ? 'Late' : ($status == 'reservation' ? 'Réservation' : ($status == 'active' ? 'Dans hôtel' : ($status == 'completed' ? 'Terminé' : ($status == 'cancelled' ? 'Annulée' : 'No Show'))))); ?>

                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <?php if($canPay): ?>
                                <a href="<?php echo e(route('transaction.payment.create', $transaction)); ?>" class="btn-action btn-pay" data-bs-toggle="tooltip" title="Payer dette">
                                    <i class="fas fa-money-bill-wave-alt"></i>
                                </a>
                                <?php endif; ?>
                                <a href="<?php echo e(route('transaction.show', $transaction)); ?>" class="btn-action btn-view" data-bs-toggle="tooltip" title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if(auth()->user()->role == 'Super' && $status == 'cancelled'): ?>
                                <form action="<?php echo e(route('transaction.restore', $transaction)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-action" style="background: var(--g50); color: var(--g600);" onclick="return confirm('Restaurer cette réservation ?')" data-bs-toggle="tooltip" title="Restaurer">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

</div>


<?php if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist'])): ?>
<div class="modal fade" id="newReservationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2" style="color: var(--g600);"></i>
                    Nouvelle Réservation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-4" style="color: var(--s500);">Le client a-t-il déjà un compte ?</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="<?php echo e(route('transaction.reservation.createIdentity')); ?>" class="btn-db btn-db-primary">
                        <i class="fas fa-user-plus"></i> Nouveau compte
                    </a>
                    <a href="<?php echo e(route('transaction.reservation.pickFromCustomer')); ?>" class="btn-db btn-db-ghost">
                        <i class="fas fa-users"></i> Client existant
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<form id="cancel-form" method="POST" action="<?php echo e(route('transaction.cancel', 0)); ?>" class="d-none">
    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
    <input type="hidden" name="transaction_id" id="cancel-transaction-id-input">
    <input type="hidden" name="cancel_reason" id="cancel-reason-input">
</form>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
/* ── Dropdown ── */
function toggleDropdown(id) {
    const element = document.getElementById(id);
    if (!element) return;
    
    // Fermer tous les autres
    document.querySelectorAll('.db-dropdown-menu.open').forEach(menu => {
        if (menu.id !== id) menu.classList.remove('open');
    });
    
    // Basculer celui-ci
    element.classList.toggle('open');
}

// Fermer les dropdowns en cliquant ailleurs
document.addEventListener('click', function(e) {
    if (!e.target.closest('.db-dropdown')) {
        document.querySelectorAll('.db-dropdown-menu.open').forEach(menu => {
            menu.classList.remove('open');
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(el) {
        return new bootstrap.Tooltip(el);
    });

    // Gestion des changements de statut
    document.querySelectorAll('.db-dropdown-item').forEach(item => {
        item.addEventListener('click', function(e) {
            const form = this.closest('form');
            if (!form) return;
            
            const newStatus = form.querySelector('input[name="status"]')?.value;
            const transactionId = form.action.match(/\/transaction\/(\d+)\//)?.[1] || '';
            
            if (newStatus === 'cancelled') {
                e.preventDefault();
                Swal.fire({
                    title: 'Annuler cette réservation ?',
                    html: '<textarea id="reason" class="form-control mt-2" placeholder="Raison (optionnelle)"></textarea>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, annuler',
                    cancelButtonText: 'Non',
                    confirmButtonColor: '#545954',
                    cancelButtonColor: '#1e6b2e',
                }).then(result => {
                    if (result.isConfirmed) {
                        document.getElementById('cancel-reason-input').value = document.getElementById('reason')?.value || '';
                        document.getElementById('cancel-transaction-id-input').value = transactionId;
                        document.getElementById('cancel-form').action = `/transaction/${transactionId}/cancel`;
                        document.getElementById('cancel-form').submit();
                    }
                });
                return false;
            }
            
            if (newStatus === 'no_show') {
                e.preventDefault();
                Swal.fire({
                    title: 'Marquer comme "No Show" ?',
                    text: 'Le client ne s\'est pas présenté',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Oui',
                    cancelButtonText: 'Non',
                    confirmButtonColor: '#1e6b2e',
                }).then(result => {
                    if (result.isConfirmed) form.submit();
                });
                return false;
            }
        });
    });

    // Gestion des boutons départ
    document.querySelectorAll('.mark-departed-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const formAction = this.dataset.formAction;
            const checkOut = this.dataset.checkOut;
            const isPaid = this.dataset.isFullyPaid === 'true';
            const remaining = this.dataset.remaining;
            
            const now = new Date();
            const currentHour = now.getHours();
            
            if (currentHour < 12) {
                Swal.fire({
                    icon: 'warning',
                    title: '⏳ Trop tôt',
                    text: 'Check-out possible à partir de 12h',
                    timer: 3000
                });
                return;
            }
            
            if (currentHour >= 20) {
                Swal.fire({
                    icon: 'error',
                    title: '⚠️ Après 20h',
                    text: 'Départ impossible après 20h. Veuillez prolonger le séjour.',
                    confirmButtonColor: '#1e6b2e'
                });
                return;
            }
            
            if (!isPaid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Paiement incomplet',
                    html: `Solde restant: <strong>${parseInt(remaining).toLocaleString()} CFA</strong>`,
                    confirmButtonText: 'Aller au paiement',
                    showCancelButton: true,
                    confirmButtonColor: '#1e6b2e'
                }).then(result => {
                    if (result.isConfirmed) {
                        window.location.href = `/transaction/${btn.dataset.transactionId}/payment/create`;
                    }
                });
                return;
            }
            
            let message = 'La chambre sera marquée comme à nettoyer.';
            if (currentHour >= 14) {
                message = 'Late checkout - La chambre sera marquée comme à nettoyer.';
            } else if (currentHour >= 12) {
                message = 'Largesse de 2h accordée. La chambre sera marquée comme à nettoyer.';
            }
            
            Swal.fire({
                title: 'Confirmer le départ ?',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui, départ',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#1e6b2e'
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = formAction;
                    form.innerHTML = '<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
});

/* Auto-reload après action */
<?php if(session('success') || session('error') || session('warning') || session('info')): ?>
    setTimeout(() => location.reload(), 2000);
<?php endif; ?>

// Rendre toggleDropdown accessible globalement
window.toggleDropdown = toggleDropdown;
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\HotelManagement\resources\views/transaction/index.blade.php ENDPATH**/ ?>