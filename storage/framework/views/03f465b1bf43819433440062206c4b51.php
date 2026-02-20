
<?php $__env->startSection('title', 'Détails de l\'activité #' . $activity->id); ?>
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

    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
}

* { box-sizing: border-box; }

.activity-detail-page {
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
    margin-bottom: 20px;
}

.card-modern:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--gray-300);
}

.card-header-modern {
    padding: 18px 24px;
    border-bottom: 1px solid var(--gray-100);
    background: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.card-header-modern h5 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-700);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-header-modern h5 i {
    color: var(--primary-500);
}

.card-header-light {
    background: var(--gray-50);
}

.card-body-modern {
    padding: 24px;
}

/* Avatar */
.avatar-lg-modern {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: linear-gradient(135deg, var(--primary-600), var(--primary-400));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.5rem;
    box-shadow: 0 4px 10px rgba(5, 150, 105, 0.2);
    flex-shrink: 0;
}

.avatar-modern {
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
}

/* Badges */
.badge-modern {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
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

.badge-secondary {
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1px solid var(--gray-200);
}

/* Table */
.table-modern {
    width: 100%;
}

.table-modern tr {
    border-bottom: 1px solid var(--gray-100);
}

.table-modern tr:last-child {
    border-bottom: none;
}

.table-modern th {
    padding: 12px 0;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--gray-500);
    letter-spacing: 0.5px;
    width: 120px;
    vertical-align: top;
}

.table-modern td {
    padding: 12px 0;
    font-size: 0.875rem;
    color: var(--gray-800);
}

/* Code */
.code-block {
    background: var(--gray-800);
    color: var(--gray-300);
    padding: 16px;
    border-radius: 12px;
    font-size: 0.813rem;
    font-family: 'Courier New', monospace;
    overflow-x: auto;
    max-height: 400px;
    line-height: 1.5;
    border: 1px solid var(--gray-700);
}

.inline-code {
    background: var(--gray-100);
    color: var(--gray-800);
    padding: 2px 6px;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 0.75rem;
}

/* Info row */
.info-row {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 16px;
}

.info-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    min-width: 100px;
}

.info-value {
    flex: 1;
    font-size: 0.875rem;
    color: var(--gray-800);
}

/* Empty state */
.empty-state-modern {
    text-align: center;
    padding: 40px 20px;
}

.empty-state-modern i {
    font-size: 3rem;
    color: var(--gray-300);
    margin-bottom: 16px;
}

.empty-state-modern h5 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 4px;
}

.empty-state-modern p {
    color: var(--gray-400);
    margin: 0;
}

/* Grille */
.detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .detail-grid {
        grid-template-columns: 1fr;
    }
}

/* Section propriétés */
.properties-section {
    margin-top: 20px;
}

