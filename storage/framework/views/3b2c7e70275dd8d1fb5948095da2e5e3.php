
<?php $__env->startSection('title', 'Inventaire des chambres'); ?>
<?php $__env->startSection('content'); ?>

<style>
/* ═══════════════════════════════════════════════════════════════
   DESIGN SYSTEM - MÊME STYLE QUE CHECK-IN
═══════════════════════════════════════════════════════════════════ */
:root {
    --primary-50: #ecfdf5;
    --primary-100: #d1fae5;
    --primary-400: #34d399;
    --primary-500: #10b981;
    --primary-600: #059669;
    --primary-700: #047857;
    --primary-800: #065f46;

    --amber-50: #fffbeb;
    --amber-100: #fef3c7;
    --amber-400: #fbbf24;
    --amber-500: #f59e0b;
    --amber-600: #d97706;

    --blue-50: #eff6ff;
    --blue-100: #dbeafe;
    --blue-500: #3b82f6;
    --blue-600: #2563eb;

    --red-50: #fee2e2;
    --red-500: #ef4444;
    --red-600: #dc2626;

    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;

    --white: #ffffff;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
}

* { box-sizing: border-box; }

.inventory-page {
    background: var(--gray-50);
    min-height: 100vh;
    padding: 24px 32px;
    font-family: 'Inter', system-ui, sans-serif;
}

/* Breadcrumb */
.breadcrumb-custom {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.813rem;
    color: var(--gray-400);
    margin-bottom: 24px;
    flex-wrap: wrap;
}

.breadcrumb-custom a {
    color: var(--gray-400);
    text-decoration: none;
    transition: color 0.2s;
}

.breadcrumb-custom a:hover {
    color: var(--primary-600);
}

.breadcrumb-custom .separator {
    color: var(--gray-300);
    font-size: 0.688rem;
}

.breadcrumb-custom .current {
    color: var(--gray-600);
    font-weight: 500;
}

/* En-tête */
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

.header-title h1 {
    font-size: 1.875rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
}

.header-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 10px rgba(5, 150, 105, 0.3);
}

.header-subtitle {
    color: var(--gray-500);
    font-size: 0.875rem;
    margin: 6px 0 0 60px;
}

.header-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

/* Boutons */
.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 500;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-primary-modern {
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    color: white;
    box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.3);
}

.btn-primary-modern:hover {
    background: linear-gradient(135deg, var(--primary-800), var(--primary-600));
    transform: translateY(-1px);
    box-shadow: 0 6px 8px -1px rgba(5, 150, 105, 0.4);
    color: white;
    text-decoration: none;
}

.btn-outline-modern {
    background: white;
    color: var(--gray-700);
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
}

.btn-outline-modern:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-900);
    transform: translateY(-1px);
    text-decoration: none;
}

.btn-success-modern {
    background: var(--primary-600);
    color: white;
}

.btn-success-modern:hover {
    background: var(--primary-700);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(5, 150, 105, 0.3);
}

.btn-warning-modern {
    background: var(--amber-500);
    color: white;
}

.btn-warning-modern:hover {
    background: var(--amber-600);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(245, 158, 11, 0.3);
}

.btn-sm-modern {
    padding: 6px 14px;
    font-size: 0.813rem;
    border-radius: 8px;
}

.btn-icon-modern {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--gray-200);
    background: white;
    color: var(--gray-600);
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-icon-modern:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-800);
    transform: translateY(-1px);
}

/* Cartes */
.card-modern {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.2s;
    height: 100%;
}

.card-modern:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--gray-300);
}

.card-header-modern {
    padding: 16px 20px;
    border-bottom: 1px solid var(--gray-100);
    background: white;
}

.card-header-modern h6 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-700);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-header-modern h6 i {
    color: var(--primary-500);
}

.card-header-success {
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    color: white;
}

.card-header-success h6 {
    color: white;
}

.card-header-success h6 i {
    color: rgba(255, 255, 255, 0.9);
}

.card-header-warning {
    background: linear-gradient(135deg, var(--amber-500), var(--amber-600));
    color: white;
}

.card-header-warning h6 {
    color: white;
}

.card-header-warning h6 i {
    color: rgba(255, 255, 255, 0.9);
}

.card-header-info {
    background: linear-gradient(135deg, var(--blue-500), var(--blue-600));
    color: white;
}

.card-header-info h6 {
    color: white;
}

.card-header-info h6 i {
    color: rgba(255, 255, 255, 0.9);
}

.card-header-dark {
    background: linear-gradient(135deg, var(--gray-800), var(--gray-900));
    color: white;
}

.card-header-dark h6 {
    color: white;
}

.card-header-dark h6 i {
    color: rgba(255, 255, 255, 0.9);
}

