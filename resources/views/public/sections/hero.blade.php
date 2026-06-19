{{-- Section hero : couverture, nom, slogan et description de l'hôtel --}}
@php $cover = $hotel->coverUrl(); @endphp
<section class="position-relative text-white d-flex align-items-center"
         style="min-height: 70vh;
                background: {{ $cover ? "linear-gradient(rgba(15,23,42,.55), rgba(15,23,42,.65)), url('{$cover}')" : 'linear-gradient(135deg, var(--hotel-primary), var(--hotel-secondary))' }};
                background-size: cover; background-position: center;">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                @if ($hotel->logoUrl())
                    <img src="{{ $hotel->logoUrl() }}" alt="{{ $hotel->name }}"
                         class="mb-4 bg-white p-2 rounded-3" style="height:72px;">
                @endif
                <h1 class="fw-bold display-4 mb-3">{{ $hotel->name }}</h1>
                @if ($hotel->tagline)
                    <p class="fs-4 mb-3 opacity-90">{{ $hotel->tagline }}</p>
                @endif
                @if ($hotel->description)
                    <p class="fs-6 opacity-75 mb-4" style="max-width: 640px;">{{ $hotel->description }}</p>
                @endif
                <div class="d-flex flex-wrap gap-2">
                    @if ($hotel->show_rooms)
                        <a href="#chambres" class="btn btn-light btn-lg text-hotel fw-semibold">
                            <i class="fas fa-bed me-2"></i>Nos chambres
                        </a>
                    @endif
                    @if ($hotel->show_contact)
                        <a href="#contact" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-envelope me-2"></i>Nous contacter
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
