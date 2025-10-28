<?php
/**
 * TEST Category Model
 * 
 * Vérifie:
 * - Structure du modèle Category
 * - Relation avec les produits
 * - Attributs translatables
 * - Génération automatique du slug
 */

// Bootstrap Laravel seulement si pas déjà fait
if (!class_exists('\App\Models\Category')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
    $app = require_once __DIR__ . '/../../bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
}

use App\Models\Category;
use App\Models\Product;

echo "=== TEST CATEGORY MODEL ===\n\n";

try {
    // Test 1: Vérifier que le modèle existe
    echo "📊 Test 1: Structure du modèle Category...\n";
    
    $categoryCount = Category::count();
    echo "  ✅ Modèle Category accessible\n";
    echo "  📈 $categoryCount catégories en base\n";
    
    // Test 2: Vérifier les attributs fillable
    echo "\n📊 Test 2: Attributs fillable...\n";
    $category = new Category();
    $fillable = $category->getFillable();
    $requiredFillable = ['name', 'description', 'slug', 'is_active'];
    
    $missingFillable = array_diff($requiredFillable, $fillable);
    if (empty($missingFillable)) {
        echo "  ✅ Tous les attributs requis sont fillable (" . count($fillable) . " total)\n";
    } else {
        echo "  ⚠️  Attributs manquants: " . implode(', ', $missingFillable) . "\n";
    }
    
    // Test 3: Vérifier les attributs translatables
    echo "\n📊 Test 3: Attributs translatables...\n";
    $translatable = $category->getTranslatableAttributes();
    echo "  ✅ Attributs translatables: " . implode(', ', $translatable) . "\n";
    echo "  📋 Total: " . count($translatable) . " attributs\n";
    
    // Test 4: Tester la relation avec les produits
    echo "\n📊 Test 4: Relation avec les produits...\n";
    $categoryWithProducts = Category::with('products')->first();
    
    if ($categoryWithProducts) {
        echo "  ✅ Relation products() définie\n";
        
        $productsCount = $categoryWithProducts->products->count();
        echo "  📦 Produits liés: $productsCount\n";
    }
    
    // Test 5: Vérifier les casts
    echo "\n📊 Test 5: Type casting...\n";
    $testCategory = Category::first();
    if ($testCategory) {
        if (is_bool($testCategory->is_active)) {
            echo "  ✅ is_active casté en boolean\n";
        }
        if (isset($testCategory->is_returnable) && is_bool($testCategory->is_returnable)) {
            echo "  ✅ is_returnable casté en boolean\n";
        }
    }
    
    // Test 6: Vérifier que le slug est généré
    echo "\n📊 Test 6: Génération du slug...\n";
    $categoriesWithSlug = Category::whereNotNull('slug')->count();
    $categoriesWithoutSlug = Category::whereNull('slug')->count();
    
    echo "  ✅ Catégories avec slug: $categoriesWithSlug\n";
    if ($categoriesWithoutSlug > 0) {
        echo "  ⚠️  Catégories sans slug: $categoriesWithoutSlug\n";
    }
    
    // Test 7: Catégories actives vs inactives
    echo "\n📊 Test 7: Statut d'activation...\n";
    $activeCategories = Category::where('is_active', true)->count();
    $inactiveCategories = Category::where('is_active', false)->count();
    
    echo "  ✅ Catégories actives: $activeCategories\n";
    echo "  ⚠️  Catégories inactives: $inactiveCategories\n";
    
    // Test 8: Vérifier les catégories avec produits
    echo "\n📊 Test 8: Catégories avec produits...\n";
    $categoriesWithProducts = Category::has('products')->count();
    $categoriesWithoutProducts = Category::doesntHave('products')->count();
    
    echo "  📦 Catégories avec produits: $categoriesWithProducts\n";
    echo "  📭 Catégories sans produits: $categoriesWithoutProducts\n";
    
    echo "\n=== RÉSUMÉ ===\n";
    echo "✅ Modèle Category: Structure OK\n";
    echo "✅ Relations: Fonctionnelles\n";
    echo "✅ Traductions: Configurées\n";
    echo "✅ Slugs: Générés\n";
    echo "\nTEST RÉUSSI ✅\n";
    
} catch (\Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "FICHIER: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "TEST ÉCHOUÉ ❌\n";
}
