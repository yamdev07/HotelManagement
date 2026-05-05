<div class="rv-item-card africa-card-module">
    <div class="rv-item-img">
        @if($menu->image)
            <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" loading="lazy" onerror="this.onerror=null; this.src='https://i.pinimg.com/736x/fc/7a/4a/fc7a4ad5e3299c1dac28baa60eef6111.jpg';">
        @else
            <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                <span style="font-size: 3rem;">
                    @if($menu->category === 'boisson') 🥤
                    @elseif($menu->category === 'dessert') 🍮
                    @elseif($menu->category === 'entree') 🥗
                    @else 🍽️
                    @endif
                </span>
            </div>
        @endif
        <div class="rv-item-price">{{ number_format($menu->price, 0, ',', ' ') }} FCFA</div>
    </div>

    <div class="rv-item-content">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="rv-item-cat">{{ $menu->category }}</span>
            <span class="badge bg-light text-success border" style="font-size: 0.65rem; font-weight: 700;">
                <i class="fas fa-globe-africa me-1"></i> Cuisine africaine
            </span>
        </div>
        <h3 class="rv-item-title">{{ $menu->name }}</h3>
        <p class="rv-item-desc">{{ Str::limit($menu->description, 80) }}</p>
        
        <div class="mt-auto">
            <a href="{{ route('frontend.restaurant') }}#menu-container" class="rv-item-btn">
                <i class="fas fa-shopping-basket"></i> Commander
            </a>
        </div>
    </div>
</div>
