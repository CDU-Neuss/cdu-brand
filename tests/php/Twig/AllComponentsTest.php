<?php

declare(strict_types=1);

use Tests\Helpers\TwigRenderer;

dataset('twig-components', [
    'button'             => ["{% include '@cdu/button.twig' %}", 'btn'],
    'icon-circle'        => ["{% include '@cdu/icon-circle.twig' %}", 'icon-circle'],
    'feature'            => ["{% embed '@cdu/feature.twig' with { title: 'T' } %}{% block icon %}<svg></svg>{% endblock %}{% block content %}Body{% endblock %}{% endembed %}", 'feature'],
    'cta'                => ["{% embed '@cdu/cta.twig' with { title: 'T' } %}{% block content %}Body{% endblock %}{% endembed %}", 'cta'],
    'linked-section'     => ["{% embed '@cdu/linked-section.twig' with { title: 'T', href: '/x' } %}{% block icon %}<svg></svg>{% endblock %}{% block content %}Body{% endblock %}{% endembed %}", 'linked-section'],
    'countdown'          => ["{% include '@cdu/countdown.twig' with { target_date: '2030-01-01' } %}", 'countdown'],
    'authors'            => ["{% include '@cdu/authors.twig' with { authors: [] } %}", 'authors'],
    'eye-catcher-circle' => ["{% embed '@cdu/eye-catcher-circle.twig' %}{% block content %}Hi{% endblock %}{% endembed %}", 'eye-catcher-circle'],
]);

it('renders without error and contains root CSS class', function (string $template, string $expectedClass) {
    $html = TwigRenderer::render($template);

    expect($html)->toContain($expectedClass);
})->with('twig-components');
