
<?php $__env->startSection('title', 'Nombre de personnes'); ?>
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

.count-person-page {
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
.count-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .8rem; color: var(--s400);
    margin-bottom: 20px;
}
.count-breadcrumb a {
    color: var(--s400); text-decoration: none;
    transition: var(--transition);
}
.count-breadcrumb a:hover { color: var(--g600); }
.count-breadcrumb .sep { color: var(--s300); }
.count-breadcrumb .current { color: var(--s600); font-weight: 500; }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.count-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.count-brand { display: flex; align-items: center; gap: 14px; }
.count-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.count-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.count-header-title em { font-style: normal; color: var(--g600); }
.count-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.count-header-sub i { color: var(--g500); }
.count-header-actions { display: flex; align-items: center; gap: 10px; }

/* ══════════════════════════════════════════════
   PROGRESS BAR
══════════════════════════════════════════════ */
.progress-container {
    margin-bottom: 30px;
}
.progress-steps {
    display: flex; justify-content: space-between; position: relative;
    margin-bottom: 20px;
}
.progress-steps::before {
    content: ''; position: absolute; top: 20px; left: 0; right: 0;
    height: 2px; background: var(--s200); z-index: 1;
}
.progress-step {
    position: relative; z-index: 2; text-align: center; flex: 1;
}
.step-circle {
    width: 40px; height: 40px; border-radius: 50%;
    background: var(--white); border: 2px solid var(--s200);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 8px; font-weight: 600; color: var(--s600);
    transition: var(--transition);
}
.step-active .step-circle {
    background: var(--g600); border-color: var(--g600);
    color: white;
}
.step-completed .step-circle {
    background: var(--g500); border-color: var(--g500);
    color: white;
}
.step-label {
    font-size: .75rem; color: var(--s400); font-weight: 500;
}
.step-active .step-label {
    color: var(--g600); font-weight: 600;
}

/* ══════════════════════════════════════════════
   CARTES
══════════════════════════════════════════════ */
.count-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 20px; box-shadow: var(--shadow-sm);
}
.count-card-body {
    padding: 24px;
}

/* ══════════════════════════════════════════════
   FORMULAIRE
══════════════════════════════════════════════ */
.form-group {
    margin-bottom: 20px;
}
.form-label {
    font-size: .75rem; font-weight: 600; color: var(--s600);
    margin-bottom: 6px; display: flex; align-items: center; gap: 6px;
    text-transform: uppercase; letter-spacing: .5px;
}
.form-label i { font-size: .7rem; color: var(--g500); }
.form-control {
    width: 100%; padding: 12px 16px;
    border: 1.5px solid var(--s200); border-radius: var(--r);
    font-size: .875rem; font-family: var(--font);
    transition: var(--transition); background: var(--white);
}
.form-control:focus {
    outline: none; border-color: var(--g400);
    box-shadow: 0 0 0 3px var(--g100);
}
.form-control.is-invalid {
    border-color: #b91c1c; background: #fee2e2;
}
.error-message {
    display: flex; align-items: center; gap: 4px;
    font-size: .7rem; color: #b91c1c; margin-top: 4px;
}
.error-message i { font-size: .65rem; }

