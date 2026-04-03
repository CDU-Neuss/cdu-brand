<?php

declare(strict_types=1);

use Tests\Helpers\BladeRenderer;

dataset('blade-components', [
    'button'             => ['<x-cdu::button>Click</x-cdu::button>', 'btn'],
    'icon-circle'        => ['<x-cdu::icon-circle><svg></svg></x-cdu::icon-circle>', 'icon-circle'],
    'feature'            => ['<x-cdu::feature title="T"><x-slot:icon><svg></svg></x-slot:icon>Body</x-cdu::feature>', 'feature'],
    'cta'                => ['<x-cdu::cta title="T">Body</x-cdu::cta>', 'cta'],
    'linked-section'     => ['<x-cdu::linked-section title="T" href="/x"><x-slot:icon><svg></svg></x-slot:icon>Body</x-cdu::linked-section>', 'linked-section'],
    'countdown'          => ['<x-cdu::countdown target-date="2030-01-01" />', 'countdown'],
    'authors'            => ['<x-cdu::authors :authors="[]" />', 'authors'],
    'eye-catcher-circle' => ['<x-cdu::eye-catcher-circle>Hi</x-cdu::eye-catcher-circle>', 'eye-catcher-circle'],
]);

it('renders without error and contains root CSS class', function (string $template, string $expectedClass) {
    $html = BladeRenderer::render($template);

    expect($html)->toContain($expectedClass);
})->with('blade-components');
