@php
    $reviews = [
        ['Awa D.', 'Cotonou', "Un séjour absolument parfait. Le service est aux petits soins et les chambres sont magnifiques.", 5],
        ['Marc L.', 'Paris', "Cadre raffiné, personnel souriant et une table d'exception. Je recommande sans hésiter.", 5],
        ['Sarah B.', 'Abidjan', "L'élégance à chaque détail. On se sent comme à la maison, en mieux. Vivement le retour !", 5],
    ];
@endphp
<section class="section dark-sec" id="avis">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="eyebrow mb-2">Témoignages</div>
            <h2 class="display-serif text-white" style="font-size:clamp(2rem,4vw,3.2rem);">Ils ont séjourné chez nous</h2>
            <div class="hero-divider" style="margin-top:1rem;"></div>
        </div>
        <div class="row g-4">
            @foreach ($reviews as $i => [$name, $city, $text, $stars])
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $i * 120 }}">
                    <div class="review h-100">
                        <div class="mb-3" style="color:#f5c451;">@for($s=0;$s<$stars;$s++)<i class="fas fa-star"></i>@endfor</div>
                        <p class="serif" style="font-size:1.2rem;line-height:1.7;opacity:.95;">“{{ $text }}”</p>
                        <div class="d-flex align-items-center gap-2 mt-3">
                            <div class="rev-ava">{{ substr($name,0,1) }}</div>
                            <div><div class="fw-semibold">{{ $name }}</div><div class="small" style="opacity:.6;">{{ $city }}</div></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
