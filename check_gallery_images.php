<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "=== PRODUITS AVEC IMAGES DE GALERIE ===\n";

$products = Product::whereNotNull('gallery_images')
    ->where('gallery_images', '!=', '[]')
    ->select('id', 'name', 'gallery_images')
    ->get();

if ($products->count() > 0) {
    foreach ($products as $product) {
        $galleryImages = $product->gallery_images ?? [];
        echo "ID: {$product->id} - {$product->name}\n";
        echo "  Nombre d'images: " . count($galleryImages) . "\n";
        foreach ($galleryImages as $index => $image) {
            echo "  [{$index}] {$image}\n";
        }
        echo "\n";
    }
} else {
    echo "Aucun produit avec images de galerie trouvé.\n";
}

echo "=== TOTAL: " . $products->count() . " produits ===\n";
