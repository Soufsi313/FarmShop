<?php

require_once 'vendor/autoload.php';

use App\Models\Product;
use App\Models\Category;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CORRECTION PAR ID DES CATÉGORIES INCORRECTES ===\n\n";

// Récupérer les catégories nécessaires
$engraisCategory = Category::where('slug', 'engrais')->first();
$feculentsCategory = Category::where('slug', 'feculents')->first();

echo "Catégorie Engrais: ID {$engraisCategory->id}\n";
echo "Catégorie Féculents: ID {$feculentsCategory->id}\n\n";

// Corrections par ID de produit (basé sur la vérification précédente)
$corrections = [
    // Produits à déplacer vers Engrais (ID: 10)
    71 => ['name' => 'Compost Bio', 'new_category_id' => 10],
    72 => ['name' => 'Fumier de Cheval', 'new_category_id' => 10],
    73 => ['name' => 'Engrais Naturel NPK', 'new_category_id' => 10],
    
    // Produits à déplacer vers Féculents (ID: 4)
    74 => ['name' => 'Pâtes Complètes Bio', 'new_category_id' => 4],
    75 => ['name' => 'Riz Basmati Bio', 'new_category_id' => 4],
];

foreach ($corrections as $productId => $correction) {
    $product = Product::find($productId);
    
    if ($product) {
        $oldCategory = Category::find($product->category_id);
        $newCategory = Category::find($correction['new_category_id']);
        
        $product->update(['category_id' => $correction['new_category_id']]);
        
        echo "✅ ID {$productId}: '{$product->name}' déplacé de '{$oldCategory->name}' vers '{$newCategory->name}'\n";
    } else {
        echo "❌ Produit ID {$productId} non trouvé\n";
    }
}

echo "\n=== VÉRIFICATION POST-CORRECTION ===\n";

// Vérifier la catégorie Irrigation maintenant
$irrigationCategory = Category::where('slug', 'irrigation')->first();
$irrigationProducts = Product::where('category_id', $irrigationCategory->id)->get(['id', 'name']);

echo "Produits restants dans Irrigation (ID: {$irrigationCategory->id}):\n";
if ($irrigationProducts->count() > 0) {
    foreach ($irrigationProducts as $product) {
        echo "- ID {$product->id}: {$product->name}\n";
    }
} else {
    echo "- Aucun produit (catégorie vide maintenant)\n";
}

echo "\n=== CORRECTION TERMINÉE ===\n";
