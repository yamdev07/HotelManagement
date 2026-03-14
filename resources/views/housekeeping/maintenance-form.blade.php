@extends('template.master')

@section('title', 'Signalement Maintenance - Chambre ' . $room->number)

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

.maintenance-page {
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
.btn-red {
    background: var(--red-500);
    color: white;
}
.btn-red:hover {
    background: var(--red-600);
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
.btn-lg {
    padding: 12px 24px;
    font-size: .9rem;
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
    padding: 16px 20px;
    border-bottom: 1.5px solid var(--gray-200);
    background: var(--red-500);
    color: white;
}
.card-header i {
    color: white;
}
.card-body {
    padding: 24px;
}
.card-footer {
    padding: 16px 20px;
    border-top: 1.5px solid var(--gray-200);
    background: var(--gray-50);
}

/* ══════════════════════════════════════════════
   ALERT
══════════════════════════════════════════════ */
.alert {
    padding: 16px 20px;
    border-radius: var(--rl);
    border: 1.5px solid;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}
.alert-info {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.alert-warning {
    background: var(--red-50);
    border-color: var(--red-100);
    color: var(--red-500);
}
.alert-icon {
    font-size: 1.5rem;
    flex-shrink: 0;
}
.alert-content {
    flex: 1;
}
.alert-content h6 {
    font-size: .85rem;
    font-weight: 600;
    margin-bottom: 4px;
}
.room-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 100px;
    font-size: .65rem;
    font-weight: 600;
}
.room-badge.warning { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }
.room-badge.info { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }

/* ══════════════════════════════════════════════
   FORM
══════════════════════════════════════════════ */
.form-group {
    margin-bottom: 24px;
}
.form-label {
    display: block;
    font-size: .8rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 8px;
}
.form-label i {
    color: var(--green-600);
    width: 20px;
}
.form-control, .form-select {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .85rem;
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
.input-group {
    display: flex;
    align-items: center;
}
.input-group .form-control {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}
.input-group-text {
    background: var(--gray-50);
    border: 1.5px solid var(--gray-200);
    border-left: none;
    padding: 12px 16px;
    border-radius: var(--r);
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    color: var(--gray-600);
}

/* ══════════════════════════════════════════════
   URGENCY CARDS
══════════════════════════════════════════════ */
.urgency-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}
.urgency-card {
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 16px;
    text-align: center;
    cursor: pointer;
    transition: var(--transition);
    background: var(--white);
}
.urgency-card:hover {
    border-color: var(--green-300);
    transform: translateY(-2px);
}
.urgency-card.selected {
    border-color: var(--green-600);
    background: var(--green-50);
}
.urgency-icon {
    font-size: 2rem;
    margin-bottom: 8px;
}
.urgency-icon.low { color: var(--green-600); }
.urgency-icon.medium { color: var(--red-500); }
.urgency-icon.high { color: var(--red-500); }
.urgency-title {
    font-size: .8rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 2px;
}
.urgency-desc {
    font-size: .65rem;
    color: var(--gray-500);
}
.radio-input {
    display: none;
}

/* ══════════════════════════════════════════════
   PHOTO UPLOAD
══════════════════════════════════════════════ */
.upload-area {
    border: 1.5px dashed var(--gray-300);
    border-radius: var(--rl);
    padding: 32px;
    text-align: center;
    background: var(--gray-50);
    cursor: pointer;
    transition: var(--transition);
}
.upload-area:hover {
    border-color: var(--green-400);
    background: var(--green-50);
}
.upload-icon {
    font-size: 2.5rem;
    color: var(--green-600);
    margin-bottom: 12px;
}
.photo-preview {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-top: 16px;
}
.photo-item {
    position: relative;
    border-radius: var(--r);
    overflow: hidden;
}
.photo-item img {
    width: 100%;
    height: 80px;
    object-fit: cover;
}
.photo-remove {
    position: absolute;
    top: 4px;
    right: 4px;
    width: 24px;
    height: 24px;
    background: var(--red-500);
    color: white;
    border: none;
    border-radius: var(--r);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media (max-width: 768px) {
    .maintenance-page { padding: 16px; }
    .urgency-grid { grid-template-columns: 1fr; }
    .photo-preview { grid-template-columns: repeat(2, 1fr); }
}
</style>

<div class="maintenance-page">

    {{-- Breadcrumb --}}
    <div class="breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('housekeeping.index') }}">Housekeeping</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('availability.room.detail', $room->id) }}">Chambre {{ $room->number }}</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Maintenance</span>
    </div>

    {{-- En-tête --}}
    <div class="page-header anim-2">
        <div>
            <div class="header-title">
                <span class="header-icon"><i class="fas fa-tools"></i></span>
                <h1>Signalement <em>maintenance</em></h1>
            </div>
            <p class="header-subtitle">Chambre {{ $room->number }}</p>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-gray">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    {{-- Formulaire --}}
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Infos chambre --}}
            <div class="alert alert-info mb-4">
                <div class="alert-icon"><i class="fas fa-door-closed"></i></div>
                <div class="alert-content">
                    <h6>Chambre {{ $room->number }}</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <small><i class="fas fa-layer-group" style="color:var(--green-600);"></i> Type: {{ $room->type->name ?? 'Standard' }}</small>
                        </div>
                        <div class="col-md-6">
                            <small><i class="fas fa-bed" style="color:var(--green-600);"></i> Statut: 
                                <span class="room-badge {{ $room->room_status_id == 2 ? 'warning' : 'info' }}">
                                    {{ $room->roomStatus->name ?? 'Inconnu' }}
                                </span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Carte formulaire --}}
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-exclamation-triangle"></i> Détails de la maintenance
                </div>
                <div class="card-body">
                    <form action="{{ route('housekeeping.mark-maintenance', $room->id) }}" method="POST" id="maintenanceForm" enctype="multipart/form-data">
                        @csrf

                        {{-- Raison --}}
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-clipboard-list"></i> Type de problème</label>
                            <select class="form-select" name="maintenance_reason" required>
                                <option value="">Sélectionner...</option>
                                <optgroup label="Problèmes techniques">
                                    <option value="Électricité">Problème électrique</option>
                                    <option value="Plomberie">Fuite d'eau / Plomberie</option>
                                    <option value="Climatisation">Climatisation défectueuse</option>
                                    <option value="Chauffage">Problème de chauffage</option>
                                    <option value="Télévision">Télévision défectueuse</option>
                                    <option value="WiFi">Problème de connexion WiFi</option>
                                </optgroup>
                                <optgroup label="Équipements">
                                    <option value="Meuble">Meuble cassé</option>
                                    <option value="Literie">Literie endommagée</option>
                                    <option value="Salle de bain">Équipement salle de bain</option>
                                    <option value="Fenêtre">Fenêtre / Store</option>
                                    <option value="Serrure">Problème de serrure</option>
                                </optgroup>
                                <option value="Autre">Autre problème</option>
                            </select>
                        </div>

                        {{-- Description --}}
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-align-left"></i> Description détaillée</label>
                            <textarea class="form-control" name="detailed_description" rows="4" placeholder="Décrivez le problème..."></textarea>
                        </div>

                        {{-- Urgence --}}
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-exclamation"></i> Niveau d'urgence</label>
                            <div class="urgency-grid">
                                <label class="urgency-card" data-value="low">
                                    <input type="radio" name="urgency" value="low" class="radio-input" checked>
                                    <div class="urgency-icon low"><i class="fas fa-flag"></i></div>
                                    <div class="urgency-title">Basse</div>
                                    <div class="urgency-desc">Problème mineur</div>
                                </label>
                                <label class="urgency-card" data-value="medium">
                                    <input type="radio" name="urgency" value="medium" class="radio-input">
                                    <div class="urgency-icon medium"><i class="fas fa-flag"></i></div>
                                    <div class="urgency-title">Moyenne</div>
                                    <div class="urgency-desc">À traiter rapidement</div>
                                </label>
                                <label class="urgency-card" data-value="high">
                                    <input type="radio" name="urgency" value="high" class="radio-input">
                                    <div class="urgency-icon high"><i class="fas fa-flag"></i></div>
                                    <div class="urgency-title">Haute</div>
                                    <div class="urgency-desc">Problème critique</div>
                                </label>
                            </div>
                        </div>

                        {{-- Durée --}}
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-clock"></i> Durée estimée</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="estimated_duration" min="1" max="168" value="2" required>
                                <span class="input-group-text">heures</span>
                            </div>
                        </div>

                        {{-- Photos --}}
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-camera"></i> Photos (optionnel)</label>
                            <div class="upload-area" id="uploadArea">
                                <input type="file" id="photoInput" multiple accept="image/*" class="d-none" name="photos[]">
                                <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                <p>Cliquez ou glissez pour ajouter des photos</p>
                                <small class="text-muted">JPG, PNG (max 5 Mo)</small>
                            </div>
                            <div class="photo-preview" id="photoPreview"></div>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-gray" onclick="history.back()"><i class="fas fa-times"></i> Annuler</button>
                        <button class="btn btn-red" form="maintenanceForm"><i class="fas fa-paper-plane"></i> Signaler</button>
                    </div>
                </div>
            </div>

            {{-- Avertissement --}}
            <div class="alert alert-warning mt-4">
                <div class="alert-icon"><i class="fas fa-info-circle"></i></div>
                <div class="alert-content">
                    <h6>Important</h6>
                    <p class="mb-0">La chambre sera marquée "En maintenance" et ne sera plus disponible à la vente.</p>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Urgence cards
    document.querySelectorAll('.urgency-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.urgency-card').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('.radio-input').checked = true;
        });
    });

    // Upload area
    const uploadArea = document.getElementById('uploadArea');
    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');
    let filesArray = [];

    uploadArea.addEventListener('click', () => photoInput.click());

    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.style.background = 'var(--green-50)';
        uploadArea.style.borderColor = 'var(--green-400)';
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.style.background = '';
        uploadArea.style.borderColor = '';
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.style.background = '';
        uploadArea.style.borderColor = '';
        const files = Array.from(e.dataTransfer.files);
        handleFiles(files);
    });

    photoInput.addEventListener('change', (e) => {
        const files = Array.from(e.target.files);
        handleFiles(files);
    });

    function handleFiles(newFiles) {
        filesArray = [...filesArray, ...newFiles];
        displayPreviews();
    }

    function displayPreviews() {
        photoPreview.innerHTML = '';
        filesArray.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'photo-item';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="">
                        <button class="photo-remove" data-index="${index}"><i class="fas fa-times"></i></button>
                    `;
                    photoPreview.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    photoPreview.addEventListener('click', (e) => {
        if (e.target.closest('.photo-remove')) {
            const index = e.target.closest('.photo-remove').dataset.index;
            filesArray.splice(index, 1);
            displayPreviews();
            
            const dt = new DataTransfer();
            filesArray.forEach(f => dt.items.add(f));
            photoInput.files = dt.files;
        }
    });
});
</script>

@endsection