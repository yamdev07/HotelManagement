@extends('template.master')
@section('title', 'Restaurant - Catégories')
@section('content')

@include('restaurant.partials.nav-tabs')

<div class="db-page">
    <div class="db-header anim-1">
        <div>
            <h1 class="db-title-h1">Gestion des Catégories</h1>
            <p class="text-muted small">Organisez vos menus par types de plats</p>
        </div>
        <button type="button" class="btn-db-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
            <i class="fas fa-plus"></i> Nouvelle Catégorie
        </button>
    </div>

    <div class="db-card anim-2 mt-4">
        <div class="table-responsive">
            <table class="table db-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Slug</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td><code class="small text-muted">{{ $category->slug }}</code></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="editCategory({{ $category->id }}, '{{ addslashes($category->name) }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="confirmDelete({{ $category->id }}, '{{ addslashes($category->name) }}', {{ $category->menus_count }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $category->id }}" action="{{ route('restaurant.categories.destroy', $category->id) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
                                Aucune catégorie définie.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Création -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title fw-bold">Nouvelle Catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('restaurant.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-600">Nom de la catégorie</label>
                        <input type="text" class="form-control" name="name" required placeholder="Ex: Entrées, Boissons...">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary px-4">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edition -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title fw-bold">Modifier la Catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label fw-600">Nom de la catégorie</label>
                        <input type="text" class="form-control" name="name" id="edit_name" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary px-4">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editCategory(id, name) {
    const form = document.getElementById('editCategoryForm');
    form.action = `/restaurant/categorie/${id}`;
    document.getElementById('edit_name').value = name;
    new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
}

function confirmDelete(id, name, count) {
    let message = `Voulez-vous vraiment supprimer la catégorie "<strong>${name}</strong>" ?`;
    if (count > 0) {
        message += `<br><br><span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Attention : Cette catégorie contient <strong>${count} plat(s)</strong>.</span>`;
    }

    Swal.fire({
        title: 'Confirmation',
        html: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    });
}
</script>

@endsection
