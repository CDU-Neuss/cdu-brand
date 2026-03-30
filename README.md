# CDU Brand

Tailwind CSS theme and brand assets for CDU-related websites.

[![Deploy static content to Pages](https://github.com/CDU-Neuss/cdu-brand/actions/workflows/github-pages.yml/badge.svg)](https://github.com/CDU-Neuss/cdu-brand/actions/workflows/github-pages.yml)

**[Live Demo & Documentation](https://cdu-neuss.github.io/cdu-brand/)**

## Installation

```bash
pnpm add @cdu-neuss/cdu-brand
```

### Peer Dependencies

Install the required peer dependencies alongside the package:

```bash
pnpm add tailwindcss@^4.0.0 @tailwindcss/typography@^0.5.0 alpinejs@^3.0.0 @fontsource/ibm-plex-serif@^5.1.0 @fontsource/inter@^5.1.0
```

### Composer (PHP projects)

For PHP projects that don't need `node_modules` in production, install via Composer to get just the template components with auto-registration:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/CDU-Neuss/cdu-brand"
        }
    ],
    "require": {
        "cdu-neuss/cdu-brand": "^1.8"
    }
}
```

> **Note:** The CSS, JS, and fonts are still provided via the NPM package (or your build pipeline / CDN). The Composer package only delivers the PHP template components and auto-registration.

## Usage

### Import the Theme

Import the theme CSS in your project's main stylesheet:

```css
@import "@cdu-neuss/cdu-brand/resources/css/theme.css";
@import "@cdu-neuss/cdu-brand/resources/css/typography.css";
```

### Import Component Styles

Import individual component styles as needed:

```css
@import "@cdu-neuss/cdu-brand/resources/css/components/button.css";
@import "@cdu-neuss/cdu-brand/resources/css/components/cta.css";
@import "@cdu-neuss/cdu-brand/resources/css/components/feature.css";
/* ... */
```

### Load Fonts

Import the font loader in your JavaScript entry point:

```js
import "@cdu-neuss/cdu-brand/resources/js/fonts.js";
```

This registers IBM Plex Serif (400, 400-italic, 700, 700-italic) and Inter (300, 400, 500, 800) via `@fontsource`.

### Alpine.js Utilities

Register the countdown component with Alpine.js:

```js
import Alpine from "alpinejs";
import countdown from "@cdu-neuss/cdu-brand/resources/js/countdown.js";

Alpine.data("countdown", countdown);
Alpine.start();
```

### Laravel Blade Components

**Via Composer:** Components are auto-registered. No manual setup needed — Laravel's package auto-discovery registers the `cdu` namespace automatically.

**Via NPM only:** Register the Blade components manually in your `AppServiceProvider`:

```php
use Illuminate\Support\Facades\Blade;

public function boot(): void
{
    Blade::anonymousComponentPath(
        base_path('node_modules/@cdu-neuss/cdu-brand/resources/blade/components'),
        'cdu'
    );
}
```

Then use the components with the `x-cdu::` prefix:

```blade
<x-cdu::button color="gold" href="/contact" as="a">
    Contact Us
</x-cdu::button>

<x-cdu::feature title="Fast Delivery">
    <x-slot:icon>
        <svg>...</svg>
    </x-slot:icon>
    We deliver within 24 hours.
</x-cdu::feature>

<x-cdu::cta title="Get Started" :links="[
    ['href' => '/signup', 'text' => 'Sign Up', 'isButton' => true],
    ['href' => '/learn-more', 'text' => 'Learn More'],
]">
    Join us today and start building.
</x-cdu::cta>

<x-cdu::countdown target-date="2026-12-31" event="New Year" />
```

Available components: `button`, `icon-circle`, `feature`, `cta`, `linked-section`, `countdown`, `authors`, `eye-catcher-circle`.

> **Note:** The `countdown` component requires Alpine.js — see [Alpine.js Utilities](#alpinejs-utilities) above.

> **Kitchen Sink:** Copy `resources/blade/examples/kitchen-sink.blade.php` into your project to test all components at once.

### Twig Components (Craft CMS / Symfony)

**Via Composer:** Register the `@cdu` namespace using the included helper:

```php
// Craft CMS: in a custom module's init()
use CduNeuss\CduBrand\Twig\CduBrandTwig;

$loader = \Craft::$app->getView()->getTwig()->getLoader();
if ($loader instanceof \Twig\Loader\FilesystemLoader) {
    CduBrandTwig::registerNamespace($loader);
}
```

**Via NPM only:** Configure the namespace manually in your Craft CMS config:

```php
// Craft CMS: config/app.php
'components' => [
    'view' => [
        'class' => \craft\web\View::class,
        'twig' => [
            'namespaces' => [
                'cdu' => '@vendor/cdu-neuss/cdu-brand/resources/twig/components',
            ],
        ],
    ],
],
```

Then use the components via `{% embed %}` or `{% include %}`:

```twig
{% embed '@cdu/button.twig' with { color: 'gold', href: '/contact' } %}
    {% block content %}Contact Us{% endblock %}
{% endembed %}

{% embed '@cdu/feature.twig' with { title: 'Fast Delivery', icon: '<svg>...</svg>' } %}
    {% block content %}We deliver within 24 hours.{% endblock %}
{% endembed %}

{% embed '@cdu/cta.twig' with { title: 'Get Started', links: [
    { href: '/signup', text: 'Sign Up', isButton: true },
    { href: '/learn-more', text: 'Learn More' },
] } %}
    {% block content %}Join us today and start building.{% endblock %}
{% endembed %}

