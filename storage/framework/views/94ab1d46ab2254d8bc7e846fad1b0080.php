
<?php $__env->startSection('title', 'Prolonger la Réservation #' . $transaction->id); ?>
<?php $__env->startSection('content'); ?>

<style>
/* ═══════════════════════════════════════════════════════════════
   STYLES TRANSACTION EXTEND - Design moderne cohérent
═══════════════════════════════════════════════════════════════════ */
:root {
    --primary: #2563eb;
    --primary-light: #3b82f6;
    --primary-soft: rgba(37, 99, 235, 0.08);
    --success: #10b981;
    --success-light: rgba(16, 185, 129, 0.08);
    --warning: #f59e0b;
    --warning-light: rgba(245, 158, 11, 0.08);
    --danger: #ef4444;
    --danger-light: rgba(239, 68, 68, 0.08);
    --info: #3b82f6;
    --info-light: rgba(59, 130, 246, 0.08);
    --dark: #1e293b;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1e293b;
    --radius: 12px;
    --shadow: 0 4px 20px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.05);
    --shadow-hover: 0 10px 30px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
    --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ────────── CARTE PRINCIPALE ────────── */
.extend-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
    overflow: hidden;
    transition: var(--transition);
    margin-bottom: 20px;
}
.extend-card:hover {
    box-shadow: var(--shadow-hover);
    border-color: var(--gray-300);
}

.extend-card .card-header {
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
    padding: 16px 20px;
}
.extend-card .card-header h5 {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--gray-800);
}
.extend-card .card-header h5 i {
    color: var(--primary);
    font-size: 1rem;
}

.extend-card .card-body {
    padding: 24px;
}

/* ────────── SÉLECTION DE NUITS ────────── */
.night-option {
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    padding: 16px;
    cursor: pointer;
    transition: var(--transition);
    background: white;
    text-align: center;
}
.night-option:hover {
    border-color: var(--primary);
    background: var(--primary-soft);
    transform: translateY(-2px);
}
.night-option.selected {
    border-color: var(--success);
    background: var(--success-light);
    box-shadow: 0 4px 10px rgba(16, 185, 129, 0.1);
}
.night-option .h4 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-800);
    margin-bottom: 4px;
}
.night-option small {
    color: var(--gray-500);
    font-size: 0.8rem;
    font-weight: 500;
}
.night-option.selected .h4 {
    color: var(--success);
}

/* ────────── PRÉVISUALISATION ────────── */
.date-preview {
    background: var(--gray-50);
    border-radius: 8px;
    padding: 20px;
    border: 1px solid var(--gray-200);
}
.date-preview h6 {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 16px;
}
.preview-number {
    font-size: 1.8rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 4px;
}
.preview-number.primary { color: var(--primary); }
.preview-number.success { color: var(--success); }
.preview-label {
    font-size: 0.7rem;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* ────────── DÉTAILS DU PRIX ────────── */
.price-breakdown {
    background: var(--primary-soft);
    border-left: 4px solid var(--primary);
    border-radius: 8px;
    padding: 20px;
    border: 1px solid rgba(37, 99, 235, 0.1);
}
.price-breakdown h6 {
    color: var(--primary);
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 16px;
}
.price-label {
    font-size: 0.75rem;
    color: var(--gray-600);
    margin-bottom: 4px;
}
.price-value {
    font-size: 1.2rem;
    font-weight: 700;
}
.price-value.primary { color: var(--primary); }
.price-value.success { color: var(--success); }

/* ────────── BOUTONS ────────── */
.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 500;
    border: 1px solid transparent;
    transition: var(--transition);
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
}
.btn-modern:hover {
    transform: translateY(-2px);
    text-decoration: none;
}
.btn-primary-modern {
    background: var(--primary);
    color: white;
}
.btn-primary-modern:hover {
    background: var(--primary-light);
    color: white;
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.2);
}
.btn-outline-modern {
    background: transparent;
    color: var(--gray-700);
    border: 1px solid var(--gray-300);
}
.btn-outline-modern:hover {
    background: var(--gray-50);
    border-color: var(--gray-400);
    color: var(--gray-800);
}

