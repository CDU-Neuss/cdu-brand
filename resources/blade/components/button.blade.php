@props([
    'color' => 'default',
    'as' => 'button',
    'href' => null,
])

@php
$colorClassMap = [
    'blue' => 'btn btn-blue',
    'gold' => 'btn btn-gold',
    'blue-inverted' => 'btn btn-blue-inverted',
    'gold-inverted' => 'btn btn-gold-inverted',
    'blue-transparent' => 'btn btn-blue-transparent',
    'gold-transparent' => 'btn btn-gold-transparent',
    'link' => 'btn btn-link',
    'default' => 'btn',
];
$buttonClass = $colorClassMap[$color] ?? $colorClassMap['default'];
$tag = $href ? 'a' : $as;
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $buttonClass, 'href' => $href]) }}>
    {{ $iconLeft ?? '' }}
    {{ $slot }}
    {{ $iconRight ?? '' }}
</{{ $tag }}>
