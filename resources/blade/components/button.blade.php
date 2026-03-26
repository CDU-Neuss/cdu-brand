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
$tag = filled($href) ? 'a' : $as;
$buttonAttributes = $tag === 'a'
    ? $attributes->merge(['class' => $buttonClass, 'href' => $href])
    : $attributes->merge(['class' => $buttonClass]);
@endphp

<{{ $tag }} {{ $buttonAttributes }}>
    {{ $iconLeft ?? '' }}
    {{ $slot }}
    {{ $iconRight ?? '' }}
</{{ $tag }}>
