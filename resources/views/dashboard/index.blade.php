@extends('template.master')
@section('title', 'Dashboard')
@section('content')
    <div id="dashboard" class="fade-in">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 text-gradient mb-1">Welcome back, {{ auth()->user()->name }}!</h1>
                        <p class="text-muted mb-0">Here's what's happening at your hotel today</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <!-- Bouton Site Vitrine -->
                        <a href="{{ route('frontend.home') }}" 
                           class="btn btn-outline-info btn-sm" 
                           target="_blank"
                           data-bs-toggle="tooltip" 
                           title="Voir le site public">
                            <i class="fas fa-external-link-alt me-1"></i>
                            Voir le site
                        </a>
                        
                        <div class="text-end">
                            <div class="text-muted small">{{ now()->format('l, F j, Y') }}</div>
                            <div class="fw-bold">{{ now()->format('g:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
                <div class="card card-stats h-100">
                    <div class="card-body text-center">
                        <div class="stats-number">{{ $transactions->count() }}</div>
                        <div class="stats-label">
                            <i class="fas fa-users me-2"></i>
                            Active Guests
                        </div>
                        <div class="stats-trend text-success small">
                            <i class="fas fa-arrow-up me-1"></i> 
                            {{ $transactions->where('check_in', '>=', now()->startOfDay())->count() }} new today
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
                <div class="card card-stats card-stats-success h-100">
                    <div class="card-body text-center">
                        @php
                            $completedToday = $transactions->filter(function($t) {
                                return \Carbon\Carbon::parse($t->check_out)->isToday() && 
                                       $t->getTotalPrice() - $t->getTotalPayment() <= 0;
                            })->count();
                        @endphp
                        <div class="stats-number">{{ $completedToday }}</div>
                        <div class="stats-label">
                            <i class="fas fa-check-circle me-2"></i>
                            Completed Today
                        </div>
                        <div class="stats-trend text-muted small">
                            Checked out & paid
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
                <div class="card card-stats card-stats-warning h-100">
                    <div class="card-body text-center">
                        @php
                            $pendingPayments = $transactions->filter(function($t) {
                                $balance = $t->getTotalPrice() - $t->getTotalPayment();
                                return $balance > 0;
                            })->count();
                        @endphp
                        <div class="stats-number">{{ $pendingPayments }}</div>
                        <div class="stats-label">
                            <i class="fas fa-clock me-2"></i>
                            Pending Payments
                        </div>
                        <div class="stats-trend text-warning small">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Need attention
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
                <div class="card card-stats card-stats-danger h-100">
                    <div class="card-body text-center">
                        @php
                            $urgentPayments = $transactions->filter(function($t) {
                                $balance = $t->getTotalPrice() - $t->getTotalPayment();
                                $daysLeft = Helper::getDateDifference(now(), $t->check_out);
                                return $balance > 0 && $daysLeft <= 1;
                            })->count();
                        @endphp
                        <div class="stats-number">{{ $urgentPayments }}</div>
                        <div class="stats-label">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Urgent Payments
                        </div>
                        <div class="stats-trend text-danger small">
                            <i class="fas fa-clock me-1"></i>
                            Due within 24h
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Debug Info (à supprimer en production) -->
        @if(env('APP_DEBUG', false))
        <div class="row mb-3">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Debug Info: {{ $transactions->count() }} transactions found</small>
                            <button class="btn btn-sm btn-outline-secondary" onclick="showDebugInfo()">
                                <i class="fas fa-bug"></i> Show Details
                            </button>
                        </div>
                        <div id="debugInfo" class="mt-2" style="display: none;">
                            @foreach($transactions as $t)
                            <div class="mb-1">
                                <small>
                                    ID: {{ $t->id }} | 
                                    Guest: {{ $t->customer->name ?? 'N/A' }} | 
                                    Room: {{ $t->room->number ?? 'N/A' }} | 
                                    Check-in: {{ $t->check_in }} | 
                                    Check-out: {{ $t->check_out }} |
                                    Status: {{ $t->status }} |
                                    Balance: {{ $t->getTotalPrice() - $t->getTotalPayment() }}
                                </small>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <!-- Today's Guests Table -->
            <div class="col-lg-8 mb-4">
                <div class="card card-lh h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-calendar-day text-primary me-2"></i>
                                Active Guests ({{ $transactions->count() }})
                            </h5>
                            <small class="text-muted">Currently checked-in guests - {{ now()->format('l, F j, Y') }}</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('transaction.reservation.createIdentity') }}" 
                               class="btn btn-primary btn-sm"
                               data-bs-toggle="tooltip" 
                               title="Add new reservation">
                                <i class="fas fa-plus me-1"></i>
                                New Guest
                            </a>
                            <a href="{{ route('frontend.home') }}" 
                               class="btn btn-outline-info btn-sm" 
                               target="_blank"
                               data-bs-toggle="tooltip" 
                               title="Voir le site public">
                                <i class="fas fa-external-link-alt me-1"></i>
                                Site
                            </a>
                            <button class="btn btn-outline-secondary btn-sm" onclick="window.location.reload()" data-bs-toggle="tooltip" title="Refresh">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($transactions->count() > 0)
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-lh mb-0 table-hover">
                                <thead class="sticky-top bg-light">
                                    <tr>
                                        <th>Guest</th>
                                        <th>Room</th>
                                        <th>Check-in/Out</th>
                                        <th>Nights</th>
                                        <th>Balance</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                        @php
                                            $balance = $transaction->getTotalPrice() - $transaction->getTotalPayment();
                                            $daysLeft = Helper::getDateDifference(now(), $transaction->check_out);
                                            $isNewToday = \Carbon\Carbon::parse($transaction->check_in)->isToday();
                                            $isCheckingOutToday = \Carbon\Carbon::parse($transaction->check_out)->isToday();
                                        @endphp
                                        <tr class="{{ $isNewToday ? 'table-info' : '' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-3">
                                                        <img src="{{ $transaction->customer->user->getAvatar() ?? 'https://ui-avatars.com/api/?name=' . urlencode($transaction->customer->name) . '&background=random' }}"
                                                            class="rounded-circle" width="40" height="40" alt="">
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">
                                                            <a href="{{ route('customer.show', ['customer' => $transaction->customer->id]) }}"
                                                               class="text-decoration-none">
                                                                {{ $transaction->customer->name }}
                                                            </a>
                                                            @if($isNewToday)
                                                            <span class="badge bg-info ms-1">New</span>
                                                            @endif
                                                        </div>
                                                        <div class="text-muted small">
                                                            <i class="fas fa-phone-alt me-1"></i>
                                                            {{ $transaction->customer->phone ?? 'N/A' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-medium">
                                                    <a href="{{ route('room.show', ['room' => $transaction->room->id]) }}"
                                                       class="text-decoration-none">
                                                        Room {{ $transaction->room->number }}
                                                    </a>
                                                </div>
                                                <div class="text-muted small">
                                                    <i class="fas fa-bed me-1"></i>
                                                    {{ $transaction->room->type->name ?? 'Standard' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <div class="d-flex align-items-center mb-1">
                                                        <i class="fas fa-sign-in-alt text-success me-2"></i>
                                                        <span><strong>In:</strong> {{ Helper::dateFormat($transaction->check_in) }}</span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-sign-out-alt text-danger me-2"></i>
                                                        <span><strong>Out:</strong> {{ Helper::dateFormat($transaction->check_out) }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column align-items-center">
                                                    <span class="badge {{ $daysLeft <= 1 ? 'bg-danger' : ($daysLeft <= 3 ? 'bg-warning' : 'bg-success') }} badge-lh mb-1">
                                                        {{ $daysLeft == 0 ? 'Last Day' : $daysLeft . ' ' . Helper::plural('Night', $daysLeft) }}
                                                    </span>
                                                    @if($isCheckingOutToday)
                                                    <small class="text-danger">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        Checking out today
                                                    </small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($balance <= 0)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>
                                                        Paid
                                                    </span>
                                                @else
                                                    <div class="d-flex flex-column">
                                                        <span class="text-danger fw-bold">{{ Helper::convertToRupiah($balance) }}</span>
                                                        <small class="text-muted">
                                                            Total: {{ Helper::convertToRupiah($transaction->getTotalPrice()) }}
                                                        </small>
                                                        <a href="{{ route('transaction.payment.create', ['transaction' => $transaction->id]) }}" 
                                                           class="btn btn-sm btn-outline-warning mt-1">
                                                            <i class="fas fa-credit-card me-1"></i>
                                                            Pay Now
                                                        </a>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    <span class="badge {{ $balance == 0 ? 'bg-success' : 'bg-warning' }} badge-lh">
                                                        {{ $balance == 0 ? 'Paid' : 'Pending Payment' }}
                                                    </span>
                                                    @if ($daysLeft < 1 && $balance > 0)
                                                        <span class="badge bg-danger badge-lh">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            Urgent Payment
                                                        </span>
                                                    @endif
                                                    @if($isNewToday)
                                                        <span class="badge bg-info badge-lh">
                                                            <i class="fas fa-star me-1"></i>
                                                            New Arrival
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('transaction.payment.create', ['transaction' => $transaction->id]) }}" 
                                                       class="btn btn-outline-success"
                                                       data-bs-toggle="tooltip" title="Add Payment">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-outline-info dropdown-toggle dropdown-toggle-split" 
                                                            data-bs-toggle="dropdown">
                                                        <span class="visually-hidden">More</span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('transaction.edit', ['transaction' => $transaction->id]) }}">
                                                                <i class="fas fa-edit me-2"></i> Edit
                                                            </a>
                                                        </li>
                    
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#" 
                                                               onclick="confirmDelete('{{ route('transaction.destroy', ['transaction' => $transaction->id]) }}')">
                                                                <i class="fas fa-trash me-2"></i> Delete
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-bed mb-3" style="font-size: 4rem; opacity: 0.2;"></i>
                                <h5 class="text-muted mb-2">No Active Guests</h5>
                                <p class="text-muted mb-4">There are no guests currently checked in</p>
                                <a href="{{ route('transaction.reservation.createIdentity') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i> Add First Guest
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                    @if($transactions->count() > 0)
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    Showing {{ $transactions->count() }} active guest(s)
                                </small>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">
                                    <span class="badge bg-info">Blue</span> = New today |
                                    <span class="badge bg-danger">Red</span> = Urgent payment |
                                    <span class="badge bg-warning">Yellow</span> = Pending payment
                                </small>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right Sidebar - Stats & Quick Actions -->
            <div class="col-lg-4 mb-4">
                <!-- Monthly Chart -->
                <div class="card card-lh mb-4">
                    <div class="card-header">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            Monthly Overview
                        </h5>
                        <small class="text-muted">{{ Helper::thisMonth() }}/{{ Helper::thisYear() }}</small>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="display-4 text-primary mb-1">{{ $transactions->count() }}</div>
                            <small class="text-muted">Active Guests This Month</small>
                        </div>

                        <div class="position-relative mb-4" style="height: 200px;">
                            <canvas id="monthlyChart" height="200"></canvas>
                        </div>

                        <div class="row text-center">
                            <div class="col-6">
                                <div class="h5 text-success">₱{{ number_format($transactions->sum(function($t) { return $t->getTotalPrice(); }), 2) }}</div>
                                <small class="text-muted">Total Revenue</small>
                            </div>
                            <div class="col-6">
                                <div class="h5 text-warning">₱{{ number_format($transactions->sum(function($t) { return $t->getTotalPrice() - $t->getTotalPayment(); }), 2) }}</div>
                                <small class="text-muted">Pending Revenue</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card card-lh">
                    <div class="card-header">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-bolt text-warning me-2"></i>
                            Quick Actions
                        </h5>
                        <small class="text-muted">Common tasks and shortcuts</small>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="{{ route('transaction.reservation.createIdentity') }}"
                                   class="btn btn-primary btn-lh w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-plus-circle mb-2" style="font-size: 1.5rem;"></i>
                                    <span>New Reservation</span>
                                    <small class="text-muted mt-1">Add guest</small>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('room.index') }}"
                                   class="btn btn-success btn-lh w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-bed mb-2" style="font-size: 1.5rem;"></i>
                                    <span>Rooms</span>
                                    <small class="text-muted mt-1">Manage rooms</small>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('customer.index') }}"
                                   class="btn btn-info btn-lh w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-users mb-2" style="font-size: 1.5rem;"></i>
                                    <span>Customers</span>
                                    <small class="text-muted mt-1">View all</small>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('payment.index') }}"
                                   class="btn btn-warning btn-lh w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-credit-card mb-2" style="font-size: 1.5rem;"></i>
                                    <span>Payments</span>
                                    <small class="text-muted mt-1">History</small>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('reports.index') }}"
                                   class="btn btn-secondary btn-lh w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-chart-bar mb-2" style="font-size: 1.5rem;"></i>
                                    <span>Reports</span>
                                    <small class="text-muted mt-1">Analytics</small>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('frontend.home') }}" target="_blank"
                                   class="btn btn-outline-dark btn-lh w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-external-link-alt mb-2" style="font-size: 1.5rem;"></i>
                                    <span>Website</span>
                                    <small class="text-muted mt-1">View site</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity (Optionnel) -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card card-lh">
                    <div class="card-header">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-history me-2"></i>
                            Recent Activity
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="activity-item">
                                    <div class="activity-icon bg-primary">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h6>New Check-ins</h6>
                                        <p class="mb-0">{{ $transactions->where('check_in', '>=', now()->startOfDay())->count() }} today</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="activity-item">
                                    <div class="activity-icon bg-success">
                                        <i class="fas fa-money-check-alt"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h6>Payments Received</h6>
                                        <p class="mb-0">₱{{ number_format($transactions->sum(function($t) { 
                                            return $t->getTotalPayment(); 
                                        }), 2) }} today</p>                                    
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="activity-item">
                                    <div class="activity-icon bg-warning">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h6>Attention Needed</h6>
                                        <p class="mb-0">{{ $urgentPayments }} urgent payments</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
