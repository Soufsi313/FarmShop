<?php

require_once 'vendor/autoload.php';
use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use App\Models\Product;

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Nettoyage des commandes de location alimentaires ===\n\n";

// Liste des produits alimentaires qui ne devraient pas être en location
$foodProducts = [
    'Pommes Golden', 'Tomates cerises', 'Fraises', 'Oranges', 'Bananes', 
    'Salade verte', 'Carottes bio', 'Poivrons rouges', 'Brocolis',
    'Courgettes', 'Radis', 'Épinards', 'Concombres', 'Aubergines'
];

echo "Recherche des commandes avec des produits alimentaires...\n\n";

$problemOrders = [];

// Trouver toutes les commandes avec des items alimentaires
$itemsToDelete = OrderItemLocation::whereIn('product_name', $foodProducts)->get();

foreach($itemsToDelete as $item) {
    $orderId = $item->order_location_id;
    if (!in_array($orderId, $problemOrders)) {
        $problemOrders[] = $orderId;
    }
    
    echo "❌ Item trouvé: {$item->product_name} dans commande {$item->orderLocation->order_number}\n";
}

echo "\n" . count($problemOrders) . " commande(s) problématique(s) trouvée(s).\n\n";

// Pour chaque commande problématique
foreach($problemOrders as $orderId) {
    $order = OrderLocation::find($orderId);
    if (!$order) continue;
    
    echo "🔍 Analyse commande {$order->order_number}:\n";
    
    $foodItems = $order->items()->whereIn('product_name', $foodProducts)->get();
    $nonFoodItems = $order->items()->whereNotIn('product_name', $foodProducts)->get();
    
    echo "   Items alimentaires: {$foodItems->count()}\n";
    echo "   Items non-alimentaires: {$nonFoodItems->count()}\n";
    
    if ($nonFoodItems->count() === 0) {
        // Commande entièrement alimentaire - supprimer
        echo "   ❌ Suppression complète de la commande (100% alimentaire)\n";
        $order->items()->delete();
        $order->delete();
    } else {
        // Commande mixte - supprimer seulement les items alimentaires
        echo "   ⚠️ Suppression des items alimentaires uniquement\n";
        $foodItems->each->delete();
        
        // Recalculer les totaux
        $newTotal = $nonFoodItems->sum('subtotal');
        $newDeposit = $nonFoodItems->sum('deposit_amount');
        
        $order->update([
            'total_amount' => $newTotal,
            'deposit_amount' => $newDeposit,
            'paid_amount' => $newTotal + $newDeposit
        ]);
        
        echo "   ✅ Nouveaux totaux: {$newTotal}€ + {$newDeposit}€ caution\n";
    }
    echo "\n";
}

echo "=== Création de nouvelles commandes avec du matériel réel ===\n\n";

// Créons quelques produits "louables" s'ils n'existent pas
$rentalProducts = [
    ['name' => 'Tondeuse à gazon électrique', 'price' => 250.00, 'description' => 'Tondeuse électrique 1800W avec bac de ramassage'],
    ['name' => 'Taille-haie électrique', 'price' => 120.00, 'description' => 'Taille-haie électrique 600W, lame 60cm'],
    ['name' => 'Souffleur de feuilles', 'price' => 180.00, 'description' => 'Souffleur thermique 2 temps, puissant'],
    ['name' => 'Échafaudage roulant', 'price' => 450.00, 'description' => 'Échafaudage mobile hauteur 3m'],
    ['name' => 'Motoculteur', 'price' => 380.00, 'description' => 'Motoculteur 4 temps avec fraises'],
    ['name' => 'Nettoyeur haute pression', 'price' => 220.00, 'description' => 'Nettoyeur 140 bars avec accessoires'],
];

foreach($rentalProducts as $productData) {
    $existing = Product::where('name', $productData['name'])->first();
    if (!$existing) {
        $product = new Product();
        $product->name = $productData['name'];
        $product->price = $productData['price'];
        $product->description = $productData['description'];
        $product->slug = \Str::slug($productData['name']);
        $product->stock = 5;
        $product->save();
        echo "✅ Produit créé: {$product->name}\n";
    }
}

echo "\n=== Résumé ===\n";
echo "✅ Nettoyage terminé !\n";
echo "✅ Produits d'outils/matériel disponibles pour location\n";
echo "💡 Les commandes ne contiennent plus de produits alimentaires\n\n";

// Afficher les commandes restantes
$remainingOrders = OrderLocation::with('items')->where('created_at', '>', '2025-07-01')->get();
echo "Commandes restantes créées aujourd'hui:\n";
foreach($remainingOrders as $order) {
    echo "  - {$order->order_number}: {$order->items->count()} item(s)\n";
}
