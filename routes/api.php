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

// Routes API pour la gestion des cookies RGPD
/*
TODO: Réactiver quand la bannière cookies sera fonctionnelle
Route::prefix('cookies')->group(function () {
    Route::post('/consent', [App\Http\Controllers\CookieConsentController::class, 'store']);
    Route::get('/consent', [App\Http\Controllers\CookieConsentController::class, 'show']);
    Route::delete('/consent', [App\Http\Controllers\CookieConsentController::class, 'destroy']);
    
    // Route pour les statistiques (admin uniquement)
    Route::get('/statistics', [App\Http\Controllers\CookieConsentController::class, 'statistics'])
        ->middleware('auth:sanctum');
});
*/

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

// Routes API publiques pour la newsletter
Route::prefix('newsletter')->group(function () {
    Route::post('/subscribe', [App\Http\Controllers\NewsletterController::class, 'subscribe']); // S'abonner (public)
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

    // API pour la newsletter - Utilisateurs connectés
    Route::prefix('newsletter')->group(function () {
        Route::post('/toggle', [App\Http\Controllers\NewsletterController::class, 'toggle']); // Basculer abonnement
        Route::delete('/unsubscribe', [App\Http\Controllers\NewsletterController::class, 'unsubscribe']); // Se désabonner
        Route::get('/status', [App\Http\Controllers\NewsletterController::class, 'status']); // Statut abonnement
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

    // API d'administration pour les newsletters
    Route::middleware(['permission:manage newsletters'])->prefix('admin')->group(function () {
        // API CRUD pour les newsletters (NewsletterAdminController)
        Route::get('/newsletters', [App\Http\Controllers\Admin\NewsletterAdminController::class, 'index']); // Liste
        Route::post('/newsletters', [App\Http\Controllers\Admin\NewsletterAdminController::class, 'store']); // Créer
        Route::get('/newsletters/{newsletter}', [App\Http\Controllers\Admin\NewsletterAdminController::class, 'show']); // Détail
        Route::put('/newsletters/{newsletter}', [App\Http\Controllers\Admin\NewsletterAdminController::class, 'update']); // Mettre à jour
        Route::delete('/newsletters/{newsletter}', [App\Http\Controllers\Admin\NewsletterAdminController::class, 'destroy']); // Supprimer
        
        // Actions spéciales pour les newsletters
        Route::put('/newsletters/{newsletter}/publish', [App\Http\Controllers\Admin\NewsletterAdminController::class, 'publish']); // Publier
        Route::post('/newsletters/{newsletter}/send', [App\Http\Controllers\Admin\NewsletterAdminController::class, 'send']); // Envoyer
        Route::get('/newsletters/statistics', [App\Http\Controllers\Admin\NewsletterAdminController::class, 'statistics']); // Statistiques
        Route::get('/newsletters/export', [App\Http\Controllers\Admin\NewsletterAdminController::class, 'export']); // Export
        
        // Gestion des abonnés
        Route::get('/newsletters/subscribers/list', [App\Http\Controllers\Admin\NewsletterAdminController::class, 'subscribers']); // Liste des abonnés
        
        // API CRUD pour les abonnements newsletter (NewsletterSubscriptionController)
        Route::get('/newsletter-subscriptions', [App\Http\Controllers\NewsletterSubscriptionController::class, 'index']); // Liste des abonnements
        Route::post('/newsletter-subscriptions', [App\Http\Controllers\NewsletterSubscriptionController::class, 'store']); // Créer un abonnement
        Route::get('/newsletter-subscriptions/{subscription}', [App\Http\Controllers\NewsletterSubscriptionController::class, 'show']); // Détail abonnement
        Route::put('/newsletter-subscriptions/{subscription}', [App\Http\Controllers\NewsletterSubscriptionController::class, 'update']); // Mettre à jour
        Route::delete('/newsletter-subscriptions/{subscription}', [App\Http\Controllers\NewsletterSubscriptionController::class, 'destroy']); // Supprimer
        
        // Actions en lot et export pour les abonnements
        Route::post('/newsletter-subscriptions/bulk-action', [App\Http\Controllers\NewsletterSubscriptionController::class, 'bulkAction']); // Actions en lot
        Route::get('/newsletter-subscriptions/export', [App\Http\Controllers\NewsletterSubscriptionController::class, 'export']); // Export CSV
        
        /*
        TODO: Corriger tous les namespaces App.Http en App\Http dans cette section
        
        Route::get('/blogs', [App\Http\Controllers\Admin\BlogAdminController::class, 'index']); // Liste articles
        Route::post('/blogs', [App\Http\Controllers\Admin\BlogAdminController::class, 'store']); // Créer article
        Route::get('/blogs/{blog}', [App\Http\Controllers\Admin\BlogAdminController::class, 'show']); // Détail article
        Route::put('/blogs/{blog}', [App.Http\Controllers\Admin\BlogAdminController::class, 'update']); // Mettre à jour article
        Route::delete('/blogs/{blog}', [App.Http\Controllers\Admin\BlogAdminController::class, 'destroy']); // Supprimer article
        Route::put('/blogs/{blog}/publish', [App.Http\Controllers\Admin\BlogAdminController::class, 'publish']); // Publier
        Route::put('/blogs/{blog}/unpublish', [App.Http\Controllers\Admin\BlogAdminController::class, 'unpublish']); // Dépublier
        Route::post('/blogs/bulk-action', [App.Http\Controllers\Admin\BlogAdminController::class, 'bulkAction']); // Actions en lot
        Route::get('/blogs/statistics', [App.Http\Controllers\Admin\BlogAdminController::class, 'statistics']); // Statistiques
        
        // API d'administration pour les commentaires de blog
        Route::get('/blog-comments', [App.Http\Controllers\Admin\BlogCommentAdminController::class, 'index']); // Liste commentaires
        Route::get('/blog-comments/{comment}', [App.Http\Controllers\Admin\BlogCommentAdminController::class, 'show']); // Détail commentaire
        Route::put('/blog-comments/{comment}/approve', [App.Http\Controllers\Admin\BlogCommentAdminController::class, 'approve']); // Approuver
        Route::put('/blog-comments/{comment}/reject', [App.Http\Controllers\Admin\BlogCommentAdminController::class, 'reject']); // Rejeter
        Route::put('/blog-comments/{comment}/hide', [App.Http\Controllers\Admin\BlogCommentAdminController::class, 'hide']); // Masquer
        Route::put('/blog-comments/{comment}/restore', [App.Http\Controllers\Admin\BlogCommentAdminController::class, 'restore']); // Restaurer
        Route::delete('/blog-comments/{comment}', [App.Http\Controllers\Admin\BlogCommentAdminController::class, 'destroy']); // Supprimer
        Route::post('/blog-comments/bulk-action', [App.Http\Controllers\Admin\BlogCommentAdminController::class, 'bulkAction']); // Actions en lot
        Route::get('/blog-comments/statistics', [App.Http\Controllers\Admin\BlogCommentAdminController::class, 'statistics']); // Statistiques
        
        // API d'administration pour les signalements de commentaires
        Route::get('/blog-reports', [App.Http\Controllers\Admin\BlogCommentReportAdminController::class, 'index']); // Liste signalements
        Route::get('/blog-reports/{report}', [App.Http\Controllers\Admin\BlogCommentReportAdminController::class, 'show']); // Détail signalement
        Route::put('/blog-reports/{report}/approve', [App.Http\Controllers\Admin\BlogCommentReportAdminController::class, 'approve']); // Approuver
        Route::put('/blog-reports/{report}/reject', [App.Http\Controllers\Admin\BlogCommentReportAdminController::class, 'reject']); // Rejeter
        Route::post('/blog-reports/bulk-action', [App.Http\Controllers\Admin\BlogCommentReportAdminController::class, 'bulkAction']); // Actions en lot
        Route::get('/blog-reports/statistics', [App.Http\Controllers\Admin\BlogCommentReportAdminController::class, 'statistics']); // Statistiques
        */
    });

    // API pour les commentaires et signalements (utilisateurs connectés)
    Route::prefix('blog')->group(function () {
        Route::get('/{blog}/comments', [App\Http\Controllers\BlogCommentController::class, 'index']); // Liste commentaires
        Route::post('/{blog}/comments', [App\Http\Controllers\BlogCommentController::class, 'store']); // Ajouter commentaire
        Route::get('/{blog}/comments/{comment}', [App\Http\Controllers\BlogCommentController::class, 'show']); // Détail commentaire
        Route::put('/{blog}/comments/{comment}', [App\Http\Controllers\BlogCommentController::class, 'update']); // Modifier commentaire
        Route::delete('/{blog}/comments/{comment}', [App\Http\Controllers\BlogCommentController::class, 'destroy']); // Supprimer commentaire
        Route::post('/{blog}/comments/{comment}/like', [App\Http\Controllers\BlogCommentController::class, 'toggleLike']); // Liker commentaire
        Route::get('/{blog}/comments/{comment}/replies', [App\Http\Controllers\BlogCommentController::class, 'replies']); // Réponses
        
        // Signaler un commentaire
        Route::post('/{blog}/comments/{comment}/report', [App\Http\Controllers\BlogCommentReportController::class, 'store']); // Signaler
        
        // Routes pour les utilisateurs connectés
        Route::get('/user/comments', [App\Http\Controllers\BlogCommentController::class, 'myComments']); // Mes commentaires
        Route::get('/user/reports', [App\Http\Controllers\BlogCommentReportController::class, 'myReports']); // Mes signalements
        Route::put('/user/reports/{report}/cancel', [App\Http\Controllers\BlogCommentReportController::class, 'cancel']); // Annuler signalement
        
    });

    // API d'administration pour les cookies
    Route::middleware(['permission:manage cookies'])->prefix('admin')->group(function () {
        Route::get('/cookies', [App\Http\Controllers\Admin\CookieAdminController::class, 'index']); // Liste cookies
        Route::post('/cookies', [App\Http\Controllers\Admin\CookieAdminController::class, 'store']); // Créer cookie
        Route::get('/cookies/{cookie}', [App\Http\Controllers\Admin\CookieAdminController::class, 'show']); // Détail cookie
        Route::put('/cookies/{cookie}', [App\Http\Controllers\Admin\CookieAdminController::class, 'update']); // Mettre à jour cookie
        Route::delete('/cookies/{cookie}', [App\Http\Controllers\Admin\CookieAdminController::class, 'destroy']); // Supprimer cookie
        Route::post('/cookies/bulk-action', [App\Http\Controllers\Admin\CookieAdminController::class, 'bulkAction']); // Actions en lot
        Route::get('/cookies/statistics', [App\Http\Controllers\Admin\CookieAdminController::class, 'statistics']); // Statistiques cookies
        
        // API d'administration pour les consentements cookies
        Route::get('/cookie-consents', [App\Http\Controllers\Admin\CookieAdminController::class, 'consents']); // Liste consentements
        Route::get('/cookie-consents/statistics', [App\Http\Controllers\Admin\CookieAdminController::class, 'consentStatistics']); // Statistiques consentements
    });

    // API pour les préférences cookies (utilisateurs connectés)
    Route::prefix('cookies')->group(function () {
        Route::post('/accept-all', [App\Http\Controllers\CookieController::class, 'acceptAll']); // Accepter tous
        Route::post('/reject-all', [App\Http\Controllers\CookieController::class, 'rejectAll']); // Refuser tous
        Route::post('/save-preferences', [App\Http\Controllers\CookieController::class, 'savePreferences']); // Sauvegarder préférences
        Route::post('/revoke', [App\Http\Controllers\CookieController::class, 'revokeConsent']); // Révoquer consentement
        Route::get('/history', [App\Http\Controllers\CookieController::class, 'getConsentHistory']); // Historique consentements
    });
});

// Routes API publiques pour les cookies (pas d'authentification requise)
Route::prefix('cookies')->group(function () {
    Route::get('/preferences', [App\Http\Controllers\CookieController::class, 'preferences']); // Préférences cookies
    Route::post('/accept-all', [App\Http\Controllers\CookieController::class, 'acceptAll']); // Accepter tous (visiteurs)
    Route::post('/reject-all', [App\Http\Controllers\CookieController::class, 'rejectAll']); // Refuser tous (visiteurs)
    Route::post('/save-preferences', [App\Http\Controllers\CookieController::class, 'savePreferences']); // Sauvegarder préférences (visiteurs)
    Route::get('/policy', [App\Http\Controllers\CookieController::class, 'policy']); // Politique cookies
    Route::get('/status', [App\Http\Controllers\CookieController::class, 'getConsentStatus']); // Statut consentement
    Route::get('/check/{category}', [App\Http\Controllers\CookieController::class, 'checkCategory']); // Vérifier catégorie
    Route::get('/by-category', [App\Http\Controllers\CookieController::class, 'getCookiesByCategory']); // Cookies par catégorie
    Route::post('/revoke', [App\Http\Controllers\CookieController::class, 'revokeConsent']); // Révoquer consentement (visiteurs)
});

// Routes API publiques pour le blog (pas d'authentification requise)
Route::prefix('blog')->group(function () {
    Route::get('/', [App\Http\Controllers\BlogController::class, 'index']); // Liste des articles
    Route::get('/search', [App\Http\Controllers\BlogController::class, 'search']); // Recherche
    Route::get('/popular', [App\Http\Controllers\BlogController::class, 'popular']); // Articles populaires
    Route::get('/recent', [App\Http\Controllers\BlogController::class, 'recent']); // Articles récents
    Route::get('/author/{authorId}', [App\Http\Controllers\BlogController::class, 'byAuthor']); // Articles par auteur
    Route::get('/{slug}', [App\Http\Controllers\BlogController::class, 'show']); // Détail article
});

// Routes API publiques pour la newsletter (pas d'authentification requise)
Route::prefix('newsletter')->group(function () {
    Route::get('/unsubscribe/{token}', [App\Http\Controllers\NewsletterSubscriptionController::class, 'unsubscribeByToken']); // Désabonnement par token
    Route::get('/status/{token}', [App\Http\Controllers\NewsletterSubscriptionController::class, 'checkStatus']); // Vérifier statut par token
});

// Routes API pour les réponses aux messages admin (accès admin uniquement)
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin/messages')->group(function () {
    Route::get('/{adminMessage}/replies', [App\Http\Controllers\AdminMessageReplyController::class, 'index']);
    Route::post('/{adminMessage}/replies', [App\Http\Controllers\AdminMessageReplyController::class, 'store']);
    Route::get('/{adminMessage}/replies/{reply}', [App\Http\Controllers\AdminMessageReplyController::class, 'show']);
    Route::put('/{adminMessage}/replies/{reply}', [App\Http\Controllers\AdminMessageReplyController::class, 'update']);
    Route::delete('/{adminMessage}/replies/{reply}', [App\Http\Controllers\AdminMessageReplyController::class, 'destroy']);
    Route::patch('/{adminMessage}/mark-read', [App\Http\Controllers\AdminMessageReplyController::class, 'markAsRead']);
});

// Routes API pour les messages admin (utilisateurs connectés)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/contact-admin', [App\Http\Controllers\AdminMessageController::class, 'store']);
});
