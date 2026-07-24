<?php

/*
|--------------------------------------------------------------------------
| Plans d'abonnement SaaS
|--------------------------------------------------------------------------
|
| Tarification par nombre de chambres :
|   - starter  : 0 à 10 chambres   -> 25 000 CFA / mois
|   - pro      : 11 à 20 chambres  -> 45 000 CFA / mois
|   - business : plus de 20 chambres -> 60 000 CFA / mois
|
| room_limit = nombre maximum de chambres (null = illimité).
|
*/

return [

    'trial_days' => 14, // essai gratuit : 2 semaines

    'default' => 'starter',

    /*
    | Pays desservis. Les prix des paliers (ci-dessous) sont la RÉFÉRENCE (Bénin).
    | Chaque pays applique un "coef" (coût de la vie relatif) à ces prix de base
    | et sa propre devise. Prix final = prix de base × coef, arrondi à la centaine.
    */
    'default_country' => 'BJ',

    // coef = multiplicateur appliqué au prix de base (Bénin) — intègre coût de la vie + devise.
    // round = pas d'arrondi dans la devise du pays. Ajuste ces valeurs à ta convenance.
    'countries' => [
        'BJ' => ['name' => 'Bénin',           'currency' => 'XOF', 'coef' => 1.00,   'round' => 100],
        'TG' => ['name' => 'Togo',            'currency' => 'XOF', 'coef' => 0.95,   'round' => 100],
        'CI' => ['name' => "Côte d'Ivoire",   'currency' => 'XOF', 'coef' => 1.20,   'round' => 100],
        'SN' => ['name' => 'Sénégal',         'currency' => 'XOF', 'coef' => 1.15,   'round' => 100],
        'BF' => ['name' => 'Burkina Faso',    'currency' => 'XOF', 'coef' => 0.90,   'round' => 100],
        'ML' => ['name' => 'Mali',            'currency' => 'XOF', 'coef' => 0.90,   'round' => 100],
        'NE' => ['name' => 'Niger',           'currency' => 'XOF', 'coef' => 0.85,   'round' => 100],
        'CM' => ['name' => 'Cameroun',        'currency' => 'XAF', 'coef' => 1.05,   'round' => 100],
        'GA' => ['name' => 'Gabon',           'currency' => 'XAF', 'coef' => 1.60,   'round' => 100],
        'NG' => ['name' => 'Nigeria',         'currency' => 'NGN', 'coef' => 1.80,   'round' => 500],
        'GH' => ['name' => 'Ghana',           'currency' => 'GHS', 'coef' => 0.025,  'round' => 10],
        'FR' => ['name' => 'France',          'currency' => 'EUR', 'coef' => 0.0018, 'round' => 1],
    ],

    'tiers' => [
        'starter' => [
            'key' => 'starter',
            'name' => 'Starter',
            'price' => 25000,
            'currency' => 'CFA',
            'room_min' => 0,
            'room_max' => 10,
            'room_limit' => 10,
            // Modules premium inclus (le socle réservations/caisse est toujours disponible).
            'modules' => [],
            'tagline' => 'Pour un hôtel qui démarre',
            'features' => [
                'Jusqu\'à 10 chambres',
                'Réservations & check-in',
                'Caisse & paiements',
                'Support par email',
            ],
        ],
        'pro' => [
            'key' => 'pro',
            'name' => 'Pro',
            'price' => 45000,
            'currency' => 'CFA',
            'room_min' => 11,
            'room_max' => 20,
            'room_limit' => 20,
            'modules' => ['restaurant', 'housekeeping', 'reports'],
            'tagline' => 'Pour les hôtels en croissance',
            'popular' => true,
            'features' => [
                'De 11 à 20 chambres',
                'Restaurant & housekeeping',
                'Rapports avancés',
                'Support prioritaire',
            ],
        ],
        'business' => [
            'key' => 'business',
            'name' => 'Business',
            'price' => 60000,
            'currency' => 'CFA',
            'room_min' => 21,
            'room_max' => null,
            'room_limit' => null,
            'modules' => ['restaurant', 'housekeeping', 'reports'],
            'tagline' => 'Pour les grands établissements',
            'features' => [
                'Plus de 20 chambres',
                'Chambres illimitées',
                'Toutes les fonctionnalités',
                'Accompagnement dédié',
            ],
        ],
    ],
];
