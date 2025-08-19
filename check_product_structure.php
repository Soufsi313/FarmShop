<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

echo "=== ANALYSE D'UN PRODUIT EXISTANT ===\n";

$product = Product::first();
if ($product) {
    echo "Produit exemple: " . $product->name . "\n";
    echo "Structure:\n";
    foreach($product->toArray() as $key => $value) {
        echo "- $key: " . (is_array($value) ? json_encode($value) : $value) . "\n";
    }
} else {
    echo "Aucun produit trouvé\n";
}

?>
