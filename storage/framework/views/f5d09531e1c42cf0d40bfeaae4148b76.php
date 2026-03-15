
<?php $__env->startSection('title', 'Modifier le Type de Chambre'); ?>
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

.edit-type-page {
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
.edit-type-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .8rem; color: var(--s400);
    margin-bottom: 20px;
}
.edit-type-breadcrumb a {
    color: var(--s400); text-decoration: none;
    transition: var(--transition);
}
.edit-type-breadcrumb a:hover { color: var(--g600); }
.edit-type-breadcrumb .sep { color: var(--s300); }
.edit-type-breadcrumb .current { color: var(--s600); font-weight: 500; }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.edit-type-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.edit-type-brand { display: flex; align-items: center; gap: 14px; }
.edit-type-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.edit-type-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.edit-type-header-title em { font-style: normal; color: var(--g600); }
.edit-type-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.edit-type-header-sub i { color: var(--g500); }
.edit-type-header-actions { display: flex; align-items: center; gap: 10px; }

/* ══════════════════════════════════════════════
   BOUTONS
══════════════════════════════════════════════ */
.btn-db {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: var(--r);
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
.btn-db-warning {
    background: #fff3cd; color: #856404;
    border: 1.5px solid #ffeeba;
}
.btn-db-warning:hover {
    background: #ffe69c; color: #856404;
    transform: translateY(-1px);
}

/* ══════════════════════════════════════════════
   CARTE PRINCIPALE
══════════════════════════════════════════════ */
.edit-type-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 20px; box-shadow: var(--shadow-sm);
}
.edit-type-card-header {
    padding: 18px 24px;
    border-bottom: 1.5px solid var(--s100);
    background: var(--white);
}
.edit-type-card-title {
    display: flex; align-items: center; gap: 10px;
    font-size: .95rem; font-weight: 600; color: var(--s800); margin: 0;
}
.edit-type-card-title i { color: var(--g500); }
.edit-type-card-body { padding: 28px; }

/* ══════════════════════════════════════════════
   FORMULAIRE
══════════════════════════════════════════════ */
.form-grid {
    display: grid; grid-template-columns: repeat(2, 1fr);
    gap: 24px;
}
@media(max-width:768px){ .form-grid{ grid-template-columns:1fr; } }

.form-group {
    display: flex; flex-direction: column;
}
.form-label {
    font-size: .75rem; font-weight: 600; color: var(--s600);
    margin-bottom: 6px; display: flex; align-items: center; gap: 6px;
    text-transform: uppercase; letter-spacing: .5px;
}
.form-label i { font-size: .7rem; color: var(--g500); }
.form-label .required {
    color: #b91c1c; font-size: .7rem;
}
.form-control, .form-select {
    padding: 10px 14px; border-radius: var(--r);
    border: 1.5px solid var(--s200); font-size: .875rem;
    font-family: var(--font); transition: var(--transition);
    background: var(--white);
}
.form-control:focus, .form-select:focus {
    outline: none; border-color: var(--g400);
    box-shadow: 0 0 0 3px var(--g100);
}
.form-hint {
    font-size: .65rem; color: var(--s400); margin-top: 4px;
}
.input-group {
    display: flex;
}
.input-group-text {
    padding: 10px 14px; background: var(--s100);
    border: 1.5px solid var(--s200); border-left: none;
    border-radius: 0 var(--r) var(--r) 0; font-size: .75rem;
    font-weight: 600; color: var(--s600);
}
.input-group .form-control {
    border-radius: var(--r) 0 0 var(--r);
}

/* ══════════════════════════════════════════════
   CHECKBOX
══════════════════════════════════════════════ */
.form-check {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 0;
}
.form-check-input {
    width: 18px; height: 18px; accent-color: var(--g500);
    margin: 0;
}
.form-check-label {
    font-size: .85rem; color: var(--s700);
}

