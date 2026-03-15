

<?php $__env->startSection('title', 'Nouvelle Chambre'); ?>

<?php $__env->startSection('content'); ?>
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    /* ── Palette : 3 couleurs uniquement ── */
    /* VERT */
    --g50:  #f0faf0;
    --g100: #d4edda;
    --g200: #a8d5b5;
    --g300: #72bb82;
    --g400: #4a9e5c;
    --g500: #2e8540;
    --g600: #1e6b2e;
    --g700: #155221;
    --g800: #0d3a16;
    --g900: #072210;
    /* BLANC / SURFACE */
    --white:    #ffffff;
    --surface:  #f7f9f7;
    --surface2: #eef3ee;
    /* GRIS */
    --s50:  #f8f9f8;
    --s100: #eff0ef;
    --s200: #dde0dd;
    --s300: #c2c7c2;
    --s400: #9ba09b;
    --s500: #737873;
    --s600: #545954;
    --s700: #3a3e3a;
    --s800: #252825;
    --s900: #131513;

    --shadow-xs: 0 1px 2px rgba(0,0,0,.04);
    --shadow-sm: 0 1px 6px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow-md: 0 4px 16px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.04);
    --shadow-lg: 0 12px 40px rgba(0,0,0,.10), 0 4px 12px rgba(0,0,0,.05);

    --r:   8px;
    --rl:  14px;
    --rxl: 20px;
    --transition: all .2s cubic-bezier(.4,0,.2,1);
    --font: 'DM Sans', system-ui, sans-serif;
    --mono: 'DM Mono', monospace;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.create-page {
    padding: 28px 32px 64px;
    background: var(--surface);
    min-height: 100vh;
    font-family: var(--font);
    color: var(--s800);
}

/* ── Animations ── */
@keyframes fadeSlide {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes scaleIn {
    from { opacity: 0; transform: scale(.96); }
    to   { opacity: 1; transform: scale(1); }
}
.anim-1 { animation: fadeSlide .4s ease both; }
.anim-2 { animation: fadeSlide .4s .08s ease both; }
.anim-3 { animation: fadeSlide .4s .16s ease both; }
.anim-4 { animation: fadeSlide .4s .24s ease both; }
.anim-5 { animation: fadeSlide .4s .32s ease both; }
.anim-6 { animation: fadeSlide .4s .40s ease both; }

/* ══════════════════════════════════════════════
   BREADCRUMB
══════════════════════════════════════════════ */
.create-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .8rem; color: var(--s400);
    margin-bottom: 20px;
}
.create-breadcrumb a {
    color: var(--s400); text-decoration: none;
    transition: var(--transition);
}
.create-breadcrumb a:hover { color: var(--g600); }
.create-breadcrumb .sep { color: var(--s300); }
.create-breadcrumb .current { color: var(--s600); font-weight: 500; }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.create-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.create-brand { display: flex; align-items: center; gap: 14px; }
.create-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.create-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.create-header-title em { font-style: normal; color: var(--g600); }
.create-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.create-header-actions { display: flex; align-items: center; gap: 10px; }

/* ══════════════════════════════════════════════
   BOUTONS
══════════════════════════════════════════════ */
.btn-db {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: var(--r);
    font-size: .8rem; font-weight: 500; border: none;
    cursor: pointer; transition: var(--transition);
    text-decoration: none; white-space: nowrap; line-height: 1;
    font-family: var(--font);
}
.btn-db-primary {
    background: var(--g600); color: white;
    box-shadow: 0 2px 10px rgba(46,133,64,.3);
}
.btn-db-primary:hover {
    background: var(--g700); color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
    text-decoration: none;
}
.btn-db-ghost {
    background: var(--white); color: var(--s600);
    border: 1.5px solid var(--s200);
}
.btn-db-ghost:hover {
    background: var(--s50); border-color: var(--s300);
    color: var(--s900); text-decoration: none;
}

