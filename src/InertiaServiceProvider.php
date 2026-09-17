<?php
namespace Inertia;

use Clicalmani\Core\Providers\ServiceProvider;

class InertiaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        \Clicalmani\Core\Resources\Kernel::$template_tags = array_merge(
            \Clicalmani\Core\Resources\Kernel::$template_tags,
            [
                \Inertia\TemplateTags\InertiaHead::class,
                \Inertia\TemplateTags\Inertia::class,
                \Inertia\TemplateTags\Vite::class,
                \Inertia\TemplateTags\ReactRefresh::class,
            ]
        );
    }
}