import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

const isCI = process.env.CI === 'true' || process.env.VERCEL === '1';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
            wayfinder: isCI ? false : {},
        }),
        react(),
    ],
});
