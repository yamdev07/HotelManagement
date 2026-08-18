@extends('public.layout')
@section('title', __('public_availability.title'))

@push('head')
<style>
    .book-wrap { max-width: 1100px; margin: 0 auto; }
    .search-bar {
        background: var(--card); border: 1px solid var(--line); border-radius: 18px; backdrop-filter: blur(14px);
        box-shadow: 0 30px 70px -40px rgba(0,0,0,.7);
        padding: 18px; display: grid; grid-template-columns: 1fr 1fr .8fr auto; gap: 14px; align-items: end;
        margin-top: -46px; position: relative; z-index: 5;
    }
    @media (max-width: 780px) { .search-bar { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 480px) { .search-bar { grid-template-columns: 1fr; } }
    .sb-field label { display:block; font-size: .7rem; font-weight: 600; text-transform: uppercase; letter-spacing: .1em; color: var(--ink2); margin-bottom: 6px; }
    .sb-field input, .sb-field select {
        width: 100%; padding: 12px 13px; border: 1px solid var(--line); border-radius: 11px;
        font-size: .95rem; color: var(--ink); background: rgba(255,255,255,.06); font-family: inherit;
    }
    .sb-field input:focus, .sb-field select:focus { outline: none; border-color: var(--c); box-shadow: 0 0 0 3px color-mix(in srgb, var(--c) 22%, transparent); }
    .sb-submit { display:inline-flex; align-items:center; justify-content:center; gap:8px; background: var(--c); color:#fff; border:0; border-radius:11px; padding:12px 22px; font-weight:600; font-size:.95rem; cursor:pointer; height: 46px; white-space:nowrap; box-shadow:0 12px 30px -10px var(--c); }
    .sb-submit:hover { filter: brightness(1.1); }

    .book-alert { background:rgba(220,60,50,.12); border:1px solid rgba(220,60,50,.35); color:#ff8f85; border-radius:12px; padding:12px 16px; margin: 22px 0; font-size:.9rem; }
    .book-alert ul { margin:0; padding-left:18px; }

    .res-head { display:flex; align-items:baseline; justify-content:space-between; gap:12px; flex-wrap:wrap; margin: 34px 0 18px; }
    .res-head h2 { font-size: 1.5rem; margin:0; }
    .res-head .meta { color:var(--ink2); font-size:.9rem; }

    .rgrid { display:grid; grid-template-columns: repeat(3,1fr); gap: 22px; }
    @media (max-width: 900px){ .rgrid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 600px){ .rgrid { grid-template-columns: 1fr; } }
    .rcard { border:1px solid var(--line); border-radius:18px; overflow:hidden; background:var(--card); backdrop-filter:blur(8px); display:flex; flex-direction:column; transition:transform .25s, box-shadow .25s, border-color .25s; }
    .rcard:hover { transform:translateY(-6px); box-shadow:0 30px 60px -34px rgba(0,0,0,.6); border-color:color-mix(in srgb, var(--c) 45%, transparent); }
    .rcard .media { height:190px; background-size:cover; background-position:center; position:relative; }
    .rcard .cap-badge { position:absolute; top:12px; left:12px; background:rgba(11,13,17,.7); backdrop-filter:blur(6px); color:#fff; font-size:.75rem; font-weight:600; padding:5px 11px; border-radius:20px; display:inline-flex; align-items:center; gap:6px; border:1px solid rgba(255,255,255,.15); }
    .rcard .body { padding:16px 18px 18px; display:flex; flex-direction:column; gap:6px; flex:1; }
    .rcard .rtype { font-size:.76rem; font-weight:600; text-transform:uppercase; letter-spacing:.08em; color:var(--c); }
    .rcard .rname { font-size:1.1rem; font-weight:700; color:var(--ink); font-family:var(--disp); }
    .rcard .rdesc { font-size:.85rem; color:var(--ink2); line-height:1.5; }
    .rcard .price-row { margin-top:auto; padding-top:12px; display:flex; align-items:flex-end; justify-content:space-between; gap:10px; border-top:1px solid var(--line); }
    .rcard .ppn { font-size:1.2rem; font-weight:800; color:var(--ink); font-family:var(--disp); }
    .rcard .ppn small { font-size:.72rem; font-weight:400; color:var(--ink2); }
    .rcard .ptotal { font-size:.78rem; color:var(--ink2); }
    .rcard .cta { display:inline-flex; align-items:center; justify-content:center; gap:8px; margin-top:14px; background:var(--c); color:#fff; border-radius:11px; padding:11px 14px; font-weight:600; font-size:.9rem; text-decoration:none; box-shadow:0 10px 26px -12px var(--c); }
    .rcard .cta:hover { filter:brightness(1.1); color:#fff; }
    .rcard .cta.wa { background:#25d366; box-shadow:none; }

    .empty-book { text-align:center; padding:52px 20px; color:var(--ink2); }
    .empty-book .ic { width:66px; height:66px; border-radius:50%; background:var(--card); border:1px solid var(--line); display:grid; place-items:center; font-size:1.5rem; color:var(--c); margin:0 auto 14px; }
    .empty-book h3 { color:var(--ink); margin:0 0 6px; font-family:var(--disp); }
</style>
@endpush

@section('content')
    @php $cover = $hotel->coverUrl(); @endphp
    <header class="page-head {{ $cover ? 'has-img' : '' }}" @if($cover) style="background-image:url('{{ $cover }}')" @endif>
        @if($cover)<div class="ov"></div>@endif
        <div class="container">
            <div class="eyebrow mb-2" style="color:#fff;opacity:.85;">{{ __('public_availability.subtitle') }}</div>
            <h1 class="display-serif" style="font-size:clamp(2.2rem,5.5vw,3.6rem);">{{ __('public_availability.title') }}</h1>
        </div>
    </header>

    <section class="section" style="padding-top:0;">
        <div class="container book-wrap">

            {{-- Barre de recherche --}}
            <form method="GET" action="{{ route('public.hotel.availability', $hotel->slug) }}" class="search-bar">
                <div class="sb-field">
                    <label>{{ __('public_booking.check_in') }}</label>
                    <input type="date" name="check_in" min="{{ now()->format('Y-m-d') }}"
                           value="{{ $checkIn ?: now()->format('Y-m-d') }}" required>
                </div>
                <div class="sb-field">
                    <label>{{ __('public_booking.check_out') }}</label>
                    <input type="date" name="check_out" min="{{ now()->addDay()->format('Y-m-d') }}"
                           value="{{ $checkOut ?: now()->addDay()->format('Y-m-d') }}" required>
                </div>
                <div class="sb-field">
                    <label>{{ __('public_hero.guests') }}</label>
                    <select name="guests">
                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ (int)$guests === $i ? 'selected' : '' }}>{{ $i }} {{ $i > 1 ? __('public_hero.guests_plural') : __('public_hero.guest') }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="sb-submit"><i class="fas fa-search"></i> {{ __('public_hero.search') }}</button>
            </form>

            @if (! empty($errors) && is_array($errors))
                <div class="book-alert"><ul>@foreach ($errors as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif

            @if ($searched)
                @php
                    $waRaw = $hotel->socials['whatsapp'] ?? $hotel->contact_phone ?? '';
                    $wa = preg_replace('/\D+/', '', (string) $waRaw);
                @endphp

                <div class="res-head">
                    <h2>{{ $rooms->count() }} {{ $rooms->count() > 1 ? __('public_availability.rooms_available_plural') : __('public_availability.rooms_available') }}</h2>
                    <div class="meta">
                        <i class="fas fa-calendar-day"></i>
                        {{ \Carbon\Carbon::parse($checkIn)->translatedFormat('d M') }} → {{ \Carbon\Carbon::parse($checkOut)->translatedFormat('d M') }}
                        · {{ $nights }} {{ __('public_booking.night', $nights) }} · {{ $guests }} {{ __('public_booking.guest', $guests) }}
                    </div>
                </div>

                @if ($rooms->isEmpty())
                    <div class="empty-book">
                        <div class="ic"><i class="fas fa-bed"></i></div>
                        <h3>{{ __('public_availability.no_room') }}</h3>
                        <p>{{ __('public_availability.try_other_dates') }}</p>
                    </div>
                @else
                    <div class="rgrid">
                        @foreach ($rooms as $room)
                            <div class="rcard">
                                <div class="media" style="background-image:url('{{ $room->firstImage() }}')">
                                    <span class="cap-badge"><i class="fas fa-user"></i> {{ $room->capacity }}</span>
                                </div>
                                <div class="body">
                                    <div class="rtype">{{ $room->type->name ?? __('public_booking.room') }}</div>
                                    <div class="rname">{{ __('public_booking.room') }} {{ $room->number }}</div>
                                    @if (! empty($room->type->description_fr ?? $room->view))
                                        <div class="rdesc">{{ \Illuminate\Support\Str::limit($room->type->description_fr ?? $room->view, 90) }}</div>
                                    @endif
                                    <div class="price-row">
                                        <div>
                                            <div class="ppn">{{ number_format($room->price, 0, ',', ' ') }} <small>{{ $hotel->currency }} {{ __('public_booking.per_night') }}</small></div>
                                            <div class="ptotal">{{ __('public_booking.total_stay') }} : <strong>{{ number_format($room->price * $nights, 0, ',', ' ') }} {{ $hotel->currency }}</strong></div>
                                        </div>
                                    </div>
                                    <a class="cta" href="{{ route('public.hotel.booking', ['slug' => $hotel->slug, 'room' => $room->id, 'check_in' => $checkIn, 'check_out' => $checkOut, 'guests' => $guests]) }}">
                                        <i class="fas fa-calendar-check"></i> {{ __('public_booking.book_now') }}
                                    </a>
                                    @if ($wa)
                                        <a class="cta wa" target="_blank" rel="noopener" style="margin-top:8px;"
                                           href="https://wa.me/{{ $wa }}?text={{ urlencode("Bonjour {$hotel->name}, je souhaite réserver la « ".($room->type->name ?? 'chambre')." » (chambre {$room->number}) du ".\Carbon\Carbon::parse($checkIn)->format('d/m/Y')." au ".\Carbon\Carbon::parse($checkOut)->format('d/m/Y')." pour {$guests} voyageur(s).") }}">
                                            <i class="fab fa-whatsapp"></i> Via WhatsApp
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p style="text-align:center; color:var(--ink2); font-size:.82rem; margin-top:26px;">
                        <i class="fas fa-lock"></i> {{ __('public_availability.secure_payment_coming') }}
                    </p>
                @endif
            @else
                <p style="text-align:center; color:var(--ink2); margin-top:34px;">
                    {{ __('public_availability.choose_dates') }}
                </p>
            @endif

        </div>
    </section>
@endsection
