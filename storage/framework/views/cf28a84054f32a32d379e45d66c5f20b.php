

<?php $__env->startSection('title', 'Rapports & Analytics'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">

    <!-- ================================================ -->
    <!-- PAGE HEADER -->
    <!-- ================================================ -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">📊 Rapports & Analytics</h3>
            <small class="text-muted">Performance • Finances • Opérations • Tendances</small>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Imprimer
            </button>
            <button class="btn btn-hotel-primary" id="exportPdf">
                <i class="fas fa-download me-2"></i>Export PDF
            </button>
        </div>
    </div>

    <!-- ================================================ -->
    <!-- FILTERS & DATE RANGE -->
    <!-- ================================================ -->
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('reports.index')); ?>" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Période</label>
                    <select name="period" class="form-select" id="periodSelect">
                        <option value="today" <?php echo e(request('period') == 'today' ? 'selected' : ''); ?>>Aujourd'hui</option>
                        <option value="yesterday" <?php echo e(request('period') == 'yesterday' ? 'selected' : ''); ?>>Hier</option>
                        <option value="week" <?php echo e(request('period') == 'week' ? 'selected' : ''); ?>>Cette semaine</option>
                        <option value="month" <?php echo e(request('period') == 'month' ? 'selected' : ''); ?>>Ce mois</option>
                        <option value="quarter" <?php echo e(request('period') == 'quarter' ? 'selected' : ''); ?>>Ce trimestre</option>
                        <option value="year" <?php echo e(request('period') == 'year' ? 'selected' : ''); ?>>Cette année</option>
                        <option value="custom" <?php echo e(request('period') == 'custom' ? 'selected' : ''); ?>>Personnalisé</option>
                    </select>
                </div>
                <div class="col-md-3" id="dateRangeStart" style="<?php echo e(request('period') == 'custom' ? '' : 'display: none;'); ?>">
                    <label class="form-label">Date début</label>
                    <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from', now()->startOfMonth()->format('Y-m-d'))); ?>">
                </div>
                <div class="col-md-3" id="dateRangeEnd" style="<?php echo e(request('period') == 'custom' ? '' : 'display: none;'); ?>">
                    <label class="form-label">Date fin</label>
                    <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to', now()->format('Y-m-d'))); ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-hotel-primary w-100">
                        <i class="fas fa-filter me-2"></i>Appliquer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================================================ -->
    <!-- KPI CARDS - ROW 1 (FINANCIAL) -->
    <!-- ================================================ -->
    <div class="row g-3 mb-4">

        <!-- Total Revenue -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-wallet fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Chiffre d'affaires</h6>
                        <h4 class="fw-bold"><?php echo e(number_format($totalRevenue ?? 0, 0, ',', ' ')); ?> CFA</h4>
                        <?php if(isset($revenueGrowth)): ?>
                        <small class="text-<?php echo e($revenueGrowth >= 0 ? 'success' : 'danger'); ?>">
                            <i class="fas fa-arrow-<?php echo e($revenueGrowth >= 0 ? 'up' : 'down'); ?> me-1"></i><?php echo e(abs($revenueGrowth)); ?>%
                        </small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Payments -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-success text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-credit-card fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Paiements encaissés</h6>
                        <h4 class="fw-bold"><?php echo e(number_format($totalPaymentsAmount ?? 0, 0, ',', ' ')); ?> CFA</h4>
                        <small class="text-muted"><?php echo e($paymentsCount ?? 0); ?> transactions</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Night Rate -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-info text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-moon fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Prix moyen / nuit</h6>
                        <h4 class="fw-bold"><?php echo e(number_format($averageNightRate ?? 0, 0, ',', ' ')); ?> CFA</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- RevPAR -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-warning text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-chart-line fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">RevPAR</h6>
                        <h4 class="fw-bold"><?php echo e(number_format($revPAR ?? 0, 0, ',', ' ')); ?> CFA</h4>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ================================================ -->
    <!-- KPI CARDS - ROW 2 (OPERATIONAL) -->
    <!-- ================================================ -->
    <div class="row g-3 mb-4">

        <!-- Occupancy Rate -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-secondary text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-bed fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Taux d'occupation</h6>
                        <h4 class="fw-bold"><?php echo e($occupancyRate ?? 0); ?>%</h4>
                        <small class="text-muted"><?php echo e($occupiedRooms ?? 0); ?>/<?php echo e($totalRooms ?? 0); ?> chambres</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Check-ins / Check-outs -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-dark text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-exchange-alt fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Arrivées / Départs</h6>
                        <h4 class="fw-bold"><?php echo e($checkinsCount ?? 0); ?> / <?php echo e($checkoutsCount ?? 0); ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Nights -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-purple text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-sun fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Nuitées vendues</h6>
                        <h4 class="fw-bold"><?php echo e($totalNights ?? 0); ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Stay -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-danger text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Séjour moyen</h6>
                        <h4 class="fw-bold"><?php echo e($averageStayLength ?? 0); ?> nuits</h4>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ================================================ -->
    <!-- CHARTS SECTION - ROW 1 -->
    <!-- ================================================ -->
    <div class="row g-4 mb-4">

        <!-- Revenue Chart -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">📈 Évolution du chiffre d'affaires</h6>
                    <span class="badge bg-primary"><?php echo e($periodLabel ?? 'Période sélectionnée'); ?></span>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Payment Methods Chart -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="fw-bold mb-0">💳 Répartition des paiements</h6>
                </div>
                <div class="card-body">
                    <canvas id="paymentChart" style="height: 250px;"></canvas>
                    <div class="mt-3 small">
                        <?php $__currentLoopData = $paymentSummary ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex justify-content-between mb-1">
                            <span><i class="<?php echo e($method['icon']); ?> me-1"></i><?php echo e($method['label']); ?></span>
                            <span class="fw-bold"><?php echo e(number_format($method['percentage'], 1)); ?>%</span>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ================================================ -->
    <!-- CHARTS SECTION - ROW 2 -->
    <!-- ================================================ -->
    <div class="row g-4 mb-4">

        <!-- Occupancy Chart -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="fw-bold mb-0">🏨 Occupation des chambres</h6>
                </div>
                <div class="card-body">
                    <canvas id="occupancyChart" style="height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Room Status Chart -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="fw-bold mb-0">🧹 Statut des chambres</h6>
                </div>
                <div class="card-body">
                    <canvas id="roomStatusChart" style="height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Checkout Times Chart -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="fw-bold mb-0">⏰ Horaires de départ</h6>
                </div>
                <div class="card-body">
                    <canvas id="checkoutTimesChart" style="height: 250px;"></canvas>
                </div>
            </div>
        </div>

    </div>

    <!-- ================================================ -->
    <!-- TABLES SECTION - TOP PERFORMERS -->
    <!-- ================================================ -->
    <div class="row g-4">

        <!-- Top Rooms -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 d-flex justify-content-between">
                    <h6 class="fw-bold mb-0">🏆 Top 5 chambres les plus rentables</h6>
                    <a href="<?php echo e(route('room.index')); ?>" class="text-decoration-none small">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Chambre</th>
                                    <th>Type</th>
                                    <th>Nuitées</th>
                                    <th class="text-end">Revenu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $topRooms ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <strong><?php echo e($room['number']); ?></strong>
                                        <small class="d-block text-muted"><?php echo e($room['name']); ?></small>
                                    </td>
                                    <td><?php echo e($room['type']); ?></td>
                                    <td><?php echo e($room['nights']); ?></td>
                                    <td class="text-end fw-bold"><?php echo e(number_format($room['revenue'], 0, ',', ' ')); ?> CFA</td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle me-2"></i>Aucune donnée disponible
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Customers -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 d-flex justify-content-between">
                    <h6 class="fw-bold mb-0">👥 Top 5 clients</h6>
                    <a href="<?php echo e(route('customer.index')); ?>" class="text-decoration-none small">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Client</th>
                                    <th>Séjours</th>
                                    <th>Nuitées</th>
                                    <th class="text-end">Dépenses totales</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $topCustomers ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <strong><?php echo e($customer['name']); ?></strong>
                                        <small class="d-block text-muted"><?php echo e($customer['email']); ?></small>
                                    </td>
                                    <td><?php echo e($customer['stays']); ?></td>
                                    <td><?php echo e($customer['nights']); ?></td>
                                    <td class="text-end fw-bold"><?php echo e(number_format($customer['spent'], 0, ',', ' ')); ?> CFA</td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle me-2"></i>Aucune donnée disponible
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ================================================ -->
    <!-- RECEPTIONIST PERFORMANCE TABLE -->
    <!-- ================================================ -->
    <div class="row g-4 mt-2">

        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 d-flex justify-content-between">
                    <h6 class="fw-bold mb-0">👤 Performance des réceptionnistes</h6>
                    <a href="<?php echo e(route('receptionist.stats')); ?>" class="text-decoration-none small">Détails</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Réceptionniste</th>
                                    <th>Sessions</th>
                                    <th>Check-ins</th>
                                    <th>Check-outs</th>
                                    <th>Réservations</th>
                                    <th class="text-end">Encaissé</th>
                                    <th class="text-center">Productivité</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $receptionistPerformance ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <strong><?php echo e($recep['name']); ?></strong>
                                    </td>
                                    <td><?php echo e($recep['sessions']); ?></td>
                                    <td><?php echo e($recep['checkins']); ?></td>
                                    <td><?php echo e($recep['checkouts']); ?></td>
                                    <td><?php echo e($recep['reservations']); ?></td>
                                    <td class="text-end fw-bold"><?php echo e(number_format($recep['total'], 0, ',', ' ')); ?> CFA</td>
                                    <td class="text-center">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                style="width: <?php echo e($recep['productivity']); ?>%;" 
                                                aria-valuenow="<?php echo e($recep['productivity']); ?>" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                                <?php echo e($recep['productivity']); ?>%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle me-2"></i>Aucune donnée disponible
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ================================================ -->
    <!-- PAYMENT SUMMARY TABLE -->
    <!-- ================================================ -->
    <div class="row g-4 mt-2">

        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 d-flex justify-content-between">
                    <h6 class="fw-bold mb-0">💰 Synthèse détaillée des paiements</h6>
                    <a href="<?php echo e(route('payment.index')); ?>" class="text-decoration-none small">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Méthode</th>
                                    <th class="text-end">Montant</th>
                                    <th class="text-end">%</th>
                                    <th class="text-end">Transactions</th>
                                    <th class="text-end">Moyenne par transaction</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $paymentSummary ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <i class="<?php echo e($method['icon']); ?> me-2"></i><?php echo e($method['label']); ?>

                                    </td>
                                    <td class="text-end fw-bold"><?php echo e(number_format($method['amount'], 0, ',', ' ')); ?> CFA</td>
                                    <td class="text-end"><?php echo e(number_format($method['percentage'], 1)); ?>%</td>
                                    <td class="text-end"><?php echo e($method['count']); ?></td>
                                    <td class="text-end"><?php echo e(number_format($method['average'], 0, ',', ' ')); ?> CFA</td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot class="bg-light fw-bold">
                                <tr>
                                    <td>TOTAL</td>
                                    <td class="text-end"><?php echo e(number_format($totalPaymentsAmount ?? 0, 0, ',', ' ')); ?> CFA</td>
                                    <td class="text-end">100%</td>
                                    <td class="text-end"><?php echo e($paymentsCount ?? 0); ?></td>
                                    <td class="text-end"><?php echo e(number_format($averagePayment ?? 0, 0, ',', ' ')); ?> CFA</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ================================================ -->
    <!-- RECENT TRANSACTIONS -->
    <!-- ================================================ -->
    <div class="row g-4 mt-2">

        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 d-flex justify-content-between">
                    <h6 class="fw-bold mb-0">🔄 Dernières transactions</h6>
                    <a href="<?php echo e(route('transaction.index')); ?>" class="text-decoration-none small">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Chambre</th>
                                    <th>Arrivée</th>
                                    <th>Départ</th>
                                    <th class="text-end">Montant</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $recentTransactions ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>#<?php echo e($transaction->id); ?></td>
                                    <td><?php echo e($transaction->customer->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($transaction->room->number ?? 'N/A'); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y')); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y')); ?></td>
                                    <td class="text-end fw-bold"><?php echo e(number_format($transaction->total_price, 0, ',', ' ')); ?> CFA</td>
                                    <td>
                                        <?php
                                            $statusColors = [
                                                'reservation' => 'info',
                                                'active' => 'success',
                                                'completed' => 'secondary',
                                                'cancelled' => 'danger',
                                                'no_show' => 'warning'
                                            ];
                                        ?>
                                        <span class="badge bg-<?php echo e($statusColors[$transaction->status] ?? 'secondary'); ?>">
                                            <?php echo e($transaction->status); ?>

                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle me-2"></i>Aucune transaction récente
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    // ================================================
    // DATE RANGE TOGGLE
    // ================================================
    document.getElementById('periodSelect').addEventListener('change', function() {
        const isCustom = this.value === 'custom';
        document.getElementById('dateRangeStart').style.display = isCustom ? 'block' : 'none';
        document.getElementById('dateRangeEnd').style.display = isCustom ? 'block' : 'none';
    });

    // ================================================
    // CHART DATA FROM CONTROLLER
    // ================================================
    const revenueData = <?php echo json_encode($revenueChartData ?? []); ?>;
    const revenueLabels = <?php echo json_encode($revenueChartLabels ?? []); ?>;
    
    const paymentData = <?php echo json_encode($paymentChartData ?? []); ?>;
    const paymentLabels = <?php echo json_encode($paymentChartLabels ?? []); ?>;
    
    const occupied = <?php echo e($occupiedRooms ?? 0); ?>;
    const available = <?php echo e($availableRooms ?? 0); ?>;
    
    const roomStatusData = <?php echo json_encode($roomStatusData ?? []); ?>;
    const roomStatusLabels = <?php echo json_encode($roomStatusLabels ?? []); ?>;
    
    const checkoutTimesData = <?php echo json_encode($checkoutTimesData ?? [0, 0, 0]); ?>;

    // ================================================
    // CHART 1: REVENUE LINE CHART
    // ================================================
    if (document.getElementById('revenueChart')) {
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Chiffre d\'affaires (CFA)',
                    data: revenueData,
                    borderWidth: 3,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#0d6efd',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw.toLocaleString() + ' CFA';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' CFA';
                            }
                        }
                    }
                }
            }
        });
    }

    // ================================================
    // CHART 2: PAYMENT METHODS DOUGHNUT
    // ================================================
    if (document.getElementById('paymentChart')) {
        new Chart(document.getElementById('paymentChart'), {
            type: 'doughnut',
            data: {
                labels: paymentLabels,
                datasets: [{
                    data: paymentData,
                    backgroundColor: [
                        '#28a745', // cash - green
                        '#dc3545', // card - red
                        '#17a2b8', // mobile - cyan
                        '#ffc107', // transfer - yellow
                        '#6f42c1', // fedapay - purple
                        '#fd7e14', // check - orange
                        '#6c757d'  // other - gray
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${label}: ${value.toLocaleString()} CFA (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }

    // ================================================
    // CHART 3: OCCUPANCY PIE CHART
    // ================================================
    if (document.getElementById('occupancyChart')) {
        new Chart(document.getElementById('occupancyChart'), {
            type: 'doughnut',
            data: {
                labels: ['Occupées', 'Disponibles'],
                datasets: [{
                    data: [occupied, available],
                    backgroundColor: ['#dc3545', '#28a745'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = occupied + available;
                                let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${label}: ${value} chambres (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }

    // ================================================
    // CHART 4: ROOM STATUS CHART
    // ================================================
    if (document.getElementById('roomStatusChart')) {
        new Chart(document.getElementById('roomStatusChart'), {
            type: 'bar',
            data: {
                labels: roomStatusLabels,
                datasets: [{
                    label: 'Nombre de chambres',
                    data: roomStatusData,
                    backgroundColor: [
                        '#28a745', // disponible - green
                        '#dc3545', // occupée - red
                        '#ffc107', // maintenance - yellow
                        '#17a2b8', // réservée - cyan
                        '#6f42c1', // nettoyage - purple
                        '#6c757d'  // sale - gray
                    ],
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }

    // ================================================
    // CHART 5: CHECKOUT TIMES CHART
    // ================================================
    if (document.getElementById('checkoutTimesChart')) {
        new Chart(document.getElementById('checkoutTimesChart'), {
            type: 'pie',
            data: {
                labels: ['Avant 12h', '12h-14h (largesse)', 'Après 14h (late checkout)'],
                datasets: [{
                    data: checkoutTimesData,
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    // ================================================
    // EXPORT PDF FUNCTION
    // ================================================
    document.getElementById('exportPdf')?.addEventListener('click', function() {
        const element = document.querySelector('.container-fluid');
        const opt = {
            margin: [0.5, 0.5, 0.5, 0.5],
            filename: 'rapport-hotel-<?php echo e(now()->format('Y-m-d')); ?>.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, letterRendering: true },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save();
    });

</script>

<style>
    .bg-purple { background-color: #6f42c1; }
    .badge { font-size: 0.8rem; padding: 0.35rem 0.65rem; }
    .card { transition: transform 0.2s; }
    .card:hover { transform: translateY(-2px); }
    .table th { font-weight: 600; color: #495057; }
    .btn-hotel-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
    }
    .btn-hotel-primary:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        color: white;
    }
    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
    }
    .progress-bar {
        border-radius: 10px;
        font-size: 0.75rem;
        line-height: 20px;
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/reports/index.blade.php ENDPATH**/ ?>