
<?php $__env->startSection('title', 'Journal d\'activités'); ?>
<?php $__env->startSection('content'); ?>

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
}

* { box-sizing: border-box; margin: 0; padding: 0; }

.act-page {
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
.anim-4 { animation: fadeSlide .4s .24s ease both; }

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
    margin-bottom: 28px;
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
    white-space: nowrap;
}
.btn-green {
    background: var(--green-600);
    color: white;
}
.btn-green:hover {
    background: var(--green-700);
    transform: translateY(-1px);
    color: white;
    text-decoration: none;
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
    transform: translateY(-1px);
    text-decoration: none;
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
.btn-sm {
    padding: 6px 12px;
    font-size: .7rem;
}
.btn-icon {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--r);
    border: 1.5px solid var(--gray-200);
    background: var(--white);
    color: var(--gray-500);
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}
.btn-icon:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
    transform: translateY(-1px);
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
    box-shadow: var(--shadow-xs);
    transition: var(--transition);
}
.card:hover {
    border-color: var(--green-300);
    box-shadow: var(--shadow-md);
}
.card-header {
    padding: 16px 22px;
    border-bottom: 1.5px solid var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.card-header h3 {
    font-size: .95rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.card-header h3 i {
    color: var(--green-600);
}
.card-body {
    padding: 22px;
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
    align-items: center;
    gap: 14px;
    transition: var(--transition);
}
.stat-card:hover {
    border-color: var(--green-300);
    transform: translateY(-2px);
}
.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: var(--green-50);
    color: var(--green-600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.stat-content { flex: 1; }
.stat-label {
    font-size: .65rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    margin-bottom: 4px;
}
.stat-value {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--gray-900);
    line-height: 1;
}
.stat-change {
    font-size: .7rem;
    color: var(--gray-400);
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 6px;
}
.stat-change.positive { color: var(--green-600); }

/* ══════════════════════════════════════════════
   FILTERS
══════════════════════════════════════════════ */
.filter-section {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    padding: 22px;
    margin-bottom: 24px;
}
.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 16px;
}
.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.form-label {
    font-size: .7rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--gray-500);
    letter-spacing: .5px;
}
.form-control,
.form-select {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .8rem;
    color: var(--gray-700);
    background: var(--white);
    transition: var(--transition);
}
.form-control:focus,
.form-select:focus {
    outline: none;
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgba(46,133,64,.1);
}
.filter-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
}
.filter-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: var(--green-50);
    border: 1.5px solid var(--green-200);
    border-radius: 100px;
    font-size: .75rem;
    color: var(--green-700);
}
.filter-badge i {
    color: var(--green-600);
}

