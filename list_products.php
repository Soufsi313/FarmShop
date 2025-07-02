<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$products = App\Models\Product::select('id', 'name', 'price')->get();

echo 'Produits disponibles (' . $products->count() . '):' . PHP_EOL;
echo str_repeat('-', 50) . PHP_EOL;

foreach($products as $product) {
    echo 'ID: ' . $product->id . ' - ' . $product->name;
    echo ' - Prix: ' . ($product->price ?? 'N/A') . '€';
    echo PHP_EOL;
}

echo str_repeat('-', 50) . PHP_EOL;
echo 'Total: ' . $products->count() . ' produits' . PHP_EOL;
