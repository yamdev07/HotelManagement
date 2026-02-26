
<?php $__env->startSection('title', 'Gestion des Utilisateurs'); ?>
<?php $__env->startSection('content'); ?>

<style>
/* ═══════════════════════════════════════════════════════════════
   DESIGN SYSTEM - MÊME STYLE QUE GESTION DES CLIENTS
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

    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
}

* { box-sizing: border-box; }

.users-page {
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
    padding: 20px;
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
.stat-card-modern.info::before { background: var(--blue-500); }
.stat-card-modern.warning::before { background: var(--amber-500); }

.stat-number {
    font-size: 1.75rem;
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

.stat-footer {
    margin-top: 12px;
    font-size: 0.688rem;
    color: var(--gray-400);
    display: flex;
    align-items: center;
    gap: 4px;
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

.btn-sm-modern {
    padding: 6px 14px;
    font-size: 0.813rem;
    border-radius: 8px;
}

/* Barre d'actions */
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
    flex-wrap: wrap;
}

.action-right {
    flex: 1;
    max-width: 400px;
}

/* Filtres */
.filter-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.filter-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 500;
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1px solid var(--gray-200);
    text-decoration: none;
    transition: all 0.2s;
}

.filter-badge:hover {
    background: var(--gray-200);
    color: var(--gray-800);
    transform: translateY(-1px);
    text-decoration: none;
}

.filter-badge.active {
    background: var(--primary-100);
    color: var(--primary-700);
    border-color: var(--primary-200);
}

.badge-count {
    background: var(--gray-200);
    padding: 2px 6px;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
    color: var(--gray-700);
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
    transition: all 0.2s;
    background: white;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px var(--primary-100);
}

.search-input::placeholder {
    color: var(--gray-400);
}

/* Cartes tableau */
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

.card-body-modern {
    padding: 0;
}

/* Tableau moderne */
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
}

.table-modern tbody tr:hover {
    background: var(--gray-50);
}

.table-modern tbody tr:last-child td {
    border-bottom: none;
}

/* Badges de rôle */
.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 0.688rem;
    font-weight: 600;
    background: var(--primary-100);
    color: var(--primary-700);
    border: 1px solid var(--primary-200);
}

.role-badge.admin {
    background: var(--amber-100);
    color: var(--amber-700);
    border-color: var(--amber-200);
}

.role-badge.customer {
    background: var(--blue-100);
    color: var(--blue-700);
    border-color: var(--blue-200);
}

/* Actions */
.action-group {
    display: flex;
    align-items: center;
    gap: 4px;
}

.action-btn {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: white;
    border: 1px solid var(--gray-200);
    color: var(--gray-600);
    transition: all 0.2s;
    cursor: pointer;
    text-decoration: none;
    font-size: 0.875rem;
}

.action-btn:hover {
    background: var(--gray-100);
    border-color: var(--gray-300);
    color: var(--gray-900);
    transform: translateY(-1px);
    text-decoration: none;
}

.action-btn.edit:hover {
    background: var(--primary-100);
    border-color: var(--primary-300);
    color: var(--primary-700);
}

.action-btn.delete:hover {
    background: #fee2e2;
    border-color: #fecaca;
    color: #b91c1c;
}

.action-btn.view:hover {
    background: var(--blue-100);
    border-color: var(--blue-300);
    color: var(--blue-700);
}

/* État vide */
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
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

/* Pagination */
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
    transition: all 0.2s;
    text-decoration: none;
}

.pagination-modern .page-link:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-800);
    transform: translateY(-2px);
    text-decoration: none;
}

.pagination-modern .active .page-link {
    background: var(--primary-500);
    border-color: var(--primary-500);
    color: white;
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

/* Responsive */
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
    
    .card-header-modern {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .table-modern {
        display: block;
        overflow-x: auto;
    }
}

/* Alertes */
.alert-modern {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    font-size: 0.875rem;
    background: white;
    box-shadow: var(--shadow-md);
}

.alert-success {
    background: var(--primary-50);
    border-color: var(--primary-200);
    color: var(--primary-800);
}

.alert-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: var(--primary-500);
    color: white;
}

