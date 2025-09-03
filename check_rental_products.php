<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "=== VÉRIFICATION DES PRODUITS DE LOCATION ===\n\n";

// Compter les produits par type
$totalProducts = Product::count();
$rentalProducts = Product::where('type', 'rental')->count();
$saleProducts = Product::where('type', 'sale')->count();

echo "Total des produits: {$totalProducts}\n";
echo "Produits de location (type='rental'): {$rentalProducts}\n";
echo "Produits de vente (type='sale'): {$saleProducts}\n\n";

if ($rentalProducts > 0) {
    echo "=== ÉCHANTILLON DES PRODUITS DE LOCATION ===\n";
    $sampleRentals = Product::where('type', 'rental')
        ->select('id', 'name', 'type', 'min_rental_days', 'max_rental_days', 'rental_price_per_day')
        ->take(10)
        ->get();
    
    foreach ($sampleRentals as $product) {
        echo "ID: {$product->id}\n";
        echo "  Nom: {$product->name}\n";
        echo "  Type: {$product->type}\n";
        echo "  Min jours: {$product->min_rental_days}\n";
        echo "  Max jours: {$product->max_rental_days}\n";
        echo "  Prix/jour: {$product->rental_price_per_day}€\n";
        echo "  ---\n";
    }
    
    echo "\n=== STATISTIQUES DES DURÉES DE LOCATION ===\n";
    $minDaysStats = Product::where('type', 'rental')
        ->selectRaw('min_rental_days, COUNT(*) as count')
        ->groupBy('min_rental_days')
        ->get();
    
    echo "Distribution min_rental_days:\n";
    foreach ($minDaysStats as $stat) {
        echo "  {$stat->min_rental_days} jour(s): {$stat->count} produit(s)\n";
    }
    
    $maxDaysStats = Product::where('type', 'rental')
        ->selectRaw('max_rental_days, COUNT(*) as count')
        ->groupBy('max_rental_days')
        ->get();
    
    echo "\nDistribution max_rental_days:\n";
    foreach ($maxDaysStats as $stat) {
        $days = $stat->max_rental_days ?? 'NULL';
        echo "  {$days} jour(s): {$stat->count} produit(s)\n";
    }
} else {
    echo "Aucun produit de location trouvé.\n";
    
    // Vérifier s'il y a des produits avec des valeurs de location
    $productsWithRentalFields = Product::whereNotNull('rental_price_per_day')
        ->orWhereNotNull('min_rental_days')
        ->orWhereNotNull('max_rental_days')
        ->count();
    
    echo "Produits avec des champs de location remplis: {$productsWithRentalFields}\n";
    
    if ($productsWithRentalFields > 0) {
        echo "\nÉchantillon de produits avec champs de location:\n";
        $sample = Product::whereNotNull('rental_price_per_day')
            ->select('id', 'name', 'type', 'min_rental_days', 'max_rental_days', 'rental_price_per_day')
            ->take(5)
            ->get();
            
        foreach ($sample as $product) {
            echo "ID: {$product->id}, Nom: {$product->name}, Type: {$product->type}\n";
        }
    }
}

echo "\n=== FIN DE LA VÉRIFICATION ===\n";
