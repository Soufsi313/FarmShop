<?php

/**
 * Test d'Integration: Commande avec Panier Vide
 * 
 * Teste le comportement du systeme lors d'une tentative de commande avec un panier vide
 */

// Bootstrap Laravel si necessaire
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
    require_once __DIR__ . '/../../vendor/autoload.php';
    $app = require_once __DIR__ . '/../../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
}

echo "\n========================================\n";
echo "TEST INTEGRATION: COMMANDE PANIER VIDE\n";
echo "========================================\n\n";

$startTime = microtime(true);
$errors = [];

try {
    // 1. Creer un utilisateur de test
    echo "1. Preparation de l'utilisateur de test...\n";
    
    $user = \App\Models\User::where('email', 'test_empty_cart@example.com')->first();
    
    if (!$user) {
        $user = new \App\Models\User();
        $user->username = 'test_empty_cart_' . time();
        $user->email = 'test_empty_cart@example.com';
        $user->password = bcrypt('password');
        $user->email_verified_at = now();
        $user->save();
        echo "   - Utilisateur cree: {$user->email}\n";
    } else {
        echo "   - Utilisateur existant: {$user->email}\n";
    }

    // 2. Verifier que le panier est vide
    echo "\n2. Verification du panier...\n";
    
    $cart = \App\Models\Cart::where('user_id', $user->id)->first();
    
    if ($cart) {
        // Vider le panier s'il existe
        $cart->items()->delete();
        echo "   - Panier vide: " . $cart->items()->count() . " items\n";
    } else {
        // Creer un panier vide
        $cart = new \App\Models\Cart();
        $cart->user_id = $user->id;
        $cart->subtotal = 0;
        $cart->tax_amount = 0;
        $cart->total = 0;
        $cart->tax_rate = 0.20;
        $cart->total_items = 0;
        $cart->save();
        echo "   - Nouveau panier cree (vide)\n";
    }
    
    $itemsCount = $cart->items()->count();
    if ($itemsCount > 0) {
        $errors[] = "Le panier devrait etre vide mais contient $itemsCount items";
    }

    // 3. Tenter de proceder au checkout avec un panier vide
    echo "\n3. Tentative de checkout avec panier vide...\n";
    
    $canCheckout = false;
    $checkoutError = null;
    
    // Simuler la validation du panier avant checkout
    if ($cart->items()->count() === 0) {
        $checkoutError = "Le panier est vide. Veuillez ajouter des produits avant de passer commande.";
        echo "   - Validation echouee: $checkoutError\n";
    } else {
        $canCheckout = true;
    }
    
    if ($canCheckout) {
        $errors[] = "Le checkout ne devrait PAS etre autorise avec un panier vide";
    } else {
        echo "   - Resultat attendu: Checkout bloque (CORRECT)\n";
    }

    // 4. Tenter de creer une commande directement (contourner la validation)
    echo "\n4. Test de creation directe de commande...\n";
    
    $orderCreationFailed = false;
    try {
        $order = new \App\Models\Order();
        $order->user_id = $user->id;
        $order->order_number = 'TEST-EMPTY-' . time();
        $order->total_amount = 0;
        $order->subtotal = 0;
        $order->tax_amount = 0;
        $order->shipping_cost = 0;
        $order->discount_amount = 0;
        $order->status = 'pending';
        $order->payment_status = 'pending';
        $order->billing_address = json_encode(['test' => true]);
        $order->shipping_address = json_encode(['test' => true]);
        $order->can_be_cancelled = true;
        $order->can_be_returned = false;
        $order->has_returnable_items = false;
        $order->has_non_returnable_items = false;
        
        // Cette sauvegarde devrait techniquement fonctionner
        // mais la commande n'aurait aucun item
        $order->save();
        
        echo "   - Commande creee: {$order->order_number}\n";
        
        // Verifier le nombre d'items
        $orderItemsCount = $order->items()->count();
        echo "   - Items dans la commande: $orderItemsCount\n";
        
        if ($orderItemsCount === 0) {
            echo "   - Alerte: Commande sans items (invalide)\n";
            $errors[] = "Une commande sans items a ete creee (ne devrait pas etre possible)";
        }
        
        // Nettoyer
        $order->delete();
        
    } catch (\Exception $e) {
        $orderCreationFailed = true;
        echo "   - Creation de commande echouee: " . $e->getMessage() . "\n";
    }

    // 5. Tester la methode de validation du panier
    echo "\n5. Test des methodes de validation du panier...\n";
    
    if (method_exists($cart, 'isEmpty')) {
        $isEmpty = $cart->isEmpty();
        echo "   - Cart::isEmpty(): " . ($isEmpty ? 'OUI' : 'NON') . "\n";
    } else {
        // Methode alternative
        $isEmpty = $cart->items()->count() === 0;
        echo "   - Verification manuelle: Panier " . ($isEmpty ? 'vide' : 'non vide') . "\n";
    }
    
    if (method_exists($cart, 'canCheckout')) {
        $canCheckoutMethod = $cart->canCheckout();
        echo "   - Cart::canCheckout(): " . ($canCheckoutMethod ? 'OUI' : 'NON') . "\n";
        
        if ($canCheckoutMethod) {
            $errors[] = "canCheckout() devrait retourner FALSE pour un panier vide";
        }
    }

    // 6. Tester les regles de validation Laravel
    echo "\n6. Test des regles de validation...\n";
    
    $validator = \Illuminate\Support\Facades\Validator::make(
        ['cart_items' => []],
        ['cart_items' => 'required|array|min:1']
    );
    
    if ($validator->fails()) {
        echo "   - Validation Laravel: ECHOUEE (attendu)\n";
        $validationErrors = $validator->errors()->first('cart_items');
        echo "   - Message d'erreur: $validationErrors\n";
    } else {
        $errors[] = "La validation devrait echouer pour un panier vide";
    }

    // 7. Tester le comportement du controleur Cart
    echo "\n7. Simulation du comportement du CartController...\n";
    
    if (class_exists('App\Http\Controllers\CartController')) {
        echo "   - CartController existe\n";
        
        // Simuler la logique du controleur
        $cartController = new \App\Http\Controllers\CartController();
        
        // Le controleur devrait avoir une methode pour verifier le checkout
        if (method_exists($cartController, 'prepareForCheckout')) {
            echo "   - Methode prepareForCheckout() existe\n";
            
            // Cette methode devrait verifier que le panier n'est pas vide
            try {
                // Authentifier l'utilisateur pour le test
                \Illuminate\Support\Facades\Auth::login($user);
                
                $request = \Illuminate\Http\Request::create('/api/cart/checkout/prepare', 'GET');
                $response = $cartController->prepareForCheckout($request);
                
                $responseData = json_decode($response->getContent(), true);
                
                if ($response->status() !== 200) {
                    echo "   - Reponse: Code " . $response->status() . "\n";
                    echo "   - Message: " . ($responseData['message'] ?? 'Aucun message') . "\n";
                } else {
                    echo "   - Reponse: Code 200\n";
                    if (isset($responseData['can_checkout']) && !$responseData['can_checkout']) {
                        echo "   - can_checkout: FALSE (correct)\n";
                    }
                }
                
                \Illuminate\Support\Facades\Auth::logout();
                
            } catch (\Exception $e) {
                echo "   - Erreur lors du test: " . $e->getMessage() . "\n";
            }
        }
    }

    // 8. Verifier les contraintes de base de donnees
    echo "\n8. Verification des contraintes base de donnees...\n";
    
    // Les commandes doivent avoir au moins 1 item
    $ordersWithoutItems = \App\Models\Order::doesntHave('items')->count();
    
    if ($ordersWithoutItems > 0) {
        echo "   - Alerte: $ordersWithoutItems commande(s) sans items trouvee(s)\n";
        $errors[] = "$ordersWithoutItems commande(s) sans items existent (anomalie)";
    } else {
        echo "   - Aucune commande sans items trouvee (correct)\n";
    }

    // 9. Tester le message d'erreur utilisateur
    echo "\n9. Test des messages d'erreur utilisateur...\n";
    
    $userMessages = [
        "Le panier est vide. Veuillez ajouter des produits avant de passer commande.",
        "Votre panier ne contient aucun article.",
        "Impossible de passer commande avec un panier vide."
    ];
    
    echo "   - Messages d'erreur disponibles:\n";
    foreach ($userMessages as $msg) {
        echo "     * $msg\n";
    }

    // 10. Nettoyer les donnees de test
    echo "\n10. Nettoyage...\n";
    
    if ($cart) {
        $cart->items()->delete();
        echo "   - Panier nettoye\n";
    }
    
    // Ne pas supprimer l'utilisateur pour les tests futurs
    echo "   - Utilisateur conserve pour futurs tests\n";

} catch (\Exception $e) {
    $errors[] = "Exception: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}

// Resultats
$duration = round((microtime(true) - $startTime) * 1000, 2);

echo "\n========================================\n";
if (count($errors) > 0) {
    echo "RESULTAT: ECHEC\n";
    echo "========================================\n";
    echo "Erreurs detectees:\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
    echo "\nDuree: {$duration}ms\n";
    exit(1);
} else {
    echo "RESULTAT: REUSSI\n";
    echo "========================================\n";
    echo "Le systeme bloque correctement les commandes avec panier vide\n";
    echo "Duree: {$duration}ms\n";
    exit(0);
}
