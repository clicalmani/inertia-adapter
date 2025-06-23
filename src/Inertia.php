<?php
namespace Inertia;

use Clicalmani\Foundation\Support\Facades\Facade;

/**
 * @method static \Inertia\Response render(string $component, array $props = []) 
 * @method static \Inertia\Response withViewData(array $data)
 * @method static \Inertia\Response location(string $url)
 * @method static void setRootView(string $rootView)
 * @method static void share(\Closure $callback)
 * @method static null|string|void version(?string $version = null)
 * @method static void encryptHistory(bool $encrypt = false)
 * @method static void clearHistory(bool $clear = false)
 */
class Inertia extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'inertia';
    }
}