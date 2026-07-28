@php
    $faq = [
        [__('vitrine.faq_q1'), __('vitrine.faq_a1')],
        [__('vitrine.faq_q2'), __('vitrine.faq_a2')],
        [__('vitrine.faq_q3'), __('vitrine.faq_a3')],
        [__('vitrine.faq_q4'), __('vitrine.faq_a4')],
    ];
@endphp
<section class="section" id="faq" style="background:#faf9f7;">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="eyebrow mb-2">{{ __('vitrine.faq_eyebrow') }}</div>
            <h2 class="display-serif" style="font-size:clamp(2rem,4vw,3.2rem);">{{ __('vitrine.faq_heading') }}</h2>
            <div class="hero-divider" style="background:var(--c);opacity:.5;"></div>
        </div>
        <div class="accordion accordion-flush mx-auto" id="faqAcc" style="max-width:760px;" data-aos="fade-up">
            @foreach ($faq as $i => [$q, $a])
                <div class="accordion-item" style="background:transparent;border:none;border-bottom:1px solid #e8e4dd;">
                    <h2 class="accordion-header">
                        <button class="accordion-button {{ $i ? 'collapsed' : '' }} serif" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq{{ $i }}" style="background:transparent;font-size:1.2rem;box-shadow:none;color:var(--ink);padding:1.3rem 0;">
                            {{ $q }}
                        </button>
                    </h2>
                    <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i ? '' : 'show' }}" data-bs-parent="#faqAcc">
                        <div class="accordion-body text-secondary" style="padding:0 0 1.3rem;">{{ $a }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
