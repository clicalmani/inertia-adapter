<?php
namespace Inertia;

use Clicalmani\Foundation\Http\Request;

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
        $this->component = $name;
    }

    /**
     * Set version
     * 
     * @param string $version
     * @return void
     */
    public static function setVersion(string $version = '1.0') : void
    {
        self::$version = $version;
    }

    public static function getVersion() : ?string
    {
        return self::$version;
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
        if ($request = Request::currentRequest() AND $errorBag = $request->input('errorBag')) {
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
            'props' => $this->props + ['errors' => self::$errors],
            'url' => client_uri(),
            'version' => self::$version ?? str_shuffle( time() )
        ];
    }
}