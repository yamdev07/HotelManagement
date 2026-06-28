@php
    $services = [
        ['fa-wifi', 'Wi-Fi gratuit', 'Connexion haut débit partout dans l\'établissement.'],
        ['fa-bell-concierge', 'Conciergerie 24/7', 'Une équipe dévouée à votre service jour et nuit.'],
        ['fa-mug-saucer', 'Petit-déjeuner', 'Une table généreuse pour bien commencer la journée.'],
        ['fa-car', 'Voiturier & parking', 'Stationnement sécurisé et service voiturier.'],
        ['fa-spa', 'Bien-être', 'Des moments de détente pensés pour vous.'],
        ['fa-location-dot', 'Emplacement', 'Au cœur des points d\'intérêt incontournables.'],
    ];
@endphp
<section class="section" id="services">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="eyebrow mb-2">L'art de recevoir</div>
            <h2 class="display-serif" style="font-size:clamp(2rem,4vw,3.2rem);">Nos services</h2>
            <div class="hero-divider" style="background:var(--c);opacity:.5;"></div>
        </div>
        <div class="row g-4">
            @foreach ($services as $i => [$icon, $title, $desc])
                <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="{{ ($i % 3) * 100 }}">
                    <div class="svc-card lift h-100" style="box-shadow:0 10px 40px -28px rgba(0,0,0,.4);">
                        <div class="svc-ico mb-3"><i class="fas {{ $icon }}"></i></div>
                        <h4 class="serif mb-2" style="font-size:1.35rem;">{{ $title }}</h4>
                        <p class="small mb-0" style="opacity:.85;">{{ $desc }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
