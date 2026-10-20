<?php

declare(strict_types=1);

/**
 * Configuration disque de l'application factice utilisée par les tests.
 * Racine : tests/Fixtures/app
 */
return [
    'default' => 'local',

    'disks' => [
        'local' => [
            'driver'     => 'local',
            'root'       => dirname(__DIR__) . '/storage/app',
            'visibility' => 'private',
        ],
        'public' => [
            'driver'     => 'local',
            'root'       => dirname(__DIR__) . '/storage/app/public',
            'url'        => '/storage',
            'visibility' => 'public',
        ],
    ],
];
