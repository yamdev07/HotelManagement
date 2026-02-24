

<?php $__env->startSection('title', 'Sessions de Caisse'); ?>

<?php $__env->startPush('styles'); ?>
<style>
:root {
    --primary: #3b82f6;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --dark: #1e293b;
    --light: #f8fafc;
    --border: #e2e8f0;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
}

.sessions-header {
    background: white;
    border-bottom: 1px solid var(--border);
    padding: 1.5rem 0;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow-sm);
}

.sessions-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Stats cards */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.25rem;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

.stat-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--dark);
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-value-success {
    color: var(--success);
}

.stat-value-warning {
    color: var(--warning);
}

.stat-subtitle {
    font-size: 0.75rem;
    color: #94a3b8;
}

/* Filters */
.filters-row {
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: center;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.filter-select, .filter-input {
    width: 100%;
    padding: 0.5rem 1rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.875rem;
    color: var(--dark);
    background: white;
}

.filter-select:focus, .filter-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
}

/* Table */
.sessions-table {
    background: white;
    border-radius: 16px;
    border: 1px solid var(--border);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.sessions-table table {
    width: 100%;
    border-collapse: collapse;
}

.sessions-table th {
    background: var(--light);
    padding: 1rem 1.25rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid var(--border);
}

.sessions-table td {
    padding: 1.25rem;
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
}

.sessions-table tbody tr:hover {
    background: var(--light);
    cursor: pointer;
}

/* Badges */
.badge {
    display: inline-block;
    padding: 0.35rem 1rem;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-active {
    background: rgba(16,185,129,0.1);
    color: #059669;
    border: 1px solid rgba(16,185,129,0.2);
}

.badge-closed {
    background: rgba(30,41,59,0.1);
    color: #1e293b;
    border: 1px solid rgba(30,41,59,0.2);
}

.badge-pending {
    background: rgba(245,158,11,0.1);
    color: #d97706;
    border: 1px solid rgba(245,158,11,0.2);
}

/* User avatar */
.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar-sm {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid white;
    box-shadow: var(--shadow-sm);
}

.avatar-placeholder-sm {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--success));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
}

.user-details {
    line-height: 1.3;
}

.user-name {
    font-weight: 600;
    color: var(--dark);
}

.user-role {
    font-size: 0.7rem;
    color: #64748b;
}

/* Montants */
.amount {
    font-weight: 700;
    color: var(--dark);
}

.amount-positive {
    color: var(--success);
    font-weight: 700;
}

.amount-negative {
    color: var(--danger);
    font-weight: 700;
}

/* Actions */
.btn-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: white;
    border: 1px solid var(--border);
    color: #64748b;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-icon:hover {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(59,130,246,0.2);
}

/* Pagination */
.pagination {
    padding: 1.25rem;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: flex-end;
}

.pagination :deep(.page-link) {
    border: 1px solid var(--border);
    color: #64748b;
    margin: 0 2px;
}

.pagination :deep(.page-item.active .page-link) {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #94a3b8;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: var(--light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2.5rem;
    color: #cbd5e1;
}

/* Tooltip */
[data-tooltip] {
    position: relative;
    cursor: help;
}

[data-tooltip]:before {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    padding: 0.25rem 0.5rem;
    background: var(--dark);
    color: white;
    font-size: 0.7rem;
    border-radius: 4px;
    white-space: nowrap;
    display: none;
    z-index: 10;
}

[data-tooltip]:hover:before {
    display: block;
}

