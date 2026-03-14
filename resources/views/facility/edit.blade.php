@extends('template.master')
@section('title', 'Modifier un équipement')
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

/* ══════════════════════════════════════════════
   ALERT
══════════════════════════════════════════════ */
.alert {
    padding: 14px 18px;
    border-radius: var(--rl);
    margin-bottom: 20px;
    border: 1.5px solid;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}
.alert-red {
    background: var(--red-50);
    border-color: var(--red-100);
    color: var(--red-500);
}
.alert-icon {
    width: 24px;
    height: 24px;
    border-radius: var(--r);
    background: var(--red-500);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.alert-content {
    flex: 1;
}
.alert-content ul {
    margin-top: 4px;
    padding-left: 20px;
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
    background: linear-gradient(135deg, var(--green-700), var(--green-600));
    padding: 20px 24px;
}
.card-header h2 {
    color: white;
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.card-header h2 i {
    color: white;
}
.card-body {
    padding: 24px;
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
textarea.form-control {
    min-height: 100px;
    resize: vertical;
}
.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1.5px solid var(--gray-200);
}
.icon-preview {
    margin-left: 8px;
    color: var(--green-600);
    font-size: 1rem;
}

/* ══════════════════════════════════════════════
   ICON LIST
══════════════════════════════════════════════ */
.icon-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 8px;
    margin-top: 8px;
    padding: 12px;
    background: var(--gray-50);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    max-height: 200px;
    overflow-y: auto;
}
.icon-option {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 10px;
    border-radius: var(--r);
    cursor: pointer;
    transition: var(--transition);
}
.icon-option:hover {
    background: var(--green-50);
}
.icon-option input[type="radio"] {
    margin-right: 6px;
    accent-color: var(--green-600);
}
.icon-option i {
    color: var(--green-600);
    width: 20px;
}
.icon-option span {
    font-size: .75rem;
    color: var(--gray-700);
}
</style>

<div class="edit-page">

    {{-- Breadcrumb --}}
    <div class="breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('facility.index') }}">Équipements</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">{{ $facility->name }}</span>
    </div>

    {{-- En-tête --}}
    <div class="page-header anim-2">
        <div>
            <div class="header-title">
                <span class="header-icon"><i class="fas fa-edit"></i></span>
                <h1>Modifier un <em>équipement</em></h1>
            </div>
            <p class="header-subtitle">Mettez à jour les informations de l'équipement</p>
        </div>
    </div>

    {{-- Erreurs --}}
    @if($errors->any())
        <div class="alert alert-red">
            <div class="alert-icon"><i class="fas fa-exclamation"></i></div>
            <div class="alert-content">
                <strong>Erreur de validation</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Formulaire --}}
    <div class="row justify-content-md-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-edit"></i> {{ $facility->name }}</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('facility.update', $facility) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nom --}}
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-tag"></i> Nom de l'équipement</label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name', $facility->name) }}" required>
                        </div>

                        {{-- Détail --}}
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-align-left"></i> Description</label>
                            <textarea name="detail" class="form-control" required>{{ old('detail', $facility->detail) }}</textarea>
                        </div>

                        {{-- Icône --}}
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-icons"></i> Icône</label>
                            
                            @php
                                $icons = [
                                    'fas fa-wifi' => 'WiFi',
                                    'fas fa-swimming-pool' => 'Piscine',
                                    'fas fa-dumbbell' => 'Salle de sport',
                                    'fas fa-concierge-bell' => 'Service en chambre',
                                    'fas fa-parking' => 'Parking',
                                    'fas fa-utensils' => 'Restaurant',
                                    'fas fa-spa' => 'Spa',
                                    'fas fa-tv' => 'Télévision',
                                    'fas fa-shuttle-van' => 'Navette',
                                    'fas fa-cocktail' => 'Bar',
                                    'fas fa-wine-glass-alt' => 'Vin',
                                    'fas fa-tshirt' => 'Blanchisserie',
                                    'fas fa-laptop' => 'Ordinateur',
                                    'fas fa-phone' => 'Téléphone',
                                    'fas fa-coffee' => 'Café',
                                    'fas fa-snowflake' => 'Climatisation',
                                    'fas fa-hot-tub' => 'Jacuzzi',
                                    'fas fa-bath' => 'Baignoire',
                                    'fas fa-shower' => 'Douche',
                                    'fas fa-iron' => 'Fer à repasser',
                                    'fas fa-baby' => 'Équipement bébé',
                                ];
                            @endphp

                            <select name="icon" class="form-select" id="iconSelect">
                                <option value="">-- Aucune icône --</option>
                                @foreach($icons as $class => $label)
                                    <option value="{{ $class }}" {{ old('icon', $facility->icon) == $class ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Aperçu de l'icône sélectionnée --}}
                            <div class="mt-2 d-flex align-items-center">
                                <small class="text-muted me-2">Aperçu :</small>
                                <span id="iconPreview" class="icon-preview">
                                    @if($facility->icon)
                                        <i class="{{ $facility->icon }} fa-lg"></i>
                                    @else
                                        <i class="fas fa-ban text-muted"></i>
                                    @endif
                                </span>
                            </div>

                            {{-- Grille alternative (optionnelle) --}}
                            <details class="mt-2">
                                <summary class="text-muted small">Choisir dans la grille</summary>
                                <div class="icon-grid">
                                    @foreach($icons as $class => $label)
                                        <label class="icon-option">
                                            <input type="radio" name="icon_radio" value="{{ $class }}" 
                                                   {{ old('icon', $facility->icon) == $class ? 'checked' : '' }}
                                                   onchange="document.getElementById('iconSelect').value = this.value; updatePreview(this.value);">
                                            <i class="{{ $class }}"></i>
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </details>
                        </div>

                        {{-- Actions --}}
                        <div class="form-actions">
                            <a href="{{ route('facility.index') }}" class="btn btn-gray">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-green">
                                <i class="fas fa-save"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function updatePreview(iconClass) {
    const preview = document.getElementById('iconPreview');
    if (iconClass) {
        preview.innerHTML = `<i class="${iconClass} fa-lg"></i>`;
    } else {
        preview.innerHTML = `<i class="fas fa-ban text-muted"></i>`;
    }
}

document.getElementById('iconSelect')?.addEventListener('change', function() {
    updatePreview(this.value);
});

// Synchroniser les radios avec le select
document.querySelectorAll('input[name="icon_radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('iconSelect').value = this.value;
    });
});
</script>

@endsection