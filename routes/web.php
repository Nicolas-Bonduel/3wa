<?php

use Illuminate\Support\Facades\Route;


/* --- Public --- */
Route::name('public.')->group(function () {

    Route::get('/', \App\Livewire\Pages\Home::class)
        ->name('home');

    Route::get('/product/{slug}', \App\Livewire\Pages\Product::class)
        ->name('product');
    Route::get('/products', \App\Livewire\Pages\Products::class)
        ->name('products');

    Route::get('/cart', \App\Livewire\Pages\Cart::class)
        ->name('cart');

    /* vv Empty for now vv */
    Route::get('/about', \App\Livewire\Pages\Later\AboutUs::class)
        ->name('about');
    Route::get('/blog', \App\Livewire\Pages\Later\Blog::class)
        ->name('blog');
    Route::get('/customer-service', \App\Livewire\Pages\Later\CustomerService::class)
        ->name('customer-service');
    Route::get('/faq', \App\Livewire\Pages\Later\FAQ::class)
        ->name('faq');
    Route::get('/legal-notice', \App\Livewire\Pages\Later\LegalNotice::class)
        ->name('legal-notice');
    Route::get('/privacy-policy', \App\Livewire\Pages\Later\PrivacyPolicy::class)
        ->name('privacy-policy');
    Route::get('/recruitment', \App\Livewire\Pages\Later\Recruitment::class)
        ->name('recruitment');
    Route::get('/terms-of-sale', \App\Livewire\Pages\Later\TermsOfSale::class)
        ->name('terms-of-sale');

});

/* --- Logged In (site) --- */
Route::middleware(['auth:customer', 'verified'])->name('customer.')->group(function () {

    Route::get('dashboard', \App\Livewire\Pages\Customer\Dashboard::class)
        ->name('dashboard');

    Route::prefix('/dashboard')->group(function () {
        Route::get('edit-account', \App\Livewire\Pages\Customer\Dashboard::class)
            ->name('edit-account');

        Route::get('orders', \App\Livewire\Pages\Customer\Dashboard::class)
            ->name('orders');

        Route::get('addresses', \App\Livewire\Pages\Customer\Dashboard::class)
            ->name('addresses');

        Route::get('change-password', \App\Livewire\Pages\Customer\Dashboard::class)
            ->name('change-password');
    });

});

/* --- Logged In (back-office) --- */
Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->prefix('/admin')->name('admin.')->group(function () {

    Route::get('/', \App\Livewire\Admin\Dashboard::class)
        ->name('dashboard');

    Route::get('/pages', \App\Livewire\Admin\Pages::class)
        ->name('pages');

    Route::get('/blog', \App\Livewire\Admin\Blog::class)
        ->name('blog');

    Route::get('/ecommerce', \App\Livewire\Admin\Ecommerce::class)
        ->name('ecommerce');

    Route::get('/media', \App\Livewire\Admin\Media::class)
        ->name('media');

    Route::get('/theme', \App\Livewire\Admin\Theme::class)
        ->name('theme');

    Route::get('/translations', \App\Livewire\Admin\Translations::class)
        ->name('translations');

    Route::get('/settings', \App\Livewire\Admin\Settings::class)
        ->name('settings');

    Route::get('/users', \App\Livewire\Admin\Users::class)
        ->name('users');

});

/* --- 404 --- */
Route::fallback(\App\Livewire\Error404::class);



require __DIR__.'/auth.php';
