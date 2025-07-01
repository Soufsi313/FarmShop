<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route d'accueil
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Route de debug pour l'authentification
Route::get('/debug-auth', function () {
    return view('debug-auth');
});

// Routes publiques pour les produits (accessibles à tous)
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [App\Http\Controllers\ProductController::class, 'index'])->name('index');
    Route::get('/{product:slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('show');
});

// Routes publiques pour les cookies (accessibles à tous)
Route::prefix('cookies')->name('cookies.')->group(function () {
    Route::get('/policy', [App\Http\Controllers\CookieController::class, 'policy'])->name('policy');
    Route::get('/preferences', [App\Http\Controllers\CookieController::class, 'preferences'])->name('preferences');
    Route::post('/accept-all', [App\Http\Controllers\CookieController::class, 'acceptAll'])->name('accept-all');
    Route::post('/reject-all', [App\Http\Controllers\CookieController::class, 'rejectAll'])->name('reject-all');
    Route::post('/save-preferences', [App\Http\Controllers\CookieController::class, 'savePreferences'])->name('save-preferences');
    Route::get('/status', [App\Http\Controllers\CookieController::class, 'getConsentStatus'])->name('status');
});

// Route dashboard qui redirige selon le rôle de l'utilisateur
Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    if (auth()->user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    
    // Pour les utilisateurs normaux, rediriger vers la liste des produits
    return redirect()->route('products.index');
})->name('dashboard');

// Routes d'authentification Fortify (gérées automatiquement)
// Route::middleware(['guest'])->group(function () {
//     Route::get('/login', [Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::class, 'create'])->name('login');
//     Route::get('/register', [Laravel\Fortify\Http\Controllers\RegisteredUserController::class, 'create'])->name('register');
// });

