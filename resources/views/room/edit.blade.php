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
                
                <!-- Room Status -->
                <div class="col-md-6">
                    <label for="room_status_id" class="form-label fw-semibold">
                        <i class="fas fa-circle text-success me-1"></i>Room Status *
                    </label>
                    <select id="room_status_id" name="room_status_id" class="form-select @error('room_status_id') is-invalid @enderror" required>
                        <option value="" disabled>-- Select Status --</option>
                        @foreach ($roomstatuses as $roomstatus)
                            <option value="{{ $roomstatus->id }}" {{ old('room_status_id', $room->room_status_id) == $roomstatus->id ? 'selected' : '' }}>
                                <span class="badge bg-{{ $roomstatus->code == 'available' ? 'success' : ($roomstatus->code == 'occupied' ? 'danger' : 'warning') }} me-2">
                                    {{ $roomstatus->code }}
                                </span>
                                {{ $roomstatus->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('room_status_id')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
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