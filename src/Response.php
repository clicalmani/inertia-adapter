<?php
namespace Inertia;

use Clicalmani\Foundation\Resources\View;

class Response extends \Clicalmani\Foundation\Http\Response
{

    /**
     * Root view
     * 
     * @var string
     */
    private static string $rootView = 'app';

    /**
     * Component data
     * 
     * @var \Inertia\ComponentData
     */
    private \Inertia\ComponentData $componentData;

    /**
     * @var array
     */
    private array $viewData = [];

    public function render(string $component, array $props = [])
    {
        $this->componentData = new ComponentData;
        $this->componentData->setComponent($component);
        $this->componentData->setProps(array_merge($props, app()->viewSharedData()));
        return $this->withAddedHeader('X-Inertia', 'true');
    }

    public function withViewData(array $data) : self
    {
        $this->viewData += $data;
        return $this;
    }

    public function location(string $url) : self
    {
        $this->componentData = new ComponentData;
        return $this->withHeader('X-Inertia-Location', $url)
                    ->withStatus(409);
    }

    public function __toString()
    {
        $componentData = $this->componentData->toArray();
        session()->set('__componentData', json_encode($componentData));
        $this->sendStatus();
        $this->sendHeaders();
        $this->body->write(new View(self::$rootView, ['page' => $componentData] + $this->viewData));
    }

    /**
     * Set root view
     * 
     * @param string $rootView
     * @return void
     */
    public function setRootView(string $rootView) : void
    {
        static::$rootView = $rootView;
    }

    /**
     * Shared view data
     * 
     * @param \Closure $callback
     * @return void
     */
    public function share(\Closure $callback) : void
    {
        app()->viewSharedData($callback);
    }

    public function version(?string $version = null)
    {
        if (isset($version)) {
            ComponentData::setVersion($version);
        } else return ComponentData::getVersion();
    }

    public function encryptHistory(bool $encrypt = false) : void
    {
        ComponentData::encryptHistory($encrypt);
    }

    public function clearHistory(bool $clear = false) : void
    {
        ComponentData::clearHistory($clear);
    }
}