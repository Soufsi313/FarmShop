<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "=== VÉRIFICATION SUPPRESSION PRODUIT 294 ===\n";

$product = Product::find(294);

if ($product) {
    echo "❌ Le produit existe encore:\n";
    echo "ID: {$product->id}\n";
    echo "Nom: {$product->name}\n";
} else {
    echo "✅ SUCCÈS ! Le produit ID 294 a été supprimé.\n";
}

echo "\n=== VÉRIFICATION DES AUTRES PRODUITS DE LAIT ===\n";
$laitProducts = Product::where('name', 'LIKE', '%Lait%')->get();
foreach ($laitProducts as $p) {
    echo "ID: {$p->id} - {$p->name}\n";
}
