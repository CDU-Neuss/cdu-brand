import { describe, it, expect } from "vitest";
import { existsSync, readFileSync, statSync } from "node:fs";
import { join, dirname, resolve } from "node:path";

const ROOT = join(import.meta.dirname, "..");
const RESOURCES = join(ROOT, "resources");

function resourcePath(...segments) {
  return join(RESOURCES, ...segments);
}

describe("package structure", () => {
  it("has a non-empty CSS entry point", () => {
    const path = resourcePath("index.css");
    expect(existsSync(path)).toBe(true);
    expect(statSync(path).size).toBeGreaterThan(0);
  });

  it("has the theme file", () => {
    expect(existsSync(resourcePath("css", "theme.css"))).toBe(true);
  });

  it.each([
    "authors",
    "button",
    "countdown",
    "cta",
    "eye-catcher-circle",
    "feature",
    "icon-circle",
    "linked-section",
    "union-title",
  ])("has component CSS: %s", (name) => {
    expect(existsSync(resourcePath("css", "components", `${name}.css`))).toBe(true);
  });

  it.each(["arc", "gradient"])("has utility CSS: %s", (name) => {
    expect(existsSync(resourcePath("css", "utilities", `${name}.css`))).toBe(true);
  });

  it.each(["typography.css", "swiperjs.css"])("has supporting CSS: %s", (name) => {
    expect(existsSync(resourcePath("css", name))).toBe(true);
  });

  it.each(["countdown.js", "fonts.js"])("has JS file: %s", (name) => {
    expect(existsSync(resourcePath("js", name))).toBe(true);
  });

  it.each([
    "arc.svg",
    "cdu-gesamtlogo.png",
    "cdu-logo-transparent.png",
    "cdu-logo-white-transparent.png",
    "pattern-cta.png",
  ])("has image: %s", (name) => {
    expect(existsSync(resourcePath("img", name))).toBe(true);
  });

  it("index.css imports all reference existing files", () => {
    const indexPath = resourcePath("index.css");
    const content = readFileSync(indexPath, "utf8");
    const imports = content.match(/@import\s+["']([^"']+)["']/g) || [];

    expect(imports.length).toBeGreaterThan(0);

    for (const imp of imports) {
      const match = imp.match(/@import\s+["']([^"']+)["']/);
      const importPath = match[1];
      const resolved = resolve(dirname(indexPath), importPath);
      expect(existsSync(resolved), `imported file should exist: ${importPath}`).toBe(true);
    }
  });

  it.each([
    "authors",
    "button",
    "countdown",
    "cta",
    "eye-catcher-circle",
    "feature",
    "icon-circle",
    "linked-section",
  ])("has Blade component: %s", (name) => {
    const path = resourcePath("blade", "components", `${name}.blade.php`);
    expect(existsSync(path)).toBe(true);
    const content = readFileSync(path, "utf8");
    expect(content).toContain("@props(");
  });

  it("package.json has correct name and type", () => {
    const pkg = JSON.parse(readFileSync(join(ROOT, "package.json"), "utf8"));
    expect(pkg.name).toBe("@cdu-neuss/cdu-brand");
    expect(pkg.type).toBe("module");
  });
});
