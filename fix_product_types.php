<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔧 Correction des types de produits...\n";
echo "=" . str_repeat("=", 50) . "\n";

try {
    // Vérifier les produits actuels
    $mixedProducts = DB::table('products')
        ->where('type', 'both')
        ->whereNull('deleted_at')
        ->get(['id', 'name', 'type', 'is_rental_available']);

    echo "📋 Produits avec type 'both' (mixte) trouvés : " . $mixedProducts->count() . "\n\n";

    if ($mixedProducts->count() > 0) {
        foreach ($mixedProducts as $product) {
            echo "- ID {$product->id}: {$product->name} | Location dispo: " . 
                 ($product->is_rental_available ? 'OUI' : 'NON') . "\n";
        }
        
        echo "\n🔄 Conversion en cours...\n";
        
        // Convertir tous les produits "both" en "purchase"
        $updated = DB::table('products')
            ->where('type', 'both')
            ->whereNull('deleted_at')
            ->update([
                'type' => 'purchase',
                'updated_at' => now()
            ]);
        
        echo "✅ {$updated} produit(s) converti(s) de 'both' vers 'purchase'\n";
    } else {
        echo "ℹ️  Aucun produit 'both' trouvé\n";
    }

    // Afficher le résumé final
    echo "\n📊 RÉSUMÉ FINAL :\n";
    echo "=" . str_repeat("=", 30) . "\n";
    
    $summary = DB::table('products')
        ->whereNull('deleted_at')
        ->select('type', DB::raw('COUNT(*) as count'))
        ->groupBy('type')
        ->get();
    
    foreach ($summary as $type) {
        $typeName = match($type->type) {
            'purchase' => 'Achat uniquement',
            'rental' => 'Location uniquement', 
            'both' => 'Mixte (achat + location)',
            default => $type->type
        };
        echo "- {$typeName}: {$type->count} produit(s)\n";
    }
    
    echo "\n🎉 Correction terminée avec succès !\n";

} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    exit(1);
}