<script>
    // Initialiser les tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Initialiser le chart
        initMonthlyChart();
    });

    // Fonction pour afficher les infos debug
    function showDebugInfo() {
        var debugDiv = document.getElementById('debugInfo');
        if (debugDiv.style.display === 'none') {
            debugDiv.style.display = 'block';
        } else {
            debugDiv.style.display = 'none';
        }
    }

    // Fonction de confirmation de suppression
    function confirmDelete(url) {
        if (confirm('Are you sure you want to delete this transaction? This action cannot be undone.')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.style.display = 'none';
            
            var csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            var methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
            
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Initialiser le chart mensuel
    function initMonthlyChart() {
        var ctx = document.getElementById('monthlyChart').getContext('2d');
        
        // Données fictives - À remplacer par des données réelles
        var monthlyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Guests',
                    data: [12, 19, 8, 15],
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        },
                        ticks: {
                            stepSize: 5
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
</script>
@endsection

<style>
    .card-stats .stats-number {
        font-size: 2rem;
        font-weight: bold;
        line-height: 1;
    }
    
    .card-stats .stats-label {
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .card-stats .stats-trend {
        margin-top: 5px;
        font-size: 0.8rem;
    }
    
    .card-stats-success {
        border-left: 4px solid #28a745;
    }
    
    .card-stats-warning {
        border-left: 4px solid #ffc107;
    }
    
    .card-stats-danger {
        border-left: 4px solid #dc3545;
    }
    
    .empty-state {
        padding: 3rem 1rem;
    }
    
    .activity-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 1rem;
    }
    
    .activity-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 1rem;
        font-size: 1.2rem;
    }
    
    .avatar img {
        object-fit: cover;
    }
    
    .table-lh tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .btn-lh {
        transition: all 0.3s ease;
        border-radius: 8px;
    }
    
    .btn-lh:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
</style>