@extends('template.master')

@section('title', 'Rapport de Session #' . $session->id)

@push('styles')
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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

body {
    background: var(--surface);
    font-family: var(--font);
    color: var(--gray-800);
    padding: 1.5rem 0;
}

.report-wrapper {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

/* ========================================= */
/* CARTE PRINCIPALE */
/* ========================================= */
.report-card {
    background: var(--white);
    border-radius: var(--rxl);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    border: 1.5px solid var(--gray-200);
}

/* ========================================= */
/* EN-TÊTE */
/* ========================================= */
.report-header {
    padding: 1.25rem 2rem;
    border-bottom: 1.5px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    background: var(--white);
}

.header-title h1 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 0.25rem;
    letter-spacing: -0.02em;
}

.header-title h1 em {
    font-style: normal;
    color: var(--green-600);
}

.header-title p {
    color: var(--gray-500);
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.header-title p i {
    color: var(--green-600);
    font-size: 0.4rem;
}

.header-badge {
    padding: 0.35rem 1.25rem;
    background: var(--gray-100);
    border-radius: 100px;
    font-weight: 600;
    font-size: 0.75rem;
    color: var(--gray-700);
    border: 1.5px solid var(--gray-200);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.header-badge.active {
    background: var(--green-50);
    color: var(--green-700);
    border-color: var(--green-200);
}

/* ========================================= */
/* GRILLE D'INFORMATIONS */
/* ========================================= */
.info-grid {
    padding: 1.25rem 2rem;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    background: var(--gray-50);
    border-bottom: 1.5px solid var(--gray-200);
}

.info-item {
    background: var(--white);
    padding: 0.75rem 1rem;
    border-radius: var(--rl);
    border: 1.5px solid var(--gray-200);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.info-icon {
    width: 36px;
    height: 36px;
    background: var(--green-50);
    border-radius: var(--r);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--green-600);
    font-size: 1rem;
}

.info-label {
    font-size: 0.6rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.1rem;
}

.info-value {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-900);
    line-height: 1.2;
}

/* ========================================= */
/* KPI CARDS */
/* ========================================= */
.kpi-grid {
    padding: 1.25rem 2rem;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    border-bottom: 1.5px solid var(--gray-200);
    background: var(--white);
}

.kpi-card {
    padding: 1rem 1.25rem;
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
}

.kpi-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.kpi-title {
    font-size: 0.65rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.kpi-icon {
    width: 28px;
    height: 28px;
    background: var(--green-50);
    border-radius: var(--r);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--green-600);
    font-size: 0.75rem;
}

.kpi-value {
    font-size: 1.5rem;
    font-weight: 700;
    font-family: var(--mono);
    color: var(--gray-900);
    margin-bottom: 0.25rem;
}

.kpi-footer {
    font-size: 0.65rem;
    color: var(--gray-500);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.kpi-badge {
    padding: 0.2rem 0.6rem;
    background: var(--gray-100);
    border-radius: 100px;
    color: var(--gray-700);
    font-weight: 500;
    font-size: 0.6rem;
    border: 1.5px solid var(--gray-200);
}

.kpi-badge.green {
    background: var(--green-50);
    color: var(--green-700);
    border-color: var(--green-200);
}

/* ========================================= */
/* SECTION TITRE */
/* ========================================= */
.section-header {
    padding: 1rem 2rem 0 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-title i {
    width: 30px;
    height: 30px;
    background: var(--green-50);
    border-radius: var(--r);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--green-600);
    font-size: 0.875rem;
}

.section-title h2 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-900);
    margin: 0;
}

.section-count {
    padding: 0.2rem 0.8rem;
    background: var(--gray-100);
    border-radius: 100px;
    font-size: 0.7rem;
    font-weight: 500;
    color: var(--gray-700);
    border: 1.5px solid var(--gray-200);
}

