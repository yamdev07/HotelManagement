

<?php $__env->startSection('title', 'Room Management'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
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

    /* Couleurs utilitaires */
    --success-500: #22C55E;
    --danger-500: #EF4444;
    --danger-600: #DC2626;
    --warning-500: #F59E0B;
    --warning-600: #D97706;
    --info-500: #3B82F6;
    --info-600: #2563EB;

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
    --shadow-sm: 0 1px 2px 0 rgba(42, 168, 116, 0.08);
    --shadow-md: 0 4px 6px -1px rgba(42, 168, 116, 0.12);
    --shadow-lg: 0 10px 15px -3px rgba(42, 168, 116, 0.15);
    
    /* Transitions */
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    
    /* Autres */
    --r: 14px;
}

* { 
    box-sizing: border-box; 
    margin:0; 
    padding:0;
}

body {
    background: var(--gray-50);
    color: var(--gray-900);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 14px;
    line-height: 1.6;
}

/* ══════════════════════════════════════
   HEADER
══════════════════════════════════════ */
.rm-header {
    background: white;
    border-bottom: 1px solid var(--gray-200);
    padding: 24px 32px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-sm);
}

.rm-header__inner {
    max-width: 1600px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.rm-header__title {
    display: flex;
    align-items: center;
    gap: 16px;
}

.rm-header__icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 10px rgba(42, 168, 116, 0.3);
}

.rm-header__title h1 {
    font-size: 24px;
    font-weight: 800;
    letter-spacing: -.5px;
    margin-bottom: 4px;
    color: var(--gray-800);
}

.rm-header__stats {
    font-size: 13px;
    color: var(--gray-500);
    margin-left: 64px;
}

.rm-header__stats span {
    margin-right: 16px;
    font-weight: 500;
}

.rm-header__stats strong {
    color: var(--primary-600);
    font-weight: 700;
}

/* ══════════════════════════════════════
   STATISTICS
══════════════════════════════════════ */
.stats-grid {
    max-width: 1600px;
    margin: 0 auto 28px;
    padding: 0 32px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
}

.stat-card.total::before { background: var(--primary-500); }
.stat-card.available::before { background: var(--success-500); }
.stat-card.occupied::before { background: var(--primary-300); }
.stat-card.maintenance::before { background: var(--warning-500); }

.stat-number {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gray-800);
    margin-bottom: 4px;
    font-family: 'IBM Plex Mono', monospace;
}

.stat-label {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--gray-500);
    letter-spacing: 0.5px;
}

.stat-footer {
    margin-top: 12px;
    font-size: 0.688rem;
    color: var(--gray-400);
    display: flex;
    align-items: center;
    gap: 4px;
}

.stat-footer i {
    color: var(--primary-500);
}

/* ══════════════════════════════════════
   ALERTS
══════════════════════════════════════ */
.alert {
    padding: 14px 18px;
    border-radius: 10px;
    border: 1px solid;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

.alert--success {
    background: rgba(42, 168, 116, 0.1);
    border-color: rgba(42, 168, 116, 0.3);
    color: var(--primary-700);
}

.alert--danger {
    background: rgba(239,68,68,.12);
    border-color: rgba(239,68,68,.3);
    color: var(--danger-500);
}

.alert i { font-size: 16px; }
.alert .btn-close {
    margin-left: auto;
    opacity: .5;
    cursor: pointer;
    background: none;
    border: none;
    color: currentColor;
}

/* ══════════════════════════════════════
   BUTTONS
══════════════════════════════════════ */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid;
    text-decoration: none;
    transition: var(--transition);
    cursor: pointer;
    white-space: nowrap;
}

.btn--primary {
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    border-color: transparent;
    color: white;
    box-shadow: 0 4px 6px -1px rgba(42, 168, 116, 0.3);
}

.btn--primary:hover {
    background: linear-gradient(135deg, var(--primary-800), var(--primary-600));
    transform: translateY(-1px);
    box-shadow: 0 6px 10px -2px rgba(42, 168, 116, 0.4);
    color: white;
}

.btn i { font-size: 14px; }

