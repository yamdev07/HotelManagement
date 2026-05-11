@extends('template.master')
@section('title', 'Mon Profil')
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

.profile-page {
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
.profile-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .8rem; color: var(--s400);
    margin-bottom: 20px;
}
.profile-breadcrumb a {
    color: var(--s400); text-decoration: none;
    transition: var(--transition);
}
.profile-breadcrumb a:hover { color: var(--g600); }
.profile-breadcrumb .sep { color: var(--s300); }
.profile-breadcrumb .current { color: var(--s600); font-weight: 500; }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.profile-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.profile-brand { display: flex; align-items: center; gap: 14px; }
.profile-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.profile-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.profile-header-title em { font-style: normal; color: var(--g600); }
.profile-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.profile-header-sub i { color: var(--g500); }
.profile-header-actions { display: flex; align-items: center; gap: 10px; }

/* ══════════════════════════════════════════════
   DATE DISPLAY
══════════════════════════════════════════════ */
.date-display {
    background: var(--white); border: 1.5px solid var(--s200);
    border-radius: var(--rl); padding: 8px 20px;
    box-shadow: var(--shadow-sm);
}
.date-display .day {
    font-size: .7rem; color: var(--s400);
    text-transform: uppercase; letter-spacing: .5px;
}
.date-display .time {
    font-size: 1.1rem; font-weight: 600; color: var(--s800);
    line-height: 1.2; font-family: var(--mono);
}

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
.alert-db-success {
    background: var(--g50); border-color: var(--g200);
    color: var(--g700);
}
.alert-db-danger {
    background: #fee2e2; border-color: #fecaca;
    color: #b91c1c;
}
.alert-db-icon {
    width: 28px; height: 28px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.alert-db-success .alert-db-icon { background: var(--g100); color: var(--g600); }
.alert-db-danger .alert-db-icon { background: #fecaca; color: #b91c1c; }
.alert-db-close {
    margin-left: auto; background: none; border: none;
    color: currentColor; opacity: .6; cursor: pointer;
    font-size: 1rem; transition: var(--transition);
}
.alert-db-close:hover { opacity: 1; }
.alert-db ul {
    margin: 8px 0 0 20px; padding: 0;
}

/* ══════════════════════════════════════════════
   CARTES
══════════════════════════════════════════════ */
.profile-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 20px; box-shadow: var(--shadow-sm);
    transition: var(--transition);
}
.profile-card:hover { box-shadow: var(--shadow-md); }

.profile-card-header {
    padding: 18px 24px; border-bottom: 1.5px solid var(--s100);
    background: var(--white);
}
.profile-card-title {
    display: flex; align-items: center; gap: 10px;
    font-size: .95rem; font-weight: 600; color: var(--s800); margin: 0;
}
.profile-card-title i { color: var(--g500); }
.profile-card-body { padding: 24px; }

/* ══════════════════════════════════════════════
   AVATAR
══════════════════════════════════════════════ */
.avatar-container {
    text-align: center; padding: 24px;
}
.avatar-image {
    width: 140px; height: 140px; border-radius: 50%;
    object-fit: cover; border: 4px solid var(--g200);
    box-shadow: var(--shadow-md); margin-bottom: 16px;
}
.role-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 16px; border-radius: 30px; font-size: .75rem;
    font-weight: 600; background: var(--g100); color: var(--g700);
    border: 1px solid var(--g200); margin-bottom: 20px;
}
.avatar-upload { margin-top: 16px; }
.file-input {
    width: 100%; padding: 10px; border: 1.5px solid var(--s200);
    border-radius: var(--r); font-size: .8rem; color: var(--s700);
    background: var(--white); margin-bottom: 12px;
}
.file-input:focus {
    outline: none; border-color: var(--g400);
    box-shadow: 0 0 0 3px var(--g100);
}

/* ══════════════════════════════════════════════
   FORMULAIRES
══════════════════════════════════════════════ */
.form-group {
    margin-bottom: 20px;
}
.form-label {
    display: block; font-size: .7rem; font-weight: 600;
    color: var(--s600); margin-bottom: 6px;
    text-transform: uppercase; letter-spacing: .5px;
}
.form-control {
    width: 100%; padding: 10px 14px; border-radius: var(--r);
    border: 1.5px solid var(--s200); font-size: .875rem;
    font-family: var(--font); transition: var(--transition);
    background: var(--white);
}
.form-control:focus {
    outline: none; border-color: var(--g400);
    box-shadow: 0 0 0 3px var(--g100);
}
.form-control:read-only {
    background: var(--s50); color: var(--s500);
}

