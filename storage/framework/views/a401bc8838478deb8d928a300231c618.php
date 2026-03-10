

<?php $__env->startSection('title', 'Rapport Quotidien - ' . $today->format('d/m/Y')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark">
                        <i class="fas fa-file-alt me-2 text-primary"></i>
                        Rapport Quotidien
                    </h1>
                    <p class="text-muted mb-0">Activités de nettoyage du <?php echo e($today->format('d/m/Y')); ?></p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('housekeeping.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour
                    </a>
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>
                        Imprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques du jour -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Chambres nettoyées</h6>
                    <h1 class="fw-bold text-success display-5"><?php echo e($stats['cleaned_today']); ?></h1>
                    <small class="text-muted">
                        <i class="fas fa-check-circle me-1"></i>
                        Aujourd'hui
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Restant à nettoyer</h6>
                    <h1 class="fw-bold text-danger display-5"><?php echo e($stats['to_clean']); ?></h1>
                    <small class="text-muted">
                        <i class="fas fa-broom me-1"></i>
                        En attente
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Taux d'achèvement</h6>
                    <h1 class="fw-bold text-info display-5">
                        <?php if($stats['cleaned_today'] + $stats['to_clean'] > 0): ?>
                            <?php echo e(round(($stats['cleaned_today'] / ($stats['cleaned_today'] + $stats['to_clean'])) * 100)); ?>%
                        <?php else: ?>
                            100%
                        <?php endif; ?>
                    </h1>
                    <small class="text-muted">
                        <i class="fas fa-chart-line me-1"></i>
                        Progression
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Heure moyenne</h6>
                    <h1 class="fw-bold text-purple display-5">25m</h1>
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        Par chambre
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chambres nettoyées aujourd'hui -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white d-print-block">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Chambres nettoyées aujourd'hui (<?php echo e($cleanedToday->count()); ?>)</strong>
                        </div>
                        <span class="badge bg-light text-success">
                            <?php echo e($today->format('d/m/Y')); ?>

                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if($cleanedToday->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Chambre</th>
                                        <th>Type</th>
                                        <th>Nettoyée à</th>
                                        <th>Durée</th>
                                        <th>Femme de chambre</th>
                                        <th>Statut actuel</th>
                                        <th class="d-print-none">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $cleanedToday; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-success"><?php echo e($room->number); ?></span>
                                        </td>
                                        <td><?php echo e($room->type->name ?? 'Standard'); ?></td>
                                        <td>
                                            <?php if($room->last_cleaned_at): ?>
                                                <?php echo e($room->last_cleaned_at->format('H:i')); ?>

                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($room->cleaning_started_at && $room->cleaning_completed_at): ?>
                                                <?php echo e($room->cleaning_started_at->diffInMinutes($room->cleaning_completed_at)); ?> min
                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($room->cleaned_by): ?>
                                                <span class="badge bg-info">
                                                    <?php echo e(\App\Models\User::find($room->cleaned_by)->name ?? 'Inconnu'); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">Inconnu</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e($room->room_status_id == \App\Models\Room::STATUS_AVAILABLE ? 'success' : ($room->room_status_id == \App\Models\Room::STATUS_OCCUPIED ? 'info' : 'warning')); ?>">
                                                <?php echo e($room->roomStatus->name ?? 'Inconnu'); ?>

                                            </span>
                                        </td>
                                        <td class="d-print-none">
                                            <div class="btn-group btn-group-sm">
                                                <a href="#" class="btn btn-outline-secondary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button class="btn btn-outline-danger">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-dark mb-2">Aucune chambre nettoyée aujourd'hui</h5>
                            <p class="text-muted">Commencez par nettoyer les chambres à nettoyer</p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if($cleanedToday->count() > 0): ?>
                    <div class="card-footer bg-light d-print-none">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <?php echo e($cleanedToday->count()); ?> chambres nettoyées
                                </small>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">
                                    Dernière mise à jour: <?php echo e(now()->format('H:i')); ?>

                                </small>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Vue d'ensemble -->
        <div class="col-md-4">
            <!-- Performances par femme de chambre -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-chart-bar me-2"></i>
                    <strong>Performance par agent</strong>
                </div>
                <div class="card-body">
                    <?php if(count($stats['cleaned_by_user']) > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $stats['cleaned_by_user']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userId => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $user = \App\Models\User::find($userId); ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center" 
                                             style="width: 32px; height: 32px;">
                                            <?php echo e(substr($user->name ?? '?', 0, 1)); ?>

                                        </div>
                                        <span class="ms-2"><?php echo e($user->name ?? 'Inconnu'); ?></span>
                                    </div>
                                    <span class="badge bg-info rounded-pill"><?php echo e($count); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-user-slash fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Aucune donnée disponible</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Chambres à nettoyer -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-danger text-white">
                    <i class="fas fa-broom me-2"></i>
                    <strong>Chambres restantes (<?php echo e($toClean->count()); ?>)</strong>
                </div>
                <div class="card-body">
                    <?php if($toClean->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Chambre</th>
                                        <th>Priorité</th>
                                        <th class="d-print-none">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $toClean->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-danger"><?php echo e($room->number); ?></span>
                                        </td>
                                        <td>
                                            <?php if($room->activeTransactions->where('check_out', '<=', now())->count() > 0): ?>
                                                <span class="badge bg-warning">Haute</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Normale</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="d-print-none">
                                            <form action="<?php echo e(route('housekeeping.start-cleaning', $room->id)); ?>" 
                                                  method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if($toClean->count() > 8): ?>
                            <div class="text-center mt-2">
                                <a href="<?php echo e(route('housekeeping.to-clean')); ?>" class="btn btn-sm btn-outline-danger">
                                    Voir les <?php echo e($toClean->count() - 8); ?> autres
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-trophy fa-2x text-success mb-2"></i>
                            <p class="text-success mb-0 fw-bold">Tout est nettoyé !</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes et commentaires -->
    <div class="row mt-4 d-print-none">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <i class="fas fa-sticky-note me-2"></i>
                    <strong>Notes du jour</strong>
                </div>
                <div class="card-body">
                    <form id="dailyNotesForm">
                        <div class="mb-3">
                            <label for="shift_notes" class="form-label">Observations générales</label>
                            <textarea class="form-control" id="shift_notes" rows="3" 
                                      placeholder="Notes sur le déroulement de la journée, problèmes rencontrés, suggestions..."></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="issues_encountered" class="form-label">Problèmes rencontrés</label>
                                    <textarea class="form-control" id="issues_encountered" rows="2" 
                                              placeholder="Problèmes techniques, manque de matériel..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="suggestions" class="form-label">Suggestions d'amélioration</label>
                                    <textarea class="form-control" id="suggestions" rows="2" 
                                              placeholder="Idées pour améliorer l'efficacité..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary">
                                <i class="fas fa-save me-2"></i>
                                Enregistrer les notes
                            </button>
                            <button type="button" class="btn btn-primary" onclick="generateSummary()">
                                <i class="fas fa-file-pdf me-2"></i>
                                Générer PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @media print {
        .d-print-none {
            display: none !important;
        }
        
        .card {
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
        }
        
        .card-header {
            background-color: #f8f9fa !important;
            color: #212529 !important;
            border-bottom: 1px solid #dee2e6 !important;
        }
        
        .badge {
            border: 1px solid #dee2e6;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function generateSummary() {
    // Récupérer les données du rapport
    const summary = {
        date: "<?php echo e($today->format('d/m/Y')); ?>",
        cleaned: <?php echo e($stats['cleaned_today']); ?>,
        remaining: <?php echo e($stats['to_clean']); ?>,
        completionRate: <?php echo e($stats['cleaned_today'] + $stats['to_clean'] > 0 ? round(($stats['cleaned_today'] / ($stats['cleaned_today'] + $stats['to_clean'])) * 100) : 100); ?>,
        notes: document.getElementById('shift_notes')?.value || '',
        issues: document.getElementById('issues_encountered')?.value || '',
        suggestions: document.getElementById('suggestions')?.value || ''
    };
    
    // Générer le contenu PDF (simplifié)
    const content = `
        <html>
            <head>
                <title>Rapport Quotidien - ${summary.date}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    h1 { color: #333; }
                    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                </style>
            </head>
            <body>
                <h1>Rapport Quotidien - ${summary.date}</h1>
                <p>Généré le: ${new Date().toLocaleString()}</p>
                
                <h2>Statistiques</h2>
                <ul>
                    <li>Chambres nettoyées: ${summary.cleaned}</li>
                    <li>Chambres restantes: ${summary.remaining}</li>
                    <li>Taux d'achèvement: ${summary.completionRate}%</li>
                </ul>
                
                <h2>Notes</h2>
                <p>${summary.notes || 'Aucune note'}</p>
                
                <h2>Problèmes</h2>
                <p>${summary.issues || 'Aucun problème rapporté'}</p>
                
                <h2>Suggestions</h2>
                <p>${summary.suggestions || 'Aucune suggestion'}</p>
            </body>
        </html>
    `;
    
    // Ouvrir une nouvelle fenêtre pour impression
    const printWindow = window.open('', '_blank');
    printWindow.document.write(content);
    printWindow.document.close();
    printWindow.print();
}

// Mettre à jour l'heure automatiquement
function updateClock() {
    const clockElement = document.querySelector('.clock');
    if (clockElement) {
        clockElement.textContent = new Date().toLocaleTimeString();
    }
}

setInterval(updateClock, 60000); // Toutes les minutes

// Initialiser
document.addEventListener('DOMContentLoaded', function() {
    updateClock();
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/housekeeping/daily-report.blade.php ENDPATH**/ ?>