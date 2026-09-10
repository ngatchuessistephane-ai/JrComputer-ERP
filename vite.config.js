import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        minify: 'terser',
        sourcemap: false,
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['bootstrap', 'alpinejs', 'pusher-js', 'laravel-echo'],
                },
            },
        },
    },
    server: {
        hmr: {
            host: '127.0.0.1',
        },
        cors: {
            origin: '*',  // ✅ Pour le développement, autoriser tout
            methods: ['GET', 'HEAD', 'PUT', 'POST', 'DELETE', 'PATCH'],
            credentials: true,
        },
    },
    // Variables d'environnement exposées au frontend
    define: {
        'import.meta.env.VITE_REVERB_APP_KEY': JSON.stringify(process.env.REVERB_APP_KEY || 'yourkey'),
        'import.meta.env.VITE_REVERB_HOST': JSON.stringify(process.env.REVERB_HOST || '127.0.0.1'),
        'import.meta.env.VITE_REVERB_PORT': JSON.stringify(process.env.REVERB_PORT || '8080'),
        'import.meta.env.VITE_REVERB_SCHEME': JSON.stringify(process.env.REVERB_SCHEME || 'http'),
    },
});