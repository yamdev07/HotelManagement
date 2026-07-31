<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenue · personnalisez votre site</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
        body { background: #f1f5f9; min-height: 100vh; }
        .wizard-card { border: none; border-radius: 18px; box-shadow: 0 30px 60px -25px rgba(15,23,42,.3); }
        .preview { border-radius: 14px; overflow: hidden; border: 1px solid #e2e8f0; position: sticky; top: 24px; }
        .preview-hero { height: 150px; display: flex; align-items: center; justify-content: center; color: #fff; transition: background .2s; }
        .step-badge { background: #eef2ff; color: #4f46e5; border-radius: 999px; padding: .35rem .9rem; font-weight: 600; font-size: .85rem; }
        .btn-go { background: var(--c, #4f46e5); border: none; color: #fff; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-4">
                <span class="step-badge"><i class="fas fa-wand-magic-sparkles me-1"></i> Dernière étape</span>
                <h2 class="fw-bold mt-3">Personnalisez votre site</h2>
                <p class="text-secondary">Choisissez vos couleurs, votre nom et votre logo. Tout s'applique immédiatement.</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success d-flex align-items-start gap-2">
                    <i class="fas fa-circle-check mt-1"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if (session('credentials_email'))
                <div class="alert alert-warning d-flex align-items-start gap-2" style="border-radius:14px;">
                    <i class="fas fa-envelope-circle-check mt-1 fs-5"></i>
                    <div>
                        <strong>Vos identifiants de connexion ont été envoyés à {{ session('credentials_email') }}.</strong><br>
                        <span class="text-muted">⚠️ Pensez à vérifier votre dossier <strong>spam / courrier indésirable</strong> : le message peut s'y trouver.
                        Marquez-le comme « non spam » pour recevoir nos prochains emails dans votre boîte principale.</span>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif

            {{-- Issues #154 #155 : rassurer sur le changement d'offre --}}
            <div class="alert alert-light border d-flex align-items-start gap-2">
                <i class="fas fa-circle-info text-primary mt-1"></i>
                <div class="small mb-0">
                    Vous pourrez <strong>changer d'offre à tout moment</strong> (passer de Starter à Pro ou Business)
                    @if (\Illuminate\Support\Facades\Route::has('billing.show'))
                        depuis <strong>Mon abonnement</strong>, une fois connecté.
                    @else
                        depuis votre espace, une fois connecté.
                    @endif
                </div>
            </div>

            <div class="card wizard-card">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('onboarding.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="col-lg-7">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nom affiché sur le site *</label>
                                    <input type="text" name="name" id="f-name" class="form-control form-control-lg"
                                           value="{{ old('name', $hotel->name) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Slogan</label>
                                    <input type="text" name="tagline" id="f-tagline" class="form-control"
                                           value="{{ old('tagline', $hotel->tagline) }}" placeholder="Ex : Votre confort, notre priorité">
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col-6">
                                        <label class="form-label fw-semibold">Couleur principale</label>
                                        <input type="color" name="primary_color" id="f-primary" class="form-control form-control-color w-100"
                                               value="{{ old('primary_color', $hotel->primaryColor()) }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-semibold">Couleur secondaire</label>
                                        <input type="color" name="secondary_color" id="f-secondary" class="form-control form-control-color w-100"
                                               value="{{ old('secondary_color', $hotel->secondaryColor()) }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Logo</label>
                                    <input type="file" name="logo" id="f-logo" class="form-control" accept="image/*">
                                </div>
                            </div>

                            <div class="col-lg-5">
                                <label class="form-label fw-semibold">Aperçu</label>
                                <div class="preview">
                                    <div class="preview-hero" id="pv-hero" style="background: linear-gradient(135deg, {{ $hotel->primaryColor() }}, {{ $hotel->secondaryColor() }});">
                                        <div class="text-center">
                                            <img id="pv-logo" alt="" @if($hotel->logoUrl()) src="{{ $hotel->logoUrl() }}" @else style="display:none;" @endif data-base="max-height:40px;background:var(--white, #fff);border-radius:6px;padding:3px;"
                                                 style="max-height:40px;background:var(--white, #fff);border-radius:6px;padding:3px;{{ $hotel->logoUrl() ? '' : 'display:none;' }}">
                                            <h5 class="fw-bold mb-0 mt-2" id="pv-name">{{ $hotel->name }}</h5>
                                            <small id="pv-tagline">{{ $hotel->tagline }}</small>
                                        </div>
                                    </div>
                                    <div class="p-3 bg-white">
                                        <button type="button" class="btn btn-sm btn-go" id="pv-btn" style="--c: {{ $hotel->primaryColor() }}">Réserver</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-grid">
                            <button type="submit" class="btn btn-lg text-white" style="background: var(--brandbtn, #4f46e5)" id="submit-btn">
                                <i class="fas fa-check me-2"></i> Valider et accéder à mon espace
                            </button>
                        </div>
                        {{-- Issue #156 : pouvoir quitter / changer de compte sans être piégé ici --}}
                        <p class="text-center text-secondary small mt-3 mb-0">
                            <a href="{{ route('landing') }}" class="text-decoration-none me-3"><i class="fas fa-arrow-left me-1"></i>Retour au site</a>
                            <a href="#" class="text-decoration-none text-danger"
                               onclick="event.preventDefault(); document.getElementById('ob-logout').submit();">
                                <i class="fas fa-arrow-right-from-bracket me-1"></i>Me déconnecter (finir plus tard)
                            </a>
                        </p>
                    </form>
                    <form id="ob-logout" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const name = document.getElementById('f-name');
    const tagline = document.getElementById('f-tagline');
    const primary = document.getElementById('f-primary');
    const secondary = document.getElementById('f-secondary');
    const logo = document.getElementById('f-logo');

    const sync = () => {
        document.getElementById('pv-name').textContent = name.value || 'Votre hôtel';
        document.getElementById('pv-tagline').textContent = tagline.value || '';
        document.getElementById('pv-hero').style.background = `linear-gradient(135deg, ${primary.value}, ${secondary.value})`;
        document.getElementById('pv-btn').style.setProperty('--c', primary.value);
        document.getElementById('submit-btn').style.setProperty('--brandbtn', primary.value);
    };
    [name, tagline, primary, secondary].forEach(el => el.addEventListener('input', sync));
    logo.addEventListener('change', e => {
        const f = e.target.files[0];
        if (f) { const img = document.getElementById('pv-logo'); img.src = URL.createObjectURL(f); img.style.display = 'inline-block'; }
    });
</script>
</body>
</html>
