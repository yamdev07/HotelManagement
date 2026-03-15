

<?php $__env->startSection('title', 'Confirmation de Réservation'); ?>

<?php $__env->startSection('content'); ?>

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

.confirm-page {
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
.confirm-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .8rem; color: var(--s400);
    margin-bottom: 20px;
}
.confirm-breadcrumb a {
    color: var(--s400); text-decoration: none;
    transition: var(--transition);
}
.confirm-breadcrumb a:hover { color: var(--g600); }
.confirm-breadcrumb .sep { color: var(--s300); }
.confirm-breadcrumb .current { color: var(--s600); font-weight: 500; }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.confirm-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.confirm-brand { display: flex; align-items: center; gap: 14px; }
.confirm-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.confirm-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.confirm-header-title em { font-style: normal; color: var(--g600); }
.confirm-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.confirm-header-sub i { color: var(--g500); }
.confirm-header-actions { display: flex; align-items: center; gap: 10px; }

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
   AGENT CARD
══════════════════════════════════════════════ */
.agent-card {
    background: linear-gradient(135deg, var(--g600), var(--g500));
    border-radius: var(--rl); padding: 16px 20px;
    margin-bottom: 24px; box-shadow: var(--shadow-md);
}
.agent-avatar {
    width: 50px; height: 50px; border-radius: 50%;
    border: 3px solid white; object-fit: cover;
}
.agent-name {
    font-size: .9rem; font-weight: 600; color: white;
}
.agent-email {
    font-size: .7rem; color: rgba(255,255,255,0.8);
}
.agent-badge {
    background: rgba(255,255,255,0.2); color: white;
    padding: 3px 10px; border-radius: 30px; font-size: .65rem;
    font-weight: 600; border: 1px solid rgba(255,255,255,0.3);
}

