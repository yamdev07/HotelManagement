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
    <link rel="icon" href="{{ asset('img/logo/sip.png') }}">
    @vite('resources/sass/app.scss')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    @stack('styles')
    <title>@yield('title') - Hotel Admin</title>
    @yield('head')

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

        /* Desktop — sidebar ouverte (272px) */
        #page-content-wrapper {
            margin-left: 272px;
            width: auto;          /* NE PAS mettre 100% : block auto = viewport - margin-left */
            min-height: 100vh;
            background: #f5f7fb;
            transition: margin-left 0.3s cubic-bezier(.4,0,.2,1);
            overflow-x: hidden;   /* Sécurité pour les tableaux très larges */
            box-sizing: border-box;
        }

        /* Desktop — sidebar collapsed (64px) */
        body.sidebar-is-collapsed #page-content-wrapper {
            margin-left: 64px;
        }

        /* Mobile ≤768px — sidebar en overlay, contenu pleine largeur */
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
    </style>
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
    @vite('resources/js/app.js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    (function () {
        'use strict';

        // Observer #sidebar — quand elle prend/perd la classe .collapsed
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
                el.title = 'Session active — déconnexion impossible';
            });
        });
        @else
        window.onbeforeunload = null;
        @endif
    })();
    </script>

    @yield('footer')
</body>
</html>