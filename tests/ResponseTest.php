<?php

declare(strict_types=1);

namespace Inertia\Tests;

use Clicalmani\Core\Http\Session;
use Inertia\Response;
use Inertia\Support\Header;

/**
 * Couvre la branche XHR de Response::__toString().
 *
 * Chaque test enregistre d'abord une requête courante portant X-Inertia :
 * sans cet en-tête, la réponse basculerait sur le rendu Twig de la vue
 * racine (echo + exit, impossible sous PHPUnit).
 */
class ResponseTest extends TestCase
{
    public function test_xhr_response_returns_json_page_object(): void
    {
        $_SERVER['REQUEST_URI'] = '/users?page=2&tab=active';
        $this->inertiaRequest([Header::INERTIA->value => 'true']);

        $response = (new Response)->render('Users/Index', ['users' => ['Ada', 'Grace']]);
        $page = json_decode((string) $response, true);

        $this->assertSame('Users/Index', $page['component']);
        $this->assertSame(['Ada', 'Grace'], $page['props']['users']);
        $this->assertSame('/users?page=2&tab=active', $page['url']);
        $this->assertNull($page['version']);
    }

    public function test_xhr_response_string_is_the_json_body_sent_by_die(): void
    {
        $this->inertiaRequest([Header::INERTIA->value => 'true']);

        $response = (new Response)->render('Users/Index');
        $json = (string) $response;

        // Le framework envoie les réponses avec die($response) : la chaîne
        // retournée par __toString() EST le corps de la réponse.
        $this->assertNotSame('', $json);
        $this->assertJson($json);

        $page = json_decode($json, true);
        $this->assertSame('Users/Index', $page['component']);
    }

    public function test_xhr_response_carries_inertia_headers(): void
    {
        $this->inertiaRequest([Header::INERTIA->value => 'true']);

        $response = (new Response)->render('Users/Index');
        (string) $response;

        $headers = $response->getHeaders();

        $this->assertSame('true', $headers['x-inertia']);
        $this->assertSame('X-Inertia', $headers['vary']);
        $this->assertSame('application/json', $headers['content-type']);
    }

    public function test_session_errors_are_exposed_in_props_then_cleared(): void
    {
        $_SESSION['errors'] = json_encode(['email' => 'The email field is required.']);
        $this->inertiaRequest([Header::INERTIA->value => 'true']);

        $response = (new Response)->render('Users/Index');
        $page = json_decode((string) $response, true);

        $this->assertSame(
            ['email' => 'The email field is required.'],
            (array) $page['props']['errors']
        );
        $this->assertSame('{}', (new Session)->get('errors'));
    }

    public function test_response_version_accessor_updates_page_version(): void
    {
        $this->inertiaRequest([Header::INERTIA->value => 'true']);

        $response = new Response;
        $response->version('1.2.3');
        $response->render('Users/Index');

        $page = json_decode((string) $response, true);

        $this->assertSame('1.2.3', $page['version']);
    }
}
