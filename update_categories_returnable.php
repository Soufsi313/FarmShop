<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Charger l'environnement Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Mise à jour des catégories retournables ===\n";

try {
    // Marquer les catégories non-alimentaires comme retournables
    $nonFoodCategories = [
        'Outils agricoles',
        'Machines',
        'Équipement',
        'Semences',
        'Engrais',
        'Irrigation',
        'Protections'
    ];

    foreach ($nonFoodCategories as $categoryName) {
        $updated = DB::table('categories')
            ->where('name', $categoryName)
            ->update(['is_returnable' => true]);
        
        if ($updated > 0) {
            echo "✅ Catégorie '$categoryName' mise à jour : retournable\n";
        } else {
            echo "⚠️  Catégorie '$categoryName' non trouvée\n";
        }
    }

    echo "\n=== Vérification des catégories alimentaires (doivent rester non-retournables) ===\n";
    
    $foodCategories = [
        'Fruits',
        'Légumes',
        'Céréales',
        'Féculents',
        'Produits Laitiers'
    ];

    foreach ($foodCategories as $categoryName) {
        $category = DB::table('categories')
            ->where('name', $categoryName)
            ->first();
        
        if ($category) {
            if ($category->is_returnable) {
                echo "⚠️  Catégorie '$categoryName' est retournable (sera corrigée)\n";
                DB::table('categories')
                    ->where('name', $categoryName)
                    ->update(['is_returnable' => false]);
                echo "✅ Catégorie '$categoryName' corrigée : non-retournable\n";
            } else {
                echo "✅ Catégorie '$categoryName' : non-retournable (correct)\n";
            }
        } else {
            echo "⚠️  Catégorie '$categoryName' non trouvée\n";
        }
    }

    echo "\n=== État final des catégories ===\n";
    
    $allCategories = DB::table('categories')
        ->orderBy('type')
        ->orderBy('name')
        ->get();

    foreach ($allCategories as $category) {
        $returnableStatus = $category->is_returnable ? '✅ OUI' : '❌ NON';
        echo "- {$category->name} ({$category->type}) : {$returnableStatus}\n";
    }

    // Compter les catégories retournables
    $returnableCount = DB::table('categories')
        ->where('is_returnable', true)
        ->count();

    echo "\n📊 Résumé :\n";
    echo "- Total catégories : " . $allCategories->count() . "\n";
    echo "- Catégories retournables : $returnableCount\n";
    echo "- Catégories non-retournables : " . ($allCategories->count() - $returnableCount) . "\n";

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}

echo "\n=== Terminé ===\n";
