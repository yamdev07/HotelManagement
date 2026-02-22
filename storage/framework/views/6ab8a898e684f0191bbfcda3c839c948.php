
<?php $__env->startSection('title', 'Modifier Réservation'); ?>
<?php $__env->startSection('content'); ?>

<style>
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

/* Carte principale */
.transaction-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
    overflow: hidden;
    transition: var(--transition);
    margin-bottom: 24px;
}
.transaction-card:hover {
    box-shadow: var(--shadow-hover);
    border-color: var(--gray-300);
}

/* En-tête de carte */
.transaction-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
    background: white;
    border-bottom: 1px solid var(--gray-200);
    flex-wrap: wrap;
    gap: 16px;
}
.transaction-card-header h5 {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-800);
    letter-spacing: -0.01em;
}
.transaction-card-header h5 i {
    color: var(--primary);
    font-size: 1.1rem;
}

/* Badges statut */
.badge-statut {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    line-height: 1;
    white-space: nowrap;
    gap: 4px;
    border: none;
    cursor: pointer;
    transition: var(--transition);
}
.badge-statut:hover {
    transform: translateY(-1px);
    filter: brightness(0.95);
}
.badge-reservation {
    background: var(--warning-light);
    color: #b45309;
    border: 1px solid rgba(245, 158, 11, 0.15);
}
.badge-active {
    background: var(--success-light);
    color: #047857;
    border: 1px solid rgba(16, 185, 129, 0.15);
}
.badge-completed {
    background: var(--info-light);
    color: #1e40af;
    border: 1px solid rgba(37, 99, 235, 0.15);
}
.badge-cancelled {
    background: var(--danger-light);
    color: #b91c1c;
    border: 1px solid rgba(239, 68, 68, 0.15);
}
.badge-no_show {
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1px solid var(--gray-200);
}

/* Badge chambre */
.room-badge {
    background: var(--gray-100);
    color: var(--gray-700);
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 0.8rem;
    display: inline-block;
    border: 1px solid var(--gray-200);
}
.room-badge i {
    margin-right: 4px;
    font-size: 0.7rem;
    color: var(--gray-500);
}

/* Prix */
.price {
    font-weight: 600;
    font-family: 'Inter', monospace;
    font-size: 0.9rem;
}
.price-positive { color: var(--gray-800); }
.price-success { color: var(--success); }
.price-danger { color: var(--danger); font-weight: 700; }
.price-small { font-size: 0.7rem; font-weight: 400; color: var(--gray-500); }

/* Nuits badge */
.nights-badge {
    background: var(--gray-100);
    color: var(--gray-600);
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 0.7rem;
    white-space: nowrap;
    border: 1px solid var(--gray-200);
}

/* Boutons */
.btn-primary-custom {
    background: var(--primary);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.85rem;
    transition: var(--transition);
}
.btn-primary-custom:hover {
    background: var(--primary-light);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.2);
}
.btn-outline-custom {
    background: transparent;
    color: var(--gray-700);
    border: 1px solid var(--gray-300);
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.85rem;
    transition: var(--transition);
}
.btn-outline-custom:hover {
    background: var(--gray-50);
    border-color: var(--gray-400);
    transform: translateY(-2px);
}
.btn-danger-custom {
    background: var(--danger);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.85rem;
    transition: var(--transition);
}
.btn-danger-custom:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(239, 68, 68, 0.2);
}

/* Date picker */
.date-picker-container {
    position: relative;
}
.date-picker-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: var(--gray-500);
}
.datetime-wrapper {
    display: flex;
    gap: 10px;
    align-items: center;
}
.datetime-date {
    flex: 1;
}
.fixed-time-badge {
    background-color: var(--gray-100);
    border-radius: 6px;
    padding: 8px 12px;
    font-size: 0.85rem;
    color: var(--gray-700);
    display: inline-block;
    border: 1px solid var(--gray-200);
    white-space: nowrap;
}

/* Alertes */
.alert-status {
    border-left: 4px solid;
    padding: 12px 16px;
    border-radius: 8px;
    background: white;
    margin-bottom: 20px;
}
.alert-status-reservation { border-left-color: var(--warning); background: var(--warning-light); }
.alert-status-active { border-left-color: var(--success); background: var(--success-light); }
.alert-status-completed { border-left-color: var(--info); background: var(--info-light); }
.alert-status-cancelled { border-left-color: var(--danger); background: var(--danger-light); }
.alert-status-no_show { border-left-color: var(--gray-500); background: var(--gray-100); }

