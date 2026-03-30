<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;

$root = __DIR__ . '/../..';
$cacheDir = sys_get_temp_dir() . '/cdu-blade-cache';

if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0755, true);
}

$container = Container::getInstance();

// ComponentTagCompiler calls Application::getNamespace() before checking anonymous
// component paths. Bind a minimal stub so it returns 'App\' — class_exists() then
// returns false and the compiler falls through to the anonymous path lookup.
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

// Bind factory BEFORE anonymousComponentPath() — that method immediately
// calls Container::make(ViewFactory::class) to register the view namespace.
$container->instance('view', $factory);
$container->instance(\Illuminate\Contracts\View\Factory::class, $factory);
$container->instance(\Illuminate\View\Compilers\BladeCompiler::class, $compiler);
$container->instance('blade.compiler', $compiler);

// Register anonymous component path so <x-cdu::button> etc. resolve
$compiler->anonymousComponentPath($root . '/resources/blade/components', 'cdu');

$html = $factory->make('examples.kitchen-sink')->render();

// Inject stylesheet link for the built CSS
echo str_replace('</head>', '    <link rel="stylesheet" href="/styles.css">' . "\n" . '</head>', $html);
