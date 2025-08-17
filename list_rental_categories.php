<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\RentalCategory;

echo "Catégories de location :\n";
echo "========================\n\n";

$categories = RentalCategory::active()->get(['id', 'name', 'slug']);

foreach ($categories as $category) {
    echo "ID: {$category->id} - Nom: {$category->name} - Slug: {$category->slug}\n";
}

echo "\nTotal: " . $categories->count() . " catégories\n";
