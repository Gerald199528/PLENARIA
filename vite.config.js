import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: true, // permite conexiones externas
        port: 5173, // puerto de vite
        cors: true,
        hmr: {
            host: '192.168.0.118', // <-- PONÉ AQUÍ TU IP LOCAL
        },
    },
});

