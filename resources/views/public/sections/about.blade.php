@php
    $roomCount = isset($rooms) ? $rooms->count() : 0;
@endphp
<section class="section" id="apropos">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="eyebrow mb-3">{{ __('public.about_welcome') }}</div>
                <h2 class="display-serif mb-4" style="font-size:clamp(2rem,4vw,3.2rem);">
                    {{ $hotel->aboutTitle() }}
                </h2>
                <p class="text-secondary" style="font-size:1.08rem;line-height:1.9;">
                    {{ $hotel->aboutText() }}
                </p>
                @if ($hotel->show_rooms)
                    <a href="{{ route('public.hotel.rooms', $hotel->slug) }}" class="btn-c mt-3">{{ __('public.about_view_rooms') }}</a>
                @endif
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="150">
                <div class="row g-4 text-center">
                    <div class="col-6">
                        <div class="p-4 lift" style="background:var(--card);border:1px solid var(--line);border-radius:6px;">
                            <div class="display-serif text-c" style="font-size:2.8rem;">{{ $roomCount ?: '∞' }}</div>
                            <div class="eyebrow mt-1" style="color:var(--ink);opacity:.6;">{{ __('public.about_rooms') }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-4 lift" style="background:var(--card);border:1px solid var(--line);border-radius:6px;">
                            <div class="display-serif text-c" style="font-size:2.8rem;">24/7</div>
                            <div class="eyebrow mt-1" style="color:var(--ink);opacity:.6;">{{ __('public.about_reception') }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-4 lift" style="background:var(--card);border:1px solid var(--line);border-radius:6px;">
                            <div class="display-serif text-c" style="font-size:2.8rem;">5★</div>
                            <div class="eyebrow mt-1" style="color:var(--ink);opacity:.6;">{{ __('public.about_service') }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-4 lift" style="background:var(--card);border:1px solid var(--line);border-radius:6px;">
                            <div class="display-serif text-c" style="font-size:2.8rem;">100%</div>
                            <div class="eyebrow mt-1" style="color:var(--ink);opacity:.6;">{{ __('public.about_satisfaction') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
