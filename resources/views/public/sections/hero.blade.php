@php $cover = $hotel->coverUrl(); @endphp
<section class="hero-lux" id="accueil">
    <div class="hero-bg" style="background-image: {{ $cover ? "url('{$cover}')" : 'linear-gradient(135deg, var(--c), var(--d))' }};"></div>
    <div class="hero-overlay"></div>

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
            @if ($hotel->show_rooms)<a href="#chambres" class="btn-c">Découvrir nos chambres</a>@endif
            @if ($hotel->show_contact)<a href="#contact" class="btn-ghost">Nous contacter</a>@endif
        </div>
    </div>

    <a href="#apropos" class="scroll-ind"><i class="fas fa-chevron-down fa-lg"></i></a>
</section>
