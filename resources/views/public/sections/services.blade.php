@php $services = $hotel->siteServices(); @endphp
<section class="section" id="services">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="eyebrow mb-2">L'art de recevoir</div>
            <h2 class="display-serif" style="font-size:clamp(2rem,4vw,3.2rem);">Nos services</h2>
            <div class="hero-divider" style="background:var(--c);opacity:.5;"></div>
        </div>
        <div class="row g-4">
            @foreach ($services as $i => $svc)
                <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="{{ ($i % 3) * 100 }}">
                    <div class="svc-card lift h-100" style="box-shadow:0 10px 40px -28px rgba(0,0,0,.4);">
                        <div class="svc-ico mb-3"><i class="fas {{ $svc['icon'] ?? 'fa-star' }}"></i></div>
                        <h4 class="serif mb-2" style="font-size:1.35rem;">{{ $svc['title'] ?? '' }}</h4>
                        <p class="small mb-0" style="opacity:.85;">{{ $svc['description'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