/* ══════════════════════════════════════════════
   CARTES PRINCIPALES
══════════════════════════════════════════════ */
.confirm-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 20px; box-shadow: var(--shadow-sm);
}
.confirm-card-header {
    padding: 18px 24px;
    border-bottom: 1.5px solid var(--s100);
    background: linear-gradient(135deg, var(--g700), var(--g500));
    color: white;
}
.confirm-card-title {
    display: flex; align-items: center; gap: 10px;
    font-size: 1rem; font-weight: 600; color: white; margin: 0;
}
.confirm-card-title i { color: white; }
.confirm-card-body { padding: 28px; }
.confirm-card-footer {
    padding: 16px 24px; border-top: 1.5px solid var(--s100);
    background: var(--surface);
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
.alert-db-info {
    background: var(--g50); border-color: var(--g200);
    color: var(--g600);
}
.alert-db-warning {
    background: #fff3cd; border-color: #ffeeba;
    color: #856404;
}

/* ══════════════════════════════════════════════
   SUMMARY CARD
══════════════════════════════════════════════ */
.summary-card {
    background: var(--surface); border: 1.5px solid var(--s100);
    border-radius: var(--rl); padding: 20px; height: 100%;
}
.summary-title {
    font-size: .9rem; font-weight: 600; color: var(--s800);
    margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
}
.summary-title i { color: var(--g500); }

.room-info {
    display: flex; align-items: center; gap: 16px; margin-bottom: 16px;
}
.room-icon {
    width: 48px; height: 48px; border-radius: var(--rl);
    background: var(--g50); color: var(--g600);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
}
.room-details h6 {
    font-size: .9rem; font-weight: 600; color: var(--s800);
    margin-bottom: 4px;
}
.room-details p {
    font-size: .75rem; color: var(--s400); margin-bottom: 4px;
}

.timeline {
    position: relative; padding-left: 24px;
}
.timeline-item {
    position: relative; padding-bottom: 20px;
}
.timeline-marker {
    position: absolute; left: -30px; width: 16px; height: 16px;
    border-radius: 50%; background: var(--g500);
    border: 3px solid var(--g200); top: 3px;
}
.timeline-content {
    padding-left: 16px;
}
.timeline-content h6 {
    font-size: .8rem; font-weight: 600; color: var(--s700);
    margin-bottom: 4px;
}
.timeline-content p {
    font-size: .75rem; color: var(--s600);
}

/* ══════════════════════════════════════════════
   PRICE HIGHLIGHT
══════════════════════════════════════════════ */
.price-highlight {
    background: var(--g50); border-left: 4px solid var(--g600);
    border-radius: var(--r); padding: 16px; text-align: center;
}
.price-highlight .price-label {
    font-size: .7rem; color: var(--s400); text-transform: uppercase;
}
.price-highlight .price-value {
    font-size: 1.6rem; font-weight: 700; color: var(--s800);
    font-family: var(--mono); line-height: 1.2;
}
.price-highlight .price-currency {
    font-size: .7rem; color: var(--s400);
}

/* ══════════════════════════════════════════════
   TABLE
══════════════════════════════════════════════ */
.table-db {
    width: 100%; border-collapse: collapse;
}
.table-db tr {
    border-bottom: 1px solid var(--s100);
}
.table-db tr:last-child { border-bottom: none; }
.table-db td {
    padding: 10px 0; font-size: .8rem; color: var(--s600);
}
.table-db td:first-child { color: var(--s400); }
.table-db td:last-child {
    font-weight: 600; color: var(--s800); text-align: right;
}

/* ══════════════════════════════════════════════
   PAYMENT OPTIONS
══════════════════════════════════════════════ */
.payment-options {
    display: flex; flex-direction: column; gap: 12px;
}
.payment-option {
    border: 1.5px solid var(--s200); border-radius: var(--rl);
    overflow: hidden; transition: var(--transition);
}
.payment-option:hover {
    border-color: var(--g300); box-shadow: var(--shadow-sm);
}
.payment-option.active {
    border-color: var(--g500); box-shadow: 0 0 0 3px var(--g100);
}
.payment-option-header {
    padding: 16px; cursor: pointer; background: var(--white);
    display: flex; align-items: center; gap: 16px;
}
.payment-radio {
    width: 18px; height: 18px; accent-color: var(--g500);
}
.payment-icon {
    width: 40px; height: 40px; border-radius: var(--rl);
    background: var(--g50); color: var(--g600);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
}
.payment-info {
    flex: 1;
}
.payment-title {
    font-size: .85rem; font-weight: 600; color: var(--s800);
    margin-bottom: 2px;
}
.payment-desc {
    font-size: .7rem; color: var(--s400);
}
.payment-details {
    padding: 16px; background: var(--surface);
    border-top: 1px solid var(--s100);
}

/* ══════════════════════════════════════════════
   FORM ELEMENTS
══════════════════════════════════════════════ */
.form-group {
    margin-bottom: 16px;
}
.form-label {
    font-size: .7rem; font-weight: 600; color: var(--s600);
    margin-bottom: 6px; display: flex; align-items: center; gap: 6px;
    text-transform: uppercase; letter-spacing: .5px;
}
.form-label i { color: var(--g500); }
.form-control, .form-select {
    width: 100%; padding: 10px 14px; border-radius: var(--r);
    border: 1.5px solid var(--s200); font-size: .8rem;
    font-family: var(--font); transition: var(--transition);
    background: var(--white);
}
.form-control:focus, .form-select:focus {
    outline: none; border-color: var(--g400);
    box-shadow: 0 0 0 3px var(--g100);
}
.input-group {
    display: flex;
}
.input-group-text {
    padding: 10px 14px; background: var(--s100);
    border: 1.5px solid var(--s200); border-right: none;
    border-radius: var(--r) 0 0 var(--r); font-size: .75rem;
    color: var(--s600);
}
.input-group .form-control {
    border-radius: 0 var(--r) var(--r) 0;
}

/* Range slider */
.form-range {
    width: 100%; height: 6px; border-radius: 3px;
    background: var(--s200); appearance: none;
}
.form-range::-webkit-slider-thumb {
    appearance: none; width: 18px; height: 18px;
    border-radius: 50%; background: var(--g600);
    cursor: pointer; margin-top: -6px;
}

/* Checkbox */
.form-check {
    display: flex; gap: 10px; padding: 12px 0;
}
.form-check-input {
    width: 18px; height: 18px; accent-color: var(--g500);
    margin-top: 2px;
}
.form-check-label {
    font-size: .8rem; color: var(--s600);
}
.form-check-label ul {
    margin-top: 8px; padding-left: 20px;
}
.form-check-label li {
    font-size: .7rem; color: var(--s400); margin-bottom: 2px;
}

/* ══════════════════════════════════════════════
   PROGRESS BAR
══════════════════════════════════════════════ */
.progress {
    height: 8px; background: var(--s100);
    border-radius: 4px; overflow: hidden;
}
.progress-bar {
    height: 100%; border-radius: 4px;
    transition: width .3s ease;
}
.progress-bar-success { background: var(--g600); }
.progress-bar-warning { background: var(--g400); }

/* ══════════════════════════════════════════════
   BOUTONS
══════════════════════════════════════════════ */
.btn-db {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 12px 28px; border-radius: var(--r);
    font-size: .85rem; font-weight: 600; border: none;
    cursor: pointer; transition: var(--transition);
    text-decoration: none; font-family: var(--font);
}
.btn-db-primary {
    background: linear-gradient(135deg, var(--g700), var(--g500));
    color: white; box-shadow: var(--shadow-md);
}
.btn-db-primary:hover {
    transform: translateY(-2px); box-shadow: var(--shadow-lg);
    color: white; text-decoration: none;
}
.btn-db-ghost {
    background: var(--white); color: var(--s600);
    border: 1.5px solid var(--s200);
}
.btn-db-ghost:hover {
    background: var(--s50); border-color: var(--s300);
    color: var(--s900); text-decoration: none;
}
.btn-db-success {
    background: var(--g600); color: white;
}
.btn-db-success:hover {
    background: var(--g700); transform: translateY(-2px);
    box-shadow: var(--shadow-md); color: white;
}
.btn-db:disabled {
    opacity: 0.5; cursor: not-allowed;
    transform: none;
}

/* ══════════════════════════════════════════════
   PROFILE CARD
══════════════════════════════════════════════ */
.profile-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    box-shadow: var(--shadow-sm); position: sticky; top: 100px;
}
.profile-header {
    background: linear-gradient(135deg, var(--g700), var(--g500));
    padding: 20px; text-align: center;
}
.profile-avatar {
    width: 100px; height: 100px; border-radius: 50%;
    border: 4px solid white; box-shadow: var(--shadow-md);
    margin: 0 auto 12px; object-fit: cover;
}
.profile-name {
    font-size: 1rem; font-weight: 600; color: white;
    margin-bottom: 4px;
}
.profile-badge {
    background: rgba(255,255,255,0.2); color: white;
    padding: 3px 10px; border-radius: 30px; font-size: .65rem;
    display: inline-block;
}
.profile-body {
    padding: 20px;
}
.profile-info-row {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 0; border-bottom: 1px solid var(--s100);
}
.profile-info-row:last-child { border-bottom: none; }
.profile-info-icon {
    width: 24px; color: var(--g500); font-size: .85rem;
}
.profile-info-label {
    flex: 1; font-size: .75rem; color: var(--s400);
}
.profile-info-value {
    font-size: .8rem; color: var(--s800); font-weight: 500;
}

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media(max-width:768px){
    .confirm-page{ padding: 20px; }
    .confirm-header{ flex-direction: column; align-items: flex-start; }
    .profile-card{ position: static; margin-top: 20px; }
    .btn-db{ width: 100%; justify-content: center; }
}
</style>

