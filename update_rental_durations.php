<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

echo "🔧 Mise à jour des durées minimales de location\n";
echo "===============================================\n\n";

// Récupérer tous les produits de location
$rentalProducts = Product::where('type', 'rental')->orWhere('type', 'both')->get();

echo "📊 État actuel des produits de location:\n";
echo "----------------------------------------\n";
foreach ($rentalProducts as $product) {
    echo "- ID: {$product->id} | {$product->name} | Min: {$product->min_rental_days} jours | Max: {$product->max_rental_days} jours\n";
}
echo "\nTotal: {$rentalProducts->count()} produits\n\n";

// Mettre à jour tous les produits pour avoir une durée minimale de 1 jour
echo "🔄 Mise à jour en cours...\n";
echo "---------------------------\n";

$updated = 0;
foreach ($rentalProducts as $product) {
    if ($product->min_rental_days != 1) {
        $oldMin = $product->min_rental_days;
        $product->update(['min_rental_days' => 1]);
        echo "✅ {$product->name}: {$oldMin} → 1 jour\n";
        $updated++;
    } else {
        echo "⚪ {$product->name}: déjà à 1 jour\n";
    }
}

echo "\n📈 Résumé:\n";
echo "----------\n";
echo "Produits modifiés: {$updated}\n";
echo "Produits déjà à jour: " . ($rentalProducts->count() - $updated) . "\n";

echo "\n✅ Mise à jour terminée! Tous les produits de location ont maintenant une durée minimale de 1 jour.\n";
echo "🎯 Vous pouvez maintenant créer des locations d'un jour pour vos tests!\n";

?>
