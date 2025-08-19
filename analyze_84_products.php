<?php

require_once 'vendor/autoload.php';

use App\Models\Product;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ANALYSE DÉTAILLÉE POUR IDENTIFIER LES 84 VRAIS PRODUITS ===\n\n";

// Vérifier les IDs manquants et les noms vides
echo "🔍 PRODUITS AVEC PROBLÈMES:\n";

$emptyNameProducts = Product::where('name', '')->orWhereNull('name')->get(['id', 'name', 'sku', 'type']);
echo "Produits avec noms vides:\n";
foreach ($emptyNameProducts as $product) {
    echo "- ID {$product->id}: '{$product->name}' (SKU: {$product->sku}) | Type: {$product->type}\n";
}

echo "\n📋 ANALYSE DE LA SÉQUENCE D'IDs:\n";
$maxId = Product::max('id');
$missingIds = [];
for ($i = 1; $i <= $maxId; $i++) {
    if (!Product::find($i)) {
        $missingIds[] = $i;
    }
}
echo "IDs manquants: " . implode(', ', $missingIds) . "\n";

echo "\n🎯 HYPOTHÈSE: VOS 84 VRAIS PRODUITS:\n";
echo "- IDs 1-84 MOINS les 4 avec noms vides (81-84) = 80 produits valides\n";
echo "- IDs manquants (30, 49, 85) = 3 produits\n";
echo "- Total produits valides dans la plage 1-84: 80\n";
echo "- Il manque encore 4 produits pour arriver à 84...\n";

echo "\n❓ QUESTION: Les produits de location (IDs 86-105) sont-ils des ajouts indésirables ?\n";
$rentalProducts = Product::where('id', '>=', 86)->where('type', 'rental')->get(['id', 'name', 'sku']);
echo "Produits de location (IDs 86+): {$rentalProducts->count()}\n";
foreach ($rentalProducts->take(5) as $product) {
    echo "- ID {$product->id}: '{$product->name}' (SKU: {$product->sku})\n";
}
if ($rentalProducts->count() > 5) {
    echo "- ... et " . ($rentalProducts->count() - 5) . " autres\n";
}

echo "\n💡 PROPOSITION DE NETTOYAGE:\n";
echo "1. Supprimer les produits avec noms vides (IDs 81-84)\n";
echo "2. Garder les IDs 1-80 + quelques autres pour arriver à 84\n";
echo "3. Décider quoi faire avec les produits de location (86-105)\n";

$validProducts = Product::whereNotIn('id', [81, 82, 83, 84])->where('name', '!=', '')->count();
echo "\nProduits actuellement valides (avec noms): {$validProducts}\n";
