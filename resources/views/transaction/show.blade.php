@extends('template.master')
@section('title', __('show.page_title', ['id' => $transaction->id]))
@section('content')

<style>
/* ═══════════════════════════════════════════════════════════════
   DESIGN MODERNE - MÊME FONCTIONNALITÉS
═══════════════════════════════════════════════════════════════════ */
:root {
    --primary-50: #ecfdf5;
    --primary-100: var(--g100);
    --primary-400: var(--g400);
    --primary-500: var(--g500);
    --primary-600: var(--g600);
    --primary-700: var(--g700);
    --primary-800: var(--g800);

    --amber-50: #fffbeb;
    --amber-100: #fef3c7;
    --amber-400: #fbbf24;
    --amber-500: #f59e0b;
    --amber-600: #d97706;

    --blue-50: #eff6ff;
    --blue-100: #dbeafe;
    --blue-500: #3b82f6;
    --blue-600: #2563eb;

    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;

    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
}

* { box-sizing: border-box; }

.detail-page {
    background: var(--gray-50);
    min-height: 100vh;
    padding: 24px 32px;
    font-family: 'Inter', system-ui, sans-serif;
}

/* Breadcrumb */
.breadcrumb-custom {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.813rem;
    color: var(--gray-400);
    margin-bottom: 24px;
    flex-wrap: wrap;
}

.breadcrumb-custom a {
    color: var(--gray-400);
    text-decoration: none;
}

.breadcrumb-custom a:hover {
    color: var(--primary-600);
}

.breadcrumb-custom .separator {
    color: var(--gray-300);
}

.breadcrumb-custom .current {
    color: var(--gray-600);
    font-weight: 500;
}

/* En-tête */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 16px;
}

.header-title {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.header-title h1 {
    font-size: 1.875rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
}

.header-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 10px rgb(from var(--g600) r g b / 0.3);
}

/* Info badge heures */
.info-badge {
    background: var(--blue-50);
    color: var(--blue-600);
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid var(--blue-200);
}

/* Boutons */
.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 500;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-primary-modern {
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    color: white;
    box-shadow: 0 4px 6px -1px rgb(from var(--g600) r g b / 0.3);
}

.btn-primary-modern:hover {
    background: linear-gradient(135deg, var(--primary-800), var(--primary-600));
    transform: translateY(-1px);
    color: white;
    text-decoration: none;
}

.btn-success-modern {
    background: var(--primary-600);
    color: white;
}

.btn-success-modern:hover {
    background: var(--primary-700);
    transform: translateY(-1px);
}

.btn-warning-modern {
    background: var(--amber-500);
    color: white;
}

.btn-warning-modern:hover {
    background: var(--amber-600);
    transform: translateY(-1px);
}

.btn-info-modern {
    background: var(--blue-500);
    color: white;
}

.btn-info-modern:hover {
    background: var(--blue-600);
    transform: translateY(-1px);
}

.btn-outline-modern {
    background: var(--white, #fff);
    color: var(--gray-700);
    border: 1px solid var(--gray-200);
}

.btn-outline-modern:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-900);
    transform: translateY(-1px);
    text-decoration: none;
}

.btn-outline-danger-modern {
    background: var(--white, #fff);
    color: #ef4444;
    border: 1px solid #ef4444;
}

.btn-outline-danger-modern:hover {
    background: #ef4444;
    color: white;
}

.btn-sm {
    padding: 6px 14px;
    font-size: 0.813rem;
}

.btn-modern:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

/* Badges statut */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 0.813rem;
    font-weight: 600;
}

.status-reservation {
    background: var(--amber-100);
    color: var(--amber-700);
    border: 1px solid var(--amber-200);
}

.status-active {
    background: var(--primary-100);
    color: var(--primary-700);
    border: 1px solid var(--primary-200);
}

.status-completed {
    background: var(--blue-100);
    color: var(--blue-700);
    border: 1px solid var(--blue-200);
}

.status-cancelled {
    background: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}

.status-no_show {
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1px solid var(--gray-200);
}

/* Badge late checkout */
.badge-late {
    background: var(--amber-100);
    color: var(--amber-700);
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid var(--amber-200);
}

/* Badge early checkout */
.badge-early {
    background: var(--blue-100);
    color: var(--blue-700);
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid var(--blue-200);
}

/* Badge paiement en attente */
.badge-pending {
    background: #fff3cd;
    color: #856404;
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid #ffeeba;
}

/* Cartes */
.detail-card {
    background: var(--white, #fff);
    border-radius: 20px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 20px;
}

.detail-card:hover {
    box-shadow: var(--shadow-md);
}

.card-header {
    padding: 16px 24px;
    border-bottom: 1px solid var(--gray-100);
    background: var(--white, #fff);
}

.card-header h5 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-700);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-header h5 i {
    color: var(--primary-500);
}

.card-body {
    padding: 24px;
}

/* Labels */
.detail-label {
    font-size: 0.688rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--gray-500);
    margin-bottom: 4px;
}

.detail-value {
    font-size: 0.938rem;
    font-weight: 500;
    color: var(--gray-800);
    margin-bottom: 12px;
}

/* Avatar client */
.client-avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-600), var(--primary-400));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.client-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

/* Badge chambre */
.room-badge-large {
    background: var(--primary-50);
    color: var(--primary-700);
    font-weight: 700;
    padding: 8px 24px;
    border-radius: 40px;
    font-size: 1.25rem;
    display: inline-block;
    border: 1px solid var(--primary-200);
}

/* Timeline */
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 11px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--gray-200);
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -19px;
    top: 6px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--primary-500);
    border: 2px solid white;
}

/* Stat boxes */
.stat-box {
    background: var(--gray-50);
    border-radius: 12px;
    padding: 16px;
    text-align: center;
    border: 1px solid var(--gray-200);
}

.stat-label {
    font-size: 0.688rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--gray-500);
    margin-bottom: 4px;
}

.stat-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--gray-800);
}

.stat-value-success {
    color: var(--primary-600);
}

.stat-value-danger {
    color: #ef4444;
}

.stat-value-primary {
    color: var(--blue-600);
}

.stat-value-warning {
    color: var(--amber-600);
}

/* Sélecteur statut */
.status-select {
    padding: 8px 16px;
    border-radius: 10px;
    border: 1px solid var(--gray-200);
    background: var(--white, #fff);
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--gray-700);
    min-width: 180px;
}

.status-select:focus {
    outline: none;
    border-color: var(--primary-500);
}

/* Alertes statut */
.alert-status {
    border-radius: 16px;
    padding: 20px 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    border-left: 4px solid;
}

.alert-status-reservation {
    border-left-color: var(--amber-500);
    background: var(--amber-50);
}

.alert-status-active {
    border-left-color: var(--primary-500);
    background: var(--primary-50);
}

.alert-status-completed {
    border-left-color: var(--blue-500);
    background: var(--blue-50);
}

.alert-status-cancelled {
    border-left-color: #ef4444;
    background: #fee2e2;
}

.alert-status-no_show {
    border-left-color: var(--gray-500);
    background: var(--gray-100);
}

/* Divider */
.divider {
    height: 1px;
    background: var(--gray-200);
    margin: 20px 0;
}

/* Actions rapides */
.quick-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 24px;
}

/* Badge paiement */
.payment-status-paid {
    color: var(--primary-600);
    font-weight: 600;
}

.payment-status-pending {
    color: var(--amber-600);
    font-weight: 600;
}

