<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Configuration de la base de données Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Analyse des produits de location mal catégorisés...\n\n";

try {
    // 1. Chercher les produits de type 'rental' ou 'both' qui sont dans la catégorie 'fruits'
    $problematicProducts = DB::table('products as p')
        ->join('categories as c', 'p.category_id', '=', 'c.id')
        ->leftJoin('rental_categories as rc', 'p.rental_category_id', '=', 'rc.id')
        ->where('p.type', 'IN', ['rental', 'both'])
        ->where('c.name', 'LIKE', '%fruit%')
        ->select([
            'p.id',
            'p.name',
            'p.type',
            'p.category_id',
            'c.name as category_name',
            'p.rental_category_id',
            'rc.name as rental_category_name'
        ])
        ->get();

    echo "📊 Produits de location dans la catégorie fruits :\n";
    echo "================================================\n";
    
    if ($problematicProducts->isEmpty()) {
        echo "✅ Aucun produit de location trouvé dans la catégorie fruits.\n\n";
    } else {
        foreach ($problematicProducts as $product) {
            echo sprintf(
                "ID: %d | Nom: %s | Type: %s | Catégorie: %s | Catégorie location: %s\n",
                $product->id,
                $product->name,
                $product->type,
                $product->category_name,
                $product->rental_category_name ?? 'Aucune'
            );
        }
        echo "\n";
    }

    // 2. Lister toutes les catégories disponibles
    echo "📋 Catégories disponibles :\n";
    echo "==========================\n";
    $categories = DB::table('categories')
        ->where('is_active', true)
        ->select(['id', 'name', 'slug'])
        ->get();
    
    foreach ($categories as $category) {
        echo sprintf("ID: %d | Nom: %s | Slug: %s\n", $category->id, $category->name, $category->slug);
    }
    echo "\n";

    // 3. Lister toutes les catégories de location disponibles
    echo "🏠 Catégories de location disponibles :\n";
    echo "=======================================\n";
    $rentalCategories = DB::table('rental_categories')
        ->where('is_active', true)
        ->select(['id', 'name', 'slug'])
        ->get();
    
    foreach ($rentalCategories as $rentalCategory) {
        echo sprintf("ID: %d | Nom: %s | Slug: %s\n", $rentalCategory->id, $rentalCategory->name, $rentalCategory->slug);
    }
    echo "\n";

    // 4. Chercher tous les produits de type 'rental' ou 'both'
    echo "🔍 Tous les produits de type location :\n";
    echo "======================================\n";
    $rentalProducts = DB::table('products as p')
        ->join('categories as c', 'p.category_id', '=', 'c.id')
        ->leftJoin('rental_categories as rc', 'p.rental_category_id', '=', 'rc.id')
        ->whereIn('p.type', ['rental', 'both'])
        ->select([
            'p.id',
            'p.name',
            'p.type',
            'p.category_id',
            'c.name as category_name',
            'p.rental_category_id',
            'rc.name as rental_category_name'
        ])
        ->get();

    foreach ($rentalProducts as $product) {
        $status = ($product->rental_category_name === null) ? "❌ Pas de catégorie location" : "✅ OK";
        echo sprintf(
            "%s | ID: %d | %s | Type: %s | Cat.: %s | Cat. Location: %s\n",
            $status,
            $product->id,
            $product->name,
            $product->type,
            $product->category_name,
            $product->rental_category_name ?? 'Aucune'
        );
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n✅ Analyse terminée.\n";
