@extends('template.master')
@section('title', 'Edit Room Type')
@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-1">Edit Room Type: {{ $type->name }}</h1>
                    <p class="text-muted mb-0">Update room type information</p>
                </div>
                <a href="{{ route('type.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Types
                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form id="edit-type-form" method="POST" action="{{ route('type.update', $type->id) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <!-- Name -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Type Name *</label>
                        <input type="text" name="name" class="form-control" 
                               value="{{ $type->name }}" required>
                    </div>
                    
                    <!-- Base Price -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Base Price (FCFA)</label>
                        <div class="input-group">
                            <input type="number" name="base_price" class="form-control"
                                   value="{{ $type->base_price }}" min="0">
                            <span class="input-group-text">FCFA</span>
                        </div>
                    </div>
                    
                    <!-- Capacity -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Capacity</label>
                        <select name="capacity" class="form-select">
                            <option value="">Select...</option>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ $type->capacity == $i ? 'selected' : '' }}>
                                    {{ $i }} person(s)
                                </option>
                            @endfor
                        </select>
                    </div>
                    
                    <!-- Description -->
                    <div class="col-12">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="information" class="form-control" rows="4">{{ $type->information }}</textarea>
                    </div>
                    
                    <!-- Active Status -->
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" 
                                   id="is_active" name="is_active" {{ $type->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active (available for selection)
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Update Type
                    </button>
                    <a href="{{ route('type.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
// Gestion AJAX de la soumission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('edit-type-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
            
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'Accept': 'application/json',
                    'X-HTTP-Method-Override': 'PUT'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = '{{ route("type.index") }}';
                } else {
                    alert(data.message || 'Error updating type');
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error. Please try again.');
                button.disabled = false;
                button.innerHTML = originalText;
            });
        });
    }
});
</script>
@endsection