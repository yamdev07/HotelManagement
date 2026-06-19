{{-- Section restaurant : aperçu du menu (données scopées) --}}
<section class="section" id="restaurant">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Notre restaurant</h2>
            <p class="text-secondary">Une sélection de notre carte.</p>
        </div>

        @if ($menus->isEmpty())
            <p class="text-center text-secondary">La carte sera bientôt disponible.</p>
        @else
            <div class="row g-3">
                @foreach ($menus as $menu)
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-start p-3 border rounded-3 h-100">
                            <div class="pe-3">
                                <h6 class="fw-bold mb-1">{{ $menu->name }}</h6>
                                @if ($menu->description)
                                    <p class="text-secondary small mb-0">{{ \Illuminate\Support\Str::limit($menu->description, 90) }}</p>
                                @endif
                            </div>
                            <span class="fw-bold text-hotel text-nowrap">{{ number_format($menu->price, 0, ',', ' ') }} {{ $hotel->currency }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
