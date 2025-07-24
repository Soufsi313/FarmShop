<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Structure de la table products:\n";

try {
    $columns = \Schema::getColumnListing('products');
    echo "Colonnes disponibles:\n";
    foreach ($columns as $column) {
        echo "- $column\n";
    }
    
    echo "\nPremiers produits:\n";
    $products = App\Models\Product::limit(3)->get();
    foreach ($products as $product) {
        echo "- ID: $product->id, Nom: $product->name, Prix: $product->price\n";
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
