<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\ProductLike;

echo "=== Synchronisation des compteurs de likes ===\n\n";

$products = Product::all();
$corrected = 0;
$total = $products->count();

echo "📊 Vérification de {$total} produits...\n\n";

foreach ($products as $product) {
    $realLikesCount = ProductLike::where('product_id', $product->id)->count();
    $currentLikesCount = $product->likes_count ?? 0;
    
    if ($currentLikesCount != $realLikesCount) {
        echo "🔧 {$product->name} (slug: {$product->slug})\n";
        echo "   - Avant: {$currentLikesCount} likes\n";
        echo "   - Réel: {$realLikesCount} likes\n";
        
        // Utiliser une requête SQL directe pour éviter les problèmes de cache Eloquent
        \DB::table('products')
            ->where('id', $product->id)
            ->update(['likes_count' => $realLikesCount, 'updated_at' => now()]);
            
        echo "   - ✅ Corrigé: {$realLikesCount} likes\n\n";
        $corrected++;
    }
}

echo "=== Résumé ===\n";
echo "✅ Total produits: {$total}\n";
echo "🔧 Produits corrigés: {$corrected}\n";
echo "✅ Produits OK: " . ($total - $corrected) . "\n";

if ($corrected > 0) {
    echo "\n🎉 Synchronisation terminée avec succès !\n";
} else {
    echo "\n✅ Tous les compteurs étaient déjà cohérents !\n";
}
