@props(['title', 'links' => []])

<div {{ $attributes->merge(['class' => 'cta' . (isset($image) ? ' with-image' : '')]) }}>
    @isset($image)
        <figure>
            {{ $image }}
        </figure>
    @endisset
    <div class="body">
        <h2 class="title">{{ $title }}</h2>
        <div class="content">
            {{ $slot }}
        </div>
        @if (count($links) > 0)
            <div class="links">
                @foreach ($links as $link)
                    <x-cdu::button :href="$link['href']" as="a" :color="!empty($link['isButton']) ? 'gold' : 'link'">
                        {{ $link['text'] ?? 'Mehr erfahren' }}
                    </x-cdu::button>
                @endforeach
            </div>
        @endif
    </div>
    <div class="overlay"></div>
</div>