/* ══════════════════════════════════════════════
   TABLE
══════════════════════════════════════════════ */
.table-container {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
}
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
    text-transform: uppercase;
    color: var(--gray-500);
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
.table tbody tr {
    transition: var(--transition);
}
.table tbody tr:hover td {
    background: var(--green-50);
}
.table tbody tr:last-child td {
    border-bottom: none;
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
    font-size: .68rem;
    font-weight: 600;
    white-space: nowrap;
}
.badge-green { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.badge-red { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }
.badge-gray { background: var(--gray-100); color: var(--gray-700); border: 1.5px solid var(--gray-200); }
.badge-number {
    background: var(--gray-100);
    color: var(--gray-600);
    padding: 2px 6px;
    border-radius: 4px;
    font-size: .65rem;
}

/* ══════════════════════════════════════════════
   AVATAR
══════════════════════════════════════════════ */
.avatar {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: var(--green-50);
    color: var(--green-600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .8rem;
    font-weight: 600;
    flex-shrink: 0;
}
.avatar img {
    width: 100%;
    height: 100%;
    border-radius: 8px;
    object-fit: cover;
}

/* ══════════════════════════════════════════════
   OBJECT BADGE
══════════════════════════════════════════════ */
.object-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    background: var(--gray-100);
    border-radius: var(--r);
    font-size: .7rem;
    color: var(--gray-600);
}
.object-badge i {
    color: var(--green-600);
}

/* ══════════════════════════════════════════════
   DETAILS COLLAPSE
══════════════════════════════════════════════ */
.details-collapse {
    margin-top: 10px;
    background: var(--gray-50);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    padding: 14px;
}
.details-pre {
    background: var(--gray-800);
    color: var(--gray-300);
    padding: 12px;
    border-radius: var(--r);
    font-size: .7rem;
    font-family: 'DM Mono', monospace;
    overflow-x: auto;
    max-height: 200px;
}

/* ══════════════════════════════════════════════
   PAGINATION
══════════════════════════════════════════════ */
.pagination-modern {
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
}
.pagination-modern .page-item {
    list-style: none;
}
.pagination-modern .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: var(--r);
    border: 1.5px solid var(--gray-200);
    background: var(--white);
    color: var(--gray-600);
    font-size: .75rem;
    font-weight: 500;
    transition: var(--transition);
    text-decoration: none;
}
.pagination-modern .page-link:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.pagination-modern .active .page-link {
    background: var(--green-600);
    border-color: var(--green-600);
    color: white;
}
.per-page-select {
    width: 70px;
    padding: 6px 8px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .75rem;
    color: var(--gray-700);
    background: var(--white);
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    text-align: center;
    padding: 48px 24px;
}
.empty-state i {
    font-size: 3rem;
    color: var(--gray-300);
    margin-bottom: 16px;
}
.empty-state h5 {
    font-size: .95rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 4px;
}
.empty-state p {
    color: var(--gray-400);
    font-size: .8rem;
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
    padding: 18px 24px;
}
.modal-header h5 i {
    color: var(--green-600);
}
.modal-body {
    padding: 24px;
}
.modal-footer {
    border-top: 1.5px solid var(--gray-200);
    padding: 16px 24px;
}
.modal pre {
    background: var(--gray-800);
    color: var(--gray-300);
    padding: 16px;
    border-radius: var(--r);
    font-size: .7rem;
    font-family: 'DM Mono', monospace;
    overflow-x: auto;
}

