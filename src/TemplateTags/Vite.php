<?php
namespace Inertia\TemplateTags;

use Clicalmani\Core\Resources\TemplateTag;

class Vite extends TemplateTag
{
    /**
     * Tag expression
     * 
     * @var string
     */
    protected string $tag = '@vite\s*\(\s*(.*?)\s*\)';

    /**
     * Render the directive output for Vite assets.
     *
     * @param array $matches Matches captured by the template tag regex pattern.
     * @return string
     */
    public function render(array $matches): string
    {
        $args = $this->parseArgs($matches[1] ?? '');

        if (empty($args)) {
            $args = ['resources/js/app.tsx'];
        }
        
        $resources = array_map(function (string $arg): string {
            $arg = trim($arg, " '\"");
            if (str_starts_with($arg, 'resources/')) {
                return $arg;
            }
            return 'resources/js/' . $arg;
        }, $args);

        $manifestPath = public_path('build/manifest.json');

        if (app()->environment('production') && file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
            $out = '';

            foreach ($resources as $resource) {
                if (!isset($manifest[$resource])) {
                    $out .= sprintf(
                        '<script type="module" src="%s"></script>',
                        assets($resource)
                    );
                    continue;
                }

                $entry = $manifest[$resource];

                // Case 1: Entry is a CSS file -> <link> tag
                if (str_ends_with($entry['file'], '.css')) {
                    $out .= sprintf(
                        '<link rel="stylesheet" href="/build/%s">',
                        ltrim($entry['file'], '/')
                    );
                    continue;
                }

                // Case 2: Entry is a JS file -> <script> tag, plus any associated imported CSS files
                $url = '/' . ltrim($entry['file'], '/');
                $out .= sprintf(
                    '<script type="module" src="/build%s"></script>',
                    $url
                );

                foreach ($entry['css'] ?? [] as $css) {
                    $out .= sprintf(
                        '<link rel="stylesheet" href="/build/%s">',
                        ltrim($css, '/')
                    );
                }
            }

            return $out;
        }
        
        $assetUrl = rtrim(env('ASSET_URL', ''), '/');
        $out = '';

        foreach ($resources as $resource) {
            if (str_ends_with($resource, '.css')) {
                $out .= sprintf(
                    '<link rel="stylesheet" href="%s/%s">',
                    $assetUrl,
                    $resource
                );
            } else {
                $out .= sprintf(
                    '<script type="module" src="%s/%s"></script>',
                    $assetUrl,
                    $resource
                );
            }
        }

        return $out;
    }

    /**
     * Splits a comma-separated argument string while respecting quoted substrings.
     *
     * @param string $raw The raw arguments string.
     * @return string[] List of parsed arguments.
     */
    protected function parseArgs(string $raw): array
    {
        $raw = trim($raw);

        if ($raw === '') {
            return [];
        }

        // preg_split on commas located outside single or double quotes
        $parts = preg_split(
            '/\s*,\s*(?=(?:[^\'"]*[\'"][^\'"]*[\'"])*[^\'"]*$)/',
            $raw
        );

        return array_values(array_filter(array_map(
            fn ($p) => trim(trim($p), " '\""),
            $parts ?: []
        ), fn ($p) => $p !== ''));
    }
}