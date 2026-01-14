<?php

use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/

/**
 * Authentication Routes
 *
 * Route group for handling user authentication via JWT.
 * All routes are prefixed with 'auth'.
 */
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

/**
 * Protected Routes
 *
 * These routes require the user to be authenticated ('auth:api' middleware).
 */
Route::middleware(['auth:api'])->group(function () {

    // Resource routes for Products (index, store, show, update, destroy)
    Route::apiResource('products', ProductController::class);

    // Custom routes for product price management
    Route::get('products/{id}/prices', [ProductController::class, 'getPrices']);
    Route::post('products/{id}/prices', [ProductController::class, 'storePrice']);
});
