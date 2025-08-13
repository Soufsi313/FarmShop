<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearLogs extends Command
{
    protected $signature = 'app:clear-logs';
    protected $description = 'Vider les fichiers de logs surchargés';

    public function handle()
    {
        $this->info('=== NETTOYAGE DES LOGS ===');

        $logFile = storage_path('logs/laravel.log');
        
        if (file_exists($logFile)) {
            $size = filesize($logFile);
            $this->info("Taille actuelle du log: " . number_format($size / 1024 / 1024, 2) . " MB");
            
            // Vider le fichier
            file_put_contents($logFile, '');
            $this->info('✅ Fichier de log vidé');
            
            // Écrire un message de démarrage
            file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . '] local.INFO: === LOGS NETTOYÉS ===' . PHP_EOL);
            $this->info('✅ Message de démarrage ajouté');
            
        } else {
            $this->info('ℹ Aucun fichier de log trouvé');
        }
        
        // Nettoyer aussi les autres logs possibles
        $logDir = storage_path('logs');
        $files = glob($logDir . '/*.log');
        
        foreach ($files as $file) {
            if (basename($file) !== 'laravel.log' && filesize($file) > 100 * 1024 * 1024) { // Plus de 100MB
                $this->info('Nettoyage de: ' . basename($file));
                file_put_contents($file, '');
            }
        }
        
        $this->info('=== NETTOYAGE TERMINÉ ===');
    }
}
