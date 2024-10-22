<?php

use App\Http\Controllers\{AuthController, PasswordResetController, UserController};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/sing-up', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.reset');

Route::middleware('auth:api')->group(function () {
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);


    Route::post('/logout', [AuthController::class, 'logout']);


    Route::get('/profile', [UserController::class, 'show'])->middleware('auth:api');
    Route::post('/edit-profile', [UserController::class, 'update']);

});

