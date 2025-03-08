// wp-content/themes/build-wp/vite.config.js

import liveReload from 'vite-plugin-live-reload';
import fullReload from 'vite-plugin-full-reload';
import { defineConfig } from "vite";
import path from "path";
import webfontDownload from 'vite-plugin-webfont-dl';

export default defineConfig({
    plugins: [
        webfontDownload([
            'https://fonts.googleapis.com/css2?family=Fira+Sans:wght@100..900&display=swap',
            'https://fonts.googleapis.com/css2?family=Fira+Code&display=swap'
        ], {
            injectAsStyleTag: false,
            minifyCss: true,
            embedFonts: false,
            assetsSubfolder: "fonts",
        }),
        liveReload([__dirname + '/**/*.php']),
        fullReload(['inc/**/*.php', 'templates/**/*.php', '*.php']),
    ],
    root: "resources",
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/src"),
            '@styles': path.resolve(__dirname, 'resources/src/styles'),
        },
    },
    publicDir: "resources/public",
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
