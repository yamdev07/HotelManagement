@extends('template.master')
@section('title', 'Ajouter un Utilisateur')

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

.add-user-page {
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
.add-user-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .8rem; color: var(--s400);
    margin-bottom: 20px;
}
.add-user-breadcrumb a {
    color: var(--s400); text-decoration: none;
    transition: var(--transition);
}
.add-user-breadcrumb a:hover { color: var(--g600); }
.add-user-breadcrumb .sep { color: var(--s300); }
.add-user-breadcrumb .current { color: var(--s600); font-weight: 500; }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.add-user-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.add-user-brand { display: flex; align-items: center; gap: 14px; }
.add-user-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.add-user-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.add-user-header-title em { font-style: normal; color: var(--g600); }
.add-user-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.add-user-header-sub i { color: var(--g500); }
.add-user-header-actions { display: flex; align-items: center; gap: 10px; }

/* ══════════════════════════════════════════════
   BOUTONS
══════════════════════════════════════════════ */
.btn-db {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: var(--r);
    font-size: .8rem; font-weight: 500; border: none;
    cursor: pointer; transition: var(--transition);
    text-decoration: none; white-space: nowrap; line-height: 1;
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
.btn-db-secondary {
    background: var(--white); color: var(--s600);
    border: 1.5px solid var(--s200);
}
.btn-db-secondary:hover {
    background: var(--s50); border-color: var(--s300);
    color: var(--s900); text-decoration: none;
}

/* ══════════════════════════════════════════════
   CARTE PRINCIPALE
══════════════════════════════════════════════ */
.add-user-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 20px; box-shadow: var(--shadow-sm);
}
.add-user-card-header {
    padding: 28px 24px;
    background: linear-gradient(135deg, var(--g700), var(--g500));
    color: white;
    display: flex; align-items: center; gap: 16px;
}
.add-user-card-header-icon {
    width: 56px; height: 56px; border-radius: 12px;
    background: rgba(255,255,255,0.15);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; color: white;
}
.add-user-card-header-content {
    flex: 1;
}
.add-user-card-title {
    font-size: 1.3rem; font-weight: 600; color: white; margin: 0 0 4px;
}
.add-user-card-subtitle {
    font-size: .8rem; color: rgba(255,255,255,0.8); margin: 0;
}
.add-user-card-body { padding: 32px 28px; }

/* ══════════════════════════════════════════════
   FORMULAIRES
══════════════════════════════════════════════ */
.form-section {
    margin-bottom: 32px; padding-bottom: 32px;
    border-bottom: 1.5px solid var(--s100);
}
.form-section:last-of-type { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }

.section-header {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 24px;
}
.section-header i {
    font-size: 1.1rem; color: var(--g500);
}
.section-header h3 {
    font-size: 1rem; font-weight: 600; color: var(--s800); margin: 0;
}

.form-group-modern {
    margin-bottom: 20px;
}
.form-group-modern:last-child { margin-bottom: 0; }

.form-label-modern {
    display: block; font-size: .75rem; font-weight: 600;
    color: var(--s600); margin-bottom: 6px;
    text-transform: uppercase; letter-spacing: .5px;
}
.required {
    color: #b91c1c; margin-left: 4px;
}

.input-with-icon {
    position: relative; display: flex; align-items: center;
}
.input-icon {
    position: absolute; left: 14px; color: var(--s400);
    font-size: .9rem; pointer-events: none; z-index: 2;
}
.form-control-modern, .form-select-modern {
    width: 100%; padding: 12px 16px 12px 42px;
    border: 1.5px solid var(--s200); border-radius: var(--r);
    font-size: .875rem; font-family: var(--font);
    transition: var(--transition); background: var(--white);
    appearance: none;
}
.form-control-modern:focus, .form-select-modern:focus {
    outline: none; border-color: var(--g400);
    box-shadow: 0 0 0 3px var(--g100);
}
.form-control-modern.is-invalid, .form-select-modern.is-invalid {
    border-color: #b91c1c; background: #fee2e2;
}
.form-select-modern {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 14 14'%3E%3Cpath fill='%23737873' d='M7 10L2 5h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 16px center;
    padding-right: 42px;
}

/* Password field */
.password-field {
    position: relative;
}
.toggle-password {
    position: absolute; right: 14px; top: 50%;
    transform: translateY(-50%); background: none;
    border: none; color: var(--s400); cursor: pointer;
    padding: 0; font-size: .9rem; transition: var(--transition);
    z-index: 2;
}
.toggle-password:hover { color: var(--g600); }

