@props([
    'size' => 'default',
    'as' => 'figure',
])

@php
    $sizeClassMap = [
        'default' => 'icon-circle',
        'lg' => 'icon-circle-lg',
        'xl' => 'icon-circle-xl',
        '2xl' => 'icon-circle-2xl',
    ];
    $circleClass = $sizeClassMap[$size] ?? $sizeClassMap['default'];
@endphp

<{{ $as }} {{ $attributes->merge(['class' => $circleClass]) }}> {{ $slot }} </{{ $as }}>
