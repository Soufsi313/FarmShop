<?php

require_once 'vendor/autoload.php';
use App\Models\OrderLocation;

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Debug commande LOC-20250701-001 ===\n\n";

$order = OrderLocation::where('order_number', 'LOC-20250701-001')->first();

if (!$order) {
    echo "❌ Commande non trouvée !\n";
    exit(1);
}

echo "✅ Commande trouvée :\n";
echo "   ID: {$order->id}\n";
echo "   Numéro: {$order->order_number}\n";
echo "   Statut: {$order->status}\n";
echo "   Client ID: {$order->user_id}\n";
echo "   Créée le: {$order->created_at}\n";

echo "\n--- Items ---\n";
$items = $order->items;
echo "Nombre d'items: " . $items->count() . "\n";

if ($items->count() === 0) {
    echo "❌ Aucun item trouvé !\n";
    
    // Chercher directement dans la table order_item_locations
    $directItems = \App\Models\OrderItemLocation::where('order_location_id', $order->id)->get();
    echo "Items trouvés directement dans la table: " . $directItems->count() . "\n";
    
    foreach ($directItems as $item) {
        echo "  - Item ID: {$item->id}, Product ID: {$item->product_id}, Nom: {$item->product_name}\n";
    }
} else {
    foreach ($items as $item) {
        echo "  - Item ID: {$item->id}, Product ID: {$item->product_id}, Nom: {$item->product_name}\n";
    }
}

echo "\n--- Relation Test ---\n";
echo "Relation items existe: " . (method_exists($order, 'items') ? 'OUI' : 'NON') . "\n";

if (method_exists($order, 'items')) {
    $relationResult = $order->items();
    echo "Query relation: " . $relationResult->toSql() . "\n";
    echo "Paramètres: " . json_encode($relationResult->getBindings()) . "\n";
}