/* Responsive */
@media (max-width: 768px) {
    .sessions-table {
        overflow-x: auto;
    }
    
    .sessions-table table {
        min-width: 800px;
    }
    
    .stats-row {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .stat-value {
        font-size: 1.5rem;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    <!-- Header -->
    <div class="sessions-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="sessions-title">
                    <i class="fas fa-history me-2" style="color:var(--primary)"></i>
                    Sessions de Caisse
                </h1>
                <p class="text-muted mt-2 small">
                    Gérez et consultez toutes les sessions de caisse
                </p>
            </div>
            <?php if(in_array(auth()->user()->role, ['Admin', 'Super'])): ?>
            <a href="<?php echo e(route('cashier.sessions.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nouvelle session
            </a>
            <?php endif; ?>
        </div>
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('dashboard.index')); ?>">
                        <i class="fas fa-home"></i> Accueil
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('cashier.dashboard')); ?>">Caissier</a>
                </li>
                <li class="breadcrumb-item active">Sessions</li>
            </ol>
        </nav>
    </div>

    <!-- Statistiques -->
    <?php
        $totalSessions = $sessions->total();
        $activeSessions = $sessions->where('status', 'active')->count();
        $totalRevenue = $sessions->sum(function($session) {
            return $session->final_balance ?? $session->current_balance ?? 0;
        });
        $avgSession = $sessions->avg(function($session) {
            return $session->final_balance ?? $session->current_balance ?? 0;
        });
    ?>

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-label">Total sessions</div>
            <div class="stat-value"><?php echo e($totalSessions); ?></div>
            <div class="stat-subtitle">Depuis le début</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Sessions actives</div>
            <div class="stat-value stat-value-success"><?php echo e($activeSessions); ?></div>
            <div class="stat-subtitle">En cours</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Chiffre d'affaires</div>
            <div class="stat-value stat-value-warning"><?php echo e(number_format($totalRevenue, 0, ',', ' ')); ?></div>
            <div class="stat-subtitle">FCFA total</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Moyenne/session</div>
            <div class="stat-value"><?php echo e(number_format($avgSession, 0, ',', ' ')); ?></div>
            <div class="stat-subtitle">FCFA par session</div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="filters-row">
        <div class="filter-group">
            <select class="filter-select" id="status-filter">
                <option value="">Tous les statuts</option>
                <option value="active">Sessions actives</option>
                <option value="closed">Sessions fermées</option>
            </select>
        </div>
        <div class="filter-group">
            <select class="filter-select" id="user-filter">
                <option value="">Tous les utilisateurs</option>
                <?php if(isset($allUsers) && $allUsers): ?>
                    <?php $__currentLoopData = $allUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="filter-group">
            <input type="date" class="filter-input" id="date-filter" placeholder="Date">
        </div>
        <div class="filter-group">
            <button class="btn btn-primary" id="apply-filters">
                <i class="fas fa-filter me-2"></i>Filtrer
            </button>
            <button class="btn btn-outline-secondary" id="reset-filters">
                <i class="fas fa-undo me-2"></i>Réinitialiser
            </button>
        </div>
    </div>

    <!-- Liste des sessions -->
    <div class="sessions-table">
        <?php if($sessions->count() > 0): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Réceptionniste</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Durée</th>
                        <th>Initial</th>
                        <th>Final</th>
                        <th>Différence</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $finalAmount = $session->final_balance ?? $session->current_balance ?? 0;
                        $difference = $finalAmount - $session->initial_balance;
                        $duration = $session->end_time 
                            ? $session->start_time->diff($session->end_time)
                            : $session->start_time->diff(now());
                    ?>
                    <tr onclick="window.location='<?php echo e(route('cashier.sessions.show', $session)); ?>'" style="cursor: pointer;">
                        <td><strong>#<?php echo e($session->id); ?></strong></td>
                        <td>
                            <div class="user-info">
                                <?php if($session->user && $session->user->getAvatar()): ?>
                                    <img src="<?php echo e($session->user->getAvatar()); ?>" class="user-avatar-sm" alt="">
                                <?php else: ?>
                                    <div class="avatar-placeholder-sm">
                                        <?php echo e(strtoupper(substr($session->user->name ?? 'U', 0, 1))); ?>

                                    </div>
                                <?php endif; ?>
                                <div class="user-details">
                                    <div class="user-name"><?php echo e($session->user->name ?? 'Utilisateur inconnu'); ?></div>
                                    <div class="user-role"><?php echo e($session->user->role ?? 'N/A'); ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div><?php echo e($session->start_time->format('d/m/Y')); ?></div>
                            <small class="text-muted"><?php echo e($session->start_time->format('H:i')); ?></small>
                        </td>
                        <td>
                            <?php if($session->end_time): ?>
                                <div><?php echo e($session->end_time->format('d/m/Y')); ?></div>
                                <small class="text-muted"><?php echo e($session->end_time->format('H:i')); ?></small>
                            <?php else: ?>
                                <span class="badge badge-pending">En cours</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge" style="background: rgba(59,130,246,0.1); color: var(--primary);">
                                <?php echo e($duration->format('%Hh %Im')); ?>

                            </span>
                        </td>
                        <td class="amount"><?php echo e(number_format($session->initial_balance, 0, ',', ' ')); ?></td>
                        <td class="amount"><?php echo e(number_format($finalAmount, 0, ',', ' ')); ?></td>
                        <td>
                            <?php
                                $diffClass = $difference > 0 ? 'amount-positive' : ($difference < 0 ? 'amount-negative' : '');
                                $diffSign = $difference > 0 ? '+' : '';
                            ?>
                            <span class="<?php echo e($diffClass); ?>">
                                <?php echo e($diffSign); ?><?php echo e(number_format($difference, 0, ',', ' ')); ?>

                            </span>
                            <?php if($session->status == 'active'): ?>
                                <span class="d-block small text-muted" data-tooltip="Solde actuel">(estimé)</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?php echo e($session->status == 'active' ? 'badge-active' : 'badge-closed'); ?>">
                                <?php echo e($session->status == 'active' ? 'Active' : 'Fermée'); ?>

                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1" onclick="event.stopPropagation();">
                                <a href="<?php echo e(route('cashier.sessions.show', $session)); ?>" 
                                   class="btn-icon" 
                                   title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if($session->status == 'closed'): ?>
                                <a href="<?php echo e(route('cashier.sessions.report', $session)); ?>" 
                                   class="btn-icon"
                                   title="Rapport détaillé">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        
        <?php if(method_exists($sessions, 'links')): ?>
        <div class="pagination">
            <?php echo e($sessions->links()); ?>

        </div>
        <?php endif; ?>
        
        <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-history"></i>
            </div>
            <h5 class="fw-bold mb-2">Aucune session</h5>
            <p class="text-muted mb-3">Aucune session de caisse n'a été trouvée.</p>
            <?php if(in_array(auth()->user()->role, ['Admin', 'Super'])): ?>
            <a href="<?php echo e(route('cashier.sessions.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Démarrer une première session
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des filtres
    const statusFilter = document.getElementById('status-filter');
    const userFilter = document.getElementById('user-filter');
    const dateFilter = document.getElementById('date-filter');
    const applyBtn = document.getElementById('apply-filters');
    const resetBtn = document.getElementById('reset-filters');
    
    if (applyBtn) {
        applyBtn.addEventListener('click', function() {
            const url = new URL(window.location.href);
            
            if (statusFilter.value) {
                url.searchParams.set('status', statusFilter.value);
            } else {
                url.searchParams.delete('status');
            }
            
            if (userFilter.value) {
                url.searchParams.set('user_id', userFilter.value);
            } else {
                url.searchParams.delete('user_id');
            }
            
            if (dateFilter.value) {
                url.searchParams.set('date', dateFilter.value);
            } else {
                url.searchParams.delete('date');
            }
            
            window.location.href = url.toString();
        });
    }
    
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            const url = new URL(window.location.href);
            url.searchParams.delete('status');
            url.searchParams.delete('user_id');
            url.searchParams.delete('date');
            window.location.href = url.toString();
        });
    }
    
    // Charger les paramètres existants
    const params = new URLSearchParams(window.location.search);
    if (statusFilter && params.has('status')) {
        statusFilter.value = params.get('status');
    }
    if (userFilter && params.has('user_id')) {
        userFilter.value = params.get('user_id');
    }
    if (dateFilter && params.has('date')) {
        dateFilter.value = params.get('date');
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/cashier/sessions/index.blade.php ENDPATH**/ ?>