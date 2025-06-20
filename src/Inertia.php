<?php
namespace Inertia;

use Clicalmani\Foundation\Support\Facades\Facade;

class Inertia extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'inertia';
    }
    
    /**
     * Shared view data
     * 
     * @param \Closure $callback
     * @return void
     */
    public static function share(\Closure $callback) : void
    {
        app()->viewSharedData($callback);
    }

    public static function version(?string $version = null)
    {
        if (isset($version)) {
            ComponentData::setVersion($version);
        } else return ComponentData::getVersion();
    }

    public static function encryptHistory(bool $encrypt = false) : void
    {
        ComponentData::encryptHistory($encrypt);
    }

    public static function clearHistory(bool $clear = false) : void
    {
        ComponentData::clearHistory($clear);
    }
}