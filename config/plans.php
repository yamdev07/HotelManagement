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

    'tiers' => [
        'starter' => [
            'key'         => 'starter',
            'name'        => 'Starter',
            'price'       => 25000,
            'currency'    => 'CFA',
            'room_min'    => 0,
            'room_max'    => 10,
            'room_limit'  => 10,
            'tagline'     => 'Pour un hôtel qui démarre',
            'features'    => [
                'Jusqu\'à 10 chambres',
                'Réservations & check-in',
                'Caisse & paiements',
                'Support par email',
            ],
        ],
        'pro' => [
            'key'         => 'pro',
            'name'        => 'Pro',
            'price'       => 45000,
            'currency'    => 'CFA',
            'room_min'    => 11,
            'room_max'    => 20,
            'room_limit'  => 20,
            'tagline'     => 'Pour les hôtels en croissance',
            'popular'     => true,
            'features'    => [
                'De 11 à 20 chambres',
                'Restaurant & housekeeping',
                'Rapports avancés',
                'Support prioritaire',
            ],
        ],
        'business' => [
            'key'         => 'business',
            'name'        => 'Business',
            'price'       => 60000,
            'currency'    => 'CFA',
            'room_min'    => 21,
            'room_max'    => null,
            'room_limit'  => null,
            'tagline'     => 'Pour les grands établissements',
            'features'    => [
                'Plus de 20 chambres',
                'Chambres illimitées',
                'Toutes les fonctionnalités',
                'Accompagnement dédié',
            ],
        ],
    ],
];
