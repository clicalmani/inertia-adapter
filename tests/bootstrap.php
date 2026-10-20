<?php

declare(strict_types=1);

use Clicalmani\Core\Maker\Application;
use Clicalmani\Core\Providers\CacheServiceProvider;
use Clicalmani\Core\Providers\ContainerServiceProvider;
use Clicalmani\Core\Providers\StorageServiceProvider;
use Clicalmani\Core\Providers\ValidationServiceProvider;

require_once __DIR__ . '/../vendor/autoload.php';

// Les helpers du framework (app(), config(), session(), client_url(), ...)
// ne sont jamais chargés par l'autoload PSR-4.
require_once __DIR__ . '/../vendor/clicalmani/foundation/src/helpers.php';

// Racine d'une application factice : fournit config/storage.php et config/cache.php.
if (!defined('TESTS_FIXTURE_ROOT')) {
    define('TESTS_FIXTURE_ROOT', __DIR__ . '/Fixtures/app');
}

// Response::__toString() lit puis vide la clé "errors" de la session.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Les providers doivent être enregistrés AVANT le boot du conteneur, car
// registerCoreContainerServices() fige la liste des services au chargement
// de services.php.
$app = Application::getInstance(TESTS_FIXTURE_ROOT);
$app->register(new StorageServiceProvider);
$app->register(new CacheServiceProvider);

// Conteneur DI cœur (config, logger, storage, cache, ...).
(new ContainerServiceProvider)->boot();

// Registre des règles de validation utilisé par Validator.
(new ValidationServiceProvider)->boot();

// Sans ce préfixe, Router::isApi() matche toutes les URL (preg_match "#^/#"
// avec un préfixe vide) et Response::sendStatus() déraille sur le corps du
// stream. Les URI de test ne commencent jamais par /api.
container()->get('config')->set('route.api_prefix', 'api');
