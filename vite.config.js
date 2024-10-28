import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                    'resources/js/app.js',
                    'resources/js/library.js',
                    'resources/js/location.js',
                    'resources/js/select2.js',
                    'resources/js/seo.js',
                    'resources/css/normalize.css',
                    'resources/fontawesome-free-6.5.2-web/css/all.min.css',
                    'public/ckeditor/ckeditor5-premium-features-43.0.0/ckeditor5/ckeditor5.css',
                    ],
            refresh: true,
        }),
    ],

    resolve: {
        alias: {
            // '$': 'jQuery',
        }
    }
});
