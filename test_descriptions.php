<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

// Test the description translation system
echo "=== TESTING PRODUCT DESCRIPTION TRANSLATION ===\n\n";

// Test with a fruit
$product = Product::where('slug', 'pommes-rouges-royal-gala-ozjz')->first();
if ($product) {
    echo "🍎 Testing Fruit Product:\n";
    echo "Product: " . $product->name . "\n";
    echo "Slug: " . $product->slug . "\n";
    echo "Description from DB: '" . $product->description . "'\n";
    echo "Translated name: " . trans_product($product, 'name') . "\n";
    echo "Translated description: " . trans_product($product, 'description') . "\n\n";
}

// Test with a vegetable
$product = Product::where('slug', 'carottes-bio-my8d')->first();
if ($product) {
    echo "🥕 Testing Vegetable Product:\n";
    echo "Product: " . $product->name . "\n";
    echo "Slug: " . $product->slug . "\n";
    echo "Description from DB: '" . $product->description . "'\n";
    echo "Translated name: " . trans_product($product, 'name') . "\n";
    echo "Translated description: " . trans_product($product, 'description') . "\n\n";
}

// Test with a cereal
$product = Product::where('slug', 'avoine-bio-25kg-ie46')->first();
if ($product) {
    echo "🌾 Testing Cereal Product:\n";
    echo "Product: " . $product->name . "\n";
    echo "Slug: " . $product->slug . "\n";
    echo "Description from DB: '" . $product->description . "'\n";
    echo "Translated name: " . trans_product($product, 'name') . "\n";
    echo "Translated description: " . trans_product($product, 'description') . "\n\n";
}

echo "=== TEST COMPLETED ===\n";
