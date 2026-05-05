<!-- ════════════════════════════════════════════════
     PANIER VITRINE — OFFCANVAS PREMIUM
     (Spécifique au client final)
════════════════════════════════════════════════ -->
<div class="offcanvas offcanvas-end r-cart-offcanvas" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
    <div class="oc-header">
        <div class="d-flex align-items-center gap-3">
            <div class="oc-icon"><i class="fas fa-shopping-basket"></i></div>
            <div>
                <h5 class="oc-title mb-0" id="cartOffcanvasLabel">Votre Panier</h5>
                <small class="oc-subtitle">Sélection de délices</small>
            </div>
        </div>
        <button type="button" class="oc-close" data-bs-dismiss="offcanvas" aria-label="Close">✕</button>
    </div>

    <div class="offcanvas-body oc-body">
        <div id="oc-cart-empty" class="oc-empty-state">
            <div class="oc-empty-icon"><i class="fas fa-utensils"></i></div>
            <h6>Votre panier est vide</h6>
            <p>Découvrez nos plats et ajoutez-les ici pour commander.</p>
        </div>

        <div id="oc-cart-list" class="oc-items-list">
            {{-- Rempli dynamiquement --}}
        </div>

        <!-- Formulaire Client (Simplifié) -->
        <div id="oc-checkout-form" class="oc-checkout-section d-none">
            <div class="oc-section-divider"><span>VOS INFORMATIONS</span></div>
            
            <div class="oc-field mb-3">
                <label class="oc-label">NOM COMPLET</label>
                <input type="text" id="oc-cust-name" class="oc-input" placeholder="Ex: Jean Dupont">
            </div>

            <div class="oc-field mb-3">
                <label class="oc-label">N° DE CHAMBRE (SI RÉSIDENT)</label>
                <input type="text" id="oc-cust-room" class="oc-input" placeholder="Ex: 302">
            </div>

            <div class="oc-field mb-3">
                <label class="oc-label">TÉLÉPHONE</label>
                <input type="tel" id="oc-cust-phone" class="oc-input" placeholder="+221 ...">
            </div>

            <div class="oc-field mb-4">
                <label class="oc-label">NOTES SPÉCIALES</label>
                <textarea id="oc-cust-notes" class="oc-input" rows="2" placeholder="Allergies, préférences..."></textarea>
            </div>
        </div>
    </div>

    <div class="oc-footer d-none" id="oc-cart-footer">
        <div class="oc-total-box">
            <span class="oc-total-label">TOTAL ESTIMÉ</span>
            <span class="oc-total-val" id="oc-cart-total">0 CFA</span>
        </div>
        <button type="button" class="oc-btn-main" id="oc-submit-order">
            CONFIRMER LA COMMANDE <i class="fas fa-arrow-right ms-2"></i>
        </button>
    </div>
</div>

<style>
.r-cart-offcanvas { width: 450px !important; border-left: none; background: var(--white); box-shadow: -15px 0 50px rgba(0,0,0,0.1); }
@media (max-width: 576px) { .r-cart-offcanvas { width: 100% !important; } }

.oc-header { padding: 30px; background: linear-gradient(135deg, var(--g900), var(--g800)); color: white; display: flex; align-items: center; justify-content: space-between; border-bottom: 3px solid var(--g400); }
.oc-icon { width: 44px; height: 44px; background: rgba(255,255,255,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--g300); font-size: 1.2rem; }
.oc-title { font-weight: 800; letter-spacing: -0.5px; }
.oc-subtitle { font-size: 0.75rem; opacity: 0.7; }
.oc-close { background: transparent; border: 1px solid rgba(255,255,255,0.2); color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: var(--transition); }
.oc-close:hover { background: #ef4444; border-color: #ef4444; }

.oc-body { padding: 30px; scrollbar-width: thin; }

.oc-empty-state { padding: 60px 20px; text-align: center; color: var(--s400); }
.oc-empty-icon { font-size: 3rem; margin-bottom: 20px; opacity: 0.3; }

.oc-items-list { margin-bottom: 30px; }
.oc-item { display: flex; align-items: center; gap: 15px; padding: 15px 0; border-bottom: 1px solid var(--s100); }
.oc-item-img { width: 50px; height: 50px; border-radius: 10px; object-fit: cover; }
.oc-item-info { flex: 1; }
.oc-item-name { font-size: 0.95rem; font-weight: 700; color: var(--s900); }
.oc-item-price { font-size: 0.75rem; color: var(--g600); font-weight: 600; font-family: var(--mono); }

.oc-item-controls { display: flex; align-items: center; gap: 12px; background: var(--surface2); padding: 5px 10px; border-radius: 10px; }
.oc-qty-btn { border: none; background: transparent; font-weight: 800; color: var(--s700); cursor: pointer; }
.oc-qty-val { font-family: var(--mono); font-weight: 800; font-size: 0.9rem; }

.oc-checkout-section { border-top: 1px solid var(--s200); padding-top: 25px; }
.oc-section-divider { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
.oc-section-divider span { font-size: 0.7rem; font-weight: 900; color: var(--s400); letter-spacing: 2px; }
.oc-section-divider::after { content: ''; flex: 1; height: 1px; background: var(--s100); }

.oc-label { font-size: 0.65rem; font-weight: 800; color: var(--s600); margin-bottom: 6px; display: block; letter-spacing: 1px; }
.oc-input { width: 100%; padding: 12px 15px; border: 1.5px solid var(--s200); border-radius: 10px; background: var(--surface); transition: var(--transition); }
.oc-input:focus { border-color: var(--g400); background: white; outline: none; box-shadow: 0 0 0 4px rgba(30,107,46,0.05); }

.oc-footer { padding: 30px; background: var(--surface); border-top: 1px solid var(--s200); }
.oc-total-box { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 20px; }
.oc-total-label { font-size: 0.85rem; font-weight: 600; color: var(--s500); }
.oc-total-val { font-size: 1.4rem; font-weight: 800; font-family: var(--mono); color: var(--g700); }
.oc-btn-main { width: 100%; padding: 18px; background: var(--g600); color: white; border: none; border-radius: 16px; font-weight: 800; transition: var(--transition); box-shadow: 0 10px 20px rgba(30,107,46,0.2); }
.oc-btn-main:hover { background: var(--g700); transform: translateY(-3px); box-shadow: 0 12px 25px rgba(30,107,46,0.3); }
</style>
