<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "=== VÉRIFICATION DES PRODUITS VEDETTES ===\n\n";

// Vérifier les produits vedettes
$featuredProducts = Product::where('is_featured', true)
    ->whereIn('type', ['sale', 'both'])
    ->with('category')
    ->get();

echo "Produits vedettes trouvés: " . $featuredProducts->count() . "\n\n";

if ($featuredProducts->count() === 0) {
    echo "❌ AUCUN PRODUIT VEDETTE TROUVÉ!\n";
    echo "Marquage de quelques produits comme vedettes...\n\n";
    
    // Marquer les 8 premiers produits de vente comme vedettes
    $productsToFeature = Product::whereIn('type', ['sale', 'both'])
        ->with('category')
        ->take(8)
        ->get();
    
    foreach ($productsToFeature as $product) {
        $product->update(['is_featured' => true]);
        echo "✅ Produit marqué comme vedette: {$product->name}\n";
    }
    
    echo "\n=== PRODUITS VEDETTES APRÈS MISE À JOUR ===\n\n";
    $featuredProducts = Product::where('is_featured', true)
        ->whereIn('type', ['sale', 'both'])
        ->with('category')
        ->get();
}

foreach ($featuredProducts as $index => $product) {
    echo "🌟 Produit #" . ($index + 1) . ":\n";
    echo "   Nom: {$product->name}\n";
    echo "   Catégorie: {$product->category->name}\n";
    echo "   Prix: {$product->price}€ / {$product->unit_symbol}\n";
    echo "   Image: " . ($product->image ? "✅ {$product->image}" : "❌ AUCUNE IMAGE") . "\n";
    echo "   Stock: {$product->quantity}\n";
    echo "   Type: {$product->type}\n";
    echo "   Description: " . substr($product->short_description, 0, 50) . "...\n";
    echo "\n";
}

// Vérifier la configuration de stockage
echo "=== CONFIGURATION DE STOCKAGE ===\n";
echo "Storage disk: " . config('filesystems.default') . "\n";
echo "Public path: " . storage_path('app/public') . "\n";
echo "Public URL: " . asset('storage') . "\n\n";

// Vérifier si le lien symbolique existe
$publicPath = public_path('storage');
if (is_link($publicPath)) {
    echo "✅ Lien symbolique storage existe\n";
    echo "   Pointe vers: " . readlink($publicPath) . "\n";
} else {
    echo "❌ Lien symbolique storage n'existe pas!\n";
    echo "   Exécutez: php artisan storage:link\n";
}

echo "\n=== FIN DE LA VÉRIFICATION ===\n";
