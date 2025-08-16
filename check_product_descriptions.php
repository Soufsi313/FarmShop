<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Models\Category;

echo "=== CATEGORIES ANALYSIS ===\n\n";

$categories = Category::select('id', 'name', 'slug')->get();
foreach ($categories as $category) {
    echo "📂 Category ID: {$category->id} | Name: {$category->name} | Slug: {$category->slug}\n";
}

echo "\n=== PRODUCT DESCRIPTIONS ANALYSIS ===\n\n";

foreach ($categories as $category) {
    echo "📂 CATEGORY: " . strtoupper($category->name) . " (ID: {$category->id})\n";
    echo str_repeat("-", 60) . "\n";
    
    $products = Product::where('category_id', $category->id)->get();
    
    if ($products->isEmpty()) {
        echo "❌ No products found in this category\n\n";
        continue;
    }
    
    foreach ($products as $product) {
        echo "🔹 ID: {$product->id} | Name: {$product->name}\n";
        echo "   Slug: {$product->slug}\n";
        
        if (empty($product->description)) {
            echo "   Description: ❌ EMPTY\n";
        } else {
            $desc = strlen($product->description) > 150 ? 
                substr($product->description, 0, 150) . "..." : 
                $product->description;
            echo "   Description: {$desc}\n";
        }
        echo "   Description length: " . strlen($product->description) . " chars\n\n";
    }
    
    echo "✅ Total products in {$category->name}: " . $products->count() . "\n\n";
}

echo "=== SUMMARY ===\n";
echo "Total categories: " . $categories->count() . "\n";
echo "Total products in database: " . Product::count() . "\n";
