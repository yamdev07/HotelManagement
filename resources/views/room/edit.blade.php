@extends('template.master')
@section('title', 'Edit Room')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-edit me-2"></i>Edit Room: {{ $room->number }}</h2>
        </div>
    </div>
    
    @if($errors->any())
    <div class="alert alert-danger">
        <h5>Please fix the following errors:</h5>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <div class="card shadow">
        <div class="card-body">
            <!-- Formulaire d'édition -->
            <form class="row g-3" method="POST" action="{{ route('room.update', $room->id) }}">
                @csrf
                @method('PUT')
                
                <div class="col-md-12">
                    <label for="type_id" class="form-label">Type *</label>
                    <select id="type_id" name="type_id" class="form-control" required>
                        <option value="">-- Select Type --</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}" {{ old('type_id', $room->type_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('type_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-12">
                    <label for="room_status_id" class="form-label">Status *</label>
                    <select id="room_status_id" name="room_status_id" class="form-control" required>
                        <option value="">-- Select Status --</option>
                        @foreach ($roomstatuses as $roomstatus)
                            <option value="{{ $roomstatus->id }}" {{ old('room_status_id', $room->room_status_id) == $roomstatus->id ? 'selected' : '' }}>
                                {{ $roomstatus->name }} ({{ $roomstatus->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('room_status_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-12">
                    <label for="number" class="form-label">Room Number *</label>
                    <input type="text" class="form-control @error('number') is-invalid @enderror" 
                           id="number" name="number" value="{{ old('number', $room->number) }}" 
                           placeholder="ex: 1A" required>
                    @error('number')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-12">
                    <label for="capacity" class="form-label">Capacity *</label>
                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                           id="capacity" name="capacity" value="{{ old('capacity', $room->capacity) }}" 
                           placeholder="ex: 4" min="1" max="10" required>
                    @error('capacity')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-12">
                    <label for="price" class="form-label">Price (CFA) *</label>
                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                           id="price" name="price" value="{{ old('price', $room->price) }}" 
                           placeholder="ex: 50000" min="0" required>
                    <small class="text-muted">Price in Francs CFA</small>
                    @error('price')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-12">
                    <label for="view" class="form-label">View</label>
                    <textarea class="form-control @error('view') is-invalid @enderror" 
                              id="view" name="view" rows="3" 
                              placeholder="ex: window see beach">{{ old('view', $room->view) }}</textarea>
                    @error('view')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-12 mt-4">
                    <!-- Bouton submit -->
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Room
                    </button>
                    <a href="{{ route('room.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Rooms
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection