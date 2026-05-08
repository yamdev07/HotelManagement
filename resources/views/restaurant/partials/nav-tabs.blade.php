@include('restaurant.partials.styles')

<style>
.db-tabs-card {
    background: var(--white); border-radius: var(--rl);
    padding: 6px; border: 1.5px solid var(--s100);
    margin-bottom: 24px; box-shadow: var(--shadow-xs);
    display: flex; align-items: center; gap: 4px;
    width: fit-content;
}

.db-tab-link {
    padding: 8px 16px; border-radius: 8px;
    font-size: .85rem; font-weight: 600; color: var(--s500);
    text-decoration: none; transition: var(--transition);
    display: flex; align-items: center; gap: 8px;
}

@media (max-width: 768px) {
    .db-tabs-card {
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
    .db-tab-link {
        justify-content: center;
        padding: 12px 8px;
        font-size: 0.8rem;
    }
}
.db-tab-link:hover { background: var(--s50); color: var(--s900); text-decoration: none; }
.db-tab-link.active { background: var(--g50); color: var(--g700); }
.db-tab-link i { font-size: 1rem; }
.db-tab-link.active i { color: var(--g600); }

.badge-pending {
    background: #ef4444; color: white;
    font-size: .65rem; padding: 2px 7px;
    border-radius: 100px; font-weight: 700;
    margin-left: 4px; border: 1.5px solid var(--white);
}
</style>

<div class="db-tabs-card anim-1">
    <a href="{{ route('restaurant.index') }}"
       class="db-tab-link {{ request()->routeIs('restaurant.index') ? 'active' : '' }}">
        <i class="fas fa-utensils"></i> Menus
    </a>
    
    <a href="{{ route('restaurant.orders') }}"
       class="db-tab-link {{ (request()->routeIs('restaurant.orders') || request()->is('restaurant/orders*')) ? 'active' : '' }}">
        <i class="fas fa-receipt"></i> Commandes
        @php $pendingCount = \App\Models\RestaurantOrder::whereIn('status', ['pending', 'validated', 'preparing'])->count(); @endphp
        @if($pendingCount > 0)
            <span class="badge-pending">{{ $pendingCount }}</span>
        @endif
    </a>

    <a href="{{ route('restaurant.categories.index') }}"
       class="db-tab-link {{ request()->routeIs('restaurant.categories.*') ? 'active' : '' }}">
        <i class="fas fa-tags"></i> Catégories
    </a>
    
    @if(in_array(auth()->user()->role, ['Super','Admin']))
        <a href="{{ route('restaurant.sales') }}"
           class="db-tab-link {{ request()->routeIs('restaurant.sales') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> Ventes
        </a>
    @endif
</div>