/* Action buttons */
.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: 2px solid var(--gray-200);
    text-decoration: none;
    transition: var(--transition);
    cursor: pointer;
    font-size: 14px;
    background: white;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-view {
    border-color: var(--primary-200);
    color: var(--primary-600);
}

.btn-view:hover {
    background: var(--primary-600);
    border-color: var(--primary-600);
    color: white;
}

.btn-edit {
    border-color: rgba(42, 168, 116, 0.3);
    color: var(--primary-500);
}

.btn-edit:hover {
    background: var(--primary-500);
    border-color: var(--primary-500);
    color: white;
}

.btn-delete {
    border-color: rgba(239,68,68,.3);
    color: var(--danger-500);
}

.btn-delete:hover {
    background: var(--danger-500);
    border-color: var(--danger-500);
    color: white;
}

/* ══════════════════════════════════════
   MAIN CONTAINER
══════════════════════════════════════ */
.rm-container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 0 32px 48px;
}

/* ══════════════════════════════════════
   ACTION BAR
══════════════════════════════════════ */
.action-bar {
    background: white;
    border-radius: 14px;
    padding: 16px 20px;
    margin-bottom: 24px;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.action-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.action-right {
    flex: 1;
    max-width: 400px;
}

.filter-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    background: var(--primary-100);
    color: var(--primary-700);
    border: 1px solid var(--primary-200);
}

.badge-count {
    background: rgba(255, 255, 255, 0.5);
    padding: 2px 6px;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
    background: white;
}

/* Search */
.search-container {
    position: relative;
    width: 100%;
}

.search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    font-size: 0.9rem;
    pointer-events: none;
    z-index: 2;
}

.search-input {
    width: 100%;
    padding: 12px 16px 12px 42px;
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    font-size: 0.875rem;
    transition: var(--transition);
    background: white;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px rgba(42, 168, 116, 0.1);
}

/* ══════════════════════════════════════
   CARD
══════════════════════════════════════ */
.card {
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: var(--r);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.card__head {
    padding: 18px 24px;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    background: var(--gray-50);
}

.card__title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 16px;
    font-weight: 700;
    color: var(--gray-700);
}

.card__title i { 
    font-size: 18px;
    color: var(--primary-500);
}

.card__badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    background: var(--primary-100);
    color: var(--primary-700);
    border: 1px solid var(--primary-200);
}

.card__body {
    padding: 0;
}

/* ══════════════════════════════════════
   TABLE
══════════════════════════════════════ */
.tbl {
    width: 100%;
    border-collapse: collapse;
}

.tbl thead {
    background: var(--gray-50);
}

.tbl th {
    padding: 14px 20px;
    font-size: 11px;
    font-weight: 700;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: .5px;
    text-align: left;
    border-bottom: 1px solid var(--gray-200);
}

.tbl td {
    padding: 18px 20px;
    font-size: 13px;
    border-bottom: 1px solid var(--gray-100);
    vertical-align: middle;
}

.tbl tbody tr:last-child td {
    border-bottom: none;
}

.tbl tbody tr:hover {
    background: var(--gray-50);
}

/* ══════════════════════════════════════
   TABLE CELLS
══════════════════════════════════════ */
.room-num {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 15px;
    font-weight: 600;
    background: var(--primary-100);
    padding: 4px 10px;
    border-radius: 6px;
    display: inline-block;
    color: var(--primary-700);
}

.room-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 2px;
}

.room-meta {
    font-size: 12px;
    color: var(--gray-500);
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 4px;
}

.room-meta i {
    font-size: 10px;
    color: var(--primary-500);
}

.room-type {
    font-size: 13px;
    font-weight: 500;
    color: var(--gray-700);
}

.room-type__base {
    font-size: 11px;
    color: var(--gray-500);
    margin-top: 3px;
}

.room-capacity {
    display: flex;
    align-items: center;
    gap: 6px;
}

.room-capacity i {
    color: var(--primary-500);
}

.room-price {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 15px;
    font-weight: 600;
    color: var(--primary-700);
}

.room-price__eur {
    font-size: 11px;
    color: var(--gray-500);
    font-family: 'Plus Jakarta Sans', sans-serif;
    margin-top: 2px;
}

