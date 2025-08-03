<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use App\Models\Product;

echo "=== HISTORIQUE PRODUIT 241 (Abreuvoir mobile 1000L) ===" . PHP_EOL;

$product = Product::find(241);
echo "Stock actuel: " . $product->rental_stock . PHP_EOL;
echo PHP_EOL;

// Trouver toutes les commandes contenant ce produit
$orderItems = OrderItemLocation::where('product_id', 241)
    ->with('orderLocation')
    ->orderBy('created_at', 'desc')
    ->get();

echo "Commandes trouvées: " . $orderItems->count() . PHP_EOL;
echo PHP_EOL;

$totalDecremente = 0;
foreach ($orderItems as $item) {
    $order = $item->orderLocation;
    echo "Commande: " . $order->order_number . PHP_EOL;
    echo "  - Date: " . $order->created_at . PHP_EOL;
    echo "  - Statut: " . $order->status . PHP_EOL;
    echo "  - Payment: " . $order->payment_status . PHP_EOL;
    echo "  - Quantité: " . $item->quantity . PHP_EOL;
    
    if ($order->status == 'confirmed' && $order->payment_status == 'paid') {
        $totalDecremente += $item->quantity;
        echo "  - Stock décrémenté: OUI (" . $item->quantity . " unités)" . PHP_EOL;
    } else {
        echo "  - Stock décrémenté: NON (commande non confirmée)" . PHP_EOL;
    }
    echo PHP_EOL;
}

echo "Total théorique décrémenté: " . $totalDecremente . PHP_EOL;
echo "Stock attendu: " . (35 - $totalDecremente) . PHP_EOL;
echo "Stock réel: " . $product->rental_stock . PHP_EOL;
echo "Différence: " . ((35 - $totalDecremente) - $product->rental_stock) . PHP_EOL;
