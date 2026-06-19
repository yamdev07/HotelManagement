{{-- White-label : applique les couleurs de l'hôtel courant. $currentHotel est partagé par AppServiceProvider. --}}
@isset($currentHotel)
    @if ($currentHotel)
        <style>
            :root {
                --hotel-primary: {{ $currentHotel->primaryColor() }};
                --hotel-secondary: {{ $currentHotel->secondaryColor() }};
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
