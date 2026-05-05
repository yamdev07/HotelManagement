@include('restaurant.partials.styles')

<style>
.db-tabs-card {
    background: var(--white); border-radius: var(--rl);
    padding: 8px; border: 1.5px solid var(--s100);
    margin-bottom: 24px; box-shadow: var(--shadow-xs);
    display: flex; align-items: center; gap: 4px;
}
.db-tab-link {
    padding: 10px 20px; border-radius: 10px;
    font-size: .88rem; font-weight: 600; color: var(--s500);
    text-decoration: none; transition: var(--transition);
    display: flex; align-items: center; gap: 8px;
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
        @php $pending = \App\Models\RestaurantOrder::where('status','pending')->count(); @endphp
        @if($pending > 0)
            <span class="badge-pending">{{ $pending }}</span>
        @endif
    </a>
    
    @if(in_array(auth()->user()->role, ['Super','Admin']))
        <a href="{{ route('restaurant.sales') }}"
           class="db-tab-link {{ request()->routeIs('restaurant.sales') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> Ventes
        </a>
    @endif
</div>
