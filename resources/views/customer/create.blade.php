@extends('template.master')
@section('title', 'Ajouter un Client')
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    /* ── 4 COULEURS (vert, rouge, gris, blanc) ── */
    --green-50:  var(--g50);
    --green-100: var(--g100);
    --green-500: var(--g500);
    --green-600: var(--g600);
    --green-700: var(--g700);

    --red-50:    #fee2e2;
    --red-100:   #fecaca;
    --red-500:   #b91c1c;
    --red-600:   #991b1b;

    --gray-50:   #f8f9f8;
    --gray-100:  #eff0ef;
    --gray-200:  #dde0dd;
    --gray-300:  #c2c7c2;
    --gray-400:  #9ba09b;
    --gray-500:  #737873;
    --gray-600:  #545954;
    --gray-700:  #3a3e3a;
    --gray-800:  #252825;
    --gray-900:  #131513;

    --white:     #ffffff;
    --surface:   #f7f9f7;

    --shadow-xs: 0 1px 2px rgba(0,0,0,.04);
    --shadow-sm: 0 1px 6px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow-md: 0 4px 16px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.04);

    --r:   8px;
    --rl:  14px;
    --rxl: 20px;
    --transition: all .2s ease;
    --font: 'DM Sans', system-ui, sans-serif;
    --mono: 'DM Mono', monospace;
}

* { box-sizing: border-box; margin: 0; padding: 0; }

.create-page {
    background: var(--surface);
    min-height: 100vh;
    padding: 24px 32px;
    font-family: var(--font);
    color: var(--gray-800);
}

/* ── Animations ── */
@keyframes fadeSlide {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
.anim-1 { animation: fadeSlide .4s ease both; }
.anim-2 { animation: fadeSlide .4s .08s ease both; }

/* ══════════════════════════════════════════════
   BREADCRUMB
══════════════════════════════════════════════ */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .8rem;
    color: var(--gray-400);
    margin-bottom: 20px;
}
.breadcrumb a {
    color: var(--gray-400);
    text-decoration: none;
    transition: var(--transition);
}
.breadcrumb a:hover {
    color: var(--green-600);
}
.breadcrumb .sep {
    color: var(--gray-300);
}
.breadcrumb .current {
    color: var(--gray-600);
    font-weight: 500;
}

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.page-header {
    margin-bottom: 24px;
}
.header-title {
    display: flex;
    align-items: center;
    gap: 12px;
}
.header-icon {
    width: 48px;
    height: 48px;
    background: var(--green-600);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 10px rgb(from var(--g500) r g b / .3);
}
.header-title h1 {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0;
}
.header-title em {
    font-style: normal;
    color: var(--green-600);
}
.header-subtitle {
    color: var(--gray-500);
    font-size: .8rem;
    margin: 6px 0 0 60px;
}

/* ══════════════════════════════════════════════
   BUTTONS
══════════════════════════════════════════════ */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 20px;
    border-radius: var(--r);
    font-size: .8rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}
.btn-green {
    background: var(--green-600);
    color: white;
}
.btn-green:hover {
    background: var(--green-700);
    transform: translateY(-1px);
    color: white;
}
.btn-gray {
    background: var(--white);
    color: var(--gray-600);
    border: 1.5px solid var(--gray-200);
}
.btn-gray:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}

/* ══════════════════════════════════════════════
   CARD
══════════════════════════════════════════════ */
.card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.card-header {
    background: var(--gray-50);
    border-bottom: 1.5px solid var(--gray-200);
    padding: 18px 24px;
}
.card-header h2 {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.card-header h2 i {
    color: var(--green-600);
}
.card-body {
    padding: 24px;
}

/* ══════════════════════════════════════════════
   FORM
══════════════════════════════════════════════ */
.form-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 600;
    font-size: .8rem;
    color: var(--gray-700);
    margin-bottom: 8px;
}
.form-label i {
    color: var(--green-600);
    width: 18px;
}
.form-control, .form-select {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .85rem;
    color: var(--gray-700);
    background: var(--white);
    transition: var(--transition);
}
.form-control:focus, .form-select:focus {
    outline: none;
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgb(from var(--g500) r g b / .1);
}
.form-control.is-invalid, .form-select.is-invalid {
    border-color: var(--red-500);
}
.invalid-feedback {
    color: var(--red-500);
    font-size: .7rem;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}
.invalid-feedback i {
    font-size: .8rem;
}

/* ── File input ── */
.form-control[type="file"] {
    padding: 8px;
    background: var(--gray-50);
    cursor: pointer;
}
.form-control[type="file"]::-webkit-file-upload-button {
    background: var(--green-600);
    border: none;
    color: white;
    padding: 6px 12px;
    border-radius: var(--r);
    margin-right: 12px;
    cursor: pointer;
    transition: var(--transition);
}
.form-control[type="file"]::-webkit-file-upload-button:hover {
    background: var(--green-700);
}

