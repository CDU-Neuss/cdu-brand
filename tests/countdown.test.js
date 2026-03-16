import { describe, it, expect, beforeEach, afterEach, vi } from "vitest";
import countdown from "../resources/js/countdown.js";

describe("countdown", () => {
  beforeEach(() => {
    vi.useFakeTimers();
  });

  afterEach(() => {
    vi.useRealTimers();
  });

  it("returns an object with the correct shape", () => {
    const component = countdown("2030-01-01");

    expect(component.target).toBeInstanceOf(Date);
    expect(component.days).toBe(0);
    expect(component.hours).toBe(0);
    expect(component.minutes).toBe(0);
    expect(component.seconds).toBe(0);
    expect(component.interval).toBeNull();
    expect(typeof component.init).toBe("function");
    expect(typeof component.updateCountdown).toBe("function");
  });

  it("calculates future date correctly", () => {
    const now = new Date("2025-06-15T12:00:00Z");
    vi.setSystemTime(now);

    // 2 days, 3 hours, 15 minutes, 30 seconds in the future
    const target = new Date(now.getTime() + 2 * 86400000 + 3 * 3600000 + 15 * 60000 + 30 * 1000);
    const component = countdown(target.toISOString());
    component.updateCountdown();

    expect(component.days).toBe(2);
    expect(component.hours).toBe(3);
    expect(component.minutes).toBe(15);
    expect(component.seconds).toBe(30);
  });

  it("sets all to zero for a past date", () => {
    vi.setSystemTime(new Date("2025-06-15T12:00:00Z"));

    const component = countdown("2020-01-01T00:00:00Z");
    component.updateCountdown();

    expect(component.days).toBe(0);
    expect(component.hours).toBe(0);
    expect(component.minutes).toBe(0);
    expect(component.seconds).toBe(0);
  });

  it("sets all to zero when difference is exactly zero", () => {
    const now = new Date("2025-06-15T12:00:00Z");
    vi.setSystemTime(now);

    const component = countdown(now.toISOString());
    component.updateCountdown();

    expect(component.days).toBe(0);
    expect(component.hours).toBe(0);
    expect(component.minutes).toBe(0);
    expect(component.seconds).toBe(0);
  });

  it("init() populates values immediately and sets interval", () => {
    const now = new Date("2025-06-15T12:00:00Z");
    vi.setSystemTime(now);

    const target = new Date(now.getTime() + 86400000); // 1 day ahead
    const component = countdown(target.toISOString());
    component.init();

    expect(component.days).toBe(1);
    expect(component.interval).not.toBeNull();
  });

  it("interval ticks decrement seconds", () => {
    const now = new Date("2025-06-15T12:00:00Z");
    vi.setSystemTime(now);

    const target = new Date(now.getTime() + 10000); // 10 seconds ahead
    const component = countdown(target.toISOString());
    component.init();

    expect(component.seconds).toBe(10);

    vi.advanceTimersByTime(1000);
    expect(component.seconds).toBe(9);
  });

  it("clears interval when countdown expires", () => {
    const now = new Date("2025-06-15T12:00:00Z");
    vi.setSystemTime(now);

    const target = new Date(now.getTime() + 2000); // 2 seconds ahead
    const component = countdown(target.toISOString());
    component.init();

    expect(component.seconds).toBe(2);

    vi.advanceTimersByTime(3000);

    expect(component.days).toBe(0);
    expect(component.hours).toBe(0);
    expect(component.minutes).toBe(0);
    expect(component.seconds).toBe(0);
  });

  it("handles large time differences", () => {
    const now = new Date("2025-06-15T12:00:00Z");
    vi.setSystemTime(now);

    const target = new Date(now.getTime() + 400 * 86400000); // 400 days
    const component = countdown(target.toISOString());
    component.updateCountdown();

    expect(component.days).toBe(400);
    expect(component.hours).toBe(0);
    expect(component.minutes).toBe(0);
    expect(component.seconds).toBe(0);
  });
});
