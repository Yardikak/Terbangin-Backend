<?php

use App\Http\Controllers\FlightController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\TicketController;

use Illuminate\Support\Facades\Route;
// D:\kuliaho\ABP\Terbangin-Backend\resources\views\welcome\navigation.blade.php
// D:\kuliaho\ABP\Terbangin-Backend\routes\web.php
// D:\kuliaho\ABP\Terbangin-Backend\resources\views\dashboard.blade.php
Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::resource('flights', FlightController::class)
    ->middleware(['auth'])  // Tambahkan middleware jika diperlukan
    ->names([
        'index' => 'flights.index',
        'create' => 'flights.create',
        'store' => 'flights.store',
        'show' => 'flights.show',
        'edit' => 'flights.edit',
        'update' => 'flights.update',
        'destroy' => 'flights.destroy',
    ]);

Route::resource('history', HistoryController::class)
    ->middleware(['auth'])  // Tambahkan middleware jika diperlukan
    ->names([
        'index' => 'history.index',
        'create' => 'history.create',
        'store' => 'history.store',
        'show' => 'history.show',
        'edit' => 'history.edit',
        'update' => 'history.update',
        'destroy' => 'history.destroy',
    ]);

Route::resource('payments', PaymentController::class)
    ->middleware(['auth'])  // Tambahkan middleware jika diperlukan
    ->names([
        'index' => 'payments.index',
        'create' => 'payments.create',
        'store' => 'payments.store',
        'show' => 'payments.show',
        'edit' => 'payments.edit',
        'update' => 'payments.update',
        'destroy' => 'payments.destroy',
    ]);

Route::resource('promo', PromoController::class)
    ->middleware(['auth'])  // Tambahkan middleware jika diperlukan
    ->names([
        'index' => 'promo.index',
        'create' => 'promo.create',
        'store' => 'promo.store',
        'show' => 'promo.show',
        'edit' => 'promo.edit',
        'update' => 'promo.update',
        'destroy' => 'promo.destroy',
    ]);

Route::resource('tickets', TicketController::class)
    ->middleware(['auth'])  // Tambahkan middleware jika diperlukan
    ->names([
        'index' => 'tickets.index',
        'create' => 'tickets.create',
        'store' => 'tickets.store',
        'show' => 'tickets.show',
        'edit' => 'tickets.edit',
        'update' => 'tickets.update',
        'destroy' => 'tickets.destroy',
    ]);
Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');

// // Rute untuk History
// Route::resource('history', HistoryController::class)->names([
//     'index' => 'history.index',  // Menyebutkan nama rute 'history.index'
// ]);
// // Rute untuk History
// Route::resource('promo', PromoController::class)->names([
//     'index' => 'promo.index',  // Menyebutkan nama rute 'history.index'
// ]);

require __DIR__ . '/auth.php';