{% include '@cdu/countdown.twig' with { target_date: '2026-12-31', event: 'New Year' } %}
```

> **Kitchen Sink:** Copy `resources/twig/examples/kitchen-sink.twig` into your project to test all components at once.

### Antlers Components (Statamic)

**Via Composer:** Antlers partials are auto-registered when Statamic is detected. No manual setup needed.

**Via NPM only:** Register the partial namespace manually in a Statamic service provider:

```php
public function boot(): void
{
    $this->app['view']->addLocation(
        base_path('node_modules/@cdu-neuss/cdu-brand/resources/antlers')
    );
}
```

Then use the components as partials:

```antlers
{{ partial:cdu/button color="gold" href="/contact" }}
    Contact Us
{{ /partial:cdu/button }}

{{ partial:cdu/feature title="Fast Delivery" }}
    {{ slot:icon }}<svg>...</svg>{{ /slot:icon }}
    We deliver within 24 hours.
{{ /partial:cdu/feature }}

{{ partial:cdu/countdown target_date="2026-12-31" event="New Year" }}
```

> **Kitchen Sink:** Copy `resources/antlers/examples/kitchen-sink.antlers.html` into your project to test all components at once.

### Astro Components

Import the components directly from the package:

```astro
---
import Button from "@cdu-neuss/cdu-brand/resources/astro/components/Button.astro";
import Feature from "@cdu-neuss/cdu-brand/resources/astro/components/Feature.astro";
import Cta from "@cdu-neuss/cdu-brand/resources/astro/components/Cta.astro";
import Countdown from "@cdu-neuss/cdu-brand/resources/astro/components/Countdown.astro";
---

<Button color="gold" href="/contact" as="a">Contact Us</Button>

<Feature title="Fast Delivery">
  <svg slot="icon">...</svg>
  We deliver within 24 hours.
</Feature>

<Cta title="Get Started" links={[
  { href: "/signup", text: "Sign Up", isButton: true },
  { href: "/learn-more", text: "Learn More" },
]}>
  Join us today and start building.
</Cta>

<Countdown targetDate="2026-12-31" event="New Year" />
```

> **Note:** The `Countdown` component requires `@astrojs/alpinejs` — see [Alpine.js Utilities](#alpinejs-utilities) above.

## What's Included

### Theme (`resources/css/theme.css`)

A Tailwind CSS 4 `@theme` block defining the full CDU brand palette, fonts, and design tokens:

- **Colors** — `cadenabbia` (teal), `rhoendorf` (blue-gray), `gold`, `red`, `black`, `white`
- **Sub-organization colors** — `fu-*`, `su-*`, `cda-*`, `mit-*`, `ju-*`
- **Social media colors** — `facebook`, `linkedin`, `whatsapp`, `x`
- **Fonts** — `font-serif` (IBM Plex Serif), `font-sans` (Inter), `font-mono`

### Components (`resources/css/components/`)

| Component | Description |
|---|---|
| `button.css` | Buttons with variants: default, gold, blue, inverted, transparent, link, social |
| `cta.css` | Call-to-action sections |
| `feature.css` | Feature card layouts |
| `countdown.css` | Countdown timer display |
| `icon-circle.css` | Circular icon containers |
| `union-title.css` | Styled section titles |
| `authors.css` | Author/person displays |
| `linked-section.css` | Clickable section blocks |
| `eye-catcher-circle.css` | Circular highlight elements |

### Utilities (`resources/css/utilities/`)

- `gradient.css` — Brand gradient backgrounds
- `arc.css` — Arc-shaped decorative backgrounds

### Typography (`resources/css/typography.css`)

Customizes `@tailwindcss/typography` prose styles with CDU brand colors and styled ordered lists with numbered circles.

### Images (`resources/img/`)

CDU logos (full logo, transparent, white) and decorative assets (arc SVG, CTA pattern).

## Development

### Prerequisites

- Node.js 22
- pnpm 10.32.1

### Setup

```bash
pnpm install
cd docs && pnpm install
```

### Commands

```bash
# Linting (from root)
pnpm run lint

# Documentation site (from docs/)
cd docs
pnpm dev        # Dev server at http://localhost:4321
pnpm build      # Production build
pnpm preview    # Preview production build
```

### Project Structure

```
src/                # PHP source (Composer package)
├── Laravel/
│   └── CduBrandServiceProvider.php
└── Twig/
    └── CduBrandTwig.php

resources/          # Distributable package assets
├── blade/
│   └── components/         # Laravel Blade components
├── twig/
│   └── components/         # Twig components (Craft CMS / Symfony)
├── antlers/
│   └── cdu/                # Antlers components (Statamic)
├── astro/
│   └── components/         # Astro components
├── css/
│   ├── theme.css           # Tailwind theme (brand tokens)
│   ├── typography.css      # Prose styling
│   ├── swiperjs.css        # Swiper overrides
│   ├── components/         # Component styles
│   └── utilities/          # Utility styles
├── js/
│   ├── fonts.js            # Font imports
│   └── countdown.js        # Alpine.js countdown
└── img/                    # Brand logos and assets

docs/               # Astro documentation/demo site
├── src/
│   ├── pages/              # Demo pages per component
│   ├── components/         # Astro UI components
│   ├── layouts/            # Page layouts
│   └── styles/             # Docs-specific styles
└── public/                 # Static assets
```

## Publishing

**NPM:** The package is published to [GitHub Packages](https://github.com/CDU-Neuss/cdu-brand/packages) automatically when a GitHub release is created.

**Composer:** The package is installed directly from the GitHub repository via VCS. Composer resolves versions from Git tags — no separate publish step is needed.
