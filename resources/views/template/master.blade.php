@php
    $activeSession = null;
    if (auth()->check()) {
        $activeSession = \App\Models\CashierSession::where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();
    }
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ ($currentHotel ?? null)?->logoUrl() ?? asset('favicon.svg') }}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Toastr -->
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <!-- DataTables Bootstrap 5 -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    @stack('styles')
    <title>@yield('title') - {{ $currentHotel->name ?? 'Hotel Admin' }}</title>
    @yield('head')

    {{-- White-label : couleurs de l'hôtel courant --}}
    @include('partials.hotel-branding')

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, sans-serif;
            background: #f5f7fb;
        }

        /*
        ═══════════════════════════════════════════════════
          SEULE RÈGLE QUI COMPTE :
          La sidebar (#sidebar) est en position:fixed dans _sidebar.blade.php
          Elle fait 272px quand ouverte, 64px quand .collapsed
          Le contenu doit juste avoir le bon margin-left
        ═══════════════════════════════════════════════════
        */

        /* Desktop · sidebar ouverte (272px) */
        #page-content-wrapper {
            margin-left: 272px;
            width: auto;          /* NE PAS mettre 100% : block auto = viewport - margin-left */
            min-height: 100vh;
            background: #f5f7fb;
            transition: margin-left 0.3s cubic-bezier(.4,0,.2,1);
            overflow-x: hidden;   /* Sécurité pour les tableaux très larges */
            box-sizing: border-box;
        }

        /* Desktop · sidebar collapsed (64px) */
        body.sidebar-is-collapsed #page-content-wrapper {
            margin-left: 64px;
        }

        /* Mobile ≤768px · sidebar en overlay, contenu pleine largeur */
        @media (max-width: 768px) {
            #page-content-wrapper {
                margin-left: 0 !important;
                padding-top: 56px; /* hauteur du mobile header */
            }
        }

        #page-content-wrapper > .p-3 {
            padding: 24px;
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: var(--g500); border-radius: 10px; }
        .table-responsive { overflow-x: auto; }

        /* ═══════════════════════════════════════════════════
   DARK MODE — JS resolves "system" → "dark" or "light"
   ═══════════════════════════════════════════════════ */

        /* ── CSS Variables ── */
        html[data-theme="dark"] {
            color-scheme: dark;
            /* Palette sombre COHÉRENTE avec le tableau de bord (dark vert-nuit).
               On bascule TOUTES les échelles neutres utilisées par les vues
               (--surface, --white, --s*, --gray-*) : surfaces sombres, texte clair. */
            --surface:  #0f1311;
            --surface2: #1b211d;
            --white:    #161b18;
            --s50:  #1b211d;
            --s100: #222a25;
            --s200: #2b332d;
            --s300: #3a433c;
            --s400: #8a938c;
            --s500: #9aa39c;
            --s600: #b3bbb5;
            --s700: #cfd5d0;
            --s800: #e4e9e5;
            --s900: #eef2ee;
            --gray-50:  #161b18;
            --gray-100: #1b211d;
            --gray-200: #2b332d;
            --gray-300: #3a433c;
            --gray-400: #8a938c;
            --gray-500: #9aa39c;
            --gray-600: #b3bbb5;
            --gray-700: #cfd5d0;
            --gray-800: #e4e9e5;
            --gray-900: #eef2ee;
            /* Échelle « slate » (--slate-*) neutre utilisée par certaines pages : bascule complète. */
            --slate-50:  #1b211d;
            --slate-100: #222a25;
            --slate-200: #2b332d;
            --slate-300: #3a433c;
            --slate-400: #8a938c;
            --slate-500: #9aa39c;
            --slate-600: #b3bbb5;
            --slate-700: #cfd5d0;
            --slate-800: #e4e9e5;
            --slate-900: #eef2ee;
            /* Échelle « green » (--green-*) : les teintes claires (50-300) donnaient des
               ronds/badges BLANCS en sombre -> on les rend sombres. Les verts moyens/foncés
               (400-900) restent (accents, en-têtes) — lisibles sur fond sombre. */
            --green-50:  #14181c;
            --green-100: #191f26;
            --green-200: #202834;
            --green-300: #24303a;
            --shadow-xs: 0 1px 2px rgba(0,0,0,.3);
            --shadow-sm: 0 1px 6px rgba(0,0,0,.4);
            --shadow-md: 0 4px 16px rgba(0,0,0,.5);
        }
        html[data-theme="dark"] body { background: #0f1311; color: #e4e9e5; }
        html[data-theme="dark"] #page-content-wrapper { background: #0f1311; }
        html[data-theme="dark"] #page-content-wrapper > .p-3 { color: #e4e9e5; }

        /* Fond d'établissement (préférence locale) : voile pour garder la lisibilité */
        html.has-app-bg #page-content-wrapper { background: rgba(248,250,249,.82) !important; backdrop-filter: blur(8px); }
        html.has-app-bg[data-theme="dark"] #page-content-wrapper { background: rgba(15,19,17,.84) !important; }

        /* ── Page-specific backgrounds ── */
        html[data-theme="dark"] .act-page { background: #0f172a; }
        html[data-theme="dark"] .notifications-page { background: #0f172a; }

        /* ── All surfaces ── */
        html[data-theme="dark"] .modal-content,
        html[data-theme="dark"] .modal-header,
        html[data-theme="dark"] .modal-footer,
        html[data-theme="dark"] .swal2-popup,
        html[data-theme="dark"] .dropdown-menu,
        html[data-theme="dark"] .card,
        html[data-theme="dark"] .bg-white,
        html[data-theme="dark"] .table-container,
        html[data-theme="dark"] .filter-section,
        html[data-theme="dark"] .stat-card,
        html[data-theme="dark"] .details-collapse,
        html[data-theme="dark"] .details-pre,
        html[data-theme="dark"] .notification-card,
        html[data-theme="dark"] .modal pre,
        html[data-theme="dark"] pre,
        html[data-theme="dark"] .form-control,
        html[data-theme="dark"] .form-select,
        html[data-theme="dark"] .per-page-select,
        html[data-theme="dark"] .btn-gray,
        html[data-theme="dark"] .btn-icon,
        html[data-theme="dark"] .filter-badge,
        html[data-theme="dark"] .pagination-modern .page-link,
        html[data-theme="dark"] .alert-green,
        html[data-theme="dark"] .empty-state,
        html[data-theme="dark"] .table thead th,
        html[data-theme="dark"] .table tbody td {
            background-color: #1e293b !important;
            color: #e2e8f0 !important;
            border-color: #334155 !important;
        }

        /* ── Text ── */
        html[data-theme="dark"] .text-muted,
        html[data-theme="dark"] .text-secondary,
        html[data-theme="dark"] .stat-label,
        html[data-theme="dark"] .stat-value,
        html[data-theme="dark"] .stat-change,
        html[data-theme="dark"] .form-label,
        html[data-theme="dark"] label,
        html[data-theme="dark"] .breadcrumb,
        html[data-theme="dark"] .header-subtitle,
        html[data-theme="dark"] h1, html[data-theme="dark"] h2,
        html[data-theme="dark"] h3, html[data-theme="dark"] h5,
        html[data-theme="dark"] h6, html[data-theme="dark"] p,
        html[data-theme="dark"] span, html[data-theme="dark"] div {
            color: #e2e8f0;
        }
        html[data-theme="dark"] .breadcrumb a { color: #94a3b8; }
        html[data-theme="dark"] .breadcrumb a:hover { color: var(--g500); }
        html[data-theme="dark"] .breadcrumb .current { color: #cbd5e1; }

        /* ── Table ── */
        html[data-theme="dark"] .table tbody tr:hover td { background: #334155 !important; }
        html[data-theme="dark"] .table thead th { background: #0f172a !important; color: #94a3b8 !important; }
        html[data-theme="dark"] .table > :not(caption) > * > * { border-bottom-color: #334155 !important; }

        /* ── Pagination ── */
        html[data-theme="dark"] .pagination-modern .page-link { background: #1e293b; color: #94a3b8; border-color: #334155; }
        html[data-theme="dark"] .pagination-modern .page-link:hover { background: #334155; color: #e2e8f0; }
        html[data-theme="dark"] .pagination-modern .active .page-link { background: var(--g500); border-color: var(--g500); color: #fff; }

        /* ── Scrollbar ── */
        html[data-theme="dark"] ::-webkit-scrollbar { background: #1e293b; }
        html[data-theme="dark"] ::-webkit-scrollbar-thumb { background: #475569; }

        /* ── SweetAlert2 ── */
        html[data-theme="dark"] .swal2-popup { background: #1e293b; color: #e2e8f0; }
        html[data-theme="dark"] .swal2-title { color: #e2e8f0 !important; }
        html[data-theme="dark"] .swal2-html-container { color: #94a3b8 !important; }

        /* ── Dropdowns ── */
        html[data-theme="dark"] .dropdown-item { color: #e2e8f0; }
        html[data-theme="dark"] .dropdown-item:hover,
        html[data-theme="dark"] .dropdown-item:focus { background: #334155; color: #fff; }
        html[data-theme="dark"] .dropdown-item i { color: var(--g500); }

        /* ── Forms ── */
        html[data-theme="dark"] .form-check-input { background-color: #334155; border-color: #475569; }
        html[data-theme="dark"] .form-check-input:checked { background-color: var(--g500); border-color: var(--g500); }

        /* ── Alerts ── */
        html[data-theme="dark"] .alert-success { background-color: rgb(from var(--g500) r g b / .15) !important; color: var(--g300) !important; border-color: rgb(from var(--g500) r g b / .3) !important; }
        html[data-theme="dark"] .alert-danger { background-color: rgba(239,68,68,.15) !important; color: #fca5a5 !important; border-color: rgba(239,68,68,.3) !important; }
        html[data-theme="dark"] .alert-warning { background-color: rgba(245,158,11,.15) !important; color: #fcd34d !important; border-color: rgba(245,158,11,.3) !important; }
        html[data-theme="dark"] .alert-info { background-color: rgba(59,130,246,.15) !important; color: #93c5fd !important; border-color: rgba(59,130,246,.3) !important; }

        /* ── Misc ── */
        html[data-theme="dark"] .bg-light,
        html[data-theme="dark"] .bg-light-subtle { background-color: #1e293b !important; color: #e2e8f0 !important; }
        html[data-theme="dark"] .border,
        html[data-theme="dark"] .border-bottom,
        html[data-theme="dark"] .border-top,
        html[data-theme="dark"] .border-start,
        html[data-theme="dark"] .border-end { border-color: #334155 !important; }
        html[data-theme="dark"] .sidebar-time { color: rgba(255,255,255,.38); }
        html[data-theme="dark"] .empty-state i { color: #475569; }
        html[data-theme="dark"] .badge-number { background: #334155; color: #94a3b8; }
        html[data-theme="dark"] .object-badge { background: #334155; color: #94a3b8; }
        html[data-theme="dark"] .header-icon { box-shadow: 0 4px 10px rgba(0,0,0,.3); }
        html[data-theme="dark"] .notification-card--unread { background: #1e293b; border-color: #334155; }
        html[data-theme="dark"] .notification-card--read { background: #1e293b; border-color: #334155; }
        html[data-theme="dark"] .notification-card__message,
        html[data-theme="dark"] .notification-card__meta { color: #94a3b8; }
        html[data-theme="dark"] .badge-green { background: rgb(from var(--g500) r g b / .2) !important; color: var(--g300) !important; border-color: rgb(from var(--g500) r g b / .3) !important; }
        html[data-theme="dark"] .badge-red { background: rgba(239,68,68,.2) !important; color: #fca5a5 !important; border-color: rgba(239,68,68,.3) !important; }
        html[data-theme="dark"] .badge-gray { background: rgba(148,163,184,.2) !important; color: #cbd5e1 !important; border-color: rgba(148,163,184,.3) !important; }
        html[data-theme="dark"] .stat-icon { background: rgb(from var(--g500) r g b / .15); }
        html[data-theme="dark"] .avatar { background: rgb(from var(--g500) r g b / .15); }
        html[data-theme="dark"] .details-pre { background: #0f172a; color: #94a3b8; }
        html[data-theme="dark"] .toast-success { background: var(--g800); }
        html[data-theme="dark"] .toast-error { background: #991b1b; }
        html[data-theme="dark"] #sidebar-overlay { background: rgba(0,0,0,.5); }
    </style>

    <script>
    (function() {
        // Défaut = thème choisi pour l'établissement ; l'appareil peut surcharger (toggle sidebar).
        var saved = localStorage.getItem('theme') || '{{ ($currentHotel ?? null)?->themeMode() ?? "light" }}';
        var resolved = saved === 'system'
            ? (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
            : saved;
        document.documentElement.setAttribute('data-theme', resolved);
    })();

    // Fond d'établissement (préférence locale par appareil) — appliqué sur toutes les pages
    window.__APPBG = @json(collect(config('appearance.backgrounds', []))->map(fn ($b) => $b['css']));
    window.applyAppBg = function () {
        try {
            var key = localStorage.getItem('app-bg') || 'none';
            var css = key === 'custom'
                ? 'url("' + (localStorage.getItem('app-bg-custom') || '') + '")'
                : (window.__APPBG[key] || '');
            var el = document.body || document.documentElement;
            if (css && key !== 'none') {
                el.style.background = css;               // shorthand : gère les dégradés à couleur finale + SVG
                el.style.backgroundSize = 'cover';
                el.style.backgroundPosition = 'center';
                el.style.backgroundAttachment = 'fixed';
                document.documentElement.classList.add('has-app-bg');
            } else {
                el.style.background = '';
                document.documentElement.classList.remove('has-app-bg');
            }
        } catch (e) {}
    };
    (function(){ try { if ((localStorage.getItem('app-bg') || 'none') !== 'none') document.documentElement.classList.add('has-app-bg'); } catch (e) {} })();
    document.addEventListener('DOMContentLoaded', window.applyAppBg);
    </script>
</head>

<body>
    <!-- Modal global -->
    <div class="modal fade" id="main-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius:12px">
                <div class="modal-header bg-light border-0">
                    <h1 class="modal-title fs-5 fw-bold" id="main-modalLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button id="btn-modal-save" type="button" class="btn btn-hotel-primary">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Header -->
    @include('template.include._mobile-header')

    <!-- Sidebar (position:fixed, gère elle-même son toggle) -->
    @include('template.include._sidebar')

    <!-- Contenu principal -->
    <div id="page-content-wrapper">
        <div class="p-3">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (required by Toastr, Select2, DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Toastr -->
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.2/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    // Correctif : les fonds d'écran d'établissement appliquent un backdrop-filter
    // sur #page-content-wrapper, ce qui « piège » les modales Bootstrap (fixed) et
    // met le backdrop au-dessus -> page figée. On déplace chaque modale vers <body>
    // à l'ouverture pour qu'elle partage le même contexte d'empilement que le backdrop.
    document.addEventListener('show.bs.modal', function (e) {
        var modal = e.target;
        if (modal && modal.classList && modal.classList.contains('modal') && modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }
    });
    // Filet de sécurité : nettoie tout backdrop résiduel si une modale se ferme mal.
    document.addEventListener('hidden.bs.modal', function () {
        if (!document.querySelector('.modal.show')) {
            document.querySelectorAll('.modal-backdrop').forEach(function (b) { b.remove(); });
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        }
    });
    </script>

    <script>
    // ── Pop-ups modernes : remplace les alert()/confirm() natifs par SweetAlert2 ──
    (function () {
        if (!window.Swal) return;
        var isEn = (document.documentElement.lang || 'fr').slice(0, 2) === 'en';
        function brand() {
            var c = getComputedStyle(document.documentElement).getPropertyValue('--hotel-primary');
            return (c && c.trim()) || '#2e8540';
        }

        // alert() -> pop-up (icône déduite du texte : erreur / succès / info)
        var nativeAlert = window.alert.bind(window);
        window.alert = function (msg) {
            try {
                var s = String(msg == null ? '' : msg).trim();
                var icon = 'info';
                if (/^[❌⛔🚫]/.test(s) || /(erreur|error|échec|echec|invalide|impossible|refus)/i.test(s)) icon = 'error';
                else if (/^[✅🎉👍]/.test(s) || /(succ[eè]s|success|enregistr|cré[eé]|ajout[ée]?|mis[e]?\s*à\s*jour|supprim|envoy|termin)/i.test(s)) icon = 'success';
                else if (/^[⚠]/.test(s) || /(attention|warning|avertiss)/i.test(s)) icon = 'warning';
                var clean = s.replace(/^([❌⛔🚫✅🎉👍⚠]️?\s*)+/, '').trim();
                Swal.fire({ icon: icon, text: clean || s, confirmButtonText: 'OK', confirmButtonColor: brand(), heightAuto: false });
            } catch (err) { nativeAlert(msg); }
        };

        // confirm() en attribut inline "return confirm('…')" -> pop-up de confirmation
        document.addEventListener('DOMContentLoaded', function () {
            var re = /^\s*return\s+confirm\(\s*(['"`])([\s\S]*?)\1\s*\)\s*;?\s*$/;
            ['onsubmit', 'onclick'].forEach(function (attr) {
                document.querySelectorAll('[' + attr + ']').forEach(function (el) {
                    var m = (el.getAttribute(attr) || '').match(re);
                    if (!m) return;
                    var message = m[2];
                    var isForm = el.tagName === 'FORM' || attr === 'onsubmit';
                    el.removeAttribute(attr);
                    el.addEventListener(isForm ? 'submit' : 'click', function (e) {
                        if (el.__swalOK) { el.__swalOK = false; return; }
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: isEn ? 'Please confirm' : 'Confirmation',
                            text: message,
                            showCancelButton: true,
                            confirmButtonText: isEn ? 'Yes' : 'Oui',
                            cancelButtonText: isEn ? 'Cancel' : 'Annuler',
                            confirmButtonColor: brand(),
                            cancelButtonColor: '#94a3b8',
                            heightAuto: false
                        }).then(function (r) {
                            if (!r.isConfirmed) return;
                            if (isForm) { (el.tagName === 'FORM' ? el : el.closest('form')).submit(); }
                            else if (el.tagName === 'A' && el.getAttribute('href')) { window.location.href = el.href; }
                            else if (el.form) { el.form.submit(); }
                            else { el.__swalOK = true; el.click(); }
                        });
                    });
                });
            });
        });
    })();
    </script>

    <script>
    (function () {
        'use strict';

        // Observer #sidebar · quand elle prend/perd la classe .collapsed
        // on ajoute/retire .sidebar-is-collapsed sur <body>
        // => le CSS s'occupe du margin-left automatiquement
        var sidebar = document.getElementById('sidebar');

        function sync() {
            if (!sidebar) return;
            document.body.classList.toggle(
                'sidebar-is-collapsed',
                sidebar.classList.contains('collapsed')
            );
        }

        if (sidebar) {
            // État initial (localStorage peut avoir sauvegardé collapsed)
            sync();
            // Observer tous les futurs changements
            new MutationObserver(sync).observe(sidebar, {
                attributes: true,
                attributeFilter: ['class']
            });
        }

        // Bootstrap tooltips
        var tries = 0;
        var bsCheck = setInterval(function () {
            tries++;
            if (typeof bootstrap !== 'undefined') {
                clearInterval(bsCheck);
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
                    try { new bootstrap.Tooltip(el); } catch (e) {}
                });
            } else if (tries > 20) {
                clearInterval(bsCheck);
            }
        }, 100);

    })();
    </script>

    @stack('scripts')

    <script>
    (function () {
        'use strict';
        @if($activeSession)
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('form[action*="logout"] button, a[href*="logout"]');
            if (!btn) return;
            e.preventDefault(); e.stopPropagation();
            Swal.fire({
                title: '⚠️ Session Active',
                html: 'Vous avez une session active <strong>#{{ $activeSession->id }}</strong>.<br>Veuillez la clôturer avant de vous déconnecter.',
                icon: 'warning',
                confirmButtonColor: 'var(--g500)', confirmButtonText: 'Compris',
                showCancelButton: true, cancelButtonText: 'Aller à la session', cancelButtonColor: '#3b82f6'
            }).then(function (r) {
                if (r.dismiss === Swal.DismissReason.cancel)
                    window.location.href = '{{ route("cashier.sessions.show", $activeSession) }}';
            });
        }, true);
        window.onbeforeunload = null;
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('form[action*="logout"], a[href*="logout"]').forEach(function (el) {
                el.style.opacity = '0.6';
                el.style.pointerEvents = 'none';
                el.title = 'Session active · déconnexion impossible';
            });
        });
        @else
        window.onbeforeunload = null;
        @endif
    })();
    
    // Sécurité globale pour les images de plats (404 Fallback)
    document.addEventListener('error', function(e) {
        if (e.target.tagName.toLowerCase() === 'img' && !e.target.classList.contains('no-fallback')) {
            const fallback = 'https://i.pinimg.com/736x/fc/7a/4a/fc7a4ad5e3299c1dac28baa60eef6111.jpg';
            if (e.target.src !== fallback) {
                e.target.src = fallback;
            }
        }
    }, true);
    </script>

    @yield('footer')

    <script>
    (function() {
        var themes = ['light', 'dark', 'system'];
        var icons  = { light: 'fa-sun', dark: 'fa-moon', system: 'fa-desktop' };
        var labels = { light: 'Clair', dark: 'Sombre', system: 'Système' };
        var current = localStorage.getItem('theme') || '{{ ($currentHotel ?? null)?->themeMode() ?? "light" }}';

        function applyTheme(theme) {
            localStorage.setItem('theme', theme);
            var resolved = theme === 'system'
                ? (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
                : theme;
            document.documentElement.setAttribute('data-theme', resolved);
            var icon  = document.getElementById('themeIcon');
            var label = document.getElementById('themeLabel');
            if (icon)  { icon.className = 'fas ' + icons[theme]; }
            if (label) { label.textContent = labels[theme]; }
        }

        applyTheme(current);

        /* Met à jour si l'OS change de préférence quand on est en "system" */
        matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function() {
            if (localStorage.getItem('theme') === 'system') applyTheme('system');
        });

        var btn = document.getElementById('themeToggle');
        if (btn) {
            btn.addEventListener('click', function() {
                var idx = themes.indexOf(current);
                current = themes[(idx + 1) % themes.length];
                applyTheme(current);
            });
        }
    })();
    </script>
</body>
</html>