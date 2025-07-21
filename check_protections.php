<?php

// Initialiser Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Product;

// Vérifier si la catégorie Protections existe
$category = Category::where('name', 'Protections')->first();

if ($category) {
    echo "✅ Catégorie 'Protections' trouvée (ID: {$category->id})\n";
    
    // Compter les produits existants
    $count = Product::where('category_id', $category->id)->count();
    echo "📊 Nombre de produits actuels: {$count}\n";
    
    if ($count > 0) {
        echo "\n📝 Produits existants:\n";
        $products = Product::where('category_id', $category->id)->get();
        foreach ($products as $product) {
            echo "- {$product->name} ({$product->price}€) (ID: {$product->id})\n";
        }
    }
} else {
    echo "❌ Catégorie 'Protections' non trouvée\n";
    echo "🔍 Catégories disponibles:\n";
    $categories = Category::all();
    foreach ($categories as $cat) {
        echo "- {$cat->name} (ID: {$cat->id})\n";
    }
}
