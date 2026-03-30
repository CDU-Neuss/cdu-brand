# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Tailwind CSS theme and brand assets package (`@cdu-neuss/cdu-brand`) for CDU-related websites. Published to GitHub Packages. Includes a `/docs` Astro site deployed to GitHub Pages as a living component demo.

## Commands

### Root (linting)
```bash
pnpm run lint                    # ESLint across js, jsx, ts, tsx, astro
```

### Docs site (`cd docs/`)
```bash
pnpm dev                         # Astro dev server at localhost:4321
pnpm build                       # Production build to docs/dist/
pnpm preview                     # Preview production build
```

Install dependencies: `pnpm install` (root) and `cd docs && pnpm install`.

## Architecture

### Two-part structure (not a monorepo)

- **`/resources`** — The distributable package: CSS theme, component styles, JS utilities, fonts config, brand images, and Laravel Blade components. Consumed by downstream CDU sites as an npm dependency.
- **`/docs`** — Astro 5 demo/documentation site that imports from `/resources` and showcases every component. Deployed to GitHub Pages at `/cdu-brand`.

### Theming system

- **`resources/css/theme.css`** — Tailwind 4 `@theme` block defining all CSS custom properties (colors, fonts, spacing). This is the single source of truth for the brand palette.
- **`resources/css/components/*.css`** — Individual component styles (button, cta, feature, countdown, etc.) using Tailwind's `@utility` and `@variant` directives.
- **`resources/css/typography.css`** — Prose/typography overrides for `@tailwindcss/typography`.
- **`resources/css/utilities/`** — Gradient and arc background utilities.
- **`resources/blade/components/`** — Anonymous Laravel Blade components (button, icon-circle, feature, cta, linked-section, countdown, authors, eye-catcher-circle). Registered via `Blade::anonymousComponentPath()` with the `cdu` namespace (`<x-cdu::button>`).
- **`resources/twig/components/`** — Twig equivalents of the Blade components for Craft CMS / Symfony projects. Used via `{% embed '@cdu/button.twig' %}` (consumers configure the `@cdu` namespace).
- **`resources/antlers/components/`** — Antlers equivalents for Statamic projects. Used via `{{ partial:cdu/button }}` (consumers register the `cdu` partial namespace).
- **`resources/astro/components/`** — Astro equivalents (PascalCase filenames). Used via `import Button from '@cdu-neuss/cdu-brand/resources/astro/components/Button.astro'`.
- **Template parity rule** — Every component must exist in all four template engines: Blade (`.blade.php`), Twig (`.twig`), Antlers (`.antlers.html`), and Astro (`.astro`). When adding or removing a component, update all four directories. CI enforces this via `tests/package-structure.test.js`.
- Color naming: `cadenabbia` (teal), `rhoendorf` (blue-gray) are CDU-specific; `fu`, `su`, `cda`, `mit`, `ju` prefixes are sub-organization colors.

### Key technologies

- **Tailwind CSS 4** with Vite plugin (not PostCSS) — configured in `docs/astro.config.mjs`
- **Alpine.js** for client-side interactivity (countdown timer, dark mode toggle)
- **Astro 5** for the docs site — components are `.astro` files
- **Fonts**: IBM Plex Serif (body/serif), Inter (sans) via `@fontsource`

### Formatting & linting

- Prettier: double quotes, trailing commas, 140 char width, with astro and tailwindcss plugins
- ESLint: flat config (v10), includes `jsx-a11y` for accessibility checks on Astro files
- Package manager: pnpm 10.32.1
