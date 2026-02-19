

<?php $__env->startSection('title', 'Edit Room'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
<style>
:root {
    --bg:       #f5f8fa;
    --surf:     #ffffff;
    --surf2:    #f1f5f9;
    --brd:      #e2e8f0;
    --brd2:     #cbd5e1;
    --txt:      #0f172a;
    --txt2:     #475569;
    --txt3:     #94a3b8;
    
    --blue:     #3b82f6;
    --blue-dim: rgba(59,130,246,.15);
    --grn:      #10b981;
    --grn-dim:  rgba(16,185,129,.15);
    --yel:      #eab308;
    --yel-dim:  rgba(234,179,8,.15);
    --red:      #ef4444;
    --red-dim:  rgba(239,68,68,.15);
    --cyan:     #06b6d4;
    --cyan-dim: rgba(6,182,212,.15);
    
    --r: 12px;
}

*, *::before, *::after { box-sizing: border-box; margin:0; padding:0; }
body {
    background: var(--bg);
    color: var(--txt);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 14px;
    line-height: 1.6;
}

/* ══════════════════════════════════════
   HEADER
══════════════════════════════════════ */
.re-header {
    background: var(--surf);
    border-bottom: 1px solid var(--brd);
    padding: 20px 28px;
    margin-bottom: 24px;
}
.re-header__inner {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}
.re-header__title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 24px;
    font-weight: 800;
    letter-spacing: -.5px;
    margin-bottom: 8px;
}
.re-header__title i {
    font-size: 22px;
    color: var(--blue);
}
.breadcrumb {
    margin: 0;
    padding: 0;
    background: none;
    font-size: 13px;
}
.breadcrumb-item + .breadcrumb-item::before {
    content: "/";
    color: var(--txt3);
}
.breadcrumb-item a {
    color: var(--txt3);
    text-decoration: none;
}
.breadcrumb-item a:hover {
    color: var(--blue);
}
.breadcrumb-item.active {
    color: var(--txt2);
}

/* ══════════════════════════════════════
   MAIN CONTAINER
══════════════════════════════════════ */
.re-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 28px 48px;
}

/* ══════════════════════════════════════
   ALERTS
══════════════════════════════════════ */
.alert {
    padding: 14px 18px;
    border-radius: 10px;
    border: 1px solid;
    margin-bottom: 20px;
}
.alert--success {
    background: var(--grn-dim);
    border-color: rgba(16,185,129,.3);
    color: var(--grn);
}
.alert--danger {
    background: var(--red-dim);
    border-color: rgba(239,68,68,.3);
    color: var(--red);
}
.alert--info {
    background: var(--blue-dim);
    border-color: rgba(59,130,246,.3);
    color: var(--blue);
}
.alert i { font-size: 16px; margin-right: 6px; }
.alert ul {
    margin: 8px 0 0 20px;
    padding: 0;
}
.alert .btn-close {
    margin-left: auto;
}

/* ══════════════════════════════════════
   CARD
══════════════════════════════════════ */
.card {
    background: var(--surf);
    border: 1px solid var(--brd);
    border-radius: var(--r);
    overflow: hidden;
}
.card__head {
    padding: 18px 24px;
    border-bottom: 1px solid var(--brd);
    background: var(--surf2);
}
.card__title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    font-weight: 700;
    margin: 0;
}
.card__title i { font-size: 17px; color: var(--cyan); }
.card__body {
    padding: 28px;
}

