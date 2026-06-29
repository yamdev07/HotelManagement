@php $cover = $hotel->coverOrDefault(); @endphp
<section class="hero-lux" id="accueil">
    <div class="hero-bg" style="background-image: url('{{ $cover }}');"></div>
    <div class="hero-overlay" style="background:linear-gradient(180deg, rgba(0,0,0,.35) 0%, rgba(0,0,0,.2) 35%, color-mix(in srgb, var(--d) 80%, rgba(0,0,0,.7)) 100%);"></div>

    <div class="hero-content">
        <div class="eyebrow" data-aos="fade-down" style="color:#fff;opacity:.85;">
            @if($hotel->address)<i class="fas fa-location-dot me-2"></i>{{ $hotel->address }}@else Bienvenue @endif
        </div>
        <h1 class="display-serif" data-aos="fade-up" data-aos-delay="100">{{ $hotel->name }}</h1>
        <div class="hero-divider" data-aos="fade" data-aos-delay="250"></div>
        @if ($hotel->tagline)
            <p class="lead" data-aos="fade-up" data-aos-delay="300" style="font-weight:300;font-size:1.3rem;max-width:640px;margin:0 auto 2rem;">{{ $hotel->tagline }}</p>
        @endif
        <div data-aos="fade-up" data-aos-delay="400" class="d-flex flex-wrap gap-3 justify-content-center">
            @if ($hotel->show_rooms)<a href="{{ route('public.hotel.rooms', $hotel->slug) }}" class="btn-c">Découvrir nos chambres</a>@endif
            @if ($hotel->show_contact)<a href="{{ route('public.hotel.contact', $hotel->slug) }}" class="btn-ghost">Nous contacter</a>@endif
        </div>
    </div>

    <a href="#apropos" class="scroll-ind"><i class="fas fa-chevron-down fa-lg"></i></a>
</section>
