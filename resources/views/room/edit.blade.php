@extends('template.master')
@section('title', 'Edit Room')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">
                    <i class="fas fa-edit me-2 text-primary"></i>Edit Room: {{ $room->number }}
                </h2>
                <nav aria-label="breadcrumb" class="mt-2">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('room.index') }}">Rooms</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('room.show', $room->id) }}">{{ $room->number }}</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('room.show', $room->id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-eye me-2"></i>View Room
            </a>
        </div>
    </div>
    
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-2 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        {!! session('info') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <div class="card shadow border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0">
                <i class="fas fa-info-circle me-2 text-info"></i>Room Information
            </h5>
        </div>
        
        <div class="card-body">
            <form class="row g-4" method="POST" action="{{ route('room.update', $room->id) }}">
                @csrf
                @method('PUT')
                
                <!-- Room Number -->
                <div class="col-md-6">
                    <label for="number" class="form-label fw-semibold">
                        <i class="fas fa-hashtag text-primary me-1"></i>Room Number *
                    </label>
                    <input type="text" class="form-control @error('number') is-invalid @enderror" 
                           id="number" name="number" value="{{ old('number', $room->number) }}" 
                           placeholder="Example: 101, 201, 301" required>
                    @error('number')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                    <small class="text-muted">Unique room identifier</small>
                </div>
                
                <!-- Room Name (Optional) -->
                <div class="col-md-6">
                    <label for="name" class="form-label fw-semibold">
                        <i class="fas fa-signature text-primary me-1"></i>Room Name
                        <span class="text-muted fw-normal">(Optional)</span>
                    </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $room->name) }}" 
                           placeholder="Example: Presidential Suite, Ocean View Room">
                    @error('name')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                    <small class="text-muted">Descriptive name for the room</small>
                </div>
                
                <!-- Room Type -->
                <div class="col-md-6">
                    <label for="type_id" class="form-label fw-semibold">
                        <i class="fas fa-bed text-primary me-1"></i>Room Type *
                    </label>
                    <select id="type_id" name="type_id" class="form-select @error('type_id') is-invalid @enderror" required>
                        <option value="" disabled>-- Select Type --</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}" {{ old('type_id', $room->type_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->name }} 
                                @if($type->base_price)
                                    - {{ number_format($type->base_price, 0, ',', ' ') }} FCFA
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('type_id')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Room Status (READ-ONLY) -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-circle text-success me-1"></i>Current Room Status
                        <span class="text-muted fw-normal">(Auto-managed)</span>
                    </label>
                    
                    <div class="card border-0 bg-light mb-2">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <!-- Badge du statut -->
                                @php
                                    $statusColor = match($room->roomStatus->code ?? '') {
                                        'available' => 'success',
                                        'occupied' => 'danger',
                                        'reserved' => 'warning',
                                        'maintenance' => 'secondary',
                                        default => 'info'
                                    };
                                    
                                    $statusIcon = match($room->roomStatus->code ?? '') {
                                        'available' => 'check',
                                        'occupied' => 'user',
                                        'reserved' => 'calendar-check',
                                        'maintenance' => 'tools',
                                        default => 'question-circle'
                                    };
                                @endphp
                                
                                <span class="badge bg-{{ $statusColor }} me-3 p-2" style="font-size: 0.9rem;">
                                    <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                    {{ $room->roomStatus->name ?? 'Unknown' }}
                                </span>
                                
                                <div class="flex-grow-1">
                                    <small class="text-muted d-block">{{ $room->roomStatus->information ?? '' }}</small>
                                    
                                    <!-- Informations dynamiques selon le statut -->
                                    @if(($room->roomStatus->code ?? '') == 'occupied')
                                        @php
                                            $activeTransaction = $room->transactions()
                                                ->where('status', 'active')
                                                ->where('check_in', '<=', now())
                                                ->where('check_out', '>=', now())
                                                ->first();
                                        @endphp
                                        @if($activeTransaction)
                                            <small class="text-danger d-block mt-1">
                                                <i class="fas fa-user me-1"></i>
                                                Client: {{ $activeTransaction->customer->name }}
                                            </small>
                                        @endif
                                    @elseif(($room->roomStatus->code ?? '') == 'reserved')
                                        @php
                                            $nextReservation = $room->transactions()
                                                ->where('status', 'reservation')
                                                ->where('check_in', '>', now())
                                                ->orderBy('check_in', 'asc')
                                                ->first();
                                        @endphp
                                        @if($nextReservation)
                                            <small class="text-warning d-block mt-1">
                                                <i class="fas fa-calendar me-1"></i>
                                                Arrival: {{ \Carbon\Carbon::parse($nextReservation->check_in)->format('d/m/Y') }}
                                            </small>
                                        @endif
                                    @elseif(($room->roomStatus->code ?? '') == 'maintenance')
                                        @if($room->maintenance_started_at)
                                            <small class="text-secondary d-block mt-1">
                                                <i class="fas fa-clock me-1"></i>
                                                Since: {{ \Carbon\Carbon::parse($room->maintenance_started_at)->format('d/m/Y H:i') }}
                                            </small>
                                        @endif
                                        @if($room->maintenance_reason)
                                            <small class="text-secondary d-block mt-1">
                                                <i class="fas fa-sticky-note me-1"></i>
                                                Reason: {{ $room->maintenance_reason }}
                                            </small>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Champ caché pour conserver la valeur -->
                    <input type="hidden" name="room_status_id" value="{{ $room->room_status_id }}">
                    
                    <!-- Information sur la gestion automatique -->
                    <div class="alert alert-info border-0 p-2 mt-2" style="background-color: #e7f3ff;">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle text-info me-2 mt-1"></i>
                            <div class="small">
                                <strong>Auto-managed status</strong><br>
                                <span class="text-muted">This status is automatically updated based on reservations and stays.</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bouton pour mode maintenance (Admin seulement) -->
                    @if(auth()->user()->role == 'Super')
                        <div class="mt-3">
                            <button type="button" class="btn btn-sm btn-outline-warning" 
                                    onclick="toggleMaintenance({{ $room->id }}, '{{ $room->roomStatus->code ?? '' }}')">
                                <i class="fas fa-tools me-1"></i>
                                {{ ($room->roomStatus->code ?? '') == 'maintenance' ? 'End Maintenance' : 'Set to Maintenance' }}
                            </button>
                        </div>
                    @endif
                </div>
                
                <!-- Capacity -->
                <div class="col-md-6">
                    <label for="capacity" class="form-label fw-semibold">
                        <i class="fas fa-users text-primary me-1"></i>Capacity *
                    </label>
                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                           id="capacity" name="capacity" value="{{ old('capacity', $room->capacity) }}" 
                           placeholder="Example: 2, 4, 6" min="1" max="10" required>
                    @error('capacity')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                    <small class="text-muted">Number of guests (1-10)</small>
                </div>
                
                <!-- Price -->
                <div class="col-md-6">
                    <label for="price" class="form-label fw-semibold">
                        <i class="fas fa-money-bill-wave text-success me-1"></i>Price per Night *
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">FCFA</span>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                               id="price" name="price" value="{{ old('price', $room->price) }}" 
                               placeholder="Example: 50000" min="0" required>
                    </div>
                    @error('price')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- View Description -->
                <div class="col-md-6">
                    <label for="view" class="form-label fw-semibold">
                        <i class="fas fa-binoculars text-info me-1"></i>View Description
                    </label>
                    <textarea class="form-control @error('view') is-invalid @enderror" 
                              id="view" name="view" rows="1" 
                              placeholder="Example: Sea view, Mountain view, City view, Garden view">{{ old('view', $room->view) }}</textarea>
                    @error('view')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Created Info (Read-only) -->
                <div class="col-md-6">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-calendar-alt me-1"></i>Room Information
                            </h6>
                            <div class="small text-muted">
                                <div>Created: {{ $room->created_at->format('d/m/Y H:i') }}</div>
                                <div>Last Updated: {{ $room->updated_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="col-12 mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('room.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <div class="d-flex gap-2">
                            <a href="{{ route('room.show', $room->id) }}" class="btn btn-outline-info px-4">
                                <i class="fas fa-eye me-2"></i>View
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Update Room
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-control, .form-select {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        border: 1px solid #dee2e6;
        transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        transition: all 0.3s;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
    }
    
    .card {
        border-radius: 15px;
        border: 1px solid rgba(0,0,0,0.1);
    }
    
    .card-header {
        border-radius: 15px 15px 0 0 !important;
        background-color: #f8f9fa;
    }
    
    .alert {
        border-radius: 10px;
        border: none;
    }
    
    .input-group-text {
        border-radius: 8px 0 0 8px;
        background-color: #f8f9fa;
    }
    
    .bg-light {
        background-color: #f8fafc !important;
    }
</style>

@endsection

@section('footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function toggleMaintenance(roomId, currentStatus) {
    const isMaintenance = currentStatus === 'maintenance';
    
    Swal.fire({
        title: isMaintenance ? 'End Maintenance Mode?' : 'Set Room to Maintenance?',
        html: `
            <div class="text-start">
                <p>${isMaintenance 
                    ? 'This will mark the room as available again.' 
                    : 'This will temporarily mark the room as unavailable.'}</p>
                
                ${!isMaintenance ? `
                <div class="mb-3">
                    <label class="form-label">Maintenance reason:</label>
                    <textarea id="maintenanceReason" class="form-control" rows="3" 
                              placeholder="Cleaning, repairs, renovation..."></textarea>
                </div>
                ` : ''}
            </div>
        `,
        icon: isMaintenance ? 'question' : 'warning',
        showCancelButton: true,
        confirmButtonColor: isMaintenance ? '#28a745' : '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: isMaintenance ? 'Yes, end maintenance' : 'Yes, set to maintenance',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            if (!isMaintenance) {
                const reason = document.getElementById('maintenanceReason').value;
                if (!reason.trim()) {
                    Swal.showValidationMessage('Please enter a maintenance reason');
                    return false;
                }
                return { reason: reason.trim() };
            }
            return {};
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const reason = result.value?.reason || '';
            
            // Afficher chargement
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Envoyer la requête
            fetch(`/room/${roomId}/maintenance-toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    action: isMaintenance ? 'end' : 'start',
                    reason: reason
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Rafraîchir la page
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Operation failed'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Network error occurred. Please try again.'
                });
            });
        }
    });
}

// Désactiver la modification du champ status
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('room_status_id');
    if (statusSelect) {
        statusSelect.addEventListener('change', function(e) {
            Swal.fire({
                icon: 'info',
                title: 'Auto-managed Status',
                html: `
                    <div class="text-start">
                        <p>The room status is automatically managed by the system.</p>
                        <p>It changes based on:</p>
                        <ul>
                            <li>Active reservations</li>
                            <li>Current stays</li>
                            <li>Upcoming bookings</li>
                            <li>Maintenance schedules</li>
                        </ul>
                        <p>To change the status, use the maintenance button or manage reservations.</p>
                    </div>
                `,
                confirmButtonText: 'OK',
                confirmButtonColor: '#4e73df'
            });
            
            // Revenir à la valeur d'origine
            this.value = {{ $room->room_status_id }};
        });
    }
});
</script>
@endsection