<?php

use App\Http\Controllers\Api\AuthApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes untuk Aplikasi Android / Mobile
|--------------------------------------------------------------------------
*/

// Endpoint Login Mobile Publik (Mendapatkan Sanctum Bearer Token)
Route::post('/login', [AuthApiController::class, 'login'])->middleware('throttle:15,1');

// Endpoint Protected Mobile (Membutuhkan Header "Authorization: Bearer <token>")
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthApiController::class, 'me']);
    Route::get('/me', [AuthApiController::class, 'me']);
    Route::post('/logout', [AuthApiController::class, 'logout']);
});
