@extends('template.master')
@section('title', 'Restaurant - Menus')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Gestion des Menus</h3>
    <a href="{{ route('restaurant.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Ajouter un Menu
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <!-- Filtres par catégorie -->
        <div class="row mb-4">
            <div class="col-md-3">
                <select class="form-select" id="categoryFilter">
                    <option value="">Toutes les catégories</option>
                    <option value="plat">Plats</option>
                    <option value="boisson">Boissons</option>
                    <option value="dessert">Desserts</option>
                    <option value="entree">Entrées</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" id="searchMenu" placeholder="Rechercher un menu...">
            </div>
        </div>

        <!-- Liste des menus -->
        <div class="row" id="menuList">
            @forelse($menus as $menu)
            <div class="col-xl-3 col-lg-4 col-md-6 menu-item" data-category="{{ $menu->category }}">
                <div class="card menu-card mb-4 border">
                    <div class="position-relative">
                        @if($menu->image)
                        <img src="{{ asset('storage/' . $menu->image) }}" class="card-img-top" alt="{{ $menu->name }}" style="height: 200px; object-fit: cover;">
                        @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-utensils fa-3x text-muted"></i>
                        </div>
                        @endif
                        <span class="badge bg-primary position-absolute top-0 end-0 m-2">
                            {{ number_format($menu->price, 2) }} €
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $menu->name }}</h5>
                            <span class="badge bg-info">{{ ucfirst($menu->category) }}</span>
                        </div>
                        <p class="card-text text-muted mb-3">
                            {{ Str::limit($menu->description, 100) }}
                        </p>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-sm btn-outline-primary add-to-order" 
                                    data-menu-id="{{ $menu->id }}" 
                                    data-menu-name="{{ $menu->name }}" 
                                    data-menu-price="{{ $menu->price }}">
                                <i class="fas fa-cart-plus me-1"></i> Commander
                            </button>
                            <div>
                                <a href="#" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger delete-menu" data-id="{{ $menu->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-utensils fa-4x text-muted mb-3"></i>
                    <h4>Aucun menu disponible</h4>
                    <p class="text-muted">Commencez par ajouter des menus à votre restaurant.</p>
                    <a href="{{ route('restaurant.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Ajouter le premier menu
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($menus->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                {{ $menus->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal pour la commande -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle Commande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="orderForm" action="{{ route('restaurant.orders.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Client</label>
                        <select class="form-select" name="customer_id" id="customerSelect" required>
                            <option value="">Sélectionner un client</option>
                            @foreach($customers ?? [] as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }} - Chambre {{ $customer->room_number ?? 'N/A' }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Menu sélectionné</label>
                        <div class="alert alert-info" id="selectedMenuInfo">
                            Aucun menu sélectionné
                        </div>
                        <input type="hidden" name="menu_id" id="selectedMenuId">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quantité</label>
                        <input type="number" class="form-control" name="quantity" value="1" min="1" max="10">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes supplémentaires</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Ex: Sans gluten, épicé..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer la commande</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
$(document).ready(function() {
    // Filtrage par catégorie
    $('#categoryFilter').change(function() {
        const category = $(this).val();
        if (category) {
            $('.menu-item').hide();
            $(`.menu-item[data-category="${category}"]`).show();
        } else {
            $('.menu-item').show();
        }
    });

    // Recherche de menu
    $('#searchMenu').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('.menu-item').each(function() {
            const menuName = $(this).find('.card-title').text().toLowerCase();
            $(this).toggle(menuName.includes(searchTerm));
        });
    });

    // Ajouter au panier
    $('.add-to-order').click(function() {
        const menuId = $(this).data('menu-id');
        const menuName = $(this).data('menu-name');
        const menuPrice = $(this).data('menu-price');
        
        $('#selectedMenuId').val(menuId);
        $('#selectedMenuInfo').html(`
            <strong>${menuName}</strong><br>
            Prix unitaire: ${menuPrice} €
        `);
        
        $('#orderModal').modal('show');
    });

    // Supprimer un menu
    $('.delete-menu').click(function() {
        const menuId = $(this).data('id');
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: 'Êtes-vous sûr ?',
            text: "Ce menu sera supprimé définitivement !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui, supprimer !',
            cancelButtonText: 'Annuler',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('restaurant/menus') }}/${menuId}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire(
                            'Supprimé !',
                            'Le menu a été supprimé avec succès.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire(
                            'Erreur !',
                            'Une erreur est survenue lors de la suppression.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>

<style>
.menu-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.menu-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.menu-card .card-img-top {
    border-top-left-radius: 0.375rem;
    border-top-right-radius: 0.375rem;
}

.badge.bg-info {
    background-color: #17a2b8 !important;
}
</style>
@endsection