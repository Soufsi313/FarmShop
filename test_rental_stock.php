<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

// Trouver un produit de location
$product = Product::where('is_rental_available', true)->where('rental_stock', '>', 0)->first();
if ($product) {
    echo "Produit: " . $product->name . PHP_EOL;
    echo "Stock de location: " . $product->rental_stock . PHP_EOL;
    echo "ID: " . $product->id . PHP_EOL;
} else {
    echo "Aucun produit de location trouvé." . PHP_EOL;
    
    // Lister tous les produits de location
    $products = Product::where('is_rental_available', true)->get();
    echo "Produits de location trouvés: " . $products->count() . PHP_EOL;
    foreach ($products->take(3) as $p) {
        echo "- " . $p->name . " (ID: " . $p->id . ", Stock: " . ($p->rental_stock ?? 'null') . ")" . PHP_EOL;
    }
}
