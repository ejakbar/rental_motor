<?php

use App\Http\Controllers\Api\MobileApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mobile API Routes (Sesuai PRD: Target Platform Aplikasi Mobile)
|--------------------------------------------------------------------------
*/

// 1. Auth Mobile
Route::prefix('auth')->group(function () {
    Route::post('/login', [MobileApiController::class, 'login']);
    Route::post('/register', [MobileApiController::class, 'register']);
});

// 2. Public Catalog & Motor Details
Route::get('/motors', [MobileApiController::class, 'publicMotors']);
Route::get('/motors/{id}', [MobileApiController::class, 'motorDetail']);

// 3. Renter Mobile API
Route::prefix('renter')->group(function () {
    Route::get('/bookings', [MobileApiController::class, 'renterBookings']);
    Route::post('/bookings', [MobileApiController::class, 'renterCreateBooking']);
});

// 4. Payments API
Route::post('/payments/{id}/simulate', [MobileApiController::class, 'simulatePayment']);

// 5. Owner Mobile API
Route::prefix('owner')->group(function () {
    Route::get('/motors', [MobileApiController::class, 'ownerMotors']);
    Route::get('/orders', [MobileApiController::class, 'ownerOrders']);
    Route::post('/orders/{id}/handover', [MobileApiController::class, 'ownerHandover']);
    Route::post('/orders/{id}/complete', [MobileApiController::class, 'ownerComplete']);
    Route::get('/wallet', [MobileApiController::class, 'ownerWallet']);
});

