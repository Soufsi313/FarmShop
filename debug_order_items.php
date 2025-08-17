<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Models\OrderItem;

echo "=== DEBUG ORDER ITEMS ===\n\n";

// Récupérer les dernières commandes avec leurs items
$orders = Order::with(['items.product'])->latest()->take(3)->get();

foreach ($orders as $order) {
    echo "Commande #{$order->id} - Statut: {$order->status}\n";
    echo "Items dans cette commande:\n";
    
    foreach ($order->items as $item) {
        echo "  - ID: {$item->id}\n";
        echo "    product_name: '" . ($item->product_name ?? 'NULL') . "'\n";
        echo "    product_id: {$item->product_id}\n";
        echo "    quantity: {$item->quantity}\n";
        
        if ($item->product) {
            echo "    product->name: '{$item->product->name}'\n";
        } else {
            echo "    product: NULL (relation non chargée ou produit supprimé)\n";
        }
        echo "  ---\n";
    }
    echo "\n";
}
