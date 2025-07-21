<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Database\Seeders\FeculentsSeeder;

echo "=== EXÉCUTION DU SEEDER FÉCULENTS ===\n";

try {
    $seeder = new FeculentsSeeder();
    
    // Simuler la commande pour les messages
    $seeder->command = new class {
        public function info($message) {
            echo $message . "\n";
        }
        
        public function warn($message) {
            echo "WARNING: " . $message . "\n";
        }
        
        public function error($message) {
            echo "ERROR: " . $message . "\n";
        }
    };
    
    $seeder->run();
    
    echo "\n=== SEEDER EXÉCUTÉ AVEC SUCCÈS ===\n";
    
} catch (Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
