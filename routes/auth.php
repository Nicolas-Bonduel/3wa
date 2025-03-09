<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Livewire\Volt\Volt;

/* --- Logged Out (site) --- */
Route::middleware('guest')->group(function () {
    Volt::route('register', 'pages.auth.register')
        ->name('register');

    Volt::route('login', 'pages.auth.login')
        ->name('login');

    /* vv Not implemented yet vv */
//    Volt::route('forgot-password', 'pages.auth.forgot-password')
//        ->name('password.request');

//    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
//        ->name('password.reset');

});

/* --- Logged In (site) --- */
Route::middleware('auth:customer')->group(function () {

    // I know, I know, this should be a Post
    Route::get('logout', function() {
        Auth::guard('customer')->logout();
        Session::invalidate();
        Session::regenerateToken();

        return redirect()->route('login');
    })
        ->name('logout');

    /* vv Not implemented yet vv */
//    Volt::route('verify-email', 'pages.auth.verify-email')
//        ->name('verification.notice');

//    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
//        ->middleware(['signed', 'throttle:6,1'])
//        ->name('verification.verify');

//    Volt::route('confirm-password', 'pages.auth.confirm-password')
//        ->name('password.confirm');
});


Route::get('admin/login', \App\Livewire\Admin\Login::class)
    ->name('admin.login');

/* --- Logged In (back-office) --- */
Route::middleware('auth:user')->prefix('/admin')->name('admin.')->group(function () {

    // I know, I know, this should be a Post
    Route::get('logout', function() {
        Auth::guard('user')->logout();
        Session::invalidate();
        Session::regenerateToken();

        return redirect()->route('admin.login');
    })
        ->name('logout');
});
