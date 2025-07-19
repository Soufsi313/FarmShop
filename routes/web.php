<?php

use App\Http\Controllers\RentalController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RentalCategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\OrderLocationController as AdminOrderLocationController;
use App\Http\Controllers\Admin\SpecialOfferController as AdminSpecialOfferController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Web\ProductController as WebProductController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Test Tailwind CSS
Route::get('/test-tailwind', function () {
    return view('test-tailwind');
})->name('test.tailwind');

// Pages légales
Route::get('/privacy', function () {
    return view('legal.privacy');
})->name('privacy');

Route::get('/returns', function () {
    return view('legal.returns');
})->name('returns');

Route::get('/gdpr-rights', function () {
    return view('legal.gdpr-rights');
})->name('gdpr-rights');

// Nouvelles pages légales conformes
Route::prefix('legal')->name('legal.')->group(function () {
    Route::get('/mentions-legales', function () {
        return view('legal.mentions');
    })->name('mentions');
    
    Route::get('/conditions-generales-vente', function () {
        return view('legal.cgv');
    })->name('cgv');
    
    Route::get('/conditions-generales-utilisation', function () {
        return view('legal.cgu');
    })->name('cgu');
    
    Route::get('/conditions-generales-location', function () {
        return view('legal.cgl');
    })->name('cgl');
    
    Route::get('/politique-confidentialite', function () {
        return view('legal.privacy');
    })->name('privacy');
    
    Route::get('/droits-rgpd', function () {
        return view('legal.gdpr-rights');
    })->name('gdpr-rights');
    
    Route::get('/politique-cookies', function () {
        return view('legal.cookies');
    })->name('cookies');
    
    Route::get('/demande-donnees', function () {
        return view('legal.data-request');
    })->name('data-request');
    
    Route::get('/droit-retractation', function () {
        return view('legal.returns');
    })->name('returns');
    
    Route::get('/garanties-legales', function () {
        return view('legal.warranties');
    })->name('warranties');
    
    Route::get('/mediation', function () {
        return view('legal.mediation');
    })->name('mediation');
    
    Route::get('/assurance', function () {
        return view('legal.insurance');
    })->name('insurance');
});

