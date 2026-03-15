<form id="form-save-roomstatus" method="POST" action="<?php echo e(route('roomstatus.update', ['roomstatus' => $roomstatus->id])); ?>">
    <?php echo method_field('PUT'); ?>
    <?php echo csrf_field(); ?>
    
    <div style="display: flex; flex-direction: column; gap: 20px;">
        <!-- Nom -->
        <div style="display: flex; flex-direction: column;">
            <label for="name" style="font-size: .75rem; font-weight: 600; color: var(--s600); margin-bottom: 6px; display: flex; align-items: center; gap: 6px; text-transform: uppercase; letter-spacing: .5px;">
                <i class="fas fa-tag" style="font-size: .7rem; color: var(--g500);"></i>
                Nom du statut
            </label>
            <input type="text" 
                   class="form-control-db <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                   id="name"
                   name="name" 
                   value="<?php echo e($roomstatus->name); ?>"
                   style="padding: 10px 14px; border-radius: var(--r); border: 1.5px solid var(--s200); font-size: .875rem; font-family: var(--font); transition: var(--transition); background: var(--white); width: 100%;"
                   onfocus="this.style.outline='none'; this.style.borderColor='var(--g400)'; this.style.boxShadow='0 0 0 3px var(--g100)'"
                   onblur="this.style.borderColor='var(--s200)'; this.style.boxShadow='none'">
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div style="display: flex; align-items: center; gap: 4px; font-size: .7rem; color: #b91c1c; margin-top: 4px;">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo e($message); ?>

                </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <div id="error_name" style="display: flex; align-items: center; gap: 4px; font-size: .7rem; color: #b91c1c; margin-top: 4px;"></div>
        </div>

        <!-- Code -->
        <div style="display: flex; flex-direction: column;">
            <label for="code" style="font-size: .75rem; font-weight: 600; color: var(--s600); margin-bottom: 6px; display: flex; align-items: center; gap: 6px; text-transform: uppercase; letter-spacing: .5px;">
                <i class="fas fa-code" style="font-size: .7rem; color: var(--g500);"></i>
                Code
            </label>
            <input type="text" 
                   class="form-control-db <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   id="code" 
                   name="code" 
                   value="<?php echo e($roomstatus->code); ?>"
                   style="padding: 10px 14px; border-radius: var(--r); border: 1.5px solid var(--s200); font-size: .875rem; font-family: var(--mono); transition: var(--transition); background: var(--white); width: 100%;"
                   onfocus="this.style.outline='none'; this.style.borderColor='var(--g400)'; this.style.boxShadow='0 0 0 3px var(--g100)'"
                   onblur="this.style.borderColor='var(--s200)'; this.style.boxShadow='none'">
            <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div style="display: flex; align-items: center; gap: 4px; font-size: .7rem; color: #b91c1c; margin-top: 4px;">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo e($message); ?>

                </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <div id="error_code" style="display: flex; align-items: center; gap: 4px; font-size: .7rem; color: #b91c1c; margin-top: 4px;"></div>
            <div style="font-size: .65rem; color: var(--s400); margin-top: 4px;">
                <i class="fas fa-info-circle"></i> Code unique (ex: AVBL, OCC, MNT)
            </div>
        </div>

        <!-- Information -->
        <div style="display: flex; flex-direction: column;">
            <label for="information" style="font-size: .75rem; font-weight: 600; color: var(--s600); margin-bottom: 6px; display: flex; align-items: center; gap: 6px; text-transform: uppercase; letter-spacing: .5px;">
                <i class="fas fa-info-circle" style="font-size: .7rem; color: var(--g500);"></i>
                Information
            </label>
            <textarea 
                class="form-control-db" 
                id="information" 
                name="information" 
                rows="3"
                style="padding: 10px 14px; border-radius: var(--r); border: 1.5px solid var(--s200); font-size: .875rem; font-family: var(--font); transition: var(--transition); background: var(--white); width: 100%; resize: vertical;"
                onfocus="this.style.outline='none'; this.style.borderColor='var(--g400)'; this.style.boxShadow='0 0 0 3px var(--g100)'"
                onblur="this.style.borderColor='var(--s200)'; this.style.boxShadow='none'"><?php echo e($roomstatus->information); ?></textarea>
            <?php $__errorArgs = ['information'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div style="display: flex; align-items: center; gap: 4px; font-size: .7rem; color: #b91c1c; margin-top: 4px;">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo e($message); ?>

                </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <div id="error_information" style="display: flex; align-items: center; gap: 4px; font-size: .7rem; color: #b91c1c; margin-top: 4px;"></div>
            <div style="font-size: .65rem; color: var(--s400); margin-top: 4px;">
                <i class="fas fa-info-circle"></i> Description du statut
            </div>
        </div>
    </div>
</form>

<style>
/* Styles additionnels pour le formulaire */
.form-control-db:focus {
    outline: none;
    border-color: var(--g400);
    box-shadow: 0 0 0 3px var(--g100);
}

.form-control-db.is-invalid {
    border-color: #b91c1c;
    background: #fee2e2;
}

.form-control-db.is-invalid:focus {
    border-color: #b91c1c;
    box-shadow: 0 0 0 3px rgba(185, 28, 28, 0.1);
}

/* Animation pour les messages d'erreur */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.text-danger {
    animation: shake 0.3s ease-in-out;
}
</style>

<script>
// Validation en temps réel (optionnel)
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('code');
    const infoInput = document.getElementById('information');
    
    const errorName = document.getElementById('error_name');
    const errorCode = document.getElementById('error_code');
    const errorInfo = document.getElementById('error_information');
    
    // Validation du nom
    nameInput.addEventListener('input', function() {
        if (this.value.trim().length < 2) {
            errorName.innerHTML = '<i class="fas fa-exclamation-circle"></i> Le nom doit contenir au moins 2 caractères';
            this.classList.add('is-invalid');
        } else {
            errorName.innerHTML = '';
            this.classList.remove('is-invalid');
        }
    });
    
    // Validation du code
    codeInput.addEventListener('input', function() {
        const codeRegex = /^[A-Z0-9]{2,10}$/;
        if (!codeRegex.test(this.value.trim())) {
            errorCode.innerHTML = '<i class="fas fa-exclamation-circle"></i> Le code doit contenir 2-10 caractères majuscules ou chiffres';
            this.classList.add('is-invalid');
        } else {
            errorCode.innerHTML = '';
            this.classList.remove('is-invalid');
        }
    });
    
    // Validation de l'information (optionnel)
    infoInput.addEventListener('input', function() {
        if (this.value.trim().length > 0 && this.value.trim().length < 5) {
            errorInfo.innerHTML = '<i class="fas fa-exclamation-circle"></i> L\'information est trop courte (minimum 5 caractères)';
            this.classList.add('is-invalid');
        } else {
            errorInfo.innerHTML = '';
            this.classList.remove('is-invalid');
        }
    });
});
</script><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/roomstatus/edit.blade.php ENDPATH**/ ?>