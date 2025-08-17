<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$categories = App\Models\Category::with('products')->get();

echo "Produits par catégorie:\n";
foreach($categories as $category) {
    $count = $category->products->count();
    echo "- {$category->slug}: {$count} produits\n";
    
    if($count > 0 && $count <= 5) {
        foreach($category->products->take(5) as $product) {
            echo "  * {$product->slug}\n";
        }
    } elseif($count > 5) {
        foreach($category->products->take(3) as $product) {
            echo "  * {$product->slug}\n";
        }
        echo "  ... et " . ($count - 3) . " autres\n";
    }
    echo "\n";
}
