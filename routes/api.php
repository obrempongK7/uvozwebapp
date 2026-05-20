<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\ApiPostController;
use App\Http\Controllers\Api\ApiWalletController;

Route::prefix('v1')->group(function () {
    Route::post('/auth/register', [ApiAuthController::class, 'register']);
    Route::post('/auth/login',    [ApiAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [ApiAuthController::class, 'logout']);
        Route::get('/user',         [ApiUserController::class, 'me']);
        Route::get('/users/{username}', [ApiUserController::class, 'show']);
        Route::get('/posts',        [ApiPostController::class, 'index']);
        Route::post('/posts',       [ApiPostController::class, 'store']);
        Route::get('/posts/{id}',   [ApiPostController::class, 'show']);
        Route::get('/wallet',       [ApiWalletController::class, 'index']);
        Route::get('/wallet/transactions', [ApiWalletController::class, 'transactions']);
    });
});
