import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: true, // écoute sur 0.0.0.0 dans le conteneur
        port: Number(process.env.VITE_PORT || 5173),
        strictPort: true,
        hmr: {
            host: process.env.VITE_HOSTNAME || 'localhost',
            port: Number(process.env.VITE_PORT || 5173),
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
