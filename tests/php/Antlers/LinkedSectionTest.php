<?php

declare(strict_types=1);

use Tests\Helpers\AntlersRenderer;

it('renders title and href', function () {
    $html = AntlersRenderer::renderComponent('linked-section', [
        'title' => 'Contact',
        'href' => '/contact',
    ]);

    expect($html)
        ->toContain('Contact')
        ->toContain('href="/contact"');
});

it('renders link_attr on the anchor tag', function () {
    $html = AntlersRenderer::renderComponent('linked-section', [
        'title' => 'Contact',
        'href' => '/contact',
        'link_attr' => 'target="_blank" rel="noopener"',
    ]);

    expect($html)
        ->toContain('target="_blank"')
        ->toContain('rel="noopener"');
});

it('toggles btn-gold class when has_button is true', function () {
    $html = AntlersRenderer::renderComponent('linked-section', [
        'title' => 'Shop',
        'href' => '/shop',
        'has_button' => true,
    ]);

    expect($html)
        ->toContain('has-button')
        ->toContain('btn-gold')
        ->not->toContain('btn-link');
});

it('renders btn-link class when has_button is false', function () {
    $html = AntlersRenderer::renderComponent('linked-section', [
        'title' => 'More',
        'href' => '/more',
        'has_button' => false,
    ]);

    expect($html)
        ->toContain('btn-link')
        ->not->toContain('btn-gold');
});

it('renders custom link_text instead of title', function () {
    $html = AntlersRenderer::renderComponent('linked-section', [
        'title' => 'Section Title',
        'href' => '/page',
        'link_text' => 'Read more',
    ]);

    expect($html)->toContain('Read more');
});
