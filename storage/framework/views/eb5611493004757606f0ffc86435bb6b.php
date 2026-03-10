

<?php $__env->startSection('title', 'Housekeeping Mobile'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
    /* Palette principale - Vert */
    --primary-50: #E8F5F0;
    --primary-100: #C1E4D6;
    --primary-200: #96D3BA;
    --primary-300: #6BC29E;
    --primary-400: #4BB589;
    --primary-500: #2AA874;
    --primary-600: #25A06C;
    --primary-700: #1F9661;
    --primary-800: #198C57;
    --primary-900: #0F7C44;

    /* Couleurs d'état */
    --dirty: #EF4444;
    --dirty-dim: rgba(239, 68, 68, 0.1);
    --cleaning: #F59E0B;
    --cleaning-dim: rgba(245, 158, 11, 0.1);
    --clean: #10B981;
    --clean-dim: rgba(16, 185, 129, 0.1);
    --occupied: #3B82F6;
    --occupied-dim: rgba(59, 130, 246, 0.1);
    --maintenance: #8B5CF6;
    --maintenance-dim: rgba(139, 92, 246, 0.1);
    --reserved: #EC4899;
    --reserved-dim: rgba(236, 72, 153, 0.1);

    /* Neutres */
    --gray-50: #F9FAFB;
    --gray-100: #F3F4F6;
    --gray-200: #E5E7EB;
    --gray-300: #D1D5DB;
    --gray-400: #9CA3AF;
    --gray-500: #6B7280;
    --gray-600: #4B5563;
    --gray-700: #374151;
    --gray-800: #1F2937;
    --gray-900: #111827;

    /* Ombres */
    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    
    /* Transitions */
    --transition: all 0.2s ease;
    
    /* Border radius */
    --r-sm: 10px;
    --r-md: 14px;
    --r-lg: 20px;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: var(--gray-50);
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--gray-800);
    line-height: 1.5;
}

/* Header */
.mobile-header {
    background: white;
    padding: 20px 16px;
    position: sticky;
    top: 0;
    z-index: 100;
    border-bottom: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
}

.mobile-header__top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.mobile-header__title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.mobile-header__icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary-600), var(--primary-400));
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 22px;
    box-shadow: 0 4px 8px rgba(42, 168, 116, 0.25);
}

.mobile-header__text h1 {
    font-size: 20px;
    font-weight: 700;
    color: var(--gray-800);
    margin-bottom: 4px;
}

.mobile-header__text p {
    font-size: 12px;
    color: var(--gray-500);
}

.mobile-header__actions {
    display: flex;
    gap: 8px;
}

/* Boutons */
.btn-mobile {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 16px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    border: none;
    transition: var(--transition);
    cursor: pointer;
    text-decoration: none;
}

.btn-mobile-primary {
    background: linear-gradient(135deg, var(--primary-600), var(--primary-400));
    color: white;
    box-shadow: 0 4px 8px rgba(42, 168, 116, 0.25);
}

.btn-mobile-primary:hover {
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    transform: translateY(-1px);
}

.btn-mobile-outline {
    background: white;
    border: 1px solid var(--gray-200);
    color: var(--gray-700);
}

.btn-mobile-outline:hover {
    border-color: var(--primary-400);
    color: var(--primary-600);
}

.btn-mobile-success {
    background: var(--clean);
    color: white;
}

.btn-mobile-success:hover {
    background: #059669;
}

.btn-mobile-warning {
    background: var(--cleaning);
    color: white;
}

.btn-mobile-warning:hover {
    background: #D97706;
}

.btn-mobile-danger {
    background: var(--dirty);
    color: white;
}

.btn-mobile-danger:hover {
    background: #DC2626;
}

/* Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    padding: 16px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 12px 8px;
    text-align: center;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
}

.stat-card__value {
    font-size: 20px;
    font-weight: 800;
    color: var(--gray-800);
    line-height: 1;
    margin-bottom: 4px;
    font-family: 'IBM Plex Mono', monospace;
}

.stat-card__label {
    font-size: 10px;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.stat-card.dirty .stat-card__value { color: var(--dirty); }
.stat-card.cleaning .stat-card__value { color: var(--cleaning); }
.stat-card.clean .stat-card__value { color: var(--clean); }
.stat-card.occupied .stat-card__value { color: var(--occupied); }

/* Search Bar */
.search-bar {
    padding: 0 16px 16px;
}

