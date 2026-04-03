<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Contracts\View\Engine as EngineInterface;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Facade;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Statamic\Tags\Loader;
use Statamic\Tags\Partial;
use Statamic\View\Antlers\Language\Analyzers\NodeTypeAnalyzer;
use Statamic\View\Antlers\Language\Lexer\AntlersLexer;
use Statamic\View\Antlers\Language\Parser\DocumentParser;
use Statamic\View\Antlers\Language\Parser\LanguageParser;
use Statamic\View\Antlers\Language\Runtime\EnvironmentDetails;
use Statamic\View\Antlers\Language\Runtime\NodeProcessor;
use Statamic\View\Antlers\Language\Runtime\RuntimeParser;

$root = __DIR__ . '/../..';

// --- Container + facades ---
$container = Container::getInstance();
Facade::setFacadeApplication($container);

$container->singleton('log', fn () => new class {
    public function __call(string $method, array $args): void {}
});

$container->singleton('config', fn () => new Repository([
    'statamic.antlers' => [
        'guardedVariables' => [],
        'guardedTags' => [],
        'guardedModifiers' => [],
        'guardedContentVariables' => [],
        'guardedContentTags' => [],
        'guardedContentModifiers' => [],
        'allowedContentTags' => [],
        'allowedContentModifiers' => [],
    ],
]));

// Stub debugbar so Engine::renderTag() and Hookable don't crash
$container->singleton('debugbar', fn () => new class {
    public function startMeasure(...$args): void {}
    public function stopMeasure(...$args): void {}
    public function isEnabled(): bool { return false; }
    public function addMessage(...$args): void {}
});

// --- Antlers parser setup ---
$envDetails = new EnvironmentDetails();
$envDetails->setTagNames(['partial']);
$envDetails->setModifierNames([]);
NodeTypeAnalyzer::$environmentDetails = $envDetails;

$container->singleton('statamic.tags', fn () => collect([
    'partial' => Partial::class,
]));

// Stubs for Statamic internals that tags/hooks rely on
$container->singleton('statamic.hooks', fn () => []);

$nodeProcessor = new NodeProcessor(new Loader(), $envDetails);
$runtimeParser = new RuntimeParser(new DocumentParser(), $nodeProcessor, new AntlersLexer(), new LanguageParser());

$container->instance(\Statamic\Contracts\View\Antlers\Parser::class, $runtimeParser);

// --- Lightweight Antlers engine for the View Factory ---
// Reads the file, strips YAML front matter, and runs through the RuntimeParser.
$filesystem = new Filesystem();

$antlersEngine = new class($filesystem, $runtimeParser) implements EngineInterface {
    private Filesystem $files;
    private RuntimeParser $parser;
    private array $extractionStack = [];

    public function __construct(Filesystem $files, RuntimeParser $parser)
    {
        $this->files = $files;
        $this->parser = $parser;
    }

    public function withoutExtractions(): static
    {
        $this->extractionStack[] = true;
        return $this;
    }

    public function get($path, array $data = []): string
    {
        $contents = $this->files->get($path);

        // Strip YAML front matter (---...\n---) if present
        if (str_starts_with($contents, '---')) {
            $end = strpos($contents, '---', 3);
            if ($end !== false) {
                $contents = substr($contents, $end + 3);
            }
        }

        array_pop($this->extractionStack);

        return (string) $this->parser->parse($contents, $data);
    }
};

// --- View Factory with antlers.html support ---
$resolver = new EngineResolver();
$resolver->register('antlers', fn () => $antlersEngine);

$finder = new FileViewFinder($filesystem, [
    $root . '/resources/antlers',
]);
$finder->addExtension('antlers.html');

$dispatcher = new Dispatcher($container);
$factory = new Factory($resolver, $finder, $dispatcher);
$factory->setContainer($container);
$factory->addExtension('antlers.html', 'antlers');

$container->instance('view', $factory);
$container->instance(\Illuminate\Contracts\View\Factory::class, $factory);

// Add withoutExtractions() macro so Partial::render()'s view()->withoutExtractions() works
\Illuminate\View\View::macro('withoutExtractions', function () {
    return $this;
});

// Bind Cascade facade to a simple key-value store so Engine references don't crash
$container->singleton(\Statamic\View\Cascade::class, fn () => new class {
    private array $data = [];
    public function get($key, $default = null) { return $this->data[$key] ?? $default; }
    public function set($key, $value): void { $this->data[$key] = $value; }
    public function all(): array { return $this->data; }
    public function hydrate(): static { return $this; }
    public function toArray(): array { return $this->data; }
    public function instance(): static { return $this; }
});

// --- Render ---
// Mark as trusted content so partial tags aren't blocked by the runtime guard
\Statamic\View\Antlers\Language\Runtime\GlobalRuntimeState::$isEvaluatingUserData = false;

$templateContent = file_get_contents($root . '/resources/antlers/examples/kitchen-sink.antlers.html');
$html = (string) $runtimeParser->parse($templateContent, []);

// Inject stylesheet link for the built CSS
echo str_replace('</head>', '    <link rel="stylesheet" href="/styles.css">' . "\n" . '</head>', $html);
