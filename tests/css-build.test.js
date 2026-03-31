import { describe, it, expect, beforeAll } from "vitest";
import { execSync } from "node:child_process";
import { writeFileSync, unlinkSync } from "node:fs";
import { join } from "node:path";

const ROOT = join(import.meta.dirname, "..");
const TMP_INPUT = join(ROOT, "tests/.tmp-test-input.css");

function compileCss() {
  // The resources package doesn't import tailwindcss itself — consumers do.
  // We need to prepend @import "tailwindcss" so @apply directives resolve.
  writeFileSync(TMP_INPUT, `@import "tailwindcss";\n@import "../resources/index.css";\n`);

  try {
    return execSync(`npx @tailwindcss/cli -i ${TMP_INPUT}`, {
      cwd: ROOT,
      encoding: "utf8",
      timeout: 30000,
      stdio: ["pipe", "pipe", "pipe"],
    });
  } finally {
    try {
      unlinkSync(TMP_INPUT);
    } catch {
      // Cleanup is best-effort
    }
  }
}

describe("CSS build", () => {
  let css;

  beforeAll(() => {
    css = compileCss();
  });

  it("compiles without errors", () => {
    expect(css).toBeTruthy();
    expect(css.length).toBeGreaterThan(0);
  });

  it("contains theme custom properties", () => {
    expect(css).toContain("--color-cadenabbia");
    expect(css).toContain("--color-rhoendorf");
    expect(css).toContain("--color-gold");
    expect(css).toContain("--font-sans");
    expect(css).toContain("--font-serif");
  });

  it("contains component selectors", () => {
    expect(css).toContain(".btn");
    expect(css).toContain(".countdown");
    expect(css).toContain(".cta");
    expect(css).toContain(".feature");
    expect(css).toContain(".authors");
    expect(css).toContain(".icon-circle");
    expect(css).toContain(".linked-section");
    expect(css).toContain(".union-title");
    expect(css).toContain(".eye-catcher-circle");
  });

  it("contains prose typography overrides", () => {
    expect(css).toContain("--tw-prose-body");
  });
});