.search-input {
    width: 100%;
    padding: 14px 16px 14px 44px;
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    font-size: 14px;
    background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%239CA3AF' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'%3E%3C/circle%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'%3E%3C/line%3E%3C/svg%3E") no-repeat 16px center;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px rgba(42, 168, 116, 0.1);
}

/* Sections */
.section {
    padding: 0 16px 20px;
}

.section__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.section__title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    font-weight: 700;
    color: var(--gray-700);
}

.section__title i {
    color: var(--primary-500);
}

.section__badge {
    background: var(--primary-100);
    color: var(--primary-700);
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
}

/* Room Cards */
.room-card {
    background: white;
    border-radius: 14px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    margin-bottom: 12px;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
}

.room-card:hover {
    transform: translateX(4px);
    box-shadow: var(--shadow-md);
}

.room-card.dirty { border-left: 4px solid var(--dirty); }
.room-card.cleaning { border-left: 4px solid var(--cleaning); }
.room-card.clean { border-left: 4px solid var(--clean); }
.room-card.occupied { border-left: 4px solid var(--occupied); }
.room-card.maintenance { border-left: 4px solid var(--maintenance); }
.room-card.reserved { border-left: 4px solid var(--reserved); }

.room-card__content {
    padding: 16px;
}

.room-card__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}

.room-card__number {
    font-size: 20px;
    font-weight: 800;
    color: var(--gray-800);
    font-family: 'IBM Plex Mono', monospace;
}

.room-card__badge {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.room-card__badge.dirty { background: var(--dirty-dim); color: var(--dirty); }
.room-card__badge.cleaning { background: var(--cleaning-dim); color: var(--cleaning); }
.room-card__badge.clean { background: var(--clean-dim); color: var(--clean); }
.room-card__badge.occupied { background: var(--occupied-dim); color: var(--occupied); }
.room-card__badge.maintenance { background: var(--maintenance-dim); color: var(--maintenance); }

.room-card__type {
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 4px;
}

.room-card__meta {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 12px;
    color: var(--gray-500);
    margin-bottom: 12px;
}

.room-card__meta i {
    color: var(--primary-400);
    width: 14px;
}

.room-card__status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
}

.room-card__status.dirty { background: var(--dirty-dim); color: var(--dirty); }
.room-card__status.cleaning { background: var(--cleaning-dim); color: var(--cleaning); }
.room-card__status.clean { background: var(--clean-dim); color: var(--clean); }
.room-card__status.occupied { background: var(--occupied-dim); color: var(--occupied); }
.room-card__status.maintenance { background: var(--maintenance-dim); color: var(--maintenance); }

/* Action Buttons */
.room-actions {
    display: flex;
    gap: 8px;
    margin-top: 12px;
}

.btn-action {
    flex: 1;
    padding: 12px;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    font-size: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: pointer;
    transition: var(--transition);
}

.btn-action-primary {
    background: linear-gradient(135deg, var(--primary-600), var(--primary-400));
    color: white;
}

.btn-action-success {
    background: var(--clean);
    color: white;
}

.btn-action-warning {
    background: var(--cleaning);
    color: white;
}

.btn-action-danger {
    background: var(--dirty);
    color: white;
}

.btn-action-outline {
    background: white;
    border: 1px solid var(--gray-200);
    color: var(--gray-700);
}

.btn-action-outline:hover {
    border-color: var(--primary-400);
    color: var(--primary-600);
}

/* Timer */
.timer-badge {
    background: var(--gray-100);
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    color: var(--gray-600);
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.timer-badge i {
    color: var(--cleaning);
}

/* Side Items (Départs/Arrivées) */
.side-item {
    background: white;
    border-radius: 12px;
    padding: 14px;
    margin-bottom: 8px;
    border: 1px solid var(--gray-200);
}

.side-item__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}