/* Nights counter */
.nights-counter {
    background: var(--gray-50);
    border-radius: 8px;
    padding: 16px;
    margin-top: 16px;
    border: 1px solid var(--gray-200);
}

/* Info bulle */
.info-badge {
    background: var(--info-light);
    color: var(--info);
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
</style>

<div class="container-fluid px-4 py-3">

    <!-- En-tête avec fil d'Ariane -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard.index')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('transaction.index')); ?>">Réservations</a></li>
                    <li class="breadcrumb-item active">Modifier #<?php echo e($transaction->id); ?></li>
                </ol>
            </nav>
            <h2 class="h5 mb-0" style="color: var(--gray-800); font-weight: 700;">
                <i class="fas fa-edit me-2" style="color: var(--primary);"></i>
                Modifier la Réservation #<?php echo e($transaction->id); ?>

            </h2>
            <p class="text-muted small mb-0">Client: <?php echo e($transaction->customer->name); ?></p>
        </div>
        
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('transaction.show', $transaction)); ?>" class="btn btn-outline-custom">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
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
        <i class="fas fa-exclamation-circle me-2"></i> <?php echo e(session('error') ?? session('failed')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Alertes selon le statut -->
    <?php
        $now = \Carbon\Carbon::now();
        $checkInDateTime = \Carbon\Carbon::parse($transaction->check_in)->setTime(12, 0, 0);
        $checkOutDateTime = \Carbon\Carbon::parse($transaction->check_out)->setTime(12, 0, 0);
        $nightsPassed = 0;
        if ($transaction->status == 'active') {
            $nightsPassed = $checkInDateTime->diffInDays($now) > 0 ? $checkInDateTime->diffInDays($now) : 0;
        }
    ?>

    <?php if($transaction->status == 'cancelled'): ?>
    <div class="alert alert-danger">
        <i class="fas fa-ban me-2"></i>
        <strong>Réservation annulée</strong> - Cette réservation ne peut plus être modifiée.
        <?php if($transaction->cancelled_at): ?>
        <br><small>Annulée le <?php echo e(\Carbon\Carbon::parse($transaction->cancelled_at)->format('d/m/Y H:i')); ?></small>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if($transaction->status == 'no_show'): ?>
    <div class="alert alert-secondary">
        <i class="fas fa-user-slash me-2"></i>
        <strong>No Show</strong> - Le client ne s'est pas présenté.
    </div>
    <?php endif; ?>

    <?php if($transaction->status == 'completed'): ?>
    <div class="alert alert-info">
        <i class="fas fa-check-circle me-2"></i>
        <strong>Séjour terminé</strong> - Le client est parti.
    </div>
    <?php endif; ?>

    <div class="row">
        <!-- Colonne principale - Formulaire -->
        <div class="col-lg-8">
            <div class="transaction-card">
                <div class="transaction-card-header">
                    <h5><i class="fas fa-edit"></i> Modifier la réservation</h5>
                    <span class="badge-statut badge-<?php echo e($transaction->status); ?>">
                        <?php if($transaction->status == 'reservation'): ?> 📅
                        <?php elseif($transaction->status == 'active'): ?> 🏨
                        <?php elseif($transaction->status == 'completed'): ?> ✅
                        <?php elseif($transaction->status == 'cancelled'): ?> ❌
                        <?php else: ?> 👤
                        <?php endif; ?>
                        <?php echo e($transaction->status_label); ?>

                    </span>
                </div>
                <div class="p-4">
                    <?php if(in_array($transaction->status, ['cancelled', 'no_show', 'completed'])): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Cette réservation est <?php echo e($transaction->status_label); ?>. Les modifications sont limitées.
                    </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('transaction.update', $transaction)); ?>" id="editForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <!-- Client (non modifiable) -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-user me-2"></i>Client
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small">Nom</label>
                                    <input type="text" class="form-control" value="<?php echo e($transaction->customer->name); ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small">Téléphone</label>
                                    <input type="text" class="form-control" value="<?php echo e($transaction->customer->phone ?? 'Non renseigné'); ?>" readonly>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label class="form-label text-muted small">Email</label>
                                    <input type="text" class="form-control" value="<?php echo e($transaction->customer->email ?? 'Non renseigné'); ?>" readonly>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label class="form-label text-muted small">Actions</label>
                                    <div>
                                        <a href="<?php echo e(route('customer.show', $transaction->customer)); ?>" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye me-1"></i>Voir profil
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ============ SECTION CHAMBRE MODIFIABLE ============ -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-bed me-2"></i>Chambre
                                <?php if($transaction->status == 'reservation'): ?>
                                    <span class="info-badge ms-2">
                                        <i class="fas fa-exchange-alt"></i> Changeable
                                    </span>
                                <?php endif; ?>
                            </h6>

                            <?php if($transaction->status == 'reservation' && isset($availableRooms)): ?>
                                <!-- Sélection de chambre pour les réservations -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold">Sélectionner une chambre *</label>
                                        <select name="room_id" id="roomSelect" class="form-control <?php $__errorArgs = ['room_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="">-- Choisir une chambre --</option>
                                            
                                            <!-- Chambre actuelle -->
                                            <?php $currentRoom = $transaction->room; ?>
                                            <?php if($currentRoom): ?>
                                            <optgroup label="Chambre actuelle">
                                                <option value="<?php echo e($currentRoom->id); ?>" selected>
                                                    🏠 Chambre <?php echo e($currentRoom->number); ?> - <?php echo e($currentRoom->type->name ?? 'Standard'); ?> 
                                                    (<?php echo e(number_format($currentRoom->price, 0, ',', ' ')); ?> CFA/nuit)
                                                </option>
                                            </optgroup>
                                            <?php endif; ?>

                                            <!-- Autres chambres disponibles -->
                                            <?php if(isset($availableRooms) && $availableRooms->where('id', '!=', $currentRoom->id)->count() > 0): ?>
                                            <optgroup label="Chambres disponibles">
                                                <?php $__currentLoopData = $availableRooms->where('id', '!=', $currentRoom->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($room->id); ?>" data-price="<?php echo e($room->price); ?>">
                                                    ✅ Chambre <?php echo e($room->number); ?> - <?php echo e($room->type->name ?? 'Standard'); ?> 
                                                    (<?php echo e(number_format($room->price, 0, ',', ' ')); ?> CFA/nuit)
                                                </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </optgroup>
                                            <?php endif; ?>

                                            <!-- Chambres occupées (affichage seulement) -->
                                            <?php if(isset($occupiedRooms) && $occupiedRooms->count() > 0): ?>
                                            <optgroup label="Chambres occupées (non disponibles)">
                                                <?php $__currentLoopData = $occupiedRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($room->id); ?>" disabled class="text-muted">
                                                    ❌ Chambre <?php echo e($room->number); ?> - <?php echo e($room->type->name ?? 'Standard'); ?> 
                                                    (Occupée pour ces dates)
                                                </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </optgroup>
                                            <?php endif; ?>
                                        </select>

                                        <!-- Informations sur les prix si changement -->
                                        <div id="roomPriceInfo" class="mt-2 alert alert-info" style="display: none;">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <span id="priceChangeMessage"></span>
                                        </div>

                                        <?php $__errorArgs = ['room_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Seules les chambres disponibles pour les dates sélectionnées sont affichées.
                                        </small>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Affichage seule pour les autres statuts -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small">Numéro</label>
                                        <div class="room-badge">
                                            <i class="fas fa-door-closed"></i> <?php echo e($transaction->room->number); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small">Type</label>
                                        <div><?php echo e($transaction->room->type->name ?? 'Standard'); ?></div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small">Prix/nuit</label>
                                        <div class="price price-positive"><?php echo e(number_format($transaction->room->price, 0, ',', ' ')); ?> CFA</div>
                                    </div>
                                </div>
                                <input type="hidden" name="room_id" value="<?php echo e($transaction->room_id); ?>">
                            <?php endif; ?>
                        </div>

                        <!-- Dates du séjour -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-calendar-alt me-2"></i>Dates du séjour
                                <span class="info-badge ms-2">
                                    <i class="fas fa-clock"></i> Heures fixes 12h-12h
                                </span>
                            </h6>

                            <!-- Affichage des nuits passées (pour séjour en cours) -->
                            <?php if($transaction->status == 'active' && $nightsPassed > 0): ?>
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Nuits déjà passées :</strong> <?php echo e($nightsPassed); ?> nuit<?php echo e($nightsPassed > 1 ? 's' : ''); ?>

                                (depuis le <?php echo e($checkInDateTime->format('d/m/Y')); ?>)
                            </div>
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Date d'arrivée *</label>
                                    <div class="datetime-wrapper">
                                        <div class="datetime-date date-picker-container">
                                            <input type="date" 
                                                   class="form-control <?php $__errorArgs = ['check_in_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="check_in_date" 
                                                   name="check_in_date" 
                                                   value="<?php echo e(old('check_in_date', $checkInDateTime->format('Y-m-d'))); ?>"
                                                   <?php echo e(in_array($transaction->status, ['cancelled', 'no_show', 'completed']) ? 'readonly' : ''); ?>

                                                   required>
                                            <span class="date-picker-icon"><i class="fas fa-calendar"></i></span>
                                            <?php $__errorArgs = ['check_in_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div class="fixed-time-badge">
                                            <i class="fas fa-clock me-1"></i>12:00
                                        </div>
                                    </div>
                                    <small class="text-muted">Heure fixe : 12h00</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Date de départ *</label>
                                    <div class="datetime-wrapper">
                                        <div class="datetime-date date-picker-container">
                                            <input type="date" 
                                                   class="form-control <?php $__errorArgs = ['check_out_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="check_out_date" 
                                                   name="check_out_date" 
                                                   value="<?php echo e(old('check_out_date', $checkOutDateTime->format('Y-m-d'))); ?>"
                                                   <?php echo e(in_array($transaction->status, ['cancelled', 'no_show', 'completed']) ? 'readonly' : ''); ?>

                                                   required>
                                            <span class="date-picker-icon"><i class="fas fa-calendar"></i></span>
                                            <?php $__errorArgs = ['check_out_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div class="fixed-time-badge">
                                            <i class="fas fa-clock me-1"></i>12:00
                                        </div>
                                    </div>
                                    <small class="text-muted">Heure fixe : 12h00 (largesse jusqu'à 14h)</small>
                                </div>
                            </div>

                            <!-- Champs cachés pour les heures -->
                            <input type="hidden" name="check_in_time" value="12:00">
                            <input type="hidden" name="check_out_time" value="12:00">
                            <input type="hidden" id="check_in" name="check_in">
                            <input type="hidden" id="check_out" name="check_out">

                            <!-- Calculateur de nuits -->
                            <div class="nights-counter">
                                <div class="row">
                                    <div class="col-md-3">
                                        <span class="text-muted small">Nuits</span>
                                        <div id="nights-count" class="h5 mb-0 text-primary">0</div>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="text-muted small">Prix total</span>
                                        <div id="new-total" class="h5 mb-0 text-success">0 CFA</div>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="text-muted small">Déjà payé</span>
                                        <div class="h5 mb-0"><?php echo e(number_format($transaction->getTotalPayment(), 0, ',', ' ')); ?> CFA</div>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="text-muted small">Différence</span>
                                        <div id="price-difference" class="h5 mb-0">0 CFA</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bouton vérification disponibilité -->
                            <?php if(!in_array($transaction->status, ['cancelled', 'no_show', 'completed'])): ?>
                            <div class="mt-3">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="checkAvailability">
                                    <i class="fas fa-search me-1"></i> Vérifier disponibilité
                                </button>
                                <div id="availabilityResult" class="mt-2"></div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Statut (avec conditions) -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-exchange-alt me-2"></i>Statut
                            </h6>

                            <?php
                                $isFullyPaid = $transaction->getRemainingPayment() <= 0;
                                $canChangeStatus = in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']) 
                                                    && !in_array($transaction->status, ['cancelled', 'no_show']);
                            ?>

                            <?php if($canChangeStatus): ?>
                            <div class="mb-3">
                                <label class="form-label">Modifier le statut</label>
                                <select name="status" id="statusSelect" class="form-control">
                                    <option value="reservation" <?php echo e($transaction->status == 'reservation' ? 'selected' : ''); ?> 
                                        <?php echo e($transaction->status == 'completed' ? 'disabled' : ''); ?>>
                                        📅 Réservation
                                    </option>
                                    <option value="active" <?php echo e($transaction->status == 'active' ? 'selected' : ''); ?>

                                        <?php echo e($transaction->status == 'completed' ? 'disabled' : ''); ?>>
                                        🏨 Dans l'hôtel
                                    </option>
                                    <option value="completed" <?php echo e($transaction->status == 'completed' ? 'selected' : ''); ?>

                                        <?php echo e(!$isFullyPaid ? 'disabled' : ''); ?>

                                        data-requires-payment="true">
                                        ✅ Terminé <?php echo e(!$isFullyPaid ? '(paiement incomplet)' : ''); ?>

                                    </option>
                                    <option value="cancelled" <?php echo e($transaction->status == 'cancelled' ? 'selected' : ''); ?>>
                                        ❌ Annulée
                                    </option>
                                    <option value="no_show" <?php echo e($transaction->status == 'no_show' ? 'selected' : ''); ?>>
                                        👤 No Show
                                    </option>
                                </select>
                                <small class="text-muted" id="statusHelp">
                                    <?php if($transaction->status == 'active' && !$isFullyPaid): ?>
                                        ⚠️ Paiement incomplet - Le client doit solder avant départ
                                    <?php endif; ?>
                                </small>
                            </div>

                            <!-- Champ raison d'annulation (caché par défaut) -->
                            <div id="cancelReasonField" style="display: none;" class="mb-3">
                                <label class="form-label">Raison de l'annulation</label>
                                <textarea name="cancel_reason" class="form-control" rows="2" 
                                          placeholder="Pourquoi annuler ?"><?php echo e(old('cancel_reason', $transaction->cancel_reason)); ?></textarea>
                            </div>
                            <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Statut actuel : <strong><?php echo e($transaction->status_label); ?></strong>
                                <?php if($transaction->status == 'cancelled' && $transaction->cancel_reason): ?>
                                <br><small>Raison : <?php echo e($transaction->cancel_reason); ?></small>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3" 
                                      placeholder="Ajouter des notes..."><?php echo e(old('notes', $transaction->notes)); ?></textarea>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-custom" id="cancelEditBtn">
                                <i class="fas fa-times me-2"></i>Annuler
                            </button>
                            <button type="submit" class="btn btn-primary-custom" id="saveBtn">
                                <i class="fas fa-save me-2"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Colonne latérale - Infos et actions -->
        <div class="col-lg-4">
            <!-- Résumé -->
            <div class="transaction-card mb-4">
                <div class="transaction-card-header">
                    <h5><i class="fas fa-info-circle"></i> Résumé</h5>
                </div>
                <div class="p-4">
                    <table class="table table-sm">
                        <tr>
                            <td class="text-muted">ID Réservation</td>
                            <td class="text-end fw-bold">#<?php echo e($transaction->id); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Client</td>
                            <td class="text-end"><?php echo e($transaction->customer->name); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Chambre</td>
                            <td class="text-end"><?php echo e($transaction->room->number); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Arrivée</td>
                            <td class="text-end"><?php echo e($checkInDateTime->format('d/m/Y')); ?> <small>12:00</small></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Départ</td>
                            <td class="text-end"><?php echo e($checkOutDateTime->format('d/m/Y')); ?> <small>12:00</small></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nuits</td>
                            <td class="text-end"><?php echo e($checkInDateTime->diffInDays($checkOutDateTime)); ?></td>
                        </tr>
                        <?php if($transaction->status == 'active' && $nightsPassed > 0): ?>
                        <tr>
                            <td class="text-muted">Nuits passées</td>
                            <td class="text-end text-warning fw-bold"><?php echo e($nightsPassed); ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td class="text-muted">Total</td>
                            <td class="text-end fw-bold"><?php echo e(number_format($transaction->getTotalPrice(), 0, ',', ' ')); ?> CFA</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Payé</td>
                            <td class="text-end text-success"><?php echo e(number_format($transaction->getTotalPayment(), 0, ',', ' ')); ?> CFA</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Reste</td>
                            <td class="text-end <?php echo e($transaction->getRemainingPayment() > 0 ? 'text-danger fw-bold' : ''); ?>">
                                <?php echo e(number_format($transaction->getRemainingPayment(), 0, ',', ' ')); ?> CFA
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Créée le</td>
                            <td class="text-end"><?php echo e($transaction->created_at->format('d/m/Y H:i')); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="transaction-card mb-4">
                <div class="transaction-card-header">
                    <h5><i class="fas fa-bolt"></i> Actions rapides</h5>
                </div>
                <div class="p-4">
                    <div class="d-grid gap-2">
                        <?php if($transaction->status == 'reservation' && in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist'])): ?>
                        <form action="<?php echo e(route('transaction.mark-arrived', $transaction)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-success w-100" 
                                    onclick="return confirm('Confirmer l\'arrivée ?')">
                                <i class="fas fa-sign-in-alt me-2"></i>Marquer arrivé
                            </button>
                        </form>
                        <?php endif; ?>

                        <?php if($transaction->status == 'active' && in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist'])): ?>
                            <?php if($now->gte($checkOutDateTime) && $now->lte($checkOutDateTime->copy()->setTime(14,0,0))): ?>
                            <form action="<?php echo e(route('transaction.mark-departed', $transaction)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-info w-100">
                                    <i class="fas fa-sign-out-alt me-2"></i>Départ (largesse)
                                </button>
                            </form>
                            <?php elseif($now->gt($checkOutDateTime->copy()->setTime(14,0,0))): ?>
                            <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#overrideModal">
                                <i class="fas fa-gavel me-2"></i>Dérogation départ
                            </button>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if($transaction->getRemainingPayment() > 0 && !in_array($transaction->status, ['cancelled', 'no_show', 'completed'])): ?>
                        <a href="<?php echo e(route('transaction.payment.create', $transaction)); ?>" class="btn btn-outline-success w-100">
                            <i class="fas fa-money-bill-wave me-2"></i>Ajouter paiement
                        </a>
                        <?php endif; ?>

                        <a href="<?php echo e(route('transaction.show', $transaction)); ?>" class="btn btn-outline-primary w-100">
                            <i class="fas fa-eye me-2"></i>Voir détails
                        </a>
                    </div>
                </div>
            </div>

            <!-- Historique -->
            <div class="transaction-card">
                <div class="transaction-card-header">
                    <h5><i class="fas fa-history"></i> Dernières modifications</h5>
                </div>
                <div class="p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 pb-2 border-bottom">
                            <small class="text-muted">Création</small><br>
                            <strong><?php echo e($transaction->created_at->format('d/m/Y H:i')); ?></strong>
                        </li>
                        <li class="mb-2 pb-2 border-bottom">
                            <small class="text-muted">Dernière modif</small><br>
                            <strong><?php echo e($transaction->updated_at->format('d/m/Y H:i')); ?></strong>
                        </li>
                        <?php if($transaction->cancelled_at): ?>
                        <li class="mb-2">
                            <small class="text-muted">Annulation</small><br>
                            <strong class="text-danger"><?php echo e($transaction->cancelled_at->format('d/m/Y H:i')); ?></strong>
                        </li>
                        <?php endif; ?>
                    </ul>
                    <a href="<?php echo e(route('transaction.history', $transaction)); ?>" class="btn btn-sm btn-outline-dark mt-3 w-100">
                        <i class="fas fa-history me-1"></i> Voir tout l'historique
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal dérogation après 14h -->
<div class="modal fade" id="overrideModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Dérogation départ après 14h
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('transaction.mark-departed', $transaction)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir autoriser ce départ après 14h ?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-clock me-2"></i>
                        Départ prévu : <?php echo e($checkOutDateTime->format('d/m/Y')); ?> à 12h00<br>
                        Heure actuelle : <?php echo e($now->format('H:i')); ?>

                    </div>
                    <div class="mb-3">
                        <label class="form-label">Raison de la dérogation :</label>
                        <textarea name="override_reason" class="form-control" rows="2" 
                                  placeholder="Pourquoi fermer les yeux ?" required></textarea>
                    </div>
                    <input type="hidden" name="override" value="1">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-check me-2"></i>Autoriser le départ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============ ÉLÉMENTS ============
    const checkInDate = document.getElementById('check_in_date');
    const checkOutDate = document.getElementById('check_out_date');
    const checkInHidden = document.getElementById('check_in');
    const checkOutHidden = document.getElementById('check_out');
    const nightsCount = document.getElementById('nights-count');
    const newTotal = document.getElementById('new-total');
    const priceDiff = document.getElementById('price-difference');
    const statusSelect = document.getElementById('statusSelect');
    const cancelReasonField = document.getElementById('cancelReasonField');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const saveBtn = document.getElementById('saveBtn');
    const checkAvailabilityBtn = document.getElementById('checkAvailability');
    const roomSelect = document.getElementById('roomSelect');
    const roomPriceInfo = document.getElementById('roomPriceInfo');
    const priceChangeMessage = document.getElementById('priceChangeMessage');
    
    const currentRoomPrice = <?php echo e($transaction->room->price); ?>;
    const originalTotal = <?php echo e($transaction->getTotalPrice()); ?>;
    const transactionId = <?php echo e($transaction->id); ?>;

    // ============ FONCTIONS ============
    function combineDateTime(dateInput) {
        return dateInput.value ? `${dateInput.value}T12:00` : null;
    }

    function calculateNights(selectedRoomPrice = null) {
        const checkIn = combineDateTime(checkInDate);
        const checkOut = combineDateTime(checkOutDate);
        const pricePerNight = selectedRoomPrice || currentRoomPrice;
        
        if (checkInHidden) checkInHidden.value = checkIn;
        if (checkOutHidden) checkOutHidden.value = checkOut;
        
        if (checkIn && checkOut) {
            const start = new Date(checkIn);
            const end = new Date(checkOut);
            
            if (end > start) {
                const diffTime = end - start;
                const nights = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const total = nights * pricePerNight;
                const difference = total - originalTotal;
                
                nightsCount.textContent = nights;
                newTotal.textContent = new Intl.NumberFormat('fr-FR').format(total) + ' CFA';
                
                if (difference > 0) {
                    priceDiff.innerHTML = `<span class="text-danger">+${new Intl.NumberFormat('fr-FR').format(difference)} CFA</span>`;
                } else if (difference < 0) {
                    priceDiff.innerHTML = `<span class="text-success">${new Intl.NumberFormat('fr-FR').format(difference)} CFA</span>`;
                } else {
                    priceDiff.innerHTML = '0 CFA';
                }
                
                // Validation départ > arrivée
                if (end <= start) {
                    checkOutDate.setCustomValidity('Le départ doit être après l\'arrivée');
                } else {
                    checkOutDate.setCustomValidity('');
                }
            }
        }
    }

    // ============ GESTION CHANGEMENT DE CHAMBRE ============
    if (roomSelect) {
        // Récupérer les prix des options
        const roomPrices = {};
        document.querySelectorAll('#roomSelect option[data-price]').forEach(option => {
            roomPrices[option.value] = parseFloat(option.dataset.price);
        });
        
        roomSelect.addEventListener('change', function() {
            const selectedRoomId = this.value;
            
            if (selectedRoomId && roomPrices[selectedRoomId]) {
                const newPrice = roomPrices[selectedRoomId];
                const priceDifference = newPrice - currentRoomPrice;
                
                // Recalculer avec le nouveau prix
                calculateNights(newPrice);
                
                // Afficher l'info de prix
                if (priceDifference !== 0) {
                    roomPriceInfo.style.display = 'block';
                    
                    if (priceDifference > 0) {
                        priceChangeMessage.innerHTML = `
                            <strong class="text-danger">⚠️ Majoration</strong><br>
                            Prix/nuit: +${new Intl.NumberFormat('fr-FR').format(priceDifference)} CFA<br>
                            Impact sur le total: +${new Intl.NumberFormat('fr-FR').format(priceDifference * parseInt(nightsCount.textContent || 0))} CFA
                        `;
                    } else {
                        priceChangeMessage.innerHTML = `
                            <strong class="text-success">✅ Réduction</strong><br>
                            Prix/nuit: ${new Intl.NumberFormat('fr-FR').format(priceDifference)} CFA<br>
                            Impact sur le total: ${new Intl.NumberFormat('fr-FR').format(priceDifference * parseInt(nightsCount.textContent || 0))} CFA
                        `;
                    }
                } else {
                    roomPriceInfo.style.display = 'none';
                }
            } else {
                roomPriceInfo.style.display = 'none';
                calculateNights(currentRoomPrice);
            }
        });
    }

    // ============ GESTION STATUT ============
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            // Afficher/cacher champ raison d'annulation
            cancelReasonField.style.display = this.value === 'cancelled' ? 'block' : 'none';
            
            // Vérifications avant changement
            if (this.value === 'completed') {
                const isFullyPaid = <?php echo e($transaction->getRemainingPayment() <= 0 ? 'true' : 'false'); ?>;
                if (!isFullyPaid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Paiement incomplet',
                        text: 'Impossible de marquer comme terminé - Solde restant: <?php echo e(number_format($transaction->getRemainingPayment(), 0, ',', ' ')); ?> CFA',
                        confirmButtonText: 'Compris'
                    });
                    this.value = '<?php echo e($transaction->status); ?>';
                }
            }
            
            if (this.value === 'active') {
                const today = new Date().toISOString().split('T')[0];
                const checkIn = checkInDate.value;
                if (checkIn > today) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Date non atteinte',
                        text: `L'arrivée est prévue le ${new Date(checkIn).toLocaleDateString('fr-FR')}`,
                        confirmButtonText: 'Compris'
                    });
                    this.value = '<?php echo e($transaction->status); ?>';
                }
            }
        });
    }

    // ============ VÉRIFICATION DISPONIBILITÉ ============
    if (checkAvailabilityBtn) {
        checkAvailabilityBtn.addEventListener('click', async function() {
            const checkIn = combineDateTime(checkInDate);
            const checkOut = combineDateTime(checkOutDate);
            
            if (!checkIn || !checkOut) {
                Swal.fire('Erreur', 'Veuillez sélectionner les dates', 'warning');
                return;
            }
            
            try {
                Swal.fire({ title: 'Vérification...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                
                const response = await fetch(`/transactions/${transactionId}/check-availability`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify({ 
                        check_in: checkIn, 
                        check_out: checkOut,
                        room_id: roomSelect ? roomSelect.value : <?php echo e($transaction->room_id); ?>

                    })
                });
                
                const data = await response.json();
                Swal.close();
                
                const resultDiv = document.getElementById('availabilityResult');
                if (data.available) {
                    resultDiv.innerHTML = '<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>Chambre disponible</div>';
                } else {
                    resultDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>Chambre non disponible pour ces dates</div>';
                }
            } catch (error) {
                Swal.close();
                console.error(error);
            }
        });
    }

    // ============ BOUTON ANNULER ============
    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', function() {
            Swal.fire({
                title: 'Annuler les modifications ?',
                text: 'Toutes les modifications non enregistrées seront perdues',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, annuler',
                cancelButtonText: 'Non, rester'
            }).then(result => {
                if (result.isConfirmed) {
                    window.location.href = '<?php echo e(route("transaction.show", $transaction)); ?>';
                }
            });
        });
    }

    // ============ VALIDATION FORMULAIRE ============
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const checkIn = combineDateTime(checkInDate);
        const checkOut = combineDateTime(checkOutDate);
        
        if (!checkIn || !checkOut) {
            Swal.fire('Erreur', 'Veuillez remplir toutes les dates', 'error');
            return;
        }
        
        if (new Date(checkOut) <= new Date(checkIn)) {
            Swal.fire('Erreur', 'Le départ doit être après l\'arrivée', 'error');
            return;
        }
        
        // Vérification sélection chambre
        if (roomSelect && !roomSelect.value) {
            Swal.fire('Erreur', 'Veuillez sélectionner une chambre', 'error');
            roomSelect.focus();
            return;
        }
        
        // Vérification statut
        if (statusSelect && statusSelect.value === 'completed') {
            const isFullyPaid = <?php echo e($transaction->getRemainingPayment() <= 0 ? 'true' : 'false'); ?>;
            if (!isFullyPaid) {
                Swal.fire('Erreur', 'Paiement incomplet - Impossible de marquer comme terminé', 'error');
                return;
            }
        }
        
        // Confirmation
        Swal.fire({
            title: 'Enregistrer les modifications ?',
            html: `<div class="alert alert-info">
                Arrivée: ${checkInDate.value}<br>
                Départ: ${checkOutDate.value}<br>
                Nuits: ${nightsCount.textContent}<br>
                ${roomSelect ? 'Chambre: ' + roomSelect.options[roomSelect.selectedIndex]?.text.split(' - ')[0] : ''}
            </div>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Oui, enregistrer',
            cancelButtonText: 'Annuler'
        }).then(result => {
            if (result.isConfirmed) {
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enregistrement...';
                e.target.submit();
            }
        });
    });

    // ============ ÉVÉNEMENTS DATES ============
    if (checkInDate && checkOutDate) {
        checkInDate.addEventListener('change', function() {
            const selectedRoomPrice = roomSelect && roomSelect.value ? 
                (roomPrices ? roomPrices[roomSelect.value] : null) : null;
            calculateNights(selectedRoomPrice || currentRoomPrice);
            
            // Mettre à jour le min du départ
            const nextDay = new Date(this.value);
            nextDay.setDate(nextDay.getDate() + 1);
            checkOutDate.min = nextDay.toISOString().split('T')[0];
        });
        
        checkOutDate.addEventListener('change', function() {
            const selectedRoomPrice = roomSelect && roomSelect.value ? 
                (roomPrices ? roomPrices[roomSelect.value] : null) : null;
            calculateNights(selectedRoomPrice || currentRoomPrice);
        });
        
        // Calcul initial
        calculateNights(currentRoomPrice);
        
        // Définir min du départ
        const nextDay = new Date(checkInDate.value);
        nextDay.setDate(nextDay.getDate() + 1);
        checkOutDate.min = nextDay.toISOString().split('T')[0];
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/transaction/edit.blade.php ENDPATH**/ ?>