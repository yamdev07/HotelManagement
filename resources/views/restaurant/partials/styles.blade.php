<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    --g50:  #f0faf0;
    --g100: #d4edda;
    --g200: #a8d5b5;
    --g300: #72bb82;
    --g400: #4a9e5c;
    --g500: #2e8540;
    --g600: #1e6b2e;
    --g700: #155221;
    --g800: #0d3a16;
    --g900: #072210;
    --white:    #ffffff;
    --surface:  #f7f9f7;
    --surface2: #eef3ee;
    --s50:  #f8f9f8;
    --s100: #eff0ef;
    --s200: #dde0dd;
    --s300: #c2c7c2;
    --s400: #9ba09b;
    --s500: #737873;
    --s600: #545954;
    --s700: #3a3e3a;
    --s800: #252825;
    --s900: #131513;
    --shadow-xs: 0 1px 2px rgba(0,0,0,.04);
    --shadow-sm: 0 1px 6px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow-md: 0 4px 16px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.04);
    --shadow-lg: 0 12px 40px rgba(0,0,0,.10), 0 4px 12px rgba(0,0,0,.05);
    --r:   8px;
    --rl:  14px;
    --rxl: 20px;
    --transition: all .2s cubic-bezier(.4,0,.2,1);
    --font: 'DM Sans', system-ui, sans-serif;
    --mono: 'DM Mono', monospace;
}

.db-page {
    padding: 24px 30px;
    background: var(--surface);
    min-height: 100vh;
    font-family: var(--font);
    color: var(--s800);
}

.db-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1.5px solid var(--s100);
}

.db-title-h1 {
    font-size: 1.5rem; font-weight: 700;
    color: var(--s900); letter-spacing: -.5px;
    margin: 0;
}
@media (max-width: 576px) {
    .db-page { padding: 15px 15px; }
    .db-header { flex-direction: column; align-items: flex-start; gap: 12px; }
    .db-title-h1 { font-size: 1.2rem; }
}

.db-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100);
    padding: 24px; margin-bottom: 24px;
    box-shadow: var(--shadow-sm);
}

/* Base Table Style */
.db-table {
    width: 100%; border-collapse: separate; border-spacing: 0;
}
.db-table thead th {
    background: var(--s50); color: var(--s500);
    font-size: .75rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .5px; padding: 14px 16px;
    border-bottom: 1.5px solid var(--s100);
}
.db-table tbody td {
    padding: 16px; border-bottom: 1px solid var(--s100);
    font-size: .88rem; vertical-align: middle;
}
.db-table tbody tr:hover { background: var(--s50); }

/* KPI Cards */
.kpi-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 20px; margin-bottom: 30px;
}
.kpi-card {
    background: var(--white); border-radius: var(--rl);
    padding: 20px; border: 1.5px solid var(--s100);
    box-shadow: var(--shadow-sm); transition: var(--transition);
    display: flex; align-items: center; gap: 16px;
}
.kpi-card:hover { transform: translateY(-3px); border-color: var(--g200); box-shadow: var(--shadow-md); }

.kpi-icon {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem; flex-shrink: 0;
}

.kpi-data { flex: 1; }
.kpi-label { font-size: .75rem; color: var(--s500); font-weight: 600; margin-bottom: 4px; }
.kpi-value { font-size: 1.25rem; font-weight: 800; color: var(--s900); font-family: var(--mono); }

/* Generic Overrides */
.btn { border-radius: var(--r); font-weight: 600; transition: var(--transition); }
.form-control, .form-select {
    border-radius: var(--r); border: 1.5px solid var(--s200);
    padding: 10px 14px; font-size: .9rem; background: var(--white);
}
.form-control:focus, .form-select:focus {
    border-color: var(--g400); box-shadow: 0 0 0 4px rgba(46,133,64,.08);
}

