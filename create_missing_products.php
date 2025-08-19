<?php

require_once 'vendor/autoload.php';

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CRÉATION DES 6 PRODUITS MANQUANTS ===\n\n";

// Récupérer les catégories nécessaires
$fruitsCategory = Category::where('slug', 'fruits')->first();
$legumesCategory = Category::where('slug', 'legumes')->first();
$outilsCategory = Category::where('slug', 'outils-agricoles')->first();

if (!$fruitsCategory || !$legumesCategory || !$outilsCategory) {
    echo "❌ Erreur: Certaines catégories nécessaires non trouvées\n";
    exit;
}

// Données des produits manquants à créer
$missingProducts = [
    [
        'id' => 30,
        'name' => 'Persil Frais',
        'description' => 'Persil frais cultivé localement, parfait pour vos plats.',
        'type' => 'sale',
        'category_id' => $legumesCategory->id,
        'price' => 2.50,
        'quantity' => 50,
        'unit_symbol' => 'bouquet'
    ],
    [
        'id' => 49,
        'name' => 'Courges Butternut',
        'description' => 'Courges butternut de saison, idéales pour soupes et gratins.',
        'type' => 'sale', 
        'category_id' => $legumesCategory->id,
        'price' => 4.80,
        'quantity' => 25,
        'unit_symbol' => 'kg'
    ],
    [
        'id' => 85,
        'name' => 'Sécateur de Jardin',
        'description' => 'Sécateur professionnel pour la taille et l\'entretien.',
        'type' => 'rental',
        'category_id' => $outilsCategory->id,
        'rental_price_per_day' => 8.50,
        'rental_stock' => 5,
        'unit_symbol' => 'unité'
    ]
];

// Créer quelques produits supplémentaires pour atteindre 84
$additionalProducts = [
    [
        'name' => 'Melons Charentais',
        'description' => 'Melons charentais sucrés et parfumés.',
        'type' => 'sale',
        'category_id' => $fruitsCategory->id,
        'price' => 3.20,
        'quantity' => 30,
        'unit_symbol' => 'pièce'
    ],
    [
        'name' => 'Pastèques',
        'description' => 'Pastèques rafraîchissantes pour l\'été.',
        'type' => 'sale',
        'category_id' => $fruitsCategory->id,
        'price' => 6.50,
        'quantity' => 15,
        'unit_symbol' => 'pièce'
    ],
    [
        'name' => 'Bineuse Manuelle',
        'description' => 'Bineuse manuelle pour l\'entretien des cultures.',
        'type' => 'rental',
        'category_id' => $outilsCategory->id,
        'rental_price_per_day' => 5.00,
        'rental_stock' => 8,
        'unit_symbol' => 'unité'
    ]
];

$created = 0;

// Créer les produits avec IDs spécifiques
foreach ($missingProducts as $productData) {
    if (!Product::find($productData['id'])) {
        $product = new Product();
        $product->id = $productData['id'];
        $product->name = $productData['name'];
        $product->slug = Str::slug($productData['name']);
        $product->description = $productData['description'];
        $product->type = $productData['type'];
        $product->category_id = $productData['category_id'];
        $product->sku = 'PROD-' . strtoupper(Str::random(6));
        $product->unit_symbol = $productData['unit_symbol'];
        $product->is_active = true;
        $product->critical_threshold = 5;
        $product->low_stock_threshold = 10;
        
        if ($productData['type'] === 'sale') {
            $product->price = $productData['price'];
            $product->quantity = $productData['quantity'];
        } else {
            $product->rental_price_per_day = $productData['rental_price_per_day'];
            $product->rental_stock = $productData['rental_stock'];
        }
        
        $product->save();
        echo "✅ Créé ID {$product->id}: '{$product->name}' (SKU: {$product->sku})\n";
        $created++;
    } else {
        echo "❌ Produit ID {$productData['id']} existe déjà\n";
    }
}

// Créer les produits supplémentaires
foreach ($additionalProducts as $productData) {
    $product = new Product();
    $product->name = $productData['name'];
    $product->slug = Str::slug($productData['name']);
    $product->description = $productData['description'];
    $product->type = $productData['type'];
    $product->category_id = $productData['category_id'];
    $product->sku = 'PROD-' . strtoupper(Str::random(6));
    $product->unit_symbol = $productData['unit_symbol'];
    $product->is_active = true;
    $product->critical_threshold = 5;
    $product->low_stock_threshold = 10;
    
    if ($productData['type'] === 'sale') {
        $product->price = $productData['price'];
        $product->quantity = $productData['quantity'];
    } else {
        $product->rental_price_per_day = $productData['rental_price_per_day'];
        $product->rental_stock = $productData['rental_stock'];
    }
    
    $product->save();
    echo "✅ Créé ID {$product->id}: '{$product->name}' (SKU: {$product->sku})\n";
    $created++;
}

$finalCount = Product::count();
echo "\n📊 RÉSUMÉ FINAL:\n";
echo "- Produits créés: {$created}\n";
echo "- Total produits: {$finalCount}\n";

if ($finalCount == 84) {
    echo "🎉 PARFAIT! Vous avez maintenant exactement 84 produits!\n";
} else {
    echo "⚠️  Total: {$finalCount} (objectif: 84)\n";
}

echo "\n=== CRÉATION TERMINÉE ===\n";
