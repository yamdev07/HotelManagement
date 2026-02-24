

<?php $__env->startSection('title', 'Nouvelle Session de Caisse'); ?>

<?php $__env->startPush('styles'); ?>
<style>
:root {
    --primary: #3b82f6;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --dark: #1e293b;
    --light: #f8fafc;
    --border: #e2e8f0;
}

.create-session-container {
    max-width: 600px;
    margin: 2rem auto;
}

.session-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
}

.session-header {
    text-align: center;
    margin-bottom: 2rem;
}

.session-header .icon {
    width: 80px;
    height: 80px;
    background: rgba(16,185,129,0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2rem;
    color: var(--success);
}

.session-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.session-header p {
    color: #64748b;
    font-size: 0.875rem;
}

.form-label {
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.form-control {
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 0.625rem 1rem;
    font-size: 0.875rem;
    transition: all 0.2s;
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    outline: none;
}

.form-control.is-invalid {
    border-color: var(--danger);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23ef4444'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23ef4444' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1rem;
}

.btn-start {
    background: var(--success);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.875rem;
    font-weight: 600;
    font-size: 0.875rem;
    width: 100%;
    transition: all 0.2s;
    cursor: pointer;
}

.btn-start:hover:not(:disabled) {
    background: #059669;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16,185,129,0.2);
}

.btn-start:disabled {
    background: #cbd5e1;
    transform: none;
    box-shadow: none;
    cursor: not-allowed;
}

.btn-start i {
    margin-right: 0.5rem;
}

.info-box {
    background: var(--light);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 1rem;
    margin: 1.5rem 0;
}

.info-item {
    display: flex;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px dashed var(--border);
}

.info-item:last-child {
    border-bottom: none;
}

.info-item i {
    width: 24px;
    color: var(--primary);
    font-size: 1rem;
}

.info-item strong {
    margin-right: 0.5rem;
    color: var(--dark);
}

.session-rules {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border: 1px solid #fbbf24;
    border-radius: 12px;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
}

.session-rules h6 {
    color: #92400e;
    font-weight: 700;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

.session-rules h6 i {
    margin-right: 0.5rem;
    font-size: 1rem;
}

.session-rules ul {
    margin: 0;
    padding-left: 1.5rem;
    color: #92400e;
    font-size: 0.875rem;
}

.session-rules li {
    margin-bottom: 0.5rem;
}

.session-rules li:last-child {
    margin-bottom: 0;
}

.alert {
    border-radius: 10px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
    border: none;
    display: flex;
    align-items: center;
}

.alert-warning {
    background: #fef3c7;
    color: #92400e;
}

.alert-danger {
    background: #fee2e2;
    color: #991b1b;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
}

.alert i {
    margin-right: 0.75rem;
    font-size: 1.25rem;
}

.btn-close {
    margin-left: auto;
    background: transparent;
    border: none;
    font-size: 1.25rem;
    cursor: pointer;
    opacity: 0.5;
    transition: opacity 0.2s;
}

.btn-close:hover {
    opacity: 1;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.fa-spinner {
    animation: spin 1s linear infinite;
}

.badge-info {
    background: rgba(59,130,246,0.1);
    color: var(--primary);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: 0.5rem;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    <!-- Header -->
    <div class="cashier-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="cashier-title">
                    <i class="fas fa-cash-register me-2" style="color:var(--primary)"></i>
                    Nouvelle Session
                </h1>
            </div>
        </div>
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('dashboard.index')); ?>">
                        <i class="fas fa-home"></i> Accueil
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('cashier.dashboard')); ?>">Caissier</a>
                </li>
                <li class="breadcrumb-item active">Nouvelle session</li>
            </ol>
        </nav>
    </div>

    <div class="create-session-container">
        <!-- Messages de notification -->
        <?php if(session('warning')): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <?php echo e(session('warning')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert">&times;</button>
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-times-circle"></i>
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert">&times;</button>
        </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i>
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert">&times;</button>
        </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            Veuillez corriger les erreurs ci-dessous
            <ul class="mb-0 mt-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert">&times;</button>
        </div>
        <?php endif; ?>

        <!-- Règles de la session -->
        <div class="session-rules">
            <h6>
                <i class="fas fa-info-circle"></i>
                Règles importantes
            </h6>
            <ul>
                <li>Une seule session active à la fois par utilisateur</li>
                <li>Cette session enregistrera tous vos paiements</li>
                <li>Vous pourrez la clôturer à tout moment avec un rapport détaillé</li>
                <li>Le solde de départ est automatiquement à 0 FCFA</li>
            </ul>
        </div>

        <!-- Formulaire de création -->
        <div class="session-card">
            <div class="session-header">
                <div class="icon">
                    <i class="fas fa-play"></i>
                </div>
                <h2>Démarrer une nouvelle session</h2>
                <p>Ouvrez votre shift pour commencer à enregistrer les paiements</p>
            </div>

            <form action="<?php echo e(route('cashier.sessions.store')); ?>" method="POST" id="startSessionForm">
                <?php echo csrf_field(); ?>
                
                <!-- Informations de la session (lecture seule) -->
                <div class="info-box">
                    <h6 class="mb-3" style="color: var(--dark); font-weight: 600;">
                        <i class="fas fa-file-invoice me-2"></i>Informations de la session
                    </h6>
                    
                    <div class="info-item">
                        <i class="fas fa-user"></i>
                        <div>
                            <strong>Réceptionniste:</strong>
                            <span><?php echo e(auth()->user()->name); ?></span>
                            <span class="badge-info"><?php echo e(auth()->user()->role); ?></span>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-calendar"></i>
                        <div>
                            <strong>Date d'ouverture:</strong>
                            <span><?php echo e(now()->format('d/m/Y')); ?></span>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>Heure:</strong>
                            <span><?php echo e(now()->format('H:i:s')); ?></span>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-wallet"></i>
                        <div>
                            <strong>Solde de départ:</strong>
                            <span>0 FCFA</span>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-hashtag"></i>
                        <div>
                            <strong>Session #:</strong>
                            <span>Généré automatiquement</span>
                        </div>
                    </div>
                </div>

                <!-- Notes (optionnel) -->
                <div class="mb-4">
                    <label for="notes" class="form-label">
                        <i class="fas fa-sticky-note me-1" style="color:var(--primary)"></i>
                        Notes (optionnel)
                    </label>
                    <textarea name="notes" 
                              id="notes"
                              class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                              rows="3"
                              placeholder="Informations complémentaires (observations particulières...)"><?php echo e(old('notes')); ?></textarea>
                    <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Maximum 500 caractères
                    </small>
                </div>

                <!-- Boutons d'action -->
                <div class="d-flex gap-2 mt-4">
                    <a href="<?php echo e(route('cashier.dashboard')); ?>" class="btn btn-secondary flex-grow-1" style="padding: 0.875rem;">
                        <i class="fas fa-times me-2"></i>Annuler
                    </a>
                    <button type="submit" class="btn-start flex-grow-1" id="submitBtn">
                        <i class="fas fa-play me-2"></i>Démarrer la session
                    </button>
                </div>

                <!-- Note de sécurité -->
                <p class="text-center text-muted mt-3 mb-0" style="font-size: 0.75rem;">
                    <i class="fas fa-shield-alt me-1"></i>
                    Toutes les actions seront enregistrées avec votre nom
                </p>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function() {
    'use strict';
    
    const form = document.getElementById('startSessionForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (!form || !submitBtn) return;
    
    // Confirmation avant soumission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!confirm('Démarrer une nouvelle session ?')) {
            return;
        }
        
        // Désactiver le bouton
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Démarrage en cours...';
        
        // Soumettre le formulaire
        form.submit();
    });
    
})();
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/cashier/sessions/create.blade.php ENDPATH**/ ?>