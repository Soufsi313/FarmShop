<?php

/**
 * RUNNER: Tous les tests Email (Mailable + Notification + Templates)
 * 
 * Execute tous les tests du systeme email
 */

echo "\n";
echo "╔════════════════════════════════════════╗\n";
echo "║   TESTS UNITAIRES: SYSTEME EMAIL      ║\n";
echo "╔════════════════════════════════════════╗\n";
echo "\n";

$startTime = microtime(true);
$allTestsPassed = true;

// Liste des tests a executer
$tests = [
    [
        'name' => 'Classes Mailable',
        'file' => __DIR__ . '/test_mailable_classes.php',
        'description' => 'Verification des classes d\'emails (Mailable)'
    ],
    [
        'name' => 'Classes Notification',
        'file' => __DIR__ . '/test_notification_classes.php',
        'description' => 'Verification des classes de notification'
    ],
    [
        'name' => 'Templates Email',
        'file' => __DIR__ . '/test_email_templates.php',
        'description' => 'Verification des templates Blade d\'email'
    ]
];

// Executer chaque test
foreach ($tests as $index => $test) {
    $testNumber = $index + 1;
    echo "Test $testNumber/{count($tests)}: {$test['name']}\n";
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
    echo "STATUT: TOUS LES TESTS EMAIL ONT REUSSI\n";
    echo "\n";
    echo "Details:\n";
    echo "  - Tests executes: " . count($tests) . "\n";
    echo "  - Tests reussis: " . count($tests) . "\n";
    echo "  - Tests echoues: 0\n";
    echo "  - Duree totale: {$duration}s\n";
    echo "\n";
    echo "Le systeme email (Mailable, Notification, Templates) fonctionne correctement.\n";
    exit(0);
} else {
    echo "STATUT: CERTAINS TESTS ONT ECHOUE\n";
    echo "\n";
    echo "Veuillez verifier les erreurs ci-dessus.\n";
    echo "Duree totale: {$duration}s\n";
    exit(1);
}
