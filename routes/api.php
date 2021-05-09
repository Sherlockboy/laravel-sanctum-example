<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------|
*/

Route::prefix('auth')->middleware('guest')->group(function() {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
});
