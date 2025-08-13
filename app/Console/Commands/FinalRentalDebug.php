<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FinalRentalDebug extends Command
{
    protected $signature = 'app:final-rental-debug';
    protected $description = 'Debug final pour identifier l\'erreur 500 exact';

    public function handle()
    {
        $this->info('=== DEBUG FINAL ERREUR 500 ===');

        try {
            // Test avec gestion d'erreur complète
            $this->info('1. Test avec capture d\'erreur...');
            
            $request = \Illuminate\Http\Request::create('/rentals', 'GET', []);
            
            // Simuler l'environnement web complet
            app()->instance('request', $request);
            
            $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
            $response = $kernel->handle($request);
            
            $statusCode = $response->getStatusCode();
            $this->info("Status code: {$statusCode}");
            
            if ($statusCode !== 200) {
                $content = $response->getContent();
                $this->error("Contenu de l'erreur:");
                $this->error(substr($content, 0, 500) . "...");
            } else {
                $this->info('🎉 SUCCESS! Page fonctionne!');
            }

        } catch (\Throwable $e) {
            $this->error('=== ERREUR CAPTURÉE ===');
            $this->error("Type: " . get_class($e));
            $this->error("Message: " . $e->getMessage());
            $this->error("Fichier: " . $e->getFile() . ":" . $e->getLine());
        }

        $this->info('=== FIN DEBUG ===');
    }
}