/* ────────── SIDEBAR ────────── */
.info-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
    overflow: hidden;
    margin-bottom: 20px;
}
.info-card .card-header {
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
    padding: 14px 16px;
}
.info-card .card-header h5, 
.info-card .card-header h6 {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.info-card .card-body {
    padding: 16px;
}
.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid var(--gray-100);
}
.info-item:last-child {
    border-bottom: none;
}
.info-label {
    font-size: 0.8rem;
    color: var(--gray-600);
    display: flex;
    align-items: center;
    gap: 6px;
}
.info-value {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--gray-800);
}

/* ────────── CARTE IMPORTANT ────────── */
.important-card {
    border: 1px solid rgba(245, 158, 11, 0.2);
    background: var(--warning-light);
}
.important-card .card-header {
    background: rgba(245, 158, 11, 0.1);
    border-bottom: 1px solid rgba(245, 158, 11, 0.2);
    color: #b45309;
}
.important-card .card-header h6 i {
    color: #b45309;
}
.important-card .card-body ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.important-card .card-body li {
    padding: 6px 0;
    font-size: 0.8rem;
    color: var(--gray-700);
    display: flex;
    align-items: center;
    gap: 8px;
}
.important-card .card-body li i {
    color: #b45309;
    font-size: 0.7rem;
}

/* ────────── BREADCRUMB ────────── */
.breadcrumb-modern {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    color: var(--gray-500);
    margin-bottom: 16px;
}
.breadcrumb-modern a {
    color: var(--gray-500);
    text-decoration: none;
}
.breadcrumb-modern a:hover {
    color: var(--primary);
}
.breadcrumb-modern .sep {
    color: var(--gray-300);
    font-size: 0.7rem;
}

/* ────────── FORMULAIRES ────────── */
.form-control-modern {
    border: 1px solid var(--gray-200);
    border-radius: 6px;
    padding: 8px 12px;
    font-size: 0.9rem;
    transition: var(--transition);
}
.form-control-modern:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 2px var(--primary-soft);
}
.input-group-modern {
    display: flex;
    align-items: center;
}
.input-group-modern .input-group-text {
    background: var(--gray-100);
    border: 1px solid var(--gray-200);
    border-left: none;
    border-radius: 0 6px 6px 0;
    padding: 8px 12px;
    color: var(--gray-600);
}
</style>

