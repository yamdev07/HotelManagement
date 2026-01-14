@extends('template.master')
@section('title', 'Room Types')
@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-2">Room Types Management</h1>
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted">
                            <i class="fas fa-bed me-1"></i>
                            {{ $types->count() }} {{ Str::plural('type', $types->count()) }} available
                        </span>
                        @php
                            $activeTypes = $types->where('is_active', true)->count();
                        @endphp
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i>
                            {{ $activeTypes }} active
                        </span>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('type.create') }}" class="btn btn-primary d-flex align-items-center">
                        <i class="fas fa-plus-circle me-2"></i>
                        Add New Type
                    </a>
                    <a href="{{ route('room.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
                        <i class="fas fa-bed me-2"></i>
                        View Rooms
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages Flash -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-3 fs-4"></i>
            <div>
                <h6 class="mb-1">Success!</h6>
                <p class="mb-0">{{ session('success') }}</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle me-3 fs-4"></i>
            <div>
                <h6 class="mb-1">Error!</h6>
                <p class="mb-0">{{ session('error') }}</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-5">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-primary-soft p-3 rounded-circle">
                            <i class="fas fa-list-alt text-primary fa-lg"></i>
                        </div>
                        <span class="badge bg-primary">Total</span>
                    </div>
                    <h2 class="fw-bold display-6 text-dark mb-1">{{ $types->count() }}</h2>
                    <p class="text-muted mb-0">Room Types</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-success-soft p-3 rounded-circle">
                            <i class="fas fa-check-circle text-success fa-lg"></i>
                        </div>
                        <span class="badge bg-success">Active</span>
                    </div>
                    <h2 class="fw-bold display-6 text-dark mb-1">{{ $activeTypes }}</h2>
                    <p class="text-muted mb-0">Available Types</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-warning-soft p-3 rounded-circle">
                            <i class="fas fa-clock text-warning fa-lg"></i>
                        </div>
                        <span class="badge bg-warning">Inactive</span>
                    </div>
                    @php
                        $inactiveTypes = $types->count() - $activeTypes;
                    @endphp
                    <h2 class="fw-bold display-6 text-dark mb-1">{{ $inactiveTypes }}</h2>
                    <p class="text-muted mb-0">Hidden Types</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-info-soft p-3 rounded-circle">
                            <i class="fas fa-bed text-info fa-lg"></i>
                        </div>
                        <span class="badge bg-info">Rooms</span>
                    </div>
                    @php
                        $totalRooms = 0;
                        foreach($types as $type) {
                            $totalRooms += $type->rooms->count();
                        }
                    @endphp
                    <h2 class="fw-bold display-6 text-dark mb-1">{{ $totalRooms }}</h2>
                    <p class="text-muted mb-0">Total Rooms</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-list-alt text-primary me-2"></i>
                        All Room Types
                    </h5>
                    <p class="text-muted mb-0">Manage your hotel's room types and pricing</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm" onclick="window.location.reload()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            @if($types->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 fw-semibold text-dark" style="width: 50px;">ID</th>
                                <th class="py-3 fw-semibold text-dark">Type Details</th>
                                <th class="py-3 fw-semibold text-dark">Pricing</th>
                                <th class="py-3 fw-semibold text-dark">Capacity</th>
                                <th class="py-3 fw-semibold text-dark text-center">Status</th>
                                <th class="py-3 fw-semibold text-dark text-center">Rooms</th>
                                <th class="pe-4 py-3 fw-semibold text-dark text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($types as $type)
                                @php
                                    $roomCount = $type->rooms->count();
                                    $isPopular = $roomCount > 5;
                                @endphp
                                <tr class="hover-row">
                                    <td class="ps-4 py-3">
                                        <span class="badge bg-dark">#{{ $type->id }}</span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3">
                                                <div class="bg-primary-soft rounded-circle p-3">
                                                    <i class="fas fa-bed text-primary"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold text-dark">{{ $type->name }}</h6>
                                                @if($type->information)
                                                    <p class="text-muted small mb-0">{{ Str::limit($type->information, 60) }}</p>
                                                @else
                                                    <p class="text-muted small mb-0">No description</p>
                                                @endif
                                                @if($isPopular)
                                                    <span class="badge bg-warning mt-1">
                                                        <i class="fas fa-star me-1"></i>
                                                        Popular
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        @if($type->base_price)
                                            <div class="d-flex align-items-center">
                                                <span class="fw-bold text-dark fs-5">{{ number_format($type->base_price, 0, ',', ' ') }}</span>
                                                <span class="text-muted ms-1">FCFA</span>
                                            </div>
                                            <small class="text-muted">Base price per night</small>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-users text-info me-2"></i>
                                            <span class="fw-medium">{{ $type->capacity ?? 1 }} person(s)</span>
                                        </div>
                                        @if($type->bed_type)
                                            <small class="text-muted">{{ $type->bed_type }} bed</small>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center">
                                        @if($type->is_active)
                                            <span class="badge bg-success py-2 px-3">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary py-2 px-3">
                                                <i class="fas fa-eye-slash me-1"></i>
                                                Hidden
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge {{ $roomCount > 0 ? 'bg-info' : 'bg-light text-dark' }} py-2 px-3">
                                                <i class="fas fa-door-closed me-1"></i>
                                                {{ $roomCount }}
                                            </span>
                                            @if($roomCount > 0)
                                                <small class="text-muted mt-1">{{ $roomCount }} {{ Str::plural('room', $roomCount) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="pe-4 py-3 text-end">
                                        <div class="btn-group" role="group">
                                            <!-- Edit Button -->
                                            <a href="{{ route('type.edit', $type->id) }}" 
                                               class="btn btn-outline-primary btn-sm px-3"
                                               data-bs-toggle="tooltip" 
                                               title="Edit {{ $type->name }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <!-- Rooms Link -->
                                            @if($roomCount > 0)
                                                <a href="{{ route('room.index') }}?type={{ $type->id }}" 
                                                   class="btn btn-outline-info btn-sm px-3"
                                                   data-bs-toggle="tooltip" 
                                                   title="View {{ $roomCount }} rooms">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif
                                            
                                            <!-- Delete Button -->
                                            <form method="POST" 
                                                  action="{{ route('type.destroy', $type->id) }}"
                                                  class="d-inline"
                                                  onsubmit="return confirmDelete('{{ $type->name }}', {{ $roomCount }})">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger btn-sm px-3"
                                                        data-bs-toggle="tooltip" 
                                                        title="Delete {{ $type->name }}"
                                                        {{ $roomCount > 0 ? 'disabled' : '' }}>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Table Footer -->
                <div class="card-footer bg-white border-0 py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <small class="text-muted">
                                Showing {{ $types->count() }} room type(s)
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Delete is disabled for types with assigned rooms
                            </small>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-5 my-4">
                    <div class="empty-state">
                        <i class="fas fa-bed fa-4x text-muted mb-4 opacity-25"></i>
                        <h3 class="text-dark mb-3">No Room Types Yet</h3>
                        <p class="text-muted mb-4">
                            Start by creating your first room type. Room types help organize<br>
                            your hotel rooms by category, price, and capacity.
                        </p>
                        <a href="{{ route('type.create') }}" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-plus-circle me-2"></i>
                            Create First Room Type
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Help Section -->
    @if($types->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 bg-light">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="mb-1">
                                <i class="fas fa-lightbulb text-warning me-2"></i>
                                Need help managing room types?
                            </h6>
                            <p class="text-muted mb-0 small">
                                Room types define categories like "Standard", "Deluxe", or "Suite". 
                                Each type can have different pricing, capacity, and amenities.
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('room.index') }}" class="btn btn-outline-dark">
                                <i class="fas fa-bed me-2"></i>
                                Manage Rooms
                            </a>
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
// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Auto-hide alerts après 5 secondes
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Confirmation de suppression
function confirmDelete(typeName, roomCount) {
    if (roomCount > 0) {
        alert(`Cannot delete "${typeName}" because it has ${roomCount} assigned room(s).\n\nPlease reassign or delete the rooms first.`);
        return false;
    }
    
    return confirm(`Are you sure you want to delete "${typeName}"?\n\nThis action cannot be undone.`);
}

// Hover effect sur les lignes
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('.hover-row');
    rows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8fafc';
        });
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
});
</script>