.card-body-modern {
    padding: 20px;
}

/* Statistiques */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 28px;
}

.stat-card-modern {
    background: white;
    border-radius: 20px;
    padding: 24px;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
    transition: transform 0.2s;
    position: relative;
    overflow: hidden;
}

.stat-card-modern:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stat-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--primary-500);
}

.stat-card-modern.primary::before { background: var(--primary-500); }
.stat-card-modern.success::before { background: var(--primary-500); }
.stat-card-modern.warning::before { background: var(--amber-500); }
.stat-card-modern.info::before { background: var(--blue-500); }

.stat-icon-wrapper {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
}

.stat-icon-wrapper.primary { background: var(--primary-100); color: var(--primary-600); }
.stat-icon-wrapper.success { background: var(--primary-100); color: var(--primary-600); }
.stat-icon-wrapper.warning { background: var(--amber-100); color: var(--amber-600); }
.stat-icon-wrapper.info { background: var(--blue-100); color: var(--blue-600); }

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--gray-800);
    line-height: 1.2;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--gray-500);
    letter-spacing: 0.5px;
}

/* Arrivées/Départs */
.arrivals-departures-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 28px;
}

.guest-item {
    display: flex;
    align-items: center;
    padding: 12px;
    border-bottom: 1px solid var(--gray-100);
    transition: background 0.2s;
}

.guest-item:last-child {
    border-bottom: none;
}

.guest-item:hover {
    background: var(--gray-50);
}

.guest-avatar-sm {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--primary-100);
    color: var(--primary-600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
    margin-right: 12px;
    flex-shrink: 0;
}

.guest-info {
    flex: 1;
}

.guest-name {
    font-weight: 600;
    color: var(--gray-800);
    font-size: 0.875rem;
    margin-bottom: 2px;
}

.guest-email {
    font-size: 0.688rem;
    color: var(--gray-500);
}

.guest-time {
    font-size: 0.688rem;
    color: var(--gray-400);
    display: flex;
    align-items: center;
    gap: 4px;
}

.room-badge-sm {
    background: var(--gray-100);
    color: var(--gray-700);
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 0.688rem;
    font-weight: 600;
}

/* Tableau */
.table-container {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 28px;
}

.table-header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    background: white;
}

.table-header h5 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-700);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.table-header h5 i {
    color: var(--primary-500);
}

.table-responsive {
    overflow-x: auto;
}

.table-modern {
    width: 100%;
    border-collapse: collapse;
}

.table-modern thead th {
    background: var(--gray-50);
    padding: 16px 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--gray-500);
    border-bottom: 1px solid var(--gray-200);
    text-align: left;
}

.table-modern tbody td {
    padding: 16px 12px;
    border-bottom: 1px solid var(--gray-100);
    color: var(--gray-700);
    font-size: 0.875rem;
    vertical-align: middle;
}

.table-modern tbody tr:hover {
    background: var(--gray-50);
}

.table-modern tbody tr:last-child td {
    border-bottom: none;
}

/* Badges */
.badge-modern {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    line-height: 1;
}

.badge-success {
    background: var(--primary-100);
    color: var(--primary-700);
    border: 1px solid var(--primary-200);
}

.badge-warning {
    background: var(--amber-100);
    color: var(--amber-700);
    border: 1px solid var(--amber-200);
}

.badge-danger {
    background: var(--red-50);
    color: var(--red-700);
    border: 1px solid #fecaca;
}

.badge-info {
    background: var(--blue-100);
    color: var(--blue-700);
    border: 1px solid var(--blue-200);
}

.badge-dark {
    background: var(--gray-800);
    color: white;
}

/* Progress bar */
.progress-modern {
    height: 8px;
    background: var(--gray-200);
    border-radius: 4px;
    overflow: hidden;
    width: 100%;
}

.progress-bar-modern {
    height: 100%;
    border-radius: 4px;
    transition: width 0.3s ease;
}

.progress-bar-success { background: var(--primary-500); }
.progress-bar-warning { background: var(--amber-500); }
.progress-bar-info { background: var(--blue-500); }

/* Room status cards */
.status-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.status-card {
    background: white;
    border-radius: 16px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
}

