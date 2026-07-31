@extends('public.layout')
@section('title', 'Réserver')

@push('head')
<style>
    .book-wrap { max-width: 1100px; margin: 0 auto; }
    .search-bar {
        background: #fff; border: 1px solid #e8eaf0; border-radius: 18px;
        box-shadow: 0 24px 60px -30px rgba(20,30,50,.25);
        padding: 18px; display: grid; grid-template-columns: 1fr 1fr .8fr auto; gap: 14px; align-items: end;
        margin-top: -46px; position: relative; z-index: 5;
    }
    @media (max-width: 780px) { .search-bar { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 480px) { .search-bar { grid-template-columns: 1fr; } }
    .sb-field label { display:block; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #8891a3; margin-bottom: 6px; }
    .sb-field input, .sb-field select {
        width: 100%; padding: 11px 13px; border: 1.5px solid #e3e6ee; border-radius: 11px;
        font-size: .95rem; color: #1f2733; background: #fff; font-family: inherit;
    }
    .sb-field input:focus, .sb-field select:focus { outline: none; border-color: var(--c); box-shadow: 0 0 0 3px color-mix(in srgb, var(--c) 18%, transparent); }
    .sb-submit { display:inline-flex; align-items:center; justify-content:center; gap:8px; background: var(--c); color:#fff; border:0; border-radius:11px; padding:12px 22px; font-weight:700; font-size:.95rem; cursor:pointer; height: 46px; white-space:nowrap; }
    .sb-submit:hover { filter: brightness(1.06); }

    .book-alert { background:#fef2f2; border:1px solid #fecaca; color:#b91c1c; border-radius:12px; padding:12px 16px; margin: 22px 0; font-size:.9rem; }
    .book-alert ul { margin:0; padding-left:18px; }

    .res-head { display:flex; align-items:baseline; justify-content:space-between; gap:12px; flex-wrap:wrap; margin: 34px 0 18px; }
    .res-head h2 { font-size: 1.4rem; margin:0; }
    .res-head .meta { color:#6b7280; font-size:.9rem; }

    .rgrid { display:grid; grid-template-columns: repeat(3,1fr); gap: 22px; }
    @media (max-width: 900px){ .rgrid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 600px){ .rgrid { grid-template-columns: 1fr; } }
    .rcard { border:1px solid #ececf2; border-radius:16px; overflow:hidden; background:#fff; box-shadow:0 10px 30px -22px rgba(20,30,50,.35); display:flex; flex-direction:column; transition:transform .18s, box-shadow .18s; }
    .rcard:hover { transform:translateY(-4px); box-shadow:0 22px 46px -26px rgba(20,30,50,.4); }
    .rcard .media { height:180px; background-size:cover; background-position:center; position:relative; }
    .rcard .cap-badge { position:absolute; top:12px; left:12px; background:rgba(255,255,255,.92); color:#1f2733; font-size:.75rem; font-weight:700; padding:5px 11px; border-radius:20px; display:inline-flex; align-items:center; gap:6px; }
    .rcard .body { padding:16px 18px 18px; display:flex; flex-direction:column; gap:6px; flex:1; }
    .rcard .rtype { font-size:.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--c); }
    .rcard .rname { font-size:1.05rem; font-weight:700; color:#1f2733; }
    .rcard .rdesc { font-size:.85rem; color:#6b7280; line-height:1.5; }
    .rcard .price-row { margin-top:auto; padding-top:12px; display:flex; align-items:flex-end; justify-content:space-between; gap:10px; border-top:1px solid #f1f2f6; }
    .rcard .ppn { font-size:1.15rem; font-weight:800; color:#1f2733; }
    .rcard .ppn small { font-size:.72rem; font-weight:500; color:#9aa1ad; }
    .rcard .ptotal { font-size:.78rem; color:#6b7280; }
    .rcard .cta { display:inline-flex; align-items:center; justify-content:center; gap:8px; margin-top:14px; background:var(--c); color:#fff; border-radius:11px; padding:11px 14px; font-weight:700; font-size:.9rem; text-decoration:none; }
    .rcard .cta:hover { filter:brightness(1.06); color:#fff; }
    .rcard .cta.wa { background:#25d366; }

    .empty-book { text-align:center; padding:52px 20px; color:#6b7280; }
    .empty-book .ic { width:66px; height:66px; border-radius:50%; background:#f3f4f8; display:grid; place-items:center; font-size:1.5rem; color:#aab2c0; margin:0 auto 14px; }
    .empty-book h3 { color:#1f2733; margin:0 0 6px; }
</style>
@endpush

@section('content')
    @php $cover = $hotel->coverUrl(); @endphp
    <header class="page-head {{ $cover ? 'has-img' : '' }}" @if($cover) style="background-image:url('{{ $cover }}')" @endif>
        @if($cover)<div class="ov"></div>@endif
        <div class="container">
            <div class="eyebrow mb-2" style="color:#fff;opacity:.85;">Réservation</div>
            <h1 class="display-serif" style="font-size:clamp(2.2rem,5.5vw,3.6rem);">Trouvez votre chambre</h1>
        </div>
    </header>

    <section class="section" style="padding-top:0;">
        <div class="container book-wrap">

            {{-- Barre de recherche --}}
            <form method="GET" action="{{ route('public.hotel.availability', $hotel->slug) }}" class="search-bar">
                <div class="sb-field">
                    <label>Arrivée</label>
                    <input type="date" name="check_in" min="{{ now()->format('Y-m-d') }}"
                           value="{{ $checkIn ?: now()->format('Y-m-d') }}" required>
                </div>
                <div class="sb-field">
                    <label>Départ</label>
                    <input type="date" name="check_out" min="{{ now()->addDay()->format('Y-m-d') }}"
                           value="{{ $checkOut ?: now()->addDay()->format('Y-m-d') }}" required>
                </div>
                <div class="sb-field">
                    <label>Voyageurs</label>
                    <select name="guests">
                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ (int)$guests === $i ? 'selected' : '' }}>{{ $i }} {{ $i > 1 ? 'personnes' : 'personne' }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="sb-submit"><i class="fas fa-search"></i> Rechercher</button>
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
                    <h2>{{ $rooms->count() }} {{ $rooms->count() > 1 ? 'chambres disponibles' : 'chambre disponible' }}</h2>
                    <div class="meta">
                        <i class="fas fa-calendar-day"></i>
                        {{ \Carbon\Carbon::parse($checkIn)->translatedFormat('d M') }} → {{ \Carbon\Carbon::parse($checkOut)->translatedFormat('d M') }}
                        · {{ $nights }} nuit{{ $nights > 1 ? 's' : '' }} · {{ $guests }} voyageur{{ $guests > 1 ? 's' : '' }}
                    </div>
                </div>

                @if ($rooms->isEmpty())
                    <div class="empty-book">
                        <div class="ic"><i class="fas fa-bed"></i></div>
                        <h3>Aucune chambre disponible</h3>
                        <p>Essayez d'autres dates ou réduisez le nombre de voyageurs.</p>
                    </div>
                @else
                    <div class="rgrid">
                        @foreach ($rooms as $room)
                            <div class="rcard">
                                <div class="media" style="background-image:url('{{ $room->firstImage() }}')">
                                    <span class="cap-badge"><i class="fas fa-user"></i> {{ $room->capacity }}</span>
                                </div>
                                <div class="body">
                                    <div class="rtype">{{ $room->type->name ?? 'Chambre' }}</div>
                                    <div class="rname">Chambre {{ $room->number }}</div>
                                    @if (! empty($room->type->description_fr ?? $room->view))
                                        <div class="rdesc">{{ \Illuminate\Support\Str::limit($room->type->description_fr ?? $room->view, 90) }}</div>
                                    @endif
                                    <div class="price-row">
                                        <div>
                                            <div class="ppn">{{ number_format($room->price, 0, ',', ' ') }} <small>{{ $hotel->currency }} / nuit</small></div>
                                            <div class="ptotal">Total séjour : <strong>{{ number_format($room->price * $nights, 0, ',', ' ') }} {{ $hotel->currency }}</strong></div>
                                        </div>
                                    </div>
                                    @if ($wa)
                                        <a class="cta wa" target="_blank" rel="noopener"
                                           href="https://wa.me/{{ $wa }}?text={{ urlencode("Bonjour {$hotel->name}, je souhaite réserver la « ".($room->type->name ?? 'chambre')." » (chambre {$room->number}) du ".\Carbon\Carbon::parse($checkIn)->format('d/m/Y')." au ".\Carbon\Carbon::parse($checkOut)->format('d/m/Y')." pour {$guests} voyageur(s).") }}">
                                            <i class="fab fa-whatsapp"></i> Réserver via WhatsApp
                                        </a>
                                    @elseif ($hotel->show_contact)
                                        <a class="cta" href="{{ route('public.hotel.contact', $hotel->slug) }}">Demander cette chambre</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p style="text-align:center; color:#9aa1ad; font-size:.82rem; margin-top:26px;">
                        <i class="fas fa-lock"></i> Paiement en ligne sécurisé bientôt disponible.
                    </p>
                @endif
            @else
                <p style="text-align:center; color:#6b7280; margin-top:34px;">
                    Choisissez vos dates ci-dessus pour voir les chambres réellement disponibles.
                </p>
            @endif

        </div>
    </section>
@endsection
