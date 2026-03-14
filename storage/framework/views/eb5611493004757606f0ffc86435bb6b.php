

<?php $__env->startSection('title', 'Housekeeping Mobile'); ?>

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
    --mono: 'DM Mono', monospace;
}

* { box-sizing: border-box; margin: 0; padding: 0; }

.mobile-page {
    background: var(--surface);
    min-height: 100vh;
    font-family: var(--font);
    color: var(--gray-800);
}

/* ── Header ── */
.mobile-header {
    background: var(--white);
    border-bottom: 1.5px solid var(--gray-200);
    padding: 16px 20px;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: var(--shadow-xs);
}
.header-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
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
    font-size: 1.2rem;
    box-shadow: 0 4px 10px rgba(46,133,64,.3);
}
.header-text h1 {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 2px;
}
.header-text p {
    font-size: .7rem;
    color: var(--gray-500);
}
.header-actions {
    display: flex;
    gap: 8px;
}
.btn-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--r);
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    color: var(--gray-600);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
}
.btn-icon:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}

/* ── Search ── */
.search-bar {
    padding: 0 20px 12px;
}
.search-input {
    width: 100%;
    padding: 12px 16px 12px 44px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .85rem;
    background: var(--white) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%239ba09b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'%3E%3C/circle%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'%3E%3C/line%3E%3C/svg%3E") no-repeat 16px center;
}
.search-input:focus {
    outline: none;
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgba(46,133,64,.1);
}

/* ── Stats cards ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    padding: 16px 20px;
}
.stat-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 12px 8px;
    text-align: center;
}
.stat-value {
    font-size: 1.4rem;
    font-weight: 700;
    font-family: var(--mono);
    color: var(--gray-900);
    line-height: 1;
    margin-bottom: 2px;
}
.stat-value.red { color: var(--red-500); }
.stat-value.green { color: var(--green-600); }
.stat-label {
    font-size: .6rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
}

/* ── Section ── */
.section {
    padding: 0 20px 24px;
}
.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}
.section-title {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: .9rem;
    font-weight: 600;
    color: var(--gray-700);
}
.section-title i {
    color: var(--green-600);
}
.section-badge {
    background: var(--green-50);
    color: var(--green-700);
    border: 1.5px solid var(--green-200);
    padding: 4px 12px;
    border-radius: 100px;
    font-size: .7rem;
    font-weight: 600;
}

/* ── Room cards ── */
.room-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    margin-bottom: 12px;
    transition: var(--transition);
    border-left: 4px solid var(--gray-200);
}
.room-card.red { border-left-color: var(--red-500); }
.room-card.green { border-left-color: var(--green-600); }
.room-card.yellow { border-left-color: var(--red-500); }
.room-card:hover {
    transform: translateX(4px);
    border-color: var(--green-300);
}
.room-content {
    padding: 16px;
}
.room-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
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
    font-size: .9rem;
}
.room-badge.red { background: var(--red-50); color: var(--red-500); }
.room-badge.green { background: var(--green-50); color: var(--green-600); }
.room-badge.yellow { background: var(--red-50); color: var(--red-500); }
.room-type {
    font-size: .8rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 4px;
}
.room-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: .7rem;
    color: var(--gray-500);
    margin-bottom: 12px;
}
.room-meta i {
    color: var(--green-600);
    width: 14px;
}
.room-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 100px;
    font-size: .7rem;
    font-weight: 600;
    margin-bottom: 12px;
}
.room-status.red { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }
.room-status.green { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.room-status.yellow { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }
.timer {
    background: var(--gray-100);
    padding: 4px 10px;
    border-radius: 100px;
    font-size: .65rem;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.room-actions {
    display: flex;
    gap: 8px;
    margin-top: 12px;
}
.btn-room {
    flex: 1;
    padding: 10px;
    border-radius: var(--r);
    border: none;
    font-weight: 600;
    font-size: .7rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    cursor: pointer;
    transition: var(--transition);
}
.btn-green {
    background: var(--green-600);
    color: white;
}
.btn-green:hover {
    background: var(--green-700);
}
.btn-red {
    background: var(--red-500);
    color: white;
}
.btn-red:hover {
    background: var(--red-600);
}
.btn-outline {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    color: var(--gray-600);
}
.btn-outline:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}

/* ── Side item (départs/arrivées) ── */
.side-item {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 14px;
    margin-bottom: 8px;
}
.side-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 6px;
}
.side-room {
    font-size: 1rem;
    font-weight: 700;
    font-family: var(--mono);
    color: var(--gray-800);
}
.side-badge {
    padding: 2px 8px;
    border-radius: 100px;
    font-size: .6rem;
    font-weight: 600;
}
.side-badge.red { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }
.side-badge.green { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.side-info {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: .75rem;
}
.side-name {
    font-weight: 600;
    color: var(--gray-700);
}
.side-time {
    color: var(--gray-500);
    display: flex;
    align-items: center;
    gap: 4px;
}

/* ── Quick actions grid ── */
.quick-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    padding: 0 20px 16px;
}
.quick-item {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 14px 8px;
    text-align: center;
    text-decoration: none;
    transition: var(--transition);
}
.quick-item:hover {
    border-color: var(--green-300);
    transform: translateY(-2px);
}
.quick-item i {
    font-size: 1.2rem;
    color: var(--green-600);
    margin-bottom: 4px;
    display: block;
}
.quick-item span {
    font-size: .65rem;
    font-weight: 600;
    color: var(--gray-700);
    display: block;
    line-height: 1.2;
}
.quick-item small {
    font-size: .55rem;
    color: var(--gray-500);
}

