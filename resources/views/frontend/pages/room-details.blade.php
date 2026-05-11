@extends('frontend.layouts.master')

@section('title', $room->name . ' — Cactus Palace 5 Étoiles')

@push('styles')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
:root {
    --cactus-green: #1A472A;
    --cactus-light: #2E5C3F;
    --cactus-dark:  #0F2918;
    --gold-accent:  #C9A961;
    --gold-light:   #E8D5A3;
    --light-bg:     #F8FAF9;
    --white:        #FFFFFF;
    --text-dark:    #1A1A1A;
    --text-gray:    #6B7280;
    --border-color: #E5E7EB;
    --transition:   all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    --shadow-sm:    0 2px 8px rgba(0,0,0,0.06);
    --shadow-md:    0 8px 24px rgba(0,0,0,0.10);
    --shadow-lg:    0 20px 50px rgba(0,0,0,0.14);
}

/* ── HERO ── */
.rd-hero {
    position: relative;
    min-height: 72vh;
    display: flex;
    align-items: flex-end;
    background: url('{{ $room->first_image_url ?? asset('img/room/gamesetting.png') }}') center/cover no-repeat;
    background-attachment: fixed;
    overflow: hidden;
}
.rd-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(15,41,24,0.96) 0%, rgba(15,41,24,0.55) 40%, rgba(15,41,24,0.15) 100%);
}
.rd-hero .container { position: relative; z-index: 2; padding-bottom: 60px; }

.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 7px 20px;
    background: rgba(201,169,97,0.15);
    border: 1px solid rgba(201,169,97,0.4);
    border-radius: 50px;
    color: var(--gold-accent);
    font-size: 11px; font-weight: 600; letter-spacing: 3px; text-transform: uppercase;
    margin-bottom: 20px;
}
.rd-hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.4rem, 5vw, 4rem);
    font-weight: 700; color: var(--white); line-height: 1.15;
    margin-bottom: 16px; letter-spacing: -0.5px;
}
.rd-hero h1 em { font-style: italic; color: var(--gold-accent); }

.hero-badges {
    display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 28px;
}
.hero-badge-item {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 8px 18px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 50px;
    color: var(--white); font-size: 13px; font-weight: 500;
    backdrop-filter: blur(10px);
}
.hero-badge-item i { color: var(--gold-accent); }

