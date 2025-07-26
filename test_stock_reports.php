<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST DES DONNÉES DE RAPPORT DE STOCK ===" . PHP_EOL . PHP_EOL;

// Simuler la méthode generateChartData() du contrôleur
$categories = \App\Models\Category::with('products')->get();

echo "📊 CATÉGORIES TROUVÉES: " . $categories->count() . PHP_EOL;

$chartData = [
    'categories' => $categories->pluck('name'),
    'stock_data' => $categories->map(function($category) {
        $products = $category->products;
        echo "Catégorie '{$category->name}': {$products->count()} produits" . PHP_EOL;
        
        return [
            'normal' => $products->filter(function($p) {
                return $p->quantity > ($p->low_stock_threshold ?? $p->critical_threshold);
            })->count(),
            'low' => $products->filter(function($p) {
                return $p->quantity > $p->critical_threshold && 
                       $p->low_stock_threshold && 
                       $p->quantity <= $p->low_stock_threshold;
            })->count(),
            'critical' => $products->filter(function($p) {
                return $p->quantity > 0 && $p->quantity <= $p->critical_threshold;
            })->count(),
            'out' => $products->where('quantity', 0)->count()
        ];
    })
];

echo PHP_EOL . "📈 DONNÉES CHART:" . PHP_EOL;
echo "Catégories: " . $chartData['categories']->implode(', ') . PHP_EOL;
echo "Stock data type: " . gettype($chartData['stock_data']) . PHP_EOL;
echo "Stock data count: " . $chartData['stock_data']->count() . PHP_EOL;

echo PHP_EOL . "🔍 TEST D'ACCÈS AUX DONNÉES:" . PHP_EOL;
try {
    $normalData = $chartData['stock_data']->pluck('normal');
    echo "Normal data: " . $normalData->implode(', ') . PHP_EOL;
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . PHP_EOL;
}

// Test du top des produits
echo PHP_EOL . "🏆 TOP PRODUITS:" . PHP_EOL;
$topProducts = \App\Models\Product::with('category')
                                 ->orderBy('views_count', 'desc')
                                 ->take(5)
                                 ->get();

foreach ($topProducts as $product) {
    $stockValue = $product->quantity * $product->price;
    echo "- {$product->name}: Stock={$product->quantity}, Prix={$product->price}€, Valeur={$stockValue}€" . PHP_EOL;
}

echo PHP_EOL . "✅ TEST TERMINÉ" . PHP_EOL;