/* ── Reason tags ── */
.reason-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 0 20px 16px;
}
.reason-tag {
    background: var(--green-50);
    color: var(--green-700);
    border: 1.5px solid var(--green-200);
    padding: 6px 12px;
    border-radius: 100px;
    font-size: .65rem;
    font-weight: 500;
}

/* ── Summary card ── */
.summary-card {
    background: var(--green-600);
    border-radius: var(--rxl);
    padding: 20px;
    margin: 0 20px 20px;
    color: white;
}
.summary-header {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .7rem;
    opacity: .9;
    margin-bottom: 12px;
}
.summary-content {
    display: flex;
    align-items: center;
    gap: 16px;
}
.summary-icon {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
}
.summary-stats {
    flex: 1;
}
.summary-stats h3 {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 4px;
}
.summary-stats p {
    font-size: .7rem;
    opacity: .9;
}
.summary-progress {
    margin-top: 16px;
    height: 6px;
    background: rgba(255,255,255,.2);
    border-radius: 100px;
    overflow: hidden;
}
.summary-bar {
    height: 100%;
    background: white;
    border-radius: 100px;
}

/* ── Empty state ── */
.empty-state {
    padding: 40px 20px;
    text-align: center;
}
.empty-state i {
    font-size: 2.5rem;
    color: var(--green-500);
    margin-bottom: 12px;
}
.empty-state h4 {
    font-size: .9rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 4px;
}
.empty-state p {
    color: var(--gray-400);
    font-size: .7rem;
}

/* ── Modal ── */
.modal-content {
    border-radius: var(--rxl);
    border: 1.5px solid var(--gray-200);
}
.modal-header {
    border-bottom: 1.5px solid var(--gray-200);
    padding: 18px 22px;
}
.modal-title i {
    color: var(--green-600);
}
.modal-body {
    padding: 22px;
}
.modal-footer {
    border-top: 1.5px solid var(--gray-200);
    padding: 16px 22px;
}
.form-label {
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray-600);
    text-transform: uppercase;
    margin-bottom: 6px;
}
.form-control, .form-select {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .8rem;
}
.form-control:focus, .form-select:focus {
    outline: none;
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgba(46,133,64,.1);
}
</style>

