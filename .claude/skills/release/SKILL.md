---
name: release
description: Analyze unreleased changes, suggest semver bump, and create a release
disable-model-invocation: true
---

# Release

Create a new release with synchronized versions across all package files.

## Steps

### 1. Determine the current version and unreleased changes

```bash
# Get latest tag and current version
git describe --tags --abbrev=0
cat package.json | grep '"version"'

# List commits since last tag
git log $(git describe --tags --abbrev=0)..HEAD --oneline
```

### 2. Analyze changes and suggest a version bump

Review the commit log since the last tag and classify each change:

| Change type | Semver bump | Examples |
|-------------|-------------|----------|
| Breaking API changes | **major** | Renamed component, removed prop, changed CSS class names consumers depend on, dropped template engine support |
| New features, new components, new props | **minor** | Added component, added prop to existing component, new CSS utility |
| Bug fixes, formatting, docs, CI, tooling | **patch** | Fixed rendering bug, updated Prettier config, fixed CSS, updated dependencies |

Apply the highest applicable bump. Present the suggestion like this:

```
Current version: 1.11.1
Commits since last release: 5

Changes:
- fix: CTA pattern image inline as data URI (patch)
- feat: attribute forwarding for all components (minor)
- chore: Prettier formatting (patch)

Suggested bump: minor → 1.12.0
```

Ask the user to confirm or override the suggested version.

### 3. Run pre-release checks

```bash
pnpm test
```

Do not proceed if tests fail.

### 4. Execute the release

Once the user confirms the version:

```bash
pnpm run release <version>
```

This updates `version` in `package.json`, `docs/package.json`, and `composer.json`, commits as "Release \<version\>", and creates the git tag.

### 5. Push

```bash
git push && git push origin <version>
```

This triggers the GitHub Action that creates the GitHub Release with auto-generated notes.

### 6. Confirm

Report the release tag and link:

```
Released <version>
https://github.com/CDU-Neuss/cdu-brand/releases/tag/<version>
```
