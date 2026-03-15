<?php $__env->startSection('title', 'Statuts des Chambres'); ?>
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

.status-page {
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
.status-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .8rem; color: var(--s400);
    margin-bottom: 20px;
}
.status-breadcrumb a {
    color: var(--s400); text-decoration: none;
    transition: var(--transition);
}
.status-breadcrumb a:hover { color: var(--g600); }
.status-breadcrumb .sep { color: var(--s300); }
.status-breadcrumb .current { color: var(--s600); font-weight: 500; }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.status-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.status-brand { display: flex; align-items: center; gap: 14px; }
.status-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.status-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.status-header-title em { font-style: normal; color: var(--g600); }
.status-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.status-header-sub i { color: var(--g500); }
.status-header-actions { display: flex; align-items: center; gap: 10px; }

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
    box-shadow: 0 2px 10px rgba(46,133,64,.3);
}
.btn-db-primary:hover {
    background: var(--g700); color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
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

/* ══════════════════════════════════════════════
   STAT CARDS
══════════════════════════════════════════════ */
.stats-grid {
    display: grid; grid-template-columns: repeat(4,1fr);
    gap: 14px; margin-bottom: 24px;
}
@media(max-width:1100px){ .stats-grid{ grid-template-columns:repeat(2,1fr); } }
@media(max-width:560px) { .stats-grid{ grid-template-columns:1fr; } }

.stat-card {
    background: var(--white); border-radius: var(--rl);
    padding: 22px 20px 18px;
    border: 1.5px solid var(--s100);
    text-decoration: none; display: block;
    position: relative; overflow: hidden;
    transition: var(--transition); box-shadow: var(--shadow-xs);
}
.stat-card:hover {
    transform: translateY(-3px); box-shadow: var(--shadow-md);
    border-color: var(--g200); text-decoration: none;
}
.stat-card::after {
    content: ''; position: absolute;
    bottom: 0; left: 0; right: 0; height: 3px;
    background: var(--bar-c, var(--g400));
    border-radius: 0 0 var(--rl) var(--rl);
}

.stat-card--total { --bar-c: var(--g500); }
.stat-card--active { --bar-c: var(--g600); }
.stat-card--occupied { --bar-c: var(--g300); }
.stat-card--inactive { --bar-c: var(--s400); }

.stat-card-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
.stat-card-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.stat-card--total .stat-card-icon { background: var(--g100); color: var(--g600); }
.stat-card--active .stat-card-icon { background: var(--g50); color: var(--g600); }
.stat-card--occupied .stat-card-icon { background: var(--g50); color: var(--g500); }
.stat-card--inactive .stat-card-icon { background: var(--s100); color: var(--s500); }

.stat-card-value {
    font-size: 2.2rem; font-weight: 700; color: var(--s900);
    line-height: 1; letter-spacing: -1px; margin-bottom: 4px;
    font-family: var(--mono);
}
.stat-card-label { font-size: .8rem; color: var(--s400); margin-bottom: 4px; }
.stat-card-footer {
    display: flex; align-items: center; gap: 5px;
    font-size: .72rem; padding-top: 12px;
    border-top: 1px solid var(--s100); color: var(--s400);
}
.stat-card--total .stat-card-footer { color: var(--g600); }
.stat-card--active .stat-card-footer { color: var(--g600); }

/* ══════════════════════════════════════════════
   ACTION BAR
══════════════════════════════════════════════ */
.action-bar {
    background: var(--white); border-radius: var(--rxl);
    padding: 16px 20px; margin-bottom: 24px;
    border: 1.5px solid var(--s100); box-shadow: var(--shadow-sm);
    display: flex; flex-wrap: wrap; align-items: center;
    justify-content: space-between; gap: 16px;
}
.action-left { display: flex; align-items: center; gap: 12px; }
.action-right { flex: 1; max-width: 400px; }

.filter-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 12px; border-radius: 30px; font-size: .75rem;
    font-weight: 600; background: var(--g100); color: var(--g700);
    border: 1px solid var(--g200);
}
.badge-count {
    background: var(--white); padding: 2px 6px; border-radius: 20px;
    font-size: .65rem; font-weight: 600;
}

