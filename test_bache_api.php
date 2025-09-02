<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Rules\RentalDateValidation;
use Carbon\Carbon;

echo "=== TEST API CALCUL BÂCHE ===\n\n";

$product = Product::find(272); // ID de la bâche

echo "Produit: {$product->name}\n";
echo "Slug: {$product->slug}\n";
echo "Min: {$product->min_rental_days}\n";
echo "Max: " . ($product->max_rental_days ?? 'NULL') . "\n\n";

// Simuler une requête API
$startDate = Carbon::now()->addDay();
$endDate = Carbon::now()->addDays(5);

echo "Test période: {$startDate->format('Y-m-d')} → {$endDate->format('Y-m-d')} (5 jours)\n\n";

// 1. Test validateRentalPeriod du modèle
echo "1. Test validateRentalPeriod:\n";
$validation = $product->validateRentalPeriod($startDate, $endDate);
echo "  Valide: " . ($validation['valid'] ? 'OUI' : 'NON') . "\n";
if (!$validation['valid']) {
    echo "  Erreurs:\n";
    foreach ($validation['errors'] as $error) {
        echo "    - {$error}\n";
    }
}

// 2. Test de la règle RentalDateValidation
echo "\n2. Test RentalDateValidation:\n";
try {
    $rule = new RentalDateValidation($product, $startDate, null, 'end');
    echo "  Instanciation: ✅\n";
    
    // Simuler la validation
    $errors = [];
    $rule->validate('end_date', $endDate->format('Y-m-d'), function($message) use (&$errors) {
        $errors[] = $message;
    });
    
    if (empty($errors)) {
        echo "  Validation: ✅ Aucune erreur\n";
    } else {
        echo "  Validation: ❌ Erreurs détectées:\n";
        foreach ($errors as $error) {
            echo "    - {$error}\n";
        }
    }
} catch (Exception $e) {
    echo "  ❌ Exception: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DU TEST ===\n";
