<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

// Trouver un produit avec stock de location
$product = Product::whereNotNull('rental_stock')->where('rental_stock', '>', 0)->first();
if ($product) {
    echo "Produit trouvé: " . $product->name . PHP_EOL;
    echo "Stock de location actuel: " . $product->rental_stock . PHP_EOL;
    echo "ID du produit: " . $product->id . PHP_EOL;
} else {
    echo "Aucun produit avec stock de location trouvé" . PHP_EOL;
    
    // Vérifier tous les produits 
    $products = Product::whereNotNull('rental_stock')->take(5)->get(['id', 'name', 'rental_stock']);
    echo "Produits avec rental_stock défini: " . PHP_EOL;
    foreach($products as $p) {
        echo "- {$p->name} (ID: {$p->id}, Stock: {$p->rental_stock})" . PHP_EOL;
    }
}