.search-container {
    position: relative; width: 100%;
}
.search-icon {
    position: absolute; left: 14px; top: 50%;
    transform: translateY(-50%); color: var(--s400);
    font-size: .9rem; pointer-events: none; z-index: 2;
}
.search-input {
    width: 100%; padding: 10px 16px 10px 42px;
    border: 1.5px solid var(--s200); border-radius: var(--rl);
    font-size: .875rem; transition: var(--transition);
    background: var(--white); font-family: var(--font);
}
.search-input:focus {
    outline: none; border-color: var(--g400);
    box-shadow: 0 0 0 3px var(--g100);
}

/* ══════════════════════════════════════════════
   CARTE PRINCIPALE
══════════════════════════════════════════════ */
.status-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 20px; box-shadow: var(--shadow-sm);
}
.status-card-header {
    padding: 18px 24px;
    border-bottom: 1.5px solid var(--s100);
    background: var(--white);
    display: flex; align-items: center; justify-content: space-between;
}
.status-card-title {
    display: flex; align-items: center; gap: 10px;
    font-size: .95rem; font-weight: 600; color: var(--s800); margin: 0;
}
.status-card-title i { color: var(--g500); }
.status-card-badge {
    background: var(--g100); color: var(--g700);
    font-size: .7rem; font-weight: 600; padding: 4px 10px;
    border-radius: 100px;
}
.status-card-body { padding: 0; }

/* ══════════════════════════════════════════════
   TABLEAU
══════════════════════════════════════════════ */
.status-table {
    width: 100%; border-collapse: collapse;
}
.status-table thead th {
    font-size: .65rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .7px; color: var(--s400);
    padding: 14px 20px; background: var(--surface);
    border-bottom: 1.5px solid var(--s100); white-space: nowrap;
}
.status-table tbody tr {
    border-bottom: 1px solid var(--s100); transition: var(--transition);
}
.status-table tbody tr:last-child { border-bottom: none; }
.status-table tbody tr:hover { background: var(--g50); }
.status-table td {
    padding: 16px 20px; vertical-align: middle;
}

/* ══════════════════════════════════════════════
   STATUS BADGES
══════════════════════════════════════════════ */
.status-badge-custom {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 12px; border-radius: 30px; font-size: .75rem;
    font-weight: 600; white-space: nowrap;
}
.status-badge--available {
    background: var(--g100); color: var(--g700);
    border: 1px solid var(--g200);
}
.status-badge--occupied {
    background: #fee2e2; color: #b91c1c;
    border: 1px solid #fecaca;
}
.status-badge--maintenance {
    background: #fff3cd; color: #856404;
    border: 1px solid #ffeeba;
}
.status-badge--cleaning {
    background: var(--g50); color: var(--g600);
    border: 1px solid var(--g200);
}
.status-badge--reserved {
    background: #e0e7ff; color: #4338ca;
    border: 1px solid #c7d2fe;
}
.status-badge--out-of-order {
    background: var(--s100); color: var(--s600);
    border: 1px solid var(--s200);
}

/* Code badge */
.code-badge {
    display: inline-flex; align-items: center;
    padding: 4px 8px; border-radius: 6px; font-size: .7rem;
    font-weight: 600; background: var(--s100); color: var(--s600);
    border: 1px solid var(--s200); font-family: var(--mono);
}

