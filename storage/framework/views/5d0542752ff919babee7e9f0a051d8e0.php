

<?php $__env->startSection('title', 'Chambres en Maintenance'); ?>

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

    /* Couleurs utilitaires */
    --success-500: #22C55E;
    --danger-500: #EF4444;
    --warning-500: #F59E0B;
    --warning-600: #D97706;
    --info-500: #3B82F6;

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
}

body {
    background: var(--gray-50);
    font-family: 'Plus Jakarta Sans', sans-serif;
}

/* Page Header */
.page-header {
    background: white;
    border-radius: 20px;
    padding: 24px 28px;
    margin-bottom: 28px;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
}

.page-header h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--gray-800);
    margin-bottom: 8px;
}

.page-header p {
    color: var(--gray-500);
    font-size: 14px;
    margin: 0;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    border: 1px solid transparent;
    transition: var(--transition);
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    color: white;
    box-shadow: 0 4px 6px -1px rgba(42, 168, 116, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-800), var(--primary-600));
    transform: translateY(-2px);
    box-shadow: 0 6px 10px -2px rgba(42, 168, 116, 0.4);
    color: white;
}

.btn-outline {
    border: 2px solid var(--gray-200);
    background: white;
    color: var(--gray-700);
}

.btn-outline:hover {
    border-color: var(--primary-500);
    color: var(--primary-500);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.btn-warning {
    background: linear-gradient(135deg, #F59E0B, #D97706);
    color: white;
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245,158,11,0.3);
}

/* Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 28px;
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
    background: var(--primary-500);
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    color: var(--gray-800);
    margin-bottom: 4px;
}

.stat-label {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--gray-500);
    letter-spacing: 0.5px;
}

.stat-icon {
    color: var(--primary-200);
    opacity: 0.8;
}

/* Main Card */
.main-card {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.card-header {
    padding: 18px 24px;
    border-bottom: 1px solid var(--gray-200);
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.card-header h5 {
    margin: 0;
    font-weight: 700;
    color: white;
}

.card-header i {
    color: rgba(255,255,255,0.9);
}

/* Table */
.table {
    margin: 0;
}

.table thead th {
    background: var(--gray-50);
    padding: 16px 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--gray-500);
    border-bottom: 1px solid var(--gray-200);
}

.table tbody td {
    padding: 16px 20px;
    font-size: 14px;
    color: var(--gray-700);
    border-bottom: 1px solid var(--gray-100);
    vertical-align: middle;
}

.table tbody tr:hover {
    background: var(--gray-50);
}

.table tbody tr.table-warning {
    background: rgba(245,158,11,0.05);
}

/* Room Badge */
.room-badge {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 16px;
    box-shadow: 0 4px 8px rgba(42, 168, 116, 0.2);
}

/* Badges */
.badge {
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.3px;
}

.badge.bg-info {
    background: rgba(42, 168, 116, 0.1) !important;
    color: var(--primary-700);
    border: 1px solid rgba(42, 168, 116, 0.2);
}

.badge.bg-warning {
    background: rgba(245,158,11,0.1) !important;
    color: var(--warning-600);
    border: 1px solid rgba(245,158,11,0.2);
}

/* Avatars */
.avatar-sm {
    width: 32px;
    height: 32px;
}

.avatar-lg {
    width: 50px;
    height: 50px;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    border-radius: 50%;
}

.avatar-title.bg-light {
    background: var(--primary-100) !important;
    color: var(--primary-700);
}

.avatar-title.bg-success {
    background: var(--success-500) !important;
    color: white;
}

/* Action Buttons */
.btn-group .btn {
    width: 36px;
    height: 36px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px !important;
    border: 2px solid var(--gray-200);
    background: white;
    color: var(--gray-600);
    transition: var(--transition);
    margin: 0 2px;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-group .btn-outline-primary {
    border-color: rgba(42, 168, 116, 0.3);
    color: var(--primary-600);
}

.btn-group .btn-outline-primary:hover {
    background: var(--primary-600);
    border-color: var(--primary-600);
    color: white;
}

.btn-group .btn-outline-warning {
    border-color: rgba(245,158,11,0.3);
    color: var(--warning-600);
}

.btn-group .btn-outline-warning:hover {
    background: var(--warning-600);
    border-color: var(--warning-600);
    color: white;
}

.btn-group .btn-success {
    background: var(--success-500);
    border-color: var(--success-500);
    color: white;
}

.btn-group .btn-success:hover {
    background: #16a34a;
    border-color: #16a34a;
}

/* Empty State */
.empty-state {
    padding: 60px 20px;
    text-align: center;
}

.empty-state i {
    font-size: 64px;
    color: var(--primary-200);
    margin-bottom: 20px;
}

.empty-state h4 {
    font-size: 20px;
    font-weight: 700;
    color: var(--gray-700);
    margin-bottom: 8px;
}

.empty-state p {
    color: var(--gray-500);
    margin-bottom: 24px;
}

/* Secondary Cards */
.secondary-card {
    background: white;
    border-radius: 16px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    height: 100%;
}

.secondary-card .card-header {
    padding: 16px 20px;
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
}

.secondary-card .card-header i {
    color: var(--primary-500);
}

.secondary-card .card-header strong {
    color: var(--gray-700);
    font-size: 14px;
}

/* Progress Bar */
.progress {
    height: 8px;
    border-radius: 10px;
    background: var(--gray-100);
}

.progress-bar {
    background: var(--primary-500);
    border-radius: 10px;
}

/* List Group */
.list-group-item {
    border: none;
    border-bottom: 1px solid var(--gray-100);
    padding: 16px 20px;
    transition: var(--transition);
}

.list-group-item:last-child {
    border-bottom: none;
}

.list-group-item:hover {
    background: var(--gray-50);
}

/* Responsive */
@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .page-header {
        padding: 20px;
    }
    
    .table-responsive {
        border-radius: 12px;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid px-4">
    <!-- En-tête -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h2 fw-bold">
                <i class="fas fa-tools me-3" style="color: var(--primary-500);"></i>
                Chambres en Maintenance
            </h1>
            <p class="text-muted mb-0">Gestion des chambres en réparation et maintenance</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('housekeeping.index')); ?>" class="btn btn-outline">
                <i class="fas fa-arrow-left me-2"></i>
                Retour
            </a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMaintenanceModal">
                <i class="fas fa-plus-circle me-2"></i>
                Nouvelle Maintenance
            </button>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Maintenance</div>
                    <div class="stat-number"><?php echo e($stats['total_maintenance'] ?? 0); ?></div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-tools fa-2x"></i>
                </div>
            </div>
            <div class="mt-2">
                <small class="text-muted">Chambres en maintenance actuellement</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Plus ancienne</div>
                    <div class="stat-number" style="font-size: 20px;">
                        <?php if(isset($stats['longest_maintenance']) && $stats['longest_maintenance']): ?>
                            <?php echo e(\Carbon\Carbon::parse($stats['longest_maintenance'])->diffForHumans(['parts' => 1])); ?>

                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-clock fa-2x"></i>
                </div>
            </div>
            <div class="mt-2">
                <small class="text-muted">Durée de la plus ancienne maintenance</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">En cours</div>
                    <div class="stat-number"><?php echo e($stats['in_progress'] ?? 0); ?></div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-spinner fa-2x"></i>
                </div>
            </div>
            <div class="mt-2">
                <small class="text-muted">Maintenances en cours</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Planifiées</div>
                    <div class="stat-number"><?php echo e($stats['scheduled'] ?? 0); ?></div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt fa-2x"></i>
                </div>
            </div>
            <div class="mt-2">
                <small class="text-muted">Maintenances planifiées</small>
            </div>
        </div>
    </div>

    <!-- Statistiques par raison -->
    <?php if(isset($stats['maintenance_by_reason']) && count($stats['maintenance_by_reason']) > 0): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="secondary-card">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-2"></i>
                    <strong>Répartition par raison de maintenance</strong>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <?php $__currentLoopData = $stats['maintenance_by_reason']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reason => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="badge" style="background: var(--primary-100); color: var(--primary-700); padding: 8px 16px;">
                            <i class="fas fa-tag me-1"></i>
                            <?php echo e($reason); ?>: <strong><?php echo e($count); ?></strong>
                        </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Liste des chambres en maintenance -->
    <div class="row">
        <div class="col-12">
            <div class="main-card">
                <div class="card-header">
                    <div>
                        <i class="fas fa-tools me-2"></i>
                        <strong>Chambres en maintenance (<?php echo e($maintenanceRooms->count()); ?>)</strong>
                    </div>
                    <div class="text-white-50">
                        <small>Mis à jour: <?php echo e(now()->format('H:i')); ?></small>
                    </div>
                </div>

                <?php if($maintenanceRooms->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Chambre</th>
                                    <th>Type</th>
                                    <th>Raison</th>
                                    <th>Début</th>
                                    <th>Durée</th>
                                    <th>Demandé par</th>
                                    <th style="text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $maintenanceRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $startDate = \Carbon\Carbon::parse($room->maintenance_started_at);
                                    $duration = $startDate->diffForHumans(now(), true);
                                    $isLongTerm = $startDate->diffInDays(now()) > 3;
                                ?>
                                <tr class="<?php echo e($isLongTerm ? 'table-warning' : ''); ?>">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="room-badge me-3">
                                                <?php echo e($room->number); ?>

                                            </div>
                                            <div>
                                                <div class="fw-bold"><?php echo e($room->type->name ?? 'Standard'); ?></div>
                                                <small class="text-muted">Étage: <?php echo e($room->floor ?? 'N/A'); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: var(--primary-100); color: var(--primary-700);">
                                            <?php echo e($room->type->name ?? 'Standard'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" 
                                             data-bs-toggle="tooltip" 
                                             title="<?php echo e($room->maintenance_reason); ?>">
                                            <?php echo e($room->maintenance_reason); ?>

                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?php echo e($startDate->format('d/m/Y')); ?></div>
                                        <small class="text-muted"><?php echo e($startDate->format('H:i')); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo e($isLongTerm ? 'warning' : 'info'); ?>">
                                            <i class="fas fa-clock me-1"></i>
                                            <?php echo e($duration); ?>

                                        </span>
                                        <?php if($room->estimated_maintenance_duration): ?>
                                            <small class="d-block text-muted mt-1">
                                                Estimé: <?php echo e($room->estimated_maintenance_duration); ?>h
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                            $requestedBy = \App\Models\User::find($room->maintenance_requested_by);
                                        ?>
                                        <?php if($requestedBy): ?>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-title bg-light">
                                                        <?php echo e(substr($requestedBy->name, 0, 1)); ?>

                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-bold small"><?php echo e($requestedBy->name); ?></div>
                                                    <small class="text-muted"><?php echo e($requestedBy->role); ?></small>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">Inconnu</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-outline-primary"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailsModal<?php echo e($room->id); ?>"
                                                    title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="<?php echo e(route('housekeeping.maintenance-form', $room->id)); ?>"
                                               class="btn btn-outline-warning"
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('housekeeping.end-maintenance', $room->id)); ?>" 
                                                  method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" 
                                                        class="btn btn-success"
                                                        title="Terminer"
                                                        onclick="return confirm('Terminer la maintenance de la chambre <?php echo e($room->number); ?> ?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-check-circle"></i>
                        <h4>Aucune chambre en maintenance</h4>
                        <p>Toutes les chambres sont opérationnelles</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMaintenanceModal">
                            <i class="fas fa-plus-circle me-2"></i>
                            Ajouter une maintenance
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Résumé et analyses -->
    <?php if($maintenanceRooms->count() > 0): ?>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="secondary-card">
                <div class="card-header">
                    <i class="fas fa-exclamation-triangle me-2" style="color: var(--warning-500);"></i>
                    <strong>Maintenance longue durée (> 3 jours)</strong>
                </div>
                <div class="card-body p-0">
                    <?php
                        $longTerm = $maintenanceRooms->filter(function($room) {
                            return \Carbon\Carbon::parse($room->maintenance_started_at)->diffInDays(now()) > 3;
                        });
                    ?>
                    <?php if($longTerm->count() > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $longTerm; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 fw-bold">
                                            <span class="room-badge me-2" style="width: 32px; height: 32px; font-size: 14px;">
                                                <?php echo e($room->number); ?>

                                            </span>
                                            <?php echo e($room->type->name ?? 'Standard'); ?>

                                        </h6>
                                        <small class="text-muted"><?php echo e($room->maintenance_reason); ?></small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge" style="background: rgba(239,68,68,0.1); color: var(--danger-500);">
                                            <i class="fas fa-clock me-1"></i>
                                            <?php echo e(\Carbon\Carbon::parse($room->maintenance_started_at)->diffForHumans(now(), true)); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x" style="color: var(--primary-200); margin-bottom: 12px;"></i>
                            <p class="text-muted mb-0">Aucune maintenance longue durée</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="secondary-card">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-2"></i>
                    <strong>Répartition par type de chambre</strong>
                </div>
                <div class="card-body">
                    <?php
                        $byType = $maintenanceRooms->groupBy(function($room) {
                            return $room->type->name ?? 'Inconnu';
                        })->map->count();
                    ?>
                    <?php if($byType->count() > 0): ?>
                        <?php $__currentLoopData = $byType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold" style="color: var(--gray-700);"><?php echo e($type); ?></span>
                                <span class="badge" style="background: var(--primary-100); color: var(--primary-700);">
                                    <?php echo e($count); ?> chambre(s)
                                </span>
                            </div>
                            <div class="progress">
                                <?php
                                    $percentage = ($count / $maintenanceRooms->count()) * 100;
                                ?>
                                <div class="progress-bar" style="width: <?php echo e($percentage); ?>%;"></div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-chart-pie fa-3x" style="color: var(--primary-200); margin-bottom: 12px;"></i>
                            <p class="text-muted mb-0">Aucune donnée disponible</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Modal Ajouter Maintenance -->
<div class="modal fade" id="addMaintenanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header" style="border-bottom: 1px solid var(--gray-200);">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-plus-circle me-2" style="color: var(--primary-500);"></i>
                    Ajouter une maintenance
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="addMaintenanceForm">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="room_select" class="form-label fw-semibold">Chambre *</label>
                        <select class="form-select" id="room_select" name="room_id" required>
                            <option value="">Sélectionner une chambre</option>
                            <?php
                                $availableRooms = \App\Models\Room::where('room_status_id', '!=', \App\Models\Room::STATUS_MAINTENANCE)
                                    ->orderBy('number')
                                    ->get();
                            ?>
                            <?php $__currentLoopData = $availableRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($room->id); ?>">
                                    Chambre <?php echo e($room->number); ?> - <?php echo e($room->type->name ?? 'Standard'); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="maintenance_reason" class="form-label fw-semibold">Raison de la maintenance *</label>
                        <select class="form-select" id="maintenance_reason" name="maintenance_reason" required>
                            <option value="">Sélectionner une raison</option>
                            <option value="Électricité">Problème électrique</option>
                            <option value="Plomberie">Fuite d'eau / Plomberie</option>
                            <option value="Climatisation">Climatisation défectueuse</option>
                            <option value="Meuble">Meuble cassé / Réparation</option>
                            <option value="Sécurité">Problème de sécurité (serrure, fenêtre)</option>
                            <option value="Peinture">Peinture / Rénovation</option>
                            <option value="Sol">Sol / Tapis à remplacer</option>
                            <option value="Salle de bain">Salle de bain (sanitaires)</option>
                            <option value="Nettoyage profond">Nettoyage profond nécessaire</option>
                            <option value="Autre">Autre raison</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="estimated_duration" class="form-label fw-semibold">Durée estimée (heures) *</label>
                        <input type="number" class="form-control" id="estimated_duration" 
                               name="estimated_duration" min="1" max="168" value="4" required>
                        <small class="text-muted">Entre 1 et 168 heures (1 semaine)</small>
                    </div>
                    <div class="mb-3">
                        <label for="additional_notes" class="form-label fw-semibold">Notes supplémentaires</label>
                        <textarea class="form-control" id="additional_notes" name="additional_notes" 
                                  rows="3" placeholder="Détails supplémentaires..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--gray-200);">
                    <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-tools me-2"></i>
                        Ajouter la maintenance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals pour détails -->
<?php $__currentLoopData = $maintenanceRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="detailsModal<?php echo e($room->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header" style="border-bottom: 1px solid var(--gray-200);">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-info-circle me-2" style="color: var(--primary-500);"></i>
                    Détails maintenance - Chambre <?php echo e($room->number); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small mb-1">Chambre</label>
                            <div class="d-flex align-items-center">
                                <div class="room-badge me-3" style="width: 48px; height: 48px;">
                                    <?php echo e($room->number); ?>

                                </div>
                                <div>
                                    <div class="fw-bold fs-5"><?php echo e($room->type->name ?? 'Standard'); ?></div>
                                    <small class="text-muted">Étage: <?php echo e($room->floor ?? 'N/A'); ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small mb-1">Capacité</label>
                            <div class="fw-bold">
                                <i class="fas fa-users me-2" style="color: var(--primary-500);"></i>
                                <?php echo e($room->capacity); ?> personnes
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small mb-1">Statut</label>
                            <div>
                                <span class="badge" style="background: var(--primary-100); color: var(--primary-700); padding: 8px 16px;">
                                    <i class="fas fa-tools me-1"></i>
                                    EN MAINTENANCE
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small mb-1">Début maintenance</label>
                            <div class="fw-bold">
                                <i class="fas fa-calendar me-2" style="color: var(--primary-500);"></i>
                                <?php echo e(\Carbon\Carbon::parse($room->maintenance_started_at)->format('d/m/Y H:i')); ?>

                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small mb-1">Durée</label>
                            <div class="fw-bold">
                                <i class="fas fa-clock me-2" style="color: var(--primary-500);"></i>
                                <?php echo e(\Carbon\Carbon::parse($room->maintenance_started_at)->diffForHumans(now(), true)); ?>

                                <?php if($room->estimated_maintenance_duration): ?>
                                    <small class="d-block text-muted mt-1">
                                        Estimé: <?php echo e($room->estimated_maintenance_duration); ?> heures
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted small mb-1">Raison de la maintenance</label>
                    <div class="alert" style="background: var(--primary-50); border: 1px solid var(--primary-100); color: var(--primary-700);">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo e($room->maintenance_reason); ?>

                    </div>
                </div>
                
                <?php if($room->additional_notes): ?>
                <div class="mb-3">
                    <label class="text-muted small mb-1">Notes supplémentaires</label>
                    <div class="card bg-light" style="border: none;">
                        <div class="card-body">
                            <?php echo e($room->additional_notes); ?>

                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Demandé par</label>
                        <?php
                            $requestedBy = \App\Models\User::find($room->maintenance_requested_by);
                        ?>
                        <?php if($requestedBy): ?>
                        <div class="d-flex align-items-center">
                            <div class="avatar-lg me-3">
                                <div class="avatar-title bg-light">
                                    <?php echo e(substr($requestedBy->name, 0, 1)); ?>

                                </div>
                            </div>
                            <div>
                                <div class="fw-bold"><?php echo e($requestedBy->name); ?></div>
                                <div class="text-muted small"><?php echo e($requestedBy->email); ?></div>
                                <span class="badge" style="background: var(--primary-100); color: var(--primary-700);">
                                    <?php echo e($requestedBy->role); ?>

                                </span>
                            </div>
                        </div>
                        <?php else: ?>
                        <span class="text-muted">Inconnu</span>
                        <?php endif; ?>
                    </div>
                    <?php if($room->maintenance_resolved_by): ?>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Résolu par</label>
                        <?php
                            $resolvedBy = \App\Models\User::find($room->maintenance_resolved_by);
                        ?>
                        <?php if($resolvedBy): ?>
                        <div class="d-flex align-items-center">
                            <div class="avatar-lg me-3">
                                <div class="avatar-title" style="background: var(--success-500); color: white;">
                                    <?php echo e(substr($resolvedBy->name, 0, 1)); ?>

                                </div>
                            </div>
                            <div>
                                <div class="fw-bold"><?php echo e($resolvedBy->name); ?></div>
                                <div class="text-muted small"><?php echo e($resolvedBy->email); ?></div>
                                <span class="badge" style="background: var(--success-500); color: white;">
                                    <?php echo e($resolvedBy->role); ?>

                                </span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--gray-200);">
                <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Fermer</button>
                <a href="<?php echo e(route('housekeeping.maintenance-form', $room->id)); ?>" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>
                    Modifier
                </a>
                <form action="<?php echo e(route('housekeeping.end-maintenance', $room->id)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success"
                            onclick="return confirm('Terminer la maintenance de la chambre <?php echo e($room->number); ?> ?')">
                        <i class="fas fa-check me-2"></i>
                        Terminer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Gestion du formulaire d'ajout de maintenance
    const addMaintenanceForm = document.getElementById('addMaintenanceForm');
    if (addMaintenanceForm) {
        addMaintenanceForm.addEventListener('submit', function(e) {
            const roomId = document.getElementById('room_select').value;
            const reason = document.getElementById('maintenance_reason').value;
            const duration = document.getElementById('estimated_duration').value;
            
            if (!roomId || !reason || !duration) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires');
                return false;
            }
            
            if (duration < 1 || duration > 168) {
                e.preventDefault();
                alert('La durée estimée doit être entre 1 et 168 heures');
                return false;
            }
            
            // Construire l'URL correcte
            const action = "<?php echo e(route('housekeeping.mark-maintenance', ':roomId')); ?>".replace(':roomId', roomId);
            addMaintenanceForm.setAttribute('action', action);
            
            // Confirmation
            if (!confirm('Êtes-vous sûr de vouloir mettre cette chambre en maintenance ?')) {
                e.preventDefault();
                return false;
            }
        });
    }
    
    // Auto-select "Autre" si on entre du texte
    const maintenanceReasonSelect = document.getElementById('maintenance_reason');
    const additionalNotes = document.getElementById('additional_notes');
    
    if (maintenanceReasonSelect && additionalNotes) {
        additionalNotes.addEventListener('input', function() {
            if (this.value.trim() !== '' && maintenanceReasonSelect.value === '') {
                maintenanceReasonSelect.value = 'Autre';
            }
        });
    }
    
    // Rafraîchissement automatique toutes les 2 minutes
    setTimeout(function() {
        window.location.reload();
    }, 120000);
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/housekeeping/maintenance.blade.php ENDPATH**/ ?>