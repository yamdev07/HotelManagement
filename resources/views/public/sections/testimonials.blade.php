@if ($hotel->show_reviews ?? true)
<section class="section dark-sec" id="avis">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="eyebrow mb-2">Témoignages</div>
            <h2 class="display-serif text-white" style="font-size:clamp(2rem,4vw,3.2rem);">Ils ont séjourné chez nous</h2>

            @if (($reviewsCount ?? 0) > 0 && ($reviewsAvg ?? null))
                <div class="rev-avg" data-aos="fade-up">
                    <span class="rev-avg-num">{{ number_format($reviewsAvg, 1) }}</span>
                    <span class="rev-avg-stars">
                        @for ($s = 1; $s <= 5; $s++)
                            <i class="fa{{ $s <= round($reviewsAvg) ? 's' : 'r' }} fa-star"></i>
                        @endfor
                    </span>
                    <span class="rev-avg-count">{{ $reviewsCount }} avis</span>
                </div>
            @endif

            <div class="hero-divider" style="margin-top:1rem;"></div>
        </div>

        {{-- Messages de retour --}}
        @if (session('review_success'))
            <div class="rev-flash rev-flash-ok" data-aos="fade-up"><i class="fas fa-check-circle me-2"></i>{{ session('review_success') }}</div>
        @endif
        @if (session('review_error'))
            <div class="rev-flash rev-flash-err" data-aos="fade-up"><i class="fas fa-exclamation-circle me-2"></i>{{ session('review_error') }}</div>
        @endif

        {{-- Avis approuvés --}}
        @if (($reviews ?? collect())->isNotEmpty())
            <div class="row g-4">
                @foreach ($reviews as $i => $review)
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 120 }}">
                        <div class="review h-100 d-flex flex-column">
                            <div class="mb-3" style="color:#f5c451;">
                                @for ($s = 0; $s < (int) $review->rating; $s++)<i class="fas fa-star"></i>@endfor
                                @for ($s = (int) $review->rating; $s < 5; $s++)<i class="far fa-star" style="opacity:.4;"></i>@endfor
                            </div>
                            <p class="serif" style="font-size:1.18rem;line-height:1.7;opacity:.95;">“{{ $review->comment }}”</p>

                            @if ($review->reply)
                                <div class="rev-reply">
                                    <div class="rev-reply-label"><i class="fas fa-reply me-1"></i>Réponse de l'établissement</div>
                                    {{ $review->reply }}
                                </div>
                            @endif

                            <div class="d-flex align-items-center gap-2 mt-auto pt-3">
                                <div class="rev-ava">{{ $review->initial() }}</div>
                                <div>
                                    <div class="fw-semibold">{{ $review->author_name }}</div>
                                    @if ($review->author_city)<div class="small" style="opacity:.6;">{{ $review->author_city }}</div>@endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center serif" style="opacity:.7;font-size:1.15rem;" data-aos="fade-up">
                Aucun avis pour le moment — soyez le premier à partager votre expérience.
            </p>
        @endif

        {{-- Formulaire de dépôt d'avis --}}
        <div class="rev-form-wrap" data-aos="fade-up">
            <h3 class="display-serif text-white text-center mb-4" style="font-size:clamp(1.5rem,3vw,2.1rem);">Laissez votre avis</h3>
            <form method="POST" action="{{ route('public.hotel.review.store', $hotel->slug) }}" class="rev-form" id="reviewForm">
                @csrf
                <div class="rev-stars-input" role="radiogroup" aria-label="Note">
                    @for ($s = 5; $s >= 1; $s--)
                        <input type="radio" id="star{{ $s }}" name="rating" value="{{ $s }}" {{ (int) old('rating') === $s ? 'checked' : '' }} required>
                        <label for="star{{ $s }}" title="{{ $s }} étoile{{ $s > 1 ? 's' : '' }}"><i class="fas fa-star"></i></label>
                    @endfor
                </div>
                @error('rating')<div class="rev-err text-center">{{ $message }}</div>@enderror

                <div class="row g-3 mt-1">
                    <div class="col-sm-6">
                        <input type="text" name="author_name" class="rev-field" placeholder="Votre nom" maxlength="120" value="{{ old('author_name') }}" required>
                        @error('author_name')<div class="rev-err">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="author_city" class="rev-field" placeholder="Votre ville (optionnel)" maxlength="120" value="{{ old('author_city') }}">
                    </div>
                </div>

                <textarea name="comment" class="rev-field mt-3" rows="4" placeholder="Partagez votre expérience…" maxlength="1000" required>{{ old('comment') }}</textarea>
                @error('comment')<div class="rev-err">{{ $message }}</div>@enderror

                <div class="text-center mt-4">
                    <button type="submit" class="btn-c"><i class="fas fa-paper-plane me-2"></i>Publier mon avis</button>
                </div>
            </form>
        </div>
    </div>
</section>

<style>
    .rev-avg { display:inline-flex; align-items:center; gap:.7rem; margin-top:1.2rem; padding:.5rem 1.2rem;
        background:var(--card); border:1px solid var(--line); border-radius:100px; backdrop-filter:blur(8px); }
    .rev-avg-num { font-size:1.6rem; font-weight:800; color:#fff; }
    .rev-avg-stars { color:#f5c451; letter-spacing:1px; }
    .rev-avg-count { font-size:.85rem; opacity:.6; color:#fff; }

    .rev-reply { margin-top:1rem; padding:.85rem 1rem; border-left:3px solid var(--c);
        background:color-mix(in srgb, var(--c) 8%, transparent); border-radius:0 12px 12px 0; font-size:.95rem; opacity:.9; }
    .rev-reply-label { font-size:.72rem; text-transform:uppercase; letter-spacing:.12em; color:var(--c); margin-bottom:.25rem; font-weight:600; }

    .rev-flash { max-width:640px; margin:0 auto 2rem; padding:.9rem 1.2rem; border-radius:14px; text-align:center; font-weight:500; }
    .rev-flash-ok { background:rgba(34,197,94,.15); border:1px solid rgba(34,197,94,.35); color:#86efac; }
    .rev-flash-err { background:rgba(239,68,68,.15); border:1px solid rgba(239,68,68,.35); color:#fca5a5; }

    .rev-form-wrap { max-width:680px; margin:4rem auto 0; padding:2.5rem; background:var(--card);
        border:1px solid var(--line); border-radius:24px; backdrop-filter:blur(10px); }
    .rev-field { width:100%; padding:.85rem 1.1rem; background:rgba(255,255,255,.04); border:1px solid var(--line);
        border-radius:12px; color:#fff; font-family:var(--sans); transition:.25s; }
    .rev-field::placeholder { color:rgba(255,255,255,.4); }
    .rev-field:focus { outline:none; border-color:var(--c); background:rgba(255,255,255,.06); box-shadow:0 0 0 3px color-mix(in srgb, var(--c) 20%, transparent); }
    .rev-err { color:#fca5a5; font-size:.85rem; margin-top:.35rem; }

    /* Étoiles cliquables (radio inversé pour le survol/sélection en CSS pur) */
    .rev-stars-input { display:inline-flex; flex-direction:row-reverse; justify-content:center; gap:.35rem; font-size:2rem; }
    .rev-stars-input input { display:none; }
    .rev-stars-input label { color:rgba(255,255,255,.25); cursor:pointer; transition:.15s; }
    .rev-stars-input label:hover,
    .rev-stars-input label:hover ~ label,
    .rev-stars-input input:checked ~ label { color:#f5c451; }
</style>
@endif
