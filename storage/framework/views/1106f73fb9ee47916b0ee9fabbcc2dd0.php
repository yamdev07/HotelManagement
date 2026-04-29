<?php $__env->startSection('title', 'Plan du Restaurant'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.rp * { box-sizing: border-box; }

/* ════════════════════════════════════════
   PAGE
════════════════════════════════════════ */
.rp {
    display: flex; flex-direction: column;
    height: calc(100vh - 48px);
    background: #f1f5f9;
    font-family: system-ui,-apple-system,'Segoe UI',sans-serif;
    overflow: hidden;
}

/* ════════════════════════════════════════
   TOOLBAR
════════════════════════════════════════ */
.rp-bar {
    display: flex; align-items: center; gap: 8px;
    padding: 0 16px; height: 54px;
    background: #fff;
    border-bottom: 1px solid #e2e8f0;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
    flex-shrink: 0;
}
.rp-brand { display:flex; align-items:center; gap:10px; margin-right:4px; }
.rp-brand-icon {
    width:32px;height:32px; background:#10b981; border-radius:8px;
    display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0;
}
.rp-brand h2 { font-size:.88rem;font-weight:700;color:#0f172a;margin:0;white-space:nowrap; }
.rp-brand small { font-size:.62rem;color:#94a3b8;display:block;line-height:1; }
.rp-sep { width:1px;height:26px;background:#e2e8f0;flex-shrink:0; }
.btn {
    display:inline-flex;align-items:center;gap:5px;
    padding:6px 12px;border-radius:7px;font-size:.77rem;font-weight:600;
    cursor:pointer;border:1px solid transparent;font-family:inherit;
    transition:all .14s;white-space:nowrap;
}
.btn-light { background:#f8fafc;border-color:#e2e8f0;color:#475569; }
.btn-light:hover { background:#f1f5f9;border-color:#cbd5e1;color:#1e293b; }
.btn-light:disabled { opacity:.38;cursor:default; }
.btn-green { background:#10b981;border-color:#059669;color:#fff; }
.btn-green:hover { background:#059669; }
.btn-green:disabled { opacity:.5;cursor:default; }
.btn-danger { background:#fff1f2;border-color:#fecdd3;color:#e11d48; }
.btn-danger:hover { background:#ffe4e6; }
.rp-toggle {
    display:flex;align-items:center;gap:5px;font-size:.75rem;color:#64748b;
    cursor:pointer;padding:5px 8px;border-radius:6px;user-select:none;transition:background .14s;
}
.rp-toggle:hover{background:#f8fafc;}
.rp-toggle input{accent-color:#10b981;cursor:pointer;}
.rp-toggle.on{color:#10b981;font-weight:600;}
.zoom-row{display:flex;align-items:center;gap:4px;}
.zoom-lbl{font-size:.72rem;color:#94a3b8;min-width:36px;text-align:center;}
.rp-stats{margin-left:auto;display:flex;gap:18px;align-items:center;}
.rp-stat{display:flex;flex-direction:column;align-items:center;font-size:.64rem;color:#94a3b8;line-height:1.3;}
.rp-stat strong{font-size:.86rem;color:#0f172a;font-weight:700;}

/* ════════════════════════════════════════
   CORPS
════════════════════════════════════════ */
.rp-body { display:flex;flex:1;overflow:hidden; }

/* Palette */
.rp-pal {
    width:158px;flex-shrink:0;background:#fff;
    border-right:1px solid #e2e8f0;overflow-y:auto;padding:10px 8px 24px;
    scrollbar-width:thin;scrollbar-color:#e2e8f0 transparent;
}
.pal-tip{font-size:.66rem;color:#94a3b8;text-align:center;background:#f8fafc;border:1px dashed #e2e8f0;border-radius:7px;padding:7px;margin-bottom:8px;line-height:1.5;}
.pal-group{font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;padding:8px 4px 3px;}
.pal-item{
    display:flex;align-items:center;gap:7px;padding:7px 9px;border-radius:8px;
    cursor:grab;border:1px solid transparent;transition:all .15s;margin-bottom:2px;
}
.pal-item:hover{background:#f0fdf4;border-color:#bbf7d0;transform:translateX(2px);}
.pal-item:active{cursor:grabbing;transform:scale(.97);}
.pal-prev{width:34px;height:34px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.pal-lbl{font-size:.73rem;font-weight:600;color:#1e293b;}
.pal-sub{font-size:.61rem;color:#94a3b8;}

/* Canvas wrap */
.rp-wrap{flex:1;overflow:auto;display:flex;align-items:flex-start;justify-content:flex-start;background:#dde3ea;padding:0;}

/* ════════════════════════════════════════
   CANVAS  —  Parquet animé
════════════════════════════════════════ */
#rp-cv {
    position:relative;
    width:1140px; height:680px;
    flex-shrink:0;
    border-radius:12px;
    border:2px solid #c8b89a;
    box-shadow:
        0 0 0 8px rgba(200,180,140,.25),
        0 24px 60px rgba(0,0,0,.18);
    overflow:hidden;
    transform-origin:top left;
    transition:transform .22s, margin .22s;

    /* Parquet lames */
    background-color:#f0e0c4;
    background-image:
        repeating-linear-gradient(
            90deg,
            transparent 0px, transparent 119px,
            rgba(160,100,40,.12) 119px, rgba(160,100,40,.12) 120px
        ),
        repeating-linear-gradient(
            0deg,
            transparent 0px, transparent 39px,
            rgba(160,100,40,.08) 39px, rgba(160,100,40,.08) 40px
        ),
        repeating-linear-gradient(
            90deg,
            rgba(255,220,160,.06) 0px, rgba(255,220,160,.06) 60px,
            rgba(210,160,80,.06) 60px, rgba(210,160,80,.06) 120px
        );
    animation: floor-breathe 8s ease-in-out infinite;
}
@keyframes floor-breathe {
    0%,100% { background-color:#f0e0c4; }
    50%      { background-color:#ecdab8; }
}
#rp-cv.no-grid {
    background-image:
        repeating-linear-gradient(90deg,rgba(255,220,160,.06) 0,rgba(255,220,160,.06) 60px,rgba(210,160,80,.06) 60px,rgba(210,160,80,.06) 120px);
}
/* Murs intérieurs */
#rp-cv::before {
    content:'';position:absolute;inset:14px;
    border:2px solid rgba(140,90,30,.2);border-radius:8px;
    pointer-events:none;z-index:0;
    box-shadow:inset 0 0 40px rgba(0,0,0,.06);
}

/* ════════════════════════════════════════
   ÉLÉMENTS CANVAS
════════════════════════════════════════ */
.cv-el {
    position:absolute;
    display:flex;align-items:center;justify-content:center;flex-direction:column;
    cursor:grab;user-select:none;
    transition:
        filter .2s ease,
        box-shadow .2s ease,
        transform .15s cubic-bezier(.34,1.56,.64,1);
    z-index:2;
    will-change:transform,filter;
}
.cv-el:active{cursor:grabbing;}

/* ── Hover lift (sans interférer avec la rotation) ── */
.cv-el:not(.drag):not(.sel):hover {
    filter:brightness(1.1) drop-shadow(0 8px 16px rgba(0,0,0,.28));
    z-index:50 !important;
}

/* ── Sélectionné : glow animé ── */
.cv-el.sel {
    outline:none;
    filter:drop-shadow(0 0 0 #10b981);
    animation:sel-glow 1.8s ease-in-out infinite;
    z-index:100 !important;
}
@keyframes sel-glow {
    0%,100% { filter:drop-shadow(0 0 4px rgba(16,185,129,.9)); }
    50%      { filter:drop-shadow(0 0 12px rgba(16,185,129,.5)) drop-shadow(0 0 4px rgba(16,185,129,.8)); }
}

/* ── Drag ── */
.cv-el.drag {
    cursor:grabbing;z-index:200 !important;
    filter:drop-shadow(0 16px 28px rgba(0,0,0,.35));
    transition:filter .1s;
}

/* ── Apparition d'un nouvel élément ── */
.cv-el.anim-in {
    animation:drop-in .42s cubic-bezier(.34,1.56,.64,1) both;
}
@keyframes drop-in {
    from { transform:scale(0.2) rotate(-12deg); opacity:0; }
    to   { transform:scale(1) rotate(0deg);    opacity:1; }
}
/* NB : quand la table a une rotation, elle est sur le style inline,
   donc drop-in part du même angle → on neutralise via la variable ci-dessous */

/* ════════════════════════════════════════
   FORMES & TEXTURES
════════════════════════════════════════ */

/* Table ronde */
.cv-el.s-round {
    border-radius:50%;
    background:
        radial-gradient(circle at 35% 28%, rgba(255,255,255,.22) 0%, transparent 55%),
        conic-gradient(from 30deg, #d4a06a, #e8c090, #c98850, #d9a870, #c48040, #d4a06a);
    border:2px solid rgba(100,60,10,.2);
    box-shadow:
        0 6px 18px rgba(0,0,0,.22),
        0 2px 4px rgba(0,0,0,.12),
        inset 0 1px 0 rgba(255,255,255,.35),
        inset 0 -3px 8px rgba(0,0,0,.12);
}
/* Nappe */
.cv-el.s-round::before {
    content:'';position:absolute;inset:18%;
    border-radius:50%;
    background:rgba(255,255,255,.14);
    border:1px solid rgba(255,255,255,.25);
    pointer-events:none;
}

/* Table carrée */
.cv-el.s-square {
    border-radius:8px;
    background:
        linear-gradient(135deg, rgba(255,255,255,.18) 0%, transparent 50%),
        linear-gradient(0deg, #be8050 0%, #d4a06a 40%, #c8904e 100%);
    border:2px solid rgba(100,60,10,.18);
    box-shadow:
        0 6px 18px rgba(0,0,0,.2),
        inset 0 1px 0 rgba(255,255,255,.3),
        inset 0 -3px 6px rgba(0,0,0,.1);
}
.cv-el.s-square::before {
    content:'';position:absolute;inset:14%;
    border-radius:4px;
    background:rgba(255,255,255,.1);
    border:1px solid rgba(255,255,255,.2);
    pointer-events:none;
}

/* Table rectangle */
.cv-el.s-rect {
    border-radius:8px;
    background:
        linear-gradient(120deg, rgba(255,255,255,.16) 0%, transparent 45%),
        repeating-linear-gradient(90deg, #c8904e 0px, #d4a06a 30px, #c08040 60px);
    border:2px solid rgba(100,60,10,.18);
    box-shadow:
        0 6px 18px rgba(0,0,0,.2),
        inset 0 1px 0 rgba(255,255,255,.28),
        inset 0 -3px 6px rgba(0,0,0,.1);
}
.cv-el.s-rect::before {
    content:'';position:absolute;inset:12%;
    border-radius:4px;
    background:rgba(255,255,255,.08);
    border:1px solid rgba(255,255,255,.16);
    pointer-events:none;
}

/* Bar / Comptoir */
.cv-el.s-bar {
    border-radius:6px;
    background:
        linear-gradient(180deg, rgba(255,255,255,.12) 0%, transparent 30%),
        repeating-linear-gradient(90deg, #3a2010 0px, #4a2e18 40px, #3a2010 80px);
    border:3px solid rgba(255,255,255,.1);
    box-shadow:
        0 8px 24px rgba(0,0,0,.35),
        inset 0 2px 0 rgba(255,255,255,.15),
        inset 0 -4px 8px rgba(0,0,0,.3);
}

/* ════════════════════════════════════════
   CHAISE  (vue de dessus avec dossier)
════════════════════════════════════════ */
.cv-el.s-chair {
    border-radius:50%;
    background:
        radial-gradient(circle at 38% 32%, #d4ae78 0%, #a07848 52%, #7c5630 100%);
    border:1.5px solid rgba(80,40,0,.25);
    box-shadow:
        0 4px 10px rgba(0,0,0,.22),
        0 1px 3px rgba(0,0,0,.15),
        inset 0 1px 0 rgba(255,255,255,.28),
        inset 0 -2px 5px rgba(0,0,0,.18);
    overflow:visible;
}
/* Dossier de la chaise (arc en haut) */
.cv-el.s-chair::after {
    content:'';
    position:absolute;
    top:8%;left:12%;
    width:76%;height:46%;
    border:2.5px solid rgba(70,35,0,.45);
    border-bottom:none;
    border-radius:50% 50% 0 0;
    pointer-events:none;
    background:transparent;
}
/* Assise (cercle intérieur) */
.cv-el.s-chair::before {
    content:'';
    position:absolute;
    inset:22%;
    border-radius:50%;
    background:radial-gradient(circle at 40% 35%, rgba(255,255,255,.2), transparent 70%);
    border:1px solid rgba(255,255,255,.2);
    pointer-events:none;
}
.cv-el.s-chair:hover { transform:scale(1.14) !important; }

/* Plante */
.cv-el.s-plant {
    border-radius:50%;
    border:2px dashed rgba(22,163,74,.5);
    background:radial-gradient(circle at 40% 35%, #bbf7d0, #4ade80 55%, #16a34a);
    box-shadow:0 4px 12px rgba(22,163,74,.3);
    animation:plant-sway 4s ease-in-out infinite;
}
@keyframes plant-sway {
    0%,100% { transform:rotate(0deg) scale(1); }
    25%      { transform:rotate(3deg) scale(1.02); }
    75%      { transform:rotate(-3deg) scale(1.02); }
}
.cv-el.s-plant.sel { animation:plant-sway 4s ease-in-out infinite, sel-glow 1.8s ease-in-out infinite; }

/* Cloison */
.cv-el.s-wall {
    border-radius:4px;
    background:
        repeating-linear-gradient(0deg, #8a9ab0 0px, #8a9ab0 8px, #7a8898 8px, #7a8898 16px);
    border:2px solid rgba(0,0,0,.2);
    box-shadow:0 3px 8px rgba(0,0,0,.2),inset 0 1px 0 rgba(255,255,255,.15);
    opacity:.85;
}

/* ════════════════════════════════════════
   LABEL dans l'élément
════════════════════════════════════════ */
.el-n {
    font-size:.63rem;font-weight:800;
    color:rgba(50,20,0,.8);
    text-align:center;pointer-events:none;
    text-shadow:0 1px 2px rgba(255,255,255,.6);
    max-width:88%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;line-height:1;
}
.el-p {
    font-size:.54rem;color:rgba(50,20,0,.55);
    pointer-events:none;line-height:1;margin-top:2px;
    text-shadow:0 1px 1px rgba(255,255,255,.5);
}

/* ════════════════════════════════════════
   GHOST DRAG PALETTE
════════════════════════════════════════ */
#pal-ghost {
    position:fixed;pointer-events:none;z-index:99999;
    display:none;align-items:center;justify-content:center;
    border:2px dashed #10b981;background:rgba(16,185,129,.12);
    border-radius:8px;font-size:.68rem;color:#10b981;font-weight:700;
}

/* ════════════════════════════════════════
   PANNEAU PROPRIÉTÉS
════════════════════════════════════════ */
.rp-props {
    width:198px;flex-shrink:0;background:#fff;
    border-left:1px solid #e2e8f0;padding:13px 12px;
    overflow-y:auto;display:flex;flex-direction:column;gap:0;
}
.pp-head {
    font-size:.63rem;text-transform:uppercase;letter-spacing:.08em;
    color:#94a3b8;font-weight:700;
    border-bottom:1px solid #f1f5f9;padding-bottom:7px;margin-bottom:10px;
}
.pp-none {
    text-align:center;color:#cbd5e1;font-size:.76rem;
    padding:24px 0;line-height:1.9;
}
.pp-none em { font-size:1.6rem;display:block;margin-bottom:8px;opacity:.4;font-style:normal; }
.pp-form { display:flex;flex-direction:column;gap:8px; }
.pp-f { display:flex;flex-direction:column;gap:3px; }
.pp-f label { font-size:.64rem;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.04em; }
.pp-f input,.pp-f select {
    padding:6px 9px;border:1px solid #e2e8f0;border-radius:7px;
    font-size:.8rem;color:#1e293b;width:100%;background:#f8fafc;font-family:inherit;
    transition:border-color .14s;
}
.pp-f input:focus,.pp-f select:focus { outline:none;border-color:#10b981;background:#fff; }
.pp-color { display:flex;align-items:center;gap:6px; }
.pp-color input[type=color] { width:34px;height:27px;padding:2px;border-radius:6px;cursor:pointer;border:1px solid #e2e8f0; }
.pp-color span { font-size:.72rem;color:#64748b; }
.pp-line { height:1px;background:#f1f5f9;margin:3px 0; }
.pp-btn {
    width:100%;padding:7px;border-radius:7px;font-size:.75rem;font-weight:600;
    cursor:pointer;border:none;font-family:inherit;
    display:flex;align-items:center;justify-content:center;gap:4px;transition:all .14s;
}
.pp-dup { background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe; }
.pp-dup:hover { background:#dbeafe; }
.pp-del { background:#fff1f2;color:#e11d48;border:1px solid #fecdd3; }
.pp-del:hover { background:#ffe4e6; }

/* ════════════════════════════════════════
   TOAST
════════════════════════════════════════ */
.rp-toast {
    position:fixed;bottom:24px;left:50%;
    transform:translateX(-50%) translateY(16px);
    background:#0f172a;color:#f1f5f9;
    border:1px solid rgba(255,255,255,.1);
    padding:10px 22px;border-radius:10px;
    font-size:.82rem;font-weight:600;
    box-shadow:0 6px 24px rgba(0,0,0,.2);
    opacity:0;pointer-events:none;
    transition:opacity .22s,transform .22s;z-index:9999;
}
.rp-toast.show{opacity:1;transform:translateX(-50%) translateY(0);}
.rp-toast.ok  {border-left:3px solid #10b981;}
.rp-toast.err {border-left:3px solid #e11d48;}

@keyframes spin-anim{to{transform:rotate(360deg);}}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('restaurant.partials.nav-tabs', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="rp">

    
    <div class="rp-bar">
        <div class="rp-brand">
            <div class="rp-brand-icon">🍽️</div>
            <div>
                <h2>Plan du Restaurant</h2>
                <small>Glissez les éléments pour les repositionner</small>
            </div>
        </div>
        <div class="rp-sep"></div>
        <button class="btn btn-light" id="btn-undo" disabled>↩ Annuler</button>
        <button class="btn btn-light" id="btn-redo" disabled>↪ Refaire</button>
        <div class="rp-sep"></div>
        <label class="rp-toggle on" id="lbl-snap"><input type="checkbox" id="chk-snap" checked> Magnétisme</label>
        <label class="rp-toggle on" id="lbl-grid"><input type="checkbox" id="chk-grid" checked> Grille</label>
        <div class="rp-sep"></div>
        <div class="zoom-row">
            <button class="btn btn-light" id="btn-zo" style="padding:5px 9px">−</button>
            <span class="zoom-lbl" id="zoom-lbl">100%</span>
            <button class="btn btn-light" id="btn-zi" style="padding:5px 9px">+</button>
        </div>
        <div class="rp-sep"></div>
        <button class="btn btn-danger" id="btn-clear">🗑 Réinitialiser</button>
        <button class="btn btn-green"  id="btn-save">💾 Sauvegarder</button>
        <div class="rp-stats">
            <div class="rp-stat"><strong id="st-t">0</strong>tables</div>
            <div class="rp-stat"><strong id="st-c">0</strong>chaises</div>
            <div class="rp-stat"><strong id="st-p">0</strong>places</div>
        </div>
    </div>

    <div class="rp-body">

        
        <div class="rp-pal">
            <div class="pal-tip">Double-clic ou<br>glisser sur le plan</div>

            <div class="pal-group">Tables rondes</div>
            <div class="pal-item" data-type="round" data-seats="2" data-w="80"  data-h="80"  data-color="#c9956a" data-name="T">
                <div class="pal-prev"><div style="width:28px;height:28px;border-radius:50%;background:conic-gradient(from 30deg,#d4a06a,#e8c090,#c98850,#d4a06a);box-shadow:0 3px 8px rgba(0,0,0,.2)"></div></div>
                <div><div class="pal-lbl">Ronde 2P</div><div class="pal-sub">2 places</div></div>
            </div>
            <div class="pal-item" data-type="round" data-seats="4" data-w="100" data-h="100" data-color="#c9956a" data-name="T">
                <div class="pal-prev"><div style="width:34px;height:34px;border-radius:50%;background:conic-gradient(from 30deg,#d4a06a,#e8c090,#c98850,#d4a06a);box-shadow:0 3px 8px rgba(0,0,0,.2)"></div></div>
                <div><div class="pal-lbl">Ronde 4P</div><div class="pal-sub">4 places</div></div>
            </div>

            <div class="pal-group">Tables carrées</div>
            <div class="pal-item" data-type="square" data-seats="2" data-w="80"  data-h="80"  data-color="#c9956a" data-name="T">
                <div class="pal-prev"><div style="width:28px;height:28px;border-radius:5px;background:linear-gradient(135deg,#e0b070,#c08040);box-shadow:0 3px 8px rgba(0,0,0,.2)"></div></div>
                <div><div class="pal-lbl">Carrée 2P</div><div class="pal-sub">2 places</div></div>
            </div>
            <div class="pal-item" data-type="square" data-seats="4" data-w="100" data-h="100" data-color="#c9956a" data-name="T">
                <div class="pal-prev"><div style="width:34px;height:34px;border-radius:5px;background:linear-gradient(135deg,#e0b070,#c08040);box-shadow:0 3px 8px rgba(0,0,0,.2)"></div></div>
                <div><div class="pal-lbl">Carrée 4P</div><div class="pal-sub">4 places</div></div>
            </div>

            <div class="pal-group">Tables longues</div>
            <div class="pal-item" data-type="rect" data-seats="6" data-w="160" data-h="80" data-color="#c9956a" data-name="T">
                <div class="pal-prev"><div style="width:36px;height:18px;border-radius:4px;background:repeating-linear-gradient(90deg,#c8904e,#d4a06a 15px,#c08040 30px);box-shadow:0 3px 8px rgba(0,0,0,.2)"></div></div>
                <div><div class="pal-lbl">Rectangle 6P</div><div class="pal-sub">6 places</div></div>
            </div>
            <div class="pal-item" data-type="rect" data-seats="10" data-w="240" data-h="80" data-color="#c9956a" data-name="T">
                <div class="pal-prev"><div style="width:36px;height:13px;border-radius:4px;background:repeating-linear-gradient(90deg,#c8904e,#d4a06a 15px,#c08040 30px);box-shadow:0 3px 8px rgba(0,0,0,.2)"></div></div>
                <div><div class="pal-lbl">Grande 10P</div><div class="pal-sub">10 places</div></div>
            </div>

            <div class="pal-group">Bar</div>
            <div class="pal-item" data-type="bar" data-seats="0" data-w="300" data-h="60" data-color="#3a2010" data-name="Bar">
                <div class="pal-prev"><div style="width:36px;height:12px;border-radius:3px;background:repeating-linear-gradient(90deg,#3a2010,#4a2e18 20px,#3a2010 40px);border:2px solid rgba(255,255,255,.1);box-shadow:0 3px 8px rgba(0,0,0,.35)"></div></div>
                <div><div class="pal-lbl">Comptoir</div><div class="pal-sub">Bar</div></div>
            </div>

            <div class="pal-group">Chaise</div>
            <div class="pal-item" data-type="chair" data-seats="1" data-w="38" data-h="38" data-color="#a07848" data-name="">
                <div class="pal-prev" style="position:relative">
                    <div style="width:24px;height:24px;border-radius:50%;background:radial-gradient(circle at 38% 32%,#d4ae78,#a07848 52%,#7c5630);box-shadow:0 3px 8px rgba(0,0,0,.22)">
                        <div style="position:absolute;top:3px;left:4px;width:16px;height:10px;border:2px solid rgba(70,35,0,.45);border-bottom:none;border-radius:50% 50% 0 0"></div>
                    </div>
                </div>
                <div><div class="pal-lbl">Chaise</div><div class="pal-sub">1 place</div></div>
            </div>

            <div class="pal-group">Décoration</div>
            <div class="pal-item" data-type="plant" data-seats="0" data-w="52" data-h="52" data-color="#4ade80" data-name="">
                <div class="pal-prev"><div style="width:26px;height:26px;border-radius:50%;background:radial-gradient(circle,#bbf7d0,#4ade80 55%,#16a34a);box-shadow:0 3px 8px rgba(22,163,74,.3);display:flex;align-items:center;justify-content:center;font-size:.8rem">🌿</div></div>
                <div><div class="pal-lbl">Plante</div><div class="pal-sub">Déco</div></div>
            </div>
            <div class="pal-item" data-type="wall" data-seats="0" data-w="200" data-h="28" data-color="#7a8898" data-name="Mur">
                <div class="pal-prev"><div style="width:36px;height:10px;border-radius:2px;background:repeating-linear-gradient(0deg,#8a9ab0,#8a9ab0 4px,#7a8898 4px,#7a8898 8px);box-shadow:0 2px 5px rgba(0,0,0,.2)"></div></div>
                <div><div class="pal-lbl">Cloison</div><div class="pal-sub">Mur</div></div>
            </div>
        </div>

        
        <div class="rp-wrap"><div id="rp-cv"></div></div>

        
        <div class="rp-props">
            <div class="pp-head">Propriétés</div>
            <div class="pp-none" id="pp-none"><em>🖱️</em>Cliquez sur un<br>élément pour<br>le modifier</div>
            <div class="pp-form" id="pp-form" style="display:none">
                <div class="pp-f"><label>Nom / Numéro</label><input type="text" id="pp-name" maxlength="20"></div>
                <div class="pp-f"><label>Type</label>
                    <select id="pp-type">
                        <option value="round">Table ronde</option>
                        <option value="square">Table carrée</option>
                        <option value="rect">Table rectangle</option>
                        <option value="bar">Bar / Comptoir</option>
                        <option value="chair">Chaise</option>
                        <option value="plant">Plante</option>
                        <option value="wall">Cloison</option>
                    </select>
                </div>
                <div class="pp-f"><label>Nb de places</label><input type="number" id="pp-seats" min="0" max="99"></div>
                <div class="pp-f"><label>Rotation (°)</label><input type="number" id="pp-rot" min="-180" max="180" step="15"></div>
                <div class="pp-f"><label>Couleur</label>
                    <div class="pp-color"><input type="color" id="pp-col"><span id="pp-col-hex">#c9956a</span></div>
                </div>
                <div class="pp-line"></div>
                <button class="pp-btn pp-dup" id="pp-dup">⧉ Dupliquer</button>
                <button class="pp-btn pp-del" id="pp-del">✕ Supprimer</button>
            </div>
        </div>

    </div>
</div>

<div id="pal-ghost"></div>
<div class="rp-toast" id="rp-toast"></div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
'use strict';

const CW=1140,CH=680,GRID=40;
const SAVE_URL="<?php echo e(route('restaurant.layout.save')); ?>";
const CSRF=document.querySelector('meta[name="csrf-token"]').content;

let els=[],nextId=1,sel=null;
let hist=[],fut=[];
let zoom=1,snap=true,counters={};

const cv      = document.getElementById('rp-cv');
const ppNone  = document.getElementById('pp-none');
const ppForm  = document.getElementById('pp-form');
const ppName  = document.getElementById('pp-name');
const ppType  = document.getElementById('pp-type');
const ppSeats = document.getElementById('pp-seats');
const ppRot   = document.getElementById('pp-rot');
const ppCol   = document.getElementById('pp-col');
const ppColHex= document.getElementById('pp-col-hex');
const toast   = document.getElementById('rp-toast');
const ghost   = document.getElementById('pal-ghost');
const btnUndo = document.getElementById('btn-undo');
const btnRedo = document.getElementById('btn-redo');

const SHAPE={round:'s-round',square:'s-square',rect:'s-rect',bar:'s-bar',chair:'s-chair',plant:'s-plant',wall:'s-wall'};

/* ── CHARGEMENT ── */
(function(){
    const raw=<?php echo json_encode($tables, 15, 512) ?>;
    if(!raw||!raw.length) return;
    raw.forEach(t=>{
        els.push({
            id:nextId++, name:t.name||'', type:t.type||'round',
            seats:parseInt(t.seats)||0,
            x:parseFloat(t.x)/100*CW, y:parseFloat(t.y)/100*CH,
            w:parseFloat(t.w)/100*CW, h:parseFloat(t.h)/100*CH,
            rotation:parseInt(t.rotation)||0, color:t.color||'#c9956a',
        });
    });
    renderAll(false); // false = pas d'animation au chargement initial
})();

/* ── RENDU ── */
function renderAll(animate=false){
    cv.innerHTML='';
    els.forEach((el,i)=>buildEl(el, animate, i*30));
    updateStats(); syncUndo();
}

function buildEl(el, animate=false, delay=0){
    const d=document.createElement('div');
    d.className='cv-el '+(SHAPE[el.type]||'s-square');
    d.dataset.id=el.id;
    // On applique la rotation via style inline pour qu'elle soit conservée
    // L'animation drop-in override transform temporairement
    d.style.cssText=`left:${el.x}px;top:${el.y}px;width:${el.w}px;height:${el.h}px;`+
                    `background:${needsCustomBg(el)?'':el.color};`+
                    `transform:rotate(${el.rotation}deg);`;
    if(el.id===sel) d.classList.add('sel');

    // Contenu textuel
    if(el.type==='plant'){
        const s=Math.min(el.w,el.h)*.48;
        d.innerHTML=`<span style="font-size:${s}px;pointer-events:none;z-index:1">🌿</span>`;
    } else if(el.type!=='wall'&&el.type!=='chair'){
        if(el.name){ const n=document.createElement('div');n.className='el-n';n.textContent=el.name;d.appendChild(n); }
        if(el.seats>0){ const p=document.createElement('div');p.className='el-p';p.textContent=el.seats+'P';d.appendChild(p); }
    }

    // Animation d'apparition
    if(animate){
        d.style.animationDelay = delay+'ms';
        d.classList.add('anim-in');
        setTimeout(()=>d.classList.remove('anim-in'), delay+500);
    }

    d.addEventListener('mousedown',e=>{ e.stopPropagation(); selectEl(el.id); startDrag(e,d,el); });
    cv.appendChild(d);
    return d;
}

// Les types avec fond CSS défini en stylesheet (pas besoin de surcharger)
function needsCustomBg(el){
    return ['round','square','rect','bar','chair','plant','wall'].includes(el.type);
}

function refreshEl(el){
    const d=cv.querySelector(`[data-id="${el.id}"]`);
    if(!d) return;
    d.className='cv-el '+(SHAPE[el.type]||'s-square')+(el.id===sel?' sel':'');
    d.style.transform=`rotate(${el.rotation}deg)`;
    if(!needsCustomBg(el)) d.style.background=el.color;
    d.querySelectorAll('.el-n,.el-p,span').forEach(n=>n.remove());
    if(el.type==='plant'){
        const s=Math.min(el.w,el.h)*.48;
        d.innerHTML=`<span style="font-size:${s}px;pointer-events:none;z-index:1">🌿</span>`;
    } else if(el.type!=='wall'&&el.type!=='chair'){
        if(el.name){const n=document.createElement('div');n.className='el-n';n.textContent=el.name;d.appendChild(n);}
        if(el.seats>0){const p=document.createElement('div');p.className='el-p';p.textContent=el.seats+'P';d.appendChild(p);}
    }
}

/* ── SÉLECTION ── */
function selectEl(id){
    sel=id;
    cv.querySelectorAll('.cv-el').forEach(d=>d.classList.toggle('sel',+d.dataset.id===id));
    const el=getEl(id); if(!el) return;
    ppNone.style.display='none'; ppForm.style.display='flex';
    ppName.value=el.name; ppType.value=el.type;
    ppSeats.value=el.seats; ppRot.value=el.rotation;
    ppCol.value=el.color; ppColHex.textContent=el.color;
}
function deselect(){
    sel=null;
    cv.querySelectorAll('.cv-el').forEach(d=>d.classList.remove('sel'));
    ppNone.style.display=''; ppForm.style.display='none';
}
cv.addEventListener('mousedown',e=>{ if(e.target===cv) deselect(); });

/* ── DRAG CANVAS ── */
let dEl=null,dOx=0,dOy=0,dMoved=false;
function startDrag(e,div,el){
    if(e.button!==0) return;
    dEl=el; dMoved=false;
    const r=cv.getBoundingClientRect();
    dOx=(e.clientX-r.left)/zoom-el.x;
    dOy=(e.clientY-r.top)/zoom-el.y;
    div.classList.add('drag'); e.preventDefault();
}
document.addEventListener('mousemove',e=>{
    if(!dEl) return;
    const r=cv.getBoundingClientRect();
    let nx=(e.clientX-r.left)/zoom-dOx, ny=(e.clientY-r.top)/zoom-dOy;
    if(snap){nx=Math.round(nx/GRID)*GRID;ny=Math.round(ny/GRID)*GRID;}
    nx=Math.max(0,Math.min(nx,CW-dEl.w));
    ny=Math.max(0,Math.min(ny,CH-dEl.h));
    dEl.x=nx; dEl.y=ny;
    const d=cv.querySelector(`[data-id="${dEl.id}"]`);
    if(d){d.style.left=nx+'px';d.style.top=ny+'px';}
    dMoved=true;
});
document.addEventListener('mouseup',()=>{
    if(!dEl) return;
    const d=cv.querySelector(`[data-id="${dEl.id}"]`);
    if(d) d.classList.remove('drag');
    if(dMoved) pushHist();
    dEl=null; dMoved=false;
});

/* ── DRAG PALETTE ── */
let palDrag=false,palData=null,palGW=0,palGH=0;
document.querySelectorAll('.pal-item').forEach(item=>{
    item.addEventListener('dblclick',()=>{
        const d=getPD(item); pushHist();
        const el=makeEl(d,CW/2-d.w/2,CH/2-d.h/2);
        els.push(el); buildEl(el,true); selectEl(el.id); updateStats();
    });
    item.addEventListener('mousedown',e=>{
        if(e.button!==0) return;
        palDrag=true; palData=getPD(item);
        palGW=Math.min(palData.w,70); palGH=Math.min(palData.h,50);
        ghost.style.cssText=`width:${palGW}px;height:${palGH}px;display:flex;`;
        mg(e); e.preventDefault();
    });
});
function mg(e){ghost.style.left=(e.clientX-palGW/2)+'px';ghost.style.top=(e.clientY-palGH/2)+'px';}
document.addEventListener('mousemove',e=>{if(palDrag)mg(e);});
document.addEventListener('mouseup',e=>{
    if(!palDrag) return;
    ghost.style.display='none'; palDrag=false;
    const r=cv.getBoundingClientRect();
    if(e.clientX>=r.left&&e.clientX<=r.right&&e.clientY>=r.top&&e.clientY<=r.bottom){
        let nx=(e.clientX-r.left)/zoom-palData.w/2, ny=(e.clientY-r.top)/zoom-palData.h/2;
        if(snap){nx=Math.round(nx/GRID)*GRID;ny=Math.round(ny/GRID)*GRID;}
        nx=Math.max(0,Math.min(nx,CW-palData.w));ny=Math.max(0,Math.min(ny,CH-palData.h));
        pushHist();
        const el=makeEl(palData,nx,ny);
        els.push(el); buildEl(el,true); selectEl(el.id); updateStats();
    }
    palData=null;
});
function getPD(item){return{type:item.dataset.type,seats:+item.dataset.seats,w:+item.dataset.w,h:+item.dataset.h,color:item.dataset.color,name:item.dataset.name};}
function makeEl(d,x,y){
    const c=(counters[d.type]=(counters[d.type]||0)+1);
    const name=d.name==='T'?'T'+c:d.name==='C'?'C'+c:d.name;
    return{id:nextId++,name,type:d.type,seats:d.seats,x,y,w:d.w,h:d.h,rotation:0,color:d.color};
}

/* ── PROPS ── */
function applyProps(){
    if(sel===null) return;
    const el=getEl(sel); if(!el) return;
    el.name=ppName.value; el.type=ppType.value;
    el.seats=parseInt(ppSeats.value)||0;
    el.rotation=parseInt(ppRot.value)||0;
    el.color=ppCol.value;
    refreshEl(el); updateStats();
}
[ppName,ppType,ppSeats,ppRot].forEach(i=>i.addEventListener('input',applyProps));
ppCol.addEventListener('input',()=>{ppColHex.textContent=ppCol.value;applyProps();});
document.getElementById('pp-dup').addEventListener('click',()=>{
    if(sel===null) return;
    const s=getEl(sel); if(!s) return; pushHist();
    const c={...s,id:nextId++,x:Math.min(s.x+GRID,CW-s.w),y:Math.min(s.y+GRID,CH-s.h)};
    els.push(c); buildEl(c,true); selectEl(c.id); updateStats();
});
document.getElementById('pp-del').addEventListener('click',delSel);

/* ── UNDO/REDO ── */
function pushHist(){hist.push(JSON.stringify(els.map(e=>({...e}))));if(hist.length>80)hist.shift();fut=[];syncUndo();}
function undo(){if(!hist.length)return;fut.push(JSON.stringify(els.map(e=>({...e}))));els=JSON.parse(hist.pop());els.forEach(e=>{if(e.id>=nextId)nextId=e.id+1;});deselect();renderAll(false);}
function redo(){if(!fut.length)return;hist.push(JSON.stringify(els.map(e=>({...e}))));els=JSON.parse(fut.pop());deselect();renderAll(false);}
function syncUndo(){btnUndo.disabled=!hist.length;btnRedo.disabled=!fut.length;}
btnUndo.addEventListener('click',undo);
btnRedo.addEventListener('click',redo);

/* ── CLAVIER ── */
document.addEventListener('keydown',e=>{
    const tag=e.target.tagName;
    if(['INPUT','SELECT','TEXTAREA'].includes(tag)) return;
    if((e.ctrlKey||e.metaKey)&&!e.shiftKey&&e.key==='z'){e.preventDefault();undo();return;}
    if((e.ctrlKey||e.metaKey)&&(e.key==='y'||(e.shiftKey&&e.key==='z'))){e.preventDefault();redo();return;}
    if((e.key==='Delete'||e.key==='Backspace')&&sel!==null){e.preventDefault();delSel();return;}
    if(sel!==null){
        const el=getEl(sel);if(!el)return;
        const st=snap?GRID:4;let mv=false;
        if(e.key==='ArrowLeft'){el.x=Math.max(0,el.x-st);mv=true;}
        if(e.key==='ArrowRight'){el.x=Math.min(CW-el.w,el.x+st);mv=true;}
        if(e.key==='ArrowUp'){el.y=Math.max(0,el.y-st);mv=true;}
        if(e.key==='ArrowDown'){el.y=Math.min(CH-el.h,el.y+st);mv=true;}
        if(mv){e.preventDefault();const d=cv.querySelector(`[data-id="${el.id}"]`);if(d){d.style.left=el.x+'px';d.style.top=el.y+'px';}}
    }
});
function delSel(){if(sel===null)return;pushHist();els=els.filter(e=>e.id!==sel);deselect();renderAll(false);}

/* ── CLEAR ── */
document.getElementById('btn-clear').addEventListener('click',()=>{
    if(!confirm('Réinitialiser le plan ?\nLe plan par défaut sera rechargé après sauvegarde.'))return;
    pushHist();els=[];counters={};deselect();renderAll(false);
    showToast('Plan vidé — sauvegardez pour confirmer',false);
});

/* ── TOGGLES ── */
document.getElementById('chk-snap').addEventListener('change',e=>{snap=e.target.checked;document.getElementById('lbl-snap').classList.toggle('on',snap);});
document.getElementById('chk-grid').addEventListener('change',e=>{cv.classList.toggle('no-grid',!e.target.checked);document.getElementById('lbl-grid').classList.toggle('on',e.target.checked);});

/* ── ZOOM ── */
function setZoom(z){
    zoom=Math.max(.3,Math.min(2,z));
    cv.style.transform=`scale(${zoom})`;
    cv.style.marginRight=(zoom-1)*CW+'px';
    cv.style.marginBottom=(zoom-1)*CH+'px';
    document.getElementById('zoom-lbl').textContent=Math.round(zoom*100)+'%';
}
document.getElementById('btn-zi').addEventListener('click',()=>setZoom(zoom+.1));
document.getElementById('btn-zo').addEventListener('click',()=>setZoom(zoom-.1));

/* ── STATS ── */
function updateStats(){
    const t=els.filter(e=>['round','square','rect','bar'].includes(e.type));
    const c=els.filter(e=>e.type==='chair');
    document.getElementById('st-t').textContent=t.length;
    document.getElementById('st-c').textContent=c.length;
    document.getElementById('st-p').textContent=t.reduce((s,e)=>s+(e.seats||0),0);
}

/* ── SAVE ── */
document.getElementById('btn-save').addEventListener('click',save);
async function save(){
    const btn=document.getElementById('btn-save');
    btn.disabled=true;
    btn.innerHTML='<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="animation:spin-anim 1s linear infinite"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg> Sauvegarde…';
    const payload=els.map((el,i)=>({
        name:el.name,type:el.type,seats:el.seats||0,
        x:+(el.x/CW*100).toFixed(4),y:+(el.y/CH*100).toFixed(4),
        w:+(el.w/CW*100).toFixed(4),h:+(el.h/CH*100).toFixed(4),
        rotation:el.rotation||0,color:el.color,z_order:i+1,
    }));
    try{
        const r=await fetch(SAVE_URL,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},body:JSON.stringify({tables:payload})});
        const d=await r.json();
        showToast(d.success?`✓ Sauvegardé — ${d.count} éléments`:'✗ Erreur serveur',d.success);
    }catch(err){showToast('✗ Erreur réseau',false);}
    finally{btn.disabled=false;btn.textContent='💾 Sauvegarder';}
}

/* ── TOAST ── */
let toastT;
function showToast(msg,ok){
    toast.textContent=msg;toast.className='rp-toast show '+(ok?'ok':'err');
    clearTimeout(toastT);toastT=setTimeout(()=>toast.classList.remove('show'),3400);
}
function getEl(id){return els.find(e=>e.id===id)||null;}

})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\HotelManagement\resources\views/restaurant/layout.blade.php ENDPATH**/ ?>