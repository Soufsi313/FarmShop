<?php

use App\Http\Controllers\UserController;
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
    
    // Routes administration (Admin seulement)
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/users', [UserController::class, 'index'])->name('api.admin.users.index');
        Route::get('/admin/users/{user}', [UserController::class, 'show'])->name('api.admin.users.show');
        Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('api.admin.users.update');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('api.admin.users.destroy');
        Route::post('/admin/users/{id}/restore', [UserController::class, 'restore'])->name('api.admin.users.restore');
        Route::post('/admin/users', [UserController::class, 'store'])->name('api.admin.users.store');
    });
});
