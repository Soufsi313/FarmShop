<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$products = App\Models\Product::whereHas('category', function($query) {
    $query->where('slug', 'fruits');
})->get(['id', 'name', 'slug', 'description']);

echo "Produits de la catégorie Fruits:\n";
foreach($products as $product) {
    echo "- ID: {$product->id}, Name: {$product->name}, Slug: {$product->slug}\n";
    echo "  Description: " . substr($product->description, 0, 100) . "...\n\n";
}
