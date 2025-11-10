import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
     base: '/build/', 
     server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        allowedHosts: ['localhost', 'ticket-nginx', 'ticket-node'],
        watch: {
            usePolling: true,
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
