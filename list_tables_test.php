<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Container\Container;

// Créer l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Lister les tables
try {
    echo "=== Tables dans la base de données ===\n";
    
    $tables = \DB::select('SHOW TABLES');
    foreach ($tables as $table) {
        foreach ($table as $key => $value) {
            echo "- " . $value . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