.room-price__custom {
    font-size: 11px;
    color: var(--warning-500);
    margin-top: 3px;
    display: flex;
    align-items: center;
    gap: 3px;
}

/* ══════════════════════════════════════
   BADGES
══════════════════════════════════════ */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

.badge--success { 
    background: var(--primary-100); 
    color: var(--primary-700);
    border: 1px solid var(--primary-200);
}
.badge--warning { 
    background: rgba(245,158,11,.12); 
    color: var(--warning-500);
    border: 1px solid rgba(245,158,11,.3);
}
.badge--danger { 
    background: rgba(239,68,68,.12); 
    color: var(--danger-500);
    border: 1px solid rgba(239,68,68,.3);
}
.badge--blue { 
    background: rgba(59,130,246,.12); 
    color: var(--info-500);
    border: 1px solid rgba(59,130,246,.3);
}
.badge--purple { 
    background: rgba(139,92,246,.12); 
    color: #8b5cf6;
    border: 1px solid rgba(139,92,246,.3);
}
.badge--gray { 
    background: var(--gray-100); 
    color: var(--gray-600);
    border: 1px solid var(--gray-200);
}

/* ══════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════ */
.empty {
    padding: 64px 20px;
    text-align: center;
}

.empty i {
    font-size: 48px;
    color: var(--gray-400);
    opacity: .4;
    margin-bottom: 20px;
}

.empty h5 {
    font-size: 18px;
    font-weight: 700;
    color: var(--gray-600);
    margin-bottom: 8px;
}

.empty p {
    font-size: 14px;
    color: var(--gray-500);
    margin-bottom: 20px;
}

/* ══════════════════════════════════════
   PAGINATION
══════════════════════════════════════ */
.pagination-wrap {
    padding: 20px 24px;
    border-top: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.pagination-info {
    font-size: 13px;
    color: var(--gray-500);
}

.pagination {
    display: flex;
    gap: 6px;
    list-style: none;
}

.page-item {
    list-style: none;
}

.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    border: 1px solid var(--gray-200);
    background: white;
    color: var(--gray-600);
    font-size: 0.875rem;
    font-weight: 500;
    transition: var(--transition);
    text-decoration: none;
}

.page-link:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-800);
    transform: translateY(-2px);
}

.active .page-link {
    background: var(--primary-500);
    border-color: var(--primary-500);
    color: white;
}

/* ══════════════════════════════════════
   ANIMATIONS
══════════════════════════════════════ */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.tbl tbody tr {
    animation: fadeIn .3s ease both;
}

.tbl tbody tr:nth-child(1) { animation-delay: .02s; }
.tbl tbody tr:nth-child(2) { animation-delay: .04s; }
.tbl tbody tr:nth-child(3) { animation-delay: .06s; }
.tbl tbody tr:nth-child(4) { animation-delay: .08s; }
.tbl tbody tr:nth-child(5) { animation-delay: .10s; }

