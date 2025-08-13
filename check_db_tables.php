<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== STRUCTURE DE LA BASE DE DONNÉES ===\n\n";

// Lister toutes les tables
$tables = DB::select('SHOW TABLES');
echo "📋 TABLES DISPONIBLES:\n";
foreach ($tables as $table) {
    $tableName = array_values((array)$table)[0];
    echo "- {$tableName}\n";
}

echo "\n=== RECHERCHE TABLES LOCATION ===\n";
foreach ($tables as $table) {
    $tableName = array_values((array)$table)[0];
    if (str_contains($tableName, 'location') || str_contains($tableName, 'order')) {
        echo "🎯 {$tableName}\n";
        
        // Afficher la structure
        $columns = DB::select("DESCRIBE {$tableName}");
        foreach ($columns as $column) {
            echo "   - {$column->Field} ({$column->Type})\n";
        }
        echo "\n";
    }
}
