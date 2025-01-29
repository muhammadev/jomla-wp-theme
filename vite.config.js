import { defineConfig } from "vite";

export default defineConfig({
  root: "./src", // Source folder
  base: "/", // Adjust if your WordPress site runs in a subdirectory
  build: {
    outDir: "./dist", // Output folder for production builds
    emptyOutDir: true,
    rollupOptions: {
      input: "./src/js/main.js", // Entry point
    },
  },
  css: {
    postcss: "./postcss.config.js", // Make sure Vite uses your PostCSS config
    preprocessorOptions: {
      scss: {
        additionalData: `@import "./src/scss/variables.scss";`, // Optionally import global SCSS variables
      },
    },
  },
  server: {
    proxy: {
      "/wp-admin": "http://localhost", // Proxy WordPress admin requests during development
    },
  },
});
