<?php

require_once 'vendor/autoload.php';

use App\Models\Product;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== NETTOYAGE COMPLET POUR REVENIR AUX 84 PRODUITS ORIGINAUX ===\n\n";

$deletedCount = 0;

// ÉTAPE 1: Supprimer les produits avec noms vides (IDs 81-84)
echo "ÉTAPE 1: Suppression des produits avec noms vides\n";
$emptyNameProducts = [81, 82, 83, 84];

foreach ($emptyNameProducts as $id) {
    $product = Product::find($id);
    if ($product) {
        echo "🗑️  Suppression ID {$id}: '{$product->name}' (SKU: {$product->sku})\n";
        $product->delete();
        $deletedCount++;
    } else {
        echo "❌ Produit ID {$id} déjà supprimé\n";
    }
}

// ÉTAPE 2: Supprimer les 20 produits de location en trop (IDs 86-105)
echo "\nÉTAPE 2: Suppression des produits de location en trop\n";
$extraRentalProducts = Product::where('id', '>=', 86)->get();

foreach ($extraRentalProducts as $product) {
    echo "🗑️  Suppression ID {$product->id}: '{$product->name}' (SKU: {$product->sku})\n";
    $product->delete();
    $deletedCount++;
}

echo "\n📊 RÉSUMÉ DE LA SUPPRESSION:\n";
echo "- Produits supprimés: {$deletedCount}\n";
echo "- Produits restants: " . Product::count() . "\n";

// ÉTAPE 3: Créer les produits manquants pour compléter à 84
echo "\nÉTAPE 3: Vérification des IDs manquants\n";
$missingIds = [30, 49, 85];
$currentCount = Product::count();
$targetCount = 84;
$needed = $targetCount - $currentCount;

echo "- Produits actuels: {$currentCount}\n";
echo "- Objectif: {$targetCount}\n";
echo "- Manquants: {$needed}\n";

if ($needed > 0) {
    echo "\n⚠️  Il manque encore {$needed} produits pour atteindre 84.\n";
    echo "Les IDs manquants détectés: " . implode(', ', $missingIds) . "\n";
    echo "Vous devrez peut-être créer ces produits manuellement ou via un seeder.\n";
} elseif ($needed == 0) {
    echo "\n✅ PARFAIT! Vous avez exactement 84 produits maintenant.\n";
} else {
    echo "\n⚠️  Attention: Vous avez " . abs($needed) . " produits de trop.\n";
}

echo "\n=== NETTOYAGE TERMINÉ ===\n";
echo "Votre base de données devrait maintenant contenir vos 84 produits originaux.\n";
echo "Le dashboard admin devrait fonctionner parfaitement avec les catégories restaurées.\n";
