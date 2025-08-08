<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

// Corriger le stock du produit chaussures de sécurité
$product = Product::find(344); // ID du produit trouvé précédemment

if ($product) {
    echo "=== CORRECTION DU STOCK ===\n";
    echo "Produit: {$product->name}\n";
    echo "Stock actuel: {$product->quantity}\n";
    
    // Le stock était de 48, on a ajouté 40 par erreur, donc on retire 40
    $correctStock = 48;
    
    $product->update(['quantity' => $correctStock]);
    
    echo "Stock corrigé: {$correctStock}\n";
    echo "✅ Correction appliquée\n";
    
} else {
    echo "Produit non trouvé\n";
}
