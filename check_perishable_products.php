<?php
// Vérifier les produits périssables et leurs catégories
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DIAGNOSTIC DES PRODUITS PÉRISSABLES ===\n\n";

// Chercher le broyeur de végétaux
$broyeur = \App\Models\Product::where('name', 'LIKE', '%broyeur%')->first();

if ($broyeur) {
    echo "Produit trouvé : {$broyeur->name}\n";
    echo "  - ID: {$broyeur->id}\n";
    echo "  - Catégorie ID: {$broyeur->category_id}\n";
    echo "  - Catégorie: " . ($broyeur->category ? $broyeur->category->name : 'Non définie') . "\n";
    echo "  - is_perishable (champ direct): " . ($broyeur->is_perishable ? 'OUI' : 'NON') . "\n";
    echo "  - isPerishable() (méthode): " . ($broyeur->isPerishable() ? 'OUI' : 'NON') . "\n";
    echo "  - Prix: {$broyeur->price}€\n";
    echo "  - Stock: {$broyeur->quantity}\n";
    
    if ($broyeur->category) {
        echo "\nDétails de la catégorie:\n";
        echo "  - Nom: {$broyeur->category->name}\n";
        echo "  - is_perishable: " . ($broyeur->category->is_perishable ? 'OUI' : 'NON') . "\n";
    }
} else {
    echo "Produit 'broyeur' non trouvé. Recherche de tous les produits contenant 'végét'...\n";
    $products = \App\Models\Product::where('name', 'LIKE', '%végét%')->get();
    foreach ($products as $product) {
        echo "  - {$product->name} (ID: {$product->id})\n";
    }
}

// Vérifier toutes les catégories et leur statut périssable
echo "\n=== TOUTES LES CATÉGORIES ===\n";
$categories = \App\Models\Category::all();
foreach ($categories as $category) {
    echo "Catégorie: {$category->name}\n";
    echo "  - ID: {$category->id}\n";
    echo "  - is_perishable: " . ($category->is_perishable ? 'OUI' : 'NON') . "\n";
    echo "  - Nombre de produits: " . $category->products()->count() . "\n";
    echo "  ---\n";
}

// Vérifier spécifiquement la catégorie "Outils de jardinage"
$outilsCategory = \App\Models\Category::where('name', 'LIKE', '%outils%')->orWhere('name', 'LIKE', '%jardinage%')->first();
if ($outilsCategory) {
    echo "\n=== CATÉGORIE OUTILS DE JARDINAGE ===\n";
    echo "Nom: {$outilsCategory->name}\n";
    echo "is_perishable: " . ($outilsCategory->is_perishable ? 'OUI' : 'NON') . "\n";
    echo "Produits dans cette catégorie:\n";
    foreach ($outilsCategory->products as $product) {
        echo "  - {$product->name} : périssable = " . ($product->isPerishable() ? 'OUI' : 'NON') . "\n";
    }
}

echo "\nDiagnostic terminé.\n";