/* ══════════════════════════════════════
   FORM
══════════════════════════════════════ */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
}
.form-group {
    display: flex;
    flex-direction: column;
}
.form-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--txt);
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.form-label i {
    font-size: 12px;
}
.form-label .optional {
    font-size: 12px;
    font-weight: 400;
    color: var(--txt3);
}
.form-control,
.form-select {
    padding: 10px 14px;
    border-radius: 8px;
    border: 1px solid var(--brd2);
    font-size: 14px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    transition: all .15s;
    background: var(--surf);
}
.form-control:focus,
.form-select:focus {
    outline: none;
    border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(59,130,246,.1);
}
.form-hint {
    font-size: 11px;
    color: var(--txt3);
    margin-top: 4px;
}
.input-group {
    display: flex;
}
.input-group-text {
    padding: 10px 14px;
    background: var(--surf2);
    border: 1px solid var(--brd2);
    border-right: none;
    border-radius: 8px 0 0 8px;
    font-size: 13px;
    font-weight: 600;
    color: var(--txt2);
}
.input-group .form-control {
    border-radius: 0 8px 8px 0;
}
.invalid-feedback {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    color: var(--red);
    margin-top: 4px;
}

/* ══════════════════════════════════════
   STATUS BOX
══════════════════════════════════════ */
.status-box {
    background: var(--surf2);
    border: 1px solid var(--brd);
    border-radius: 10px;
    padding: 16px;
}
.status-display {
    display: flex;
    align-items: center;
    gap: 12px;
}
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    flex-shrink: 0;
}
.status-badge--success { background: var(--grn-dim); color: var(--grn); }
.status-badge--warning { background: var(--yel-dim); color: var(--yel); }
.status-badge--danger  { background: var(--red-dim); color: var(--red); }
.status-badge--gray    { background: var(--surf2); color: var(--txt3); }
.status-info {
    flex: 1;
}
.status-meta {
    font-size: 12px;
    color: var(--txt3);
    margin-top: 4px;
}
.status-detail {
    font-size: 12px;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}
.status-detail i {
    font-size: 11px;
}

/* ══════════════════════════════════════
   INFO BOX
══════════════════════════════════════ */
.info-box {
    background: var(--blue-dim);
    border: 1px solid rgba(59,130,246,.3);
    border-radius: 8px;
    padding: 12px;
    margin-top: 12px;
    display: flex;
    gap: 10px;
}
.info-box i {
    color: var(--blue);
    flex-shrink: 0;
    margin-top: 2px;
}
.info-box__content {
    font-size: 12px;
    color: var(--txt2);
}
.info-box__content strong {
    display: block;
    margin-bottom: 2px;
    color: var(--txt);
}

/* ══════════════════════════════════════
   META CARD
══════════════════════════════════════ */
.meta-card {
    background: var(--surf2);
    border: 1px solid var(--brd);
    border-radius: 10px;
    padding: 16px;
}
.meta-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--txt3);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.meta-title i {
    font-size: 12px;
}
.meta-row {
    font-size: 12px;
    color: var(--txt2);
    margin-bottom: 4px;
}
.meta-row:last-child {
    margin-bottom: 0;
}

/* ══════════════════════════════════════
   BUTTONS
══════════════════════════════════════ */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid;
    text-decoration: none;
    transition: all .15s;
    cursor: pointer;
    white-space: nowrap;
}
.btn--primary {
    background: var(--blue);
    border-color: var(--blue);
    color: white;
}
.btn--primary:hover {
    background: #2563eb;
    border-color: #2563eb;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59,130,246,.3);
}
.btn--outline {
    background: transparent;
    border-color: var(--brd2);
    color: var(--txt2);
}
.btn--outline:hover {
    background: var(--surf2);
    border-color: var(--brd2);
    color: var(--txt);
}
.btn--info {
    background: var(--cyan-dim);
    border-color: rgba(6,182,212,.3);
    color: var(--cyan);
}
.btn--info:hover {
    background: rgba(6,182,212,.25);
    border-color: var(--cyan);
    color: var(--cyan);
}
.btn--warning {
    background: var(--yel-dim);
    border-color: rgba(234,179,8,.3);
    color: var(--yel);
    font-size: 12px;
    padding: 6px 12px;
}
.btn--warning:hover {
    background: rgba(234,179,8,.25);
    border-color: var(--yel);
    color: var(--yel);
}

