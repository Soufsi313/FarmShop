<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== STRUCTURE TABLE PRODUCTS ===\n\n";

$columns = DB::select('DESCRIBE products');
foreach ($columns as $column) {
    echo "- {$column->Field} ({$column->Type})\n";
}

echo "\n=== PRODUIT ID 102 ===\n";
$product = DB::table('products')->where('id', 102)->first();
if ($product) {
    foreach ((array)$product as $key => $value) {
        echo "- {$key}: {$value}\n";
    }
} else {
    echo "Produit non trouvé\n";
}

echo "\n=== STOCK AVANT COMMANDE LOC-202508139827 ===\n";
// Regarder les logs pour voir l'état du stock avant la commande
echo "Vérification dans les logs...\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $logs = file_get_contents($logFile);
    
    // Chercher les logs de stock pour le produit 102
    $lines = explode("\n", $logs);
    $stockLogs = [];
    
    foreach ($lines as $line) {
        if (strpos($line, 'Stock de location') !== false && strpos($line, 'product_id":102') !== false) {
            $stockLogs[] = $line;
        }
    }
    
    if (!empty($stockLogs)) {
        echo "Logs de stock trouvés:\n";
        foreach ($stockLogs as $log) {
            echo $log . "\n";
        }
    } else {
        echo "Aucun log de stock trouvé pour le produit 102\n";
    }
}
