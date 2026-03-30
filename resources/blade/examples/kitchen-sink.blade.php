{{--
  CDU Brand — Kitchen Sink (Laravel Blade)

  Copy this file into your Laravel project to verify all CDU brand components
  render correctly. Requires the components to be registered via:

  Blade::anonymousComponentPath(
      base_path('node_modules/@cdu-neuss/cdu-brand/resources/blade/components'),
      'cdu'
  );
--}}

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CDU Brand — Kitchen Sink</title>
</head>
<body class="p-8 space-y-16">

    {{-- Button --}}
    <section>
        <h2>Button</h2>
        <div class="flex flex-wrap gap-4">
            <x-cdu::button>Default</x-cdu::button>
            <x-cdu::button color="blue">Blue</x-cdu::button>
            <x-cdu::button color="gold">Gold</x-cdu::button>
            <x-cdu::button color="blue-inverted">Blue Inverted</x-cdu::button>
            <x-cdu::button color="gold-inverted">Gold Inverted</x-cdu::button>
            <x-cdu::button color="blue-transparent">Blue Transparent</x-cdu::button>
            <x-cdu::button color="gold-transparent">Gold Transparent</x-cdu::button>
            <x-cdu::button color="link">Link</x-cdu::button>
            <x-cdu::button color="gold" href="/example" as="a">As Link</x-cdu::button>
        </div>
    </section>

    {{-- Icon Circle --}}
    <section>
        <h2>Icon Circle</h2>
        <div class="flex items-end gap-4">
            <x-cdu::icon-circle>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Z"/></svg>
            </x-cdu::icon-circle>
            <x-cdu::icon-circle size="lg">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Z"/></svg>
            </x-cdu::icon-circle>
        </div>
    </section>

    {{-- Feature --}}
    <section>
        <h2>Feature</h2>
        <x-cdu::feature title="Schnelle Lieferung">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Z"/></svg>
            </x-slot:icon>
            Weit hinten, hinter den Wortbergen, fern der Länder Vokalien und Konsonantien leben die Blindtexte.
        </x-cdu::feature>
    </section>

    {{-- CTA --}}
    <section>
        <h2>Call to Action</h2>
        <x-cdu::cta title="CDU Shop" :links="[
            ['href' => '/shop', 'text' => 'Zum Shop', 'isButton' => true],
            ['href' => '/info', 'text' => 'Mehr erfahren'],
        ]">
            Weit hinten, hinter den Wortbergen, fern der Länder Vokalien und Konsonantien leben die Blindtexte.
        </x-cdu::cta>
    </section>

    {{-- Linked Section --}}
    <section>
        <h2>Linked Section</h2>
        <div class="grid gap-8 md:grid-cols-2">
            <x-cdu::linked-section title="CDUplus" href="/cduplus">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Z"/></svg>
                </x-slot:icon>
                Weit hinten, hinter den Wortbergen, fern der Länder Vokalien und Konsonantien leben die Blindtexte.
            </x-cdu::linked-section>

            <x-cdu::linked-section title="CDU Shop" href="/shop" :hasButton="true">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Z"/></svg>
                </x-slot:icon>
                Weit hinten, hinter den Wortbergen, fern der Länder Vokalien und Konsonantien leben die Blindtexte.
            </x-cdu::linked-section>
        </div>
    </section>

    {{-- Countdown --}}
    <section>
        <h2>Countdown</h2>
        <x-cdu::countdown
            target-date="2028-01-01 00:00:00"
            event="bis zum großen Event"
            :labels="['days' => 'Tage', 'hours' => 'Stunden', 'minutes' => 'Minuten', 'seconds' => 'Sekunden']"
        />
    </section>

    {{-- Authors --}}
    <section>
        <h2>Authors</h2>
        <x-cdu::authors :authors="[
            ['name' => 'Max Mustermann', 'link' => '#', 'image' => 'https://i.pravatar.cc/80?img=6'],
            ['name' => 'Erika Musterfrau', 'link' => '#', 'image' => 'https://i.pravatar.cc/80?img=5'],
            ['name' => 'Hans Beispiel', 'link' => '#'],
        ]" />
    </section>

    {{-- Eye Catcher Circle --}}
    <section>
        <h2>Eye Catcher Circle</h2>
        <x-cdu::eye-catcher-circle class="size-32">
            <strong>Neu</strong><br>2028
        </x-cdu::eye-catcher-circle>
    </section>

</body>
</html>
