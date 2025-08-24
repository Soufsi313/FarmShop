<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "=== Diagnostic des slugs de produits ===\n\n";

// Rechercher les produits avec des slugs similaires à 'epandeur'
$products = Product::where('slug', 'like', '%epandeur%')->get(['id', 'name', 'slug']);

echo "Produits avec 'epandeur' dans le slug :\n";
foreach($products as $product) {
    echo "ID: {$product->id} | Nom: " . (is_array($product->name) ? json_encode($product->name) : $product->name) . " | Slug: {$product->slug}\n";
}

echo "\n";

// Rechercher spécifiquement le slug qui pose problème
$problematicProduct = Product::where('slug', 'epandeur-dengrais')->first();
if ($problematicProduct) {
    echo "Produit avec slug 'epandeur-dengrais' :\n";
    echo "ID: {$problematicProduct->id}\n";
    echo "Nom: " . (is_array($problematicProduct->name) ? json_encode($problematicProduct->name) : $problematicProduct->name) . "\n";
    echo "Slug: {$problematicProduct->slug}\n";
} else {
    echo "Aucun produit trouvé avec le slug 'epandeur-dengrais'\n";
}

echo "\n";

// Vérifier le produit que vous essayez de modifier (ID 212)
$productToUpdate = Product::find(212);
if ($productToUpdate) {
    echo "Produit que vous essayez de modifier (ID 212) :\n";
    echo "Nom actuel: " . (is_array($productToUpdate->name) ? json_encode($productToUpdate->name) : $productToUpdate->name) . "\n";
    echo "Slug actuel: {$productToUpdate->slug}\n";
} else {
    echo "Produit avec ID 212 non trouvé\n";
}

?>
