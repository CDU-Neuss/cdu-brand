---
name: verify-kitchen-sink
description: Build CSS and render all kitchen sink templates (Blade, Twig) to verify components render correctly
disable-model-invocation: true
---

# Verify Kitchen Sink

Run the full kitchen sink verification pipeline to ensure all components render correctly across template engines.

## Steps

### 1. Run unit tests

```bash
pnpm test
```

This checks package structure, template parity, CSS build, and countdown logic.

### 2. Build kitchen sink CSS

```bash
composer kitchen-sink:build
```

This runs `@tailwindcss/cli` to compile `tests/kitchen-sink/input.css` into `tests/kitchen-sink/styles.css`.

### 3. Render Blade kitchen sink

```bash
php tests/kitchen-sink/blade.php
```

Check the output for:
- No PHP errors or warnings
- All components present in the HTML output
- No broken attribute forwarding (look for duplicate `class=` attributes)

### 4. Render Twig kitchen sink

```bash
php tests/kitchen-sink/twig.php
```

Check the output for:
- No Twig errors
- All components present
- No raw `{{ attr }}` or `{{ class }}` strings in output (would indicate unset variables)

### 5. Report results

Summarize which engines passed and flag any issues found.
