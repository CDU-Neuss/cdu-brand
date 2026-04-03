<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;

final class BladeRenderer
{
    private static ?Factory $factory = null;
    private static string $tempDir;

    public static function render(string $bladeTemplate): string
    {
        $factory = self::factory();

        $tempFile = self::$tempDir . '/' . md5($bladeTemplate) . '.blade.php';
        file_put_contents($tempFile, $bladeTemplate);

        $level = ob_get_level();

        try {
            return trim($factory->file($tempFile)->render());
        } finally {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }
            @unlink($tempFile);
        }
    }

    private static function factory(): Factory
    {
        if (self::$factory !== null) {
            return self::$factory;
        }

        $root = dirname(__DIR__, 3);
        $cacheDir = sys_get_temp_dir() . '/cdu-blade-test-cache';
        self::$tempDir = sys_get_temp_dir() . '/cdu-blade-test-views';

        foreach ([$cacheDir, self::$tempDir] as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }

        $container = Container::getInstance();

        $container->instance(
            \Illuminate\Contracts\Foundation\Application::class,
            new class extends Container {
                public function getNamespace(): string
                {
                    return 'App\\';
                }
            }
        );

        $filesystem = new Filesystem();
        $compiler = new BladeCompiler($filesystem, $cacheDir);

        $resolver = new EngineResolver();
        $resolver->register('php', fn () => new PhpEngine($filesystem));
        $resolver->register('blade', fn () => new CompilerEngine($compiler, $filesystem));

        $finder = new FileViewFinder($filesystem, [$root . '/resources/blade']);
        $dispatcher = new Dispatcher($container);

        $factory = new Factory($resolver, $finder, $dispatcher);
        $factory->setContainer($container);

        $container->instance('view', $factory);
        $container->instance(\Illuminate\Contracts\View\Factory::class, $factory);
        $container->instance(\Illuminate\View\Compilers\BladeCompiler::class, $compiler);
        $container->instance('blade.compiler', $compiler);

        $compiler->anonymousComponentPath($root . '/resources/blade/components', 'cdu');

        self::$factory = $factory;

        return $factory;
    }
}
