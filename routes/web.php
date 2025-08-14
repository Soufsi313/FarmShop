<?php

use App\Http\Controllers\RentalController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RentalCategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\MyRentalsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\OrderLocationController as AdminOrderLocationController;
use App\Http\Controllers\Admin\SpecialOfferController as AdminSpecialOfferController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\BlogCommentController as AdminBlogCommentController;
use App\Http\Controllers\Admin\StockController as AdminStockController;
use App\Http\Controllers\Admin\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Web\ProductController as WebProductController;
use App\Http\Controllers\HomeController;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('check.rental.statuses');

// Page Contact
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Page de confirmation contact
Route::get('/contact/confirmation', function () {
    return view('contact.confirmation');
})->name('contact.confirmation');

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

// Nouvelles pages légales conformes
Route::prefix('legal')->name('legal.')->group(function () {
    Route::get('/mentions-legales', function () {
        return view('legal.mentions');
    })->name('mentions');
    
    Route::get('/conditions-generales-vente', function () {
        return view('legal.cgv');
    })->name('cgv');
    
    Route::get('/conditions-generales-utilisation', function () {
        return view('legal.cgu');
    })->name('cgu');
    
    Route::get('/conditions-generales-location', function () {
        return view('legal.cgl');
    })->name('cgl');
    
    Route::get('/politique-confidentialite', function () {
        return view('legal.privacy');
    })->name('privacy');
    
    Route::get('/droits-rgpd', function () {
        return view('legal.gdpr-rights');
    })->name('gdpr-rights');
    
    Route::get('/politique-cookies', function () {
        return view('legal.cookies');
    })->name('cookies');
    
    Route::get('/demande-donnees', function () {
        return view('legal.data-request');
    })->name('data-request');
    
    Route::get('/droit-retractation', function () {
        return view('legal.returns');
    })->name('returns');
    
    Route::get('/garanties-legales', function () {
        return view('legal.warranties');
    })->name('warranties');
    
    Route::get('/mediation', function () {
        return view('legal.mediation');
    })->name('mediation');
    
    Route::get('/assurance', function () {
        return view('legal.insurance');
    })->name('insurance');
});

