
<?php $__env->startSection('title', 'Modifier le client - ' . $customer->name); ?>
<?php $__env->startSection('content'); ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    /* ── 4 COULEURS (vert, rouge, gris, blanc) ── */
    --green-50:  #f0faf0;
    --green-100: #d4edda;
    --green-500: #2e8540;
    --green-600: #1e6b2e;
    --green-700: #155221;

    --red-50:    #fee2e2;
    --red-100:   #fecaca;
    --red-500:   #b91c1c;
    --red-600:   #991b1b;

    --gray-50:   #f8f9f8;
    --gray-100:  #eff0ef;
    --gray-200:  #dde0dd;
    --gray-300:  #c2c7c2;
    --gray-400:  #9ba09b;
    --gray-500:  #737873;
    --gray-600:  #545954;
    --gray-700:  #3a3e3a;
    --gray-800:  #252825;
    --gray-900:  #131513;

    --white:     #ffffff;
    --surface:   #f7f9f7;

    --shadow-xs: 0 1px 2px rgba(0,0,0,.04);
    --shadow-sm: 0 1px 6px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow-md: 0 4px 16px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.04);

    --r:   8px;
    --rl:  14px;
    --rxl: 20px;
    --transition: all .2s ease;
    --font: 'DM Sans', system-ui, sans-serif;
    --mono: 'DM Mono', monospace;
}

* { box-sizing: border-box; margin: 0; padding: 0; }

.edit-page {
    background: var(--surface);
    min-height: 100vh;
    padding: 24px 32px;
    font-family: var(--font);
    color: var(--gray-800);
}

/* ── Animations ── */
@keyframes fadeSlide {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
.anim-1 { animation: fadeSlide .4s ease both; }
.anim-2 { animation: fadeSlide .4s .08s ease both; }
.anim-3 { animation: fadeSlide .4s .16s ease both; }

/* ══════════════════════════════════════════════
   BREADCRUMB
══════════════════════════════════════════════ */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .8rem;
    color: var(--gray-400);
    margin-bottom: 20px;
}
.breadcrumb a {
    color: var(--gray-400);
    text-decoration: none;
    transition: var(--transition);
}
.breadcrumb a:hover {
    color: var(--green-600);
}
.breadcrumb .sep {
    color: var(--gray-300);
}
.breadcrumb .current {
    color: var(--gray-600);
    font-weight: 500;
}

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
}
.header-title {
    display: flex;
    align-items: center;
    gap: 12px;
}
.header-icon {
    width: 48px;
    height: 48px;
    background: var(--green-600);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 10px rgba(46,133,64,.3);
}
.header-title h1 {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0;
}
.header-title em {
    font-style: normal;
    color: var(--green-600);
}
.header-subtitle {
    color: var(--gray-500);
    font-size: .8rem;
    margin: 6px 0 0 60px;
}

/* ══════════════════════════════════════════════
   BUTTONS
══════════════════════════════════════════════ */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: var(--r);
    font-size: .8rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}
.btn-green {
    background: var(--green-600);
    color: white;
}
.btn-green:hover {
    background: var(--green-700);
    transform: translateY(-1px);
    color: white;
}
.btn-gray {
    background: var(--white);
    color: var(--gray-600);
    border: 1.5px solid var(--gray-200);
}
.btn-gray:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.btn-outline {
    background: var(--white);
    color: var(--gray-600);
    border: 1.5px solid var(--gray-200);
}
.btn-outline:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.btn-sm {
    padding: 6px 12px;
    font-size: .7rem;
}

