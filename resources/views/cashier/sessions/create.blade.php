@extends('template.master')

@section('title', __('cashier-sessions.create_title'))

@push('styles')
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

* { box-sizing: border-box; }

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
.anim-3 { animation: fadeSlide .4s .16s ease both; }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.create-header {
    background: var(--white);
    border-bottom: 1.5px solid var(--gray-200);
    padding: 20px 0;
    margin-bottom: 24px;
}
.header-title {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0;
}
.header-title i {
    color: var(--green-600);
    margin-right: 10px;
}
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .8rem;
    color: var(--gray-400);
}
.breadcrumb a {
    color: var(--gray-400);
    text-decoration: none;
    transition: var(--transition);
}
.breadcrumb a:hover {
    color: var(--green-600);
}
.breadcrumb .active {
    color: var(--gray-600);
    font-weight: 500;
}

/* ══════════════════════════════════════════════
   CONTAINER
══════════════════════════════════════════════ */
.create-container {
    max-width: 600px;
    margin: 0 auto;
}

/* ══════════════════════════════════════════════
   ALERTES
══════════════════════════════════════════════ */
.alert {
    border-radius: var(--rl);
    padding: 14px 18px;
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    border: 1.5px solid;
    animation: fadeSlide .3s ease;
}
.alert-green {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.alert-red {
    background: var(--red-50);
    border-color: var(--red-100);
    color: var(--red-500);
}
.alert-yellow {
    background: var(--red-50);
    border-color: var(--red-100);
    color: var(--red-500);
}
.alert-icon {
    font-size: 1.2rem;
    flex-shrink: 0;
}
.alert-content {
    flex: 1;
}
.alert-content ul {
    margin-top: 8px;
    padding-left: 20px;
}
.alert-close {
    background: transparent;
    border: none;
    color: currentColor;
    opacity: .6;
    cursor: pointer;
    font-size: 1rem;
    padding: 0 4px;
}
.alert-close:hover {
    opacity: 1;
}

/* ══════════════════════════════════════════════
   RULES CARD
══════════════════════════════════════════════ */
.rules-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: var(--shadow-xs);
}
.rules-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    font-size: .9rem;
    color: var(--gray-800);
    margin-bottom: 12px;
}
.rules-title i {
    color: var(--green-600);
}
.rules-list {
    padding-left: 28px;
    color: var(--gray-600);
    font-size: .8rem;
    line-height: 1.6;
}
.rules-list li {
    margin-bottom: 6px;
}

/* ══════════════════════════════════════════════
   FORM CARD
══════════════════════════════════════════════ */
.form-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    padding: 28px;
    box-shadow: var(--shadow-sm);
}
.form-icon {
    width: 80px;
    height: 80px;
    background: var(--green-50);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    font-size: 2rem;
    color: var(--green-600);
}
.form-title {
    text-align: center;
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 6px;
}
.form-subtitle {
    text-align: center;
    color: var(--gray-500);
    font-size: .8rem;
    margin-bottom: 24px;
}

/* ══════════════════════════════════════════════
   INFO BOX
══════════════════════════════════════════════ */
.info-box {
    background: var(--gray-50);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 18px;
    margin-bottom: 24px;
}
.info-box-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    font-size: .85rem;
    color: var(--gray-800);
    margin-bottom: 16px;
}
.info-box-title i {
    color: var(--green-600);
}
.info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 0;
    border-bottom: 1px dashed var(--gray-200);
}
.info-item:last-child {
    border-bottom: none;
}
.info-item i {
    width: 20px;
    color: var(--green-600);
    font-size: .9rem;
}
.info-label {
    font-weight: 600;
    color: var(--gray-700);
    margin-right: 4px;
}
.info-value {
    color: var(--gray-600);
}
.info-badge {
    display: inline-block;
    background: var(--green-50);
    color: var(--green-700);
    border: 1.5px solid var(--green-200);
    border-radius: 100px;
    padding: 2px 8px;
    font-size: .65rem;
    font-weight: 600;
    margin-left: 8px;
}

