<?php
namespace Inertia;

use Clicalmani\Core\Resources\View;
use Inertia\Support\Header;

class Response extends \Clicalmani\Core\Http\Response
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
        return $this;
    }

    public function withViewData(array $data) : self
    {
        $this->viewData += $data;
        return $this;
    }

    public function location(string $url): never
    {
        $this->header(Header::LOCATION->value, $url);
        $this->sendStatus(409);
        $this->sendHeaders();
        exit;
    }

    public function __toString()
    {
        $page = $this->componentData->toArray();
        $isInertia = \Clicalmani\Core\Http\Request::current()?->hasHeader(Header::INERTIA->value);

        $routes = [];
        $current = [];

        if (class_exists(\Tonka\Spark\TemplateTags\SparkRoutes::class)) {
            $routes = json_decode((new \Tonka\Spark\TemplateTags\SparkRoutes)->render([]))?->routes ?? [];
            $current = json_decode((new \Tonka\Spark\TemplateTags\CurrentRoute)->render([]), true) ?? [];
        }
        
        $errors = json_decode(session()->get('errors', '{}'), true) ?: [];
        session()->set('errors', '{}');
        
        $page['props']['routes'] = $routes;
        $page['props']['currentRoute'] = $current;
        $page['props']['errors'] = (object) $errors;
        
        if ($isInertia) {
            $this->sendStatus();
            $this->withHeaders([
                Header::INERTIA->value => 'true',
                'Vary' => Header::INERTIA->value,
                'Content-Type' => 'application/json'
            ]);
            $this->sendHeaders();
            $json = json_encode($page, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $this->body->write($json);
            return $json;
        }

        $componentData = $this->componentData->toArray();
        session()->set('__componentData', json_encode($componentData));
        $this->sendStatus();
        $this->sendHeaders();
        $this->body->write(new View(self::$rootView, ['page' => $componentData] + $this->viewData));
    }

    public function getComponentData(): ComponentData
    {
        return $this->componentData;
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
    public function share(array|\Closure $callback) : void
    {
        app()->viewSharedData(is_array($callback) ? $callback : $callback->bindTo(null));
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