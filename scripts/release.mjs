#!/usr/bin/env node

/**
 * Release script — bumps version in all package files, commits, and tags.
 *
 * Usage:
 *   pnpm run release <version>
 *   pnpm run release 1.12.0
 *
 * This will:
 *   1. Update "version" in package.json, docs/package.json, and composer.json
 *   2. Commit the changes
 *   3. Create a git tag
 *   4. Prompt to push (tag push triggers the GitHub release workflow)
 */

import { readFileSync, writeFileSync } from "node:fs";
import { execSync } from "node:child_process";
import { join, dirname } from "node:path";
import { fileURLToPath } from "node:url";

const ROOT = join(dirname(fileURLToPath(import.meta.url)), "..");

const version = process.argv[2];

if (!version) {
  console.error("Usage: pnpm run release <version>");
  console.error("Example: pnpm run release 1.12.0");
  process.exit(1);
}

if (!/^\d+\.\d+\.\d+(-[\w.]+)?$/.test(version)) {
  console.error(`Invalid version format: "${version}"`);
  console.error("Expected semver like 1.12.0 or 1.12.0-beta.1");
  process.exit(1);
}

// Files to update
const files = ["package.json", "docs/package.json", "composer.json"];

for (const file of files) {
  const path = join(ROOT, file);
  const content = readFileSync(path, "utf8");
  const json = JSON.parse(content);

  if (!json.version) {
    console.log(`  ⏭  ${file} — no "version" field, skipping`);
    continue;
  }

  const old = json.version;
  json.version = version;
  writeFileSync(path, JSON.stringify(json, null, 2) + "\n");
  console.log(`  ✓  ${file} — ${old} → ${version}`);
}

// Update composer.lock to match composer.json
execSync("composer update --lock", { cwd: ROOT, stdio: "inherit" });

// Stage, commit, tag
execSync(`git add ${files.join(" ")} composer.lock`, { cwd: ROOT, stdio: "inherit" });
execSync(`git commit -m "Release ${version}"`, { cwd: ROOT, stdio: "inherit" });
execSync(`git tag ${version}`, { cwd: ROOT, stdio: "inherit" });

console.log(`\nTagged ${version}. Push with:\n`);
console.log(`  git push && git push origin ${version}\n`);
