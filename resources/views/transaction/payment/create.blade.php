@extends('template.master')
@section('title', 'Effectuer un Paiement')
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

.payment-page {
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
.payment-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .8rem; color: var(--s400);
    margin-bottom: 20px;
}
.payment-breadcrumb a {
    color: var(--s400); text-decoration: none;
    transition: var(--transition);
}
.payment-breadcrumb a:hover { color: var(--g600); }
.payment-breadcrumb .sep { color: var(--s300); }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.payment-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.payment-brand { display: flex; align-items: center; gap: 14px; }
.payment-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.payment-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.payment-header-title em { font-style: normal; color: var(--g600); }
.payment-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.payment-header-actions { display: flex; align-items: center; gap: 10px; }

/* ══════════════════════════════════════════════
   CARTES
══════════════════════════════════════════════ */
.payment-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 20px; box-shadow: var(--shadow-sm);
    transition: var(--transition);
}
.payment-card:hover { border-color: var(--g200); box-shadow: var(--shadow-md); }
.payment-card:last-child { margin-bottom: 0; }

.payment-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 22px; border-bottom: 1.5px solid var(--s100);
    background: var(--white); flex-wrap: wrap; gap: 12px;
}
.payment-card-title {
    display: flex; align-items: center; gap: 10px;
    font-size: .95rem; font-weight: 600; color: var(--s800); margin: 0;
}
.payment-card-title i { color: var(--g600); }

.payment-card-body { padding: 24px; }

/* ══════════════════════════════════════════════
   RÉSUMÉ TRANSACTION
══════════════════════════════════════════════ */
.summary-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 24px; box-shadow: var(--shadow-sm);
    padding: 24px;
}

.summary-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 24px;
}
@media(max-width:768px){ .summary-grid{ grid-template-columns:1fr; } }

.summary-info { display: flex; flex-direction: column; gap: 16px; }

