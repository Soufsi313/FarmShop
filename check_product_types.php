<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$types = \App\Models\Product::select('type', \DB::raw('COUNT(*) as count'))
    ->groupBy('type')
    ->get();

echo "Types de produits en base :\n";
foreach ($types as $type) {
    echo "- {$type->type}: {$type->count} produits\n";
}

// Vérifier le produit spécifique qui pose problème
$product = \App\Models\Product::where('slug', 'location-abreuvoir-mobile-1000l')->first();
if ($product) {
    echo "\nProduit Abreuvoir mobile 1000L :\n";
    echo "- ID: {$product->id}\n";
    echo "- Type: {$product->type}\n";
    echo "- Is rental available: " . ($product->is_rental_available ? 'OUI' : 'NON') . "\n";
    echo "- Rental stock: " . ($product->rental_stock ?? 'NULL') . "\n";
    echo "- isRentable(): " . ($product->isRentable() ? 'OUI' : 'NON') . "\n";
}