/* ══════════════════════════════════════════════
   FORM ELEMENTS
══════════════════════════════════════════════ */
.form-group {
    margin-bottom: 24px;
}
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
}
.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .85rem;
    color: var(--gray-700);
    background: var(--white);
    transition: var(--transition);
    font-family: var(--font);
}
.form-control:focus {
    outline: none;
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgb(from var(--g500) r g b / .1);
}
.form-control.is-invalid {
    border-color: var(--red-500);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='%23b91c1c'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
}
.invalid-feedback {
    color: var(--red-500);
    font-size: .7rem;
    margin-top: 4px;
}
.text-muted {
    color: var(--gray-400) !important;
    font-size: .7rem;
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 4px;
}

/* ══════════════════════════════════════════════
   BUTTONS
══════════════════════════════════════════════ */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: var(--r);
    font-size: .85rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    white-space: nowrap;
}
.btn-green {
    background: var(--green-600);
    color: white;
    flex: 1;
}
.btn-green:hover:not(:disabled) {
    background: var(--green-700);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgb(from var(--g500) r g b / .2);
}
.btn-green:disabled {
    background: var(--gray-300);
    cursor: not-allowed;
}
.btn-gray {
    background: var(--white);
    color: var(--gray-600);
    border: 1.5px solid var(--gray-200);
    flex: 1;
}
.btn-gray:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
    transform: translateY(-1px);
}
.btn-icon {
    width: 36px;
    height: 36px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--r);
    border: 1.5px solid var(--gray-200);
    background: var(--white);
    color: var(--gray-500);
}
.btn-icon:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}

/* ══════════════════════════════════════════════
   ANIMATIONS
══════════════════════════════════════════════ */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.fa-spinner {
    animation: spin 1s linear infinite;
}

