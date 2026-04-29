<div class="africa-card">
    
    <?php if($menu->image): ?>
    <div class="africa-card-img">
        <img src="<?php echo e($menu->image_url); ?>" alt="<?php echo e($menu->name); ?>" loading="lazy">
        <div class="africa-card-badge">
            <?php if($menu->category === 'plat'): ?> Plat principal
            <?php elseif($menu->category === 'entree'): ?> Entrée
            <?php elseif($menu->category === 'dessert'): ?> Dessert
            <?php elseif($menu->category === 'boisson'): ?> Boisson
            <?php endif; ?>
        </div>
        <div class="africa-price-badge"><?php echo e(number_format($menu->price, 0, ',', ' ')); ?> FCFA</div>
    </div>
    <?php else: ?>
    <div class="africa-card-noimg">
        <?php if($menu->category === 'boisson'): ?> 🥤
        <?php elseif($menu->category === 'dessert'): ?> 🍮
        <?php elseif($menu->category === 'entree'): ?> 🥗
        <?php else: ?> 🍽️
        <?php endif; ?>
        <div class="africa-card-badge" style="position:relative;top:auto;left:auto;margin-top:8px;">
            <?php if($menu->category === 'plat'): ?> Plat <?php elseif($menu->category === 'entree'): ?> Entrée
            <?php elseif($menu->category === 'dessert'): ?> Dessert <?php else: ?> Boisson <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="africa-card-body">
        <div class="africa-card-name"><?php echo e($menu->name); ?></div>
        <p class="africa-card-desc"><?php echo e($menu->description); ?></p>
        <div class="africa-card-footer">
            <span class="origin-tag"><i class="fas fa-globe-africa"></i> Cuisine africaine</span>
            <a href="<?php echo e(route('frontend.restaurant')); ?>#menuSection" class="btn-africa-order">
                <i class="fas fa-cart-plus"></i> Commander
            </a>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\HP\HotelManagement\resources\views/frontend/partials/african-card.blade.php ENDPATH**/ ?>