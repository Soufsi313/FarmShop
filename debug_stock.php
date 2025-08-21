<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Product;
use App\Models\Category;

// Initialiser Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Diagnostic du stock réel\n\n";

// Statistiques générales
$totalProducts = Product::count();
$outOfStock = Product::where('quantity', '<=', 0)->count();
$criticalStock = Product::whereColumn('quantity', '<=', 'critical_threshold')
                        ->where('quantity', '>', 0)
                        ->count();
$lowStock = Product::whereRaw('quantity <= low_stock_threshold AND quantity > critical_threshold')
                   ->count();

echo "📊 Vue d'ensemble du stock:\n";
echo "   - Total produits: $totalProducts\n";
echo "   - Rupture de stock: $outOfStock\n";
echo "   - Stock critique: $criticalStock\n";
echo "   - Stock bas: $lowStock\n";

// Valeur totale du stock
$totalStockValue = Product::selectRaw('SUM(quantity * price) as total_value')
                         ->value('total_value') ?? 0;
echo "   - Valeur totale du stock: " . number_format($totalStockValue, 2) . "€\n\n";

// Produits par catégorie
echo "📦 Stock par catégorie:\n";
$categories = Category::with('products')->get();
foreach ($categories as $category) {
    $products = $category->products;
    $totalProducts = $products->count();
    $totalValue = $products->sum(function($p) {
        return $p->quantity * $p->price;
    });
    
    echo "   - {$category->name}: $totalProducts produits, " . number_format($totalValue, 2) . "€\n";
}

// Top 10 des produits les plus/moins en stock
echo "\n📈 Top 10 - Plus haut stock:\n";
$topStock = Product::orderBy('quantity', 'desc')->take(10)->get();
foreach ($topStock as $product) {
    echo "   - {$product->name}: {$product->quantity} unités\n";
}

echo "\n⚠️ Top 10 - Stock le plus bas (non nul):\n";
$lowStockProducts = Product::where('quantity', '>', 0)
                           ->orderBy('quantity', 'asc')
                           ->take(10)
                           ->get();
foreach ($lowStockProducts as $product) {
    echo "   - {$product->name}: {$product->quantity} unités\n";
}

// Produits en rupture
echo "\n❌ Produits en rupture de stock:\n";
$outOfStockProducts = Product::where('quantity', '<=', 0)->get();
if ($outOfStockProducts->count() > 0) {
    foreach ($outOfStockProducts as $product) {
        echo "   - {$product->name}: {$product->quantity} unités\n";
    }
} else {
    echo "   ✅ Aucun produit en rupture de stock!\n";
}

// Configuration des seuils
echo "\n⚙️ Configuration des seuils:\n";
$avgCritical = Product::avg('critical_threshold');
$avgLow = Product::avg('low_stock_threshold');
echo "   - Seuil critique moyen: " . number_format($avgCritical, 1) . "\n";
echo "   - Seuil stock bas moyen: " . number_format($avgLow, 1) . "\n";
