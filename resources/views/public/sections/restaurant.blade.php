<section class="section dark-sec" id="restaurant">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="eyebrow mb-2">Gastronomie</div>
            <h2 class="display-serif text-white" style="font-size:clamp(2rem,4vw,3.2rem);">Notre restaurant</h2>
            <div class="hero-divider" style="margin-top:1rem;"></div>
            <p class="mt-3" style="opacity:.75;max-width:560px;margin-inline:auto;font-weight:300;">Une cuisine soignée, des produits choisis, une carte qui évolue au fil des saisons.</p>
        </div>

        @if ($menus->isEmpty())
            <p class="text-center" style="opacity:.7;">Notre carte sera bientôt dévoilée.</p>
        @else
            <div class="row g-4 justify-content-center" style="max-width:880px;margin-inline:auto;">
                @foreach ($menus as $i => $menu)
                    <div class="col-md-6" data-aos="fade-up" data-aos-delay="{{ ($i % 2) * 100 }}">
                        <div class="d-flex justify-content-between align-items-baseline pb-3" style="border-bottom:1px solid rgba(255,255,255,.15);">
                            <div class="pe-3">
                                <h4 class="serif text-white mb-1" style="font-size:1.3rem;">{{ $menu->name }}</h4>
                                @if ($menu->description)<p class="small mb-0" style="opacity:.65;">{{ \Illuminate\Support\Str::limit($menu->description, 70) }}</p>@endif
                            </div>
                            <span class="serif text-c" style="font-size:1.3rem;white-space:nowrap;filter:brightness(1.6);">{{ number_format($menu->price, 0, ',', ' ') }} {{ $hotel->currency }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
