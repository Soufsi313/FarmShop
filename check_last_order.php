<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\OrderLocation;

// Vérifier le stock du produit 241 (dernière commande)
$product241 = Product::find(241);
if ($product241) {
    echo "Produit 241: " . $product241->name . PHP_EOL;
    echo "Stock de location ACTUEL: " . ($product241->rental_stock ?? 'null') . PHP_EOL;
    echo "Disponible pour location: " . ($product241->is_rental_available ? 'Oui' : 'Non') . PHP_EOL;
} else {
    echo "Produit 241 non trouvé" . PHP_EOL;
}

echo PHP_EOL;

// Vérifier le stock avant
$product122 = Product::find(122);
echo "Produit 122 - Stock: " . $product122->rental_stock . PHP_EOL;

// Trouver la dernière commande créée
$lastOrder = OrderLocation::latest()->first();
if ($lastOrder) {
    echo "Dernière commande: " . $lastOrder->order_number . PHP_EOL;
    echo "Statut: " . $lastOrder->status . PHP_EOL;
    echo "Items: " . $lastOrder->items->count() . PHP_EOL;
    
    foreach ($lastOrder->items as $item) {
        echo "- Produit: " . $item->product_name . " (ID: " . $item->product_id . "), Quantité: " . $item->quantity . PHP_EOL;
    }
}
