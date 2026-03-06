
<?php $__env->startSection('title', 'Gestion des Utilisateurs'); ?>
<?php $__env->startSection('content'); ?>

<style>
/* ═══════════════════════════════════════════════════════════════
   DESIGN SYSTEM - VERT SIDEBAR
═══════════════════════════════════════════════════════════════════ */
:root {
    /* Palette principale - Vert du Sidebar */
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
}

* { 
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

.users-page {
    background: var(--gray-50);
    min-height: 100vh;
    padding: 32px;
    font-family: 'Inter', -apple-system, sans-serif;
}

/* ═══════════════════════════════════════════════════════════════
   BREADCRUMB
═══════════════════════════════════════════════════════════════════ */
.breadcrumb-pro {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    margin-bottom: 24px;
}

.breadcrumb-pro a {
    color: var(--gray-600);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
}

.breadcrumb-pro a:hover {
    color: var(--primary-600);
}

.breadcrumb-pro .separator {
    color: var(--gray-400);
}

.breadcrumb-pro .current {
    color: var(--gray-700);
    font-weight: 600;
}

/* ═══════════════════════════════════════════════════════════════
   PAGE HEADER
═══════════════════════════════════════════════════════════════════ */
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
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 10px rgba(42, 168, 116, 0.3);
}

.header-title h1 {
    font-size: 1.875rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
}

.header-subtitle {
    color: var(--gray-500);
    font-size: 0.875rem;
    margin: 6px 0 0 60px;
}

/* ═══════════════════════════════════════════════════════════════
   STATISTICS
═══════════════════════════════════════════════════════════════════ */
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
}

.stat-card.primary::before { background: var(--primary-500); }
.stat-card.success::before { background: var(--success-500); }
.stat-card.warning::before { background: var(--warning-500); }
.stat-card.info::before { background: var(--info-500); }

.stat-number {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gray-800);
    margin-bottom: 4px;
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

/* ═══════════════════════════════════════════════════════════════
   BUTTONS
═══════════════════════════════════════════════════════════════════ */
.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 500;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    color: white;
    box-shadow: 0 4px 6px -1px rgba(42, 168, 116, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-800), var(--primary-600));
    transform: translateY(-1px);
    box-shadow: 0 6px 8px -1px rgba(42, 168, 116, 0.4);
    color: white;
    text-decoration: none;
}

/* ═══════════════════════════════════════════════════════════════
   ACTION BAR
═══════════════════════════════════════════════════════════════════ */
.action-bar {
    background: white;
    border-radius: 16px;
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
    font-weight: 500;
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
}

/* Recherche */
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
}

.search-input:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px rgba(42, 168, 116, 0.1);
}

/* ═══════════════════════════════════════════════════════════════
   CARDS & TABLES
═══════════════════════════════════════════════════════════════════ */
.card-modern {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 24px;
}

.card-header-modern {
    padding: 20px 24px;
    border-bottom: 1px solid var(--gray-200);
    background: var(--gray-50);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.card-header-modern h3 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--gray-700);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-header-modern h3 i {
    color: var(--primary-500);
}

.card-badge {
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

/* Table */
.table-modern {
    width: 100%;
    border-collapse: collapse;
}

.table-modern thead th {
    background: var(--gray-50);
    padding: 16px 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--gray-500);
    border-bottom: 1px solid var(--gray-200);
    text-align: left;
    white-space: nowrap;
}

.table-modern tbody td {
    padding: 16px 20px;
    font-size: 0.875rem;
    color: var(--gray-700);
    border-bottom: 1px solid var(--gray-100);
    white-space: nowrap;
    vertical-align: middle;
}

.table-modern tbody tr:hover {
    background: var(--gray-50);
}

.table-modern tbody tr:last-child td {
    border-bottom: none;
}

/* User Avatar */
.user-avatar-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.user-avatar.admin {
    background: linear-gradient(135deg, #FEF3C7, #FDE68A);
    color: #D97706;
}

.user-avatar.staff {
    background: linear-gradient(135deg, var(--primary-100), var(--primary-50));
    color: var(--primary-700);
}

.user-avatar.customer {
    background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
    color: #2563EB;
}

/* Role Badges */
.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 0.688rem;
    font-weight: 600;
}

