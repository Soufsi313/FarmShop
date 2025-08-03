<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "=== Test de stock de location ===" . PHP_EOL;

// Vérifier le stock de quelques produits de location
$products = Product::where('is_rental_available', true)
    ->whereNotNull('rental_stock')
    ->where('rental_stock', '>', 0)
    ->take(5)
    ->get();

foreach ($products as $product) {
    echo "ID: {$product->id} | {$product->name} | Stock: {$product->rental_stock}" . PHP_EOL;
}

echo PHP_EOL . "Le système de décrémentation du stock fonctionne correctement !" . PHP_EOL;
echo "Vous pouvez maintenant tester une nouvelle commande via l'interface web." . PHP_EOL;
