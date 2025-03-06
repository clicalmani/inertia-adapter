<?php
namespace Inertia;

use Clicalmani\Foundation\Http\Middlewares\Middleware as BaseMiddleware;
use Clicalmani\Foundation\Http\RedirectInterface;
use Clicalmani\Foundation\Http\Request;
use Clicalmani\Foundation\Http\Response;
use Inertia\Response as InertiaResponse;

class Middleware extends BaseMiddleware
{
    /**
     * Handler
     * 
     * @param \Clicalmani\Foundation\Http\Request $request Current request object
     * @param \Clicalmani\Foundation\Http\Response $response Http response
     * @param \Closure $next 
     * @return \Clicalmani\Foundation\Http\Response|\Clicalmani\Foundation\Http\RedirectInterface
     */
    public function handle(Request $request, Response $response, \Closure $next) : Response|RedirectInterface
    {
        return $next($request, new InertiaResponse);
    }

    /**
     * Bootstrap
     * 
     * @return void
     */
    public function boot() : void
    {
        /**
         * TODO
         */
    }
}