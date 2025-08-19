<?php

require_once 'vendor/autoload.php';

use App\Models\Category;
use App\Models\RentalCategory;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== RESTAURATION COMPLÈTE DU SYSTÈME ===\n\n";

// ÉTAPE 1: Restaurer les noms des catégories
echo "ÉTAPE 1: Restauration des catégories\n";

$categoryNames = [
    1 => 'Fruits',
    2 => 'Légumes', 
    3 => 'Produits Laitiers',
    4 => 'Produits Non-Alimentaires',
    5 => 'Céréales',
    6 => 'Graines',
    7 => 'Engrais',
    8 => 'Féculents',
    9 => 'Huiles et Vinaigres',
    10 => 'Épices et Aromates',
    11 => 'Boissons',
    12 => 'Conserves'
];

foreach ($categoryNames as $id => $name) {
    $category = Category::find($id);
    if ($category) {
        $category->update([
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'description' => "Catégorie des {$name}",
            'is_active' => true
        ]);
        echo "✅ Catégorie ID {$id} -> '{$name}'\n";
    } else {
        echo "❌ Catégorie ID {$id} non trouvée\n";
    }
}

// ÉTAPE 2: Restaurer les catégories de location
echo "\nÉTAPE 2: Restauration des catégories de location\n";

$rentalCategoryNames = [
    1 => 'Outils de jardinage',
    2 => 'Équipements motorisés', 
    3 => 'Protection et structures'
];

foreach ($rentalCategoryNames as $id => $name) {
    $rentalCategory = RentalCategory::find($id);
    if ($rentalCategory) {
        $rentalCategory->update([
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'description' => "Catégorie de location: {$name}",
            'is_active' => true
        ]);
        echo "✅ Catégorie Location ID {$id} -> '{$name}'\n";
    } else {
        echo "❌ Catégorie Location ID {$id} non trouvée\n";
    }
}

echo "\n=== RESTAURATION TERMINÉE ===\n";
echo "Les catégories ont été restaurées. Le dashboard devrait maintenant fonctionner.\n";
echo "Il reste à restaurer les traductions des templates manuellement.\n";
