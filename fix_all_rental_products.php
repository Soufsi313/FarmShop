<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔧 Correction de TOUS les produits de location...\n";
echo "=" . str_repeat("=", 50) . "\n";

// Trouver tous les produits de type 'rental'
$rentalProducts = \App\Models\Product::where('type', 'rental')
    ->whereNull('deleted_at')
    ->get(['id', 'name', 'is_rental_available', 'rental_stock']);

echo "📋 Produits de location trouvés : " . $rentalProducts->count() . "\n\n";

$toUpdate = [];
$alreadyOk = [];

foreach ($rentalProducts as $product) {
    if (!$product->is_rental_available || ($product->rental_stock ?? 0) <= 0) {
        $toUpdate[] = $product;
        echo "⚠️  À corriger - ID {$product->id}: {$product->name} | Dispo: " . 
             ($product->is_rental_available ? 'OUI' : 'NON') . 
             " | Stock: " . ($product->rental_stock ?? 0) . "\n";
    } else {
        $alreadyOk[] = $product;
        echo "✅ Déjà OK - ID {$product->id}: {$product->name}\n";
    }
}

if (count($toUpdate) > 0) {
    echo "\n🔄 Correction en cours de " . count($toUpdate) . " produit(s)...\n";
    
    // Mettre à jour tous les produits de location en une seule requête
    $updated = \DB::table('products')
        ->where('type', 'rental')
        ->whereNull('deleted_at')
        ->update([
            'is_rental_available' => 1,
            'rental_stock' => 10, // Stock par défaut
            'updated_at' => now()
        ]);
    
    echo "✅ {$updated} produit(s) de location corrigé(s)\n";
    
    echo "\n📝 Corrections appliquées :\n";
    echo "- is_rental_available = 1 (OUI)\n";
    echo "- rental_stock = 10 (stock par défaut)\n";
} else {
    echo "\n✅ Tous les produits de location sont déjà correctement configurés !\n";
}

echo "\n📊 RÉSUMÉ FINAL :\n";
echo "=" . str_repeat("=", 30) . "\n";
echo "- Produits déjà OK : " . count($alreadyOk) . "\n";
echo "- Produits corrigés : " . count($toUpdate) . "\n";
echo "- Total produits de location : " . $rentalProducts->count() . "\n";

echo "\n🎉 Correction terminée ! Tous les produits de location devraient maintenant apparaître sur /rentals\n";
