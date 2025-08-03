<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "=== DIAGNOSTIC PRODUIT LOCATION ===\n";

// Rechercher le produit problématique
echo "1. Recherche produit ID 214...\n";
$product214 = Product::find(214);
if ($product214) {
    echo "✅ Produit ID 214 trouvé: {$product214->name}\n";
    echo "   - Stock location: {$product214->rental_stock}\n";
    echo "   - Disponible location: " . ($product214->is_rental_available ? 'OUI' : 'NON') . "\n";
} else {
    echo "❌ ERREUR: Produit ID 214 introuvable!\n";
}

// Rechercher l'aérateur
echo "\n2. Recherche aérateur de prairie...\n";
$aerateurs = Product::where('name', 'like', '%aerateur%')
    ->orWhere('name', 'like', '%aérateur%')
    ->orWhere('name', 'like', '%prairie%')
    ->get();

if ($aerateurs->count() > 0) {
    foreach ($aerateurs as $aerateur) {
        echo "✅ Trouvé: ID {$aerateur->id} - {$aerateur->name}\n";
        echo "   - Stock location: {$aerateur->rental_stock}\n";
        echo "   - Disponible: " . ($aerateur->is_rental_available ? 'OUI' : 'NON') . "\n";
    }
} else {
    echo "❌ Aucun aérateur trouvé\n";
}

// Afficher quelques produits disponibles pour location
echo "\n3. Quelques produits disponibles pour location:\n";
$available = Product::where('is_rental_available', true)
    ->where('rental_stock', '>', 0)
    ->take(5)
    ->get(['id', 'name', 'rental_stock']);

foreach ($available as $prod) {
    echo "- ID {$prod->id}: {$prod->name} (stock: {$prod->rental_stock})\n";
}

echo "\n=== FIN DIAGNOSTIC ===\n";