.availability-badge {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 22px; border-radius: 50px; font-size: 13px; font-weight: 600;
}
.availability-badge.avail {
    background: rgba(34,197,94,0.2); border: 1px solid rgba(34,197,94,0.5); color: #86efac;
}
.availability-badge.unavail {
    background: rgba(239,68,68,0.2); border: 1px solid rgba(239,68,68,0.4); color: #fca5a5;
}
.availability-badge .dot {
    width: 8px; height: 8px; border-radius: 50%;
    animation: pulse-dot 1.8s infinite;
}
.availability-badge.avail .dot { background: #22c55e; }
.availability-badge.unavail .dot { background: #ef4444; }
@keyframes pulse-dot {
    0%,100% { transform: scale(1); opacity: 1; }
    50%      { transform: scale(.7); opacity: .6; }
}

/* ── MAIN CONTENT ── */
.rd-main { background: var(--light-bg); padding: 60px 0 80px; }

/* ── GALLERY ── */
.gallery-main {
    border-radius: 20px; overflow: hidden;
    box-shadow: var(--shadow-lg); position: relative;
    background: #111; margin-bottom: 16px;
}
.gallery-main img {
    width: 100%; height: 460px; object-fit: cover; display: block;
    transition: opacity .35s ease;
}
.gallery-nav {
    position: absolute; top: 50%; transform: translateY(-50%);
    left: 0; right: 0; display: flex; justify-content: space-between;
    padding: 0 16px; pointer-events: none;
}
.gallery-nav-btn {
    pointer-events: all;
    width: 44px; height: 44px; border-radius: 50%;
    background: rgba(255,255,255,0.9); border: none;
    display: flex; align-items: center; justify-content: center;
    color: var(--cactus-green); font-size: 0.85rem; cursor: pointer;
    transition: var(--transition); box-shadow: var(--shadow-md);
}
.gallery-nav-btn:hover { background: var(--cactus-green); color: var(--white); transform: scale(1.08); }

.gallery-counter {
    position: absolute; bottom: 16px; right: 16px;
    padding: 5px 12px; background: rgba(0,0,0,0.55);
    backdrop-filter: blur(6px); color: var(--white);
    border-radius: 50px; font-size: 12px;
}
.gallery-availability {
    position: absolute; top: 16px; left: 16px;
}

.gallery-thumbs {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;
}
.gallery-thumb {
    border-radius: 12px; overflow: hidden;
    height: 90px; cursor: pointer;
    border: 2px solid transparent; transition: var(--transition);
    background: #111;
}
.gallery-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s ease; }
.gallery-thumb:hover img { transform: scale(1.06); }
.gallery-thumb.active { border-color: var(--gold-accent); }

/* ── DESCRIPTION CARD ── */
.rd-card {
    background: var(--white); border-radius: 20px;
    border: 1px solid var(--border-color); padding: 32px;
    margin-bottom: 20px; box-shadow: var(--shadow-sm);
}
.rd-card-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem; font-weight: 700; color: var(--text-dark);
    margin-bottom: 18px;
    display: flex; align-items: center; gap: 10px;
}
.rd-card-title i { color: var(--cactus-green); }
.rd-card-title .title-line {
    flex: 1; height: 1px; background: var(--border-color);
}

.room-lead { font-size: 1.05rem; color: var(--cactus-green); font-weight: 600; margin-bottom: 10px; }
.room-body-text { font-size: 0.95rem; color: var(--text-gray); line-height: 1.85; }

/* ── FACILITIES ── */
.facility-item {
    display: flex; align-items: center; gap: 14px;
    padding: 14px; background: var(--light-bg);
    border-radius: 12px; border: 1px solid var(--border-color);
    transition: var(--transition);
}
.facility-item:hover {
    background: var(--white); box-shadow: var(--shadow-sm);
    transform: translateX(4px);
}
.facility-icon-wrap {
    width: 42px; height: 42px; min-width: 42px; border-radius: 10px;
    background: linear-gradient(135deg, var(--cactus-green), var(--cactus-light));
    display: flex; align-items: center; justify-content: center;
    color: var(--white); font-size: 1rem;
}
.facility-info strong { font-size: 0.9rem; color: var(--text-dark); }
.facility-info small { font-size: 0.8rem; color: var(--text-gray); }

/* ── BOOKING CARD ── */
.booking-sticky { position: sticky; top: 96px; }

.booking-card {
    background: var(--white); border-radius: 24px;
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-lg); overflow: hidden;
}
.booking-header {
    background: linear-gradient(135deg, var(--cactus-dark), var(--cactus-green));
    padding: 28px 28px 22px;
    position: relative; overflow: hidden;
}
.booking-header::after {
    content: '';
    position: absolute; bottom: -30px; right: -30px;
    width: 120px; height: 120px; border-radius: 50%;
    background: rgba(201,169,97,0.12);
}
.booking-header .bh-eyebrow {
    font-size: 11px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--gold-accent); font-weight: 600; margin-bottom: 8px;
}
.booking-header h4 {
    font-family: 'Playfair Display', serif; font-size: 1.3rem;
    color: var(--white); margin-bottom: 4px;
}
.booking-header p { font-size: 0.85rem; color: rgba(255,255,255,0.65); margin: 0; }

.booking-price-box {
    background: var(--light-bg); margin: 0; padding: 20px 28px;
    border-bottom: 1px solid var(--border-color);
    display: flex; align-items: baseline; gap: 8px;
}
.booking-price-box .price {
    font-family: 'Playfair Display', serif;
    font-size: 2rem; font-weight: 700; color: var(--cactus-green);
}
.booking-price-box .per-night { font-size: 0.85rem; color: var(--text-gray); }

