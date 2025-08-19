<?php

require_once 'vendor/autoload.php';

use App\Models\Product;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VÉRIFICATION DES PRODUITS ===\n\n";

$products = Product::take(10)->get(['id', 'name', 'sku', 'type']);

if ($products->count() > 0) {
    echo "Premiers 10 produits:\n";
    foreach ($products as $product) {
        echo "- ID: {$product->id} | SKU: {$product->sku} | Nom: '{$product->name}' | Type: {$product->type}\n";
    }
} else {
    echo "❌ AUCUN PRODUIT TROUVÉ!\n";
}

$total = Product::count();
echo "\nTotal produits: {$total}\n";
