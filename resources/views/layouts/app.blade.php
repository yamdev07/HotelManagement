<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- OBLIGATOIRE pour la sauvegarde AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('layouts.admin_panel', ['app' => config('app.name', 'Laravel')]) }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">

    <!-- Bootstrap CSS (CDN) - Rapide et fiable -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons (si utilisé) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Chart.js (pour les graphiques) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- jQuery (required by some plugins) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Fallback CSS personnalisé (si Vite ne charge pas) -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    {{-- OBLIGATOIRE pour charger les styles de la vue --}}
    @stack('styles')
</head>
<body>
    <div id="app">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            @if(auth()->user()->role !== 'Customer')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard.index') }}">
                                    <i class="fas fa-tachometer-alt"></i> {{ __('layouts.dashboard') }}
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-bed"></i> {{ __('layouts.accommodation') }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('room.index') }}">{{ __('layouts.rooms') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('type.index') }}">{{ __('layouts.types') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('roomstatus.index') }}">{{ __('layouts.statuses') }}</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('transaction.index') }}">
                                    <i class="fas fa-exchange-alt"></i> {{ __('layouts.transactions') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customer.index') }}">
                                    <i class="fas fa-users"></i> {{ __('layouts.customers') }}
                                </a>
                            </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login.index') }}">{{ __('Login') }}</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.index') }}">
                                        <i class="fas fa-id-card"></i> {{ __('layouts.my_profile') }}
                                    </a>
                                    
                                    @if(auth()->user()->role === 'Super')
                                    <a class="dropdown-item" href="{{ route('user.index') }}">
                                        <i class="fas fa-user-cog"></i> {{ __('layouts.users') }}
                                    </a>
                                    @endif
                                    
                                    <div class="dropdown-divider"></div>
                                    
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="py-4">
            <div class="container">
                @include('partials.alerts')
                @yield('content')
            </div>
        </main>
    </div>

    <!-- jQuery (nécessaire pour certains plugins) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS Bundle (Popper included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts spécifiques à la page -->
    {{-- OBLIGATOIRE pour charger les scripts de la vue --}}
    @stack('scripts')

    <!-- Initialisation Bootstrap -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier Bootstrap
            if (typeof bootstrap === 'undefined') {
                console.error('{{ __('layouts.bootstrap_not_loaded') }}');
                var script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js';
                script.onload = function() {
                    console.log('{{ __('layouts.bootstrap_reloaded') }}');
                    initBootstrap();
                };
                document.head.appendChild(script);
            } else {
                console.log('{{ __('layouts.bootstrap_loaded') }}');
                initBootstrap();
            }
        });

        function initBootstrap() {
            // Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Popovers
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function(popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });

            // Toasts
            var toastElList = [].slice.call(document.querySelectorAll('.toast'));
            toastElList.map(function(toastEl) {
                return new bootstrap.Toast(toastEl);
            });

            // Dropdowns (auto-initialisés, mais on force)
            var dropdowns = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            dropdowns.map(function(dropdown) {
                return new bootstrap.Dropdown(dropdown);
            });

            console.log('{{ __('layouts.bootstrap_initialized') }}');
        }
    </script>
</body>
</html>