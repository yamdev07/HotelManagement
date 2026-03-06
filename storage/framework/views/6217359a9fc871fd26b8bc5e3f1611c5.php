<?php $__env->startSection('title', 'Add User'); ?>

<?php $__env->startSection('content'); ?>
    <div class="user-form-container">
        <div class="container-fluid">
            <!-- Header Section -->
            <div class="page-header mb-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h1 class="page-title">Ajouter un utilisateur</h1>
                        <p class="page-subtitle">Créer un nouveau compte utilisateur dans le système</p>
                    </div>
                    <a href="<?php echo e(route('user.index')); ?>" class="btn-back">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour
                    </a>
                </div>
            </div>

            <!-- Form Card -->
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-7">
                    <div class="form-card">
                        <div class="form-card-header">
                            <div class="header-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div>
                                <h2 class="form-card-title">Informations de l'utilisateur</h2>
                                <p class="form-card-subtitle">Veuillez remplir tous les champs obligatoires</p>
                            </div>
                        </div>

                        <div class="form-card-body">
                            <form method="POST" action="<?php echo e(route('user.store')); ?>" class="modern-form" id="userForm">
                                <?php echo csrf_field(); ?>
                                
                                <!-- Section Informations personnelles -->
                                <div class="form-section">
                                    <div class="section-header">
                                        <i class="fas fa-user-circle"></i>
                                        <h3>Informations personnelles</h3>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-12">
                                            <div class="form-group-modern">
                                                <label for="name" class="form-label-modern">
                                                    Nom complet
                                                    <span class="required">*</span>
                                                </label>
                                                <div class="input-with-icon">
                                                    <i class="fas fa-user input-icon"></i>
                                                    <input 
                                                        type="text" 
                                                        class="form-control-modern <?php $__errorArgs = ['name'];
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
                                                        placeholder="Ex: Jean Dupont"
                                                        autocomplete="name">
                                                </div>
                                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="error-message">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        <?php echo e($message); ?>

                                                    </div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section Identifiants -->
                                <div class="form-section">
                                    <div class="section-header">
                                        <i class="fas fa-lock"></i>
                                        <h3>Identifiants de connexion</h3>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="form-group-modern">
                                                <label for="email" class="form-label-modern">
                                                    Adresse email
                                                    <span class="required">*</span>
                                                </label>
                                                <div class="input-with-icon">
                                                    <i class="fas fa-envelope input-icon"></i>
                                                    <input 
                                                        type="email" 
                                                        class="form-control-modern <?php $__errorArgs = ['email'];
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
                                                        placeholder="exemple@hotel.com"
                                                        autocomplete="email">
                                                </div>
                                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="error-message">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        <?php echo e($message); ?>

                                                    </div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group-modern">
                                                <label for="password" class="form-label-modern">
                                                    Mot de passe
                                                    <span class="required">*</span>
                                                </label>
                                                <div class="input-with-icon password-field">
                                                    <i class="fas fa-key input-icon"></i>
                                                    <input 
                                                        type="password" 
                                                        class="form-control-modern <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                        id="password"
                                                        name="password" 
                                                        placeholder="Minimum 8 caractères"
                                                        autocomplete="new-password">
                                                    <button type="button" class="toggle-password" onclick="togglePassword()">
                                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                                    </button>
                                                </div>
                                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="error-message">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        <?php echo e($message); ?>

                                                    </div>
                                                <?php else: ?>
                                                    <div class="form-hint">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Le mot de passe doit contenir au moins 8 caractères
                                                    </div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section Rôle -->
                                <div class="form-section">
                                    <div class="section-header">
                                        <i class="fas fa-user-tag"></i>
                                        <h3>Rôle et permissions</h3>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-12">
                                            <div class="form-group-modern">
                                                <label for="role" class="form-label-modern">
                                                    Rôle de l'utilisateur
                                                    <span class="required">*</span>
                                                </label>
                                                <div class="input-with-icon">
                                                    <i class="fas fa-briefcase input-icon"></i>
                                                    <select 
                                                        id="role" 
                                                        name="role" 
                                                        class="form-select-modern <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                        <option value="" selected disabled>Sélectionner un rôle...</option>
                                                        <option value="Admin" <?php if(old('role') == 'Admin'): ?> selected <?php endif; ?>>
                                                            👑 Administrateur
                                                        </option>
                                                        <option value="Receptionist" <?php if(old('role') == 'Receptionist'): ?> selected <?php endif; ?>>
                                                            🎯 Réceptionniste
                                                        </option>
                                                        <option value="Housekeeping" <?php if(old('role') == 'Housekeeping'): ?> selected <?php endif; ?>>
                                                            🧹 Femme de ménage
                                                        </option>
                                                        <option value="Customer" <?php if(old('role') == 'Customer'): ?> selected <?php endif; ?>>
                                                            👤 Client
                                                        </option>
                                                    </select>
                                                </div>
                                                <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="error-message">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        <?php echo e($message); ?>

                                                    </div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                
                                                <!-- Role Description -->
                                                <div class="role-description" id="roleDescription" style="display: none;">
                                                    <div class="alert-info">
                                                        <i class="fas fa-info-circle"></i>
                                                        <div id="roleDescriptionText"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="form-actions">
                                    <button type="button" class="btn-secondary" onclick="window.history.back()">
                                        <i class="fas fa-times me-2"></i>
                                        Annuler
                                    </button>
                                    <button type="submit" class="btn-primary">
                                        <i class="fas fa-check me-2"></i>
                                        Créer l'utilisateur
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="info-card mt-4">
                        <div class="info-card-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div class="info-card-content">
                            <h4>Conseils de sécurité</h4>
                            <ul>
                                <li>Utilisez un mot de passe fort et unique</li>
                                <li>Attribuez le rôle approprié selon les responsabilités</li>
                                <li>Vérifiez l'adresse email avant la création</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ========================================
   VARIABLES & BASE
   ======================================== */
