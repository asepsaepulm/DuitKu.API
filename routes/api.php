<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SavingsGoalController;
use App\Http\Controllers\Api\RecurringTransactionController;

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

    /*
    |--------------------------------------------------------------------------
    | AUTH
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [
        AuthController::class,
        'profile'
    ]);

    Route::post('/logout', [
        AuthController::class,
        'logout'
    ]);

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [
        DashboardController::class,
        'index'
    ]);

    /*
    |--------------------------------------------------------------------------
    | WALLET
    |--------------------------------------------------------------------------
    */

    Route::apiResource(
        'wallets',
        WalletController::class
    );

    /*
    |--------------------------------------------------------------------------
    | CATEGORY
    |--------------------------------------------------------------------------
    */

    Route::apiResource(
        'categories',
        CategoryController::class
    );

    /*
    |--------------------------------------------------------------------------
    | TRANSACTION
    |--------------------------------------------------------------------------
    */

    Route::apiResource(
        'transactions',
        TransactionController::class
    );

    /*
    |--------------------------------------------------------------------------
    | BUDGET
    |--------------------------------------------------------------------------
    */

    Route::apiResource(
        'budgets',
        BudgetController::class
    );

    /*
    |--------------------------------------------------------------------------
    | SAVINGS GOALS
    |--------------------------------------------------------------------------
    */

    Route::apiResource(
        'savings-goals',
        SavingsGoalController::class
    );

    /*
    |--------------------------------------------------------------------------
    | RECURRING TRANSACTIONS
    |--------------------------------------------------------------------------
    */

    Route::apiResource(
        'recurring-transactions',
        RecurringTransactionController::class
    );

    /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

    Route::get('/notifications', [
        NotificationController::class,
        'index'
    ]);

    Route::put('/notifications/{id}/read', [
        NotificationController::class,
        'read'
    ]);

    /*
    |--------------------------------------------------------------------------
    | STATISTICS
    |--------------------------------------------------------------------------
    */

    Route::get('/statistics/monthly', [
        StatisticsController::class,
        'monthly'
    ]);

    Route::get('/statistics/weekly', [
        StatisticsController::class,
        'weekly'
    ]);

    Route::get('/statistics/category', [
        StatisticsController::class,
        'category'
    ]);
});