.role-badge.admin {
    background: #FEF3C7;
    color: #D97706;
    border: 1px solid #FDE68A;
}

.role-badge.staff {
    background: var(--primary-100);
    color: var(--primary-700);
    border: 1px solid var(--primary-200);
}

.role-badge.customer {
    background: #DBEAFE;
    color: #2563EB;
    border: 1px solid #BFDBFE;
}

/* ═══════════════════════════════════════════════════════════════
   ACTION BUTTONS - TRÈS VISIBLE
═══════════════════════════════════════════════════════════════════ */
.action-group {
    display: flex;
    align-items: center;
    gap: 6px;
}

.action-btn {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: white;
    border: 2px solid var(--gray-200);
    color: var(--gray-600);
    transition: var(--transition);
    cursor: pointer;
    text-decoration: none;
    font-size: 0.875rem;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.action-btn.view {
    border-color: var(--info-500);
    color: var(--info-500);
}

.action-btn.view:hover {
    background: var(--info-500);
    color: white;
}

.action-btn.edit {
    border-color: var(--primary-500);
    color: var(--primary-500);
}

.action-btn.edit:hover {
    background: var(--primary-500);
    color: white;
}

.action-btn.delete {
    border-color: var(--danger-500);
    color: var(--danger-500);
}

.action-btn.delete:hover {
    background: var(--danger-500);
    color: white;
}

.action-btn.disabled {
    opacity: 0.3;
    cursor: not-allowed;
    pointer-events: none;
}

/* ═══════════════════════════════════════════════════════════════
   EMPTY STATE
═══════════════════════════════════════════════════════════════════ */
.empty-state-modern {
    background: white;
    border-radius: 20px;
    padding: 60px 20px;
    text-align: center;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
}

.empty-state-modern i {
    font-size: 4rem;
    color: var(--gray-300);
    margin-bottom: 20px;
}

.empty-state-modern h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 8px;
}

.empty-state-modern p {
    color: var(--gray-400);
    margin-bottom: 24px;
}

/* ═══════════════════════════════════════════════════════════════
   PAGINATION
═══════════════════════════════════════════════════════════════════ */
.pagination-modern {
    display: flex;
    gap: 6px;
    justify-content: center;
    margin: 24px 0 16px;
}

.pagination-modern .page-item {
    list-style: none;
}

.pagination-modern .page-link {
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

.pagination-modern .page-link:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-800);
    transform: translateY(-2px);
}

.pagination-modern .active .page-link {
    background: var(--primary-500);
    border-color: var(--primary-500);
    color: white;
}

/* ═══════════════════════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════════════════════════ */
@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .users-page {
        padding: 16px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .action-bar {
        flex-direction: column;
        align-items: stretch;
    }
    
    .action-right {
        max-width: 100%;
    }
    
    .table-modern {
        display: block;
        overflow-x: auto;
    }
}
</style>

