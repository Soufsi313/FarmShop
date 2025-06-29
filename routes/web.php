<?php

use Illuminate\Support\Facades\Route;

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
    Route::delete('/products/{product}', [App\Http\Controllers\Admin\AdminController::class, 'destroyProduct'])->name('products.destroy');
    
    // Gestion des commandes
    Route::get('/orders', [App\Http\Controllers\Admin\AdminController::class, 'ordersIndex'])->name('orders.index');
    Route::patch('/orders/{order}/status', [App\Http\Controllers\Admin\AdminController::class, 'updateOrderStatus'])->name('orders.status');
    
    // Gestion des messages admin
    Route::get('/messages', [App\Http\Controllers\AdminMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{adminMessage}', [App\Http\Controllers\AdminMessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{adminMessage}/reply', [App\Http\Controllers\AdminMessageController::class, 'reply'])->name('messages.reply');
    Route::patch('/messages/{adminMessage}/resolve', [App\Http\Controllers\AdminMessageController::class, 'markAsResolved'])->name('messages.resolve');
    Route::delete('/messages/{adminMessage}', [App\Http\Controllers\AdminMessageController::class, 'destroy'])->name('messages.destroy');
});

// Routes pour envoyer des messages à l'admin (utilisateurs connectés)
Route::middleware(['auth'])->group(function () {
    Route::post('/contact-admin', [App\Http\Controllers\AdminMessageController::class, 'store'])->name('contact.admin');
});
