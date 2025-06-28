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

// Routes publiques pour les produits (accessibles à tous)
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [App\Http\Controllers\ProductController::class, 'index'])->name('index');
    Route::get('/{product:slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('show');
    Route::get('/search', [App\Http\Controllers\ProductController::class, 'search'])->name('search');
    
    // Routes pour utilisateurs connectés
    Route::middleware('auth')->group(function () {
        Route::post('/{product}/like', [App\Http\Controllers\ProductController::class, 'like'])->name('like');
        Route::post('/{product}/wishlist', [App\Http\Controllers\ProductController::class, 'wishlist'])->name('wishlist');
    });
});

// Routes publiques pour les catégories (accessibles à tous)
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [App\Http\Controllers\CategoryController::class, 'index'])->name('index');
    Route::get('/{category:slug}', [App\Http\Controllers\CategoryController::class, 'show'])->name('show');
});

// Routes publiques pour le contact (accessibles à tous)
Route::prefix('contact')->name('contact.')->group(function () {
    Route::get('/', [App\Http\Controllers\ContactController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\ContactController::class, 'store'])->name('store');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Routes pour le panier (Cart) - Utilisateurs connectés uniquement
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [App\Http\Controllers\CartController::class, 'index'])->name('index'); // Afficher le panier
        Route::post('/add', [App\Http\Controllers\CartController::class, 'store'])->name('add'); // Ajouter un produit
        Route::put('/update/{cart}', [App\Http\Controllers\CartController::class, 'update'])->name('update'); // Modifier la quantité
        Route::delete('/remove/{cart}', [App\Http\Controllers\CartController::class, 'destroy'])->name('remove'); // Supprimer un article
        Route::delete('/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('clear'); // Vider le panier
        Route::post('/quick-add/{product}', [App\Http\Controllers\CartController::class, 'quickAdd'])->name('quick-add'); // Ajout rapide
        Route::post('/sync', [App\Http\Controllers\CartController::class, 'sync'])->name('sync'); // Synchroniser le panier
        Route::post('/validate', [App\Http\Controllers\CartController::class, 'validateCart'])->name('validate'); // Valider le panier
        Route::get('/count', [App\Http\Controllers\CartController::class, 'getCartCount'])->name('count'); // Nombre d'articles
        Route::get('/total', [App\Http\Controllers\CartController::class, 'getCartTotal'])->name('total'); // Total du panier
    });

    // Routes pour le panier de location (CartLocation) - Utilisateurs connectés uniquement
    Route::prefix('cart-location')->name('cart-location.')->group(function () {
        Route::get('/', [App\Http\Controllers\CartLocationController::class, 'index'])->name('index'); // Afficher le panier de location
        Route::post('/add', [App\Http\Controllers\CartLocationController::class, 'store'])->name('add'); // Ajouter un produit en location
        Route::get('/{cart_location}', [App\Http\Controllers\CartLocationController::class, 'show'])->name('show'); // Afficher un article
        Route::put('/update/{cart_location}', [App\Http\Controllers\CartLocationController::class, 'update'])->name('update'); // Modifier un article
        Route::delete('/remove/{cart_location}', [App\Http\Controllers\CartLocationController::class, 'destroy'])->name('remove'); // Supprimer un article
        Route::delete('/clear', [App\Http\Controllers\CartLocationController::class, 'clear'])->name('clear'); // Vider le panier de location
        Route::post('/quick-add/{product}', [App\Http\Controllers\CartLocationController::class, 'quickAdd'])->name('quick-add'); // Ajout rapide
        Route::post('/sync', [App\Http\Controllers\CartLocationController::class, 'sync'])->name('sync'); // Synchroniser le panier
        Route::post('/validate', [App\Http\Controllers\CartLocationController::class, 'validateCart'])->name('validate'); // Valider le panier
        Route::get('/count', [App\Http\Controllers\CartLocationController::class, 'getCartCount'])->name('count'); // Nombre d'articles
        Route::get('/total', [App\Http\Controllers\CartLocationController::class, 'getCartTotal'])->name('total'); // Total du panier
        Route::post('/{cart_location}/extend', [App\Http\Controllers\CartLocationController::class, 'extend'])->name('extend'); // Prolonger une location
    });

    // Routes pour les utilisateurs (actions personnelles)
    Route::prefix('user')->name('user.')->group(function () {
        Route::post('/newsletter/subscribe', [App\Http\Controllers\UserController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
        Route::post('/newsletter/unsubscribe', [App\Http\Controllers\UserController::class, 'unsubscribeNewsletter'])->name('newsletter.unsubscribe');
        Route::get('/export-data', [App\Http\Controllers\UserController::class, 'exportData'])->name('export.data');
    });

    // Routes d'administration
    Route::middleware(['permission:manage users'])->prefix('admin')->name('admin.')->group(function () {
        // CRUD complet pour les utilisateurs
        Route::resource('users', App\Http\Controllers\UserController::class);
        
        // Routes supplémentaires pour la gestion des utilisateurs
        Route::post('/users/{id}/restore', [App\Http\Controllers\UserController::class, 'restore'])->name('users.restore');
        Route::delete('/users/{id}/force-delete', [App\Http\Controllers\UserController::class, 'forceDelete'])->name('users.force-delete');
        Route::post('/users/bulk-action', [App\Http\Controllers\UserController::class, 'bulkAction'])->name('users.bulk-action');
        Route::get('/users-statistics', [App\Http\Controllers\UserController::class, 'statistics'])->name('users.statistics');
    });

    // Routes d'administration pour les produits
    Route::middleware(['permission:manage products'])->prefix('admin')->name('admin.')->group(function () {
        // CRUD complet pour les produits
        Route::resource('products', App\Http\Controllers\ProductController::class)->except(['show']);
        
        // Routes supplémentaires pour la gestion des produits
        Route::post('/products/bulk-action', [App\Http\Controllers\ProductController::class, 'bulkAction'])->name('products.bulk-action');
        Route::get('/products-statistics', [App\Http\Controllers\ProductController::class, 'statistics'])->name('products.statistics');
    });

    // Routes d'administration pour les contacts
    Route::middleware(['permission:manage contacts'])->prefix('admin')->name('admin.')->group(function () {
        // CRUD pour les contacts
        Route::get('/contacts', [App\Http\Controllers\ContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/{contact}', [App\Http\Controllers\ContactController::class, 'show'])->name('contacts.show');
        Route::put('/contacts/{contact}', [App\Http\Controllers\ContactController::class, 'update'])->name('contacts.update');
        Route::delete('/contacts/{contact}', [App\Http\Controllers\ContactController::class, 'destroy'])->name('contacts.destroy');
        
        // Actions rapides pour les contacts
        Route::post('/contacts/{contact}/mark-in-progress', [App\Http\Controllers\ContactController::class, 'markInProgress'])->name('contacts.mark-in-progress');
        Route::post('/contacts/{contact}/mark-resolved', [App\Http\Controllers\ContactController::class, 'markResolved'])->name('contacts.mark-resolved');
        Route::post('/contacts/{contact}/close', [App\Http\Controllers\ContactController::class, 'close'])->name('contacts.close');
        Route::post('/contacts/{contact}/assign', [App\Http\Controllers\ContactController::class, 'assign'])->name('contacts.assign');
        
        // Téléchargement des pièces jointes et statistiques
        Route::get('/contacts/{contact}/attachment/{attachmentIndex}', [App\Http\Controllers\ContactController::class, 'downloadAttachment'])->name('contacts.download-attachment');
        Route::get('/contacts-statistics', [App\Http\Controllers\ContactController::class, 'statistics'])->name('contacts.statistics');
    });

    // Routes d'administration pour les catégories
    Route::middleware(['permission:manage categories'])->prefix('admin')->name('admin.')->group(function () {
        // CRUD complet pour les catégories
        Route::resource('categories', App\Http\Controllers\CategoryController::class);
        
        // Routes supplémentaires pour la gestion des catégories
        Route::post('/categories/bulk-action', [App\Http\Controllers\CategoryController::class, 'bulkAction'])->name('categories.bulk-action');
        Route::post('/categories/reorder', [App\Http\Controllers\CategoryController::class, 'reorder'])->name('categories.reorder');
        Route::get('/categories-statistics', [App\Http\Controllers\CategoryController::class, 'statistics'])->name('categories.statistics');
    });

    // Routes d'administration pour les images de produits
    Route::middleware(['permission:manage products'])->prefix('admin')->name('admin.')->group(function () {
        // CRUD complet pour les images de produits
        Route::resource('product-images', App\Http\Controllers\ProductImageController::class);
        
        // Routes supplémentaires pour la gestion des images
        Route::post('/product-images/bulk-destroy', [App\Http\Controllers\ProductImageController::class, 'bulkDestroy'])->name('product-images.bulk-destroy');
        Route::post('/product-images/reorder', [App\Http\Controllers\ProductImageController::class, 'reorder'])->name('product-images.reorder');
        Route::post('/product-images/{productImage}/set-main', [App\Http\Controllers\ProductImageController::class, 'setMain'])->name('product-images.set-main');
        Route::get('/product-images-statistics', [App\Http\Controllers\ProductImageController::class, 'statistics'])->name('product-images.statistics');
    });
});
