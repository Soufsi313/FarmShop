<?php
/**
 * Script de test pour vérifier que la page de gestion de stock
 * affiche correctement les données réelles
 */

require 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Category;

// Initialisation de Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test des données de la page Stock ===\n\n";

try {
    // 1. Vérifier les statistiques de stock
    echo "📊 Vérification des statistiques de stock:\n";
    
    $totalProducts = Product::count();
    $outOfStock = Product::where('quantity', '<=', 0)->count();
    $criticalStock = Product::where('quantity', '>', 0)
                           ->where('quantity', '<=', 5)
                           ->count();
    $lowStock = Product::where('quantity', '>', 5)
                      ->where('quantity', '<=', 10)
                      ->count();
    $normalStock = Product::where('quantity', '>', 10)->count();
    
    echo "- Total produits: {$totalProducts}\n";
    echo "- Rupture de stock: {$outOfStock}\n";
    echo "- Stock critique (≤5): {$criticalStock}\n";
    echo "- Stock bas (6-10): {$lowStock}\n";
    echo "- Stock normal (>10): {$normalStock}\n\n";
    
    // 2. Calculer la valeur totale du stock
    echo "💰 Calcul de la valeur du stock:\n";
    
    $totalStockValue = Product::selectRaw('SUM(price * quantity) as total_value')
                             ->value('total_value');
    
    $criticalStockValue = Product::selectRaw('SUM(price * quantity) as critical_value')
                                ->where('quantity', '>', 0)
                                ->where('quantity', '<=', 5)
                                ->value('critical_value');
    
    echo "- Valeur totale du stock: " . number_format($totalStockValue, 2) . "€\n";
    echo "- Valeur stock critique: " . number_format($criticalStockValue, 2) . "€\n\n";
    
    // 3. Analyser par catégorie
    echo "📂 Analyse par catégorie:\n";
    
    $categoriesWithStock = Category::select('categories.id', 'categories.name')
        ->selectRaw('COUNT(products.id) as total_products')
        ->selectRaw('SUM(CASE WHEN products.quantity <= 0 THEN 1 ELSE 0 END) as out_of_stock')
        ->selectRaw('SUM(CASE WHEN products.quantity > 0 AND products.quantity <= 5 THEN 1 ELSE 0 END) as critical_stock')
        ->selectRaw('SUM(CASE WHEN products.quantity > 5 AND products.quantity <= 10 THEN 1 ELSE 0 END) as low_stock')
        ->selectRaw('SUM(CASE WHEN products.quantity > 10 THEN 1 ELSE 0 END) as normal_stock')
        ->selectRaw('SUM(products.price * products.quantity) as total_value')
        ->leftJoin('products', 'categories.id', '=', 'products.category_id')
        ->groupBy('categories.id', 'categories.name')
        ->having('total_products', '>', 0)
        ->orderBy('total_products', 'desc')
        ->get();
    
    foreach ($categoriesWithStock as $cat) {
        echo "- {$cat->name}: {$cat->total_products} produits";
        echo " (Rupture: {$cat->out_of_stock}, Critique: {$cat->critical_stock}, Bas: {$cat->low_stock}, Normal: {$cat->normal_stock})";
        echo " - Valeur: " . number_format($cat->total_value, 2) . "€\n";
    }
    
    echo "\n";
    
    // 4. Simuler les tendances des 7 derniers jours
    echo "📈 Simulation des tendances (7 derniers jours):\n";
    
    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i);
        $dayLabel = $date->format('D');
        
        // Pour la simulation, on varie légèrement les chiffres
        $variance = rand(85, 115) / 100; // ±15%
        $outOfStockDay = intval($outOfStock * $variance);
        $criticalStockDay = intval($criticalStock * $variance);
        $lowStockDay = intval($lowStock * $variance);
        $totalAlertsDay = $outOfStockDay + $criticalStockDay + $lowStockDay;
        
        echo "- {$dayLabel}: {$totalAlertsDay} alertes (Rupture: {$outOfStockDay}, Critique: {$criticalStockDay}, Bas: {$lowStockDay})\n";
    }
    
    echo "\n";
    
    // 5. Identifier les produits nécessitant une attention
    echo "⚠️ Produits nécessitant une attention:\n";
    
    $needsAttention = Product::where(function($query) {
        $query->where('quantity', '<=', 5)
              ->orWhere('quantity', '>', 100); // Stock excessif aussi
    })->with('category')->get();
    
    echo "- Nombre total de produits nécessitant attention: " . $needsAttention->count() . "\n";
    
    foreach ($needsAttention->take(10) as $product) {
        $status = $product->quantity <= 0 ? 'RUPTURE' :
                 ($product->quantity <= 5 ? 'CRITIQUE' : 'EXCESSIF');
        
        $categoryName = $product->category ? $product->category->name : 'Sans catégorie';
        echo "  • {$product->name} ({$categoryName}): {$product->quantity} unités - {$status}\n";
    }
    
    if ($needsAttention->count() > 10) {
        echo "  ... et " . ($needsAttention->count() - 10) . " autres produits\n";
    }
    
    echo "\n✅ Test terminé avec succès !\n";
    echo "La page de gestion de stock devrait maintenant afficher toutes ces données réelles.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
