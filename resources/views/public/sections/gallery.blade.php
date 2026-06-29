@php $gallery = config('vitrine.gallery'); @endphp
<section class="section" id="galerie">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="eyebrow mb-2">Galerie</div>
            <h2 class="display-serif" style="font-size:clamp(2rem,4vw,3.2rem);">L'atmosphère des lieux</h2>
            <div class="hero-divider" style="background:var(--c);opacity:.5;"></div>
        </div>
        <div class="gallery-grid">
            @foreach ($gallery as $i => $src)
                <a href="{{ $src }}" target="_blank" rel="noopener" class="gallery-item {{ $i % 5 === 0 ? 'tall' : '' }} {{ $i % 7 === 0 ? 'wide' : '' }}"
                   data-aos="zoom-in" data-aos-delay="{{ ($i % 4) * 80 }}" style="background-image:url('{{ $src }}');">
                    <span class="gallery-ov"><i class="fas fa-expand"></i></span>
                </a>
            @endforeach
        </div>
    </div>
</section>
