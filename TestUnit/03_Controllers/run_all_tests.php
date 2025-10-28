<?php
/**
 * Runner pour tous les tests Controllers
 * 
 * Exécute tous les tests de validation des controllers
 */

// Bootstrap Laravel une seule fois
require_once __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║          FARMSHOP - TESTS UNITAIRES CONTROLLERS           ║\n";
echo "║                                                            ║\n";
echo "║  Exécution de tous les tests des controllers              ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

$startTime = microtime(true);
$tests = [
    'Product Controller' => __DIR__ . '/test_product_controller.php',
    'Cart Controller' => __DIR__ . '/test_cart_controller.php',
    'Order Controller' => __DIR__ . '/test_order_controller.php',
    'Admin Dashboard Controller' => __DIR__ . '/test_admin_dashboard_controller.php',
];

$results = [];

foreach ($tests as $testName => $testFile) {
    echo "┌─────────────────────────────────────────────────────────┐\n";
    echo "│ TEST: $testName\n";
    echo "└─────────────────────────────────────────────────────────┘\n";
    
    $testStartTime = microtime(true);
    
    ob_start();
    try {
        include $testFile;
        $output = ob_get_clean();
        echo $output;
        
        // Déterminer si le test a réussi
        $success = (strpos($output, 'TEST RÉUSSI ✅') !== false);
        $results[$testName] = $success ? 'success' : 'failed';
    } catch (Exception $e) {
        $output = ob_get_clean();
        echo $output;
        echo "❌ ERREUR: " . $e->getMessage() . "\n";
        $results[$testName] = 'failed';
    }
    
    $testEndTime = microtime(true);
    $testDuration = round($testEndTime - $testStartTime, 2);
    
    echo "\n";
}

$endTime = microtime(true);
$totalDuration = round($endTime - $startTime, 2);

// Résumé final
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                    RÉSUMÉ FINAL                            ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

$successCount = 0;
$failedCount = 0;

foreach ($results as $testName => $result) {
    $icon = $result === 'success' ? '✅' : '❌';
    $status = $result === 'success' ? 'RÉUSSI' : 'ÉCHOUÉ';
    echo "  $icon $status - $testName\n";
    
    if ($result === 'success') {
        $successCount++;
    } else {
        $failedCount++;
    }
}

echo "\n";
echo "─────────────────────────────────────────────────────────────\n";
echo "  Total tests: " . count($results) . "\n";
echo "  ✅ Réussis: $successCount\n";
echo "  ❌ Échoués: $failedCount\n";
echo "  ⏱️  Temps total: {$totalDuration}s\n";
echo "─────────────────────────────────────────────────────────────\n";
echo "\n";

if ($failedCount === 0) {
    echo "🎉 TOUS LES TESTS CONTROLLERS ONT RÉUSSI ! 🎉\n";
} else {
    echo "⚠️  CERTAINS TESTS ONT ÉCHOUÉ ⚠️\n";
}

echo "\n";
