<div class="africa-card">
    {{-- Image --}}
    @if($menu->image)
    <div class="africa-card-img">
        <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" loading="lazy">
        <div class="africa-card-badge">
            @if($menu->category === 'plat') Plat principal
            @elseif($menu->category === 'entree') Entrée
            @elseif($menu->category === 'dessert') Dessert
            @elseif($menu->category === 'boisson') Boisson
            @endif
        </div>
        <div class="africa-price-badge">{{ number_format($menu->price, 0, ',', ' ') }} FCFA</div>
    </div>
    @else
    <div class="africa-card-noimg">
        @if($menu->category === 'boisson') 🥤
        @elseif($menu->category === 'dessert') 🍮
        @elseif($menu->category === 'entree') 🥗
        @else 🍽️
        @endif
        <div class="africa-card-badge" style="position:relative;top:auto;left:auto;margin-top:8px;">
            @if($menu->category === 'plat') Plat @elseif($menu->category === 'entree') Entrée
            @elseif($menu->category === 'dessert') Dessert @else Boisson @endif
        </div>
    </div>
    @endif

    {{-- Body --}}
    <div class="africa-card-body">
        <div class="africa-card-name">{{ $menu->name }}</div>
        <p class="africa-card-desc">{{ $menu->description }}</p>
        <div class="africa-card-footer">
            <span class="origin-tag"><i class="fas fa-globe-africa"></i> Cuisine africaine</span>
            <a href="{{ route('frontend.restaurant') }}#menuSection" class="btn-africa-order">
                <i class="fas fa-cart-plus"></i> Commander
            </a>
        </div>
    </div>
</div>
