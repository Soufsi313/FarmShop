<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\ProductLike;

echo "=== Diagnostic complet du système de likes ===\n\n";

$productSlug = 'tondeuse-a-gazon';
$product = Product::where('slug', $productSlug)->first();

echo "📋 Diagnostic pour '{$product->name}':\n";
echo "   - ID produit: {$product->id}\n";
echo "   - likes_count (colonne): {$product->likes_count}\n";

echo "\n🔍 Requête manuelle de comptage:\n";

// Compter manuellement avec différentes approches
$count1 = ProductLike::where('product_id', $product->id)->count();
echo "   - ProductLike::where('product_id', {$product->id})->count(): {$count1}\n";

$count2 = \DB::table('product_likes')->where('product_id', $product->id)->count();
echo "   - DB::table('product_likes')->where('product_id', {$product->id})->count(): {$count2}\n";

$count3 = $product->likes()->count();
echo "   - \$product->likes()->count(): {$count3}\n";

echo "\n📊 Tous les likes de ce produit:\n";
$likes = ProductLike::where('product_id', $product->id)->get();
foreach ($likes as $like) {
    echo "   - Like ID: {$like->id}, User ID: {$like->user_id}, Product ID: {$like->product_id}, Créé: {$like->created_at}\n";
}

echo "\n🔍 Vérification de la table product_likes:\n";
$allLikes = \DB::table('product_likes')->get();
echo "   - Total des likes dans la table: " . $allLikes->count() . "\n";
foreach ($allLikes as $like) {
    echo "   - ID: {$like->id}, User: {$like->user_id}, Product: {$like->product_id}, Créé: {$like->created_at}\n";
}

echo "\n=== Test d'ajout/suppression ===\n";

// Test d'ajout
echo "➕ Tentative d'ajout d'un like...\n";
try {
    $newLike = ProductLike::create([
        'user_id' => 1,
        'product_id' => $product->id
    ]);
    echo "✅ Like créé avec ID: {$newLike->id}\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

$countAfterAdd = ProductLike::where('product_id', $product->id)->count();
echo "🔢 Compteur après ajout: {$countAfterAdd}\n";

echo "\n=== Diagnostic terminé ===\n";
