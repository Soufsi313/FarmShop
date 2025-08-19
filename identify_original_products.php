<?php

require_once 'vendor/autoload.php';

use App\Models\Product;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== IDENTIFICATION DES PRODUITS ORIGINAUX VS AJOUTÉS ===\n\n";

// Grouper par date de création pour identifier les batches
echo "📅 PRODUITS PAR DATE DE CRÉATION:\n";
$productsByDate = Product::selectRaw('DATE(created_at) as creation_date, COUNT(*) as count')
    ->groupBy('creation_date')
    ->orderBy('creation_date')
    ->get();

foreach ($productsByDate as $dateGroup) {
    echo "- {$dateGroup->creation_date}: {$dateGroup->count} produits\n";
}

echo "\n🔍 PRODUITS CRÉÉS LE 2025-08-13 (probablement ajoutés récemment):\n";
$recentProducts = Product::whereDate('created_at', '2025-08-13')->get(['id', 'name', 'sku', 'type', 'created_at']);
echo "Nombre: {$recentProducts->count()}\n";

foreach ($recentProducts as $product) {
    echo "- ID {$product->id}: '{$product->name}' (SKU: {$product->sku}) | Type: {$product->type}\n";
}

echo "\n🎯 PRODUITS CRÉÉS AVANT LE 2025-08-13 (probablement originaux):\n";
$originalProducts = Product::whereDate('created_at', '<', '2025-08-13')->get(['id', 'name', 'sku', 'type', 'created_at']);
echo "Nombre: {$originalProducts->count()}\n";

if ($originalProducts->count() <= 20) {
    foreach ($originalProducts as $product) {
        echo "- ID {$product->id}: '{$product->name}' (SKU: {$product->sku}) | Type: {$product->type} | Créé: {$product->created_at}\n";
    }
} else {
    echo "Trop nombreux pour afficher individuellement.\n";
}

echo "\n📊 RÉSUMÉ:\n";
echo "- Produits probablement originaux: {$originalProducts->count()}\n";
echo "- Produits probablement ajoutés: {$recentProducts->count()}\n";
echo "- Total actuel: " . Product::count() . "\n";
echo "- Objectif (vos originaux): 84\n";
echo "- Différence: " . (Product::count() - 84) . " produits en trop\n";
