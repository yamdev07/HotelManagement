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
                        <img src="{{ $menu->image_url }}" class="card-img-top" alt="{{ $menu->name }}" style="height: 200px; object-fit: cover;">
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

<!-- ════════════════════════════════════════════════
     MODAL COMMANDE — ADMIN 5 ÉTOILES
════════════════════════════════════════════════ -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content nom-card">

            <div class="nom-header">
                <div class="nom-header-left">
                    <div class="nom-icon-wrap"><i class="fas fa-utensils"></i></div>
                    <div>
                        <div class="nom-title">Nouvelle Commande</div>
                        <div class="nom-subtitle">Restaurant — Interface Administration</div>
                    </div>
                </div>
                <button type="button" class="nom-close" data-bs-dismiss="modal">✕</button>
            </div>

            <div class="nom-steps">
                <div class="nom-step active" data-step="1"><div class="nom-dot">1</div><span>Client</span></div>
                <div class="nom-step-line"></div>
                <div class="nom-step" data-step="2"><div class="nom-dot">2</div><span>Plats</span></div>
                <div class="nom-step-line"></div>
                <div class="nom-step" data-step="3"><div class="nom-dot">3</div><span>Préférences</span></div>
                <div class="nom-step-line"></div>
                <div class="nom-step" data-step="4"><div class="nom-dot">4</div><span>Confirmation</span></div>
            </div>

            <form action="{{ route('restaurant.orders.store') }}" method="POST" id="orderForm">
            @csrf
            <input type="hidden" name="customer_id"    id="h-cid">
            <input type="hidden" name="customer_name"  id="h-cname">
            <input type="hidden" name="phone"          id="h-cphone">
            <input type="hidden" name="email"          id="h-cemail">
            <input type="hidden" name="room_number"    id="h-croom">
            <input type="hidden" name="items"          id="h-citems">
            <input type="hidden" name="total"          id="h-ctotal">
            <input type="hidden" name="notes"          id="h-cnotes">
            <input type="hidden" name="payment_method" id="h-cpayment" value="cash">

            <div class="nom-body">

                {{-- ÉTAPE 1 : Client --}}
                <div class="nom-panel active" id="panel-1">
                    <div class="nom-panel-title"><i class="fas fa-user me-2"></i>Identification du client</div>
                    <p class="nom-desc">Sélectionnez un client existant ou saisissez ses informations.</p>

                    <div class="nom-toggle-row mb-4">
                        <button type="button" class="nom-toggle active" id="tog-ex">Client existant</button>
                        <button type="button" class="nom-toggle" id="tog-nw">Saisie manuelle</button>
                    </div>

                    <div id="blk-ex">
                        <div class="nom-field">
                            <label class="nom-label">Sélectionner un client <span class="nom-req">*</span></label>
                            <select class="nom-input nom-select" id="sel-client">
                                <option value="">— Choisir un client —</option>
                                @foreach($customers ?? [] as $c)
                                <option value="{{ $c->id }}" data-name="{{ $c->name }}"
                                        data-room="{{ $c->room_number ?? '' }}"
                                        data-phone="{{ $c->phone ?? '' }}"
                                        data-email="{{ $c->email ?? '' }}">
                                    {{ $c->name }}{{ !empty($c->room_number) ? ' — Ch. '.$c->room_number : '' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="nom-grid-3 mt-3" id="ex-info" style="display:none">
                            <div class="nom-info-card"><span class="nom-ic-label">Chambre</span><span class="nom-ic-val" id="d-room">—</span></div>
                            <div class="nom-info-card"><span class="nom-ic-label">Téléphone</span><span class="nom-ic-val" id="d-phone">—</span></div>
                            <div class="nom-info-card"><span class="nom-ic-label">Email</span><span class="nom-ic-val" id="d-email">—</span></div>
                        </div>
                    </div>

                    <div id="blk-nw" style="display:none">
                        <div class="nom-grid-2">
                            <div class="nom-field">
                                <label class="nom-label">Prénom <span class="nom-req">*</span></label>
                                <input type="text" class="nom-input" id="nw-prenom" placeholder="Prénom">
                                <div class="nom-err" id="err-prenom"></div>
                            </div>
                            <div class="nom-field">
                                <label class="nom-label">Nom <span class="nom-req">*</span></label>
                                <input type="text" class="nom-input" id="nw-nom" placeholder="Nom de famille">
                                <div class="nom-err" id="err-nom"></div>
                            </div>
                            <div class="nom-field">
                                <label class="nom-label">Téléphone <span class="nom-req">*</span></label>
                                <input type="tel" class="nom-input" id="nw-phone" placeholder="+33 6 00 00 00 00">
                                <div class="nom-err" id="err-phone"></div>
                            </div>
                            <div class="nom-field">
                                <label class="nom-label">Email</label>
                                <input type="email" class="nom-input" id="nw-email" placeholder="email@exemple.com">
                            </div>
                            <div class="nom-field">
                                <label class="nom-label">N° de chambre</label>
                                <input type="text" class="nom-input" id="nw-room" placeholder="Ex : 214">
                            </div>
                            <div class="nom-field">
                                <label class="nom-label">Occasion</label>
                                <select class="nom-input nom-select" id="nw-occasion">
                                    <option value="">— Sélectionner —</option>
                                    <option>🌹 Dîner romantique</option>
                                    <option>🎂 Anniversaire</option>
                                    <option>💼 Repas d'affaires</option>
                                    <option>👨‍👩‍👧 Famille</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="nom-err mt-2" id="err-client"></div>
                </div>

                {{-- ÉTAPE 2 : Plats --}}
                <div class="nom-panel" id="panel-2">
                    <div class="nom-panel-title"><i class="fas fa-utensils me-2"></i>Composition du repas</div>
                    <p class="nom-desc">Ajoutez ou ajustez les plats. Le plat sélectionné est déjà ajouté.</p>

                    <div class="nom-filters">
                        <button type="button" class="nom-filter active" data-cat="all">Tous</button>
                        <button type="button" class="nom-filter" data-cat="entree">Entrées</button>
                        <button type="button" class="nom-filter" data-cat="plat">Plats</button>
                        <button type="button" class="nom-filter" data-cat="dessert">Desserts</button>
                        <button type="button" class="nom-filter" data-cat="boisson">Boissons</button>
                    </div>

                    <div class="nom-menu-grid" id="order-menu-grid">
                        @foreach($menus as $menu)
                        <div class="nom-dish" data-cat="{{ $menu->category }}"
                             data-id="{{ $menu->id }}" data-name="{{ $menu->name }}" data-price="{{ $menu->price }}">
                            <div class="nom-dish-img">
                                @if($menu->image)
                                    <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}">
                                @else
                                    <div class="nom-dish-noimg"><i class="fas fa-utensils"></i></div>
                                @endif
                            </div>
                            <div class="nom-dish-body">
                                <div class="nom-dish-name">{{ $menu->name }}</div>
                                @if($menu->description)
                                <div class="nom-dish-desc">{{ Str::limit($menu->description, 55) }}</div>
                                @endif
                                <div class="nom-dish-footer">
                                    <span class="nom-dish-price">{{ number_format($menu->price, 0, ',', ' ') }} CFA</span>
                                    <div class="nom-qty" id="qty-{{ $menu->id }}" style="display:none">
                                        <button type="button" class="nom-qty-btn qminus" data-id="{{ $menu->id }}">−</button>
                                        <span class="qval" id="qval-{{ $menu->id }}">0</span>
                                        <button type="button" class="nom-qty-btn qplus" data-id="{{ $menu->id }}">+</button>
                                    </div>
                                    <button type="button" class="nom-add-btn" id="addbtn-{{ $menu->id }}" data-id="{{ $menu->id }}">
                                        <i class="fas fa-plus"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="nom-basket" id="basket" style="display:none">
                        <div class="nom-basket-title"><i class="fas fa-shopping-cart me-2"></i>Sélection en cours</div>
                        <div id="basket-items"></div>
                        <div class="nom-basket-total">Total : <strong id="basket-total">0 CFA</strong></div>
                    </div>
                    <div class="nom-err mt-2" id="err-items"></div>
                </div>

                {{-- ÉTAPE 3 : Préférences --}}
                <div class="nom-panel" id="panel-3">
                    <div class="nom-panel-title"><i class="fas fa-heart me-2"></i>Préférences & Allergies</div>
                    <p class="nom-desc">Informations importantes pour la préparation.</p>

                    <div class="nom-section-lbl">Allergènes</div>
                    <div class="nom-allergen-grid">
                        <label class="nom-allergen"><input type="checkbox" value="gluten"><span>🌾 Gluten</span></label>
                        <label class="nom-allergen"><input type="checkbox" value="lactose"><span>🥛 Lactose</span></label>
                        <label class="nom-allergen"><input type="checkbox" value="oeufs"><span>🥚 Œufs</span></label>
                        <label class="nom-allergen"><input type="checkbox" value="fruits-a-coque"><span>🥜 Fruits à coque</span></label>
                        <label class="nom-allergen"><input type="checkbox" value="crustaces"><span>🦐 Crustacés</span></label>
                        <label class="nom-allergen"><input type="checkbox" value="poisson"><span>🐟 Poisson</span></label>
                        <label class="nom-allergen"><input type="checkbox" value="soja"><span>🫘 Soja</span></label>
                        <label class="nom-allergen"><input type="checkbox" value="celeri"><span>🥬 Céleri</span></label>
                    </div>
                    <div class="nom-field mt-3">
                        <label class="nom-label">Autres allergies</label>
                        <input type="text" class="nom-input" id="al-custom" placeholder="Ex : arachides, alcool…">
                    </div>

                    <div class="nom-section-lbl mt-4">Cuisson</div>
                    <div class="nom-radio-row">
                        <label class="nom-radio"><input type="radio" name="cuisson" value="saignant"> Saignant</label>
                        <label class="nom-radio"><input type="radio" name="cuisson" value="a-point" checked> À point</label>
                        <label class="nom-radio"><input type="radio" name="cuisson" value="bien-cuit"> Bien cuit</label>
                    </div>

                    <div class="nom-section-lbl mt-4">Régime alimentaire</div>
                    <div class="nom-radio-row">
                        <label class="nom-radio"><input type="radio" name="regime" value="aucun" checked> Standard</label>
                        <label class="nom-radio"><input type="radio" name="regime" value="vegetarien"> 🥦 Végétarien</label>
                        <label class="nom-radio"><input type="radio" name="regime" value="vegan"> 🌱 Vegan</label>
                        <label class="nom-radio"><input type="radio" name="regime" value="halal"> ☪️ Halal</label>
                        <label class="nom-radio"><input type="radio" name="regime" value="kasher"> ✡️ Kasher</label>
                    </div>

                    <div class="nom-field mt-4">
                        <label class="nom-label">Notes pour le chef</label>
                        <textarea class="nom-input nom-textarea" id="chef-notes" rows="3" placeholder="Cuisson particulière, présentation, message spécial…"></textarea>
                    </div>

                    <div class="nom-section-lbl mt-4">Mode de règlement</div>
                    <div class="nom-pay-grid">
                        <label class="nom-pay"><input type="radio" name="payment" value="cash" checked>
                            <div class="nom-pay-body"><span>💵</span><span>Espèces</span></div></label>
                        <label class="nom-pay"><input type="radio" name="payment" value="card">
                            <div class="nom-pay-body"><span>💳</span><span>Carte</span></div></label>
                        <label class="nom-pay"><input type="radio" name="payment" value="room_charge">
                            <div class="nom-pay-body"><span>🔑</span><span>Chambre</span></div></label>
                        <label class="nom-pay"><input type="radio" name="payment" value="online">
                            <div class="nom-pay-body"><span>📲</span><span>En ligne</span></div></label>
                    </div>
                </div>

                {{-- ÉTAPE 4 : Récapitulatif --}}
                <div class="nom-panel" id="panel-4">
                    <div class="nom-panel-title"><i class="fas fa-check-circle me-2 text-success"></i>Récapitulatif</div>
                    <div class="nom-recap-grid">
                        <div class="nom-recap-block">
                            <div class="nom-recap-title"><i class="fas fa-user me-1"></i> Client</div>
                            <div id="rc-client"></div>
                        </div>
                        <div class="nom-recap-block">
                            <div class="nom-recap-title"><i class="fas fa-heart me-1"></i> Préférences</div>
                            <div id="rc-prefs"></div>
                        </div>
                    </div>
                    <div class="nom-recap-block mt-3">
                        <div class="nom-recap-title"><i class="fas fa-utensils me-1"></i> Plats commandés</div>
                        <div id="rc-items"></div>
                        <div class="nom-recap-total">Total : <strong id="rc-total">0 CFA</strong></div>
                    </div>
                </div>

            </div>

            <div class="nom-footer">
                <button type="button" class="nom-btn nom-btn-ghost" id="btn-prev" style="display:none">
                    <i class="fas fa-arrow-left me-1"></i> Précédent
                </button>
                <div class="ms-auto d-flex gap-2">
                    <button type="button" class="nom-btn nom-btn-outline" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="nom-btn nom-btn-primary" id="btn-next">
                        Suivant <i class="fas fa-arrow-right ms-1"></i>
                    </button>
                    <button type="submit" class="nom-btn nom-btn-success" id="btn-submit" style="display:none">
                        <i class="fas fa-check me-1"></i> Enregistrer la commande
                    </button>
                </div>
            </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ── Cartes menu ── */
.menu-card { transition:transform .3s ease, box-shadow .3s ease; }
.menu-card:hover { transform:translateY(-5px); box-shadow:0 5px 15px rgba(0,0,0,.1); }

/* ══════════════════════════════════════
   MODAL COMMANDE (styles partagés)
══════════════════════════════════════ */
.nom-card { border:none; border-radius:16px; overflow:hidden; background:#fff; box-shadow:0 30px 70px rgba(0,0,0,.18); }
.nom-header { display:flex; align-items:center; justify-content:space-between; padding:18px 24px; background:linear-gradient(135deg,#1e293b,#0f172a); border-bottom:2px solid #d4af37; }
.nom-header-left { display:flex; align-items:center; gap:12px; }
.nom-icon-wrap { width:38px; height:38px; background:#d4af37; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#0f172a; font-size:1rem; }
.nom-title { font-size:1rem; font-weight:700; color:#f8fafc; }
.nom-subtitle { font-size:.7rem; color:#94a3b8; margin-top:1px; }
.nom-close { background:transparent; border:1px solid rgba(255,255,255,.15); color:#94a3b8; width:30px; height:30px; border-radius:50%; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:.8rem; transition:all .2s; }
.nom-close:hover { background:rgba(255,255,255,.1); color:#fff; }
.nom-steps { display:flex; align-items:center; padding:14px 24px; background:#f8fafc; border-bottom:1px solid #e2e8f0; }
.nom-step { display:flex; align-items:center; gap:6px; flex:1; }
.nom-dot { width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:700; flex-shrink:0; border:2px solid #e2e8f0; color:#94a3b8; background:#fff; transition:all .3s; }
.nom-step span { font-size:.72rem; color:#94a3b8; white-space:nowrap; }
.nom-step.active .nom-dot { background:#d4af37; border-color:#d4af37; color:#0f172a; }
.nom-step.active span { color:#92740a; font-weight:600; }
.nom-step.done .nom-dot { background:#10b981; border-color:#10b981; color:#fff; }
.nom-step.done span { color:#10b981; }
.nom-step-line { flex:1; height:1px; background:#e2e8f0; margin:0 6px; }
.nom-body { padding:24px; background:#fff; min-height:320px; }
.nom-panel { display:none; animation:nomIn .28s ease; }
.nom-panel.active { display:block; }
@keyframes nomIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:none} }
.nom-panel-title { font-size:.95rem; font-weight:700; color:#0f172a; margin-bottom:4px; }
.nom-desc { font-size:.78rem; color:#94a3b8; margin-bottom:18px; }
.nom-toggle-row { display:flex; border:1px solid #e2e8f0; border-radius:8px; overflow:hidden; width:fit-content; }
.nom-toggle { padding:7px 18px; font-size:.78rem; font-weight:600; cursor:pointer; border:none; background:#f8fafc; color:#64748b; transition:all .18s; }
.nom-toggle.active { background:#0f172a; color:#fff; }
.nom-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.nom-grid-3 { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
@media(max-width:600px){ .nom-grid-2,.nom-grid-3{grid-template-columns:1fr;} }
.nom-field { display:flex; flex-direction:column; gap:4px; }
.nom-label { font-size:.7rem; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:.05em; }
.nom-req { color:#d4af37; }
.nom-input { padding:10px 13px; border:1px solid #e2e8f0; border-radius:8px; font-size:.84rem; color:#1e293b; background:#f8fafc; width:100%; transition:border-color .18s,box-shadow .18s; outline:none; }
.nom-input:focus { border-color:#d4af37; box-shadow:0 0 0 3px rgba(212,175,55,.12); background:#fff; }
.nom-select { appearance:none; cursor:pointer; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%2394a3b8'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 13px center; padding-right:34px; }
.nom-select option { background:#fff; }
.nom-textarea { resize:vertical; min-height:80px; }
.nom-err { font-size:.72rem; color:#e11d48; min-height:14px; }
.nom-info-card { background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:10px 14px; display:flex; flex-direction:column; gap:3px; }
.nom-ic-label { font-size:.64rem; text-transform:uppercase; color:#94a3b8; font-weight:600; }
.nom-ic-val { font-size:.84rem; color:#1e293b; font-weight:600; }
.nom-filters { display:flex; flex-wrap:wrap; gap:6px; margin-bottom:14px; }
.nom-filter { padding:5px 14px; border-radius:20px; font-size:.74rem; font-weight:600; cursor:pointer; border:1px solid #e2e8f0; background:#f8fafc; color:#64748b; transition:all .16s; }
.nom-filter.active { background:#0f172a; border-color:#0f172a; color:#fff; }
.nom-filter:hover:not(.active) { border-color:#0f172a; color:#0f172a; }
.nom-menu-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(190px,1fr)); gap:12px; max-height:320px; overflow-y:auto; scrollbar-width:thin; scrollbar-color:#e2e8f0 transparent; padding-right:4px; margin-bottom:14px; }
.nom-dish { border:1px solid #e2e8f0; border-radius:10px; overflow:hidden; background:#fff; cursor:pointer; transition:border-color .18s,transform .18s; }
.nom-dish:hover { border-color:#d4af37; transform:translateY(-2px); }
.nom-dish.selected { border-color:#d4af37; background:#fffbeb; }
.nom-dish-img { height:90px; overflow:hidden; background:#f1f5f9; display:flex; align-items:center; justify-content:center; }
.nom-dish-img img { width:100%; height:100%; object-fit:cover; }
.nom-dish-noimg { font-size:1.8rem; color:#cbd5e1; }
.nom-dish-body { padding:9px 11px; }
.nom-dish-name { font-size:.8rem; font-weight:700; color:#0f172a; margin-bottom:2px; }
.nom-dish-desc { font-size:.68rem; color:#94a3b8; line-height:1.4; margin-bottom:7px; }
.nom-dish-footer { display:flex; align-items:center; justify-content:space-between; gap:5px; }
.nom-dish-price { font-size:.78rem; color:#d4af37; font-weight:700; white-space:nowrap; }
.nom-add-btn { background:#0f172a; color:#fff; border:none; border-radius:6px; font-size:.68rem; font-weight:600; padding:4px 9px; cursor:pointer; transition:background .16s; white-space:nowrap; }
.nom-add-btn:hover { background:#1e293b; }
.nom-qty { display:flex; align-items:center; gap:5px; }
.nom-qty-btn { width:22px; height:22px; border-radius:50%; border:1px solid #d4af37; background:transparent; color:#d4af37; font-size:.9rem; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .14s; }
.nom-qty-btn:hover { background:#d4af37; color:#0f172a; }
.qval { font-size:.8rem; font-weight:700; color:#0f172a; min-width:16px; text-align:center; }
.nom-basket { background:#fffbeb; border:1px solid #fde68a; border-radius:10px; padding:12px 14px; }
.nom-basket-title { font-size:.76rem; font-weight:700; color:#92740a; margin-bottom:8px; }
.nom-basket-item { display:flex; justify-content:space-between; font-size:.76rem; color:#64748b; padding:4px 0; border-bottom:1px solid #fde68a; }
.nom-basket-item:last-child { border-bottom:none; }
.nom-basket-total { font-size:.8rem; color:#92740a; font-weight:700; text-align:right; margin-top:8px; }
.nom-section-lbl { font-size:.68rem; text-transform:uppercase; letter-spacing:.07em; color:#94a3b8; font-weight:700; margin-bottom:8px; }
.nom-allergen-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:8px; }
@media(max-width:600px){ .nom-allergen-grid{grid-template-columns:repeat(2,1fr);} }
.nom-allergen { display:flex; align-items:center; gap:7px; padding:8px 11px; border:1px solid #e2e8f0; border-radius:8px; cursor:pointer; background:#f8fafc; font-size:.76rem; color:#64748b; transition:all .16s; }
.nom-allergen:has(input:checked) { border-color:#f87171; background:#fff1f2; color:#e11d48; }
.nom-allergen input { display:none; }
.nom-radio-row { display:flex; flex-wrap:wrap; gap:8px; }
.nom-radio { padding:7px 13px; border:1px solid #e2e8f0; border-radius:8px; cursor:pointer; background:#f8fafc; font-size:.76rem; color:#64748b; transition:all .16s; }
.nom-radio:has(input:checked) { border-color:#d4af37; background:#fffbeb; color:#92740a; font-weight:600; }
.nom-radio input { display:none; }
.nom-pay-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:8px; }
@media(max-width:600px){ .nom-pay-grid{grid-template-columns:repeat(2,1fr);} }
.nom-pay { cursor:pointer; border:1px solid #e2e8f0; border-radius:9px; background:#f8fafc; transition:all .16s; }
.nom-pay:has(input:checked) { border-color:#d4af37; background:#fffbeb; }
.nom-pay input { display:none; }
.nom-pay-body { display:flex; flex-direction:column; align-items:center; padding:12px 8px; gap:5px; font-size:.72rem; color:#64748b; text-align:center; }
.nom-pay:has(input:checked) .nom-pay-body { color:#92740a; font-weight:600; }
.nom-pay-body span:first-child { font-size:1.3rem; }
.nom-recap-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
@media(max-width:600px){ .nom-recap-grid{grid-template-columns:1fr;} }
.nom-recap-block { background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:14px 16px; }
.nom-recap-title { font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; font-weight:700; margin-bottom:8px; }
.nom-recap-line { display:flex; justify-content:space-between; font-size:.8rem; color:#475569; padding:3px 0; }
.nom-recap-line span { color:#94a3b8; }
.nom-recap-item { display:flex; justify-content:space-between; font-size:.8rem; color:#475569; padding:5px 0; border-bottom:1px solid #e2e8f0; }
.nom-recap-item:last-child { border-bottom:none; }
.nom-recap-total { text-align:right; margin-top:10px; font-size:.88rem; color:#d4af37; font-weight:700; }
.nom-footer { display:flex; align-items:center; padding:14px 24px; background:#f8fafc; border-top:1px solid #e2e8f0; }
.nom-btn { padding:9px 20px; border-radius:8px; font-size:.8rem; font-weight:600; cursor:pointer; border:none; transition:all .16s; display:inline-flex; align-items:center; gap:5px; }
.nom-btn-ghost { background:transparent; color:#94a3b8; border:1px solid #e2e8f0; }
.nom-btn-ghost:hover { color:#475569; }
.nom-btn-outline { background:#fff; color:#64748b; border:1px solid #e2e8f0; }
.nom-btn-outline:hover { border-color:#94a3b8; color:#1e293b; }
.nom-btn-primary { background:#0f172a; color:#fff; }
.nom-btn-primary:hover { background:#1e293b; }
.nom-btn-success { background:#10b981; color:#fff; box-shadow:0 3px 10px rgba(16,185,129,.3); }
.nom-btn-success:hover { background:#059669; }
.nom-btn-success:disabled { opacity:.5; cursor:not-allowed; }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {

    /* ── Filtres de la liste des menus ── */
    $('#categoryFilter').change(function() {
        const cat = $(this).val();
        if (cat) { $('.menu-item').hide(); $(`.menu-item[data-category="${cat}"]`).show(); }
        else { $('.menu-item').show(); }
    });
    $('#searchMenu').on('input', function() {
        const q = $(this).val().toLowerCase();
        $('.menu-item').each(function() {
            $(this).toggle($(this).find('.card-title').text().toLowerCase().includes(q));
        });
    });

    /* ── Supprimer un menu (vanilla JS, pas de dépendance jQuery) ── */
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.delete-menu');
        if (!btn) return;
        const menuId = btn.dataset.id;
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: 'Ce menu sera supprimé définitivement !',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui, supprimer !',
            cancelButtonText: 'Annuler',
            reverseButtons: true
        }).then(r => {
            if (!r.isConfirmed) return;
            fetch(`{{ url('restaurant/menus') }}/${menuId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: `_method=DELETE&_token={{ csrf_token() }}`
            })
            .then(res => res.json())
            .then(data => {
                Swal.fire('Supprimé !', 'Le menu a été supprimé.', 'success')
                    .then(() => location.reload());
            })
            .catch(() => {
                Swal.fire('Erreur !', 'Une erreur est survenue.', 'error');
            });
        });
    });

    /* ════════════════════════════════════════
       MODAL COMMANDE MULTI-ÉTAPES
    ════════════════════════════════════════ */
    let step = 1;
    let orderItems = {};
    let mode = 'existing';

    /* Ouvrir le modal avec le plat pré-sélectionné */
    $('.add-to-order').click(function() {
        const id    = String($(this).data('menu-id'));
        const name  = $(this).data('menu-name');
        const price = parseFloat($(this).data('menu-price'));
        orderItems = {};
        orderItems[id] = { menu_id: id, name, price, quantity: 1 };
        resetModal();
        refreshDishCards();
        renderBasket();
        new bootstrap.Modal(document.getElementById('orderModal')).show();
    });

    /* ── Navigation ── */
    $('#btn-next').click(function() { if (validateStep(step)) goStep(step + 1); });
    $('#btn-prev').click(function() { goStep(step - 1); });

    function goStep(n) {
        if (n < 1 || n > 4) return;
        if (n === 4) buildRecap();
        $('.nom-step').each(function(){
            const s = parseInt($(this).data('step'));
            $(this).toggleClass('active', s === n).toggleClass('done', s < n);
        });
        $('.nom-panel').removeClass('active');
        $(`#panel-${n}`).addClass('active');
        step = n;
        $('#btn-prev').toggle(n > 1);
        $('#btn-next').toggle(n < 4);
        $('#btn-submit').toggle(n === 4);
        if (n === 4) $('#btn-next').hide();
    }

    function validateStep(s) {
        if (s === 1) {
            $('#err-client').text('');
            if (mode === 'existing' && !$('#sel-client').val()) {
                $('#err-client').text('Veuillez sélectionner un client.'); return false;
            }
            if (mode === 'new') {
                let ok = true;
                if (!$('#nw-prenom').val().trim()) { $('#err-prenom').text('Requis.'); ok = false; } else $('#err-prenom').text('');
                if (!$('#nw-nom').val().trim())    { $('#err-nom').text('Requis.'); ok = false; }    else $('#err-nom').text('');
                if (!$('#nw-phone').val().trim())  { $('#err-phone').text('Requis.'); ok = false; }  else $('#err-phone').text('');
                return ok;
            }
        }
        if (s === 2) {
            if (Object.keys(orderItems).length === 0) { $('#err-items').text('Ajoutez au moins un plat.'); return false; }
            $('#err-items').text('');
        }
        return true;
    }

    /* ── Toggle client ── */
    $('#tog-ex').click(function(){
        mode='existing'; $(this).addClass('active'); $('#tog-nw').removeClass('active');
        $('#blk-ex').show(); $('#blk-nw').hide();
    });
    $('#tog-nw').click(function(){
        mode='new'; $(this).addClass('active'); $('#tog-ex').removeClass('active');
        $('#blk-nw').show(); $('#blk-ex').hide();
    });
    $('#sel-client').change(function(){
        const s = $(this).find(':selected');
        if ($(this).val()) {
            $('#d-room').text(s.data('room')||'—'); $('#d-phone').text(s.data('phone')||'—'); $('#d-email').text(s.data('email')||'—');
            $('#ex-info').show();
        } else { $('#ex-info').hide(); }
    });

    /* ── Filtres plats ── */
    $(document).on('click', '.nom-filter', function(){
        $('.nom-filter').removeClass('active'); $(this).addClass('active');
        const cat = $(this).data('cat');
        if (cat === 'all') $('.nom-dish').show();
        else { $('.nom-dish').hide(); $(`.nom-dish[data-cat="${cat}"]`).show(); }
    });

    /* ── Ajout / retrait plats ── */
    $(document).on('click', '.nom-add-btn', function(){
        const id = String($(this).data('id'));
        const d  = $(`.nom-dish[data-id="${id}"]`);
        if (!orderItems[id]) orderItems[id] = { menu_id:id, name:d.data('name'), price:parseFloat(d.data('price')), quantity:1 };
        else orderItems[id].quantity++;
        updateDish(id); renderBasket();
    });
    $(document).on('click', '.qplus', function(){
        const id = String($(this).data('id'));
        if (orderItems[id]) { orderItems[id].quantity++; updateDish(id); renderBasket(); }
    });
    $(document).on('click', '.qminus', function(){
        const id = String($(this).data('id'));
        if (!orderItems[id]) return;
        orderItems[id].quantity--;
        if (orderItems[id].quantity <= 0) delete orderItems[id];
        updateDish(id); renderBasket();
    });

    function updateDish(id) {
        const item = orderItems[id];
        if (item && item.quantity > 0) {
            $(`#addbtn-${id}`).hide(); $(`#qty-${id}`).show(); $(`#qval-${id}`).text(item.quantity);
            $(`.nom-dish[data-id="${id}"]`).addClass('selected');
        } else {
            $(`#addbtn-${id}`).show(); $(`#qty-${id}`).hide(); $(`#qval-${id}`).text(0);
            $(`.nom-dish[data-id="${id}"]`).removeClass('selected');
        }
    }

    function refreshDishCards() {
        $('.nom-dish').removeClass('selected');
        $('.nom-qty').hide(); $('.nom-add-btn').show();
        Object.keys(orderItems).forEach(id => updateDish(id));
    }

    function renderBasket() {
        const items = Object.values(orderItems);
        if (!items.length) { $('#basket').hide(); return; }
        $('#basket').show();
        let html = '', total = 0;
        items.forEach(it => {
            const sub = it.price * it.quantity; total += sub;
            html += `<div class="nom-basket-item"><span>${it.name} × ${it.quantity}</span><strong>${sub.toLocaleString('fr-FR')} CFA</strong></div>`;
        });
        $('#basket-items').html(html);
        $('#basket-total').text(total.toLocaleString('fr-FR') + ' CFA');
    }

    /* ── Récapitulatif ── */
    function buildRecap() {
        let clientHtml = '';
        if (mode === 'existing') {
            const s = $('#sel-client').find(':selected');
            clientHtml = `<div class="nom-recap-line"><span>Client</span>${s.data('name')}</div>`;
            if (s.data('room'))  clientHtml += `<div class="nom-recap-line"><span>Chambre</span>${s.data('room')}</div>`;
            if (s.data('phone')) clientHtml += `<div class="nom-recap-line"><span>Tél.</span>${s.data('phone')}</div>`;
        } else {
            clientHtml = `<div class="nom-recap-line"><span>Nom</span>${$('#nw-prenom').val()} ${$('#nw-nom').val()}</div>
                          <div class="nom-recap-line"><span>Tél.</span>${$('#nw-phone').val()}</div>`;
            if ($('#nw-room').val()) clientHtml += `<div class="nom-recap-line"><span>Chambre</span>${$('#nw-room').val()}</div>`;
        }
        $('#rc-client').html(clientHtml);

        const allergens = [];
        $('.nom-allergen input:checked').each(function(){ allergens.push($(this).val()); });
        const alCustom = $('#al-custom').val().trim();
        if (alCustom) allergens.push(alCustom);
        const cuisson  = $('input[name="cuisson"]:checked').val();
        const regime   = $('input[name="regime"]:checked').val();
        const payment  = $('input[name="payment"]:checked').val();
        let prefHtml = `<div class="nom-recap-line"><span>Cuisson</span>${cuisson}</div>
                        <div class="nom-recap-line"><span>Régime</span>${regime}</div>
                        <div class="nom-recap-line"><span>Paiement</span>${payment}</div>`;
        if (allergens.length) prefHtml += `<div class="nom-recap-line"><span>Allergies</span>${allergens.join(', ')}</div>`;
        $('#rc-prefs').html(prefHtml);

        const items = Object.values(orderItems);
        let itemsHtml = '', total = 0;
        items.forEach(it => {
            const sub = it.price * it.quantity; total += sub;
            itemsHtml += `<div class="nom-recap-item"><span>${it.name} × ${it.quantity}</span><strong>${sub.toLocaleString('fr-FR')} CFA</strong></div>`;
        });
        $('#rc-items').html(itemsHtml);
        $('#rc-total').text(total.toLocaleString('fr-FR') + ' CFA');

        // Remplir les champs cachés
        if (mode === 'existing') {
            const s = $('#sel-client').find(':selected');
            $('#h-cid').val($('#sel-client').val());
            $('#h-cname').val(s.data('name'));
            $('#h-cphone').val(s.data('phone')||'');
            $('#h-cemail').val(s.data('email')||'');
            $('#h-croom').val(s.data('room')||'');
        } else {
            $('#h-cid').val('');
            $('#h-cname').val(($('#nw-prenom').val()+' '+$('#nw-nom').val()).trim());
            $('#h-cphone').val($('#nw-phone').val());
            $('#h-cemail').val($('#nw-email').val());
            $('#h-croom').val($('#nw-room').val());
        }
        const noteParts = [];
        if (allergens.length) noteParts.push('Allergies : ' + allergens.join(', '));
        if (cuisson !== 'a-point') noteParts.push('Cuisson : ' + cuisson);
        if (regime !== 'aucun') noteParts.push('Régime : ' + regime);
        const freeNote = $('#chef-notes').val().trim();
        if (freeNote) noteParts.push(freeNote);
        $('#h-cnotes').val(noteParts.join(' | '));
        $('#h-cpayment').val(payment);
        $('#h-citems').val(JSON.stringify(items.map(i => ({ menu_id: i.menu_id, quantity: i.quantity }))));
        $('#h-ctotal').val(total.toFixed(2));
    }

    /* ── Soumission ── */
    $('#orderForm').submit(function(e) {
        e.preventDefault();
        if (Object.keys(orderItems).length === 0) {
            Swal.fire({ icon:'warning', title:'Aucun plat sélectionné', text:'Ajoutez au moins un plat.' }); return;
        }
        const btn = $('#btn-submit').prop('disabled', true).text('Enregistrement…');
        $.ajax({
            url: $(this).attr('action'), type:'POST', data: new FormData(this), processData:false, contentType:false,
            success: function() {
                Swal.fire({ icon:'success', title:'Commande enregistrée !', confirmButtonColor:'#10b981' })
                .then(() => { bootstrap.Modal.getInstance(document.getElementById('orderModal'))?.hide(); location.reload(); });
            },
            error: function(xhr) {
                Swal.fire({ icon:'error', title:'Erreur', text: xhr.responseJSON?.message || 'Une erreur est survenue.' });
                btn.prop('disabled', false).html('<i class="fas fa-check me-1"></i> Enregistrer la commande');
            }
        });
    });

    /* ── Reset ── */
    function resetModal() {
        step = 1; mode = 'existing';
        $('#orderForm')[0].reset();
        goStep(1);
        $('#sel-client').val(''); $('#ex-info').hide();
        $('#blk-ex').show(); $('#blk-nw').hide();
        $('#tog-ex').addClass('active'); $('#tog-nw').removeClass('active');
        $('.nom-filter').removeClass('active').first().addClass('active');
        $('.nom-dish').show();
        $('#basket').hide();
    }
    document.getElementById('orderModal').addEventListener('hidden.bs.modal', function(){
        orderItems = {}; resetModal(); refreshDishCards();
    });
});
</script>
@endpush