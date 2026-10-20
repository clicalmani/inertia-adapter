<?php

declare(strict_types=1);

/**
 * Configuration cache de l'application factice (tests/Fixtures/app).
 */
return [
    'default' => 'array',

    'prefix'  => 'inertia_tests_',

    'stores'  => [
        'array'    => ['driver' => 'array'],

        'file'     => ['driver' => 'file'],

        'database' => [
            'driver'   => 'database',
            'table'    => 'cache',
            'lifetime' => 0,
            'lock'     => true,
        ],

        'chain'    => [
            'driver'         => 'chain',
            'stores'         => ['array', 'file'],
            'default_lifetime' => 0,
        ],
    ],
];
