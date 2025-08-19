<?php

require_once 'vendor/autoload.php';

use App\Models\Product;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VÉRIFICATION DES PRODUITS ===\n\n";

$products = Product::all();

foreach ($products as $product) {
    echo "ID: {$product->id}\n";
    echo "SKU: {$product->sku}\n";
    echo "Nom: '{$product->name}'\n";
    echo "Description: " . (strlen($product->description ?? '') > 0 ? substr($product->description, 0, 50) . '...' : 'VIDE') . "\n";
    echo "Type: {$product->type}\n";
    echo "---\n";
}

echo "\nTotal produits: " . $products->count() . "\n";
