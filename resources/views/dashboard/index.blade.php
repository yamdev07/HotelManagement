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
        <!-- STATISTICS CARDS -->
        <!-- ==================== -->
        <div class="row mb-4 g-3">
            <!-- Card 1: Active Guests -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="p-3 rounded-circle bg-blue-soft">
                                <i class="fas fa-users fa-lg text-blue"></i>
                            </div>
                            <span class="badge bg-blue-soft text-blue">Today</span>
                        </div>
                        <h2 class="fw-bold display-6 text-dark mb-1">{{ $transactions->count() }}</h2>
                        <p class="text-muted mb-2">Active Guests</p>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-arrow-up text-success me-1"></i>
                            <small class="text-success fw-medium">
                                {{ $transactions->where('check_in', '>=', now()->startOfDay())->count() }} new arrivals
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Completed Today -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="p-3 rounded-circle bg-green-soft">
                                <i class="fas fa-check-circle fa-lg text-green"></i>
                            </div>
                            <span class="badge bg-green-soft text-green">Completed</span>
                        </div>
                        @php
                            $completedToday = $transactions->filter(function($t) {
                                return \Carbon\Carbon::parse($t->check_out)->isToday() && 
                                       $t->getTotalPrice() - $t->getTotalPayment() <= 0;
                            })->count();
                        @endphp
                        <h2 class="fw-bold display-6 text-dark mb-1">{{ $completedToday }}</h2>
                        <p class="text-muted mb-2">Checked Out & Paid</p>
                        <small class="text-muted">All payments settled</small>
                    </div>
                </div>
            </div>

            <!-- Card 3: Pending Payments -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="p-3 rounded-circle bg-orange-soft">
                                <i class="fas fa-clock fa-lg text-orange"></i>
                            </div>
                            <span class="badge bg-orange-soft text-orange">Attention</span>
                        </div>
                        @php
                            $pendingPayments = $transactions->filter(function($t) {
                                $balance = $t->getTotalPrice() - $t->getTotalPayment();
                                return $balance > 0;
                            })->count();
                        @endphp
                        <h2 class="fw-bold display-6 text-dark mb-1">{{ $pendingPayments }}</h2>
                        <p class="text-muted mb-2">Pending Payments</p>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle text-orange me-1"></i>
                            <small class="text-orange fw-medium">Require follow-up</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4: Urgent Payments -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="p-3 rounded-circle bg-red-soft">
                                <i class="fas fa-exclamation-triangle fa-lg text-red"></i>
                            </div>
                            <span class="badge bg-red-soft text-red">Urgent</span>
                        </div>
                        @php
                            $urgentPayments = $transactions->filter(function($t) {
                                $balance = $t->getTotalPrice() - $t->getTotalPayment();
                                $daysLeft = Helper::getDateDifference(now(), $t->check_out);
                                return $balance > 0 && $daysLeft <= 1;
                            })->count();
                        @endphp
                        <h2 class="fw-bold display-6 text-dark mb-1">{{ $urgentPayments }}</h2>
                        <p class="text-muted mb-2">Due within 24h</p>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock text-red me-1"></i>
                            <small class="text-red fw-medium">Immediate action needed</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== -->
        <!-- ARRIVALS & DEPARTURES -->
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
                                <div class="card card-highlight">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="fw-bold text-dark mb-0">Today</h6>
                                            <span class="badge bg-primary">{{ now()->format('D, M j') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Arrivals:</span>
                                            <span class="fw-bold text-dark">{{ $transactions->where('check_in', '>=', now()->startOfDay())->count() }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Departures:</span>
                                            <span class="fw-bold text-dark">{{ $transactions->where('check_out', '>=', now()->startOfDay())->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Tomorrow -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="fw-bold text-dark mb-0">Tomorrow</h6>
                                            <span class="badge bg-secondary">{{ now()->addDay()->format('D, M j') }}</span>
                                        </div>
                                        @php
                                            $tomorrowArrivals = 0; // À calculer depuis la base
                                            $tomorrowDepartures = 0; // À calculer depuis la base
                                        @endphp
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Arrivals:</span>
                                            <span class="fw-bold text-dark">{{ $tomorrowArrivals }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Departures:</span>
                                            <span class="fw-bold text-dark">{{ $tomorrowDepartures }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Day +2 -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="fw-bold text-dark mb-0">Day +2</h6>
                                            <span class="badge bg-secondary">{{ now()->addDays(2)->format('D, M j') }}</span>
                                        </div>
                                        <div class="text-center py-2">
                                            <small class="text-muted">À implémenter</small>
                                        </div>
                                    </div>
                                </div>
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
                            <a href="{{ route('transaction.reservation.createIdentity') }}" 
                               class="btn btn-primary btn-sm d-flex align-items-center gap-2">
                                <i class="fas fa-plus"></i>
                                New Guest
                            </a>
                            <button class="btn btn-outline-secondary btn-sm" onclick="window.location.reload()">
                                <i class="fas fa-sync-alt"></i>
                            </button>
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
                                                $daysLeft = Helper::getDateDifference(now(), $transaction->check_out);
                                                $isNewToday = \Carbon\Carbon::parse($transaction->check_in)->isToday();
                                                $isCheckingOutToday = \Carbon\Carbon::parse($transaction->check_out)->isToday();
                                            @endphp
                                            <tr class="{{ $isNewToday ? 'bg-blue-soft' : '' }}">
                                                <!-- Guest Column -->
                                                <td class="px-4 py-3">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $transaction->customer->user->getAvatar() ?? 'https://ui-avatars.com/api/?name=' . urlencode($transaction->customer->name) . '&background=random' }}"
                                                             class="rounded-circle me-3" width="40" height="40" alt="">
                                                        <div>
                                                            <div class="fw-bold text-dark">
                                                                {{ $transaction->customer->name }}
                                                                @if($isNewToday)
                                                                    <span class="badge bg-info ms-2">New</span>
                                                                @endif
                                                            </div>
                                                            <small class="text-muted">{{ $transaction->customer->phone ?? 'No phone' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                
                                                <!-- Room Column -->
                                                <td class="px-4 py-3">
                                                    <div class="fw-medium text-dark">Room {{ $transaction->room->number }}</div>
                                                    <small class="text-muted">{{ $transaction->room->type->name ?? 'Standard' }}</small>
                                                </td>
                                                
                                                <!-- Dates Column -->
                                                <td class="px-4 py-3">
                                                    <div class="d-flex flex-column">
                                                        <div class="mb-1">
                                                            <i class="fas fa-sign-in-alt text-success me-2 fa-sm"></i>
                                                            <span class="fw-medium">{{ Helper::dateFormat($transaction->check_in) }}</span>
                                                        </div>
                                                        <div>
                                                            <i class="fas fa-sign-out-alt text-danger me-2 fa-sm"></i>
                                                            <span class="fw-medium">{{ Helper::dateFormat($transaction->check_out) }}</span>
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
                                                            <span class="fw-bold text-danger fs-5">{{ Helper::convertToRupiah($balance) }}</span>
                                                            <small class="text-muted">Total: {{ Helper::convertToRupiah($transaction->getTotalPrice()) }}</small>
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
                                                        <a href="{{ route('transaction.edit', ['transaction' => $transaction->id]) }}" 
                                                           class="btn btn-outline-primary btn-sm"
                                                           title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                                                data-bs-toggle="dropdown">
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="#">View Details</a></li>
                                                            <li><a class="dropdown-item" href="#">Move Room</a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item text-danger" href="#" 
                                                                   onclick="confirmDelete('{{ route('transaction.destroy', ['transaction' => $transaction->id]) }}')">
                                                                Delete
                                                            </a></li>
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
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Search by name or reservation ID">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <a href="{{ route('transaction.reservation.createIdentity') }}" class="btn btn-success w-100">
                            <i class="fas fa-user-plus me-2"></i>
                            New Check-in
                        </a>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm">
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
                            <a href="{{ route('reports.index') }}" class="btn btn-outline-primary text-start">
                                <i class="fas fa-chart-bar me-2"></i>
                                View Reports
                            </a>
                            <a href="{{ route('frontend.home') }}" target="_blank" class="btn btn-outline-dark text-start">
                                <i class="fas fa-external-link-alt me-2"></i>
                                Visit Website
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
<script>
    // Attendre que Bootstrap soit chargé
    function initDashboardScripts() {
        // Vérifier que Bootstrap est disponible ET que Tooltip existe
        if (typeof bootstrap === 'undefined' || typeof bootstrap.Tooltip === 'undefined') {
            console.warn('Bootstrap non chargé, nouvelle tentative dans 100ms...');
            setTimeout(initDashboardScripts, 100);
            return;
        }
        
        console.log('Initialisation des scripts du dashboard...');
        
        // 1. Initialiser les tooltips
        var tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var initializedTooltips = tooltips.map(function (el) {
            try {
                return new bootstrap.Tooltip(el);
            } catch (e) {
                console.error('Erreur initialisation tooltip:', e);
                return null;
            }
        }).filter(function(tooltip) {
            return tooltip !== null;
        });
        
        console.log(initializedTooltips.length + ' tooltip(s) initialisé(s) sur ' + tooltips.length + ' trouvé(s)');
        
        // 2. Initialiser les toasts si présents
        var toasts = [].slice.call(document.querySelectorAll('.toast'));
        if (toasts.length > 0 && typeof bootstrap.Toast !== 'undefined') {
            toasts.forEach(function (el) {
                try {
                    new bootstrap.Toast(el);
                } catch (e) {
                    console.error('Erreur initialisation toast:', e);
                }
            });
            console.log(toasts.length + ' toast(s) initialisé(s)');
        }
        
        // 3. Initialiser les popovers si présents
        var popovers = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        if (popovers.length > 0 && typeof bootstrap.Popover !== 'undefined') {
            popovers.forEach(function (el) {
                try {
                    new bootstrap.Popover(el);
                } catch (e) {
                    console.error('Erreur initialisation popover:', e);
                }
            });
            console.log(popovers.length + ' popover(s) initialisé(s)');
        }
    }

    // Fonction confirmDelete (peut rester car n'utilise pas Bootstrap)
    function confirmDelete(url) {
        if (confirm('Are you sure you want to delete this transaction? This action cannot be undone.')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.style.display = 'none';
            
            // CSRF Token
            var csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Méthode DELETE
            var methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
            
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Exposer la fonction globalement (si utilisée dans des onclick inline)
    window.confirmDelete = confirmDelete;

    // Démarrer l'initialisation quand le DOM est prêt
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDashboardScripts);
    } else {
        // DOM déjà chargé
        initDashboardScripts();
    }
    
    // Fallback : si après 3 secondes Bootstrap n'est toujours pas chargé
    setTimeout(function() {
        if (typeof bootstrap === 'undefined') {
            console.error('Bootstrap toujours non chargé après 3 secondes');
            // Optionnel : recharger Bootstrap manuellement
            var script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js';
            script.onload = function() {
                console.log('Bootstrap chargé manuellement');
                initDashboardScripts();
            };
            document.head.appendChild(script);
        }
    }, 3000);
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
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important;
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

    /* Responsive */
    @media (max-width: 768px) {
        .display-6 { font-size: 2rem; }
        .card-body { padding: 1rem !important; }
    }
</style>