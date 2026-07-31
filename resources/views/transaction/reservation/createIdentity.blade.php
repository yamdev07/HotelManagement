@extends('template.master')
@section('title', __('reservation.create_client'))
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    /* ── Palette : 3 couleurs uniquement ── */
    /* VERT */
    --g50:  #f0faf0;
    --g100: #d4edda;
    --g200: #a8d5b5;
    --g300: #72bb82;
    --g400: #4a9e5c;
    --g500: #2e8540;
    --g600: #1e6b2e;
    --g700: #155221;
    --g800: #0d3a16;
    --g900: #072210;
    /* BLANC / SURFACE */
    --white:    #ffffff;
    --surface:  #f7f9f7;
    --surface2: #eef3ee;
    /* GRIS */
    --s50:  #f8f9f8;
    --s100: #eff0ef;
    --s200: #dde0dd;
    --s300: #c2c7c2;
    --s400: #9ba09b;
    --s500: #737873;
    --s600: #545954;
    --s700: #3a3e3a;
    --s800: #252825;
    --s900: #131513;

    --shadow-xs: 0 1px 2px rgba(0,0,0,.04);
    --shadow-sm: 0 1px 6px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow-md: 0 4px 16px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.04);
    --shadow-lg: 0 12px 40px rgba(0,0,0,.10), 0 4px 12px rgba(0,0,0,.05);

    --r:   8px;
    --rl:  14px;
    --rxl: 20px;
    --transition: all .2s cubic-bezier(.4,0,.2,1);
    --font: 'DM Sans', system-ui, sans-serif;
    --mono: 'DM Mono', monospace;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.create-identity-page {
    padding: 28px 32px 64px;
    background: var(--surface);
    min-height: 100vh;
    font-family: var(--font);
    color: var(--s800);
}

/* ── Animations ── */
@keyframes fadeSlide {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes scaleIn {
    from { opacity: 0; transform: scale(.96); }
    to   { opacity: 1; transform: scale(1); }
}
.anim-1 { animation: fadeSlide .4s ease both; }
.anim-2 { animation: fadeSlide .4s .08s ease both; }
.anim-3 { animation: fadeSlide .4s .16s ease both; }
.anim-4 { animation: fadeSlide .4s .24s ease both; }
.anim-5 { animation: fadeSlide .4s .32s ease both; }
.anim-6 { animation: fadeSlide .4s .40s ease both; }

/* ══════════════════════════════════════════════
   BREADCRUMB
══════════════════════════════════════════════ */
.identity-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .8rem; color: var(--s400);
    margin-bottom: 20px;
}
.identity-breadcrumb a {
    color: var(--s400); text-decoration: none;
    transition: var(--transition);
}
.identity-breadcrumb a:hover { color: var(--g600); }
.identity-breadcrumb .sep { color: var(--s300); }
.identity-breadcrumb .current { color: var(--s600); font-weight: 500; }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.identity-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.identity-brand { display: flex; align-items: center; gap: 14px; }
.identity-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgb(from var(--g500) r g b / .35);
}
.identity-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.identity-header-title em { font-style: normal; color: var(--g600); }
.identity-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.identity-header-sub i { color: var(--g500); }
.identity-header-actions { display: flex; align-items: center; gap: 10px; }

/* ══════════════════════════════════════════════
   PROGRESS BAR
══════════════════════════════════════════════ */
.progress-container {
    margin-bottom: 30px;
}
.progress-steps {
    display: flex; justify-content: space-between; position: relative;
    margin-bottom: 20px;
}
.progress-steps::before {
    content: ''; position: absolute; top: 20px; left: 0; right: 0;
    height: 2px; background: var(--s200); z-index: 1;
}
.progress-step {
    position: relative; z-index: 2; text-align: center; flex: 1;
}
.step-circle {
    width: 40px; height: 40px; border-radius: 50%;
    background: var(--white); border: 2px solid var(--s200);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 8px; font-weight: 600; color: var(--s600);
    transition: var(--transition);
}
.step-active .step-circle {
    background: var(--g600); border-color: var(--g600);
    color: white;
}
.step-completed .step-circle {
    background: var(--g500); border-color: var(--g500);
    color: white;
}
.step-label {
    font-size: .75rem; color: var(--s400); font-weight: 500;
}
.step-active .step-label {
    color: var(--g600); font-weight: 600;
}

