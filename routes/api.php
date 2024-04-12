<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Group for auth-related routes with 'auth/' prefix
Route::prefix('auth')->group(function () {
    Route::post('/signup', [AuthController::class, 'register'])->middleware('throttle:5,1');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
         ->name('verification.verify')
         ->middleware(['signed', 'throttle:6,1']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->middleware('throttle:3,1');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->middleware('throttle:5,1');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::middleware('auth:api')->get('/user', [AuthController::class, 'fetchUserDetails']);

});

// Group for user-related routes with 'user/' prefix
Route::prefix('user')->middleware('auth:api')->group(function () {
    Route::get('/ticketCounts', [UserController::class, 'ticketCounts']);
    Route::get('/fetchEvents', [UserController::class, 'fetchEvents']);

});