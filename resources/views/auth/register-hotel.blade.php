<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Essai gratuit · {{ config('app.name', 'checkinHub') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
        body { background: linear-gradient(135deg, #eef2ff 0%, #f8fafc 100%); min-height: 100vh; }
        .brand { font-weight: 800; color: #4f46e5; }
        .card-signup { border: none; border-radius: 18px; box-shadow: 0 30px 60px -25px rgba(79,70,229,.35); }
        .step-badge { background: #eef2ff; color: #4f46e5; border-radius: 999px; padding: .35rem .9rem; font-weight: 600; font-size: .85rem; }
        .btn-brand { background: #4f46e5; border-color: #4f46e5; color: #fff; }
        .btn-brand:hover { background: #4338ca; color: #fff; }
        .plan-card { cursor: pointer; border: 2px solid #e8eaf0; border-radius: 14px; transition: .2s; height: 100%; }
        .plan-card:hover { border-color: #c7d2fe; transform: translateY(-3px); }
        .plan-card input { display: none; }
        .plan-card.selected { border-color: #4f46e5; box-shadow: 0 10px 30px -12px rgba(79,70,229,.5); }
        .plan-price { font-size: 1.6rem; font-weight: 800; color: #4f46e5; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="text-center mb-4">
        <a href="{{ route('landing') }}" class="text-decoration-none brand fs-4">
            <i class="fas fa-hotel me-1"></i> {{ config('app.name', 'checkinHub') }}
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
            <div class="card card-signup">
                <div class="card-body p-4 p-md-5">
                    <span class="step-badge"><i class="fas fa-gift me-1"></i> Essai gratuit · {{ config('plans.trial_days', 14) }} jours · sans carte bancaire</span>
                    <h3 class="fw-bold mt-3 mb-1">Créez votre établissement</h3>
                    <p class="text-secondary mb-4">Choisissez votre formule et renseignez vos infos. Vos identifiants vous seront envoyés par email.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('hotel.register.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <h6 class="fw-semibold text-uppercase text-secondary small mb-0">1. Votre pays & formule</h6>
                            <div class="d-flex align-items-center gap-2">
                                <label class="small text-secondary mb-0"><i class="fas fa-earth-africa me-1"></i>Pays</label>
                                <select name="country" id="country-select" class="form-select form-select-sm" style="width:auto;">
                                    @foreach ($countries as $code => $c)
                                        <option value="{{ $code }}" {{ old('country', $defaultCountry) === $code ? 'selected' : '' }}>{{ $c['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            @foreach ($plans as $key => $tier)
                                <div class="col-md-4">
                                    <label class="plan-card d-block p-3 {{ $selectedPlan === $key ? 'selected' : '' }}" data-plan="{{ $key }}" data-base="{{ $tier['price'] }}">
                                        <input type="radio" name="plan" value="{{ $key }}" {{ $selectedPlan === $key ? 'checked' : '' }}>
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-bold">{{ $tier['name'] }}</span>
                                            @if (! empty($tier['popular']))<span class="badge bg-primary">Populaire</span>@endif
                                        </div>
                                        <div class="plan-price"><span class="price-amount">{{ number_format($tier['price'], 0, ',', ' ') }}</span> <small class="text-secondary fw-normal price-cur" style="font-size:.8rem">XOF/mois</small></div>
                                        <div class="small text-secondary">{{ $tier['tagline'] }}</div>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <h6 class="fw-semibold text-uppercase text-secondary small mb-3">2. Votre établissement</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label">Nom de l'établissement *</label>
                                <input type="text" name="company_name" class="form-control form-control-lg @error('company_name') is-invalid @enderror" value="{{ old('company_name') }}" placeholder="Ex : Cactus Hotel" maxlength="255" required>
                                @error('company_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Téléphone</label>
                                <input type="tel" name="contact_phone" class="form-control form-control-lg @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone') }}" placeholder="Ex : +229 01 02 03 04" pattern="[0-9+\s().\-]{6,20}" maxlength="20" inputmode="tel">
                                @error('contact_phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Logo (optionnel)</label>
                                <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,.svg,image/*">
                                <small class="text-muted">JPG, PNG, WEBP ou SVG · 4 Mo max.</small>
                                @error('logo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <h6 class="fw-semibold text-uppercase text-secondary small mb-3">3. Vous (administrateur)</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Nom complet *</label>
                                <input type="text" name="admin_name" class="form-control @error('admin_name') is-invalid @enderror" value="{{ old('admin_name') }}" maxlength="255" required>
                                @error('admin_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email *</label>
                                <input type="email" name="admin_email" class="form-control @error('admin_email') is-invalid @enderror" value="{{ old('admin_email') }}" required>
                                @error('admin_email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                <small class="text-muted">Vos identifiants seront envoyés à cette adresse.</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-brand btn-lg w-100">
                            <i class="fas fa-rocket me-2"></i> Démarrer mon essai gratuit
                        </button>
                    </form>

                    <hr class="my-4">
                    <p class="text-center text-secondary mb-0">
                        Vous avez déjà un compte ? <a href="{{ route('login.index') }}" class="fw-semibold">Se connecter</a>
                    </p>
                    {{-- Issue #163 : retour explicite vers le site vitrine --}}
                    <p class="text-center mb-0 mt-2">
                        <a href="{{ route('landing') }}" class="text-decoration-none"><i class="fas fa-arrow-left me-1"></i>Retour au site</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.plan-card').forEach(card => {
        card.addEventListener('click', () => {
            document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            card.querySelector('input').checked = true;
        });
    });

    // Prix ajustés selon le pays (coût de la vie)
    const countries = @json($countries);
    const countrySel = document.getElementById('country-select');
    const fmt = n => n.toLocaleString('fr-FR');
    function updatePrices() {
        const c = countries[countrySel.value]; if (!c) return;
        document.querySelectorAll('.plan-card').forEach(card => {
            const base = +card.dataset.base;
            const price = Math.round(base * c.coef / c.round) * c.round;
            card.querySelector('.price-amount').textContent = fmt(price);
            card.querySelector('.price-cur').textContent = c.currency + '/mois';
        });
    }
    countrySel.addEventListener('change', updatePrices);
    updatePrices();
</script>
</body>
</html>
