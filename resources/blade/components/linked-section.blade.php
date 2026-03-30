@props([
    'title',
    'href',
    'linkText' => null,
    'hasButton' => false,
])

<div class="linked-section{{ $hasButton ? ' has-button' : '' }}">
    <div class="headline">
        <div class="icon">
            <x-cdu::icon-circle>
                {{ $icon ?? '' }}
            </x-cdu::icon-circle>
        </div>
        <h3 class="title">{{ $title }}</h3>
    </div>
    <div class="content">
        {{ $slot }}
    </div>
    <div class="link">
        <x-cdu::button :href="$href" as="a" :color="$hasButton ? 'gold' : 'link'">
            @unless($hasButton)
                <x-slot:icon-right>
                    {{-- Phosphor caret-right-bold --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor" width="1em" height="1em"><path d="M181.66,133.66l-80,80a8,8,0,0,1-11.32-11.32L164.69,128,90.34,53.66a8,8,0,0,1,11.32-11.32l80,80A8,8,0,0,1,181.66,133.66Z"/></svg>
                </x-slot:icon-right>
            @endunless
            {{ $linkText ?? $title }}
        </x-cdu::button>
    </div>
</div>
