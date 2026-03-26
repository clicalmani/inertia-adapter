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
        $resource = 'resources/js/' . trim(@$matches[1] ?? 'app.tsx', " '\"");
        $manifestPath = public_path('build/manifest.json');

        // if (! file_exists($manifestPath)) {
        //     throw new \Exception('Vite manifest not found. Please run "npm run build" to generate the manifest.');
        // }

        if (file_exists($manifestPath)) { // Production
            $manifest = json_decode(file_get_contents($manifestPath), true);

            if (isset($manifest[$resource])) {
                $url = $manifest[$resource]['file'];

                if (!str_starts_with($url, '/')) {
                    $url = '/' . $url;
                }

                $url = '/build' . $url;

                return sprintf('<script type="module" src="%s"></script>', $url);
            }

            return sprintf('<script type="module" src="%s"></script>', assets($resource));
        }

        return sprintf(
                <<<'HTML'
                <script type="module" src="%s/%s"></script>
                HTML,
                env('ASSET_URL'),
                $resource
            );
    }
}