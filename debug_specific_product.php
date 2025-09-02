<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\ProductLike;

// Test avec un produit spécifique
$productSlug = 'tondeuse-a-gazon'; // Le produit qui avait 1 like

echo "=== Test du produit '{$productSlug}' ===\n\n";

$product = Product::where('slug', $productSlug)->first();
if (!$product) {
    echo "❌ Produit non trouvé\n";
    exit(1);
}

echo "📋 État actuel:\n";
echo "   - likes_count (colonne): {$product->likes_count}\n";
echo "   - getLikesCount(): {$product->getLikesCount()}\n";

$realCount = ProductLike::where('product_id', $product->id)->count();
echo "   - Likes réels: {$realCount}\n";

if ($product->likes_count != $realCount) {
    echo "\n⚠️  PROBLÈME: La colonne likes_count n'est pas à jour!\n";
    echo "🔧 Correction...\n";
    $product->update(['likes_count' => $realCount]);
    $product->refresh();
    echo "✅ Corrigé: likes_count = {$product->likes_count}\n";
} else {
    echo "✅ Compteur cohérent\n";
}

// Afficher les likes de ce produit
$likes = ProductLike::where('product_id', $product->id)->with('user')->get();
echo "\n👥 Utilisateurs qui ont liké:\n";
foreach ($likes as $like) {
    echo "   - {$like->user->name} (ID: {$like->user->id}) - {$like->created_at}\n";
}

echo "\n=== Test terminé ===\n";
