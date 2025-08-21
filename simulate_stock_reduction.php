<?php
/**
 * Script de simulation de réduction de stock
 * Pour voir l'impact sur la valeur totale
 */

require 'vendor/autoload.php';

use App\Models\Product;

// Initialisation de Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Simulation de réduction de stock ===\n\n";

try {
    // Valeur actuelle
    $currentValue = Product::selectRaw('SUM(price * quantity) as total_value')->value('total_value');
    echo "💰 Valeur actuelle du stock: " . number_format($currentValue, 2) . "€\n\n";
    
    // Produits les plus impactants
    $highValueProducts = Product::selectRaw('*, (price * quantity) as total_value')
                               ->orderByRaw('(price * quantity) DESC')
                               ->take(10)
                               ->get();
    
    echo "🎯 Top 10 des produits avec le plus d'impact sur la valeur:\n";
    echo "    (Réduire leur stock aura le plus d'effet)\n\n";
    
    foreach ($highValueProducts as $index => $product) {
        $currentTotal = $product->price * $product->quantity;
        
        // Simulation de réduction de 50%
        $newQuantity = intval($product->quantity * 0.5);
        $newTotal = $product->price * $newQuantity;
        $savings = $currentTotal - $newTotal;
        
        echo sprintf("%2d. %-35s\n", $index + 1, substr($product->name, 0, 35));
        echo sprintf("    Actuel: %3d unités × %8.2f€ = %12.2f€\n", 
            $product->quantity, $product->price, $currentTotal);
        echo sprintf("    -50%%:   %3d unités × %8.2f€ = %12.2f€ (Économie: %10.2f€)\n\n", 
            $newQuantity, $product->price, $newTotal, $savings);
    }
    
    // Scénarios de réduction
    echo "📋 Scénarios de réduction de stock:\n\n";
    
    $scenarios = [
        [
            'name' => 'Réduction conservatrice',
            'description' => 'Réduire de 30% les 5 produits les plus chers',
            'products' => 5,
            'reduction' => 0.3
        ],
        [
            'name' => 'Réduction modérée', 
            'description' => 'Réduire de 50% les 5 produits les plus chers',
            'products' => 5,
            'reduction' => 0.5
        ],
        [
            'name' => 'Réduction importante',
            'description' => 'Réduire de 50% les 10 produits les plus chers',
            'products' => 10,
            'reduction' => 0.5
        ],
        [
            'name' => 'Réduction drastique',
            'description' => 'Réduire de 70% les 10 produits les plus chers',
            'products' => 10,
            'reduction' => 0.7
        ]
    ];
    
    foreach ($scenarios as $scenario) {
        $totalSavings = 0;
        $affectedProducts = $highValueProducts->take($scenario['products']);
        
        foreach ($affectedProducts as $product) {
            $currentTotal = $product->price * $product->quantity;
            $newQuantity = intval($product->quantity * (1 - $scenario['reduction']));
            $newTotal = $product->price * $newQuantity;
            $savings = $currentTotal - $newTotal;
            $totalSavings += $savings;
        }
        
        $newStockValue = $currentValue - $totalSavings;
        $reductionPercentage = ($totalSavings / $currentValue) * 100;
        
        echo "🔸 " . $scenario['name'] . "\n";
        echo "   " . $scenario['description'] . "\n";
        echo "   Économie: " . number_format($totalSavings, 2) . "€ (-" . number_format($reductionPercentage, 1) . "%)\n";
        echo "   Nouvelle valeur: " . number_format($newStockValue, 2) . "€\n\n";
    }
    
    // Recommandations spécifiques
    echo "💡 Recommandations spécifiques:\n\n";
    
    $rentalProducts = Product::where('type', 'rental')
                            ->selectRaw('*, (price * quantity) as total_value')
                            ->orderByRaw('(price * quantity) DESC')
                            ->take(5)
                            ->get();
    
    echo "🚜 Produits de location (impact majeur):\n";
    foreach ($rentalProducts as $product) {
        $currentTotal = $product->price * $product->quantity;
        
        // Suggestion: réduire à 10-15 unités max pour les gros équipements
        $suggestedQuantity = min($product->quantity, $product->price > 10000 ? 10 : 15);
        $suggestedTotal = $product->price * $suggestedQuantity;
        $savings = $currentTotal - $suggestedTotal;
        
        if ($savings > 0) {
            echo sprintf("- %-35s: %d → %d unités (Économie: %s€)\n", 
                substr($product->name, 0, 35), 
                $product->quantity, 
                $suggestedQuantity, 
                number_format($savings, 0)
            );
        }
    }
    
    echo "\n🛒 Produits de vente (impact modéré):\n";
    $saleProducts = Product::where('type', 'sale')
                          ->selectRaw('*, (price * quantity) as total_value')
                          ->orderByRaw('(price * quantity) DESC')
                          ->take(5)
                          ->get();
    
    foreach ($saleProducts as $product) {
        $currentTotal = $product->price * $product->quantity;
        
        // Suggestion: réduire de 30% les stocks de vente importants
        $suggestedQuantity = intval($product->quantity * 0.7);
        $suggestedTotal = $product->price * $suggestedQuantity;
        $savings = $currentTotal - $suggestedTotal;
        
        if ($savings > 1000) { // Seulement si économie > 1000€
            echo sprintf("- %-35s: %d → %d unités (Économie: %s€)\n", 
                substr($product->name, 0, 35), 
                $product->quantity, 
                $suggestedQuantity, 
                number_format($savings, 0)
            );
        }
    }
    
    echo "\n✅ Les modifications de stock se répercuteront automatiquement sur:\n";
    echo "   - Le dashboard de gestion de stock\n";
    echo "   - Les statistiques d'alertes\n";
    echo "   - Les calculs de valeur par catégorie\n";
    echo "   - Tous les rapports financiers\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
