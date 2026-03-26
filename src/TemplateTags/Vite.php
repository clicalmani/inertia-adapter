<?php
namespace Inertia\TemplateTags;

use Clicalmani\Foundation\Resources\TemplateTag;

class Vite extends TemplateTag
{
    /**
     * Tag expression
     * 
     * @var string
     */
    protected string $tag = '@vite\s*\(\s*(.*)\s*\)';

    public function render(array $matches) : string
    {
        return sprintf(
                <<<'HTML'
                <script type="module" src="%s/%s"></script>
                HTML,
                env('ASSET_URL'),
                trim(@$matches[1] ?? '', " '\"")
            );
    }
}