/* Responsive */
@media (max-width: 768px) {
    .activity-detail-page {
        padding: 16px;
    }
    
    .table-modern th {
        width: 100px;
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
</style>

<div class="activity-detail-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-custom">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home fa-xs me-1"></i>Dashboard</a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="<?php echo e(route('activity.index')); ?>">Journal d'activités</a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Détails #<?php echo e($activity->id); ?></span>
    </div>

    <!-- En-tête -->
    <div class="page-header">
        <div class="header-title">
            <span class="header-icon">
                <i class="fas fa-info-circle"></i>
            </span>
            <h1>Détails de l'activité #<?php echo e($activity->id); ?></h1>
        </div>
        <p class="header-subtitle">Informations complètes sur cette action</p>
    </div>

    <!-- Actions -->
    <div class="d-flex justify-content-end gap-2 mb-4">
        <a href="<?php echo e(route('activity.index')); ?>" class="btn-modern btn-outline-modern btn-sm-modern">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
        <button onclick="window.print()" class="btn-modern btn-outline-modern btn-sm-modern">
            <i class="fas fa-print me-1"></i> Imprimer
        </button>
    </div>

    <!-- Grille principale -->
    <div class="detail-grid">
        <!-- Informations générales -->
        <div class="card-modern fade-in">
            <div class="card-header-modern card-header-light">
                <h5>
                    <i class="fas fa-info-circle"></i>
                    Informations générales
                </h5>
            </div>
            <div class="card-body-modern">
                <table class="table-modern">
                    <tr>
                        <th>ID</th>
                        <td><span class="badge-modern badge-secondary">#<?php echo e($activity->id); ?></span></td>
                    </tr>
                    <tr>
                        <th>Date & Heure</th>
                        <td>
                            <div style="font-weight: 500;"><?php echo e($activity->created_at->format('d/m/Y')); ?></div>
                            <div style="font-size: 0.75rem; color: var(--gray-500);"><?php echo e($activity->created_at->format('H:i:s')); ?></div>
                        </td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td style="font-weight: 500;"><?php echo e($activity->description); ?></td>
                    </tr>
                    <tr>
                        <th>Événement</th>
                        <td>
                            <?php
                                $badgeClass = match($activity->event) {
                                    'created' => 'badge-success',
                                    'updated' => 'badge-warning',
                                    'deleted' => 'badge-danger',
                                    'restored' => 'badge-info',
                                    default => 'badge-secondary'
                                };
                                $eventLabel = match($activity->event) {
                                    'created' => 'Création',
                                    'updated' => 'Modification',
                                    'deleted' => 'Suppression',
                                    'restored' => 'Restauration',
                                    default => ucfirst($activity->event)
                                };
                                $eventIcon = match($activity->event) {
                                    'created' => 'fa-plus-circle',
                                    'updated' => 'fa-edit',
                                    'deleted' => 'fa-trash-alt',
                                    'restored' => 'fa-undo-alt',
                                    default => 'fa-history'
                                };
                            ?>
                            <span class="badge-modern <?php echo e($badgeClass); ?>">
                                <i class="fas <?php echo e($eventIcon); ?> me-1"></i>
                                <?php echo e($eventLabel); ?>

                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Log Name</th>
                        <td><span class="inline-code"><?php echo e($activity->log_name); ?></span></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Utilisateur -->
        <div class="card-modern fade-in">
            <div class="card-header-modern card-header-light">
                <h5>
                    <i class="fas fa-user"></i>
                    Utilisateur
                </h5>
            </div>
            <div class="card-body-modern">
                <?php if($activity->causer): ?>
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="avatar-lg-modern">
                            <?php echo e(substr($activity->causer->name, 0, 1)); ?>

                        </div>
                        <div>
                            <h5 style="font-weight: 600; color: var(--gray-800); margin-bottom: 4px;">
                                <?php echo e($activity->causer->name); ?>

                            </h5>
                            <p style="color: var(--gray-500); margin: 0;"><?php echo e($activity->causer->email); ?></p>
                        </div>
                    </div>
                    <table class="table-modern">
                        <tr>
                            <th>ID</th>
                            <td><?php echo e($activity->causer_id); ?></td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td><span class="inline-code"><?php echo e(class_basename($activity->causer_type)); ?></span></td>
                        </tr>
                    </table>
                <?php else: ?>
                    <div class="empty-state-modern">
                        <i class="fas fa-robot"></i>
                        <h5>Action système</h5>
                        <p>Aucun utilisateur associé</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Objet concerné -->
        <div class="card-modern fade-in">
            <div class="card-header-modern card-header-light">
                <h5>
                    <i class="fas fa-cube"></i>
                    Objet concerné
                </h5>
            </div>
            <div class="card-body-modern">
                <?php if($activity->subject): ?>
                    <?php
                        $modelName = class_basename($activity->subject_type);
                        $modelIcon = match($modelName) {
                            'User' => 'fa-user',
                            'Facility' => 'fa-cogs',
                            'Room' => 'fa-bed',
                            'Type' => 'fa-tag',
                            'Transaction' => 'fa-receipt',
                            'Payment' => 'fa-credit-card',
                            'Customer' => 'fa-user-tie',
                            default => 'fa-cube'
                        };
                    ?>
                    
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="avatar-modern" style="width: 48px; height: 48px; font-size: 1.25rem;">
                            <i class="fas <?php echo e($modelIcon); ?>"></i>
                        </div>
                        <div>
                            <h5 style="font-weight: 600; color: var(--gray-800); margin-bottom: 4px;">
                                <?php echo e($modelName); ?>

                            </h5>
                            <p style="color: var(--gray-500); margin: 0;">ID: <?php echo e($activity->subject_id); ?></p>
                        </div>
                    </div>
                    
                    <?php if(method_exists($activity->subject, 'getNameAttribute') && $activity->subject->getNameAttribute()): ?>
                        <div class="info-row">
                            <div class="info-label">Nom</div>
                            <div class="info-value"><?php echo e($activity->subject->getNameAttribute()); ?></div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($activity->subject_url): ?>
                        <div class="mt-4">
                            <a href="<?php echo e($activity->subject_url); ?>" class="btn-modern btn-outline-modern btn-sm-modern">
                                <i class="fas fa-external-link-alt me-1"></i> Voir l'objet
                            </a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="empty-state-modern">
                        <i class="fas fa-trash-alt"></i>
                        <h5>Objet supprimé</h5>
                        <p>L'objet n'existe plus dans la base de données</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Informations techniques -->
        <div class="card-modern fade-in">
            <div class="card-header-modern card-header-light">
                <h5>
                    <i class="fas fa-microchip"></i>
                    Informations techniques
                </h5>
            </div>
            <div class="card-body-modern">
                <table class="table-modern">
                    <tr>
                        <th>IP Address</th>
                        <td><span class="inline-code"><?php echo e($activity->properties['ip_address'] ?? 'N/A'); ?></span></td>
                    </tr>
                    <tr>
                        <th>User Agent</th>
                        <td style="font-size: 0.75rem; word-break: break-word;">
                            <?php echo e($activity->properties['user_agent'] ?? 'N/A'); ?>

                        </td>
                    </tr>
                    <tr>
                        <th>URL</th>
                        <td style="font-size: 0.75rem; word-break: break-word;">
                            <?php echo e($activity->properties['url'] ?? 'N/A'); ?>

                        </td>
                    </tr>
                    <tr>
                        <th>Méthode</th>
                        <td>
                            <?php if(isset($activity->properties['method'])): ?>
                                <span class="badge-modern badge-secondary"><?php echo e($activity->properties['method']); ?></span>
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Propriétés complètes -->
    <div class="card-modern fade-in properties-section">
        <div class="card-header-modern card-header-light">
            <h5>
                <i class="fas fa-code"></i>
                Propriétés complètes
            </h5>
            <span class="badge-modern badge-info"><?php echo e($activity->properties->count()); ?> propriété(s)</span>
        </div>
        <div class="card-body-modern">
            <?php if($activity->properties->count() > 0): ?>
                <pre class="code-block"><?php echo e(json_encode($activity->properties->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                
                <?php if(isset($activity->properties['old']) || isset($activity->properties['attributes'])): ?>
                <div style="margin-top: 20px;">
                    <h6 style="font-weight: 600; color: var(--gray-700); margin-bottom: 12px;">
                        <i class="fas fa-exchange-alt me-2" style="color: var(--primary-500);"></i>
                        Modifications
                    </h6>
                    <div style="background: var(--gray-50); border-radius: 12px; padding: 16px;">
                        <?php if(isset($activity->properties['old'])): ?>
                        <div style="margin-bottom: 16px;">
                            <div style="font-size: 0.75rem; color: var(--gray-500); margin-bottom: 8px;">Anciennes valeurs</div>
                            <pre style="background: var(--gray-800); color: var(--gray-300); padding: 12px; border-radius: 8px; font-size: 0.75rem; max-height: 200px;"><?php echo e(json_encode($activity->properties['old'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(isset($activity->properties['attributes'])): ?>
                        <div>
                            <div style="font-size: 0.75rem; color: var(--gray-500); margin-bottom: 8px;">Nouvelles valeurs</div>
                            <pre style="background: var(--gray-800); color: var(--gray-300); padding: 12px; border-radius: 8px; font-size: 0.75rem; max-height: 200px;"><?php echo e(json_encode($activity->properties['attributes'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="empty-state-modern">
                    <i class="fas fa-inbox"></i>
                    <h5>Aucune propriété</h5>
                    <p>Aucune propriété supplémentaire n'est associée à cette activité</p>
                </div>
            <?php endif; ?>
        </div>
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
    
    console.log('Activity details page loaded');
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/activity/show.blade.php ENDPATH**/ ?>