/* ══════════════════════════════════════════════
   ACTION BUTTONS
══════════════════════════════════════════════ */
.action-group {
    display: flex; align-items: center; gap: 4px; justify-content: center;
}
.btn-db-icon {
    width: 32px; height: 32px; padding: 0;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 6px; font-size: .75rem;
    background: var(--white); color: var(--s400);
    border: 1.5px solid var(--s200); cursor: pointer;
    transition: var(--transition); text-decoration: none;
    font-family: var(--font);
}
.btn-db-icon:hover {
    transform: translateY(-1px);
}
.btn-db-icon-view:hover {
    background: var(--g50); color: var(--g600);
    border-color: var(--g200);
}
.btn-db-icon-edit:hover {
    background: var(--g50); color: var(--g600);
    border-color: var(--g200);
}
.btn-db-icon-delete:hover {
    background: #fee2e2; color: #b91c1c;
    border-color: #fecaca;
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    padding: 64px 24px; text-align: center;
}
.empty-icon {
    width: 80px; height: 80px; background: var(--g50);
    border-radius: 50%; display: flex; align-items: center;
    justify-content: center; font-size: 2rem; color: var(--g300);
    margin: 0 auto 20px; border: 2px solid var(--g100);
}
.empty-title {
    font-size: 1rem; font-weight: 600; color: var(--s700);
    margin-bottom: 8px;
}
.empty-text {
    font-size: .8rem; color: var(--s400);
    margin-bottom: 24px; max-width: 400px; margin-left: auto; margin-right: auto;
}

/* ══════════════════════════════════════════════
   PAGINATION
══════════════════════════════════════════════ */
.pagination-custom {
    display: flex; gap: 4px; justify-content: center; padding: 20px;
}
.pagination-custom .page-item { list-style: none; }
.pagination-custom .page-link {
    display: flex; align-items: center; justify-content: center;
    width: 36px; height: 36px; border-radius: 8px;
    border: 1.5px solid var(--s200); background: var(--white);
    color: var(--s600); font-size: .75rem; font-weight: 500;
    transition: var(--transition); text-decoration: none;
}
.pagination-custom .page-link:hover {
    background: var(--g50); border-color: var(--g200);
    color: var(--g700); transform: translateY(-1px);
}
.pagination-custom .active .page-link {
    background: var(--g600); border-color: var(--g600);
    color: white;
}

/* ══════════════════════════════════════════════
   DATATABLES OVERRIDES
══════════════════════════════════════════════ */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    padding: 12px 20px;
}
.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
    border: 1.5px solid var(--s200);
    border-radius: var(--r);
    padding: 6px 10px;
    font-size: .8rem;
    background: var(--white);
}
.dataTables_wrapper .dataTables_filter input:focus {
    border-color: var(--g400);
    box-shadow: 0 0 0 3px var(--g100);
    outline: none;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 6px !important;
    border: 1px solid var(--s200) !important;
    background: var(--white) !important;
    color: var(--s600) !important;
    margin: 0 2px;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--g600) !important;
    border-color: var(--g600) !important;
    color: white !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: var(--g50) !important;
    border-color: var(--g200) !important;
    color: var(--g700) !important;
}

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media(max-width:768px){
    .status-page{ padding: 20px; }
    .status-header{ flex-direction: column; align-items: flex-start; }
    .stats-grid{ grid-template-columns:1fr; }
    .action-bar{ flex-direction: column; align-items: stretch; }
    .action-right{ max-width: 100%; }
    .status-card-header{ flex-direction: column; align-items: flex-start; gap: 10px; }
    .status-table{ display: block; overflow-x: auto; }
    .status-table td{ padding: 12px; }
}
</style>

