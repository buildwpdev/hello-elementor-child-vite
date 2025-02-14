// wp-content/themes/ej-stone-co/vite.config.js

import { defineConfig } from "vite";
import path from "path";
import webfontDownload from "vite-plugin-webfont-dl";

export default defineConfig({
    plugins: [
        webfontDownload({
            fonts: [
                "https://fonts.googleapis.com/css2?family=Funnel+Sans:wght@400;700&family=Fira+Sans:wght@400;600;700&display=swap",
            ],
            outputDir: "resources/src/assets/fonts", // ✅ Ensure fonts are placed here
        }),
    ],
    root: "resources",
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/src"),
        },
    },
    publicDir: "resources/src/assets", // ✅ Ensures fonts are copied to dist/
    css: {
        //postcss: "./postcss.config.js",
        preprocessorOptions: {
            scss: {
                additionalData: `
                    @use "sass:color";
                    @use "@/styles/variables" as *;
                    @use "@/styles/breakpoints" as *;
                    @use "@/styles/mixins" as *;
                `,
            },
        },
    },
    build: {
        outDir: "dist",
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            input: {
                main: path.resolve(__dirname, "resources/src/main.js"),
                styles: path.resolve(__dirname, "resources/src/styles/main.scss"),
            },
        },
    },
    server: {
        open: true,
        strictPort: true,
        host: "localhost",
        port: 5173,
        hmr: {
            protocol: "ws",
            host: "localhost",
            port: 5173,
        },
        watch: {
            usePolling: true,
        },
    },
});
