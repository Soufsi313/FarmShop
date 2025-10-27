<?php
/**
 * LANCEUR DE TOUS LES TESTS DATABASE
 * 
 * Exécute tous les tests du dossier 01_Database dans l'ordre
 * 
 * Usage: php TestUnit/01_Database/run_all_tests.php
 */

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║          FARMSHOP - TESTS UNITAIRES DATABASE              ║\n";
echo "║                                                            ║\n";
echo "║  Exécution de tous les tests de base de données           ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

$startTime = microtime(true);
$testsPassed = 0;
$testsFailed = 0;

// Liste des tests à exécuter dans l'ordre
$tests = [
    'test_connection.php' => 'Connexion à la base de données',
    'test_tables.php' => 'Structure des tables',
    'test_migrations.php' => 'Migrations',
    'test_indexes.php' => 'Indexes et performance',
    'test_data_integrity.php' => 'Intégrité des données'
];

$testResults = [];

foreach ($tests as $testFile => $testName) {
    echo "┌─────────────────────────────────────────────────────────┐\n";
    echo "│ TEST: $testName\n";
    echo "└─────────────────────────────────────────────────────────┘\n";
    
    $testPath = __DIR__ . '/' . $testFile;
    
    if (!file_exists($testPath)) {
        echo "❌ Fichier de test introuvable: $testFile\n\n";
        $testsFailed++;
        $testResults[$testName] = 'ÉCHEC - Fichier introuvable';
        continue;
    }
    
    // Capturer la sortie du test
    ob_start();
    try {
        include $testPath;
        $output = ob_get_clean();
        echo $output;
        
        // Vérifier si le test a réussi (chercher "TEST RÉUSSI" dans la sortie)
        if (stripos($output, 'TEST RÉUSSI') !== false) {
            $testsPassed++;
            $testResults[$testName] = '✅ RÉUSSI';
        } elseif (stripos($output, 'PARTIELLEMENT RÉUSSI') !== false) {
            $testsPassed++;
            $testResults[$testName] = '⚠️  PARTIELLEMENT RÉUSSI';
        } else {
            $testsFailed++;
            $testResults[$testName] = '❌ ÉCHOUÉ';
        }
    } catch (Exception $e) {
        ob_end_clean();
        echo "❌ ERREUR lors de l'exécution: " . $e->getMessage() . "\n\n";
        $testsFailed++;
        $testResults[$testName] = '❌ EXCEPTION';
    }
    
    echo "\n";
}

$endTime = microtime(true);
$totalTime = round($endTime - $startTime, 2);

// Résumé final
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                    RÉSUMÉ FINAL                            ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

foreach ($testResults as $testName => $result) {
    echo "  $result - $testName\n";
}

echo "\n";
echo "─────────────────────────────────────────────────────────────\n";
echo "  Total tests: " . count($tests) . "\n";
echo "  ✅ Réussis: $testsPassed\n";
echo "  ❌ Échoués: $testsFailed\n";
echo "  ⏱️  Temps total: {$totalTime}s\n";
echo "─────────────────────────────────────────────────────────────\n";

if ($testsFailed === 0) {
    echo "\n🎉 TOUS LES TESTS DATABASE ONT RÉUSSI ! 🎉\n\n";
    exit(0);
} else {
    echo "\n⚠️  CERTAINS TESTS ONT ÉCHOUÉ - Vérifiez les détails ci-dessus\n\n";
    exit(1);
}
