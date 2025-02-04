import { defineConfig, loadEnv } from "vite";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig(({ command, mode }) => {
  return {
    plugins: [tailwindcss()],
    root: "resources",
    build: {
      outDir: '../dist',
      emptyOutDir: true
    }
  };
});