<div class="confirm-page">
    <!-- Breadcrumb -->
    <div class="confirm-breadcrumb anim-1">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="<?php echo e(route('transaction.reservation.createIdentity')); ?>">Création client</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="<?php echo e(route('transaction.reservation.viewCountPerson', $customer->id)); ?>">Dates</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="<?php echo e(route('transaction.reservation.chooseRoom', $customer->id)); ?>?check_in=<?php echo e($stayFrom); ?>&check_out=<?php echo e($stayUntil); ?>">Choix chambre</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Confirmation</span>
    </div>

    <!-- Header -->
    <div class="confirm-header anim-2">
        <div class="confirm-brand">
            <div class="confirm-brand-icon"><i class="fas fa-file-invoice"></i></div>
            <div>
                <h1 class="confirm-header-title">Confirmation de <em>réservation</em></h1>
                <p class="confirm-header-sub">
                    <i class="fas fa-check-circle me-1"></i> Étape 4/4 · Récapitulatif et paiement
                </p>
            </div>
        </div>
        <div class="confirm-header-actions">
            <a href="<?php echo e(route('transaction.reservation.chooseRoom', $customer->id)); ?>?check_in=<?php echo e($stayFrom); ?>&check_out=<?php echo e($stayUntil); ?>" class="btn-db btn-db-ghost">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="progress-container anim-3">
        <div class="progress-steps">
            <div class="progress-step step-completed">
                <div class="step-circle"><i class="fas fa-check"></i></div>
                <div class="step-label">Identité</div>
            </div>
            <div class="progress-step step-completed">
                <div class="step-circle"><i class="fas fa-check"></i></div>
                <div class="step-label">Dates</div>
            </div>
            <div class="progress-step step-completed">
                <div class="step-circle"><i class="fas fa-check"></i></div>
                <div class="step-label">Chambre</div>
            </div>
            <div class="progress-step step-active">
                <div class="step-circle">4</div>
                <div class="step-label">Confirmation</div>
            </div>
        </div>
    </div>

    <!-- Agent Card -->
    <?php if(auth()->guard()->check()): ?>
    <div class="agent-card anim-4">
        <div class="d-flex align-items-center gap-3">
            <div class="agent-avatar d-flex align-items-center justify-content-center" style="background:rgba(255,255,255,0.2);">
                <i class="fas fa-user-tie fa-2x text-white"></i>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="agent-name"><?php echo e(auth()->user()->name); ?></div>
                        <div class="agent-email"><?php echo e(auth()->user()->email); ?></div>
                    </div>
                    <div>
                        <span class="agent-badge"><?php echo e(auth()->user()->role); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Colonne principale -->
        <div class="col-lg-8">
            <!-- Alertes -->
            <?php if(session('success')): ?>
                <div class="alert-db alert-db-success anim-4">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo session('success'); ?></span>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert-db alert-db-warning anim-4">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo e(session('error')); ?></span>
                </div>
            <?php endif; ?>

            <?php if($existingReservationsCount > 0): ?>
            <div class="alert-db alert-db-info anim-4">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>Client régulier</strong><br>
                    <span>Ce client a déjà <?php echo e($existingReservationsCount); ?> réservation(s) dans notre établissement.</span>
                    <a href="<?php echo e(route('transaction.reservation.customerReservations', $customer)); ?>" 
                       class="btn-db btn-db-ghost mt-2" style="padding:5px 12px; font-size:.7rem;">
                        <i class="fas fa-history me-1"></i> Voir l'historique
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Formulaire de réservation -->
            <div class="confirm-card anim-5">
                <div class="confirm-card-header">
                    <h5 class="confirm-card-title">
                        <i class="fas fa-file-invoice"></i>
                        Confirmation de réservation
                    </h5>
                </div>

                <div class="confirm-card-body">
                    <div class="row g-4 mb-4">
                        <!-- Chambre -->
                        <div class="col-md-6">
                            <div class="summary-card">
                                <div class="summary-title">
                                    <i class="fas fa-bed"></i>
                                    Chambre sélectionnée
                                </div>
                                <div class="room-info">
                                    <div class="room-icon">
                                        <i class="fas fa-door-open"></i>
                                    </div>
                                    <div class="room-details">
                                        <h6>Chambre <?php echo e($room->number); ?></h6>
                                        <p><?php echo e($room->type->name ?? 'Standard'); ?></p>
                                        <span class="badge-db badge-db-info">
                                            <i class="fas fa-user me-1"></i> <?php echo e($room->capacity); ?> personnes
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-3 pt-3 border-top" style="border-color:var(--s100);">
                                    <span class="text-muted">Prix par nuit</span>
                                    <span class="fw-bold" style="color:var(--s800); font-family:var(--mono);"><?php echo e(number_format($room->price, 0, ',', ' ')); ?> FCFA</span>
                                </div>
                            </div>
                        </div>

                        <!-- Détails du séjour -->
                        <div class="col-md-6">
                            <div class="summary-card">
                                <div class="summary-title">
                                    <i class="fas fa-calendar-alt"></i>
                                    Détails du séjour
                                </div>
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker"></div>
                                        <div class="timeline-content">
                                            <h6>Arrivée</h6>
                                            <p><?php echo e(\Carbon\Carbon::parse($stayFrom)->format('d/m/Y')); ?> • <span style="color:var(--g600);">14:00</span></p>
                                        </div>
                                    </div>
                                    <div class="timeline-item">
                                        <div class="timeline-marker"></div>
                                        <div class="timeline-content">
                                            <h6>Départ</h6>
                                            <p><?php echo e(\Carbon\Carbon::parse($stayUntil)->format('d/m/Y')); ?> • <span style="color:var(--g600);">12:00</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-3 pt-3 border-top" style="border-color:var(--s100);">
                                    <span class="text-muted">Nombre de nuits</span>
                                    <span class="fw-bold"><?php echo e($dayDifference); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Calcul du prix -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-8">
                            <div class="summary-card">
                                <div class="summary-title">
                                    <i class="fas fa-calculator"></i>
                                    Calcul du prix
                                </div>
                                <table class="table-db">
                                    <tr>
                                        <td><?php echo e(number_format($room->price, 0, ',', ' ')); ?> FCFA × <?php echo e($dayDifference); ?> nuit(s)</td>
                                        <td><?php echo e(number_format($room->price * $dayDifference, 0, ',', ' ')); ?> FCFA</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total séjour</strong></td>
                                        <td><strong style="color:var(--g600);"><?php echo e(number_format($room->price * $dayDifference, 0, ',', ' ')); ?> FCFA</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="price-highlight h-100">
                                <div class="price-label">TOTAL</div>
                                <div class="price-value"><?php echo e(number_format($room->price * $dayDifference, 0, ',', ' ')); ?></div>
                                <div class="price-currency">FCFA</div>
                            </div>
                        </div>
                    </div>

                    <!-- Options de paiement -->
                    <div class="confirm-card" style="margin-bottom:0;">
                        <div class="confirm-card-header" style="background:var(--g50); color:var(--s800);">
                            <h5 class="confirm-card-title" style="color:var(--s800);">
                                <i class="fas fa-credit-card" style="color:var(--g500);"></i>
                                Mode de paiement
                            </h5>
                        </div>
                        <div class="confirm-card-body">
                            <form method="POST" 
                                  action="<?php echo e(route('transaction.reservation.payDownPayment', ['customer' => $customer->id, 'room' => $room->id])); ?>"
                                  id="reservationForm">
                                <?php echo csrf_field(); ?>

                                <input type="hidden" name="check_in" value="<?php echo e($stayFrom); ?>">
                                <input type="hidden" name="check_out" value="<?php echo e($stayUntil); ?>">
                                <input type="hidden" name="person_count" value="1">
                                <input type="hidden" name="downPayment" id="downPaymentHidden" value="0">

                                <div class="payment-options">
                                    <!-- Option 1 : Sans acompte -->
                                    <div class="payment-option" id="option1">
                                        <div class="payment-option-header" onclick="document.getElementById('option_reserve_only').click()">
                                            <input type="radio" class="payment-radio" name="payment_option" 
                                                   id="option_reserve_only" value="reserve_only" checked>
                                            <div class="payment-icon">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                            <div class="payment-info">
                                                <div class="payment-title">Réserver sans acompte</div>
                                                <div class="payment-desc">Confirmation immédiate sans paiement</div>
                                            </div>
                                        </div>
                                        <div class="payment-details">
                                            <div class="alert-db alert-db-info">
                                                <i class="fas fa-info-circle"></i>
                                                <small>La réservation est confirmée sans paiement immédiat. Le paiement complet sera effectué à l'arrivée du client.</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Option 2 : Acompte -->
                                    <div class="payment-option" id="option2">
                                        <div class="payment-option-header" onclick="document.getElementById('option_pay_deposit').click()">
                                            <input type="radio" class="payment-radio" name="payment_option" 
                                                   id="option_pay_deposit" value="pay_deposit">
                                            <div class="payment-icon">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </div>
                                            <div class="payment-info">
                                                <div class="payment-title">Payer un acompte</div>
                                                <div class="payment-desc">Sécurisez votre réservation avec un acompte</div>
                                            </div>
                                        </div>
                                        <div class="payment-details">
                                            <div class="form-group">
                                                <label class="form-label">
                                                    <i class="fas fa-money-bill"></i>
                                                    Montant de l'acompte
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">FCFA</span>
                                                    <input type="number" class="form-control" id="deposit_amount" 
                                                           value="<?php echo e($downPayment); ?>" min="0" max="<?php echo e($room->price * $dayDifference); ?>" step="500">
                                                </div>
                                                <input type="range" class="form-range" id="deposit_slider"
                                                       min="0" max="<?php echo e($room->price * $dayDifference); ?>" step="500"
                                                       value="<?php echo e($downPayment); ?>">
                                                <div class="d-flex justify-content-between mt-2">
                                                    <small class="text-muted">Min: 0 FCFA</small>
                                                    <small class="text-muted">Recommandé: <?php echo e(number_format($downPayment, 0, ',', ' ')); ?> FCFA</small>
                                                    <small class="text-muted">Max: <?php echo e(number_format($room->price * $dayDifference, 0, ',', ' ')); ?> FCFA</small>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">
                                                    <i class="fas fa-credit-card"></i>
                                                    Méthode de paiement
                                                </label>
                                                <select class="form-select" id="payment_method">
                                                    <option value="cash">💵 Espèces</option>
                                                    <option value="card">💳 Carte bancaire</option>
                                                    <option value="mobile_money">📱 Mobile Money</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Option 3 : Paiement complet -->
                                    <div class="payment-option" id="option3">
                                        <div class="payment-option-header" onclick="document.getElementById('option_pay_full').click()">
                                            <input type="radio" class="payment-radio" name="payment_option" 
                                                   id="option_pay_full" value="pay_full">
                                            <div class="payment-icon">
                                                <i class="fas fa-wallet"></i>
                                            </div>
                                            <div class="payment-info">
                                                <div class="payment-title">Paiement complet</div>
                                                <div class="payment-desc">Payez l'intégralité du séjour maintenant</div>
                                            </div>
                                        </div>
                                        <div class="payment-details">
                                            <div class="alert-db alert-db-success">
                                                <i class="fas fa-check-circle"></i>
                                                <div>
                                                    <strong>Paiement complet</strong><br>
                                                    <span class="fw-bold"><?php echo e(number_format($room->price * $dayDifference, 0, ',', ' ')); ?> FCFA</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">
                                                    <i class="fas fa-credit-card"></i>
                                                    Méthode de paiement
                                                </label>
                                                <select class="form-select" id="payment_method_full">
                                                    <option value="cash">💵 Espèces</option>
                                                    <option value="card">💳 Carte bancaire</option>
                                                    <option value="mobile_money">📱 Mobile Money</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Résumé du paiement -->
                                <div class="alert-db alert-db-info mt-4" id="paymentSummary">
                                    <i class="fas fa-file-invoice fa-lg"></i>
                                    <div class="flex-grow-1">
                                        <strong id="summaryText">Réservation sans acompte</strong>
                                        <div id="amountDetails" style="display:none; margin-top:12px;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-muted">Payé :</span>
                                                        <strong id="paidAmount" class="fw-bold">0 FCFA</strong>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-muted">Solde :</span>
                                                        <strong id="balanceAmount" class="fw-bold"><?php echo e(number_format($room->price * $dayDifference, 0, ',', ' ')); ?> FCFA</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-success" id="paymentProgress" style="width:0%"></div>
                                                    </div>
                                                    <small class="text-muted d-block text-center mt-1" id="progressText">Aucun paiement</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Conditions -->
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        J'accepte les conditions générales de réservation :
                                        <ul>
                                            <li>La réservation est confirmée immédiatement après validation</li>
                                            <li>Le paiement complet est dû à l'arrivée (sauf paiement anticipé)</li>
                                            <li>Annulation gratuite jusqu'à 48h avant l'arrivée</li>
                                            <li>Check-in à partir de 14h, check-out avant 12h</li>
                                            <li>Présentation d'une pièce d'identité obligatoire à l'arrivée</li>
                                        </ul>
                                    </label>
                                </div>

                                <!-- Boutons -->
                                <div class="d-flex justify-content-between gap-3 mt-4">
                                    <a href="<?php echo e(route('transaction.reservation.chooseRoom', $customer->id)); ?>?check_in=<?php echo e($stayFrom); ?>&check_out=<?php echo e($stayUntil); ?>" 
                                       class="btn-db btn-db-ghost flex-grow-1">
                                        <i class="fas fa-arrow-left me-2"></i> Retour
                                    </a>
                                    <button type="submit" class="btn-db btn-db-success flex-grow-1" id="submitBtn">
                                        <span id="submitText">Confirmer la réservation</span>
                                        <small class="d-block fw-normal opacity-75">Agent: <?php echo e(auth()->user()->name ?? 'Système'); ?></small>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne latérale - Client -->
        <div class="col-lg-4">
            <div class="profile-card anim-6">
                <div class="profile-header">
                    <?php if($customer->avatar): ?>
                        <img src="<?php echo e(Storage::url($customer->avatar)); ?>" alt="<?php echo e($customer->name); ?>" class="profile-avatar">
                    <?php else: ?>
                        <div class="profile-avatar d-flex align-items-center justify-content-center" style="background:rgba(255,255,255,0.2);">
                            <i class="fas fa-user-circle fa-4x text-white"></i>
                        </div>
                    <?php endif; ?>
                    <div class="profile-name"><?php echo e($customer->name); ?></div>
                    <span class="profile-badge">ID #<?php echo e($customer->id); ?></span>
                </div>
                <div class="profile-body">
                    <div class="profile-info-row">
                        <div class="profile-info-icon"><i class="fas fa-<?php echo e($customer->gender == 'Male' ? 'mars' : 'venus'); ?>"></i></div>
                        <div class="profile-info-label">Genre</div>
                        <div class="profile-info-value"><?php echo e($customer->gender == 'Male' ? 'Homme' : 'Femme'); ?></div>
                    </div>
                    <?php if($customer->phone): ?>
                    <div class="profile-info-row">
                        <div class="profile-info-icon"><i class="fas fa-phone"></i></div>
                        <div class="profile-info-label">Téléphone</div>
                        <div class="profile-info-value"><?php echo e($customer->phone); ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if($customer->email): ?>
                    <div class="profile-info-row">
                        <div class="profile-info-icon"><i class="fas fa-envelope"></i></div>
                        <div class="profile-info-label">Email</div>
                        <div class="profile-info-value"><small><?php echo e($customer->email); ?></small></div>
                    </div>
                    <?php endif; ?>
                    <?php if($customer->job): ?>
                    <div class="profile-info-row">
                        <div class="profile-info-icon"><i class="fas fa-briefcase"></i></div>
                        <div class="profile-info-label">Profession</div>
                        <div class="profile-info-value"><?php echo e($customer->job); ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if($existingReservationsCount > 0): ?>
                    <div class="profile-info-row" style="background:var(--g50);">
                        <div class="profile-info-icon"><i class="fas fa-bed"></i></div>
                        <div class="profile-info-label">Historique</div>
                        <div class="profile-info-value"><?php echo e($existingReservationsCount); ?> réservation(s)</div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="confirm-card-footer">
                    <small class="text-muted d-block text-center">
                        <i class="fas fa-clock me-1"></i>
                        Création le <?php echo e(now()->format('d/m/Y H:i')); ?>

                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const downPaymentHidden = document.getElementById('downPaymentHidden');
    const depositAmount = document.getElementById('deposit_amount');
    const depositSlider = document.getElementById('deposit_slider');
    const summaryText = document.getElementById('summaryText');
    const amountDetails = document.getElementById('amountDetails');
    const paidAmount = document.getElementById('paidAmount');
    const balanceAmount = document.getElementById('balanceAmount');
    const paymentProgress = document.getElementById('paymentProgress');
    const progressText = document.getElementById('progressText');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const termsCheckbox = document.getElementById('terms');

    const totalPrice = <?php echo e($room->price * $dayDifference); ?>;
    const recommendedDeposit = <?php echo e($downPayment); ?>;
    const agentName = "<?php echo e(auth()->user()->name ?? 'Système'); ?>";

    function formatCurrency(amount) {
        return new Intl.NumberFormat('fr-FR').format(amount) + ' FCFA';
    }

    function updatePaymentSummary() {
        const selectedOption = document.querySelector('input[name="payment_option"]:checked').value;
        let paymentAmount = 0;
        let summary = '';

        switch(selectedOption) {
            case 'reserve_only':
                paymentAmount = 0;
                summary = 'Réservation sans acompte';
                amountDetails.style.display = 'none';
                downPaymentHidden.value = 0;
                break;
            case 'pay_deposit':
                paymentAmount = parseFloat(depositAmount.value) || 0;
                summary = `Acompte de ${formatCurrency(paymentAmount)}`;
                amountDetails.style.display = 'block';
                downPaymentHidden.value = paymentAmount;
                break;
            case 'pay_full':
                paymentAmount = totalPrice;
                summary = 'Paiement complet';
                amountDetails.style.display = 'block';
                downPaymentHidden.value = paymentAmount;
                break;
        }

        summaryText.textContent = summary;

        if (selectedOption !== 'reserve_only') {
            const percentage = Math.round((paymentAmount / totalPrice) * 100);
            paidAmount.textContent = formatCurrency(paymentAmount);
            balanceAmount.textContent = formatCurrency(totalPrice - paymentAmount);
            paymentProgress.style.width = percentage + '%';
            progressText.textContent = percentage === 100 ? 'Paiement complet' : percentage + '% payé';
        }

        // Texte du bouton
        if (paymentAmount === totalPrice) {
            submitText.innerHTML = '<i class="fas fa-wallet me-2"></i> Payer et réserver';
        } else if (paymentAmount > 0) {
            submitText.innerHTML = `<i class="fas fa-money-bill-wave me-2"></i> Payer ${formatCurrency(paymentAmount)}`;
        } else {
            submitText.innerHTML = 'Confirmer la réservation';
        }

        submitBtn.disabled = !termsCheckbox.checked;
    }

    // Écouteurs
    document.querySelectorAll('input[name="payment_option"]').forEach(radio => {
        radio.addEventListener('change', updatePaymentSummary);
    });

    depositAmount.addEventListener('input', function() {
        depositSlider.value = this.value;
        updatePaymentSummary();
    });

    depositSlider.addEventListener('input', function() {
        depositAmount.value = this.value;
        updatePaymentSummary();
    });

    termsCheckbox.addEventListener('change', function() {
        submitBtn.disabled = !this.checked;
    });

    // Initialisation
    updatePaymentSummary();

    // Animation des options
    document.querySelectorAll('.payment-option').forEach((opt, idx) => {
        setTimeout(() => {
            opt.style.opacity = '0';
            opt.style.transform = 'translateY(10px)';
            opt.style.transition = 'all 0.3s ease';
            setTimeout(() => {
                opt.style.opacity = '1';
                opt.style.transform = 'translateY(0)';
            }, idx * 100);
        }, 100);
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/transaction/reservation/confirmation.blade.php ENDPATH**/ ?>