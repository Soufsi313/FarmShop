<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap de l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;

echo "🔍 Vérification des données LOC-TEST-001\n\n";

$order = OrderLocation::where('order_number', 'LOC-TEST-001')
                     ->with('orderItemLocations.product')
                     ->first();

if (!$order) {
    echo "❌ Commande LOC-TEST-001 non trouvée\n";
    exit;
}

echo "✅ Commande trouvée:\n";
echo "- ID: {$order->id}\n";
echo "- Status: {$order->status}\n";
echo "- OrderItemLocations count: {$order->orderItemLocations->count()}\n\n";

if ($order->orderItemLocations->count() > 0) {
    echo "📦 Produits:\n";
    foreach ($order->orderItemLocations as $item) {
        echo "- {$item->product_name} (ID: {$item->id})\n";
    }
} else {
    echo "⚠️ PROBLÈME: Aucun OrderItemLocation trouvé!\n";
    echo "Cela explique pourquoi la section produits ne s'affiche pas.\n\n";
    
    echo "🔧 Création des OrderItemLocations manquants...\n";
    
    // Créer l'OrderItemLocation manquant
    $product = \App\Models\Product::find($order->product_id);
    if (!$product) {
        echo "❌ Produit non trouvé (ID: {$order->product_id})\n";
        exit;
    }
    
    $item = $order->orderItemLocations()->create([
        'product_id' => $order->product_id,
        'product_name' => $product->name,
        'quantity' => $order->quantity,
        'deposit_per_item' => $order->deposit_amount / $order->quantity,
        'condition_at_return' => null,
        'item_damage_cost' => 0,
        'item_inspection_notes' => null
    ]);
    
    echo "✅ OrderItemLocation créé (ID: {$item->id})\n";
}
