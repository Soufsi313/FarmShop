<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Rules\RentalDateValidation;
use Carbon\Carbon;

echo "=== TEST DE L'API DE CALCUL DE COÛT ===\n\n";

// Prendre un produit de location
$testProduct = Product::where('type', 'rental')->first();

if (!$testProduct) {
    echo "❌ Aucun produit de location trouvé\n";
    exit;
}

echo "Test avec le produit: {$testProduct->name}\n";
echo "  - Min jours: {$testProduct->min_rental_days}\n";
echo "  - Max jours: " . ($testProduct->max_rental_days ?? 'NULL (pas de limite)') . "\n";
echo "  - Prix/jour: {$testProduct->rental_price_per_day}€\n\n";

// Test de la règle de validation directement
echo "1. Test de la règle RentalDateValidation:\n";

$startDate = Carbon::now()->addDay();
$endDate = Carbon::now()->addDays(5);

echo "  Test avec une période de 5 jours ({$startDate->format('Y-m-d')} → {$endDate->format('Y-m-d')}):\n";

try {
    $validation = $testProduct->validateRentalPeriod($startDate, $endDate);
    if ($validation['valid']) {
        echo "    ✅ Validation réussie\n";
    } else {
        echo "    ❌ Erreurs de validation:\n";
        foreach ($validation['errors'] as $error) {
            echo "      - {$error}\n";
        }
    }
} catch (Exception $e) {
    echo "    ❌ Exception: " . $e->getMessage() . "\n";
}

// Test avec une période plus longue
$endDateLong = Carbon::now()->addDays(100);
echo "\n  Test avec une période de 100 jours ({$startDate->format('Y-m-d')} → {$endDateLong->format('Y-m-d')}):\n";

try {
    $validationLong = $testProduct->validateRentalPeriod($startDate, $endDateLong);
    if ($validationLong['valid']) {
        echo "    ✅ Validation réussie (devrait passer maintenant car pas de limite max)\n";
    } else {
        echo "    ❌ Erreurs de validation:\n";
        foreach ($validationLong['errors'] as $error) {
            echo "      - {$error}\n";
        }
    }
} catch (Exception $e) {
    echo "    ❌ Exception: " . $e->getMessage() . "\n";
}

echo "\n2. Test de la règle de validation Laravel:\n";

try {
    // Test avec des chaînes de dates
    $rule = new RentalDateValidation($testProduct, $startDate, null, 'end');
    
    echo "  Test de l'instanciation: ✅ Réussie\n";
    
    // Test de validation (nous ne pouvons pas facilement simuler le callback closure ici)
    echo "  Note: Le test complet nécessiterait une requête HTTP complète\n";
    
} catch (Exception $e) {
    echo "  ❌ Erreur lors de l'instanciation: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DU TEST ===\n";
