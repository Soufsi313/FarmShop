<?php

// Initialiser Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "🧹 NETTOYAGE DES DOUBLONS\n";
echo "=" . str_repeat("=", 30) . "\n\n";

// Trouver tous les doublons par nom
$duplicatesByName = Product::select('name')
    ->groupBy('name')
    ->havingRaw('COUNT(*) > 1')
    ->get();

$totalDeleted = 0;

if ($duplicatesByName->count() > 0) {
    echo "🔍 Doublons trouvés: {$duplicatesByName->count()}\n\n";
    
    foreach ($duplicatesByName as $duplicate) {
        $products = Product::where('name', $duplicate->name)->orderBy('id')->get();
        
        echo "📝 Traitement: '{$duplicate->name}'\n";
        echo "   Produits trouvés: {$products->count()}\n";
        
        // Garder le premier, supprimer les autres
        $kept = $products->first();
        $toDelete = $products->slice(1);
        
        echo "   ✅ Garde: ID {$kept->id}\n";
        
        foreach ($toDelete as $product) {
            echo "   ❌ Supprime: ID {$product->id}\n";
            $product->delete();
            $totalDeleted++;
        }
        echo "\n";
    }
    
    echo "🎉 RÉSUMÉ:\n";
    echo "✅ {$totalDeleted} doublons supprimés\n";
    echo "📦 Base de données nettoyée\n";
    
} else {
    echo "✅ Aucun doublon trouvé\n";
}

// Vérification finale
$finalCount = Product::count();
echo "\n📊 Nombre total de produits après nettoyage: {$finalCount}\n";
