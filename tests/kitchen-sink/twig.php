<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$root = __DIR__ . '/../..';

$loader = new FilesystemLoader($root . '/resources/twig');
$loader->addPath($root . '/resources/twig/components', 'cdu');

$twig = new Environment($loader, [
    'cache'       => sys_get_temp_dir() . '/cdu-twig-cache',
    'auto_reload' => true,
]);

echo $twig->render('examples/kitchen-sink.twig');
