<?php

// Initialiser Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "🔍 VÉRIFICATION DES DOUBLONS DANS TOUS LES PRODUITS\n";
echo "=" . str_repeat("=", 50) . "\n\n";

// 1. Vérifier les doublons par nom
echo "📝 1. DOUBLONS PAR NOM:\n";
$duplicatesByName = Product::select('name')
    ->groupBy('name')
    ->havingRaw('COUNT(*) > 1')
    ->get();

if ($duplicatesByName->count() > 0) {
    echo "❌ DOUBLONS TROUVÉS:\n";
    foreach ($duplicatesByName as $duplicate) {
        $products = Product::where('name', $duplicate->name)->get();
        echo "\n🔄 Nom: '{$duplicate->name}'\n";
        foreach ($products as $product) {
            echo "   - ID: {$product->id} | Catégorie: {$product->category->name} | Prix: {$product->price}€\n";
        }
    }
} else {
    echo "✅ Aucun doublon par nom trouvé\n";
}

echo "\n" . str_repeat("-", 60) . "\n";

// 2. Vérifier les doublons par SKU
echo "📝 2. DOUBLONS PAR SKU:\n";
$duplicatesBySku = Product::select('sku')
    ->groupBy('sku')
    ->havingRaw('COUNT(*) > 1')
    ->get();

if ($duplicatesBySku->count() > 0) {
    echo "❌ DOUBLONS SKU TROUVÉS:\n";
    foreach ($duplicatesBySku as $duplicate) {
        $products = Product::where('sku', $duplicate->sku)->get();
        echo "\n🔄 SKU: '{$duplicate->sku}'\n";
        foreach ($products as $product) {
            echo "   - ID: {$product->id} | Nom: {$product->name} | Prix: {$product->price}€\n";
        }
    }
} else {
    echo "✅ Aucun doublon par SKU trouvé\n";
}

echo "\n" . str_repeat("-", 60) . "\n";

// 3. Vérifier les doublons par slug
echo "📝 3. DOUBLONS PAR SLUG:\n";
$duplicatesBySlug = Product::select('slug')
    ->groupBy('slug')
    ->havingRaw('COUNT(*) > 1')
    ->get();

if ($duplicatesBySlug->count() > 0) {
    echo "❌ DOUBLONS SLUG TROUVÉS:\n";
    foreach ($duplicatesBySlug as $duplicate) {
        $products = Product::where('slug', $duplicate->slug)->get();
        echo "\n🔄 Slug: '{$duplicate->slug}'\n";
        foreach ($products as $product) {
            echo "   - ID: {$product->id} | Nom: {$product->name} | Catégorie: {$product->category->name}\n";
        }
    }
} else {
    echo "✅ Aucun doublon par slug trouvé\n";
}

echo "\n" . str_repeat("-", 60) . "\n";

// 4. Statistiques générales
echo "📊 4. STATISTIQUES GÉNÉRALES:\n";
$totalProducts = Product::count();
$totalCategories = Product::distinct('category_id')->count();
echo "📦 Total produits: {$totalProducts}\n";
echo "📂 Catégories utilisées: {$totalCategories}\n";

// Compter par catégorie
$categoryCounts = Product::with('category')
    ->select('category_id', \DB::raw('count(*) as total'))
    ->groupBy('category_id')
    ->get();

echo "\n📂 Répartition par catégorie:\n";
foreach ($categoryCounts as $count) {
    echo "   - {$count->category->name}: {$count->total} produits\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "✅ VÉRIFICATION TERMINÉE\n";