:root {
    --primary-color: #1A472A;
    --primary-light: #2E5C3F;
    --primary-dark: #0F2918;
    --accent-color: #C9A961;
    --success-color: #22C55E;
    --danger-color: #EF4444;
    --warning-color: #F59E0B;
    --info-color: #3B82F6;
    
    --bg-light: #F8FAF9;
    --bg-white: #FFFFFF;
    --text-dark: #1A1A1A;
    --text-gray: #6B7280;
    --text-light: #9CA3AF;
    --border-color: #E5E7EB;
    
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --shadow-sm: 0 2px 8px rgba(26, 71, 42, 0.08);
    --shadow-md: 0 8px 24px rgba(26, 71, 42, 0.12);
    --shadow-lg: 0 16px 48px rgba(26, 71, 42, 0.16);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

body {
    background: var(--bg-light);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

.user-form-container {
    padding: 30px 0;
    min-height: 100vh;
}

/* ========================================
   PAGE HEADER
   ======================================== */
.page-header {
    margin-bottom: 30px;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 5px;
    letter-spacing: -0.5px;
}

.page-subtitle {
    font-size: 0.95rem;
    color: var(--text-gray);
    margin: 0;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    background: var(--bg-white);
    color: var(--text-dark);
    text-decoration: none;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-color);
    font-size: 0.9rem;
    font-weight: 500;
    transition: var(--transition);
}

.btn-back:hover {
    background: var(--primary-color);
    color: var(--bg-white);
    border-color: var(--primary-color);
    transform: translateX(-3px);
}

/* ========================================
   FORM CARD
   ======================================== */
.form-card {
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    margin-bottom: 30px;
}

.form-card-header {
    padding: 30px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--bg-white);
    display: flex;
    align-items: center;
    gap: 20px;
}

.header-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
}

.form-card-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
}

.form-card-subtitle {
    font-size: 0.9rem;
    margin: 5px 0 0 0;
    opacity: 0.9;
}

.form-card-body {
    padding: 40px 30px;
}

/* ========================================
   FORM SECTIONS
   ======================================== */