<div class="mobile-page">

    
    <div class="mobile-header">
        <div class="header-top">
            <div class="header-title">
                <div class="header-icon"><i class="fas fa-broom"></i></div>
                <div class="header-text">
                    <h1>Housekeeping</h1>
                    <p><?php echo e(now()->format('l d F Y')); ?></p>
                </div>
            </div>
            <div class="header-actions">
                <a href="<?php echo e(route('housekeeping.index')); ?>" class="btn-icon"><i class="fas fa-desktop"></i></a>
                <a href="<?php echo e(route('housekeeping.scan')); ?>" class="btn-icon"><i class="fas fa-qrcode"></i></a>
            </div>
        </div>
        <div class="search-bar">
            <input type="text" class="search-input" id="searchRooms" placeholder="Rechercher une chambre...">
        </div>
    </div>

    
    <div class="stats-grid">
        <div class="stat-card"><div class="stat-value red"><?php echo e($stats['dirty'] ?? 0); ?></div><div class="stat-label">À nettoyer</div></div>
        <div class="stat-card"><div class="stat-value red"><?php echo e($stats['cleaning'] ?? 0); ?></div><div class="stat-label">En cours</div></div>
        <div class="stat-card"><div class="stat-value green"><?php echo e($stats['clean'] ?? 0); ?></div><div class="stat-label">Nettoyées</div></div>
        <div class="stat-card"><div class="stat-value"><?php echo e($stats['occupied'] ?? 0); ?></div><div class="stat-label">Occupées</div></div>
    </div>

    
    <?php if(($todayDepartures ?? collect())->count() > 0): ?>
    <div class="section">
        <div class="section-header">
            <div class="section-title"><i class="fas fa-sign-out-alt"></i> Départs aujourd'hui</div>
            <span class="section-badge"><?php echo e($todayDepartures->count()); ?></span>
        </div>
        <?php $__currentLoopData = $todayDepartures->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="side-item">
            <div class="side-header">
                <div class="side-room">#<?php echo e($d->room->number); ?></div>
                <div class="side-badge red"><i class="fas fa-clock"></i> 12h00</div>
            </div>
            <div class="side-info">
                <span class="side-name"><?php echo e($d->customer->name ?? 'Client'); ?></span>
                <span class="side-time"><i class="fas fa-bed"></i> <?php echo e($d->room->type->name ?? 'Standard'); ?></span>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    
    <div class="section">
        <div class="section-header">
            <div class="section-title"><i class="fas fa-broom"></i> À nettoyer</div>
            <span class="section-badge"><?php echo e($dirtyRooms->count()); ?></span>
        </div>
        <?php if($dirtyRooms->count() > 0): ?>
            <?php $__currentLoopData = $dirtyRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="room-card red">
                <div class="room-content">
                    <div class="room-header">
                        <div class="room-number">#<?php echo e($room->number); ?></div>
                        <div class="room-badge red"><i class="fas fa-broom"></i></div>
                    </div>
                    <div class="room-type"><?php echo e($room->type->name ?? 'Standard'); ?></div>
                    <div class="room-meta">
                        <i class="fas fa-user"></i> <?php echo e($room->capacity); ?> pers.
                        <?php if($room->floor): ?><i class="fas fa-layer-group"></i> Étage <?php echo e($room->floor); ?><?php endif; ?>
                    </div>
                    <div class="room-status red"><i class="fas fa-exclamation-circle"></i> À nettoyer</div>
                    <div class="room-actions">
                        <form action="<?php echo e(route('housekeeping.start-cleaning', $room->id)); ?>" method="POST" style="flex:1">
                            <?php echo csrf_field(); ?>
                            <button class="btn-room btn-green"><i class="fas fa-play"></i> Commencer</button>
                        </form>
                        <button class="btn-room btn-outline" onclick="openMaintenanceModal('<?php echo e($room->number); ?>')"><i class="fas fa-tools"></i></button>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-check-circle" style="color:var(--green-600);"></i>
            <h4>Aucune chambre à nettoyer</h4>
            <p>Toutes les chambres sont propres</p>
        </div>
        <?php endif; ?>
    </div>

    
    <?php if($cleaningRooms->count() > 0): ?>
    <div class="section">
        <div class="section-header">
            <div class="section-title"><i class="fas fa-spinner"></i> En cours</div>
            <span class="section-badge"><?php echo e($cleaningRooms->count()); ?></span>
        </div>
        <?php $__currentLoopData = $cleaningRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $dur = \Carbon\Carbon::parse($room->cleaning_started_at ?? now())->diffForHumans(now(), true); ?>
        <div class="room-card yellow">
            <div class="room-content">
                <div class="room-header">
                    <div class="room-number">#<?php echo e($room->number); ?></div>
                    <div class="room-badge yellow"><i class="fas fa-spinner"></i></div>
                </div>
                <div class="room-type"><?php echo e($room->type->name ?? 'Standard'); ?></div>
                <div class="room-meta"><i class="fas fa-clock"></i> <span class="timer"><i class="fas fa-hourglass-half"></i> <?php echo e($dur); ?></span></div>
                <div class="room-status yellow"><i class="fas fa-spinner fa-spin"></i> En nettoyage</div>
                <div class="room-actions">
                    <form action="<?php echo e(route('housekeeping.mark-cleaned', $room->id)); ?>" method="POST" style="flex:1">
                        <?php echo csrf_field(); ?>
                        <button class="btn-room btn-green"><i class="fas fa-check"></i> Terminer</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    
    <?php if(($todayArrivals ?? collect())->count() > 0): ?>
    <div class="section">
        <div class="section-header">
            <div class="section-title"><i class="fas fa-sign-in-alt"></i> Arrivées aujourd'hui</div>
            <span class="section-badge"><?php echo e($todayArrivals->count()); ?></span>
        </div>
        <?php $__currentLoopData = $todayArrivals->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="side-item">
            <div class="side-header">
                <div class="side-room">#<?php echo e($a->room->number); ?></div>
                <div class="side-badge green"><i class="fas fa-clock"></i> 14h00</div>
            </div>
            <div class="side-info">
                <span class="side-name"><?php echo e($a->customer->name ?? 'Client'); ?></span>
                <span class="side-time"><i class="fas fa-bed"></i> <?php echo e($a->room->type->name ?? 'Standard'); ?></span>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    
    <?php if(isset($stats['maintenance_by_reason']) && count($stats['maintenance_by_reason']) > 0): ?>
    <div class="section-header" style="padding:0 20px 8px;">
        <div class="section-title"><i class="fas fa-chart-pie"></i> Raisons</div>
    </div>
    <div class="reason-tags">
        <?php $__currentLoopData = $stats['maintenance_by_reason']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reason => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <span class="reason-tag"><?php echo e($reason); ?>: <?php echo e($count); ?></span>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    
    <div class="quick-grid">
        <a href="<?php echo e(route('housekeeping.to-clean')); ?>" class="quick-item"><i class="fas fa-list"></i><span>Liste</span><small>complète</small></a>
        <button class="quick-item" onclick="openMaintenanceModal()"><i class="fas fa-tools"></i><span>Maintenance</span><small>signaler</small></button>
        <a href="<?php echo e(route('housekeeping.scan')); ?>" class="quick-item"><i class="fas fa-qrcode"></i><span>Scanner</span><small>QR code</small></a>
        <a href="<?php echo e(route('housekeeping.reports')); ?>" class="quick-item"><i class="fas fa-chart-bar"></i><span>Rapports</span><small>stats</small></a>
    </div>

    
    <?php
        $total = ($stats['dirty'] ?? 0) + ($stats['cleaning'] ?? 0) + ($stats['clean'] ?? 0);
        $progress = $total > 0 ? round(($stats['clean'] ?? 0) / $total * 100) : 0;
    ?>
    <div class="summary-card">
        <div class="summary-header"><i class="fas fa-calendar-day"></i> Résumé du jour</div>
        <div class="summary-content">
            <div class="summary-icon"><i class="fas fa-trophy"></i></div>
            <div class="summary-stats"><h3><?php echo e($stats['cleaned_today'] ?? 0); ?></h3><p>chambres nettoyées</p></div>
        </div>
        <div class="summary-progress"><div class="summary-bar" style="width:<?php echo e($progress); ?>%"></div></div>
    </div>

