<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "=== VÉRIFICATION STOCK ABREUVOIR ===\n";

$abreuvoir = Product::where('name', 'like', '%breuvoir%')->first();

if ($abreuvoir) {
    echo "Produit trouvé: {$abreuvoir->name}\n";
    echo "ID: {$abreuvoir->id}\n";
    echo "Stock location: {$abreuvoir->rental_stock} unités\n";
    echo "Stock vente: {$abreuvoir->stock} unités\n";
    echo "Prix location: {$abreuvoir->rental_price}€/jour\n";
    echo "Disponible pour location: " . ($abreuvoir->is_rental_available ? 'OUI' : 'NON') . "\n";
} else {
    echo "❌ Aucun abreuvoir trouvé dans la base\n";
    
    // Chercher tous les produits avec 'abreuvoir' dans le nom
    $produits = Product::where('name', 'like', '%abreuvoir%')->get();
    echo "Recherche avec 'abreuvoir' (minuscules): " . $produits->count() . " produits\n";
    
    $produits = Product::where('name', 'like', '%Abreuvoir%')->get();
    echo "Recherche avec 'Abreuvoir' (majuscule): " . $produits->count() . " produits\n";
    
    // Afficher quelques produits pour debug
    echo "\nPremiers produits en base:\n";
    $premiers = Product::take(5)->get(['id', 'name', 'rental_stock']);
    foreach ($premiers as $p) {
        echo "- {$p->name} (stock: {$p->rental_stock})\n";
    }
}

echo "\n=== FIN VÉRIFICATION ===\n";
