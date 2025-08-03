<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;
use App\Models\Product;

$order = OrderLocation::where('order_number', 'LOC-202508034159')->first();
if ($order) {
    echo "=== COMMANDE LOC-202508034159 ===" . PHP_EOL;
    echo "Statut: " . $order->status . PHP_EOL;
    echo "Payment status: " . $order->payment_status . PHP_EOL;
    echo "Créée le: " . $order->created_at . PHP_EOL;
    echo "Confirmée le: " . ($order->confirmed_at ?? 'Non confirmée') . PHP_EOL;
    echo PHP_EOL;
    
    foreach ($order->items as $item) {
        echo "PRODUIT: " . $item->product_name . " (ID: " . $item->product_id . ")" . PHP_EOL;
        echo "Quantité commandée: " . $item->quantity . PHP_EOL;
        
        $product = Product::find($item->product_id);
        if ($product) {
            echo "Stock ACTUEL: " . $product->rental_stock . PHP_EOL;
            echo "Stock ATTENDU si décrément correct: " . (35 - $item->quantity) . PHP_EOL;
            
            if ($product->rental_stock == 35) {
                echo "❌ PROBLÈME: Le stock n'a PAS été décrémenté!" . PHP_EOL;
            } else if ($product->rental_stock == (35 - $item->quantity)) {
                echo "✅ OK: Le stock a été correctement décrémenté" . PHP_EOL;
            } else {
                echo "⚠️ ANOMALIE: Stock inattendu (" . $product->rental_stock . ")" . PHP_EOL;
            }
        }
    }
} else {
    echo "Commande non trouvée" . PHP_EOL;
}
