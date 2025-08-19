<?php

require_once 'vendor/autoload.php';

use App\Models\Category;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== RESTAURATION FINALE DES CATÉGORIES ===\n\n";

// Mapping basé sur les slugs existants
$categoryMapping = [
    'fruits' => 'Fruits',
    'legumes' => 'Légumes',
    'cereales' => 'Céréales',
    'feculents' => 'Féculents',
    'produits-laitiers' => 'Produits Laitiers',
    'outils-agricoles' => 'Outils Agricoles',
    'machines' => 'Machines',
    'equipement' => 'Équipement',
    'semences' => 'Semences',
    'engrais' => 'Engrais',
    'irrigation' => 'Irrigation',
    'protections' => 'Protections'
];

$categories = Category::all();

foreach ($categories as $category) {
    if (isset($categoryMapping[$category->slug])) {
        $newName = $categoryMapping[$category->slug];
        
        $category->update([
            'name' => $newName,
            'description' => "Catégorie des {$newName}",
            'is_active' => true
        ]);
        
        echo "✅ Catégorie '{$category->slug}' -> '{$newName}'\n";
    } else {
        echo "❌ Slug '{$category->slug}' non mappé\n";
    }
}

echo "\n=== RESTAURATION TERMINÉE ===\n";
echo "Toutes les catégories ont été restaurées selon leurs slugs existants.\n";
