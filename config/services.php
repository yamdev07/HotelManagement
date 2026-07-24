<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    | FedaPay — paiement des abonnements SaaS (Afrique de l'Ouest).
    | env : 'sandbox' (test) ou 'live' (production).
    | On appelle l'API REST via le client HTTP de Laravel (pas de SDK à installer).
    */
    'fedapay' => [
        'secret' => env('FEDAPAY_SECRET'),
        'public' => env('FEDAPAY_PUBLIC'),
        'env' => env('FEDAPAY_ENV', 'sandbox'),
    ],

];
