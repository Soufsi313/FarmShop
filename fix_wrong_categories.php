<?php

require_once 'vendor/autoload.php';

use App\Models\Product;
use App\Models\Category;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CORRECTION DES CATÉGORIES INCORRECTES ===\n\n";

// Récupérer les catégories nécessaires
$engraisCategory = Category::where('slug', 'engrais')->first();
$feculentsCategory = Category::where('slug', 'feculents')->first();

if (!$engraisCategory || !$feculentsCategory) {
    echo "❌ Erreur: Catégories Engrais ou Féculents non trouvées\n";
    exit;
}

echo "Catégorie Engrais: ID {$engraisCategory->id}\n";
echo "Catégorie Féculents: ID {$feculentsCategory->id}\n\n";

// Corrections à effectuer
$corrections = [
    // Produits à déplacer vers Engrais
    ['name' => 'Compost Bio', 'new_category_id' => $engraisCategory->id],
    ['name' => 'Fumier de Cheval', 'new_category_id' => $engraisCategory->id],
    ['name' => 'Engrais Naturel NPK', 'new_category_id' => $engraisCategory->id],
    
    // Produits à déplacer vers Féculents  
    ['name' => 'Pâtes Complètes Bio', 'new_category_id' => $feculentsCategory->id],
    ['name' => 'Riz Basmati Bio', 'new_category_id' => $feculentsCategory->id],
];

foreach ($corrections as $correction) {
    $product = Product::where('name', $correction['name'])->first();
    
    if ($product) {
        $oldCategory = Category::find($product->category_id);
        $newCategory = Category::find($correction['new_category_id']);
        
        $product->update(['category_id' => $correction['new_category_id']]);
        
        echo "✅ '{$product->name}' déplacé de '{$oldCategory->name}' vers '{$newCategory->name}'\n";
    } else {
        echo "❌ Produit '{$correction['name']}' non trouvé\n";
    }
}

echo "\n=== VÉRIFICATION POST-CORRECTION ===\n";

// Vérifier la catégorie Irrigation maintenant
$irrigationCategory = Category::where('slug', 'irrigation')->first();
$irrigationProducts = Product::where('category_id', $irrigationCategory->id)->get(['name']);

echo "Produits restants dans Irrigation:\n";
if ($irrigationProducts->count() > 0) {
    foreach ($irrigationProducts as $product) {
        echo "- {$product->name}\n";
    }
} else {
    echo "- Aucun produit (catégorie vide maintenant)\n";
}

echo "\n=== CORRECTION TERMINÉE ===\n";
