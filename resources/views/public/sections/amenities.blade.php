@php $services = $hotel->show_services ? $hotel->siteServices() : []; @endphp
@if (! empty($services))
<section class="section" id="services">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="eyebrow mb-2">{{ __('public.amenities_art') }}</div>
            <h2 class="display-serif" style="font-size:clamp(2rem,4vw,3.2rem);">{{ __('public.amenities_title') }}</h2>
            <div class="hero-divider" style="background:var(--c);opacity:.5;"></div>
        </div>
        <div class="row g-4">
            @foreach ($services as $i => $svc)
                <div class="col-6 col-md-4 col-lg-3" data-aos="zoom-in" data-aos-delay="{{ ($i % 4) * 90 }}">
                    <div class="svc-card lift h-100" style="box-shadow:0 10px 40px -30px rgba(0,0,0,.35);border:1px solid #f0efec;">
                        <div class="svc-ico mb-3"><i class="fas {{ $svc['icon'] ?? 'fa-star' }}"></i></div>
                        <h4 class="serif mb-2" style="font-size:1.15rem;">{{ $svc['title'] ?? '' }}</h4>
                        @if (! empty($svc['description']))
                            <p class="small mb-0" style="opacity:.8;">{{ \Illuminate\Support\Str::limit($svc['description'], 70) }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="{{ route('public.hotel.services', $hotel->slug) }}" class="btn-c">{{ __('public.amenities_all') }}</a>
        </div>
    </div>
</section>
@endif
