{{-- White-label : applique les couleurs de l'hôtel courant. $currentHotel est partagé par AppServiceProvider. --}}
@isset($currentHotel)
    @if ($currentHotel)
        <style>
            :root {
                --hotel-primary: {{ $currentHotel->primaryColor() }};
                --hotel-secondary: {{ $currentHotel->secondaryColor() }};

                /* Recolore la palette verte historique (--g50..--g900) du thème
                   à partir de la couleur de l'hôtel. !important pour gagner sur
                   les :root redéfinis dans les vues. color-mix génère les nuances. */
                --g50:  color-mix(in srgb, var(--hotel-primary) 6%,  #fff) !important;
                --g100: color-mix(in srgb, var(--hotel-primary) 12%, #fff) !important;
                --g200: color-mix(in srgb, var(--hotel-primary) 26%, #fff) !important;
                --g300: color-mix(in srgb, var(--hotel-primary) 45%, #fff) !important;
                --g400: color-mix(in srgb, var(--hotel-primary) 70%, #fff) !important;
                --g500: color-mix(in srgb, var(--hotel-primary) 88%, #fff) !important;
                --g600: var(--hotel-primary) !important;
                --g700: color-mix(in srgb, var(--hotel-primary) 80%, #000) !important;
                --g800: color-mix(in srgb, var(--hotel-primary) 62%, #000) !important;
                --g900: color-mix(in srgb, var(--hotel-primary) 45%, #000) !important;
            }

            /* Boutons & accents Bootstrap */
            .btn-primary {
                background-color: var(--hotel-primary) !important;
                border-color: var(--hotel-primary) !important;
            }
            .btn-outline-primary {
                color: var(--hotel-primary) !important;
                border-color: var(--hotel-primary) !important;
            }
            .btn-outline-primary:hover {
                background-color: var(--hotel-primary) !important;
                color: #fff !important;
            }
            .bg-primary { background-color: var(--hotel-primary) !important; }
            .text-primary { color: var(--hotel-primary) !important; }
            .border-primary { border-color: var(--hotel-primary) !important; }
            a { color: var(--hotel-primary); }

            /* Sidebar & navigation active */
            #sidebar { background: var(--hotel-secondary) !important; }
            #sidebar .nav-item.active,
            #sidebar .nav-item:hover,
            #sidebar .nav-item--active {
                background: var(--hotel-primary) !important;
            }
            .progress-bar { background-color: var(--hotel-primary) !important; }
        </style>
    @endif
@endisset
