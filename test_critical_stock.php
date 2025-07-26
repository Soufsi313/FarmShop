<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== SIMULATION COMMANDE POUR DÉCLENCHER ALERTE CRITIQUE ===" . PHP_EOL . PHP_EOL;

// Récupérer le produit pommes vertes bio
$product = \App\Models\Product::where('name', 'like', '%pommes%')->where('name', 'like', '%bio%')->first();

if (!$product) {
    echo "❌ Produit pommes vertes bio non trouvé !" . PHP_EOL;
    exit;
}

echo "📦 ÉTAT INITIAL DU STOCK" . PHP_EOL;
echo "Produit: " . $product->name . PHP_EOL;
echo "Stock actuel: " . $product->quantity . PHP_EOL;
echo "Seuil critique: " . $product->critical_threshold . PHP_EOL;
echo "Seuil stock bas: " . ($product->low_stock_threshold ?? 'Non défini') . PHP_EOL;
echo PHP_EOL;

// Calculer la quantité à commander pour déclencher l'alerte critique
$currentStock = $product->quantity;
$criticalThreshold = $product->critical_threshold;
$quantityToOrder = $currentStock - $criticalThreshold + 5; // On laisse 5 unités en dessous du seuil

echo "🛒 CRÉATION DE LA COMMANDE" . PHP_EOL;
echo "Quantité commandée: " . $quantityToOrder . " kg" . PHP_EOL;
echo "Stock après commande: " . ($currentStock - $quantityToOrder) . " kg" . PHP_EOL;
echo "Statut prévu: " . (($currentStock - $quantityToOrder) < $criticalThreshold ? "🚨 CRITIQUE" : "✅ Normal") . PHP_EOL;
echo PHP_EOL;

// Récupérer un utilisateur pour créer la commande
$user = \App\Models\User::first();
if (!$user) {
    echo "❌ Aucun utilisateur trouvé pour créer la commande !" . PHP_EOL;
    exit;
}

echo "👤 Utilisateur: " . $user->name . PHP_EOL . PHP_EOL;

// Créer la commande
echo "🔄 TRAITEMENT DE LA COMMANDE..." . PHP_EOL;

try {
    // Obtenir ou créer un panier actif
    $cart = $user->getOrCreateActiveCart();
    
    // Vider le panier pour cette simulation
    $cart->clear();
    
    // Ajouter le produit au panier
    $cartItem = $cart->addProduct($product, $quantityToOrder);
    echo "✅ Produit ajouté au panier" . PHP_EOL;
    
    // Créer la commande à partir du panier
    $shippingAddress = [
        'name' => $user->name,
        'address' => '123 Test Street',
        'city' => 'Test City',
        'postal_code' => '12345'
    ];
    
    $order = \App\Models\Order::create([
        'user_id' => $user->id,
        'status' => 'confirmed',
        'subtotal' => $cart->subtotal,
        'tax_amount' => $cart->tax_amount,
        'total_amount' => $cart->total, // Corrigé: total_amount au lieu de total
        'shipping_address' => json_encode($shippingAddress),
        'billing_address' => json_encode($shippingAddress),
        'payment_method' => 'card',
        'payment_status' => 'paid'
    ]);
    
    // Créer les items de commande
    foreach ($cart->items as $cartItem) {
        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $cartItem->product_id,
            'quantity' => $cartItem->quantity,
            'price' => $cartItem->price,
            'total' => $cartItem->total
        ]);
    }
    
    echo "✅ Commande créée (ID: " . $order->id . ")" . PHP_EOL;
    
    // Réduire le stock
    $newStock = $product->quantity - $quantityToOrder;
    $product->update(['quantity' => $newStock]);
    
    echo "✅ Stock mis à jour" . PHP_EOL . PHP_EOL;
    
} catch (Exception $e) {
    echo "❌ Erreur lors de la création de la commande: " . $e->getMessage() . PHP_EOL;
    exit;
}

// Vérifier l'état final
$product = $product->fresh();
echo "📊 ÉTAT FINAL DU STOCK" . PHP_EOL;
echo "Stock actuel: " . $product->quantity . PHP_EOL;
echo "Seuil critique: " . $product->critical_threshold . PHP_EOL;

$status = '';
if ($product->quantity == 0) {
    $status = "🔴 RUPTURE DE STOCK";
} elseif ($product->quantity <= $product->critical_threshold) {
    $status = "🚨 STOCK CRITIQUE";
} elseif ($product->low_stock_threshold && $product->quantity <= $product->low_stock_threshold) {
    $status = "⚠️ STOCK BAS";
} else {
    $status = "✅ STOCK NORMAL";
}

echo "Statut: " . $status . PHP_EOL . PHP_EOL;

// Vérifier si des alertes ont été générées
echo "🔍 VÉRIFICATION DES ALERTES GÉNÉRÉES" . PHP_EOL;
$alerts = \App\Models\Message::where('type', 'stock_alert')
    ->where('created_at', '>=', now()->subMinutes(5))
    ->orderBy('created_at', 'desc')
    ->get();

if ($alerts->count() > 0) {
    echo "✅ " . $alerts->count() . " alerte(s) générée(s):" . PHP_EOL;
    foreach ($alerts as $alert) {
        echo "  - " . $alert->content . " (créée à " . $alert->created_at->format('H:i:s') . ")" . PHP_EOL;
    }
} else {
    echo "⚠️ Aucune alerte automatique trouvée" . PHP_EOL;
    echo "Note: Les alertes peuvent être générées par des observers ou des tâches en arrière-plan" . PHP_EOL;
}

echo PHP_EOL . "🎯 SIMULATION TERMINÉE - Consultez maintenant la section 'Gestion de Stock' dans l'admin !" . PHP_EOL;
echo "URL: http://localhost:8000/admin/stock" . PHP_EOL;
