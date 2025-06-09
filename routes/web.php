<?php

use App\Http\Controllers\FlightController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Rute untuk halaman depan
Route::get('/', function () {
    return view('welcome');
});

// Rute untuk dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Rute untuk menampilkan form login
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');

// Rute untuk memproses login
Route::post('login', [AuthController::class, 'login'])->name('login.submit');

// Rute untuk logout
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Rute untuk menampilkan form registrasi
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');

// Rute untuk memproses registrasi
Route::post('register', [AuthController::class, 'register'])->name('register.submit');

// Rute untuk profile
Route::view('profile', 'profile')->middleware('auth')->name('profile');

// Rute Resource untuk Flight
Route::resource('flights', FlightController::class)
    ->middleware('auth')  // Tambahkan middleware hanya sekali
    ->names([
        'index' => 'flights.index',
        'create' => 'flights.create',
        'destroy' => 'flights.destroy',
    ]);
Route::get('/ticket-flight/{ticket_id}', 'TicketController@getFlightId');
Route::get('/flight/{flight_id}', 'FlightController@getPrice');
// Rute Resource untuk History
Route::resource('history', HistoryController::class)
    ->middleware('auth')
    ->names([
        'index' => 'history.index',
        'create' => 'history.create',
        'destroy' => 'history.destroy',
    ]);

// Rute Resource untuk Payments
Route::resource('payments', PaymentController::class)
    ->middleware('auth')
    ->names([
        'index' => 'payments.index',
        'create' => 'payments.create',
        'destroy' => 'payments.destroy',
    ]);

// Rute Resource untuk Promo
Route::resource('promo', PromoController::class)
    ->middleware('auth')
    ->names([
        'index' => 'promo.index',
        'create' => 'promo.create',
        'destroy' => 'promo.destroy',
    ]);

// Rute Resource untuk Tickets
Route::resource('tickets', TicketController::class)
    ->middleware('auth')
    ->names([
        'index' => 'tickets.index',
        'create' => 'tickets.create',
        'update' => 'tickets.update',
        'destroy' => 'tickets.destroy',
    ]);

// Menambahkan rute khusus untuk update pada tickets jika diperlukan
Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');

Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');

// Memuat file auth.php untuk rute otentikasi
require __DIR__ . '/auth.php';
