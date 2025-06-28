<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes API publiques pour les produits
Route::prefix('products')->group(function () {
    Route::get('/', [App\Http\Controllers\ProductController::class, 'index']);
    Route::get('/{product}', [App\Http\Controllers\ProductController::class, 'show']);
    Route::get('/search', [App\Http\Controllers\ProductController::class, 'search']);
});

// Routes API pour les utilisateurs authentifiés
Route::middleware('auth:sanctum')->group(function () {
    // Actions personnelles de l'utilisateur
    Route::prefix('user')->group(function () {
        Route::post('/newsletter/subscribe', [App\Http\Controllers\UserController::class, 'subscribeNewsletter']);
        Route::post('/newsletter/unsubscribe', [App\Http\Controllers\UserController::class, 'unsubscribeNewsletter']);
        Route::get('/export-data', [App\Http\Controllers\UserController::class, 'exportData']);
    });

    // Actions sur les produits pour utilisateurs connectés
    Route::prefix('products')->group(function () {
        Route::post('/{product}/like', [App\Http\Controllers\ProductController::class, 'like']);
        Route::post('/{product}/wishlist', [App\Http\Controllers\ProductController::class, 'wishlist']);
    });

    // API d'administration
    Route::middleware(['permission:manage users'])->prefix('admin')->group(function () {
        Route::get('/users/statistics', [App\Http\Controllers\UserController::class, 'statistics']);
        Route::post('/users/bulk-action', [App\Http\Controllers\UserController::class, 'bulkAction']);
        Route::post('/users/{id}/restore', [App\Http\Controllers\UserController::class, 'restore']);
        Route::delete('/users/{id}/force-delete', [App\Http\Controllers\UserController::class, 'forceDelete']);
        
        // API CRUD pour les utilisateurs
        Route::apiResource('users', App\Http\Controllers\UserController::class);
    });

    // API d'administration pour les produits
    Route::middleware(['permission:manage products'])->prefix('admin')->group(function () {
        // API principale: Gestion de stock automatisée
        Route::post('/products/{product}/update-stock', [App\Http\Controllers\ProductController::class, 'updateStock']);
        
        // Statistiques et actions en lot
        Route::get('/products/statistics', [App\Http\Controllers\ProductController::class, 'statistics']);
        Route::post('/products/bulk-action', [App\Http\Controllers\ProductController::class, 'bulkAction']);
        
        // API CRUD pour les produits
        Route::apiResource('products', App\Http\Controllers\ProductController::class);
    });
});
