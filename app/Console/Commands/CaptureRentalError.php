<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\RentalController;

class CaptureRentalError extends Command
{
    protected $signature = 'app:capture-rental-error';
    protected $description = 'Capturer l\'erreur exacte sur /rentals';

    public function handle()
    {
        $this->info('=== CAPTURE D\'ERREUR RENTALS ===');

        try {
            // Test basique du contrôleur
            $this->info('1. Test du contrôleur...');
            $request = Request::create('/rentals', 'GET');
            $controller = new RentalController();
            
            $response = $controller->index($request);
            $this->info('✓ Contrôleur OK');
            
            // Test de rendu minimal
            $this->info('2. Test de rendu...');
            if ($response instanceof \Illuminate\View\View) {
                // Juste les premiers caractères pour éviter les problèmes de mémoire
                $html = $response->render();
                $this->info('✓ Rendu OK - Taille: ' . strlen($html) . ' chars');
                $this->info('✓ Début: ' . substr($html, 0, 100) . '...');
            }
            
        } catch (\Throwable $e) {
            $this->error('=== ERREUR TROUVÉE ===');
            $this->error('Type: ' . get_class($e));
            $this->error('Message: ' . $e->getMessage());
            $this->error('Fichier: ' . $e->getFile() . ':' . $e->getLine());
            
            // Stack trace limité
            $trace = explode("\n", $e->getTraceAsString());
            $this->error('Stack trace (5 premières lignes):');
            for ($i = 0; $i < min(5, count($trace)); $i++) {
                $this->error('  ' . $trace[$i]);
            }
        }

        // Vérifier l'état des logs d'erreur
        $this->info('3. Vérification logs...');
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $this->info('✓ Fichier de log existe');
            $this->info('Taille: ' . filesize($logFile) . ' bytes');
            
            // Juste les dernières lignes
            $lines = file($logFile);
            if ($lines) {
                $this->info('Dernières lignes du log:');
                $lastLines = array_slice($lines, -3);
                foreach ($lastLines as $line) {
                    $this->info('  ' . trim($line));
                }
            }
        } else {
            $this->warn('✗ Pas de fichier de log');
        }
        
        $this->info('=== FIN CAPTURE ===');
    }
}
