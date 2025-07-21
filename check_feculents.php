<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\Category;

echo "=== VÉRIFICATION PRODUITS FÉCULENTS ===\n\n";

// Vérifier la catégorie Féculents
$feculentsCategory = Category::where('name', 'Féculents')->first();

if (!$feculentsCategory) {
    echo "❌ Catégorie 'Féculents' non trouvée\n";
    echo "Créons-la d'abord...\n";
    
    $feculentsCategory = Category::create([
        'name' => 'Féculents',
        'slug' => 'feculents',
        'description' => 'Tubercules, légumineuses et farines fermières',
        'is_active' => true,
        'sort_order' => 10
    ]);
    
    echo "✅ Catégorie 'Féculents' créée avec l'ID: {$feculentsCategory->id}\n\n";
} else {
    echo "✅ Catégorie 'Féculents' trouvée - ID: {$feculentsCategory->id}\n";
    echo "   Statut: " . ($feculentsCategory->is_active ? 'Active' : 'Inactive') . "\n\n";
}

// Vérifier les produits dans cette catégorie
$feculentsProducts = Product::where('category_id', $feculentsCategory->id)->get();

echo "📊 PRODUITS DANS LA CATÉGORIE FÉCULENTS:\n";
echo "Nombre total: " . $feculentsProducts->count() . "\n\n";

if ($feculentsProducts->count() > 0) {
    echo "Liste des produits:\n";
    foreach ($feculentsProducts as $product) {
        echo "• ID: {$product->id} - {$product->name} - {$product->price}€\n";
        echo "  SKU: {$product->sku}\n";
        echo "  Statut: " . ($product->is_active ? 'Actif' : 'Inactif') . "\n\n";
    }
} else {
    echo "❌ AUCUN PRODUIT trouvé dans la catégorie Féculents\n";
    echo "Le seeder n'a pas été exécuté ou a échoué.\n\n";
    
    echo "Pour exécuter le seeder:\n";
    echo "php artisan db:seed --class=FeculentsSeeder\n";
}

echo "=== FIN DE LA VÉRIFICATION ===\n";