<div class="status-page">
    <!-- Breadcrumb -->
    <div class="status-breadcrumb anim-1">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Statuts des chambres</span>
    </div>

    <!-- Header -->
    <div class="status-header anim-2">
        <div class="status-brand">
            <div class="status-brand-icon"><i class="fas fa-toggle-on"></i></div>
            <div>
                <h1 class="status-header-title">Statuts des <em>chambres</em></h1>
                <p class="status-header-sub">
                    <i class="fas fa-info-circle me-1"></i> Gérez les statuts de disponibilité des chambres
                </p>
            </div>
        </div>
        <div class="status-header-actions">
            <button id="add-button" class="btn-db btn-db-primary">
                <i class="fas fa-plus-circle me-2"></i> Nouveau statut
            </button>
        </div>
    </div>

    <!-- Statistics -->
    <?php
        // Simuler des statistiques - à remplacer par vos vraies données
        $totalStatuses = 8;
        $availableCount = 3;
        $occupiedCount = 2;
        $maintenanceCount = 2;
        $cleaningCount = 1;
    ?>
    
    <div class="stats-grid anim-3">
        <div class="stat-card stat-card--total">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-tags"></i></div>
            </div>
            <div class="stat-card-value"><?php echo e($totalStatuses); ?></div>
            <div class="stat-card-label">Statuts totaux</div>
            <div class="stat-card-footer">
                <i class="fas fa-list"></i>
                Tous les statuts
            </div>
        </div>
        
        <div class="stat-card stat-card--active">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-check-circle"></i></div>
            </div>
            <div class="stat-card-value"><?php echo e($availableCount); ?></div>
            <div class="stat-card-label">Disponibles</div>
            <div class="stat-card-footer">
                <i class="fas fa-door-open"></i>
                Chambres libres
            </div>
        </div>
        
        <div class="stat-card stat-card--occupied">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-user"></i></div>
            </div>
            <div class="stat-card-value"><?php echo e($occupiedCount); ?></div>
            <div class="stat-card-label">Occupés</div>
            <div class="stat-card-footer">
                <i class="fas fa-bed"></i>
                Chambres prises
            </div>
        </div>
        
        <div class="stat-card stat-card--inactive">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-tools"></i></div>
            </div>
            <div class="stat-card-value"><?php echo e($maintenanceCount + $cleaningCount); ?></div>
            <div class="stat-card-label">Hors service</div>
            <div class="stat-card-footer">
                <i class="fas fa-wrench"></i>
                Maintenance/nettoyage
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="action-bar anim-4">
        <div class="action-left">
            <span class="filter-badge">
                <i class="fas fa-toggle-on"></i>
                Tous les statuts
                <span class="badge-count"><?php echo e($totalStatuses); ?></span>
            </span>
        </div>
        
        <div class="action-right">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" 
                       class="search-input" 
                       id="searchInput"
                       placeholder="Rechercher un statut..." 
                       autocomplete="off">
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="status-card anim-5">
        <div class="status-card-header">
            <h5 class="status-card-title">
                <i class="fas fa-toggle-on"></i>
                Liste des statuts
            </h5>
            <span class="status-card-badge">
                <i class="fas fa-list"></i>
                <?php echo e($totalStatuses); ?> enregistrés
            </span>
        </div>
        
        <div class="status-card-body">
            <div style="overflow-x:auto;">
                <table id="roomstatus-table" class="status-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Code</th>
                            <th>Information</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Les données seront chargées via DataTables -->
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination (gérée par DataTables) -->
            <div id="pagination-wrapper"></div>
        </div>
    </div>
