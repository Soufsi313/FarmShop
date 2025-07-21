<?php

// Initialiser Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Product;

echo "🗑️  SUPPRESSION DES PRODUITS CATÉGORIE PROTECTIONS\n";
echo "=" . str_repeat("=", 50) . "\n\n";

// Trouver la catégorie Protections
$protectionsCategory = Category::where('name', 'Protections')->first();

if (!$protectionsCategory) {
    echo "❌ Catégorie 'Protections' non trouvée\n";
    exit;
}

echo "✅ Catégorie 'Protections' trouvée (ID: {$protectionsCategory->id})\n";

// Compter les produits existants
$products = Product::where('category_id', $protectionsCategory->id)->get();
$count = $products->count();

echo "📊 Produits à supprimer: {$count}\n\n";

if ($count > 0) {
    echo "📝 Liste des produits à supprimer:\n";
    foreach ($products as $product) {
        echo "- {$product->name} (ID: {$product->id}) - {$product->price}€\n";
    }
    
    echo "\n🗑️  Suppression en cours...\n";
    
    // Supprimer tous les produits de cette catégorie
    $deleted = Product::where('category_id', $protectionsCategory->id)->delete();
    
    echo "✅ {$deleted} produits supprimés avec succès\n";
    echo "🎯 Catégorie 'Protections' vidée et prête pour les nouveaux EPI\n";
    
} else {
    echo "ℹ️  Aucun produit à supprimer\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "✅ SUPPRESSION TERMINÉE\n";