/* ══════════════════════════════════════════════
   SECURITY NOTE
══════════════════════════════════════════════ */
.security-note {
    text-align: center;
    margin-top: 20px;
    color: var(--gray-400);
    font-size: .7rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.security-note i {
    color: var(--green-500);
}
</style>
@endpush

@section('content')
<div class="create-page">

    {{-- Header --}}
    <div class="create-header anim-1">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-cash-register"></i>
                    {{ __('cashier-sessions.create_title') }}
                </h1>
            </div>
        </div>
        
        <div class="breadcrumb">
            <a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> {{ __('cashier-sessions.home') }}</a>
            <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
            <a href="{{ route('cashier.dashboard') }}">{{ __('cashier-sessions.cashier') }}</a>
            <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
            <span class="active">{{ __('cashier-sessions.create_breadcrumb') }}</span>
        </div>
    </div>

    <div class="create-container">

        {{-- Messages de notification --}}
        @if(session('warning'))
        <div class="alert alert-yellow">
            <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="alert-content">{{ session('warning') }}</div>
            <button class="alert-close" onclick="this.parentElement.remove()">✕</button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-red">
            <div class="alert-icon"><i class="fas fa-times-circle"></i></div>
            <div class="alert-content">{{ session('error') }}</div>
            <button class="alert-close" onclick="this.parentElement.remove()">✕</button>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-green">
            <div class="alert-icon"><i class="fas fa-check-circle"></i></div>
            <div class="alert-content">{{ session('success') }}</div>
            <button class="alert-close" onclick="this.parentElement.remove()">✕</button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-red">
            <div class="alert-icon"><i class="fas fa-exclamation-circle"></i></div>
            <div class="alert-content">
                <strong>{{ __('cashier-sessions.alert_fix_errors') }}</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button class="alert-close" onclick="this.parentElement.remove()">✕</button>
        </div>
        @endif

        {{-- Règles de la session --}}
        <div class="rules-card anim-2">
            <div class="rules-title">
                <i class="fas fa-info-circle"></i>
                {{ __('cashier-sessions.rules_title') }}
            </div>
            <ul class="rules-list">
                <li>{{ __('cashier-sessions.rule_one_session') }}</li>
                <li>{{ __('cashier-sessions.rule_records_payments') }}</li>
                <li>{{ __('cashier-sessions.rule_close_anytime') }}</li>
                <li>{{ __('cashier-sessions.rule_starting_balance') }}</li>
            </ul>
        </div>

        {{-- Formulaire de création --}}
        <div class="form-card anim-3">
            <div class="form-icon">
                <i class="fas fa-play"></i>
            </div>
            <div class="form-title">{{ __('cashier-sessions.form_start_new_session') }}</div>
            <div class="form-subtitle">{{ __('cashier-sessions.form_start_subtitle') }}</div>

            <form action="{{ route('cashier.sessions.store') }}" method="POST" id="startSessionForm">
                @csrf
                
                {{-- Informations de la session --}}
                <div class="info-box">
                    <div class="info-box-title">
                        <i class="fas fa-file-invoice"></i>
                        {{ __('cashier-sessions.form_session_info') }}
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-user"></i>
                        <div>
                            <span class="info-label">{{ __('cashier-sessions.form_receptionist') }}</span>
                            <span class="info-value">{{ auth()->user()->name }}</span>
                            <span class="info-badge">{{ auth()->user()->role }}</span>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-calendar"></i>
                        <div>
                            <span class="info-label">{{ __('cashier-sessions.form_date') }}</span>
                            <span class="info-value">{{ now()->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <span class="info-label">{{ __('cashier-sessions.form_time') }}</span>
                            <span class="info-value">{{ now()->format('H:i:s') }}</span>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-wallet"></i>
                        <div>
                            <span class="info-label">{{ __('cashier-sessions.form_starting_balance') }}</span>
                            <span class="info-value">0 FCFA</span>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-hashtag"></i>
                        <div>
                            <span class="info-label">{{ __('cashier-sessions.form_session_number') }}</span>
                            <span class="info-value">{{ __('cashier-sessions.form_auto_generated') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Notes optionnelles --}}
                <div class="form-group">
                    <label for="notes" class="form-label">
                        <i class="fas fa-sticky-note"></i>
                        {{ __('cashier-sessions.form_notes_optional') }}
                    </label>
                    <textarea name="notes" 
                              id="notes"
                              class="form-control @error('notes') is-invalid @enderror" 
                              rows="3"
                              placeholder="{{ __('cashier-sessions.form_notes_placeholder') }}">{{ old('notes') }}</textarea>
                    @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        {{ __('cashier-sessions.form_notes_max') }}
                    </div>
                </div>

                {{-- Boutons d'action --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('cashier.dashboard') }}" class="btn btn-gray">
                        <i class="fas fa-times"></i> {{ __('cashier-sessions.btn_cancel') }}
                    </a>
                    <button type="submit" class="btn btn-green" id="submitBtn">
                        <i class="fas fa-play"></i> {{ __('cashier-sessions.btn_start_session') }}
                    </button>
                </div>

                {{-- Note de sécurité --}}
                <div class="security-note">
                    <i class="fas fa-shield-alt"></i>
                    {{ __('cashier-sessions.security_note') }}
                </div>
            </form>
        </div>
    </div>

</div>

<script>
(function() {
    'use strict';
    
    const form = document.getElementById('startSessionForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (!form || !submitBtn) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        var start = function () {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('cashier-sessions.starting') }}';
            form.submit(); // submit natif : ne re-déclenche pas ce handler
        };

        if (window.Swal) {
            Swal.fire({
                icon: 'question',
                title: '{{ __('cashier-sessions.confirm_start') }}',
                showCancelButton: true,
                confirmButtonText: 'Oui, démarrer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: (getComputedStyle(document.documentElement).getPropertyValue('--hotel-primary') || '#2e8540').trim(),
                heightAuto: false
            }).then(function (r) { if (r.isConfirmed) start(); });
        } else {
            start();
        }
    });
    
})();
</script>

@endsection