<?php

require_once 'vendor/autoload.php';

use App\Models\Product;
use App\Models\Category;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DIAGNOSTIC COMPLET DES PRODUITS ===\n\n";

// Compter le total
$totalProducts = Product::count();
echo "📊 TOTAL PRODUITS ACTUELS: {$totalProducts}\n\n";

// Analyser par type
echo "📋 RÉPARTITION PAR TYPE:\n";
$saleProducts = Product::where('type', 'sale')->count();
$rentalProducts = Product::where('type', 'rental')->count();
$bothProducts = Product::where('type', 'both')->count();

echo "- Vente uniquement: {$saleProducts}\n";
echo "- Location uniquement: {$rentalProducts}\n";
echo "- Mixte (vente + location): {$bothProducts}\n\n";

// Analyser par catégorie
echo "📂 RÉPARTITION PAR CATÉGORIE:\n";
$categories = Category::with('products')->get();
foreach ($categories as $category) {
    $count = $category->products->count();
    echo "- {$category->name} (ID: {$category->id}): {$count} produits\n";
}

echo "\n🔍 PRODUITS SUSPECTS (sans image ou avec des patterns suspects):\n";
$suspiciousProducts = Product::where(function($query) {
    $query->whereNull('main_image')
          ->orWhere('main_image', '')
          ->orWhere('name', 'like', '%Product%')
          ->orWhere('name', 'like', '%Test%')
          ->orWhere('description', 'like', '%generated%');
})->get(['id', 'name', 'sku', 'main_image', 'created_at']);

foreach ($suspiciousProducts as $product) {
    echo "- ID {$product->id}: '{$product->name}' (SKU: {$product->sku}) | Image: " . ($product->main_image ? 'OUI' : 'NON') . " | Créé: {$product->created_at}\n";
}

echo "\n🎯 ÉCHANTILLON DES DERNIERS PRODUITS CRÉÉS:\n";
$recentProducts = Product::orderBy('created_at', 'desc')->take(10)->get(['id', 'name', 'sku', 'created_at', 'category_id']);
foreach ($recentProducts as $product) {
    $category = Category::find($product->category_id);
    echo "- ID {$product->id}: '{$product->name}' (SKU: {$product->sku}) | Catégorie: " . ($category ? $category->name : 'AUCUNE') . " | Créé: {$product->created_at}\n";
}
