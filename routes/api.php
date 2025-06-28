<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes API publiques pour les produits
Route::prefix('products')->group(function () {
    Route::get('/', [App\Http\Controllers\ProductController::class, 'index']);
    Route::get('/{product}', [App\Http\Controllers\ProductController::class, 'show']);
    Route::get('/search', [App\Http\Controllers\ProductController::class, 'search']);
});

// Routes API publiques pour les catégories
Route::prefix('categories')->group(function () {
    Route::get('/', [App\Http\Controllers\CategoryController::class, 'index']);
    Route::get('/{category}', [App\Http\Controllers\CategoryController::class, 'show']);
});

// Routes API publiques pour le contact
Route::prefix('contact')->group(function () {
    Route::post('/', [App\Http\Controllers\ContactController::class, 'store']);
});

// Routes API publiques pour les likes
Route::prefix('likes')->group(function () {
    Route::get('/most-liked', [App\Http\Controllers\ProductLikeController::class, 'mostLiked']); // Produits les plus likés
    Route::get('/statistics', [App\Http\Controllers\ProductLikeController::class, 'statistics']); // Statistiques publiques des likes
});

// Routes API pour les utilisateurs authentifiés
Route::middleware('auth:sanctum')->group(function () {
    // Routes API pour le panier (Cart) - Utilisateurs connectés uniquement
    Route::prefix('cart')->group(function () {
        Route::get('/', [App\Http\Controllers\CartController::class, 'index']); // Obtenir le contenu du panier
        Route::post('/add', [App\Http\Controllers\CartController::class, 'store']); // Ajouter un produit au panier
        Route::put('/update/{cart}', [App\Http\Controllers\CartController::class, 'update']); // Modifier la quantité d'un article
        Route::delete('/remove/{cart}', [App\Http\Controllers\CartController::class, 'destroy']); // Supprimer un article du panier
        Route::delete('/clear', [App\Http\Controllers\CartController::class, 'clear']); // Vider complètement le panier
        Route::post('/quick-add/{product}', [App\Http\Controllers\CartController::class, 'quickAdd']); // Ajout rapide d'un produit (quantité 1)
        Route::post('/sync', [App\Http\Controllers\CartController::class, 'sync']); // Synchroniser le panier avec les données côté client
        Route::post('/validate', [App\Http\Controllers\CartController::class, 'validateCart']); // Valider le panier (stock, prix, etc.)
        Route::get('/count', [App\Http\Controllers\CartController::class, 'getCartCount']); // Obtenir le nombre total d'articles
        Route::get('/total', [App\Http\Controllers\CartController::class, 'getCartTotal']); // Obtenir le montant total du panier
    });

    // Routes API pour le panier de location (CartLocation) - Utilisateurs connectés uniquement
    Route::prefix('cart-location')->group(function () {
        Route::get('/', [App\Http\Controllers\CartLocationController::class, 'index']); // Obtenir le contenu du panier de location
        Route::post('/add', [App\Http\Controllers\CartLocationController::class, 'store']); // Ajouter un produit au panier de location
        Route::get('/{cart_location}', [App\Http\Controllers\CartLocationController::class, 'show']); // Obtenir un article spécifique
        Route::put('/update/{cart_location}', [App\Http\Controllers\CartLocationController::class, 'update']); // Modifier un article de location
        Route::delete('/remove/{cart_location}', [App\Http\Controllers\CartLocationController::class, 'destroy']); // Supprimer un article du panier de location
        Route::delete('/clear', [App\Http\Controllers\CartLocationController::class, 'clear']); // Vider complètement le panier de location
        Route::post('/quick-add/{product}', [App\Http\Controllers\CartLocationController::class, 'quickAdd']); // Ajout rapide d'un produit en location
        Route::post('/sync', [App\Http\Controllers\CartLocationController::class, 'sync']); // Synchroniser le panier de location
        Route::post('/validate', [App\Http\Controllers\CartLocationController::class, 'validateCart']); // Valider le panier de location
        Route::get('/count', [App\Http\Controllers\CartLocationController::class, 'getCartCount']); // Obtenir le nombre total d'articles en location
        Route::get('/total', [App\Http\Controllers\CartLocationController::class, 'getCartTotal']); // Obtenir le montant total du panier de location
        Route::post('/{cart_location}/extend', [App\Http\Controllers\CartLocationController::class, 'extend']); // Prolonger une location
    });

    // API pour la wishlist - Utilisateurs connectés uniquement
    Route::prefix('wishlist')->group(function () {
        Route::get('/', [App\Http\Controllers\WishlistController::class, 'index']); // Obtenir la wishlist
        Route::post('/add', [App\Http\Controllers\WishlistController::class, 'store']); // Ajouter un produit à la wishlist
        Route::delete('/remove/{product}', [App\Http\Controllers\WishlistController::class, 'destroy']); // Retirer un produit de la wishlist
        Route::delete('/clear', [App\Http\Controllers\WishlistController::class, 'clear']); // Vider la wishlist
        Route::post('/toggle/{product}', [App\Http\Controllers\WishlistController::class, 'toggle']); // Toggle produit (ajouter/retirer)
        Route::get('/check/{product}', [App\Http\Controllers\WishlistController::class, 'check']); // Vérifier si produit en wishlist
        Route::get('/count', [App\Http\Controllers\WishlistController::class, 'getCount']); // Nombre d'articles
        Route::post('/move-to-cart', [App\Http\Controllers\WishlistController::class, 'moveToCart']); // Transférer vers le panier
    });

    // API pour les likes de produits - Utilisateurs connectés uniquement
    Route::prefix('likes')->group(function () {
        Route::get('/', [App\Http\Controllers\ProductLikeController::class, 'index']); // Obtenir mes likes
        Route::post('/toggle', [App\Http\Controllers\ProductLikeController::class, 'store']); // Liker/unliker un produit
        Route::delete('/remove/{product}', [App\Http\Controllers\ProductLikeController::class, 'destroy']); // Retirer un like
        Route::delete('/clear', [App\Http\Controllers\ProductLikeController::class, 'clearUserLikes']); // Vider tous mes likes
        Route::get('/check/{product}', [App\Http\Controllers\ProductLikeController::class, 'check']); // Vérifier si produit liké
        Route::get('/count', [App\Http\Controllers\ProductLikeController::class, 'getUserLikeCount']); // Nombre de mes likes
    });

    // API pour les commandes - Utilisateurs connectés uniquement
    Route::prefix('orders')->group(function () {
        Route::get('/', [App\Http\Controllers\OrderController::class, 'index']); // Obtenir la liste des commandes
        Route::post('/', [App\Http\Controllers\OrderController::class, 'store']); // Créer une nouvelle commande
        Route::get('/{order}', [App\Http\Controllers\OrderController::class, 'show']); // Obtenir les détails d'une commande
        Route::put('/{order}/cancel', [App\Http\Controllers\OrderController::class, 'cancel']); // Annuler une commande (admin)
        Route::put('/{order}/user-cancel', [App\Http\Controllers\OrderController::class, 'userCancel']); // Annuler par l'utilisateur
        Route::get('/{order}/cancellation-eligibility', [App\Http\Controllers\OrderController::class, 'checkCancellationEligibility']); // Vérifier éligibilité annulation
        Route::get('/{order}/returns', [App\Http\Controllers\OrderController::class, 'returns']); // Obtenir les retours d'une commande
        Route::post('/{order}/returns', [App\Http\Controllers\OrderController::class, 'createReturn']); // Créer une demande de retour
    });

    // API pour les locations - Utilisateurs connectés uniquement
    Route::prefix('rentals')->group(function () {
        Route::get('/', [App\Http\Controllers\RentalController::class, 'index']); // Obtenir la liste des locations
        Route::get('/create', [App\Http\Controllers\RentalController::class, 'create']); // Données pour créer une location
        Route::post('/', [App\Http\Controllers\RentalController::class, 'store']); // Créer une nouvelle location
        Route::get('/statistics', [App\Http\Controllers\RentalController::class, 'statistics']); // Statistiques utilisateur
        Route::post('/check-availability', [App\Http\Controllers\RentalController::class, 'checkAvailability']); // Vérifier disponibilité
        Route::get('/{rental}', [App\Http\Controllers\RentalController::class, 'show']); // Obtenir les détails d'une location
        Route::put('/{rental}', [App\Http\Controllers\RentalController::class, 'update']); // Modifier une location
        Route::put('/{rental}/cancel', [App\Http\Controllers\RentalController::class, 'cancel']); // Annuler une location
        Route::post('/{rental}/return', [App\Http\Controllers\RentalController::class, 'processReturn']); // Traiter un retour
        Route::delete('/{rental}', [App\Http\Controllers\RentalController::class, 'destroy']); // Supprimer une location
    });

    // API pour les retours - Utilisateurs connectés uniquement
    Route::prefix('returns')->group(function () {
        Route::post('/', [App\Http\Controllers\OrderReturnController::class, 'store']); // Créer une demande de retour
        Route::get('/check-eligibility', [App\Http\Controllers\OrderReturnController::class, 'checkEligibility']); // Vérifier éligibilité
        Route::get('/{return}', [App\Http\Controllers\OrderReturnController::class, 'show']); // Détail d'un retour
    });

    // Actions personnelles de l'utilisateur
    Route::prefix('user')->group(function () {
        Route::post('/newsletter/subscribe', [App\Http\Controllers\UserController::class, 'subscribeNewsletter']);
        Route::post('/newsletter/unsubscribe', [App\Http\Controllers\UserController::class, 'unsubscribeNewsletter']);
        Route::get('/export-data', [App\Http\Controllers\UserController::class, 'exportData']);
    });

    // Actions sur les produits pour utilisateurs connectés
    Route::prefix('products')->group(function () {
        Route::post('/{product}/like', [App\Http\Controllers\ProductController::class, 'like']);
        Route::post('/{product}/wishlist', [App\Http\Controllers\ProductController::class, 'wishlist']);
    });

    // API d'administration
    Route::middleware(['permission:manage users'])->prefix('admin')->group(function () {
        Route::get('/users/statistics', [App\Http\Controllers\UserController::class, 'statistics']);
        Route::post('/users/bulk-action', [App\Http\Controllers\UserController::class, 'bulkAction']);
        Route::post('/users/{id}/restore', [App\Http\Controllers\UserController::class, 'restore']);
        Route::delete('/users/{id}/force-delete', [App\Http\Controllers\UserController::class, 'forceDelete']);
        
        // API CRUD pour les utilisateurs
        Route::apiResource('users', App\Http\Controllers\UserController::class, ['as' => 'api.admin']);
    });

    // API d'administration pour les produits
    Route::middleware(['permission:manage products'])->prefix('admin')->group(function () {
        // API principale: Gestion de stock automatisée
        Route::post('/products/{product}/update-stock', [App\Http\Controllers\ProductController::class, 'updateStock']);
        
        // Statistiques et actions en lot
        Route::get('/products/statistics', [App\Http\Controllers\ProductController::class, 'statistics']);
        Route::post('/products/bulk-action', [App\Http\Controllers\ProductController::class, 'bulkAction']);
        
        // API CRUD pour les produits
        Route::apiResource('products', App\Http\Controllers\ProductController::class, ['as' => 'api.admin']);
    });

    // API d'administration pour les contacts
    Route::middleware(['permission:manage contacts'])->prefix('admin')->group(function () {
        Route::get('/contacts', [App\Http\Controllers\ContactController::class, 'index']);
        Route::get('/contacts/{contact}', [App\Http\Controllers\ContactController::class, 'show']);
        Route::put('/contacts/{contact}', [App\Http\Controllers\ContactController::class, 'update']);
        Route::delete('/contacts/{contact}', [App\Http\Controllers\ContactController::class, 'destroy']);
        
        // Actions rapides
        Route::post('/contacts/{contact}/mark-in-progress', [App\Http\Controllers\ContactController::class, 'markInProgress']);
        Route::post('/contacts/{contact}/mark-resolved', [App\Http\Controllers\ContactController::class, 'markResolved']);
        Route::post('/contacts/{contact}/close', [App\Http\Controllers\ContactController::class, 'close']);
        Route::post('/contacts/{contact}/assign', [App\Http\Controllers\ContactController::class, 'assign']);
        
        // Statistiques et téléchargements
        Route::get('/contacts/statistics', [App\Http\Controllers\ContactController::class, 'statistics']);
        Route::get('/contacts/{contact}/attachment/{attachmentIndex}', [App\Http\Controllers\ContactController::class, 'downloadAttachment']);
    });

    // API d'administration pour les catégories
    Route::middleware(['permission:manage categories'])->prefix('admin')->group(function () {
        // Statistiques et actions en lot
        Route::get('/categories/statistics', [App\Http\Controllers\CategoryController::class, 'statistics']);
        Route::get('/categories/enhanced-statistics', [App\Http\Controllers\CategoryController::class, 'enhancedStatistics']);
        Route::post('/categories/bulk-action', [App\Http\Controllers\CategoryController::class, 'bulkAction']);
        Route::post('/categories/reorder', [App\Http\Controllers\CategoryController::class, 'reorder']);
        
        // Routes spécialisées pour les futures interfaces séparées  
        Route::get('/categories/purchase', [App\Http\Controllers\CategoryController::class, 'purchaseCategories']);
        Route::get('/categories/rental', [App\Http\Controllers\CategoryController::class, 'rentalCategories']);
        Route::get('/categories/types', [App\Http\Controllers\CategoryController::class, 'getAvailableTypes']);
        
        // API CRUD pour les catégories
        Route::apiResource('categories', App\Http\Controllers\CategoryController::class, ['as' => 'api.admin']);
    });

    // API d'administration pour les images de produits
    Route::middleware(['permission:manage products'])->prefix('admin')->group(function () {
        // Statistiques et actions spécialisées
        Route::get('/product-images/statistics', [App\Http\Controllers\ProductImageController::class, 'statistics']);
        Route::post('/product-images/bulk-destroy', [App\Http\Controllers\ProductImageController::class, 'bulkDestroy']);
        Route::post('/product-images/reorder', [App\Http\Controllers\ProductImageController::class, 'reorder']);
        Route::post('/product-images/{productImage}/set-main', [App\Http\Controllers\ProductImageController::class, 'setMain']);
        
        // API CRUD pour les images de produits
        Route::apiResource('product-images', App\Http\Controllers\ProductImageController::class, ['as' => 'api.admin']);
    });

    // API d'administration pour les likes de produits
    Route::middleware(['permission:manage products'])->prefix('admin')->group(function () {
        // Interface d'administration pour les likes
        Route::get('/likes', [App\Http\Controllers\ProductLikeController::class, 'adminIndex']); // Liste des likes pour l'admin
        Route::get('/likes/statistics', [App\Http\Controllers\ProductLikeController::class, 'statistics']); // Statistiques détaillées des likes
    });

    // API d'administration pour les commandes
    Route::middleware(['permission:manage orders'])->prefix('admin')->group(function () {
        // Interface d'administration pour les commandes
        Route::get('/orders', [App\Http\Controllers\OrderController::class, 'adminIndex']); // Liste des commandes pour l'admin
        Route::get('/orders/{order}', [App\Http\Controllers\OrderController::class, 'adminShow']); // Détail d'une commande pour l'admin
        Route::put('/orders/{order}/status', [App\Http\Controllers\OrderController::class, 'updateStatus']); // Mettre à jour le statut
        Route::get('/orders/automation/status-updates', [App\Http\Controllers\OrderController::class, 'automateStatusUpdates']); // Automatisation des statuts
        Route::post('/orders/automation/run', [App\Http\Controllers\OrderController::class, 'runStatusAutomation']); // Déclencher manuellement l'automatisation
        Route::get('/orders/statistics', [App\Http\Controllers\OrderController::class, 'statistics']); // Statistiques des commandes
        
        // Gestion des retours
        Route::get('/orders/{order}/returns', [App\Http\Controllers\OrderReturnController::class, 'index']); // Liste des retours
        Route::put('/orders/returns/{return}/approve', [App\Http\Controllers\OrderReturnController::class, 'approve']); // Approuver un retour
        Route::put('/orders/returns/{return}/reject', [App\Http\Controllers\OrderReturnController::class, 'reject']); // Rejeter un retour
        Route::put('/orders/returns/{return}/process', [App\Http\Controllers\OrderReturnController::class, 'process']); // Traiter un retour
    });

    // API d'administration pour les locations
    Route::middleware(['permission:manage_rentals'])->prefix('admin')->group(function () {
        // Interface d'administration pour les locations
        Route::get('/rentals', [App\Http\Controllers\Admin\RentalAdminController::class, 'index']); // Liste des locations pour l'admin
        Route::get('/rentals/dashboard', [App\Http\Controllers\Admin\RentalAdminController::class, 'dashboard']); // Dashboard locations
        Route::get('/rentals/export', [App\Http\Controllers\Admin\RentalAdminController::class, 'export']); // Export CSV
        Route::get('/rentals/{rental}', [App\Http\Controllers\Admin\RentalAdminController::class, 'show']); // Détail d'une location pour l'admin
        Route::put('/rentals/{rental}/status', [App\Http\Controllers\Admin\RentalAdminController::class, 'updateStatus']); // Mettre à jour le statut
        Route::put('/rentals/{rental}/confirm', [App\Http\Controllers\Admin\RentalAdminController::class, 'confirm']); // Confirmer une location
        Route::delete('/rentals/{rental}', [App\Http\Controllers\Admin\RentalAdminController::class, 'destroy']); // Supprimer une location
        
        // Gestion des amendes
        Route::get('/rentals/{rental}/penalties', [App\Http\Controllers\Admin\RentalAdminController::class, 'managePenalties']); // Liste des amendes
        Route::post('/rentals/{rental}/penalties', [App\Http\Controllers\Admin\RentalAdminController::class, 'managePenalties']); // Ajouter une amende
        Route::put('/rentals/{rental}/penalties/{penalty}', [App\Http\Controllers\Admin\RentalAdminController::class, 'updatePenalty']); // Mettre à jour une amende
        
        // Automatisation des locations
        Route::post('/rentals/automation/run', function() {
            \Artisan::call('rentals:automate');
            return response()->json([
                'success' => true,
                'message' => 'Automatisation exécutée avec succès',
                'output' => \Artisan::output()
            ]);
        }); // Déclencher l'automatisation
    });
});
