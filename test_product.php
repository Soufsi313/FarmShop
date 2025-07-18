<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$request = \Illuminate\Http\Request::create('/');
$response = $kernel->handle($request);

use App\Models\Product;

$product = Product::find(241);
echo 'Product 241: ' . ($product ? $product->name : 'NOT FOUND') . PHP_EOL;

if ($product) {
    echo 'Type: ' . $product->type . PHP_EOL;
    echo 'Is Rentable: ' . ($product->isRentable() ? 'YES' : 'NO') . PHP_EOL;
    echo 'Rental Price: ' . $product->rental_price_per_day . PHP_EOL;
    echo 'Min Days: ' . $product->min_rental_days . PHP_EOL;
    echo 'Max Days: ' . $product->max_rental_days . PHP_EOL;
}