.side-item__room {
    font-size: 18px;
    font-weight: 700;
    color: var(--gray-800);
    font-family: 'IBM Plex Mono', monospace;
}

.side-item__badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.side-item__badge.departure {
    background: var(--dirty-dim);
    color: var(--dirty);
}

.side-item__badge.arrival {
    background: var(--clean-dim);
    color: var(--clean);
}

.side-item__info {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 13px;
}

.side-item__name {
    font-weight: 600;
    color: var(--gray-700);
}

.side-item__time {
    color: var(--gray-500);
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Quick Actions Grid */
.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    padding: 16px;
}

.quick-action-item {
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    padding: 16px 8px;
    text-align: center;
    text-decoration: none;
    transition: var(--transition);
}

.quick-action-item:hover {
    transform: translateY(-3px);
    border-color: var(--primary-400);
    box-shadow: var(--shadow-md);
}

.quick-action-item i {
    font-size: 22px;
    color: var(--primary-500);
    margin-bottom: 8px;
    display: block;
}

.quick-action-item span {
    font-size: 12px;
    font-weight: 600;
    color: var(--gray-700);
    display: block;
    margin-bottom: 2px;
}

.quick-action-item small {
    font-size: 10px;
    color: var(--gray-500);
}

/* Summary Card */
.summary-card {
    background: linear-gradient(135deg, var(--primary-600), var(--primary-400));
    border-radius: 16px;
    padding: 20px;
    margin: 16px;
    color: white;
}

.summary-card__header {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 16px;
    opacity: 0.9;
}

.summary-card__content {
    display: flex;
    align-items: center;
    gap: 16px;
}

.summary-card__icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.summary-card__stats {
    flex: 1;
}

.summary-card__stats h3 {
    font-size: 32px;
    font-weight: 800;
    margin-bottom: 4px;
    line-height: 1;
}

.summary-card__stats p {
    font-size: 13px;
    opacity: 0.9;
}

.summary-card__progress {
    margin-top: 16px;
    height: 6px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    overflow: hidden;
}

.summary-card__progress-bar {
    height: 100%;
    background: white;
    border-radius: 10px;
    transition: width 0.3s ease;
}

/* Reason Tags */
.reason-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 16px;
}

.reason-tag {
    background: var(--primary-100);
    color: var(--primary-700);
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 500;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    background: white;
    border-radius: 14px;
    border: 1px solid var(--gray-200);
}

.empty-state i {
    font-size: 48px;
    color: var(--primary-200);
    margin-bottom: 16px;
}

.empty-state h4 {
    font-size: 16px;
    font-weight: 700;
    color: var(--gray-700);
    margin-bottom: 6px;
}

.empty-state p {
    font-size: 13px;
    color: var(--gray-500);
}

/* Modal */
.modal-content {
    border-radius: 20px;
    border: none;
}

.modal-header {
    border-bottom: 1px solid var(--gray-200);
    padding: 20px;
}

.modal-title {
    font-weight: 700;
    color: var(--gray-800);
    display: flex;
    align-items: center;
    gap: 8px;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    border-top: 1px solid var(--gray-200);
    padding: 16px 20px;
}

/* Form */
.form-group {
    margin-bottom: 16px;
}

.form-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 6px;
}

.form-control, .form-select {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    font-size: 14px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    transition: var(--transition);
}

.form-control:focus, .form-select:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px rgba(42, 168, 116, 0.1);
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.room-card {
    animation: fadeIn 0.3s ease both;
}

.room-card:nth-child(1) { animation-delay: 0.05s; }
.room-card:nth-child(2) { animation-delay: 0.1s; }
.room-card:nth-child(3) { animation-delay: 0.15s; }
.room-card:nth-child(4) { animation-delay: 0.2s; }

