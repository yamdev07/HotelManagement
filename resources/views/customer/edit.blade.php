@extends('template.master')
@section('title', 'Modifier le client - ' . $customer->name)
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    /* ── 4 COULEURS (vert, rouge, gris, blanc) ── */
    --green-50:  #f0faf0;
    --green-100: #d4edda;
    --green-500: #2e8540;
    --green-600: #1e6b2e;
    --green-700: #155221;

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

.edit-page {
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
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
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
    box-shadow: 0 4px 10px rgba(46,133,64,.3);
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
    padding: 8px 16px;
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
.btn-outline {
    background: var(--white);
    color: var(--gray-600);
    border: 1.5px solid var(--gray-200);
}
.btn-outline:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.btn-sm {
    padding: 6px 12px;
    font-size: .7rem;
}

/* ══════════════════════════════════════════════
   ALERTS
══════════════════════════════════════════════ */
.alert {
    padding: 14px 18px;
    border-radius: var(--rl);
    margin-bottom: 20px;
    border: 1.5px solid;
    display: flex;
    align-items: center;
    gap: 12px;
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
.alert-icon {
    width: 28px;
    height: 28px;
    border-radius: var(--r);
    background: var(--green-600);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.alert-close {
    margin-left: auto;
    background: none;
    border: none;
    color: currentColor;
    opacity: .6;
    cursor: pointer;
}

/* ══════════════════════════════════════════════
   FORM CARD
══════════════════════════════════════════════ */
.form-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.form-header {
    background: linear-gradient(135deg, var(--green-700), var(--green-600));
    padding: 24px 28px;
}
.form-header h2 {
    color: white;
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.form-header h2 i {
    color: white;
}
.form-header p {
    color: rgba(255,255,255,.9);
    font-size: .8rem;
    margin: 0;
}
.form-body {
    padding: 28px;
}

/* ══════════════════════════════════════════════
   FORM
══════════════════════════════════════════════ */
.form-group {
    margin-bottom: 20px;
}
.form-label {
    display: block;
    font-size: .8rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 6px;
}
.form-label i {
    color: var(--green-600);
    width: 18px;
    margin-right: 4px;
}
.form-control, .form-select {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .8rem;
    transition: var(--transition);
    background: var(--white);
}
.form-control:focus, .form-select:focus {
    outline: none;
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgba(46,133,64,.1);
}
.form-control.is-invalid, .form-select.is-invalid {
    border-color: var(--red-500);
    background: var(--red-50);
}
.form-control:disabled {
    background: var(--gray-50);
    color: var(--gray-500);
    border-color: var(--gray-200);
    cursor: not-allowed;
}
textarea.form-control {
    min-height: 100px;
    resize: vertical;
}
.error-message {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 4px;
    color: var(--red-500);
    font-size: .7rem;
}
.error-message i {
    font-size: .8rem;
}
.text-muted {
    color: var(--gray-400) !important;
    font-size: .65rem;
    margin-top: 4px;
    display: block;
}
.text-muted i {
    color: var(--green-600);
}

/* ══════════════════════════════════════════════
   AVATAR PREVIEW
══════════════════════════════════════════════ */
.avatar-preview {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
    padding: 16px;
    background: var(--gray-50);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
}
.avatar-preview img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 3px solid var(--white);
    box-shadow: var(--shadow-sm);
    object-fit: cover;
}
.avatar-preview-info h6 {
    font-size: .85rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 4px;
}
.avatar-preview-info p {
    font-size: .7rem;
    color: var(--gray-500);
}

/* ══════════════════════════════════════════════
   FILE INPUT
══════════════════════════════════════════════ */
.form-file {
    position: relative;
}
.form-file-input {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}
.form-file-label {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border: 1.5px dashed var(--gray-300);
    border-radius: var(--r);
    background: var(--gray-50);
    transition: var(--transition);
    cursor: pointer;
}
.form-file-label:hover {
    border-color: var(--green-400);
    background: var(--green-50);
}
.form-file-icon {
    width: 36px;
    height: 36px;
    background: var(--white);
    border-radius: var(--r);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--green-600);
    font-size: 1rem;
}
.form-file-text {
    flex: 1;
}
.form-file-text .filename {
    font-size: .8rem;
    font-weight: 500;
    color: var(--gray-700);
}
.form-file-text .hint {
    font-size: .65rem;
    color: var(--gray-500);
}

/* ══════════════════════════════════════════════
   FORM ACTIONS
══════════════════════════════════════════════ */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 28px;
    padding-top: 20px;
    border-top: 1.5px solid var(--gray-200);
}

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media (max-width: 768px) {
    .edit-page { padding: 16px; }
    .form-body { padding: 20px; }
    .avatar-preview { flex-direction: column; text-align: center; }
    .form-actions { flex-direction: column; }
    .form-actions .btn { width: 100%; justify-content: center; }
}
</style>

<div class="edit-page">

    {{-- Breadcrumb --}}
    <div class="breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> {{ __('customer.dashboard') }}</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('customer.index') }}">{{ __('customer.clients') }}</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('customer.show', $customer->id) }}">{{ $customer->name }}</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">{{ __('customer.edit') }}</span>
    </div>

    {{-- En-tête --}}
    <div class="page-header anim-2">
        <div>
            <div class="header-title">
                <span class="header-icon"><i class="fas fa-user-edit"></i></span>
                <h1>{!! __('customer.header_edit_customer', ['name' => $customer->name]) !!}</h1>
            </div>
            <p class="header-subtitle">{{ __('customer.header_edit_subtitle') }}</p>
        </div>
    </div>

    {{-- Messages --}}
    @if(session('success'))
    <div class="alert alert-green">
        <div class="alert-icon"><i class="fas fa-check"></i></div>
        <span>{!! session('success') !!}</span>
        <button class="alert-close" onclick="this.parentElement.remove()">✕</button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-red">
        <div class="alert-icon"><i class="fas fa-exclamation"></i></div>
        <div>
            <strong>{{ __('customer.validation_error') }}</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">✕</button>
    </div>
    @endif

    {{-- Formulaire --}}
    <div class="row justify-content-md-center anim-3">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="form-header">
                    <h2><i class="fas fa-user"></i> {{ __('customer.customer_info') }}</h2>
                    <p>{{ __('customer.customer_info_text') }}</p>
                </div>
                
                <div class="form-body">
                    <form method="POST" action="{{ route('customer.update', $customer->id) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        
                        {{-- Avatar preview --}}
                        @php
                            $avatarUrl = $customer->user ? $customer->user->getAvatar() : null;
                        @endphp
                        
                        <div class="avatar-preview">
                            <img src="{{ $avatarUrl ?? 'https://ui-avatars.com/api/?name='.urlencode($customer->name).'&background=1e6b2e&color=fff&size=80' }}" 
                                 alt="{{ $customer->name }}">
                            <div class="avatar-preview-info">
                                <h6>{{ __('customer.current_photo') }}</h6>
                                <p>{{ __('customer.upload_new_photo') }}</p>
                            </div>
                        </div>
                        
                        {{-- Nom --}}
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-user"></i> {{ __('customer.full_name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name', $customer->name) }}" required>
                            @error('name')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                        
                        {{-- Email (disabled) --}}
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" class="form-control" value="{{ $customer->user->email ?? '' }}" disabled>
                            <span class="text-muted"><i class="fas fa-info-circle"></i> {{ __('customer.email_disabled_hint') }}</span>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-cake-candles"></i> {{ __('customer.date_of_birth') }}</label>
                                    <input type="date" class="form-control @error('birthdate') is-invalid @enderror"
                                           min="1900-01-01" max="{{ date('Y-m-d') }}"
                                           name="birthdate" value="{{ old('birthdate', $customer->birthdate) }}">
                                    @error('birthdate')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-venus-mars"></i> {{ __('customer.gender') }}</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" name="gender">
                                        <option value="">{{ __('customer.gender_select') }}</option>
                                        <option value="Male" {{ old('gender', $customer->gender) == 'Male' ? 'selected' : '' }}>{{ __('customer.gender_male') }}</option>
                                        <option value="Female" {{ old('gender', $customer->gender) == 'Female' ? 'selected' : '' }}>{{ __('customer.gender_female') }}</option>
                                    </select>
                                    @error('gender')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-briefcase"></i> {{ __('customer.profession') }}</label>
                                    <input type="text" class="form-control @error('job') is-invalid @enderror" 
                                           name="job" value="{{ old('job', $customer->job) }}">
                                    @error('job')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-phone"></i> {{ __('customer.phone') }}</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           name="phone" value="{{ old('phone', $customer->phone) }}">
                                    @error('phone')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-map-marker-alt"></i> {{ __('customer.address') }}</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      name="address" rows="3">{{ old('address', $customer->address) }}</textarea>
                            @error('address')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                        
                        {{-- Photo de profil --}}
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-camera"></i> {{ __('customer.new_photo') }}</label>
                            <div class="form-file">
                                <input type="file" class="form-file-input" name="avatar" id="avatar" accept="image/*">
                                <div class="form-file-label">
                                    <div class="form-file-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                    <div class="form-file-text">
                                        <div class="filename" id="fileName">{{ __('customer.no_file_chosen') }}</div>
                                        <div class="hint">{{ __('customer.avatar_format_hint') }}</div>
                                    </div>
                                </div>
                            </div>
                            @error('avatar')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                        
                        {{-- Actions --}}
                        <div class="form-actions">
                            <a href="{{ route('customer.show', $customer->id) }}" class="btn btn-outline">
                                <i class="fas fa-times"></i> {{ __('customer.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-green">
                                <i class="fas fa-save"></i> {{ __('customer.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity .5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // File input preview
    const fileInput = document.getElementById('avatar');
    const fileName = document.getElementById('fileName');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                fileName.textContent = this.files[0].name;
            } else {
                fileName.textContent = 'Aucun fichier choisi';
            }
        });
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            document.querySelector('form').submit();
        }
        if (e.key === 'Escape' && !e.target.matches('input, textarea, select')) {
            window.location.href = "{{ route('customer.show', $customer->id) }}";
        }
    });
});
</script>

@endsection