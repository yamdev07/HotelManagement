
<?php $__env->startSection('title', 'Types de Chambres'); ?>
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

.types-page {
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
.types-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.types-brand { display: flex; align-items: center; gap: 14px; }
.types-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.types-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.types-header-title em { font-style: normal; color: var(--g600); }
.types-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.types-header-sub i { color: var(--g500); }
.types-header-actions { display: flex; align-items: center; gap: 10px; }

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
.btn-db-icon {
    width: 36px; height: 36px; padding: 0;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: var(--r); font-size: .8rem;
    background: var(--white); color: var(--s400);
    border: 1.5px solid var(--s200); cursor: pointer;
    transition: var(--transition); text-decoration: none;
    font-family: var(--font);
}
.btn-db-icon:hover {
    background: var(--g50); color: var(--g600);
    border-color: var(--g200); transform: translateY(-1px);
}
.btn-db-icon-danger:hover {
    background: #fee2e2; color: #b91c1c;
    border-color: #fecaca;
}
.btn-db-icon:disabled {
    opacity: 0.5; cursor: not-allowed;
    pointer-events: none;
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
.stat-card--inactive { --bar-c: var(--s400); }
.stat-card--rooms { --bar-c: var(--g300); }

.stat-card-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
.stat-card-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.stat-card--total .stat-card-icon { background: var(--g100); color: var(--g600); }
.stat-card--active .stat-card-icon { background: var(--g50); color: var(--g600); }
.stat-card--inactive .stat-card-icon { background: var(--s100); color: var(--s500); }
.stat-card--rooms .stat-card-icon { background: var(--g50); color: var(--g500); }

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
   ALERTES
══════════════════════════════════════════════ */
.alert-modern {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 18px; border-radius: var(--rl);
    margin-bottom: 20px; border: 1.5px solid transparent;
    font-size: .875rem; background: var(--white);
    box-shadow: var(--shadow-sm);
}
.alert-success {
    background: var(--g50); border-color: var(--g200);
    color: var(--g700);
}
.alert-danger {
    background: #fee2e2; border-color: #fecaca;
    color: #b91c1c;
}
.alert-icon {
    width: 28px; height: 28px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.alert-success .alert-icon { background: var(--g100); color: var(--g600); }
.alert-danger .alert-icon { background: #fecaca; color: #b91c1c; }
.alert-close {
    margin-left: auto; background: none; border: none;
    color: currentColor; opacity: .6; cursor: pointer;
    font-size: 1rem; transition: var(--transition);
}
.alert-close:hover { opacity: 1; }

/* ══════════════════════════════════════════════
   CARTE PRINCIPALE
══════════════════════════════════════════════ */
.types-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 20px; box-shadow: var(--shadow-sm);
}
.types-card-header {
    padding: 18px 24px;
    border-bottom: 1.5px solid var(--s100);
    background: var(--white);
    display: flex; align-items: center; justify-content: space-between;
}
.types-card-title {
    display: flex; align-items: center; gap: 10px;
    font-size: .95rem; font-weight: 600; color: var(--s800); margin: 0;
}
.types-card-title i { color: var(--g500); }
.types-card-body { padding: 0; }
.types-card-footer {
    padding: 16px 24px;
    border-top: 1.5px solid var(--s100);
    background: var(--surface);
}

/* ══════════════════════════════════════════════
   TABLEAU
══════════════════════════════════════════════ */
.types-table {
    width: 100%; border-collapse: collapse;
}
.types-table thead th {
    font-size: .65rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .7px; color: var(--s400);
    padding: 14px 20px; background: var(--surface);
    border-bottom: 1.5px solid var(--s100); white-space: nowrap;
}
.types-table tbody tr {
    border-bottom: 1px solid var(--s100); transition: var(--transition);
}
.types-table tbody tr:last-child { border-bottom: none; }
.types-table tbody tr:hover { background: var(--g50); }
.types-table td {
    padding: 16px 20px; vertical-align: middle;
}

/* ══════════════════════════════════════════════
   AVATAR / ICÔNE
══════════════════════════════════════════════ */
.type-avatar {
    width: 48px; height: 48px; border-radius: var(--rl);
    background: var(--g50); color: var(--g600);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}

/* ══════════════════════════════════════════════
   BADGES
══════════════════════════════════════════════ */
.badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 6px; font-size: .65rem;
    font-weight: 600; white-space: nowrap;
}
.badge--success {
    background: var(--g100); color: var(--g700);
    border: 1px solid var(--g200);
}
.badge--warning {
    background: #fff3cd; color: #856404;
    border: 1px solid #ffeeba;
}
.badge--info {
    background: var(--g50); color: var(--g600);
    border: 1px solid var(--g200);
}
.badge--gray {
    background: var(--s100); color: var(--s600);
    border: 1px solid var(--s200);
}
.badge--dark {
    background: var(--s800); color: white;
    border: none;
}
.badge--popular {
    background: var(--g100); color: var(--g700);
    border: 1px solid var(--g200);
    font-size: .6rem;
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
   HELP SECTION
══════════════════════════════════════════════ */
.help-card {
    background: var(--g50); border-radius: var(--rl);
    border: 1.5px solid var(--g200); padding: 20px;
    margin-top: 24px;
}
.help-icon {
    width: 32px; height: 32px; border-radius: 8px;
    background: var(--g100); color: var(--g600);
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem;
}
.help-text {
    font-size: .75rem; color: var(--s600);
}
.help-text strong { color: var(--s800); }

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media(max-width:768px){
    .types-page{ padding: 20px; }
    .types-header{ flex-direction: column; align-items: flex-start; }
    .stats-grid{ grid-template-columns:1fr; }
    .types-card-header{ flex-direction: column; align-items: flex-start; gap: 10px; }
    .types-table{ display: block; overflow-x: auto; }
    .types-table td{ padding: 12px; }
}
</style>

<div class="types-page">
    <!-- Header -->
    <div class="types-header anim-1">
        <div class="types-brand">
            <div class="types-brand-icon"><i class="fas fa-list-alt"></i></div>
            <div>
                <h1 class="types-header-title">Types de <em>chambres</em></h1>
                <p class="types-header-sub">
                    <i class="fas fa-bed me-1"></i> <?php echo e($types->count()); ?> type(s) disponible(s)
                </p>
            </div>
        </div>
        <div class="types-header-actions">
            <a href="<?php echo e(route('type.create')); ?>" class="btn-db btn-db-primary">
                <i class="fas fa-plus-circle me-2"></i> Nouveau type
            </a>
            <a href="<?php echo e(route('room.index')); ?>" class="btn-db btn-db-ghost">
                <i class="fas fa-bed me-2"></i> Voir les chambres
            </a>
        </div>
    </div>

    <!-- Alertes -->
    <?php if(session('success')): ?>
    <div class="alert-modern alert-success anim-2">
        <div class="alert-icon"><i class="fas fa-check-circle"></i></div>
        <span><?php echo e(session('success')); ?></span>
        <button class="alert-close" onclick="this.parentElement.remove()">×</button>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="alert-modern alert-danger anim-2">
        <div class="alert-icon"><i class="fas fa-exclamation-circle"></i></div>
        <span><?php echo e(session('error')); ?></span>
        <button class="alert-close" onclick="this.parentElement.remove()">×</button>
    </div>
    <?php endif; ?>

    <!-- Statistiques -->
    <?php
        $activeTypes = $types->where('is_active', true)->count();
        $inactiveTypes = $types->count() - $activeTypes;
        $totalRooms = 0;
        foreach($types as $type) {
            $totalRooms += $type->rooms->count();
        }
    ?>

    <div class="stats-grid anim-3">
        <div class="stat-card stat-card--total">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-list-alt"></i></div>
            </div>
            <div class="stat-card-value"><?php echo e($types->count()); ?></div>
            <div class="stat-card-label">Total types</div>
            <div class="stat-card-footer">
                <i class="fas fa-tag"></i>
                Types de chambres
            </div>
        </div>

        <div class="stat-card stat-card--active">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-check-circle"></i></div>
            </div>
            <div class="stat-card-value"><?php echo e($activeTypes); ?></div>
            <div class="stat-card-label">Actifs</div>
            <div class="stat-card-footer">
                <i class="fas fa-eye"></i>
                Visibles
            </div>
        </div>

        <div class="stat-card stat-card--inactive">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-eye-slash"></i></div>
            </div>
            <div class="stat-card-value"><?php echo e($inactiveTypes); ?></div>
            <div class="stat-card-label">Inactifs</div>
            <div class="stat-card-footer">
                <i class="fas fa-clock"></i>
                Masqués
            </div>
        </div>

        <div class="stat-card stat-card--rooms">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-bed"></i></div>
            </div>
            <div class="stat-card-value"><?php echo e($totalRooms); ?></div>
            <div class="stat-card-label">Chambres</div>
            <div class="stat-card-footer">
                <i class="fas fa-door-open"></i>
                Au total
            </div>
        </div>
    </div>

    <!-- Tableau principal -->
    <div class="types-card anim-4">
        <div class="types-card-header">
            <h5 class="types-card-title">
                <i class="fas fa-list-alt"></i>
                Tous les types de chambres
            </h5>
            <button class="btn-db btn-db-ghost" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
        <div class="types-card-body">
            <?php if($types->count() > 0): ?>
                <div style="overflow-x:auto;">
                    <table class="types-table">
                        <thead>
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Détails</th>
                                <th>Tarif</th>
                                <th>Capacité</th>
                                <th class="text-center">Statut</th>
                                <th class="text-center">Chambres</th>
                                <th class="pe-4 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $roomCount = $type->rooms->count();
                                    $isPopular = $roomCount > 5;
                                ?>
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge badge--dark">#<?php echo e($type->id); ?></span>
                                    </td>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:12px;">
                                            <div class="type-avatar">
                                                <i class="fas fa-bed"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold" style="color:var(--s800); margin-bottom:4px;">
                                                    <?php echo e($type->name); ?>

                                                </div>
                                                <?php if($type->information): ?>
                                                    <div style="font-size:.7rem; color:var(--s400); max-width:250px;">
                                                        <?php echo e(Str::limit($type->information, 60)); ?>

                                                    </div>
                                                <?php else: ?>
                                                    <div style="font-size:.7rem; color:var(--s400);">
                                                        <i class="fas fa-minus"></i> Aucune description
                                                    </div>
                                                <?php endif; ?>
                                                <?php if($isPopular): ?>
                                                    <span class="badge badge--popular mt-1">
                                                        <i class="fas fa-star me-1"></i> Populaire
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($type->base_price): ?>
                                            <div style="font-size:.9rem; font-weight:600; color:var(--s800); font-family:var(--mono);">
                                                <?php echo e(number_format($type->base_price, 0, ',', ' ')); ?> FCFA
                                            </div>
                                            <div style="font-size:.65rem; color:var(--s400);">prix de base</div>
                                        <?php else: ?>
                                            <span style="color:var(--s400); font-size:.7rem;">Non défini</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:6px;">
                                            <i class="fas fa-users" style="color:var(--g500); font-size:.7rem;"></i>
                                            <span style="font-size:.8rem; color:var(--s800);"><?php echo e($type->capacity ?? 1); ?> personne(s)</span>
                                        </div>
                                        <?php if($type->bed_type): ?>
                                            <div style="font-size:.65rem; color:var(--s400); margin-top:2px;">
                                                <i class="fas fa-bed"></i> Lit <?php echo e($type->bed_type); ?>

                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($type->is_active): ?>
                                            <span class="badge badge--success">
                                                <i class="fas fa-check-circle me-1"></i> Actif
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge--gray">
                                                <i class="fas fa-eye-slash me-1"></i> Inactif
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div style="display:flex; flex-direction:column; align-items:center;">
                                            <span class="badge badge--info" style="padding:6px 12px;">
                                                <i class="fas fa-door-closed me-1"></i>
                                                <?php echo e($roomCount); ?>

                                            </span>
                                            <?php if($roomCount > 0): ?>
                                                <div style="font-size:.6rem; color:var(--s400); margin-top:2px;">
                                                    <?php echo e($roomCount); ?> chambre(s)
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div style="display:flex; gap:4px; justify-content:flex-end;">
                                            <!-- Edit -->
                                            <a href="<?php echo e(route('type.edit', $type->id)); ?>" 
                                               class="btn-db-icon" 
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <!-- Voir les chambres -->
                                            <?php if($roomCount > 0): ?>
                                                <a href="<?php echo e(route('room.index')); ?>?type=<?php echo e($type->id); ?>" 
                                                   class="btn-db-icon" 
                                                   title="Voir les chambres">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <!-- Supprimer -->
                                            <form method="POST" 
                                                  action="<?php echo e(route('type.destroy', $type->id)); ?>"
                                                  style="display:inline"
                                                  onsubmit="return confirmDelete('<?php echo e($type->name); ?>', <?php echo e($roomCount); ?>)">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" 
                                                        class="btn-db-icon btn-db-icon-danger"
                                                        title="Supprimer"
                                                        <?php echo e($roomCount > 0 ? 'disabled' : ''); ?>>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Footer -->
                <div class="types-card-footer">
                    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                        <div style="font-size:.7rem; color:var(--s400);">
                            <i class="fas fa-info-circle me-1"></i>
                            Affichage de <?php echo e($types->count()); ?> type(s) de chambre
                        </div>
                        <div style="font-size:.7rem; color:var(--s400);">
                            <i class="fas fa-ban me-1" style="color:var(--s400);"></i>
                            La suppression est désactivée pour les types ayant des chambres assignées
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Empty state -->
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-bed"></i></div>
                    <p class="empty-title">Aucun type de chambre</p>
                    <p class="empty-text">
                        Commencez par créer votre premier type de chambre.<br>
                        Les types aident à organiser vos chambres par catégorie et tarif.
                    </p>
                    <a href="<?php echo e(route('type.create')); ?>" class="btn-db btn-db-primary">
                        <i class="fas fa-plus-circle me-2"></i>
                        Créer un type
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Help Section -->
    <?php if($types->count() > 0): ?>
    <div class="help-card anim-5">
        <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
            <div style="display:flex; align-items:center; gap:12px;">
                <div class="help-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div class="help-text">
                    <strong>Besoin d'aide pour gérer les types de chambres ?</strong><br>
                    Les types définissent des catégories comme "Standard", "Deluxe" ou "Suite". 
                    Chaque type peut avoir des tarifs, capacités et équipements différents.
                </div>
            </div>
            <div style="margin-left:auto;">
                <a href="<?php echo e(route('room.index')); ?>" class="btn-db btn-db-ghost">
                    <i class="fas fa-bed me-2"></i>
                    Gérer les chambres
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Auto-hide alerts après 5 secondes
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-modern');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
});

// Confirmation de suppression
function confirmDelete(typeName, roomCount) {
    if (roomCount > 0) {
        alert(`Impossible de supprimer "${typeName}" car ${roomCount} chambre(s) y sont assignées.\n\nVeuillez d'abord réassigner ou supprimer ces chambres.`);
        return false;
    }
    
    return confirm(`Êtes-vous sûr de vouloir supprimer "${typeName}" ?\n\nCette action est irréversible.`);
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/type/index.blade.php ENDPATH**/ ?>