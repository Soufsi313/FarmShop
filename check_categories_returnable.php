<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$categories = \App\Models\Category::select('id', 'name', 'food_type', 'is_returnable')->get();

echo "Catégories et leur statut de retour :\n";
echo "ID | Nom | Type | Retournable\n";
echo "---|-----|------|------------\n";
foreach ($categories as $cat) {
    echo sprintf('%d | %s | %s | %s', 
        $cat->id, 
        $cat->name, 
        $cat->food_type ?? 'null', 
        $cat->is_returnable ? 'OUI' : 'NON'
    ) . "\n";
}

// Vérifier aussi quelques produits
echo "\n\nQuelques produits et leur catégorie :\n";
echo "ID | Nom | Catégorie | Type | Retournable\n";
echo "---|-----|-----------|------|------------\n";

$products = \App\Models\Product::with('category')
    ->select('id', 'name', 'category_id')
    ->take(10)
    ->get();

foreach ($products as $product) {
    echo sprintf('%d | %s | %s | %s | %s', 
        $product->id, 
        $product->name, 
        $product->category->name ?? 'N/A',
        $product->category->food_type ?? 'null', 
        ($product->category->is_returnable ?? false) ? 'OUI' : 'NON'
    ) . "\n";
}
