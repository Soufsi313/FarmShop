<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

// Trouver le produit abreuvoir
$product = Product::where('slug', 'location-abreuvoir-mobile-1000l')->first();

if ($product) {
    echo "=== Contraintes du produit: {$product->name} ===\n";
    echo "Slug: {$product->slug}\n";
    echo "Type: {$product->type}\n";
    echo "Est louable: " . ($product->isRentable() ? 'Oui' : 'Non') . "\n";
    echo "Stock: {$product->quantity}\n";
    echo "Prix location/jour: {$product->rental_price_per_day}€\n";
    echo "Caution: {$product->deposit_amount}€\n";
    echo "Durée min: " . ($product->min_rental_days ?? 'Non définie') . " jour(s)\n";
    echo "Durée max: " . ($product->max_rental_days ?? 'Non définie') . " jour(s)\n";
    echo "Préavis: " . ($product->advance_notice_days ?? 'Non défini') . " jour(s)\n";
    
    // Tester différentes durées
    echo "\n=== Tests de durées ===\n";
    $testCases = [
        ['2025-07-28', '2025-07-29', 'Une journée'],
        ['2025-07-28', '2025-07-30', 'Deux jours'],
        ['2025-07-28', '2025-08-04', 'Une semaine'],
    ];
    
    foreach ($testCases as [$startDate, $endDate, $description]) {
        $start = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);
        $days = $start->diffInDays($end) + 1;
        
        echo "\n{$description} ({$startDate} -> {$endDate}): {$days} jour(s)\n";
        
        if ($product->min_rental_days && $days < $product->min_rental_days) {
            echo "  ❌ Trop court (min: {$product->min_rental_days})\n";
        } elseif ($product->max_rental_days && $days > $product->max_rental_days) {
            echo "  ❌ Trop long (max: {$product->max_rental_days})\n";
        } else {
            echo "  ✅ Durée valide\n";
            $cost = $product->rental_price_per_day * $days;
            echo "  💰 Coût: {$cost}€\n";
        }
    }
    
} else {
    echo "Produit non trouvé avec le slug 'location-abreuvoir-mobile-1000l'\n";
    
    // Lister les produits de location disponibles
    echo "\nProduits de location disponibles:\n";
    $products = Product::whereIn('type', ['rental', 'both'])->where('is_active', true)->get(['id', 'name', 'slug']);
    foreach ($products as $p) {
        echo "- {$p->name} (slug: {$p->slug})\n";
    }
}
