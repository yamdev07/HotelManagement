
<?php $__env->startSection('title', 'Inventaire des chambres'); ?>
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
    /* COULEURS DE STATUT */
    --blue: #3b82f6;
    --blue-light: #dbeafe;
    --amber: #f59e0b;
    --amber-light: #fef3c7;
    --red: #b91c1c;
    --red-light: #fee2e2;
    --purple: #8b5cf6;
    --purple-light: #ede9fe;

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

.inventory-page {
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
.breadcrumb-custom {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .8rem;
    color: var(--s400);
    margin-bottom: 20px;
}
.breadcrumb-custom a {
    color: var(--s400);
    text-decoration: none;
    transition: var(--transition);
}
.breadcrumb-custom a:hover {
    color: var(--g600);
}
.breadcrumb-custom .separator {
    color: var(--s300);
}
.breadcrumb-custom .current {
    color: var(--s600);
    font-weight: 500;
}

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.inventory-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.inventory-brand { display: flex; align-items: center; gap: 14px; }
.inventory-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.inventory-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.inventory-header-title em { font-style: normal; color: var(--g600); }
.inventory-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.inventory-header-actions { display: flex; align-items: center; gap: 10px; }

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
    transform: translateY(-1px);
    text-decoration: none;
}
.btn-db-icon {
    width: 36px;
    height: 36px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--r);
    border: 1.5px solid var(--s200);
    background: var(--white);
    color: var(--s500);
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}
.btn-db-icon:hover {
    background: var(--g50);
    border-color: var(--g300);
    color: var(--g600);
    transform: translateY(-1px);
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
    border: 1.5px solid var(--s100);
    border-radius: var(--rl);
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: var(--transition);
    box-shadow: var(--shadow-xs);
}
.stat-card:hover {
    transform: translateY(-2px);
    border-color: var(--g200);
    box-shadow: var(--shadow-md);
}
.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.stat-icon.primary { background: var(--g50); color: var(--g600); }
.stat-icon.success { background: var(--g50); color: var(--g600); }
.stat-icon.warning { background: var(--amber-light); color: var(--amber); }
.stat-icon.info { background: var(--blue-light); color: var(--blue); }
.stat-content { flex: 1; }
.stat-number {
    font-size: 2rem;
    font-weight: 700;
    font-family: var(--mono);
    line-height: 1;
    color: var(--s900);
    margin-bottom: 4px;
}
.stat-label {
    font-size: .7rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--s400);
    letter-spacing: .4px;
}

/* ══════════════════════════════════════════════
   TABLES
══════════════════════════════════════════════ */
.table-container {
    background: var(--white);
    border: 1.5px solid var(--s100);
    border-radius: var(--rxl);
    overflow: hidden;
    margin-bottom: 28px;
    box-shadow: var(--shadow-sm);
}
.table-header {
    padding: 18px 22px;
    border-bottom: 1.5px solid var(--s100);
    background: var(--white);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}
.table-header h5 {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .95rem;
    font-weight: 600;
    color: var(--s800);
    margin: 0;
}
.table-header h5 i {
    color: var(--g600);
}
.table-responsive {
    overflow-x: auto;
}
.table-modern {
    width: 100%;
    border-collapse: collapse;
}
.table-modern thead th {
    background: var(--surface);
    color: var(--s500);
    font-weight: 600;
    font-size: .68rem;
    text-transform: uppercase;
    letter-spacing: .6px;
    padding: 14px 18px;
    border-bottom: 1.5px solid var(--s100);
    white-space: nowrap;
    text-align: left;
}
.table-modern tbody td {
    padding: 14px 18px;
    font-size: .82rem;
    color: var(--s700);
    border-bottom: 1px solid var(--s100);
    vertical-align: middle;
}
.table-modern tbody tr {
    transition: var(--transition);
}
.table-modern tbody tr:hover td {
    background: var(--g50);
}
.table-modern tbody tr:last-child td {
    border-bottom: none;
}

