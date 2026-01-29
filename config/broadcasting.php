<?php

return [

    'default' => env('BROADCAST_DRIVER', 'null'),

    'connections' => [

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true, // ⚠️ IMPORTANT : true pour Pusher.com
                // ⚠️ SUPPRIMEZ ces 4 lignes :
                // 'host' => env('PUSHER_HOST', '127.0.0.1'),
                // 'port' => env('PUSHER_PORT', 6001),
                // 'scheme' => env('PUSHER_SCHEME', 'http'),
                // 'encrypted' => true,
            ],
        ],

        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];