/* ══════════════════════════════════════════════
   CARTE PRINCIPALE
══════════════════════════════════════════════ */
.identity-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 20px; box-shadow: var(--shadow-sm);
}
.identity-card-header {
    padding: 18px 24px;
    border-bottom: 1.5px solid var(--s100);
    background: linear-gradient(135deg, var(--g700), var(--g500));
    color: white;
}
.identity-card-title {
    display: flex; align-items: center; gap: 10px;
    font-size: 1rem; font-weight: 600; color: white; margin: 0;
}
.identity-card-title i { color: white; }
.identity-card-body { padding: 28px; }
.identity-card-footer {
    padding: 16px 24px; border-top: 1.5px solid var(--s100);
    background: var(--surface);
}

/* ══════════════════════════════════════════════
   INFO BOX
══════════════════════════════════════════════ */
.info-box {
    background: var(--g50); border-left: 4px solid var(--g600);
    padding: 14px 18px; border-radius: var(--rl);
    margin-bottom: 20px; display: flex; align-items: center; gap: 12px;
}
.info-icon {
    width: 32px; height: 32px; border-radius: 8px;
    background: var(--g100); color: var(--g600);
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; flex-shrink: 0;
}
.info-text {
    font-size: .8rem; color: var(--s700);
}
.info-text strong { color: var(--s800); }

/* ══════════════════════════════════════════════
   EXISTING CUSTOMER INFO
══════════════════════════════════════════════ */
.existing-customer-info {
    background: var(--g50); border-left: 4px solid var(--g600);
    border-radius: var(--rl); padding: 16px;
    margin-bottom: 24px; display: none;
}
.existing-customer-content {
    display: flex; align-items: center; gap: 16px;
}
.existing-icon {
    width: 48px; height: 48px; border-radius: 12px;
    background: var(--g100); color: var(--g600);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}
.existing-details {
    flex: 1;
}
.existing-title {
    font-size: .9rem; font-weight: 600; color: var(--s800);
    margin-bottom: 4px;
}
.existing-info {
    font-size: .8rem; color: var(--s600); margin-bottom: 4px;
}
.existing-meta {
    font-size: .7rem; color: var(--s400);
}
.reservation-badge {
    display: inline-block; background: var(--g600); color: white;
    padding: 2px 8px; border-radius: 20px; font-size: .7rem;
    font-weight: 600; margin-left: 6px;
}

/* ══════════════════════════════════════════════
   FORMULAIRES
══════════════════════════════════════════════ */
.form-grid {
    display: grid; grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}
@media(max-width:768px){ .form-grid{ grid-template-columns:1fr; } }

.form-group {
    display: flex; flex-direction: column;
}
.form-label {
    font-size: .75rem; font-weight: 600; color: var(--s600);
    margin-bottom: 6px; display: flex; align-items: center; gap: 6px;
    text-transform: uppercase; letter-spacing: .5px;
}
.form-label i { font-size: .7rem; color: var(--g500); }
.form-label .required {
    color: #b91c1c; font-size: .7rem; margin-left: 4px;
}
.form-control, .form-select {
    padding: 10px 14px; border-radius: var(--r);
    border: 1.5px solid var(--s200); font-size: .875rem;
    font-family: var(--font); transition: var(--transition);
    background: var(--white); width: 100%;
}
.form-control:focus, .form-select:focus {
    outline: none; border-color: var(--g400);
    box-shadow: 0 0 0 3px var(--g100);
}
.form-control.is-invalid, .form-select.is-invalid {
    border-color: #b91c1c; background: #fee2e2;
}
.form-text {
    font-size: .7rem; color: var(--s400); margin-top: 4px;
    display: flex; align-items: center; gap: 4px;
}
.form-text i { color: var(--g500); }

/* ══════════════════════════════════════════════
   FILE INPUT
══════════════════════════════════════════════ */
.input-group {
    display: flex;
}
.input-group .form-control {
    border-radius: var(--r) 0 0 var(--r); flex: 1;
}
.input-group .btn-outline {
    padding: 0 16px; background: var(--white);
    border: 1.5px solid var(--s200); border-left: none;
    border-radius: 0 var(--r) var(--r) 0;
    color: var(--s600); cursor: pointer; transition: var(--transition);
}
.input-group .btn-outline:hover {
    background: var(--g50); color: var(--g600);
    border-color: var(--g200);
}

/* Avatar preview */
.avatar-preview {
    margin-top: 12px; display: none;
}
.preview-image {
    max-width: 150px; max-height: 150px;
    border-radius: var(--rl); border: 3px solid var(--g200);
}

/* ══════════════════════════════════════════════
   MESSAGES D'ERREUR
══════════════════════════════════════════════ */
.error-message {
    display: flex; align-items: center; gap: 4px;
    font-size: .7rem; color: #b91c1c; margin-top: 4px;
}
.error-message i { font-size: .65rem; }

