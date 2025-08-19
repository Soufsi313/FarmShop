<?php

require_once 'vendor/autoload.php';

use App\Models\Category;
use App\Models\RentalCategory;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== RESTAURATION SÉCURISÉE DES CATÉGORIES ===\n\n";

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
        // Générer un slug unique
        $baseSlug = \Illuminate\Support\Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;
        
        // Vérifier si le slug existe déjà
        while (Category::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $category->update([
            'name' => $name,
            'slug' => $slug,
            'description' => "Catégorie des {$name}",
            'is_active' => true
        ]);
        echo "✅ Catégorie ID {$id} -> '{$name}' (slug: {$slug})\n";
    } else {
        echo "❌ Catégorie ID {$id} non trouvée\n";
    }
}

echo "\n=== RESTAURATION TERMINÉE ===\n";
echo "Les catégories ont été restaurées avec des slugs uniques.\n";
