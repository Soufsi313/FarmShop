<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "=== TEST CORRECTION API ===\n\n";

// Simuler une requête HTTP POST vers l'API
$product = Product::find(272); // ID de la bâche directement

if (!$product) {
    echo "❌ Produit non trouvé\n";
    exit;
}

echo "Produit: {$product->name}\n";
echo "Max jours: " . ($product->max_rental_days ?? 'NULL') . "\n\n";

// Test avec différentes durées
$testCases = [
    ['start' => '2025-09-03', 'end' => '2025-09-03', 'days' => 1],
    ['start' => '2025-09-03', 'end' => '2025-09-07', 'days' => 5],
    ['start' => '2025-09-03', 'end' => '2025-11-01', 'days' => 60],
];

foreach ($testCases as $test) {
    echo "Test {$test['days']} jour(s) ({$test['start']} → {$test['end']}):\n";
    
    $startDate = \Carbon\Carbon::parse($test['start']);
    $endDate = \Carbon\Carbon::parse($test['end']);
    $days = $startDate->diffInDays($endDate) + 1;
    
    echo "  Durée calculée: {$days} jour(s)\n";
    
    // Simulation de la logique du contrôleur
    if ($days < $product->min_rental_days) {
        echo "  ❌ Erreur: Durée minimale de location : {$product->min_rental_days} jour(s)\n";
    } elseif ($product->max_rental_days !== null && $days > $product->max_rental_days) {
        echo "  ❌ Erreur: Durée maximale de location : {$product->max_rental_days} jour(s)\n";
    } else {
        echo "  ✅ Validation réussie\n";
    }
    echo "\n";
}

echo "=== FIN DU TEST ===\n";