</div>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Sample data for room statuses
    var statusData = [
        { id: 1, name: 'Disponible', code: 'AVBL', info: 'Chambre prête pour check-in', statusClass: 'available' },
        { id: 2, name: 'Occupée', code: 'OCC', info: 'Chambre actuellement occupée', statusClass: 'occupied' },
        { id: 3, name: 'Maintenance', code: 'MNT', info: 'Chambre en maintenance', statusClass: 'maintenance' },
        { id: 4, name: 'Nettoyage', code: 'CLN', info: 'Chambre en cours de nettoyage', statusClass: 'cleaning' },
        { id: 5, name: 'Réservée', code: 'RSV', info: 'Chambre réservée', statusClass: 'reserved' },
        { id: 6, name: 'Hors service', code: 'OOO', info: 'Chambre temporairement indisponible', statusClass: 'out-of-order' },
        { id: 7, name: 'Ne pas déranger', code: 'DND', info: 'Client demande la tranquillité', statusClass: 'occupied' },
        { id: 8, name: 'Départ', code: 'COUT', info: 'Chambre en attente de nettoyage', statusClass: 'cleaning' }
    ];

    // Function to get status badge class
    function getStatusClass(name) {
        const statusMap = {
            'Disponible': 'available',
            'Occupée': 'occupied',
            'Maintenance': 'maintenance',
            'Nettoyage': 'cleaning',
            'Réservée': 'reserved',
            'Hors service': 'out-of-order',
            'Ne pas déranger': 'occupied',
            'Départ': 'cleaning'
        };
        return statusMap[name] || 'available';
    }

    // Initialize DataTable
    var table = $('#roomstatus-table').DataTable({
        data: statusData,
        columns: [
            { 
                data: 'id',
                render: function(data) {
                    return '<span class="badge badge--dark" style="background:var(--g600); color:white; padding:4px 8px;">#' + data + '</span>';
                }
            },
            { 
                data: 'name',
                render: function(data, type, row) {
                    var statusClass = getStatusClass(data);
                    var icon = data === 'Disponible' ? 'check-circle' : 
                               (data === 'Occupée' ? 'user' : 
                               (data === 'Maintenance' ? 'tools' : 
                               (data === 'Nettoyage' ? 'broom' : 
                               (data === 'Réservée' ? 'calendar-check' : 
                               (data === 'Hors service' ? 'exclamation-triangle' : 'circle')))));
                    
                    return '<span class="status-badge-custom status-badge--' + statusClass + '">' +
                           '<i class="fas fa-' + icon + ' fa-xs"></i>' +
                           data +
                           '</span>';
                }
            },
            { 
                data: 'code',
                render: function(data) {
                    return '<span class="code-badge">' + data + '</span>';
                }
            },
            { 
                data: 'info',
                render: function(data) {
                    return '<span style="color:var(--s600);">' + data + '</span>';
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    return '<div class="action-group">' +
                           '<button class="btn-db-icon btn-db-icon-view" onclick="viewStatus(' + row.id + ')" title="Voir">' +
                           '<i class="fas fa-eye"></i>' +
                           '</button>' +
                           '<button class="btn-db-icon btn-db-icon-edit" onclick="editStatus(' + row.id + ')" title="Modifier">' +
                           '<i class="fas fa-edit"></i>' +
                           '</button>' +
                           '<button class="btn-db-icon btn-db-icon-delete" onclick="deleteStatus(' + row.id + ', \'' + row.name + '\')" title="Supprimer">' +
                           '<i class="fas fa-trash"></i>' +
                           '</button>' +
                           '</div>';
                }
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json',
            emptyTable: "Aucun statut disponible",
            info: "Affichage de _START_ à _END_ sur _TOTAL_ statuts",
            infoEmpty: "Affichage 0 à 0 sur 0 statuts",
            infoFiltered: "(filtré sur _MAX_ statuts au total)",
            lengthMenu: "Afficher _MENU_ statuts",
            search: "Rechercher :",
            zeroRecords: "Aucun statut trouvé"
        },
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Tous"]],
        initComplete: function() {
            // Hide default search input
            $('.dataTables_filter').hide();
            
            // Connect custom search
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });
        },
        drawCallback: function() {
            // Reinitialize tooltips
            $('[title]').tooltip();
        }
    });

    // Add button click handler
    $('#add-button').on('click', function() {
        Swal.fire({
            title: 'Ajouter un statut',
            html: `
                <div style="text-align: left;">
                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: 600; color: var(--s600); margin-bottom: 5px; display: block;">
                            <i class="fas fa-tag" style="color: var(--g500); margin-right: 5px;"></i>Nom du statut
                        </label>
                        <input id="statusName" class="swal2-input" placeholder="Ex: Disponible" style="width: 100%;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: 600; color: var(--s600); margin-bottom: 5px; display: block;">
                            <i class="fas fa-code" style="color: var(--g500); margin-right: 5px;"></i>Code
                        </label>
                        <input id="statusCode" class="swal2-input" placeholder="Ex: AVBL" style="width: 100%;">
                    </div>
                    <div>
                        <label style="font-weight: 600; color: var(--s600); margin-bottom: 5px; display: block;">
                            <i class="fas fa-info-circle" style="color: var(--g500); margin-right: 5px;"></i>Information
                        </label>
                        <textarea id="statusInfo" class="swal2-textarea" placeholder="Description du statut..." style="width: 100%;"></textarea>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save me-2"></i>Ajouter',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Annuler',
            reverseButtons: true,
            confirmButtonColor: '#1e6b2e',
            cancelButtonColor: '#737873',
            preConfirm: () => {
                const name = document.getElementById('statusName').value;
                const code = document.getElementById('statusCode').value;
                const info = document.getElementById('statusInfo').value;
                
                if (!name || !code) {
                    Swal.showValidationMessage('Le nom et le code sont requis');
                    return false;
                }
                
                return { name, code, info };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Ajouter à la table
                var newId = statusData.length + 1;
                var newStatus = {
                    id: newId,
                    name: result.value.name,
                    code: result.value.code,
                    info: result.value.info,
                    statusClass: 'available'
                };
                statusData.push(newStatus);
                table.clear().rows.add(statusData).draw();
                
                Swal.fire({
                    title: 'Succès!',
                    text: `Le statut "${result.value.name}" a été ajouté.`,
                    icon: 'success',
                    confirmButtonColor: '#1e6b2e',
                    timer: 2000
                });
            }
        });
    });
});

// View function
function viewStatus(id) {
    Swal.fire({
        title: 'Détails du statut',
        html: `
            <div style="text-align: left; padding: 10px;">
                <p><strong><i class="fas fa-hashtag" style="color: var(--g500);"></i> ID:</strong> ${id}</p>
                <p><strong><i class="fas fa-tag" style="color: var(--g500);"></i> Nom:</strong> Statut exemple</p>
                <p><strong><i class="fas fa-code" style="color: var(--g500);"></i> Code:</strong> SMP</p>
                <p><strong><i class="fas fa-info-circle" style="color: var(--g500);"></i> Information:</strong> Description détaillée du statut.</p>
            </div>
        `,
        confirmButtonText: '<i class="fas fa-check me-2"></i>Fermer',
        confirmButtonColor: '#1e6b2e'
    });
}

// Edit function
function editStatus(id) {
    Swal.fire({
        title: 'Modifier le statut',
        html: `
            <div style="text-align: left;">
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 600; color: var(--s600); margin-bottom: 5px; display: block;">
                        <i class="fas fa-tag" style="color: var(--g500); margin-right: 5px;"></i>Nom du statut
                    </label>
                    <input id="editStatusName" class="swal2-input" value="Statut exemple" style="width: 100%;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 600; color: var(--s600); margin-bottom: 5px; display: block;">
                        <i class="fas fa-code" style="color: var(--g500); margin-right: 5px;"></i>Code
                    </label>
                    <input id="editStatusCode" class="swal2-input" value="SMP" style="width: 100%;">
                </div>
                <div>
                    <label style="font-weight: 600; color: var(--s600); margin-bottom: 5px; display: block;">
                        <i class="fas fa-info-circle" style="color: var(--g500); margin-right: 5px;"></i>Information
                    </label>
                    <textarea id="editStatusInfo" class="swal2-textarea" style="width: 100%;">Description détaillée</textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-save me-2"></i>Enregistrer',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Annuler',
        reverseButtons: true,
        confirmButtonColor: '#1e6b2e',
        cancelButtonColor: '#737873'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Succès!',
                text: 'Le statut a été modifié.',
                icon: 'success',
                confirmButtonColor: '#1e6b2e',
                timer: 2000
            });
        }
    });
}

// Delete function
function deleteStatus(id, name) {
    Swal.fire({
        title: 'Confirmer la suppression',
        html: `<strong>${name}</strong> sera supprimé définitivement.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-trash me-2"></i>Oui, supprimer',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Annuler',
        reverseButtons: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#737873'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Supprimé!',
                text: `Le statut "${name}" a été supprimé.`,
                icon: 'success',
                confirmButtonColor: '#1e6b2e',
                timer: 2000
            });
        }
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/roomstatus/index.blade.php ENDPATH**/ ?>