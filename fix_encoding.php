<?php

require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Models\Product;

echo "🔍 Recherche des produits avec problèmes d'encodage...\n\n";

// Rechercher les produits avec des entités HTML
$products = Product::where('name', 'like', '%&#%')
                   ->orWhere('description', 'like', '%&#%')
                   ->orWhere('short_description', 'like', '%&#%')
                   ->get();

if ($products->count() > 0) {
    echo "❌ Trouvé {$products->count()} produit(s) avec des problèmes d'encodage:\n";
    
    foreach ($products as $product) {
        echo "\n📦 ID: {$product->id}\n";
        echo "   Nom: {$product->name}\n";
        
        if (strpos($product->description, '&#') !== false) {
            echo "   Description contient des entités HTML\n";
        }
        
        if (strpos($product->short_description, '&#') !== false) {
            echo "   Description courte contient des entités HTML\n";
        }
    }
    
    echo "\n🔧 Correction automatique des entités HTML...\n";
    
    foreach ($products as $product) {
        $updated = false;
        
        // Décoder les entités HTML
        if (strpos($product->name, '&#') !== false) {
            $product->name = html_entity_decode($product->name, ENT_QUOTES, 'UTF-8');
            $updated = true;
        }
        
        if (strpos($product->description, '&#') !== false) {
            $product->description = html_entity_decode($product->description, ENT_QUOTES, 'UTF-8');
            $updated = true;
        }
        
        if (strpos($product->short_description, '&#') !== false) {
            $product->short_description = html_entity_decode($product->short_description, ENT_QUOTES, 'UTF-8');
            $updated = true;
        }
        
        if ($updated) {
            $product->save();
            echo "✅ Corrigé: {$product->name}\n";
        }
    }
    
} else {
    echo "✅ Aucun produit avec problème d'encodage trouvé.\n";
}

echo "\n🔍 Vérification finale...\n";
$remaining = Product::where('name', 'like', '%&#%')
                   ->orWhere('description', 'like', '%&#%')
                   ->orWhere('short_description', 'like', '%&#%')
                   ->count();

if ($remaining == 0) {
    echo "✅ Tous les problèmes d'encodage ont été corrigés!\n";
} else {
    echo "⚠️  Il reste {$remaining} produit(s) avec des problèmes d'encodage.\n";
}