/* Messages d'erreur */
.error-message {
    display: flex; align-items: center; gap: 6px;
    color: #b91c1c; font-size: .75rem; margin-top: 6px;
    padding: 6px 10px; background: #fee2e2;
    border-radius: var(--r); border-left: 3px solid #b91c1c;
}

/* Hint */
.form-hint {
    display: flex; align-items: center; gap: 6px;
    color: var(--s400); font-size: .7rem; margin-top: 6px;
}

/* Description du rôle */
.role-description { margin-top: 12px; }
.alert-info {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 16px; background: var(--g50);
    border-left: 3px solid var(--g500); border-radius: var(--r);
    font-size: .8rem; color: var(--s700);
}
.alert-info i { color: var(--g500); font-size: .9rem; }

/* ========================================
   FORM ACTIONS
   ======================================== */
.form-actions {
    display: flex; gap: 12px; justify-content: flex-end;
    margin-top: 32px; padding-top: 24px;
    border-top: 1.5px solid var(--s100);
}

/* ========================================
   INFO CARD
   ======================================== */
.info-card {
    background: var(--g50); border-radius: var(--rl);
    border: 1.5px solid var(--g200); padding: 20px;
    display: flex; align-items: center; gap: 16px;
}
.info-card-icon {
    width: 48px; height: 48px; border-radius: var(--rl);
    background: var(--g100); color: var(--g600);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}
.info-card-content {
    flex: 1;
}
.info-card-content h4 {
    font-size: .9rem; font-weight: 600; color: var(--s800);
    margin: 0 0 8px;
}
.info-card-content ul {
    margin: 0; padding-left: 18px;
}
.info-card-content li {
    font-size: .75rem; color: var(--s600); margin-bottom: 4px;
}

/* ========================================
   RESPONSIVE
   ======================================== */
@media(max-width:768px){
    .add-user-page{ padding: 20px; }
    .add-user-header{ flex-direction: column; align-items: flex-start; }
    .add-user-card-header{ flex-direction: column; text-align: center; }
    .add-user-card-body{ padding: 24px 20px; }
    .form-actions{ flex-direction: column-reverse; }
    .form-actions .btn-db{ width: 100%; justify-content: center; }
    .info-card{ flex-direction: column; text-align: center; }
}
</style>

