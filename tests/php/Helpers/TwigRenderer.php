<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class TwigRenderer
{
    private static ?Environment $twig = null;

    public static function render(string $twigTemplate, array $context = []): string
    {
        $twig = self::environment();
        $template = $twig->createTemplate($twigTemplate);

        return trim($template->render($context));
    }

    private static function environment(): Environment
    {
        if (self::$twig !== null) {
            return self::$twig;
        }

        $root = dirname(__DIR__, 3);

        $loader = new FilesystemLoader($root . '/resources/twig');
        $loader->addPath($root . '/resources/twig/components', 'cdu');

        self::$twig = new Environment($loader, [
            'cache' => sys_get_temp_dir() . '/cdu-twig-test-cache',
            'auto_reload' => true,
        ]);

        return self::$twig;
    }
}
