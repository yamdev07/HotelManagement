<?php $__env->startSection('title', 'Ajouter un Client'); ?>
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

.create-page {
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
    margin-bottom: 24px;
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
    padding: 10px 20px;
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

/* ══════════════════════════════════════════════
   CARD
══════════════════════════════════════════════ */
.card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.card-header {
    background: var(--gray-50);
    border-bottom: 1.5px solid var(--gray-200);
    padding: 18px 24px;
}
.card-header h2 {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.card-header h2 i {
    color: var(--green-600);
}
.card-body {
    padding: 24px;
}

/* ══════════════════════════════════════════════
   FORM
══════════════════════════════════════════════ */
.form-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 600;
    font-size: .8rem;
    color: var(--gray-700);
    margin-bottom: 8px;
}
.form-label i {
    color: var(--green-600);
    width: 18px;
}
.form-control, .form-select {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .85rem;
    color: var(--gray-700);
    background: var(--white);
    transition: var(--transition);
}
.form-control:focus, .form-select:focus {
    outline: none;
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgba(46,133,64,.1);
}
.form-control.is-invalid, .form-select.is-invalid {
    border-color: var(--red-500);
}
.invalid-feedback {
    color: var(--red-500);
    font-size: .7rem;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}
.invalid-feedback i {
    font-size: .8rem;
}

/* ── File input ── */
.form-control[type="file"] {
    padding: 8px;
    background: var(--gray-50);
    cursor: pointer;
}
.form-control[type="file"]::-webkit-file-upload-button {
    background: var(--green-600);
    border: none;
    color: white;
    padding: 6px 12px;
    border-radius: var(--r);
    margin-right: 12px;
    cursor: pointer;
    transition: var(--transition);
}
.form-control[type="file"]::-webkit-file-upload-button:hover {
    background: var(--green-700);
}

/* ══════════════════════════════════════════════
   FORM ACTIONS
══════════════════════════════════════════════ */
.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1.5px solid var(--gray-200);
}

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media (max-width: 768px) {
    .create-page { padding: 16px; }
    .card-body { padding: 18px; }
}
</style>

<div class="create-page">

    
    <div class="breadcrumb anim-1">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="<?php echo e(route('customer.index')); ?>">Clients</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Nouveau client</span>
    </div>

    
    <div class="page-header anim-2">
        <div class="header-title">
            <span class="header-icon"><i class="fas fa-user-plus"></i></span>
            <h1>Ajouter un <em>client</em></h1>
        </div>
        <p class="header-subtitle">Créez un nouveau compte client</p>
    </div>

    
    <div class="row justify-content-md-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-user-plus"></i> Nouveau client</h2>
                </div>
                <div class="card-body">
                    <form class="row g-4" method="POST" action="<?php echo e(route('customer.store')); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        
                        <div class="col-md-12">
                            <label for="name" class="form-label">
                                <i class="fas fa-user"></i> Nom complet
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
                                   placeholder="Ex: Jean Dupont">
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="col-md-12">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" 
                                   class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="email"
                                   name="email" 
                                   value="<?php echo e(old('email')); ?>"
                                   placeholder="exemple@email.com">
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="col-md-12">
                            <label for="birthdate" class="form-label">
                                <i class="fas fa-cake-candles"></i> Date de naissance
                            </label>
                            <input type="date" 
                                   class="form-control <?php $__errorArgs = ['birthdate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="birthdate"
                                   name="birthdate" 
                                   value="<?php echo e(old('birthdate')); ?>">
                            <?php $__errorArgs = ['birthdate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="col-md-12">
                            <label for="gender" class="form-label">
                                <i class="fas fa-venus-mars"></i> Genre
                            </label>
                            <select class="form-select <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="gender" 
                                    name="gender">
                                <option value="" selected disabled>-- Sélectionnez --</option>
                                <option value="Male" <?php echo e(old('gender') == 'Male' ? 'selected' : ''); ?>>Masculin</option>
                                <option value="Female" <?php echo e(old('gender') == 'Female' ? 'selected' : ''); ?>>Féminin</option>
                            </select>
                            <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="col-md-12">
                            <label for="job" class="form-label">
                                <i class="fas fa-briefcase"></i> Profession
                            </label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['job'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="job" 
                                   name="job"
                                   value="<?php echo e(old('job')); ?>"
                                   placeholder="Ex: Ingénieur, Enseignant...">
                            <?php $__errorArgs = ['job'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="col-md-12">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Adresse
                            </label>
                            <textarea class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="address" 
                                      name="address"
                                      rows="3"
                                      placeholder="Adresse complète..."><?php echo e(old('address')); ?></textarea>
                            <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="col-md-12">
                            <label for="avatar" class="form-label">
                                <i class="fas fa-camera"></i> Photo de profil
                            </label>
                            <input class="form-control <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   type="file" 
                                   name="avatar" 
                                   id="avatar"
                                   accept="image/*">
                            <small class="text-muted d-block mt-1">
                                <i class="fas fa-info-circle"></i> Formats acceptés: JPG, PNG, GIF (max 2MB)
                            </small>
                            <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="col-12">
                            <div class="form-actions">
                                <a href="<?php echo e(route('customer.index')); ?>" class="btn btn-gray">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-green">
                                    <i class="fas fa-save"></i> Enregistrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row justify-content-md-center mt-4">
        <div class="col-lg-8">
            <div class="card" style="margin-top: -10px;">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:60px; height:60px; background:var(--gray-100); border-radius:50%; display:flex; align-items:center; justify-content:center; color:var(--gray-400);">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Astuce</small>
                            <span style="color:var(--gray-600); font-size:.85rem;">
                                Vous pourrez modifier la photo plus tard depuis le profil du client.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
// Aperçu de l'avatar avant upload (optionnel)
document.getElementById('avatar')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Vous pouvez ajouter un aperçu ici si vous le souhaitez
        console.log('Fichier sélectionné:', file.name);
    }
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/customer/create.blade.php ENDPATH**/ ?>