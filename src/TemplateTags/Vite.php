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

        if (app()->environment('production') && file_exists($manifestPath)) { // Production
            $manifest = json_decode(file_get_contents($manifestPath), true);

            if (isset($manifest[$resource])) {
                $url = $manifest[$resource]['file'];

                if (!str_starts_with($url, '/')) {
                    $url = '/' . $url;
                }

                $url = '/build' . $url;
                $ret = sprintf('<script type="module" src="%s"></script>', $url);

                if (isset($manifest['resources/sass/app.scss'])) {
                    $ret .= sprintf('<link rel="stylesheet" href="./build/%s">', $manifest['resources/sass/app.scss']);
                }

                return $ret;
            }

            return sprintf('<script type="module" src="%s"></script>', assets($resource));
        }

        $app_style = '';

        if ( is_file(resources_path('/sass/app.scss')) ) {
            $app_style = sprintf('<link rel="stylesheet" href="%s/%s">', env('ASSET_URL'), 'resources/sass/app.scss');
        }

        return sprintf(
                <<<'HTML'
                <script type="module" src="%s/%s"></script> %s
                HTML,
                env('ASSET_URL'),
                $resource,
                $app_style
            );
    }
}