<div class="container-fluid px-4 py-3">
    <!-- Breadcrumb -->
    <div class="breadcrumb-modern">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home fa-xs me-1"></i>Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="<?php echo e(route('transaction.index')); ?>">Réservations</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="<?php echo e(route('transaction.show', $transaction)); ?>">#<?php echo e($transaction->id); ?></a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span style="color: var(--gray-700); font-weight: 500;">Prolonger</span>
    </div>

    <!-- En-tête -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1" style="color: var(--gray-800); font-weight: 700;">
                <i class="fas fa-calendar-plus me-2" style="color: var(--primary);"></i>
                Prolonger la Réservation #<?php echo e($transaction->id); ?>

            </h2>
            <p class="text-muted small mb-0">Ajoutez des nuits supplémentaires au séjour du client</p>
        </div>
        
        <a href="<?php echo e(route('transaction.show', $transaction)); ?>" class="btn-modern btn-outline-modern">
            <i class="fas fa-arrow-left me-1"></i>Retour
        </a>
    </div>

    <!-- Messages de session -->
    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> <?php echo session('success'); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if(session('error') || session('failed')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> <?php echo session('error') ?? session('failed'); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="extend-card">
                <div class="card-header">
                    <h5><i class="fas fa-calendar-alt me-2"></i>Prolonger le séjour</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('transaction.extend.process', $transaction)); ?>" id="extend-form">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Informations actuelles -->
                        <div class="alert alert-info d-flex align-items-center gap-3 mb-4" style="background: var(--info-light); border: 1px solid rgba(59, 130, 246, 0.1); color: #1e40af;">
                            <i class="fas fa-info-circle fa-2x"></i>
                            <div>
                                <strong>Séjour actuel</strong>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <small>Client</small>
                                        <p class="mb-1"><strong><?php echo e($transaction->customer->name); ?></strong></p>
                                    </div>
                                    <div class="col-md-6">
                                        <small>Chambre</small>
                                        <p class="mb-1"><strong>Chambre <?php echo e($transaction->room->number); ?></strong></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <small>Arrivée</small>
                                        <p class="mb-1"><strong><?php echo e(\Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y')); ?></strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <small>Départ actuel</small>
                                        <p class="mb-1"><strong><?php echo e(\Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y')); ?></strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <small>Nuits actuelles</small>
                                        <p class="mb-1"><strong><?php echo e(\Carbon\Carbon::parse($transaction->check_in)->diffInDays($transaction->check_out)); ?></strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sélection du nombre de nuits supplémentaires -->
                        <div class="mb-4">
                            <h6 style="font-size: 0.9rem; font-weight: 600; color: var(--gray-700); margin-bottom: 16px; border-bottom: 1px solid var(--gray-200); padding-bottom: 8px;">
                                <i class="fas fa-moon me-2" style="color: var(--primary);"></i>Nombre de nuits supplémentaires
                            </h6>
                            
                            <div class="row g-3 mb-3" id="nights-options">
                                <?php $__currentLoopData = [1, 2, 3, 7]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $night): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-3">
                                    <div class="night-option" data-nights="<?php echo e($night); ?>" onclick="selectNights(<?php echo e($night); ?>)">
                                        <div class="h4"><?php echo e($night); ?></div>
                                        <small><?php echo e($night == 1 ? 'Nuit' : 'Nuits'); ?></small>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            
                            <!-- Sélection personnalisée -->
                            <div class="mt-4">
                                <label for="additional_nights" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--gray-700);">
                                    <i class="fas fa-sliders-h me-1" style="color: var(--primary);"></i>Nombre personnalisé
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control" 
                                           id="additional_nights" 
                                           name="additional_nights" 
                                           min="1" 
                                           max="30" 
                                           value="<?php echo e(old('additional_nights', 1)); ?>"
                                           required
                                           onchange="updatePreview()"
                                           style="border-radius: 6px 0 0 6px; border: 1px solid var(--gray-200); padding: 8px 12px;">
                                    <span class="input-group-text" style="background: var(--gray-100); border: 1px solid var(--gray-200); border-left: none; border-radius: 0 6px 6px 0; color: var(--gray-600);">nuit(s)</span>
                                </div>
                                <?php $__errorArgs = ['additional_nights'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted" style="font-size: 0.7rem;">Maximum 30 nuits supplémentaires</small>
                            </div>
                        </div>

                        <!-- Nouvelle date de départ -->
                        <div class="mb-4">
                            <label for="new_check_out" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--gray-700);">
                                <i class="fas fa-calendar-day me-1" style="color: var(--primary);"></i>Nouvelle date de départ
                            </label>
                            <div class="input-group">
                                <input type="date" 
                                       class="form-control" 
                                       id="new_check_out" 
                                       name="new_check_out" 
                                       value="<?php echo e(old('new_check_out', $suggestedDate->format('Y-m-d'))); ?>"
                                       required
                                       onchange="updateNightsFromDate()"
                                       style="border-radius: 6px 0 0 6px; border: 1px solid var(--gray-200); padding: 8px 12px;">
                                <span class="input-group-text" style="background: var(--gray-100); border: 1px solid var(--gray-200); border-left: none; border-radius: 0 6px 6px 0; color: var(--gray-600);">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                            <?php $__errorArgs = ['new_check_out'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted" style="font-size: 0.7rem;">
                                Départ actuel : <?php echo e(\Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y')); ?>

                            </small>
                        </div>

                        <!-- Prévisualisation -->
                        <div class="date-preview mb-4">
                            <h6><i class="fas fa-eye me-2" style="color: var(--primary);"></i>Prévisualisation</h6>
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="preview-number"><?php echo e(\Carbon\Carbon::parse($transaction->check_in)->diffInDays($transaction->check_out)); ?></div>
                                    <div class="preview-label">Nuits actuelles</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="preview-number primary" id="additional-nights-preview">0</div>
                                    <div class="preview-label">Nuits supplémentaires</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="preview-number success" id="total-nights">0</div>
                                    <div class="preview-label">Total nuits</div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="preview-label">Nouvelle date de départ</div>
                                    <div class="h5 mb-0" id="new-check-out-preview">-</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="preview-label">Disponibilité</div>
                                    <div id="availability-status">
                                        <span class="badge" style="background: var(--gray-200); color: var(--gray-600);">Non vérifié</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Détails du prix -->
                        <div class="price-breakdown mb-4">
                            <h6><i class="fas fa-calculator me-2"></i>Détails du prix</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="price-label">Prix par nuit</div>
                                    <div class="price-value primary"><?php echo e(number_format($transaction->room->price, 0, ',', ' ')); ?> CFA</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="price-label">Total actuel</div>
                                    <div class="price-value"><?php echo e(number_format($transaction->getTotalPrice(), 0, ',', ' ')); ?> CFA</div>
                                </div>
                            </div>
                            <div class="row mt-3 pt-3" style="border-top: 1px solid rgba(37, 99, 235, 0.1);">
                                <div class="col-md-6">
                                    <div class="price-label">Supplément</div>
                                    <div class="price-value primary" id="additional-price">0 CFA</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="price-label">Nouveau total</div>
                                    <div class="price-value success" id="new-total-price">0 CFA</div>
                                </div>
                            </div>
                        </div>

                        <!-- Vérification de disponibilité -->
                        <div class="mb-4">
                            <button type="button" id="check-availability-btn" class="btn-modern btn-outline-modern">
                                <i class="fas fa-search me-1"></i>Vérifier disponibilité
                            </button>
                            <div id="availability-result" class="mt-2"></div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--gray-700);">
                                <i class="fas fa-sticky-note me-1" style="color: var(--primary);"></i>Notes (optionnel)
                            </label>
                            <textarea class="form-control" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3"
                                      style="border: 1px solid var(--gray-200); border-radius: 6px; padding: 8px 12px;"
                                      placeholder="Raison de la prolongation, instructions spéciales..."><?php echo e(old('notes')); ?></textarea>
                            <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo e(route('transaction.show', $transaction)); ?>" class="btn-modern btn-outline-modern">
                                <i class="fas fa-times me-1"></i>Annuler
                            </a>
                            <button type="submit" class="btn-modern btn-primary-modern" id="extend-btn">
                                <i class="fas fa-calendar-plus me-1"></i>Confirmer la prolongation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Résumé -->
            <div class="info-card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle" style="color: var(--primary);"></i>Résumé</h5>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-user"></i>Client</span>
                        <span class="info-value"><?php echo e($transaction->customer->name); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-bed"></i>Chambre</span>
                        <span class="info-value"><?php echo e($transaction->room->number); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-calendar-check"></i>Arrivée</span>
                        <span class="info-value"><?php echo e(\Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y')); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-calendar-times"></i>Départ actuel</span>
                        <span class="info-value"><?php echo e(\Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y')); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-money-bill"></i>Prix/nuit</span>
                        <span class="info-value"><?php echo e(number_format($transaction->room->price, 0, ',', ' ')); ?> CFA</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-chart-line"></i>Statut</span>
                        <span class="info-value">
                            <span class="badge" style="background: <?php echo e($transaction->status == 'active' ? 'var(--success-light)' : 'var(--warning-light)'); ?>; color: <?php echo e($transaction->status == 'active' ? '#047857' : '#b45309'); ?>;">
                                <?php echo e($transaction->status == 'active' ? 'Dans l\'hôtel' : 'Réservation'); ?>

                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Important -->
            <div class="info-card important-card">
                <div class="card-header">
                    <h6><i class="fas fa-exclamation-triangle"></i>Important</h6>
                </div>
                <div class="card-body">
                    <ul>
                        <li><i class="fas fa-check-circle"></i> La prolongation prend effet immédiatement</li>
                        <li><i class="fas fa-check-circle"></i> Le supplément sera ajouté au total</li>
                        <li><i class="fas fa-check-circle"></i> Vérifiez la disponibilité de la chambre</li>
                        <li><i class="fas fa-check-circle"></i> Le client sera notifié</li>
                        <li><i class="fas fa-check-circle"></i> Toute modification est enregistrée</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pricePerNight = <?php echo e($transaction->room->price); ?>;
    const currentCheckOut = "<?php echo e($transaction->check_out->format('Y-m-d')); ?>";
    const currentNights = <?php echo e(\Carbon\Carbon::parse($transaction->check_in)->diffInDays($transaction->check_out)); ?>;
    const currentTotal = <?php echo e($transaction->getTotalPrice()); ?>;
    
    // Formater le prix
    function formatPrice(price) {
        return new Intl.NumberFormat('fr-FR').format(price) + ' CFA';
    }
    
    // Mettre à jour la prévisualisation
    function updatePreview() {
        const additionalNights = parseInt(document.getElementById('additional_nights').value) || 0;
        const newCheckOutInput = document.getElementById('new_check_out');
        
        // Calculer la nouvelle date de départ
        const currentCheckOutDate = new Date(currentCheckOut);
        const newCheckOutDate = new Date(currentCheckOutDate);
        newCheckOutDate.setDate(newCheckOutDate.getDate() + additionalNights);
        
        // Mettre à jour le champ date
        const formattedDate = newCheckOutDate.toISOString().split('T')[0];
        newCheckOutInput.value = formattedDate;
        
        // Mettre à jour l'affichage
        document.getElementById('additional-nights-preview').textContent = additionalNights;
        document.getElementById('total-nights').textContent = currentNights + additionalNights;
        document.getElementById('new-check-out-preview').textContent = 
            newCheckOutDate.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' });
        
        // Calculer les prix
        const additionalPrice = additionalNights * pricePerNight;
        const newTotalPrice = currentTotal + additionalPrice;
        
        document.getElementById('additional-price').textContent = formatPrice(additionalPrice);
        document.getElementById('new-total-price').textContent = formatPrice(newTotalPrice);
        
        // Mettre à jour les options sélectionnées
        document.querySelectorAll('.night-option').forEach(option => {
            const optionNights = parseInt(option.getAttribute('data-nights'));
            if (optionNights === additionalNights) {
                option.classList.add('selected');
            } else {
                option.classList.remove('selected');
            }
        });
    }
    
    // Mettre à jour le nombre de nuits à partir de la date
    function updateNightsFromDate() {
        const newCheckOut = document.getElementById('new_check_out').value;
        if (!newCheckOut) return;
        
        const currentCheckOutDate = new Date(currentCheckOut);
        const newCheckOutDate = new Date(newCheckOut);
        
        // Calculer la différence en jours
        const timeDiff = newCheckOutDate.getTime() - currentCheckOutDate.getTime();
        const additionalNights = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
        if (additionalNights > 0) {
            document.getElementById('additional_nights').value = additionalNights;
            updatePreview();
        }
    }
    
    // Sélectionner une option de nuits
    window.selectNights = function(nights) {
        document.getElementById('additional_nights').value = nights;
        updatePreview();
    };
    
    // Vérifier la disponibilité
    async function checkAvailability() {
        const newCheckOut = document.getElementById('new_check_out').value;
        const transactionId = <?php echo e($transaction->id); ?>;
        
        if (!newCheckOut) {
            alert('Veuillez d\'abord sélectionner une date de départ');
            return;
        }
        
        if (new Date(newCheckOut) <= new Date(currentCheckOut)) {
            alert('La nouvelle date de départ doit être après la date actuelle');
            return;
        }
        
        try {
            // Afficher chargement
            const checkBtn = document.getElementById('check-availability-btn');
            const originalText = checkBtn.innerHTML;
            checkBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Vérification...';
            checkBtn.disabled = true;
            
            const response = await fetch(`/transaction/${transactionId}/check-availability`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    check_in: "<?php echo e($transaction->check_in->format('Y-m-d')); ?>",
                    check_out: newCheckOut,
                    transaction_id: transactionId
                })
            });
            
            const data = await response.json();
            
            // Restaurer le bouton
            checkBtn.innerHTML = originalText;
            checkBtn.disabled = false;
            
            // Afficher le résultat
            const resultDiv = document.getElementById('availability-result');
            const statusBadge = document.getElementById('availability-status');
            
            if (data.available) {
                resultDiv.innerHTML = `
                    <div class="alert alert-success py-2" style="background: var(--success-light); border: 1px solid rgba(16, 185, 129, 0.2); color: #047857;">
                        <i class="fas fa-check-circle me-1"></i> Disponible !
                    </div>
                `;
                statusBadge.innerHTML = '<span class="badge" style="background: var(--success); color: white;">Disponible ✓</span>';
            } else {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger py-2" style="background: var(--danger-light); border: 1px solid rgba(239, 68, 68, 0.2); color: #b91c1c;">
                        <i class="fas fa-times-circle me-1"></i> Non disponible !
                    </div>
                `;
                statusBadge.innerHTML = '<span class="badge" style="background: var(--danger); color: white;">Non disponible ✗</span>';
            }
            
        } catch (error) {
            console.error('Erreur:', error);
            document.getElementById('availability-result').innerHTML = `
                <div class="alert alert-warning py-2" style="background: var(--warning-light); border: 1px solid rgba(245, 158, 11, 0.2); color: #b45309;">
                    <i class="fas fa-exclamation-triangle me-1"></i> Erreur lors de la vérification
                </div>
            `;
            
            const checkBtn = document.getElementById('check-availability-btn');
            checkBtn.innerHTML = '<i class="fas fa-search me-1"></i>Vérifier disponibilité';
            checkBtn.disabled = false;
        }
    }
    
    // Attacher l'événement au bouton de vérification
    document.getElementById('check-availability-btn').addEventListener('click', checkAvailability);
    
    // Validation du formulaire
    document.getElementById('extend-form').addEventListener('submit', function(e) {
        const additionalNights = parseInt(document.getElementById('additional_nights').value) || 0;
        const newCheckOut = document.getElementById('new_check_out').value;
        
        if (additionalNights < 1 || additionalNights > 30) {
            e.preventDefault();
            alert('Le nombre de nuits supplémentaires doit être entre 1 et 30');
            return false;
        }
        
        if (new Date(newCheckOut) <= new Date(currentCheckOut)) {
            e.preventDefault();
            alert('La nouvelle date de départ doit être après la date actuelle');
            return false;
        }
        
        // Désactiver le bouton pour éviter double soumission
        const extendBtn = document.getElementById('extend-btn');
        extendBtn.disabled = true;
        extendBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Traitement...';
        
        return true;
    });
    
    // Initialiser
    updatePreview();
    
    // Définir la date minimale pour le nouveau départ
    const currentDate = new Date(currentCheckOut);
    currentDate.setDate(currentDate.getDate() + 1);
    document.getElementById('new_check_out').min = currentDate.toISOString().split('T')[0];
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/transaction/extend.blade.php ENDPATH**/ ?>