// Routes pour les produits publics
Route::get('/products', [WebProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [WebProductController::class, 'show'])->name('products.show');
Route::post('/products/{product}/buy-now', [WebProductController::class, 'buyNow'])->name('products.buy-now')->middleware('auth');

// Routes pour les locations
Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
Route::get('/rentals/{product:slug}', [RentalController::class, 'show'])->name('rentals.show');

Route::middleware('auth')->group(function () {
    // Routes du panier (nécessitent une authentification)
    Route::get('/cart', function () {
        return view('cart.simple');
    })->name('cart.index');
    
    // Route du panier de location
    Route::get('/cart-location', function () {
        return view('cart-location.index');
    })->name('cart-location.index');
    
    // Route AJAX pour récupérer les données du panier
    Route::get('/cart/data', function () {
        try {
            $user = auth()->user();
            $cart = $user->getOrCreateActiveCart();
            $cartSummary = $cart->getCompleteCartSummary();
            
            // Calculer les détails TVA par taux
            $tvaTaxDetails = [];
            $items = $cart->items;
            
            foreach ($items as $item) {
                $taxRate = $item->tax_rate;
                if (!isset($tvaTaxDetails[$taxRate])) {
                    $tvaTaxDetails[$taxRate] = [
                        'rate' => $taxRate,
                        'subtotal_ht' => 0,
                        'tax_amount' => 0
                    ];
                }
                $tvaTaxDetails[$taxRate]['subtotal_ht'] += $item->subtotal;
                $tvaTaxDetails[$taxRate]['tax_amount'] += $item->tax_amount;
            }
            
            return response()->json([
                'success' => true,
                'items' => $cart->items->map(function($item) {
                    return $item->toDisplayArray();
                }),
                'subtotal' => $cartSummary['formatted']['subtotal_ht'],
                'tva_details' => $tvaTaxDetails,
                'tva_amount' => $cartSummary['formatted']['tax_amount'],
                'total' => $cartSummary['formatted']['total_ttc'],
                'shipping_cost' => $cartSummary['formatted']['shipping_cost'],
                'total_with_shipping' => $cartSummary['formatted']['total_with_shipping'],
                'is_free_shipping' => $cartSummary['is_free_shipping'],
                'remaining_for_free_shipping' => $cartSummary['formatted']['remaining_for_free_shipping'],
                'total_items' => $cartSummary['total_items']
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération des données du panier', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des données du panier',
                'error' => $e->getMessage()
            ], 500);
        }
    })->name('cart.data');
    
    // Route de debug temporaire
    Route::get('/cart/debug', function () {
        $user = auth()->user();
        $cart = $user->getOrCreateActiveCart();
        
        return response()->json([
            'user_id' => $user->id,
            'cart_id' => $cart->id,
            'cart_items_count' => $cart->items()->count(),
            'cart_items' => $cart->items->map(function($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'product_image' => $item->product_image,
                    'toDisplayArray' => $item->toDisplayArray()
                ];
            })
        ]);
    });
    
    
    // Route AJAX pour ajouter un produit au panier
    Route::post('/cart/add-product/{id}', function ($id, Request $request) {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:100'
        ]);
        
        // Récupérer le produit par ID explicitement
        $product = Product::findOrFail($id);
        
        $user = auth()->user();
        $cart = $user->getOrCreateActiveCart();
        
        // Vérifier si le produit existe déjà dans le panier
        $existingItem = $cart->items()->where('product_id', $product->id)->first();
        
        if ($existingItem) {
            // Mettre à jour la quantité
            $newQuantity = $existingItem->quantity + $request->quantity;
            $existingItem->updateQuantity($newQuantity);
        } else {
            // Créer un nouvel item
            CartItem::createFromProduct($cart, $product, $request->quantity);
        }
        
        // Recalculer les totaux du panier
        $cart->calculateTotal();
        
        return response()->json([
            'success' => true,
            'message' => 'Produit ajouté au panier',
            'cart_count' => $cart->items()->sum('quantity')
        ]);
    })->name('cart.add-product');
    
    // Route pour vider le panier
    Route::post('/cart/clear', function () {
        $user = auth()->user();
        $cart = $user->getOrCreateActiveCart();
        $cart->items()->delete();
        $cart->calculateTotal();
        
        return response()->json([
            'success' => true,
            'message' => 'Panier vidé'
        ]);
    })->name('cart.clear');
    
    // Route pour mettre à jour la quantité (format simple)
    Route::post('/cart/update', function (Request $request) {
        $request->validate([
            'cart_item_id' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:100'
        ]);
        
        $user = auth()->user();
        $cart = $user->getOrCreateActiveCart();
        
        $item = $cart->items()->where('id', $request->cart_item_id)->firstOrFail();
        $item->updateQuantity($request->quantity);
        
        return response()->json([
            'success' => true,
            'message' => 'Quantité mise à jour'
        ]);
    })->name('cart.update');
    
    // Route pour supprimer un article
    Route::post('/cart/remove', function (Request $request) {
        $request->validate([
            'cart_item_id' => 'required|integer'
        ]);
        
        $user = auth()->user();
        $cart = $user->getOrCreateActiveCart();
        
        $item = $cart->items()->where('id', $request->cart_item_id)->firstOrFail();
        $item->delete();
        $cart->calculateTotal();
        
        return response()->json([
            'success' => true,
            'message' => 'Article supprimé'
        ]);
    })->name('cart.remove');
    
    // Route pour mettre à jour la quantité d'un article
    Route::put('/cart/items/{itemId}/quantity', function ($itemId, Request $request) {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:100'
        ]);
        
        $user = auth()->user();
        $cart = $user->getOrCreateActiveCart();
        
        $item = $cart->items()->where('id', $itemId)->firstOrFail();
        $item->updateQuantity($request->quantity);
        
        return response()->json([
            'success' => true,
            'message' => 'Quantité mise à jour',
            'item' => $item->toDisplayArray(),
            'cart_summary' => $cart->getCompleteCartSummary()
        ]);
    })->name('cart.update-quantity');
    
    // Route pour supprimer un article du panier
    Route::delete('/cart/items/{itemId}', function ($itemId) {
        $user = auth()->user();
        $cart = $user->getOrCreateActiveCart();
        
        $item = $cart->items()->where('id', $itemId)->firstOrFail();
        $item->delete();
        $cart->calculateTotal();
        
        return response()->json([
            'success' => true,
            'message' => 'Article supprimé du panier'
        ]);
    })->name('cart.remove-item');
    
    // Routes de checkout
    Route::get('/checkout', [\App\Http\Controllers\OrderController::class, 'showCheckout'])->name('checkout.index');
    
    Route::post('/checkout/create-order', [\App\Http\Controllers\OrderController::class, 'store'])->name('checkout.create-order');
    
    // Routes de checkout pour les locations
    Route::get('/checkout-rental', [\App\Http\Controllers\OrderLocationController::class, 'showCheckout'])->name('checkout-rental.index');
    
    Route::post('/checkout-rental/create-order', [\App\Http\Controllers\OrderLocationController::class, 'store'])->name('checkout-rental.create-order');
    
    // Route pour la page de paiement Stripe
    Route::get('/payment/{order}', function (Order $order) {
        // Debug - vérifier l'utilisateur connecté
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page');
        }
        
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Commande non autorisée');
        }
        
        // Vérifier que la commande est en attente de paiement
        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Cette commande ne peut plus être payée');
        }
        
        // Charger les items de la commande avec les produits
        $order->load(['items.product']);
        
        return view('payment.stripe', compact('order'));
    })->name('payment.stripe');
    
    // Route POST pour traiter le paiement Stripe
    Route::post('/payment/{order}/stripe', [\App\Http\Controllers\StripePaymentController::class, 'createPaymentIntentForPurchase'])
        ->name('payment.stripe.process');
    
    // Route webhook Stripe (sans middleware auth pour que Stripe puisse y accéder)
    Route::post('/webhook/stripe', [\App\Http\Controllers\StripePaymentController::class, 'webhook'])
        ->withoutMiddleware(['web', 'auth']);
    
    // Routes pour le paiement des locations
    Route::get('/payment-rental/{orderLocation}', function (\App\Models\OrderLocation $orderLocation) {
        // Debug - vérifier l'utilisateur connecté
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page');
        }
        
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($orderLocation->user_id !== auth()->id()) {
            abort(403, 'Commande non autorisée');
        }
        
        // Vérifier que la commande est en attente de paiement
        if ($orderLocation->status !== 'pending') {
            return redirect()->route('rental-orders.show', $orderLocation)
                ->with('error', 'Cette commande ne peut plus être payée');
        }
        
        // Charger les items de la commande avec les produits
        $orderLocation->load(['items.product']);
        
        return view('payment.stripe-rental', compact('orderLocation'));
    })->name('payment.stripe-rental');
    
    // Route POST pour traiter le paiement Stripe des locations
    Route::post('/payment-rental/{orderLocation}/stripe', [\App\Http\Controllers\StripePaymentController::class, 'createPaymentIntentForRental'])
        ->name('payment.stripe-rental.process');
    
    // Routes de retour après paiement Stripe pour les locations
    Route::get('/rental-payment-success/{orderLocation}', [App\Http\Controllers\OrderLocationController::class, 'paymentSuccess'])
        ->name('rental.payment.success');
    
    Route::get('/rental-payment-cancel/{orderLocation}', [App\Http\Controllers\OrderLocationController::class, 'paymentCancel'])
        ->name('rental.payment.cancel');
    
    // Route de test pour simuler le décrément de stock
    Route::get('/test-webhook/{order}', function(\App\Models\Order $order) {
        $order->update(['status' => 'confirmed', 'payment_status' => 'paid']);
        
        // Décrémenter le stock manuellement
        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product && $product->quantity >= $item->quantity) {
                $newQuantity = $product->quantity - $item->quantity;
                $product->update(['quantity' => $newQuantity]);
                
                echo "Stock du produit {$product->name} : {$product->quantity} → {$newQuantity}<br>";
            }
        }
        
        return "Test terminé ! Stock décrémenté pour la commande {$order->order_number}";
    })->name('test.webhook');
    
    // Route pour vérifier le stock d'un produit
    Route::get('/check-stock/{product}', function(\App\Models\Product $product) {
        return "Produit: {$product->name} - Stock actuel: {$product->quantity}";
    })->name('check.stock');
    
    // Routes pour les commandes utilisateur
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'webIndex'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'webShow'])->name('orders.show');
    Route::get('/orders/{order}/confirmation', function(\App\Models\Order $order) {
        // Vérifier que l'utilisateur peut voir cette commande
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Commande non autorisée');
        }
        
        // Charger les items de la commande
        $order->load('items');
        
        return view('orders.confirmation', compact('order'));
    })->name('orders.confirmation');
    Route::post('/orders/{order}/cancel', [\App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}/cancelled', [\App\Http\Controllers\OrderController::class, 'showCancelled'])->name('orders.cancelled');
    Route::get('/orders/{order}/invoice', [\App\Http\Controllers\OrderController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::get('/orders/{order}/return-confirm', [\App\Http\Controllers\OrderController::class, 'showReturnConfirmation'])->name('orders.return.confirm');
    Route::post('/orders/{order}/return', [\App\Http\Controllers\OrderController::class, 'requestReturn'])->name('orders.return');
    Route::post('/orders/{order}/reorder', [\App\Http\Controllers\OrderController::class, 'reorder'])->name('orders.reorder');
    
    // Routes pour les commandes de location utilisateur
    Route::get('/rental-orders', [\App\Http\Controllers\OrderLocationController::class, 'index'])->name('rental-orders.index');
    Route::get('/rental-orders/{orderLocation}', [\App\Http\Controllers\OrderLocationController::class, 'show'])->name('rental-orders.show');
    Route::post('/rental-orders/{orderLocation}/cancel', [\App\Http\Controllers\OrderLocationController::class, 'cancel'])->name('rental-orders.cancel');
    Route::get('/rental-orders/{orderLocation}/invoice', [\App\Http\Controllers\OrderLocationController::class, 'downloadInvoice'])->name('rental-orders.invoice');
});

// Routes d'authentification
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Routes de vérification d'email
Route::get('/email/verify', [EmailVerificationController::class, 'show'])
    ->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])->name('verification.send');

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
    
    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'showPage'])->name('wishlist.index');
    
    // Mes locations
    Route::get('/my-rentals', [MyRentalsController::class, 'index'])->name('my-rentals.index')->middleware('check.rental.statuses');
    Route::get('/my-rentals/{orderLocation}', [MyRentalsController::class, 'show'])->name('my-rentals.show')->middleware('check.rental.statuses');
    Route::post('/my-rentals/{orderLocation}/close', [MyRentalsController::class, 'close'])->name('my-rentals.close');
    Route::get('/my-rentals/{orderLocation}/invoice', [MyRentalsController::class, 'downloadInvoice'])->name('my-rentals.invoice');
    
    // Gestion des messages
    Route::patch('/messages/{message}/read', [UserController::class, 'markMessageAsRead'])->name('messages.read');
    Route::patch('/messages/{message}/archive', [UserController::class, 'archiveMessage'])->name('messages.archive');
    Route::delete('/messages/{message}', [UserController::class, 'deleteMessage'])->name('messages.delete');
    
    // Newsletter
    Route::post('/newsletter/subscribe', [UserController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
    Route::post('/newsletter/unsubscribe', [UserController::class, 'unsubscribeNewsletter'])->name('newsletter.unsubscribe');
    
    // Routes publiques pour tracking newsletter
    Route::get('/newsletter/track/{token}', [\App\Http\Controllers\NewsletterController::class, 'track'])->name('newsletter.track');
    Route::get('/newsletter/unsubscribe/{token}', [\App\Http\Controllers\NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe.token');
    Route::get('/newsletter/click/{token}', [\App\Http\Controllers\NewsletterController::class, 'trackClick'])->name('newsletter.click');
    
    // Export données RGPD
    Route::get('/profile/download-data', [UserController::class, 'downloadData'])->name('users.download-data');
    
    // Suppression de compte en 2 étapes
    Route::post('/profile/request-delete', [UserController::class, 'requestSelfDelete'])->name('users.request-delete');
    Route::get('/profile/confirm-delete/{user}', [UserController::class, 'confirmSelfDelete'])->name('account.confirm-deletion')->middleware('signed');
    
    // Ancien endpoint (pour compatibilité)
    Route::delete('/profile/self-delete', [UserController::class, 'requestSelfDelete'])->name('users.self-delete');
});

// Routes administration
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard admin (avec vérification du rôle dans le contrôleur)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // Page de statistiques avancées
    Route::get('/statistics', [DashboardController::class, 'statistics'])->name('statistics');
    
    // Routes spécifiques pour les produits (avant resource pour éviter les conflits)
    Route::post('/products/{product}/restock', [DashboardController::class, 'restockProduct'])->name('products.restock');
    Route::post('/products/{product}/restock-suggestion', [DashboardController::class, 'getProductRestockSuggestion'])->name('products.restock-suggestion');
    
    // Gestion des produits
    Route::resource('products', AdminProductController::class);
    
    // Gestion de stock
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/', [AdminStockController::class, 'index'])->name('index');
        Route::get('/alerts', [AdminStockController::class, 'alerts'])->name('alerts');
        Route::get('/reports', [AdminStockController::class, 'reports'])->name('reports');
        Route::get('/restock', [AdminStockController::class, 'restock'])->name('restock');
        Route::put('/products/{product}/update', [AdminStockController::class, 'updateStock'])->name('update');
        Route::get('/api/alerts', [AdminStockController::class, 'getStockAlerts'])->name('api.alerts');
    });
    
    // Routes pour la gestion des stocks (API-like)
    Route::post('/stock/restock-suggestions', [DashboardController::class, 'getRestockSuggestions'])->name('stock.restock-suggestions');
    Route::post('/stock/apply-all-restock', [DashboardController::class, 'applyAllRestock'])->name('stock.apply-all-restock');
    Route::post('/stock/bulk-restock', [DashboardController::class, 'bulkRestock'])->name('stock.bulk-restock');
    Route::post('/stock/bulk-update', [DashboardController::class, 'bulkUpdateStock'])->name('stock.bulk-update');
    Route::post('/stock/weekly-report', [DashboardController::class, 'generateWeeklyReport'])->name('stock.weekly-report');
    Route::post('/stock/export', [DashboardController::class, 'exportStockData'])->name('stock.export');
    Route::post('/stock/optimize', [DashboardController::class, 'optimizeStock'])->name('stock.optimize');
    
    // Gestion des catégories
    Route::resource('categories', AdminCategoryController::class);
    
    // Gestion des catégories de location
    Route::get('/rental-categories', [DashboardController::class, 'rentalCategories'])->name('rental-categories.index');
    Route::get('/rental-categories/create', [DashboardController::class, 'createRentalCategory'])->name('rental-categories.create');
    Route::post('/rental-categories', [DashboardController::class, 'storeRentalCategory'])->name('rental-categories.store');
    Route::get('/rental-categories/{rentalCategory}', [DashboardController::class, 'showRentalCategory'])->name('rental-categories.show');
    Route::get('/rental-categories/{rentalCategory}/edit', [DashboardController::class, 'editRentalCategory'])->name('rental-categories.edit');
    Route::put('/rental-categories/{rentalCategory}', [DashboardController::class, 'updateRentalCategory'])->name('rental-categories.update');
    Route::delete('/rental-categories/{rentalCategory}', [DashboardController::class, 'destroyRentalCategory'])->name('rental-categories.destroy');
    
    // Gestion des offres spéciales - CRUD complet avec contrôleur admin
    Route::resource('special-offers', AdminSpecialOfferController::class);
    Route::patch('/special-offers/{specialOffer}/toggle', [AdminSpecialOfferController::class, 'toggle'])->name('special-offers.toggle');
    
    // Sections du dashboard (pages existantes)
    Route::get('/users', [DashboardController::class, 'users'])->name('users.index');
    Route::get('/users/{user}', [DashboardController::class, 'showUser'])->name('users.show');
    Route::get('/users/{user}/edit', [DashboardController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [DashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [DashboardController::class, 'destroyUser'])->name('users.destroy');
    Route::post('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
    // Gestion des commandes d'achat
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
    
    // Gestion des commandes de location
    Route::get('/order-locations', [AdminOrderLocationController::class, 'index'])->name('order-locations.index');
    Route::get('/order-locations/export', [AdminOrderLocationController::class, 'export'])->name('order-locations.export');
    Route::get('/order-locations/{orderLocation}', [AdminOrderLocationController::class, 'show'])->name('order-locations.show');
    Route::patch('/order-locations/{orderLocation}/status', [AdminOrderLocationController::class, 'updateStatus'])->name('order-locations.update-status');
    Route::delete('/order-locations/{orderLocation}', [AdminOrderLocationController::class, 'destroy'])->name('order-locations.destroy');
    
    // Inspection des locations
    Route::post('/order-locations/{orderLocation}/start-inspection', [AdminOrderLocationController::class, 'startInspection'])->name('order-locations.start-inspection');
    Route::post('/order-locations/{orderLocation}/finalize-inspection', [AdminOrderLocationController::class, 'finalizeInspection'])->name('order-locations.finalize-inspection');
    
    // Gestion des retours de location
    Route::get('/rental-returns', [\App\Http\Controllers\Admin\RentalReturnsController::class, 'index'])->name('rental-returns.index');
    Route::get('/rental-returns/{orderLocation}', [\App\Http\Controllers\Admin\RentalReturnsController::class, 'show'])->name('rental-returns.show');
    Route::patch('/rental-returns/{orderLocation}/mark-returned', [\App\Http\Controllers\Admin\RentalReturnsController::class, 'markAsReturned'])->name('rental-returns.mark-returned');
    Route::patch('/rental-returns/{orderLocation}/start-inspection', [\App\Http\Controllers\Admin\RentalReturnsController::class, 'startInspection'])->name('rental-returns.start-inspection');
    Route::patch('/rental-returns/{orderLocation}/finish-inspection', [\App\Http\Controllers\Admin\RentalReturnsController::class, 'finishInspection'])->name('rental-returns.finish-inspection');
    Route::get('/rental-returns-export', [\App\Http\Controllers\Admin\RentalReturnsController::class, 'export'])->name('rental-returns.export');
    
    // Gestion des messages (nouvelle section)
    Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/statistics', [AdminMessageController::class, 'statistics'])->name('messages.statistics');
    Route::get('/messages/{message}', [AdminMessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{message}/respond', [AdminMessageController::class, 'respond'])->name('messages.respond');
    Route::post('/messages/{message}/mark-read', [AdminMessageController::class, 'markAsRead'])->name('messages.mark-read');
    Route::post('/messages/{message}/archive', [AdminMessageController::class, 'archive'])->name('messages.archive');
    Route::delete('/messages/{message}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');
    
    // Gestion du Blog
    Route::get('/blog', [DashboardController::class, 'blog'])->name('blog.index');
    Route::get('/blog/create', [DashboardController::class, 'createBlogPost'])->name('blog.create');
    Route::post('/blog', [DashboardController::class, 'storeBlogPost'])->name('blog.store');
    Route::get('/blog/{blogPost}/edit', [DashboardController::class, 'editBlogPost'])->name('blog.edit');
    Route::put('/blog/{blogPost}', [DashboardController::class, 'updateBlogPost'])->name('blog.update');
    Route::delete('/blog/{blogPost}', [DashboardController::class, 'destroyBlogPost'])->name('blog.destroy');
    
    Route::get('/blog-categories', [DashboardController::class, 'blogCategories'])->name('blog-categories.index');
    Route::post('/blog-categories', [DashboardController::class, 'storeBlogCategory'])->name('blog-categories.store');
    Route::put('/blog-categories/{blogCategory}', [DashboardController::class, 'updateBlogCategory'])->name('blog-categories.update');
    Route::delete('/blog-categories/{blogCategory}', [DashboardController::class, 'destroyBlogCategory'])->name('blog-categories.destroy');
    
    // Gestion des commentaires de blog
    Route::get('/blog-comments', [AdminBlogCommentController::class, 'index'])->name('blog-comments.index');
    Route::get('/blog-comments/{comment}', [AdminBlogCommentController::class, 'show'])->name('blog-comments.show');
    Route::put('/blog-comments/{comment}', [AdminBlogCommentController::class, 'update'])->name('blog-comments.update');
    Route::delete('/blog-comments/{comment}', [AdminBlogCommentController::class, 'destroy'])->name('blog-comments.destroy');
    Route::post('/blog-comments/bulk-action', [AdminBlogCommentController::class, 'bulkAction'])->name('blog-comments.bulk-action');
    
    // Gestion des signalements de commentaires
    Route::get('/blog-comment-reports', [AdminBlogCommentController::class, 'reports'])->name('blog-comment-reports.index');
    Route::put('/blog-comment-reports/{report}', [AdminBlogCommentController::class, 'updateReport'])->name('blog-comment-reports.update');
    Route::delete('/blog-comment-reports/{report}', [AdminBlogCommentController::class, 'destroyReport'])->name('blog-comment-reports.destroy');
    
    // Gestion des newsletters
    Route::resource('newsletters', AdminNewsletterController::class);
    Route::post('/newsletters/{newsletter}/send', [AdminNewsletterController::class, 'send'])->name('newsletters.send');
    Route::post('/newsletters/{newsletter}/send-to-me', [AdminNewsletterController::class, 'sendToMe'])->name('newsletters.send-to-me');
    Route::post('/newsletters/{newsletter}/test', [AdminNewsletterController::class, 'sendTest'])->name('newsletters.test');
    Route::get('/newsletters/{newsletter}/subscribers', [AdminNewsletterController::class, 'subscribers'])->name('newsletters.subscribers');
    Route::post('/newsletters/{newsletter}/duplicate', [AdminNewsletterController::class, 'duplicate'])->name('newsletters.duplicate');
    
    // Gestion des abonnés newsletter (nouvelles routes)
    Route::post('/newsletter-subscribers/subscribe', [AdminNewsletterController::class, 'subscribeUser'])->name('newsletter.subscribers.subscribe');
    Route::post('/newsletter-subscribers/unsubscribe', [AdminNewsletterController::class, 'unsubscribeUser'])->name('newsletter.subscribers.unsubscribe');
    Route::post('/newsletter-subscribers/bulk-action', [AdminNewsletterController::class, 'bulkSubscriberAction'])->name('newsletter.subscribers.bulk');
    
    // Gestion des abonnés newsletter
    Route::get('/newsletter-subscribers', [AdminNewsletterController::class, 'allSubscribers'])->name('newsletter.subscribers');
    Route::post('/newsletter-subscribers/{user}/toggle', [AdminNewsletterController::class, 'toggleSubscription'])->name('newsletter.subscribers.toggle');
    Route::delete('/newsletter-subscribers/{user}', [AdminNewsletterController::class, 'deleteSubscriber'])->name('newsletter.subscribers.delete');
    Route::post('/newsletter-subscribers/bulk-action', [AdminNewsletterController::class, 'bulkSubscriberAction'])->name('newsletter.subscribers.bulk');
    Route::get('/newsletter-subscribers/{user}/history', [AdminNewsletterController::class, 'subscriberHistory'])->name('newsletter.subscribers.history');
    Route::get('/newsletter-subscribers/export', [AdminNewsletterController::class, 'exportSubscribers'])->name('newsletter.subscribers.export');
    
    // Gestion des abonnés newsletter
    Route::prefix('newsletters/subscribers')->name('newsletters.subscribers.')->group(function () {
        Route::get('/export', [AdminNewsletterController::class, 'exportSubscribers'])->name('export');
        Route::get('/{user}/history', [AdminNewsletterController::class, 'subscriberHistory'])->name('history');
        Route::post('/{user}/toggle', [AdminNewsletterController::class, 'toggleSubscription'])->name('toggle');
        Route::delete('/{user}', [AdminNewsletterController::class, 'deleteSubscriber'])->name('delete');
        Route::post('/bulk-action', [AdminNewsletterController::class, 'bulkAction'])->name('bulk-action');
    });
    
    // Gestion des cookies
    Route::get('/cookies', [App\Http\Controllers\Admin\CookieController::class, 'index'])->name('cookies.index');
    
    // API admin cookies (avec auth web)
    Route::prefix('api/cookies')->name('api.cookies.')->group(function () {
        Route::get('/stats', [App\Http\Controllers\Admin\CookieController::class, 'stats'])->name('stats');
        Route::get('/list', [App\Http\Controllers\Admin\CookieController::class, 'list'])->name('list');
        Route::get('/export', [App\Http\Controllers\Admin\CookieController::class, 'export'])->name('export');
        Route::get('/{cookie}', [App\Http\Controllers\Admin\CookieController::class, 'show'])->name('show');
        Route::put('/{cookie}/status', [App\Http\Controllers\Admin\CookieController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{cookie}', [App\Http\Controllers\Admin\CookieController::class, 'destroy'])->name('destroy');
        Route::delete('/cleanup/all', [App\Http\Controllers\Admin\CookieController::class, 'cleanup'])->name('cleanup');
    });
    
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings.index');
});

// Routes web pour likes et wishlists (retournent du JSON mais utilisent l'auth web)
Route::middleware(['auth'])->group(function () {
    Route::post('/web/likes/products/{product}/toggle', [\App\Http\Controllers\ProductLikeController::class, 'toggle'])->name('web.likes.toggle');
    Route::post('/web/wishlist/products/{product}/toggle', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('web.wishlist.toggle');
    Route::delete('/web/wishlist/products/{product}', [\App\Http\Controllers\WishlistController::class, 'destroy'])->name('web.wishlist.destroy');
    Route::delete('/web/wishlist/clear', [\App\Http\Controllers\WishlistController::class, 'clear'])->name('web.wishlist.clear');
});

// Routes Blog public
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [\App\Http\Controllers\BlogPostController::class, 'index'])->name('index');
    Route::get('/{slug}', [\App\Http\Controllers\BlogPostController::class, 'showWeb'])->name('show');
});

// Routes API publiques pour les fonctionnalités de location (sans CSRF)
Route::prefix('api/rentals')->name('api.rentals.')->withoutMiddleware(['csrf'])->group(function () {
    Route::get('/{product}/constraints', [RentalController::class, 'getProductConstraints'])->name('constraints');
    Route::post('/{product}/calculate-cost', [RentalController::class, 'calculateRentalCost'])->name('calculate-cost');
});

// Route de debug temporaire pour les cookies
Route::get('/debug-cookies', function() {
    $cookies = \App\Models\Cookie::orderBy('created_at', 'desc')->get();
    
    $output = "<h1>🍪 Debug Cookies System</h1>";
    $output .= "<h2>État actuel des cookies:</h2><ul>";
    
    foreach($cookies as $cookie) {
        $output .= "<li>";
        $output .= "<strong>Cookie #{$cookie->id}</strong><br>";
        $output .= "User ID: " . ($cookie->user_id ?? '<em>NULL (visiteur)</em>') . "<br>";
        $output .= "Session ID: " . ($cookie->session_id ?? '<em>NULL</em>') . "<br>";
        $output .= "IP: {$cookie->ip_address}<br>";
        $output .= "Status: <strong>{$cookie->status}</strong><br>";
        $output .= "Migrated: " . ($cookie->migrated_at ? $cookie->migrated_at->format('Y-m-d H:i:s') : '<em>Jamais</em>') . "<br>";
        $output .= "Created: {$cookie->created_at->format('Y-m-d H:i:s')}<br>";
        $output .= "</li><br>";
    }
    
    $output .= "</ul>";
    
    $output .= "<h2>Session actuelle:</h2>";
    $output .= "Session ID: " . session()->getId() . "<br>";
    $output .= "User connecté: " . (auth()->check() ? "OUI (ID: " . auth()->id() . ")" : "NON") . "<br>";
    $output .= "IP: " . request()->ip() . "<br>";
    
    $output .= "<h2>Actions de test:</h2>";
    $output .= "<a href='/reset-cookies' style='background: red; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>🗑️ Réinitialiser TOUS les cookies</a><br><br>";
    $output .= "<a href='/reset-local-storage' style='background: orange; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>🧹 Nettoyer localStorage</a>";
    
    return $output;
});

// Route pour réinitialiser tous les cookies
Route::get('/reset-cookies', function() {
    \App\Models\Cookie::truncate();
    return redirect('/debug-cookies')->with('message', 'Tous les cookies ont été supprimés !');
});

// Route pour nettoyer le localStorage
Route::get('/reset-local-storage', function() {
    return "<script>
        localStorage.clear();
        sessionStorage.clear();
        console.log('🧹 localStorage et sessionStorage nettoyés !');
        alert('LocalStorage nettoyé ! Retour à la page de debug...');
        window.location.href = '/debug-cookies';
    </script>";
});
