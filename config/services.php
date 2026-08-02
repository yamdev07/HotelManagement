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

    /*
    | WhatsApp — notifications automatiques (Meta WhatsApp Cloud API, officiel/gratuit).
    | On appelle l'API Graph via le client HTTP de Laravel (pas de SDK).
    | Non configuré (token/phone_id vides) => aucun envoi (dégradation silencieuse).
    | 'templates' : noms des modèles approuvés (envoi business-initiated). Vide => texte simple.
    */
    'whatsapp' => [
        'token' => env('WHATSAPP_TOKEN'),
        'phone_id' => env('WHATSAPP_PHONE_ID'),
        'api_version' => env('WHATSAPP_API_VERSION', 'v21.0'),
        'default_country' => env('WHATSAPP_DEFAULT_COUNTRY', '229'), // Bénin par défaut
    ],

    /*
    | Groq — assistant IA de la vitrine (API compatible OpenAI, très rapide).
    | Clé sur https://console.groq.com. Non configuré (clé vide) => assistant masqué.
    */
    'groq' => [
        'key' => env('GROQ_API_KEY'),
        'model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
        'base_url' => env('GROQ_BASE_URL', 'https://api.groq.com/openai/v1'),
    ],

];
