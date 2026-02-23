

<?php $__env->startSection('title', 'Dashboard Caissier'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
    --primary: #3b82f6;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --info: #06b6d4;
    --dark: #1e293b;
    --light: #f8fafc;
    --border: #e2e8f0;
    --shadow: rgba(0,0,0,0.05);
}

body {
    font-family: 'Inter', sans-serif;
    background: var(--light);
}

/* Header */
.cashier-header {
    background: white;
    border-bottom: 1px solid var(--border);
    padding: 1.5rem 0;
    margin-bottom: 1.5rem;
}

.cashier-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--dark);
    margin: 0;
}

.user-info-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #64748b;
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.role-admin {
    background: rgba(16,185,129,0.1);
    color: #059669;
}

.role-receptionist {
    background: rgba(59,130,246,0.1);
    color: #2563eb;
}

/* Permission alerts */
.permission-alert {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border: 1px solid #fbbf24;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
}

.permission-alert-icon {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #f59e0b;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.permission-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.75rem;
}

.permission-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
}

.permission-badge i {
    font-size: 0.75rem;
}

/* Active session card */
.active-session {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    border: 2px solid #10b981;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}

.active-session::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(16,185,129,0.1) 0%, transparent 70%);
    border-radius: 50%;
    transform: translate(50%, -50%);
}

.session-icon {
    width: 50px;
    height: 50px;
    background: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: var(--success);
    flex-shrink: 0;
    box-shadow: 0 4px 6px rgba(16,185,129,0.1);
}

.session-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 1rem;
    background: white;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

