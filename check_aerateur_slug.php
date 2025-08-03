<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "=== VÉRIFICATION SLUG AÉRATEUR ===\n";

$aerateur = Product::find(214);
if ($aerateur) {
    echo "Produit ID 214 trouvé :\n";
    echo "- Nom: {$aerateur->name}\n";
    echo "- Slug: '{$aerateur->slug}'\n";
    echo "- Type: {$aerateur->type}\n";
    echo "- Disponible location: " . ($aerateur->is_rental_available ? 'OUI' : 'NON') . "\n";
    echo "- Stock location: {$aerateur->rental_stock}\n";
    
    // Vérifier si le slug est correct
    $correctURL = "/rentals/{$aerateur->slug}";
    echo "- URL correcte: {$correctURL}\n";
    
    // Chercher s'il y a des slug similaires
    echo "\nRecherche autres slug avec 'aerateur':\n";
    $others = Product::where('slug', 'like', '%aerateur%')->get(['id', 'name', 'slug']);
    foreach ($others as $prod) {
        echo "- ID {$prod->id}: {$prod->slug}\n";
    }
} else {
    echo "❌ Produit ID 214 introuvable\n";
}

echo "\n=== FIN VÉRIFICATION ===\n";
