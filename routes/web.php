<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RentalCategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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

// Routes pour les produits (temporaires pour les liens de la landing page)
Route::get('/products', function () {
    return redirect('/')->with('message', 'Page produits en cours de développement');
})->name('products.index');

Route::get('/rentals', function () {
    return redirect('/')->with('message', 'Page locations en cours de développement');
})->name('rentals.index');

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
    
    // Sections du dashboard
    Route::get('/users', [DashboardController::class, 'users'])->name('users.index');
    Route::get('/products', [DashboardController::class, 'products'])->name('products.index');
    Route::get('/categories', [DashboardController::class, 'categories'])->name('categories.index');
    Route::get('/orders', [DashboardController::class, 'orders'])->name('orders.index');
    Route::get('/special-offers', [DashboardController::class, 'specialOffers'])->name('special-offers.index');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings.index');
    
    // CRUD Routes existantes (à garder pour plus tard)
    // Route::resource('users', UserController::class);
    // Route::resource('categories', CategoryController::class);
    // Route::resource('rental-categories', RentalCategoryController::class);
});
