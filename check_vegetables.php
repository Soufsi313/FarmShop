<?php

require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Models\Category;
use App\Models\Product;

$category = Category::where('name', 'Légumes')->first();

if ($category) {
    $products = Product::where('category_id', $category->id)->get(['name', 'slug', 'price']);
    echo "Nombre de légumes en base : " . $products->count() . PHP_EOL;
    echo "Liste des légumes :" . PHP_EOL;
    foreach ($products as $product) {
        echo "- " . $product->name . " (" . $product->price . "€)" . PHP_EOL;
    }
} else {
    echo "Catégorie Légumes non trouvée" . PHP_EOL;
}
