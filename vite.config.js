import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: "resources/js/app.js",
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        host: "localhost", // Vaša lokalna IP adresa
        port: 3000, // Port za Vite (možete promeniti ako želite)
        hmr: {
            host: "localhost", // Potrebno za Hot Module Replacement
        },
    },
});
