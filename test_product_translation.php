<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Set locale to English
app()->setLocale('en');

// Get a product with the slug
$product = \App\Models\Product::where('slug', 'pommes-vertes-granny-smith-n1xl')->first();

if ($product) {
    echo "Product found: {$product->name}\n";
    echo "Product slug: {$product->slug}\n";
    
    // Test the translation function
    $translated_name = trans_product($product);
    echo "Translated name: {$translated_name}\n";
    
    // Test direct translation
    $direct_translation = __('app.' . $product->slug);
    echo "Direct translation: {$direct_translation}\n";
    
    // Test fallback translation
    $fallback_translation = __('app.product_names.' . $product->slug);
    echo "Fallback translation: {$fallback_translation}\n";
    
} else {
    echo "Product not found\n";
    
    // List products with similar names
    $products = \App\Models\Product::where('name', 'like', '%Pommes Vertes%')->get();
    echo "Found " . $products->count() . " products:\n";
    foreach ($products as $p) {
        echo "- {$p->name} (slug: {$p->slug})\n";
    }
}
