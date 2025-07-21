<?php

require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Models\Category;

$categories = Category::all(['id', 'name']);

echo "Toutes les catégories :\n";
foreach ($categories as $category) {
    echo "ID: {$category->id} - {$category->name}\n";
}

echo "\nCatégories contenant 'lait' :\n";
$laitierCategories = Category::where('name', 'like', '%lait%')->get(['id', 'name']);
foreach ($laitierCategories as $category) {
    echo "ID: {$category->id} - {$category->name}\n";
}
