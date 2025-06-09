<?php

use App\Http\Controllers\FlightController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Rute untuk halaman depan
Route::get('/', function () {
    return view('welcome');
});

// Rute untuk dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute untuk menampilkan form login
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');

// Rute untuk memproses login
Route::post('login', [AuthController::class, 'login'])->name('login.submit');

// Rute untuk logout
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Rute untuk profile
Route::view('profile', 'profile')->middleware('auth')->name('profile');

// Rute Resource untuk Flight
Route::resource('flights', FlightController::class)
    ->middleware('auth')  // Tambahkan middleware hanya sekali
    ->names([
        'index' => 'flights.index',
        'create' => 'flights.create',
        'store' => 'flights.store',
        'show' => 'flights.show',
        'edit' => 'flights.edit',
        'update' => 'flights.update',
        'destroy' => 'flights.destroy',
    ]);

// Rute Resource untuk History
Route::resource('history', HistoryController::class)
    ->middleware('auth')
    ->names([
        'index' => 'history.index',
        'create' => 'history.create',
        'store' => 'history.store',
        'show' => 'history.show',
        'edit' => 'history.edit',
        'update' => 'history.update',
        'destroy' => 'history.destroy',
    ]);

// Rute Resource untuk Payments
Route::resource('payments', PaymentController::class)
    ->middleware('auth')
    ->names([
        'index' => 'payments.index',
        'create' => 'payments.create',
        'store' => 'payments.store',
        'show' => 'payments.show',
        'edit' => 'payments.edit',
        'update' => 'payments.update',
        'destroy' => 'payments.destroy',
    ]);

// Rute Resource untuk Promo
Route::resource('promo', PromoController::class)
    ->middleware('auth')
    ->names([
        'index' => 'promo.index',
        'create' => 'promo.create',
        'store' => 'promo.store',
        'show' => 'promo.show',
        'edit' => 'promo.edit',
        'update' => 'promo.update',
        'destroy' => 'promo.destroy',
    ]);

// Rute Resource untuk Tickets
Route::resource('tickets', TicketController::class)
    ->middleware('auth')
    ->names([
        'index' => 'tickets.index',
        'create' => 'tickets.create',
        'store' => 'tickets.store',
        'show' => 'tickets.show',
        'edit' => 'tickets.edit',
        'update' => 'tickets.update',
        'destroy' => 'tickets.destroy',
    ]);

// Menambahkan rute khusus untuk update pada tickets jika diperlukan
Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');

// Memuat file auth.php untuk rute otentikasi
require __DIR__ . '/auth.php';
