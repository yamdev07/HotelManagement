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
        ::-webkit-scrollbar-thumb { background: #10b981; border-radius: 10px; }
        .table-responsive { overflow-x: auto; }

        /* ══ DARK MODE ══ */
        html[data-theme="dark"] {
            color-scheme: dark;
        }
        html[data-theme="dark"] body {
            background: #0f172a;
            color: #e2e8f0;
        }
        html[data-theme="dark"] #page-content-wrapper {
            background: #0f172a;
        }
        html[data-theme="dark"] #page-content-wrapper > .p-3 {
            color: #e2e8f0;
        }
        html[data-theme="dark"] .modal-content {
            background: #1e293b;
            color: #e2e8f0;
        }
        html[data-theme="dark"] .modal-header {
            background: #1e293b !important;
            color: #e2e8f0;
            border-color: #334155;
        }
        html[data-theme="dark"] .modal-footer {
            background: #1e293b !important;
            border-color: #334155;
        }
        html[data-theme="dark"] .btn-outline-secondary {
            color: #94a3b8;
            border-color: #475569;
        }
        html[data-theme="dark"] .btn-outline-secondary:hover {
            background: #334155;
            color: #e2e8f0;
        }
        html[data-theme="dark"] .card,
        html[data-theme="dark"] .bg-white,
        html[data-theme="dark"] .table,
        html[data-theme="dark"] .form-control,
        html[data-theme="dark"] .form-select {
            background-color: #1e293b;
            color: #e2e8f0;
            border-color: #334155;
        }
        html[data-theme="dark"] .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: rgba(30, 41, 59, .5);
            color: #e2e8f0;
        }
        html[data-theme="dark"] .table > :not(caption) > * > * {
            color: #e2e8f0;
            border-bottom-color: #334155;
        }
        html[data-theme="dark"] .text-muted,
        html[data-theme="dark"] .text-secondary {
            color: #94a3b8 !important;
        }
        html[data-theme="dark"] .bg-light,
        html[data-theme="dark"] .bg-light-subtle {
            background-color: #1e293b !important;
            color: #e2e8f0;
        }
        html[data-theme="dark"] .border,
        html[data-theme="dark"] .border-bottom,
        html[data-theme="dark"] .border-top,
        html[data-theme="dark"] .border-start,
        html[data-theme="dark"] .border-end {
            border-color: #334155 !important;
        }
        html[data-theme="dark"] .page-link {
            background: #1e293b;
            color: #94a3b8;
            border-color: #334155;
        }
        html[data-theme="dark"] .page-item.active .page-link {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }
        html[data-theme="dark"] .pagination .page-link:hover {
            background: #334155;
            color: #e2e8f0;
        }
        html[data-theme="dark"] .alert-success {
            background-color: rgba(16, 185, 129, .15);
            color: #6ee7b7;
            border-color: rgba(16, 185, 129, .3);
        }
        html[data-theme="dark"] .alert-danger {
            background-color: rgba(239, 68, 68, .15);
            color: #fca5a5;
            border-color: rgba(239, 68, 68, .3);
        }
        html[data-theme="dark"] .alert-warning {
            background-color: rgba(245, 158, 11, .15);
            color: #fcd34d;
            border-color: rgba(245, 158, 11, .3);
        }
        html[data-theme="dark"] .alert-info {
            background-color: rgba(59, 130, 246, .15);
            color: #93c5fd;
            border-color: rgba(59, 130, 246, .3);
        }
        html[data-theme="dark"] .dropdown-menu {
            background: #1e293b;
            border-color: #334155;
        }
        html[data-theme="dark"] .dropdown-item {
            color: #e2e8f0;
        }
        html[data-theme="dark"] .dropdown-item:hover,
        html[data-theme="dark"] .dropdown-item:focus {
            background: #334155;
            color: #fff;
        }
        html[data-theme="dark"] .form-check-input {
            background-color: #334155;
            border-color: #475569;
        }
        html[data-theme="dark"] .form-check-input:checked {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }
        html[data-theme="dark"] label,
        html[data-theme="dark"] .form-label {
            color: #cbd5e1;
        }
        html[data-theme="dark"] .badge {
            color: #0f172a;
        }
        html[data-theme="dark"] ::-webkit-scrollbar { background: #1e293b; }
        html[data-theme="dark"] ::-webkit-scrollbar-thumb { background: #475569; }
        html[data-theme="dark"] .swal2-popup {
            background: #1e293b;
            color: #e2e8f0;
        }
        html[data-theme="dark"] .swal2-title { color: #e2e8f0; }
        html[data-theme="dark"] .swal2-html-container { color: #94a3b8; }

        /* ══ SYSTEM THEME (prefers-color-scheme) ══ */
        @media (prefers-color-scheme: dark) {
            html[data-theme="system"] body { background: #0f172a; color: #e2e8f0; }
            html[data-theme="system"] #page-content-wrapper { background: #0f172a; }
            html[data-theme="system"] #page-content-wrapper > .p-3 { color: #e2e8f0; }
            html[data-theme="system"] .modal-content { background: #1e293b; color: #e2e8f0; }
            html[data-theme="system"] .modal-header { background: #1e293b !important; color: #e2e8f0; border-color: #334155; }
            html[data-theme="system"] .modal-footer { background: #1e293b !important; border-color: #334155; }
            html[data-theme="system"] .card,
            html[data-theme="system"] .bg-white,
            html[data-theme="system"] .table,
            html[data-theme="system"] .form-control,
            html[data-theme="system"] .form-select { background-color: #1e293b; color: #e2e8f0; border-color: #334155; }
            html[data-theme="system"] .table > :not(caption) > * > * { color: #e2e8f0; border-bottom-color: #334155; }
            html[data-theme="system"] .text-muted,
            html[data-theme="system"] .text-secondary { color: #94a3b8 !important; }
            html[data-theme="system"] .bg-light,
            html[data-theme="system"] .bg-light-subtle { background-color: #1e293b !important; color: #e2e8f0; }
            html[data-theme="system"] .border,
            html[data-theme="system"] .border-bottom,
            html[data-theme="system"] .border-top,
            html[data-theme="system"] .border-start,
            html[data-theme="system"] .border-end { border-color: #334155 !important; }
            html[data-theme="system"] .dropdown-menu { background: #1e293b; border-color: #334155; }
            html[data-theme="system"] .dropdown-item { color: #e2e8f0; }
            html[data-theme="system"] .dropdown-item:hover,
            html[data-theme="system"] .dropdown-item:focus { background: #334155; color: #fff; }
            html[data-theme="system"] .form-check-input { background-color: #334155; border-color: #475569; }
            html[data-theme="system"] .form-check-input:checked { background-color: #4f46e5; border-color: #4f46e5; }
            html[data-theme="system"] label,
            html[data-theme="system"] .form-label { color: #cbd5e1; }
            html[data-theme="system"] .alert-success { background-color: rgba(16,185,129,.15); color: #6ee7b7; border-color: rgba(16,185,129,.3); }
            html[data-theme="system"] .alert-danger { background-color: rgba(239,68,68,.15); color: #fca5a5; border-color: rgba(239,68,68,.3); }
            html[data-theme="system"] .alert-warning { background-color: rgba(245,158,11,.15); color: #fcd34d; border-color: rgba(245,158,11,.3); }
            html[data-theme="system"] .alert-info { background-color: rgba(59,130,246,.15); color: #93c5fd; border-color: rgba(59,130,246,.3); }
            html[data-theme="system"] .page-link { background: #1e293b; color: #94a3b8; border-color: #334155; }
            html[data-theme="system"] .page-item.active .page-link { background-color: #4f46e5; border-color: #4f46e5; }
            html[data-theme="system"] ::-webkit-scrollbar { background: #1e293b; }
            html[data-theme="system"] ::-webkit-scrollbar-thumb { background: #475569; }
            html[data-theme="system"] .swal2-popup { background: #1e293b; color: #e2e8f0; }
            html[data-theme="system"] .swal2-title { color: #e2e8f0; }
            html[data-theme="system"] .swal2-html-container { color: #94a3b8; }
        }
    </style>

    <script>
    (function() {
        var saved = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', saved);
    })();
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
                confirmButtonColor: '#10b981', confirmButtonText: 'Compris',
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
        var current = localStorage.getItem('theme') || 'light';

        function applyTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            var icon  = document.getElementById('themeIcon');
            var label = document.getElementById('themeLabel');
            if (icon)  { icon.className = 'fas ' + icons[theme]; }
            if (label) { label.textContent = labels[theme]; }
        }

        applyTheme(current);

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