/* ══════════════════════════════════════════════
   BOUTONS
══════════════════════════════════════════════ */
.btn-db {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 10px 20px; border-radius: var(--r);
    font-size: .8rem; font-weight: 500; border: none;
    cursor: pointer; transition: var(--transition);
    text-decoration: none; width: 100%; justify-content: center;
    font-family: var(--font);
}
.btn-db-primary {
    background: var(--g600); color: white;
    box-shadow: 0 2px 10px rgba(46,133,64,.3);
}
.btn-db-primary:hover {
    background: var(--g700); color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.btn-db-success {
    background: var(--g500); color: white;
}
.btn-db-success:hover {
    background: var(--g600); transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(46,133,64,.3);
}
.btn-db-warning {
    background: #fff3cd; color: #856404;
    border: 1.5px solid #ffeeba;
}
.btn-db-warning:hover {
    background: #ffe69c; color: #856404;
    transform: translateY(-1px);
}

/* ══════════════════════════════════════════════
   INFO LINES
══════════════════════════════════════════════ */
.info-line {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 0; border-bottom: 1px solid var(--s100);
}
.info-line:last-child { border-bottom: none; }
.info-icon {
    width: 36px; height: 36px; border-radius: 10px;
    background: var(--g50); color: var(--g600);
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem;
}
.info-content { flex: 1; }
.info-label {
    font-size: .65rem; color: var(--s400);
    text-transform: uppercase; letter-spacing: .5px; margin-bottom: 2px;
}
.info-value {
    font-size: .85rem; font-weight: 500; color: var(--s800);
}

/* ══════════════════════════════════════════════
   PASSWORD STRENGTH
══════════════════════════════════════════════ */
.password-strength {
    margin-top: 8px; height: 4px; border-radius: 2px;
    background: var(--s100); overflow: hidden;
}
.strength-bar {
    height: 100%; width: 0; transition: all .3s;
}
.strength-weak { width: 33.33%; background: #dc3545; }
.strength-medium { width: 66.66%; background: #ffc107; }
.strength-strong { width: 100%; background: var(--g500); }

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media(max-width:768px){
    .profile-page{ padding: 20px; }
    .profile-header{ flex-direction: column; align-items: flex-start; }
    .date-display{ width: 100%; }
}
</style>

<div class="profile-page">
    <!-- Breadcrumb -->
    <div class="profile-breadcrumb anim-1">
        @php
            $dashboardRoute = in_array($user->role, ['Servant', 'Cuisiner'])
                ? route('cashier.dashboard')
                : route('dashboard.index');
        @endphp
        <a href="{{ $dashboardRoute }}"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Mon Profil</span>
    </div>

    <!-- Header -->
    <div class="profile-header anim-2">
        <div class="profile-brand">
            <div class="profile-brand-icon"><i class="fas fa-user-circle"></i></div>
            <div>
                <h1 class="profile-header-title">Bonjour, <em>{{ $user->name }}</em> !</h1>
                <p class="profile-header-sub">
                    <i class="fas fa-user me-1"></i> Gérez vos informations personnelles
                </p>
            </div>
        </div>
        <div class="profile-header-actions">
            <div class="date-display">
                <div class="day">{{ now()->translatedFormat('l j F Y') }}</div>
                <div class="time">{{ now()->format('H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Alertes -->
    @if(session('success'))
    <div class="alert-db alert-db-success anim-3">
        <div class="alert-db-icon"><i class="fas fa-check"></i></div>
        <div class="flex-grow-1">{{ session('success') }}</div>
        <button class="alert-db-close" onclick="this.parentElement.remove()">×</button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-db alert-db-danger anim-3">
        <div class="alert-db-icon"><i class="fas fa-exclamation"></i></div>
        <div class="flex-grow-1">{{ session('error') }}</div>
        <button class="alert-db-close" onclick="this.parentElement.remove()">×</button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert-db alert-db-danger anim-3">
        <div class="alert-db-icon"><i class="fas fa-exclamation"></i></div>
        <div style="flex:1">
            <strong>Erreurs :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button class="alert-db-close" onclick="this.parentElement.remove()">×</button>
    </div>
    @endif

    <div class="row g-4">
        <!-- Colonne gauche - Avatar -->
        <div class="col-lg-4">
            <div class="profile-card anim-4">
                <div class="avatar-container">
                    <img src="{{ $user->getAvatar() }}" 
                         alt="{{ $user->name }}" 
                         class="avatar-image"
                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2e8540&color=fff&size=140'">
                    
                    <div class="role-badge">
                        <i class="fas fa-{{ $user->role == 'Admin' ? 'user-shield' : ($user->role == 'Super' ? 'crown' : 'user-tie') }}"></i>
                        {{ $user->formatted_role ?? $user->role }}
                    </div>

                    <form action="{{ route('profile.update.avatar') }}" method="POST" enctype="multipart/form-data" class="avatar-upload">
                        @csrf
                        <input type="file" name="avatar" class="file-input" accept="image/*" id="avatarInput" required>
                        <button type="submit" class="btn-db btn-db-primary">
                            <i class="fas fa-upload me-2"></i> Changer la photo
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Colonne droite - Informations -->
        <div class="col-lg-8">
            <!-- Informations du compte -->
            <div class="profile-card anim-5">
                <div class="profile-card-header">
                    <h5 class="profile-card-title">
                        <i class="fas fa-id-card"></i>
                        Informations du compte
                    </h5>
                </div>
                <div class="profile-card-body">
                    <form action="{{ route('profile.update.info') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nom complet</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $user->name) }}" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $user->email) }}" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone', $user->phone) }}">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Rôle</label>
                                    <input type="text" class="form-control" value="{{ $user->formatted_role ?? $user->role }}" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-db btn-db-success mt-3">
                            <i class="fas fa-save me-2"></i> Mettre à jour
                        </button>
                    </form>
                </div>
            </div>

            <!-- Changer le mot de passe -->
            <div class="profile-card anim-6">
                <div class="profile-card-header">
                    <h5 class="profile-card-title">
                        <i class="fas fa-lock"></i>
                        Changer le mot de passe
                    </h5>
                </div>
                <div class="profile-card-body">
                    <form action="{{ route('profile.update.password') }}" method="POST" id="passwordForm">
                        @csrf
                        
                        <div class="form-group">
                            <label class="form-label">Mot de passe actuel</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nouveau mot de passe</label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                    <div class="password-strength">
                                        <div class="strength-bar" id="strengthBar"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Confirmer le mot de passe</label>
                                    <input type="password" name="password_confirmation" id="passwordConfirm" class="form-control" required>
                                    <div style="font-size:.7rem; margin-top:4px;" id="passwordMatch"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert-db alert-db-success mt-3" style="background:var(--g50); color:var(--g700); padding:10px;">
                            <i class="fas fa-info-circle me-2"></i>
                            <small>Le mot de passe doit contenir au moins 6 caractères.</small>
                        </div>
                        
                        <button type="submit" class="btn-db btn-db-warning mt-3">
                            <i class="fas fa-key me-2"></i> Modifier le mot de passe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss des alertes
    const alerts = document.querySelectorAll('.alert-db');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // Force du mot de passe
    const password = document.getElementById('password');
    const strengthBar = document.getElementById('strengthBar');
    const passwordConfirm = document.getElementById('passwordConfirm');
    const matchMessage = document.getElementById('passwordMatch');

    if (password) {
        password.addEventListener('input', function() {
            const value = this.value;
            let strength = 0;

            if (value.length >= 6) strength += 1;
            if (/[a-z]/.test(value)) strength += 1;
            if (/[A-Z]/.test(value)) strength += 1;
            if (/[0-9]/.test(value)) strength += 1;

            strengthBar.className = 'strength-bar';
            if (strength <= 2) strengthBar.classList.add('strength-weak');
            else if (strength <= 3) strengthBar.classList.add('strength-medium');
            else strengthBar.classList.add('strength-strong');
        });
    }

    // Correspondance des mots de passe
    if (passwordConfirm) {
        passwordConfirm.addEventListener('input', function() {
            if (password.value !== this.value) {
                matchMessage.innerHTML = '<i class="fas fa-times-circle text-danger me-1"></i> Les mots de passe ne correspondent pas';
                matchMessage.style.color = '#dc3545';
            } else {
                matchMessage.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i> Les mots de passe correspondent';
                matchMessage.style.color = '#28a745';
            }
        });
    }

    // Aperçu avatar
    const avatarInput = document.getElementById('avatarInput');
    const avatarImage = document.querySelector('.avatar-image');

    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Validation formulaire
    const passwordForm = document.getElementById('passwordForm');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            if (password.value !== passwordConfirm.value) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas !');
            }
            if (password.value.length < 6) {
                e.preventDefault();
                alert('Le mot de passe doit contenir au moins 6 caractères !');
            }
        });
    }
});
</script>
@endsection