.info-row {
    display: flex; align-items: center; gap: 12px;
}
.info-icon {
    width: 40px; height: 40px; border-radius: 10px;
    background: var(--g50); color: var(--g600);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.info-content { flex: 1; }
.info-label {
    font-size: .7rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .5px; color: var(--s400); margin-bottom: 2px;
}
.info-value {
    font-weight: 600; color: var(--s800); font-size: 1rem;
}

.summary-amounts {
    background: var(--surface); border-radius: var(--rl);
    padding: 20px; border: 1.5px solid var(--s100);
}

.amount-row {
    display: flex; justify-content: space-between;
    padding: 8px 0; border-bottom: 1px solid var(--s100);
}
.amount-row:last-child { border-bottom: none; }
.amount-label { color: var(--s400); }
.amount-value { font-weight: 600; color: var(--s800); }
.amount-total {
    font-size: 1.25rem; font-weight: 700; color: var(--g600);
}

/* Barre de progression */
.progress-container {
    margin-top: 16px;
}
.progress-bar-modern {
    height: 8px; background: var(--s100);
    border-radius: 4px; overflow: hidden; margin-bottom: 8px;
}
.progress-fill {
    height: 100%; background: var(--g500);
    border-radius: 4px; transition: width .3s ease;
}
.progress-text {
    font-size: .75rem; color: var(--s400);
}

/* ══════════════════════════════════════════════
   MONTANT
══════════════════════════════════════════════ */
.amount-card {
    background: var(--white); border-radius: var(--rl);
    border: 1.5px solid var(--s100); padding: 24px;
}

.amount-label-modern {
    display: block; font-size: .75rem; font-weight: 600;
    text-transform: uppercase; color: var(--s400);
    margin-bottom: 8px; letter-spacing: .5px;
}

.amount-input-wrapper {
    position: relative; margin-bottom: 16px;
}
.amount-input {
    width: 100%; padding: 16px 20px;
    border: 1.5px solid var(--s200); border-radius: var(--rl);
    font-size: 1.5rem; font-weight: 700; color: var(--s800);
    transition: var(--transition); font-family: var(--mono);
}
.amount-input:focus {
    outline: none; border-color: var(--g400);
    box-shadow: 0 0 0 3px var(--g100);
}
.amount-input.error {
    border-color: var(--s500); background: #fee2e2;
}
.amount-currency {
    position: absolute; right: 20px; top: 50%;
    transform: translateY(-50%);
    color: var(--s400); font-size: .875rem;
}

.quick-amounts {
    display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px;
}
.quick-amount-btn {
    padding: 8px 16px; border-radius: 30px; font-size: .75rem;
    font-weight: 500; background: var(--s100); color: var(--s700);
    border: 1.5px solid var(--s200); cursor: pointer;
    transition: var(--transition);
}
.quick-amount-btn:hover {
    background: var(--g50); border-color: var(--g300);
    color: var(--g700); transform: translateY(-1px);
}
.quick-amount-btn.full {
    background: var(--g100); color: var(--g700);
    border-color: var(--g200); font-weight: 600;
}

/* ══════════════════════════════════════════════
   MÉTHODES DE PAIEMENT
══════════════════════════════════════════════ */
.methods-grid {
    display: grid; grid-template-columns: repeat(3,1fr);
    gap: 12px; margin-bottom: 24px;
}
@media(max-width:768px){ .methods-grid{ grid-template-columns:repeat(2,1fr); } }
@media(max-width:480px){ .methods-grid{ grid-template-columns:1fr; } }

.method-card-modern {
    background: var(--white); border: 1.5px solid var(--s200);
    border-radius: var(--rl); padding: 20px 16px;
    cursor: pointer; transition: var(--transition);
    position: relative;
}
.method-card-modern:hover {
    transform: translateY(-2px); box-shadow: var(--shadow-md);
    border-color: var(--g300);
}
.method-card-modern.active {
    border-color: var(--g500); background: var(--g50);
    box-shadow: 0 0 0 3px var(--g100);
}

.method-radio {
    position: absolute; top: 12px; right: 12px;
    width: 18px; height: 18px; accent-color: var(--g500);
}

.method-icon-wrapper {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 12px; font-size: 1.5rem;
    background: var(--g50); color: var(--g600);
}

.method-name {
    font-weight: 600; color: var(--s800); margin-bottom: 4px;
}
.method-description {
    font-size: .688rem; color: var(--s400);
}

/* ══════════════════════════════════════════════
   CHAMPS DE FORMULAIRE
══════════════════════════════════════════════ */
.method-fields-modern {
    background: var(--surface); border-radius: var(--rl);
    padding: 20px; margin-top: 16px;
    border: 1.5px solid var(--s100);
}

.form-group-modern {
    margin-bottom: 16px;
}
.form-label-modern {
    display: block; font-size: .75rem; font-weight: 600;
    text-transform: uppercase; color: var(--s400);
    margin-bottom: 6px; letter-spacing: .5px;
}
.form-control-modern {
    width: 100%; padding: 12px 16px;
    border: 1.5px solid var(--s200); border-radius: var(--r);
    font-size: .875rem; transition: var(--transition);
    font-family: var(--font);
}
.form-control-modern:focus {
    outline: none; border-color: var(--g400);
    box-shadow: 0 0 0 3px var(--g100);
}
.form-control-modern[readonly] {
    background: var(--s100); color: var(--s500);
    border-color: var(--s200);
}

/* ══════════════════════════════════════════════
   ALERTES
══════════════════════════════════════════════ */
.alert-modern {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 18px; border-radius: var(--rl);
    margin-bottom: 16px; border: 1.5px solid transparent;
    font-size: .875rem; background: var(--white);
    box-shadow: var(--shadow-sm);
}
.alert-warning {
    background: #fff3cd; border-color: #ffeeba;
    color: #856404;
}
.alert-info {
    background: var(--g50); border-color: var(--g200);
    color: var(--g700);
}
.alert-icon {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.alert-warning .alert-icon { background: #ffc107; color: #856404; }
.alert-info .alert-icon { background: var(--g100); color: var(--g600); }

/* ══════════════════════════════════════════════
   BARRE D'ACTIONS
══════════════════════════════════════════════ */
.action-bar {
    display: flex; justify-content: space-between;
    align-items: center; flex-wrap: wrap; gap: 16px;
    margin-top: 24px; padding: 20px;
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100);
}

.action-buttons {
    display: flex; gap: 8px; flex-wrap: wrap;
}

/* ══════════════════════════════════════════════
   BOUTONS
══════════════════════════════════════════════ */
.btn-modern {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: var(--r);
    font-size: .8rem; font-weight: 500; border: none;
    cursor: pointer; transition: var(--transition);
    text-decoration: none; white-space: nowrap;
    font-family: var(--font);
}

.btn-primary-modern {
    background: var(--g600); color: white;
    box-shadow: 0 2px 10px rgba(46,133,64,.3);
}
.btn-primary-modern:hover {
    background: var(--g700); color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
    text-decoration: none;
}

.btn-outline-modern {
    background: var(--white); color: var(--s600);
    border: 1.5px solid var(--s200);
}
.btn-outline-modern:hover {
    background: var(--s50); border-color: var(--s300);
    color: var(--s900); text-decoration: none;
}

.btn-success-modern {
    background: var(--g600); color: white;
}
.btn-success-modern:hover {
    background: var(--g700); transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(46,133,64,.25);
}

.btn-outline-warning-modern {
    background: var(--white); color: #856404;
    border: 1.5px solid #ffeeba;
}
.btn-outline-warning-modern:hover {
    background: #fff3cd; border-color: #ffc107;
    color: #856404; transform: translateY(-1px);
}

.btn-outline-danger-modern {
    background: var(--white); color: var(--s500);
    border: 1.5px solid var(--s200);
}
.btn-outline-danger-modern:hover {
    background: #fee2e2; border-color: #fecaca;
    color: #b91c1c;
}

.btn-sm-modern { padding: 6px 12px; font-size: .75rem; border-radius: 6px; }
.btn-lg-modern { padding: 12px 24px; font-size: .9rem; border-radius: var(--rl); }

.btn-icon-modern {
    width: 36px; height: 36px; border-radius: 8px;
    display: inline-flex; align-items: center; justify-content: center;
    border: 1.5px solid var(--s200); background: var(--white);
    color: var(--s500); cursor: pointer; transition: var(--transition);
    text-decoration: none; font-size: .8rem;
}
.btn-icon-modern:hover {
    background: var(--g50); border-color: var(--g300);
    color: var(--g600); transform: translateY(-1px);
}

/* ══════════════════════════════════════════════
   DEBUG PANEL
══════════════════════════════════════════════ */
.debug-panel {
    background: var(--s900); color: var(--s300);
    border-radius: var(--rl); padding: 16px 20px;
    margin-bottom: 20px; font-family: var(--mono);
    font-size: .813rem; border: 1.5px solid var(--s700);
}
.debug-panel .debug-title {
    color: var(--s400); font-size: .75rem;
    text-transform: uppercase; margin-bottom: 12px;
}
.debug-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(250px,1fr));
    gap: 12px;
}
.debug-item {
    display: flex; justify-content: space-between;
    padding: 4px 0; border-bottom: 1px solid var(--s700);
}
.debug-label { color: var(--s400); }
.debug-value { color: var(--g300); font-weight: 500; }

