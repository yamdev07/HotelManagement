

<?php $__env->startSection('title', 'Rapports de Nettoyage'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>
                        Rapports de Nettoyage
                    </h1>
                    <p class="text-muted mb-0">Analyses et statistiques détaillées</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('housekeeping.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour
                    </a>
                    <a href="<?php echo e(route('housekeeping.daily-report')); ?>" class="btn btn-primary">
                        <i class="fas fa-file-alt me-2"></i>
                        Rapport Quotidien
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="<?php echo e(route('housekeeping.reports')); ?>" method="GET" id="reportFilters">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="date" class="form-label">Date</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" id="date" name="date" 
                                           value="<?php echo e($selectedDate->format('Y-m-d')); ?>">
                                    <button class="btn btn-outline-secondary" type="button" id="todayBtn">
                                        Aujourd'hui
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="employee" class="form-label">Femme de chambre</label>
                                <select class="form-select" id="employee" name="employee">
                                    <option value="">Toutes</option>
                                    <?php
                                        // Récupérer les utilisateurs avec le rôle Housekeeping
                                        $housekeepingStaff = \App\Models\User::where('role', 'Housekeeping')
                                            ->orWhere('role', 'housekeeping')
                                            ->get();
                                    ?>
                                    <?php $__currentLoopData = $housekeepingStaff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($employee->id); ?>" 
                                            <?php echo e(request('employee') == $employee->id ? 'selected' : ''); ?>>
                                            <?php echo e($employee->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="room_type" class="form-label">Type de chambre</label>
                                <select class="form-select" id="room_type" name="room_type">
                                    <option value="">Tous</option>
                                    <?php
                                        // Vérifier quel modèle de type de chambre existe
                                        $typeModel = null;
                                        if (class_exists('\App\Models\Type')) {
                                            $typeModel = '\App\Models\Type';
                                            $roomTypes = $typeModel::all();
                                        } elseif (class_exists('\App\Models\RoomType')) {
                                            $typeModel = '\App\Models\RoomType';
                                            $roomTypes = $typeModel::all();
                                        } else {
                                            $roomTypes = collect([]);
                                        }
                                    ?>
                                    
                                    <?php $__currentLoopData = $roomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($type->id); ?>" 
                                            <?php echo e(request('room_type') == $type->id ? 'selected' : ''); ?>>
                                            <?php echo e($type->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12 d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-2"></i>
                                    Filtrer
                                </button>
                                <a href="<?php echo e(route('housekeeping.reports')); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-redo me-2"></i>
                                    Réinitialiser
                                </a>
                                <button type="button" class="btn btn-success" onclick="exportToExcel()">
                                    <i class="fas fa-file-excel me-2"></i>
                                    Exporter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Chambres nettoyées</h6>
                            <h2 class="fw-bold text-primary"><?php echo e($stats['total_cleaned'] ?? 0); ?></h2>
                            <small class="text-muted"><?php echo e($selectedDate->format('d/m/Y')); ?></small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-bed fa-2x text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Temps moyen</h6>
                            <h2 class="fw-bold text-info"><?php echo e($stats['average_cleaning_time'] ?? 30); ?> min</h2>
                            <small class="text-muted">Par chambre</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Disponibles</h6>
                            <h2 class="fw-bold text-success">
                                <?php echo e($stats['cleaned_by_status'][\App\Models\Room::STATUS_AVAILABLE] ?? 0); ?>

                            </h2>
                            <small class="text-muted">Nettoyées et disponibles</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Occupées</h6>
                            <h2 class="fw-bold text-warning">
                                <?php echo e($stats['cleaned_by_status'][\App\Models\Room::STATUS_OCCUPIED] ?? 0); ?>

                            </h2>
                            <small class="text-muted">Nettoyées et occupées</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Liste détaillée -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-list me-2"></i>
                            <strong>Détail des chambres nettoyées</strong>
                        </div>
                        <span class="badge bg-light text-primary"><?php echo e($cleanedRooms->count()); ?> chambres</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if($cleanedRooms->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="cleaningReportTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Chambre</th>
                                        <th>Type</th>
                                        <th>Début</th>
                                        <th>Fin</th>
                                        <th>Durée</th>
                                        <th>Agent</th>
                                        <th>Statut</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $cleanedRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary"><?php echo e($room->number); ?></span>
                                        </td>
                                        <td><?php echo e($room->type->name ?? 'Standard'); ?></td>
                                        <td>
                                            <?php if($room->cleaning_started_at): ?>
                                                <?php echo e(\Carbon\Carbon::parse($room->cleaning_started_at)->format('H:i')); ?>

                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($room->cleaning_completed_at): ?>
                                                <?php echo e(\Carbon\Carbon::parse($room->cleaning_completed_at)->format('H:i')); ?>

                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($room->cleaning_started_at && $room->cleaning_completed_at): ?>
                                                <?php
                                                    $start = \Carbon\Carbon::parse($room->cleaning_started_at);
                                                    $end = \Carbon\Carbon::parse($room->cleaning_completed_at);
                                                    $duration = $start->diffInMinutes($end);
                                                    $color = $duration > 60 ? 'danger' : ($duration > 45 ? 'warning' : 'success');
                                                ?>
                                                <span class="badge bg-<?php echo e($color); ?>">
                                                    <?php echo e($duration); ?> min
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($room->cleaned_by): ?>
                                                <?php
                                                    $cleaner = \App\Models\User::find($room->cleaned_by);
                                                ?>
                                                <span class="badge bg-info">
                                                    <?php echo e($cleaner->name ?? 'Inconnu'); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">Auto</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                                $statusClass = $room->room_status_id == \App\Models\Room::STATUS_AVAILABLE ? 'success' : 
                                                              ($room->room_status_id == \App\Models\Room::STATUS_OCCUPIED ? 'info' : 
                                                              ($room->room_status_id == \App\Models\Room::STATUS_DIRTY ? 'danger' : 'warning'));
                                            ?>
                                            <span class="badge bg-<?php echo e($statusClass); ?>">
                                                <?php echo e($room->roomStatus->name ?? 'Inconnu'); ?>

                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-secondary" 
                                                        onclick="showRoomDetails(<?php echo e($room->id); ?>)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="<?php echo e(route('housekeeping.maintenance-form', $room)); ?>" 
                                                   class="btn btn-outline-warning">
                                                    <i class="fas fa-tools"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                            <h5 class="text-dark mb-2">Aucune donnée disponible</h5>
                            <p class="text-muted">Aucune chambre nettoyée à cette date</p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if($cleanedRooms->count() > 0): ?>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Durée de nettoyage: 
                                    <span class="text-success">≤45 min</span> | 
                                    <span class="text-warning">45-60 min</span> | 
                                    <span class="text-danger">≥60 min</span>
                                </small>
                            </div>
                            <div>
                                <small class="text-muted">
                                    Généré le <?php echo e(now()->format('d/m/Y H:i')); ?>

                                </small>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Graphiques et statistiques -->
        <div class="col-md-4">
            <!-- Répartition par agent -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-users me-2"></i>
                    <strong>Répartition par agent</strong>
                </div>
                <div class="card-body">
                    <?php if(isset($cleanedByUser) && $cleanedByUser->count() > 0): ?>
                        <canvas id="employeeChart" height="200"></canvas>
                        <div class="mt-3">
                            <table class="table table-sm">
                                <tbody>
                                    <?php $__currentLoopData = $cleanedByUser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($item['name']); ?></td>
                                        <td class="text-end"><?php echo e($item['count']); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-user-slash fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Aucune donnée disponible</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Dates disponibles -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <i class="fas fa-calendar me-2"></i>
                    <strong>Dates disponibles</strong>
                </div>
                <div class="card-body">
                    <?php if(isset($availableDates) && $availableDates->count() > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $availableDates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('housekeeping.reports', ['date' => $date])); ?>" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php echo e($selectedDate->format('Y-m-d') == $date ? 'active' : ''); ?>">
                                <?php echo e(\Carbon\Carbon::parse($date)->format('d/m/Y')); ?>

                                <span class="badge bg-primary rounded-pill">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Aucune date disponible</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Rapport mensuel -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-chart-line me-2"></i>
                    <strong>Tendances mensuelles</strong>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chart-line fa-2x me-3"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Rapports avancés</h6>
                                <p class="mb-0">
                                    Consultez les 
                                    <a href="<?php echo e(route('housekeeping.monthly-stats')); ?>" class="alert-link">
                                        statistiques mensuelles
                                    </a> 
                                    pour une analyse plus détaillée des performances.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="<?php echo e(route('housekeeping.monthly-stats')); ?>" class="btn btn-success">
                            <i class="fas fa-chart-bar me-2"></i>
                            Voir les statistiques mensuelles
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal détails chambre -->
<div class="modal fade" id="roomDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-door-closed me-2"></i>
                    Détails de la chambre
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="roomDetailsContent">
                <!-- Les détails seront chargés ici -->
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    .list-group-item.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }
    
    .list-group-item.active .badge {
        background-color: white !important;
        color: #0d6efd !important;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser le graphique des employés
    const employeeChart = document.getElementById('employeeChart');
    if (employeeChart) {
        try {
            const ctx = employeeChart.getContext('2d');
            
            // Données du graphique
            <?php if(isset($cleanedByUser) && $cleanedByUser->count() > 0): ?>
                const labels = <?php echo json_encode($cleanedByUser->pluck('name')); ?>;
                const data = <?php echo json_encode($cleanedByUser->pluck('count')); ?>;
                const backgroundColors = generateColors(data.length);
                
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: backgroundColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            }
                        }
                    }
                });
            <?php endif; ?>
        } catch (error) {
            console.error('Erreur lors de la création du graphique:', error);
        }
    }
    
    // Bouton "Aujourd'hui"
    document.getElementById('todayBtn').addEventListener('click', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date').value = today;
        document.getElementById('reportFilters').submit();
    });
    
    // Initialiser DataTable
    const table = document.getElementById('cleaningReportTable');
    if (table && typeof $.fn.DataTable !== 'undefined') {
        try {
            $(table).DataTable({
                pageLength: 25,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
                },
                dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip',
                order: [[0, 'asc']]
            });
        } catch (error) {
            console.error('Erreur lors de l\'initialisation de DataTable:', error);
        }
    }
});

