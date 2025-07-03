<?php

/**
 * Script d'automatisation continue - PRODUCTION
 * Exécute la commande orders:update-status toutes les 45 secondes
 * Ce script doit tourner en continu en arrière-plan
 */

echo "=== AUTOMATISATION CONTINUE - PRODUCTION ===\n";
echo "Démarrage à " . date('d/m/Y H:i:s') . "\n";
echo "Interval: 45 secondes\n";
echo "Appuyez sur Ctrl+C pour arrêter\n\n";

$iteration = 1;

while (true) {
    echo "[" . date('H:i:s') . "] === Itération #{$iteration} ===\n";
    
    // Exécuter la commande artisan sécurisée
    $command = 'php artisan orders:safe-update-status --no-email'; // Désactivé par défaut pour éviter le spam
    $output = [];
    $returnCode = 0;
    
    exec($command, $output, $returnCode);
    
    if ($returnCode === 0) {
        foreach ($output as $line) {
            if (!empty(trim($line))) {
                echo "   " . $line . "\n";
            }
        }
        echo "   ✅ Automatisation exécutée avec succès\n";
    } else {
        echo "   ❌ Erreur lors de l'exécution (code: {$returnCode})\n";
        foreach ($output as $line) {
            echo "   ERROR: " . $line . "\n";
        }
    }
    
    echo "   ⏰ Pause de 45 secondes...\n\n";
    
    // Attendre 45 secondes
    sleep(45);
    
    $iteration++;
}