/* Badges */
.badge-modern {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 100px;
    font-size: .68rem;
    font-weight: 600;
    white-space: nowrap;
}
.badge-success { background: var(--g50); color: var(--g700); border: 1px solid var(--g200); }
.badge-warning { background: var(--amber-light); color: #92400e; border: 1px solid var(--amber); }
.badge-danger { background: var(--red-light); color: var(--red); border: 1px solid #fecaca; }
.badge-info { background: var(--blue-light); color: var(--blue); border: 1px solid var(--blue-light); }
.badge-dark { background: var(--s700); color: white; }

/* ── Room type cell ── */
.room-type-info {
    display: flex;
    align-items: center;
    gap: 12px;
}
.room-type-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: var(--g50);
    color: var(--g600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .9rem;
    flex-shrink: 0;
}
.room-type-name {
    font-weight: 600;
    color: var(--s800);
}
.room-type-price {
    font-size: .68rem;
    color: var(--s400);
    margin-top: 2px;
}

/* ── Progress bar ── */
.progress-container {
    display: flex;
    align-items: center;
    gap: 8px;
    width: 160px;
}
.progress-modern {
    height: 8px;
    background: var(--s100);
    border-radius: 4px;
    overflow: hidden;
    flex: 1;
}
.progress-bar-modern {
    height: 100%;
    border-radius: 4px;
    transition: width .6s ease;
}
.progress-bar-success { background: var(--g600); }
.progress-bar-warning { background: var(--amber); }
.progress-bar-info { background: var(--blue); }

/* ══════════════════════════════════════════════
   STATUS GRID
══════════════════════════════════════════════ */
.status-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
.status-card {
    background: var(--white);
    border: 1.5px solid var(--s100);
    border-radius: var(--rl);
    overflow: hidden;
    transition: var(--transition);
}
.status-card:hover {
    border-color: var(--g200);
    box-shadow: var(--shadow-md);
}
.status-card-header {
    padding: 14px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    font-size: .85rem;
}
.status-card-header.success { background: var(--g600); color: white; }
.status-card-header.danger { background: var(--red); color: white; }
.status-card-header.info { background: var(--blue); color: white; }
.status-card-header.warning { background: var(--amber); color: white; }
.status-card-header.secondary { background: var(--s600); color: white; }
.status-card-body {
    padding: 16px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    background: var(--surface);
}

.room-chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 12px;
    background: var(--white);
    border: 1.5px solid var(--s200);
    border-radius: 100px;
    font-size: .75rem;
    font-weight: 500;
    color: var(--s700);
    text-decoration: none;
    transition: var(--transition);
}
.room-chip:hover {
    background: var(--g50);
    border-color: var(--g300);
    color: var(--g700);
    transform: translateY(-1px);
    text-decoration: none;
}
.room-chip i {
    font-size: .65rem;
    color: var(--s400);
}
.room-chip:hover i {
    color: var(--g600);
}

/* ── Empty state ── */
.empty-state-small {
    text-align: center;
    padding: 32px 16px;
}
.empty-state-small i {
    font-size: 2.5rem;
    color: var(--s300);
    margin-bottom: 12px;
}
.empty-state-small h6 {
    font-size: .9rem;
    font-weight: 600;
    color: var(--s600);
    margin-bottom: 4px;
}
.empty-state-small p {
    font-size: .75rem;
    color: var(--s400);
}

/* ── Badge de mise à jour ── */
.update-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 14px;
    background: var(--g50);
    border: 1.5px solid var(--g200);
    border-radius: 100px;
    font-size: .75rem;
    font-weight: 500;
    color: var(--g700);
}
</style>

