<?php
namespace Inertia;

use Clicalmani\Foundation\Http\Middlewares\Middleware as BaseMiddleware;
use Clicalmani\Foundation\Http\RedirectInterface;
use Clicalmani\Foundation\Http\RequestInterface;
use Clicalmani\Foundation\Http\ResponseInterface;
use Inertia\Response as InertiaResponse;

class Middleware extends BaseMiddleware
{
    /**
     * Handler
     * 
     * @param \Clicalmani\Foundation\Http\Requests\RequestInterface $request Request object
     * @param \Clicalmani\Foundation\Http\ResponseInterface $response Response object
     * @param \Closure $next Next middleware function
     * @return \Clicalmani\Foundation\Http\ResponseInterface|\Clicalmani\Foundation\Http\RedirectInterface
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