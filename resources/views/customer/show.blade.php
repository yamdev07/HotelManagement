@extends('template.master')
@section('title', 'Customer Profile - ' . $customer->name)
@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-2">Customer Profile</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Customers</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $customer->name }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </a>
                    <a href="{{ route('transaction.reservation.createIdentity') }}?customer_id={{ $customer->id }}" 
                       class="btn btn-primary">
                        <i class="fas fa-calendar-plus me-2"></i>New Reservation
                    </a>
                    <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Profile Card -->
    <div class="row mb-5">
        <div class="col-xl-4 col-lg-5">
            <!-- Profile Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="avatar-profile mb-3">
                            <img src="{{ $customer->user->getAvatar() }}" 
                                 class="rounded-circle border" 
                                 alt="{{ $customer->name }}"
                                 width="120" height="120">
                        </div>
                        <h3 class="fw-bold text-dark mb-1">{{ $customer->name }}</h3>
                        @if($customer->job)
                            <p class="text-muted mb-3">
                                <i class="fas fa-briefcase me-2"></i>{{ $customer->job }}
                            </p>
                        @endif
                        
                        <!-- Customer Status -->
                        <div class="mb-3">
                            @php
                                $activeReservations = $customer->transactions()
                                    ->where('check_in', '<=', now())
                                    ->where('check_out', '>=', now())
                                    ->count();
                            @endphp
                            <span class="badge {{ $activeReservations > 0 ? 'bg-success' : 'bg-secondary' }} py-2 px-3">
                                <i class="fas fa-user me-1"></i>
                                {{ $activeReservations > 0 ? 'Active Guest' : 'No Active Stay' }}
                            </span>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="fas fa-address-card me-2"></i>Contact Information
                        </h6>
                        <ul class="list-unstyled mb-0">
                            @if($customer->email)
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-envelope text-primary me-3" style="width: 20px;"></i>
                                <div>
                                    <div class="text-muted small">Email</div>
                                    <a href="mailto:{{ $customer->email }}" class="text-dark">
                                        {{ $customer->email }}
                                    </a>
                                </div>
                            </li>
                            @endif
                            
                            @if($customer->phone)
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-phone text-success me-3" style="width: 20px;"></i>
                                <div>
                                    <div class="text-muted small">Phone</div>
                                    <a href="tel:{{ $customer->phone }}" class="text-dark">
                                        {{ $customer->phone }}
                                    </a>
                                </div>
                            </li>
                            @endif
                            
                            @if($customer->address)
                            <li class="d-flex align-items-start">
                                <i class="fas fa-map-marker-alt text-danger me-3 mt-1" style="width: 20px;"></i>
                                <div>
                                    <div class="text-muted small">Address</div>
                                    <div class="text-dark">{{ $customer->address }}</div>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>

                    <!-- Quick Stats -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="fas fa-chart-bar me-2"></i>Customer Stats
                        </h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="bg-light rounded-3 p-3 text-center">
                                    <div class="h4 fw-bold text-primary mb-1">{{ $customer->transactions->count() }}</div>
                                    <div class="text-muted small">Total Stays</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded-3 p-3 text-center">
                                    @php
                                        $totalNights = 0;
                                        foreach($customer->transactions as $transaction) {
                                            $totalNights += \App\Helpers\Helper::getDateDifference(
                                                $transaction->check_in, 
                                                $transaction->check_out
                                            );
                                        }
                                    @endphp
                                    <div class="h4 fw-bold text-success mb-1">{{ $totalNights }}</div>
                                    <div class="text-muted small">Total Nights</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    @if($customer->company || $customer->notes)
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="fas fa-info-circle me-2"></i>Additional Information
                        </h6>
                        <div class="bg-light rounded-3 p-3">
                            @if($customer->company)
                                <div class="mb-2">
                                    <div class="text-muted small">Company</div>
                                    <div class="fw-medium">{{ $customer->company }}</div>
                                </div>
                            @endif
                            @if($customer->notes)
                                <div>
                                    <div class="text-muted small">Notes</div>
                                    <div class="text-dark small">{{ $customer->notes }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Member Since -->
                    <div class="text-center pt-3 border-top">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            Member since {{ $customer->created_at->format('M d, Y') }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="mailto:{{ $customer->email }}" class="btn btn-outline-primary text-start">
                            <i class="fas fa-envelope me-2"></i>
                            Send Email
                        </a>
                        @if($customer->phone)
                        <a href="tel:{{ $customer->phone }}" class="btn btn-outline-success text-start">
                            <i class="fas fa-phone me-2"></i>
                            Call Customer
                        </a>
                        @endif
                        <a href="{{ route('transaction.reservation.customerReservations', $customer->id) }}" 
                           class="btn btn-outline-info text-start">
                            <i class="fas fa-history me-2"></i>
                            View All Stays
                        </a>
                        <button class="btn btn-outline-warning text-start" onclick="showNotesModal()">
                            <i class="fas fa-sticky-note me-2"></i>
                            Add Notes
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-xl-8 col-lg-7">
            <!-- Current/Upcoming Stays -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-calendar-check me-2"></i>
                            Current & Upcoming Stays
                        </h6>
                        <span class="badge bg-primary">
                            {{ $customer->transactions->where('check_out', '>=', now())->count() }} stay(s)
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @php
                        $currentStays = $customer->transactions()
                            ->where('check_out', '>=', now())
                            ->orderBy('check_in', 'desc')
                            ->with('room')
                            ->get();
                    @endphp
                    
                    @if($currentStays->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 py-3">Room</th>
                                        <th class="py-3">Check-in / Check-out</th>
                                        <th class="py-3">Status</th>
                                        <th class="pe-4 py-3 text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($currentStays as $transaction)
                                        @php
                                            $isActive = $transaction->check_in <= now() && $transaction->check_out >= now();
                                            $isFuture = $transaction->check_in > now();
                                            $balance = $transaction->getTotalPrice() - $transaction->getTotalPayment();
                                        @endphp
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light rounded p-2 me-3">
                                                        <i class="fas fa-bed text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">
                                                            <a href="{{ route('room.show', $transaction->room_id) }}" 
                                                               class="text-dark text-decoration-none">
                                                                Room {{ $transaction->room->number }}
                                                            </a>
                                                        </div>
                                                        <small class="text-muted">{{ $transaction->room->type->name ?? 'Standard' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex flex-column">
                                                    <div class="mb-1">
                                                        <i class="fas fa-sign-in-alt text-success me-2 fa-sm"></i>
                                                        <span class="fw-medium">{{ \App\Helpers\Helper::dateFormat($transaction->check_in) }}</span>
                                                    </div>
                                                    <div>
                                                        <i class="fas fa-sign-out-alt text-danger me-2 fa-sm"></i>
                                                        <span class="fw-medium">{{ \App\Helpers\Helper::dateFormat($transaction->check_out) }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                @if($isActive)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-user-check me-1"></i>
                                                        Currently Staying
                                                    </span>
                                                @elseif($isFuture)
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        Upcoming
                                                    </span>
                                                @endif
                                                @if($balance > 0)
                                                    <div class="mt-1">
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-money-bill-wave me-1"></i>
                                                            Balance: {{ \App\Helpers\Helper::convertToRupiah($balance) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="pe-4 py-3 text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('transaction.show', $transaction->id) }}" 
                                                       class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($balance > 0)
                                                    <a href="{{ route('transaction.payment.create', $transaction->id) }}" 
                                                       class="btn btn-outline-success">
                                                        <i class="fas fa-credit-card"></i>
                                                    </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bed fa-3x text-muted mb-3 opacity-25"></i>
                            <h6 class="text-dark mb-2">No Current or Upcoming Stays</h6>
                            <p class="text-muted mb-3">This customer has no active or future reservations</p>
                            <a href="{{ route('transaction.reservation.createIdentity') }}?customer_id={{ $customer->id }}" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>Create Reservation
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Stay History -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-history me-2"></i>
                            Stay History
                        </h6>
                        <span class="badge bg-secondary">
                            {{ $customer->transactions()->where('check_out', '<', now())->count() }} past stay(s)
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $pastStays = $customer->transactions()
                            ->where('check_out', '<', now())
                            ->orderBy('check_out', 'desc')
                            ->with('room')
                            ->take(5)
                            ->get();
                    @endphp
                    
                    @if($pastStays->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Room</th>
                                        <th>Nights</th>
                                        <th>Total</th>
                                        <th class="text-end">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pastStays as $transaction)
                                        @php
                                            $nights = \App\Helpers\Helper::getDateDifference(
                                                $transaction->check_in, 
                                                $transaction->check_out
                                            );
                                            $totalPrice = $transaction->getTotalPrice();
                                        @endphp
                                        <tr>
                                            <td>
                                                <small>{{ \App\Helpers\Helper::dateFormat($transaction->check_in) }}</small>
                                            </td>
                                            <td>
                                                <div class="fw-medium">Room {{ $transaction->room->number }}</div>
                                                <small class="text-muted">{{ $transaction->room->type->name ?? 'Standard' }}</small>
                                            </td>
                                            <td>{{ $nights }} night(s)</td>
                                            <td>
                                                <span class="fw-semibold">{{ \App\Helpers\Helper::convertToRupiah($totalPrice) }}</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('transaction.show', $transaction->id) }}" 
                                                   class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($customer->transactions()->where('check_out', '<', now())->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('transaction.reservation.customerReservations', $customer->id) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    View All History
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-2x text-muted mb-3 opacity-25"></i>
                            <p class="text-muted mb-0">No past stays recorded</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notes Modal -->
<div class="modal fade" id="notesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add Notes for {{ $customer->name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('customer.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="4" 
                                  placeholder="Add notes about this customer...">{{ $customer->notes }}</textarea>
                    </div>
                    <input type="hidden" name="update_type" value="notes">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Notes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
function showNotesModal() {
    const modal = new bootstrap.Modal(document.getElementById('notesModal'));
    modal.show();
}

// Initialiser les tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<style>
/* Custom Styles */
.avatar-profile {
    position: relative;
    display: inline-block;
}

.avatar-profile img {
    border: 4px solid white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin-bottom: 0;
}

.card {
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

.table th {
    font-weight: 600;
    color: #4b5563;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
    border-radius: 6px;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
}

.hover-link:hover {
    text-decoration: underline;
    color: #3b82f6;
}

.text-muted {
    color: #6b7280 !important;
}

.bg-light {
    background-color: #f8fafc !important;
}

/* Status badges */
.badge.bg-success {
    background: linear-gradient(135deg, #10b981, #059669);
}

.badge.bg-primary {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
}

.badge.bg-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.badge.bg-info {
    background: linear-gradient(135deg, #06b6d4, #0891b2);
}

/* Responsive */
@media (max-width: 768px) {
    .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .btn-group {
        flex-wrap: wrap;
        justify-content: flex-end;
    }
    
    .btn-group .btn {
        margin-bottom: 0.25rem;
    }
}
</style>
@endsection     