<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Redis Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configure Redis caching for optimal performance
    |
    */

    'default' => env('CACHE_DRIVER', 'redis'),

    'stores' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
            'lock_connection' => 'default',
        ],

        'redis_sessions' => [
            'driver' => 'redis',
            'connection' => 'sessions',
        ],

        'redis_queue' => [
            'driver' => 'redis',
            'connection' => 'queue',
        ],
    ],

    'ttl' => [
        'user' => 7200, // 2 hours
        'attendance' => 1800, // 30 minutes
        'report' => 3600, // 1 hour
        'default' => 3600, // 1 hour
    ],

    'prefix' => env('CACHE_PREFIX', 'dutacomputer_'),

    'key_patterns' => [
        'user:*',
        'attendances:*',
        'stats:*',
        'report:*',
    ],
];
