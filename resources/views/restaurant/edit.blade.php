@extends('template.master')
@section('title', 'Restaurant - Modifier Menu')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Modifier un Menu</h3>
    <a href="{{ route('restaurant.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('restaurant.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nom du menu <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $menu->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="category" class="form-label">Catégorie <span class="text-danger">*</span></label>
                    <select class="form-select @error('category_id') is-invalid @enderror" 
                            id="category" name="category_id" required>
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Prix (FCFA) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="1" class="form-control @error('price') is-invalid @enderror"
                               id="price" name="price" value="{{ old('price', $menu->price) }}" min="0" required>
                        <span class="input-group-text">FCFA</span>
                    </div>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="image" class="form-label">Image du menu</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                           id="image" name="image" accept="image/*">
                    <small class="text-muted">Laissez vide pour conserver l'image actuelle.</small>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label av-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3" style="font-size: 0.85rem;">{{ old('description', $menu->description) }}</textarea>
                <small class="text-muted" style="font-size: 0.7rem;">Décrivez les ingrédients, la préparation, etc.</small>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @php
                $availableDays = $menu->available_days ?? ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
            @endphp
            <div class="row align-items-center">
                <div class="col-md-8 mb-3">
                    <label class="form-label d-block av-label">Jours de disponibilité</label>
                    <div class="btn-group w-100" role="group">
                        @foreach(['mon' => 'Lun', 'tue' => 'Mar', 'wed' => 'Mer', 'thu' => 'Jeu', 'fri' => 'Ven', 'sat' => 'Sam', 'sun' => 'Dim'] as $key => $label)
                            <input type="checkbox" class="btn-check" id="day-{{ $key }}" name="available_days[]" value="{{ $key }}" 
                                   {{ in_array($key, $availableDays) ? 'checked' : '' }} autocomplete="off">
                            <label class="btn btn-outline-secondary btn-day" for="day-{{ $key }}">{{ $label }}</label>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <div class="form-check form-switch mb-1 p-2 px-3 rounded border bg-light d-flex align-items-center" style="height: 38px;">
                        <input class="form-check-input ms-0" type="checkbox" id="is_available" name="is_available" value="1" 
                               {{ $menu->is_available ? 'checked' : '' }}>
                        <label class="form-check-label ms-2 fw-bold av-switch-label" for="is_available">Dispo immédiatement</label>
                    </div>
                </div>
            </div>


            <!-- Prévisualisation de l'image -->
            @php
                $hasRealImage = $menu->image && !empty($menu->image);
                $previewSrc = '#';
                if ($hasRealImage) {
                    $previewSrc = str_starts_with($menu->image, 'http') ? $menu->image : asset('storage/' . $menu->image);
                }
            @endphp
            <div class="mb-3" id="imagePreviewContainer" style="{{ $hasRealImage ? 'display: block;' : 'display: none;' }}">
                <label class="form-label preview-title">Prévisualisation d'origine</label>
                <div class="border rounded p-2 text-center bg-light">
                    <img id="imagePreview" src="{{ $previewSrc }}" alt="Prévisualisation" style="max-height: 120px;" class="img-fluid mb-2 border no-fallback" 
                         onerror="if(this.src !== '#') { document.getElementById('imagePreviewContainer').style.display = 'none'; }">
                    <div class="mt-1">
                        <button type="button" class="btn btn-xs btn-danger" onclick="removeImagePreview()" style="font-size: 0.65rem; padding: 2px 8px;">
                            <i class="fas fa-trash me-1"></i> Supprimer l'image actuelle
                        </button>
                    </div>
                </div>
            </div>


            <div class="d-flex justify-content-between mt-4 db-form-footer">
                <a href="{{ route('restaurant.index') }}" class="btn btn-secondary btn-responsive">
                    <i class="fas fa-arrow-left me-1"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary btn-responsive">
                    <i class="fas fa-save me-1"></i> Enregistrer les modifications
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
    let value = parseInt(e.target.value);
    if (value < 0 || isNaN(value)) e.target.value = 0;
    if (value > 9999999) e.target.value = 9999999;
});
</script>

<style>
.form-label {
    font-weight: 500;
}

.av-label {
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    margin-bottom: 6px;
    font-weight: 700 !important;
}

.btn-day {
    font-size: 0.68rem;
    padding: 6px 4px;
    border-color: #e2e8f0;
    color: #64748b;
    font-weight: 600;
}

.btn-check:checked + .btn-day {
    background-color: #334155 !important;
    border-color: #334155 !important;
    color: white !important;
}

.av-switch-label {
    font-size: 0.75rem;
    color: #334155;
    margin-top: 1px;
}

.preview-title {
    font-size: 0.72rem;
    text-transform: uppercase;
    color: #64748b;
    font-weight: 700;
}

.input-group-text {
    background-color: #f8f9fa;
}

#imagePreview {
    border-radius: 0.375rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

@media (max-width: 576px) {
    .db-form-footer {
        flex-direction: column-reverse;
        gap: 10px;
    }
    .btn-responsive {
        width: 100%;
        padding: 10px 15px !important;
        font-size: 0.85rem !important;
    }
}
</style>
@endsection
