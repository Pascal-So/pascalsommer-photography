// @ts-check
import { defineConfig } from "astro/config";

import tailwindcss from "@tailwindcss/vite";

import react from "@astrojs/react";

// https://astro.build/config
export default defineConfig({
  vite: {
    plugins: [tailwindcss()],
    build: {
      assetsInlineLimit: 1024 * 8,
    }
  },

  prefetch: true,
  integrations: [react()],
});
