<?php

use Illuminate\Support\Facades\Route;


//Route::view('profile', 'profile')
//    ->middleware(['auth'])
//    ->name('profile');

Route::name('public.')->group(function () {

    Route::get('/', \App\Livewire\Pages\Home::class)
        ->name('home');

    Route::get('/product/{slug}', \App\Livewire\Pages\Product::class)
        ->name('product');
    Route::get('/products', \App\Livewire\Pages\Products::class)
        ->name('products');

    Route::get('/cart', \App\Livewire\Pages\Cart::class)
        ->name('cart');

});

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





require __DIR__.'/auth.php';