/* ══════════════════════════════════════════════
   ALERT
══════════════════════════════════════════════ */
.alert {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 18px;
    border-radius: var(--rl);
    border: 1.5px solid;
    margin-bottom: 20px;
}
.alert-green {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.alert-icon {
    width: 28px;
    height: 28px;
    border-radius: var(--r);
    background: var(--green-600);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* ══════════════════════════════════════════════
   DROPDOWN
══════════════════════════════════════════════ */
.dropdown-menu {
    border-radius: var(--rl);
    border: 1.5px solid var(--gray-200);
    padding: 6px;
    box-shadow: var(--shadow-md);
}
.dropdown-item {
    border-radius: var(--r);
    padding: 8px 14px;
    font-size: .8rem;
    color: var(--gray-700);
    transition: var(--transition);
}
.dropdown-item:hover {
    background: var(--green-50);
    color: var(--green-700);
}
.dropdown-item i {
    width: 20px;
    color: var(--green-600);
}
</style>

<div class="act-page">

    
    <div class="breadcrumb anim-1">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Journal d'activités</span>
    </div>

    
    <div class="page-header anim-2">
        <div class="header-title">
            <span class="header-icon"><i class="fas fa-history"></i></span>
            <h1>Journal d'<em>activités</em></h1>
        </div>
        <p class="header-subtitle">Consultez l'historique des actions système</p>
    </div>

    
    <div class="stats-grid anim-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-history"></i></div>
            <div class="stat-content">
                <div class="stat-label">Total activités</div>
                <div class="stat-value"><?php echo e($activities->total()); ?></div>
                <div class="stat-change"><i class="fas fa-calendar-alt"></i> <?php echo e($activities->lastPage()); ?> pages</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-calendar-week"></i></div>
            <div class="stat-content">
                <div class="stat-label">Cette semaine</div>
                <div class="stat-value"><?php echo e($weeklyCount ?? 0); ?></div>
                <div class="stat-change <?php echo e(isset($weeklyChange) && $weeklyChange > 0 ? 'positive' : ''); ?>">
                    <?php if(isset($weeklyChange)): ?>
                        <i class="fas <?php echo e($weeklyChange > 0 ? 'fa-arrow-up' : 'fa-arrow-down'); ?>"></i>
                        <?php echo e(abs($weeklyChange)); ?>%
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-content">
                <div class="stat-label">Utilisateurs actifs</div>
                <div class="stat-value"><?php echo e($activeUsersCount ?? $users->count()); ?></div>
                <div class="stat-change"><i class="fas fa-clock"></i> 24h</div>
            </div>
        </div>
    </div>

    
    <div class="filter-section anim-4">
        <form method="GET" action="<?php echo e(route('activity.index')); ?>" id="filterForm">
            <div class="filter-grid">
                <div class="form-group">
                    <label class="form-label">Utilisateur</label>
                    <select name="user_id" class="form-select">
                        <option value="">Tous</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>><?php echo e($user->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Événement</label>
                    <select name="event" class="form-select">
                        <option value="">Tous</option>
                        <option value="created" <?php echo e(request('event') == 'created' ? 'selected' : ''); ?>>Création</option>
                        <option value="updated" <?php echo e(request('event') == 'updated' ? 'selected' : ''); ?>>Modification</option>
                        <option value="deleted" <?php echo e(request('event') == 'deleted' ? 'selected' : ''); ?>>Suppression</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Date début</label>
                    <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Date fin</label>
                    <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Objet</label>
                    <select name="subject_type" class="form-select">
                        <option value="">Tous</option>
                        <option value="App\Models\User" <?php echo e(request('subject_type') == 'App\Models\User' ? 'selected' : ''); ?>>Utilisateurs</option>
                        <option value="App\Models\Room" <?php echo e(request('subject_type') == 'App\Models\Room' ? 'selected' : ''); ?>>Chambres</option>
                        <option value="App\Models\Transaction" <?php echo e(request('subject_type') == 'App\Models\Transaction' ? 'selected' : ''); ?>>Transactions</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Recherche</label>
                    <input type="text" name="search" class="form-control" placeholder="Description..." value="<?php echo e(request('search')); ?>">
                </div>
            </div>
            
            <div class="filter-actions">
                <button type="submit" class="btn btn-green">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
                <a href="<?php echo e(route('activity.index')); ?>" class="btn btn-gray">
                    <i class="fas fa-times"></i> Réinitialiser
                </a>
                <button type="button" class="btn btn-gray" data-bs-toggle="modal" data-bs-target="#cleanupModal">
                    <i class="fas fa-broom"></i> Nettoyer
                </button>
                
                <div class="dropdown" style="margin-left:auto;">
                    <button class="btn btn-gray dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-download"></i> Exporter
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo e(route('activity.export', ['format' => 'csv'])); ?>"><i class="fas fa-file-csv"></i> CSV</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('activity.export', ['format' => 'json'])); ?>"><i class="fas fa-file-code"></i> JSON</a></li>
                    </ul>
                </div>
            </div>
        </form>
        
        <?php if(request()->anyFilled(['user_id', 'event', 'date_from', 'date_to', 'search', 'subject_type'])): ?>
            <div class="filter-badge mt-4">
                <i class="fas fa-info-circle"></i>
                <span><?php echo e($activities->total()); ?> résultat(s) trouvé(s)</span>
                <a href="<?php echo e(route('activity.index')); ?>" class="btn-icon" style="width:auto; padding:0 6px;"><i class="fas fa-times"></i></a>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="table-container">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date & Heure</th>
                        <th>Action</th>
                        <th>Utilisateur</th>
                        <th>Objet</th>
                        <th>Événement</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $eventColor = match($activity->event) {
                                'created' => 'badge-green',
                                'updated' => 'badge-gray',
                                'deleted' => 'badge-red',
                                default => 'badge-gray'
                            };
                            $eventIcon = match($activity->event) {
                                'created' => 'fa-plus-circle',
                                'updated' => 'fa-edit',
                                'deleted' => 'fa-trash-alt',
                                default => 'fa-history'
                            };
                            $eventLabel = match($activity->event) {
                                'created' => 'Création',
                                'updated' => 'Modification',
                                'deleted' => 'Suppression',
                                default => ucfirst($activity->event)
                            };
                            
                            $modelName = $activity->subject ? class_basename($activity->subject_type) : 'Inconnu';
                            $modelIcon = match($modelName) {
                                'User' => 'fa-user',
                                'Room' => 'fa-bed',
                                'Transaction' => 'fa-receipt',
                                default => 'fa-cube'
                            };
                        ?>
                        
                        <tr>
                            <td><span class="badge-number"><?php echo e(($activities->currentPage() - 1) * $activities->perPage() + $loop->iteration); ?></span></td>
                            
                            <td>
                                <div style="font-weight:500;"><?php echo e($activity->created_at->format('d/m/Y')); ?></div>
                                <div style="font-size:.7rem; color:var(--gray-500);"><?php echo e($activity->created_at->format('H:i:s')); ?></div>
                            </td>
                            
                            <td>
                                <div style="font-weight:500;"><?php echo e($activity->description); ?></div>
                                <?php if($activity->properties->count() > 0): ?>
                                    <button class="btn btn-sm btn-gray" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#details-<?php echo e($activity->id); ?>">
                                        <i class="fas fa-code"></i> Détails
                                    </button>
                                    <div class="collapse details-collapse" id="details-<?php echo e($activity->id); ?>">
                                        <pre class="details-pre"><?php echo e(json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                                    </div>
                                <?php endif; ?>
                            </td>
                            
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar">
                                        <?php if($activity->causer && $activity->causer->avatar): ?>
                                            <img src="<?php echo e(asset('storage/' . $activity->causer->avatar)); ?>" alt="">
                                        <?php else: ?>
                                            <i class="fas fa-user"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div style="font-weight:500;"><?php echo e($activity->causer->name ?? 'Système'); ?></div>
                                        <div style="font-size:.65rem; color:var(--gray-500);"><?php echo e($activity->causer->email ?? ''); ?></div>
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                <div class="object-badge">
                                    <i class="fas <?php echo e($modelIcon); ?>"></i>
                                    <span><?php echo e($modelName); ?></span>
                                </div>
                            </td>
                            
                            <td>
                                <span class="badge <?php echo e($eventColor); ?>">
                                    <i class="fas <?php echo e($eventIcon); ?>"></i>
                                    <?php echo e($eventLabel); ?>

                                </span>
                            </td>
                            
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <button class="btn-icon" onclick="showActivityDetails(<?php echo e($activity->id); ?>)" title="Détails">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a href="<?php echo e(route('activity.show', $activity->id)); ?>" class="btn-icon" title="Ouvrir">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-history"></i>
                                    <h5>Aucune activité trouvée</h5>
                                    <p>Aucun log d'activité n'a été enregistré</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($activities->hasPages()): ?>
        <div style="padding: 16px 22px; border-top: 1.5px solid var(--gray-200);">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div style="font-size:.75rem; color:var(--gray-500);">
                    <?php echo e($activities->firstItem()); ?> - <?php echo e($activities->lastItem()); ?> sur <?php echo e($activities->total()); ?>

                </div>
                
                <ul class="pagination-modern">
                    <li class="page-item <?php echo e($activities->onFirstPage() ? 'disabled' : ''); ?>">
                        <a class="page-link" href="<?php echo e($activities->url(1)); ?>"><i class="fas fa-angle-double-left"></i></a>
                    </li>
                    <li class="page-item <?php echo e($activities->onFirstPage() ? 'disabled' : ''); ?>">
                        <a class="page-link" href="<?php echo e($activities->previousPageUrl()); ?>"><i class="fas fa-angle-left"></i></a>
                    </li>
                    
                    <?php for($i = max(1, $activities->currentPage() - 2); $i <= min($activities->lastPage(), $activities->currentPage() + 2); $i++): ?>
                        <li class="page-item <?php echo e($i == $activities->currentPage() ? 'active' : ''); ?>">
                            <a class="page-link" href="<?php echo e($activities->url($i)); ?>"><?php echo e($i); ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <li class="page-item <?php echo e(!$activities->hasMorePages() ? 'disabled' : ''); ?>">
                        <a class="page-link" href="<?php echo e($activities->nextPageUrl()); ?>"><i class="fas fa-angle-right"></i></a>
                    </li>
                    <li class="page-item <?php echo e(!$activities->hasMorePages() ? 'disabled' : ''); ?>">
                        <a class="page-link" href="<?php echo e($activities->url($activities->lastPage())); ?>"><i class="fas fa-angle-double-right"></i></a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-2">
                    <span style="font-size:.7rem; color:var(--gray-500);">Lignes:</span>
                    <select class="per-page-select" onchange="changePerPage(this)">
                        <option value="10" <?php echo e($activities->perPage() == 10 ? 'selected' : ''); ?>>10</option>
                        <option value="25" <?php echo e($activities->perPage() == 25 ? 'selected' : ''); ?>>25</option>
                        <option value="50" <?php echo e($activities->perPage() == 50 ? 'selected' : ''); ?>>50</option>
                    </select>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="modal fade" id="activityModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-info-circle me-2" style="color:var(--green-600);"></i> Détails de l'activité</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="activityModalBody"></div>
                <div class="modal-footer">
                    <button class="btn btn-gray" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="cleanupModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-broom me-2" style="color:var(--green-600);"></i> Nettoyer les logs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('activity.cleanup')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <p style="margin-bottom:16px;">Supprimer les logs plus anciens que :</p>
                        <div class="form-group mb-4">
                            <input type="number" name="days" class="form-control" min="1" max="365" value="30">
                            <small class="text-muted">(jours)</small>
                        </div>
                        <div class="alert alert-green">
                            <div class="alert-icon"><i class="fas fa-info-circle"></i></div>
                            <div>Cette action est irréversible. <?php echo e($activities->total()); ?> logs analysés.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-gray" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-red"><i class="fas fa-broom me-2"></i>Nettoyer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
function showActivityDetails(id) {
    fetch(`/activity/${id}/details`)
        .then(r => r.json())
        .then(data => {
            const body = document.getElementById('activityModalBody');
            body.innerHTML = `
                <div class="mb-4">
                    <div style="display:grid; grid-template-columns:100px 1fr; gap:8px;">
                        <span class="text-muted">ID:</span><span>${data.id}</span>
                        <span class="text-muted">Date:</span><span>${data.created_at}</span>
                        <span class="text-muted">Événement:</span><span><span class="badge ${data.event_color === 'success' ? 'badge-green' : (data.event_color === 'danger' ? 'badge-red' : 'badge-gray')}">${data.event_label}</span></span>
                        <span class="text-muted">IP:</span><span>${data.ip_address || 'N/A'}</span>
                    </div>
                </div>
                <h6 class="fw-bold mb-2">Propriétés</h6>
                <pre>${JSON.stringify(data.properties, null, 2)}</pre>
            `;
            new bootstrap.Modal(document.getElementById('activityModal')).show();
        });
}

function changePerPage(select) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', select.value);
    window.location.href = url;
}
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\HotelManagement\resources\views/activity/index.blade.php ENDPATH**/ ?>