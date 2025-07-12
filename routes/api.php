<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CartLocationController;
use App\Http\Controllers\CartItemLocationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductLikeController;
use App\Http\Controllers\RentalCategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ContactController;
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
    
    // Routes panier pour utilisateurs connectés
    Route::prefix('cart')->name('api.cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/products/{product}', [CartController::class, 'addProduct'])->name('add-product');
        Route::put('/products/{product}', [CartController::class, 'updateQuantity'])->name('update-quantity');
        Route::delete('/products/{product}', [CartController::class, 'removeProduct'])->name('remove-product');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
        Route::get('/availability', [CartController::class, 'checkAvailability'])->name('check-availability');
        Route::get('/summary', [CartController::class, 'summary'])->name('summary');
        Route::get('/checkout/prepare', [CartController::class, 'prepareForCheckout'])->name('prepare-checkout');
    });
    
    // Routes éléments de panier pour utilisateurs connectés
    Route::prefix('cart-items')->name('api.cart-items.')->group(function () {
        Route::get('/', [CartItemController::class, 'index'])->name('index');
        Route::get('/{cartItem}', [CartItemController::class, 'show'])->name('show');
        Route::put('/{cartItem}', [CartItemController::class, 'update'])->name('update');
        Route::delete('/{cartItem}', [CartItemController::class, 'destroy'])->name('destroy');
        Route::get('/{cartItem}/availability', [CartItemController::class, 'checkAvailability'])->name('check-availability');
        Route::post('/{cartItem}/duplicate', [CartItemController::class, 'duplicate'])->name('duplicate');
        Route::patch('/{cartItem}/details', [CartItemController::class, 'updateDetails'])->name('update-details');
    });
    
    // Routes panier de location pour utilisateurs connectés
    Route::prefix('cart-location')->name('api.cart-location.')->group(function () {
        Route::get('/', [CartLocationController::class, 'index'])->name('index');
        Route::post('/products/{product}', [CartLocationController::class, 'addProduct'])->name('add-product');
        Route::put('/products/{product}/quantity', [CartLocationController::class, 'updateQuantity'])->name('update-quantity');
        Route::put('/products/{product}/dates', [CartLocationController::class, 'updateDates'])->name('update-dates');
        Route::delete('/products/{product}', [CartLocationController::class, 'removeProduct'])->name('remove-product');
        Route::delete('/clear', [CartLocationController::class, 'clear'])->name('clear');
        Route::get('/availability', [CartLocationController::class, 'checkAvailability'])->name('check-availability');
        Route::get('/summary', [CartLocationController::class, 'summary'])->name('summary');
        Route::put('/default-dates', [CartLocationController::class, 'updateDefaultDates'])->name('update-default-dates');
        Route::get('/checkout/prepare', [CartLocationController::class, 'prepareForCheckout'])->name('prepare-checkout');
    });
    
    // Routes éléments de panier de location pour utilisateurs connectés
    Route::prefix('cart-location-items')->name('api.cart-location-items.')->group(function () {
        Route::get('/', [CartItemLocationController::class, 'index'])->name('index');
        Route::get('/{cartItemLocation}', [CartItemLocationController::class, 'show'])->name('show');
        Route::put('/{cartItemLocation}/quantity', [CartItemLocationController::class, 'updateQuantity'])->name('update-quantity');
        Route::put('/{cartItemLocation}/dates', [CartItemLocationController::class, 'updateDates'])->name('update-dates');
        Route::delete('/{cartItemLocation}', [CartItemLocationController::class, 'destroy'])->name('destroy');
        Route::get('/{cartItemLocation}/availability', [CartItemLocationController::class, 'checkAvailability'])->name('check-availability');
        Route::post('/{cartItemLocation}/duplicate', [CartItemLocationController::class, 'duplicate'])->name('duplicate');
        Route::patch('/{cartItemLocation}/details', [CartItemLocationController::class, 'updateDetails'])->name('update-details');
        Route::get('/{cartItemLocation}/suggest-dates', [CartItemLocationController::class, 'suggestOptimalDates'])->name('suggest-dates');
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
            Route::get('/stats', [CategoryController::class, 'adminStats'])->name('stats');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/restore', [CategoryController::class, 'restore'])->name('restore');
            Route::patch('/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('toggle-status');
        });
        
        // Gestion des catégories de location (Admin seulement)
        Route::prefix('admin/rental-categories')->name('api.admin.rental-categories.')->group(function () {
            Route::get('/stats', [RentalCategoryController::class, 'adminStats'])->name('stats');
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
        
        // Gestion des wishlists (Admin seulement)
        Route::prefix('admin/wishlists')->name('api.admin.wishlists.')->group(function () {
            Route::get('/stats', [WishlistController::class, 'adminStats'])->name('stats');
            Route::get('/', [WishlistController::class, 'adminIndex'])->name('index');
            Route::delete('/{wishlist}', [WishlistController::class, 'adminDestroy'])->name('destroy');
            Route::get('/users/{userId}/analysis', [WishlistController::class, 'adminUserAnalysis'])->name('user-analysis');
        });
        
        // Gestion des paniers (Admin seulement)
        Route::prefix('admin/carts')->name('api.admin.carts.')->group(function () {
            Route::get('/stats', [CartController::class, 'adminStats'])->name('stats');
            Route::get('/', [CartController::class, 'adminIndex'])->name('index');
        });
        
        // Gestion des éléments de panier (Admin seulement)
        Route::prefix('admin/cart-items')->name('api.admin.cart-items.')->group(function () {
            Route::get('/stats', [CartItemController::class, 'adminStats'])->name('stats');
            Route::get('/', [CartItemController::class, 'adminIndex'])->name('index');
        });
        
        // Gestion des paniers de location (Admin seulement)
        Route::prefix('admin/cart-locations')->name('api.admin.cart-locations.')->group(function () {
            Route::get('/stats', [CartLocationController::class, 'adminStats'])->name('stats');
            Route::get('/', [CartLocationController::class, 'adminIndex'])->name('index');
        });
        
        // Gestion des éléments de panier de location (Admin seulement)
        Route::prefix('admin/cart-location-items')->name('api.admin.cart-location-items.')->group(function () {
            Route::get('/stats', [CartItemLocationController::class, 'adminStats'])->name('stats');
            Route::get('/', [CartItemLocationController::class, 'adminIndex'])->name('index');
        });
        
        // Gestion des contacts (Admin seulement)
        Route::prefix('admin/contacts')->name('api.admin.contacts.')->group(function () {
            Route::get('/', [ContactController::class, 'index'])->name('index');
            Route::get('/statistics', [ContactController::class, 'statistics'])->name('statistics');
            Route::get('/{contact}', [ContactController::class, 'show'])->name('show');
            Route::post('/{contact}/respond', [ContactController::class, 'respond'])->name('respond');
            Route::patch('/{contact}/status', [ContactController::class, 'updateStatus'])->name('update-status');
            Route::patch('/{contact}/priority', [ContactController::class, 'updatePriority'])->name('update-priority');
            Route::delete('/{contact}', [ContactController::class, 'destroy'])->name('destroy');
            Route::post('/mark-as-read', [ContactController::class, 'markAsRead'])->name('mark-as-read');
        });
    });
});

// Route publique pour le formulaire de contact
Route::post('/contact', [ContactController::class, 'store'])->name('api.contact.store');
