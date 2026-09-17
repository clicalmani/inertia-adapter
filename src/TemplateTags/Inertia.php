<?php
namespace Inertia\TemplateTags;

use Clicalmani\Core\Resources\TemplateTag;

class Inertia extends TemplateTag
{
    /**
     * Tag expression
     * 
     * @var string
     */
    protected string $tag = '@inertia';

    /**
     * Render a tag
     * 
     * @return string
     */
    public function render(array $matches) : string
    {
        return "<div id='app' {$this->getAttributes()}></div>";
    }
}