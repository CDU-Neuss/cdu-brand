@props(['title'])

<div {{ $attributes->merge(['class' => 'feature']) }}>
    <div class="icon">
        <x-cdu::icon-circle> {{ $icon ?? '' }} </x-cdu::icon-circle>
    </div>
    <div class="content">
        <h5 class="title">{{ $title }}</h5>
        {{ $slot }}
    </div>
</div>
