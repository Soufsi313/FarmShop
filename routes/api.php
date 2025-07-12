<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductLikeController;
use App\Http\Controllers\RentalCategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Routes publiques pour l'inscription
Route::post('/register', [UserController::class, 'store'])->name('api.users.register');

// Routes publiques pour les catégories (consultation)
Route::get('/categories', [CategoryController::class, 'index'])->name('api.categories.index');
Route::get('/categories/active', [CategoryController::class, 'active'])->name('api.categories.active');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('api.categories.show');

// Routes publiques pour les catégories de location (consultation)
Route::get('/rental-categories', [RentalCategoryController::class, 'index'])->name('api.rental-categories.index');
Route::get('/rental-categories/active', [RentalCategoryController::class, 'active'])->name('api.rental-categories.active');
Route::get('/rental-categories/{rentalCategory}', [RentalCategoryController::class, 'show'])->name('api.rental-categories.show');

// Routes publiques pour les produits (consultation, recherche, tri)
Route::get('/products', [ProductController::class, 'index'])->name('api.products.index');
Route::get('/products/search', [ProductController::class, 'search'])->name('api.products.search');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('api.products.show');
Route::get('/products/category/{category}', [ProductController::class, 'byCategory'])->name('api.products.by-category');

// Routes publiques pour les likes (consultation seulement)
Route::get('/products-likes', [ProductLikeController::class, 'index'])->name('api.product-likes.index');
Route::get('/products/{product}/likes', [ProductLikeController::class, 'show'])->name('api.product-likes.show');
Route::get('/products/{product}/likes/check', [ProductLikeController::class, 'check'])->name('api.product-likes.check');

// Routes protégées nécessitant une authentification
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Routes utilisateur standard
    Route::get('/profile', [UserController::class, 'show'])->name('api.users.profile');
    Route::put('/profile', [UserController::class, 'update'])->name('api.users.update');
    Route::delete('/profile/self-delete', [UserController::class, 'selfDelete'])->name('api.users.self-delete');
    Route::get('/profile/download-data', [UserController::class, 'downloadData'])->name('api.users.download-data');
    
    // Routes newsletter
    Route::post('/newsletter/subscribe', [UserController::class, 'subscribeNewsletter'])->name('api.newsletter.subscribe');
    Route::post('/newsletter/unsubscribe', [UserController::class, 'unsubscribeNewsletter'])->name('api.newsletter.unsubscribe');
    
    // Routes produits pour utilisateurs connectés
    Route::prefix('products')->name('api.products.')->group(function () {
        Route::post('/{product}/like', [ProductController::class, 'toggleLike'])->name('toggle-like');
        Route::post('/{product}/wishlist', [ProductController::class, 'toggleWishlist'])->name('toggle-wishlist');
        Route::get('/wishlist', [ProductController::class, 'getWishlist'])->name('wishlist');
        Route::get('/liked', [ProductController::class, 'getLikedProducts'])->name('liked');
    });
    
    // Routes likes pour utilisateurs connectés
    Route::prefix('likes')->name('api.likes.')->group(function () {
        Route::post('/products/{product}', [ProductLikeController::class, 'store'])->name('add');
        Route::delete('/products/{product}', [ProductLikeController::class, 'destroy'])->name('remove');
        Route::post('/products/{product}/toggle', [ProductLikeController::class, 'toggle'])->name('toggle');
        Route::get('/my-likes', [ProductLikeController::class, 'getUserLikes'])->name('user-likes');
    });
    
    // Routes wishlist pour utilisateurs connectés
    Route::prefix('wishlist')->name('api.wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/products/{product}', [WishlistController::class, 'store'])->name('add');
        Route::delete('/products/{product}', [WishlistController::class, 'destroy'])->name('remove');
        Route::post('/products/{product}/toggle', [WishlistController::class, 'toggle'])->name('toggle');
        Route::delete('/clear', [WishlistController::class, 'clear'])->name('clear');
        Route::get('/count', [WishlistController::class, 'count'])->name('count');
        Route::get('/products/{product}/check', [WishlistController::class, 'check'])->name('check');
    });
    
    // Routes administration (Admin seulement)
    Route::middleware(['admin'])->group(function () {
        // Gestion des utilisateurs
        Route::get('/admin/users', [UserController::class, 'index'])->name('api.admin.users.index');
        Route::get('/admin/users/{user}', [UserController::class, 'show'])->name('api.admin.users.show');
        Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('api.admin.users.update');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('api.admin.users.destroy');
        Route::post('/admin/users/{id}/restore', [UserController::class, 'restore'])->name('api.admin.users.restore');
        Route::post('/admin/users', [UserController::class, 'store'])->name('api.admin.users.store');
        
        // Gestion des catégories (Admin seulement)
        Route::prefix('admin/categories')->name('api.admin.categories.')->group(function () {
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/restore', [CategoryController::class, 'restore'])->name('restore');
            Route::patch('/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('toggle-status');
        });
        
        // Gestion des catégories de location (Admin seulement)
        Route::prefix('admin/rental-categories')->name('api.admin.rental-categories.')->group(function () {
            Route::post('/', [RentalCategoryController::class, 'store'])->name('store');
            Route::put('/{rentalCategory}', [RentalCategoryController::class, 'update'])->name('update');
            Route::delete('/{rentalCategory}', [RentalCategoryController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/restore', [RentalCategoryController::class, 'restore'])->name('restore');
            Route::patch('/{rentalCategory}/toggle-status', [RentalCategoryController::class, 'toggleStatus'])->name('toggle-status');
        });
        
        // Gestion des produits (Admin seulement)
        Route::prefix('admin/products')->name('api.admin.products.')->group(function () {
            Route::get('/', [ProductController::class, 'adminIndex'])->name('index');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/{product}', [ProductController::class, 'adminShow'])->name('show');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/restore', [ProductController::class, 'restore'])->name('restore');
            Route::patch('/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('toggle-status');
            
            // Gestion de stock
            Route::post('/{product}/stock/update', [ProductController::class, 'updateStock'])->name('stock.update');
            Route::get('/stock/alerts', [ProductController::class, 'getStockAlerts'])->name('stock.alerts');
            Route::get('/stock/low', [ProductController::class, 'getLowStockProducts'])->name('stock.low');
        });
        
        // Gestion des likes (Admin seulement)
        Route::prefix('admin/likes')->name('api.admin.likes.')->group(function () {
            Route::get('/stats', [ProductLikeController::class, 'adminStats'])->name('stats');
            Route::get('/', [ProductLikeController::class, 'adminIndex'])->name('index');
            Route::delete('/{like}', [ProductLikeController::class, 'adminDestroy'])->name('destroy');
        });
    });
});
