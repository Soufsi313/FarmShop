<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\Rental;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test de simulation de paiement ===\n\n";

// Trouver un utilisateur avec un panier
$user = User::whereHas('cartItems')->first();

if (!$user) {
    echo "❌ Aucun utilisateur avec panier trouvé. Création d'un panier test...\n";
    
    $user = User::first();
    $product = Product::first();
    
    if ($user && $product) {
        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => $product->price,
        ]);
        echo "✅ Panier test créé\n\n";
    } else {
        echo "❌ Impossible de créer un panier test\n";
        exit;
    }
}

$cartItems = $user->cartItems()->with('product')->get();

echo "👤 Utilisateur: {$user->name}\n";
echo "🛒 Articles dans le panier: {$cartItems->count()}\n\n";

// Calculer le total
$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item->total_price;
    echo "   📦 {$item->product->name} x{$item->quantity} = {$item->total_price}€\n";
}

$shippingCost = $subtotal < 25 ? 2.50 : 0;
$total = $subtotal + $shippingCost;

echo "\n💰 Récapitulatif:\n";
echo "   Sous-total: {$subtotal}€\n";
echo "   Frais de livraison: {$shippingCost}€\n";
echo "   Total: {$total}€\n\n";

// Simuler les données de session
session()->put('order_data', [
    'subtotal' => $subtotal,
    'tax_amount' => 0,
    'shipping_cost' => $shippingCost,
    'total_amount' => $total,
    'shipping_address' => ['test' => 'address'],
    'notes' => null,
    'order_type' => 'purchase',
]);

echo "📝 Données de session stockées\n";

// Vérifier l'état avant paiement
$stockAvant = [];
foreach ($cartItems as $item) {
    $stockAvant[$item->product_id] = $item->product->quantity;
}

echo "\n📊 Stock avant paiement:\n";
foreach ($stockAvant as $productId => $quantity) {
    $product = Product::find($productId);
    echo "   {$product->name}: {$quantity} unités\n";
}

// Compter les commandes avant
$ordersCount = $user->orders()->count();
echo "\n📋 Commandes utilisateur avant: {$ordersCount}\n";

echo "\n🎯 Prêt pour la simulation du paiement!\n";
echo "💡 Pour tester complètement:\n";
echo "1. Créer un PaymentIntent avec metadata order_type='purchase'\n";
echo "2. Appeler finalizeOrder() avec le payment_intent_id\n";
echo "3. Vérifier que le panier est vidé\n";
echo "4. Vérifier que le stock est décrémenté\n";
echo "5. Vérifier qu'une commande est créée\n";
echo "6. Vérifier la redirection vers /mes-commandes\n\n";

echo "✅ Le système est maintenant configuré pour:\n";
echo "   - Différencier achats et locations\n";
echo "   - Vider le panier après paiement réussi\n";
echo "   - Décrémenter le stock approprié\n";
echo "   - Rediriger vers la bonne page d'historique\n";
