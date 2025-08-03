<?php

require_once 'vendor/autoload.php';

// Charger Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\OrderLocation;
use App\Models\Product;

echo "=== VÉRIFICATION COMMANDE LOC-202508026857 ===" . PHP_EOL;

$order = OrderLocation::where('order_number', 'LOC-202508026857')->first();

if ($order) {
    echo "✅ Commande trouvée: " . $order->order_number . PHP_EOL;
    echo "Status: " . $order->status . PHP_EOL;
    echo "Payment status: " . $order->payment_status . PHP_EOL;
    echo "Total: " . $order->total_amount . "€" . PHP_EOL;
    echo "Items: " . $order->items->count() . PHP_EOL;
    echo "Période: " . $order->start_date . " -> " . $order->end_date . PHP_EOL;
    echo "Jours: " . $order->rental_days . PHP_EOL;
    echo "Pickup address: " . $order->pickup_address . PHP_EOL;
    echo "Return address: " . $order->return_address . PHP_EOL;
    echo PHP_EOL;
    
    foreach ($order->items as $item) {
        echo "--- ITEM ---" . PHP_EOL;
        echo "Product: " . $item->product_name . " (ID: " . $item->product_id . ")" . PHP_EOL;
        echo "Daily price: " . $item->daily_price . "€" . PHP_EOL;
        echo "Deposit: " . $item->deposit_amount . "€" . PHP_EOL;
        echo "Quantity: " . $item->quantity . PHP_EOL;
        
        if ($item->product) {
            echo "Stock actuel: " . $item->product->rental_stock . PHP_EOL;
            echo "Prix location/jour: " . $item->product->rental_price_per_day . "€" . PHP_EOL;
            echo "Caution: " . $item->product->rental_deposit . "€" . PHP_EOL;
        } else {
            echo "⚠️ Produit non trouvé!" . PHP_EOL;
        }
        echo PHP_EOL;
    }
} else {
    echo "❌ Commande non trouvée" . PHP_EOL;
}

echo "=== VÉRIFICATION PRODUIT ABREUVOIR ===" . PHP_EOL;
$product = Product::where('name', 'like', '%Abreuvoir%')->first();
if ($product) {
    echo "Produit trouvé: " . $product->name . PHP_EOL;
    echo "Stock rental: " . $product->rental_stock . PHP_EOL;
    echo "Prix/jour: " . $product->rental_price_per_day . "€" . PHP_EOL;
    echo "Caution: " . $product->rental_deposit . "€" . PHP_EOL;
} else {
    echo "Produit Abreuvoir non trouvé" . PHP_EOL;
}
