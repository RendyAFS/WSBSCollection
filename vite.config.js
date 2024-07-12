import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/auth.js',
                'resources/css/app.css',
                'resources/css/bootstrap.css',
                'resources/css/landing-page.css',
                'resources/css/nav-side-bar.css',
            ],
            refresh: true,
        }),
    ],
});
