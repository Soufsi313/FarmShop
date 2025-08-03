<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔧 Correction du produit Abreuvoir mobile 1000L...\n";

$product = \App\Models\Product::where('slug', 'location-abreuvoir-mobile-1000l')->first();

if ($product) {
    echo "📋 État AVANT correction :\n";
    echo "- Type: {$product->type}\n";
    echo "- Is rental available: " . ($product->is_rental_available ? 'OUI' : 'NON') . "\n";
    echo "- Rental stock: " . ($product->rental_stock ?? 'NULL') . "\n";
    echo "- isRentable(): " . ($product->isRentable() ? 'OUI' : 'NON') . "\n";
    
    // Correction
    $product->update([
        'is_rental_available' => 1,
        'rental_stock' => 5
    ]);
    
    // Recharger le produit
    $product->refresh();
    
    echo "\n✅ APRÈS correction :\n";
    echo "- Type: {$product->type}\n";
    echo "- Is rental available: " . ($product->is_rental_available ? 'OUI' : 'NON') . "\n";
    echo "- Rental stock: " . ($product->rental_stock ?? 'NULL') . "\n";
    echo "- isRentable(): " . ($product->isRentable() ? 'OUI' : 'NON') . "\n";
    
    echo "\n🎉 Produit corrigé avec succès !\n";
} else {
    echo "❌ Produit non trouvé\n";
}
