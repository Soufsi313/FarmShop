<?php

require_once 'vendor/autoload.php';

use App\Models\Product;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VÉRIFICATION SPÉCIFIQUE DES IDs 81-84 ===\n\n";

for ($id = 81; $id <= 84; $id++) {
    $product = Product::find($id);
    if ($product) {
        echo "ID {$id}: '{$product->name}' (SKU: {$product->sku}) | Type: {$product->type} | Catégorie: {$product->category_id}\n";
    } else {
        echo "ID {$id}: PRODUIT NON TROUVÉ\n";
    }
}

echo "\n=== VOTRE VRAIE QUESTION ===\n";
echo "Vous dites avoir 84 produits originaux dans votre base de données.\n";
echo "Actuellement, nous en avons 102.\n";
echo "La différence est de 18 produits en trop.\n\n";

echo "🤔 POSSIBILITÉS:\n";
echo "1. Vos 84 originaux sont les IDs 1-84 (en ignorant les manquants 30, 49, 85)\n";
echo "2. Les 20 produits de location (IDs 86-105) sont des ajouts indésirables\n";
echo "3. Vous voulez revenir EXACTEMENT à vos 84 produits d'origine\n\n";

echo "📊 COMPTAGE ACTUEL PAR PLAGE D'IDs:\n";
$range1to84 = Product::whereBetween('id', [1, 84])->count();
$range86to105 = Product::where('id', '>=', 86)->count();

echo "- IDs 1-84: {$range1to84} produits\n";
echo "- IDs 86+: {$range86to105} produits\n";
echo "- Total: " . ($range1to84 + $range86to105) . "\n\n";

echo "❓ QUESTION CRUCIALE:\n";
echo "Voulez-vous que je:\n";
echo "A) Garde seulement les IDs 1-84 (moins les manquants) = 81 produits\n";
echo "B) Supprime les 20 produits de location pour revenir à ~82 produits\n";
echo "C) Autre stratégie ?\n\n";

echo "Pour vous aider à décider, voici les produits de location qui seraient supprimés:\n";
$locationProducts = Product::where('id', '>=', 86)->get(['id', 'name', 'type']);
foreach ($locationProducts as $product) {
    echo "- ID {$product->id}: '{$product->name}' ({$product->type})\n";
}
