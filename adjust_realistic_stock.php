<?php
/**
 * Script pour ajuster les stocks de manière réaliste
 */

require 'vendor/autoload.php';

use App\Models\Product;

// Initialisation de Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Ajustement réaliste des stocks ===\n\n";

try {
    // Valeur avant ajustement
    $valueBefore = Product::selectRaw('SUM(price * quantity) as total_value')->value('total_value');
    echo "💰 Valeur avant ajustement: " . number_format($valueBefore, 2) . "€\n\n";
    
    // Ajustements recommandés
    $adjustments = [
        // Gros équipements de location -> réduire à 8-12 unités max
        ['name' => 'Tracteur Compact 25CV', 'new_quantity' => 8],
        ['name' => 'Chargeuse Compacte', 'new_quantity' => 8], 
        ['name' => 'Mini-pelle 1.5T', 'new_quantity' => 10],
        ['name' => 'Gyrobroyeur 1.8m', 'new_quantity' => 12],
        ['name' => 'Remorque Basculante 2T', 'new_quantity' => 12],
        ['name' => 'Fendeuse à Bois 12T', 'new_quantity' => 15],
        
        // Équipements de vente -> réduire le sur-stock
        ['name' => 'Tondeuse Autoportée', 'new_quantity' => 25],
        ['name' => 'Tondeuse Professionnelle', 'new_quantity' => 25],
        ['name' => 'Épandeur d\'Engrais', 'new_quantity' => 20],
    ];
    
    echo "🔧 Application des ajustements:\n";
    
    foreach ($adjustments as $adjustment) {
        $product = Product::where('name', $adjustment['name'])->first();
        
        if ($product) {
            $oldQuantity = $product->quantity;
            $oldValue = $product->price * $oldQuantity;
            $newValue = $product->price * $adjustment['new_quantity'];
            $savings = $oldValue - $newValue;
            
            echo sprintf("- %-35s: %2d → %2d unités (Économie: %s€)\n", 
                substr($product->name, 0, 35),
                $oldQuantity, 
                $adjustment['new_quantity'],
                number_format($savings, 0)
            );
            
            // Décommenter la ligne suivante pour appliquer réellement les changements
            // $product->update(['quantity' => $adjustment['new_quantity']]);
        }
    }
    
    echo "\n";
    
    // Calculer la nouvelle valeur théorique
    $totalSavings = 0;
    foreach ($adjustments as $adjustment) {
        $product = Product::where('name', $adjustment['name'])->first();
        if ($product) {
            $oldValue = $product->price * $product->quantity;
            $newValue = $product->price * $adjustment['new_quantity'];
            $totalSavings += ($oldValue - $newValue);
        }
    }
    
    $newValue = $valueBefore - $totalSavings;
    $reductionPercent = ($totalSavings / $valueBefore) * 100;
    
    echo "📊 Résumé de l'impact:\n";
    echo "- Économie totale: " . number_format($totalSavings, 2) . "€\n";
    echo "- Réduction: " . number_format($reductionPercent, 1) . "%\n";
    echo "- Nouvelle valeur: " . number_format($newValue, 2) . "€\n\n";
    
    echo "⚠️  IMPORTANT:\n";
    echo "Pour appliquer ces changements, décommentez la ligne 47 dans ce script\n";
    echo "et relancez-le. Les changements seront alors effectifs sur le dashboard.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
