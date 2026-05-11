<!-- ════════════════════════════════════════════════
     MODAL NOUVELLE COMMANDE — ADMIN 5 ÉTOILES
════════════════════════════════════════════════ -->
<div class="modal fade" id="newOrderModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content nom-card">

            {{-- En-tête --}}
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

            {{-- Barre de progression --}}
            <div class="nom-steps">
                <div class="nom-step active" data-step="1"><div class="nom-dot">1</div><span>Plats</span></div>
                <div class="nom-step-line"></div>
                <div class="nom-step" data-step="2"><div class="nom-dot">2</div><span>Client</span></div>
                <div class="nom-step-line"></div>
                <div class="nom-step" data-step="3"><div class="nom-dot">3</div><span>Confirmation</span></div>
            </div>

            <form action="{{ route('restaurant.orders.store') }}" method="POST" id="newOrderForm">
            @csrf
            {{-- Champs cachés --}}
            <input type="hidden" name="customer_id"     id="h-customer-id">
            <input type="hidden" name="customer_name"   id="h-customer-name">
            <input type="hidden" name="phone"           id="h-phone">
            <input type="hidden" name="email"           id="h-email">
            <input type="hidden" name="room_number"     id="h-room">
            <input type="hidden" name="items"           id="h-items">
            <input type="hidden" name="total"           id="h-total">
            <input type="hidden" name="notes"           id="h-total-notes">
            <input type="hidden" name="order_location"  id="h-location" value="room">
            <input type="hidden" name="table_number"    id="h-table">
            <input type="hidden" name="payment_method"  id="h-payment" value="cash">

            <div class="nom-body">

                {{-- ── ÉTAPE 1 : Récapitulatif du panier ── --}}
                <div class="nom-panel active" id="nom-panel-1">
                    <div class="nom-panel-title"><i class="fas fa-shopping-cart me-2"></i>Détail de la commande</div>
                    <p class="nom-desc">Voici les plats sélectionnés. Ajustez les quantités ou supprimez un article avant de continuer.</p>

                    {{-- Tableau récapitulatif du panier --}}
                    <div id="cart-review-empty" class="nom-cart-empty d-flex flex-column align-items-center">
                        <i class="fas fa-shopping-cart fa-2x mb-2 text-muted"></i>
                        <p class="text-muted mb-3">Votre panier est vide.</p>
                        <small class="text-muted">Ajoutez des plats depuis la page restaurant puis revenez ici.</small>
                    </div>

                    <div id="cart-review-list">
                        {{-- Rempli dynamiquement par JS --}}
                    </div>

                    <div id="cart-review-footer" class="nom-cart-footer d-none justify-content-between align-items-center mt-3 pt-3 border-top">
                        <button type="button" class="btn btn-sm btn-outline-danger" id="nom-clear-cart">
                            <i class="fas fa-trash-alt me-1"></i> Vider le panier
                        </button>
                        <div class="nom-cart-total-line m-0">
                            <span class="text-muted">Total :</span>
                            <strong id="cart-review-total" class="ms-2 fs-5" style="color: var(--g800);">0 CFA</strong>
                        </div>
                    </div>

                    <div class="nom-err mt-2" id="n-err-items"></div>
                </div>

                {{-- Grille menus cachée — nécessaire pour les boutons #naddbtn-* utilisés par la page index --}}
                <div style="display:none" aria-hidden="true">
                    @php $modalMenus = $allMenus ?? $menus ?? []; @endphp
                    @foreach($modalMenus as $menu)
                    @if(is_object($menu))
                    <div class="nom-dish" data-cat="{{ $menu->category ?? '' }}"
                         data-id="{{ $menu->id ?? 0 }}" data-name="{{ $menu->name ?? '' }}" data-price="{{ $menu->price ?? 0 }}"
                         data-image="{{ $menu->image_url ?? '' }}">
                        <div class="nom-qty" id="nqty-{{ $menu->id ?? 0 }}">
                            <button type="button" class="nom-qty-btn nom-qminus" data-id="{{ $menu->id ?? 0 }}">>−</button>
                            <span class="nom-qval" id="nqval-{{ $menu->id ?? 0 }}">0</span>
                            <button type="button" class="nom-qty-btn nom-qplus" data-id="{{ $menu->id ?? 0 }}">+</button>
                        </div>
                        <button type="button" class="nom-add-btn" id="naddbtn-{{ $menu->id ?? 0 }}" data-id="{{ $menu->id ?? 0 }}">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    @endif
                    @endforeach
                </div>
                {{-- ── ÉTAPE 2 : Identification & Lieu ── --}}
                <div class="nom-panel" id="nom-panel-2">
                    <div class="nom-panel-title"><i class="fas fa-user-tag me-2"></i>Identification & Lieu de service</div>
                    <p class="nom-desc">Précisez où le client sera servi et identifiez-le.</p>

                    {{-- Choix Lieu de Service --}}
                    <div class="nom-section-lbl mb-2">Lieu de service</div>
                    <div class="nom-toggle-row mb-4">
                        <button type="button" class="nom-toggle active" id="loc-room"><i class="fas fa-bed me-1"></i> En Chambre</button>
                        <button type="button" class="nom-toggle" id="loc-table"><i class="fas fa-utensils me-1"></i> Au Restaurant</button>
                    </div>

                    <div id="section-table-only" style="display:none">
                        <div class="nom-field mb-3">
                            <label class="nom-label">N° de Table <span class="nom-req">*</span></label>
                            <input type="text" class="nom-input" id="n-table-number" placeholder="Ex: 5, 12, Terrasse 2">
                            <div class="nom-err" id="n-err-table"></div>
                        </div>
                    </div>

                    {{-- Type de client --}}
                    <div class="nom-section-lbl mb-2">Type de client</div>
                    <div class="nom-toggle-row mb-4">
                        <button type="button" class="nom-toggle active" id="tog-existing">Client Résident</button>
                        <button type="button" class="nom-toggle" id="tog-new">Client Extérieur / Nouveau</button>
                    </div>

                    {{-- Client existant --}}
                    <div id="block-existing">
                        <div class="nom-field">
                            {{-- État : Sélection --}}
                            <div id="customer-selection-ui">
                                <div class="nom-search-wrap">
                                    <input type="text" class="nom-input" id="n-customer-search" placeholder="Nom, téléphone, n° de chambre...">
                                    <i class="fas fa-search nom-search-icon"></i>
                                </div>

                                <div class="nom-customer-list-box mt-3" id="customer-list-box">
                                    @forelse($customers ?? [] as $customer)
                                    @if(is_object($customer))
                                    <div class="nom-customer-item" 
                                         data-id="{{ $customer->id ?? 0 }}"
                                         data-name="{{ $customer->name ?? '' }}"
                                         data-room="{{ $customer->room_number ?? '' }}"
                                         data-phone="{{ $customer->phone ?? '' }}"
                                         data-email="{{ $customer->email ?? '' }}"
                                         data-search="{{ strtolower(($customer->name ?? '') . ' ' . ($customer->phone ?? '') . ' ' . ($customer->room_number ?? '')) }}">
                                        <img src="{{ $customer->avatar_url ?? '' }}" 
                                             onerror="this.src='https://i.pinimg.com/736x/fc/7a/4a/fc7a4ad5e3299c1dac28baa60eef6111.jpg'"
                                             class="nom-c-avatar">
                                        <div class="nom-c-info">
                                            <div class="nom-c-name">{{ $customer->name ?? '' }}</div>
                                            <div class="nom-c-sub">
                                                @if($customer->room_number ?? null)<span class="badge bg-info p-1" style="font-size:0.6rem">🔑 {{ $customer->room_number }}</span>@endif
                                                @if($customer->phone ?? null)<span><i class="fas fa-phone fa-xs"></i> {{ $customer->phone }}</span>@endif
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @empty
                                    <div class="p-5 text-center">
                                        <div class="mb-3"><i class="fas fa-user-slash fa-3x text-muted opacity-25"></i></div>
                                        <div class="fw-bold text-muted">Aucun client résident actif</div>
                                        <p class="small text-muted px-4">Tous les clients enregistrés sont actuellement libérés ou n'ont pas de séjour actif.</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- État : Sélectionné --}}
                            <div id="selected-customer-card" class="nom-selected-card mt-2" style="display:none">
                                <div class="nom-selected-body">
                                    <img src="" id="sel-c-avatar" class="nom-c-avatar">
                                    <div class="nom-c-info">
                                        <div class="nom-c-name" id="sel-c-name"></div>
                                        <div class="nom-c-sub" id="sel-c-sub"></div>
                                    </div>
                                    <button type="button" class="nom-btn-change" id="change-customer-btn" title="Modifier le choix">
                                        <i class="fas fa-sync-alt"></i> Modifier
                                    </button>
                                </div>
                            </div>
                            
                            {{-- Select caché pour garder la compatibilité JS --}}
                            <select id="n-customer-select" style="display:none">
                                <option value="">— Sélectionner —</option>
                                @foreach($customers ?? [] as $customer)
                                @if(is_object($customer))
                                <option value="{{ $customer->id ?? 0 }}"
                                        data-name="{{ $customer->name ?? '' }}"
                                        data-room="{{ $customer->room_number ?? '' }}"
                                        data-phone="{{ $customer->phone ?? '' }}"
                                        data-email="{{ $customer->email ?? '' }}">
                                    {{ $customer->name ?? '' }}
                                </option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="nom-grid-2 mt-3" id="existing-info" style="display:none">
                            <div class="nom-info-card"><span class="nom-ic-label">Chambre</span><span class="nom-ic-val" id="disp-room">—</span></div>
                            <div class="nom-info-card"><span class="nom-ic-label">Statut</span><span class="nom-ic-val text-muted">Client enregistré</span></div>
                        </div>
                    </div>

                    <div id="block-new" style="display:none">
                        <div class="nom-field">
                            <label class="nom-label">Nom ou Référence (Optionnel)</label>
                            <input type="text" class="nom-input" id="n-fullname" placeholder="Ex: Client terrasse, Mr. X, etc.">
                            <div class="nom-err" id="n-err-fullname"></div>
                        </div>
                    </div>
                </div>

                {{-- ── ÉTAPE 3 : Notes, Facturation & Confirmation ── --}}
                <div class="nom-panel" id="nom-panel-3">
                    <div class="nom-panel-title"><i class="fas fa-check-circle me-2 text-success"></i>Confirmation & Facturation</div>
                    <p class="nom-desc">Vérifiez la commande, ajoutez des notes et choisissez le mode de facturation.</p>

                    <div class="row gx-4">
                        <div class="col-md-6 mb-4 mb-md-0">
                            {{-- Info Facturation Simple --}}
                            <div class="nom-field mb-3" id="room-payment-notice" style="display:none">
                                <div class="d-flex align-items-center p-3 rounded" style="background:#f0f9ff; border:1px solid #bae6fd;">
                                    <i class="fas fa-hotel text-primary me-3" style="font-size:1.2rem"></i>
                                    <div>
                                        <div class="fw-bold" style="font-size:0.85rem; color:#0369a1;">Facturation chambre active</div>
                                        <div class="small" id="room-payment-text" style="color:#0ea5e9;"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Bloc paiement direct --}}
                            <div id="block-direct-billing" style="display:none">
                                <div class="nom-section-lbl mb-2">Choisir le règlement</div>
                                <div class="nom-pay-grid">
                                    <label class="nom-pay"><input type="radio" name="n_payment" value="cash" checked>
                                        <div class="nom-pay-body"><i class="fas fa-money-bill-wave fa-lg mb-1 text-success"></i><span>Espèces</span></div></label>
                                    <label class="nom-pay"><input type="radio" name="n_payment" value="card">
                                        <div class="nom-pay-body"><i class="fas fa-credit-card fa-lg mb-1 text-primary"></i><span>Carte</span></div></label>
                                    <label class="nom-pay"><input type="radio" name="n_payment" value="mobile_money">
                                        <div class="nom-pay-body"><i class="fas fa-mobile-alt fa-lg mb-1" style="color: #f59e0b;"></i><span>Mobile M.</span></div></label>
                                    <label class="nom-pay"><input type="radio" name="n_payment" value="transfer">
                                        <div class="nom-pay-body"><i class="fas fa-university fa-lg mb-1 text-info"></i><span>Virement</span></div></label>
                                    <label class="nom-pay"><input type="radio" name="n_payment" value="fedapay">
                                        <div class="nom-pay-body"><i class="fas fa-wallet fa-lg mb-1 text-indigo"></i><span>Fedapay</span></div></label>
                                    <label class="nom-pay"><input type="radio" name="n_payment" value="check">
                                        <div class="nom-pay-body"><i class="fas fa-file-invoice-dollar fa-lg mb-1 text-secondary"></i><span>Chèque</span></div></label>
                                </div>
                            </div>

                            <div class="nom-field mt-3">
                                <label class="nom-label">Notes pour le chef</label>
                                <textarea class="nom-input nom-textarea" id="n-notes" rows="2" placeholder="Ex: sans oignons, cuisson à point..."></textarea>
                            </div>
                        </div>

                        <div class="col-md-6 ps-md-4">
                            <div class="nom-section-lbl">Aperçu & Détails</div>
                            
                            <div class="nom-recap-block mb-3">
                                <div class="nom-recap-title"><i class="fas fa-info-circle me-1"></i> Infos Client & Lieu</div>
                                <div id="nrecap-client" class="nom-recap-content"></div>
                            </div>
                            
                            <div class="nom-recap-block mb-3 d-none" id="nrecap-notes-block">
                                <div class="nom-recap-title"><i class="fas fa-sticky-note me-1"></i> Préférences Chef</div>
                                <div id="nrecap-notes-content" class="nom-recap-content fst-italic"></div>
                            </div>

                            <div class="nom-recap-block">
                                <div class="nom-recap-title"><i class="fas fa-shopping-cart me-1"></i> Plats sélectionnés (<span id="nrecap-total"></span>)</div>
                                <div id="nrecap-items" style="max-height: 200px; overflow-y: auto;"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- /nom-body --}}

            {{-- Pied --}}
            <div class="nom-footer">
                <button type="button" class="nom-btn nom-btn-ghost" id="nom-prev" style="display:none">
                    <i class="fas fa-arrow-left me-1"></i> Précédent
                </button>
                <div class="ms-auto d-flex gap-2">
                    <button type="button" class="nom-btn nom-btn-outline" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="nom-btn nom-btn-primary" id="nom-next">
                        Suivant <i class="fas fa-arrow-right ms-1"></i>
                    </button>
                    <button type="submit" class="nom-btn nom-btn-success" id="nom-submit" style="display:none">
                        <i class="fas fa-check me-1"></i> Enregistrer la commande
                    </button>
                </div>
            </div>

            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