/* ══════════════════════════════════════════════
   ALERTES
══════════════════════════════════════════════ */
.alert-db {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 18px; border-radius: var(--rl);
    margin-bottom: 20px; border: 1.5px solid transparent;
    font-size: .875rem; background: var(--white);
    box-shadow: var(--shadow-sm);
}
.alert-db-info {
    background: var(--g50); border-color: var(--g200);
    color: var(--g700);
}
.alert-db-danger {
    background: #fee2e2; border-color: #fecaca;
    color: #b91c1c;
}
.alert-db ul {
    margin: 8px 0 0 20px; padding: 0;
}

/* ══════════════════════════════════════════════
   BOUTONS
══════════════════════════════════════════════ */
.btn-db {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 10px 20px; border-radius: var(--r);
    font-size: .8rem; font-weight: 500; border: none;
    cursor: pointer; transition: var(--transition);
    text-decoration: none; white-space: nowrap; line-height: 1;
    font-family: var(--font);
}
.btn-db-primary {
    background: var(--g600); color: white;
    box-shadow: 0 2px 10px rgb(from var(--g500) r g b / .3);
}
.btn-db-primary:hover {
    background: var(--g700); color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgb(from var(--g500) r g b / .35);
    text-decoration: none;
}
.btn-db-ghost {
    background: var(--white); color: var(--s600);
    border: 1.5px solid var(--s200);
}
.btn-db-ghost:hover {
    background: var(--s50); border-color: var(--s300);
    color: var(--s900); text-decoration: none;
}
.btn-db-outline {
    background: transparent; color: var(--s600);
    border: 1.5px solid var(--s200);
}
.btn-db-outline:hover {
    background: var(--g50); color: var(--g700);
    border-color: var(--g300);
}

/* ══════════════════════════════════════════════
   FOOTER INFO
══════════════════════════════════════════════ */
.footer-info {
    display: flex; justify-content: space-between; align-items: center;
    font-size: .7rem; color: var(--s400);
}
.footer-info i { color: var(--g500); }

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media(max-width:768px){
    .create-identity-page{ padding: 20px; }
    .identity-header{ flex-direction: column; align-items: flex-start; }
    .identity-card-body{ padding: 20px; }
    .progress-steps{ flex-wrap: wrap; gap: 10px; }
    .progress-step{ min-width: 120px; }
    .footer-info{ flex-direction: column; gap: 10px; text-align: center; }
}
</style>

