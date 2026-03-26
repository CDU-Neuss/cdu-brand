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

For Laravel projects, register the Blade components in your `AppServiceProvider`:

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
resources/          # Distributable package assets
├── blade/
│   └── components/         # Laravel Blade components
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

The package is published to [GitHub Packages](https://github.com/CDU-Neuss/cdu-brand/packages) automatically when a GitHub release is created.
