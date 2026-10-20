<?php
namespace Inertia;

use Clicalmani\Core\Http\Request;
use Inertia\Support\Header;

class ComponentData
{
    /**
     * Component
     * 
     * @var string
     */
    private string $component = '';

    /**
     * Version
     * 
     * @var ?string
     */
    private static ?string $version = null;

    /**
     * Encrypt history
     * 
     * @var ?bool
     */
    private static ?bool $encryptHistory = null;

    /**
     * Clear history
     * 
     * @var ?bool
     */
    private static ?bool $clearHistory = null;

    /**
     * @var array
     */
    private static array $errors = [];

    /**
     * Properties
     * 
     * @var array
     */
    private array $props = [];

    /**
     * Set component
     * 
     * @param string $name
     * @return void
     */
    public function setComponent(string $name) : void
    {
        $this->component = str_replace('.', '/', trim($name, '/'));
    }

    public function getComponent(): string
    {
        return $this->component;
    }

    /**
     * Set version
     * 
     * @param string $version
     * @return void
     */
    public static function setVersion(string $version = '1.0') : void
    {
        static::$version = $version;
    }

    public static function getVersion() : ?string
    {
        return static::$version;
    }

    /**
     * Set props
     * 
     * @param array $props
     * @return void
     */
    public function setProps(array $props) : void
    {
        $this->props += $props;
    }

    public function getProps(): array
    {
        return $this->props;
    }

    /**
     * Encrypt history
     * 
     * @param bool $encrypt Default false
     * @return void
     */
    public static function encryptHistory(bool $encrypt = false) : void
    {
        self::$encryptHistory = $encrypt;
    }

    /**
     * Clear history
     * 
     * @param bool $clear
     * @return void
     */
    public static function clearHistory(bool $clear = false) : void
    {
        self::$clearHistory = $clear;
    }

    /**
     * Add error message
     * 
     * @param string $field
     * @param string $error_msg
     * @return void
     */
    public static function addError(string $field, string $error_msg) : void
    {
        if ($request = Request::current() AND $errorBag = $request->getHeaderLine(Header::ERROR_BAG)) {
            static::$errors[$errorBag][$field] = $error_msg;
        } else static::$errors[$field] = $error_msg;
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }

    public function toArray() : array
    {
        return [
            'component' => $this->component,
            'props' => $this->resolvePartialProps(),
            'url' => $_SERVER['REQUEST_URI'] ?? '/',
            'version' => static::$version
        ];
    }

    /**
     * @return array
     */
    private function resolvePartialProps() : array
    {
        $request = Request::current();

        if (null === $request OR $request->getHeaderLine(Header::PARTIAL_COMPONENT->value) !== $this->component) {
            return $this->props;
        }

        $only = array_filter(array_map('trim', explode(',', $request->getHeaderLine(Header::PARTIAL_ONLY->value))));

        if (empty($only) OR empty($only = array_intersect($only, array_keys($this->props)))) {
            return $this->props;
        }

        $only = array_flip($only);
        $except = array_flip(array_filter(array_map('trim', explode(',', $request->getHeaderLine(Header::PARTIAL_EXCEPT->value)))));

        return array_filter(
            $this->props,
            fn (string $key) : bool => isset($only[$key]) && ! isset($except[$key]),
            ARRAY_FILTER_USE_KEY
        );
    }
}