// Générer des couleurs pour le graphique
function generateColors(count) {
    const colors = [
        '#4dc9f6', '#f67019', '#f53794', '#537bc4', '#acc236',
        '#166a8f', '#00a950', '#58595b', '#8549ba', '#ff6384',
        '#36a2eb', '#cc65fe', '#ffce56', '#4bc0c0', '#9966ff'
    ];
    return colors.slice(0, count);
}

// Exporter vers Excel
function exportToExcel() {
    const table = document.getElementById('cleaningReportTable');
    if (!table) {
        alert('Aucune donnée à exporter');
        return;
    }
    
    // Créer une copie du tableau pour l'export
    const clone = table.cloneNode(true);
    
    // Supprimer la colonne Actions
    const rows = clone.querySelectorAll('tr');
    rows.forEach(row => {
        if (row.cells.length > 0) {
            row.deleteCell(row.cells.length - 1);
        }
    });
    
    // Créer le contenu HTML
    const html = `
        <html>
            <head>
                <title>Export Rapport Nettoyage</title>
                <meta charset="UTF-8">
                <style>
                    table { border-collapse: collapse; width: 100%; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                </style>
            </head>
            <body>
                <h2>Rapport de nettoyage - <?php echo e($selectedDate->format('d/m/Y')); ?></h2>
                ${clone.outerHTML}
                <p><em>Généré le <?php echo e(now()->format('d/m/Y H:i')); ?></em></p>
            </body>
        </html>
    `;
    
    // Ouvrir dans une nouvelle fenêtre pour impression/export
    const printWindow = window.open('', '_blank');
    printWindow.document.write(html);
    printWindow.document.close();
    printWindow.focus();
    
    // Option d'impression
    setTimeout(() => {
        printWindow.print();
    }, 500);
}

// Afficher les détails d'une chambre
function showRoomDetails(roomId) {
    const content = document.getElementById('roomDetailsContent');
    const modal = new bootstrap.Modal(document.getElementById('roomDetailsModal'));
    
    // Afficher le chargement
    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-2 text-muted">Chargement des détails de la chambre...</p>
        </div>
    `;
    
    modal.show();
    
    // Simulation de chargement (à remplacer par un vrai appel AJAX)
    setTimeout(() => {
        content.innerHTML = `
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Les détails complets de la chambre seront disponibles prochainement.
                <br><small>ID de la chambre: ${roomId}</small>
            </div>
            <div class="text-center mt-3">
                <button class="btn btn-sm btn-primary" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-2"></i>
                    Rafraîchir
                </button>
            </div>
        `;
    }, 800);
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/housekeeping/reports.blade.php ENDPATH**/ ?>