.booking-body { padding: 24px 28px; }

.booking-label {
    font-size: 11px; font-weight: 700; letter-spacing: 1.5px;
    text-transform: uppercase; color: var(--cactus-green); margin-bottom: 8px; display: block;
}
.booking-input {
    width: 100%; padding: 12px 14px;
    border: 1.5px solid var(--border-color); border-radius: 10px;
    font-size: 0.9rem; color: var(--text-dark);
    transition: var(--transition); background: var(--white);
}
.booking-input:focus {
    outline: none; border-color: var(--cactus-green);
    box-shadow: 0 0 0 3px rgba(26,71,42,0.08);
}

.btn-book-now {
    display: flex; align-items: center; justify-content: center; gap: 10px;
    width: 100%; padding: 16px;
    background: var(--cactus-green); color: var(--white);
    border: 2px solid var(--cactus-green); border-radius: 12px;
    font-size: 0.95rem; font-weight: 700; cursor: pointer;
    text-decoration: none; transition: var(--transition); margin-bottom: 10px;
}
.btn-book-now:hover {
    background: transparent; color: var(--cactus-green);
    box-shadow: 0 8px 24px rgba(26,71,42,0.18);
}

.btn-contact-us {
    display: flex; align-items: center; justify-content: center; gap: 10px;
    width: 100%; padding: 13px;
    background: transparent; color: var(--cactus-green);
    border: 2px solid var(--cactus-green); border-radius: 12px;
    font-size: 0.88rem; font-weight: 600; cursor: pointer;
    text-decoration: none; transition: var(--transition);
}
.btn-contact-us:hover { background: var(--cactus-green); color: var(--white); }

.booking-footer {
    background: var(--light-bg); padding: 18px 28px;
    border-top: 1px solid var(--border-color);
}
.info-row {
    display: flex; align-items: center; gap: 10px;
    font-size: 0.875rem; color: var(--text-gray); margin-bottom: 8px;
}
.info-row:last-child { margin-bottom: 0; }
.info-row i { color: var(--cactus-green); width: 16px; font-size: 0.85rem; }

/* ── RELATED ROOMS ── */
.related-section { background: var(--white); padding: 70px 0; }

.related-card {
    background: var(--white); border-radius: 16px; overflow: hidden;
    border: 1px solid var(--border-color); transition: var(--transition);
    height: 100%;
}
.related-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-lg); border-color: transparent; }
.related-card .card-img { height: 200px; overflow: hidden; position: relative; }
.related-card .card-img img {
    width: 100%; height: 100%; object-fit: cover; transition: transform .5s ease;
}
.related-card:hover .card-img img { transform: scale(1.07); }

.related-badge {
    position: absolute; top: 12px; right: 12px;
    padding: 4px 10px; border-radius: 50px;
    font-size: 11px; font-weight: 700; text-transform: uppercase;
}
.related-badge.avail { background: rgba(34,197,94,0.9); color: var(--white); }
.related-badge.unavail { background: rgba(239,68,68,0.9); color: var(--white); }

.related-body { padding: 20px; }
.related-body h5 { font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--text-dark); margin-bottom: 4px; }
.related-type { font-size: 0.82rem; color: var(--text-gray); margin-bottom: 14px; }
.related-footer { display: flex; justify-content: space-between; align-items: center; }
.related-price { font-size: 1.2rem; font-weight: 700; color: var(--cactus-green); }
.related-price small { font-size: 0.75rem; color: var(--text-gray); font-weight: 400; }
.btn-related-view {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; background: var(--cactus-green); color: var(--white);
    border-radius: 8px; font-size: 0.82rem; font-weight: 600;
    text-decoration: none; transition: var(--transition);
    border: 2px solid var(--cactus-green);
}
.btn-related-view:hover { background: transparent; color: var(--cactus-green); }

