<?php
/**
 * Script d'analyse détaillée de la valeur du stock
 */

require 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Product;

// Initialisation de Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Analyse détaillée de la valeur du stock ===\n\n";

try {
    // 1. Analyser la distribution des prix et quantités
    echo "📊 Distribution des prix et quantités:\n";
    
    $products = Product::select('id', 'name', 'price', 'quantity', 'type')
                      ->orderBy('price', 'desc')
                      ->get();
    
    $totalProducts = $products->count();
    $totalValue = $products->sum(function($product) {
        return $product->price * $product->quantity;
    });
    
    echo "- Nombre total de produits: {$totalProducts}\n";
    echo "- Valeur totale calculée: " . number_format($totalValue, 2) . "€\n\n";
    
    // 2. Top 10 des produits les plus chers
    echo "💰 Top 10 des produits les plus chers:\n";
    foreach ($products->take(10) as $product) {
        $value = $product->price * $product->quantity;
        echo sprintf("- %-40s | %8.2f€ x %3d = %12.2f€ (%s)\n", 
            substr($product->name, 0, 40),
            $product->price, 
            $product->quantity, 
            $value,
            $product->type
        );
    }
    
    echo "\n";
    
    // 3. Analyser par tranches de prix
    echo "📈 Analyse par tranches de prix:\n";
    
    $priceRanges = [
        ['min' => 0, 'max' => 10, 'label' => '0-10€'],
        ['min' => 10, 'max' => 50, 'label' => '10-50€'],
        ['min' => 50, 'max' => 100, 'label' => '50-100€'],
        ['min' => 100, 'max' => 500, 'label' => '100-500€'],
        ['min' => 500, 'max' => 1000, 'label' => '500-1000€'],
        ['min' => 1000, 'max' => 5000, 'label' => '1000-5000€'],
        ['min' => 5000, 'max' => 999999, 'label' => '5000€+']
    ];
    
    foreach ($priceRanges as $range) {
        $rangeProducts = $products->filter(function($product) use ($range) {
            return $product->price >= $range['min'] && $product->price < $range['max'];
        });
        
        $count = $rangeProducts->count();
        $totalQuantity = $rangeProducts->sum('quantity');
        $totalValue = $rangeProducts->sum(function($product) {
            return $product->price * $product->quantity;
        });
        
        if ($count > 0) {
            echo sprintf("- %-12s: %3d produits, %4d unités, %12.2f€\n", 
                $range['label'], $count, $totalQuantity, $totalValue);
        }
    }
    
    echo "\n";
    
    // 4. Analyser par type de produit
    echo "🏷️ Analyse par type de produit:\n";
    
    $productsByType = $products->groupBy('type');
    
    foreach ($productsByType as $type => $typeProducts) {
        $count = $typeProducts->count();
        $totalQuantity = $typeProducts->sum('quantity');
        $totalValue = $typeProducts->sum(function($product) {
            return $product->price * $product->quantity;
        });
        $avgPrice = $typeProducts->avg('price');
        
        echo sprintf("- %-8s: %3d produits, %4d unités, %12.2f€ (prix moyen: %.2f€)\n", 
            ucfirst($type), $count, $totalQuantity, $totalValue, $avgPrice);
    }
    
    echo "\n";
    
    // 5. Identifier les produits qui contribuent le plus à la valeur
    echo "🎯 Top 15 des produits contribuant le plus à la valeur totale:\n";
    
    $productsWithValue = $products->map(function($product) {
        $product->total_value = $product->price * $product->quantity;
        return $product;
    })->sortByDesc('total_value');
    
    $top15Value = 0;
    foreach ($productsWithValue->take(15) as $index => $product) {
        $top15Value += $product->total_value;
        $percentage = ($product->total_value / $totalValue) * 100;
        
        echo sprintf("%2d. %-35s | %8.2f€ x %3d = %12.2f€ (%4.1f%%) [%s]\n", 
            $index + 1,
            substr($product->name, 0, 35),
            $product->price, 
            $product->quantity, 
            $product->total_value,
            $percentage,
            $product->type
        );
    }
    
    $top15Percentage = ($top15Value / $totalValue) * 100;
    echo "\nLes 15 premiers produits représentent " . number_format($top15Value, 2) . "€ soit " . number_format($top15Percentage, 1) . "% de la valeur totale\n\n";
    
    // 6. Vérifier s'il y a des valeurs aberrantes
    echo "⚠️ Détection de valeurs potentiellement aberrantes:\n";
    
    $highValueProducts = $productsWithValue->filter(function($product) {
        return $product->total_value > 50000; // Plus de 50k€ de valeur
    });
    
    if ($highValueProducts->count() > 0) {
        echo "Produits avec une valeur unitaire très élevée (>50k€):\n";
        foreach ($highValueProducts as $product) {
            echo sprintf("- %-40s: %8.2f€ x %3d = %12.2f€\n", 
                $product->name, $product->price, $product->quantity, $product->total_value);
        }
    } else {
        echo "Aucun produit avec une valeur unitaire excessive détecté.\n";
    }
    
    // 7. Calcul de statistiques supplémentaires
    echo "\n📋 Statistiques supplémentaires:\n";
    
    $avgPrice = $products->avg('price');
    $medianPrice = $products->pluck('price')->sort()->values();
    $medianIndex = intval($medianPrice->count() / 2);
    $median = $medianPrice->count() % 2 == 0 ? 
              ($medianPrice[$medianIndex - 1] + $medianPrice[$medianIndex]) / 2 : 
              $medianPrice[$medianIndex];
    
    $avgQuantity = $products->avg('quantity');
    $totalQuantity = $products->sum('quantity');
    
    echo "- Prix moyen: " . number_format($avgPrice, 2) . "€\n";
    echo "- Prix médian: " . number_format($median, 2) . "€\n";
    echo "- Quantité moyenne par produit: " . number_format($avgQuantity, 1) . " unités\n";
    echo "- Quantité totale en stock: " . number_format($totalQuantity) . " unités\n";
    echo "- Valeur moyenne par unité: " . number_format($totalValue / $totalQuantity, 2) . "€\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
