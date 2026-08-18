@extends('public.layout')
@section('title', __('public_rooms.title'))

@section('content')
    @php $cover = $hotel->coverUrl(); @endphp
    <header class="page-head {{ $cover ? 'has-img' : '' }}" @if($cover) style="background-image:url('{{ $cover }}')" @endif>
        @if($cover)<div class="ov"></div>@endif
        <div class="container">
            <div class="eyebrow mb-2" style="color:#fff;opacity:.85;">{{ __('public_rooms.accommodation') }}</div>
            <h1 class="display-serif" style="font-size:clamp(2.4rem,6vw,4rem);">{{ __('public_rooms.our_rooms') }}</h1>
        </div>
    </header>

    <section class="section">
        <div class="container">
            @if ($rooms->isEmpty())
                <p class="text-center text-secondary">{{ __('public_rooms.soon_available') }}</p>
            @else
                @php $fallbacks = config('vitrine.rooms'); @endphp
                <div class="row g-4">
                    @foreach ($rooms as $room)
                        @php $img = optional($room->images->first())->getRoomImage() ?: $fallbacks[$loop->index % count($fallbacks)]; @endphp
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 120 }}">
                            <div class="room-card lift h-100">
                                <div class="room-media">
                                    <div class="img" style="background-image: url('{{ $img }}');"></div>
                                    <span class="room-price">{{ number_format($room->price, 0, ',', ' ') }} {{ $hotel->currency }}<small style="font-weight:400;opacity:.85;"> {{ __('public_rooms.per_night') }}</small></span>
                                </div>
                                <div class="p-4">
                                    @if ($room->type)<div class="eyebrow mb-1">{{ $room->type->name }}</div>@endif
                                    <h4 class="serif mb-2" style="font-size:1.5rem;">{{ $room->name ?: __('public_rooms.room_default').' '.$room->number }}</h4>
                                    <p class="text-secondary small mb-3">
                                        <i class="fas fa-user-group me-1 text-c"></i> {{ $room->capacity }} {{ __('public_rooms.persons') }}
                                        @if ($room->number) &nbsp;·&nbsp; <i class="fas fa-door-closed me-1 text-c"></i> {{ __('public_rooms.room_default') }} {{ $room->number }}@endif
                                    </p>
                                    @if ($hotel->show_rooms)
                                        <a href="{{ route('public.hotel.booking', ['slug' => $hotel->slug, 'room' => $room->id, 'check_in' => now()->format('Y-m-d'), 'check_out' => now()->addDay()->format('Y-m-d'), 'guests' => 1]) }}" class="btn-c" style="padding:.55rem 1.3rem;font-size:.9rem;">{{ __('public_rooms.book') }} <i class="fas fa-arrow-right-long ms-1"></i></a>
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
