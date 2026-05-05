@extends('template.master')
@section('title', 'Restaurant - Menus')
@section('content')

@include('restaurant.partials.nav-tabs')

<style>
/* Page Specific Styles */
.menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.category-tag {
    position: absolute; top: 12px; left: 12px;
    background: var(--g100); color: var(--g800);
    padding: 3px 10px; border-radius: 6px;
    font-size: .65rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .5px; z-index: 2;
}

.menu-actions {
    display: flex; align-items: center; justify-content: space-between;
    gap: 10px; padding-top: 15px; border-top: 1px solid var(--s100);
}

.btn-add-menu {
    flex: 1; height: 36px; border: none;
    background: var(--g600); color: white;
    border-radius: 8px; font-weight: 600; font-size: .82rem;
    cursor: pointer; transition: var(--transition);
}
.btn-add-menu:hover { background: var(--g700); transform: translateY(-1px); }

.btn-icon-sm {
    width: 34px; height: 34px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    background: var(--white); border: 1.5px solid var(--s200);
    color: var(--s500); transition: var(--transition); text-decoration: none;
}
.btn-icon-sm:hover { border-color: var(--g300); color: var(--g600); background: var(--g50); }
.btn-icon-danger:hover { border-color: #fca5a5; color: #dc2626; background: #fef2f2; }

#cart-counter-pill {
    background: #dc3545; color: white;
    font-size: .65rem; padding: 2px 6px;
    border-radius: 100px; font-weight: 700;
    margin-left: 4px; border: 1.5px solid white;
}
</style>

<div class="db-page">
    <div class="db-header anim-1">
        <div>
            <h1 class="db-title-h1">Gestion de la Carte</h1>
            <p class="text-muted small">Configurez et gérez les menus de votre restaurant</p>
        </div>
        <a href="{{ route('restaurant.create') }}" class="btn-db-primary">
            <i class="fas fa-plus"></i> Nouveau Menu
        </a>
    </div>

    <div class="db-card anim-2">
        <div class="filter-row">
            <select class="db-input" id="categoryFilter" style="width: 200px;">
                <option value="">Toutes les catégories</option>
                <option value="plat">Plats</option>
                <option value="boisson">Boissons</option>
                <option value="dessert">Desserts</option>
                <option value="entree">Entrées</option>
            </select>
            <div style="flex: 1; position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 14px; top: 14px; color: var(--s400);"></i>
                <input type="text" class="db-input w-100" id="searchMenu" placeholder="Rechercher une spécialité..." style="padding-left: 40px;">
            </div>
            <button class="btn-cart-pill open-cart-modal" type="button">
                <i class="fas fa-shopping-basket"></i> Panier
                <span id="cart-counter-pill" style="display: none;">0</span>
            </button>
        </div>

        <div class="menu-grid" id="menuList">
            @forelse($menus as $menu)
            <div class="menu-item anim-3" data-category="{{ $menu->category }}">
                <div class="db-item-card">
                    <div class="db-item-img">
                        @if($menu->image)
                            <img src="{{ $menu->image_url }}" 
                                 alt="{{ $menu->name }}"
                                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-100 h-100 bg-light d-flex align-items-center justify-content-center\'><i class=\'fas fa-utensils fa-3x text-muted\'></i></div>';">
                        @else
                            <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-utensils fa-3x text-muted"></i>
                            </div>
                        @endif
                        <span class="category-tag">{{ $menu->category }}</span>
                        <div class="db-price-tag">{{ number_format($menu->price, 0, ',', ' ') }} CFA</div>
                    </div>
                    <div class="db-item-content">
                        <h3 class="menu-title">{{ $menu->name }}</h3>
                        <p class="menu-desc">{{ Str::limit($menu->description, 70) }}</p>
                        
                        <div class="menu-actions">
                            <div class="flex-grow-1" data-id="{{ $menu->id }}">
                                <button class="btn-add-menu add-to-order" 
                                        id="main-add-btn-{{ $menu->id }}"
                                        data-menu-id="{{ $menu->id }}" 
                                        data-menu-name="{{ $menu->name }}" 
                                        data-menu-price="{{ $menu->price }}">
                                    <i class="fas fa-plus-circle"></i> Ajouter
                                </button>
                                
                                <div class="db-qty-pill d-none" id="main-qty-wrapper-{{ $menu->id }}">
                                    <button class="db-qty-btn main-qminus" data-id="{{ $menu->id }}"><i class="fas fa-minus"></i></button>
                                    <span class="db-qty-val" id="main-qval-{{ $menu->id }}">0</span>
                                    <button class="db-qty-btn main-qplus" data-id="{{ $menu->id }}"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <a href="{{ route('restaurant.menus.edit', $menu->id) }}" class="btn-icon-sm" title="Modifier"><i class="fas fa-pen"></i></a>
                                <button class="btn-icon-sm btn-icon-danger delete-menu" data-id="{{ $menu->id }}" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div style="font-size: 4rem; color: var(--s200); margin-bottom: 20px;"><i class="fas fa-scroll"></i></div>
                <h4 class="fw-bold">Carte vide</h4>
                <p class="text-muted">Aucun menu n'a encore été ajouté.</p>
                <a href="{{ route('restaurant.create') }}" class="btn-db-primary mt-3">Ajouter le premier menu</a>
            </div>
            @endforelse
        </div>

        @if($menus->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $menus->links("pagination::bootstrap-5") }}
        </div>
        @endif
    </div>
