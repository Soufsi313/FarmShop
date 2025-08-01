<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

use App\Models\Product;

echo "Vérification du produit avec ID 241:\n";

$product = Product::find(241);

if ($product) {
    echo "Produit trouvé:\n";
    echo "- ID: {$product->id}\n";
    echo "- Nom: {$product->name}\n";
    echo "- Slug: {$product->slug}\n";
    echo "- Type: {$product->type}\n";
    echo "- Actif: " . ($product->is_active ? 'Oui' : 'Non') . "\n";
} else {
    echo "Aucun produit trouvé avec l'ID 241\n";
}

echo "\nVérification du produit avec slug (s'il existe):\n";
if ($product && $product->slug) {
    $productBySlug = Product::where('slug', $product->slug)->first();
    if ($productBySlug) {
        echo "Produit trouvé par slug: {$productBySlug->id} - {$productBySlug->name}\n";
    } else {
        echo "Aucun produit trouvé avec le slug: {$product->slug}\n";
    }
}
