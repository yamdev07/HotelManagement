
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-2 px-3">
        <ul class="nav nav-pills gap-1 flex-wrap">
            <li class="nav-item">
                <a href="<?php echo e(route('restaurant.index')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('restaurant.index') ? 'active' : 'text-muted'); ?>">
                    <i class="fas fa-utensils me-1"></i> Menus
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(route('restaurant.orders')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('restaurant.orders') ? 'active' : 'text-muted'); ?>">
                    <i class="fas fa-receipt me-1"></i> Commandes
                    <?php $pending = \App\Models\RestaurantOrder::where('status','pending')->count(); ?>
                    <?php if($pending > 0): ?>
                    <span class="badge bg-warning text-dark ms-1"><?php echo e($pending); ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <?php if(in_array(auth()->user()->role, ['Super','Admin','Receptionist'])): ?>
            <li class="nav-item">
                <a href="<?php echo e(route('restaurant.stock')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('restaurant.stock') ? 'active' : 'text-muted'); ?>">
                    <i class="fas fa-boxes me-1"></i> Stock
                    <?php $lowStock = \App\Models\Ingredient::where('quantity_in_stock', '<=', \Illuminate\Support\Facades\DB::raw('min_stock'))->count(); ?>
                    <?php if($lowStock > 0): ?>
                    <span class="badge bg-danger ms-1"><?php echo e($lowStock); ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <?php endif; ?>
            <?php if(in_array(auth()->user()->role, ['Super','Admin'])): ?>
            <li class="nav-item">
                <a href="<?php echo e(route('restaurant.sales')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('restaurant.sales') ? 'active' : 'text-muted'); ?>">
                    <i class="fas fa-chart-bar me-1"></i> Ventes
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(route('restaurant.layout')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('restaurant.layout') ? 'active' : 'text-muted'); ?>">
                    <i class="fas fa-table me-1"></i> Plan de salle
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<?php /**PATH C:\Users\HP\HotelManagement\resources\views/restaurant/partials/nav-tabs.blade.php ENDPATH**/ ?>