<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Test de récupération des données ===\n";

try {
    $user = App\Models\User::first();
    $product = App\Models\Product::where('is_rentable', true)->first();
    
    if (!$user) {
        echo "❌ Aucun utilisateur trouvé\n";
        exit(1);
    }
    
    if (!$product) {
        echo "❌ Aucun produit louable trouvé\n";
        exit(1);
    }
    
    echo "✅ Utilisateur de test: {$user->id} ({$user->name})\n";
    echo "✅ Produit de test: {$product->id} ({$product->name})\n";
    echo "   Prix location: {$product->rental_price_per_day}€/jour\n";
    echo "   Stock: {$product->quantity}\n";
    
    // Test de création d'un panier de location
    echo "\n=== Test de création d'un panier de location ===\n";
    
    $cart = App\Models\CartLocation::getActiveCartForUser($user->id);
    echo "✅ Panier créé/récupéré: ID {$cart->id}\n";
    echo "   Status: {$cart->status}\n";
    echo "   Articles: {$cart->items->count()}\n";
    
    // Test d'ajout d'un article
    echo "\n=== Test d'ajout d'un article de location ===\n";
    
    $startDate = now()->addDay()->format('Y-m-d');
    $endDate = now()->addDays(3)->format('Y-m-d');
    
    $cartItem = App\Models\CartItemLocation::updateOrCreate([
        'cart_location_id' => $cart->id,
        'product_id' => $product->id,
    ], [
        'product_name' => $product->name,
        'product_category' => $product->category ? $product->category->name : null,
        'product_description' => $product->description,
        'product_unit' => $product->unit_symbol ?? 'unité',
        'quantity' => 1,
        'unit_price_per_day' => $product->rental_price_per_day,
        'rental_start_date' => $startDate,
        'rental_end_date' => $endDate,
        'deposit_amount' => $product->deposit_amount ?? 0,
        'status' => 'pending'
    ]);
    
    echo "✅ Article ajouté: ID {$cartItem->id}\n";
    echo "   Du: {$cartItem->rental_start_date}\n";
    echo "   Au: {$cartItem->rental_end_date}\n";
    echo "   Durée: {$cartItem->rental_duration_days} jours\n";
    echo "   Prix total: {$cartItem->total_price}€\n";
    
    // Recharger le panier pour voir les totaux
    $cart->refresh();
    $cart->load('items');
    
    echo "\n=== Résumé du panier ===\n";
    echo "✅ Total articles: {$cart->total_items}\n";
    echo "✅ Total prix: {$cart->total_amount}€\n";
    echo "✅ Total cautions: {$cart->total_deposit}€\n";
    echo "✅ Grand total: {$cart->grand_total}€\n";
    
    // Test des routes API
    echo "\n=== Test des endpoints de compteur ===\n";
    
    // Simuler une requête pour le compteur
    auth()->login($user);
    
    $controller = new App\Http\Controllers\CartLocationController();
    $request = new Illuminate\Http\Request();
    $request->setUserResolver(function () use ($user) {
        return $user;
    });
    
    $response = $controller->getCartCount($request);
    $responseData = $response->getData();
    
    echo "✅ API Count Response: " . json_encode($responseData) . "\n";
    
    echo "\n=== Tous les tests réussis! ===\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
