<?php

declare(strict_types=1);

use Tests\Helpers\BladeRenderer;

it('renders title and href', function () {
    $html = BladeRenderer::render(
        '<x-cdu::linked-section title="Contact" href="/contact"><x-slot:icon><svg></svg></x-slot:icon>Body</x-cdu::linked-section>'
    );

    expect($html)
        ->toContain('Contact')
        ->toContain('href="/contact"');
});

it('renders link_attr on the anchor tag', function () {
    $html = BladeRenderer::render(
        '<x-cdu::linked-section title="Contact" href="/contact" link-attr=\'target="_blank" rel="noopener"\'><x-slot:icon><svg></svg></x-slot:icon>Body</x-cdu::linked-section>'
    );

    expect($html)
        ->toContain('target="_blank"')
        ->toContain('rel="noopener"');
});

it('toggles btn-gold class when hasButton is true', function () {
    $html = BladeRenderer::render(
        '<x-cdu::linked-section title="Shop" href="/shop" :hasButton="true"><x-slot:icon><svg></svg></x-slot:icon>Body</x-cdu::linked-section>'
    );

    expect($html)
        ->toContain('has-button')
        ->toContain('btn-gold')
        ->not->toContain('btn-link');
});

it('renders btn-link class when hasButton is false', function () {
    $html = BladeRenderer::render(
        '<x-cdu::linked-section title="More" href="/more"><x-slot:icon><svg></svg></x-slot:icon>Body</x-cdu::linked-section>'
    );

    expect($html)
        ->toContain('btn-link')
        ->not->toContain('btn-gold');
});

it('renders custom linkText instead of title', function () {
    $html = BladeRenderer::render(
        '<x-cdu::linked-section title="Section Title" href="/page" linkText="Read more"><x-slot:icon><svg></svg></x-slot:icon>Body</x-cdu::linked-section>'
    );

    expect($html)->toContain('Read more');
});