/* ══════════════════════════════════════
   MODAL COMMANDE ADMIN
══════════════════════════════════════ */
.nom-card { border:none; border-radius:16px; overflow:hidden; background:#fff; box-shadow:0 30px 70px rgba(0,0,0,.18); display:flex; flex-direction:column; max-height: 90vh; }
#newOrderForm { display:flex; flex-direction:column; flex:1; overflow:hidden; }

.nom-header {
    display:flex; align-items:center; justify-content:space-between;
    padding:18px 24px; flex-shrink:0;
    background:linear-gradient(135deg, var(--g700), var(--g900));
    border-bottom:2px solid var(--g600);
}

.nom-header-left { display:flex; align-items:center; gap:12px; }
.nom-icon-wrap {
    width:38px; height:38px; background:var(--g600); border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    color:#fff; font-size:1rem;
}
.nom-title { font-size:1rem; font-weight:700; color:#fff; }
.nom-subtitle { font-size:.7rem; color:var(--s300); margin-top:1px; }
.nom-close {
    background:transparent; border:1px solid rgba(255,255,255,.15); color:#94a3b8;
    width:30px; height:30px; border-radius:50%; cursor:pointer;
    display:flex; align-items:center; justify-content:center; font-size:.8rem;
    transition:all .2s;
}
.nom-close:hover { background:rgba(255,255,255,.1); color:#fff; }

/* Étapes */
.nom-steps {
    display:flex; align-items:center; padding:14px 24px;
    background:#f8fafc; border-bottom:1px solid #e2e8f0;
    flex-shrink:0;
}
.nom-step { display:flex; align-items:center; gap:6px; flex:1; }
.nom-dot {
    width:28px; height:28px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:.75rem; font-weight:700; flex-shrink:0;
    border:2px solid #e2e8f0; color:#94a3b8; background:#fff;
    transition:all .3s;
}
.nom-step span { font-size:.72rem; color:#94a3b8; white-space:nowrap; }
.nom-step.active .nom-dot { background:var(--g600); border-color:var(--g600); color:#fff; }
.nom-step.active span { color:var(--g700); font-weight:600; }
.nom-step.done .nom-dot { background:#10b981; border-color:#10b981; color:#fff; }
.nom-step.done span { color:#10b981; }
.nom-step-line { flex:1; height:1px; background:#e2e8f0; margin:0 6px; }

/* Corps */
.nom-body { padding:24px; background:#fff; flex:1; overflow-y:auto; min-height:320px; }
.nom-panel { display:none; animation:nomIn .28s ease; }
.nom-panel.active { display:block; }
@keyframes nomIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:none} }
.nom-panel-title { font-size:.95rem; font-weight:700; color:#0f172a; margin-bottom:4px; }
.nom-desc { font-size:.78rem; color:#94a3b8; margin-bottom:18px; }

/* Toggle client existant / nouveau */
.nom-toggle-row { display:flex; gap:0; border:1px solid #e2e8f0; border-radius:8px; overflow:hidden; width:fit-content; }
.nom-toggle {
    padding:7px 18px; font-size:.78rem; font-weight:600; cursor:pointer;
    border:none; background:#f8fafc; color:#64748b; transition:all .18s;
}
.nom-toggle.active { background:var(--g800); color:#fff; }

/* Grilles */
.nom-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.nom-grid-3 { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
@media(max-width:600px){ .nom-grid-2,.nom-grid-3{grid-template-columns:1fr;} }

/* Champs */
.nom-field { display:flex; flex-direction:column; gap:4px; }
.nom-label { font-size:.7rem; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:.05em; }
.nom-req { color:#d4af37; }
.nom-input {
    padding:10px 13px; border:1px solid #e2e8f0; border-radius:8px;
    font-size:.84rem; color:#1e293b; background:#f8fafc; width:100%;
    transition:border-color .18s, box-shadow .18s; outline:none;
}
.nom-input:focus { border-color:var(--g600); box-shadow:0 0 0 3px rgba(32,178,170,.12); background:#fff; }
.nom-select { appearance:none; cursor:pointer;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%2394a3b8'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 13px center; padding-right:34px; }
.nom-select option { background:#fff; }
.nom-textarea { resize:vertical; min-height:80px; }
.nom-err { font-size:.72rem; color:#e11d48; min-height:14px; }

/* Info cards client */
.nom-info-card {
    background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px;
    padding:10px 14px; display:flex; flex-direction:column; gap:3px;
}
.nom-ic-label { font-size:.64rem; text-transform:uppercase; color:#94a3b8; font-weight:600; }
.nom-ic-val { font-size:.84rem; color:#1e293b; font-weight:600; }

/* Filtres */
.nom-filters { display:flex; flex-wrap:wrap; gap:6px; margin-bottom:14px; }
.nom-filter {
    padding:5px 14px; border-radius:20px; font-size:.74rem; font-weight:600;
    cursor:pointer; border:1px solid #e2e8f0; background:#f8fafc; color:#64748b; transition:all .16s;
}
.nom-filter.active { background:var(--g800); border-color:var(--g800); color:#fff; }
.nom-filter:hover:not(.active) { border-color:var(--g800); color:var(--g800); }

/* Grille plats */
.nom-menu-grid {
    display:grid; grid-template-columns:repeat(auto-fill,minmax(190px,1fr));
    gap:12px; max-height:320px; overflow-y:auto;
    scrollbar-width:thin; scrollbar-color:#e2e8f0 transparent;
    padding-right:4px; margin-bottom:14px;
}
.nom-dish {
    border:1px solid #e2e8f0; border-radius:10px; overflow:hidden;
    background:#fff; cursor:pointer; transition:border-color .18s, transform .18s;
}
.nom-dish:hover { border-color:var(--g600); transform:translateY(-2px); }
.nom-dish.selected { border-color:var(--g600); background:var(--g50); }
.nom-dish-img { height:90px; overflow:hidden; background:#f1f5f9; display:flex; align-items:center; justify-content:center; }
.nom-dish-img img { width:100%; height:100%; object-fit:cover; }
.nom-dish-noimg { font-size:1.8rem; color:#cbd5e1; }
.nom-dish-body { padding:9px 11px; }
.nom-dish-name { font-size:.8rem; font-weight:700; color:#0f172a; margin-bottom:2px; }
.nom-dish-desc { font-size:.68rem; color:#94a3b8; line-height:1.4; margin-bottom:7px; }
.nom-dish-footer { display:flex; align-items:center; justify-content:space-between; gap:5px; }
.nom-dish-price { font-size:.78rem; color:var(--g600); font-weight:700; white-space:nowrap; }
.nom-add-btn {
    background:var(--g800); color:#fff; border:none; border-radius:6px;
    font-size:.68rem; font-weight:600; padding:4px 9px; cursor:pointer;
    transition:background .16s; white-space:nowrap;
}
.nom-add-btn:hover { background:var(--g900); }
.nom-qty { display:flex; align-items:center; gap:5px; }
.nom-qty-btn {
    width:22px; height:22px; border-radius:50%; border:1px solid var(--g600);
    background:transparent; color:var(--g600); font-size:.9rem; font-weight:700;
    cursor:pointer; display:flex; align-items:center; justify-content:center;
    transition:all .14s;
}
.nom-qty-btn:hover { background:var(--g600); color:#fff; }
.nom-qval { font-size:.8rem; font-weight:700; color:var(--g900); min-width:16px; text-align:center; }

/* Panier */
.nom-basket {
    background:var(--g50); border:1px solid var(--g200); border-radius:10px; padding:12px 14px;
}
.nom-basket-title { font-size:.76rem; font-weight:700; color:var(--g700); margin-bottom:8px; }
.nom-basket-item {
    display:flex; justify-content:space-between;
    font-size:.76rem; color:#64748b; padding:4px 0;
    border-bottom:1px solid var(--g100);
}
.nom-basket-item:last-child { border-bottom:none; }
.nom-basket-total { font-size:.8rem; color:var(--g700); font-weight:700; text-align:right; margin-top:8px; }

/* Allergènes */
.nom-section-lbl { font-size:.68rem; text-transform:uppercase; letter-spacing:.07em; color:#94a3b8; font-weight:700; margin-bottom:8px; }
.nom-allergen-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:8px; }
@media(max-width:600px){ .nom-allergen-grid{grid-template-columns:repeat(2,1fr);} }
.nom-allergen {
    display:flex; align-items:center; gap:7px;
    padding:8px 11px; border:1px solid #e2e8f0; border-radius:8px;
    cursor:pointer; background:#f8fafc; font-size:.76rem; color:#64748b;
    transition:all .16s;
}
.nom-allergen:has(input:checked) { border-color:#f87171; background:#fff1f2; color:#e11d48; }
.nom-allergen input { display:none; }

/* Radios */
.nom-radio-row { display:flex; flex-wrap:wrap; gap:8px; }
.nom-radio {
    padding:7px 13px; border:1px solid #e2e8f0; border-radius:8px;
    cursor:pointer; background:#f8fafc; font-size:.76rem; color:#64748b; transition:all .16s;
}
.nom-radio:has(input:checked) { border-color:var(--g600); background:var(--g50); color:var(--g700); font-weight:600; }
.nom-radio input { display:none; }

/* Paiement */
.nom-pay-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(100px, 1fr)); gap:8px; }
@media(max-width:600px){ .nom-pay-grid{grid-template-columns:repeat(2,1fr);} }
.nom-pay {
    cursor:pointer; border:1px solid #e2e8f0; border-radius:9px;
    background:#f8fafc; transition:all .16s;
}
.nom-pay:has(input:checked) { border-color:var(--g600); background:var(--g50); }
.nom-pay input { display:none; }
.nom-pay-body {
    display:flex; flex-direction:column; align-items:center;
    padding:14px 8px; gap:8px; font-size:.72rem; color:#64748b; text-align:center;
}
.nom-pay:has(input:checked) .nom-pay-body { color:var(--g700); font-weight:600; }


/* Récap */
.nom-recap-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
@media(max-width:600px){ .nom-recap-grid{grid-template-columns:1fr;} }
.nom-recap-block { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:12px 14px; }
.nom-recap-title { font-size:.68rem; text-transform:uppercase; letter-spacing:.05em; color:#64748b; font-weight:700; margin-bottom:8px; display:flex; align-items:center; }
.nom-recap-content { font-size:.8rem; color:#1e293b; }
.nom-recap-line { display:flex; justify-content:space-between; font-size:.78rem; color:#475569; padding:2px 0; }
.nom-recap-line span { color:#94a3b8; margin-right:8px; }
.nom-recap-item { display:flex; justify-content:space-between; font-size:.8rem; color:#475569; padding:5px 0; border-bottom:1px solid #f1f5f9; }
.nom-recap-item:last-child { border-bottom:none; }
.nom-recap-total { text-align:right; margin-top:10px; font-size:.88rem; color:var(--g700); font-weight:800; }


/* Pied */
.nom-footer {
    display:flex; align-items:center; padding:14px 24px;
    background:#f8fafc; border-top:1px solid #e2e8f0;
    flex-shrink:0;
}
.nom-btn {
    padding:9px 20px; border-radius:8px; font-size:.8rem; font-weight:600;
    cursor:pointer; border:none; transition:all .16s;
    display:inline-flex; align-items:center; gap:5px;
}
.nom-btn-ghost { background:transparent; color:#94a3b8; border:1px solid #e2e8f0; }
.nom-btn-ghost:hover { color:#475569; }
.nom-btn-outline { background:#fff; color:#64748b; border:1px solid #e2e8f0; }
.nom-btn-outline:hover { border-color:#94a3b8; color:#1e293b; }
.nom-btn-primary { background:var(--g800); color:#fff; }
.nom-btn-primary:hover { background:var(--g900); }
.nom-btn-success { background:#10b981; color:#fff; box-shadow:0 3px 10px rgba(16,185,129,.3); }
.nom-btn-success:hover { background:#059669; }
.nom-btn-success:disabled { opacity:.5; cursor:not-allowed; }



/* Table */
.card { transition:transform .3s ease; }
.card:hover { transform:translateY(-2px); }
.table tbody tr:hover { background-color:rgba(0,123,255,.04); }
.badge { font-size:.75em; padding:.35em .65em; }

/* ── Cart Review (étape 1 du modal) ── */
.nom-cart-empty {
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    padding:40px 20px; text-align:center;
    background:#f8fafc; border:2px dashed #e2e8f0; border-radius:12px;
}
.nom-cart-row {
    display:flex; align-items:center; gap:12px;
    padding:12px 14px; border:1px solid #e2e8f0; border-radius:10px;
    background:#fff; margin-bottom:8px;
    transition:border-color .16s;
}
.nom-cart-row:hover { border-color:var(--g600); }
.nom-cart-row-info { flex:1; min-width:0; }
.nom-cart-row-img { width: 45px; height: 45px; border-radius: 6px; object-fit: cover; border: 1px solid #e2e8f0; flex-shrink: 0; }
.nom-cart-row-img-none { width: 45px; height: 45px; border-radius: 6px; background: #f1f5f9; color: #cbd5e1; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
.nom-cart-row-name { font-size:.85rem; font-weight:700; color:var(--g900); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.nom-cart-row-price { font-size:.7rem; color:#94a3b8; margin-top:2px; }
.nom-cart-row-controls {
    display:flex; align-items:center; gap:8px;
    background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:4px 8px;
}
.nom-cr-btn {
    width:24px; height:24px; border-radius:50%; border:1px solid var(--g600);
    background:transparent; color:var(--g600); font-size:1rem; font-weight:700;
    cursor:pointer; display:flex; align-items:center; justify-content:center;
    line-height:1; transition:all .14s;
}
.nom-cr-btn:hover { background:var(--g600); color:#fff; }
.nom-cr-qty { font-size:.85rem; font-weight:700; color:var(--g900); min-width:20px; text-align:center; }
.nom-cart-row-sub { font-size:.82rem; font-weight:700; color:var(--g600); white-space:nowrap; min-width:90px; text-align:right; }
.nom-cr-remove {
    background:transparent; border:none; color:#cbd5e1; cursor:pointer;
    font-size:.8rem; padding:4px; border-radius:50%; transition:all .14s;
    display:flex; align-items:center; justify-content:center;
}
.nom-cr-remove:hover { background:#fee2e2; color:#e11d48; }
.nom-cart-footer {
    margin-top:12px; padding:12px 16px;
    background:linear-gradient(135deg,var(--g50),#fff);
    border:1px solid var(--g200); border-radius:10px;
}
.nom-cart-total-line {
    display:flex; justify-content:space-between; align-items:center;
    font-size:.9rem; color:var(--g700);
}
.nom-cart-total-line strong { font-size:1.05rem; color:var(--g800); }

/* ── Recherche de client (étape 2) ── */
.nom-search-wrap { position: relative; width: 100%; }
.nom-search-icon { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
.nom-customer-list-box {
    max-height: 280px; overflow-y: auto;
    border: 1px solid #e2e8f0; border-radius: 12px;
    background: #fff; padding: 6px;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.03);
}
.nom-customer-item {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 14px; border-radius: 9px;
    cursor: pointer; transition: all .16s;
    border: 1px solid transparent; margin-bottom: 4px;
}
.nom-customer-item:hover { background: #f8fafc; border-color: #e2e8f0; }
.nom-customer-item.selected { 
    background: #f0faf0; border-color: #10b981; 
    box-shadow: 0 4px 6px -1px rgba(16,185,129,0.1); 
}
.nom-c-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0; flex-shrink: 0; }
.nom-customer-item.selected .nom-c-avatar { border-color: #10b981; }
.nom-c-info { flex: 1; min-width: 0; }
.nom-c-name { font-size: .88rem; font-weight: 700; color: #1e293b; }
.nom-c-sub { font-size: .72rem; color: #94a3b8; margin-top: 1px; display: flex; align-items: center; gap: 10px; }
.nom-c-sub i { color: #cbd5e1; }

/* Carte client sélectionné */
.nom-selected-card {
    background: #f8fafc; border: 1.5px solid #d4af37; border-radius: 12px;
    padding: 12px 16px; animation: nomIn .25s ease;
}
.nom-selected-body { display: flex; align-items: center; gap: 14px; }
.nom-btn-change {
    background: #fff; border: 1px solid #e2e8f0; color: #64748b;
    padding: 6px 12px; border-radius: 8px; font-size: .7rem; font-weight: 600;
    cursor: pointer; transition: all .2s; display: flex; align-items: center; gap: 5px;
}
.nom-btn-change:hover { background: #f1f5f9; color: #1e293b; border-color: #cbd5e1; }

@media (max-width: 768px) {
    .nom-header { padding: 12px 16px; }
    .nom-icon-wrap { width: 32px; height: 32px; font-size: 0.8rem; }
    .nom-title { font-size: 0.85rem; }
    .nom-subtitle { display: none; }
    .nom-steps { padding: 8px 16px; }
    .nom-dot { width: 22px; height: 22px; font-size: 0.65rem; }
    .nom-step span { font-size: 0.6rem; }
    .nom-body { padding: 15px; }
    .nom-panel-title { font-size: 0.85rem; }
    .nom-footer { padding: 10px 16px; }
    .nom-btn { padding: 7px 14px; font-size: 0.75rem; }

    /* Fix horiz overflow on cart rows & grids */
    .nom-menu-grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 8px;
    }
    .nom-dish-img { height: 75px; }
    .nom-dish-body { padding: 7px 9px; }
    .nom-dish-footer { gap: 2px; }
    .nom-qty-btn { width: 20px; height: 20px; font-size: 0.75rem; }

    .nom-cart-row {
        flex-wrap: wrap;
        padding: 8px;
        gap: 8px;
        position: relative;
    }
    .nom-cart-row-img { width: 35px; height: 35px; }
    .nom-cart-row-info {
        flex: 1 1 150px;
        order: 1;
    }
    .nom-cart-row-controls {
        order: 2;
        padding: 2px 6px;
        gap: 4px;
    }
    .nom-cart-row-sub {
        order: 3;
        min-width: unset;
        flex: 1;
        text-align: right;
        font-size: 0.75rem;
    }
    .nom-cr-remove {
        position: absolute;
        top: 4px;
        right: 4px;
        padding: 2px;
    }
    .nom-recap-item {
        flex-wrap: wrap;
    }
    .nom-recap-item img {
        margin-bottom: 8px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Attente asynchrone de jQuery (app.js charge jQuery via Vite)
function initNomModal() {
    if (!window.$) { setTimeout(initNomModal, 50); return; }
    $(document).ready(function() {

    /* ══════════════════════════════
       FILTRES TABLE
    ══════════════════════════════ */
    $('#statusFilter').change(function() {
        const s = $(this).val();
        if (s) { $('tbody tr').hide(); $(`tbody tr[data-status="${s}"]`).show(); }
        else { $('tbody tr').show(); }
    });

    /* ══════════════════════════════
       DÉTAILS COMMANDE
    ══════════════════════════════ */
    $(document).on('click', '.view-items, button[data-bs-target="#orderDetailsModal"]', function() {
        const orderId = $(this).data('order-id');
        if (!orderId) return;
        $('#orderId').text(orderId);
        $.ajax({
            url: `{{ url('restaurant/orders') }}/${orderId}`,
            success: r => $('#orderDetailsContent').html(r.html),
            error: () => $('#orderDetailsContent').html('<div class="alert alert-danger">Erreur de chargement.</div>')
        });
    });

    /* ══════════════════════════════
       CHANGEMENT DE STATUT (direct, sans confirmation)
    ══════════════════════════════ */
    $(document).on('click', '.change-status', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        const btn = $(this);
        const orderId = btn.data('order-id');
        const newStatus = btn.data('status');

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: `{{ url('restaurant/orders') }}/${orderId}`,
            type: 'PUT',
            data: { _token: '{{ csrf_token() }}', status: newStatus },
            success: function() {
                location.reload();
            },
            error: function(xhr) {
                btn.prop('disabled', false);
                const icons = { preparing: 'fa-play', delivered: 'fa-check', paid: 'fa-money-bill-wave' };
                btn.html(`<i class="fas ${icons[newStatus] || 'fa-sync'}"></i>`);
                Swal.fire('Erreur', xhr.responseJSON?.message || 'Action impossible.', 'error');
            }
        });
    });

    /* ══════════════════════════════
       ANNULATION COMMANDE
    ══════════════════════════════ */
    $(document).on('click', '.cancel-order', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        const orderId = $(this).data('order-id');
        Swal.fire({ title:'Annuler la commande ?', icon:'warning', showCancelButton:true, confirmButtonText:'Oui, annuler', cancelButtonText:'Non', reverseButtons:true })
        .then(r => {
            if (!r.isConfirmed) return;
            $.ajax({ url:`{{ url('restaurant/orders') }}/${orderId}/cancel`, type:'PUT',
                data:{ _token:'{{ csrf_token() }}' },
                success:()=>Swal.fire('Annulé !','','success').then(()=>location.reload()),
                error:()=>Swal.fire('Erreur !','','error')
            });
        });
    });

    // L'impression est gérée dans orders.blade.php (ouvre /restaurant/orders/{id}/invoice dans un nouvel onglet)

    /* ══════════════════════════════════════════
       NOUVELLE COMMANDE — MULTI-ÉTAPES
    ══════════════════════════════════════════ */
    let nomStep = 1;
    let nomItems = {};
    try {
        nomItems = JSON.parse(localStorage.getItem('restaurant_cart') || '{}');
        // Nettoyage de sécurité
        Object.keys(nomItems).forEach(id => {
            if (!nomItems[id].quantity || isNaN(nomItems[id].quantity)) nomItems[id].quantity = 1;
            if (!nomItems[id].price || isNaN(nomItems[id].price)) nomItems[id].price = 0;
        });
    } catch(e) { nomItems = {}; }
    let nomMode  = 'existing'; // 'existing' | 'new'
    let nomLocation = 'room'; // 'room' | 'table'
    let selectedCustomerRoom = ''; // Numéro de chambre du client sélectionné

    /* Toggle Lieu */
    $('#loc-room').click(function(){
        nomLocation = 'room';
        $(this).addClass('active'); $('#loc-table').removeClass('active');
        $('#section-table-only').hide();
        $('#h-location').val('room');
        
        // Cacher le toggle "Client Extérieur" car en chambre c'est forcément un résident
        $('#tog-new').hide();
        $('#tog-existing').click(); // Revenir sur résident par défaut
    });
    $('#loc-table').click(function(){
        nomLocation = 'table';
        $(this).addClass('active'); $('#loc-room').removeClass('active');
        $('#section-table-only').show();
        $('#h-location').val('table');
        
        // Réafficher le choix pour restaurant
        $('#tog-new').show();
        setTimeout(() => $('#n-table-number').focus(), 100);
    });

    /* Toggle client résident / nouveau */
    $('#tog-existing').click(function(){
        nomMode = 'existing';
        $(this).addClass('active'); $('#tog-new').removeClass('active');
        $('#block-existing').show(); $('#block-new').hide();
    });
    $('#tog-new').click(function(){
        nomMode = 'new';
        $(this).addClass('active'); $('#tog-existing').removeClass('active');
        $('#block-new').show(); $('#block-existing').hide();
    });

    /* ── Recherche et Sélection Client ── */
    $(document).on('input', '#n-customer-search', function() {
        const q = $(this).val().toLowerCase();
        $('.nom-customer-item').each(function() {
            const searchStr = $(this).data('search');
            $(this).toggle(searchStr.indexOf(q) > -1);
        });
    });

    $(document).on('click', '.nom-customer-item', function() {
        const $this = $(this);
        $('.nom-customer-item').removeClass('selected');
        $this.addClass('selected');
        
        const id = $this.data('id');
        const name = $this.data('name');
        const room = $this.data('room');
        const avatar = $this.find('.nom-c-avatar').attr('src');
        const sub = $this.find('.nom-c-sub').html();
        
        // Mettre à jour le select caché pour la compatibilité avec le reste du code
        $('#n-customer-select').val(id);
        
        // Remplir la carte résumé
        $('#sel-c-avatar').attr('src', avatar);
        $('#sel-c-name').text(name);
        $('#sel-c-sub').html(sub);
        
        // Basculer l'affichage
        $('#customer-selection-ui').hide();
        $('#selected-customer-card').fadeIn();

        // Afficher les infos sous la liste (toujours utile pour le récap)
        $('#disp-room').text(room || '—');
        $('#existing-info').fadeIn();
        
        // Mettre à jour l'affichage de facturation chambre
        const billingMsg = $('#room-billing-display');
        selectedCustomerRoom = room || ''; // Stocker pour l'envoi
        if (room) {
            billingMsg.text(`Garantie sur Chambre ${room} (${name})`).removeClass('text-warning').addClass('text-success');
        } else {
            billingMsg.text(`Attention : ${name} n'est relié à aucune chambre active.`).removeClass('text-success').addClass('text-warning');
        }
    });

    $(document).on('click', '#change-customer-btn', function() {
        $('#selected-customer-card').hide();
        $('#customer-selection-ui').fadeIn();
        $('#n-customer-search').focus();
    });



    /* ── Navigation ── */
    $('#nom-next').click(function(){ if (validateNomStep(nomStep)) goNomStep(nomStep + 1); });
    $('#nom-prev').click(function(){ goNomStep(nomStep - 1); });

    function goNomStep(n) {
        if (n < 1 || n > 3) return;
        if (n === 3) {
            // Logique d'affichage automatique du paiement
            const isRoom = (nomMode === 'existing' && selectedCustomerRoom !== '');
            $('#room-payment-notice').toggle(isRoom);
            $('#block-direct-billing').toggle(!isRoom);
            if (isRoom) {
                $('#room-payment-text').text(`Le montant sera ajouté à la note de la chambre ${selectedCustomerRoom}.`);
            }
            
            buildNomRecap();
        }
        $('.nom-step').each(function(){
            const s = parseInt($(this).data('step'));
            $(this).toggleClass('active', s === n).toggleClass('done', s < n);
        });
        $('.nom-panel').removeClass('active');
        $(`#nom-panel-${n}`).addClass('active');
        nomStep = n;
        $('#nom-prev').toggle(n > 1);
        $('#nom-next').toggle(n < 3);
        $('#nom-submit').toggle(n === 3);
        if (n === 3) $('#nom-next').hide();
    }



    function validateNomStep(step) {
        if (step === 1) {
            // Étape 1 : au moins un plat
            if (Object.keys(nomItems).length === 0) {
                $('#n-err-items').text('Veuillez ajouter au moins un plat.');
                return false;
            }
            $('#n-err-items').text('');
        }
        if (step === 2) {
            // Étape 2 : identification client & lieu
            $('#n-err-client').text('');
            $('#n-err-table').text('');
            
            if (nomLocation === 'table') {
                if (!$('#n-table-number').val().trim()) {
                    $('#n-err-table').text('Veuillez indiquer le numéro de table.');
                    return false;
                }
            }

            if (nomMode === 'existing') {
                if (!$('#n-customer-select').val()) {
                    $('#n-err-client').text('Veuillez sélectionner un client.');
                    return false;
                }
            } else {
                // Pour nouveau client/extérieur, tout est optionnel (le nom peut être vide)
                return true;
            }
        }
        return true;
    }

    /* ── Filtres plats dans le modal ── */
    $(document).on('click', '.nom-filter', function(){
        $('.nom-filter').removeClass('active'); $(this).addClass('active');
        const cat = $(this).data('cat');
        if (cat === 'all') { $('.nom-dish').show(); }
        else { $('.nom-dish').hide(); $(`.nom-dish[data-cat="${cat}"]`).show(); }
    });

    /* ── Ajout / retrait de plats (boutons cachés, utilisés depuis la page index) ── */
    $(document).on('click', '.nom-add-btn', function(){
        const id = $(this).data('id');
        const d  = $(`.nom-dish[data-id="${id}"]`);
        if (!nomItems[id]) nomItems[id] = { 
            menu_id: id, 
            name: d.data('name'), 
            price: parseFloat(d.data('price')), 
            image: d.data('image'),
            quantity: 1 
        };
        else nomItems[id].quantity++;
        nomUpdateDish(id); nomRenderBasket();
    });
    $(document).on('click', '.nom-qplus', function(){
        const id = $(this).data('id');
        if (nomItems[id]) { nomItems[id].quantity++; nomUpdateDish(id); nomRenderBasket(); }
    });
    $(document).on('click', '.nom-qminus', function(){
        const id = $(this).data('id');
        if (!nomItems[id]) return;
        nomItems[id].quantity--;
        if (nomItems[id].quantity <= 0) delete nomItems[id];
        nomUpdateDish(id); nomRenderBasket();
    });

    /* ── Contrôles du récapitulatif panier (étape 1 du modal) ── */
    $(document).on('click', '.nom-cr-plus', function(){
        const id = $(this).data('id');
        if (nomItems[id]) { nomItems[id].quantity++; nomUpdateDish(id); nomRenderBasket(); }
    });
    $(document).on('click', '.nom-cr-minus', function(){
        const id = $(this).data('id');
        if (!nomItems[id]) return;
        nomItems[id].quantity--;
        if (nomItems[id].quantity <= 0) delete nomItems[id];
        nomUpdateDish(id); nomRenderBasket();
    });
    $(document).on('click', '.nom-cr-remove', function(){
        const id = $(this).data('id');
        delete nomItems[id];
        nomUpdateDish(id); nomRenderBasket();
    });

    $(document).on('click', '#nom-clear-cart', function(){
        Swal.fire({
            title: 'Vider le panier ?',
            text: "Tous les plats sélectionnés seront retirés.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, vider',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                const keys = Object.keys(nomItems);
                keys.forEach(id => {
                    delete nomItems[id];
                    nomUpdateDish(id);
                });
                nomRenderBasket();
            }
        });
    });

    function nomUpdateDish(id) {
        const item = nomItems[id];
        if (item && item.quantity > 0) {
            $(`#naddbtn-${id}`).hide(); $(`#nqty-${id}`).show(); $(`#nqval-${id}`).text(item.quantity);
            $(`.nom-dish[data-id="${id}"]`).addClass('selected');
            
            // Synchronisation avec la page principale
            $(`#main-add-btn-${id}`).addClass('d-none');
            $(`#main-qty-wrapper-${id}`).removeClass('d-none').addClass('d-flex');
            $(`#main-qval-${id}`).text(item.quantity);
        } else {
            $(`#naddbtn-${id}`).show(); $(`#nqty-${id}`).hide(); $(`#nqval-${id}`).text(0);
            $(`.nom-dish[data-id="${id}"]`).removeClass('selected');
            
            // Synchronisation avec la page principale
            $(`#main-add-btn-${id}`).removeClass('d-none');
            $(`#main-qty-wrapper-${id}`).addClass('d-none').removeClass('d-flex');
            $(`#main-qval-${id}`).text(0);
        }
    }

    function nomRenderBasket() {
        localStorage.setItem('restaurant_cart', JSON.stringify(nomItems));
        
        // Synchroniser l'affichage de tous les produits sur la page
        $('.main-qty-controls').each(function() {
            nomUpdateDish($(this).data('id'));
        });

        const items = Object.values(nomItems);
        let totalCount = 0;
        items.forEach(it => { totalCount += it.quantity; });

        // Mise à jour du badge compteur sur la page
        let counterBadge = document.getElementById('cart-counter-pill');
        if (counterBadge) {
            counterBadge.innerText = totalCount;
            counterBadge.style.display = totalCount > 0 ? 'inline-block' : 'none';
        }

        // Mise à jour du récapitulatif étape 1
        nomRenderCartReview();
    }

    function nomRenderCartReview() {
        const items = Object.values(nomItems);
        const listEl   = document.getElementById('cart-review-list');
        const emptyEl  = document.getElementById('cart-review-empty');
        const footerEl = document.getElementById('cart-review-footer');
        const totalEl  = document.getElementById('cart-review-total');

        if (!listEl) return;

        if (!items.length) {
            listEl.innerHTML   = '';
            
            emptyEl.classList.remove('d-none');
            emptyEl.classList.add('d-flex');
            
            footerEl.classList.remove('d-flex');
            footerEl.classList.add('d-none');
            
            $('#cart-review-total').text('0 CFA');
            buildNomRecap();
            return;
        }

        emptyEl.classList.remove('d-flex');
        emptyEl.classList.add('d-none');
        
        footerEl.classList.remove('d-none');
        footerEl.classList.add('d-flex');

        let total = 0;
        let html  = '';
        items.forEach(it => {
            const sub = (it.price || 0) * (it.quantity || 0);
            total += sub;
            
            // Récupérer l'image depuis l'objet ou depuis le DOM en fallback
            let imgUrl = it.image;
            if (!imgUrl) {
                // Fallback si l'item en localStorage n'avait pas l'image (ancien panier)
                imgUrl = document.querySelector(`.nom-dish[data-id="${it.menu_id}"]`)?.dataset.image;
            }
            
            const defaultImg = 'https://i.pinimg.com/736x/fc/7a/4a/fc7a4ad5e3299c1dac28baa60eef6111.jpg';
            let imgHtml = '';
            if (imgUrl) {
                imgHtml = `<img src="${imgUrl}" class="nom-cart-row-img" alt="${it.name}" onerror="this.src='${defaultImg}'">`;
            } else {
                imgHtml = `<img src="${defaultImg}" class="nom-cart-row-img" alt="${it.name}">`;
            }
            
            const qVal = parseInt(it.quantity) || 1;
            const pVal = parseFloat(it.price) || 0;

            html += `
            <div class="nom-cart-row" data-id="${it.menu_id}">
                ${imgHtml}
                <div class="nom-cart-row-info">
                    <div class="nom-cart-row-name">${it.name}</div>
                    <div class="nom-cart-row-price">${pVal.toLocaleString('fr-FR')} CFA / unité</div>
                </div>
                <div class="nom-cart-row-controls">
                    <button type="button" class="nom-cr-btn nom-cr-minus" data-id="${it.menu_id}">−</button>
                    <span class="nom-cr-qty">${qVal}</span>
                    <button type="button" class="nom-cr-btn nom-cr-plus" data-id="${it.menu_id}">+</button>
                </div>
                <div class="nom-cart-row-sub">${(pVal * qVal).toLocaleString('fr-FR')} CFA</div>
                <button type="button" class="nom-cr-remove" data-id="${it.menu_id}" title="Retirer"><i class="fas fa-times"></i></button>
            </div>`;
        });

        listEl.innerHTML = html;
        totalEl.textContent = total.toLocaleString('fr-FR') + ' CFA';
        
        // Mettre à jour l'aperçu latéral en temps réel
        buildNomRecap();
    }

    /* ── Récapitulatif ── */
    function buildNomRecap() {
        let clientHtml = '';
        if (nomMode === 'existing') {
            const sel = $('#n-customer-select').find(':selected');
            const name = sel.data('name') || '<span class="text-muted">Non sélectionné</span>';
            clientHtml = `<div class="nom-recap-line"><span>Client</span>${name}</div>`;
            if (sel.data('room')) clientHtml += `<div class="nom-recap-line"><span>Chambre</span>${sel.data('room')}</div>`;
        } else {
            const name = $('#n-fullname').val().trim() || '<span class="text-muted">Client inconnu</span>';
            clientHtml = `<div class="nom-recap-line"><span>Nom</span>${name}</div>`;
        }
        $('#nrecap-client').html(clientHtml || '—');
        
        // Affichage du lieu
        let locHtml = `<div class="nom-recap-line"><span>Lieu</span>${nomLocation === 'room' ? 'En Chambre' : 'À Table'}</div>`;
        if (nomLocation === 'table' && $('#n-table-number').val()) {
            locHtml += `<div class="nom-recap-line"><span>Table</span>${$('#n-table-number').val()}</div>`;
        }
        $('#nrecap-client').append(locHtml);
        
        const notesVal = $('#n-notes').val().trim();
        if (notesVal) {
            $('#nrecap-notes-block').removeClass('d-none');
            $('#nrecap-notes-content').text(notesVal);
        } else {
            $('#nrecap-notes-block').addClass('d-none');
        }

        // Remplacer n_billing (car on a supprimé les radios)
        const billing = (nomMode === 'existing' && selectedCustomerRoom !== '') ? 'room' : 'direct';
        const payment = billing === 'room' ? 'room_charge' : ($('input[name="n_payment"]:checked').val() || 'cash');
        
        const methodLabels = {
            'room_charge': '<i class="fas fa-hotel text-primary me-2"></i>Facturé sur la chambre',
            'cash': '<i class="fas fa-money-bill-wave text-success me-2"></i>Paiement direct (Espèces)',
            'card': '<i class="fas fa-credit-card text-success me-2"></i>Paiement direct (Carte)',
            'mobile_money': '<i class="fas fa-mobile-alt text-success me-2"></i>Paiement direct (Mobile Money)',
            'transfer': '<i class="fas fa-university text-success me-2"></i>Paiement direct (Virement)',
            'fedapay': '<i class="fas fa-wallet text-success me-2"></i>Paiement direct (Fedapay)',
            'check': '<i class="fas fa-file-invoice-dollar text-success me-2"></i>Paiement direct (Chèque)'
        };
        
        const billingLabel = methodLabels[payment] || ('Paiement direct (' + payment + ')');
        let prefHtml = `<div class="nom-recap-line"><span>Facturation</span>${billingLabel}</div>`;
        const notesFree = $('#n-notes').val().trim();
        if (notesFree) prefHtml += `<div class="nom-recap-line"><span>Notes</span>${notesFree}</div>`;
        $('#nrecap-prefs').html(prefHtml);

        const items = Object.values(nomItems);
        let itemsHtml = '', total = 0;
        items.forEach(it => {
            const sub = it.price * it.quantity; total += sub;
            itemsHtml += `
            <div class="nom-recap-item d-flex align-items-center mb-2 pb-2" style="border-bottom: 1px solid #eaeaea;">
                <img src="${it.image}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #ddd;" onerror="this.onerror=null; this.src='https://i.pinimg.com/736x/fc/7a/4a/fc7a4ad5e3299c1dac28baa60eef6111.jpg';">
                <div class="flex-grow-1">
                    <div style="font-size: 0.9rem; font-weight: 600; color: #333;">${it.name}</div>
                    <div style="font-size: 0.8rem; color: #666;">${it.price.toLocaleString('fr-FR')} CFA / unité</div>
                    <div class="d-flex align-items-center mt-1">
                        <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-center p-0 nom-qminus" data-id="${it.menu_id}" style="width: 24px; height: 24px; border-radius: 50%;">−</button>
                        <span class="mx-2 fw-semibold" style="min-width: 15px; text-align: center;">${it.quantity}</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-center p-0 nom-qplus" data-id="${it.menu_id}" style="width: 24px; height: 24px; border-radius: 50%;">+</button>
                    </div>
                </div>
                <div class="fw-bold" style="color: #d4af37; font-size: 0.95rem;">${Math.round(sub).toLocaleString('fr-FR')} CFA</div>
            </div>`;
        });
        $('#nrecap-items').html(itemsHtml);
        $('#nrecap-total').text(total.toLocaleString('fr-FR') + ' CFA');

        // Remplir les champs hidden
        const loc = $('#h-location').val() || 'room';
        const tNum = $('#n-table-number').val() || '';
        $('#h-table').val(tNum);

        if (nomMode === 'existing') {
            const sel = $('#n-customer-select').find(':selected');
            $('#h-customer-id').val($('#n-customer-select').val());
            $('#h-customer-name').val(sel.data('name'));
            $('#h-phone').val('');
            $('#h-email').val('');
            
            // Si client existant AVEC chambre → facturation chambre
            const isRoom = (selectedCustomerRoom !== '');
            $('#h-room').val(isRoom ? selectedCustomerRoom : '');
        } else {
            $('#h-customer-id').val('');
            $('#h-customer-name').val($('#n-fullname').val().trim() || 'Client Passant');
            $('#h-phone').val('');
            $('#h-email').val('');
            $('#h-room').val(''); 
        }

        $('#h-total-notes').val($('#n-notes').val().trim());
        $('#h-payment').val(payment);
        $('#h-items').val(JSON.stringify(items.map(i => ({ menu_id: i.menu_id, quantity: i.quantity }))));
        $('#h-total').val(total.toFixed(2));
    }

    /* ── Live update note hidden field ── */
    $(document).on('input', '#n-notes', function() {
        $('#h-notes').val($(this).val().trim());
    });

    /* ── Soumission ── */
    $('#newOrderForm').submit(function(e) {
        e.preventDefault();
        if (Object.keys(nomItems).length === 0) {
            Swal.fire({ icon:'warning', title:'Sélection vide', text:'Ajoutez au moins un plat.' });
            return;
        }
        // Forcer la mise à jour de la note juste avant l'envoi
        $('#h-notes').val($('#n-notes').val().trim());
        const btn = $('#nom-submit').prop('disabled', true).text('Enregistrement…');
        const fd = new FormData(this);
        $.ajax({
            url: $(this).attr('action'), type:'POST', data:fd, processData:false, contentType:false,
            success: function() {
                localStorage.removeItem('restaurant_cart');
                Swal.fire({ icon:'success', title:'Commande enregistrée !', confirmButtonColor:'#10b981' })
                .then(() => { bootstrap.Modal.getInstance(document.getElementById('newOrderModal'))?.hide(); location.reload(); });
            },
            error: function(xhr) {
                const msg = xhr.responseJSON?.message || 'Une erreur est survenue.';
                Swal.fire({ icon:'error', title:'Erreur', text:msg });
                btn.prop('disabled', false).html('<i class="fas fa-check me-1"></i> Enregistrer la commande');
            }
        });
    });

    /* ── Reset à la fermeture ── */
    document.getElementById('newOrderModal').addEventListener('hidden.bs.modal', function(){
        nomStep = 1; nomMode = 'existing';
        // Reset selection UI
        $('#selected-customer-card').hide();
        $('#customer-selection-ui').show();
        $('.nom-customer-item').removeClass('selected');
        $('#n-customer-select').val('');
        $('#n-customer-search').val('');
        $('.nom-customer-item').show();
        
        // Reset billing
        $('#billing-room').prop('checked', true);
        $('#block-room-billing').show(); $('#block-direct-billing').hide();
        $('#room-billing-display').text('\u2014 sera déduite du client sélectionné \u2014').removeClass('text-warning').addClass('text-success');
        goNomStep(1);
    });

    document.getElementById('newOrderModal').addEventListener('show.bs.modal', function(){
        // Restaurer l'affichage initial depuis le cache quand on l'ouvre
        Object.keys(nomItems).forEach(id => {
            nomUpdateDish(id);
        });
        nomRenderBasket();
    });

    // ── Initialiser le compteur et l'UI dès le chargement ──
    nomRenderBasket();
    
    // Appliquer les règles de visibilité initiales
    if (nomLocation === 'room') {
        $('#tog-new').hide();
    } else {
        $('#tog-new').show();
    }
    }); // fin $(document).ready
}
initNomModal();
</script>
@endpush