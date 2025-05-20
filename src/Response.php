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

    public function withViewData(array $data) : static
    {
        $this->viewData += $data;
        return $this;
    }

    public function location(string $url) : static
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
    public static function setRootView(string $rootView) : void
    {
        static::$rootView = $rootView;
    }
}