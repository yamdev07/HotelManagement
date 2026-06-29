@extends('public.layout')
@section('title', 'Chambres')

@section('content')
    @php $cover = $hotel->coverUrl(); @endphp
    <header class="page-head {{ $cover ? 'has-img' : '' }}" @if($cover) style="background-image:url('{{ $cover }}')" @endif>
        @if($cover)<div class="ov"></div>@endif
        <div class="container">
            <div class="eyebrow mb-2" style="color:#fff;opacity:.85;">Hébergement</div>
            <h1 class="display-serif" style="font-size:clamp(2.4rem,6vw,4rem);">Nos chambres</h1>
        </div>
    </header>

    <section class="section">
        <div class="container">
            @if ($rooms->isEmpty())
                <p class="text-center text-secondary">Nos chambres seront bientôt disponibles à la réservation.</p>
            @else
                <div class="row g-4">
                    @foreach ($rooms as $room)
                        @php $img = optional($room->images->first())->getRoomImage(); @endphp
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 120 }}">
                            <div class="room-card lift h-100">
                                <div class="room-media">
                                    <div class="img" style="background-image: {{ $img ? "url('{$img}')" : 'linear-gradient(135deg, var(--c), var(--d))' }};"></div>
                                    <span class="room-price">{{ number_format($room->price, 0, ',', ' ') }} {{ $hotel->currency }}<small style="font-weight:400;opacity:.85;"> /nuit</small></span>
                                </div>
                                <div class="p-4">
                                    @if ($room->type)<div class="eyebrow mb-1">{{ $room->type->name }}</div>@endif
                                    <h4 class="serif mb-2" style="font-size:1.5rem;">{{ $room->name ?: 'Chambre '.$room->number }}</h4>
                                    <p class="text-secondary small mb-3">
                                        <i class="fas fa-user-group me-1 text-c"></i> {{ $room->capacity }} personnes
                                        @if ($room->number) &nbsp;·&nbsp; <i class="fas fa-door-closed me-1 text-c"></i> Chambre {{ $room->number }}@endif
                                    </p>
                                    @if ($hotel->show_contact)
                                        <a href="{{ route('public.hotel.contact', $hotel->slug) }}" class="text-c fw-semibold">Réserver <i class="fas fa-arrow-right-long ms-1"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