/* ══════════════════════════════════════════════
   ALERTS
══════════════════════════════════════════════ */
.alert {
    padding: 14px 18px;
    border-radius: var(--rl);
    margin-bottom: 20px;
    border: 1.5px solid;
    display: flex;
    align-items: center;
    gap: 12px;
}
.alert-green {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.alert-red {
    background: var(--red-50);
    border-color: var(--red-100);
    color: var(--red-500);
}
.alert-icon {
    width: 28px;
    height: 28px;
    border-radius: var(--r);
    background: var(--green-600);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.alert-close {
    margin-left: auto;
    background: none;
    border: none;
    color: currentColor;
    opacity: .6;
    cursor: pointer;
}

/* ══════════════════════════════════════════════
   FORM CARD
══════════════════════════════════════════════ */
.form-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.form-header {
    background: linear-gradient(135deg, var(--green-700), var(--green-600));
    padding: 24px 28px;
}
.form-header h2 {
    color: white;
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.form-header h2 i {
    color: white;
}
.form-header p {
    color: rgba(255,255,255,.9);
    font-size: .8rem;
    margin: 0;
}
.form-body {
    padding: 28px;
}

/* ══════════════════════════════════════════════
   FORM
══════════════════════════════════════════════ */
.form-group {
    margin-bottom: 20px;
}
.form-label {
    display: block;
    font-size: .8rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 6px;
}
.form-label i {
    color: var(--green-600);
    width: 18px;
    margin-right: 4px;
}
.form-control, .form-select {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .8rem;
    transition: var(--transition);
    background: var(--white);
}
.form-control:focus, .form-select:focus {
    outline: none;
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgba(46,133,64,.1);
}
.form-control.is-invalid, .form-select.is-invalid {
    border-color: var(--red-500);
    background: var(--red-50);
}
.form-control:disabled {
    background: var(--gray-50);
    color: var(--gray-500);
    border-color: var(--gray-200);
    cursor: not-allowed;
}
textarea.form-control {
    min-height: 100px;
    resize: vertical;
}
.error-message {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 4px;
    color: var(--red-500);
    font-size: .7rem;
}
.error-message i {
    font-size: .8rem;
}
.text-muted {
    color: var(--gray-400) !important;
    font-size: .65rem;
    margin-top: 4px;
    display: block;
}
.text-muted i {
    color: var(--green-600);
}

/* ══════════════════════════════════════════════
   AVATAR PREVIEW
══════════════════════════════════════════════ */
.avatar-preview {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
    padding: 16px;
    background: var(--gray-50);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
}
.avatar-preview img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 3px solid var(--white);
    box-shadow: var(--shadow-sm);
    object-fit: cover;
}
.avatar-preview-info h6 {
    font-size: .85rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 4px;
}
.avatar-preview-info p {
    font-size: .7rem;
    color: var(--gray-500);
}

/* ══════════════════════════════════════════════
   FILE INPUT
══════════════════════════════════════════════ */
.form-file {
    position: relative;
}
.form-file-input {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}
.form-file-label {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border: 1.5px dashed var(--gray-300);
    border-radius: var(--r);
    background: var(--gray-50);
    transition: var(--transition);
    cursor: pointer;
}
.form-file-label:hover {
    border-color: var(--green-400);
    background: var(--green-50);
}
.form-file-icon {
    width: 36px;
    height: 36px;
    background: var(--white);
    border-radius: var(--r);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--green-600);
    font-size: 1rem;
}
.form-file-text {
    flex: 1;
}
.form-file-text .filename {
    font-size: .8rem;
    font-weight: 500;
    color: var(--gray-700);
}
.form-file-text .hint {
    font-size: .65rem;
    color: var(--gray-500);
}

/* ══════════════════════════════════════════════
   FORM ACTIONS
══════════════════════════════════════════════ */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 28px;
    padding-top: 20px;
    border-top: 1.5px solid var(--gray-200);
}

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media (max-width: 768px) {
    .edit-page { padding: 16px; }
    .form-body { padding: 20px; }
    .avatar-preview { flex-direction: column; text-align: center; }
    .form-actions { flex-direction: column; }
    .form-actions .btn { width: 100%; justify-content: center; }
}
</style>

