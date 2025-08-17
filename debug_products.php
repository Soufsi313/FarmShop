<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "=== DEBUG PRODUCTS ===\n\n";

// Vérifier les produits utilisés dans les commandes
$productIds = [2, 77, 47];

foreach ($productIds as $productId) {
    $product = Product::find($productId);
    
    if ($product) {
        echo "Produit ID {$productId}:\n";
        echo "  name: '" . ($product->name ?? 'NULL') . "'\n";
        echo "  title: '" . ($product->title ?? 'NULL') . "'\n";
        echo "  slug: '" . ($product->slug ?? 'NULL') . "'\n";
        echo "  description: '" . substr($product->description ?? 'NULL', 0, 50) . "'\n";
        echo "---\n";
    } else {
        echo "Produit ID {$productId}: NOT FOUND\n";
    }
}
