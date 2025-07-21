<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\Category;

echo "=== DIAGNOSTIC FÉCULENTS ===\n\n";

// 1. Vérifier la catégorie Féculents
echo "1. VÉRIFICATION CATÉGORIE FÉCULENTS:\n";
$feculentsCategory = Category::where('name', 'Féculents')->first();

if ($feculentsCategory) {
    echo "✅ Catégorie trouvée:\n";
    echo "   ID: {$feculentsCategory->id}\n";
    echo "   Nom: {$feculentsCategory->name}\n";
    echo "   Active: " . ($feculentsCategory->is_active ? 'OUI' : 'NON') . "\n";
    echo "   Slug: {$feculentsCategory->slug}\n\n";
    
    // 2. Vérifier les produits féculents
    echo "2. PRODUITS FÉCULENTS EN BASE:\n";
    $feculentsProducts = Product::where('category_id', $feculentsCategory->id)->get();
    
    echo "Nombre total: " . $feculentsProducts->count() . "\n\n";
    
    foreach ($feculentsProducts as $product) {
        echo "• {$product->name}\n";
        echo "  ID: {$product->id} | Active: " . ($product->is_active ? 'OUI' : 'NON') . "\n";
        echo "  Prix: {$product->price}€ | Stock: {$product->quantity}\n";
        echo "  Slug: {$product->slug}\n\n";
    }
    
} else {
    echo "❌ Catégorie 'Féculents' NON TROUVÉE\n";
    
    // Chercher des catégories similaires
    echo "\nCatégories existantes:\n";
    $categories = Category::all();
    foreach ($categories as $cat) {
        echo "• {$cat->name} (ID: {$cat->id}) - Active: " . ($cat->is_active ? 'OUI' : 'NON') . "\n";
    }
}

// 3. Vérifier tous les produits actifs
echo "\n3. RÉSUMÉ PRODUITS ACTIFS PAR CATÉGORIE:\n";
$categoriesWithProducts = Category::withCount(['products' => function($query) {
    $query->where('is_active', true);
}])->get();

foreach ($categoriesWithProducts as $cat) {
    echo "• {$cat->name}: {$cat->products_count} produits actifs\n";
}
