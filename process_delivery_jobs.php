<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Artisan;

echo "=== Traitement automatique des jobs en attente ===\n";

// Traiter tous les jobs en attente une seule fois
try {
    Artisan::call('queue:work', [
        '--stop-when-empty' => true,  // S'arrêter quand la queue est vide
        '--timeout' => 60,
        '--tries' => 3
    ]);
    
    echo "Jobs traités avec succès !\n";
    echo Artisan::output();
} catch (Exception $e) {
    echo "Erreur lors du traitement des jobs: " . $e->getMessage() . "\n";
}

echo "=== Terminé ===\n";
