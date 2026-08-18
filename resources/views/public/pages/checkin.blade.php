@extends('public.layout')
@section('title', __('public_checkin.title'))

@push('head')
<style>
    .ci-wrap { max-width: 640px; margin: 40px auto 0; }
    .ci-card { background:#fff; border:1px solid #ececf2; border-radius:18px; box-shadow:0 18px 48px -30px rgba(20,30,50,.35); overflow:hidden; margin-bottom:20px; }
    .ci-card .hd { padding:16px 20px; border-bottom:1px solid #f1f2f6; font-weight:800; color:#1f2733; display:flex; align-items:center; gap:9px; }
    .ci-card .hd i { color:var(--c); }
    .ci-body { padding:20px; }
    .ci-recap { display:flex; gap:8px; flex-wrap:wrap; font-size:.85rem; color:#4b5563; }
    .ci-recap span { background:#f3f4f8; border-radius:8px; padding:5px 10px; }
    .ci-field { margin-bottom:14px; }
    .ci-field label { display:block; font-size:.74rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#8891a3; margin-bottom:6px; }
    .ci-field input, .ci-field select, .ci-field textarea { width:100%; padding:11px 13px; border:1.5px solid #e3e6ee; border-radius:11px; font-size:.95rem; color:#1f2733; font-family:inherit; background:#fff; }
    .ci-field input:focus, .ci-field select:focus, .ci-field textarea:focus { outline:none; border-color:var(--c); box-shadow:0 0 0 3px color-mix(in srgb, var(--c) 18%, transparent); }
    .ci-field .fe { color:#b91c1c; font-size:.78rem; margin-top:5px; }
    .ci-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
    @media (max-width:520px){ .ci-row { grid-template-columns:1fr; } }
    .ci-submit { width:100%; background:var(--c); color:#fff; border:0; border-radius:12px; padding:14px; font-weight:800; font-size:1rem; cursor:pointer; margin-top:6px; display:inline-flex; align-items:center; justify-content:center; gap:9px; }
    .ci-submit:hover { filter:brightness(1.06); }
    .ci-note { font-size:.78rem; color:#9aa1ad; text-align:center; margin-top:12px; }
    .ci-done-ic { width:84px; height:84px; border-radius:50%; margin:0 auto 18px; display:grid; place-items:center; font-size:2.1rem; background:color-mix(in srgb, var(--c) 14%, #fff); color:var(--c); }
    .ci-done { text-align:center; }
    .ci-done .line { display:flex; justify-content:space-between; gap:12px; padding:8px 0; font-size:.92rem; color:#4b5563; border-bottom:1px solid #f1f2f6; text-align:left; }
    .ci-done .line strong { color:#1f2733; }
</style>
@endpush

@section('content')
    @php
        $ci = \Carbon\Carbon::parse($tx->check_in);
        $co = \Carbon\Carbon::parse($tx->check_out);
        $done = $tx->preCheckinDone() || session('checkin_done');
        $c = $tx->customer;
        $pc = $tx->pre_checkin ?? [];
    @endphp

    <section class="section" style="padding-top:110px;">
        <div class="container ci-wrap">
            <div class="eyebrow mb-2" style="color:var(--c);">{{ __('public_checkin.subtitle') }}</div>
            <h1 class="display-serif" style="font-size:clamp(1.8rem,4.5vw,2.6rem); margin-bottom:8px;">
                {{ $done ? __('public_checkin.all_ready') : __('public_checkin.save_time') }}
            </h1>
            <p style="color:#6b7280; margin-bottom:22px;">
                {{ $done
                    ? __('public_checkin.info_registered')
                    : __('public_checkin.fill_before', ['hotel' => $hotel->name]) }}
            </p>

            <div class="ci-card">
                <div class="hd"><i class="fas fa-bed"></i> {{ __('public_checkin.your_stay') }}</div>
                <div class="ci-body">
                    <div class="ci-recap">
                        <span><i class="fas fa-door-closed"></i> {{ $tx->room->type->name ?? __('public_booking.room') }} · n°{{ $tx->room->number ?? '-' }}</span>
                        <span><i class="fas fa-calendar-day"></i> {{ $ci->translatedFormat('d M') }} → {{ $co->translatedFormat('d M Y') }}</span>
                        <span><i class="fas fa-user"></i> {{ $tx->person_count }} {{ __('public_booking.guest', $tx->person_count) }}</span>
                    </div>
                </div>
            </div>

            @if ($done)
                <div class="ci-card">
                    <div class="ci-body ci-done">
                        <div class="ci-done-ic"><i class="fas fa-check"></i></div>
                        <div style="max-width:420px;margin:0 auto;">
                            <div class="line"><span>{{ __('public_checkin.name') }}</span> <strong>{{ $c->name ?? '' }}</strong></div>
                            <div class="line"><span>{{ __('public_checkin.phone') }}</span> <strong>{{ $c->phone ?? '' }}</strong></div>
                            @if (! empty($pc['id_type']))<div class="line"><span>{{ __('public_checkin.id_document') }}</span> <strong>{{ $pc['id_type'] }} · {{ $pc['id_number'] ?? '' }}</strong></div>@endif
                            @if (! empty($pc['arrival_time']))<div class="line"><span>{{ __('public_checkin.estimated_arrival') }}</span> <strong>{{ $pc['arrival_time'] }}</strong></div>@endif
                        </div>
                        <p class="ci-note" style="margin-top:18px;"><i class="fas fa-circle-check"></i> {{ __('public_checkin.thanks_message', ['hotel' => $hotel->name]) }}</p>
                    </div>
                </div>
            @else
                <div class="ci-card">
                    <div class="hd"><i class="fas fa-id-card"></i> {{ __('public_checkin.your_info') }}</div>
                    <div class="ci-body">
                        <form method="POST" action="{{ route('public.checkin.store', $tx->checkin_token) }}">
                            @csrf
                            <div class="ci-field">
                                <label>{{ __('public_checkin.full_name') }} *</label>
                                <input type="text" name="name" value="{{ old('name', $c->name ?? '') }}" maxlength="255" required>
                                @error('name')<div class="fe">{{ $message }}</div>@enderror
                            </div>
                            <div class="ci-row">
                                <div class="ci-field">
                                    <label>{{ __('public_checkin.phone') }} *</label>
                                    <input type="tel" name="phone" value="{{ old('phone', $c->phone ?? '') }}" pattern="[0-9+\s().\-]{6,20}" required>
                                    @error('phone')<div class="fe">{{ $message }}</div>@enderror
                                </div>
                                <div class="ci-field">
                                    <label>{{ __('public_checkin.email') }}</label>
                                    <input type="email" name="email" value="{{ old('email', $c->email ?? '') }}">
                                    @error('email')<div class="fe">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="ci-field">
                                <label>{{ __('public_checkin.address') }}</label>
                                <input type="text" name="address" value="{{ old('address', $c->address ?? '') }}" maxlength="255" placeholder="{{ __('public_checkin.address_placeholder') }}">
                            </div>
                            <div class="ci-row">
                                <div class="ci-field">
                                    <label>{{ __('public_checkin.id_type') }} *</label>
                                    <select name="id_type" required>
                                        @foreach (['CNI' => __('public_checkin.cni'), 'Passeport' => __('public_checkin.passport'), 'Permis' => __('public_checkin.drivers_license'), 'Autre' => __('public_checkin.other')] as $v => $lbl)
                                            <option value="{{ $v }}" {{ old('id_type') === $v ? 'selected' : '' }}>{{ $lbl }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_type')<div class="fe">{{ $message }}</div>@enderror
                                </div>
                                <div class="ci-field">
                                    <label>{{ __('public_checkin.id_number') }} *</label>
                                    <input type="text" name="id_number" value="{{ old('id_number') }}" maxlength="60" required>
                                    @error('id_number')<div class="fe">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="ci-row">
                                <div class="ci-field">
                                    <label>{{ __('public_checkin.estimated_arrival') }}</label>
                                    <input type="text" name="arrival_time" value="{{ old('arrival_time') }}" placeholder="{{ __('public_checkin.arrival_placeholder') }}" maxlength="20">
                                </div>
                            </div>
                            <div class="ci-field">
                                <label>{{ __('public_checkin.special_requests') }}</label>
                                <textarea name="special_requests" rows="2" maxlength="500" placeholder="{{ __('public_checkin.special_requests_placeholder') }}">{{ old('special_requests') }}</textarea>
                            </div>

                            <button type="submit" class="ci-submit"><i class="fas fa-check-circle"></i> {{ __('public_checkin.submit') }}</button>
                            <p class="ci-note"><i class="fas fa-lock"></i> {{ __('public_checkin.privacy', ['hotel' => $hotel->name]) }}</p>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