/* ══════════════════════════════════════════════
   FORM ACTIONS
══════════════════════════════════════════════ */
.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1.5px solid var(--gray-200);
}

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media (max-width: 768px) {
    .create-page { padding: 16px; }
    .card-body { padding: 18px; }
}
</style>

<div class="create-page">

    {{-- Breadcrumb --}}
    <div class="breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> {{ __('customer.dashboard') }}</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('customer.index') }}">{{ __('customer.clients') }}</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">{{ __('customer.breadcrumb_new_customer') }}</span>
    </div>

    {{-- En-tête --}}
    <div class="page-header anim-2">
        <div class="header-title">
            <span class="header-icon"><i class="fas fa-user-plus"></i></span>
            <h1>{!! __('customer.header_add_customer') !!}</h1>
        </div>
        <p class="header-subtitle">{{ __('customer.header_create_account') }}</p>
    </div>

    {{-- Formulaire --}}
    <div class="row justify-content-md-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-user-plus"></i> {{ __('customer.breadcrumb_new_customer') }}</h2>
                </div>
                <div class="card-body">
                    <form class="row g-4" method="POST" action="{{ route('customer.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Nom --}}
                        <div class="col-md-12">
                            <label for="name" class="form-label">
                                <i class="fas fa-user"></i> {{ __('customer.full_name') }}
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name"
                                   name="name" 
                                   value="{{ old('name') }}"
                                   placeholder="{{ __('customer.name_placeholder') }}">
                            @error('name')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-12">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email"
                                   name="email" 
                                   value="{{ old('email') }}"
                                   placeholder="exemple@email.com">
                            @error('email')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Date de naissance --}}
                        <div class="col-md-12">
                            <label for="birthdate" class="form-label">
                                <i class="fas fa-cake-candles"></i> {{ __('customer.date_of_birth') }}
                            </label>
                            <input type="date"
                                   class="form-control @error('birthdate') is-invalid @enderror"
                                   id="birthdate"
                                   name="birthdate"
                                   min="1900-01-01" max="{{ date('Y-m-d') }}"
                                   value="{{ old('birthdate') }}">
                            @error('birthdate')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Genre --}}
                        <div class="col-md-12">
                            <label for="gender" class="form-label">
                                <i class="fas fa-venus-mars"></i> {{ __('customer.gender') }}
                            </label>
                            <select class="form-select @error('gender') is-invalid @enderror" 
                                    id="gender" 
                                    name="gender">
                                <option value="" selected disabled>{{ __('customer.gender_select') }}</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>{{ __('customer.gender_male') }}</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>{{ __('customer.gender_female') }}</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Profession --}}
                        <div class="col-md-12">
                            <label for="job" class="form-label">
                                <i class="fas fa-briefcase"></i> {{ __('customer.profession') }}
                            </label>
                            <input type="text" 
                                   class="form-control @error('job') is-invalid @enderror" 
                                   id="job" 
                                   name="job"
                                   value="{{ old('job') }}"
                                   placeholder="{{ __('customer.job_placeholder') }}">
                            @error('job')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Adresse --}}
                        <div class="col-md-12">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt"></i> {{ __('customer.address') }}
                            </label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address"
                                      rows="3"
                                       placeholder="{{ __('customer.address_placeholder') }}">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Avatar --}}
                        <div class="col-md-12">
                            <label for="avatar" class="form-label">
                                <i class="fas fa-camera"></i> {{ __('customer.profile_photo') }}
                            </label>
                            <input class="form-control @error('avatar') is-invalid @enderror" 
                                   type="file" 
                                   name="avatar" 
                                   id="avatar"
                                   accept="image/*">
                            <small class="text-muted d-block mt-1">
                                <i class="fas fa-info-circle"></i> {{ __('customer.avatar_hint') }}
                            </small>
                            @error('avatar')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Boutons --}}
                        <div class="col-12">
                            <div class="form-actions">
                                <a href="{{ route('customer.index') }}" class="btn btn-gray">
                                    <i class="fas fa-times"></i> {{ __('customer.cancel') }}
                                </a>
                                <button type="submit" class="btn btn-green">
                                    <i class="fas fa-save"></i> {{ __('customer.save') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Aperçu de l'avatar (optionnel) --}}
    <div class="row justify-content-md-center mt-4">
        <div class="col-lg-8">
            <div class="card" style="margin-top: -10px;">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:60px; height:60px; background:var(--gray-100); border-radius:50%; display:flex; align-items:center; justify-content:center; color:var(--gray-400);">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">{{ __('customer.tip') }}</small>
                            <span style="color:var(--gray-600); font-size:.85rem;">
                                {{ __('customer.tip_text') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
// Aperçu de l'avatar avant upload (optionnel)
document.getElementById('avatar')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Vous pouvez ajouter un aperçu ici si vous le souhaitez
        console.log('Fichier sélectionné:', file.name);
    }
});
</script>

@endsection