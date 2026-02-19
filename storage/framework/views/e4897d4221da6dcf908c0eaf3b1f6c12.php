
<?php $__env->startSection('title', 'Mon Profil'); ?>
<?php $__env->startSection('content'); ?>

<style>
/* ═══════════════════════════════════════════════════════════════
   DESIGN SYSTEM - MÊME STYLE QUE CHECK-IN
═══════════════════════════════════════════════════════════════════ */
:root {
    --primary-50: #ecfdf5;
    --primary-100: #d1fae5;
    --primary-400: #34d399;
    --primary-500: #10b981;
    --primary-600: #059669;
    --primary-700: #047857;
    --primary-800: #065f46;

    --amber-50: #fffbeb;
    --amber-100: #fef3c7;
    --amber-400: #fbbf24;
    --amber-500: #f59e0b;
    --amber-600: #d97706;

    --blue-50: #eff6ff;
    --blue-100: #dbeafe;
    --blue-500: #3b82f6;
    --blue-600: #2563eb;

    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;

    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
}

* { box-sizing: border-box; }

.profile-page {
    background: var(--gray-50);
    min-height: 100vh;
    padding: 24px 32px;
    font-family: 'Inter', system-ui, sans-serif;
}

/* Breadcrumb */
.breadcrumb-custom {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.813rem;
    color: var(--gray-400);
    margin-bottom: 24px;
    flex-wrap: wrap;
}

.breadcrumb-custom a {
    color: var(--gray-400);
    text-decoration: none;
    transition: color 0.2s;
}

.breadcrumb-custom a:hover {
    color: var(--primary-600);
}

.breadcrumb-custom .separator {
    color: var(--gray-300);
    font-size: 0.688rem;
}

.breadcrumb-custom .current {
    color: var(--gray-600);
    font-weight: 500;
}

/* En-tête */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 16px;
}

.header-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-title h1 {
    font-size: 1.875rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
}

.header-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 10px rgba(5, 150, 105, 0.3);
}

.header-subtitle {
    color: var(--gray-500);
    font-size: 0.875rem;
    margin: 6px 0 0 60px;
}

.date-display {
    background: white;
    padding: 8px 16px;
    border-radius: 12px;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
}

.date-display .day {
    font-size: 0.75rem;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.date-display .time {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-800);
    line-height: 1.2;
}

/* Boutons */
.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 500;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    width: 100%;
    justify-content: center;
}

.btn-primary-modern {
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    color: white;
    box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.3);
}

.btn-primary-modern:hover {
    background: linear-gradient(135deg, var(--primary-800), var(--primary-600));
    transform: translateY(-1px);
    box-shadow: 0 6px 8px -1px rgba(5, 150, 105, 0.4);
    color: white;
    text-decoration: none;
}

.btn-success-modern {
    background: var(--primary-600);
    color: white;
}

.btn-success-modern:hover {
    background: var(--primary-700);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(5, 150, 105, 0.3);
}

.btn-warning-modern {
    background: var(--amber-500);
    color: white;
}

.btn-warning-modern:hover {
    background: var(--amber-600);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(245, 158, 11, 0.3);
}

/* Cartes */
.card-modern {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.2s;
    height: 100%;
}

.card-modern:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--gray-300);
}

.card-header-modern {
    padding: 20px 24px;
    border-bottom: 1px solid var(--gray-100);
    background: white;
}

.card-header-modern h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-header-modern h4 i {
    color: var(--primary-500);
}

.card-body-modern {
    padding: 24px;
}

/* Avatar */
.avatar-container {
    text-align: center;
    padding: 24px;
}

.avatar-image {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 16px;
}

.avatar-upload {
    margin-top: 16px;
}

.file-input {
    width: 100%;
    padding: 10px;
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    font-size: 0.875rem;
    color: var(--gray-700);
    background: white;
    margin-bottom: 12px;
}

.file-input:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px var(--primary-100);
}

/* Formulaire */
.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--gray-500);
    margin-bottom: 6px;
    letter-spacing: 0.5px;
}

.form-control-modern {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    font-size: 0.875rem;
    color: var(--gray-700);
    transition: all 0.2s;
    background: white;
}

.form-control-modern:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px var(--primary-100);
}

.form-control-modern:read-only {
    background: var(--gray-50);
    color: var(--gray-500);
}

/* Rôle badge */
.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    background: var(--primary-100);
    color: var(--primary-700);
    border: 1px solid var(--primary-200);
    margin-bottom: 20px;
}

/* Alertes */
.alert-modern {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    border: 1px solid transparent;
    font-size: 0.875rem;
    background: white;
    box-shadow: var(--shadow-md);
}

