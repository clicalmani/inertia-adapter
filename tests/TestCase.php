<?php

declare(strict_types=1);

namespace Inertia\Tests;

use Clicalmani\Core\Acme\Configure;
use Clicalmani\Core\Http\Request;
use Inertia\ComponentData;
use PHPUnit\Framework\TestCase as BaseTestCase;
use ReflectionProperty;

/**
 * Socle des tests de l'adaptateur Inertia.
 *
 * L'amorçage (conteneur DI, providers, session, préfixe API) est fait une fois
 * pour toutes dans tests/bootstrap.php. Cette classe assure l'isolation entre
 * tests : superglobales, configuration statique, requête courante et statics
 * de ComponentData (version notamment) sont restaurés après chaque test.
 */
abstract class TestCase extends BaseTestCase
{
    private array $serverSnapshot = [];

    private array $sessionSnapshot = [];

    private array $configSnapshot = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->serverSnapshot  = $_SERVER;
        $this->sessionSnapshot = $_SESSION;
        $this->configSnapshot  = self::configureStorage();

        // URI par défaut hors préfixe API pour que Route::isApi() reste faux
        // pendant les casts (string) des réponses.
        $_SERVER['REQUEST_URI'] = '/users';
    }

    protected function tearDown(): void
    {
        self::setConfigureStorage($this->configSnapshot);

        $this->resetComponentDataStatics();
        $this->resetCurrentRequest();

        $_SERVER  = $this->serverSnapshot;
        $_SESSION = $this->sessionSnapshot;

        parent::tearDown();
    }

    /**
     * Enregistre une requête courante (construite depuis les superglobales,
     * URI = $_SERVER['REQUEST_URI']) et la retourne. Les en-têtes Inertia
     * (X-Inertia, partial reload, ...) se posent via $headers.
     */
    protected function inertiaRequest(array $headers = []): Request
    {
        $request = new Request;

        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        return Request::current($request);
    }

    protected function setConfig(string $key, mixed $value): void
    {
        container()->get('config')->set($key, $value);
    }

    protected function getConfig(?string $key = null, mixed $default = null): mixed
    {
        return container()->get('config')->get($key, $default);
    }

    private function resetComponentDataStatics(): void
    {
        (new ReflectionProperty(ComponentData::class, 'version'))->setValue(null, null);
        (new ReflectionProperty(ComponentData::class, 'encryptHistory'))->setValue(null, null);
        (new ReflectionProperty(ComponentData::class, 'clearHistory'))->setValue(null, null);
        (new ReflectionProperty(ComponentData::class, 'errors'))->setValue(null, []);
    }

    private function resetCurrentRequest(): void
    {
        (new ReflectionProperty(Request::class, 'current_request'))->setValue(null, null);
    }

    private static function configureStorage(): array
    {
        return (new ReflectionProperty(Configure::class, 'storage'))->getValue();
    }

    private static function setConfigureStorage(array $storage): void
    {
        (new ReflectionProperty(Configure::class, 'storage'))->setValue(null, $storage);
    }
}
