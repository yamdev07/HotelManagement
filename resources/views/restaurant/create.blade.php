@extends('template.master')
@section('title', 'Restaurant - Nouveau Menu')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Ajouter un Menu</h3>
    <a href="{{ route('restaurant.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('restaurant.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nom du menu <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="category" class="form-label">Catégorie <span class="text-danger">*</span></label>
                    <select class="form-select @error('category') is-invalid @enderror" 
                            id="category" name="category" required>
                        <option value="">Sélectionner une catégorie</option>
                        <option value="plat" {{ old('category') == 'plat' ? 'selected' : '' }}>Plat principal</option>
                        <option value="entree" {{ old('category') == 'entree' ? 'selected' : '' }}>Entrée</option>
                        <option value="dessert" {{ old('category') == 'dessert' ? 'selected' : '' }}>Dessert</option>
                        <option value="boisson" {{ old('category') == 'boisson' ? 'selected' : '' }}>Boisson</option>
                        <option value="aperitif" {{ old('category') == 'aperitif' ? 'selected' : '' }}>Apéritif</option>
                        <option value="salade" {{ old('category') == 'salade' ? 'selected' : '' }}>Salade</option>
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Prix (€) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                               id="price" name="price" value="{{ old('price') }}" min="0" required>
                        <span class="input-group-text">€</span>
                    </div>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="image" class="form-label">Image du menu</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                           id="image" name="image" accept="image/*">
                    <small class="text-muted">Formats acceptés: JPG, PNG, GIF. Max: 2MB</small>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4">{{ old('description') }}</textarea>
                <small class="text-muted">Décrivez les ingrédients, la préparation, etc.</small>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Prévisualisation de l'image -->
            <div class="mb-3" id="imagePreviewContainer" style="display: none;">
                <label class="form-label">Prévisualisation</label>
                <div class="border rounded p-3 text-center">
                    <img id="imagePreview" src="#" alt="Prévisualisation" style="max-width: 200px; max-height: 200px;" class="img-fluid mb-2">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeImagePreview()">
                        <i class="fas fa-trash me-1"></i> Supprimer l'image
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('restaurant.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Enregistrer le menu
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('footer')
<script>
// Prévisualisation de l'image
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreviewContainer').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});

function removeImagePreview() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').src = '#';
    document.getElementById('imagePreviewContainer').style.display = 'none';
}

// Validation du prix
document.getElementById('price').addEventListener('input', function(e) {
    let value = parseFloat(e.target.value);
    if (value < 0) {
        e.target.value = 0;
    }
    if (value > 1000) {
        e.target.value = 1000;
    }
});
</script>

<style>
.form-label {
    font-weight: 500;
}

.input-group-text {
    background-color: #f8f9fa;
}

#imagePreview {
    border-radius: 0.375rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
@endsection