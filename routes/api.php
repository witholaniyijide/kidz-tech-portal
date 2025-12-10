<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PushTokenController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Push Notification Routes
|--------------------------------------------------------------------------
| Supports both:
| - Web session auth (for browser/web app)
| - Sanctum token auth (for mobile apps)
*/
Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/push-token', [PushTokenController::class, 'store']);
    Route::delete('/push-token', [PushTokenController::class, 'destroy']);
    Route::post('/push-token/test', [PushTokenController::class, 'test']);
});

// Also allow Sanctum token auth for mobile apps
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/mobile/push-token', [PushTokenController::class, 'store']);
    Route::delete('/mobile/push-token', [PushTokenController::class, 'destroy']);
    Route::post('/mobile/push-token/test', [PushTokenController::class, 'test']);
});
