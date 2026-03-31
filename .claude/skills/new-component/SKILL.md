---
name: new-component
description: Scaffold a new CDU brand component across all 4 template engines (Blade, Twig, Antlers, Astro) with attribute forwarding, CSS file, and test registration
disable-model-invocation: true
---

# New Component Scaffold

Create a new CDU brand component with files in all 4 template engines plus a CSS file. The user provides:
- **Component name** (kebab-case, e.g. `card` or `hero-banner`)
- **Root HTML element** (e.g. `div`, `section`, `figure`)
- **Brief description** of what the component does

## What to create

Given a component named `{name}` with root element `<{el}>`:

### 1. CSS (`resources/css/components/{name}.css`)

Create a minimal component CSS file using Tailwind `@utility` or plain CSS with the component's class name matching the kebab-case name.

### 2. Blade (`resources/blade/components/{name}.blade.php`)

Follow this pattern — use `$attributes->merge()` for attribute forwarding:

```blade
@props([])

<{el} {{ $attributes->merge(['class' => '{name}']) }}>
    {{ $slot }}
</{el}>
```

### 3. Twig (`resources/twig/components/{name}.twig`)

Follow this pattern — use `class` and `attr` parameters:

```twig
{#
  CDU {PascalName} Component

  Parameters:
    - class: additional CSS classes (default: '')
    - attr: extra HTML attributes string (default: '')

  Blocks:
    - content: main content

  Usage:
    {%% embed '@cdu/{name}.twig' %%}
      {%% block content %%}...{%% endblock %%}
    {%% endembed %%}
#}

{%% set class = class|default('') %%}
{%% set attr = attr|default('') %%}

<{el} class="{{ ['{name}', class]|join(' ')|trim }}" {{ attr|raw }}>
    {%% block content %%}{%% endblock %%}
</{el}>
```

### 4. Antlers (`resources/antlers/cdu/{name}.antlers.html`)

Follow this pattern:

```antlers
{{#
  CDU {PascalName} Component

  Parameters:
    - class: additional CSS classes (default: '')
    - attr: extra HTML attributes string (default: '')

  Slots:
    - default: main content

  Usage:
    {{ partial:cdu/{name} }}
        Content here
    {{ /partial:cdu/{name} }}
#}}

<{el} class="{name} {{ class }}" {{ attr }}>
    {{ slot }}
</{el}>
```

### 5. Astro (`resources/astro/components/{PascalName}.astro`)

Follow this pattern — use `HTMLAttributes` and rest-spread:

```astro
---
import type { HTMLAttributes } from "astro/types";

type Props = HTMLAttributes<"{el}">;

const { class: className, ...attrs } = Astro.props;
---

<{el} class:list={["{name}", className]} {...attrs}>
  <slot />
</{el}>
```

## After creating files

1. Add `@import "./components/{name}.css";` to `resources/index.css`
2. Register the component name in the test arrays in `tests/package-structure.test.js` (CSS components, Blade, Twig, Antlers, and Astro lists)
3. Add entries to all 3 kitchen sink examples (`resources/blade/examples/kitchen-sink.blade.php`, `resources/twig/examples/kitchen-sink.twig`, `resources/antlers/examples/kitchen-sink.antlers.html`)
4. Run `pnpm test` to verify template parity passes
5. Run `npx prettier --write` on all new files

## Naming conventions

- CSS class / file name: `kebab-case` (e.g. `hero-banner`)
- Blade file: `{kebab-case}.blade.php`
- Twig file: `{kebab-case}.twig`
- Antlers file: `{kebab-case}.antlers.html`
- Astro file: `{PascalCase}.astro` (e.g. `HeroBanner.astro`)