// Routes pour les produits publics
Route::get('/products', [WebProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [WebProductController::class, 'show'])->name('products.show');
Route::post('/products/{product}/buy-now', [WebProductController::class, 'buyNow'])->name('products.buy-now')->middleware('auth');

// Routes pour les locations
Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
Route::get('/rentals/{product:slug}', [RentalController::class, 'show'])->name('rentals.show');

// Routes d'authentification
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Routes publiques pour les catégories
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Routes publiques pour les catégories de location
Route::get('/rental-categories', [RentalCategoryController::class, 'index'])->name('rental-categories.index');
Route::get('/rental-categories/{rentalCategory}', [RentalCategoryController::class, 'show'])->name('rental-categories.show');

// Routes d'authentification et utilisateur (pour interface web si nécessaire)
Route::middleware(['auth'])->group(function () {
    // Profil utilisateur
    Route::get('/profile', [UserController::class, 'show'])->name('users.profile');
    Route::put('/profile', [UserController::class, 'update'])->name('users.update');
    
    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'showPage'])->name('wishlist.index');
    
    // Gestion des messages
    Route::patch('/messages/{message}/read', [UserController::class, 'markMessageAsRead'])->name('messages.read');
    Route::patch('/messages/{message}/archive', [UserController::class, 'archiveMessage'])->name('messages.archive');
    Route::delete('/messages/{message}', [UserController::class, 'deleteMessage'])->name('messages.delete');
    
    // Newsletter
    Route::post('/newsletter/subscribe', [UserController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
    Route::post('/newsletter/unsubscribe', [UserController::class, 'unsubscribeNewsletter'])->name('newsletter.unsubscribe');
    
    // Export données RGPD
    Route::get('/profile/download-data', [UserController::class, 'downloadData'])->name('users.download-data');
    
    // Auto-suppression
    Route::delete('/profile/self-delete', [UserController::class, 'selfDelete'])->name('users.self-delete');
});

// Routes administration
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard admin (avec vérification du rôle dans le contrôleur)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // Gestion des produits
    Route::resource('products', AdminProductController::class);
    
    // Gestion des catégories
    Route::resource('categories', AdminCategoryController::class);
    
    // Gestion des catégories de location
    Route::get('/rental-categories', [DashboardController::class, 'rentalCategories'])->name('rental-categories.index');
    Route::get('/rental-categories/create', [DashboardController::class, 'createRentalCategory'])->name('rental-categories.create');
    Route::post('/rental-categories', [DashboardController::class, 'storeRentalCategory'])->name('rental-categories.store');
    Route::get('/rental-categories/{rentalCategory}', [DashboardController::class, 'showRentalCategory'])->name('rental-categories.show');
    Route::get('/rental-categories/{rentalCategory}/edit', [DashboardController::class, 'editRentalCategory'])->name('rental-categories.edit');
    Route::put('/rental-categories/{rentalCategory}', [DashboardController::class, 'updateRentalCategory'])->name('rental-categories.update');
    Route::delete('/rental-categories/{rentalCategory}', [DashboardController::class, 'destroyRentalCategory'])->name('rental-categories.destroy');
    
    // Gestion des offres spéciales - CRUD complet avec contrôleur admin
    Route::resource('special-offers', AdminSpecialOfferController::class);
    Route::patch('/special-offers/{specialOffer}/toggle', [AdminSpecialOfferController::class, 'toggle'])->name('special-offers.toggle');
    
    // Sections du dashboard (pages existantes)
    Route::get('/users', [DashboardController::class, 'users'])->name('users.index');
    Route::get('/users/{user}', [DashboardController::class, 'showUser'])->name('users.show');
    Route::get('/users/{user}/edit', [DashboardController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [DashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [DashboardController::class, 'destroyUser'])->name('users.destroy');
    // Gestion des commandes d'achat
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
    
    // Gestion des commandes de location
    Route::get('/order-locations', [AdminOrderLocationController::class, 'index'])->name('order-locations.index');
    Route::get('/order-locations/{orderLocation}', [AdminOrderLocationController::class, 'show'])->name('order-locations.show');
    Route::patch('/order-locations/{orderLocation}/status', [AdminOrderLocationController::class, 'updateStatus'])->name('order-locations.update-status');
    Route::delete('/order-locations/{orderLocation}', [AdminOrderLocationController::class, 'destroy'])->name('order-locations.destroy');
    
    // Gestion des messages (nouvelle section)
    Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/statistics', [AdminMessageController::class, 'statistics'])->name('messages.statistics');
    Route::get('/messages/{message}', [AdminMessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{message}/respond', [AdminMessageController::class, 'respond'])->name('messages.respond');
    Route::post('/messages/{message}/mark-read', [AdminMessageController::class, 'markAsRead'])->name('messages.mark-read');
    Route::post('/messages/{message}/archive', [AdminMessageController::class, 'archive'])->name('messages.archive');
    Route::delete('/messages/{message}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');
    
    // Gestion du Blog
    Route::get('/blog', [DashboardController::class, 'blog'])->name('blog.index');
    Route::get('/blog/create', [DashboardController::class, 'createBlogPost'])->name('blog.create');
    Route::post('/blog', [DashboardController::class, 'storeBlogPost'])->name('blog.store');
    Route::get('/blog/{blogPost}/edit', [DashboardController::class, 'editBlogPost'])->name('blog.edit');
    Route::put('/blog/{blogPost}', [DashboardController::class, 'updateBlogPost'])->name('blog.update');
    Route::delete('/blog/{blogPost}', [DashboardController::class, 'destroyBlogPost'])->name('blog.destroy');
    
    Route::get('/blog-categories', [DashboardController::class, 'blogCategories'])->name('blog-categories.index');
    Route::post('/blog-categories', [DashboardController::class, 'storeBlogCategory'])->name('blog-categories.store');
    Route::put('/blog-categories/{blogCategory}', [DashboardController::class, 'updateBlogCategory'])->name('blog-categories.update');
    Route::delete('/blog-categories/{blogCategory}', [DashboardController::class, 'destroyBlogCategory'])->name('blog-categories.destroy');
    
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings.index');
});

// Routes web pour likes et wishlists (retournent du JSON mais utilisent l'auth web)
Route::middleware(['auth'])->group(function () {
    Route::post('/web/likes/products/{product}/toggle', [\App\Http\Controllers\ProductLikeController::class, 'toggle'])->name('web.likes.toggle');
    Route::post('/web/wishlist/products/{product}/toggle', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('web.wishlist.toggle');
    Route::delete('/web/wishlist/products/{product}', [\App\Http\Controllers\WishlistController::class, 'destroy'])->name('web.wishlist.destroy');
    Route::delete('/web/wishlist/clear', [\App\Http\Controllers\WishlistController::class, 'clear'])->name('web.wishlist.clear');
});

// Routes Blog public
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [\App\Http\Controllers\BlogPostController::class, 'index'])->name('index');
    Route::get('/{slug}', [\App\Http\Controllers\BlogPostController::class, 'showWeb'])->name('show');
});
