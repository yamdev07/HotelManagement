    {{-- ── MENU SECTION ── --}}
    <section class="menu-section" id="menuSection">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-tag">Notre Carte</span>
                <h2 class="section-title">Découvrez nos créations</h2>
                <p style="font-size:1rem;color:var(--text-gray);max-width:520px;margin:0 auto;">
                    Une sélection de plats raffinés préparés avec des produits frais et locaux.
                </p>
                <div class="mt-4" data-aos="fade-up" data-aos-delay="40">
                    <a href="#"
                        style="display:inline-flex;align-items:center;gap:10px;padding:13px 30px;
                              background:linear-gradient(135deg,#5C3317,#8B5A2B);color:#fff;
                              border-radius:50px;font-size:0.92rem;font-weight:700;text-decoration:none;
                              box-shadow:0 6px 20px rgba(92,51,23,0.3);transition:all .3s ease;"
                        onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 10px 28px rgba(92,51,23,0.4)'"
                        onmouseout="this.style.transform='none';this.style.boxShadow='0 6px 20px rgba(92,51,23,0.3)'">
                        <i class="fas fa-globe-africa"></i>
                        Spécialités Africaines
                        <span
                            style="background:rgba(255,255,255,0.2);border-radius:20px;padding:2px 9px;font-size:11px;">Nouveau</span>
                    </a>
                </div>
            </div>

            {{-- Onglets de catégorie --}}
            <div class="category-tabs-grid mb-5" data-aos="fade-up" data-aos-delay="60">
                <button class="category-filter active" data-category="all"><i class="fas fa-th me-1"></i>Tout voir</button>
                @foreach($categories as $category)
                    <button class="category-filter" data-category="{{ $category->slug }}">
                        <i class="fas fa-utensils me-1"></i> {{ $category->name }}
                    </button>
                @endforeach
            </div>

            {{-- Grille menu (une catégorie à la fois par défaut) --}}
            <div class="row g-3" id="menuList">
                <div id="noItemsMessage" class="col-12 text-center py-5 d-none no-items-empty-state">
                    <div style="width:80px;height:80px;border-radius:50%;background:rgba(26,71,42,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:2rem;color:var(--cactus-green);">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4 style="font-family:'Playfair Display',serif;color:var(--text-dark);">Aucun plat disponible</h4>
                    <p style="color:var(--text-gray);">Il n'y a pas de plats dans cette catégorie aujourd'hui.</p>
                </div>
                @forelse($menus as $menu)
                    <div class="col-6 col-md-4 col-lg-3 menu-item" data-category="{{ $menu->category?->slug }}" data-aos="fade-up"
                        data-aos-delay="{{ ($loop->index % 4) * 60 }}">
                        <div class="menu-card">
                        <div class="menu-card-img" style="cursor: pointer;" 
                             onclick="showDishDetail('{{ addslashes($menu->name) }}', '{{ addslashes($menu->description) }}', '{{ $menu->image_url }}', '{{ number_format($menu->price, 0, ',', ' ') }}', '{{ $menu->category?->name }}')">
                                <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" onerror="this.onerror=null; this.src='https://i.pinimg.com/736x/fc/7a/4a/fc7a4ad5e3299c1dac28baa60eef6111.jpg';">
                        </div>
                            <div class="menu-card-body">
                                <div class="menu-card-header">
                                    <div class="menu-card-name">{{ $menu->name }}</div>
                                    <div class="menu-card-price">{{ number_format($menu->price, 0, ',', ' ') }} FCFA</div>
                                </div>
                                <p class="menu-card-desc">{{ $menu->description }}</p>
                                <div class="menu-card-footer">
                                    <span class="cat-badge">
                                        {{ $menu->category?->name ?? 'Sans catégorie' }}
                                    </span>
                                    @if($showOrderControls ?? true)
                                    <div class="v-qty-controls" data-id="{{ $menu->id }}">
                                        <button class="btn-order add-to-order" id="v-addbtn-{{ $menu->id }}"
                                            data-menu-id="{{ $menu->id }}" data-menu-name="{{ $menu->name }}"
                                            data-menu-price="{{ $menu->price }}"
                                            data-menu-image="{{ $menu->image_url }}">
                                            <i class="fas fa-cart-plus"></i> Commander
                                        </button>
                                        <div class="v-qty-wrapper d-none" id="v-qty-wrapper-{{ $menu->id }}">
                                            <button type="button" class="v-qty-btn v-qminus"
                                                data-id="{{ $menu->id }}">−</button>
                                            <span class="v-qval" id="v-qval-{{ $menu->id }}">0</span>
                                            <button type="button" class="v-qty-btn v-qplus"
                                                data-id="{{ $menu->id }}">+</button>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div
                            style="width:80px;height:80px;border-radius:50%;background:rgba(26,71,42,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:2rem;color:var(--cactus-green);">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h4 style="font-family:'Playfair Display',serif;color:var(--text-dark);">Menu en préparation</h4>
                        <p style="color:var(--text-gray);">Notre chef travaille sur de nouvelles créations.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════
             OFFCANVAS COMMANDE — LUXURY SIDE PANEL
        ════════════════════════════════════════════ -->
    @if($showOrderControls ?? true)
    <div class="offcanvas offcanvas-end v-offcanvas" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" data-bs-backdrop="static">
        <div class="v-offcanvas-content">
            {{-- En-tête --}}
            <div class="om-header">
                <div class="om-header-left">
                    <span class="om-crown"><i class="fas fa-crown"></i></span>
                    <div>
                        <div class="om-title">Finaliser ma Commande</div>
                        <div class="om-subtitle">Cactus Palace — Expérience Gastronomique</div>
                    </div>
                </div>
                <button type="button" class="om-close" data-bs-dismiss="offcanvas"><i class="fas fa-times"></i></button>
            </div>

                {{-- Barre de progression --}}
                <div class="om-steps">
                    <div class="om-step active" data-step="1">
                        <div class="om-step-dot">1</div>
                        <div class="om-step-lbl">Identification</div>
                    </div>
                    <div class="om-step-line"></div>
                    <div class="om-step" data-step="2">
                        <div class="om-step-dot">2</div>
                        <div class="om-step-lbl">Paiement</div>
                    </div>
                    <div class="om-step-line"></div>
                    <div class="om-step" data-step="3">
                        <div class="om-step-dot">3</div>
                        <div class="om-step-lbl">Récapitulatif</div>
                    </div>
                </div>

                <form id="orderForm" action="{{ route('restaurant.orders.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="items" id="itemsInput">
                    <input type="hidden" name="total" id="totalInput">
                    <input type="hidden" name="customer_name" id="hCustomerName">
                    <input type="hidden" name="phone" id="hPhone">
                    <input type="hidden" name="email" id="hEmail">
                    <input type="hidden" name="room_number" id="hRoom">
                    <input type="hidden" name="notes" id="hNotes">
                    <input type="hidden" name="payment_method" id="hPayment" value="cash">

                    <div class="om-body">

                        {{-- ── ÉTAPE 1 : Informations ── --}}
                        <div class="om-panel active" id="panel-1">
                            <div class="om-field mb-3">
                                <label class="om-label" for="f-customer-name">Votre Nom <span class="text-white-50">(Optionnel)</span></label>
                                <div class="om-input-icon">
                                    <span class="om-icon"><i class="fas fa-user"></i></span>
                                    <input type="text" class="om-input has-icon" id="f-customer-name" placeholder="Ex: Jean Dupont">
                                </div>
                            </div>
                            <p class="om-panel-desc mb-3">Où souhaitez-vous être servi ?</p>
                            
                            <!-- Choix du lieu de service -->
                            <div class="mb-4 d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="order_location" id="locRoom" value="room" checked>
                                    <label class="form-check-label text-light fw-bold" for="locRoom" style="font-size: 0.85rem;">
                                        <i class="fas fa-bed me-1 text-gold"></i> En Chambre
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="order_location" id="locTable" value="table">
                                    <label class="form-check-label text-light fw-bold" for="locTable" style="font-size: 0.85rem;">
                                        <i class="fas fa-utensils me-1 text-gold"></i> Au Restaurant
                                    </label>
                                </div>
                            </div>

                            <!-- Champs specifiques Chambre -->
                            <div class="om-grid-2" id="room_service_fields">
                                <div class="om-field">
                                    <label class="om-label" for="f-room">N° de chambre <span class="om-req">*</span></label>
                                    <div class="om-input-icon">
                                        <span class="om-icon"><i class="fas fa-door-closed"></i></span>
                                        <input type="text" class="om-input has-icon" id="f-room" placeholder="Ex : 214">
                                    </div>
                                    <div class="om-err" id="err-room"></div>
                                </div>
                                <div class="om-field">
                                    <label class="om-label" for="f-email">Email enregistré <span class="om-req">*</span></label>
                                    <div class="om-input-icon">
                                        <span class="om-icon"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="om-input has-icon" id="f-email" placeholder="Email de réservation">
                                    </div>
                                    <div class="om-err" id="err-email"></div>
                                </div>
                            </div>

                            <!-- Champs specifiques Table -->
                            <div class="d-none" id="table_service_fields">
                                <div class="om-grid-2 mb-3">
                                    <div class="om-field" style="grid-column: span 2;">
                                        <label class="om-label" for="f-table">N° de Table <span class="om-req">*</span></label>
                                        <div class="om-input-icon">
                                            <span class="om-icon"><i class="fas fa-hashtag"></i></span>
                                            <input type="text" class="om-input has-icon" id="f-table" placeholder="Votre numéro de table">
                                        </div>
                                        <div class="om-err" id="err-table"></div>
                                    </div>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_resident">
                                    <label class="form-check-label text-gold-light" for="is_resident" style="font-size: 0.8rem; cursor:pointer;">
                                        Je suis résident à l'hôtel (Facturer sur ma chambre)
                                    </label>
                                </div>

                                <div class="om-grid-2 d-none" id="resident_extra_fields">
                                    <div class="om-field">
                                        <label class="om-label" for="f-room-alt">N° de chambre <span class="om-req">*</span></label>
                                        <input type="text" class="om-input" id="f-room-alt" placeholder="Ex : 214">
                                        <div class="om-err" id="err-room-alt"></div>
                                    </div>
                                    <div class="om-field">
                                        <label class="om-label" for="f-email-alt">Email enregistré <span class="om-req">*</span></label>
                                        <input type="email" class="om-input" id="f-email-alt" placeholder="Email de réservation">
                                        <div class="om-err" id="err-email-alt"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="om-grid-2 mt-2">
                                <div class="om-field" style="grid-column: span 2;">
                                    <label class="om-label" for="f-notes">Notes spéciales (optionnel)</label>
                                    <textarea class="om-input" id="f-notes" rows="2" placeholder="Allergies, préférences de cuisson..."></textarea>
                                </div>
                            </div>
                        </div>




                        {{-- Étape 2: Mode de règlement --}}
                        <div class="om-panel" id="panel-2">
                            <div class="om-panel-title">
                                <i class="fas fa-credit-card me-1 om-panel-icon"></i> Mode de règlement
                            </div>
                            <p class="text-white-50 mb-4" style="font-size: 0.9rem;">Comment souhaitez-vous régler votre
                                commande ?</p>

                            <div class="om-payment-grid"
                                style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 15px;">
                                <label class="om-payment-card active" id="pay-card-cash">
                                    <input type="radio" name="payment_choice" value="cash" checked style="display:none">
                                    <div class="om-pc-icon"><i class="fas fa-money-bill-wave"></i></div>
                                    <div class="om-pc-label">Espèces</div>
                                    <div class="om-pc-status"><i class="fas fa-check-circle"></i></div>
                                </label>
                                <label class="om-payment-card" id="pay-card-mobile">
                                    <input type="radio" name="payment_choice" value="mobile_money" style="display:none">
                                    <div class="om-pc-icon"><i class="fas fa-mobile-alt"></i></div>
                                    <div class="om-pc-label">Mobile Money</div>
                                    <div class="om-pc-status"><i class="fas fa-check-circle"></i></div>
                                </label>
                                <label class="om-payment-card" id="pay-card-card">
                                    <input type="radio" name="payment_choice" value="card" style="display:none">
                                    <div class="om-pc-icon"><i class="fas fa-credit-card"></i></div>
                                    <div class="om-pc-label">Carte Bancaire</div>
                                    <div class="om-pc-status"><i class="fas fa-check-circle"></i></div>
                                </label>
                                <label class="om-payment-card" id="pay-card-fedapay">
                                    <input type="radio" name="payment_choice" value="fedapay" style="display:none">
                                    <div class="om-pc-icon"><i class="fas fa-wallet"></i></div>
                                    <div class="om-pc-label">Fedapay</div>
                                    <div class="om-pc-status"><i class="fas fa-check-circle"></i></div>
                                </label>
                                <label class="om-payment-card" id="pay-card-transfer">
                                    <input type="radio" name="payment_choice" value="transfer" style="display:none">
                                    <div class="om-pc-icon"><i class="fas fa-university"></i></div>
                                    <div class="om-pc-label">Virement</div>
                                    <div class="om-pc-status"><i class="fas fa-check-circle"></i></div>
                                </label>
                                <label class="om-payment-card" id="pay-card-check">
                                    <input type="radio" name="payment_choice" value="check" style="display:none">
                                    <div class="om-pc-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                                    <div class="om-pc-label">Chèque</div>
                                    <div class="om-pc-status"><i class="fas fa-check-circle"></i></div>
                                </label>
                                <div id="room-payment-notice" class="col-span-2 d-none" style="grid-column: span 2;">
                                    <div class="om-payment-card active w-100" style="cursor: default;">
                                        <input type="radio" name="payment_choice" value="room_charge" style="display:none">
                                        <div class="om-pc-icon"><i class="fas fa-hotel"></i></div>
                                        <div class="om-pc-label">Facture de la chambre</div>
                                        <div class="om-pc-status"><i class="fas fa-check-circle"></i></div>
                                    </div>
                                </div>
                            </div>
                            {{-- Hint removed at user request --}}
                            <div id="payment-normal-hint" class="d-none"></div>
                            <div id="payment-room-hint" class="mt-4 p-3 rounded d-none"
                                style="background: rgba(46, 125, 50, 0.1); border: 1px dashed #4caf50;">
                                <p class="mb-0" style="color: #81c784; font-size: 0.85rem;">
                                    <i class="fas fa-check-circle me-2"></i> Client de l'hôtel détecté. La commande sera ajoutée directement à votre note de chambre.
                                </p>
                            </div>


                        </div>

                        {{-- Étape 3: Confirmation Finale --}}
                        <div class="om-panel" id="panel-3">
                            <div class="om-checkout-grid">
                                {{-- Gauche: Infos & Paiement --}}
                                <div class="om-checkout-left">
                                    <div class="om-panel-title">
                                        <i class="fas fa-id-card me-1 om-panel-icon"></i> Vos Informations
                                    </div>
                                    <div class="om-section-label">Identification</div>
                                    <div id="recap-identity" class="om-recap-block">
                                        {{-- Rempli via JS --}}
                                    </div>

                                    <div class="om-section-label mt-4">Paiement</div>
                                    <div id="recap-payment" class="om-recap-block">
                                        {{-- Rempli via JS --}}
                                    </div>
                                    
                                    <div class="mt-4 p-3 rounded" style="background: rgba(212,175,55,0.03); border: 1px solid rgba(212,175,55,0.1);">
                                        <div style="color: #c0b080; font-size: 0.75rem; line-height: 1.5;">
                                            <i class="fas fa-leaf me-2"></i> Votre commande sera préparée avec soin et livrée dans les plus brefs délais par notre équipe.
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Droite: Contenu du Panier --}}
                                <div class="om-checkout-right">
                                    <div class="om-section-label">Plats sélectionnés</div>
                                    <div class="om-recap-items-container">
                                        <div id="recap-items" style="max-height: 300px; overflow-y: auto;">
                                            {{-- Rempli via JS --}}
                                        </div>
                                    </div>

                                    <div class="om-recap-total-box mt-4">
                                        <div class="om-total-subtitle">À régler</div>
                                        <div id="recap-total" class="om-total-amount">0 FCFA</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>{{-- /om-body --}}

                    {{-- Pied de modal --}}
                    <div class="om-footer">
                        <button type="button" class="om-btn om-btn-ghost" id="om-prev" style="display:none">
                            ← Précédent
                        </button>
                        <button type="button" class="om-btn om-btn-outline" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="om-btn om-btn-gold" id="om-next">
                            Suivant →
                        </button>
                        <button type="submit" class="om-btn om-btn-gold" id="om-submit" style="display:none">
                            <i class="fas fa-check me-1"></i> Confirmer
                        </button>
                    </div>

                </form>
            </div>{{-- /v-offcanvas-content --}}
        </div>
    </div>
    @endif

    {{-- Modal Détail Plat --}}
    <div class="modal fade" id="dishDetailModal" tabindex="-1" aria-hidden="true" style="z-index: 9999;">
        <div class="modal-dialog modal-xl modal-dialog-centered"> {{-- Changé lg en xl --}}
            <div class="modal-content border-0 overflow-hidden shadow-lg" style="border-radius: 20px;">
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <div class="col-lg-7" id="modalDishImgSide"> {{-- Plus de place pour l'image (7/12) --}}
                            <div id="modalDishImgWrap" class="position-relative w-100 h-100" style="min-height: 350px; background: #f8f9fa;">
                                <img id="modalDishImg" src="" alt="" class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover;"
                                     onerror="this.classList.add('d-none'); document.getElementById('modalDishNoImg').classList.remove('d-none');">
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="pointer-events: none;">
                                    <i id="modalDishNoImg" class="fas fa-utensils fa-5x text-muted d-none"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5"> {{-- Reste pour le texte (5/12) --}}
                            <div class="p-4 p-lg-5 h-100 d-flex flex-column" style="min-height: 350px;">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <span id="modalDishCategory" class="cat-badge px-3 py-2"></span>
                                    <button type="button" class="btn-close bg-light rounded-circle p-2" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <h1 id="modalDishName" class="section-title mb-4" style="font-size: 2.8rem; color: var(--cactus-green); line-height: 1.1;"></h1>
                                <div class="description-scroll pe-2" style="max-height: 250px; overflow-y: auto;">
                                    <p id="modalDishDesc" class="text-muted mb-0" style="line-height: 1.8; font-size: 1.1rem; text-align: justify;"></p>
                                </div>
                                <div class="mt-auto pt-5 border-top">
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="price-wrap">
                                            <span class="text-muted small d-block mb-1 text-uppercase fw-bold" style="letter-spacing: 1px;">Prix Gastronomique</span>
                                            <span class="h2 fw-bold text-success mb-0" style="color: var(--cactus-green) !important;"><span id="modalDishPrice"></span> <small style="font-size: 14px; font-weight: 600;">FCFA</small></span>
                                        </div>
                                        <button type="button" class="btn btn-outline-success py-2 px-4 shadow-sm fw-bold" data-bs-dismiss="modal" style="border-radius: 12px; border-width: 2px; transition: all 0.3s ease; font-size: 0.9rem;">
                                            RETOUR AU MENU
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($showOrderControls ?? true)
    {{-- Panier flottant --}}
    <button id="floating-cart">
        <i class="fas fa-shopping-basket fa-lg"></i>
        <span id="cart-badge">0</span>
    </button>
    @endif


@push('styles')
    <style>
        /* ── MENU SECTION ── */
        .menu-section .container {
            max-width: 95%;
            padding-left: 10px;
            padding-right: 10px;
        }

        .menu-section {
            background: var(--white);
            padding: 40px 0 80px;
        }

        .category-filter {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 22px;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            border: 1.5px solid var(--border-color);
            background: var(--white);
            color: var(--text-gray);
            transition: var(--transition);
            margin: 0;
            width: 100%;
            justify-content: center;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .category-tabs-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
            margin: 0 auto;
            max-width: 1000px;
        }

        @media (max-width: 991px) {
            .category-tabs-grid { grid-template-columns: repeat(4, 1fr); }
        }

        @media (max-width: 576px) {
            .category-tabs-grid { 
                grid-template-columns: repeat(3, 1fr);
                gap: 6px;
            }
            .category-filter {
                padding: 8px 4px;
                font-size: 0.65rem;
                gap: 4px;
            }
            .category-filter i { font-size: 0.7rem; }
        }

        .category-filter:hover {
            border-color: var(--cactus-green);
            color: var(--cactus-green);
        }

        .category-filter.active {
            background: var(--cactus-green);
            border-color: var(--cactus-green);
            color: var(--white);
            box-shadow: 0 4px 14px rgba(26, 71, 42, 0.2);
        }

        .menu-card {
            background: var(--white);
            border-radius: 20px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: var(--transition);
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }

        .menu-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-color: transparent;
        }

        .menu-card-img {
            flex-shrink: 0;
            width: 100%;
            height: 180px;
            overflow: hidden;
            background: var(--light-bg);
            position: relative;
        }

        .menu-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s ease;
        }

        .menu-card:hover .menu-card-img img {
            transform: scale(1.08);
        }

        .menu-card-noimg {
            width: 100%;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--light-bg);
            flex-shrink: 0;
        }

        .menu-card-noimg i {
            font-size: 2.5rem;
            color: rgba(26, 71, 42, 0.15);
        }

        .menu-card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .menu-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .menu-card-name {
            font-family: 'Playfair Display', serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .menu-card-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--cactus-green);
            white-space: nowrap;
        }

        .menu-card-desc {
            font-size: 0.85rem;
            color: var(--text-gray);
            line-height: 1.6;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .menu-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 14px;
        }

        .cat-badge {
            display: inline-block;
            padding: 3px 12px;
            background: rgba(26, 71, 42, 0.08);
            color: var(--cactus-green);
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-order {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            background: var(--gold-accent);
            color: var(--cactus-dark);
            border: none;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-order:hover {
            background: #b8962e;
            transform: translateY(-1px);
        }

        /* Contrôle quantité Vitrine */
        .v-qty-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(26, 71, 42, 0.05);
            border: 1.5px solid var(--gold-accent);
            border-radius: 10px;
            padding: 5px 12px;
        }

        .v-qty-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--gold-accent);
            color: var(--cactus-dark);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .v-qty-btn:hover {
            background: #b8962e;
            transform: scale(1.1);
        }

        .v-qval {
            font-weight: 700;
            color: var(--cactus-green);
            min-width: 20px;
            text-align: center;
        }

        /* Responsive Dish Detail Modal */
        @media (max-width: 991px) {
            #modalDishImgWrap, 
            #modalDishImgSide + .col-lg-5 > div {
                min-height: auto !important;
            }
            #modalDishImgWrap {
                height: 250px !important;
            }
            #modalDishName {
                font-size: 1.8rem !important;
                margin-bottom: 1rem !important;
            }
            #modalDishImgSide + .col-lg-5 > div {
                padding: 1.5rem !important;
            }
        }

        /* Responsive Grid for 2-cols mobile and 3-cols tablet */
        @media (max-width: 991px) {
            .menu-card-img { height: 160px; }
            .menu-card-name { font-size: 0.95rem; }
            .menu-card-price { font-size: 1rem; }
        }

        @media (max-width: 576px) {
            .menu-section .container { padding-left: 10px; padding-right: 10px; }
            .row.g-3 { --bs-gutter-x: 0.75rem; --bs-gutter-y: 0.75rem; }
            .menu-card-body { padding: 12px; }
            .menu-card-img { height: 130px; }
            .menu-card-name { font-size: 0.85rem; line-height: 1.3; }
            .menu-card-price { font-size: 0.9rem; }
            .menu-card-desc { display: none; }
            .cat-badge { display: none; }
            .btn-order { padding: 8px 10px; font-size: 0.7rem; width: 100%; justify-content: center; }
            .v-qty-wrapper { padding: 5px 8px; gap: 8px; border-radius: 8px; }
            .v-qty-btn { width: 24px; height: 24px; font-size: 0.8rem; }
            .v-qval { font-size: 0.8rem; min-width: 15px; }
        }

        /* ══════════════════════════════════════════════════════
           OFFCANVAS LUXURY — SIDE ORDER PANEL
        ══════════════════════════════════════════════════════ */
        .v-offcanvas {
            width: 480px !important;
            background: #0e0e0e !important;
            border-left: 1px solid rgba(212, 175, 55, 0.3) !important;
            box-shadow: -20px 0 50px rgba(0,0,0,0.8) !important;
            color: #fff;
        }

        @media (max-width: 576px) {
            .v-offcanvas {
                width: 100% !important;
            }
            .om-header { padding: 15px 20px; }
            .om-steps { padding: 12px 15px; }
            .om-step-lbl { display: none; }
            .om-body { padding: 20px 18px; }
            .om-footer { padding: 12px 18px; }
            .om-btn { padding: 10px 14px; font-size: 0.75rem; }
        }

        .v-offcanvas-content {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .om-card {
            border: none;
            background: transparent;
            display: flex;
            flex-direction: column;
            flex: 1;
            overflow: hidden;
        }

        #orderForm {
            display: flex;
            flex-direction: column;
            flex: 1;
            overflow: hidden;
        }

        .om-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 22px 30px 18px;
            background: linear-gradient(135deg, #1a1208, #2a1e08);
            border-bottom: 1px solid rgba(212, 175, 55, .25);
        }

        .om-header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .om-crown { color: #d4af37; font-size: 1.6rem; }
        .om-title { font-size: 1.15rem; font-weight: 700; color: #f5f0e0; }
        .om-subtitle { font-size: .72rem; color: #a09060; text-transform: uppercase; }

        .om-close {
            background: none; border: 1px solid rgba(212, 175, 55, .3);
            color: #a09060; width: 32px; height: 32px; border-radius: 50%;
            cursor: pointer; transition: all .2s;
            display: flex; align-items: center; justify-content: center;
        }

        .om-close:hover { background: rgba(212, 175, 55, .15); color: #d4af37; }

        .om-steps {
            display: flex; align-items: center; padding: 18px 30px;
            background: #141414; border-bottom: 1px solid #222;
        }

        .om-step { display: flex; align-items: center; gap: 8px; flex: 1; }
        .om-step-dot {
            width: 30px; height: 30px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: .78rem; font-weight: 700; border: 2px solid #333;
            color: #555; background: #1a1a1a; transition: all .3s;
        }

        .om-step-lbl { font-size: .72rem; color: #555; white-space: nowrap; }
        .om-step.active .om-step-dot { background: #d4af37; border-color: #d4af37; color: #0e0e0e; }
        .om-step.active .om-step-lbl { color: #d4af37; }
        .om-step.done .om-step-dot { background: #2a6; border-color: #2a6; color: #fff; }
        .om-step.done .om-step-lbl { color: #4ade80; }
        .om-step-line { flex: 1; height: 1px; background: #2a2a2a; margin: 0 8px; }

        .om-body { padding: 28px 30px; background: #111; flex: 1; overflow-y: auto; }
        .om-panel { display: none; }
        .om-panel.active { display: block; animation: omFadeIn .3s ease; }

        @keyframes omFadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: none; }
        }

        .om-panel-title { font-size: 1rem; font-weight: 700; color: #e8d8b0; display: flex; align-items: center; gap: 10px; margin-bottom: 6px; }
        .om-panel-desc { font-size: .8rem; color: #707070; margin-bottom: 22px; }

        .om-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media(max-width:991px) { .om-grid-2 { grid-template-columns: 1fr; } }

        .om-field { display: flex; flex-direction: column; gap: 5px; }
        .om-label { font-size: .72rem; color: #a09060; text-transform: uppercase; }
        .om-req { color: #d4af37; }

        .om-input {
            background: #1a1a1a; border: 1px solid #2e2e2e; border-radius: 9px;
            color: #f0e8d0; padding: 11px 14px; font-size: .88rem; width: 100%;
            outline: none; transition: all .2s;
        }
        @media (max-width: 768px) {
            .om-input { font-size: 16px !important; }
        }

        .om-input:focus { border-color: #d4af37; box-shadow: 0 0 0 3px rgba(212,175,55,.12); }
        .om-input-icon { position: relative; }
        .om-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: .85rem; color: #a09060; }
        .om-input.has-icon { padding-left: 36px; }
        .om-err { font-size: .72rem; color: #f87171; min-height: 16px; }
        .om-hint { font-size: .68rem; color: #444; }

        .om-payment-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .om-payment-card.active {
            border-color: var(--gold-accent);
            background: rgba(212, 175, 55, 0.08);
        }

        .om-pc-icon {
            font-size: 1.5rem;
            color: var(--gold-accent);
            margin-bottom: 10px;
        }

        .om-pc-label {
            font-size: 0.9rem;
            color: #fff;
            font-weight: 600;
        }

        .om-pc-status {
            position: absolute;
            top: 10px;
            right: 10px;
            color: var(--gold-accent);
            font-size: 1.1rem;
            display: none;
        }

        .om-payment-card.active .om-pc-status {
            display: block;
        }

        .om-checkout-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 25px;
        }
        @media (max-width: 768px) {
            .om-checkout-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .om-payment-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }

        .om-recap-block { 
            background: #141414; border: 1px solid #1e1e1e; border-radius: 12px; padding: 10px 18px; 
            box-shadow: inset 0 0 20px rgba(0,0,0,0.2);
        }
        .om-recap-line { 
            font-size: .85rem; color: #fff; padding: 12px 0; display: flex; justify-content: space-between; align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.05); 
        }
        .om-recap-line:last-child { border-bottom: none; }
        .om-recap-line span { color: #888; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; }
        .om-recap-line strong { color: var(--gold-accent); }

        .om-recap-items-container {
            background: #1a1a1a; border-radius: 12px; padding: 5px 15px; border: 1px solid #2a2a2a;
        }

        .om-recap-total-box {
            background: linear-gradient(135deg, var(--cactus-dark) 0%, #0f2918 100%);
            border: 2px solid var(--gold-accent); border-radius: 15px; padding: 22px; text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .om-total-subtitle { font-size: 0.75rem; color: var(--gold-accent); text-transform: uppercase; letter-spacing: 3px; margin-bottom: 8px; opacity: 0.8; }
        .om-total-amount { font-size: 2rem; font-weight: 800; color: #fff; text-shadow: 0 2px 10px rgba(0,0,0,0.3); }

        .om-recap-item {
            display: flex; align-items: center; gap: 12px; padding: 12px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .om-recap-item:last-child { border-bottom: none; }
        .om-recap-img { width: 50px; height: 50px; border-radius: 8px; object-fit: cover; }
        .om-recap-info { flex: 1; }
        .om-recap-name { font-size: 0.9rem; color: #fff; font-weight: 600; }
        .om-recap-controls { display: flex; align-items: center; gap: 10px; margin-top: 4px; }
        .om-recap-qty-btn {
            width: 20px; height: 20px; border-radius: 50%; border: 1px solid #d4af37;
            background: transparent; color: #d4af37; font-size: 0.8rem;
            display: flex; align-items: center; justify-content: center; cursor: pointer;
        }
        .om-recap-qty-val { font-size: 0.85rem; color: #fff; min-width: 15px; text-align: center; }
        .om-recap-price { font-size: 0.9rem; color: #d4af37; font-weight: 700; }

        .om-footer {
            display: flex; align-items: center; justify-content: flex-end; gap: 10px;
            padding: 16px 20px; background: #0e0e0e; border-top: 1px solid #1e1e1e;
        }
        #om-prev { margin-right: auto; padding-left: 0; padding-right: 10px; }
        .om-btn {
            padding: 10px 18px; border-radius: 9px; font-size: .8rem; font-weight: 700;
            cursor: pointer; border: none; transition: all .18s;
            display: inline-flex; align-items: center; gap: 6px; white-space: nowrap;
        }
        .om-btn-gold { background: var(--gold-accent); color: var(--cactus-dark); }
        .om-btn-gold:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(212,175,55,0.2); }
        .om-btn-outline { background: transparent; border: 1px solid rgba(255,255,255,0.1); color: #777; }
        .om-btn-outline:hover { border-color: #d4af37; color: #d4af37; }
        .om-btn-ghost { background: transparent; color: #aaa; padding: 10px 10px; }
        .om-btn-ghost:hover { color: #fff; }

        #floating-cart {
            position: fixed; bottom: 30px; right: 30px; width: 64px; height: 64px;
            background: var(--cactus-green); color: #fff; border-radius: 50%;
            display: none; align-items: center; justify-content: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3); z-index: 999; border: 2px solid var(--gold-accent);
            cursor: pointer; transition: all 0.3s ease;
        }
        #floating-cart:hover { transform: scale(1.1) rotate(5deg); }
        #cart-badge {
            position: absolute; top: -5px; right: -5px; background: var(--gold-accent);
            color: var(--cactus-dark); width: 24px; height: 24px; border-radius: 50%;
            font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            /* ═══════════════════════════════
               FILTRAGE DE LA CARTE
            ═══════════════════════════════ */
            $('.category-filter').click(function() {
                const $btn = $(this);
                if($btn.hasClass('active')) return;

                $('.category-filter').removeClass('active');
                $btn.addClass('active');
                const cat = $btn.data('category');
                
                // On cache tout immédiatement
                $('.menu-item').addClass('d-none').css('opacity', '0');
                
                let visibleCount = 0;
                let $items;
                
                if (cat === 'all') {
                    $items = $('.menu-item');
                } else {
                    $items = $(`.menu-item[data-category="${cat}"]`);
                }

                visibleCount = $items.length;
                
                if (visibleCount === 0) {
                    $('#noItemsMessage').removeClass('d-none');
                } else {
                    $('#noItemsMessage').addClass('d-none');
                    // On affiche immédiatement sans délai AOS ni transition
                    $items.removeClass('d-none').css({
                        'opacity': '1',
                        'transition': 'none'
                    }).addClass('aos-animate');
                    
                    // On restaure la transition pour les futurs hovers
                    setTimeout(() => {
                        $items.css('transition', '');
                    }, 100);
                }

                if (typeof AOS !== 'undefined') {
                    AOS.refresh();
                }
            });

            /* ═══════════════════════════════
               LOGIQUE PANIER ET COMMANDE
            ═══════════════════════════════ */
            let orderItems = {};
            const TOTAL_STEPS = 3;
            let currentStep = 1;

            function saveCart() {
                const data = {
                    items: orderItems,
                    customer: {
                        fullname: $('#f-fullname').val(),
                        room: $('#f-room').val()
                    }
                };
                localStorage.setItem('cactus_cart', JSON.stringify(data));
            }

            function loadCart() {
                const saved = localStorage.getItem('cactus_cart');
                if (saved) {
                    try {
                        const data = JSON.parse(saved);
                        if (data.items) {
                            orderItems = data.items;
                            if (data.customer) {
                                $('#f-fullname').val(data.customer.fullname || '');
                                $('#f-room').val(data.customer.room || '');
                            }
                        } else {
                            orderItems = data;
                        }
                        Object.keys(orderItems).forEach(id => syncUI(id));
                        updateFloatingCart();
                    } catch (e) {
                        orderItems = {};
                    }
                }
            }

            $(document).on('input', '#f-fullname, #f-room', saveCart);

            $(document).on('click', '.add-to-order', function() {
                const id = $(this).data('menu-id');
                const name = $(this).data('menu-name');
                const price = parseFloat($(this).data('menu-price'));
                const image = $(this).data('menu-image') || 'https://i.pinimg.com/736x/fc/7a/4a/fc7a4ad5e3299c1dac28baa60eef6111.jpg';

                if (!orderItems[id]) {
                    orderItems[id] = { menu_id: id, name, price, image, quantity: 1 };
                }
                syncUI(id);
                updateFloatingCart();
                saveCart();
            });

            $(document).on('click', '.v-qplus', function() {
                const id = $(this).data('id');
                if (orderItems[id]) {
                    orderItems[id].quantity++;
                    syncUI(id);
                    updateFloatingCart();
                    saveCart();
                }
            });

            $(document).on('click', '.v-qminus', function() {
                const id = $(this).data('id');
                if (orderItems[id]) {
                    orderItems[id].quantity--;
                    if (orderItems[id].quantity <= 0) delete orderItems[id];
                    syncUI(id);
                    updateFloatingCart();
                    saveCart();
                }
            });

            function syncUI(id) {
                const item = orderItems[id];
                const btn = $(`#v-addbtn-${id}`);
                const wrapper = $(`#v-qty-wrapper-${id}`);
                const val = $(`#v-qval-${id}`);

                if (item && item.quantity > 0) {
                    btn.addClass('d-none');
                    wrapper.removeClass('d-none').addClass('d-flex');
                    val.text(item.quantity);
                } else {
                    btn.removeClass('d-none');
                    wrapper.addClass('d-none').removeClass('d-flex');
                    val.text(0);
                }
            }

            function updateFloatingCart() {
                const count = Object.values(orderItems).reduce((sum, item) => sum + item.quantity, 0);
                if (count > 0) {
                    $('#floating-cart').css('display', 'flex');
                    $('#cart-badge').text(count);
                } else {
                    $('#floating-cart').hide();
                }
            }

            $('#floating-cart').click(function() {
                goToStep(1);
                const offcanvasElement = document.getElementById('orderModal');
                const bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement) || new bootstrap.Offcanvas(offcanvasElement);
                bsOffcanvas.show();
            });

            $('#om-next').click(function() { validateAndNext(); });
            $('#om-prev').click(function() { goToStep(currentStep - 1); });

            $('input[name="order_location"]').change(function() {
                if ($(this).val() === 'room') {
                    $('#room_service_fields').removeClass('d-none');
                    $('#table_service_fields').addClass('d-none');
                } else {
                    $('#room_service_fields').addClass('d-none');
                    $('#table_service_fields').removeClass('d-none');
                }
                $('#err-email, #err-room, #err-table, #err-email-alt, #err-room-alt').text('');
            });

            $('#is_resident').change(function() {
                if ($(this).is(':checked')) {
                    $('#resident_extra_fields').removeClass('d-none');
                } else {
                    $('#resident_extra_fields').addClass('d-none');
                }
            });

            function validateAndNext() {
                if (currentStep !== 1) { goToStep(currentStep + 1); return; }

                let ok = true;
                const location = $('input[name="order_location"]:checked').val();
                $('#err-email, #err-room, #err-table, #err-email-alt, #err-room-alt').text('');

                if (location === 'room') {
                    const emailVal = $('#f-email').val().trim();
                    const roomVal = $('#f-room').val().trim();
                    
                    if (!emailVal) { $('#err-email').text("L'email est requis."); ok = false; }
                    if (!roomVal) { $('#err-room').text('Le n° de chambre est requis.'); ok = false; }
                    if (!ok) return;

                    const $btn = $('#om-next').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Vérification…');
                    $.get('/api/restaurant/check-room', { 
                        room_number: roomVal,
                        email: emailVal
                    })
                    .done(function(res) {
                        if (res.valid) { goToStep(3); } // Skip payment step for room service (always on bill)
                        else { $('#err-room').html(`<span class="text-danger">${res.message}</span>`); }
                    })
                    .fail(function() {
                        $('#err-room').html('<span class="text-danger">Erreur de vérification.</span>');
                    })
                    .always(function() {
                        $btn.prop('disabled', false).html('Suivant →');
                    });
                } else {
                    const tableVal = $('#f-table').val().trim();
                    if (!tableVal) { $('#err-table').text('Le n° de table est requis.'); ok = false; }
                    
                    if ($('#is_resident').is(':checked')) {
                        const emailAlt = $('#f-email-alt').val().trim();
                        const roomAlt = $('#f-room-alt').val().trim();
                        if (!emailAlt) { $('#err-email-alt').text("L'email est requis."); ok = false; }
                        if (!roomAlt) { $('#err-room-alt').text('Le n° de chambre est requis.'); ok = false; }
                        
                        if (!ok) return;

                        const $btn = $('#om-next').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Vérification…');
                        $.get('/api/restaurant/check-room', { 
                            room_number: roomAlt,
                            email: emailAlt
                        })
                        .done(function(res) {
                            if (res.valid) { goToStep(3); } // Link to room, skip payment
                            else { $('#err-room-alt').html(`<span class="text-danger">${res.message}</span>`); }
                        })
                        .fail(function() {
                            $('#err-room-alt').html('<span class="text-danger">Erreur de vérification.</span>');
                        })
                        .always(function() {
                            $btn.prop('disabled', false).html('Suivant →');
                        });
                    } else {
                        if (!ok) return;
                        goToStep(2); // Regular table client, choose payment
                    }
                }
            }

            function goToStep(n) {
                if (n < 1 || n > TOTAL_STEPS) return;
                const location = $('input[name="order_location"]:checked').val();
                const isResident = $('#is_resident').is(':checked');
                const isGuestForBill = (location === 'room' || isResident);

                if (isGuestForBill && n === 2 && currentStep === 1) n = 3;
                if (isGuestForBill && n === 2 && currentStep === 3) n = 1;
                
                if (n === 2) {
                    // We'll sync UI after the potential room_charge check below
                }

                if (n === TOTAL_STEPS) buildRecap();

                if (isGuestForBill) {
                    $('#panel-2 label.om-payment-card').addClass('d-none');
                    $('#room-payment-notice, #payment-room-hint').removeClass('d-none');
                    $('#payment-normal-hint').addClass('d-none');
                    $('input[name="payment_choice"][value="room_charge"]').prop('checked', true);
                } else {
                    $('#panel-2 label.om-payment-card').removeClass('d-none');
                    $('#room-payment-notice, #payment-room-hint').addClass('d-none');
                    $('#payment-normal-hint').removeClass('d-none');
                    if ($('input[name="payment_choice"]:checked').val() === 'room_charge') {
                        $('input[name="payment_choice"][value="cash"]').prop('checked', true);
                    }
                }

                // IMPORTANT: Final sync of active class for payment grid
                if (n === 2) {
                    $('.om-payment-card').removeClass('active');
                    const currentVal = $('input[name="payment_choice"]:checked').val() || 'cash';
                    $(`input[name="payment_choice"][value="${currentVal}"]`).prop('checked', true).closest('.om-payment-card').addClass('active');
                }

                $('.om-step').each(function() {
                    const s = parseInt($(this).data('step'));
                    $(this).toggleClass('active', s === n).toggleClass('done', s < n);
                });
                $('.om-panel').removeClass('active');
                $(`#panel-${n}`).addClass('active');
                currentStep = n;

                $('#om-prev').toggle(n > 1);
                $('#om-next').toggle(n < TOTAL_STEPS);
                $('#om-submit').toggle(n === TOTAL_STEPS);
            }

            function buildRecap() {
                const location = $('input[name="order_location"]:checked').val();
                const isResident = $('#is_resident').is(':checked');
                const notes = $('#f-notes').val().trim();
                let idHtml = '';

                const custName = $('#f-customer-name').val().trim();

                // Identification
                if (location === 'room') {
                    const room = $('#f-room').val().trim();
                    const email = $('#f-email').val().trim();
                    idHtml += `<div class="om-recap-line"><span>Service</span><strong>Room Service</strong></div>`;
                    idHtml += `<div class="om-recap-line"><span>Chambre</span><strong>${room}</strong></div>`;
                    if (custName) idHtml += `<div class="om-recap-line"><span>Nom</span><strong>${custName}</strong></div>`;
                    
                    $('#hCustomerName').val(custName || ("Room Service - " + room));
                    $('#hEmail').val(email); 
                    $('#hRoom').val(room);
                } else {
                    const table = $('#f-table').val().trim();
                    idHtml += `<div class="om-recap-line"><span>Service</span><strong>Table ${table}</strong></div>`;
                    if (isResident) {
                        const roomAlt = $('#f-room-alt').val().trim();
                        const emailAlt = $('#f-email-alt').val().trim();
                        idHtml += `<div class="om-recap-line"><span>Client</span><strong>Résident (Ch. ${roomAlt})</strong></div>`;
                        if (custName) idHtml += `<div class="om-recap-line"><span>Nom</span><strong>${custName}</strong></div>`;
                        
                        $('#hCustomerName').val(custName || ("Table " + table + " (Resident " + roomAlt + ")"));
                        $('#hEmail').val(emailAlt); $('#hRoom').val(roomAlt);
                    } else {
                        idHtml += `<div class="om-recap-line"><span>Client</span><strong>Extérieur</strong></div>`;
                        if (custName) idHtml += `<div class="om-recap-line"><span>Nom</span><strong>${custName}</strong></div>`;
                        
                        $('#hCustomerName').val(custName || ("Client Table " + table));
                        $('#hEmail').val(""); $('#hRoom').val('');
                    }
                }
                if (notes) idHtml += `<div class="om-recap-line"><span>Notes</span><strong>${notes}</strong></div>`;
                $('#recap-identity').html(idHtml);

                // Paiement
                let payHtml = '';
                const pMode = (location === 'room' || isResident) ? 'room_charge' : $('input[name="payment_choice"]:checked').val();
                let pLabel = "Espèces";
                if(pMode === 'mobile_money') pLabel = "Mobile Money";
                else if(pMode === 'card') pLabel = "Carte Bancaire";
                else if(pMode === 'fedapay') pLabel = "Fedapay";
                else if(pMode === 'transfer') pLabel = "Virement";
                else if(pMode === 'check') pLabel = "Chèque";
                else if(pMode === 'room_charge') pLabel = "Facture de la chambre";

                payHtml += `<div class="om-recap-line"><span>Méthode</span><strong>${pLabel}</strong></div>`;
                $('#recap-payment').html(payHtml);

                // Items
                const items = Object.values(orderItems);
                let itemsHtml = '', total = 0;
                items.forEach(it => {
                    const sub = it.price * it.quantity;
                    total += sub;
                    itemsHtml += `
                        <div class="om-recap-item">
                            <img src="${it.image}" class="om-recap-img">
                            <div class="om-recap-info">
                                <div class="om-recap-name">${it.name}</div>
                                <div class="om-recap-controls">
                                    <button type="button" class="om-recap-qty-btn m-qminus" data-id="${it.menu_id}">−</button>
                                    <span class="om-recap-qty-val">${it.quantity}</span>
                                    <button type="button" class="om-recap-qty-btn m-qplus" data-id="${it.menu_id}">+</button>
                                </div>
                            </div>
                            <div class="om-recap-price"><span>${Math.round(sub).toLocaleString('fr-FR')}</span></div>
                        </div>`;
                });
                $('#recap-items').html(itemsHtml);
                $('#recap-total').text(Math.round(total).toLocaleString('fr-FR') + ' FCFA');

                $('#hNotes').val(notes);
                $('#hPayment').val(pMode);
                $('#itemsInput').val(JSON.stringify(items.map(i => ({ menu_id: i.menu_id, quantity: i.quantity }))));
                $('#totalInput').val(total.toFixed(2));
            }

            $(document).on('click', '.om-payment-card', function() {
                $('.om-payment-card').removeClass('active');
                $(this).addClass('active').find('input').prop('checked', true);
            });

            $('#orderForm').submit(function(e) {
                e.preventDefault();
                const btn = $('#om-submit');
                btn.prop('disabled', true).text('Envoi…');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: new FormData(this),
                    processData: false, contentType: false,
                    success: function() {
                        Swal.fire({ icon: 'success', title: 'Commande confirmée', confirmButtonColor: '#d4af37' }).then(() => {
                            bootstrap.Offcanvas.getInstance(document.getElementById('orderModal'))?.hide();
                            resetModal();
                        });
                    },
                    error: function() {
                        Swal.fire({ icon: 'error', title: 'Erreur' });
                        btn.prop('disabled', false).text('Confirmer');
                    }
                });
            });

            function resetModal() {
                orderItems = {}; currentStep = 1; updateFloatingCart();
                const saved = JSON.parse(localStorage.getItem('cactus_cart') || '{}');
                saved.items = {}; localStorage.setItem('cactus_cart', JSON.stringify(saved));
                $('.v-qty-wrapper').addClass('d-none'); $('.add-to-order').removeClass('d-none');
            }

            $(document).on('click', '.m-qplus', function() {
                const id = $(this).data('id');
                if (orderItems[id]) { orderItems[id].quantity++; syncUI(id); updateFloatingCart(); saveCart(); buildRecap(); }
            });

            $(document).on('click', '.m-qminus', function() {
                const id = $(this).data('id');
                if (orderItems[id]) {
                    orderItems[id].quantity--;
                    if (orderItems[id].quantity <= 0) delete orderItems[id];
                    syncUI(id); updateFloatingCart(); saveCart(); buildRecap();
                }
            });

            window.showDishDetail = function(name, description, image, price, category) {
                $('#modalDishName').text(name);
                $('#modalDishDesc').text(description || '...');
                $('#modalDishPrice').text(price);
                $('#modalDishCategory').text(category || 'Plat');
                // Toujours réinitialiser l'état avant d'affecter la nouvelle image
                $('#modalDishNoImg').addClass('d-none');
                if (image) {
                    $('#modalDishImg').removeClass('d-none').attr('src', image);
                    // onerror sur le tag img gère le cas 404
                } else {
                    $('#modalDishImg').addClass('d-none').attr('src', '');
                    $('#modalDishNoImg').removeClass('d-none');
                }
                new bootstrap.Modal(document.getElementById('dishDetailModal')).show();
            };

            loadCart();
        });
    </script>
@endpush
