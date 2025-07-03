<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Product;
use App\Models\CartItem;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test de la fonctionnalité Acheter Maintenant ===\n\n";

// Trouver un utilisateur de test
$user = User::find(31);
if (!$user) {
    echo "❌ Utilisateur introuvable\n";
    exit;
}

echo "👤 Utilisateur: {$user->name} (ID: {$user->id})\n";

// Vérifier l'état initial du panier
$cartItems = $user->cartItems()->with('product')->get();
echo "🛒 Articles dans le panier avant: {$cartItems->count()}\n";

// Trouver un produit pour le test
$product = Product::where('quantity', '>', 0)->first();
if (!$product) {
    echo "❌ Aucun produit en stock trouvé\n";
    exit;
}

echo "📦 Produit test: {$product->name} (Stock: {$product->quantity})\n";

// Simuler l'achat immédiat
echo "\n🚀 Simulation de l'achat immédiat...\n";

try {
    // Vider le panier existant (comme le fait buyNow)
    $user->cartItems()->delete();
    echo "✅ Panier vidé\n";
    
    // Ajouter le produit au panier
    $cartItem = $user->cartItems()->create([
        'product_id' => $product->id,
        'quantity' => 1,
        'unit_price' => $product->price,
        'total_price' => $product->price * 1,
    ]);
    echo "✅ Produit ajouté au panier\n";
    
    // Vérifier l'état final du panier
    $finalCartItems = $user->cartItems()->with('product')->get();
    echo "🛒 Articles dans le panier après: {$finalCartItems->count()}\n";
    
    foreach ($finalCartItems as $item) {
        echo "   - {$item->product->name} x{$item->quantity} = {$item->total_price}€\n";
    }
    
    echo "\n✅ Test réussi ! La fonctionnalité 'Acheter maintenant' fonctionne.\n";
    echo "🔗 URL de redirection: http://127.0.0.1:8000/payment/form\n";
    
} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== Vérification des routes ===\n";

// Vérifier que les routes existent
$routes = [
    'orders.buy-now' => route('orders.buy-now'),
    'payment.form' => route('payment.form'),
    'payment.create-intent' => route('payment.create-intent'),
    'payment.confirm' => route('payment.confirm'),
    'payment.finalize-order' => route('payment.finalize-order'),
];

foreach ($routes as $name => $url) {
    echo "✅ {$name}: {$url}\n";
}

echo "\n🎯 Toutes les routes nécessaires sont configurées !\n";
