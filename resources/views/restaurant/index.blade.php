@extends('template.master')
@section('title', 'Restaurant - Menus')
@section('content')

@include('restaurant.partials.nav-tabs')

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
                        <img src="{{ $menu->image_url }}" class="card-img-top" alt="{{ $menu->name }}" style="height: 200px; object-fit: cover;" onerror="this.onerror=null; this.src='https://i.pinimg.com/736x/fc/7a/4a/fc7a4ad5e3299c1dac28baa60eef6111.jpg';">
                        @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-utensils fa-3x text-muted"></i>
                        </div>
                        @endif
                        <span class="badge bg-primary position-absolute top-0 end-0 m-2">
                            {{ number_format($menu->price, 0, ',', ' ') }} FCFA
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
                                <a href="{{ route('restaurant.menus.edit', $menu->id) }}" class="btn btn-sm btn-outline-secondary me-1"><i class="fas fa-edit"></i></a>
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
                {{ $menus->links("pagination::bootstrap-5") }}
            </div>
        </div>
        @endif
    </div>
</div>

@include('restaurant.partials.new-order-modal')
@endsection

@push('styles')
<style>
/* ── Cartes menu ── */
.menu-card { transition:transform .3s ease, box-shadow .3s ease; }
.menu-card:hover { transform:translateY(-5px); box-shadow:0 5px 15px rgba(0,0,0,.1); }
</style>
@endpush

@push('scripts')
<script>
    // Filtres en Vanilla JS
    document.addEventListener('change', function(e) {
        if (e.target.id === 'categoryFilter') {
            const cat = e.target.value;
            document.querySelectorAll('.menu-item').forEach(item => {
                if (cat) {
                    item.style.display = item.dataset.category === cat ? '' : 'none';
                } else {
                    item.style.display = '';
                }
            });
        }
    });

    document.addEventListener('input', function(e) {
        if (e.target.id === 'searchMenu') {
            const q = e.target.value.toLowerCase();
            document.querySelectorAll('.menu-item').forEach(item => {
                const title = item.querySelector('.card-title').textContent.toLowerCase();
                item.style.display = title.includes(q) ? '' : 'none';
            });
        }
    });

    // Écouteurs de clics (Délégation globale Vanilla)
    document.addEventListener('click', function(e) {
        // --- 1. Suppression ---
        const btnDelete = e.target.closest('.delete-menu');
        if (btnDelete) {
            e.preventDefault();
            const menuId = btnDelete.dataset.id;
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: 'Ce menu sera supprimé !',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer !',
                cancelButtonText: 'Annuler'
            }).then(r => {
                if (r.isConfirmed) {
                    fetch('/restaurant/menus/' + menuId, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: '_method=DELETE&_token=' + (document.querySelector('meta[name="csrf-token"]')?.content || '')
                    })
                    .then(res => res.json())
                    .then(() => location.reload())
                    .catch(() => alert('Erreur lors de la suppression'));
                }
            });
        }

        // --- 2. Commander ---
        const btnOrder = e.target.closest('.add-to-order');
        if (btnOrder) {
            e.preventDefault();
            const id = btnOrder.dataset.menuId;
            try {
                const el = document.getElementById('newOrderModal');
                if (window.bootstrap && bootstrap.Modal) {
                    bootstrap.Modal.getOrCreateInstance(el).show();
                } else if (window.$) {
                    $(el).modal('show');
                }
                
                // Simulation du clic sur l'ajout spécifique dans le modal
                setTimeout(() => {
                    const addBtn = document.getElementById('naddbtn-' + id);
                    if (addBtn) addBtn.click();
                    else console.warn("Lien modal introuvable");
                }, 200);
            } catch(e) {
                console.error("Action commander échouée :", e);
            }
        }
    });
</script>
@endpush