/* ══════════════════════════════════════════════
   MODAL
══════════════════════════════════════════════ */
.modal-modern .modal-content {
    border-radius: var(--rxl); border: 1.5px solid var(--s200);
    overflow: hidden; box-shadow: var(--shadow-lg);
}
.modal-modern .modal-header {
    background: var(--surface); border-bottom: 1.5px solid var(--s100);
    padding: 18px 24px;
}
.modal-modern .modal-title {
    font-size: .95rem; font-weight: 600; color: var(--s800);
    display: flex; align-items: center; gap: 8px;
}
.modal-modern .modal-body { padding: 24px; }
.modal-modern .modal-footer {
    background: var(--surface); border-top: 1.5px solid var(--s100);
    padding: 16px 24px;
}
.modal-modern pre {
    max-height: 400px; overflow: auto;
    background: var(--s900); color: var(--s300);
    padding: 16px; border-radius: var(--rl);
    font-size: .813rem; font-family: var(--mono);
}

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media(max-width:768px){
    .payment-page{ padding: 16px; }
    .action-bar{ flex-direction: column; align-items: stretch; }
    .action-buttons{ justify-content: stretch; }
    .action-buttons .btn-modern{ flex: 1; }
}
</style>

<div class="payment-page">
    <!-- Breadcrumb -->
    <div class="payment-breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('transaction.index') }}">Transactions</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('transaction.show', $transaction) }}">#{{ $transaction->id }}</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Paiement</span>
    </div>

    <!-- En-tête -->
    <div class="payment-header anim-2">
        <div class="payment-brand">
            <div class="payment-brand-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div>
                <h1 class="payment-header-title">Effectuer un <em>paiement</em></h1>
                <p class="payment-header-sub">
                    <i class="fas fa-user me-1"></i> {{ $transaction->customer->name }} 
                    <i class="fas fa-circle fa-xs" style="color:var(--s300); font-size:4px;"></i>
                    <i class="fas fa-door-open me-1"></i> #{{ $transaction->room->number }}
                </p>
            </div>
        </div>
    </div>

    <!-- Debug panel (admin only) -->
    @if(auth()->user()->isAdmin())
    <div class="debug-panel fade-in anim-2 d-none" id="debug-panel">
        <div class="debug-title">
            <i class="fas fa-bug me-1"></i> Informations de débogage
        </div>
        <div class="debug-grid">
            <div>
                <div class="debug-item">
                    <span class="debug-label">Transaction ID:</span>
                    <span class="debug-value">#{{ $transaction->id }}</span>
                </div>
                <div class="debug-item">
                    <span class="debug-label">Statut:</span>
                    <span class="debug-value">{{ $transaction->status }}</span>
                </div>
                <div class="debug-item">
                    <span class="debug-label">Total (colonne):</span>
                    <span class="debug-value">{{ number_format($transaction->total_price, 0, ',', ' ') }} CFA</span>
                </div>
            </div>
            <div>
                <div class="debug-item">
                    <span class="debug-label">Total (calculé):</span>
                    <span class="debug-value">{{ number_format($transaction->getTotalPrice(), 0, ',', ' ') }} CFA</span>
                </div>
                <div class="debug-item">
                    <span class="debug-label">Payé (calculé):</span>
                    <span class="debug-value">{{ number_format($transaction->getTotalPayment(), 0, ',', ' ') }} CFA</span>
                </div>
                <div class="debug-item">
                    <span class="debug-label">Reste (calculé):</span>
                    <span class="debug-value">{{ number_format($transaction->getRemainingPayment(), 0, ',', ' ') }} CFA</span>
                </div>
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <button type="button" class="btn-modern btn-sm-modern btn-outline-modern" id="refresh-debug">
                <i class="fas fa-sync-alt me-1"></i> Actualiser
            </button>
            <button type="button" class="btn-modern btn-sm-modern btn-outline-modern" id="force-sync">
                <i class="fas fa-cogs me-1"></i> Synchroniser
            </button>
            <button type="button" class="btn-modern btn-sm-modern btn-outline-modern" id="show-api">
                <i class="fas fa-code me-1"></i> API
            </button>
            <button type="button" class="btn-modern btn-sm-modern btn-outline-modern" id="hide-debug">
                <i class="fas fa-eye-slash me-1"></i> Cacher
            </button>
        </div>
    </div>
    @endif

    <!-- Résumé de la transaction -->
    <div class="summary-card anim-3">
        <div class="summary-grid">
            <div class="summary-info">
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-user"></i></div>
                    <div class="info-content">
                        <div class="info-label">Client</div>
                        <div class="info-value">{{ $transaction->customer->name }}</div>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-bed"></i></div>
                    <div class="info-content">
                        <div class="info-label">Chambre</div>
                        <div class="info-value">#{{ $transaction->room->number }} · {{ $transaction->room->type->name ?? 'Standard' }}</div>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div class="info-content">
                        <div class="info-label">Période</div>
                        <div class="info-value">
                            {{ $transaction->check_in->format('d/m/Y') }} 
                            <i class="fas fa-arrow-right mx-2" style="color:var(--s300); font-size:.7rem;"></i>
                            {{ $transaction->check_out->format('d/m/Y') }}
                            ({{ $transaction->getNightsAttribute() }} nuits)
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="summary-amounts">
                <div class="amount-row">
                    <span class="amount-label">Total séjour</span>
                    <span class="amount-value">{{ number_format($transaction->getTotalPrice(), 0, ',', ' ') }} CFA</span>
                </div>
                <div class="amount-row">
                    <span class="amount-label">Déjà payé</span>
                    <span class="amount-value">{{ number_format($transaction->getTotalPayment(), 0, ',', ' ') }} CFA</span>
                </div>
                <div class="amount-row">
                    <span class="amount-label">Reste à payer</span>
                    <span class="amount-value amount-total">{{ number_format($transaction->getRemainingPayment(), 0, ',', ' ') }} CFA</span>
                </div>
                
                <div class="progress-container">
                    <div class="progress-bar-modern">
                        <div class="progress-fill" id="progressBar" style="width: {{ $transaction->getPaymentRate() }}%"></div>
                    </div>
                    <div class="progress-text" id="progressText">
                        {{ number_format($transaction->getPaymentRate(), 1) }}% du séjour payé
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de paiement -->
    <form action="{{ route('transaction.payment.store', $transaction) }}" method="POST" id="paymentForm">
        @csrf
        
        <div class="row g-4">
            <!-- Colonne gauche - Montant -->
            <div class="col-lg-5">
                <div class="payment-card anim-4">
                    <div class="payment-card-header">
                        <h5 class="payment-card-title">
                            <i class="fas fa-money-bill-wave"></i>
                            Montant du paiement
                        </h5>
                    </div>
                    <div class="payment-card-body">
                        <div class="amount-card">
                            <label class="amount-label-modern">Montant à payer</label>
                            <div class="amount-input-wrapper">
                                <input type="number" 
                                       class="amount-input" 
                                       id="amount"
                                       name="amount"
                                       min="100"
                                       max="{{ $transaction->getRemainingPayment() }}"
                                       step="100"
                                       value="{{ min($transaction->getRemainingPayment(), $transaction->getRemainingPayment()) }}"
                                       required>
                                <span class="amount-currency">FCFA</span>
                            </div>
                            
                            <div class="quick-amounts">
                                @php
                                    $remaining = $transaction->getRemainingPayment();
                                    $quickAmounts = [
                                        min(1000, $remaining),
                                        min(5000, $remaining),
                                        min(10000, $remaining),
                                        min(25000, $remaining),
                                        min(50000, $remaining),
                                        $remaining
                                    ];
                                    $quickAmounts = array_unique(array_filter($quickAmounts));
                                @endphp
                                
                                @foreach($quickAmounts as $quickAmount)
                                    @if($quickAmount >= 100)
                                        <button type="button" 
                                                class="quick-amount-btn {{ $quickAmount == $remaining ? 'full' : '' }}"
                                                data-amount="{{ $quickAmount }}">
                                            {{ number_format($quickAmount, 0, ',', ' ') }} CFA
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                            
                            <div id="amountInfo" class="mt-3">
                                <div class="alert-modern alert-info" id="remainingAfter">
                                    <div class="alert-icon"><i class="fas fa-info"></i></div>
                                    <div>
                                        Reste après paiement: 
                                        <strong id="remainingAfterValue">{{ number_format($transaction->getRemainingPayment(), 0, ',', ' ') }} CFA</strong>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="amountWarning" class="d-none">
                                <div class="alert-modern alert-warning" id="warningMessage"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Colonne droite - Méthode de paiement -->
            <div class="col-lg-7">
                <div class="payment-card anim-5">
                    <div class="payment-card-header">
                        <h5 class="payment-card-title">
                            <i class="fas fa-credit-card"></i>
                            Méthode de paiement
                        </h5>
                    </div>
                    <div class="payment-card-body">
                        <!-- Méthodes de paiement -->
                        <div class="methods-grid" id="paymentMethods">
                            @php
                                $paymentMethods = \App\Models\Payment::getPaymentMethods();
                            @endphp
                            
                            @foreach($paymentMethods as $method => $details)
                                <label class="method-card-modern {{ $loop->first ? 'active' : '' }}" 
                                       for="method_{{ $method }}">
                                    <input type="radio" 
                                           name="payment_method" 
                                           id="method_{{ $method }}"
                                           value="{{ $method }}"
                                           class="method-radio"
                                           {{ $loop->first ? 'checked' : '' }}
                                           required>
                                    <div class="method-icon-wrapper">
                                        <i class="fas {{ $details['icon'] }}"></i>
                                    </div>
                                    <div class="method-name">{{ $details['label'] }}</div>
                                    <div class="method-description">{{ $details['description'] }}</div>
                                </label>
                            @endforeach
                        </div>
                        
                        <!-- Champs spécifiques -->
                        <div class="method-fields-modern" id="methodFields">
                            <!-- Description (toujours visible) -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">Description (optionnelle)</label>
                                        <textarea class="form-control-modern" 
                                                name="description" 
                                                id="description"
                                                rows="2"
                                                placeholder="Informations sur le paiement..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- ===== CHAMPS SPÉCIFIQUES PAR MÉTHODE ===== -->
                            
                            <!-- Mobile Money -->
                            <div id="fields_mobile_money" class="method-fields-group" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-mobile-alt me-1"></i> Opérateur
                                            </label>
                                            <select name="mobile_operator" class="form-control-modern">
                                                <option value="">-- Sélectionner --</option>
                                                <option value="MTN">MTN Mobile Money</option>
                                                <option value="Moov">Moov Money</option>
                                                <option value="Orange">Orange Money</option>
                                                <option value="Airtel">Airtel Money</option>
                                                <option value="Wave">Wave</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-phone me-1"></i> Numéro de téléphone
                                            </label>
                                            <input type="tel" name="mobile_number" class="form-control-modern" 
                                                placeholder="Ex: 01 23 45 67 89">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Carte Bancaire -->
                            <div id="fields_card" class="method-fields-group" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-credit-card me-1"></i> Numéro de carte
                                            </label>
                                            <input type="text" name="card_number" class="form-control-modern" 
                                                placeholder="**** **** **** 1234" maxlength="19">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-calendar me-1"></i> Expiration
                                            </label>
                                            <input type="text" name="card_expiry" class="form-control-modern" 
                                                placeholder="MM/AA">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-lock me-1"></i> CVV
                                            </label>
                                            <input type="password" name="card_cvv" class="form-control-modern" 
                                                placeholder="***" maxlength="3">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Virement Bancaire -->
                            <div id="fields_transfer" class="method-fields-group" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-university me-1"></i> Banque
                                            </label>
                                            <input type="text" name="bank_name" class="form-control-modern" 
                                                placeholder="Nom de la banque">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-hashtag me-1"></i> Numéro de compte
                                            </label>
                                            <input type="text" name="account_number" class="form-control-modern" 
                                                placeholder="Numéro de compte">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fedapay -->
                            <div id="fields_fedapay" class="method-fields-group" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-fingerprint me-1"></i> Token Fedapay
                                            </label>
                                            <input type="text" name="fedapay_token" class="form-control-modern" 
                                                placeholder="Token de paiement">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-hashtag me-1"></i> ID Transaction
                                            </label>
                                            <input type="text" name="fedapay_transaction_id" class="form-control-modern" 
                                                placeholder="ID de transaction">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Chèque -->
                            <div id="fields_check" class="method-fields-group" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-hashtag me-1"></i> Numéro de chèque
                                            </label>
                                            <input type="text" name="check_number" class="form-control-modern" 
                                                placeholder="Numéro du chèque">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-university me-1"></i> Banque émettrice
                                            </label>
                                            <input type="text" name="issuing_bank" class="form-control-modern" 
                                                placeholder="Nom de la banque">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Espèces -->
                            <div id="fields_cash" class="method-fields-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <i class="fas fa-user me-1"></i> Reçu par
                                            </label>
                                            <input type="text" name="received_by" class="form-control-modern" 
                                                value="{{ auth()->user()->name }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Référence automatique -->
                            <input type="hidden" name="reference" id="reference" value="">
                            
                            <div class="alert-modern alert-info mt-3">
                                <div class="alert-icon"><i class="fas fa-info-circle"></i></div>
                                <div>
                                    <small>Référence: <span id="referenceDisplay" class="fw-bold"></span></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Barre d'actions -->
        <div class="action-bar anim-6">
            <a href="{{ route('transaction.show', $transaction) }}" class="btn-modern btn-outline-modern">
                <i class="fas fa-arrow-left me-2"></i>
                Retour
            </a>
            
            <div class="action-buttons">
                <button type="button" class="btn-modern btn-outline-warning-modern" id="validateBtn">
                    <i class="fas fa-check-circle me-2"></i>
                    Valider
                </button>
                <button type="reset" class="btn-modern btn-outline-danger-modern">
                    <i class="fas fa-redo me-2"></i>
                    Réinitialiser
                </button>
                <button type="submit" class="btn-modern btn-success-modern btn-lg-modern" id="submitBtn">
                    <i class="fas fa-check-circle me-2"></i>
                    <span id="submitText">Enregistrer le paiement</span>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Modal API -->