/* ========================================= */
/* MÉTHODES DE PAIEMENT */
/* ========================================= */
.methods-grid {
    padding: 1rem 2rem;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}

.method-card {
    padding: 1rem;
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
}

.method-name {
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 0.5rem;
    font-size: 0.8rem;
}

.method-stats {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 0.5rem;
}

.method-amount {
    font-size: 1rem;
    font-weight: 700;
    font-family: var(--mono);
    color: var(--gray-900);
}

.method-count {
    font-size: 0.65rem;
    color: var(--gray-500);
    font-weight: 500;
}

.method-progress {
    height: 4px;
    background: var(--gray-100);
    border-radius: 100px;
    overflow: hidden;
}

.method-progress-bar {
    height: 100%;
    background: var(--green-600);
    border-radius: 100px;
}

/* ========================================= */
/* TABLEAU */
/* ========================================= */
.table-container {
    margin: 1rem 2rem;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    overflow: hidden;
    background: var(--white);
    max-height: 350px;
    overflow-y: auto;
}

.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.75rem;
}

th {
    text-align: left;
    padding: 0.6rem 1rem;
    background: var(--gray-50);
    font-size: 0.6rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.4px;
    border-bottom: 1.5px solid var(--gray-200);
    white-space: nowrap;
    position: sticky;
    top: 0;
    z-index: 10;
}

td {
    padding: 0.6rem 1rem;
    border-bottom: 1px solid var(--gray-200);
    color: var(--gray-700);
    vertical-align: middle;
}

tr:last-child td {
    border-bottom: none;
}

/* Badges */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.2rem 0.6rem;
    border-radius: 100px;
    font-size: 0.6rem;
    font-weight: 600;
    background: var(--gray-100);
    color: var(--gray-700);
    white-space: nowrap;
    border: 1.5px solid var(--gray-200);
}

.badge.green {
    background: var(--green-50);
    color: var(--green-700);
    border-color: var(--green-200);
}

.method-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.2rem 0.6rem;
    border-radius: 100px;
    font-size: 0.6rem;
    font-weight: 600;
    background: var(--gray-100);
    color: var(--gray-700);
    border: 1.5px solid var(--gray-200);
}

.method-badge i {
    font-size: 0.6rem;
    color: var(--green-600);
}

/* Montants */
.amount {
    font-weight: 600;
    font-size: 0.7rem;
    font-family: var(--mono);
}

.amount.positive {
    color: var(--green-600);
}

.amount.negative {
    color: var(--red-500);
}

