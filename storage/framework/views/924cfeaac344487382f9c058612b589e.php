
<?php $__env->startSection('title', 'Create Identity'); ?>
<?php $__env->startSection('head'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('style/css/progress-indication.css')); ?>">
    <style>
        .email-info {
            background-color: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .existing-customer-info {
            background-color: #e7f1ff;
            border-left: 4px solid #0d6efd;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: none;
        }
        .reservation-count-badge {
            background-color: #0d6efd;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            margin-left: 5px;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('transaction.reservation.progressbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="container mt-3">
        <div class="row justify-content-md-center">
            <div class="col-lg-12">
                <div class="card shadow-sm border">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0">
                            <i class="fas fa-user-plus me-2"></i>
                            Add Customer Information
                        </h2>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Information sur la politique d'email -->
                        <div class="email-info">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            <strong>Important:</strong> Same email address can be used for multiple reservations. 
                            If customer already exists, their information will be updated.
                        </div>
                        
                        <!-- Affichage des informations client existant (via AJAX) -->
                        <div id="existingCustomerInfo" class="existing-customer-info">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-check fa-2x text-primary me-3"></i>
                                <div>
                                    <h5 class="mb-1">Existing Customer Found</h5>
                                    <p class="mb-1" id="customerDetails"></p>
                                    <small class="text-muted">
                                        <span id="reservationCount">0</span> existing reservation(s)
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <?php if(session('info')): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <?php echo e(session('info')); ?>

                            </div>
                        <?php endif; ?>
                        
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <form class="row g-3" method="POST" action="<?php echo e(route('transaction.reservation.storeCustomer')); ?>"
                            enctype="multipart/form-data" id="customerForm">
                            <?php echo csrf_field(); ?>
                            
                            <!-- Champ Email avec vérification AJAX -->
                            <div class="col-md-12 mb-3">
                                <label for="email" class="form-label fw-bold">
                                    Email Address 
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="email" name="email" value="<?php echo e(old('email')); ?>" 
                                       placeholder="example@domain.com" required
                                       onblur="checkExistingCustomer()">
                                <div class="form-text">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Enter customer's email address. System will check if customer already exists.
                                </div>
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <!-- Champ Nom -->
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label fw-bold">
                                    Full Name 
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="name" name="name" value="<?php echo e(old('name')); ?>" 
                                       placeholder="John Doe" required>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <!-- Date de naissance -->
                            <div class="col-md-6 mb-3">
                                <label for="birthdate" class="form-label fw-bold">
                                    Date of Birth
                                </label>
                                <input type="date" class="form-control <?php $__errorArgs = ['birthdate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="birthdate" name="birthdate" value="<?php echo e(old('birthdate')); ?>">
                                <?php $__errorArgs = ['birthdate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <!-- Genre -->
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label fw-bold">
                                    Gender 
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" <?php echo e(old('gender') == 'Male' ? 'selected' : ''); ?>>Male</option>
                                    <option value="Female" <?php echo e(old('gender') == 'Female' ? 'selected' : ''); ?>>Female</option>
                                    <option value="Other" <?php echo e(old('gender') == 'Other' ? 'selected' : ''); ?>>Other</option>
                                </select>
                                <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <!-- Téléphone -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label fw-bold">
                                    Phone Number 
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="phone" name="phone" value="<?php echo e(old('phone')); ?>" 
                                       placeholder="+1234567890" required>
                                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <!-- Profession -->
                            <div class="col-md-6 mb-3">
                                <label for="job" class="form-label fw-bold">Profession</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['job'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="job" name="job" value="<?php echo e(old('job')); ?>" 
                                       placeholder="Software Engineer">
                                <?php $__errorArgs = ['job'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <!-- Adresse -->
                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label fw-bold">Address</label>
                                <textarea class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                          id="address" name="address" rows="3" 
                                          placeholder="Street, City, Country"><?php echo e(old('address')); ?></textarea>
                                <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <!-- Photo de profil -->
                            <div class="col-md-12 mb-4">
                                <label for="avatar" class="form-label fw-bold">Profile Picture (Optional)</label>
                                <div class="input-group">
                                    <input class="form-control <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           type="file" name="avatar" id="avatar" accept="image/*">
                                    <button class="btn btn-outline-secondary" type="button" onclick="clearAvatar()">
                                        <i class="fas fa-times"></i> Clear
                                    </button>
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-image me-1"></i>
                                    Upload customer's photo (JPEG, PNG, max 2MB)
                                </div>
                                <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                
                                <!-- Aperçu de l'image -->
                                <div id="avatarPreview" class="mt-2" style="display: none;">
                                    <img id="previewImage" src="#" alt="Preview" 
                                         class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                </div>
                            </div>
                            
                            <!-- Boutons -->
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="<?php echo e(route('dashboard.index')); ?>" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-2"></i>
                                        Save & Continue
                                        <span id="submitText">to Room Selection</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>
                                    Customer Information - Step 1 of 4
                                </small>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">
                                    Next: Select Dates & Room
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script>
// Fonction pour vérifier si le client existe déjà
function checkExistingCustomer() {
    const emailInput = document.getElementById('email');
    const email = emailInput.value;
    
    if (!email || !validateEmail(email)) {
        hideExistingCustomerInfo();
        return;
    }
    
    // Afficher un indicateur de chargement
    const submitButton = document.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Checking...';
    submitButton.disabled = true;
    
    // Effectuer la requête AJAX
    fetch('<?php echo e(route("transaction.reservation.searchByEmail")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            // Afficher les informations du client existant
            showExistingCustomerInfo(data);
            
            // Pré-remplir les champs du formulaire
            if (!document.getElementById('name').value) {
                document.getElementById('name').value = data.customer.name;
            }
            
            // Mettre à jour le texte du bouton
            document.getElementById('submitText').innerHTML = 
                'Update & Continue <span class="reservation-count-badge">' + 
                data.customer.reservation_count + ' existing</span>';
        } else {
            hideExistingCustomerInfo();
            document.getElementById('submitText').innerHTML = 'Save & Continue to Room Selection';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        hideExistingCustomerInfo();
    })
    .finally(() => {
        // Restaurer le bouton
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
}

// Fonction pour afficher les informations du client existant
function showExistingCustomerInfo(data) {
    const infoDiv = document.getElementById('existingCustomerInfo');
    const customerDetails = document.getElementById('customerDetails');
    const reservationCount = document.getElementById('reservationCount');
    
    customerDetails.innerHTML = 
        `<strong>${data.customer.name}</strong> | ${data.customer.email} | ${data.customer.phone}`;
    reservationCount.textContent = data.customer.reservation_count;
    
    infoDiv.style.display = 'block';
}

// Fonction pour masquer les informations du client existant
function hideExistingCustomerInfo() {
    document.getElementById('existingCustomerInfo').style.display = 'none';
    document.getElementById('submitText').innerHTML = 'Save & Continue to Room Selection';
}

// Fonction de validation d'email
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Prévisualisation de l'avatar
document.getElementById('avatar').addEventListener('change', function(e) {
    const preview = document.getElementById('avatarPreview');
    const previewImage = document.getElementById('previewImage');
    
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            preview.style.display = 'block';
        }
        
        reader.readAsDataURL(this.files[0]);
    } else {
        preview.style.display = 'none';
    }
});

// Effacer l'avatar sélectionné
function clearAvatar() {
    document.getElementById('avatar').value = '';
    document.getElementById('avatarPreview').style.display = 'none';
}

// Validation du formulaire avant soumission
document.getElementById('customerForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value;
    const name = document.getElementById('name').value;
    
    if (!validateEmail(email)) {
        e.preventDefault();
        alert('Please enter a valid email address.');
        document.getElementById('email').focus();
        return false;
    }
    
    if (!name.trim()) {
        e.preventDefault();
        alert('Please enter customer name.');
        document.getElementById('name').focus();
        return false;
    }
    
    // Vérifier si on veut confirmer pour un client existant
    const existingCustomerDiv = document.getElementById('existingCustomerInfo');
    if (existingCustomerDiv.style.display === 'block') {
        if (!confirm('Customer already exists. Do you want to update their information and create a new reservation?')) {
            e.preventDefault();
            return false;
        }
    }
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si un email est déjà présent
    const emailValue = document.getElementById('email').value;
    if (emailValue && validateEmail(emailValue)) {
        setTimeout(() => {
            checkExistingCustomer();
        }, 500);
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/transaction/reservation/createIdentity.blade.php ENDPATH**/ ?>