<div class="users-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-pro">
        <a href="<?php echo e(route('dashboard.index')); ?>">
            <i class="fas fa-home fa-xs me-1"></i>Dashboard
        </a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Utilisateurs</span>
    </div>

    <!-- Header -->
    <div class="page-header">
        <div class="header-title">
            <span class="header-icon">
                <i class="fas fa-users-cog"></i>
            </span>
            <div>
                <h1>Gestion des Utilisateurs</h1>
                <p class="header-subtitle">Gérez les utilisateurs et clients de l'application</p>
            </div>
        </div>
        <a href="<?php echo e(route('user.create')); ?>" class="btn-primary">
            <i class="fas fa-plus-circle"></i>
            Nouvel utilisateur
        </a>
    </div>

    <!-- Statistics -->
    <?php
        $totalUsers = $users->total();
        $totalCustomers = $customers->total();
        $adminCount = $users->where('role', 'Admin')->count();
        $receptionistCount = $users->where('role', 'Receptionist')->count();
        $housekeepingCount = $users->where('role', 'Housekeeping')->count();
    ?>
    
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-number"><?php echo e($totalUsers + $totalCustomers); ?></div>
            <div class="stat-label">Utilisateurs totaux</div>
            <div class="stat-footer">
                <i class="fas fa-users"></i>
                Tous les comptes
            </div>
        </div>
        
        <div class="stat-card success">
            <div class="stat-number"><?php echo e($totalUsers); ?></div>
            <div class="stat-label">Utilisateurs</div>
            <div class="stat-footer">
                <i class="fas fa-user-tie"></i>
                <?php echo e($adminCount); ?> admin · <?php echo e($receptionistCount + $housekeepingCount); ?> staff
            </div>
        </div>
        
        <div class="stat-card warning">
            <div class="stat-number"><?php echo e($totalCustomers); ?></div>
            <div class="stat-label">Clients</div>
            <div class="stat-footer">
                <i class="fas fa-user"></i>
                Comptes clients
            </div>
        </div>
        
        <div class="stat-card info">
            <div class="stat-number"><?php echo e($users->currentPage()); ?></div>
            <div class="stat-label">Page actuelle</div>
            <div class="stat-footer">
                <i class="fas fa-layer-group"></i>
                <?php echo e($users->perPage()); ?> par page
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="action-bar">
        <div class="action-left">
            <span class="filter-badge">
                <i class="fas fa-users"></i>
                Tous
                <span class="badge-count"><?php echo e($totalUsers + $totalCustomers); ?></span>
            </span>
        </div>
        
        <div class="action-right">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <form method="GET" action="<?php echo e(route('user.index')); ?>">
                    <input type="hidden" name="qc" value="<?php echo e(request()->input('qc')); ?>">
                    <input type="text" 
                           class="search-input" 
                           placeholder="Rechercher un utilisateur..." 
                           name="qu" 
                           value="<?php echo e(request()->input('qu')); ?>"
                           autocomplete="off">
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Users Column -->
        <div class="col-lg-6">
            <div class="card-modern">
                <div class="card-header-modern">
                    <h3>
                        <i class="fas fa-user-tie"></i>
                        Utilisateurs
                    </h3>
                    <span class="card-badge">
                        <i class="fas fa-users"></i>
                        <?php echo e($users->total()); ?> enregistrés
                    </span>
                </div>
                
                <div class="card-body-modern" style="padding: 0;">
                    <?php if($users->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th style="text-align: center;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <span style="font-weight: 600; color: var(--primary-600);">
                                                    <?php echo e(($users->currentpage() - 1) * $users->perpage() + $loop->index + 1); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <div class="user-avatar-cell">
                                                    <div class="user-avatar <?php echo e($user->role == 'Admin' ? 'admin' : 'staff'); ?>">
                                                        <i class="fas fa-<?php echo e($user->role == 'Admin' ? 'crown' : ($user->role == 'Receptionist' ? 'headset' : 'broom')); ?>"></i>
                                                    </div>
                                                    <span style="font-weight: 500;"><?php echo e($user->name); ?></span>
                                                </div>
                                            </td>
                                            <td><?php echo e($user->email); ?></td>
                                            <td>
                                                <span class="role-badge <?php echo e($user->role == 'Admin' ? 'admin' : 'staff'); ?>">
                                                    <i class="fas fa-<?php echo e($user->role == 'Admin' ? 'shield-alt' : 'id-badge'); ?>"></i>
                                                    <?php echo e($user->role); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-group" style="justify-content: center;">
                                                    <a href="<?php echo e(route('user.show', ['user' => $user->id])); ?>" 
                                                       class="action-btn view"
                                                       data-bs-toggle="tooltip" 
                                                       title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <a href="<?php echo e(route('user.edit', ['user' => $user->id])); ?>" 
                                                       class="action-btn edit"
                                                       data-bs-toggle="tooltip" 
                                                       title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <form class="d-inline" method="POST"
                                                          id="delete-post-form-<?php echo e($user->id); ?>"
                                                          action="<?php echo e(route('user.destroy', ['user' => $user->id])); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="button" 
                                                                class="action-btn delete"
                                                                onclick="confirmDelete('<?php echo e($user->name); ?>', <?php echo e($user->id); ?>, '<?php echo e($user->role); ?>')"
                                                                data-bs-toggle="tooltip" 
                                                                title="Supprimer">
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
                        
                        <?php if($users->hasPages()): ?>
                        <div class="pagination-modern">
                            <?php echo e($users->onEachSide(1)->appends(['customers' => $customers->currentPage(), 'qc' => request()->input('qc')])->links('pagination::bootstrap-5')); ?>

                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="empty-state-modern">
                            <i class="fas fa-user-tie"></i>
                            <h3>Aucun utilisateur</h3>
                            <p>Commencez par ajouter votre premier utilisateur.</p>
                            <a href="<?php echo e(route('user.create')); ?>" class="btn-primary">
                                <i class="fas fa-plus-circle"></i>Ajouter un utilisateur
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Customers Column -->
        <div class="col-lg-6">
            <!-- Customer Search Bar -->
            <div class="action-bar" style="margin-bottom: 16px !important;">
                <div class="action-left">
                    <span class="filter-badge">
                        <i class="fas fa-user"></i>
                        Clients
                        <span class="badge-count"><?php echo e($customers->total()); ?></span>
                    </span>
                </div>
                
                <div class="action-right">
                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <form method="GET" action="<?php echo e(route('user.index')); ?>">
                            <input type="hidden" name="qu" value="<?php echo e(request()->input('qu')); ?>">
                            <input type="text" 
                                   class="search-input" 
                                   placeholder="Rechercher un client..." 
                                   name="qc" 
                                   value="<?php echo e(request()->input('qc')); ?>"
                                   autocomplete="off">
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="card-modern">
                <div class="card-header-modern">
                    <h3>
                        <i class="fas fa-user"></i>
                        Clients
                    </h3>
                    <span class="card-badge">
                        <i class="fas fa-users"></i>
                        <?php echo e($customers->total()); ?> enregistrés
                    </span>
                </div>
                
                <div class="card-body-modern" style="padding: 0;">
                    <?php if($customers->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th style="text-align: center;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <span style="font-weight: 600; color: var(--warning-600);">
                                                    <?php echo e(($customers->currentpage() - 1) * $customers->perpage() + $loop->index + 1); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <div class="user-avatar-cell">
                                                    <div class="user-avatar customer">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <span style="font-weight: 500;"><?php echo e($user->name); ?></span>
                                                </div>
                                            </td>
                                            <td><?php echo e($user->email); ?></td>
                                            <td>
                                                <span class="role-badge customer">
                                                    <i class="fas fa-user"></i>
                                                    Customer
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-group" style="justify-content: center;">
                                                    <span class="action-btn view disabled" 
                                                          data-bs-toggle="tooltip" 
                                                          title="Détails non disponibles">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                    
                                                    <a href="<?php echo e(route('user.edit', ['user' => $user->id])); ?>" 
                                                       class="action-btn edit"
                                                       data-bs-toggle="tooltip" 
                                                       title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <form class="d-inline" method="POST"
                                                          id="delete-post-form-customer-<?php echo e($user->id); ?>"
                                                          action="<?php echo e(route('user.destroy', ['user' => $user->id])); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="button" 
                                                                class="action-btn delete"
                                                                onclick="confirmDelete('<?php echo e($user->name); ?>', <?php echo e($user->id); ?>, 'Customer')"
                                                                data-bs-toggle="tooltip" 
                                                                title="Supprimer">
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
                        
                        <?php if($customers->hasPages()): ?>
                        <div class="pagination-modern">
                            <?php echo e($customers->onEachSide(1)->appends(['users' => $users->currentPage(), 'qu' => request()->input('qu')])->links('pagination::bootstrap-5')); ?>

                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="empty-state-modern">
                            <i class="fas fa-user"></i>
                            <h3>Aucun client</h3>
                            <p>Aucun client enregistré pour le moment.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) {
        return new bootstrap.Tooltip(el);
    });

    // Search debounce
    const searchInputs = document.querySelectorAll('.search-input');
    searchInputs.forEach(input => {
        let searchTimeout;
        input.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.closest('form').submit();
            }, 500);
        });
    });
});

function confirmDelete(name, id, role) {
    Swal.fire({
        title: 'Confirmer la suppression',
        html: `<strong>${name}</strong> (${role}) sera supprimé définitivement.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-trash me-2"></i>Oui, supprimer',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Annuler',
        reverseButtons: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280'
    }).then((result) => {
        if (result.isConfirmed) {
            if (role == "Customer") {
                document.getElementById('delete-post-form-customer-' + id).submit();
            } else {
                document.getElementById('delete-post-form-' + id).submit();
            }
        }
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/user/index.blade.php ENDPATH**/ ?>