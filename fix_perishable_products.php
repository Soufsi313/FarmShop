<?php
// Corriger les produits non alimentaires marqués comme périssables
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CORRECTION DES PRODUITS PÉRISSABLES ===\n\n";

// Définir les catégories qui ne devraient jamais être périssables
$nonPerishableCategories = [
    'Outils',
    'Outils de jardinage', 
    'Machines',
    'Machines & Équipements',
    'Semences et fertilisants'
];

$correctedProducts = 0;

foreach ($nonPerishableCategories as $categoryName) {
    $category = \App\Models\Category::where('name', $categoryName)->first();
    
    if (!$category) {
        echo "Catégorie '{$categoryName}' non trouvée.\n";
        continue;
    }
    
    echo "Traitement de la catégorie: {$category->name}\n";
    
    // S'assurer que la catégorie elle-même n'est pas périssable
    if ($category->is_perishable) {
        $category->update(['is_perishable' => false]);
        echo "  - Catégorie corrigée: is_perishable = false\n";
    }
    
    // Corriger tous les produits de cette catégorie
    $products = $category->products()->where('is_perishable', true)->get();
    
    foreach ($products as $product) {
        echo "  - Correction du produit: {$product->name}\n";
        echo "    Avant: is_perishable = " . ($product->is_perishable ? 'OUI' : 'NON') . "\n";
        
        $product->update(['is_perishable' => false]);
        $correctedProducts++;
        
        echo "    Après: is_perishable = " . ($product->fresh()->is_perishable ? 'OUI' : 'NON') . "\n";
    }
    
    echo "  ---\n";
}

// Vérification spéciale pour les catégories alimentaires
echo "\n=== CATÉGORIES ALIMENTAIRES (DEVRAIENT ÊTRE PÉRISSABLES) ===\n";

$perishableCategories = [
    'Fruits et légumes',
    'Produits laitiers',
    'Viandes et poissons',
    'Produits frais'
];

foreach ($perishableCategories as $categoryName) {
    $category = \App\Models\Category::where('name', 'LIKE', "%{$categoryName}%")->first();
    
    if (!$category) {
        echo "Catégorie '{$categoryName}' non trouvée.\n";
        continue;
    }
    
    echo "Catégorie alimentaire: {$category->name}\n";
    echo "  - is_perishable: " . ($category->is_perishable ? 'OUI' : 'NON') . "\n";
    
    // S'assurer que la catégorie alimentaire EST périssable
    if (!$category->is_perishable) {
        $category->update(['is_perishable' => true]);
        echo "  - Catégorie corrigée: is_perishable = true\n";
    }
    
    // Corriger les produits alimentaires qui ne seraient pas périssables
    $products = $category->products()->where('is_perishable', false)->get();
    foreach ($products as $product) {
        echo "  - Correction du produit alimentaire: {$product->name}\n";
        $product->update(['is_perishable' => true]);
        $correctedProducts++;
    }
}

echo "\n✅ Correction terminée !\n";
echo "Nombre de produits corrigés: {$correctedProducts}\n";

// Vérification finale
echo "\n=== VÉRIFICATION FINALE DU BROYEUR ===\n";
$broyeur = \App\Models\Product::where('name', 'LIKE', '%broyeur%')->first();
if ($broyeur) {
    echo "Broyeur de végétaux électrique:\n";
    echo "  - is_perishable: " . ($broyeur->is_perishable ? 'OUI' : 'NON') . "\n";
    echo "  - isPerishable(): " . ($broyeur->isPerishable() ? 'OUI' : 'NON') . "\n";
    echo "  - Catégorie: {$broyeur->category->name}\n";
    echo "  - Catégorie périssable: " . ($broyeur->category->is_perishable ? 'OUI' : 'NON') . "\n";
}

echo "\nCorrection terminée !\n";
