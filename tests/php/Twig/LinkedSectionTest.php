<?php

declare(strict_types=1);

use Tests\Helpers\TwigRenderer;

it('renders title and href', function () {
    $html = TwigRenderer::render(
        "{% embed '@cdu/linked-section.twig' with { title: 'Contact', href: '/contact' } %}{% block icon %}<svg></svg>{% endblock %}{% block content %}Body{% endblock %}{% endembed %}"
    );

    expect($html)
        ->toContain('Contact')
        ->toContain('href="/contact"');
});

it('renders link_attr on the anchor tag', function () {
    $html = TwigRenderer::render(
        "{% embed '@cdu/linked-section.twig' with { title: 'Contact', href: '/contact', link_attr: 'target=\"_blank\" rel=\"noopener\"' } %}{% block icon %}<svg></svg>{% endblock %}{% block content %}Body{% endblock %}{% endembed %}"
    );

    expect($html)
        ->toContain('target="_blank"')
        ->toContain('rel="noopener"');
});

it('toggles btn-gold class when has_button is true', function () {
    $html = TwigRenderer::render(
        "{% embed '@cdu/linked-section.twig' with { title: 'Shop', href: '/shop', has_button: true } %}{% block icon %}<svg></svg>{% endblock %}{% block content %}Body{% endblock %}{% endembed %}"
    );

    expect($html)
        ->toContain('has-button')
        ->toContain('btn-gold')
        ->not->toContain('btn-link');
});

it('renders btn-link class when has_button is false', function () {
    $html = TwigRenderer::render(
        "{% embed '@cdu/linked-section.twig' with { title: 'More', href: '/more' } %}{% block icon %}<svg></svg>{% endblock %}{% block content %}Body{% endblock %}{% endembed %}"
    );

    expect($html)
        ->toContain('btn-link')
        ->not->toContain('btn-gold');
});

it('renders custom link_text instead of title', function () {
    $html = TwigRenderer::render(
        "{% embed '@cdu/linked-section.twig' with { title: 'Section Title', href: '/page', link_text: 'Read more' } %}{% block icon %}<svg></svg>{% endblock %}{% block content %}Body{% endblock %}{% endembed %}"
    );

    expect($html)->toContain('Read more');
});
