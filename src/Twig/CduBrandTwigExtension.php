<?php

declare(strict_types=1);

namespace CduNeuss\CduBrand\Twig;

use Twig\Loader\FilesystemLoader;

class CduBrandTwigExtension
{
    public static function registerNamespace(FilesystemLoader $loader): void
    {
        $twigPath = dirname(__DIR__, 2) . '/resources/twig/components';

        if (is_dir($twigPath)) {
            $loader->addPath($twigPath, 'cdu');
        }
    }
}
