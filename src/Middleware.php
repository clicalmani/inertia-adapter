<?php
namespace Inertia;

use Clicalmani\Core\Http\Middlewares\Middleware as BaseMiddleware;
use Clicalmani\Core\Http\RedirectInterface;
use Clicalmani\Core\Http\RequestInterface;
use Clicalmani\Core\Http\ResponseInterface;
use Inertia\Response as InertiaResponse;

class Middleware extends BaseMiddleware
{
    /**
     * Handler
     * 
     * @param \Clicalmani\Core\Http\Requests\RequestInterface $request Request object
     * @param \Clicalmani\Core\Http\ResponseInterface $response Response object
     * @param \Closure $next Next middleware function
     * @return \Clicalmani\Core\Http\ResponseInterface|\Clicalmani\Core\Http\RedirectInterface
     */
    public function handle(RequestInterface $request, ResponseInterface $response, \Closure $next) : ResponseInterface|RedirectInterface
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