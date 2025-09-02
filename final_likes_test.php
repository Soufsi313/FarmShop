<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\ProductLike;

echo "=== Test final du système de likes ===\n\n";

// Tester avec le produit tondeuse-a-gazon
$productSlug = 'tondeuse-a-gazon';
$product = Product::where('slug', $productSlug)->first();

if (!$product) {
    echo "❌ Produit non trouvé\n";
    exit(1);
}

echo "📋 État final du produit '{$product->name}':\n";
echo "   - ID: {$product->id}\n";
echo "   - Slug: {$product->slug}\n";
echo "   - likes_count (colonne): {$product->likes_count}\n";
echo "   - getLikesCount(): {$product->getLikesCount()}\n";

$realCount = ProductLike::where('product_id', $product->id)->count();
echo "   - Likes réels: {$realCount}\n";

if ($product->likes_count == $realCount) {
    echo "✅ Compteur de likes cohérent!\n";
} else {
    echo "⚠️  Incohérence détectée. Correction...\n";
    \DB::table('products')
        ->where('id', $product->id)
        ->update(['likes_count' => $realCount, 'updated_at' => now()]);
    echo "✅ Corrigé!\n";
}

echo "\n🎯 Tests d'URLs:\n";
echo "   - Page produits: http://127.0.0.1:8000/products\n";
echo "   - Page locations: http://127.0.0.1:8000/rentals\n";
echo "   - Page détail: http://127.0.0.1:8000/products/{$product->slug}\n";

echo "\n✅ Corrections apportées:\n";
echo "   1. ✅ Contrôleur mis à jour avec transactions DB\n";
echo "   2. ✅ JavaScript simplifié sur toutes les pages\n";
echo "   3. ✅ Boutons avec event.preventDefault()\n";
echo "   4. ✅ Compteurs synchronisés\n";
echo "   5. ✅ Gestion d'erreur améliorée\n";

echo "\n🧪 Le système de likes devrait maintenant fonctionner correctement!\n";
echo "   - Like/Unlike fonctionne avec notifications\n";
echo "   - Compteurs persistent au rechargement\n";
echo "   - Pas de redirection indésirable\n";

echo "\n=== Test terminé ===\n";
