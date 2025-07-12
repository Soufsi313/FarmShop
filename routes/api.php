<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CartLocationController;
use App\Http\Controllers\CartItemLocationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductLikeController;
use App\Http\Controllers\RentalCategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\NewsletterSubscriptionController;
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

// Routes publiques pour les catégories de blog (consultation)
Route::get('/blog/categories', [App\Http\Controllers\BlogCategoryController::class, 'index'])->name('api.blog.categories.index');
Route::get('/blog/categories/{slug}', [App\Http\Controllers\BlogCategoryController::class, 'show'])->name('api.blog.categories.show');

// Routes publiques pour les articles de blog (consultation)
Route::get('/blog/posts', [App\Http\Controllers\BlogPostController::class, 'index'])->name('api.blog.posts.index');
Route::get('/blog/posts/{slug}', [App\Http\Controllers\BlogPostController::class, 'show'])->name('api.blog.posts.show');
Route::get('/blog/posts/tag/{tag}', [App\Http\Controllers\BlogPostController::class, 'byTag'])->name('api.blog.posts.by-tag');

// Routes publiques pour les commentaires de blog (consultation)
Route::get('/blog/posts/{blogPost}/comments', [App\Http\Controllers\BlogCommentController::class, 'show'])->name('api.blog.comments.show');
Route::get('/blog/comments/{blogComment}/replies', [App\Http\Controllers\BlogCommentController::class, 'replies'])->name('api.blog.comments.replies');

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
    
    // Routes messages (boîte de réception)
    Route::prefix('messages')->name('api.messages.')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('index');
        Route::get('/stats', [MessageController::class, 'getStats'])->name('stats');
        Route::get('/{message}', [MessageController::class, 'show'])->name('show');
        Route::put('/{message}/read', [MessageController::class, 'markAsRead'])->name('mark-read');
        Route::put('/{message}/unread', [MessageController::class, 'markAsUnread'])->name('mark-unread');
        Route::put('/{message}/archive', [MessageController::class, 'archive'])->name('archive');
        Route::put('/{message}/unarchive', [MessageController::class, 'unarchive'])->name('unarchive');
        Route::put('/{message}/important', [MessageController::class, 'toggleImportant'])->name('toggle-important');
        Route::delete('/{message}', [MessageController::class, 'destroy'])->name('delete');
        Route::put('/mark-all-read', [MessageController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::post('/bulk-action', [MessageController::class, 'bulkAction'])->name('bulk-action');
    });
    
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
        
        // Gestion des newsletters (Admin seulement)
        Route::prefix('admin/newsletters')->name('api.admin.newsletters.')->group(function () {
            Route::get('/', [NewsletterController::class, 'index'])->name('index');
            Route::post('/', [NewsletterController::class, 'store'])->name('store');
            Route::get('/statistics', [NewsletterController::class, 'statistics'])->name('statistics');
            Route::get('/templates', [NewsletterController::class, 'templates'])->name('templates');
            Route::get('/{newsletter}', [NewsletterController::class, 'show'])->name('show');
            Route::put('/{newsletter}', [NewsletterController::class, 'update'])->name('update');
            Route::delete('/{newsletter}', [NewsletterController::class, 'destroy'])->name('destroy');
            Route::post('/{newsletter}/send', [NewsletterController::class, 'sendNow'])->name('send-now');
            Route::post('/{newsletter}/schedule', [NewsletterController::class, 'schedule'])->name('schedule');
            Route::post('/{newsletter}/cancel', [NewsletterController::class, 'cancel'])->name('cancel');
            Route::post('/{newsletter}/duplicate-template', [NewsletterController::class, 'duplicateAsTemplate'])->name('duplicate-template');
            Route::post('/templates/{template}/create', [NewsletterController::class, 'createFromTemplate'])->name('create-from-template');
        });
        
        // Gestion des catégories de blog (Admin seulement)
        Route::prefix('admin/blog/categories')->name('api.admin.blog.categories.')->group(function () {
            Route::get('/', [App\Http\Controllers\BlogCategoryController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\BlogCategoryController::class, 'store'])->name('store');
            Route::get('/statistics', [App\Http\Controllers\BlogCategoryController::class, 'statistics'])->name('statistics');
            Route::put('/{blogCategory}', [App\Http\Controllers\BlogCategoryController::class, 'update'])->name('update');
            Route::delete('/{blogCategory}', [App\Http\Controllers\BlogCategoryController::class, 'destroy'])->name('destroy');
            Route::patch('/{blogCategory}/toggle-status', [App\Http\Controllers\BlogCategoryController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/update-order', [App\Http\Controllers\BlogCategoryController::class, 'updateOrder'])->name('update-order');
        });
        
        // Gestion des articles de blog (Admin seulement)
        Route::prefix('admin/blog/posts')->name('api.admin.blog.posts.')->group(function () {
            Route::get('/', [App\Http\Controllers\BlogPostController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\BlogPostController::class, 'store'])->name('store');
            Route::get('/statistics', [App\Http\Controllers\BlogPostController::class, 'statistics'])->name('statistics');
            Route::put('/{blogPost}', [App\Http\Controllers\BlogPostController::class, 'update'])->name('update');
            Route::delete('/{blogPost}', [App\Http\Controllers\BlogPostController::class, 'destroy'])->name('destroy');
            Route::post('/{blogPost}/publish', [App\Http\Controllers\BlogPostController::class, 'publish'])->name('publish');
            Route::post('/{blogPost}/unpublish', [App\Http\Controllers\BlogPostController::class, 'unpublish'])->name('unpublish');
            Route::post('/{blogPost}/schedule', [App\Http\Controllers\BlogPostController::class, 'schedule'])->name('schedule');
        });
        
        // Gestion des commentaires de blog (Admin seulement)
        Route::prefix('admin/blog/comments')->name('api.admin.blog.comments.')->group(function () {
            Route::get('/', [App\Http\Controllers\BlogCommentController::class, 'index'])->name('index');
            Route::get('/statistics', [App\Http\Controllers\BlogCommentController::class, 'statistics'])->name('statistics');
            Route::post('/{blogComment}/moderate', [App\Http\Controllers\BlogCommentController::class, 'moderate'])->name('moderate');
            Route::patch('/{blogComment}/toggle-pin', [App\Http\Controllers\BlogCommentController::class, 'togglePin'])->name('toggle-pin');
            Route::delete('/{blogComment}', [App\Http\Controllers\BlogCommentController::class, 'destroy'])->name('destroy');
        });
        
        // Gestion des signalements de commentaires (Admin seulement)
        Route::prefix('admin/blog/comment-reports')->name('api.admin.blog.comment-reports.')->group(function () {
            Route::get('/', [App\Http\Controllers\BlogCommentReportController::class, 'index'])->name('index');
            Route::get('/statistics', [App\Http\Controllers\BlogCommentReportController::class, 'statistics'])->name('statistics');
            Route::get('/{blogCommentReport}', [App\Http\Controllers\BlogCommentReportController::class, 'show'])->name('show');
            Route::post('/{blogCommentReport}/review', [App\Http\Controllers\BlogCommentReportController::class, 'review'])->name('review');
            Route::post('/{blogCommentReport}/resolve', [App\Http\Controllers\BlogCommentReportController::class, 'resolve'])->name('resolve');
            Route::post('/{blogCommentReport}/dismiss', [App\Http\Controllers\BlogCommentReportController::class, 'dismiss'])->name('dismiss');
            Route::patch('/{blogCommentReport}/priority', [App\Http\Controllers\BlogCommentReportController::class, 'updatePriority'])->name('update-priority');
            Route::get('/comments/{blogComment}/reports', [App\Http\Controllers\BlogCommentReportController::class, 'commentReports'])->name('comment-reports');
            Route::post('/bulk-action', [App\Http\Controllers\BlogCommentReportController::class, 'bulkAction'])->name('bulk-action');
        });
        
        // Routes pour le système de commandes
        Route::middleware(['auth:sanctum'])->group(function () {
            // Routes commandes pour les utilisateurs connectés
            Route::prefix('orders')->name('api.orders.')->group(function () {
                Route::get('/', [App\Http\Controllers\OrderController::class, 'index'])->name('index');
                Route::post('/', [App\Http\Controllers\OrderController::class, 'store'])->name('store');
                Route::get('/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('show');
                Route::patch('/{order}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('cancel');
                Route::get('/{order}/invoice', [App\Http\Controllers\OrderController::class, 'downloadInvoice'])->name('download-invoice');
                Route::get('/{order}/track', [App\Http\Controllers\OrderController::class, 'track'])->name('track');
                
                // Routes articles de commandes
                Route::prefix('{order}/items')->name('items.')->group(function () {
                    Route::get('/', [App\Http\Controllers\OrderItemController::class, 'index'])->name('index');
                    Route::get('/{item}', [App\Http\Controllers\OrderItemController::class, 'show'])->name('show');
                    Route::get('/{item}/return-eligibility', [App\Http\Controllers\OrderItemController::class, 'checkReturnEligibility'])->name('return-eligibility');
                    Route::get('/{item}/return-history', [App\Http\Controllers\OrderItemController::class, 'returnHistory'])->name('return-history');
                });
            });
            
            // Routes retours de commandes pour les utilisateurs connectés
            Route::prefix('returns')->name('api.returns.')->group(function () {
                Route::get('/', [App\Http\Controllers\OrderReturnController::class, 'index'])->name('index');
                Route::post('/', [App\Http\Controllers\OrderReturnController::class, 'store'])->name('store');
                Route::get('/{return}', [App\Http\Controllers\OrderReturnController::class, 'show'])->name('show');
                Route::patch('/{return}/cancel', [App\Http\Controllers\OrderReturnController::class, 'cancel'])->name('cancel');
            });
            
            // Routes administrateur pour les commandes
            Route::middleware(['admin'])->group(function () {
                // Gestion des commandes (Admin seulement)
                Route::prefix('admin/orders')->name('api.admin.orders.')->group(function () {
                    Route::get('/', [App\Http\Controllers\OrderController::class, 'adminIndex'])->name('index');
                    Route::get('/statistics', [App\Http\Controllers\OrderController::class, 'adminStats'])->name('statistics');
                    Route::get('/export', [App\Http\Controllers\OrderController::class, 'export'])->name('export');
                    Route::get('/{order}', [App\Http\Controllers\OrderController::class, 'adminShow'])->name('show');
                    Route::patch('/{order}/status', [App\Http\Controllers\OrderController::class, 'updateStatus'])->name('update-status');
                });
                
                // Gestion des articles de commandes (Admin seulement)
                Route::prefix('admin/order-items')->name('api.admin.order-items.')->group(function () {
                    Route::get('/', [App\Http\Controllers\OrderItemController::class, 'adminIndex'])->name('index');
                    Route::get('/statistics', [App\Http\Controllers\OrderItemController::class, 'adminStats'])->name('statistics');
                    Route::get('/export', [App\Http\Controllers\OrderItemController::class, 'export'])->name('export');
                    Route::get('/{item}', [App\Http\Controllers\OrderItemController::class, 'adminShow'])->name('show');
                });
                
                // Gestion des retours (Admin seulement)
                Route::prefix('admin/returns')->name('api.admin.returns.')->group(function () {
                    Route::get('/', [App\Http\Controllers\OrderReturnController::class, 'adminIndex'])->name('index');
                    Route::get('/statistics', [App\Http\Controllers\OrderReturnController::class, 'adminStats'])->name('statistics');
                    Route::get('/export', [App\Http\Controllers\OrderReturnController::class, 'export'])->name('export');
                    Route::get('/{return}', [App\Http\Controllers\OrderReturnController::class, 'adminShow'])->name('show');
                    Route::patch('/{return}/approve', [App\Http\Controllers\OrderReturnController::class, 'approve'])->name('approve');
                    Route::patch('/{return}/reject', [App\Http\Controllers\OrderReturnController::class, 'reject'])->name('reject');
                    Route::patch('/{return}/received', [App\Http\Controllers\OrderReturnController::class, 'markAsReceived'])->name('mark-as-received');
                    Route::patch('/{return}/refund', [App\Http\Controllers\OrderReturnController::class, 'processRefund'])->name('process-refund');
                });

                // Gestion des commandes de location (Admin seulement)
                Route::prefix('admin/rental-orders')->name('api.admin.rental-orders.')->group(function () {
                    Route::get('/', [App\Http\Controllers\OrderLocationController::class, 'index'])->name('index');
                    Route::get('/statistics', [App\Http\Controllers\OrderLocationController::class, 'statistics'])->name('statistics');
                    Route::get('/export', [App\Http\Controllers\OrderLocationController::class, 'export'])->name('export');
                    Route::get('/{orderLocation}', [App\Http\Controllers\OrderLocationController::class, 'show'])->name('show');
                    Route::patch('/{orderLocation}/confirm', [App\Http\Controllers\OrderLocationController::class, 'confirm'])->name('confirm');
                    Route::patch('/{orderLocation}/cancel', [App\Http\Controllers\OrderLocationController::class, 'cancel'])->name('cancel');
                    Route::patch('/{orderLocation}/start-inspection', [App\Http\Controllers\OrderLocationController::class, 'startInspection'])->name('start-inspection');
                    Route::patch('/{orderLocation}/finish-inspection', [App\Http\Controllers\OrderLocationController::class, 'finishInspection'])->name('finish-inspection');
                });

                // Gestion des articles de location (Admin seulement)
                Route::prefix('admin/rental-items')->name('api.admin.rental-items.')->group(function () {
                    Route::get('/{orderLocation}', [App\Http\Controllers\OrderItemLocationController::class, 'index'])->name('index');
                    Route::get('/{orderLocation}/{orderItemLocation}', [App\Http\Controllers\OrderItemLocationController::class, 'show'])->name('show');
                    Route::patch('/{orderLocation}/{orderItemLocation}/pickup-condition', [App\Http\Controllers\OrderItemLocationController::class, 'updatePickupCondition'])->name('update-pickup-condition');
                    Route::patch('/{orderLocation}/{orderItemLocation}/return-condition', [App\Http\Controllers\OrderItemLocationController::class, 'updateReturnCondition'])->name('update-return-condition');
                    Route::get('/{orderLocation}/{orderItemLocation}/condition-summary', [App\Http\Controllers\OrderItemLocationController::class, 'getConditionSummary'])->name('condition-summary');
                    Route::get('/{orderLocation}/{orderItemLocation}/history', [App\Http\Controllers\OrderItemLocationController::class, 'getHistory'])->name('history');
                    Route::get('/{orderLocation}/{orderItemLocation}/penalties', [App\Http\Controllers\OrderItemLocationController::class, 'calculatePenalties'])->name('calculate-penalties');
                    Route::patch('/{orderLocation}/{orderItemLocation}/penalties', [App\Http\Controllers\OrderItemLocationController::class, 'updatePenalties'])->name('update-penalties');
                });
            });

            // Routes commandes de location pour utilisateurs connectés
            Route::prefix('rental-orders')->name('api.rental-orders.')->group(function () {
                Route::get('/', [App\Http\Controllers\OrderLocationController::class, 'index'])->name('index');
                Route::post('/', [App\Http\Controllers\OrderLocationController::class, 'store'])->name('store');
                Route::get('/{orderLocation}', [App\Http\Controllers\OrderLocationController::class, 'show'])->name('show');
                Route::patch('/{orderLocation}/cancel', [App\Http\Controllers\OrderLocationController::class, 'cancel'])->name('cancel');
                Route::patch('/{orderLocation}/complete', [App\Http\Controllers\OrderLocationController::class, 'complete'])->name('complete');
                Route::patch('/{orderLocation}/close', [App\Http\Controllers\OrderLocationController::class, 'close'])->name('close');

                // Routes pour les articles de location
                Route::prefix('{orderLocation}/items')->name('items.')->group(function () {
                    Route::get('/', [App\Http\Controllers\OrderItemLocationController::class, 'index'])->name('index');
                    Route::get('/{orderItemLocation}', [App\Http\Controllers\OrderItemLocationController::class, 'show'])->name('show');
                    Route::get('/{orderItemLocation}/condition-summary', [App\Http\Controllers\OrderItemLocationController::class, 'getConditionSummary'])->name('condition-summary');
                    Route::get('/{orderItemLocation}/penalties', [App\Http\Controllers\OrderItemLocationController::class, 'calculatePenalties'])->name('calculate-penalties');
                });
            });
        });
    });
});

// Route publique pour le formulaire de contact
Route::post('/contact', [ContactController::class, 'store'])->name('api.contact.store');

// Routes publiques pour newsletter (tracking et désabonnement)
Route::get('/newsletter/track/open/{token}', [NewsletterSubscriptionController::class, 'trackOpen'])->name('api.newsletter.track-open');
Route::post('/newsletter/track/click/{token}', [NewsletterSubscriptionController::class, 'trackClick'])->name('api.newsletter.track-click');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterSubscriptionController::class, 'unsubscribeByToken'])->name('api.newsletter.unsubscribe-token');

// Routes commentaires de blog pour utilisateurs connectés
Route::prefix('blog')->name('api.blog.')->group(function () {
    Route::post('/posts/{blogPost}/comments', [App\Http\Controllers\BlogCommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{blogComment}', [App\Http\Controllers\BlogCommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{blogComment}', [App\Http\Controllers\BlogCommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{blogComment}/like', [App\Http\Controllers\BlogCommentController::class, 'like'])->name('comments.like');
    Route::post('/comments/{blogComment}/report', [App\Http\Controllers\BlogCommentReportController::class, 'store'])->name('comments.report');
});