.status-card-header {
    padding: 12px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.status-card-header.success { background: var(--primary-500); color: white; }
.status-card-header.danger { background: var(--red-500); color: white; }
.status-card-header.info { background: var(--blue-500); color: white; }
.status-card-header.warning { background: var(--amber-500); color: white; }
.status-card-header.secondary { background: var(--gray-600); color: white; }

.status-card-body {
    padding: 16px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.room-chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 10px;
    background: var(--gray-100);
    color: var(--gray-700);
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
    border: 1px solid var(--gray-200);
}

.room-chip:hover {
    background: var(--gray-200);
    transform: translateY(-1px);
    text-decoration: none;
    color: var(--gray-900);
}

.room-chip i {
    font-size: 0.65rem;
    color: var(--gray-500);
}

/* Avatar */
.avatar-sm {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--primary-100);
    color: var(--primary-600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
}

/* Empty state */
.empty-state-small {
    text-align: center;
    padding: 32px 16px;
}

.empty-state-small i {
    font-size: 2rem;
    color: var(--gray-300);
    margin-bottom: 12px;
}

.empty-state-small h6 {
    font-size: 0.875rem;
    color: var(--gray-600);
    margin-bottom: 4px;
}

/* Responsive */
@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .arrivals-departures-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .inventory-page {
        padding: 16px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .table-modern thead th {
        padding: 12px 8px;
    }
    
    .table-modern tbody td {
        padding: 12px 8px;
    }
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

.fade-in {
    animation: fadeIn 0.3s ease-out forwards;
}

.stagger-1 { animation-delay: 0.05s; }
.stagger-2 { animation-delay: 0.1s; }
.stagger-3 { animation-delay: 0.15s; }
.stagger-4 { animation-delay: 0.2s; }
</style>

<div class="inventory-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-custom">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home fa-xs me-1"></i>Dashboard</a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Inventaire</span>
    </div>

    <!-- En-tête -->
    <div class="page-header">
        <div class="header-title">
            <span class="header-icon">
                <i class="fas fa-clipboard-list"></i>
            </span>
            <h1>Inventaire des chambres</h1>
        </div>
        <p class="header-subtitle">Statut et occupation des chambres en temps réel</p>
    </div>

    <!-- Actions -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div></div>
        <div class="header-actions">
            <a href="<?php echo e(route('availability.calendar')); ?>" class="btn-modern btn-outline-modern">
                <i class="fas fa-calendar-alt me-2"></i>
                Calendrier
            </a>
            <a href="<?php echo e(route('availability.dashboard')); ?>" class="btn-modern btn-outline-modern">
                <i class="fas fa-tachometer-alt me-2"></i>
                Dashboard
            </a>
            <button class="btn-modern btn-primary-modern" onclick="window.print()">
                <i class="fas fa-print me-2"></i>
                Imprimer
            </button>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card-modern primary fade-in stagger-1">
            <div class="stat-icon-wrapper primary">
                <i class="fas fa-bed"></i>
            </div>
            <div class="stat-number"><?php echo e($stats['total_rooms']); ?></div>
            <div class="stat-label">Chambres totales</div>
        </div>
        
        <div class="stat-card-modern success fade-in stagger-2">
            <div class="stat-icon-wrapper success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number"><?php echo e($stats['available_rooms']); ?></div>
            <div class="stat-label">Chambres disponibles</div>
        </div>
        
        <div class="stat-card-modern warning fade-in stagger-3">
            <div class="stat-icon-wrapper warning">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-number"><?php echo e($stats['occupied_rooms']); ?></div>
            <div class="stat-label">Chambres occupées</div>
        </div>
        
        <div class="stat-card-modern info fade-in stagger-4">
            <div class="stat-icon-wrapper info">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-number"><?php echo e(number_format($stats['occupancy_rate'], 1)); ?>%</div>
            <div class="stat-label">Taux d'occupation</div>
        </div>
    </div>

    <!-- Arrivées et départs du jour -->
    <div class="arrivals-departures-grid">
        <!-- Arrivées du jour -->
        <div class="card-modern fade-in">
            <div class="card-header-success card-header-modern">
                <h6>
                    <i class="fas fa-sign-in-alt"></i>
                    Arrivées du jour
                </h6>
                <span class="badge-modern" style="background: white; color: var(--primary-700);">
                    <?php echo e($todayArrivals->count()); ?>

                </span>
            </div>
            <div class="card-body-modern p-0">
                <?php if($todayArrivals->count() > 0): ?>
                    <div style="max-height: 300px; overflow-y: auto;">
                        <?php $__currentLoopData = $todayArrivals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $arrival): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="guest-item">
                            <div class="guest-avatar-sm">
                                <?php echo e(substr($arrival->customer->name, 0, 1)); ?>

                            </div>
                            <div class="guest-info">
                                <div class="guest-name"><?php echo e($arrival->customer->name); ?></div>
                                <div class="guest-email"><?php echo e($arrival->customer->email); ?></div>
                            </div>
                            <div class="text-end">
                                <div class="room-badge-sm mb-1">Ch. <?php echo e($arrival->room->number); ?></div>
                                <div class="guest-time">
                                    <i class="fas fa-clock"></i>
                                    <?php echo e($arrival->check_in->format('H:i')); ?>

                                </div>
                            </div>
                            <div class="ms-3">
                                <?php
                                    try {
                                        $checkinRoute = route('checkin.show', $arrival->id);
                                    } catch (\Exception $e) {
                                        $checkinRoute = "/checkin/{$arrival->id}";
                                    }
                                ?>
                                <a href="<?php echo e($checkinRoute); ?>" class="btn-icon-modern" title="Check-in">
                                    <i class="fas fa-door-open"></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state-small">
                        <i class="fas fa-calendar-check"></i>
                        <h6>Aucune arrivée prévue</h6>
                        <p class="text-muted small">Aucune arrivée aujourd'hui</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Départs du jour -->
        <div class="card-modern fade-in">
            <div class="card-header-warning card-header-modern">
                <h6>
                    <i class="fas fa-sign-out-alt"></i>
                    Départs du jour
                </h6>
                <span class="badge-modern" style="background: white; color: var(--amber-700);">
                    <?php echo e($todayDepartures->count()); ?>

                </span>
            </div>
            <div class="card-body-modern p-0">
                <?php if($todayDepartures->count() > 0): ?>
                    <div style="max-height: 300px; overflow-y: auto;">
                        <?php $__currentLoopData = $todayDepartures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $departure): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="guest-item">
                            <div class="guest-avatar-sm">
                                <?php echo e(substr($departure->customer->name, 0, 1)); ?>

                            </div>
                            <div class="guest-info">
                                <div class="guest-name"><?php echo e($departure->customer->name); ?></div>
                                <div class="guest-email"><?php echo e($departure->customer->email); ?></div>
                            </div>
                            <div class="text-end">
                                <div class="room-badge-sm mb-1">Ch. <?php echo e($departure->room->number); ?></div>
                                <div class="guest-time">
                                    <i class="fas fa-clock"></i>
                                    <?php echo e($departure->check_out->format('H:i')); ?>

                                </div>
                            </div>
                            <div class="ms-3">
                                <a href="<?php echo e(route('transaction.show', $departure->id)); ?>" class="btn-icon-modern" title="Facture">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state-small">
                        <i class="fas fa-calendar-times"></i>
                        <h6>Aucun départ prévu</h6>
                        <p class="text-muted small">Aucun départ aujourd'hui</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Inventaire par type de chambre -->
    <div class="table-container fade-in">
        <div class="table-header">
            <h5>
                <i class="fas fa-list-alt"></i>
                Inventaire par type de chambre
            </h5>
            <div>
                <span class="badge-modern badge-info">
                    <i class="fas fa-clock me-1"></i>
                    Mis à jour: <?php echo e(now()->format('H:i')); ?>

                </span>
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
                        
                        // Calcul des prix
                        $prices = $type->rooms->pluck('price')->filter()->unique()->sort();
                        $avgPrice = $type->rooms->avg('price');
                        $minPrice = $type->rooms->min('price');
                        $maxPrice = $type->rooms->max('price');
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--primary-100); color: var(--primary-600); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-bed"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: var(--gray-800);"><?php echo e($type->name); ?></div>
                                    <div style="font-size: 0.688rem; color: var(--gray-500);">
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
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress-modern">
                                    <div class="progress-bar-modern <?php echo e($progressClass); ?>" style="width: <?php echo e($percentage); ?>%"></div>
                                </div>
                                <span style="font-size: 0.75rem; font-weight: 600; color: var(--gray-700); min-width: 45px;"><?php echo e(number_format($percentage, 1)); ?>%</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="<?php echo e(route('availability.search', ['room_type_id' => $type->id])); ?>" 
                               class="btn-icon-modern"
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

    <!-- Chambres par statut -->
    <div class="table-container fade-in">
        <div class="card-header-info card-header-modern">
            <h6>
                <i class="fas fa-clipboard-check"></i>
                Chambres par statut
            </h6>
        </div>
        
        <div class="card-body-modern">
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
                                <i class="fas fa-tools"></i>
                            <?php elseif($statusId == 3): ?>
                                <i class="fas fa-broom"></i>
                            <?php endif; ?>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if($rooms->count() > 12): ?>
                            <span class="room-chip" style="background: var(--gray-200);">
                                +<?php echo e($rooms->count() - 12); ?> autres
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <!-- Timestamp de mise à jour -->
    <div class="text-end mt-3">
        <small class="text-muted">
            <i class="fas fa-sync-alt fa-xs me-1"></i>
            Dernière mise à jour: <?php echo e(now()->format('d/m/Y H:i:s')); ?>

        </small>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
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
    
    console.log('Inventaire mis à jour: <?php echo e(now()->format("H:i:s")); ?>');
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/availability/inventory.blade.php ENDPATH**/ ?>