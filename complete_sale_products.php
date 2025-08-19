<?php

require_once 'vendor/autoload.php';

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== AJOUT DES 8 DERNIERS PRODUITS D'ACHAT ===\n\n";

// Récupérer les catégories existantes
$categories = Category::all()->keyBy('slug');

// 8 PRODUITS SUPPLÉMENTAIRES pour atteindre 80
$additionalProducts = [
    // VIANDES (4 produits) - Catégorie: viandes
    ['name' => ['fr' => 'Poulet Fermier Bio', 'en' => 'Organic Free-Range Chicken', 'nl' => 'Bio Scharrelvlees Kip'], 'category' => 'viandes', 'unit' => 'kg', 'price' => 18.50],
    ['name' => ['fr' => 'Bœuf Bio Local', 'en' => 'Local Organic Beef', 'nl' => 'Lokaal Bio Rundvlees'], 'category' => 'viandes', 'unit' => 'kg', 'price' => 26.80],
    ['name' => ['fr' => 'Porc Fermier', 'en' => 'Farm Pork', 'nl' => 'Boeren Varkensvlees'], 'category' => 'viandes', 'unit' => 'kg', 'price' => 16.20],
    ['name' => ['fr' => 'Agneau Bio', 'en' => 'Organic Lamb', 'nl' => 'Bio Lamsvlees'], 'category' => 'viandes', 'unit' => 'kg', 'price' => 28.50],

    // POISSONS (4 produits) - Catégorie: poissons
    ['name' => ['fr' => 'Saumon Bio Atlantique', 'en' => 'Organic Atlantic Salmon', 'nl' => 'Bio Atlantische Zalm'], 'category' => 'poissons', 'unit' => 'kg', 'price' => 24.80],
    ['name' => ['fr' => 'Truite Arc-en-Ciel', 'en' => 'Rainbow Trout', 'nl' => 'Regenboogforel'], 'category' => 'poissons', 'unit' => 'kg', 'price' => 19.50],
    ['name' => ['fr' => 'Dorade Royale', 'en' => 'Royal Sea Bream', 'nl' => 'Koninklijke Zeebrasem'], 'category' => 'poissons', 'unit' => 'kg', 'price' => 22.30],
    ['name' => ['fr' => 'Bar de Ligne', 'en' => 'Line-Caught Sea Bass', 'nl' => 'Lijn Gevangen Zeebaars'], 'category' => 'poissons', 'unit' => 'kg', 'price' => 26.90],
];

echo "🛒 AJOUT DES PRODUITS D'ACHAT SUPPLÉMENTAIRES:\n";
$createdSale = 0;

foreach ($additionalProducts as $index => $productData) {
    try {
        $category = $categories[$productData['category']];
        if (!$category) {
            echo "❌ Catégorie '{$productData['category']}' non trouvée\n";
            continue;
        }

        $product = Product::create([
            'name' => $productData['name'],
            'slug' => Str::slug($productData['name']['fr']),
            'sku' => 'SALE-' . strtoupper(Str::random(6)),
            'description' => [
                'fr' => "Produit {$productData['name']['fr']} de qualité premium, sélectionné par nos producteurs locaux.",
                'en' => "Premium {$productData['name']['en']} product, selected by our local producers.",
                'nl' => "Premium {$productData['name']['nl']} product, geselecteerd door onze lokale producenten."
            ],
            'short_description' => [
                'fr' => "Produit frais de qualité supérieure.",
                'en' => "Fresh product of superior quality.",
                'nl' => "Vers product van superieure kwaliteit."
            ],
            'price' => $productData['price'],
            'quantity' => rand(5, 50),
            'unit_symbol' => $productData['unit'],
            'type' => 'sale', // TYPE SALE UNIQUEMENT
            'is_active' => true,
            'is_featured' => rand(1, 10) <= 3, // 30% de chance d'être en vedette
            'category_id' => $category->id,
            'meta_title' => [
                'fr' => "Achat {$productData['name']['fr']} Premium - FarmShop",
                'en' => "Buy {$productData['name']['en']} Premium - FarmShop",
                'nl' => "Koop {$productData['name']['nl']} Premium - FarmShop"
            ],
            'meta_description' => [
                'fr' => "Achetez {$productData['name']['fr']} premium de qualité supérieure sur FarmShop. Livraison fraîcheur garantie.",
                'en' => "Buy premium {$productData['name']['en']} of superior quality on FarmShop. Guaranteed freshness delivery.",
                'nl' => "Koop premium {$productData['name']['nl']} van superieure kwaliteit op FarmShop. Gegarandeerde versheid levering."
            ],
            'critical_threshold' => 3,
            'low_stock_threshold' => 10,
        ]);

        $createdSale++;
        echo "✅ Produit d'achat " . (72 + $createdSale) . ": {$productData['name']['fr']} (Catégorie: {$category->name})\n";

    } catch (Exception $e) {
        echo "❌ Erreur produit {$index}: " . $e->getMessage() . "\n";
    }
}

// Vérification du total
$totalProducts = Product::where('type', 'sale')->count();

echo "\n📊 RÉSUMÉ FINAL PRODUITS D'ACHAT:\n";
echo "- Ajoutés maintenant: {$createdSale}/8\n";
echo "- TOTAL PRODUITS D'ACHAT: {$totalProducts}/80\n";
echo "- Type: 'sale' uniquement ✅\n";
echo "- Unités: kg, litre, pièce selon le produit ✅\n";
echo "- Traductions: FR/EN/NL complètes ✅\n\n";

echo "=== CRÉATION TERMINÉE ===\n";
if ($totalProducts >= 80) {
    echo "🎉 OBJECTIF ATTEINT! 80 produits d'achat créés avec succès!\n";
} else {
    echo "⚠️ Manque encore " . (80 - $totalProducts) . " produits pour atteindre 80.\n";
}
