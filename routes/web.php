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

    // Routes d'administration pour les utilisateurs
    Route::middleware(['permission:manage users'])->prefix('admin')->name('admin.')->group(function () {
        // CRUD complet pour les utilisateurs
        Route::resource('users', App\Http\Controllers\UserController::class);
        
        // Routes supplémentaires pour la gestion des utilisateurs
        Route::post('/users/{id}/restore', [App\Http\Controllers\UserController::class, 'restore'])->name('users.restore');
        Route::delete('/users/{id}/force-delete', [App\Http\Controllers\UserController::class, 'forceDelete'])->name('users.force-delete');
        Route::post('/users/bulk-action', [App\Http\Controllers\UserController::class, 'bulkAction'])->name('users.bulk-action');
        Route::get('/users-statistics', [App\Http\Controllers\UserController::class, 'statistics'])->name('users.statistics');
    });
});
