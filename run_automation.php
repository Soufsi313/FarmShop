<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Log avec timestamp
$timestamp = now()->format('Y-m-d H:i:s');
echo "[{$timestamp}] Exécution automatique de l'automatisation\n";

try {
    // Exécuter la commande d'automatisation
    $exitCode = \Artisan::call('orders:update-status');
    $output = \Artisan::output();
    
    // Log seulement si il y a eu des mises à jour
    if (strpos($output, '✅') !== false) {
        echo $output;
        // Optionnel : écrire dans un fichier de log
        file_put_contents(__DIR__ . '/automation.log', "[{$timestamp}] " . $output . "\n", FILE_APPEND);
    } else {
        echo "Aucune commande à mettre à jour\n";
    }
    
} catch (\Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    file_put_contents(__DIR__ . '/automation_errors.log', "[{$timestamp}] Erreur: " . $e->getMessage() . "\n", FILE_APPEND);
}