<div class="add-user-page">
    <!-- Breadcrumb -->
    <div class="add-user-breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('user.index') }}">Utilisateurs</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Ajouter</span>
    </div>

    <!-- Header -->
    <div class="add-user-header anim-2">
        <div class="add-user-brand">
            <div class="add-user-brand-icon"><i class="fas fa-user-plus"></i></div>
            <div>
                <h1 class="add-user-header-title">Ajouter un <em>utilisateur</em></h1>
                <p class="add-user-header-sub">
                    <i class="fas fa-users me-1"></i> Créer un nouveau compte utilisateur
                </p>
            </div>
        </div>
        <div class="add-user-header-actions">
            <a href="{{ route('user.index') }}" class="btn-db btn-db-ghost">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <!-- Formulaire -->
            <div class="add-user-card anim-3">
                <div class="add-user-card-header">
                    <div class="add-user-card-header-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="add-user-card-header-content">
                        <h2 class="add-user-card-title">Informations de l'utilisateur</h2>
                        <p class="add-user-card-subtitle">Veuillez remplir tous les champs obligatoires</p>
                    </div>
                </div>

                <div class="add-user-card-body">
                    <form method="POST" action="{{ route('user.store') }}" class="modern-form" id="userForm">
                        @csrf
                        
                        <!-- Section Informations personnelles -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-user-circle"></i>
                                <h3>Informations personnelles</h3>
                            </div>

                            <div class="form-group-modern">
                                <label for="name" class="form-label-modern">
                                    Nom complet <span class="required">*</span>
                                </label>
                                <div class="input-with-icon">
                                    <i class="fas fa-user input-icon"></i>
                                    <input 
                                        type="text" 
                                        class="form-control-modern @error('name') is-invalid @enderror" 
                                        id="name"
                                        name="name" 
                                        value="{{ old('name') }}"
                                        placeholder="Ex: Jean Dupont">
                                </div>
                                @error('name')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Section Identifiants -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-lock"></i>
                                <h3>Identifiants de connexion</h3>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="email" class="form-label-modern">
                                            Adresse email <span class="required">*</span>
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-envelope input-icon"></i>
                                            <input 
                                                type="email" 
                                                class="form-control-modern @error('email') is-invalid @enderror" 
                                                id="email"
                                                name="email" 
                                                value="{{ old('email') }}"
                                                placeholder="exemple@hotel.com">
                                        </div>
                                        @error('email')
                                            <div class="error-message">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="password" class="form-label-modern">
                                            Mot de passe <span class="required">*</span>
                                        </label>
                                        <div class="input-with-icon password-field">
                                            <i class="fas fa-key input-icon"></i>
                                            <input 
                                                type="password" 
                                                class="form-control-modern @error('password') is-invalid @enderror" 
                                                id="password"
                                                name="password" 
                                                placeholder="Minimum 8 caractères">
                                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                                <i class="fas fa-eye" id="toggleIcon"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="error-message">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @else
                                            <div class="form-hint">
                                                <i class="fas fa-info-circle"></i> Minimum 8 caractères
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Rôle -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-user-tag"></i>
                                <h3>Rôle et permissions</h3>
                            </div>

                            <div class="form-group-modern">
                                <label for="role" class="form-label-modern">
                                    Rôle de l'utilisateur <span class="required">*</span>
                                </label>
                                <div class="input-with-icon">
                                    <i class="fas fa-briefcase input-icon"></i>
                                    <select 
                                        id="role" 
                                        name="role" 
                                        class="form-select-modern @error('role') is-invalid @enderror">
                                        <option value="" selected disabled>-- Sélectionner un rôle --</option>
                                        <option value="Admin" @if(old('role') == 'Admin') selected @endif>
                                            👑 Administrateur
                                        </option>
                                        <option value="Receptionist" @if(old('role') == 'Receptionist') selected @endif>
                                            🎯 Réceptionniste
                                        </option>
                                        <option value="Housekeeping" @if(old('role') == 'Housekeeping') selected @endif>
                                            🧹 Femme de ménage
                                        </option>
                                        <option value="Customer" @if(old('role') == 'Customer') selected @endif>
                                            👤 Client
                                        </option>
                                        <option value="Servant" @if(old('role') == 'Servant') selected @endif>
                                            🍳 Serveur
                                        </option>
                                    </select>
                                </div>
                                @error('role')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                                
                                <!-- Description du rôle -->
                                <div class="role-description" id="roleDescription" style="display: none;">
                                    <div class="alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        <span id="roleDescriptionText"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="form-actions">
                            <button type="button" class="btn-db btn-db-secondary" onclick="window.history.back()">
                                <i class="fas fa-times me-2"></i> Annuler
                            </button>
                            <button type="submit" class="btn-db btn-db-primary" id="submitBtn">
                                <i class="fas fa-check me-2"></i> Créer l'utilisateur
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="info-card anim-4">
                <div class="info-card-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div class="info-card-content">
                    <h4>Conseils de sécurité</h4>
                    <ul>
                        <li>Utilisez un mot de passe fort et unique</li>
                        <li>Attribuez le rôle approprié selon les responsabilités</li>
                        <li>Vérifiez l'adresse email avant la création</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Descriptions des rôles
    const roleDescriptions = {
        'Admin': 'Accès complet au système avec tous les privilèges administratifs. Peut gérer les utilisateurs, les réservations, et configurer le système.',
        'Receptionist': 'Gère les réservations, l\'accueil des clients, et les opérations quotidiennes de la réception.',
        'Housekeeping': 'Responsable de l\'entretien des chambres et du nettoyage de l\'établissement.',
        'Customer': 'Accès limité pour effectuer des réservations et consulter son historique.',
        'Servant': 'Accès dédié aux modules Restaurant et Caisse pour la prise de commande et les encaissements.'
    };
    
    // Afficher la description du rôle
    const roleSelect = document.getElementById('role');
    const roleDescriptionDiv = document.getElementById('roleDescription');
    const roleDescriptionText = document.getElementById('roleDescriptionText');
    
    if (roleSelect) {
        roleSelect.addEventListener('change', function() {
            const selectedRole = this.value;
            
            if (selectedRole && roleDescriptions[selectedRole]) {
                roleDescriptionText.textContent = roleDescriptions[selectedRole];
                roleDescriptionDiv.style.display = 'block';
                roleDescriptionDiv.style.animation = 'fadeSlide .3s ease';
            } else {
                roleDescriptionDiv.style.display = 'none';
            }
        });
        
        // Afficher si déjà sélectionné
        if (roleSelect.value && roleDescriptions[roleSelect.value]) {
            roleDescriptionText.textContent = roleDescriptions[roleSelect.value];
            roleDescriptionDiv.style.display = 'block';
        }
    }
    
    // Focus auto sur le premier champ
    setTimeout(() => {
        const firstInput = document.getElementById('name');
        if (firstInput) firstInput.focus();
    }, 300);
});

// Toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Loading state on submit
document.getElementById('userForm')?.addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Création...';
});
</script>
@endsection