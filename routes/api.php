<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScreeningController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::apiResource('users', UserController::class);

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    Route::put('/profile', [ProfileController::class, 'updateProfile']);
    Route::delete('/profile', [ProfileController::class, 'deleteProfile']);
});

// Movie routes
Route::apiResource('movies', MovieController::class);

// Screening routes
Route::apiResource('screenings', ScreeningController::class);
Route::get('/screenings/filter/{type}', [ScreeningController::class, 'filterByType']);

// Hall routes
Route::apiResource('halls', HallController::class);

// Seat routes
Route::apiResource('seats', SeatController::class);
Route::get('/halls/{hallId}/available-seats', [SeatController::class, 'getAvailableSeats']);
Route::post('/seats/reserve', [SeatController::class, 'reserveSeats']);

Route::middleware('auth:api')->group(function () {
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::put('/reservations/{id}/cancel', [ReservationController::class, 'cancel']);
});

Route::middleware('auth:api')->group(function () {
    Route::put('/reservations/{id}/cancel', [ReservationController::class, 'cancel']);
    Route::put('/reservations/{id}', [ReservationController::class, 'update']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('/payment/create-intent', [PaymentController::class, 'createPaymentIntent']);
    Route::get('/payment/status', [PaymentController::class, 'getPaymentStatus']);
    
    Route::post('/payment/webhook', [PaymentController::class, 'handleWebhook']);
});

Route::middleware('auth:api')->group(function () {
    // Ticket routes
    Route::get('/tickets/{ticket}/download', [TicketController::class, 'download']);
    Route::post('/tickets/validate', [TicketController::class, 'validateTicket']);
});

Route::middleware('auth:api')->group(function () {
    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'getDashboardStats']);
        Route::get('/movies/stats', [AdminController::class, 'getMovieStats']);
        Route::get('/screenings/stats', [AdminController::class, 'getScreeningStats']);
        Route::get('/users/stats', [AdminController::class, 'getUserStats']);
    });
});
