<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\DB;

echo "🔄 Synchronisation des compteurs de likes...\n\n";

// Récupérer tous les produits
$products = Product::all();

foreach ($products as $product) {
    // Compter les vrais likes depuis la table product_likes
    $realLikesCount = DB::table('product_likes')
        ->where('product_id', $product->id)
        ->count();
    
    // Mettre à jour la colonne likes_count
    $product->update(['likes_count' => $realLikesCount]);
    
    echo "✅ Produit {$product->id} ({$product->name}): {$realLikesCount} likes\n";
}

echo "\n🎉 Synchronisation terminée !\n";
echo "📊 Résumé:\n";
echo "   - Produits traités: " . $products->count() . "\n";
echo "   - Total des likes: " . DB::table('product_likes')->count() . "\n";
