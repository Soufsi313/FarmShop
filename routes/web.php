<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Routes publiques pour les catégories
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

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
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Gestion des utilisateurs
    Route::resource('users', UserController::class);
    Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    
    // Gestion des catégories
    Route::resource('categories', CategoryController::class);
    Route::post('categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::patch('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
});
