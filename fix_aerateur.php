<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "=== CORRECTION AÉRATEUR LOCATION ===\n";

$aerateur = Product::find(214);
if ($aerateur) {
    echo "Avant correction :\n";
    echo "- Disponible: " . ($aerateur->is_rental_available ? 'OUI' : 'NON') . "\n";
    echo "- Stock: {$aerateur->rental_stock}\n";
    
    // Corriger les valeurs
    $aerateur->update([
        'is_rental_available' => true,
        'rental_stock' => 25,
        'rental_price_per_day' => 18.00,
        'deposit_amount' => 195.00
    ]);
    
    echo "\nAprès correction :\n";
    echo "- Disponible: " . ($aerateur->fresh()->is_rental_available ? 'OUI' : 'NON') . "\n";
    echo "- Stock: {$aerateur->fresh()->rental_stock}\n";
    echo "- Prix/jour: {$aerateur->fresh()->rental_price_per_day}€\n";
    echo "- Caution: {$aerateur->fresh()->deposit_amount}€\n";
    
    echo "\n✅ Aérateur corrigé et disponible pour location !\n";
    echo "URL : /rentals/location-aerateur-de-prairie-traine\n";
    
} else {
    echo "❌ Produit introuvable\n";
}

echo "\n=== FIN CORRECTION ===\n";