.btn-db-primary {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px; background: var(--g600);
    color: white; border-radius: var(--r);
    font-weight: 600; text-decoration: none;
    transition: var(--transition); border: none;
    box-shadow: 0 4px 12px rgba(46,133,64,.2);
}
.btn-db-primary:hover {
    background: var(--g700); color: white;
    transform: translateY(-2px); box-shadow: 0 6px 18px rgba(46,133,64,.3);
    text-decoration: none;
}

.db-input {
    height: 42px; padding: 0 16px;
    border: 1.5px solid var(--s200); border-radius: 10px;
    font-size: .9rem; background: var(--surface);
    transition: var(--transition); outline: none;
}
.db-input:focus {
    border-color: var(--g400); background: var(--white);
    box-shadow: 0 0 0 4px rgba(46,133,64,.08);
}

.filter-row {
    display: flex; gap: 15px; align-items: center; justify-content: space-between;
    margin-bottom: 24px;
}

.filter-group {
    display: flex; gap: 12px; flex: 1;
}

.filter-group .db-input {
    width: 200px;
}

.search-box-wrap {
    position: relative; flex: 1;
}

.search-box-icon {
    position: absolute; left: 14px; top: 13px; color: var(--s400); font-size: 0.9rem;
}

@media (max-width: 768px) {
    .filter-row { flex-direction: column; align-items: flex-start; }
    .filter-group { flex-direction: column; width: 100%; }
    .filter-group .db-input { width: 100%; }
    .btn-cart-pill { width: auto; padding: 6px 14px; font-size: 0.85rem; }
}

.btn-cart-pill {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 8px 18px; background: #fff3cd;
    color: #856404; border: 1.5px solid #ffe69c;
    border-radius: 100px; font-weight: 700;
    position: relative; transition: var(--transition);
}
.btn-cart-pill:hover { background: #ffe69c; transform: translateY(-1px); }

/* Menu Card Component (Shared between Admin & Vitrine) */
.db-item-card {
    background: var(--white); border-radius: var(--rl);
    border: 1.5px solid var(--s100);
    overflow: hidden; transition: var(--transition);
    display: flex; flex-direction: column;
    box-shadow: var(--shadow-sm); height: 100%;
}
.db-item-card:hover {
    transform: translateY(-5px); border-color: var(--g200);
    box-shadow: var(--shadow-md);
}

.db-item-img {
    position: relative; height: 180px; overflow: hidden; background: var(--s50);
}
.db-item-img img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .5s ease;
}
.db-item-card:hover .db-item-img img { transform: scale(1.08); }

.db-price-tag {
    position: absolute; top: 12px; right: 12px;
    background: rgba(13, 58, 22, 0.85); backdrop-filter: blur(4px);
    color: white; padding: 5px 12px; border-radius: 8px;
    font-family: var(--mono); font-size: .85rem; font-weight: 700;
    z-index: 2;
}

.db-item-content {
    padding: 18px; flex: 1; display: flex; flex-direction: column;
}

.db-qty-pill {
    display: flex; align-items: center; justify-content: space-between;
    background: var(--surface2); padding: 4px; gap: 8px;
    border-radius: 12px; border: 1.5px solid var(--s200);
    width: 100%;
}
.db-qty-btn {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    background: var(--white); border: 1px solid var(--s200);
    color: var(--s700); cursor: pointer; transition: var(--transition);
}
.db-qty-btn:hover { background: var(--g600); color: white; border-color: var(--g600); }
.db-qty-val { font-weight: 800; color: var(--g700); font-size: 1.1rem; flex: 1; text-align: center; }

/* Animations */
.anim-1 { animation: fadeIn 0.4s ease-out; }
.anim-2 { animation: fadeIn 0.4s ease-out 0.1s both; }
.anim-3 { animation: fadeIn 0.4s ease-out 0.2s both; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }

/* Fix generic tags */
.badge { border-radius: 6px; font-weight: 600; padding: 5px 10px; }
.table > :not(caption) > * > * { border-bottom-width: 1px; border-color: var(--s100); }
</style>
