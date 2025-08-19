<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== TEST DES TYPES DE PRODUITS ===\n";

// Test quelques produits de vente
$saleProducts = DB::table('products')->where('type', 'sale')->limit(3)->get(['id', 'name', 'type']);
echo "Produits de vente (type='sale'):\n";
foreach($saleProducts as $product) {
    $name = json_decode($product->name)->fr ?? 'Nom non défini';
    echo "- ID {$product->id}: {$name} (type: {$product->type})\n";
}

// Test quelques produits de location  
$rentalProducts = DB::table('products')->where('type', 'rental')->limit(3)->get(['id', 'name', 'type']);
echo "\nProduits de location (type='rental'):\n";
foreach($rentalProducts as $product) {
    $name = json_decode($product->name)->fr ?? 'Nom non défini';
    echo "- ID {$product->id}: {$name} (type: {$product->type})\n";
}

echo "\n✅ Les corrections dans les vues admin devraient maintenant afficher:\n";
echo "- 'Vente uniquement' pour les produits avec type='sale'\n";
echo "- 'Location uniquement' pour les produits avec type='rental'\n";
echo "- 'Mixte' pour les produits avec type='both'\n";

?>
