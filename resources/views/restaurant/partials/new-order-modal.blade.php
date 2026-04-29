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
                <div class="nom-step" data-step="3"><div class="nom-dot">3</div><span>Préférences</span></div>
                <div class="nom-step-line"></div>
                <div class="nom-step" data-step="4"><div class="nom-dot">4</div><span>Confirmation</span></div>
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
            <input type="hidden" name="notes"           id="h-notes">
            <input type="hidden" name="payment_method"  id="h-payment" value="cash">

            <div class="nom-body">

                {{-- ── ÉTAPE 1 : Récapitulatif du panier ── --}}
                <div class="nom-panel active" id="nom-panel-1">
                    <div class="nom-panel-title"><i class="fas fa-shopping-cart me-2"></i>Détail de la commande</div>
                    <p class="nom-desc">Voici les plats sélectionnés. Ajustez les quantités ou supprimez un article avant de continuer.</p>

                    {{-- Tableau récapitulatif du panier --}}
                    <div id="cart-review-empty" class="nom-cart-empty" style="display:none">
                        <i class="fas fa-shopping-cart fa-2x mb-2 text-muted"></i>
                        <p class="text-muted mb-3">Votre panier est vide.</p>
                        <small class="text-muted">Ajoutez des plats depuis la page restaurant puis revenez ici.</small>
                    </div>

                    <div id="cart-review-list">
                        {{-- Rempli dynamiquement par JS --}}
                    </div>

                    <div id="cart-review-footer" class="nom-cart-footer" style="display:none">
                        <div class="nom-cart-total-line">
                            <span>Total de la commande</span>
                            <strong id="cart-review-total">0 CFA</strong>
                        </div>
                    </div>

                    <div class="nom-err mt-2" id="n-err-items"></div>
                </div>

                {{-- Grille menus cachée — nécessaire pour les boutons #naddbtn-* utilisés par la page index --}}
                <div style="display:none" aria-hidden="true">
                    @php $modalMenus = $allMenus ?? $menus ?? []; @endphp
                    @foreach($modalMenus as $menu)
                    <div class="nom-dish" data-cat="{{ $menu->category }}"
                         data-id="{{ $menu->id }}" data-name="{{ $menu->name }}" data-price="{{ $menu->price }}"
                         data-image="{{ $menu->image_url }}">
                        <div class="nom-qty" id="nqty-{{ $menu->id }}">
                            <button type="button" class="nom-qty-btn nom-qminus" data-id="{{ $menu->id }}">−</button>
                            <span class="nom-qval" id="nqval-{{ $menu->id }}">0</span>
                            <button type="button" class="nom-qty-btn nom-qplus" data-id="{{ $menu->id }}">+</button>
                        </div>
                        <button type="button" class="nom-add-btn" id="naddbtn-{{ $menu->id }}" data-id="{{ $menu->id }}">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    @endforeach
                </div>

                {{-- ── ÉTAPE 2 : Identification du client ── --}}
                <div class="nom-panel" id="nom-panel-2">
                    <div class="nom-panel-title"><i class="fas fa-user me-2"></i>Identification du client</div>
                    <p class="nom-desc">Sélectionnez un client existant ou saisissez ses informations manuellement.</p>

                    {{-- Toggle client existant / nouveau --}}
                    <div class="nom-toggle-row mb-4">
                        <button type="button" class="nom-toggle active" id="tog-existing">Client existant</button>
                        <button type="button" class="nom-toggle" id="tog-new">Saisie manuelle</button>
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
                                    @foreach($customers ?? [] as $customer)
                                    <div class="nom-customer-item" 
                                         data-id="{{ $customer->id }}"
                                         data-name="{{ $customer->name }}"
                                         data-room="{{ $customer->room_number ?? '' }}"
                                         data-phone="{{ $customer->phone ?? '' }}"
                                         data-email="{{ $customer->email ?? '' }}"
                                         data-search="{{ strtolower($customer->name . ' ' . ($customer->phone ?? '') . ' ' . ($customer->room_number ?? '')) }}">
                                        <img src="{{ $customer->avatar_url }}" 
                                             onerror="this.src='https://i.pinimg.com/736x/fc/7a/4a/fc7a4ad5e3299c1dac28baa60eef6111.jpg'"
                                             class="nom-c-avatar">
                                        <div class="nom-c-info">
                                            <div class="nom-c-name">{{ $customer->name }}</div>
                                            <div class="nom-c-sub">
                                                @if($customer->room_number)<span class="badge bg-info p-1" style="font-size:0.6rem">🔑 {{ $customer->room_number }}</span>@endif
                                                @if($customer->phone)<span><i class="fas fa-phone fa-xs"></i> {{ $customer->phone }}</span>@endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
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
                                <option value="{{ $customer->id }}"
                                        data-name="{{ $customer->name }}"
                                        data-room="{{ $customer->room_number ?? '' }}"
                                        data-phone="{{ $customer->phone ?? '' }}"
                                        data-email="{{ $customer->email ?? '' }}">
                                    {{ $customer->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="nom-grid-3 mt-3" id="existing-info" style="display:none">
                            <div class="nom-info-card"><span class="nom-ic-label">Chambre</span><span class="nom-ic-val" id="disp-room">—</span></div>
                            <div class="nom-info-card"><span class="nom-ic-label">Téléphone</span><span class="nom-ic-val" id="disp-phone">—</span></div>
                            <div class="nom-info-card"><span class="nom-ic-label">Email</span><span class="nom-ic-val" id="disp-email">—</span></div>
                        </div>
                    </div>

                    {{-- Nouveau client --}}
                    <div id="block-new" style="display:none">
                        <div class="nom-grid-2">
                            <div class="nom-field">
                                <label class="nom-label">Prénom <span class="nom-req">*</span></label>
                                <input type="text" class="nom-input" id="n-prenom" placeholder="Prénom">
                                <div class="nom-err" id="n-err-prenom"></div>
                            </div>
                            <div class="nom-field">
                                <label class="nom-label">Nom <span class="nom-req">*</span></label>
                                <input type="text" class="nom-input" id="n-nom" placeholder="Nom de famille">
                                <div class="nom-err" id="n-err-nom"></div>
                            </div>
                            <div class="nom-field">
                                <label class="nom-label">Téléphone <span class="nom-req">*</span></label>
                                <input type="tel" class="nom-input" id="n-phone" placeholder="+33 6 00 00 00 00">
                                <div class="nom-err" id="n-err-phone"></div>
                            </div>
                            <div class="nom-field">
                                <label class="nom-label">Email</label>
                                <input type="email" class="nom-input" id="n-email" placeholder="email@exemple.com">
                            </div>
                            <div class="nom-field">
                                <label class="nom-label">N° de chambre</label>
                                <input type="text" class="nom-input" id="n-room" placeholder="Ex : 214">
                            </div>
                            <div class="nom-field">
                                <label class="nom-label">Occasion</label>
                                <select class="nom-input nom-select" id="n-occasion">
                                    <option value="">— Sélectionner —</option>
                                    <option value="romantique">🌹 Dîner romantique</option>
                                    <option value="anniversaire">🎂 Anniversaire</option>
                                    <option value="affaires">💼 Repas d'affaires</option>
                                    <option value="famille">👨‍👩‍👧 Famille</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="nom-err mt-2" id="n-err-client"></div>
                </div>

                {{-- ── ÉTAPE 3 : Préférences & Paiement ── --}}
                <div class="nom-panel" id="nom-panel-3">
                    <div class="nom-panel-title"><i class="fas fa-heart me-2"></i>Préférences alimentaires</div>
                    <p class="nom-desc">Informations importantes pour la préparation de la commande.</p>

                    <div class="nom-section-lbl">Allergènes signalés</div>
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
                        <label class="nom-label">Autres allergies / restrictions</label>
                        <input type="text" class="nom-input" id="n-allergies-custom" placeholder="Ex : arachides, alcool…">
                    </div>

                    <div class="nom-section-lbl mt-4">Cuisson préférée</div>
                    <div class="nom-radio-row">
                        <label class="nom-radio"><input type="radio" name="n_cuisson" value="saignant"> Saignant</label>
                        <label class="nom-radio"><input type="radio" name="n_cuisson" value="a-point" checked> À point</label>
                        <label class="nom-radio"><input type="radio" name="n_cuisson" value="bien-cuit"> Bien cuit</label>
                    </div>

                    <div class="nom-section-lbl mt-4">Régime alimentaire</div>
                    <div class="nom-radio-row">
                        <label class="nom-radio"><input type="radio" name="n_regime" value="aucun" checked> Standard</label>
                        <label class="nom-radio"><input type="radio" name="n_regime" value="vegetarien"> 🥦 Végétarien</label>
                        <label class="nom-radio"><input type="radio" name="n_regime" value="vegan"> 🌱 Vegan</label>
                        <label class="nom-radio"><input type="radio" name="n_regime" value="halal"> ☪️ Halal</label>
                        <label class="nom-radio"><input type="radio" name="n_regime" value="kasher"> ✡️ Kasher</label>
                    </div>

                    <div class="nom-field mt-4">
                        <label class="nom-label">Notes pour le chef</label>
                        <textarea class="nom-input nom-textarea" id="n-notes" rows="3" placeholder="Cuisson particulière, présentation souhaitée, message spécial…"></textarea>
                    </div>

                    {{-- ════ FACTURATION : choix critique ════ --}}
                    <div class="nom-section-lbl mt-4">Mode de facturation</div>
                    <div class="nom-billing-grid">
                        <label class="nom-billing-choice" id="lbl-room-bill">
                            <input type="radio" name="n_billing" value="room" checked id="billing-room">
                            <div class="nom-billing-body">
                                <span class="nom-billing-icon">🔑</span>
                                <div>
                                    <div class="nom-billing-title">Mettre sur la chambre</div>
                                    <div class="nom-billing-sub">Ajouté à la facture du séjour</div>
                                </div>
                                <span class="nom-billing-check">✓</span>
                            </div>
                        </label>
                        <label class="nom-billing-choice" id="lbl-direct-pay">
                            <input type="radio" name="n_billing" value="direct" id="billing-direct">
                            <div class="nom-billing-body">
                                <span class="nom-billing-icon">💵</span>
                                <div>
                                    <div class="nom-billing-title">Paiement direct</div>
                                    <div class="nom-billing-sub">Le client paie maintenant</div>
                                </div>
                                <span class="nom-billing-check">✓</span>
                            </div>
                        </label>
                    </div>

                    {{-- Bloc chambre (visible si "room") --}}
                    <div id="block-room-billing" class="mt-3 p-3 rounded" style="background:#f0fdf4; border:1px solid #86efac;">
                        <div class="nom-ic-label mb-1">Chambre liée</div>
                        <div id="room-billing-info" class="fw-bold text-success">
                            <span id="room-billing-display">— sera déduite du client sélectionné —</span>
                        </div>
                        <small class="text-muted d-block mt-1">La commande s'ajoutera automatiquement à la facture de la chambre.</small>
                    </div>

                    {{-- Bloc paiement direct (visible si "direct") --}}
                    <div id="block-direct-billing" style="display:none" class="mt-3">
                        <div class="nom-section-lbl">Mode de règlement</div>
                        <div class="nom-pay-grid">
                            <label class="nom-pay"><input type="radio" name="n_payment" value="cash" checked>
                                <div class="nom-pay-body"><span>💵</span><span>Espèces</span></div></label>
                            <label class="nom-pay"><input type="radio" name="n_payment" value="card">
                                <div class="nom-pay-body"><span>💳</span><span>Carte</span></div></label>
                            <label class="nom-pay"><input type="radio" name="n_payment" value="mobile_money">
                                <div class="nom-pay-body"><span>📲</span><span>Mobile Money</span></div></label>
                            <label class="nom-pay"><input type="radio" name="n_payment" value="bank_transfer">
                                <div class="nom-pay-body"><span>🏦</span><span>Virement</span></div></label>
                        </div>
                    </div>
                </div>

                {{-- ── ÉTAPE 4 : Récapitulatif ── --}}
                <div class="nom-panel" id="nom-panel-4">
                    <div class="nom-panel-title"><i class="fas fa-check-circle me-2 text-success"></i>Récapitulatif de la commande</div>

                    <div class="nom-recap-grid">
                        <div class="nom-recap-block">
                            <div class="nom-recap-title"><i class="fas fa-user me-1"></i> Client</div>
                            <div id="nrecap-client"></div>
                        </div>
                        <div class="nom-recap-block">
                            <div class="nom-recap-title"><i class="fas fa-heart me-1"></i> Préférences</div>
                            <div id="nrecap-prefs"></div>
                        </div>
                    </div>

                    <div class="nom-recap-block mt-3">
                        <div class="nom-recap-title"><i class="fas fa-utensils me-1"></i> Plats commandés</div>
                        <div id="nrecap-items"></div>
                        <div class="nom-recap-total">Total : <strong id="nrecap-total">0 CFA</strong></div>
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
    background:linear-gradient(135deg,#1e293b,#0f172a);
    border-bottom:2px solid #d4af37;
}

.nom-header-left { display:flex; align-items:center; gap:12px; }
.nom-icon-wrap {
    width:38px; height:38px; background:#d4af37; border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    color:#0f172a; font-size:1rem;
}
.nom-title { font-size:1rem; font-weight:700; color:#f8fafc; }
.nom-subtitle { font-size:.7rem; color:#94a3b8; margin-top:1px; }
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
.nom-step.active .nom-dot { background:#d4af37; border-color:#d4af37; color:#0f172a; }
.nom-step.active span { color:#92740a; font-weight:600; }
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
.nom-toggle.active { background:#0f172a; color:#fff; }

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
.nom-input:focus { border-color:#d4af37; box-shadow:0 0 0 3px rgba(212,175,55,.12); background:#fff; }
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
.nom-filter.active { background:#0f172a; border-color:#0f172a; color:#fff; }
.nom-filter:hover:not(.active) { border-color:#0f172a; color:#0f172a; }

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
.nom-add-btn {
    background:#0f172a; color:#fff; border:none; border-radius:6px;
    font-size:.68rem; font-weight:600; padding:4px 9px; cursor:pointer;
    transition:background .16s; white-space:nowrap;
}
.nom-add-btn:hover { background:#1e293b; }
.nom-qty { display:flex; align-items:center; gap:5px; }
.nom-qty-btn {
    width:22px; height:22px; border-radius:50%; border:1px solid #d4af37;
    background:transparent; color:#d4af37; font-size:.9rem; font-weight:700;
    cursor:pointer; display:flex; align-items:center; justify-content:center;
    transition:all .14s;
}
.nom-qty-btn:hover { background:#d4af37; color:#0f172a; }
.nom-qval { font-size:.8rem; font-weight:700; color:#0f172a; min-width:16px; text-align:center; }

/* Panier */
.nom-basket {
    background:#fffbeb; border:1px solid #fde68a; border-radius:10px; padding:12px 14px;
}
.nom-basket-title { font-size:.76rem; font-weight:700; color:#92740a; margin-bottom:8px; }
.nom-basket-item {
    display:flex; justify-content:space-between;
    font-size:.76rem; color:#64748b; padding:4px 0;
    border-bottom:1px solid #fde68a;
}
.nom-basket-item:last-child { border-bottom:none; }
.nom-basket-total { font-size:.8rem; color:#92740a; font-weight:700; text-align:right; margin-top:8px; }

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
.nom-radio:has(input:checked) { border-color:#d4af37; background:#fffbeb; color:#92740a; font-weight:600; }
.nom-radio input { display:none; }

/* Paiement */
.nom-pay-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:8px; }
@media(max-width:600px){ .nom-pay-grid{grid-template-columns:repeat(2,1fr);} }
.nom-pay {
    cursor:pointer; border:1px solid #e2e8f0; border-radius:9px;
    background:#f8fafc; transition:all .16s;
}
.nom-pay:has(input:checked) { border-color:#d4af37; background:#fffbeb; }
.nom-pay input { display:none; }
.nom-pay-body {
    display:flex; flex-direction:column; align-items:center;
    padding:12px 8px; gap:5px; font-size:.72rem; color:#64748b; text-align:center;
}
.nom-pay:has(input:checked) .nom-pay-body { color:#92740a; font-weight:600; }
.nom-pay-body span:first-child { font-size:1.3rem; }

/* Récap */
.nom-recap-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
@media(max-width:600px){ .nom-recap-grid{grid-template-columns:1fr;} }
.nom-recap-block { background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:14px 16px; }
.nom-recap-title { font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; font-weight:700; margin-bottom:8px; }
.nom-recap-line { display:flex; justify-content:space-between; font-size:.8rem; color:#475569; padding:3px 0; }
.nom-recap-line span { color:#94a3b8; }
.nom-recap-item { display:flex; justify-content:space-between; font-size:.8rem; color:#475569; padding:5px 0; border-bottom:1px solid #e2e8f0; }
.nom-recap-item:last-child { border-bottom:none; }
.nom-recap-total { text-align:right; margin-top:10px; font-size:.88rem; color:#d4af37; font-weight:700; }

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
.nom-btn-primary { background:#0f172a; color:#fff; }
.nom-btn-primary:hover { background:#1e293b; }
.nom-btn-success { background:#10b981; color:#fff; box-shadow:0 3px 10px rgba(16,185,129,.3); }
.nom-btn-success:hover { background:#059669; }
.nom-btn-success:disabled { opacity:.5; cursor:not-allowed; }

/* Facturation billing choice */
.nom-billing-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
@media(max-width:600px){ .nom-billing-grid{grid-template-columns:1fr;} }
.nom-billing-choice { cursor:pointer; border:2px solid #e2e8f0; border-radius:12px; background:#f8fafc; transition:all .18s; }
.nom-billing-choice:has(input:checked) { border-color:#10b981; background:#f0fdf4; }
.nom-billing-choice input { display:none; }
.nom-billing-body { display:flex; align-items:center; gap:12px; padding:14px 16px; }
.nom-billing-icon { font-size:1.6rem; flex-shrink:0; }
.nom-billing-title { font-size:.84rem; font-weight:700; color:#0f172a; }
.nom-billing-sub { font-size:.7rem; color:#94a3b8; margin-top:1px; }
.nom-billing-check { margin-left:auto; color:#10b981; font-size:1.1rem; display:none; }
.nom-billing-choice:has(input:checked) .nom-billing-check { display:block; }

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
.nom-cart-row:hover { border-color:#d4af37; }
.nom-cart-row-info { flex:1; min-width:0; }
.nom-cart-row-img { width: 45px; height: 45px; border-radius: 6px; object-fit: cover; border: 1px solid #e2e8f0; flex-shrink: 0; }
.nom-cart-row-img-none { width: 45px; height: 45px; border-radius: 6px; background: #f1f5f9; color: #cbd5e1; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
.nom-cart-row-name { font-size:.85rem; font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.nom-cart-row-price { font-size:.7rem; color:#94a3b8; margin-top:2px; }
.nom-cart-row-controls {
    display:flex; align-items:center; gap:8px;
    background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:4px 8px;
}
.nom-cr-btn {
    width:24px; height:24px; border-radius:50%; border:1px solid #d4af37;
    background:transparent; color:#d4af37; font-size:1rem; font-weight:700;
    cursor:pointer; display:flex; align-items:center; justify-content:center;
    line-height:1; transition:all .14s;
}
.nom-cr-btn:hover { background:#d4af37; color:#0f172a; }
.nom-cr-qty { font-size:.85rem; font-weight:700; color:#0f172a; min-width:20px; text-align:center; }
.nom-cart-row-sub { font-size:.82rem; font-weight:700; color:#d4af37; white-space:nowrap; min-width:90px; text-align:right; }
.nom-cr-remove {
    background:transparent; border:none; color:#cbd5e1; cursor:pointer;
    font-size:.8rem; padding:4px; border-radius:50%; transition:all .14s;
    display:flex; align-items:center; justify-content:center;
}
.nom-cr-remove:hover { background:#fee2e2; color:#e11d48; }
.nom-cart-footer {
    margin-top:12px; padding:12px 16px;
    background:linear-gradient(135deg,#fffbeb,#fef3c7);
    border:1px solid #fde68a; border-radius:10px;
}
.nom-cart-total-line {
    display:flex; justify-content:space-between; align-items:center;
    font-size:.9rem; color:#92740a;
}
.nom-cart-total-line strong { font-size:1.05rem; color:#d4af37; }

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

    $('#printOrder').click(()=>window.print());

    /* ══════════════════════════════════════════
       NOUVELLE COMMANDE — MULTI-ÉTAPES
    ══════════════════════════════════════════ */
    let nomStep = 1;
    let nomItems = JSON.parse(localStorage.getItem('restaurant_cart') || '{}');
    let nomMode  = 'existing'; // 'existing' | 'new'

    /* Toggle client existant / nouveau */
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
        $('#disp-phone').text($this.data('phone') || '—');
        $('#disp-email').text($this.data('email') || '—');
        $('#existing-info').fadeIn();
        
        // Mettre à jour l'affichage de facturation chambre
        const billingMsg = $('#room-billing-display');
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

    /* Basculer entre facturation chambre / direct */
    $('input[name="n_billing"]').change(function(){
        const isRoom = $(this).val() === 'room';
        $('#block-room-billing').toggle(isRoom);
        $('#block-direct-billing').toggle(!isRoom);
        // Si paiement direct, vider le numéro de chambre
        if (!isRoom) $('#h-room').val('');
    });

    /* ── Navigation ── */
    $('#nom-next').click(function(){ if (validateNomStep(nomStep)) goNomStep(nomStep + 1); });
    $('#nom-prev').click(function(){ goNomStep(nomStep - 1); });

    function goNomStep(n) {
        if (n < 1 || n > 4) return;
        if (n === 4) buildNomRecap();
        $('.nom-step').each(function(){
            const s = parseInt($(this).data('step'));
            $(this).toggleClass('active', s === n).toggleClass('done', s < n);
        });
        $('.nom-panel').removeClass('active');
        $(`#nom-panel-${n}`).addClass('active');
        nomStep = n;
        $('#nom-prev').toggle(n > 1);
        $('#nom-next').toggle(n < 4);
        $('#nom-submit').toggle(n === 4);
        if (n === 4) $('#nom-next').hide();
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
            // Étape 2 : identification client
            $('#n-err-client').text('');
            if (nomMode === 'existing') {
                if (!$('#n-customer-select').val()) {
                    $('#n-err-client').text('Veuillez sélectionner un client.');
                    return false;
                }
            } else {
                let ok = true;
                if (!$('#n-prenom').val().trim()) { $('#n-err-prenom').text('Requis.'); ok = false; } else { $('#n-err-prenom').text(''); }
                if (!$('#n-nom').val().trim())    { $('#n-err-nom').text('Requis.'); ok = false; }    else { $('#n-err-nom').text(''); }
                if (!$('#n-phone').val().trim())  { $('#n-err-phone').text('Requis.'); ok = false; }  else { $('#n-err-phone').text(''); }
                return ok;
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
        let counterBadge = document.getElementById('cart-counter');
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
            emptyEl.style.display  = 'flex';
            footerEl.style.display = 'none';
            return;
        }

        emptyEl.style.display  = 'none';
        footerEl.style.display = 'block';

        let total = 0;
        let html  = '';
        items.forEach(it => {
            const sub = it.price * it.quantity;
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
            
            html += `
            <div class="nom-cart-row" data-id="${it.menu_id}">
                ${imgHtml}
                <div class="nom-cart-row-info">
                    <div class="nom-cart-row-name">${it.name}</div>
                    <div class="nom-cart-row-price">${it.price.toLocaleString('fr-FR')} CFA / unité</div>
                </div>
                <div class="nom-cart-row-controls">
                    <button type="button" class="nom-cr-btn nom-cr-minus" data-id="${it.menu_id}">−</button>
                    <span class="nom-cr-qty">${it.quantity}</span>
                    <button type="button" class="nom-cr-btn nom-cr-plus" data-id="${it.menu_id}">+</button>
                </div>
                <div class="nom-cart-row-sub">${sub.toLocaleString('fr-FR')} CFA</div>
                <button type="button" class="nom-cr-remove" data-id="${it.menu_id}" title="Retirer"><i class="fas fa-times"></i></button>
            </div>`;
        });

        listEl.innerHTML = html;
        totalEl.textContent = total.toLocaleString('fr-FR') + ' CFA';
    }

    /* ── Récapitulatif ── */
    function buildNomRecap() {
        let clientHtml = '';
        if (nomMode === 'existing') {
            const sel = $('#n-customer-select').find(':selected');
            clientHtml = `<div class="nom-recap-line"><span>Client</span>${sel.data('name')}</div>`;
            if (sel.data('room')) clientHtml += `<div class="nom-recap-line"><span>Chambre</span>${sel.data('room')}</div>`;
            if (sel.data('phone')) clientHtml += `<div class="nom-recap-line"><span>Tél.</span>${sel.data('phone')}</div>`;
        } else {
            clientHtml = `<div class="nom-recap-line"><span>Nom</span>${$('#n-prenom').val()} ${$('#n-nom').val()}</div>
                          <div class="nom-recap-line"><span>Tél.</span>${$('#n-phone').val()}</div>`;
            if ($('#n-room').val()) clientHtml += `<div class="nom-recap-line"><span>Chambre</span>${$('#n-room').val()}</div>`;
        }
        $('#nrecap-client').html(clientHtml);

        const allergens = [];
        $('.nom-allergen input:checked').each(function(){ allergens.push($(this).val()); });
        const custom = $('#n-allergies-custom').val().trim();
        if (custom) allergens.push(custom);
        const cuisson = $('input[name="n_cuisson"]:checked').val();
        const regime  = $('input[name="n_regime"]:checked').val();
        const billing = $('input[name="n_billing"]:checked').val();
        const payment = billing === 'room' ? 'room_charge' : ($('input[name="n_payment"]:checked').val() || 'cash');
        const billingLabel = billing === 'room' ? '🔑 Facturé sur la chambre' : '💵 Paiement direct (' + payment + ')';
        let prefHtml = `<div class="nom-recap-line"><span>Cuisson</span>${cuisson}</div>
                        <div class="nom-recap-line"><span>Régime</span>${regime}</div>
                        <div class="nom-recap-line"><span>Facturation</span>${billingLabel}</div>`;
        if (allergens.length) prefHtml += `<div class="nom-recap-line"><span>Allergies</span>${allergens.join(', ')}</div>`;
        $('#nrecap-prefs').html(prefHtml);

        const items = Object.values(nomItems);
        let itemsHtml = '', total = 0;
        items.forEach(it => {
            const sub = it.price * it.quantity; total += sub;
            itemsHtml += `<div class="nom-recap-item"><span>${it.name} × ${it.quantity}</span><strong>${sub.toLocaleString('fr-FR')} CFA</strong></div>`;
        });
        $('#nrecap-items').html(itemsHtml);
        $('#nrecap-total').text(total.toLocaleString('fr-FR') + ' CFA');

        // Remplir les champs hidden
        if (nomMode === 'existing') {
            const sel = $('#n-customer-select').find(':selected');
            $('#h-customer-id').val($('#n-customer-select').val());
            $('#h-customer-name').val(sel.data('name'));
            $('#h-phone').val(sel.data('phone') || '');
            $('#h-email').val(sel.data('email') || '');
            // Si facturation chambre → envoyer le numéro de chambre pour lier à la transaction
            $('#h-room').val(billing === 'room' ? (sel.data('room') || '') : '');
        } else {
            $('#h-customer-id').val('');
            $('#h-customer-name').val(($('#n-prenom').val()+' '+$('#n-nom').val()).trim());
            $('#h-phone').val($('#n-phone').val());
            $('#h-email').val($('#n-email').val());
            // Si facturation chambre → envoyer numéro de chambre manuel
            $('#h-room').val(billing === 'room' ? $('#n-room').val() : '');
        }

        const noteParts = [];
        if (allergens.length) noteParts.push('Allergies : ' + allergens.join(', '));
        if (cuisson !== 'a-point') noteParts.push('Cuisson : ' + cuisson);
        if (regime !== 'aucun') noteParts.push('Régime : ' + regime);
        const notesFree = $('#n-notes').val().trim();
        if (notesFree) noteParts.push(notesFree);
        $('#h-notes').val(noteParts.join(' | '));
        $('#h-payment').val(payment);
        $('#h-items').val(JSON.stringify(items.map(i => ({ menu_id: i.menu_id, quantity: i.quantity }))));
        $('#h-total').val(total.toFixed(2));
    }

    /* ── Soumission ── */
    $('#newOrderForm').submit(function(e) {
        e.preventDefault();
        if (Object.keys(nomItems).length === 0) {
            Swal.fire({ icon:'warning', title:'Sélection vide', text:'Ajoutez au moins un plat.' });
            return;
        }
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

    // ── Initialiser le compteur du panier dès le chargement de la page ──
    nomRenderBasket();

    }); // fin $(document).ready
}
initNomModal();
</script>
@endpush