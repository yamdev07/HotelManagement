<?php $__env->startSection('title', 'Modifier le client - ' . $customer->name); ?>
<?php $__env->startSection('content'); ?>

<style>
/* ═══════════════════════════════════════════════════════════════
   DESIGN SYSTEM - MÊME STYLE QUE GESTION DES CLIENTS
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

.edit-page {
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

/* Boutons */
.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 500;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
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

.btn-outline-modern {
    background: white;
    color: var(--gray-700);
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
}

.btn-outline-modern:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-900);
    transform: translateY(-1px);
    text-decoration: none;
}

.btn-sm-modern {
    padding: 6px 14px;
    font-size: 0.813rem;
    border-radius: 8px;
}

.btn-warning-modern {
    background: linear-gradient(135deg, #d97706, #f59e0b);
    color: white;
    box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.3);
}

.btn-warning-modern:hover {
    background: linear-gradient(135deg, #b45309, #d97706);
    transform: translateY(-1px);
    box-shadow: 0 6px 8px -1px rgba(245, 158, 11, 0.4);
    color: white;
    text-decoration: none;
}

.btn-danger-modern {
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: white;
    box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.3);
}

.btn-danger-modern:hover {
    background: linear-gradient(135deg, #b91c1c, #dc2626);
    transform: translateY(-1px);
    box-shadow: 0 6px 8px -1px rgba(220, 38, 38, 0.4);
    color: white;
    text-decoration: none;
}

/* Alertes */
.alert-modern {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
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

.alert-info {
    background: var(--blue-50);
    border-color: var(--blue-200);
    color: var(--blue-800);
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

.alert-info .alert-icon {
    background: var(--blue-500);
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

/* Formulaire */
.form-card {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.form-header {
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    padding: 24px 28px;
    position: relative;
}

.form-header h2 {
    color: white;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-header p {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.875rem;
    margin: 0;
}

.form-body {
    padding: 32px;
}

.form-group {
    margin-bottom: 24px;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 8px;
}

.form-label i {
    color: var(--primary-500);
    width: 20px;
    margin-right: 6px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid var(--gray-200);
    border-radius: 10px;
    font-size: 0.875rem;
    transition: all 0.2s;
    background: white;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px var(--primary-100);
}

.form-control.is-invalid {
    border-color: #ef4444;
    background-color: #fef2f2;
}

.form-control.is-invalid:focus {
    box-shadow: 0 0 0 3px #fee2e2;
}

.form-control:disabled {
    background: var(--gray-50);
    color: var(--gray-500);
    border-color: var(--gray-200);
    cursor: not-allowed;
}

.form-select {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid var(--gray-200);
    border-radius: 10px;
    font-size: 0.875rem;
    transition: all 0.2s;
    background: white;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
}

.form-select:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px var(--primary-100);
}

.form-select.is-invalid {
    border-color: #ef4444;
    background-color: #fef2f2;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23ef4444' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
}

textarea.form-control {
    min-height: 100px;
    resize: vertical;
}

/* File input */
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
    border: 1px dashed var(--gray-300);
    border-radius: 10px;
    background: var(--gray-50);
    transition: all 0.2s;
    cursor: pointer;
}

.form-file-label:hover {
    border-color: var(--primary-500);
    background: var(--primary-50);
}

.form-file-icon {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-500);
    font-size: 1.25rem;
}

.form-file-text {
    flex: 1;
}

.form-file-text .filename {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--gray-700);
    margin-bottom: 2px;
}

.form-file-text .hint {
    font-size: 0.688rem;
    color: var(--gray-500);
}

/* Avatar preview */
.avatar-preview {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 16px;
    padding: 16px;
    background: var(--gray-50);
    border-radius: 12px;
    border: 1px solid var(--gray-200);
}

.avatar-preview img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: var(--shadow-md);
    object-fit: cover;
}

.avatar-preview-info h6 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 4px;
}

.avatar-preview-info p {
    font-size: 0.75rem;
    color: var(--gray-500);
    margin: 0;
}

/* Error messages */
.error-message {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 6px;
    font-size: 0.75rem;
    color: #ef4444;
}

