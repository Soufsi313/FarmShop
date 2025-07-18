<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Product;

echo "=== REQUETE SQL DIRECTE ===\n";

// Compter les likes par produit via SQL
$likesPerProduct = DB::select('
    SELECT product_id, COUNT(*) as likes_count
    FROM product_likes 
    GROUP BY product_id 
    HAVING COUNT(*) > 0
    ORDER BY likes_count DESC
    LIMIT 10
');

echo "Top 10 produits avec le plus de likes (via SQL):\n";
foreach ($likesPerProduct as $row) {
    $product = Product::find($row->product_id);
    $productName = $product ? $product->name : 'Product not found';
    echo "Product {$row->product_id}: {$productName} - {$row->likes_count} likes\n";
}

echo "\n=== MISE A JOUR DIRECTE ===\n";

// Mettre à jour directement via SQL
$updated = DB::statement('
    UPDATE products 
    SET likes_count = (
        SELECT COUNT(*) 
        FROM product_likes 
        WHERE product_likes.product_id = products.id
    )
');

echo "Mise à jour SQL: " . ($updated ? "SUCCESS" : "FAILED") . "\n";

// Vérifier le résultat
$productsWithLikes = DB::select('SELECT id, name, likes_count FROM products WHERE likes_count > 0 LIMIT 10');
echo "\nProduits avec likes après mise à jour SQL:\n";
foreach ($productsWithLikes as $product) {
    echo "Product {$product->id}: {$product->name} - {$product->likes_count} likes\n";
}
