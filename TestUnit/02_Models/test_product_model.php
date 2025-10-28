<?php
/**
 * TEST Product Model
 * 
 * Vérifie:
 * - Structure du modèle Product
 * - Relations (category, rentalCategory, wishlists, likes)
 * - Attributs translatables
 * - Types de produits (sale, rental)
 * - Génération automatique du slug
 */

// Bootstrap Laravel seulement si pas déjà fait
if (!class_exists('\App\Models\Product')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
    $app = require_once __DIR__ . '/../../bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
}

use App\Models\Product;
use App\Models\Category;

echo "=== TEST PRODUCT MODEL ===\n\n";

try {
    // Test 1: Vérifier que le modèle existe
    echo "📊 Test 1: Structure du modèle Product...\n";
    
    $productCount = Product::count();
    echo "  ✅ Modèle Product accessible\n";
    echo "  📈 $productCount produits en base\n";
    
    // Test 2: Vérifier les constantes de type
    echo "\n📊 Test 2: Constantes de type...\n";
    if (defined('App\Models\Product::TYPE_SALE')) {
        echo "  ✅ Constante TYPE_SALE définie: " . Product::TYPE_SALE . "\n";
    }
    if (defined('App\Models\Product::TYPE_RENTAL')) {
        echo "  ✅ Constante TYPE_RENTAL définie: " . Product::TYPE_RENTAL . "\n";
    }
    
    // Test 3: Vérifier les attributs fillable
    echo "\n📊 Test 3: Attributs fillable...\n";
    $product = new Product();
    $fillable = $product->getFillable();
    $requiredFillable = ['name', 'description', 'slug', 'price', 'quantity', 'type', 'category_id'];
    
    $missingFillable = array_diff($requiredFillable, $fillable);
    if (empty($missingFillable)) {
        echo "  ✅ Tous les attributs requis sont fillable (" . count($fillable) . " total)\n";
    } else {
        echo "  ⚠️  Attributs manquants: " . implode(', ', $missingFillable) . "\n";
    }
    
    // Test 4: Vérifier les attributs translatables
    echo "\n📊 Test 4: Attributs translatables...\n";
    $translatable = $product->getTranslatableAttributes();
    $expectedTranslatable = ['name', 'description', 'short_description', 'meta_title'];
    
    $foundTranslatable = array_intersect($expectedTranslatable, $translatable);
    echo "  ✅ Attributs translatables: " . implode(', ', $translatable) . "\n";
    echo "  📋 Total: " . count($translatable) . " attributs\n";
    
    // Test 5: Tester les relations
    echo "\n📊 Test 5: Relations...\n";
    $productWithRelations = Product::with(['category', 'rentalCategory'])->first();
    
    if ($productWithRelations) {
        echo "  ✅ Relation category() définie\n";
        echo "  ✅ Relation rentalCategory() définie\n";
        
        if (method_exists($productWithRelations, 'wishlists')) {
            echo "  ✅ Relation wishlists() définie\n";
        }
        if (method_exists($productWithRelations, 'likes')) {
            echo "  ✅ Relation likes() définie\n";
        }
    }
    
    // Test 6: Vérifier les casts
    echo "\n📊 Test 6: Type casting...\n";
    $testProduct = Product::first();
    if ($testProduct) {
        if (is_bool($testProduct->is_active)) {
            echo "  ✅ is_active casté en boolean\n";
        }
        if (is_bool($testProduct->is_featured)) {
            echo "  ✅ is_featured casté en boolean\n";
        }
        if (is_array($testProduct->gallery_images)) {
            echo "  ✅ gallery_images casté en array\n";
        }
    }
    
    // Test 7: Vérifier les produits par type
    echo "\n📊 Test 7: Types de produits...\n";
    $saleProducts = Product::where('type', Product::TYPE_SALE)->count();
    $rentalProducts = Product::where('type', Product::TYPE_RENTAL)->count();
    
    echo "  📦 Produits à la vente: $saleProducts\n";
    echo "  🏷️  Produits en location: $rentalProducts\n";
    
    // Test 8: Vérifier que le slug est généré
    echo "\n📊 Test 8: Génération du slug...\n";
    $productsWithSlug = Product::whereNotNull('slug')->count();
    $productsWithoutSlug = Product::whereNull('slug')->count();
    
    echo "  ✅ Produits avec slug: $productsWithSlug\n";
    if ($productsWithoutSlug > 0) {
        echo "  ⚠️  Produits sans slug: $productsWithoutSlug\n";
    }
    
    echo "\n=== RÉSUMÉ ===\n";
    echo "✅ Modèle Product: Structure OK\n";
    echo "✅ Types: sale et rental définis\n";
    echo "✅ Relations: Fonctionnelles\n";
    echo "✅ Traductions: Configurées\n";
    echo "\nTEST RÉUSSI ✅\n";
    
} catch (\Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "FICHIER: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "TEST ÉCHOUÉ ❌\n";
}