/* ── RESPONSIVE ── */
@media (max-width: 991px) {
    .booking-sticky { position: static; margin-top: 28px; }
    .rd-hero { min-height: 60vh; background-attachment: scroll; }
    .gallery-main img { height: 360px; }
}
@media (max-width: 767px) {
    .gallery-thumbs { grid-template-columns: repeat(4, 1fr); gap: 6px; }
    .gallery-thumb { height: 70px; }
    .rd-card { padding: 22px; }
    .booking-header { padding: 22px 22px 18px; }
    .booking-body { padding: 20px 22px; }
    .booking-footer { padding: 14px 22px; }
    .hero-badges { gap: 8px; }
    .hero-badge-item { font-size: 12px; padding: 7px 14px; }
}
@media (max-width: 576px) {
    .gallery-main img { height: 260px; }
    .gallery-thumbs { grid-template-columns: repeat(4, 1fr); gap: 4px; }
    .gallery-thumb { height: 58px; }
    .rd-hero { min-height: 75vh; }
}
</style>
@endpush

@section('content')

{{-- ── HERO ── --}}
<section class="rd-hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-9" data-aos="fade-up" data-aos-duration="800">
                <div class="hero-eyebrow">
                    <i class="fas fa-star" style="font-size:9px;"></i>
                    Chambre — Cactus Palace 5 Étoiles
                    <i class="fas fa-star" style="font-size:9px;"></i>
                </div>
                <h1>
                    {{ $room->name }}<br>
                    <em>{{ $room->type->name ?? 'Suite Premium' }}</em>
                </h1>
                <div class="hero-badges">
                    <div class="hero-badge-item">
                        <i class="fas fa-users"></i>{{ $room->capacity }} personne{{ $room->capacity > 1 ? 's' : '' }}
                    </div>
                    <div class="hero-badge-item">
                        <i class="fas fa-expand-arrows-alt"></i>{{ $room->size ?? '25' }} m²
                    </div>
                    @if($room->floor)
                    <div class="hero-badge-item">
                        <i class="fas fa-building"></i>Étage {{ $room->floor }}
                    </div>
                    @endif
                    @if($room->view)
                    <div class="hero-badge-item">
                        <i class="fas fa-eye"></i>{{ $room->view }}
                    </div>
                    @endif
                </div>
                <div class="availability-badge {{ $room->is_available_today ? 'avail' : 'unavail' }}">
                    <span class="dot"></span>
                    {{ $room->is_available_today ? 'Disponible aujourd\'hui' : 'Non disponible ce jour' }}
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── MAIN ── --}}
<section class="rd-main">
    <div class="container">
        <div class="row g-4">

            {{-- ── LEFT COLUMN ── --}}
            <div class="col-lg-8">

                {{-- Gallery --}}
                <div data-aos="fade-up">
                    <div class="gallery-main">
                        @php
                            $mainImage = asset('img/default/default-room.png');
                            if($room->images && $room->images->count() > 0) {
                                $firstImage = $room->images->first();
                                $testPath = 'img/room/' . $room->number . '/' . $firstImage->url;
                                if(file_exists(public_path($testPath))) $mainImage = asset($testPath);
                            }
                        @endphp
                        <img src="{{ $mainImage }}" alt="{{ $room->name }}" id="mainRoomImage">

                        {{-- Availability overlay --}}
                        <div class="gallery-availability">
                            <span class="availability-badge {{ $room->is_available_today ? 'avail' : 'unavail' }}" style="font-size:12px;padding:6px 14px;">
                                <span class="dot"></span>
                                {{ $room->is_available_today ? 'Disponible' : 'Non disponible' }}
                            </span>
                        </div>

                        @if($room->images && $room->images->count() > 1)
                        <div class="gallery-nav">
                            <button class="gallery-nav-btn" id="prevImg"><i class="fas fa-chevron-left"></i></button>
                            <button class="gallery-nav-btn" id="nextImg"><i class="fas fa-chevron-right"></i></button>
                        </div>
                        <div class="gallery-counter" id="imgCounter">1 / {{ $room->images->count() }}</div>
                        @endif
                    </div>

                    {{-- Thumbnails --}}
                    @if($room->images && $room->images->count() > 1)
                    <div class="gallery-thumbs">
                        @foreach($room->images->take(4) as $index => $image)
                        @php
                            $thumbPath = 'img/room/' . $room->number . '/' . $image->url;
                            $thumbUrl = file_exists(public_path($thumbPath)) ? asset($thumbPath) : asset('img/default/default-room.png');
                        @endphp
                        <div class="gallery-thumb {{ $index === 0 ? 'active' : '' }}"
                             data-src="{{ $thumbUrl }}"
                             data-index="{{ $index }}"
                             onclick="selectThumb(this)">
                            <img src="{{ $thumbUrl }}" alt="Photo {{ $index+1 }}">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Description --}}
                <div class="rd-card mt-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="rd-card-title">
                        <i class="fas fa-align-left"></i>
                        Description
                        <div class="title-line"></div>
                    </div>
                    <p class="room-lead">{{ $room->name }} — {{ $room->type->name ?? 'Chambre Standard' }}</p>
                    <p class="room-body-text">{{ $room->type->description_fr ?? 'Profitez d\'un séjour exceptionnel dans cette chambre luxueuse dotée de tous les équipements haut de gamme.' }}</p>
                    @if($room->description)
                    <p class="room-body-text mt-2">{{ $room->description }}</p>
                    @endif
                </div>

                {{-- Facilities --}}
                @if($room->facilities && $room->facilities->count() > 0)
                <div class="rd-card" data-aos="fade-up" data-aos-delay="150">
                    <div class="rd-card-title">
                        <i class="fas fa-concierge-bell"></i>
                        Équipements & Services
                        <div class="title-line"></div>
                    </div>
                    <div class="row g-3">
                        @foreach($room->facilities as $facility)
                        <div class="col-md-6">
                            <div class="facility-item">
                                <div class="facility-icon-wrap">
                                    <i class="fas fa-{{ $facility->icon ?? 'check' }}"></i>
                                </div>
                                <div class="facility-info">
                                    <strong>{{ $facility->name }}</strong>
                                    @if($facility->description)
                                    <br><small>{{ $facility->description }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Specs card --}}
                <div class="rd-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="rd-card-title">
                        <i class="fas fa-info-circle"></i>
                        Informations détaillées
                        <div class="title-line"></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-3">
                            <div style="text-align:center;padding:16px;background:var(--light-bg);border-radius:12px;border:1px solid var(--border-color);">
                                <i class="fas fa-users" style="color:var(--cactus-green);font-size:1.4rem;margin-bottom:8px;display:block;"></i>
                                <div style="font-weight:700;color:var(--text-dark);font-size:1.1rem;">{{ $room->capacity }}</div>
                                <div style="font-size:0.8rem;color:var(--text-gray);">Personnes</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div style="text-align:center;padding:16px;background:var(--light-bg);border-radius:12px;border:1px solid var(--border-color);">
                                <i class="fas fa-expand-arrows-alt" style="color:var(--cactus-green);font-size:1.4rem;margin-bottom:8px;display:block;"></i>
                                <div style="font-weight:700;color:var(--text-dark);font-size:1.1rem;">{{ $room->size ?? '25' }} m²</div>
                                <div style="font-size:0.8rem;color:var(--text-gray);">Superficie</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div style="text-align:center;padding:16px;background:var(--light-bg);border-radius:12px;border:1px solid var(--border-color);">
                                <i class="fas fa-building" style="color:var(--cactus-green);font-size:1.4rem;margin-bottom:8px;display:block;"></i>
                                <div style="font-weight:700;color:var(--text-dark);font-size:1.1rem;">{{ $room->floor ?? 'RDC' }}</div>
                                <div style="font-size:0.8rem;color:var(--text-gray);">Étage</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div style="text-align:center;padding:16px;background:var(--light-bg);border-radius:12px;border:1px solid var(--border-color);">
                                <i class="fas fa-door-open" style="color:var(--cactus-green);font-size:1.4rem;margin-bottom:8px;display:block;"></i>
                                <div style="font-weight:700;color:var(--text-dark);font-size:1.1rem;">{{ $room->number }}</div>
                                <div style="font-size:0.8rem;color:var(--text-gray);">Numéro</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── RIGHT COLUMN — BOOKING ── --}}
            <div class="col-lg-4">
                <div class="booking-sticky" data-aos="fade-left" data-aos-delay="100">
                    <div class="booking-card">
                        {{-- Header --}}
                        <div class="booking-header">
                            <div class="bh-eyebrow">Réservation</div>
                            <h4>Réserver cette chambre</h4>
                            <p>Séjournez dans l'excellence</p>
                        </div>

                        {{-- Price --}}
                        <div class="booking-price-box">
                            <span class="price">{{ $room->type->formatted_price ?? 'N/A' }}</span>
                            <span class="per-night">par nuit</span>
                        </div>

                        {{-- Form --}}
                        <div class="booking-body">
                            <form action="{{ route('frontend.reservation') }}" method="GET">
                                <input type="hidden" name="room_id" value="{{ $room->id }}">

                                <div class="mb-3">
                                    <label class="booking-label">Dates de séjour</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="date" class="booking-input" name="check_in" id="check_in"
                                                   value="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                                   min="{{ date('Y-m-d') }}">
                                            <div style="font-size:11px;color:var(--text-gray);margin-top:4px;">Arrivée</div>
                                        </div>
                                        <div class="col-6">
                                            <input type="date" class="booking-input" name="check_out" id="check_out"
                                                   value="{{ date('Y-m-d', strtotime('+2 day')) }}"
                                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                            <div style="font-size:11px;color:var(--text-gray);margin-top:4px;">Départ</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="booking-label" for="adults">Nombre de personnes</label>
                                    <select class="booking-input" name="adults" id="adults" style="cursor:pointer;">
                                        @for($i = 1; $i <= $room->capacity; $i++)
                                        <option value="{{ $i }}" {{ $i == min(2, $room->capacity) ? 'selected' : '' }}>
                                            {{ $i }} personne{{ $i > 1 ? 's' : '' }}
                                        </option>
                                        @endfor
                                    </select>
                                </div>

                                <button type="submit" class="btn-book-now">
                                    <i class="fas fa-calendar-check"></i> Réserver maintenant
                                </button>
                                <a href="{{ route('frontend.contact') }}?room_id={{ $room->id }}" class="btn-contact-us">
                                    <i class="fas fa-envelope"></i> Nous contacter
                                </a>

                                <p class="text-center mt-3 mb-0" style="font-size:12px;color:var(--text-gray);">
                                    <i class="fas fa-lock me-1" style="color:var(--cactus-green);"></i>
                                    Réservation 100% sécurisée
                                </p>
                            </form>
                        </div>

                        {{-- Footer --}}
                        <div class="booking-footer">
                            <div class="info-row">
                                <i class="fas fa-clock"></i>
                                <span>Check-in : 15h00 – 22h00</span>
                            </div>
                            <div class="info-row">
                                <i class="fas fa-clock"></i>
                                <span>Check-out : avant 13h00</span>
                            </div>
                            <div class="info-row">
                                <i class="fas fa-wifi"></i>
                                <span>Wi-Fi haut débit gratuit</span>
                            </div>
                            <div class="info-row">
                                <i class="fas fa-undo"></i>
                                <span>Annulation gratuite 48h avant</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ── RELATED ROOMS ── --}}
