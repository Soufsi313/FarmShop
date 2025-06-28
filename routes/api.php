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
    // Routes API pour le panier (Cart) - Utilisateurs connectés uniquement
    Route::prefix('cart')->group(function () {
        Route::get('/', [App\Http\Controllers\CartController::class, 'index']); // Obtenir le contenu du panier
        Route::post('/add', [App\Http\Controllers\CartController::class, 'store']); // Ajouter un produit au panier
        Route::put('/update/{cart}', [App\Http\Controllers\CartController::class, 'update']); // Modifier la quantité d'un article
        Route::delete('/remove/{cart}', [App\Http\Controllers\CartController::class, 'destroy']); // Supprimer un article du panier
        Route::delete('/clear', [App\Http\Controllers\CartController::class, 'clear']); // Vider complètement le panier
        Route::post('/quick-add/{product}', [App\Http\Controllers\CartController::class, 'quickAdd']); // Ajout rapide d'un produit (quantité 1)
        Route::post('/sync', [App\Http\Controllers\CartController::class, 'sync']); // Synchroniser le panier avec les données côté client
        Route::post('/validate', [App\Http\Controllers\CartController::class, 'validateCart']); // Valider le panier (stock, prix, etc.)
        Route::get('/count', [App\Http\Controllers\CartController::class, 'getCartCount']); // Obtenir le nombre total d'articles
        Route::get('/total', [App\Http\Controllers\CartController::class, 'getCartTotal']); // Obtenir le montant total du panier
    });

    // Routes API pour le panier de location (CartLocation) - Utilisateurs connectés uniquement
    Route::prefix('cart-location')->group(function () {
        Route::get('/', [App\Http\Controllers\CartLocationController::class, 'index']); // Obtenir le contenu du panier de location
        Route::post('/add', [App\Http\Controllers\CartLocationController::class, 'store']); // Ajouter un produit au panier de location
        Route::get('/{cart_location}', [App\Http\Controllers\CartLocationController::class, 'show']); // Obtenir un article spécifique
        Route::put('/update/{cart_location}', [App\Http\Controllers\CartLocationController::class, 'update']); // Modifier un article de location
        Route::delete('/remove/{cart_location}', [App\Http\Controllers\CartLocationController::class, 'destroy']); // Supprimer un article du panier de location
        Route::delete('/clear', [App\Http\Controllers\CartLocationController::class, 'clear']); // Vider complètement le panier de location
        Route::post('/quick-add/{product}', [App\Http\Controllers\CartLocationController::class, 'quickAdd']); // Ajout rapide d'un produit en location
        Route::post('/sync', [App\Http\Controllers\CartLocationController::class, 'sync']); // Synchroniser le panier de location
        Route::post('/validate', [App\Http\Controllers\CartLocationController::class, 'validateCart']); // Valider le panier de location
        Route::get('/count', [App\Http\Controllers\CartLocationController::class, 'getCartCount']); // Obtenir le nombre total d'articles en location
        Route::get('/total', [App\Http\Controllers\CartLocationController::class, 'getCartTotal']); // Obtenir le montant total du panier de location
        Route::post('/{cart_location}/extend', [App\Http\Controllers\CartLocationController::class, 'extend']); // Prolonger une location
    });

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
