<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "=== CONFIGURATION ABREUVOIR POUR LOCATION ===\n";

// Trouver l'abreuvoir
$abreuvoir = Product::find(214);
if (!$abreuvoir) {
    echo "❌ Abreuvoir ID 214 introuvable!\n";
    exit;
}

echo "Produit trouvé: {$abreuvoir->name}\n";

// Configurer pour la location
$abreuvoir->update([
    'type' => 'rental',  // ou 'both' si vente ET location
    'is_rental_available' => true,
    'rental_stock' => 25,
    'rental_price_per_day' => 18.00,
    'deposit_amount' => 195.00,
    'min_rental_days' => 1,
    'max_rental_days' => 30
]);

echo "✅ Abreuvoir configuré avec succès !\n";
echo "   - Type: {$abreuvoir->fresh()->type}\n";
echo "   - Disponible location: " . ($abreuvoir->fresh()->is_rental_available ? 'OUI' : 'NON') . "\n";
echo "   - Stock location: {$abreuvoir->fresh()->rental_stock}\n";
echo "   - Prix/jour: {$abreuvoir->fresh()->rental_price_per_day}€\n";
echo "   - Caution: {$abreuvoir->fresh()->deposit_amount}€\n";

echo "\n=== CONFIGURATION TERMINÉE ===\n";