.error-message i {
    font-size: 0.813rem;
}

/* Form actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid var(--gray-200);
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

/* Responsive */
@media (max-width: 768px) {
    .edit-page {
        padding: 16px;
    }
    
    .form-body {
        padding: 20px;
    }
    
    .avatar-preview {
        flex-direction: column;
        text-align: center;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .btn-modern {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="edit-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-custom fade-in">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home fa-xs me-1"></i>Dashboard</a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="<?php echo e(route('customer.index')); ?>">Clients</a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="<?php echo e(route('customer.show', $customer->id)); ?>"><?php echo e($customer->name); ?></a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Modifier</span>
    </div>

    <!-- En-tête -->
    <div class="page-header fade-in">
        <div class="header-title">
            <span class="header-icon">
                <i class="fas fa-user-edit"></i>
            </span>
            <h1>Modifier le client</h1>
        </div>
        <p class="header-subtitle">Mettez à jour les informations du client #<?php echo e($customer->id); ?></p>
    </div>

    <!-- Messages d'alerte -->
    <?php if(session('success')): ?>
    <div class="alert-modern alert-success fade-in">
        <div class="alert-icon">
            <i class="fas fa-check"></i>
        </div>
        <div class="flex-grow-1"><?php echo session('success'); ?></div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
    <div class="alert-modern alert-danger fade-in">
        <div class="alert-icon">
            <i class="fas fa-exclamation"></i>
        </div>
        <div class="flex-grow-1">
            <strong>Erreur de validation</strong>
            <ul class="mb-0 mt-2">
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

    <div class="row justify-content-md-center">
        <div class="col-lg-8">
            <!-- Formulaire -->
            <div class="form-card fade-in">
                <div class="form-header">
                    <h2>
                        <i class="fas fa-user"></i>
                        Informations du client
                    </h2>
                    <p>Modifiez les informations ci-dessous</p>
                </div>
                
                <div class="form-body">
                    <form method="POST" action="<?php echo e(route('customer.update', ['customer' => $customer->id])); ?>" enctype="multipart/form-data">
                        <?php echo method_field('PUT'); ?>
                        <?php echo csrf_field(); ?>
                        
                        <!-- Avatar preview -->
                        <?php
                            $avatarUrl = $customer->user ? $customer->user->getAvatar() : null;
                        ?>
                        
                        <div class="avatar-preview">
                            <?php if($avatarUrl): ?>
                            <img src="<?php echo e($avatarUrl); ?>" 
                                 alt="<?php echo e($customer->name); ?>"
                                 onerror="this.src='https://ui-avatars.com/api/?name=<?php echo e(urlencode($customer->name)); ?>&background=059669&color=fff&size=80'">
                            <?php else: ?>
                            <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($customer->name)); ?>&background=059669&color=fff&size=80" 
                                 alt="<?php echo e($customer->name); ?>">
                            <?php endif; ?>
                            <div class="avatar-preview-info">
                                <h6>Photo de profil actuelle</h6>
                                <p>Vous pouvez télécharger une nouvelle photo ci-dessous</p>
                            </div>
                        </div>
                        
                        <!-- Nom complet -->
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="fas fa-user"></i>
                                Nom complet <span class="text-danger">*</span>
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
                                   value="<?php echo e(old('name', $customer->name)); ?>"
                                   placeholder="Entrez le nom complet"
                                   required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <!-- Email (disabled) -->
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email
                            </label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email"
                                   name="email" 
                                   value="<?php echo e($customer->user->email ?? ''); ?>"
                                   disabled>
                            <small class="text-muted" style="font-size: 0.688rem; margin-top: 4px; display: block;">
                                <i class="fas fa-info-circle"></i>
                                L'email ne peut pas être modifié
                            </small>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Date de naissance -->
                                <div class="form-group">
                                    <label for="birthdate" class="form-label">
                                        <i class="fas fa-cake-candles"></i>
                                        Date de naissance
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
                                           value="<?php echo e(old('birthdate', $customer->birthdate)); ?>">
                                    <?php $__errorArgs = ['birthdate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Genre -->
                                <div class="form-group">
                                    <label for="gender" class="form-label">
                                        <i class="fas fa-venus-mars"></i>
                                        Genre
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
                                        <option value="" disabled selected>Sélectionnez le genre</option>
                                        <option value="Male" <?php echo e(old('gender', $customer->gender) == 'Male' ? 'selected' : ''); ?>>Male</option>
                                        <option value="Female" <?php echo e(old('gender', $customer->gender) == 'Female' ? 'selected' : ''); ?>>Female</option>
                                        <option value="Other" <?php echo e(old('gender', $customer->gender) == 'Other' ? 'selected' : ''); ?>>Other</option>
                                    </select>
                                    <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Profession -->
                                <div class="form-group">
                                    <label for="job" class="form-label">
                                        <i class="fas fa-briefcase"></i>
                                        Profession
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
                                           value="<?php echo e(old('job', $customer->job)); ?>"
                                           placeholder="Ex: Ingénieur, Médecin, etc.">
                                    <?php $__errorArgs = ['job'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Téléphone -->
                                <div class="form-group">
                                    <label for="phone" class="form-label">
                                        <i class="fas fa-phone"></i>
                                        Téléphone
                                    </label>
                                    <input type="tel" 
                                           class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="phone" 
                                           name="phone"
                                           value="<?php echo e(old('phone', $customer->phone)); ?>"
                                           placeholder="Ex: +229 97 40 20 34">
                                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Adresse -->
                        <div class="form-group">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt"></i>
                                Adresse
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
                                      placeholder="Adresse complète"><?php echo e(old('address', $customer->address)); ?></textarea>
                            <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <!-- Photo de profil -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-camera"></i>
                                Photo de profil
                            </label>
                            <div class="form-file">
                                <input type="file" class="form-file-input" id="avatar" name="avatar" accept="image/*">
                                <div class="form-file-label" id="fileLabel">
                                    <div class="form-file-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="form-file-text">
                                        <div class="filename" id="fileName">Aucun fichier choisi</div>
                                        <div class="hint">JPG, PNG ou GIF (max. 2 Mo)</div>
                                    </div>
                                </div>
                            </div>
                            <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <!-- Actions -->
                        <div class="form-actions">
                            <a href="<?php echo e(route('customer.show', $customer->id)); ?>" class="btn-modern btn-outline-modern">
                                <i class="fas fa-times me-2"></i>
                                Annuler
                            </a>
                            <button type="submit" class="btn-modern btn-primary-modern">
                                <i class="fas fa-save me-2"></i>
                                Enregistrer les modifications
                            </button>
                        </div>
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

    // Preview du nom de fichier pour l'upload
    const fileInput = document.getElementById('avatar');
    const fileName = document.getElementById('fileName');
    
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                fileName.textContent = this.files[0].name;
                
                // Optionnel: afficher une preview de l'image
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Vous pouvez ajouter une preview ici si souhaité
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                fileName.textContent = 'Aucun fichier choisi';
            }
        });
    }

    // Raccourcis clavier
    document.addEventListener('keydown', function(e) {
        // Ctrl+S pour sauvegarder
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            document.querySelector('form').submit();
        }
        
        // Esc pour annuler
        if (e.key === 'Escape') {
            const cancelBtn = document.querySelector('a[href*="customer"]');
            if (cancelBtn && !e.target.matches('input, textarea, select')) {
                window.location.href = cancelBtn.href;
            }
        }
        
        // ? pour aide
        if (e.key === '?' && !e.ctrlKey && !e.metaKey && !e.target.matches('input, textarea, select')) {
            e.preventDefault();
            Swal.fire({
                title: 'Raccourcis clavier',
                html: `
                    <div style="text-align: left;">
                        <p><kbd>Ctrl+S</kbd> : Enregistrer</p>
                        <p><kbd>Esc</kbd> : Annuler</p>
                    </div>
                `,
                icon: 'info',
                confirmButtonColor: '#10b981'
            });
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/customer/edit.blade.php ENDPATH**/ ?>