/* ══════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════ */
@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .rm-header { padding: 20px; }
    .rm-header__inner { flex-direction: column; align-items: flex-start; }
    .rm-container { padding: 0 20px 40px; }
    .stats-grid { 
        grid-template-columns: 1fr;
        padding: 0 20px;
    }
    .action-bar { flex-direction: column; align-items: stretch; }
    .action-right { max-width: 100%; }
    .card__head { padding: 16px 20px; flex-direction: column; align-items: flex-start; }
    .tbl { display: block; overflow-x: auto; }
    .tbl th,
    .tbl td { padding: 12px; font-size: 12px; }
    .room-num { font-size: 13px; }
    .room-name { font-size: 13px; }
    .room-price { font-size: 13px; }
    .pagination-wrap { flex-direction: column; gap: 12px; align-items: flex-start; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="rm-header">
    <div class="rm-header__inner">
        <div class="rm-header__title">
            <div class="rm-header__icon">
                <i class="fas fa-bed"></i>
            </div>
            <div>
                <h1>Room Management</h1>
                <div class="rm-header__stats">
                    <span><strong><?php echo e($rooms->total()); ?></strong> rooms total</span>
                    <?php if($rooms->total() > 0): ?>
                        <span>Showing <?php echo e($rooms->firstItem()); ?>-<?php echo e($rooms->lastItem()); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div>
            <a href="<?php echo e(route('room.create')); ?>" class="btn btn--primary">
                <i class="fas fa-plus-circle"></i>
                Add New Room
            </a>
        </div>
    </div>
</div>


<?php
    $totalRooms = $rooms->total();
    $availableRooms = $rooms->where('roomStatus.name', 'Available')->count();
    $occupiedRooms = $rooms->where('roomStatus.name', 'Occupied')->count();
    $maintenanceRooms = $rooms->where('roomStatus.name', 'Maintenance')->count();
?>

<div class="stats-grid">
    <div class="stat-card total">
        <div class="stat-number"><?php echo e($totalRooms); ?></div>
        <div class="stat-label">Total Rooms</div>
        <div class="stat-footer">
            <i class="fas fa-building"></i>
            All rooms in hotel
        </div>
    </div>
    
    <div class="stat-card available">
        <div class="stat-number"><?php echo e($availableRooms); ?></div>
        <div class="stat-label">Available</div>
        <div class="stat-footer">
            <i class="fas fa-check-circle"></i>
            Ready for check-in
        </div>
    </div>
    
    <div class="stat-card occupied">
        <div class="stat-number"><?php echo e($occupiedRooms); ?></div>
        <div class="stat-label">Occupied</div>
        <div class="stat-footer">
            <i class="fas fa-user"></i>
            Currently occupied
        </div>
    </div>
    
    <div class="stat-card maintenance">
        <div class="stat-number"><?php echo e($maintenanceRooms); ?></div>
        <div class="stat-label">Maintenance</div>
        <div class="stat-footer">
            <i class="fas fa-tools"></i>
            Under maintenance
        </div>
    </div>
</div>


<div class="rm-container">

    
    <?php if(session('success')): ?>
    <div class="alert alert--success">
        <i class="fas fa-check-circle"></i>
        <span><?php echo e(session('success')); ?></span>
        <button type="button" class="btn-close" onclick="this.parentElement.remove()">×</button>
    </div>
    <?php endif; ?>

    <?php if(session('failed')): ?>
    <div class="alert alert--danger">
        <i class="fas fa-exclamation-circle"></i>
        <span><?php echo e(session('failed')); ?></span>
        <button type="button" class="btn-close" onclick="this.parentElement.remove()">×</button>
    </div>
    <?php endif; ?>

    
    <div class="action-bar">
        <div class="action-left">
            <span class="filter-badge">
                <i class="fas fa-bed"></i>
                All Rooms
                <span class="badge-count"><?php echo e($rooms->total()); ?></span>
            </span>
        </div>
        
        <div class="action-right">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" 
                       class="search-input" 
                       id="searchInput"
                       placeholder="Search rooms by number, name or type..." 
                       autocomplete="off">
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card__head">
            <div class="card__title">
                <i class="fas fa-door-open"></i>
                Rooms List
            </div>
            <span class="card__badge">
                <i class="fas fa-list"></i>
                <?php echo e($rooms->total()); ?> entries
            </span>
        </div>
        <div class="card__body">
            <table class="tbl" id="roomsTable">
                <thead>
                    <tr>
                        <th>Room #</th>
                        <th>Room Name</th>
                        <th>Type</th>
                        <th>Capacity</th>
                        <th>Price (FCFA)</th>
                        <th>Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <span class="room-num"><?php echo e($room->number); ?></span>
                        </td>
                        <td>
                            <div>
                                <div class="room-name">
                                    <?php echo e($room->display_name ?? $room->getNameOrNumber()); ?>

                                </div>
                                <?php if($room->name && $room->name !== $room->display_name): ?>
                                <div class="room-meta">
                                    <i class="fas fa-tag"></i>
                                    <?php echo e($room->name); ?>

                                </div>
                                <?php endif; ?>
                                <?php if($room->view): ?>
                                <div class="room-meta">
                                    <i class="fas fa-mountain"></i>
                                    <?php echo e($room->view); ?>

                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div class="room-type"><?php echo e($room->type->name ?? 'Standard'); ?></div>
                                <?php if($room->type && $room->type->base_price): ?>
                                <div class="room-type__base">
                                    Base: <?php echo e(number_format($room->type->base_price, 0, ',', ' ')); ?> FCFA
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div class="room-capacity">
                                <i class="fas fa-users"></i>
                                <span><?php echo e($room->capacity); ?> person(s)</span>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div class="room-price"><?php echo e(number_format($room->price, 0, ',', ' ')); ?> FCFA</div>
                                <?php if($room->price > 0): ?>
                                <div class="room-price__eur">
                                    ≈ €<?php echo e(number_format($room->price / 655, 2, ',', ' ')); ?>

                                </div>
                                <?php if($room->type && $room->type->base_price && $room->price != $room->type->base_price): ?>
                                <div class="room-price__custom">
                                    <i class="fas fa-exclamation-circle"></i>
                                    Custom price
                                </div>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge--<?php echo e($room->roomStatus->color ?? 'gray'); ?>">
                                <i class="<?php echo e($room->status_icon ?? 'fa-door-closed'); ?>"></i>
                                <?php echo e($room->roomStatus->name ?? 'Unknown'); ?>

                            </span>
                        </td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <a href="<?php echo e(route('room.show', $room->id)); ?>" 
                                   class="btn-action btn-view" 
                                   title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="<?php echo e(route('room.edit', $room->id)); ?>" 
                                   class="btn-action btn-edit" 
                                   title="Edit Room">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <?php if(auth()->user()->role === 'Super' || auth()->user()->role === 'Admin'): ?>
                                <form method="POST" 
                                      action="<?php echo e(route('room.destroy', $room->id)); ?>"
                                      style="display:inline"
                                      onsubmit="return confirm('Delete room <?php echo e($room->number); ?>? This action cannot be undone.')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" 
                                            class="btn-action btn-delete"
                                            title="Delete Room">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty">
                                <i class="fas fa-door-closed"></i>
                                <h5>No Rooms Found</h5>
                                <p>You haven't added any rooms yet. Start by adding your first room.</p>
                                <a href="<?php echo e(route('room.create')); ?>" class="btn btn--primary">
                                    <i class="fas fa-plus-circle"></i>
                                    Add Your First Room
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            
            <?php if($rooms->hasPages()): ?>
            <div class="pagination-wrap">
                <div class="pagination-info">
                    Showing <?php echo e($rooms->firstItem()); ?> to <?php echo e($rooms->lastItem()); ?> of <?php echo e($rooms->total()); ?> entries
                </div>
                <div>
                    <?php echo e($rooms->links()); ?>

                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Auto-hide alerts after 5 seconds
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);

