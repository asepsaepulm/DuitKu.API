<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TransactionController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::post('/register', [
    AuthController::class,
    'register'
]);

Route::post('/login', [
    AuthController::class,
    'login'
]);

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [
        AuthController::class,
        'profile'
    ]);

    Route::post('/logout', [
        AuthController::class,
        'logout'
    ]);

    Route::apiResource(
        'wallets',
        WalletController::class
    );

    Route::apiResource(
        'categories',
        CategoryController::class
    );

    Route::apiResource(
        'transactions',
        TransactionController::class
    );
});