/* No session card */
.no-session {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border: 2px dashed #f59e0b;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

/* Stats cards */
.stat-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.25rem;
    transition: all 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px var(--shadow);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.stat-label {
    font-size: 0.8125rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: var(--dark);
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-subtitle {
    font-size: 0.75rem;
    color: #94a3b8;
}

/* Tabs */
.nav-tabs {
    border: none;
    background: white;
    border-radius: 12px;
    padding: 0.5rem;
    box-shadow: 0 1px 3px var(--shadow);
}

.nav-tabs .nav-link {
    border: none;
    border-radius: 8px;
    color: #64748b;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.75rem 1.25rem;
    transition: all 0.2s;
}

.nav-tabs .nav-link:hover {
    background: var(--light);
    color: var(--dark);
}

.nav-tabs .nav-link.active {
    background: var(--primary);
    color: white;
}

.nav-tabs .badge {
    margin-left: 0.5rem;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.nav-tabs .nav-link.active .badge {
    background: rgba(255,255,255,0.2);
}

/* Table */
.table-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
}

.table {
    margin: 0;
}

.table thead {
    background: var(--light);
}

.table thead th {
    border: none;
    padding: 1rem 1.25rem;
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table tbody td {
    border-color: var(--border);
    padding: 1rem 1.25rem;
    vertical-align: middle;
}

.table tbody tr:hover {
    background: var(--light);
}

/* Badges */
.badge {
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.75rem;
}

.badge-success-soft {
    background: rgba(16,185,129,0.1);
    color: #059669;
}

.badge-warning-soft {
    background: rgba(245,158,11,0.1);
    color: #d97706;
}

.badge-danger-soft {
    background: rgba(239,68,68,0.1);
    color: #dc2626;
}

.badge-info-soft {
    background: rgba(6,182,212,0.1);
    color: #0891b2;
}

.badge-dark-soft {
    background: rgba(30,41,59,0.1);
    color: #1e293b;
}

/* Buttons */
.btn {
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    transition: all 0.2s;
}

.btn-icon {
    width: 36px;
    height: 36px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-primary {
    background: var(--primary);
    border-color: var(--primary);
}

.btn-primary:hover {
    background: #2563eb;
    border-color: #2563eb;
    transform: translateY(-2px);
}

/* Empty states */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: var(--light);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #cbd5e1;
    margin-bottom: 1.5rem;
}

/* User avatar */
.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
}

/* Filters */
.filters-row {
    background: white;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1rem;
    border: 1px solid var(--border);
}

/* Modal */
.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.modal-header {
    border-bottom: 1px solid var(--border);
    padding: 1.5rem;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    border-top: 1px solid var(--border);
    padding: 1.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .stat-value {
        font-size: 1.5rem;
    }
    
    .cashier-title {
        font-size: 1.5rem;
    }
    
    .stat-card {
        margin-bottom: 1rem;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    <!-- Header -->
    <div class="cashier-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="cashier-title">
                    <i class="fas fa-cash-register me-2" style="color:var(--primary)"></i>
                    Dashboard Caissier
                </h1>
                <div class="user-info-badge mt-2">
                    Bonjour, <strong class="mx-1"><?php echo e(auth()->user()->name); ?></strong>
                    <span class="role-badge <?php echo e($isAdmin ? 'role-admin' : 'role-receptionist'); ?>">
                        <i class="fas <?php echo e($isAdmin ? 'fa-crown' : 'fa-user'); ?>"></i>
                        <?php echo e(auth()->user()->role); ?>

                    </span>
                </div>
            </div>
            
            <?php if($isAdmin): ?>
            <div>
                <a href="<?php echo e(route('cashier.sessions.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nouvelle session
                </a>
            </div>
            <?php endif; ?>
        </div>
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('dashboard.index')); ?>">
                        <i class="fas fa-home"></i> Accueil
                    </a>
                </li>
                <li class="breadcrumb-item active">Caissier</li>
            </ol>
        </nav>
    </div>

    <!-- Receptionist permission notice -->
    <?php if(auth()->user()->role == 'Receptionist'): ?>
    <div class="permission-alert">
        <div class="d-flex align-items-start gap-3">
            <div class="permission-alert-icon">
                <i class="fas fa-eye"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="fw-bold mb-1">Mode Lecture Seule</h6>
                <p class="mb-2 small">Vous pouvez consulter les données mais seuls les administrateurs peuvent effectuer des modifications.</p>
                <div class="permission-badges">
                    <span class="permission-badge">
                        <i class="fas fa-check text-success"></i> Visualisation
                    </span>
                    <span class="permission-badge">
                        <i class="fas fa-check text-success"></i> Filtrage
                    </span>
                    <span class="permission-badge">
                        <i class="fas fa-times text-danger"></i> Création
                    </span>
                    <span class="permission-badge">
                        <i class="fas fa-times text-danger"></i> Modification
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Active session -->
    <?php if($activeSession): ?>
    <div class="active-session">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="session-icon pulse">
                    <i class="fas fa-play-circle"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-2">
                        Session Active #<?php echo e($activeSession->id); ?>

                        <?php if($activeSession->user_id != auth()->id()): ?>
                        <span class="badge badge-dark-soft"><?php echo e($activeSession->user->name); ?></span>
                        <?php endif; ?>
                    </h5>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="session-badge">
                            <i class="fas fa-user"></i>
                            <span><?php echo e($activeSession->user->name); ?></span>
                        </div>
                        <div class="session-badge">
                            <i class="fas fa-clock"></i>
                            <span><?php echo e($activeSession->start_time->format('d/m/Y H:i')); ?></span>
                        </div>
                        <div class="session-badge">
                            <i class="fas fa-wallet" style="color:var(--success)"></i>
                            <strong style="color:var(--success)"><?php echo e(number_format($activeSession->current_balance, 0, ',', ' ')); ?> FCFA</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('cashier.sessions.show', $activeSession)); ?>" class="btn btn-outline-success">
                    <i class="fas fa-eye"></i> Détails
                </a>
                <?php if($isAdmin && $activeSession->user_id == auth()->id()): ?>
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#closeModal">
                    <i class="fas fa-lock"></i> Clôturer
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="no-session">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="session-icon" style="background:white;color:var(--warning)">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Aucune session active</h5>
                    <p class="mb-0 small text-muted">
                        <?php if($isAdmin): ?>
                        Démarrez une nouvelle session pour commencer
                        <?php else: ?>
                        Contactez un administrateur pour démarrer une session
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            <?php if($isAdmin && $canStartSession): ?>
            <a href="<?php echo e(route('cashier.sessions.create')); ?>" class="btn btn-warning">
                <i class="fas fa-play"></i> Démarrer
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Réservations</div>
                        <div class="stat-value"><?php echo e($todayStats['totalBookings']); ?></div>
                        <div class="stat-subtitle">Aujourd'hui</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(59,130,246,0.1);color:var(--primary)">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Chiffre d'affaires</div>
                        <div class="stat-value"><?php echo e(number_format($todayStats['revenue'], 0)); ?></div>
                        <div class="stat-subtitle">FCFA aujourd'hui</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(16,185,129,0.1);color:var(--success)">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Check-ins</div>
                        <div class="stat-value"><?php echo e($todayStats['checkins']); ?></div>
                        <div class="stat-subtitle">Aujourd'hui</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(6,182,212,0.1);color:var(--info)">
                        <i class="fas fa-door-open"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">En attente</div>
                        <div class="stat-value"><?php echo e($todayStats['pendingPayments']); ?></div>
                        <div class="stat-subtitle">Paiements</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(245,158,11,0.1);color:var(--warning)">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pending">
                <i class="fas fa-clock me-1"></i> Paiements
                <span class="badge bg-warning"><?php echo e($pendingPayments->count()); ?></span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sessions">
                <i class="fas fa-history me-1"></i> Mes sessions
                <span class="badge bg-info"><?php echo e($recentSessions->count()); ?></span>
            </button>
        </li>
        <?php if($isAdmin): ?>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#all-sessions">
                <i class="fas fa-users me-1"></i> Toutes
                <span class="badge bg-dark"><?php echo e($allSessionsCount ?? 0); ?></span>
            </button>
        </li>
        <?php endif; ?>
    </ul>

    <!-- Tab content -->
    <div class="tab-content">
        
        <!-- Pending payments -->
        <div class="tab-pane fade show active" id="pending">
            <div class="table-card">
                <?php if($pendingPayments->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Montant</th>
                                <th>Client</th>
                                <th>Réceptionniste</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $pendingPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong>#<?php echo e($payment->reference); ?></strong></td>
                                <td>
                                    <span class="badge badge-danger-soft">
                                        <?php echo e(number_format($payment->amount, 0)); ?> FCFA
                                    </span>
                                </td>
                                <td>
                                    <?php if($payment->transaction && $payment->transaction->booking && $payment->transaction->booking->customer): ?>
                                    <?php echo e($payment->transaction->booking->customer->name); ?>

                                    <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($payment->user): ?>
                                    <small><?php echo e($payment->user->name); ?></small>
                                    <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?php echo e($payment->created_at->format('d/m H:i')); ?></small>
                                </td>
                                <td>
                                    <?php if($isAdmin): ?>
                                    <button class="btn btn-sm btn-success btn-icon" title="Valider">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <?php else: ?>
                                    <button class="btn btn-sm btn-outline-secondary btn-icon" disabled title="Réservé aux admins">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Aucun paiement en attente</h5>
                    <p class="text-muted">Tous les paiements sont à jour</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- My sessions -->
        <div class="tab-pane fade" id="sessions">
            <div class="table-card">
                <?php if($recentSessions->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Session</th>
                                <th>Début</th>
                                <th>Fin</th>
                                <th>Durée</th>
                                <th>Initial</th>
                                <th>Final</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $recentSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong>#<?php echo e($session->id); ?></strong></td>
                                <td><?php echo e($session->start_time->format('d/m H:i')); ?></td>
                                <td>
                                    <?php if($session->end_time): ?>
                                    <?php echo e($session->end_time->format('d/m H:i')); ?>

                                    <?php else: ?>
                                    <span class="badge badge-warning-soft">En cours</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($session->end_time): ?>
                                    <span class="badge badge-dark-soft">
                                        <?php echo e($session->start_time->diff($session->end_time)->format('%hh %im')); ?>

                                    </span>
                                    <?php else: ?>
                                    <span class="badge badge-info-soft">
                                        <?php echo e($session->start_time->diffForHumans(null, true)); ?>

                                    </span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e(number_format($session->initial_balance, 0)); ?> FCFA</td>
                                <td><?php echo e(number_format($session->final_balance, 0)); ?> FCFA</td>
                                <td>
                                    <?php if($session->status == 'active'): ?>
                                    <span class="badge badge-success-soft">Active</span>
                                    <?php else: ?>
                                    <span class="badge badge-dark-soft">Terminée</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('cashier.sessions.show', $session)); ?>" 
                                       class="btn btn-sm btn-info btn-icon" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Aucune session</h5>
                    <p class="text-muted">Vous n'avez pas encore de sessions</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- All sessions (admin) -->
        <?php if($isAdmin): ?>
        <div class="tab-pane fade" id="all-sessions">
            <div class="filters-row">
                <div class="row g-2">
                    <div class="col-md-4">
                        <select class="form-select">
                            <option>Tous les utilisateurs</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select">
                            <option>Tous les statuts</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="date" class="form-control">
                    </div>
                </div>
            </div>
            
            <div class="table-card">
                <?php if(isset($allSessions) && $allSessions->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Session</th>
                                <th>Début</th>
                                <th>Fin</th>
                                <th>Initial</th>
                                <th>Final</th>
                                <th>Différence</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $allSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="<?php echo e($session->user->getAvatar()); ?>" class="user-avatar" alt="">
                                        <div>
                                            <div class="fw-medium"><?php echo e($session->user->name); ?></div>
                                            <small class="text-muted"><?php echo e($session->user->role); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><strong>#<?php echo e($session->id); ?></strong></td>
                                <td><?php echo e($session->start_time->format('d/m H:i')); ?></td>
                                <td>
                                    <?php if($session->end_time): ?>
                                    <?php echo e($session->end_time->format('d/m H:i')); ?>

                                    <?php else: ?>
                                    <span class="badge badge-warning-soft">En cours</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e(number_format($session->initial_balance, 0)); ?></td>
                                <td><?php echo e(number_format($session->final_balance, 0)); ?></td>
                                <td>
                                    <?php
                                        $diff = $session->final_balance - $session->initial_balance;
                                    ?>
                                    <span class="badge <?php echo e($diff >= 0 ? 'badge-success-soft' : 'badge-danger-soft'); ?>">
                                        <?php echo e($diff >= 0 ? '+' : ''); ?><?php echo e(number_format($diff, 0)); ?>

                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?php echo e($session->status == 'active' ? 'badge-success-soft' : 'badge-dark-soft'); ?>">
                                        <?php echo e($session->status == 'active' ? 'Active' : 'Terminée'); ?>

                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('cashier.sessions.show', $session)); ?>" 
                                       class="btn btn-sm btn-info btn-icon">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Aucune session</h5>
                    <p class="text-muted">Aucune session n'a été créée</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
    </div>
</div>

<!-- Close modal -->
<?php if($activeSession && $isAdmin && $activeSession->user_id == auth()->id()): ?>
<div class="modal fade" id="closeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-lock text-danger me-2"></i>Clôturer la session
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('cashier.sessions.destroy', $activeSession)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Cette action est irréversible.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Solde final (réel)</label>
                        <input type="number" name="final_balance" class="form-control" 
                               step="0.01" value="<?php echo e($activeSession->current_balance); ?>" required>
                        <small class="text-muted">Montant réel en caisse</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Notes</label>
                        <textarea name="closing_notes" class="form-control" rows="3" 
                                  placeholder="Observations, anomalies..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-lock me-2"></i>Clôturer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/cashier/dashboard.blade.php ENDPATH**/ ?>