/* Responsive */
@media (max-width: 360px) {
    .quick-actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="mobile-header">
    <div class="mobile-header__top">
        <div class="mobile-header__title">
            <div class="mobile-header__icon">
                <i class="fas fa-broom"></i>
            </div>
            <div class="mobile-header__text">
                <h1>Housekeeping</h1>
                <p><?php echo e(now()->format('l d F Y')); ?></p>
            </div>
        </div>
        <div class="mobile-header__actions">
            <a href="<?php echo e(route('housekeeping.index')); ?>" class="btn-mobile btn-mobile-outline">
                <i class="fas fa-desktop"></i>
            </a>
            <a href="<?php echo e(route('housekeeping.scan')); ?>" class="btn-mobile btn-mobile-primary">
                <i class="fas fa-qrcode"></i>
            </a>
        </div>
    </div>
    
    
    <div class="search-bar">
        <input type="text" class="search-input" id="searchRooms" placeholder="Rechercher une chambre...">
    </div>
</div>


<div class="stats-grid">
    <div class="stat-card dirty">
        <div class="stat-card__value"><?php echo e($stats['dirty'] ?? 0); ?></div>
        <div class="stat-card__label">À nettoyer</div>
    </div>
    <div class="stat-card cleaning">
        <div class="stat-card__value"><?php echo e($stats['cleaning'] ?? 0); ?></div>
        <div class="stat-card__label">En cours</div>
    </div>
    <div class="stat-card clean">
        <div class="stat-card__value"><?php echo e($stats['clean'] ?? 0); ?></div>
        <div class="stat-card__label">Nettoyées</div>
    </div>
    <div class="stat-card occupied">
        <div class="stat-card__value"><?php echo e($stats['occupied'] ?? 0); ?></div>
        <div class="stat-card__label">Occupées</div>
    </div>
</div>


<?php if(($todayDepartures ?? collect())->count() > 0): ?>
<div class="section">
    <div class="section__header">
        <div class="section__title">
            <i class="fas fa-sign-out-alt"></i>
            Départs aujourd'hui
        </div>
        <span class="section__badge"><?php echo e($todayDepartures->count()); ?></span>
    </div>
    <?php $__currentLoopData = $todayDepartures->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $departure): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="side-item">
        <div class="side-item__header">
            <div class="side-item__room">#<?php echo e($departure->room->number); ?></div>
            <div class="side-item__badge departure">
                <i class="fas fa-clock"></i> 12h00
            </div>
        </div>
        <div class="side-item__info">
            <span class="side-item__name"><?php echo e($departure->customer->name ?? 'Client'); ?></span>
            <span class="side-item__time">
                <i class="fas fa-bed"></i> <?php echo e($departure->room->type->name ?? 'Standard'); ?>

            </span>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>


<div class="section">
    <div class="section__header">
        <div class="section__title">
            <i class="fas fa-broom"></i>
            À nettoyer
        </div>
        <span class="section__badge"><?php echo e($dirtyRooms->count()); ?></span>
    </div>
    
    <?php if($dirtyRooms->count() > 0): ?>
        <?php $__currentLoopData = $dirtyRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="room-card dirty">
            <div class="room-card__content">
                <div class="room-card__header">
                    <div class="room-card__number">#<?php echo e($room->number); ?></div>
                    <div class="room-card__badge dirty">
                        <i class="fas fa-broom"></i>
                    </div>
                </div>
                <div class="room-card__type"><?php echo e($room->type->name ?? 'Chambre Standard'); ?></div>
                <div class="room-card__meta">
                    <i class="fas fa-user"></i>
                    <span><?php echo e($room->capacity); ?> pers.</span>
                    <?php if($room->floor): ?>
                    <i class="fas fa-layer-group"></i>
                    <span>Étage <?php echo e($room->floor); ?></span>
                    <?php endif; ?>
                </div>
                <div class="room-card__status dirty">
                    <i class="fas fa-exclamation-circle"></i>
                    À nettoyer
                </div>
                <div class="room-actions">
                    <form action="<?php echo e(route('housekeeping.start-cleaning', $room->id)); ?>" method="POST" style="flex: 1;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn-action btn-action-primary">
                            <i class="fas fa-play"></i>
                            Commencer
                        </button>
                    </form>
                    <button class="btn-action btn-action-outline" onclick="openMaintenanceModal('<?php echo e($room->number); ?>')">
                        <i class="fas fa-tools"></i>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-check-circle" style="color: var(--clean);"></i>
            <h4>Aucune chambre à nettoyer</h4>
            <p>Toutes les chambres sont propres</p>
        </div>
    <?php endif; ?>