<div class="inventory-page">

    
    <div class="breadcrumb-custom anim-1">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Inventaire</span>
    </div>

    
    <div class="inventory-header anim-2">
        <div class="inventory-brand">
            <div class="inventory-brand-icon"><i class="fas fa-clipboard-list"></i></div>
            <div>
                <h1 class="inventory-header-title">Inventaire des <em>chambres</em></h1>
                <div class="inventory-header-sub">
                    <span>Statut et occupation des chambres en temps réel</span>
                </div>
            </div>
        </div>
        <div class="inventory-header-actions">
            <a href="<?php echo e(route('availability.calendar')); ?>" class="btn-db btn-db-ghost">
                <i class="fas fa-calendar-alt"></i> Calendrier
            </a>
            <a href="<?php echo e(route('availability.dashboard')); ?>" class="btn-db btn-db-ghost">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <button class="btn-db btn-db-primary" onclick="window.print()">
                <i class="fas fa-print"></i> Imprimer
            </button>
        </div>
    </div>

    
    <div class="stats-grid anim-3">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-bed"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo e($stats['total_rooms']); ?></div>
                <div class="stat-label">Chambres totales</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo e($stats['available_rooms']); ?></div>
                <div class="stat-label">Chambres disponibles</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo e($stats['occupied_rooms']); ?></div>
                <div class="stat-label">Chambres occupées</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo e(number_format($stats['occupancy_rate'], 1)); ?>%</div>
                <div class="stat-label">Taux d'occupation</div>
            </div>
        </div>
    </div>

    
    <div class="table-container anim-4">
        <div class="table-header">
            <h5>
                <i class="fas fa-list-alt"></i>
                Inventaire par type de chambre
            </h5>
            <div class="update-badge">
                <i class="fas fa-clock fa-xs"></i>
                Mis à jour: <?php echo e(now()->format('H:i')); ?>

            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Type de chambre</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Disponibles</th>
                        <th class="text-center">Occupées</th>
                        <th class="text-center">Nettoyage</th>
                        <th class="text-center">Maintenance</th>
                        <th class="text-center">Taux d'occupation</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $roomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $totalRooms = $type->rooms->count();
                        $occupiedRooms = $occupancyByType[$type->name]['occupied'] ?? 0;
                        $percentage = $occupancyByType[$type->name]['percentage'] ?? 0;
                        
                        $available = $type->rooms->where('room_status_id', 1)->count();
                        $cleaning = $type->rooms->where('room_status_id', 3)->count();
                        $maintenance = $type->rooms->where('room_status_id', 2)->count();
                        
                        $progressClass = $percentage > 80 ? 'progress-bar-success' : ($percentage > 50 ? 'progress-bar-warning' : 'progress-bar-info');
                        
                        $prices = $type->rooms->pluck('price')->filter()->unique()->sort();
                        $avgPrice = $type->rooms->avg('price');
                        $minPrice = $type->rooms->min('price');
                        $maxPrice = $type->rooms->max('price');
                    ?>
                    <tr>
                        <td>
                            <div class="room-type-info">
                                <div class="room-type-icon">
                                    <i class="fas fa-bed"></i>
                                </div>
                                <div>
                                    <div class="room-type-name"><?php echo e($type->name); ?></div>
                                    <div class="room-type-price">
                                        <?php if($avgPrice): ?>
                                            <?php if($minPrice && $maxPrice && $minPrice != $maxPrice): ?>
                                                <?php echo e(number_format($minPrice, 0, ',', ' ')); ?> - <?php echo e(number_format($maxPrice, 0, ',', ' ')); ?> FCFA
                                            <?php else: ?>
                                                <?php echo e(number_format($avgPrice, 0, ',', ' ')); ?> FCFA
                                            <?php endif; ?>
                                            <span class="text-muted">/nuit</span>
                                        <?php else: ?>
                                            <span class="text-muted">Prix non défini</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge-modern badge-dark"><?php echo e($totalRooms); ?></span>
                        </td>
                        <td class="text-center">
                            <span class="badge-modern badge-success"><?php echo e($available); ?></span>
                        </td>
                        <td class="text-center">
                            <span class="badge-modern badge-warning"><?php echo e($occupiedRooms); ?></span>
                        </td>
                        <td class="text-center">
                            <span class="badge-modern badge-info"><?php echo e($cleaning); ?></span>
                        </td>
                        <td class="text-center">
                            <span class="badge-modern badge-danger"><?php echo e($maintenance); ?></span>
                        </td>
                        <td>
                            <div class="progress-container">
                                <div class="progress-modern">
                                    <div class="progress-bar-modern <?php echo e($progressClass); ?>" style="width: <?php echo e($percentage); ?>%"></div>
                                </div>
                                <span style="font-size: .7rem; font-weight: 600; color: var(--s700); min-width: 45px;"><?php echo e(number_format($percentage, 1)); ?>%</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="<?php echo e(route('availability.search', ['room_type_id' => $type->id])); ?>" 
                               class="btn-db-icon"
                               data-bs-toggle="tooltip" 
                               title="Voir les chambres">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="table-container anim-5">
        <div class="table-header" style="background: linear-gradient(135deg, var(--blue), var(--g600)); color: white;">
            <h5 style="color: white;">
                <i class="fas fa-clipboard-check" style="color: white;"></i>
                Chambres par statut
            </h5>
        </div>
        
        <div class="card-body-modern" style="padding: 20px;">
            <div class="status-grid">
                <?php $__currentLoopData = $roomsByStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusId => $rooms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $status = $rooms->first()->roomStatus ?? null;
                    if (!$status) continue;
                    
                    $color = match($statusId) {
                        1 => 'success',
                        2 => 'danger',
                        3 => 'info',
                        4 => 'warning',
                        default => 'secondary'
                    };
                    
                    $icon = match($statusId) {
                        1 => 'fa-check-circle',
                        2 => 'fa-tools',
                        3 => 'fa-broom',
                        4 => 'fa-clock',
                        default => 'fa-circle'
                    };
                    
                    $headerClass = match($statusId) {
                        1 => 'success',
                        2 => 'danger',
                        3 => 'info',
                        4 => 'warning',
                        default => 'secondary'
                    };
                ?>
                
                <div class="status-card">
                    <div class="status-card-header <?php echo e($headerClass); ?>">
                        <div>
                            <i class="fas <?php echo e($icon); ?> me-2"></i>
                            <strong><?php echo e($status->name); ?></strong>
                        </div>
                        <span class="badge bg-light text-dark"><?php echo e($rooms->count()); ?></span>
                    </div>
                    <div class="status-card-body">
                        <?php $__currentLoopData = $rooms->take(12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('availability.room.detail', $room->id)); ?>" 
                           class="room-chip"
                           data-bs-toggle="tooltip" 
                           title="<?php echo e($room->type->name ?? 'Chambre'); ?> · <?php echo e(number_format($room->price, 0, ',', ' ')); ?> FCFA">
                            <i class="fas fa-bed"></i>
                            <?php echo e($room->number); ?>

                            <?php if($statusId == 2): ?>
                                <i class="fas fa-tools ms-1" style="font-size:.6rem;"></i>
                            <?php elseif($statusId == 3): ?>
                                <i class="fas fa-broom ms-1" style="font-size:.6rem;"></i>
                            <?php endif; ?>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if($rooms->count() > 12): ?>
                            <span class="room-chip" style="background: var(--s100); border-color: var(--s200);">
                                <i class="fas fa-plus-circle"></i>
                                <?php echo e($rooms->count() - 12); ?> autres
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    
    <div class="text-end mt-3 anim-6">
        <span class="update-badge">
            <i class="fas fa-sync-alt fa-xs"></i>
            Dernière mise à jour: <?php echo e(now()->format('d/m/Y H:i:s')); ?>

        </span>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            placement: 'top',
            delay: { show: 100, hide: 100 }
        });
    });
    
    // Rafraîchissement automatique toutes les 60 secondes
    let refreshTimeout = setTimeout(function() {
        window.location.reload();
    }, 60000);
    
    // Annuler le rafraîchissement si l'utilisateur interagit
    document.addEventListener('click', function() {
        clearTimeout(refreshTimeout);
        refreshTimeout = setTimeout(function() {
            window.location.reload();
        }, 60000);
    });
    
    // Animation des progress bars au chargement
    document.querySelectorAll('.progress-bar-modern').forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 300);
    });
    
    console.log('Inventaire mis à jour: <?php echo e(now()->format("H:i:s")); ?>');
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/availability/inventory.blade.php ENDPATH**/ ?>