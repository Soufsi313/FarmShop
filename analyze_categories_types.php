<?php

// Initialiser Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Product;

echo "🔍 ANALYSE DES CATÉGORIES ET TYPES DE PRODUITS\n";
echo "=" . str_repeat("=", 50) . "\n\n";

$categories = Category::with('products')->get();

foreach ($categories as $category) {
    echo "📂 {$category->name} (ID: {$category->id})\n";
    
    $products = $category->products;
    $totalProducts = $products->count();
    
    if ($totalProducts > 0) {
        $saleCount = $products->where('type', 'sale')->count();
        $rentalCount = $products->where('type', 'rental')->count();
        $bothCount = $products->where('type', 'both')->count();
        
        echo "   📊 Total: {$totalProducts} produits\n";
        echo "   💰 Vente seulement: {$saleCount}\n";
        echo "   🏠 Location seulement: {$rentalCount}\n";
        echo "   🔄 Les deux: {$bothCount}\n";
        
        // Déterminer si cette catégorie devrait apparaître dans les filtres
        $hasForSale = ($saleCount + $bothCount) > 0;
        $onlyRental = ($rentalCount > 0 && $saleCount == 0 && $bothCount == 0);
        
        if ($onlyRental) {
            echo "   ❌ CATÉGORIE À EXCLURE (location uniquement)\n";
        } elseif ($hasForSale) {
            echo "   ✅ CATÉGORIE À GARDER (contient des produits de vente)\n";
        }
    } else {
        echo "   📝 Aucun produit\n";
    }
    echo "\n";
}
