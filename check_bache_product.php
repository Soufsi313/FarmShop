<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "=== RECHERCHE PRODUITS BÂCHE ===\n";

// Trouvons d'abord l'ID
$allRentals = Product::where('type', 'rental')->get();
$bacheProduct = null;

foreach ($allRentals as $product) {
    if (stripos($product->name, 'bâche') !== false && stripos($product->name, 'protection') !== false) {
        $bacheProduct = $product;
        break;
    }
}

if ($bacheProduct) {
    echo "=== DONNÉES DU PRODUIT BÂCHE ===\n";
    echo "ID: {$bacheProduct->id}\n";
    echo "Nom: {$bacheProduct->name}\n";
    echo "Slug: {$bacheProduct->slug}\n";
    echo "Min: {$bacheProduct->min_rental_days}\n";
    echo "Max: " . ($bacheProduct->max_rental_days ?? 'NULL') . "\n";
    echo "Prix/jour: {$bacheProduct->rental_price_per_day}€\n";
    echo "Caution: {$bacheProduct->deposit_amount}€\n";
    echo "Stock location: {$bacheProduct->rental_stock}\n";
} else {
    echo "Aucun produit trouvé. Recherche plus large:\n";
    $allRentals = Product::where('type', 'rental')->get();
    foreach ($allRentals as $product) {
        if (stripos($product->name, 'bâche') !== false || stripos($product->name, 'protection') !== false) {
            echo "Trouvé: {$product->name}\n";
        }
    }
}