.alert-success {
    background: var(--primary-50);
    border-color: var(--primary-200);
    color: var(--primary-800);
}

.alert-danger {
    background: #fee2e2;
    border-color: #fecaca;
    color: #b91c1c;
}

.alert-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.alert-success .alert-icon {
    background: var(--primary-500);
    color: white;
}

.alert-danger .alert-icon {
    background: #ef4444;
    color: white;
}

.alert-close {
    margin-left: auto;
    background: none;
    border: none;
    color: currentColor;
    opacity: 0.5;
    cursor: pointer;
    padding: 4px;
    font-size: 0.9rem;
    transition: all 0.2s;
}

.alert-close:hover {
    opacity: 1;
}

/* Info ligne */
.info-line {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid var(--gray-100);
}

.info-line:last-child {
    border-bottom: none;
}

.info-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: var(--primary-100);
    color: var(--primary-600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.info-content {
    flex: 1;
}

.info-label {
    font-size: 0.688rem;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}

.info-value {
    font-size: 0.938rem;
    font-weight: 500;
    color: var(--gray-800);
}

/* Password strength */
.password-strength {
    margin-top: 8px;
    height: 4px;
    border-radius: 2px;
    background: var(--gray-200);
    overflow: hidden;
}

.strength-bar {
    height: 100%;
    width: 0;
    transition: all 0.3s;
}

.strength-weak { width: 33.33%; background: #ef4444; }
.strength-medium { width: 66.66%; background: #f59e0b; }
.strength-strong { width: 100%; background: var(--primary-500); }

/* Responsive */
@media (max-width: 768px) {
    .profile-page {
        padding: 16px;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .date-display {
        width: 100%;
    }
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeIn 0.3s ease-out forwards;
}
</style>

<div class="profile-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-custom">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home fa-xs me-1"></i>Dashboard</a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Mon Profil</span>
    </div>

    <!-- En-tête avec date/heure -->
    <div class="page-header">
        <div>
            <div class="header-title">
                <span class="header-icon">
                    <i class="fas fa-user-circle"></i>
                </span>
                <h1>Bonjour, <?php echo e(auth()->user()->name); ?> !</h1>
            </div>
            <p class="header-subtitle">Gérez vos informations personnelles et votre mot de passe</p>
        </div>
        
        <div class="date-display">
            <div class="day"><?php echo e(now()->translatedFormat('l, j F Y')); ?></div>
            <div class="time"><?php echo e(now()->format('H:i')); ?></div>
        </div>
    </div>

    <!-- Messages d'alerte -->
    <?php if(session('success')): ?>
    <div class="alert-modern alert-success">
        <div class="alert-icon">
            <i class="fas fa-check"></i>
        </div>
        <div class="flex-grow-1"><?php echo e(session('success')); ?></div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="alert-modern alert-danger">
        <div class="alert-icon">
            <i class="fas fa-exclamation"></i>
        </div>
        <div class="flex-grow-1"><?php echo e(session('error')); ?></div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
    <div class="alert-modern alert-danger">
        <div class="alert-icon">
            <i class="fas fa-exclamation"></i>
        </div>
        <div class="flex-grow-1">
            <ul class="mb-0 ps-3">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Colonne gauche - Photo de profil -->
        <div class="col-lg-4">
            <div class="card-modern">
                <div class="avatar-container">
                    <img src="<?php echo e($user->avatar ?? '/img/default-avatar.png'); ?>" 
                         alt="<?php echo e($user->name); ?>" 
                         class="avatar-image"
                         onerror="this.src='https://ui-avatars.com/api/?name=<?php echo e(urlencode($user->name)); ?>&background=059669&color=fff&size=140'">
                    
                    <div class="role-badge">
                        <i class="fas fa-shield-alt"></i>
                        <?php echo e($user->role); ?>

                    </div>

                    <form action="<?php echo e(route('profile.update.avatar')); ?>" method="POST" enctype="multipart/form-data" class="avatar-upload">
                        <?php echo csrf_field(); ?>
                        <input type="file" name="avatar" class="file-input" accept="image/*" id="avatarInput" required>
                        <button type="submit" class="btn-modern btn-primary-modern">
                            <i class="fas fa-upload me-2"></i>
                            Changer la photo
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Colonne droite - Informations -->
        <div class="col-lg-8">
            <!-- Informations du compte -->
            <div class="card-modern mb-4">
                <div class="card-header-modern">
                    <h4>
                        <i class="fas fa-id-card"></i>
                        Informations du compte
                    </h4>
                </div>
                <div class="card-body-modern">
                    <form action="<?php echo e(route('profile.update.info')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nom complet</label>
                                    <input type="text" 
                                           name="name" 
                                           class="form-control-modern <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           value="<?php echo e(old('name', $user->name)); ?>"
                                           required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" 
                                           name="email" 
                                           class="form-control-modern <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           value="<?php echo e(old('email', $user->email)); ?>"
                                           required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" 
                                           name="phone" 
                                           class="form-control-modern <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           value="<?php echo e(old('phone', $user->phone)); ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Rôle</label>
                                    <input type="text" 
                                           class="form-control-modern" 
                                           value="<?php echo e($user->role); ?>"
                                           readonly>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-modern btn-success-modern mt-3">
                            <i class="fas fa-save me-2"></i>
                            Mettre à jour les informations
                        </button>
                    </form>
                </div>
            </div>

            <!-- Changer le mot de passe -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h4>
                        <i class="fas fa-lock"></i>
                        Changer le mot de passe
                    </h4>
                </div>
                <div class="card-body-modern">
                    <form action="<?php echo e(route('profile.update.password')); ?>" method="POST" id="passwordForm">
                        <?php echo csrf_field(); ?>
                        
                        <div class="form-group">
                            <label class="form-label">Mot de passe actuel</label>
                            <input type="password" 
                                   name="current_password" 
                                   class="form-control-modern <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   required
                                   placeholder="Entrez votre mot de passe actuel">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nouveau mot de passe</label>
                                    <input type="password" 
                                           name="password" 
                                           id="password"
                                           class="form-control-modern <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           required
                                           placeholder="Minimum 6 caractères">
                                    <div class="password-strength">
                                        <div class="strength-bar" id="strengthBar"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Confirmer le mot de passe</label>
                                    <input type="password" 
                                           name="password_confirmation" 
                                           id="passwordConfirm"
                                           class="form-control-modern" 
                                           required
                                           placeholder="Confirmez le nouveau mot de passe">
                                    <small class="text-muted" id="passwordMatch"></small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-3" style="background: var(--blue-50); border: 1px solid var(--blue-200); color: var(--blue-800);">
                            <i class="fas fa-info-circle me-2"></i>
                            Le mot de passe doit contenir au moins 6 caractères.
                        </div>
                        
                        <button type="submit" class="btn-modern btn-warning-modern mt-3">
                            <i class="fas fa-key me-2"></i>
                            Modifier le mot de passe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss des alertes après 5 secondes
    const alerts = document.querySelectorAll('.alert-modern');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // Vérification de la force du mot de passe
    const password = document.getElementById('password');
    const strengthBar = document.getElementById('strengthBar');
    const passwordConfirm = document.getElementById('passwordConfirm');
    const matchMessage = document.getElementById('passwordMatch');

    if (password) {
        password.addEventListener('input', function() {
            const value = this.value;
            let strength = 0;

            // Vérifier la longueur (min 6 caractères)
            if (value.length >= 6) strength += 1;
            // Vérifier les minuscules
            if (/[a-z]/.test(value)) strength += 1;
            // Vérifier les majuscules
            if (/[A-Z]/.test(value)) strength += 1;
            // Vérifier les chiffres
            if (/[0-9]/.test(value)) strength += 1;

            // Mettre à jour la barre
            strengthBar.className = 'strength-bar';
            if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
            } else if (strength <= 3) {
                strengthBar.classList.add('strength-medium');
            } else {
                strengthBar.classList.add('strength-strong');
            }
        });
    }

    // Vérifier la correspondance des mots de passe
    if (passwordConfirm) {
        passwordConfirm.addEventListener('input', function() {
            if (password.value !== this.value) {
                matchMessage.innerHTML = '<i class="fas fa-times-circle text-danger me-1"></i> Les mots de passe ne correspondent pas';
                matchMessage.style.color = '#ef4444';
            } else {
                matchMessage.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i> Les mots de passe correspondent';
                matchMessage.style.color = '#10b981';
            }
        });
    }

    // Aperçu de l'avatar avant upload
    const avatarInput = document.getElementById('avatarInput');
    const avatarImage = document.querySelector('.avatar-image');

    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Validation avant soumission
    const passwordForm = document.getElementById('passwordForm');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            if (password.value !== passwordConfirm.value) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas !');
            }
            
            if (password.value.length < 6) {
                e.preventDefault();
                alert('Le mot de passe doit contenir au moins 6 caractères !');
            }
        });
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/profile/index.blade.php ENDPATH**/ ?>