/* Pied du tableau */
.table-footer {
    padding: 0.6rem 1rem;
    background: var(--gray-50);
    border-top: 1.5px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.table-totals {
    display: flex;
    gap: 1.5rem;
}

.total-label {
    font-size: 0.6rem;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.total-value {
    font-size: 0.9rem;
    font-weight: 700;
    font-family: var(--mono);
    color: var(--gray-900);
}

.total-value.green {
    color: var(--green-600);
}

.total-value.red {
    color: var(--red-500);
}

/* ========================================= */
/* RÉSUMÉ FINANCIER */
/* ========================================= */
.summary-grid {
    padding: 1rem 2rem;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    background: var(--gray-50);
    border-top: 1.5px solid var(--gray-200);
    border-bottom: 1.5px solid var(--gray-200);
}

.summary-item {
    text-align: center;
    padding: 0.75rem;
    background: var(--white);
    border-radius: var(--rl);
    border: 1.5px solid var(--gray-200);
}

.summary-label {
    font-size: 0.6rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.25rem;
}

.summary-value {
    font-size: 1.25rem;
    font-weight: 700;
    font-family: var(--mono);
    color: var(--gray-900);
    margin-bottom: 0.1rem;
}

.summary-value.green {
    color: var(--green-600);
}

.summary-value.red {
    color: var(--red-500);
}

/* ========================================= */
/* PIED DE PAGE */
/* ========================================= */
.report-footer {
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    background: var(--white);
}

.signatures {
    display: flex;
    gap: 2rem;
}

.signature-item {
    text-align: center;
}

.signature-title {
    font-size: 0.6rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.25rem;
}

.signature-line {
    width: 120px;
    height: 1px;
    background: var(--gray-200);
    margin: 0.35rem 0;
}

.signature-name {
    color: var(--gray-700);
    font-size: 0.7rem;
    font-weight: 500;
}

.signature-empty {
    color: var(--gray-400);
    font-size: 0.7rem;
    font-style: italic;
}

.footer-actions {
    display: flex;
    gap: 0.5rem;
}

.btn {
    padding: 0.5rem 1.25rem;
    border-radius: var(--r);
    font-weight: 600;
    font-size: 0.75rem;
    cursor: pointer;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    transition: var(--transition);
}

.btn-green {
    background: var(--green-600);
    color: white;
}

.btn-green:hover {
    background: var(--green-700);
    transform: translateY(-1px);
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

/* ========================================= */
/* PRINT STYLES */
/* ========================================= */
@media print {
    @page {
        size: A4 portrait;
        margin: 0.5cm;
    }
    
    body { background: white; padding: 0; }
    .footer-actions { display: none !important; }
    .btn { display: none !important; }
    
    .report-card { box-shadow: none; border: 1.5px solid var(--gray-200); }
    .badge, .method-badge { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}

/* ========================================= */
/* RESPONSIVE */
/* ========================================= */
@media (max-width: 1200px) {
    .info-grid, .kpi-grid, .methods-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .info-grid, .kpi-grid, .methods-grid, .summary-grid {
        grid-template-columns: 1fr;
    }
    .signatures { flex-direction: column; gap: 1rem; }
    .signature-line { width: 100%; }
}
</style>
@endpush

@section('content')
@php
    // Calcul des totaux
    $totalRefunded = $payments->where('status', 'completed')->where('payment_method', 'refund')->sum('amount');
    $totalCompleted = $payments->where('status', 'completed')->where('payment_method', '!=', 'refund')->sum('amount');
    $paymentCount = $payments->where('status', 'completed')->where('payment_method', '!=', 'refund')->count();
    $netTotal = $totalCompleted - $totalRefunded;
    
    // Répartition par méthode
    $byMethod = $payments->where('status', 'completed')
        ->groupBy('payment_method')
        ->map(function($group) {
            return [
                'method' => $group->first()->payment_method_label ?? $group->first()->payment_method,
                'count' => $group->count(),
                'total' => $group->sum('amount'),
                'type' => $group->first()->payment_method
            ];
        });
@endphp

<div class="report-wrapper">
    <div class="report-card">
        
        <!-- EN-TÊTE -->
        <div class="report-header">
            <div class="header-title">
                <h1>Rapport de Session <em>#{{ $session->id }}</em></h1>
                <p>
                    <i class="fas fa-circle"></i>
                    {{ now()->format('d/m/Y H:i') }} • {{ auth()->user()->name }}
                </p>
            </div>
            
            <div class="header-badge {{ $session->status }}">
                <i class="fas fa-{{ $session->status == 'active' ? 'play' : 'check-circle' }}"></i>
                {{ $session->status == 'active' ? 'Session active' : 'Fermée' }}
            </div>
        </div>

        <!-- INFORMATIONS GÉNÉRALES -->
        <div class="info-grid">
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-user"></i></div>
                <div class="info-content">
                    <div class="info-label">RÉCEPTIONNISTE</div>
                    <div class="info-value">{{ $session->user->name }}</div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon"><i class="fas fa-calendar"></i></div>
                <div class="info-content">
                    <div class="info-label">DATE</div>
                    <div class="info-value">{{ $session->start_time->format('d/m/Y') }}</div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon"><i class="fas fa-clock"></i></div>
                <div class="info-content">
                    <div class="info-label">HORAIRES</div>
                    <div class="info-value">{{ $session->start_time->format('H:i') }}-{{ $session->end_time ? $session->end_time->format('H:i') : '...' }}</div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon"><i class="fas fa-coins"></i></div>
                <div class="info-content">
                    <div class="info-label">SOLDE INITIAL</div>
                    <div class="info-value">{{ number_format($session->initial_balance, 0, ',', ' ') }}</div>
                </div>
            </div>
        </div>

        <!-- KPI CARDS -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">Encaissé</span>
                    <span class="kpi-icon"><i class="fas fa-arrow-up"></i></span>
                </div>
                <div class="kpi-value">{{ number_format($totalCompleted, 0, ',', ' ') }}</div>
                <div class="kpi-footer">
                    <span>FCFA</span>
                    <span class="kpi-badge green">{{ $paymentCount }} paiements</span>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">Remboursé</span>
                    <span class="kpi-icon"><i class="fas fa-arrow-down"></i></span>
                </div>
                <div class="kpi-value" style="color: var(--red-500);">{{ number_format($totalRefunded, 0, ',', ' ') }}</div>
                <div class="kpi-footer">
                    <span>FCFA</span>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">Net</span>
                    <span class="kpi-icon"><i class="fas fa-wallet"></i></span>
                </div>
                <div class="kpi-value {{ $netTotal >= 0 ? 'green' : 'red' }}">{{ number_format($netTotal, 0, ',', ' ') }}</div>
                <div class="kpi-footer">
                    @if($session->balance_difference != 0)
                    <span>Écart: {{ number_format(abs($session->balance_difference), 0, ',', ' ') }}</span>
                    @endif
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">Moyenne</span>
                    <span class="kpi-icon"><i class="fas fa-chart-line"></i></span>
                </div>
                <div class="kpi-value">{{ $paymentCount > 0 ? number_format($totalCompleted / $paymentCount, 0, ',', ' ') : 0 }}</div>
                <div class="kpi-footer">
                    <span>FCFA/tx</span>
                </div>
            </div>
        </div>

        <!-- RÉPARTITION DES PAIEMENTS -->
        @if($byMethod->count() > 0)
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-chart-pie"></i>
                <h2>Répartition</h2>
            </div>
            <span class="section-count">{{ $paymentCount }} tx</span>
        </div>

        <div class="methods-grid">
            @foreach($byMethod as $method)
            @php
                $methodTotal = $method['total'] > 0 ? $method['total'] : abs($method['total']);
                $percentage = $paymentCount > 0 ? round(($method['count'] / $paymentCount) * 100) : 0;
                $isPositive = $method['total'] > 0;
            @endphp
            <div class="method-card">
                <div class="method-name">{{ $method['method'] }}</div>
                <div class="method-stats">
                    <span class="method-amount {{ !$isPositive ? 'red' : '' }}">
                        {{ $isPositive ? number_format($method['total'], 0, ',', ' ') : '-' . number_format(abs($method['total']), 0, ',', ' ') }}
                    </span>
                    <span class="method-count">{{ $method['count'] }} tx</span>
                </div>
                <div class="method-progress">
                    <div class="method-progress-bar" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- TABLEAU DES PAIEMENTS -->
        @if($payments->count() > 0)
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-list"></i>
                <h2>Transactions</h2>
            </div>
        </div>

        <div class="table-container">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Réf.</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Méthode</th>
                            <th>Montant</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments->take(15) as $payment)
                        @php
                            $isCompleted = $payment->status == 'completed';
                            $isPositive = $payment->amount > 0;
                            
                            $methodClass = 'cash';
                            $methodIcon = 'fa-money-bill-wave';
                            
                            if($payment->payment_method == 'card' || $payment->payment_method == 'fedapay') {
                                $methodClass = 'card';
                                $methodIcon = 'fa-credit-card';
                            } elseif($payment->payment_method == 'mobile_money') {
                                $methodClass = 'mobile';
                                $methodIcon = 'fa-mobile-alt';
                            }
                        @endphp
                        <tr>
                            <td><span style="font-family: var(--mono);">{{ substr($payment->reference, -8) }}</span></td>
                            <td>{{ $payment->created_at->format('d/m H:i') }}</td>
                            <td>
                                @if($payment->transaction && $payment->transaction->customer)
                                    {{ Str::limit($payment->transaction->customer->name, 15) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <span class="method-badge">
                                    <i class="fas {{ $methodIcon }}"></i>
                                </span>
                            </td>
                            <td>
                                <span class="amount {{ $isPositive ? 'positive' : 'negative' }}">
                                    {{ $isPositive ? '+' : '-' }} {{ number_format(abs($payment->amount), 0, ',', ' ') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $isCompleted ? 'green' : '' }}">
                                    {{ $isCompleted ? '✓' : '⏱' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="table-footer">
                <div class="table-totals">
                    <div>
                        <span class="total-label">Total</span>
                        <span class="total-value">{{ $paymentCount }}</span>
                    </div>
                    <div>
                        <span class="total-label">Encaissé</span>
                        <span class="total-value green">{{ number_format($totalCompleted, 0, ',', ' ') }}</span>
                    </div>
                    <div>
                        <span class="total-label">Remboursé</span>
                        <span class="total-value red">{{ number_format($totalRefunded, 0, ',', ' ') }}</span>
                    </div>
                    <div>
                        <span class="total-label">Net</span>
                        <span class="total-value {{ $netTotal >= 0 ? 'green' : 'red' }}">{{ number_format($netTotal, 0, ',', ' ') }}</span>
                    </div>
                </div>
                <div>
                    <span class="badge green">✓ {{ $payments->where('status', 'completed')->count() }}</span>
                    <span class="badge">⏱ {{ $payments->where('status', 'pending')->count() }}</span>
                </div>
            </div>
        </div>
        @endif

        <!-- RÉSUMÉ FINANCIER -->
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Solde initial</div>
                <div class="summary-value">{{ number_format($session->initial_balance, 0, ',', ' ') }}</div>
            </div>
            
            <div class="summary-item">
                <div class="summary-label">Total encaissé</div>
                <div class="summary-value green">{{ number_format($totalCompleted, 0, ',', ' ') }}</div>
            </div>
            
            <div class="summary-item">
                <div class="summary-label">Total remboursé</div>
                <div class="summary-value red">{{ number_format($totalRefunded, 0, ',', ' ') }}</div>
            </div>
            
            <div class="summary-item">
                <div class="summary-label">Solde final</div>
                <div class="summary-value {{ $netTotal >= 0 ? 'green' : 'red' }}">{{ number_format($netTotal + $session->initial_balance, 0, ',', ' ') }}</div>
            </div>
        </div>

        <!-- SIGNATURES -->
        <div class="report-footer">
            <div class="signatures">
                <div class="signature-item">
                    <div class="signature-title">Réceptionniste</div>
                    <div class="signature-line"></div>
                    <span class="signature-name">{{ $session->user->name }}</span>
                </div>
                <div class="signature-item">
                    <div class="signature-title">Supérieur</div>
                    <div class="signature-line"></div>
                    <span class="signature-empty"></span>
                </div>
                <div class="signature-item">
                    <div class="signature-title">Cachet</div>
                    <div class="signature-line"></div>
                    <span class="signature-empty"></span>
                </div>
            </div>
            
            <div class="footer-actions">
                <a href="{{ route('cashier.sessions.show', $session) }}" class="btn btn-gray">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
                <button onclick="window.print()" class="btn btn-green">
                    <i class="fas fa-print"></i> Imprimer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function exportToPDF() { window.print(); }
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('tbody tr');
    if (rows.length > 18) {
        for (let i = 18; i < rows.length; i++) rows[i].style.display = 'none';
    }
});
</script>
@endsection