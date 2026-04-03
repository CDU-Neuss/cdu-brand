@props(['level' => 'h2'])

@php
    $tag = in_array($level, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div']) ? $level : 'h2';
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => 'union-title']) }}>
    <span>{{ $slot }}</span>
    </{{ $tag }}>
