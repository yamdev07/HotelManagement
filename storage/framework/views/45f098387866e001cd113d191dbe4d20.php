<?php $__env->startSection('title', 'Restaurant - Gestion du Stock'); ?>
<?php $__env->startSection('content'); ?>

<?php echo $__env->make('restaurant.partials.nav-tabs', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0">Gestion du Stock</h3>
        <small class="text-muted">Suivi des ingrédients & alertes de rupture</small>
    </div>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addIngredientModal">
        <i class="fas fa-plus me-2"></i>Ajouter un ingrédient
    </button>
</div>

<!-- Statistiques stock -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total ingrédients</h6>
                        <h3 class="mb-0 text-primary"><?php echo e($ingredients->count()); ?></h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="fas fa-boxes fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Stock faible</h6>
                        <h3 class="mb-0 text-warning"><?php echo e($lowStockCount); ?></h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Rupture de stock</h6>
                        <h3 class="mb-0 text-danger"><?php echo e($outOfStockCount); ?></h3>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-3 rounded">
                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alertes stock faible -->
<?php $lowItems = $ingredients->filter(fn($i) => $i->isLowStock()); ?>
<?php if($lowItems->count() > 0): ?>
<div class="alert alert-warning border-0 shadow-sm mb-4" role="alert">
    <div class="d-flex align-items-center mb-2">
        <i class="fas fa-bell me-2"></i>
        <strong><?php echo e($lowItems->count()); ?> alerte(s) de stock</strong>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <?php $__currentLoopData = $lowItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <span class="badge <?php echo e($item->quantity_in_stock <= 0 ? 'bg-danger' : 'bg-warning text-dark'); ?> px-3 py-2">
            <i class="fas fa-<?php echo e($item->quantity_in_stock <= 0 ? 'times' : 'exclamation'); ?> me-1"></i>
            <?php echo e($item->name); ?> — <?php echo e($item->quantity_in_stock); ?> <?php echo e($item->unit); ?>

            <?php echo e($item->quantity_in_stock <= 0 ? '(RUPTURE)' : '(min: '.$item->min_stock.')'); ?>

        </span>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>

<!-- Tableau des ingrédients -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-list me-2 text-muted"></i>Inventaire</h5>
        <input type="text" id="searchIngredient" class="form-control form-control-sm w-auto" placeholder="Rechercher...">
    </div>
    <div class="card-body p-0">
        <?php if($ingredients->isEmpty()): ?>
        <div class="text-center py-5 text-muted">
            <i class="fas fa-box-open fa-3x mb-3"></i>
            <p>Aucun ingrédient enregistré. Ajoutez votre premier ingrédient.</p>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="ingredientsTable">
                <thead class="table-light">
                    <tr>
                        <th>Ingrédient</th>
                        <th>Unité</th>
                        <th>Stock actuel</th>
                        <th>Stock minimum</th>
                        <th>Prix unitaire</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $ingredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="ingredient-row">
                        <td>
                            <strong class="ingredient-name"><?php echo e($ingredient->name); ?></strong>
                        </td>
                        <td><span class="text-muted"><?php echo e($ingredient->unit); ?></span></td>
                        <td>
                            <span class="fw-bold <?php echo e($ingredient->quantity_in_stock <= 0 ? 'text-danger' : ($ingredient->isLowStock() ? 'text-warning' : 'text-success')); ?>">
                                <?php echo e(number_format($ingredient->quantity_in_stock, 2, ',', ' ')); ?>

                            </span>
                        </td>
                        <td><?php echo e(number_format($ingredient->min_stock, 2, ',', ' ')); ?></td>
                        <td>
                            <?php if($ingredient->price_per_unit): ?>
                            <?php echo e(number_format($ingredient->price_per_unit, 0, ',', ' ')); ?> FCFA
                            <?php else: ?>
                            <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($ingredient->quantity_in_stock <= 0): ?>
                                <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Rupture</span>
                            <?php elseif($ingredient->isLowStock()): ?>
                                <span class="badge bg-warning text-dark"><i class="fas fa-exclamation me-1"></i>Faible</span>
                            <?php else: ?>
                                <span class="badge bg-success"><i class="fas fa-check me-1"></i>OK</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary me-1"
                                onclick="openRestockModal(<?php echo e($ingredient->id); ?>, '<?php echo e(addslashes($ingredient->name)); ?>', <?php echo e($ingredient->quantity_in_stock); ?>, '<?php echo e($ingredient->unit); ?>', <?php echo e($ingredient->min_stock); ?>, <?php echo e($ingredient->price_per_unit ?? 0); ?>)"
                                title="Modifier / Réapprovisionner">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger"
                                onclick="deleteIngredient(<?php echo e($ingredient->id); ?>, '<?php echo e(addslashes($ingredient->name)); ?>')"
                                title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal : Ajouter ingrédient -->
<div class="modal fade" id="addIngredientModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2 text-success"></i>Ajouter un ingrédient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addIngredientForm">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nom de l'ingrédient <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required placeholder="ex: Riz, Poulet, Tomates...">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold">Unité <span class="text-danger">*</span></label>
                            <select class="form-select" name="unit" required>
                                <option value="kg">Kilogramme (kg)</option>
                                <option value="g">Gramme (g)</option>
                                <option value="L">Litre (L)</option>
                                <option value="cL">Centilitre (cL)</option>
                                <option value="unité" selected>Unité</option>
                                <option value="boîte">Boîte</option>
                                <option value="bouteille">Bouteille</option>
                                <option value="sachet">Sachet</option>
                                <option value="portion">Portion</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold">Quantité en stock <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="quantity_in_stock" min="0" step="0.01" value="0" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold">Stock minimum <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="min_stock" min="0" step="0.01" value="1" required>
                            <small class="text-muted">Seuil d'alerte</small>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold">Prix unitaire (FCFA)</label>
                            <input type="number" class="form-control" name="price_per_unit" min="0" step="1" placeholder="Optionnel">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" onclick="submitAddIngredient()">
                    <i class="fas fa-save me-2"></i>Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal : Modifier / Réapprovisionner -->
<div class="modal fade" id="restockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2 text-primary"></i>Modifier le stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="restockForm">
                    <input type="hidden" id="restockId">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nom</label>
                        <input type="text" class="form-control" id="restockName" name="name" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold">Unité</label>
                            <select class="form-select" id="restockUnit" name="unit" required>
                                <option value="kg">Kilogramme (kg)</option>
                                <option value="g">Gramme (g)</option>
                                <option value="L">Litre (L)</option>
                                <option value="cL">Centilitre (cL)</option>
                                <option value="unité">Unité</option>
                                <option value="boîte">Boîte</option>
                                <option value="bouteille">Bouteille</option>
                                <option value="sachet">Sachet</option>
                                <option value="portion">Portion</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold">Quantité en stock</label>
                            <input type="number" class="form-control" id="restockQty" name="quantity_in_stock" min="0" step="0.01" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold">Stock minimum</label>
                            <input type="number" class="form-control" id="restockMin" name="min_stock" min="0" step="0.01" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold">Prix unitaire (FCFA)</label>
                            <input type="number" class="form-control" id="restockPrice" name="price_per_unit" min="0" step="1">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="submitRestock()">
                    <i class="fas fa-save me-2"></i>Mettre à jour
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// Recherche dans le tableau
document.getElementById('searchIngredient').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.ingredient-row').forEach(row => {
        const name = row.querySelector('.ingredient-name').textContent.toLowerCase();
        row.style.display = name.includes(q) ? '' : 'none';
    });
});

