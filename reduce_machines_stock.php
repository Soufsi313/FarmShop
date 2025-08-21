<?php
/**
 * Script pour diminuer de 20 unités tous les stocks de la catégorie "Machines"
 */

require 'vendor/autoload.php';

use App\Models\Product;
use App\Models\Category;

// Initialisation de Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Réduction de 20 unités pour la catégorie Machines ===\n\n";

try {
    // Trouver la catégorie "Machines" (recherche insensible à la casse)
    $machinesCategory = Category::where('name', 'LIKE', '%Machine%')->first();
    
    if (!$machinesCategory) {
        echo "❌ Catégorie contenant 'Machine' non trouvée.\n";
        echo "Catégories disponibles:\n";
        $categories = Category::all();
        foreach ($categories as $cat) {
            echo "- '{$cat->name}' (ID: {$cat->id})\n";
        }
        exit;
    }
    
    echo "🔍 Catégorie trouvée: {$machinesCategory->name} (ID: {$machinesCategory->id})\n\n";
    
    // Récupérer tous les produits de la catégorie Machines
    $machineProducts = Product::where('category_id', $machinesCategory->id)->get();
    
    echo "📊 Produits dans la catégorie Machines: {$machineProducts->count()}\n\n";
    
    // Calculer la valeur avant modification
    $valueBefore = $machineProducts->sum(function($product) {
        return $product->price * $product->quantity;
    });
    
    echo "💰 Valeur actuelle de la catégorie Machines: " . number_format($valueBefore, 2) . "€\n\n";
    
    // Afficher l'état actuel et les modifications prévues
    echo "🔧 Modifications prévues:\n";
    echo str_repeat("-", 90) . "\n";
    echo sprintf("%-35s | %8s | %8s | %12s | %12s\n", "Produit", "Stock", "Nouveau", "Économie", "Type");
    echo str_repeat("-", 90) . "\n";
    
    $totalSavings = 0;
    $modifiedProducts = [];
    
    foreach ($machineProducts as $product) {
        $oldQuantity = $product->quantity;
        $newQuantity = max(0, $oldQuantity - 20); // Ne pas descendre en dessous de 0
        $reduction = $oldQuantity - $newQuantity;
        $savings = $product->price * $reduction;
        $totalSavings += $savings;
        
        $modifiedProducts[] = [
            'product' => $product,
            'old_quantity' => $oldQuantity,
            'new_quantity' => $newQuantity,
            'reduction' => $reduction,
            'savings' => $savings
        ];
        
        echo sprintf("%-35s | %8d | %8d | %10.2f€ | %8s\n", 
            substr($product->name, 0, 35),
            $oldQuantity,
            $newQuantity,
            $savings,
            $product->type
        );
    }
    
    echo str_repeat("-", 90) . "\n";
    echo sprintf("%-35s | %8s | %8s | %10.2f€ | %8s\n", "TOTAL", "", "", $totalSavings, "");
    echo str_repeat("-", 90) . "\n\n";
    
    $newCategoryValue = $valueBefore - $totalSavings;
    $reductionPercent = ($totalSavings / $valueBefore) * 100;
    
    echo "📊 Résumé de l'impact:\n";
    echo "- Économie totale sur la catégorie: " . number_format($totalSavings, 2) . "€\n";
    echo "- Réduction: " . number_format($reductionPercent, 1) . "%\n";
    echo "- Nouvelle valeur de la catégorie: " . number_format($newCategoryValue, 2) . "€\n\n";
    
    // Calculer l'impact sur la valeur totale du stock
    $totalStockValue = Product::selectRaw('SUM(price * quantity) as total_value')->value('total_value');
    $newTotalStockValue = $totalStockValue - $totalSavings;
    $totalReductionPercent = ($totalSavings / $totalStockValue) * 100;
    
    echo "🌐 Impact sur la valeur totale du stock:\n";
    echo "- Valeur totale actuelle: " . number_format($totalStockValue, 2) . "€\n";
    echo "- Nouvelle valeur totale: " . number_format($newTotalStockValue, 2) . "€\n";
    echo "- Réduction globale: " . number_format($totalReductionPercent, 1) . "%\n\n";
    
    // Demander confirmation
    echo "⚠️  Voulez-vous appliquer ces modifications ? (y/N): ";
    $handle = fopen("php://stdin", "r");
    $confirmation = trim(fgets($handle));
    fclose($handle);
    
    if (strtolower($confirmation) === 'y' || strtolower($confirmation) === 'yes') {
        echo "\n🔄 Application des modifications...\n";
        
        $successCount = 0;
        foreach ($modifiedProducts as $modification) {
            try {
                $modification['product']->update(['quantity' => $modification['new_quantity']]);
                $successCount++;
                echo "✅ {$modification['product']->name}: {$modification['old_quantity']} → {$modification['new_quantity']}\n";
            } catch (Exception $e) {
                echo "❌ Erreur pour {$modification['product']->name}: " . $e->getMessage() . "\n";
            }
        }
        
        echo "\n🎉 Modifications terminées !\n";
        echo "- {$successCount}/{$machineProducts->count()} produits modifiés avec succès\n";
        echo "- Économie réalisée: " . number_format($totalSavings, 2) . "€\n";
        echo "\n✅ Le dashboard de stock affichera maintenant les nouvelles valeurs.\n";
        
    } else {
        echo "\n❌ Modifications annulées. Aucune modification n'a été appliquée.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
