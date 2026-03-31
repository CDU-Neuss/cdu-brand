<?php

/**
 * PHP built-in server router for CDU brand kitchen sink preview.
 *
 * Usage:
 *   composer kitchen-sink          — build CSS + start server
 *   composer kitchen-sink:serve    — start server only (skip CSS build)
 *
 * Then open:
 *   http://localhost:8080/         — index
 *   http://localhost:8080/blade    — Blade kitchen sink
 *   http://localhost:8080/twig     — Twig kitchen sink
 */

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Serve built CSS file
if ($path === '/styles.css') {
    $css = __DIR__ . '/styles.css';
    if (file_exists($css)) {
        header('Content-Type: text/css');
        readfile($css);
    } else {
        http_response_code(404);
        echo '/* styles.css not found — run: composer kitchen-sink:build */';
    }
    return;
}

match ($path) {
    '/', '/index' => (function () {
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head><meta charset="utf-8"><title>CDU Brand — Kitchen Sink</title></head>
        <body style="font-family:sans-serif;padding:2rem">
            <h1>CDU Brand — Kitchen Sink</h1>
            <ul>
                <li><a href="/blade">Blade</a></li>
                <li><a href="/twig">Twig</a></li>
            </ul>
        </body>
        </html>
        HTML;
    })(),
    '/blade' => require __DIR__ . '/blade.php',
    '/twig'  => require __DIR__ . '/twig.php',
    default  => (function () {
        http_response_code(404);
        echo '404 Not Found';
    })(),
};