// Routes admin (accessibles uniquement aux admins)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    
    // Gestion des utilisateurs
    Route::get('/users', [App\Http\Controllers\Admin\AdminController::class, 'usersIndex'])->name('users.index');
    Route::post('/users', [App\Http\Controllers\Admin\AdminController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\AdminController::class, 'destroyUser'])->name('users.destroy');
    
    // Gestion des produits
    Route::get('/products', [App\Http\Controllers\Admin\AdminController::class, 'productsIndex'])->name('products.index');
    Route::post('/products', [App\Http\Controllers\Admin\AdminController::class, 'storeProduct'])->name('products.store');
    Route::put('/products/{product}', [App\Http\Controllers\Admin\AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [App\Http\Controllers\Admin\AdminController::class, 'destroyProduct'])->name('products.destroy');
    
    // Gestion des catégories
    Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [App\Http\Controllers\CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');
    Route::get('/categories/{category}/edit', [App\Http\Controllers\CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');
    
    // Gestion des commandes
    Route::get('/orders', [App\Http\Controllers\Admin\AdminController::class, 'ordersIndex'])->name('orders.index');
    Route::patch('/orders/{order}/status', [App\Http\Controllers\Admin\AdminController::class, 'updateOrderStatus'])->name('orders.status');
    Route::get('/orders/{order}/invoice', [App\Http\Controllers\OrderController::class, 'downloadInvoice'])->name('orders.invoice');
    
    // Automatisation des commandes
    Route::get('/orders/automation', [App\Http\Controllers\Admin\AdminController::class, 'automationDashboard'])->name('orders.automation');
    Route::post('/orders/automation/run', [App\Http\Controllers\Admin\AdminController::class, 'runAutomation'])->name('orders.automation.run');
    Route::post('/orders/automation/dry-run', [App\Http\Controllers\Admin\AdminController::class, 'runDryRun'])->name('orders.automation.dry-run');
    Route::get('/orders/automation/stats', [App\Http\Controllers\Admin\AdminController::class, 'automationStats'])->name('orders.automation.stats');
    
    // Gestion des annulations et retours
    Route::get('/orders/cancellation', [App\Http\Controllers\Admin\AdminController::class, 'orderCancellationIndex'])->name('orders.cancellation');
    Route::get('/orders/{order}/cancellation-check', [App\Http\Controllers\Admin\AdminController::class, 'checkCancellationEligibility'])->name('orders.cancellation.check');
    Route::post('/orders/{order}/cancel', [App\Http\Controllers\Admin\AdminController::class, 'cancelOrder'])->name('orders.cancel');
    Route::get('/orders/{order}/return-check', [App\Http\Controllers\Admin\AdminController::class, 'checkReturnEligibility'])->name('orders.return.check');
    Route::post('/orders/{order}/return', [App\Http\Controllers\Admin\AdminController::class, 'createReturn'])->name('orders.return');
    
    // Gestion des messages admin
    Route::get('/messages', [App\Http\Controllers\AdminMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{adminMessage}', [App\Http\Controllers\AdminMessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{adminMessage}/reply', [App\Http\Controllers\AdminMessageController::class, 'reply'])->name('messages.reply');
    Route::patch('/messages/{adminMessage}/resolve', [App\Http\Controllers\AdminMessageController::class, 'markAsResolved'])->name('messages.resolve');
    Route::delete('/messages/{adminMessage}', [App\Http\Controllers\AdminMessageController::class, 'destroy'])->name('messages.destroy');
    
    // Gestion des réponses aux messages
    Route::get('/messages/{adminMessage}/replies', [App\Http\Controllers\AdminMessageReplyController::class, 'index'])->name('messages.replies.index');
    Route::post('/messages/{adminMessage}/replies', [App\Http\Controllers\AdminMessageReplyController::class, 'store'])->name('messages.replies.store');
    Route::get('/messages/{adminMessage}/replies/{reply}', [App\Http\Controllers\AdminMessageReplyController::class, 'show'])->name('messages.replies.show');
    Route::put('/messages/{adminMessage}/replies/{reply}', [App\Http\Controllers\AdminMessageReplyController::class, 'update'])->name('messages.replies.update');
    Route::delete('/messages/{adminMessage}/replies/{reply}', [App\Http\Controllers\AdminMessageReplyController::class, 'destroy'])->name('messages.replies.destroy');
    Route::patch('/messages/{adminMessage}/mark-read', [App\Http\Controllers\AdminMessageReplyController::class, 'markAsRead'])->name('messages.mark_read');
});

// Routes pour envoyer des messages à l'admin (utilisateurs connectés)
Route::middleware(['auth'])->group(function () {
    Route::post('/contact-admin', [App\Http\Controllers\AdminMessageController::class, 'store'])->name('contact.admin');
    
    // Routes panier (utilisateurs connectés)
    Route::get('/panier', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::get('/api/cart/count', [App\Http\Controllers\CartController::class, 'getCartCount'])->name('cart.count');
    Route::post('/api/cart/add', [App\Http\Controllers\CartController::class, 'store'])->name('cart.add');
    Route::put('/api/cart/items/{cartItem}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/api/cart/items/{cartItem}', [App\Http\Controllers\CartController::class, 'destroy'])->name('cart.remove');
    Route::delete('/api/cart/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
    
    // Routes commandes (utilisateurs connectés)
    Route::get('/checkout', [App\Http\Controllers\OrderController::class, 'create'])->name('orders.create');
    Route::post('/checkout', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    Route::get('/mes-commandes', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.user.index');
    Route::get('/mes-commandes/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.user.show');
    Route::get('/mes-commandes/{order}/facture', [App\Http\Controllers\OrderController::class, 'downloadInvoice'])->name('orders.user.invoice');
    Route::post('/mes-commandes/{order}/annuler', [App\Http\Controllers\OrderController::class, 'userCancel'])->name('orders.user.cancel');
    
    // Route de test pour vérifier l'authentification
    Route::get('/api/test-auth', function () {
        return response()->json([
            'authenticated' => auth()->check(),
            'user' => auth()->user() ? auth()->user()->only(['id', 'name', 'email']) : null
        ]);
    });

    // Routes profil utilisateur
    Route::get('/mon-profil', [App\Http\Controllers\UserProfileController::class, 'show'])->name('profile.show');
    Route::put('/mon-profil', [App\Http\Controllers\UserProfileController::class, 'update'])->name('profile.update');
    Route::post('/mon-profil/photo', [App\Http\Controllers\UserProfileController::class, 'uploadPhoto'])->name('profile.photo.upload');
    Route::delete('/mon-profil/photo', [App\Http\Controllers\UserProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
    Route::post('/mon-profil/newsletter', [App\Http\Controllers\UserProfileController::class, 'toggleNewsletter'])->name('profile.newsletter.toggle');
    Route::get('/mon-profil/donnees', [App\Http\Controllers\UserProfileController::class, 'downloadUserData'])->name('profile.data.download');
    Route::delete('/mon-profil/account', [App\Http\Controllers\UserProfileController::class, 'requestAccountDeletion'])->name('profile.account.delete');
    
    // Routes messages utilisateur
    Route::get('/mes-messages', [App\Http\Controllers\UserMessageController::class, 'index'])->name('user.messages.index');
    Route::get('/mes-messages/{message}', [App\Http\Controllers\UserMessageController::class, 'show'])->name('user.messages.show');
});

// Routes pour le panier de location
Route::middleware(['auth'])->group(function () {
    // Panier de location global
    Route::prefix('panier-location')->name('cart-location.')->group(function () {
        Route::get('/', [App\Http\Controllers\CartLocationController::class, 'index'])->name('index');
        Route::post('/ajouter', [App\Http\Controllers\CartLocationController::class, 'addSimple'])->name('add-simple');
        Route::post('/store', [App\Http\Controllers\CartLocationController::class, 'store'])->name('store');
        Route::delete('/vider', [App\Http\Controllers\CartLocationController::class, 'clear'])->name('clear');
        Route::post('/valider', [App\Http\Controllers\CartLocationController::class, 'validateCart'])->name('validate');
        Route::post('/soumettre', [App\Http\Controllers\CartLocationController::class, 'submit'])->name('submit');
        
        // API endpoints
        Route::get('/api/count', [App\Http\Controllers\CartLocationController::class, 'getCartCount'])->name('api.count');
        Route::get('/api/total', [App\Http\Controllers\CartLocationController::class, 'getCartTotal'])->name('api.total');
        Route::post('/api/quick-add/{product}', [App\Http\Controllers\CartLocationController::class, 'quickAdd'])->name('api.quick-add');
    });
    
    // Articles individuels du panier de location
    Route::prefix('article-location')->name('cart-item-location.')->group(function () {
        Route::get('/{cartItemLocation}', [App\Http\Controllers\CartLocationController::class, 'show'])->name('show');
        Route::put('/{cartItemLocation}', [App\Http\Controllers\CartLocationController::class, 'update'])->name('update');
        Route::delete('/{cartItemLocation}', [App\Http\Controllers\CartLocationController::class, 'destroy'])->name('destroy');
        Route::post('/{cartItemLocation}/prolonger', [App\Http\Controllers\CartLocationController::class, 'extend'])->name('extend');
    });
});