.alert-close {
    margin-left: auto;
    background: none;
    border: none;
    color: currentColor;
    opacity: 0.5;
    cursor: pointer;
    padding: 4px;
    font-size: 0.9rem;
    transition: all 0.2s;
}

.alert-close:hover {
    opacity: 1;
}
</style>

<div class="users-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-custom fade-in">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home fa-xs me-1"></i>Dashboard</a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Utilisateurs</span>
    </div>

    <!-- En-tête -->
    <div class="page-header fade-in">
        <div class="header-title">
            <span class="header-icon">
                <i class="fas fa-users-cog"></i>
            </span>
            <h1>Gestion des Utilisateurs</h1>
        </div>
        <p class="header-subtitle">Gérez les utilisateurs et clients de l'application</p>
    </div>

    <!-- Statistiques -->
    <?php
        $totalUsers = $users->total();
        $totalCustomers = $customers->total();
        $adminCount = $users->where('role', 'Admin')->count();
        $staffCount = $users->where('role', 'Staff')->count();
    ?>
    
    <div class="stats-grid fade-in">
        <div class="stat-card-modern primary stagger-1">
            <div class="stat-number"><?php echo e($totalUsers + $totalCustomers); ?></div>
            <div class="stat-label">Utilisateurs totaux</div>
            <div class="stat-footer">
                <i class="fas fa-users"></i>
                Tous les comptes
            </div>
        </div>
        
        <div class="stat-card-modern success stagger-2">
            <div class="stat-number"><?php echo e($totalUsers); ?></div>
            <div class="stat-label">Utilisateurs</div>
            <div class="stat-footer">
                <i class="fas fa-user-tie"></i>
                <?php echo e($adminCount); ?> admin, <?php echo e($staffCount); ?> staff
            </div>
        </div>
        
        <div class="stat-card-modern info stagger-3">
            <div class="stat-number"><?php echo e($totalCustomers); ?></div>
            <div class="stat-label">Clients</div>
            <div class="stat-footer">
                <i class="fas fa-user"></i>
                Comptes clients
            </div>
        </div>
        
        <div class="stat-card-modern warning stagger-4">
            <div class="stat-number"><?php echo e($users->currentPage()); ?></div>
            <div class="stat-label">Page actuelle</div>
            <div class="stat-footer">
                <i class="fas fa-layer-group"></i>
                <?php echo e($users->perPage()); ?> par page
            </div>
        </div>
    </div>

    <!-- Barre d'actions globale -->
    <div class="action-bar fade-in">
        <div class="action-left">
            <a href="<?php echo e(route('user.create')); ?>" class="btn-modern btn-primary-modern">
                <i class="fas fa-plus-circle"></i>
                Nouvel utilisateur
            </a>
            
            <div class="filter-badges">
                <span class="filter-badge active">
                    <i class="fas fa-users"></i>
                    Tous
                </span>
            </div>
        </div>
        
        <div class="action-right">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <form method="GET" action="<?php echo e(route('user.index')); ?>" id="search-form">
                    <input type="hidden" name="qc" value="<?php echo e(request()->input('qc')); ?>">
                    <input type="hidden" name="customers" value="<?php echo e(request()->input('customers')); ?>">
                    <input type="text" 
                           class="search-input" 
                           placeholder="Rechercher un utilisateur..." 
                           name="qu" 
                           id="search-user"
                           value="<?php echo e(request()->input('qu')); ?>"
                           autocomplete="off">
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Colonne gauche - Utilisateurs -->
        <div class="col-lg-6">
            <div class="card-modern fade-in">
                <div class="card-header-modern">
                    <h3>
                        <i class="fas fa-user-tie"></i>
                        Utilisateurs
                    </h3>
                    <span class="filter-badge">
                        <i class="fas fa-users"></i>
                        <?php echo e($users->total()); ?> enregistrés
                    </span>
                </div>
                
                <div class="card-body-modern">
                    <?php if($users->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <span class="fw-semibold">
                                                    <?php echo e(($users->currentpage() - 1) * $users->perpage() + $loop->index + 1); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-circle bg-<?php echo e($user->role == 'Admin' ? 'warning' : 'primary'); ?> bg-opacity-10 p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-<?php echo e($user->role == 'Admin' ? 'crown' : 'user'); ?> text-<?php echo e($user->role == 'Admin' ? 'warning' : 'primary'); ?>" style="font-size: 0.875rem;"></i>
                                                    </div>
                                                    <span class="fw-medium"><?php echo e($user->name); ?></span>
                                                </div>
                                            </td>
                                            <td><?php echo e($user->email); ?></td>
                                            <td>
                                                <span class="role-badge <?php echo e($user->role == 'Admin' ? 'admin' : ''); ?>">
                                                    <i class="fas fa-<?php echo e($user->role == 'Admin' ? 'shield-alt' : 'user'); ?>"></i>
                                                    <?php echo e($user->role); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-group">
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
                                                    
                                                    <a href="<?php echo e(route('user.show', ['user' => $user->id])); ?>" 
                                                       class="action-btn view"
                                                       data-bs-toggle="tooltip" 
                                                       title="Détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if($users->hasPages()): ?>
                        <div class="pagination-modern">
                            <?php echo e($users->onEachSide(1)->appends(['customers' => $customers->currentPage(), 'qc' => request()->input('qc')])->links('pagination::bootstrap-5')); ?>

                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="empty-state-modern py-5">
                            <i class="fas fa-user-tie"></i>
                            <h3>Aucun utilisateur</h3>
                            <p>Commencez par ajouter votre premier utilisateur.</p>
                            <a href="<?php echo e(route('user.create')); ?>" class="btn-modern btn-primary-modern">
                                <i class="fas fa-plus-circle me-2"></i>Ajouter un utilisateur
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Colonne droite - Clients -->
        <div class="col-lg-6">
            <!-- Barre de recherche clients -->
            <div class="action-bar fade-in mb-3" style="margin-bottom: 16px !important;">
                <div class="action-left">
                    <span class="filter-badge active">
                        <i class="fas fa-user"></i>
                        Clients
                    </span>
                </div>
                
                <div class="action-right">
                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <form method="GET" action="<?php echo e(route('user.index')); ?>">
                            <input type="hidden" name="qu" value="<?php echo e(request()->input('qu')); ?>">
                            <input type="hidden" name="users" value="<?php echo e(request()->input('users')); ?>">
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
            
            <div class="card-modern fade-in">
                <div class="card-header-modern">
                    <h3>
                        <i class="fas fa-user"></i>
                        Clients
                    </h3>
                    <span class="filter-badge">
                        <i class="fas fa-users"></i>
                        <?php echo e($customers->total()); ?> enregistrés
                    </span>
                </div>
                
                <div class="card-body-modern">
                    <?php if($customers->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <span class="fw-semibold">
                                                    <?php echo e(($customers->currentpage() - 1) * $customers->perpage() + $loop->index + 1); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-circle bg-info bg-opacity-10 p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-user text-info" style="font-size: 0.875rem;"></i>
                                                    </div>
                                                    <span class="fw-medium"><?php echo e($user->name); ?></span>
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
                                                <div class="action-group">
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
                                                    
                                                    <span class="action-btn view disabled" 
                                                          style="opacity: 0.5; cursor: not-allowed;"
                                                          data-bs-toggle="tooltip" 
                                                          title="Détails non disponibles">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if($customers->hasPages()): ?>
                        <div class="pagination-modern">
                            <?php echo e($customers->onEachSide(1)->appends(['users' => $users->currentPage(), 'qu' => request()->input('qu')])->links('pagination::bootstrap-5')); ?>

                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="empty-state-modern py-5">
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

<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) {
        return new bootstrap.Tooltip(el);
    });

    // Debounce pour la recherche
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

    // Raccourcis clavier
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + N pour nouvel utilisateur
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            window.location.href = "<?php echo e(route('user.create')); ?>";
        }
        
        // / pour focus recherche
        if (e.key === '/' && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
            e.preventDefault();
            document.querySelector('.search-input')?.focus();
        }
    });
});

// Confirmation de suppression
function confirmDelete(name, id, role) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
        title: 'Confirmer la suppression',
        html: `<strong>${name}</strong> (${role}) sera supprimé. Cette action est irréversible.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-trash me-2"></i>Oui, supprimer',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Annuler',
        reverseButtons: true,
        background: '#ffffff',
        borderRadius: '12px'
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