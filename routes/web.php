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

// Routes publiques pour les likes (accessibles à tous)
Route::prefix('likes')->name('public.likes.')->group(function () {
    Route::get('/most-liked', [App\Http\Controllers\ProductLikeController::class, 'mostLiked'])->name('most-liked'); // Produits les plus likés
    Route::get('/statistics', [App\Http\Controllers\ProductLikeController::class, 'statistics'])->name('statistics'); // Statistiques publiques des likes
});

// Routes publiques pour le contact (accessibles à tous)
Route::prefix('contact')->name('contact.')->group(function () {
    Route::get('/', [App\Http\Controllers\ContactController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\ContactController::class, 'store'])->name('store');
});

// Routes publiques pour les newsletters
Route::prefix('newsletter')->name('newsletter.')->group(function () {
    Route::post('/subscribe', [App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('subscribe'); // S'abonner (public)
    Route::get('/unsubscribe/{token}', [App\Http\Controllers\NewsletterController::class, 'unsubscribeByToken'])->name('unsubscribe'); // Désabonnement par token
});

Route::middleware('auth')->group(function () {
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

    // Routes pour la wishlist - Utilisateurs connectés uniquement
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [App\Http\Controllers\WishlistController::class, 'index'])->name('index'); // Afficher la wishlist
        Route::post('/add', [App\Http\Controllers\WishlistController::class, 'store'])->name('add'); // Ajouter un produit à la wishlist
        Route::delete('/remove/{product}', [App\Http\Controllers\WishlistController::class, 'destroy'])->name('remove'); // Retirer un produit de la wishlist
        Route::delete('/clear', [App\Http\Controllers\WishlistController::class, 'clear'])->name('clear'); // Vider la wishlist
        Route::post('/toggle/{product}', [App\Http\Controllers\WishlistController::class, 'toggle'])->name('toggle'); // Toggle produit (ajouter/retirer)
        Route::get('/check/{product}', [App\Http\Controllers\WishlistController::class, 'check'])->name('check'); // Vérifier si produit en wishlist
        Route::get('/count', [App\Http\Controllers\WishlistController::class, 'getCount'])->name('count'); // Nombre d'articles
        Route::post('/move-to-cart', [App\Http\Controllers\WishlistController::class, 'moveToCart'])->name('move-to-cart'); // Transférer vers le panier
    });

    // Routes pour les likes de produits - Utilisateurs connectés uniquement
    Route::prefix('likes')->name('likes.')->group(function () {
        Route::get('/', [App\Http\Controllers\ProductLikeController::class, 'index'])->name('index'); // Afficher mes likes
        Route::post('/toggle', [App\Http\Controllers\ProductLikeController::class, 'store'])->name('toggle'); // Liker/unliker un produit
        Route::delete('/remove/{product}', [App\Http\Controllers\ProductLikeController::class, 'destroy'])->name('remove'); // Retirer un like
        Route::delete('/clear', [App\Http\Controllers\ProductLikeController::class, 'clearUserLikes'])->name('clear'); // Vider tous mes likes
        Route::get('/check/{product}', [App\Http\Controllers\ProductLikeController::class, 'check'])->name('check'); // Vérifier si produit liké
        Route::get('/count', [App\Http\Controllers\ProductLikeController::class, 'getUserLikeCount'])->name('count'); // Nombre de mes likes
    });

    // Routes pour les commandes - Utilisateurs connectés uniquement
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [App\Http\Controllers\OrderController::class, 'index'])->name('index'); // Liste des commandes
        Route::get('/create', [App\Http\Controllers\OrderController::class, 'create'])->name('create'); // Formulaire de commande
        Route::post('/', [App\Http\Controllers\OrderController::class, 'store'])->name('store'); // Créer une commande
        Route::get('/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('show'); // Détail d'une commande
        Route::put('/{order}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('cancel'); // Annuler une commande (admin)
        Route::put('/{order}/user-cancel', [App\Http\Controllers\OrderController::class, 'userCancel'])->name('user-cancel'); // Annuler par l'utilisateur
        Route::get('/{order}/cancellation-eligibility', [App\Http\Controllers\OrderController::class, 'checkCancellationEligibility'])->name('cancellation-eligibility'); // Vérifier éligibilité annulation
        Route::get('/{order}/invoice', [App\Http\Controllers\OrderController::class, 'downloadInvoice'])->name('invoice'); // Télécharger la facture
        Route::get('/{order}/returns', [App\Http\Controllers\OrderController::class, 'returns'])->name('returns'); // Afficher les retours
        Route::post('/{order}/returns', [App\Http\Controllers\OrderController::class, 'createReturn'])->name('returns.store'); // Créer un retour
    });

    // Routes pour les locations - Utilisateurs connectés uniquement
    Route::prefix('rentals')->name('rentals.')->group(function () {
        Route::get('/', [App\Http\Controllers\RentalController::class, 'index'])->name('index'); // Liste des locations
        Route::get('/create', [App\Http\Controllers\RentalController::class, 'create'])->name('create'); // Formulaire de location
        Route::post('/', [App\Http\Controllers\RentalController::class, 'store'])->name('store'); // Créer une location
        Route::get('/statistics', [App\Http\Controllers\RentalController::class, 'statistics'])->name('statistics'); // Statistiques utilisateur
        Route::post('/check-availability', [App\Http\Controllers\RentalController::class, 'checkAvailability'])->name('check-availability'); // Vérifier disponibilité
        Route::get('/{rental}', [App\Http\Controllers\RentalController::class, 'show'])->name('show'); // Détail d'une location
        Route::put('/{rental}', [App\Http\Controllers\RentalController::class, 'update'])->name('update'); // Modifier une location
        Route::put('/{rental}/cancel', [App\Http\Controllers\RentalController::class, 'cancel'])->name('cancel'); // Annuler une location
        Route::post('/{rental}/return', [App\Http\Controllers\RentalController::class, 'processReturn'])->name('return'); // Traiter un retour
        Route::get('/{rental}/invoice', [App\Http\Controllers\RentalController::class, 'generateInvoice'])->name('invoice'); // Générer facture PDF
        Route::delete('/{rental}', [App\Http\Controllers\RentalController::class, 'destroy'])->name('destroy'); // Supprimer une location
    });

    // Routes pour les articles de commande - Utilisateurs connectés uniquement
    Route::prefix('order-items')->name('order-items.')->group(function () {
        Route::get('/', [App\Http\Controllers\OrderItemController::class, 'index'])->name('index'); // Liste des articles
        Route::get('/{orderItem}', [App\Http\Controllers\OrderItemController::class, 'show'])->name('show'); // Détail d'un article
        Route::post('/{orderItem}/returns', [App\Http\Controllers\OrderItemController::class, 'createReturn'])->name('returns.store'); // Créer un retour pour l'article
    });

    // Routes pour les retours - Utilisateurs connectés uniquement
    Route::prefix('returns')->name('returns.')->group(function () {
        Route::post('/', [App\Http\Controllers\OrderReturnController::class, 'store'])->name('store'); // Créer une demande de retour
        Route::get('/check-eligibility', [App\Http\Controllers\OrderReturnController::class, 'checkEligibility'])->name('check-eligibility'); // Vérifier éligibilité
        Route::get('/{return}', [App\Http\Controllers\OrderReturnController::class, 'show'])->name('show'); // Détail d'un retour
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
        Route::get('/categories-enhanced-statistics', [App\Http\Controllers\CategoryController::class, 'enhancedStatistics'])->name('categories.enhanced-statistics');
        
        // Routes spécialisées pour les futures interfaces séparées
        Route::get('/categories/purchase', [App\Http\Controllers\CategoryController::class, 'purchaseCategories'])->name('categories.purchase');
        Route::get('/categories/rental', [App\Http\Controllers\CategoryController::class, 'rentalCategories'])->name('categories.rental');
        Route::get('/categories/types', [App\Http\Controllers\CategoryController::class, 'getAvailableTypes'])->name('categories.types');
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

    // Routes d'administration pour les likes de produits
    Route::middleware(['permission:manage products'])->prefix('admin')->name('admin.')->group(function () {
        // Interface d'administration pour les likes
        Route::get('/likes', [App\Http\Controllers\ProductLikeController::class, 'adminIndex'])->name('likes.index'); // Liste des likes
        Route::get('/likes/statistics', [App\Http\Controllers\ProductLikeController::class, 'statistics'])->name('likes.statistics'); // Statistiques des likes
    });

    // Routes d'administration pour les commandes
    Route::middleware(['permission:manage orders'])->prefix('admin')->name('admin.')->group(function () {
        // Interface d'administration pour les commandes
        Route::get('/orders', [App\Http\Controllers\OrderController::class, 'adminIndex'])->name('orders.index'); // Liste des commandes
        Route::get('/orders/{order}', [App\Http\Controllers\OrderController::class, 'adminShow'])->name('orders.show'); // Détail d'une commande
        Route::put('/orders/{order}/status', [App\Http\Controllers\OrderController::class, 'updateStatus'])->name('orders.update-status'); // Mettre à jour le statut
        Route::get('/orders/automation/status-updates', [App\Http\Controllers\OrderController::class, 'automateStatusUpdates'])->name('orders.automation'); // Automatisation des statuts
        Route::post('/orders/automation/run', [App\Http\Controllers\OrderController::class, 'runStatusAutomation'])->name('orders.automation.run'); // Déclencher manuellement l'automatisation
        Route::get('/orders-statistics', [App\Http\Controllers\OrderController::class, 'statistics'])->name('orders.statistics'); // Statistiques des commandes
        
        // Gestion des retours
        Route::get('/orders/{order}/returns', [App\Http\Controllers\OrderReturnController::class, 'index'])->name('orders.returns.index'); // Liste des retours
        Route::put('/orders/returns/{return}/approve', [App\Http\Controllers\OrderReturnController::class, 'approve'])->name('orders.returns.approve'); // Approuver un retour
        Route::put('/orders/returns/{return}/reject', [App\Http\Controllers\OrderReturnController::class, 'reject'])->name('orders.returns.reject'); // Rejeter un retour
        Route::put('/orders/returns/{return}/process', [App\Http\Controllers\OrderReturnController::class, 'process'])->name('orders.returns.process'); // Traiter un retour
    });

    // Routes d'administration pour les locations
    Route::middleware(['permission:manage_rentals'])->prefix('admin')->name('admin.')->group(function () {
        // Interface d'administration pour les locations
        Route::get('/rentals', [App\Http\Controllers\Admin\RentalAdminController::class, 'index'])->name('rentals.index'); // Liste des locations
        Route::get('/rentals/dashboard', [App\Http\Controllers\Admin\RentalAdminController::class, 'dashboard'])->name('rentals.dashboard'); // Dashboard locations
        Route::get('/rentals/export', [App\Http\Controllers\Admin\RentalAdminController::class, 'export'])->name('rentals.export'); // Export CSV
        Route::get('/rentals/{rental}', [App\Http\Controllers\Admin\RentalAdminController::class, 'show'])->name('rentals.show'); // Détail d'une location
        Route::put('/rentals/{rental}/status', [App\Http\Controllers\Admin\RentalAdminController::class, 'updateStatus'])->name('rentals.update-status'); // Mettre à jour le statut
        Route::put('/rentals/{rental}/confirm', [App\Http\Controllers\Admin\RentalAdminController::class, 'confirm'])->name('rentals.confirm'); // Confirmer une location
        Route::delete('/rentals/{rental}', [App\Http\Controllers\Admin\RentalAdminController::class, 'destroy'])->name('rentals.destroy'); // Supprimer une location
        
        // Gestion des amendes
        Route::get('/rentals/{rental}/penalties', [App\Http\Controllers\Admin\RentalAdminController::class, 'managePenalties'])->name('rentals.penalties.index'); // Liste des amendes
        Route::post('/rentals/{rental}/penalties', [App\Http\Controllers\Admin\RentalAdminController::class, 'managePenalties'])->name('rentals.penalties.store'); // Ajouter une amende
        Route::put('/rentals/{rental}/penalties/{penalty}', [App\Http\Controllers\Admin\RentalAdminController::class, 'updatePenalty'])->name('rentals.penalties.update'); // Mettre à jour une amende
    
        // Automatisation des locations
        Route::get('/rentals/automation/status-updates', function() {
            return view('admin.rentals.automation');
        })->name('rentals.automation'); // Interface d'automatisation
        Route::post('/rentals/automation/run', function() {
            \Artisan::call('rentals:automate');
            return response()->json([
                'success' => true,
                'message' => 'Automatisation exécutée avec succès',
                'output' => \Artisan::output()
            ]);
        })->name('rentals.automation.run'); // Déclencher l'automatisation
    });

    // Routes d'administration pour les newsletters
    Route::middleware(['permission:manage newsletters'])->prefix('admin')->name('admin.')->group(function () {
        // CRUD pour les newsletters
        Route::get('/newsletters', [App\Http\Controllers\Admin\NewsletterAdminController::class, 'index'])->name('newsletters.index'); // Liste
        Route::get('/newsletters/create', [App\Http\Controllers\Admin\NewsletterAdminController::class, 'create'])->name('newsletters.create'); // Créer
        Route::post('/newsletters', [App\Http\Controllers\Admin\NewsletterAdminController::class, 'store'])->name('newsletters.store'); // Stocker
        Route::get('/newsletters/{newsletter}', [App\Http\Controllers\Admin\NewsletterAdminController::class, 'show'])->name('newsletters.show'); // Détail
        Route::get('/newsletters/{newsletter}/edit', [App\Http\Controllers\Admin\NewsletterAdminController::class, 'edit'])->name('newsletters.edit'); // Éditer
        Route::put('/newsletters/{newsletter}', [App\Http\Controllers\Admin\NewsletterAdminController::class, 'update'])->name('newsletters.update'); // Mettre à jour
        Route::delete('/newsletters/{newsletter}', [App\Http\Controllers\Admin\NewsletterAdminController::class, 'destroy'])->name('newsletters.destroy'); // Supprimer
        
        // Actions spéciales pour les newsletters
        Route::put('/newsletters/{newsletter}/publish', [App\Http\Controllers\Admin\NewsletterAdminController::class, 'publish'])->name('newsletters.publish'); // Publier
        Route::post('/newsletters/{newsletter}/send', [App\Http\Controllers\Admin\NewsletterAdminController::class, 'send'])->name('newsletters.send'); // Envoyer
        
        // Gestion des abonnés
        Route::get('/newsletters/subscribers/list', [App\Http\Controllers\Admin\NewsletterAdminController::class, 'subscribers'])->name('newsletters.subscribers'); // Liste des abonnés
        
        // CRUD pour les abonnements newsletter (NewsletterSubscriptionController)
        Route::get('/newsletter-subscriptions', [App\Http\Controllers\NewsletterSubscriptionController::class, 'index'])->name('newsletter-subscriptions.index'); // Liste des abonnements
        Route::post('/newsletter-subscriptions', [App\Http\Controllers\NewsletterSubscriptionController::class, 'store'])->name('newsletter-subscriptions.store'); // Créer un abonnement
        Route::get('/newsletter-subscriptions/{subscription}', [App\Http\Controllers\NewsletterSubscriptionController::class, 'show'])->name('newsletter-subscriptions.show'); // Détail abonnement
        Route::put('/newsletter-subscriptions/{subscription}', [App\Http\Controllers\NewsletterSubscriptionController::class, 'update'])->name('newsletter-subscriptions.update'); // Mettre à jour
        Route::delete('/newsletter-subscriptions/{subscription}', [App\Http\Controllers\NewsletterSubscriptionController::class, 'destroy'])->name('newsletter-subscriptions.destroy'); // Supprimer
        
        // Actions en lot pour les abonnements
        Route::post('/newsletter-subscriptions/bulk-action', [App\Http\Controllers\NewsletterSubscriptionController::class, 'bulkAction'])->name('newsletter-subscriptions.bulk-action'); // Actions en lot
        Route::get('/newsletter-subscriptions/export', [App\Http\Controllers\NewsletterSubscriptionController::class, 'export'])->name('newsletter-subscriptions.export'); // Export CSV
    });

    // Routes publiques pour la newsletter (désabonnement par token)
    Route::prefix('newsletter')->name('newsletter.')->group(function () {
        Route::get('/unsubscribe/{token}', [App\Http\Controllers\NewsletterSubscriptionController::class, 'unsubscribeByToken'])->name('unsubscribe.token'); // Désabonnement par token
        Route::get('/status/{token}', [App\Http\Controllers\NewsletterSubscriptionController::class, 'checkStatus'])->name('status.token'); // Vérifier statut par token
    });
});
