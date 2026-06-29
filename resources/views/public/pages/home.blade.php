@extends('public.layout')

@section('content')
    @include('public.sections.hero')
    @include('public.sections.about')

    {{-- Aperçu des chambres --}}
    @if ($hotel->show_rooms && $rooms->isNotEmpty())
        <section class="section" style="background:#faf9f7;">
            <div class="container">
                <div class="text-center mb-5" data-aos="fade-up">
                    <div class="eyebrow mb-2">Hébergement</div>
                    <h2 class="display-serif" style="font-size:clamp(2rem,4vw,3.2rem);">Nos chambres</h2>
                    <div class="hero-divider" style="background:var(--c);opacity:.5;"></div>
                </div>
                <div class="row g-4">
                    @foreach ($rooms as $room)
                        @php $img = optional($room->images->first())->getRoomImage(); @endphp
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 120 }}">
                            <div class="room-card lift h-100">
                                <div class="room-media">
                                    <div class="img" style="background-image: {{ $img ? "url('{$img}')" : 'linear-gradient(135deg, var(--c), var(--d))' }};"></div>
                                    <span class="room-price">{{ number_format($room->price, 0, ',', ' ') }} {{ $hotel->currency }}</span>
                                </div>
                                <div class="p-4">
                                    @if ($room->type)<div class="eyebrow mb-1">{{ $room->type->name }}</div>@endif
                                    <h4 class="serif mb-2" style="font-size:1.4rem;">{{ $room->name ?: 'Chambre '.$room->number }}</h4>
                                    <p class="text-secondary small mb-0"><i class="fas fa-user-group me-1 text-c"></i> {{ $room->capacity }} personnes</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-5" data-aos="fade-up">
                    <a href="{{ route('public.hotel.rooms', $hotel->slug) }}" class="btn-c">Toutes nos chambres</a>
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    @if ($hotel->show_contact)
        <section class="section dark-sec text-center">
            <div class="container" data-aos="zoom-in">
                <div class="eyebrow mb-2">Réservez votre séjour</div>
                <h2 class="display-serif text-white mb-4" style="font-size:clamp(2rem,4vw,3rem);">Vivez l'expérience {{ $hotel->name }}</h2>
                <a href="{{ route('public.hotel.contact', $hotel->slug) }}" class="btn-c">Nous contacter</a>
            </div>
        </section>
    @endif
@endsection
