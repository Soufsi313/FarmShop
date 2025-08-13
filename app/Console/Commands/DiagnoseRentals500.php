<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;

class DiagnoseRentals500 extends Command
{
    protected $signature = 'app:diagnose-rentals-500';
    protected $description = 'Diagnostic précis de l\'erreur 500 sur /rentals';

    public function handle()
    {
        $this->info('=== DIAGNOSTIC ERREUR 500 /RENTALS ===');

        try {
            // Test 1: Accès direct au contrôleur
            $this->info('1. Test contrôleur direct...');
            $request = Request::create('/rentals', 'GET');
            $controller = new \App\Http\Controllers\RentalController();
            $response = $controller->index($request);
            $this->info('✅ Contrôleur fonctionne');

            // Test 2: Test via route
            $this->info('2. Test via route...');
            $response = app('router')->dispatch($request);
            $statusCode = $response->getStatusCode();
            $this->info("Code de réponse: {$statusCode}");
            
            if ($statusCode === 200) {
                $this->info('✅ Route fonctionne');
            } else {
                $this->error("❌ Erreur route: {$statusCode}");
            }

        } catch (\Throwable $e) {
            $this->error('❌ ERREUR TROUVÉE:');
            $this->error("Type: " . get_class($e));
            $this->error("Message: " . $e->getMessage());
            $this->error("Fichier: " . $e->getFile() . ":" . $e->getLine());
            
            // Stack trace court
            $trace = explode("\n", $e->getTraceAsString());
            $this->error("Stack trace:");
            for ($i = 0; $i < min(3, count($trace)); $i++) {
                $this->error("  " . $trace[$i]);
            }
        }

        // Test 3: Vérifier les derniers logs d'erreur
        $this->info('3. Logs d\'erreur récents...');
        
        // Forcer l'écriture dans les logs pour tester
        \Illuminate\Support\Facades\Log::info('TEST DIAGNOSTIC RENTALS');
        
        $this->info('✅ Test terminé');
    }
}