.form-section {
    margin-bottom: 35px;
    padding-bottom: 35px;
    border-bottom: 2px solid var(--bg-light);
}

.form-section:last-of-type {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 25px;
}

.section-header i {
    color: var(--primary-color);
    font-size: 20px;
}

.section-header h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0;
}

/* ========================================
   FORM GROUPS
   ======================================== */
.form-group-modern {
    margin-bottom: 0;
}

.form-label-modern {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 10px;
}

.required {
    color: var(--danger-color);
    margin-left: 3px;
}

/* Input with Icon */
.input-with-icon {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 16px;
    color: var(--text-gray);
    font-size: 14px;
    pointer-events: none;
    z-index: 2;
}

.form-control-modern,
.form-select-modern {
    width: 100%;
    padding: 14px 16px 14px 45px;
    border: 2px solid var(--border-color);
    border-radius: var(--radius-sm);
    font-size: 0.95rem;
    color: var(--text-dark);
    background: var(--bg-white);
    transition: var(--transition);
    outline: none;
}

.form-control-modern:focus,
.form-select-modern:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(26, 71, 42, 0.1);
}

.form-control-modern::placeholder {
    color: var(--text-light);
}

.form-control-modern.is-invalid,
.form-select-modern.is-invalid {
    border-color: var(--danger-color);
    padding-right: 45px;
}

.form-control-modern.is-invalid:focus,
.form-select-modern.is-invalid:focus {
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
}

/* Password Field */
.password-field {
    position: relative;
}

.toggle-password {
    position: absolute;
    right: 16px;
    background: none;
    border: none;
    color: var(--text-gray);
    cursor: pointer;
    padding: 0;
    font-size: 14px;
    transition: var(--transition);
    z-index: 2;
}

.toggle-password:hover {
    color: var(--primary-color);
}

/* Error Message */
.error-message {
    display: flex;
    align-items: center;
    color: var(--danger-color);
    font-size: 0.85rem;
    margin-top: 8px;
    padding: 8px 12px;
    background: rgba(239, 68, 68, 0.05);
    border-radius: var(--radius-sm);
    border-left: 3px solid var(--danger-color);
}

/* Form Hint */
.form-hint {
    display: flex;
    align-items: center;
    color: var(--text-gray);
    font-size: 0.85rem;
    margin-top: 8px;
}

/* Role Description */
.role-description {
    margin-top: 15px;
}

.alert-info {
    display: flex;
    align-items: start;
    gap: 12px;
    padding: 15px;
    background: rgba(59, 130, 246, 0.05);
    border-left: 3px solid var(--info-color);
    border-radius: var(--radius-sm);
    font-size: 0.9rem;
    color: var(--text-dark);
}

.alert-info i {
    color: var(--info-color);
    font-size: 16px;
    margin-top: 2px;
    flex-shrink: 0;
}

/* ========================================
   FORM ACTIONS
   ======================================== */
.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid var(--bg-light);
}

.btn-primary,
.btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 30px;
    font-size: 0.95rem;
    font-weight: 500;
    border-radius: var(--radius-sm);
    border: none;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--bg-white);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-secondary {
    background: var(--bg-white);
    color: var(--text-dark);
    border: 2px solid var(--border-color);
}

.btn-secondary:hover {
    background: var(--bg-light);
    border-color: var(--text-gray);
}

/* ========================================
   INFO CARD
   ======================================== */
.info-card {
    background: linear-gradient(135deg, #FEF3C7, #FDE68A);
    border-radius: var(--radius-lg);
    padding: 25px;
    display: flex;
    gap: 20px;
    border: 2px solid #FCD34D;
}

.info-card-icon {
    width: 50px;
    height: 50px;
    background: var(--warning-color);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--bg-white);
    font-size: 20px;
    flex-shrink: 0;
}

.info-card-content h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 10px;
}

.info-card-content ul {
    margin: 0;
    padding-left: 20px;
}

.info-card-content li {
    font-size: 0.9rem;
    color: var(--text-dark);
    margin-bottom: 5px;
}

/* ========================================
   RESPONSIVE
   ======================================== */