@if(isset($relatedRooms) && $relatedRooms->count() > 0)
<section class="related-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span style="display:inline-block;padding:5px 18px;background:rgba(26,71,42,0.08);color:var(--cactus-green);border-radius:50px;font-size:11px;font-weight:600;letter-spacing:2px;text-transform:uppercase;margin-bottom:14px;">Suggestions</span>
            <h2 style="font-family:'Playfair Display',serif;font-size:clamp(1.8rem,3vw,2.5rem);color:var(--text-dark);margin:0;">Chambres similaires</h2>
        </div>
        <div class="row g-4">
            @foreach($relatedRooms as $relatedRoom)
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                <div class="related-card">
                    <div class="card-img">
                        @php
                            $relatedImage = asset('img/default/default-room.png');
                            if($relatedRoom->images && $relatedRoom->images->count() > 0) {
                                $testPath = 'img/room/' . $relatedRoom->number . '/' . $relatedRoom->images->first()->url;
                                if(file_exists(public_path($testPath))) $relatedImage = asset($testPath);
                            }
                        @endphp
                        <img src="{{ $relatedImage }}" alt="{{ $relatedRoom->name }}">
                        <span class="related-badge {{ $relatedRoom->is_available_today ? 'avail' : 'unavail' }}">
                            {{ $relatedRoom->is_available_today ? 'Disponible' : 'Sur demande' }}
                        </span>
                    </div>
                    <div class="related-body">
                        <h5>{{ $relatedRoom->name }}</h5>
                        <div class="related-type">{{ $relatedRoom->type->name ?? 'Standard' }} · {{ $relatedRoom->capacity }} pers.</div>
                        <div class="related-footer">
                            <div>
                                <span class="related-price">{{ number_format($relatedRoom->price, 0, ',', ' ') }} FCFA</span><br>
                                <small>par nuit</small>
                            </div>
                            <a href="{{ route('frontend.room.details', $relatedRoom->id) }}" class="btn-related-view">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({ duration: 750, easing: 'ease-out-cubic', once: true, offset: 60 });

