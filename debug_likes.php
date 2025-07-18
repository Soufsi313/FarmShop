<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Models\ProductLike;

echo "=== DEBUG LIKES ===\n";
echo "Total likes en base: " . ProductLike::count() . "\n";
echo "Produits avec likes_count > 0: " . Product::where('likes_count', '>', 0)->count() . "\n";

$product = Product::first();
if ($product) {
    echo "\n=== PREMIER PRODUIT ===\n";
    echo "ID: " . $product->id . "\n";
    echo "Nom: " . $product->name . "\n";
    echo "likes_count (DB): " . $product->likes_count . "\n";
    echo "likes()->count(): " . $product->likes()->count() . "\n";
    
    // Test de mise à jour
    $realCount = $product->likes()->count();
    $product->update(['likes_count' => $realCount]);
    $product->refresh();
    echo "Après mise à jour: " . $product->likes_count . "\n";
}

// Vérifier quelques likes
echo "\n=== PREMIERS LIKES ===\n";
$likes = ProductLike::with('product')->take(5)->get();
foreach ($likes as $like) {
    echo "Like ID {$like->id}: User {$like->user_id} -> Product {$like->product_id} ({$like->product->name})\n";
}

echo "\n=== VERIFICATION ===\n";
echo "Produits avec likes_count > 0 après update: " . Product::where('likes_count', '>', 0)->count() . "\n";

// Vérifier les produits des likes
echo "\n=== VERIFICATION PRODUITS AVEC LIKES ===\n";
$productWithLikes = Product::find(223);
if ($productWithLikes) {
    echo "Product 223: " . $productWithLikes->name . "\n";
    echo "likes_count (DB): " . $productWithLikes->likes_count . "\n";
    echo "likes()->count(): " . $productWithLikes->likes()->count() . "\n";
    
    // Mettre à jour ce produit spécifiquement
    $realCount = $productWithLikes->likes()->count();
    $productWithLikes->update(['likes_count' => $realCount]);
    $productWithLikes->refresh();
    echo "Après mise à jour: " . $productWithLikes->likes_count . "\n";
} else {
    echo "Product 223 non trouvé\n";
}
