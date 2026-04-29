
<?php $__env->startSection('title', 'Restaurant - Nouveau Menu'); ?>
<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Ajouter un Menu</h3>
    <a href="<?php echo e(route('restaurant.index')); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="<?php echo e(route('restaurant.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nom du menu <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="name" name="name" value="<?php echo e(old('name')); ?>" required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="category" class="form-label">Catégorie <span class="text-danger">*</span></label>
                    <select class="form-select <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            id="category" name="category" required>
                        <option value="">Sélectionner une catégorie</option>
                        <option value="plat" <?php echo e(old('category') == 'plat' ? 'selected' : ''); ?>>Plat principal</option>
                        <option value="entree" <?php echo e(old('category') == 'entree' ? 'selected' : ''); ?>>Entrée</option>
                        <option value="dessert" <?php echo e(old('category') == 'dessert' ? 'selected' : ''); ?>>Dessert</option>
                        <option value="boisson" <?php echo e(old('category') == 'boisson' ? 'selected' : ''); ?>>Boisson</option>
                        <option value="aperitif" <?php echo e(old('category') == 'aperitif' ? 'selected' : ''); ?>>Apéritif</option>
                        <option value="salade" <?php echo e(old('category') == 'salade' ? 'selected' : ''); ?>>Salade</option>
                    </select>
                    <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Prix (FCFA) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="1" class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="price" name="price" value="<?php echo e(old('price')); ?>" min="0" required>
                        <span class="input-group-text">FCFA</span>
                    </div>
                    <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="image" class="form-label">Image du menu</label>
                    <input type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="image" name="image" accept="image/*">
                    <small class="text-muted">Formats acceptés: JPG, PNG, GIF. Max: 2MB</small>
                    <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                          id="description" name="description" rows="4"><?php echo e(old('description')); ?></textarea>
                <small class="text-muted">Décrivez les ingrédients, la préparation, etc.</small>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Prévisualisation de l'image -->
            <div class="mb-3" id="imagePreviewContainer" style="display: none;">
                <label class="form-label">Prévisualisation</label>
                <div class="border rounded p-3 text-center">
                    <img id="imagePreview" src="#" alt="Prévisualisation" style="max-width: 200px; max-height: 200px;" class="img-fluid mb-2">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeImagePreview()">
                        <i class="fas fa-trash me-1"></i> Supprimer l'image
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="<?php echo e(route('restaurant.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Enregistrer le menu
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script>
// Prévisualisation de l'image
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreviewContainer').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});

function removeImagePreview() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').src = '#';
    document.getElementById('imagePreviewContainer').style.display = 'none';
}

// Validation du prix
document.getElementById('price').addEventListener('input', function(e) {
    let value = parseInt(e.target.value);
    if (value < 0 || isNaN(value)) e.target.value = 0;
    if (value > 9999999) e.target.value = 9999999;
});
</script>

<style>
.form-label {
    font-weight: 500;
}

.input-group-text {
    background-color: #f8f9fa;
}

#imagePreview {
    border-radius: 0.375rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\HotelManagement\resources\views/restaurant/create.blade.php ENDPATH**/ ?>