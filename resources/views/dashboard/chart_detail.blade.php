@extends('template.master')
@section('title', 'Dashboard - Rooms Occupancy')
@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h1 class="h2 fw-bold text-dark mb-2">Room Occupancy</h1>
                        <p class="text-muted mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Occupied rooms on {{ Helper::dateFormat($date) }}
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="badge bg-primary fs-6 px-3 py-2">
                            {{ $transactions->count() }} {{ Str::plural('Room', $transactions->count()) }} Occupied
                        </div>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rooms Grid -->
        @if($transactions->count() > 0)
            <div class="row g-4">
                @foreach ($transactions as $transaction)
                    @php
                        $checkOutDate = \Carbon\Carbon::parse($transaction->check_out);
                        $isCheckingOutToday = $checkOutDate->isToday();
                        $daysLeft = Helper::getDateDifference(now(), $transaction->check_out);
                        $balance = $transaction->getTotalPrice() - $transaction->getTotalPayment();
                    @endphp
                    
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <!-- Room Header -->
                            <div class="card-header bg-white border-0 pb-0 pt-4 px-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <span class="badge {{ $isCheckingOutToday ? 'bg-warning' : 'bg-primary' }} px-3 py-2 fs-6">
                                            <i class="fas fa-door-closed me-2"></i>
                                            Room {{ $transaction->room->number }}
                                        </span>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-muted small">Status</div>
                                        <div class="fw-bold {{ $transaction->room->roomStatus->color_class ?? 'text-primary' }}">
                                            {{ $transaction->room->roomStatus->name ?? 'Occupied' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Guest Info -->
                            <div class="card-body px-4 pt-0">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="avatar avatar-lg me-3">
                                        <img src="{{ $transaction->customer->user->getAvatar() ?? 'https://ui-avatars.com/api/?name=' . urlencode($transaction->customer->name) . '&background=random' }}"
                                             class="rounded-circle border" 
                                             alt="{{ $transaction->customer->name }}">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1">
                                            <a href="{{ route('customer.show', ['customer' => $transaction->customer->id]) }}"
                                               class="text-dark text-decoration-none hover-primary">
                                                {{ $transaction->customer->name }}
                                            </a>
                                        </h5>
                                        <div class="text-muted small">
                                            <i class="fas fa-phone-alt me-1"></i>
                                            {{ $transaction->customer->phone ?? 'No phone' }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Stay Details -->
                                <div class="bg-light rounded-3 p-3 mb-4">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-success-soft rounded-circle p-2 me-2">
                                                    <i class="fas fa-sign-in-alt text-success fa-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="text-muted small">Check-in</div>
                                                    <div class="fw-bold">{{ Helper::dateFormat($transaction->check_in) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-danger-soft rounded-circle p-2 me-2">
                                                    <i class="fas fa-sign-out-alt text-danger fa-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="text-muted small">Check-out</div>
                                                    <div class="fw-bold">{{ Helper::dateFormat($transaction->check_out) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Nights & Balance -->
                                    <div class="row g-2 mt-3">
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-info-soft rounded-circle p-2 me-2">
                                                    <i class="fas fa-moon text-info fa-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="text-muted small">Remaining</div>
                                                    <div class="fw-bold {{ $daysLeft <= 1 ? 'text-danger' : 'text-dark' }}">
                                                        {{ $daysLeft }} {{ Str::plural('Night', $daysLeft) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-warning-soft rounded-circle p-2 me-2">
                                                    <i class="fas fa-money-bill-wave text-warning fa-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="text-muted small">Balance</div>
                                                    <div class="fw-bold {{ $balance > 0 ? 'text-danger' : 'text-success' }}">
                                                        {{ Helper::convertToRupiah($balance) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Room Type -->
                                <div class="mb-4">
                                    <div class="text-muted small mb-1">Room Type</div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-bed text-primary me-2"></i>
                                        <span class="fw-medium">{{ $transaction->room->type->name ?? 'Standard Room' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions Footer -->
                            <div class="card-footer bg-white border-0 pt-0 px-4 pb-4">
                                <div class="d-grid gap-2">
                                    @if($balance > 0)
                                        <a href="{{ route('transaction.payment.create', ['transaction' => $transaction->id]) }}" 
                                           class="btn btn-warning btn-sm d-flex align-items-center justify-content-center">
                                            <i class="fas fa-credit-card me-2"></i>
                                            Process Payment
                                        </a>
                                    @endif
                                    
                                    <div class="btn-group w-100">
                                        <a href="{{ route('room.show', ['room' => $transaction->room->id]) }}" 
                                           class="btn btn-outline-primary btn-sm flex-fill">
                                            <i class="fas fa-eye me-1"></i> View Room
                                        </a>
                                        <a href="{{ route('customer.show', ['customer' => $transaction->customer->id]) }}" 
                                           class="btn btn-outline-info btn-sm flex-fill">
                                            <i class="fas fa-user me-1"></i> Guest Profile
                                        </a>
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle flex-fill" 
                                                data-bs-toggle="dropdown">
                                            More
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('transaction.edit', ['transaction' => $transaction->id]) }}">
                                                    <i class="fas fa-edit me-2"></i> Edit Stay
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="roomMove({{ $transaction->id }})">
                                                    <i class="fas fa-exchange-alt me-2"></i> Move Room
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" 
                                                   onclick="confirmCheckout({{ $transaction->id }})">
                                                    <i class="fas fa-sign-out-alt me-2"></i> Early Checkout
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body py-5 text-center">
                            <div class="empty-state-icon mb-4">
                                <i class="fas fa-door-open fa-4x text-muted opacity-25"></i>
                            </div>
                            <h3 class="text-dark mb-3">No Rooms Occupied</h3>
                            <p class="text-muted mb-4">
                                There are no occupied rooms on {{ Helper::dateFormat($date) }}.
                                <br>
                                All rooms are available or checked out.
                            </p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                    <i class="fas fa-tachometer-alt me-2"></i> Back to Dashboard
                                </a>
                                <a href="{{ route('room.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-bed me-2"></i> View All Rooms
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Summary -->
        @if($transactions->count() > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-chart-pie me-2 text-primary"></i>
                                Occupancy Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-blue-soft rounded-3">
                                        <div class="display-6 fw-bold text-primary">{{ $transactions->count() }}</div>
                                        <div class="text-muted">Total Occupied</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-warning-soft rounded-3">
                                        @php
                                            $checkingOutToday = $transactions->filter(function($t) {
                                                return \Carbon\Carbon::parse($t->check_out)->isToday();
                                            })->count();
                                        @endphp
                                        <div class="display-6 fw-bold text-warning">{{ $checkingOutToday }}</div>
                                        <div class="text-muted">Checking Out Today</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-danger-soft rounded-3">
                                        @php
                                            $pendingPayments = $transactions->filter(function($t) {
                                                return ($t->getTotalPrice() - $t->getTotalPayment()) > 0;
                                            })->count();
                                        @endphp
                                        <div class="display-6 fw-bold text-danger">{{ $pendingPayments }}</div>
                                        <div class="text-muted">Pending Payments</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-success-soft rounded-3">
                                        @php
                                            $completedPayments = $transactions->count() - $pendingPayments;
                                        @endphp
                                        <div class="display-6 fw-bold text-success">{{ $completedPayments }}</div>
                                        <div class="text-muted">Fully Paid</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('footer')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les tooltips Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });

    function roomMove(transactionId) {
        // À implémenter : logique de déplacement de chambre
        alert('Room move feature will be implemented soon for transaction #' + transactionId);
    }

    function confirmCheckout(transactionId) {
        if (confirm('Are you sure you want to process early checkout? This action cannot be undone.')) {
            // À implémenter : logique de checkout anticipé
            alert('Early checkout for transaction #' + transactionId + ' will be processed.');
        }
    }
</script>
@endsection

<style>
    /* ==================== */
    /* CUSTOM STYLES */
    /* ==================== */
    :root {
        --blue: #0d6efd;
        --blue-soft: #e3f2fd;
        --green: #198754;
        --green-soft: #d1e7dd;
        --orange: #fd7e14;
        --orange-soft: #fff3cd;
        --red: #dc3545;
        --red-soft: #f8d7da;
        --warning: #ffc107;
        --warning-soft: #fff3cd;
        --dark: #212529;
        --light: #f8f9fa;
    }

    body {
        background-color: #f8fafc;
        color: #374151;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Cards */
    .card {
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
    }

    /* Avatar */
    .avatar {
        width: 60px;
        height: 60px;
    }
    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Color Utilities */
    .bg-blue-soft { background-color: var(--blue-soft); }
    .bg-green-soft { background-color: var(--green-soft); }
    .bg-orange-soft { background-color: var(--orange-soft); }
    .bg-red-soft { background-color: var(--red-soft); }
    .bg-warning-soft { background-color: var(--warning-soft); }
    .bg-danger-soft { background-color: var(--red-soft); }
    .bg-success-soft { background-color: var(--green-soft); }
    .bg-info-soft { background-color: #cff4fc; }

    /* Typography */
    h1, h2, h3, h4, h5, h6 {
        color: #1f2937;
        font-weight: 600;
    }
    
    .text-muted {
        color: #6b7280 !important;
    }

    /* Badges */
    .badge {
        font-weight: 500;
        border-radius: 8px;
    }

    /* Buttons */
    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .btn-sm {
        padding: 0.375rem 0.75rem;
    }
    
    .btn-group .btn {
        border-radius: 8px;
    }

    /* Empty State */
    .empty-state-icon {
        opacity: 0.5;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .display-6 { font-size: 1.75rem; }
        .avatar { width: 50px; height: 50px; }
        .btn-group {
            flex-direction: column;
        }
        .btn-group .btn {
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }
        .btn-group .dropdown-toggle {
            order: 3;
        }
    }

    /* Hover Effects */
    .hover-primary:hover {
        color: var(--blue) !important;
    }
    
    a.text-decoration-none:hover {
        text-decoration: underline !important;
    }
</style>