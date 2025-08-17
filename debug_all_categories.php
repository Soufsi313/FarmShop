<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Récupérer toutes les catégories et leurs produits
$categories = App\Models\Category::with('products')->get();

echo "Catégories et leurs produits:\n\n";
foreach($categories as $category) {
    echo "=== CATÉGORIE: {$category->slug} (nom: '{$category->name}') ===\n";
    echo "Nombre de produits: " . $category->products->count() . "\n";
    
    if ($category->products->count() > 0) {
        echo "Produits:\n";
        foreach($category->products->take(5) as $product) {
            echo "  - ID: {$product->id}, Slug: {$product->slug}\n";
        }
        if ($category->products->count() > 5) {
            echo "  ... et " . ($category->products->count() - 5) . " autres\n";
        }
    }
    echo "\n";
}
