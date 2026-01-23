<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- App CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <!-- Votre navbar... -->
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- jQuery (si nécessaire) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- App JS -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Scripts spécifiques à la page -->
    @stack('scripts')

    <!-- Script pour s'assurer que Bootstrap est chargé -->
    <script>
        // Attendre que tout soit chargé
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier que Bootstrap est disponible
            if (typeof bootstrap === 'undefined') {
                console.error('Bootstrap n\'est pas chargé !');
                // Recharger Bootstrap
                var script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js';
                script.onload = function() {
                    console.log('Bootstrap rechargé avec succès');
                    // Redémarrer les composants Bootstrap
                    initBootstrapComponents();
                };
                document.head.appendChild(script);
            } else {
                console.log('Bootstrap est disponible');
                // Initialiser les composants Bootstrap
                initBootstrapComponents();
            }
        });

        function initBootstrapComponents() {
            // Initialiser les toasts
            if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
                var toastElList = [].slice.call(document.querySelectorAll('.toast'));
                var toastList = toastElList.map(function(toastEl) {
                    return new bootstrap.Toast(toastEl);
                });
                console.log(toastList.length + ' toast(s) initialisé(s)');
            }

            // Initialiser les tooltips
            if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
                console.log(tooltipList.length + ' tooltip(s) initialisé(s)');
            }

            // Initialiser les popovers
            if (typeof bootstrap !== 'undefined' && bootstrap.Popover) {
                var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
                var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl);
                });
            }
        }
    </script>
</body>
</html>