<div class="edit-page">

    
    <div class="breadcrumb anim-1">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="<?php echo e(route('customer.index')); ?>">Clients</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="<?php echo e(route('customer.show', $customer->id)); ?>"><?php echo e($customer->name); ?></a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Modifier</span>
    </div>

    
    <div class="page-header anim-2">
        <div>
            <div class="header-title">
                <span class="header-icon"><i class="fas fa-user-edit"></i></span>
                <h1>Modifier <em><?php echo e($customer->name); ?></em></h1>
            </div>
            <p class="header-subtitle">Mettez à jour les informations du client</p>
        </div>
    </div>

    
    <?php if(session('success')): ?>
    <div class="alert alert-green">
        <div class="alert-icon"><i class="fas fa-check"></i></div>
        <span><?php echo session('success'); ?></span>
        <button class="alert-close" onclick="this.parentElement.remove()">✕</button>
    </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
    <div class="alert alert-red">
        <div class="alert-icon"><i class="fas fa-exclamation"></i></div>
        <div>
            <strong>Erreur de validation</strong>
            <ul class="mb-0 mt-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">✕</button>
    </div>
    <?php endif; ?>

    
    <div class="row justify-content-md-center anim-3">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="form-header">
                    <h2><i class="fas fa-user"></i> Informations du client</h2>
                    <p>Modifiez les informations ci-dessous</p>
                </div>
                
                <div class="form-body">
                    <form method="POST" action="<?php echo e(route('customer.update', $customer->id)); ?>" enctype="multipart/form-data">
                        <?php echo method_field('PUT'); ?>
                        <?php echo csrf_field(); ?>
                        
                        
                        <?php
                            $avatarUrl = $customer->user ? $customer->user->getAvatar() : null;
                        ?>
                        
                        <div class="avatar-preview">
                            <img src="<?php echo e($avatarUrl ?? 'https://ui-avatars.com/api/?name='.urlencode($customer->name).'&background=1e6b2e&color=fff&size=80'); ?>" 
                                 alt="<?php echo e($customer->name); ?>">
                            <div class="avatar-preview-info">
                                <h6>Photo de profil actuelle</h6>
                                <p>Vous pouvez télécharger une nouvelle photo ci-dessous</p>
                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-user"></i> Nom complet</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   name="name" value="<?php echo e(old('name', $customer->name)); ?>" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error-message"><i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" class="form-control" value="<?php echo e($customer->user->email ?? ''); ?>" disabled>
                            <span class="text-muted"><i class="fas fa-info-circle"></i> L'email ne peut pas être modifié</span>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-cake-candles"></i> Date de naissance</label>
                                    <input type="date" class="form-control <?php $__errorArgs = ['birthdate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           name="birthdate" value="<?php echo e(old('birthdate', $customer->birthdate)); ?>">
                                    <?php $__errorArgs = ['birthdate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error-message"><i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-venus-mars"></i> Genre</label>
                                    <select class="form-select <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="gender">
                                        <option value="">-- Sélectionnez --</option>
                                        <option value="Male" <?php echo e(old('gender', $customer->gender) == 'Male' ? 'selected' : ''); ?>>Masculin</option>
                                        <option value="Female" <?php echo e(old('gender', $customer->gender) == 'Female' ? 'selected' : ''); ?>>Féminin</option>
                                    </select>
                                    <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error-message"><i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-briefcase"></i> Profession</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['job'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           name="job" value="<?php echo e(old('job', $customer->job)); ?>">
                                    <?php $__errorArgs = ['job'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error-message"><i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-phone"></i> Téléphone</label>
                                    <input type="tel" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           name="phone" value="<?php echo e(old('phone', $customer->phone)); ?>">
                                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error-message"><i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-map-marker-alt"></i> Adresse</label>
                            <textarea class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      name="address" rows="3"><?php echo e(old('address', $customer->address)); ?></textarea>
                            <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error-message"><i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-camera"></i> Nouvelle photo</label>
                            <div class="form-file">
                                <input type="file" class="form-file-input" name="avatar" id="avatar" accept="image/*">
                                <div class="form-file-label">
                                    <div class="form-file-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                    <div class="form-file-text">
                                        <div class="filename" id="fileName">Aucun fichier choisi</div>
                                        <div class="hint">JPG, PNG, GIF (max. 2 Mo)</div>
                                    </div>
                                </div>
                            </div>
                            <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error-message"><i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        
                        <div class="form-actions">
                            <a href="<?php echo e(route('customer.show', $customer->id)); ?>" class="btn btn-outline">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-green">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity .5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // File input preview
    const fileInput = document.getElementById('avatar');
    const fileName = document.getElementById('fileName');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                fileName.textContent = this.files[0].name;
            } else {
                fileName.textContent = 'Aucun fichier choisi';
            }
        });
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            document.querySelector('form').submit();
        }
        if (e.key === 'Escape' && !e.target.matches('input, textarea, select')) {
            window.location.href = "<?php echo e(route('customer.show', $customer->id)); ?>";
        }
    });
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/customer/edit.blade.php ENDPATH**/ ?>