// Ajouter un ingrédient
function submitAddIngredient() {
    const form = document.getElementById('addIngredientForm');
    const data = new FormData(form);

    fetch('<?php echo e(route("restaurant.stock.store")); ?>', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: data,
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            Swal.fire({ icon: 'success', title: res.message, timer: 1500, showConfirmButton: false });
            setTimeout(() => location.reload(), 1600);
        } else {
            Swal.fire({ icon: 'error', title: 'Erreur', text: res.message });
        }
    });
}

// Ouvrir modal de modification
function openRestockModal(id, name, qty, unit, minStock, price) {
    document.getElementById('restockId').value = id;
    document.getElementById('restockName').value = name;
    document.getElementById('restockQty').value = qty;
    document.getElementById('restockMin').value = minStock;
    document.getElementById('restockPrice').value = price || '';
    const sel = document.getElementById('restockUnit');
    for (let opt of sel.options) { if (opt.value === unit) { opt.selected = true; break; } }
    new bootstrap.Modal(document.getElementById('restockModal')).show();
}

// Mettre à jour le stock
function submitRestock() {
    const id = document.getElementById('restockId').value;
    const data = new FormData(document.getElementById('restockForm'));

    fetch(`/restaurant/stock/${id}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'X-HTTP-Method-Override': 'PUT' },
        body: data,
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            Swal.fire({ icon: 'success', title: res.message, timer: 1500, showConfirmButton: false });
            setTimeout(() => location.reload(), 1600);
        } else {
            Swal.fire({ icon: 'error', title: 'Erreur', text: res.message });
        }
    });
}

// Supprimer un ingrédient
function deleteIngredient(id, name) {
    Swal.fire({
        title: 'Supprimer cet ingrédient ?',
        text: name,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'Annuler',
        confirmButtonText: 'Supprimer',
    }).then(result => {
        if (!result.isConfirmed) return;
        fetch(`/restaurant/stock/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                Swal.fire({ icon: 'success', title: 'Supprimé !', timer: 1200, showConfirmButton: false });
                setTimeout(() => location.reload(), 1300);
            }
        });
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\HotelManagement\resources\views/restaurant/stock.blade.php ENDPATH**/ ?>