</div>

@include('restaurant.partials.new-order-modal')
@endsection


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
                const titleEl = item.querySelector('.menu-title');
                if (titleEl) {
                    const title = titleEl.textContent.toLowerCase();
                    item.style.display = title.includes(q) ? '' : 'none';
                }
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

        // --- 2. Ajouter au Panier Silencieusement ---
        const btnOrder = e.target.closest('.add-to-order');
        if (btnOrder) {
            e.preventDefault();
            const id = btnOrder.dataset.menuId;
            try {
                // Clic silencieux sur le bouton d'ajout interne au modal (gardé en mémoire)
                const addBtn = document.getElementById('naddbtn-' + id);
                if (addBtn) {
                    addBtn.click();
                } else {
                    console.warn("Lien addBtn introuvable. JS du modal pas prêt?");
                }
            } catch(e) {
                console.error("Action commander échouée :", e);
            }
        }

        // --- 2.1 Incrementation / Decrementation depuis la page principale ---
        const btnPlus = e.target.closest('.main-qplus');
        if (btnPlus) {
            const id = btnPlus.dataset.id;
            const target = document.querySelector(`#nqval-${id}`)?.nextElementSibling; // the .nom-qplus button
            if (target) target.click();
        }

        const btnMinus = e.target.closest('.main-qminus');
        if (btnMinus) {
            const id = btnMinus.dataset.id;
            const target = document.querySelector(`#nqty-${id} .nom-qminus`);
            if (target) target.click();
        }
        // --- 3. Panier Global ---
        const btnCart = e.target.closest('.open-cart-modal');
        if (btnCart) {
            e.preventDefault();
            const el = document.getElementById('newOrderModal');
            if (window.bootstrap && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(el).show();
            } else if (window.$) {
                $(el).modal('show');
            }
        }
    });

    // Observer pour mettre à jour les compteurs de la page en fonction du modal
    setInterval(() => {
        const modalCounter = document.getElementById('cart-counter-pill');
        
        if (modalCounter) {
            const count = parseInt(modalCounter.innerText) || 0;
            
            // Sync individual quantities
            document.querySelectorAll('.qty-pill').forEach(pill => {
                const id = pill.id.replace('main-qty-wrapper-', '');
                const modalQty = document.getElementById('nqval-' + id);
                
                if (modalQty) {
                    const qty = parseInt(modalQty.innerText) || 0;
                    const mainQtyVal = document.getElementById('main-qval-' + id);
                    if (mainQtyVal) mainQtyVal.innerText = qty;
                    
                    const addBtn = document.getElementById('main-add-btn-' + id);
                    if (qty > 0) {
                        pill.classList.remove('d-none');
                        if (addBtn) addBtn.classList.add('d-none');
                    } else {
                        pill.classList.add('d-none');
                        if (addBtn) addBtn.classList.remove('d-none');
                    }
                }
            });
        }
    }, 500);
</script>
@endpush