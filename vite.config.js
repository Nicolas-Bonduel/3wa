import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.scss',
                'resources/js/app.js',
                'resources/js/util.js',
                'resources/css/header.scss',
                'resources/js/header.js',
                'resources/css/components/quick-search.scss',
                'resources/js/components/quick-search.js',
                'resources/css/login.scss',
                'resources/css/register.scss',
                'resources/css/dashboard.scss',
                'resources/css/product.scss',
                'resources/css/slider.scss',
                'resources/css/loader.scss',
                'resources/css/add-to-cart.scss',
                'resources/css/cart.scss',
                'resources/css/products.scss',
                'resources/css/footer.scss',
                'resources/css/admin.scss',
                'resources/css/notifier.scss',
            ],
            refresh: true,
        }),
    ],
});
