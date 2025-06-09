<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\FlightController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\HistoryController;
use App\Http\Controllers\Api\KNNController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    
    Route::post('/flights/search', [FlightController::class, 'search']);
    Route::apiResource('passengers', PassengerController::class);
    Route::apiResource('flights', FlightController::class);

    Route::apiResource('payments', PaymentController::class);
    Route::apiResource('histories', HistoryController::class);
    
    Route::post('/payments/notification', [PaymentController::class, 'handleNotification']);
    Route::get('/payment/finish', [PaymentController::class, 'paymentFinish']);
    Route::get('/payment/error', [PaymentController::class, 'paymentError']);
    Route::get('/payment/pending', [PaymentController::class, 'paymentPending']);
    
    Route::get('/tickets/user/{user_id}', [TicketController::class, 'getTicketsByUser']);
    Route::apiResource('tickets', TicketController::class);
});
Route::post('/promos/search', [PromoController::class, 'search']);

Route::apiResource('promos', PromoController::class);
Route::get('/knn/recommend', [KNNController::class, 'recommendLeastPopular']);