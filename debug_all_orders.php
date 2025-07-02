<?php

require_once 'vendor/autoload.php';
use App\Models\OrderLocation;
use App\Models\OrderItemLocation;

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Debug toutes les commandes ===\n\n";

$orders = OrderLocation::with(['items', 'user'])->orderBy('created_at', 'desc')->get();

foreach($orders as $order) {
    echo "Commande: {$order->order_number} (ID: {$order->id})\n";
    echo "  Statut: {$order->status}\n";
    echo "  Client: {$order->user->name}\n";
    echo "  Items: {$order->items->count()}\n";
    
    if ($order->items->count() > 0) {
        foreach($order->items as $item) {
            echo "    - {$item->product_name} (ID: {$item->id})\n";
        }
    } else {
        echo "    ❌ Aucun item\n";
    }
    echo "\n";
}

// Regarder directement la table order_item_locations
echo "=== Items dans la table order_item_locations ===\n";
$allItems = OrderItemLocation::with('orderLocation')->get();
echo "Total items dans la table: {$allItems->count()}\n\n";

foreach($allItems as $item) {
    echo "Item ID: {$item->id}\n";
    echo "  Commande: {$item->orderLocation->order_number} (ID: {$item->order_location_id})\n";
    echo "  Produit: {$item->product_name}\n\n";
}
