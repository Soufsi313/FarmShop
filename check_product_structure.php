<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== STRUCTURE TABLE PRODUCTS ===" . PHP_EOL;

$columns = DB::select('DESCRIBE products');
foreach($columns as $col) {
    $line = sprintf("%-25s %s", $col->Field, $col->Type);
    if ($col->Field == 'quantity' || $col->Field == 'rental_stock') {
        $line .= " ⭐"; // Marquer les champs de stock
    }
    echo $line . PHP_EOL;
}

echo PHP_EOL . "=== VALEURS POUR PRODUIT 241 ===" . PHP_EOL;
$product = DB::table('products')->select('id', 'name', 'quantity', 'rental_stock')->where('id', 241)->first();
if ($product) {
    echo "Produit: " . $product->name . PHP_EOL;
    echo "quantity (stock vente): " . ($product->quantity ?? 'NULL') . PHP_EOL;
    echo "rental_stock (stock location): " . ($product->rental_stock ?? 'NULL') . PHP_EOL;
} else {
    echo "Produit 241 non trouvé" . PHP_EOL;
}
