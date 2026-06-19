<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'MyHotel') }} — La plateforme de gestion hôtelière tout-en-un</title>
    <meta name="description" content="Gérez réservations, caisse, restaurant, housekeeping et rapports pour un ou plusieurs hôtels, depuis une seule plateforme.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand: #4f46e5;
            --brand-dark: #4338ca;
            --ink: #0f172a;
        }
        * { font-family: 'Inter', system-ui, sans-serif; }
        body { color: var(--ink); }
        .navbar-brand { font-weight: 800; letter-spacing: -.5px; }
        .text-brand { color: var(--brand) !important; }
        .btn-brand { background: var(--brand); border-color: var(--brand); color: #fff; }
        .btn-brand:hover { background: var(--brand-dark); border-color: var(--brand-dark); color: #fff; }
        .btn-outline-brand { color: var(--brand); border-color: var(--brand); }
        .btn-outline-brand:hover { background: var(--brand); color: #fff; }

        .hero {
            background: radial-gradient(1200px 500px at 70% -10%, #e0e7ff 0%, rgba(224,231,255,0) 60%),
                        linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            padding: 6rem 0 5rem;
        }
        .hero h1 { font-weight: 800; font-size: clamp(2.2rem, 5vw, 3.6rem); line-height: 1.05; letter-spacing: -1.5px; }
        .badge-soft { background: #eef2ff; color: var(--brand); font-weight: 600; padding: .5rem 1rem; border-radius: 999px; }

        .hero-mock {
            border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 30px 60px -20px rgba(79,70,229,.35);
            overflow: hidden; background: #fff;
        }
        .hero-mock .bar { height: 36px; background: #f1f5f9; display: flex; align-items: center; gap: 6px; padding: 0 12px; }
        .hero-mock .dot { width: 10px; height: 10px; border-radius: 50%; background: #cbd5e1; }

        .feature-icon {
            width: 52px; height: 52px; border-radius: 12px; display: grid; place-items: center;
            background: #eef2ff; color: var(--brand); font-size: 1.3rem;
        }
        .card-feature { border: 1px solid #eef0f4; border-radius: 16px; transition: .2s; height: 100%; }
        .card-feature:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -24px rgba(15,23,42,.25); }

        .step-num { width: 44px; height: 44px; border-radius: 50%; background: var(--brand); color: #fff; display: grid; place-items: center; font-weight: 700; }

        .price-card { border: 1px solid #e8eaf0; border-radius: 18px; height: 100%; }
        .price-card.popular { border: 2px solid var(--brand); box-shadow: 0 24px 50px -28px rgba(79,70,229,.5); }
        .price-amount { font-size: 2.4rem; font-weight: 800; letter-spacing: -1px; }

        .cta-band { background: linear-gradient(120deg, var(--brand) 0%, #7c3aed 100%); color: #fff; border-radius: 24px; }
        footer a { color: #cbd5e1; text-decoration: none; }
        footer a:hover { color: #fff; }
        .section { padding: 5rem 0; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('landing') }}">
            <i class="fas fa-hotel text-brand me-1"></i> {{ config('app.name', 'MyHotel') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav mx-auto gap-lg-3">
                <li class="nav-item"><a class="nav-link" href="#features">Fonctionnalités</a></li>
                <li class="nav-item"><a class="nav-link" href="#how">Comment ça marche</a></li>
                <li class="nav-item"><a class="nav-link" href="#pricing">Tarifs</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('frontend.home') }}">Démo vitrine</a></li>
            </ul>
            <div class="d-flex gap-2">
                <a href="{{ route('login.index') }}" class="btn btn-outline-brand">Se connecter</a>
                <a href="{{ route('hotel.register') }}" class="btn btn-brand">Essai gratuit</a>
            </div>
        </div>
    </div>
</nav>

<!-- HERO -->
<header class="hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge-soft mb-3 d-inline-block"><i class="fas fa-bolt me-1"></i> Plateforme SaaS multi-établissements</span>
                <h1 class="mb-3">Gérez vos hôtels,<br>sans la complexité.</h1>
                <p class="fs-5 text-secondary mb-4">
                    Réservations, check-in, caisse, restaurant, housekeeping et rapports —
                    le tout dans une seule plateforme, pour un hôtel comme pour tout un groupe.
                </p>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <a href="{{ route('hotel.register') }}" class="btn btn-brand btn-lg px-4"><i class="fas fa-rocket me-2"></i>Commencer gratuitement</a>
                    <a href="{{ route('frontend.home') }}" class="btn btn-outline-brand btn-lg px-4"><i class="fas fa-play me-2"></i>Voir une démo</a>
                </div>
                <div class="d-flex gap-4 text-secondary small">
                    <span><i class="fas fa-check text-success me-1"></i> Sans engagement</span>
                    <span><i class="fas fa-check text-success me-1"></i> Mise en route en 5 min</span>
                    <span><i class="fas fa-check text-success me-1"></i> Support inclus</span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-mock">
                    <div class="bar"><span class="dot"></span><span class="dot"></span><span class="dot"></span></div>
                    <div class="p-3">
                        <div class="row g-3">
                            <div class="col-4"><div class="p-3 rounded-3 bg-light text-center"><div class="fs-4 fw-bold text-brand">128</div><div class="small text-secondary">Chambres</div></div></div>
                            <div class="col-4"><div class="p-3 rounded-3 bg-light text-center"><div class="fs-4 fw-bold text-brand">94%</div><div class="small text-secondary">Occupation</div></div></div>
                            <div class="col-4"><div class="p-3 rounded-3 bg-light text-center"><div class="fs-4 fw-bold text-brand">3</div><div class="small text-secondary">Hôtels</div></div></div>
                            <div class="col-12">
                                <div class="p-3 rounded-3 border">
                                    <div class="d-flex justify-content-between mb-2"><span class="fw-semibold">Revenus du jour</span><span class="text-success fw-bold">+ 1 240 000 CFA</span></div>
                                    <div class="progress" style="height:8px"><div class="progress-bar" style="width:72%;background:var(--brand)"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- FEATURES -->
<section class="section" id="features">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge-soft mb-2 d-inline-block">Fonctionnalités</span>
            <h2 class="fw-bold">Tout ce qu'il faut pour faire tourner un hôtel</h2>
            <p class="text-secondary">Une suite complète, pensée pour la réception, la caisse et la direction.</p>
        </div>
        <div class="row g-4">
            @php
                $features = [
                    ['fa-building', 'Multi-établissements', 'Gérez plusieurs hôtels depuis un seul compte, avec des données parfaitement isolées.'],
                    ['fa-calendar-check', 'Réservations & check-in', 'Réservations, check-in/out direct, gestion des arrivées tardives et des chambres.'],
                    ['fa-cash-register', 'Caisse & paiements', 'Sessions de caisse, encaissements multi-moyens, clôture et contrôle des écarts.'],
                    ['fa-utensils', 'Restaurant', 'Menu, commandes en chambre, réservations de table et facturation intégrée.'],
                    ['fa-broom', 'Housekeeping', 'Suivi du nettoyage, statuts des chambres et coordination des équipes.'],
                    ['fa-chart-line', 'Rapports & analytics', 'Tableaux de bord, revenus, taux d\'occupation et exports en un clic.'],
                ];
            @endphp
            @foreach ($features as [$icon, $title, $desc])
                <div class="col-md-6 col-lg-4">
                    <div class="card-feature p-4">
                        <div class="feature-icon mb-3"><i class="fas {{ $icon }}"></i></div>
                        <h5 class="fw-bold">{{ $title }}</h5>
                        <p class="text-secondary mb-0">{{ $desc }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="section bg-light" id="how">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge-soft mb-2 d-inline-block">Comment ça marche</span>
            <h2 class="fw-bold">Opérationnel en 3 étapes</h2>
        </div>
        <div class="row g-4">
            @php
                $steps = [
                    ['Créez votre établissement', 'Ajoutez votre hôtel, vos chambres et votre équipe en quelques minutes.'],
                    ['Gérez au quotidien', 'Réceptionnez, encaissez, suivez le restaurant et le ménage en temps réel.'],
                    ['Pilotez vos résultats', 'Suivez vos revenus et votre occupation, hôtel par hôtel ou globalement.'],
                ];
            @endphp
            @foreach ($steps as $i => [$title, $desc])
                <div class="col-md-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="step-num flex-shrink-0">{{ $i + 1 }}</div>
                        <div>
                            <h5 class="fw-bold mb-1">{{ $title }}</h5>
                            <p class="text-secondary mb-0">{{ $desc }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- PRICING -->
<section class="section" id="pricing">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge-soft mb-2 d-inline-block">Tarifs</span>
            <h2 class="fw-bold">Des offres simples et transparentes</h2>
            <p class="text-secondary">Sans frais cachés. Changez d'offre à tout moment.</p>
        </div>
        <div class="row g-4 justify-content-center">
            @php
                $plans = [
                    ['Starter', '25 000', 'Pour un hôtel qui démarre', ['1 établissement', 'Jusqu\'à 20 chambres', 'Réservations & caisse', 'Support par email'], false],
                    ['Pro', '60 000', 'Pour les hôtels en croissance', ['1 établissement', 'Chambres illimitées', 'Restaurant & housekeeping', 'Rapports avancés', 'Support prioritaire'], true],
                    ['Enterprise', 'Sur devis', 'Pour les groupes hôteliers', ['Établissements illimités', 'Dashboard multi-hôtels', 'Rôles & permissions avancés', 'Accompagnement dédié'], false],
                ];
            @endphp
            @foreach ($plans as [$name, $price, $tagline, $items, $popular])
                <div class="col-md-6 col-lg-4">
                    <div class="price-card p-4 {{ $popular ? 'popular' : '' }}">
                        @if ($popular)
                            <span class="badge bg-brand text-white mb-2" style="background:var(--brand)">Le plus populaire</span>
                        @endif
                        <h4 class="fw-bold">{{ $name }}</h4>
                        <p class="text-secondary small">{{ $tagline }}</p>
                        <div class="price-amount mb-1">
                            {{ $price }}
                            @if (is_numeric(str_replace(' ', '', $price)))
                                <span class="fs-6 text-secondary fw-normal">CFA / mois</span>
                            @endif
                        </div>
                        <hr>
                        <ul class="list-unstyled mb-4">
                            @foreach ($items as $item)
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ $item }}</li>
                            @endforeach
                        </ul>
                        <a href="{{ route('hotel.register') }}" class="btn {{ $popular ? 'btn-brand' : 'btn-outline-brand' }} w-100">
                            Choisir {{ $name }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA BAND -->
<section class="pb-5">
    <div class="container">
        <div class="cta-band p-5 text-center">
            <h2 class="fw-bold mb-2">Prêt à digitaliser votre hôtel ?</h2>
            <p class="mb-4 opacity-75">Rejoignez les établissements qui pilotent leur activité avec {{ config('app.name', 'MyHotel') }}.</p>
            <a href="{{ route('hotel.register') }}" class="btn btn-light btn-lg px-4 text-brand fw-semibold">
                <i class="fas fa-rocket me-2"></i>Démarrer maintenant
            </a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="bg-dark text-light pt-5 pb-4">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-hotel text-brand me-1"></i> {{ config('app.name', 'MyHotel') }}</h5>
                <p class="text-secondary small">La plateforme de gestion hôtelière tout-en-un pour les hôtels et les groupes.</p>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="fw-semibold mb-3">Produit</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="#features">Fonctionnalités</a></li>
                    <li class="mb-2"><a href="#pricing">Tarifs</a></li>
                    <li class="mb-2"><a href="{{ route('hotel.register') }}">Essai gratuit</a></li>
                    <li class="mb-2"><a href="{{ route('frontend.home') }}">Démo</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="fw-semibold mb-3">Compte</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('login.index') }}">Se connecter</a></li>
                    <li class="mb-2"><a href="#pricing">Essai gratuit</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="fw-semibold mb-3">Contact</h6>
                <p class="text-secondary small mb-1"><i class="fas fa-envelope me-2"></i>contact@{{ \Illuminate\Support\Str::slug(config('app.name', 'myhotel')) }}.com</p>
                <p class="text-secondary small"><i class="fas fa-phone me-2"></i>+229 00 00 00 00</p>
            </div>
        </div>
        <hr class="border-secondary">
        <div class="text-center text-secondary small">
            © {{ date('Y') }} {{ config('app.name', 'MyHotel') }}. Tous droits réservés.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