/* ══════════════════════════════════════════════
   ALERTES
══════════════════════════════════════════════ */
.alert-modern {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 18px; border-radius: var(--rl);
    margin-bottom: 20px; border: 1.5px solid transparent;
    font-size: .875rem; background: var(--white);
    box-shadow: var(--shadow-sm);
}
.alert-success {
    background: var(--g50); border-color: var(--g200);
    color: var(--g700);
}
.alert-danger {
    background: #fee2e2; border-color: #fecaca;
    color: #b91c1c;
}
.alert-icon {
    width: 28px; height: 28px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.alert-success .alert-icon { background: var(--g100); color: var(--g600); }
.alert-danger .alert-icon { background: #fecaca; color: #b91c1c; }
.alert-close {
    margin-left: auto; background: none; border: none;
    color: currentColor; opacity: .6; cursor: pointer;
    font-size: 1rem; transition: var(--transition);
}
.alert-close:hover { opacity: 1; }
.alert ul {
    margin: 8px 0 0 20px; padding: 0;
}

/* ══════════════════════════════════════════════
   CARTE PRINCIPALE
══════════════════════════════════════════════ */
.create-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 20px; box-shadow: var(--shadow-sm);
}
.create-card-header {
    padding: 18px 24px;
    border-bottom: 1.5px solid var(--s100);
    background: var(--white);
}
.create-card-title {
    display: flex; align-items: center; gap: 10px;
    font-size: .95rem; font-weight: 600; color: var(--s800); margin: 0;
}
.create-card-title i { color: var(--g500); }
.create-card-body { padding: 28px; }

/* ══════════════════════════════════════════════
   FORMULAIRE
══════════════════════════════════════════════ */
.form-grid {
    display: grid; grid-template-columns: repeat(2, 1fr);
    gap: 24px;
}
@media(max-width:768px){ .form-grid{ grid-template-columns:1fr; } }

.form-group {
    display: flex; flex-direction: column;
}
.form-label {
    font-size: .75rem; font-weight: 600; color: var(--s600);
    margin-bottom: 6px; display: flex; align-items: center; gap: 6px;
    text-transform: uppercase; letter-spacing: .5px;
}
.form-label i { font-size: .7rem; color: var(--g500); }
.form-label .optional {
    font-size: .65rem; font-weight: 400; color: var(--s400);
    margin-left: 4px;
}
.form-control, .form-select {
    padding: 10px 14px; border-radius: var(--r);
    border: 1.5px solid var(--s200); font-size: .875rem;
    font-family: var(--font); transition: var(--transition);
    background: var(--white);
}
.form-control:focus, .form-select:focus {
    outline: none; border-color: var(--g400);
    box-shadow: 0 0 0 3px var(--g100);
}
.form-hint {
    font-size: .65rem; color: var(--s400); margin-top: 4px;
}
.input-group {
    display: flex;
}
.input-group-text {
    padding: 10px 14px; background: var(--s100);
    border: 1.5px solid var(--s200); border-right: none;
    border-radius: var(--r) 0 0 var(--r); font-size: .75rem;
    font-weight: 600; color: var(--s600);
}
.input-group .form-control {
    border-radius: 0 var(--r) var(--r) 0;
}
.invalid-feedback {
    display: flex; align-items: center; gap: 4px;
    font-size: .7rem; color: #b91c1c; margin-top: 4px;
}

/* ══════════════════════════════════════════════
   ACTIONS BAR
══════════════════════════════════════════════ */
.actions-bar {
    padding-top: 24px; margin-top: 24px;
    border-top: 1.5px solid var(--s100);
    display: flex; justify-content: space-between; align-items: center;
}
.actions-group {
    display: flex; gap: 8px;
}

/* ══════════════════════════════════════════════
   BADGES POUR STATUTS
══════════════════════════════════════════════ */
.badge-option {
    display: inline-block;
    margin-right: 4px;
}
.badge-option--success { background: var(--g100); color: var(--g700); }
.badge-option--danger { background: #fee2e2; color: #b91c1c; }
.badge-option--warning { background: #fff3cd; color: #856404; }

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media(max-width:768px){
    .create-page{ padding: 20px; }
    .create-header{ flex-direction: column; align-items: flex-start; }
    .create-card-body{ padding: 20px; }
    .actions-bar{ flex-direction: column; gap: 12px; }
    .btn-db{ width: 100%; justify-content: center; }
}
</style>

<div class="create-page">
    <!-- Breadcrumb -->
    <div class="create-breadcrumb anim-1">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="<?php echo e(route('room.index')); ?>">Chambres</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Nouvelle chambre</span>
    </div>

    <!-- Header -->
    <div class="create-header anim-2">
        <div class="create-brand">
            <div class="create-brand-icon"><i class="fas fa-plus"></i></div>
            <div>
                <h1 class="create-header-title">Nouvelle <em>chambre</em></h1>
                <p class="create-header-sub">
                    <i class="fas fa-door-open me-1"></i> Ajouter une nouvelle chambre à l'hôtel
                </p>
            </div>
        </div>
        <div class="create-header-actions">
            <a href="<?php echo e(route('room.index')); ?>" class="btn-db btn-db-ghost">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
        </div>
    </div>

    <!-- Alertes -->
    <?php if($errors->any()): ?>
    <div class="alert-modern alert-danger anim-2">
        <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div style="flex:1">
            <strong>Veuillez corriger les erreurs suivantes :</strong>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">×</button>
    </div>
    <?php endif; ?>

    <?php if(session('success')): ?>
    <div class="alert-modern alert-success anim-2">
        <div class="alert-icon"><i class="fas fa-check-circle"></i></div>
        <span><?php echo e(session('success')); ?></span>
        <button class="alert-close" onclick="this.parentElement.remove()">×</button>
    </div>
    <?php endif; ?>

    <!-- Formulaire -->
    <div class="create-card anim-3">
        <div class="create-card-header">
            <h5 class="create-card-title">
                <i class="fas fa-info-circle"></i>
                Informations de la chambre
            </h5>
        </div>
        <div class="create-card-body">
            <form class="form" method="POST" action="<?php echo e(route('room.store')); ?>">
                <?php echo csrf_field(); ?>
                
                <div class="form-grid">
                    
                    <!-- Numéro de chambre -->
                    <div class="form-group">
                        <label for="number" class="form-label">
                            <i class="fas fa-hashtag"></i>
                            Numéro de chambre *
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
                               value="<?php echo e(old('number')); ?>" 
                               placeholder="Ex: 101, 201, 301" 
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
                        <div class="form-hint">Identifiant unique de la chambre</div>
                    </div>
                    
                    <!-- Nom de la chambre -->
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-signature"></i>
                            Nom de la chambre
                            <span class="optional">(Optionnel)</span>
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
                               value="<?php echo e(old('name')); ?>" 
                               placeholder="Ex: Suite Présidentielle, Vue Mer">
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
                        <div class="form-hint">Nom descriptif de la chambre</div>
                    </div>
                    
                    <!-- Type de chambre -->
                    <div class="form-group">
                        <label for="type_id" class="form-label">
                            <i class="fas fa-bed"></i>
                            Type de chambre *
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
                            <option value="" disabled selected>-- Sélectionner un type --</option>
                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type->id); ?>" <?php echo e(old('type_id') == $type->id ? 'selected' : ''); ?>>
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
                    
                    <!-- Statut de la chambre -->
                    <div class="form-group">
                        <label for="room_status_id" class="form-label">
                            <i class="fas fa-circle"></i>
                            Statut de la chambre *
                        </label>
                        <select id="room_status_id" 
                                name="room_status_id" 
                                class="form-select <?php $__errorArgs = ['room_status_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                required>
                            <option value="" disabled selected>-- Sélectionner un statut --</option>
                            <?php $__currentLoopData = $roomstatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roomstatus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $badgeClass = match($roomstatus->code ?? '') {
                                        'available' => 'success',
                                        'occupied' => 'danger',
                                        'reserved' => 'warning',
                                        default => 'gray'
                                    };
                                ?>
                                <option value="<?php echo e($roomstatus->id); ?>" <?php echo e(old('room_status_id') == $roomstatus->id ? 'selected' : ''); ?>>
                                    <?php echo e($roomstatus->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['room_status_id'];
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
                        <div class="form-hint">Statut initial de la chambre (peut être modifié plus tard)</div>
                    </div>
                    
                    <!-- Capacité -->
                    <div class="form-group">
                        <label for="capacity" class="form-label">
                            <i class="fas fa-users"></i>
                            Capacité *
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
                               value="<?php echo e(old('capacity', 2)); ?>" 
                               placeholder="Ex: 2, 4, 6" 
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
                        <div class="form-hint">Nombre de personnes (1-10)</div>
                    </div>
                    
                    <!-- Prix par nuit -->
                    <div class="form-group">
                        <label for="price" class="form-label">
                            <i class="fas fa-money-bill-wave"></i>
                            Prix par nuit *
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
                                   value="<?php echo e(old('price')); ?>" 
                                   placeholder="Ex: 50000" 
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
                    
                    <!-- Description de la vue -->
                    <div class="form-group">
                        <label for="view" class="form-label">
                            <i class="fas fa-binoculars"></i>
                            Description de la vue
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
                                  placeholder="Ex: Vue sur mer, Vue sur montagne, Vue sur ville"><?php echo e(old('view')); ?></textarea>
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
                        <div class="form-hint">Optionnel - décrit la vue depuis la chambre</div>
                    </div>
                    
                </div>
                
                <!-- Actions -->
                <div class="actions-bar">
                    <a href="<?php echo e(route('room.index')); ?>" class="btn-db btn-db-ghost">
                        <i class="fas fa-times me-2"></i>
                        Annuler
                    </a>
                    <div class="actions-group">
                        <button type="reset" class="btn-db btn-db-ghost">
                            <i class="fas fa-redo me-2"></i>
                            Réinitialiser
                        </button>
                        <button type="submit" class="btn-db btn-db-primary">
                            <i class="fas fa-save me-2"></i>
                            Créer la chambre
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/room/create.blade.php ENDPATH**/ ?>