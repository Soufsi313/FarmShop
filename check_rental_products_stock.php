<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

use App\Models\Product;
use App\Models\Category;

echo "=== VÉRIFICATION DES PRODUITS DE LOCATION ===\n\n";

// 1. Compter tous les produits par type
echo "1. RÉPARTITION DES PRODUITS PAR TYPE:\n";
$purchaseCount = Product::where('type', 'purchase')->count();
$rentalCount = Product::where('type', 'rental')->count();
$mixedCount = Product::where('type', 'mixed')->count();

echo "   - Purchase: $purchaseCount produits\n";
echo "   - Rental: $rentalCount produits\n";
echo "   - Mixed: $mixedCount produits\n";
echo "   - Total: " . ($purchaseCount + $rentalCount + $mixedCount) . " produits\n\n";

// 2. Vérifier les produits de location actifs
echo "2. PRODUITS DE LOCATION ACTIFS:\n";
$activeRentals = Product::where('type', 'rental')
    ->where('is_active', true)
    ->get();

echo "   Nombre de produits de location actifs: " . $activeRentals->count() . "\n\n";

if ($activeRentals->count() > 0) {
    echo "   DÉTAILS DES PRODUITS DE LOCATION:\n";
    foreach ($activeRentals as $product) {
        echo "   🔧 {$product->name}\n";
        echo "      - ID: {$product->id}\n";
        echo "      - SKU: {$product->sku}\n";
        echo "      - Prix: {$product->price}€/jour\n";
        echo "      - Stock rental: {$product->rental_stock}\n";
        echo "      - Actif: " . ($product->is_active ? 'Oui' : 'Non') . "\n";
        echo "      - Catégorie: " . ($product->category ? $product->category->name : 'Aucune') . "\n";
        echo "      - Image: " . ($product->main_image ? 'Oui' : 'Non') . "\n\n";
    }
}

// 3. Vérifier les produits mixtes (qui peuvent être loués)
echo "3. PRODUITS MIXTES (ACHAT + LOCATION):\n";
$mixedProducts = Product::where('type', 'mixed')
    ->where('is_active', true)
    ->get();

echo "   Nombre de produits mixtes actifs: " . $mixedProducts->count() . "\n\n";

if ($mixedProducts->count() > 0) {
    echo "   DÉTAILS DES PRODUITS MIXTES:\n";
    foreach ($mixedProducts as $product) {
        echo "   🔧 {$product->name}\n";
        echo "      - ID: {$product->id}\n";
        echo "      - Stock rental: {$product->rental_stock}\n";
        echo "      - Prix location: {$product->price}€/jour\n\n";
    }
}

// 4. Total des produits disponibles en location
$totalRentalAvailable = Product::whereIn('type', ['rental', 'mixed'])
    ->where('is_active', true)
    ->where('rental_stock', '>', 0)
    ->count();

echo "4. PRODUITS DISPONIBLES EN LOCATION (stock > 0): $totalRentalAvailable\n\n";

// 5. Vérifier les catégories pour les locations
echo "5. CATÉGORIES POUR LES LOCATIONS:\n";
$rentalCategories = Category::whereHas('products', function($query) {
    $query->whereIn('type', ['rental', 'mixed'])->where('is_active', true);
})->get();

foreach ($rentalCategories as $category) {
    $productCount = $category->products()->whereIn('type', ['rental', 'mixed'])->where('is_active', true)->count();
    echo "   - {$category->name}: $productCount produits\n";
}

echo "\n=== DIAGNOSTIC TERMINÉ ===\n";
