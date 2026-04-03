<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Statamic\Tags\Loader;
use Statamic\Tags\Partial;
use Statamic\View\Antlers\Language\Analyzers\NodeTypeAnalyzer;
use Statamic\View\Antlers\Language\Lexer\AntlersLexer;
use Statamic\View\Antlers\Language\Parser\DocumentParser;
use Statamic\View\Antlers\Language\Parser\LanguageParser;
use Statamic\View\Antlers\Language\Runtime\EnvironmentDetails;
use Statamic\View\Antlers\Language\Runtime\NodeProcessor;
use Statamic\View\Antlers\Language\Runtime\RuntimeParser;

final class AntlersRenderer
{
    private static ?RuntimeParser $parser = null;

    /**
     * Render an Antlers template string with the given variables.
     */
    public static function render(string $template, array $variables = []): string
    {
        $parser = self::parser();

        return trim((string) $parser->parse($template, $variables));
    }

    /**
     * Render an Antlers component file by name (e.g. 'linked-section').
     */
    public static function renderComponent(string $name, array $variables = []): string
    {
        $root = dirname(__DIR__, 3);
        $path = $root . '/resources/antlers/cdu/' . $name . '.antlers.html';

        if (!file_exists($path)) {
            throw new \InvalidArgumentException("Antlers component not found: $path");
        }

        return self::render(file_get_contents($path), $variables);
    }

    private static function parser(): RuntimeParser
    {
        if (self::$parser !== null) {
            return self::$parser;
        }

        $container = Container::getInstance();
        Facade::setFacadeApplication($container);

        // Bind a null logger so Log::warning() calls in NodeProcessor don't crash
        $container->singleton('log', fn () => new class {
            public function __call(string $method, array $args): void {}
        });

        // Bind minimal config for Antlers runtime guards
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

        // Register known tags
        $envDetails = new EnvironmentDetails();
        $envDetails->setTagNames(['partial']);
        $envDetails->setModifierNames([]);
        NodeTypeAnalyzer::$environmentDetails = $envDetails;

        $container->singleton('statamic.tags', fn () => collect([
            'partial' => Partial::class,
        ]));

        $nodeProcessor = new NodeProcessor(new Loader(), $envDetails);

        self::$parser = new RuntimeParser(
            new DocumentParser(),
            $nodeProcessor,
            new AntlersLexer(),
            new LanguageParser(),
        );

        return self::$parser;
    }
}
