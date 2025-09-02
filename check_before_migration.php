<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "=== ÉTAT AVANT MIGRATION ===\n\n";

$rentalProducts = Product::where('type', 'rental')->get();

echo "Nombre de produits de location: " . $rentalProducts->count() . "\n\n";

foreach ($rentalProducts as $product) {
    echo "ID: {$product->id} - {$product->name}\n";
    echo "  AVANT: min_days={$product->min_rental_days}, max_days={$product->max_rental_days}\n";
    echo "  APRÈS: min_days=1, max_days=NULL (pas de limite)\n";
    echo "  ---\n";
}

echo "\n=== RÉSUMÉ DES CHANGEMENTS À APPLIQUER ===\n";

$needMinUpdate = Product::where('type', 'rental')
    ->where('min_rental_days', '>', 1)
    ->count();

$allRentals = Product::where('type', 'rental')->count();

echo "• Produits qui auront min_rental_days mis à 1: {$needMinUpdate}\n";
echo "• Produits qui auront max_rental_days mis à NULL: {$allRentals}\n";
echo "\nCela permettra aux clients de:\n";
echo "✓ Louer pour 1 jour minimum (au lieu de 1-7 jours selon le produit)\n";
echo "✓ Louer sans limite de durée maximum\n";

echo "\n=== PRÊT POUR LA MIGRATION ===\n";
echo "Exécutez: php artisan migrate\n\n";