/* Toast notification */
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    max-width: 400px;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Loader */
.loader {
    border: 3px solid #f3f3f3;
    border-radius: 50%;
    border-top: 3px solid var(--primary-500);
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin: 20px auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.alert.alert-success {
    border-radius: 12px;
    background: var(--primary-50);
    border-color: var(--primary-200);
    color: var(--primary-800);
}

.alert.alert-danger {
    border-radius: 12px;
    background: #fee2e2;
    border-color: #fecaca;
    color: #b91c1c;
}

.alert.alert-warning {
    border-radius: 12px;
    background: #fff3cd;
    border-color: #ffeeba;
    color: #856404;
}

.modal-content {
    border-radius: 20px;
    border: none;
}

.modal-header {
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
}

.modal-footer {
    background: var(--gray-50);
    border-top: 1px solid var(--gray-200);
}
</style>

<div class="detail-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-custom">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home fa-xs me-1"></i>Dashboard</a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('transaction.index') }}">{{ __('show.reservations') }}</a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">#{{ $transaction->id }}</span>
    </div>

    <!-- En-tête -->
    <div class="page-header">
        <div class="header-title">
            <span class="header-icon">
                <i class="fas fa-calendar-check"></i>
            </span>
            <h1>{{ __('show.reservation_title', ['id' => $transaction->id]) }}</h1>
            <span class="info-badge">
                <i class="fas fa-clock"></i> {{ __('show.check_in_out_badge') }}
            </span>
            
            {{-- Affichage si late checkout --}}
            @if($transaction->late_checkout)
                <span class="badge-late">
                    <i class="fas fa-clock"></i> Late checkout: {{ $transaction->expected_checkout_time }}
                    @if($transaction->late_checkout_fee)
                        (+{{ number_format($transaction->late_checkout_fee, 0, ',', ' ') }} FCFA)
                    @endif
                </span>
            @endif
            
            {{-- Affichage si early checkout --}}
            @if($transaction->early_checkout)
                <span class="badge-early">
                    <i class="fas fa-clock"></i> {{ __('show.early_checkout') }} - {{ __('show.early_checkout_departure') }}
                    @if($transaction->early_checkout_refund)
                        ({{ __('show.refunded', ['amount' => number_format($transaction->early_checkout_refund, 0, ',', ' ')]) }})
                    @endif
                </span>
            @endif
        </div>
        
        <div class="d-flex gap-2 flex-wrap">
            @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']))
            <form action="{{ route('transaction.updateStatus', $transaction) }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <select name="status" class="status-select">
                    <option value="reservation" {{ $transaction->status == 'reservation' ? 'selected' : '' }}>{{ __('show.status_reservation') }}</option>
                    <option value="active" {{ $transaction->status == 'active' ? 'selected' : '' }}>{{ __('show.status_active') }}</option>
                    <option value="completed" {{ $transaction->status == 'completed' ? 'selected' : '' }}>{{ __('show.status_completed') }}</option>
                    <option value="cancelled" {{ $transaction->status == 'cancelled' ? 'selected' : '' }}>{{ __('show.status_cancelled') }}</option>
                    <option value="no_show" {{ $transaction->status == 'no_show' ? 'selected' : '' }}>{{ __('show.status_no_show') }}</option>
                </select>
            </form>
            @endif
            
            <a href="{{ route('transaction.index') }}" class="btn-modern btn-outline-modern">
                <i class="fas fa-arrow-left me-2"></i>{{ __('show.back') }}
            </a>
        </div>
    </div>

    <!-- Messages de session -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {!! session('success') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    
    @if(session('error') || session('failed'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') ?? session('failed') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    
    @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    
    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Alerte statut avec heure mise à jour -->
    @if($transaction->status == 'reservation')
    <div class="alert-status alert-status-reservation">
        <i class="fas fa-calendar-check fa-2x" style="color: var(--amber-600);"></i>
        <div>
            <strong class="d-block mb-1">{{ __('show.reservation_heading') }}</strong>
            <p class="mb-0 small">{{ __('show.expected_arrival', ['date' => \Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y')]) }}</p>
        </div>
    </div>
    @elseif($transaction->status == 'active')
    <div class="alert-status alert-status-active">
        <i class="fas fa-bed fa-2x" style="color: var(--primary-600);"></i>
        <div>
            <strong class="d-block mb-1">{{ __('show.active_heading') }}</strong>
            <p class="mb-0 small">
                {{ __('show.expected_departure') }}
                <strong>
                    {{ \Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y') }} 
                    @if($transaction->late_checkout)
                        à <span style="color: var(--amber-600);">{{ $transaction->expected_checkout_time }}</span>
                        @if($transaction->late_checkout_fee)
                            <span class="badge-late" style="margin-left: 8px;">+{{ number_format($transaction->late_checkout_fee, 0, ',', ' ') }} FCFA</span>
                        @endif
                    @elseif($transaction->early_checkout)
                        <span class="badge-early" style="margin-left: 8px;">{{ __('show.early_checkout_abbrev') }}</span>
                    @else
                        à 12h00
                    @endif
                </strong>
            </p>
        </div>
    </div>
    @elseif($transaction->status == 'completed')
    <div class="alert-status alert-status-completed">
        <i class="fas fa-check-circle fa-2x" style="color: var(--blue-600);"></i>
        <div>
            <strong class="d-block mb-1">{{ __('show.completed_heading') }}</strong>
            <p class="mb-0 small">
                {{ __('show.client_left_on', ['date' => \Carbon\Carbon::parse($transaction->check_out_actual ?? $transaction->check_out)->translatedFormat('d/m/Y H:i')]) }}
                @if($transaction->late_checkout)
                    <br><span class="badge-late"><i class="fas fa-clock"></i> {{ __('show.late_checkout_label', ['time' => $transaction->expected_checkout_time]) }} ({{ number_format($transaction->late_checkout_fee, 0, ',', ' ') }} FCFA)</span>
                @endif
                @if($transaction->early_checkout)
                    <br><span class="badge-early"><i class="fas fa-clock"></i> {{ __('show.early_checkout') }} - {{ __('show.early_checkout_departure') }}</span>
                    @if($transaction->early_checkout_refund)
                        <br><small>{{ __('show.refunded', ['amount' => number_format($transaction->early_checkout_refund, 0, ',', ' ')]) }}</small>
                    @endif
                @endif
            </p>
        </div>
    </div>
    @elseif($transaction->status == 'cancelled')
    <div class="alert-status alert-status-cancelled">
        <i class="fas fa-ban fa-2x" style="color: #b91c1c;"></i>
        <div>
            <strong class="d-block mb-1">{{ __('show.cancelled_heading') }}</strong>
            @if($transaction->cancelled_at)
            <p class="mb-0 small">{{ __('show.cancelled_on', ['date' => \Carbon\Carbon::parse($transaction->cancelled_at)->translatedFormat('d/m/Y H:i')]) }}
                @if($transaction->cancel_reason)
                <br>{{ __('show.cancel_reason', ['reason' => $transaction->cancel_reason]) }}
                @endif
            </p>
            @endif
        </div>
    </div>
    @elseif($transaction->status == 'no_show')
    <div class="alert-status alert-status-no_show">
        <i class="fas fa-user-slash fa-2x" style="color: var(--gray-500);"></i>
        <div>
            <strong class="d-block mb-1">{{ __('show.no_show_heading') }}</strong>
            <p class="mb-0 small">{{ __('show.no_show_text') }}</p>
        </div>
    </div>
    @endif

    <!-- Actions rapides -->
    @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']))
    <div class="quick-actions">
        {{-- Contacter le client sur WhatsApp (messages pré-remplis, envoi en un tap) --}}
        @php
            $waHotel = auth()->user()->hotel;
            $waPhone = optional($transaction->customer)->phone;
            $waConfirm = $waHotel ? \App\Support\GuestMessages::link($waPhone, \App\Support\GuestMessages::confirmation($waHotel, $transaction)) : null;
            $waReminder = $waHotel ? \App\Support\GuestMessages::link($waPhone, \App\Support\GuestMessages::checkInReminder($waHotel, $transaction)) : null;
            $waPayment = ($waHotel && (float) ($transaction->total_payment ?? 0) > 0)
                ? \App\Support\GuestMessages::link($waPhone, \App\Support\GuestMessages::paymentReceived($waHotel, $transaction)) : null;
            $waCheckin = ($waHotel && ! in_array($transaction->status, ['completed','cancelled','no_show']) && ! $transaction->preCheckinDone())
                ? \App\Support\GuestMessages::link($waPhone, \App\Support\GuestMessages::preCheckinInvite($waHotel, $transaction)) : null;
        @endphp
        @if($waConfirm)
            <div class="dropdown d-inline">
                <button type="button" class="btn-modern" style="background:#25d366;color:#fff" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fab fa-whatsapp me-1"></i>{{ __('show.whatsapp_client') ?? 'WhatsApp client' }}
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ $waConfirm }}" target="_blank" rel="noopener"><i class="fas fa-circle-check me-2 text-success"></i>{{ __('show.whatsapp_confirmation') ?? 'Confirmation de réservation' }}</a></li>
                    <li><a class="dropdown-item" href="{{ $waReminder }}" target="_blank" rel="noopener"><i class="fas fa-clock me-2 text-warning"></i>{{ __('show.whatsapp_reminder') ?? "Rappel d'arrivée" }}</a></li>
                    @if($waPayment)
                        <li><a class="dropdown-item" href="{{ $waPayment }}" target="_blank" rel="noopener"><i class="fas fa-coins me-2 text-primary"></i>{{ __('show.whatsapp_payment') ?? 'Accusé de paiement' }}</a></li>
                    @endif
                    @if($waCheckin)
                        <li><a class="dropdown-item" href="{{ $waCheckin }}" target="_blank" rel="noopener"><i class="fas fa-id-card me-2" style="color:#6366f1"></i>Inviter au pré-check-in</a></li>
                    @endif
                </ul>
            </div>
            @if($waHotel && ! in_array($transaction->status, ['completed','cancelled','no_show']))
                <button type="button" class="btn-modern btn-outline-modern" data-bs-toggle="modal" data-bs-target="#preCheckinModal">
                    <i class="fas fa-qrcode me-1"></i>Pré-check-in
                    @if($transaction->preCheckinDone())<span class="badge bg-success ms-1">Fait</span>@endif
                </button>
            @endif
        @endif

        @if($transaction->status == 'reservation')
            @php
                $now = \Carbon\Carbon::now();
                $checkInDateTime = \Carbon\Carbon::parse($transaction->check_in)->setTime(12, 0, 0);
            @endphp
            @if($now->gte($checkInDateTime))
                <form action="{{ route('transaction.mark-arrived', $transaction) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn-modern btn-success-modern">
                        <i class="fas fa-sign-in-alt me-1"></i>{{ __('show.arrival') }}
                    </button>
                </form>
            @else
                <button type="button" class="btn-modern btn-outline-modern" disabled>
                    <i class="fas fa-clock me-1"></i>{{ __('show.arrival_at_12h') }}
                </button>
            @endif
        @endif
        
        @if($transaction->status == 'active')
            @php
                $now = \Carbon\Carbon::now();
                $checkOutDate = \Carbon\Carbon::parse($transaction->check_out)->setTime(12, 0, 0);
                $checkOutLargess = $checkOutDate->copy()->setTime(14, 0, 0);
                $lateCheckoutEnd = $checkOutDate->copy()->setTime(20, 0, 0);
                $today = \Carbon\Carbon::today();
                $scheduledCheckOut = \Carbon\Carbon::parse($transaction->check_out)->startOfDay();
                
                // ✅ Vérification plus robuste du paiement late checkout
                $latePayment = null;
                $isLatePaid = false;
                $hasPendingLatePayment = false;
                
                if ($transaction->late_checkout && $transaction->late_checkout_fee > 0) {
                    foreach ($transaction->payments as $payment) {
                        $isLateReference = $payment->reference && str_contains($payment->reference, 'LATE-');
                        $isLateDescription = $payment->description && (
                            str_contains($payment->description, 'Late checkout') || 
                            str_contains($payment->description, 'late checkout')
                        );
                        
                        if ($isLateReference || $isLateDescription) {
                            if ($payment->status == 'completed') {
                                $latePayment = $payment;
                                $isLatePaid = true;
                                break;
                            } elseif ($payment->status == 'pending') {
                                $hasPendingLatePayment = true;
                                $latePayment = $payment;
                            }
                        }
                    }
                }
            @endphp
            
            {{-- BOUTON EARLY CHECKOUT (avant la date prévue) --}}
            @if($today->lt($scheduledCheckOut))
                <button type="button" class="btn-modern btn-info-modern" data-bs-toggle="modal" data-bs-target="#earlyCheckoutModal">
                    <i class="fas fa-clock me-1"></i>Early checkout
                </button>
            @endif
            
            {{-- 12h - 14h : Départ avec largesse (GRATUIT) --}}
            @if($now->gte($checkOutDate) && $now->lte($checkOutLargess))
                <form action="{{ route('transaction.mark-departed', $transaction) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn-modern btn-success-modern">
                        <i class="fas fa-sign-out-alt me-1"></i>{{ __('show.departure_largesse') }}
                    </button>
                </form>
            
            {{-- 14h - 20h : Gestion du late checkout --}}
            @elseif($now->gt($checkOutLargess) && $now->lt($lateCheckoutEnd))
                @if(!$transaction->late_checkout)
                    {{-- Pas encore en late checkout --}}
                    <button type="button" class="btn-modern btn-warning-modern" data-bs-toggle="modal" data-bs-target="#lateCheckoutModal">
                        <i class="fas fa-clock me-1"></i>Late checkout
                    </button>
                @else
                    {{-- Déjà en late checkout --}}
                    @if($isLatePaid)
                        {{-- ✅ Late checkout payé → bouton départ actif --}}
                        <form action="{{ route('transaction.mark-departed', $transaction) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-modern btn-success-modern">
                                <i class="fas fa-sign-out-alt me-1"></i>{{ __('show.departure_late_checkout') }}
                            </button>
                        </form>
                    @elseif($hasPendingLatePayment)
                        {{-- ⏳ Late checkout en attente de paiement --}}
                        <div class="d-inline-block" data-bs-toggle="tooltip" 
                             title="{{ __('show.late_checkout_tooltip') }}">
                            <span class="btn-modern btn-outline-modern disabled">
                                <i class="fas fa-clock me-1"></i>{{ __('show.departure_pending_payment') }}
                            </span>
                        </div>
                    @else
                        {{-- ❌ Late checkout non payé --}}
                        <div class="d-inline-block" data-bs-toggle="tooltip" 
                             title="{{ __('show.late_fee_unpaid_tooltip', ['amount' => number_format($transaction->late_checkout_fee, 0, ',', ' ')]) }}">
                            <span class="btn-modern btn-outline-modern disabled">
                                <i class="fas fa-ban me-1"></i>{{ __('show.departure_blocked') }}
                            </span>
                        </div>
                    @endif
                @endif
            
            {{-- Après 20h : Forcer prolongation --}}
            @elseif($now->gte($lateCheckoutEnd) && !$transaction->late_checkout)
                <a href="{{ route('transaction.extend', $transaction) }}" class="btn-modern btn-warning-modern">
                    <i class="fas fa-calendar-plus me-1"></i>{{ __('show.extend_one_night') }}
                </a>
            
            {{-- Après 20h avec late checkout --}}
            @elseif($now->gte($lateCheckoutEnd) && $transaction->late_checkout)
                <span class="btn-modern btn-outline-modern disabled" 
                      data-bs-toggle="tooltip" 
                      title="{{ __('show.departure_after_20h_tooltip') }}">
                    <i class="fas fa-clock me-1"></i>{{ __('show.departure_impossible') }}
                </span>
            
            {{-- Déjà en late checkout mais avant 14h (cas normal) --}}
            @elseif($transaction->late_checkout && $now->lt($checkOutLargess))
                <span class="btn-modern btn-outline-modern disabled" 
                      data-bs-toggle="tooltip" 
                      title="{{ __('show.departure_at_time_tooltip', ['time' => $transaction->expected_checkout_time]) }}">
                    <i class="fas fa-clock me-1"></i>{{ __('show.departure_at', ['time' => $transaction->expected_checkout_time]) }}
                </span>
            
            {{-- Autres cas --}}
            @else
                <button type="button" class="btn-modern btn-outline-modern" disabled>
                    <i class="fas fa-clock me-1"></i>{{ __('show.departure_at_12h') }}
                </button>
            @endif
        @endif
        
        @if(in_array($transaction->status, ['reservation', 'active']))
        <a href="{{ route('transaction.extend', $transaction) }}" class="btn-modern btn-warning-modern">
            <i class="fas fa-calendar-plus me-1"></i>{{ __('show.extend') }}
        </a>
        @endif
        
        @if(!in_array($transaction->status, ['cancelled', 'no_show', 'completed']))
        <a href="{{ route('transaction.edit', $transaction) }}" class="btn-modern btn-outline-modern">
            <i class="fas fa-edit me-1"></i>{{ __('show.edit') }}
        </a>
        @endif
        
        @if($remaining > 0 && !in_array($transaction->status, ['cancelled', 'no_show']))
        <a href="{{ route('transaction.payment.create', $transaction) }}" class="btn-modern btn-primary-modern">
            <i class="fas fa-money-bill-wave me-1"></i>{{ __('show.payment') }}
        </a>
        @endif
        
        @if(!in_array($transaction->status, ['cancelled', 'no_show', 'completed']))
        <button type="button" class="btn-modern btn-outline-danger-modern" 
                data-bs-toggle="modal" data-bs-target="#cancelModal">
            <i class="fas fa-ban me-1"></i>{{ __('show.cancel') }}
        </button>
        @endif
    </div>
    @endif

    <div class="row">
        <!-- Colonne gauche -->
        <div class="col-lg-8">
            <!-- Client -->
            <div class="detail-card">
                <div class="card-header">
                    <h5><i class="fas fa-user"></i>{{ __('show.customer_info') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="client-avatar">
                            @if($transaction->customer->avatar)
                                <img src="{{ $transaction->customer->avatar_url }}" alt="{{ $transaction->customer->name }}">
                            @else
                                {{ strtoupper(substr($transaction->customer->name, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <h4 class="mb-1" style="color: var(--gray-800); font-weight: 600;">{{ $transaction->customer->name }}</h4>
                            <p class="text-muted small mb-0">{{ $transaction->customer->email ?? __('show.email_not_set') }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p class="detail-label">{{ __('show.phone') }}</p>
                            <p class="detail-value">{{ $transaction->customer->phone ?? __('show.not_specified') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="detail-label">{{ __('show.nic_id') }}</p>
                            <p class="detail-value">{{ $transaction->customer->nik ?? __('show.not_specified') }}</p>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-2">
                        <a href="{{ route('customer.show', $transaction->customer) }}" class="btn-modern btn-outline-modern btn-sm">
                            <i class="fas fa-eye me-1"></i>{{ __('show.view_profile') }}
                        </a>
                        <a href="{{ route('transaction.reservation.customerReservations', $transaction->customer) }}" class="btn-modern btn-outline-modern btn-sm">
                            <i class="fas fa-history me-1"></i>{{ __('show.history') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Chambre et dates avec heure mise à jour -->
            <div class="detail-card">
                <div class="card-header">
                    <h5><i class="fas fa-bed"></i>{{ __('show.stay_info') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 text-center mb-3 mb-md-0">
                            <p class="detail-label">{{ __('show.room') }}</p>
                            <span class="room-badge-large">{{ $transaction->room->number }}</span>
                            <p class="text-muted small mt-2">{{ $transaction->room->type->name ?? __('show.type_not_specified') }}</p>
                        </div>
                        <div class="col-md-6 text-center">
                            <p class="detail-label">{{ __('show.stay_duration') }}</p>
                            <span class="room-badge-large" style="background: var(--gray-100); color: var(--gray-700); border-color: var(--gray-200);">
                                {{ $nights }} {{ __('show.nights') }}
                                @if($transaction->late_checkout)
                                    <br><small class="text-warning">({{ __('show.in_late_checkout') }})</small>
                                @elseif($transaction->early_checkout)
                                    <br><small class="text-info">({{ __('show.with_early_checkout') }})</small>
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p class="detail-label">{{ __('show.arrival_date') }}</p>
                            <p class="detail-value">
                                <i class="fas fa-calendar-check me-2" style="color: var(--primary-500);"></i>
                                {{ \Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y') }}
                                <span class="text-muted ms-2">12:00</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="detail-label">{{ __('show.departure_date') }}</p>
                            <p class="detail-value">
                                <i class="fas fa-calendar-times me-2" style="color: #ef4444;"></i>
                                {{ \Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y') }}
                                <span class="text-muted ms-2">
                                    @if($transaction->late_checkout)
                                        <strong style="color: var(--amber-600);">{{ $transaction->expected_checkout_time }}</strong>
                                    @elseif($transaction->early_checkout)
                                        <strong style="color: var(--blue-600);">Early checkout</strong>
                                    @else
                                        12:00
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="divider"></div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p class="detail-label">{{ __('show.room_status') }}</p>
                            <p class="detail-value">
                                @if($transaction->room->roomStatus)
                                <span class="status-badge {{ $transaction->room->roomStatus->name == 'Occupée' ? 'status-active' : ($transaction->room->roomStatus->name == 'Disponible' ? 'status-completed' : 'status-reservation') }}">
                                    {{ $transaction->room->roomStatus->name }}
                                </span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="detail-label">{{ __('show.reservation_status') }}</p>
                            <p class="detail-value">
                                <span class="status-badge status-{{ $transaction->status }}">
                                    {{ $transaction->status_label }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paiements avec affichage du supplément late checkout -->
            <div class="detail-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-money-bill-wave"></i>{{ __('show.payments') }}</h5>
                    <span class="status-badge {{ $isFullyPaid ? 'status-active' : ($remaining > 0 ? 'status-reservation' : 'status-completed') }}">
                        {{ $isFullyPaid ? __('show.settled') : ($remaining > 0 ? __('show.pending') : __('show.no_debt')) }}
                    </span>
                </div>
                <div class="card-body">
                    <!-- Résumé financier avec late checkout -->
                    <div class="row g-3 mb-4">
                        @php
                            $totalInitial = $totalPrice - ($transaction->late_checkout_fee ?? 0);
                        @endphp
                        
                        <div class="col-md-3">
                            <div class="stat-box">
                                <p class="stat-label">{{ __('show.initial_total') }}</p>
                                <p class="stat-value">{{ number_format($totalInitial, 0, ',', ' ') }} CFA</p>
                            </div>
                        </div>
                        
                        @if($transaction->late_checkout_fee > 0)
                        <div class="col-md-3">
                            <div class="stat-box" style="background: var(--amber-50); border-color: var(--amber-200);">
                                <p class="stat-label">{{ __('show.late_supplement') }}</p>
                                <p class="stat-value" style="color: var(--amber-600);">+ {{ number_format($transaction->late_checkout_fee, 0, ',', ' ') }} CFA</p>
                                <small class="text-muted">{{ $transaction->expected_checkout_time ?? 'N/A' }}</small>
                            </div>
                        </div>
                        @endif
                        
                        @if($transaction->early_checkout_refund > 0)
                        <div class="col-md-3">
                            <div class="stat-box" style="background: var(--blue-50); border-color: var(--blue-200);">
                                <p class="stat-label">{{ __('show.refund') }}</p>
                                <p class="stat-value" style="color: var(--blue-600);">- {{ number_format($transaction->early_checkout_refund, 0, ',', ' ') }} CFA</p>
                                <small class="text-muted">Early checkout</small>
                            </div>
                        </div>
                        @endif
                        
                        <div class="col-md-3">
                            <div class="stat-box">
                                <p class="stat-label">{{ __('show.final_total') }}</p>
                                <p class="stat-value stat-value-primary">{{ number_format($totalPrice, 0, ',', ' ') }} CFA</p>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="stat-box">
                                <p class="stat-label">{{ __('show.paid') }}</p>
                                <p class="stat-value stat-value-success">{{ number_format($totalPayment, 0, ',', ' ') }} CFA</p>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="stat-box">
                                <p class="stat-label">{{ __('show.remaining') }}</p>
                                <p class="stat-value {{ $remaining > 0 ? 'stat-value-danger' : 'stat-value-success' }}">
                                    @if($remaining > 0)
                                        {{ number_format($remaining, 0, ',', ' ') }} CFA
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Consommations Restaurant (Room Charge) -->
                    @php
                        $restOrders = $transaction->restaurantOrders()->where('payment_method', 'room_charge')->get();
                        $restTotal = $restOrders->where('status', 'delivered')->sum('total');
                    @endphp
                    
                    @if($restOrders->count() > 0)
                    <div class="divider"></div>
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="mb-3" style="font-weight: 600; color: var(--gray-700);">
                                <i class="fas fa-utensils me-2 text-primary"></i>{{ __('show.restaurant_consumptions') }}
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover border-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-0 small text-muted">{{ __('show.id') }}</th>
                                            <th class="border-0 small text-muted">{{ __('show.date') }}</th>
                                            <th class="border-0 small text-muted">{{ __('show.status') }}</th>
                                            <th class="border-0 small text-muted text-end">{{ __('show.amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($restOrders as $ro)
                                        <tr>
                                            <td class="align-middle">#{{ $ro->id }}</td>
                                            <td class="align-middle">{{ $ro->created_at->format('d/m/H:i') }}</td>
                                            <td class="align-middle">
                                                @if($ro->status === 'delivered')
                                                    <span class="badge bg-success-subtle text-success px-2 py-1">{{ __('show.delivered_on_bill') }}</span>
                                                @elseif($ro->status === 'paid')
                                                    <span class="badge bg-primary-subtle text-primary px-2 py-1">{{ __('show.paid') }}</span>
                                                @elseif($ro->status === 'cancelled')
                                                    <span class="badge bg-danger-subtle text-danger px-2 py-1">{{ __('show.status_cancelled') }}</span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning px-2 py-1">{{ $ro->status }} ({{ __('show.not_invoiced') }})</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-end fw-bold text-dark">{{ number_format($ro->total, 0, ',', ' ') }} CFA</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="border-top">
                                        <tr>
                                            <td colspan="3" class="text-end small text-muted">{{ __('show.total_added_to_bill') }}</td>
                                            <td class="text-end fw-bold text-primary">{{ number_format($restTotal, 0, ',', ' ') }} CFA</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Liste des paiements -->
                    @if($payments && $payments->count() > 0)
                        <p class="detail-label mb-3">{{ __('show.payment_history') }}</p>
                        <div class="timeline">
                            @foreach($payments as $payment)
                                @php
                                    $isLatePayment = (str_contains($payment->reference ?? '', 'LATE-') || 
                                                      str_contains($payment->description ?? '', 'Late checkout') ||
                                                      str_contains($payment->description ?? '', 'late checkout'));
                                    
                                    $isRefundPayment = (str_contains($payment->reference ?? '', 'REFUND-') || 
                                                       str_contains($payment->description ?? '', 'Remboursement') ||
                                                       $payment->amount < 0);
                                @endphp
                                <div class="timeline-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1" style="font-weight: 600;">
                                                Paiement #{{ $payment->id }}
                                                @if($isLatePayment)
                                                    <span class="badge-late" style="margin-left: 8px;">{{ __('show.late_checkout') }}</span>
                                                @endif
                                                @if($isRefundPayment)
                                                    <span class="badge-early" style="margin-left: 8px;">{{ __('show.refund_badge') }}</span>
                                                @endif
                                                @if($payment->status == 'pending')
                                                    <span class="badge-pending" style="margin-left: 8px;">
                                                        <i class="fas fa-clock me-1"></i>{{ __('show.pending') }}
                                                    </span>
                                                @elseif($payment->status == 'completed')
                                                    <span class="badge-late" style="background: var(--primary-100); color: var(--primary-700); margin-left: 8px;">
                                                        <i class="fas fa-check-circle me-1"></i>{{ __('show.paid') }}
                                                    </span>
                                                @endif
                                            </h6>
                                            <p class="text-muted small mb-1">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ \Carbon\Carbon::parse($payment->created_at)->translatedFormat('d/m/Y H:i') }}
                                            </p>
                                            @if($payment->payment_method)
                                                <p class="text-muted small mb-1">
                                                    <i class="fas fa-credit-card me-1"></i>
                                                    {{ ucfirst($payment->payment_method_label ?? $payment->payment_method) }}
                                                </p>
                                            @endif
                                            @if($payment->description)
                                                <p class="text-muted small mb-0">{{ __('show.note') }} {{ $payment->description }}</p>
                                            @endif
                                        </div>
                                        <div class="text-end">
                                            <p class="fw-bold mb-1" style="font-size: 1.1rem; {{ $payment->status == 'pending' ? 'color: var(--amber-600);' : ($payment->amount > 0 ? 'color: var(--primary-600);' : 'color: var(--blue-600);') }}">
                                                {{ $payment->amount > 0 ? '+' : '-' }} {{ number_format(abs($payment->amount), 0, ',', ' ') }} CFA
                                            </p>
                                            <a href="{{ route('payment.invoice', $payment) }}" class="btn-modern btn-outline-modern btn-sm" target="_blank">
                                                <i class="fas fa-receipt"></i> {{ __('show.receipt') }}
                                            </a>
                                            @if($payment->status == 'pending')
                                                <button onclick="markPaymentAsPaid({{ $payment->id }})" class="btn btn-sm btn-success mt-1">
                                                    <i class="fas fa-check"></i> {{ __('show.mark_as_paid') }}
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-money-bill-wave fa-3x mb-3" style="color: var(--gray-300);"></i>
                            <h5 style="color: var(--gray-600);">{{ __('show.no_payment') }}</h5>
                            <p class="text-muted small">{{ __('show.no_payment_text') }}</p>
                            @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']) && $remaining > 0 && !in_array($transaction->status, ['cancelled', 'no_show']))
                                <a href="{{ route('transaction.payment.create', $transaction) }}" class="btn-modern btn-primary-modern mt-2">
                                    <i class="fas fa-plus me-1"></i>{{ __('show.add_payment') }}
                                </a>
                            @endif
                        </div>
                    @endif
                    
                    {{-- Alert si late checkout non payé --}}
                    @if($transaction->late_checkout_fee > 0 && $transaction->status == 'active')
                        @php
                            $remainingWithLate = $totalPrice - $totalPayment;
                            $latePayment = $payments->first(function($p) {
                                return (($p->reference && str_contains($p->reference, 'LATE-')) || 
                                       ($p->description && (str_contains($p->description, 'Late checkout') || str_contains($p->description, 'late checkout'))));
                            });
                        @endphp
                        @if($remainingWithLate > 0 && (!$latePayment || $latePayment->status == 'pending'))
                            <div class="alert alert-warning mt-3" style="border-left: 4px solid var(--amber-500);">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div>
                                        <i class="fas fa-clock me-2" style="color: var(--amber-600);"></i>
                                        <strong>{{ __('show.late_checkout_registered') }}</strong><br>
                                        <span class="small">
                                            {{ __('show.departure_at', ['time' => $transaction->expected_checkout_time]) }} - 
                                            {{ __('show.late_supplement') }} {{ __('show.total') }}: <strong>{{ number_format($transaction->late_checkout_fee, 0, ',', ' ') }} FCFA</strong>
                                            @if($latePayment && $latePayment->status == 'pending')
                                                <br><span class="text-info"><i class="fas fa-info-circle me-1"></i>{{ __('show.late_checkout_pending_info') }}</span>
                                            @elseif(!$latePayment)
                                                <br><span class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>{{ __('show.no_payment_created') }}</span>
                                            @endif
                                        </span>
                                    </div>
                                    @if(!$latePayment)
                                        <a href="{{ route('transaction.payment.create', $transaction) }}?amount={{ $transaction->late_checkout_fee }}" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-money-bill-wave me-1"></i>{{ __('show.create_payment') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif
                    
                    {{-- Alert si early checkout avec remboursement --}}
                    @if($transaction->early_checkout && $transaction->early_checkout_refund > 0 && $transaction->status == 'active')
                        <div class="alert alert-info mt-3" style="border-left: 4px solid var(--blue-500); background: var(--blue-50);">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <i class="fas fa-clock me-2" style="color: var(--blue-600);"></i>
                                    <strong>{{ __('show.early_checkout_registered') }}</strong><br>
                                    <span class="small">
                                        {{ __('show.early_checkout_refund_text', ['amount' => number_format($transaction->early_checkout_refund, 0, ',', ' ')]) }}
                                        @if($transaction->early_checkout_reason)
                                            <br>{{ __('show.cancel_reason', ['reason' => $transaction->early_checkout_reason]) }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne droite -->
        <div class="col-lg-4">
            <!-- Actions rapides compactes -->
            <div class="detail-card">
                <div class="card-header">
                    <h5><i class="fas fa-bolt"></i>{{ __('show.quick_actions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('transaction.history', $transaction) }}" class="btn-modern btn-outline-modern w-100">
                            <i class="fas fa-history me-1"></i>{{ __('show.history') }}
                        </a>
                        
                        @if($payments && $payments->count() > 0)
                        <a href="{{ route('transaction.invoice', $transaction) }}" class="btn-modern btn-outline-modern w-100" target="_blank">
                            <i class="fas fa-file-invoice me-1"></i>{{ __('show.invoice') }}
                        </a>
                        @endif
                        
                        @if($transaction->status == 'cancelled' && in_array(auth()->user()->role, ['Super', 'Admin']))
                        <form action="{{ route('transaction.restore', $transaction) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-modern btn-warning-modern w-100" onclick="return confirm('{{ __('show.restore_confirm') }}')">
                                <i class="fas fa-undo me-1"></i>{{ __('show.restore') }}
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informations supplémentaires avec heure de départ -->
            <div class="detail-card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i>{{ __('show.details') }}</h5>
                </div>
                <div class="card-body">
                    <p class="detail-label">{{ __('show.number_of_guests') }}</p>
                    <p class="detail-value">{{ $transaction->person_count ?? 1 }} {{ __('show.persons') }}</p>
                    
                    <p class="detail-label">{{ __('show.price_per_night') }}</p>
                    <p class="detail-value">{{ number_format($transaction->room->price, 0, ',', ' ') }} CFA</p>
                    
                    @if($transaction->late_checkout_fee)
                    <p class="detail-label">{{ __('show.late_checkout_supplement') }}</p>
                    <p class="detail-value" style="color: var(--amber-600);">+ {{ number_format($transaction->late_checkout_fee, 0, ',', ' ') }} CFA</p>
                    @endif
                    
                    @if($transaction->early_checkout_refund)
                    <p class="detail-label">{{ __('show.early_checkout_refund_label') }}</p>
                    <p class="detail-value" style="color: var(--blue-600);">- {{ number_format($transaction->early_checkout_refund, 0, ',', ' ') }} CFA</p>
                    @endif
                    
                    {{-- Heure de départ effective --}}
                    @if($transaction->expected_checkout_time && $transaction->late_checkout)
                    <p class="detail-label">{{ __('show.departure_time') }}</p>
                    <p class="detail-value" style="color: var(--amber-600);">
                        <i class="fas fa-clock me-1"></i> {{ $transaction->expected_checkout_time }}
                    </p>
                    @endif
                    
                    <p class="detail-label">{{ __('show.created_on') }}</p>
                    <p class="detail-value">{{ \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('d/m/Y H:i') }}</p>
                    
                    @if($transaction->user)
                    <p class="detail-label">{{ __('show.created_by') }}</p>
                    <p class="detail-value">{{ $transaction->user->name }}</p>
                    @endif
                    
                    @if($transaction->updated_at != $transaction->created_at)
                    <p class="detail-label">{{ __('show.last_modified') }}</p>
                    <p class="detail-value">{{ \Carbon\Carbon::parse($transaction->updated_at)->translatedFormat('d/m/Y H:i') }}</p>
                    @endif
                    
                    @if($transaction->notes)
                    <div class="divider"></div>
                    <p class="detail-label">{{ __('show.notes') }}</p>
                    <p class="detail-value" style="white-space: pre-line;">{{ $transaction->notes }}</p>
                    @endif
                    
                    @if($transaction->early_checkout_reason)
                    <div class="divider"></div>
                    <p class="detail-label">{{ __('show.early_checkout_reason') }}</p>
                    <p class="detail-value" style="color: var(--blue-700);">{{ $transaction->early_checkout_reason }}</p>
                    @endif
                    
                    @if($transaction->checkout_notes && $transaction->late_checkout)
                    <div class="divider"></div>
                    <p class="detail-label">{{ __('show.departure_notes') }}</p>
                    <p class="detail-value" style="white-space: pre-line; color: var(--amber-700);">📝 {{ $transaction->checkout_notes }}</p>
                    @endif
                </div>
            </div>

            <!-- Statistiques -->
            <div class="detail-card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-bar"></i>{{ __('show.statistics') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="stat-box p-3">
                                <p class="stat-label">{{ __('show.nights_label') }}</p>
                                <p class="stat-value">{{ $nights }}</p>
                                @if($transaction->late_checkout)
                                    <small class="text-warning">+ late checkout</small>
                                @elseif($transaction->early_checkout)
                                    <small class="text-info">early checkout</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box p-3">
                                <p class="stat-label">{{ __('show.payments') }}</p>
                                <p class="stat-value">{{ $payments ? $payments->count() : 0 }}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box p-3">
                                <p class="stat-label">{{ __('show.total') }}</p>
                                <p class="stat-value stat-value-primary">{{ number_format($totalPrice, 0, ',', ' ') }} CFA</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box p-3">
                                <p class="stat-label">{{ __('show.paid') }}</p>
                                <p class="stat-value stat-value-success">{{ number_format($totalPayment, 0, ',', ' ') }} CFA</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($remaining > 0)
                    <div class="divider"></div>
                    <div class="text-center">
                        <p class="detail-label mb-1">{{ __('show.remaining_to_pay') }}</p>
                        <p class="stat-value stat-value-danger h4">{{ number_format($remaining, 0, ',', ' ') }} CFA</p>
                        @if($transaction->late_checkout_fee > 0)
                            <small class="text-muted">{{ __('show.remaining_with_late', ['amount' => number_format($transaction->late_checkout_fee, 0, ',', ' ')]) }}</small>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EARLY CHECKOUT --}}
@if($transaction->status == 'active' && \Carbon\Carbon::today()->lt(\Carbon\Carbon::parse($transaction->check_out)->startOfDay()) && !$transaction->early_checkout)
<div class="modal fade" id="earlyCheckoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--blue-500), var(--blue-400)); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-clock me-2"></i>
                    {{ __('show.early_checkout_title') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('transaction.early-checkout', $transaction) }}" method="POST">
                @csrf
                <div class="modal-body">
                    @php
                        $plannedNights = $nights;
                        $actualNights = \Carbon\Carbon::parse($transaction->check_in)->diffInDays(\Carbon\Carbon::today());
                        $nightsShort = $plannedNights - $actualNights;
                        $totalPaid = $totalPayment;
                        $roomPrice = $transaction->room->price;
                        $newTotalPrice = $roomPrice * $actualNights;
                        $potentialRefund = max(0, $totalPaid - $newTotalPrice);
                    @endphp
                    
                    <div class="alert alert-info mb-4" style="background: var(--blue-50); border-color: var(--blue-200);">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-info-circle fa-2x text-info"></i>
                            <div>
                                <strong>{{ __('show.stay_summary') }}</strong><br>
                                {{ __('show.client_label', ['name' => $transaction->customer->name]) }}<br>
                                {{ __('show.room_label', ['number' => $transaction->room->number ]) }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="stat-box">
                                <p class="stat-label">{{ __('show.planned_nights') }}</p>
                                <p class="stat-value">{{ $plannedNights }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-box">
                                <p class="stat-label">{{ __('show.actual_nights') }}</p>
                                <p class="stat-value">{{ $actualNights }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-box">
                                <p class="stat-label">{{ __('show.unused_nights') }}</p>
                                <p class="stat-value">{{ $nightsShort }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-box">
                                <p class="stat-label">{{ __('show.total_paid') }}</p>
                                <p class="stat-value stat-value-success">{{ number_format($totalPaid, 0, ',', ' ') }} FCFA</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-calculator me-2"></i>
                        <strong>{{ __('show.potential_refund_calc') }}</strong><br>
                        - {{ __('show.new_total') }} ({{ $actualNights }} {{ __('show.nights') }}): {{ number_format($newTotalPrice, 0, ',', ' ') }} FCFA<br>
                        - {{ __('show.already_paid') }}: {{ number_format($totalPaid, 0, ',', ' ') }} FCFA<br>
                        - {{ __('show.max_refund') }}: <strong class="text-success">{{ number_format($potentialRefund, 0, ',', ' ') }} FCFA</strong>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-hand-holding-usd text-info me-1"></i>
                            {{ __('show.refund_policy') }}
                        </label>
                        <select name="refund_policy" class="form-select form-select-lg" id="refundPolicy" required>
                            <option value="full">{{ __('show.full_refund', ['amount' => number_format($potentialRefund, 0, ',', ' ')]) }}</option>
                            <option value="partial">{{ __('show.partial_refund') }}</option>
                            <option value="none">{{ __('show.no_refund') }}</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="refundAmountSection">
                        <label class="form-label fw-bold">
                            <i class="fas fa-money-bill-wave text-success me-1"></i>
                            {{ __('show.refund_amount') }}
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light">FCFA</span>
                            <input type="number" name="refund_amount" id="refundAmount" class="form-control" 
                                   value="{{ $potentialRefund }}" min="0" max="{{ $potentialRefund }}" step="100">
                        </div>
                        <small class="text-muted">Maximum: {{ number_format($potentialRefund, 0, ',', ' ') }} FCFA</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-credit-card text-primary me-1"></i>
                            {{ __('show.refund_method') }}
                        </label>
                        <select name="payment_method" class="form-select form-select-lg" required>
                            <option value="cash">{{ __('show.cash') }}</option>
                            <option value="card">{{ __('show.credit_card') }}</option>
                            <option value="mobile_money">{{ __('show.mobile_money') }}</option>
                            <option value="bank_transfer">{{ __('show.bank_transfer') }}</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-pen text-secondary me-1"></i>
                            {{ __('show.early_departure_reason') }}
                        </label>
                        <textarea name="early_checkout_reason" class="form-control" rows="3" 
                                  placeholder="{{ __('show.early_departure_placeholder') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modern btn-outline-modern" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn-modern btn-info-modern">
                        <i class="fas fa-check me-2"></i>Confirmer early checkout
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('refundPolicy')?.addEventListener('change', function() {
    const section = document.getElementById('refundAmountSection');
    if (this.value === 'partial') {
        section.style.display = 'block';
    } else {
        section.style.display = 'none';
    }
});

// Initialiser l'affichage
if (document.getElementById('refundPolicy')?.value !== 'partial') {
    document.getElementById('refundAmountSection').style.display = 'none';
}
</script>
@endif

{{-- MODAL LATE CHECKOUT - MONTANT LIBRE --}}
@if($transaction->status == 'active' && !$transaction->late_checkout)
<div class="modal fade" id="lateCheckoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--amber-500), var(--amber-400)); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-clock me-2"></i>
                    {{ __('show.late_checkout_title', ['room' => $transaction->room->number]) }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('transaction.late-checkout', $transaction) }}" method="POST" id="lateCheckoutForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info mb-4" style="background: var(--blue-50); border-color: var(--blue-200);">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        <strong>{{ __('show.client_label', ['name' => $transaction->customer->name]) }}</strong><br>
                        {{ __('show.normal_departure') }} {{ \Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y') }} à 12h00
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-clock text-warning me-1"></i>
                                    {{ __('show.new_departure_time') }}
                                </label>
                                <select name="expected_checkout_time" class="form-select form-select-lg" id="lateTimeSelect" required>
                                    <option value="">{{ __('show.choose_time') }}</option>
                                    <option value="15:00">15h00</option>
                                    <option value="16:00">16h00</option>
                                    <option value="17:00">17h00</option>
                                    <option value="18:00">18h00</option>
                                    <option value="19:00">19h00</option>
                                    <option value="20:00">20h00</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-money-bill-wave text-success me-1"></i>
                                    {{ __('show.payment_method') }}
                                </label>
                                <select name="payment_method" class="form-select form-select-lg" required>
                                    <option value="cash">{{ __('show.cash') }}</option>
                                    <option value="card">{{ __('show.credit_card') }}</option>
                                    <option value="mobile_money">{{ __('show.mobile_money') }}</option>
                                    <option value="transfer">{{ __('show.transfer') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    {{-- BOUTONS DE SUGGESTION RAPIDE --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('show.amount_suggestions') }}</label>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            @php
                                $prixNuit = $transaction->room->price;
                                $suggestions = [
                                    '0' => __('show.free'),
                                    round($prixNuit * 0.25) => '25%',
                                    round($prixNuit * 0.5) => '50%',
                                    round($prixNuit * 0.75) => '75%',
                                    $prixNuit => '100% (nuit)',
                                ];
                            @endphp
                            
                            @foreach($suggestions as $montant => $label)
                                <button type="button" class="btn btn-sm btn-outline-primary suggestion-btn" 
                                        data-amount="{{ $montant }}">
                                    {{ $label }}<br>
                                    <small>{{ number_format($montant, 0, ',', ' ') }} FCFA</small>
                                </button>
                            @endforeach
                        </div>
                    </div>
                    
                    {{-- CHAMP DE SAISIE LIBRE --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            {{ __('show.supplement_label') }} - <span class="text-primary">{{ __('show.free_label') }}</span>
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light">FCFA</span>
                            <input type="number" name="late_checkout_fee" id="lateFee" class="form-control"
                                   value="{{ round($prixNuit * 0.5) }}" min="0" step="100" 
                                   style="font-size: 1.2rem; font-weight: 600;" required>
                        </div>
                        <small class="text-muted">
                            {{ __('show.night_price') }} {{ number_format($prixNuit, 0, ',', ' ') }} FCFA
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('show.notes_optional') }}</label>
                        <textarea name="notes" class="form-control" rows="2" 
                                  placeholder="{{ __('show.late_checkout_reason_placeholder') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modern btn-outline-modern" data-bs-dismiss="modal">{{ __('show.cancel') }}</button>
                    <button type="submit" class="btn-modern btn-warning-modern">
                        <i class="fas fa-check me-2"></i>{{ __('show.confirm_late_checkout') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('lateTimeSelect')?.addEventListener('change', function() {
    const pricePerNight = {{ $transaction->room->price }};
    const hour = parseInt(this.value.split(':')[0]);
    
    let suggestedPercentage = 0.5;
    if (hour <= 15) suggestedPercentage = 0.4;
    else if (hour <= 17) suggestedPercentage = 0.5;
    else if (hour <= 19) suggestedPercentage = 0.6;
    else if (hour == 20) suggestedPercentage = 0.7;
    
    const suggestedAmount = Math.round(pricePerNight * suggestedPercentage);
    const currentValue = document.getElementById('lateFee').value;
    
    if (currentValue == Math.round(pricePerNight * 0.5)) {
        document.getElementById('lateFee').value = suggestedAmount;
    }
});

// Gestionnaires pour les boutons de suggestion
document.querySelectorAll('.suggestion-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('lateFee').value = this.dataset.amount;
    });
});
</script>

@if($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {
    var cancelModal = document.getElementById('cancelModal');
    if (cancelModal) {
        new bootstrap.Modal(cancelModal).show();
    }
});
</script>
@endif
@endif

<!-- Modal d'annulation -->
@if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']) && !in_array($transaction->status, ['cancelled', 'no_show', 'completed']))
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-ban text-danger me-2"></i>
                    {{ __('show.cancel_title') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('transaction.cancel', $transaction) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    <p class="mb-3">{{ __('show.cancel_confirm_text') }}</p>
                    <div class="mb-3">
                        <label class="form-label">{{ __('show.cancel_reason_label') }}</label>
                        <textarea name="cancel_reason" class="form-control" rows="3" placeholder="{{ __('show.cancel_reason_placeholder') }}">{{ old('cancel_reason') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modern btn-outline-modern" data-bs-dismiss="modal">{{ __('show.cancel') }}</button>
                    <button type="submit" class="btn-modern btn-info-modern">
                        <i class="fas fa-check me-2"></i>{{ __('show.confirm_cancellation') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Formulaire annulation masqué -->
<form id="cancel-form" method="POST" action="{{ route('transaction.cancel', 0) }}" class="d-none">
    @csrf @method('DELETE')
    <input type="hidden" name="transaction_id" id="cancel-transaction-id-input">
    <input type="hidden" name="cancel_reason" id="cancel-reason-input">
</form>

{{-- Script pour marquer un paiement comme payé --}}
<script>
function markPaymentAsPaid(paymentId) {
    const btn = event.target;
    window.confirmAction('{{ __("show.confirm_payment") }}', function () {
    // Afficher un indicateur de chargement
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("show.processing") }}';
    btn.disabled = true;
    
    console.log('Tentative de paiement pour:', paymentId);
    
    fetch(`/payments/${paymentId}/mark-paid`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Réponse:', data);
        
        if (data.success) {
            // Toast de succès
            showToast('success', '{{ __("show.payment_confirmed_title") }}', '{{ __("show.payment_confirmed_message") }}');
            
            // Recharger après un délai
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            // Restaurer le bouton
            btn.innerHTML = originalText;
            btn.disabled = false;
            
            // Afficher l'erreur
            showToast('error', '{{ __("show.error_title") }}', data.error || data.message || '{{ __("show.unknown_error") }}');
        }
    })
    .catch(error => {
        console.error('Erreur réseau:', error);
        
        // Restaurer le bouton
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        showToast('error', '{{ __("show.communication_error") }}', error.toString());
    });
    });
}

// Fonction pour afficher les toasts
function showToast(type, title, message) {
    // Utiliser SweetAlert2 si disponible
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: type,
            title: title,
            text: message,
            timer: 3000,
            showConfirmButton: false
        });
    } else {
        // Fallback vers alert
        alert(title + ': ' + message);
    }
}

// Fonction pour vérifier le statut du late checkout
function checkLateCheckoutStatus(transactionId) {
    fetch(`/transaction/${transactionId}/late-checkout-status`, {
        headers: { 'Accept': 'application/json' }
    })
        .then(response => {
            if (!response.ok) return null;
            const ct = response.headers.get('Content-Type') || '';
            return ct.includes('application/json') ? response.json() : null;
        })
        .then(data => {
            if (data?.success && data.data?.has_late_checkout) {
                console.log('Statut late checkout:', data.data);
            }
        })
        .catch(() => {}); // silence · non-critique
}
</script>

@if(auth()->user()->hotel && ! in_array($transaction->status, ['completed','cancelled','no_show']))
<!-- Modal Pré-check-in -->
<div class="modal fade" id="preCheckinModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-id-card me-2"></i>Pré-check-in en ligne</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                @if($transaction->preCheckinDone())
                    <div class="alert alert-success mb-3"><i class="fas fa-check-circle me-1"></i> Complété le {{ $transaction->pre_checkin_completed_at->format('d/m/Y à H:i') }}</div>
                @else
                    <p class="text-muted small mb-3">Le client scanne ce QR (ou reçoit le lien) et remplit ses informations avant d'arriver.</p>
                @endif
                <div id="preCheckinQr" style="display:flex;justify-content:center;margin:4px 0 16px;"></div>
                <div class="input-group mb-2">
                    <input type="text" class="form-control" id="preCheckinLink" value="{{ $transaction->checkinUrl() }}" readonly onclick="this.select()">
                    <button class="btn btn-outline-secondary" type="button" onclick="copyPreCheckin()"><i class="fas fa-copy"></i></button>
                </div>
                @if($waCheckin)
                    <a href="{{ $waCheckin }}" target="_blank" rel="noopener" class="btn w-100 mt-1" style="background:#25d366;color:#fff"><i class="fab fa-whatsapp me-1"></i> Envoyer le lien sur WhatsApp</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var el = document.getElementById('preCheckinQr');
        if (el && window.QRCode) { new QRCode(el, { text: {!! json_encode($transaction->checkinUrl()) !!}, width: 176, height: 176 }); }
    });
    function copyPreCheckin() {
        var i = document.getElementById('preCheckinLink'); if (!i) return;
        i.select(); if (navigator.clipboard) navigator.clipboard.writeText(i.value);
    }
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) {
        return new bootstrap.Tooltip(el);
    });
    
    // Gestionnaire pour le changement de statut (confirmation via SweetAlert)
    document.querySelectorAll('.status-select').forEach(select => {
        select.__prev = select.value;
        select.addEventListener('change', function () {
            const newStatus = this.value;
            const oldStatus = this.__prev;
            const self = this;
            const form = this.closest('form');

            const needConfirm = (newStatus === 'cancelled' || newStatus === 'no_show');
            if (!needConfirm) { self.__prev = newStatus; form.submit(); return; }

            const msg = newStatus === 'cancelled'
                ? '{{ __("show.confirm_cancel_status") }}'
                : '{{ __("show.confirm_no_show_status") }}';

            self.value = oldStatus; // annule visuellement en attendant la confirmation
            window.confirmAction(msg, function () {
                self.value = newStatus;
                self.__prev = newStatus;
                form.submit();
            }, { danger: true });
        });
    });
    
    // Vérifier le statut du late checkout au chargement
    const transactionId = {{ $transaction->id }};
    checkLateCheckoutStatus(transactionId);
});

// Rafraîchir la page après une action
@if(session('success') || session('error') || session('warning') || session('info'))
    setTimeout(function() {
        location.reload();
    }, 2000);
@endif
</script>
@endsection