/* ══════════════════════════════════════════════
   BOUTONS
══════════════════════════════════════════════ */
.btn-db {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 10px 24px; border-radius: var(--r);
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
   PROFIL CLIENT
══════════════════════════════════════════════ */
.profile-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.profile-image {
    width: 100%; height: auto; display: block;
}
.profile-info {
    padding: 20px;
}
.profile-table {
    width: 100%; border-collapse: collapse;
}
.profile-table tr {
    border-bottom: 1px solid var(--s100);
}
.profile-table tr:last-child {
    border-bottom: none;
}
.profile-table td {
    padding: 10px 0;
    font-size: .85rem;
    color: var(--s600);
}
.profile-table td:first-child {
    width: 40px; text-align: center; color: var(--g500);
}
.profile-icon {
    font-size: 1rem;
}

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media(max-width:768px){
    .count-person-page{ padding: 20px; }
    .count-header{ flex-direction: column; align-items: flex-start; }
    .profile-table td{ font-size: .8rem; }
}
</style>

<div class="count-person-page">
    <!-- Breadcrumb -->
    <div class="count-breadcrumb anim-1">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="<?php echo e(route('transaction.reservation.createIdentity')); ?>">Création client</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Dates et personnes</span>
    </div>

    <!-- Header -->
    <div class="count-header anim-2">
        <div class="count-brand">
            <div class="count-brand-icon"><i class="fas fa-calendar-alt"></i></div>
            <div>
                <h1 class="count-header-title">Dates et <em>personnes</em></h1>
                <p class="count-header-sub">
                    <i class="fas fa-user me-1"></i> Étape 2/4 · Sélection des dates
                </p>
            </div>
        </div>
        <div class="count-header-actions">
            <a href="<?php echo e(route('transaction.reservation.createIdentity')); ?>" class="btn-db btn-db-ghost">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="progress-container anim-3">
        <div class="progress-steps">
            <div class="progress-step step-completed">
                <div class="step-circle"><i class="fas fa-check"></i></div>
                <div class="step-label">Identité</div>
            </div>
            <div class="progress-step step-active">
                <div class="step-circle">2</div>
                <div class="step-label">Dates</div>
            </div>
            <div class="progress-step">
                <div class="step-circle">3</div>
                <div class="step-label">Chambre</div>
            </div>
            <div class="progress-step">
                <div class="step-circle">4</div>
                <div class="step-label">Confirmation</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Formulaire -->
        <div class="col-md-8">
            <div class="count-card anim-4">
                <div class="count-card-body">
                    <form method="GET" action="<?php echo e(route('transaction.reservation.chooseRoom', ['customer' => $customer->id])); ?>">
                        
                        <!-- Nombre de personnes -->
                        <div class="form-group">
                            <label for="count_person" class="form-label">
                                <i class="fas fa-users"></i>
                                Nombre de personnes
                            </label>
                            <input type="number" 
                                   class="form-control <?php $__errorArgs = ['count_person'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="count_person" 
                                   name="count_person" 
                                   value="<?php echo e(old('count_person', 1)); ?>"
                                   min="1"
                                   max="10"
                                   placeholder="Ex: 2"
                                   required>
                            <?php $__errorArgs = ['count_person'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text" style="font-size:.7rem; color:var(--s400); margin-top:4px;">
                                <i class="fas fa-info-circle"></i> Maximum 10 personnes
                            </div>
                        </div>

                        <!-- Date d'arrivée -->
                        <div class="form-group">
                            <label for="check_in" class="form-label">
                                <i class="fas fa-sign-in-alt"></i>
                                Date d'arrivée
                            </label>
                            <input type="date" 
                                   class="form-control <?php $__errorArgs = ['check_in'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="check_in" 
                                   name="check_in" 
                                   value="<?php echo e(old('check_in', now()->format('Y-m-d'))); ?>"
                                   min="<?php echo e(now()->format('Y-m-d')); ?>"
                                   required>
                            <?php $__errorArgs = ['check_in'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Date de départ -->
                        <div class="form-group">
                            <label for="check_out" class="form-label">
                                <i class="fas fa-sign-out-alt"></i>
                                Date de départ
                            </label>
                            <input type="date" 
                                   class="form-control <?php $__errorArgs = ['check_out'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="check_out" 
                                   name="check_out" 
                                   value="<?php echo e(old('check_out', now()->addDays(1)->format('Y-m-d'))); ?>"
                                   min="<?php echo e(now()->addDays(1)->format('Y-m-d')); ?>"
                                   required>
                            <?php $__errorArgs = ['check_out'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Bouton suivant -->
                        <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
                            <button type="submit" class="btn-db btn-db-primary">
                                <i class="fas fa-arrow-right me-2"></i>
                                Voir les chambres disponibles
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Profil client -->
        <div class="col-md-4">
            <div class="profile-card anim-5">
                <img src="<?php echo e($customer->user->getAvatar()); ?>" class="profile-image" alt="<?php echo e($customer->name); ?>">
                <div class="profile-info">
                    <h5 style="font-size:1rem; font-weight:600; color:var(--s800); margin-bottom:12px;">
                        <i class="fas fa-user-circle me-2" style="color:var(--g500);"></i>
                        <?php echo e($customer->name); ?>

                    </h5>
                    
                    <table class="profile-table">
                        <tr>
                            <td class="profile-icon"><i class="fas <?php echo e($customer->gender == 'Male' ? 'fa-male' : 'fa-female'); ?>"></i></td>
                            <td><?php echo e($customer->gender == 'Male' ? 'Masculin' : ($customer->gender == 'Female' ? 'Féminin' : 'Autre')); ?></td>
                        </tr>
                        <?php if($customer->job): ?>
                        <tr>
                            <td class="profile-icon"><i class="fas fa-briefcase"></i></td>
                            <td><?php echo e($customer->job); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($customer->birthdate): ?>
                        <tr>
                            <td class="profile-icon"><i class="fas fa-birthday-cake"></i></td>
                            <td><?php echo e(\Carbon\Carbon::parse($customer->birthdate)->format('d/m/Y')); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($customer->phone): ?>
                        <tr>
                            <td class="profile-icon"><i class="fas fa-phone"></i></td>
                            <td><?php echo e($customer->phone); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($customer->email): ?>
                        <tr>
                            <td class="profile-icon"><i class="fas fa-envelope"></i></td>
                            <td><?php echo e($customer->email); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($customer->address): ?>
                        <tr>
                            <td class="profile-icon"><i class="fas fa-map-marker-alt"></i></td>
                            <td><?php echo e($customer->address); ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>

                    <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid var(--s100);">
                        <small class="text-muted" style="color:var(--s400);">
                            <i class="fas fa-clock me-1"></i>
                            Créé le <?php echo e($customer->created_at->format('d/m/Y')); ?>

                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkIn = document.getElementById('check_in');
    const checkOut = document.getElementById('check_out');
    
    // Valider que la date de départ est après la date d'arrivée
    checkIn.addEventListener('change', function() {
        checkOut.min = this.value;
        if (checkOut.value && checkOut.value < this.value) {
            checkOut.value = this.value;
        }
    });
    
    checkOut.addEventListener('change', function() {
        if (this.value < checkIn.value) {
            this.value = checkIn.value;
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/transaction/reservation/viewCountPerson.blade.php ENDPATH**/ ?>