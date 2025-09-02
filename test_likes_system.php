<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\ProductLike;
use App\Models\User;

echo "=== Test du système de likes ===\n\n";

// Trouver un produit de test
$product = Product::where('is_active', true)->first();
if (!$product) {
    echo "❌ Aucun produit actif trouvé\n";
    exit(1);
}

echo "✅ Produit de test: {$product->name} (slug: {$product->slug})\n";
echo "   - Likes actuels: {$product->getLikesCount()}\n";
echo "   - likes_count en base: {$product->likes_count}\n";

// Trouver un utilisateur de test
$user = User::first();
if (!$user) {
    echo "❌ Aucun utilisateur trouvé\n";
    exit(1);
}

echo "✅ Utilisateur de test: {$user->name}\n";

// Vérifier si l'utilisateur a déjà liké ce produit
$existingLike = ProductLike::where('user_id', $user->id)->where('product_id', $product->id)->first();
echo "   - Like existant: " . ($existingLike ? 'OUI' : 'NON') . "\n";

// Compter les likes réels pour ce produit
$realLikesCount = ProductLike::where('product_id', $product->id)->count();
echo "   - Likes réels en base: {$realLikesCount}\n";

// Vérifier s'il y a une différence
if ($product->likes_count != $realLikesCount) {
    echo "⚠️  INCOHÉRENCE détectée!\n";
    echo "   - likes_count (colonne): {$product->likes_count}\n";
    echo "   - Likes réels (comptage): {$realLikesCount}\n";
    
    echo "\n🔧 Correction automatique...\n";
    $product->update(['likes_count' => $realLikesCount]);
    $product->refresh();
    echo "✅ likes_count corrigé: {$product->likes_count}\n";
} else {
    echo "✅ Compteur de likes cohérent\n";
}

echo "\n=== Test simulation ajout/suppression like ===\n";

if ($existingLike) {
    echo "🗑️  Suppression du like existant...\n";
    $existingLike->delete();
    $newCount = ProductLike::where('product_id', $product->id)->count();
    $product->update(['likes_count' => $newCount]);
    echo "✅ Like supprimé. Nouveau compteur: {$newCount}\n";
} else {
    echo "➕ Ajout d'un nouveau like...\n";
    ProductLike::create([
        'user_id' => $user->id,
        'product_id' => $product->id
    ]);
    $newCount = ProductLike::where('product_id', $product->id)->count();
    $product->update(['likes_count' => $newCount]);
    echo "✅ Like ajouté. Nouveau compteur: {$newCount}\n";
}

echo "\n=== Vérification des méthodes du modèle ===\n";
$product->refresh();
echo "✅ getLikesCount(): {$product->getLikesCount()}\n";
echo "✅ isLikedByUser() (auth requis): " . ($product->isLikedByUser() ? 'OUI' : 'NON') . "\n";

echo "\n=== Test terminé ===\n";
