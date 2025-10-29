<?php

/**
 * RUNNER: Tous les tests d'integration
 * 
 * Execute tous les tests d'integration (scenarios reels et cas d'erreur)
 */

echo "\n";
echo "╔════════════════════════════════════════╗\n";
echo "║   TESTS INTEGRATION: CAS D'ERREUR     ║\n";
echo "╔════════════════════════════════════════╗\n";
echo "\n";

$startTime = microtime(true);
$allTestsPassed = true;

// Liste des tests a executer
$tests = [
    [
        'name' => 'Commande Panier Vide',
        'file' => __DIR__ . '/test_empty_cart_checkout.php',
        'description' => 'Verification blocage commande avec panier vide'
    ],
    [
        'name' => 'Produit Rupture Stock',
        'file' => __DIR__ . '/test_out_of_stock_product.php',
        'description' => 'Verification blocage ajout produit en rupture de stock'
    ],
    [
        'name' => 'Paiement Montant Invalide',
        'file' => __DIR__ . '/test_invalid_payment_amount.php',
        'description' => 'Verification validation montants de paiement'
    ],
    [
        'name' => 'Location Date Passee',
        'file' => __DIR__ . '/test_rental_past_date.php',
        'description' => 'Verification blocage location avec date de debut dans le passe'
    ],
    [
        'name' => 'Restrictions Retour Produits',
        'file' => __DIR__ . '/test_product_return_restrictions.php',
        'description' => 'Verification restrictions retour: produits non retournables, delai 14 jours, commandes mixtes'
    ]
];

// Executer chaque test
foreach ($tests as $index => $test) {
    $testNumber = $index + 1;
    echo "Test $testNumber/" . count($tests) . ": {$test['name']}\n";
    echo str_repeat('-', 60) . "\n";
    echo "Description: {$test['description']}\n";
    echo "\n";
    
    if (!file_exists($test['file'])) {
        echo "ERREUR: Fichier de test non trouve: {$test['file']}\n\n";
        $allTestsPassed = false;
        continue;
    }
    
    // Executer le test
    $output = [];
    $returnCode = 0;
    exec("php \"{$test['file']}\"", $output, $returnCode);
    
    // Afficher la sortie
    echo implode("\n", $output) . "\n";
    
    if ($returnCode !== 0) {
        $allTestsPassed = false;
        echo "\n>>> ECHEC du test: {$test['name']} <<<\n\n";
    } else {
        echo "\n>>> SUCCES du test: {$test['name']} <<<\n\n";
    }
    
    echo "\n";
}

// Resultats globaux
$duration = round((microtime(true) - $startTime), 2);

echo "╔════════════════════════════════════════╗\n";
echo "║         RESULTATS GLOBAUX              ║\n";
echo "╔════════════════════════════════════════╗\n";
echo "\n";

if ($allTestsPassed) {
    echo "STATUT: TOUS LES TESTS D'INTEGRATION ONT REUSSI\n";
    echo "\n";
    echo "Details:\n";
    echo "  - Tests executes: " . count($tests) . "\n";
    echo "  - Tests reussis: " . count($tests) . "\n";
    echo "  - Tests echoues: 0\n";
    echo "  - Duree totale: {$duration}s\n";
    echo "\n";
    echo "Le systeme gere correctement les cas d'erreur et scenarios invalides.\n";
    exit(0);
} else {
    echo "STATUT: CERTAINS TESTS ONT ECHOUE\n";
    echo "\n";
    echo "Veuillez verifier les erreurs ci-dessus.\n";
    echo "Duree totale: {$duration}s\n";
    exit(1);
}
