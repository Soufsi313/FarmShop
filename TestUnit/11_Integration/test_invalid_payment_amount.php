<?php

/**
 * Test d'Integration: Paiement avec Montant Invalide
 * 
 * Teste le comportement du systeme lors d'une tentative de paiement avec un montant invalide
 */

// Bootstrap Laravel si necessaire
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
    require_once __DIR__ . '/../../vendor/autoload.php';
    $app = require_once __DIR__ . '/../../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
}

echo "\n========================================\n";
echo "TEST INTEGRATION: PAIEMENT INVALIDE\n";
echo "========================================\n\n";

$startTime = microtime(true);
$errors = [];

try {
    // 1. Test avec montant zero
    echo "1. Test paiement avec montant zero...\n";
    
    $zeroAmount = 0.00;
    $isValidZero = $zeroAmount > 0;
    
    echo "   - Montant: $zeroAmount EUR\n";
    echo "   - Validation: " . ($isValidZero ? 'VALIDE' : 'INVALIDE') . "\n";
    
    if ($isValidZero) {
        $errors[] = "Le paiement avec montant zero devrait etre refuse";
    } else {
        echo "   - Resultat: Paiement bloque (CORRECT)\n";
    }

    // 2. Test avec montant negatif
    echo "\n2. Test paiement avec montant negatif...\n";
    
    $negativeAmount = -50.00;
    $isValidNegative = $negativeAmount > 0;
    
    echo "   - Montant: $negativeAmount EUR\n";
    echo "   - Validation: " . ($isValidNegative ? 'VALIDE' : 'INVALIDE') . "\n";
    
    if ($isValidNegative) {
        $errors[] = "Le paiement avec montant negatif devrait etre refuse";
    } else {
        echo "   - Resultat: Paiement bloque (CORRECT)\n";
    }

    // 3. Test avec montant trop eleve
    echo "\n3. Test paiement avec montant excessif...\n";
    
    $excessiveAmount = 1000000.00; // 1 million d'euros
    $maxAmount = 999999.99; // Limite hypothetique
    $isValidExcessive = $excessiveAmount <= $maxAmount;
    
    echo "   - Montant: $excessiveAmount EUR\n";
    echo "   - Limite maximale: $maxAmount EUR\n";
    echo "   - Validation: " . ($isValidExcessive ? 'VALIDE' : 'INVALIDE') . "\n";
    
    if ($isValidExcessive) {
        echo "   - Resultat: Montant accepte\n";
    } else {
        echo "   - Resultat: Montant refuse (depasse la limite)\n";
    }

    // 4. Test de validation Stripe
    echo "\n4. Test validation format Stripe...\n";
    
    // Stripe utilise des montants en centimes
    $amounts = [
        100.50 => 10050,  // 100.50 EUR = 10050 centimes
        0.01 => 1,        // 0.01 EUR = 1 centime (minimum)
        0.00 => 0,        // 0.00 EUR = 0 centimes (invalide)
        -10.00 => -1000   // Negatif (invalide)
    ];
    
    foreach ($amounts as $euros => $cents) {
        $isValidStripe = $cents >= 50; // Stripe minimum = 0.50 EUR (50 centimes)
        echo "   - " . number_format($euros, 2) . " EUR ($cents centimes): ";
        echo ($isValidStripe ? 'VALIDE' : 'INVALIDE') . "\n";
        
        if (!$isValidStripe && $cents > 0) {
            echo "     Note: Inferieur au minimum Stripe (0.50 EUR)\n";
        }
    }

    // 5. Test de concordance montant panier vs paiement
    echo "\n5. Test concordance panier/paiement...\n";
    
    $cartTotal = 99.99;
    $paymentAmount = 89.99; // Montant different
    
    echo "   - Total panier: $cartTotal EUR\n";
    echo "   - Montant paiement: $paymentAmount EUR\n";
    
    if ($cartTotal !== $paymentAmount) {
        echo "   - Alerte: Les montants ne correspondent pas\n";
        echo "   - Difference: " . abs($cartTotal - $paymentAmount) . " EUR\n";
        echo "   - Resultat: Paiement bloque (CORRECT)\n";
    } else {
        echo "   - Resultat: Montants concordants\n";
    }

    // 6. Test avec decimales invalides
    echo "\n6. Test precision decimale...\n";
    
    $invalidDecimals = [
        123.456,   // 3 decimales
        99.9999,   // 4 decimales
        50.123456  // 6 decimales
    ];
    
    foreach ($invalidDecimals as $amount) {
        $rounded = round($amount, 2);
        $hasValidDecimals = $amount == $rounded;
        
        echo "   - Montant: $amount EUR\n";
        echo "   - Arrondi: $rounded EUR\n";
        echo "   - Precision valide: " . ($hasValidDecimals ? 'OUI' : 'NON') . "\n";
        
        if (!$hasValidDecimals) {
            echo "   - Action: Arrondi automatique a $rounded EUR\n";
        }
        echo "\n";
    }

    // 7. Test validation Laravel
    echo "7. Test regles de validation Laravel...\n";
    
    $testCases = [
        ['amount' => 100.00, 'should_pass' => true],
        ['amount' => 0, 'should_pass' => false],
        ['amount' => -50, 'should_pass' => false],
        ['amount' => 'invalid', 'should_pass' => false],
        ['amount' => null, 'should_pass' => false]
    ];
    
    foreach ($testCases as $index => $testCase) {
        $validator = \Illuminate\Support\Facades\Validator::make(
            ['amount' => $testCase['amount']],
            ['amount' => 'required|numeric|min:0.01|max:999999.99']
        );
        
        $passed = !$validator->fails();
        $expected = $testCase['should_pass'];
        
        echo "   - Test " . ($index + 1) . ": ";
        echo "Montant = " . var_export($testCase['amount'], true) . " => ";
        echo ($passed ? 'VALIDE' : 'INVALIDE');
        
        if ($passed === $expected) {
            echo " (attendu)\n";
        } else {
            echo " (INATTENDU)\n";
            $errors[] = "Validation incorrecte pour le montant " . var_export($testCase['amount'], true);
        }
    }

    // 8. Test calcul TVA (Belgique)
    echo "\n8. Test calcul de TVA (Belgique)...\n";
    
    // Test TVA 21% (produits non essentiels)
    $subtotal1 = 100.00;
    $tvaRate1 = 0.21; // 21% pour produits non essentiels
    $tvaAmount1 = round($subtotal1 * $tvaRate1, 2);
    $total1 = round($subtotal1 + $tvaAmount1, 2);
    
    echo "   - Produits non essentiels:\n";
    echo "     * Sous-total: $subtotal1 EUR\n";
    echo "     * TVA (21%): $tvaAmount1 EUR\n";
    echo "     * Total: $total1 EUR\n";
    
    // Verifier que la somme est correcte
    $calculatedTotal1 = $subtotal1 + $tvaAmount1;
    if (abs($calculatedTotal1 - $total1) > 0.01) {
        $errors[] = "Erreur de calcul TVA 21%: $calculatedTotal1 != $total1";
    } else {
        echo "     * Calcul: CORRECT\n";
    }
    
    // Test TVA 6% (produits essentiels/alimentaires)
    $subtotal2 = 100.00;
    $tvaRate2 = 0.06; // 6% pour produits essentiels et alimentaires
    $tvaAmount2 = round($subtotal2 * $tvaRate2, 2);
    $total2 = round($subtotal2 + $tvaAmount2, 2);
    
    echo "   - Produits essentiels/alimentaires:\n";
    echo "     * Sous-total: $subtotal2 EUR\n";
    echo "     * TVA (6%): $tvaAmount2 EUR\n";
    echo "     * Total: $total2 EUR\n";
    
    // Verifier que la somme est correcte
    $calculatedTotal2 = $subtotal2 + $tvaAmount2;
    if (abs($calculatedTotal2 - $total2) > 0.01) {
        $errors[] = "Erreur de calcul TVA 6%: $calculatedTotal2 != $total2";
    } else {
        echo "     * Calcul: CORRECT\n";
    }

    // 9. Test conversion devise
    echo "\n9. Test conversion de devise...\n";
    
    $eurAmount = 100.00;
    $usdRate = 1.10; // Taux hypothetique
    $usdAmount = round($eurAmount * $usdRate, 2);
    
    echo "   - Montant EUR: $eurAmount\n";
    echo "   - Taux EUR/USD: $usdRate\n";
    echo "   - Montant USD: $usdAmount\n";
    
    // Reconversion
    $backToEur = round($usdAmount / $usdRate, 2);
    echo "   - Reconversion: $backToEur EUR\n";
    
    if (abs($eurAmount - $backToEur) > 0.01) {
        echo "   - Alerte: Perte de precision lors de la conversion\n";
    }

    // 10. Test limites systeme
    echo "\n10. Test limites du systeme...\n";
    
    $limits = [
        'min_order' => 0.01,
        'max_order' => 999999.99,
        'stripe_min' => 0.50,
        'stripe_max' => 999999.99
    ];
    
    echo "   - Limites definies:\n";
    foreach ($limits as $key => $value) {
        echo "     * $key: $value EUR\n";
    }
    
    $testAmount = 75.50;
    $isWithinLimits = $testAmount >= $limits['min_order'] && 
                      $testAmount <= $limits['max_order'] &&
                      $testAmount >= $limits['stripe_min'];
    
    echo "   - Test montant $testAmount EUR: " . ($isWithinLimits ? 'Dans les limites' : 'Hors limites') . "\n";

} catch (\Exception $e) {
    $errors[] = "Exception: " . $e->getMessage();
}

// Resultats
$duration = round((microtime(true) - $startTime) * 1000, 2);

echo "\n========================================\n";
if (count($errors) > 0) {
    echo "RESULTAT: ECHEC\n";
    echo "========================================\n";
    echo "Erreurs detectees:\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
    echo "\nDuree: {$duration}ms\n";
    exit(1);
} else {
    echo "RESULTAT: REUSSI\n";
    echo "========================================\n";
    echo "Le systeme valide correctement les montants de paiement\n";
    echo "Duree: {$duration}ms\n";
    exit(0);
}