</div>


<?php if($cleaningRooms->count() > 0): ?>
<div class="section">
    <div class="section__header">
        <div class="section__title">
            <i class="fas fa-spinner"></i>
            En cours
        </div>
        <span class="section__badge"><?php echo e($cleaningRooms->count()); ?></span>
    </div>
    
    <?php $__currentLoopData = $cleaningRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $startTime = $room->cleaning_started_at ?? now();
        $duration = $startTime->diffForHumans(now(), true);
    ?>
    <div class="room-card cleaning">
        <div class="room-card__content">
            <div class="room-card__header">
                <div class="room-card__number">#<?php echo e($room->number); ?></div>
                <div class="room-card__badge cleaning">
                    <i class="fas fa-spinner"></i>
                </div>
            </div>
            <div class="room-card__type"><?php echo e($room->type->name ?? 'Chambre Standard'); ?></div>
            <div class="room-card__meta">
                <i class="fas fa-clock"></i>
                <span class="timer-badge">
                    <i class="fas fa-hourglass-half"></i>
                    <?php echo e($duration); ?>

                </span>
            </div>
            <div class="room-card__status cleaning">
                <i class="fas fa-spinner fa-spin"></i>
                En nettoyage
            </div>
            <div class="room-actions">
                <form action="<?php echo e(route('housekeeping.mark-cleaned', $room->id)); ?>" method="POST" style="flex: 1;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-action btn-action-success">
                        <i class="fas fa-check"></i>
                        Terminer
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>


<?php if(($todayArrivals ?? collect())->count() > 0): ?>
<div class="section">
    <div class="section__header">
        <div class="section__title">
            <i class="fas fa-sign-in-alt"></i>
            Arrivées aujourd'hui
        </div>
        <span class="section__badge"><?php echo e($todayArrivals->count()); ?></span>
    </div>
    <?php $__currentLoopData = $todayArrivals->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $arrival): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="side-item">
        <div class="side-item__header">
            <div class="side-item__room">#<?php echo e($arrival->room->number); ?></div>
            <div class="side-item__badge arrival">
                <i class="fas fa-clock"></i> 14h00
            </div>
        </div>
        <div class="side-item__info">
            <span class="side-item__name"><?php echo e($arrival->customer->name ?? 'Client'); ?></span>
            <span class="side-item__time">
                <i class="fas fa-bed"></i> <?php echo e($arrival->room->type->name ?? 'Standard'); ?>

            </span>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>


<?php if(isset($stats['maintenance_by_reason']) && count($stats['maintenance_by_reason']) > 0): ?>
<div class="section">
    <div class="section__header">
        <div class="section__title">
            <i class="fas fa-chart-pie"></i>
            Raisons de maintenance
        </div>
    </div>
    <div class="reason-tags">
        <?php $__currentLoopData = $stats['maintenance_by_reason']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reason => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <span class="reason-tag">
            <?php echo e($reason); ?>: <?php echo e($count); ?>

        </span>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>


<div class="quick-actions-grid">
    <a href="<?php echo e(route('housekeeping.to-clean')); ?>" class="quick-action-item">
        <i class="fas fa-list"></i>
        <span>Liste</span>
        <small>complète</small>
    </a>
    <button class="quick-action-item" onclick="openMaintenanceModal()">
        <i class="fas fa-tools"></i>
        <span>Maintenance</span>
        <small>signaler</small>
    </button>
    <a href="<?php echo e(route('housekeeping.scan')); ?>" class="quick-action-item">
        <i class="fas fa-qrcode"></i>
        <span>Scanner</span>
        <small>QR code</small>
    </a>
    <a href="<?php echo e(route('housekeeping.reports')); ?>" class="quick-action-item">
        <i class="fas fa-chart-bar"></i>
        <span>Rapports</span>
        <small>stats</small>
    </a>
</div>


