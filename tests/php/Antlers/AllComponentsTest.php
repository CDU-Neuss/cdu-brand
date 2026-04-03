<?php

declare(strict_types=1);

use Tests\Helpers\AntlersRenderer;

dataset('antlers-components', [
    'button'             => ['button', ['href' => '/x'], 'btn'],
    'icon-circle'        => ['icon-circle', [], 'icon-circle'],
    'feature'            => ['feature', ['title' => 'T'], 'feature'],
    'cta'                => ['cta', ['title' => 'T'], 'cta'],
    'linked-section'     => ['linked-section', ['title' => 'T', 'href' => '/x'], 'linked-section'],
    'countdown'          => ['countdown', ['target_date' => '2030-01-01'], 'countdown'],
    'authors'            => ['authors', ['authors' => []], 'authors'],
    'eye-catcher-circle' => ['eye-catcher-circle', [], 'eye-catcher-circle'],
]);

it('renders without error and contains root CSS class', function (string $component, array $vars, string $expectedClass) {
    $html = AntlersRenderer::renderComponent($component, $vars);

    expect($html)->toContain($expectedClass);
})->with('antlers-components');
