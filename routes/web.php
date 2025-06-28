<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route de test pour vérifier les utilisateurs créés
Route::get('/test-users', function () {
    $users = \App\Models\User::with('roles')->get();
    return view('test-users', compact('users'));
})->middleware('auth');

// Routes publiques pour les produits (accessibles à tous)
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [App\Http\Controllers\ProductController::class, 'index'])->name('index');
    Route::get('/{product:slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('show');
    Route::get('/search', [App\Http\Controllers\ProductController::class, 'search'])->name('search');
    
    // Routes pour utilisateurs connectés
    Route::middleware('auth')->group(function () {
        Route::post('/{product}/like', [App\Http\Controllers\ProductController::class, 'like'])->name('like');
        Route::post('/{product}/wishlist', [App\Http\Controllers\ProductController::class, 'wishlist'])->name('wishlist');
    });
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Routes pour les utilisateurs (actions personnelles)
    Route::prefix('user')->name('user.')->group(function () {
        Route::post('/newsletter/subscribe', [App\Http\Controllers\UserController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
        Route::post('/newsletter/unsubscribe', [App\Http\Controllers\UserController::class, 'unsubscribeNewsletter'])->name('newsletter.unsubscribe');
        Route::get('/export-data', [App\Http\Controllers\UserController::class, 'exportData'])->name('export.data');
    });

    // Routes d'administration
    Route::middleware(['permission:manage users'])->prefix('admin')->name('admin.')->group(function () {
        // CRUD complet pour les utilisateurs
        Route::resource('users', App\Http\Controllers\UserController::class);
        
        // Routes supplémentaires pour la gestion des utilisateurs
        Route::post('/users/{id}/restore', [App\Http\Controllers\UserController::class, 'restore'])->name('users.restore');
        Route::delete('/users/{id}/force-delete', [App\Http\Controllers\UserController::class, 'forceDelete'])->name('users.force-delete');
        Route::post('/users/bulk-action', [App\Http\Controllers\UserController::class, 'bulkAction'])->name('users.bulk-action');
        Route::get('/users-statistics', [App\Http\Controllers\UserController::class, 'statistics'])->name('users.statistics');
    });

    // Routes d'administration pour les produits
    Route::middleware(['permission:manage products'])->prefix('admin')->name('admin.')->group(function () {
        // CRUD complet pour les produits
        Route::resource('products', App\Http\Controllers\ProductController::class)->except(['show']);
        
        // Routes supplémentaires pour la gestion des produits
        Route::post('/products/bulk-action', [App\Http\Controllers\ProductController::class, 'bulkAction'])->name('products.bulk-action');
        Route::get('/products-statistics', [App\Http\Controllers\ProductController::class, 'statistics'])->name('products.statistics');
    });
});