</div>


<div class="modal fade" id="maintenanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-tools"></i> Signaler maintenance</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label">Chambre</label><input type="text" class="form-control" id="roomNumber" placeholder="Ex: 101"></div>
                <div class="mb-3"><label class="form-label">Raison</label>
                    <select class="form-select" id="maintenanceReason">
                        <option value="Électricité">⚡ Électricité</option>
                        <option value="Plomberie">💧 Plomberie</option>
                        <option value="Climatisation">❄️ Climatisation</option>
                        <option value="Meuble">🪑 Meuble</option>
                        <option value="Sécurité">🔒 Sécurité</option>
                    </select>
                </div>
                <div class="mb-3"><label class="form-label">Durée (h)</label><input type="number" class="form-control" id="estimatedDuration" value="4"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-gray" data-bs-dismiss="modal">Annuler</button>
                <button class="btn btn-green" onclick="submitMaintenance()">Signaler</button>
            </div>
        </div>
    </div>
</div>

<script>
function openMaintenanceModal(room = '') {
    document.getElementById('roomNumber').value = room;
    new bootstrap.Modal(document.getElementById('maintenanceModal')).show();
}

function submitMaintenance() {
    const num = document.getElementById('roomNumber').value;
    if (!num) return alert('Numéro de chambre requis');
    fetch(`/api/rooms/find-by-number/${num}`)
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                const f = document.createElement('form');
                f.method = 'POST';
                f.action = `/housekeeping/room/${d.room.id}/mark-maintenance`;
                f.innerHTML = `<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>"><input type="hidden" name="maintenance_reason" value="${document.getElementById('maintenanceReason').value}"><input type="hidden" name="estimated_duration" value="${document.getElementById('estimatedDuration').value}">`;
                document.body.appendChild(f);
                f.submit();
            } else alert('Chambre non trouvée');
        });
}

document.getElementById('searchRooms').addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase();
    document.querySelectorAll('.room-card').forEach(c => {
        const txt = (c.querySelector('.room-number')?.textContent + c.querySelector('.room-type')?.textContent).toLowerCase();
        c.style.display = txt.includes(term) ? 'block' : 'none';
    });
});

// Auto-refresh
let timer = setTimeout(() => location.reload(), 300000);
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/housekeeping/mobile.blade.php ENDPATH**/ ?>