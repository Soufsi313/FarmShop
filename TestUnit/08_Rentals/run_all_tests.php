<?php
/**
 * Runner pour tous les tests Rentals
 * 
 * Execute tous les tests du systeme de location
 */

// Bootstrap Laravel une seule fois
require_once __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n";
echo "=============================================================\n";
echo "       FARMSHOP - TESTS UNITAIRES RENTALS SYSTEM           \n";
echo "                                                            \n";
echo "  Execution de tous les tests du systeme de location       \n";
echo "=============================================================\n";
echo "\n";

$startTime = microtime(true);
$tests = [
    'CartLocation Model' => __DIR__ . '/test_cart_location_model.php',
    'CartItemLocation Model' => __DIR__ . '/test_cart_item_location_model.php',
    'OrderItemLocation Model' => __DIR__ . '/test_order_item_location_model.php',
];

$results = [];

foreach ($tests as $testName => $testFile) {
    echo "-----------------------------------------------------------\n";
    echo "TEST: $testName\n";
    echo "-----------------------------------------------------------\n";
    
    $testStartTime = microtime(true);
    
    ob_start();
    try {
        include $testFile;
        $output = ob_get_clean();
        echo $output;
        
        // Determiner si le test a reussi
        $success = (strpos($output, 'TEST REUSSI') !== false);
        $results[$testName] = $success ? 'success' : 'failed';
    } catch (Exception $e) {
        $output = ob_get_clean();
        echo $output;
        echo "ERREUR: " . $e->getMessage() . "\n";
        $results[$testName] = 'failed';
    }
    
    $testEndTime = microtime(true);
    $testDuration = round($testEndTime - $testStartTime, 2);
    
    echo "\n";
}

$endTime = microtime(true);
$totalDuration = round($endTime - $startTime, 2);

// Resume final
echo "=============================================================\n";
echo "                    RESUME FINAL                            \n";
echo "=============================================================\n";
echo "\n";

$successCount = 0;
$failedCount = 0;

foreach ($results as $testName => $result) {
    $status = $result === 'success' ? 'REUSSI' : 'ECHOUE';
    echo "  $status - $testName\n";
    
    if ($result === 'success') {
        $successCount++;
    } else {
        $failedCount++;
    }
}

echo "\n";
echo "-------------------------------------------------------------\n";
echo "  Total tests: " . count($results) . "\n";
echo "  Reussis: $successCount\n";
echo "  Echoues: $failedCount\n";
echo "  Temps total: {$totalDuration}s\n";
echo "-------------------------------------------------------------\n";
echo "\n";

if ($failedCount === 0) {
    echo "TOUS LES TESTS RENTALS SYSTEM ONT REUSSI\n";
} else {
    echo "CERTAINS TESTS ONT ECHOUE\n";
}

echo "\n";
