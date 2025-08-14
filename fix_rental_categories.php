<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Configuration de la base de données Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 Correction des catégories des produits de location...\n\n";

try {
    // Mapping des catégories de location vers les catégories principales correspondantes
    $categoryMapping = [
        'Outils agricoles' => 6,  // ID de la catégorie "Outils agricoles"
        'Machines' => 7,          // ID de la catégorie "Machines" 
        'Équipements' => 8        // ID de la catégorie "Équipement"
    ];

    // Récupérer tous les produits de location mal catégorisés
    $rentalProducts = DB::table('products as p')
        ->join('rental_categories as rc', 'p.rental_category_id', '=', 'rc.id')
        ->where('p.type', 'rental')
        ->where('p.category_id', 1) // Catégorie "Fruits" (incorrecte)
        ->select([
            'p.id',
            'p.name',
            'p.category_id',
            'rc.name as rental_category_name'
        ])
        ->get();

    echo "📊 Produits à corriger : " . $rentalProducts->count() . "\n\n";

    $correctionCount = 0;

    DB::beginTransaction();

    foreach ($rentalProducts as $product) {
        // Déterminer la bonne catégorie principale basée sur la catégorie de location
        $newCategoryId = $categoryMapping[$product->rental_category_name] ?? null;
        
        if ($newCategoryId) {
            // Mettre à jour la catégorie du produit
            $updated = DB::table('products')
                ->where('id', $product->id)
                ->update(['category_id' => $newCategoryId]);
            
            if ($updated) {
                echo sprintf(
                    "✅ Produit %d '%s' : Fruits → %s (ID: %d)\n",
                    $product->id,
                    $product->name,
                    $product->rental_category_name,
                    $newCategoryId
                );
                $correctionCount++;
            } else {
                echo sprintf(
                    "❌ Échec mise à jour produit %d '%s'\n",
                    $product->id,
                    $product->name
                );
            }
        } else {
            echo sprintf(
                "⚠️  Pas de mapping trouvé pour '%s' (produit %d)\n",
                $product->rental_category_name,
                $product->id
            );
        }
    }

    DB::commit();

    echo "\n🎉 Correction terminée !\n";
    echo "📊 Produits corrigés : $correctionCount\n\n";

    // Vérification finale
    echo "🔍 Vérification post-correction :\n";
    echo "=================================\n";
    
    $finalCheck = DB::table('products as p')
        ->join('categories as c', 'p.category_id', '=', 'c.id')
        ->join('rental_categories as rc', 'p.rental_category_id', '=', 'rc.id')
        ->where('p.type', 'rental')
        ->select([
            'p.id',
            'p.name',
            'c.name as category_name',
            'rc.name as rental_category_name'
        ])
        ->orderBy('p.id')
        ->get();

    foreach ($finalCheck as $product) {
        $status = ($product->category_name === 'Fruits') ? "❌" : "✅";
        echo sprintf(
            "%s ID: %d | %s | Cat.: %s | Cat. Location: %s\n",
            $status,
            $product->id,
            $product->name,
            $product->category_name,
            $product->rental_category_name
        );
    }

    // Vérifier s'il reste des produits de location dans "Fruits"
    $remainingInFruits = DB::table('products as p')
        ->join('categories as c', 'p.category_id', '=', 'c.id')
        ->where('p.type', 'rental')
        ->where('c.name', 'Fruits')
        ->count();

    echo "\n📊 Résumé final :\n";
    echo "================\n";
    echo "Produits de location restants dans 'Fruits' : $remainingInFruits\n";
    
    if ($remainingInFruits == 0) {
        echo "✅ Tous les produits de location sont maintenant dans les bonnes catégories !\n";
    } else {
        echo "⚠️  Il reste encore des produits de location dans la catégorie Fruits.\n";
    }

} catch (Exception $e) {
    DB::rollBack();
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n✅ Script terminé.\n";
