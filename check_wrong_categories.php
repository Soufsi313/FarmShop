<?php

require_once 'vendor/autoload.php';

use App\Models\Product;
use App\Models\Category;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VÉRIFICATION DES CATÉGORIES INCORRECTES ===\n\n";

// Vérifier les produits dans la catégorie Irrigation
$irrigationCategory = Category::where('slug', 'irrigation')->first();

if ($irrigationCategory) {
    echo "Catégorie Irrigation (ID: {$irrigationCategory->id}):\n";
    $irrigationProducts = Product::where('category_id', $irrigationCategory->id)->get(['id', 'name', 'sku']);
    
    foreach ($irrigationProducts as $product) {
        echo "- ID: {$product->id} | SKU: {$product->sku} | Nom: '{$product->name}'\n";
    }
    
    echo "\nTotal produits mal catégorisés: " . $irrigationProducts->count() . "\n";
} else {
    echo "Catégorie Irrigation non trouvée\n";
}

echo "\n=== LISTE DE TOUTES LES CATÉGORIES ===\n";
$categories = Category::all(['id', 'name', 'slug']);
foreach ($categories as $cat) {
    $productCount = Product::where('category_id', $cat->id)->count();
    echo "ID: {$cat->id} | '{$cat->name}' ({$cat->slug}) | {$productCount} produits\n";
}
