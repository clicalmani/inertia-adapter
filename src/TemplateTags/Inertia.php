<?php
namespace Inertia\TemplateTags;

use Clicalmani\Foundation\Resources\TemplateTag;

class Inertia extends TemplateTag
{
    /**
     * Tag expression
     * 
     * @var string
     */
    protected string $tag = '@inertia\b';

    /**
     * Render a tag
     * 
     * @return string
     */
    public function render() : string
    {
        return "<div id='app' {$this->getAttributes()}></div>";
    }
}