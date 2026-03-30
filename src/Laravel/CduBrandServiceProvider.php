<?php

declare(strict_types=1);

namespace CduNeuss\CduBrand\Laravel;

use Illuminate\Support\ServiceProvider;

class CduBrandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerBladeComponents();

        if ($this->isStatamicInstalled()) {
            $this->registerAntlersPartials();
        }
    }

    private function registerBladeComponents(): void
    {
        $bladePath = $this->componentPath('blade/components');

        if (is_dir($bladePath)) {
            $this->callAfterResolving('blade.compiler', function ($blade) use ($bladePath) {
                $blade->anonymousComponentPath($bladePath, 'cdu');
            });
        }
    }

    private function registerAntlersPartials(): void
    {
        $antlersPath = $this->componentPath('antlers');

        if (is_dir($antlersPath)) {
            $this->app['view']->addLocation($antlersPath);
        }
    }

    private function isStatamicInstalled(): bool
    {
        return class_exists(\Statamic\Statamic::class);
    }

    private function componentPath(string $relative): string
    {
        return dirname(__DIR__, 2) . '/resources/' . $relative;
    }
}
