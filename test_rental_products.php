<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "=== Test des produits de location ===\n";

$products = Product::whereIn('type', ['rental', 'both'])
    ->where('is_active', true)
    ->take(5)
    ->get(['id', 'name', 'type', 'quantity', 'rental_price_per_day', 'deposit_amount', 'min_rental_days', 'max_rental_days']);

if ($products->count() > 0) {
    echo "Produits de location trouvés :\n\n";
    
    foreach ($products as $product) {
        echo "ID: {$product->id}\n";
        echo "Nom: {$product->name}\n";
        echo "Type: {$product->type}\n";
        echo "Stock: {$product->quantity}\n";
        echo "Prix/jour: {$product->rental_price_per_day}€\n";
        echo "Caution: {$product->deposit_amount}€\n";
        echo "Durée min: {$product->min_rental_days} jour(s)\n";
        echo "Durée max: {$product->max_rental_days} jour(s)\n";
        echo "---\n";
    }
    
    // Test avec le premier produit
    $testProduct = $products->first();
    echo "\n=== Test du calculateur avec le produit ID: {$testProduct->id} ===\n";
    
    // Simuler une demande de calcul
    $startDate = now()->addDays(1)->format('Y-m-d');
    $endDate = now()->addDays(3)->format('Y-m-d');
    $quantity = 1;
    
    echo "Dates de test: du {$startDate} au {$endDate}\n";
    echo "Quantité: {$quantity}\n";
    
    // Calcul manuel pour vérifier
    $days = \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1;
    $subtotal = $testProduct->rental_price_per_day * $quantity * $days;
    $deposit = $testProduct->deposit_amount * $quantity;
    $tax = $subtotal * 0.20;
    $total = $subtotal + $tax;
    
    echo "Durée calculée: {$days} jour(s)\n";
    echo "Sous-total: {$subtotal}€\n";
    echo "TVA (20%): {$tax}€\n";
    echo "Caution: {$deposit}€\n";
    echo "Total: {$total}€\n";
    
} else {
    echo "Aucun produit de location trouvé.\n";
}

echo "\n=== Fin du test ===\n";
