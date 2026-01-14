@extends('template.master')
@section('title', 'Create Room Type')
@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-1">Create New Room Type</h1>
                    <p class="text-muted mb-0">Add a new room type to your hotel</p>
                </div>
                <a href="{{ route('type.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Types
                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form id="create-type-form" method="POST" action="{{ route('type.store') }}">
                @csrf
                <div class="row g-3">
                    <!-- Name -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Type Name *</label>
                        <input type="text" name="name" class="form-control" required 
                               placeholder="Standard, Deluxe, Suite...">
                    </div>
                    
                    <!-- Base Price -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Base Price (FCFA)</label>
                        <div class="input-group">
                            <input type="number" name="base_price" class="form-control"
                                   placeholder="25000" min="0">
                            <span class="input-group-text">FCFA</span>
                        </div>
                    </div>
                    
                    <!-- Capacity -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Capacity</label>
                        <select name="capacity" class="form-select">
                            <option value="">Select...</option>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }} person(s)</option>
                            @endfor
                        </select>
                    </div>
                    
                    <!-- Description -->
                    <div class="col-12">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="information" class="form-control" rows="4"
                                  placeholder="Description of this room type..."></textarea>
                    </div>
                    
                    <!-- Active Status -->
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" 
                                   id="is_active" name="is_active" checked>
                            <label class="form-check-label" for="is_active">
                                Active (available for selection)
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Type
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
    const form = document.getElementById('create-type-form');
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
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = '{{ route("type.index") }}';
                } else {
                    alert(data.message || 'Error saving type');
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