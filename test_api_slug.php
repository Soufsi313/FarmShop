<?php

// Script de test pour vérifier l'API de panier location avec slug

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test 1: Recherche par slug
echo "=== Test 1: Recherche par slug ===\n";
$product = App\Models\Product::where('slug', 'location-abreuvoir-mobile-1000l')->first();
if ($product) {
    echo "✅ Produit trouvé par slug: {$product->name} (ID: {$product->id})\n";
    echo "Slug: {$product->slug}\n";
    echo "Is rentable: " . ($product->isRentable() ? 'Oui' : 'Non') . "\n";
} else {
    echo "❌ Produit non trouvé par slug\n";
}

// Test 2: Test de l'URL de l'API
echo "\n=== Test 2: URL API ===\n";
$url = url('/api/cart-location/products/location-abreuvoir-mobile-1000l');
echo "URL API: $url\n";

// Test 3: Test de la route
echo "\n=== Test 3: Route ===\n";
try {
    $route = Route::getRoutes()->match(
        Illuminate\Http\Request::create('/api/cart-location/products/location-abreuvoir-mobile-1000l', 'POST')
    );
    echo "✅ Route trouvée: " . $route->getName() . "\n";
    echo "Action: " . $route->getActionName() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur route: " . $e->getMessage() . "\n";
}

echo "\n=== Fin des tests ===\n";