<div class="create-identity-page">
    <!-- Breadcrumb -->
    <div class="identity-breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">{{ __('reservation.create_client') }}</span>
    </div>

    <!-- Header -->
    <div class="identity-header anim-2">
        <div class="identity-brand">
            <div class="identity-brand-icon"><i class="fas fa-user-plus"></i></div>
            <div>
                <h1 class="identity-header-title">{{ __('reservation.create_client_title_1') }} <em>{{ __('reservation.create_client_title_2') }}</em></h1>
                <p class="identity-header-sub">
                    <i class="fas fa-users me-1"></i> {{ __('reservation.step_1_4') }}
                </p>
            </div>
        </div>
        <div class="identity-header-actions">
            <a href="{{ route('dashboard.index') }}" class="btn-db btn-db-ghost">
                <i class="fas fa-times me-2"></i> {{ __('reservation.cancel') }}
            </a>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="progress-container anim-3">
        <div class="progress-steps">
            <div class="progress-step step-active">
                <div class="step-circle">1</div>
                <div class="step-label">{{ __('reservation.step_identity') }}</div>
            </div>
            <div class="progress-step">
                <div class="step-circle">2</div>
                <div class="step-label">{{ __('reservation.step_dates') }}</div>
            </div>
            <div class="progress-step">
                <div class="step-circle">3</div>
                <div class="step-label">{{ __('reservation.step_room') }}</div>
            </div>
            <div class="progress-step">
                <div class="step-circle">4</div>
                <div class="step-label">{{ __('reservation.step_confirmation') }}</div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="identity-card anim-4">
        <div class="identity-card-header">
            <h5 class="identity-card-title">
                <i class="fas fa-user-circle"></i>
                {{ __('reservation.customer_info') }}
            </h5>
        </div>

        <div class="identity-card-body">
            <!-- Info Email -->
            <div class="info-box">
                <div class="info-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="info-text">
                    {{ __('reservation.important_email') }}
                </div>
            </div>

            <!-- Existing Customer Info (AJAX) -->
            <div id="existingCustomerInfo" class="existing-customer-info">
                <div class="existing-customer-content">
                    <div class="existing-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="existing-details">
                        <div class="existing-title">{{ __('reservation.existing_customer_found') }}</div>
                        <div class="existing-info" id="customerDetails"></div>
                        <div class="existing-meta">
                            <span id="reservationCount">0</span> {{ __('reservation.existing_reservations') }}
                        </div>
                    </div>
                </div>
            </div>

            @if(session('info'))
                <div class="alert-db alert-db-info">
                    <i class="fas fa-info-circle"></i>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-db alert-db-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <div style="flex:1">
                        <strong>{{ __('reservation.fix_errors') }}</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('transaction.reservation.storeCustomer') }}"
                  enctype="multipart/form-data" id="customerForm">
                @csrf
                {{-- Édition du client déjà saisi (retour en arrière) : évite le doublon · issue #189 --}}
                <input type="hidden" name="customer_id" value="{{ old('customer_id', $customer->id ?? '') }}">

                <div class="form-grid">
                    <!-- Email (full width) -->
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            {{ __('reservation.email_label') }} <span class="required">*</span>
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $customer->email ?? '') }}"
                               placeholder="exemple@domaine.com" required
                               onblur="checkExistingCustomer()">
                        @error('email')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-lightbulb"></i>
                            {{ __('reservation.email_hint') }}
                        </div>
                    </div>

                    <!-- Nom complet -->
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user"></i>
                            {{ __('reservation.full_name') }} <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $customer->name ?? '') }}"
                               placeholder="Jean Dupont" required>
                        @error('name')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Date de naissance -->
                    <div class="form-group">
                        <label for="birthdate" class="form-label">
                            <i class="fas fa-birthday-cake"></i>
                            {{ __('reservation.birthdate') }}
                        </label>
                        <input type="date" class="form-control @error('birthdate') is-invalid @enderror"
                               min="1900-01-01" max="{{ date('Y-m-d') }}"
                               id="birthdate" name="birthdate" value="{{ old('birthdate', optional($customer)->birthdate ? \Illuminate\Support\Carbon::parse($customer->birthdate)->format('Y-m-d') : '') }}">
                        @error('birthdate')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Genre -->
                    <div class="form-group">
                        <label for="gender" class="form-label">
                            <i class="fas fa-venus-mars"></i>
                            {{ __('reservation.gender') }} <span class="required">*</span>
                        </label>
                        <select class="form-select @error('gender') is-invalid @enderror" 
                                id="gender" name="gender" required>
                            <option value="">{{ __('reservation.select') }}</option>
                            @php $g = old('gender', $customer->gender ?? ''); @endphp
                            <option value="Male" {{ $g == 'Male' ? 'selected' : '' }}>{{ __('reservation.male') }}</option>
                            <option value="Female" {{ $g == 'Female' ? 'selected' : '' }}>{{ __('reservation.female') }}</option>
                            <option value="Other" {{ $g == 'Other' ? 'selected' : '' }}>{{ __('reservation.other') }}</option>
                        </select>
                        @error('gender')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- {{ __('reservation.phone') }} -->
                    <div class="form-group">
                        <label for="phone" class="form-label">
                            <i class="fas fa-phone"></i>
                            {{ __('reservation.phone') }} <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone', $customer->phone ?? '') }}"
                               placeholder="+229 XX XX XX XX" required>
                        @error('phone')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Profession -->
                    <div class="form-group">
                        <label for="job" class="form-label">
                            <i class="fas fa-briefcase"></i>
                            {{ __('reservation.profession') }}
                        </label>
                        <input type="text" class="form-control @error('job') is-invalid @enderror" 
                               id="job" name="job" value="{{ old('job', $customer->job ?? '') }}"
                               placeholder="{{ __('reservation.profession_placeholder') }}">
                        @error('job')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Adresse (full width) -->
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="address" class="form-label">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ __('reservation.address') }}
                        </label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3"
                                  placeholder="{{ __('reservation.address_placeholder') }}">{{ old('address', $customer->address ?? '') }}</textarea>
                        @error('address')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Photo de profil (full width) -->
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="avatar" class="form-label">
                            <i class="fas fa-camera"></i>
                            {{ __('reservation.photo') }}
                        </label>
                        <div class="input-group">
                            <input class="form-control @error('avatar') is-invalid @enderror" 
                                   type="file" name="avatar" id="avatar" accept="image/*">
                            <button class="btn-outline" type="button" onclick="clearAvatar()">
                                <i class="fas fa-times"></i> {{ __('reservation.clear_photo') }}
                            </button>
                        </div>
                        <div class="form-text">
                            <i class="fas fa-image"></i>
                            {{ __('reservation.formats_accepted') }}
                        </div>
                        @error('avatar')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror

                        <!-- Avatar preview -->
                        <div id="avatarPreview" class="avatar-preview">
                            <img id="previewImage" src="#" alt="{{ __('reservation.preview') }}" class="preview-image">
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div style="display: flex; justify-content: space-between; margin-top: 28px;">
                    <a href="{{ route('dashboard.index') }}" class="btn-db btn-db-ghost">
                        <i class="fas fa-times me-2"></i> {{ __('reservation.cancel') }}
                    </a>
                    <button type="submit" class="btn-db btn-db-primary">
                        <i class="fas fa-save me-2"></i> <span id="submitText">{{ __('reservation.save_continue') }}</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="identity-card-footer">
            <div class="footer-info">
                <div>
                    <i class="fas fa-user me-1"></i> {{ __('reservation.step_1_identity') }}
                </div>
                <div>
                    <i class="fas fa-arrow-right me-1"></i> {{ __('reservation.next_step') }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer')
<script>
// Fonction pour vérifier si le client existe déjà
function checkExistingCustomer() {
    const emailInput = document.getElementById('email');
    const email = emailInput.value;
    
    if (!email || !validateEmail(email)) {
        hideExistingCustomerInfo();
        return;
    }
    
    // Afficher un indicateur de chargement
    const submitButton = document.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> {{ __("reservation.checking") }}';
    submitButton.disabled = true;
    
    // Effectuer la requête AJAX
    fetch('{{ route("transaction.reservation.searchByEmail") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            // Afficher les informations du client existant
            showExistingCustomerInfo(data);
            
            // Pré-remplir les champs du formulaire
            if (!document.getElementById('name').value) {
                document.getElementById('name').value = data.customer.name;
            }
            
            // Mettre à jour le texte du bouton
            document.getElementById('submitText').innerHTML = 
                '{{ __("reservation.save_update") }} <span class="reservation-badge">' + 
                data.customer.reservation_count + '</span>';
        } else {
            hideExistingCustomerInfo();
            document.getElementById('submitText').innerHTML = '{{ __("reservation.save_continue") }}';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        hideExistingCustomerInfo();
    })
    .finally(() => {
        // Restaurer le bouton
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
}

// Fonction pour afficher les informations du client existant
function showExistingCustomerInfo(data) {
    const infoDiv = document.getElementById('existingCustomerInfo');
    const customerDetails = document.getElementById('customerDetails');
    const reservationCount = document.getElementById('reservationCount');
    
    customerDetails.innerHTML = 
        `<strong>${data.customer.name}</strong> · ${data.customer.email} · ${data.customer.phone}`;
    reservationCount.textContent = data.customer.reservation_count;
    
    infoDiv.style.display = 'block';
}

// Fonction pour masquer les informations du client existant
function hideExistingCustomerInfo() {
    document.getElementById('existingCustomerInfo').style.display = 'none';
    document.getElementById('submitText').innerHTML = '{{ __("reservation.save_continue") }}';
}

// Fonction de validation d'email
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Prévisualisation de l'avatar
document.getElementById('avatar').addEventListener('change', function(e) {
    const preview = document.getElementById('avatarPreview');
    const previewImage = document.getElementById('previewImage');
    
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            preview.style.display = 'block';
        }
        
        reader.readAsDataURL(this.files[0]);
    } else {
        preview.style.display = 'none';
    }
});

// Effacer l'avatar sélectionné
function clearAvatar() {
    document.getElementById('avatar').value = '';
    document.getElementById('avatarPreview').style.display = 'none';
}

// Validation du formulaire avant soumission
document.getElementById('customerForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value;
    const name = document.getElementById('name').value;
    
    if (!validateEmail(email)) {
        e.preventDefault();
        alert('{{ __("reservation.invalid_email") }}');
        document.getElementById('email').focus();
        return false;
    }
    
    if (!name.trim()) {
        e.preventDefault();
        alert('{{ __("reservation.enter_name") }}');
        document.getElementById('name').focus();
        return false;
    }
    
    // Vérifier si on veut confirmer pour un client existant
    const existingCustomerDiv = document.getElementById('existingCustomerInfo');
    if (existingCustomerDiv.style.display === 'block') {
        if (!confirm('{{ __("reservation.confirm_existing") }}')) {
            e.preventDefault();
            return false;
        }
    }
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si un email est déjà présent
    const emailValue = document.getElementById('email').value;
    if (emailValue && validateEmail(emailValue)) {
        setTimeout(() => {
            checkExistingCustomer();
        }, 500);
    }
});
</script>
@endsection
