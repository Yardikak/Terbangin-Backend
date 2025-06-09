<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Mengganti Volt::route dengan Route::view
    Route::view('register', 'auth.register')->name('register');
    Route::view('login', 'auth.login')->name('login'); // Menghilangkan 'pages.' di sini
    Route::view('forgot-password', 'auth.forgot-password')->name('password.request');
    Route::view('reset-password/{token}', 'auth.reset-password')->name('password.reset');
});

Route::middleware('auth')->group(function () {
    // Mengganti Volt::route dengan Route::view
    Route::view('verify-email', 'auth.verify-email')->name('verification.notice');
    
    // Rute untuk verifikasi email menggunakan controller
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    
    Route::view('confirm-password', 'auth.confirm-password')->name('password.confirm');
});