// Search functionality
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('roomsTable');
    const rows = table.querySelectorAll('tbody tr');
    
    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        rows.forEach(row => {
            const roomNumber = row.querySelector('.room-num')?.textContent.toLowerCase() || '';
            const roomName = row.querySelector('.room-name')?.textContent.toLowerCase() || '';
            const roomType = row.querySelector('.room-type')?.textContent.toLowerCase() || '';
            const status = row.querySelector('.badge')?.textContent.toLowerCase() || '';
            
            if (roomNumber.includes(searchTerm) || 
                roomName.includes(searchTerm) || 
                roomType.includes(searchTerm) || 
                status.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show empty message if all rows hidden
        const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
        if (visibleRows.length === 0 && rows.length > 0) {
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = `
                <td colspan="7">
                    <div class="empty" style="padding: 40px 20px;">
                        <i class="fas fa-search"></i>
                        <h5>No Results Found</h5>
                        <p>No rooms match your search criteria</p>
                    </div>
                </td>
            `;
            table.querySelector('tbody').appendChild(emptyRow);
        } else {
            const emptyRow = table.querySelector('tbody tr:last-child');
            if (emptyRow && emptyRow.querySelector('.empty h5')?.textContent === 'No Results Found') {
                emptyRow.remove();
            }
        }
    });
});

// Tooltips
document.addEventListener('DOMContentLoaded', () => {
    const tooltips = [].slice.call(document.querySelectorAll('[title]'));
    tooltips.map(el => new bootstrap.Tooltip(el));
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/room/index.blade.php ENDPATH**/ ?>