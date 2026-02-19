
<?php $__env->startSection('title', 'Journal d\'activités'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h1 class="h3 mb-0">
                                <i class="fas fa-history me-2"></i>Journal d'activités
                            </h1>
                            <div class="btn-group">
                                <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-download me-1"></i> Exporter
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?php echo e(route('activity.export', ['format' => 'csv'])); ?>">CSV</a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('activity.export', ['format' => 'json'])); ?>">JSON</a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('activity.export', ['format' => 'pdf'])); ?>">PDF</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body bg-light">
                        <form method="GET" action="<?php echo e(route('activity.index')); ?>" class="row g-3">
                            <div class="col-md-3">
                                <label for="user" class="form-label">Utilisateur</label>
                                <select name="user_id" id="user" class="form-select">
                                    <option value="">Tous les utilisateurs</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>>
                                            <?php echo e($user->name); ?> (<?php echo e($user->email); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="event" class="form-label">Événement</label>
                                <select name="event" id="event" class="form-select">
                                    <option value="">Tous les événements</option>
                                    <option value="created" <?php echo e(request('event') == 'created' ? 'selected' : ''); ?>>Création</option>
                                    <option value="updated" <?php echo e(request('event') == 'updated' ? 'selected' : ''); ?>>Modification</option>
                                    <option value="deleted" <?php echo e(request('event') == 'deleted' ? 'selected' : ''); ?>>Suppression</option>
                                    <option value="restored" <?php echo e(request('event') == 'restored' ? 'selected' : ''); ?>>Restauration</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="date_from" class="form-label">Date de début</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" 
                                       value="<?php echo e(request('date_from')); ?>">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="date_to" class="form-label">Date de fin</label>
                                <input type="date" name="date_to" id="date_to" class="form-control" 
                                       value="<?php echo e(request('date_to')); ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="subject_type" class="form-label">Type d'objet</label>
                                <select name="subject_type" id="subject_type" class="form-select">
                                    <option value="">Tous les types</option>
                                    <option value="App\Models\User" <?php echo e(request('subject_type') == 'App\Models\User' ? 'selected' : ''); ?>>Utilisateurs</option>
                                    <option value="App\Models\Facility" <?php echo e(request('subject_type') == 'App\Models\Facility' ? 'selected' : ''); ?>>Équipements</option>
                                    <option value="App\Models\Room" <?php echo e(request('subject_type') == 'App\Models\Room' ? 'selected' : ''); ?>>Chambres</option>
                                    <option value="App\Models\Type" <?php echo e(request('subject_type') == 'App\Models\Type' ? 'selected' : ''); ?>>Types</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="search" class="form-label">Recherche</label>
                                <input type="text" name="search" id="search" class="form-control" 
                                       placeholder="Rechercher dans la description..." value="<?php echo e(request('search')); ?>">
                            </div>
                            
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter me-1"></i> Filtrer
                                    </button>
                                    <a href="<?php echo e(route('activity.index')); ?>" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i> Réinitialiser
                                    </a>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#cleanupModal">
                                        <i class="fas fa-broom me-1"></i> Nettoyer
                                    </button>
                                </div>
                            </div>
                        </form>
                        
                        <!-- Résumé des filtres -->
                        <?php if(request()->anyFilled(['user_id', 'event', 'date_from', 'date_to', 'search'])): ?>
                            <div class="mt-3 p-2 bg-info bg-opacity-10 border-start border-info border-4">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Filtres actifs : 
                                    <?php if(request('user_id')): ?> Utilisateur #<?php echo e(request('user_id')); ?> <?php endif; ?>
                                    <?php if(request('event')): ?> | Événement: <?php echo e(request('event')); ?> <?php endif; ?>
                                    <?php if(request('date_from')): ?> | À partir du: <?php echo e(request('date_from')); ?> <?php endif; ?>
                                    <?php if(request('date_to')): ?> | Jusqu'au: <?php echo e(request('date_to')); ?> <?php endif; ?>
                                    <?php if(request('search')): ?> | Recherche: "<?php echo e(request('search')); ?>" <?php endif; ?>
                                    - <?php echo e($activities->total()); ?> résultat(s)
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th width="200">Date & Heure</th>
                                        <th>Action</th>
                                        <th width="150">Utilisateur</th>
                                        <th width="150">Objet</th>
                                        <th width="100">Événement</th>
                                        <th width="100">IP</th>
                                        <th width="80" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="fw-bold"><?php echo e(($activities->currentPage() - 1) * $activities->perPage() + $loop->iteration); ?></td>
                                            <td>
                                                <div class="text-muted small"><?php echo e($activity->created_at->format('d/m/Y')); ?></div>
                                                <div class="small"><?php echo e($activity->created_at->format('H:i:s')); ?></div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold"><?php echo e($activity->description); ?></div>
                                                <?php if($activity->properties->count() > 0): ?>
                                                    <button class="btn btn-sm btn-outline-info mt-1" type="button" 
                                                            data-bs-toggle="collapse" 
                                                            data-bs-target="#details-<?php echo e($activity->id); ?>">
                                                        <i class="fas fa-eye me-1"></i> Voir les détails
                                                    </button>
                                                    <div class="collapse mt-2" id="details-<?php echo e($activity->id); ?>">
                                                        <div class="card card-body bg-light p-2">
                                                            <pre class="mb-0 small" style="max-height: 200px; overflow: auto;"><?php echo e(json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($activity->causer): ?>
                                                    <div class="d-flex align-items-center">
                                                        <?php if($activity->causer->avatar): ?>
                                                            <img src="<?php echo e(asset('storage/' . $activity->causer->avatar)); ?>" 
                                                                 alt="<?php echo e($activity->causer->name); ?>" 
                                                                 class="rounded-circle me-2" width="30" height="30">
                                                        <?php else: ?>
                                                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                                 style="width: 30px; height: 30px;">
                                                                <?php echo e(substr($activity->causer->name, 0, 1)); ?>

                                                            </div>
                                                        <?php endif; ?>
                                                        <div>
                                                            <div class="fw-semibold"><?php echo e($activity->causer->name); ?></div>
                                                            <small class="text-muted"><?php echo e($activity->causer->email); ?></small>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted fst-italic">Système</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($activity->subject): ?>
                                                    <?php
                                                        $modelName = class_basename($activity->subject_type);
                                                        $modelIcon = match($modelName) {
                                                            'User' => 'fa-user',
                                                            'Facility' => 'fa-cogs',
                                                            'Room' => 'fa-door-closed',
                                                            'Type' => 'fa-tag',
                                                            default => 'fa-cube'
                                                        };
                                                    ?>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas <?php echo e($modelIcon); ?> me-2 text-primary"></i>
                                                        <div>
                                                            <div class="fw-semibold"><?php echo e($modelName); ?></div>
                                                            <?php if(method_exists($activity->subject, 'getNameAttribute')): ?>
                                                                <small class="text-muted"><?php echo e($activity->subject->name); ?></small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted fst-italic">Supprimé</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $eventColor = match($activity->event) {
                                                        'created' => 'success',
                                                        'updated' => 'warning',
                                                        'deleted' => 'danger',
                                                        'restored' => 'info',
                                                        default => 'secondary'
                                                    };
                                                    $eventLabel = match($activity->event) {
                                                        'created' => 'Création',
                                                        'updated' => 'Modification',
                                                        'deleted' => 'Suppression',
                                                        'restored' => 'Restauration',
                                                        default => ucfirst($activity->event)
                                                    };
                                                ?>
                                                <span class="badge bg-<?php echo e($eventColor); ?>"><?php echo e($eventLabel); ?></span>
                                            </td>
                                            <td>
                                                <small class="text-monospace"><?php echo e($activity->properties['ip_address'] ?? 'N/A'); ?></small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-info" 
                                                            data-bs-toggle="tooltip" title="Voir les détails"
                                                            onclick="showActivityDetails(<?php echo e($activity->id); ?>)">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                    <a href="<?php echo e(route('activity.show', $activity->id)); ?>" 
                                                       class="btn btn-outline-primary" 
                                                       data-bs-toggle="tooltip" title="Ouvrir">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                                    <h4>Aucune activité trouvée</h4>
                                                    <p>Aucun log d'activité n'a été enregistré pour le moment.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination améliorée -->
                        <?php if($activities->hasPages()): ?>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        Affichage de <strong><?php echo e($activities->firstItem()); ?></strong> à 
                                        <strong><?php echo e($activities->lastItem()); ?></strong> sur 
                                        <strong><?php echo e($activities->total()); ?></strong> entrées
                                    </div>
                                    
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination mb-0">
                                            <!-- First Page -->
                                            <li class="page-item <?php echo e($activities->onFirstPage() ? 'disabled' : ''); ?>">
                                                <a class="page-link" href="<?php echo e($activities->url(1)); ?>">
                                                    <i class="fas fa-angle-double-left"></i>
                                                </a>
                                            </li>
                                            
                                            <!-- Previous Page -->
                                            <li class="page-item <?php echo e($activities->onFirstPage() ? 'disabled' : ''); ?>">
                                                <a class="page-link" href="<?php echo e($activities->previousPageUrl()); ?>">
                                                    <i class="fas fa-angle-left"></i>
                                                </a>
                                            </li>
                                            
                                            <!-- Dynamic Pagination Numbers -->
                                            <?php
                                                $current = $activities->currentPage();
                                                $last = $activities->lastPage();
                                                $start = max($current - 2, 1);
                                                $end = min($current + 2, $last);
                                                
                                                if ($start > 1) {
                                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                                }
                                                
                                                for ($i = $start; $i <= $end; $i++) {
                                                    echo '<li class="page-item ' . ($i == $current ? 'active' : '') . '">';
                                                    echo '<a class="page-link" href="' . $activities->url($i) . '">' . $i . '</a>';
                                                    echo '</li>';
                                                }
                                                
                                                if ($end < $last) {
                                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                                }
                                            ?>
                                            
                                            <!-- Next Page -->
                                            <li class="page-item <?php echo e(!$activities->hasMorePages() ? 'disabled' : ''); ?>">
                                                <a class="page-link" href="<?php echo e($activities->nextPageUrl()); ?>">
                                                    <i class="fas fa-angle-right"></i>
                                                </a>
                                            </li>
                                            
                                            <!-- Last Page -->
                                            <li class="page-item <?php echo e(!$activities->hasMorePages() ? 'disabled' : ''); ?>">
                                                <a class="page-link" href="<?php echo e($activities->url($last)); ?>">
                                                    <i class="fas fa-angle-double-right"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                    
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted small">Par page:</span>
                                        <select class="form-select form-select-sm w-auto" onchange="changePerPage(this)">
                                            <option value="10" <?php echo e($activities->perPage() == 10 ? 'selected' : ''); ?>>10</option>
                                            <option value="25" <?php echo e($activities->perPage() == 25 ? 'selected' : ''); ?>>25</option>
                                            <option value="50" <?php echo e($activities->perPage() == 50 ? 'selected' : ''); ?>>50</option>
                                            <option value="100" <?php echo e($activities->perPage() == 100 ? 'selected' : ''); ?>>100</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour les détails -->
    <div class="modal fade" id="activityDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Détails de l'activité</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="activityDetailsContent">
                    <!-- Content loaded via JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de nettoyage -->
    <div class="modal fade" id="cleanupModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nettoyer les logs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('activity.cleanup')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <p>Supprimer les logs plus anciens que :</p>
                        <div class="mb-3">
                            <label class="form-label">Nombre de jours</label>
                            <input type="number" name="days" class="form-control" min="1" max="365" value="30">
                            <small class="text-muted">Les logs plus anciens que ce nombre de jours seront supprimés.</small>
                        </div>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Cette action est irréversible. <?php echo e($totalActivities); ?> logs seront analysés.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Nettoyer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            function showActivityDetails(activityId) {
                fetch(`/activity/${activityId}/details`)
                    .then(response => response.json())
                    .then(data => {
                        const modal = document.getElementById('activityDetailsModal');
                        const content = document.getElementById('activityDetailsContent');
                        
                        let html = `
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Informations générales</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="150">ID :</th>
                                            <td>${data.id}</td>
                                        </tr>
                                        <tr>
                                            <th>Date :</th>
                                            <td>${data.created_at}</td>
                                        </tr>
                                        <tr>
                                            <th>Événement :</th>
                                            <td><span class="badge bg-${data.event_color}">${data.event_label}</span></td>
                                        </tr>
                                        <tr>
                                            <th>Log Name :</th>
                                            <td>${data.log_name}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6>Contexte</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="150">Utilisateur :</th>
                                            <td>${data.causer_name || 'Système'}</td>
                                        </tr>
                                        <tr>
                                            <th>Objet :</th>
                                            <td>${data.subject_type || 'N/A'}</td>
                                        </tr>
                                        <tr>
                                            <th>IP :</th>
                                            <td>${data.ip_address || 'N/A'}</td>
                                        </tr>
                                        <tr>
                                            <th>User Agent :</th>
                                            <td><small>${data.user_agent || 'N/A'}</small></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <h6 class="mt-4">Propriétés</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <pre class="mb-0" style="max-height: 300px; overflow: auto;">${JSON.stringify(data.properties, null, 2)}</pre>
                                </div>
                            </div>
                            
                            <h6 class="mt-4">Modifications</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <pre class="mb-0" style="max-height: 200px; overflow: auto;">${JSON.stringify(data.changes, null, 2)}</pre>
                                </div>
                            </div>
                        `;
                        
                        content.innerHTML = html;
                        new bootstrap.Modal(modal).show();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Erreur lors du chargement des détails');
                    });
            }
            
            function changePerPage(select) {
                const url = new URL(window.location.href);
                url.searchParams.set('per_page', select.value);
                window.location.href = url.toString();
            }
            
            // Initialiser les tooltips
            document.addEventListener('DOMContentLoaded', function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
            
            // Auto-refresh toutes les 30 secondes si demandé
            <?php if(request('auto_refresh')): ?>
                setInterval(() => {
                    window.location.reload();
                }, 30000);
            <?php endif; ?>
        </script>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('styles'); ?>
        <style>
            .table-hover tbody tr:hover {
                background-color: rgba(0, 123, 255, 0.05);
            }
            .badge {
                font-size: 0.75em;
                padding: 0.35em 0.65em;
            }
            .page-item.active .page-link {
                background-color: #0d6efd;
                border-color: #0d6efd;
            }
            pre {
                background: #f8f9fa;
                border-radius: 4px;
                padding: 1rem;
                font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
                font-size: 0.85em;
            }
            .collapse-btn[aria-expanded="true"] .fa-chevron-down {
                transform: rotate(180deg);
            }
            .model-icon {
                width: 30px;
                height: 30px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 4px;
                margin-right: 8px;
            }
        </style>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/activity/index.blade.php ENDPATH**/ ?>