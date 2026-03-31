import { describe, it, expect } from "vitest";
import { existsSync, readdirSync, readFileSync, statSync } from "node:fs";
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

  it.each(["authors", "button", "countdown", "cta", "eye-catcher-circle", "feature", "icon-circle", "linked-section", "union-title"])(
    "has component CSS: %s",
    (name) => {
      expect(existsSync(resourcePath("css", "components", `${name}.css`))).toBe(true);
    },
  );

  it.each(["arc", "gradient"])("has utility CSS: %s", (name) => {
    expect(existsSync(resourcePath("css", "utilities", `${name}.css`))).toBe(true);
  });

  it.each(["typography.css", "swiperjs.css"])("has supporting CSS: %s", (name) => {
    expect(existsSync(resourcePath("css", name))).toBe(true);
  });

  it.each(["countdown.js", "fonts.js"])("has JS file: %s", (name) => {
    expect(existsSync(resourcePath("js", name))).toBe(true);
  });

  it.each(["arc.svg", "cdu-gesamtlogo.png", "cdu-logo-transparent.png", "cdu-logo-white-transparent.png", "pattern-cta.png"])(
    "has image: %s",
    (name) => {
      expect(existsSync(resourcePath("img", name))).toBe(true);
    },
  );

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

  it.each(["authors", "button", "countdown", "cta", "eye-catcher-circle", "feature", "icon-circle", "linked-section"])(
    "has Blade component: %s",
    (name) => {
      const path = resourcePath("blade", "components", `${name}.blade.php`);
      expect(existsSync(path)).toBe(true);
      const content = readFileSync(path, "utf8");
      expect(content).toMatch(/@props\s*\(/);
    },
  );

  it.each(["authors", "button", "countdown", "cta", "eye-catcher-circle", "feature", "icon-circle", "linked-section"])(
    "has Twig component: %s",
    (name) => {
      const path = resourcePath("twig", "components", `${name}.twig`);
      expect(existsSync(path)).toBe(true);
    },
  );

  it.each(["authors", "button", "countdown", "cta", "eye-catcher-circle", "feature", "icon-circle", "linked-section"])(
    "has Antlers component: %s",
    (name) => {
      const path = resourcePath("antlers", "cdu", `${name}.antlers.html`);
      expect(existsSync(path)).toBe(true);
    },
  );

  it.each(["Authors", "Button", "Countdown", "Cta", "EyeCatcherCircle", "Feature", "IconCircle", "LinkedSection"])(
    "has Astro component: %s",
    (name) => {
      const path = resourcePath("astro", "components", `${name}.astro`);
      expect(existsSync(path)).toBe(true);
    },
  );

  it("all template engine component directories have matching component names", () => {
    // Helper: PascalCase → kebab-case (e.g. "EyeCatcherCircle" → "eye-catcher-circle")
    const toKebab = (s) =>
      s
        .replace(/([a-z])([A-Z])/g, "$1-$2")
        .replace(/([A-Z]+)([A-Z][a-z])/g, "$1-$2")
        .toLowerCase();

    const bladeNames = readdirSync(resourcePath("blade", "components"))
      .filter((f) => f.endsWith(".blade.php"))
      .map((f) => f.replace(".blade.php", ""))
      .sort();

    const twigNames = readdirSync(resourcePath("twig", "components"))
      .filter((f) => f.endsWith(".twig"))
      .map((f) => f.replace(".twig", ""))
      .sort();

    const antlersNames = readdirSync(resourcePath("antlers", "cdu"))
      .filter((f) => f.endsWith(".antlers.html"))
      .map((f) => f.replace(".antlers.html", ""))
      .sort();

    const astroNames = readdirSync(resourcePath("astro", "components"))
      .filter((f) => f.endsWith(".astro"))
      .map((f) => toKebab(f.replace(".astro", "")))
      .sort();

    expect(twigNames).toEqual(bladeNames);
    expect(antlersNames).toEqual(bladeNames);
    expect(astroNames).toEqual(bladeNames);
  });

  it.each([
    ["blade", "examples/kitchen-sink.blade.php"],
    ["twig", "examples/kitchen-sink.twig"],
    ["antlers", "examples/kitchen-sink.antlers.html"],
  ])("has %s kitchen sink example", (engine, file) => {
    const path = resourcePath(engine, file);
    expect(existsSync(path)).toBe(true);
  });

  it.each(["blade", "twig", "antlers"])("%s kitchen sink references all components", (engine) => {
    const componentNames = readdirSync(resourcePath("blade", "components"))
      .filter((f) => f.endsWith(".blade.php"))
      .map((f) => f.replace(".blade.php", ""));

    const exts = {
      blade: "examples/kitchen-sink.blade.php",
      twig: "examples/kitchen-sink.twig",
      antlers: "examples/kitchen-sink.antlers.html",
    };
    const content = readFileSync(resourcePath(engine, exts[engine]), "utf8").toLowerCase();

    for (const name of componentNames) {
      expect(content, `${engine} kitchen sink should reference "${name}"`).toContain(name);
    }
  });

  it.each([
    ["Laravel/CduBrandServiceProvider.php", "CduBrandServiceProvider"],
    ["Twig/CduBrandTwig.php", "CduBrandTwig"],
  ])("has PHP source file: %s", (file, className) => {
    const path = join(ROOT, "src", file);
    expect(existsSync(path)).toBe(true);
    const content = readFileSync(path, "utf8");
    expect(content).toContain(`class ${className}`);
  });

  it("composer.json has correct name and autoload", () => {
    const composer = JSON.parse(readFileSync(join(ROOT, "composer.json"), "utf8"));
    expect(composer.name).toBe("cdu-neuss/cdu-brand");
    expect(composer.autoload["psr-4"]["CduNeuss\\CduBrand\\"]).toBe("src/");
  });

  it("package.json has correct name and type", () => {
    const pkg = JSON.parse(readFileSync(join(ROOT, "package.json"), "utf8"));
    expect(pkg.name).toBe("@cdu-neuss/cdu-brand");
    expect(pkg.type).toBe("module");
  });
});
