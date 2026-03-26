<?php
namespace Inertia;

use Clicalmani\Foundation\Providers\ServiceProvider;

class InertiaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        \Clicalmani\Foundation\Resources\Kernel::$template_tags = array_merge(
            \Clicalmani\Foundation\Resources\Kernel::$template_tags,
            [
                \Inertia\TemplateTags\InertiaHead::class,
                \Inertia\TemplateTags\Inertia::class,
                \Inertia\TemplateTags\Vite::class,
                \Inertia\TemplateTags\ReactRefresh::class,
            ]
        );
    }
}