@extends('template.master')
@section('title', 'Dashboard')
@section('content')
    <div id="dashboard" class="fade-in">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h1 class="h2 fw-bold text-dark mb-1">Welcome back, {{ auth()->user()->name }}!</h1>
                        <p class="text-muted mb-0">Overview of your hotel operations for {{ now()->format('l, F j, Y') }}</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('frontend.home') }}" 
                           class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2"
                           target="_blank"
                           data-bs-toggle="tooltip" 
                           title="View public website">
                            <i class="fas fa-external-link-alt"></i>
                            View Website
                        </a>
                        <div class="text-end">
                            <div class="text-muted small">{{ now()->format('l, F j') }}</div>
                            <div class="fw-bold fs-5 text-primary">{{ now()->format('g:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== -->
        <!-- STATISTICS CARDS - CLIQUABLES -->
        <!-- ==================== -->
        <div class="row mb-4 g-3">
            <!-- Card 1: Active Guests -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <a href="{{ route('transaction.index') }}?status=active&date_filter=today" class="text-decoration-none stats-card-link">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="p-3 rounded-circle bg-blue-soft">
                                    <i class="fas fa-users fa-lg text-blue"></i>
                                </div>
                                <span class="badge bg-blue-soft text-blue">Today</span>
                            </div>
                            <h2 class="fw-bold display-6 text-dark mb-1">{{ $stats['activeGuests'] ?? 0 }}</h2>
                            <p class="text-muted mb-2">Active Guests</p>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-arrow-up text-success me-1"></i>
                                <small class="text-success fw-medium">
                                    {{ $stats['todayArrivals'] ?? 0 }} new arrivals
                                </small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 2: Completed Today -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <a href="{{ route('transaction.index') }}?status=completed&date_filter=today" class="text-decoration-none stats-card-link">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="p-3 rounded-circle bg-green-soft">
                                    <i class="fas fa-check-circle fa-lg text-green"></i>
                                </div>
                                <span class="badge bg-green-soft text-green">Completed</span>
                            </div>
                            <h2 class="fw-bold display-6 text-dark mb-1">{{ $stats['completedToday'] ?? 0 }}</h2>
                            <p class="text-muted mb-2">Checked Out & Paid</p>
                            <small class="text-muted">All payments settled</small>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 3: Pending Payments -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <a href="{{ route('transaction.index') }}?payment_status=pending" class="text-decoration-none stats-card-link">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="p-3 rounded-circle bg-orange-soft">
                                    <i class="fas fa-clock fa-lg text-orange"></i>
                                </div>
                                <span class="badge bg-orange-soft text-orange">Attention</span>
                            </div>
                            <h2 class="fw-bold display-6 text-dark mb-1">{{ $stats['pendingPayments'] ?? 0 }}</h2>
                            <p class="text-muted mb-2">Pending Payments</p>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle text-orange me-1"></i>
                                <small class="text-orange fw-medium">Require follow-up</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 4: Urgent Payments -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <a href="{{ route('transaction.index') }}?payment_status=urgent&due_within=24h" class="text-decoration-none stats-card-link">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="p-3 rounded-circle bg-red-soft">
                                    <i class="fas fa-exclamation-triangle fa-lg text-red"></i>
                                </div>
                                <span class="badge bg-red-soft text-red">Urgent</span>
                            </div>
                            <h2 class="fw-bold display-6 text-dark mb-1">{{ $stats['urgentPayments'] ?? 0 }}</h2>
                            <p class="text-muted mb-2">Due within 24h</p>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock text-red me-1"></i>
                                <small class="text-red fw-medium">Immediate action needed</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- ==================== -->
        <!-- ARRIVALS & DEPARTURES - CLIQUABLES -->
        <!-- ==================== -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pb-0 pt-4">
                        <h3 class="fw-bold text-dark mb-3">
                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                            Arrivals & Departures
                        </h3>
                        <div class="row g-4">
                            <!-- Today -->
                            <div class="col-md-4">
                                <a href="{{ route('checkin.index') }}?date=today" class="text-decoration-none">
                                    <div class="card card-highlight hover-card">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="fw-bold text-dark mb-0">Today</h6>
                                                <span class="badge bg-primary">{{ now()->format('D, M j') }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted">Arrivals:</span>
                                                <span class="fw-bold text-dark">{{ $stats['todayArrivals'] ?? 0 }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Departures:</span>
                                                <span class="fw-bold text-dark">{{ $stats['todayDepartures'] ?? 0 }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- Tomorrow -->
                            <div class="col-md-4">
                                <a href="{{ route('checkin.index') }}?date=tomorrow" class="text-decoration-none">
                                    <div class="card hover-card">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="fw-bold text-dark mb-0">Tomorrow</h6>
                                                <span class="badge bg-secondary">{{ now()->addDay()->format('D, M j') }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted">Arrivals:</span>
                                                <span class="fw-bold text-dark">{{ $stats['tomorrowArrivals'] ?? 0 }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Departures:</span>
                                                <span class="fw-bold text-dark">{{ $stats['tomorrowDepartures'] ?? 0 }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- Day +2 -->
                            <div class="col-md-4">
                                <a href="{{ route('checkin.index') }}?date=day+2" class="text-decoration-none">
                                    <div class="card hover-card">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="fw-bold text-dark mb-0">Day +2</h6>
                                                <span class="badge bg-secondary">{{ now()->addDays(2)->format('D, M j') }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted">Arrivals:</span>
                                                <span class="fw-bold text-dark">{{ $stats['day2Arrivals'] ?? 0 }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Departures:</span>
                                                <span class="fw-bold text-dark">{{ $stats['day2Departures'] ?? 0 }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Chambres et occupation -->
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <a href="{{ route('room.index') }}?status=available" class="text-decoration-none">
                                    <div class="card hover-card">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="fw-bold text-dark mb-0">Available Rooms</h6>
                                                <span class="badge bg-success">Vacant</span>
                                            </div>
                                            <div class="text-center py-2">
                                                <h2 class="fw-bold text-dark mb-1">{{ $stats['availableRooms'] ?? 0 }}</h2>
                                                <small class="text-muted">of {{ $stats['totalRooms'] ?? 0 }} total rooms</small>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('room.index') }}?status=occupied" class="text-decoration-none">
                                    <div class="card hover-card">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="fw-bold text-dark mb-0">Occupied Rooms</h6>
                                                <span class="badge bg-primary">Occupied</span>
                                            </div>
                                            <div class="text-center py-2">
                                                <h2 class="fw-bold text-dark mb-1">{{ $stats['occupiedRooms'] ?? 0 }}</h2>
                                                <small class="text-muted">Currently in use</small>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('reports.index') }}" class="text-decoration-none">
                                    <div class="card hover-card">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="fw-bold text-dark mb-0">Occupancy Rate</h6>
                                                <span class="badge bg-info">Today</span>
                                            </div>
                                            <div class="text-center py-2">
                                                <h2 class="fw-bold text-dark mb-1">{{ $stats['occupancyRate'] ?? 0 }}%</h2>
                                                <div class="progress mt-2" style="height: 8px;">
                                                    <div class="progress-bar bg-info" role="progressbar" 
                                                         style="width: {{ $stats['occupancyRate'] ?? 0 }}%;" 
                                                         aria-valuenow="{{ $stats['occupancyRate'] ?? 0 }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== -->
        <!-- ACTIVE GUESTS TABLE -->
        <!-- ==================== -->
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <div>
                            <h3 class="fw-bold text-dark mb-0">
                                <i class="fas fa-user-friends text-primary me-2"></i>
                                Active Guests
                            </h3>
                            <p class="text-muted mb-0 small">{{ $transactions->count() }} guests currently checked in</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2" 
                                    onclick="refreshDashboard()">
                                <i class="fas fa-sync-alt"></i>
                                Refresh
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                        type="button" 
                                        data-bs-toggle="dropdown" 
                                        aria-expanded="false">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="?status=active">Active Only</a></li>
                                    <li><a class="dropdown-item" href="?status=reservation">Reservations</a></li>
                                    <li><a class="dropdown-item" href="?payment_status=pending">Pending Payments</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="?date_filter=today">Today</a></li>
                                    <li><a class="dropdown-item" href="?date_filter=tomorrow">Tomorrow</a></li>
                                    <li><a class="dropdown-item" href="?date_filter=this_week">This Week</a></li>
                                    <li><a class="dropdown-item" href="?date_filter=all">All Dates</a></li>
                                </ul>
                            </div>
                            <a href="{{ route('transaction.reservation.createIdentity') }}" 
                               class="btn btn-primary btn-sm d-flex align-items-center gap-2">
                                <i class="fas fa-plus"></i>
                                New Guest
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body p-0">
                        @if($transactions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light text-dark">
                                        <tr>
                                            <th class="py-3 px-4 fw-semibold">Guest</th>
                                            <th class="py-3 px-4 fw-semibold">Room</th>
                                            <th class="py-3 px-4 fw-semibold">Dates</th>
                                            <th class="py-3 px-4 fw-semibold">Balance</th>
                                            <th class="py-3 px-4 fw-semibold text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $transaction)
                                            @php
                                                $balance = $transaction->getTotalPrice() - $transaction->getTotalPayment();
                                                $isNewToday = \Carbon\Carbon::parse($transaction->check_in)->isToday();
                                                $isCheckingOutToday = \Carbon\Carbon::parse($transaction->check_out)->isToday();
                                                $balanceFormatted = number_format($balance, 0, ',', ' ') . ' CFA';
                                                $totalPriceFormatted = number_format($transaction->getTotalPrice(), 0, ',', ' ') . ' CFA';
                                            @endphp
                                            <tr class="{{ $isNewToday ? 'bg-blue-soft' : '' }} hover-row">
                                                <!-- Guest Column -->
                                                <td class="px-4 py-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" 
                                                             style="width: 40px; height: 40px;">
                                                            <i class="fas fa-user text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <a href="{{ route('customer.show', $transaction->customer->id) }}" 
                                                               class="fw-bold text-dark text-decoration-none">
                                                                {{ $transaction->customer->name }}
                                                            </a>
                                                            @if($isNewToday)
                                                                <span class="badge bg-info ms-2">New</span>
                                                            @endif
                                                            <div>
                                                                <small class="text-muted">{{ $transaction->customer->phone ?? 'No phone' }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                
                                                <!-- Room Column -->
                                                <td class="px-4 py-3">
                                                    @if($transaction->room)
                                                        <a href="{{ route('room.show', $transaction->room->id) }}" 
                                                        class="fw-medium text-dark text-decoration-none">
                                                            Room {{ $transaction->room->number }}
                                                        </a>
                                                        <div>
                                                            <small class="text-muted">{{ $transaction->room->type->name ?? 'Standard' }}</small>
                                                        </div>
                                                    @else
                                                        <div class="text-muted">
                                                            <i class="fas fa-exclamation-triangle me-1 text-warning"></i>
                                                            Room not assigned
                                                        </div>
                                                        <div>
                                                            <small class="text-muted">Awaiting check-in</small>
                                                        </div>
                                                    @endif
                                                </td>
                                                
                                                <!-- Dates Column -->
                                                <td class="px-4 py-3">
                                                    <div class="d-flex flex-column">
                                                        <div class="mb-1">
                                                            <i class="fas fa-sign-in-alt text-success me-2 fa-sm"></i>
                                                            <span class="fw-medium">{{ $transaction->check_in->format('d/m/Y') }}</span>
                                                        </div>
                                                        <div>
                                                            <i class="fas fa-sign-out-alt text-danger me-2 fa-sm"></i>
                                                            <span class="fw-medium">{{ $transaction->check_out->format('d/m/Y') }}</span>
                                                        </div>
                                                        @if($isCheckingOutToday)
                                                            <small class="text-danger mt-1">
                                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                                Checking out today
                                                            </small>
                                                        @endif
                                                    </div>
                                                </td>
                                                
                                                <!-- Balance Column -->
                                                <td class="px-4 py-3">
                                                    @if($balance <= 0)
                                                        <span class="badge bg-success py-2 px-3">
                                                            <i class="fas fa-check me-1"></i>
                                                            Paid
                                                        </span>
                                                    @else
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold text-danger fs-5">{{ $balanceFormatted }}</span>
                                                            <small class="text-muted">Total: {{ $totalPriceFormatted }}</small>
                                                            <a href="{{ route('transaction.payment.create', ['transaction' => $transaction->id]) }}" 
                                                               class="btn btn-sm btn-warning mt-2">
                                                                <i class="fas fa-credit-card me-1"></i>
                                                                Pay Now
                                                            </a>
                                                        </div>
                                                    @endif
                                                </td>
                                                
                                                <!-- Actions Column -->
                                                <td class="px-4 py-3 text-end">
                                                    <div class="btn-group">
                                                        <a href="{{ route('transaction.payment.create', ['transaction' => $transaction->id]) }}" 
                                                           class="btn btn-outline-success btn-sm"
                                                           title="Add Payment">
                                                            <i class="fas fa-money-bill-wave"></i>
                                                        </a>
                                                        <a href="{{ route('transaction.show', ['transaction' => $transaction->id]) }}" 
                                                           class="btn btn-outline-info btn-sm"
                                                           title="View Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                                                data-bs-toggle="dropdown">
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li>
                                                                <a class="dropdown-item" 
                                                                   href="{{ route('transaction.show', ['transaction' => $transaction->id]) }}">
                                                                    <i class="fas fa-eye me-2"></i> View Details
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" 
                                                                   href="{{ route('transaction.edit', ['transaction' => $transaction->id]) }}">
                                                                    <i class="fas fa-edit me-2"></i> Edit
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" 
                                                                   href="{{ route('transaction.invoice', ['transaction' => $transaction->id]) }}">
                                                                    <i class="fas fa-file-invoice me-2"></i> Invoice
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            @if($transaction->canBeCancelled())
                                                                <li>
                                                                    <a class="dropdown-item text-danger" href="#" 
                                                                       onclick="confirmCancel('{{ route('transaction.cancel', ['transaction' => $transaction->id]) }}', '{{ $transaction->customer->name }}')">
                                                                        <i class="fas fa-times me-2"></i> Cancel Reservation
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            @if(method_exists($transactions, 'links') && $transactions->hasPages())
                                <div class="card-footer bg-white border-0">
                                    {{ $transactions->links() }}
                                </div>
                            @endif
                            
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-bed fa-4x text-muted mb-3 opacity-25"></i>
                                <h4 class="text-dark mb-2">No Active Guests</h4>
                                <p class="text-muted mb-4">There are no guests currently checked in</p>
                                <a href="{{ route('transaction.reservation.createIdentity') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i> Add First Guest
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ==================== -->
            <!-- RIGHT SIDEBAR -->
            <!-- ==================== -->
            <div class="col-lg-4 mb-4">
                <!-- Quick Check-in -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="fas fa-door-open text-success me-2"></i>
                            Quick Check-in
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Check in a guest with an existing reservation</p>
                        <form action="{{ route('checkin.search') }}" method="GET" class="mb-3">
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       name="search" 
                                       placeholder="Search by name, room, or ID"
                                       value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                        <div class="d-grid gap-2">
                            <a href="{{ route('checkin.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-list me-2"></i>
                                View All Arrivals
                            </a>
                            <a href="{{ route('checkin.direct') }}" class="btn btn-success">
                                <i class="fas fa-user-plus me-2"></i>
                                Direct Check-in
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="fas fa-bolt text-warning me-2"></i>
                            Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('room.index') }}" class="btn btn-outline-primary text-start">
                                <i class="fas fa-bed me-2"></i>
                                Manage Rooms
                            </a>
                            <a href="{{ route('customer.index') }}" class="btn btn-outline-primary text-start">
                                <i class="fas fa-users me-2"></i>
                                View Customers
                            </a>
                            <a href="{{ route('checkin.index') }}" class="btn btn-outline-primary text-start">
                                <i class="fas fa-calendar-check me-2"></i>
                                Check-in Dashboard
                            </a>
                            <a href="{{ route('payments.index') }}" class="btn btn-outline-primary text-start">
                                <i class="fas fa-money-bill-wave me-2"></i>
                                View Payments
                            </a>
                            <a href="{{ route('reports.index') }}" class="btn btn-outline-primary text-start">
                                <i class="fas fa-chart-bar me-2"></i>
                                View Reports
                            </a>
                            @if(auth()->user()->isAdmin() || auth()->user()->role === 'Super')
                                <a href="{{ route('cashier.dashboard') }}" class="btn btn-outline-warning text-start">
                                    <i class="fas fa-cash-register me-2"></i>
                                    Cashier Dashboard
                                </a>
                            @endif
                            <a href="{{ route('frontend.home') }}" target="_blank" class="btn btn-outline-dark text-start">
                                <i class="fas fa-external-link-alt me-2"></i>
                                Visit Website
                            </a>
                        </div>
                    </div>
                </div>

                <!-- System Status -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="fas fa-heartbeat text-danger me-2"></i>
                            System Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                                <span class="text-muted">Last Updated</span>
                                <span class="badge bg-light text-dark" id="last-updated">
                                    {{ now()->format('H:i:s') }}
                                </span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                                <span class="text-muted">Active Sessions</span>
                                <span class="badge bg-info">1</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                                <span class="text-muted">Database</span>
                                <span class="badge bg-success">Online</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                                <span class="text-muted">Memory Usage</span>
                                <span class="badge bg-warning">Normal</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-secondary w-100" onclick="refreshDashboard()">
                                <i class="fas fa-sync-alt me-2"></i> Refresh Dashboard
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ==================== INITIALISATION ====================
    document.addEventListener('DOMContentLoaded', function() {
        initDashboardScripts();
        initAutoRefresh();
    });

    // ==================== FONCTIONS DASHBOARD ====================
    
    // Initialiser Bootstrap components
    function initDashboardScripts() {
        if (typeof bootstrap === 'undefined' || typeof bootstrap.Tooltip === 'undefined') {
            console.warn('Bootstrap non chargé, nouvelle tentative...');
            setTimeout(initDashboardScripts, 100);
            return;
        }
        
        // Tooltips
        var tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltips.forEach(function (el) {
            try {
                new bootstrap.Tooltip(el);
            } catch (e) {
                console.error('Erreur initialisation tooltip:', e);
            }
        });
        
        // Dropdowns
        var dropdowns = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        dropdowns.forEach(function (el) {
            try {
                new bootstrap.Dropdown(el);
            } catch (e) {
                console.error('Erreur initialisation dropdown:', e);
            }
        });
    }
    
    // Rafraîchir automatiquement les stats
    function initAutoRefresh() {
        // Rafraîchir les stats toutes les 30 secondes
        setInterval(updateStats, 30000);
        
        // Rafraîchir l'heure
        setInterval(updateTime, 1000);
    }
    
    // Mettre à jour l'heure
    function updateTime() {
        const now = new Date();
        const timeElement = document.querySelector('.text-end .fw-bold.fs-5');
        if (timeElement) {
            timeElement.textContent = now.toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit',
                hour12: true 
            });
        }
        
        // Mettre à jour la dernière mise à jour
        const lastUpdated = document.getElementById('last-updated');
        if (lastUpdated) {
            lastUpdated.textContent = now.toLocaleTimeString('fr-FR', { 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit'
            });
        }
    }
    
    // Mettre à jour les statistiques via AJAX
    function updateStats() {
        fetch('{{ route("dashboard.stats") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour les cartes de statistiques
                    updateStatCard('activeGuests', data.stats.activeGuests);
                    updateStatCard('todayArrivals', data.stats.todayArrivals);
                    updateStatCard('completedToday', data.stats.completedToday);
                    updateStatCard('pendingPayments', data.stats.pendingPayments);
                    updateStatCard('urgentPayments', data.stats.urgentPayments);
                    
                    // Mettre à jour la dernière mise à jour
                    const lastUpdated = document.getElementById('last-updated');
                    if (lastUpdated) {
                        lastUpdated.textContent = data.stats.updated_at;
                    }
                }
            })
            .catch(error => {
                console.error('Error updating stats:', error);
            });
    }
    
    // Mettre à jour une carte de statistique
    function updateStatCard(statName, value) {
        const cards = document.querySelectorAll('.stats-card-link');
        cards.forEach(card => {
            const h2 = card.querySelector('.display-6');
            const textElements = card.querySelectorAll('small');
            
            if (h2 && h2.textContent.includes(statName)) {
                // Cette logique peut être améliorée pour mieux identifier les cartes
                h2.textContent = value;
            }
            
            // Mettre à jour les textes d'arrivées
            if (statName === 'todayArrivals') {
                textElements.forEach(text => {
                    if (text.textContent.includes('new arrivals')) {
                        text.textContent = value + ' new arrivals';
                    }
                });
            }
        });
    }
    
    // Rafraîchir tout le dashboard
    function refreshDashboard() {
        const refreshBtn = document.querySelector('[onclick="refreshDashboard()"]');
        if (refreshBtn) {
            refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Refreshing...';
            refreshBtn.disabled = true;
        }
        
        // Rafraîchir la page après 1 seconde
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }
    
    // Confirmation de suppression
    function confirmDelete(url, customerName = 'this transaction') {
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete ${customerName}. This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                form.style.display = 'none';
                
                // CSRF Token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                // Méthode DELETE
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    
    // Confirmation d'annulation
    function confirmCancel(url, customerName) {
        Swal.fire({
            title: 'Cancel Reservation?',
            html: `You are about to cancel the reservation for <strong>${customerName}</strong>.<br>
                   This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, cancel it!',
            cancelButtonText: 'Keep reservation',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                form.style.display = 'none';
                
                // CSRF Token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                // Méthode DELETE
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    
    // Exposer les fonctions globalement
    window.confirmDelete = confirmDelete;
    window.confirmCancel = confirmCancel;
    window.refreshDashboard = refreshDashboard;
</script>
@endsection

<style>
    /* ==================== */
    /* DESIGN SYSTEM */
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
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .hover-card {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .hover-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.12) !important;
        border-color: var(--blue) !important;
    }
    
    .stats-card-link {
        display: block;
        text-decoration: none;
    }
    
    .stats-card-link:hover .card {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.12) !important;
        border-color: var(--blue) !important;
    }
    
    .hover-row:hover {
        background-color: #f8f9fa !important;
        transform: translateX(4px);
        transition: all 0.2s ease;
    }
    
    .card-highlight {
        border: 2px solid var(--blue);
        background-color: var(--blue-soft);
    }

    /* Typography */
    h1, h2, h3, h4, h5, h6 {
        color: #1f2937;
        font-weight: 600;
    }
    .text-dark {
        color: #1f2937 !important;
    }
    .text-muted {
        color: #6b7280 !important;
    }

    /* Stats Cards */
    .bg-blue-soft { background-color: var(--blue-soft); }
    .bg-green-soft { background-color: var(--green-soft); }
    .bg-orange-soft { background-color: var(--orange-soft); }
    .bg-red-soft { background-color: var(--red-soft); }
    .text-blue { color: var(--blue); }
    .text-green { color: var(--green); }
    .text-orange { color: var(--orange); }
    .text-red { color: var(--red); }

    /* Table */
    .table {
        color: #374151;
    }
    .table thead th {
        border-bottom: 2px solid #e5e7eb;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #6b7280;
    }
    .table tbody tr {
        border-bottom: 1px solid #f3f4f6;
    }
    .table tbody tr:hover {
        background-color: #f9fafb;
    }
    .table td, .table th {
        padding: 1rem 1.5rem;
        vertical-align: middle;
    }

    /* Buttons */
    .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 0.5rem 1rem;
    }
    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
    }
    .btn-primary {
        background-color: var(--blue);
        border-color: var(--blue);
    }
    .btn-success {
        background-color: var(--green);
        border-color: var(--green);
    }
    .btn-warning {
        background-color: var(--orange);
        border-color: var(--orange);
        color: white;
    }

    /* Badges */
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        border-radius: 6px;
    }

    /* Inputs */
    .form-control {
        border-radius: 8px;
        border: 1px solid #d1d5db;
        padding: 0.5rem 1rem;
    }
    .form-control:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
    }

    /* Spacing */
    .mb-6 { margin-bottom: 3rem; }
    .mb-7 { margin-bottom: 4rem; }

    /* Progress bars */
    .progress {
        border-radius: 6px;
        overflow: hidden;
    }
    .progress-bar {
        transition: width 0.6s ease;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in {
        animation: fadeInUp 0.5s ease-out;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .display-6 { font-size: 2rem; }
        .card-body { padding: 1rem !important; }
        .table-responsive {
            font-size: 0.875rem;
        }
        .btn-sm {
            padding: 0.2rem 0.5rem;
            font-size: 0.75rem;
        }
    }
    
    /* Spinner animation */
    .fa-spinner.fa-spin {
        animation: fa-spin 1s infinite linear;
    }
    
    @keyframes fa-spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>