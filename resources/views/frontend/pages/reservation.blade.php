@extends('frontend.layouts.master')

@section('title', 'Réservation en ligne - Hôtel Cactus Palace')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
    --primary: #1A472A;
    --primary-dark: #0F2918;
    --primary-light: rgba(201,169,97,0.08);
    --gold: #C9A961;
    --error: #e53935;
    --error-light: #FFEBEE;
}

* { -webkit-tap-highlight-color: transparent; }
*:focus, *:focus-visible { outline: none !important; box-shadow: none !important; }

body { font-family: 'Plus Jakarta Sans', sans-serif; }

/* Hero */
.hero-reservation {
    background: linear-gradient(rgba(15,41,24,.72), rgba(15,41,24,.85)),
                url('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1920') center/cover fixed;
    color: white; padding: 140px 0 80px; text-align: center; position: relative;
}
.hero-reservation .eyebrow-badge {
    display: inline-block; background: rgba(201,169,97,.2); color: var(--gold);
    border: 1px solid rgba(201,169,97,.4); padding: .4rem 1.4rem;
    border-radius: 50px; font-size: .8rem; letter-spacing: 2px; text-transform: uppercase;
    margin-bottom: 1.5rem;
}
.hero-reservation h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem,5vw,3.2rem); font-weight: 700; margin-bottom: 1rem;
}
.hero-reservation h1 em { color: var(--gold); font-style: italic; }
.hero-reservation .lead { color: rgba(255,255,255,.8); font-size: 1.05rem; }

/* Cards */
.card { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,.08); margin-bottom: 1.5rem; }
.card-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: white; border-radius: 12px 12px 0 0 !important; padding: 1rem 1.5rem; border: none;
}
.card-header h4 { margin: 0; font-size: 1.1rem; font-weight: 700; font-family: 'Playfair Display', serif; }
.card-body { padding: 1.5rem; }

/* ══════════════════════════════
   CHAMPS + VALIDATION TEMPS RÉEL
══════════════════════════════ */
.form-label {
    font-weight: 600; color: #333; margin-bottom: .5rem;
    display: flex; align-items: center; gap: .35rem;
}
.label-icon { font-size: .8rem; color: var(--gold); }

.form-control,
.form-select {
    border: 2px solid #e0e0e0; border-radius: 8px;
    padding: .75rem 1rem; transition: border-color .25s, box-shadow .25s;
    font-size: .95rem;
}
.form-control:focus,
.form-select:focus {
    border-color: var(--gold) !important;
    box-shadow: 0 0 0 3px rgba(201,169,97,.15) !important;
    outline: none !important;
}

/* Valide */
.form-control.is-valid,
.form-select.is-valid {
    border-color: var(--primary) !important;
    background-color: #fafffa;
    padding-right: 2.5rem;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%231A472A' d='M2.3 6.73.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
    background-repeat: no-repeat; background-position: right 1rem center; background-size: 1rem;
}

/* Invalide */
.form-control.is-invalid,
.form-select.is-invalid {
    border-color: var(--error) !important;
    background-color: #fff9f9;
    padding-right: 2.5rem;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23e53935'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23e53935' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat; background-position: right 1rem center; background-size: 1rem;
    box-shadow: 0 0 0 3px rgba(229,57,53,.1) !important;
}

