@extends('public.layout')
@section('title', __('public_booking.title'))

@push('head')
<style>
    .bk-wrap { max-width: 980px; margin: 40px auto 0; }
    .bk-grid { display:grid; grid-template-columns: 1.15fr .85fr; gap: 26px; align-items:start; }
    @media (max-width: 840px){ .bk-grid { grid-template-columns: 1fr; } }
    .bk-card { background:#fff; border:1px solid #ececf2; border-radius:18px; box-shadow:0 18px 48px -30px rgba(20,30,50,.35); overflow:hidden; }
    .bk-card .hd { padding:16px 20px; border-bottom:1px solid #f1f2f6; font-weight:800; font-size:1rem; color:#1f2733; display:flex; align-items:center; gap:9px; }
    .bk-card .hd i { color:var(--c); }
    .bk-body { padding:20px; }

    /* Récap chambre */
    .sum-room { display:flex; gap:14px; }
    .sum-room .thumb { width:110px; height:82px; border-radius:12px; background-size:cover; background-position:center; flex:none; }
    .sum-room .rt { font-size:.74rem; font-weight:800; text-transform:uppercase; letter-spacing:.05em; color:var(--c); }
    .sum-room .rn { font-weight:800; color:#1f2733; }
    .sum-room .rc { font-size:.82rem; color:#6b7280; }
    .sum-lines { margin-top:18px; display:flex; flex-direction:column; gap:9px; }
    .sum-line { display:flex; justify-content:space-between; font-size:.9rem; color:#4b5563; }
    .sum-line strong { color:#1f2733; }
    .sum-total { margin-top:12px; padding-top:14px; border-top:1px dashed #e5e7eb; display:flex; justify-content:space-between; align-items:baseline; }
    .sum-total .lbl { font-weight:700; color:#1f2733; }
    .sum-total .val { font-size:1.4rem; font-weight:900; color:#1f2733; }
    .sum-deposit { margin-top:12px; background:color-mix(in srgb, var(--c) 8%, #fff); border:1px solid color-mix(in srgb, var(--c) 22%, #fff); border-radius:12px; padding:12px 14px; font-size:.86rem; color:#374151; }
    .sum-deposit b { color:var(--c); }
    .promo-box { display:flex; gap:8px; margin-top:14px; }
    .promo-box input { flex:1; min-width:0; padding:10px 12px; border:1.5px solid #e3e6ee; border-radius:10px; font-size:.9rem; color:#1f2733; font-family:inherit; }
    .promo-box input:focus { outline:none; border-color:var(--c); box-shadow:0 0 0 3px color-mix(in srgb, var(--c) 18%, transparent); }
    .promo-box button { flex:none; border:1.5px solid var(--c); background:transparent; color:var(--c); border-radius:10px; padding:0 16px; font-weight:700; font-size:.85rem; cursor:pointer; }
    .promo-box button:hover { background:var(--c); color:#fff; }
    .promo-err { margin-top:8px; font-size:.82rem; color:#b91c1c; }
    .promo-ok { margin-top:8px; font-size:.82rem; color:#16a34a; font-weight:600; }
    .sum-dates { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin:16px 0 4px; }
    .sum-dates .sd-field:last-child { grid-column:1 / -1; }
    .sd-field label { display:block; font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#8891a3; margin-bottom:5px; }
    .sd-field input, .sd-field select { width:100%; padding:10px 12px; border:1.5px solid #e3e6ee; border-radius:10px; font-size:.92rem; color:#1f2733; font-family:inherit; background:#fff; }
    .sd-field input:focus, .sd-field select:focus { outline:none; border-color:var(--c); box-shadow:0 0 0 3px color-mix(in srgb, var(--c) 18%, transparent); }

    /* Form */
    .bk-field { margin-bottom:14px; }
    .bk-field label { display:block; font-size:.74rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#8891a3; margin-bottom:6px; }
    .bk-field input { width:100%; padding:11px 13px; border:1.5px solid #e3e6ee; border-radius:11px; font-size:.95rem; color:#1f2733; font-family:inherit; }
    .bk-field input:focus { outline:none; border-color:var(--c); box-shadow:0 0 0 3px color-mix(in srgb, var(--c) 18%, transparent); }
    .bk-field .fe { color:#b91c1c; font-size:.78rem; margin-top:5px; }
    .bk-submit { width:100%; background:var(--c); color:#fff; border:0; border-radius:12px; padding:14px; font-weight:800; font-size:1rem; cursor:pointer; margin-top:6px; display:inline-flex; align-items:center; justify-content:center; gap:9px; }
    .bk-submit:hover { filter:brightness(1.06); }
    .bk-alert { background:#fef2f2; border:1px solid #fecaca; color:#b91c1c; border-radius:12px; padding:12px 15px; margin-bottom:16px; font-size:.9rem; }
    .bk-note { font-size:.78rem; color:#9aa1ad; text-align:center; margin-top:12px; }
    .bk-back { display:inline-flex; align-items:center; gap:7px; color:#6b7280; text-decoration:none; font-size:.86rem; margin-bottom:14px; }
    .bk-back:hover { color:var(--c); }
</style>
@endpush

@section('content')
    <section class="section" style="padding-top:120px;">
        <div class="container bk-wrap">
            <a href="{{ route('public.hotel.availability', ['slug' => $hotel->slug, 'check_in' => $checkIn, 'check_out' => $checkOut, 'guests' => $guests]) }}" class="bk-back">
                <i class="fas fa-arrow-left"></i> {{ __('public_booking.back_to_rooms') }}
            </a>
            <h1 class="display-serif" style="font-size:clamp(1.8rem,4.5vw,2.6rem); margin-bottom:22px;">{{ __('public_booking.finalize') }}</h1>

            @if (session('booking_error'))
                <div class="bk-alert"><i class="fas fa-triangle-exclamation"></i> {{ session('booking_error') }}</div>
            @endif

            <div class="bk-grid">
                {{-- Formulaire voyageur --}}
                <div class="bk-card">
                    <div class="hd"><i class="fas fa-user"></i> {{ __('public_booking.your_details') }}</div>
                    <div class="bk-body">
                        <form id="bookingForm" method="POST" action="{{ route('public.hotel.booking.store', [$hotel->slug, $roomModel->id]) }}">
                            @csrf
                            <input type="hidden" name="promo_code" value="{{ $promo?->code ?? '' }}">

                            <div class="bk-field">
                                <label>{{ __('public_booking.full_name') }} *</label>
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="{{ __('public_booking.name_placeholder') }}" maxlength="255" required>
                                @error('name')<div class="fe">{{ $message }}</div>@enderror
                            </div>
                            <div class="bk-field">
                                <label>{{ __('public_booking.email') }} *</label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('public_booking.email_placeholder') }}" required>
                                @error('email')<div class="fe">{{ $message }}</div>@enderror
                            </div>
                            <div class="bk-field">
                                <label>{{ __('public_booking.phone') }} *</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="{{ __('public_booking.phone_placeholder') }}" pattern="[0-9+\s().\-]{6,20}" required>
                                @error('phone')<div class="fe">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="bk-submit"><i class="fas fa-check-circle"></i> {{ __('public_booking.confirm') }}</button>
                            <p class="bk-note"><i class="fas fa-lock"></i> {{ __('public_booking.note') }}</p>
                        </form>
                    </div>
                </div>

                {{-- Récapitulatif --}}
                <div class="bk-card">
                    <div class="hd"><i class="fas fa-receipt"></i> {{ __('public_booking.summary') }}</div>
                    <div class="bk-body">
                        <div class="sum-room">
                            <div class="thumb" style="background-image:url('{{ $roomModel->firstImage() }}')"></div>
                            <div>
                                <div class="rt">{{ $roomModel->type->name ?? __('public_booking.room') }}</div>
                                <div class="rn">{{ __('public_booking.room') }} {{ $roomModel->number }}</div>
                                <div class="rc"><i class="fas fa-user"></i> {{ $guests }} {{ __('public_booking.guest', $guests) }} · {{ __('public_booking.capacity') }} {{ $roomModel->capacity }}</div>
                            </div>
                        </div>

                        <div class="sum-dates">
                            <div class="sd-field">
                                <label>{{ __('public_booking.check_in') }}</label>
                                <input type="date" name="check_in" form="bookingForm" id="bkCheckIn" value="{{ $checkIn }}" min="{{ now()->format('Y-m-d') }}" required>
                            </div>
                            <div class="sd-field">
                                <label>{{ __('public_booking.check_out') }}</label>
                                <input type="date" name="check_out" form="bookingForm" id="bkCheckOut" value="{{ $checkOut }}" min="{{ now()->addDay()->format('Y-m-d') }}" required>
                            </div>
                            <div class="sd-field">
                                <label>{{ __('public_hero.guests') }}</label>
                                <select name="guests" form="bookingForm" id="bkGuests">
                                    @for ($i = 1; $i <= $roomModel->capacity; $i++)<option value="{{ $i }}" {{ (int)$guests === $i ? 'selected' : '' }}>{{ $i }}</option>@endfor
                                </select>
                            </div>
                        </div>

                        <div class="sum-lines">
                            <div class="sum-line"><span id="bkNightsLabel">{{ number_format($roomModel->price, 0, ',', ' ') }} {{ $hotel->currency }} × {{ $nights }} nuit{{ $nights > 1 ? 's' : '' }}</span> <strong id="bkSubtotal">{{ number_format($total, 0, ',', ' ') }} {{ $hotel->currency }}</strong></div>
                            @if (($discount ?? 0) > 0)
                                <div class="sum-line" style="color:#16a34a"><span><i class="fas fa-tag"></i> Code « {{ $promo->code }} »</span> <strong style="color:#16a34a">− {{ number_format($discount, 0, ',', ' ') }} {{ $hotel->currency }}</strong></div>
                            @endif
                        </div>

                        {{-- Code promo --}}
                        <form method="GET" action="{{ route('public.hotel.booking', [$hotel->slug, $roomModel->id]) }}" class="promo-box">
                            <input type="hidden" name="check_in" id="bkPromoCi" value="{{ $checkIn }}">
                            <input type="hidden" name="check_out" id="bkPromoCo" value="{{ $checkOut }}">
                            <input type="hidden" name="guests" id="bkPromoGuests" value="{{ $guests }}">
                            <input type="text" name="promo" value="{{ $promoRaw ?? '' }}" placeholder="{{ __('public_booking.promo_placeholder') }}" maxlength="40" style="text-transform:uppercase">
                            <button type="submit">{{ ($discount ?? 0) > 0 ? __('public_booking.promo_modify') : __('public_booking.promo_apply') }}</button>
                        </form>
                        @if (! empty($promoError))
                            <div class="promo-err"><i class="fas fa-circle-exclamation"></i> {{ $promoError }}</div>
                        @elseif (($discount ?? 0) > 0)
                            <div class="promo-ok"><i class="fas fa-circle-check"></i> {{ __('public_booking.promo_applied', ['amount' => number_format($discount, 0, ',', ' '), 'currency' => $hotel->currency]) }}</div>
                        @endif

                        <div class="sum-total">
                            <span class="lbl">{{ __('public_booking.total_stay') }}</span>
                            <span class="val">
                                @if (($discount ?? 0) > 0)<span style="font-size:.9rem;font-weight:600;color:#9aa1ad;text-decoration:line-through;margin-right:6px">{{ number_format($total, 0, ',', ' ') }}</span>@endif
                                <span id="bkTotal">{{ number_format($finalTotal ?? $total, 0, ',', ' ') }}</span> {{ $hotel->currency }}
                            </span>
                        </div>

                        <div class="sum-deposit">
                            <i class="fas fa-coins"></i> {{ __('public_booking.suggested_deposit') }} : <b><span id="bkDeposit">{{ number_format($deposit, 0, ',', ' ') }}</span> {{ $hotel->currency }}</b><br>
                            {{ __('public_booking.balance_due') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    (function () {
        var price = {{ (float) $roomModel->price }};
        var cur = @json($hotel->currency);
        var ci = document.getElementById('bkCheckIn'), co = document.getElementById('bkCheckOut'), gu = document.getElementById('bkGuests');
        if (!ci || !co) return;
        var nightsLabel = document.getElementById('bkNightsLabel'), subtotalEl = document.getElementById('bkSubtotal'),
            totalEl = document.getElementById('bkTotal'), depositEl = document.getElementById('bkDeposit'),
            pCi = document.getElementById('bkPromoCi'), pCo = document.getElementById('bkPromoCo'), pGu = document.getElementById('bkPromoGuests');
        function fmt(n) { return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' '); }
        function nextDay(v) { var x = new Date(v); x.setDate(x.getDate() + 1); return x.toISOString().slice(0, 10); }
        function recompute() {
            if (!ci.value) return;
            co.min = nextDay(ci.value);
            if (!co.value || co.value <= ci.value) co.value = nextDay(ci.value);
            var nights = Math.round((new Date(co.value) - new Date(ci.value)) / 86400000);
            if (!(nights > 0)) nights = 1;
            var subtotal = price * nights;
            nightsLabel.textContent = fmt(price) + ' ' + cur + ' × ' + nights + ' nuit' + (nights > 1 ? 's' : '');
            subtotalEl.textContent = fmt(subtotal) + ' ' + cur;
            totalEl.textContent = fmt(subtotal);
            depositEl.textContent = fmt(subtotal * 0.15);
            if (pCi) pCi.value = ci.value; if (pCo) pCo.value = co.value; if (pGu && gu) pGu.value = gu.value;
        }
        ci.addEventListener('change', recompute);
        co.addEventListener('change', recompute);
        if (gu) gu.addEventListener('change', function () { if (pGu) pGu.value = gu.value; });
    })();
    </script>
@endsection
