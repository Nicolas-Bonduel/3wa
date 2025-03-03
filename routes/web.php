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

    Route::get('edit-account', \App\Livewire\Pages\Customer\Dashboard::class)
        ->name('edit-account');

    Route::get('orders', \App\Livewire\Pages\Customer\Dashboard::class)
        ->name('orders');

    Route::get('addresses', \App\Livewire\Pages\Customer\Dashboard::class)
        ->name('addresses');

    Route::get('change-password', \App\Livewire\Pages\Customer\Dashboard::class)
        ->name('change-password');

});

Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->prefix('/admin')->name('admin.')->group(function () {

    Route::get('/', \App\Livewire\Admin\Dashboard::class)
        ->name('dashboard');

});





require __DIR__.'/auth.php';