@media (max-width: 768px) {
    .user-form-container {
        padding: 20px 0;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 15px;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .form-card-header {
        flex-direction: column;
        text-align: center;
        padding: 25px 20px;
    }
    
    .form-card-body {
        padding: 30px 20px;
    }
    
    .form-actions {
        flex-direction: column-reverse;
    }
    
    .btn-primary,
    .btn-secondary {
        width: 100%;
    }
    
    .info-card {
        flex-direction: column;
        text-align: center;
    }
    
    .info-card-icon {
        margin: 0 auto;
    }
}

/* ========================================
   ANIMATIONS
   ======================================== */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-card {
    animation: fadeInUp 0.5s ease-out;
}

.form-section {
    animation: fadeInUp 0.5s ease-out;
    animation-fill-mode: both;
}

.form-section:nth-child(1) { animation-delay: 0.1s; }
.form-section:nth-child(2) { animation-delay: 0.2s; }
.form-section:nth-child(3) { animation-delay: 0.3s; }

/* Custom Select Arrow */
.form-select-modern {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236B7280' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    padding-right: 45px;
}

.form-select-modern:focus {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%231A472A' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Role descriptions
    const roleDescriptions = {
        'Admin': 'Accès complet au système avec tous les privilèges administratifs. Peut gérer les utilisateurs, les réservations, et configurer le système.',
        'Receptionist': 'Gère les réservations, l\'accueil des clients, et les opérations quotidiennes de la réception.',
        'Housekeeping': 'Responsable de l\'entretien des chambres et du nettoyage de l\'établissement.',
        'Customer': 'Accès limité pour effectuer des réservations et consulter son historique.'
    };
    
    // Show role description on select
    const roleSelect = document.getElementById('role');
    const roleDescriptionDiv = document.getElementById('roleDescription');
    const roleDescriptionText = document.getElementById('roleDescriptionText');
    
    if (roleSelect) {
        roleSelect.addEventListener('change', function() {
            const selectedRole = this.value;
            
            if (selectedRole && roleDescriptions[selectedRole]) {
                roleDescriptionText.textContent = roleDescriptions[selectedRole];
                roleDescriptionDiv.style.display = 'block';
                roleDescriptionDiv.style.animation = 'fadeInUp 0.3s ease-out';
            } else {
                roleDescriptionDiv.style.display = 'none';
            }
        });
        
        // Show description on page load if role is selected
        if (roleSelect.value && roleDescriptions[roleSelect.value]) {
            roleDescriptionText.textContent = roleDescriptions[roleSelect.value];
            roleDescriptionDiv.style.display = 'block';
        }
    }
    
    // Form validation enhancement
    const form = document.getElementById('userForm');
    const inputs = form.querySelectorAll('.form-control-modern, .form-select-modern');
    
    inputs.forEach(input => {
        // Add success state on valid input
        input.addEventListener('blur', function() {
            if (this.value.trim() !== '' && !this.classList.contains('is-invalid')) {
                this.style.borderColor = 'var(--success-color)';
                
                setTimeout(() => {
                    if (!this.classList.contains('is-invalid')) {
                        this.style.borderColor = '';
                    }
                }, 2000);
            }
        });
        
        // Remove invalid state on input
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
            }
        });
    });
    
    // Password strength indicator
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            
            // You can add a visual password strength indicator here
            if (password.length > 0) {
                this.setAttribute('data-strength', strength);
            } else {
                this.removeAttribute('data-strength');
            }
        });
    }
    
    // Email validation
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.style.borderColor = 'var(--warning-color)';
            }
        });
    }
    
    // Form submission with loading state
    form.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Création en cours...';
    });
});

// Toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Calculate password strength
function calculatePasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    if (strength <= 2) return 'weak';
    if (strength <= 4) return 'medium';
    return 'strong';
}

// Auto-focus first input
window.addEventListener('load', function() {
    const firstInput = document.getElementById('name');
    if (firstInput) {
        setTimeout(() => firstInput.focus(), 300);
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/user/create.blade.php ENDPATH**/ ?>