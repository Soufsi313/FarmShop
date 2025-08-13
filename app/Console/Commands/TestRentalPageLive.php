<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\RentalController;

class TestRentalPageLive extends Command
{
    protected $signature = 'app:test-rental-page-live';
    protected $description = 'Simuler une vraie requête sur /rentals pour capturer l\'erreur 500';

    public function handle()
    {
        $this->info('=== TEST COMPLET DE LA PAGE /RENTALS ===');

        try {
            // Simuler une vraie requête HTTP
            $this->info('1. Simulation d\'une requête GET /rentals...');
            
            $request = Request::create('/rentals', 'GET');
            $controller = new RentalController();
            
            // Capturer tous les logs d'erreur
            Log::info('=== DÉBUT TEST RENTAL PAGE ===');
            
            $response = $controller->index($request);
            
            $this->info('✓ Requête traitée sans exception PHP');
            
            if ($response instanceof \Illuminate\View\View) {
                $this->info('✓ Vue retournée: ' . $response->name());
                
                // Tenter de rendre la vue
                $this->info('2. Test de rendu de la vue...');
                $html = $response->render();
                $this->info('✓ Vue rendue avec succès (' . strlen($html) . ' caractères)');
                
                // Vérifier le contenu
                if (strpos($html, 'Nos Locations') !== false) {
                    $this->info('✓ Contenu principal trouvé');
                } else {
                    $this->warn('⚠ Contenu principal non trouvé');
                }
                
            } else {
                $this->error('✗ Réponse inattendue: ' . get_class($response));
            }

        } catch (\Exception $e) {
            $this->error('=== ERREUR CAPTURÉE ===');
            $this->error('Type: ' . get_class($e));
            $this->error('Message: ' . $e->getMessage());
            $this->error('Fichier: ' . $e->getFile() . ':' . $e->getLine());
            $this->error('Trace:');
            $this->error($e->getTraceAsString());
            
            Log::error('Erreur sur page rentals', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        // Vérifier les logs récents
        $this->info('3. Vérification des logs d\'erreur...');
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            $logs = file_get_contents($logPath);
            $recentLogs = substr($logs, -2000); // Derniers 2000 caractères
            
            if (strpos($recentLogs, 'ERROR') !== false || strpos($recentLogs, 'Exception') !== false) {
                $this->warn('⚠ Erreurs trouvées dans les logs récents:');
                $lines = explode("\n", $recentLogs);
                $errorLines = array_filter($lines, function($line) {
                    return strpos($line, 'ERROR') !== false || strpos($line, 'Exception') !== false;
                });
                
                foreach (array_slice($errorLines, -5) as $errorLine) {
                    $this->error('  ' . $errorLine);
                }
            } else {
                $this->info('✓ Aucune erreur récente dans les logs');
            }
        }

        $this->info('=== FIN DU TEST ===');
    }
}
