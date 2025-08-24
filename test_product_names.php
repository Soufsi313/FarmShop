<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Test des noms de produits dans le panier ===\n\n";

// Récupérer quelques produits pour voir leur format de nom
$products = DB::select("SELECT id, name, slug FROM products LIMIT 5");

echo "Échantillon de produits :\n";
foreach($products as $product) {
    echo "ID: {$product->id}\n";
    echo "Slug: {$product->slug}\n";
    echo "Nom (brut): {$product->name}\n";
    
    // Essayer de décoder le JSON
    $decoded = json_decode($product->name, true);
    if ($decoded && is_array($decoded)) {
        echo "Nom décodé: " . ($decoded['fr'] ?? $decoded['en'] ?? $decoded['nl'] ?? 'N/A') . "\n";
    } else {
        echo "Nom simple: {$product->name}\n";
    }
    echo "---\n";
}

echo "\n=== Test de la logique JavaScript ===\n";

// Simuler la logique JavaScript
$testProduct = (object)[
    'name' => '{"fr":"Épandeur d\'Engrais","en":"Fertilizer Spreader","nl":"Meststrooier"}',
    'slug' => 'epandeur-engrais-correct'
];

echo "Produit test:\n";
echo "Nom: {$testProduct->name}\n";
echo "Slug: {$testProduct->slug}\n";

$nameObj = json_decode($testProduct->name, true);
if ($nameObj && is_array($nameObj)) {
    $locale = 'fr';
    $translatedName = $nameObj[$locale] ?? $nameObj['fr'] ?? $nameObj['en'] ?? $nameObj['nl'] ?? array_values($nameObj)[0] ?? 'Produit';
    echo "Nom traduit (fr): {$translatedName}\n";
} else {
    echo "Échec du décodage JSON\n";
}

?>