// Gallery
const images = [
    @if($room->images && $room->images->count() > 0)
        @foreach($room->images as $image)
        @php $imgPath = 'img/room/' . $room->number . '/' . $image->url; @endphp
        '{{ file_exists(public_path($imgPath)) ? asset($imgPath) : asset('img/room/gamesetting.png') }}',
        @endforeach
    @else
        '{{ asset('img/default/default-room.png') }}'
    @endif
];

let currentIndex = 0;

function setImage(index) {
    currentIndex = Math.max(0, Math.min(images.length - 1, index));
    const main = document.getElementById('mainRoomImage');
    if (main) {
        main.style.opacity = '0';
        setTimeout(() => {
            main.src = images[currentIndex];
            main.style.opacity = '1';
        }, 180);
    }
    const counter = document.getElementById('imgCounter');
    if (counter) counter.textContent = (currentIndex + 1) + ' / ' + images.length;

    document.querySelectorAll('.gallery-thumb').forEach((t, i) => {
        t.classList.toggle('active', i === currentIndex);
    });
}

function selectThumb(el) {
    setImage(parseInt(el.dataset.index));
}

document.getElementById('prevImg')?.addEventListener('click', () => setImage(currentIndex - 1));
document.getElementById('nextImg')?.addEventListener('click', () => setImage(currentIndex + 1));

// Date logic
const checkIn = document.getElementById('check_in');
const checkOut = document.getElementById('check_out');
if (checkIn && checkOut) {
    checkIn.addEventListener('change', function () {
        const next = new Date(this.value);
        next.setDate(next.getDate() + 1);
        const fmt = d => d.toISOString().split('T')[0];
        checkOut.min = fmt(next);
        if (checkOut.value <= this.value) checkOut.value = fmt(next);
    });
}
</script>
@endpush