<style>
/* Design System */
:root {
    --primary: #3b82f6;
    --primary-soft: #dbeafe;
    --success: #10b981;
    --success-soft: #d1fae5;
    --warning: #f59e0b;
    --warning-soft: #fef3c7;
    --danger: #ef4444;
    --danger-soft: #fee2e2;
    --info: #06b6d4;
    --info-soft: #cffafe;
    --dark: #1f2937;
    --light: #f8fafc;
}

/* Cards */
.card {
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
}

/* Table */
.table {
    margin-bottom: 0;
}

.table thead th {
    border-bottom: 2px solid #e5e7eb;
    font-weight: 600;
    text-transform: none;
    letter-spacing: normal;
    color: #4b5563;
}

.table tbody tr {
    border-bottom: 1px solid #f3f4f6;
}

.table tbody tr:last-child {
    border-bottom: none;
}

.table td, .table th {
    vertical-align: middle;
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

.btn-outline-primary:hover {
    background-color: var(--primary);
    border-color: var(--primary);
    color: white;
}

.btn-outline-info:hover {
    background-color: var(--info);
    border-color: var(--info);
    color: white;
}

.btn-outline-danger:hover {
    background-color: var(--danger);
    border-color: var(--danger);
    color: white;
}

.btn-outline-danger:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Badges */
.badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
    border-radius: 6px;
}

/* Avatar */
.avatar {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Empty State */
.empty-state {
    padding: 3rem 1rem;
}

.empty-state i {
    opacity: 0.3;
}

/* Hover effect */
.hover-row {
    transition: background-color 0.2s;
}

/* Alert */
.alert {
    border-radius: 10px;
    border: none;
}

.alert-success {
    background-color: var(--success-soft);
    color: #065f46;
}

.alert-danger {
    background-color: var(--danger-soft);
    color: #7f1d1d;
}

/* Statistics Cards */
.bg-primary-soft { background-color: var(--primary-soft); }
.bg-success-soft { background-color: var(--success-soft); }
.bg-warning-soft { background-color: var(--warning-soft); }
.bg-info-soft { background-color: var(--info-soft); }

/* Responsive */
@media (max-width: 768px) {
    .display-6 { font-size: 1.75rem; }
    .btn-group { flex-wrap: wrap; }
    .btn-group .btn { margin-bottom: 0.25rem; }
}
</style>
@endsection