/* ══════════════════════════════════════
   ACTIONS BAR
══════════════════════════════════════ */
.actions-bar {
    padding-top: 24px;
    margin-top: 24px;
    border-top: 1px solid var(--brd);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.actions-group {
    display: flex;
    gap: 8px;
}

/* ══════════════════════════════════════
   FULL WIDTH ELEMENT
══════════════════════════════════════ */
.full-width {
    grid-column: 1 / -1;
}

/* ══════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════ */
@media (max-width: 768px) {
    .re-header { padding: 16px 20px; }
    .re-header__inner { flex-direction: column; align-items: flex-start; }
    .re-container { padding: 0 20px 40px; }
    .form-grid { grid-template-columns: 1fr; }
    .card__body { padding: 20px; }
    .actions-bar { flex-direction: column; gap: 12px; }
    .actions-group { width: 100%; flex-direction: column; }
    .btn { width: 100%; justify-content: center; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="re-header">
    <div class="re-header__inner">
        <div>
            <div class="re-header__title">
                <i class="fas fa-edit"></i>
                Edit Room: <?php echo e($room->number); ?>

            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard.index')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('room.index')); ?>">Rooms</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('room.show', $room->id)); ?>"><?php echo e($room->number); ?></a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <a href="<?php echo e(route('room.show', $room->id)); ?>" class="btn btn--outline">
            <i class="fas fa-eye"></i>
            View Room
        </a>
    </div>
</div>


<div class="re-container">

    
    <?php if($errors->any()): ?>
    <div class="alert alert--danger">
        <i class="fas fa-exclamation-triangle"></i>
        <div style="flex:1">
            <strong>Please fix the following errors:</strong>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if(session('success')): ?>
    <div class="alert alert--success">
        <i class="fas fa-check-circle"></i>
        <span><?php echo e(session('success')); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if(session('info')): ?>
    <div class="alert alert--info">
        <i class="fas fa-info-circle"></i>
        <span><?php echo session('info'); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    
    <div class="card">
        <div class="card__head">
            <h5 class="card__title">
                <i class="fas fa-info-circle"></i>
                Room Information
            </h5>
        </div>
        <div class="card__body">
            <form method="POST" action="<?php echo e(route('room.update', $room->id)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <div class="form-grid">
                    
                    
                    <div class="form-group">
                        <label for="number" class="form-label">
                            <i class="fas fa-hashtag" style="color:var(--blue)"></i>
                            Room Number *
                        </label>
                        <input type="text" 
                               class="form-control <?php $__errorArgs = ['number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="number" 
                               name="number" 
                               value="<?php echo e(old('number', $room->number)); ?>" 
                               placeholder="Example: 101, 201, 301" 
                               required>
                        <?php $__errorArgs = ['number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo e($message); ?>

                        </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-hint">Unique room identifier</div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-signature" style="color:var(--blue)"></i>
                            Room Name
                            <span class="optional">(Optional)</span>
                        </label>
                        <input type="text" 
                               class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="name" 
                               name="name" 
                               value="<?php echo e(old('name', $room->name)); ?>" 
                               placeholder="Presidential Suite, Ocean View">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo e($message); ?>

                        </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-hint">Descriptive name for the room</div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="type_id" class="form-label">
                            <i class="fas fa-bed" style="color:var(--blue)"></i>
                            Room Type *
                        </label>
                        <select id="type_id" 
                                name="type_id" 
                                class="form-select <?php $__errorArgs = ['type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                required>
                            <option value="" disabled>-- Select Type --</option>
                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type->id); ?>" <?php echo e(old('type_id', $room->type_id) == $type->id ? 'selected' : ''); ?>>
                                    <?php echo e($type->name); ?> 
                                    <?php if($type->base_price): ?>
                                        - <?php echo e(number_format($type->base_price, 0, ',', ' ')); ?> FCFA
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo e($message); ?>

                        </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-circle" style="color:var(--grn)"></i>
                            Current Room Status
                            <span class="optional">(Auto-managed)</span>
                        </label>
                        
                        <div class="status-box">
                            <div class="status-display">
                                <?php
                                    $statusColor = match($room->roomStatus->code ?? '') {
                                        'available' => 'success',
                                        'occupied' => 'danger',
                                        'reserved' => 'warning',
                                        'maintenance' => 'gray',
                                        default => 'gray'
                                    };
                                ?>
                                
                                <span class="status-badge status-badge--<?php echo e($statusColor); ?>">
                                    <i class="fas fa-<?php echo e(match($room->roomStatus->code ?? '') {
                                        'available' => 'check',
                                        'occupied' => 'user',
                                        'reserved' => 'calendar-check',
                                        'maintenance' => 'tools',
                                        default => 'question-circle'
                                    }); ?>"></i>
                                    <?php echo e($room->roomStatus->name ?? 'Unknown'); ?>

                                </span>
                                
                                <div class="status-info">
                                    <div class="status-meta"><?php echo e($room->roomStatus->information ?? ''); ?></div>
                                    
                                    <?php if(($room->roomStatus->code ?? '') == 'occupied'): ?>
                                        <?php
                                            $activeTransaction = $room->transactions()
                                                ->where('status', 'active')
                                                ->where('check_in', '<=', now())
                                                ->where('check_out', '>=', now())
                                                ->first();
                                        ?>
                                        <?php if($activeTransaction): ?>
                                        <div class="status-detail" style="color:var(--red)">
                                            <i class="fas fa-user"></i>
                                            Client: <?php echo e($activeTransaction->customer->name); ?>

                                        </div>
                                        <?php endif; ?>
                                    <?php elseif(($room->roomStatus->code ?? '') == 'reserved'): ?>
                                        <?php
                                            $nextReservation = $room->transactions()
                                                ->where('status', 'reservation')
                                                ->where('check_in', '>', now())
                                                ->orderBy('check_in', 'asc')
                                                ->first();
                                        ?>
                                        <?php if($nextReservation): ?>
                                        <div class="status-detail" style="color:var(--yel)">
                                            <i class="fas fa-calendar"></i>
                                            Arrival: <?php echo e(\Carbon\Carbon::parse($nextReservation->check_in)->format('d/m/Y')); ?>

                                        </div>
                                        <?php endif; ?>
                                    <?php elseif(($room->roomStatus->code ?? '') == 'maintenance'): ?>
                                        <?php if($room->maintenance_started_at): ?>
                                        <div class="status-detail">
                                            <i class="fas fa-clock"></i>
                                            Since: <?php echo e(\Carbon\Carbon::parse($room->maintenance_started_at)->format('d/m/Y H:i')); ?>

                                        </div>
                                        <?php endif; ?>
                                        <?php if($room->maintenance_reason): ?>
                                        <div class="status-detail">
                                            <i class="fas fa-sticky-note"></i>
                                            Reason: <?php echo e($room->maintenance_reason); ?>

                                        </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" name="room_status_id" value="<?php echo e($room->room_status_id); ?>">
                        
                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            <div class="info-box__content">
                                <strong>Auto-managed status</strong>
                                This status is automatically updated based on reservations and stays.
                            </div>
                        </div>
                        
                        <?php if(auth()->user()->role == 'Super'): ?>
                        <div style="margin-top:12px">
                            <button type="button" class="btn btn--warning" 
                                    onclick="toggleMaintenance(<?php echo e($room->id); ?>, '<?php echo e($room->roomStatus->code ?? ''); ?>')">
                                <i class="fas fa-tools"></i>
                                <?php echo e(($room->roomStatus->code ?? '') == 'maintenance' ? 'End Maintenance' : 'Set to Maintenance'); ?>

                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="capacity" class="form-label">
                            <i class="fas fa-users" style="color:var(--blue)"></i>
                            Capacity *
                        </label>
                        <input type="number" 
                               class="form-control <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="capacity" 
                               name="capacity" 
                               value="<?php echo e(old('capacity', $room->capacity)); ?>" 
                               placeholder="2, 4, 6" 
                               min="1" 
                               max="10" 
                               required>
                        <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo e($message); ?>

                        </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-hint">Number of guests (1-10)</div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="price" class="form-label">
                            <i class="fas fa-money-bill-wave" style="color:var(--grn)"></i>
                            Price per Night *
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">FCFA</span>
                            <input type="number" 
                                   class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="price" 
                                   name="price" 
                                   value="<?php echo e(old('price', $room->price)); ?>" 
                                   placeholder="50000" 
                                   min="0" 
                                   required>
                        </div>
                        <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo e($message); ?>

                        </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="view" class="form-label">
                            <i class="fas fa-binoculars" style="color:var(--cyan)"></i>
                            View Description
                        </label>
                        <textarea class="form-control <?php $__errorArgs = ['view'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="view" 
                                  name="view" 
                                  rows="1" 
                                  placeholder="Sea view, Mountain view, City view"><?php echo e(old('view', $room->view)); ?></textarea>
                        <?php $__errorArgs = ['view'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo e($message); ?>

                        </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    
                    <div class="form-group">
                        <div class="meta-card">
                            <div class="meta-title">
                                <i class="fas fa-calendar-alt"></i>
                                Room Information
                            </div>
                            <div class="meta-row">Created: <?php echo e($room->created_at->format('d/m/Y H:i')); ?></div>
                            <div class="meta-row">Last Updated: <?php echo e($room->updated_at->format('d/m/Y H:i')); ?></div>
                        </div>
                    </div>
                    
                </div>
                
                
                <div class="actions-bar">
                    <a href="<?php echo e(route('room.index')); ?>" class="btn btn--outline">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <div class="actions-group">
                        <a href="<?php echo e(route('room.show', $room->id)); ?>" class="btn btn--info">
                            <i class="fas fa-eye"></i>
                            View
                        </a>
                        <button type="submit" class="btn btn--primary">
                            <i class="fas fa-save"></i>
                            Update Room
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function toggleMaintenance(roomId, currentStatus) {
    const isMaintenance = currentStatus === 'maintenance';
    
    Swal.fire({
        title: isMaintenance ? 'End Maintenance Mode?' : 'Set Room to Maintenance?',
        html: `
            <div style="text-align:left">
                <p>${isMaintenance 
                    ? 'This will mark the room as available again.' 
                    : 'This will temporarily mark the room as unavailable.'}</p>
                
                ${!isMaintenance ? `
                <div style="margin-bottom:16px">
                    <label style="display:block;margin-bottom:6px;font-weight:600">Maintenance reason:</label>
                    <textarea id="maintenanceReason" class="form-control" rows="3" 
                              placeholder="Cleaning, repairs, renovation..."></textarea>
                </div>
                ` : ''}
            </div>
        `,
        icon: isMaintenance ? 'question' : 'warning',
        showCancelButton: true,
        confirmButtonColor: isMaintenance ? '#10b981' : '#eab308',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: isMaintenance ? 'Yes, end maintenance' : 'Yes, set to maintenance',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            if (!isMaintenance) {
                const reason = document.getElementById('maintenanceReason').value;
                if (!reason.trim()) {
                    Swal.showValidationMessage('Please enter a maintenance reason');
                    return false;
                }
                return { reason: reason.trim() };
            }
            return {};
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const reason = result.value?.reason || '';
            
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch(`/room/${roomId}/maintenance-toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    action: isMaintenance ? 'end' : 'start',
                    reason: reason
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Operation failed'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Network error occurred. Please try again.'
                });
            });
        }
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/room/edit.blade.php ENDPATH**/ ?>