/* Message d'erreur animé */
.field-error {
    display: none;
    color: var(--error); font-size: .8rem; font-weight: 500;
    margin-top: .4rem; padding: .35rem .7rem;
    background: var(--error-light); border-radius: 6px;
    border-left: 3px solid var(--error);
    animation: slideDown .2s ease;
}
.field-error.show { display: flex; align-items: center; gap: .4rem; }
@keyframes slideDown {
    from { opacity: 0; transform: translateY(-5px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Input avec icône */
.input-wrapper { position: relative; }
.input-wrapper .input-icon {
    position: absolute; left: .9rem; top: 50%; transform: translateY(-50%);
    color: #bbb; font-size: .85rem; pointer-events: none; transition: color .2s;
    z-index: 2;
}
.input-wrapper .form-control { padding-left: 2.4rem; }
.input-wrapper .form-control:focus ~ .input-icon,
.input-wrapper .form-control.is-valid ~ .input-icon { color: var(--gold); }
.input-wrapper .form-control.is-invalid ~ .input-icon { color: var(--error); }

.field-hint { font-size: .76rem; color: #999; margin-top: .25rem; }

/* Compteur caractères */
.char-counter { font-size: .75rem; color: #aaa; text-align: right; margin-top: .2rem; }
.char-counter.warn   { color: #FF9800; }
.char-counter.danger { color: var(--error); }

/* ══════════════════════════════
   BARRE DE PROGRESSION
══════════════════════════════ */
.form-progress { margin-bottom: 2rem; }
.progress-steps {
    display: flex; justify-content: space-between; position: relative;
}
.progress-steps::before {
    content: ''; position: absolute; top: 16px; left: 0; right: 0;
    height: 2px; background: #e0e0e0; z-index: 0;
}
.progress-fill {
    position: absolute; top: 16px; left: 0; height: 2px;
    background: linear-gradient(90deg, var(--primary), var(--gold)); z-index: 1; transition: width .4s ease; width: 0%;
}
.step { display: flex; flex-direction: column; align-items: center; gap: .4rem; position: relative; z-index: 2; }
.step-circle {
    width: 32px; height: 32px; border-radius: 50%; background: #e0e0e0; color: #999;
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem; font-weight: 700; transition: all .3s;
}
.step.active .step-circle { background: var(--gold); color: var(--primary-dark); }
.step.done   .step-circle { background: var(--primary); color: white; }
.step-label { font-size: .7rem; color: #999; font-weight: 600; white-space: nowrap; }
.step.active .step-label,
.step.done   .step-label  { color: var(--primary); }

/* Room cards */
.room-card {
    border: 2px solid #e9ecef; border-radius: 8px;
    padding: 1rem; cursor: pointer; transition: all .25s;
    background: white; margin-bottom: .75rem;
}
.room-card:hover { border-color: var(--gold); transform: translateX(4px); box-shadow: 0 4px 12px rgba(201,169,97,.2); }
.room-card.selected { border-color: var(--gold); background: rgba(201,169,97,.06); box-shadow: 0 0 0 3px rgba(201,169,97,.25); }
.room-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: .5rem; }
.room-name { font-size: 1.05rem; font-weight: 700; color: var(--primary); font-family: 'Playfair Display', serif; }
.room-number { font-size: .83rem; color: #777; }
.room-meta { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
.room-price { font-size: 1.3rem; font-weight: 800; color: var(--gold); }
.room-price-unit { font-size: .78rem; color: #999; }
.badge-room-capacity { background: var(--primary); color: white; padding: .3rem .7rem; border-radius: 20px; font-size: .75rem; }
.badge-room-total { background: var(--gold); color: var(--primary-dark); padding: .3rem .7rem; border-radius: 20px; font-size: .75rem; font-weight: 700; }

#roomsList::-webkit-scrollbar { width: 8px; }
#roomsList::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
#roomsList::-webkit-scrollbar-thumb { background: var(--gold); border-radius: 10px; }

/* Summary */
.summary-box {
    background: rgba(201,169,97,.07); border-left: 4px solid var(--gold);
    border-radius: 8px; padding: 1.25rem;
}
.summary-row { display: flex; justify-content: space-between; margin-bottom: .75rem; }
.summary-row:last-child { margin-bottom: 0; }
.summary-total { font-size: 1.5rem; font-weight: 800; color: var(--gold); font-family: 'Playfair Display', serif; }

/* Buttons */
.btn { border-radius: 8px; padding: .75rem 1.5rem; font-weight: 600; transition: all .25s; }
.btn:focus, .btn:focus-visible { outline: none !important; box-shadow: none !important; }
.btn-primary {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-color: var(--primary); color: white;
}
.btn-primary:hover {
    background: linear-gradient(135deg, var(--gold) 0%, #b8924f 100%);
    border-color: var(--gold); color: var(--primary-dark); transform: translateY(-1px);
}
.btn-primary:disabled { background: #ccc; border-color: #ccc; transform: none; color: white; }
.btn-lg { padding: 1rem 2rem; font-size: 1.05rem; letter-spacing: .5px; }

/* Features sidebar */
.feature-item { display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1rem; }
.feature-icon {
    flex-shrink: 0; width: 40px; height: 40px;
    background: rgba(201,169,97,.12); border-radius: 50%;
    display: flex; align-items: center; justify-content: center; color: var(--gold);
}
.feature-content h6 { margin-bottom: .25rem; font-weight: 600; }
.feature-content small { color: #666; }

.loading-state, .empty-state { text-align: center; padding: 3rem 1rem; }
.empty-state i { font-size: 3rem; color: #ccc; margin-bottom: 1rem; display: block; }
.spinner-border { width: 3rem; height: 3rem; }

.sticky-sidebar { position: sticky; top: 20px; }

@media (max-width: 768px) {
    .hero-reservation { padding: 60px 0 40px; }
    .hero-reservation h1 { font-size: 1.75rem; }
    .card-body { padding: 1rem; }
    .summary-total { font-size: 1.2rem; }
    .progress-steps::before { display: none; }
    .step-label { font-size: .6rem; }
}
</style>
@endpush

@section('content')

<section class="hero-reservation">
    <div class="container">
        <div class="eyebrow-badge"><i class="fas fa-star me-2"></i>Hôtel Cactus Palace 5★</div>
        <h1>Réservez votre <em>séjour</em></h1>
        <p class="lead">Choisissez votre chambre et confirmez en quelques clics</p>
    </div>
</section>

<section class="py-5">
    <div class="container">

        {{-- Barre de progression --}}
        <div class="form-progress">
            <div class="progress-steps">
                <div class="progress-fill" id="progressFill"></div>
                <div class="step active" id="step1">
                    <div class="step-circle">1</div>
                    <span class="step-label">Infos</span>
                </div>
                <div class="step" id="step2">
                    <div class="step-circle">2</div>
                    <span class="step-label">Dates</span>
                </div>
                <div class="step" id="step3">
                    <div class="step-circle">3</div>
                    <span class="step-label">Chambre</span>
                </div>
                <div class="step" id="step4">
                    <div class="step-circle"><i class="fas fa-check" style="font-size:.6rem"></i></div>
                    <span class="step-label">Confirmation</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <form id="reservationForm" action="{{ route('frontend.reservation.request') }}" method="POST" novalidate>
                    @csrf

                    {{-- SECTION 1 — Infos personnelles --}}
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-user me-2"></i>Vos informations</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label" for="f_name">
                                        <i class="label-icon fas fa-user"></i> Nom complet <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-user input-icon"></i>
                                        <input type="text" class="form-control" id="f_name" name="name"
                                               placeholder="Jean Dupont" autocomplete="name" required>
                                    </div>
                                    <div class="field-error" id="f_name-err"><i class="fas fa-exclamation-circle"></i><span></span></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="f_email">
                                        <i class="label-icon fas fa-envelope"></i> Email <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-envelope input-icon"></i>
                                        <input type="email" class="form-control" id="f_email" name="email"
                                               placeholder="jean@exemple.com" autocomplete="email" required>
                                    </div>
                                    <div class="field-error" id="f_email-err"><i class="fas fa-exclamation-circle"></i><span></span></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="f_phone">
                                        <i class="label-icon fas fa-phone"></i> Téléphone <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-phone input-icon"></i>
                                        <input type="tel" class="form-control" id="f_phone" name="phone"
                                               placeholder="+229 01 23 45 67" autocomplete="tel" required>
                                    </div>
                                    <div class="field-hint">Ex : +229 01 23 45 67 ou 01 23 45 67</div>
                                    <div class="field-error" id="f_phone-err"><i class="fas fa-exclamation-circle"></i><span></span></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="f_address">
                                        <i class="label-icon fas fa-map-marker-alt"></i> Adresse <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-map-marker-alt input-icon"></i>
                                        <input type="text" class="form-control" id="f_address" name="address"
                                               placeholder="Ville / Quartier" autocomplete="street-address" required>
                                    </div>
                                    <div class="field-error" id="f_address-err"><i class="fas fa-exclamation-circle"></i><span></span></div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="f_gender">
                                        <i class="label-icon fas fa-venus-mars"></i> Genre <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="f_gender" name="gender" required>
                                        <option value="">Sélectionnez</option>
                                        <option value="Homme">Homme</option>
                                        <option value="Femme">Femme</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                    <div class="field-error" id="f_gender-err"><i class="fas fa-exclamation-circle"></i><span></span></div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="f_job">
                                        <i class="label-icon fas fa-briefcase"></i> Profession <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-briefcase input-icon"></i>
                                        <input type="text" class="form-control" id="f_job" name="job"
                                               placeholder="Votre métier" required>
                                    </div>
                                    <div class="field-error" id="f_job-err"><i class="fas fa-exclamation-circle"></i><span></span></div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="f_birthdate">
                                        <i class="label-icon fas fa-birthday-cake"></i> Date de naissance <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="f_birthdate" name="birthdate"
                                           required
                                           max="{{ date('Y-m-d', strtotime('-18 years')) }}"
                                           value="{{ date('Y-m-d', strtotime('-30 years')) }}">
                                    <div class="field-hint">Minimum 18 ans requis</div>
                                    <div class="field-error" id="f_birthdate-err"><i class="fas fa-exclamation-circle"></i><span></span></div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2 — Dates --}}
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-calendar me-2"></i>Dates du séjour</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="check_in">Arrivée <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="check_in" name="check_in" required>
                                    <div class="field-error" id="check_in-err"><i class="fas fa-exclamation-circle"></i><span></span></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="check_out">Départ <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="check_out" name="check_out" required>
                                    <div class="field-error" id="check_out-err"><i class="fas fa-exclamation-circle"></i><span></span></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="adults">Personnes</label>
                                    <select class="form-select" id="adults" name="adults">
                                        @for($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}" {{ $i == 2 ? 'selected' : '' }}>
                                                {{ $i }} personne{{ $i > 1 ? 's' : '' }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div id="stayInfo" class="alert alert-success mt-3 mb-0 small" style="display:none">
                                <i class="fas fa-moon me-2"></i><span id="stayInfoText"></span>
                            </div>

                            <div class="mt-3">
                                <button type="button" class="btn btn-primary w-100" id="checkAvailability">
                                    <i class="fas fa-search me-2"></i>Rechercher les chambres disponibles
                                </button>
                                <div class="field-error mt-2" id="dates-err"><i class="fas fa-exclamation-circle"></i><span></span></div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3 — Chambre --}}
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-bed me-2"></i>Choisissez votre chambre</h4>
                        </div>
                        <div class="card-body">
                            <div id="roomFilters" style="display:none">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <select class="form-select form-select-sm" id="typeFilter">
                                            <option value="">Tous les types</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-select form-select-sm" id="priceFilter">
                                            <option value="">Tous les prix</option>
                                            <option value="50000">≤ 50 000 FCFA</option>
                                            <option value="100000">≤ 100 000 FCFA</option>
                                            <option value="150000">≤ 150 000 FCFA</option>
                                            <option value="200000">≤ 200 000 FCFA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="alert alert-info small mb-3">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <span id="roomCount">0 chambre(s) disponible(s)</span>
                                </div>
                            </div>

                            <div id="roomsList" style="max-height:400px; overflow-y:auto;">
                                <div class="empty-state">
                                    <i class="fas fa-search"></i>
                                    <p class="text-muted">Cliquez sur "Rechercher" pour voir les chambres disponibles</p>
                                </div>
                            </div>
                            <input type="hidden" name="room_id" id="selected_room_id">
                            <div class="field-error mt-2" id="room-err"><i class="fas fa-exclamation-circle"></i><span></span></div>
                        </div>
                    </div>

                    {{-- SECTION 4 — Notes + Récap + Submit --}}
                    <div class="card">
                        <div class="card-body">
                            <label class="form-label" for="notes">
                                <i class="label-icon fas fa-comment-alt"></i>
                                Demandes spéciales <small class="text-muted fw-normal">(optionnel)</small>
                            </label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" maxlength="500"
                                      placeholder="Lit bébé, étage préféré, heure d'arrivée..."></textarea>
                            <div class="char-counter"><span id="notesCount">0</span> / 500 caractères</div>

                            <div id="summary" class="summary-box mt-4" style="display:none">
                                <h6 class="fw-bold mb-3"><i class="fas fa-receipt me-2" style="color:var(--primary)"></i>Récapitulatif</h6>
                                <div class="summary-row"><span>Chambre :</span><strong id="sumRoom">-</strong></div>
                                <div class="summary-row"><span>Dates :</span><strong id="sumDates">-</strong></div>
                                <div class="summary-row"><span>Nuits :</span><strong id="sumNights">0</strong></div>
                                <div class="summary-row"><span>Prix/nuit :</span><strong id="sumPrice">-</strong></div>
                                <hr>
                                <div class="summary-row">
                                    <span class="fw-bold">Total :</span>
                                    <span class="summary-total" id="sumTotal">0 FCFA</span>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                                    <i class="fas fa-check-circle me-2"></i>Confirmer ma réservation
                                </button>
                            </div>
                            <p class="text-center text-muted small mt-3">
                                <i class="fas fa-lock me-1"></i>Réservation 100% sécurisée
                            </p>
                        </div>
                    </div>

                </form>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="card sticky-sidebar">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">
                            <i class="fas fa-info-circle me-2" style="color:var(--primary)"></i>
                            Pourquoi réserver chez nous ?
                        </h5>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-check"></i></div>
                            <div class="feature-content"><h6>Meilleur tarif garanti</h6><small>Prix les plus bas du marché</small></div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-undo"></i></div>
                            <div class="feature-content"><h6>Annulation gratuite</h6><small>Jusqu'à 48h avant l'arrivée</small></div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-ban"></i></div>
                            <div class="feature-content"><h6>Sans frais cachés</h6><small>Prix transparent</small></div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-headset"></i></div>
                            <div class="feature-content"><h6>Support 24/7</h6><small>Équipe disponible</small></div>
                        </div>
                        <hr class="my-4">
                        <h6 class="fw-bold mb-3">Besoin d'aide ?</h6>
                        <div class="d-grid gap-2">
                            <a href="https://wa.me/229XXXXX" target="_blank" class="btn btn-primary">
                                <i class="fab fa-whatsapp me-2"></i>WhatsApp
                            </a>
                            <a href="tel:+229XXXXX" class="btn" style="border:2px solid var(--primary);color:var(--primary);font-weight:600;">
                                <i class="fas fa-phone me-2"></i>Appeler
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Modal succès --}}
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#1A472A,#0F2918);color:white;border:none">
                <h5 class="modal-title" style="font-family:'Playfair Display',serif;"><i class="fas fa-check-circle me-2" style="color:#C9A961"></i>Réservation confirmée !</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="fas fa-check-circle fa-4x mb-3" style="color:#C9A961"></i>
                <h4>Merci pour votre réservation !</h4>
                <p id="modalMessage" class="text-muted"></p>
                <div id="modalDetails" class="alert alert-success text-start" style="display:none">
                    <p class="mb-1"><strong>Réf :</strong> <span id="refNumber"></span></p>
                    <p class="mb-1"><strong>Client :</strong> <span id="modalName"></span></p>
                    <p class="mb-1"><strong>Arrivée :</strong> <span id="modalCheckIn"></span></p>
                    <p class="mb-1"><strong>Départ :</strong> <span id="modalCheckOut"></span></p>
                    <p class="mb-0"><strong>Total :</strong> <span id="modalTotal"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ══════════════════════════════════════════
       RÈGLES DE VALIDATION
    ══════════════════════════════════════════ */
    const RULES = {
        f_name:      { required: true, minLen: 3, maxLen: 80,
                       pattern: /^[a-zA-ZÀ-ÿ\s\-'\.]+$/,
                       msgs: { required: 'Le nom complet est obligatoire.', minLen: 'Minimum 3 caractères requis.', maxLen: 'Maximum 80 caractères.', pattern: 'Le nom ne peut pas contenir de chiffres ou caractères spéciaux.' } },
        f_email:     { required: true, pattern: /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/,
                       msgs: { required: 'L\'adresse email est obligatoire.', pattern: 'Email invalide (ex: jean@exemple.com).' } },
        f_phone:     { required: true, pattern: /^(\+?229\s?)?\d{2}(\s?\d{2}){3,4}$/,
                       msgs: { required: 'Le téléphone est obligatoire.', pattern: 'Format invalide. Ex : +229 01 23 45 67' } },
        f_address:   { required: true, minLen: 3,
                       msgs: { required: 'L\'adresse est obligatoire.', minLen: 'Minimum 3 caractères.' } },
        f_gender:    { required: true,
                       msgs: { required: 'Veuillez sélectionner votre genre.' } },
        f_job:       { required: true, minLen: 2,
                       msgs: { required: 'La profession est obligatoire.', minLen: 'Minimum 2 caractères.' } },
        f_birthdate: { required: true, minAge: 18,
                       msgs: { required: 'La date de naissance est obligatoire.', minAge: 'Vous devez avoir au moins 18 ans.' } },
        check_in:    { required: true, msgs: { required: 'La date d\'arrivée est obligatoire.' } },
        check_out:   { required: true, msgs: { required: 'La date de départ est obligatoire.' } },
    };

    /* ── helpers DOM ── */
    const $ = id => document.getElementById(id);

    function setValid(fieldId) {
        const el = $(fieldId); const err = $(fieldId + '-err');
        if (el)  { el.classList.remove('is-invalid'); el.classList.add('is-valid'); }
        if (err) err.classList.remove('show');
    }

    function setInvalid(fieldId, msg) {
        const el = $(fieldId); const err = $(fieldId + '-err');
        if (el)  { el.classList.remove('is-valid'); el.classList.add('is-invalid'); }
        if (err) { err.querySelector('span').textContent = msg; err.classList.add('show'); }
    }

    function clearField(fieldId) {
        const el = $(fieldId); const err = $(fieldId + '-err');
        if (el)  el.classList.remove('is-valid', 'is-invalid');
        if (err) err.classList.remove('show');
    }

    /* ── Valider un champ ── */
    function validate(fieldId) {
        const el = $(fieldId);
        if (!el) return true;
        const val  = el.value.trim();
        const rule = RULES[fieldId];
        if (!rule) return true;

        if (rule.required && !val)    { setInvalid(fieldId, rule.msgs.required); return false; }
        if (!val)                      { clearField(fieldId); return true; }
        if (rule.minLen && val.length < rule.minLen) { setInvalid(fieldId, rule.msgs.minLen); return false; }
        if (rule.maxLen && val.length > rule.maxLen) { setInvalid(fieldId, rule.msgs.maxLen); return false; }
        if (rule.pattern && !rule.pattern.test(val)) { setInvalid(fieldId, rule.msgs.pattern); return false; }
        if (rule.minAge) {
            const birth = new Date(val);
            const limit = new Date();
            limit.setFullYear(limit.getFullYear() - rule.minAge);
            if (birth > limit) { setInvalid(fieldId, rule.msgs.minAge); return false; }
        }
        setValid(fieldId);
        return true;
    }

    /* ── Attacher les listeners temps réel ── */
    Object.keys(RULES).forEach(id => {
        const el = $(id);
        if (!el) return;
        const ev = (el.tagName === 'SELECT' || el.type === 'date') ? 'change' : 'input';
        el.addEventListener(ev,   () => validate(id));
        el.addEventListener('blur', () => { if ($(id).value.trim()) validate(id); });
    });

    /* Bloquer chiffres dans le champ nom */
    const nameEl = $("f_name");
    if (nameEl) {
        nameEl.addEventListener("keypress", function (e) {
            if (/[0-9]/.test(e.key)) e.preventDefault();
        });
        nameEl.addEventListener("input", function () {
            const cleaned = this.value.replace(/[0-9]/g, "");
            if (cleaned !== this.value) {
                const pos = this.selectionStart - (this.value.length - cleaned.length);
                this.value = cleaned;
                this.setSelectionRange(Math.max(0,pos), Math.max(0,pos));
            }
        });
    }

    /* Compteur notes */
    const notesEl = $('notes'), notesCount = $('notesCount');
    if (notesEl) {
        notesEl.addEventListener('input', function () {
            const n = this.value.length;
            notesCount.textContent = n;
            const cc = notesCount.closest('.char-counter');
            cc.classList.remove('warn','danger');
            if (n > 450) cc.classList.add('danger');
            else if (n > 350) cc.classList.add('warn');
        });
    }

    /* ══════════════════════════════════════════
       DATES
    ══════════════════════════════════════════ */
    const checkIn  = $('check_in');
    const checkOut = $('check_out');

    function pad(n) { return String(n).padStart(2,'0'); }
    function fmtDate(str) {
        if (!str) return '';
        const [y,m,d] = str.split('-');
        return `${d}/${m}/${y}`;
    }

    const tom = new Date(); tom.setDate(tom.getDate()+1);
    const da2 = new Date(); da2.setDate(da2.getDate()+2);
    const fmt = d => `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;

    checkIn.value  = fmt(tom); checkIn.min  = fmt(tom);
    checkOut.value = fmt(da2); checkOut.min = fmt(da2);

    function refreshStayInfo() {
        const stayInfo = $('stayInfo');
        if (!checkIn.value || !checkOut.value) { stayInfo.style.display='none'; return; }
        const nights = Math.ceil((new Date(checkOut.value) - new Date(checkIn.value)) / 86400000);
        if (nights > 0) {
            $('stayInfoText').textContent = `Séjour de ${nights} nuit${nights>1?'s':''} — du ${fmtDate(checkIn.value)} au ${fmtDate(checkOut.value)}`;
            stayInfo.style.display = 'block';
        } else {
            stayInfo.style.display = 'none';
        }
        refreshProgress();
    }

    checkIn.addEventListener('change', function () {
        const next = new Date(this.value); next.setDate(next.getDate()+1);
        checkOut.min = fmt(next);
        if (checkOut.value <= this.value) checkOut.value = fmt(next);
        validate('check_in'); validate('check_out');
        refreshStayInfo();
    });

    checkOut.addEventListener('change', function () {
        if (this.value <= checkIn.value) {
            setInvalid('check_out', 'La date de départ doit être postérieure à l\'arrivée.');
        } else {
            setValid('check_out');
            refreshStayInfo();
        }
    });

    refreshStayInfo();

    /* ══════════════════════════════════════════
       PROGRESSION
    ══════════════════════════════════════════ */
    function refreshProgress() {
        const infoIds   = ['f_name','f_email','f_phone','f_address','f_gender','f_job','f_birthdate'];
        const infoOk    = infoIds.every(id => { const el=$(id); return el && el.classList.contains('is-valid'); });
        const datesOk   = checkIn.value && checkOut.value && checkOut.value > checkIn.value;
        const roomOk    = !!$('selected_room_id').value;

        const steps = ['step1','step2','step3','step4'].map(s => $(s));
        steps.forEach(s => s.classList.remove('active','done'));

        let pct = 0;
        if (infoOk)                          { steps[0].classList.add('done'); pct = 33; } else { steps[0].classList.add('active'); }
        if (infoOk && datesOk)               { steps[1].classList.add('done'); pct = 66; } else if (infoOk) { steps[1].classList.add('active'); }
        if (infoOk && datesOk && roomOk)     { steps[2].classList.add('done'); steps[3].classList.add('active'); pct = 100; }
        else if (infoOk && datesOk)          { steps[2].classList.add('active'); }

        $('progressFill').style.width = pct + '%';
    }

    document.querySelectorAll('#reservationForm input, #reservationForm select, #reservationForm textarea').forEach(el => {
        el.addEventListener('change', refreshProgress);
        el.addEventListener('input',  refreshProgress);
    });

    /* ══════════════════════════════════════════
       RECHERCHE CHAMBRES
    ══════════════════════════════════════════ */
    const checkBtn    = $('checkAvailability');
    const roomsList   = $('roomsList');
    const roomFilters = $('roomFilters');
    const typeFilter  = $('typeFilter');
    const priceFilter = $('priceFilter');
    const summary     = $('summary');
    const submitBtn   = $('submitBtn');

    let allRooms = [], selectedRoom = null;

    const urlParams         = new URLSearchParams(window.location.search);
    const preSelectedRoomId = urlParams.get('room_id');
    if (preSelectedRoomId) checkBtn.click();

    checkBtn.addEventListener('click', async function () {
        if (!checkIn.value)  { setInvalid('check_in',  'Veuillez sélectionner la date d\'arrivée.'); return; }
        if (!checkOut.value) { setInvalid('check_out', 'Veuillez sélectionner la date de départ.'); return; }
        if (checkOut.value <= checkIn.value) { setInvalid('check_out', 'La date de départ doit être après l\'arrivée.'); return; }

        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Recherche...';
        roomsList.innerHTML = '<div class="loading-state"><div class="spinner-border" style="color:#1A472A"></div><p class="mt-2 text-muted">Recherche des chambres...</p></div>';

        try {
            const p = new URLSearchParams({ check_in: checkIn.value, check_out: checkOut.value, adults: $('adults').value });
            const res = await fetch('/api/available-rooms?' + p);
            const data = await res.json();

            if (data.rooms && data.rooms.length > 0) {
                allRooms = data.rooms;
                const types = [...new Set(data.rooms.map(r => r.type_name))];
                typeFilter.innerHTML = '<option value="">Tous les types</option>' + types.map(t => `<option value="${t}">${t}</option>`).join('');
                roomFilters.style.display = 'block';
                displayRooms(data.rooms);
                if (preSelectedRoomId) {
                    setTimeout(() => {
                        const card = document.querySelector(`[data-id="${preSelectedRoomId}"]`);
                        if (card) { card.click(); card.scrollIntoView({ behavior:'smooth', block:'center' }); }
                    }, 250);
                }
            } else {
                roomFilters.style.display = 'none';
                roomsList.innerHTML = `<div class="empty-state"><i class="fas fa-bed text-warning"></i><h5>Aucune chambre disponible</h5><p class="text-muted">Essayez d'autres dates.</p></div>`;
            }
        } catch {
            roomFilters.style.display = 'none';
            roomsList.innerHTML = `<div class="empty-state"><i class="fas fa-exclamation-triangle text-danger"></i><h5>Erreur de connexion</h5><p class="text-muted">Vérifiez votre réseau et réessayez.</p></div>`;
        }

        this.disabled = false;
        this.innerHTML = '<i class="fas fa-search me-2"></i>Rechercher les chambres disponibles';
    });

    function displayRooms(rooms) {
        const nights = Math.ceil((new Date(checkOut.value) - new Date(checkIn.value)) / 86400000);
        $('roomCount').textContent = `${rooms.length} chambre(s) disponible(s)`;

        if (!rooms.length) {
            roomsList.innerHTML = `<div class="empty-state"><i class="fas fa-filter"></i><p class="text-muted">Aucune chambre pour ces filtres.</p></div>`;
            return;
        }

        roomsList.innerHTML = rooms.map(room => {
            const total = room.price * nights;
            const sel   = preSelectedRoomId == room.id ? 'selected' : '';
            return `
            <div class="room-card ${sel}"
                 data-id="${room.id}" data-price="${room.price}"
                 data-name="${room.name}" data-number="${room.number}">
                <div class="room-card-header">
                    <div>
                        <div class="room-name">${room.name}</div>
                        <div class="room-number">Chambre ${room.number} · ${room.type_name || 'Standard'}</div>
                    </div>
                    <span class="badge-room-capacity"><i class="fas fa-users me-1"></i>${room.capacity} pers.</span>
                </div>
                <div class="room-meta">
                    <div>
                        <span class="room-price">${room.price.toLocaleString()}</span>
                        <span class="room-price-unit"> FCFA/nuit</span>
                    </div>
                    <div class="ms-auto">
                        <span class="badge-room-total">${total.toLocaleString()} FCFA total</span>
                    </div>
                </div>
            </div>`;
        }).join('');

        document.querySelectorAll('.room-card').forEach(card => {
            card.addEventListener('click', function () {
                document.querySelectorAll('.room-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                selectedRoom = { id: this.dataset.id, price: +this.dataset.price, name: this.dataset.name, number: this.dataset.number };
                $('selected_room_id').value = selectedRoom.id;
                const re = $('room-err'); if (re) re.classList.remove('show');
                updateSummary();
                refreshProgress();
            });
        });

        // Auto-select
        if (preSelectedRoomId) {
            const pc = document.querySelector(`[data-id="${preSelectedRoomId}"]`);
            if (pc) {
                selectedRoom = { id: pc.dataset.id, price: +pc.dataset.price, name: pc.dataset.name, number: pc.dataset.number };
                $('selected_room_id').value = selectedRoom.id;
                updateSummary(); refreshProgress();
            }
        }
    }

    typeFilter.addEventListener('change',  filterRooms);
    priceFilter.addEventListener('change', filterRooms);
    function filterRooms() {
        const tv = typeFilter.value, pv = priceFilter.value ? +priceFilter.value : null;
        displayRooms(allRooms.filter(r => (!tv || r.type_name === tv) && (!pv || r.price <= pv)));
    }

    function updateSummary() {
        const nights = Math.ceil((new Date(checkOut.value) - new Date(checkIn.value)) / 86400000);
        const total  = selectedRoom.price * nights;
        $('sumRoom').textContent   = `${selectedRoom.name} (Chambre ${selectedRoom.number})`;
        $('sumDates').textContent  = `${fmtDate(checkIn.value)} → ${fmtDate(checkOut.value)}`;
        $('sumNights').textContent = nights;
        $('sumPrice').textContent  = selectedRoom.price.toLocaleString() + ' FCFA';
        $('sumTotal').textContent  = total.toLocaleString() + ' FCFA';
        summary.style.display = 'block';
        submitBtn.disabled = false;
        summary.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    /* ══════════════════════════════════════════
       SOUMISSION
    ══════════════════════════════════════════ */
    $('reservationForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const requiredFields = ['f_name','f_email','f_phone','f_address','f_gender','f_job','f_birthdate','check_in','check_out'];
        let ok = true;
        requiredFields.forEach(id => { if (!validate(id)) ok = false; });

        if (!selectedRoom) {
            const re = $('room-err');
            re.querySelector('span').textContent = 'Veuillez sélectionner une chambre avant de confirmer.';
            re.classList.add('show');
            ok = false;
            $('roomsList').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        if (!ok) {
            const first = document.querySelector('.is-invalid');
            if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement en cours...';

        const fd = new FormData(this);
        try {
            const res  = await fetch(this.action, { method:'POST', body: fd, headers: { 'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value, 'Accept':'application/json' } });
            const data = await res.json();

            if (data.success) {
                $('modalMessage').textContent = data.message || 'Votre réservation a été enregistrée.';
                if (data.transaction) {
                    $('modalDetails').style.display = 'block';
                    $('refNumber').textContent    = 'RES-' + String(data.transaction.id).padStart(6,'0');
                    $('modalName').textContent    = fd.get('name');
                    $('modalCheckIn').textContent = fmtDate(checkIn.value);
                    $('modalCheckOut').textContent= fmtDate(checkOut.value);
                    $('modalTotal').textContent   = $('sumTotal').textContent;
                }
                new bootstrap.Modal($('successModal')).show();

                // Reset
                this.reset();
                summary.style.display = 'none'; roomFilters.style.display = 'none';
                $('stayInfo').style.display = 'none';
                roomsList.innerHTML = '<div class="empty-state"><i class="fas fa-search"></i><p class="text-muted">Recherchez des chambres</p></div>';
                submitBtn.disabled = true; selectedRoom = null; allRooms = [];
                $('notesCount').textContent = '0';
                document.querySelectorAll('.form-control,.form-select').forEach(el => el.classList.remove('is-valid','is-invalid'));
                window.history.replaceState({}, document.title, window.location.pathname);
                refreshProgress();

            } else {
                if (data.errors) {
                    // Mapper les noms serveur → IDs locaux
                    const map = { name:'f_name', email:'f_email', phone:'f_phone', address:'f_address', gender:'f_gender', job:'f_job', birthdate:'f_birthdate' };
                    Object.keys(data.errors).forEach(k => {
                        const localId = map[k] || k;
                        setInvalid(localId, data.errors[k][0]);
                    });
                    const first = document.querySelector('.is-invalid');
                    if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    alert('Erreur : ' + (data.message || 'Veuillez réessayer.'));
                }
            }
        } catch {
            alert('Erreur de connexion. Vérifiez votre réseau et réessayez.');
        }

        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i>Confirmer ma réservation';
    });

    refreshProgress();
});
</script>
@endpush