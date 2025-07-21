<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use App\Models\Product;
use App\Models\Category;

// Récupérer la catégorie "Produits laitiers"
$dairyCategory = Category::where('name', 'Produits laitiers')->first();

if ($dairyCategory) {
    echo "Catégorie trouvée: {$dairyCategory->name} (ID: {$dairyCategory->id})\n\n";
    
    $products = Product::where('category_id', $dairyCategory->id)->get(['name', 'price', 'sku']);
    
    echo "Produits dans la catégorie 'Produits laitiers' ({$products->count()} produits):\n";
    echo str_repeat("-", 60) . "\n";
    
    foreach ($products as $product) {
        echo "• {$product->name} - {$product->price}€ (SKU: {$product->sku})\n";
    }
    
    // Identifier les anciens vs nouveaux produits
    $oldProducts = $products->filter(function($p) {
        return !str_starts_with($p->sku, 'DAIRY-');
    });
    
    $newProducts = $products->filter(function($p) {
        return str_starts_with($p->sku, 'DAIRY-');
    });
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "RÉSUMÉ:\n";
    echo "• Anciens produits (non fermiers): {$oldProducts->count()}\n";
    echo "• Nouveaux produits fermiers: {$newProducts->count()}\n";
    echo "• Total: {$products->count()}\n";
    
    if ($oldProducts->count() > 0) {
        echo "\nAnciens produits à potentiellement supprimer:\n";
        foreach ($oldProducts as $old) {
            echo "- {$old->name} (SKU: {$old->sku})\n";
        }
    }
} else {
    echo "Catégorie 'Produits laitiers' non trouvée.\n";
}