<div class="summary-card">
    <div class="summary-card__header">
        <i class="fas fa-calendar-day"></i>
        Résumé du jour
    </div>
    <div class="summary-card__content">
        <div class="summary-card__icon">
            <i class="fas fa-trophy"></i>
        </div>
        <div class="summary-card__stats">
            <h3><?php echo e($stats['cleaned_today'] ?? 0); ?></h3>
            <p>chambres nettoyées</p>
        </div>
    </div>
    <?php
        $totalRooms = ($stats['dirty'] ?? 0) + ($stats['cleaning'] ?? 0) + ($stats['clean'] ?? 0);
        $progress = $totalRooms > 0 ? round(($stats['clean'] ?? 0) / $totalRooms * 100) : 0;
    ?>
    <div class="summary-card__progress">
        <div class="summary-card__progress-bar" style="width: <?php echo e($progress); ?>%"></div>
    </div>
</div>


<div class="modal fade" id="maintenanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-tools" style="color: var(--primary-500);"></i>
                    Signaler une maintenance
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Numéro de chambre</label>
                    <input type="text" class="form-control" id="roomNumber" placeholder="Ex: 101, 102...">
                </div>
                <div class="form-group">
                    <label class="form-label">Raison</label>
                    <select class="form-select" id="maintenanceReason">
                        <option value="Électricité">⚡ Problème électrique</option>
                        <option value="Plomberie">💧 Fuite d'eau</option>
                        <option value="Climatisation">❄️ Climatisation</option>
                        <option value="Meuble">🪑 Meuble cassé</option>
                        <option value="Sécurité">🔒 Problème de sécurité</option>
                        <option value="Nettoyage profond">🧹 Nettoyage profond</option>
                        <option value="Autre">📌 Autre</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Durée estimée (heures)</label>
                    <input type="number" class="form-control" id="estimatedDuration" min="1" max="48" value="4">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn" style="background: var(--primary-500); color: white;" onclick="submitMaintenance()">
                    <i class="fas fa-exclamation-triangle"></i>
                    Signaler
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Variables globales
let currentRoomNumber = '';

// Ouvrir modal avec numéro de chambre pré-rempli
function openMaintenanceModal(roomNumber = '') {
    document.getElementById('roomNumber').value = roomNumber;
    new bootstrap.Modal(document.getElementById('maintenanceModal')).show();
}

// Soumission maintenance
function submitMaintenance() {
    const roomNumber = document.getElementById('roomNumber').value;
    const reason = document.getElementById('maintenanceReason').value;
    const duration = document.getElementById('estimatedDuration').value;
    
    if (!roomNumber) {
        alert('Veuillez saisir un numéro de chambre');
        return;
    }
    
    // Rechercher la chambre par numéro
    fetch(`/api/rooms/find-by-number/${roomNumber}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/housekeeping/room/${data.room.id}/mark-maintenance`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '<?php echo e(csrf_token()); ?>';
                form.appendChild(csrfToken);
                
                const reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'maintenance_reason';
                reasonInput.value = reason;
                form.appendChild(reasonInput);
                
                const durationInput = document.createElement('input');
                durationInput.type = 'hidden';
                durationInput.name = 'estimated_duration';
                durationInput.value = duration;
                form.appendChild(durationInput);
                
                document.body.appendChild(form);
                form.submit();
            } else {
                alert('Chambre non trouvée');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la recherche de la chambre');
        });
}

// Recherche en temps réel
document.getElementById('searchRoons')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    
    document.querySelectorAll('.room-card').forEach(card => {
        const roomNumber = card.querySelector('.room-card__number')?.textContent.toLowerCase() || '';
        const roomType = card.querySelector('.room-card__type')?.textContent.toLowerCase() || '';
        
        if (roomNumber.includes(searchTerm) || roomType.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Rafraîchissement automatique (optionnel)
let refreshInterval = setInterval(function() {
    if (!document.querySelector('.modal.show')) {
        location.reload();
    }
}, 300000); // 5 minutes

// Nettoyage
window.addEventListener('beforeunload', function() {
    clearInterval(refreshInterval);
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/housekeeping/mobile.blade.php ENDPATH**/ ?>