<div class="modal fade modal-modern" id="apiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-code me-2"></i>
                    Réponse API
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre id="apiResponseContent" class="bg-dark text-light p-3 rounded"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modern btn-outline-modern" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== PAGE DE PAIEMENT INITIALISÉE ===');
    
    const transactionId = {{ $transaction->id }};
    const remaining = {{ $transaction->getRemainingPayment() }};
    const totalPrice = {{ $transaction->getTotalPrice() }};
    const totalPayment = {{ $transaction->getTotalPayment() }};
    
    // Éléments DOM
    const amountInput = document.getElementById('amount');
    const remainingAfter = document.getElementById('remainingAfterValue');
    const amountWarning = document.getElementById('amountWarning');
    const warningMessage = document.getElementById('warningMessage');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const validateBtn = document.getElementById('validateBtn');
    const paymentForm = document.getElementById('paymentForm');
    const referenceInput = document.getElementById('reference');
    const referenceDisplay = document.getElementById('referenceDisplay');
    
    let currentRemaining = remaining;
    
    // Initialisation
    updateReference();
    updateMethodFields();
    
    // Fonction pour mettre à jour la référence
    function updateReference() {
        const method = document.querySelector('input[name="payment_method"]:checked')?.value || 'cash';
        
        let prefix = 'PAY-';
        switch(method) {
            case 'cash': prefix = 'CASH-'; break;
            case 'card': prefix = 'CARD-'; break;
            case 'transfer': prefix = 'VIR-'; break;
            case 'mobile_money': prefix = 'MOMO-'; break;
            case 'fedapay': prefix = 'FDP-'; break;
            case 'check': prefix = 'CHQ-'; break;
        }
        
        const ref = `${prefix}${transactionId}-${Date.now()}`;
        referenceInput.value = ref;
        referenceDisplay.textContent = ref;
    }
    
    // Fonction pour mettre à jour les champs selon la méthode
    function updateMethodFields() {
        const method = document.querySelector('input[name="payment_method"]:checked')?.value || 'cash';
        
        // Cacher tous les groupes
        document.querySelectorAll('.method-fields-group').forEach(group => {
            group.style.display = 'none';
        });
        
        // Afficher le groupe correspondant
        const activeGroup = document.getElementById(`fields_${method}`);
        if (activeGroup) {
            activeGroup.style.display = 'block';
        }
    }
    
    // Mettre à jour les calculs
    function updateCalculations() {
        const amount = parseFloat(amountInput.value) || 0;
        const newRemaining = currentRemaining - amount;
        const newPaymentRate = ((totalPayment + amount) / totalPrice) * 100;
        
        remainingAfter.textContent = `${newRemaining.toLocaleString('fr-FR')} CFA`;
        progressBar.style.width = `${newPaymentRate}%`;
        progressText.textContent = `${newPaymentRate.toFixed(1)}% du séjour payé`;
        
        if (amount > currentRemaining) {
            amountWarning.classList.remove('d-none');
            warningMessage.innerHTML = `
                <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div>Le montant dépasse le solde restant de ${currentRemaining.toLocaleString('fr-FR')} CFA</div>
            `;
            amountInput.classList.add('error');
        } else if (amount < 100 && amount > 0) {
            amountWarning.classList.remove('d-none');
            warningMessage.innerHTML = `
                <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div>Le montant minimum est de 100 CFA</div>
            `;
            amountInput.classList.add('error');
        } else {
            amountWarning.classList.add('d-none');
            amountInput.classList.remove('error');
        }
        
        const method = document.querySelector('input[name="payment_method"]:checked')?.value;
        if (method && amount > 0) {
            const methodLabel = document.querySelector(`label[for="method_${method}"] .method-name`).textContent;
            submitText.innerHTML = amount === currentRemaining ? 
                `Payer la totalité (${methodLabel})` : 
                `Payer ${amount.toLocaleString('fr-FR')} CFA (${methodLabel})`;
        } else {
            submitText.innerHTML = 'Enregistrer le paiement';
        }
    }
    
    // Gérer les boutons de montant rapide
    document.querySelectorAll('.quick-amount-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const amount = parseFloat(this.getAttribute('data-amount'));
            amountInput.value = amount;
            updateCalculations();
        });
    });
    
    // Gérer le changement de méthode de paiement
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.method-card-modern').forEach(card => {
                card.classList.remove('active');
            });
            this.closest('label').classList.add('active');
            updateReference();
            updateMethodFields();
            updateCalculations();
        });
    });
    
    // Gérer la saisie du montant
    amountInput.addEventListener('input', updateCalculations);
    
    // Valider le formulaire
    validateBtn.addEventListener('click', function() {
        const amount = parseFloat(amountInput.value) || 0;
        const method = document.querySelector('input[name="payment_method"]:checked')?.value;
        
        if (!method) {
            Swal.fire({
                title: 'Erreur',
                text: 'Veuillez sélectionner une méthode de paiement',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        if (amount <= 0) {
            Swal.fire({
                title: 'Erreur',
                text: 'Veuillez saisir un montant valide',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        if (amount > currentRemaining) {
            Swal.fire({
                title: 'Erreur',
                text: `Le montant ne peut pas dépasser ${currentRemaining.toLocaleString('fr-FR')} CFA`,
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        if (amount < 100) {
            Swal.fire({
                title: 'Erreur',
                text: 'Le montant minimum est de 100 CFA',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        const methodLabel = document.querySelector(`label[for="method_${method}"] .method-name`).textContent;
        
        Swal.fire({
            title: 'Validation réussie',
            html: `
                <div class="text-start">
                    <div class="alert alert-success mb-0" style="background:var(--g50); border-color:var(--g200); color:var(--g700);">
                        <strong>Montant:</strong> ${amount.toLocaleString('fr-FR')} CFA<br>
                        <strong>Méthode:</strong> ${methodLabel}<br>
                        <strong>Reste:</strong> ${(currentRemaining - amount).toLocaleString('fr-FR')} CFA
                    </div>
                </div>
            `,
            icon: 'success',
            confirmButtonText: 'Continuer'
        });
    });
    
    // Soumettre le formulaire
    paymentForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const amount = parseFloat(amountInput.value) || 0;
        
        if (amount <= 0 || amount > currentRemaining) {
            Swal.fire({
                title: 'Erreur',
                text: 'Montant invalide',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement...';
        
        try {
            const formData = new FormData(this);

            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });

            let data;
            try {
                data = await response.json();
            } catch {
                const status = response.status;
                if (status === 419) throw new Error('Session expirée. Rechargez la page et réessayez.');
                if (status === 403) throw new Error('Action non autorisée.');
                if (status >= 500) throw new Error('Erreur serveur. Contactez l\'administrateur si le problème persiste.');
                throw new Error('Réponse inattendue du serveur. Réessayez.');
            }

            if (data.success) {
                Swal.fire({
                    title: '✅ Succès',
                    html: `
                        <div class="text-start">
                            <p>${data.message}</p>
                            <div class="alert alert-success" style="background:var(--g50); border-color:var(--g200); color:var(--g700);">
                                <strong>Montant:</strong> ${data.data.payment.amount.toLocaleString('fr-FR')} CFA<br>
                                <strong>Référence:</strong> ${data.data.payment.reference}
                            </div>
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonText: 'Retour sur le dashboard'
                }).then(() => {
                    window.location.href = `/transactions/${transactionId}`;
                });
            } else {
                throw new Error(data.message || 'Erreur lors du paiement');
            }
        } catch (error) {
            console.error('Erreur:', error);
            
            Swal.fire({
                title: '❌ Erreur',
                text: error.message || 'Une erreur est survenue',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            
            submitBtn.disabled = false;
            submitText.innerHTML = 'Enregistrer le paiement';
        }
    });
    
    // Rafraîchissement périodique
    setInterval(async () => {
        try {
            const response = await fetch(`/api/transactions/${transactionId}/check-status`);
            const data = await response.json();
            
            if (data.success && data.transaction.remaining !== currentRemaining) {
                currentRemaining = data.transaction.remaining;
                amountInput.max = currentRemaining;
                updateCalculations();
            }
        } catch (error) {
            console.warn('Erreur rafraîchissement:', error);
        }
    }, 30000);
    
    // Debug panel (admin only)
    @if(auth()->user()->isAdmin())
        const debugPanel = document.getElementById('debug-panel');
        
        if (document.getElementById('hide-debug')) {
            document.getElementById('hide-debug').addEventListener('click', function() {
                debugPanel.classList.add('d-none');
            });
        }
        
        if (document.getElementById('refresh-debug')) {
            document.getElementById('refresh-debug').addEventListener('click', async function() {
                location.reload();
            });
        }
        
        if (document.getElementById('show-api')) {
            document.getElementById('show-api').addEventListener('click', async function() {
                try {
                    const response = await fetch(`/api/transactions/${transactionId}/check-status`);
                    const data = await response.json();
                    
                    document.getElementById('apiResponseContent').textContent = JSON.stringify(data, null, 2);
                    
                    const modal = new bootstrap.Modal(document.getElementById('apiModal'));
                    modal.show();
                } catch (error) {
                    console.error('Erreur:', error);
                }
            });
        }
    @endif
});
</script>
@endsection