/* ══════════════════════════════════════════════
   ACTIONS BAR
══════════════════════════════════════════════ */
.actions-bar {
    padding-top: 24px; margin-top: 24px;
    border-top: 1.5px solid var(--s100);
    display: flex; gap: 12px; align-items: center;
}

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media(max-width:768px){
    .edit-type-page{ padding: 20px; }
    .edit-type-header{ flex-direction: column; align-items: flex-start; }
    .edit-type-card-body{ padding: 20px; }
    .actions-bar{ flex-direction: column; align-items: stretch; }
    .actions-bar .btn-db{ width: 100%; justify-content: center; }
}
</style>

<div class="edit-type-page">
    <!-- Breadcrumb -->
    <div class="edit-type-breadcrumb anim-1">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="<?php echo e(route('type.index')); ?>">Types de chambres</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Modifier: <?php echo e($type->name); ?></span>
    </div>

    <!-- Header -->
    <div class="edit-type-header anim-2">
        <div class="edit-type-brand">
            <div class="edit-type-brand-icon"><i class="fas fa-edit"></i></div>
            <div>
                <h1 class="edit-type-header-title">Modifier le <em>type</em></h1>
                <p class="edit-type-header-sub">
                    <i class="fas fa-tag me-1"></i> <?php echo e($type->name); ?> · Mise à jour des informations
                </p>
            </div>
        </div>
        <div class="edit-type-header-actions">
            <a href="<?php echo e(route('type.index')); ?>" class="btn-db btn-db-ghost">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="edit-type-card anim-3">
        <div class="edit-type-card-header">
            <h5 class="edit-type-card-title">
                <i class="fas fa-info-circle"></i>
                Informations du type
            </h5>
        </div>
        <div class="edit-type-card-body">
            <form id="edit-type-form" method="POST" action="<?php echo e(route('type.update', $type->id)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <div class="form-grid">
                    <!-- Nom -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-tag"></i>
                            Nom du type <span class="required">*</span>
                        </label>
                        <input type="text" name="name" class="form-control" 
                               value="<?php echo e($type->name); ?>" required
                               placeholder="Ex: Standard, Deluxe, Suite">
                    </div>
                    
                    <!-- Prix de base -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-money-bill-wave"></i>
                            Prix de base (FCFA)
                        </label>
                        <div class="input-group">
                            <input type="number" name="base_price" class="form-control"
                                   value="<?php echo e($type->base_price); ?>" min="0"
                                   placeholder="50000">
                            <span class="input-group-text">FCFA</span>
                        </div>
                        <div class="form-hint">Prix par nuit recommandé</div>
                    </div>
                    
                    <!-- Capacité -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-users"></i>
                            Capacité
                        </label>
                        <select name="capacity" class="form-select">
                            <option value="">-- Sélectionner --</option>
                            <?php for($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo e($i); ?>" <?php echo e($type->capacity == $i ? 'selected' : ''); ?>>
                                    <?php echo e($i); ?> personne(s)
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Description (pleine largeur) -->
                <div class="form-group" style="margin-top:16px;">
                    <label class="form-label">
                        <i class="fas fa-align-left"></i>
                        Description
                    </label>
                    <textarea name="information" class="form-control" rows="4" 
                              placeholder="Description du type de chambre..."><?php echo e($type->information); ?></textarea>
                </div>
                
                <!-- Statut actif -->
                <div class="form-check" style="margin-top:16px;">
                    <input class="form-check-input" type="checkbox" value="1" 
                           id="is_active" name="is_active" <?php echo e($type->is_active ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="is_active">
                        Actif (disponible pour sélection)
                    </label>
                </div>
                
                <!-- Actions -->
                <div class="actions-bar">
                    <button type="submit" class="btn-db btn-db-warning">
                        <i class="fas fa-save me-2"></i> Mettre à jour
                    </button>
                    <a href="<?php echo e(route('type.index')); ?>" class="btn-db btn-db-ghost">
                        <i class="fas fa-times me-2"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script>
// Gestion AJAX de la soumission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('edit-type-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enregistrement...';
            
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'Accept': 'application/json',
                    'X-HTTP-Method-Override': 'PUT'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = '<?php echo e(route("type.index")); ?>';
                } else {
                    alert(data.message || 'Erreur lors de la modification');
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur réseau. Veuillez réessayer.');
                button.disabled = false;
                button.innerHTML = originalText;
            });
        });
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/type/edit.blade.php ENDPATH**/ ?>