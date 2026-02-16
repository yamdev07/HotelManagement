import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import viteCompression from 'vite-plugin-compression';

export default defineConfig({
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
        },
    },

    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        viteCompression(),
    ],

    // 🔥 TRÈS IMPORTANT POUR DOCKER
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,

        hmr: {
            host: 'localhost',
            port: 5173,
        },

        watch: {
            usePolling: true,
        },
    },
});
