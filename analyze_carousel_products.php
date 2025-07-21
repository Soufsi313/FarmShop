<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "=== ANALYSE DES PRODUITS POUR LE CARROUSEL ===\n\n";

// Récupérer 10 produits aléatoires pour analyse
$products = Product::with(['category'])
    ->where('is_active', true)
    ->whereIn('type', ['sale', 'rental', 'both'])
    ->inRandomOrder()
    ->take(10)
    ->get();

echo "Produits analysés: " . $products->count() . "\n\n";

foreach ($products as $index => $product) {
    echo "📦 Produit #" . ($index + 1) . ":\n";
    echo "   Nom: {$product->name}\n";
    echo "   Type: {$product->type}\n";
    echo "   Prix: {$product->price}€\n";
    echo "   Unité: {$product->unit_symbol}\n";
    echo "   Image: " . ($product->image ? "✅ {$product->image}" : "❌ AUCUNE") . "\n";
    echo "   Catégorie: {$product->category->name}\n";
    
    // Vérifier si les champs spécifiques aux locations existent
    $columns = \Schema::getColumnListing('products');
    if (in_array('daily_price', $columns)) {
        echo "   Prix journalier: " . ($product->daily_price ?? 'NON DÉFINI') . "€\n";
    }
    if (in_array('rental_price', $columns)) {
        echo "   Prix location: " . ($product->rental_price ?? 'NON DÉFINI') . "€\n";
    }
    
    echo "\n";
}

// Vérifier la configuration de stockage
echo "=== CONFIGURATION STOCKAGE ===\n";
echo "Disk par défaut: " . config('filesystems.default') . "\n";
echo "URL publique: " . config('app.url') . "/storage\n";

// Vérifier si des produits ont des images
$productsWithImages = Product::whereNotNull('image')->count();
$totalProducts = Product::count();
echo "Produits avec images: {$productsWithImages}/{$totalProducts}\n";

echo "\n=== COLONNES DE LA TABLE PRODUCTS ===\n";
$columns = \Schema::getColumnListing('products');
foreach ($columns as $column) {
    echo "- {$column}\n";
}

echo "\n=== FIN DE L'ANALYSE ===\n";
