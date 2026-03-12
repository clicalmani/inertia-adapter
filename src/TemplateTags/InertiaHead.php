<?php
namespace Inertia\TemplateTags;

use Clicalmani\Foundation\Resources\TemplateTag;

class InertiaHead extends TemplateTag
{
    /**
     * Tag expression
     * 
     * @var string
     */
    protected string $tag = '@inertiaHead';

    /**
     * Render a tag
     * 
     * @return string
     */
    public function render() : string
    {
        return <<<HEAD
        <title>{{ env('APP_NAME') }}</title>
        <meta property="og:locale" content="{{ app().getLocale() }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}"/>
        HEAD;
    }
}